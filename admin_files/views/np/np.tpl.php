<img src="<?=SITE_URL.$this->getCurMenu()->getImage();?>" width="32" class="page-img" />
<h2><?=$this->getCurMenu()->getTitle();?></h2>
<?=$this->getCurMenu()->getPageBody();?>
<?php $type = array(8 => 'Онлайн', 16 =>'Наложка'); ?>
<div style="display:none;">
<!--<input type="text" name="city" placeholder="Город"  id="city" autocomplete="off" value=""/>
	<input type="text" name="cityx"  id="cityx"  value=""/>-->
	Отследить посылку НР по ТТН:<br><input name="ttn" class="form-control input" type="trxt" id="ttn">
	<button name="send_ttn" class="button" id="send_ttn" onclick="np_tracking($('#ttn').val());">Отследить</button>
	<p><select id="warehouses" style="display:none;"></select></p>
	</div>
	<!--<div >
	Отследить посылку УП по ТТН:<input name="ttnukr" class="form-control input" type="trxt" id="ttnukr">
	<button name="send_ttn_ukr"  class="button" id="send_ttn_ukr" onclick="ukr_tracking($('#ttnukr').val());">Отследить</button>
	</div>
	<div >
	Отследить посылку МистЕкспрес по ТТН:<input name="ttnmest" class="form-control input" type="trxt" id="ttnmest">
	<button name="send_ttn_mest"  class="button" id="send_ttn_mest" onclick="meest_tracking($('#ttnmest').val());">Отследить</button>
	</div>-->
	<?php  //echo $this->message;?>
