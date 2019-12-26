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
	<td><strong>Финиш</strong></td>
        <td><strong>Действия</strong></td>
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
<td><?php if($d->type == 'final'){ echo 'финишная';}elseif($d->type == 'dop'){ echo 'дополнительная'; }else{ echo 'информационная';} ?></td>
<td><?php if($d->action == 'category'){ echo 'Категория';}elseif($d->action == 'brand'){ echo 'Бренд';}else{ echo 'Товар';}?></td>
<td><?=($d->status==1)?'<i class="icon ion-checkmark green tx-30 pd-5 mg-5"  data-tooltip="tooltip" data-original-title="Акция активна"></i>':'<i class="icon ion-close text-danger tx-30 pd-5" data-tooltip="tooltip" data-original-title="Акция не активна"></i>'?></td>
<td><?=$d->start?></td>
<td><?=$d->end?></td>
<td>
    <form action="" name="del-<?=$d->id?>" method="post">
        <input type="text" name="id" value="<?=$d->id?>" hidden="">
        <input type="submit" value="Удалить" name="dell_akciya" class="btn btn-danger btn-sm" data-tooltip="tooltip" data-original-title="Удалить акцию и все содержимое">        
    </form>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
</div>
</div>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
    <form action="" method="post" class="form-horizontal was-validated" role="form">
        
        <div class="form-group inline">
    <label class="sr-only1" for="option_text" data-tooltip="tooltip" data-original-title="Название акции, будет отображаться на странице АКЦИИ">Название</label>
    <input type="text" class="form-control" id="option_text" name="option_text" placeholder="Название акции" required="">
  </div>
  <div class="form-group inline-block">
    <label class="sr-only1" for="value" data-placement="top" title="" data-tooltip="tooltip" data-original-title="Процент скидки (15)">Скидка</label>
    <input type="number" class="form-control" id="value" name="value" placeholder="процент скидки" style="width: 200px;" required="">
  </div>
            <div class="form-group inline-block">
    <label class="sr-only1" for="start" data-placement="top" title="" data-tooltip="tooltip" data-original-title="Дата начала акции">Старт</label>
    <input type="date" class="form-control" name="start" id="start" required="">
  </div>
   <div class="form-group inline-block">
    <label class="sr-only1" for="end" data-placement="top" title="" data-tooltip="tooltip" data-original-title="Дата окончания акции">Финиш</label>
    <input type="date" class="form-control" name="end" id="end" required="">
  </div>
        <div class="form-group inline-block">
    <label class="sr-only1" for="action" data-placement="top" title="" data-tooltip="tooltip" data-original-title="Акция может действовать на товар, категорию или бренд (что-то одно!!!)">Распространяется на:</label>
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
  <label data-placement="top" title="" data-tooltip="tooltip" data-original-title="Никакие другие скидки не действуют(клиентская и доп.скидки)" >
          <input type="radio" name="type"  value="final" checked >
          Финишная
        </label>
</div>
<div class="radio-inline">
  <label data-placement="top" title="" data-tooltip="tooltip" data-original-title="Дополнительно действуют скидка клиента или другие скидки">
          <input type="radio" name="type"  value="dop">
          Дополнительная
        </label>
</div>
            <div class="radio-inline">
  <label data-placement="top" title="" data-tooltip="tooltip" data-original-title="Информационная, отображает информацию, без ссылки на товары">
          <input type="radio" name="type"  value="all">
          Информационная
        </label>
</div>
        </div>
        <div class="form-group ">
        <div class="checkbox-inline">
  <label data-placement="top" title="" data-tooltip="tooltip" data-original-title="Активность на случай приостановления в период действия акции">
          <input type="checkbox" name="status" value="1">
          Активность
        </label>
</div>
     </div>   

        
        <button type="submit" class="btn btn-default"  name="add">Создать</button>
           
    </form>
             </div>
    </div>
</div>
</div>

<script>
    $('.history').click(function (e) {
var id = e.target.attributes.getNamedItem("data-id").value;
//var type = e.target.attributes.getNamedItem("data-type").value;
$.get('/admin/discounts/?method=histoory&id='+id,
function (data) {
   // console.log(data);
fopen('Детали акции',data,'<button class="btn btn-secondary pd-x-20" id="to_excel" onclick="return to_excel();">Выгрузить в Excel</button><button class="btn btn-danger pd-x-20" data-dismiss="modal" aria-hidden="true">Закрыть</button>');
    });	

}); 

function to_excel(){
   // console.log('data');
    
    // var data_to_post = new Object();
      // data_to_post.to_excel = '1';
      // data_to_post.all = $('#all').text();
      // data_to_post.summa_all_no_akciya = $('#summa_all_no_akciya').text();
     //  data_to_post.summa_all = $('#summa_all').text();
     //  data_to_post.summa_all_no_akciya_akciya = $('#summa_all_no_akciya_akciya').text();
     //  data_to_post.fact = $('#fact').text();
     //  data_to_post.summa_fact_no_akciya = $('#summa_fact_no_akciya').text();
    //   data_to_post.summa_fact = $('#summa_fact').text();
      // data_to_post.summa_fact_no_akciya_akciya = $('#summa_fact_no_akciya_akciya').text();
       
       window.location = '/admin/discounts/to_excel/'+$('.id_akciya').val();
       
      /*
       *  $.post('/admin/discounts/', data_to_post, function (data) {
			//createSelectList(data, this, i); 
                        console.log(data);
		}, 'json');
         */
     return false;
 }
</script>