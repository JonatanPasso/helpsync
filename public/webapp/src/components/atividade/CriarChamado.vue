<template>

    <div style="min-height: 300px">
        <div class="row">

            <div class="col-md-12 col-lg-6">
                <div class="form-group" :class="{'has-error': l_.get(validationResponse,'id_empresa')}">
                    <label>Empresa</label>
                    <v-select name="empresa"
                              v-model="criar_chamado.selEmpresa"
                              :options="empresasComputed">
                        <span slot="no-options">Não há opções para esta empresa!</span>
                    </v-select>
                    <small class="invalid-feedback" v-if="l_.get(validationResponse,'id_empresa')">
                        {{ l_.get(validationResponse,'id_empresa') .join(' ') }}</small>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="form-group" :class="{'has-error': l_.get(validationResponse,'id_departamento')}">
                    <label>Departamento</label>
                    <v-select name="departamento"
                              v-model="criar_chamado.selDepartamentos"
                              :options="departamentosComputed"
                              :disabled="disab">
                        <span slot="no-options">Não há opções para esta empresa!</span>
                    </v-select>
                    <small class="invalid-feedback" v-if="l_.get(validationResponse,'id_departamento')">
                        {{ l_.get(validationResponse,'id_departamento') .join(' ')}}</small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div class="form-group" :class="{'has-error': l_.get(validationResponse,'id_atividade')}">
                    <label>Atividade</label>
                    <v-select name="atividades"
                              v-model="criar_chamado.selAtividades"
                              :disabled="disab1"
                              :options="atividadesComputed">
                        <span slot="no-options">Não há opções para esta empresa!</span>
                    </v-select>
                    <small class="invalid-feedback" v-if="l_.get(validationResponse,'id_atividade')">
                        {{ l_.get(validationResponse,'id_atividade') .join(' ') }}</small>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="form-group" :class="{'has-error': l_.get(validationResponse,'id_subatividade')}">
                    <label>Subatividade</label>
                    <v-select name="subatividades"
                              v-model="criar_chamado.selSubatividades"
                              :disabled="disab2"
                              :options="subAtividadesComputed">
                        <span slot="no-options">Não há opções para esta empresa!</span>
                    </v-select>
                    <small class="invalid-feedback"
                           v-if="l_.get(validationResponse,'id_subatividade')">{{
                        l_.get(validationResponse,'id_subatividade').join(' ') }}</small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="form-group">
                    <label>Título</label>
                    <input type="text"
                           class="form-control"
                           :class="{'is-invalid': l_.get(validationResponse,'titulo_chamado')}"
                           name="titulo_chamado"
                           style="text-transform: uppercase"
                           v-model="criar_chamado.titulo_chamado"
                    />
                    <small class="invalid-feedback"
                           v-if="l_.get(validationResponse,'titulo_chamado')">
                        {{l_.get(validationResponse,'titulo_chamado').join(' ')}}</small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-4">
                <div class="form-group atrib" :class="{'has-error': l_.get(validationResponse,'id_usuario_executor')}">
                    <label>Atribuir</label>
                    <v-select name="atribuir"
                              id="atrib"
                              v-model="criar_chamado.selAtribuir"
                              :placeholder="l_.get(criar_chamado,'selAtividades.dados.moderada') == 'S' ? 'Gestor Departamento' : 'Selecionar Executor'"
                              :disabled="isDisabled"
                              :options="usuariosComputed">
                    </v-select>
                    <small class="invalid-feedback" v-if="l_.get(validationResponse,'id_usuario_executor')">
                        {{ l_.get(validationResponse,'id_usuario_executor') .join(' ') }}
                    </small>
                </div>
            </div>
            <div class="col-md-12 col-lg-3">
                <!--                {{criar_chamado}}-->
                <div :class="{'has-error': l_.get(validationResponse,'tipo_chamado')}">
                    <label>Tipo</label>
                    <b-form-select v-model="criar_chamado.tipo_chamado">
                        <b-form-select-option v-for="t in tipos"
                                              :key="t.value"
                                              :value="t.value">{{t.text}}
                        </b-form-select-option>
                    </b-form-select>
                    <small class="invalid-feedback" v-if="l_.get(validationResponse,'tipo_chamado')">
                        {{ l_.get(validationResponse,'tipo_chamado') .join(' ') }}
                    </small>
                </div>
            </div>
            <div class="col-md-12 col-lg-3">
                <template>
                    <div :class="{'has-error': l_.get(validationResponse,'prioridade_chamado')}">
                        <label>Prioridade</label>
                        <b-form-select v-model="criar_chamado.prioridade_chamado">
                            <b-form-select-option v-for="p in prioridades"
                                                  :key="p.value"
                                                  :value="p.value">{{p.text}}
                            </b-form-select-option>
                        </b-form-select>
                        <small class="invalid-feedback" v-if="l_.get(validationResponse,'prioridade_chamado')">
                            {{ l_.get(validationResponse,'prioridade_chamado') .join(' ') }}
                        </small>
                    </div>
                </template>
            </div>
            <div class="col-md-12 col-lg-2">
                <div class="form-group" :class="{'has-error': l_.get(validationResponse,'data_inicio_chamado')}">
                    <label>Data Início Chamado</label>
                    <InputDate type="text"
                               :config="{}"
                               v-model="criar_chamado.dt_inicio_chamado"
                               id="dt_inicio_treinamento"
                               name="dt_inicio_treinamento">
                    </InputDate>

                    <small class="invalid-feedback" v-if="l_.get(validationResponse,'data_inicio_chamado')">
                        {{ l_.get(validationResponse,'data_inicio_chamado') .join(' ') }}
                    </small>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-4">
                <b-form-group>
                    <label>Compartilhar Chamado</label>
                    <div class="form-control">
                        <b-form-checkbox v-model="criar_chamado.checkComp">Compartilhar com: </b-form-checkbox>
                    </div>
                </b-form-group>
            </div>
            <div class="col-md-12 col-lg-8" v-if="criar_chamado.checkComp">
                <b-form-group>
                    <label style="visibility: hidden"> Salvar</label>
                    <div class="form-control">
                        <b-form-radio-group>
                            <b-form-radio v-model="selectedTpComp" name="some-radios" value="D">Departamentos</b-form-radio>
                            <b-form-radio v-model="selectedTpComp" name="some-radios" value="U">Usuários</b-form-radio>
                        </b-form-radio-group>
                    </div>
                </b-form-group>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12" v-if="selectedTpComp == 'D'">
                <b-form-group label="Citar Departamento">
                    <v-select name="citarDepartamento"
                              multiple
                              id="departamento_id"
                              v-model="criar_chamado.selDepartamentosCompartilhados"
                              :options="departamentosCompartilhadosComputed">
                        <span slot="no-options">Não há opções para esta empresa!</span>
                    </v-select>
                </b-form-group>
            </div>
            <div class="col-md-12 col-lg-12" v-if="selectedTpComp == 'U'">
                <b-form-group label="Citar Colaborador">
                    <v-select name="interar"
                              multiple
                              id="id_usuario_chamado"
                              v-model="usuariosCompartilhados"
                              :options="listaUsuariosComp"
                              :loading="loading">
                        <template #spinner="{ loading }">
                            <div v-if="loading" style="border-left-color: rgba(88,151,251,0.71)" class="vs__spinner">
                                The .vs__spinner class will hide the text for me.
                            </div>
                        </template>
                    </v-select>
                </b-form-group>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="form-group" :class="{'has-error':l_.get(validationResponse,'descricao_chamado')}">
                    <label>Descrição</label>

                    <ckeditor :editor="editor"
                              v-model="editorData"
                              :config="editorConfig"></ckeditor>

                    <small class="invalid-feedback" v-if="l_.get(validationResponse,'descricao_chamado')">
                        {{ l_.get(validationResponse,'descricao_chamado') .join(' ') }}
                    </small>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-6" style="text-align: left">

                <!--                <b-button variant="outline-primary" @click="incluirAnexos"><i class="fa fa-paperclip"></i> Incluir Anexo</b-button>-->

                <b-button variant="primary" @click="incluirAnexos">
                    <i class="fa fa-paperclip"></i> Incluir Anexo <b-badge variant="light">{{lista_anexo.length}}</b-badge>
                </b-button>
                <!--                <BtnUpload v-model="criar_chamado.upload_doc_uid"-->
                <!--                           v-if="!criar_chamado.uid_anexo"-->
                <!--                           style="display: inline-block;"-->
                <!--                           mimes="">-->
                <!--                </BtnUpload>-->
                <!--                <div style="display: inline-block;" v-if="criar_chamado.uid_anexo">-->
                <!--				<span class="btn btn-labeled btn-success">-->
                <!--					<a href="#" class="btn-label pull-left" style="color: white;" v-on:click="limparUidAnexo">-->
                <!--						<i class="fa fa-times"></i>-->
                <!--					</a>-->
                <!--					<a :href="criar_chamado.url_anexo" target="_blank" class="label-text" style="color: white;">{{criar_chamado.title_anexo}}</a>-->
                <!--				</span>-->
                <!--                </div>-->
            </div>
            <div class="col-md-12 col-lg-6" style="text-align: right">
                <b-button-group>

                    <b-button variant="success"
                              :disabled="criar_chamado.id != ''"
                              @click="criarChamado"><i class="fa fa-save"></i> Criar Chamado
                    </b-button>
                    <b-button variant="primary" @click="listarChamadosParaEdicao"><i class="fa fa-list"></i> Listar
                        Chamado
                    </b-button>
                    <b-button variant="warning" @click="alterarChamado" v-if="criar_chamado.id"><i
                            class="fa fa-edit"></i> Alterar Chamado
                    </b-button>
                    <b-button variant="secondary" @click="limparFormCadastroChamado"><i class="fa fa-eraser"></i> Limpar
                    </b-button>
                </b-button-group>
            </div>
        </div>

        <!-- Modal Chamados Criados -->
        <b-modal id="mListaChamados"
                 ok-only
                 ok-title="FECHAR"
                 title="CHAMADOS">
            <table class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>Chamado</th>
                    <th>Título</th>
                    <th>Data Criação</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="d in arrayChamadosEdicao">
                    <tr v-if="d.status_chamado == 'CE'">
                        <td class="tss" style="color: #0e90d2; cursor: pointer; font-weight: bold"
                            v-on:click="selecionaChamado(d.nr_chamado)">
                            #{{d.nr_chamado}}
                        </td>
                        <td style="font-weight: bold">{{d.titulo_chamado}}</td>
                        <td style="font-weight: bold">{{d.criado_em | dateTimeFormat('DD/MM/YY hh:mm')}}</td>
                    </tr>
                </template>
                </tbody>
            </table>
        </b-modal>

        <!--End Modal Avaliação-->

        <!-- Modal Incluir Anexo -->
        <b-modal id="mIncluirAnexo"
                 hide-footer
                 title="INCLUIR ANEXO(s)"
                 size="lg">
            <div class="row">
                <div class="col-md-12 form-group">
                    <BtnUpload v-model="criar_chamado.upload_doc_uid"
                               v-if="!criar_chamado.uid_anexo"
                               style="display: inline-block;"
                               mimes="">
                    </BtnUpload>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 form-group">
                    <b-form-input placeholder="Nome Anexo"
                                  v-model="dados_lista_anexo.titulo_anexo"
                                  :disabled="criar_chamado.upload_doc_uid == ''">
                    </b-form-input>
                </div>
                <div class="col-md-2" style="text-align: right">
                    <b-button variant="success"
                              @click="incluirListaAnexo"
                              :disabled="criar_chamado.upload_doc_uid == ''">
                        <i class="fa fa-folder-plus"></i> Incluir</b-button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 ">
                    <table class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th>Título</th>
                            <th style="text-align: center">Anexo</th>
                            <th style="text-align: center">Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-if="lista_anexo.length > 0">
                            <tr v-for="(i, index) in lista_anexo">
                                <td>{{i.titulo_anexo}}</td>
                                <td style="text-align: center">
                                    <a :href="i.url" target="_blank" v-if="i.url">
