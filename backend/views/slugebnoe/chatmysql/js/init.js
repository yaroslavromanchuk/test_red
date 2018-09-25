/**
 * @author Melnaron
 */

$(document).ready(function() {
	
	// load js engine
	$('head').append('<script type="text/javascript" src="js/langs/'+($.cookie('language') || 'ru')+'.js"></script>');
	$('head').append('<script type="text/javascript" src="js/actions.js"></script>');
	$('head').append('<script type="text/javascript" src="js/chat.js"></script>');
	$('head').append('<script type="text/javascript" src="js/smiles.js"></script>');
	$('head').append('<script type="text/javascript" src="js/smiles/'+chat.smiles+'.js"></script>');
	$('head').append('<script type="text/javascript" src="js/user.js"></script>');
	
	$(window).unload(chat.disconnect);
	
	$.styleTip({border: '0', background: '#333333', color: '#ffffff'});
	
	$.ajaxSetup({url: 'server/', type: 'post', dataType: 'json'});
	
	// fill all elements with lang attribute
	$('*[lang]').each(function() {
		$(this).text(lang[$(this).attr('lang')] || '...');
	});
	
	// buttons highlight
	$('button.btn').hover(function() { $(this).toggleClass('btn-hl'); }, function() { $(this).toggleClass('btn-hl'); });
	
	// login buttons events and tooltips
	$('#btnPassword').click(function() { $(this).next().show().find('input').focus(); }).tip(lang[1208]);
	$('#btnConnect').click(chat.connect);
	chat.connect;
	$('#btnLangRu').click(function() { chat.selectLang('ru'); }).tip(lang[2201]);
	$('#btnLangEn').click(function() { chat.selectLang('en'); }).tip(lang[2202]);
	
	// main buttons events and tooltips
	$('#btnSendMessage').click(chat.sendMessage).tip(lang[2001]);
	$('#btnClearMessage').click(chat.clearMessage).tip(lang[2002]);
	$('#btnSmiles').click(chat.toggleSmiles).tip(lang[2003]);
	$('#btnSound').click(chat.toggleSound).tip(lang[2004]);
	$('#btnClear').click(chat.clear).tip(lang[2005]);
	$('#btnAbout').click(chat.about).tip(lang[2006]);
	$('#btnDisconnect').click(chat.disconnect).tip(lang[2007]);
	
	// smiles button events
	$('#btnSmiles, #smiles')
		.hover(
			function() { clearTimeout(chat.smilesHideTimer); },
			function() { chat.smilesHideTimer = setTimeout(function() { chat.hideSmiles(); }, 3000); }
		)
	;
	
	// input message hotkeys
	$('#inpMessage')
		.keydown(function(e){
			// return
			if (e.keyCode == 13) {
				chat.sendMessage();
				return false;
			}
			// tab
			if (e.keyCode == 9) {
				chat.whisperTo();
				return false;
			}
			//chat.print('keydown: '+e.keyCode);
		})
		
		.keypress(function(e){
			// return
			if (e.keyCode == 13) {
				return false;
			}
			// tab
			if (e.keyCode == 9) {
				return false;
			}
			//chat.print('keypress: '+e.keyCode);
		})
		
		.keyup(function(e){
			// return
			if (e.keyCode == 13) {
				return false;
			}
			// tab
			if (e.keyCode == 9) {
				return false;
			}
			//chat.print('keyup: '+e.keyCode);
		})
	;
	
	// return to input hotkey
	$(document).keydown(function(e) {
		if (e.keyCode == 13) {
			$('#inpMessage').focus();
			return false;
		}
	});
	
	// statuses controls
	$('#self').click(function() {
		chat.toggleStatuses();
	});
	
	$('#statuses div')
		.click(function() {
			user.setStatus($(this).attr('status'));
		})
		.hover(
			function() { $(this).css('background-color', '#ffb91e'); },
			function() { $(this).css('background-color', 'transparent'); }
		)
	;
	
	$('#self, #statuses')
		.hover(
			function() { clearTimeout(chat.statusesHideTimer); },
			function() { chat.statusesHideTimer = setTimeout(function() { chat.hideStatuses(); }, 3000); }
		)
	;
	
	// browser check & show login frame
	if ($.browser.msie && $.browser.version == '6.0') {
		if(confirm(lang[1010])) {
			document.location = 'http://getfirefox.com/';
		}
	} else {
		$('#frameLogin').popup().find('#inpNickname').val($.cookie('nickname') || '').focusto('end');
		if ($.cookie('password')) {
			$('#btnPassword').click();
		}
	}
	
});