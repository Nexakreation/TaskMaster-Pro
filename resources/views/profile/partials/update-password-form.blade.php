<section class="p-4 sm:p-6 md:p-8 from-indigo-50 to-purple-50 dark:bg-transparent rounded-xl sm:rounded-2xl">
    <header>
        <h2 class="text-xl sm:text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-400 dark:to-pink-400 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            {{ __('Update Password') }}
        </h2>

        <p class="text-xs sm:text-sm bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-400 dark:to-pink-400 mt-2">
            {{ __('Enhance your account security with a strong, unique password combination.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4 sm:mt-6 md:mt-8 space-y-4 sm:space-y-6 dark:text-gray-100">
        @csrf
        @method('put')

        <div class="transform transition-all duration-200 hover:scale-[1.01]">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="font-medium text-gray-700 dark:text-gray-300 text-sm sm:text-base" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" 
                class="mt-1 block w-full rounded-lg sm:rounded-xl border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base p-2 sm:p-3" 
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs sm:text-sm" />
        </div>

        <div class="transform transition-all duration-200 hover:scale-[1.01]">
            <x-input-label for="update_password_password" :value="__('New Password')" class="font-medium text-gray-700 dark:text-gray-300 text-sm sm:text-base" />
            <x-text-input id="update_password_password" name="password" type="password" 
                class="mt-1 block w-full rounded-lg sm:rounded-xl border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base p-2 sm:p-3" 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs sm:text-sm" />
        </div>

        <div class="transform transition-all duration-200 hover:scale-[1.01]">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="font-medium text-gray-700 dark:text-gray-300 text-sm sm:text-base" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                class="mt-1 block w-full rounded-lg sm:rounded-xl border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base p-2 sm:p-3" 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-xs sm:text-sm" />
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 pt-2">
            <x-primary-button class="w-full sm:w-auto bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-purple-500/30 text-sm sm:text-base py-2 px-4 sm:py-3 sm:px-6">
                <span class="flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    {{ __('Save') }}
                </span>
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition.duration.500ms
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs sm:text-sm text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full animate-pulse"
                >{{ __('Password Updated Successfully!') }}</p>
            @endif
        </div>
    </form>
</section>
