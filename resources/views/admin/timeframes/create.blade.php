<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Timeframe') }}
            </h2>
            <a href="{{ route('admin.timeframes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Timeframes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Add New Trading Timeframe</h3>

                    <form method="POST" action="{{ route('admin.timeframes.store') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Timeframe Name *
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   value="{{ old('name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror"
                                   placeholder="e.g., M5, H1, D1"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Common naming conventions: M1, M5, M15, M30, H1, H4, D1, W1, MN1
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                                      placeholder="Describe this timeframe (e.g., 5 minute chart, 1 hour chart)">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div class="mb-6">
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                Sort Order
                            </label>
                            <input type="number" 
                                   name="sort_order" 
                                   id="sort_order"
                                   value="{{ old('sort_order') }}"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('sort_order') border-red-300 @enderror"
                                   placeholder="Optional - leave blank to auto-assign">
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Lower numbers appear first. Leave blank to automatically assign the highest number + 1.
                            </p>
                        </div>

                        <!-- Active Status -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                    Active
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Only active timeframes will be available when creating new strategies.
                            </p>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.timeframes.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Create Timeframe
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Examples Info Box -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                <h4 class="text-sm font-medium text-blue-900 mb-2">Common Timeframe Examples</h4>
                <div class="text-sm text-blue-700 space-y-1">
                    <div><strong>M1:</strong> 1 minute chart</div>
                    <div><strong>M5:</strong> 5 minute chart</div>
                    <div><strong>M15:</strong> 15 minute chart</div>
                    <div><strong>M30:</strong> 30 minute chart</div>
                    <div><strong>H1:</strong> 1 hour chart</div>
                    <div><strong>H4:</strong> 4 hour chart</div>
                    <div><strong>D1:</strong> Daily chart</div>
                    <div><strong>W1:</strong> Weekly chart</div>
                    <div><strong>MN1:</strong> Monthly chart</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 