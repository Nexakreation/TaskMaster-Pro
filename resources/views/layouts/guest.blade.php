<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }" 
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-r dark:from-slate-900 dark:to-slate-800">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
         
            <div class="mb-6 mt-4 ">
                <h1 class="text-4xl font-black bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600 ">
                    TaskMaster
                </h1>
            </div>

            {{ $slot }}
        </div>
    </body>
</html>
