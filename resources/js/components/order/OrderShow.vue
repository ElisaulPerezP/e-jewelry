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
                                    <tr><th class="px-4 py-3">Detalles de la orden de pago: </th></tr>
                                    <tr><th class="text-md text-lg">{{ order.name }}</th></tr>
                                    <tr><th><img :src="'../../storage/' + order.image" alt="name" class="max-w-xl"></th></tr>
                                    </thead>
                                    <tbody class="bg-white">

                                        <tr class="text-black-700">
                                            <td class="px-4 py-3 border text-justify">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">Identificador</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-left">{{ order.id }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">Descripcion</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-left">{{
                                                            order.description
                                                        }}</p>
                                                </div>
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
                                                    <p class="font-semibold text-black text-left">{{ order.currency + ' ' + order.total }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">Stock</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-left">{{ order.stock }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">score</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-left">{{ order.score }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">Enabling</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-left">{{ order.status }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <h1 class="font-semibold text-black text-md text-lg text-left">Codigo de barras</h1>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-left">{{ order.barCode }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                    <td>
                    <button @click="back" class = "inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Atras</button>
                    </td>
                    <td>
                        <a :href="'/products/edit/' + order.id"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <slot>Editar</slot>
                        </a>
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
    axios.get('/api/order/' + props.order_id + '/checkStatus')
        .then(response => {
            order.value = response.data.data;
        })
        .catch(error => {
            console.log(error);
        });
})

const back = () => {
    window.location.href = "/products";
}

const edit = () => {
    window.location.href = "/products";
}

</script>
