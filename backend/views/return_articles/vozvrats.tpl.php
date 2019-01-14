<img src="<?=SITE_URL.$this->getCurMenu()->getImage();?>" alt=""  class="page-img" />
<h1><?=$this->getCurMenu()->getTitle();?></h1><br/>
<form action="" method="get" id="myform">

	<table style="width: 100%;" class="table">
	<tr>
	<th>Статус</th>
	<th>Заказ</th>
	<th>Артикул</th>
	<th>Магазин</th>
	<th>Админ</th>
	<th>Создан от</th>
	<th>Создан до</th>
	<th>Депозит</th>
	</tr>
	<tr>
	<td>
	<select name="status" class="form-control input">
    <option value="" >Все</option>
    <option value="0" <?php if (isset($_GET['status']) and $_GET['status'] == '0') {echo 'selected="selected"';}?> selected="selected" >Новый</option>
    <option value="1" <?php if (isset($_GET['status']) and $_GET['status'] == '1') echo 'selected="selected"';?>>Принят</option>
    <option value="2" <?php if (isset($_GET['status']) and $_GET['status'] == '2') echo 'selected="selected"';?>>Удален без возврата</option>
	 <option value="3" <?php if (isset($_GET['status']) and $_GET['status'] == '3') echo 'selected="selected"';?>>Возврат в заказ</option>
</select>
</td>
	<td ><input type="text" style="width:75px;" value="<?=$_GET['order']?>"  class="form-control input" name="order" id="order"/></td>
	<td><input type="text"  value="<?=$_GET['articul']?>" class="form-control input" name="articul"/></td>
	<td>
	<select name="delivery" class="form-control input">
    <option value="">Все</option>
    <option value="3" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '3') echo 'selected="selected"';?>>пр.Победы</option>
	 <option value="5" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '5') echo 'selected="selected"';?>>ул.Строителей</option>
         <option value="4" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '4') echo 'selected="selected"';?>>Укр.Почта</option>
         <option value="8" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '8') echo 'selected="selected"';?>>Нова.Почта</option>
         <option value="16" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '16') echo 'selected="selected"';?>>Нова.Почта НП</option>
         <option value="9" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '9') echo 'selected="selected"';?>>Курьер</option>
</select>
</td>
	<td>
	<select name="admin" class="form-control input">
    <option value="">Все</option>
	<?php foreach(AdminRights::getRights(491) as $m){
$owner = new Customer($m->admin_id);
	?>
    <option value="<?=$owner->id?>" <?php if (isset($_GET['admin']) and $_GET['admin'] == $owner->id ) echo 'selected="selected"';?>><?=$owner->middle_name?></option>
	<?php } ?>
</select>
</td>
	<td><input type="date"  value="<?php if(@$_GET['create_from']) echo date('Y-m-d', strtotime($_GET['create_from']));?>"  class="form-control input" name="create_from"/></td>
	<td><input type="date"  value="<?php if(@$_GET['create_to']) echo date('Y-m-d', strtotime($_GET['create_to']));?>" class="form-control input" name="create_to"/></td>
	<td>
	<select name="deposit" class="form-control input">
    <option value="">Все</option>
    <option value="0" <?php if (isset($_GET['deposit']) and $_GET['deposit'] == '0') echo 'selected="selected"';?>>Без депозита</option>
    <option value="1" <?php if (isset($_GET['deposit']) and $_GET['deposit'] == '1') echo 'selected="selected"';?>>С депозитом</option>
</select>
</td>
	</tr>
	</table>
	<div style="float: right;">
<label for="nakladna">Номер накладной:</label>
<input type="text" id="nakladna" name="nakladna" value="<?php if (isset($_GET['nakladna'])) echo$_GET['nakladna']; ?>" class="form-control input w100">
<button onclick="$('#nakladna').val('');return false;" class="btn btn-primary pd-x-20">Завершить</button></div>
	<button type="submit"   class="btn btn-default"><span style="font-weight: bold;font-size: 16px;"><i class="glyphicon glyphicon-search" aria-hidden="true"></i> Найти</span></button>

