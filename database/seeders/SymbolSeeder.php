<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        
        // Read and insert each line
        while (($data = fgetcsv($csvFile)) !== false) {
            DB::table('symbols')->insert([
                'code' => $data[0],
                'symbol' => $data[1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($csvFile);
    }
}
