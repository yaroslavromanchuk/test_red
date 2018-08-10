<?php 
header("Content-Type: text/html; charset=utf-8");
?>

<?php

//$html=simplexml_load_file('https://www.red.ua/articlelist/?id=106');
//var_dump($html->offers);
    //  foreach ($html->offers->offer as $item) {
	 //  echo $item->title.'<br>';
       // 	}



//require_once('ws_articles.php');
//require_once('ws_order_articles03.php');
//require_once('ws_cat.php');
require_once('parse_excel.php');
//$mass=array();




/*$path = 'phone_roz.xlsx';
$res = parse_excel_file($path);
$i = 0;
foreach($res as $b){
$p = substr(preg_replace('~\D+~','',$b[0]), -9);
$p = '+380'.$p;
if(strlen($p) == 13) echo $p.'<br>'; //$mass[] = $p;
$i++;
//if($i == 100) break;
}
*/
//echo save_excel_file($mass, 'phone');
/*
foreach($mas as $a => $k){
$r_ok[] = array('x'=> $a, 'u_20' =>@$k[20]?$k[20]:0,  'u_30'=>@$k[30]?$k[30]:0, 'u_40'=>@$k[40]?$k[40]:0,'u_50'=>@$k[50]?$k[50]:0,'u_60'=>@$k[60]?$k[60]:0);
		   }
echo '<pre>';
echo print_r($r_ok);
echo '</pre>';*/
//$path = 'list_end.xlsx';
//$res = parse_excel_file($path);
if(false{
$path = 'list_end_f.xlsx';
$res = parse_excel_file($path);
echo '<table>';
foreach($res as $b){


$sql = "SELECT `ws_articles`.id, `ws_articles`.`category_id` FROM `ws_articles`
inner join `ws_articles_sizes` on `ws_articles`.`id` = `ws_articles_sizes`.`id_article`
 WHERE `ws_articles_sizes`.`code` like '".trim($b[0])."' GROUP BY  `ws_articles`.`id`";
//$a = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
$a = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array("code LIKE'".trim($b[0])."'"));
if($a){
//foreach($articles as $a){
echo '<tr>';
 echo '<td>'.$a->article_rod->category->getRoutezGolovna().'</td><td>'.$a->article_rod->category->name.'</td><td>'.$b[0].'</td><td>'.$b[1].'</td>';
echo '</tr>';
//}
}
}
echo '</table>';
}
if(false){
$path = 'list_end.xlsx';
$res = parse_excel_file($path);
$i=0;
$mass = array();
echo '<table>';
foreach($res as $b){
//echo $b[0].'-'.$b[1].'<br>';

$articles = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array(" code LIKE'".trim($b[0])."' ", ' `count` >'.(int)$b[1]));
//$articles = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array(" code LIKE'".trim($b[0])."' ", ' `count` > 0'));
if($articles){
//$tmp = 0;

//$tmp = $tmp - 2;
$articles->setFlag($articles->getCount());
$tmp = $articles->getCount()-(int)$b[1];
echo '<tr>';
// echo '<td>'.$articles->getCode().'</td><td>'.$articles->getCount().'</td>';
 echo '<td>'.$articles->getCode().'</td><td>'.$tmp.'</td>';

//$articles->setFlag($tmp);
//$articles->setCount(0);
$articles->setCount((int)$b[1]);
//
$articles->save();

$i++;
echo '</tr>';
}
//if($i > 10) break;
}
echo '</table>';
//echo '<pre>';
//echo print_r($mass);
//echo '</pre>';
//save_excel_file($mass);
}
//require_once('up/UkrPostAPI.php');
	//$api = new UkrPostAPI('f9027fbb-cf33-3e11-84bb-5484491e2c94', 'ba5378df-985e-49c5-9cf3-d222fa60aa68', '2304bbe5-015c-44f6-a5bf-3e750d753a17', false, 'POST');
	
	//$r = $api->getInfo();
	//$r = $api->getNewClient();
	//$r = $api->getBarcode('0500004910314');
