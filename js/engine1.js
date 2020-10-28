
//$(function () {
    //console.log('&'.charCodeAt(0));
//if (window.location.hash){ parseHash(); }
console.log(window.location);
//console.log(window.location.search.indexOf("s"));

//br = window.location.pathname.indexOf("brandss");
//br.find('brands');
//console.log(br);
//console.log(location);
//return false;
//});

//новые фильтры
function ToPage(page) {//пагинация по страницам
	//$('#current_page').val(p - 2);
	//change_page_hash = true;
	//console.log(page);
        console.log(window.location);
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
    //gatfilterSelected((page-1));
	return false;
}

function sorter(e, p) {//сортировать товар на сайте
   // console.log(e.value);
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
   // location.search = order_by;
	//gatfilterSelected(page);
}
function gatfilterSelected(page) {//вызов фильтра товаров
var gettext = [];
c = []; 
		$('.c_category:checked').each(function () {c.push($(this).val());});
		if(c.length > 0) gettext.push('categories-'+c.join(','));
c = [];
		$('.c_brand:checked').each(function () {c.push($(this).val());});
		if(c.length > 0 ) gettext.push('brands-'+c.join(','));
c = [];
		$('.c_sezon:checked').each(function () { c.push($(this).val());});
		if(c.length > 0) gettext.push('sezons-'+c.join(','));
c = [];
		$('.s_size:checked').each(function () { c.push($(this).val());});
		if(c.length > 0) gettext.push('sizes-'+c.join(','));
c = [];
		$('.c_color:checked').each(function () { c.push($(this).val());});
		if(c.length > 0) gettext.push('colors-'+c.join(','));
c = [];
		$('.c_label:checked').each(function () { c.push($(this).val());});
		if(c.length > 0) gettext.push('labels-'+c.join(','));
c = [];
		$('.c_skidka:checked').each(function () {c.push($(this).val());});
		if(c.length > 0) gettext.push('skidka-'+c.join(','));

		if($('#minCost').val()) gettext.push('price_min-'+$('#minCost').val());
		if($('#maxCost').val()) gettext.push('price_max-'+$('#maxCost').val());
		
                var search = '';
		if($('#search_word').val()){
                    search = '?s='+$('#search_word').val();
                   // gettext.push('?s='+$('#search_word').val());
                }	
		
//if($('#order_by option:selected').val()) {
    //gettext.push('order_by/'+$('#order_by option:selected').val()+'/');
    //sear.push('order_by='+$('#order_by option:selected').val());
//}
		
		//if(page > 0) {
                    //gettext.push('page/'+page);
                  //  sear.push('page='+page);
               // }
                
		//if(gettext.length === 0 && sear.length === 0){
                //    $('#foo').detach(); 
                //    return false; 
               // }
		//gettext = 
                
              //  if(sear.length > 0){
               //sear = sear.join('&');
             // gettext += '?'+sear.join('&');
          // / console.log(sear);
           // }
                
		//$('#current_page').val(page);
		//console.log(gettext);
		$('.caregory_footer').hide();
               // var g_url = '';
                //if($('#g_url').val()){ g_url = $('#g_url').val();}
		location.href = "https://www.red.ua"+$('#g_url').val()+gettext.join('-')+search;
		//location.search = search;
		//window.location;
		//$('#foo').detach();
}
function clearallfilters(){// уочистка фильтров
window.location.pathname = $('#g_url').val();
return false;
}
//выход с новых фильтров
////////////////старые фильтры
var script_change_hash = true;
var run = 0;
var reset_params = 0;
var prev_hash = '';
var change_page_hash = true;

function prepareSearchToPage(p, q) {
	$('#current_page').val(p - 2);
	change_page_hash = true;
	goSearch(q);
	return false;
}
function changeSortOrder() {
	$('#current_page').val(-1);
	goSearch();
}

