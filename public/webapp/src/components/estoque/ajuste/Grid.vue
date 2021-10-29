<template>
    <b-card>
        <template #header>
            <span class="text-info"><i class="fa fa-table"></i> Histório de Ajustes</span>
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
                    Registros: {{ pageData.total }}&nbsp;
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

        <b-overlay :show="ajaxStatus && operacao == 'cge'" class="ajuste" rounded="sm">
            <b-table
                table-variant="bordered"
                selectable
                select-mode="single"
                @row-selected="$emit('select',$event)"
                :fields="campos" :items="dados">
                <template v-slot:cell(documento.url)="data">
                    <div v-if="l_.get(data,'item.documento.url')">
                        <b-button tag="a"
                                  target="_blank"
                                  :href="l_.get(data,'item.documento.url')">
                            <i class="fa fa-download"></i> {{ data.item.documento.metadata.originalName }}
                        </b-button>
                    </div>
                    <div v-else>Nenhum</div>
                </template>

            </b-table>
        </b-overlay>

    </b-card>
</template>

<script>

import Vue from 'vue';

export default {
    name: "Grid",
    data() {
        return {
            campos: [
                {label: 'Código', key: 'id'},
                {label: 'Empresa', key: 'armazem.estoque.cliente.nome', sortable: 1},
                {label: 'Estoque', key: 'armazem.estoque.nome', sortable: 1},
                {label: 'Armazem', key: 'armazem.nome', sortable: 1},
                {label: 'Responsável', key: 'realizado_por.nome', sortable: 1},
                {
                    label: 'Data', key: 'realizado_em',
                    sortable: 1,
                    formatter(value) {
                        return Vue.filter('dateTimeFormat')(value, 'DD/MM/YYYY HH:mm')
                    }
                },
                {label: 'Notas', key: 'notas', sortable: 1},
                {label: 'Documento', key: 'documento.url', sortable: 1},
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

            this.doGet('estoque/movimento/buscar', {
                filtro: this.filtro,
                page: this.currentPage,
                include: ['armazem.estoque.cliente', 'realizadoPor', 'documento', 'itens.produto']
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

.ajuste table tr td {
    vertical-align: middle !important;
}

</style>
