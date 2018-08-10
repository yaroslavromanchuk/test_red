<div style="width: 260px;margin:auto;" class="row">
<i class=" ion-calendar tx-30 pd-5" style="float:left;"></i><p style="font-size: 20px;float: left;padding: 5px;margin: 0 5px;">Заказ <span id="get_order_id"><?=$this->getOrder()->getId();?></span></p>
<i class="icon ion-settings bleak tx-30 pd-5 view_detaly" data-placement="bottom"  data-tooltip="tooltip"  data-original-title="Показать детали"></i>
<?php if ($this->user->isSuperAdmin()) { ?>
<i class="icon ion-clock bleak tx-30 pd-5 history" alt="История" data-id="<?=$this->getOrder()->getId();?>" data-placement="bottom" title="" data-tooltip="tooltip" data-original-title="Смотреть историю заказа"></i>
    <?php } ?>
<script type="text/javascript">
  $(document).ready(function () {
        $('.view_detaly').click(function () {
		if($('.detaly_order').is(":visible")){
		$('.detaly_order').slideUp();
		}else{
		$('.detaly_order').slideDown();
		}
		});
		});
</script>
</div>
<?php if (@$this->errordell) { ?>
    <div id="errormessage"><img src="<?=SITE_URL;?>/img/icons/error.png" alt=""   class="page-img"/>
        <h1>Ошибки при удалении товара:</h1>
        <p><?=$this->errordell;?></p>
    </div>
<?php } ?>

<div style=" width: 100%;    display: none;text-align: center;" class="detaly_order">
<div style="display:inline-block; width:59%;margin-right: 1%;" >
<p style="text-align:center;"><strong>Информация о покупателе</strong></p>
<form method="POST" action="" id="user_info" class="form-horizontal">
<table cellpadding="4" cellspacing="0" id="order-client" align="center">
<?php
//echo date("Y-m-d H:i:s", strtotime("now +2 days"));
$order_owner = new Customer($this->getOrder()->getCustomerId());
if ($order_owner->getAdminComents()) { ?>
    <tr>
        <td class="column-data">Комментарий админа</td>
        <td style="color:red;"><?php echo $order_owner->getAdminComents(); ?></td>
    </tr>
<?php } ?>
<tr>
    <td class="column-data">ИД</td>
    <td><b><?php echo $this->getOrder()->getCustomerId();?></b> <?php echo '   (Общая сумма заказов: <b>' . Shoparticles::showPrice($this->total_amount) . ' грн</b>)' ?></td>
</tr>
<tr>
    <td class="column-data">Имя</td>
    <td><input name="name" class="form-control input"  value="<?php echo ($this->getOrder()->getName() ? $this->getOrder()->getName() : "")?>"></td>
</tr>
<tr>
    <td class="column-data">Фамилия</td>
    <td><input name="middle_name" class="form-control input"  value="<?php echo ($this->getOrder()->getMiddleName() ? $this->getOrder()->getMiddleName() : "")?>"></td>
</tr>

<tr>
    <td class="column-data">Адрес</td>
    <td><input name="address" class="form-control input"  value="<?php echo $this->getOrder()->getAddress() ? $this->getOrder()->getAddress() : ""; ?>"></td>
</tr>
<tr>
    <td class="column-data">Город</td>
    <td><input name="city" class="form-control input"  value="<?php echo $this->getOrder()->getCity() ? $this->getOrder()->getCity() : ""; ?>"></td>
</tr>
<tr>
    <td class="column-data">Область</td>
    <td><input name="obl" class="form-control input"  value="<?php echo $this->getOrder()->getObl() ? $this->getOrder()->getObl() : ""; ?>"></td>
</tr>
<tr>
    <td class="column-data">Район</td>
    <td><input name="rayon" class="form-control input"  value="<?php echo $this->getOrder()->getRayon() ? $this->getOrder()->getRayon() : ""; ?>"></td>
</tr>
<tr>
    <td class="column-data">Индекс</td>
    <td><input name="index" class="form-control input"  value="<?php echo $this->getOrder()->getIndex() ? $this->getOrder()->getIndex() : ""; ?>"></td>
</tr>
<tr>
    <td class="column-data">Улица</td>
    <td><input name="street"  class="form-control input" value="<?php echo $this->getOrder()->getStreet() ? $this->getOrder()->getStreet() : ""; ?>"></td>
</tr>
<tr>
    <td class="column-data">Дом</td>
    <td><input name="house" class="form-control input"  value="<?php echo $this->getOrder()->getHouse() ? $this->getOrder()->getHouse() : ""; ?>"></td>
</tr>
<tr>
    <td class="column-data">Квартира</td>
    <td><input name="flat" class="form-control input"  value="<?php echo $this->getOrder()->getFlat() ? $this->getOrder()->getFlat() : ""; ?>"></td>
