<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Реестр Курьер</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script src="<?=$this->files?>scripts/jquery.js" type="text/javascript" charset="utf-8"></script>
</head>
<body onload="window.print();" style="font-family: verdana;">
<p style="margin:auto; text-align: center;font-size: 16px;font-weight: 600; ">Курьерская заявка от <?=date('d.m.Y')?></p>
<br>
<br>
<?php 
if($this->order){?>
<table cellpadding="2" cellspacing="0" border="1" width="1000" style="text-align: center;font-size: 10px;">
    <tr style="background-color: #d9d9d9;font-weight: 600;">
        <td colspan="2">Дата загрузки</td>
        <td><?=date('d.m.Y')?></td>
        <td colspan="2">Время подачи машины</td>
        <td>с 13.00 до 17.00</td>
        <td colspan="2">Адрес загрузки</td>
        <td colspan="2">Нижнеюрковская, 31 <br>(конец переулка, железные ворота - направо под арку)</td>
        <td></td>
        <td></td>
        <td>Телефоны</td>
        <td colspan="3">(093) 051 09 20</td>
    </tr>
    <tr bgcolor="#969ba0">
        <td>Номер заказа</td>
        <td>Контактное лицо</td>
        <td>Телефон</td>
        <td>Город</td>
        <td>Адрес доставки</td>
        <td>Дата доставки</td>
        <td>Время доставки</td>
        <td>Описание товара</td>
        <td>Сумма к оплате</td>
        <td>Сумма страховки</td>
        <td>Кол.мест</td>
        <td>Вес,кг</td>
        <td>Примечания</td>
        <td>Длина, см</td>
        <td>Ширина, см</td>
        <td>Высота, см</td>
    </tr>
<?php
foreach($this->order as $order){
if($order->getPaymentMethodId() == 4 or $order->getPaymentMethodId() == 5 or $order->getPaymentMethodId() == 6){
$price_skidka = 0;
}else{
$price_skidka = $order->getAmount();
if($price_skidka < 1) {$price_skidka=0;}
}
?>
<tr>
		<td><?=$order->getId()?></td>
        <td><?=$order->getMiddleName().' '.$order->getName()?></td>
        <td><?=$order->getTelephone()?></td>
        <td>Киев</td>
        <td><?=$order->getAddress()?></td>
        <td><?=date('d.m.Y', strtotime($order->getDeliveryDate()))?></td>
        <td><?=$order->getDeliveryInterval()?></td>
        <td>Одежда</td>
        <td><?=$price_skidka?></td>
        <td><?=ceil($order->getAmount()+$order->getDeposit())?></td>
        <td>1</td>
        <td> </td>
        <td>Предварительно связаться!</td>
        <td> </td>
        <td> </td>
        <td> </td>
</tr>
<?php
}
?>

</table>
<?php
}


?>
<br>
<br>
<div style="width:300px; height:200px;float:right;font-size: 12px;">
<span>Отправил: <?=$this->name;?> / __________</span><br><br>
<span>Получил: _______________ / __________</span>
</div>
<body>
</head>
</html>
