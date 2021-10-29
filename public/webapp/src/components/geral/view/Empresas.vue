<template>

    <div>

        <b-card class="shadow-lg mb-4" no-body>

            <template #header>
                <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-pager"></i> Cadastro de Empresas</h6>
            </template>

            <template #default>

                <b-tabs v-model="tab_index" card>

                    <b-tab>

                        <b-row>
                            <b-col md="3">
                                <b-card footer-text-variant="center"
                                        class="shadow-lg"
                                        body-class="grid_transp"
                                        body-text-variant="center">

                                    <b-card-img :width="'100%'"
                                                :src="urlFoto"></b-card-img>

                                    <template #footer>

                                        <button class="btn btn-info btn-sm"
                                                id="pick-avatar">ALTERAR FOTO
                                        </button>

                                    </template>

                                </b-card>
                            </b-col>
                            <b-col>
                                <SingleForm v-bind:scheme="campos"
                                            :model="registro"
                                            :errors="validationResponse"
                                            @update-model="registro"></SingleForm>

                            </b-col>
                        </b-row>


                        <template #title>
                            <i class="fa fa-building"></i> Empresa
                        </template>

                        <avatar-cropper
                            :labels="{submit:'ok',cancel:'fechar'}"
                            upload-form-name="files[]"
                            :upload-url="uploadUrl"
                            @uploaded="getImage"
                            trigger="#pick-avatar"/>

                    </b-tab>

                    <b-tab :disabled="!registro.id">
                        <template #title>
                            <i class="fa fa-sitemap"></i> Organograma
                        </template>

                        <b-row>

                            <b-col>
                                <b-card header="DEPARTAMENTO">


                                    <b-form-group label="DEP. SUPERIOR"
                                                  description="DEPARTAMENTO ASCENDENTE">

                                        <b-select size="departamento_pai_id"
                                                  v-model="departamento.departamento_pai_id">

                                            <option value="null">NENHUM (SUPERIOR)</option>

                                            <option v-for="d in departamentos"
                                                    :disabled="departamento.id == d.id"
                                                    :value="d.id">{{ d.nome }}
                                            </option>

                                        </b-select>

                                    </b-form-group>

                                    <b-form-group description="NOME DO DEPARTAMENTO"
                                                  label="NOME">
                                        <b-input type="text"
                                                 v-model="departamento.nome"
                                                 name="nome"></b-input>
                                    </b-form-group>

                                    <template #footer>

                                        <b-button-group>
                                            <b-button @click="salvarDepartamento"
                                                      title="Salvar"
                                                      variant="success"><i class="fa fa-save"></i> SALVAR
                                            </b-button>
                                            <b-button @click="excluirDepartamento"
                                                      title="Excluir Departamento"
                                                      :disabled="!departamento.id"
                                                      variant="danger"><i class="fa fa-times"></i> REMOVER
                                            </b-button>
                                            <b-button @click="departamento={}"
                                                      title="Limpar formulário"
                                                      variant="secondary">
                                                <i class="fa fa-recycle"></i> LIMPAR
                                            </b-button>
                                        </b-button-group>

                                    </template>
                                </b-card>
                            </b-col>

                            <b-col>

                                <b-card header="HIERARQUIA INSTITUCIONAL">

                                    <h5 v-if="!departamentos.length">
                                        Nenhum departamento cadastrado
                                    </h5>

                                    <div style="margin-left: -30px;margin-top: -20px">

                                        <TreeDepartamento v-on:select="departamento=l_.clone($event)"
                                                          :departamentos="departamentos"></TreeDepartamento>
                                    </div>
                                </b-card>
                            </b-col>

                        </b-row>


                    </b-tab>

                </b-tabs>

            </template>

            <template v-if="tab_index ==0" #footer>
                <div class="btn-group">

                    <button type="button"
                            @click="salvar"
                            :disabled="ajaxStatus && operacao == 'salvar'"
                            class="btn btn-success">SALVAR <i v-if="ajaxStatus && operacao=='salvar' "
                                                              class="fas fa-spinner fa-spin"></i>
                    </button>

                    <button type="button"
                            :disabled="( ajaxStatus && operacao == 'excluir' ) || !registro.id"
                            @click="excluir"
                            class="btn btn-danger">EXCLUIR <i v-if="ajaxStatus && operacao=='excluir' "
                                                              class="fas fa-spinner fa-spin"></i>
                    </button>

                    <button type="button"
                            @click="limpar"
                            class="btn btn-info">LIMPAR
                    </button>
                </div>
            </template>
        </b-card>

        <GridEmpresas ref="grid" @select="editar"></GridEmpresas>

    </div>
</template>

<script>
import Card from "./../Card";
import GridEmpresas from "./../GridEmpresas";
import SingleForm from "../SingleForm";
import TreeDepartamento from "../TreeDepartamento";
import defaultFoto from "@/assets/tema/fluxo/Logo-Empresa-sem-imagem.png";
import AvatarCropper from "vue-avatar-cropper";

