/*
 * MEL.Tipper
 * 
 * http://melnaron.net/projects/meltipper
 * 
 * Copyright (c) 2008 Melnaron (melnaron.net)
 * 
 * Version: 1.2.1
 * Date:	2008-05-30
 */

jQuery.showTip = function(tip, x, y) {
	if (tip != null) {
		var t   = $('#divTip');
		var ox  = 16;
		var oy  = 16;
		var twp = parseInt(t.css('padding-left')) + parseInt(t.css('padding-right'));
		var twb = parseInt(t.css('border-left-width')) + parseInt(t.css('border-right-width'));
		var thp = parseInt(t.css('padding-top')) + parseInt(t.css('padding-bottom'));
		var thb = parseInt(t.css('border-top-width')) + parseInt(t.css('border-bottom-width'));
		
		if ($.browser.msie) {
			t.html(tip).show().css({width: ''});
			var tw  = t.width();
			var twm = parseInt(t.css('max-width'));
			if (tw > twm) {
				t.css('width', twm);
				tw = twm + twp + twb;
			} else {
				tw = tw + twp + twb;
			}
		} else {
			t.html(tip).show();
			var tw  = t.width() + twp + twb;
		}
		
		var th  = t.height() + thp + thb;
		
		x = (x + tw + ox > $(document).width()) ? x = x - tw - (ox/2) + 'px' : x = x + ox + 'px';
		y = (y + th + oy > $(document).height()) ? y = y - th - (oy/2) + 'px' : y = y + oy + 'px';
		
		t.css({left: x, top: y});
	}
}

jQuery.hideTip = function() {
	$('#divTip').hide();
}

jQuery.styleTip = function(style) {
	$('#divTip').css(style);
}

jQuery.initTips = function() {
	$('*[tip]')
		.hover(
			function(e) {
				$.showTip($(this).attr('tip'), e.pageX, e.pageY);
			},
			function() {
				$.hideTip();
			}
		)
		.mousemove(function(e) {
			$.showTip($(this).attr('tip'), e.pageX, e.pageY);
		})
	;
}

jQuery.fn.tip = function(tip, e) {
	if (tip == null) {
		$(this).removeAttr('tip');
		$.hideTip();
	} else {
		$(this)
			.attr('tip', tip)
			.hover(
				function(e) {
					$.showTip(tip, e.pageX, e.pageY);
				},
				function() {
					$.hideTip();
				}
			)
			.mousemove(function(e) {
				$.showTip(tip, e.pageX, e.pageY);
			})
		;
		if (e != null) {
			$.showTip(tip, e.pageX, e.pageY);
		}
	}
	return this;
}

$(document).ready(function() {
	$('<div id="divTip"></div>')
		.css({
			position:	'absolute',
			display:	'none',
			border:		'1px solid #333333',
			background:	'#ffffcc',
			font:		'11px Tahoma',
			color:		'#333',
			padding:	'5px',
			maxWidth:	'200px',
			opacity:	'0.90',
			zIndex:		'999999'
		})
		.mouseover(function(){$.hideTip()})
		.appendTo('body')
	;
	
	$.initTips();
});