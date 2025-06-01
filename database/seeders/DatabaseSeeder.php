<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the custom seeders first
        $this->call([
            GroupSeeder::class,
            StatusSeeder::class,
            TimeframeSeeder::class,
        ]);

        // Create an admin user
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create a regular test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        // Assign users to groups
        $adminGroup = Group::where('name', 'Administrators')->first();
        $userGroup = Group::where('name', 'Users')->first();

        if ($adminGroup) {
            $adminUser->groups()->attach($adminGroup->id);
        }

        if ($userGroup) {
            $testUser->groups()->attach($userGroup->id);
        }
    }
}
