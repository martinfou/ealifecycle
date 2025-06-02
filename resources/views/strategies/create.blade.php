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
                                   placeholder="e.g., EURUSD, GBPUSD, AAPL, TSLA, ES, CL"
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('symbols_traded') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-400">Enter symbols separated by commas. Examples: <strong>Forex:</strong> EURUSD, GBPJPY, XAUUSD • <strong>Stocks:</strong> AAPL, TSLA, SPY • <strong>Futures:</strong> ES, NQ, CL, GC</p>
                            @error('symbols_traded')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Timeframes -->
                        <div class="mb-4">
                            <label for="timeframe_ids" class="block text-sm font-medium text-gray-300">Timeframes *</label>
                            <div class="mt-1 space-y-2">
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                    @foreach($timeframes as $timeframe)
                                        <label class="flex items-center space-x-2 p-2 border border-gray-600 rounded-md hover:bg-gray-700 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="timeframe_ids[]" 
                                                   value="{{ $timeframe->id }}"
                                                   {{ collect(old('timeframe_ids'))->contains($timeframe->id) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-600 rounded bg-gray-900"
                                                   onchange="updatePrimaryTimeframeOptions()">
                                            <span class="text-sm text-white font-medium">{{ $timeframe->name }}</span>
                                            @if($timeframe->description)
                                                <span class="text-xs text-gray-400">- {{ $timeframe->description }}</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @error('timeframe_ids')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Select all timeframes this strategy will use</p>
                        </div>

                        <!-- Primary Timeframe -->
                        <div class="mb-4">
                            <label for="primary_timeframe_id" class="block text-sm font-medium text-gray-300">Primary Timeframe *</label>
                            <select name="primary_timeframe_id" id="primary_timeframe_id" required
                                    class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('primary_timeframe_id') border-red-500 @enderror">
                                <option value="">Select primary timeframe...</option>
                                @foreach($timeframes as $timeframe)
                                    <option value="{{ $timeframe->id }}" 
                                            {{ old('primary_timeframe_id') == $timeframe->id ? 'selected' : '' }}
                                            data-timeframe-id="{{ $timeframe->id }}"
                                            style="display: none;">
                                        {{ $timeframe->name }}
                                        @if($timeframe->description)
                                            - {{ $timeframe->description }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('primary_timeframe_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Choose the main timeframe for strategy decisions</p>
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

    <script>
        function updatePrimaryTimeframeOptions() {
            const checkboxes = document.querySelectorAll('input[name="timeframe_ids[]"]');
            const primarySelect = document.getElementById('primary_timeframe_id');
            const selectedTimeframes = [];
            
            // Get all checked timeframes
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedTimeframes.push(checkbox.value);
                }
            });
            
            // Show/hide options in primary timeframe select
            const options = primarySelect.querySelectorAll('option[data-timeframe-id]');
            options.forEach(option => {
                const timeframeId = option.getAttribute('data-timeframe-id');
                if (selectedTimeframes.includes(timeframeId)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                    // Clear selection if this option was selected but is no longer available
                    if (option.selected) {
                        option.selected = false;
                        primarySelect.value = '';
                    }
                }
            });
            
            // If only one timeframe is selected, auto-select it as primary
            if (selectedTimeframes.length === 1) {
                primarySelect.value = selectedTimeframes[0];
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updatePrimaryTimeframeOptions();
        });
    </script>
</x-app-layout> 