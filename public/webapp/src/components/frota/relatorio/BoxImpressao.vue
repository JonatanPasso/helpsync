<template>
    <div class="cabecalho-impressao">
        <table class="table-wraper">
            <thead>
            <tr>
                <th rowspan="3" class="text-center">
                    <img class="print-logo-topo" src="./../../assets/logo-wide.png">
                </th>
                <th class="text-center" rowspan="2">
                    <h5 class="print-title">
                        <slot name="title">RELATÓRIO</slot>
                    </h5>
                </th>
                <th>
                    Cliente
                </th>
                <th>
                    Usuário
                </th>
                <th>Data</th>
            </tr>
            <tr>
                <td><b>{{config.cliente}}</b></td>
                <td><b>{{config.usuario}}</b></td>
                <td><b>{{config.data}}</b></td>
            </tr>
            <tr>
                <td colspan="2">
                    <slot name="subtitle"></slot>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="99">
                    <slot></slot>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "BoxImpressao",
        computed: {
            config() {
                return {
                    cliente: this.$root.CLIENTE.nome,
                    usuario: this.$root.USER.nome,
                    data: moment().format('DD/MM/YYYY HH:mm:ss')
                }
            }
        }
    }
</script>

<style scoped>

</style>
