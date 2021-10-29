<script>

import {Bar} from 'vue-chartjs'

export default {
  name: "QuantidadeChamados",
  extends: Bar,
  props: ['dados'],

  methods: {
    renderme() {

      var dados = {
        labels: ['Caixa Entrada', 'Iniciados', 'Concluídos', 'Finalizados'],
        datasets: [{
          label: '# Chamados',
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
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: false,
              display: false
            }
          }],
          xAxes: [{
            gridLines: {
              offsetGridLines: false,
              display: false
            }
          }]
        },
      };

      this.renderChart(dados, options);
    },
  },

  watch:{
    dados(d){
      if(d.length) this.renderme();
    }
  }

}
</script>

<style scoped>

</style>
