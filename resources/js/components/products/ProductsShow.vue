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
                                    <th class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <tr>
                                                <td
                                                    class="text-md font-semibold tracking-wide text-left text-blue-600 uppercase  border-gray-600">

                                                <tr class="px-4 py-3 ">Detalles de producto: </tr><tr class="text-md text-lg">{{product.name}}</tr>

                                                </td>
                                                <img :src="'../../storage/' + product.image" alt="name" style="width: 25%; max-width: 50%; height: auto;">

                                            </tr>

                                        </div>

                                    </th>
                                    </thead>
                                    <tbody class="bg-white">
                                    <div class="container">
                                        <tr class="text-black-700">
                                            <td class="px-4 py-3 border text-justify">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">ID</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-left">{{ product.id }}</p>
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
                                                    <p class="font-semibold text-black text-left">{{ product.description }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">Precio</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-left">{{ product.price }}
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
                                                    <p class="font-semibold text-black text-left">{{ product.stock }}
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
                                                    <p class="font-semibold text-black text-left">{{ product.score }}
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
                                                    <p class="font-semibold text-black text-left">{{product.status}}
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
                                                    <p class="font-semibold text-black text-left">{{product.barCode}}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </div>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                    <button @click="handleClick" class = "inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Atras</button>
                </div>
            </div>
            <div class="m-4">
            </div>
        </div>
    </div>
</template>

<script setup>
import {defineProps, ref, onMounted, reactive} from 'vue'
import axios from 'axios'


const product = ref({})
const props = defineProps({
    product_id: {
        type: Number
    }
})
onMounted(() => {
    axios.get('/api/products/' + props.product_id)
        .then(response => {
            product.value = response.data.data;
        })
        .catch(error => {
            console.log(error);
        });
})

</script>
