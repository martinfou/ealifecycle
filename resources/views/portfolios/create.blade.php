<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Create New Portfolio') }}
            </h2>
            <a href="{{ route('portfolios.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Back to Portfolios
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('portfolios.store') }}">
                        @csrf

                        <!-- Portfolio Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-white mb-2">Portfolio Name</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
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
                                      placeholder="Describe your portfolio strategy and goals">{{ old('description') }}</textarea>
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
                                       value="{{ old('initial_capital') }}"
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
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="paused" {{ old('status') === 'paused' ? 'selected' : '' }}>Paused</option>
                                <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('portfolios.index') }}" 
                               class="px-4 py-2 text-gray-300 hover:text-white transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                                Create Portfolio
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-white">About Portfolios</h3>
                        <div class="mt-2 text-sm text-gray-300 space-y-1">
                            <p>• Portfolios help you organize and manage multiple trading strategies</p>
                            <p>• You can allocate specific amounts or percentages to each strategy</p>
                            <p>• Track performance and manage risk across your entire portfolio</p>
                            <p>• Start with basic information - you can add strategies after creation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 