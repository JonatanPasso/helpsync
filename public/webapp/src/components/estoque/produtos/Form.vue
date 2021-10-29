<template>
    <b-card>
        <template #header>
            <span class="text-info">
                <i class="fa fa-edit"></i> Cadastro de Produtos</span>
        </template>

        <b-row>
            <!-- Imagem do produto-->
            <b-col md="3" align="center">
                <h6>Imagem Produto</h6>
                <b-img thumbnail fluid :src="registro.imagem ? registro.imagem : defaultProdutoImg"
                       alt="Image 1"></b-img>
                <br><br>
                <btn-upload
                    ref="btnUpload"
                    v-model="uploadImage"
                    :full-result="true"
                    :historico="false"
                    label="Enviar Imagem"
                    mimes="image/jpeg,image/png"></btn-upload>

                <hr>

                <h6 v-if="registro.codigo_barras">Código de Barras</h6>
                <CodigoBarras v-if="registro.codigo_barras" :produto="registro"></CodigoBarras>

            </b-col>
            <!--            Dados do produto-->


            <b-col>
                <b-row>
                    <b-col v-for="(c,id) in form"
                           :md="c.md"
                           :lg="c.lg"
                           :key="id">
                        <!--                {{c}}-->
                        <b-form-group :label="c.label" :key="id">
                            <component
                                :is="c.is"
                                v-model="registro[id]"
                                v-on="c.ev"
                                v-bind="c.attr">
                            </component>
                            <b-form-invalid-feedback :state="c.state">
                                {{ c.erros }}
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-col>
        </b-row>

        <template #footer>
            <b-button-group>
                <b-button
                    @click="salvar"
                    variant="success"><i class="fa fa-save"></i> SALVAR
                </b-button>
                <b-button
                    :disabled="!registro.id"
                    @click="excluir"
                    variant="danger"><i class="fa fa-times"></i> EXCLUIR
                </b-button>
                <b-button
                    @click="limpar"
                    variant="primary"><i class="fa fa-recycle"></i> LIMPAR
                </b-button>
            </b-button-group>
        </template>
    </b-card>
</template>

<script>

import BtnUpload from "@/components/geral/BtnUpload";
import Estoque from "@/components/estoque/Estoque";
import CKEDITOR from "@ckeditor/ckeditor5-build-classic/build/ckeditor";
import TESTE from "@ckeditor/ckeditor5-vue";
import TblCodigoReferencia from "@/components/estoque/produtos/TblCodigoReferencia";
import CodigoBarras from "@/components/estoque/produtos/CodigoBarras";