<div id="result" style="text-align: center;">
<?php
if($this->getOrder()){
$or = $this->getOrder();
//$or->middle_name.' '.$or->name;
//$or->telephone;
$uuid_city = '';
$uuid_branch = '';
if(@$or->meest_id){
$mm = wsActiveRecord::useStatic('Shopordersmeestexpres')->findById($or->meest_id);
$uuid_city = $mm->uuid_city;
$uuid_branch = $mm->uuid_branch;
}
$name = $or->middle_name.' '.$or->name.' '.$or->telephone;
echo '<input type="text" hidden id="name" value="'.$name.'">';
 ?>
 <div class="row">
 <div class="panel panel-danger">
 <div class="panel-heading"><h3 class="panel-title"><?=$name?></h3></div>
 <div class="panel-body">
  <div class="row">
  <div class="col-sm-12 col-md-12 col-lg-12">
  <div class="panel panel-default" style="margin-bottom:5px;">
  <div class="panel-body">
 <div class="form-group" style="display:  inline-block;">
    <label for="order" class="ct-110 control-label">Заказ:</label>
    <div class="col-xs-6">
	<input type="text" class="form-control input" name="clientbarcode" id="clientbarcode" disabled value="<?=$or->id;?>" >
    </div>
  </div>
   <div class="form-group" style="display:  inline-block;">
    <label for="order" class="ct-110 control-label">Статус:</label>
    <div class="col-xs-6">
	<input type="text" class="form-control input" name="status"  disabled id="Status" value="<?=$this->order_status[$or->status];?>">
    </div>
  </div>
     <div class="form-group" style="display:  inline-block;">
    <label for="order" class="ct-110 control-label">Тип оплаты:</label>
    <div class="col-xs-6">
	<input type="text" class="form-control input" name="type"  disabled id="Type" data-id="<?=$or->delivery_type_id?>" value="<?=$type[$or->delivery_type_id]?>">
    </div>
  </div>

  <div class="form-group" style="display:  inline-block;">
    <label for="order" class="ct-110 control-label">Объём:</label>
    <div class="col-xs-6">
	<input disabled  class="form-control input" type="text" name="volumegeneral" id="volumegeneral" value="0.0004"  >
    </div>
  </div>
  </div>
  </div>
   </div>
   <div class="col-sm-12 col-md-12 col-lg-12">
   
     <div class="panel panel-default" style="margin-bottom:5px;">
  <div class="panel-body">
<div class="col-sm-12 col-md-12 col-lg-6" style="    text-align: left;">
			<div class="form-group">
     <label class="form-control-label">Город: <span class="tx-danger">*</span></label>
	<input type="text" class="form-control " name="sityrecipient_np" autocomplete="off" required id="CityRecipient_np" value="<?php if(@$uuid_city){ echo $or->city;}?>"><input type="text" hidden name="citycecipient" id="CityRecipient" value="<?=$uuid_city?>" >
      </div>
	  
		<div class="form-group">
     <label class="form-control-label">Отделение: <span class="tx-danger">*</span></label>
<input class="form-control " type="text" autocomplete="off" name="recipient_np" id="Recipient_np" value="<?php if(@$uuid_branch){ echo $or->sklad;}?>"><input type="text" hidden name="recipient" id="Recipient" value="<?=$uuid_branch?>" >
      </div>

		<div class="form-group">
     <label class="form-control-label">Получатель: <span class="tx-danger">*</span></label>
	 <div>
<input class="form-control" type="text" autocomplete="off" name="recipientname_np" id="RecipientName_np" value="" style="width:80%; float:left;" required>
<input type="text" hidden name="recipientname" id="RecipientName" value="" ><input type="button" class="btn btn-small btn-default" data-placement="right"  data-tooltip="tooltip" title="Добавить новый контакт" value="+" id="new_contr"><input type="button" style="display:none;" class="btn btn-small btn-default" data-placement="right"  data-tooltip="tooltip" title="Редактировать контакт" value="..." id="update_contr">
</div>
      </div>
		<div class="form-group">
     <label class="form-control-label">Телефон: <span class="tx-danger">*</span></label>
<input class="form-control" type="text" autocomplete="off" name="phones" id="Phones" value="" required>
      </div>
	</div>
	
	<div class="col-sm-12 col-md-12 col-lg-6" style="    text-align: left;">
	<div class="form-group">
     <label class="form-control-label">Масса: <span class="tx-danger">*</span></label>
<input class="form-control" type="text" name="weight" id="weight"  value="1" required>
      </div>
	  	<div class="form-group">
     <label class="form-control-label">Тип посылки: <span class="tx-danger">*</span></label>
	 <div>
<select name="сargoеype" id="сargoеype" class="form-control " style="width: 50%;    float: left;" data-placement="right" required  data-tooltip="tooltip" title="Посылка <= 30кг. Груз > 30кг." ><option value="Parcel" >Посылка</option><option  value="Cargo">Груз</option></select><input type="text" name="s" id="s" placeholder="Ш" class="form-control " style="width:50px;    float: left;"><input type="text" name="d" id="d" class="form-control" style="width:50px;    float: left;" placeholder="Д"><input type="text" name="v" id="v" placeholder="В" class="form-control " style="width:50px;">
      </div>
	  </div>
<div class="form-group">
     <label class="form-control-label">Сумма: <span class="tx-danger">*</span></label>
<input class="form-control " type="text" name="cost" required id="cost" value="<?=$or->amount;?>" >
      </div>
	  <div class="form-group">
     <label class="form-control-label">Мест: <span class="tx-danger">*</span></label>
<input class="form-control " type="text" name="seatsamount" required id="seatsamount" value="1">
      </div>
	</div>
  </div>
   </div>
   </div>
 </div>
<input align="center" type="button" style="margin-top: 15px;" class="btn btn-lg btn-primary" data-placement="right"  data-tooltip="tooltip" title="Создать накладную" value="Создать" id="new_ttn">
<p align="center" id="pageslist"></p>
</div>
</div>
</div>
<?php } ?>

	<?php 
	if($this->all_order and !isset($_GET['id'])){
	//require_once('np/NovaPoshta.php');
	//$np = new NovaPoshta('2c28a9c1a5878cb01c8f9c440e827a61','ru', true, 'curl' );
	?>
	<div style="text-align: left;    margin-top: 10px;">
	<a onclick="chekAll(); return false;" style="padding-right: 5px;" href="#">Отметить/Снять все</a> -  <span id="ck_l">0</span>
	<a onclick="printRegistr(); return false;" class="button" href="#">Создать/Печать реестр</a>
	<a href="#" class="button" onclick="GoNp(); return false;" id="np_go" style="display:none;float: right;" >Сменить статус на "Отправлен Новой Почтой"</a></div>
	<script>
    var clik_ok = 0;
    function chekAll() {
        if (!clik_ok) {
            $('.cheker').attr('checked', true);
            clik_ok = 1;
        } else {
            $('.cheker').attr('checked', false);
            clik_ok = 0;
        }
	var ck = $('#myTable').find('input[type=checkbox]:checked').length;
	if(ck > 0) {
	$("#np_go").show();
	}else{
	$("#np_go").hide();
	}
	$("#ck_l").html(ck);
//console.log(ck);
        return false;
    }
		function chek_l(){
		var ck = $('#myTable').find('input[type=checkbox]:checked').length;
		if(ck > 0) {
	$("#np_go").show();
	}else{
	$("#np_go").hide();
	}
	$("#ck_l").html(ck);
	return false;
	};
	
</script>
	<table id="myTable" class="table table-hover" >
	<tr>
	<th></th>
	<th>Заказ</th>
	<th>Статус</th>
	<th>Создан</th>
	<th>Отправлен</th>
	<th>ТТН</th>
	<th>Тип</th>
	<th>Город</th>
	<th>Отделение</th>
	</tr>
	<?php
	$row = 'row2';
	foreach ($this->all_order as $or){
	 $row = ($row == 'row2') ? 'row1' : 'row2';
	?>
	<tr class="<?=$row;?>" >
	<td><?php if(@$or->nakladna) echo '<input type="checkbox" class="order-item cheker" onclick="chek_l();" id="'.$or->meest_id.'" name="'.$or->id.'" />';?></td>
	<td><?php if(@$or->nakladna){ echo $or->id;}else{ ?><a href="/admin/novapochta/?id=<?=$or->id?>" target="blank"><?=$or->id?></a><?php } ?></td>
	<td><?=$this->order_status[$or->status];?></td>
	<td><?=$or->date_create?></td>
	<td><?php if(@$or->order_go != '0000-00-00 00:00:00') echo $or->order_go;?></td>
	<td><?php if(@$or->nakladna) echo @$or->nakladna;?></td>
	<td><?=$type[$or->delivery_type_id]?></td>
	<td><?=$or->city;?></td>
	<td><?=$or->sklad;?></td>
	</tr>
	<?php
	}
	echo '</table>';
	}
	?>
