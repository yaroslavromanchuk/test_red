<?php 
//03:00
//error_reporting(E_ALL);
//ini_set('display_errors',1);
	$date_min = new DateTime("02:40"); // минимальное значение времени
       $date_max = new DateTime("03:30"); // максимальное значение времени
       $date_now = new DateTime();
if ($date_now >= $date_min && $date_now <= $date_max) {
require_once('cron_init.php');
//if(date("Y-m-d") == "2020-03-01"){ wsActiveRecord::query("UPDATE `red_site`.`ws_red_coin` SET `status` = '2'"); }
$today = date("Y-m-d H:i:s");
$days = [1 => 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота' , 'Воскресенье'];	

//if($days[date('N')] == 'Вторник'){
   // Ucenka(); 
  //  articleNoSkidkaBlock();
//}//выход с уценки
ActiveBonusUser(); // Активация бонусов пользователей
articleRavno(); //выравнивание количества размеров и общего количества
fixedBalance(); //
clearLog();	//
//clearUserCart();  //удалить старые корзины  

//
//
//ускоренная уценка
if(false)
    { //уценка
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
}//выход с уценки
//выход с ускоренной уценки
if(false)
    {
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
		}
      
}//end if
	
