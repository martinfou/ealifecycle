<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Status Details') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.statuses.edit', $status) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Status
                </a>
                <a href="{{ route('admin.statuses.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Details Card -->
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status Name and Color -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">Status Information</h3>
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-6 h-6 rounded-full" style="background-color: {{ $status->color ?? '#6B7280' }}"></div>
                                <span class="text-xl font-semibold text-white">{{ $status->name }}</span>
                            </div>
                            <p class="text-gray-300">{{ $status->description ?? 'No description provided.' }}</p>
                        </div>

                        <!-- Status Stats -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">Statistics</h3>
                            <div class="bg-gray-700 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-400">Active Status</p>
                                        <p class="text-lg font-semibold text-white">
                                            @if($status->is_active)
                                                <span class="text-green-400">Yes</span>
                                            @else
                                                <span class="text-red-400">No</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Strategies Using This Status</p>
                                        <p class="text-lg font-semibold text-white">{{ $strategiesCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Strategies Using This Status -->
            @if($strategiesCount > 0)
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gray-800 border-b border-gray-700">
                        <h3 class="text-lg font-medium text-white mb-4">Strategies Using This Status</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Group</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date in Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    @foreach($status->strategies as $strategy)
                                        <tr class="hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('strategies.show', $strategy) }}" class="text-blue-400 hover:text-blue-300">
                                                    {{ $strategy->name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                                                {{ $strategy->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                                                {{ $strategy->group->name ?? 'No Group' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                                                {{ $strategy->date_in_status->format('Y-m-d H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Delete Status Section -->
            @if($strategiesCount === 0)
                <div class="mt-6 bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gray-800 border-b border-gray-700">
                        <h3 class="text-lg font-medium text-red-400 mb-4">Danger Zone</h3>
                        <p class="text-gray-300 mb-4">This status is not being used by any strategies. You can safely delete it.</p>
                        <form action="{{ route('admin.statuses.destroy', $status) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('Are you sure you want to delete this status? This action cannot be undone.')">
                                Delete Status
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 