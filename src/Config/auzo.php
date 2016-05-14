<?php

/*
|--------------------------------------------------------------------------
| User model
|--------------------------------------------------------------------------
|
| We here get the user model path from the one defined in config/auth.php
| file. First look for the model parameter in that file, if not, then get
| the path from the providers ['users'] ['model']
|
*/

$user_model = config('auth.model') ?: config('auth.providers.users.model');

return [

    /*
    |--------------------------------------------------------------------------
    | Application/Website name
    |--------------------------------------------------------------------------
    |
    | To be used in the views, by default it gets the name from url parameter at
    | config/app.php file, then parse it for the name, and make first char uppercase
    | ex. url('http://mysite.com') or (in .env) APP_URL=http://mysite.com -> Mysite
    | Of course you can change it here as you like.
    | ex. 'site_name' => 'My Site Name',
    |
    */

    'site_name' => ucfirst(explode('.', parse_url(config('app.url'))['host'])[0]),

    /*
    |--------------------------------------------------------------------------
    | Auzo Authorize Registrar
    |--------------------------------------------------------------------------
    |
    | You may here add custom registrar where the Laravel Gate abilities are defined
    |
    */

    'registrar' => \Kordy\Auzo\Services\PermissionRegistrar::class,

    /*
    |--------------------------------------------------------------------------
    | Auzo Middleware
    |--------------------------------------------------------------------------
    |
    | You may here add custom middleware that can be used for routes and controller
    |
    */

    'middleware' => \Kordy\Auzo\Middleware\AuzoMiddleware::class,

    /*
    |--------------------------------------------------------------------------
    | Auzo models paths
    |--------------------------------------------------------------------------
    |
    | You may here add custom models paths to be used instead of models included
    | with the package
    |
    */

    'models' => [

        'user' => $user_model,

        'ability' => \Kordy\Auzo\Models\Ability::class,

        'policy' => \Kordy\Auzo\Models\Policy::class,

        'permission' => \Kordy\Auzo\Models\Permission::class,

        'role' => \Kordy\Auzo\Models\Role::class,
    ],

];
