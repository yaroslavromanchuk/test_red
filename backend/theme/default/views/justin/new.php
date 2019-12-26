<div class="card pd-20">
    <div class="card-header">
        <div class="card-title">Создание посылки №<?=$this->order->id?></div>
    </div>
    <div class="card-body">
        <form action="" method="post" name="go_jastin">
        <div class="row m-auto">
        <div class="col-sm-12 col-lg-6">
        <div class="row m-auto">

                    <input type="text" hidden="true"  value="<?=$this->order->id?>" name="number">
                    <input type="text" hidden="true" value="<?= date("Y-m-d")?>" name="date">
                    <input type="text" hidden="true" value="32b69b95-9018-11e8-80c1-525400fb7782" name="sender_city_id">
                    <input type="text" hidden="true" value="RED" name="sender_company">
                    <input type="text" hidden="true" value="Куковицкий Сергей" name="sender_contact">
                    <input type="text" hidden="true" value="+380674069080" name="sender_phone">
                    <input type="text" hidden="true" value=" " name="sender_pick_up_address">
                    <input type="text" hidden="true" value="true" name="pick_up_is_required"> 
                    
                 
            <div class="col-12">
                <div class="form-group">
                    <label class="form-control-label">Получатель: <span class="tx-danger">*</span></label>
                    <input type="text"  class="form-control" value="<?=$this->order->middle_name.' '.$this->order->name?>" required name="receiver">
                    <input type="text" hidden="true"   value="<?=$this->order->middle_name.' '.$this->order->name?>" required name="receiver_contact">
                    
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-control-label">Телефон: <span class="tx-danger">*</span></label>
                    <input type="text"  class="form-control" value="<?=$this->order->telephone?>" required name="receiver_phone">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-control-label">Отделение: <span class="tx-danger">*</span></label>
                    <select class="form-control select2-show-search" name="branch" id="branch" data-placeholder="Выберите Отделение" required tabindex="-1" >
                        <option label="Выберите Отделение"></option>
                        <?php
                        foreach ($this->list as $c) { ?>
                        <option value="<?=$c->branch?>" <?php if($c->branch == JustinDepartmentToOrder::getUuid($this->order->id)){echo 'selected';}?> ><?=$c->depart_descr.' обл. '.$c->address?></option>
                        <?php }
                        ?>
                    </select>
                </div>
            </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
        <div class="row m-auto" >
           <!-- <div class="col-2">
                <div class="form-group">
                    <label class="form-control-label">Город: <span class="tx-danger">*</span></label>
                    <select class="form-control select2-show-search" name="city" id="city" data-placeholder="Выберите город" required tabindex="-1" >
                        <option label="Выберите город"></option>
                        <?php
                        foreach ($this->list as $c) { ?>
                        <option value="<?=$c->uuid?>"><?=$c->name_ru?></option>
                        <?php }
                        ?>
                    </select>
                </div>
            </div>-->
           
            <div class="col-6">
                <div class="form-group">
                    <label class="form-control-label">Вес посылки: <span class="tx-danger">*</span></label>
                    <input type="text" required class="form-control" value="" name="weight">
                    <input type="text" hidden="true" value="0" name="volume">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="form-control-label">Количество мест: <span class="tx-danger">*</span></label>
                    <select class="form-control" name="count_cargo_places"  required >
                        <option value="1" selected >1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="form-control-label">Оценка: <span class="tx-danger">*</span></label>
                    <input type="text" required class="form-control" value="<?=$this->order->getAmount()?>" name="declared_cost">
                    <input type="text" hidden="true" value="0" name="delivery_amount">
                    <input type="text" hidden="true" value="0" name="redelivery_amount"> 
                </div>
            </div>
           <div class="col-6">
                <div class="form-group">
                    <label class="form-control-label">Оплата: <span class="tx-danger">*</span></label>
                    <input type="text" required class="form-control" value="<?php if($this->order->liqpay_status_id == 3) { echo 0; }else{ echo $this->order->getAmount();}?>" name="order_amount">
                
                    <input type="text" hidden="true" value="<?php if($this->order->liqpay_status_id == 3){ echo 'false';}else{ echo 'true';} ?>" name="redelivery_payment_is_required">
                    <input type="text" hidden="true" value="<?php if($this->order->liqpay_status_id == 3){ echo 0;}else{ echo 1;}?>" name="redelivery_payment_payer">
                    
                    <input type="text" hidden="true" value="false" name="delivery_payment_is_required">
                    <input type="text" hidden="true" value="0" name="delivery_payment_payer">
                     <input type="text" hidden="true" value="<?php if($this->order->liqpay_status_id == 3) { echo 'false';}else{ echo 'true';}?>" name="order_payment_is_required">
                </div>
            </div>
             <div class="col-12">
                <div class="form-group">
                    <label class="form-control-label">Коментарий: </label>
                    <input type="text"  class="form-control" value="" name="add_description">
                </div>
            </div>
            
            
        </div>
            </div>
        <div class="col-sm-12  text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Создать</button>
            </div>
        </div>
        </form>
        </div>
    <div class="card-footer">
        
    </div>
    
   <?php

 ///l($this->list);
?>

</div>
<script>
    $(document).ready(function(){
        var select = $("#city");
        var dep =  $("#branch");
        select.on("select2:select", function(e){ refresh(e);});
        select.on("select2:unselect", function(){  dep.empty(); });
        
        function refresh(evt){
        if(!evt){
            var args = {};
        }else{
            //console.log(evt.params.data);
            var args = evt.params.data;
        }
        $.ajax({
         type: 'POST',
         url: '/admin/justin/',
         dataType: 'json',
         data: {id:args.id, metod: 'search_depart'},
         success: function(data) {
             dep.empty().select2({data : data});
             //console.log('Yay! '+data);
         }
     });
        }

        $('form').submit(function() {
     var theForm = $(this);
     console.log(theForm.serializeArray());
      $.ajax({
         type: 'POST',
         url: '/admin/justin/new/add/1/',
        // dataType: 'json',
         data: theForm.serialize(),
         beforeSend: function(){
             $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
         },
         success: function(data) {
             $('.card-footer').html(data);
             window.location = data;
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
    </script>
