<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-400 dark:text-gray-200 leading-tight">
            {{ __(trans('general.cart'))}}
        </h2>
    </x-slot>

    <div class="py-12" id="app">
        <cart-index>
        </cart-index>
    </div>

</x-app-layout>
