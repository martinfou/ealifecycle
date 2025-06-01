<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Strategies') }}
            </h2>
            <a href="{{ route('strategies.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Strategy
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @forelse($strategies as $strategy)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg mb-4 last:mb-0 hover:bg-gray-50 transition-colors">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            <a href="{{ route('strategies.show', $strategy) }}" class="hover:text-blue-600">
                                                {{ $strategy->name }}
                                            </a>
                                        </h3>
                                        <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                            <span>{{ $strategy->timeframe->name }}</span>
                                            @if($strategy->symbols_traded)
                                                <span>•</span>
                                                <span>{{ $strategy->symbols_traded }}</span>
                                            @endif
                                            @if($strategy->magic_number)
                                                <span>•</span>
                                                <span>Magic: {{ $strategy->magic_number }}</span>
                                            @endif
                                            <span>•</span>
                                            <span>In {{ $strategy->status->name }} since {{ $strategy->date_in_status->format('M j, Y') }}</span>
                                        </div>
                                        @if($strategy->description)
                                            <p class="mt-1 text-sm text-gray-600">{{ Str::limit($strategy->description, 100) }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                                              style="background-color: {{ $strategy->status->color }}20; color: {{ $strategy->status->color }}">
                                            {{ $strategy->status->name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <a href="{{ route('strategies.show', $strategy) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View
                                </a>
                                <a href="{{ route('strategies.edit', $strategy) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    Edit
                                </a>
                                <a href="{{ route('strategies.history', $strategy) }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                    History
                                </a>
                                <form method="POST" action="{{ route('strategies.destroy', $strategy) }}" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this strategy?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No strategies</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first trading strategy.</p>
                            <div class="mt-6">
                                <a href="{{ route('strategies.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
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