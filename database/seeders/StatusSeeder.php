<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Demo',
                'description' => 'Strategy is being tested on demo account',
                'color' => '#3B82F6', // Blue
                'is_active' => true,
            ],
            [
                'name' => 'Production',
                'description' => 'Strategy is live and trading with real money',
                'color' => '#10B981', // Green
                'is_active' => true,
            ],
            [
                'name' => 'On Hold',
                'description' => 'Strategy is temporarily paused',
                'color' => '#F59E0B', // Yellow
                'is_active' => true,
            ],
            [
                'name' => 'Retired',
                'description' => 'Strategy is no longer in use',
                'color' => '#EF4444', // Red
                'is_active' => true,
            ],
            [
                'name' => 'Development',
                'description' => 'Strategy is still being developed',
                'color' => '#8B5CF6', // Purple
                'is_active' => true,
            ],
        ];

        foreach ($statuses as $status) {
            Status::firstOrCreate(['name' => $status['name']], $status);
        }
    }
}
