<template>
    <div>
        <FormRastreador :rastreador="rastreador"
                        @saved="atualizar"
                        @deleted="atualizar"></FormRastreador>
        <GridRastreadores ref="grid" @select="rastreador=$event"></GridRastreadores>

    </div>
</template>

<script>
    import FormRastreador from "../FormRastreador";
    import GridRastreadores from "../GridRastreadores";

    export default {
        name: "Rastreadores",
        components: {GridRastreadores, FormRastreador},
        data() {
            return {
                rastreador: null
            }
        },
        methods: {
            atualizar() {
                this.$refs.grid.carregarGrid();
            }
        }
    }
</script>

<style scoped>

</style>
