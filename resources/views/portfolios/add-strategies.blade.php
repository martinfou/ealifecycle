<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Add Strategies to: ') . $portfolio->name }}
            </h2>
            <a href="{{ route('portfolios.show', $portfolio) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Back to Portfolio
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if($availableStrategies->count() > 0)
                <form method="POST" action="{{ route('portfolios.store-strategies', $portfolio) }}">
                    @csrf
                    
                    <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="p-6">
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-white mb-2">Select Strategies to Add</h3>
                                <p class="text-sm text-gray-400">Choose the strategies you want to add to this portfolio and set their allocations.</p>
                            </div>

                            @if($errors->any())
                                <div class="mb-4 bg-red-800 border border-red-600 text-red-200 px-4 py-3 rounded">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="space-y-4">
                                @foreach($availableStrategies as $index => $strategy)
                                    <div class="border border-gray-700 rounded-lg p-4 bg-gray-900 strategy-card">
                                        <div class="flex items-start space-x-4">
                                            <!-- Strategy Selection -->
                                            <div class="flex items-center mt-2">
                                                <input type="checkbox" 
                                                       value="{{ $strategy->id }}"
                                                       id="strategy_{{ $strategy->id }}"
                                                       class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2"
                                                       onchange="toggleStrategyInputs({{ $strategy->id }}, {{ $index }})">
                                            </div>

                                            <!-- Strategy Info -->
                                            <div class="flex-1">
                                                <label for="strategy_{{ $strategy->id }}" class="text-lg font-medium text-white cursor-pointer">
                                                    {{ $strategy->name }}
                                                </label>
                                                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-400">
                                                    <span class="flex items-center space-x-1">
                                                        @if($strategy->timeframes->count() > 0)
                                                            @foreach($strategy->timeframes->sortBy('sort_order') as $timeframe)
                                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium 
                                                                    {{ $timeframe->pivot->is_primary ? 'bg-blue-800 text-blue-200' : 'bg-gray-700 text-gray-300' }}">
                                                                    {{ $timeframe->name }}
                                                                </span>
                                                            @endforeach
                                                        @endif
                                                    </span>
                                                    @if($strategy->symbols_traded)
                                                        <span>•</span>
                                                        <span>{{ $strategy->symbols_traded }}</span>
                                                    @endif
                                                    <span>•</span>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" 
                                                          style="background-color: {{ $strategy->status->color }}20; color: {{ $strategy->status->color }}">
                                                        {{ $strategy->status->name }}
                                                    </span>
                                                </div>
                                                @if($strategy->description)
                                                    <p class="mt-1 text-sm text-gray-300">{{ Str::limit($strategy->description, 150) }}</p>
                                                @endif
                                            </div>

                                            <!-- Allocation Inputs -->
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-96" id="inputs_{{ $strategy->id }}" style="display: none;">
                                                <!-- Hidden strategy_id field that gets enabled when checkbox is checked -->
                                                <input type="hidden" 
                                                       name="strategies[{{ $index }}][strategy_id]" 
                                                       value="{{ $strategy->id }}"
                                                       id="hidden_strategy_{{ $strategy->id }}"
                                                       disabled>
                                                
                                                <!-- Allocation Amount -->
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-300 mb-1">Amount ($)</label>
                                                    <input type="number" 
                                                           name="strategies[{{ $index }}][allocation_amount]" 
                                                           step="0.01" 
                                                           min="0"
                                                           id="amount_{{ $strategy->id }}"
                                                           class="w-full px-2 py-1 text-sm bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                                                           placeholder="0.00"
                                                           disabled>
                                                </div>

                                                <!-- Allocation Percentage -->
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-300 mb-1">Percentage (%)</label>
                                                    <input type="number" 
                                                           name="strategies[{{ $index }}][allocation_percent]" 
                                                           step="0.1" 
                                                           min="0" 
                                                           max="100"
                                                           id="percent_{{ $strategy->id }}"
                                                           class="w-full px-2 py-1 text-sm bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                                                           placeholder="0.0"
                                                           disabled>
                                                </div>

                                                <!-- Notes -->
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-300 mb-1">Notes</label>
                                                    <input type="text" 
                                                           name="strategies[{{ $index }}][notes]" 
                                                           id="notes_{{ $strategy->id }}"
                                                           class="w-full px-2 py-1 text-sm bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                                                           placeholder="Optional notes"
                                                           disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-8 flex items-center justify-between">
                                <div class="text-sm text-gray-400">
                                    <p>Select strategies to add them to your portfolio. You can set specific dollar amounts, percentages, or both.</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('portfolios.show', $portfolio) }}" 
                                       class="px-4 py-2 text-gray-300 hover:text-white transition-colors">
                                        Cancel
                                    </a>
                                    <button type="submit" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                                        Add Selected Strategies
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Portfolio Information -->
                <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-white mb-3">Portfolio Information</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <div class="text-gray-400">Initial Capital</div>
                            <div class="text-white font-medium">${{ number_format($portfolio->initial_capital, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400">Currently Allocated</div>
                            <div class="text-white font-medium">${{ number_format($portfolio->total_allocated_amount, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400">Allocation %</div>
                            <div class="text-white font-medium">{{ number_format($portfolio->total_allocated_percent, 1) }}%</div>
                        </div>
                        <div>
                            <div class="text-gray-400">Available</div>
                            <div class="text-white font-medium">${{ number_format($portfolio->initial_capital - $portfolio->total_allocated_amount, 2) }}</div>
                        </div>
                    </div>
                </div>

            @else
                <!-- No Available Strategies -->
                <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-white">No available strategies</h3>
                        <p class="mt-1 text-sm text-gray-400">All your strategies are already in this portfolio, or you need to create some strategies first.</p>
                        <div class="mt-6 flex justify-center space-x-4">
                            <a href="{{ route('strategies.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                Create New Strategy
                            </a>
                            <a href="{{ route('portfolios.show', $portfolio) }}" class="inline-flex items-center px-4 py-2 border border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600 transition-colors">
                                Back to Portfolio
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleStrategyInputs(strategyId, index) {
            const checkbox = document.getElementById('strategy_' + strategyId);
            const inputs = document.getElementById('inputs_' + strategyId);
            const hiddenInput = document.getElementById('hidden_strategy_' + strategyId);
            const amountInput = document.getElementById('amount_' + strategyId);
            const percentInput = document.getElementById('percent_' + strategyId);
            const notesInput = document.getElementById('notes_' + strategyId);
            
            if (checkbox.checked) {
                inputs.style.display = 'grid';
                // Enable all inputs for this strategy
                hiddenInput.disabled = false;
                amountInput.disabled = false;
                percentInput.disabled = false;
                notesInput.disabled = false;
            } else {
                inputs.style.display = 'none';
                // Disable all inputs and clear values
                hiddenInput.disabled = true;
                amountInput.disabled = true;
                amountInput.value = '';
                percentInput.disabled = true;
                percentInput.value = '';
                notesInput.disabled = true;
                notesInput.value = '';
            }
        }

        // Select all / deselect all functionality
        function toggleAll() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][id^="strategy_"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach((checkbox, index) => {
                checkbox.checked = !allChecked;
                const strategyId = checkbox.value;
                toggleStrategyInputs(strategyId, index);
            });
        }
    </script>
</x-app-layout> 