</tr>
<tr>
    <td class="column-data">Склад</td>
    <td><input name="sklad" class="form-control input"  value="<?php echo $this->getOrder()->getSklad() ? $this->getOrder()->getSklad() : ""; ?>"></td>
</tr>
<tr>
    <td class="column-data">Телефон</td>
    <td><input name="telephone" class="form-control input"  value="<?php echo $this->getOrder()->getTelephone() ? $this->getOrder()->getTelephone() : ""; ?>"></td>
</tr>
<tr>
    <td class="column-data">E-mail</td>
    <td><input name="email" class="form-control input"  value="<?php echo $this->getOrder()->getEmail() ? $this->getOrder()->getEmail() : ""; ?>" type="email"></td>
</tr>
<tr id="d_d"  <?php echo $this->getOrder()->getDeliveryTypeId() != 9 ? 'style="display:none;"': ''?>>
    <td class="column-data">Дата доставки</td>
    <td><input name="delivery_date" class="form-control input"  value="<?php if($this->getOrder()->getDeliveryDate()) echo date('Y-m-d', strtotime($this->getOrder()->getDeliveryDate()))?>" type="date" ></td>
</tr>
<tr id="t_d" <?php echo $this->getOrder()->getDeliveryTypeId() != 9 ? 'style="display:none;"': ''?>>
    <td class="column-data">Время доставки</td>
    <td><input name="delivery_interval" class="form-control input"  value="<?php echo $this->getOrder()->getDeliveryInterval() ? $this->getOrder()->getDeliveryInterval() : ""; ?>" type="text" ></td>
</tr>
<tr>
    <td class="column-data">Комментарий</td>
    <td><?php echo trim($this->getOrder()->getComments()) ? '<div style="border:1px dashed #666; min-height: 18px;padding: 2px;background: #ffff33;">'.htmlspecialchars($this->getOrder()->getComments()).'</div>' : "отсутствует"; ?></td>
</tr>
<tr>
    <td class="column-data">Способ доставки</td>
    <td>
        <select name="delivery_type_id" class="form-control input"  id="delivery_type">
         <?php foreach (wsActiveRecord::useStatic('DeliveryType')->findAll(array('active'=>1), array('sort'=>'ASC')) as $method) { ?>
         <option value="<?=$method->getId()?>" <?php if ($method->getId() == $this->getOrder()->getDeliveryTypeId()) echo 'selected="selected"'?>><?=$method->getName()?></option><?php } ?>
        </select>
		</td>
</tr>
<tr>
    <td class="column-data">Способ оплаты</td>
    <td>
        <select name="payment_method_id" class="form-control input"  id="payment_method">
            <?php foreach (wsActiveRecord::useStatic('DeliveryPayment')->findAll(array('delivery_id'=>$this->getOrder()->getDeliveryTypeId())) as $method) {
			?>
         <option value="<?=$method->getPaymentId()?>" <?php if ($method->getPaymentId() == $this->getOrder()->getPaymentMethodId()) echo 'selected="selected"'?>><?=$method->payment->getName()?></option>
            <?php } ?>
        </select>

    </td>
</tr>
<script>
function parseHash(){

    var hash = window.location.hash;
	
    var page = /flag=(\d+)/.exec(hash); 
	//console.log(page);
if(page != null){   
   if (page[1] > 0){
   //console.log($('#'+page[1]).offset().top+500);
	 $('#content').animate({ scrollTop: $('#'+page[1]).offset().top }, 1);
	 return false;
    }
	}

}
    $(document).ready(function () {
	parseHash();
	
	$('#savepage').on( "click", function () {
		$.ajax({
			beforeSend: function( data ) {
				//$('#savepage').attr('value', 'Сохраняется...');
			},
			type: "POST",
			url: '/admin/orderinfo/id/'+<?=$this->getOrder()->getId();?>,
			data: $("#user_info").serialize()+'&ajax=1',
			dataType: 'json',
			success: function( data ) {
				console.log('success');
				//$( "#errormessage" ).slideUp();
			//$('#save_done').slideDown().delay(1600).slideUp();
			},
			complete: function( data ) {
			$('#save_done').slideDown().delay(1000).slideUp();
				//$('#savepage').attr('value', 'Сохранить информацию');
			},
			error: function( e ) {
			console.log(e);
				$( "#errormessage" ).empty().append( e.responseText ).slideDown();
			}
		});
		//return false;
	} )
	
        $('#delivery_type').change(function () {
            var delivery = $(this).val();
            var payment = $('#payment_method').val();
            if (delivery == 4) {
                $('.ukr>span').html('*');
            }
            else {
                $('.ukr>span').html('');
            }
			if(delivery == 9){ $('#d_d').show(); $('input[name="delivery_date"]').addClass("border border-danger");
			$('#t_d').show(); $('input[name="delivery_interval"]').addClass("border border-danger");
			}else{
			$('#d_d').hide();
			$('#t_d').hide();
			}
			
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
                    }else {
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
                            "id=" + $('#get_order_id').html() + '&delyvery=' + $('#delivery_type').val() + '&payment=' + $('#payment_method').val(),
							function (res) {
								$("#dely_cost").html(res);
							console.log(res); 
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
                "id=" + $('#get_order_id').html() + '&delyvery=' + $('#delivery_type').val() + '&payment=' + $('#payment_method').val(),
				function (res) {
				$("#dely_cost").html(res);
				console.log(res);
                });
        });
    });

