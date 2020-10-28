<!doctype html>
<html id="html" >
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
<body>
<?php 
ini_set('display_errors',0);
require_once('../cron_init.php');
if(isset($_GET['sr'])){
$mass = array();
$subject = $_GET['sr'];
$order = substr($subject,-7, -1);
if (ctype_digit($order) and strlen($order) == 6){
 $or = wsActiveRecord::useStatic('Shoporders')->findById($order);
if($or){
foreach($or->getArticles() as $a){
echo '<img src="'.$a->getArticleDb()->getImagePath('listing').'"><br>';
echo $a->getTitle().' '.$a->sizes->size.'/'.$a->colors->name.'('.$a->count.')<br>';

}
echo 'Сумма заказа: '.$or->getAmount().'<br>';
}else{
echo 'Ощибка чтения заказа :'.$order;
}
exit;

}
//$pattern = '/^SR/';
//preg_match($pattern, substr($subject, 0, 2), $matches, PREG_OFFSET_CAPTURE);
//echo print_r($subject);
//echo $matches[0][0];
if(strlen(trim($_GET['sr'])) == 16 or strlen(trim($_GET['sr'])) == 14){
$o_stat = wsActiveRecord::useStatic('OrderStatuses')->findAll();
        $mas_os = [];
        foreach ($o_stat as $o) {
            $mas_os[$o->getId()] = $o->getName();
        }
 //$ar = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(['code' => $subject]);
 $ar =(object)wsActiveRecord::findByQueryFirstArray("SELECT * FROM `ws_articles_sizes` WHERE `code` LIKE '{$subject}'");
 if($ar){
$add = wsActiveRecord::findByQueryFirstArray("SELECT sum(count) as addsum FROM `red_article_log` WHERE type_id = 1 and `code` LIKE '{$subject}'")['addsum'];
//$add = $add;
$del = wsActiveRecord::findByQueryFirstArray("SELECT sum(count) as del FROM  `red_article_log` WHERE type_id = 2 and `code` LIKE '{$subject}'")['del'];
//$del = $del;

$order = wsActiveRecord::findByQueryFirstArray("SELECT sum(ws_order_articles.count) as sumaorder FROM ws_order_articles WHERE ws_order_articles.artikul LIKE '{$subject}'")['sumaorder'];
//$order = $order;
$return = wsActiveRecord::findByQueryFirstArray("SELECT sum(ws_order_articles_vozrat.count) as sum FROM ws_order_articles_vozrat WHERE ws_order_articles_vozrat.cod LIKE '{$subject}'")['sum'];
$orders = [];
if($order > 0 and false){
$orders = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT ws_orders.id, ws_orders.status , ws_order_articles.count, ws_order_articles.artikul, ws_order_articles.order_id   FROM ws_orders
INNER JOIN ws_order_articles ON ws_orders.id = ws_order_articles.order_id
WHERE ws_order_articles.artikul LIKE  '".$subject."' and ws_order_articles.count > 0 ");
}
  $articles = wsActiveRecord::useStatic('Shoparticles')->findById($ar->id_article);
  if($articles){
  $title = $articles->getBrand() . ' (' . $articles->getModel() . ')';
  $cat = $articles->category->getRoutez();
  $img = $articles->getImagePath('card_product');
  $art = $ar->code;
  $count = $ar->count;
  ?>
  <div  style="font-size: 24px; width: 100%; margin: auto; text-align: center">
 <span style="font-weight: bold"><?=$title?></span>
 <span style="font-size: 20px;"><?=$cat?></span><br>
 <img src="<?=$img?>" style="margin: auto;display: block;max-width: 100%;">
 <span style="color: red;font-weight: bold;"><?=$art?></span>
  </div>
 <table align="center" style="text-align: center;font-size: 16px;">
 <tr><th>Добавлено</th><th>Удалено</th><th>Возвраты</th><th>Заказано</th><th>Остаток</th><tr>
 <tr><td><?=$add?></td><td><?=$del?></td><td><?=$return?></td><td><?=$order?></td><td><?=$count?></td></tr>
 </table>

 <?php
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