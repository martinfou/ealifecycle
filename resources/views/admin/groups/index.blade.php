<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Manage Groups') }}
            </h2>
            <a href="{{ route('admin.groups.create') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Add New Group
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
                    <h3 class="text-lg font-medium text-white mb-6">User Groups</h3>
                    
                    @if($groups->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Users</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Strategies</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($groups as $group)
                                        <tr class="hover:bg-gray-750 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-white">{{ $group->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-300">
                                                {{ $group->description ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $group->users_count }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $group->strategies_count }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <a href="{{ route('admin.groups.show', $group) }}" class="text-gray-300 hover:text-white transition-colors">
                                                    View
                                                </a>
                                                <a href="{{ route('admin.groups.edit', $group) }}" class="text-gray-300 hover:text-white transition-colors">
                                                    Edit
                                                </a>
                                                @if($group->strategies_count == 0)
                                                    <form action="{{ route('admin.groups.destroy', $group) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-400 hover:text-red-300 transition-colors"
                                                                onclick="return confirm('Are you sure you want to delete this group?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-500" title="Cannot delete - group has associated strategies">Delete</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-300 text-lg">No groups found</div>
                            <div class="text-gray-400 mt-2">
                                <a href="{{ route('admin.groups.create') }}" class="text-gray-300 hover:text-white transition-colors">
                                    Create your first group
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-gray-900 border border-gray-600 rounded-md p-4">
                <h4 class="text-sm font-medium text-blue-400 mb-2">About User Groups</h4>
                <p class="text-sm text-gray-300">
                    Groups allow you to organize users and control access to strategies. 
                    Users can have read-only or write permissions in each group. 
                    Strategies assigned to a group become visible to all group members based on their permissions.
                </p>
            </div>
        </div>
    </div>
</x-app-layout> 