function goSearch(q) {
	reset_params = 0;
	var total_pages = parseInt($('#total_pages').val());
	var page = parseInt($('#current_page').val()) + 1;
	var calc_hash = '#';
	if (page == total_pages) {
		$('#show_next').attr('disabled', true);
	} else {
	$('.caregory_footer').hide();
		gatheringSelected('', page, q);
		$('#current_page').val(page);
		//$('#foo').detach();
	}
	prev_hash = window.location.hash;
	script_change_hash = true;
	if (change_page_hash || true) {
		calc_hash += "page=" + (page + 1);
	}
	calc_hash += '&order_by=' + $('#order_by option:selected').val();
	window.location = calc_hash;
	
	
	change_page_hash = false;
	script_change_hash = false;
}
function gatheringSelected(what, page, q) {
	var zz = 0;
	if ( false && q == 4) { zz = 1;}
	var request = 'page=' + page + '&selected_root_category=' + $('#selected_root_category').val() + '&';
	if (run == 0) {
		
		request += 'search_word="' + $('#search_word').val() + '&categories=';
		$('.c_category:checked').each(function () {
			request += $(this).val() + ',';
		});
		request += '&colors=';
		$('.c_color:checked').each(function () {
			request += $(this).val() + ',';
		});
		request += '&sizes=';
		$('.s_size:checked').each(function () {
			request += $(this).val() + ',';
		});
		request += '&labels=';
		$('.c_label:checked').each(function () {
			request += $(this).val() + ',';
		});
		request += '&skidka=';
		$('.c_skidka:checked').each(function () {
			request += $(this).val() + ',';
		});
		request += '&sezons=';
		$('.c_sezon:checked').each(function () {
			request += $(this).val() + ',';
		});
		request += '&brands=';
		$('.c_brand:checked').each(function () {
			request += $(this).val() + ',';
		});

		request += '&order_by=' + $('#order_by option:selected').val();
		run = 1;
		request += '&on_page=' + $('.items_on_page').val();
		$.ajax({
			beforeSend: function () {
				$('#show_next').hide();
				$('#total_founded').hide();
				
				$('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
				
			},
			type: 'POST',
			url: '/finder/ajaxsearch/&',// + request,
			data: request,
			success: function (data) {
			
				run = 0;
				var data = JSON.parse(data);
				//console.log(data);
				var enb = data.enabled_params;
				pasteResult(what, 'c_category', enb.categories, 'categories', 0);
				pasteResult(what, 'c_color', enb.colors, 'colors', 0);
				pasteResult(what, 's_size', enb.sizes, 'sizes', 0);
				pasteResult(what, 'c_label', enb.labels, 'labels', 0);
				pasteResult(what, 'c_sezon', enb.sezons, 'sezons', 0);
				pasteResult(what, 'c_skidka', enb.skidka, 'skidka', 0);
				pasteResult(what, 'c_brand', enb.brands, 'brands', 0);
				if (q == 2) {
					$('#result').append(data.result);
				} else {
				if(page > 0) $('head').append('<link rel="canonical" href="'+window.location.pathname+'"/><meta name="robots" content="noindex, follow" />');
					$('#result').html(data.result);
				}
				if (q == 1) {
					$('html, body').animate({ scrollTop: 0 });
				} else if (zz == 1) {
					$('#top').toggle();
					$('#filter').toggle();
				}
				
				$('#foo').detach();
				$('#show_next').show();
				$('#total_founded').html(data.total_count);
				reset_params = 0;
				if (page + 1 == data.total_pages) {
					$('#show_next').attr('disabled', true);
				} else {
					$('#show_next').attr('disabled', false);
				}
				$('#total_pages').val(data.total_pages);
				$('#total_founded').show();
				
				//initiateArticlesJS();
			},
			error: function(e){
			console.log(data);
			}
		});
		
		return true;
	} else {
		return false;
	}
}
function pasteResult(what, clss, arr, curr, use_name) {
	$('.' + clss).each(function () {
		var o = $(this);
		var disabled = true;
		var v = o.val();
		for (var k in arr) {
			if (use_name) {
				if (v == arr[k].name) {
					disabled = false;
				}
			} else {
				if (v == arr[k].id) {
					disabled = false;
				}
			}
		}
		if (what != curr) {
			//if (disabled && o.attr('checked')) {}
			if (disabled) {
			
				o.parent().hide();
				o.parent().addClass('i_disabled');
			} else {
			l = o.parent();
			c = l.children('span').children();
			for (var z in arr) {
			if(arr[z].id == o.val()) c.html(arr[z].count);
			}
				o.parent().show();
				o.parent().removeClass('i_disabled');
			}
		}
	});
}
function resetOneFilter(cls, reset_checked) {
	$('.' + cls).each(function () {
		var o = $(this);
		if (reset_checked) {
			o.attr('checked', false);
		}
		o.attr('disabled', false);
		o.parent().removeClass('i_disabled');
	});
}
function clearsearchfilters() {
	reset_params = 1;
	run = 1;
	resetOneFilter('c_category', true);
	resetOneFilter('c_color', true);
	resetOneFilter('s_size', true);
	resetOneFilter('c_label', true);
	resetOneFilter('c_sezon', true);
	resetOneFilter('c_brand', true);
	resetOneFilter('c_skidka', true);
	//$(".tooltip-min .tooltip-inner").html($('#minCost').val());
	//$(".tooltip-max .tooltip-inner").html($('#maxCost').val());


	run = 0;
	gatheringSelected('', 1);
	$('#show_next').attr('disabled', false);
	$('#curent_page').val(0);
	return false;
}
//выход с старых фильтров
$(window).bind('hashchange', function () {
	var hash = window.location.hash;
	if (!script_change_hash) {
		parseHash();
	}
});
function but_val(obj) {
	$('#page_navi button').removeAttr('disabled');
	obj.attr('disabled', 'disabled');
	$('.items_on_page').val(obj.val());
	prepareSearchToPage(1, 0);
	return false;
}
function but_val_new(obj) {
//console.log(obj.val());
	//$('#page_navi button').removeAttr('disabled');
	obj.attr('disabled', 'disabled');
	$('.items_on_page').val(obj.val());
	document.cookie = "items_on_page="+obj.val()+"; path=/";
	//$.session.set("items_on_page", obj.val());

//sessionStorage['items_on_page'] = obj.val();
	//prepareSearchToPage(1, 0);
	gatfilterSelected();
	return false;
}
