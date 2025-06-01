<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Strategy') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('strategies.show', $strategy) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    View Details
                </a>
                <a href="{{ route('strategies.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Strategies
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Edit Strategy: {{ $strategy->name }}</h3>
                    
                    <form method="POST" action="{{ route('strategies.update', $strategy) }}">
                        @csrf
                        @method('PUT')

                        <!-- Strategy Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Strategy Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $strategy->name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Symbols Traded -->
                        <div class="mb-4">
                            <label for="symbols_traded" class="block text-sm font-medium text-gray-700">Symbols Traded</label>
                            <input type="text" name="symbols_traded" id="symbols_traded" value="{{ old('symbols_traded', $strategy->symbols_traded) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('symbols_traded') border-red-500 @enderror"
                                   placeholder="e.g., EURUSD, GBPUSD, USDJPY">
                            @error('symbols_traded')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Enter the currency pairs or symbols this strategy trades</p>
                        </div>

                        <!-- Timeframe -->
                        <div class="mb-4">
                            <label for="timeframe_id" class="block text-sm font-medium text-gray-700">Timeframe *</label>
                            <select name="timeframe_id" id="timeframe_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('timeframe_id') border-red-500 @enderror">
                                <option value="">Select timeframe...</option>
                                @foreach($timeframes as $timeframe)
                                    <option value="{{ $timeframe->id }}" {{ old('timeframe_id', $strategy->timeframe_id) == $timeframe->id ? 'selected' : '' }}>
                                        {{ $timeframe->name }}
                                        @if($timeframe->description)
                                            - {{ $timeframe->description }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('timeframe_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Magic Number -->
                        <div class="mb-4">
                            <label for="magic_number" class="block text-sm font-medium text-gray-700">Magic Number</label>
                            <input type="number" name="magic_number" id="magic_number" value="{{ old('magic_number', $strategy->magic_number) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('magic_number') border-red-500 @enderror"
                                   placeholder="e.g., 12345">
                            @error('magic_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Optional unique identifier used in your trading platform</p>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                      placeholder="Describe your trading strategy, entry/exit rules, etc.">{{ old('description', $strategy->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Status Info -->
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Current Status</h4>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $strategy->status->color ?? '#6B7280' }}"></div>
                                <span class="text-sm text-blue-700">{{ $strategy->status->name }}</span>
                                <span class="text-sm text-blue-600">(since {{ $strategy->date_in_status->format('M j, Y') }})</span>
                            </div>
                            <p class="text-sm text-blue-700 mt-2">
                                To change the status, use the status change form on the strategy details page.
                            </p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('strategies.show', $strategy) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Strategy
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <h4 class="text-sm font-medium text-yellow-900 mb-2">Note</h4>
                <p class="text-sm text-yellow-700">
                    Editing a strategy will not affect its current status or status history. 
                    To change the strategy status, use the status change form on the strategy details page.
                </p>
            </div>
        </div>
    </div>
</x-app-layout> 