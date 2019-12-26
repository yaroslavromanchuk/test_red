<?php
	set_time_limit(600);
	date_default_timezone_set('Europe/Kiev');
	setlocale(LC_ALL, "ru_RU");
	setlocale(LC_NUMERIC,'en_US'); //dot - for MySQL
	mb_internal_encoding("UTF-8");
    require_once('/home/www.red.ua/www/site_config.php');     
    require_once('/home/www.red.ua/www/Framework2.0/packages/Zend/Loader.php');
    require_once('/home/www.red.ua/www/Framework2.0/packages/Orm/ormLoader.php');
    Zend_Loader::registerAutoload();// nujno
    $today = date("Y-m-d H:i:s");
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
    Registry::set('site_id', 1);
    Registry::loadDBConfig(); //loads automatically

    
    
   /**
    * Очистка корзин
    * @return boolean
    */ 
function clearUserCart(){
    $cart = Cart::allCart();
    Telegram::sendMessageTelegram(404070580, 'Удалено '.$cart->count().' корзин.');//Yarik
    foreach ($cart as $c) {
        $c->clearCart();
    }
    return false;
 }
 /**
  * 
  */
 function addBonusUser(){
     if((int)Config::findByCode('bonus')->getValue() == 1 and false){
          $today = date("Y-m-d H:i:s");
$mess = '';
$array = array(63,281,280,275,276,278,273,279,277,163,274,282,283);
		$order = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT `id`, `bonus`, `bonus_flag`, `amount`, `status`, `customer_id`, `admin_pay_time` from ws_orders where `admin_pay_time` < ('".$today."' - INTERVAL " . (int) Config::findByCode('bonus_dey')->getValue() . " day) and `date_create` < '2018-08-08 23:59:00' and '2018-08-01 10:00:00' < `date_create` and `status` = 8 and `bonus_flag` = 0 and `customer_id` = 8005");
		foreach ($order as $r) {
		
		$sum_delly = 0;
		foreach($r->getArticles() as $art){
		if($art->getCount() > 0 and in_array($art->getArticleDb()->category_id, $array)){
		$sum_delly+=$art->price;
		}
		}
		if($sum_delly >= 600){
		$bonus = 100;//$r->amount*0.1;
		 $c = new Customer($r->customer_id);
		 $bonusadd = $bonus + $c->getBonus();
		 $c->setBonus($bonusadd);
		 $c->save();
		// $r->setBonusFlag(1);
		 $q = "UPDATE  `red_site`.`ws_orders` SET  `bonus_flag` =  '1' WHERE  `ws_orders`.`id` = ".$r->id;
		 $cnt_m = wsActiveRecord::query($q);
		 BonusHistory::add(8005, $r->customer_id, '+', $bonus, $r->id);
		 $mess.=$r->id.',';
		}
		}
		}
 }
 /**
  * старт очистки логов
  */
 function clearLog(){
      $today = date("Y-m-d H:i:s");
     //remove old machines
	$q = "DELETE FROM ws_machines where ctime < ('".$today."' - INTERVAL " . (int) Config::findByCode('delete_visits_days')->getValue() . " day)";
	$cnt_m = wsActiveRecord::query($q);
	$msg_m = "Deleted machines: $cnt_m\r\n";
	//wsLog::add($msg_m, 'CRON');

	//remove old visits
	$q = "DELETE FROM ws_visits where ctime < ('".$today."' - INTERVAL " . (int) Config::findByCode('delete_visits_days')->getValue() . " day)";
	$cnt_v = wsActiveRecord::query($q);
	$msg_v = "Deleted visits: $cnt_v\r\n";
	//wsLog::add($msg_v, 'CRON');
	
	//remove old logs
	$q = "DELETE FROM ws_log where timestamp < ('".$today."' - INTERVAL " . (int) Config::findByCode('delete_visits_days')->getValue() . " day)";
	$cnt_l = wsActiveRecord::query($q);
	$msg_l = "Deleted logs: $cnt_l\r\n";
	//wsLog::add($msg_l, 'CRON');
	
		$mess = "Start CRON ".$today."\r\n";
		$mess .= $msg_m.$msg_v.$msg_l;
		Telegram::sendMessageTelegram(404070580, $mess);
 }
 /**
  * сбор остатков товара
  */
 function fixedBalance(){
     $balance = new Balance();
$balance->setDate(date('Y-m-d'));
$balance->save();
$id = $balance->getId();
$sql = "SELECT DISTINCT  `brand_id` FROM  `ws_articles`";
$brand = wsActiveRecord::findByQueryArray($sql);
foreach($brand as $b){
$sql = "SELECT `brand_id`, `category_id`, SUM(`stock`) AS ctn FROM `ws_articles` WHERE `brand_id` = ".$b->brand_id." and status = 3 GROUP BY  `category_id`";
$articles = wsActiveRecord::findByQueryArray($sql);
foreach($articles as $a){
$bal_cat = new BalanceCategory();
$bal_cat->setIdBalance($id);
$bal_cat->setIdBrand($a->brand_id);
$bal_cat->setIdCategory($a->category_id);
$bal_cat->setCount($a->ctn);
$bal_cat->save();
}
}
 }
 /**
  * 
  */
 function Ucenka(){
      $today = date("Y-m-d H:i:s");
     $interval = 14;
$sum = 0;
$all_sum = 0;
$all_count = 0;
$mes="Уценка ".$today."\r\n";
//AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 35 DAY )
///10
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 21 DAY )
	
	AND `old_price` = 0 AND `ucenka` = 0 and skidka_block != 1";
