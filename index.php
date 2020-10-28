
<?php
//define('THROTTLE_SPEED', '10,50,400,600');//requests per second, 10 seconds, 100 seconds, 1k seconds
//require_once('Framework2.0/throttle.php');//v sluchi dos
error_reporting(E_ALL);
ini_set('display_errors',0);
//set_time_limit(600);
date_default_timezone_set('Europe/Kiev');
$locale = "ru_RU"; //for future - get it from browser preferences - in header
setlocale(LC_ALL, $locale);
setlocale(LC_NUMERIC, "en_US");
mb_internal_encoding("UTF-8");

//if(!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '127.0.0.2', '91.225.165.62'))) die('Сайт на техобслуживании, вернитесь позже.');

require_once('site_config.php');
require_once('functions.php'); //move to separate class
require_once('Zend/Loader.php');
require_once('Orm/ormLoader.php');
require_once('MobileDetect/Mobile_Detect.php');
Zend_Loader::registerAutoload();// nujno
//spl_autoload_register(array('Zend_Loader', 'autoload'));

//$timer = new DebugTimer(4);
Registry::set('SQLLogger', SQLLogger::getInstance());

header('Content-type: text/html; charset=UTF-8');
header('Cache-control: private');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: ' . date(DATE_RFC822, strtotime('+10 minutes')));

session_start();

/*счетчик пользвателей*/
/*
$id = session_id();
if ($id!="") {
 //текущее время
 $CurrentTime = time();
 //через какое время сессии удаляются
 $LastTime = time() - 100;
 //файл, в котором храним идентификаторы и время
 $base = "session.txt";
$is_sid_in_file = false;
 $file = file($base);
 $k = 0;
 for ($i = 0; $i < sizeof($file); $i++) {
  $line = explode("|", $file[$i]);
   if ($line[1] > $LastTime) {
   $ResFile[$k] = $file[$i];
   $k++;
  }
 }

 for ($i = 0; $i<sizeof($ResFile); $i++) {
  $line = explode("|", $ResFile[$i]);
  if ($line[0]==$id) {
      $line[1] = trim($CurrentTime)."\n";
      $is_sid_in_file = 1;
  }
  $line = implode("|", $line);
  $ResFile[$i] = $line;
 }

 $fp = fopen($base, "w");
 for ($i = 0; $i<sizeof($ResFile); $i++) {
     fputs($fp, $ResFile[$i]);
     }
 fclose($fp);

 if (!$is_sid_in_file) {
  $fp = fopen($base, "a-");
  $line = $id."|".$CurrentTime."\n";
  fputs($fp, $line);
  fclose($fp);
 }
}
*/
/*выход счетчик пользвателей*/
//load Cache
$cache = new Cache();
$cache->setEnabled(true);

Registry::set('cache', $cache);
Registry::set('locale', $locale);
Registry::loadConfig($config_values);

$db_config = [
    'adapter' => 'PDO_MYSQL',
    'config' => [
        'host' => $sql_host,
        'username' => $sql_user,
        'password' => $sql_passwd,
        'dbname' => $sql_database
    ]
];

$db = Zend_Db::factory($db_config['adapter'], $db_config['config']);
$db->query("SET NAMES utf8");
Registry::set('dbpdo', $db);
if (!PDO) {
    $db = @mysql_connect($sql_host, $sql_user, $sql_passwd, true) or die('Error connecting to DB');
    mysql_select_db($sql_database, $db) or die('Error selecting DB');
    mysql_query("SET NAMES utf8", $db);
}

Registry::set('db', $db);

Registry::set('site_id', 1);//Website::getSite()->getId()
Registry::loadDBConfig(); //loads automatically

Registry::set('db_name', $sql_database);

Registry::set('use_hs', false);

//Mobile_Detect::isDevice();
$detect = new Mobile_Detect;
Registry::set('device', $detect->isDevice());
//opredelenie device
if (isset($_GET['clearcache'])) {
    $cache->setEnabled(true);
    $cache->clean();
}


if (isset($_COOKIE['cache_config'])){ $cache->setEnabled($_COOKIE['cache_config']);}


if (isset($_REQUEST['site_date']) || isset($_SESSION['site_date'])) {
    $d = (isset($_REQUEST['site_date'])) ? $_REQUEST['site_date'] : $_SESSION['site_date'];
    if ($d != 'no') {
        $curdate = new wsDate($d);
        $_SESSION['site_date'] = $d;
    }
    else
    {
        unset($_SESSION['site_date']);
    }
}else{
    $curdate = new wsDate();

}

Registry::set('curdate', $curdate);

$website = new Website();
Registry::set('Website', $website);
$isAdmin = $website->getCustomer()->isAdmin()==1;



set_exception_handler('exception_handler');
set_error_handler('error_handler');

if(isset($_GET["utm_email_track"])){
     Emailpost::linkEmail(['track'=>$_GET["utm_email_track"]]);
}
if(isset($_GET["track_cart"])){ 
    CartLog::link(['track'=>$_GET["track_cart"]]);
   //  setcookie('track', 'globus_shop', strtotime('+1 year'), '/');
}


//Site lock
if (Config::findByCode('under_maintaince')->getValue() && !$website->getCustomer()->isSuperAdmin()) { die('Under maintaince. Please come back shortly.');}
// run!
Router::route();
echo Controller::process();
mysql_close($db);
if($website->getCustomer()->getId() == 8005){
		//	define('FORME', true);
	//echo get_include_path();
	//echo FORME;
	//echo '<pre>';

       //echo  print_r($_COOKIE);
	//echo print_r(define);
	//echo '</pre>';
//echo $_SERVER[HTTP_COOKIE];
//echo $_COOKIE["PHPSESSID"];

    //Debug::dump(SQLLogger::getInstance()->reportShort());
    // Debug::dump(SQLLogger::getInstance()->reportBySql());
       // Debug::dump(SQLLogger::getInstance()->reportByTime());
   //  Debug::dump(SQLLogger::getInstance()->reportByClass());
   // Debug::dump($timer->getResults());

      //  $timer->stop();
     //  echo 'Time: ' . $timer->getResults('main') . ' s<br>';
      // echo 'Memory: ' . number_format(memory_get_usage(true)/1024/1024,3,'.',',') . ' Mb<br>';
      //  //echo Registry::get('obj');
//echo "Сейчас на сайте: <b>".sizeof(file($base))."</b>";
	//echo '<pre>';
            //   print_r($_SERVER);
        // print_r($_SESSION);
             
	//l(Registry::getInstance());
		//l($_REQUEST['route']);
//		/l($_SESSION['basket']);
	//echo '</pre>';
       // echo 'dsgsd';
    }
