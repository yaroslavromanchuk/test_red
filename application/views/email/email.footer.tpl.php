<?php $dname = Config::findByCode('domain_name')->getValue();
$today = date("Y-m-d H:i:s"); 
$all_news = wsActiveRecord::useStatic('News')->findAll(array("status"=> 2, "start_datetime <= '$today' and '$today' <= end_datetime"),array(),array(2));
$w = 'width: 33.33333333%;';
?>
<?php
if(/*$all_news->count() == 2*/false ){ ?>
<table  align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:12px;color:#6c6c6c;width:700px;">
<tr>
<?php foreach($all_news as $news) { ?>
<td><div style="text-align:center;padding:2px;height:100px;background:white;">
	<h3><?=$news->getTitle()?></h3>
	<p  style="text-align:center;"><?=$news->getIntro()?></p>
	<a  style="cursor: pointer;    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;" href="<?=$news->getPath()?>"><?=$this->trans->get('Смотреть детали')?></a><br/>
	</div>
</td>
 <?php } ?>

</tr>
</table>
<?php }
 ?>
<table  align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:12px;color:#6c6c6c;width:700px;">
<tr style="background: #ededed;">
<td  style="<?=$w?>padding-top:10px;padding-left:10px;"><b>CALL-ЦЕНТР</b></td>
<td  style="<?=$w?>padding-top:10px;padding-left:10px;"><b>КОНТАКТЫ</b></td>
<td  style="<?=$w?>padding-top:10px;padding-left:10px;"><b>СТРАНИЦЫ</b></td>
</tr>
<tr style="background: #ededed;" >
<td  style="<?=$w?>padding-left:10px;">Пн-Пт: 09:00 - 18:00<br>Сб-Вс: Выходные</td>
<td  style="<?=$w?>padding-left:10px;" >(044) 224-40-00<br>(063) 809-35-29<br>(067) 406-90-80<br>market@red.ua</td>
<td  style="<?=$w?>padding-left:10px;padding-bottom:10px;" >
<a href="https://<?=$dname?>/advantages/" style="color: #878787;text-decoration: none;">Преимущества</a><br>
<a href="https://<?=$dname?>/reviews/" style="color: #878787;text-decoration: none;">Отзывы</a><br>
<a href="https://<?=$dname?>/pays/" style="color: #878787;text-decoration: none;">Доставка и оплата</a><br>
<a href="https://<?=$dname?>/returns/" style="color: #878787;text-decoration: none;">Возвраты</a>
</td>

</tr>
</table>
</body>
</html>