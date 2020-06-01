$(function () {
  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true


  var listDataValue = [];
  var listDataMonth = [];

  var data = $('#visitors-chart').data('root');

  data.forEach(function (element) {
    listDataValue.push(element.cpi);

  });
  var listDataValue_Predict = [];

  var data_predict = $('#visitors-chart').data('predict');

  data_predict.forEach(function (element) {
    listDataValue_Predict.push(element.cpi);
  });
  var data_month = $('#visitors-chart').data('month');

  data_month.forEach(function (element) {
    listDataMonth.push(element.date_time);
  });

  var $visitorsChart = $('#visitors-chart')
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels: listDataMonth,
      datasets: [{
        type: 'line',
        data: listDataValue,
        backgroundColor: 'transparent',
        borderColor: '#007bff',
        pointBorderColor: '#007bff',
        pointBackgroundColor: '#007bff',
        fill: false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      },
      {
        type: 'line',
        data: listDataValue_Predict,
        backgroundColor: 'tansparent',
        borderColor: '#eb0202',
        pointBorderColor: '#eb0202',
        pointBackgroundColor: '#eb0202',
        fill: false
        // pointHoverBackgroundColor: '#ced4da',
        // pointHoverBorderColor    : '#ced4da'
      }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 2.5
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
})
