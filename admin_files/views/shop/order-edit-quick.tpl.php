 <div class="row">
 <div class="panel panel-primary">
 <div class="panel-heading">
 <h3 class="panel-title">Заявка <?=$this->getOrder()->getQuickNumber(); echo $this->getOrder()->getStatus()==2 ? ' <span style="color:#FF9800">Отменена</span>':''?></h3></div>
 <div class="panel-body">
 <div class="panel panel-success" >
 <div class="panel-heading"><h3 class="panel-title">Информация о покупателе</h3></div>
 <div class="panel-body">
<form method="POST" action="" id="user_info">
<table cellpadding="4" cellspacing="0" id="order-client" align="center">
<?php
$order_owner = new Customer($this->getOrder()->getCustomerId());
if ($order_owner->getAdminComents()) { ?>
	<tr>
		<td class="column-data">Комментарий админа</td>
		<td><?php echo $order_owner->getAdminComents(); ?></td>
	</tr>
<?php } ?>
<tr>
	<td class="column-data">Номер заявки</td>
	<td><?php echo $this->getOrder()->getQuickNumber(); ?></td>
</tr>
<tr>
	<td class="column-data">Номер заказа</td>
	<td id='get_order_id'><?php echo $this->getOrder()->getId(); ?></td>
</tr>
<tr>
	<td class="column-data">Имя <span class="red">*</span></td>
	<td><input name="name" class="form-control input" value="<?php echo ($this->getOrder()->getName() ? $this->getOrder()->getName() : "")?>"></td>
</tr>
<tr>
	<td class="column-data">Фамилия <span class="red">*</span></td>
	<td><input name="middle_name" class="form-control input" value="<?php echo ($this->getOrder()->getMiddleName() ? $this->getOrder()->getMiddleName() : "")?>"></td>
