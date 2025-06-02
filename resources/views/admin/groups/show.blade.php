<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Group: ') . $group->name }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.groups.edit', $group) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Edit Group
                </a>
                <a href="{{ route('admin.groups.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Back to Groups
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="p-4 bg-green-900 border border-green-600 rounded-md">
                    <div class="text-sm font-medium text-green-200">{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-900 border border-red-600 rounded-md">
                    <div class="text-sm font-medium text-red-200">{{ session('error') }}</div>
                </div>
            @endif

            <!-- Group Details -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-4">Group Details</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-400">Name</dt>
                            <dd class="mt-1 text-sm text-white">{{ $group->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-400">Created</dt>
                            <dd class="mt-1 text-sm text-white">{{ $group->created_at->format('M j, Y \a\t g:i A') }}</dd>
                        </div>
                        @if($group->description)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-400">Description</dt>
                            <dd class="mt-1 text-sm text-white">{{ $group->description }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Group Members -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-white">Group Members ({{ $group->users->count() }})</h3>
                    </div>

                    <!-- Add User Form -->
                    @if($availableUsers->count() > 0)
                    <div class="mb-6 p-4 bg-gray-900 border border-gray-600 rounded-md">
                        <h4 class="text-sm font-medium text-gray-300 mb-3">Add User to Group</h4>
                        <form action="{{ route('admin.groups.add-user', $group) }}" method="POST" class="flex items-end space-x-3">
                            @csrf
                            <div class="flex-1">
                                <label for="user_id" class="block text-xs font-medium text-gray-400 mb-1">User</label>
                                <select name="user_id" id="user_id" class="block w-full rounded-md bg-gray-800 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500">
                                    <option value="">Select a user</option>
                                    @foreach($availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="permission" class="block text-xs font-medium text-gray-400 mb-1">Permission</label>
                                <select name="permission" id="permission" class="block w-full rounded-md bg-gray-800 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500">
                                    <option value="read">Read Only</option>
                                    <option value="write">Read & Write</option>
                                </select>
                            </div>
                            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                Add User
                            </button>
                        </form>
                    </div>
                    @endif

                    <!-- Current Members -->
                    @if($group->users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Permission</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Added</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($group->users as $user)
                                        <tr class="hover:bg-gray-750 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-white">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-300">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form action="{{ route('admin.groups.update-user-permission', [$group, $user]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="permission" onchange="this.form.submit()" class="text-sm bg-gray-900 border-gray-600 text-white rounded-md focus:border-gray-500 focus:ring-gray-500">
                                                        <option value="read" {{ $user->pivot->permission === 'read' ? 'selected' : '' }}>Read Only</option>
                                                        <option value="write" {{ $user->pivot->permission === 'write' ? 'selected' : '' }}>Read & Write</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $user->pivot->created_at ? $user->pivot->created_at->format('M j, Y') : 'Unknown' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <form action="{{ route('admin.groups.remove-user', [$group, $user]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" onclick="return confirm('Remove {{ $user->name }} from this group?')">
                                                        Remove
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400">No members in this group yet.</div>
                            @if($availableUsers->count() > 0)
                                <div class="text-sm text-gray-500 mt-2">Use the form above to add users.</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Associated Strategies -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-6">Associated Strategies ({{ $group->strategies->count() }})</h3>

                    @if($group->strategies->count() > 0)
                        <div class="space-y-4">
                            @foreach($group->strategies as $strategy)
                                <div class="flex items-center justify-between p-4 border border-gray-700 rounded-lg bg-gray-900">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-white">{{ $strategy->name }}</h4>
                                        <div class="mt-1 flex items-center space-x-4 text-xs text-gray-400">
                                            <span>Created by {{ $strategy->user->name }}</span>
                                            <span>•</span>
                                            <span>Status: {{ $strategy->status->name }}</span>
                                            @if($strategy->symbols_traded)
                                                <span>•</span>
                                                <span>{{ $strategy->symbols_traded }}</span>
                                            @endif
                                        </div>
                                        @if($strategy->description)
                                            <p class="mt-1 text-xs text-gray-300">{{ Str::limit($strategy->description, 100) }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('strategies.show', $strategy) }}" class="text-gray-300 hover:text-white text-sm transition-colors">
                                            View Strategy
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400">No strategies associated with this group yet.</div>
                            <div class="text-sm text-gray-500 mt-2">Strategies can be assigned to this group when creating or editing them.</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Usage Information -->
            <div class="bg-gray-900 border border-gray-600 rounded-md p-4">
                <h4 class="text-sm font-medium text-blue-400 mb-2">Group Permissions</h4>
                <div class="text-sm text-gray-300 space-y-1">
                    <p><strong>Read Only:</strong> Users can view strategies assigned to this group.</p>
                    <p><strong>Read & Write:</strong> Users can view, edit, and change status of strategies assigned to this group.</p>
                    <p><strong>Note:</strong> Strategy owners always have full access regardless of group permissions.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 