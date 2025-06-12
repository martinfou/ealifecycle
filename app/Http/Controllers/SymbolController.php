<?php

namespace App\Http\Controllers;

use App\Models\Symbol;
use Illuminate\Http\Request;

class SymbolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $symbols = Symbol::orderBy('code')->paginate(20);
        return view('symbols.index', compact('symbols'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('symbols.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|integer|unique:symbols,code',
            'symbol' => 'required|string|max:255',
        ]);

        Symbol::create($request->all());

        return redirect()->route('symbols.index')
                         ->with('success', 'Symbol created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Symbol $symbol)
    {
        return view('symbols.show', compact('symbol'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Symbol $symbol)
    {
        return view('symbols.edit', compact('symbol'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Symbol $symbol)
    {
        $request->validate([
            'code' => 'required|integer|unique:symbols,code,' . $symbol->id,
            'symbol' => 'required|string|max:255',
        ]);

        $symbol->update($request->all());

        return redirect()->route('symbols.index')
                         ->with('success', 'Symbol updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Symbol $symbol)
    {
        $symbol->delete();

        return redirect()->route('symbols.index')
                         ->with('success', 'Symbol deleted successfully.');
    }
}
