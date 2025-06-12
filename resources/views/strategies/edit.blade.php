<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Strategy') }}
            </h2>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <a href="{{ route('strategies.show', $strategy) }}" class="w-full sm:w-auto text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                    View Details
                </a>
                <a href="{{ route('strategies.index') }}" class="w-full sm:w-auto text-center bg-gray-700 hover:bg-gray-800 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                    Back to Strategies
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-6">Edit Strategy: {{ $strategy->name }}</h3>
                    
                    <form method="POST" action="{{ route('strategies.update', $strategy) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Strategy Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-300">Strategy Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $strategy->name) }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Symbols Traded -->
                        <div class="mb-4">
                            <label for="symbols_traded" class="block text-sm font-medium text-gray-300">Symbols Traded</label>
                            <input type="text" name="symbols_traded" id="symbols_traded" value="{{ old('symbols_traded', $strategy->symbols_traded) }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('symbols_traded') border-red-500 @enderror"
                                   placeholder="e.g., EURUSD, GBPUSD, AAPL, TSLA, ES, CL">
                            @error('symbols_traded')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Enter symbols separated by commas. Examples: <strong>Forex:</strong> EURUSD, GBPJPY, XAUUSD • <strong>Stocks:</strong> AAPL, TSLA, SPY • <strong>Futures:</strong> ES, NQ, CL, GC</p>
                        </div>

                        <!-- Timeframes -->
                        <div class="mb-4">
                            <label for="timeframe_ids" class="block text-sm font-medium text-gray-300">Timeframes *</label>
                            <div class="mt-1 space-y-2">
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                    @foreach($timeframes as $timeframe)
                                        @php
                                            $isSelected = $strategy->timeframes->contains('id', $timeframe->id) || collect(old('timeframe_ids'))->contains($timeframe->id);
                                        @endphp
                                        <label class="flex items-center space-x-2 p-2 border border-gray-600 rounded-md hover:bg-gray-700 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="timeframe_ids[]" 
                                                   value="{{ $timeframe->id }}"
                                                   {{ $isSelected ? 'checked' : '' }}
                                                   class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-600 rounded bg-gray-900"
                                                   onchange="updatePrimaryTimeframeOptions()">
                                            <span class="text-sm text-white font-medium">{{ $timeframe->name }}</span>
                                            @if($timeframe->description)
                                                <span class="text-xs text-gray-400">- {{ $timeframe->description }}</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @error('timeframe_ids')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Select all timeframes this strategy will use</p>
                        </div>

                        <!-- Primary Timeframe -->
                        <div class="mb-4">
                            <label for="primary_timeframe_id" class="block text-sm font-medium text-gray-300">Primary Timeframe *</label>
                            <select name="primary_timeframe_id" id="primary_timeframe_id" required
                                    class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('primary_timeframe_id') border-red-500 @enderror">
                                <option value="">Select primary timeframe...</option>
                                @foreach($timeframes as $timeframe)
                                    @php
                                        $isPrimary = $strategy->timeframes->where('pivot.is_primary', true)->first()?->id == $timeframe->id;
                                        $isSelected = old('primary_timeframe_id') == $timeframe->id || ($isPrimary && !old('primary_timeframe_id'));
                                    @endphp
                                    <option value="{{ $timeframe->id }}" 
                                            {{ $isSelected ? 'selected' : '' }}
                                            data-timeframe-id="{{ $timeframe->id }}">
                                        {{ $timeframe->name }}
                                        @if($timeframe->description)
                                            - {{ $timeframe->description }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('primary_timeframe_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Choose the main timeframe for strategy decisions</p>
                        </div>

                        <!-- Group -->
                        <div class="mb-4">
                            <label for="group_id" class="block text-sm font-medium text-gray-300">Group</label>
                            <select name="group_id" id="group_id" 
                                    class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('group_id') border-red-500 @enderror">
                                <option value="">No group (private to you)</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ old('group_id', $strategy->group_id) == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }} - {{ $group->description }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-400">Select a group to share this strategy with other members</p>
                            @error('group_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Magic Number -->
                        <div class="mb-4">
                            <label for="magic_number" class="block text-sm font-medium text-gray-300">Magic Number</label>
                            <input type="number" name="magic_number" id="magic_number" value="{{ old('magic_number', $strategy->magic_number) }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('magic_number') border-red-500 @enderror"
                                   placeholder="e.g., 12345">
                            @error('magic_number')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Optional unique identifier used in your trading platform</p>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('description') border-red-500 @enderror"
                                      placeholder="Describe your trading strategy, entry/exit rules, etc.">{{ old('description', $strategy->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Source Code File -->
                        <div class="mb-6" id="source-file">
                            <label for="source_code_file" class="block text-sm font-medium text-gray-300">Source Code (Optional)</label>
                            @if ($strategy->source_code_path)
                                <div class="mt-2 text-sm text-gray-300 flex items-center space-x-4">
                                    <span>Current file:</span>
                                    <a href="{{ route('strategies.downloadSourceCode', $strategy) }}" class="text-blue-400 hover:text-blue-300 font-medium" download>
                                        {{ $strategy->source_code_original_filename ?? basename($strategy->source_code_path) }}
                                    </a>
                                    <a href="javascript:void(0)" class="text-green-400 hover:text-green-300 font-medium view-source-link"
                                       data-source-url="{{ route('strategies.downloadSourceCode', $strategy) }}"
                                       data-filename="{{ $strategy->source_code_original_filename ?? basename($strategy->source_code_path) }}">
                                        View
                                    </a>
                                    <button type="button" class="text-red-400 hover:text-red-600 font-medium ml-2 delete-source-btn" data-url="{{ route('strategies.update', $strategy) }}" data-token="{{ csrf_token() }}">Delete</button>
                                </div>
                                <p class="text-xs text-gray-500">Uploading a new file will replace the current one.</p>
                            @endif

                            <input type="file" name="source_code_file" id="source_code_file"
                                   class="mt-2 block w-full text-sm text-gray-400
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-gray-700 file:text-white
                                          hover:file:bg-gray-600
                                          @error('source_code_file') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-400">Upload a new MT4/MT5 source file (.mq4, .mq5, .ex4, .ex5).</p>
                            @error('source_code_file')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Backtest Report PDF (Optional) -->
                        <div class="mb-6" id="report-file">
                            <label for="report_pdf" class="block text-sm font-medium text-gray-300">Backtest Report (PDF, Optional)</label>
                            @php $report = $strategy->report; @endphp
                            @if ($report)
                                <div class="mt-2 text-sm text-gray-300 flex items-center space-x-4">
                                    <span>Current file:</span>
                                    <a href="{{ route('strategies.downloadReport', [$strategy, $report]) }}" class="text-blue-400 hover:text-blue-300 font-medium" download>
                                        {{ $report->original_filename }}
                                    </a>
                                    <a href="javascript:void(0)" class="text-green-400 hover:text-green-300 font-medium view-pdf-link"
                                       data-pdf-url="{{ route('strategies.viewReport', [$strategy, $report]) }}"
                                       data-download-url="{{ route('strategies.downloadReport', [$strategy, $report]) }}"
                                       data-filename="{{ $report->original_filename }}">
                                        View
                                    </a>
                                    <button type="button" class="text-red-400 hover:text-red-600 font-medium ml-2 delete-report-btn" data-url="{{ route('strategies.update', $strategy) }}" data-token="{{ csrf_token() }}">Delete</button>
                                </div>
                                <p class="text-xs text-gray-500">Uploading a new file will replace the current one.</p>
                            @endif
                            <input type="file" name="report_pdf" id="report_pdf" accept="application/pdf"
                                   class="mt-1 block w-full text-sm text-gray-400
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-gray-700 file:text-white
                                          hover:file:bg-gray-600
                                          @error('report_pdf') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-400">Upload a PDF report about the backtesting of this strategy. Uploading a new PDF will replace the current one.</p>
                            @error('report_pdf')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Status Info -->
                        <div class="mb-6 p-4 bg-gray-900 border border-gray-600 rounded-md">
                            <h4 class="text-sm font-medium text-blue-400 mb-2">Current Status</h4>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $strategy->status->color ?? '#6B7280' }}"></div>
                                <span class="text-sm text-gray-300">{{ $strategy->status->name }}</span>
                                <span class="text-sm text-gray-400">(since {{ $strategy->date_in_status->format('M j, Y') }})</span>
                            </div>
                            <p class="text-sm text-gray-300 mt-2">
                                To change the status, use the status change form on the strategy details page.
                            </p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('strategies.show', $strategy) }}" 
                               class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Update Strategy
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-gray-900 border border-gray-600 rounded-md p-4">
                <h4 class="text-sm font-medium text-blue-400 mb-2">Note</h4>
                <p class="text-sm text-gray-300">
                    Editing a strategy will not affect its current status or status history. 
                    To change the strategy status, use the status change form on the strategy details page.
                </p>
            </div>
        </div>
    </div>

    <!-- Modal for PDF viewing -->
    <div id="pdfModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 hidden">
        <div id="pdfModalContent" class="bg-gray-900 rounded-lg shadow-lg w-full relative resize overflow-auto" style="min-width:350px; min-height:300px; width:700px; height:550px; max-width:98vw;">
            <button id="closePdfModal" class="absolute top-2 right-2 text-gray-400 hover:text-white text-2xl font-bold focus:outline-none">&times;</button>
            <div class="p-4 pb-0 flex justify-between items-center">
                <span id="pdfModalFilename" class="text-white font-medium"></span>
                <a id="pdfModalDownload" href="#" download class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">Download</a>
            </div>
            <div class="p-4 pt-2" style="height:calc(100% - 80px);">
                <iframe id="pdfModalIframe" src="" width="100%" height="100%" class="rounded border border-gray-700 bg-white" style="min-height:200px;"></iframe>
            </div>
            <div id="resizeHandle" class="absolute bottom-0 right-0 w-6 h-6 z-20 flex items-end justify-end" style="cursor: se-resize;">
                <svg width="24" height="24" class="pointer-events-none select-none" style="display:block;" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="0,24 24,24 24,0" fill="#374151" />
                    <polygon points="6,24 24,24 24,6" fill="#4B5563" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Modal for Source Code viewing -->
    <div id="sourceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 hidden">
        <div id="sourceModalContent" class="bg-gray-900 rounded-lg shadow-lg w-full relative resize overflow-auto" style="min-width:350px; min-height:300px; width:700px; height:550px; max-width:98vw;">
            <button id="closeSourceModal" class="absolute top-2 right-2 text-gray-400 hover:text-white text-2xl font-bold focus:outline-none">&times;</button>
            <div class="p-4 pb-0 flex justify-between items-center">
                <span id="sourceModalFilename" class="text-white font-medium"></span>
                <a id="sourceModalDownload" href="#" download class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">Download</a>
            </div>
            <div class="p-4 pt-2" style="height:calc(100% - 80px); overflow:auto;">
                <pre id="sourceModalPre" class="rounded border border-gray-700 bg-gray-950 text-xs text-white p-3 overflow-auto" style="min-height:200px; max-height:400px;"><code id="sourceModalCode"></code></pre>
            </div>
            <div id="resizeSourceHandle" class="absolute bottom-0 right-0 w-6 h-6 z-20 flex items-end justify-end" style="cursor: se-resize;">
                <svg width="24" height="24" class="pointer-events-none select-none" style="display:block;" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="0,24 24,24 24,0" fill="#374151" />
                    <polygon points="6,24 24,24 24,6" fill="#4B5563" />
                </svg>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/github-dark.min.css">
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // PDF Modal logic
        const pdfLinks = document.querySelectorAll('.view-pdf-link');
        const pdfModal = document.getElementById('pdfModal');
        const pdfModalContent = document.getElementById('pdfModalContent');
        const pdfModalIframe = document.getElementById('pdfModalIframe');
        const pdfModalFilename = document.getElementById('pdfModalFilename');
        const pdfModalDownload = document.getElementById('pdfModalDownload');
        const closePdfModal = document.getElementById('closePdfModal');
        const resizeHandle = document.getElementById('resizeHandle');

        pdfLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const pdfUrl = this.getAttribute('data-pdf-url');
                const downloadUrl = this.getAttribute('data-download-url');
                const filename = this.getAttribute('data-filename');
                pdfModalIframe.src = pdfUrl;
                pdfModalFilename.textContent = filename;
                pdfModalDownload.href = downloadUrl;
                pdfModalDownload.setAttribute('download', filename);
                pdfModal.classList.remove('hidden');
            });
        });

        closePdfModal.addEventListener('click', function () {
            pdfModal.classList.add('hidden');
            pdfModalIframe.src = '';
        });

        // Allow closing modal by clicking outside content
        pdfModal.addEventListener('click', function (e) {
            if (e.target === pdfModal) {
                pdfModal.classList.add('hidden');
                pdfModalIframe.src = '';
            }
        });

        // Resizing logic
        let isResizing = false;
        let lastDownX = 0;
        let lastDownY = 0;
        let startWidth = 0;
        let startHeight = 0;

        resizeHandle.addEventListener('mousedown', function(e) {
            e.preventDefault();
            isResizing = true;
            lastDownX = e.clientX;
            lastDownY = e.clientY;
            startWidth = pdfModalContent.offsetWidth;
            startHeight = pdfModalContent.offsetHeight;
            document.body.style.userSelect = 'none';
        });

        document.addEventListener('mousemove', function(e) {
            if (!isResizing) return;
            const dx = e.clientX - lastDownX;
            const dy = e.clientY - lastDownY;
            pdfModalContent.style.width = (startWidth + dx) + 'px';
            pdfModalContent.style.height = (startHeight + dy) + 'px';
        });

        document.addEventListener('mouseup', function() {
            if (isResizing) {
                isResizing = false;
                document.body.style.userSelect = '';
            }
        });

        // Source Code Modal logic
        const sourceLinks = document.querySelectorAll('.view-source-link');
        const sourceModal = document.getElementById('sourceModal');
        const sourceModalContent = document.getElementById('sourceModalContent');
        const sourceModalPre = document.getElementById('sourceModalPre');
        const sourceModalCode = document.getElementById('sourceModalCode');
        const sourceModalFilename = document.getElementById('sourceModalFilename');
        const sourceModalDownload = document.getElementById('sourceModalDownload');
        const closeSourceModal = document.getElementById('closeSourceModal');
        const resizeSourceHandle = document.getElementById('resizeSourceHandle');

        sourceLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const sourceUrl = this.getAttribute('data-source-url');
                const filename = this.getAttribute('data-filename');
                sourceModalFilename.textContent = filename;
                sourceModalDownload.href = sourceUrl;
                sourceModalDownload.setAttribute('download', filename);
                sourceModal.classList.remove('hidden');
                sourceModalCode.textContent = 'Loading...';
                fetch(sourceUrl)
                    .then(response => response.text())
                    .then(text => {
                        sourceModalCode.textContent = text;
                        hljs.highlightElement(sourceModalCode);
                    })
                    .catch(() => {
                        sourceModalCode.textContent = 'Failed to load source code.';
                    });
            });
        });

        closeSourceModal.addEventListener('click', function () {
            sourceModal.classList.add('hidden');
            sourceModalCode.textContent = '';
        });

        // Allow closing modal by clicking outside content
        sourceModal.addEventListener('click', function (e) {
            if (e.target === sourceModal) {
                sourceModal.classList.add('hidden');
                sourceModalCode.textContent = '';
            }
        });

        // Resizing logic for source modal
        let isResizingSource = false;
        let lastDownXSource = 0;
        let lastDownYSource = 0;
        let startWidthSource = 0;
        let startHeightSource = 0;

        resizeSourceHandle.addEventListener('mousedown', function(e) {
            e.preventDefault();
            isResizingSource = true;
            lastDownXSource = e.clientX;
            lastDownYSource = e.clientY;
            startWidthSource = sourceModalContent.offsetWidth;
            startHeightSource = sourceModalContent.offsetHeight;
            document.body.style.userSelect = 'none';
        });

        document.addEventListener('mousemove', function(e) {
            if (!isResizingSource) return;
            const dx = e.clientX - lastDownXSource;
            const dy = e.clientY - lastDownYSource;
            sourceModalContent.style.width = (startWidthSource + dx) + 'px';
            sourceModalContent.style.height = (startHeightSource + dy) + 'px';
        });

        document.addEventListener('mouseup', function() {
            if (isResizingSource) {
                isResizingSource = false;
                document.body.style.userSelect = '';
            }
        });
    });

    // Scroll to section after redirect if ?scroll=source-file or ?scroll=report-file is present
    window.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        const scrollTarget = params.get('scroll');
        if (scrollTarget) {
            const el = document.getElementById(scrollTarget);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });

    // AJAX delete for source code and report
    function showToast(message) {
        let toast = document.createElement('div');
        toast.textContent = message;
        toast.className = 'fixed top-4 right-4 bg-green-700 text-white px-4 py-2 rounded shadow-lg z-50';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }

    document.querySelectorAll('.delete-source-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove only the current file display, not the whole upload section
            fetch(this.dataset.url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.dataset.token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ _method: 'PUT', delete_source_code: 1 })
            })
            .then(res => res.json())
            .then(data => {
                // Find and remove the current file display div (the one with file info and delete button)
                const parent = btn.closest('.mt-2.text-sm.text-gray-300.flex.items-center.space-x-4');
                if (parent) parent.remove();
                // Optionally, show a toast
                showToast('Source code file deleted successfully.');
            })
            .catch(() => alert('Failed to delete source code file.'));
        });
    });

    document.querySelectorAll('.delete-report-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove only the current file display, not the whole upload section
            fetch(this.dataset.url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.dataset.token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ _method: 'PUT', delete_report: 1 })
            })
            .then(res => res.json())
            .then(data => {
                // Find and remove the current file display div (the one with file info and delete button)
                const parent = btn.closest('.mt-2.text-sm.text-gray-300.flex.items-center.space-x-4');
                if (parent) parent.remove();
                // Optionally, show a toast
                showToast('Backtest report deleted successfully.');
            })
            .catch(() => alert('Failed to delete backtest report.'));
        });
    });
    </script>
</x-app-layout> 