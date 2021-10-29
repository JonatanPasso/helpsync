<template>
    <div>

        <FormGruposUsuarios ref="form"
                            @deleted="atualizarGrid"
                            @saved="atualizarGrid"></FormGruposUsuarios>

        <GridGruposUsuarios ref="grid"
                            @select="editar"></GridGruposUsuarios>

    </div>
</template>

<script>

    import Card from "../Card";
    import SingleForm from "../SingleForm";
    import FormGruposUsuarios from "../FormGruposUsuarios";
    import GridGruposUsuarios from "../GridGruposUsuarios";

    export default {
        name: "UsuariosGrupos",
        components: {GridGruposUsuarios, FormGruposUsuarios, SingleForm, Card},

        data() {
            return {
                grupo: null
            }
        },

        created: function () {


        },

        methods: {
            editar(grupo) {
                this.$refs.form.registro = grupo;
            },
            atualizarGrid() {
                this.$refs.grid.carregarGrid();
            }
        }
    }
</script>

<style scoped>

</style>
