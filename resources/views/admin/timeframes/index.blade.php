<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Manage Timeframes') }}
            </h2>
            <a href="{{ route('admin.timeframes.create') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Add New Timeframe
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900 border border-green-600 rounded-md">
                    <div class="text-sm font-medium text-green-200">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Error Messages -->
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-900 border border-red-600 rounded-md">
                    <div class="text-sm font-medium text-red-200">{{ session('error') }}</div>
                </div>
            @endif

            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-6">Trading Timeframes</h3>
                    
                    @if($timeframes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Sort Order</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Active</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Strategies</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach($timeframes as $timeframe)
                                        <tr class="hover:bg-gray-750 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-white">{{ $timeframe->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-300">
                                                {{ $timeframe->description ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $timeframe->sort_order }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($timeframe->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300 border border-green-600">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300 border border-red-600">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $timeframe->strategies()->count() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <a href="{{ route('admin.timeframes.show', $timeframe) }}" class="text-gray-300 hover:text-white transition-colors">
                                                    View
                                                </a>
                                                <a href="{{ route('admin.timeframes.edit', $timeframe) }}" class="text-gray-300 hover:text-white transition-colors">
                                                    Edit
                                                </a>
                                                @if($timeframe->strategies()->count() == 0)
                                                    <form action="{{ route('admin.timeframes.destroy', $timeframe) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-400 hover:text-red-300 transition-colors"
                                                                onclick="return confirm('Are you sure you want to delete this timeframe?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-500" title="Cannot delete - timeframe is in use">Delete</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-300 text-lg">No timeframes found</div>
                            <div class="text-gray-400 mt-2">
                                <a href="{{ route('admin.timeframes.create') }}" class="text-gray-300 hover:text-white transition-colors">
                                    Create your first timeframe
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-gray-900 border border-gray-600 rounded-md p-4">
                <h4 class="text-sm font-medium text-blue-400 mb-2">About Trading Timeframes</h4>
                <p class="text-sm text-gray-300">
                    Timeframes define the period intervals for your trading strategies. 
                    Common timeframes include M1 (1 minute), M5 (5 minutes), M15 (15 minutes), M30 (30 minutes), 
                    H1 (1 hour), H4 (4 hours), D1 (daily), W1 (weekly), and MN1 (monthly). 
                    Set custom sort orders to organize them logically.
                </p>
            </div>
        </div>
    </div>
</x-app-layout> 