<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Import Trades') }}
            </h2>
            <a href="{{ route('trades.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Back to Trades
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-6">Import FX Blue Trade History</h3>
                    
                    <!-- Instructions -->
                    <div class="mb-6 p-4 bg-gray-900 border border-gray-600 rounded-md">
                        <h4 class="text-sm font-medium text-blue-400 mb-2">Import Instructions:</h4>
                        <ul class="text-sm text-gray-300 space-y-1">
                            <li>• Export your trade history from FX Blue in CSV format</li>
                            <li>• The system will automatically match trades to strategies using magic numbers</li>
                            <li>• If no magic number match is found, trades will be assigned to the default strategy (if selected)</li>
                            <li>• Duplicate trades (same ticket number) will be skipped</li>
                            <li>• Maximum file size: 10MB</li>
                        </ul>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-900 border border-red-600 rounded-md">
                            <div class="text-sm font-medium text-red-200 mb-2">Please correct the following errors:</div>
                            <ul class="text-sm text-red-300 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

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

                    <!-- Import Form -->
                    <form action="{{ route('trades.process-import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="trade_file" class="block text-sm font-medium text-gray-300 mb-2">
                                Select Trade History File (CSV)
                            </label>
                            <input type="file" 
                                   name="trade_file" 
                                   id="trade_file" 
                                   accept=".csv,.txt"
                                   required
                                   class="block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600 bg-gray-900 border border-gray-600 rounded-md">
                            <p class="mt-1 text-sm text-gray-400">CSV or TXT files only, maximum 10MB</p>
                        </div>

                        <div>
                            <label for="default_strategy_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Default Strategy (Optional)
                            </label>
                            <select name="default_strategy_id" 
                                    id="default_strategy_id" 
                                    class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500">
                                <option value="">No default strategy</option>
                                @foreach($strategies as $strategy)
                                    <option value="{{ $strategy->id }}" {{ old('default_strategy_id') == $strategy->id ? 'selected' : '' }}>
                                        {{ $strategy->name }}
                                        @if($strategy->magic_number)
                                            (Magic: {{ $strategy->magic_number }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-400">
                                Trades without matching magic numbers will be assigned to this strategy. 
                                Leave blank if you want to manually assign unmatched trades later.
                            </p>
                        </div>

                        <!-- Expected CSV Format -->
                        <div class="p-4 bg-gray-900 border border-gray-600 rounded-md">
                            <h4 class="text-sm font-medium text-white mb-2">Expected CSV Format:</h4>
                            <div class="text-xs text-gray-300 font-mono bg-gray-800 p-2 rounded border border-gray-600 overflow-x-auto">
                                Ticket,Open Time,Type,Size,Symbol,Open Price,S/L,T/P,Close Time,Close Price,Commission,Swap,Profit,Magic Number,Comment
                            </div>
                            <p class="mt-2 text-sm text-gray-400">
                                The system expects the above column headers. Make sure your CSV file from FX Blue matches this format.
                            </p>
                        </div>

                        <!-- Strategy Assignment Info -->
                        @if($strategies->count() > 0)
                            <div class="p-4 bg-yellow-900 border border-yellow-600 rounded-md">
                                <h4 class="text-sm font-medium text-yellow-200 mb-2">Current Strategies with Magic Numbers:</h4>
                                <div class="space-y-1">
                                    @foreach($strategies->where('magic_number', '!=', null) as $strategy)
                                        <div class="text-sm text-yellow-300">
                                            <strong>{{ $strategy->name }}</strong> - Magic Number: {{ $strategy->magic_number }}
                                        </div>
                                    @endforeach
                                    @if($strategies->where('magic_number', '!=', null)->count() == 0)
                                        <div class="text-sm text-yellow-300">
                                            No strategies have magic numbers assigned. Consider setting magic numbers for automatic trade assignment.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-yellow-900 border border-yellow-600 rounded-md">
                                <h4 class="text-sm font-medium text-yellow-200 mb-2">No Strategies Found</h4>
                                <p class="text-sm text-yellow-300">
                                    You should create some strategies first to properly organize your trades.
                                    <a href="{{ route('strategies.create') }}" class="text-blue-400 hover:text-blue-300 underline transition-colors">
                                        Create a strategy now
                                    </a>
                                </p>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <a href="{{ route('trades.index') }}" 
                               class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Import Trades
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 