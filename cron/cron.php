<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
		$date_min = new DateTime("02:30"); // минимальное значение времени
       $date_max = new DateTime("05:00"); // максимальное значение времени
       $date_now = new DateTime();
if ($date_now >= $date_min && $date_now <= $date_max) {
require_once('cron_init.php');
$today = date("Y-m-d H:i:s");
$days = array( 1 => 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота' , 'Воскресенье' );
//set_time_limit(600); 
if(true){
//выравнивание количества размеров и общего количества
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
		

		
if(false){
	$sum = 0;
$all_sum = 0;
$all_count = 0;
$mes="Уценка ".$today."\r\n";

///50
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' AND  `active` =  'y'
	AND `old_price` = 0 AND `ucenka` = 0 and category_id = 74";
$i=0;
$j = 0;	
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$koll = $a->getCountArticles();
$a->setUcenka(50);
$a->setDataUcenki($today);
$a->setOldPrice($a->getPrice());
$a->setPrice($a->getPrice() * 0.5);
$cat = new Shopcategories($a->getCategoryId());
$a->setDopCatId($cat->getUsencaCategory());
$a->save();
$sum+= ($a->getOldPrice()-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $a->getOldPrice(), $a->getPrice(), $koll, 50);
$i++;
$j+=$koll;
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="50 - ".$j." - ".$sum." грн.\r\n";

///30
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' AND  `active` =  'y'
	AND `old_price` > 0 AND `ucenka` = 20 and category_id = 74";
$i=0;
$j = 0;	
$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$koll = $a->getCountArticles();
$a->setUcenka(30);
$a->setDataUcenki($today);
$a->setOldPrice($a->getPrice());
$a->setPrice($a->getPrice() * 0.7);
$cat = new Shopcategories($a->getCategoryId());
$a->setDopCatId($cat->getUsencaCategory());
$a->save();
$sum+= ($a->getOldPrice()-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $a->getOldPrice(), $a->getPrice(), $koll, 30);
$i++;
$j+=$koll;
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="30 - ".$j." - ".$sum." грн.\r\n";
$mes.="All - ".$all_count." - ".$all_sum." грн.\r\n";
sendMessageTelegram(404070580, $mes);//Yarik
		}
		
		
if($days[date('N')] == 'Вторник'){ //уценка
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
$a->setPrice($a->getPrice() * 0.9);
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
$a->setPrice($a->getOldPrice() * 0.8);
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
$a->setPrice($a->getOldPrice() * 0.7);
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
$a->setPrice($a->getOldPrice() * 0.6);
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
$a->setPrice($a->getOldPrice() * 0.5);
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
$a->setPrice($a->getOldPrice() * 0.4);
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
sendMessageTelegram(404070580, $mes);//Yarik
sendMessageTelegram(396902554, $mes);//Ira
}//выход с уценки

//ускоренная уценка
if(false){ //уценка
$sum = 0;
$all_sum = 0;
$all_count = 0;
$mes="Уценка ".$today."\r\n";

///20
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 14 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 28 DAY )
	AND `old_price` = 0
	AND category_id in(115,253,32,30,143,144)
	";
$i=0;
$j = 0;	
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$koll = $a->getCountArticles();
$a->setUcenka(20);
$a->setDataUcenki($today);
$a->setOldPrice($a->getPrice());
$a->setPrice($a->getPrice() * 0.8);
$cat = new Shopcategories($a->getCategoryId());
$a->setDopCatId($cat->getUsencaCategory());
$a->save();
$sum+= ($a->getOldPrice()-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $a->getOldPrice(), $a->getPrice(), $koll, 20);
$i++;
$j+=$koll;
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="20 - ".$j." - ".$sum." грн.\r\n";
///end 20
/// 30
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 28 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 42 DAY )
	AND `old_price` > 0
	AND category_id in(115,253,32,30,143,144)";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(30);
$a->setDataUcenki($today);
$a->setPrice($a->getOldPrice() * 0.7);
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 30);
$i++;
$j+=$koll;
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="30 - ".$j." - ".$sum." грн.\r\n";
/// end 30
/// 40
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 42 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 56 DAY )
	AND `old_price` > 0
	AND `ucenka` = 30
	AND category_id  in(115,253,32,30,143,144)";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(40);
$a->setDataUcenki($today);
$a->setPrice($a->getOldPrice() * 0.6);
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 40);
$i++;
$j+=$koll;
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="40 - ".$j." - ".$sum." грн.\r\n";
/// end 40
/// 50
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 56 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 70 DAY )
	AND `old_price` > 0
	AND `ucenka` = 40
	AND category_id  in(115,253,32,30,143,144)";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(50);
$a->setDataUcenki($today);
$a->setPrice($a->getOldPrice() * 0.5);
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 50);
$i++;
$j+=$koll;
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="50 - ".$j." - ".$sum." грн.\r\n";
/// end 50
/// 60
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 70 DAY )
	AND `old_price` > 0
	AND `ucenka` = 50
	AND category_id  in(115,253,32,30,143,144)";
	$i=0;
	$j=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$s_p = $a->getPrice();
$koll = $a->getCountArticles();
$a->setUcenka(60);
$a->setDataUcenki($today);
$a->setPrice($a->getOldPrice() * 0.4);
$a->save();
$sum += ($s_p-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 60);
$i++;
$j+=$koll;
}
$all_count+=$j;
$all_sum+=$sum;
$mes.="60 - ".$j." - ".$sum." грн.\r\n";
/// end 60
$mes.="All - ".$all_count." - ".$all_sum." грн.\r\n";
sendMessageTelegram(404070580, $mes);//Yarik
sendMessageTelegram(396902554, $mes);//Ira
}//выход с уценки
//выход с ускоренной уценки
		

if(true){//сбор остатков товара
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
}//выход с сбора остатков товара
	
if(true){// старт очистки логов
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
		sendMessageTelegram(404070580, $mess);
	}
}//end if
//sendMessageTelegram(404070580, 'Старт cron');

		?>			
