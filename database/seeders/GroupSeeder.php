<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Administrators',
                'description' => 'Full access to all features and settings',
            ],
            [
                'name' => 'Users',
                'description' => 'Standard users who can manage their own strategies',
            ],
            [
                'name' => 'Viewers',
                'description' => 'Read-only access to strategies and data',
            ],
        ];

        foreach ($groups as $group) {
            Group::firstOrCreate(['name' => $group['name']], $group);
        }
    }
}
