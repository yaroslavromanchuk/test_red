
<?php
$order_owner = new Customer($this->getOrder()->getCustomerId());
if ($order_owner->getAdminComents()) { $comment  = '<div class="alert alert-danger" role="alert">'.$order_owner->getAdminComents().'</div>';}else{
    $comment  = '';
} ?>
<div class="card pd-20 mb-3">
  <h5 class="card-body-title">Заявка <?=$this->getOrder()->getQuickNumber().'/<span id="get_order_id">'.$this->getOrder()->getId().'</span>'; if(trim($this->getOrder()->getComments())){ echo '   <span class="text-danger">'.htmlspecialchars($this->getOrder()->getComments()).'</span>';}?></h5>
  <h6 class="card-subtitle mb-2 text-muted"><?=$comment?></h6>
  <div class="card-body">
      <form method="POST" action="" id="user_info" name="user_info" class="contact-form was-validated">
          <div class="form-layout">
              <div class="row mg-b-25">
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Имя: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" id="name" required="" value="<?=($this->getOrder()->getName()?$this->getOrder()->getName():"")?>" name="name" placeholder="Имя">
                </div>
              </div><!-- col-2 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Фамилия: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" id="middle_name" required="" value="<?=($this->getOrder()->getMiddleName() ? $this->getOrder()->getMiddleName() : "")?>" name="middle_name"  placeholder="Фамилия">
                </div>
              </div><!-- col-2 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Телефон: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" required="" value="<?php echo $this->getOrder()->getTelephone() ? $this->getOrder()->getTelephone() : ""; ?>" name="telephone"  placeholder="Телефон">
                </div>
              </div><!-- col-2 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Email: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" required="" value="<?php echo $this->getOrder()->getEmail() ? $this->getOrder()->getEmail() : ""; ?>" name="email"  placeholder="Email">
                </div>
              </div><!-- col-2 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Адрес: </label>
                  <input class="form-control" type="text" value="<?php echo $this->getOrder()->getAddress() ? $this->getOrder()->getAddress() : ""; ?>" name="address"  placeholder="Адрес">
                </div>
              </div><!-- col-2 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Способ доставки: <span class="tx-danger">*</span></label>
                  <select name="delivery_type_id" id="delivery_type" required="" class="form-control" data-placeholder="&darr; Выберите из списка">
                      <option label="&darr; Выберите из списка"></option>
			<?php foreach (wsActiveRecord::useStatic('DeliveryType')->findAll(array('active'=>1)) as $method) { ?>
				<option value="<?=$method->getId()?>" <?php if ($method->getId() == $this->getOrder()->getDeliveryTypeId()) echo 'selected="selected"'?>><?=$method->getName()?></option>
			<?php } ?>
		</select>
                </div>
              </div><!-- col-2 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Способ оплаты: <span class="tx-danger">*</span></label>
                  <select name="payment_method_id" id="payment_method" class="form-control" required="" data-placeholder="&darr; Выберите из списка" >
                    <option label="&darr; Выберите из списка" ></option>
			<?php foreach (wsActiveRecord::useStatic('PaymentMethod')->findAll(array('active'=>1)) as $method) { ?>
<option value="<?=$method->getId()?>" <?php if ($method->getId() == $this->getOrder()->getPaymentMethodId()) echo 'selected="selected"'?>><?=$method->getName()?></option>
			<?php } ?>
		</select>
                  </div>
              </div><!-- col-2 -->
              <div class="col-lg-2 dop_fields ukr novp up_r ">
                <div class="form-group">
                  <label class="form-control-label">Город: <span class="tx-danger">*</span></label>
                  <input class="form-control in req_ukr req_np" name="city" id="city"  placeholder="Город" type="text"  value="<?php echo $this->getOrder()->getCity() ? trim($this->getOrder()->getCity()) : ""; ?>"  >
                </div>
              </div><!-- col-2 -->
              <div class="col-lg-2 dop_fields ukr up_r">
                <div class="form-group">
                  <label class="form-control-label">Индекс: <span class="tx-danger">*</span></label>
                  <input class="form-control in req_ukr" name="index" id="index"  placeholder="Индекс" type="text"  value="<?php echo $this->getOrder()->getIndex() ? $this->getOrder()->getIndex() : ""; ?>"  >
                </div>
              </div><!-- col-2 -->
               <div class="col-lg-2 dop_fields ukr kur">
                <div class="form-group">
                  <label class="form-control-label">Улица: <span class="tx-danger">*</span></label>
                  <input class="form-control in req_ukr req_kur" name="street" id="street"  placeholder="Улица" type="text"  value="<?php echo $this->getOrder()->getStreet() ? $this->getOrder()->getStreet() : ""; ?>"  >
                </div>
              </div><!-- col-2 -->
              <div class="col-lg-2 dop_fields ukr kur ">
                <div class="form-group">
                  <label class="form-control-label">Дом: <span class="tx-danger">*</span></label>
                  <input class="form-control in req_ukr req_kur" name="house" id="house"  placeholder="Дом" type="text"  value="<?php echo $this->getOrder()->getHouse() ? $this->getOrder()->getHouse() : ""; ?>"  >
                </div>
              </div><!-- col-2 -->
              <div class="col-lg-2 dop_fields all">
                <div class="form-group">
                  <label class="form-control-label">Квартира: </label>
                  <input class="form-control in" name="flat" id="flat"  placeholder="Квартира" type="text"  value="<?php echo $this->getOrder()->getFlat() ? $this->getOrder()->getFlat() : ""; ?>"  >
                </div>
              </div><!-- col-2 -->
              <div class="col-lg-2 dop_fields novp">
                <div class="form-group">
                  <label class="form-control-label">Отделение: </label>
                  <input class="form-control in req_np" name="sklad" id="sklad"  placeholder="Отделение" type="text"  value="<?php echo $this->getOrder()->getSklad() ? $this->getOrder()->getSklad() : ""; ?>"  >
                </div>
              </div><!-- col-2 -->
              <div class="col-lg-12">
                <div class="form-group text-center">
                  <p id="save_done" style="display:none; color:red; font-size: 20px;margin: 0;padding-top: 8px;height: 45px;text-align: center;">Сохранено!</p>
		<div id="errormessage" style="display:none;"></div>
                <input type="submit" class="btn  btn-lg btn-primary" name="savepage" id="savepage" value="Сохранить информацию о покупателе">
                </div>
              </div><!-- col-2 -->
              </div>
          </div>
