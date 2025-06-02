<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ __('Portfolio History: ') . $portfolio->name }}
                </h2>
                <div class="flex items-center space-x-4 mt-1 text-sm text-gray-400">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $portfolio->status === 'active' ? 'bg-green-800 text-green-200' : 
                           ($portfolio->status === 'paused' ? 'bg-yellow-800 text-yellow-200' : 'bg-gray-700 text-gray-300') }}">
                        {{ ucfirst($portfolio->status) }}
                    </span>
                    <span>${{ number_format($portfolio->initial_capital, 2) }} initial capital</span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('portfolios.show', $portfolio) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Back to Portfolio
                </a>
                <a href="{{ route('portfolios.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    All Portfolios
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-white">Activity History</h3>
                        <div class="text-sm text-gray-400">
                            {{ $history->total() }} total activities
                        </div>
                    </div>

                    @forelse($history as $activity)
                        <div class="flex items-start space-x-4 pb-6 mb-6 border-b border-gray-700 last:border-b-0 last:pb-0 last:mb-0">
                            <!-- Activity Icon -->
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium
                                    {{ $activity->action_type === 'created' ? 'bg-blue-800 text-blue-200' :
                                       ($activity->action_type === 'updated' || $activity->action_type === 'status_changed' ? 'bg-yellow-800 text-yellow-200' :
                                       (str_contains($activity->action_type, 'added') || str_contains($activity->action_type, 'activated') ? 'bg-green-800 text-green-200' :
                                       (str_contains($activity->action_type, 'removed') || str_contains($activity->action_type, 'paused') ? 'bg-red-800 text-red-200' : 'bg-gray-700 text-gray-300'))) }}">
                                    @if($activity->action_type === 'created')
                                        ✨
                                    @elseif($activity->action_type === 'updated' || $activity->action_type === 'status_changed')
                                        ✏️
                                    @elseif(str_contains($activity->action_type, 'added') || str_contains($activity->action_type, 'activated'))
                                        ➕
                                    @elseif(str_contains($activity->action_type, 'removed'))
                                        ➖
                                    @elseif(str_contains($activity->action_type, 'paused'))
                                        ⏸️
                                    @else
                                        📝
                                    @endif
                                </div>
                            </div>

                            <!-- Activity Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-white">
                                            {{ $activity->detailed_description }}
                                        </p>
                                        <div class="mt-1 flex items-center space-x-4 text-xs text-gray-400">
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $activity->user->name }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $activity->created_at->format('M j, Y') }} at {{ $activity->created_at->format('g:i A') }}
                                            </span>
                                            <span>{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Details -->
                                @if($activity->notes)
                                    <div class="mt-2 text-sm text-gray-300">
                                        <span class="font-medium">Notes:</span> {{ $activity->notes }}
                                    </div>
                                @endif

                                @if($activity->old_values || $activity->new_values)
                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if($activity->old_values && $activity->action_type !== 'created')
                                            <div class="bg-gray-900 rounded-lg p-3">
                                                <h5 class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Previous Values</h5>
                                                <div class="space-y-1 text-sm">
                                                    @foreach($activity->old_values as $key => $value)
                                                        @if($value !== null && $value !== '')
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-400">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                                <span class="text-gray-300">
                                                                    @if($key === 'allocation_amount')
                                                                        ${{ number_format($value, 2) }}
                                                                    @elseif($key === 'allocation_percent')
                                                                        {{ number_format($value, 1) }}%
                                                                    @elseif($key === 'initial_capital')
                                                                        ${{ number_format($value, 2) }}
                                                                    @else
                                                                        {{ $value }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if($activity->new_values)
                                            <div class="bg-gray-900 rounded-lg p-3">
                                                <h5 class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">
                                                    {{ $activity->action_type === 'created' ? 'Initial Values' : 'New Values' }}
                                                </h5>
                                                <div class="space-y-1 text-sm">
                                                    @foreach($activity->new_values as $key => $value)
                                                        @if($value !== null && $value !== '')
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-400">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                                <span class="text-gray-300">
                                                                    @if($key === 'allocation_amount')
                                                                        ${{ number_format($value, 2) }}
                                                                    @elseif($key === 'allocation_percent')
                                                                        {{ number_format($value, 1) }}%
                                                                    @elseif($key === 'initial_capital')
                                                                        ${{ number_format($value, 2) }}
                                                                    @else
                                                                        {{ $value }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-white">No activity history</h3>
                            <p class="mt-1 text-sm text-gray-400">This portfolio doesn't have any recorded activities yet.</p>
                        </div>
                    @endforelse

                    @if($history->hasPages())
                        <div class="mt-8">
                            {{ $history->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Summary -->
            @if($history->total() > 0)
                <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-white mb-3">Activity Summary</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        @php
                            $activityCounts = $history->groupBy('action_type')->map->count();
                        @endphp
                        <div>
                            <div class="text-gray-400">Portfolio Changes</div>
                            <div class="text-white font-medium">
                                {{ ($activityCounts['created'] ?? 0) + ($activityCounts['updated'] ?? 0) + ($activityCounts['status_changed'] ?? 0) }}
                            </div>
                        </div>
                        <div>
                            <div class="text-gray-400">Strategies Added</div>
                            <div class="text-white font-medium">
                                {{ ($activityCounts['strategy_added'] ?? 0) + ($activityCounts['strategy_activated'] ?? 0) }}
                            </div>
                        </div>
                        <div>
                            <div class="text-gray-400">Allocation Updates</div>
                            <div class="text-white font-medium">{{ $activityCounts['strategy_updated'] ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400">Strategies Removed</div>
                            <div class="text-white font-medium">
                                {{ ($activityCounts['strategy_removed'] ?? 0) + ($activityCounts['strategy_paused'] ?? 0) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 