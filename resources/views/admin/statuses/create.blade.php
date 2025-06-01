<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Status') }}
            </h2>
            <a href="{{ route('admin.statuses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Statuses
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Status Information</h3>

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

                    <form action="{{ route('admin.statuses.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Name *
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   required
                                   maxlength="255"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g. Demo, Production, On Hold, Retired">
                            <p class="mt-1 text-sm text-gray-500">Enter a descriptive name for this status</p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      maxlength="500"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Optional description of what this status represents">{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Optional description (max 500 characters)</p>
                        </div>

                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Color
                            </label>
                            <div class="flex items-center space-x-3">
                                <input type="color" 
                                       name="color" 
                                       id="color" 
                                       value="{{ old('color', '#6B7280') }}"
                                       class="h-10 w-20 rounded border border-gray-300 cursor-pointer">
                                <div class="flex-1">
                                    <input type="text" 
                                           name="color_text" 
                                           id="color_text" 
                                           value="{{ old('color', '#6B7280') }}"
                                           pattern="^#[0-9A-Fa-f]{6}$"
                                           maxlength="7"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="#6B7280">
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Choose a color to represent this status visually</p>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Active Status
                            </label>
                        </div>
                        <p class="text-sm text-gray-500">Only active statuses will be available when creating or editing strategies</p>

                        <!-- Preview -->
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Preview</h4>
                            <div class="flex items-center">
                                <div id="preview-color" class="w-4 h-4 rounded-full mr-3" style="background-color: {{ old('color', '#6B7280') }}"></div>
                                <span id="preview-name" class="text-sm font-medium text-gray-900">{{ old('name', 'Status Name') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between pt-6">
                            <a href="{{ route('admin.statuses.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sync color picker with text input and preview
        const colorPicker = document.getElementById('color');
        const colorText = document.getElementById('color_text');
        const previewColor = document.getElementById('preview-color');
        
        colorPicker.addEventListener('input', function() {
            colorText.value = this.value;
            previewColor.style.backgroundColor = this.value;
        });
        
        colorText.addEventListener('input', function() {
            if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                colorPicker.value = this.value;
                previewColor.style.backgroundColor = this.value;
            }
        });
        
        // Update preview name
        const nameInput = document.getElementById('name');
        const previewName = document.getElementById('preview-name');
        
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'Status Name';
        });
    </script>
</x-app-layout> 