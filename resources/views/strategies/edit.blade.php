<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Strategy') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('strategies.show', $strategy) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    View Details
                </a>
                <a href="{{ route('strategies.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Back to Strategies
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-6">Edit Strategy: {{ $strategy->name }}</h3>
                    
                    <form method="POST" action="{{ route('strategies.update', $strategy) }}">
                        @csrf
                        @method('PUT')

                        <!-- Strategy Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-300">Strategy Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $strategy->name) }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Symbols Traded -->
                        <div class="mb-4">
                            <label for="symbols_traded" class="block text-sm font-medium text-gray-300">Symbols Traded</label>
                            <input type="text" name="symbols_traded" id="symbols_traded" value="{{ old('symbols_traded', $strategy->symbols_traded) }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('symbols_traded') border-red-500 @enderror"
                                   placeholder="e.g., EURUSD, GBPUSD, AAPL, TSLA, ES, CL">
                            @error('symbols_traded')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Enter symbols separated by commas. Examples: <strong>Forex:</strong> EURUSD, GBPJPY, XAUUSD • <strong>Stocks:</strong> AAPL, TSLA, SPY • <strong>Futures:</strong> ES, NQ, CL, GC</p>
                        </div>

                        <!-- Timeframes -->
                        <div class="mb-4">
                            <label for="timeframe_ids" class="block text-sm font-medium text-gray-300">Timeframes *</label>
                            <div class="mt-1 space-y-2">
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                    @foreach($timeframes as $timeframe)
                                        @php
                                            $isSelected = $strategy->timeframes->contains('id', $timeframe->id) || collect(old('timeframe_ids'))->contains($timeframe->id);
                                        @endphp
                                        <label class="flex items-center space-x-2 p-2 border border-gray-600 rounded-md hover:bg-gray-700 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="timeframe_ids[]" 
                                                   value="{{ $timeframe->id }}"
                                                   {{ $isSelected ? 'checked' : '' }}
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
                                    @php
                                        $isPrimary = $strategy->timeframes->where('pivot.is_primary', true)->first()?->id == $timeframe->id;
                                        $isSelected = old('primary_timeframe_id') == $timeframe->id || ($isPrimary && !old('primary_timeframe_id'));
                                    @endphp
                                    <option value="{{ $timeframe->id }}" 
                                            {{ $isSelected ? 'selected' : '' }}
                                            data-timeframe-id="{{ $timeframe->id }}">
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
                                    <option value="{{ $group->id }}" {{ old('group_id', $strategy->group_id) == $group->id ? 'selected' : '' }}>
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
                            <input type="number" name="magic_number" id="magic_number" value="{{ old('magic_number', $strategy->magic_number) }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('magic_number') border-red-500 @enderror"
                                   placeholder="e.g., 12345">
                            @error('magic_number')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Optional unique identifier used in your trading platform</p>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('description') border-red-500 @enderror"
                                      placeholder="Describe your trading strategy, entry/exit rules, etc.">{{ old('description', $strategy->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Status Info -->
                        <div class="mb-6 p-4 bg-gray-900 border border-gray-600 rounded-md">
                            <h4 class="text-sm font-medium text-blue-400 mb-2">Current Status</h4>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $strategy->status->color ?? '#6B7280' }}"></div>
                                <span class="text-sm text-gray-300">{{ $strategy->status->name }}</span>
                                <span class="text-sm text-gray-400">(since {{ $strategy->date_in_status->format('M j, Y') }})</span>
                            </div>
                            <p class="text-sm text-gray-300 mt-2">
                                To change the status, use the status change form on the strategy details page.
                            </p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('strategies.show', $strategy) }}" 
                               class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Update Strategy
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-gray-900 border border-gray-600 rounded-md p-4">
                <h4 class="text-sm font-medium text-blue-400 mb-2">Note</h4>
                <p class="text-sm text-gray-300">
                    Editing a strategy will not affect its current status or status history. 
                    To change the strategy status, use the status change form on the strategy details page.
                </p>
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