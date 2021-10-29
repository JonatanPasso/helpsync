<template>
    <b-card>
        <template #header>
            <span class="text-info"><i class="fa fa-arrow-up"></i><i class="fa fa-arrow-down"></i> Transferência de Estoque</span>
        </template>


        <b-card class="mb-2">
            <b-row>
                <b-col>
                    <h5 class="border-bottom text-info">
                        <i class="fa fa-arrow-left"></i> <i class="fa fa-dolly"></i> Origem</h5>
                </b-col>
            </b-row>
            <SubFormArmazem v-model="origem"
                            :disabled="registro.id !== ''"></SubFormArmazem>

        </b-card>

        <b-row>
            <b-col class="text-center text-info">
                <!--                <i class="fa fa-arrow-up"></i>-->
                <i class="fa fa-arrow-circle-down fa-2x"></i>
            </b-col>
        </b-row>

        <b-card class="mb-2">
            <b-row>
                <b-col>
                    <h5 class="border-bottom text-info">
                        <i class="fa fa-dolly"></i> <i class="fa fa-arrow-right"></i> Destino</h5>
                </b-col>
            </b-row>
            <SubFormArmazem v-model="destino"
                            :disabled="registro.id !== ''"></SubFormArmazem>
        </b-card>


        <b-card>

            <b-row>
                <b-col>
                    <h5 class="border-bottom text-info">
                        <i class="fa fa-clipboard-list"></i> Produtos</h5>
                </b-col>
            </b-row>

            <b-row>
                <b-col>
                    <BuscaDeProdutos :disabled="registro.id!=='' || origem.armazem_id == ''"
                                     :armazem_id="origem.armazem_id"
                                     @select="addProduto"></BuscaDeProdutos>
                </b-col>
            </b-row>

            <b-row>
                <b-col>
                    <hr>
                    <h6>Lista de Transfêrencia</h6>
                    <table class="table table-striped ajustar bg-gradient-light">
                        <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Nome</th>
                            <th>Código</th>
                            <th>Quantidade</th>
                            <!--                            <th>Operação</th>-->
                            <th><i class="fa fa-trash"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(a,index) in itens">
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
                                <b-link @click="itens.splice(index,1)" :disabled="registro.id!==''">
                                    <i class="fa fa-trash fa-2x"></i></b-link>
                            </td>
                        </tr>
                        <tr v-if="itens.length === 0">
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
                    <b-form-group label="Nota desta transferência">
                        <b-textarea :disabled="registro.id!==''"
                                    v-model="registro.notas"></b-textarea>
                    </b-form-group>
                </b-col>
            </b-row>

            <b-row>
                <b-col>

                    <div v-if="l_.get(registro,'documento.url') && registro.id !== ''">
                        <b-button tag="a"
                                  target="_blank"
                                  :href="l_.get(registro,'documento.url')">
                            <i class="fa fa-download"></i> {{ registro.documento.metadata.originalName }}
                        </b-button>
                    </div>
                    <div v-else>
                        <btn-upload ref="btnUpload"
                                    :disabled="registro.id!==''"
                                    @sucesso="getUpload" label="Documento"></btn-upload>
                    </div>


                </b-col>
            </b-row>

        </b-card>

        <template #footer>
            <b-button-group>
                <b-button
                    :disabled="registro.id!==''"
                    @click="salvar"
                    variant="success"><i class="fa fa-save"></i> EXCUTAR TRANFERÊNCIA
                </b-button>
                <b-button
                    :disabled="!registro.id"
                    @click="imprimir"
                    variant="info"><i class="fa fa-print"></i> IMPRIMIR (PDF)
                </b-button>
                <b-button
                    @click.prevent.stop="limpar"
                    variant="primary"><i class="fa fa-recycle"></i> LIMPAR
                </b-button>
            </b-button-group>
        </template>

        <div>
            <TemplateImpressao class="print-me"
                               v-if="imprimirData"
                               style="background-color: #fff;width: 760px"
                               :transferencia="imprimirData"></TemplateImpressao>
        </div>

    </b-card>
</template>

<script>
import BtnUpload from "@/components/geral/BtnUpload";
import BuscaDeProdutos from "@/components/estoque/produtos/BuscaDeProdutos";
import SubFormArmazem from "@/components/estoque/transferencia/SubFormArmazem";
import ImgProfile from "@/components/geral/perfil/ImgProfile";
import TemplateImpressao from "@/components/estoque/transferencia/TemplateImpressao";
import html2canvas from 'html2canvas';
import {jsPDF} from 'jspdf';

