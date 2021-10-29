<template>

    <div>

        <b-button-group v-if="usuarioModal">

            <b-button variant="success" :title="usuarioModal.nome">
                <div class="text-elipses">{{usuarioModal.nome}}</div>
            </b-button>
            <b-button @click="limparUsuario"
                      variant="warning"><i class="fa fa-times"></i></b-button>

        </b-button-group>

        <b-button type="button" v-else
                  v-bind="$attrs"
                  v-b-modal="'mBuscarUsuario'">

            <slot>
                <i class="fas fa-users"></i> Buscar Usuário
            </slot>

        </b-button>

        <b-modal id="mBuscarUsuario"
                 size="xl"
                 body-class="ajustes-modal"
                 cancel-title="Fechar"
                 ok-title="Selecionar"
                 :ok-disabled="(usuarioModal===null)"
                 @ok="$emit('select',usuarioModal)"
                 title="Buscar Usuários">
            <GridUsuarios key="usuario"
                          :modal="true"
                          @select="usuarioModal=$event"></GridUsuarios>
        </b-modal>

    </div>
</template>

<script>
    import GridUsuarios from "./GridUsuarios";

    export default {
        inheritAttrs: false,
        name: "BtnBuscarUsuarios",
        components: {GridUsuarios},
        data() {
            return {
                teste: null,
                usuarioModal: null
            }
        },
        methods: {
            limparUsuario() {
                this.usuarioModal = null;
                this.$emit('select', null);

            }
        }
    }
</script>

<style lang="scss">

    #mBuscarUsuario {
        .ajustes-modal {
            padding: 0px;
        }
    }

    .text-elipses {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

</style>
