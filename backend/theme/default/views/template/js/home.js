function nTime(){
$(".m_time").hide();
$(".n_time").show();
return false;
}
function mTime(){
$(".n_time").hide();
$(".m_time").show();
return false;
}
function naTime(){
$(".m_a_time").hide();
$(".n_a_time").show();
return false;
}
function maTime(){
$(".n_a_time").hide();
$(".m_a_time").show();
return false;
}

$(function(){
$('#days').on('shown.bs.modal', function () {
$('.sparkline_days').html($('.days').text());
  $('.sparkline_days').sparkline('html', {
    type: 'bar',
    barWidth: 30,
    height: 200,
    barColor: '#0083CD',
    lineColor: 'rgba(255,255,255,0.5)',
    chartRangeMin: 0,
    chartRangeMax: 10
  });
});
$('#week').on('shown.bs.modal', function () {
$('.sparkline_week').html($('.week').text());
  $('.sparkline_week').sparkline('html', {
    type: 'bar',
    barWidth: 30,
    height: 200,
    barColor: '#0083CD',
    lineColor: 'rgba(255,255,255,0.5)',
    chartRangeMin: 0,
    chartRangeMax: 10
  });
});
$('#month').on('shown.bs.modal', function () {
$('.sparkline_month').html($('.month').text());
  $('.sparkline_month').sparkline('html', {
    type: 'bar',
    barWidth: 30,
    height: 200,
    barColor: '#6e42c1',
    lineColor: 'rgba(255,255,255,0.5)',
    chartRangeMin: 0,
    chartRangeMax: 10
  });
});
$('#year').on('shown.bs.modal', function () {
$('.sparkline_year').html($('.year').text());
  $('.sparkline_year').sparkline('html', {
    type: 'bar',
    barWidth: 30,
    height: 200,
    barColor: '#2b333e',
    lineColor: 'rgba(255,255,255,0.5)',
    chartRangeMin: 0,
    chartRangeMax: 10
  });
});
   

});
/**
 * 
 * @type type
 */

$("#order_gryde_form_id").submit(function(){
if(!$("#gryde").val()){
    $("#gryde").focus();
    alert('Укажите грейд');
    return false;
}
    var f1 = new Date($('#one_date_from_gryde').val());
   var t1 = new Date($('#one_date_to_gryde').val());
   
   var f2 = new Date($('#two_date_from_gryde').val());
   var t2 = new Date($('#two_date_to_gryde').val());
   
   if(f1 > t1){
       alert('Не правильно указана дата');
       return false;
   }
  /* else if(f2 > t2){
        alert('Не правильно указана дата сравнения');
       return false;
   }else if(f2 >= f1  || t2 >= t1){
       alert('Дата сравнения должна быть меньше первой даты');
       return false;
   }*/


console.log($(this).serialize());
console.log($(this).serializeArray());

order_gryde($(this).serialize());
return false;
});
$("#procent-ostatka").submit(function(){

    var f = $(this).serializeArray();
        console.log(f);
       // console.log(f.one-date-from);
   // alert(f);
    var f1 = new Date($('#one-date-from').val());
   var t1 = new Date($('#one-date-to').val());
   var f2 = new Date($('#two-date-from').val());
   var t2 = new Date($('#two-date-to').val());
   
   if(f1 > t1){
       alert('Не правильно указана дата');
       return false;
   }else if(f2 > t2){
        alert('Не правильно указана дата сравнения');
       return false;
   }else if(f2 >= f1  || t2 >= t1){
       alert('Дата сравнения должна быть меньше первой даты');
       return false;
   }

   procent_ostatka($(this).serialize());
    return false;
});

$("#realization_form").submit(function(){

    var f = $(this).serializeArray();
        console.log(f);
       // console.log(f.one-date-from);
   // alert(f);
    var f1 = new Date($('#one_date_from_r').val());
   var t1 = new Date($('#one_date_to_r').val());

   
   if(f1 > t1){
       alert('Не правильно указана дата');
       return false;
   }

   realization($(this).serialize());
    return false;
});


$("#form_prognoz").submit(function(e){
    console.log($(this).serialize());
    prognoz($(this).serialize());
    return false;
});
$("#form_prognoz_brand").submit(function(e){
    console.log($(this).serialize());
    prognozBrand($(this).serialize());
    return false;
});
$("#form_oborot_all").submit(function(e){
    var form = $(this).serialize();
    var from = $('#from_oborot').val();
    var to = $('#to_oborot').val();
    form+='&cat_prognoz=267&from_prognoz='+from+'&to_prognoz='+to;

    oborot_all(form);
    return false;
});
$("#form_oborot_all_grn").submit(function(e){
    var form = $(this).serialize();
    var from = $('#from_oborot').val();
    var to = $('#to_oborot').val();
    form+='&cat_prognoz=267&from_prognoz='+from+'&to_prognoz='+to;
 console.log(form);
    oborot_all_monch(form);
    
    
    return false;
});


$("#form_oborot_root").submit(function(e){
    var form = $(this).serialize();
    var from = $('#from_oborot').val();
    var to = $('#to_oborot').val();
    form+='&from_prognoz='+from+'&to_prognoz='+to;
 console.log(form);
   //  $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
  
    oborot_category(form, 'oborot_root');
    
   //   oborot_root(form, ar, 0); 
    return false;
});
$("#form_oborot_brand").submit(function(e){
    var form = $(this).serialize();
    var from = $('#from_oborot').val();
    var to = $('#to_oborot').val();
    form+='&from_prognoz='+from+'&to_prognoz='+to;
 console.log(form);
   //  $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
  oborot_brand(form, 'oborot_brand');
    return false;
});
$("#form_oborot_graid").submit(function(e){
    var form = $(this).serialize();
    var from = $('#from_oborot').val();
    var to = $('#to_oborot').val();
    form+='&from_prognoz='+from+'&to_prognoz='+to;
 console.log(form);
   //  $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
  oborot_graid(form, 'oborot_graid');
    return false;
});
$("#form_oborot_graid_category").submit(function(e){
    var form = $(this).serialize();
    var from = $('#from_oborot').val();
    var to = $('#to_oborot').val();
    form+='&from_prognoz='+from+'&to_prognoz='+to;
 console.log(form);
   //  $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
  oborot_graid_category(form, 'oborot_graid');
    return false;
});

