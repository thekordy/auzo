<?php

namespace Kordy\Auzo\Commands;

use AuzoAbility as AuzoAbilityFacade;
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
                            {--option= : option for the operation}
                            {--label= : label for the create operation}
                            {--tag= : tag for the create operation}
                            {--n|no-interaction : tag for the create operation}';

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

        switch ($operation) {
            case 'generate':
                $this->generator($value);
                break;
            case 'create':
                $this->create($value);
                break;
            case 'delete':
                $this->delete($value);
                break;
        }
    }

    private function generator($value)
    {
        $option = $this->option('option');

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

    private function create($value)
    {
        $label = $this->option('label');
        $tag = $this->option('tag');
        
        AuzoAbilityFacade::create([
            'name' => $value,
            'label' => $label,
            'tag' => $tag
        ]);

        $this->info("$value is created.");
    }

    private function delete($value)
    {
        $no_interaction = $this->option('no-interaction');

        if ($no_interaction) {
            AuzoAbilityFacade::findByNameOrId($value)->delete();
        } elseif ($this->confirm("$value is going to be deleted. Do you wish to continue? [y|N]")) {
            AuzoAbilityFacade::findByNameOrId($value)->delete();
        }

        $this->info("$value is deleted.");
    }
}
