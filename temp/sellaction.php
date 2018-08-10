<?php
header("Content-Type: text/html; charset=utf-8");
require_once('../cron_init.php');
require_once('parse_excel.php');

$path = 'list_sellection.xlsx';
$res = parse_excel_file($path);
$mas = array();
$mas[100] = 'ожидание'; 
$mas[1] = 'ожидание'; 
$mas[2] = 'отменён'; 
$mas[3] = 'ожидание'; 
$mas[4] = 'Отправлен Укр.почта'; 
$mas[5] = 'отменён'; 
$mas[6] = 'Отправлен Новая почта'; 
$mas[7] = 'отменён'; 
$mas[8] = 'оплачен'; 
$mas[9] = 'ожидание'; 
$mas[10] = 'ожидание'; 
$mas[11] = 'ожидание'; 
$mas[12] = 'ожидание'; 
$mas[13] = 'ожидание'; 
$mas[14] = 'оплачен'; 
$mas[15] = 'ожидание'; 
$mas[16] = 'ожидание'; 
$mas[17] = 'отменён'; 
$count = count($res);
//echo $count.'<br>';
if($count > 0){
$i = 0;
$mass = array();
echo '<table>';
foreach ($res as $k => $l) {
$or = wsActiveRecord::useStatic('Shoporders')->findById($l[0]);
if($or){
echo '<tr><td>'.$l[0].'</td><td>'.($or->amount+$or->deposit).'</td><td>'.$mas[$or->status].'</td></tr>';
//echo $mas[$or->status].'<br>';
//echo $l[0].' - '.($or->amount+$or->deposit).' -  '.$mas[$or->status].'<br>';
//$mass[$i] = array('id' => $l[0], 'sum' => $or->amount+$or->deposit, 'status' => $mas[$or->status]); 
$i++;
}
}
echo '</table>';
//if(count($mass) > 0)  echo '<pre>'; echo print_r($mass); echo '</pre>';//echo save_excel_file($mass);
}
?>