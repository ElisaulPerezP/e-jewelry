<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div class="font-semibold text-xl text-gray-400 dark:text-gray-200 leading-tight">
                {{ trans('general.dashboard') }}
            </div>
            <div class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <a href="{{ route('cart', Auth::user()) }}"
                   class="ml-4 font-semi-bold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <font-awesome-icon icon="fa-solid fa-cart-shopping" beat size="2xl"
                                           style="color: #9e5ae2"></font-awesome-icon>
                    </div>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div  class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <product-card :user_id="{{auth()->check() ? auth()->user()->id : null}}"></product-card>
            </div>
        </div>
    </div>
</x-app-layout>
