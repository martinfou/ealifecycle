<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('User: ') . $user->name }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Back to Users
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

            <!-- User Details -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-4">User Details</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-400">Name</dt>
                            <dd class="mt-1 text-sm text-white">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-white">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-400">Joined</dt>
                            <dd class="mt-1 text-sm text-white">{{ $user->created_at->format('M j, Y \a\t g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-400">Last Updated</dt>
                            <dd class="mt-1 text-sm text-white">{{ $user->updated_at->format('M j, Y \a\t g:i A') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Group Memberships -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-white">Group Memberships ({{ $user->groups->count() }})</h3>
                    </div>

                    <!-- Group Assignment Form -->
                    <div class="mb-6 p-4 bg-gray-900 border border-gray-600 rounded-md">
                        <h4 class="text-sm font-medium text-gray-300 mb-3">Manage Group Assignments</h4>
                        <form action="{{ route('admin.users.assign-groups', $user) }}" method="POST">
                            @csrf
                            <div class="space-y-3" id="group-assignments">
                                @foreach(App\Models\Group::all() as $group)
                                    @php
                                        $userGroup = $user->groups->where('id', $group->id)->first();
                                        $isAssigned = $userGroup !== null;
                                        $permission = $userGroup ? $userGroup->pivot->permission : 'read';
                                    @endphp
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" 
                                               name="groups[]" 
                                               value="{{ $group->id }}" 
                                               {{ $isAssigned ? 'checked' : '' }}
                                               id="group_{{ $group->id }}"
                                               class="rounded bg-gray-800 border-gray-600 text-gray-600 focus:ring-gray-500">
                                        <label for="group_{{ $group->id }}" class="flex-1 text-sm text-gray-300">
                                            {{ $group->name }} - {{ $group->description }}
                                        </label>
                                        <select name="permissions[]" class="text-sm bg-gray-800 border-gray-600 text-white rounded-md">
                                            <option value="read" {{ $permission === 'read' ? 'selected' : '' }}>Read Only</option>
                                            <option value="write" {{ $permission === 'write' ? 'selected' : '' }}>Read & Write</option>
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                    Update Group Assignments
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Current Groups -->
                    @if($user->groups->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($user->groups as $group)
                                <div class="p-4 border border-gray-700 rounded-lg bg-gray-900">
                                    <h4 class="text-sm font-medium text-white">{{ $group->name }}</h4>
                                    <p class="text-xs text-gray-300 mt-1">{{ $group->description }}</p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-xs px-2 py-1 rounded {{ $group->pivot->permission === 'write' ? 'bg-green-800 text-green-200' : 'bg-gray-700 text-gray-300' }}">
                                            {{ ucfirst($group->pivot->permission) }} Permission
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            Since {{ $group->pivot->created_at ? $group->pivot->created_at->format('M j, Y') : 'Unknown' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400">Not a member of any groups.</div>
                            <div class="text-sm text-gray-500 mt-2">Use the form above to assign groups.</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User's Strategies -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-6">Created Strategies ({{ $user->strategies->count() }})</h3>

                    @if($user->strategies->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->strategies as $strategy)
                                <div class="flex items-center justify-between p-4 border border-gray-700 rounded-lg bg-gray-900">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-white">{{ $strategy->name }}</h4>
                                        <div class="mt-1 flex items-center space-x-4 text-xs text-gray-400">
                                            <span>Status: {{ $strategy->status->name }}</span>
                                            @if($strategy->symbols_traded)
                                                <span>•</span>
                                                <span>{{ $strategy->symbols_traded }}</span>
                                            @endif
                                            @if($strategy->group)
                                                <span>•</span>
                                                <span>Group: {{ $strategy->group->name }}</span>
                                            @else
                                                <span>•</span>
                                                <span>Private</span>
                                            @endif
                                        </div>
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
                            <div class="text-gray-400">No strategies created yet.</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Trade Imports -->
            @if($user->importedTrades->count() > 0)
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-6">Recent Trade Imports ({{ $user->importedTrades->count() }} total)</h3>
                    <div class="text-sm text-gray-300">
                        Last import: {{ $user->importedTrades->sortByDesc('created_at')->first()->created_at->format('M j, Y \a\t g:i A') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout> 