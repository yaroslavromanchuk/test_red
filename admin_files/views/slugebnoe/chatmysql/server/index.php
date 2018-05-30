<?php

if (@$_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
	exit;
}

//require('lib/mel_mysql.php');
require('lib/FirePHPCore/fb.php');
require('lib/Timer.php');

require('cfg.php');
require('class.chat.php');
require('class.user.php');
require('class.util.php');

// if (db::connect(cfg::$mysql_host, cfg::$mysql_user, cfg::$mysql_pass, cfg::$mysql_base) == false) {
	// chat::retAction('Connect', 'success: false, error: 6007', true);
// }

chat::callAction();

?>