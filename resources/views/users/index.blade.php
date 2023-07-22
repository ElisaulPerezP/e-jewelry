<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-400 dark:text-gray-200 leading-tight">
            {{ __(trans('general.seeUsers')) }}
        </h2>
    </x-slot>
    <users-index>
    </users-index>
</x-app-layout>
