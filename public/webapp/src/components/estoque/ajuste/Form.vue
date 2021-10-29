<template>
    <b-card>
        <template #header>
            <span class="text-info"><i class="fa fa-edit"></i> Ajuste de Estoque</span>
        </template>

        <b-row>
            <b-col v-for="c in form"
                   v-bind="c.size"
                   :key="c.name">
                <!--                {{c}}-->
                <b-form-group :label="c.label"
                              :key="c.name">
                    <component
                        :is="c.type"
                        :disabled="registro.id!==''"
                        v-model="registro[c.name]"
                        v-bind="c.attr"></component>
                    <b-form-invalid-feedback :state="c.state">
                        {{ c.erros }}
                    </b-form-invalid-feedback>
                </b-form-group>
            </b-col>
        </b-row>

        <b-row>
            <b-col>
                <BuscaDeProdutos :disabled="registro.id!==''" @select="addProduto"></BuscaDeProdutos>
            </b-col>
        </b-row>

        <b-row>
            <b-col>
                <hr>
                <h5>Tabela de Ajuste</h5>
                <table class="table table-striped ajustar bg-gradient-light">
                    <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Nome</th>
                        <th>Código</th>
                        <th>Quantidade</th>
                        <th>Operação</th>
                        <th><i class="fa fa-trash"></i></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(a,index) in ajuste">
                        <td>
                            <b-img-lazy style="max-height: 50px"
                                        thumbnail
                                        :src="a.produto.imagem ?  a.produto.imagem : defaultImg "></b-img-lazy>
                        </td>

                        <td>{{ a.produto.nome }}</td>

                        <td>{{ a.produto.id }}</td>

                        <td>
                            <b-input v-model="a.quantidade"
                                     :disabled="registro.id!==''"
                                     type="number"></b-input>
                        </td>
                        <td>
                            <b-select v-model="a.tipo"
                                      :disabled="registro.id !=='' "
                                      :options="['ADICIONAR','REMOVER']"></b-select>
                        </td>
                        <td>
                            <b-link @click="ajuste.splice(index,1)" :disabled="registro.id!==''">
                                <i class="fa fa-trash fa-2x"></i></b-link>
                        </td>
                    </tr>
                    <tr v-if="ajuste.length === 0">
                        <td colspan="99">Nenhum produto adicionado</td>
                    </tr>
<!--                    <tr>-->
<!--                        <td>Total</td>-->
<!--                        <td><b>{{ ajuste.length }}</b></td>-->
<!--                    </tr>-->
                    </tbody>
                </table>
            </b-col>
        </b-row>

        <b-row>
            <b-col>
                <b-form-group label="Nota deste ajuste">
                    <b-textarea :disabled="registro.id!==''"
                                v-model="registro.notas"></b-textarea>
                </b-form-group>
            </b-col>
        </b-row>

        <b-row>
            <b-col>

                <div v-if="l_.get(movimento,'documento.url') && registro.id !== ''">
                    <b-button tag="a"
                              target="_blank"
                              :href="l_.get(movimento,'documento.url')">
                        <i class="fa fa-download"></i> {{ movimento.documento.metadata.originalName }}
                    </b-button>
                </div>
                <div v-else>
                    <btn-upload ref="btnUpload"
                                :disabled="registro.id!==''"
                                @sucesso="getUpload" label="Documento"></btn-upload>
                </div>


            </b-col>
        </b-row>

        <template #footer>
            <b-button-group>
                <b-button
                    :disabled="registro.id!==''"
                    @click="salvar"
                    variant="success"><i class="fa fa-save"></i> APLICAR AJUSTE
                </b-button>
                <!--                <b-button-->
                <!--                    :disabled="!registro.id"-->
                <!--                    @click="excluir"-->
                <!--                    variant="danger"><i class="fa fa-times"></i> EXCLUIR-->
                <!--                </b-button>-->
                <b-button
                    @click.prevent.stop="limpar"
                    variant="primary"><i class="fa fa-recycle"></i> LIMPAR
                </b-button>
            </b-button-group>
        </template>

    </b-card>
</template>

<script>
import BtnUpload from "@/components/geral/BtnUpload";
import BuscaDeProdutos from "@/components/estoque/produtos/BuscaDeProdutos";


