<script>

import {Doughnut} from 'vue-chartjs'
import Util from "@/util";
// import ChartDataLabels from 'chartjs-plugin-datalabels';

export default {
  name: "PercentualAtividades",
  extends: Doughnut,
  props: ['dados'],

  methods: {
    renderme(dtAux, nm, cl, tot) {

      var dados = {
        labels: nm,
        datasets: [{

          data: dtAux,

          backgroundColor: cl,
          borderColor: cl,
          borderWidth: 1,
          barPercentage: 0.5,
        }]
      };

      var options = {
        // plugins: [ChartDataLabels],
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

        title: {
          display: true,
          text: 'Atividades por Departamentos'
        },

        legend: {
          display: false,
          labels: {
            fontColor: 'rgb(255, 99, 132)'
          }
        },

        plugins: {
          datalabels: {
            formatter: (value, ctx) => {
              return Math.round((value / tot) * 100) + '%';
            },
            color: 'blue',
          }
        }
      };

      this.renderChart(dados, options);
    }
  },

  watch:{
    dados(d){
      if(d.length){

        var dataAux = [];
        var nmAux = [];
        var color = [];
        var totalSomado = 0;

        $.each(d, function (index, value) {
          totalSomado = totalSomado+value.n;
          color.push(Util.getRandomColor());
          nmAux.push(value.nome+'--'+value.nm_atividade);
          dataAux.push(value.n);
        });

        this.renderme(dataAux, nmAux, color, totalSomado);
      }
    }
  },

  data: function (){
    return {
      dataAux: []
    };
  }
};
</script>

<style scoped>

</style>
