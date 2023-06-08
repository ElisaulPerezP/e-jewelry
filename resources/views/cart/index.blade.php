<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(trans('navigation.users')) }}
        </h2>
    </x-slot>

    <div class="py-12" id="app">
        <cart-index :user_id="{{$user->id}}">
        </cart-index>
    </div>

</x-app-layout>
