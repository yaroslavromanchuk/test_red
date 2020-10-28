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
	if (size > 0 && color > 0) {
		$.ajax({
			beforeSend: function () {
				
			},
			type: "POST",
			url: '/addtocard/id/'+id+'/',
			data: '&size=' + size + '&color=' + color + '&artikul=' + art,
			dataType: 'json',
			success: function (data) {
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
                                $('#sub_bascet').prop('disabled',true);
                                
                                $(".error.error_add .mes").html('');
                                $(".error.ok_add .mes").html(data.message);
                                $(".error.ok_add").fadeIn();
				$("#error").html('');
				$('#wait_circle').show();

					$('#span_ok1').addClass("span_ok").html(data.count).show();
					$('#span_ok').addClass("span_ok").html(data.count).show();
					ga('send', 'add', '/virtual/tovartobacket/');
					dataLayer.push({'event' : 'articles', 'eventAction': 'add_backet', 'eventLabel' : $("#id_tovar").val(), 'eventValue' : price });
				} else {
                                    
                                $(".error.ok_add .mes").html('');
                                console.log(data.message);
                                $(".error.error_add .mes").html(data.message);
				$(".error.error_add").fadeIn();
				}

			},
			error: function (data) {
				console.log('error = ' + data);
			},
			complete: function () {
				$('#wait_circle').hide();
				setTimeout(function () {
                                   // $(".error.ok_add").css({'opacity': '0'});
                                   // $(".error.error_add").css({'opacity': '0'});
                                    $(".error.ok_add").fadeOut(10);
                                    $(".error.error_add").fadeOut(10);
				}, 4000);
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
		$('#view_article').html('');
                $('#id_comment_modal_article').html('');
		},
		type: "POST",
		url: '/quikview/id/' + id + '/metod/frame/',
                dataType: 'json',
		success: function (data) {
                    console.log(data);
                   console.log(data.title);
                    $('#id_comment_modal_article').html(data.title);
			$('#view_article').css({
				'padding': '0',
				'height': 'auto'
			}).html(data.data);
		},
		error: function (data) {
                    console.log(data);
                },
		complete: function () {
			/*$('a.cloud-zoom').lightBox({
				fixedNavigation: true,
				overlayOpacity: 0.6
			});
			$('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();*/
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
        
           // if (color == 0 || typeof color == "undefined"){$(".error.color").fadeIn();}else{$(".error.color").html('');}
           // if (size == 0 || typeof size == "undefined"){$(".error.size").fadeIn();}else{$(".error.size").html('');}
            
    if (size > 0 && color > 0) {
		$.ajax({
			beforeSend: function () {
				$("#error").html('');
				$('#wait_circle').show();
			},
			type: "POST",
			url: '/addtocard/id/'+id+'/',
			data: '&size=' + size + '&color=' + color + '&artikul=' + art,
			dataType: 'json',
			success: function (data) {
				if (data.error != 1) {
					$('#span_ok1').addClass("span_ok").html(data.count).show();
					$('#span_ok').addClass("span_ok").html(data.count).show();
					 $('#add_card').prop('disabled',true);
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
		//return true;
	} else{
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
		return false;
}

$(document).ready(function () {
	//$('.menu-2-box').liFixar({side: 'top',position: $('.top_menu_new').innerHeight()});//
       // $('.filter_fixed').liFixar({side: 'top',position: $('.menu-2-box').innerHeight()+$('.top_menu_new').innerHeight(), 'background-color' : '#a59e9e !important' });//
        //$('#filter').liFixar({side: 'top',position: $('.menu-2-box').innerHeight()});//
	
$("#qo").submit(function () { //bistriy zakaz
	
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
			$.ajax({
			beforeSend: function () {
			$('#hide .modal-body #qo-result').html('<div style="text-align:center;"><img src="/img/loading_trac.png"></div>');
			$('#qo-result').show();
			$('#hide .modal-footer').hide();
				},
				type: 'POST',
				url: '/quick-order/',
				data: $("form#qo").serialize() + '&size=' + $("input[name='size']:checked").val() + '&color=' + $("input[name='color']:checked").val()+'&artikul='+$('#artikul').val(),
				dataType: 'json',
				success: function (data) {
				
				if(data.result == 'send'){
                                  dataLayer.push({'event' : 'quick', 'eventAction' : 'add_quick'});
				$('#qo-result').hide();
				$('#hide .modal-body').html(data.message);
				$('#hide .modal-footer').hide();
				ga('send', 'quick', '/virtual/quick/');
				ga('send', {hitType: 'event', eventCategory: 'quick',  eventAction: 'add_quick' });
				}else{
				var er = data.message.error;
				t = '';
				for(var key in er){
				$('#'+key).addClass('is-invalid');
				t+=er[key];
				
				}
				$('#hide .modal-body #qo-result').html('<div class="alert alert-danger" role="alert">'+t+'</div>');
					$('#qo-result').fadeIn(300);
					$('#hide .modal-footer').show();
				}	
				},
				error: function (e) {
                                    console.log(e);
					$('#qo-result').html('<div class="alert alert-danger" role="alert">Извините, но при отправке заказа произошла ошибка, попробуйте позже</div>');
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
        
	$('#filterButton button').click(function (){
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
        
	$('#brand_discription').hide();
        
	$('#brand_name').hover(function () {
		$('#brand_discription').fadeIn();
	}, function () {
            
        });
        
	$('#brand_discription').hover(function () {
            
        }, function () {
		$(this).fadeOut();
	});

    
//$("#scrolled-socials").hide();
	// hide #back-top first
	$("#back-top").hide();
	// fade in #back-top
        
$(function () {
	$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
			//$("#scrolled-socials").fadeIn();
				$('#back-top').fadeIn();
			} else {
			//$("#scrolled-socials").fadeOut();
				$('#back-top').fadeOut();
			}
		});
		// scroll body to 0px on click
$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 500);
			return false;
		}); 
	});
        
});
/*
(function() {
var widgetId = 'a977b0023f4594ba63190bf5ca00d6ba';
var s = document.createElement('script');
s.type = 'text/javascript';
s.charset = 'utf-8';
s.async = true;
s.src = '//callme.voip.com.ua/lirawidget/script/'+widgetId;
var ss = document.getElementsByTagName('script')[0];
ss.parentNode.insertBefore(s, ss);
}
)();*/


function setUk(lang, ses, url) {
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
}
/*
document.addEventListener("DOMContentLoaded", function() {
  var lazyloadImages = document.querySelectorAll('img[data-src]');    
  var lazyloadThrottleTimeout;
  
  function lazyload () {
    if(lazyloadThrottleTimeout) {
      clearTimeout(lazyloadThrottleTimeout);
    }    
    
    lazyloadThrottleTimeout = setTimeout(function() {
        var scrollTop = window.pageYOffset;
        [].forEach.call(document.querySelectorAll('img[data-src]'),    function(img) {
            if(img.offsetTop < (window.innerHeight + scrollTop)) {
  img.setAttribute('src', img.getAttribute('data-src'));
            }
  img.onload = function() {
    img.removeAttribute('data-src');
  };
});

        if(lazyloadImages.length == 0) { 
          document.removeEventListener("scroll", lazyload);
          window.removeEventListener("resize", lazyload);
          window.removeEventListener("orientationChange", lazyload);
        }
    }, 50);
  }

  document.addEventListener("scroll", lazyload);
  window.addEventListener("resize", lazyload);
  window.addEventListener("orientationChange", lazyload);
});*/
$(window).load(function() {
/** код будет запущен когда страница будет полностью загружена, включая все фреймы, объекты и изображения **/
       [].forEach.call(document.querySelectorAll('img[data-src]'), function(img) {
img.setAttribute('src', img.getAttribute('data-src'));
img.onload = function() {
img.removeAttribute('data-src');
};
});
});


var accept_cookies_button = document.getElementById('user-accept-cookies');
var cookie_notice = document.querySelector('.user-cookie-notice');
if (getCookie('cookie_consent') !== 'true' && cookie_notice !== null) {
  cookie_notice.style.display = 'flex';
}

if (accept_cookies_button !== null) {
  accept_cookies_button.addEventListener('click', function (e) {
    if (getCookie('cookie_consent') !== 'true') {
      setCookie('cookie_consent', 'true', 365);
      cookie_notice.style.transition = 'opacity 1s ease';
      cookie_notice.style.opacity = '0';

      setTimeout(function () {
        cookie_notice.style.visibility = 'hidden';
      }, 1000)
    }
  })
}

function setCookie (cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = 'expires=' + d.toUTCString();
  document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
}

function getCookie (cname) {
  var name = cname + '=';
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';')
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return '';
}