</form>
<?php 
if ($this->getArticles()) {
$deli = array(12 => 'Мишуга', 3 => 'Победа', 5 => 'Строителей', 4=>'УкрПочта', 9=>'Курьер', 8=>'НП', 16=>'НП:НП');
?> 
<script>
       $(document).ready(function() {
     $("body").keypress(function(e) {
         switch(e.originalEvent.code){
             case 'NumpadMultiply': if($('.chekAll').is(":checked")){ $('.chekAll').prop('checked', false); }else{$('.chekAll').prop('checked', true);}  chekAll(); break;
             case 'NumpadAdd': pr('p_all'); break;
         }
         console.log(e.originalEvent.code);
         // if (e.which == 13) {
            //  return false;
          //}
     });
});
        function chekAll() {
            console.log($(this))
		if($('.chekAll').is(":checked")){
		$('.cheker').prop('checked', true);
		}else{
		$('.cheker').prop('checked', false);
		}
            return false;
        }
</script>
<i class="icon ion-checkmark green tx-30 pd-5 mg-5" id="p_all" name="p_all"  onclick="return pr('p_all');" data-tooltip="tooltip" data-placement="left" data-original-title="Принять отмеченый товар"></i>
<?php if($this->user->getId()== 8005){ ?>
<i class="icon ion-close red tx-30 pd-5"  id="dell_all" name="dell_all" onclick="return dell('dell_all');" data-tooltip="tooltip" data-placement="right" data-original-title="Удалить без возврата на сайт"></i>
<?php }?>
<div id="alert"></div>

<table cellspacing="0" cellpadding="4" id="orders" class="table table-hover table-bordered" >
    <tr>
		<th><label class="ckbox" data-tooltip="tooltip" data-placement="left" title="Выделить все товары"><input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span></label></th>
        <th>Действия</th>
        <th>Статус</th>
        <th>Заказ</th>
        <th>Артикул</th>
        <th>Товар</th>
        <th>Колл.</th>
        <th>Цена</th>
        <th>Нач.цена</th>
		<th>Добавил</th>
		<th>Дата</th>
		<th>Обработал</th>
		<th>Дата</th>
		<th>Магазин</th>
    </tr>
    <?php //$row = 'row2';
    foreach ($this->getArticles() as $order) {
   // $row = ($row == 'row2') ? 'row1' : 'row2';
    $owner_add = new Customer($order->user);
	$owner_pr = new Customer($order->user_pr);
    ?>
    <tr  <?php if($order->deposit == 1){ echo 'style="background: #11c118b5;"';  } ?> id="<?=$order->getId();?>">
        <td>
<?php if($order->count > 0 and  $this->admin_rights['492']['right'] == 1) { ?>
 <label class="ckbox"><input type="checkbox" class="order-item cheker" name="item_<?=$order->getId()?>"/><span></span></label>
 <?php } ?>
        </td>
        <td>
		<?php if($order->count > 0 and  $this->admin_rights['492']['right'] == 1) { ?>
		<i class="icon ion-checkmark green tx-30 pd-5 mg-5"   onclick="return pr(<?=$order->getId()?>);" data-tooltip="tooltip" data-placement="left" data-original-title="Принять товар"></i>
		<?php } ?>
		<?php if($order->count > 0 and  $this->admin_rights['493']['right'] == 1) { ?>
		<i class="icon ion-refresh bleak tx-30 pd-5 mg-5"  name="ret_<?=$order->getId()?>" onclick="return ret_ord(<?=$order->getId()?>);" data-placement="right" data-tooltip="tooltip" data-original-title="Вернуть товар в заказ"></i>
		<?php } ?>		 
		<?php if($order->count > 0 and  $this->admin_rights['492']['right'] == 1) { ?>
		<i class="icon ion-close red tx-30 pd-5"   onclick="return dell(<?=$order->getId();?>);" data-tooltip="tooltip" data-placement="right" data-original-title="Удалить без возврата на сайт"></i>
		<?php } ?>
        </td>
        <td><?=$this->order_status[$order->status];?></td>
	<td><a href="<?=$this->path?>shop-orders/edit/id/<?=$order->order_id?>/"><?=$order->order_id?></a></td>
        <td><?=$order->cod?></td>
        <td><?=$order->title?></td>
        <td><?=$order->count?></td>
        <td><?=$order->price?></td>
        <td><?=$order->old_price?></td>
	<td><?=$owner_add->middle_name?></td>
        <td><?=$order->ctime?></td>
	<td><?=$owner_pr->middle_name?></td>
        <td><?=$order->utime?></td>
        <td><?=$deli[$order->delivery]?></td>
    </tr>
    <?php } ?>
</table>

<?php }else{
    
    echo 'Нет записей';

} ?>
<script>
 
var  n = 0;
$('#order').keypress(function(e){
 //if(e.key == 'Enter')  $('#myform').submit(); 
 if(n == 6) {
	 n = 0;
	  console.log($("#order").val());
	//$("#order").val('');
	//$("#order").focus();
	$('#myform').submit(); 
	  } 
     // e = e || event;
      if (e.ctrlKey || e.altKey || e.metaKey){ return false;}
	  if(e.which > 47 && e.which < 58 ){
	  n++;
	 // console.log($i);
	  return true;
	  }else{
	   return false;
	  }
    });
	
 $('#p_all').show();
