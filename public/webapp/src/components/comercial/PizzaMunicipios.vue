<script>


import {Pie} from 'vue-chartjs';
import Util from '@/util';
import ChartDataLabels from 'chartjs-plugin-datalabels';
import Vue from 'vue';

export default {
    name: "PizzaMunicipios",
    extends: Pie,
    props: ['dados'],

    methods: {

        renderme() {

            var dados = {
                labels: [],
                datasets: [{
                    label: '# Chamados',
                    data: [],

                    backgroundColor: [
                        // '#D17732',
                        // '#2170A6',
                        // '#AAC1E0',
                        // '#F4BD98',
                    ],
                    // borderColor: [
                    //     '#a75413',
                    //     '#1c5e8a',
                    //     '#80afe5',
                    //     '#ea9b64',
                    // ],
                    borderWidth: 1,
                    barPercentage: 0.5,
                }]
            };

            for (var i in this.dados) {
                if (i > 10) break;
                dados.labels.push(this.dados[i].munic);
                dados.datasets[0].data.push(this.dados[i].total);
                dados.datasets[0].backgroundColor.push(Util.getRandomColor())
            }


            var options = {

                tooltips: {
                    enabled: true,
                    callbacks: {
                        label(tooltipItem, data) {

                            return data.labels[tooltipItem.index] + ' : ' + Vue.filter('currency')(data.datasets[0].data[tooltipItem.index])
                        }
                    }
                },

                plugins: {
                    datalabels: {
                        display: false,
                        align: 'bottom',
                        // backgroundColor: '#ccc',
                        color: 'red',
                        borderRadius: 3,
                        font: {
                            size: 14,
                        },
                        formatter: function (value, context) {
                            return Vue.filter('currency')(value)
                        }
                    },
                }

                // plugins: {
                //     datalabels: {
                //         color: 'blue',
                //         labels: {
                //             title: {
                //                 font: {
                //                     weight: 'bold'
                //                 }
                //             },
                //             value: {
                //                 color: '#000'
                //             }
                //         }
                //     }
                // },
                // scales: {
                //     yAxes: [{
                //         ticks: {
                //             beginAtZero: false,
                //             display: false
                //         }
                //     }],
                //     xAxes: [{
                //         gridLines: {
                //             offsetGridLines: false,
                //             display: false
                //         }
                //     }]
                // },
            };

            this.renderChart(dados, options);
        },
    },

    watch: {
        dados(d) {
            if (d.length) this.renderme();
        }
    },
    mounted() {
        if (this.dados.length) this.renderme();
    }

}
</script>

<style scoped>

</style>
