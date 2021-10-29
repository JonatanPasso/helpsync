<template>
    <div>
        <Form class="shadow-lg"
              @update="atualizar"
              :fabricante="registro"></Form>
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
            editar(categorias) {
                this.registro = categorias[0];
            }
        }
    }
</script>

<style scoped>

</style>
