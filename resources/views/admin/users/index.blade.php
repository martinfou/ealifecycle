<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Manage Users') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Add New User
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900 border border-green-600 rounded-md">
                    <div class="text-sm font-medium text-green-200">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Error Messages -->
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-900 border border-red-600 rounded-md">
                    <div class="text-sm font-medium text-red-200">{{ session('error') }}</div>
                </div>
            @endif

            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-6">System Users</h3>
                    
                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Groups</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Strategies</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Trades Imported</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Joined</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($users as $user)
                                        <tr class="hover:bg-gray-750 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-white">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-300">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($user->groups->count() > 0)
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($user->groups as $group)
                                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-700 text-gray-300">
                                                                {{ $group->name }}
                                                                <span class="ml-1 text-xs px-1 py-0.5 rounded {{ $group->pivot->permission === 'write' ? 'bg-green-800 text-green-200' : 'bg-gray-600 text-gray-400' }}">
                                                                    {{ $group->pivot->permission }}
                                                                </span>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-500 text-sm">No groups</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $user->strategies_count }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $user->imported_trades_count }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $user->created_at->format('M j, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <a href="{{ route('admin.users.show', $user) }}" class="text-gray-300 hover:text-white transition-colors">
                                                    View
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-300 hover:text-white transition-colors">
                                                    Edit
                                                </a>
                                                @if($user->strategies_count == 0 && $user->imported_trades_count == 0)
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-400 hover:text-red-300 transition-colors"
                                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-500" title="Cannot delete - user has associated data">Delete</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-300 text-lg">No users found</div>
                            <div class="text-gray-400 mt-2">
                                <a href="{{ route('admin.users.create') }}" class="text-gray-300 hover:text-white transition-colors">
                                    Create your first user
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-gray-900 border border-gray-600 rounded-md p-4">
                <h4 class="text-sm font-medium text-blue-400 mb-2">User Management</h4>
                <p class="text-sm text-gray-300">
                    Manage system users, their group memberships, and permissions. 
                    Users with strategies or imported trades cannot be deleted to maintain data integrity.
                </p>
            </div>
        </div>
    </div>
</x-app-layout> 