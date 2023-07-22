<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <section class="container mx-auto p-6 font-mono">
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                        <div>

                                    <input @change="search(query)" type="text" v-model="query" placeholder="Buscar..."
                                           class="bg-white  shadow-sm sm:rounded-lg">

                            <table class="w-full text-xs border-separate">
                                <thead class="text-sm">
                                <tr
                                    class="text-md font-semibold tracking-wide text-left text-gray-900 bg-blue-100 uppercase border-b border-gray-600">
                                    <th class="px-4 py-3">{{ ('Identificador') }}</th>
                                    <th class="px-4 py-3">{{ ('Fecha de creacion') }}</th>
                                    <th class="px-4 py-3">{{ ('Total') }}</th>
                                    <th class="px-4 py-3">{{ ('Estado') }}</th>
                                    <th class="px-4 py-3">{{ ('Detalles') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                <tr class="text-gray-700" v-for="order in orders" :key="order.id">
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ order.reference }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ order.created_at.slice(0, 10) }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-xs">
                                            <p v-if="order.total" class="font-semibold text-black">{{ order.currency + ' $' + formattedPrice(order.total) }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <tr>
                                            <td colspan="2"
                                                v-if="order.state === 'approved'">
                                                <font-awesome-icon icon="fa-regular fa-check-circle" beat size="2xl"
                                                                   style="color: #32b849"/>
                                            </td>
                                            <td colspan="2"
                                                v-if="order.state === 'rejected'">
                                                <font-awesome-icon icon="fa-regular fa-circle-xmark" beat size="2xl"
                                                                   style="color: #cf4a4a"/>
                                            </td>
                                            <td colspan="2"
                                                v-if="order.state === 'pending'">
                                                <font-awesome-icon icon="fa-regular fa-arrow-alt-circle-right" beat
                                                                   size="2xl"
                                                                   style="color: #ebe52d"/>
                                            </td>
                                        </tr>
                                    </td>

                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <button @click="detail(order.id)"
                                                    class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                <slot> ...</slot>
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                      <paginator @data="handleDataPagination" :currentPage="currentPage"
                                 :lastPage="receivedLastPage"></paginator>
                    </div>
                </section>
                <button @click="back"
                        class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Atras
                </button>
            </div>
        </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Modal</h2>
            <p>{{ modalMessage }}</p>
            <button @click="closeModal"
                    class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Cerrar
            </button>
        </div>
    </div>

</template>

<script setup>
import {ref, onMounted} from 'vue'
import axios from 'axios'

const orders = ref([])
const query = ref("")
const open = ref(false)
const showModal = ref(false)
const modalMessage = ref('')
const currentPage = ref(1)
const receivedData = ref("")
const receivedCurrentPage = ref(1)
const receivedFirstPage = ref(1)
const receivedLastPage = ref(1)
const per_page = ref(3)

onMounted(() => {
    axios.get('/api/order/', {
        params: {
            searching: '',
            current_page: currentPage.value,
            per_page: per_page.value,
            flag: 0
        }
    })
        .then(response => {
            orders.value = response.data.data
            receivedCurrentPage.value = response.data.meta.current_page
            receivedLastPage.value = response.data.meta.last_page
        })
        .catch(error => console.log(error))
})

const search = async (query) => {
    axios.get('/api/order/', {
        params: {
            searching: query,
            current_page: currentPage.value,
            per_page: per_page.value,
            flag: 0
        }
    })
        .then(response => {
            orders.value = response.data.data;
            receivedCurrentPage.value = response.data.meta.current_page
            receivedLastPage.value = response.data.meta.last_page
        })
        .catch(error => {
            console.log(error);
        });
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
    axios.get('/api/order/', {
        params: {
            searching: query.value,
            current_page: currentPage.value,
            per_page: per_page.value,
            flag: 0
        }
    })
        .then(response => {
            orders.value = response.data.data;
            receivedCurrentPage.value = response.data.meta.current_page
            receivedLastPage.value = response.data.meta.last_page
        })
        .catch(error => {
            console.log(error);
        });
};


const back = () => {
    window.location.href = window.history.back()
}

const detail = (id) => {
    window.location.href = '/order/' + id + '/show'
}

const closeModal = () => {
    location.reload()
    showModal.value = false
}
const formattedPrice = (price) => {
  const [integerPart, decimalPart] = price.toFixed(2).split('.');
  const formattedIntegerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return `${formattedIntegerPart}.${decimalPart}`;
}
</script>
