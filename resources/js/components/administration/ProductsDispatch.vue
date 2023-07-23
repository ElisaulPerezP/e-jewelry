<template>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <button @click="downloadProducts"
            class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
      Descargar lista de productos para despacho
    </button>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 bg-white border-b border-gray-200">
        <section class="container mx-auto p-6 font-mono">
          <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
            <div>
              <div class="py-4 flex justify-between">
                <div>
                  <input @change="search(query)" type="text" v-model="query" placeholder="Buscar..."
                         class="bg-white  shadow-sm sm:rounded-lg">
                </div>
                <div>

                  <paginator @data="handleDataPagination" :currentPage="currentPage"
                             :lastPage="receivedLastPage"></paginator>
                </div>
              </div>
            </div>
            <div class="w-full overflow-x-auto">
              <table class="w-full text-xs border-separate">
                <thead class="text-sm">
                <tr
                    class="text-md font-semibold tracking-wide text-left text-gray-900 bg-blue-100 uppercase border-b border-gray-600">
                  <th class="px-4 py-3">{{ ('imagen') }}</th>
                  <th class="px-4 py-3">{{ ('nombre') }}</th>
                  <th class="px-4 py-3">{{ ('cantidad') }}</th>
                  <th class="px-4 py-3">{{ ('usuario') }}</th>
                  <th class="px-4 py-3">{{ ('estado') }}</th>
                </tr>
                </thead>
                <tbody class="bg-white">
                <tr class="text-gray-700" v-for="CartItem in CartItems" :key="CartItem.id">
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
                    <p class="font-semibold text-black">{{ CartItem.amount }}</p>
                  </td>
                  <td class="px-4 py-3 border">
                    <tr>
                      <button @click="seeUser(CartItem)"
                              class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Ver usuario
                      </button>
                    </tr>
                  </td>
                  <td class="px-4 py-3 border">
                    <div class="flex items-center text-sm">
                      <button @click="changeState(CartItem)"
                              class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Marcar como enviado
                      </button>
                    </div>
                  </td>

                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>
        <button @click="back"
                class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
          Atras
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import axios from 'axios'

const CartItems = ref([])
const query = ref("")
const open = ref(false)
const receivedData = ref("")
const currentPage = ref(1)
const receivedCurrentPage = ref(1)
const receivedFirstPage = ref(1)
const receivedLastPage = ref(1)
const per_page = ref(3)

onMounted(() => {
  axios.get('/api/sales/cart', {
    params: {
      searching: '',
      current_page: currentPage.value,
      per_page: per_page.value,
      flag: '1'
    }
  })
      .then(response => {
        CartItems.value = response.data.data
        receivedCurrentPage.value = response.data.meta.current_page
        receivedLastPage.value = response.data.meta.last_page
      })
      .catch(error => console.log(error))
})

const search = async (query) => {
  axios.get('/api/sales/cart', {
    params: {
      searching: query,
      current_page: currentPage.value,
      per_page: per_page.value,
      flag: 1
    }
  })
      .then(response => {
        CartItems.value = response.data.data;
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
  axios.get('/api/sales/cart', {
    params: {
      searching: query.value,
      current_page: currentPage.value,
      per_page: per_page.value,
      flag: 1
    }
  })
      .then(response => {
        CartItems.value = response.data.data;
        receivedCurrentPage.value = response.data.meta.current_page
        receivedLastPage.value = response.data.meta.last_page
      })
      .catch(error => {
        console.log(error);
      });
};

const changeState = async CartItem => {
  axios.put('/api/cart/' + CartItem.id + '/changeState', {'state': 'dispatched'})
      .catch(error => {
        console.log(error)
      })
  location.reload(true);
}

const back = () => {
  window.history.back(true)
}
const seeUser = (CartItem) => {
  window.location.href = '/users/show/' + CartItem.user_id
}

const downloadProducts = async () => {
  try {
    const response = await axios.get('/api/reports/dispatch');
  } catch (error) {
    console.error('Error, no se puede encolar la tarea', error);
  }
};
</script>
