<?php

namespace Kordy\Auzo\Commands;

use AuzoRole as AuzoRoleFacade;
use Illuminate\Console\Command;

class AuzoRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auzo:role 
                            {operation : operation to be done the role model} 
                            {role : role name for the create operation}
                            {--description= : role description for the create operation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auzo roles management';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $operation = $this->argument('operation');
        $role = $this->argument('role');
        $description = $this->option('description');

        switch ($operation) {
            case 'create':
                $this->create($role, $description);
                break;
            case 'delete':
                $this->delete($role);
                break;
        }
    }

    private function create($role, $description)
    {
        AuzoRoleFacade::create([
            'name'        => $role,
            'description' => $description,
        ]);

        $this->info("$role is created.");
    }

    private function delete($role)
    {
        $no_interaction = $this->option('no-interaction');

        if ($no_interaction || $this->confirm("$role is going to be deleted. Do you wish to continue? [y|N]")) {
            AuzoRoleFacade::findByNameOrId($role)->delete();

            $this->info("$role is deleted.");
        }
    }
}
