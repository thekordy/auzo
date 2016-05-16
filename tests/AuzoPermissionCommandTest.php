<?php

class AuzoPermissionCommandTest extends AuzoTestCase
{
    public function test_auzo_permission_give_artisan_command_with_multiple_arguments()
    {
        $role = AuzoRole::create(['name' => 'testRole']);
        AuzoAbility::create(['name' => 'ability.test']);
        AuzoAbility::create(['name' => 'ability.test2']);
        $policy1 = AuzoPolicy::create(['name' => 'test Policy', 'method' => 'Controller@method']);
        $policy2 = AuzoPolicy::create(['name' => 'test Policy 2', 'method' => 'Controller@method2']);

        $this->artisan('auzo:permission', [
            'operation'  => 'give',
            'role'       => 'testRole',
            'abilities'  => 'ability.test,ability.test2', // abilities names or ids separated with ,
            '--policies' => '1,2:||', // Policies ids separated with ,
        ]);

        $role_permission1_policy1 = $role->permissions->first()->policies->first();
        $role_permission1_policy2 = $role->permissions->first()->policies->slice(1, 1)->first();

        $role_permission2_policy1 = $role->permissions->slice(1, 1)->first()->policies->first();
        $role_permission2_policy2 = $role->permissions->slice(1, 1)->first()->policies->slice(1, 1)->first();

        // role has assigned the permission1 with policy1
        $this->assertEquals($policy1->name, $role_permission1_policy1->name);
        // role has assigned the permission1 with policy2
        $this->assertEquals($policy2->name, $role_permission1_policy2->name);
        // role has assigned the permission1 with policy2 and the operator (or) ||
        $this->assertEquals('||', $role_permission1_policy2->pivot->operator);

        // role has assigned the permission2 with policy1
        $this->assertEquals($policy1->name, $role_permission2_policy1->name);
        // role has assigned the permission2 with policy2
        $this->assertEquals($policy2->name, $role_permission2_policy2->name);
        // role has assigned the permission2 with policy2 and the operator (or) ||
        $this->assertEquals('||', $role_permission2_policy2->pivot->operator);
    }

    public function test_auzo_permission_remove_artisan_command_with_multiple_arguments()
    {
        $role = AuzoRole::create(['name' => 'testRole']);
        AuzoAbility::create(['name' => 'ability.test']);
        AuzoAbility::create(['name' => 'ability.test2']);

        $role->givePermissionTo([1, 2]);

        // Role has been given the permissions
        $this->assertFalse(AuzoRole::find(1)->permissions->isEmpty());

        $this->artisan('auzo:permission', [
            '--no-interaction' => true,
            'operation'        => 'remove',
            'role'             => 'testRole',
            'abilities'        => 'ability.test,ability.test2',
        ]);

        // Permissions are removed
        $this->assertTrue(AuzoRole::find(1)->permissions->isEmpty());
    }
}