export default {
    name: "Form",
    components: {TemplateImpressao, ImgProfile, SubFormArmazem, BuscaDeProdutos, BtnUpload},
    props: ['transferencia'],

    data() {
        return {

            origem: {
                id: '',
                cliente_id: '',//'5',
                estoque_id: '',//'5',
                armazem_id: '',//'4',
            },

            destino: {
                id: '',
                cliente_id: '',//'5',
                estoque_id: '',//'5',
                armazem_id: '',//'4',
            },


            registro: {
                id: '',
                notas: '',
                upload_uid: ''
            },

            itens: [
                /*{
                "produto_id": 1,
                "produto": {
                    "id": 1,
                    "nome": "produto teste",
                    "ncm": "01012900",
                    "fabricante_id": 9,
                    "modelo_id": 5,
                    "categoria_id": 15,
                    "unidade_id": 1,
                    "unidade_venda_id": 1,
                    "unidade_compra_id": 5,
                    "imagem": null,
                    "marca_id": 12,
                    "codigo_referencia": null,
                    "tipo_cadastro": "MANUAL",
                    "cadastrado_por": 19,
                    "cadastrado_em": "2020-08-10 15:34:57",
                    "linha_id": 5,
                    "descricao": "<p>DETALHES DO PRODUTO</p><figure class=\"table\"><table><tbody><tr><td>A</td><td>B</td><td>C</td></tr><tr><td>1</td><td>2</td><td>3</td></tr><tr><td>2</td><td>3</td><td>4</td></tr><tr><td>&lt;&gt;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table></figure>",
                    "saldo": 100,
                    "marca": {"id": 12, "nome": "MARCA FABRICANTE LINHA", "fabricante_id": 9},
                    "modelo": {"id": 5, "nome": "MODELO LINHA", "marca_id": 12},
                    "categoria": {"id": 15, "nome": "LED", "descricao": "GOLDEN ULTRALED A60", "imagem": null},
                    "unidade": {"id": 1, "unidade": "MC", "descricao": "1 Maço"}
                },
                "quantidade": 1
            }, {
                "produto_id": 2,
                "produto": {
                    "id": 2,
                    "nome": "teste",
                    "ncm": "01012900",
                    "fabricante_id": 9,
                    "modelo_id": 5,
                    "categoria_id": 16,
                    "unidade_id": 1,
                    "unidade_venda_id": 1,
                    "unidade_compra_id": 1,
                    "imagem": "https://illuminarti.com.br/api/geral/file-storage/get/5f31f0630e0666.54653984",
                    "marca_id": 12,
                    "codigo_referencia": null,
                    "tipo_cadastro": "MANUAL",
                    "cadastrado_por": 19,
                    "cadastrado_em": "2020-08-10 22:17:28",
                    "linha_id": 5,
                    "descricao": "<p>sdfsfasdfsfsdfasd</p>",
                    "saldo": 0,
                    "marca": {"id": 12, "nome": "MARCA FABRICANTE LINHA", "fabricante_id": 9},
                    "modelo": {"id": 5, "nome": "MODELO LINHA", "marca_id": 12},
                    "categoria": {"id": 16, "nome": "TESTE", "descricao": "<P>TESTE</P>", "imagem": null},
                    "unidade": {"id": 1, "unidade": "MC", "descricao": "1 Maço"}
                },
                "quantidade": 1
            }*/],

            defaultImg: require('@/assets/estoque/produto-padrao.png'),

            imprimirData: false,

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
        transferencia: {
            deep: true,
            handler(value) {

                if (value) {

                    this.registro = value;

                    this.origem.cliente_id = value.origem_empresa_id;
                    this.origem.estoque_id = value.origem_estoque_id;
                    this.origem.armazem_id = value.origem_armazem_id;

                    this.destino.cliente_id = value.cliente_id;
                    this.destino.estoque_id = value.estoque_id;
                    this.destino.armazem_id = value.armazem_id;

                    // this.registro.cliente_id = value.armazem.estoque.cliente.id;
                    // this.registro.estoque_id = value.armazem.estoque.id;
                    // this.registro.armazem_id = value.armazem.id;
                    // this.registro.notas = value.notas;
                    //

                    this.itens = [];

                    value.itens.forEach(i => {
                        if (i.operacao == 'ENTRADA') {
                            this.itens.push(
                                {
                                    produto: i.produto,
                                    quantidade: i.quantidade,
                                }
                            );
                        }
                    });

                    $('body,html').animate({
                        scrollTop: 0
                    }, 800);
                }
            }
        }
    },

    created() {


    },
    methods: {


        salvar() {


            var params = {
                origem: this.origem,
                destino: this.destino,
                itens: this.itens,
                upload_uid: this.registro.upload_uid,
                notas: this.registro.notas
            }

            this.confirmar(op => {
                if (op) {
                    this.doPost('estoque/movimento/transferir', params)
                        .then(r => {
                            this.$bvToast.toast("Registro salvo",
                                {variant: 'success'});
                            this.$emit('update', _.clone(r));
                            this.registro = r;
                        })
                }
            }, 'Confirmar Transferência?. A operação é ireverssível!')

        },

        limpar() {

            this.registro = this.$options.data().registro;
            this.origem = this.$options.data().origem;
            this.destino = this.$options.data().destino;
            this.itens = this.$options.data().itens;
            this.validationResponse = this.$options.data().validationResponse;
        },


        addProduto(produtos) {

            produtos.forEach(p => {
                    this.itens.push(
                        {
                            produto_id: p.id,
                            produto: p,
                            quantidade: 1,
                        }
                    );

                    this.itens = _.uniqBy(this.itens, (i => {
                        return i.produto.id
                    }));
                }
            )

        },

        getUpload(upload) {
            this.registro.upload_uid = upload.uid;
        },

        imprimir() {

            window.scrollTo(0, 0);

            this.imprimirData = this.transferencia;


            setTimeout(n => {


                let container = $(this.$el).find('.print-me').get(0);
                let config = {
                    height: 1000,
                    width: 760,
                    useCORS: true
                };

                const doc = new jsPDF('p', 'pt', 'a4', true);
                // const doc = new jsPDF('p', 'pt', 'a4', true);

                html2canvas(container, config).then(canvas => {
                    let dataURL = canvas.toDataURL('image/png', 1);
                    //  $('body').append(canvas)
                    doc.addImage(dataURL, 'PNG', 10, 5);
                    doc.save();
                    this.imprimirData = false;
                })

            }, 2000);
        }
    }
}
</script>

<style scoped>

table.ajustar td {
    /*vertical-align: middle;*/
}

</style>
