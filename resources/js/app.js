import('./bootstrap');
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCheckCircle, faCircleXmark, faArrowAltCircleRight} from '@fortawesome/free-regular-svg-icons'


import { createApp } from "vue/dist/vue.esm-bundler";
import ProductsIndex from "./components/products/ProductsIndex.vue";
import ProductsShow from "./components/products/ProductsShow.vue";
import ProductsEdit from "./components/products/ProductsEdit.vue";
import ProductCard from "./components/products/ProductCard.vue";
import ProductsCreate from "./components/products/ProductsCreate.vue";
import Paginator from "./components/Paginator.vue";
import Cart from "./components/cart/Cart.vue";
import Order from "./components/order/Order.vue";
import OrderShow from "./components/order/OrderShow.vue";
import OrderStateMail from "./components/order/OrderStateMail.vue";
import PermissionsIndex from "./components/permissions/PermissionsIndex.vue";
import RolesIndex from "./components/permissions/RolesIndex.vue";
import assignPermissions from "./components/permissions/assignPermissionsToResource.vue";
import assignRoles from "./components/permissions/assignRolesToUser.vue";
import ClientIndex from "./components/permissions/ClientIndex.vue";
import ClientCode from "./components/permissions/ClientCode.vue";
import ProductsDispatch from "./components/administration/ProductsDispatch.vue";
import Administration from "./components/administration/Administration.vue";


library.add(faCheckCircle, faCircleXmark, faArrowAltCircleRight);
const app = createApp({});


app.component('font-awesome-icon', FontAwesomeIcon)

app.component('products-index', ProductsIndex);
app.component('products-show', ProductsShow);
app.component('products-edit', ProductsEdit);
app.component('product-card', ProductCard);
app.component('products-create', ProductsCreate);
app.component('cart-index', Cart);
app.component('order-index', Order);
app.component('order-show', OrderShow);
app.component('order-state-mail', OrderStateMail);
app.component('permissions-index', PermissionsIndex);
app.component('roles-index', RolesIndex);
app.component('assign-permissions-to-resource', assignPermissions);
app.component('assign-roles-to-user', assignRoles);
app.component('client-index', ClientIndex);
app.component('client-code', ClientCode);
app.component('products-dispatch', ProductsDispatch);
app.component('administration', Administration);


app.component('paginator', Paginator);
app.mount('#app');
