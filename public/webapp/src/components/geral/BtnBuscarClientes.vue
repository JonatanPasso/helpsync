<template>

    <div>

        <template v-if="clienteModal">

            <b-button-group>

                <b-button variant="success" :title="clienteModal.nome">
                    <div class="text-elipses">{{clienteModal.nome}}</div>
                </b-button>
                <b-button @click="limparCliente"
                          variant="warning"><i class="fa fa-times"></i></b-button>
            </b-button-group>

        </template>

        <template v-else>

            <b-button type="button"
                      class="btn-block"
                      v-bind="$attrs"
                      v-b-modal="modalId">

                <slot>
                    <i class="fas fa-users"></i> Buscar Clientes
                </slot>

            </b-button>

        </template>

        <b-modal :id="modalId"
                 size="xl"
                 body-class="ajustes-modal"
                 cancel-title="Fechar"
                 ok-title="Selecionar"
                 :ok-disabled="(clienteModal===null)"
                 @ok="$emit('select',clienteModal)"
                 title="Buscar Clientes">
            <GridEmpresas key="clientes"
                          :modal="true"
                          @select="clienteModal=$event"></GridEmpresas>
        </b-modal>

    </div>
</template>

<script>
    import GridEmpresas from "./GridEmpresas";

    import Util from '@/util';

    export default {
        inheritAttrs: false,
        name: "BtnBuscarClientes",
        components: {GridEmpresas},
        data() {

            var uid = Util.uid();
            return {
                teste: null,
                clienteModal: null,
                modalId: uid
            }
        },
        methods: {
            limparCliente() {
                this.clienteModal = null;
                this.$emit('select', null);
            }
        }
    }
</script>

<style lang="scss">

    #mBuscarClientes {
        .ajustes-modal {
            padding: 0px;
        }
    }

    .text-elipses {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px;
    }
</style>
