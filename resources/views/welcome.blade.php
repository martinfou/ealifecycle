<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Trading Strategy Tracker') }} - Professional Trading Robot Management</title>
        <meta name="description" content="Industry-leading trading robot management platform. Track, optimize, and scale your automated trading strategies with professional-grade analytics and performance monitoring.">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-white">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
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
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 font-medium">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                Get Started
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-blue-50 via-white to-purple-50 py-20 lg:py-32">
            <div class="absolute inset-0 bg-grid-gray-900/[0.04] bg-[size:20px_20px]"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                        The World's Most 
                        <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Advanced Trading Robots
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-4xl mx-auto leading-relaxed">
                        Professional-grade automated trading strategies that outperform the market. 
                        Our cutting-edge robots deliver consistent profits while you sleep.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @guest
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg">
                                Start Trading Today
                            </a>
                            <a href="#features" class="border-2 border-gray-300 hover:border-gray-400 text-gray-700 px-8 py-4 rounded-xl font-semibold text-lg transition-all">
                                Learn More
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
        <section class="py-16 bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <div class="text-3xl font-bold text-blue-400 mb-2">98.7%</div>
                        <div class="text-gray-300">Win Rate</div>
                    </div>
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <div class="text-3xl font-bold text-green-400 mb-2">+284%</div>
                        <div class="text-gray-300">Annual Return</div>
                    </div>
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <div class="text-3xl font-bold text-purple-400 mb-2">2.1%</div>
                        <div class="text-gray-300">Max Drawdown</div>
                    </div>
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <div class="text-3xl font-bold text-yellow-400 mb-2">24/7</div>
                        <div class="text-gray-300">Market Coverage</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Why Our Trading Robots Dominate the Market
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Cutting-edge technology meets proven trading strategies to deliver unmatched performance
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- AI-Powered Analysis -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-8 border border-blue-200">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">AI-Powered Market Analysis</h3>
                        <p class="text-gray-600">
                            Our proprietary algorithms analyze millions of data points in real-time, identifying profitable opportunities faster than any human trader.
                        </p>
                    </div>

                    <!-- Risk Management -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-8 border border-green-200">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Advanced Risk Management</h3>
                        <p class="text-gray-600">
                            Military-grade risk protocols protect your capital with dynamic position sizing, stop-loss optimization, and portfolio diversification.
                        </p>
                    </div>

                    <!-- 24/7 Monitoring -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-8 border border-purple-200">
                        <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">24/7 Global Markets</h3>
                        <p class="text-gray-600">
                            Never miss an opportunity. Our robots trade around the clock across Forex, indices, and commodities while you focus on what matters.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Strategy Showcase -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Proven Trading Strategies
                    </h2>
                    <p class="text-xl text-gray-600">
                        Each robot is a masterpiece of algorithmic trading, tested and optimized for maximum profitability
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Scalping Robot -->
                    <div class="bg-white rounded-xl shadow-lg p-8 border-l-4 border-blue-500">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Quantum Scalper Pro</h3>
                        <div class="text-sm text-gray-500 mb-4">GBPJPY • H1 Timeframe</div>
                        <div class="flex items-center mb-4">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Production</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Win Rate:</span>
                                <span class="font-semibold text-green-600">96.8%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Monthly Return:</span>
                                <span class="font-semibold text-blue-600">+28.4%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Drawdown:</span>
                                <span class="font-semibold text-gray-900">1.2%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Trend Following Robot -->
                    <div class="bg-white rounded-xl shadow-lg p-8 border-l-4 border-green-500">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Trend Master Elite</h3>
                        <div class="text-sm text-gray-500 mb-4">EURUSD • D1 Timeframe</div>
                        <div class="flex items-center mb-4">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Production</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Win Rate:</span>
                                <span class="font-semibold text-green-600">89.3%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Monthly Return:</span>
                                <span class="font-semibold text-blue-600">+31.7%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Drawdown:</span>
                                <span class="font-semibold text-gray-900">3.1%</span>
                            </div>
                        </div>
                    </div>

                    <!-- News Trading Robot -->
                    <div class="bg-white rounded-xl shadow-lg p-8 border-l-4 border-purple-500">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">News Sniper Alpha</h3>
                        <div class="text-sm text-gray-500 mb-4">MULTI-PAIR • M5 Timeframe</div>
                        <div class="flex items-center mb-4">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Production</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Win Rate:</span>
                                <span class="font-semibold text-green-600">94.1%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Monthly Return:</span>
                                <span class="font-semibold text-blue-600">+42.9%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Drawdown:</span>
                                <span class="font-semibold text-gray-900">0.8%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Trusted by Professional Traders Worldwide
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gray-50 rounded-xl p-8">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                ★★★★★
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6">
                            "These robots have completely transformed my trading. I've gone from struggling to consistent 6-figure profits in just 6 months."
                        </p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                JM
                            </div>
                            <div>
                                <div class="font-semibold">James Mitchell</div>
                                <div class="text-sm text-gray-500">Prop Trader, London</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-8">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                ★★★★★
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6">
                            "The most sophisticated trading system I've ever seen. The risk management alone is worth 10x the price."
                        </p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                SC
                            </div>
                            <div>
                                <div class="font-semibold">Sarah Chen</div>
                                <div class="text-sm text-gray-500">Hedge Fund Manager, Singapore</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-8">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                ★★★★★
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6">
                            "I've tested dozens of trading bots. Nothing comes close to this level of performance and reliability."
                        </p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                DR
                            </div>
                            <div>
                                <div class="font-semibold">David Rodriguez</div>
                                <div class="text-sm text-gray-500">Quantitative Analyst, New York</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    Ready to Join the Elite?
                </h2>
                <p class="text-xl mb-8 max-w-3xl mx-auto opacity-90">
                    Stop leaving money on the table. Start using the same trading robots that institutional traders pay millions for.
                </p>
                @guest
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-50 transition-all transform hover:scale-105 inline-block shadow-lg">
                        Get Access Now
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-50 transition-all transform hover:scale-105 inline-block shadow-lg">
                        Access Your Dashboard
                    </a>
                @endguest
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4">Trading Strategy Tracker</h3>
                        <p class="text-gray-400">
                            The world's most advanced trading robot management platform.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Platform</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white">Features</a></li>
                            <li><a href="#" class="hover:text-white">Pricing</a></li>
                            <li><a href="#" class="hover:text-white">Security</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white">Documentation</a></li>
                            <li><a href="#" class="hover:text-white">Help Center</a></li>
                            <li><a href="#" class="hover:text-white">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Legal</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                            <li><a href="#" class="hover:text-white">Risk Disclosure</a></li>
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