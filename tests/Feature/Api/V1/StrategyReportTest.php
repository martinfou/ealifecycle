<?php

namespace Tests\Feature\Api\V1;

use App\Models\Strategy;
use App\Models\StrategyReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StrategyReportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Strategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create();
        
        // Create a test strategy owned by the user
        $this->strategy = Strategy::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    #[Test]
    public function it_can_upload_a_report()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.pdf', 100);

        $response = $this->actingAs($this->user)
            ->postJson("/api/v1/strategies/{$this->strategy->id}/reports", [
                'report' => $file
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'filename',
                'original_filename'
            ]);

        // Assert file was stored
        Storage::disk('public')->assertExists("strategies/{$this->strategy->id}/reports/{$response->json('filename')}");

        // Assert database record was created
        $this->assertDatabaseHas('strategy_reports', [
            'strategy_id' => $this->strategy->id,
            'original_filename' => 'test.pdf',
            'uploaded_by' => $this->user->id
        ]);
    }

    #[Test]
    public function it_can_download_a_report()
    {
        Storage::fake('public');

        // Create a test report
        $report = StrategyReport::factory()->create([
            'strategy_id' => $this->strategy->id,
            'uploaded_by' => $this->user->id,
            'file_path' => "strategies/{$this->strategy->id}/reports/test.pdf",
            'original_filename' => 'test.pdf'
        ]);

        // Create a fake file
        Storage::disk('public')->put(
            $report->file_path,
            'fake pdf content'
        );

        $response = $this->actingAs($this->user)
            ->get("/api/v1/strategies/{$this->strategy->id}/reports");

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('Content-Disposition', 'attachment; filename=test.pdf');
    }

    #[Test]
    public function it_can_delete_a_report()
    {
        Storage::fake('public');

        // Create a test report
        $report = StrategyReport::factory()->create([
            'strategy_id' => $this->strategy->id,
            'uploaded_by' => $this->user->id,
            'file_path' => "strategies/{$this->strategy->id}/reports/test.pdf",
            'original_filename' => 'test.pdf'
        ]);

        // Create a fake file
        Storage::disk('public')->put(
            $report->file_path,
            'fake pdf content'
        );

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/strategies/{$this->strategy->id}/reports");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Report deleted successfully']);

        // Assert file was deleted
        Storage::disk('public')->assertMissing($report->file_path);

        // Assert database record was deleted
        $this->assertDatabaseMissing('strategy_reports', [
            'id' => $report->id
        ]);
    }

    #[Test]
    public function it_validates_file_type_on_upload()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.txt', 100);

        $response = $this->actingAs($this->user)
            ->postJson("/api/v1/strategies/{$this->strategy->id}/reports", [
                'report' => $file
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['report']);
    }

    #[Test]
    public function it_validates_file_size_on_upload()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.pdf', 11000); // 11MB

        $response = $this->actingAs($this->user)
            ->postJson("/api/v1/strategies/{$this->strategy->id}/reports", [
                'report' => $file
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['report']);
    }

    #[Test]
    public function it_returns_404_when_report_not_found()
    {
        $response = $this->actingAs($this->user)
            ->get("/api/v1/strategies/{$this->strategy->id}/reports");

        $response->assertStatus(404)
            ->assertJson(['message' => 'Report not found']);
    }

    #[Test]
    public function it_returns_404_when_deleting_nonexistent_report()
    {
        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/strategies/{$this->strategy->id}/reports");

        $response->assertStatus(404)
            ->assertJson(['message' => 'Report not found']);
    }

    #[Test]
    public function it_requires_authentication()
    {
        $response = $this->getJson("/api/v1/strategies/{$this->strategy->id}/reports");
        $response->assertStatus(401);

        $response = $this->postJson("/api/v1/strategies/{$this->strategy->id}/reports");
        $response->assertStatus(401);

        $response = $this->deleteJson("/api/v1/strategies/{$this->strategy->id}/reports");
        $response->assertStatus(401);
    }

    #[Test]
    public function it_requires_permission_to_access_strategy()
    {
        $otherUser = User::factory()->create();
        $otherStrategy = Strategy::factory()->create([
            'user_id' => $otherUser->id
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/v1/strategies/{$otherStrategy->id}/reports");

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized']);
    }
} 