<div class="card pd-20">
     <div class="card-body">
<?php 
$type = array(8 => 'Онлайн', 16 =>'Наложка'); 
if($this->getOrder()){
$or = $this->getOrder();
//$or->middle_name.' '.$or->name;
//$or->telephone;
$uuid_city = '';
$uuid_branch = '';
if($or->meest_id){
$mm = wsActiveRecord::useStatic('Shopordersmeestexpres')->findById($or->meest_id);
$uuid_city = $mm->uuid_city;
$uuid_branch = $mm->uuid_branch;
}
$name = $or->middle_name.' '.$or->name.' '.$or->telephone;
echo '<input type="text" hidden id="name" value="'.$name.'">';
 ?>
 <div class="card">
     <div class="card-header bg-danger text-white">
         <h6 class=" "><?=$name?></h6>
     </div>
 
 <div class="card-body">
  <div class="row">
  <div class="col-sm-12 col-md-12 col-lg-12 text-center">

 <div class="form-group" style="display:  inline-block;">
    <label for="order" class="ct-110 control-label">Заказ:</label>
    <div class="col-xs-6">
	<input type="text" class="form-control input" name="clientbarcode" id="clientbarcode" disabled value="<?=$or->id;?>" >
    </div>
  </div>
   <div class="form-group" style="display:  inline-block;">
    <label for="order" class="ct-110 control-label">Статус:</label>
    <div class="col-xs-6">
	<input type="text" class="form-control input" name="status"  disabled id="Status" value="<?=$or->getStat()->getName()?>">
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
   <div class="col-sm-12 col-md-12 col-lg-12">
       <div class="row">
<div class="col-sm-12 col-md-12 col-lg-6" style="    text-align: left;">
			<div class="form-group">
     <label class="form-control-label">Город: <span class="tx-danger">*</span></label>
	<input type="text" class="form-control " name="sityrecipient_np" autocomplete="off" required id="CityRecipient_np" value="<?php if($uuid_city){ echo $or->city;}?>">
        <input type="text" hidden name="citycecipient" id="CityRecipient" value="<?=$uuid_city?>" >
      </div>
	  
		<div class="form-group">
     <label class="form-control-label">Отделение: <span class="tx-danger">*</span></label>
<input class="form-control " type="text" autocomplete="off" name="recipient_np" id="Recipient_np" value="<?php if($uuid_branch){ echo $or->sklad;}?>">
<input type="text" hidden name="recipient" id="Recipient" value="<?=$uuid_branch?>" >
      </div>

	<div class="form-group">
     <label class="form-control-label">Получатель: <span class="tx-danger">*</span></label>
<div class="input-group">
<input class="form-control" type="text" autocomplete="off" name="recipientname_np" id="RecipientName_np" value=""  required>
<div class="input-group-append">
<input type="text" hidden name="recipientname" id="RecipientName" value="" >
<input type="button" class="btn btn-outline-secondary" data-placement="right"  data-tooltip="tooltip" title="Добавить новый контакт" value="+" id="new_contr">
<input type="button" style="display:none;" class="btn btn-outline-secondary" data-placement="right"  data-tooltip="tooltip" title="Редактировать контакт" value="..." id="update_contr">
</div>
         </div>
      </div>
		<div class="form-group">
     <label class="form-control-label">Телефон: <span class="tx-danger">*</span></label>
<input class="form-control" type="text" autocomplete="off" name="phones" id="Phones" value="" required>
      </div>
	</div>
	
	<div class="col-sm-12 col-md-12 col-lg-3" style="    text-align: left;">
	<div class="form-group">
     <label class="form-control-label">Масса: <span class="tx-danger">*</span></label>
     <select class="form-control"  name="weight" id="weight" required >
         <option value="1" selected >1кг</option>
         <option value="1.5" >1.5кг</option>
         <option value="0.10" >0.10кг(коробки)</option>
     </select>

      </div>
	  	
<div class="form-group">
     <label class="form-control-label">Сумма: <span class="tx-danger">*</span></label>
<input class="form-control " type="text" name="cost" required id="cost" value="<?=$or->amount;?>" >
      </div>
            <div class="form-group">
                 <label class="form-control-label">Платит за доставку:</label>
                <div>
            <label class="rdiobox"><input name="PayerType" checked type="radio" value="Recipient"><span>Получатель</span></label>
            <label class="rdiobox"><input name="PayerType" type="radio" value="Sender"><span>Отправитель</span></label>
            </div>
            </div>
	</div>
           <div class="col-sm-12 col-md-12 col-lg-3" style="    text-align: left;">
               <div class="form-group">
                    <input class="form-control" type="text" hidden name="сargoеype" id="сargoеype"  value="Parcel">
     <label class="form-control-label">Коробка:</label>
	 <div>
<select  id="box" class="form-control" placeholder="Размер коробки"  >
    <option label="Коробка"></option>
    <option value="36" >36/20/20</option>
    <option  value="38">38/28/23</option>
     <option  value="40">40/40/40</option>
</select>
             <input type="text" name="s" hidden id="s" placeholder="Ш" class="form-control " >
             <input type="text" name="d" hidden id="d" class="form-control"  placeholder="Д">
             <input type="text" name="v" hidden id="v" placeholder="В" class="form-control " >
      </div>
	  </div>
               <div class="form-group">
     <label class="form-control-label">Мест: <span class="tx-danger">*</span></label>
<input class="form-control " type="text" name="seatsamount" required id="seatsamount" value="1">
      </div>
           </div>

</div>
   </div>
      <div class="col-sm-12 text-center mt-3">
          <input  type="button" style="cursor: pointer;"  class="btn btn-lg btn-outline-dark m-auto"  value="Создать посылку" id="new_ttn">

      </div>
 </div>
<p align="center" id="pageslist"></p>
<div id="alert"></div>
</div>
</div>
<?php 

}
?>
     </div>
