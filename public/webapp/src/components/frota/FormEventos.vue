<template>

    <b-card class="shadow-lg">

        <template #header>
            <i class="fa fa-calendar-check"></i> Cadastro de Eventos/Alertas
        </template>

        <template #default>

            <b-form-row>

                <b-col label-for="evento" md="4">
                    <b-form-group label="Evento">
                        <b-form-select :options="tipoEventos"
                                       id="evento"
                                       nome="evento"
                                       :class="validationResponse['evento'] ? 'is-invalid' :''"
                                       class="form-control"
                                       v-model="registro.evento"></b-form-select>

                        <div class="invalid-feedback d-block"
                             v-if="validationResponse['evento']">
                            {{validationResponse['evento']}}
                        </div>

                    </b-form-group>
                </b-col>

                <b-col label-for="vel_min"
                       v-if="registro.evento == 'VELOCIDADE'"
                       md="2">
                    <b-form-group description="Velocidade miníma"
                                  :state="!l_.has(validationResponse,'vel_min')"
                                  label="Vel miníma">
                        <the-mask mask="#####"
                                  id="vel_min"
                                  nome="vel_min"
                                  :class="validationResponse['vel_min'] ? 'is-invalid' :''"
                                  class="form-control"
                                  v-model="registro.vel_min"></the-mask>
                        <div class="invalid-feedback d-block"
                             v-if="validationResponse['vel_min']">
                            {{validationResponse['vel_min']}}
                        </div>

                    </b-form-group>
                </b-col>

                <b-col label-for="vel_max"
                       v-if="registro.evento == 'VELOCIDADE'"
                       md="2">
                    <b-form-group description="Velocidade máxima" label="Vel máxima">
                        <the-mask mask="#####"
                                  id="vel_max"
                                  nome="vel_max"
                                  :class="validationResponse['vel_max'] ? 'is-invalid' :''"
                                  class="form-control"
                                  v-model="registro.vel_max"></the-mask>
                        <div class="invalid-feedback d-block"
                             v-if="validationResponse['vel_max']">
                            {{validationResponse['vel_max']}}
                        </div>

                    </b-form-group>
                </b-col>

                <b-col label-for="tempo"
                       v-if="registro.evento == 'LIGADO E PARADO'"
                       md="2">
                    <b-form-group description="Tempo ligado parado (MINUTOS)"
                                  label="Minutos">
                        <the-mask mask="#####"
                                  id="tempo"
                                  name="tempo"
                                  :class="validationResponse['tempo'] ? 'is-invalid' :''"
                                  class="form-control"
                                  v-model="registro.tempo"></the-mask>

                        <div class="invalid-feedback d-block"
                             v-if="validationResponse['tempo']">
                            {{validationResponse['tempo']}}
                        </div>

                    </b-form-group>
                </b-col>

                <b-col label-for="valor_km"
                       v-if="registro.evento == 'KM'"
                       md="2">
                    <b-form-group label="KM (QUILOMETROS)">
                        <the-mask mask="#####"
                                  id="valor_km"
                                  name="valor_km"
                                  :class="validationResponse['valor_km'] ? 'is-invalid' :''"
                                  class="form-control"
                                  v-model="registro.valor_km"></the-mask>

                        <div class="invalid-feedback d-block"
                             v-if="validationResponse['valor_km']">
                            {{validationResponse['valor_km']}}
                        </div>

                    </b-form-group>
                </b-col>

                <b-col>
                    <b-form-group>
                        <label style="visibility: hidden">Salvar</label>
                        <b-button :disabled="registro.evento == ''"
                                  @click="salvar"
                                  variant="success"
                                  class="d-block"><i class="fa fa-save"></i> SALVAR
                        </b-button>
                    </b-form-group>
                </b-col>

            </b-form-row>

            <b-row>

                <b-col>

                    <table class="table table-bordered">

                        <thead>
                        <tr>
                            <th>Evento</th>
                            <th>Parametros</th>
                            <th>Ações</th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="ev in eventos">

                            <td>{{ev.evento}}</td>

                            <td>
                                <span
                                    v-if="ev.evento == 'VELOCIDADE'">Alertas para velocidade  entre <b>{{ev.vel_min}} e {{ev.vel_max}}</b> km/h</span>

                                <span
                                    v-if="ev.evento == 'IGNICAO'">Alertas quando o veículo <b>Ligar ou Desligar</b></span>

                                <span
                                    v-if="ev.evento == 'LIGADO E PARADO'">Alertas se veículo ligado e parado por <b>{{ev.tempo}}</b> minutos</span>

                                <span
                                    v-if="ev.evento == 'KM'">Alertas quando a QUILOMETRAGEM atinger <b>{{ev.valor_km}}</b> KM</span>

                            </td>

                            <td class="text-center">

                                <b-button-group>
                                    <b-button variant="info"
                                              @click="registro=l_.clone(ev)"><i class="fa fa-edit"></i></b-button>
                                    <b-button @click="excluir(ev)"
                                              variant="danger"><i class="fa fa-times"></i>
                                    </b-button>
                                </b-button-group>

                            </td>

                        </tr>

                        </tbody>

                    </table>

                </b-col>

            </b-row>

        </template>

    </b-card>

