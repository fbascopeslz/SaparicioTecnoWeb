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
Vue.component('Producto', require('./components/Producto.vue').default);
Vue.component('Almacen', require('./components/Almacen.vue').default);
Vue.component('Cliente', require('./components/Cliente.vue').default);
Vue.component('Proveedor', require('./components/Proveedor.vue').default);
Vue.component('Rol', require('./components/Rol.vue').default);
Vue.component('Usuario', require('./components/Usuario.vue').default);
Vue.component('Ingreso', require('./components/Ingreso.vue').default);
Vue.component('Venta', require('./components/Venta.vue').default);
Vue.component('Reportes', require('./components/Reportes.vue').default);
Vue.component('Estadisticas', require('./components/Estadisticas.vue').default);
Vue.component('Inventario', require('./components/Inventario.vue').default);


//Vue loading overlay
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
Vue.use(Loading);


//Google Maps
import * as VueGoogleMaps from 'vue2-google-maps'
Vue.use(VueGoogleMaps, {
    load: {
      key: 'AIzaSyBa_VaIloaj9-J3iFd2QjxwKF4JS-Cpogo'            
    },
});


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data :{
        menu : 1,                        
    }
});
