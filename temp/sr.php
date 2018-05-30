<!doctype html>
<html id="html" >
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
<body>
<?php 

require_once('../cron_init.php');
if(isset($_GET['sr'])){
$mass = array();
$subject = $_GET['sr'];
//echo  substr($subject,0, 2);
$pattern = '/^SR/';
preg_match($pattern, substr($subject, 0, 2), $matches, PREG_OFFSET_CAPTURE);
//print_r($matches);
//echo $matches[0][0];
if(strlen(trim($_GET['sr'])) == 16 and $matches[0][0] == 'SR'){
$o_stat = wsActiveRecord::useStatic('OrderStatuses')->findAll();
        $mas_os = array();
        foreach ($o_stat as $o) {
            $mas_os[$o->getId()] = $o->getName();
        }
 $ar = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('code' => $subject));
 if($ar){
 $add = wsActiveRecord::useStatic('Shoparticlelog')->findByQuery("SELECT sum(count) as addsum FROM  `red_article_log` WHERE type_id = 1 and `code` LIKE '".$subject."'")->at(0)->addsum;
//$add = $add;
$del = wsActiveRecord::useStatic('Shoparticlelog')->findByQuery("SELECT sum(count) as del FROM  `red_article_log` WHERE type_id = 2 and `code` LIKE '".$subject."'")->at(0)->del;
//$del = $del;
$order = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT  sum(ws_order_articles.count) as sumaorder FROM ws_order_articles
	WHERE ws_order_articles.artikul LIKE  '".$subject."'")->at(0)->sumaorder;
//$order = $order;
$return = wsActiveRecord::useStatic('ShoporderarticlesVozrat')->findByQuery("SELECT  sum(ws_order_articles_vozrat.count) as sum FROM ws_order_articles_vozrat WHERE ws_order_articles_vozrat.cod LIKE  '".$subject."'")->at(0)->sum;
$orders = array();
if($order > 0 and false){
$orders = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT ws_orders.id, ws_orders.status , ws_order_articles.count, ws_order_articles.artikul, ws_order_articles.order_id   FROM ws_orders
INNER JOIN ws_order_articles ON ws_orders.id = ws_order_articles.order_id
WHERE ws_order_articles.artikul LIKE  '".$subject."' and ws_order_articles.count > 0 ");
}
  $articles = wsActiveRecord::useStatic('Shoparticles')->findById($ar->id_article);
  if($articles){
  $title = $articles->getBrand() . ' (' . $articles->getModel() . ')';
  $cat = $articles->category->getRoutez();
  $img = $articles->getImagePath('detail');
  $art = $ar->code;
  $count = $ar->count;
  
   echo '<p align="center" style="font-size: 24px;">
 <span style="color: green;font-weight: bold">'.$title.'</span><br>
 <span style="font-size: 20px;">'.$cat.'</span><br>
 <img src="'.$img.'" style="    width: 80%;">
 <span style="color: red;font-weight: bold;">'.$art.'</span></p>
 <table align="center" style="text-align: center;font-size: 16px;">
 <tr><th>Добавлено</th><th>Удалено</th><th>Возвраты</th><th>Заказано</th><th>Остаток</th><tr>
 <tr><td>'.$add.'</td><td>'.$del.'</td><td>'.$return.'</td><td>'.$order.'</td><td>'.$count.'</td></tr>
 </table>
 ';
 
  if($orders){
 echo '<p align="center">';
 echo '<span style="    font-size: 18px;
    font-weight: bold;">Заказы</span><br>';
 foreach ($orders as $x) {
 //print_r($x);
 echo '<span>'.$x->id.' - '.$mas_os[$x->status].'</span><br>';
 }
 echo '</p>';
 }
  }else{
 echo '<p align="center" style="font-size: 24px;">Ошибка чтения id товара('.$ar->id_article.')</p>';
 
 }
 }else{
 echo '<p align="center" style="font-size: 24px;">Размер ненайден('.$subject.')</p>';
 
 }

 }else{
 echo '<p align="center" style="font-size: 24px;">Ошибка чтения штрихкода('.$subject.')</p>';
 
 }

 
 

 //print_r($mass);
 }

 
?>
</body>
</html>