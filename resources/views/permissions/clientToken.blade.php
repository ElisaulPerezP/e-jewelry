<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="py-12" id="app">
        <client-code :code="{{json_encode($code)}}">
        </client-code>
    </div>

</x-app-layout>
