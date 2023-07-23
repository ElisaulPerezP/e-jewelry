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
                                    <tbody class="bg-white">
                                    <div class="container">
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                    <p class="font-semibold text-black text-md text-lg text-left">
                                                        <img v-if="imageUrl" :src="imageUrl" alt="Uploaded image">
                                                    </p>
                                            </td>

                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <input
                                                        type="file"
                                                        accept="image/bmp, image/png, image/jpeg"
                                                        @change="handleFileUpload">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">
                                                        Nombre:</p>
                                                </div>
                                            </td>

                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <input v-model="product.name" placeholder="Nuevo nombre" class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2">

                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">
                                                        Descripcion</p>
                                                </div>
                                            </td>

                                            <td class="px-4 py-3 border items-center">
                                                <div class="flex items-center text-sm">
                                                    <textarea v-model="product.description"
                                                              placeholder="Nueva descripcion"
                                                              class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2"
                                                              style="font-size: 25px"></textarea>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">
                                                        Precio</p>
                                                </div>
                                            </td>

                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <input type="number" v-model="product.price"
                                                           placeholder="Nuevo precio"
                                                           class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2"
                                                           style="font-size: 25px">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">
                                                        Stock</p>
                                                </div>
                                            </td>

                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <input type="number" v-model="product.stock"
                                                           placeholder="Nuevo stock"
                                                           class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2"
                                                           style="font-size: 25px">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">
                                                        Enabling</p>
                                                </div>
                                            </td>

                                            <td class="px-4 py-3 border items-center">
                                                <select v-model="product.status"
                                                        class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2"
                                                        style="font-size: 25px">
                                                    <option disabled value="">Seleccione un elemento</option>
                                                    <option>1</option>
                                                    <option>0</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <p class="font-semibold text-black text-md text-lg text-left">
                                                        Codigo de barras</p>
                                                </div>
                                            </td>

                                            <td class="px-4 py-3 border">
                                                <div class="flex items-center text-sm">
                                                    <input type="number" v-model="product.barCode"
                                                           placeholder="Codigo de barras"
                                                           class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2"
                                                           style="font-size: 25px">
                                                </div>
                                            </td>
                                        </tr>
                                    </div>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                    <table class="w-full">
                        <tr class="flex">
                            <td class="w-1/2 p-2">
                                <button @click="back"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Atras
                                </button>
                            </td>
                            <td class="w-1/2 p-2">
                                <button @click="handleClick"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Guardar
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="m-4">
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue'
import axios from 'axios'

const product = ref({})

const imageUrl = ref(null)


function handleFileUpload(event) {
    const file = event.target.files[0]
    const reader = new FileReader()
    reader.onload = () => {
        imageUrl.value = reader.result
    }
    product.value.image=file
    reader.readAsDataURL(file)
}
const handleClick = () => {
    const formData = new FormData()
    formData.append('name', product.value.name)
    formData.append('description', product.value.description)
    formData.append('price', product.value.price)
    formData.append('stock', product.value.stock)
    formData.append('status', product.value.status)
    formData.append('barCode', product.value.barCode)
    if (product.value.image) {
        formData.append('image', product.value.image)
    }
    axios.post('/api/products', formData)
        .then(response => {
            product.value.value = response.data.data
            window.location.href = "/products"
        })
        .catch(error => {
            console.log(error)
        })
}
const back = () => {
    window.location.href = window.history.back()
}

</script>
