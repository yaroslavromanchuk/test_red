
$(document).ready(function () {
if (window.location.hash) {parseHash(); }
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
function goSearch(q) {
	reset_params = 0;
	var total_pages = parseInt($('#total_pages').val());
	var page = parseInt($('#current_page').val()) + 1;
	var calc_hash = '#';
	if (page == total_pages) {
		$('#show_next').attr('disabled', true);
	} else {
		gatheringSelected('', page, q);
		$('#current_page').val(page);
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
	var gettext = '?';
	var request = 'page=' + page + '&selected_root_category=' + $('#selected_root_category').val() + '&';
	if (run == 0) {
		gettext += 'categories=';
		request += 'search_word="' + $('#search_word').val() + '&categories=';
		$('.c_category:checked').each(function () {
			gettext += $(this).val() + ',';
			request += $(this).val() + ',';
		});
		request += '&colors=';
		gettext += '&colors=';
		$('.c_color:checked').each(function () {
			gettext += $(this).val() + ',';
			request += $(this).val() + ',';
		});
		request += '&sizes=';
		gettext += '&sizes=';
		$('.s_size:checked').each(function () {
			gettext += $(this).val() + ',';
			request += $(this).val() + ',';
		});
		request += '&labels=';
		gettext += '&labels=';
		$('.c_label:checked').each(function () {
			gettext += $(this).val() + ',';
			request += $(this).val() + ',';
		});
		request += '&skidka=';
		gettext += '&skidka=';
		$('.c_skidka:checked').each(function () {
			gettext += $(this).val() + ',';
			request += $(this).val() + ',';
		});
		request += '&sezons=';
		gettext += '&sezons=';
		$('.c_sezon:checked').each(function () {
			gettext += $(this).val() + ',';
			request += $(this).val() + ',';
		});
		request += '&brands=';
		gettext += '&brands=';
		$('.c_brand:checked').each(function () {
			gettext += $(this).val() + ',';
			request += $(this).val() + ',';
		});
		request += '&price_min=' + $(".tooltip-min .tooltip-inner").html();
		request += '&price_max=' + $(".tooltip-max .tooltip-inner").html();
		gettext += '&order_by=' + $('#order_by option:selected').val();
		request += '&order_by=' + $('#order_by option:selected').val();
		run = 1;
		request += '&on_page=' + $('.items_on_page').val();
		console.log(gettext);
		$.ajax({
			beforeSend: function () {
				$('#show_next_text').html('Подождите, идёт загрузка...');
				$('#show_next').hide();
				$('.res_loader').show();
				$('#total_founded').hide();
				if (q == 1) {
					$('html, body').animate({
						scrollTop: 0
					}, 'slow');
				} else if (zz == 1) {
					$('#top').toggle();
					$('#filter').toggle();
				}
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
					$('#result').html(data.result);
				}
				$('#show_next_text').html('Показать ещё 15 товаров');
				$('#show_next').show();
				$('#total_founded').html(data.total_count);
				reset_params = 0;
				if (page + 1 == data.total_pages) {
					$('#show_next').attr('disabled', true);
				} else {
					$('#show_next').attr('disabled', false);
				}
				$('#total_pages').val(data.total_pages);
				$('.res_loader').hide();
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
	$(".tooltip-min .tooltip-inner").html($('#minCost').val());
	$(".tooltip-max .tooltip-inner").html($('#maxCost').val());


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
