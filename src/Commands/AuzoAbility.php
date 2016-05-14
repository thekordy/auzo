<?php

namespace Kordy\Auzo\Commands;

use Illuminate\Console\Command;
use Kordy\Auzo\Services\GenerateAbilitiesToDB;

class AuzoAbility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auzo:ability 
                            {operation : operation to be done the ability model} 
                            {value : value for the operation}
                            {--option= : option for the operation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auzo abilities management';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $operation = $this->argument('operation');
        $value = $this->argument('value');
        $option = $this->option('option');

        switch ($operation) {

            case 'generate':
                $this->generator($value, $option);
        }
    }

    private function generator($value, $option)
    {
        $model = app($value);

        $generator = new GenerateAbilitiesToDB();

        switch ($option) {
            case 'model':
                $generator->modelAbilities($model)->saveModelToDB();
                break;
            case 'fields':
                $generator->fieldsAbilities($model)->saveFieldsToDB();
                break;
            default:
                $generator->fullCrudAbilities($model)->saveToDB();
        }
    }
}
