<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run seeders for each test
        $this->seed();
    }
    
    protected function createUserWithRole($roleName)
    {
        $user = User::factory()->create();
        $user->assignRole($roleName);
        return $user;
    }
}