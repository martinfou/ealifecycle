<?php

namespace Database\Factories;

use App\Models\Strategy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StrategyReport>
 */
class StrategyReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $strategy = Strategy::factory()->create();
        $filename = Str::uuid() . '.pdf';

        return [
            'strategy_id' => $strategy->id,
            'file_path' => "strategies/{$strategy->id}/reports/{$filename}",
            'original_filename' => $this->faker->word . '.pdf',
            'uploaded_by' => User::factory(),
        ];
    }
} 