<template>
    <b-card>
        <template #header>
            <span class="text-info"><i class="fa fa-table"></i> Histório de Transferência</span>
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
                striped
                empty-text="Nenhum registro"
                show-empty
                class="flux-table-text-align-middle"
                select-mode="single"
                @row-selected="$emit('select',$event)"
                :fields="campos" :items="dados">

                <template v-slot:cell(origem)="data">

                    <div class="text-nowrap">

                        <ImgProfileEmpresa style="height: 50px;vertical-align: top"
                                           class="d-inline-block mr-1"
                                           :cliente="data.item.origem_empresa"></ImgProfileEmpresa>

                        <div class="d-inline-block text-wrap">
                            {{ data.item.origem_empresa.nome | wfirst }} <i class="fa fa-caret-right"></i>
                            {{ data.item.origem_estoque.nome | wfirst }} <i class="fa fa-caret-right"></i>
                            {{ data.item.origem_armazem.nome | wfirst }}
                        </div>
                    </div>
                </template>

                <template v-slot:cell(destino)="data">
                    <div class="text-nowrap">
                        <ImgProfileEmpresa style="height: 50px;vertical-align: top"
                                           class="mr-1"
                                           :cliente="data.item.cliente"></ImgProfileEmpresa>
                        <div class="d-inline-block text-wrap">
                            {{ data.item.cliente.nome | wfirst }} <i class="fa fa-caret-right"></i>
                            {{ data.item.estoque.nome | wfirst }} <i class="fa fa-caret-right"></i>
                            {{ data.item.armazem.nome | wfirst }}
                        </div>
                    </div>
                </template>

                <template v-slot:cell(realizado_por)="data">
                    <div style="white-space: nowrap">
                        <ImgProfile :usuario="data.item.realizado_por" style="height: 40px"></ImgProfile>
                        {{ data.item.realizado_por.nome | wfirst }}
                    </div>
                </template>

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
import ImgProfile from "@/components/geral/perfil/ImgProfile";
import ImgProfileEmpresa from "@/components/geral/cliente/ImgProfile"

export default {
    name: "Grid",
    components: {ImgProfile, ImgProfileEmpresa},
    data() {
        return {
            campos: [
                {label: 'Código', key: 'id'},

                {label: 'Origem (de)', key: 'origem'},
                {label: 'Destino (para)', key: 'destino'},

                {label: 'Responsável', key: 'realizado_por'},
                {
                    label: 'Data', key: 'realizado_em',
                    sortable: 1,
                    formatter(value) {
                        return Vue.filter('dateTimeFormat')(value, 'DD/MM/YYYY HH:mm')
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
                contem: '',
                tipo: ['TRANFERENCIA']
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
                include: [
                    'cliente',
                    'estoque',
                    'armazem',
                    'realizadoPor',
                    'documento',
                    'itens.produto.unidade',
                    'origemEmpresa',
                    'origemEstoque',
                    'origemArmazem'
                ]
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
