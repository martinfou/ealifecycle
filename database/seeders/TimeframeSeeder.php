<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Timeframe;

class TimeframeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timeframes = [
            [
                'name' => 'M1',
                'description' => '1 Minute',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'M5',
                'description' => '5 Minutes',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'M15',
                'description' => '15 Minutes',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'M30',
                'description' => '30 Minutes',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'H1',
                'description' => '1 Hour',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'H4',
                'description' => '4 Hours',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'D1',
                'description' => 'Daily',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'W1',
                'description' => 'Weekly',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'MN1',
                'description' => 'Monthly',
                'sort_order' => 9,
                'is_active' => true,
            ],
        ];

        foreach ($timeframes as $timeframe) {
            Timeframe::firstOrCreate(['name' => $timeframe['name']], $timeframe);
        }
    }
}