//header('Cache-Control: public'); 
//header('Content-type: application/json');
//header('Content-Disposition: attachment; filename="new.json"');
//header('Content-Length: '.strlen($Result));
//echo print_r($Result);
//echo '<pre>';
//echo print_r($r);
//echo '</pre>';
$mas = array();
//$s= 0;
//echo save_excel_file($ws_order_articles, 'brand');
/*
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
*/
/*
foreach($ws_articles as $a){
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `stock` >0
AND  `old_price` = 0
AND  `brand_id` =".$a['brand_id'];
$articles_0 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
if($articles_0 )
$s+=$articles_0;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `old_price` >0
and `stock` > 0
AND ( 100 - (  `price` /  `old_price` *100 ) ) < 20
AND  `brand_id` = ".$a['brand_id'];
$articles_20 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_20;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `old_price` >0
and `stock` > 0
AND ( 100 - (  `price` /  `old_price` *100 ) >= 20 and  100 - (  `price` /  `old_price` *100 ) < 25 )  
AND  `brand_id` = ".$a['brand_id'];
$articles_25 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_25;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `old_price` >0
and `stock` > 0
AND ( 100 - (  `price` /  `old_price` *100 ) >= 25 and  100 - (  `price` /  `old_price` *100 ) < 30 )  
AND  `brand_id` = ".$a['brand_id'];
$articles_30 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_30;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `old_price` >0
and `stock` > 0
AND ( 100 - (  `price` /  `old_price` *100 ) >= 30 and  100 - (  `price` /  `old_price` *100 ) < 35 )  
AND  `brand_id` = ".$a['brand_id'];
$articles_35 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_35;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `old_price` >0
and `stock` > 0
AND ( 100 - (  `price` /  `old_price` *100 ) >= 35 and  100 - (  `price` /  `old_price` *100 ) < 40 )  
AND  `brand_id` = ".$a['brand_id'];
$articles_40 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_40;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `old_price` >0
and `stock` > 0
AND ( 100 - (  `price` /  `old_price` *100 ) >= 40 and  100 - (  `price` /  `old_price` *100 ) < 45 )  
AND  `brand_id` = ".$a['brand_id'];
$articles_45 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_45;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `old_price` >0
and `stock` > 0
AND ( 100 - (  `price` /  `old_price` *100 ) >= 45 and  100 - (  `price` /  `old_price` *100 ) < 50 )  
AND  `brand_id` = ".$a['brand_id'];
$articles_50 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_50;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `old_price` >0
and `stock` > 0
AND ( 100 - (  `price` /  `old_price` *100 ) >= 50 and  100 - (  `price` /  `old_price` *100 ) < 55 )  
AND  `brand_id` = ".$a['brand_id'];
$articles_55 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_55;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `old_price` >0
and `stock` > 0
AND ( 100 - (  `price` /  `old_price` *100 ) >= 55 and  100 - (  `price` /  `old_price` *100 ) < 60 )  
AND  `brand_id` = ".$a['brand_id'];
$articles_60 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_60;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `old_price` >0
and `stock` > 0
AND ( 100 - (  `price` /  `old_price` *100 ) >= 60 and  100 - (  `price` /  `old_price` *100 ) < 65 )  
AND  `brand_id` = ".$a['brand_id'];
$articles_65 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_65;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `old_price` >0
and `stock` > 0
AND ( 100 - (  `price` /  `old_price` *100 ) >= 65 and  100 - (  `price` /  `old_price` *100 ) < 70 )  
AND  `brand_id` = ".$a['brand_id'];
$articles_70 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_70;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
WHERE  `old_price` >0
and `stock` > 0
AND ( 100 - (  `price` /  `old_price` *100 ) >= 70 and  100 - (  `price` /  `old_price` *100 ) < 75 )  
AND  `brand_id` = ".$a['brand_id'];
$articles_75 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_75;
$sql = "SELECT IF( SUM(  `stock` ) IS NULL , 0, SUM(  `stock` ) ) AS ctn
FROM  `ws_articles` 
inner join 
WHERE  `old_price` >0
and `stock` > 0
AND  100 - (  `price` /  `old_price` *100 ) >= 75  
AND  `brand_id` = ".$a['brand_id'];
$articles_80 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
$s+=$articles_80;

$mas[] = array(
'brand_id'=>$a['brand_id'],
'brand'=>$a['brand'],
'20'=>$articles_0+$articles_20,
'25'=>$articles_25,
'30'=>$articles_30,
'35'=>$articles_35,
'40'=>$articles_40,
'45'=>$articles_45,
'50'=>$articles_50,
'55'=>$articles_55,
'60'=>$articles_60,
'65'=>$articles_65,
'70'=>$articles_70,
'75'=>$articles_75,
'80'=>$articles_80
 );

}*/
/*
foreach($mas as $m){
echo $m['brand_id'].' - '.$m['brand'].' - '.$m['20'].' - '.$m['25'].' - '.$m['30'].' - '.$m['35'].' - '.$m['40'].' - '.$m['45'].' - '.$m['50'].' - '.$m['55'].' - '.$m['60'].' - '.$m['65'].' - '.$m['70'].' - '.$m['75'].'<br>';
}*/

//echo save_excel_file($mas, 'brand');
//echo $s;





/*
$sql = "SELECT  `customer_id`, count(`customer_id`) as ctn 
FROM  `ws_orders` 
WHERE  `date_create` >  '2017-06-01 00:00:00'
AND  `delivery_type_id` =12
GROUP BY  `customer_id` 
HAVING COUNT(  `customer_id` ) >1";
$order = wsActiveRecord::useStatic('Shoporders')->findByQuery($sql);
$mas = array();
foreach($order as $c){
$mas[$c->customer_id]['12'] = $c->ctn;
}
$sql1 = "SELECT  `customer_id`, count(`customer_id`) as ctn 
FROM  `ws_orders` 
WHERE  `date_create` >  '2017-06-01 00:00:00'
AND  `delivery_type_id` = 3
GROUP BY  `customer_id` 
HAVING COUNT(  `customer_id` ) >1";
$order1 = wsActiveRecord::useStatic('Shoporders')->findByQuery($sql1);
foreach($order1 as $c){
$mas[$c->customer_id]['3'] = $c->ctn;
}
$sql1 = "SELECT  `customer_id`, count(`customer_id`) as ctn 
FROM  `ws_orders` 
WHERE `delivery_type_id` = 5
GROUP BY  `customer_id`";
$order1 = wsActiveRecord::useStatic('Shoporders')->findByQuery($sql1);
foreach($order1 as $c){
$mas[$c->customer_id]['5'] = $c->ctn;
}
foreach($mas as $k=>$v){
$max = array_keys($v, max($v));
//$m = $v[12];
//$p = $v[3];
//$s = $v[5];
$mas2[$k] = $max;
}*/

