<x-guest-layout>
    <!-- Welcome Message -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Join EALifeCycle</h2>
        <p class="text-sm text-gray-600">
            Create your account to start managing your Expert Advisor lifecycle professionally
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your full name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your email address" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" 
                            placeholder="Create a secure password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long</p>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" 
                            placeholder="Confirm your password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms and Features Info -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
            <h4 class="text-sm font-medium text-blue-900 mb-2">What you'll get:</h4>
            <ul class="text-xs text-blue-700 space-y-1">
                <li>• Complete EA lifecycle management</li>
                <li>• DevOps-inspired workflows for trading robots</li>
                <li>• Monitor EA performance and deployment status</li>
                <li>• Organize EAs by strategies and environments</li>
                <li>• Professional algorithmic trading operations</li>
            </ul>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gray-600 hover:text-gray-900 underline" href="{{ route('login') }}">
                {{ __('Already have an account? Sign in') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Create Account') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Privacy Note -->
    <div class="mt-6 text-center">
        <p class="text-xs text-gray-500">
            By creating an account, you agree to keep your EA data secure and private. 
            We don't share your algorithmic trading information with third parties.
        </p>
    </div>
</x-guest-layout>
