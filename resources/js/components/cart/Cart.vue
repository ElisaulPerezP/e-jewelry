<template>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 bg-white border-b border-gray-200">
        <section class="container mx-auto p-6 font-mono">
          <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
            <div>
              <div class="py-4 flex justify-between">

                <button @click="seeOrders"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                  Ver ordenes
                </button>
                <div>
                  <input @change="search(query)" type="text" v-model="query" placeholder="Buscar..."
                         class="bg-white  shadow-sm sm:rounded-lg">
                </div>

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
                  <th class="px-4 py-3">{{ ('detalle') }}</th>
                  <th class="px-4 py-3">{{ ('eliminar') }}</th>
                </tr>
                </thead>
                <tbody class="bg-white">
                <tr class="text-gray-700" v-for="CartItem in cartItems" :key="CartItem.id">
                  <td class="px-4 py-3 border">
                    <div class="flex items-center text-sm">
                      <input
                          type="checkbox"
                          :checked="CartItem==='selected'"
                          v-model="CartItem.state"
                          true-value="selected"
                          false-value="in_cart"
                          @change="changeState(CartItem)">
                    </div>
                  </td>
                  <div class="max-w-xs">
                    <img
                        :src="'/storage/' + CartItem.product_image"
                        :alt="CartItem.name"
                    />
                  </div>

                  <td class="px-4 py-3 border">
                    <div class="flex items-center text-sm">
                      <p class="font-semibold text-black">{{ CartItem.product_name }}</p>
                    </div>
                  </td>
                  <td class="px-4 py-3 border">
                    <input type="number" v-model="CartItem.amount"
                           @change="setAmount(CartItem)"
                           placeholder="Cantidad"
                           class="mb-2 mt-2 border border-gray-400 rounded-lg p-2">
                  </td>
                  <td class="px-4 py-3 border">
                    <div class="flex items-center text-xs">
                      <p class="font-semibold text-black">COP ${{ formattedPrice(CartItem.product_price) }}
                      </p>
                    </div>
                  </td>
                  <td class="px-4 py-3 border">
                    <div class="flex items-center text-sm">
                      <a :href="'/products/' + CartItem.product_id"
                         class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <slot>Ver</slot>
                      </a>
                    </div>
                  </td>
                  <td class="px-4 py-3 border">
                    <div class="flex items-center text-sm">
                      <button @click="deleteItemCart(CartItem)"
                              class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Borrar
                      </button>

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
                    <td class="px-4 py-3 border" v-if="totalPrice!== 0">
                      <div>
                        <p class="font-semibold text-black">Total
                        </p>
                      </div>
                    </td>
                    <td class="px-4 py-3 border text-xl">
                      <div>
                        <p>
                          COP ${{ formattedPrice(totalPrice) }}
                        </p>
                      </div>
                    </td>
                    <td class="px-4 py-3 border">
                      <div>
                        <button @click="pay"
                                v-if="totalPrice !== 0"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                          {{
                            totalPrice.total === 0 ? 'NO HA SELECCIONADO PRODUCTOS' : 'PAGAR AHORA'
                          }}
                        </button>
                      </div>

                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div>

            <paginator @data="handleDataPagination" :currentPage="currentPage"
                       :lastPage="receivedLastPage"></paginator>
          </div>
        </section>
        <button @click="back"
                class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
          Atras
        </button>
      </div>
    </div>
  </div>

  <div v-if="showModal" class="fixed inset-0 flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-lg">
      <h2 class="text-2xl font-bold mb-4">{{ modalTitle }}</h2>
      <p>{{ modalMessage }}</p>
      <button @click="closeModal"
              class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        Cerrar
      </button>
    </div>
  </div>

</template>

<script setup>
import {ref, onMounted,} from 'vue'
import axios from 'axios'

const cartItems = ref([])
const query = ref("")
const open = ref(false)
const showModal = ref(false)
const modalMessage = ref('')
const modalTitle = ref('')
const receivedData = ref("")
const currentPage = ref(1)
const receivedCurrentPage = ref(1)
const receivedFirstPage = ref(1)
const receivedLastPage = ref(1)
const per_page = ref(3)
const totalPrice = ref(0)

