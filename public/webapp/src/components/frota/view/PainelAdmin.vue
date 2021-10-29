<template>
    <div>

        <!-- QUADRO DE INDICADORES-->

        <div class="row">

            <div class="col-sm-6 col-md-3 col-lg-2  mb-4"
                 v-for="(v,n,i) in contadores">

                <b-card class="card border-left-primary shadow-lg h-100 py-2">

                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fa" :class="`fa-${icons[i]}`"></i> {{labels[i]}}</h6>

                    <br>

                    <h4 class="text-left" style="margin-bottom: 0px">
                        <ICountUp
                            :delay="3000"
                            :endVal="v"
                            :options="{ useEasing: true, useGrouping: true,separator: '.', decimal: ','}"
                        />

                        <span v-if="n == 'conexoes'">
                            /
                        <ICountUp
                            :delay="3000"
                            :endVal="contadores.rastreadores - v"
                            :options="{ useEasing: true, useGrouping: true,separator: '.', decimal: ','}"
                        />
                        </span>

                    </h4>

                </b-card>
            </div>

        </div>


        <b-row>
            <b-col md="4" class=" mb-4">

                <!--Lista de veiculos-->
                <b-card class="shadow-lg">

                    <template #header>
                        <i class='fa fa-car'></i> Veículos
                        <b-input-group style="width: 70%;float:right"
                                       append-html="<i class='fa fa-search'></i>">
                            <b-form-input type="text"
                                          v-model="filtro_placa"
                                          placeholder="PLACA"></b-form-input>
                        </b-input-group>
                    </template>

                    <div style="max-height: 50vh;overflow: auto;margin: 0px"
                         class="table-responsive text-center">

                        <table class="table table-bordered table-sm">

                            <thead>
                            <tr class="">
                                <th>#</th>
                                <th>Placa</th>
                                <th><i class="fa fa-clock"></i></th>
                                <th><i class="fa fa-key"></i></th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-if="ajaxStatus && operacao == 'loadveiculos'">
                                <td colspan="22">
                                    <b-spinner></b-spinner>
                                </td>
                            </tr>

                            <tr v-for="(v,i) in mapaVeiculos"
                                @click.prevent.stop="loadStatusRastreador(v.rastreador_esn)"
                                @click.prevent="veiculosSele=v"
                                class="selectable"
                                v-show="v.placa.toLowerCase().trim().indexOf(filtro_placa.toLowerCase().trim()) != -1"
                                :class="{'table-warning':!v.last_logs,'table-success':v.last_logs,'table-active':v==veiculosSele}">
                                <td style="vertical-align: middle">
                                    {{ i + 1 }}
                                </td>
                                <td style="vertical-align: middle">
                                    {{v.placa}}
                                </td>
                                <td style="white-space: nowrap">
                                    <span v-if="v.last_logs">
                                        {{v.last_logs.gps_timestamp | dateTimeFormat('DD/MM/YY HH:mm')}}
                                    </span>
                                    <span v-else><i class="fa fa-exclamation-triangle"></i></span>
                                </td>

                                <td style="vertical-align: middle">
                                    <span v-if="v.last_logs">
                                        <i class="fa fa-key" style="color: green" v-if="v.last_logs.pos_chave==1"></i>
                                        <i class="fa fa-key" style="color: red" v-else="v.last_logs.pos_chave"></i>
                                    </span>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>

                </b-card>


                <!--   Dados do rastreador-->
                <b-card v-if="rastreadorStatus" class="shadow-lg">

                    <template #header>
                        <i class="fa fa-mobile"> Rastreador</i>
                    </template>

                    <template>


                        <b-row v-if="!rastreadorStatus.last_log">
                            <b-col>
                                <b-card bg-variant="danger" class="text-white text-center">
                                    <i class="fa fa-exclamation-triangle fa-3x"></i> FALHA DE COMUNIÇÃO
                                </b-card>
                            </b-col>
                        </b-row>

                        <b-row v-else-if="rastreadorStatus.alerta_atrazo">
                            <b-col>
                                <b-card bg-variant="warning" class="text-white text-center">
                                    <i class="fa fa-exclamation-triangle fa-3x"></i> ATRAZO DE COMUNIÇÃO
                                </b-card>
                            </b-col>
                        </b-row>

                        <table class="table">

                            <tr v-if="rastreadorStatus.last_log">
                                <td colspan="2">

                                    <b-row>

                                        <b-col class="text-center">
                                            <div>GPS</div>
                                            <span class="text-success"
                                                  v-if="rastreadorStatus.last_log.gps_fixo == 1">OK</span>
                                            <span class="text-warning"
                                                  v-else>RUIM</span>
                                        </b-col>

                                        <b-col class="text-center">
                                            <div>SOMBRA</div>
                                            <i class="fa fa-check-square fa-2x text-success"
                                               v-if="rastreadorStatus.last_log.gps_online == 0"></i>
                                            <i class="fa fa-times fa-2x text-danger"
                                               v-else></i>
                                        </b-col>

                                        <b-col class="text-center">

                                            <div>SINAL</div>

                                            <span class="text-success"
                                                  v-if="rastreadorStatus.last_log.gps_quality > 3">ÓTIMO</span>

                                            <span class="text-warning"
                                                  v-else-if="rastreadorStatus.last_log.gps_quality > 1">BOM</span>

                                            <span class="text-danger" v-else>RUIM</span>


                                        </b-col>

                                        <b-col class="text-center">
                                            <div>POSCHAVE</div>
                                            <i class="fa fa-key fa-2x text-success"
                                               v-if="rastreadorStatus.last_log.pos_chave == 1"></i>
                                            <i class="fa fa-key fa-2x text-danger"
                                               v-else></i>
                                        </b-col>

                                        <b-col class="text-center">
                                            <div>DIRECAO</div>
                                            <i :style="`transform: rotate(${rastreadorStatus.last_log.gps_dir}deg)`"
                                               class="fa fa-arrow-up fa-2x text-success"></i>
                                        </b-col>

                                    </b-row>
                                </td>
                            </tr>

                            <tr v-if="rastreadorStatus.last_log">
                                <td>ÚLTIMA ATUALIZAÇÃO</td>
                                <td>{{rastreadorStatus.last_log.gps_timestamp |
                                    dateTimeFormat('DD/MM/YYYY HH:mm:ss')}}
                                </td>
                            </tr>

                            <tr v-if="rastreadorStatus.last_log">
                                <td>ATRAZO DE COMUNICAÇÃO</td>
                                <td>{{rastreadorStatus.delay}}</td>
                            </tr>

                            <tr v-if="rastreadorStatus.last_log">
                                <td>LOGS ENVIADOS</td>
                                <td>{{rastreadorStatus.count_logs}}</td>
                            </tr>

                            <tr v-if="rastreadorStatus.last_log">
                                <td>LOCALIZAÇÃO</td>
                                <td><a
                                    :href="`https://maps.google.com/?q=${rastreadorStatus.last_log.gps_lat},${rastreadorStatus.last_log.gps_lng}`">
                                    {{reverse_geocode}}</a>
                                </td>
                            </tr>

                            <tr v-for="(n,i) in ['esn','modelo','fone_chip_gsm','operadora_gsm']">
                                <td>{{['ESN','MODELO','Nº CHIP','OPERADORA'][i]}}</td>
                                <td>{{rastreadorStatus.rastreador[n]}}</td>
                            </tr>

                            <tr>
                                <td>VEÍCULO</td>
                                <td>{{rastreadorStatus.veiculo.placa}}</td>
                            </tr>
                        </table>
                    </template>
                </b-card>

            </b-col>

            <!--            Mapa-->
            <b-col>
                <b-card class="shadow-lg"
                        no-body
                        header-html="<i class='fa fa-map'></i> Todos Veículos no mapa">


                    <b-row style="padding:10px">
                        <b-col>
                            <btn-buscar-clientes variant="primary" block
                                                 @select="cliente=$event">
                                <i class="fa fa-user-friends"></i> BUSCAR CLIENTE
                            </btn-buscar-clientes>
                        </b-col>

                        <b-col>

                            <btn-buscar-veiculos variant="primary" block
                                                 @select="showVeiculo">
                                <i class="fa fa-card"></i> BUSCAR VEÍCULO
                            </btn-buscar-veiculos>
                        </b-col>

                        <b-col>
                            <b-form-group>

                                <b-input-group>
                                    <the-mask type="text"
                                              :mask="'#####################'"
                                              class="form-control"
                                              v-model="rastreador_esn"
                                              placeholder="ID RASTREADOR"></the-mask>
                                    <b-input-group-append>
                                        <b-input-group-text href="#"
                                                            @click.prevent.stop="loadStatusRastreador(rastreador_esn,true)"
                                                            tag="a"><i class="fa fa-search"></i>
                                        </b-input-group-text>
                                    </b-input-group-append>
                                </b-input-group>
                            </b-form-group>
                        </b-col>

                        <b-col md="2" class="text-right">
                            <b-button @click="limpar" variant="secondary">LIMPAR</b-button>
                        </b-col>
                    </b-row>


                    <div class="mapa" :style="{height:'50vh'}"></div>
                </b-card>

            </b-col>


        </b-row>


        <b-row>


            <b-col>
                <br>
                <b-card v-if="cliente"
                        class="shadow-lg"
                        body-border-variant="none">
                    <template #header>
                        <i class="fa fa-users"> Cliente</i>
                    </template>
                    <template>
                        <table class="table table-bordered">
                            <tbody>
                            <tr v-for="n in [['nome','NOME'],['tipo','TIPO'],['cpf_cnpj','CPF/CNPJ']]">
                                <td>{{n[1]}}:</td>
                                <td>{{ l_.get(cliente,n[0]) }}</td>
                            </tr>
                            <tr>
                                <td>ENDEREÇO:</td>
                                <td>
                                    <span>{{l_.values(l_.pick(cliente,['endereco','complemento','cidade','estado'])).join(', ')}},&nbsp</span>
                                </td>
                            </tr>
                            <tr>
                                <td>USUÁRIOS</td>
                                <td style="padding: 0px">
                                    <table class="table table-light" style="margin: 0px">
                                        <thead>
                                        <tr>
                                            <th>login</th>
                                            <th>status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="u in usuarios">
                                            <td>{{u.email}}</td>
                                            <td>{{u.status}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </template>
                </b-card>
            </b-col>

        </b-row>
    </div>
</template>

<script>


    import ICountUp from 'vue-countup-v2';
    import BtnBuscarClientes from "../../geral/BtnBuscarClientes";
    import BtnBuscarVeiculos from "../BtnBuscarVeiculos";
    import Util from "../../../util";

    var iconCarLigado = require('@/assets/frota/icones/carro-ligado.png');
    var iconCarDesligado = require('@/assets/frota/icones/carro-desligado.png');
    var mapLayers = new Map();

    export default {
        name: "PainelAdmin",
        components: {BtnBuscarVeiculos, BtnBuscarClientes, ICountUp},

        data() {
            return {
                contadores: {
                    veiculos: 0,
                    rastreadores: 0,
                    clientes: 0,
                    usuarios: 0,
                    conexoes: 0,
                    fila: 0
                },
                labels: ['Veículos', 'Rastreadores', 'Clientes', 'Usuarios', 'Eqp On/ Off', 'Fila Msg'],
                icons: ['car', 'mobile', 'users', 'user'],
                intervalId: null,

                cliente: null,
                usuarios: [],
                usuario: null,
                veiculos: [],

                veiculo: null,

                rastreador_esn: null,

                rastreadorStatus: null,

                filtro: {
                    texto_veiculo: '',
                    texto_cliente: ''
                },

                reverse_geocode: null,

                mapCanvas: null,
                mapa: {
                    _veiculos: []
                },
                filtro_placa: '',
                veiculosSele: null


            }
        },

        computed: {
            mapaVeiculos() {
                return this.mapa._veiculos;
            }
        },

        created: function () {

            this.loadContadores();

            this.intervalId = setInterval(() => {
                this.loadContadores();
            }, 1000 * 15);
        },

        mounted() {

            var htmlElemnt = $(this.$el).find('.mapa').get(0);
            this.mapCanvas = Util.Leaflet.initMap(htmlElemnt, 3);

            var _this = this;

            this.mapCanvas.whenReady(() => {
                _this.loadMapVeiculos();
            });

        },

        beforeDestroy() {
            clearTimeout(this.intervalId);
        },

        watch: {

            'cliente.id'(clienteId) {
                if (clienteId) {
                    // this.buscarVeiculos();
                    this.buscarUsuarios();
                    this.loadMapVeiculos(1);
                }
            },

            'rastreadorStatus.last_log'(log) {
                log && this.endereco();
            },

            'veiculo.id'(id) {
                id && this.loadStatusRastreador(this.veiculo.rastreador_esn);
            }
        },

        methods: {
            loadContadores() {

                this.doGet('frota/painel-admin/contadores')
                    .dthen('contadores');
            },

            loadStatusRastreador(esn, filrar = false) {

                if (!esn) {
                    this.alertErro("Veículo sem rastreador vinculado");
                    return;
                }

                this.doGet('frota/painel-admin/rastreador-status', {rastreador_esn: esn})
                    .then(result => {
                        this.rastreadorStatus = result;
                        if (result.veiculo) {
                            this.showVeiculo(result.veiculo);
                            if (filrar) this.filtro_placa = result.veiculo.placa;
                        }
                    });
            },

            buscarVeiculos() {
                this.operacao = 'buscar';
                this.doGet('frota/veiculos/buscar', {
                    filtro: {cliente_id: this.cliente.id}
                }).then(result => {
                    this.veiculos = result.data;
                }).always(() => {
                    this.operacao = null
                });
            },
            buscarUsuarios() {
                this.operacao = 'buscar-usuarios';
                this.doGet('geral/usuarios/buscar', {
                    filtro: {cliente_id: this.cliente.id}
                }).then(result => {
                    this.usuarios = result.data;
                }).always(() => {
                    this.operacao = null
                });
            },


            limpar() {
                var defaultData = this.$options.data();

                for (var i in defaultData) {
                    if (i !== 'contadores' && i != 'mapCanvas')
                        this[i] = defaultData[i];
                }

                this.mapCanvas.flyTo(new L.LatLng(-14, -50), 3, {animate: false});
                this.clearIconsMap();

                setTimeout(() => {
                    this.loadMapVeiculos();
                }, 1000);

            },

            endereco: function () {

                var self = this;

                Util.googleMaps.reverseGeoCode(this.rastreadorStatus.last_log.gps_lat,
                    this.rastreadorStatus.last_log.gps_lng)
                    .then(function (result) {
                        if (result.status == 'OK') {
                            self.reverse_geocode = result.results[0].formatted_address;
                        }
                    });
            },

            loadMapVeiculos(page = 1) {

                if (page == 1) this.mapa._veiculos = [];

                this.operacao = 'loadveiculos';

                this.doGet('frota/veiculos/buscar', {
                    page: page,
                    filtro: {cliente_id: _.get(this, 'cliente.id', null)}
                }).then(r => {
                    if (r.data.length > 0) {
                        this.mapa._veiculos = this.mapa._veiculos.concat(r.data);
                        this.loadMapVeiculos(page + 1);
                    } else {
                        this.renderIcons();
                    }
                }).always(() => {
                    this.operacao = '';
                })
            },
            renderIcons() {

                this.clearIconsMap();

                this.mapa._veiculos.forEach(v => {
                    this.createOrUpdateIcone(v)
                });
            },

            clearIconsMap() {
                var _this = this;
                mapLayers.forEach(v => {
                    _this.mapCanvas.removeLayer(v);
                });
            },

            createOrUpdateIcone: function (veiculo) {


                if (veiculo.last_logs) {

                    var imgIconeCar = veiculo.last_logs.pos_chave == 1 ? iconCarLigado : iconCarDesligado;

                    var template = `<div><div style="display: none" class="label">${veiculo.placa}
                            </div><img src="${imgIconeCar}"></div>`;

                    var divIcon = L.divIcon({
                        className: 'teste',
                        html: template
                    });

                    var markerOptions = {icon: divIcon};
                    var latLng = [veiculo.last_logs.gps_lat.toFixed(5),
                        veiculo.last_logs.gps_lng.toFixed(5)];

                    var marker = L.marker(latLng, markerOptions);
                    marker.bindPopup(`Placa: ${veiculo.placa}`);
                    marker.addTo(this.mapCanvas);
                    mapLayers.set(veiculo.id, marker);

                }
            },

            showVeiculo(v) {

                let marker = mapLayers.get(v.id);
                if (marker) {
                    this.mapCanvas.flyTo(marker.getLatLng(), 16, {animate: false});
                    marker.openPopup();

                }
                // this.loadStatusRastreador(v.rastreador_esn)
            }
        },
    }
</script>

<style scoped>

</style>
