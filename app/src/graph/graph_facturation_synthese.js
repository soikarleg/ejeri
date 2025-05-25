function factureSynthese(cible, ca, can, mois) {
  var myChart = echarts.init(document.getElementById(cible));

  var option = {
    tooltip: {
      padding: [16, 16, 16, 16],
      borderWidth: 0.5,
      borderColor: "var(--border-form)",
      trigger: 'axis',
      axisPointer: {
        // Use axis to trigger tooltip
        type: 'line' // 'shadow' as default; can also be 'line' or 'shadow'
      }
    },
    legend: {},
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
    yAxis: {
      type: 'value',
      show: false
    },
    xAxis: [
      {
        type: 'category',
        boundaryGap: false,
        data: mois,
        axisLine: {
          show: false
        },
        // smooth: false
      }
    ],
    series: [
      {
        name: 'CA',
        type: 'bar',
        color:'#3d76ad',
          showBackground: false,
        backgroundStyle: {
          color: 'rgba(180, 180, 180, 0.1)'
        },
        stack: 'total',
        label: {
          show: true
        },
        emphasis: {
          focus: 'series'
        },
        data: ca,
        // markPoint: {
        //   data: [{
        //     type: "max"
        //   }],
        //   symbol: "pin",
        //   itemStyle: {
        //     color: "#3d76ad"
        //   }
        // },
      },
      {
        name: 'CA attente de règlement',
        type: 'bar',
        color: '#3d76ad50',
        showBackground: false,
        backgroundStyle: {
          color: 'rgba(180, 180, 180, 0.1)'
        },
        stack: 'total',
        label: {
          show: true
        },
        emphasis: {
          focus: 'series'
        },
        data: can,
        // markPoint: {
        //   data: [{
        //     type: "average"
        //   }],
        //   symbol: "pin",
        //   itemStyle: {
        //     color: "#3d76ad50"
        //   }
        // },
      },

    ]
  };
  myChart.setOption(option);
}

