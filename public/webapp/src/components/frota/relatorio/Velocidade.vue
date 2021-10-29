<template>

    <div>
        <div class="print-hide">
            <b-card class="shadow-lg mb-4">
                <template #header>
                    <i class="fa fa-chart-area"></i> {{titulo}}
                </template>

                <template #default>

                    <div class="row">

                        <div class="form-group"
                             :class="c.bclass ? c.bclass : 'col'"
                             v-for="c in scheme">

                            <label>{{c.label}}</label>

                            <component
                                :is="c.type ? c.type : 'input-text' "
                                v-bind="c.options"
                                :class="{'is-invalid': l_.get(validationResponse,c.name),'form-control':c.type=='the-mask' }"
                                :cliente_id="filtro.cliente_id"
                                v-model="filtro[c.name]"></component>

                            <div class="invalid-feedback visible"
                                 v-if="l_.get(validationResponse,c.name)">
                                {{l_.get(validationResponse,c.name).join('')}}
                            </div>

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
            </b-card>
        </div>

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
                            {{titulo}}
                        </template>
                        <template #subtitle>
                            VEÍCULO: {{result.veiculo.placa}}
                            PERÍODO: {{filtro.data_inicial}} à {{filtro.data_final}}
                        </template>

                        <b-overlay :show="ajaxStatus  && operacao == 'gerar'"
                                   rounded
                                   opacity="0.6"
                                   spinner-variant="primary">

                            <table class="alinhar table table-bordered table-sm  table-hover">
                                <thead>
                                <tr>
                                    <th>DATA E HORA</th>
                                    <th>VELOCIDADE</th>
                                    <th>ENDEREÇO</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="t in result.formatted">
                                    <td style="white-space: nowrap;text-align: center">
                                        {{t.gps_timestamp | dateTimeFormat('DD/MM/YYYY HH:mm')}}
                                    </td>
                                    <td style="white-space: nowrap;text-align: center">
                                        {{t.gps_speed}} km/h
                                    </td>

                                    <td>
                                        <GoogleMapsLink :lat="t.gps_lat" :lng="t.gps_lng">
                                            <span v-geocode="[t.gps_lat,t.gps_lng]"></span>
                                        </GoogleMapsLink>
                                    </td>
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
        name: "Velocidade",
        components: {GoogleMapsLink, BoxImpressao, SingleForm, Card},
        data() {

            return {
                titulo: 'Relatório de Velocidade',
                filtro: {
                    cliente_id: null,
                    veiculo_id: '17',
                    data_inicial: moment().subtract(31, 'DAY').format('YYYY-MM-DD'),
                    data_final: moment().format('YYYY-MM-DD')
                },
                scheme: [
                    {
                        type: InputVeiculos,
                        label: 'Veículo',
                        name: 'veiculo_id',
                        options: {ignorar_cliente: false}
                    },

                    {
                        type: 'the-mask',
                        label: 'Velocidade',
                        name: 'velocidade',
                        options: {
                            'mask': '#####'
                        },
                    },

                    {
                        type: 'b-form-datepicker',
                        label: 'Data Inicial',
                        name: 'data_inicial',
                        options: {
                            locale: 'pt-BR',
                            max: moment().toDate()
                        }
                    },

                    {
                        type: 'b-form-datepicker',
                        label: 'Data Final',
                        name: 'data_final',
                        options: {
                            locale: 'pt-BR',
                            max: moment().toDate()
                        }
                    }
                ],

                result: {
                    veiculo: {},
                    formatted: [],
                }
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

                this.doGet('relatorio/telemetria/velocidade', this.filtro)
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
