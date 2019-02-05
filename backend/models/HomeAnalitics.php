<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HomeAnalitics
 *
 * @author PHP
 */
class HomeAnalitics extends wsActiveRecord{
   
     public static function sendPost($post = [], $get = []){
         switch ($post->method) {
                case 'ucenka_2': return self::ucenka_2();
                case 'ucenka': return self::ucenka();
                case 'ostatki': return self::ostatki();
                case 'konversiya': return  self::googleanalitics($post->from, $post->to);
                case 'delivery': return  self::swithDelivery($post);
                case 'shop': return  self::swithShop($post);
                case 'order': return  self::orders($post);
                case 'prognoz': return self::prognoz($post);
                    
             default:
                 break;
         }
         
     }
     public static function sendGet($get = []){
         switch ($get->method) {
                case 'balance_brand_to_excel': return self::balance_brand_to_excel($get);
                case 'balance_to_excel': return self::balance_to_excel($get);
             default:
                 break;
         }
         
     }
     private static function prognoz($post) {
         return Bufer::getNorma($post);
     }
     
     private static function ucenka_2(){
         $sql = "SELECT  `ws_articles`.`ucenka` , SUM(  `ws_articles_sizes`.`count` ) AS ctn
FROM  `ws_articles` 
INNER JOIN  `ws_articles_sizes` ON  `ws_articles`.`id` =  `ws_articles_sizes`.`id_article` 
WHERE  `ws_articles_sizes`.`count` > 0
and `ws_articles`.status  = 3
GROUP BY  `ws_articles`.`ucenka` ";
$ucenka = wsActiveRecord::findByQueryArray($sql);
$s = 0;
foreach($ucenka as $c){
$result[$c->ucenka] = $c->ctn;
$s+=$c->ctn;
}
$result['sum'] = $s;

	
         return $result;
     }
     private static function ucenka(){
         
         $sql="SELECT DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) AS dat
FROM  `ucenka_history` 
WHERE DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 60 DAY ) 
GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ";
$mas = [];
$i=0;
foreach(wsActiveRecord::findByQueryArray($sql) as $c){
$sql = "SELECT  `proc` , SUM(  `koll` ) AS ctn FROM   `ucenka_history` WHERE  DATE_FORMAT( `ctime` ,  '%Y-%m-%d' ) =  '".$c->dat."' GROUP BY  `proc` ";
$mas[$i]['x'] = $c->dat;

foreach(wsActiveRecord::findByQueryArray($sql) as $t){
$mas[$i][$t->proc] = $t->ctn?$t->ctn:0;
}
$i++;
}
return $mas;
     }
     private static function ostatki(){
         $type = 0;
          $ok = wsActiveRecord::findByQueryArray("
                SELECT DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) AS dat, SUM(  `ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 30 
DAY ) 
GROUP BY  `ws_balance`.`id` 
ORDER BY  `dat` ASC   ");
		   foreach($ok as $k){
                       $r_ok['x'][] = $k->dat;
                       $r_ok['y'][] = (int)$k->ctn;
		   // $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn);
		   }
		    return $r_ok;
     }
     private static function visit(){
          $res = [];
                $ok2 = wsCustomerVisit::findByQueryArray("SELECT COUNT(  `id` ) AS  `visit` , SUM(  `total_number_of_pages` ) AS  `page` , SUM( IF(  `total_number_of_pages` =1, 1, 0 ) ) AS otkaz, DATE_FORMAT(  `ctime` ,  '%d.%m' ) AS  `dat` , DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) AS  `dat_p` 
FROM  `ws_visits` 
WHERE (customer_id NOT IN(1,2993,7341,7668,24150,26187,34608,34655,35971,36149,36431,37449,8005,24148,29397,36213,37075,22844,23029,27804,36399,37183,37307,22832,33929,20336,37484) OR  `customer_id` IS NULL
)
GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' )");
                foreach ($ok2 as $key => $value) {
                    $res[$value->dat]['visit'] = $value->visit; 
                    $res[$value->dat]['page'] = $value->page; 
                    $res[$value->dat]['glubina'] = round(($value->page/$value->visit), 2); 
                    $res[$value->dat]['otkaz'] = round((($value->otkaz/$value->page)*100), 2);
                   // $dat = date('Y-m-d', strtotime($value->dat.'.2018'))$value->dat.
                $s_z =    OrderHistory::findByQueryFirstArray("SELECT count(`order_id`) as `ctn` FROM  `order_history` WHERE  `name` LIKE  '%Заказ оформлен%' and DATE_FORMAT(`ctime` ,  '%Y-%m-%d' ) = '$value->dat_p' ")['ctn'];
                $res[$value->dat]['konvers'] = round((($s_z/$value->visit)*100) , 2);
                }
                
                return $res;
     }
      private static function googleanalitics($from = '', $to = ''){
       // return $from;
        if($from == ''){$from = date('Y-m-d'); }
        if($to == ''){$to = date('Y-m-d'); }
       // $from = (string)"'".$from."'";
       // $to  = (string)"'".$to."'";
        require_once('Google/HelloAnalytics.php');
        
        $analytics = initializeAnalytics();
        
        $results = $analytics->data_ga->get('ga:57394917', $from, $to, 'ga:sessions, ga:users , ga:newUsers, ga:bounceRate,  ga:pageviews, ga:pageviewsPerSession');
        
        if (count($results->getRows()) > 0) {
    // Get the entry for the first entry in the first row.
    $res = $results->getRows()[0];
    $text['sessions'] = $res[0];
    $text['users'] = $res[1];
    $text['newUsers'] = $res[2];
    $text['otkaz'] = round($res[3], 2);
    $text['pageviews'] = $res[4];
    $text['pageviewsPerSession'] = round($res[5], 2);
    $sql = "SELECT count(`order_id`) as `ctn` FROM  `order_history` WHERE  `name` LIKE  '%Заказ оформлен%' and DATE_FORMAT(`ctime` ,  '%Y-%m-%d' ) >= '$from'  and  DATE_FORMAT(`ctime` ,  '%Y-%m-%d' ) <= '$to' ";
   // var_dump($sql, false);
    $k = OrderHistory::findByQueryFirstArray($sql)['ctn'];
 $text['konvers'] = round(($k/$res[0])*100, 2);
 
 
 $now =  strtotime($from); // текущее время (метка времени)
$your_date = strtotime($to); // какая-то дата в строке (1 января 2017 года)
$datediff = $your_date - $now; // получим разность дат (в секундах)
$text['dney'] = floor($datediff / (60 * 60 * 24))+1;
    $result = $text;
    //echo print_r($result);
    //$sessions = $rows[0][0];
  } else {
    $result = "No results found.\n";
  }
        return $result;
    } 
    
   private static function  swithDelivery($post){
        
       switch ($post->type) {
           case '35' :
                        $ok = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` 
IN ( 3, 5 ) 
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
			return $ok;
                      
                    case '4' :
                        $ok = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` =4
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
			return $ok;
                    case '816' :
                        $ok = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` 
IN ( 8, 16 ) 
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
			return $ok;
                    case '9' :
                        $ok = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` = 9
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
			return $ok;
        default:
             $ok_m = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` 
IN ( 3, 5 ) 
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
             $ok_up = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` =4
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
             $ok_np = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` 
IN ( 8, 16 ) 
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
              $ok_k = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` = 9
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
            
            return ['m'=>$ok_m, 'up'=>$ok_up, 'np'=>$ok_np, 'k'=>$ok_k];
       }
   
    }
    
    private static function  swithShop($post){
        
        switch ($post->type) {
	case 'h_a' ://
$ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(IF(`ws_order_articles`.`count` =0, 1,`ws_order_articles`.`count`)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) 

GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d %H' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) 
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d %H' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) 
AND `ws_orders`.`status`  in(2,7,15)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d %H' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[(int)$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[(int)$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[(int)$k->dat] ? $p[(int)$k->dat] : 0, 'ret'=>$re[(int)$k->dat] ? $re[(int)$k->dat] : 0 );
		   }
		   
			return $r_ok;
			case 'n_d_a' :
$ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY )
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND `ws_orders`.`status`  in(2,7)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[$k->dat] ? $p[$k->dat] : 0, 'ret'=>$re[$k->dat] ? $re[$k->dat] : 0 );
		   }
		   
			return $r_ok;
        case 'n_h_a':
            $ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY )
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status`  in(2,7)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[$k->dat] ? $p[$k->dat] : 0, 'ret'=>$re[$k->dat] ? $re[$k->dat] : 0 );
		   }
            
            return $r_ok;
                        
			case 'm_d_a' :
$ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status`  in(2,7)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[$k->dat] ? $p[$k->dat] : 0, 'ret'=>$re[$k->dat] ? $re[$k->dat] : 0 );
		   }
		   
			return $r_ok;
        case 'm_h_a':
            $ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status`  in(2,7)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[$k->dat] ? $p[$k->dat] : 0, 'ret'=>$re[$k->dat] ? $re[$k->dat] : 0 );
		   }
		   
			return $r_ok;
	default : 
 
 
			return $r_ok;
        }
    }
    private static function orders($post) {
        switch ($post->type) {
            case 'h' : 
			$ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%H' ) AS dat, COUNT(  `id` ) AS ctn FROM  `ws_orders` WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) AND status NOT IN (5,7,17) GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d %H' ) ORDER BY  `dat` ASC ");
		   foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->ctn, $type => 0 );
		   }
		     return $r_ok;
            case 'm_h' : 
			$ok = 	wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%H' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND status NOT IN (5,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");
		   foreach($ok as $k){
		    $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->ctn, $type => 0 );
		   }
		     return $r_ok;
			 case 'm_d' : 
			$ok = 	wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND status NOT IN (5,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
		   foreach($ok as $k){
		    $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn, $type => 0 );
		   }
		     return $r_ok;
			case 'n_h' :
			$ok = 	wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%H' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 
