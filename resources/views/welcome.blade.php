<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'EALifeCycle') }} - The Definitive Platform for Managing Expert Advisors Professionally</title>
        <meta name="description" content="EALifeCycle - Expert Advisor Lifecycle Management. Complete EA lifecycle management with DevOps-inspired workflows for algorithmic trading and trading robot operations.">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/algo-trading/ea-lifecycle-favicon-simple.svg') }}">
        <link rel="alternate icon" href="/favicon.ico">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-900 text-white">
        <!-- Navigation -->
        <nav class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50 backdrop-blur-sm bg-gray-900/90">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center space-x-3">
                            <img src="{{ asset('images/algo-trading/ea-lifecycle-main-logo.svg') }}" alt="EA Lifecycle Logo" class="h-10 w-auto">
                            <h1 class="text-2xl font-bold text-white">
                                EALifeCycle
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
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Text Content -->
                    <div>
                        <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                            Expert Advisor 
                            <span class="text-blue-400">
                                Lifecycle Management
                            </span>
                        </h1>
                        <p class="text-xl md:text-2xl text-gray-300 mb-8 leading-relaxed">
                            The definitive platform for managing Expert Advisors professionally. Complete EA lifecycle management with DevOps-inspired workflows for algorithmic trading teams and trading robot operations.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            @guest
                                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg">
                                    Start Managing EAs Today
                                </a>
                                <a href="#features" class="border-2 border-gray-600 hover:border-gray-500 text-gray-300 hover:text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all">
                                    Explore EA Features
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg">
                                    View EA Dashboard
                                </a>
                            @endguest
                        </div>
                    </div>
                    
                    <!-- Hero Image -->
                    <div class="flex justify-center lg:justify-end">
                        <div class="relative">
                            <img src="{{ asset('images/algo-trading/trading-dashboard.svg') }}" alt="EA Lifecycle Control Center - Professional Trading Dashboard" class="w-full max-w-7xl h-auto"/>
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/20 to-transparent rounded-lg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Performance Stats -->
        <section class="py-16 bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="text-3xl font-bold text-blue-400 mb-2">Complete</div>
                        <div class="text-gray-400">EA Lifecycle</div>
                    </div>
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="text-3xl font-bold text-blue-400 mb-2">DevOps</div>
                        <div class="text-gray-400">Inspired Workflows</div>
                    </div>
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="text-3xl font-bold text-blue-400 mb-2">Multi-User</div>
                        <div class="text-gray-400">Team Collaboration</div>
                    </div>
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="text-3xl font-bold text-blue-400 mb-2">Professional</div>
                        <div class="text-gray-400">Trading Operations</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- EA Lifecycle Process Section -->
        <section class="py-20 bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Complete Expert Advisor Lifecycle Management
                    </h2>
                    <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                        From initial strategy concept through live trading to retirement - track every stage of your EA's journey with complete audit trails and status history.
                    </p>
                </div>
                
                <div class="flex justify-center mb-12">
                    <img src="{{ asset('images/algo-trading/ea-lifecycle-flow.svg') }}" alt="Expert Advisor Lifecycle Flow" class="w-full max-w-7xl h-auto"/>
                </div>
                
                <div class="mt-12 text-center">
                    <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 max-w-4xl mx-auto">
                        <h3 class="text-xl font-bold text-white mb-4">DevOps-Inspired EA Management</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                            <div class="text-center">
                                <div class="text-blue-400 font-semibold mb-2">🔄 Status Transitions</div>
                                <p class="text-gray-400">Track every status change with timestamps and notes</p>
                            </div>
                            <div class="text-center">
                                <div class="text-green-400 font-semibold mb-2">📊 Performance Analytics</div>
                                <p class="text-gray-400">Monitor EA performance across all lifecycle stages</p>
                            </div>
                            <div class="text-center">
                                <div class="text-purple-400 font-semibold mb-2">🔒 Audit Trail</div>
                                <p class="text-gray-400">Complete history for compliance and optimization</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Why Algorithmic Trading Teams Choose EALifeCycle
                    </h2>
                    <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                        Comprehensive EA management tools designed for professional trading robot development and operations
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- EA Lifecycle Management -->
                    <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Complete EA Lifecycle</h3>
                        <p class="text-gray-400">
                            Track your Expert Advisors from Development through Testing to Production with full status history. Monitor transitions from Demo to Live trading with detailed change logs and performance analytics.
                        </p>
                    </div>

                    <!-- Team Collaboration -->
                    <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">Multi-User Collaboration</h3>
                        <p class="text-gray-400">
                            Complete admin user management system with group-based permissions. Perfect for algorithmic trading teams, prop firms, and EA developers working in collaborative environments.
                        </p>
                    </div>

                    <!-- DevOps Integration -->
                    <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 hover:border-gray-600 transition-colors">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">DevOps-Inspired Workflows</h3>
                        <p class="text-gray-400">
                            Professional EA operations with version control concepts, deployment pipelines, and continuous monitoring. Transform manual EA management into automated, scalable processes.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- EA Management Features -->
        <section class="py-20 bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Everything You Need to Manage Expert Advisors
                    </h2>
                    <p class="text-xl text-gray-400">
                        From initial development to production deployment and retirement
                    </p>
                </div>

                <!-- Trading Visualization -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                    <div>
                        <img src="{{ asset('images/algo-trading/trading-charts.svg') }}" alt="Algorithmic Trading Charts" class="w-full h-auto rounded-lg"/>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-4">Real-Time Market Analysis</h3>
                        <p class="text-gray-400 mb-6">
                            Advanced algorithmic trading visualization with candlestick charts, trend analysis, and moving averages. Our platform provides comprehensive market data analysis to help your Expert Advisors make informed trading decisions.
                        </p>
                        <ul class="space-y-3 text-gray-400">
                            <li class="flex items-center">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mr-3"></span>
                                Live candlestick chart analysis
                            </li>
                            <li class="flex items-center">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                                Trend line identification
                            </li>
                            <li class="flex items-center">
                                <span class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></span>
                                Moving average calculations
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- EA Registry -->
                    <div class="bg-gray-900 rounded-xl shadow-lg p-8 border border-gray-700 hover:border-blue-600 transition-colors">
                        <h3 class="text-xl font-bold text-white mb-3">📚 EA Registry</h3>
                        <div class="text-sm text-gray-400 mb-4">Central Repository • Version Control • Documentation</div>
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

                    <!-- Stage Management -->
                    <div class="bg-gray-900 rounded-xl shadow-lg p-8 border border-gray-700 hover:border-blue-600 transition-colors">
                        <h3 class="text-xl font-bold text-white mb-3">📋 Stage Management</h3>
                        <div class="text-sm text-gray-400 mb-4">Development → Testing → Production</div>
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
                        Why EALifeCycle Is Essential
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

                    <!-- AI Robot Trader Image -->
                    <div class="flex justify-center">
                        <img src="{{ asset('images/algo-trading/robot-trader.svg') }}" alt="AI-Powered Trading Robot" class="w-full max-w-md h-auto"/>
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
                    Join professional traders who use EALifeCycle to manage, optimize, and scale their algorithmic trading operations.
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
                        <h3 class="text-xl font-bold mb-4 text-white">EALifeCycle</h3>
                        <p class="text-gray-400">
                            The definitive platform for managing Expert Advisors professionally.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4 text-white">Features</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition-colors">EA Lifecycle Management</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Multi-User Collaboration</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">DevOps Integration</a></li>
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
                    <p>&copy; {{ date('Y') }} EALifeCycle. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html> 