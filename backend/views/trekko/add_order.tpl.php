<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt="" width="32" class="page-img" height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
  <!--  <p><a href="/admin/shop-orders/edit/id/<?php //echo $this->getId();?>">К заказу</a></p>-->
	<?php //$ttn = $this->order_trekko_edit; ?>
	<h3>Заказ №<?php echo $this->order_trekko_edit->getId(); ?></h3>
<table id="ttn_trekko" cellpadding="4" cellspacing="0" style="width:900px;">
<form method="post" action="" name="ttn" id="ttn">
<tr>
<th>Заказ</th>
<th>ФИО</th>
<th>Телефон</th>
<th>Адресс</th>
<th>Дата доставки</th>
<th>Время доставки</th>
<th>Сумма оплаты</th>
<th>Вес</th>
<th>Колл.мест</th>
<th>Комент</th>
</tr>
<tr>
<td><input style="width: 55px;" type="text" name="order" id="order" value="<?=$this->order_trekko_edit->id;?>"></td>
<td><input style="" type="text" name="name" id="name" value="<?=$this->order_trekko_edit->middle_name.' '.$this->order_trekko_edit->name;?>"></td>
<td><input style="width: 110px;" type="text" name="phone" id="phone" value="<?=$this->order_trekko_edit->telephone;?>"></td>
<td><input style="" type="text" name="address" id="address" value="<?php echo 'г.'.$this->order_trekko_edit->getCity().', '.$this->order_trekko_edit->getStreet().', д.'.$this->order_trekko_edit->getHouse().', кв.'.$this->order_trekko_edit->getFlat();?>"></td>
<td><input style="width: 75px;" type="text" name="delivery_date" id="delivery_date" value="<?=$this->order_trekko_edit->delivery_date;?>"></td>
<td><input style="width: 100px;" type="text" name="delivery_interval" id="delivery_interval" value="<?=$this->order_trekko_edit->delivery_interval;?>"></td>
<td><input style="width: 55px;" type="text" name="amount" id="amount" value="<?=$this->order_trekko_edit->amount;?>"></td>
<td><input style="width: 55px;" type="text" name="ves" value="1" id="ves" ></td>
<td><input style="width: 55px;" type="text" name="mest" id="mest" value="1"></td>
<td><input style="width: 55px;" type="text" name="koment" id="koment" value=" "></td>
</tr>
<tr><td><input hidden type="text" name="strachovka" value="<?=ceil($this->order_trekko_edit->amount);?>"></td></tr>
</table>
<input type="button" name="save" id="save" value="Создать">
</form>
<div id="mess" ><span id="mess_span" style="font-size:16px;color:red;"></span></div>
<p align="center" id="ss"></p>
<script type="text/javascript">
    $(document).ready(function () {
	$('#save').on( "click", function () {
	var valid = true;
	var message = '';
	if($('#strachovka').val() <= 0) { valid = false; message += ' Сумма страховки должна быть суммой заказа.';}
	if($('#amount').val() == '') { valid = false; message += ' Сумма заказа не может быть пустой!';}
	if($('#mest').val() == '' || $('#mest').val() <0) { valid = false; message += ' Количество мест неможет быть пустым или меньше 1';}
	if($('#ves').val() == '') { valid = false; message += ' Укажите массу';}
	if($('#delivery_interval').val() == '' && $('#massa').val() >= 1){ valid = false; message += ' Укажите время доставки!';}
	if($('#delivery_date').val() == '')  { valid = false; message += ' Укажите дату доставки';}
	if($('#address').val() == '')  { valid = false; message += ' Укажите адресс!';}
	if($('#phone').val().length < 10 && $('#phone').val() == '')  { valid = false; message += ' Телефон указан неверно!';}
	if($('#name').val() == '') { valid = false; message += ' Укажите имя';}
	if(valid == true){
	Go();
		}else{
		alert(message);
		}
		//return false;
	} );
	
	});
	
	function Go(){

		$.ajax({
			beforeSend: function( data ) {
				$('#save').attr('value', 'Создается...');
			},
			type: "POST",
			url: '/admin/trekko/',
			dataType: 'json',
			data: $("#ttn").serialize()+'&method=add_ttn',
			success: function( data ) {
			if(data.success == 1){
			$('#ss').attr('style', 'color:green;font-weight: bold;');
			$('#ss').html('Заказ '+data.response+' добавлен!');
			setTimeout(function(){$('#ss').fadeOut(500)}, 5000);
			
			}else{
			$('#ss').attr('style', 'color:red;font-weight: bold;');
			$('#ss').html(data.error+'!<br>КОД ОШИБКИ - '+data.code);
			setTimeout(function(){$('#ss').fadeOut(500)}, 8000); 
			//$('#ss').fadeOut(1000);
			}
				console.log(data);
			},
			complete: function( data ) {
			$('#save').attr('value', 'Создать');
			},
			error: function( e ) {
			console.log(e);
			alert('Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
			}
		});
		return true;
	}
</script>