DAY ) 
AND status NOT IN (5,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");
		
		   foreach($ok as $k){
		    $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->ctn, $type => 0 );
		   }
			 return $r_ok;
			 case 'n_d' :
			$ok = 	wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 
DAY ) 
AND status NOT IN (5,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
		   foreach($ok as $k){
		    $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn, $type => 0 );
		   }
			 return $r_ok;
		case 'dely':
		$days_arr_dely = array();
	$days_dely = wsActiveRecord::findByQueryArray("SELECT COUNT(  `ws_orders`.`id` ) AS ctn,  `ws_orders`.`delivery_type_id` 
FROM  `ws_orders` 
WHERE  STATUS IN ( 100, 9, 15,16 ) 
AND  `ws_orders`.`delivery_type_id` !=0 and `ws_orders`.`delivery_type_id` !=12
GROUP BY  `ws_orders`.`delivery_type_id` 
ORDER BY  `ctn` ASC");
$mas = array(3=>'Победа', 4=>'Укр.Почта', 5=>'Строителей', 8=>'НП:ОО', 9=>'Курьер', 16=>'НП:НП');
	foreach($days_dely as $d){
	$days_arr_dely['name'][] = $mas[$d->delivery_type_id];
	$days_arr_dely['koll'][] = (int)$d->ctn;
}
	return $days_arr_dely;
	case 'status':
	$days_arr_status = array();
	$days_status = wsActiveRecord::findByQueryArray("SELECT COUNT(  `ws_orders`.`id` ) AS ctn,  `ws_orders`.`status` 
FROM  `ws_orders` 
WHERE `ws_orders`.`status` IN (100,1,9,15,16) and `ws_orders`.`from_quick` != 1
GROUP BY  `ws_orders`.`status` 
ORDER BY  `ws_orders`.`status` ASC");
        
$mas = array(100=>'Новый', 1=>'В процесе', 2=>'Отменён', 8=>'Оплачен', 9=>'Собран', 10=>'Продлён клиентом', 12=>'Ждёт возврат', 15=>'Собран 2', 16=>'Собран 3' );
$color = array(0=>'#4c4fd2',1=>'#d7da5c',2=>'#677489',8=>'#5B93D3',9=>'#37da3d', 15=>'#37c2da', 16=>'#dac037');
	foreach($days_status as $d){
	$days_arr_status[] = array('label'=>$mas[$d->status], data=>array(1,(int)$d->ctn), 'color'=>$color[$d->status]);
}
	
	return $days_arr_status;
	case 'quick':
	//$quick_arr = array();
	$quick = wsActiveRecord::findByQueryArray("SELECT  `status` , COUNT(  `id` ) AS  `ctn` 
FROM  `ws_orders` 
WHERE  `from_quick` = 1
AND  `status` != 17 
AND  `quick` = 1
AND DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
GROUP BY  `status` ");
            
$mas = array(100=>'Новый', 1=>'В процесе', 2=>'Отменён', 8=>'Оплачен', 9=>'Собран', 10=>'Продлён клиентом', 12=>'Ждёт возврат', 15=>'Собран 2', 16=>'Собран 3' );
$color = array(0=>'#4c4fd2',1=>'#d7da5c',2=>'#677489',8=>'#5B93D3',9=>'#37da3d', 15=>'#37c2da', 16=>'#dac037');
	foreach($quick as $d){
	$days_arr_status[] = array('label'=>$mas[$d->status], data=>array(1,(int)$d->ctn), 'color'=>$color[$d->status]);
}
	
	return $days_arr_status;
            default : 
			$ok = "SELECT DATE_FORMAT(  `date_create` ,  '%Y-%m-%d %H' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) 
AND status NOT IN (5,7,17) 
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d %H' ) 
ORDER BY  `dat` ASC ";
			
		   
		   foreach(wsActiveRecord::findByQueryArray($ok) as $k){
		    $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn, $type => 0 );
		   }
		    return $r_ok;
		   }
        
    }
    public static function createOrder(){
       
             /**
            * оформленные заказы
            */        
	  $days_arr = [];
          $week_arr =[];
          $month_arr = [];
          $year_arr = [];
          
	$days = wsActiveRecord::findByQueryArray(""
        . "SELECT DATE_FORMAT(  `ctime` ,  '%H' ) AS dat, SUM(  `sum_order` ) AS money "
        . "FROM  `order_history`  "
        . "WHERE DATE_FORMAT(  `ctime` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) "
        . "AND `name` LIKE  'Заказ оформлен'"
        . "GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m-%d %H' ) "
        . "ORDER BY  `dat` ASC ");
        $s = 0;
	foreach($days as $d){
            $s += $d->money;
	$days_arr['koll'][] = $d->money;
}
$days_arr['summa'] = $s;
	
	$week = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ctime` ,  '%d' ) AS dat, SUM(  `sum_order` ) AS money
FROM   `order_history` 
WHERE  DATE_FORMAT(  `ctime` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND `name` LIKE  'Заказ оформлен' 
GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
 $s = 0;
	foreach($week as $d){
            $s += $d->money;
	$week_arr['koll'][] = $d->money;
}
$week_arr['summa'] = $s;

$month = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) AS dat, SUM(  `sum_order` ) AS money
FROM   `order_history` 
WHERE DATE_FORMAT(  `ctime` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `name` LIKE  'Заказ оформлен'
GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
        $s = 0;
	foreach($month as $d){
            $s += $d->money;
	$month_arr['koll'][] = $d->money;
}
$month_arr['summa'] = $s;

$year = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ctime` ,  '%Y-%m' ) AS dat, SUM(  `sum_order` ) AS money
FROM  `order_history`  
WHERE DATE_FORMAT(  `ctime` ,  '%Y' ) = DATE_FORMAT( NOW( ) ,  '%Y' ) 
AND `name` LIKE  'Заказ оформлен'
GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m' ) 
ORDER BY  `dat` ASC ");
        $s = 0;
	foreach($year as $d){
            $s += $d->money;
	$year_arr['koll'][] = $d->money;
}
$year_arr['summa'] = $s;
return  ['year'=>$year_arr, 'month' =>$month_arr, 'week'=> $week_arr, 'days'=> $days_arr];
    }
    
    public static function paymentOrder(){
                /**
         * оплеченые заказы
         */
$days_arr_op = [];       
$week_arr_op = [];
$month_arr_op = [];
$year_arr_op = [];



$days_op = wsActiveRecord::findByQueryArray(""
        . "SELECT DATE_FORMAT(  `admin_pay_time` ,  '%H' ) AS dat,  SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit "
        . "FROM  `ws_orders`  "
        . "WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) "
        . " AND status IN (8,14) "
        . "GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d %H' ) "
        . "ORDER BY  `dat` ASC ");

foreach($days_op as $d){
	$days_arr_op['am'][$d->dat] = $d->money;
	$days_arr_op['dep'][$d->dat] = $d->deposit;
	$days_arr_op['koll'][] = $d->deposit+$d->money;
}

$week_op = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%d' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 
DAY ) 
AND status IN (8,14) 
GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
	foreach($week_op as $d){
	$week_arr_op['am'][$d->dat] = $d->money;
	$week_arr_op['dep'][$d->dat] = $d->deposit;
	$week_arr_op['koll'][] = $d->deposit+$d->money;
}