onMounted(() => {
  axios.get('/api/cart/', {params: {searching: '', current_page: currentPage.value, per_page: per_page.value, flag: 1}})
      .then(response => {
        cartItems.value = response.data.data
        receivedCurrentPage.value = response.data.meta.current_page
        receivedLastPage.value = response.data.meta.last_page
      })
      .catch(error => console.log(error))
  axios.get('/api/cart/total')
      .then(response => {
        totalPrice.value = response.data.total;
      })
      .catch(error => {
        console.log(error);
      });
})


const search = (query) => {
  axios.get('/api/cart/', {
    params: {
      searching: query,
      current_page: currentPage.value,
      per_page: per_page.value,
      flag: 1
    }
  })
      .then(response => {
        cartItems.value = response.data.data;
        receivedCurrentPage.value = response.data.meta.current_page
        receivedLastPage.value = response.data.meta.last_page
      })
      .catch(error => {
        console.log(error);
      });
}

const handleDataPagination = (data) => {
  receivedData.value = data;
  if (receivedData.value === 'firts_page') {
    currentPage.value = receivedFirstPage.value
  } else if (receivedData.value === 'back_page' && currentPage.value !== 1) {
    currentPage.value = currentPage.value - 1
  } else if (receivedData.value === 'next_page' && currentPage.value !== receivedLastPage.value) {
    currentPage.value = currentPage.value + 1
  } else if (receivedData.value === 'last_page') {
    currentPage.value = receivedLastPage.value
  } else {
  }
  axios.get('/api/cart/', {
    params: {
      searching: query.value,
      current_page: currentPage.value,
      per_page: per_page.value,
      flag: 1
    }
  })
      .then(response => {
        cartItems.value = response.data.data;
        receivedCurrentPage.value = response.data.meta.current_page
        receivedLastPage.value = response.data.meta.last_page
      })
      .catch(error => {
        console.log(error);
      });
  axios.get('/api/cart/total')
      .then(response => {
        totalPrice.value = response.data;
      })
      .catch(error => {
        console.log(error);
      });
};

const changeState = async CartItem => {
  axios.put('/api/cart/' + CartItem.id + '/changeState', {'state': CartItem.state})
      .catch(error => {
        console.log(error)
        popModal('Ups, tenemos un problema', error.response.data.error)
      })

  axios.get('/api/cart/total')
      .then(response => {
        totalPrice.value = response.data.total;
      })
      .catch(error => {
        console.log(error);
      });
}

const setAmount = async CartItem => {
  axios.put('/api/cart/' + CartItem.id + '/setAmount', {'amount': CartItem.amount})
      .then(response => CartItem.value = response.data.data)
      .catch(error => {
        console.log(error)
        popModal('Ups, tenemos un problema', error.response.data.error)
      })
  axios.get('/api/cart/total')
      .then(response => {
        totalPrice.value = response.data.total;
      })
      .catch(error => {
        console.log(error);
      });
}
const back = () => {
  window.history.back(true)
}
const deleteItemCart = async (CartItem) => {
  axios.delete('/api/cart/' + CartItem.id + '/delete')
      .then(() => cartItems.value.splice(cartItems.value.indexOf(CartItem), 1))
      .catch(error => console.log(error))
  location.reload()
}

const popModal = (title, message) => {
  showModal.value = true
  modalMessage.value = message
  modalTitle.value = title
}
const closeModal = () => {
  location.reload()
  showModal.value = false
}

const pay = () => {
  axios.post('/api/order/create/')
      .then(response => window.location.href = response.data.data.process_url)
}

const seeOrders = () => {
  window.location.href = '/order'
}

const formattedPrice = (price) => {
  const [integerPart, decimalPart] = price.toFixed(2).split('.');
  const formattedIntegerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return `${formattedIntegerPart}.${decimalPart}`
}
</script>
