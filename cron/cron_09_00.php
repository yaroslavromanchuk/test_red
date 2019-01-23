<?php


//error_reporting(E_ALL);
//ini_set('display_errors',1);

		$date_min = new DateTime("08:50"); // минимальное значение времени
       $date_max = new DateTime("10:00"); // максимальное значение времени
       $date_now = new DateTime();
if (/*$date_now >= $date_min && $date_now <= $date_max and */true) {
require_once('cron_init.php');
$today = date("Y-m-d H:i:s");
$days = array( 1 => 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота' , 'Воскресенье' );
//set_time_limit(600); 


	
if((int)Config::findByCode('bonus')->getValue() == 1 and false){
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
		
		
		sendMessageTelegram(404070580, 'zapusk 09-00');//Yarik

}//end if

	
?>			
