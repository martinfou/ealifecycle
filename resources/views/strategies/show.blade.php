<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $strategy->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('strategies.edit', $strategy) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit Strategy
                </a>
                <a href="{{ route('strategies.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Strategies
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Success Messages -->
            @if (session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-md">
                    <div class="text-sm font-medium text-green-900">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Strategy Details Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Strategy Details</h3>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Strategy Name</label>
                            <div class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md border">
                                {{ $strategy->name }}
                            </div>
                        </div>

                        <!-- Current Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Status</label>
                            <div class="text-sm bg-gray-50 px-3 py-2 rounded-md border flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $strategy->status->color ?? '#6B7280' }}"></div>
                                <span>{{ $strategy->status->name }}</span>
                                <span class="text-gray-500">(since {{ $strategy->date_in_status->format('M j, Y') }})</span>
                            </div>
                        </div>

                        <!-- Timeframe -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Timeframe</label>
                            <div class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md border">
                                {{ $strategy->timeframe->name }}
                                @if($strategy->timeframe->description)
                                    <span class="text-gray-500">({{ $strategy->timeframe->description }})</span>
                                @endif
                            </div>
                        </div>

                        <!-- Symbols Traded -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Symbols Traded</label>
                            <div class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md border">
                                {{ $strategy->symbols_traded ?: 'Not specified' }}
                            </div>
                        </div>

                        <!-- Magic Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Magic Number</label>
                            <div class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md border">
                                {{ $strategy->magic_number ?: 'Not assigned' }}
                            </div>
                        </div>

                        <!-- Created Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Created</label>
                            <div class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md border">
                                {{ $strategy->created_at->format('M j, Y g:i A') }}
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($strategy->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <div class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md border">
                                {{ $strategy->description }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Change Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Change Status</h3>
                    
                    <form method="POST" action="{{ route('strategies.change-status', $strategy) }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status_id" class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                                <select name="status_id" id="status_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select new status...</option>
                                    @foreach(\App\Models\Status::where('is_active', true)->get() as $status)
                                        <option value="{{ $status->id }}" {{ $status->id == $strategy->status_id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                <input type="text" name="notes" id="notes" 
                                       placeholder="Reason for status change..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Trades -->
            @if($strategy->trades->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Recent Trades (Last 10)</h3>
                            <a href="{{ route('trades.index', ['strategy' => $strategy->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View All Trades
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Symbol</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lot Size</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Open Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($strategy->trades as $trade)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trade->symbol }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">{{ $trade->type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trade->lot_size }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trade->open_time->format('M j, Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="font-medium {{ $trade->profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 text-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Trades Found</h3>
                        <p class="text-gray-500 mb-4">This strategy doesn't have any imported trades yet.</p>
                        <a href="{{ route('trades.import') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Import Trades
                        </a>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('strategies.edit', $strategy) }}" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Edit Strategy
                        </a>
                        
                        <a href="{{ route('strategies.history', $strategy) }}" 
                           class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            View Status History
                        </a>
                        
                        <form action="{{ route('strategies.destroy', $strategy) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
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