<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="text-white font-bold text-xl hover:text-gray-300 transition-colors">
                        🤖 EALifeCycle
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- API Dropdown -->
                    <div class="relative flex" x-data="{ apiOpen: false }">
                        <button @click="apiOpen = !apiOpen"
                                class="inline-flex items-center px-1 border-b-2 text-sm font-medium leading-5 text-gray-300 hover:text-white hover:border-blue-400 focus:outline-none focus:text-white focus:border-blue-400 transition duration-150 ease-in-out {{ request()->routeIs('tokens.*') || request()->is('api/documentation') ? 'border-blue-400' : 'border-transparent' }}">
                            <div>API</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                        <div x-show="apiOpen" @click.away="apiOpen = false"
                             class="absolute z-50 mt-12 w-48 rounded-md shadow-lg origin-top-right right-0"
                             style="display: none;"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 bg-gray-700 py-1">
                                <x-dropdown-link :href="route('tokens.index')">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                                <a href="/api/documentation" target="_blank" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-300 hover:bg-gray-800 focus:outline-none focus:bg-gray-800 transition duration-150 ease-in-out">
                                    {{ __('API Docs') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <x-nav-link :href="route('strategies.index')" :active="request()->routeIs('strategies.*')">
                        {{ __('Strategies') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('portfolios.index')" :active="request()->routeIs('portfolios.*')">
                        {{ __('Portfolios') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('trades.index')" :active="request()->routeIs('trades.*')">
                        {{ __('Trades') }}
                    </x-nav-link>

                    <!-- Admin Dropdown -->
                    <div class="relative flex" x-data="{ adminOpen: false }">
                        <button @click="adminOpen = !adminOpen"
                                class="inline-flex items-center px-1 border-b-2 text-sm font-medium leading-5 text-gray-300 hover:text-white hover:border-blue-400 focus:outline-none focus:text-white focus:border-blue-400 transition duration-150 ease-in-out {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'border-blue-400' : 'border-transparent' }}">
                            <div>Admin</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                        <div x-show="adminOpen" @click.away="adminOpen = false"
                             class="absolute z-50 mt-12 w-48 rounded-md shadow-lg origin-top-right right-0"
                             style="display: none;"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 bg-gray-700 py-1">
                                <x-dropdown-link :href="route('admin.users.index')">
                                    {{ __('Users') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.groups.index')">
                                    {{ __('Groups') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.statuses.index')">
                                    {{ __('Statuses') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.timeframes.index')">
                                    {{ __('Timeframes') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('symbols.index')">
                                    {{ __('Symbols') }}
                                </x-dropdown-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-900 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-300 hover:bg-gray-800 focus:outline-none focus:bg-gray-800 focus:text-gray-300 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 bg-gray-900">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Responsive API Menu -->
            <div class="pt-2 pb-3 space-y-1">
                <div class="px-4 font-medium text-base text-gray-200">API</div>
                <x-responsive-nav-link :href="route('tokens.index')" :active="request()->routeIs('tokens.*')">
                    {{ __('API Tokens') }}
                </x-responsive-nav-link>
                <a href="/api/documentation" target="_blank" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-300 hover:text-gray-200 hover:bg-gray-700 hover:border-gray-600 focus:outline-none focus:text-gray-200 focus:bg-gray-700 focus:border-gray-600 transition duration-150 ease-in-out">
                    {{ __('API Docs') }}
                </a>
            </div>

            <!-- Responsive Admin Menu -->
            @can('view-admin-area')
                <div class="pt-4 pb-1 border-t border-gray-600">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-200">Admin</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Users') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.groups.index')" :active="request()->routeIs('admin.groups.*')">
                            {{ __('Groups') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.statuses.index')" :active="request()->routeIs('admin.statuses.*')">
                            {{ __('Statuses') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.timeframes.index')" :active="request()->routeIs('admin.timeframes.*')">
                            {{ __('Timeframes') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('symbols.index')" :active="request()->routeIs('symbols.*')">
                            {{ __('Symbols') }}
                        </x-responsive-nav-link>
                    </div>
                </div>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-700 bg-gray-900">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
