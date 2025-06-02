<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ $portfolio->name }}
                </h2>
                <div class="flex items-center space-x-4 mt-1 text-sm text-gray-400">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $portfolio->status === 'active' ? 'bg-green-800 text-green-200' : 
                           ($portfolio->status === 'paused' ? 'bg-yellow-800 text-yellow-200' : 'bg-gray-700 text-gray-300') }}">
                        {{ ucfirst($portfolio->status) }}
                    </span>
                    <span>${{ number_format($portfolio->initial_capital, 2) }} initial capital</span>
                    <span>Created {{ $portfolio->created_at->format('M j, Y') }}</span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('portfolios.add-strategies', $portfolio) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Add Strategies
                </a>
                {{-- Temporarily commented out for production hotfix --}}
                {{-- <a href="{{ route('portfolios.history', $portfolio) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    View History
                </a> --}}
                <a href="{{ route('portfolios.edit', $portfolio) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Edit Portfolio
                </a>
                <a href="{{ route('portfolios.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Back to Portfolios
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Portfolio Overview -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-4">Portfolio Overview</h3>
                    
                    @if($portfolio->description)
                        <p class="text-gray-300 mb-4">{{ $portfolio->description }}</p>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-gray-900 p-4 rounded-lg">
                            <div class="text-sm text-gray-400">Initial Capital</div>
                            <div class="text-xl font-semibold text-white">${{ number_format($portfolio->initial_capital, 2) }}</div>
                        </div>
                        <div class="bg-gray-900 p-4 rounded-lg">
                            <div class="text-sm text-gray-400">Allocated Amount</div>
                            <div class="text-xl font-semibold text-green-400">${{ number_format($portfolio->total_allocated_amount, 2) }}</div>
                        </div>
                        <div class="bg-gray-900 p-4 rounded-lg">
                            <div class="text-sm text-gray-400">Allocated Percentage</div>
                            <div class="text-xl font-semibold text-blue-400">{{ number_format($portfolio->total_allocated_percent, 1) }}%</div>
                        </div>
                        <div class="bg-gray-900 p-4 rounded-lg">
                            <div class="text-sm text-gray-400">Active Strategies</div>
                            <div class="text-xl font-semibold text-white">{{ $portfolio->active_strategies_count }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Portfolio Strategies -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-white">Strategies in Portfolio</h3>
                        <a href="{{ route('portfolios.add-strategies', $portfolio) }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium transition-colors">
                            + Add More Strategies
                        </a>
                    </div>

                    @forelse($portfolio->strategies as $strategy)
                        <div class="flex items-center justify-between p-4 border border-gray-700 rounded-lg mb-4 last:mb-0 bg-gray-900">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-white">
                                            <a href="{{ route('strategies.show', $strategy) }}" class="hover:text-gray-300 transition-colors">
                                                {{ $strategy->name }}
                                            </a>
                                        </h4>
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
                                            <span>{{ $strategy->status->name }}</span>
                                        </div>
                                        
                                        <!-- Allocation Info -->
                                        <div class="mt-2 flex items-center space-x-6 text-sm">
                                            @if($strategy->pivot->allocation_amount > 0)
                                                <span class="text-green-400">
                                                    ${{ number_format($strategy->pivot->allocation_amount, 2) }}
                                                </span>
                                            @endif
                                            @if($strategy->pivot->allocation_percent > 0)
                                                <span class="text-blue-400">
                                                    {{ number_format($strategy->pivot->allocation_percent, 1) }}%
                                                </span>
                                            @endif
                                            <span class="text-gray-400">
                                                Added {{ \Carbon\Carbon::parse($strategy->pivot->date_added)->format('M j, Y') }}
                                            </span>
                                            @if($strategy->pivot->notes)
                                                <span class="text-gray-500">• {{ Str::limit($strategy->pivot->notes, 50) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            {{ $strategy->pivot->status === 'active' ? 'bg-green-800 text-green-200' : 'bg-yellow-800 text-yellow-200' }}">
                                            {{ ucfirst($strategy->pivot->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <button onclick="openEditModal({{ $strategy->id }}, '{{ $strategy->name }}', {{ $strategy->pivot->allocation_amount }}, {{ $strategy->pivot->allocation_percent }}, '{{ $strategy->pivot->status }}', '{{ addslashes($strategy->pivot->notes ?? '') }}')" 
                                        class="text-blue-400 hover:text-blue-300 text-sm font-medium transition-colors">
                                    Edit Allocation
                                </button>
                                <form method="POST" action="{{ route('portfolios.remove-strategy', [$portfolio, $strategy]) }}" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to remove this strategy from the portfolio?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium transition-colors">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-white">No strategies in portfolio</h3>
                            <p class="mt-1 text-sm text-gray-400">Start building your portfolio by adding some trading strategies.</p>
                            <div class="mt-6">
                                <a href="{{ route('portfolios.add-strategies', $portfolio) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                    Add Strategies
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Allocation Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-medium text-white mb-4">Edit Strategy Allocation</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-white mb-2">Allocation Amount ($)</label>
                    <input type="number" name="allocation_amount" id="edit_allocation_amount" step="0.01" min="0" 
                           class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-white mb-2">Allocation Percentage (%)</label>
                    <input type="number" name="allocation_percent" id="edit_allocation_percent" step="0.1" min="0" max="100" 
                           class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-white mb-2">Status</label>
                    <select name="status" id="edit_status" 
                            class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="active">Active</option>
                        <option value="paused">Paused</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-white mb-2">Notes</label>
                    <textarea name="notes" id="edit_notes" rows="3"
                              class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 text-gray-300 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        Update Allocation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(strategyId, strategyName, amount, percent, status, notes) {
            document.getElementById('editForm').action = `{{ route('portfolios.update-strategy-allocation', [$portfolio, '__STRATEGY__']) }}`.replace('__STRATEGY__', strategyId);
            document.getElementById('edit_allocation_amount').value = amount || '';
            document.getElementById('edit_allocation_percent').value = percent || '';
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_notes').value = notes || '';
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</x-app-layout> 