$("#form_oborot_category").submit(function(e){
   // var form_array  = $(this).serializeArray();
    var form = $(this).serialize();
    //console.log(form_array);
    var from = $('#from_oborot').val();
    var to = $('#to_oborot').val();
    form+='&from_prognoz='+from+'&to_prognoz='+to;
    console.log(form);
    oborot_category(form, 'oborot_category');
    return false;
});
function oborot_category(form, element){
  //  console.log(form);
   // console.log(element);
    //return false;
    
    if(element == 'oborot_category'){
         var cat = $('#cat_oborot_category option:selected').text();
    }else{
        var cat = 'Главные категории';
    }
   
    console.log(cat);
            //var date =[];
		$.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form,
                success: function (res) {
			console.log(res);
                        //  date = res;
                },
		error: function (res) {
			console.log(res);
		}
            }).done(function(date) {
                if(date.length > 0){
                 chart.addSeries({data: date[0].niz, name:'21', type: 'area', zIndex: 4,color: '#23bf08', marker: {enabled: false}});
                 chart.addSeries({data: date[0].norma, name:'28', type: 'area', zIndex: 3, color: '#17f2f4',  marker: {enabled: false}});
                 chart.addSeries({data: date[0].verch, name:'45', type: 'area', zIndex: 2,color: '#ff0018', marker: {enabled: false}});
                 chart.xAxis[0].setCategories(date[0].x);
                 var z = 5;
                for(var k in date){
                    chart.addSeries({data: date[k].oborot, name:date[k].cat,  type: 'spline', zIndex: z});
                    z++;
                }
            }
                console.log(date);
                    $('.modal-backdrop').hide();
                    $('#foo').detach();
                      });
                      
     
var chart =  new  Highcharts.Chart({
title: {
        text: cat
            },
  chart: {
    renderTo: element
  },

    xAxis: {
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Недели'
    }
            },
    yAxis: {
                title: {
                    text: 'Дни'
                },
                 categories: [0]
            },
    tooltip: {
        shared: true
    }
});
     

return false;

}
function oborot_brand(form, element){
  //  console.log(form);
   // console.log(element);
    //return false;
    
   // if(element == 'oborot_brand'){
       //  var cat = $('#cat_oborot_category option:selected').text();
    //}else{
        var cat = 'Бренды';
   // }
   
    console.log(cat);
            //var date =[];
		$.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form,
                success: function (res) {
			console.log(res);
                        //  date = res;
                },
		error: function (res) {
			console.log(res);
		}
            }).done(function(date) {
                if(date.length > 0){
                    var l = date[0].verch.length;
                   
                 chart.addSeries({data: date[0].niz, name:date[0].niz_m, type: 'area', zIndex: 4,color: '#23bf08', marker: {enabled: false}});
                 chart.addSeries({data: date[0].norma, name:date[0].norma_m, type: 'area', zIndex: 3, color: '#17f2f4',  marker: {enabled: false}});
                // chart.addSeries({data: date[0].verch, name:date[0].verch_m, type: 'area', zIndex: 2,color: '#ff0018', marker: {enabled: false}});
                 chart.xAxis[0].setCategories(date[0].x);
                 var z = 5;
                 var verch = 0;
                 for(var v in date){
                    if(date[v].verch_m > verch){
                      verch = date[v].verch_m;
                    }
                 }
                 var vv = [];
                  for(i=0;i<l;i++){
                      vv.push(verch);
                  }
                  chart.addSeries({data: vv, name: verch, type: 'area', zIndex: 2,color: '#ff0018', marker: {enabled: false}});
                  
                for(var k in date){
                    
                    chart.addSeries({data: date[k].oborot, name:date[k].cat,  type: 'spline', zIndex: z});
                    z++;
                }
                
            }
                console.log(date);
                    $('.modal-backdrop').hide();
                    $('#foo').detach();
                      });
                      
     
var chart =  new  Highcharts.Chart({
title: {
        text: cat
            },
  chart: {
    renderTo: element
  },

    xAxis: {
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Недели'
    }
            },
    yAxis: {
                title: {
                    text: 'Дни'
                },
                 categories: [0]
            },
    tooltip: {
        shared: true
    }
});
     

return false;

}
function oborot_graid(form, element){
  //  console.log(form);
   // console.log(element);
    //return false;
    
   // if(element == 'oborot_brand'){
       //  var cat = $('#cat_oborot_category option:selected').text();
    //}else{
        var cat = 'Грейды';
   // }
   
    console.log(cat);
            //var date =[];
		$.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form,
                success: function (res) {
			console.log(res);
                        //  date = res;
                },
		error: function (res) {
			console.log(res);
		}
            }).done(function(date) {
                if(date.length > 0){
                    var l = date[0].verch.length;
                   
                 chart.addSeries({data: date[0].niz, name:date[0].niz_m, type: 'area', zIndex: 4,color: '#23bf08', marker: {enabled: false}});
                 chart.addSeries({data: date[0].norma, name:date[0].norma_m, type: 'area', zIndex: 3, color: '#17f2f4',  marker: {enabled: false}});
                // chart.addSeries({data: date[0].verch, name:date[0].verch_m, type: 'area', zIndex: 2,color: '#ff0018', marker: {enabled: false}});
                 chart.xAxis[0].setCategories(date[0].x);
                 var z = 5;
                 var verch = 0;
                 for(var v in date){
                    if(date[v].verch_m > verch){
                      verch = date[v].verch_m;
                    }
                 }
                 var vv = [];
                  for(i=0;i<l;i++){
                      vv.push(verch);
                  }
                  chart.addSeries({data: vv, name: verch, type: 'area', zIndex: 2,color: '#ff0018', marker: {enabled: false}});
                  
                for(var k in date){
                    
                    chart.addSeries({data: date[k].oborot, name:date[k].cat,  type: 'spline', zIndex: z});
                    z++;
                }
                
            }
                console.log(date);
                    $('.modal-backdrop').hide();
                    $('#foo').detach();
                      });
                      
     
var chart =  new  Highcharts.Chart({
title: {
        text: cat
            },
  chart: {
    renderTo: element
  },

    xAxis: {
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Недели'
    }
            },
    yAxis: {
                title: {
                    text: 'Дни'
                },
                 categories: [0]
            },
    tooltip: {
        shared: true
    }
});
     

