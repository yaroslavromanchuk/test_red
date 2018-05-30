<img src="<?=SITE_URL.$this->getCurMenu()->getImage();?>" alt="" class="page-img" />
<h1><?=$this->getCurMenu()->getTitle();?></h1><br>
<div style="width:450px; margin:5px 0 0 40px;">
<?php
$iskat = $this->trans->get('Искать');
 //echo $this->getCurMenu()->getPageBody();
 if($this->user->getId() == 8005){
 /*
 $needle = 'Сохранить'; 
 $result = array_filter($this->transtate, function($innerArray){
    global $needle;
   return in_array($needle, $innerArray);    //Поиск по всему массиву
    //return ($innerArray['name'] == $needle); //Поиск по первому значению
});
 echo print_r($result);*/
 //echo print_r(Registry::get('cache'));
//unset($_SESSION['lang']);
//echo printf("<pre>%s</pre>",print_r($this->transtate, true));
//echo printf("<pre>%s</pre>",print_r($_SESSION, true));
 }?>

<form action="/admin/shop-orders/" method="get">
<table>
	<tr>
	<td width="200" style="font-size: 100%;"><?=$this->trans->get('Поиск заказа №');?></td>
    <td><input name="order" style="height:25px;" class="input" value="" type="text" /></td>
	<td><input type="submit" style="padding: 2px 10px;" class="button" value="<?=$iskat?>" /></td>
	</tr>
</table>
</form>

<form action="/admin/viewOrder/" method="get">
<table>
	<tr>
	<td width="200" style="font-size: 100%;"><?=$this->trans->get('Просмотр заказа №');?> </td>
    <td><input name="order"  style="height:25px;" class="input" value="" type="text" /></td>
	<td><input type="submit" style="padding: 2px 10px;"   class="button" value="<?=$iskat?>" /></td>
	</tr>
</table>
</form>

<form action="/admin/viewOrder/metod/edit/" method="get">
<table>
	<tr><td width="200" style="font-size: 100%;"><?=$this->trans->get('Редактирование заказа №');?> </td>
    <td><input name="order"  style="height:25px;" class="input" value="" type="text" /></td>
	<td><input type="submit" style="padding: 2px 10px;"  class="button" value="<?=$iskat?>" /></td></tr>
</table>
</form>
<form action="/admin/viewOrder/metod/view/" method="get">
<table>
	<tr><td width="200" style="font-size: 100%;"><?=$this->trans->get('Просмотр товара по артикулу №');?></td>
    <td><input name="articul"  style="height:25px;" class="input" value="" type="text" /></td>
	<td><input type="submit" style="padding: 2px 10px;"  class="button" value="<?=$iskat?>" /></td></tr>
</table>
</form>
</div>