export default {
    components: {TreeDepartamento, SingleForm, GridEmpresas, Card, AvatarCropper},
    data: function () {
        return {

            departamento: {
                id: '',
                empresa_id: '',
                nome: '',
                departamento_pai_id: '',
            },

            departamentos: [],

            registro: {
                "estado": "",
                "cidade": "",
                "nome": "",
                "tipo": "",
                "cpf_cnpj": "",
                "cep": "",
                "endereco": "",
                "complemento": "",
                "cadastrado_em": "",
                "fone1": "",
                "fone2": "",
                "fone3": "",
                "email": "",
                "url_foto": "",
                "foto_upload_id": ""
            },
            operacao: null,

            campos: [{
                'type': 'input-text',
                'label': 'Código',
                'name': 'id',
                'desc': 'Código do Cliente',
                'bclass': 'col-md-2',
                'html': 'texto',
                'attribs': {'readonly': 'readonly'}
            },
                {

                    'label': 'Nome',
                    'name': 'nome',
                    'desc': 'NOME DO CLIENTE',
                    'bclass': 'col-md-5',
                    'type': 'input-text',
                },
                {
                    'label': 'Pessoa',
                    'name': 'tipo',
                    'desc': 'Tipo de Pessoa',
                    'options': ['FISICA', 'JURIDICA', 'NA'],
                    'bclass': 'col-md-2',
                    'type': 'input-select',
                },
                {
                    'label': 'Cpf/Cnpj',
                    'name': 'cpf_cnpj',
                    'desc': 'Cpf/Cnpj',
                    'bclass': 'col-md-3',
                    'html': 'cpf-cnpj',
                    'type': 'input-cpf-cnpj'
                },
                {
                    'label': 'Cep',
                    'name': 'cep',
                    'desc': 'CEP',
                    'bclass': 'col-md-2',
                    'html': 'cep',
                    'type': 'input-cep'

                },
                {
                    'label': 'UF',
                    'name': 'estado',
                    'desc': 'Estado',
                    'bclass': 'col-md-2',
                    'type': 'input-estados',
                },
                {
                    'label': 'Cidade',
                    'name': 'cidade',
                    'desc': 'Cidade',
                    'bclass': 'col-md-2',
                    'type': 'input-cidades',
                    'bind': ['estado']
                },
                {
                    'label': 'Endereço',
                    'name': 'endereco',
                    'desc': 'Endereço',
                    'bclass': 'col-md-4',
                    'html': 'texto'
                },
                {
                    'label': 'Comp',
                    'name': 'complemento',
                    'desc': 'Complemento',
                    'bclass': 'col-md-2',
                    'html': 'texto'
                },
                {
                    'label': 'Dt Cadastro',
                    'name': 'cadastrado_em',
                    'desc': 'Data Inclusão',
                    'bclass': 'col-md-2',
                    'type': 'input-date',
                    'datePickerConfig': {
                        maxDate: moment(),
                    }
                },
                {
                    'label': 'Fone 1',
                    'name': 'fone1',
                    'desc': 'Telefone opcao 1',
                    'bclass': 'col-md-2',
                    'type': 'input-fone'
                },
                {
                    'label': 'Fone 2',
                    'name': 'fone2',
                    'desc': 'Telefone opcao 2',
                    'bclass': 'col-md-2',
                    'type': 'input-fone'
                },
                {
                    'label': 'Fone 3',
                    'name': 'fone3',
                    'desc': 'Telefone opcao 3',
                    'bclass': 'col-md-2',
                    'type': 'input-fone'
                },
                {
                    'label': 'Email',
                    'name': 'email',
                    'desc': 'Endereço de Email',
                    'bclass': 'col-md-4',
                    'type': 'input-email'
                }
            ],

            tab_index: 0
        }
    },
    created: function () {
    },

    watch: {
        /**
         * Buscar departamentos quando selelecionar empresa
         */
        'registro.id'(empresaId) {
            if (empresaId) {
                this.buscarDepartamentos();
            } else {
                this.departamentos = null;
            }
        }
    },

    computed: {

        urlFoto() {
            console.log(this.registro)
            return _.isEmpty(this.registro.url_foto) ? defaultFoto : this.registro.url_foto;
        },
        uploadUrl() {
            var token = '&authorization=' + TOKEN;
            return HOST_API + '/api/geral/file-storage/upload?' + token
        },
    },

    methods: {

        salvarDepartamento() {

            this.departamento.empresa_id = this.registro.id;

            this.doPost('geral/departamentos/salvar', this.departamento)
                .then(r => {
                    this.buscarDepartamentos();
                    this.departamento = {};
                });
        },

        excluirDepartamento() {


            this.departamento.empresa_id = this.registro.id;

            this.confirmar(opt => {
                if (opt) {
                    this.doPost('geral/departamentos/excluir', this.departamento)
                        .then(r => {
                            this.buscarDepartamentos();
                            this.departamento = {};
                        }).fail(msg => {

                        this.alertErro(msg.responseText);
                    });
                }
            }, 'CONFIRMAR EXCLUSÃO?')


        },

        salvar: function () {

            var self = this;

            this.operacao = 'salvar';

            this.doPost('geral/clientes/salvar', this.registro)
                .then(function (response) {

                    self.validationResponse = {};
                    self.registro = response;
                    self.alertSucesso();

                    this.$refs.grid.carregarGrid();

                });

        },


        excluir: function () {

            let self = this;

            this.operacao = 'excluir';

            this.confirmar(function (sim) {
                if (sim) {
                    self.doPost('geral/clientes/excluir', self.registro)
                        .then(function () {
                            self.alertSucesso('Registro excluído com sucesso');

                            this.$refs.grid.carregarGrid();

                        });
                }
            })

        },

        limpar: function () {

            this.registro = {};
            this.validationResponse = {};

        },
        editar(empresa) {
            this.registro = empresa;
        },

        buscarDepartamentos() {
            this.doGet('geral/departamentos/buscar', {
                paginate: false,
                empresa_id: this.registro.id
            }).dthen('departamentos');
        },

        getImage(img) {

            var url = _.get(img, '0.url');

            if (url) {

                this.registro.url_foto = url ?? null;

                this.registro.foto_upload_id = img[0].uid ?? null;

                this.$root.CLIENTE.url_foto = url;

            }
        },
    }
};


</script>
<style lang="scss" scoped>
ul.tree-depar {
    li {
        list-style: none;
        margin-bottom: 10px;
    }
}
</style>
