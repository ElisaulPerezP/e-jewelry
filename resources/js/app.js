import('./bootstrap');

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import { createApp } from "vue/dist/vue.esm-bundler";
import ProductsIndex from "./components/products/ProductsIndex.vue";

const app = createApp({});

app.component('products-index', ProductsIndex);
app.mount('#app');
