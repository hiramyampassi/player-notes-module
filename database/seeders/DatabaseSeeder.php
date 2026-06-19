<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call permission seeder first
        $this->call(PermissionSeeder::class);

        // Create a test user with all permissions
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Give test user all permissions
        $testUser->givePermissionTo('create player notes');
        $testUser->givePermissionTo('delete player notes');

        // Create additional users for testing
        User::factory(9)->create();
    }
}
