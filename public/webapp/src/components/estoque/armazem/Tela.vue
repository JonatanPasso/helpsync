<template>
    <div>

        <Form class="shadow-lg"
              @update="atualizar"
              :armazem="registro"></Form>
        <Grid class="mt-2"
              @select="editar"
              ref="grid"></Grid>
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
            registro: null
        }
    },
    methods: {
        atualizar() {
            this.$refs.grid.carregarGrid();
        },
        editar(values) {
            this.registro = values[0];
        }
    },

    mounted() {
        //$('img').attr('src','https://rowl.com.br/img/logo%20ROWL.jpg')
    }
}
</script>

<style scoped>

</style>
