function repartitionDevis(cible, mydata = "", mymin = "", mymax = "") {
  var myChart = echarts.init(document.getElementById(cible));
  var option = {
    tooltip: {
      trigger: 'item',
      formatter: '{b}</br>{c} u. </br> {d} %'
    },
    // visualMap: {
    //   show: false,
    //   min: mymin,
    //   max: mymax,
    //   inRange: {
    //     colorLightness: [0.25, 0.75]
    //   }
    // },
    legend: {
      top: '5%',
      left: 'center'
    },
    series: [{
      type: 'pie',
      radius: ['40%', '70%'],
      itemStyle: {
        borderRadius: 10,
        borderColor: '#fff',
        borderWidth: 0
        // color: '#3d76ad'
      },
      label: {
        show: false,
        position: 'center'
      },
      emphasis: {
        label: {
          show: false,
          fontSize: 17,
          fontWeight: 'bold'
        }
      },
      labelLine: {
        show: true
      },



      data: mydata,

    }]
  };
  myChart.setOption(option);
}

