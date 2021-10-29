<template>
    <div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>CÓDIGO</th>
                <th colspan="2">NOME</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="d in dados">
                <td>{{ d.codigo }}</td>
                <td>{{ d.nome }}</td>
                <td>
                    <b-btn class="m-0"
                           @click="$emit('del',d)"
                           variant="outline-danger"><i class="fa fa-minus"></i></b-btn>
                </td>
            </tr>
            <tr>
                <td>
                    <b-input v-model="codigo" class="m-0"></b-input>
                </td>
                <td>
                    <b-input v-model="nome" class="m-0"></b-input>
                </td>
                <td>
                    <b-btn class="m-0"
                           @click="add"
                           variant="outline-success"><i class="fa fa-plus"></i></b-btn>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>


export default {
    name: "TblCodigoReferencia",
    components: {},
    props: {
        dados: {
            type: Array,
            default() {
                return []
            }
        }
    },

    data() {
        return {
            codigo: '',
            nome: ''
        }
    },
    methods: {
        add() {
            this.$emit('add', {codigo: this.codigo, nome: this.nome});
            this.codigo = '';
            this.nome = '';
        }
    }
}
</script>

<style scoped>

</style>