return false;

}
function oborot_graid_category(form, element){
  //  console.log(form);
   // console.log(element);
    //return false;
    
   // if(element == 'oborot_brand'){
       //  var cat = $('#cat_oborot_category option:selected').text();
    //}else{
        var cat = 'Грейды';
   // }
   
    console.log(cat);
            //var date =[];
		$.ajax({
                beforeSend: function(){
                    $('<div/>', {
                        id: 'foo',
                        class: 'modal-backdrop fade show',
                        html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>'
                    }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form,
                success: function (res) {
			console.log(res);
                        //  date = res;
                },
		error: function (res) {
			console.log(res);
		}
            }).done(function(date) {
                if(date.length > 0){
                    var l = date[0].verch.length;
                   
                 chart.addSeries({data: date[0].niz, name:date[0].niz_m, type: 'area', zIndex: 4,color: '#23bf08', marker: {enabled: false}});
                 chart.addSeries({data: date[0].norma, name:date[0].norma_m, type: 'area', zIndex: 3, color: '#17f2f4',  marker: {enabled: false}});
                // chart.addSeries({data: date[0].verch, name:date[0].verch_m, type: 'area', zIndex: 2,color: '#ff0018', marker: {enabled: false}});
                 chart.xAxis[0].setCategories(date[0].x);
                 var z = 5;
                 var verch = 0;
                 for(var v in date){
                    if(date[v].verch_m > verch){
                      verch = date[v].verch_m;
                    }
                 }
                 var vv = [];
                  for(i=0;i<l;i++){
                      vv.push(verch);
                  }
                  chart.addSeries({data: vv, name: verch, type: 'area', zIndex: 2,color: '#ff0018', marker: {enabled: false}});
                  
                for(var k in date){
                    
                    chart.addSeries({data: date[k].oborot, name:date[k].cat,  type: 'spline', zIndex: z});
                    z++;
                }
                
            }
                console.log(date);
                    $('.modal-backdrop').hide();
                    $('#foo').detach();
                      });
                      
     
var chart =  new  Highcharts.Chart({
title: {
        text: cat
            },
  chart: {
    renderTo: element
  },

    xAxis: {
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Недели'
    }
            },
    yAxis: {
                title: {
                    text: 'Дни'
                },
                 categories: [0]
            },
    tooltip: {
        shared: true
    }
});
     

return false;

}



$("#form_analitics").submit(function(e){
    analityks($('#from_analitic').val(), $('#to_analitic').val())
    return false;
});
$('.articles').click(function(e){
  if(e.target.id == 'h') {
  $(".n_a_time").hide();
$(".m_a_time").hide();
}
$('#'+e.target.id).addClass("active");
  //rickshaw2(e.target.id);
  console.log(e.target.id);
  chartLine(e.target.id);
});

$('.order').click(function(e){
$('#h').removeClass("active");
$('#n').removeClass("active");
$('#m').removeClass("active");
  console.log(e.target.id);
  if(e.target.id == 'h') {
  $(".n_time").hide();
$(".m_time").hide();
}
  $('#'+e.target.id).addClass("active");
  rickshaw2(e.target.id);
});

function order_gryde(from){
     var date =[];
 var max = 0;
 var min = 30000;
		//var new_data = '&method=ostatki';
		//console.log(new_data);
		$.ajax({
                    beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: from,
                success: function (res) {
                    console.log(res);
                         /*       date = res;
                        for(var key in res.y){  
                        if(res.y[key] > max){ max = res.y[key];}
                        if(res.y[key] < min){ min = res.y[key];}
                        }
                        min = min - 100;
                        max = max +100;*/
                },
				error: function (res) {
				console.log(res);
                                $('#foo').detach();
				}
            }).done(function(date) {
		var ctx4 = $('#order_gryde');
                ctx4.empty();
                        // ctx4.height= 300;
                    $('#foo').detach();
                    new  Highcharts.Chart({
title: {
        text: 'Продажи по грейдам за период'
            },
  chart: {
    renderTo: 'order_gryde'
  },
  yAxis: [{ // Primary yAxis
        labels: {
            format: '{value}',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        title: {
            text: 'Значение',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        }
    }],

    xAxis: {
         categories: date['y'],
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Месяцы'
    }
            },
    
    tooltip: {
        shared: true
    },
    series: [
        date['a'],
        date['b']
  ]
});
                    
   /*                 
   new Chart(ctx4, {
    type: 'line',
    data: {
      labels: date.x,
      datasets: [{
	label: 'Сумма выбраный период ', 
        data: date.y,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      },
      {
	label: 'Сумма период год назад ', 
        data: date.z,
        borderColor: '#320063',
        borderWidth: 1,
        fill: false
      }
	  ]
    },
    options: {
      legend: {
        display: false,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10,
            max: max,
            min: min
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10
          }
        }]
      }
    }
  });*/
});
}
//realization
function realization(from){
     var date =[];
 var max = 0;
 var min = 30000;
		//var new_data = '&method=ostatki';
		//console.log(new_data);
		$.ajax({
                    beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: from,
                success: function (res) {
                    console.log(res);
                                date = res;
                        for(var key in res.y){  
                        if(res.y[key] > max){ max = res.y[key];}
                        if(res.y[key] < min){ min = res.y[key];}
                        }
                        min = min - 100;
                        max = max +100;
                },
				error: function (res) {
				console.log(res);
                                $('#foo').detach();
				}
            }).done(function() {
		var ctx4 = $('#realization');
                    ctx4.height= 300;
                    $('#foo').detach();
   new Chart(ctx4, {
    type: 'line',
    data: {
      labels: date.x,
      datasets: [{
	label: 'Сумма ', 
        data: date.y,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      }
	  ]
    },
    options: {
      legend: {
        display: false,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10,
            max: max,
            min: min
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10
          }
        }]
      }
    }
  });
});
}
//сравнение остатков
function procent_ostatka(form){

		$.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form,
                success: function (res) {
			console.log(res);
                      
                },
		error: function (res) {
                   // fopen();
			console.log(res.responseText);
                        console.log(res);
                        $('.modal-backdrop').hide();
                    $('#foo').detach();
		}
            }).done(function(date) {
                 var s1 = 0;
                 var s2 = 0;
                 var s3 = 0;
                 var s4 = 0;
                 
              var text = '<legend>Средние остатки: в шт.</legend>';
            text += '<table class="table"><thead><tr><td>Категория</td><td>'+date.t+' (остаток/оборот)</td><td>'+date.t+' (продажи)</td><td>'+date.s+' (остаток/оборот)</td><td>'+date.s+' (продажи)</td></tr></thead><tbody>';
            delete date['s'];
            delete date['t'];
            for(var k in date.table){
                s1+=date.table[k].one;
                s2+=date.table[k].two;
                s3+=date.table[k].one_prod;
                s4+=date.table[k].two_prod;
               
                text+='<tr><td>'+date.table[k].name+'</td><td>'+date.table[k].one+' ('+date.table[k].oborot_one+')</td><td>'+date.table[k].one_prod+'</td><td>'+date.table[k].two+' ('+date.table[k].oborot_two+')</td><td>'+date.table[k].two_prod+'</td></tr>';
               // da.push(date[k].y);
               // date[k].y.empty();
            }
            
            text+='</tbody><tfoot><tr><td></td><td>'+s1+'</td><td>'+s3+'</td><td>'+s2+'</td><td>'+s4+'</td></tr></tfoot></table>';
           $('#ostatki2_table').html(text);

                    $('.modal-backdrop').hide();
                    $('#foo').detach();
                    $('#ostatki2').empty();
 new  Highcharts.Chart({
title: {
        text: 'Сравнение остатков %'
            },
  chart: {
    renderTo: 'ostatki2'
  },
  yAxis: [{ // Primary yAxis
        labels: {
            format: '{value} %',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        title: {
            text: 'Средний остаток',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        }
    }],

    xAxis: {
         categories: date['y'],
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Категории'
    }
            },
    
    tooltip: {
        shared: true
    },
    series: [
        date['a'],
        date['b'],
        date['c'],
        date['d']
  ]
});
     
 });
