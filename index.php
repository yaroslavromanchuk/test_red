
<?php
//define('THROTTLE_SPEED', '10,50,400,600');//requests per second, 10 seconds, 100 seconds, 1k seconds
//require_once('Framework2.0/throttle.php');//v sluchi dos
//ini_set('display_errors',1);
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
Zend_Loader::registerAutoload();
//spl_autoload_register(array('Zend_Loader', 'autoload'));

//$timer = new DebugTimer(4);
//Registry::set('SQLLogger', SQLLogger::getInstance());

header('Content-type: text/html; charset=UTF-8');
header('Cache-control: private');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: ' . date(DATE_RFC822, strtotime('+10 minutes')));

session_start();

//load Cache
$cache = new Cache();
$cache->setEnabled(true);
Registry::set('cache', $cache);
Registry::set('locale', $locale);
Registry::loadConfig($config_values);
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
Registry::set('dbpdo', $db);
if (!PDO) {
    $db = @mysql_connect($sql_host, $sql_user, $sql_passwd, true) or die('Error connecting to DB');
    @mysql_select_db($sql_database, $db) or die('Error selecting DB');
    @mysql_query("SET NAMES utf8", $db);
}

Registry::set('db', $db);

Registry::set('site_id', Website::getSite()->getId());
Registry::loadDBConfig(); //loads automatically

Registry::set('db_name', $sql_database);

Registry::set('use_hs', false);

$lng = Registry::get('default_language');
if (isset($_SESSION['lang']) && $_SESSION['lang']){ $lng = $_SESSION['lang'];}else{$_SESSION['lang'] = $lng;}

if (isset($_REQUEST['lang']) && $_REQUEST['lang']) {
    $lng = $_REQUEST['lang'];
    $_SESSION['lang'] = $lng;
}
if ($lng) {
if($lng == 'uk'){ $l = 2;}else{$l = 1;}
Registry::set('lang_id', $l ); //wsLanguage::findByCode(strtolower($lng))->getId()
Registry::set('lang', strtolower($lng));
}

$detect = new Mobile_Detect;
Registry::set('device', ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer'));
//opredelenie device

	function morph($n, $k) {
		$unit=array(
			array('гривня'  ,'гривні'  ,'гривень'    ,0),
			array('копійка' ,'копійки' ,'копійок',	 1),
		);
		
		$n = abs(intval($n)) % 100;
		if ($n>10 && $n<20) return $unit[$k][2];
		$n = $n % 10;
		if ($n>1 && $n<5) return $unit[$k][1];
		if ($n==1) return $unit[$k][0];
		return $unit[$k][2];
	}

	function num2strm($num) {
		$nul='нуль';
        $ukr = array(
            array( //one_nine
                array('', 'один', 'два', 'три', 'чотири', 'п\'ять', 'шість', 'сім', 'вісім', 'дев\'ять'),
                array('', 'одна', 'дві', 'три', 'чотири', 'п\'ять', 'шість', 'сім', 'вісім', 'дев\'ять'),
            ),
            array( //teen
                'десять', 'одинадцять', 'дванадцять', 'тринадцать', 'чотирнадцять', 'п\'ятнадцять', 'шістнадцять', 'сімнадцять', 'вісімнадцять', 'дев\'ятнадцять'
            ),
            array( //tenth
                2 => 'двадцять', 'тридцять', 'сорок', 'п\'ятьдесят', 'шістьдесят', 'сімдесять', 'вісімьдесят', 'дев\'яносто'
            ),
            array( //hundred
                '', 'сто', 'двісти', 'триста', 'чотириста', 'п\'ятсот', 'шістсот', 'сімсот', 'вісімсот', 'дев\'ятсот'
            ),
            array( //scales
                array('триліон', 'триліона', 'триліонів', 0),
                array('мільйард', 'мільйарда', 'мільйардів', 0),
                array('мільйон', 'мільйона', 'мільйонів', 0),
                array('тисяча', 'тисячі', 'тисяч', 1),
                array('', '', '', 0)
            ),
            array('Вкажіть число (до 15 цифр)') //number_not_set
        );

        $num = is_numeric(trim($num)) ? (string)$num : 0;

        list($one_nine, $teen, $tenth, $hundred, $scales, $number_not_set) = $ukr;

        // массив будующего числа
        $out = array();

        // обробатываем числа не больше 15 знаков
        if (strlen(trim($num)) <= 15) {
			if (intval($num) > 0) {

				// формируем число с нулями перед ним и длиной 15 сиволов
				$num = sprintf("%015s", trim($num));

				// обробатываем по 3 символа
				foreach (str_split($num, 3) as $k => $v) {

					// пропускаем 000
					if (!intval($v)) continue;

					list($num1, $num2, $num3) = array_map('intval', str_split($v, 1));

					// диапазон 1-999
					$out[] = $hundred[$num1]; // диапазон 100-900
					if ($num2 > 1)
						$out[] = $tenth[$num2] . ' ' . $one_nine[$scales[$k][3]] [$num3]; // диапазон 20-99
					elseif ($num2 > 0)
						$out[] = $teen[$num3]; // диапазон 10-19
					else $out[] = $one_nine[$scales[$k][3]] [$num3]; // диапазон 1-9

					// тысячи, милионы ... и склонения
					$n = $v % 10;
					$n2 = $v % 100;
					if ($n2 > 10 && $n2 < 20) $out[] = $scales[$k][2];
					elseif ($n > 1 && $n < 5) $out[] = $scales[$k][1];
					elseif ($n == 1) $out[] = $scales[$k][0];
					else $out[] = $scales[$k][2];

				}
			}
			elseif (intval($num) == 0) {
				$out[] = $nul;
			}
        } else $out[] = $number_not_set[0];

        return implode(' ', $out);
    }



//remove me;
//$_COOKIE['cache_config'] = false;
//unset($_COOKIE['cache_config']);

if (isset($_GET['clearcache'])) {
    $cache->setEnabled(true);
    $cache->clean();
}


if (isset($_COOKIE['cache_config'])) $cache->setEnabled($_COOKIE['cache_config']);


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
}
else
    $curdate = new wsDate();
