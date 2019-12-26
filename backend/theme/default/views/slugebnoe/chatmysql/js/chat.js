/**
 * @author Melnaron
 */

var chat = {
	
	version: '2.0b3',
	
	wellcome: lang[1000],
	
	statuses: [lang[3000], lang[3001], lang[3002], lang[3003]],
	
	statusesHideTimer: null,
	
	smiles: 'yami',
	
	smilesHideTimer: null,
	
	sound: true,
	
	whisperLast: null,
	
	pollTimer: null,
	
	about: function() {
		chat.window(lang[4011], $('#tplAbout').html().replace(/%VERSION%/i, chat.version), 300, true);
	},
	
	toggleStatuses: function() {
		$('#statuses').slideToggle();
		$('#status').toggle();
		$('#status_label').toggle();
	},
	
	hideStatuses: function() {
		$('#statuses').slideUp();
		$('#status').show();
		$('#status_label').hide();
	},
	
	toggleSmiles: function() {
		$('#smiles').slideToggle();
	},
	
	hideSmiles: function() {
		$('#smiles').slideUp();
	},
	
	toggleSound: function() {
		if (chat.sound) {
			disableSound();
			$('#btnSound').attr('class', 'ctrl ctrl_sound0');
			chat.sound = false;
		} else {
			enableSound();
			$('#btnSound').attr('class', 'ctrl ctrl_sound1');
			chat.sound = true;
		}
	},
	
	selectLang: function(lang) {
		$.cookie('language', lang, {expires: 7});
		document.location = document.URL;
	},
	
	clear: function() {
		$('#log').empty();
	},
	
	clearMessage: function() {
		$('#inpMessage').val('').focus();
	},
	
	window: function(title, html, width, height, modal) {
		new Ext.Window({
			title: title || lang[4010],
			html: html,
			width: width || 600,
			height: height || 400,
			modal: modal,
			autoScroll: true,
			resizable: false,
			plain: false,
			bodyStyle: 'background: #ffffff; font-size: 10pt; padding: 10px;'
		}).show();
	},
	
	alert: function(title, msg, icon) {
		switch (icon) {
			case 'i': icon = Ext.MessageBox.INFO; break;
			case 'q': icon = Ext.MessageBox.QUESTION; break;
			case 'w': icon = Ext.MessageBox.WARNING; break;
			case 'e': icon = Ext.MessageBox.ERROR; break;
		}
		Ext.MessageBox.show({
			title: title,
			msg: msg,
			icon: icon,
			buttons: Ext.MessageBox.OK
		});
	},
	
	error: function(msg) {
		chat.alert(lang[4012], msg, 'e');
		playSound('lib/sounds/fatal');
	},
	
	print: function(msg) {
		$('#log').append('<div>'+msg+'</div>').get(0).scrollTop = 99999;
	},
	
	printMessage: function(time, from, msg) {
		msg = smiles.replace(msg);
		chat.print(
			'['+time+'] <strong class="'+((from == user.nickname) ? 'nm_s' : 'nm_o')+
			'" onclick="chat.whisperTo(\''+from+'\')">'+from+'</strong>: '+
			'<span class="msg_m">'+msg+'</span>'
		);
		if (from != user.nickname) {
			playSound('lib/sounds/message');
		}
	},
	
	printWhisper: function(time, from, to, msg) {
		msg = smiles.replace(msg);
		chat.print(
			'['+time+'] <strong class="'+((from == user.nickname) ? 'nm_sw' : 'nm_ow')+
			'" onclick="chat.whisperTo(\''+((from == user.nickname) ? to : from)+'\')">'+from+
			' to '+to+'</strong>: '+
			'<span class="msg_w">'+msg+'</span>'
		);
		if (from != user.nickname) {
			playSound('lib/sounds/whisper');
		}
	},
	
	printSystemMessage: function(msg) {
		chat.print('['+chat.getTime()+'] <span class="msg_s">'+msg+'</span>');
		playSound('lib/sounds/system');
	},
	
	printErrorMessage: function(msg) {
		chat.print('['+chat.getTime()+'] <span class="msg_e">'+msg+'</span>');
		playSound('lib/sounds/error');
	},
	
	printEchoMessage: function(msg) {
		chat.print('['+chat.getTime()+'] <span class="msg_h">'+msg+'</span>');
		playSound('lib/sounds/echo');
	},
	
	fillMessage: function(params) {
		var msg = params.message;
		var ins = params.inserts || null;
		if (typeof msg == 'number') {
			msg = lang[msg];
		}
		if (ins) {
			for (var i = 0; i < ins.length; i++) {
				msg = msg.replace(new RegExp('%'+i, 'g'), ins[i]);
			}
		}
		return msg;
	},
	
	// - not use
	//replaceUrl: function(string) {
	//	return string.replace(/((https?|ftp):\/\/\S+)/ig, '<a href="$1" target="_blank">$1</a>');
	//},
	
	// - not use
	//replaceHtml: function(string) {
	//	return string.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	//},
	
	getTime: function() {
		var date = new Date();
		var h = date.getHours();
		var m = date.getMinutes();
		var s = date.getSeconds();
		if (h < 10) h = '0'+h;
		if (m < 10) m = '0'+m;
		if (s < 10) s = '0'+s;
		return h+':'+m+':'+s;
	},
	
	whisperTo: function(name) {
		if (! name && ! chat.whisperLast) {
			return false;
		}
		if (name) {
			chat.whisperLast = name;
		}
		$('#inpMessage').val('/whisper '+chat.whisperLast+' ').focusto('end');
	},
	
	callAction: function(action, params) {
		if (typeof actions[action] == 'function') {
			actions[action](params);
		}
	},
	
	onSuccess: function(data) {
		if (data.actions) {
			for (var i = 0; i < data.actions.length; i++) {
				chat.callAction(data.actions[i].action, data.actions[i].params);
			}
		}
	},
	
	onComplete: function(xhr, more) {
		if (xhr.status == 404) {
			if (user.conn) {
				actions.Disconnect();
			}
			chat.error(lang[6001]+' '+lang[6002]);
		}
		
		if (more == 1) {
			$('#frameLogin p:eq(2)').show();
			$('#frameLogin p:eq(3)').hide();
			return;
		}
		
		if (more == 2) {
			if (user.conn) {
				chat.pollTimer = setTimeout(chat.checkActions, (xhr.status == 200) ? 2000 : 8000);
			}
			return;
		}
	},
	
	// ACTIONS
	
	connect: function() {
		var nickname = $('#inpNickname').val();
		var password = $('#inpPassword').val();
		
		if (! nickname) {
			$('#inpNickname').focusto('end');
			chat.error(lang[6003]);
			return false;
		}
		
		if (user.conn) {
			chat.error(lang[6004]);
			return false;
		}
		
		if (password) {
			$('#inpPassword').val('');
			$.cookie('password', 1, {expires: 7});
		} else {
			$.cookie('password', null);
		}
		
		$('#frameLogin p:eq(2)').hide();
		$('#frameLogin p:eq(3)').show();
		
		$.ajax({
			data: 'action=Connect&pass='+user.pass+'&nickname='+nickname+'&password='+password,
			success: chat.onSuccess,
			complete: function(xhr) { chat.onComplete(xhr, 1); }
		});
	},
	
	disconnect: function() {
		if (user.conn) {
			$.ajax({
				data: 'action=Disconnect&pass='+user.pass,
				success: chat.onSuccess,
				complete: function(xhr) { chat.onComplete(xhr); }
			});
		}
	},
	
	sendMessage: function() {
		if (user.conn) {
			var message = $.trim($('#inpMessage').val());
			
			if (message) {
				message = message
						  .replace(/\+/g, '%2B').replace(/\?/g, '%3F').replace(/\&/g, '%26')
						  .replace(/\`/g, '%60').replace(/\\/g, '%5C').replace(/\\/g, '%5C');
				
				$.ajax({
					data: 'action=SendMessage&pass='+user.pass+'&message='+message,
					success: chat.onSuccess,
					complete: function(xhr) { chat.onComplete(xhr); }
				});
			}
			
			chat.clearMessage();
		}
	},
	
	checkActions: function() {
		if (user.conn) {
			user.sock = $.ajax({
				data: 'action=CheckActions&pass='+user.pass,
				success: chat.onSuccess,
				complete: function(xhr) { chat.onComplete(xhr, 2); }
			});
		}
	}
	
};