require('./bootstrap');
//////////////////////////////
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
//////////////////////////////
import { createApp } from "vue";
import ProductsIndex from './components/products/ProductsIndex';

const app = createApp({});

app.component('product-index', ProductsIndex);
app.mount('#app');
