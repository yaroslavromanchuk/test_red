/*
Sweet Titles (c) Creative Commons 2005
http://creativecommons.org/licenses/by-sa/2.5/
Author: Dustin Diaz | http://www.dustindiaz.com
Edited: Melnaron | http://mel.hazard-games.com
*/

Array.prototype.inArray = function (value) {
	var i;
	for (i=0; i < this.length; i++) {
		if (this[i] === value) {
			return true;
		}
	}
	return false;
};

function addEvent( obj, type, fn ) {
	if (obj.addEventListener) {
		obj.addEventListener( type, fn, false );
		EventCache.add(obj, type, fn);
	}
	else if (obj.attachEvent) {
		obj["e"+type+fn] = fn;
		obj[type+fn] = function() { obj["e"+type+fn]( window.event ); }
		obj.attachEvent( "on"+type, obj[type+fn] );
		EventCache.add(obj, type, fn);
	}
	else {
		obj["on"+type] = obj["e"+type+fn];
	}
}
	
var EventCache = function(){
	var listEvents = [];
	return {
		listEvents : listEvents,
		add : function(node, sEventName, fHandler){
			listEvents.push(arguments);
		},
		flush : function(){
			var i, item;
			for(i = listEvents.length - 1; i >= 0; i = i - 1){
				item = listEvents[i];
				if(item[0].removeEventListener){
					item[0].removeEventListener(item[1], item[2], item[3]);
				};
				if(item[1].substring(0, 2) != "on"){
					item[1] = "on" + item[1];
				};
				if(item[0].detachEvent){
					item[0].detachEvent(item[1], item[2]);
				};
				item[0][item[1]] = null;
			};
		}
	};
}();
addEvent(window,'unload',EventCache.flush);

var sweetTitles = { 
	xCord : 0,								// @Number: x pixel value of current cursor position
	yCord : 0,								// @Number: y pixel value of current cursor position
	obj : Object,							// @Element: That of which you're hovering over
	tip : Object,							// @Element: The actual toolTip itself
	active : 0,								// @Number: 0: Not Active || 1: Active
	init : function() {
		if ( !document.getElementById ||
			!document.createElement ||
			!document.getElementsByTagName ) {
			return;
		}
		var i,j;
		this.tip = document.getElementById('tooltip');
		var current = document.all ? document.all : document.getElementsByTagName("*");
		var curLen = current.length;
		for ( j=0; j<curLen; j++ ) {
			t_title = current[j].getAttribute("title");
			if (t_title) {
				addEvent(current[j],'mouseover',this.tipOver);
				addEvent(current[j],'mouseout',this.tipOut);
				addEvent(current[j],'click',this.tipOut);
				current[j].setAttribute('tip',current[j].title);
				current[j].removeAttribute('title');
			}
		}
	},
	updateXY : function(e) {
		if ( document.captureEvents ) {
			sweetTitles.xCord = e.pageX;
			sweetTitles.yCord = e.pageY;
		} else if ( window.event.clientX ) {
			sweetTitles.xCord = window.event.clientX+document.documentElement.scrollLeft;
			sweetTitles.yCord = window.event.clientY+document.documentElement.scrollTop;
		}
	},
	tipOut: function() {
		if ( window.tID ) {
			clearTimeout(tID);
		}
		if ( window.opacityID ) {
			clearTimeout(opacityID);
		}
		sweetTitles.tip.style.visibility = 'hidden';
	},
	tipOver : function(e) {
		sweetTitles.obj = this;
		tID = window.setTimeout("sweetTitles.tipShow()",0);
		sweetTitles.updateXY(e);
	},
	tipShow : function() {		
		var scrX = Number(this.xCord);
		var scrY = Number(this.yCord);
		var tp = parseInt(scrY+20);
		var lt = parseInt(scrX+20);
		var anch = this.obj;
		var addy = '';
		var access = '';
		if (anch.getAttribute('tip').length > 0) {
			this.tip.innerHTML = anch.getAttribute('tip');
			if ( parseInt(document.documentElement.clientWidth+document.documentElement.scrollLeft) < parseInt(this.tip.offsetWidth+lt) ) {
				this.tip.style.left = parseInt(lt-(this.tip.offsetWidth+20))+'px';
			} else {
				this.tip.style.left = lt+'px';
			}
			if ( parseInt(document.documentElement.clientHeight+document.documentElement.scrollTop) < parseInt(this.tip.offsetHeight+tp) ) {
				this.tip.style.top = parseInt(tp-(this.tip.offsetHeight+20))+'px';
			} else {
				this.tip.style.top = tp+'px';
			}
			this.tip.style.visibility = 'visible';
			this.tip.style.opacity = '100';
			this.tip.style.filter = "alpha(opacity:100)";
			//this.tipFade(10);
		}
	},
	tipFade: function(opac) {
		var passed = parseInt(opac);
		var newOpac = parseInt(passed+10);
		if ( newOpac < 80 ) {
			this.tip.style.opacity = '.'+newOpac;
			this.tip.style.filter = "alpha(opacity:"+newOpac+")";
			opacityID = window.setTimeout("sweetTitles.tipFade('"+newOpac+"')",20);
		}
		else { 
			this.tip.style.opacity = '.80';
			this.tip.style.filter = "alpha(opacity:80)";
		}
	}
};
function titles_init() {
	sweetTitles.init();
}
addEvent(window, 'load', titles_init);
