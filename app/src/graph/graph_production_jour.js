function productionJour(cible, hnf = 1, hf = 1, colhnf) {
  var myChart = echarts.init(document.getElementById(cible));
  option = {

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
      color: ['#3d76ad', colhnf],
      data: [{
        value: hf,
        name: 'Heures facturables',
        colorBy: 'series',
      },
      {
        value: hnf,
        name: 'Heures non facturables',
        colorBy: 'series',
      }
      ]
    }]
  };
  console.log(option);
  myChart.setOption(option);
};