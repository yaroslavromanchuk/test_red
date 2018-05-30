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
	<td ><input type="text" style="width:75px;" value="<?php echo @$_GET['order']?>" autofocus class="form-control input" name="order" id="order"/></td>
	<td><input type="text" style="width:120px;" value="<?php echo @$_GET['articul']?>" class="form-control input" name="articul"/></td>
	<td>
	<select name="delivery" class="form-control input">
    <option value="">Все</option>
    <option value="3" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '3') echo 'selected="selected"';?>>пр.Победы</option>
    <option value="12" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '12') echo 'selected="selected"';?>>ул.Мишуги</option>
	 <option value="5" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '5') echo 'selected="selected"';?>>ул.Строителей</option>
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
	<td><input type="date" style="width:120px;" value="<?php echo @$_GET['create_from']?>"  class="form-control input" name="create_from"/></td>
	<td><input type="date" style="width:120px;" value="<?php echo @$_GET['create_to']?>" class="form-control input" name="create_to"/></td>
	<td>
	<select name="deposit" class="form-control input">
    <option value="">Все</option>
    <option value="0" <?php if (isset($_GET['deposit']) and $_GET['deposit'] == '0') echo 'selected="selected"';?>>Без депозита</option>
    <option value="1" <?php if (isset($_GET['deposit']) and $_GET['deposit'] == '1') echo 'selected="selected"';?>>С депозитом</option>
</select>
</td>
	</tr>
	</table>
	<button type="submit"   class="btn btn-default"><span style="font-weight: bold;font-size: 16px;"><i class="glyphicon glyphicon-search" aria-hidden="true"></i> Найти</span></button>

