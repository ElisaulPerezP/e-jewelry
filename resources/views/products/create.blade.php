<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(trans('general.storeProduct')) }}
        </h2>
    </x-slot>

    <div class="py-12" id="app">
        <products-create>
        </products-create>
    </div>

</x-app-layout>