</tr>
<tr>
	<td class="column-data">Способ доставки <span class="red">*</span></td>
	<td>
		<select name="delivery_type_id" id="delivery_type" class="form-control input">
			<option value="0">&darr; Выберите из списка</option>
			<?php foreach (wsActiveRecord::useStatic('DeliveryType')->findAll(array('active'=>1)) as $method) { ?>
				<option value="<?php echo $method->getId() ?>" <?php if ($method->getId() == $this->getOrder()->getDeliveryTypeId()) echo 'selected="selected"'?>><?php echo $method->getName()?></option>
			<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<td class="column-data">Способ оплаты</td>
	<td>
		<select name="payment_method_id" id="payment_method" class=" form-control input">
			<?php foreach (wsActiveRecord::useStatic('PaymentMethod')->findAll() as $method) { ?>
				<option value="<?php echo $method->getId() ?>" <?php if ($method->getId() == $this->getOrder()->getPaymentMethodId()) echo 'selected="selected"'?>><?php echo $method->getName()?></option>
			<?php } ?>
		</select>
	</td>
</tr>
<tr>
	<td class="column-data">Адрес</td>
	<td><input name="address" class="form-control input" value="<?php echo $this->getOrder()->getAddress() ? $this->getOrder()->getAddress() : ""; ?>"></td>
</tr>
<tr>
	<td colspan="2" style="border: 1px dashed #999; padding: 0;"></td>
</tr>
<tr class="dop_fields ukr novp only_ukr only_novp" <?php ?>>
	<td class="column-data">Город</td>
	<td><input name="city" class="form-control input" value="<?php echo $this->getOrder()->getCity() ? $this->getOrder()->getCity() : ""; ?>"></td>
</tr>
<tr class="dop_fields ukr only_ukr">
	<td class="column-data">Индекс</td>
	<td><input name="index" class="form-control input" value="<?php echo $this->getOrder()->getIndex() ? $this->getOrder()->getIndex() : ""; ?>"></td>
</tr>
<tr class="dop_fields ukr novp kur">
	<td class="column-data">Улица</td>
	<td><input name="street" class=" form-control input" value="<?php echo $this->getOrder()->getStreet() ? $this->getOrder()->getStreet() : ""; ?>"></td>
</tr>
<tr class="dop_fields ukr kur novp">
	<td class="column-data">Дом</td>
	<td><input name="house" class="form-control input" value="<?php echo $this->getOrder()->getHouse() ? $this->getOrder()->getHouse() : ""; ?>"></td>
</tr>
<tr class="dop_fields novp">
	<td class="column-data">Квартира</td>
	<td><input name="flat" class=" form-control input" value="<?php echo $this->getOrder()->getFlat() ? $this->getOrder()->getFlat() : ""; ?>"></td>
</tr>
<tr class="dop_fields novp only_novp">
	<td class="column-data">Склад</td>
	<td><input name="sklad" class=" form-control input" value="<?php echo $this->getOrder()->getSklad() ? $this->getOrder()->getSklad() : ""; ?>"></td>
</tr>
<tr>
	<td class="column-data">Телефон <span class="red">*</span></td>
	<td><input name="telephone" class=" form-control input" value="<?php echo $this->getOrder()->getTelephone() ? $this->getOrder()->getTelephone() : ""; ?>"></td>
</tr>
<tr>
	<td class="column-data">E-mail <span class="red">*</span></td>
	<td><input name="email" class="form-control input" value="<?php echo $this->getOrder()->getEmail() ? $this->getOrder()->getEmail() : ""; ?>" type="email"></td>
</tr>

<tr>
	<td class="column-data">Комментарий</td>
	<td><br><?=trim($this->getOrder()->getComments()) ? '<span style="background: yellow;padding: 3px;">'.htmlspecialchars($this->getOrder()->getComments()).'</span>' : "отсутствует"?><br><br></td>
</tr>
<script>
	$(document).ready(function () {
	$('#savepage').on( "click", function () {
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
		//return false;
	} )
	
	function hide_dop_fields(item){
		$('.dop_fields').hide();
		$('.dop_fields span').html('');
		if (item == 0) {
			$('.dop_fields').hide();
		}
		if (item == 4) {
			$('.dop_fields').show();
			$('.ukr span').html('*');
		}

		if (item == 8) {
			$('.only_novp').show();
			$('.novp').show();
			$('.novp span').html('*');
		}
		if (item == 16) {
			$('.only_novp').show();
			$('.novp').show();
			$('.novp span').html('*');
		}

		if (item == 9 || item == 10) {
			$('.dop_fields').show();
			$('.kur span').html('*');
			$('.only_ukr').hide();
			$('.only_novp').hide();
		}
	}
	hide_dop_fields($('#delivery_type').val());
	
		$('#delivery_type').change(function () {

			var delivery = $(this).val();
			hide_dop_fields(delivery);


			var payment = $('#payment_method').val();
			if (delivery == 4) {
				$('.ukr>span').html('*');
			}
			else {
				$('.ukr>span').html('');
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
<?php  //$peresilka = Shoparticles::showPrice($this->getOrder()->getDeliveryCost()); ?>
<!--
<tr>
	<td class="column-data">Стоимость доставки</td>
	<td><?php /*echo $peresilka;*/ ?> грн</td>
</tr>
<tr>
	<td class="column-data">Акционная скидка</td>
	<td><?php /*echo $this->getOrder()->getEventSkidka(); ?> % <a href="/admin/editskidkabyorder/id/<?php echo $this->getOrder()->getId()*/ ?>">Изменить/Добавить скидки к товарам</a></td>
</tr>
<!--
<tr>
	<td colspan="2">
		<a href="/admin/user/edit/id/<?=$this->getOrder()->getCustomerId()?>"  target="_blank">Редактировать клиента</a>
	</td>
</tr>-->
<tr>
	<td colspan="2" style="    text-align: center;">
		<p id="save_done" style="display:none; color:red; font-size: 20px;margin: 0;padding-top: 8px;height: 45px;text-align: center;">Сохранено!</p>
		<div id="errormessage" style="display:none;"></div>
		<input type="button" class="btn  btn-lg btn-primary" name="savepage" id="savepage" value="Сохранить информацию о покупателе">
	</td>
</tr>
</table>
</form>
 </div>
 </div>


 
  <div class="panel panel-info" >
 <div class="panel-heading"><h3 class="panel-title">Товары</h3></div>
 <div class="panel-body">
<form method="post" action="">
<table cellpadding="4" cellspacing="0" id="order-articles" align="center">
<tr>
    <td colspan="2" class="kolomicon"><strong>Действие</strong></td>
    <td class="column-article"><strong>Кол./Товар</strong></td>
    <td class="column-article"><strong>Размер/Цвет</strong></td>
    <td><strong>Цена</strong></td>
    <td></td>
</tr>
    <?php $t_price = 0.00; $t_option = 0.00; if ($this->getOrder()->getArticles()->count()) { ?>
        <?php
        foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
            $article = new Shoparticles($article_rec->getArticleId());
            ?>
			<tr>
				<td colspan="9" style="padding: 0; border-top: 1px dotted #9c9e9f;"></td>
			</tr>
			<tr>
				<td class="kolomicon" colspan="2">
					<img class="prev" rel="#miesart<?= $article_rec->getId(); ?>"
						 src="<?php echo $article_rec->getImagePath('small_basket'); ?>"
						 alt="<?php echo htmlspecialchars($article_rec->getTitle()); ?>"
						 style="
							border: 1px solid #D8D8D8;
							box-shadow: 0px 0px 20px #BBB;
							margin: 20px;
							cursor: pointer; "/>

					<div class="simple_overlay" id="miesart<?= $article_rec->getId(); ?>" style="min-height: 300px; padding:10px 80px; position: absolute !important">
						<img src="<?php echo $article_rec->getImagePath('detail'); ?>" alt="<?php echo htmlspecialchars($article_rec->getTitle()); ?>"/>
					</div><br>
					<a href="/admin/shop-articles/edit/id/<?php echo $article->getId(); ?>" title="Редактировать">
						<img src="<?php echo SITE_URL; ?>/img/icons/edit-small.png" alt="Редактировать" width="24" height="24"/>
					</a>
					<a href="<?php echo $this->path; ?>shop-orders/adelete/id/<?php echo $article_rec->getId(); ?>/" onclick="return confirm('Удалить?');" title="Удалить">
						<img src="<?php echo SITE_URL; ?>/img/icons/remove-small.png" alt="Удалить" width="24" height="24"/>
					</a>
					<br>
					<a href="<?php echo $this->path; ?>shop-orders/adeletenoshop/id/<?php echo $article_rec->getId(); ?>/" onclick="return confirm('Удалить товар без возврата на склад? (товар не вернется на склад)');">Удалить без возврата</a>
				</td>

				<td class="column-article">
					<?php echo 'Количество: ' . $article_rec->getCount();?>
					<br><span style="color: #048;"><?php if (strlen($article_rec->getCode()) > 0) echo 'КОД: ' . $article_rec->getCode(); ?></span>
					<br><a href="<?php echo $article->getPath(); ?>" target="_blank"><?php echo $article_rec->getTitle(); ?></a>
					<br><span style="color: #777;"><?php echo $article_rec->article_db->category->getRoutez() ?></span>
					<?php if ($main_key == 0 && !(isset($options[$article_rec->getOptionId()]) && $article_rec->getOptionId() > 0)) echo ""; ?>
					<?php if (isset($options[$article_rec->getOptionId()]) && $article_rec->getOptionId() > 0) echo "<br />({$options[$article_rec->getOptionId()]->getOption()})"; ?>
					<br>
					<br>
					Наличие:
					<select name="count-<?= $article_rec->getId(); ?>" name="count" class="count">
						<?php
						//SQLLogger::getInstance()->reportBySQL()
						$cnt = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article_rec->getArticleId(), 'id_size' => $article_rec->getSize(), 'id_color' => $article_rec->getColor()));
						$cnt = $cnt ? $cnt->getCount() : 0;

						//$cnt = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article_rec->getArticleId(), 'id_size' => $article_rec->getSize(), 'id_color' => $article_rec->getColor()))->getCount();
						if ((int)$cnt > 0) {
							for ($i = 1; $i <= $cnt; $i++) echo ($i != $article_rec->getCount())
								? "<option value=\"{$i}\">{$i}</option>"
								: "<option value=\"{$i}\" selected=\"selected\">{$i}</option>";
						} else echo '<option value="0">Нет на складе</option>';?>
					</select><br>
					<label><input type="checkbox" name="edit_count-<?= $article_rec->getId(); ?>" class="chek_edit"> Изменить</label>
				</td>
				<td>
					<input type="hidden" class="hidden" value="<?= $article->getId() ?>">
					<?=wsActiveRecord::useStatic('Size')->findById($article_rec->getSize())->getSize();?>
					/<?=wsActiveRecord::useStatic('Shoparticlescolor')->findById($article_rec->getColor())->getName();?>
					<br/>
					<select name="size-<?= $article_rec->getId(); ?>" class="size">
						<option></option>
						<?php
						$mas = array();

						foreach ($article->getSizes() as $size) {
							if ($size->getCount() > 0)
								$mas [$size->getSize()->getId()] = $size->getSize()->getSize();
						}
						foreach (array_unique($mas) as $kay => $value) {
							if ($kay != $article_rec->getSize())
								echo '<option value="' . $kay . '">' . $value . '</option>';
							else
								echo '<option value="' . $kay . '" selected="selected">' . $value . '</option>';
						}
						?>
					</select>
					<select name="color-<?= $article_rec->getId(); ?>" class="color">
						<option></option>
						<?php
						$mas = array();

						foreach ($article->getSizes() as $color) {
							if ($color->getCount() > 0)
								$mas [$color->getColor()->getId()] = $color->getColor()->getName();
						}
						foreach (array_unique($mas) as $kay => $value) {
							if ($kay != $article_rec->getColor())
								echo '<option value="' . $kay . '">' . $value . '</option>';
							else
								echo '<option value="' . $kay . '" selected="selected">' . $value . '</option>';
						}
						?>
					</select>
				</td>
				<?php
					$price_real = (int)$article_rec->getOldPrice() ? $article_rec->getOldPrice() : $article_rec->getPrice();
					$price_show = $article_rec->getPrice() * (1 - ($article_rec->getEventSkidka() / 100)) * $article_rec->getCount(); $t_price += $price_show;
					$skid_show = round((1 - ($article_rec->getPrice() / $price_real)) * 100);
				?>
				<td>
					<?php if ($price_real != $article_rec->getPrice()){ ?>
						<span style="text-decoration:line-through"><?php echo $price_real; ?></span><br>
					<?php } ?>
					<?php	if (!$article_rec->getEventSkidka()) echo $skid_show ? '<span style="font-size:10px">Скидка</span> '.$skid_show.'% <br><br>' : '';
							else echo '<span style="font-size:10px">Скидка</span> '.$skid_show.'% +'.$article_rec->getEventSkidka().'% <br><br>'; ?>
					<span style="font-weight:bold"><?php echo Shoparticles::showPrice($price_show); ?> грн</span>
				</td>
				<td>
					<a href="/admin/articlehistory/id/<?php echo $article->getId(); ?>">История изменения товара</a><br><br>
					<span>Заказы по товару:</span>
					<?php if ($article->ArtycleBuyCount() == 0) { ?>
					<span>(Куплено: 0 шт.)</span>
					<?php } else { ?>
					<span>(</span><a href="/admin/ordersbyartycle/id/<?php echo $article->getId(); ?>">Куплено: <?php echo $article->ArtycleBuyCount() . ' шт.'; ?></a><span>)</span>
					<?php } ?>
				</td>
			</tr>
        <?php } ?>
    <?php } ?>
    <tr>
        <td colspan="2"></td>
        <td><input type="submit" class="button" name="edit" id="edit" value="Изменить"/></td>
        <td colspan="2"></td>
    </tr>

<script>
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

	function loadArticles(category_id) {
		var data_to_post = new Object();
		data_to_post.id = category_id;
		data_to_post.getarticles = '1';
		$.post('<?=$this->path."shop-orders/"?>', data_to_post, function (data) {
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
<tr>
    <td colspan="9" class="tussenrij" style="padding: 0;">&nbsp;</td>
</tr>
<tr>
	<td colspan="2"></td>
	<td colspan="7">
		<input name="add_article_by_barcode" class="input" placeholder="Добавить по штрихкоду" style="width: 170px;"/>
		<input type="submit" class="button" name="Toevoegen2" value="+"/>
	</td>
</tr>
<tr>
    <td colspan="9" class="tussenrij">&nbsp;</td>
</tr>
<tr>
    <td colspan="4"><strong>Всего</strong></td>
    <td colspan="2"><strong><?php echo Shoparticles::showPrice($t_price); ?> грн</strong></td>
</tr>
<tr>
    <td colspan="4"><strong>Доставка</strong></td>
    <td colspan="2"><strong><?php echo $peresilka; ?> грн</strong></td>
</tr>
<?php if (!($this->getOrder()->getKuponPrice() > 0)) { ?>
<tr>
    <td colspan="4"><strong>Скидка</strong></td>
    <td colspan="2"><strong><?php
            echo $order_owner->getDiscont($this->getOrder()->getId()); ?>%</strong></td>
</tr>
<?php } else { ?>
    <tr>
        <td colspan="4"><strong>Скидка по купону</strong></td>
        <td colspan="2"><strong><?php echo $this->getOrder()->getKuponPrice() ?> грн</strong></td>
    </tr>
<?php } ?>
<tr>
    <td colspan="4"><strong>Всего со скидкой и доставкой</strong></td>
    <td colspan="2"><strong><?php
            //$price_with_skidka = $this->getOrder()->getPriceWithSkidka();//ЕВЖЕНЯ
            echo $this->getOrder()->calculateOrderPrice(false);
            ?> грн</strong></td>
</tr>
<?php if ($this->getOrder()->getDeposit() > 0) { ?>
    <tr>
        <td colspan="4"><strong>Депозит</strong></td>
        <!-- <td class="column-euro"><strong></strong></td> -->
        <td colspan="2"><strong><?php echo $this->getOrder()->getDeposit() ?> грн</strong></td>
    </tr>
<?php } ?>

<tr>
    <td colspan="4"><strong>У пользователя на депозите</strong></td>
    <!-- <td class="column-euro"><strong></strong></td> -->
    <td colspan="4"><strong><?php

            echo $order_owner->getDeposit() ?> грн</strong><br/>
        <a href="/admin/usedeposit/id/<?php echo $this->getOrder()->getId(); ?>">Использовать депозит</a> <br/>
        <a href="/admin/unusedeposit/id/<?php echo $this->getOrder()->getId(); ?>"
           onclick="return confirm('При отмене депозита сума депозита вернется на счет клиента, а сумма заказа изменится. Продолжить ?')">Отменить депозит</a>
    </td>
</tr>
</tr>
</table>
</form>
</div>
</div>
<div class="panel panel-danger" >
 <div class="panel-heading"><h3 class="panel-title">Комментарий (внутренний)</h3></div>
 <div class="panel-body">
<table cellpadding="4" cellspacing="0" id="order-remarks" align="center">
    <?php foreach ($this->getOrder()->getRemarks() as $remark) { ?>
        <tr>
            <td class="column-data"><?php $d = new wsDate($remark->getDateCreate()); echo $d->getFormattedDate(); ?>  <?php echo $d->getHour() . '.' . $d->getMinute(); ?></td>
            <td><?php echo $remark->getRemark(); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td class="column-data">Добавить</td>
		<td>
			<form method="post" action="">
				<input type="hidden" name="add_remark" value="1"/>
				<textarea name="remark" class="text" id="remark"></textarea><br>
				<input type="submit" class="button" name="button" id="button" value="Добавить"/>
			</form>
		</td>
    </tr>
</table>
</div>
</div>

	<?php if ($this->getOrder()->getStatus()!=2){ ?>
	<div class="panel panel-success" >
 <div class="panel-heading"><h3 class="panel-title">Действия</h3></div>
 <div class="panel-body">
	<form method="post" action="" style="text-align: center; padding-top:20px;">
	<input type="submit" name="converting_to_order" class="buttonps" style="font-size: 16px;" value="В заказы">
	<input type="submit" name="delete_qo" class="buttonps" style="font-size: 16px;background:#E00;" value="Отмена">
	</form>
	 </div>
 </div>
	<?php } ?>

 </div>
 </div>
 </div>