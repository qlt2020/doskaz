<template>
  <div class="chart"></div>
</template>

<script>
import Anychart from 'anychart'
export default {
  props: ['statData', 'title', 'Anychart', 'selectedCategory', 'totalData', 'categoryData', 'rowPie'],
  name: 'AnychartDoughnut',
  data() {
    return {
      chart: null,
      group: this.categoryData,
    }
  },
  mounted() {
    if (!this.chart && this.options) {
      this.init()
    }
  },
  computed: {
    options() {
      const data = []
      let totalCount = null;
      if (!Array.isArray(this.statData)) {
        totalCount = this.totalData
        for (let k in this.statData) {
            let titleGroup =  this.group.filter(item => item.value === k);
            data.push({
              x:  titleGroup[0].title || k,
              value: this.statData[k]
            })
        }
      }
      if (Array.isArray(this.statData)) {
          this.statData.forEach(item => {
            totalCount += item[this.selectedCategory];
            data.push({
              x: item.category_title,
              value: item[this.selectedCategory]
            })
          })
      }

      return {
          "chart": {
            "type": "pie",
            "chartLabels": [
              {
                "zIndex": 50,
                "enabled": true,
                "fontSize": 20,
                "fontColor": '#000000',
                "fontFamily": 'SFProDisplay',
                "fontWeight": 700,
                "textOverflow": "",
                "selectable": false,
                "disablePointerEvents": true,
                "text": totalCount,
                "position": this.rowPie ? "center" : "left-center",
                "anchor": "center",
                "offsetX": this.rowPie ? "-1%" : "25%",
                "offsetY": this.rowPie ? "-9%" : "5%",
              },
            ],
            "title": {
              text: this.title,
              align: 'left',
              fontSize: 18,
              fontFamily: 'SFProDisplay',
              fontColor: '#323136',
              fontWeight: 500,
              margin: {
                left: this.rowPie ? 20 : 10,
                bottom: this.rowPie ? 60 : 10
              }
            },
            "autoRedraw": true,
            "animation": {
              "enabled": true,
              "duration": 500
            },
            "selectRectangleMarqueeFill": {
              "color": "#d3d3d3",
              "opacity": 0.4
            },
            "selectRectangleMarqueeStroke": "#d3d3d3",
            "legend": {
              legendItem: {iconType: "marker"},
              iconType: "marker",
              "usePointStyle": true,
              "zIndex": 200,
              "enabled": true,
              "fontSize": 12,
              "fontFamily": "Verdana, Helvetica, Arial, sans-serif",
              "fontColor": "#535353",
              "fontOpacity": 1,
              "fontDecoration": "none",
              "fontStyle": "normal",
              "fontVariant": "normal",
              "fontWeight": "normal",
              "letterSpacing": "normal",
              "textDirection": "ltr",
              "textShadow": "1111",
              "lineHeight": "normal",
              "textIndent": 1,
              "vAlign": "right",
              "hAlign": "end",
              "wordWrap": "normal",
              "wordBreak": "normal",
              // "textOverflow": "...",
              "selectable": false,
              "disablePointerEvents": false,
              "useHtml": false,
              "inverted": false,
              "itemsLayout": "verticalExpandable",
              "width": this.rowPie ? "100%" : '50%',
              "height": '200px',
              "maxWidth": null,
              "maxHeight": null,
              "position": this.rowPie ? "bottom" : "right",
              "positionMode": "outside",
              "drag": false,
              "itemsFormat": null,
              "titleFormat": null,
              "itemsHAlign": "left",
              "itemsSpacing": 15,
              "itemsSourceMode": "default",
              "hoverCursor": "pointer",
              "iconTextSpacing": 5,
              "align": "bottom",
              "margin": {
                "left": 0,
                "top": 0,
                "bottom": 0,
                "right": 0
              },
              "background": {
                "enabled": false
              },
            
            },
            data,
            "innerRadius": "50%",
            "insideLabelsOffset": "50%",
          }
      }
    }
  },
  methods: {
    removeSeries() {
      if (this.chart.getSeriesCount()) {
        this.chart.removeSeriesAt(0)
      }
    },
    removeAllSeries() {
      if (this.chart.getSeriesCount()) {
        this.chart.removeAllSeries()
      }
    },
    addSeries(data) {
      this.delegateMethod('addSeries', data)
    },
    delegateMethod(name, data) {
      if (!this.chart) {
        warn(
          `Cannot call [$name] before the chart is initialized. Set prop [options] first.`,
          this
        )
        return
      }
      return this.chart[name](data)
    },
    init() {
      if (!this.chart && this.options) {
        let _Anychart = this.Anychart || Anychart
        this.chart = new _Anychart.fromJson(this.options)
        this.chart.container(this.$el).draw()
      }
    }
  },
  watch: {
    options: function(options) {
      if (!this.chart && options) {
        this.init()
      } else {
        this.chart.dispose()
        this.chart = null
        this.init()
      }
    }
  },
  beforeDestroy() {
    if (this.chart) {
      this.chart.dispose()
      this.chart = null
    }
  }
}
</script>
