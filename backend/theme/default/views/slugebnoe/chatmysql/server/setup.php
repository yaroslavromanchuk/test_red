<?php

require('lib/mel_mysql.php');

require('cfg.php');

echo 'Connecting to MySQL database...<br>';
if (db::connect(cfg::$mysql_host, cfg::$mysql_user, cfg::$mysql_pass, cfg::$mysql_base, true)) {
	echo 'Connecting to MySQL database completed!<br>';
	echo 'Setup tables...<br>';
	if (($es = db::import('setup.sql')) === true) {
		echo 'Setup tables completed!<br>';
		echo 'For security reason, please remove both setup.php and setup.sql files!<br>';
	} else {
		echo 'Setup tables failed!<br>';
		foreach ($es as $e) {
			echo $e . '<br>';
		}
	}
} else {
	echo 'Connecting to MySQL database failed!<br>';
}

?>