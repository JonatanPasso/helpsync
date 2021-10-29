<template>

    <div>

        <b-button-group v-if="clienteModal">
            <b-button variant="success">
                <i class="fa fa-car"></i> {{clienteModal.placa}}
            </b-button>
            <b-button variant="warning">
                <i class="fa fa-times"></i>
            </b-button>
        </b-button-group>

        <b-button v-else
                  type="button"
                  v-bind="$attrs"
                  v-b-modal="'mBuscarVeiculos'">

            <slot>
                <i class="fas fa-users"></i> Buscar Veículos
            </slot>

        </b-button>

        <b-modal id="mBuscarVeiculos"
                 size="xl"
                 body-class="ajustes-modal"
                 cancel-title="Fechar"
                 ok-title="Selecionar"
                 :ok-disabled="(clienteModal===null)"
                 @ok="$emit('select',clienteModal)"
                 title="Buscar Veículos">

            <GridVeiculos key="veiculos"
                          :modal="true"
                          @select="clienteModal=$event"></GridVeiculos>
        </b-modal>

    </div>
</template>

<script>

    import GridVeiculos from "./GridVeiculos";

    export default {
        inheritAttrs: false,
        name: "BtnBuscarVeiculos",
        components: {GridVeiculos},
        data() {
            return {
                teste: null,
                clienteModal: null
            }
        }
    }
</script>

<style lang="scss">

    #mBuscarVeiculos {
        .ajustes-modal {
            padding: 0px;
        }
    }
</style>
