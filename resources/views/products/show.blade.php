<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-400 dark:text-gray-200 leading-tight">
            {{ __(trans('general.productDetail')) }}
        </h2>
    </x-slot>

    <div class="py-12" id="app">
        <products-show :product_id="{{ $productId }}" :role="{{ auth()->user()->hasPermissionTo('edit.product') ? 'true' : 'false'}}">
        </products-show>
    </div>

</x-app-layout>
