<?php

class AuzoPolicyCommandTest extends AuzoTestCase
{
    public function test_auzo_policy_create_artisan_command()
    {
        $this->artisan('auzo:policy', ['operation' => 'create', 'name' => 'Test Policy', 'method' => 'Controller@policy']);
        $this->seeInDatabase(app('AuzoPolicy')->getTable(), ['name' => 'Test Policy', 'method' => 'Controller@policy']);
    }

    public function test_auzo_policy_delete_artisan_command()
    {
        AuzoPolicy::create(['name' => 'Test Policy', 'method' => 'Controller@policy']);
        $this->artisan('auzo:policy', ['--no-interaction' => true, 'operation' => 'delete', 'id' => 1]);
        $this->dontSeeInDatabase(app('AuzoRole')->getTable(), ['name' => 'Test Policy']);
    }
}
