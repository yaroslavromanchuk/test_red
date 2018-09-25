<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
    <p><a href="/admin/shop-orders/edit/id/<?php echo $this->getId();?>">К заказу</a></p>
	<?php $ttn = wsActiveRecord::useStatic('Shopordersmeestexpres')->findFirst(array('id'=>$this->order->getMeestId()));?>
<table id="ttn_meest" cellpadding="4" cellspacing="0" style="width:900px;">
<form method="post" action="" name="ttn_form" id="ttn_form">
<tr ><td colspan="6"><h3>Заказ №<?php echo $this->order->getId(); ?></h3></td></tr>
<tr><td colspan="6">
<input name="meest_id" id="meest_id"  type="hidden" value="<?php echo $this->order->getMeestId();?>"/>
<input name="order_id" id="order_id"  type="hidden" value="<?php echo $this->order->getId();?>"/></td></tr>
<tr><td style="width: 150px;">Сервис*-></td><td  colspan="5"><select class="input" name="type_id" id="type_id"><option value="0" <?php if($ttn->type_id == 0) echo 'selected'; ?>  ><?php echo 'Склад';?></option><option value="1" <?php if($ttn->type_id == 1) echo 'selected'; ?>  ><?php echo 'Дверь'; ?></option></select></td></tr>
<?php if(!$ttn->uuid_city or !$ttn->uuid_street){ echo "<tr><td></td><td colspan='5' style='color:red;'><b>Адрес доставки сменился! Укажите новый адрес!</b></td></tr>";} ?>
<tr><td style="width: 150px;">Город*-></td><td  colspan="5"><input class="input" name="city" id="city" style="width: 300px;height: 25px;" autocomplete="off" type="text" value="<?php  if($ttn->uuid_city) echo $this->order->getCity();?>"/><input type="hidden" name="uuid_city" id="uuid_city" value="<?php echo $ttn->uuid_city; ?>"/></td></tr>

<tr id="br" <?php if($ttn->type_id == 1) echo 'style="display:none;" ';?>><td>Склад*-></td><td  colspan="5"><select class="input" name="uuid_branch" id="uuid_branch" style="width: 300px;height: 25px;" ><option value="<?php echo $ttn->uuid_branch; ?>"><?php echo 'Отделение'; ?></option></select></td></tr>

<tr id="st"><td style="width: 150px;">Улица*-></td><td><input class="input" style="width: 300px;height: 25px;" type="text" name="street" id="street" value="<?php if($ttn->uuid_street) echo $this->order->getStreet();?>"/><input type="hidden"  name="uuid_street" id="uuid_street" value="<?php echo $ttn->uuid_street; ?>"/></td><td>Дом-></td><td><input class="input" style="width: 100px;height: 25px;" name="house" type="text" value="<?php echo $this->order->getHouse();?>"/></td><td>Квартира-></td><td><input class="input" style="width: 70px;height: 25px;" name="flat" type="text" value="<?php echo $this->order->getFlat();?>"/><input class="input" placeholder="Поверх" style="width: 70px;height: 25px;" name="floor" type="text" value=""/></td></tr>


<tr><td style="width: 150px;">Получатель ФИО*-></td><td><input class="input" name="name" style="width: 300px;height: 25px;" type="text" value="<?php echo $this->order->getMiddleName().' '.$this->order->getName();?>"/></td><td>Телефон моб.*-></td><td><input class="input" style="width: 100px;height: 25px;" name="telephone" type="text" value="<?php echo $this->order->getTelephone();?>"/></td><td>Телефон-></td><td><input class="input" style="width: 100px;height: 25px;" name="telephone2" type="text" value=""/></td></tr>


<tr><td colspan="6"><h4>Параметры посылки:</h4></td></tr>

<tr><td style="width: 150px;">Формат посылки:*<select class="input" name="box" id="box"><option value="">Выберите тип посылки</option><option value="PAX" <?php if($ttn->box == 'PAX') echo 'selected'; ?>>Коробка</option><option value="DOX" <?php if($ttn->box == 'DOX') echo 'selected'; ?> >Пакет</option></select></td><td>Масса*</br><input class="input" style="width: 50px;height: 25px;" name="massa" id="massa" type="text" value=""/></td><td>Ширина</br><input class="input" style="width: 50px;height: 25px;" name="r_s" id="r_s" type="text" value="<?php echo $ttn->r_s; ?>"/></td><td>Длина</br><input class="input" style="width: 50px;height: 25px;" name="r_d" id="r_d" type="text" value="<?php echo $ttn->r_d; ?>"/></td><td>Высота</br><input class="input" style="width: 50px;height: 25px;" name="r_h" id="r_h" type="text" value="<?php echo $ttn->r_h; ?>"/></td><td>Сумма*<input class="input" style="width: 50px;height: 25px;" name="r_summa" id="r_summa" type="text" value="<?php echo $this->order->getAmount(); ?>"/></br>Страховка*<input  class="input" style="width: 50px;height: 25px;" name="r_strach" id="r_strach" type="text" value="<?php echo ceil($this->order->calculateOrderPrice(false, false, false)); ?>"/></td></tr>

