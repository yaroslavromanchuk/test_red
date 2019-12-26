<link rel="stylesheet" href="/js/meest/jquery-ui.css?v=211" type="text/css" media="screen">
<!--<link rel="stylesheet" href="<?=$this->files?>/scripts/jquery-ui.css" type="text/css" media="screen">-->
<div class="card pd-20 pd-sm-40">
     <h4 class="text-center card-title">Реєстрація нового відправлення</h4>


<?php
if($this->order){ ?>
     
    <?php if(!$this->client->uuid){
        ?>
  <div class="modal fade" id="add_client" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered w-100" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Создание нового контрагента</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
         <form action="" method="post" name="new_rec" id="new_rec">
      <div class="modal-body">
         
              <div class="row p-1">
                <label class="col-sm-3 form-control-label">Фамилия: <span class="tx-danger">*</span></label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" required name="last_name" value="<?=$this->order->middle_name?>">
                </div>
              </div>
              <div class="row p-1">
                <label class="col-sm-3 form-control-label">Имя: <span class="tx-danger">*</span></label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                  <input type="text" class="form-control" required  name="first_name"  value="<?=$this->order->name?>">
                </div>
              </div>
              <div class="row p-1">
                <label class="col-sm-3 form-control-label">Отчество: </label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                  <input type="text" class="form-control"  name="middle_name"  value="<?=$this->order->middle_name?>">
                </div>
              </div>
           <div class="row p-1">
                <label class="col-sm-3 form-control-label">Email: </label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                  <input type="text" class="form-control"  name="email"  value="<?=$this->order->email?>">
                </div>
              </div>
          <div class="row p-1">
                <label class="col-sm-3 form-control-label">Телефон: <span class="tx-danger">*</span></label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" hidden  name="external_id" value="<?=$this->order->customer_id?>">
                    <input type="text" class="form-control" name="phone_number" value="<?=$this->order->telephone?>">
                </div>
              </div>
          <div class="row p-1">
                <label class="col-sm-3 form-control-label">Индекс: <span class="tx-danger">*</span></label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" required name="postcode" id="postcode" maxlength="5"   pattern="[0-9]{5}" value="<?=$this->order->index?>">
                </div>
              </div>
          <div class="row p-1">
                <label class="col-sm-3 form-control-label">Область: </label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control"  name="region" id="region"  value="<?=$this->order->obl?>">
                </div>
              </div>
          <div class="row p-1">
                <label class="col-sm-3 form-control-label">Район: </label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control"  name="district" id="district" value="<?=$this->order->rayon?>">
                </div>
              </div>
          <div class="row p-1">
                <label class="col-sm-3 form-control-label">Населенный пункт: </label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" required  name="city" id="city"  value="<?=$this->order->city?>">
                </div>
              </div>
          <div class="row p-1">
                <label class="col-sm-3 form-control-label">Улица: </label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control"  name="street" id="street" value="<?=$this->order->street?>">
                </div>
              </div>
              <div class="row p-1">
                <label class="col-sm-3 form-control-label">Дом: </label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control"  name="house_number"  value="<?=$this->order->house?>">
                </div>
              </div>
          <div class="row p-1">
                <label class="col-sm-3 form-control-label">Квартира: </label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control"  name="apartment_number"  value="<?=$this->order->flat?>">
                </div>
              </div>
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <button  type="submit" name="add_client" value="1"  class="btn btn-primary">Создать</button>
      </div>
      </form>
    </div>
  </div>
</div>
<script>
   $(function() {
$('#postcode').autocomplete({
	source: function( request, response ) {
        $.ajax( {
          url: "/admin/ukrpost/new-address/?query=postcode",
          dataType: "json",
          data: {
            term: request.term
          },
          success: function( data ) {
            response( data.suggestions);
         //   console.log(data.suggestions);
          }
        } );
      },
			minLength: 5,
			maxHeight: 5,
                        width: 200, // Ширина списка
                        zIndex: 9999, // z-index списка
			deferRequestBy: 100,
			search: function( event, ui ) {
                           // console.log(event);
                           // console.log(ui);
			//$('#k_s_g').fadeIn(500);
			},
			select: function (event, ui) {
                            console.log(ui);
                            $('#region').val(ui.item.REGION);
                            $('#district').val(ui.item.DISTRICT);
                            $('#city').val(ui.item.CITY);
			}
				});

});
</script>

   <?php }else{ ?>
        
                 <div class="modal fade" id="add_addres" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered w-100" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Створеня нової адреси клієнту</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
           <form  action="/admin/ukrpost/new-address/" method="post" enctype="multipart/form-data" name="new_ad" id="new_ad">
      <div class="modal-body">
         
       
          <div class="form-layout">
            <div class="row mg-b-25">
              <div class="col-lg-4">
                <div class="form-group">
                    <input class="form-control" type="text" hidden name="customer_id"  value="<?=$this->order->customer_id?>" >
                     <input class="form-control" type="text" hidden name="m_order_id"  value="<?=$this->order->id?>" >
                     <input class="form-control" type="text" hidden name="m_uuid"  value="<?=$this->client->uuid?>" >
                  <label class="form-control-label">Поштовий індекс: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" required name="m_postcode" id="m_postcode" value="" maxlength="5"  pattern="[0-9]{5}" placeholder="Введите почтовый индекс">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Область: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" id="m_region" name="m_region" value="" placeholder="введите область">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Район: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="m_district" id="m_district" value="" placeholder="Введите район">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Місто: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="m_city" id="m_city" value="" placeholder="Введите город">
                </div>
              </div><!-- col-8 -->
              <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Вулиця:  <span class="tx-danger">*</span></label>
                        <input class="form-control" type="text"  name="m_street" id="m_street" value="" placeholder="Введите № дома">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Будинок: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="m_houseNumber" id="m_houseNumber" value="" placeholder="Введите № дома">
                </div>
              </div><!-- col-8 -->
              
            </div><!-- row -->
          </div><!-- form-layout -->
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Відміна</button>
        <button  type="submit" name="save_address" class="btn btn-primary">Створити</button>
      </div>
        </form>
    </div>
  </div>
</div>
    <script>
    $( function() {
$('#m_postcode').autocomplete({
	source: function( request, response ) {
        $.ajax( {
          url: "/admin/ukrpost/new-address/?query=postcode",
          dataType: "json",
          data: {
            term: request.term
          },
          success: function( data ) {
            response( data.suggestions);
            console.log(data.suggestions);
          }
        } );
      },
			minLength: 4,
			maxHeight: 5,
                        width: 200, // Ширина списка
            zIndex: 9999, // z-index списка
			deferRequestBy: 100,
			search: function( event, ui ) {
                            console.log(event);
                            console.log(ui);
			//$('#k_s_g').fadeIn(500);
			},
			select: function (event, ui) {
                            console.log(ui);
                            $('#m_region').val(ui.item.REGION);
                            $('#m_district').val(ui.item.DISTRICT);
                            $('#m_city').val(ui.item.CITY);
			}
				});

});
</script>   
   <?php } ?>

<?php if($this->error){ ?>
<div class="alert alert-danger" role="alert"><?=$this->error?></div>  
<?php  } ?>
    
      <form  action="" method="post"  id="form_add_ttn" data-parsley-validate >

	<div class="card">
             <h6 class="card-body-title">Відправник:</h6>
             <div class="card-body">
                  <?php if($this->sender){ ?>
                <!-- <input type="text" name="sender" hidden value="<?=$this->sender->uuid?>">-->
                 <p>
                        <span><?=$this->sender->name?></span><br>
                        <span><?=$this->sender->lastName.' '.$this->sender->firstName?></span><br>
                         <span><?=UkrPostAddress::getAddress($this->sender->address_id)->detailed_info?></span>
                    </p>
                      <?php }?>
             </div>
            </div>
          <div class="card">
             <h6 class="card-body-title">Одержувач:</h6>
             <div class="card-body recipient">
                 <?php
                 if($this->order->remarks){ ?>
                     <div class="alert alert-warning" role="alert">
                         <?php
                         foreach ($this->order->remarks as $remark) {
						echo  $remark->getRemark()."-".$remark->getName().'; ';
						//	$remar[] = $rem;
                        }
                         ?>
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
               <?php  }
                 ?>
                 
                  <?php if($this->client->uuid){ ?>
                 <p class="font-weight-bold text-dark">Контактні дані:   <button class="btn btn-outline-info btn-sm" type="button" onclick="EditDani();"><i class="icon ion-md-sync"> Змінити</i></button></p> 
                 <div class="row row-xs">
                     <div class="col-lg-3">
                   <div class="form-group">
                  <label class="form-control-label">Прізвище:  <span class="tx-danger">*</span></label>
                  <input type="text" hidden name="recipient_uuid" id="recipient_uuid" value="<?=$this->client->uuid?>" >
                  <input type="text" class="form-control" required id="r_lastName" name="lastName" value="<?=$this->client->lastName?>" >
                </div>
                     </div>
                     <div class="col-lg-3">
                   <div class="form-group">
 <label class="form-control-label"> Ім'я: <span class="tx-danger">*</span></label>
    <input type="text" class="form-control" name="firstName" id="r_firstName" required value="<?=$this->client->firstName?>" >
                </div>
                     </div>
                       <div class="col-lg-3">
                   <div class="form-group">
<label class="form-control-label"> По-батькові: </label>
                  <input type="text" class="form-control" id="r_middleName" name="middleName" value="<?=$this->client->middleName?>" >

                </div>
                </div>
                       <div class="col-lg-3">
                   <div class="form-group">
<label class="form-control-label"> Телефон: <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control" id="r_phoneNumber" required name="phoneNumber" value="<?=$this->client->phoneNumber?>" >
                </div>
                </div><!-- col-lg-3 -->
                     
              </div><!-- row -->
              <p class="font-weight-bold text-dark">Адреса одержувача:</p><button class="btn bd bg-white tx-gray-600 btn-sm" type="button" data-toggle="modal" data-target="#add_addres"><i class="icon ion-md-person-add"> Додати адресу</i></button>
              <?php
              $list_addr = UkrPostAddress::listAddress($this->order->customer_id);
              if($list_addr->count() > 1){ ?>
                  <div class="row row-xs">
                    <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Адреса:  <span class="tx-danger">*</span></label>
                  <select class="form-control select21" data-placeholder="Виберіть адресу" tabindex="-1" required aria-hidden="true"  id="search_address">
                    <option label="Виберіть адресу"></option>
                    <?php foreach ($list_addr as $v) { ?>
                    <option value="<?=$v->ids?>" <?php if($v->ids == $this->client->addressId){ echo 'selected';} ?>><?=$v->detailed_info?></option>          
                          <?php      } ?>
                  </select>
                </div>
              </div><!-- col-4 -->
                      
                  </div>
             <?php } 
                    $addr = $this->up->getAddress($this->client->addressId); 
              ?>
                  <div class="row row-xs">
                     
                      
                 <div class="col-lg-3">
                   <div class="form-group">
<label class="form-control-label"> Індекс: <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control" required name="postcode" value="<?=$addr->postcode?>" >
                </div>
                </div><!-- col-lg-3 -->
                 <div class="col-lg-3">
                   <div class="form-group">
 <label class="form-control-label"> Область: </label>
                  <input type="text" class="form-control" name="region" value="<?=$addr->region?>" >
                </div>
                </div><!-- col-lg-3 -->
                <div class="col-lg-3">
                   <div class="form-group">
                   <label class="form-control-label"> Район: </label>
                  <input type="text" class="form-control" name="district" value="<?=$addr->district?>" >
                </div>
                </div><!-- col-lg-3 -->
                <div class="col-lg-3">
                   <div class="form-group">
                <label class="form-control-label"> Населений пункт: </label>
                  <input type="text" class="form-control" required name="city" value="<?=$addr->city?>" >
                </div>
                </div><!-- col-lg-3 -->
                <div class="col-lg-3">
                   <div class="form-group">
                        <label class="form-control-label"> Вулиця: </label>
                  <input type="text" class="form-control" name="street" value="<?=$addr->street?>" >
                </div>
                </div><!-- col-lg-3 -->
                <div class="col-lg-3">
                   <div class="form-group">
                        <label class="form-control-label"> Номер будинку: </label>
                  <input type="text" class="form-control" name="houseNumber" value="<?=$addr->houseNumber?>" >
                </div>
                </div><!-- col-lg-3 -->
                <div class="col-lg-3">
                   <div class="form-group">
                        <label class="form-control-label"> Номер квартири: </label>
                  <input type="text" class="form-control" name="apartmentNumber" value="<?=$addr->apartmentNumber?>" >
                </div>
                </div><!-- col-lg-3 -->
                <div class="col-lg-3">
                   <div class="form-group">
                        <label class="form-control-label"> Коментар: </label>
                  <input type="text" class="form-control" name="description" value="<?=$addr->description?>" >
                </div>
                </div><!-- col-lg-3 -->
              </div><!-- row -->
              
               
                      <?php } else{ ?>
                    <!-- Modal -->
                <div class="row row-xs">
                   <div class="col-lg-12 m-auto">
                    <div class="alert alert-danger" role="alert">Контрагента немає в системі! <button type="button" data-toggle="modal" data-target="#add_client" class="btn btn-secondary mx-5">Створити контрагента</button></div>
                    </div>
              </div>
                     <?php }?>
             </div>
            </div>
          <div class="card">
             <h6 class="card-body-title">Інформація про відправлення:</h6>
             <div class="card-body ">
                 <div class="row row-xs">
                     <div class="col-lg-3">
                   <div class="form-group">
                        <input type="text" hidden name="externalId" value="<?=$this->order->id?>" >
                  <label class="form-control-label text-primary">Вага, г:  <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control" required name="weight" value="<?=$this->get->weight?$this->get->weight:''?>" >
                </div>
                     </div><!-- col-lg-3 -->
                      <div class="col-lg-3">
                   <div class="form-group">
                  <label class="form-control-label text-primary">Найбільша сторона, см:  <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control" required name="length" value="<?=$this->get->length?$this->get->length:''?>" >
                </div>
                     </div><!-- col-lg-3 -->
                     <div class="col-lg-3">
                   <div class="form-group">
                  <label class="form-control-label text-primary">Оголошена цінність, грн:  <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control" required name="declaredPrice" value="<?=ceil(($this->order->amount+$this->order->deposit))?>" >
                </div>
                     </div><!-- col-lg-3 -->
                     <div class="col-lg-3">
                   <div class="form-group">
                  <label class="form-control-label text-primary">Післяплата, грн:  <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control" name="postPay" value="<?php if($this->order->payment_method_id != 4 and $this->order->payment_method_id != 6 and $this->order->amount > 0){ echo $this->order->amount; }?>" >
                 <p class="m-0 text-info">(<?=$this->order->getDeliveryType()->getName().'/'.$this->order->getPaymentMethod()->getName()?>)</p>
                   </div>
                         
                     </div><!-- col-lg-3 -->
                     <div class="col-lg-12">
                   <div class="form-group">
                  <label class="form-control-label text-primary">Додаткова інформація:  <span class="tx-danger">*</span></label>
                  <textarea class="form-control" name="description" cols="30" rows="3" maxlength="40" placeholder="Додаткова інформація"><?=$this->order->id?></textarea>
                </div>
                     </div><!-- col-lg-3 -->
                  </div>
                  <p class="font-weight-bold text-dark">Додаткові послуги:</p>
                 <div class="row">
                  <div class="col-lg-3">
                      
                <div class="form-group">
                     <label class="form-control-label text-primary d-block">Група: <span class="tx-danger">*</span></label>
                                <?php
  if($this->sgroup){ ?>
      
                     <select name="shipmentGroupUuid" class="form-control" id="shipmentGroupUuid" required data-placeholder="Вкажіть групу">
       <option label="Вкажіть групу"></option>
       <?php foreach ($this->sgroup as $v) {
           if(isset($_COOKIE['up_uuid_group']) and $_COOKIE['up_uuid_group'] == $v->uuid){
              $selected = 'selected'; 
           }else{
               $selected = '';
           }
           echo '<option value="'.$v->uuid.'" '.$selected.' >'.$v->name.' ('.$this->up->getCountShipmentInGroup($v->uuid)->quantity.')</option>';
               
           } ?>
  </select>
 <?php }
  ?>
                </div>
                </div><!-- col-lg-4 -->  
                <div class="col-lg-3">
                <div class="form-group">
                     <label class="form-control-label text-primary d-block">Зараховувати післяплату на Р/Р: </label>
                     <label class="rdiobox d-inline-block mx-2">
                         <input name="transferPostPayToBankAccount" type="radio" <?php if($this->order->payment_method_id != 4 and $this->order->payment_method_id != 6 and $this->order->amount > 0){ echo 'checked="checked"';} ?>   value="true">
                    <span>Так</span>
                 </label>
                <label class="rdiobox d-inline-block mx-2">
                    <input name="transferPostPayToBankAccount" type="radio" <?php if($this->order->payment_method_id == 4 or $this->order->payment_method_id == 6 or $this->order->amount < 1){ echo 'checked="checked"';} ?>   value="false">
                    <span>Ні</span>
                 </label>
               <!-- <label class="ckbox d-inline-block mx-2">
                    <input type="checkbox" name="transferPostPayToBankAccount" checked="" value="true"><span></span>
                </label>-->
                </div>
                </div><!-- col-lg-4 -->
                <div class="col-lg-3">
                <div class="form-group">
                     <label class="form-control-label text-primary d-block">Сплачує плату за відправлення: </label>
                <label class="rdiobox d-inline-block mx-2">
                    <input name="paidByRecipient" type="radio"  value="true">
                    <span>Одержувач</span>
                 </label>
                     <label class="rdiobox d-inline-block mx-2">
                    <input name="paidByRecipient" type="radio" checked="checked"  value="false">
                    <span>Відправник</span>
                 </label>
                </div>
                </div><!-- col-lg-4 -->
                <div class="col-lg-3">
                <div class="form-group">
                     <label class="form-control-label text-primary d-block">Сплачує плату за пересилання післяплати: </label>
                <label class="rdiobox d-inline-block mx-2">
                    <input name="postPayPaidByRecipient" type="radio" checked="checked" value="true">
                    <span>Одержувач</span>
                 </label>
                     <label class="rdiobox d-inline-block mx-2">
                    <input name="postPayPaidByRecipient" type="radio"  value="false">
                    <span>Відправник</span>
                 </label>
                </div>
                </div><!-- col-lg-4 -->
                
                    
                 </div>
             </div>
       </div>

         <div class="form-layout-footer">
             <button class="btn btn-info mg-r-5" name="add_ttn" id="add_ttn" type="submit">Створити</button>
             <button class="btn btn-secondary" type="reset">Очистити</button>
            </div>
              
      </form>
        <?php } ?>
