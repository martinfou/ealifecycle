<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Timeframe Details') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.timeframes.edit', $timeframe) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Edit Timeframe
                </a>
                <a href="{{ route('admin.timeframes.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Back to Timeframes
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900 border border-green-600 rounded-md">
                    <div class="text-sm font-medium text-green-200">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Timeframe Details -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-6">{{ $timeframe->name }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Name</label>
                            <div class="text-sm text-white bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                {{ $timeframe->name }}
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                            <div class="text-sm text-white bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                {{ $timeframe->description ?: 'No description provided' }}
                            </div>
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Sort Order</label>
                            <div class="text-sm text-white bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                {{ $timeframe->sort_order }}
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                            <div class="text-sm bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                @if($timeframe->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300 border border-green-600">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300 border border-red-600">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Strategies Count -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Strategies Using This Timeframe</label>
                            <div class="text-sm text-white bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                {{ $strategiesCount }} strategy(ies)
                            </div>
                        </div>

                        <!-- Created/Updated -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Created</label>
                            <div class="text-sm text-white bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                {{ $timeframe->created_at->format('M j, Y g:i A') }}
                                @if($timeframe->updated_at != $timeframe->created_at)
                                    <br><span class="text-gray-400">Updated: {{ $timeframe->updated_at->format('M j, Y g:i A') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-4">Actions</h3>
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.timeframes.edit', $timeframe) }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                            Edit Timeframe
                        </a>
                        
                        @if($strategiesCount == 0)
                            <form action="{{ route('admin.timeframes.destroy', $timeframe) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors"
                                        onclick="return confirm('Are you sure you want to delete this timeframe? This action cannot be undone.')">
                                    Delete Timeframe
                                </button>
                            </form>
                        @else
                            <button type="button" 
                                    class="bg-gray-500 text-gray-300 font-bold py-2 px-4 rounded-lg cursor-not-allowed"
                                    title="Cannot delete - timeframe is in use by {{ $strategiesCount }} strategy(ies)"
                                    disabled>
                                Delete Timeframe
                            </button>
                        @endif
                    </div>

                    @if($strategiesCount > 0)
                        <p class="mt-2 text-sm text-red-400">
                            This timeframe cannot be deleted because it is currently being used by {{ $strategiesCount }} strategy(ies).
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 