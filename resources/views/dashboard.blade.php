<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Strategy Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @forelse($strategyCounts as $statusCount)
                    <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg hover:border-gray-600 transition-colors">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $statusCount->color }}"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-400">{{ $statusCount->name }}</p>
                                    <p class="text-2xl font-bold text-white">{{ $statusCount->count }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="p-6 text-center">
                            <p class="text-gray-400">No strategies yet. <a href="{{ route('strategies.create') }}" class="text-gray-300 hover:text-white transition-colors">Create your first strategy</a></p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-white mb-4">Quick Actions</h3>
                        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('strategies.create') }}" 
                               class="w-full sm:w-auto text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Add New Strategy
                            </a>
                            <a href="{{ route('trades.import') }}" 
                               class="w-full sm:w-auto text-center bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Import Trades
                            </a>
                            <a href="{{ route('strategies.index') }}" 
                               class="w-full sm:w-auto text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                View All Strategies
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Strategies -->
                <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-white mb-4">Recent Strategies</h3>
                        @forelse($recentStrategies as $strategy)
                            <div class="flex items-center justify-between py-3 border-b border-gray-700 last:border-b-0">
                                <div>
                                    <p class="text-sm font-medium text-white">
                                        <a href="{{ route('strategies.show', $strategy) }}" class="hover:text-gray-300 transition-colors">
                                            {{ $strategy->name }}
                                        </a>
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        @if($strategy->timeframes->count() > 0)
                                            {{ $strategy->timeframes->pluck('name')->join(', ') }}
                                        @else
                                            No timeframes
                                        @endif
                                        @if($strategy->symbols_traded)
                                            • {{ $strategy->symbols_traded }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                          style="background-color: {{ $strategy->status->color }}20; color: {{ $strategy->status->color }}">
                                        {{ $strategy->status->name }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 text-sm">No strategies yet.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Status Changes -->
                <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-white mb-4">Recent Status Changes</h3>
                        @forelse($recentStatusChanges as $change)
                            <div class="py-3 border-b border-gray-700 last:border-b-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-white">{{ $change->strategy->name }}</p>
                                        <p class="text-xs text-gray-400">
                                            @if($change->previousStatus)
                                                {{ $change->previousStatus->name }} → {{ $change->newStatus->name }}
                                            @else
                                                Created as {{ $change->newStatus->name }}
                                            @endif
                                        </p>
                                        @if($change->notes)
                                            <p class="text-xs text-gray-500 mt-1">{{ $change->notes }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $change->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 text-sm">No status changes yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Statistics Summary -->
            <div class="mt-8 bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-4">Statistics Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-300">{{ $strategyCounts->sum('count') }}</p>
                            <p class="text-sm text-gray-400">Total Strategies</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-300">{{ $totalTrades }}</p>
                            <p class="text-sm text-gray-400">Total Trades</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-300">{{ $recentStatusChanges->count() }}</p>
                            <p class="text-sm text-gray-400">Recent Changes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