$month_op = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND status IN (8,14) 
GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
	foreach($month_op as $d){
	$month_arr_op['am'][$d->dat] = $d->money;
	$month_arr_op['dep'][$d->dat] = $d->deposit;
	$month_arr_op['koll'][] = $d->deposit+$d->money;
}

$year_op = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y' ) = DATE_FORMAT( NOW( ) ,  '%Y' ) 
AND status IN (8,14) 
GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m' ) 
ORDER BY  `dat` ASC ");
	foreach($year_op as $d){
	$year_arr_op['am'][$d->dat] = $d->money;
	$year_arr_op['dep'][$d->dat] = $d->deposit;
	$year_arr_op['koll'][] = $d->deposit+$d->money;
}

        return  ['year'=>$year_arr_op, 'month' =>$month_arr_op, 'week'=> $week_arr_op, 'days'=> $days_arr_op];
    }
    
    public static function comment(){
     return  wsActiveRecord::findByQueryArray("
    SELECT `ws_orders`.* 
    FROM  `ws_orders` 
    WHERE  `ws_orders`.`status` = 100
    AND  `id` NOT IN (
        SELECT  `ws_order_remarks`.`order_id` 
        FROM  `ws_order_remarks`
                    )
    AND  `customer_id` NOT IN (
            SELECT  `id` 
            FROM  `ws_customers` 
            WHERE  `customer_type_id` >1
                                )
    AND  `comments` !=  ''
    ORDER BY  `ws_orders`.`date_create` ASC");
     
    }
    public static function chek(){
        $ok = wsActiveRecord::findByQueryArray("
    SELECT DATE_FORMAT(  `date_create` ,  '%d.%m' ) AS dat, count(`id`) as `ctn`, SUM(  `amount`+ `deposit`) AS `summ`, id
    FROM  `ws_orders` 
    WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 30 DAY ) 
AND status != 17 
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `id` ASC ");
$m = [];
foreach ($ok as $value) {
    $m['label'][] = (int)($value->summ/$value->ctn);
    $m['date'][] = $value->dat;
}
return $m;
        
    }
    
    private static function balance_brand_to_excel($get){
        $res = Bufer::getToExcelBrand($get);
                 $br = [];
                foreach($res as $k => $v){
                    $br[$k] =  Bufer::prognozBrand($v);
                }
                $p = [];
                
                $p[0][0] = 'Бренд';
                $p[0][1] = 'Буфер';
                 $i = 1;
                foreach ($br as $ke => $value) {
                    if($value[0] != 0){
                    $p[$i][0] = wsActiveRecord::useStatic('Brand')->findById($ke)->name;
                    $p[$i][1] = $value[0];
                    $i++;
                    }
                }
                 $cat = wsActiveRecord::useStatic('Shopcategories')->findById((int)$get->cat_prognoz)->getRoutez();
                return ['name' =>$cat.'_'.date('d.m.Y',strtotime($get->from_prognoz)).'_'.date('d.m.Y', strtotime($get->to_prognoz)), 'paremetr'=>$p];
    }
    private static function balance_to_excel($get){
         $res = Bufer::getToExcelNorma($get);
                $p = [];
                
                $p[0][0] = 'Категория';
                $p[0][1] = 'Без грейда';
                $p[0][2] = 'Грейд 1';
                $p[0][3] = 'Грейд 2';
                $p[0][4] = 'Грейд 3';
                $p[0][5] = 'Грейд 4';
                $p[0][6] = 'Грейд 5';
                
                $p[0][7] = 'Общее';
                
                $gr = [];
                foreach($res as $k => $v){
                    $gr[$k] =  Bufer::prognozExcel($v);
                }
                $i = 1;
                foreach($gr as $k => $v){
                    //if($v['prognoz'] != 0){
                    $cat = new Shopcategories($k);
                    $p[$i][0] = $cat->getRoutez();
                    $p[$i][1] = $v[0];
                    $p[$i][2] = $v[1];
                    $p[$i][3] = $v[2];
                    $p[$i][4] = $v[3];
                    $p[$i][5] = $v[4];
                    $p[$i][6] = $v[5];
                    $p[$i][7] = ($v[0]+$v[1]+$v[2]+$v[3]+$v[4]+$v[5]);
                    $i++;
                    //}
                }
                 $cat = wsActiveRecord::useStatic('Shopcategories')->findById((int)$get->cat_prognoz)->getRoutez();
                return ['name' =>$cat.'_'.date('d.m.Y',strtotime($get->from_prognoz)).'_'.date('d.m.Y', strtotime($get->to_prognoz)), 'paremetr'=>$p];
    }
}
