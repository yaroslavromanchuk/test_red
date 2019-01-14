 <div class="modal fade" id="new_group" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered w-100" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Створення нової групи відправлень</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
         <form action="/admin/ukrpost/new-group/" method="post" name="new_group" id="new_group">
      <div class="modal-body">
         
              <div class="row p-1">
                <label class="col-sm-3 form-control-label">Назва групи: <span class="tx-danger">*</span></label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" required name="name" value="">
                </div>
              </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Відміна</button>
        <button  type="submit" class="btn btn-primary">Створити</button>
      </div>
      </form>
    </div>
  </div>
</div>
<div class="card pd-20 pd-sm-40">
<div class="card-header">
        <div class="btn-group m-auto" role="group" aria-label="Basic example">
            <select class="form-control status select2" name="status" data-placeholder="Виберіть статус">
                <option label="Виберіть статус"></option>
                <option value="CREATED" <?php if($_GET['status'] == 'CREATED'){ echo 'selected';} ?> >Створено</option>
                <option value="REGISTERED" <?php if($_GET['status'] == 'REGISTERED'){ echo 'selected';} ?>>Зареєстровано</option>
                 <option value="DELIVERED" <?php if($_GET['status'] == 'DELIVERED'){ echo 'selected';} ?>>Доставлено</option>
                 <option value="FORWARDING" <?php if($_GET['status'] == 'FORWARDING'){ echo 'selected';} ?>>Переадресація</option>
                 <option value="RETURNING" <?php if($_GET['status'] == 'RETURNING'){ echo 'selected';} ?>>Повернення</option>
                 <option value="RETURNED" <?php if($_GET['status'] == 'RETURNED'){ echo 'selected';} ?>>Повернено</option>
            </select>
            
            <div class="input-group">
                <select name="shipmentGroupUuid" class="form-control select2-show-search shipmentGroupUuid" data-placeholder="Виберіть групу відправлень" >
        <option label="Виберіть групу відправлень"></option>
       <?php if(@$this->up->getListGroups()){ 
           foreach ($this->up->getListGroups() as $v) { ?>
           <option value="<?=$v->uuid?>" <?php if($_GET['group'] == $v->uuid){ echo 'selected'; } ?>><?=$v->name?> (<?=$this->up->getCountShipmentInGroup($v->uuid)->quantity?>)</option>
       <?php  } 
       
           } ?>
  </select>
<span class="input-group-btn">
                  <button class="btn bd bg-white tx-gray-600 shipmentGroupUuid_status" type="button"><i class="fa fa-search"></i></button>
                </span>
