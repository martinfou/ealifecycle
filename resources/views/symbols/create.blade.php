<!-- resources/views/symbols/create.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Add New Symbol') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 shadow-lg sm:rounded-lg p-6 md:p-8">
            
                @if ($errors->any())
                    <div class="mb-6 bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded-lg relative" role="alert">
                        <strong class="font-bold">Whoops!</strong>
                        <span class="block sm:inline">There were some problems with your input.</span>
                        <ul class="mt-3 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('symbols.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-300 mb-1">Code</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}"
                               class="bg-gray-900 border border-gray-700 text-white sm:text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                               placeholder="e.g., 1">
                    </div>
                    <div>
                        <label for="symbol" class="block text-sm font-medium text-gray-300 mb-1">Symbol</label>
                        <input type="text" name="symbol" id="symbol" value="{{ old('symbol') }}"
                               class="bg-gray-900 border border-gray-700 text-white sm:text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5"
                               placeholder="e.g., AUDCAD">
                    </div>
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('symbols.index') }}" class="text-gray-400 hover:text-white transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                            Save Symbol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 