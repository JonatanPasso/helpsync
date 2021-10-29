<script>

import {Doughnut} from 'vue-chartjs'

export default {
  name: "PercentualChamados",
  extends: Doughnut,
  props: ['dados'],

  methods: {
    renderme() {

      var totalSomado = this.dados[0].qtde_status + this.dados[1].qtde_status + this.dados[2].qtde_status + this.dados[3].qtde_status;

      var dados = {
        labels: [
            Math.round((this.dados[0].qtde_status * 100) / totalSomado)+'% Caixa de Entrada',
            Math.round((this.dados[1].qtde_status * 100) / totalSomado)+'% Iniciados',
            Math.round((this.dados[2].qtde_status * 100) / totalSomado)+'% Concluídos',
            Math.round((this.dados[3].qtde_status * 100) / totalSomado)+'% Finalizados',
        ],
        datasets: [{

          data: [
            this.dados[0].qtde_status,
            this.dados[1].qtde_status,
            this.dados[2].qtde_status,
            this.dados[3].qtde_status
          ],

          backgroundColor: [
            '#D17732',
            '#2170A6',
            '#AAC1E0',
            '#F4BD98',
          ],
          borderColor: [
            '#a75413',
            '#1c5e8a',
            '#80afe5',
            '#ea9b64',
          ],
          borderWidth: 1,
          barPercentage: 0.5,
        }]
      };

      var options = {
        plugins: {
          datalabels: {
            color: 'blue',
            labels: {
              title: {
                font: {
                  weight: 'bold'
                }
              },
              value: {
                color: '#000'
              }
            }
          }
        },

        // scales: {
        //   yAxes: [{
        //     ticks: {
        //       beginAtZero: true,
        //       display: false,
        //     }
        //   }],
        //   xAxes: [{
        //     gridLines: {
        //       offsetGridLines: true,
        //       display: false,
        //     }
        //   }]
        // },

        tooltips: {
          enabled: true,
          mode: 'index'
        },

        // legend: {
        //   display: true,
        //   labels: {
        //     fontColor: 'rgb(255, 99, 132)'
        //   }
        // }
      };

      this.renderChart(dados, options);
    }
  },

  watch:{
    dados(d){
      if(d.length) this.renderme();
    }
  }
};
</script>

<style scoped>

</style>
