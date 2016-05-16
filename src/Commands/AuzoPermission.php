<?php

namespace Kordy\Auzo\Commands;

use AuzoRole as AuzoRoleFacade;
use Illuminate\Console\Command;

class AuzoPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auzo:permission 
                            {operation : operation to be done the permission model} 
                            {role : role name}
                            {abilities : abilities names or ids separated by ,}
                            {--policies= : policies ids for the give operation separated by ,}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auzo permissions management';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $operation = $this->argument('operation');
        $role = $this->argument('role');
        $abilities = $this->extractAbilities($this->argument('abilities'));

        switch ($operation) {
            case 'give':
                $this->give($role, $abilities);
                break;
            case 'remove':
                $this->remove($role, $abilities);
                break;
        }
    }

    /**
     * Give ability permission to a role
     *
     * @param $role
     * @param $abilities
     */
    private function give($role, $abilities)
    {
        $policies = $this->option('policies');

        $role = AuzoRoleFacade::findByNameOrId($role)->first();

        $permissions = $role->givePermissionTo($abilities);

        if ($policies) {
            $policies = $this->extractPolicies($policies);
        }

        foreach ($permissions as $permission) {
            foreach ($policies as $policy) {
                $permission->addPolicy($policy);
            }
        }

        foreach ($abilities as $ability) {
            $this->info("$role is assigned $ability.");
        }
    }

    private function remove($role, $abilities)
    {
        $no_interaction = $this->option('no-interaction');

        $role = AuzoRoleFacade::findByNameOrId($role)->first();

        if ($no_interaction || $this->confirm("$role is going to be deleted. Do you wish to continue? [y|N]")) {

            $role->removePermissionTo($abilities);

            foreach ($abilities as $ability) {
                $this->info("$role is revoked from $ability.");
            }
        }
    }

    /**
     * @param $policies
     * @return array|int
     */
    private function extractPolicies($policies)
    {
        if (strpos($policies, ',')) {
            $final_policies = [];
            foreach (explode(",", $policies) as $policy) {
                if (strpos($policy, ':')) {
                    list($id, $operator) = explode(":", $policy);
                    $final_policies[] = [ (int)$id => ['operator' => $operator] ];
                } else {
                    $final_policies[] = (int)$policy;
                }

            }
        } else {
            $final_policies[] = (int)$policies;
        }

        return $final_policies;
    }

    private function extractAbilities($abilities)
    {
        if (strpos($abilities, ',')) {
            $final_abilities = [];
            foreach (explode(",", $abilities) as $ability) {
                $final_abilities[] = $ability;
            }
        } else {
            $final_abilities[] = $abilities;
        }

        return $final_abilities;
    }
}
