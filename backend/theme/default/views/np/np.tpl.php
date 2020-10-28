<div class="card pd-20">
     <div class="card-header">
         <span id="ck_l" class="m-auto p-2">0</span>
        <div class="btn-group m-auto" role="group" aria-label="Basic example">
  <button type="button" onclick="createRegistr(); return false;" class="btn btn-secondary">Создать Реестр</button>
  <a href="/admin/novapochta/list/" class="btn btn-secondary">Список реестров</a>
  <button type="button" onclick="GoNp(); return false;" id="np_go" style="display:none;" class="btn btn-secondary">Сменить статус на "Отправлен"</button>
</div>
	<!--<a  class="button" href="#">Создать/Печать реестр</a>-->
	<!--<a href="#" class="button" onclick="GoNp(); return false;" id="np_go" style="display:none;float: right;" >Сменить статус на "Отправлен Новой Почтой"</a>-->

     </div>
     <div class="card-body">
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
<?php
if($this->all_order){ ?>
	
	<script>
    var clik_ok = 0;
    function chekAll() {
		if($('.chekAll').is(":checked")){
		$('.cheker').prop('checked', true);
		}else{
		$('.cheker').prop('checked', false);
		}
	var ck = $('#myTable').find('input[type=checkbox]:checked').length -1;
	if(ck > 0) {
	$("#np_go").show();
	}else{
            ck = 0;
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
	<table id="myTable" class="table table-hover table-bordered" >
            <thead>
	<tr>
	<th><label class="ckbox" data-tooltip="tooltip" title="Выделить все заказы"><input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span></label></th>
	<th>Заказ</th>
	<th>Статус</th>
	<th>Создан</th>
	<th>Отправлен</th>
	<th>ТТН</th>
	<th>Тип</th>
	<th>Город</th>
	<th>Отделение</th>
	</tr>
            </thead>
            <tbody>
	<?php
        $i = 0;
	foreach ($this->all_order as $or){ $i++; ?>
	<tr >
        <td>
            <?=$i?>
            <?php if($or->nakladna){ ?>
        <label class="ckbox">
            <input type="checkbox" class="order-item cheker" onclick="chek_l();" id="<?=$or->meest_id?>"  name="<?=$or->id?>"><span></span>
        </label>
                <?php } ?>
        </td>
	<td>
            <?php if($or->nakladna){
          $uuid =   Shopordersmeestexpres::getUuid($or->meest_id)->ref;
            ?>
            <div class="btn-group" role="group" aria-label="Basic example">
            <button class="btn bd bg-white tx-gray-600 btn-sm" onclick="window.open('https://my.novaposhta.ua/orders/printDocument/orders/<?=$uuid?>/type/pdf/apiKey/6007d99fef17e74a4cb6bc57c67557dd', '_blank'); return false;" type="button">
                <i class="icon ion-ios-print-outline" data-tooltip="tooltip" data-original-title="Печать ТТН"></i><span>A4</span>
   </button>
    <button class="btn bd bg-white tx-gray-600 btn-sm" onclick="window.open('https://my.novaposhta.ua/orders/printMarking100x100/orders/<?=$uuid?>/type/pdf/apiKey/6007d99fef17e74a4cb6bc57c67557dd', '_blank'); return false;" type="button">
                <i class="icon ion-ios-print-outline" data-tooltip="tooltip" data-original-title="Печать ТТН 100*100"></i><span>100/100</span>
   </button>
    <button class="btn bd bg-white tx-gray-600 btn-sm" onclick="deleteDoc('<?=$uuid?>', <?=$or->id?>);" type="button">
       <i class="icon icon ion-ios-trash-outline" data-tooltip="tooltip" data-original-title="Удалить ТТН"></i>
   </button>
            </div>
         <?php   echo $or->id; }else{ ?>
            <a href="/admin/novapochta/new/id/<?=$or->id?>/" target="blank"><?=$or->id?></a>
                <?php } ?>
        </td>
	<td><?=$this->order_status[$or->status]?></td>
	<td><?=$or->date_create?></td>
	<td><?=$or->order_go!='0000-00-00 00:00:00'?$or->order_go:''?></td>
	<td><?=$or->nakladna?$or->nakladna:''?></td>
	<td><?=$or->payment_method->name?><?=$or->isPay()?></td>
	<td><?=$or->city?></td>
	<td><?=$or->sklad?></td>
	</tr>
	<?php } ?>
            </tbody>
	</table>
        <?php } ?>
</div>
</div>    
<link rel="stylesheet" href="<?=$this->files?>/scripts/jquery-ui.css" type="text/css" media="screen">
<script  src="<?=$this->files?>/scripts/jquery-ui.js"></script>



<script>
function deleteDoc(e, r){
   // alert(e);
    $.ajax({
                url: '/admin/novapochta/?',
                type: 'POST',
                dataType: 'json',
                data: 'metod=delete_document&ref='+e+'&order='+r,
                success: function (res) {
                    console.log(res);
		if(res.data[0].Ref){
                    location.reload();
                    
                               }else{
                                    alert('Ошибка');
                                }
                },
                error: function(e){
                    $('<div/>', {  class: 'alert alert-danger alert-dismissible fade show m-2', html: '<strong>Ошибка!</strong> '+e+'.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' }).appendTo('div.card-header');
                }
            });
    return false;
}
	
	function GoNp(){
            var mas = [];
	 if ($('.order-item:checked').val()) {
                // var id = '';
                    jQuery.each($('.order-item:checked'), function () {
                         mas.push($(this).attr('name'));
                    });
                    console.log(mas);
                    window.location = '/admin/allstatus/id/' + mas.join(',') + '/status/13/';
//return false;
                }else{
                      $('<div/>', {  class: 'alert alert-danger alert-dismissible fade show m-2', html: '<strong>Ошибка!</strong>  Вы не выбрали заказы для смены статуса.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' }).appendTo('div.card-header');
                }
	}
	
	
	function createRegistr(){
	  var id = '';
          var mas = [];
            if ($('.order-item:checked').val()) {
                jQuery.each($('.order-item:checked'), function () {
                    mas.push($(this).attr('id'));
                });
				}
                  id = mas.join(',');              
	if( id != ''){
	$.ajax({
                url: '/admin/novapochta/?',
                type: 'POST',
                dataType: 'json',
                data: 'metod=new_registr&id='+id,
                success: function (res) {
				if(res.data[0].Ref){
                                     $('<div/>', {  class: 'alert alert-success alert-dismissible fade show m-2', html: '<strong>Ok!</strong> Реестр создан! Можно распечатать в разделе реестры.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' }).appendTo('div.card-header');
				//window.open("https://my.novaposhta.ua/scanSheet/printScanSheet/refs%5B%5D/"+res.data[0].Ref+"/type/html/apiKey/1e594a002b9860276775916cdc07c9a6");
				}
                },
                error: function(e){
                    $('<div/>', {  class: 'alert alert-danger alert-dismissible fade show m-2', html: '<strong>Ошибка!</strong> '+e+'.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' }).appendTo('div.card-header');
                }
            });
	
	}else{
         $('<div/>', {  class: 'alert alert-danger alert-dismissible fade show m-2', html: '<strong>Ошибка!</strong> Вы не выбрали накладные для добавления в реестр.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' }).appendTo('div.card-header');
	}
	return false;
	}
	

		
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