</div>
<link rel="stylesheet" href="<?=$this->files?>/scripts/jquery-ui.css" type="text/css" media="screen">
<script  src="<?=$this->files?>scripts/jquery-ui.js"></script>
<script src="/js/call/jquery.mask.js?v=20131212"></script>
<script>
$(function(){ $("#Phone").mask("389999999999");});
$(function(){ $("#Phones").mask("389999999999");});

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
	//$('#v').removeClass("red_error"); $('#s').removeClass("red_error"); $('#d').removeClass("red_error");
	}//else{
	//$('#v').addClass("red_error"); $('#s').addClass("red_error"); $('#d').addClass("red_error"); valid = false;
	//}
	if($('#cost').val()) { dat+='&cost='+$('#cost').val(); $('#cost').removeClass("red_error"); }else{$('#cost').addClass("red_error"); $('#cost').focus(); valid = false;}
	if($('#seatsamount').val()) { dat+='&seatsamount='+$('#seatsamount').val(); $('#seatsamount').removeClass("red_error"); }else{$('#seatsamount').addClass("red_error"); $('#seatsamount').focus(); valid = false;}
	
        if($("input[name='PayerType']:checked").val()){
            dat+='&payer_type='+$("input[name='PayerType']:checked").val(); $("input[name='PayerType']").removeClass("red_error");
        }else {
            $("input[name='PayerType']").addClass("red_error"); $("input[name='PayerType']").focus(); valid = false;
        }
        //console.log(dat);
	if(valid == true){ 
	//if(false){
	console.log(dat);
       // return false;
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
    $('#alert').html('<div class="alert alert-success" role="alert"><strong>Успех!</strong> ТТН: '+res.data[0].IntDocNumber+'</div>');
   // setTimeout(function(){$('#alert').html('')}, 5000);
				//$('#popup').html(res.data[0].IntDocNumber);
				//window.open("https://my.novaposhta.ua/orders/printDocument/orders/"+res.data[0].Ref+"/type/html/apiKey/1e594a002b9860276775916cdc07c9a6");//, '_blank'
//console.log(res.print.data[0]);	
window.location.replace(res.print.data[0]);
   // window.location.replace("https://my.novaposhta.ua/orders/printMarking100x100/orders/"+res.data[0].Ref+"/type/pdf/apiKey/920af0b399119755cbca360907f4fa60");//, '_blank'
                                }
				}else{
                                $('#new_ttn').show();
                                        //$('#popup').html(res.errors[0]);
                                $('#alert').html('<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> '+res.errors[0]+'</div>');
   // setTimeout(function(){$('#alert').html('')}, 5000);
				}
				//fopen();
                }
            });
	}else{
	$('#new_ttn').show();
	}
	
	});
	//редактировать контакт
	$('#update_contr').click(function(){
	var name = $('#RecipientName_np').val().split(' ');
        if(typeof name[0] !== "undefined"){
             var last = name[0];
        }else{
             var last =  '';
        }
        if(typeof name[1] !== "undefined"){
             var first = name[1];
        }else{
             var first =  '';
        }
        if(typeof name[2] !== "undefined"){
             var midl = name[2];
        }else{
             var midl =  '';
        }
       
        //console.log(name);
	var ref = $('#RecipientName').val();
	var pho = $('#Phones').val();
	$('#popup').html('<p>Редактирование контактной особы:</p><form action="" method="POST" id="myform" align="center"><input type="text" hidden name="ref" id="ref" value="'+ref+'"><input type="text" placeholder="Фамілія"  name="lastname" id="LastName" value="'+last+'"  class="form-control input"><input type="text" placeholder="Ім`я"  name="firstname" id="FirstName" value="'+first+'"  class="form-control input"><input type="text"  placeholder="По батькові" name="middlemame" id="MiddleName" value="'+midl+'"  class="form-control input"><input type="text" placeholder="Телефон"  name="phone" id="Phone" value="'+pho+'"  class="form-control input"><br><input type="button" class="button" value="Редактировать" id="go_update_contr"></form>');
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
			console.log(ui);
				$('#CityRecipient').val('');
			},
			select: function (event, ui) {
			console.log(ui);
				if (ui.item == null) {
					$('#CityRecipient').val('');
				} else {
					$('#CityRecipient').val(ui.item.id);
					$('#Recipient_np').val('');
					$('#RecipientName_np').val('');
					document.cookie = 'uid_city='+ui.item.id+'; path=/;';
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
                                console.log(ui);
			},
			select: function (event, ui) {
			console.log(ui);
				if (ui.item == null) {
					$('#Recipient').val('');
				} else {
					$('#Recipient').val(ui.item.id);
					document.cookie = 'uid_warehouses='+ui.item.id+'; path=/;';
				}
			}
		});
		//поиск контакта
