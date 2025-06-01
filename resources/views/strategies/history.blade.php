<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Status History') }} - {{ $strategy->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('strategies.show', $strategy) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    View Strategy
                </a>
                <a href="{{ route('strategies.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Strategies
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Strategy Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $strategy->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $strategy->timeframe->name }} • 
                                @if($strategy->symbols_traded)
                                    {{ $strategy->symbols_traded }} • 
                                @endif
                                @if($strategy->magic_number)
                                    Magic: {{ $strategy->magic_number }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Current Status:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                                  style="background-color: {{ $strategy->status->color ?? '#6B7280' }}20; color: {{ $strategy->status->color ?? '#6B7280' }}">
                                {{ $strategy->status->name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Status Change History</h3>
                    
                    @if($history->count() > 0)
                        <div class="space-y-4">
                            @foreach($history as $change)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4">
                                                <!-- Status Change Visual -->
                                                <div class="flex items-center space-x-2">
                                                    @if($change->previousStatus)
                                                        <div class="flex items-center space-x-1">
                                                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $change->previousStatus->color ?? '#6B7280' }}"></div>
                                                            <span class="text-sm text-gray-600">{{ $change->previousStatus->name }}</span>
                                                        </div>
                                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    @else
                                                        <span class="text-sm text-gray-500 italic">Initial Status</span>
                                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    @endif
                                                    
                                                    <div class="flex items-center space-x-1">
                                                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $change->newStatus->color ?? '#6B7280' }}"></div>
                                                        <span class="text-sm font-medium text-gray-900">{{ $change->newStatus->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Change Details -->
                                            <div class="mt-2 text-sm text-gray-600">
                                                <div class="flex items-center space-x-4">
                                                    <span>
                                                        <strong>Changed by:</strong> {{ $change->changedByUser->name }}
                                                    </span>
                                                    <span>
                                                        <strong>Date:</strong> {{ $change->created_at->format('M j, Y g:i A') }}
                                                    </span>
                                                </div>
                                                
                                                @if($change->notes)
                                                    <div class="mt-2">
                                                        <strong>Notes:</strong> {{ $change->notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Time Badge -->
                                        <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                            {{ $change->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Summary Stats -->
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="text-sm font-medium text-blue-900">Total Status Changes</div>
                                <div class="text-2xl font-bold text-blue-600">{{ $history->count() }}</div>
                            </div>
                            
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="text-sm font-medium text-green-900">Strategy Created</div>
                                <div class="text-lg font-semibold text-green-600">{{ $strategy->created_at->format('M j, Y') }}</div>
                            </div>
                            
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <div class="text-sm font-medium text-purple-900">Current Status Since</div>
                                <div class="text-lg font-semibold text-purple-600">{{ $strategy->date_in_status->format('M j, Y') }}</div>
                            </div>
                        </div>
                        
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg">No status history found</div>
                            <div class="text-gray-400 mt-2">
                                This strategy doesn't have any recorded status changes yet.
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('strategies.show', $strategy) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            View Strategy Details
                        </a>
                        
                        <a href="{{ route('strategies.edit', $strategy) }}" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Edit Strategy
                        </a>
                        
                        <a href="{{ route('strategies.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to All Strategies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 