 <div class="panel panel-primary" style="background-color: #ffffff78;">
 <div class="panel-heading"><h3 class="panel-title"><?=$this->getCurMenu()->getTitle()?></h3></div>
<div class="panel-body">
    <div class="row " style="background: white;">
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
    <select name="action" class="form-control" id="action" required="true">
                    <option value="">Укажите на что акция</option>
                    <option value="article" >Товар</option>
                    <option value="category" >Категория</option>
                    <option value="brand" >Бренд</option>
                    <option value="sezon" >Сезон</option>
                    <option value="all" >Общая информационная</option>
                        </select>
  </div>
    <div class="form-group inline-block">
    <label class="sr-only1" for="komu" data-placement="top" title="" data-tooltip="tooltip" data-original-title="Для кого предназначена акция?">Предназначена для:</label>
    <select name="komu" class="form-control" id="komu" required="true">
                    <option value="all" >Для всех</option>
                    <option value="user" >Авторизированые пользователи</option>
                    <option value="email" >Рассылка</option>
                    <option value="promo" >Промокод</option>
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
<i class="glyphicon glyphicon-edit bleak1 tx-25 pd-5" alt="Редактировать" data-placement="left" title="" data-tooltip="tooltip" data-original-title="Редактировать акцию"></i></a></td>
<td><i class="icon ion-clipboard  bleak tx-20 pd-5 result" alt="Результат" data-id="<?=$d->id?>" data-type="<?=$d->type?>" data-placement="top" title="" data-tooltip="tooltip" data-original-title="Смотреть детали"></i>
<i class="icon ion-clock  tx-20 pd-5 history" alt="История" data-id="<?=$d->id?>" data-type="<?=$d->type?>" data-placement="right" title="" data-tooltip="tooltip" data-original-title="История"></i>
</td>
<td><?=$d->option_text?></td>
<td><?=$d->value?> %</td>
<td><?php if($d->type == 'final'){ echo 'финишная';}elseif($d->type == 'dop'){ echo 'дополнительная'; }else{ echo 'информационная';} ?></td>
<td><?php if($d->action == 'category'){ echo 'Категория';}elseif($d->action == 'brand'){ echo 'Бренд';}elseif($d->action == 'sezon'){ echo 'Сезон';}else{ echo 'Товар';}?></td>
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
    
</div>
</div>

<script>
    $('.result').click(function (e) {
        
    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
              
var id = e.target.attributes.getNamedItem("data-id").value;
//var type = e.target.attributes.getNamedItem("data-type").value;
$.get('/admin/discounts/?method=result&id='+id,
function (data) {
     $('#foo').detach();
   // console.log(data);
fopen('Детали акции',data,'<button class="btn btn-secondary pd-x-20" id="to_excel" onclick="return download(rezultat_akcii);">Выгрузить в Excel</button><button class="btn btn-danger pd-x-20" data-dismiss="modal" aria-hidden="true">Закрыть</button>');
    });	
}); 
    $('.history').click(function (e) {
        $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
       
var id = e.target.attributes.getNamedItem("data-id").value;
//var type = e.target.attributes.getNamedItem("data-type").value;
$.get('/admin/discounts/?method=history&id='+id,
function (data) {
     $('#foo').detach();
    console.log(data);
fopen('История',data);
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