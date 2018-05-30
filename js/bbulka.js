(function($){$.fn.extend({bbulka:function(config){return this.each(function(){new $.bbulka(this,config);});}});$.bbulka=function(el,config){this.el=$(el);this.options=$.extend(this.defaults,config||{});this.init();}
$.bbulka.prototype.extend=$.extend;$.bbulka.prototype.extend({defaults:{timeHide:80,tpl:'<div class="bbulka-box">'+'<div class="bulka-top-left"></div>'+'<div class="bulka-top-right"></div>'+'<div class="bulka-top-center"></div>'+'<div class="bulka-bottom-left"></div>'+'<div class="bulka-bottom-right"></div>'+'<div class="bulka-bottom-center"></div>'+'<div class="bulka-left"></div>'+'<div class="bulka-right"></div>'+'<div class="bulka-center"></div>'+'</div>',heightMin:13,widthMin:50,html:null,htmlurl:null},init:function(){this.html=this.options.html;if(this.html==null&&this.el.next().is('.html'))this.html=this.el.next().html();this.htmlurl=this.options.htmlurl;if(this.htmlurl==null)this.htmlurl=this.el.attr('htmlurl');if(this.html==null&&this.htmlurl==null)return;this.box=null;this.timer=null;var self=this;this.showFunc=function(){self.show();};this.hideFunc=function(){self.hide();};this.hideSlowFunc=function(){self.hideSlow();};this.clearTimerFunc=function(){self.clearTimer();};this.el.bind('mouseover',this.showFunc);this.el.bind('mouseout',this.hideSlowFunc);},show:function(){this.clearTimer();if(this.box!=null)return;this.box=$(this.options.tpl).appendTo('body');this.box.bind('mouseover',this.clearTimerFunc);this.box.bind('mouseout',this.hideSlowFunc);this.content=$('<div/>').appendTo($('div.bulka-center',this.box));if(this.html){this.content.html(this.html);}else if(this.htmlurl){var self=this;this.content.addClass('bulka-loading');$.ajax({type:'GET',url:this.htmlurl,dataType:'html',complete:function(xhr,status){if(status!='success')self.hide();},success:function(data){self.content.removeClass('bulka-loading').html(data).css({height:'auto',width:'auto'});self.calcSizes();}});}this.calcSizes();},clearTimer:function(){window.clearTimeout(this.timer);},hideSlow:function(){this.clearTimer();this.timer=window.setTimeout(this.hideFunc,this.options.timeHide);},hide:function(){this.clearTimer();if(this.box!=null){this.box.remove();this.box.unbind('mouseover').unbind('mouseout');}this.box=null;},calcSizes:function(){var ch=this.content.height();var cw=this.content.width();if(ch<this.options.heightMin){ch=this.options.heightMin;this.content.height(ch);}if(cw<this.options.widthMin){cw=this.options.widthMin;this.content.width(cw);}var h=this.box.height(),hel=this.el.height();var w=this.box.width(),wel=this.el.width();$('div.bulka-top-center',this.box).width(w-22);$('div.bulka-bottom-center',this.box).width(w);$('div.bulka-left',this.box).height(h);$('div.bulka-right',this.box).height(h);h=h+42;w=w+10;var offset=this.el.offset();offset.top+=33;offset.left-=120;if(offset.left>$(window).width()-$(window).scrollLeft()-w){offset.left=$(window).width()+$(window).scrollLeft()-w;}this.box.css(offset);}});})(jQuery);