<script>
	$(document).ready(function () {
	$("#user_info").submit(function () {
		$.ajax({
			beforeSend: function( data ) {
				$('#savepage').attr('value', 'Сохраняется...');
			},
			type: "POST",
			url: '/admin/orderinfo/id/'+<?=$this->getOrder()->getId()?>,
			data: $("#user_info").serialize()+'&ajax=1',
			success: function( data ) {
				console.log('success');
				$( "#errormessage" ).slideUp();
				$('#save_done').slideDown().delay(1600).slideUp();
			},
			dataType: 'json',
			complete: function( data ) {
				$('#savepage').attr('value', 'Сохранить информацию о покупателе');
			},
			error: function( e ) {
				$( "#errormessage" ).empty().append( e.responseText ).slideDown();
			}
		});
		return false;
	} );
	
	function hide_dop_fields(item){
		$('.dop_fields').hide();
              // $('.in').removeAttr('required');
                switch(item){
                    case '0':  $('.dop_fields').hide(); /*$('.in').removeAttr('required');*/ break; 
                    case '4':  $('.ukr').show(); /*$('.req_ukr').attr("required", "required");*/ break;
                    case '8':  $('.novp').show(); /* $('.req_np').attr("required", "required");*/ break;
                    case '16': $('.novp').show(); /*$('.req_np').attr("required", "required");*/ break;
                    case '9':  $('.kur').show(); $('.all').show(); /* $('.req_kur').attr("required", "required");*/  break;
                    
                    default: $('.dop_fields').hide(); /*$('.in').removeAttr('required');*/ break;
                }

	}
        
	hide_dop_fields($('#delivery_type').val());
	
		$('#delivery_type').change(function () {

			var delivery = $(this).val();
			hide_dop_fields(delivery);


			var payment = $('#payment_method').val();
		
			if (delivery == '0') {
				window.location.reload(true);
				return(false);
			}
                        
			$('#payment_method').attr('disabled', true);
			$('#payment_method').html('<option>загрузка...</option>');
			var url = '/page/getpayment/';
			$.get(
				url,
				"delivery=" + delivery,
				function (result) {
					if (result.type == 'error') {
						alert('error');
						return(false);
					}
					else {
						var options = '';
						var option = '';
						$(result.payment).each(function () {
							option = '';
							if (payment == $(this).attr('id')) option = 'selected="selected"';
							options += '<option value="' + $(this).attr('id') + '" ' + option + '>' + $(this).attr('title') + '</option>';
						});
						$('#payment_method').html(options);
						$('#payment_method').attr('disabled', false);
						var url = '/page/savedelyveryordr/';
						$.get(
							url,
							"id=" + $('#get_order_id').html() + '&delyvery=' + $('#delivery_type').val() + '&payment=' + $('#payment_method').val(), function () {
							});
					}
				},
				"json"
			);


		});
		$('#payment_method').change(function () {
			var url = '/page/savedelyveryordr/';
			$.get(
				url,
				"id=" + $('#get_order_id').html() + '&delyvery=' + $('#delivery_type').val() + '&payment=' + $('#payment_method').val(), function () {});
		});
	});

