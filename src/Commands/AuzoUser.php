<?php

namespace Kordy\Auzo\Commands;

use AuzoUser as AuzoUserFacade;
use Illuminate\Console\Command;

class AuzoUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auzo:user 
                            {operation : operation to be done the user model (assign|revoke)} 
                            {users : users ids separated by ,}
                            {role : role id or name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auzo users role assignment management';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $operation = $this->argument('operation');
        $users = $this->extractArgument($this->argument('users'));
        $role = $this->argument('role');

        switch ($operation) {
            case 'assign':
                $this->assign($users, $role);
                break;
            case 'revoke':
                $this->revoke($users, $role);
                break;
        }
    }

    /**
     * Assign role to users.
     *
     * @param $role
     * @param $users
     */
    private function assign($users, $role)
    {
        foreach ($users as $user) {
            $user = AuzoUserFacade::findOrFail($user);
            $user->assignRole($role);
            $this->info("user $user is assigned role $role.");
        }
    }

    private function revoke($users, $role)
    {
        $no_interaction = $this->option('no-interaction');

        if ($no_interaction || $this->confirm(
                'user(s) '.implode(',', $users)." is going to be revoked from role $role. Do you wish to continue? [y|N]"
            )) {
            foreach ($users as $user) {
                $user = AuzoUserFacade::findOrFail($user)->revokeRole($role);
                $this->info("user $user is revoked from role $role.");
            }
        }
    }

    /**
     * @param $users
     *
     * @return array|int
     */
    private function extractArgument($users)
    {
        if (strpos($users, ',')) {
            $final_users = [];
            foreach (explode(',', $users) as $user) {
                $final_users[] = (int) $user;
            }
        } else {
            $final_users[] = (int) $users;
        }

        return $final_users;
    }
}
