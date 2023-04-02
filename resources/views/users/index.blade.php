<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(trans('navigation.users')) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="m-4">
                {{ $users->links() }}
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <section class="container mx-auto p-6 font-mono">
                        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                            <div class="w-full overflow-x-auto">
                                <table class="w-full text-center border-separate">
                                    <thead>
                                    <tr
                                        class="text-md font-semibold tracking-wide text-left text-gray-900 bg-blue-100 uppercase border-b border-gray-600">
                                        <th class="px-4 py-3">Id</th>
                                        <th class="px-4 py-3">{{ trans('auth.name') }}</th>
                                        <th class="px-4 py-3">{{ trans('auth.email') }}</th>
                                        <th class="px-4 py-3">{{ trans('tables.show') }}</th>
                                        <th class="px-4 py-3">{{ trans('tables.update') }}</th>
                                        <th class="px-4 py-3">{{ trans('tables.habilitation') }}</th>

                                    </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                    <div class="container">
                                        @foreach ($users as $user)
                                            <tr class="text-gray-700">
                                                <td class="px-4 py-3 border">
                                                    <div class="flex items-center text-sm">
                                                        <p class="font-semibold text-black">{{ $user->id }}</p>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 border">
                                                    <div class="flex items-center text-sm">
                                                        <p class="font-semibold text-black">{{ $user->name }}</p>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 border">
                                                    <div class="flex items-center text-sm">
                                                        <p class="font-semibold text-black">{{ $user->email }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 border">
                                                    <div class="flex items-center text-sm">
                                                        <x-primary-button
                                                            href="#">
                                                            {{ trans('buttons.show') }}
                                                        </x-primary-button>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 border">
                                                    <div class="flex items-center text-sm">
                                                        <a
                                                            href="{{ route('users.edit', $user) }}">
                                                            <x-primary-button>
                                                                {{ trans('buttons.update') }}
                                                            </x-primary-button>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 border">
                                                    <div class="flex items-center text-sm">
                                                        <form action="{{ route('users.changeStatus', $user) }}"
                                                              method="POST">
                                                            @csrf
                                                            {{ method_field('PUT') }}
                                                            <x-primary-button>
                                                                {{ $user->status ? trans('buttons.disable'): trans('buttons.enable')}}
                                                            </x-primary-button>
                                                        </form>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <div class="m-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
