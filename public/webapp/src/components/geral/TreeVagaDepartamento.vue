<template>
    <ul class="tree-depar">
        <li v-for="d in vagaDepartamentos"
            v-if="!d.vaga_departamento_pai || nolevel">

            <a href="#" class="text-nowrap" @click.prevent.stop="$emit('select',d)">

                <span v-if="d.usuario_id"
                      class="text-success font-weight-bold">
                    <i class="fa fa-id-badge"></i> {{l_.get(d,'usuario.nome','Usuário Removido')}}
                    <span style="font-size: 12px">( {{d.vaga.nome}} )</span>
                </span>

                <span v-else>
                    <i class="fa fa-briefcase"></i> {{d.vaga.nome}}
                    <span style="font-size: 12px">( VAGO )</span>
                </span>

            </a>

            <TreeVagaDepartamento :nolevel="true"
                                  v-on:select="$emit('select',$event)"
                                  :vaga-departamentos="d.filhos"></TreeVagaDepartamento>

        </li>
    </ul>
</template>

<script>
    export default {
        name: "TreeVagaDepartamento",
        props: ['vaga-departamentos', 'nolevel']
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