export default {
    name: "Form",
    components: {CodigoBarras, BtnUpload, ckeditor: TESTE.component, TblCodigoReferencia},
    props: ['produto'],
    extends: {
        methods: Estoque,
    },


    data() {
        return {
            ckInstance: CKEDITOR,
            registro: {
                id: '',
                nome: '',
                ncm: '',
                descricao: '',
                fabricante_id: '',
                modelo_id: '',
                categoria_id: '',
                unidade_id: '',
                unidade_venda_id: '',
                unidade_compra_int: '',
                imagem: '',
                marca_id: '',
                codigo_referencia: '',
                tipo_cadastro: '',
                cadastrado_por: '',
                cadastrado_em: '',
                linha_id: '',
                tipo_codigo_barras: '',
                codigo_barras: '',
                /** dados upload se imagem for alterada*/
                upload_image: null,
                codigos_referencia: []
            },
            /**
             * upload data
             */
            uploadImage: null,
            defaultProdutoImg: 'https://picsum.photos/250/250/?image=54',

            fabricantes: [],
            marcas: [],
            modelos: [],
            linhas: [],

            categorias: [],
            unidades: [],

            ncm: []
        }
    },

    async created() {

        this.registro = _.isObject(this.produto) ?
            this.produto : this.$options.data().registro;

        this.fabricantes = await this.getFabricantes({paginacao: false});
        this.categorias = await this.getCategorias({paginacao: false});
        this.unidades = await this.getUnidades({paginacao: false});

        this.atualizarMarcas();

        this.atualizarNcm();


    },

    computed: {
        form() {

            var _this = this;
            return _.mapValues({

                id: {
                    is: 'b-form-input', label: 'Código', md: 3, lg: 2,
                    attr: {disabled: true, placeholder: 'auto'}
                },
                nome: {is: 'b-form-input', label: 'Nome do Produto', md: 9, lg: 5},
                fabricante_id: {
                    is: 'v-select', label: 'Fabricante',
                    md: 6, lg: 5,
                    attr: {
                        reduce(option) {
                            return option.value;
                        },
                        options: this.fabricantes.map(i => ({
                            label: i.nome,
                            value: i.id
                        }))
                    }
                },
                marca_id: {
                    is: 'v-select',
                    label: 'Marca',
                    md: 6, lg: 3,
                    attr: {
                        reduce(option) {
                            return option.value;
                        },
                        options: this.marcas.map(i => ({label: i.nome, value: i.id}))
                    }
                },
                modelo_id: {
                    is: 'v-select', label: 'Modelo', md: 6, lg: 3,
                    attr: {
                        reduce(option) {
                            return option.value;
                        },
                        options: this.modelos.map(i => ({label: i.nome, value: i.id}))
                    }
                },
                linha_id: {
                    is: 'v-select', label: 'Linha', md: 6, lg: 3,
                    attr: {
                        reduce(option) {
                            return option.value;
                        },
                        options: this.linhas.map(i => ({label: i.nome, value: i.id}))
                    }
                },
                categoria_id: {
                    is: 'b-form-select',
                    label: 'Categoria',
                    md: 6, lg: 3,

                    attr: {
                        reduce(option) {
                            return option.value;
                        },
                        options: this.categorias.map(i => ({text: `${i.nome} - ${i.descricao}`, value: i.id}))
                    }
                },

                unidade_id: {
                    is: 'v-select', label: 'Unidade', md: 6, lg: 4,
                    attr: {
                        reduce(option) {
                            return option.value;
                        },
                        options: this.unidades.map(i => ({label: `${i.unidade} - ${i.descricao}`, value: i.id}))
                    }
                },
                unidade_venda_id: {
                    is: 'v-select', label: 'Unidade Venda', md: 6, lg: 4,
                    attr: {
                        reduce(option) {
                            return option.value;
                        },
                        options: this.unidades.map(i => ({label: `${i.unidade} - ${i.descricao}`, value: i.id}))
                    }
                },
                unidade_compra_id: {
                    is: 'v-select', label: 'Unidade Compra', md: 6, lg: 4,
                    attr: {
                        reduce(option) {
                            return option.value;
                        },
                        options: this.unidades.map(i => ({label: `${i.unidade} - ${i.descricao}`, value: i.id}))
                    }
                },

                ncm: {
                    is: 'v-select', label: 'NCM', md: 12,
                    attr: {
                        reduce(option) {
                            return option.value;
                        },
                        options:
                            this.ncm
                                .map(i => ({
                                    label: `${i.descricao} / ${i.categoria}`,
                                    value: i.ncm,
                                    d: i
                                }))
                    },

                    ev: {
                        search(value, load) {
                            load(true);
                            _this.atualizarNcm(value)
                                .then(r => load(false))
                                .fail(e => load(false));
                        }
                    }
                },

                tipo_codigo_barras: {
                    is: 'b-select',
                    label: 'Tipo Código Barras',
                    md: 4,
                    attr: {
                        options: ['EAN', /*'UPC', 'GTIN', 'ISBN', 'JAN'*/]
                    }
                },

                codigo_barras: {
                    is: 'the-mask',
                    label: 'Número do Código',
                    md: 8,
                    attr: {
                        mask: '#############',
                        disabled: this.registro.tipo_codigo_barras == '',
                        class: 'form-control'
                    }
                },

                descricao: {
                    is: 'ckeditor',
                    label: 'Descrição do Produto',
                    md: 12, lg: 12,
                    attr: {
                        editor: this.ckInstance,
                        config: {
                            // The configuration of the editor.
                            language: 'pt_BR',
                            placeholder: 'Descrição do produto',
                            height: 100,
                        }
                    },
                },

                codigo_referencia: {
                    is: 'tbl-codigo-referencia',
                    label: 'Códigos de Referências',
                    md: 12, lg: 12,
                    attr: {
                        dados: this.registro.codigos_referencia
                    },
                    ev: {
                        add: this.addCodigoRef,
                        del: this.delCodigoRef
                    }
                },

                tipo_cadastro: {
                    is: 'b-form-select',
                    label: 'Tipo Cadastro',
                    md: 4,

                    attr: {options: ['IMPORTACAO', 'MANUAL'], disabled: true,}
                },
                cadastrado_por: {
                    is: 'b-form-input', label: 'Usuário Cadastro', md: 5, attr: {disabled: true},
                },
                cadastrado_em: {is: 'b-form-input', label: 'Data Cadastro', md: 3, attr: {disabled: true}},


            }, (item, key, object) => {
                let erros = _.get(this.validationResponse, key);
                if (erros) {
                    item.state = false;
                    item.erros = erros.join();
                }

                if (_.get(item, 'attr.options')) {
                    item.attr.options = [{text: 'Nenhum', label: 'Nenhum', value: ''}].concat(item.attr.options);
                }
                return item;
            });
        }
    },

    watch: {
        produto: {
            deep: true,
            handler(prod) {

                this.registro = _.isObject(prod) ? prod : this.$options.data().registro;

                if (this.registro.codigos_referencia == undefined) {
                    this.registro.codigos_referencia = [];
                }

            }
        },
        uploadImage: {
            deep: true,
            handler(img) {
                var url = _.get(img, 'url');
                if (url) {
                    this.registro.imagem = url ?? null;
                    this.registro.upload_image = img ?? null;
                    this.$refs.btnUpload.limpar();
                }

            }
        },

        'registro.fabricante_id'(id) {

            this.marcas = [];
            this.modelos = [];
            this.linhas = [];
            // this.registro.marca_id = '';
            // this.registro.modelo_id = '';
            // this.registro.linha_id = '';

            if (id)
                this.atualizarMarcas();
        },
        'registro.marca_id'(id) {
            this.modelos = [];
            // this.registro.modelo_id = '';
            this.linhas = [];
            // this.registro.linha_id = ''
            if (id) {
                this.atualizarLinhas();
                this.atualizarModelos();
            }
        },
    },
    methods: {
        salvar() {

            this.doPost('estoque/produtos/salvar', this.registro)
                .then(r => {
                    this.$bvToast.toast("Registro salvo",
                        {variant: 'success'});
                    this.$emit('update', _.clone(r));
                    this.registro = r;
                })
        },
        excluir() {
            this.confirmar(opt => {
                if (opt) {
                    this.doPost('estoque/produtos/excluir', this.registro)
                        .then(() => {
                            this.$bvToast.toast("Registro excluído",
                                {variant: 'success'});
                            this.limpar();
                            this.$emit('update');
                        })
                }
            }, 'Confirmar exclusão de registro?')
        },
        limpar() {
            this.registro = this.$options.data().registro;
        },

        atualizarMarcas() {
            let p = {
                filtro: {fabricante_id: this.registro.fabricante_id ? this.registro.fabricante_id : false},
                paginacao: false,
            };
            this.getMarcas(p).then(r => this.marcas = r);
        },

        atualizarModelos() {
            let p = {filtro: {marca_id: this.registro.marca_id}, paginacao: false}

            this.getModelos(p).then(r => this.modelos = r)
        },
        atualizarLinhas() {
            let p = {filtro: {marca_id: this.registro.marca_id}, paginacao: false}
            this.getLinhas(p).then(r => this.linhas = r)
        },

        atualizarNcm(query) {
            return this.getNcm({
                filtro: {
                    contem: query
                }
            }).then(r => this.ncm = r.data);
        },

        addCodigoRef(ev) {
            this.registro
                .codigos_referencia
                .push(ev);
        },
        delCodigoRef(ev) {
            this.registro
                .codigos_referencia = _.remove(this.registro
                .codigos_referencia, i => i !== ev)
        }
    }
}
</script>

<style scoped>

</style>
