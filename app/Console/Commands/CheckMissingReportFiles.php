<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StrategyReport;
use Illuminate\Support\Facades\Storage;

class CheckMissingReportFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-missing-report-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $missing = 0;
        $total = 0;
        $this->info('Checking for missing report files...');
        foreach (StrategyReport::all() as $report) {
            $total++;
            if (!Storage::disk('local')->exists($report->file_path)) {
                $missing++;
                $this->error("Missing: ID={$report->id}, Strategy ID={$report->strategy_id}, File={$report->file_path}, Original={$report->original_filename}");
            }
        }
        $this->info("Checked $total reports. Missing files: $missing");
        return $missing === 0 ? 0 : 1;
    }
}