</script>
</form>
  </div>
</div>

<div class="card pd-20 mb-3">
  <h6 class="card-body-title">Товары</h6>
  <div class="card-body">
  <form method="post" action="" class="form-horizontal1">

<?php if($this->getOrder()->getDeposit() > 0) { echo '<p style="padding: 5px;border-radius: 2px;background: #37d011;width: 200px;color: #040404;font-size: 16px;margin: 5px auto;"><b>Присутствует депозит!</b></p>';} ?>


   <table cellpadding="4" cellspacing="0" id="order-articles" class="table " >
    <thead class="thead-light">
<tr>
    <td colspan="2"><strong>Действие</strong></td>
    <td><strong>Кол./Товар</strong></td>
    <td><strong>Размер/Цвет</strong></td>
    <td><strong>Цена</strong></td>
	<td><strong>Детали</strong></td>
</tr>
 </thead>
 <tbody>
    <?php 
      $SumOrder = $this->getOrder()->calculateOrderPrice(true, false);
	
	$t_price = 0.00;
        $t_option = 0.00;
	$t_real_price =0.00;
        $sum_skudka = 0.00;
	if ($this->getOrder()->getArticles()->count()) { ?>
        <?php 
        foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {?>
			<tr id="<?=$article_rec->getId()?>" <?php if($article_rec->article_db->getCategoryId() == 16){ echo 'style="background: rgba(210, 33, 33, 0.43);"';} ?>>
                            <td colspan="2" class="text-center">
                                <div class="d-inline-block">
					<img class="prev w200 " style="vertical-align: sub;" rel="#miesart<?=$article_rec->getId(); ?>"
						 src="<?=$article_rec->getImagePath('listing')?>"
						 alt="<?=htmlspecialchars($article_rec->getTitle())?>"
						 style="box-shadow: 0px 0px 20px #BBB;cursor: pointer;"/>

					<div class="simple_overlay" id="miesart<?=$article_rec->getId(); ?>" style="position: absolute;  margin-left: 20%; margin-top: -15%;z-index: 10;">
						<img src="<?=$article_rec->getImagePath('card_product');?>" alt="<?=htmlspecialchars($article_rec->getTitle());?>" />
					</div>
                                        </div>
                                        
	<?php if($article_rec->getCount() > 0){ ?>
                                        <div class="d-inline-block">
                <a href="/admin/shop-articles/edit/id/<?=$article_rec->getArticleId()?>" title="Редактировать" data-placement="bottom" class="d-block"  data-tooltip="tooltip" >
                    <img src="/img/icons/edit-small.png" alt="Редактировать" class="img_return"/>
		</a><br>
                <a href="<?=$this->path;?>shop-orders/adelete/id/<?=$article_rec->getId();?>/#flag=<?=$article_rec->getId();?>" class="d-block" onclick="return confirm('Удалить?');"  data-placement="bottom"  data-tooltip="tooltip" data-original-title="Удалить на сайт">
                    <img src="/img/icons/cantremove-small.png" alt="Удалить" class="img_return" />
		</a><br>
                <a href="<?=$this->path;?>shop-orders/adeletenoshop/id/<?=$article_rec->getId();?>/" class="d-block" onclick="return dell(this);" data-original-title="Удалить без возврата на сайт" data-placement="bottom"  data-tooltip="tooltip" >
                    <img src="/img/icons/remove-small.png" alt="Удалить" class="img_return"/>
		</a>
                </div>
	<?php } ?>
				</td>
<script>
//return confirm('Удалить товар без возврата на склад? (товар не вернется на склад)');
function dell(th){
var value = prompt("Введите причину удаления товара: ", '');
if(value === null) return false;
if(value === '') return false;
if(value.length > 1){
		th.href +='mes/'+value+'/';
			return true;
}
return false;
}
function ret(d){
var id = d.name;
if(id){
console.log(id);

$.ajax({
			type: "POST",
			url: '/admin/shop-orders/return_article/',
			dataType: 'json',
			data: '&id='+ id +'&js=go',
			success: function( data ) {
			console.log(data);
			if(data == 'ok'){
			$('#'+id).hide();
			}
			console.log(data);
			},
			error: function(e) {
			console.log(e);
			alert('Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
			}
		});
		
		}
return false;

}
</script>
				<td>
					<?='Колл.: <b>' . $article_rec->getCount().'</b>'?>
					<br><span style="color: #777;"><?=$article_rec->article_db->category->getRoutez()?></span>
                                        <br><a href="<?=$article_rec->article_db->getPath()?>" target="_blank"><?=$article_rec->getTitle()?></a>
					<br>Наличие:
					<?php $art = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article_rec->getArticleId(), 'id_size' => $article_rec->getSize(), 'id_color' => $article_rec->getColor()));
						$cnt = $art->getCount();
					if ((int)$cnt > 0) { ?>
					<select name="count-<?=$article_rec->getId()?>"  class="count form-control input w150">
					<?php
		for ($i = 1; $i <= $cnt; $i++) echo ($i != $article_rec->getCount()) ? "<option value=\"{$i}\">{$i}</option>" : "<option value=\"{$i}\" selected=\"selected\">{$i}</option>";?>
					</select><br>
					<label><input type="checkbox" name="edit_count-<?= $article_rec->getId(); ?>" class="chek_edit"> Изменить</label>
				<?php  } else {echo 'Нет на складе';	}?>
				</td>
				<td>
					<input type="hidden" class="hidden" value="<?=$article_rec->geArticletId() ?>">
					<?=$article_rec->sizes->getSize().' / '.$article_rec->colors->getName()?>
                                        <br><span class="text-primary"><?=$article_rec->artikul?></span>
					<input type="hidden" class="hidden"  name="size-<?=$article_rec->getId(); ?>"  value="<?=$article_rec->getSize()?>">
					<input type="hidden" class="hidden"  name="color-<?=$article_rec->getId(); ?>"  value="<?=$article_rec->getColor()?>">
				</td>
				<?php
					$price_real = (int)$article_rec->getOldPrice() ? $article_rec->getOldPrice() : $article_rec->getPrice();
						$t_real_price += $price_real * $article_rec->getCount();
						
						$price_show = $article_rec->getPerc($this->order->getAllAmount());
							$sum_skudka += $price_show['minus'];
						
					if($article_rec->getCount() > 0){
					$skid_show = round((1 - (($price_show['price']/$article_rec->getCount())/ $price_real)) * 100);
					}
					$st = (int)$article_rec->getOldPrice() ? 'style="color:red;font-size:12px"' : 'style="font-size:12px"';
					$s_uc = (int)$article_rec->getOldPrice() ? 'Уценка' : 'Скидка';
				?>
				<td>
					<?php if ($article_rec->getCount() >0 and $price_real != ($price_show['price']/$article_rec->getCount())){ ?>
						<span style="text-decoration:line-through"><?=$price_real?></span><br>
					<?php } ?>
	<?php if ($article_rec->getCount() > 0) { echo $skid_show ? '<span '.$st.'>'.$s_uc.' '.$skid_show.'%</span><br><br>' : ''; } ?>
					<b><?=Number::formatFloat($price_show['price'])?> грн</b>
					
					<?php /*@$price_show['comment']?'<br><span style="font-size:10px;color:red;">'.$price_show['comment'].'</span>':''*/ ?>
					
					<?=$article_rec->getOptionId()?'<br><span style="font-size:10px;color:red;">'.$article_rec->getOption()->option_text:''?>
					
					<?php 
					if(!$article_rec->getOptionId() and $article_rec->getOptions()){
					?>
					<br/><select name="option_id-<?=$article_rec->getId()?>" onChange="return addAkciya(this);"  class="form-control">
					<option value="0">Есть активные скидки</option>
					<?php
					foreach($article_rec->getOptions() as $o){
					
					echo "<option value='$o->id'>$o->option_text</option>";
					
					}
					?>
						</select><br>
				<?php	} ?>
					
				</td>
				<td>
				<img alt="История" src="/img/icons/histori.png"  data-id="<?=$article_rec->getArticleId()?>"   data-tooltip="tooltip" class="img_return history_article" data-original-title="История изменения товара">
					<?php if ($art_by = $article_rec->getOrders()->count()) { ?>
                                <img alt="Покупки" src="/img/icons/shoppingcart.png"  data-id="<?=$article_rec->getArticleId()?>"   data-tooltip="tooltip" class="img_return shoping" data-original-title="Товар покупался <?=$art_by?> раз">
					<?php } else { ?>
					<img alt="Покупки" src="/img/icons/shoppingcart.png"  data-id="<?=$article_rec->getArticleId()?>"   data-tooltip="tooltip" class="img_return" data-original-title="Это первый заказ">
					<span>Всего куплено: 0 шт.</span>
					<?php } ?>
				</td>
			</tr>
    <?php } } ?>
    <tr>
        <td colspan="2">
                <div class="input-group mb-3">
                        <input name="add_article_by_barcode"  placeholder="Добавить по штрихкоду" class="form-control" placeholder="Артикул">
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" type="submit"   name="Toevoegen2" id="button-addon2">+</button>
  </div>
