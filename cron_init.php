<?php
	//error_reporting(E_ALL ^ E_NOTICE);
	//ini_set('display_errors',1);
	set_time_limit(600);
	date_default_timezone_set('Europe/Kiev');
	$locale = "ru_RU"; //for future - get it from browser preferences - in header
	setlocale(LC_ALL, $locale);
	setlocale(LC_NUMERIC,'en_US'); //dot - for MySQL
	//setlocale(LC_MESSAGES, 'en_US');
	mb_internal_encoding("UTF-8");
	
require_once('site_config.php');
require_once('functions.php'); //move to separate class
require_once('Zend/Loader.php');
require_once('Orm/ormLoader.php');
Zend_Loader::registerAutoload();

   // spl_autoload_register(array('Zend_Loader', 'autoload')); 

   // $timer = new DebugTimer(4);
    //Registry::set('timer', $timer);

    $cache = new Cache();
	$cache->setEnabled(true);
    Registry::set('cache', $cache);
	$db_config = array(
                'adapter' => 'PDO_MYSQL',
                'config' => array(
                    'host' => $sql_host,
                    'username' => $sql_user,
                    'password' => $sql_passwd,
                    'dbname' => $sql_database
                    )
                );
    $db = Zend_Db::factory($db_config['adapter'], $db_config['config']);
    $db->query("SET NAMES utf8");
    Registry::set('dbpdo',$db);
 	if(!PDO) {
		$db = @mysql_pconnect($sql_host, $sql_user, $sql_passwd, true) or die('Error connecting to DB');
		@mysql_select_db($sql_database,$db) or die('Error selecting DB');
		@mysql_query("SET NAMES utf8", $db);
 	}

    Registry::set('db',$db);
	Registry::set('site_id', Website::getSite()->getId());
	Registry::loadDBConfig(); //loads automatically
	
		
function sendMessageTelegram($chat_id, $message) {
    return false;
}
	?>