<?php

namespace Kordy\Auzo\Test;

use Illuminate\Routing\Router;
use AuzoRole;
use AuzoAbility;
use AuzoPolicy;
use AuzoPermissionRegistrar;

class PermissionsTest extends AuzoTestCase
{

    
    public function test_create_new_ability()
    {
        $ability = $this->createTestAbility();
        $this->seeInDatabase($ability->getTable(), ['name' => 'test.ability', 'label' => 'Testing']);
    }

    public function test_can_not_duplicate_abilities()
    {
        $this->createTestAbility();
        $this->setExpectedException('Illuminate\Database\QueryException');
        $this->createTestAbility();
    }

    public function test_can_create_policy()
    {
        $policy = AuzoPolicy::create(['name' => 'Test Policy', 'method' => 'Class@method']);
        $this->seeInDatabase($policy->getTable(),
            ['name' => 'Test Policy', 'method' => 'Class@method']
        );
    }
    
    public function test_can_create_new_role()
    {
        $role = AuzoRole::create(['name' => 'testRole', 'description' => 'test role description']);
        $this->seeInDatabase($role->getTable(),
            ['name' => 'testRole', 'description' => 'test role description']
        );
    }

    public function test_can_give_role_permission_to_ability()
    {
        $role = AuzoRole::create(['name' => 'testRole']);
        $ability1 = AuzoAbility::create(['name' => 'test1.ability', 'label' => 'Testing1']);
        $ability2 = AuzoAbility::create(['name' => 'test2.ability', 'label' => 'Testing2']);
        $role->givePermissionTo($ability1->name);

        $this->assertTrue($role->hasPermissionTo($ability1->name));
        $this->assertFalse($role->hasPermissionTo($ability2->name));
    }

    public function test_can_add_user_to_role()
    {
        $user = $this->createUser();
        $role = AuzoRole::create(['name' => 'testRole']);
        $user->assignRole($role);
        
        $this->assertTrue($user->hasRole($role));
        $this->assertEquals($role->name,$user->role->name);
    }

    public function test_can_user_inherit_role_permission_to_ability()
    {
        $role = AuzoRole::create(['name' => 'testRole']);
        $user = $this->createUser();
        $ability1 = AuzoAbility::create(['name' => 'test1.ability', 'label' => 'Testing1']);
        $ability2 = AuzoAbility::create(['name' => 'test2.ability', 'label' => 'Testing2']);

        $role->givePermissionTo($ability1->name);

        $user->assignRole($role);

        $this->assertTrue($user->hasPermissionTo($ability1->name));
        $this->assertFalse($user->hasPermissionTo($ability2->name));
    }

    public function test_can_user_inherit_role_permission_with_policies_to_ability()
    {
        $role = AuzoRole::create(['name' => 'testRole']);
        $ability = AuzoAbility::create(['name' => 'test.ability', 'label' => 'Testing']);
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $policy1 = AuzoPolicy::create([
            'name' => 'Profile Owner',
            'method' => $this->user_model.'@profileOwner'
        ]);
        $policy2 = AuzoPolicy::create([
            'name' => 'Application Admin',
            'method' => $this->user_model.'@siteAdmin'
        ]);

        $role->givePermissionTo($ability->name)
            ->addPolicy($policy1)
            ->addPolicy([$policy2->id => ['operator' => 'or']]);

        $user1->assignRole($role);

        $this->assertTrue($user1->isCapableTo($ability->name, $user2));
        $this->assertFalse($user2->isCapableTo($ability->name, $user1));
    }

    public function test_user_inherit_role_permission_with_policies_to_ability_with_laravel_authorize()
    {
        $role = AuzoRole::create(['name' => 'testRole']);
        $ability = AuzoAbility::create(['name' => 'test.ability', 'label' => 'Testing']);
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $policy1 = AuzoPolicy::create([
            'name' => 'Profile Owner',
            'method' => $this->user_model.'@profileOwner'
        ]);
        $policy2 = AuzoPolicy::create([
            'name' => 'Application Admin',
            'method' => $this->user_model.'@siteAdmin'
        ]);

        $role->givePermissionTo($ability->name)
            ->addPolicy($policy1)
            ->addPolicy([$policy2->id => ['operator' => 'or']]);

        $user1->assignRole($role);

        AuzoPermissionRegistrar::registerPermissions();

        $this->assertTrue($user1->can($ability->name, $user2));
        $this->assertTrue($user2->cannot($ability->name, $user1));
    }

    public function test_auzo_middleware_uses_route_name_as_ability_name_for_authorization_check_with_params()
    {
        $ability = AuzoAbility::create(
            ['name' => 'user-profile', 'label' => 'user profile test route']
        );

        $role = AuzoRole::create(['name' => 'testRole']);

        $user1 = $this->createUser();

        $user1->assignRole($role);

        $role->givePermissionTo($ability->name);

        $user2 = $this->createUser();

        AuzoPermissionRegistrar::registerPermissions();

        $router = app(Router::class);
        $router->get('user-profile-test', function(){
            return 'hello there';
        })->middleware('auzo.acl:user-profile');

        $this->actingAs($user1)
            ->visit('/user-profile-test')
            ->see('hello there');

        try {
            $this->actingAs($user2)->visit('/user-profile-test');
        } catch (\Exception $e) {
            $this->assertContains ("Received status code [403]",$e->getMessage());
        }

    }

    public function test_auzo_middleware_uses_route_name_as_ability_name_for_authorization_check_with_policies()
    {
        $ability = AuzoAbility::create(
            ['name' => 'user.profile.test', 'label' => 'user profile test route']
        );
        $role1 = AuzoRole::create(['name' => 'testRole1']);
        $role2 = AuzoRole::create(['name' => 'testRole2']);
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $policy1 = AuzoPolicy::create([
            'name' => 'Profile Owner',
            'method' => $this->user_model.'@profileOwner'
        ]);
        $policy2 = AuzoPolicy::create([
            'name' => 'Application Admin',
            'method' => $this->user_model.'@siteAdmin'
        ]);

        $role1->givePermissionTo($ability->name)
            ->addPolicy($policy1)
            ->addPolicy([$policy2->id => ['operator' => 'or']]);

        $role2->givePermissionTo($ability->name)->addPolicy($policy1);

        $user1->assignRole($role1);
        $user2->assignRole($role2);

        AuzoPermissionRegistrar::registerPermissions();

        $router = app(Router::class);
        $router->get('user-profile-test/{id}', function($id){
            return "hello there user $id";
        })->name('user.profile.test')->middleware('auzo.acl'); 

        // user1 can view any user profile as an admin policy
        $this->actingAs($user1)
            ->visit('/user-profile-test/1')
            ->see('hello there user 1')
            ->visit('/user-profile-test/2')
            ->see('hello there user 2');

        // user2 can only see his own profile
        $this->actingAs($user2)
            ->visit('/user-profile-test/2')
            ->see('hello there user 2');

        try {
            $this->actingAs($user2)->visit('/user-profile-test/1');
        } catch (\Exception $e) {
            $this->assertContains ("Received status code [403]",$e->getMessage());
        }

    }

    protected function createTestAbility()
    {
        return AuzoAbility::create(['name' => 'test.ability', 'label' => 'Testing']);
    }

    /**
     * @return mixed
     */
    protected function createUser($params = [])
    {
        return factory($this->user_model)->create($params);
    }

}
