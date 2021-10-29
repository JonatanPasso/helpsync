<template>

    <div>

        <b-row>
            <b-col align="right">
                <b-button variant="outline-info"
                          @click="showForm=!showForm"
                          class="mb-3"><i class="fa" :class="showForm ? 'fa-minus':'fa-plus'"></i> PRODUTO
                </b-button>
            </b-col>
        </b-row>

        <Form class="shadow-lg mb-3"
              v-if="showForm"
              :produto="registro"></Form>

        <b-card class="shadow-lg">
            <template #header>
                <span class="text-info"><i class="fa fa-table"></i> Produtos Cadastrados</span>
            </template>
            <Grid @select="selecionarProdutos"></Grid>
        </b-card>
    </div>
</template>

<script>

import Form from './Form';
import Grid from './Grid';

export default {
    name: "Tela",
    components: {Form, Grid},
    data() {
        return {
            registro: null,
            showForm: false
        }
    },
    methods: {
        selecionarProdutos(itens) {
            this.registro = itens.length ? itens[0] : null;
            this.showForm = true;
            $("html, body").animate({ scrollTop: 0 }, "slow");
        }
    }
}
</script>

<style scoped>

</style>
