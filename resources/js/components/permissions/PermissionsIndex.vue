<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        </div><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <section class="container mx-auto p-6 font-mono">
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
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
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                <tr class="text-gray-700" v-for="permission in permissions" :key="permission.id">
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ permission.id }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ permission.name }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ permission.guard_name }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ permission.created_at }}
                                            </p>
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

</template>

<script setup>
import {ref, onMounted} from 'vue'
import axios from 'axios'

const permissions = ref([])
const query = ref("")
const receivedData = ref("")
const currentPage = ref(1)
const receivedCurrentPage = ref(1)
const receivedFirstPage = ref(1)
const receivedLastPage = ref(1)
const per_page = ref(6)


onMounted(() => {
    axios.get('/api/permissions/', {
        params: {
            searching: "",
            current_page: currentPage.value,
            per_page: per_page.value,
            flag: 0
        }
    })
        .then(response => {
            permissions.value = response.data.data
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
    axios.get('/api/permissions/', {
        params: {
            searching: query.value,
            current_page: currentPage.value,
            per_page: per_page.value,
            flag: 0
        }
    })
        .then(response => {
            permissions.value = response.data.data
            receivedCurrentPage.value = response.data.meta.current_page
            receivedLastPage.value = response.data.meta.last_page
        })
        .catch(error => {
            console.log(error);
        });
};

const search = async (query) => {
    axios.get('/api/permissions/', {
        params: {
            searching: query,
            current_page: currentPage.value,
            per_page: per_page.value,
            flag: 0
        }
    })
        .then(response => {
            permissions.value = response.data.data
            receivedCurrentPage.value = response.data.meta.current_page
            receivedLastPage.value = response.data.meta.last_page
        })
        .catch(error => {
            console.log(error);
        });

};
</script>