return false;

}
//сравнение грейдов
function procent_graid(form){

		$.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form,
                success: function (res) {
			console.log(res);
                      
                },
		error: function (res) {
			console.log(res);
                        $('.modal-backdrop').hide();
                    $('#foo').detach();
		}
            }).done(function(date) {
                 var s1 = 0;
                 var s2 = 0;
                 
              var text = '<legend>Средние грейдов: в шт.</legend>';
            text += '<table class="table"><thead><tr><td>Грейд</td><td>'+date.t+' (оборот)</td><td>'+date.s+' (оборот)</td></tr></thead><tbody>';
            delete date['s'];
            delete date['t'];
            for(var k in date.table){
                s1+=date.table[k].one;
                s2+=date.table[k].two;
               
                text+='<tr><td>'+date.table[k].name+'</td><td>'+date.table[k].one+' ('+date.table[k].oborot_one+')</td><td>'+date.table[k].two+' ('+date.table[k].oborot_two+')</td></tr>';
               // da.push(date[k].y);
               // date[k].y.empty();
            }
            
            text+='</tbody><tfoot><tr><td></td><td>'+s1+'</td><td>'+s2+'</td></tr></tfoot></table>';
           $('#ostatki2_table').html(text);

                    $('.modal-backdrop').hide();
                    $('#foo').detach();
                    $('#ostatki2').empty();
 new  Highcharts.Chart({
title: {
        text: 'Сравнение грейдов за период'
            },
  chart: {
    renderTo: 'gride2'
  },
  yAxis: [{ // Primary yAxis
        labels: {
            format: '{value} %',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        title: {
            text: 'Дни',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        }
    }],

    xAxis: {
         categories: date['y'],
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Категории'
    }
            },
    
    tooltip: {
        shared: true
    },
    series: [
        date['a'],
        date['b']
  ]
});
     
 });
return false;

}



/*
  function oborot_root(form, ar = [], i = 0, date = []){
    
    var l = ar.length;
              $.ajax({
                beforeSend: function(){
                 },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form+'&cat_prognoz='+ar[i],
                success: function (res) {
                  //  i++;
                    
                   // console.log(res);
                    date.push(res);
                    if(i<l){
                        i++;
                        oborot_root(form, ar, i, date);
                    }else{
                        new_grafik_root_category(date);
                    }
                },
				error: function (res) {
				console.log(res);
				}
            });
      //  console.log(date);
        return   false;  
}
  function new_grafik_root_category(date){
                   $('.modal-backdrop').hide();
                    $('#foo').detach();
new  Highcharts.Chart({
title: {
        text: 'Категории'
            },
  chart: {
    renderTo: 'oborot_root'
  },

    xAxis: {
    categories: date[0].x,
    
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Недели'
    }
            },
    yAxis: {
                title: {
                    text: 'Дни'
                }
            },
    tooltip: {
        shared: true
    },
  series: [
           {
    type: 'area',
   marker: {
            enabled: false
        },
   name: '14',
   color: '#23bf08',
   zIndex: 4,
    data: date[0].niz
  },
  {
   type: 'area',
   name: '24',
   color: '#17f2f4',
   marker: {
            enabled: false
        },
   zIndex: 3,
    data: date[0].norma
  },
       {
    type: 'area',
   marker: {
            enabled: false
        },
   name: '40',
   color: '#ff0018',
   zIndex: 2,
    data: date[0].verch
  },
  {
   type: 'spline',
   name: date[0].cat,
   zIndex: 5,
    data: date[0].oborot
  },
  {
   type: 'spline',
   name: date[1].cat,
   zIndex: 6,
    data: date[1].oborot
  },
  {
   type: 'spline',
   name: date[2].cat,
   zIndex: 7,
    data: date[2].oborot
  },
  {
   type: 'spline',
   name: date[3].cat,
   zIndex: 8,
    data: date[3].oborot
  },
  {
   type: 'spline',
   name: date[4].cat,
   zIndex: 9,
    data: date[4].oborot
  },
  {
   type: 'spline',
   name: date[5].cat,
   zIndex: 10,
    data: date[5].oborot
  }
  ]
});
      
       return false;
}
*/

