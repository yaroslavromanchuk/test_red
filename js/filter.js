function ToPage(page) {//пагинация по страницам
	$('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
var get_page = '';
if($('#search_word').val()){
    get_page += 's='+$('#search_word').val()+'&';
}
    if($('#order_by option:selected').val()){
       get_page += 'order_by='+$('#order_by option:selected').val();  
       if(page > 1){
           get_page +='&page='+(page-1);
       }
        }else if(page > 1){
            get_page +='page='+(page-1);
            
        }

        //console.log(get_page);
        if(get_page != ''){
    location.search = get_page;
        }else{
           location.href = location.pathname;
        }
	return false;
}

function getOrdreBy(e, p) {//сортировать товар на сайте
    var  order_by = '';
   if($('#search_word').val()){
    order_by += 's='+$('#search_word').val()+'&';
}
    order_by += 'order_by='+e.value;
    if(p > 0){
       order_by += '&page='+p;
    }
    
    if(order_by != ''){
    location.search = order_by;
        }else{
           location.href = location.pathname;
        }
}
function gatFilters(array) {//вызов фильтра товаров
   var  a = [];
   $.each( array, function(key, value ) {
      // console.log(value.name);
       if(value.value !== "" && value.name !== "selected_root_category"){
  if(a[value.name]){
     a[value.name].push(value.value);
  }else{
       a[value.name] = [value.value];
  }
   }
});

   var gettext = [];
    for(var key in a){
        if(a[key].length >= 1){
            gettext.push(key+'-'+a[key].join(',')); 
        }
        
        
    }
                var search = '';
		if($('#search_word').val()){
                    search = '?s='+$('#search_word').val();
                }	
//console.log($('#g_url').val()+gettext.join('-')+search);
		location.href = "https://www.red.ua"+$('#g_url').val()+gettext.join('-')+search;

}
function getClearAllFilters(){// уочистка фильтров
window.location.pathname = $('#g_url').val();
return false;
}
function but_val_new(obj) {
$('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');

	//obj.attr('disabled', 'disabled');
	$('.items_on_page').val(obj.val());
	document.cookie = "items_on_page="+obj.val()+"; path=/";
location.reload();
	return false;
}