</script>

<?php  $peresilka = Shoparticles::showPrice($this->getOrder()->getDeliveryCost()); ?>
<tr>
    <td class="column-data">Стоимость доставки</td>
    <td ><span id="dely_cost"><?=$peresilka;?></span> грн</td>
</tr>
<tr>
	<td class="column-data"></td>
	<td>
		<button type="button" class="btn btn-primary"  name="savepage" id="savepage"><i class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></i> Сохранить информацию</button>
		
		<p id="save_done" style="display:none; color:red; font-size: 20px;margin: 0;padding-top: 8px;">Сохранено!</p>
	</td>
</tr>
<tr>
    <td class="column-data">Акционная скидка</td>
    <td><?php echo $this->getOrder()->getEventSkidka(); ?> % <a href="/admin/editskidkabyorder/id/<?=$this->getOrder()->getId()?>">Изменить/Добавить скидки к товарам</a></td>
</tr>
<!--
<tr>
    <td colspan="2">
        <a href="/admin/orderinfo/id/<?php //echo $this->getOrder()->getId(); ?>" target="_blank">Редактировать</a>
    </td>
</tr>-->
<tr>
    <td colspan="2">
        <a href="/admin/user/edit/id/<?php echo $this->getOrder()->getCustomerId(); ?>"  target="_blank">Редактировать клиента</a>
    </td>
</tr>
</table>
</form>
</div>
<div style="width:39%; display:inline-block;float: right;">
<p style="text-align:center;"><strong>Информация о заказе</strong></p>
<form action="" method="post" class="form-horizontal" style="text-align:left;">
	<div class="form-group">
    <label  class="ct-110 control-label">Номер заказа:</label>
    <div class="col-xs-6">
	<span><?=$this->getOrder()->getId();?></span>
    </div>
  </div>
      <?php if ($this->getOrder()->getComlpect()) { ?>
	  	<div class="form-group">
    <label  class="ct-110 control-label">Скомплектовано с:</label>
    <div class="col-xs-6">
	<span><?=$this->getOrder()->getComlpect();?></span>
    </div>
  </div>
    <?php } ?>
  	<div class="form-group">
    <label  class="ct-110 control-label">Дата:</label>
    <div class="col-xs-6">
	<span><?php $d = new wsDate($this->getOrder()->getDateCreate()); echo $d->getFormattedDateTime(); ?></span>
    </div>
  </div>
  <?php if($this->getOrder()->getDeliveryTypeId() == 8 or $this->getOrder()->getDeliveryTypeId() == 16 or $this->getOrder()->getDeliveryTypeId() == 4){ ?>
  	<div class="form-group">
    <label  class="ct-110 control-label">Накладная №:</label>
    <div class="col-xs-6">
	<input type="text" class="form-control input" name="nakladna" value="<?=$this->getOrder()->getNakladna(); ?>"/>
    </div>
  </div>
	<?php } ?>
	  	<div class="form-group">
    <label  class="ct-110 control-label">Статус:</label>
    <div class="col-xs-6">
	<select name="order_status" class="form-control input" onChange="this.form.submit(); return false;"  >
<?php foreach ($this->order_status as $key => $item) { ?>
		<option value="<?=$key;?>"<?php echo $key == $this->getOrder()->getStatus()?" selected":''; ?>><?=$item?></option>
<?php } ?>
			</select>
    </div>
  </div>
  <?php if($this->user->isOperatorAdmin() or $this->user->isDeveloperAdmin()){ ?>
    <div class="form-group">
    <label  class="ct-110 control-label">Скидка:</label>
    <div class="col-xs-6">
	<input type="text" name="skidka" class="form-control input"  value="<?=$this->getOrder()->getSkidka()?$this->getOrder()->getSkidka():'';?>">
    </div>%
  </div>
  <?php } ?>
    	<div class="form-group">
    <label  class="ct-110 control-label">Код купона:</label>
    <div class="col-xs-6">
	<input type="text" name="kupon" class="form-control input"  value="<?php echo $this->getOrder()->getKupon()?$this->getOrder()->getKupon():''; ?>" />
    </div>
  </div>
    	<div class="form-group">
    <label  class="ct-110 control-label">Скидка по купону:</label>
    <div class="col-xs-6">
	<input type="text" name="kupon_price" class="form-control input"  value="<?php echo $this->getOrder()->getKuponPrice(); ?>"/>
  </div>
  </div><br>
  <div class="form-group">
    <div class="col-xs-offset-3 col-xs-11">
      <button type="submit"  class="btn btn-default"><span style="font-weight: bold;font-size: 18px;"><i class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></i> Сохранить</span></button>
    </div>
  </div>
