function productionSyntheseMois(cible) {
  var myChart = echarts.init(document.getElementById(cible));
  var option = {

    tooltip: {
      trigger: 'axis',
      axisPointer: {
        type: 'cross',
        label: {
          backgroundColor: '#6a7985'
        }
      }
    },
    legend: {
      data: ['HeuresNF', 'HMO']
    },
    // toolbox: {
    //   feature: {
    //     saveAsImage: {}
    //   }
    // },
    grid: {
      left: '1%',
      right: '1%',
      bottom: '1%',
      containLabel: true
    },
    xAxis: [
      {
        type: 'category',
        boundaryGap: true,
        data: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31],
        axisLine: {
          show: false
        },
        smooth: true
      }
    ],
    yAxis: [
      {
        type: 'value',
        show:false
      }
    ],
    series: [
      {
        name: 'HeuresNF',
        type: 'line',
        stack: 'Total',
        smooth: true,
        areaStyle: {},
        emphasis: {
          focus: 'series'
        },
        data: [256, 412, 478, 125, 125, 874, 569, 290, 234, 191, 182, 220, 210, 230, 90, 134, 101, 132, 120, 290, 234, 191, 182, 220, 210, 230, 90, 134, 101, 132, 120]
      },
      {
        name: 'HMO',
        type: 'line',
        stack: 'Total',
        smooth: true,
        areaStyle: {},
        emphasis: {
          focus: 'series'
        },
        data: [120, 132, 101, 134, 90, 230, 210, 220, 182, 191, 234, 290, 120, 132, 101, 134, 90, 230, 210, 220, 182, 191, 234, 290, 569, 874, 125, 125, 478, 412, 256]
      },



    ]
  };
  myChart.setOption(option);
}

