<template>
    <b-card class="shadow-lg">
        <template #header>
            <span class="text-info">Panel de Notas Fiscais</span>
        </template>

        <b-row>
            <b-col>
                <h5>Notas por Municipio</h5>
                <PizzaMunicipios :height="200"
                                 :dados="contadores"></PizzaMunicipios>
            </b-col>

        </b-row>

        <b-overlay :show="ajaxStatus">
            {{
                paginacaoNotas.total | currency({
                    symbol: '',
                    thousandsSeparator: '.',
                    fractionCount: 0,
                    fractionSeparator: ',',
                    symbolPosition: 'front',
                    symbolSpacing: true
                })
            }} Notas

            <b-pagination size="sm"
                          v-model="paginacaoNotas.current_page"
                          :total-rows="paginacaoNotas.total"
                          :per-page="paginacaoNotas.per_page"
                          aria-controls="my-table"
                          align="center">
            </b-pagination>

            <div class="table-responsive ajustar">
                <b-table :fields="grid"
                         striped
                         size="sm"
                         :items="paginacaoNotas.data"></b-table>
            </div>

        </b-overlay>

    </b-card>
</template>

<script>

import Vue from 'vue';
import PizzaMunicipios from "@/components/comercial/PizzaMunicipios";

export default {
    name: "PainelNotas",
    components: {PizzaMunicipios},
    data() {
        return {
            paginacaoNotas: {
                current_page: 1,
                data: [],
                per_page: 100
            },
            contadores: []
        }
    },

    watch: {
        'paginacaoNotas.current_page'(valor) {
            this.queryNotas();
        }
    },

    computed: {
        grid() {
            return [
                {key: 'ide_serie', label: 'Serie'},
                {key: 'ide_nnf', label: 'Número'},
                {
                    key: 'ide_dhemi', label: 'Emissao', formatter(value) {
                        return moment(value).format('DD/MM/YYYY')
                    }
                },
                {
                    key: 'ide_tpnf', label: 'tipo', formatter(value) {
                        return value == '1' ? 'saida' : 'entrada'
                    }
                },
                {key: 'emit_cnpj', label: 'Doc Emissor'},
                {key: 'emit_xnome', label: 'Emissor'},

                {key: 'dest_cnpj', label: 'Doc Destinatario'},
                {key: 'dest_xnome', label: 'destinatario'},
                {key: 'dest_enderdest_uf', label: 'Uf Dest'},
                {key: 'dest_enderdest_xmun', label: 'Munic Dest'},

                {
                    key: 'total_icmstot_vprod', label: 'Total', formatter(value) {
                        return Vue.filter('currency')(value)
                    }
                }
            ]
        }
    },

    methods: {

        queryNotas() {
            this.doGet('comercial/nota-fiscal/buscar', {
                page: this.paginacaoNotas.current_page
            })
                .then(r => this.paginacaoNotas = r)
        },

        queryContadores() {
            this.doGet('comercial/nota-fiscal/contadores')
                .then(r => this.contadores = r);
        }
    },

    created() {

        this.queryNotas();
        this.queryContadores();

    }
}
</script>

<style scoped>

div.ajustar table {
    font-size: 10px;
}

</style>