<table cellspacing="0" cellpadding="4" id="order-details" style="display:none;">

    <?php if ($this->order->getFirst() == 1) { ?>
        <tr>
            <td class="column-data" colspan="2">Уточнить детали (первый заказ). <a
                    href="/admin/calling/?order=<?php echo $this->order->getId(); ?>&type=first">Уточнили.</a></td>
        </tr>
    <?php } ?>
    <?php if ($this->order->getFirst() == 2) { ?>
        <tr>
            <td class="column-data" colspan="2">Детали (первый заказ) уточнили. </td>
        </tr>
    <?php } ?>
    <?php if ($this->order->getFirst()==2 or $this->order->getFirst()==1) { ?>
          <tr>
              <td class="column-data" colspan="2"><a href="/admin/calling/?order=<?php echo $this->order->getId(); ?>&type=otmena">СМС отмена.</a></td>
          </tr>
      <?php } ?>
    <?php if ($this->order->getFirst()==3 ) { ?>
        <td class="column-data" colspan="2">Отправлена СМС отмена. </td>
    <?php } ?>
    <?php if ($this->order->getCallMy() == 1) { ?>
        <tr>
            <td class="column-data" colspan="2">Перезвонить для уточнения деталей. <a
                    href="/admin/calling/?order=<?=$this->order->getId();?>">Уточнили.</a></td>
        </tr>
    <?php } ?>
    <?php if ($this->order->getCallMy() == 2) { ?>
        <tr>
            <td class="column-data" colspan="2">Нет необходимости подтверждать заказ по телефону</td>
        </tr>
    <?php } ?>




	<?php if ($this->getOrder()->getBoxNumber()) { ?>
	<tr>
		<td class="column-data">Номер ячейки</td>
		<td>
			№<input type="text" name="box_number" value="<?php echo $this->getOrder()->getBoxNumber(); ?>"/>
		</td>
	</tr>
<?php } ?>
</table>
</form>
</div>
</div>

<p id="555"><strong>Товары</strong></p>
<?php if($this->getOrder()->getDeposit() > 0) { echo '<p style="padding: 5px;border-radius: 2px;background: #37d011;width: 200px;color: #040404;font-size: 16px;margin: 5px auto;"><b>Присутствует депозит!</b></p>';} ?>

<form method="post" action="" class="form-horizontal" style="width: 800px;margin:auto;background: #f9f9f9;">
   <table cellpadding="4" cellspacing="0" id="order-articles" class="table" >
<tr>
    <td colspan="2"><strong>Действие</strong></td>
    <td class="column-article"><strong>Кол./Товар</strong></td>
    <td><strong>Размер/Цвет</strong></td>
    <td><strong>Цена</strong></td>
	<td><strong>Детали</strong></td>
