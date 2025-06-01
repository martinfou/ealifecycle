<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Import Trades') }}
            </h2>
            <a href="{{ route('trades.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Trades
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Import FX Blue Trade History</h3>
                    
                    <!-- Instructions -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Import Instructions:</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Export your trade history from FX Blue in CSV format</li>
                            <li>• The system will automatically match trades to strategies using magic numbers</li>
                            <li>• If no magic number match is found, trades will be assigned to the default strategy (if selected)</li>
                            <li>• Duplicate trades (same ticket number) will be skipped</li>
                            <li>• Maximum file size: 10MB</li>
                        </ul>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
                            <div class="text-sm font-medium text-red-900 mb-2">Please correct the following errors:</div>
                            <ul class="text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Success Messages -->
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-md">
                            <div class="text-sm font-medium text-green-900">{{ session('success') }}</div>
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
                            <div class="text-sm font-medium text-red-900">{{ session('error') }}</div>
                        </div>
                    @endif

                    <!-- Import Form -->
                    <form action="{{ route('trades.process-import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="trade_file" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Trade History File (CSV)
                            </label>
                            <input type="file" 
                                   name="trade_file" 
                                   id="trade_file" 
                                   accept=".csv,.txt"
                                   required
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-sm text-gray-500">CSV or TXT files only, maximum 10MB</p>
                        </div>

                        <div>
                            <label for="default_strategy_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Default Strategy (Optional)
                            </label>
                            <select name="default_strategy_id" 
                                    id="default_strategy_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                            <p class="mt-1 text-sm text-gray-500">
                                Trades without matching magic numbers will be assigned to this strategy. 
                                Leave blank if you want to manually assign unmatched trades later.
                            </p>
                        </div>

                        <!-- Expected CSV Format -->
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Expected CSV Format:</h4>
                            <div class="text-xs text-gray-600 font-mono bg-white p-2 rounded border overflow-x-auto">
                                Ticket,Open Time,Type,Size,Symbol,Open Price,S/L,T/P,Close Time,Close Price,Commission,Swap,Profit,Magic Number,Comment
                            </div>
                            <p class="mt-2 text-sm text-gray-600">
                                The system expects the above column headers. Make sure your CSV file from FX Blue matches this format.
                            </p>
                        </div>

                        <!-- Strategy Assignment Info -->
                        @if($strategies->count() > 0)
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                <h4 class="text-sm font-medium text-yellow-900 mb-2">Current Strategies with Magic Numbers:</h4>
                                <div class="space-y-1">
                                    @foreach($strategies->where('magic_number', '!=', null) as $strategy)
                                        <div class="text-sm text-yellow-700">
                                            <strong>{{ $strategy->name }}</strong> - Magic Number: {{ $strategy->magic_number }}
                                        </div>
                                    @endforeach
                                    @if($strategies->where('magic_number', '!=', null)->count() == 0)
                                        <div class="text-sm text-yellow-700">
                                            No strategies have magic numbers assigned. Consider setting magic numbers for automatic trade assignment.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                <h4 class="text-sm font-medium text-yellow-900 mb-2">No Strategies Found</h4>
                                <p class="text-sm text-yellow-700">
                                    You should create some strategies first to properly organize your trades.
                                    <a href="{{ route('strategies.create') }}" class="text-blue-600 hover:text-blue-800 underline">
                                        Create a strategy now
                                    </a>
                                </p>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <a href="{{ route('trades.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Import Trades
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 