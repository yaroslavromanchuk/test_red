<?php
	set_time_limit(600);
	date_default_timezone_set('Europe/Kiev');
	setlocale(LC_ALL, "ru_RU");
	setlocale(LC_NUMERIC,'en_US'); //dot - for MySQL
	mb_internal_encoding("UTF-8");
        ini_set('display_errors',false);
    require_once('/home/www.red.ua/www/site_config.php');     
    require_once('/home/www.red.ua/www/Framework2.0/packages/Zend/Loader.php');
    require_once('/home/www.red.ua/www/Framework2.0/packages/Orm/ormLoader.php');
    Zend_Loader::registerAutoload();// nujno
    session_start();
    $today = date("Y-m-d H:i:s");
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
    * Очистка товара в корзине которого нет в наличии
    * @return boolean
    */ 
function clearUserCart(){
    $cart = Cart::allCart();
    $d = 0;
    foreach ($cart as $c) {
        $item = $c->item;
       
      if($item->count()){
          $cc = $ccc = $c->count;
       
        foreach ($item as $it){
            if($it->article->stock <= 0){
                $ccc-=$it->count;
                $d+=$it->count;
                $it->clearCartIthem();
            }
        }
        if($ccc < $cc){
            $c->count = ($ccc<0)?0:$ccc;
            $c->save();
        }
       }
    }
   // if($d > 0){
  //  Telegram::sendMessageTelegram(404070580, 'Удалено '.$d.' товаров.');//Yarik
  //  }
    return true;
 }
 function emait_to_Cart(){
    // $start = microtime(true);
     clearUserCart();
    $cart = Cart::getCartForEmail();
    $to = 0;
    $track = '';
    if($cart->count()){
        $track = base64_encode(date('Y-m-d H:i:s'));
    foreach ($cart  as $c) {
        $c->email = 1;
        $c->cem+=1;
        $c->save();
        $c->sendEmailCart($track);
        $to++;
       //sleep for 3 seconds
       // sleep(2);
        
    }
 }
   // $time = round(((microtime(true) - $start)/60) ,4);
    if($to > 0){
        CartLog::add($to, $track);
       //  Telegram::sendMessageTelegram(404070580, 'Отправлено '.$to.' писем. Время: '.$time.' мин.');//Yarik
    }//else{
       // Telegram::sendMessageTelegram(404070580, 'Нет просроченых корзин. Время: '.$time.' мин.');//Yarik
   // }
 }
 
 function clarBonusUser(){
     //$d = date('T-m-d');
    // $sql = "UPDATE `red_site`.`ws_red_coin` SET `status` = '4' WHERE `ws_red_coin`.`date_off` = '{$d}' and `ws_red_coin`.`status` = 2";
      $coin = wsActiveRecord::useStatic('RedCoin')->findAll(['status'=>2, 'date_off' => date("Y-m-d")]);
   if($coin){
   $summ = 0;
   $count = 0;
foreach ($coin as $c){
    $c->setStatus(4);
    $c->save();
    $count++;
    $summ+=$c->coin;
}
//Telegram::sendMessageTelegram(404070580, 'Анулировано '.$count.' бонусов, на сумму '.$summ.' redcoin');//Yarik
}
 }
 /**
  * 
  */
 function ActiveBonusUser()
 {
     clarBonusUser();
     if(Config::findByCode('bonus')->getValue()){
         $coin = wsActiveRecord::useStatic('RedCoin')->findAll(['status'=>1, 'coin > 0', 'date_active' => date("Y-m-d")]);
         // $coin = wsActiveRecord::useStatic('RedCoin')->findAll(['id'=>8]);
if($coin){
   $summ = 0;
   $count = 0;
foreach ($coin as $c){
    $s = $c->order_add->getOrderAmountMinusCoin();
   // foreach ($c->order_add->articles as $art){
            //    if($art->old_price == 0 and !$art->skidka_block and $c->order_add->shop_id == 1){ $sum+=$art->price; }
         //  }
           // if($sum > ($c->order_add->amount + $c->order_add->deposit)){
              //  $sum = $c->order_add->amount + $c->order_add->deposit;
            //}
            
             if($s > 0){
            if($s != $c->coin){
                 BonusHistory::add($c->customer_id, 'Изменено сумму бонуса '.$c->coin.' на '.$s, $s, $c->order_id_add); 
               $c->setCoin($s);
            }
    $c->setStatus(2);
    $c->save();
    BonusHistory::add($c->customer_id, 'Активировано', $c->coin, $c->order_id_add);
    $count++;
    $summ+=$c->coin;
}else{
    if($s != $c->coin){
                 BonusHistory::add($c->customer_id, 'Изменено сумму бонуса '.$c->coin.' на '.$s, $s, $c->order_id_add); 
               $c->setCoin($s);
            }
    $c->setStatus(4);
    $c->save();
}
}
//Telegram::sendMessageTelegram(404070580, 'Активировано '.$count.' бонусов, на сумму '.$summ.' redcoin');//Yarik
}
		}
 }
 
 /**/
 function add_coin_birth(){
     $today = date("Y-m-d");
     $b = date("m-d");
     $count = 0;
     $sql = "SELECT id, email, hash_id, middle_name, first_name FROM `ws_customers` WHERE  `date_birth` < '{$today}' and `date_birth` > '1950-01-01' and DATE_FORMAT(`date_birth`, '%m-%d') = DATE_FORMAT(('{$today}' + INTERVAL 3 day), '%m-%d')";
   $birth = wsActiveRecord::useStatic('Customer')->findByQuery($sql);
   if($birth->count()){
       foreach ($birth as $b){
          
           $coin = new RedCoin();
$coin->import([
    'coin' => 200,
    'customer_id' => $b->id,
    'status'=>2,
    'order_id_add' => 0,
    'order_id_on' => 0,
    'date_add' => date("Y-m-d"), 'date_active' => date("Y-m-d"), 'date_off' => date("Y-m-d", strtotime("now +7 days"))]);
$coin->save();
BonusHistory::add($b->id, 'Зачислено ДР', 200, 0);

if(filter_var($b->email, FILTER_VALIDATE_EMAIL)){
    $b->sendEmailBirth();
   $count++;
}

       }
      // Telegram::sendMessageTelegram(404070580, 'Birthday: '.$count);//Yarik
   }
    

     
 }
 /**
  * старт очистки логов
  */
 function clearLog()
 {
     $q = "UPDATE `red_site`.`ws_cart` SET `email` = '0' WHERE `ws_cart`.`count` > 0";
	$cnt_m = wsActiveRecord::query($q);
      
      $today = date("Y-m-d H:i:s");
     //remove old machines
        $q = "DELETE FROM ws_machines where ctime < ('{$today}' - INTERVAL " . (int) Config::findByCode('delete_visits_days')->getValue() . " day)";
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
		//Telegram::sendMessageTelegram(404070580, $mess);
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
$sql = "SELECT `brand_id`, `category_id`, SUM(`stock`) AS ctn, SUM(IF(`stock` NOT LIKE  '0', (`stock`*`price`), 0)) as summa, SUM(IF(`old_price` > 0, (`stock`*`old_price`), (`stock`*`price`))) as summa_start FROM `ws_articles` WHERE `brand_id` = ".$b->brand_id." and status = 3 GROUP BY  `category_id`";
$articles = wsActiveRecord::findByQueryArray($sql);
foreach($articles as $a){
$bal_cat = new BalanceCategory();
$bal_cat->setIdBalance($id);
$bal_cat->setIdBrand($a->brand_id);
$bal_cat->setIdCategory($a->category_id);
$bal_cat->setCount($a->ctn);
$bal_cat->setSumma($a->summa);
$bal_cat->setSummaStart($a->summa_start);
$bal_cat->save();
}
}
$sq = "SELECT `id` as `id_article`, `stock` as `count`, `price`, `old_price`
FROM `ws_articles`
WHERE `stock` NOT LIKE '0'
AND `active` = 'y'
AND `status` =3";
$article = wsActiveRecord::findByQueryArray($sq);
if($article){
    foreach($article as $a){
        $bal = new BalanceArticles();
        $bal->setIdBalance($id);
        $bal->setIdArticle($a->id_article);
        $bal->setCount($a->count);
        $bal->setPrice($a->price);
        $bal->setOldPrice($a->old_price);
        $bal->save();
    }
}

 }
