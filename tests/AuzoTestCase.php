<?php

namespace Kordy\Auzo\Test;

class AuzoTestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    protected $user_model;

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__.'/../../../../database/migrations'),
        ]);

        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__.'/../src/Migrations'),
        ]);

        // Set the baseUrl to the APP_URL configured in .env
        $this->baseUrl = config('app.url');

        // Get the user model from the Config/auth.php file
        $this->user_model = config('auth.model') ?: config('auth.providers.users.model');
    }

    protected function getPackageProviders($app)
    {
        return ['Kordy\Auzo\AuzoServiceProvider'];
    }
}