var ch = $('input:radio:checked').prop("checked");
//return confirm('Удалить товар без возврата на склад? (товар не вернется на склад)');
function pr(th){
var nakladna = $('#nakladna').val();
if(nakladna.length == 0){
    $('#alert').html('<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Введите номер накладной!</div>');
    setTimeout(function(){$('#alert').html('')}, 2000);
            
            return false;
        }

 if ($('.order-item:checked').val() && th == 'p_all') {
                   var id = '';
                    i = 0;
                    jQuery.each($('.order-item:checked'), function () {
                        if (i != 0) {
                            id += ',' + $(this).attr('name').substr(5);
                        } else {
                            id += $(this).attr('name').substr(5);
                        }
                        i++;
                    });
                }else if(th != 'p_all'){ var id = th; }
var dat = '&id='+id+'&nakladna='+nakladna;
if(id){
$.ajax({
			//beforeSend: function( data ) { fopen('Возвраты', '<img  id="loading" src="/img/loader-article.gif">'); },
			type: "POST",
			url: '/admin/vozvrats/',
			dataType: 'json',
			data: '&method=priyom'+dat,
			success: function( data ) {
			if(data.send == 1){
			var t = '';
			for (index = 0; index < data.text.length; ++index) {
			t +=data.text[index]+'<br>';
			}
			//console.log(data);
			$('#'+th).hide();
			//fopen('Возвраты', t);
			//setTimeout(FormClose, 700);
			}
			if(data.send == 0){
			var r = '';
			for (index = 0; index < data.text.length; ++index) {
			r +=data.text[index]+'<br>';
			}
			//console.log(data);
                        $('#alert').html('<div class="alert alert-danger" role="alert"><strong>'+r+'</div>');
                        setTimeout(function(){$('#alert').html('')}, 2000);
			//fopen('Возвраты', r);
			//setTimeout(FormClose, 700);
			}
			},
			complete: function( data ) {
			jQuery.each($('.order-item:checked'), function () {
			$("#"+$(this).attr('name').substr(5)).hide();
			});
			//FormClose();
			$("#order").val('');
			$("#order").focus();
			},
			error: function( e ) {
                            $('#alert').html('<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!</div>');
                        //setTimeout(function(){$('#alert').html('')}, 2000);
                           // fopen('Ощибка', 'Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
                        }
		});
}else{
$('#alert').html('<div class="alert alert-warning" role="alert"><strong>Предупреждение</strong> Вы не выбрали товары который нужно принять!</div>');
setTimeout(function(){$('#alert').html('')}, 5000);
//fopen('Ощибка', 'Вы не выбрали товары который нужно принять!');
}
return false;
}

function ret_ord(th){
var dat = '&id='+th;
var value = prompt("Введите причину возврата товара в заказ: ", '');
if(value === null) return false;
if(value === '') return false;

if(value.length > 1){
dat +='&mes='+value;
$.ajax({
			//beforeSend: function( data ) { fopen('Загрузка', '<img  id="loading" src="/img/loader-article.gif">'); },
			type: "POST",
			url: '/admin/vozvrats/',
			dataType: 'json',
			data: '&method=return_order'+dat,
			success: function( data ) {
			console.log(data);
			if(data.send == 1){ $('#'+th).hide(); }
			//fopen('Возвращение товара', data.text+' ( '+data.ss+' )');
                        $('#alert').html('<div class="alert alert-success" role="alert"><strong>Возвращение товара</strong>'+data.text+' ( '+data.ss+')</div>');
setTimeout(function(){$('#alert').html('')}, 5000);
			},
			error: function( e ) {
			fopen('Ошибка', 'Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
			}
		});
		}
return false;
}	
function dell(th){
var nakladna = $('#nakladna').val();
if(nakladna.length == 0){
    $('#alert').html('<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Введите номер накладной!</div>');
    setTimeout(function(){$('#alert').html('')}, 2000);
    //alert('Введите номер накладной!');
    return false; }
if ($('.order-item:checked').val() && th == 'dell_all') {
                   var id = '';
                    i = 0;
                    jQuery.each($('.order-item:checked'), function () {
                        if (i != 0) {
                            id += ',' + $(this).attr('name').substr(5);
                        } else {
                            id += $(this).attr('name').substr(5);
                        }
                        i++;
                    });
                }else if(th != 'dell_all'){ var id = th; }

var dat = '&id='+id+'&nakladna='+nakladna;

//var value = prompt("Введите причину удаления товара: ", '');
//if(value === null) return false;
//if(value === '') return false;
//if(value.length > 1){
	//	dat +='&mes='+value;
if(id){
$.ajax({
			//beforeSend: function( data ) {
			//fopen('Удаление товара','<img  id="loading" src="/img/loader-article.gif">');
			//},
			type: "POST",
			url: '/admin/vozvrats/',
			dataType: 'json',
			data: '&method=deleteshop'+dat,
			success: function( data ) {
			if(data.send == 1){ $('#'+th).hide(); }
			fopen('Удаление товара',data.text+' ( '+data.ss+' )');
			},
			error: function( e ) {
			fopen('Ошибка', 'Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
			}
		});
		}
return false;
}	
</script>