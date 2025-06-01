<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Trade History') }}
            </h2>
            <a href="{{ route('trades.import') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Import Trades
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-4">Filters</h3>
                    <form method="GET" action="{{ route('trades.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="strategy_id" class="block text-sm font-medium text-gray-300">Strategy</label>
                            <select name="strategy_id" id="strategy_id" class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500">
                                <option value="">All Strategies</option>
                                @foreach($strategies as $strategy)
                                    <option value="{{ $strategy->id }}" {{ request('strategy_id') == $strategy->id ? 'selected' : '' }}>
                                        {{ $strategy->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-300">Date From</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500">
                        </div>
                        
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-300">Date To</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg mr-2 transition-colors">
                                Filter
                            </button>
                            <a href="{{ route('trades.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Trades Summary -->
            @if($trades->count() > 0)
                <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                    <div class="p-6 bg-gray-800 border-b border-gray-700">
                        <h3 class="text-lg font-medium text-white mb-4">Summary</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-gray-900 border border-gray-700 p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-400">Total Trades</div>
                                <div class="text-2xl font-bold text-white">{{ $trades->total() }}</div>
                            </div>
                            <div class="bg-gray-900 border border-gray-700 p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-400">Total Profit</div>
                                <div class="text-2xl font-bold text-green-400">
                                    ${{ number_format($trades->sum('profit'), 2) }}
                                </div>
                            </div>
                            <div class="bg-gray-900 border border-gray-700 p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-400">Winning Trades</div>
                                <div class="text-2xl font-bold text-white">
                                    {{ $trades->where('profit', '>', 0)->count() }}
                                </div>
                            </div>
                            <div class="bg-gray-900 border border-gray-700 p-4 rounded-lg">
                                <div class="text-sm font-medium text-gray-400">Losing Trades</div>
                                <div class="text-2xl font-bold text-white">
                                    {{ $trades->where('profit', '<', 0)->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Trades Table -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-4">Trades</h3>
                    
                    @if($trades->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Ticket</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Strategy</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Symbol</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Size</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Open Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Close Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Profit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($trades as $trade)
                                        <tr class="hover:bg-gray-750 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                                {{ $trade->ticket }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $trade->strategy ? $trade->strategy->name : 'No Strategy' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $trade->symbol }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $trade->type }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ number_format($trade->size, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $trade->open_time ? \Carbon\Carbon::parse($trade->open_time)->format('M j, Y H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $trade->close_time ? \Carbon\Carbon::parse($trade->close_time)->format('M j, Y H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="font-medium {{ $trade->profit >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                                    ${{ number_format($trade->profit, 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('trades.show', $trade) }}" class="text-gray-300 hover:text-white transition-colors">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $trades->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-300 text-lg">No trades found</div>
                            <div class="text-gray-400 mt-2">
                                @if(request()->hasAny(['strategy_id', 'date_from', 'date_to']))
                                    Try adjusting your filters or 
                                    <a href="{{ route('trades.index') }}" class="text-gray-300 hover:text-white transition-colors">clear all filters</a>
                                @else
                                    <a href="{{ route('trades.import') }}" class="text-gray-300 hover:text-white transition-colors">Import your first trades</a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 