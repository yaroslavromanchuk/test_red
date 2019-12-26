/**
 * @author Melnaron
 */

// english (en)

var lang = {
	
	// welcome message
	1000: 'Welcome to MEL.Chat! Use /help command to view all commands available for you.',
	
	// browser check message
	1010: 
		'Your browser is incompatible for this web-application.\n\n'+
		'Please install compatible browser such as world\'s best browser - Firefox.\n\n'+
		'Do you want to open Firefox download page?',
	
	// help (0) guest
	1050: 
		'<p class="t">You can use next commands:</p>'+
		'<p><strong>/help</strong> - to view help dialog.</p>'+
		'<p><strong>/motd</strong> - to view message of the day.</p>'+
		'<p><strong>/nick (nickname)</strong> - to set up your nickname.</p>'+
		'<p><strong>/whisper (nickname) (message)</strong> - to send private message.</p>'+
		'<p><strong>/reg (password)</strong> - to register your nickname.</p>',
	
	// help (1) user
	1051: 
		'<p><strong>/password (password) (new password)</strong> - to change the password.</p>'+
		'<p><strong>/unreg (password)</strong> - to unregister your nickname.</p>',
	
	// help (2) operator
	1052: 
		'<p><strong>/motd (message)</strong> - to set up message of the day.</p>'+
		'<p><strong>/motd clear</strong> - to clear message of the day.</p>'+
		'<p><strong>/echo (message)</strong> - to post global message.</p>'+
		'<p><strong>/messages</strong> - to show messages log.</p>'+
		'<p><strong>/users</strong> - to show users.</p>'+
		'<p><strong>/bans</strong> - to show bans.</p>'+
		'<p><strong>/kick (nickname)</strong> - to kick user from the chat.</p>'+
		'<p><strong>/ban (nickname)</strong> - to ban user by nickname.</p>'+
		'<p><strong>/unban (nickname)</strong> - to remove user from ban by nickname.</p>'+
		'<p><strong>/banip (ip address)</strong> - to ban user by ip address.</p>'+
		'<p><strong>/unbanip (ip address)</strong> - to remove user from ban by ip address.</p>'+
		'<p><strong>/silence (nickname) (minutes)</strong> - to silence user for a specified number of minutes.</p>',
	
	// help (3) administrator
	1053: 
		'<p><strong>/messages clear</strong> - to clear messages log.</p>'+
		'<p><strong>/op (nickname)</strong> - to grant operator\'s access.</p>'+
		'<p><strong>/unop (nickname)</strong> - to revoke operator\'s access.</p>',
	
	// user fields
	1101: 'ID',
	1102: 'Nickname',
	1103: 'Password',
	1104: 'Access',
	1105: 'Status',
	1106: 'IP',
	1107: 'Agent',
	1108: 'Conn. Time',
	1109: 'Reg. Time',
	1110: 'Avatar',
	1111: 'Email',
	1112: 'Reputation',
	1113: 'Title',
	
	// common words
	1201: 'Copyright',
	1202: 'Version',
	1203: 'Messages', 	// Messages: 3
	1204: 'Users', 		// Users: 3
	1205: 'Bans', 		// Bans: 3
	1206: 'Nicknames',
	1207: 'IPs',
	1208: 'With password',
	1209: 'Connect',
	1210: 'Connecting...',
	1211: 'Select status',
	
	// main buttons tooltips
	2001: 'Send a message',
	2002: 'Clear a message',
	2003: 'Toggle a smiles',
	2004: 'Toggle a sound',
	2005: 'Clear a messages log',
	2006: 'About the chat',
	2007: 'Disconnect from the chat',
	
	// language tooltips
	2201: 'Russian',
	2202: 'English',
	
	// statuses
	3000: 'Online',
	3001: 'Away',
	3002: 'Busy',
	3003: 'Invisible',
	
	// windows headers
	4010: 'Untitled Window',
	4011: 'About',
	4012: 'Error',
	4013: 'Help',
	4014: 'Messages',
	4015: 'Users',
	4016: 'Bans',
	
	// system messages
	5001: 'Message of the Day: %0',
	5002: 'Message of the Day has not set yet.',
	5003: 'Message of the Day has been successfully clear.',
	5004: 'Message of the Day has been successfully set.',
	5011: '%0 has connected to the chat.',
	5012: '%0 has disconnected from the chat.',
	5013: '%0 has been disconnected from the chat by timeout.',
	5014: '%0 has been kicked from the chat.',
	5015: '%0 now known as %1.',
	5016: 'Now your nickname is %0.',
	5017: 'You has been registered as administrator.',
	5018: 'You has been registered.',
	5019: 'Your password has been changed.',
	5020: 'You has been successfully unregistered.',
	5021: 'Messages log has been successfully cleared.',
	5022: '%0 will be kicked in a moment.',
	5023: 'The nickname (%0) has been added to the ban.',
	5024: 'The nickname (%0) has been removed from the ban.',
	5025: 'The ip (%0) has been added to the ban.',
	5026: 'The ip (%0) has been removed from the ban.',
	5027: '%0 has been silenced for %1 minute(s).',
	5028: 'You has been promoted to the operator.',
	5029: '%0 has been promoted to the operator.',
	5030: 'You has been demoted to the user.',
	5031: '%0 has been demoted to the user.',
	
	// error messages
	6001: 'Connection failed.',
	6002: 'Server not found (404).',
	6003: 'You must enter a nickname.',
	6004: 'You are already connected.',
	6005: 'Guests are not allowed enter in the chat.',
	6006: 'Can\'t create a database.',
	6007: 'Can\'t connect to a database.',
	6011: 'This nickname is incorrect. You can use only this characters: A-z А-я . - _',
	6012: 'This nickname is incorrect. It can be from 2 to 16 characters long.',
	6013: 'You are can\'t enter in the chat while your nickname or ip-address are banned.',
	6014: 'Wrong password!',
	6015: 'This nickname is already used.',
	6016: 'Database error!',
	6017: 'You not have access for this command.',
	6018: 'You are kicked from the chat.',
	6019: 'You are can\'t send anything to the chat while you are silenced. Remain minutes: %0.',
	6020: 'Unknown command.',
	6021: 'You can\'t send whisper to ourselves.',
	6022: 'User with this nickname is not found.',
	6023: 'You must enter a message.',
	6024: 'You are already registered.',
	6025: 'You must enter a password.',
	6026: 'Your password must not contain a spaces.',
	6027: 'You must enter both old and new passwords.',
	6028: 'Old password is wrong.',
	6029: 'Operators or Administrators can\'t be unregistered.',
	6030: 'You must enter your valid password.',
	6031: 'Enter user\'s nickname which you want to kick.',
	6032: 'You must enter correct time in minutes.',
	6033: 'You can\'t silence yourself.',
	6034: 'You can use this command only on users with access level low than your.',
	6035: 'You has been silenced for %0 minute(s).',
	6036: '',
	6037: 'Only user can be promoted to the operator.',
	6038: 'Only operator can be demoted to the user.',
	6039: 'You can\'t add this nickname to the ban.',
	6040: 'You can\'t add this ip to the ban.',
	
	9999: ''
	
};