<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit User: ') . $user->name }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.show', $user) }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    View User
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Back to Users
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PATCH')

                        <!-- User Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-300">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-300">Email Address *</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('email') border-red-500 @enderror" 
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password (Optional) -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-300">New Password (Optional)</label>
                            <input type="password" name="password" id="password" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('password') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-400">Leave blank to keep current password</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500">
                            <p class="mt-1 text-xs text-gray-400">Required only if changing password</p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- User Statistics -->
            <div class="mt-6 bg-gray-900 border border-gray-600 rounded-md p-4">
                <h4 class="text-sm font-medium text-blue-400 mb-2">User Statistics</h4>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-300">
                    <div>
                        <span class="font-medium">Groups:</span> {{ $user->groups()->count() }}
                    </div>
                    <div>
                        <span class="font-medium">Strategies:</span> {{ $user->strategies()->count() }}
                    </div>
                    <div>
                        <span class="font-medium">Joined:</span> {{ $user->created_at->format('M j, Y') }}
                    </div>
                    <div>
                        <span class="font-medium">Last Updated:</span> {{ $user->updated_at->format('M j, Y') }}
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">
                    Group assignments can be managed from the user's detail page.
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 