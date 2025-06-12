<?php

namespace Tests\Feature\Api\V1;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fetching a list of portfolios.
     *
     * @return void
     */
    public function test_can_get_portfolios(): void
    {
        $user = User::factory()->create();
        $portfolios = Portfolio::factory()->count(2)->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/portfolios');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'initial_capital',
                    'status',
                    'user_id',
                    'group_id',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }
}
