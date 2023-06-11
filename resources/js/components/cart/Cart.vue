<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <section class="container mx-auto p-6 font-mono">
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                        <div class="py-4 flex justify-between">
                            <div class="py-4 flex justify-between">
                                <div class="py-4 flex justify-between">
                                <button @click="pay"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{
                                        totalPrice === 0 ? 'selecciona productos para pagar' : 'pagar ahora:' + ' COP $' + totalPrice
                                    }}
                                </button>
                                </div>
                                <div class="py-4 flex justify-between">
                                <button @click="seeOrders"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        Ver ordenes
                                </button>
                                </div>
                            </div>
                        </div>
                        <div class="w-full overflow-x-auto">
                            <table class="w-full text-xs border-separate">
                                <thead class="text-sm">
                                <tr
                                    class="text-md font-semibold tracking-wide text-left text-gray-900 bg-blue-100 uppercase border-b border-gray-600">
                                    <th class="px-4 py-3">{{ ('a pagar') }}</th>
                                    <th class="px-4 py-3">{{ ('imagen') }}</th>
                                    <th class="px-4 py-3">{{ ('nombre') }}</th>
                                    <th class="px-4 py-3">{{ ('cantidad') }}</th>
                                    <th class="px-4 py-3">{{ ('precio') }}</th>
                                    <th class="px-4 py-3">{{ ('detalle') }}</th>
                                    <th class="px-4 py-3">{{ ('eliminar') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                <tr class="text-gray-700" v-for="itemCart in itemsCart" :key="itemCart.id">
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <input
                                                type="checkbox"
                                                v-model="itemCart.state"
                                                true-value="selected"
                                                false-value="in_cart"
                                                @change="changeState(itemCart)" >
                                        </div>
                                    </td>
                                    <div class="max-w-xs">
                                        <img
                                            :src="'/storage/' + itemCart.product_image"
                                            :alt="itemCart.name"
                                        />
                                    </div>

                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ itemCart.product_name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <input type="number" v-model="itemCart.amount"
                                               @change="setAmount(itemCart)"
                                               placeholder="Cantidad"
                                               class="mb-2 mt-2 border border-gray-400 rounded-lg p-2">
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-xs">
                                            <p class="font-semibold text-black">{{ itemCart.product_price }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <a :href="'/products/' + itemCart.product_id"
                                               class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                <slot>...</slot>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <button @click="deleteItemCart(itemCart)"
                                                    class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Borrar
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
                            <div>
                                <table class="w-full text-xs border-separate">
                                    <tr class="text-gray-700">
                                        <td class="px-4 py-3 border">
                                            <div>
                                                <p class="font-semibold text-black">Total
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border text-xl">
                                            <div>
                                                <p>
                                                    {{ totalPrice === 0 ? 'COP $0' : ' COP $' + totalPrice }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <div>
                                                <button @click="pay"
                                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    {{
                                                        totalPrice === 0 ? 'NO HA SELECCIONADO PRODUCTOS' : 'PAGAR AHORA'
                                                    }}
                                                </button>
                                            </div>

                                        </td>
                                    </tr>
                                </table>
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
import {ref, onMounted, computed} from 'vue'
import axios from 'axios'

const itemsCart = ref([])
const query = ref("")
const open = ref(false)
const showModal = ref(false)
const modalMessage = ref('')

const totalPrice = computed(() => {
    let total = 0
        itemsCart.value.map(itemCart => {
            if (itemCart.state === 'selected') {
                total += itemCart.product_price * itemCart.amount
            }
        })
    return total
});

onMounted(() => {
    axios.get('/api/cart/')
        .then(response => itemsCart.value = response.data.data)
        .catch(error => console.log(error))
})

const changeState = itemCart => {
    axios.put('/api/cart/' + itemCart.id + '/changeState', {'state': itemCart.state})
        .catch(error => console.log(error))
}

const setAmount = async itemCart => {
    axios.put('/api/cart/' + itemCart.id + '/setAmount', {'amount': itemCart.amount})
        .then(response => itemCart.value = response.data.data)
        .catch(error => console.log(error))
}
const back = () => {
    window.location.href = window.history.back()
}
const deleteItemCart = async (itemCart) => {
    axios.delete('/api/cart/' + itemCart.id + '/delete')
        .then(() => itemsCart.value.splice(itemsCart.value.indexOf(itemCart), 1))
        .catch(error => console.log(error))
}

const closeModal = () => {
    location.reload()
    showModal.value = false
}

const pay = () => {
    axios.post('/api/order/create/')
       .then(response => window.location.href = response.data.data.process_url)
}

const seeOrders = () => {
    window.location.href = '/order'
}

</script>
