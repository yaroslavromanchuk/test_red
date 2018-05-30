/**
 * Popup plugin for jQuery
 * 
 * @author Melnaron
 * @version 1.0.1
 */

jQuery.fn.popup = function(options) {
	var options = options || {};
	
	var offX = options.x || 0;
	var offY = options.y || 0;
	var incZ = options.z || false;
	
	$.popupZ = $.popupZ ? ++$.popupZ : 1000;
	
	if (incZ) {
		$(this).css({zIndex: $.popupZ});
	} else {
		var brdW = parseInt($(this).css('borderLeftWidth')) + parseInt($(this).css('borderRightWidth'));
		var brdH = parseInt($(this).css('borderTopWidth')) + parseInt($(this).css('borderBottomWidth'));
		var padW = parseInt($(this).css('paddingLeft')) + parseInt($(this).css('paddingRight'));
		var padH = parseInt($(this).css('paddingTop')) + parseInt($(this).css('paddingBottom'));
		var winW = $(this).width() + brdW + padW;
		var winH = $(this).height() + brdH + padH;
		var marL = ((winW / 2) * (-1) + (offX || 0)) + 'px';
		var marT = ((winH / 2) * (-1) + (offY || 0)) + 'px';
		var wW = $(window).width();
		var wH = $(window).height();
		var lP = 50 - ((winW / 2) / (wW / 100));
		var tP = 50 - ((winH / 2) / (wH / 100));
		$(this).css({position: 'fixed', left: lP+'%', top: tP+'%', zIndex: $.popupZ}).show();
	}
	
	return this;
};