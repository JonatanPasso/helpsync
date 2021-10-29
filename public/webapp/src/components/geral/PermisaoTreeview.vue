<template>

    <div>

        <ul v-for="(i,key) in acessos">

            <li v-if="!i.id"><label><input @click="selAll({recurso:i,status:$event.target.checked})"
                                           :checked="teste(i)"
                                           type="checkbox"> {{ key }}</label>
                <PermisaoTreeview :acessos="i"
                                  @sel="selItem"
                                  @selAll="selAll"></PermisaoTreeview>
            </li>

            <li v-else>

                <label :title="i.descricao">

                    <input @change="selItem({recurso:i,status:$event.target.checked})"
                           :checked="i.acesso == 'Y'"
                           type="checkbox">&nbsp;<span class="badge badge-success">{{i.tipo}}</span> {{i.nome }}</label>

            </li>
        </ul>

    </div>

</template>

<script>

    export default {
        name: "PermisaoTreeview",
        props: ['acessos'],
        data() {
            return {
                todos: false,
                nao: 0,
            }
        },
        methods: {
            selItem(item) {
                this.$emit('sel', item);
            },
            selAll(item) {
                this.$emit('selAll', item)
            },
            teste(lista){
                return JSON.stringify(lista).indexOf('"acesso":"Y"') > -1 ? true : false;
                return 1
            }
        }
    }

</script>

<style scoped>
    label {
        cursor: pointer;
    }

    ul li {
        list-style: none;
    }
</style>
