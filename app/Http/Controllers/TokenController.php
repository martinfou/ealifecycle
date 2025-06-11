<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    /**
     * Display a listing of the user's API tokens
     */
    public function index()
    {
        $tokens = Auth::user()->tokens;
        return view('tokens.index', compact('tokens'));
    }

    /**
     * Store a newly created API token
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $token = Auth::user()->createToken($validated['name']);

        return back()->with([
            'success' => 'Token created successfully.',
            'plain_text_token' => $token->plainTextToken
        ]);
    }

    /**
     * Remove the specified API token
     */
    public function destroy($tokenId)
    {
        $token = Auth::user()->tokens()->findOrFail($tokenId);
        $token->delete();

        return back()->with('success', 'Token deleted successfully.');
    }
} 