<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('plain_text_token'))
                        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
                            <p class="font-bold mb-2">Please copy your new token now. You won't be able to see it again!</p>
                            <div class="flex items-center space-x-2">
                                <code class="bg-yellow-50 p-2 flex-1 break-all">{{ session('plain_text_token') }}</code>
                                <button onclick="copyToken()" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                    Copy
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Create New Token Form -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">Create New Token</h3>
                        <form method="POST" action="{{ route('tokens.store') }}" class="flex gap-4 items-end">
                            @csrf
                            <div class="flex-1">
                                <x-input-label for="name" value="Token Name" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                    placeholder="e.g. Mobile App Token" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <x-primary-button>
                                    Create Token
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Existing Tokens -->
                    <div>
                        <h3 class="text-lg font-medium mb-4">Your API Tokens</h3>
                        @if($tokens->isEmpty())
                            <p class="text-gray-500 italic">You haven't created any tokens yet.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Created
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Last Used
                                            </th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($tokens as $token)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $token->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $token->created_at->diffForHumans() }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    <form method="POST" action="{{ route('tokens.destroy', $token->id) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Are you sure you want to delete this token?')">
                                                            Delete
                                                        </button>
                                                    </form>
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
        </div>
    </div>

    @push('scripts')
    <script>
        function copyToken() {
            const token = document.querySelector('code').textContent;
            navigator.clipboard.writeText(token).then(() => {
                alert('Token copied to clipboard!');
            });
        }
    </script>
    @endpush
</x-app-layout> 