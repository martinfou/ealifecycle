<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Strategy Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @forelse($strategyCounts as $statusCount)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $statusCount->color }}"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">{{ $statusCount->name }}</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $statusCount->count }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center">
                            <p class="text-gray-500">No strategies yet. <a href="{{ route('strategies.create') }}" class="text-blue-600 hover:text-blue-800">Create your first strategy</a></p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="flex space-x-4">
                            <a href="{{ route('strategies.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add New Strategy
                            </a>
                            <a href="{{ route('trades.import') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Import Trades
                            </a>
                            <a href="{{ route('strategies.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                View All Strategies
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Strategies -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Strategies</h3>
                        @forelse($recentStrategies as $strategy)
                            <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('strategies.show', $strategy) }}" class="hover:text-blue-600">
                                            {{ $strategy->name }}
                                        </a>
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $strategy->timeframe->name }} • {{ $strategy->symbols_traded }}</p>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                          style="background-color: {{ $strategy->status->color }}20; color: {{ $strategy->status->color }}">
                                        {{ $strategy->status->name }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No strategies yet.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Status Changes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Status Changes</h3>
                        @forelse($recentStatusChanges as $change)
                            <div class="py-3 border-b border-gray-200 last:border-b-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $change->strategy->name }}</p>
                                        <p class="text-xs text-gray-500">
                                            @if($change->previousStatus)
                                                {{ $change->previousStatus->name }} → {{ $change->newStatus->name }}
                                            @else
                                                Created as {{ $change->newStatus->name }}
                                            @endif
                                        </p>
                                        @if($change->notes)
                                            <p class="text-xs text-gray-400 mt-1">{{ $change->notes }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $change->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No status changes yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Statistics Summary -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $strategyCounts->sum('count') }}</p>
                            <p class="text-sm text-gray-500">Total Strategies</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $totalTrades }}</p>
                            <p class="text-sm text-gray-500">Total Trades</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $recentStatusChanges->count() }}</p>
                            <p class="text-sm text-gray-500">Recent Changes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