function prognoz(form){
  var cat = $('#cat_prognoz option:selected').text();
  if(!cat){
      alert('Выберите категорию');
      return false;
      
  }
   var start = new Date($('#from_prognoz').val());
   var end = new Date($('#to_prognoz').val());
  var interval = $('#interval_prognoz').val();
  if(interval == 2){
     if(((end-start)/86400000) > 30){
           alert('Для построения по дням, интервал дат не должен прывишать 30 дней. Сейчас '+((end-start)/86400000)+' дней.');
      return false;
      
      }
  }//else{
     // if(getWeekDay(start) != 1 && getWeekDay(end) != 0){
         //  alert('Для построения по неделям, первая дата должна быть началом недели а вторая окончанием недели.');
   //   return false;
    //  }
  //}
  var date =[];
		$.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: 'method=prognoz&'+form,
                success: function (res) {
				//console.log(res);
                                date = res;
                                console.log(res.otkloneniye);
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
               // console.log(date);
                    $('.modal-backdrop').hide();
                    $('#foo').detach();
       
var c = new  Highcharts.Chart({
title: {
        text: cat
            },
    subtitle: {
        text: date.otkloneniye
    },
  chart: {
    renderTo: 'prognoz',
  },

    xAxis: {
    categories: date.x,
    
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Недели'
    }
            },
    yAxis: {
                title: {
                    text: 'Остаток товара'
                },
    tooltip: {
        shared: true
    }
},
  series: [
  {
  
   type: 'area',
   name:'Ниже нормы',
   color: '#bc0000',
   zIndex: 2,
   marker: {
            enabled: false
        },
    data: date.n_0
  },
  {
   type: 'area',
   name: 'Норма',
   color: '#fcff5e',
   zIndex: 1,
   marker: {
            enabled: false
        },
    data: date.n_1
  },
  {
   type: 'area',
   name: 'Выше нормы',
   color: '#03a842',
   zIndex: 0,
   marker: {
            enabled: false
        },
    data: date.n_2
  },
  {
   type: 'spline',
   name: 'Остатки',
   color: '#000000',
   zIndex: 3,
    data: date.ost
  },
  {
   type: 'spline',
   name: 'Продажи',
   color: '#005cf2',
   zIndex: 4,
    data: date.prod
  },
  {
   type: 'spline',
   name: 'Добавлено',
   color: '#ad06a7',
   zIndex: 5,
    data: date.add
  }
  ,
  {
   type: 'spline',
   dashStyle: 'Dot',
   name: 'Сред.Продажи',
   color: Highcharts.getOptions().colors[2],
   zIndex: 5,
    data: date.sr_pr
  }
  ,
  {
   type: 'spline',
   dashStyle: 'Dot',
   name: 'Сред.Остатки',
   color: Highcharts.getOptions().colors[1],
   zIndex: 5,
    data: date.sr_ost
  },
  {
   type: 'spline',
   dashStyle: 'Dot',
   name: 'Оборот',
   color: Highcharts.getOptions().colors[4],
   zIndex: 5,
    data: date.oborot
  }
  ]
});
        });

return false;

}
function DistinctBrand(){
    if($('#from_prognoz_brand').val() && $('#to_prognoz_brand').val()){
        $.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: 'method=distinct_brand&from_prognoz_brand='+$('#from_prognoz_brand').val()+'&to_prognoz_brand='+$('#to_prognoz_brand').val(),
                success: function (res) {
                   var sel =  $('#brand_prognoz');
                   var t = '<option value="111111111">Все бренды</option>';
                   for(var k in res){
                       t+='<option value="'+res[k].id+'">'+res[k].name+'</option>'
                   }
                   sel.html(t);
				//console.log(res);
                },
				error: function (res) {
				console.log(res);
				}
            });
            $('#foo').detach();
            return true;
        
    }else{
        return false;
    }
    
}
function prognozBrand(form){
    console.log(form);
  var cat = $('#brand_prognoz option:selected').text();
  if(!cat){
      alert('Выберите Бренд');
      return false;
      
  }
 //  var start = new Date($('#from_prognoz_brand').val());
  // var end = new Date($('#to_prognoz_brand').val());
 // var interval = $('#interval_prognoz').val();
  
  var date =[];
		$.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: 'method=prognozBrand&'+form,
                success: function (res) {
				console.log(res);
                                date = res;
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
               // console.log(date);
                    $('.modal-backdrop').hide();
                    $('#foo').detach();
       
var c = new  Highcharts.Chart({
title: {
        text: cat
            },
   subtitle: {
        text: date.otkloneniye
    },
  chart: {
    renderTo: 'prognoz_brand',
  },

    xAxis: {
    categories: date.x,
    
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Недели'
    }
            },
    yAxis: {
                title: {
                    text: 'Остаток товара'
                },
    tooltip: {
        shared: true
    }
},
  series: [
  {
  
   type: 'area',
   name:'Ниже нормы',
   color: '#bc0000',
   zIndex: 2,
   marker: {
            enabled: false
        },
    data: date.n_0
  },
  {
   type: 'area',
   name: 'Норма',
   color: '#fcff5e',
   zIndex: 1,
   marker: {
            enabled: false
        },
    data: date.n_1
  },
  {
   type: 'area',
   name: 'Выше нормы',
   color: '#03a842',
   zIndex: 0,
   marker: {
            enabled: false
        },
    data: date.n_2
  },
  {
   type: 'spline',
   name: 'Остатки',
   color: '#000000',
   zIndex: 3,
    data: date.ost
  },
  {
   type: 'spline',
   name: 'Продажи',
   color: '#005cf2',
   zIndex: 4,
    data: date.prod
  },
  {
   type: 'spline',
   name: 'Добавлено',
   color: '#ad06a7',
   zIndex: 5,
    data: date.add
  }
  ,
  {
   type: 'spline',
   dashStyle: 'Dot',
   name: 'Сред.Продажи',
   color: Highcharts.getOptions().colors[2],
   zIndex: 5,
    data: date.sr_pr
  }
  ,
  {
   type: 'spline',
   dashStyle: 'Dot',
   name: 'Сред.Остатки',
   color: Highcharts.getOptions().colors[1],
   zIndex: 5,
    data: date.sr_ost
  },
  {
   type: 'spline',
   dashStyle: 'Dot',
   name: 'Оборот',
   color: Highcharts.getOptions().colors[4],
   zIndex: 5,
    data: date.oborot
  }
  ]
});
        });

return false;

}

function getWeekDay(date) {
  var days = ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'];

  return days[date.getDay()];
}

function analityks(from, to){
      var url = '/admin/home/';
		var new_data = '&method=konversiya&from='+from+'&to='+to;
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
                        for(var key in res){
                            $('.'+key).html(res[key]); 
                            if(key != 'otkaz' && key != 'konvers' && key != 'pageviewsPerSession'){
                                 $('.'+key+'_s').html(parseInt(res[key]/res['dney']));
                            }else{
                             //    $('.'+key+'_s').html(res[key]);
                            }
                           
                        }
                },
				error: function (res) {
				console.log(res);
				}
            });
    return false;
}


