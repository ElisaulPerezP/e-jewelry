<template>

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
                                            <font-awesome-icon icon="fa-regular fa-arrow-alt-circle-right" beat size="2xl"
                                                               style="color: #ebe52d"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="px-4 py-3 text-center text-2xl">
                                            Detalles de la orden de pago:
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white">

                                    <tr class="text-black-700">
                                        <td class="px-4 py-3 border text-justify">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black text-md text-lg text-left">
                                                    Identificador</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black text-left">{{ order.reference }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="py-10 text-center border text-xl">Productos en esta orden
                                        </th>
                                    </tr>
                                    <tr
                                        v-for=" CartItem in order.cart_items">
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <img :src="'/storage/' + CartItem.product_image" alt="name"
                                                     class="w-20 h-auto">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <table class="w-full text-center border-separate">
                                                <thead>
                                                <tr>
                                                    <th class="px-4 py-3 text-center ">Nombre:</th>
                                                    <th class="px-4 py-3 text-center ">Cantidad:</th>
                                                    <th class="px-4 py-3 text-center ">Precio:</th>
                                                    <th class="px-4 py-3 text-center ">Subtotal:</th>
                                                </tr>
                                                </thead>
                                                <tbody class="bg-white">
                                                <tr>
                                                    <td class="px-4 py-3 border">
                                                        <p class="font-semibold text-black text-md text-lg text-left">
                                                            {{ CartItem.product_name }}</p>
                                                    </td>
                                                    <td class="px-4 py-3 border">
                                                        <p class="font-semibold text-black text-md text-lg text-left">
                                                            {{ CartItem.amount }}</p>
                                                    </td>
                                                    <td class="px-4 py-3 border">
                                                        <p class="font-semibold text-black text-md text-lg text-left">
                                                            {{ CartItem.product_price }}</p>
                                                    </td>
                                                    <td class="px-4 py-3 border">
                                                        <p class="font-semibold text-black text-md text-lg text-left">
                                                            {{ CartItem.product_price * CartItem.amount }}</p>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black text-md text-lg text-left">Total:</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black text-left">
                                                    {{ order.currency + ' ' + order.total }}
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black text-md text-lg text-left">Estado</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black text-left">{{
                                                        order.state === 'rejected' ? 'RECHAZADA' : order.state === 'approved' ? 'APROBADA' : 'PENDIENTE'
                                                    }}
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black text-md text-lg text-left">
                                                    Acción</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <button
                                                @click="order.state === 'rejected' ? retryPay(order.id) : seePay() "
                                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                {{
                                                    order.state === 'rejected' ? 'Reintentar el pago' : 'Ver el pago'
                                                }}
                                            </button>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                    <td>
                        <button @click="back"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Atras
                        </button>
                    </td>
                </div>
            </div>
            <div class="m-4">
            </div>
        </div>
    </div>
</template>

<script setup>
import {defineProps, ref, onMounted} from 'vue'
import axios from 'axios'

const order = ref({})
const props = defineProps({
    order_id: {
        type: Number
    }
})

onMounted(() => {
    axios.get('/api/order/' + props.order_id + '/show')
        .then(response => {
            order.value = response.data.data
        })
        .catch(error => {
            console.log(error)
        });
})

const back = () => {
    window.location.href = window.history.back()
}

const retryPay = async (id) => {
    await axios.post('/api/order/' + id + '/retry')
        .then(response => window.location.href = response.data.data.process_url)
}

const seePay = () => {
    window.location.href = order.value.process_url
}

const edit = () => {
    window.location.href = window.history.back()
}

</script>