</div>
        </td>
        <td>
		<button type="submit" class="btn btn-default" name="edit" id="edit"><i class="glyphicon glyphicon-pencil" aria-hidden="true"></i> Изменить</button>
	</td>
               
    </tr>
<tr>
    <td colspan="4"><strong>Всего</strong></td>
    <td colspan="2"><strong><?php echo Number::formatFloat($t_real_price); ?> грн</strong></td>
</tr>
<tr>
    <td colspan="4"><strong>Доставка</strong></td>
    <!-- <td class="column-euro"><strong></strong></td> -->
    <td colspan="2"><strong><?php echo $peresilka; ?> грн</strong></td>
</tr>

<tr>
    <td colspan="4"><strong>Скидка клиента</strong></td>
    <!-- <td class="column-euro"><strong></strong></td> -->
    <td colspan="2"><strong><?=$this->getOrder()->customer->getDiscont($this->getOrder()->getId())?> %</strong></td>
</tr>
<tr>
    <td colspan="4"><strong>Сумма скидки</strong></td>
    <td colspan="2"><strong><?=Number::formatFloat($sum_skudka)?> грн.</strong></td>

</tr>
<?php if ($this->getOrder()->getKuponPrice() > 0) { ?>
    <tr>
        <td colspan="4"><strong>Скидка по купону</strong></td>
        <td colspan="2"><strong><?=$this->getOrder()->getKuponPrice()?>%</strong></td>
    </tr>
<?php } ?>
<?php if ($this->getOrder()->getBonus() > 0) { ?>
    <tr>
        <td colspan="4"><strong>Бонусная скидка</strong></td>
        <td colspan="2"><strong><?=$this->getOrder()->getBonus()?>грн.</strong></td>
    </tr>
<?php } ?>
<tr>
    <td colspan="4"><strong>Всего со скидкой и доставкой</strong></td>
    <td colspan="2"><strong><?php
      echo Number::formatFloat($SumOrder, 2);
            ?> грн</strong></td>
