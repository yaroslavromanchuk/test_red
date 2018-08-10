<img src="<?=SITE_URL.$this->getCurMenu()->getImage();?>" alt=""  class="page-img" />
<h1><?=$this->getCurMenu()->getTitle();?></h1><br/>
<form action="" method="get" id="myform">
	<table  class="table">
	<tr>
	<th>Статус</th>
	<th>Заказ</th>
	<th>Id</th>
	<th>Создан от</th>
	<th>Создан до</th>
	<th>Способ возврата</th>
	</tr>
	<tr>
	<td>
	<select name="status" class="form-control input">
    <option value="" >Все</option>
	<?php if(@$this->order_status){
	foreach($this->order_status as $k=>$s){ ?>
	 <option value="<?=$k?>" <?php if (isset($_GET['status']) and $_GET['status'] == $k) { echo 'selected="selected"';}?> ><?=$s?></option>
	<?php }
	} ?>
</select>
</td>
	<td ><input type="text"  value="<?=@$_GET['order']?>" class="form-control " name="order" id="order"/></td>
		<td><input type="text"  value="<?=@$_GET['customer_id']?>" class="form-control " name="customer_id" id="customer_id"/></td>
	<td><input type="date"  value="<?php if(@$_GET['create_from']) echo date('Y-m-d', strtotime($_GET['create_from']));?>"  class="form-control" name="create_from"/></td>

	<td><input type="date"  value="<?php if(@$_GET['create_to']) echo date('Y-m-d', strtotime($_GET['create_to']));?>" class="form-control" name="create_to"/></td>
	<td>
	<select name="sposob" class="form-control ">
    <option value="">Все</option>
    <option value="1" <?php if (isset($_GET['sposob']) and $_GET['sposob'] == '1') echo 'selected="selected"';?>>На депозит</option>
    <option value="2" <?php if (isset($_GET['sposob']) and $_GET['sposob'] == '2') echo 'selected="selected"';?>>Почтовы перевод</option>
</select>
</td>
	</tr>
	</table>
	<!--
<div style="float: right;">
<label for="nakladna">Номер накладной:</label>
<input type="text" id="nakladna" name="nakladna" value="<?php if (isset($_GET['nakladna'])) echo$_GET['nakladna']; ?>" class="form-control input w100">
<button onclick="$('#nakladna').val('');return false;" class="btn btn-primary pd-x-20">Завершить</button>
</div>-->
	<button type="submit" name="search"  class="btn btn-default"><span style="font-weight: bold;font-size: 16px;"><i class="glyphicon glyphicon-search" aria-hidden="true"></i> Найти</span></button>

