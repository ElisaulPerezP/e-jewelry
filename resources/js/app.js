import('./bootstrap');

import { createApp } from "vue/dist/vue.esm-bundler";
import ProductsIndex from "./components/products/ProductsIndex.vue";
import ProductsShow from "./components/products/ProductsShow.vue";
import ProductsEdit from "./components/products/ProductsEdit.vue";
import ProductCard from "./components/products/ProductCard.vue";
import ProductsCreate from "./components/products/ProductsCreate.vue";
import Paginator from "./components/Paginator.vue";


const app = createApp({});

app.component('products-index', ProductsIndex);
app.component('products-show', ProductsShow);
app.component('products-edit', ProductsEdit);
app.component('product-card', ProductCard);
app.component('products-create', ProductsCreate);
app.component('paginator', Paginator);
app.mount('#app');
