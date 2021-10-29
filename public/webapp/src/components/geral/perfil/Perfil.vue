<template>
    <div>

        <b-card class="shadow-lg">

            <template #header>
                <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-pager"></i> Perfil</h6>
            </template>

            <template #default>

                <form autocomplete="off">
                    <b-row>

                        <b-col md="4" sm="4">

                            <avatar-cropper
                                :labels="{submit:'ok',cancel:'fechar'}"
                                upload-form-name="files[]"
                                :upload-url="uploadUrl"
                                @uploaded="getImage"
                                trigger="#pick-avatar"/>


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

                        <b-col md="8" sm="8">
                            <b-row>
                                <b-col v-for="c in form"
                                       v-bind="c.size"
                                       :key="c.name">
                                    <b-form-group :label="c.label"
                                                  :key="c.name">
                                        <component
                                            :is="c.type"
                                            v-model="registro[c.name]"
                                            v-bind="c.attr"></component>
                                        <b-form-invalid-feedback style="display: block"
                                                                 v-if="c.erros.length">
                                            {{ c.erros }}
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                </b-col>
                            </b-row>
                        </b-col>

                    </b-row>
                </form>

            </template>

            <template #footer>
                <div class="btn-group">

                    <button type="button"
                            @click="salvar"
                            :disabled="ajaxStatus && operacao == 'salvar'"
                            class="btn btn-success">SALVAR <i v-if="ajaxStatus && operacao == 'salvar'"
                                                              class="fas fa-spinner fa-spin"></i>
                    </button>

                </div>
            </template>
        </b-card>

    </div>
</template>

<script>

import SingleForm from "../SingleForm";
import BtnUpload from "@/components/geral/BtnUpload";
import defaultFoto from "@/assets/tema/fluxo/usuario-unisex.png";
import AvatarCropper from "vue-avatar-cropper";

export default {
    name: "Perfil",
    components: {BtnUpload, SingleForm, AvatarCropper},

    data() {
        return {

            registro: {
                id: '',
                nome: '',
                genero: 'NÃO INFORMADO',
                email: '',
                fone: '',
                alterar_senha: 'NAO',
                cpf: null,
                is_root: 'Y',
                status: 'ATIVO',
                modo_noturno: 'NAO',
                url_foto: '',
                foto_upload_id: undefined
            },

            fix: true

        }
    },

    computed: {

        form() {
            return [
                {
                    'label': 'Código',
                    'name': 'id',
                    'desc': 'Código do Usuário',
                    'size': {md: 2},
                    'type': 'b-form-input',
                    'attr': {'readonly': 'readonly'}
                },

                {
                    'label': 'Nome',
                    'name': 'nome',
                    'desc': 'Nome do usuário',
                    'size': {md: 6},
                    'type': 'b-form-input'
                },

                {
                    'label': 'Gênero',
                    'name': 'genero',
                    'desc': 'Genero do usuario',
                    'size': {md: 4},
                    'type': 'b-form-select',
                    attr: {
                        options: ['MASCULINO', 'FEMININO', 'NÃO INFORMADO']
                    }
                },

                {
                    'label': 'Usuário (login)',
                    'name': 'usuario',
                    'desc': 'Login',
                    'size': {md: 4},
                    'type': 'b-form-input',
                    attr: {
                        class: _.get(this.validationResponse, 'usuario') ? 'is-invalid' : ''
                    }
                },


                {
                    'label': 'Email',
                    'name': 'email',
                    'desc': 'Endereço de Email',
                    'size': {md: 4},
                    'type': 'b-form-input',
                    attr: {
                        disabled: this.fix
                    }
                },

                {
                    'label': 'CPF/CNPJ',
                    'name': 'cpf',
                    'desc': 'Cpf do Usuário',
                    'size': {md: 4},
                    'type': 'the-mask',
                    attr: {
                        class: _.get(this.validationResponse, 'cpf') ?
                            'form-control is-invalid' : 'form-control',
                        mask: ['###.###.###-##', '##.###.###/####-##'],
                        disabled: this.fix
                    }
                },

                {
                    'label': 'Fone',
                    'name': 'fone',
                    'desc': 'Celular',
                    'size': {md: 4},
                    'type': 'the-mask',
                    attr: {
                        class: _.get(this.validationResponse, 'fone') ? 'form-control is-invalid' : 'form-control',
                        mask: '(##) #####-####',
                        disabled: this.fix
                    }
                },

                {
                    label: 'Alterar Senha',
                    name: 'alterar_senha',
                    size: {md: 3},
                    type: 'b-form-select',
                    attr: {
                        options: ['NAO', 'SIM']
                    }
                },

                {
                    'label': 'Senha',
                    'name': 'senha',
                    'desc': 'Senha',
                    'size': {md: 5},
                    'type': 'b-form-input',
                    attr: {
                        class: _.get(this.validationResponse, 'senha') ? 'form-control is-invalid' : 'form-control',
                        type: 'password',
                        disabled: this.fix || this.registro.alterar_senha != 'SIM'
                    }

                },

                {
                    'label': 'Modo Noturno',
                    'name': 'modo_noturno',
                    'desc': 'Nodo noturno sistema',
                    'size': {md: 3},
                    'type': 'b-form-select',
                    attr: {options: ['SIM', 'NAO']}
                },

            ].map((item, key, object) => {
                // consoloe.log('aaa',item)
                let erros = _.get(this.validationResponse, item.name);
                item.state = undefined;
                item.erros = [];
                _.set(item, 'attr.autocomplete', 'off');
                if (erros) {
                    item.state = false;
                    item.erros = erros.join();
                }
                return item;
            })
        },

        urlFoto() {
            return _.isEmpty(this.registro.url_foto) ? defaultFoto : this.registro.url_foto;
        },
        uploadUrl() {
            var token = '&authorization=' + TOKEN;
            return HOST_API + '/api/geral/file-storage/upload?' + token
        }
    },

    watch: {
        'registro.modo_noturno'(opcao) {
            if (opcao == 'SIM') {
                $('body').addClass('modo-noturno');
            } else {
                $('body').removeClass('modo-noturno');
            }
            this.$root.USER.modo_noturno = opcao
        },

    },

    created: function () {

        this.$root.$watch('USER.id', () => {
            this.registro.id = this.$root.USER.id;
            this.registro.nome = this.$root.USER.nome;
            this.registro.email = this.$root.USER.email;
            this.registro.fone = this.$root.USER.fone;
            this.registro.cpf = this.$root.USER.cpf;
            this.registro.is_root = this.$root.USER.is_root;
            this.registro.usuario = this.$root.USER.usuario;
            if (this.$root.USER.genero)
                this.registro.genero = this.$root.USER.genero;
        });

        this.registro = _.extend(this.registro, this.$root.USER);

        setTimeout(() => {
            this.fix = false;
        }, 1000)

    },

    methods: {


        getImage(img) {

            var url = _.get(img, '0.url');

            if (url) {

                this.registro.url_foto = url ?? null;

                this.registro.foto_upload_id = img[0].uid ?? null;

                this.$root.USER.url_foto = url;

            }
        },

        salvar: function () {
            this.doPost('geral/usuarios/perfil', this.registro)
                .then(function () {
                    this.alertSucesso();
                });
        }
    }
}
</script>

<style scoped>

</style>
