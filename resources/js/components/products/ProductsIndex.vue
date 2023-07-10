<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="py-4 flex justify-between">
            <div>
                <button @click="downloadProducts"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Descargar lista de productos
                </button>
            </div>
            <div>
                <button class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                        @click="popModal">Subir lista de productos</button>
            </div>

            </div>
        </div><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <section class="container mx-auto p-6 font-mono">
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                        <div class="py-4 flex justify-between">
                            <div>
                                <button @click="newProduct"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Nuevo producto
                                </button>
                            </div>
                            <div>
                                <input @change="search(query)" type="text" v-model="query" placeholder="Buscar..."
                                       class="bg-white  shadow-sm sm:rounded-lg">
                                <paginator @data="handleDataPagination" :currentPage="currentPage"
                                           :lastPage="receivedLastPage"></paginator>
                            </div>
                        </div>
                        <div class="w-full overflow-x-auto">
                            <table class="w-full text-xs border-separate">
                                <thead class="text-sm">
                                <tr
                                    class="text-md font-semibold tracking-wide text-left text-gray-900 bg-blue-100 uppercase border-b border-gray-600">
                                    <th class="px-4 py-3">{{ ('nombre') }}</th>
                                    <th class="px-4 py-3">{{ ('descripción') }}</th>
                                    <th class="px-4 py-3">{{ ('precio') }}</th>
                                    <th class="px-4 py-3">{{ ('stock') }}</th>
                                    <th class="px-4 py-3">{{ ('puntos') }}</th>
                                    <th class="px-4 py-3">{{ ('estado') }}</th>
                                    <th class="px-4 py-3">{{ ('codigo') }}</th>
                                    <th class="px-4 py-3">{{ ('imagen') }}</th>
                                    <th class="px-4 py-3">{{ ('ver') }}</th>
                                    <th class="px-4 py-3">{{ ('editar') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                <tr class="text-gray-700" v-for="product in products" :key="product.id">
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
                                        <button @click="changeStatus(product)"
                                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                            {{ product.status ? "DESACTIVAR" : "ACTIVAR" }}
                                        </button>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <p class="font-semibold text-black">{{ product.barCode }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <img :src="'/storage/' + product.image" alt="name">
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <a :href="'/products/' + product.id "
                                               class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                <slot>Detalle</slot>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border">
                                        <div class="flex items-center text-sm">
                                            <a :href=" '/products/' + product.id + '/edit/' "
                                               class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                <slot>Editar</slot>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button @click="back"
                                class="inline-flex items-center px-1 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Atras
                        </button>
                    </div>
                </section>
            </div>
        </div>

    <div v-if="formModal" class="fixed inset-0 flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-lg">
            <h2 class="text-2xl font-bold mb-4"> Subir un archivo .xlsx </h2>
            <p>{{ modalMessage }}</p>
            <div class="flex items-center text-sm">
                <input type="file" @change="handleFileUpload">
            </div>
            <div class="flex items-center text-sm">
                <input type="text" v-model="toSendFile.name"
                       placeholder="Nombre"
                       class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2">
            </div>
            <div class="flex items-center text-sm">
                <input type="text" v-model="toSendFile.comments"
                       placeholder="comments"
                       class="font-arial mb-2 mt-2 border border-gray-400 rounded-lg p-2">
            </div>
            <button class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xl text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                    @click="uploadProducts">Subir lista de productos</button>
            <button @click="closeModal"
                    class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Cancelar
            </button>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import axios from 'axios'

const products = ref([])
const query = ref("")
const receivedData = ref("")
const currentPage = ref(1)
const receivedCurrentPage = ref(1)
const receivedFirstPage = ref(1)
const receivedLastPage = ref(1)
const per_page = ref(15)
const toSendFile = ref({})
const formModal = ref(false)

function handleFileUpload(event) {
    const file = event.target.files[0]
    const reader = new FileReader()
    toSendFile.value.file=file
    reader.readAsDataURL(file)
}

const uploadProducts = async () => {
    try {
        const formData = new FormData();
        formData.append('file', toSendFile.value.file);
        formData.append('name', toSendFile.value.name);
        formData.append('comments', toSendFile.value.comments);

        const response = await axios.post('/api/import/products', formData);
        if (response.status === 201) {
            closeModal();
        }
    } catch (error) {
        console.error('Error al subir los productos:', error);
    }
};

onMounted(() => {
    axios.get('/api/products', {
        params: {
            searching: "",
            current_page: currentPage.value,
            per_page: per_page.value,
            flag: 0
        }
    })
        .then(response => {
            products.value = response.data.data
            receivedCurrentPage.value = response.data.meta.current_page
            receivedLastPage.value = response.data.meta.last_page
        })
        .catch(error => {
            console.log(error);
        });
})

const back = () => {
    window.location.href = window.history.back();
}

const newProduct = () => {
    window.location.href = '/products/create';
}

const changeStatus = async (product) => {
    product.status = !product.status
    await axios.put('/api/products/' + product.id + '/changeStatus/')
}

const downloadProducts = async () => {
    try {
        const response = await axios.get('/api/export/products/', {responseType: 'blob'});
        const contentDisposition = response.headers['content-disposition'];
        const fileNameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
        const matches = fileNameRegex.exec(contentDisposition);
        const fileName = matches && matches[1] ? matches[1] : 'products.xlsx';
        const blob = new Blob([response.data], {type: 'text/xlsx'});
        FileSaver.saveAs(blob, fileName);
    } catch (error) {
        console.error('Error al descargar los productos:', error);
    }
};
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
            flag: 0
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

const search = async (query) => {
    axios.get('/api/products', {
        params: {
            searching: query,
            current_page: currentPage.value,
            per_page: per_page.value,
            flag: 0
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
const popModal = () =>{
    formModal.value = true
}
const closeModal = () => {
    formModal.value = false
}

</script>
