<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12" id="app">
        <assign-permissions-to-resource resource_type="{{"role"}}" :id="{{$id}}">
        </assign-permissions-to-resource >
    </div>

</x-app-layout>
