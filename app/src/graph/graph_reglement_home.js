function reglementHome(cible, color, mo, nf) {
  var myChart = echarts.init(document.getElementById(cible));
  var option = {
    tooltip: {
      trigger: 'item',
      formatter: '{b}</br>{c} €TTC </br> {d} %'
    },
    series: [{
      type: 'pie',
      radius: ['40%', '70%'],
      itemStyle: {
        borderRadius: 10,
        borderColor: '#fff',
        borderWidth: 0
      },
      label: {
        show: false
      },
      labelLine: {
        show: false
      },
      color: ['#3d76ad', color],
      data: [
        {
          value: mo,
          name: 'Réglements',
          colorBy: 'series',
        },
        {
          value: nf,
          name: 'En attente',
          colorBy: 'series',
        }
      ]
    }]
  };
  myChart.setOption(option);
}

