/*
 * MEL.Sound
 * 
 * Version:	1.2.2
 * Date:	2008-02-22
 * URL:		http://melnaron.net/c/projects/melsound
 * Author:	Melnaron
 */

var soundEnable = 1;

$(document).ready(function() {
	$(document.body).append('<div id="soundContainer"></div>');
	$(document.body).append('<div id="soundOff"></div>');
	$(document.body).append('<div id="soundOn"></div>');
	if (!$.browser.msie) {
		$('#soundOff').css('position', 'fixed');
		$('#soundOn').css('position', 'fixed');
	}
});

function playSound(path) {
	var sound = path+'.swf';
	if (soundEnable) {
		if ($.browser.msie) {
			document.getElementById('soundContainer').innerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0"><param name="movie" value="'+sound+'"><param name="quality" value="high"></object>';
		} else {
			$('#soundContainer').html('<embed src="'+sound+'" width="1" height="1" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"></embed>');
		}
	}
}

function enableSound() {
	if (soundEnable == 0) {
		soundEnable = 1;
		$('#soundOff').stop().hide();
		$('#soundOn').stop().css('opacity', 1).fadeIn(250).fadeOut(2000);
	}
}

function disableSound() {
	if (soundEnable == 1) {
		soundEnable = 0;
		$('#soundOn').stop().hide();
		$('#soundOff').stop().css('opacity', 1).fadeIn(250).fadeOut(2000);
	}
}

function toggleSound() {
	if (soundEnable) {
		disableSound();
	} else {
		enableSound();
	}
}