</div>
            </div>
            
  <button class="btn bd bg-white tx-gray-600" data-toggle="modal" data-target="#new_group" type="button">
       <i class="icon ion-ios-create-outline" data-tooltip="tooltip" data-original-title="Нова група"> Нова група</i>
   </button>
   <button class="btn bd bg-white tx-gray-600" onclick="window.open('/admin/ukrpost/print/?sticker=group&uuid='+$('.shipmentGroupUuid').val(), '_blank'); return false;" type="button">
       <i class="icon ion-ios-print-outline" data-tooltip="tooltip" data-original-title="Друк стікерів групи"> Стікер 100*100</i>
   </button>
  <button class="btn bd bg-white tx-gray-600" onclick="window.open('/admin/ukrpost/print/?sticker=form103&uuid='+$('.shipmentGroupUuid').val(), '_blank'); return false;" type="button">
       <i class="icon ion-ios-print" data-tooltip="tooltip" data-original-title="Друк форми 103а"> Форма 103а</i>
   </button>
 <!-- <a href="/admin/ukrpost/new-address/" class="btn btn-secondary">Новый адрес</a>
  <a href="/admin/ukrpost/new-client/" class="btn btn-secondary">Новый клиент</a>-->

    <div class="alert alert-warning" style="display: none;" role="alert"></div>
    
	<!--<a  class="button" href="#">Создать/Печать реестр</a>-->
	<!--<a href="#" class="button" onclick="GoNp(); return false;" id="np_go" style="display:none;float: right;" >Сменить статус на "Отправлен Новой Почтой"</a>-->

     </div>
    <?php
    
    if($this->shipments){
        $stat = ['CREATED'=>'Створено', 'REGISTERED'=>'Зареєстровано', 'DELIVERED'=>'Доставлено', 'FORWARDING'=>'Переадресація', 'RETURNING'=>'Повернення', 'RETURNED'=>'Повернено'];
        ?>
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
    <table id="myTable" class="table table-hover table-bordered datatable1" data-page-length='50' >
            <thead>
	<tr>
	<!--<th><label class="ckbox" data-tooltip="tooltip" title="Выделить все заказы"><input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span></label></th>-->
        <th>Дії</th>
	<th>Замовлення</th>
        <th>Статус</th>
	<th>ТТН</th>
	<th>Адресат</th>
	<th>Телефон</th>
	<th>Адреса</th>
	<th>Сума Oголошена<br>/Післяплата</th>
        <th>Плата за пресилання<br>/Платник</th>
        <th>Плата за пер.п/плати<br>/Платник</th>
        <th>Дата доставки</th>
	</tr>
            </thead>
            <tbody>
       <?php foreach ($this->shipments as $v) { ?>
                <tr>
                  <!--  <td><label class="ckbox"><input type="checkbox" class="order-item cheker" onclick="chek_l();" id="<?=$v->uuid?>"  name="<?=$v->uuid?>"><span></span></label></td>-->
                    <td><div class="btn-group btn-group-sm" role="group" aria-label="...">
                        <button class="btn  bd bg-white tx-gray-600 btn-sm" data-tooltip="tooltip" data-original-title="Друк стикера" onclick="printStickerSipment('<?=$v->uuid?>');"><i class="icon ion-ios-print-outline" ></i></button>
                    <button class="btn  bd bg-white tx-gray-600 btn-sm" data-tooltip="tooltip" data-original-title="Видалити відправлення" onclick="deleteStickerSipment('<?=$v->uuid?>');"><i class="icon ion-ios-trash-outline" ></i></button>
                      <button class="btn  bd bg-white tx-gray-600 btn-sm" data-tooltip="tooltip" data-original-title="Відстежити" onclick="up_tracking('<?=$v->barcode?>');"><i class="icon ion-ios-help-circle-outline"></i></button> 
                       </div>
                        
                    </td>
                    <td><?=$v->externalId?></td>
                    <td><?=$stat[$v->lifecycle->status]?></td>
                    <td><?=$v->barcode?></td>
                    <td><?=$v->recipient->name?></td>
                    <td><?=$v->recipientPhone?></td>
                    <td><?=UkrPostAddress::getAddress($v->recipientAddressId)->detailed_info?></td>
                    <td><?=$v->declaredPrice.'/'.$v->postPay?></td>
                    <td><?=$v->deliveryPrice.'/'?><?php if($v->paidByRecipient){echo 'Одержувач';}else{ echo 'Відправник';} ?></td>
                    <td><?=$v->postPayDeliveryPrice.'/'?><?php if($v->postPayPaidByRecipient){echo 'Одержувач';}else{ echo 'Відправник';} ?></td>
                    <td><?=date('d.m.Y', strtotime($v->deliveryDate))?></td>
                </tr> 
      <?php  } ?>
            </tbody>
    </table>
  <?php
 // echo '<pre>';
 // print_r($this->shipments[0]);
//  echo '</pre>';
  
       } ?>
        <?php if($this->all_order and false){ ?>
	

	<table id="myTable" class="table table-hover table-bordered datatable1" >
            <thead>
	<tr>
	<th><label class="ckbox" data-tooltip="tooltip" title="Выделить все заказы"><input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span></label></th>
	<th>Заказ</th>
	<th>Статус</th>
	<th>Оформлен</th>
	<th>Отправлен</th>
	<th>ТТН</th>
	<th>Город</th>
	</tr>
            </thead>
            <tbody>
	<?php
	foreach ($this->all_order as $or){ ?>
	<tr >
        <td><?php if($or->nakladna){ ?>
            <label class="ckbox"><input type="checkbox" class="order-item cheker" onclick="chek_l();" id="<?=$or->meest_id?>"  name="<?=$or->id?>"><span></span></label><?php } ?></td>
	<td><?php if($or->nakladna){ echo $or->id; }else{ ?><a href="/admin/ukrpost/new-shipment/id/<?=$or->id?>/" target="blank"><?=$or->id?></a><?php } ?></td>
	<td><?=$or->getStat()->getName()?></td>
	<td><?=$or->date_create?></td>
	<td><?php if($or->order_go != '0000-00-00 00:00:00') {echo $or->order_go;}?></td>
	<td><?php if($or->nakladna) {echo $or->nakladna;}?></td>
	<td><?=$or->city?></td>
	</tr>
	<?php } ?>
            </tbody>
	</table>
        <?php } ?>
</div>
 <script>

     //получение информации по посылке ukr почта
function up_tracking(x) {
$.get('/admin/novapochta/ukr/'+x+'/metod/ukr/',
		function (data) {
		if(data){fopen('Відслідковування замовлення', data);}
		});
		return false;
}
   $(".shipmentGroupUuid_status").click(function(){
     // console.log($(this).serialize());
     var er = [];
     var z = '';
     if($('.shipmentGroupUuid').val()){
         z+='group='+$('.shipmentGroupUuid').val();
     }else{
         er.push('Виберіть групу відправлень!');
     }
     if($('.status').val()){
         z+='&status='+$('.status').val();
     }
     if(!er.length){
     location.search = z;
 }else{
     $('.alert-warning').html(er[0]);
     $('.alert-warning').show();
     }
     //  location.search = 'group='+$('.shipmentGroupUuid').val()+'&status='+$('.status').val();
    /*   $.ajax( {
          url: "/admin/ukrpost/new-group/",
          method: "POST",
          dataType: "json",
          data: $(this).serialize(),
          success: function( data ) {
              console.log(data);
              if(data.status === 'ok'){
                  
                  var rec = '';
                  rec += '<input type="text" name="recipient_uuid" value="'+data.message+'">';
              }else{
                  console.log(data.message);
              }
            
          },
          error: function (data){
               console.log(data);
          }
        } );
        */
        
        return false;

});

function printStickerSipment(e){
    window.open('/admin/ukrpost/print/?sticker=shipment&uuid='+e, '_blank');
    
   /* $.ajax( {
          url: "/admin/ukrpost/print/?sticker=shipment",
          method: "POST",
          dataType: "json",
          data: { uuid : e },
          success: function( data ) {
              console.log(data); 
          },
          error: function (data){
               console.log(data);
          }
        } );
        */
    return false;
}
function deleteStickerSipment(e){
    //window.open('/admin/ukrpost/print/?sticker=shipment&uuid='+e, '_blank');
    if(confirm('Точно видалити відправлення?')){
    $.ajax( {
        beforeSend: function(){
             $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
         },
          url: "/admin/ukrpost/new-shipment/",
          method: "POST",
          dataType: "json",
          data: { method : 'delete_shipment', uuid : e },
          success: function( data ) {
              console.log(data); 
              location.reload();
          },
          error: function (data){
               console.log(data);
          }
        } );
    }
       
    return false;
}

    </script>