function Ucenka_2(){
var url = '/admin/home/';
		var new_data = '&method=ucenka_2';
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {

				$("#koll_ucenka").html(res['sum']);
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function(res) {
			console.log(res);
			new Morris.Donut({
    element: 'ucenka_2',
    data: res.uc,
    colors: ['#0c8e22','#0c8e17','#98eacc','#4a63e0','#ffccce','#ff6870', '#e40613'],
    resize: true
  });
	});

}
function OrderBonus(){
var url = '/admin/home/';
		var new_data = '&method=order_bonus';
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
console.log(res);
		
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function(res) {
			//console.log(res);
			new Morris.Bar({
    element: 'order_bonus',
    data: [
        {y: "Отправлено", a: res.c_ctn, b : 0},
        {y: "Заказы", a: res.r_ctn, b: res.r_summ}
    ],
    xkey: 'y',
    ykeys: ['a', 'b'],
    labels: ['Колл.', 'Сумма'],
    colors: ['#0c8e22','#e40613'],
    gridTextSize: 11,
hideHover: 'auto',
resize: true
  });
	});

}
function rickshaw2(e){
  var label = [];
  var date =[];
 var i;
 var max = 0;
 var sum = 0;
  var url = '/admin/home/';
		var new_data = '&method=order&type='+e;
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
		var l = res.length;
			for (i = 0; i < l; i++) {
			if(res[i]['y'] > max) max = res[i]['y'];
			label.push(res[i]['x']); 
			date.push(res[i]['y']); 
			sum +=res[i]['y']; 
  }
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
			$('#col_order_5').html(sum);
			$("canvas#rickshaw2").remove();
			$("div.diagram_5").append('<canvas id="rickshaw2" height="200"></canvas>');
			var ctx = document.getElementById("rickshaw2").getContext('2d');

new Chart(ctx, {
type: 'line',
data: {
  labels: label,
  datasets: [{
    data: date,
  fill: true,
    backgroundColor: '#73a9e7'
  }]
},
options: {
  legend: {
    display: false,
      labels: {
        display: false
      }
  },
  scales: {
    yAxes: [{gridLines: {
        display: false,
        color: "black"
      },
      ticks: {
        beginAtZero:true,
        fontSize: 10,
        max: max
      }
    }],
    xAxes: [{
	gridLines: {
        display: false,
        color: "black"
      },
      ticks: {
        beginAtZero:true,
        fontSize: 10
      }
    }]
  }
}
});
});
}

function delivery_time(e, from, to){
  var label = [];
  var m =[];
  var up =[];
  var np =[];
  var k =[];
 var sum = 0;

		$.ajax({
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: { method: 'delivery', type: e, from : from, to: to},
                success: function (res) {
                    console.log(res);
                    if(true){
				
                                for(var key in res){
                                    label.push(res[key]['date']+' : '+res[key]['ctn']);
                                    m.push(parseInt(res[key]['time'])); 
                                    sum += parseInt(res[key]['time']);
                                }
                                $('#col_dely_5').html(parseInt(sum/res.length));
                            }
                            if(false){
                                for(var key in res.m){
                                    label.push(res.m[key]['date']+':'+res.m[key]['ctn']);
                                    m.push(parseInt(res.m[key]['time'])); 
                                }
                            
                                for(var key in res.up){
                                    up.push(parseInt(res.up[key]['time'])); 
                                }
                                for(var key in res.np){
                                    np.push(parseInt(res.np[key]['time'])); 
                                }
                                for(var key in res.k){
                                    k.push(parseInt(res.k[key]['time'])); 
                                } 
                            }

                    },
                    error: function (res) {
				console.log(res);
				}
            }).done(function() {
			
			$("#dely_shop_3").remove();
			$(".diagram_8_dely").append('<canvas id="dely_shop_3"></canvas>');
			var ctx4 = document.getElementById('dely_shop_3');
  var myChart8 = new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	  label: 'Дней',
        data: m,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      }]
    },
    options: {
      legend: {
        display: false,
          labels: {
            display: false
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10//,
           // max: max
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 11
          }
        }]
      }
    }
  });
});
}

function  ToExcel(form){
     $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
   $('#table_return_prognoz').detach();
            $('#download_table_return_prognoz').detach();
            var t = document.getElementById("prognoz");
            var button = document.createElement("button");
                                      
    button.innerHTML = "Скачать таблицу";
    button.setAttribute("class", "btn btn-danger");
    button.setAttribute("id", "download_table_return_prognoz");
    button.setAttribute("onClick", "return download('table_return_prognoz')");
                                
                    var table = document.createElement("table");
                    table.setAttribute("id", "table_return_prognoz");
                    table.setAttribute("class", "table");
                    t.appendChild(table);
                    t.appendChild(button);
                    var header = table.createTHead();
  var row = header.insertRow(0);
            row.insertCell(0).innerHTML = "<b>Категория</b>";
            row.insertCell(1).innerHTML = "<b>Без грейда</b>";
            row.insertCell(2).innerHTML = "<b>Грейд 1</b>";
            row.insertCell(3).innerHTML = "<b>Грейд 2</b>";
            row.insertCell(4).innerHTML = "<b>Грейд 3</b>";
            row.insertCell(5).innerHTML = "<b>Грейд 4</b>";
            row.insertCell(6).innerHTML = "<b>Грейд 5</b>";
            row.insertCell(7).innerHTML = "<b>Общее</b>"; 
             var body = table.createTBody();
     $.ajax({
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form+'&method=balance_to_excel_list_cat',
                success: function (res) {
                    console.log(res);    
                
                   if(ToExcelS(form, res, body)){
                        $('#foo').detach(); 
                   }
                },
				error: function(res){
				console.log(res);
					}
            });
}
function  ToExcelS(form, ss, body){
    console.log(form);
    console.log(ss);
  //  var l = ss.length;
     for(var i in ss){
             $.ajax({
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form+'&method=balance_to_excel&cat='+ss[i],
                success: function (res) {
                    console.log(res);
                var row = body.insertRow(); 
                     row.insertCell(0).innerHTML = res.name;
                     var o = 0;

                          row.insertCell(1).innerHTML = res.res[0];
                          o+=res.res[0];
                          row.insertCell(2).innerHTML = res.res[1];
                          o+=res.res[1];
                          row.insertCell(3).innerHTML = res.res[2];
                          o+=res.res[2];
                          row.insertCell(4).innerHTML = res.res[3];
                          o+=res.res[3];
                          row.insertCell(5).innerHTML = res.res[4];
                          o+=res.res[4];
                          row.insertCell(6).innerHTML = res.res[5];
                          o+=res.res[5];
                      row.insertCell(7).innerHTML = o;                         
                },
				error: function(res){
				console.log(res);
					}
            });
        }
                    
       
//window.location = '/admin/home/method/balance_to_excel?'+form+'/';
    return true;
};
function sendAjaxToExcel(){
    
}

