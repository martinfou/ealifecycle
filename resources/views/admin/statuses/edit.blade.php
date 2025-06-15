<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <form action="{{ route('admin.statuses.update', $status) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                Status Name *
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $status->name) }}"
                                   required
                                   maxlength="255"
                                   class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500"
                                   placeholder="e.g. Demo, Production, On Hold, Retired">
                            <p class="mt-1 text-sm text-gray-400">Enter a descriptive name for this status</p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      maxlength="500"
                                      class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500"
                                      placeholder="Optional description of what this status represents">{{ old('description', $status->description) }}</textarea>
                            <p class="mt-1 text-sm text-gray-400">Optional description (max 500 characters)</p>
                        </div>

                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-300 mb-2">
                                Status Color
                            </label>
                            <div class="flex items-center space-x-3">
                                <input type="color" 
                                       name="color" 
                                       id="color" 
                                       value="{{ old('color', $status->color ?? '#6B7280') }}"
                                       class="h-10 w-20 rounded border border-gray-600 cursor-pointer">
                                <div class="flex-1">
                                    <input type="text" 
                                           name="color_text" 
                                           id="color_text" 
                                           value="{{ old('color', $status->color ?? '#6B7280') }}"
                                           pattern="^#[0-9A-Fa-f]{6}$"
                                           maxlength="7"
                                           class="block w-full rounded-md bg-gray-700 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500"
                                           placeholder="#6B7280">
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-400">Choose a color to represent this status visually</p>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1"
                                   {{ old('is_active', $status->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-600 rounded bg-gray-700">
                            <label for="is_active" class="ml-2 block text-sm text-gray-300">
                                Active Status
                            </label>
                        </div>
                        <p class="text-sm text-gray-400">Only active statuses will be available when creating or editing strategies</p>

                        <!-- Preview -->
                        <div class="p-4 bg-gray-700 border border-gray-600 rounded-md">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Preview</h4>
                            <div class="flex items-center">
                                <div id="preview-color" class="w-4 h-4 rounded-full mr-3" style="background-color: {{ old('color', $status->color ?? '#6B7280') }}"></div>
                                <span id="preview-name" class="text-sm font-medium text-white">{{ old('name', $status->name) }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between pt-6">
                            <a href="{{ route('admin.statuses.index') }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Status
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