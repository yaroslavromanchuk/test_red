$(function ($) {
	$.extend({
		rvkParseInt: function (val) {
			val = parseInt(val);
			return isNaN(val) ? 0 : val;
		}
	});
	$('a[rel=_blank]').attr('target', '_blank').removeAttr('rel');
	$('input.default-input').each(function () {
		$(this).bind('init', function () {
			var val = $.trim($(this).val()),
			def = $(this).attr('default');
			$(this)[val.length == 0 || val == def ? 'addClass' : 'removeClass']('default');
			if ($(this).hasClass('default'))
				$(this).val(def);
		}).trigger('init');
		$(this).bind('focus', function () {
			var val = $.trim($(this).val()),
			def = $(this).attr('default');
			$(this).removeClass('default');
			if (val == def)
				$(this).val('');
		});
		$(this).bind('blur', function () {
			$(this).trigger('init');
		});
	});
});
function toggleCategory(id) {
	$('#' + id + '_for_toggle').slideToggle('fast', function () {
		if ($('#' + id + '_for_toggle').css('display') == 'none') {
			$('#' + id).html('Развернуть');
		} else {
			$('#' + id).html('Свернуть');
		}
	});
	return false;
}
function getQuickCart(id) {//dobavlenie tovara v korzinu
	var size = $("input[name='size']:checked").val();
	var color = $("input[name='color']:checked").val();
	var art = $("#artikul").val();
	var price = $("#price").val();
	//console.log();
	if (size > 0 && color > 0) {
		$.ajax({
			beforeSend: function () {
				
			},
			type: "POST",
			url: id,
			data: '&metod=frame&size=' + size + '&color=' + color + '&artikul=' + art,
			dataType: 'json',
			success: function (data) {
			//console.log(data);
				if (data.error != 1) {
				
				$('#test').clone()
				.appendTo('.container-fluid')
				.css({
					'position': 'absolute',
					'z-index': '9999',
					top: $('#test').offset().top,
    left: $('#test').offset().left,
    width: '400px',
	'border-radius': '100px'
				})
				.animate({
					//opacity: 'toggle',
					top: $('.img_bag').offset().top-25,
					left: $('.img_bag').offset().left+5,
					width: '30px'
				}, {
    duration: 1000,
	//queue: false,
    specialEasing: {
      opacity: 'linear',
      height: 'swing'
    } }, function () {
					$(this).remove();
				}).animate({
				 top: $('.img_bag').offset().top+10,
				 left: $('.img_bag').offset().left+10,
				 opacity: 'toggle',
				 width: '10px'
				}, {
    duration: 500});
	
                                $(".error.error_add").html('');
                                $(".error.ok_add").fadeIn();
				//$("#message").html('');
				$("#error").html('');
				//$('#quik_frame').html('');
				$('#wait_circle').show();
				
				
					$('#span_ok1').addClass("span_ok").html(data.count).show();
					$('#span_ok').addClass("span_ok").html(data.count).show();
					//ga('send', 'event', 'Articles', 'tovartobacket');
					ga('send', 'add', '/virtual/tovartobacket/');
					//_gaq.push(['tovarTobacket','/virtual/tovartobacket/']);
					dataLayer.push({'event' : 'articles', 'eventAction': 'add_backet', 'eventLabel' : $("#id_tovar").val(), 'eventValue' : price });
					//console.log(dataLayer);
					//console.log(data);
					//$("#message").html(data.message);
					//_gaq.push(['_trackEvent', 'articles', 'add_backet']);
					//ga('send', 'event', 'articles', 'add_backet');
				} else {
                                $(".error.ok_add").html('');
				$(".error.error_add").fadeIn();
					//console.log(data.message);
				}
                                
				//$('#wait_circle').hide();
			},
			error: function (data) {
				console.log('error = ' + data);
			},
			complete: function () {
				$('#wait_circle').hide();
				setTimeout(function () {
                                    $(".error.ok_add").css({'opacity': '0'});
                                    $(".error.error_add").css({'opacity': '0'});
                                    //$("#message").css({'opacity': '0'});
					//$("#message").fadeOut(500)
				}, 5000);
			}
		});
	}
	if (color <= 0 || !color) {
		$(".error.color").fadeIn();
	} else {
		$(".error.color").html('');
	}
	if (size <= 0 || !size) {
		$(".error.size").fadeIn();
	} else {
		$(".error.size").html('');
	}
}
function getQuickOrder(id) {//bistriy zakaz open form
	$('#qo-result').hide();
	var size = $("input[name='size']:checked").val();
	var color = $("input[name='color']:checked").val();
	if (color == 0 || typeof color == "undefined")
		$(".error.color").fadeIn();
	else
		$(".error.color").html('');
	if (size == 0 || typeof size == "undefined")
		$(".error.size").fadeIn();
	else
		$(".error.size").html('');
	if (size > 0 && color > 0) {
	
		$('#quick_order').attr('data-toggle', 'modal');
	}
}
function getQuikArticle(id) {//bistriy prosmotr
	$.ajax({
		beforeSend: function () {
		//$('#view_article').html('');	
		},
		type: "POST",
		url: '/product/id/' + id + '/metod/frame/',
		success: function (data) {
			$('#view_article').css({
				'padding': '0',
				'height': 'auto'
			}).append(data);
		},
		error: function (data) {
                    console.log(data);
                },
		complete: function () {
			$('a.cloud-zoom').lightBox({
				fixedNavigation: true,
				overlayOpacity: 0.6
			});
			$('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
		}
	});
}
function getQuikBrand(id) {//open brand
	$.ajax({
		beforeSend: function () {
		$('.brand_info').html('');
		},
		type: "POST",
		url: '/product/id/' + id + '/metod/getbrand/',
		success: function (data) {
		
			$('.brand_info').css({
				'padding': '5px',
				'height': 'auto'
			}).append(data);
		},
		error: function (data) {
                    console.log(data);
                }
	});
	return false;
}
function submitCartValidator(id) {// add korzina s bistrogo prosmotra
	var size = $("input[name='size']:checked").val();
	var color = $("input[name='color']:checked").val();
	var art = $("#artikul").val();
	if (color == 0 || typeof color == "undefined")
		$(".error.color").fadeIn();
	else
		$(".error.color").html('');
	if (size == 0 || typeof size == "undefined")
		$(".error.size").fadeIn();
	else
		$(".error.size").html('');
	if (size > 0 && color > 0) {
		$.ajax({
			beforeSend: function () {
				$("#error").html('');
				$('#wait_circle').show();
			},
			type: "POST",
			url: id,
			data: '&metod=frame&size=' + size + '&color=' + color + '&artikul=' + art,
			dataType: 'json',
			success: function (data) {
				if (data.error != 1) {
					$('#span_ok1').addClass("span_ok").html(data.count).show();
					$('#span_ok').addClass("span_ok").html(data.count).show();
					
				} else {
					console.log('bs');
				}
				$('#wait_circle').hide();

			},
			error: function (data) {
                            console.log(data);
                        },
			complete: function () {
				$('#wait_circle').hide();
				setTimeout(function () {
					$('.simple_overlay').fadeOut(300);
					$('#exposeMask').fadeOut(300);
					$('#quik_frame').fadeOut(300);
					$('#quik_frame').html('');
				}, 2000);
			}
		});
		return true;
	} else
		return false;
}
$(document).ready(function () {
	$('.menu-2-box').liFixar({side: 'top',position: $('.top_menu_new').innerHeight()});//
       // $('.filter_fixed').liFixar({side: 'top',position: $('.menu-2-box').innerHeight()+$('.top_menu_new').innerHeight(), 'background-color' : '#a59e9e !important' });//
        //$('#filter').liFixar({side: 'top',position: $('.menu-2-box').innerHeight()});//
	
$("#qo").submit(function () {//bistriy zakaz
	
		var f = $('#telephone').val();
		f = f.replace(/[^0123456789]/g, '');

		if (f.length != 12) {
			var x = 12 - f.length;
			var t = ' В номере телефона не хватает ' + x + ' цыфр.';
			$("#leb").css({
				'color': 'red'
			});
			$("#leb").text(t);
			$('#telephone').css({
				'border-color': '#d8512d'
			});
			setTimeout(function () {
				$('#telephone').removeAttr('style');
			}, 600);
		} else {
		//console.log($("form#qo").serialize());
			$.ajax({
			beforeSend: function () {
			$('#hide .modal-body #qo-result').html('<div style="text-align:center;"><img src="/img/loading_trac.png"></div>');
			$('#qo-result').show();
			$('#hide .modal-footer').hide();
//console.log($("form#qo").serialize() + '&size=' + $("input[name='size']:checked").val() + '&color=' + $("input[name='color']:checked").val()+'&artikul='+$('#artikul').val());
					//$('#hide').hide();
					//$('#qo-first_step').hide();
					//$('#qo-load').show();
				},
				type: 'POST',
				url: '/quick-order/&',
				data: $("form#qo").serialize() + '&size=' + $("input[name='size']:checked").val() + '&color=' + $("input[name='color']:checked").val()+'&artikul='+$('#artikul').val(),
				dataType: 'json',
				success: function (data) {
				dataLayer.push({'event' : 'quick', 'eventAction' : 'add_quick'});
				//console.log(data);
				if(data.result == 'send'){
				$('#qo-result').hide();
				$('#hide .modal-body').html(data.message);
				$('#hide .modal-footer').hide();
				ga('send', 'quick', '/virtual/quick/');
				ga('send', {hitType: 'event', eventCategory: 'quick',  eventAction: 'add_quick' });
				}else{
				er = data.message.error;
				//console.log(er);
				t = '';
				for(var key in er){
				$('#'+key).addClass('is-invalid');
				//console.log(er[key]);
				t+=er[key];
				
				}
				$('#hide .modal-body #qo-result').html(t);
					$('#qo-result').fadeIn(300);
					$('#hide .modal-footer').show();
				}	
				},
				error: function (e) {
					$('#qo-result').html('Извините, но при отправке заказа произошла ошибка, попробуйте позже');
					$('#qo-result').show();
					$('#hide .modal-footer').hide();
				}
			});
		}
		return false;
	});
        
	$("[data-tooltip='tooltip']").tooltip();
       // $('.carousel').carousel();
         $("[data-popover='popover']").popover({container: 'body'});
	
	$("a.l_box").each(function () { $(this).lightBox(); });

	$("#QuickCartHide, .simple_overlay_back").click(function () {
		//$('.simple_overlay').hide();
		$('.simple_overlay_back').hide();
	});
        
	$('.list_wrapper:gt(2) > .drop_list').hide();
	$('.list_wrapper > .sub-title').click(function () {
		$('.drop_list', $(this).parent()).slideToggle();
		if ($(this).parent().hasClass('sub-title-click')) {
			$(this).parent().removeClass("sub-title-click");
		} else {
			$(this).parent().addClass("sub-title-click");
		}
	});
	var brand_vis_all = 1;
	$('#filterButton button').click(function () {
		$('#filterButton button').removeClass('filterButton_classSelect');
		$(this).addClass('filterButton_classSelect');
		var brandList = $(this).parent().attr('id') + "_select";
		if (brandList == 'brand_tab_1_select') {
			$('.brand_tab_select').show();
			brand_vis_all = 1;
		} else {
			var selectElement = $('#' + brandList);
			if (!selectElement.is(":visible") || brand_vis_all) {
				brand_vis_all = 0;
				$('.brand_tab_select').hide(0, function () {
					selectElement.show();
				});
			}
		}
	});
        
	$('.brand_img').mouseover(function () {
		$(this).next().css({ 'text-decoration': 'underline'});
                
		$(this).mouseleave(function () { $(this).next().css({'text-decoration': 'none'}); });
	});
        
	$('#brand_discription').hide(0);
	$('#brand_name').hover(function () {
		$('#brand_discription').fadeIn();
	}, function () {
            
        });
        
	$('#brand_discription').hover(function () {
            
        }, function () {
		$(this).fadeOut();
	});

    
$("#scrolled-socials").hide();
	// hide #back-top first
	$("#back-top").hide();
	// fade in #back-top
$(function () {
	$(window).scroll(function () {
        /*if($("form").is(".form-filter")){
            if($(this).scrollTop() > 250){
                $('.filter_fixed').css({
                    'position':'fixed',
                    'top':$('.menu-2-box').innerHeight()+$('.top_menu_new').innerHeight(),
                    'width':$('.filter_fixed').parent().innerWidth()
                });
            }else{
                 $('.filter_fixed').css({
                    'position':'',
                    'top':'',
                    'width':''
                });
            }
        }*/
			if ($(this).scrollTop() > 100) {
			$("#scrolled-socials").fadeIn();
				$('#back-top').fadeIn();
			} else {
			$("#scrolled-socials").fadeOut();
				$('#back-top').fadeOut();
			}
		});
		// scroll body to 0px on click
$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		}); 
	});
        
});
(function() {
var widgetId = 'a977b0023f4594ba63190bf5ca00d6ba';
var s = document.createElement('script');
s.type = 'text/javascript';
s.charset = 'utf-8';
s.async = true;
s.src = '//callme.voip.com.ua/lirawidget/script/'+widgetId;
var ss = document.getElementsByTagName('script')[0];
ss.parentNode.insertBefore(s, ss);}
)();

function setUk(lang, ses, url) {
//console.log(ses);
//console.log(url);
//console.log(lang);
//console.log(s);
if(lang !== ses){
      $.ajax({
         type: "POST",
         url: "/ajax/setlang/",
         data: "&lang="+lang+"&ur="+url,
         success: function(res){
			 console.log(res);
			 location.replace(res);

		 },
		 error: function(e){
			 console.log(e);
			 
		 }
          });
		}
          return false;
}
function setCooki(e) {
document.cookie = "mobil =" + e;
location.reload();
         // return false;
}


