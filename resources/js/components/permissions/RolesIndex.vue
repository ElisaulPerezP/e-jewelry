<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

    </div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <section class="container mx-auto p-6 font-mono">
                <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                    <div>
                        <button
                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            @click="popModal">Crear un role
                        </button>
                    </div>
                    <div class="py-4 flex justify-between">
                        <div>
                            <input @change="search(query)" type="text" v-model="query" placeholder="Buscar..."
                                   class="bg-white  shadow-sm sm:rounded-lg">
                        </div>
                        <div>
                            <paginator @data="handleDataPagination" :currentPage="currentPage"
                                       :lastPage="receivedLastPage"></paginator>
                        </div>
                    </div>
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-xs border-separate">
                            <thead class="text-sm">
                            <tr
                                class="text-md font-semibold tracking-wide text-left text-gray-900 bg-blue-100 uppercase border-b border-gray-600">
                                <th class="px-4 py-3">{{ ('id') }}</th>
                                <th class="px-4 py-3">{{ ('nombre') }}</th>
                                <th class="px-4 py-3">{{ ('guard') }}</th>
                                <th class="px-4 py-3">{{ ('fecha de creacion') }}</th>
                                <th class="px-4 py-3">{{ ('permisos') }}</th>
                                <th class="px-4 py-3">{{ ('Borrar') }}</th>

                            </tr>
                            </thead>
                            <tbody class="bg-white">
                            <tr class="text-gray-700" v-for="role in roles" :key="role.id">
                                <td class="px-4 py-3 border">
                                    <div class="flex items-center text-sm">
                                        <p class="font-semibold text-black">{{ role.id }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 border">
                                    <div class="flex items-center text-sm">
                                        <p class="font-semibold text-black">{{ role.name }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 border">
                                    <div class="flex items-center text-sm">
                                        <p class="font-semibold text-black">{{ role.guard_name }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 border">
                                    <div class="flex items-center text-sm">
                                        <p class="font-semibold text-black">{{ role.created_at }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 border">
                                    <div class="flex items-center text-sm">
                                        <button
                                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                            @click="redirectPermissionsToRoles(role)">permisos
                                        </button>
                                    </div>
                                </td>
                                <td class="px-4 py-3 border">
                                    <div class="flex items-center text-sm">
                                        <button
                                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                            @click="destroy(role)">borrar
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <button @click="back"
                            class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Atras
                    </button>
                </div>
            </section>
        </div>
    </div>

    <div v-if="formModal" class="fixed inset-0 flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Crear un nuevo role</h2>
            <div class="flex items-center text-sm">
                <input type="text" v-model="shippableRole.name"
                       placeholder="Nombre"
                       class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2">
            </div>
            <div class="flex items-center text-sm">
                <label class="inline-flex items-center">
                    <input type="checkbox" v-model="shippableRole.isWeb" class="form-checkbox">
                    <span class="ml-2">Web</span>
                </label>
                <label class="inline-flex items-center ml-4">
                    <input type="checkbox" v-model="shippableRole.isApi" class="form-checkbox">
                    <span class="ml-2">API</span>
                </label>
            </div>
            <button
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                @click="send"> Crear
            </button>
            <button @click="closeModal"
                    class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Cancelar
            </button>
        </div>
    </div>
</template>

<script setup>

import {ref, onMounted} from 'vue'
import axios from 'axios'

const permissions = ref([])
const roles = ref([])
const query = ref("")
const receivedData = ref("")
const currentPage = ref(1)
const receivedCurrentPage = ref(1)
const receivedFirstPage = ref(1)
const receivedLastPage = ref(1)
const per_page = ref(15)
const formModal = ref(false)
const shippableRole = ref({})


onMounted(() => {
    axios.get('/api/roles/', {
        params: {
            searching: "",
            current_page: currentPage.value,
            per_page: per_page.value,
            flag: 0
        }
    })
        .then(response => {
            roles.value = response.data.data
            receivedCurrentPage.value = response.data.meta.current_page
            receivedLastPage.value = response.data.meta.last_page
        })
        .catch(error => {
            console.log(error);
        });
})

const back = () => {
    window.location.href = window.history.back();
}


const handleDataPagination = (data) => {
    receivedData.value = data;
    if (receivedData.value === 'firts_page') {
        currentPage.value = receivedFirstPage.value
    } else if (receivedData.value === 'back_page' && currentPage.value !== 1) {
        currentPage.value = currentPage.value - 1
    } else if (receivedData.value === 'next_page' && currentPage.value !== receivedLastPage.value) {
        currentPage.value = currentPage.value + 1
    } else if (receivedData.value === 'last_page') {
        currentPage.value = receivedLastPage.value
    } else {
    }
    axios.get('/api/roles/', {
        params: {
            searching: query.value,
            current_page: currentPage.value,
            per_page: per_page.value,
            flag: 0
        }
    })
        .then(response => {
            roles.value = response.data.data
            receivedCurrentPage.value = response.data.meta.current_page
            receivedLastPage.value = response.data.meta.last_page
        })
        .catch(error => {
            console.log(error);
        });
};

const search = async (query) => {
    axios.get('/api/roles/', {
        params: {
            searching: query,
            current_page: currentPage.value,
            per_page: per_page.value,
            flag: 0
        }
    })
        .then(response => {
            roles.value = response.data.data
            receivedCurrentPage.value = response.data.meta.current_page
            receivedLastPage.value = response.data.meta.last_page
        })
        .catch(error => {
            console.log(error);
        });

};
const popModal = async () => {
    formModal.value = true
}

const closeModal = () => {
    formModal.value = false
    shippableRole.value.isWeb = false
    shippableRole.value.isApi = false
    shippableRole.value.name = '';
    formModal.value = false
}
const send = async () => {
    try {
        const formData = new FormData();
        formData.append('name', shippableRole.value.name);
        formData.append('guardApi', shippableRole.value.isApi ?? false);
        formData.append('guardWeb', shippableRole.value.isWeb ?? false);

        const response = await axios.post('/api/roles/store', formData);
        if (response.status === 201) {
            closeModal();
            window.location.reload()
        }
    } catch (error) {
        console.error('Error al crear el role:', error);
    }
};

const destroy = async (role) => {
    try {
        const response = await axios.delete('/api/roles/' + role.id);
        if (response.status === 200) {
            window.location.reload()
        }
    } catch (error) {
        console.error('Error al borrar el role:', error);
    }
};

const redirectPermissionsToRoles = async (role) => {
    window.location.href = '/roles/' + role.id;
};
</script>
