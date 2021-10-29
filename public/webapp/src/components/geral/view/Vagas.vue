<template>

    <b-card class="shadow-lg">

        <template #header>
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fa fa-pager"></i> Distribuição de Vagas por Departamento</h6>
        </template>

        <template #default>

            <form autocomplete="off">

                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <b-overlay
                    :show="ajaxStatus"
                    rounded
                    opacity="0.6"
                    spinner-variant="primary">

                    <b-row>
                        <b-col>
                            <b-form-group label="EMPRESA">
                                <BtnBuscarClientes @select="empresa=$event">
                                    <i class="fa fa-building"></i> SELECIONAR EMPRESA
                                </BtnBuscarClientes>
                            </b-form-group>
                        </b-col>

                        <b-col class="position-relative" v-if="empresa">

                            <b-form-group label="Departamento">

                                <b-button block
                                          @click="showDepartamentosTreView=!showDepartamentosTreView"
                                          :variant="departamento && departamento.id  ? 'success' :'secondary' ">
                                    {{departamento ? departamento.nome : 'Selecionar Departamento'}}
                                </b-button>

                            </b-form-group>

                            <div v-show="showDepartamentosTreView"
                                 style="z-index: 100"
                                 class="shadow position-absolute overflow-hidden bg-white w-100 border mt-n3">
                                <TreeDepartamento style="padding: 0px 8px;"
                                                  @select="selDepartamento"
                                                  :departamentos="departamentos"></TreeDepartamento>
                            </div>

                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col>

                        <span v-if="departamento">
                             <b-card>

                            <template #header><i class="fa fa-sitemap"></i> ORGANIZACIONAL</template>


                            <b-form-group label="Vaga ascendente">
                                <b-select v-model="vagaDepartamento.vaga_departamento_pai">
                                    <option value="">SUPERIOR (GESTOR)</option>
                                    <option v-for="v in vagaDepartamentos"
                                            :value="v.id">{{v.vaga.nome}}
                                    </option>
                                </b-select>
                            </b-form-group>

                            <b-form-group label="Nome da Vaga">
                                <b-input v-model="vagaDepartamento.nome" :state="validationResponse['nome'] ? false : undefined"></b-input>
                                <b-form-invalid-feedback v-text="l_.join(validationResponse['nome'])"></b-form-invalid-feedback>
                            </b-form-group>

                            <b-form-group label="Descrição">
                                <b-textarea v-model="vagaDepartamento.descricao"></b-textarea>
                            </b-form-group>

                            <b-form-group label="Usuário Vinculado">
                            <span v-if="vagaDepartamento.usuario_id && usuario">
                                <b-btn-group>
                                    <b-button variant="primary"><i
                                        class="fa fa-id-badge"></i> {{usuario.nome}}</b-button>
                                    <b-button title="Remover Usuário da vaga"
                                              @click="vagaDepartamento.usuario_id = ''"
                                              variant="danger"><i
                                        class="fa fa-times"></i> </b-button>
                                </b-btn-group>
                            </span>

                                <BtnBuscarUsuarios v-else
                                                   @select="usuario=$event">
                                    <i class="fa fa-search"></i> PROVER VAGA
                                </BtnBuscarUsuarios>
                            </b-form-group>

                            <template #footer>
                                <b-button-group>
                                    <b-button variant="success"
                                              @click="salvar">SALVAR
                                    </b-button>
                                    <b-button :disabled="!vagaDepartamento.id"
                                              @click="excluir"
                                              variant="danger">EXCLUIR
                                    </b-button>
                                    <b-button @click="limparVagaDepartamento" variant="secondary">LIMPAR</b-button>
                                </b-button-group>
                            </template>

                        </b-card>
                        </span>

                        </b-col>
                        <b-col v-if="departamento">

                            <b-card :no-body="vagaDepartamentos.length === 0 ? false : true">
                                <template #header><i class="fa fa-address-card"></i> PREENCHIMENTO DE VAGAS
                                </template>

                                <span v-if="vagaDepartamentos.length === 0">
                                <h4 class="text-center"> Nenhuma Vaga Criada</h4>
                            </span>

                                <TreeVagaDepartamento v-else
                                                      @select="editarVagaDepartamento"
                                                      :vaga-departamentos="vagaDepartamentos"></TreeVagaDepartamento>

                            </b-card>

                        </b-col>
                    </b-row>

                </b-overlay>

            </form>

        </template>

    </b-card>

</template>

