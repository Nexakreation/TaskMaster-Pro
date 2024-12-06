<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }" 
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TaskMaster Pro</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50 dark:bg-slate-900 overflow-x-hidden">
        <!-- Navigation Bar -->
        <nav class="fixed w-screen overflow-x-hidden bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg z-50 border-b border-gray-400 dark:border-slate-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-xl sm:text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                            TaskMaster Pro
                        </span>
                    </div>
                    
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('home') }}" class="text-sm sm:text-base text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Home</a>
                                <a href="{{ route('profile.edit') }}" class="text-sm sm:text-base text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Profile</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm sm:text-base text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm sm:text-base bg-purple-600 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">Register</a>
                                @endif
                            @endauth
                        @endif
                        
                        <button 
                            @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')" 
                            class="p-2 rounded-lg bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600"
                        >
                            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative pt-16 overflow-x-hidden overflow-y-hidden">
            <div class="absolute inset-0 blur-3xl opacity-30 dark:opacity-20">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-pink-400 transform rotate-12"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 sm:pt-20 pb-16 sm:pb-24">
                <div class="text-center">
                    <h1 class="text-3xl md:text-4xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-6">
                        Manage Tasks with
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 block sm:inline">
                            Intelligence
                        </span>
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto mb-6 sm:mb-8 px-4">
                        Transform your productivity with our AI-powered task management system.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4 px-4">
                        <a href="{{ route('register') }}" class="bg-purple-600 text-white px-6 sm:px-8 py-3 rounded-lg text-base sm:text-lg font-semibold hover:bg-purple-700 transition-colors w-full sm:w-auto">
                            Get Started Free
                        </a>
                        <a href="#features" class="bg-white border border-gray-400 dark:border-none dark:bg-slate-800 text-gray-900 dark:text-white px-6 sm:px-8 py-3 rounded-lg text-base sm:text-lg font-semibold hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors w-full sm:w-auto">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <section id="features" class="py-12 sm:py-20 bg-white dark:bg-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 sm:mb-16">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Powerful Features for Modern Teams
                    </h2>
                    <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 px-4">
                        Everything you need to manage tasks effectively
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-gray-300 border border-gray-400 dark:border-none dark:bg-slate-700 rounded-xl p-6 sm:p-8 hover:scale-105 transition-transform duration-300">
                        <div class="h-12 w-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Smart Task Management</h3>
                        <p class="text-gray-600 dark:text-gray-300">Organize tasks with AI-powered prioritization and intelligent scheduling.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-gray-300 border border-gray-400 dark:border-none  dark:bg-slate-700 rounded-xl p-6 sm:p-8 hover:scale-105 transition-transform duration-300">
                        <div class="h-12 w-12 bg-pink-100 dark:bg-pink-900 rounded-lg flex items-center justify-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600 dark:text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Real-time Filtering</h3>
                        <p class="text-gray-600 dark:text-gray-300">Filter tasks by status - completed, pending, or overdue - to stay focused on what matters.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-gray-300 border border-gray-400 dark:border-none dark:bg-slate-700 rounded-xl p-6 sm:p-8 hover:scale-105 transition-transform duration-300">
                        <div class="h-12 w-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Analytics & Insights</h3>
                        <p class="text-gray-600 dark:text-gray-300">Get detailed insights into your productivity and team performance.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-12 sm:py-20 bg-gray-50 dark:bg-slate-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 sm:mb-16">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Loved by Teams Worldwide
                    </h2>
                    <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 px-4">
                        See what our users have to say
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <!-- Testimonial 1 -->
                    <div class="bg-gray-300 border border-gray-400 dark:border-none dark:bg-slate-800 p-6 sm:p-8 rounded-xl shadow-lg">
                        <div class="flex items-center mb-6">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                JS
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">John Smith</h4>
                                <p class="text-gray-600 dark:text-gray-400">Project Manager</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 italic">
                            "TaskMaster Pro has transformed how our team collaborates. The AI features are game-changing!"
                        </p>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="bg-gray-300 border border-gray-400 dark:border-none dark:bg-slate-800 p-6 sm:p-8 rounded-xl shadow-lg">
                        <div class="flex items-center mb-6">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                SD
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Sarah Davis</h4>
                                <p class="text-gray-600 dark:text-gray-400">Freelancer</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 italic">
                            "The intuitive interface and smart features help me stay organized and meet all my deadlines."
                        </p>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="bg-gray-300 border border-gray-400 dark:border-none dark:bg-slate-800 p-6 sm:p-8 rounded-xl shadow-lg">
                        <div class="flex items-center mb-6">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                MJ
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Mike Johnson</h4>
                                <p class="text-gray-600 dark:text-gray-400">Team Lead</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 italic">
                            "Best task management tool we've ever used. The analytics help us optimize our workflow."
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-12 sm:py-20 bg-white dark:bg-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                    <div class="text-center p-4 sm:p-6">
                        <div class="text-3xl sm:text-4xl font-bold text-purple-600 dark:text-purple-400 mb-2">10k+</div>
                        <div class="text-sm sm:text-base text-gray-600 dark:text-gray-300">Active Users</div>
                    </div>
                    <div class="text-center p-4 sm:p-6">
                        <div class="text-3xl sm:text-4xl font-bold text-purple-600 dark:text-purple-400 mb-2">50k+</div>
                        <div class="text-sm sm:text-base text-gray-600 dark:text-gray-300">Tasks Completed</div>
                    </div>
                    <div class="text-center p-4 sm:p-6">
                        <div class="text-3xl sm:text-4xl font-bold text-purple-600 dark:text-purple-400 mb-2">99%</div>
                        <div class="text-sm sm:text-base text-gray-600 dark:text-gray-300">Satisfaction Rate</div>
                    </div>
                    <div class="text-center p-4 sm:p-6">
                        <div class="text-3xl sm:text-4xl font-bold text-purple-600 dark:text-purple-400 mb-2">24/7</div>
                        <div class="text-sm sm:text-base text-gray-600 dark:text-gray-300">Support</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-12 sm:py-20 bg-gray-50 dark:bg-slate-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl p-6 sm:p-12 text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-grid-white/10 bg-grid"></div>
                    <div class="relative">
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4 sm:mb-6">
                            Ready to Transform Your Workflow?
                        </h2>
                        <p class="text-lg sm:text-xl text-white/90 mb-6 sm:mb-8 max-w-2xl mx-auto px-4">
                            Join thousands of satisfied users and experience the future of task management today.
                        </p>
                        <a href="{{ route('register') }}" class="inline-block bg-white text-purple-600 px-6 sm:px-8 py-3 sm:py-4 rounded-lg text-base sm:text-lg font-semibold hover:bg-gray-100 transition-colors w-full sm:w-auto">
                            Start Your Free Trial
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white dark:bg-slate-800 border-t border-gray-400 dark:border-slate-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center text-gray-600 dark:text-gray-400">
                    <p>&copy; {{ date('Y') }} TaskMaster Pro. All rights reserved. by <a href="https://ashadeewanexports.com/portfolio" class="text-purple-600 dark:text-purple-400">Nikhil kumar</a> </p>
                </div>
            </div>
        </footer>
    </body>
</html> 