/*$sql = "SELECT c.*, COUNT( r.id ) AS ctn
FROM  `ws_customers` c
INNER JOIN  `ws_orders` r ON c.`id` = r.customer_id
WHERE c.`default_delivery_address_id` =12
AND r.`delivery_type_id` 
IN ( 4, 5, 9, 8, 16 ) 
AND r.`date_create` >  '2018-01-24 00:00:00'
GROUP BY c.id";*/

//$customer = wsActiveRecord::useStatic('Customer')->findAll(array('bonus > 0'));
//foreach($customer as $c){
//$t = preg_replace('~[^0-9]+~','',$c->getPhone1()); 
//$c->setPhone1($t);
//$c->setTimeZoneId(4);
//$c->setBonus(0);
//$c->save();
//}
//echo '<pre>';
//echo print_r($mas2);
//echo '</pre>';


/*
$order = wsActiveRecord::useStatic('Amazonorderarticles')->findAll(array('order_id'=>3));
foreach ($order as $r) {
$or = wsActiveRecord::useStatic('Amazon')->findFirst(array("asin LIKE  '".$r->asin."' "));
$r->setLink($or->link);
$r->save();
}
*/
//$model = 'eeeeeeeeeeeeeeeeeee';
//echo $model;
//$sum = 0;
//$dir    = $_SERVER['DOCUMENT_ROOT'] . '/files/org';
//$files1 = scandir($dir);

//foreach ($files1 as $f) {
//echo $f.'<br>';
//}


/*
$articles = wsActiveRecord::useStatic('Shoporderarticles')->findAll(array("title LIKE  '%GUESS%' "));
foreach ($articles as $a) {
$output  = str_replace('GUESS','RED', $a->title);
echo $a->title.'->'.$output.'<br>';
$sum++;
$a->setTitle($output);
$a->save();
}*/
/*
$articles = wsActiveRecord::useStatic('Shoparticles')->findAll(array('category_id'=>16 ));

$flag = 0;
echo 'Найдено товаров - '.$articles->count().'<br>';
foreach ($articles as $a) {
$articl = wsActiveRecord::useStatic('Shoparticlessize')->findAll(array('id_article' =>$a->getId()));
 foreach ($articl as $c) {
 //echo $c->code.' - '.$c->count.'<br>';
 $sum = $sum+$c->count;
 if($c->count > 0){
 
									$log = new Shoparticlelog();
                                    $log->setCustomerId(8005);
                                    $log->setUsername('programmer');
                                    $log->setArticleId($a->getId());
									if ($c->getIdSize() and $c->getIdColor()) { 
									$size = new Size($c->getIdSize());
									$color = new Shoparticlescolor($c->getIdColor());
                                        $log->setInfo($size->getSize() . ' ' . $color->getName());
                                    }
									$log->setTypeId(2);
									$log->setCount($c->getCount());
									$log->setComents('Удаление в несезон');
									$log->setCode($c->getCode());
									$log->save();
                $c->setCount(0);
                $c->save();
				$flag = 1;
				}
 }
		if($flag == 1){
			$a->setStock(0);
			$a->save();
			}
			$flag = 0;

 }*/
 //echo '<br>Удалено (единиц) - '.$sum.'<br>';

/*
$a =  wsActiveRecord::useStatic('Shoporderarticles')->findAll(array('artikul  IS NULL '), array('id'=>'DESC'), array(1000));
//echo $a->count();

$q =0;
foreach ($a as $k) {
$value = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' =>$k->article_id, 'id_size' =>$k->size, 'id_color' =>$k->color));
//echo $value->code.'<br>';
if(@$value){
$k->setArtikul($value->code);
$k->save();
$q++;
}
}
echo $q;
*/
?>
<form action="parse_excel.php" method="POST"  enctype="multipart/form-data">
<div class="form-layout">
            <div class="row mg-b-25">
			 <div class="col-lg-3 mg-t-40 mg-lg-t-0">
              <label class="custom-file">
                <input type="file" name="excel_file" class="custom-file-input">
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
            </div>
            
            </div>

            <div class="form-layout-footer">
              <button class="btn btn-info mg-r-5" name="save" type="submit">Загрузить</button>
              <button class="btn btn-secondary">Очистить</button>
            </div>
		  </form>


