<template>
    <ul class="tree-depar">
        <li v-for="d in departamentos"

            v-if="!d.departamento_pai_id || nolevel">

            <a href="#" @click.prevent.stop="$emit('select',d)"><i class="fa fa-briefcase"></i> {{d.nome}}</a>

            <TreeDepartamento :nolevel="true"
                              v-on:select="$emit('select',$event)"
                              :departamentos="d.filhos"></TreeDepartamento>

        </li>
    </ul>
</template>

<script>
    export default {
        name: "TreeDepartamento",
        props: ['departamentos', 'nolevel']
    }
</script>

<style scoped>

    ul.tree-depar {
        margin-top: 10px;
    }
    .tree-depar li {
        margin-bottom: 10px;
    }

</style>
