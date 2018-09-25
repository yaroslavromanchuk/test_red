 <div class="panel panel-primary" style="background-color: #ffffff78;">
 <div class="panel-heading"><h3 class="panel-title"><?=$this->getCurMenu()->getTitle()?></h3></div>
<div class="panel-body">
<div class="row">
<div class="col col-sm-12 col-md-12 col-lg-12">
<?php if($this->discounts){
?>
   <table cellpadding="4" cellspacing="0" id="order-articles" class="table " >
    <thead class="thead-light">
<tr>
    <td colspan="2"><strong>Действие</strong></td>
    <td><strong>Название</strong></td>
    <td><strong>Процент скидки</strong></td>
    <td><strong>Тип</strong></td>
    <td><strong>Действует</strong></td>
	<td><strong>Активность</strong></td>
	<td><strong>Старт</strong></td>
	<td><strong>Конец</strong></td>
</tr>
 </thead>
 <tbody>
<?php
foreach($this->discounts as $d){ ?>
<tr>
<td><a href="/admin/discounts/edit/<?=$d->id?>/">
				<i class="icon ion-clipboard bleak1 tx-30 pd-5" alt="Редактировать" data-placement="left" title="" data-tooltip="tooltip" data-original-title="Редактировать акцию"></i></a></td>
<td><i class="icon ion-clock bleak tx-30 pd-5 history" alt="История" data-id="<?=$d->id?>" data-type="<?=$d->type?>" data-placement="top" title="" data-tooltip="tooltip" data-original-title="Смотреть детали"></i></td>
<td><?=$d->option_text?></td>
<td><?=$d->value?> %</td>
<td><?=$d->type=='all'?'финишная':'дополнительная'?></td>
<td><?php if($d->action == 'category'){ echo 'Категория';}elseif($d->action == 'brand'){ echo 'Бренд';}else{ echo 'Товар';}?></td>
<td><?=($d->status==1)?'<i class="icon ion-checkmark green tx-30 pd-5 mg-5"  data-tooltip="tooltip" data-original-title="Акция активна"></i>':'<i class="icon ion-close text-danger tx-30 pd-5" data-tooltip="tooltip" data-original-title="Акция не активна"></i>'?></td>
<td><?=$d->start?></td>
<td><?=$d->end?></td>
</tr>

<?php
} ?>
</tbody>
</table>
<?php
} ?>
</div>
</div>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
    <form action="" method="post" class="form-horizontal was-validated" role="form">
        
        <div class="form-group inline">
    <label class="sr-only1" for="option_text">Название</label>
    <input type="text" class="form-control" id="option_text" name="option_text" placeholder="Название акции" required="">
  </div>
  <div class="form-group inline-block">
    <label class="sr-only1" for="value">Скидка</label>
    <input type="number" class="form-control" id="value" name="value" placeholder="процент скидки" style="width: 200px;" required="">
  </div>
            <div class="form-group inline-block">
    <label class="sr-only1" for="start">Старт</label>
    <input type="date" class="form-control" name="start" id="start" required="">
  </div>
   <div class="form-group inline-block">
    <label class="sr-only1" for="end">Старт</label>
    <input type="date" class="form-control" name="end" id="end" required="">
  </div>
        <div class="form-group inline-block">
    <label class="sr-only1" for="action">Старт</label>
    <select name="action" class="form-control" id="action" required="">
                    <option value="">Укажите на что акция</option>
                    <option value="article" >Товар</option>
                    <option value="category" >Категория</option>
                    <option value="brand" >Бренд</option>
                    <option value="all" >Общая информационная</option>
                        </select>
  </div>
        <div class="form-group ">     
  <div class="form-group radio-inline">
  <label>
          <input type="radio" name="type"  value="all" checked >
          Финишная
        </label>
</div>
<div class="radio-inline">
  <label>
          <input type="radio" name="type"  value="dop">
          Дополнительная
        </label>
</div>
        </div>
        <div class="form-group ">
        <div class="checkbox-inline">
  <label>
          <input type="checkbox" name="status" value="1">
          Активность
        </label>
</div>
     </div>   

        
  <button type="submit" class="btn btn-default" name="add">Создать</button>
           
    </form>
             </div>
    </div>
</div>
</div>

<script>
    $('.history').click(function (e) {
//console.log(e);

var id = e.target.attributes.getNamedItem("data-id").value;
var type = e.target.attributes.getNamedItem("data-type").value;
$.get('/admin/discounts/?method=histoory&id='+id,function (data) {
    console.log(data);
                 fopen('Детали акции', data);
    
    });	

}); 
    </script>