<?php

namespace Kordy\Auzo\Commands;

use AuzoPolicy as AuzoPolicyFacade;
use Illuminate\Console\Command;

class AuzoPolicy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auzo:policy 
                            {operation : operation to be done the policy model} 
                            {--name= : policy name for the create operation}
                            {--id= : policy id for the delete operation}
                            {--method= : policy method for the create operation}';

    /**
     * The console command method.
     *
     * @var string
     */
    protected $method = 'Auzo policies management';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $operation = $this->argument('operation');
        $name = $this->option('name');
        $id = $this->option('id');
        $method = $this->option('method');

        switch ($operation) {
            case 'create':
                $this->create($name, $method);
                break;
            case 'delete':
                $this->delete($id);
                break;
        }
    }

    private function create($name, $method)
    {
        AuzoPolicyFacade::create([
            'name'   => $name,
            'method' => $method,
        ]);

        $this->info("$name is created.");
    }

    private function delete($id)
    {
        $no_interaction = $this->option('no-interaction');

        if ($no_interaction || $this->confirm("policy with id $id is going to be deleted. Do you wish to continue? [y|N]")) {
            AuzoPolicyFacade::findOrFail($id)->delete();

            $this->info("$id is deleted.");
        }
    }
}
