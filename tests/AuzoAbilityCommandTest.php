<?php

class AuzoAbilityCommandTest extends AuzoTestCase
{
    private $expected_saved_generated_abilities = [
        'model' => [
            [
                'name' => 'ability.index',
                'tag'  => 'ability',
            ],

            [
                'name' => 'ability.create',
                'tag'  => 'ability',
            ],

            [
                'name' => 'ability.store',
                'tag'  => 'ability',
            ],

            [
                'name' => 'ability.show',
                'tag'  => 'ability',
            ],

            [
                'name' => 'ability.edit',
                'tag'  => 'ability',
            ],

            [
                'name' => 'ability.update',
                'tag'  => 'ability',
            ],

            [
                'name' => 'ability.destroy',
                'tag'  => 'ability',
            ],
        ],

        'fields' => [

            [
                'name' => 'ability.index.id',
                'tag'  => 'ability.index',
            ],

            [
                'name' => 'ability.create.id',
                'tag'  => 'ability.create',
            ],

            [
                'name' => 'ability.store.id',
                'tag'  => 'ability.store',
            ],

            [
                'name' => 'ability.show.id',
                'tag'  => 'ability.show',
            ],

            [
                'name' => 'ability.edit.id',
                'tag'  => 'ability.edit',
            ],

            [
                'name' => 'ability.update.id',
                'tag'  => 'ability.update',
            ],

            [
                'name' => 'ability.destroy.id',
                'tag'  => 'ability.destroy',
            ],

            [
                'name' => 'ability.index.name',
                'tag'  => 'ability.index',
            ],

            [
                'name' => 'ability.create.name',
                'tag'  => 'ability.create',
            ],

            [
                'name' => 'ability.store.name',
                'tag'  => 'ability.store',
            ],

            [
                'name' => 'ability.show.name',
                'tag'  => 'ability.show',
            ],

            [
                'name' => 'ability.edit.name',
                'tag'  => 'ability.edit',
            ],

            [
                'name' => 'ability.update.name',
                'tag'  => 'ability.update',
            ],

            [
                'name' => 'ability.destroy.name',
                'tag'  => 'ability.destroy',
            ],

            [
                'name' => 'ability.index.label',
                'tag'  => 'ability.index',
            ],

            [
                'name' => 'ability.create.label',
                'tag'  => 'ability.create',
            ],

            [
                'name' => 'ability.store.label',
                'tag'  => 'ability.store',
            ],

            [
                'name' => 'ability.show.label',
                'tag'  => 'ability.show',
            ],

            [
                'name' => 'ability.edit.label',
                'tag'  => 'ability.edit',
            ],

            [
                'name' => 'ability.update.label',
                'tag'  => 'ability.update',
            ],

            [
                'name' => 'ability.destroy.label',
                'tag'  => 'ability.destroy',
            ],

            [
                'name' => 'ability.index.tag',
                'tag'  => 'ability.index',
            ],

            [
                'name' => 'ability.create.tag',
                'tag'  => 'ability.create',
            ],

            [
                'name' => 'ability.store.tag',
                'tag'  => 'ability.store',
            ],

            [
                'name' => 'ability.show.tag',
                'tag'  => 'ability.show',
            ],

            [
                'name' => 'ability.edit.tag',
                'tag'  => 'ability.edit',
            ],

            [
                'name' => 'ability.update.tag',
                'tag'  => 'ability.update',
            ],

            [
                'name' => 'ability.destroy.tag',
                'tag'  => 'ability.destroy',
            ],
        ],
    ];

    public function test_auzo_ability_generate_artisan_command()
    {
        $this->artisan('auzo:ability', ['operation' => 'generate', 'value' => 'AuzoAbility']);

        $saved_abilities = AuzoAbility::all(['name', 'tag'])->toArray();

        $full_expected_model_generated = array_merge(
            $this->expected_saved_generated_abilities['model'], $this->expected_saved_generated_abilities['fields']
        );

        $this->assertEquals($full_expected_model_generated, $saved_abilities);
    }

    public function test_auzo_ability_generate_artisan_command_model_option()
    {
        $this->artisan('auzo:ability', ['operation' => 'generate', 'value' => 'AuzoAbility', '--option' => 'model']);

        $saved_abilities = AuzoAbility::all(['name', 'tag'])->toArray();

        $this->assertEquals($this->expected_saved_generated_abilities['model'], $saved_abilities);
    }

    public function test_auzo_ability_generate_artisan_command_fields_option()
    {
        $this->artisan('auzo:ability', ['operation' => 'generate', 'value' => 'AuzoAbility', '--option' => 'fields']);

        $saved_abilities = AuzoAbility::all(['name', 'tag'])->toArray();

        $this->assertEquals($this->expected_saved_generated_abilities['fields'], $saved_abilities);
    }

    public function test_auzo_ability_create_artisan_command()
    {
        $this->artisan('auzo:ability', [
            'operation' => 'create',
            'value' => 'ability.index',
            '--label' => 'Ability Index',
            '--tag' => 'ability',
        ]);

        $this->seeInDatabase(app('AuzoAbility')->getTable(), [
            'name' => 'ability.index',
            'label' => 'Ability Index',
            'tag' => 'ability',
        ]);
    }

    public function test_auzo_ability_delete_artisan_command()
    {
        AuzoAbility::create(['name' => 'test.ability']);
        $this->artisan('auzo:ability', ['--no-interaction' => true, 'operation' => 'delete', 'value' => 'test.ability']);
        $this->dontSeeInDatabase(app('AuzoAbility')->getTable(), ['name' => 'test.ability']);
    }
}
