<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('My Strategies') }}
            </h2>
            <a href="{{ route('strategies.create') }}" class="w-full sm:w-auto text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Add New Strategy
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    @forelse($strategies as $strategy)
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-4 border border-gray-700 rounded-lg mb-4 last:mb-0 bg-gray-900 hover:bg-gray-750 hover:border-gray-600 transition-all">
                            <div class="flex-1 mb-4 lg:mb-0">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-white">
                                            <a href="{{ route('strategies.show', $strategy) }}" class="hover:text-gray-300 transition-colors">
                                                {{ $strategy->name }}
                                            </a>
                                        </h3>
                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-gray-400">
                                            <span class="flex items-center space-x-1">
                                                @if($strategy->timeframes->count() > 0)
                                                    @foreach($strategy->timeframes->sortBy('sort_order') as $timeframe)
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium 
                                                            {{ $timeframe->pivot->is_primary ? 'bg-blue-800 text-blue-200' : 'bg-gray-700 text-gray-300' }}">
                                                            {{ $timeframe->name }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-gray-500">No timeframes</span>
                                                @endif
                                            </span>
                                            @if($strategy->symbols_traded)
                                                <span>•</span>
                                                <span>{{ $strategy->symbols_traded }}</span>
                                            @endif
                                            @if($strategy->magic_number)
                                                <span>•</span>
                                                <span>Magic: {{ $strategy->magic_number }}</span>
                                            @endif
                                            @if($strategy->group)
                                                <span>•</span>
                                                <span class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                                    </svg>
                                                    {{ $strategy->group->name }}
                                                    @if($strategy->user_id !== Auth::id())
                                                        @php
                                                            $permission = Auth::user()->getPermissionInGroup($strategy->group_id);
                                                        @endphp
                                                        <span class="ml-1 text-xs px-1.5 py-0.5 rounded {{ $permission === 'write' ? 'bg-green-800 text-green-200' : 'bg-gray-700 text-gray-300' }}">
                                                            {{ $permission }}
                                                        </span>
                                                    @endif
                                                </span>
                                            @else
                                                <span>•</span>
                                                <span class="text-gray-500">Private</span>
                                            @endif
                                            <span>•</span>
                                            <span>In {{ $strategy->status->name }} since {{ $strategy->date_in_status->format('M j, Y') }}</span>
                                        </div>
                                        @if($strategy->description)
                                            <p class="mt-1 text-sm text-gray-300">{{ Str::limit($strategy->description, 100) }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center mt-3 lg:mt-0">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                                              style="background-color: {{ $strategy->status->color }}20; color: {{ $strategy->status->color }}">
                                            {{ $strategy->status->name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mobile Actions (Stacked) -->
                            <div class="lg:hidden border-t border-gray-700 pt-3 mt-3">
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('strategies.show', $strategy) }}" 
                                       class="text-center bg-gray-700 hover:bg-gray-600 text-white font-medium py-2 px-3 rounded transition-colors">
                                        View
                                    </a>
                                    <a href="{{ route('strategies.edit', $strategy) }}" 
                                       class="text-center bg-gray-700 hover:bg-gray-600 text-white font-medium py-2 px-3 rounded transition-colors">
                                        Edit
                                    </a>
                                    <a href="{{ route('strategies.history', $strategy) }}" 
                                       class="text-center bg-gray-700 hover:bg-gray-600 text-white font-medium py-2 px-3 rounded transition-colors">
                                        History
                                    </a>
                                    <form method="POST" action="{{ route('strategies.destroy', $strategy) }}" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this strategy?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-3 rounded transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Desktop Actions (Inline) -->
                            <div class="hidden lg:flex items-center space-x-2 ml-4">
                                <a href="{{ route('strategies.show', $strategy) }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">
                                    View
                                </a>
                                <a href="{{ route('strategies.edit', $strategy) }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">
                                    Edit
                                </a>
                                <a href="{{ route('strategies.history', $strategy) }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">
                                    History
                                </a>
                                <form method="POST" action="{{ route('strategies.destroy', $strategy) }}" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this strategy?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-white">No strategies</h3>
                            <p class="mt-1 text-sm text-gray-400">Get started by creating your first trading strategy.</p>
                            <div class="mt-6">
                                <a href="{{ route('strategies.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 transition-colors">
                                    Add Strategy
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 