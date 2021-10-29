<template>

    <div>
        <Card class="print-hide">
            <template #header>
                <i class="fa fa-chart-area"></i> Relatório Analítico
            </template>

            <template #default>

                <div class="row">

                    <div class="form-group"
                         :class="c.bclass ? c.bclass : 'col-md-4'"
                         v-for="c in scheme">

                        <label>{{c.label}}</label>

                        <component
                            :is="c.type ? c.type : 'input-text' "
                            :config="c"
                            :cliente_id="filtro.cliente_id"
                            :errors="l_.get(validationResponse,c.name)"
                            v-model="filtro[c.name]"></component>

                    </div>

                </div>


                <b-card-text align="right">
                    <i class="fa fa-info"></i> Intervalo de 31 dias no máximo.
                </b-card-text>


            </template>

            <template #footer>
                <b-btn-group>
                    <b-button @click="gerarRelatorio"
                              :disabled="ajaxStatus && operacao == 'gerar'"
                              variant="success">
                        <i class="fa fa-search"></i>
                        GERAR
                    </b-button>
                    <b-button @click="limpar"><i class="fa fa-recycle"></i> LIMPAR</b-button>
                </b-btn-group>
            </template>
        </Card>

        <b-card class="shadow-lg">
            <template #header>
                Relatório Gerado
                <b-button variant="secondary"
                          class="float-right"
                          size="sm"
                          :disabled="!result.formatted"
                          @click="imprimir">
                    <i class="fa fa-print"></i> Imprimir
                </b-button>

            </template>
            <template #default>

                <hr class="print-hide">

                <div class="table-responsive-md">
                    <BoxImpressao>

                        <template #title>
                            RELATÓRIO ANALÍTICO
                        </template>
                        <template #subtitle>
                            VEÍCULO: {{result.veiculo.placa}}
                            PERÍODO: {{filtro.data_inicial}} à {{filtro.data_final}}
                        </template>

                        <b-overlay :show="ajaxStatus  && operacao == 'gerar'"
                                   rounded
                                   opacity="0.6"
                                   spinner-variant="primary">
                            <table class="alinhar table table-bordered table-sm table-hover">
                                <thead>
                                <tr>
                                    <th>SITUACAO</th>
                                    <th>INICIO</th>
                                    <th>FIM</th>
                                    <th>TEMPO</th>
                                    <th>KM</th>
                                    <th>ENDEREÇO</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="t in result.formatted">
                                    <td style="white-space: nowrap">{{t.status}}</td>
                                    <td style="white-space: nowrap;text-align: center">
                                        {{t.start | dateTimeFormat('DD/MM HH:mm')}}
                                    </td>
                                    <td style="white-space: nowrap;text-align: center">
                                        {{t.stop | dateTimeFormat('DD/MM HH:mm')}}
                                    </td>
                                    <td style="white-space: nowrap;text-align: center">
                                        {{t.diff < 60 ? `${t.diff} min` : (t.diff/60).toFixed(0)+' Hrs' }}
                                    </td>
                                    <td style="white-space: nowrap;text-align: center">
                                        <span v-if="t.status == 'EM DESLOCAMENTO'">
                                         {{t.dist.toFixed(0)}} KM
                                        </span>
                                        <span v-else>
                                           --
                                        </span>
                                    </td>
                                    <td>
                                        <GoogleMapsLink :lat="t.lat" :lng="t.lng">
                                            <span v-geocode="[t.lat,t.lng]"></span>
                                        </GoogleMapsLink>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-right" colspan="5">TEMPO ESTACIONADO</td>
                                    <td class="table-primary">{{result.tempo_estacionado}} Hrs</td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">TEMPO EM DESLOCAMENTO</td>
                                    <td class="table-primary">{{result.tempo_deslocamento}} Hrs</td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">DISTÂNCIA PERCORRIDA</td>
                                    <td class="table-primary">{{result.distancia_deslocamento}} km</td>
                                </tr>
                                </tbody>
                            </table>
                        </b-overlay>

                    </BoxImpressao>
                </div>
            </template>

            <template #footer>
                <b-button variant="secondary"
                          :disabled="!result.formatted"
                          @click="imprimir">
                    <i class="fa fa-print"></i> Imprimir
                </b-button>
            </template>
        </b-card>

    </div>

</template>

<script>
    import Card from "../geral/Card";
    import SingleForm from "../geral/SingleForm";
    import BoxImpressao from "./BoxImpressao";
    import InputVeiculos from "../frota/singleForm/InputVeiculos";
    import InputDate from "../geral/singleForm/InputDate";
    import Util from "../../util";
    import ReverseGeocode from "../../../directives/ReverseGeocode";
    import GoogleMapsLink from "../geral/googleMapsLink";

    export default {
        name: "Analitico",
        components: {GoogleMapsLink, BoxImpressao, SingleForm, Card},
        data() {

            return {
                filtro: {
                    cliente_id: null,
                    veiculo_id: '17',
                    data_inicial: moment().subtract(31, 'DAY').format('DD/MM/YYYY'),
                    data_final: moment().format('DD/MM/YYYY')
                },
                scheme: [
                    {
                        type: InputVeiculos,
                        label: 'Veículo',
                        name: 'veiculo_id',
                        bind: ['cliente_id'],
                        attribs: {ignorar_cliente: false}
                    },
                    {
                        type: InputDate,
                        label: 'Data Inicial',
                        name: 'data_inicial',
                        attribs: {
                            datePickerConfig: {maxDate: moment()}
                        }
                    },

                    {
                        type: InputDate,
                        label: 'Data Final',
                        name: 'data_final',
                        attribs: {
                            datePickerConfig: {maxDate: moment()}
                        }
                    }
                ],
                table: {
                    fields: [

                        {
                            key: 'day',
                            label: 'Dia',
                            sortable: true
                        },
                        {
                            key: 'placa',
                            label: 'Placa',
                            sortable: false
                        },
                        {
                            label: 'KM (km)',
                            key: 'distance',
                            sortable: true
                        }
                    ]
                },
                result: {
                    veiculo: {},
                    formatted: [],
                    tempo_estacionado: 0,
                    tempo_deslocamento: 0,
                    distancia_deslocamento: 0,
                },
                total: 0,
                cache: {}
            }
        },
        created() {

            var self = this;

            this.setUpClienteId();

            this.$root.$watch('CLIENTE.id', (id) => {
                self.setUpClienteId();
            });
        },
        methods: {
            setUpClienteId() {
                this.filtro.cliente_id = this.$root.CLIENTE.id;
            },
            gerarRelatorio() {

                this.operacao = 'gerar';

                this.doGet('relatorio/telemetria/analitico', this.filtro)
                    .then((result) => {
                        this.result = result;
                    }).always(() => {
                    this.operacao = 'gerar';
                });
            },
            imprimir() {
                window.print();
            },
            limpar() {
                var defaultDataa = this.$options.data();
                this.filtro = defaultDataa.filtro;
                this.result = defaultDataa.result;
                this.total = defaultDataa.total;
                this.validationResponse = {};
                this.setUpClienteId();
            },

        },
        directives: {
            geocode: ReverseGeocode
        }
    }
</script>

<style>


    table.alinhar tr td {
        vertical-align: middle;
    }
</style>
