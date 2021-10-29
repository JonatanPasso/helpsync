<template>
    <b-card>
        <template #header>
            <span class="text-info"><i class="fa fa-table"></i> Marcas</span>
        </template>

        <b-row>

            <b-col>
                <b-input-group size="sm"
                               prepend-html="<i class='fa fa-search'>">
                    <b-form-input v-model="filtro.contem" placeholder="Filtrar"></b-form-input>
                </b-input-group>
            </b-col>

            <!-- paginacao-->
            <b-col class="text-right">

                <div class="text-center d-inline-block">
                    Registros: {{pageData.total}}&nbsp;
                </div>

                <div class="d-inline-block">
                    <b-pagination
                        size="sm"
                        v-model="currentPage"
                        :total-rows="pageData.total"
                        :per-page="pageData.per_page"
                        align="center"></b-pagination>
                </div>

            </b-col>
        </b-row>

        <b-overlay :show="ajaxStatus && operacao == 'cge'" rounded="sm">
            <b-table
                table-variant="bordered"
                selectable
                select-mode="single"
                @row-selected="$emit('select',$event)"
                :fields="campos" :items="dados">
            </b-table>
        </b-overlay>

    </b-card>
</template>

<script>
    export default {
        name: "Grid",
        data() {
            return {
                campos: [
                    {label: 'Código', key: 'id'},
                    {label: 'Nome', key: 'nome', sortable: 1},
                    {
                        label: 'Fabricante', key: 'fabricante.nome', formatter(value) {
                            return value ?? 'nenhum'
                        }
                    },
                ],

                currentPage: 1,
                pageData: {
                    total: 0,
                    per_page: 100,
                    data: []
                },
                filtro: {
                    contem: ''
                }

            }
        },
        computed: {
            dados() {
                return this.pageData.data;
            }
        },

        watch: {
            'filtro.contem'(value) {
                this.delayed();
            }
        },

        methods: {
            carregarGrid() {

                this.operacao = 'cge';

                this.doGet('estoque/marcas/buscar', {
                    filtro: this.filtro,
                    page: this.currentPage,
                    include: ['fabricante']
                }).then(r => {
                    this.pageData = r;
                    this.operacao = '';
                }).fail(e => this.operacao = '');
            }
        },
        created() {
            this.carregarGrid();
            this.delayed = _.throttle(this.carregarGrid, 500)
        }

    }
</script>

<style scoped>

</style>
