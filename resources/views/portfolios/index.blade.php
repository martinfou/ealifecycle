<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('My Portfolios') }}
            </h2>
            <a href="{{ route('portfolios.create') }}" class="w-full sm:w-auto text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Create New Portfolio
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
                    @forelse($portfolios as $portfolio)
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-4 border border-gray-700 rounded-lg mb-4 last:mb-0 bg-gray-900 hover:bg-gray-750 hover:border-gray-600 transition-all">
                            <div class="flex-1 mb-4 lg:mb-0">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-white">
                                            <a href="{{ route('portfolios.show', $portfolio) }}" class="hover:text-gray-300 transition-colors">
                                                {{ $portfolio->name }}
                                            </a>
                                        </h3>
                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-gray-400">
                                            <span class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                                </svg>
                                                <span>${{ number_format($portfolio->initial_capital, 2) }}</span>
                                            </span>
                                            <span>•</span>
                                            <span class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>{{ $portfolio->active_strategies_count }} active</span>
                                            </span>
                                            <span>•</span>
                                            <span>{{ $portfolio->total_strategies_count }} total strategies</span>
                                            <span>•</span>
                                            <span>Created {{ $portfolio->created_at->format('M j, Y') }}</span>
                                        </div>
                                        @if($portfolio->description)
                                            <p class="mt-1 text-sm text-gray-300">{{ Str::limit($portfolio->description, 100) }}</p>
                                        @endif
                                        
                                        @if($portfolio->total_allocated_amount > 0)
                                            <div class="mt-2 text-xs text-gray-400">
                                                Allocated: ${{ number_format($portfolio->total_allocated_amount, 2) }} 
                                                ({{ number_format($portfolio->total_allocated_percent, 1) }}%)
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center mt-3 lg:mt-0">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            {{ $portfolio->status === 'active' ? 'bg-green-800 text-green-200' : 
                                               ($portfolio->status === 'paused' ? 'bg-yellow-800 text-yellow-200' : 'bg-gray-700 text-gray-300') }}">
                                            {{ ucfirst($portfolio->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mobile Actions (Stacked) -->
                            <div class="lg:hidden border-t border-gray-700 pt-3 mt-3">
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('portfolios.show', $portfolio) }}" 
                                       class="text-center bg-gray-700 hover:bg-gray-600 text-white font-medium py-2 px-3 rounded transition-colors">
                                        View
                                    </a>
                                    <a href="{{ route('portfolios.edit', $portfolio) }}" 
                                       class="text-center bg-gray-700 hover:bg-gray-600 text-white font-medium py-2 px-3 rounded transition-colors">
                                        Edit
                                    </a>
                                    <a href="{{ route('portfolios.add-strategies', $portfolio) }}" 
                                       class="text-center bg-blue-700 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded transition-colors">
                                        Add Strategies
                                    </a>
                                    <form method="POST" action="{{ route('portfolios.destroy', $portfolio) }}" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this portfolio? This will remove all strategy associations.')">
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
                                <a href="{{ route('portfolios.show', $portfolio) }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">
                                    View
                                </a>
                                <a href="{{ route('portfolios.edit', $portfolio) }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">
                                    Edit
                                </a>
                                <a href="{{ route('portfolios.add-strategies', $portfolio) }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium transition-colors">
                                    Add Strategies
                                </a>
                                <form method="POST" action="{{ route('portfolios.destroy', $portfolio) }}" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this portfolio? This will remove all strategy associations.')">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-white">No portfolios</h3>
                            <p class="mt-1 text-sm text-gray-400">Get started by creating your first trading portfolio to manage multiple strategies.</p>
                            <div class="mt-6">
                                <a href="{{ route('portfolios.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 transition-colors">
                                    Create Portfolio
                                </a>
                            </div>
                        </div>
                    @endforelse

                    @if($portfolios->hasPages())
                        <div class="mt-6">
                            {{ $portfolios->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 