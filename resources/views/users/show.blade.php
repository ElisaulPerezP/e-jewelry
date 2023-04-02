<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(trans('boxes.users')) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="m-4">
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <section class="container mx-auto p-6 font-mono">
                        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                            <div class="w-full overflow-x-auto">
                                <table class="w-full text-center border-separate">
                                    <thead>
                                    <tr
                                        class="text-md font-semibold tracking-wide text-left text-blue-600 bg-blue-100 uppercase border-b border-gray-600">
                                        <th class="px-4 py-3">{{ trans('boxes.userDetail') }} </th>

                                    </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                    <div class="container">
                                        <tr class="text-black-700">
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">ID</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{ $user->id }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{trans("boxes.name")}}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{ $user->name }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{trans("boxes.email")}}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{ $user->email }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{trans("tables.createDate")}}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{ $user->created_at }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{trans("tables.updateDate")}}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{ $user->updated_at }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{trans("tables.verificationMailDate")}}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{ $user->email_verified_at }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{trans("buttons.status")}}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black">{{ $user->status ? trans("buttons.enableStatus") : trans("buttons.disableStatus") }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                    <section>
                        <a href="/users" >
                            <x-primary-button class="m-2">
                                {{trans('buttons.back')}}
                            </x-primary-button>
                        </a>
                    </section>
                </div>
            </div>
            <div class="m-4">
            </div>
        </div>
    </div>
</x-app-layout>
