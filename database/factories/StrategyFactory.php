<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Strategy>
 */
class StrategyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'symbols_traded' => implode(',', $this->faker->randomElements(['EURUSD', 'GBPUSD', 'USDJPY', 'AUDUSD'], 2)),
            'magic_number' => $this->faker->numberBetween(100000, 999999),
            'status_id' => Status::factory(),
            'date_in_status' => now(),
            'user_id' => User::factory(),
            'group_id' => Group::factory(),
            'description' => $this->faker->paragraph,
            'source_code_path' => null,
            'source_code_original_filename' => null,
        ];
    }
} 