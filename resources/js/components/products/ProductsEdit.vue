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
                    <th colspan="3" class="px-4 py-3 text-4xl align-middle text-3xl">Edicion de un producto</th>
                  </tr>
                  <tr>
                    <th colspan="3" class="text-2xl">
                      Nuevo nombre: <input v-model="product.name"
                                           placeholder="Nuevo nombre"
                                           class="text-2xl font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2">
                    </th>
                  </tr>
                  <tr>
                    <th colspan="2">
                      <img v-if="imageUrl" :src="imageUrl" alt="Uploaded image">
                      <img v-if="!imageUrl && product.image" :src="'/storage/' + product.image" alt="name"
                           class="max-w-xl">
                    </th>
                    <th>
                      <input type="file" accept="image/png, image/bpn, image/jpeg" @change="handleFileUpload">
                    </th>
                  </tr>
                  </thead>
                  <tbody class="bg-white">
                    <tr>
                      <td class="px-4 py-3 border">
                          <p class="font-semibold text-black text-md text-lg text-left">
                            Descripcion</p>
                      </td>
                      <td class="px-4 py-3 border">
                          <p class="font-semibold text-black text-left">{{
                              product.description
                            }}</p>
                      </td>
                      <td class="px-4 py-3 border items-center">
                                                    <textarea v-model="product.description"
                                                              placeholder="Nueva descripcion"
                                                              class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2"
                                                              style="font-size: 25px"></textarea>
                      </td>
                    </tr>
                    <tr>
                      <td class="px-4 py-3 border">
                          <p class="font-semibold text-black text-md text-lg text-left">
                            Precio</p>
                      </td>
                      <td class="px-4 py-3 border">
                          <p v-if="product.price" class="font-semibold text-black text-left">COP
                            ${{ formattedPrice(product.price) }}
                          </p>
                      </td>
                      <td class="px-4 py-3 border">
                          <input type="number" v-model="product.price"
                                 placeholder="Nuevo precio"
                                 class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2"
                                 style="font-size: 25px">
                      </td>
                    </tr>
                    <tr>
                      <td class="px-4 py-3 border">
                          <p class="font-semibold text-black text-md text-lg text-left">
                            Stock</p>
                      </td>
                      <td class="px-4 py-3 border">
                          <p class="font-semibold text-black text-left">{{ product.stock }}
                          </p>
                      </td>
                      <td class="px-4 py-3 border">
                          <input type="number" v-model="product.stock"
                                 placeholder="Nuevo stock"
                                 class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2"
                                 style="font-size: 25px">
                      </td>
                    </tr>
                    <tr>
                      <td class="px-4 py-3 border">
                          <p class="font-semibold text-black text-md text-lg text-left">
                            Enabling</p>
                      </td>
                      <td class="px-4 py-3 border">
                          <p class="font-semibold text-black text-left">{{ product.status ? "ACTIVO" : "INACTIVO" }}
                          </p>
                      </td>
                      <td class="px-4 py-3 border items-center">
                        <button @click="changeStatus"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                          {{ product.status ? "DESACTIVAR" : "ACTIVAR" }}
                        </button>
                      </td>
                    </tr>

                  </tbody>
                </table>
              </div>
            </div>
          </section>

          <table class="w-full">
            <th colspan="5">
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
            </th>
          </table>
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


const product = ref({})
const imageUrl = ref(null)

const props = defineProps({
  product_id: {
    type: Number,
    required: true
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

function handleFileUpload(event) {
  const file = event.target.files[0]
  const reader = new FileReader()
  reader.onload = () => {
    imageUrl.value = reader.result
  }
  product.value.newImage = file
  reader.readAsDataURL(file)
}

const handleClick = () => {
  delete product.value.image
  product.value.image = product.value.newImage
  delete product.value.newImage
  const formData = new FormData()
  formData.append('_method', 'PUT')
  formData.append('name', product.value.name)
  formData.append('description', product.value.description)
  formData.append('price', product.value.price)
  formData.append('stock', product.value.stock)
  formData.append('status', product.value.status)
  formData.append('barCode', product.value.barCode)
  if (product.value.image) {
    formData.append('image', product.value.image)
  }
  console.log('hola')
  console.log(formData)
  console.log('holi')
  axios.post('/api/products/' + props.product_id, formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
      .then(response => {
        product.value = response.data.data;
        window.location.href = "/products"
      })
      .catch(error => {
        console.log(error)
        window.alert(error.response.data.message)
      })
}
const changeStatus = () => {
  product.value.status = !product.value.status;
  axios.put('/api/products/' + product.value.id + '/changeStatus/')
}
const back = () => {
  window.history.back()
}
const formattedPrice = (price) => {
  const [integerPart, decimalPart] = price.toFixed(2).split('.');
  const formattedIntegerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return `${formattedIntegerPart}.${decimalPart}`;
};

</script>
