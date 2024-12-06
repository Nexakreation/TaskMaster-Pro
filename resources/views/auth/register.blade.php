<x-guest-layout>
<div class="absolute top-2 right-2 sm:top-4 sm:right-4">
        <button
            @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
            class="p-1.5 sm:p-2 rounded-lg bg-white/10 backdrop-blur-sm border border-black border-opacity-35 shadow-lg hover:bg-white/20 transition-all duration-300"
            x-data
        >
            <!-- Sun icon -->
            <svg
                x-show="darkMode"
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 sm:h-6 sm:w-6 text-yellow-300"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                />
            </svg>
            <!-- Moon icon -->
            <svg
                x-show="!darkMode"
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 sm:h-6 sm:w-6 text-gray-700"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                />
            </svg>
        </button>
    </div>
    <div class="w-full flex flex-col sm:justify-center items-center pt-2 sm:pt-0">
        <div class=" w-full max-w-[90vw] sm:max-w-md px-4 sm:px-8 py-6 sm:py-8 bg-gray-300 dark:bg-gray-800/90 backdrop-blur-[12px] shadow-2xl overflow-hidden rounded-xl sm:rounded-2xl border border-black border-opacity-35 dark:border-purple-900/20">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 text-center bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                    Create Your Account
                </h2>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 text-center mb-4 sm:mb-6">
                    Join TaskMaster to start organizing your tasks effectively
                </p>

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" class="text-sm sm:text-base text-gray-700 dark:text-gray-300" />
                    <x-text-input id="name" 
                                 class="block mt-1 w-full text-sm sm:text-base bg-white/90 dark:bg-gray-700/90 border-black border-opacity-35 dark:border-gray-600 text-gray-900 dark:text-gray-100" 
                                 type="text" 
                                 name="name" 
                                 :value="old('name')" 
                                 required 
                                 autofocus 
                                 autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" class="text-sm sm:text-base text-gray-700 dark:text-gray-300" />
                    <x-text-input id="email" 
                                 class="block mt-1 w-full text-sm sm:text-base bg-white/90 dark:bg-gray-700/90 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100" 
                                 type="email" 
                                 name="email" 
                                 :value="old('email')" 
                                 required 
                                 autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-sm sm:text-base text-gray-700 dark:text-gray-300" />
                    <x-text-input id="password" 
                                 class="block mt-1 w-full text-sm sm:text-base bg-white/90 dark:bg-gray-700/90 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100"
                                 type="password"
                                 name="password"
                                 required 
                                 autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm sm:text-base text-gray-700 dark:text-gray-300" />
                    <x-text-input id="password_confirmation" 
                                 class="block mt-1 w-full text-sm sm:text-base bg-white/90 dark:bg-gray-700/90 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100"
                                 type="password"
                                 name="password_confirmation" 
                                 required 
                                 autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm" />
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 sm:gap-4 mt-6">
                    <a class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:focus:ring-offset-gray-800" 
                       href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-primary-button class="w-full sm:w-auto text-sm bg-purple-600 hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
