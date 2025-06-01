<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Trading Strategy Tracker') }} - Professional Trading Strategy Management</title>
        <meta name="description" content="Professional trading strategy management platform. Track, analyze, and optimize your automated trading strategies with comprehensive performance monitoring and lifecycle management.">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-900 text-white">
        <!-- Navigation -->
        <nav class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50 backdrop-blur-sm bg-gray-900/90">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-2xl font-bold text-white">
                                Trading Strategy Tracker
                            </h1>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white font-medium transition-colors">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                Get Started
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative bg-gray-900 py-20 lg:py-32">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                        Master Your Trading 
                        <span class="text-blue-400">
                            Strategy Management
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-4xl mx-auto leading-relaxed">
                        Professional-grade platform to track, analyze, and optimize your automated trading strategies. 
                        Comprehensive lifecycle management with FX Blue integration and advanced analytics.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @guest
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg">
                                Start Managing Today
                            </a>
                            <a href="#features" class="border-2 border-gray-600 hover:border-gray-500 text-gray-300 hover:text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all">
                                Explore Features
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg">
                                View Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </section>

        <!-- Performance Stats -->
        <section class="py-16 bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="text-3xl font-bold text-blue-400 mb-2">100%</div>
                        <div class="text-gray-400">Strategy Visibility</div>
                    </div>
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="text-3xl font-bold text-blue-400 mb-2">Real-time</div>
                        <div class="text-gray-400">Performance Tracking</div>
                    </div>
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="text-3xl font-bold text-blue-400 mb-2">FX Blue</div>
                        <div class="text-gray-400">Import Integration</div>
                    </div>
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="text-3xl font-bold text-blue-400 mb-2">Complete</div>
                        <div class="text-gray-400">Lifecycle Management</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Why Professional Traders Choose Our Platform
                    </h2>
                    <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                        Comprehensive strategy management tools designed for serious algorithmic traders
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Strategy Lifecycle Management -->
                    <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Complete Strategy Lifecycle</h3>
                        <p class="text-gray-400">
                            Track your strategies from Demo to Production with full status history. Monitor transitions from Development to Live trading with detailed change logs and performance analytics.
                        </p>
                    </div>

                    <!-- FX Blue Integration -->
                    <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Seamless FX Blue Import</h3>
                        <p class="text-gray-400">
                            Automatically import your trading history from FX Blue with one-click uploads. Our system intelligently processes CSV files and maps trades to your strategies.
                        </p>
                    </div>

                    <!-- Advanced Analytics -->
                    <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Professional Analytics</h3>
                        <p class="text-gray-400">
                            Comprehensive performance tracking with magic number mapping, timeframe analysis, and symbol-based reporting. Get insights that matter for strategy optimization.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Strategy Management Features -->
        <section class="py-20 bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Everything You Need to Manage Trading Strategies
                    </h2>
                    <p class="text-xl text-gray-400">
                        From initial development to production deployment and beyond
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Strategy Organization -->
                    <div class="bg-gray-900 rounded-xl shadow-lg p-8 border border-gray-700 hover:border-blue-600 transition-colors">
                        <h3 class="text-xl font-bold text-white mb-3">Centralized Strategy Management</h3>
                        <div class="text-sm text-gray-400 mb-4">Organize • Track • Optimize</div>
                        <div class="flex items-center mb-4">
                            <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-medium">Core Feature</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Magic Numbers:</span>
                                <span class="font-semibold text-blue-400">Automatic Mapping</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Timeframes:</span>
                                <span class="font-semibold text-blue-400">All Supported</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Symbol Tracking:</span>
                                <span class="font-semibold text-white">Multi-Pair</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Management -->
                    <div class="bg-gray-900 rounded-xl shadow-lg p-8 border border-gray-700 hover:border-blue-600 transition-colors">
                        <h3 class="text-xl font-bold text-white mb-3">Intelligent Status Tracking</h3>
                        <div class="text-sm text-gray-400 mb-4">Demo → Production → Monitoring</div>
                        <div class="flex items-center mb-4">
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium">Advanced</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Status History:</span>
                                <span class="font-semibold text-blue-400">Complete Log</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Change Tracking:</span>
                                <span class="font-semibold text-blue-400">With Notes</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Transitions:</span>
                                <span class="font-semibold text-white">Auditable</span>
                            </div>
                        </div>
                    </div>

                    <!-- Trade Import -->
                    <div class="bg-gray-900 rounded-xl shadow-lg p-8 border border-gray-700 hover:border-blue-600 transition-colors">
                        <h3 class="text-xl font-bold text-white mb-3">Automated Trade Import</h3>
                        <div class="text-sm text-gray-400 mb-4">FX Blue • CSV • Bulk Processing</div>
                        <div class="flex items-center mb-4">
                            <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-sm font-medium">Integration</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">File Format:</span>
                                <span class="font-semibold text-blue-400">FX Blue CSV</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Processing:</span>
                                <span class="font-semibold text-blue-400">Intelligent</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Validation:</span>
                                <span class="font-semibold text-white">Automatic</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section class="py-20 bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Why Trading Strategy Tracker Is Essential
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <!-- Benefits List -->
                    <div class="space-y-8">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-2">Never Lose Track of Strategy Performance</h3>
                                <p class="text-gray-400">Complete visibility into every strategy's journey from development to retirement with detailed performance metrics and status tracking.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-2">Effortless Trade Data Management</h3>
                                <p class="text-gray-400">Automatically import and organize thousands of trades from FX Blue with intelligent strategy mapping and duplicate detection.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-2">Professional Portfolio Management</h3>
                                <p class="text-gray-400">Organize multiple strategies by timeframes, symbols, and magic numbers with comprehensive analytics for optimization decisions.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-2">Secure & Reliable Platform</h3>
                                <p class="text-gray-400">Enterprise-grade security with user authentication, data protection, and reliable performance for professional trading operations.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Screenshot/Demo -->
                    <div class="bg-gray-800 rounded-xl p-8 border border-gray-700">
                        <div class="bg-gray-900 rounded-lg p-6 mb-6">
                            <h4 class="text-white font-semibold mb-4">Live Dashboard Preview</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-gray-800 rounded">
                                    <span class="text-gray-300">Scalping Strategy Pro</span>
                                    <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">Production</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-800 rounded">
                                    <span class="text-gray-300">Trend Following EA</span>
                                    <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs">Demo</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-800 rounded">
                                    <span class="text-gray-300">News Trading Bot</span>
                                    <span class="bg-yellow-600 text-white px-2 py-1 rounded text-xs">On Hold</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm">Real-time strategy monitoring with status indicators and performance metrics</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-white">
                    Ready to Take Control of Your Trading Strategies?
                </h2>
                <p class="text-xl mb-8 max-w-3xl mx-auto text-gray-300">
                    Join professional traders who use Trading Strategy Tracker to manage, optimize, and scale their algorithmic trading operations.
                </p>
                @guest
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all transform hover:scale-105 inline-block shadow-lg">
                        Start Managing Your Strategies
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all transform hover:scale-105 inline-block shadow-lg">
                        Access Your Dashboard
                    </a>
                @endguest
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-black py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4 text-white">Trading Strategy Tracker</h3>
                        <p class="text-gray-400">
                            Professional strategy management platform for algorithmic traders.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4 text-white">Features</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition-colors">Strategy Management</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">FX Blue Import</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Performance Analytics</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4 text-white">Support</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4 text-white">Legal</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Risk Disclosure</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} Trading Strategy Tracker. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html> 