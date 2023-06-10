<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <section class="container mx-auto p-6 font-mono">
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full text-xs border-separate">
                                <thead class="text-sm">
                                <tr
                                    class="text-md font-semibold tracking-wide text-left text-gray-900 bg-blue-100 uppercase border-b border-gray-600">
                                    <th class="px-4 py-3">{{ ('Identificador') }}</th>
                                    <th class="px-4 py-3">{{ ('Descripcion') }}</th>
                                    <th class="px-4 py-3">{{ ('Fecha de creacion') }}</th>
                                    <th class="px-4 py-3">{{ ('Total') }}</th>
                                    <th class="px-4 py-3">{{ ('Estado') }}</th>
                                    <th class="px-4 py-3">{{ ('Accion') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                <tr class="text-gray-700" v-for="order in orders" :key="order.id">
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ order.payment_reference }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ order.description }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ order.created_at.slice(0, 10) }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-xs">
                                            <p class="font-semibold text-black">{{order.currency + ' ' + order.total }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        {{ order.order_state }}
                                    </td>

                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <button @click="order.order_state === 'reject' ? retryPay(order) : order.order_state === 'processing' ? detail(order.id) : back"
                                                    class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                <slot>{{order.order_state === 'reject' ? 'pagar de nuevo' : 'ver detalles' }}</slot>
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex justify-between">
                            <div>
                            </div>
                        </div>
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
const showModal = ref(false);
const modalMessage = ref('');


onMounted(() => {
    axios.get('/api/order/')
        .then(response => orders.value = response.data.data)
        .catch(error => console.log(error));
})

const back = () => {
    window.location.href = window.history.back();
}

const detail = (id) => {
    window.location.href = '/order/' + id + '/show';
}

const closeModal = () => {
    location.reload()
    showModal.value = false;
}

const retryPay = (order) => {
    window.location.href = order.process_url;
}

</script>