<tr><td style="width: 150px;">Дата доставки-></td><td><select class="input" name="date_go" id="r_date" ></select><!--<input class="input datetime" style="height: 25px;" name="date_go" id="r_date" type="text" value="<?php //echo $ttn->date_go; ?>"/>--></br>Интервал:</br><select class="input" name="interval" id="interval"><option value="" selected>Выбрать...</option><option value="10:00-14:00">10-14</option><option value="14:00-18:00">14-18</option><option value="18:00-20:00">18-20</option><option value="10:00-18:00">10-18</option></select></td><td colspan="4"><input class="input" placeholder="Коментарий..." style="width: 480px;height: 25px;" name="r_koment" type="text" value="<?php echo $ttn->r_koment; ?>"/></td></tr>

<tr><td colspan="5"></td><td><input class="button" <?php if($ttn->status != 0) echo 'hidden';?> style="padding: 5px 12px;font-size: 16px;"  name="savepage1" id="savepage1" type="button" value="Создать"/></td></tr>
</table>
</form>

<div id="mess" ><span id="mess_span" style="font-size:16px;color:red;"></span></div>
<link rel="stylesheet" href="/admin_files/scripts/jquery-ui.css" type="text/css" media="screen">
<script type="text/javascript" src="/admin_files/scripts/jquery-ui.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
	if($('#uuid_street').val() !== ''){goDate();}
	
	//$('.datetime').datepicker();
	$('#type_id').change(function(){
			if (this.value == 0){
				$('#st').hide();
				//checkAPT();
			} 
			if (this.value == 1){
				jQuery('#br').hide();
				jQuery('#st').hide();
			} 
		});
		$('#box').change(function(){
			if (this.value == 'DOX'){
				$('#r_s').val(25);
				$('#r_d').val(20);
				$('#r_h').val(3);

			} 
			if (this.value == 'PAX'){
					$('#r_s').val(25);
				$('#r_d').val(20);
				$('#r_h').val(10);

			} 
		});
	
	
	
	$('#savepage1').on( "click", function () {
	var valid = true;
	var message = '';
	if($('#r_strach').val() <= 0) { valid = false; message += ' Сумма страховки должна быть суммой заказа.';}
	if($('#box').val() == '') { valid = false; message += ' Выберите тип посылки';}
	if($('#massa').val() == '') { valid = false; message += ' Укажите массу';}
	if($('#box').val() == "DOX" && $('#massa').val() >= 1){ valid = false; message += ' В пакете масса не должна прывишать 1 кг.';}
	if($('#uuid_city').val() == '')  { valid = false; message += ' Выберите Город со списка';}
	if($('#type_id').val() == 0 && $('#uuid_branch').val() == '')  { valid = false; message += ' Выберите отделение со списка';}
	if($('#type_id').val() == 1 && $('#uuid_street').val() == '')  { valid = false; message += ' Выберите улицу со списка';}
	if($('#r_date').val() == '0000-00-00') { valid = false; message += ' Укажите дату доставки';}
	if($('#interval').val() == '') { valid = false; message += ' Укажите интервал';}
	
	
	
	if(valid == true){
		
		$.ajax({
			beforeSend: function( data ) {
				$('#savepage1').attr('value', 'Сохраняется...');
			},
			type: "POST",
			url: '/admin/addmeestttn/',
			data: $("#ttn_form").serialize()+'&method=add_ttn',
			success: function( data ) {
			$('#mess_span').html(''+data.error+'');
				console.log(data.ttn);
				console.log(data.error);
				//alert(data.ttn);
			},
			dataType: 'json',
			complete: function( data ) {
			$('#savepage1').hide();
				//$('#savepage1').attr('value', 'Сохранить');
			},
			error: function( e ) {
			alert('Вы ввели что-то не верно! ТТН не создана, внесите изменения и попробуйте снова!');
			}
		});
		}else{
		alert(message);
		}
		//return false;
	} );
jQuery( '#city' ).autocomplete({
			source: '/admin/getmistcity/?what=city',
			minLength: 3,
			maxHeight: 200,
			deferRequestBy: 300,
			search: function( event, ui ) {
				jQuery('#uuid_city').val('');
			},
			select: function (event, ui) {
			//console.log(ui.item);
				if (ui.item == null) {
					jQuery('#uuid_city').val('');
				} else {
					jQuery('#uuid_city').val(ui.item.id);
					if ($('#type_id').val() == '1'){
					$('#st').show();
					$('#street' ).val('');
					$('#uuid_street' ).val('');
					var uid = $('#uuid_city').val();
						$.get('/admin/getmistcity/', { uid: uid});
						$('#street' ).focus();
					}else if($('#type_id').val() == '0'){
					$('#st').hide();
					getBranch();
					}
					
				}
			}
		});
		$('#street').autocomplete({
	source: '/admin/getmistcity/?what=street',
			minLength: 3,
			maxHeight: 200,
			deferRequestBy: 300,
			search: function( event, ui ) {
				jQuery('#uuid_street').val('');
			},
			select: function (event, ui) {
				if (ui.item == null) {
					jQuery('#uuid_street').val('');
				} else {
					$('#uuid_street').val(ui.item.id);
					goDate();
				}
			}
				});

    });
	
	function goDate(){
	var uidd = $( '#uuid_city').val();
		$.get('/admin/getmistcity/?what=dateplan&term=' + uidd,
		
			function (data) {
			$('#r_date').html(data);
			//$('#br').show();
					//$('#uuid_branch' ).focus();
			});
			return false;
	
	
	}
	
				//получение отделений
	function getBranch() {
	var uid = $( '#uuid_city').val();
		$.get('/admin/getmistcity/?what=branch&term=' + uid,
		
			function (data) {
			$('#uuid_branch').html(data);
			$('#br').show();
					$('#uuid_branch' ).focus();
			});
			return false;
				}


</script>
