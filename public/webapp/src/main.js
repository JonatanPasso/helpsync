import Vue from 'vue'
import App from './App.vue'

import {BootstrapVue} from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// import VeeValidate from 'vee-validate';
//
// Vue.use(VeeValidate, {
//     locale: 'pt_BR'
// });

import vueFilterPrettyBytes from 'vue-filter-pretty-bytes'
import vSelect from "vue-select"
import "vue-select/dist/vue-select.css"

import moment from "moment";
import StringMask from "./filters/StringMask";

window.moment = moment;

import VueBootrapDatepicker from 'vue-bootstrap-datetimepicker';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

Vue.component('date-picker', VueBootrapDatepicker);

import WFirstFilter from './filters/wfirst';
import DateTimeFormat from './filters/dateTimeFormat';

import loadDashMixin from './mixins/loadash.js';

import routes from './routes';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

Vue.use(BootstrapVue);
Vue.use(vueFilterPrettyBytes);

Vue.component("v-select", vSelect);

Vue.filter('wfirst', WFirstFilter.wfirst);
Vue.filter('dateTimeFormat', DateTimeFormat.dateTimeFormat);
Vue.filter('mask', StringMask.mask);

Vue.mixin(loadDashMixin);

Vue.config.productionTip = false;


import {Loader, LoaderOptions} from 'google-maps';

const loader = new Loader(GOOGLE_MAPS_KEY, {});

loader.load();


const router = new VueRouter({
    routes: routes,
    scrollBehavior(to, from, savedPosition) {

        $('html,body').animate({scrollTop: 0}, 500);

        // if (savedPosition) {
        //     return savedPosition
        // } else {
        //     return { x: 0, y: 0 }
        // }
        // return desired position
    }
});

// import AncoraTopo from './mixins/AncoraTopo';
// Vue.mixin(AncoraTopo);
//
//
// new Vue({
//
//     router,
//
//     data() {
//         return {
//             CLIENTE: {}, USER: {}, menu: null,
//         }
//     },
//
//     created() {
//         this.doGet('info-sessao')
//             .then((d) => {
//                 this.menu = d.menu;
//                 this.CLIENTE = d.CLIENTE;
//                 this.USER = d.USER;
//             })
//     },
//
//     render: h => h(App),
// }).$mount('#app');


import AncoraTopo from './mixins/AncoraTopo';

Vue.mixin(AncoraTopo);


import VueHtmlToPaper from 'vue-html-to-paper';

const options = {
    name: '_blank',
    specs: [
        'fullscreen=yes',
        'titlebar=yes',
        'scrollbars=yes'
    ],
    styles: [
        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
        'https://unpkg.com/kidlat-css/css/kidlat.css'
    ]
};

Vue.use(VueHtmlToPaper, options);


function initApp(sessionData) {

    new Vue({

        router,

        data() {
            return {
                CLIENTE: sessionData.CLIENTE,
                USER: sessionData.USER,
                menu: sessionData.menu,
                USER_PERMISSIONS: sessionData.permissoes
            }
        },

        created() {

            this.$root.$watch('CLIENTE.id', r => {

                this.doGet('info-sessao', {cliente_id: r})
                    .then(result => {
                        this.$root.menu = result.menu;
                        this.$root.USER_PERMISSIONS = result.permissoes;
                    });

            });
            //     this.doGet('info-sessao')
            //         .then((d) => {
            //             this.menu = d.menu;
            //             this.CLIENTE = d.CLIENTE;
            //             this.USER = d.USER;
            //         })
        },

        render: h => h(App),
    }).$mount('#app');

}

if (window['INFOSESSION']) {

    initApp(INFOSESSION);

} else {

    var urlToken = '&authorization=' + TOKEN;

    $.getJSON(HOST_API + '/api/info-sessao?' + urlToken)
        .then(function (d) {
            initApp(d);
        }, 'JSON')
        .fail(function () {
            location.href = HOST_API + '/login';
        });

}