</tr>
<?php if ($this->getOrder()->getDeposit() > 0) { ?>
    <tr>
        <td colspan="4"><strong>Депозит</strong></td>
        <td colspan="2"><strong><?=$this->getOrder()->getDeposit()?> грн</strong></td>
    </tr>
<?php } ?>

<tr>
    <td colspan="4"><strong>У пользователя на депозите</strong></td>
    <td colspan="4"><strong><?=$this->getOrder()->customer->getDeposit()?$this->getOrder()->customer->getDeposit():0?> грн</strong>   
<?php if($this->getOrder()->customer->getDeposit() and $this->getOrder()->getDeposit() == 0){ ?><a href="/admin/usedeposit/id/<?=$this->getOrder()->getId()?>">Использовать депозит</a> 
<?php }elseif($this->getOrder()->getDeposit() > 0){ ?><a href="/admin/unusedeposit/id/<?=$this->getOrder()->getId()?>"
           onclick="return confirm('При отмене депозита сума депозита вернется на счет клиента, а сумма заказа изменится. Продолжить ?')">Отменить
            депозит</a><?php } ?>
    </td>
</tr>
</tbody>
</table>
</form>
  </div>
  </div>


<div class="card pd-20 mb-3">
  <h6 class="card-body-title">Комментарий (внутренний)</h6>
 <div class="card-body">
     <div class="row">
         <div class="col-12 col-xl-4">
             <?php
             if($rem = $this->getOrder()->getRemarks()){ ?>
             <ul class="list-group">
                 <?php foreach ($rem as $remark) { ?> 
                 <li class="list-group-item"><?php $d = new wsDate($remark->getDateCreate()); echo $d->getFormattedDate(); ?>  <?php echo $d->getHour() . '.' . $d->getMinute(); ?> : <?=$remark->getName()?> -> <?php echo $remark->getRemark(); ?></li>
                 <?php } ?>
             </ul>
            <?php } ?>
         </div>
         
         <div class="col-12 col-xl-4">
             <form method="post" action="" id="add_remark">
				<input type="hidden" name="add_remark" value="1"/>
                                <textarea name="remark" class="form-control"  rows="10"  id="remark"></textarea>
				
		</form>	
         </div>
             <div class="col-12 col-xl-4">
                 <input type="button" class="btn btn-info mg-r-5 btn-lg" name="button" id="button" value="Добавить"/>
             </div>
             
     </div>
