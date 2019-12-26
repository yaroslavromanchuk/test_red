<div class="card pd-20">
    <form action="" method="post" name="go_jastin">
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="form-control-label">Заказ: <span class="tx-danger">*</span></label>
                    <input type="text" disabled="true" class="form-control" value="<?=$this->order->id?>" name="number">
                    <input type="text" hidden="true" value="<?= date("Y-m-d")?>" name="date">
                    <input type="text" hidden="true" value="32b69b95-9018-11e8-80c1-525400fb7782" name="sender_city_id">
                    <input type="text" hidden="true" value="RED" name="sender_company">
                    <input type="text" hidden="true" value="Куковицкий Сергей" name="sender_contact">
                    <input type="text" hidden="true" value="+380968171330" name="sender_phone">
                    <input type="text" hidden="true" value="Киев, Нижнеюрковская 31" name="sender_pick_up_address">
                    <input type="text" hidden="true" value="true" name="pick_up_is_required"> 
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="form-control-label">Получатель: <span class="tx-danger">*</span></label>
                    <input type="text"  class="form-control" value="<?=$this->order->middle_name.' '.$this->order->name?>" required name="receiver ">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="form-control-label">Телефон: <span class="tx-danger">*</span></label>
                    <input type="text"  class="form-control" value="<?=$this->order->telephone?>" required name="receiver_phone">
                </div>
            </div>
            </div>
        <div class="row" >
            <div class="col-2">
                <div class="form-group">
                    <label class="form-control-label">Город: <span class="tx-danger">*</span></label>
                    <select class="form-control select2-show-search" name="city" id="city" data-placeholder="Выберите город" required tabindex="-1" >
                        <option label="Выберите город"></option>
                        <?php
                        foreach ($this->list as $c) {?>
                        <option value="<?=$c->uuid?>"><?=$c->name_ru?></option>
                        <?php }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="form-control-label">Отделение: <span class="tx-danger">*</span></label>
                    <select class="form-control select2" name="branch" id="branch" data-placeholder="Выберите Отделение" required tabindex="-1" >
                        <option label="Выберите Отделение"></option>
                        
                    </select>
                </div>
            </div>
            <div class="col-1">
                <div class="form-group">
                    <label class="form-control-label">Вес посылки: <span class="tx-danger">*</span></label>
                    <input type="text" required class="form-control" value="" name="weight">
                </div>
            </div>
            <div class="col-1">
                <div class="form-group">
                    <label class="form-control-label">Объем: <span class="tx-danger">*</span></label>
                    <input type="text" required class="form-control" value="" name="volume">
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <label class="form-control-label">Количество мест: <span class="tx-danger">*</span></label>
                    <select class="form-control" name="count_cargo_places"  required >
                        <option value="1" selected >1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
            <div class="col-1">
                <div class="form-group">
                    <label class="form-control-label">Оценка: <span class="tx-danger">*</span></label>
                    <input type="text" required class="form-control" value="<?=$this->order->getAmount()?>" name="declared_cost">
                </div>
            </div>
            
            <div class="col-12">
                <button type="submit">Отправить</button>
            </div>
        </div>
        </div>
    </form>
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
     console.log(theForm.serialize());
     return false;
 });
        
    });
    </script>
