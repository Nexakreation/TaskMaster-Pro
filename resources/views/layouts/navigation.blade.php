<!-- Dark Mode Toggle  -->
<div class="fixed top-4 right-4 z-50 ">
    <button 
        x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
        @click="
            darkMode = !darkMode; 
            localStorage.theme = darkMode ? 'dark' : 'light'; 
            document.documentElement.classList.toggle('dark');
            // Dispatch a custom event when theme changes
            window.dispatchEvent(new CustomEvent('theme-changed'));
        "
        class="flex items-center justify-center p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 shadow-lg border border-black border-opacity-35 dark:border-gray-600"
    >
        <!-- Sun icon -->
        <svg 
            x-show="darkMode"
            xmlns="http://www.w3.org/2000/svg" 
            class="h-5 w-5" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <!-- Moon icon -->
        <svg 
            x-show="!darkMode"
            xmlns="http://www.w3.org/2000/svg" 
            class="h-5 w-5" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>
</div>

<nav x-data="{ open: false, navExpanded: localStorage.getItem('nav-expanded') === 'true' }" 
     :class="{ 'w-64': navExpanded, 'w-16': !navExpanded }"
     class="hidden lg:block fixed top-0 left-0 h-full bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-all duration-300 shadow-lg">
    
    <!-- Toggle Expand Button -->
    <button @click="
        navExpanded = !navExpanded; 
        localStorage.setItem('nav-expanded', navExpanded);
        $dispatch('nav-toggle', { expanded: navExpanded });
    "
            class="absolute -right-3 top-7 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-full p-1 hover:bg-gray-100 dark:hover:bg-gray-600 shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" 
             :class="{ 'rotate-180': !navExpanded }"
             class="h-4 w-4 text-gray-500 dark:text-gray-400 transition-transform duration-300" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <!-- Logo -->
    <div class="flex items-center justify-center h-16 border-b border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
        <a href="{{ route('home') }}" class="flex items-center">
            <div x-data="{ 
                darkMode: localStorage.getItem('theme') === 'dark',
                updateLogo() {
                    this.darkMode = document.documentElement.classList.contains('dark');
                }
            }"
            @theme-changed.window="updateLogo"
            >
                <img :src="darkMode ? '{{ asset('images/sticky-dark.png') }}' : '{{ asset('images/sticky.png') }}'"
                     alt="Logo" 
                     class="h-9 w-9"
                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=S&color=7F9CF5&background=EBF4FF';">
            </div>
            <span x-show="navExpanded" 
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 transform -translate-x-2"
                  x-transition:enter-end="opacity-100 transform translate-x-0"
                  x-transition:leave="transition ease-in duration-300"
                  x-transition:leave-start="opacity-100 transform translate-x-0"
                  x-transition:leave-end="opacity-0 transform -translate-x-2"
                  class="ml-2 text-xl font-bold text-gray-800 dark:text-white">
                Sticky
            </span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex flex-col h-[calc(100%-4rem)]">
        <div class="flex-1 py-4">
            <!-- Home -->
            <div class="px-2">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')" 
                    class="flex items-center w-full px-3 py-2 rounded-lg transition-colors duration-200 relative group
                        {{ request()->routeIs('home') 
                            ? 'bg-purple-100 text-purple-800 dark:bg-gray-700 dark:text-white' 
                            : 'text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('home') ? 'text-purple-600 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span x-show="navExpanded" 
                          x-transition:enter="transition ease-out duration-300"
                          x-transition:enter-start="opacity-0 transform -translate-x-2"
                          x-transition:enter-end="opacity-100 transform translate-x-0"
                          x-transition:leave="transition ease-in duration-300"
                          x-transition:leave-start="opacity-100 transform translate-x-0"
                          x-transition:leave-end="opacity-0 transform -translate-x-2"
                          class="ml-2">
                        {{ __('Home') }}
                    </span>
                    <!-- Tooltip -->
                    <div x-show="!navExpanded" 
                         class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
                        {{ __('Home') }}
                    </div>
                </x-nav-link>
            </div>

            <!-- Tasks Section -->
            <div class="mt-0">
                <!-- <h3 class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center justify-center">
                    Tasks
                </h3> -->
                <div class="mt-2 px-2 space-y-1 flex flex-col">
                    <!-- Today -->
                    <x-nav-link :href="route('tasks.today')" :active="request()->routeIs('tasks.today')" 
                        class="flex items-center w-full px-3 py-2 rounded-lg transition-colors duration-200 relative group
                            {{ request()->routeIs('tasks.today') 
                                ? 'bg-purple-100 text-purple-800 dark:bg-gray-700 dark:text-white' 
                                : 'text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('tasks.today') ? 'text-purple-600 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-show="navExpanded" 
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 transform -translate-x-2"
                              x-transition:enter-end="opacity-100 transform translate-x-0"
                              x-transition:leave="transition ease-in duration-300"
                              x-transition:leave-start="opacity-100 transform translate-x-0"
                              x-transition:leave-end="opacity-0 transform -translate-x-2"
                              class="ml-2">Today</span>
                        <!-- Tooltip -->
                        <div x-show="!navExpanded" 
                             class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
                            Today's Tasks
                        </div>
                    </x-nav-link>

                    <!-- Upcoming -->
                    <x-nav-link :href="route('tasks.upcoming')" :active="request()->routeIs('tasks.upcoming')" 
                        class="flex items-center w-full px-3 py-2 rounded-lg transition-colors duration-200 relative group
                            {{ request()->routeIs('tasks.upcoming') 
                                ? 'bg-purple-100 text-purple-800 dark:bg-gray-700 dark:text-white' 
                                : 'text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('tasks.upcoming') ? 'text-purple-600 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z" />
                        </svg>
                        <span x-show="navExpanded" 
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 transform -translate-x-2"
                              x-transition:enter-end="opacity-100 transform translate-x-0"
                              x-transition:leave="transition ease-in duration-300"
                              x-transition:leave-start="opacity-100 transform translate-x-0"
                              x-transition:leave-end="opacity-0 transform -translate-x-2"
                              class="ml-2">Upcoming</span>
                        <!-- Tooltip -->
                        <div x-show="!navExpanded" 
                             class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
                            Upcoming Tasks
                        </div>
                    </x-nav-link>

                    <!-- Calendar -->
                    <x-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.index')" 
                        class="flex items-center w-full px-3 py-2 rounded-lg transition-colors duration-200 relative group
                            {{ request()->routeIs('calendar.index') 
                                ? 'bg-purple-100 text-purple-800 dark:bg-gray-700 dark:text-white' 
                                : 'text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('calendar.index') ? 'text-purple-600 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span x-show="navExpanded" 
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 transform -translate-x-2"
                              x-transition:enter-end="opacity-100 transform translate-x-0"
                              x-transition:leave="transition ease-in duration-300"
                              x-transition:leave-start="opacity-100 transform translate-x-0"
                              x-transition:leave-end="opacity-0 transform -translate-x-2"
                              class="ml-2">Calendar</span>
                        <!-- Tooltip -->
                        <div x-show="!navExpanded" 
                             class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
                            Calendar View
                        </div>
                    </x-nav-link>

                  
                </div>
            </div>

            <!-- Notes Section -->
            <div class="mt-2 px-2 space-y-1">
                <!-- <h3 class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center justify-center">
                    Notes
                </h3> -->
                <!-- Sticky Notes -->
                <x-nav-link :href="route('notes.index')" :active="request()->routeIs('notes.index')" 
                    class="flex items-center w-full px-3 py-2 rounded-lg transition-colors duration-200 relative group
                        {{ request()->routeIs('notes.index') 
                            ? 'bg-purple-100 text-purple-800 dark:bg-gray-700 dark:text-white' 
                            : 'text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('notes.index') ? 'text-purple-600 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="navExpanded" 
                          x-transition:enter="transition ease-out duration-300"
                          x-transition:enter-start="opacity-0 transform -translate-x-2"
                          x-transition:enter-end="opacity-100 transform translate-x-0"
                          x-transition:leave="transition ease-in duration-300"
                          x-transition:leave-start="opacity-100 transform translate-x-0"
                          x-transition:leave-end="opacity-0 transform -translate-x-2"
                          class="ml-2">Sticky Notes</span>
                    <!-- Tooltip -->
                    <div x-show="!navExpanded" 
                         class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
                        Sticky Notes
                    </div>
                </x-nav-link>
                <!-- <h3 class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center justify-center">
                    Tasks
                </h3> -->
         <!-- Tasks -->
<x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.index')" 
    class="flex items-center w-full px-3 py-2 rounded-lg transition-colors duration-200 relative group
        {{ request()->routeIs('tasks.index') 
            ? 'bg-purple-100 text-purple-800 dark:bg-gray-700 dark:text-white' 
            : 'text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700' }}">
    <svg xmlns="http://www.w3.org/2000/svg" 
         class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('tasks.index') ? 'text-purple-600 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}"
         fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
    </svg>
    <span x-show="navExpanded" 
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 transform -translate-x-2"
          x-transition:enter-end="opacity-100 transform translate-x-0"
          x-transition:leave="transition ease-in duration-300"
          x-transition:leave-start="opacity-100 transform translate-x-0"
          x-transition:leave-end="opacity-0 transform -translate-x-2"
          class="ml-2">Tasks</span>
    <!-- Tooltip -->
    <div x-show="!navExpanded" 
         class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
        Task List
    </div>
</x-nav-link>

            </div>
              <!-- Analytics -->
              <div class="mt-2 px-2 space-y-1">
                        <!-- <h3 class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center justify-center">
                            Analytics
                        </h3> -->
                        <!-- Analytics Link -->
                        <x-nav-link :href="route('analytics')" :active="request()->routeIs('analytics')" 
                            class="flex items-center w-full px-3 py-2 rounded-lg transition-colors duration-200 relative group
                                {{ request()->routeIs('analytics') 
                                    ? 'bg-purple-100 text-purple-800 dark:bg-gray-700 dark:text-white' 
                                    : 'text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('analytics') ? 'text-purple-600 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span x-show="navExpanded" 
                                  x-transition:enter="transition ease-out duration-300"
                                  x-transition:enter-start="opacity-0 transform -translate-x-2"
                                  x-transition:enter-end="opacity-100 transform translate-x-0"
                                  x-transition:leave="transition ease-in duration-300"
                                  x-transition:leave-start="opacity-100 transform translate-x-0"
                                  x-transition:leave-end="opacity-0 transform -translate-x-2"
                                  class="ml-2">Analytics</span>
                            <!-- Tooltip -->
                            <div x-show="!navExpanded" 
                                 class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
                                Task Analytics
                            </div>
                        </x-nav-link>
                    </div>
        </div>
        

        <!--User Profile -->
        <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 relative z-50">
            <div class="p-2" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-center lg:justify-start p-2 rounded-lg text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                         alt="{{ Auth::user()->name }}"
                         class="h-8 w-8 rounded-full object-cover flex-shrink-0 border-2 border-gray-300 dark:border-gray-600">
                    <div x-show="navExpanded" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-x-2"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform -translate-x-2"
                         class="ml-2 text-left">
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</div>
                    </div>
                </button>

                <!-- Dropdown Menu  -->
                <div x-show="open" 
                     @click.away="open = false" 
                     class="absolute left-full bottom-0 ml-2 mb-2 w-48 rounded-lg bg-white dark:bg-gray-700 shadow-lg border border-gray-100 dark:border-gray-600 z-50"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">
                    <div class="py-1 z-50">
                        <a href="{{ route('profile.edit') }}" 
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('Profile') }}
                            </div>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Log Out') }}
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile menu button -->
<!-- Mobile Navigation -->
<div class="lg:hidden" x-data="{ mobileMenuOpen: false }">
    <!-- Mobile menu button -->
    <button 
        @click="mobileMenuOpen = !mobileMenuOpen" 
        class="fixed top-4 left-4 inline-flex items-center justify-center p-1.5 sm:p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 border border-black border-opacity-35 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 z-50"
    >
        <svg class="h-5 w-5 sm:h-6 sm:w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Mobile menu -->
    <div 
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-x-full"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200" 
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 -translate-x-full"
        class="fixed inset-0 z-40"
        @click.away="mobileMenuOpen = false"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

        <!-- Menu content -->
        <nav class="fixed inset-y-0 left-0 w-64 bg-gray-100 dark:bg-gray-800 shadow-lg overflow-y-auto">
            <!-- Logo section -->
            <div class="flex items-center  justify-end h-16 pr-14 border-b border-gray-200 dark:border-gray-700 bg-gray-200 dark:bg-gray-800">
                <a href="{{ route('home') }}" class="flex items-center">
                    <div x-data="{ 
                        darkMode: localStorage.getItem('theme') === 'dark',
                        updateLogo() {
                            this.darkMode = document.documentElement.classList.contains('dark');
                        }
                    }"
                    @theme-changed.window="updateLogo"
                    >
                        <img :src="darkMode ? '{{ asset('images/sticky-dark.png') }}' : '{{ asset('images/sticky.png') }}'"
                             alt="Logo" 
                             class="h-8 w-8"
                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=S&color=7F9CF5&background=EBF4FF';">
                    </div>
                    <span class="ml-2 text-xl font-bold text-gray-800 dark:text-gray-100">Sticky</span>
                </a>
        
            </div>

            <!-- Navigation Links -->
            <div class="px-2 py-4 space-y-1 dark:text-gray-100">
                <!-- Home -->
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" 
                    class="flex items-center px-3 py-2 rounded-lg transition-colors duration-200 ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('Home') }}
                </x-responsive-nav-link>

                <!-- Today's Tasks -->
                <x-responsive-nav-link :href="route('tasks.today')" :active="request()->routeIs('tasks.today')" 
                    class="flex items-center px-3 py-2 rounded-lg transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Today
                </x-responsive-nav-link>

                <!-- Upcoming Tasks -->
                <x-responsive-nav-link :href="route('tasks.upcoming')" :active="request()->routeIs('tasks.upcoming')" 
                    class="flex items-center px-3 py-2 rounded-lg transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z" />
                    </svg>
                    Upcoming
                </x-responsive-nav-link>

                <!-- Calendar -->
                <x-responsive-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.index')" 
                    class="flex items-center px-3 py-2 rounded-lg transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Calendar
                </x-responsive-nav-link>

                <!-- Sticky Notes -->
                <x-responsive-nav-link :href="route('notes.index')" :active="request()->routeIs('notes.index')" 
                    class="flex items-center px-3 py-2 rounded-lg transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Sticky Notes
                </x-responsive-nav-link>

                <!-- Tasks -->
                <x-responsive-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.index')" 
                    class="flex items-center px-3 py-2 rounded-lg transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Tasks
                </x-responsive-nav-link>

                <!-- Analytics -->
                <x-responsive-nav-link :href="route('analytics')" :active="request()->routeIs('analytics')" 
                    class="flex items-center px-3 py-2 rounded-lg transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Analytics
                </x-responsive-nav-link>
            </div>


            <!-- User Profile Section -->
            <div class="flex border-t border-gray-200 dark:border-gray-700 bg-gray-200 dark:bg-gray-800 absolute bottom-0 w-full">
                <div class="p-4">
                    <div class="flex items-center">
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                         alt="{{ Auth::user()->name }}"
                         class="h-8 w-8 rounded-full object-cover flex-shrink-0 border-2 border-gray-300 dark:border-gray-600">
                    <div class="ml-3">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    
                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')" class="block px-3 py-2 rounded-lg">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="block px-3 py-2 rounded-lg">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
