<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::withCount(['users', 'strategies'])->get();
        
        return view('admin.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name',
            'description' => 'nullable|string|max:500',
        ]);

        Group::create($validated);

        return redirect()->route('admin.groups.index')
            ->with('success', 'Group created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        $group->load(['users', 'strategies.user', 'strategies.status']);
        
        // Get users not in this group for adding new members
        $availableUsers = User::whereNotIn('id', $group->users->pluck('id'))->get();
        
        return view('admin.groups.show', compact('group', 'availableUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        return view('admin.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name,' . $group->id,
            'description' => 'nullable|string|max:500',
        ]);

        $group->update($validated);

        return redirect()->route('admin.groups.show', $group)
            ->with('success', 'Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        // Check if group has associated strategies
        if ($group->strategies()->count() > 0) {
            return back()->with('error', 'Cannot delete group that has associated strategies.');
        }

        // Remove all user associations
        $group->users()->detach();
        
        $group->delete();

        return redirect()->route('admin.groups.index')
            ->with('success', 'Group deleted successfully.');
    }

    /**
     * Add a user to the group with specified permission.
     */
    public function addUser(Request $request, Group $group)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission' => 'required|in:read,write',
        ]);

        // Check if user is already in the group with this permission
        if ($group->users()->where('users.id', $validated['user_id'])
                         ->wherePivot('permission', $validated['permission'])
                         ->exists()) {
            return back()->with('error', 'User already has this permission in the group.');
        }

        // Remove existing association if any (to update permission)
        $group->users()->detach($validated['user_id']);
        
        // Add user with new permission
        $group->users()->attach($validated['user_id'], [
            'permission' => $validated['permission'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'User added to group successfully.');
    }

    /**
     * Update user permission in the group.
     */
    public function updateUserPermission(Request $request, Group $group, User $user)
    {
        $validated = $request->validate([
            'permission' => 'required|in:read,write',
        ]);

        if (!$group->hasUser($user->id)) {
            return back()->with('error', 'User is not a member of this group.');
        }

        $group->users()->updateExistingPivot($user->id, [
            'permission' => $validated['permission'],
            'updated_at' => now(),
        ]);

        return back()->with('success', 'User permission updated successfully.');
    }

    /**
     * Remove a user from the group.
     */
    public function removeUser(Group $group, User $user)
    {
        if (!$group->hasUser($user->id)) {
            return back()->with('error', 'User is not a member of this group.');
        }

        $group->users()->detach($user->id);

        return back()->with('success', 'User removed from group successfully.');
    }
}
