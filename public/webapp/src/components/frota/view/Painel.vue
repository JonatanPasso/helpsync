<template>


    <div class="row no-gutters" id="jt-frota-Painel" v-cloak>


        <div class="col-sm-12 col-md-12 col-xl-3">


            <div class="card shadow mb-1">

                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-car"></i> Veículos</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body" style="padding: 0px">

                    <div style="max-height: 300px;max-height: 58vh">

                        <table class="table table-hover selectable">
                            <thead>
                            <tr>
                                <th>Frota</th>
                                <th>Placa</th>
                                <th><i class="fa fa-key"></i></th>
                                <th><i class="fa fa-check"></i></th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-if="!veiculos.length">
                                <td colspan="4" class="text-center">Nenhum veículo cadastrado</td>
                            </tr>

                            <tr class="clickable"
                                v-for="v in veiculos"
                                @click="veiculo=v"
                                :class="{'table-success':veiculo && v.id == veiculo.id}">
                                <td>{{v.numero_frota}}</td>
                                <td>{{v.placa}}</td>
                                <td>

                                    <i class="fa fa-key" v-if="v.last_logs && v.last_logs.pos_chave == 1"
                                       style="color:green"></i>
                                    <i class="fa fa-key" v-else style="color:red"></i>
                                </td>
                                <td>
                                    <span v-if="v.last_logs">
                                    <i class="fa fa-check" style="color: green"></i>
                                    </span>
                                    <span v-else="v.last_logs">
                                    <i class="fa fa-exclamation-triangle" style="color: orange"></i>
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="card shadow mb-1" v-if="veiculo && veiculo.last_logs">

                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-clock"></i> Última Posição</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body"
                     style="padding: 0px">

                    <table class="table" style="margin-bottom: 0px">

                        <tbody>
                        <tr>
                            <td>DATA E HORA</td>
                            <td class="text-center">
                                {{ veiculo.last_logs.gps_timestamp | dateTimeFormat('DD/MM/YYYY HH:mm:ss') }}
                            </td>
                        </tr>
                        <tr>
                            <td>IGNIÇÃO</td>
                            <td class="text-center">
                                <i v-if="veiculo.last_logs.pos_chave == 1" class="fa fa-key color_green"></i>
                                <i v-if="veiculo.last_logs.pos_chave == 0" class="fa fa-key color_red"></i>
                                <i v-if="veiculo.last_logs.pos_chave === null" class="fa fa-key color_red"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>VELOC.</td>
                            <td class="text-center">{{ veiculo.last_logs.gps_speed }} KM</td>
                        </tr>

                        <tr>
                            <td>
                                SINAL GPS
                            </td>
                            <td class="text-center">
                              <span v-if="veiculo.last_logs.gps_fixo =='0'" class=" text-warning">
                                 <i class="fa fa-exclamation-triangle"></i> RUIM
                              </span>
                                <span v-else class="text-success">OK</span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" class="text-center">

                                <google-maps-link :lat="veiculo.last_logs.gps_lat"
                                                  :lng="veiculo.last_logs.gps_lng">
                                    <span v-geocode="[veiculo.last_logs.gps_lat,veiculo.last_logs.gps_lng]"></span>
                                </google-maps-link>
                            </td>
                        </tr>

                        </tbody>

                    </table>


                </div>
            </div>


        </div>


        <div class="col-sm-12 col-md-12 col-xl-9">


            <div class="card mb-1">

                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-map"></i> Mapa</h6>

                </div>
                <!-- Card Body -->
                <div class="card-body" style="padding: 0px">

                    <div id="mapa" style="min-height: 300px;height: 58vh; border: 1px solid #ccc"></div>

                    <b-overlay
                        :show="ajaxStatus && operacao == 'historico'"
                        rounded
                        opacity="0.6"
                        spinner-variant="primary">

                        <b-form inline class="historico">
                            <b-form-datepicker
                                locale="pt-BR"
                                @input="carregarHistorico"
                                label-no-date-selected="Selecionar Data"
                                v-model="data_dia_filtro"
                                class="ml-2 mb-0"></b-form-datepicker>

                            <div class="ml-2 ml-md-auto d-md-inline-flex">
                                <label>Itens por página:</label>
                                <b-select v-model="config.items_por_pagina"
                                          @change="carregarHistorico"
                                          class="ml-0">
                                    <option>10</option>
                                    <option>100</option>
                                    <option>1000</option>
                                    <option>10000</option>
                                </b-select>
                            </div>
                        </b-form>

                        <br>

                        <b-pagination
                            v-model="config.page"
                            :total-rows="historico.total"
                            :per-page="historico.per_page"
                            align="center"></b-pagination>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <!--                                    <th class="text-center">Frota</th>-->
                                    <!--                                    <th class="text-center">Placa</th>-->
                                    <th class="text-center">DATA HORA</th>
                                    <th class="text-center">IGN</th>
                                    <th class="text-center">VEL.</th>
                                    <th class="text-center d-none d-lg-table-cell">DIR</th>
                                    <th class="text-center d-none d-lg-table-cell">GPS</th>
                                    <th class="text-center d-none d-lg-table-cell">A. SOMBA</th>
                                    <th class="text-center">LOCAL</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr v-if="!historico.data.length">
                                    <td colspan="99" class="text-center" v-if="!veiculo">SELECIONE UM VEICULO</td>
                                    <td colspan="99" class="text-center" v-else="!veiculo">Sem dados</td>
                                </tr>
                                <tr v-for="v in historico.data">
                                    <!--                                    <td class="text-center">{{ v.veiculo.numero_frota }}</td>-->
                                    <!--                                    <td class="text-center">{{ v.veiculo.placa }}</td>-->
                                    <td class="text-center">
                                        {{ v.gps_timestamp | dateTimeFormat('DD/MM/YYYY HH:mm:ss') }}
                                    </td>
                                    <td class="text-center">
                                        <i v-if="v.pos_chave == 1" class="fa fa-key color_green"></i>
                                        <i v-if="v.pos_chave == 0" class="fa fa-key color_red"></i>
                                        <i v-if="v.pos_chave === null" class="fa fa-key color_red"></i>
                                    </td>

                                    <td class="text-center">{{ v.gps_speed }} KM</td>

                                    <td class="text-center  d-none d-lg-table-cell">
                                        <i class="fa fa-arrow-up"
                                           :style="`transform: rotate(${v.gps_dir}deg)`"></i>
                                    </td>

                                    <td class="text-center  d-none d-lg-table-cell">
                                        <i v-if="v.gps_fixo == 1" class="fa fa-wifi color_green"></i>
                                        <i v-if="v.gps_fixo == 0" class="fa fa-wifi color_red"></i>
                                    </td>

                                    <td class="text-center d-none d-lg-table-cell">
                                        <i v-if="v.gps_online == 1" class="fa fa-check color_green"></i>
                                        <i v-if="v.gps_online == 0" class="fa fa-check color_red"></i>
                                    </td>

                                    <td class="text-center">
                                        <google-maps-link :lat="v.gps_lat" :lng="v.gps_lng">
                                            <span v-geocode="[v.gps_lat,v.gps_lng]"></span>
                                        </google-maps-link>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </b-overlay>

                </div>

            </div>

            <!--            <div class="card shadow mb-4 tbl-filtro-posisic">-->

            <!--                &lt;!&ndash; Card Header - Dropdown &ndash;&gt;-->
            <!--                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">-->
            <!--                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-calendar"></i> Filtrar Histórico</h6>-->
            <!--                    <a href="#"-->
            <!--                       onclick="$('.tbl-filtro-posisic .card-body').toggleClass('d-md-block d-none');return false">-->
            <!--                        <i class="fa fa-caret-down"></i>-->
            <!--                    </a>-->
            <!--                </div>-->
            <!--                &lt;!&ndash; Card Body &ndash;&gt;-->
            <!--                <div class="card-body d-md-block d-none">-->

            <!--                    <div class="row" v-if="!veiculo">-->
            <!--                        <div class="col-md-12">-->
            <!--                            <h5>Selecionar veículo acima <i class="fa fa-hand-point-up"></i></h5>-->
            <!--                        </div>-->
            <!--                    </div>-->

            <!--                    <template v-else>-->
            <!--                        <div class="row">-->

            <!--                            <div class="col-md-6 form-group">-->

            <!--                                <label>Data Inícial</label>-->
            <!--                                <div class="input-group">-->
            <!--                                    <date-picker :bootstrap-styling="true"-->
            <!--                                                 :class="isValid('data_inicio')"-->
            <!--                                                 :config="{ format : 'DD/MM/YYYY',inline:false}"-->
            <!--                                                 v-mask="'##/##/####'"-->
            <!--                                                 v-model="config.data_inicial"-->
            <!--                                                 placeholder="Data Inicial"-->
            <!--                                                 title="Data Inicial"-->
            <!--                                                 name="data_inicial"></date-picker>-->

            <!--                                    <span class="input-group-append">-->
            <!--                                               <span class="input-group-text">-->
            <!--                                                   <i class="fa fa-calendar"></i>-->
            <!--                                               </span>-->
            <!--                                        </span>-->
            <!--                                </div>-->
            <!--                            </div>-->

            <!--                            <div class="col-md-6 form-group">-->

            <!--                                <label>Data Final</label>-->
            <!--                                <div class="input-group">-->
            <!--                                    <date-picker :bootstrap-styling="true"-->
            <!--                                                 :class="isValid('data_inicio')"-->
            <!--                                                 :config="{ format : 'DD/MM/YYYY'}"-->
            <!--                                                 v-mask="'##/##/####'"-->
            <!--                                                 v-model="config.data_final"-->
            <!--                                                 placeholder="Data Inicial"-->
            <!--                                                 title="Data Final"-->
            <!--                                                 name="data_final"></date-picker>-->

            <!--                                    <span class="input-group-append">-->
            <!--                                               <span class="input-group-text">-->
            <!--                                                   <i class="fa fa-calendar"></i>-->
            <!--                                               </span>-->
            <!--                                        </span>-->
            <!--                                </div>-->
            <!--                            </div>-->

            <!--                        </div>-->

            <!--                        <div class="row">-->
            <!--                            <div class="col-md-4 form-group" style="margin-bottom: 0px">-->
            <!--                                <button type="button"-->
            <!--                                        :disabled="!veiculo"-->
            <!--                                        @click.prevent.stop="carregarHistorico"-->
            <!--                                        class="btn btn-success"><i class="fa fa-search"></i></button>-->
            <!--                            </div>-->
            <!--                        </div>-->
            <!--                    </template>-->

            <!--                </div>-->
            <!--            </div>-->


            <!--            <div class="card shadow mb-4" v-show="buscar_historico">-->

            <!--                &lt;!&ndash; Card Header - Dropdown &ndash;&gt;-->
            <!--                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">-->
            <!--                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-calendar"></i> Histório de Posições-->
            <!--                    </h6>-->
            <!--                </div>-->
            <!--                &lt;!&ndash; Card Body &ndash;&gt;-->
            <!--                <div class="card-body" style="">-->

            <!--                    <nav aria-label="Page navigation example" v-if="historico.last_page > 1">-->

            <!--                        <ul class="pagination justify-content-center">-->

            <!--                            <li class="page-item">-->
            <!--                                <a class="page-link"-->
            <!--                                   @click.prevent="config.page > 1 ? config.page&#45;&#45; : null"-->
            <!--                                   href="#"-->
            <!--                                   aria-label="Previous">-->
            <!--                                    <span aria-hidden="true">&laquo;</span>-->
            <!--                                    <span class="sr-only">Voltar</span>-->
            <!--                                </a>-->
            <!--                            </li>-->

            <!--                            <li v-for="i in historico.last_page"-->
            <!--                                :class="{active:i==config.page}"-->
            <!--                                class="page-item">-->
            <!--                                <a class="page-link"-->
            <!--                                   @click.prevent="config.page=i" href="#">{{ i }}</a>-->
            <!--                            </li>-->


            <!--                            <li class="page-item">-->
            <!--                                <a class="page-link"-->
            <!--                                   @click.prevent="config.page < historico.last_page ? config.page++ : null"-->
            <!--                                   href="#" aria-label="Next">-->
            <!--                                    <span aria-hidden="true">&raquo;</span>-->
            <!--                                    <span class="sr-only">Proximo</span>-->
            <!--                                </a>-->
            <!--                            </li>-->
            <!--                        </ul>-->

            <!--                    </nav>-->


            <!--                </div>-->
            <!--            </div>-->

        </div>


    </div>
