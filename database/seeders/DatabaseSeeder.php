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

        // Create a staff user WITH permissions
        $staffUser = User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => bcrypt('password'),
        ]);
        $staffUser->assignRole('staff');

        // Create a viewer user WITHOUT permissions
        $viewerUser = User::factory()->create([
            'name' => 'Viewer User',
            'email' => 'viewer@example.com',
            'password' => bcrypt('password'),
        ]);
        // No role assigned - has no permissions

        // Create additional users for testing (players)
        User::factory(8)->create();
    }
}