<!--                                        <span class="fa fa-file-pdf fa-2x" style="color: red"></span>-->
                                        <Ficone :mime="i.mimeType"></Ficone>
                                    </a>
                                    <a href="" v-else>
                                        <span style="font-weight: bold">--</span>
                                    </a>
                                </td>
                                <td style="text-align: center">
                                    <b-button variant="danger" @click="removerItemListaAnexo(index)"><i class="fa fa-trash"></i></b-button>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="22">Não há dados para serem exibidos!</td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="text-align: right">
                <b-button-group>
                    <b-button class="mt-3" @click="limparListaAnexo"><i class="fa fa-eraser"></i> Limpar</b-button>
                    <b-button class="mt-3" variant="success"  @click="$bvModal.hide('mIncluirAnexo')"><i class="fa fa-check"></i> Concluir</b-button>
                </b-button-group>
            </div>

        </b-modal>
        <!-- End Modal Incluir Anexo -->

        <br>
    </div>

</template>

<style scoped>

    .dd-select {
        background-color: #fff !important;
    }

    .dd-selected {
        height: 32px !important;
    }

    .dd-option-image {
        width: 16px;
        height: 16px;
    }

    .dd-selected-image {
        width: 16px;
        height: 16px;
    }

    .dd-selected-text {
        position: absolute;
        margin-top: 7px;
    }

    .dd-option-text {
        position: absolute;
        margin-top: -11px;
    }

    .dd-selected-text-sub {
        position: absolute;
        margin-top: -8px;
    }

    .info-chamado {
        position: absolute;
        margin-top: -37px;
        /*z-index: 9934;*/
        margin-left: 221px;
    }

    .has-error .invalid-feedback {
        display: block !important;

    }

    .has-error .vs__dropdown-toggle,
    .has-error .ck-editor,
    .has-error select {
        border: 1px solid red !important;
    }


