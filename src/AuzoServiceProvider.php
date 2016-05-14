<?php

namespace Kordy\Auzo;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Kordy\Auzo\Facades\AuzoAbilityFacade;
use Kordy\Auzo\Facades\AuzoPolicyFacade;
use Kordy\Auzo\Facades\AuzoPermissionFacade;
use Kordy\Auzo\Facades\AuzoPermissionRegistrarFacade;
use Kordy\Auzo\Facades\AuzoRoleFacade;
use AuzoPermissionRegistrar;
use Kordy\Auzo\Facades\AuzoUserFacade;

class AuzoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap Auzo application services.
     * 
     */
    public function boot()
    {
        // Load stored users abilities to Laravel Gate
        AuzoPermissionRegistrar::registerPermissions();

        /** Package Resources **/

        // Publish the configuration file to the application config folder
        $this->publishes([
            __DIR__.'/Config/auzo.php' => config_path('auzo.php'),
        ], 'config');

        // Publish the migrations files to the application database migrations folder
        $this->publishes([
            __DIR__.'/Migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register Auzo application services.
     *
     * @return void
     */
    public function register()
    {
        // Default configuration file
        $this->mergeConfigFrom(
            __DIR__.'/Config/auzo.php', 'auzo'
        );
        $this->registerModelBindings();
        $this->registerFacadesAliases();
        $this->registerCommands();
    }

    /**
     * Bind the Permission, Ability, Policy, Role models
     * and PermissionRegistrar into the IoC.
     */
    protected function registerModelBindings()
    {
        $this->app->bind('AuzoPermissionRegistrar', config('auzo.registrar'));
        $this->app->bind('AuzoPermission', config('auzo.models.permission'));
        $this->app->bind('AuzoAbility', config('auzo.models.ability'));
        $this->app->bind('AuzoPolicy', config('auzo.models.policy'));
        $this->app->bind('AuzoRole', config('auzo.models.role'));
        $this->app->bind('AuzoUser', config('auzo.models.user'));
    }

    /**
     * Create aliases for the Model Bindings.
     *
     * @see registerModelBindings
     */
    protected function registerFacadesAliases()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('AuzoPermissionRegistrar', AuzoPermissionRegistrarFacade::class);
        $loader->alias('AuzoAbility', AuzoAbilityFacade::class);
        $loader->alias('AuzoPolicy', AuzoPolicyFacade::class);
        $loader->alias('AuzoPermission', AuzoPermissionFacade::class);
        $loader->alias('AuzoRole', AuzoRoleFacade::class);
        $loader->alias('AuzoUser', AuzoUserFacade::class);
    }

    /**
     * Register the commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $commands = [
            'Kordy\Auzo\Commands\AuzoAbility',
        ];

        $this->commands($commands);
    }
}
