<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
require_once(dirname(__FILE__) . '/cron_init.php');
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
		
if($days[date( 'N' )] == 'Вторник'){//уценка  
$sum = 0;
$all_sum = 0;
$all_count = 0;
$mes="Уценка ".$today."\r\n";
///20
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 34 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 68 DAY )
	AND `old_price` = 0 AND `ucenka` = 0";
$i=0;	
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$a->setUcenka(20);
$a->setDataUcenki($today);
$a->setOldPrice($a->getPrice());
$a->setPrice($a->getPrice() * 0.8);
$cat = new Shopcategories($a->getCategoryId());
$a->setDopCatId($cat->getUsencaCategory());
$a->save();
$sum+=$a->getOldPrice()-$a->getPrice();
UcenkaHistory::newUcenka(8005, $a->getId(), $a->getOldPrice(), $a->getPrice(), 20);
$i++;
}
$all_count+=$i;
$all_sum+=$sum;
$mes.="20 - ".$i." - ".$sum." грн.\r\n";
///end 20
/// 30
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 68 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 102 DAY )
	AND `old_price` > 0
	AND `ucenka` = 20";
	$i=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$s_p = $a->getPrice();
$a->setUcenka(30);
$a->setDataUcenki($today);
$a->setPrice($a->getOldPrice() * 0.7);
$a->save();
$sum += $s_p-$a->getPrice();
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), 30);
$i++;
}
$all_count+=$i;
$all_sum+=$sum;
$mes.="30 - ".$i." - ".$sum." грн.\r\n";
/// end 30
/// 40
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 102 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 136 DAY )
	AND `old_price` > 0
	AND `ucenka` = 30";
	$i=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$s_p = $a->getPrice();
$a->setUcenka(40);
$a->setDataUcenki($today);
$a->setPrice($a->getOldPrice() * 0.6);
$a->save();
$sum += $s_p-$a->getPrice();
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), 40);
$i++;
}
$all_count+=$i;
$all_sum+=$sum;
$mes.="40 - ".$i." - ".$sum." грн.\r\n";
/// end 40
/// 50
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 136 DAY )
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 170 DAY )
	AND `old_price` > 0
	AND `ucenka` = 40";
	$i=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$s_p = $a->getPrice();
$a->setUcenka(50);
$a->setDataUcenki($today);
$a->setPrice($a->getOldPrice() * 0.5);
$a->save();
$sum += $s_p-$a->getPrice();
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), 50);
$i++;
}
$all_count+=$i;
$all_sum+=$sum;
$mes.="50 - ".$i." - ".$sum." грн.\r\n";
/// end 50
/// 60
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' 
	AND  `active` =  'y'
	AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) < DATE_SUB( CURRENT_DATE, INTERVAL 170 DAY )
	AND `old_price` > 0
	AND `ucenka` = 50";
	$i=0;
	$sum = 0;
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$s_p = $a->getPrice();
$a->setUcenka(60);
$a->setDataUcenki($today);
$a->setPrice($a->getOldPrice() * 0.4);
$a->save();
$sum += $s_p-$a->getPrice();
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), 60);
$i++;
}
$all_count+=$i;
$all_sum+=$sum;
$mes.="60 - ".$i." - ".$sum." грн.\r\n";
/// end 60
$mes.="All - ".$all_count." - ".$all_sum." грн.\r\n";
sendMessageTelegram(404070580, $mes);
sendMessageTelegram(396902554, $mes);
}//выход с уценки
		

if(true){//сбор остатков товара
$balance = new Balance();
$balance->setDate(date('Y-m-d'));
$balance->save();
$id = $balance->getId();
$sql = "SELECT DISTINCT  `brand_id` FROM  `ws_articles`";
$brand = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
foreach($brand as $b){
$sql = "SELECT `brand_id`, `category_id`, SUM(`stock`) AS ctn FROM `ws_articles` WHERE `brand_id` = ".$b->brand_id." GROUP BY  `category_id`";
$articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
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


/*		
if((int)Config::findByCode('bonus')->getValue() == 1 and false){
		$order = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT `id`, `bonus`, `bonus_flag`, `amount`, `status`, `customer_id`, `date_create` from ws_orders where `date_create` < ('".$today."' - INTERVAL " . (int) Config::findByCode('bonus_dey')->getValue() . " day)  and `status` = 8 and `bonus_flag` = 0 and `customer_id` = 8005");
		foreach ($order as $r) {
		 $bonus = $r->amount*0.1;
		 $c = new Customer($r->customer_id);
		 $bonusadd = $bonus + $c->getBonus();
		 $c->setBonus($bonusadd);
		 $c->save();
		 //$r->setBonusFlag(1);
		 $q = "UPDATE  `red_site`.`ws_orders` SET  `bonus_flag` =  '1' WHERE  `ws_orders`.`id` = ".$r->id;
		 $cnt_m = wsActiveRecord::query($q);
		 BonusHistory::add(8005, $r->customer_id, '+', $bonus, $r->id);
		}
		
		}*/
		
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
					//SendMail::getInstance()->sendSubEmail('php@red.ua', 'RED', "Start CRON ".$today, $mess);
		sendMessageTelegram(404070580, $mess);
	}
	//функция отправки сообщения в телеграм
	function sendMessageTelegram($chat_id, $message) {
  file_get_contents('https://api.telegram.org/bot'.Config::findByCode('telegram_key')->getValue().'/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
}	
		?>			
