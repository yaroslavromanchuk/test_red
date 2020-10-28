 $(function(){
	  'use strict';
         
         
           
      });
function ABCostatok(f, g, r, type){
    console.log(r);
   // f.serialize()
   var val = f.val();
    console.log(f.val());
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
                data: { method : 'asb_ostatok', interval: val, type: type,  group: g.val()  },
                success: function (res) {
                    console.log(res);
                         
                         if(type == 'view'){
                             r.html(res);
                             var button = document.createElement("button");
                                          //button.type = "button";
    button.innerHTML = "Скачать таблицу";
    button.setAttribute("class", "btn btn-danger");
    button.setAttribute("onClick", "return download('abc-result-table-data')");
   r.prepend(button);
    $(".galery").lightGallery();
   var t = $('#abc-result-table-data').DataTable({
         
          language: {
            searchPlaceholder: 'Поиск в таблице...',
            sSearch: '',
            lengthMenu: '_MENU_ записей/страница',
			processing: "Выполняется обработка...",
			info:       "Записи с _START_ по _END_ из _TOTAL_ ",
			sInfoFiltered: '(найдено _TOTAL_ из _MAX_ записей)',
			paginate: {
            first:      "Первая",
            previous:   "Придведущая",
            next:       "Следующая",
            last:       "Последняя"
        }
          },
          columnDefs: [ {
            searchable: true,
            orderable: false,
            targets: 0
        } ]
       // order: [[ 1, 'asc' ]]
        });
       

                         }else{
                              r.html(res);
                             download('abc-result-table');
                         }
                    
                },
				error: function (res) {
				console.log(res);
                                $('#foo').detach();
				}
            }).done(function(){
                 $('#foo').detach();
            });
    return false;
}

function ABCorder(f,g,r,type){
    console.log(r);
   var val = f.val();
    console.log(f.val());
    var from = $('#from_oborot').val();
      var to = $('#to_oborot').val();
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
                data: { method : 'asb_order', interval: val, type: type, from: from, to: to, group: g.val() },
                success: function (res) {
                    console.log(res);
                         r.html(res);
                         if(type == 'view'){
                             r.html(res);
                            var button = document.createElement("button");
                                          //button.type = "button";
    button.innerHTML = "Скачать таблицу";
    button.setAttribute("class", "btn btn-danger");
    button.setAttribute("onClick", "return download('abc-order-result-table-data')");
    r.prepend(button);
                      
   
   $('#abc-order-result-table-data').DataTable({
         
          language: {
            searchPlaceholder: 'Поиск в таблице...',
            sSearch: '',
            lengthMenu: '_MENU_ записей/страница',
			processing: "Выполняется обработка...",
			info:       "Записи с _START_ по _END_ из _TOTAL_ ",
			sInfoFiltered: '(найдено _TOTAL_ из _MAX_ записей)',
			paginate: {
            first:      "Первая",
            previous:   "Придведущая",
            next:       "Следующая",
            last:       "Последняя"
        },
          },
          columnDefs: [ {
            searchable: true,
            orderable: false,
            targets: 0
        } ],
       // order: [[ 1, 'asc' ]]
        });
       

                         }else{
                              r.html(res);
                             download('abc-order-result-table');
                         }
                       
                    
                },
				error: function (res) {
				console.log(res);
                                $('#foo').detach();
				}
            }).done(function(){
                 $('#foo').detach();
            });
            
    
    return false;
}

function Top(){
   // console.log(e);
    var date = [];
      var limit = $('#top_limit').val();
      var from = $('#from_oborot').val();
      var to = $('#to_oborot').val();
      var ed = $('#top_ed').val();
      var type = $('#top_type').val();
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
                data: { method : 'top_cat', limit: limit, from: from, to: to , ed: ed, type: type},
                success: function (res) {
                    console.log(res);
                                date = res;
                    
                },
				error: function (res) {
				console.log(res);
                                $('#foo').detach();
				}
            }).done(function() {
		//var ctx4 = $('#realization');
                   // ctx4.height= 300;
                    
         topChart('res_top', date);
         $('#foo').detach();
        });
    
   
    return false;
}
function topChart(id, date){
  $("div.top_res").empty();
  $("div.top_res").append('<canvas id="res_top" height="500"></canvas>');
   var ctx = document.getElementById(id).getContext('2d');
new Chart(ctx, {
//type: 'bar',
type: 'horizontalBar',
data: {
  labels: date.labels,
  datasets: [{
    label: 'Текущий: ',
    data: date.data,
    borderWidth: 1,
        fill: true
  },
  {
    label: 'Предыдущий: ',
    data: date.data2,
    borderWidth: 1,
        fill: true
  }
  ,
  {
    label: 'Ср.Остаток: ',
    data: date.data3,
    borderWidth: 1,
        fill: true
  }]
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
        fontSize: 12,
        max: 80
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
}
function TopExcel(){
    var from = $('#from_oborot').val();
      var to = $('#to_oborot').val();
      var ed = $('#top_ed').val();
      var limit = $('#top_limit').val();
      var type = $('#top_type').val();
     window.location = '/admin/home/method/top_cat/ed/'+ed+'/type/'+type+'/from/'+from+'/to/'+to+'/limit/'+limit+'/';
    return false;
    
}
function TopExcelMarga(){
    var from = $('#from_oborot').val();
      var to = $('#to_oborot').val();
      var group_by = $("#group_by").val();
      var ostatok =  $("#ostatok").is(':checked');
     window.location = '/admin/home/method/marga_period/from/'+from+'/to/'+to+'/group_by/'+group_by+'/ostatok/'+ostatok;
    return false;
    
}


