<template>
  <div class="chart"></div>
</template>

<script>
import Anychart from 'anychart'
export default {
  props: {
      statData: {
        type: [Object, Array]
      }, 
      fill: {
        default: '#EB5757',
        type: String
      }, 
      title: {
        default: '',
        type: String
      }, 
      Anychart, 
      stroke: {
        default: '#EB5757',
        type: String
      },
      month: {
        default() {
          return {}
        },
        type: Object
      }
      },
  name: 'AnychartColumn',
  data() {
    return {
      chart: null,
    }
  },
  mounted() {
    if (!this.chart && this.options) {
      this.init()
    }
  },
  computed: {
    options() {
        let data = []
        this.statData.forEach(item => {
          data.push({
            x: this.month[item.id],
            value: item.count,
            fill: this.fill || '#1890ff',
            stroke: this.stroke || '#1890ff'
          })
        })
        return {
          chart: {
            type: 'column',
            barGroupsPadding: 0.9,
            xAxes: [
              {
                labels: {
                  rotation: -85,
                  fontSize: 10,
                  wordWrap: 'break-word',
                  hAlign: 'end',
                }
              }
            ],
            yAxes: [
              {
                labels: {
                  fontSize: 10
                }
              }
            ],
            series: [
              {
                data
              }
            ],
            // title: {
            //   text: this.title,
            //   align: 'left',
            //   fontSize: 18,
            //   fontFamily: 'SFProDisplay',
            //   fontColor: '#323136',
            //   fontWeight: 500,
            //   margin: {
            //     bottom: 30
            //   }
            // }
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
