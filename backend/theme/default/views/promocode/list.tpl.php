<?php
//l($this->others->key());
//l(get_object_vars($this->others));
//l(get_class_vars($this->others));
//l(get_class_methods($this->others));

?>
<div class="card pd-20">
    <div class="card-body">
               <table id="myTable" class="table table-hover table-bordered" >
            <thead>
	<tr>
            <th>Редактировать</th>
            <th>Колл.Заказов</th>
            <th>Сумм.Заказов</th>
        <th>Промокод</th>
        <th>Скидка %</th>
	<th>Мин. Сумма заказа</th>
	<th>Начало</th>
	<th>Окончание</th>
	</tr>
            </thead>
            <tbody>
                <?php if(isset($this->others)){
                    foreach ($this->others as $o){
                        $ct_sm = $o->getCountOrderPromo();
                        ?>
                <tr>
                    <td><button class="btn bd bg-white tx-gray-600 btn-sm" id="<?=$o->id?>"
                                onclick="edit(<?=$o->id?>);"
                                data-id="<?=$o->id?>"
                                data-cod="<?=$o->cod?>"
                                data-skidka="<?=$o->skidka?>"
                                data-min_sum ="<?=$o->min_sum?>"
                                data-ctime="<?=date('Y-m-d\TH:i', strtotime($o->ctime))?>"
                                data-expirationtime="<?=date('Y-m-d\TH:i', strtotime($o->expirationtime))?>"
                                type="button">
       <i class="icon icon ion-ios-help-outline" data-tooltip="tooltip" data-original-title="Редактировать"></i>
   </button></td>
   <td><?=$ct_sm->ctn?></td>
   <td><?=$ct_sm->suma?></td>
                    <td><?=$o->cod?></td>
                    <td><?=$o->skidka?></td>
                    <td><?=$o->min_sum?></td>
                    <td><?=$o->ctime?></td>
                    <td><?=$o->expirationtime?></td>
                </tr>
                <?php } } ?>
            </tbody>
               </table>
</div>
    <div class="card-footer">
        <form action="/admin/promocode/edit/" method="post" name="promocode-form">
        <div class="row">
            
            <div class="col-sm-12 col-md-4 col-lg-3">
                <div class="form-group">
                    <input type="text"  value="1" hidden name="all">
                    <input type="text" id="form-id"  value="" hidden name="id">
                    <label class="form-control-label">Промокод: <span class="tx-danger">*</span></label>
                    <input type="text" id="form-cod" class="form-control" value="" required name="cod">
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-2">
                <div class="form-group">
                    <label class="form-control-label">Скидка %: <span class="tx-danger">*</span></label>
                    <input type="text" id="form-skidka"  class="form-control" value="" required name="skidka">
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-2">
                <div class="form-group">
                    <label class="form-control-label">Мин. сумма заказа: <span class="tx-danger">*</span></label>
                    <input type="text" id="form-min_sum" class="form-control" value="" required name="min_sum">
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-2">
                <div class="form-group">
                    <label class="form-control-label">Дата начала: <span class="tx-danger">*</span></label>
                    <input type="datetime-local" id="form-ctime" class="form-control" value="" required name="ctime">
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-2">
                <div class="form-group"> 
                    <label class="form-control-label">Дата завершения: <span class="tx-danger">*</span></label>
                    <input type="datetime-local" id="form-expirationtime" class="form-control" value="" required name="expirationtime">
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-2">
                <div class="form-group">
                    <label class="form-control-label"></label>
                    <input type="submit"  class="btn btn-dark" value="Добавить/Изменить"  name="add_promokode">
                </div>
            </div>
           
        </div>
         </form>
    </div>
</div>
    

<script>
function edit(e){
    $('#form-id').val($('#'+e).attr('data-id'));
    $('#form-cod').val($('#'+e).attr('data-cod'));
    $('#form-skidka').val($('#'+e).attr('data-skidka'));
     $('#form-min_sum').val($('#'+e).attr('data-min_sum'));
    // var c =  new Date($('#'+e).attr('data-ctime'));
      $('#form-ctime').val($('#'+e).attr('data-ctime'));
    //  var u =  new Date($('#'+e).attr('data-utime'));
       $('#form-expirationtime').val($('#'+e).attr('data-expirationtime'));
      
    
    console.log($('#'+e).attr('data-ctime'));
    return false;
}
</script>
