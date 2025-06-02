<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Create New User') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <!-- User Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-300">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('name') border-red-500 @enderror" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-300">Email Address *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('email') border-red-500 @enderror" 
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-300">Password *</label>
                            <input type="password" name="password" id="password" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500 @error('password') border-red-500 @enderror" 
                                   required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirm Password *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white shadow-sm focus:border-gray-500 focus:ring-gray-500" 
                                   required>
                        </div>

                        <!-- Group Assignments -->
                        @if($groups->count() > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-300 mb-3">Group Assignments (Optional)</label>
                            <div class="space-y-3 p-4 bg-gray-900 border border-gray-600 rounded-md">
                                @foreach($groups as $group)
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" 
                                               name="groups[]" 
                                               value="{{ $group->id }}" 
                                               id="group_{{ $group->id }}"
                                               class="rounded bg-gray-800 border-gray-600 text-gray-600 focus:ring-gray-500">
                                        <label for="group_{{ $group->id }}" class="flex-1 text-sm text-gray-300">
                                            {{ $group->name }} - {{ $group->description }}
                                        </label>
                                        <select name="permissions[]" class="text-sm bg-gray-800 border-gray-600 text-white rounded-md">
                                            <option value="read">Read Only</option>
                                            <option value="write">Read & Write</option>
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Select groups and permissions for this user. Groups can be modified later.</p>
                        </div>
                        @endif

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.users.index') }}" 
                               class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-gray-900 border border-gray-600 rounded-md p-4">
                <h4 class="text-sm font-medium text-blue-400 mb-2">User Creation</h4>
                <p class="text-sm text-gray-300">
                    Create a new user account with optional group assignments. 
                    Users will be able to log in immediately with the provided credentials. 
                    Group memberships and permissions can be modified after account creation.
                </p>
            </div>
        </div>
    </div>
</x-app-layout> 