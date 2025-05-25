function productionSyntheseAnnee(cible, categorie = 0, hnf = 0, hmo = 0, moyenne = 0, th = 0) {
  var myChart = echarts.init(document.getElementById(cible));
  // const hnf = hnf;
  // const hmo = hmo;
  var option = {

    tooltip: {
      padding: [16, 16, 16, 16],
      borderWidth: 0.5,
      borderColor: "var(--border-form)",

      formatter: function (params) {
        //console.log(params);
        var hmo_titre = params[1].seriesName;
        var hmo_periode = params[1].axisValue;
        var hmo_valeur = params[1].data;
        var hnf_titre = params[0].seriesName;
        var hnf_periode = params[0].axisValue;
        var hnf_valeur = params[0].data;
        var total = parseInt(hmo_valeur) + parseInt(hnf_valeur);
        var pourcentHNF = parseInt(hnf_valeur) * 100 / total;
        var approx = parseInt(total) * parseInt(th);
        return `<p class="titre_menu_item">${hnf_periode}</p><br>${hmo_titre} :<span class="pull-right">${hmo_valeur.toFixed(2)}</span> <br>${hnf_titre} :<span class="pull-right">${hnf_valeur.toFixed(2)}</span> <br>Total heures : <span class="pull-right">${total.toFixed(2)}</span><br>CA : <span class="pull-right">${approx.toFixed(2)} €TTC</span>  <br><br><span class="text-bold text-primary">% HNF : </span><span class="text-bold text-primary pull-right">${pourcentHNF.toFixed(2)}</span>`;
      },
      trigger: 'axis',
      axisPointer: {
        type: 'line',
        label: {
          backgroundColor: '#6a7985'
        }
      }
    },
    legend: {
      type: "plain",
      data: ['Heures MO', 'Heures NF', 'Moyenne TOT']
    },
    toolbox: {
      feature: {
        saveAsImage: { title: "Téléchargez image" }
      }
    },
    grid: {
      tooltip: {

        textStyle: {
          width: 450,
          height: 100,

        },
        borderWidth: 0.5,
        borderColor: "var(--border-form)"
      },
      left: '3%',
      right: '3%',
      bottom: '3%',
      containLabel: true
    },
    xAxis: [
      {
        type: 'category',
        boundaryGap: false,
        data: categorie,
        axisLine: {
          show: false
        },
        // smooth: false
      }
    ],
    yAxis: [
      {
        type: 'value',
        show: false
      }
    ],
    series: [

      {
        name: 'Heures NF',
        type: 'line',
        symbol: "circle",
        symbolSize: 10,
        itemStyle: {
          color: "#3d76ad50"
        },
        lineStyle: {
          width: 3
        },
        // areaStyle: {
        //   color: "#ccc",

        // },
        markPoint: {
          data: [{
            type: "average"
          }],
          symbol: "pin",
          itemStyle: {
            color: "#3d76ad50"
          }
        },
        stack: 'total',
        smooth: true,
        // areaStyle: {},
        emphasis: {
          focus: 'series'
        },
        data: hnf
      },
      {
        name: 'Heures MO',
        type: 'line',
        symbol: "circle",
        symbolSize: 10,
        itemStyle: {
          color: "#3d76ad"
        },
        lineStyle: {
          width: 3
        },
        // areaStyle: {
        //   color: "#ccc"
        // },
        markPoint: {
          data: [{
            type: "average"
          }],
          symbol: "pin",
          itemStyle: {
            color: "#3d76ad"
          }
        },
        stack: 'total',
        smooth: true,
        // areaStyle: {},
        emphasis: {
          focus: 'series'
        },
        data: hmo
      },


      {
        name: 'Moyenne TOT',
        type: 'line',
        symbol: "circle",
        symbolSize: 4,
        itemStyle: {
          color: "#777"
        },
        lineStyle: {
          type: "dotted"
        },
        lineStyle: {
          width: 1
        },
        // areaStyle: {
        //   color: "#eeeeee80",

        // },
        markPoint: {
          data: [{
            type: "average"
          }],
          symbol: "pin",
          itemStyle: {
            color: "#777"
          }
        },
        // stack: 'total',
        smooth: false,
        // areaStyle: {},
        emphasis: {
          focus: 'series'
        },
        data: moyenne
      },




    ]
  };
  myChart.setOption(option);
}