function  ProcentToExcel(form){
window.location = '/admin/home/method/procent_to_excel/from_procent/'+$('#from_procent').val()+'/to_procent/'+$('#to_procent').val();
    return false;
};
function balance_brand_in_category_to_excel(form){
 window.location = '/admin/home/method/balance_brand_in_category_to_excel?'+form+'/';         
    return false;
};
function balance_brand_all_to_excel(form){
 window.location = '/admin/home/method/balance_brand_all_to_excel?'+form+'/';         
    return false;
};
function testik_dey(form){
 window.location = '/admin/home/method/balance_brand_to_excel_dey?'+form+'/';
    return false;
};

function oborot_all(form){
  var date =[];
		$.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form,
                success: function (res) {
				console.log(res);
                                date = res;
                              //  console.log(res.otkloneniye);
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
              //  console.log(date);
                    $('.modal-backdrop').hide();
                    $('#foo').detach();
       
new  Highcharts.Chart({
title: {
        text: 'Весь товар'
            },
  chart: {
    renderTo: 'oborot',
  },

    xAxis: {
    categories: date.x,
    
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Недели'
    }
            },
    yAxis: {
                title: {
                    text: 'Дни'
                }
            },
    tooltip: {
        shared: true
    },
  series: [
         {
    type: 'area',
   marker: {
            enabled: false
        },
   name: '21',
   color: '#23bf08',
   zIndex: 4,
    data: date.niz
  },
  {
   type: 'area',
   name: '28',
   color: '#17f2f4',
   marker: {
            enabled: false
        },
   zIndex: 3,
    data: date.norma
  },
       {
    type: 'area',
   marker: {
            enabled: false
        },
   name: '45',
   color: '#ff0018',
   zIndex: 2,
    data: date.verch
  },
  {
   type: 'spline',
   name: 'Оборот шт.',
   color: Highcharts.getOptions().colors[4],
   zIndex: 5,
    data: date.oborot
  },
  {
   type: 'spline',
   name: 'Оборот грн.',
   color: Highcharts.getOptions().colors[5],
   zIndex: 5,
    data: date.oborot_grn
  }
  ]
});
       });

return false;

} 
function oborot_all_monch(form){
  var date =[];
		$.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form,
                success: function (res) {
				console.log(res);
                                date = res;
                              //  console.log(res.otkloneniye);
                },
				error: function (res) {
				console.log(res);
                                $('#foo').detach();
				}
            }).done(function() {
              //  console.log(date);
                    $('.modal-backdrop').hide();
                    $('#foo').detach();
       
new  Highcharts.Chart({
title: {
        text: 'Весь товар'
            },
  chart: {
    renderTo: 'oborot_grn'
  },

    xAxis: {
    categories: date.x,
    
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Месяцы'
    }
            },
    yAxis: {
                title: {
                    text: 'Дни'
                }
            },
    tooltip: {
        shared: true
    },
  series: [
         {
    type: 'area',
   marker: {
            enabled: false
        },
   name: '21',
   color: '#23bf08',
   zIndex: 4,
    data: date.niz
  },
  {
   type: 'area',
   name: '28',
   color: '#17f2f4',
   marker: {
            enabled: false
        },
   zIndex: 3,
    data: date.norma
  },
       {
    type: 'area',
   marker: {
            enabled: false
        },
   name: '45',
   color: '#ff0018',
   zIndex: 2,
    data: date.verch
  },
  {
   type: 'spline',
   name: 'Оборот шт.',
   color: Highcharts.getOptions().colors[4],
   zIndex: 5,
    data: date.oborot
  },
  {
   type: 'spline',
   name: 'Оборот грн.',
   color: Highcharts.getOptions().colors[5],
   zIndex: 5,
    data: date.oborot_grn
  }
  ]
});
       });

return false;

}


