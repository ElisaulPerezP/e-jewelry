<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
        <div class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ trans('general.dashboard') }}
        </div>
        <div class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{ route('cart', Auth::user()) }}" class="ml-4 font-semi-bold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">CARRITO</a>
        </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="app" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <product-card></product-card>
            </div>
        </div>
    </div>
</x-app-layout>
