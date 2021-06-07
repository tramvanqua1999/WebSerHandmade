require('./bootstrap');


window.Vue = require('vue');
import VueAxios from 'vue-axios';
import axios from 'axios';
import store from './store'
Vue.use(VueAxios, axios);
import Home from './components/pages/Home.vue'
import Store from './components/pages/Store.vue'
import Block from './components/pages/Block.vue'
import Unblock from './components/pages/Unblock.vue'
import Confirmation from './components/pages/Confirmation.vue'
import Create from './components/pages/Add.vue'
import Login from './components/pages/Login.vue'
import AddGroup from './components/pages/AddGroup.vue'
import Order from './components/pages/admin/order/Home.vue'
import Detail from './components/pages/admin/detail/Detail.vue'
import Orderapproved from './components/pages/admin/order/Orderapproved.vue'
import Orderiscanceled from './components/pages/admin/order/Orderiscanceled.vue'
import Listproduct from './components/pages/admin/product/Listproduct.vue'
import Detailproduct from './components/pages/admin/product/Detail.vue'
import Comments from './components/pages/admin/comments/comment.vue'
import Chartgroupbar from './components/pages/admin/chart/ChartGroupBar.vue'
import ChartTotalBar from './components/pages/admin/chart/ChartTotalbar.vue'
import ChartTotalLine from './components/pages/admin/chart/ChartTotalLine.vue'
// -----------------inf----------------?
import InformationShop from './components/pages/InformationShop.vue'
import InformationCustomer from './components/pages/InformationCustomer.vue'
//----------------------------------SHOP---------------------------------
import ShopHome from './components/pages/shop/Home.vue'
import ShopHomeCreate from './components/pages/shop/Add.vue'
import ShopHomeUpdate from './components/pages/shop/Update.vue'
import DetailProduct1 from './components/pages/shop/DetailProduct.vue'
import TransportFee from './components/pages/shop/TransportFee.vue'
import ShopAccount from './components/pages/shop/Account.vue'
import ShopUpdateAccount from './components/pages/shop/UpdateProfile.vue'
import ShopUpdateAvatar from './components/pages/shop/UpdateAvatar.vue'
import ShopUpdateCredit from './components/pages/shop/UpdateCredit.vue'
import ShopChartgroupbar from './components/pages/shop/chart/ChartGroupBar.vue'
import ShopChartTotalLine from './components/pages/shop/chart/ChartTotalLine.vue'
//----------------------------------Shop Order---------------------------------
import OrderHome from './components/pages/shop/order/Home.vue'
import Cancel from './components/pages/shop/order/ListCanceled.vue'
import Confirmproduct from './components/pages/shop/order/ListConfirm.vue'

import Vue from 'vue'
import VueRouter from 'vue-router'
import VueToast from 'vue-toast-notification'
import 'vue-toast-notification/dist/theme-sugar.css'
import { initialize } from "./modules/general"

Vue.use(VueToast,{
    position: 'top'
});
Vue.use(VueRouter)
const routes = [
    {  path: '/home', component: Home, meta: { requiresAuth: true }},
    {  path: '/login', component: Login, meta: { requiresAuth: false }},
    {  path: '/store', component: Store, meta: { requiresAuth: true } },
    {  path: '/confirmation', component: Confirmation, meta: { requiresAuth: true } },
    {  path: '/block', component: Block, meta: { requiresAuth: true } },
    {  path: '/unblock', component: Unblock, meta: { requiresAuth: true } },
    {  path: '/create', component: Create, meta: { requiresAuth: true } },
    {  path: '/addgroup', component: AddGroup, meta: { requiresAuth: true } },
    {  path: '/home/order', component: Order, meta: { requiresAuth: true } },
    {  path: '/home/detail', component: Detail, meta: { requiresAuth: true } },
    {  path: '/home/order/approved', component: Orderapproved, meta: { requiresAuth: true } },
    {  path: '/home/order/canceled', component: Orderiscanceled, meta: { requiresAuth: true } },
    {  path: '/home/listproduct', component: Listproduct, meta: { requiresAuth: true } },
    {  path: '/home/detailproduct', component: Detailproduct, meta: { requiresAuth: true } },
    {  path: '/home/comments', component: Comments, meta: { requiresAuth: true } },
    {  path: '/chart/group/bar', component: Chartgroupbar, meta: { requiresAuth: true } },
    {  path: '/chart/total/bar', component: ChartTotalBar, meta: { requiresAuth: true } },
    {  path: '/chart/total/line', component: ChartTotalLine, meta: { requiresAuth: true } },
    // --------------------inf--------------------
    {  path: '/informationShop', component: InformationShop, meta: { requiresAuth: true } },
    {  path: '/informationCustomer', component: InformationCustomer, meta: { requiresAuth: true } },
    //-----------------------------------SHOP----------------------------
    {  path: '/shop/home', component: ShopHome, meta: { requiresAuth: true }},
    {  path: '/shop/home/create', component: ShopHomeCreate, meta: { requiresAuth: true }},
    {  path: '/shop/home/update/{$id}', component: ShopHomeUpdate, meta: { requiresAuth: true }},
    {  path: '/shop/home/transportfee', component: TransportFee, meta: { requiresAuth: true }},
    {  path: '/shop/account', component: ShopAccount, meta: { requiresAuth: true }},
    {  path: '/shop/updateaccount', component: ShopUpdateAccount, meta: { requiresAuth: true }},
    {  path: '/shop/updatecredit', component: ShopUpdateCredit, meta: { requiresAuth: true }},
    {  path: '/shop/updateavatar', component: ShopUpdateAvatar, meta: { requiresAuth: true }},
    {  path: '/shop/detail/product', component: DetailProduct1, meta: { requiresAuth: true }},
    {  path: '/shop/chart/group/bar', component: ShopChartgroupbar, meta: { requiresAuth: true } },
    {  path: '/shop/chart/total/line', component: ShopChartTotalLine, meta: { requiresAuth: true } },
     //-----------------------------------ORDER----------------------------
    {  path: '/shop/order', component: OrderHome, meta: { requiresAuth: true }},
    {  path: '/shop/cancel', component: Cancel, meta: { requiresAuth: true }},
    {  path: '/shop/confirm', component: Confirmproduct, meta: { requiresAuth: true }},
  ]

const router = new VueRouter({
    mode: 'history',
    routes 
})
initialize(store, router);
Vue.config.devtools = false
Vue.component('mainapp', require('./App.vue'))
const app = new Vue({
    el: '#app',
    router,
    store
});