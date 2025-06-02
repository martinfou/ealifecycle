<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Portfolio: ') . $portfolio->name }}
            </h2>
            <a href="{{ route('portfolios.show', $portfolio) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Back to Portfolio
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('portfolios.update', $portfolio) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Portfolio Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-white mb-2">Portfolio Name</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $portfolio->name) }}"
                                   class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Enter portfolio name"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Describe your portfolio strategy and goals">{{ old('description', $portfolio->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Initial Capital -->
                        <div class="mb-6">
                            <label for="initial_capital" class="block text-sm font-medium text-white mb-2">Initial Capital</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-400 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       name="initial_capital" 
                                       id="initial_capital" 
                                       value="{{ old('initial_capital', $portfolio->initial_capital) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full pl-7 pr-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0.00"
                                       required>
                            </div>
                            @error('initial_capital')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-400">The starting capital for this portfolio</p>
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-white mb-2">Status</label>
                            <select name="status" 
                                    id="status"
                                    class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="active" {{ old('status', $portfolio->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="paused" {{ old('status', $portfolio->status) === 'paused' ? 'selected' : '' }}>Paused</option>
                                <option value="archived" {{ old('status', $portfolio->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('portfolios.show', $portfolio) }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                                Update Portfolio
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Portfolio Statistics -->
            @if($portfolio->strategies->count() > 0)
                <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-white mb-3">Current Portfolio Statistics</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <div class="text-gray-400">Total Strategies</div>
                            <div class="text-white font-medium">{{ $portfolio->total_strategies_count }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400">Active Strategies</div>
                            <div class="text-white font-medium">{{ $portfolio->active_strategies_count }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400">Total Allocated</div>
                            <div class="text-white font-medium">${{ number_format($portfolio->total_allocated_amount, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400">Allocation %</div>
                            <div class="text-white font-medium">{{ number_format($portfolio->total_allocated_percent, 1) }}%</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Warning about status changes -->
            <div class="mt-6 bg-yellow-800 border border-yellow-600 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-200">Status Change Impact</h3>
                        <div class="mt-2 text-sm text-yellow-300">
                            <p>• <strong>Pausing</strong> a portfolio will keep all strategy associations but mark them as inactive</p>
                            <p>• <strong>Archiving</strong> a portfolio will preserve all data but remove it from active management</p>
                            <p>• Strategy allocations and history will remain unchanged</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 