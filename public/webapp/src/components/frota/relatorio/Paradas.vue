<template>

    <div>
        <Card class="print-hide">
            <template #header>
                <i class="fa fa-chart-area"></i> Relatório de Paradas
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
                              :disabled="ajaxStatus && operacao=='gerar'"
                              variant="success">
                        <i class="fa fa-search"></i>
                        GERAR
                    </b-button>
                    <b-button @click="limpar"><i class="fa fa-recycle"></i> LIMPAR</b-button>
                </b-btn-group>
            </template>
        </Card>

        <Card>
            <template #header>
                Relatório Gerado
            </template>
            <template #default>

                <b-button variant="success"
                          :disabled="!result.length"
                          @click="imprimir">
                    <i class="fa fa-print"></i> Imprimir
                </b-button>
                <hr class="print-hide">

                <div class="table-responsive-md">
                    <BoxImpressao>

                        <b-overlay :show="ajaxStatus && operacao== 'gerar'"
                                   rounded
                                   opacity="0.6"
                                   spinner-variant="primary">

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>PLACA</th>
                                    <th>INICIO</th>
                                    <th>FIM</th>
                                    <th>TEMPO</th>
                                    <th>ENDEREÇO</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="t in result">
                                    <td>{{t[0]['placa']}}</td>
                                    <td>{{t[0]['gps_timestamp'] | dateTimeFormat('DD/MM HH:mm')}}</td>
                                    <td>{{t[1]['gps_timestamp'] | dateTimeFormat('DD/MM HH:mm')}}</td>
                                    <td>{{t[2]}} min</td>
                                    <td><span v-geocoode="[t[0].gps_lat,t[0].gps_lng]"></span></td>
                                </tr>
                                </tbody>
                            </table>
                        </b-overlay>

                    </BoxImpressao>
                </div>
            </template>

            <template #footer>
                <b-button variant="success"
                          :disabled="!result.length"
                          @click="imprimir">
                    <i class="fa fa-print"></i> Imprimir
                </b-button>
            </template>
        </Card>

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

    export default {
        name: "Paradas",
        components: {BoxImpressao, SingleForm, Card},
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
                result: [],
                total: 0,
                cache: {}
            }
        }
        ,
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
                this.doGet('relatorio/telemetria/paradas', this.filtro)
                    .then((result) => {
                        this.result = result;
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
            geocoode: ReverseGeocode
        }
    }
</script>

<style scoped>

</style>
