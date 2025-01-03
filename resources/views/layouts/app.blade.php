<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ 
          darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)
      }" 
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles and Scripts -->
        @if(file_exists(public_path('build/manifest.json')))
            <script type="module" src="{{ asset('build/'.json_decode(file_get_contents(public_path('build/manifest.json')), true)['resources/js/app.js']['file']) }}"></script>
            <link rel="stylesheet" href="{{ asset('build/'.json_decode(file_get_contents(public_path('build/manifest.json')), true)['resources/css/app.css']['file']) }}">
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ navExpanded: localStorage.getItem('nav-expanded') === 'true' }"
             @nav-toggle.window="navExpanded = $event.detail.expanded"
             class="min-h-screen bg-gray-200 dark:bg-gray-900">
            <!-- Navigation always at top -->
            <div class="sticky top-0 z-50">
                @include('layouts.navigation')
            </div>

            <!-- Main Content -->
            <div class="transition-all duration-300 overflow-x-hidden lg:ml-16"
                 :class="{ 'lg:ml-64': navExpanded }">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="py-4 overflow-x-hidden" >
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Dark Mode Script -->
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
    </body>
</html> 