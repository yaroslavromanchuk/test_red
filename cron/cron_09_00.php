<?php
require_once('cron_init.php');

//CustomersSegment();
//UcenkaGo();
//articleNoSkidkaBlock();
/*
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' AND  `active` =  'y' AND `ucenka` = 10 and sezon = 1";
$i=0;
$j = 0;	
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
$koll = $a->getCountArticles();
$s_p = $a->getPrice();
$a->setUcenka(20);
$a->setDataUcenki($today);
//$a->setOldPrice($a->getPrice());
$a->setPrice(ceil($a->getOldPrice() * 0.8));
//$cat = new Shopcategories($a->getCategoryId());
//$a->setDopCatId($cat->getUsencaCategory());
$a->save();
UcenkaHistory::newUcenka(8005, $a->getId(), $s_p, $a->getPrice(), $koll, 20);
$i++;
$j+=$koll;
}
*/

Telegram::sendMessageTelegram(404070580, '09:00');//Yarik
        






	
		
