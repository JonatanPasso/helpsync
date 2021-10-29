<template>
    <div id="app">

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->

            <Menu></Menu>

            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <BarraSuperior></BarraSuperior>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <!--                        <FrotaPainel></FrotaPainel>-->
                        <!--                        <component :is="tela"></component>-->

                        <!--                        <keep-alive>-->
                        <router-view></router-view>
                        <!--                        </keep-alive>-->
                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; ILLUMINARTI {{ currentYear }}</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->


        </div>

    </div>
</template>

<script>

import "./assets/tema/sb-admin/css/sb-admin-2.css";
import '@fortawesome/fontawesome-free/css/all.min.css';
import "./assets/tema/fluxo/modo_noturno.scss"
import "./assets/tema/fluxo/geral.scss"

// <!-- Bootstrap core JavaScript-->
/* eslint-disable  */
// import $ from "../../vendor/jquery/jquery"
// import "../../vendor/bootstrap/js/bootstrap.bundle.min.js"


window.$ = window.jQuery = require('jquery');

import 'bootstrap';

// require("./assets/tema/sb-admin/js/sb-admin-2");
require('jquery.easing');
// require('leaflet');
import 'leaflet';
import 'leaflet/dist/leaflet.css';

require('leaflet.gridlayer.googlemutant');
// require('bootstrap-datepicker');
require('lodash');


import Vue from 'vue';
import moment from 'moment';

moment.locale('pt-br');

import VueTheMask from 'vue-the-mask';
import vSelect from 'vue-select'

Vue.use(VueTheMask);
Vue.use(vSelect);

import VueCurrencyFilter from 'vue-currency-filter';

Vue.use(VueCurrencyFilter, {
    symbol: 'R$',
    thousandsSeparator: '.',
    fractionCount: 2,
    fractionSeparator: ',',
    symbolPosition: 'front',
    symbolSpacing: true
})

import Menu from './components/Menu.vue'

import AjaxMixin from './mixins/ajax.js';

Vue.mixin(AjaxMixin);

import Permissoes from './mixins/Permissoes';

Vue.mixin(Permissoes);

import BarraSuperior from "./components/BarraSuperior";

import VueScrollTo from "vue-scrollto"

Vue.use(VueScrollTo)

export default {
    name: 'App',
    components: {
        BarraSuperior,
        Menu,
    },

    data() {
        return {

            tela: null,
            currentYear: moment().format('Y')
        }
    },

    created() {

    },


    computed: {
        USER() {
            return this.$root.USER;
        }
    },

    mounted() {
        setTimeout(function () {
            require("./assets/tema/sb-admin/js/sb-admin-2");
        }, 50);

        /**modo noturno*/
        let modoNoturno = _.get(this.$root, 'USER.modo_noturno');
        if (modoNoturno === 'SIM') {
            $('body').addClass('modo-noturno');
        }

    },

    methods: {},
    filters: {
        wfirst(value) {
            return _.isString(value) ? _.first(value.split(" ")) : value;
        }
    }
}
</script>

<style>

body {
    color: #000;
}

@media (min-width: 768px) {
    .sidebar .nav-item .nav-link span {
        display: inline;
    }
}

.table {
    color: #000;
}

[v-cloak] {
    display: none;
}

.campo-busca-cliente-topo input, .campo-busca-cliente-topo .input-group-text {
    border-radius: 0px;
}
</style>
