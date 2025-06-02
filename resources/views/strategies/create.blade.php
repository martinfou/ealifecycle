<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Create New Strategy') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('strategies.store') }}">
                        @csrf

                        <!-- Strategy Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-300">Strategy Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Symbols Traded -->
                        <div class="mb-4">
                            <label for="symbols_traded" class="block text-sm font-medium text-gray-300">Symbols Traded</label>
                            <input type="text" name="symbols_traded" id="symbols_traded" value="{{ old('symbols_traded') }}" 
                                   placeholder="e.g., EURUSD, GBPUSD, XAUUSD"
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('symbols_traded') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-400">Enter the symbols this strategy trades, separated by commas</p>
                            @error('symbols_traded')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Timeframe -->
                        <div class="mb-4">
                            <label for="timeframe_id" class="block text-sm font-medium text-gray-300">Timeframe *</label>
                            <select name="timeframe_id" id="timeframe_id" 
                                    class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('timeframe_id') border-red-500 @enderror" 
                                    required>
                                <option value="">Select a timeframe</option>
                                @foreach($timeframes as $timeframe)
                                    <option value="{{ $timeframe->id }}" {{ old('timeframe_id') == $timeframe->id ? 'selected' : '' }}>
                                        {{ $timeframe->name }} - {{ $timeframe->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('timeframe_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Group -->
                        <div class="mb-4">
                            <label for="group_id" class="block text-sm font-medium text-gray-300">Group</label>
                            <select name="group_id" id="group_id" 
                                    class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('group_id') border-red-500 @enderror">
                                <option value="">No group (private to you)</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }} - {{ $group->description }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-400">Select a group to share this strategy with other members</p>
                            @error('group_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Magic Number -->
                        <div class="mb-4">
                            <label for="magic_number" class="block text-sm font-medium text-gray-300">Magic Number</label>
                            <input type="number" name="magic_number" id="magic_number" value="{{ old('magic_number') }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('magic_number') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-400">Optional unique identifier used in your trading platform</p>
                            @error('magic_number')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Initial Status -->
                        <div class="mb-4">
                            <label for="status_id" class="block text-sm font-medium text-gray-300">Initial Status *</label>
                            <select name="status_id" id="status_id" 
                                    class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('status_id') border-red-500 @enderror" 
                                    required>
                                <option value="">Select initial status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }} - {{ $status->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      placeholder="Optional description of your strategy..."
                                      class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('strategies.index') }}" 
                               class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Create Strategy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 