</template>


<script>

    import Util from './../../../util.js';
    import ReverseGeocode from "../../../directives/ReverseGeocode";
    import GoogleMapsLink from "../../geral/googleMapsLink";
    // import BrazilStates from './../../../assets/frota/brazil-states.geojson';

    var imgIconeCar = require('./../../../assets/frota/icones/car01.svg');

    var iconCarLigado = require('./../../../assets/frota/icones/carro-ligado.png');
    var iconCarDesligado = require('./../../../assets/frota/icones/carro-desligado.png');

    export default {


        name: 'Painel',
        components: {GoogleMapsLink},
        directives: {
            geocode: ReverseGeocode
        },

        data: function () {
            return {

                registro: {},

                veiculos: [],
                mapCanvas: null,
                veiculo: null,
                markers: [],
                config: {
                    page: 1,
                    data_inicial: '',
                    data_final: '',
                    items_por_pagina: 10
                },

                data_dia_filtro: null,

                historico: {
                    data: [],
                    page: 1
                },

                buscar_historico: false,

                theLine: null,
                auxLayers: [],

                reverse_geocode: null
            }
        },
        created: function () {


            var self = this;

            if (_.get(this.$root, 'CLIENTE.id')) {
                this.getVeiculos();
            }

            this.$root.$watch('CLIENTE.id', () => {

                self.getVeiculos();

            });

        },


        mounted: function () {


            this.mapCanvas = Util.Leaflet.initMap('mapa', 4);

            // L.geoJSON(BrazilStates, {
            //     style(f) {
            //         return {
            //             fillColor: Util.getRandomColor(),
            //             fillOpacity:0.6
            //         }
            //     }
            // }).addTo(this.mapCanvas);


        },

        computed: {
            veiculosCmpt: function () {
                return _.map(this.veiculos, function (veiculo) {
                    return {
                        veiculo_id: veiculo.id,
                        label: veiculo.numero_frota + ' ' + veiculo.placa,
                        posicao: veiculo.last_logs
                    }
                })
            }
        },

        watch: {
            veiculos: {
                deep: true,
                handler: function () {
                    this.renderIcons();

                    //se cliente possuir somente um veiculo
                    //selecionar automaticamente
                    if (this.veiculos.length == 1) {
                        this.veiculo = this.veiculos[0];
                    }
                }
            },

            /**
             * Quando um veiculo é selecionado
             * @param veiculo
             */
            veiculo: function (v) {

                if (v.last_logs) {

                    this.data_dia_filtro = moment(v.last_logs.gps_timestamp).format('YYYY-MM-DD');
                    this.carregarHistorico();
                    // this.carregarGeoCode();

                    this.zoomTo(this.veiculo.id);

                    // $([document.documentElement, document.body]).animate({
                    //     scrollTop: $("#mapa").offset().top - 80
                    // }, 2000);


                } else {
                    this.alertErro('Dados de Rastramento inesistentes.');
                }
            },
            historico: {
                handler: function () {
                    var latlngs = [];

                    for (var i in this.auxLayers) this.mapCanvas.removeLayer(this.auxLayers[i]);

                    for (var i in this.historico.data) {
                        var pos = this.historico.data[i];
                        var auxLatLng = [pos.gps_lat.toFixed(5), pos.gps_lng.toFixed(5)];
                        latlngs.push(auxLatLng);

                        var auxGpsPont = L.circle(auxLatLng, {radius: 2, color: '#3388ff'})
                            .addTo(this.mapCanvas);

                        this.auxLayers.push(auxGpsPont);

                        // L.circleMarker(auxLatLng, {radius: 5}).addTo(this.mapCanvas);
                    }

                    if (this.theLine) {
                        this.theLine.setLatLngs(latlngs);
                    } else {
                        this.theLine = L.polyline(latlngs, {
                            color: '#1988ff',
                            // opacity: '.7',
                            // dashArray: '5, 5', dashOffset: '5',

                            //   color: 'blue',
                            weight: '6.0',
                            opacity: '1',
                            lineCap: 'round',
                            lineJoin: 'round',
                            dashArray: '4 4',
                            dashOffset: '10'

                        }).addTo(this.mapCanvas);
                    }

                    // this.mapCanvas.fitBounds(latlngs);
                }
            },
            'config.page': function () {

                this.carregarHistorico();
            }
        },

        methods: {

            getVeiculos() {
                this.doGet('frota/veiculos/buscar', {
                    paginacao: false,
                    include: [],
                    filtro: {
                        cliente_id: this.$root.CLIENTE.id,
                    }
                }).dthen('veiculos');
            },

            renderIcons: function () {
                for (var i in this.veiculosCmpt) {
                    var v = this.veiculosCmpt[i];
                    this.createOrUpdateIcone(v);
                }
            },

            getLayerAux: function (veiculo_id) {

                var marker = null;
                for (var i in this.markers) {
                    if (this.markers[i].veiculo_id == veiculo_id) {
                        return this.markers[i];
                    }
                }

                return marker;

            },

            zoomTo: function (veiculo_id) {

                var aux = this.getLayerAux(veiculo_id);
                if (aux && aux.marker) this.mapCanvas.flyTo(aux.marker.getLatLng(), 16, {animate: false});

            },

            createOrUpdateIcone: function (veiculo) {

                var aux = this.getLayerAux(veiculo.veiculo_id);
                var marker = aux ? aux.marker : null;

                if (veiculo.posicao) {

                    var imgIconeCar = veiculo.posicao.pos_chave == 1 ? iconCarLigado : iconCarDesligado;

                    var template = `<div><div class="label">${veiculo.label}
                            </div><img src="${imgIconeCar}"></div>`;

                    var divIcon = L.divIcon({
                        className: 'teste',
                        html: template
                    });

                    var markerOptions = {icon: divIcon};
                    var latLng = [veiculo.posicao.gps_lat.toFixed(5),
                        veiculo.posicao.gps_lng.toFixed(5)];

                    if (marker) {
                        marker.options.icon.options.html = divIcon.options.html;
                        marker.setLatLng(latLng);
                    } else {
                        var marker = L.marker(latLng, markerOptions);
                        marker.addTo(this.mapCanvas);
                        this.markers.push({
                            veiculo_id: veiculo.veiculo_id,
                            marker: marker
                        });
                    }
                }
            },

            carregarHistorico: function () {

                var aux = moment(this.data_dia_filtro);

                var options = {
                    veiculo_id: this.veiculo.id,
                    include: ['veiculo'],
                    page: this.config.page,
                    data_inicial: aux.format('DD/MM/YYYY'),
                    data_final: aux.format('DD/MM/YYYY'),
                    items_por_pagina: this.config.items_por_pagina
                };

                //mostrar tabela de lgos abaixo do mapa
                this.buscar_historico = true;

                this.operacao = 'historico';


                this.doGet('frota/logs/buscar', options)
                    .then(historico => {
                        this.historico = historico;
                    })
                    .always(() => {
                        this.operacao = '';
                    });
            },

            limpar: function () {

                this.validationResponse = {};
                this.aux_sel_cliente = null;

            }
        }
    }


</script>


<style lang="scss">

    .teste img {

        height: 50px;
    }

    .teste .label {
        position: absolute;
        width: 100px;
        background-color: #fff;
        color: #000000c7;
        padding: 0px;
        font-size: 12px;
        text-align: center;
        border: 1px solid #000000b3;
        font-weight: bold;
        border-radius: 5px;
        letter-spacing: 2px;
        top: 7px;
        left: 50px;
        /* transform: scale(0.6); */
        box-shadow: 2px 2px 5px #000000a1;
    }

    .color_green {
        color: green;
    }

    .color_red {
        color: red;
    }


    .historico {
        background-color: #03a9f440;
        color: #3F51B5;
        padding: 5px;
        vertical-align: middle;

        .ajustar {
            margin-left: auto;
            display: inherit;
        }

    }
</style>
