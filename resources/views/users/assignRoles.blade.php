<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12" id="app">
        <assign-roles-to-user resource_type="{{"user"}}" :id="{{$id}}">
        </assign-roles-to-user >
    </div>

</x-app-layout>
