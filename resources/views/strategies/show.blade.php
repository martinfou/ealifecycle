<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start space-y-3 sm:space-y-0">
            <div class="flex-1 min-w-0">
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ $strategy->name }}
                </h2>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 flex-shrink-0">
                <a href="{{ route('strategies.edit', $strategy) }}" class="w-full sm:w-auto text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Edit Strategy
                </a>
                <a href="{{ route('strategies.index') }}" class="w-full sm:w-auto text-center bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Back to Strategies
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Success Messages -->
            @if (session('success'))
                <div class="p-4 bg-green-800 border border-green-600 rounded-md">
                    <div class="text-sm font-medium text-green-200">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Strategy Details Card -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-white">Strategy Details</h3>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                                  style="background-color: {{ $strategy->status->color ?? '#6B7280' }}20; color: {{ $strategy->status->color ?? '#6B7280' }}">
                                {{ $strategy->status->name }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Strategy Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Strategy Name</label>
                            <div class="text-sm text-white bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                {{ $strategy->name }}
                            </div>
                        </div>

                        <!-- Current Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Current Status</label>
                            <div class="text-sm bg-gray-900 px-3 py-2 rounded-md border border-gray-600 flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $strategy->status->color ?? '#6B7280' }}"></div>
                                <span class="text-white">{{ $strategy->status->name }}</span>
                                <span class="text-gray-400">(since {{ $strategy->date_in_status->format('M j, Y') }})</span>
                            </div>
                        </div>

                        <!-- Timeframes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Timeframes</label>
                            <div class="text-sm bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                @if($strategy->timeframes->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($strategy->timeframes->sortBy('sort_order') as $timeframe)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                                {{ $timeframe->pivot->is_primary ? 'bg-blue-900 text-blue-200 border border-blue-600' : 'bg-gray-700 text-gray-300 border border-gray-600' }}">
                                                {{ $timeframe->name }}
                                                @if($timeframe->pivot->is_primary)
                                                    <span class="ml-1 text-blue-300">•</span>
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                    @if($strategy->timeframes->where('pivot.is_primary', true)->first())
                                        <p class="text-xs text-gray-400 mt-1">
                                            Primary: {{ $strategy->timeframes->where('pivot.is_primary', true)->first()->name }}
                                        </p>
                                    @endif
                                @else
                                    <span class="text-gray-400">No timeframes assigned</span>
                                @endif
                            </div>
                        </div>

                        <!-- Symbols Traded -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Symbols Traded</label>
                            <div class="text-sm text-white bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                {{ $strategy->symbols_traded ?: 'Not specified' }}
                            </div>
                        </div>

                        <!-- Magic Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Magic Number</label>
                            <div class="text-sm text-white bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                {{ $strategy->magic_number ?: 'Not assigned' }}
                            </div>
                        </div>

                        <!-- Source Code -->
                        @if($strategy->source_code_path)
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Source Code</label>
                                <div class="text-sm bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                    <a href="{{ route('strategies.downloadSourceCode', $strategy) }}" 
                                       class="text-blue-400 hover:text-blue-300 font-medium">
                                       Download {{ $strategy->source_code_original_filename ?? 'source file' }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Created Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Created</label>
                            <div class="text-sm text-white bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                {{ $strategy->created_at->format('M j, Y g:i A') }}
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($strategy->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                            <div class="text-sm text-white bg-gray-900 px-3 py-2 rounded-md border border-gray-600">
                                {{ $strategy->description }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Change Card -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-4">Change Status</h3>
                    
                    <form method="POST" action="{{ route('strategies.change-status', $strategy) }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status_id" class="block text-sm font-medium text-gray-300 mb-2">New Status</label>
                                <select name="status_id" id="status_id" required
                                        class="w-full px-3 py-2 bg-gray-900 border border-gray-600 text-white rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500">
                                    <option value="">Select new status...</option>
                                    @foreach(\App\Models\Status::where('is_active', true)->get() as $status)
                                        <option value="{{ $status->id }}" {{ $status->id == $strategy->status_id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">Notes (Optional)</label>
                                <input type="text" name="notes" id="notes" 
                                       placeholder="Reason for status change..."
                                       class="w-full px-3 py-2 bg-gray-900 border border-gray-600 text-white placeholder-gray-400 rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Trades -->
            @if($strategy->trades->count() > 0)
                <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 bg-gray-800 border-b border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-white">Recent Trades (Last 10)</h3>
                            <a href="{{ route('trades.index', ['strategy' => $strategy->id]) }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">
                                View All Trades
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Symbol</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Lot Size</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Open Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Profit</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($strategy->trades as $trade)
                                        <tr class="hover:bg-gray-750 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $trade->symbol }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white capitalize">{{ $trade->type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $trade->lot_size }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $trade->open_time->format('M j, Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="font-medium {{ $trade->profit >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                                    ${{ number_format($trade->profit, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 bg-gray-800 border-b border-gray-700 text-center">
                        <h3 class="text-lg font-medium text-white mb-2">No Trades Found</h3>
                        <p class="text-gray-400 mb-4">This strategy doesn't have any imported trades yet.</p>
                        <a href="{{ route('trades.import') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                            Import Trades
                        </a>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-4">Actions</h3>
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('strategies.edit', $strategy) }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                            Edit Strategy
                        </a>
                        
                        <a href="{{ route('strategies.history', $strategy) }}" 
                           class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                            View Status History
                        </a>
                        
                        <form action="{{ route('strategies.destroy', $strategy) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full sm:w-auto text-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this strategy? This action cannot be undone and will also delete all associated status history.')">
                                Delete Strategy
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 