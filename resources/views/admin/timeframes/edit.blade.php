<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Timeframe') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.timeframes.show', $timeframe) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    View Details
                </a>
                <a href="{{ route('admin.timeframes.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Back to Timeframes
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-6">Edit Timeframe: {{ $timeframe->name }}</h3>

                    <form method="POST" action="{{ route('admin.timeframes.update', $timeframe) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                Timeframe Name *
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   value="{{ old('name', $timeframe->name) }}"
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror"
                                   placeholder="e.g., M5, H1, D1"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">
                                Common naming conventions: M1, M5, M15, M30, H1, H4, D1, W1, MN1
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description"
                                      rows="3"
                                      class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('description') border-red-500 @enderror"
                                      placeholder="Describe this timeframe (e.g., 5 minute chart, 1 hour chart)">{{ old('description', $timeframe->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div class="mb-4">
                            <label for="sort_order" class="block text-sm font-medium text-gray-300 mb-2">
                                Sort Order
                            </label>
                            <input type="number" 
                                   name="sort_order" 
                                   id="sort_order"
                                   value="{{ old('sort_order', $timeframe->sort_order) }}"
                                   min="0"
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('sort_order') border-red-500 @enderror">
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">
                                Lower numbers appear first in the selection lists.
                            </p>
                        </div>

                        <!-- Active Status -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active"
                                       value="1"
                                       {{ old('is_active', $timeframe->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-600 rounded bg-gray-900">
                                <label for="is_active" class="ml-2 block text-sm text-gray-300">
                                    Active
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">
                                Only active timeframes will be available when creating new strategies.
                            </p>
                        </div>

                        <!-- Current Usage Info -->
                        @if($timeframe->strategies()->count() > 0)
                            <div class="mb-6 p-4 bg-gray-900 border border-gray-600 rounded-md">
                                <h4 class="text-sm font-medium text-blue-400 mb-2">Usage Information</h4>
                                <p class="text-sm text-gray-300">
                                    This timeframe is currently being used by {{ $timeframe->strategies()->count() }} strategy(ies).
                                    Changes to this timeframe will affect all associated strategies.
                                </p>
                            </div>
                        @endif

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.timeframes.show', $timeframe) }}" 
                               class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Update Timeframe
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Examples Info Box -->
            <div class="mt-6 bg-gray-900 border border-gray-600 rounded-md p-4">
                <h4 class="text-sm font-medium text-blue-400 mb-2">Common Timeframe Examples</h4>
                <div class="text-sm text-gray-300 space-y-1">
                    <div><strong class="text-white">M1:</strong> 1 minute chart</div>
                    <div><strong class="text-white">M5:</strong> 5 minute chart</div>
                    <div><strong class="text-white">M15:</strong> 15 minute chart</div>
                    <div><strong class="text-white">M30:</strong> 30 minute chart</div>
                    <div><strong class="text-white">H1:</strong> 1 hour chart</div>
                    <div><strong class="text-white">H4:</strong> 4 hour chart</div>
                    <div><strong class="text-white">D1:</strong> Daily chart</div>
                    <div><strong class="text-white">W1:</strong> Weekly chart</div>
                    <div><strong class="text-white">MN1:</strong> Monthly chart</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 