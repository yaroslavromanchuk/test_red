$(function(){
  'use strict';

  $('.sparkline1').sparkline('html', {
    type: 'bar',
    barWidth: 5,
    height: 50,
    barColor: '#0083CD',
    chartRangeMin: 0,
    chartRangeMax: 10
  });

  $('.sparkline2').sparkline('html', {
    type: 'bar',
    barWidth: 10,
    height: 100,
    barColor: '#fff',
    lineColor: 'rgba(255,255,255,0.5)',
    chartRangeMin: 0,
    chartRangeMax: 10
  });

  
  $('.sparkline0').sparkline('html', {
    type: 'bar',
    barWidth: 30,
    height: 200,
    barColor: '#0083CD',
    lineColor: 'rgba(255,255,255,0.5)',
    chartRangeMin: 0,
    chartRangeMax: 10
  });
  });
  