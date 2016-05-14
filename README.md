Central management interface with database and user roles for Laravel 5.1 and
5.2 users authorize system.

This package will install database tables to store abilities, user roles,
permissions, and custom condition/policy per each permission you give.

This package requires [AuzoTools](https://github.com/thekordy/auzo-tools/)
which provides several great tools that facilitates Laravel authorize
management.

## What you can do with this package:
1. [Manage Abilities](#abilities)
2. [Manage Roles](#roles)
3. [Manage Conditions/Policies](#policies)
4. [Manage Permissions](#permissions)
5. [Artisan Commands](#artisan-commands)

# Installation
You can install the package via composer:
``` bash
composer require kordy/auzo
```

This service provider must be installed.
```php
// config/app.php
'providers' => [
    ...
    Kordy\Auzo\AuzoServiceProvider::class,
];
```

You can publish the migrations with:
```bash
php artisan vendor:publish --provider="Kordy\Auzo\AuzoServiceProvider" --tag="migrations"
```

After the migration has been published you can create the role- and permission-tables by
running the migrations:

```bash
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Kordy\Auzo\AuzoServiceProvider" --tag="config"
```

## Customization
You can replace or extend any model of the package through the configuration
 file, create your own class, use the model trait, modify any function,
 and just add the new class path in the config/auzo.php file.

`config/auzo.php` contents:

```php
/*
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
```

# Usage

## Abilities
By default Laravel authorize abilities are [defined](https://laravel.com/docs/5.1/authorization#defining-abilities)
statically through the service provider, this package gives more flexibility
 by creating them in the database and automatically defines them.

Manage abilities using AuzoAbility Facade:
```php
AuzoAbility::create([
    'name' => 'ability.name',
    'label' => 'Abiliy Label',
    'tag' => 'ability.tag'
]);
```

## Roles
Auzo approaches Role Based Access Control (RBAC) methodology, All users get
their permissions "only" through their role, that is for better usability
 and scalability, and to maintain solid and non-conflict polices from
 different roles, "only" single role is allowed per user.

Manage roles using AuzoRole Facade:
```php
$role = AuzoRole::create([
    'name' => 'testRole',
    'description' => 'test role description'
]);
```

Assign role to a user:
```php
// by role instance
$user->assignRole($role);
// or by role id
$user->assignRole(2);
// or by role name
$user->assignRole('testRole');
```

## Policies
You can define custom conditions or as we name it here "policies", policy is
a custom function (that you have created somewhere) which defines some
conditions that have to be met before granting the permission to a user.

example: grant a user permission if the user is the owner of the post.
```php
// App\Post
public function owner($user, $model) {
    return $user->id == $model->usr_id;
}
```

Manage policies through AuzoPolicy Facade:
```php
$policy = AuzoPolicy::create([
    'name'   => 'Post Owner',
    'method' => 'App\Post@owner',
]);
```

## Permissions

Give role a permission to an ability:
```php
// by ability instance
$role->givePermissionTo($ability);
// or by ability id
$role->givePermissionTo(3);
// or by array of abilities ids
$role->givePermissionTo([1,3]);
// or by ability name
$role->givePermissionTo('ability.name');
```

Give role a permission to an ability restricted by policy:
```php
$role->givePermissionTo($ability->name)
    ->addPolicy($policy1)
    ->addPolicy([$policy2->id => ['operator' => 'or']]);
```

if multiple policies applied, a default "AND" is applied between policies,
unless you specified an operator at the time of adding policies to the
permission.

## Artisan Commands

### auzo:ability
This will use `GenerateAbilities` from [AuzoTools](https://github.com/thekordy/auzo-tools/)
 to generate all abilities (by default matching route source scheme) for a model
  and its fields, and saves them to the database abilities table.

```bash
php artisan auzo:ability generate 'App\Post'
# or to generate only model abilities
php artisan auzo:ability generate 'App\Post' --model
# or to generate only fields abilities
php artisan auzo:ability generate 'App\Post' --fields
```
This will generate and save all abilities below to abilities table:
```php
[
    [
        'name' => 'post.index',
        'tag'  => 'post',
    ],

    [
        'name' => 'post.create',
        'tag'  => 'post',
    ],

    [
        'name' => 'post.store',
        'tag'  => 'post',
    ],

    [
        'name' => 'post.show',
        'tag'  => 'post',
    ],

    [
        'name' => 'post.edit',
        'tag'  => 'post',
    ],

    [
        'name' => 'post.update',
        'tag'  => 'post',
    ],

    [
        'name' => 'post.destroy',
        'tag'  => 'post',
    ],

    [
        'name' => 'post.index.id',
        'tag'  => 'post.index',
    ],

    [
        'name' => 'post.create.id',
        'tag'  => 'post.create',
    ],

    [
        'name' => 'post.store.id',
        'tag'  => 'post.store',
    ],

    [
        'name' => 'post.show.id',
        'tag'  => 'post.show',
    ],

    [
        'name' => 'post.edit.id',
        'tag'  => 'post.edit',
    ],

    [
        'name' => 'post.update.id',
        'tag'  => 'post.update',
    ],

    [
        'name' => 'post.destroy.id',
        'tag'  => 'post.destroy',
    ],

    [
        'name' => 'post.index.name',
        'tag'  => 'post.index',
    ],

    [
        'name' => 'post.create.name',
        'tag'  => 'post.create',
    ],

    [
        'name' => 'post.store.name',
        'tag'  => 'post.store',
    ],
    ....
```

# Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

# License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

