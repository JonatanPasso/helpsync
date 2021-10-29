<template>
    <div :class="nocard ? '':'card shadow mb-4'">
        <!-- Card Header - Dropdown -->
        <div v-if="header"
             class="card-header py-3 d-flex flex-row align-items-center justify-content-between print-hide">

            <h6 class="m-0 font-weight-bold text-primary">
                <slot name="header">
                    <i class="fa fa-pager"></i> Titulo card
                </slot>
            </h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">

            <slot>Conteudo card</slot>

        </div>

        <div class="card-footer print-hide" v-if="footer">
            <slot name="footer">Rodape card</slot>
        </div>

    </div>
</template>

<script>
    export default {
        name: "Card",
        props: {
            footer: {
                default: true,
            },
            header: {
                default: true
            },
            nocard: {
                default: false
            }
        }
    }
</script>

<style scoped>

</style>
