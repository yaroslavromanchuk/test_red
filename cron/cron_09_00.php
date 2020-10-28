<?php
//09:00
require_once('cron_init.php');

function ucenca_id(){
    $proc = 15;
    $today = date("Y-m-d H:i:s");
    $sum = 0;
$all_sum = 0;
$all_count = 0;
$mes="Уценка {$today}\r\n";
//AND DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 35 DAY )
///10
$sql = "SELECT * FROM `red_site`.`ws_articles` WHERE  `stock` NOT LIKE  '0' and old_price = 0 and `code` IN ('82214', '82175', '82113', '83278', '83276')";
$i=0;
$j = 0;	
foreach(wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql) as $a){
if( $proc <= (int)$a->max_skidka or (int)$a->max_skidka <= 1){
$koll = $a->getCountArticles();
$a->setUcenka($proc);
$a->setDataUcenki($today);
    $a->setOldPrice($a->getPrice());

$a->setPrice(ceil($a->getPrice() * 0.7));
$cat = new Shopcategories($a->getCategoryId());
$a->setDopCatId($cat->getUsencaCategory());
$a->save();
$sum+= ($a->getOldPrice()-$a->getPrice())*$koll;
UcenkaHistory::newUcenka(8005, $a->getId(), $a->getOldPrice(), $a->getPrice(), $koll, $proc);
$i++;
$j+=$koll;
}else{
$a->setLabelid($proc);
$a->save();
}
}
$all_count+=$j;
$all_sum+=$sum;
$mes.=$proc." - ".$j." - ".$sum." грн.\r\n";
//Telegram::sendMessageTelegram(404070580, $mes);//Yarik
}
add_coin_birth(); 
//ucenca_id();
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

$summ = 0;
/*
$order = wsActiveRecord::useStatic('Shoporders')->findAll(['id'=> 422977, 'bonus_flag'=>0]);
if($order){ 
foreach ($order as $r) {
    $s = ceil($r->skidka/100*$r->amount);
    $summ += $s;
$array = [
    'coin' => $s,
    'customer_id' => $r->customer_id,
    'order_id_add' => $r->id,
    'date_add' => date("Y-m-d"),
    'date_off' => date("Y-m-d", strtotime("now +180 days"))
];
$coin = new RedCoin();
$coin->import($array);
$coin->save();
$q = "UPDATE  `red_site`.`ws_orders` SET  `bonus_flag` =  '1' WHERE  `ws_orders`.`id` = ".$r->id;
wsActiveRecord::query($q);
BonusHistory::add($r->customer_id, '+', $s, $r->id);
}
}*/
//$coin = wsActiveRecord::useStatic('RedCoin')->findAll(['status'=>1, 'coin > 0', 'date_active == '.date("Y-m-d")]);
/*
function add_coin(){
    $i = 0;
   $customer =  wsActiveRecord::useStatic('Customer')->findByQuery("SELECT *
FROM `ws_customers`
WHERE `id` NOT
IN (

SELECT `customer_id`
FROM `ws_red_coin`
)
AND `customer_status_id` IS NULL
LIMIT 0 , 2000");//
   foreach ($customer as $c) {
       switch ($c->real_skidka){
           case '0': $s = 50; break;
           case '5': $s = 100; break;
           case '10': $s = 200; break;
           case '15': $s = 300; break;
           case '20': $s = 300; break;
           default: $s= 50; break;
       }
               
       $coin = new RedCoin();
$coin->import([
    'coin' => $s,
    'customer_id' => $c->id,
    'status'=>1,
    'date_add' => '2020-03-01',
    'date_active' => '2020-03-01',
    'date_off' => '2020-05-30'
    ]);
$coin->save();
     $i++;  
   }
   return $i;
}
$summ = add_coin();
*/

//Telegram::sendMessageTelegram(404070580, 'Coin add: '.$summ);//Yarik
        






	
		
