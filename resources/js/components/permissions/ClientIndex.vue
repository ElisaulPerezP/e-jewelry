<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <section class="container mx-auto p-6 font-mono">
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                        <div>
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                @click="popModal"
                            >
                                Crear un Cliente
                            </button>
                        </div>
                        <div class="w-full overflow-x-auto">
                            <table class="w-full text-xs border-separate">
                                <thead class="text-sm">
                                <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-blue-100 uppercase border-b border-gray-600">
                                    <th class="px-4 py-3">{{ 'id' }}</th>
                                    <th class="px-4 py-3">{{ 'user_id' }}</th>
                                    <th class="px-4 py-3">{{ 'name' }}</th>
                                    <th class="px-4 py-3">{{ 'secret' }}</th>
                                    <th class="px-4 py-3">{{ 'redirect' }}</th>
                                    <th class="px-4 py-3">{{ 'revoked' }}</th>
                                    <th class="px-4 py-3">{{ 'create autorization code' }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                <tr class="text-gray-700" v-for="client in clients" :key="client.id">
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ client.id }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ client.user_id }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ client.name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ client.secret }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ client.redirect }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                    <button
                                        v-if="client.revoked === false"
                                        @click="revoke(client)"
                                        class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                    >
                                       Revoke
                                    </button>
                                    <h1
                                        v-if="client.revoked === true"
                                    >
                                        Revoked
                                    </h1>
                                    </td>
                                    <td
                                        class="px-4 py-3 border">
                                        <button
                                            @click="autorizedCode(client)"
                                            class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                        >
                                            Crear codigo
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button
                            @click="back"
                            class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                        >
                            Atras
                        </button>
                    </div>
                </section>
            </div>
        </div>

        <div v-if="formModal" class="fixed inset-0 flex items-center justify-center">
            <div class="bg-white p-8 rounded shadow-lg">
                <h2 class="text-2xl font-bold mb-4">Crear un nuevo cliente</h2>
                <div class="flex items-center text-sm">
                    <input
                        type="text"
                        v-model="shippableClient.name"
                        placeholder="Nombre"
                        class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2"
                    />
                </div>
                <button
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                    @click="send"
                >
                    Crear
                </button>
                <button
                    @click="closeModal"
                    class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const clients = ref([]);
const formModal = ref(false);
const shippableClient = ref({ name: '', redirect: '' });

onMounted(async () => {
    try {
        const response = await axios.get('/oauth/clients');
        clients.value = response.data;
    } catch (error) {
        console.log(error);
    }
});

const back = () => {
    window.location.href = window.history.back();
};

const popModal = () => {
    formModal.value = true;
};

const closeModal = () => {
    formModal.value = false;
};

const send = async () => {
    try {
        const formData = new FormData();
        formData.append('name', shippableClient.value.name);
        formData.append('redirect', 'http://127.0.0.1:8000/dev/showAuthorizationCode/');

        const response = await axios.post('/oauth/clients', formData);
        if (response.status === 201) {
            closeModal();
            window.location.reload();
        }
    } catch (error) {
        console.error('Error al crear el cliente:', error);
    }
};

const revoke = async (client) => {
    try {

        const response = await axios.delete('/oauth/clients/' + client.id);
        if (response.status === 204) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Error al borrar el cliente:', error);
    }
};

const generarTokenAleatorio = (longitud) => {
    const caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const bytes = new Uint8Array(longitud);

    crypto.getRandomValues(bytes);

    let token = '';
    for (let i = 0; i < longitud; i++) {
        token += caracteres[bytes[i] % caracteres.length];
    }

    return token;
};
const autorizedCode = async (client) => {
    const state = generarTokenAleatorio(40);
    const redirectUrl = '/oauth/authorize';

    const url = new URL(redirectUrl, window.location.origin);
    url.searchParams.append('client_id', client.id);
    url.searchParams.append('redirect_uri', client.redirect);
    url.searchParams.append('response_type', 'code');
    url.searchParams.append('state', state);

    window.location.href = url.href;
};

</script>
