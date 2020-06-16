/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Echo from "laravel-echo"
import BootstrapVue from "bootstrap-vue";
require('./bootstrap');
require('../../node_modules/jquery-easing/jquery.easing.1.3')
require('../../node_modules/chart.js/dist/Chart')
require('../../node_modules/xterm/lib/xterm')
//require('./chart-area-demo')
//require('./chart-bar-demo')
//require('./chart-pie-demo')
//require('./datatables-demo')

window.Vue = require('vue');
Vue.use(BootstrapVue);
//Vue.use(VueWebsocket, "ws://78.47.135.212:2375/containers/mc-home/attach/ws?logs=0&stream=1&stdin=1&stdout=1&stderr=1");
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

 const files = require.context('./', true, /\.vue$/i)
 files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
//Vue.component('dashboard-component', require('./components/DashboardComponent').default);
//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//Vue.component('terminal-component', require('./components/TerminalComponent.vue').default);

//configures socket.io

window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});
/*window.Echo.private(`App.User.2`)
    .listen('.test', (e) => {
        console.log(e.update);
        console.log('heys')
    });*/

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
