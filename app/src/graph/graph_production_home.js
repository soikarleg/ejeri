function productionHome(cible, color, mo, nf) {
  var myChart = echarts.init(document.getElementById( cible));
  var option = {
    tooltip: {
      trigger: 'item',
      formatter: '{b}</br>{c} hrs </br> {d} %'
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
      color: ['#3d76ad',  color ],
      data: [
        {
          value: mo,
          name: 'Heures facturables',
          colorBy: 'series',
        },
        {
          value: nf,
          name: 'Heures non facturables',
          colorBy: 'series',
        }
      ]
    }]
  };
  myChart.setOption(option);
}

