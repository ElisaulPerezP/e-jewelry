<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <section class="container mx-auto p-6 font-mono">
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                        <div class="py-4 flex justify-between">
                            <div>
                                <button @click="newProduct"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{
                                        totalPrice === 0 ? 'selecciona productos para pagar' : 'pagar ahora:' + ' COP $' + totalPrice
                                    }}
                                </button>
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
                                    <th class="px-4 py-3">{{ ('reserva') }}</th>
                                    <th class="px-4 py-3">{{ ('estado') }}</th>
                                    <th class="px-4 py-3">{{ ('detalle') }}</th>
                                    <th class="px-4 py-3">{{ ('eliminar') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                <tr class="text-gray-700" v-for="item in itemsCart" :key="item.id">

                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <input type="checkbox" :id="'checkbox-' + item.id"
                                                   v-model="selectedProducts" :value="item">
                                            <label :for="'checkbox-' + item.id">{{ item.name }} - {{
                                                    item.price
                                                }}</label>

                                        </div>
                                    </td>
                                    <div class="max-w-xs">
                                        <img
                                            :src="'/storage/' + item.product_image"
                                            :alt="item.name"
                                        />
                                    </div>

                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ item.product_name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <input type="number" v-model="item.amount"
                                               @change="updateProductAmount(item)"
                                               placeholder="Cantidad"
                                               class="mb-2 mt-2 border border-gray-400 rounded-lg p-2">
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-xs">
                                            <p class="font-semibold text-black">{{ item.products_price }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <button type="button"
                                                :id="'reservar-' + product.id"
                                                @click="() => updateProductState(product)"
                                                class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                            Botón
                                        </button>
                                    </td>

                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ product.item_state }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <a :href="'/products/show/' + item.product_id"
                                               class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                <slot>...</slot>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <a :href="'/products/show/' + product.id"
                                               class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                <h>Eliminar</h>
                                            </a>

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
                                                <button @click="newProduct"
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
</template>

<script setup>
import {defineProps, ref, onMounted, computed} from 'vue'
import axios from 'axios'

const itemsCart = ref([])
const query = ref("")
const receivedData = ref("")
const selectedProducts = ref([]);
const receivedCurrentPage = ref(1)
const receivedFirstPage = ref(1)
const receivedLastPage = ref(1)

const props = defineProps({
    user_id: {
        type: Number,
        required: true
    }
})

const totalPrice = computed(() => {
    let total = 0;
    for (const product of selectedProducts.value) {
        total += product.products_price * product.amount;
    }
    return total;
});

onMounted(() => {
    axios.get('/api/cart/' + props.user_id)
        .then(response => itemsCart.value = response.data.data)
        .catch(error => console.log(error))
})


const updateProductAmount = async (product) => {
    axios.put('/api/cart/update/' + product.itemCart_id, product)
        .then(response => {
            item.value = response.data.data;
        })
        .catch(error => {
            console.log(error);
        });
}

const updateProductExpireDate = async (item) => {
    axios.put('/api/cart/' + item.itemCart_id + '/update/date', item)
        .then(response => {
            item.value = response.data.data;
        })
        .catch(error => {
            console.log(error);
        });
}

const totalPrice = computed(() => {
    let total = 0;
    for (const product of selectedProducts.value) {
        total += product.products_price * product.amount;
    }
    return total;
});
const back = () => {
    window.location.href = window.history.back();
}

const changeStatus = async (product) => {
    product.status = !product.status
    await axios.put('api/products/changeStatus/' + product.id)
}

</script>
