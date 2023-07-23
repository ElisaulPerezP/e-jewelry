<template>
  <div class="max-w-7xl sm:px-6 lg:px-8">
    <div class="py-4 flex justify-between">
      <div>
        <input @change="search(query)" type="text" v-model="query" placeholder="Buscar..."
               class="bg-white  shadow-sm sm:rounded-lg">
      </div>
      <button @click="props.user_id === 0 ? register() : goToCart()"
              class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
        <font-awesome-icon icon="fa-solid fa-cart-shopping" size="2xl"
                           style="color: #9e5ae2"/>
        {{ itemsCart.length }}
      </button>
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
                  <span class="text-gray-500 mt-2">COP ${{ formattedPrice(product.price) }}</span>
                  <button
                      @click="props.user_id === 0 ? register() : (!product.inCart ? sendToCart(product) : goToCart())"
                      class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ !product.inCart ? "COLOCAR EN EL CARRITO" : "VER EN EL CARRITO" }}
                  </button>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <paginator @data="handleDataPagination" :currentPage="currentPage" :lastPage="receivedLastPage"></paginator>
    </div>
  </div>
</template>

<script setup>

import {defineProps, ref, onMounted, computed, reactive} from 'vue'
import axios from 'axios'

const products = ref([])
const query = ref("")
const receivedData = ref("")
const currentPage = ref(1)
const receivedCurrentPage = ref(1)
const receivedFirstPage = ref(1)
const receivedLastPage = ref(1)
const itemsCart = ref([])
const per_page = ref(9)

const props = defineProps({
  user_id: {
    type: Number,
    required: false
  }
})


onMounted(() => {
  axios.get('/api/products', {
    params: {
      searching: "",
      current_page: currentPage.value,
      per_page: per_page.value,
      flag: 1
    }
  })
      .then(response => {
        products.value = response.data.data;
        receivedCurrentPage.value = response.data.meta.current_page
        receivedLastPage.value = response.data.meta.last_page
      })
      .catch(error => {
        console.log(error);
      });

  if (props.user_id !== 0) {
    axios.get('/api/cart/', {params: {searching: "", current_page: currentPage.value, per_page: 1000, flag: 1}})
        .then(response => itemsCart.value = response.data.data)
        .catch(error => console.log(error))
  }

})


const search = async (query) => {
  axios.get('/api/products', {
    params: {
      searching: query,
      current_page: currentPage.value,
      per_page: per_page.value,
      flag: 1
    }
  })
      .then(response => {
        products.value = response.data.data;
        receivedCurrentPage.value = response.data.meta.current_page
        receivedLastPage.value = response.data.meta.last_page
      })
      .catch(error => {
        console.log(error);
      });


}

const inCartProducts = computed(() => {
  return products.value.map(product => {
    const inCart = itemsCart.value.some(item => item.product_id === product.id)
    return {...product, inCart: inCart}
  });
});


const filteredProducts = computed(() => {
  return inCartProducts.value.filter(product => (
          product.name.toLowerCase().includes(query.value.toLowerCase()) ||
          product.description.toLowerCase().includes(query.value.toLowerCase())) &&
      product.status === 1)
})

const sendToCart = async (product) => {
  await axios.post('/api/cart/' + product.id + '/store')
      .catch(error => console.log(error))
  location.reload()
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
  axios.get('/api/products', {
    params: {
      searching: query.value,
      current_page: currentPage.value,
      per_page: per_page.value,
      flag: 1
    }
  })
      .then(response => {
        products.value = response.data.data;
        receivedCurrentPage.value = response.data.meta.current_page
        receivedLastPage.value = response.data.meta.last_page
      })
      .catch(error => {
        console.log(error);
      });

};

const goToCart = () => {
  window.location.href = "/cart/" + props.user_id;
}
const back = () => {
  window.history.back(true)
}
const register = () => {
  window.location.href = "register"
}
const formattedPrice = (price) => {
  const [integerPart, decimalPart] = price.toFixed(2).split('.');
  const formattedIntegerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return `${formattedIntegerPart}.${decimalPart}`;
};
</script>
