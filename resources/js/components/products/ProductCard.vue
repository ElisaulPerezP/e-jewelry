<template>
    <div class="max-w-7xl sm:px-6 lg:px-8">
        <div class="py-4">
            <input type="text" v-model="query" placeholder="Buscar..."
                   class="bg-white  shadow-sm sm:rounded-lg">
        </div>
        <div class="bg-white  shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="my-8">
                    <div class="container mx-auto px-6">
                        <div
                            class="grid gap-6 grid-cols-3 mt-6"
                        >
                            <div
                                class="mx-2 my-2 px-4 py-6 max-w-sm rounded-md shadow-md bg-gray-100"
                                v-for="product in filteredProducts"
                                :key="product.id"
                            >
                                <div class="max-w-sm">
                                    <img
                                        :src="'/storage/' + product.image"
                                        :alt="product.name"
                                    />
                                </div>
                                <span class="text-gray-600 mt-2 text-xl uppercase">{{ product.name }}</span>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 mt-2">COP $ {{ product.price }}</span>
                                    <button @click="sendToCart(product)"
                                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        CARRITO
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import {ref, onMounted, computed} from 'vue'
import axios from 'axios'

const products = ref([])
const query = ref("")

onMounted(() => {
    axios.get('/api/products', {params: {searching: "hola"}})
        .then(response => {
            products.value = response.data.data;
        })
        .catch(error => {
            console.log(error);
        });
})

const filteredProducts = computed(() => {
    return products.value.filter(product => (
            product.name.toLowerCase().includes(query.value.toLowerCase()) ||
            product.description.toLowerCase().includes(query.value.toLowerCase())) &&
        product.status === 1)
})

const sendToCart = async (product) => {
    await axios.put('/api/cart/addProduct/' + product.id)
}
</script>
