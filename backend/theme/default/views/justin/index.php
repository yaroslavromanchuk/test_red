<div class="card pd-20 mb-2">
    <div class="card-body">
        <div class="card-title">Просмотр оформленых посылок по датам.</div>
  <form name="list_order" method="POST" class="form-inline">

  <div class="form-group mx-sm-3 mb-2">
    <label for="data" class="sr-only">Password</label>
    <input type="date" class="form-control" name="list_orders"  >
  </div>
  <button type="submit" class="btn btn-primary mb-2">Смотреть</button>
      </form>
</div>
    <div class="card-footer">
        
    </div>
    </div>
<?php
        if($this->orders->count()){ ?>
<div class="card pd-20">
    <div class="card-header">
        <span id="ck_l" class="m-auto p-2">0</span>
        <div class="btn-group m-auto" role="group" aria-label="Basic example">
        <button class="btn bd bg-white tx-gray-600 reestr" style="display: none;" onclick="createRegistr(); return false;" type="button">
       <i class="icon ion-ios-print" data-tooltip="tooltip" data-original-title="Создать реестр выбраных посылок">Создать реестр</i>
   </button>
            <button class="btn bd bg-white tx-gray-600 reestr" style="display: none;" onclick="StatusToVproceseDostavki(); return false;" type="button">
       <i class="icon ion-ios-print" data-tooltip="tooltip" data-original-title="Сменить статус на 'В процесе доставки'">Сменить статус</i>
   </button> 
            </div>
    </div>
    <div class="card-body">
         <h5 class="card-title">Посылки на оформлении:</h5>
	
               <table id="myTable" class="table table-hover table-bordered" >
            <thead>
	<tr>
	<th><label class="ckbox" data-tooltip="tooltip" title="Выделить все заказы"><input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span></label></th>
	<th>Заказ</th>
        <th>Статус</th>
	<th>Создан</th>
	<th>ТТН</th>
	<th>Доставка</th>
        <th>Оплата</th>
	<th>Отделение</th>
	</tr>
            </thead>
            <tbody>  
    <?php foreach ($this->orders as $or){ ?>
               <tr >
        <td><?php if($or->nakladna){ ?>
        <label class="ckbox">
            <input type="checkbox" class="order-item cheker" onclick="chek_l();" id="<?=$or->id?>"  name="<?=$or->nakladna?>"><span></span>
        </label>
                <?php } ?>
        </td>
	<td><?php if($or->nakladna){
          //$uuid = JustinRequestDeliveryInfo::getDelivery($or->nakladna);
            ?>
            <div class="btn-group" role="group" aria-label="Basic example">
           <!-- <button class="btn bd bg-white tx-gray-600 btn-sm" onclick="window.open('https://my.novaposhta.ua/orders/printDocument/orders/<?=$uuid?>/type/pdf/apiKey/920af0b399119755cbca360907f4fa60', '_blank'); return false;" type="button">
                <i class="icon ion-ios-print-outline" data-tooltip="tooltip" data-original-title="Печать ТТН"></i><span>A4</span>
   </button>-->
    <button class="btn bd bg-white tx-gray-600 btn-sm" onclick="stickers(<?=$or->id?>);" type="button">
                <i class="icon ion-ios-print-outline" data-tooltip="tooltip" data-original-title="Печать ТТН 100*100"></i><span></span>
   </button>
    <button class="btn bd bg-white tx-gray-600 btn-sm" onclick="deleteDoc(<?=$or->id?>);" type="button">
       <i class="icon icon ion-ios-trash-outline" data-tooltip="tooltip" data-original-title="Удалить ТТН"></i>
   </button>
                <button class="btn bd bg-white tx-gray-600 btn-sm" onclick="info(<?=$or->id?>);" type="button">
       <i class="icon icon ion-ios-help-outline" data-tooltip="tooltip" data-original-title="Посмотреть информацию"></i>
   </button>
            </div>
         <?php   echo $or->id; 
         
        }else{
            ?><a href="/admin/justin/new/id/<?=$or->id?>/" target="blank"><?=$or->id?></a><?php } ?></td>
        <td><?=$or->stat->name?></td>
	<td><?=$or->date_create?></td>
	<td><?php if($or->nakladna) { echo $or->nakladna;}?></td>
	<td><?=$or->delivery_type->name?></td>
        <td><?php  echo $or->payment_method->name; if($or->payment_method_id == 6 or $or->payment_method_id == 4){ if($or->liqpay_status_id == 3){ echo '<span style="color: #00da00;font-weight: bold;"> (оплачен)</span>';}else{ echo '<span style="color:red;    font-weight: bold;"> (не оплачен)</span>';} } ?></td>
	<td><?=$or->sklad?></td>
	</tr>    
    <?php } ?>
        </tbody>
        </table>

	
    </div>
</div>
<?php }?>
<script>
    $(document).ready(function(){
            $('form').submit(function() {
     var theForm = $(this);
     console.log(theForm.serializeArray());
      $.ajax({
         type: 'POST',
         url: '/admin/justin/',
        // dataType: 'json',
         data: theForm.serialize(),
         beforeSend: function(){
             $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
         },
         success: function(data) {
             $('.card-footer').html(data);
             $('#foo').detach();
         },
         error: function(e){
             $('#foo').detach();
             
             console.log(e);
              $('.card-footer').html(e);
             
         }
     });
     return false;
 });
    });
    function stickers(r){
         $.ajax({
                url: '/admin/justin/edit/stickers/1/',
                type: 'POST',
                data: {number: r},
                success: function (res) {
                    window.open(res, '_blank');
         
                },
                error: function(e){
                    console.log(e);
                   // $('<div/>', {  class: 'alert alert-danger alert-dismissible fade show m-2', html: '<strong>Ошибка!</strong> '+e+'.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' }).appendTo('div.card-header');
                }
            });
     }
     function info(r){
         $.ajax({
                url: '/admin/justin/edit/info/1/',
                type: 'POST',
                data: {number: r},
                success: function (res) {
                    console.log(res);
                   fopen('Информаци про посылку №'+r, res, '<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Закрыть</button>');
                },
                error: function(e){
                    console.log(e);
                   // $('<div/>', {  class: 'alert alert-danger alert-dismissible fade show m-2', html: '<strong>Ошибка!</strong> '+e+'.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' }).appendTo('div.card-header');
                }
            });
     }
    function deleteDoc(r){
   // alert(e);
    $.ajax({
                url: '/admin/justin/edit/cancel/1/',
                type: 'POST',
               // dataType: 'json',
                data: {number: r},//etod=delete_document&ref='+e+'&order='+r,
                success: function (res) {
                    console.log(res);
                    alert(res);
                    window.reload;
                },
                error: function(e){
                    console.log(e);
                   // $('<div/>', {  class: 'alert alert-danger alert-dismissible fade show m-2', html: '<strong>Ошибка!</strong> '+e+'.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' }).appendTo('div.card-header');
                }
            });
    return false;
}
    var clik_ok = 0;
    function chekAll() {
		if($('.chekAll').is(":checked")){
                    
		$('.cheker').prop('checked', true);
		}else{
                    
		$('.cheker').prop('checked', false);
		}
	var ck = $('#myTable').find('input[type=checkbox]:checked').length -1;
	if(ck > 0) {
            $('.reestr').show();
	//$("#np_go").show();
	}else{
            $('.reestr').hide();
            ck = 0;
	//$("#np_go").hide();
	}
	$("#ck_l").html(ck);
//console.log(ck);
        return false;
    }
		function chek_l(){
		var ck = $('#myTable').find('input[type=checkbox]:checked').length;
		if(ck > 0) {
                    $('.reestr').show();
	//$("#np_go").show();
	}else{
            $('.reestr').show();
	//$("#np_go").hide();
	}
	$("#ck_l").html(ck);
	return false;
	};
        function createRegistr(){
	  var id = '';
          var mas = [];
            if ($('.order-item:checked').val()) {
                jQuery.each($('.order-item:checked'), function () {
                    mas.push($(this).attr('id'));
                });
				}
                 // id = mas.join(',');              
	if(mas){
	$.ajax({
                url: '/admin/justin/',
                type: 'POST',
              //  dataType: 'json',
                data: {reestradd: true, id: mas},
                success: function (res) {
                    console.log(res);
                    fopen('Реестр', res, '<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Закрыть</button>');
				//if(res.data[0].Ref){
                                //     $('<div/>', {  class: 'alert alert-success alert-dismissible fade show m-2', html: '<strong>Ok!</strong> Реестр создан! Можно распечатать в разделе реестры.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' }).appendTo('div.card-header');
				//window.open("https://my.novaposhta.ua/scanSheet/printScanSheet/refs%5B%5D/"+res.data[0].Ref+"/type/html/apiKey/1e594a002b9860276775916cdc07c9a6");
				//}
                },
                error: function(e){
                    console.log(e);
                    $('<div/>', {  class: 'alert alert-danger alert-dismissible fade show m-2', html: '<strong>Ошибка!</strong> '+e+'.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' }).appendTo('div.card-header');
                }
            });
	
	}else{
         $('<div/>', {  class: 'alert alert-danger alert-dismissible fade show m-2', html: '<strong>Ошибка!</strong> Вы не выбрали накладные для добавления в реестр.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' }).appendTo('div.card-header');
	}
	return false;
	}
        function StatusToVproceseDostavki(){
        var id = '';
          var mas = [];
            if ($('.order-item:checked').val()) {
                jQuery.each($('.order-item:checked'), function () {
                    mas.push($(this).attr('id'));
                });
                id = mas.join(',');
                  window.location = '/admin/allstatus/id/' + id + '/status/13/';
				}else{
                                    alert('Выберите заказы!');
                                }
                  
        }
	
</script>
