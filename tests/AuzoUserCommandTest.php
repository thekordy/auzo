<?php

class AuzoUserCommandTest extends AuzoTestCase
{
    public function test_auzo_user_assign_artisan_command()
    {
        factory('TestUser')->create();
        factory('TestUser')->create();
        $role = AuzoRole::create(['name' => 'testRole']);

        $this->artisan('auzo:user', [
            'operation' => 'assign',
            'users'     => '1,2',  // multiple users ids separated by ,
            'role'      => 'testRole', // role name or id
        ]);

        $user1 = AuzoUser::find(1);
        $user2 = AuzoUser::find(2);

        $this->assertTrue($user1->hasRole($role));
        $this->assertTrue($user2->hasRole($role));
    }

    public function test_auzo_user_revoke_artisan_command()
    {
        $user1 = factory('TestUser')->create();
        $user2 = factory('TestUser')->create();
        $role = AuzoRole::create(['name' => 'testRole']);

        $user1->assignRole($role);
        $user2->assignRole($role);

        $this->assertTrue($user1->hasRole($role));
        $this->assertTrue($user2->hasRole($role));

        $this->artisan('auzo:user', [
            '--no-interaction' => true,
            'operation'        => 'revoke',
            'users'            => '1,2',  // multiple users ids separated by ,
            'role'             => 1, // role name or id
        ]);

        $user1 = AuzoUser::find(1);
        $user2 = AuzoUser::find(2);

        $this->assertFalse($user1->hasRole($role));
        $this->assertFalse($user2->hasRole($role));
    }
}
