<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Status History') }} - {{ $strategy->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('strategies.show', $strategy) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    View Strategy
                </a>
                <a href="{{ route('strategies.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Back to Strategies
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Strategy Summary -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-white">{{ $strategy->name }}</h3>
                            <p class="text-sm text-gray-400 mt-1">
                                @if($strategy->timeframes->count() > 0)
                                    @foreach($strategy->timeframes->sortBy('sort_order') as $timeframe)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium 
                                            {{ $timeframe->pivot->is_primary ? 'bg-blue-800 text-blue-200' : 'bg-gray-700 text-gray-300' }}">
                                            {{ $timeframe->name }}
                                        </span>
                                    @endforeach
                                @endif
                                @if($strategy->symbols_traded)
                                    • {{ $strategy->symbols_traded }}
                                @endif
                                @if($strategy->magic_number)
                                    • Magic: {{ $strategy->magic_number }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-400">Current Status:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                                  style="background-color: {{ $strategy->status->color ?? '#6B7280' }}20; color: {{ $strategy->status->color ?? '#6B7280' }}">
                                {{ $strategy->status->name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status History -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-6">Status Change History</h3>
                    
                    @if($statusHistory->count() > 0)
                        <div class="space-y-4">
                            @foreach($statusHistory as $change)
                                <div class="border border-gray-700 rounded-lg p-4 bg-gray-900 hover:bg-gray-850 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4">
                                                <!-- Status Change Visual -->
                                                <div class="flex items-center space-x-2">
                                                    @if($change->previousStatus)
                                                        <div class="flex items-center space-x-1">
                                                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $change->previousStatus->color ?? '#6B7280' }}"></div>
                                                            <span class="text-sm text-gray-300">{{ $change->previousStatus->name }}</span>
                                                        </div>
                                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    @else
                                                        <span class="text-sm text-gray-500 italic">Initial Status</span>
                                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    @endif
                                                    
                                                    <div class="flex items-center space-x-1">
                                                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $change->newStatus->color ?? '#6B7280' }}"></div>
                                                        <span class="text-sm font-medium text-white">{{ $change->newStatus->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Change Details -->
                                            <div class="mt-2 text-sm text-gray-400">
                                                <div class="flex items-center space-x-4">
                                                    <span>
                                                        <strong class="text-gray-300">Changed by:</strong> {{ $change->changedByUser->name }}
                                                    </span>
                                                    <span>
                                                        <strong class="text-gray-300">Date:</strong> {{ $change->created_at->format('M j, Y g:i A') }}
                                                    </span>
                                                </div>
                                                
                                                @if($change->notes)
                                                    <div class="mt-2">
                                                        <strong class="text-gray-300">Notes:</strong> {{ $change->notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Time Badge -->
                                        <div class="text-xs text-gray-400 bg-gray-700 px-2 py-1 rounded">
                                            {{ $change->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Summary Stats -->
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-900 border border-blue-700 rounded-lg p-4">
                                <div class="text-sm font-medium text-blue-200">Total Status Changes</div>
                                <div class="text-2xl font-bold text-blue-100">{{ $statusHistory->count() }}</div>
                            </div>
                            
                            <div class="bg-green-900 border border-green-700 rounded-lg p-4">
                                <div class="text-sm font-medium text-green-200">Strategy Created</div>
                                <div class="text-lg font-semibold text-green-100">{{ $strategy->created_at->format('M j, Y') }}</div>
                            </div>
                            
                            <div class="bg-purple-900 border border-purple-700 rounded-lg p-4">
                                <div class="text-sm font-medium text-purple-200">Current Status Since</div>
                                <div class="text-lg font-semibold text-purple-100">{{ $strategy->date_in_status->format('M j, Y') }}</div>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $statusHistory->links('pagination::tailwind') }}
                        </div>
                        
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 text-lg">No status history found</div>
                            <div class="text-gray-500 mt-2">
                                This strategy doesn't have any recorded status changes yet.
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-4">Actions</h3>
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('strategies.show', $strategy) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                            View Strategy Details
                        </a>
                        
                        <a href="{{ route('strategies.edit', $strategy) }}" 
                           class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                            Edit Strategy
                        </a>
                        
                        <a href="{{ route('strategies.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                            Back to All Strategies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 