function ostatki(){
  var date =[];
 var max = 0;
 var min = 30000;
 var min_grn = 10000000;
 var max_grn = 10;
  var url = '/admin/home/';
		var new_data = '&method=ostatki';
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                    console.log(res);
                                date = res;
                        for(var key in res.y){  
                        if(res.y[key] > max){ max = res.y[key];}
                        if(res.y[key] < min){ min = res.y[key];}
                        }
                        min = min - 100;
                        max = max +100;
                        
                        for(var key in res.z){  
                        if(res.z[key] > max_grn){ max_grn = res.z[key];}
                        if(res.z[key] < min_grn){ min_grn = res.z[key];}
                        }
                        min_grn = min_grn - 100;
                        max_grn = max_grn + 100;
                        
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
		var ctx4 = $('#ostatki');
                    ctx4.height= 300;
   new Chart(ctx4, {
    type: 'line',
   // height: 300,
    data: {
      labels: date.x,
      datasets: [{
	  label: 'Колл. ', 
        data: date.y,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      }
	  ]
    },
    options: {
      legend: {
        display: false,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10,
            max: max,
            min: min
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10
          }
        }]
      }
    }
  });
  var ctx4_grn = $('#ostatki_grn');
    ctx4_grn.height= 300;
  new Chart(ctx4_grn, {
    type: 'line',
   // height: 300,
    data: {
      labels: date.x,
      datasets: [{
	  label: 'Сумма',
        data: date.z,
        borderColor: '#5B93D3',
        borderWidth: 1,
        fill: false
      }
	  ]
    },
    options: {
      legend: {
        display: false,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10,
            max: max_grn,
            min: min_grn
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10
          }
        }]
      }
    }
  });
});
}
function chartLine(e){
var label = [];
  var date1 =[];
  var date2 =[];
  var date3 =[];
 var i;
 var max = 0;
 var sum = 0;
  var url = '/admin/home/';
		var new_data = '&method=shop&type='+e;
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
				//console.log(e);
				var l = res.length;
					for (i = 0; i < l; i++) {
			if(res[i]['y'] > max) max = res[i]['y'];
			label.push(res[i]['x']); 
			date1.push(res[i]['y']); 
			date2.push(res[i]['pay']); 
			date3.push(res[i]['ret']);
			sum +=res[i]['y']; 
  }
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
			$('#col_articles_5').html(sum);

  var ctx4 = document.getElementById('articles_shop_3');
  var myChart4 = new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	  label: 'Заказали',
        data: date1,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Купили',
        data: date2,
        borderColor: '#5B93D3',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Вернули',
        data: date3,
        borderColor: '#5B9300',
        borderWidth: 1,
        fill: false
      }]
    },
    options: {
      legend: {
        display: true,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10//,
           // max: max
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 11
          }
        }]
      }
    }
  });
  });
  return false;
}
function visit(){
var label = [];
  var date1 =[];
  var date2 =[];
  var date3 =[];
  var date4 =[];
  var date5 =[];
  var url = '/admin/home/';
		var new_data = '&method=visit';
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                                for(var key in res){
                                  label.push(key); 
                                  date1.push(res[key]['visit']);
                                  date2.push(res[key]['page']);
                                  date3.push(res[key]['glubina']);
                                  date4.push(res[key]['otkaz']);
                                  date5.push(res[key]['konvers']);
                                    
                                }
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
  var ctx4 = $('#visit');
  var myChart4 = new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	  label: 'Сеансы',
        data: date1,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Просмотры страниц',
        data: date2,
        borderColor: '#5B93D3',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Глубина',
        data: date3,
        borderColor: '#5B9300',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Отказы %',
        data: date4,
        borderColor: '#5B0000',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Конверсия %',
        data: date5,
        borderColor: '#5B9300',
        borderWidth: 1,
        fill: false
      }]
    },
    options: {
      legend: {
        display: true,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 11
          }
        }]
      }
    }
  });
  });
  return false;
}
function konversiya(){
var label = [];
  var date1 =[];
  var url = '/admin/home/';
		var new_data = '&method=konversiya';
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                                for(var key in res){
                                  label.push(key); 
                                  date1.push(res[key]['new']);   
                                }
                },
		error: function (res) {
			console.log(res);
				}
            }).done(function() {
  var ctx4 = $('#views');
  var myChart4 = new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	  label: 'Конверсия',
        data: date1,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      }]
    },
    options: {
      legend: {
        display: true,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 11
          }
        }]
      }
    }
  });
  });
  return false;
}
function Ucenka(){
var label = [];
var date10 =[];
  var date20 =[];
  var date30 =[];
  var date40 =[];
  var date50 =[];
  var date60 =[];
var url = '/admin/home/';
		var new_data = '&method=ucenka';
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
console.log(res);
var l = res.length;
			for (i = 0; i < l; i++) {
			label.push(res[i]['x']); 
                        date10.push(res[i]['10']); 
			date20.push(res[i]['20']); 
			date30.push(res[i]['30']); 
			date40.push(res[i]['40']);
			date50.push(res[i]['50']);
			date60.push(res[i]['60']);
  }		
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function(res) {

    var ctx4 = document.getElementById('ucenka');
    var myChart4 = new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	  label: '10%',
        data: date10,
        borderColor: '#320063',
        borderWidth: 1,
        fill: false
      },{
	  label: '20%',
        data: date20,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      },{
	  label: '30%',
        data: date30,
        borderColor: '#5B93D3',
        borderWidth: 1,
        fill: false
      },{
	  label: '40%',
        data: date40,
        borderColor: '#5B9300',
        borderWidth: 1,
        fill: false
      },{
	  label: '50%',
        data: date50,
        borderColor: '#5B9300',
        borderWidth: 1,
        fill: false
      },{
	  label: '60%',
        data: date60,
        borderColor: '#5B9311',
        borderWidth: 1,
        fill: false
      }
	  ]
    },
    options: {
      legend: {
        display: true,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 11
          }
        }]
      }
    }
  });
});
}

function chartBar4(e){
var label = [];
  var date =[];
 var i;
 var max = 0;
  var url = '/admin/home/';
		var new_data = '&method=order&type='+e;
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				label = res.name;
				date = res.koll;
					for (var i in res.koll) {
			if(res.koll[i] > max) {max = res.koll[i];}
  }
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
			 var ctb4 = document.getElementById('chartBar4').getContext('2d');
  new Chart(ctb4, {
    type: 'bar',
    data: {
      labels: label,
      datasets: [{
        label: 'Заказов: ',
        data: date,
        backgroundColor: [
          '#324463',
          '#5B93D3',
          '#7CBDDF',
          '#5B93D3',
          '#324463'
        ]
      }]
    },
    options: {
      legend: {
        display: false,
          labels: {
            display: false
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10,
			 max: max
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 11
          }
        }]
      }
    }
  });
			
			});

};

function flotPie2(e){
		$.ajax({
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: '&method=order&type='+e,
		error: function (res) {console.log(res);}
            }).done(function(res) {
                console.log(res);
    new Morris.Donut({
    element: 'flotPie2',
    data: res.st,
    colors: res.color,
    resize: true
  });
  });
}
function flotPie3(e){
		$.ajax({
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: '&method=order&type='+e,
		error: function (res) {console.log(res);}
            }).done(function(res) {
    new Morris.Donut({
    element: 'flotPie3',
    data: res.st,
    colors: res.color,
    resize: true
  });
  });
}
function  chek(){
    var date = $('.chek .label').text().split(',');// [1,2,3,4,5,6,7,8,9];
    var label = $('.chek .date').text().split(',');
    var min = Math.min.apply(null, date);
    var max = Math.max.apply(null, date);
    console.log(date);
    var sum = 0;
for(var i = 0; i < date.length; i++){
    sum += parseInt(date[i]);
    }
    $('.sr_chek').html(parseInt(sum/date.length));
    var ctx4 = $('.chek');
    ctx4.height= 300;
   new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	  label: 'Ср.чек',
        data: date,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      }
	  ]
    },
    options: {
      legend: {
        display: false,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10,
            max: max,
            min: min
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10
          }
        }]
      }
    }
  }); 
};

