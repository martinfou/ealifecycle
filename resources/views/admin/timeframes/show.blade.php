<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Timeframe Details') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.timeframes.edit', $timeframe) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit Timeframe
                </a>
                <a href="{{ route('admin.timeframes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Timeframes
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-md">
                    <div class="text-sm font-medium text-green-900">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Timeframe Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">{{ $timeframe->name }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <div class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md border">
                                {{ $timeframe->name }}
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <div class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md border">
                                {{ $timeframe->description ?: 'No description provided' }}
                            </div>
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <div class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md border">
                                {{ $timeframe->sort_order }}
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="text-sm bg-gray-50 px-3 py-2 rounded-md border">
                                @if($timeframe->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Strategies Count -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Strategies Using This Timeframe</label>
                            <div class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md border">
                                {{ $strategiesCount }} strategy(ies)
                            </div>
                        </div>

                        <!-- Created/Updated -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Created</label>
                            <div class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md border">
                                {{ $timeframe->created_at->format('M j, Y g:i A') }}
                                @if($timeframe->updated_at != $timeframe->created_at)
                                    <br><span class="text-gray-500">Updated: {{ $timeframe->updated_at->format('M j, Y g:i A') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.timeframes.edit', $timeframe) }}" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Edit Timeframe
                        </a>
                        
                        @if($strategiesCount == 0)
                            <form action="{{ route('admin.timeframes.destroy', $timeframe) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('Are you sure you want to delete this timeframe? This action cannot be undone.')">
                                    Delete Timeframe
                                </button>
                            </form>
                        @else
                            <button type="button" 
                                    class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed"
                                    title="Cannot delete - timeframe is in use by {{ $strategiesCount }} strategy(ies)"
                                    disabled>
                                Delete Timeframe
                            </button>
                        @endif
                    </div>

                    @if($strategiesCount > 0)
                        <p class="mt-2 text-sm text-red-600">
                            This timeframe cannot be deleted because it is currently being used by {{ $strategiesCount }} strategy(ies).
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 