</div>
<link rel="stylesheet" href="/admin_files/scripts/jquery-ui.css" type="text/css" media="screen">
<script  src="/admin_files/scripts/jquery-ui.js"></script>
<script src="/js/call/jquery.mask.js?v=20131212"></script>

<script>
$(function(){ $("#Phone").mask("389999999999");});
$(function(){ $("#Phones").mask("389999999999");});
    $(document).ready(function () {
	
//var ck = (':checkbox:checked').length;
//console.log(ck);
	});
	
	function GoNp(){
	 if ($('.order-item:checked').val()) {
                    id = '';
                    i = 0;
                    jQuery.each($('.order-item:checked'), function () {
                      if (i != 0) {
						id+= ',' + $(this).attr('name');
                    } else {
						id += $(this).attr('name');
                    }
                        i++;
                    });
					//console.log(id);
                    window.location = '/admin/allstatus/id/' + id + '/status/6';

                }
	}
	
	
	function printRegistr(){
	  var id = '';
            if ($('.order-item:checked').val()) {
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
						id+= ',' + $(this).attr('id');
                    } else {
						id += $(this).attr('id');
                    }
                    i++;
                });
				}
	
	if( id != ''){
	$.ajax({
                url: '/admin/novapochta/?',
                type: 'POST',
                dataType: 'json',
                data: 'metod=new_registr&id='+id,
                success: function (res) {
				console.log(res);
				if(res.data[0].Ref){
				fopen('РЕЕСТР','СОЗДАН РЕЕСТР №'+res.data[0].Number);
				window.open("https://my.novaposhta.ua/scanSheet/printScanSheet/refs%5B%5D/"+res.data[0].Ref+"/type/html/apiKey/2c28a9c1a5878cb01c8f9c440e827a61");
				}
                }
            });
	
	}else{
	fopen('Создание реестра','Вы не выбрали накладные для побавления в реестр');
	}
	return false;
	}
	

	$('#new_ttn').click(function(){
	$('#new_ttn').hide();
	var dat = 'metod=new_ttn';
	var valid = true;
	if($('#clientbarcode').val()) { dat+='&clientbarcode='+$('#clientbarcode').val(); $('#clientbarcode').removeClass("red_error"); }else{$('#clientbarcode').addClass("red_error"); $('#clientbarcode').focus(); valid = false;}
	if($('#Type').val()) { dat+='&type='+$('#Type').data('id'); $('#Type').removeClass("red_error"); }else{$('#Type').addClass("red_error"); $('#Type').focus(); valid = false;}
	if($('#CityRecipient').val()) { dat+='&cityrecipient='+$('#CityRecipient').val(); $('#CityRecipient_np').removeClass("red_error"); }else{$('#CityRecipient_np').addClass("red_error"); $('#CityRecipient_np').focus(); valid = false;}
	if($('#Recipient').val()) { dat+='&recipient='+$('#Recipient').val(); $('#Recipient_np').removeClass("red_error"); }else{$('#Recipient_np').addClass("red_error"); $('#Recipient_np').focus(); valid = false;}
	if($('#RecipientName').val()) { dat+='&recipientname='+$('#RecipientName').val(); $('#RecipientName_np').removeClass("red_error"); }else{$('#RecipientName_np').addClass("red_error"); $('#RecipientName_np').focus(); valid = false;}
	if($('#Phones').val()) { dat+='&phones='+$('#Phones').val(); $('#Phones').removeClass("red_error"); }else{$('#Phones').addClass("red_error"); $('#Phones').focus(); valid = false;}
	if($('#weight').val()) { dat+='&weight='+$('#weight').val(); $('#weight').removeClass("red_error"); }else{$('#weight').addClass("red_error"); $('#weight').focus(); valid = false;}
	if($('#сargoеype').val()) { dat+='&сargoеype='+$('#сargoеype').val(); $('#сargoеype').removeClass("red_error"); }else{$('#сargoеype').addClass("red_error"); $('#сargoеype').focus(); valid = false;}
	if($('#volumegeneral').val()) {
	dat+='&volumegeneral='+$('#volumegeneral').val();
	$('#v').removeClass("red_error"); $('#s').removeClass("red_error"); $('#d').removeClass("red_error");
	}else{
	$('#v').addClass("red_error"); $('#s').addClass("red_error"); $('#d').addClass("red_error"); valid = false;
	}
	if($('#cost').val()) { dat+='&cost='+$('#cost').val(); $('#cost').removeClass("red_error"); }else{$('#cost').addClass("red_error"); $('#cost').focus(); valid = false;}
	if($('#seatsamount').val()) { dat+='&seatsamount='+$('#seatsamount').val(); $('#seatsamount').removeClass("red_error"); }else{$('#seatsamount').addClass("red_error"); $('#seatsamount').focus(); valid = false;}
	//console.log(dat);
	if(valid == true){ 
	//if(false){
	console.log(dat);
	var url = '/admin/novapochta/?';
	$.ajax({
		beforeSend: function( data ) {
		$('#pageslist').html('<img  id="loading" src="/img/loader-article.gif">');
			},
                url: url,
                type: 'POST',
                dataType: 'json',
                data: dat,
                success: function (res) {
				$('#pageslist').html('');
				console.log(res);
				//console.log(res.errors.length);
				if(res.errors.length == 0){
				if(res.data[0].Ref){
				$('#popup').html(res.data[0].IntDocNumber);
				window.open("https://my.novaposhta.ua/orders/printDocument/orders/"+res.data[0].Ref+"/type/html/apiKey/2c28a9c1a5878cb01c8f9c440e827a61", '_blank');
				}
				}else{
				$('#popup').html(res.errors[0]);
				}
				fopen();
                }
            });
	}else{
	$('#new_ttn').show();
	}
	
	});
	//редактировать контакт
	$('#update_contr').click(function(){
	var name = $('#RecipientName_np').val().split(' ');
	var ref = $('#RecipientName').val();
	var pho = $('#Phones').val();
	$('#popup').html('<p>Редактирование контактной особы:</p><form action="" method="POST" id="myform" align="center"><input type="text" hidden name="ref" id="ref" value="'+ref+'"><input type="text" placeholder="Фамілія"  name="lastname" id="LastName" value=""  class="form-control input"><input type="text" placeholder="Ім`я"  name="firstname" id="FirstName" value=""  class="form-control input"><input type="text"  placeholder="По батькові" name="middlemame" id="MiddleName" value=""  class="form-control input"><input type="text" placeholder="Телефон"  name="phone" id="Phone" value="'+pho+'"  class="form-control input"><br><input type="button" class="button" value="Редактировать" id="go_update_contr"></form>');
	fopen();
	$('#go_update_contr').click(function(){
	//var f = $('#myform').serialize();
	//console.log(f);
	var url = '/admin/novapochta/?';
var new_data = 'metod=update_counterparties&ref='+$('#ref').val()+'&lastname='+$('#LastName').val()+'&firstname='+$('#FirstName').val()+'&middlename='+$('#MiddleName').val()+'&phone='+$('#Phone').val();
		//console.log(url+new_data);
	$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				$('#popup').html('');
				console.log(res);
				//console.log(res.data[0]);
				if(res.errors.length > 0){
				$('#popup').append(res.errors[0]+'<br>');
				}
				if(res.warnings.length > 0){
				$('#popup').append(res.warnings[0]+'<br>');
				}
				if(res.data.length > 0){
				var tx = '';
				tx += res.data[0]['Description'];
				tx += '<br>'+res.data[0]['Phones'];
				$('#popup').append(tx);
				}
                }
            });
	});
	});
	//Создать контакт
	$('#new_contr').click(function(){
	var n = $('#name').val();
	var name = arr = n.split(' ');
	if(name.length == 3){
	name[3] = name[2];
	name[2] = '';
	}
	console.log(name);
	$('#popup').html('<p>Нова контактна особа:</p><form action="" method="POST" id="myform" align="center"><input type="text" placeholder="Фамілія"  name="lastname" id="LastName" value="'+name[0]+'"  class="form-control input"><input type="text" placeholder="Ім`я"  name="firstname" id="FirstName" value="'+name[1]+'"  class="form-control input"><input type="text"  placeholder="По батькові" name="middlemame" id="MiddleName" value="'+name[2]+'"  class="form-control input"><input type="text" placeholder="Телефон"  name="phone" id="Phone" value="'+name[3]+'"  class="form-control input"><br><input type="button" class="button" value="Создать" id="go_new_contr" ></form>');
	fopen();
	$('#go_new_contr').click(function(){
var url = '/admin/novapochta/?';
var new_data = 'metod=new_counterparties&lastname='+$('#LastName').val()+'&firstname='+$('#FirstName').val()+'&middlename='+$('#MiddleName').val()+'&phone='+$('#Phone').val();
		//console.log(url+new_data);
	$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				$('#popup').html('');
				console.log(res);
				//console.log(res.data[0]);
				if(res.errors.length > 0){
				$('#popup').append('Error: '+res.errors[0]+'<br>');
				}
				if(res.warnings.length > 0){
				$('#popup').append('Warnings: '+res.warnings[0]+'<br>');
				}
				if(res.data.length > 0){
				var tx = '';
				tx += res.data[0]['Description'];
				tx += '<br>'+res.data[0]['Phones'];
				$('#popup').append(tx);
				}
                }
            });
	});
	
	
	});
	//Поиск города
	$('#CityRecipient_np').autocomplete({
			source: '/admin/novapochta/?what=citynpochta&term=' + $( '#CityRecipient_np').val(),
			minLength: 3,
			maxHeight: 200,
			deferRequestBy: 300,
			search: function( event, ui ) {
			//console.log(ui);
				$('#CityRecipient').val('');
			},
			select: function (event, ui) {
			//console.log(ui);
				if (ui.item == null) {
					$('#CityRecipient').val('');
				} else {
					$('#CityRecipient').val(ui.item.id);
					$('#Recipient_np').val('');
					$('#RecipientName_np').val('');
					document.cookie = "uid_city="+ui.item.id;
				}
			}
		});
	//поиск отделения
	$('#Recipient_np').autocomplete({
			source: "/admin/novapochta/?what=warehouses&term=" + $('#Recipient_np').val(),
			minLength: 1,
			maxHeight: 200,
			deferRequestBy: 300,
			search: function( event, ui ) {
				$('#Recipient').val('');
			},
			select: function (event, ui) {
			//console.log(ui);
				if (ui.item == null) {
					$('#Recipient').val('');
				} else {
					$('#Recipient').val(ui.item.id);
					document.cookie = "uid_warehouses="+ui.item.id;	
				}
			}
		});
		//поиск контакта
