// function rgbaToHex(rgba) {
//   const [r, g, b, a] = rgba.match(/\d+/g).map(Number);
//   return `#${((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1)}`;
// }

// function hexToRgba(hex, alpha = 1) {
//   const bigint = parseInt(hex.slice(1), 16);
//   const r = (bigint >> 16) & 255;
//   const g = (bigint >> 8) & 255;
//   const b = bigint & 255;
//   return `rgba(${r}, ${g}, ${b}, ${alpha})`;
// }

function generateGradientColors(baseColor, steps) {
  const colors = [];
  for (let i = 0; i < steps; i++) {
    const color = echarts.color.lift(baseColor, i /steps);
    colors.push(color);
  }
  console.log(colors);
  return colors;
}

function repartitionContacts(cible, mydata = "", mymin = "", mymax = "") {
  var myChart = echarts.init(document.getElementById(cible));
  var baseColor = "#3e74a9";
  //mydata.length
  var colors = generateGradientColors(baseColor, mydata.length);
  var option = {
    tooltip: {
      trigger: "item",
      formatter: "{b}</br>{c} u. </br> {d} %",
    },
    visualMap: {
      show: false,
      min: mymin,
      max: mymax,
      inRange: {
        colorLightness: [0.25, 0.75],
      },
    },
    legend: {
      top: "5%",
      left: "center",
    },
    series: [
      {
        type: "pie",
        radius: ["40%", "70%"],
        itemStyle: {
          borderRadius: 10,
          borderColor: "#fff",
          borderWidth: 0,
        },
        label: {
          show: false,
          position: "center",
        },
        emphasis: {
          label: {
            show: false,
            fontSize: 17,
            fontWeight: "bold",
          },
        },
        labelLine: {
          show: true,
        },
        data: mydata.map((item, index) => ({
          ...item,
          itemStyle: {
            color: colors[index % colors.length], // Utilise les couleurs du dégradé
          },
        })),
      },
    ],
  };
  myChart.setOption(option);
}