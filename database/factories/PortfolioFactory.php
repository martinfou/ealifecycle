<?php

namespace Database\Factories;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Portfolio>
 */
class PortfolioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Portfolio::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Portfolio',
            'description' => $this->faker->sentence,
            'initial_capital' => $this->faker->randomFloat(2, 10000, 1000000),
            'status' => $this->faker->randomElement(['active', 'paused', 'archived']),
            'user_id' => User::factory(),
        ];
    }
}