$('#RecipientName_np').autocomplete({
			source: "/admin/novapochta/new/?what=counterparties&term=" + $( '#RecipientName_np').val(),
			minLength: 3,
			maxHeight: 200,
			deferRequestBy: 300,
			search: function( event, ui ) {
                            console.log($( '#RecipientName_np').val());
			console.log(ui);
                        console.log(event);
				$('#RecipientName').val('');
			},
			select: function (event, ui) {
			//console.log(ui);
				if (ui.item == null) {
					$('#RecipientName').val('');
				} else {
					$('#RecipientName').val(ui.item.id);
					$('#Phones').val(ui.item.phones);
					document.cookie = "uid_counterparties="+ui.item.id+'; path=/;';
					$('#new_contr').hide();
					$('#update_contr').show();
					
				}
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

$("#box").change(function() { 
    $(this).val();
    switch($(this).val()){
        case '36' : 
            $("#volumegeneral").val((36*20*20)/1000000); 
            $('#weight option[value="0.10"]').prop('selected', true); 
            $("#s").val(36);$("#d").val(20);$("#v").val(20);
            break;
            case '38' : 
                $("#volumegeneral").val((38*28*23)/1000000); 
                $('#weight option[value="0.10"]').prop('selected', true); 
                $("#s").val(38);$("#d").val(28);$("#v").val(23);
                break;
                case '40' : 
                    $("#volumegeneral").val((40*40*40)/1000000); 
                    $('#weight option[value="0.10"]').prop('selected', true);
                    $("#s").val(40);$("#d").val(40);$("#v").val(40);
                    break;
                default : 
                    $("#volumegeneral").val(0.0004); 
                    $('#weight option[value="1"]').prop('selected', true);
                    $("#s").val('');$("#d").val('');$("#v").val('');
                    break;
    }
    return true;
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

</script>