</tr>
    <?php 
      $SumOrder = $this->getOrder()->calculateOrderPrice(true, false, true);
	
	$t_price = 0.00; $t_option = 0.00;
	$t_real_price =0.00; $sum_skudka = 0.00;
	if ($this->getOrder()->getArticles()->count()) { ?>
        <?php 
        foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
            $article = new Shoparticles($article_rec->getArticleId());
            ?>
			<tr id="<?=$article_rec->getId()?>" <?php if($article->getCategoryId() == 16){ echo 'style="background: rgba(210, 33, 33, 0.43);"';} ?>>
				<td  colspan="2">
					<img class="prev w100" rel="#miesart<?=$article_rec->getId(); ?>"
						 src="<?=$article_rec->getImagePath('listing')?>"
						 alt="<?=htmlspecialchars($article_rec->getTitle())?>"
						 style="box-shadow: 0px 0px 20px #BBB;margin: 10px;cursor: pointer;"/>

					<div class="simple_overlay" id="miesart<?=$article_rec->getId(); ?>" style="position: absolute;  margin-left: 170px; margin-top: -170px;">
						<img src="<?=$article_rec->getImagePath('detail');?>" alt="<?=htmlspecialchars($article_rec->getTitle());?>" style="max-width:300px;border-radius: 10px;"/>
					</div><br>
					<?php if($article_rec->getCount() > 0){ ?>
					<a href="/admin/shop-articles/edit/id/<?=$article->getId();?>" title="Редактировать" data-placement="bottom"  data-tooltip="tooltip" style="display: inline-block;">
						<img src="<?=SITE_URL;?>/img/icons/edit-small.png" alt="Редактировать" class="img_return"/>
					</a>
					<a href="<?=$this->path;?>shop-orders/adelete/id/<?=$article_rec->getId();?>/#flag=<?=$article_rec->getId();?>" onclick="return confirm('Удалить?');" style="display: inline-block;" data-placement="bottom"  data-tooltip="tooltip" data-original-title="Удалить на сайт">
						<img src="<?=SITE_URL;?>/img/icons/cantremove-small.png" alt="Удалить" class="img_return" />
					</a>
					<a href="<?=$this->path;?>shop-orders/adeletenoshop/id/<?=$article_rec->getId();?>/" onclick="return dell(this);" data-original-title="Удалить без возврата на сайт" data-placement="bottom"  data-tooltip="tooltip" style="display: inline-block;">
				<img src="<?=SITE_URL;?>/img/icons/remove-small.png" alt="Удалить" class="img_return"/>
				</a>
				<?php if($this->admin_rights['491']['right'] == 1){ ?>
	<img src="<?=SITE_URL;?>/img/icons/return.png" alt="Возврат" name="<?=$article_rec->getId();?>"   style="display: inline-block;" title="Возврат" class="img_return" data-placement="bottom"  data-tooltip="tooltip" onclick="return ret(this);"/>
					<?php }?>
				
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
				<td class="column-article">
					<?php echo 'Количество: <b>' . $article_rec->getCount().'</b>'; ?>
					<br><span style="color: #048;"><?=$article_rec->getCode()?></span>
					<br><a href="<?=$article->getPath(); ?>" target="_blank"><?=$article_rec->getTitle()?></a>
					<br><span style="color: #777;"><?=$article_rec->article_db->category->getRoutez()?></span>
					<br>
					<br>
					Наличие:
					<?php $art = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article_rec->getArticleId(), 'id_size' => $article_rec->getSize(), 'id_color' => $article_rec->getColor()));
						$cnt = $art->getCount();
					
					if ((int)$cnt > 0) { ?>
					<select name="count-<?=$article_rec->getId()?>"  class="count form-control input w150">
					<?php
		for ($i = 1; $i <= $cnt; $i++) echo ($i != $article_rec->getCount()) ? "<option value=\"{$i}\">{$i}</option>" : "<option value=\"{$i}\" selected=\"selected\">{$i}</option>";?>
					</select><br>
					<label><input type="checkbox" name="edit_count-<?= $article_rec->getId(); ?>" class="chek_edit"> Изменить</label>
				<?php  } else echo 'Нет на складе';	?>
				
				
				</td>
				<td>
					<input type="hidden" class="hidden" value="<?=$article->getId() ?>">
					<?=$article_rec->sizes->getSize().' / '.$article_rec->colors->getName()?>
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
					<b><?php echo Number::formatFloat($price_show['price']); ?> грн</b>
					<?=@$price_show['comment']?'<br><span style="font-size:10px;color:red;">'.$price_show['comment'].'</span>':''?>
				</td>
				<td>
				<img alt="История" src="/img/icons/histori.png"  data-id="<?=$article->getId()?>"   data-tooltip="tooltip" class="img_return history_article" data-original-title="История изменения товара">
					<?php if ($article->ArtycleBuyCount() == 0) { ?>
					<img alt="Покупки" src="/img/icons/shoppingcart.png"  data-id="<?=$article->getId()?>"   data-tooltip="tooltip" class="img_return" data-original-title="Это первый заказ">
					<span>Всего куплено: 0 шт.</span>
					<?php } else { ?>
					<img alt="Покупки" src="/img/icons/shoppingcart.png"  data-id="<?=$article->getId()?>"   data-tooltip="tooltip" class="img_return shoping" data-original-title="Товар покупался <?=$article->ArtycleBuyCount()?> раз">
					<?php } ?>
				</td>
			</tr>
    <?php } } ?>
    <tr>
        <td colspan="2"></td>
        <td>
		<button type="submit" class="btn btn-default" name="edit" id="edit"><i class="glyphicon glyphicon-pencil" aria-hidden="true"></i> Изменить</button>
		</td>
		<td colspan="3">
		<input name="add_article_by_barcode" placeholder="Добавить по штрихкоду" class="form-control input "/>
		<input type="submit" class="btn btn-small btn-default"   name="Toevoegen2" value="+"/>
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
    <td colspan="2"><strong><?=$order_owner->getDiscont($this->getOrder()->getId())?> %</strong></td>
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
    <td colspan="4"><strong><?=$order_owner->getDeposit()?$order_owner->getDeposit():0?> грн</strong>   
<?php if($order_owner->getDeposit() and $this->getOrder()->getDeposit() == 0){ ?><a href="/admin/usedeposit/id/<?=$this->getOrder()->getId()?>">Использовать депозит</a> 
<?php }elseif($this->getOrder()->getDeposit() > 0){ ?><a href="/admin/unusedeposit/id/<?=$this->getOrder()->getId()?>"
           onclick="return confirm('При отмене депозита сума депозита вернется на счет клиента, а сумма заказа изменится. Продолжить ?')">Отменить
            депозит</a><?php } ?>
    </td>