Registry::set('curdate', $curdate);

$website = new Website();
Registry::set('Website', $website);
$isAdmin = $website->getCustomer()->isAdmin()==1;

function exception_handler($exception)
{
    $exceptionContent = "Uncaught exception '" . get_class($exception)
                        . "' with message '{$exception->getMessage()}'\n"
                        . "File: {$exception->getFile()}, "
                        . "line {$exception->getLine()}\n"
                        . "Trace\n"
                        . preg_replace('/(\#[0-9]+ )/', '\n', $exception->getTraceAsString())
                        . "\n";

    if (Cfg::getInstance()->getValue('is_live')) {
        wsLog::add($exceptionContent, 'EMERG');
        header("Location: /status/");
        die();
    }

    echo '<fieldset style="font-family:verdana;font-size:11px;line-height:2em">'
         . '<legend>PHP Exception</legend>'
         . "Uncaught exception '" . get_class($exception)
         . "' with message <strong>'{$exception->getMessage()}'</strong><br />\n"
         . "File: <strong>{$exception->getFile()}</strong>, "
         . "line <strong>{$exception->getLine()}</strong><br />\n";

    echo "Trace<br />\n<ol start='0'>"
         . preg_replace('/(\#[0-9]+ )/', '</li><li>', $exception->getTraceAsString())
         . "</li></ol>";
    echo '</fieldset>';
}

function error_handler($errno, $errstr, $errfile, $errline)
{
	if ($errno == E_STRICT) return;
	if (error_reporting() == 0) return;
	global $isAdmin;

	$exceptionContent = "FATAL ERROR #" .$errno. ' '
						. " with message: '{$errstr}'\n"
						. "File: {$errfile}, "
						. "line {$errline}\n";

	if ($errno!=8){
		@ob_end_clean();
		if (!$isAdmin) {
			if (Cfg::getInstance()->getValue('is_live')) {
				wsLog::add($exceptionContent, 'EMERG');
				header("Location: /status/");
				exit;
			}
		}
		else throw new ErrorException($exceptionContent);
	}
}

set_exception_handler('exception_handler');
set_error_handler('error_handler');


//Site lock
//if (Config::findByCode('under_maintaince')->getValue() && !$website->getCustomer()->isSuperAdmin())
   // die('Under maintaince. Please come back shortly.');

// run!
Router::route();
echo Controller::process();

if($website->getCustomer()->getId() == 8005){
		//	define('FORME', true);
	//echo get_include_path();
	//echo FORME;
	//echo '<pre>';
	//echo print_r(define);
	//echo '</pre>';
//echo $_SERVER[HTTP_COOKIE];
//echo $_COOKIE["PHPSESSID"];
	
    // Debug::dump(SQLLogger::getInstance()->reportShort());
      //  Debug::dump(SQLLogger::getInstance()->reportBySql());
      //  Debug::dump(SQLLogger::getInstance()->reportByTime());
    // Debug::dump(SQLLogger::getInstance()->reportByClass());
     // Debug::dump($timer->getResults());

       // $timer->stop();
       // echo 'Time: ' . $timer->getResults('main') . 's<br>';
        //echo 'Memory: ' . number_format(memory_get_usage(true)/1024/1024,3,'.',',') . 'Mb<br>';
        //echo Registry::get('obj');

		//echo '<pre>';
		//print_r($_SESSION);
		//echo count($_SESSION['basket']);
	//echo '</pre>';
	


    }
	
	?> 	
