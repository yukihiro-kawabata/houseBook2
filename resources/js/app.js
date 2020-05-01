/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('cash-list-food-cost-graph-component', require('./components/cash/list/FoodCostGraphComponent.vue').default);// 食費、cash明細で使用するグラフ
Vue.component('cash-list-eating-out-graph-component', require('./components/cash/list/EatingOutGraphComponent.vue').default);// 外食、cash明細で使用するグラフ
Vue.component('cash-list-utility-cost-graph-component', require('./components/cash/list/UtilityCostGraphComponent.vue').default);// 水道光熱費、cash明細で使用するグラフ
Vue.component('cash-list-social-expence-graph-component', require('./components/cash/list/SocialExpenceGraphComponent.vue').default);// 遊興費、cash明細で使用するグラフ
Vue.component('cash-list-life-cost-graph-component', require('./components/cash/list/LifeCostGraphComponent.vue').default);// 生活費・日用品、cash明細で使用するグラフ

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const graph1 = new Vue({ el: '#graph' });

