<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt=""  class="page-img" />
<!--<script src="<?=$this->files?>scripts/ui/jquery-ui-1.9.1.custom.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?=$this->files?>scripts/ui/jquery-ui-1.9.1.custom.css" type="text/css" media="screen"/>-->
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<br>
<style>
.img{width:20px;cursor: pointer;}
</style>
<p style="/*float:  left;*/"><a href="/admin/amazon" target="_blank"  >Новый заказ</a></p>
<?php
 if ($this->errors) { ?>
    <div id="errormessage" style="margin: auto;">
	<img src="<?php echo SITE_URL; ?>/img/icons/error.png" alt=""  class="page-img"/>
        <span style="font-size: 14px;font-weight: bold;">Найдены ошибки:</span><br>
        <ul>
            <?php foreach ($this->errors as $error) echo "<li>{$error}</li>"; ?>
        </ul>
    </div>
<?php
}
 if($this->orders->count() >0){ ?>
 <table id="products1" cellpadding="4" cellspacing="0" style="" class="table" align="center">
    <tr>
        <th>№</th>
        <th>Создал</th>
        <th>Дата</th>
		<th>Кол.товаров</th>
        <th>Сумма</th>
		<th>Согласие</th>
		<th>Состояние</th>
    </tr>
	<?php foreach ($this->orders as $a){
$row = ($row == 'row2') ? 'row1' : 'row2';	?>
<tr class="<?php echo $row;?>">
	<td><a href="/admin/amazonorders/orderarticles/<?=$a->id ?>" target="_blank"><?=$a->id?></a></td>
	<td><?=$a->name?></td>
	<td><?=$a->ctime?></td>
	<td><?=$a->count?></td>
	<td><?=$a->price?></td>
	<td>
	<?php if($a->flag == 1) {
	echo '<img   src="/img/icons/check.png"  class="img" data-placement="bottom"  data-tooltip="tooltip"  title="Одобрен" />';
	}else if($a->flag == 2){ ?>
	<img   src="/img/icons/cantremove-small.png" class="img" data-placement="bottom"  data-tooltip="tooltip" title="Не одобрен"  />
	 <?php if($this->user->getId() == 1 or $this->user->getId() == 8005) { ?>
	 <img   src="/img/icons/accept.png" class="img" data-placement="bottom"  data-tooltip="tooltip" title="Одобрить"  onclick="ok_order(<?=$a->id?>); return false;" /> <?php } ?>
	 
	<?php }else if($a->flag == 0 and ($this->user->getId() == 1 or $this->user->getId() == 8005)) { ?>
	<img   src="/img/icons/accept.png" class="img" data-placement="bottom"  data-tooltip="tooltip" title="Одобрить"  onclick="ok_order(<?=$a->id?>); return false;" />
	<img   src="/img/icons/error.png" class="img" data-placement="bottom"  data-tooltip="tooltip" title="Отменить"  onclick="off_order(<?=$a->id?>); return false;" /> 
	<?php } ?>
	</td>
	<td><?php if($a->otpravka == 1) {  ?>
	<img   src="/img/icons/check.png" class="img" data-placement="right"  data-tooltip="tooltip" title="Заказ отправлен" >
	<?php
	}else if($a->flag == 1 and $a->otpravka == 0) { ?>
	<img   src="/img/icons/mailing.png" class="img" data-placement="right"  data-tooltip="tooltip" title="Отправить"  onclick="go_email(<?=$a->id?>); return false;" />
	<?php } ?>
	</td>
</tr>
	<?php } ?>
	</table>
	<?php }?>
 <script>
 function ok_order(e){
  $.ajax({
                url:  '/admin/amazonorders/',
                type: 'POST',
                dataType: 'json',
                data: '&method=ok&id='+e,
                success: function (res) {
				if(res) {
				$('#popup').html(res);
				fopen(); 
				}
				}
				});
 }
 function off_order(e){
  $.ajax({
                url:  '/admin/amazonorders/',
                type: 'POST',
                dataType: 'json',
                data: '&method=off&id='+e,
                success: function (res) {
				if(res) {
				$('#popup').html(res);
				fopen(); 
				}
				}
				});
 }
 function go_email(e){
  $.ajax({
                url:  '/admin/amazonorders/',
                type: 'POST',
                dataType: 'json',
                data: '&method=order_go&id='+e,
                success: function (res) {
				if(res) {
				$('#popup').html(res);
				fopen(); 
				}
				}
				});
 }
$(document).ready(function () {

		});
 </script>