</div>
    <script>

 $("#shipmentGroupUuid").change(function(e){
    // console.log($(this).val());
     document.cookie = 'up_uuid_group='+$(this).val()+'; path=/;';
 });
/*
$("#form_add_ttn").submit(function(e){
     console.log($(this).serialize());
     return false;
 });
 */
 
 function EditDani(){
     if(confirm('Ви збираетесь змінити контактні дані?')){
     $.ajax({
         beforeSend: function(){
             $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
         },
          url: "/admin/ukrpost/edit-client/",
          method: "POST",
          dataType: "json",
          data: { uuid: $("#recipient_uuid").val(), param:{ lastName:$("#r_lastName").val(), firstName:$("#r_firstName").val(), middleName:$("#r_middleName").val(), phoneNumber:$("#r_phoneNumber").val()}},
          success: function( data ) {
              console.log(data);
             if(data.status == 'ok'){
                  location.reload();
              }else{
                   $('.modal-backdrop').hide();
                  $('#foo').detach();
                  fopen('Помилка зміни контактних даних', '<div class="alert alert-danger" role="alert">'+data.message+'</div>');
              }
              
          },
          error: function (data){
               console.log(data);
          }
        } );
    }
     return false;
 }
 
 $("#search_address").change(function(){
     console.log($(this).val());
     if(confirm('Ви збираетесь змінити адресу?')){
     $.ajax({
         beforeSend: function(){
             $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
         },
          url: "/admin/ukrpost/new-client/",
          method: "POST",
          dataType: "json",
          data: { method : 'put_address', ids : $(this).val(), uuid : $('#recipient_uuid').val(), order : $('#m_order_id').val()},
          success: function( data ) {
              console.log(data);
              if(data.status == 'ok'){
                  location.reload();
              }else{
                   $('.modal-backdrop').hide();
                  $('#foo').detach();
                  fopen('Помилка зміни адреси', '<div class="alert alert-danger" role="alert">'+data.message+'</div>');
              }
          },
          error: function (data){
               console.log(data);
          }
        } );
    }
        return false;
     
     
 });
   $("#new_rec").submit(function(e){
      //console.log($(this).serialize());
      // console.log('dfhgdf');
      $('#add_client').hide();
       $.ajax( {
            beforeSend: function(){
             $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
         },
          url: "/admin/ukrpost/new-client/",
          method: "POST",
          dataType: "json",
          data: $(this).serialize(),
          success: function( data ) {
              console.log(data);
              if(data.status === 'ok'){
                  location.reload();
              }else{
                  $('.modal-backdrop').hide();
                  $('#foo').detach();
                  fopen('Помилка створення контагента', '<div class="alert alert-danger" role="alert">'+data.message+'</div>');
                  console.log(data.message);
              }
            
          },
          error: function (data){
               console.log(data);
               
               $('.modal-backdrop').hide();
              $('#foo').detach();
              alert('Помилка(((');
          }
        } );
        return false;

});
    </script>