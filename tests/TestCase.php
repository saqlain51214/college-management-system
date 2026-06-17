<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    protected function createSuperAdmin(array $attributes = []): User
    {
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        $user = User::factory()->create($attributes);
        $user->assignRole($role);

        return $user;
    }

    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }
}
