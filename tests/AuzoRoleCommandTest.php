<?php

class AuzoRoleCommandTest extends AuzoTestCase
{
    public function test_auzo_role_create_artisan_command()
    {
        $this->artisan('auzo:role', ['operation' => 'create', 'role' => 'testRole', '--description' => 'test role']);
        $this->seeInDatabase(app('AuzoRole')->getTable(), ['name' => 'testRole', 'description' => 'test role']);
    }

    public function test_auzo_role_delete_artisan_command()
    {
        AuzoRole::create(['name' => 'testRole']);
        $this->artisan('auzo:role', ['--no-interaction' => true, 'operation' => 'delete', 'role' => 'testRole']);
        $this->dontSeeInDatabase(app('AuzoRole')->getTable(), ['name' => 'testRole']);
    }
}
