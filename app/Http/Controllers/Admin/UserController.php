<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['groups'])
            ->withCount(['strategies', 'importedTrades'])
            ->orderBy('name')
            ->get();
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::all();
        return view('admin.users.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'in:read,write',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Attach groups with permissions
        if (!empty($validated['groups'])) {
            foreach ($validated['groups'] as $index => $groupId) {
                $permission = $validated['permissions'][$index] ?? 'read';
                $user->groups()->attach($groupId, [
                    'permission' => $permission,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['groups', 'strategies.status', 'importedTrades']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load('groups');
        $groups = Group::all();
        
        return view('admin.users.edit', compact('user', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Check if user has associated strategies
        if ($user->strategies()->count() > 0) {
            return back()->with('error', 'Cannot delete user that has created strategies.');
        }

        // Check if user has imported trades
        if ($user->importedTrades()->count() > 0) {
            return back()->with('error', 'Cannot delete user that has imported trades.');
        }

        // Remove all group associations
        $user->groups()->detach();
        
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Assign groups to a user with specific permissions.
     */
    public function assignGroups(Request $request, User $user)
    {
        $validated = $request->validate([
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'in:read,write',
        ]);

        // Remove all current group associations
        $user->groups()->detach();

        // Attach new groups with permissions
        if (!empty($validated['groups'])) {
            foreach ($validated['groups'] as $index => $groupId) {
                $permission = $validated['permissions'][$index] ?? 'read';
                $user->groups()->attach($groupId, [
                    'permission' => $permission,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back()->with('success', 'User group assignments updated successfully.');
    }
}
