 var tableToExcel = (function() {
		var uri = 'data:application/vnd.ms-excel;base64,'
		, template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
		, base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
		, format = function(s, c) { 	    	 
			return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) 
		}
		, downloadURI = function(uri, name) {
		    var link = document.createElement("a");
		    link.download = name;
		    link.href = uri;
		    link.click();
		}

		return function(table, name, fileName) {
			if (!table.nodeType) table = document.getElementById(table)
				var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
			var resuri = uri + base64(format(template, ctx))
			downloadURI(resuri, fileName);
		}
	})(); 
        // params: element id, sheet name, file name
              //   tableToExcel('resultTable','Смета', 'Ремрайон_смета.xls');
  function download(table){
     return  tableToExcel(table,table, table+'.xls');
  }              
function prognoz_category_to_excel(t){
  
   var start = new Date($('#from_prognoz').val());
   var end = new Date($('#to_prognoz').val());
  var interval = $('#interval_prognoz').val();
  if(interval == 2){
     if(((end-start)/86400000) > 30){
           alert('Для построения по дням, интервал дат не должен прывишать 30 дней. Сейчас '+((end-start)/86400000)+' дней.');
      return false;
      
      }
  }
    var cat = [];
    var res = [];
    var new_data = 'method=list_category&'+t;
    $.ajax({
        beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                    console.log(res);
				cat = res;
                                 $('#table_result').detach();
                            
                                 var t = document.getElementById("prognoz");
                                // var botton = document.createElement("button");
                                  var button = document.createElement("button");
                                          //button.type = "button";
    button.innerHTML = "Скачать таблицу";
    button.setAttribute("class", "btn btn-danger");
    button.setAttribute("onClick", "return download('table_result')");
                                
                    var table = document.createElement("table");
                    table.setAttribute("id", "table_result");
                    table.setAttribute("class", "table");
                    t.appendChild(table);
                    t.appendChild(button);
                             
var header = table.createTHead();
  var row = header.insertRow(0);
            row.insertCell(0).innerHTML = "<b>Категория</b>";
            row.insertCell(1).innerHTML = "<b>Тек.Остаток</b>";
            row.insertCell(2).innerHTML = "<b>Диапазон</b>";
            row.insertCell(3).innerHTML = "<b>Остаток</b>";
            row.insertCell(4).innerHTML = "<b>Продажи</b>";
            row.insertCell(5).innerHTML = "<b>Добавлено</b>";
            row.insertCell(6).innerHTML = "<b>Оборот</b>";
            row.insertCell(7).innerHTML = "<b>Прогноз</b>"; 
            
                    },
                    error: function (res) {
                       
				console.log(res);
                                 $('#foo').detach();
                                  alert('error');
				}
            }).done(function(res) {
                 var table = document.getElementById("table_result");
                var body = table.createTBody();
var from = $('#form_prognoz').serializeArray();
console.log(from);
var from_prognoz = '';
var to_prognoz = '';
var interval_prognoz = '';
for(var kk in from){
    if(from[kk].name == "from_prognoz"){
        from_prognoz = from[kk].name+'='+from[kk].value;
    }
    if(from[kk].name == "to_prognoz"){
        to_prognoz = from[kk].name+'='+from[kk].value;
    }
    if(from[kk].name == "cat_prognoz"){
       cat_prognoz =  from[kk].name+'='+from[kk].value
    }
    if(from[kk].name == "interval_prognoz"){
        interval_prognoz = from[kk].name+'='+from[kk].value;
    }
}
var st = [from_prognoz, to_prognoz, interval_prognoz];
var ss = st.join('&');
  //  var cat = [];
    
   // for(var index in res){
      //  var item = res[index];
      //  cat.push({id:index, name:res[index]});
 // cat_prognoz = 'cat_prognoz='+index;
   // var st = [from_prognoz, to_prognoz, interval_prognoz];
   // var ss = st.join('&');
  
//}
 prognoz_table(ss, body, res, 0, res.length);
console.log(cat);

               //  $('#foo').detach();
            });
    
               return false;     
}
function prognoz_table(form, element, cat, index, count){
  
  var new_data = form+'&cat_prognoz='+cat[index].id;
  
		 $.ajax({
                url: '/admin/home/', 
                type: 'POST',
                dataType: 'json',
                data: 'method=prognoz_table&'+new_data,
                success: function (res) {
                                console.log(res);

   //  var row = element.insertRow();
   //  row.insertCell(0).innerHTML = cat[index].name;
   //  row.insertCell(1).innerHTML = res.tost;
    // row.insertCell(2).innerHTML = '';
    // row.insertCell(3).innerHTML = ''; 
   //  row.insertCell(4).innerHTML = '';
   //  row.insertCell(5).innerHTML = '';
    // row.insertCell(6).innerHTML = '';
   // row.insertCell(7).innerHTML = res.raznica < 0?'<span class="text-danger">'+res.raznica+'</span>':'<span class="text-success">'+res.raznica+'</span>';
  
    var z = 0;
                    for(var i in res.x){
                         var row = element.insertRow();
                        if(z==0){
                            row.insertCell(0).innerHTML = cat[index].name;
     row.insertCell(1).innerHTML = res.tost;
     row.insertCell(2).innerHTML = res.x[i];
    row.insertCell(3).innerHTML = res.ost[i];
    row.insertCell(4).innerHTML = res.prod[i];
    row.insertCell(5).innerHTML = res.add[i];
    row.insertCell(6).innerHTML = res.oborot[i];
    row.insertCell(7).innerHTML = res.raznica < 0?'<span class="text-danger">'+res.raznica+'</span>':'<span class="text-success">'+res.raznica+'</span>';
                        }else{
    row.insertCell(0).innerHTML = '';
    row.insertCell(1).innerHTML = '';
    row.insertCell(2).innerHTML = res.x[i];
    row.insertCell(3).innerHTML = res.ost[i];
    row.insertCell(4).innerHTML = res.prod[i];
    row.insertCell(5).innerHTML = res.add[i];
    row.insertCell(6).innerHTML = res.oborot[i];   
                        }
    z++;
    }
     var ind = index+1;
    if(ind < count){
        
        prognoz_table(form, element, cat, ind, count);
    }else{
        $('#foo').detach();
    }
                },
				error: function (res) {
				console.log(res);
                                return false;
				}
            });
           
        }