<script>

    import BtnBuscarClientes from "../BtnBuscarClientes";
    import TreeDepartamento from "../TreeDepartamento";
    import SeletorClientes from "../SeletorClientes";
    import BtnBuscarUsuarios from "../BtnBuscarUsuarios";
    import TreeVagaDepartamento from "../TreeVagaDepartamento";

    export default {
        name: "Vagas",
        components: {TreeVagaDepartamento, BtnBuscarUsuarios, SeletorClientes, TreeDepartamento, BtnBuscarClientes},

        data() {
            return {
                empresa: {
                    id: null
                },
                departamentos: null,
                departamento: null,
                usuario: null,
                vagaDepartamento: {
                    id: null,
                    departamento_id: null,
                    vaga_id: null,
                    usuario_id: null,
                    vaga_departamento_pai: null,
                    nome: null,
                    descricao: null
                },
                vagaDepartamentos: [],
                showDepartamentosTreView: false
            }
        },
        watch: {
            'empresa.id'(empresaId) {
                if (empresaId) {
                    this.carregarDepartamentos(empresaId);
                } else {
                    this.empresa = this.$options.data().empresa;
                    this.departamentos = this.$options.data().departamentos;
                    this.departamento = this.$options.data().departamento;
                    this.usuario = this.$options.data().usuario;
                    this.vagaDepartamento = this.$options.data().vagaDepartamento;
                    this.vagaDepartamentos = this.$options.data().vagaDepartamentos;
                    this.showDepartamentosTreView = this.$options.data().showDepartamentosTreView;
                }
            },
            'departamento.id'(departamentoId) {
                if (departamentoId) {
                    this.carregarVagasDepartamento(departamentoId);
                    this.vagaDepartamento.departamento_id = departamentoId;
                    // this.carregarVagasDepartamento(departamentoId);
                }
            },

            'usuario.id'(usuarioId) {
                if (usuarioId) {
                    this.vagaDepartamento.usuario_id = usuarioId;
                }
            }
        },

        created: function () {


        },

        methods: {
            salvar: function () {

                this.doPost('geral/vaga-departamento/salvar', this.vagaDepartamento)
                    .then(function () {
                        this.alertSucesso();
                        this.carregarVagasDepartamento(this.vagaDepartamento.departamento_id);
                        this.limparVagaDepartamento();
                    });
            },
            excluir: function () {

                this.confirmar(r => {
                    if (r)
                        this.doPost('geral/vaga-departamento/excluir', this.vagaDepartamento)
                            .then(function () {
                                this.alertSucesso();
                                this.carregarVagasDepartamento(this.vagaDepartamento.departamento_id);
                            });
                }, 'Confirmar exclusão de vaga?');

            },

            carregarDepartamentos(empresaId) {
                this.doGet('geral/departamentos/buscar', {
                    paginate: false,
                    empresa_id: empresaId
                }).dthen('departamentos');
            },

            carregarVagasDepartamento(departamento_id) {

                this.doGet('geral/vaga-departamento/buscar', {

                    filtro: {departamento_id: departamento_id}

                }).dthen('vagaDepartamentos');

            },
            selDepartamento(departamento) {

                this.departamento = departamento;
                this.showDepartamentosTreView = false;

                this.vagaDepartamento = this.$options.data().vagaDepartamento;
                // this.usuario = this.$options.data().usuario;


            },
            editarVagaDepartamento(vagaDarta) {

                this.vagaDepartamento.id = vagaDarta.id;
                this.vagaDepartamento.nome = vagaDarta.vaga.nome;
                this.vagaDepartamento.descricao = vagaDarta.vaga.descricao;
                this.vagaDepartamento.descricao = vagaDarta.vaga.descricao;
                this.vagaDepartamento.usuario_id = vagaDarta.usuario_id;

                this.usuario = vagaDarta.usuario ?? null;

                this.vagaDepartamento.vaga_departamento_pai = '';

                if (vagaDarta.vaga_departamento_pai) {
                    this.vagaDepartamento.vaga_departamento_pai = vagaDarta.vaga_departamento_pai;
                }
            },

            limparVagaDepartamento() {

                this.vagaDepartamento.id = '';
                this.vagaDepartamento.nome = '';
                this.vagaDepartamento.descricao = '';
                this.vagaDepartamento.descricao = '';
                this.vagaDepartamento.usuario_id = '';
                this.vagaDepartamento.vaga_departamento_pai = '';
            }
        }
    }
</script>

<style scoped>

</style>
