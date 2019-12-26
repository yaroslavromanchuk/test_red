<?php

header('content-type: text/html; charset=utf-8');

class cfg
{
	
	public static
		// mysql settings
		$mysql_host 		= 'localhost',
		$mysql_user 		= 'root',
		$mysql_pass 		= '',
		$mysql_base 		= 'chat2',
		
		// unique hash for pass & password keys, PLEASE CHANGE IT IN ANYWAY!
		$unique_hash 		= '12341234',
		
		// can guests enter in the chat?
		$guests_allowed 	= true
	;
	
}

define('TB_ACTIONS', 	'melchat_actions');
define('TB_BANS', 		'melchat_bans');
define('TB_MESSAGES', 	'melchat_messages');
define('TB_SETTINGS', 	'melchat_settings');
define('TB_USERS', 		'melchat_users');

class db extends Mel_Mysql
{}

?>