export default {
    name: "Form",
    components: {BuscaDeProdutos, BtnUpload},
    props: ['movimento'],

    data() {
        return {
            registro: {
                id: '',
                estoque_id: '',
                armazem_id: '',
                cliente_id: '',
                upload_uid: '',
                notas: ''
            },

            armazens: [],
            estoques: [],
            clientes: [],

            ajuste: [],

            defaultImg: require('@/assets/estoque/produto-padrao.png'),


        }
    },
    computed: {
        form() {
            return [

                {
                    name: 'cliente_id',
                    label: 'Empresa',
                    type: 'b-form-select',
                    size: {md: 6},
                    attr: {
                        options: this.clientes.map(i => ({text: i.nome, value: i.id}))
                    }
                },

                {
                    name: 'estoque_id',
                    label: 'Estoque',
                    type: 'b-form-select',
                    size: {md: 6},
                    attr: {
                        options: this.estoques.map(i => ({text: i.nome, value: i.id}))
                    }
                },

                {
                    name: 'armazem_id',
                    label: 'Armazem',
                    type: 'b-form-select',
                    size: {md: 12},
                    attr: {
                        options: this.armazens.map(i => ({text: i.nome, value: i.id}))
                    }
                },

            ].map(i => {
                i.attr.state = this.validationResponse[i.name] ? false : undefined
                i.erros = this.validationResponse[i.name] ? this.validationResponse[i.name].join('') : ''
                return i;
            })
        },
    },

    watch: {
        movimento: {
            deep: true,
            handler(value) {
                if (value) {
                    this.registro.id = value.id;
                    this.registro.cliente_id = value.armazem.estoque.cliente.id;
                    this.registro.estoque_id = value.armazem.estoque.id;
                    this.registro.armazem_id = value.armazem.id;
                    this.registro.notas = value.notas;

                    this.ajuste = [];

                    value.itens.forEach(i => {
                        this.ajuste.push(
                            {
                                produto: i.produto,
                                quantidade: i.quantidade,
                                tipo: i.operacao == 'ENTRADA' ? 'ADICIONAR' : 'REMOVER'
                            }
                        );
                    });

                    $('body,html').animate({
                        scrollTop: 0
                    }, 800);
                }
            }
        },
        'registro.cliente_id'() {
            this.carregarEstoques();
        },
        'registro.estoque_id'() {
            this.carregarArmazens();
        }
    },

    created() {

        this.carregarEmpresas();


    },
    methods: {


        /**
         * Carregar fabricantes
         */
        carregarEmpresas() {

            this.doGet('geral/clientes/buscar', {
                paginacao: false,
                filtro: {}
            }).then(r => {
                this.clientes = r;
            });
        },

        /**
         * Carregar Estoques
         */
        carregarEstoques() {

            this.doGet('estoque/estoque/buscar', {
                paginacao: false,
                filtro: {
                    cliente_id: this.registro.cliente_id
                }
            }).then(r => {
                this.estoques = r;
            });
        },

        /**
         * Carregar Armazens
         */
        carregarArmazens() {

            this.doGet('estoque/armazem/buscar', {
                paginacao: false,
                filtro: {
                    estoque_id: this.registro.estoque_id
                }
            }).then(r => {
                this.armazens = r;
            });
        },

        salvar() {

            var params = this.registro;

            params['ajuste'] = this.ajuste.map(i => ({
                quantidade: i.quantidade,
                tipo: i.tipo,
                produto_id: i.produto.id
            }));

            this.confirmar(op => {
                if (op) {
                    this.doPost('estoque/movimento/ajustar', params)
                        .then(r => {
                            this.$bvToast.toast("Registro salvo",
                                {variant: 'success'});
                            this.$emit('update', _.clone(r));
                            this.registro = r;
                        })
                }
            }, 'Confirmar Ajuste. Após a confirmação não é possivel.')

        },

        limpar() {
            this.registro = this.$options.data().registro;
            this.ajuste = this.$options.data().ajuste;
            // console.log(this.$refs);
            this.$refs.btnUpload && this.$refs.btnUpload.limpar();
            this.validationResponse = this.$options.data().validationResponse;
        },


        addProduto(produtos) {

            produtos.forEach(p => {
                    this.ajuste.push(
                        {
                            produto: p,
                            quantidade: 1,
                            tipo: 'ADICIONAR'
                        }
                    );

                    this.ajuste = _.uniqBy(this.ajuste, (i => {
                        return i.produto.id
                    }));
                }
            )

        },

        getUpload(upload) {
            this.registro.upload_uid = upload.uid;
        }
    }
}
</script>

<style scoped>

table.ajustar td {
    vertical-align: middle;
}

</style>
