<template>

    <div v-if="transferencia">
        <table class="table table-bordered">

            <tr>
                <td class="text-left" rowspan="2">
                    <ImgProfile style="height: 50px" :cliente="CLIENTE"></ImgProfile>
                </td>
                <td colspan="3"> {{ CLIENTE.nome }} - {{ CLIENTE.cpf_cnpj }}
                    {{ CLIENTE.endereco }} - {{ CLIENTE.cidade }}- {{ CLIENTE.estado }}<br>
                    cep: {{ CLIENTE.cep }}
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    TRANSFERENCIA DE ESTOQUES
                </td>
            </tr>
            <tr>
                <td>
                    Código<br>
                    <b>{{ transferencia.id }}</b>
                </td>
                <td colspan="2">
                    Responsável<br>
                    <ImgProfileUsuario style="height: 40px"
                                       class="mr-2"
                                       :usuario="transferencia.realizado_por"></ImgProfileUsuario>
                    <b>{{ transferencia.realizado_por.nome }}</b>
                </td>
                <td colspan="2">
                    Data da Transferencia<br>
                    <b>{{ transferencia.realizado_em | dateTimeFormat('DD/MM/YYYY HH:mm') }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Origem
                </td>
                <td colspan="2">
                    Destino
                </td>
            </tr>
            <tr>

                <td>
                    <ImgProfile style="height: 50px"
                                :cliente="transferencia.origem_empresa"></ImgProfile>
                </td>
                <td>
                    <b>{{ transferencia.origem_empresa.nome }} - {{ transferencia.origem_empresa.cpf_cnpj }}</b></td>

                <td>
                    <ImgProfile style="height: 50px"
                                :cliente="transferencia.cliente"></ImgProfile>
                </td>
                <td>
                    <b>{{ transferencia.cliente.nome }} - {{ transferencia.cliente.cpf_cnpj }}</b></td>
            </tr>

            <tr>

                <td colspan="2">
                    <b>{{ transferencia.origem_estoque.nome }}</b>
                    / <b>{{ transferencia.origem_armazem.nome }}</b>
                </td>

                <td colspan="2">
                    <b>{{ transferencia.estoque.nome }}</b> /
                    <b>{{ transferencia.armazem.nome }}</b>
                </td>

            </tr>

            <tr>
                <td colspan="4">Notas<br><b>{{ transferencia.notas }}</b></td>
            </tr>

        </table>

        <table class="table table-striped table-borderless">
            <thead>
            <tr>
                <th colspan="5">Itens Movimentados</th>
            </tr>
            <tr>
                <th>Item</th>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Unidade</th>
                <th>Ncm</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(n,i) in itensCmpt" v-if="n.operacao=='ENTRADA'">
                <td>{{ i + 1 }}</td>
                <td>{{ n.produto.nome }}</td>
                <td>{{ n.quantidade | currency('') }}</td>
                <td>{{ n.produto.unidade.unidade }}</td>
                <td>{{ n.produto.ncm }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import ImgProfile from "@/components/geral/cliente/ImgProfile";
import ImgProfileUsuario from "@/components/geral/perfil/ImgProfile";

export default {
    name: "TemplateImpressao",
    components: {ImgProfile, ImgProfileUsuario},
    props: ['transferencia'],
    computed: {
        CLIENTE() {
            return this.$root.CLIENTE
        },
        itensCmpt() {
            return this.transferencia.itens.filter(i => i.operacao === 'ENTRADA')
        }
    }
}
</script>

<style scoped>

table tr td {
    vertical-align: middle;
}

</style>
