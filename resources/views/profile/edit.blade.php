<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black mb-4 sm:mb-6 md:mb-8 dark:text-gray-100 tracking-tight text-center sm:text-left">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
                    {{ __('Your Profile') }}
                </span>
            </h1>

            <div class="space-y-4 sm:space-y-6 md:space-y-8">
                <div class="p-4 sm:p-6 md:p-8 bg-white/80 dark:bg-gray-800/80 shadow-xl rounded-xl sm:rounded-2xl border border-black border-opacity-35 shadow-lg dark:border-purple-900/20 transform hover:scale-[1.01] transition-all duration-300">
                    <div class="max-w-xl mx-auto lg:mx-0">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 sm:p-6 md:p-8 bg-white/80 dark:bg-gray-800/80 shadow-xl rounded-xl sm:rounded-2xl border border-black border-opacity-35 shadow-lg dark:border-purple-900/20 transform hover:scale-[1.01] transition-all duration-300">
                    <div class="max-w-xl mx-auto lg:mx-0">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 sm:p-6 md:p-8 bg-white/80 dark:bg-gray-800/80 shadow-xl rounded-xl sm:rounded-2xl border border-black border-opacity-35 shadow-lg dark:border-purple-900/20 transform hover:scale-[1.01] transition-all duration-300">
                    <div class="max-w-xl mx-auto lg:mx-0">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
