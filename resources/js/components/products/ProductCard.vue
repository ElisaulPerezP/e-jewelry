<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="my-8">
                    <div class="container mx-auto px-6">
                        <input type="text" v-model="query" placeholder="Buscar...">
                        <div
                            class="grid gap-6 grid-cols-3 mt-6"
                        >
                            <div
                                class=" max-w-sm mx-auto rounded-md shadow-md overflow-hidden bg-gray-100"
                                v-for="product in filteredProducts"
                                :key="product.id"
                            >
                                <div
                                    class="max-w-xl flex items-end justify-end h-56 w-full bg-cover"
                                    v-if="product.status"

                                >
                                    <img
                                        :src="'/storage/' + product.image"
                                        :alt="product.name"
                                    />
                                </div>
                                <div class="px-5 py-3"
                                     v-if="product.status"
                                >
                                    <h3 class="text-gray-700 uppercase">
                                        {{ product.name }}
                                    </h3>
                                    <span class="text-gray-500 mt-2">
                                    COP ${{ product.price }} </span
                                    ><br/>
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
    return products.value.filter(product => product.name.toLowerCase().includes(query.value.toLowerCase()) || product.description.toLowerCase().includes(query.value.toLowerCase()))
})

</script>