$i=0;
$j = 0;	
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if( 10 <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$koll = $a->getCountArticles();
$a->setUcenka(10);
$a->setDataUcenki($today);
$a->setOldPrice($a->getPrice());
$a->setPrice(ceil($a->getPrice() * 0.9));
$cat = new Shopcategories($a->getCategoryId());
$a->setDopCatId($cat->getUsencaCategory());
$a->save();
$sum+= ($a->getOldPrice()-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $a->getOldPrice(), $a->getPrice(), $koll, 10);
$i++;
$j+=$koll;
}else{
$a->setLabelid(10);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="10 - ".$j." - ".$sum." грн.\r\n";
///end 20

/// 20
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 35 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 49 DAY )
	AND `old_price` > 0
	AND `ucenka` < 20 and skidka_block != 1";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if(20 <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(20);
$a->setDataUcenki($today);
$a->setPrice(ceil($a->getOldPrice() * 0.8));
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 20);
$i++;
$j+=$koll;
}else{
$a->setLabelid(20);
$a->setSkidkaBlock(1);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="20 - ".$j." - ".$sum." грн.\r\n";
/// end 20
/// 30
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 49 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 63 DAY )
	AND `old_price` > 0
	AND `ucenka` < 30 and skidka_block != 1";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if(30 <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(30);
$a->setDataUcenki($today);
$a->setPrice(ceil($a->getOldPrice() * 0.7));
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 30);
$i++;
$j+=$koll;
}else{
$a->setLabelid(30);
$a->setSkidkaBlock(1);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="30 - ".$j." - ".$sum." грн.\r\n";
/// end 30
/// 40
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 63 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 77 DAY )
	AND `old_price` > 0
	AND `ucenka` < 40 and skidka_block != 1";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if( 40 <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(40);
$a->setDataUcenki($today);
$a->setPrice(ceil($a->getOldPrice() * 0.6));
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 40);
$i++;
$j+=$koll;
}else{
$a->setLabelid(40);
$a->setSkidkaBlock(1);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="40 - ".$j." - ".$sum." грн.\r\n";
/// end 40
/// 50
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 77 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 91 DAY )
	AND `old_price` > 0 
	AND `ucenka` < 50 and skidka_block != 1";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if(50 <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(50);