</tr>
<tr>
    <td colspan="8" class="tussenrij">&nbsp;</td>
</tr>
<tr>
    <td colspan="2" style="vertical-align:middle;"><strong>Счет</strong></td>
    <td colspan="6" style="vertical-align:middle;">
		<script type="text/javascript">
			function NowaMail(id, object) {
				$.post('/admin/nowamail/', {id: id}, function (data) {
					object.parent().html(data);
				});
			}
		</script>
		<strong>
			<a target="_blank" href="/admin/generateorder/id/<?=$this->getOrder()->getId()?>/type/1" style="padding-right:10px;">Магазин</a>
			<a target="_blank" href="/admin/generateorder/id/<?=$this->getOrder()->getId()?>/type/2" style="padding-right:10px;">Укрпочта</a>
			<a target="_blank" href="/admin/generateorder/id/<?=$this->getOrder()->getId()?>/type/3" style="padding-right:10px;">Новая почта</a>
			<a target="_blank" href="/admin/generateorder/id/<?=$this->getOrder()->getId()?>/type/4" style="padding-right:10px;">Курьер</a>
			<?php if ($this->getOrder()->getDeliveryTypeId() == 8 and false) {
				if ($this->getOrder()->getNowaMail() == '0000-00-00 00:00:00') {
					?>
					<span> <a href="#"
							  onclick="NowaMail(<?php echo $this->getOrder()->getId(); ?>,$(this)); return false;"
							  class="nowa_mail"><button>Отправить счет</button></a></span>
				<?php } else { ?>
					<span> счет отправлен: <?php echo date('d-m-Y', strtotime($this->getOrder()->getNowaMail())); ?>
						<a href="#"
						   onclick="NowaMail(<?php echo $this->getOrder()->getId(); ?>,$(this)); return false;"
						   class="nowa_mail"><button>Отправить повторно</button></a></span>
				<?php } ?>
			<?php } ?>
        </strong></td>
</tr>
<tr>
    <td colspan="2">
        <b><a target="_blank" href="/admin/masgeneratechek/ids/<?php echo $this->getOrder()->getId(); ?>" style="padding-right:10px;">Чек</a></b>
    </td>
    <td colspan="6"></td>
</tr>
</table>
</form>

<p class="col-xl-12" style="margin: 20px;"><b>Комментарий (внутренний)</b></p>

<div class="row" style="margin: 0 20px;">
<div class="col-xl-12">
<ul class="list-group">
<?php foreach ($this->getOrder()->getRemarks() as $remark) { ?>
<li class="list-group-item">
    <label  class="control-label"><?=$remark->getName()?>:</label>
	<div style="display: inline-block;"><?=$remark->getRemark()?></div>
	<div style="display: inline-block;    float: right;"><?=date('H:i d.m.Y ', strtotime($remark->getDateCreate()))?></div>
  </li>
  <?php } ?>
  </ul>
<form method="post" action="">	 
	  <div class="form-group" style="display: inline-block;">
	  
	  <label  class="ct-110 control-label">Добавить:</label>
	   <div class="col-md-12 col-lg-9 col-xl-9 ">
	   <input type="hidden" name="add_remark" value="1"/>
				<textarea name="remark" class="form-control" id="remark" cols='55' rows="3"></textarea><br>
				<button type="submit" name="button" id="button" class="btn btn-default"><i class="glyphicon glyphicon-plus-sign" aria-hidden="true"></i> Добавить</button>
				<?php if($this->admin_rights['506']['right'] == 1){ ?>
					<button type="button" name="go_transfer" id="go_transfer" class="btn btn-default"><i class="glyphicon glyphicon-plus-sign" aria-hidden="true"></i> <?=$this->trans->get('Почтовый перевод');?></button>
				<?php }?>
					
				
	   </div>
	  </form>
	   </div>
	   
  
</div>
<div class="col-md-12 col-lg-3 col-xl-4">

</div>
</div>


<script type="text/javascript">
$('#go_transfer').click(function (e) {//форма отправки почтового перевода
var form ='<div class="input-group"><span class="input-group-addon" id="basic-addon1">Сумма</span><input type="text" id="summa" class="form-control" placeholder="Сумма перевода" aria-describedby="basic-addon1"></div><div class="input-group"><span class="input-group-addon" id="basic-addon1">Индекс</span><input type="text" id="ind" class="form-control" placeholder="Почтовый индекс" aria-describedby="basic-addon1"></div><button type="button"  onclick="go_post_ukr(this);" class="btn btn-default"><i class="glyphicon glyphicon-plus-sign" aria-hidden="true"></i> Отправить письмо</button></div>';
fopen('<?=$this->trans->get('Почтовый перевод');?>', form);
});