</form>
<?php 
if (@$this->getArticles()) {
$deli = array(12 => 'Мишуга', 3 => 'Победа', 5 => 'Строителей');
?> 
<script type="text/javascript">
    var clik_ok = 0;
    function chekAll() {
        if (!clik_ok) {
            $('.cheker').attr('checked', true);
            clik_ok = 1;
        } else {
            $('.cheker').attr('checked', false);
            clik_ok = 0;
        }
        return false;
    }
</script>
<a href="#" onclick="chekAll(); return false;" style="display:inline-block;margin-right: 20px;float: left;">Отметить/Снять все</a>
<img id="p_all" name="p_all" src="<?=SITE_URL?>/img/icons/check.png" style="width: 30px;padding: 2px;border: 1px solid red;border-radius: 5px; display:none;"  onclick="return pr(this);" alt="Принять"  class="img_return" data-placement="right"  data-tooltip="tooltip"  title="Принять отмеченый товар"/>
<?php if($this->user->getId()== 8005){ ?>
<img src="<?=SITE_URL?>/img/icons/error.png" id="dell_all" name="dell_all" style="width: 30px;padding: 2px;border: 1px solid red;border-radius: 5px;" onclick="return dell(this);" alt="Удалить без возврата"  class="img_return" data-placement="right"  data-tooltip="tooltip"  title="Удалить без возврата" >
<?php }?>
<table cellspacing="0" cellpadding="4" id="orders" class="table table-hover" >
    <tr>
        <th colspan="3">Действия</th>
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
    <?php $row = 'row2'; foreach ($this->getArticles() as $order) {
    $row = ($row == 'row2') ? 'row1' : 'row2';
    $owner_add = new Customer($order->user);
	$owner_pr = new Customer($order->user_pr);
    ?>
    <tr class="<?=$row;?>" <?php if($order->deposit == 1) echo 'style="background: #11c118b5;"'; ?> id="<?=$order->getId();?>">
        <td>
           <?php if($order->count > 0 and  $this->admin_rights['492']['right'] == 1) { ?> <input type="checkbox" class="order-item cheker" name="item_<?=$order->getId();?>"/><?php } ?>
        </td>
        <td class="kolomicon">
		<?php if($order->count > 0 and  $this->admin_rights['492']['right'] == 1) { ?><img src="<?=SITE_URL?>/img/icons/check.png" name="<?=$order->getId();?>" onclick="return pr(this);" alt="Принять"   class="img_return" data-placement="bottom"  data-tooltip="tooltip"  title="Принять товар"/><?php } ?>
		<?php if($order->count > 0 and  $this->admin_rights['493']['right'] == 1) { ?><img src="<?=SITE_URL;?>/img/icons/return_order.png" name="ret_<?=$order->getId();?>" onclick="return ret_ord(this);" alt="Вернуть"  class="img_return" data-placement="bottom"  data-tooltip="tooltip"  title="Вернуть товар в заказ"/><?php } ?>
		</td>
		<td class="kolomicon">		 
		<?php if($order->count > 0 and  $this->admin_rights['492']['right'] == 1) { ?><img src="<?=SITE_URL;?>/img/icons/remove-small.png" name="<?=$order->getId();?>" onclick="return dell(this);" alt="Удалить без возврата"  class=" img_return" data-placement="bottom"  data-tooltip="tooltip"  title="Удалить без возврата на сайт"/><?php } ?>
        </td>
        <td><?=$this->order_status[$order->status];?></td>
		<td><a href="<?=$this->path;?>shop-orders/edit/id/<?=$order->order_id;?>/"><?=$order->order_id;?></a></td>
        <td><?=$order->cod;?></td>
        <td><?=$order->title;?></td>
        <td><?=$order->count;?></td>
        <td><?=$order->price;?></td>
        <td><?=$order->old_price;?>
            <!--<b>Сумма: <?php //echo number_format($order->getVSum(), 2, ',', ''); ?></b>-->
        </td>
		<td><?=$owner_add->middle_name;?></td>
        <td><?=$order->ctime;?></td>
		<td><?=$owner_pr->middle_name;?></td>
        <td><?=$order->utime;?></td>
        <td><?=$deli[$order->delivery];?></td>
    </tr>
    <?php } ?>
</table>
<?php
    $limitLeft = 2;
    $limitRight = 2;
    $url = explode('?', $_SERVER['REQUEST_URI']);
    if (count($url) == 2) {
        $ur = $url[0];
        $get = '?' . $url[1];
    } else {
        $ur = $_SERVER['REQUEST_URI'];
        $get = '';
    }
    $pager = preg_replace('/\/page\/\d*/', '', $ur) . '/page/';
    $paginator = '&nbsp;&nbsp;';
    if ($this->page > 1) {
        $paginator .= '<a href="' . $pager . '1' . $get . '"><<</a>&nbsp;<a href="' . $pager . ($this->page - 1) . $get . '"><</a>&nbsp;';
    } else {
        $paginator .= '<span class="grey"><</span>&nbsp;<span class="grey"><<</span>&nbsp;';
    }
    $start = 1;
    $end = $this->totalPages;
    if ($this->page > $limitLeft) {
        $paginator .= '...&nbsp;';
        $start = $this->page - $limitLeft;
    }
    if (($this->page + $limitRight) < $this->totalPages) {
        $end = $this->page + $limitRight;
    }
    //for ($i = 1; $i <= $this->totalPages; $i++){
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $this->page) {
            $paginator .= '<span>' . $i . '</span>';
        } else {
            $paginator .= '<span><a href="' . $pager . $i . $get . '">' . $i . '</a></span>';
        }
        if ($i <= $end - 1) {
            $paginator .= '<span class="delimiter">&nbsp;|&nbsp;</span>';
        }

    }
    if ($this->page == $this->totalPages) {
        $paginator .= '&nbsp;<span class="grey">>></span>&nbsp;<span class="grey">></span>';
    } else {
        $paginator .= '&nbsp;<a href="' . $pager . ($this->page + 1) . $get . '">></a>&nbsp;<a href="' . $pager . $this->totalPages . $get . '">>></a>';
    }
    echo $paginator;

    ?><br/>
Всего страниц: <?=$this->totalPages?>,  записей: <?=$this->count ?>
<?php } else echo 'Нет записей'; ?>
<script>

