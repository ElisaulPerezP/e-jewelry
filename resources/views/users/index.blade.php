<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-400 dark:text-gray-200 leading-tight">
            {{ __(trans('general.seeUsers')) }}
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
                                <table class=" text-center ">
                                    <thead>
                                    <tr
                                        class="text-md font-semibold tracking-wide text-left text-gray-900 bg-blue-100 uppercase border-b border-gray-600">
                                        <th class="px-4 py-3">Id</th>
                                        <th class="px-4 py-3">{{ trans('auth.name') }}</th>
                                        <th class="px-4 py-3">{{ trans('auth.email') }}</th>
                                        <th class="px-4 py-3">{{ trans('tables.show') }}</th>
                                        <th class="px-4 py-3">{{ trans('tables.update') }}</th>
                                        <th class="px-4 py-3">{{ trans('tables.enabling') }}</th>
                                        @can('edit.users.permissions')
                                            <th class="px-4 py-3">{{ trans('tables.permissions') }}</th>
                                            <th class="px-4 py-3">{{ trans('tables.roles') }}</th>

                                        @endcan

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
                                                        <a
                                                            href="{{ route('users.show', $user) }}">
                                                            <x-primary-button>
                                                                {{ trans('buttons.show') }}
                                                            </x-primary-button>
                                                        </a>
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
                                                        @if($user->email != 'admin@jewelry.com')
                                                            <form action="{{ route('users.changeStatus', $user) }}"
                                                                  method="POST">
                                                                @csrf
                                                                {{ method_field('PUT') }}
                                                                <x-primary-button>
                                                                    {{ $user->status ? trans('buttons.disable'): trans('buttons.enable')}}
                                                                </x-primary-button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                                @can('edit.users.permissions')
                                                    <td class="px-4 py-3 border">
                                                        <div class="flex items-center text-sm">
                                                            <form action="{{ route('users.assignPermissions', $user) }}"
                                                                  method="POST">
                                                                @csrf
                                                                {{ method_field('PUT') }}
                                                                <x-primary-button>
                                                                    {{trans('buttons.permissions')}}
                                                                </x-primary-button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3 border">
                                                        <div class="flex items-center text-sm">
                                                            <form action="{{ route('users.assignRoles', $user) }}"
                                                                  method="POST">
                                                                @csrf
                                                                {{ method_field('PUT') }}
                                                                <x-primary-button>
                                                                    {{trans('buttons.roles')}}
                                                                </x-primary-button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                @endcan
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
            <a
                href="{{ url()->previous() }}" class="absolute mb-4 mr-10">
                <x-primary-button>
                    {{ trans('buttons.back') }}
                </x-primary-button>
            </a>
        </div>
    </div>
</x-app-layout>


<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Are you sure you want to delete your account?') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
        </p>

        <div class="mt-6">
            <x-input-label for="password" value="{{ __('Password') }}" class="sr-only"/>

            <x-text-input
                id="password"
                name="password"
                type="password"
                class="mt-1 block w-3/4"
                placeholder="{{ __('Password') }}"
            />

            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2"/>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-3">
                {{ __('Delete Account') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