</template>

<script>

    import BtnBuscarVeiculos from "./BtnBuscarVeiculos";

    export default {

        name: "FormEventos",

        components: {BtnBuscarVeiculos},

        props: ['veiculo', 'grupoVeiculos'],

        data() {
            return {
                tipoEventos: [
                    {
                        value: '',
                        text: 'SELECIONAR'
                    },
                    'VELOCIDADE',
                    'IGNICAO',
                    'LIGADO E PARADO',
                    'KM'],

                registro: {
                    codigo: '',
                    evento: '',
                    veiculo_id: ''
                },
                eventos: []
            }
        },

        watch: {
            'veiculo.id'(veiculoId) {
                if (veiculoId) {
                    console.log('Carregando eventpos por veiculo');
                    this.carregarEventos();
                }
            },
            'grupoVeiculo.id'(grupoEveiculoId) {
                if (grupoEveiculoId) {
                    this.carregarEventos();
                }
            }
        },

        created() {
            this.carregarEventos();
        },

        computed: {},
        methods: {

            carregarEventos() {

                var filtro = null;

                /**
                 * listar eventos por veiculo
                 */
                if (_.get(this, 'veiculo.id')) {
                    filtro = {veiculo_id: this.veiculo.id};
                }

                /**
                 * Listar eventos por grupo de veiculos
                 */
                if (_.get(this, 'grupoEveiculo.id')) {
                    filtro = {grupo_veiculo_id: this.grupoEveiculo.id};
                }

                if (filtro) {

                    this.doGet('frota/eventos/buscar', {filtro: filtro, paginacao: 0})
                        .then(result => {
                            this.eventos = result;
                        });
                } else {
                    this.eventos = [];
                }
            },


            salvar: function () {

                this.operacao = 'salvar';

                this.registro.veiculo_id = this.veiculo.id;

                this.doPost('frota/eventos/salvar', this.registro)
                    .then(function (response) {

                        this.validationResponse = {};
                        this.registro = {};
                        this.alertSucesso();
                        this.carregarEventos();

                        this.$emit('saved', this.registro);

                    });

            },


            excluir: function (evento) {

                let self = this;

                this.operacao = 'excluir';

                this.confirmar(function (sim) {
                    if (sim) {
                        self.doPost('frota/eventos/excluir', evento)
                            .then(function () {
                                self.alertSucesso('Registro excluído com sucesso');
                                this.carregarEventos();
                                this.$emit('deleted', evento);

                            });
                    }

                    self.limpar();
                })

            },

            limpar: function () {

                this.registro = {};
                this.validationResponse = {};

            },

        }
    }
</script>

<style scoped>

</style>
