<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Symbol;
use Illuminate\Support\Facades\Log;

class SymbolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("symbols-code.csv"), "r");
        
        // Skip the first line (header)
        fgetcsv($csvFile);
        
        try {
        // Read and insert each line
        while (($data = fgetcsv($csvFile)) !== false) {
                Symbol::updateOrCreate(
                    ['code' => $data[0]],
                    [
                'symbol' => $data[1],
                'updated_at' => now(),
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('SymbolSeeder SQL error: ' . $e->getMessage());
            echo "\n[SymbolSeeder] SQL error: " . $e->getMessage() . "\n";
            fclose($csvFile);
            exit(1); // Stop the seeder and fail the deploy
        }

        fclose($csvFile);
    }
}