function go_post_ukr(e){// отправка почтового перевода
var id = $('#get_order_id').html();
var sum = $("#summa").val();
var index = ($("#ind").val());
if(sum.length > 1){
$("#summa").parent().removeClass("has-error");
if(index.length == 5){
$("#ind").parent().removeClass("has-error");

$.get('/admin/ukrpost/ukrpost_transfer/summa/'+sum+'/index/'+index+'/id/'+id, function (data) {fopen('<?=$this->trans->get('Почтовый перевод');?>', data);});	
//FormClose();
return true;
}else{
$("#ind").parent().addClass("has-error");
}
}else{
$("#summa").parent().addClass("has-error");
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
var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/ordersbyartycle/id/'+id+'/m/1',function (data) {fopen('История покупок товара', data);});	
});


$(document).ready(function () {
     $('.prev').hover(function () {
     $(this).parent().find('div.simple_overlay').show();
     }, function () {
     $(this).parent().find('div.simple_overlay').hide();
     });
    var color_end = 0;
    var size_end = 0;
    $('.color').change(function () {
        var color_id = $(this).val();
        color_end = color_id;
        var size = $(this).parent().find('.size');
        var size_id = size.val();
        if (color_id == '0') {
            window.location.reload(true);
            return(false);
        }
        size.attr('disabled', true);
        size.html('<option>загрузка...</option>');
        var url = '/page/getsize/';
        $.get(
            url,
            "color_id=" + color_id + '&article_id=' + $(this).parent().find('.hidden').val(),
            function (result) {
                if (result.type == 'error') {
                    alert('error');
                    return(false);
                }
                else {
                    var options = '';
                    var option = '';
                    $(result.size).each(function () {
                        option = '';
                        if (size_id == $(this).attr('id')) option = 'selected="selected"';
                        options += '<option value="' + $(this).attr('id') + '" ' + option + '>' + $(this).attr('title') + '</option>';
                    });
                    size.html(options);
                    size.attr('disabled', false);
                }
            },
            "json"
        );
        if (color_end != 0 && size_end != 0) {
            $(this).parent().parent().find('.chek_edit').removeAttr("checked");
            var count_item = $(this).parent().parent().find('.count');
            var count = count_item.val();
            count_item.attr('disabled', true);
            count_item.html('<option>загрузка...</option>');
            var url = '/page/getcount/';
            $.get(
                url,
                "color_id=" + color_end + '&size_id=' + size_end + '&article_id=' + $(this).parent().find('.hidden').val(),
                function (result) {
                    if (result.type == 'error') {
                        alert('error');
                        return(false);
                    }
                    else {
                        var options = '';
                        var option = '';
                        $(result.count).each(function () {
                            option = '';
                            if (count == $(this).attr('id')) option = 'selected="selected"';
                            options += '<option value="' + $(this).attr('id') + '" ' + option + '>' + $(this).attr('title') + '</option>';
                        });
                        count_item.html(options);
                        count_item.attr('disabled', false);
                    }
                },
                "json"
            );
        }
    });


    $('.size').change(function () {
        var size_id = $(this).val();
        size_end = size_id;
        var color = $(this).parent().find('.color');
        var color_id = color.val();
        if (size_id == '0') {
            window.location.reload(true);
            return(false);
        }
        color.attr('disabled', true);
        color.html('<option>загрузка...</option>');
        var url = '/page/getcolor/';
        $.get(
            url,
            "size_id=" + size_id + '&article_id=' + $(this).parent().find('.hidden').val(),
            function (result) {
                if (result.type == 'error') {
                    alert('error');
                    return(false);
                }
                else {
                    var options = '';
                    var option = '';
                    $(result.color).each(function () {
                        option = '';
                        if (color_id == $(this).attr('id')) option = 'selected="selected"';
                        options += '<option value="' + $(this).attr('id') + '"' + option + '>' + $(this).attr('title') + '</option>';
                    });
                    color.html(options);
                    color.attr('disabled', false);
                }
            },
            "json"
        );
        if (color_end != 0 && size_end != 0) {
            $(this).parent().parent().find('.chek_edit').removeAttr("checked");
            var count_item = $(this).parent().parent().find('.count');
            var count = count_item.val();
            count_item.attr('disabled', true);
            count_item.html('<option>загрузка...</option>');
            var url = '/page/getcount/';
            $.get(
                url,
                "color_id=" + color_end + '&size_id=' + size_end + '&article_id=' + $(this).parent().find('.hidden').val(),
                function (result) {
                    if (result.type == 'error') {
                        alert('error');
                        return(false);
                    }
                    else {
                        var options = '';
                        var option = '';
                        $(result.count).each(function () {
                            option = '';
                            if (count == $(this).attr('id')) option = 'selected="selected"';
                            options += '<option value="' + $(this).attr('id') + '" ' + option + '>' + $(this).attr('title') + '</option>';
                        });
                        count_item.html(options);
                        count_item.attr('disabled', false);
                    }
                },
                "json"
            );
        }
    });
    $('.color_id').change(function () {
        var color_id = $(this).val();
        var size_id = $('.size_id').val();
        if (color_id == '0') {
            window.location.reload(true);
            return(false);
        }
        $('.size_id').attr('disabled', true);
        $('.size_id').html('<option>загрузка...</option>');
        var url = '/page/getsize/';
        $.get(
            url,
            "color_id=" + color_id + '&article_id=' + $('#article_id').val(),
            function (result) {
                if (result.type == 'error') {
                    alert('error');
                    return(false);
                }
                else {
                    var options = '';
                    var option = '';
                    $(result.size).each(function () {
                        option = '';
                        if (size_id == $(this).attr('id')) option = 'selected="selected"';
                        options += '<option value="' + $(this).attr('id') + '" ' + option + '>' + $(this).attr('title') + '</option>';
                    });
                    $('.size_id').html(options);
                    $('.size_id').attr('disabled', false);
                }
            },
            "json"
        );
    });


    $('.size_id').change(function () {
        var size_id = $(this).val();
        var color_id = $('.color_id').val();
        if (size_id == '0') {
            window.location.reload(true);
            return(false);
        }
        $('.color_id').attr('disabled', true);
        $('.color_id').html('<option>загрузка...</option>');
        var url = '/page/getcolor/';
        $.get(
            url,
            "size_id=" + size_id + '&article_id=' + $('#article_id').val(),
            function (result) {
                if (result.type == 'error') {
                    alert('error');
                    return(false);
                }
                else {
                    var options = '';
                    var option = '';
                    $(result.color).each(function () {
                        option = '';
                        if (color_id == $(this).attr('id')) option = 'selected="selected"';
                        options += '<option value="' + $(this).attr('id') + '"' + option + '>' + $(this).attr('title') + '</option>';
                    });
                    $('.color_id').html(options);
                    $('.color_id').attr('disabled', false);
                }
            },
            "json"
        );
    });
    $('#article_id').change(function () {
        $('.color_id').attr('disabled', true);
        $('.color_id').html('<option>загрузка...</option>');
        $('.size_id').attr('disabled', true);
        $('.size_id').html('<option>загрузка...</option>');
        var url = '/page/getcolorandsize/';
        $.get(
            url,
            '&article_id=' + $(this).val(),
            function (result) {
                if (result.type == 'error') {
                    alert('error');
                    return(false);
                }
                else {
                    var options = '';
                    $(result.color).each(function () {
                        options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
                    });
                    $('.color_id').html(options);
                    $('.color_id').attr('disabled', false);
                    var options = '';
                    $(result.size).each(function () {
                        options += '<option value="' + $(this).attr('id') + '" >' + $(this).attr('title') + '</option>';
                    });
                    $('.size_id').html(options);
                    $('.size_id').attr('disabled', false);
                }
            },
            "json"
        );

    });
});

