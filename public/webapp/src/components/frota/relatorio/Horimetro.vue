<template>

    <div>
        <Card class="print-hide">
            <template #header>
                <i class="fa fa-chart-area"></i> Relatório de Horímetro
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
                              :disabled="ajaxStatus"
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

                        <b-overlay :show="ajaxStatus"
                                   rounded
                                   opacity="0.6"
                                   spinner-variant="primary">


                            <b-table bordered hover
                                     :items="result"
                                     :fields="table.fields">
                                <template #bottom-row>

                                    <b-td colspan="2" align="right">Total:</b-td>
                                    <b-td align="left" variant="warning">{{total}} Horas</b-td>
                                </template>
                            </b-table>
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

    export default {
        name: "Horimetro",
        components: {BoxImpressao, SingleForm, Card},
        data() {

            return {
                filtro: {
                    cliente_id: null,
                    veiculo_id: '',
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
                            label: 'Horas',
                            key: 'distance',
                            sortable: true
                        }
                    ]
                },
                result: [],
                total: 0
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
                this.doGet('relatorio/telemetria/horimetro', this.filtro)
                    .then((result) => {
                        this.result = result.dias;
                        this.total = result.total;
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
            }
        }
    }
</script>

<style scoped>

</style>
