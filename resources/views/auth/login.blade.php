<x-guest-layout>
    <div class="absolute top-4 right-4 z-10">
        <button
            @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
            class="p-2 rounded-lg bg-white/10 backdrop-blur-sm border border-black border-opacity-35 shadow-lg hover:bg-white/20 transition-all duration-300"
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

    <div class="min-auto sm:min-h-[93vh]  w-full flex flex-col justify-center items-center px-4 py-20 sm:px-6 lg:px-8 bg-gradient-to-br from-purple-50 to-purple-200 dark:from-gray-900 dark:to-gray-800">
        <div class=" w-full max-w-[90%] sm:max-w-md px-4 sm:px-8 py-6 sm:py-8 bg-gray-300 dark:bg-gray-800/90 backdrop-blur-lg shadow-2xl overflow-hidden rounded-xl sm:rounded-2xl border border-black border-opacity-35 dark:border-purple-900/20">
          
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                    Welcome Back!
                </h1>
                <p class="mt-2 text-sm sm:text-base text-gray-600 dark:text-gray-400">Please sign in to your account</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email Address
                    </label>
                    <div class="mt-1 relative">
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus
                            class="block w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base"
                            placeholder="Enter your email"
                            tabindex="1">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" type="password" name="password" required
                            class="block w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base"
                            placeholder="••••••••"
                            tabindex="2">
                        <button type="button" 
                                onclick="togglePasswordVisibility('password')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                tabindex="3">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 id="password-eye"
                                 class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" 
                                 fill="none" 
                                 viewBox="0 0 24 24" 
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-2 sm:space-y-0">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:ring-purple-500 dark:focus:ring-offset-gray-800"
                            tabindex="4">
                        <span class="ml-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs sm:text-sm text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 transition-colors duration-200"
                            tabindex="6">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 sm:py-3 px-4 border border-transparent rounded-lg sm:rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 transform hover:scale-[1.02]"
                        tabindex="5">
                        Sign in
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center mt-4 sm:mt-6">
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                            class="font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 transition-colors duration-200"
                            tabindex="7">
                            Sign up
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(inputId + '-eye');
            
            if (input.type === 'password') {
                input.type = 'text';
                eye.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                input.type = 'password';
                eye.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</x-guest-layout>
