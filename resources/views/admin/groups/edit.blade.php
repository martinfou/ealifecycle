<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Group: ') . $group->name }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.groups.show', $group) }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    View Group
                </a>
                <a href="{{ route('admin.groups.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Back to Groups
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.groups.update', $group) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Group Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-300">Group Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $group->name) }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      placeholder="Optional description of this group's purpose..."
                                      class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('description') border-red-500 @enderror">{{ old('description', $group->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.groups.show', $group) }}" 
                               class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Update Group
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Group Statistics -->
            <div class="mt-6 bg-gray-900 border border-gray-600 rounded-md p-4">
                <h4 class="text-sm font-medium text-blue-400 mb-2">Group Statistics</h4>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-300">
                    <div>
                        <span class="font-medium">Members:</span> {{ $group->users()->count() }}
                    </div>
                    <div>
                        <span class="font-medium">Strategies:</span> {{ $group->strategies()->count() }}
                    </div>
                    <div>
                        <span class="font-medium">Created:</span> {{ $group->created_at->format('M j, Y') }}
                    </div>
                    <div>
                        <span class="font-medium">Last Updated:</span> {{ $group->updated_at->format('M j, Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 