<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <section class="container mx-auto p-6 font-mono">
                        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                            <div class="w-full overflow-x-auto">
                                <form action="{{ route('users.update', $user) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <label class="block m-2 font-medium text-sm text-gray-700"
                                           for="name">{{ trans('boxes.name') }}</label>
                                    <input
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        name="name" type="text" id="name" value="{{ $user->name }}">
                                    <label for="email"
                                           class="block m-2 font-medium text-sm text-gray-700">{{ trans('boxes.email') }}</label>
                                    <input
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        name="email" type="text" class="form-control" id="email"
                                        value="{{ $user->email }}"><br>
                                    <x-primary-button class="m-2">
                                        {{trans('buttons.save')}}
                                    </x-primary-button>
                                </form>
                                <a href="/users">
                                    <x-primary-button class="m-2">
                                        {{trans('buttons.cancel')}}
                                    </x-primary-button>
                                </a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
