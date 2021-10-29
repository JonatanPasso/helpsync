<template>

    <div>
        <b-button type="button"
                  v-bind="$attrs"
                  v-b-modal="'mBuscarGruposUsuarios'">
            <slot>
                <i class="fas fa-users"></i> Buscar Grupos
            </slot>

        </b-button>

        <b-modal id="mBuscarGruposUsuarios"
                 size="xl"
                 body-class="ajustes-modal"
                 cancel-title="Fechar"
                 ok-title="Selecionar"
                 :ok-disabled="(usuarioModal===null)"
                 @ok="$emit('select',usuarioModal)"
                 title="Buscar Grupos de  Usuários">
            <GridGruposUsuarios key="usuario"
                                :modal="true"
                                @select="usuarioModal=$event"></GridGruposUsuarios>
        </b-modal>

    </div>
</template>

<script>

    import GridGruposUsuarios from "./GridGruposUsuarios";

    export default {
        inheritAttrs: false,
        name: "BtnBuscarGruposUsuarios",
        components: {GridGruposUsuarios},
        data() {
            return {
                teste: null,
                usuarioModal: null
            }
        }
    }
</script>

<style lang="scss">

    #mBuscarGruposUsuarios {
        .ajustes-modal {
            padding: 0px;
        }
    }
</style>