/**
 * Уценка товара
 * @return boolean
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
	
	AND `old_price` = 0 AND `ucenka` = 0 and skidka_block != 1 and shop_id = 1";
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
	AND `ucenka` < 20 and skidka_block != 1 and shop_id = 1";
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
	AND `ucenka` < 30 and skidka_block != 1 and shop_id = 1";
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
	AND `ucenka` < 40 and skidka_block != 1 and shop_id = 1";
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
	AND `ucenka` < 50 and skidka_block != 1 and shop_id = 1";
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
	AND `ucenka` < 60 and skidka_block != 1 and shop_id = 1";
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
//Telegram::sendMessageTelegram(404070580, $mes);//Yarik
Telegram::sendMessageTelegram(396902554, $mes);//Ira
return false;
 }
 /**
  * 
  * @return boolean
  */
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
//Telegram::sendMessageTelegram(404070580, $mes);//Yarik
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
HAVING suma <> stock ');

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
 foreach( wsActiveRecord::useStatic('wsMenu')->findAll(['type_id is not null', 'parent_id' => null, 'no_sitemap'=>NULL, 'nofollow'=>NULL, 'noindex'=>NULL], ['sequence' => 'ASC']) as $item) {
    
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
      $res.="<url>";
                $res.="<loc>".$_link."/blog/</loc>";
                $res.="<lastmod>".$d."</lastmod>";
                $res.="<changefreq>weekly </changefreq>";
		$res.="<priority>0.7</priority>";
            $res.="</url>";
            $i++;
            $res.="<url>";
                $res.="<loc>".$_link."/uk/blog/</loc>";
                $res.="<lastmod>".$d."</lastmod>";
                $res.="<changefreq>weekly </changefreq>";
		$res.="<priority>0.7</priority>";
            $res.="</url>";
            $i++;
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
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."</loc>";
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
WHERE  `ws_articles`.`status` = 3
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
            $res.="<url>";
                $res.="<loc>".$_link."/uk".$url."</loc>";
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