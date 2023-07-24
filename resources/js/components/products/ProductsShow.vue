<template>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <section class="container mx-auto p-6 font-mono">
            <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
              <div class="w-full overflow-x-auto">
                <table class="w-full text-center border-separate">
                  <thead>
                  <tr>
                    <th colspan="2" class="px-4 py-3 text-xl">Detalles de producto:</th>
                  </tr>
                  <tr>
                    <th colspan="2" class="text-md text-lg">{{ product.name }}</th>
                  </tr>
                  <tr>
                    <th colspan="2">
                      <img v-if="product.image" :src="'./../../storage/' + product.image" :alt="product.name"
                           class="max-w-sm">
                    </th>
                  </tr>
                  </thead>
                  <tbody class="bg-white">

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
                        <p v-if="product.price" class="font-semibold text-black text-left">COP
                          ${{ formattedPrice(product.price) }}
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
                        <p class="font-semibold text-black text-md text-lg text-left">Score</p>
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
                        <p class="font-semibold text-black text-left">{{ product.status }}
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
                        <p class="font-semibold text-black text-left">{{ product.barCode }}
                        </p>
                      </div>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>
          <button @click="back"
                  class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            Atras
          </button>
          <a v-if="props.role" :href="'/products/' + product.id + '/edit/'"
             class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            <slot>Editar</slot>
          </a>
        </div>
      </div>
      <div class="m-4">
      </div>
    </div>
  </div>
</template>

<script setup>
import {defineProps, ref, onMounted, onBeforeMount} from 'vue'
import axios from 'axios'

const product = ref({})

const props = defineProps({
  product_id: {
    type: Number,
    required: true
  },

  role: {
    type: Boolean
  }
})

onBeforeMount(() => {
  loadProduct()
})

const loadProduct = async () => {
  let response = await axios.get('/api/products/' + props.product_id)
  product.value = response.data.data
}

const back = () => {
  window.history.back(true)
}

const edit = () => {
  window.location.href = "/products"
}
const formattedPrice = (price) => {
  const [integerPart, decimalPart] = price.toFixed(2).split('.');
  const formattedIntegerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return `${formattedIntegerPart}.${decimalPart}`;
};
</script>
