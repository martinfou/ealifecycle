<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Timeframe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TimeframeController extends Controller
{
    /**
     * Display a listing of timeframes
     */
    public function index()
    {
        $timeframes = Timeframe::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.timeframes.index', compact('timeframes'));
    }

    /**
     * Show the form for creating a new timeframe
     */
    public function create()
    {
        return view('admin.timeframes.create');
    }

    /**
     * Store a newly created timeframe in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:timeframes,name',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If no sort order provided, set it to the highest + 1
        $sortOrder = $request->sort_order;
        if ($sortOrder === null) {
            $maxOrder = Timeframe::max('sort_order') ?? 0;
            $sortOrder = $maxOrder + 1;
        }

        Timeframe::create([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $sortOrder,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.timeframes.index')
            ->with('success', 'Timeframe created successfully.');
    }

    /**
     * Display the specified timeframe
     */
    public function show(Timeframe $timeframe)
    {
        $strategiesCount = $timeframe->strategies()->count();
        return view('admin.timeframes.show', compact('timeframe', 'strategiesCount'));
    }

    /**
     * Show the form for editing the specified timeframe
     */
    public function edit(Timeframe $timeframe)
    {
        return view('admin.timeframes.edit', compact('timeframe'));
    }

    /**
     * Update the specified timeframe in storage
     */
    public function update(Request $request, Timeframe $timeframe)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:timeframes,name,' . $timeframe->id,
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $timeframe->update([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? $timeframe->sort_order,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.timeframes.index')
            ->with('success', 'Timeframe updated successfully.');
    }

    /**
     * Remove the specified timeframe from storage
     */
    public function destroy(Timeframe $timeframe)
    {
        // Check if timeframe is being used by any strategies
        $strategiesCount = $timeframe->strategies()->count();
        
        if ($strategiesCount > 0) {
            return redirect()->route('admin.timeframes.index')
                ->with('error', "Cannot delete timeframe. It is currently being used by {$strategiesCount} strategy(ies).");
        }

        $timeframe->delete();

        return redirect()->route('admin.timeframes.index')
            ->with('success', 'Timeframe deleted successfully.');
    }
}
