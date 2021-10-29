<template>
    <div class="position-relative">

        <b-form-group class="mb-0" label="Selecionar Produto">
            <b-input-group size="lg">
                <b-input-group-addon>
                    <b-input-group-text>
                        <i class="fa fa-barcode"></i>
                    </b-input-group-text>
                </b-input-group-addon>
                <b-input @focusin="showAutocomplete"
                         :disabled="disabled"
                         autocomplete="off"
                         @focusout="hideAutocomplete"
                         placeholder="Código, Apelido ou Descrição"></b-input>
            </b-input-group>
        </b-form-group>

        <div class="position-absolute bg-white w-100 shadow rounded"
             v-show="show_autocomplete"
             style="z-index: 9;border: 1px solid #ccc">
            <table class="table table-borderless table-striped">

                <thead>
                <tr>
                    <th colspan="2">Produto</th>
                    <th>Saldo</th>
                    <th>Unidade</th>
                    <th>Catetoria</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="p in produtos"
                    class="hover"
                    @click="$emit('select',[p])"
                    style="cursor:pointer">
                    <td style="width: 100px">
                        <b-img-lazy thumbnail
                                    style="max-width: 100px"
                                    :src="p.imagem ? p.imagem : defaultImg"></b-img-lazy>
                    </td>

                    <td>
                        <h3>{{ p.nome }}</h3>
                        <h4>{{ p.modelo.nome }}</h4>
                        {{ p.marca.nome }}
                    </td>
                    <td>
                        {{ p.saldo | currency('') }}
                    </td>
                    <td>
                        {{ p.unidade.unidade }}
                    </td>

                    <td>
                        {{ p.categoria.nome }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: "BuscaDeProdutos",

    data() {
        return {
            show_autocomplete: false,
            produtos: [],
            defaultImg: require('@/assets/estoque/produto-padrao.png'),
            timeoutIdAux: false
        }
    },

    props: {
        disabled: {
            type: [Boolean, String],
            default: false
        },
        armazem_id: {
            type: [String, Number],
            default: ''
        }
    },

    watch: {
        armazem_id(value) {
            console.log('0000');
            this.filtrarProdutos();
        }
    },

    created() {
        this.filtrarProdutos();
    },

    methods: {

        filtrarProdutos() {

            this.doGet('estoque/produtos/buscar', {
                paginacao: false,
                include: [
                    'marca',
                    'modelo',
                    'categoria',
                    'unidade'
                ],
                filtro: {
                    armazem_id: this.armazem_id
                }
            }).then(r => this.produtos = r);

        },

        showAutocomplete() {

            if (this.timeoutIdAux) {
                clearTimeout(this.timeoutIdAux);
            }
            this.show_autocomplete = true;
            this.filtrarProdutos();

        },

        hideAutocomplete() {
            this.timeoutIdAux = setTimeout(() => {
                this.show_autocomplete = false;
            }, 500)
        },
    }
}
</script>

<style lang="scss" scoped>

tr.hover:hover {
    td {
        background: #8a9192;
    }
}

</style>