var $i = 0;
$('#order').keypress(function(e){
 //if(e.key == 'Enter')  $('#myform').submit(); 
 if($i == 6) {
 
	  $i = 0;
	  console.log($("#order").val());
	//$("#order").val('');
	//$("#order").focus();
	$('#myform').submit(); 
	  } 
	  

      e = e || event;
      if (e.ctrlKey || e.altKey || e.metaKey) return;
	  if(e.which > 47 && e.which < 58 ){
	  $i++;
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
//alert(th.id);
 if ($('.order-item:checked').val() && th.name == 'p_all') {

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
                }else if(th.name != 'p_all'){ var id = th.name; }

//var dat = '&id='+th.name;
var dat = '&id='+id;
//alert(id);
//var value = confirm("Принять?");
if(id){
$.ajax({
			beforeSend: function( data ) {
			//console.log(dat);
			fopen('Возвраты', '<img  id="loading" src="/img/loader-article.gif">');
			},
			type: "POST",
			url: '/admin/vozvrats/',
			dataType: 'json',
			data: '&method=priyom'+dat,
			success: function( data ) {
			//console.log(data);
			if(data.send == 1){
			var t = '';
			for (index = 0; index < data.text.length; ++index) {
			t +=data.text[index]+'<br>';
			}
			//console.log(data);
			$('#'+th.name).hide();
			//fopen('Возвраты', t);
			//setTimeout(FormClose, 700);
			}
			if(data.send == 0){
			var r = '';
			for (index = 0; index < data.text.length; ++index) {
			r +=data.text[index]+'<br>';
			}
			//console.log(data);
			fopen('Возвраты', r);
			//setTimeout(FormClose, 700);
			}
			},
			complete: function( data ) {
			jQuery.each($('.order-item:checked'), function () {
			$("#"+$(this).attr('name').substr(5)).hide();
			});
			FormClose();
			$("#order").val('');
			$("#order").focus();
			//$('#save').attr('value', 'Создать');
			},
			error: function( e ) {
			//console.log(e);
			fopen('Ощибка', 'Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
			//alert('Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
			}
		});
}else{
fopen('Ощибка', 'Вы не выбрали товары который нужно принять!');
//setTimeout(FormClose, 700);
}
return false;
}

function ret_ord(th){
var dat = '&id='+th.name.substr(4);
var value = prompt("Введите причину возврата товара в заказ: ", '');
if(value === null) return false;
if(value === '') return false;
if(value.length > 1){
		dat +='&mes='+value;

$.ajax({
			beforeSend: function( data ) {
			fopen('Загрузка', '<img  id="loading" src="/img/loader-article.gif">');
			},
			type: "POST",
			url: '/admin/vozvrats/',
			dataType: 'json',
			data: '&method=return_order'+dat,
			success: function( data ) {
			console.log(data);
			if(data.send == 1){
			$('#'+th.name).hide();
			fopen('Возвращение товара', data.text+' ( '+data.ss+' )');
			}else{
			fopen('Возвращение товара', data.text+' ( '+data.ss+' )');
			}
			},
			complete: function( data ) {
			//$('#save').attr('value', 'Создать');
			},
			error: function( e ) {
			//console.log(e);
			fopen('Ошибка', 'Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
			}
		});
		}
return false;
}	
function dell(th){
 if ($('.order-item:checked').val() && th.name == 'dell_all') {

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
                }else if(th.name != 'dell_all'){ var id = th.name; }

//var dat = '&id='+th.name;
var dat = '&id='+id;

//var value = prompt("Введите причину удаления товара: ", '');
//if(value === null) return false;
//if(value === '') return false;
//if(value.length > 1){
	//	dat +='&mes='+value;
	if(id){

$.ajax({
			beforeSend: function( data ) {
			//console.log(dat);
			fopen('Удаление товара','<img  id="loading" src="/img/loader-article.gif">');
			},
			type: "POST",
			url: '/admin/vozvrats/',
			dataType: 'json',
			data: '&method=deleteshop'+dat,
			success: function( data ) {
			//console.log(data);
			if(data.send == 1){
			$('#'+th.name).hide();
			fopen('Удаление товара',data.text+' ( '+data.ss+' )');
			}else{
			fopen('Удаление товара',data.text+' ( '+data.ss+' )');
			}
			},
			complete: function( data ) {
			//$('#save').attr('value', 'Создать');
			},
			error: function( e ) {
			//console.log(e);
			fopen('Ошибка', 'Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
			}
		});
		}
return false;
}	
</script>