</form>
<?php 
if (@$this->getOrders()) {
$deli = array(12 => 'Мишуга', 3 => 'Победа', 5 => 'Строителей', 4=>'УкрПочта', 9=>'Курьер', 8=>'НП', 16=>'НП:НП');
?> 
<script type="text/javascript">
        function chekAll() {
		if($('.chekAll').is(":checked")){
		$('.cheker').prop('checked', true);
		}else{
		$('.cheker').prop('checked', false);
		}
            return false;
        }
</script>
<?php if($this->user->getId()== 8005){ ?>
<i class="icon ion-checkmark text-success tx-30 pd-5 mg-5" id="p_all" name="p_all"  onclick="return pr('p_all');" data-tooltip="tooltip" data-original-title="Принять отмеченый товар"></i>

<i class="icon ion-close text-danger tx-30 pd-5"  id="dell_all" name="dell_all" onclick="return dell('dell_all');" data-tooltip="tooltip" data-original-title="Удалить без возврата на сайт"></i>

<?php }?>
<i class="icon ion-clipboard text-primary tx-30 pd-5"  id="print" name="print" onclick="return print103();" data-tooltip="tooltip" data-original-title="Печать формы 103"></i>
<?php if($_GET['status'] == 2) { ?><span id="sum_perekaz"></span> <?php } ?>
<table cellspacing="0" cellpadding="4" id="orders" class="table table-hover" >
    <tr>
		<th><label class="ckbox" data-tooltip="tooltip" title="Выделить все товары"><input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span></label></th>
        <th>Статус</th>
        <th>Заказ</th>
		<th>Id</th>
		<th>Принят</th>
		<th>Принял</th>
		<th>Сумма взврата</th>
		<th>Доп.Cумма</th>
		<th>Способ доставки</th>
		<th>Способ взврата</th>
		<th>Комент</th>
		<th>Админ</th>
    </tr>
    <?php $row = 'row2';
		$spos = array(1=>'На депозин', 2=>'Почтовый перевод');
	foreach ($this->getOrders() as $order) {
	$r = new Shoporders($order->order_id);
	$adm = new Customer($order->admin_create);
	$item_time = strtotime($order->date_create);
        $day = (time() - $item_time) / (24 * 60 * 60);
		 $day = (int)$day;
		// echo $day;
		 if($day >12){
		 $color = 'red';
		 }elseif($day >= 10){
		  $color = '#FF9800';
		 }elseif($day >= 5){
		 $color = '#FFEB3B';
		 }else{
		 $color = '';
		 }
    $row = ($row == 'row2') ? 'row1' : 'row2'; ?>
    <tr class="<?=$row;?>" <?php if($order->status == 1 or $order->status == 2) echo 'style="background: '.$color.';"'; ?> id="<?=$order->getOrderId();?>">
        <td>
 <label class="ckbox"><input type="checkbox" class="order-item cheker" onChange="return Calculat(this);" name="item_<?=$order->getId()?>"/><span></span></label>
        </td>
        <td><?=$this->order_status[$order->status];?></td>
		<!--<td><?=$order->order_id;?></td>-->
		<td><a href="<?=$this->path;?>vozrat/id/<?=$order->id;?>/"><?=$order->order_id;?></a></td>
		<td><?=$order->customer_id?></td></td>
		<td><?=$order->date_create?></td>
		<td><?=$adm->middle_name?></td>
        <td id="s_<?=$order->id?>"><?=@$order->amount?></td>
		<td id="d_<?=$order->id?>"><?=@$order->dop_suma?></td>
		<td><?=$r->getDeliveryTypeId() ? $r->getDeliveryType()->getName(): ''?></td>
		<td><?=@$order->sposob?$spos[$order->sposob]:''?></td>
        <td><?=@$order->comments?></td>
		<td><?php 
		

		if ($r->getRemarks()->count()) { ?>
		
		
<?php
$text = '';
 foreach ($r->getRemarks() as $remark) { $text.=$remark->getRemark()."-".$remark->getName(); } ?>
<i class="icon ion-ios-chatboxes green tx-20 " data-placement="right"  data-tooltip="tooltip" title="" data-original-title="<?=$text?>"></i>
						<?php }	?>
							</td>
    </tr>
    <?php } ?>
</table>
<?php } else echo 'Нет записей'; ?>
<script>

function Calculat(e){
var sum = 0.00;
console.log(e);
//if ($('.order-item:checked').val()) {
jQuery.each($('.order-item:checked'), function () {

sum += Number($('#s_'+$(this).attr('name').substr(5)).html());
sum += Number($('#d_'+$(this).attr('name').substr(5)).html());
                    });
sum = sum.toFixed(2);
$('#sum_perekaz').html('Сумма поштового переказу '+sum+' грн.');
return true;
				//}

return false;
}

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
var nakladna = $('#nakladna').val();
if(nakladna.length == 0){ alert('Введите номер накладной!'); return false;}

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
			beforeSend: function( data ) { fopen('Возвраты', '<img  id="loading" src="/img/loader-article.gif">'); },
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
			},
			error: function( e ) { fopen('Ощибка', 'Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');}
		});
}else{
fopen('Ощибка', 'Вы не выбрали товары который нужно принять!');
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
			beforeSend: function( data ) { fopen('Загрузка', '<img  id="loading" src="/img/loader-article.gif">'); },
			type: "POST",
			url: '/admin/vozvrats/',
			dataType: 'json',
			data: '&method=return_order'+dat,
			success: function( data ) {
			console.log(data);
			if(data.send == 1){ $('#'+th).hide(); }
			fopen('Возвращение товара', data.text+' ( '+data.ss+' )');
			},
			error: function( e ) {
			fopen('Ошибка', 'Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
			}
		});
		}
return false;
}	
function print103(){
var ids = '';
if ($('.order-item:checked').val()) {
                    i = 0;
                    jQuery.each($('.order-item:checked'), function () {//order-item cheker
                        if (i != 0) {
                            ids += ',' + $(this).attr('name').substr(5);
                        } else {
                            ids += $(this).attr('name').substr(5);
                        }
                        i++;
                    });
					
					//alert(ids);
					window.open ( '/admin/vozrat/method/forma103/ids/' + ids , '_blank');
					}else{
					alert('Отметьте нужные заказы!');
					return false;
					}

return false;
}
function dell(th){
var nakladna = $('#nakladna').val();
if(nakladna.length == 0){ alert('Введите номер накладной!'); return false; }
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
			beforeSend: function( data ) {
			fopen('Удаление товара','<img  id="loading" src="/img/loader-article.gif">');
			},
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