</script>
<script type="text/javascript">

    function loadArticles(category_id) {
        var data_to_post = new Object();
        data_to_post.id = category_id;
        data_to_post.getarticles = '1';
        $.post('<?=$this->path."shop-orders/"; ?>', data_to_post, function (data) {
            createSelectList(data);
        }, 'json');
        $('#article_id').html('');
        $('#option_id').html('');
    }

    function loadOptions(article_id) {
        var data_to_post = new Object();
        data_to_post.id = article_id;
        data_to_post.getoptions = '1';
        data_to_post.delivery_cost = '<?php echo Shoparticles::showPrice($this->getOrder()->getDeliveryCost()); ?>';
        data_to_post.articles_count = '<?php echo $this->getOrder()->count(); ?>';
        $.post('<?php echo $this->path . "shop-orders/"; ?>', data_to_post, function (data) {
            createSelectList(data);
        }, 'json');
        $('#option_id').html('');
    }

    function createSelectList(data) {
        if ('done' == data.result) {
            out = '';
            himg = '';
            for (var i = 0; i < data.data.length; i++) {
                if (data.data[i].img) {
                    himg += '<img style="display: none;" id ="aih_' + data.data[i].id + '" src="' + data.data[i].img + '"  />';
                }
                out += '<option value="' + data.data[i].id + '">' + data.data[i].title + himg + '</option>';
            }
            if ('articles' == data.type) {
                out = '<option value="0" selected>Выберите товар...</option>' + out;
                $('#article_id').html(out);
                $('#aih_box').html(himg);
            } else {
                out = '<option value="0" selected>Selecteer een optie...</option>' + out;
                $('#option_id').html(out);
            }
        }
    }
	$('#article_id').hover(function () {
		$('#aih_box img').hide();
		$('#aih_box #aih_' + $(this).attr('value')).show();
	}, function () {
		$('#aih_box img').hide();
	});

</script>
<script type="text/javascript" src="<?=SITE_URL.$this->files; ?>scripts/tiny_mce/tiny_mce.js"></script>