$('#RecipientName_np').autocomplete({
			source: "/admin/novapochta/?what=counterparties&term=" + $( '#RecipientName_np').val(),
			minLength: 3,
			maxHeight: 200,
			deferRequestBy: 300,
			search: function( event, ui ) {
			//console.log(ui);
				$('#RecipientName').val('');
			},
			select: function (event, ui) {
			//console.log(ui);
				if (ui.item == null) {
					$('#RecipientName').val('');
				} else {
					$('#RecipientName').val(ui.item.id);
					$('#Phones').val(ui.item.phones);
					document.cookie = "uid_counterparties="+ui.item.id;
					$('#new_contr').hide();
					$('#update_contr').show();
					
				}
			}
		});	
	//ввод только чисел в отслеживание
	  $('#ttn').keypress(function(e){
      e = e || event;
      if (e.ctrlKey || e.altKey || e.metaKey) return;
	  if(e.which > 47 && e.which < 58 ){
	  return;
	  }else{
	   return false;
	  }
    });
	// установка типа посылки
	$("#weight").keyup(function(e){
		var inp = $(this).val();
		if(inp > 30){
		//console.log($(this).val());
		$("#сargoеype [value='Cargo']").attr("selected", "selected");
		}
		if(inp <= 30){
		$("#сargoеype [value='Parcel']").attr("selected", "selected");
		}
});

