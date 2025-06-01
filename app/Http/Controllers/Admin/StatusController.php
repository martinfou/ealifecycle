<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
{
    /**
     * Display a listing of statuses
     */
    public function index()
    {
        $statuses = Status::orderBy('name')->get();
        return view('admin.statuses.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new status
     */
    public function create()
    {
        return view('admin.statuses.create');
    }

    /**
     * Store a newly created status in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:statuses,name',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Status::create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?? '#6B7280',
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.statuses.index')
            ->with('success', 'Status created successfully.');
    }

    /**
     * Display the specified status
     */
    public function show(Status $status)
    {
        $strategiesCount = $status->strategies()->count();
        return view('admin.statuses.show', compact('status', 'strategiesCount'));
    }

    /**
     * Show the form for editing the specified status
     */
    public function edit(Status $status)
    {
        return view('admin.statuses.edit', compact('status'));
    }

    /**
     * Update the specified status in storage
     */
    public function update(Request $request, Status $status)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:statuses,name,' . $status->id,
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $status->update([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?? '#6B7280',
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.statuses.index')
            ->with('success', 'Status updated successfully.');
    }

    /**
     * Remove the specified status from storage
     */
    public function destroy(Status $status)
    {
        // Check if status is being used by any strategies
        $strategiesCount = $status->strategies()->count();
        
        if ($strategiesCount > 0) {
            return redirect()->route('admin.statuses.index')
                ->with('error', "Cannot delete status. It is currently being used by {$strategiesCount} strategy(ies).");
        }

        $status->delete();

        return redirect()->route('admin.statuses.index')
            ->with('success', 'Status deleted successfully.');
    }
}
