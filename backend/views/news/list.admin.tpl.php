<img src="<?=SITE_URL.$this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32"/>
<h1> <?=$this->getCurMenu()->getTitle();?></h1>
<?=$this->getCurMenu()->getPageBody();?>
<br>
</br>
<?php
$today = date("Y-m-d H:i:s"); 
$all_news = wsActiveRecord::useStatic('News')->findAll(array("status"=> 1, "start_datetime <= '$today' and '$today' <= end_datetime"),array(),array(10));
if($all_news->count()){?>
<table style="font-size: 16px;" align="center">
<tr>
<th>Заголовок</th>
<th>Краткая информация</th>
<th>Действие</th>
</tr>
<?php
$row = 'row1';
foreach($all_news as $news) {
$row = ($row == 'row2') ? 'row1' : 'row2';
 ?>
<tr class="<?=$row;?>">
<td style="font-size: 14px;color: red;"><?=$news->getTitle()?></td>
<td style="font-size: 12px;"><?=$news->getIntro()?></td>
<td><a href="id/<?=$news->getId()?>/">Читать далее</a></td>
</tr>
<?php } ?> 
<?php } ?>