</div>
</div>

<?php if ($this->getOrder()->getStatus()!=2){ ?>
	<div class="card pd-20" >
 <div class="card-body">
     <div class="alert alert-danger error_to_order" role="alert" style="display:none;">
         <strong>Ошибка!  </strong> <span class="error_to_order_text"></span>
     </div>
	<form method="post" action="" style="text-align: center; padding-top:20px;" class="go_order">
	<input type="submit" name="converting_to_order" class="btn btn-primary btn-lg"  value="В заказы">
	<input type="submit" name="delete_qo" class="btn btn-danger btn-lg" value="Отмена">
	</form>
	<script>
	 $(".go_order").submit(function (e) {
                e.preventDefault();
                console.log(e.serialize());
      
		if(!$('#delivery_type').val()) {
                    $('.error_to_order_text').html('Укажите способ доставки');
                    $('.error_to_order').show();
		return false;
		}else if(!$('#payment_method').val()){
                    $('.error_to_order_text').html('Укажите способ оплаты');
                    $('.error_to_order').show();
                    return false;
                }

       $(this).submit();
    });
	
	</script>
	 </div>
 </div>
	<?php } ?>
<script>
$(document).ready(function () {
    $('#button').click(function(){
        $('#add_remark').submit();
    });
    
     $('.prev').hover(function () {
     $(this).parent().find('div.simple_overlay').show();
     }, function () {
     $(this).parent().find('div.simple_overlay').hide();
     });
 });
         /*
 $('#article_id').hover(function () {
		$('#aih_box img').hide();
		$('#aih_box #aih_' + $(this).attr('value')).show();
	}, function () {
		$('#aih_box img').hide();
	});
        */
function addAkciya(e){
if(e.value > 0){
		var data_to_post = new Object();
        data_to_post.id = e.name.substr(10);
        data_to_post.addskidka = 'add_sk';
        data_to_post.option_id = e.value;
		//console.log(data_to_post);
        $.post('<?=$this->path ."shop-orders/"?>', data_to_post, function(data){
		console.log(data); 
		document.location.reload(true);
		} ,'json');
		
	return false;	
}

return false;
}
$('.history').click(function (e) {
var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/orderhistory/id/'+id+'/m/1',function (data) {fopen('История изменения заказа №'+id, data);});	
});
$('.history_article').click(function (e) {
var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/articlehistory/id/'+id+'/m/1',function (data) {fopen('История изменения товара', data);});	
});
$('.shoping').click(function (e) {
//var article_id = e.target.attributes.getNamedItem("data-id").value;
var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/ordersbyartycle/id/'+id+'/m/1',function (data) {fopen('История покупок товара', data);});	
});
</script>