/**
 * @author Melnaron
 */

var actions = {
	
	Connect: function(params) {
		if (! params.success) {
			chat.error(lang[6001]+' '+lang[params.error]);
			return false;
		}
		
		user.id = params.id;
		user.pass = params.pass;
		user.setNickname(params.nickname);
		user.setAccess(params.access);
		user.setStatus(params.status);
		user.conn = true;
		
		$('#users').empty();
		
		if (params.users) {
			for (var i = 0; i < params.users.length; i++) {
				actions.AddUser(params.users[i]);
			}
		}
		
		chat.printSystemMessage(chat.wellcome);
		
		if (params.motd) {
			chat.printSystemMessage(chat.fillMessage({message: 5001, inserts: [params.motd]}));
		}
		
		chat.checkActions();
		
		smiles.fill();
		
		$('#frameLogin').hide();
		$('#frameMain').popup();
		$('#inpMessage').focus();
		
		document.title = user.nickname+' - MEL.Chat';
	},
	
	Disconnect: function(params) {
		if (params.message) {
			chat.error(lang[params.message]);
		}
		
		if (user.sock) {
			user.sock.abort();
		}
		
		user.sock = null;
		user.conn = false;
		
		$('#frameMain').hide();
		$('#frameLogin').popup();
		
		document.title = 'MEL.Chat';
	},
	
	PrintMessage: function(params) {
		chat.printMessage(params.time, params.from, params.message);
	},
	
	PrintWhisper: function(params) {
		chat.printWhisper(params.time, params.from, params.to, params.message);
	},
	
	PrintSystemMessage: function(params) {
		chat.printSystemMessage(chat.fillMessage(params));
	},
	
	PrintErrorMessage: function(params) {
		chat.printErrorMessage(chat.fillMessage(params));
	},
	
	PrintEchoMessage: function(params) {
		chat.printEchoMessage(params.message);
	},
	
	ChangeNickname: function(params) {
		user.setNickname(params.nickname);
	},
	
	ChangeAccess: function(params) {
		user.setAccess(params.access);
	},
	
	AddUser: function(params) {
		if (! $('#users #user_'+params.id).text()) {
			$('<div class="user l'+params.access+'" id="user_'+params.id+'"></div>')
				.append('<span class="icon_status_'+params.status+'">'+params.nickname+'</span>')
				.click(function() {
					chat.whisperTo($('span', this).text());
				})
				.hover(
					function() {$(this).css('background-color', '#ffb91e');},
					function() {$(this).css('background-color', 'transparent');}
				)
				.appendTo('#users')
			;
		}
	},
	
	UpdateUser: function(params) {
		if (params.id) {
			if (params.nickname != null) {
				$('#users #user_'+params.id+' span').text(params.nickname);
			}
			if (params.access != null) {
				$('#users #user_'+params.id).attr('class', 'user l'+params.access);
			}
			if (params.status != null) {
				$('#users #user_'+params.id+' span').attr('class', 'icon_status_'+params.status);
			}
		}
	},
	
	RemoveUser: function(params) {
		$('#users #user_'+params.id).remove();
	},
	
	ShowHelp: function(params) {
		if (typeof params.access == 'number') {
			switch (params.access) {
				case 0: chat.window(lang[4013], lang[1050]); break;
				case 1: chat.window(lang[4013], lang[1050]+lang[1051]); break;
				case 2: chat.window(lang[4013], lang[1050]+lang[1051]+lang[1052]); break;
				case 3: chat.window(lang[4013], lang[1050]+lang[1051]+lang[1052]+lang[1053]); break;
			}
		}
	},
	
	ShowMessages: function(params) {
		if (params.messages) {
			var count = params.messages.length;
			var html = '<p class="t">'+lang[1203]+': '+count+'</p>';
			for (var i = 0; i < count; i++) {
				var m = params.messages[i];
				html += '<div>['+m.time+'] <strong>'+m.from+((m.to) ? ' to '+m.to : '')+'</strong>: '+m.message+'</div>';
			}
			chat.window(lang[4014], html);
		}
	},
	
	ShowUsers: function(params) {
		if (params.users) {
			var count = params.users.length;
			var html = '<p class="t">'+lang[1204]+': '+count+'</p>';
			html += '<table width="100%" cellpadding="0" cellspacing="0">'+$('#tplShowUsers table').html();
			for (var i = 0; i < count; i++) {
				var u = params.users[i];
				html += 
					'<tr><td>'+u.nickname+'</td><td>'+u.access+'</td><td>'+u.ip+'</td>'+
					'<td>'+u.agent+'</td><td>'+u.conntime+'</td><td>'+u.regtime+'</td></tr>'
				;
			}
			html += '</table>';
			chat.window(lang[4015], html);
		}
	},
	
	ShowBans: function(params) {
		if (params.bans) {
			var count = params.bans.nns.length + params.bans.ips.length;
			var html = '<p class="t">'+lang[1205]+': '+count+'</p>';
			html += '<table width="100%" cellpadding="0" cellspacing="0">'+$('#tplShowBans table').html();
			html += '<tr><td valign="top">';
			for (var i = 0; i < params.bans.nns.length; i++) {
				html += '<div>'+params.bans.nns[i]+'</div>';
			}
			html += '</td><td valign="top">';
			for (var i = 0; i < params.bans.ips.length; i++) {
				html += '<div>'+params.bans.ips[i]+'</div>';
			}
			html += '</td></tr></table>';
			chat.window(lang[4016], html);
		}
	}
	
};