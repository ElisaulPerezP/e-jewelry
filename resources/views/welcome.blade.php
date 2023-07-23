<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>E-JEWELRY</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Script -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body >
        <h1 class="bg-gray-800 dark:bg-gray-800 overflow-hidden shadow-sm  text-5xl text-center">E-JEWELRY</h1>
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center
        bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semi-bold text-gray-800 hover:text-gray-900
                        dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm
                        focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semi-bold text-gray-800 hover:text-gray-900
                        dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm
                        focus:outline-red-500">{{ trans('general.logIn') }}</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semi-bold text-gray-800
                             hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline
                             focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                {{ trans('general.register') }}</a>
                        @endif
                    @endauth
                </div>
            @endif
                <div class="py-20">
                <div id="app" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <product-card :user_id="{{auth()->check() ? auth()->user()->id : 0}}"></product-card>

                </div>
                </div>
        </div>

    </body>
</html>
