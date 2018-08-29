
$(document).ready(function () {
//if (window.location.hash){ parseHash(); }
});
function parseHash() {
	var hash = window.location.hash;
	var page = /page=(\d+)/.exec(hash);
	var order_by = /order_by=(\d+)/.exec(hash);
	if (page == null) {
		page = 0;
	} else {
		page = page[1];
	}
	$('#current_page').val(page - 2);
	if (order_by == null) {
		$('#order_by').val('');
	} else {
		$('#order_by').val(order_by[1]);
	}
	
	goSearch();
}

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



function ToPage(page) {
	//$('#current_page').val(p - 2);
	//change_page_hash = true;
	//console.log(page);
	
	gatfilterSelected((page-1));
	return false;
}

function sorter(page) {
	gatfilterSelected(page);
}
function gatfilterSelected(page) {
if(!page){
current_page = $('#current_page').val();
if(current_page == 0){ page = current_page;}else{ page =current_page-1;}
 //page = parseInt($('#current_page').val()-1);
 }
 console.log(page);
 //return false;
var gettext = [];

c = []; 
		$('.c_category:checked').each(function () {c.push($(this).val());});
		if(c.length > 0) gettext.push('categories='+c.join(','));
c = [];
		$('.c_brand:checked').each(function () {c.push($(this).val());});
		if(c.length > 0) gettext.push('brands='+c.join(','));
c = [];
		$('.c_sezon:checked').each(function () { c.push($(this).val());});
		if(c.length > 0) gettext.push('sezons='+c.join(','));
c = [];
		$('.s_size:checked').each(function () { c.push($(this).val());});
		if(c.length > 0) gettext.push('sizes='+c.join(','));
c = [];
		$('.c_label:checked').each(function () { c.push($(this).val());});
		if(c.length > 0) gettext.push('labels='+c.join(','));
c = [];
		$('.c_skidka:checked').each(function () {c.push($(this).val());});
		if(c.length > 0) gettext.push('skidka='+c.join(','));
		
		if($('#minCost').val()) gettext.push('price_min='+$('#minCost').val());
		if($('#maxCost').val()) gettext.push('price_max='+$('#maxCost').val());
		
			
		
		if($('#order_by option:selected').val()) gettext.push('order_by='+$('#order_by option:selected').val());
		
		gettext.push('page='+page);
		if(gettext.length == 0){ $('#foo').detach(); return false; }
		gettext = '?'+gettext.join('&');
		$('#current_page').val(page);
		//document.location.href = act;
		console.log(gettext);
		$('.caregory_footer').hide();
		return window.location.search = gettext;
		$('#foo').detach();
return false;
}
function clearallfilters(){
window.location.search = '';
return false;
}

////////////////
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
			url: '/finder/ajaxsearch/&' + request,
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