$a->setDataUcenki($today);
$a->setPrice(ceil($a->getOldPrice() * 0.5));
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 50);
$i++;
$j+=$koll;
}else{
$a->setLabelid(50);
$a->setSkidkaBlock(1);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="50 - ".$j." - ".$sum." грн.\r\n";
/// end 50
/// 60
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 91 DAY )
	AND `old_price` > 0
	AND `ucenka` < 60 and skidka_block != 1";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if(60 <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(60);
$a->setDataUcenki($today);
$a->setPrice(ceil($a->getOldPrice() * 0.4));
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 60);
$i++;
$j+=$koll;
}else{
$a->setLabelid(60);
$a->setSkidkaBlock(1);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="60 - ".$j." - ".$sum." грн.\r\n";
/// end 60
$mes.="All - ".$all_count." - ".$all_sum." грн.\r\n";
Telegram::sendMessageTelegram(404070580, $mes);//Yarik
Telegram::sendMessageTelegram(396902554, $mes);//Ira
return false;
 }
  function UcenkaGo(){
      $today = date("Y-m-d H:i:s");
      $usenka = date("Y-m-d",  strtotime("+7 days"));
     $interval = 14;
$sum = 0;
$all_sum = 0;
$all_count = 0;
$mes="Уценка ".$today."\r\n";
//AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 35 DAY )
///10
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( ".$usenka.", INTERVAL 21 DAY )
	
	AND `old_price` = 0 AND `ucenka` = 0 and skidka_block != 1 and sezon =1";
$i=0;
$j = 0;	
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if( 10 <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$koll = $a->getCountArticles();
$a->setUcenka(10);
$a->setDataUcenki($today);
$a->setOldPrice($a->getPrice());
$a->setPrice(ceil($a->getPrice() * 0.9));
$cat = new Shopcategories($a->getCategoryId());
$a->setDopCatId($cat->getUsencaCategory());
$a->save();
$sum+= ($a->getOldPrice()-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $a->getOldPrice(), $a->getPrice(), $koll, 10);
$i++;
$j+=$koll;
}else{
$a->setLabelid(10);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="10 - ".$j." - ".$sum." грн.\r\n";
///end 20

/// 20
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( ".$usenka.", INTERVAL 35 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( ".$usenka.", INTERVAL 49 DAY )
	AND `old_price` > 0
	AND `ucenka` < 20 and skidka_block != 1 and sezon = 1";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if(20 <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(20);
$a->setDataUcenki($today);
$a->setPrice(ceil($a->getOldPrice() * 0.8));
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 20);
$i++;
$j+=$koll;
}else{
$a->setLabelid(20);
$a->setSkidkaBlock(1);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="20 - ".$j." - ".$sum." грн.\r\n";
/// end 20
/// 30
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( ".$usenka.", INTERVAL 49 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( ".$usenka.", INTERVAL 63 DAY )
	AND `old_price` > 0
	AND `ucenka` < 30 and skidka_block != 1 and sezon = 1";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if(30 <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(30);
$a->setDataUcenki($today);
$a->setPrice(ceil($a->getOldPrice() * 0.7));
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 30);
$i++;
$j+=$koll;
}else{
$a->setLabelid(30);
$a->setSkidkaBlock(1);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="30 - ".$j." - ".$sum." грн.\r\n";
/// end 30
/// 40
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( ".$usenka.", INTERVAL 63 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( ".$usenka.", INTERVAL 77 DAY )
	AND `old_price` > 0
	AND `ucenka` < 40 and skidka_block != 1 and sezon = 1";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if( 40 <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(40);
$a->setDataUcenki($today);
$a->setPrice(ceil($a->getOldPrice() * 0.6));
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 40);
$i++;
$j+=$koll;
}else{
$a->setLabelid(40);
$a->setSkidkaBlock(1);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="40 - ".$j." - ".$sum." грн.\r\n";
/// end 40
/// 50
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( ".$usenka.", INTERVAL 77 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( ".$usenka.", INTERVAL 91 DAY )
	AND `old_price` > 0 
	AND `ucenka` < 50 and skidka_block != 1 and sezon = 1";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if(50 <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(50);
$a->setDataUcenki($today);
$a->setPrice(ceil($a->getOldPrice() * 0.5));
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 50);
$i++;
$j+=$koll;
}else{
$a->setLabelid(50);
$a->setSkidkaBlock(1);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="50 - ".$j." - ".$sum." грн.\r\n";
/// end 50
/// 60
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( ".$usenka.", INTERVAL 91 DAY )
	AND `old_price` > 0
	AND `ucenka` < 60 and skidka_block != 1 and sezon = 1";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if(60 <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(60);
$a->setDataUcenki($today);
$a->setPrice(ceil($a->getOldPrice() * 0.4));
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 60);
$i++;
$j+=$koll;
}else{
$a->setLabelid(60);
$a->setSkidkaBlock(1);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="60 - ".$j." - ".$sum." грн.\r\n";
/// end 60
$mes.="All - ".$all_count." - ".$all_sum." грн.\r\n";
Telegram::sendMessageTelegram(404070580, $mes);//Yarik
//Telegram::sendMessageTelegram(396902554, $mes);//Ira
return false;
 }
 
 function articleNoSkidkaBlock(){
    $block_sk = wsActiveRecord::useStatic('Shoparticles')->findByQuery("SELECT * 
FROM  `ws_articles` 
WHERE  `stock` NOT LIKE  '0'
AND  `ucenka` >0
AND (
`max_skidka` -  `ucenka`
) >10
AND  `skidka_block` =1");
 
 if($block_sk){
     foreach ($block_sk as $a) {
      //$a->setLabelid(60);
        $a->setSkidkaBlock(0);
        $a->save();
     }
 }
 }
 /**
  * выравнивание количества размеров и общего количества
  */
 function articleRavno(){
      $kost = wsActiveRecord::useStatic('Shoparticles')->findByQuery('SELECT ws_articles.*, sum(ws_articles_sizes.count) as suma FROM  ws_articles_sizes
JOIN ws_articles on ws_articles.id = ws_articles_sizes.id_article
GROUP BY ws_articles_sizes.id_article
HAVING sum(ws_articles_sizes.count) <>stock ');

        foreach ($kost as $k) {
            $k->setStock($k->suma);
            $k->save();
        }
				//выравнивание количества размеров и общего количества
			
 }
 function CustomersSegment(){
     $segment = wsActiveRecord::useStatic('CustomersSegment')->findAll(['active'=>1]);
foreach ($segment as $s) {
    $cust =  wsActiveRecord::useStatic('Shoporders')->findByQuery($s->sql);
    foreach ($cust as $cu) {
        $c = new Customer($cu->customer_id);
        if($c->id){
        $c->setSegmentId($s->id);
        $c->save();
        }
        
    }
    
}
 }
 function updateSitemap(){
     $data = date('Y-m-d');
     //$_link = 'https://www.red.ua';
        
            $res = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
                 $res.='<sitemap><loc>https://www.red.ua/sitemap-women.xml</loc><lastmod>'.$data.'</lastmod></sitemap>';
                 $res.='<sitemap><loc>https://www.red.ua/sitemap-men.xml</loc><lastmod>'.$data.'</lastmod></sitemap>';
                 $res.='<sitemap><loc>https://www.red.ua/sitemap-baby.xml</loc><lastmod>'.$data.'</lastmod></sitemap>';
                 $res.='<sitemap><loc>https://www.red.ua/sitemap-accessory.xml</loc><lastmod>'.$data.'</lastmod></sitemap>';
                 $res.='<sitemap><loc>https://www.red.ua/sitemap-sale.xml</loc><lastmod>'.$data.'</lastmod></sitemap>';
                 $res.='<sitemap><loc>https://www.red.ua/sitemap-shoes.xml</loc><lastmod>'.$data.'</lastmod></sitemap>';
                 $res.='<sitemap><loc>https://www.red.ua/sitemap-tovarydlyadoma.xml</loc><lastmod>'.$data.'</lastmod></sitemap>';
                 $res.='<sitemap><loc>https://www.red.ua/sitemap-products.xml</loc><lastmod>'.$data.'</lastmod></sitemap>';
                 $res.='<sitemap><loc>https://www.red.ua/sitemap-blog.xml</loc><lastmod>'.$data.'</lastmod></sitemap>';
                 $res.='<sitemap><loc>https://www.red.ua/sitemap-brands.xml</loc><lastmod>'.$data.'</lastmod></sitemap>';
                 $res.='<sitemap><loc>https://www.red.ua/sitemap-service.xml</loc><lastmod>'.$data.'</lastmod></sitemap>';
            $res.='</sitemapindex>';
            file_put_contents('../sitemap.xml', $res);
            return true;
 }
 function womenSitemap(){
     $i=0;
     $data = date('Y-m-d');
     $_link = 'https://www.red.ua';
     $res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
     $cat = wsActiveRecord::useStatic('Shopcategories')->findById(14);
     foreach(wsActiveRecord::useStatic('Shopcategories')->findAll(['sitemap' => 1, 'active' => 1, 'id in('.implode(",", $cat->getKidsIds()).')']) as $item) {
         
                 $url = stripslashes($item->getPath());
                $res.="<url>";
                $res.="<loc>".$_link.$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $sezon = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `sezon`");
         if($sezon){
             foreach ($sezon as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $color = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
and color_id > 0
GROUP BY `color_id`");
         if($color){
             foreach ($color as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $brand = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `brand_id`");
         if($brand){
             foreach ($brand as $b) {
                  $res.="<url>";
                $res.="<loc>".$_link.$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
        }
     $res.="</urlset>";
     
     file_put_contents('../sitemap-women.xml', $res);
     return $i;
 }
  function menSitemap(){
      $i=0;
     $data = date('Y-m-d');
     $_link = 'https://www.red.ua';
     $res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
     $cat = wsActiveRecord::useStatic('Shopcategories')->findById(15);
     foreach(wsActiveRecord::useStatic('Shopcategories')->findAll(['sitemap' => 1, 'active' => 1, 'id in('.implode(",", $cat->getKidsIds()).')']) as $item) {
                 $url = stripslashes($item->getPath());
                $res.="<url>";
                $res.="<loc>".$_link.$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $sezon = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `sezon`");
         if($sezon){
             foreach ($sezon as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $color = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
and `color_id` > 0
GROUP BY `color_id`");
         if($color){
             foreach ($color as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $brand = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `brand_id`");
         if($brand){
             foreach ($brand as $b) {
                  $res.="<url>";
                $res.="<loc>".$_link.$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
        }
     $res.="</urlset>";
     
     file_put_contents('../sitemap-men.xml', $res);
     return $i;
 }
  function babySitemap(){
      $i=0;
     $data = date('Y-m-d');
     $_link = 'https://www.red.ua';
     $res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
     $cat = wsActiveRecord::useStatic('Shopcategories')->findById(59);
     foreach(wsActiveRecord::useStatic('Shopcategories')->findAll(['sitemap' => 1, 'active' => 1, 'id in('.implode(",", $cat->getKidsIds()).')']) as $item) {
                 $url = stripslashes($item->getPath());
                $res.="<url>";
                $res.="<loc>".$_link.$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $sezon = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `sezon`");
         if($sezon){
             foreach ($sezon as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $color = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
and color_id > 0
GROUP BY `color_id`");
         if($color){
             foreach ($color as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $brand = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `brand_id`");
         if($brand){
             foreach ($brand as $b) {
                  $res.="<url>";
                $res.="<loc>".$_link.$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
        }
     $res.="</urlset>";
     
     file_put_contents('../sitemap-baby.xml', $res);
     return $i;
 }
  function accessorySitemap(){
      $i=0;
     $data = date('Y-m-d');
     $_link = 'https://www.red.ua';
     $res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
     $cat = wsActiveRecord::useStatic('Shopcategories')->findById(54);
     foreach(wsActiveRecord::useStatic('Shopcategories')->findAll(['sitemap' => 1, 'active' => 1, 'id in('.implode(",", $cat->getKidsIds()).')']) as $item) {
                $url = stripslashes($item->getPath());
                $res.="<url>";
                $res.="<loc>".$_link.$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $sezon = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `sezon`");
         if($sezon){
             foreach ($sezon as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $color = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
and color_id > 0
GROUP BY `color_id`");
         if($color){
             foreach ($color as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $brand = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `brand_id`");
         if($brand){
             foreach ($brand as $b) {
                  $res.="<url>";
                $res.="<loc>".$_link.$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
        }
     $res.="</urlset>";
     
     file_put_contents('../sitemap-accessory.xml', $res);
     return $i;
 }
  function saleSitemap(){
      $i=0;
     $data = date('Y-m-d');
     $_link = 'https://www.red.ua';
     $res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
     $cat = wsActiveRecord::useStatic('Shopcategories')->findById(85);
     foreach(wsActiveRecord::useStatic('Shopcategories')->findAll(['sitemap' => 1, 'active' => 1, 'id in('.implode(",", $cat->getKidsIds()).')']) as $item) {
                 $url = stripslashes($item->getPath());
                $res.="<url>";
                $res.="<loc>".$_link.$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $sezon = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `sezon`");
         if($sezon){
             foreach ($sezon as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $color = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
and color_id > 0
GROUP BY `color_id`");
         if($color){
             foreach ($color as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $brand = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `brand_id`");
         if($brand){
             foreach ($brand as $b) {
                  $res.="<url>";
                $res.="<loc>".$_link.$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
        }
     $res.="</urlset>";
     
     file_put_contents('../sitemap-sale.xml', $res);
     return $i;
 }
  function shoesSitemap(){
      $i=0;
     $data = date('Y-m-d');
     $_link = 'https://www.red.ua';
     $res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
     $cat = wsActiveRecord::useStatic('Shopcategories')->findById(33);
     foreach(wsActiveRecord::useStatic('Shopcategories')->findAll(['sitemap' => 1, 'active' => 1, 'id in('.implode(",", $cat->getKidsIds()).')']) as $item) {
                 $url = stripslashes($item->getPath());
                $res.="<url>";
                $res.="<loc>".$_link.$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $sezon = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `sezon`");
         if($sezon){
             foreach ($sezon as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $color = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
and color_id > 0
GROUP BY `color_id`");
         if($color){
             foreach ($color as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $brand = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `brand_id`");
         if($brand){
             foreach ($brand as $b) {
                  $res.="<url>";
                $res.="<loc>".$_link.$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
        }
     $res.="</urlset>";
     
    file_put_contents('../sitemap-shoes.xml', $res);
    return $i;
 }
 function tovarydlyadomaSitemap(){
     $i=0;
     $data = date('Y-m-d');
     $_link = 'https://www.red.ua';
     $res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
     $cat = wsActiveRecord::useStatic('Shopcategories')->findById(254);
     foreach(wsActiveRecord::useStatic('Shopcategories')->findAll(['sitemap' => 1, 'active' => 1, 'id in('.implode(",", $cat->getKidsIds()).')']) as $item) {
         $url = stripslashes($item->getPath());
                $res.="<url>";
                $res.="<loc>".$_link.$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
            $sezon = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `sezon`");
         if($sezon){
             foreach ($sezon as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."sezons-".$s->getNameSezon()->translate."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $color = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
and color_id > 0
GROUP BY `color_id`");
         if($color){
             foreach ($color as $s){
                $res.="<url>";
                $res.="<loc>".$_link.$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."colors-".$s->color_id."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
         $brand = wsActiveRecord::useStatic('Shoparticles')->findByQuery(" SELECT *
FROM `ws_articles`
WHERE `category_id` ='$item->id'
AND `stock` NOT LIKE '0'
GROUP BY `brand_id`");
         if($brand){
             foreach ($brand as $b) {
                  $res.="<url>";
                $res.="<loc>".$_link.$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++; 
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."brands-".$b->getBrands()->getToUrl()."/</loc>";
                $res.="<changefreq>always</changefreq>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            $i++;
             }
         }
        }
     $res.="</urlset>";
     
     file_put_contents('../sitemap-tovarydlyadoma.xml', $res);
     return $i;
 }
 function productsSitemap(){
     $i=0;
     $_link = 'https://www.red.ua';
     $res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
     
        foreach(wsActiveRecord::useStatic('Shoparticles')->findAll(array( 'active' => "y",  'stock not like "0"', 'status' => 3), array('id'=>'DESC')) as $ar){
             $data = date('Y-m-d', strtotime($ar->ctime));
             $url = stripslashes($ar->getPath());
                $res.="<url>";
                $res.="<loc>".$_link.$url."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
                $res.="<changefreq>daily</changefreq>";
		$res.="<priority>0.8</priority>";
            $res.="</url>";
            $i++;
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
                $res.="<changefreq>daily</changefreq>";
		$res.="<priority>0.8</priority>";
            $res.="</url>";
            $i++;
			 }
     $res.="</urlset>";
     
     file_put_contents('../sitemap-products.xml', $res);
     return $i;
 }
 
 function serviceSitemap(){
     $i=0;
     $_link = 'https://www.red.ua';
     $res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
     $data = date('Y-m-d');
 foreach( wsActiveRecord::useStatic('wsMenu')->findAll(['type_id is not null', 'parent_id' => null, 'no_sitemap'=>NULL, 'nofollow'=>NULL], ['sequence' => 'ASC']) as $item) {
    
		$url = stripslashes($item->getPathSitemap());
                $res.="<url>";
                $res.="<loc>".$_link.$url."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
                $res.="<changefreq>weekly </changefreq>";
		$res.="<priority>0.7</priority>";
            $res.="</url>";
            $i++;
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
                $res.="<changefreq>weekly </changefreq>";
		$res.="<priority>0.7</priority>";
            $res.="</url>";
     $i++;
           
        }
        
 $res.="</urlset>";
     
     file_put_contents('../sitemap-service.xml', $res);
     return $i;
 }
  function blogSitemap(){
      $i=0;
     $_link = 'https://www.red.ua';
     $res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
     $d = date("Y-m-d H:i:s");
 foreach( wsActiveRecord::useStatic('Blog')->findAll(["public = 1 and ctime < '$d'"]) as $item) {
     $data = date('Y-m-d', strtotime($item->ctime));
		$url = stripslashes($item->getPath());
                $res.="<url>";
                $res.="<loc>".$_link.$url."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
                $res.="<changefreq>weekly </changefreq>";
		$res.="<priority>0.7</priority>";
            $res.="</url>";
            $i++;
        }
 $res.="</urlset>";
     
     file_put_contents('../sitemap-blog.xml', $res);
     return $i;
 }
 function brandsSitemap(){
     $i=0;
     $_link = 'https://www.red.ua';
     $res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
     $data = date('Y-m-d');
 foreach( wsActiveRecord::useStatic('Brand')->findByQuery("SELECT `red_brands` . *
FROM `red_brands`
INNER JOIN `ws_articles` ON `ws_articles`.`brand_id` = `red_brands`.`id`
WHERE `ws_articles`.`stock` NOT LIKE '0'
AND `ws_articles`.`status` =3
AND `ws_articles`.`active` = 'y'
group by `red_brands` .`id`") as $item) {
     
		$url = stripslashes($item->getToSitemapUrl());
                $res.="<url>";
                $res.="<loc>".$_link.$url."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
                $res.="<changefreq>daily</changefreq>";
		$res.="<priority>0.7</priority>";
            $res.="</url>";
            $i++;
        }
 $res.="</urlset>";
     
     file_put_contents('../sitemap-brands.xml', $res);
     return $i;
 }