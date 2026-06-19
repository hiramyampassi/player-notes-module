<?php

namespace Tests;

use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     * Seed permissions for testing.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Seed permissions in test environment
        $this->seed(PermissionSeeder::class);
    }
}