$("#s").keyup(function(e){
if($("#d").val() && $("#v").val()){
var m = $("#d").val()*$("#v").val()*$(this).val();
$("#volumegeneral").val(m/1000000);
$("#weight").val(0.10);
}
});
$("#v").keyup(function(e){
if($("#s").val() && $("#d").val()){
var m = $("#s").val()*$("#d").val()*$(this).val();
$("#volumegeneral").val(m/1000000);
$("#weight").val(0.10);
}
});
$("#d").keyup(function(e){
if($("#s").val() && $("#v").val()){
var m = $("#s").val()*$("#v").val()*$(this).val();
$("#volumegeneral").val(m/1000000);
$("#weight").val(0.10);
}
});
/*
$('#sel').change(function(){
        var inp = $(this).val();
		document.getElementById("cities").value=inp;
		var opt = document.querySelector("#sel option[value='"+inp+"']");
	if (opt) myNP(opt.dataset.value);
		});*/
// проверка раскладки
/*
 $('#cities').keyup(function(e){ if($(this).val().match(/([a-zA-ZёЁэы]+)/)){ document.getElementById('leb').style.display='block';}else{ document.getElementById('leb').style.display='none';} });
 
		//обработка инпута городов
		document.getElementById("cities").oninput = function(){
		var inp = $(this).val();
		var opt = document.querySelector('#citieslist option[value="'+inp+'"]');
		if (opt) np_warehouses(opt.dataset.value);
}*/
//получение отделений
	function np_warehouses (x) {
$.get('/admin/novapochta/warehouses/'+x+'/metod/warehouses/',
		function (data) {
		 document.getElementById('warehouses').style.display='block';
		 $('#warehouses').html(data);
		});
		return false;
}
//получение информации по посылке новой почты
function np_tracking(x) {
//alert(x.length);
$.get('/admin/novapochta/tracking/'+x+'/metod/tracking/',
		function (data) {
		if(data){
		 $('#popup').html(data);
		 fopen();
		 }
		});
		return false;
}
//получение информации по посылке укр почты
function ukr_tracking(x) {
$.get('/admin/novapochta/ukr/'+x+'/metod/ukr/',
		function (data) {
		if(data){
		 $('#popup').html('<p>'+data+'</p>');
		 fopen();
		 }
		});
		return false;
}
//получение информации по посылке миск експрес
function meest_tracking(x) {
//alert(x.length);

$.get('/admin/addmeestttn/ttn/'+x+'/metod/tracking/',
		function (data) {
		if(data){
		//alert(data);
		 $('#popup').html(data);
		 fopen();
		 }
		});

		return false;
}
</script>
