<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <section class="container mx-auto p-6 font-mono">
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full text-center border-separate">
                                <thead>
                                <tr
                                    class="text-md font-semibold tracking-wide text-left text-gray-900 bg-blue-100 uppercase border-b border-gray-600">
                                    <th class="px-4 py-3">Id</th>
                                    <th class="px-4 py-3">{{ ('name') }}</th>
                                    <th class="px-4 py-3">{{ ('description') }}</th>
                                    <th class="px-4 py-3">{{ ('price') }}</th>
                                    <th class="px-4 py-3">{{ ('stock') }}</th>
                                    <th class="px-4 py-3">{{ ('score') }}</th>
                                    <th class="px-4 py-3">{{ ('enabling') }}</th>
                                    <th class="px-4 py-3">{{ ('barCode') }}</th>
                                    <th class="px-4 py-3">{{ ('image') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr class="text-gray-700" v-for="product in products" :key="product.id">
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black">{{ product.id }}</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black">{{ product.name }}</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black">{{ product.description }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black">{{ product.price }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black">{{ product.stock }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black">{{ product.score }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black">{{ product.status }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <p class="font-semibold text-black">{{ product.barCode }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border">
                                            <div class="flex items-center text-sm">
                                                <img :src="'storage/' + product.image" alt="name">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import axios from 'axios'


const products = ref([])

onMounted(() => {
    axios.get('/api/products')
        .then(response => {
            products.value = response.data.data;
        })
        .catch(error => {
            console.log(error);
        });
})
</script>
