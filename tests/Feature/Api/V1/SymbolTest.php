<?php

namespace Tests\Feature\Api\V1;

use App\Models\Symbol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SymbolTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fetching a list of symbols.
     *
     * @return void
     */
    public function test_can_get_symbols(): void
    {
        $user = User::factory()->create();
        Symbol::factory()->count(3)->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/symbols');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'code',
                    'symbol',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }
}
