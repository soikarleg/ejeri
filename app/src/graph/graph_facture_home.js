function factureHome(cible, color, mo, nf) {
  var myChart = echarts.init(document.getElementById(cible));
  var option = {
    tooltip: {
      trigger: 'item',
      formatter: '{b}</br>{c} u. </br> {d} %'
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
          name: 'Nombre facture réglée',
          colorBy: 'series',
        },
        {
          value: nf,
          name: 'Nombre en attente',
          colorBy: 'series',
        }
      ]
    }]
  };
  myChart.setOption(option);
}

