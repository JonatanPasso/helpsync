<template>
    <div>

        <b-input-group class="mb-2"
                       append-html="<i class='fa fa-search'></i>">
            <b-form-input v-model="buscaText"
                          placeholder="CODIGO, NOME, CODIGO DE REFERENCIA, DESCRIÇAO DO PRODUTO"></b-form-input>
        </b-input-group>

        <b-overlay :show="ajaxStatus">
            <div class="table-responsive">
                <b-table :fields="colunas"
                         selectable
                         select-mode="single"
                         @row-selected="selecionar"
                         show-empty
                         :items="paginate.data">
                    <template #cell(imagem)="data">
                        <b-img-lazy thumbnail
                                    height="100"
                                    style="max-width: 200px"
                                    :src="data.item.imagem ? data.item.imagem : defaultImg"></b-img-lazy>
                    </template>
                    <template #empty>
                        Nenhum produto encontrado
                    </template>
                </b-table>
            </div>
        </b-overlay>
    </div>
</template>

<script>

import Estoque from "@/components/estoque/Estoque";

export default {

    extends: {methods: Estoque},
    name: "Grid",

    data() {
        return {
            paginate: {
                data: [],
                current_page: 0,
                per_page: 0,
                total: 0
            },
            colunas: [
                {key: 'id', label: 'Código'},
                {key: 'imagem', label: 'Produto'},
                {key: 'nome', label: 'Nome'},
                {key: 'categoria.nome', label: 'Categoria'},
                {key: 'marca.nome', label: 'Marca'},
                {key: 'modelo.nome', label: 'Modelo'},
                {key: 'fabricante.nome', label: 'Fabricante'},
                {key: 'linha.nome', label: 'Linha'},
            ],
            defaultImg: 'https://picsum.photos/250/250/?image=54',

            buscaText: '',
        }
    },

    created() {

        this.atualizarProdutos();

        this.throttleCall = _.throttle(this.atualizarProdutos, 500)

    },

    watch: {
        buscaText(value) {
            this.throttleCall();
        }
    },

    methods: {

        atualizarProdutos() {

            this.getProdutos({
                filtro: {contem: this.buscaText},
                include: ['categoria', 'fabricante', 'linha', 'marca', 'modelo', 'codigosReferencia']
            }).then(r => this.paginate = r);
        },

        selecionar(itens) {
            if (itens.length) {
                this.$emit('select', itens);
            }
        }
    }
}
</script>

<style scoped>

</style>
