<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-200">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-900 text-green-300 border border-green-600 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('plain_text_token'))
                        <div class="mb-6 p-4 bg-blue-900 border border-blue-600 text-blue-200 rounded-lg">
                            <p class="font-bold text-lg text-white mb-2">New Token Generated</p>
                            <p class="text-sm mb-3">Please copy your new token now. For security reasons, you won't be able to see it again!</p>
                            <div class="flex items-center space-x-2 bg-gray-900 p-2 rounded-md">
                                <code class="text-blue-300 font-mono p-2 flex-1 break-all">{{ session('plain_text_token') }}</code>
                                <button onclick="copyToken()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500">
                                    Copy
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Create New Token Form -->
                    <div class="mb-8 p-6 bg-gray-900 border border-gray-700 rounded-lg">
                        <h3 class="text-lg font-medium text-white mb-4">Create New Token</h3>
                        <form method="POST" action="{{ route('tokens.store') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                            @csrf
                            <div class="flex-1 w-full">
                                <x-input-label for="name" value="Token Name" class="text-gray-300" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-800 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="e.g. Mobile App Token" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="w-full sm:w-auto">
                                <x-primary-button class="w-full justify-center">
                                    Create Token
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Existing Tokens -->
                    <div>
                        <h3 class="text-lg font-medium text-white mb-4">Your API Tokens</h3>
                        @if($tokens->isEmpty())
                            <div class="text-center py-12 px-6 bg-gray-900 border-2 border-dashed border-gray-700 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-white">No tokens</h3>
                                <p class="mt-1 text-sm text-gray-400">You haven't created any tokens yet.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto bg-gray-900 border border-gray-700 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead class="bg-gray-800">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Created</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Last Used</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        @foreach($tokens as $token)
                                            <tr class="hover:bg-gray-800/50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $token->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $token->created_at->diffForHumans() }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                    {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex items-center justify-end">
                                                        <form method="POST" action="{{ route('tokens.destroy', $token->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-400 transition-colors"
                                                                onclick="return confirm('Are you sure you want to delete this token? This action cannot be undone.')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Info Box -->
            <div class="mt-6 bg-gray-900 border border-gray-600 rounded-md p-4">
                <h4 class="text-sm font-medium text-blue-400 mb-2">About API Tokens</h4>
                <p class="text-sm text-gray-300">
                    API tokens allow third-party services to authenticate with this application on your behalf.
                    Treat your tokens like passwords; do not share them with anyone.
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyToken() {
            const token = document.querySelector('code').textContent;
            navigator.clipboard.writeText(token).then(() => {
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                setTimeout(() => {
                    button.textContent = originalText;
                }, 2000);
            });
        }
    </script>
    @endpush
</x-app-layout> 