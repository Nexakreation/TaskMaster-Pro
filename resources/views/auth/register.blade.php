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
                                 value="{{ old('name') }}"
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
                                 value="{{ old('email') }}"
                                 required 
                                 autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-sm sm:text-base text-gray-700 dark:text-gray-300" />
                    <div class="relative">
                        <x-text-input id="password" 
                                     class="block mt-1 w-full text-sm sm:text-base bg-white/90 dark:bg-gray-700/90 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100"
                                     type="password"
                                     name="password"
                                     required 
                                     autocomplete="new-password" />
                        <button type="button" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 dark:text-gray-400"
                                onclick="togglePasswordVisibility('password')">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm sm:text-base text-gray-700 dark:text-gray-300" />
                    <div class="relative">
                        <x-text-input id="password_confirmation" 
                                     class="block mt-1 w-full text-sm sm:text-base bg-white/90 dark:bg-gray-700/90 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100"
                                     type="password"
                                     name="password_confirmation" 
                                     required 
                                     autocomplete="new-password" />
                        <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 dark:text-gray-400"
                                onclick="togglePasswordVisibility('password_confirmation')">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm" />
                </div>

                <script>
                    function togglePasswordVisibility(inputId) {
                        const input = document.getElementById(inputId);
                        input.type = input.type === 'password' ? 'text' : 'password';
                    }
                </script>

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

<script>
    document.querySelector('form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        try {
            const form = e.target;
            const formData = new FormData(form);
            
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            // Check if the response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // If not JSON, it's probably an error page
                const text = await response.text();
                console.error('Server returned non-JSON response:', text);
                throw new Error('Server error occurred. Please try again.');
            }

            const data = await response.json();
            
            if (!response.ok) {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.querySelector(`[data-error="${field}"]`);
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                        }
                    });
                    throw new Error(Object.values(data.errors).flat()[0]);
                }
                throw new Error(data.message || 'Registration failed');
            }

            // If successful, redirect
            window.location.href = data.redirect || '/dashboard';
            
        } catch (error) {
            console.error('Registration error:', error);
            // Display error to user
            const errorMessage = document.createElement('div');
            errorMessage.className = 'text-red-500 text-sm mt-2';
            errorMessage.textContent = error.message;
            this.querySelector('button[type="submit"]').before(errorMessage);
        }
    });

    // Preserve console logs
    const originalConsoleLog = console.log;
    const logs = [];
    console.log = function() {
        logs.push(Array.from(arguments));
        originalConsoleLog.apply(console, arguments);
    };

    window.onerror = function(msg, url, lineNo, columnNo, error) {
        console.log('Global error:', {
            message: msg,
            url: url,
            lineNo: lineNo,
            columnNo: columnNo,
            error: error
        });
        return false;
    };

    // Log any validation errors that might be present
    document.addEventListener('DOMContentLoaded', () => {
        const errors = document.querySelectorAll('.text-red-600');
        if (errors.length > 0) {
            console.log('Validation errors present:', 
                Array.from(errors).map(el => el.textContent)
            );
        }

        // Check if we're in an error state from a previous submission
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('error')) {
            console.log('URL contains error parameter:', urlParams.get('error'));
        }
    });

    // Before the page unloads, log all collected logs
    window.addEventListener('beforeunload', function() {
        console.log('All collected logs:', logs);
    });
</script>
