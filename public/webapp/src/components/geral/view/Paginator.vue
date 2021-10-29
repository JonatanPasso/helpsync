<template>
    <div>
        <nav aria-label="Page navigation example" v-if="paginate.last_page > 1">
            <ul class="pagination justify-content-center">

                <li class="page-item">
                    <a class="page-link"
                       @click.prevent="current_page > 1 ? current_page-- : null"
                       href="#"
                       aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Voltar</span>
                    </a>
                </li>

                <li v-for="i in paginate.last_page"
                    :class="{active:i==current_page}"
                    class="page-item">
                    <a class="page-link"
                       @click.prevent="current_page=i" href="#">{{ i }}</a>
                </li>


                <li class="page-item">
                    <a class="page-link"
                       @click.prevent="current_page < paginate.last_page ? current_page++ : null"
                       href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Proximo</span>
                    </a>
                </li>
            </ul>
        </nav>

        {{paginate.total}} Registros

    </div>
</template>

<script>
    export default {
        name: "Paginator",
        props: {
            paginate: {
                type: Object,
                required: true,
            }
        },
        data() {
            return {current_page: 1}
        },
        watch: {
            current_page() {
                this.$emit('change-page', this.current_page)
            }
        }
    }
</script>

<style scoped>

</style>
