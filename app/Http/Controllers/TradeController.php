<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TradeController extends Controller
{
    /**
     * Display a listing of trades
     */
    public function index(Request $request)
    {
        $query = Trade::with(['strategy']);
        
        // Filter by strategy if specified
        if ($request->filled('strategy_id')) {
            $query->where('strategy_id', $request->strategy_id);
        }
        
        // Filter by date range if specified
        if ($request->filled('date_from')) {
            $query->where('open_time', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('close_time', '<=', $request->date_to . ' 23:59:59');
        }
        
        $trades = $query->orderBy('open_time', 'desc')->paginate(50);
        $strategies = Strategy::orderBy('name')->get();
        
        return view('trades.index', compact('trades', 'strategies'));
    }

    /**
     * Show the trade import form
     */
    public function import()
    {
        $strategies = Strategy::orderBy('name')->get();
        return view('trades.import', compact('strategies'));
    }

    /**
     * Process the imported trade data
     */
    public function processImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trade_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
            'default_strategy_id' => 'nullable|exists:strategies,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('trade_file');
        $defaultStrategyId = $request->default_strategy_id;
        
        try {
            $importResult = $this->parseAndImportTrades($file, $defaultStrategyId);
            
            return redirect()->route('trades.index')
                ->with('success', "Successfully imported {$importResult['imported']} trades. {$importResult['skipped']} duplicates skipped.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing trades: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display a specific trade
     */
    public function show(Trade $trade)
    {
        $trade->load('strategy');
        return view('trades.show', compact('trade'));
    }

    /**
     * Parse and import trades from FX Blue CSV format
     */
    private function parseAndImportTrades($file, $defaultStrategyId = null)
    {
        $imported = 0;
        $skipped = 0;
        
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($csvData); // Remove header row
        
        // Expected FX Blue format columns (adjust as needed)
        $expectedColumns = [
            'Ticket', 'Open Time', 'Type', 'Size', 'Symbol', 
            'Open Price', 'S/L', 'T/P', 'Close Time', 'Close Price', 
            'Commission', 'Swap', 'Profit', 'Magic Number', 'Comment'
        ];
        
        DB::beginTransaction();
        
        try {
            foreach ($csvData as $row) {
                if (count($row) < count($expectedColumns)) {
                    continue; // Skip incomplete rows
                }
                
                $tradeData = array_combine($expectedColumns, $row);
                
                // Check if trade already exists
                $existingTrade = Trade::where('ticket', $tradeData['Ticket'])->first();
                if ($existingTrade) {
                    $skipped++;
                    continue;
                }
                
                // Try to match strategy by magic number first
                $strategyId = $defaultStrategyId;
                if (!empty($tradeData['Magic Number'])) {
                    $strategy = Strategy::where('magic_number', $tradeData['Magic Number'])->first();
                    if ($strategy) {
                        $strategyId = $strategy->id;
                    }
                }
                
                // Create the trade record
                Trade::create([
                    'strategy_id' => $strategyId,
                    'ticket' => $tradeData['Ticket'],
                    'open_time' => $this->parseDateTime($tradeData['Open Time']),
                    'close_time' => $this->parseDateTime($tradeData['Close Time']),
                    'type' => $tradeData['Type'],
                    'size' => (float) $tradeData['Size'],
                    'symbol' => $tradeData['Symbol'],
                    'open_price' => (float) $tradeData['Open Price'],
                    'close_price' => (float) $tradeData['Close Price'],
                    'stop_loss' => !empty($tradeData['S/L']) ? (float) $tradeData['S/L'] : null,
                    'take_profit' => !empty($tradeData['T/P']) ? (float) $tradeData['T/P'] : null,
                    'commission' => (float) $tradeData['Commission'],
                    'swap' => (float) $tradeData['Swap'],
                    'profit' => (float) $tradeData['Profit'],
                    'magic_number' => !empty($tradeData['Magic Number']) ? (int) $tradeData['Magic Number'] : null,
                    'comment' => $tradeData['Comment'] ?? '',
                ]);
                
                $imported++;
            }
            
            DB::commit();
            
            return [
                'imported' => $imported,
                'skipped' => $skipped
            ];
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Parse datetime from various formats
     */
    private function parseDateTime($dateTimeString)
    {
        if (empty($dateTimeString)) {
            return null;
        }
        
        // Try different date formats that FX Blue might use
        $formats = [
            'Y-m-d H:i:s',
            'Y.m.d H:i:s',
            'm/d/Y H:i:s',
            'd/m/Y H:i:s',
            'Y-m-d H:i',
            'Y.m.d H:i',
        ];
        
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $dateTimeString);
            if ($date !== false) {
                return $date->format('Y-m-d H:i:s');
            }
        }
        
        // If none of the formats work, try strtotime as fallback
        $timestamp = strtotime($dateTimeString);
        if ($timestamp !== false) {
            return date('Y-m-d H:i:s', $timestamp);
        }
        
        return null;
    }
}
