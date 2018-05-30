/*==================================================*\

	MEL.Sound
	
	Author:		Melnaron
	URL:			http://melnaron.net/c/projects/melsound
	Version:		1.1
	Date:			2007/06/21

\*==================================================*/

var soundContainer = 'soundContainer'; // идентификатор div-блока для звуковых роликов

var soundEnable = 1; // по умолчанию: звуки включены - да(1) / нет(0)

function playSound(path) {
	//var o = null;
	var url = path+'.swf';
	var html = ['<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0"><param name="movie" value="'+url+'"><param name="quality" value="high"></object>', '<embed src="'+url+'" width="1" height="1" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"></embed>'];
	var sC = document.getElementById(soundContainer);
	if (soundEnable) {
		if (navigator.appName.toLowerCase().indexOf('microsoft')+1) {
			sC.innerHTML = html[0];
			//o = sC.getElementsByTagName('object')[0];
		}
		else {
			sC.innerHTML = html[1];
			//o = sC.getElementsByTagName('embed')[0];
		}
	}
	//return o;
}

function enableSound() {
	soundEnable = 1;
	alert('Звуки включены!');
}

function disableSound() {
	soundEnable = 0;
	alert('Звуки отключены!');
}

function toggleSound() {
	if (soundEnable) {
		disableSound();
	}
	else {
		enableSound();
	}
}

function initSound() {
	var sC = document.createElement('div');
	sC.id = soundContainer;
	with (sC.style) {
		position = 'absolute';
		left = '-256px';
		width = '1px';
		height = '1px';
		overflow = 'hidden';
	}
	document.getElementsByTagName('body')[0].appendChild(sC);
}

function loadSound(obj, type, fn)
{
	if (obj.addEventListener) {
		obj.addEventListener(type, fn, false);
	}
	else if (obj.attachEvent) {
		obj["e"+type+fn] = fn;
		obj[type+fn] = function() { obj["e"+type+fn](window.event); }
		obj.attachEvent("on"+type, obj[type+fn]);
	}
	else {
		obj["on"+type] = obj["e"+type+fn];
	}
}

loadSound(window, 'load', initSound);