</style>

<style>
    .ck-editor__editable_inline {
        min-height: 200px !important;
    }

</style>
<script>


    import CKEDITOR from "@ckeditor/ckeditor5-build-classic/build/ckeditor";
    import TESTE from "@ckeditor/ckeditor5-vue";


    import BtnUpload from "../geral/BtnUpload";
    import InputDate from "../geral/singleForm/InputDate";
    import 'ddslick/dist/jquery.ddslick.min'
    import Ficone from "../geral/Ficone";

    export default {

        name: 'CriarChamado',
        components: {Ficone, InputDate, BtnUpload, ckeditor: TESTE.component},
        props: ['chamado'],

        watch: {
            'criar_chamado.selEmpresa': function (valor) {
                if (_.get(valor, 'dados.id')) {
                    this.getDepartamentos(valor.dados.id);
                }
            },

            'criar_chamado.selDepartamentos': function (valor) {
                if (_.get(valor, 'dados.id')) {
                    this.getAtividadesPorDepartamento(valor.dados.id);
                    this.getUsuariosPorDepartamento(valor.dados.id);
                }
            },

            'criar_chamado.selAtividades': function (valor) {

                if (_.get(valor, 'dados.id')) {
                    this.getSubatividadesPorIdAtividade(valor.dados.id);
                }

                if (_.get(valor, 'dados.moderada') !== 'S') {

                    // $('.atrib input').attr('placeholder', 'Selecione um usuário');
                    this.isDisabled = false;

                } else {
                    //   $('.atrib input').attr('placeholder', 'Gestor do Departamento');
                    this.isDisabled = true;
                    this.criar_chamado.selAtribuir = '';
                }
            },

            'criar_chamado.checkComp': function (vlr) {
                this.selectedTpComp = '';
                this.usuariosCompartilhados = [];
                this.criar_chamado.selDepartamentosCompartilhados = [];
            },

            'criar_chamado.upload_doc_uid': function (value) {

                this.doGet('geral/file-storage/buscar', {filtro:{uid:value}}).then(function (result) {
                    this.dados_lista_anexo.token = result.data[0].uid;
                    this.dados_lista_anexo.img_thumb = result.data[0].thumb_url;
                    this.dados_lista_anexo.url = result.data[0].url;
                    this.dados_lista_anexo.original_name = result.data[0].metadata.originalName;
                    this.dados_lista_anexo.mimeType = result.data[0].metadata.mimeType;
                });
            }
        },

        mounted: function () {

            var _this = this;

            this.getEmpresas();
            this.carregaCkEditorChamado();
            this.getUsuarios();

            $('#demo-htmlselect').ddslick({

                onSelected: function (data) {
                    var valOptions = $('#demo-htmlselect .dd-selected-value').val();

                    _this.criar_chamado.tipo = valOptions;

                    if (valOptions !== '0') {
                        $('.dd-selected-text').addClass('dd-selected-text-sub');
                    }
                }
            });

            $('#demo-htmlselect-pri').ddslick({
                onSelected: function (data) {
                    var valOptions = $('#demo-htmlselect-pri .dd-selected-value').val();
                    ;

                    _this.criar_chamado.prioridade = valOptions;

                    if (valOptions !== '0') {
                        $('.dd-selected-text').addClass('dd-selected-text-sub');
                    }
                }
            });
        },

        computed: {

            listaUsuariosComp: function () {
                return _.map(this.arrayAuxUsuarios, function (item) {
                    return {
                        label: item.nome,
                        value: item.id,
                        dados: item
                    };
                });
            },

            departamentosComputed: function () {
                return _.map(this.arrayDepartamentos, function (item) {
                    return {
                        label: item.nome,
                        value: item.id,
                        dados: item
                    }
                })
            },

            departamentosCompartilhadosComputed: function () {
                return _.map(this.arrayDepartamentosCompartilhados, function (item) {
                    return {
                        label: item.nome,
                        value: item.id,
                        dados: item
                    }
                })
            },

            empresasComputed: function () {
                return _.map(this.arrayEmpresas, function (item) {
                    return {
                        label: item.nome,
                        value: item.id,
                        dados: item
                    }
                })
            },

            atividadesComputed: function () {
                return _.map(this.arrayAtividades, function (item) {
                    return {
                        label: item.nm_atividade,
                        value: item.id,
                        dados: item
                    }
                })
            },

            subAtividadesComputed: function () {
                return _.map(this.arraySubAtividade, function (item) {
                    return {
                        label: item.nm_subatividade,
                        value: item.id,
                        dados: item
                    }
                })
            },

            usuariosComputed: function () {
                return _.map(this.arrayUsuarios, function (item) {

                    return {
                        label: _.get(item, 'usuario.nome'),
                        value: _.get(item, 'usuario.id'),
                        dados: _.get(item, 'usuario')
                    }
                })
            },
        },

        methods: {


            getDepartamentos: function (value) {

                var params = {
                    idEmpresa: value
                };

                this.criar_chamado.selDepartamentos = '';
                this.criar_chamado.selAtividades = '';
                this.criar_chamado.selSubatividades = '';
                this.disab = true;

                this.doGet('geral/departamentos/listarDepartamentosPorEmpresa', params).then(function (result) {
                    this.arrayDepartamentos = result;
                    this.arrayDepartamentosCompartilhados = result;

                    this.disab = false;
                });
            },

            getEmpresas: function () {

                this.doGet('geral/clientes/listarTodasEmpresas').then(function (result) {
                    this.arrayEmpresas = result;
                });
            },

            getAtividadesPorDepartamento: function (value) {

                this.criar_chamado.selAtividades = '';
                this.criar_chamado.selSubatividades = '';
                this.disab1 = true;

                this.doGet('atividades/atividades/listarAtividadesCadastradas', {idDepartamento: value}).then(function (result) {
                    this.arrayAtividades = result;

                    this.disab1 = false;
                });
            },

            getUsuariosPorDepartamento: function (value) {

                var params = {
                    departamento_id: value,
                    opcao: 'usuarios'
                };

                this.doGet('geral/vaga-departamento/listaVagaDepartamento', params).then(function (result) {

                    var _this = this;

                    $.each(result, function (index, value) {
                        $.each(_.get(value, 'usuario.vaga_departamentos'), function (indice, val) {
                            if (_.get(val, 'vaga_departamento_pai', 1) == null) {
                                _this.isGestor = val.usuario_id;
                            }
                        });
                    });

                    this.arrayUsuarios = result;
                });
            },

            getSubatividadesPorIdAtividade: function (value) {

                var params = {
                    idAtividade: value
                };

                this.criar_chamado.selSubatividades = '';
                this.disab2 = true;

                this.doGet('atividades/atividades/listarSubAtividadePorAtividade', params).then(function (result) {
                    this.arraySubAtividade = result;

                    this.disab2 = false;

                });
            },

            getUsuarios: function(){

                var _this = this;

                this.doGet('geral/usuarios/buscar', {paginate: 'false', include: ['vagaDepartamentos']}).then(function (result) {
                    _this.arrayAuxUsuarios = result;
                    $.each(_this.arrayAuxUsuarios, function (index, value) {
                        if(_this.$root.USER.id == _.get(value,'id')){
                            // console.log(_this.arrayAuxUsuarios.indexOf(value.id));
                            // console.log('index', index);
                            _this.arrayAuxUsuarios.splice(index, 1);
                        }
                    });
                    // console.log('novo', _this.arrayAuxUsuarios);
                });
            },

            criarChamado: function () {

                var idUsuariothis = this.$root.USER.id;
                // this.criar_chamado.descricao_chamado = CKEDITOR.instances.descricao_chamado.getData();
                this.criar_chamado.descricao_chamado = this.editorData;

                var idUsuarioInteracao = [];
                var idDepartamentosCompartilhados = [];

                if(this.criar_chamado.checkComp == true && this.selectedTpComp == 'U') {

                    for (var i in this.usuariosCompartilhados) {

                        idUsuarioInteracao.push(this.usuariosCompartilhados[i].value);
                    }
                }else if(this.criar_chamado.checkComp == true && this.selectedTpComp == 'D'){

                    for (var i in this.criar_chamado.selDepartamentosCompartilhados) {

                        idDepartamentosCompartilhados.push(this.criar_chamado.selDepartamentosCompartilhados[i].value);
                    }
                }

                //this.$api.atividade.cadastro.criarChamado
                this.doPost('atividades/atividades/criarChamado', {
                    id_empresa: this.criar_chamado.selEmpresa !== '' ? this.criar_chamado.selEmpresa.dados.id : '',
                    id_departamento: this.criar_chamado.selDepartamentos !== '' ? this.criar_chamado.selDepartamentos.dados.id : '',
                    id_atividade: this.criar_chamado.selAtividades !== '' ? this.criar_chamado.selAtividades.dados.id : '',
                    id_subatividade: this.criar_chamado.selSubatividades !== '' ? this.criar_chamado.selSubatividades.dados.id : '',
                    id_usuario_executor: this.criar_chamado.selAtribuir !== '' ? this.criar_chamado.selAtribuir.dados.id : this.isGestor,
                    id_usuario_criador: idUsuariothis,
                    titulo_chamado: this.criar_chamado.titulo_chamado,
                    tipo_chamado: this.criar_chamado.tipo_chamado,
                    prioridade_chamado: this.criar_chamado.prioridade_chamado,
                    status_chamado: 'CE',
                    data_inicio_chamado: this.criar_chamado.dt_inicio_chamado,
                    descricao_chamado: this.criar_chamado.descricao_chamado,
                    chamado_privado: this.criar_chamado.chamado_privado === 'true' ? 1 : 0,
                    moderou_chamado: this.criar_chamado.selAtividades.dados.moderada == 'S' ? '0' : '2', // SE A ATIVIDADE FOR MODERADA ENTÃO GRAVA NO CHAMADO QUE ELA AINDA NÃO FOI MODERADA POR ISSO GRAVA-SE COM NÃO (0).
                    uidAnexo: this.lista_anexo,
                    quem_observa: this.criar_chamado.quem_observa
                }).then(function (chamado) {

                    this.limparFormCadastroChamado();

                    this.$emit('lista_chamado');

                    // Criar lista de atendimento.
                    this.doPost('atividades/atividades/criarListaAtendimentoChamado', {
                        id_chamado: chamado.id,
                        id_usuario_chamado: chamado.id_usuario_executor
                    }).then(function (atendimento) {
                        // console.log(atendimento);
                    });

                    // Criar Interação / Histórico.
                    var params = {
                        id_chamado: chamado.id,
                        id_usuario_chamado: idUsuarioInteracao ? idUsuarioInteracao : [],
                        detartamento_id: idDepartamentosCompartilhados ? idDepartamentosCompartilhados : [],
                        tempo_executado: '00:00:00',
                        uidAnexo: '',
                        justificativa_iteracao_chamado: 'Chamado criado!'
                    };

                    this.doPost('atividades/atividades/incluirIteracao', params).then(function (result) {
                        //console.log(result)
                    }).then(function (atendimento) {
                        // console.log(atendimento);
                    });

                    this.limparListaAnexo();

                    this.alertSucesso();
                });

            },

            alterarChamado: function () {

                var idUsuariothis = this.$root.USER.id;
                // this.criar_chamado.descricao_chamado = CKEDITOR.instances.descricao_chamado.getData();
                this.criar_chamado.descricao_chamado = this.editorData;

                //
                //this.$api.atividade.cadastro.alterarChamado(

                this.doPost('atividades/atividades/alterarChamado', {
                    id_empresa: this.criar_chamado.selEmpresa !== '' ? this.criar_chamado.selEmpresa.dados.id : '',
                    id_departamento: this.criar_chamado.selDepartamentos !== '' ? this.criar_chamado.selDepartamentos.dados.id : '',
                    id_atividade: this.criar_chamado.selAtividades !== '' ? this.criar_chamado.selAtividades.dados.id : '',
                    id_subatividade: this.criar_chamado.selSubatividades !== '' ? this.criar_chamado.selSubatividades.dados.id : '',
                    id_usuario_executor: this.criar_chamado.selAtribuir !== '' ? this.criar_chamado.selAtribuir.dados.id : this.isGestor,
                    id_usuario_criador: idUsuariothis,
                    titulo_chamado: this.criar_chamado.titulo_chamado,
                    tipo_chamado: this.criar_chamado.tipo_chamado,
                    prioridade_chamado: this.criar_chamado.prioridade_chamado,
                    status_chamado: 'CE',
                    data_inicio_chamado: this.criar_chamado.dt_inicio_chamado,
                    descricao_chamado: this.criar_chamado.descricao_chamado,
                    chamado_privado: this.criar_chamado.chamado_privado === 'true' ? 1 : 0,
                    uidAnexo: this.criar_chamado.upload_doc_uid,
                    quem_observa: this.criar_chamado.quem_observa,
                    nr_chamado: this.criar_chamado.nr_chamado,
                })
                    .then(function (chamado) {

                        this.limparFormCadastroChamado();

                        this.$emit('lista_chamado');

                        this.alertSucesso('Chamado alterado com sucesso!');
                    })
                    .fail(function (erro) {
                        this.alertErro(erro);
                    });

            },

            limparFormCadastroChamado: function () {

                this.criar_chamado.id = '';
                this.criar_chamado.selEmpresa = '';
                this.criar_chamado.selAtribuir = '';
                this.criar_chamado.selDepartamentos = '';
                this.criar_chamado.selAtividades = '';
                this.criar_chamado.selSubatividades = '';
                this.criar_chamado.titulo_chamado = '';
                this.criar_chamado.tipo_chamado = '';
                this.criar_chamado.prioridade_chamado = '';
                this.criar_chamado.dt_inicio_chamado = moment();
                this.criar_chamado.descricao_chamado = '';
                this.criar_chamado.chamado_privado = '';
                this.criar_chamado.quem_observa = '';
                $('#demo-htmlselect').ddslick('select', {index: 0});
                $('#demo-htmlselect-pri').ddslick('select', {index: 0});
                this.criar_chamado.uid_anexo = '';
                this.criar_chamado.upload_doc_uid = '';
                // CKEDITOR.instances.descricao_chamado.setData('');
                this.editorData = '';
                this.usuariosCompartilhados = [];
                this.criar_chamado.selDepartamentosCompartilhados = [];
                this.criar_chamado.checkComp = false;
            },

            listarChamadosParaEdicao: function () {
                var _this = this;
                var idUsuarioLogado = this.$root.USER.id;

                var params = {
                    paramControle: 't',
                    idUsuario: idUsuarioLogado
                }

                //this.$api.atividade.consultas.listarChamados(

                this.doGet('atividades/atividades/listarChamados', params).then(function (result) {
                    this.arrayChamadosEdicao = result;
                });

                this.$bvModal.show('mListaChamados');

                // $('#modalChamadoCriados').modal('show');
                //
                // setTimeout(function () {
                //     _this.carregaDataTable();
                // }, 500);
            },

            selecionaChamado: function ($nr_chamado) {
                var _this = this;

                var params = {
                    nr_chamado: $nr_chamado
                };

                this.$bvModal.hide('mListaChamados');

                this.doGet('atividades/atividades/listarChamadosPorNr', params).then(function (result) {

                    this.criar_chamado.selEmpresa = {
                        label: result.empresa_chamado.nome,
                        value: result.empresa_chamado.id,
                        dados: result.empresa_chamado
                    };

                    this.criar_chamado.id = result.id;
                    this.criar_chamado.id_departamento = result.id_departamento;
                    this.criar_chamado.id_atividade = result.id_atividade;
                    this.criar_chamado.id_subatividade = result.id_subatividade;
                    this.criar_chamado.titulo_chamado = result.titulo_chamado;
                    this.criar_chamado.tipo_chamado = result.tipo_chamado;
                    this.criar_chamado.prioridade_chamado = result.prioridade_chamado;

                    this.criar_chamado.uid_anexo = result.uid_anexo;
                    this.criar_chamado.nr_chamado = result.nr_chamado;

                    // $('#demo-htmlselect').ddslick('select', {index: result.tipo_chamado});
                    // $('#demo-htmlselect-pri').ddslick('select', {index: result.prioridade_chamado});

                    this.criar_chamado.dt_inicio_chamado = moment(result.data_inicio_chamado).format('DD/MM/YYYY');
                    this.criar_chamado.quem_observa = result.quem_observa;
                    // CKEDITOR.instances.descricao_chamado.setData(result.descricao_chamado);
                    this.editorData = result.descricao_chamado;

                    this.criar_chamado.selAtribuir = {
                        label: result.usuario_executor.nome,
                        value: result.usuario_executor.id,
                        dados: result.usuario_executor
                    };


                    var paramDep = {
                        id_departamento: this.criar_chamado.id_departamento
                    };

                    //this.$api.atividade.consultas.listaDepartamentoPorId(
                    this.doGet('geral/departamentos/buscar', {
                        filtro: {
                            id: this.criar_chamado.id_departamento
                        }
                    }).then(function (result) {

                        this.criar_chamado.selDepartamentos = {
                            label: result[0].nome,
                            value: result[0].id,
                            dados: result[0]
                        };
                    });

                    var paramAti = {
                        id_atividade: this.criar_chamado.id_atividade
                    };

                    //    this.$api.atividade.consultas.consultaAtividade(

                    this.doGet('atividades/atividades/consultaAtividade', paramAti).then(function (resultAti) {

                        this.criar_chamado.selAtividades = {
                            label: resultAti.nm_atividade,
                            value: resultAti.id,
                            dados: resultAti
                        };
                    });

                    var paramsSub = {
                        id_subatividade: this.criar_chamado.id_subatividade
                    };

                    this.doGet('atividades/atividades/buscarSubAtividades', {
                        id_subatividade: this.criar_chamado.id_subatividade
                    }).then(function (resultSub) {
                        this.criar_chamado.selSubatividades = {
                            label: resultSub.nm_subatividade,
                            value: resultSub.id,
                            dados: resultSub
                        };
                    });


                    // var paramsUid = {
                    //     uid_anexo: this.criar_chamado.uid_anexo
                    // };
                    //
                    // // this.$api.atividade.consultas.listaAnexo(
                    //
                    // this.doGet('/beta/api/everyday/file-storage/lista', paramsUid).then(function (resultUid) {
                    //     this.criar_chamado.title_anexo = resultUid.original_name;
                    //     this.criar_chamado.url_anexo = resultUid.url
                    // })

                });

                $('#modalChamadoCriados').modal('hide');

            },

            limparUidAnexo: function () {
                this.criar_chamado.uid_anexo = '';
            },

            carregaCkEditorChamado: function () {


                return;
                CKEDITOR.replace('descricao_chamado', {
                    height: 100,
                    extraPlugins: 'colorbutton,font',
                    colorButton_enableAutomatic: false,
                    toolbar: [
                        {
                            name: 'basicstyles',
                            groups: ['basicstyles', 'cleanup'],
                            items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']
                        }, {name: 'colors', items: ['TextColor', 'BGColor']},
                        {
                            name: 'paragraph',
                            groups: ['list', 'indent', 'blocks', 'align', 'bidi'],
                            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language']
                        },
                        {
                            name: 'styles',
                            items: ['Format', 'Font', 'FontSize']
                        }, '/',
                        {
                            name: 'clipboard',
                            groups: ['clipboard', 'undo'],
                            items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
                        },
                        {
                            name: 'insert',
                            items: ['Table', 'HorizontalRule']
                        },
                        {
                            name: 'document',
                            groups: ['mode', 'document', 'doctools'],
                            items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates']
                        },
                        {
                            name: 'tools',
                            items: ['Maximize', 'ShowBlocks']
                        }
                    ]
                });
            },

            carregaDataTable: function () {
                // $('#example').DataTable({
                //     searching: true,
                //     "bDestroy": true,
                //     responsive: true,
                //     "bInfo": false,
                //     "pageLength": 7,
                //     "bLengthChange": false,
                //     "language": {
                //         "url": "/beta/js/Portuguese-Brasil.json"
                //     }
                // });
            },

            incluirAnexos: function () {
                this.$bvModal.show('mIncluirAnexo');
            },

            salvarAnexos: function () {
                this.doPost('atividades/atividades/salvarAnexos', this.atividade).then(function (result) {
                    this.atividade.id = result.id;
                    this.carregarAtividades();
                    this.alertSucesso();
                });
            },

            incluirListaAnexo: function () {

                this.dados_lista_anexo.token = this.dados_lista_anexo.token;
                this.dados_lista_anexo.url = this.dados_lista_anexo.url;
                this.dados_lista_anexo.titulo_anexo = this.dados_lista_anexo.titulo_anexo ? this.dados_lista_anexo.titulo_anexo : this.dados_lista_anexo.original_name;
                this.dados_lista_anexo.original_name = this.dados_lista_anexo.original_name;
                this.dados_lista_anexo.mimeType = this.dados_lista_anexo.mimeType;

                this.lista_anexo.push(_.clone(this.dados_lista_anexo));

                this.dados_lista_anexo.titulo_anexo = '';
                this.criar_chamado.upload_doc_uid = '';

            },

            removerItemListaAnexo: function ($i) {
                this.lista_anexo.splice($i, 1);
            },

            limparListaAnexo: function () {
                this.lista_anexo = [];
                this.dados_lista_anexo.titulo_anexo = '';
                this.criar_chamado.upload_doc_uid = '';
            }
        },

        data: function () {
            return {

                editor: CKEDITOR,
                editorData: '',
                editorConfig: {
                    // The configuration of the editor.
                    language: 'pt_BR',
                    placeholder: 'Descrição do chamado',
                    height: 200,
                },

                criar_chamado: {
                    id: '',
                    selEmpresa: '',
                    selDepartamentos: '',
                    selDepartamentosCompartilhados: '',
                    selAtividades: '',
                    selSubatividades: '',
                    selAtribuir: '',
                    privado: '',
                    tipo_chamado: '',
                    usuario_executor: '',
                    usuario_criador: '',
                    dt_inicio_chamado: moment().format('DD/MM/YYYY'),
                    titulo_chamado: '',
                    quem_observa: 'N',
                    chamado_privado: false,
                    status_chamado: '',
                    estimativa_horas: '',
                    descricao_chamado: '',
                    isGestor: '',
                    upload_doc_uid: '',
                    uid_anexo: '',
                    title_anexo: '',
                    url_anexo: '',
                    prioridade_chamado: '',
                    checkComp: false,
                },

                dados_lista_anexo: {
                    token: '',
                    titulo_anexo: '',
                    original_name: '',
                    img_thumb: '',
                    url: '',
                    data_cricao: '',
                    mimeType: ''
                },

                lista_anexo: [],

                tipos: [
                    {value: '', text: 'Selecione o tipo'},
                    {value: '1', text: 'ERRO'},
                    {value: '2', text: 'APRIMORAMENTO'},
                    {value: '3', text: 'PROPOSTA'},
                    {value: '4', text: 'TAREFA'},
                ],

                prioridades: [
                    {value: '', text: 'Selecione a prioridade'},
                    {value: '1', text: 'TRIVIAL'},
                    {value: '2', text: 'BAIXA'},
                    {value: '3', text: 'ALTA'},
                    {value: '4', text: 'CRÍTICA'},
                    {value: '5', text: 'BLOQUEIO'},
                ],

                arrayChamadosEdicao: [],
                arrayDepartamentos: [],
                arrayDepartamentosCompartilhados: [],
                arrayEmpresas: [],
                arrayAtividades: [],
                arraySubAtividade: [],
                arrayUsuarios: [],
                isDisabled: true,
                arrayAuxUsuarios: [],
                usuariosCompartilhados: [],
                departamentoComportailhado: [],
                now: $.now(),
                loading: false,
                disab: false,
                disab1: false,
                disab2: false,
                disab3: false,
                optcomp: true,
                selectedTpComp: '',

                optionsCompartilhar: [
                    { text: 'Departamentos', value: 'D' },
                    { text: 'Usuários', value: 'U' },
                ]
            }
        }
    };
</script>
