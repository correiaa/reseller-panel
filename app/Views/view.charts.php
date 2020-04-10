<div class="row">
  <div class="col-md-12">
    <div class="card card-chart">
      <div class="card-header ">
        <h5 class="card-title">Last Year Income Breakdown</h5>
      </div>
      <div class="card-body ">

        <div id="chartActivity" class="ct-chart"></div>

      </div>
      <div class="card-footer ">
        <hr>
        <div class="stats">

        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">

var incomeData = {};

$.ajax({
    type: 'GET',
    url: 'http://' + window.location.host + '/charts/last-year-income',
    async: false,
    success: function(data) {
        incomeData = {
          labels: Object.keys(data),
          series: [
            Object.values(data)
          ]
        }
    }
});


var options = {
    seriesBarDistance: 10,
    axisX: {
        showGrid: false
    },
    height: "245px"
};

var responsiveOptions = [
  ['screen and (max-width: 640px)', {
    seriesBarDistance: 5,
    axisX: {
      labelInterpolationFnc: function (value) {
        return value[0];
      }
    }
  }]
];

Chartist.Line('#chartActivity', incomeData, options, responsiveOptions);
</script>
