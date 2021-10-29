<template>

    <div>
        <FormVeiculo :veiculo="veiculo" @update="atualizaGrid" @delete="atualizaGrid"></FormVeiculo>

        <br>
        <GridVeiculos ref="grid" @select="veiculo=$event"></GridVeiculos>
    </div>

</template>

<script>
    import Card from "../../geral/Card";
    import FormVeiculo from "../FormVeiculo";
    import GridVeiculos from "../GridVeiculos";

    export default {
        name: "Veiculos",
        components: {GridVeiculos, FormVeiculo, Card},
        data() {
            return {
                veiculo: null
            }
        },
        methods: {
            atualizaGrid() {
                this.$refs.grid.carregarGrid();
            }
        }
    }
</script>

<style scoped>

</style>
