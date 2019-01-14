<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt=""  class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle();?> </h1>
<?=$this->getCurMenu()->getPageBody(); ?>

<?php
if ($this->errors) {
    ?>
<div id="errormessage"><img src="<?=SITE_URL?>/img/icons/error.png" alt="" class="page-img"/>
    <h2>Ошибка:</h2>
    <ul>
        <?php
        foreach ($this->errors as $error) {
            ?>
            <li><?=$error?></li>
            <?php

        }
        ?>
    </ul>
</div>
<?php
}
if ($this->saved) { ?>
<div id="pagesaved">
    <img src="<?=SITE_URL?>/img/icons/accept.png" alt="" class="page-img"/>
    <h2>Запись сохранена.</h2>
</div>
<?php } ?>

<form method="POST" action="<?=$this->path?>user/edit/id/<?=$this->sub->getId();?>/" id="userform" enctype="multipart/form-data">
    <input type="hidden" value="<?=$this->sub->getId();?>" name="id" id="id">
    <table id="editpage" cellpadding="5" cellspacing="0" class="table">
	<?php if($this->sub->getEmailOk() == 0){ echo '<tr id="ok"><td class="kolom1" style="color: red;">Email не изменялся!</td><td><input class="input" style="cursor: pointer;" type="button" name="go_mail" id="go_mail" value="Email для подтверждения"></td></tr>';} else if($this->sub->getEmailOk() == 2){echo '<tr id="ok"><td class="kolom1" style="color: red;">Email ('.$this->sub->getTempEmail().') не подтвержден!</td><td><input class="input" style="cursor: pointer;" type="button" name="go_mail" id="go_mail" value="Email для подтверждения"></td></tr>';}?>
        <?php if ($this->user->isSuperAdmin() and $this->sub->getCustomerTypeId() < 2) { ?>
        <tr>
            <td class="kolom1"></td>
            <td><a href="/admin/adminedit/edit/<?php echo (int)$this->sub->getId();?>?new_admin=1"
                   onclick="return confirm('Внимание, при переходе по этой ссылке пользователь станет администратором.')">Зделать
                администратором</a></td>
        </tr>
        <?php } ?>
        <tr>
            <td class="kolom1">Номер клиента</td>
            <td><?php echo $this->sub->getId();?></td>
        </tr>
        <tr>
            <td class="kolom1">Логин</td>
            <td><?php echo $this->sub->getUsername();?></td>
        </tr>
        <tr>
            <td class="kolom1">Имя</td>
            <td><input name="first_name" type="text" class="form-control input" id="paginatitle"
                       value="<?php echo $this->sub->getFirstName();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Фамилия</td>
            <td><input name="middle_name" class="form-control input"
                       value="<?php echo $this->sub->getMiddleName();?>"/></td>
        </tr>
		  <tr>
            <td class="kolom1">Дата рождения</td>
            <td><input name="date_birth" class="form-control input"
                       value="<?php echo $this->sub->getDateBirth();?>"/>гггг-мм-дд</td>
        </tr>
		<tr>
            <td class="kolom1">Аватарка</td>
            <td><input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
			<input name="logo" type="file" class="form-control input" /> Размер 1Х1 не более 1мб.
			<?php if ($this->sub->getLogo()) { echo '<img style="max-width:50px;" src="' . $this->sub->getLogo() . '" />'; } ?>
			</td>
        </tr>
        <tr>
            <td class="kolom1">E-mail</td>
            <td><input name="email" id="email" class="form-control input" value="<?php echo $this->sub->getEmail();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Телефон</td>
            <td><input name="phone1" class="form-control input" value="<?php echo $this->sub->getPhone1();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Адрес</td>
            <td><input name="adress" class="form-control " value="<?php echo $this->sub->getAdress();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Индекс</td>
            <td><input name="index" class="form-control input" value="<?php echo $this->sub->getIndex();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Область</td>
            <td><input name="obl" class="form-control input" value="<?php echo $this->sub->getObl();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Район</td>
            <td><input name="rayon" class="form-control input" value="<?php echo $this->sub->getRayon();?>"/></td>
        </tr>
		 <tr>
            <td class="kolom1">Город</td>
            <td><input name="city" class="form-control input" value="<?php echo $this->sub->getCity();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Улица</td>
            <td><input name="street" class="form-control input" value="<?php echo $this->sub->getStreet();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Дом</td>
            <td><input name="house" class="form-control input" value="<?php echo $this->sub->getHouse();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Квартира</td>
            <td><input name="flat" class="form-control input" value="<?php echo $this->sub->getFlat();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Компания</td>
            <td><input name="company_name" class="form-control input"
                       value="<?php echo $this->sub->getCompanyName();?>"/></td>
        </tr>

        <tr>
            <td class="kolom1">Скидка по покупкам</td>
            <td><?php echo $this->sub->getDiscont(false, 0, true);?>%</td>
        </tr>
        <tr>
            <td class="kolom1">Скидка по акциям</td>
            <td><?php echo (int)EventCustomer::getEventsDiscont($this->sub->getId());?>%</td>
        </tr>
        <tr>
            <td class="kolom1">Участие в акциях</td>
            <td>
                <a href="#" onclick="$('.view_event_history').slideToggle(); return false;">Показать\Скрыть</a>

                <div class="view_event_history" style="display: none;">
                    <?php $user_event = wsActiveRecord::useStatic('EventCustomer')->findAll(array('customer_id' => $this->sub->getId()), array('ctime' => 'DESC'));
                    foreach ($user_event as $uevent) {
                        $event = new Event($uevent->getEventId());
                        ?>
                        <p>Акция <a
                                href="/admin/event/id/<?php  echo $event->getId();?>">"<?php echo $event->getName()?>
                            "</a> скидка <?php echo $event->getDiscont()?>%.
                            Активация <?php echo date('d-m-Y', strtotime($uevent->getCtime()))?>.
                            Статус <?php echo $event->getPublick() ? '<b>Активна</b>' : 'Остановлена';?></p>
                        <?php } ?>
                </div>
            </td>
        </tr>

        <tr>
            <td class="kolom1">Скидка VIP</td>
            <td><input name="skidka" class="form-control input" value="<?php echo $this->sub->getSkidka();?>"/>%</td>
        </tr>

        <tr>
            <td class="kolom1">Депозит</td>
            <td><input name="deposit" style="width:100px;" class="form-control input" value="<?php echo $this->sub->getDeposit();?>"/><input type="checkbox" id="deposit_email" name="deposit_email" value="1"><label>Не уведомлять пользователя</label></td>
        </tr>
        <tr>
            <td class="kolom1">Колл. заказов на пункт выдачи: </td>
            <td><input name="count_order_magaz" style="width:100px;" type="text" class="form-control input" value="<?=$this->sub->count_order_magaz?>"/></td>
        </tr>
		<?php if($this->sub->getBonus()){ ?>
		<tr>
            <td class="kolom1">Бонус</td>
            <td><?php echo $this->sub->getBonus();?> грн.</td>
        </tr>
		<?php } ?>
        <tr>
            <td class="kolom1">Статус</td>
            <td>
                <input type="radio" name="customer_status_id" id='activ'
                       value="1" <?php if ($this->sub->getCustomerStatusId() != 2) { ?>checked="checked"<?php } ?>>
                <label for="activ">Активный</label>
                <input type="radio" name="customer_status_id" id='ban'
                       value="2" <?php if ($this->sub->getCustomerStatusId() == 2) { ?>checked="checked"<?php } ?>>
                <label for="activ">Бан</label>
                <br/>
                За что Бан: <input type="text" name="ban_comment" value="<?php echo $this->sub->getBanComment();?>" /><br/>
                Последний бан <?php echo date('d-m-Y H:i:s',strtotime($this->sub->getBanDate()));?> установил
                <?php
                $customer_ban = new Customer($this->sub->getBanAdmin());
                if($customer_ban){
                    echo $customer_ban->getUsername();
                }
                ?>
            </td>
        </tr>
		<tr>
		<td class="kolom1">Блок наложка</td>
		<td>
		<input type="checkbox" name="bloсk_np_n" id='bloсk_np_n'
                       value="1" <?php if ($this->sub->bloсk_np_n == 1) { ?>checked="checked"<?php } ?>>
		</td>
		</tr>
		<tr>
		<td class="kolom1">Блок Курьером</td>
		<td>
		<input type="checkbox" name="block_cur" id='block_cur'
                       value="1" <?php if ($this->sub->block_cur == 1) { ?>checked="checked"<?php } ?>>
		</td>
		</tr>
		<tr>
		<td class="kolom1">Блок Самовывоз</td>
		<td>
		<input type="checkbox" name="block_m" id='block_m'
                       value="1" <?php if ($this->sub->block_m == 1) { ?>checked="checked"<?php } ?>>
		</td>
		</tr>
		<tr>
		<td class="kolom1">Блок быстрой заявки</td>
		<td>
		<input type="checkbox" name="block_quick" id='block_quick'
                       value="1" <?php if ($this->sub->block_quick == 1) { ?>checked="checked"<?php } ?>>
		</td>
		</tr>
        <tr>
            <td class="kolom1">Сброс пароля</td>
            <td>
                <?php if (isset($_GET['rpass'])) {
                if (@$_GET['rpass'] == 'email') echo 'Новый пароль отправлен по Email';
                elseif (@$_GET['rpass'] == 'sms') echo 'Новый пароль отправлен по Sms'; else 'Ошибка';
            } else {
                ?>
                <a href="&resetpass=email">По Email</a> <br/>
                <a href="&resetpass=sms">По SMS</a>
                <?php } ?><br />
                <?php if(!$this->save_pass_p){?>
                Установить пароль принудительно:<br />
                Пароль: <input type="text" value="" name="pass_p" />
                <?php } else {?>
                    Новый пароль установлен принудительно.
                <?php } ?>
            </td>
        </tr>
        <tr>
        <tr>
            <td class="kolom1">Коментарии админа</td>
            <td>
                <textarea rows="5" cols="25" name="admin_coments"><?php echo $this->sub->getAdminComents();?></textarea>
            </td>
        </tr>
        <tr>
            <td class="kolom1">&nbsp;</td>
            <td><input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/></td>
        </tr>
    </table>
</form>

<?php  //$orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('customer_id' => $this->sub->getId()));
$orders = wsActiveRecord::useStatic('Shoporders')->findByQuery('SELECT * FROM ws_orders WHERE customer_id ='.$this->sub->getId().' ORDER BY `ws_orders`.`id` DESC LIMIT 10');
if ($orders->count() > 0) {
    ?>

<p> Оплатить заказы: </p>
<input type="input" class="pay_orders_sum" name="pay_orders_sum" value="0"/> грн.
<input type="button" disabled="disabled" class="pay_pay" value="Оплатить"/> <br/>
<p>
    Заказы на сумму: <span class="all_orders_sum">0</span> грн.<br/>
    На депозит: <span class="to_deposit_sum">0</span> грн.<br/>
</p>
<p>Заказы:</p>
<script>
    $(document).ready(function () {

        $('.user_orders').click(function () {
            $('.pay_pay').attr('disabled', true);


            if ($(this).attr('checked')) {
                if ($(this).hasClass('oplach')) {
                    if (!confirm('Заказ уже оплачкен. Продолжить ?')) return false;
                }
            }
            sum = 0;
            jQuery.each($('.user_orders:checked'), function () {
                sum = parseFloat(sum) + parseFloat($(this).parent().find('.real_sum').val());
            });
            $('.all_orders_sum').html(sum.toFixed(2));
            depoz = parseFloat($('.pay_orders_sum').val()) - parseFloat(sum.toFixed(2));
            $('.to_deposit_sum').html(depoz.toFixed(2));
            if ($('.to_deposit_sum').html() >= 0) {
                $('.pay_pay').attr('disabled', false);
            }

        });
        $('.pay_orders_sum').keyup(function () {
            $('.pay_pay').attr('disabled', true);
            depoz = parseFloat($('.pay_orders_sum').val()) - parseFloat($('.all_orders_sum').html());
            $('.to_deposit_sum').html(depoz.toFixed(2));
            if ($('.user_orders:checked').val()) {
                if ($('.to_deposit_sum').html() >= 0) {
                    $('.pay_pay').attr('disabled', false);
                }
            }
        });
        $('.pay_pay').click(function () {
            if ($('.user_orders:checked').val()) {
                if (parseFloat($('.pay_orders_sum').val()) > 0) {
                    ids = '';
                    i = 0;
                    jQuery.each($('.user_orders:checked'), function () {
                        if (i != 0) {
                            ids += ',' + $(this).attr('name').substr(10);
                        } else {
                            ids += $(this).attr('name').substr(10);
                        }
                        i++;
                    });
                    window.location = '/admin/payorder/?ids=' + ids + '&pay=' + parseFloat($('.pay_orders_sum').val()) + '&customer=' +<?php echo $this->sub->getId();?>;
                } else {
                    alert('Введите сумму');
                }
            } else {
                alert('Выберите заказы');
            }
        });

    });
</script>

<table cellspacing="0" cellpadding="4" id="orders" class="table">
    <tr>
        <th></th>
        <th>Действие</th>
        <th>Статус</th>
        <th>Номер</th>
        <th>Дата</th>
        <th>Товаров</th>
        <th>Стоимость</th>
        <th>Счет</th>
        <th>Магазин</th>
    </tr>
    <?php $row = 'row2'; foreach ($orders as $order) {
    $row = ($row == 'row2') ? 'row1' : 'row2';
    ?>
    <tr class="<?php echo $row; ?>">
        <td>
            <?php if ($order->getStatus() != 2) { ?>
            <input type="checkbox" name="pay_order_<?=$order->getId();?>"  class="user_orders <?php if ($order->getStatus() == 8) echo 'oplach'; ?>"/>
            <?php } ?>
<input type="hidden" class="real_sum" value="<?php echo (float)$order->getPriceWithSkidka() + (float)$order->getDeliveryCost() - $order->getDeposit(); ?>" />
        </td>
        <td>
            <a href="/admin/shop-orders/edit/id/<?=$order->getId();?>"><img width="24" height="24"
                                                                                     alt="Редактировать"
                                                                                     src="/img/icons/edit-small.png"></a>
        </td>
        <td><?=$order->getStat()->getName()?>
            <?php if ($order->getAdminPayId()) { ?>
                <br/><span style="color: #6666ff">Проведен</span>
                <?php } ?>
        </td>
        <td><?=$order->getId();?></td>
        <td><?php $d = new wsDate($order->getDateCreate()); echo $d->getFormattedDateTime(); ?></td>
        <td><?php $count_o = $order->countArticles(); echo $count_o; ?></td>
        <td><?php if ($count_o != 0) {
            echo number_format((double)$order->getTotal('a'), 2, ',', '') . ' грн <br/>' . Number::formatFloat(((float)$order->getPriceWithSkidka() + (float)$order->getDeliveryCost() - (float)$order->getDeposit()), 2) . ' грн';
        } ?></td>
        <td>
            <a target="_blank" href="/admin/generateorder/id/<?php echo $order->getId();?>/type/1">Магазин</a><br/>
            <a target="_blank" href="/admin/generateorder/id/<?php echo $order->getId();?>/type/2">Укрпочта</a><br/>
        </td>
        <td>
            <?php echo $order->getDeliveryTypeId() ? $order->getDeliveryType()->getName() : "&nbsp;"; ?><br/>
            <?php if ($order->getNakladna()) { ?>
            Накладная №: <?php echo $order->getNakladna(); ?>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
</table>

<?php } ?>
	<script type="text/javascript">
    $(document).ready(function () {
	$('#go_mail').on( "click", function () {
	//alert($('#email').val());
	var id = $('#id').val();
		$.ajax({
			beforeSend: function( data ) {
				$('#go_mail').attr('value', 'Email отправляется...');
			},
			type: "POST",
			url: '/admin/user/',
			data: '&id='+id+'&method=emailgo',
			success: function( data ) {
				console.log(data);
			},
			dataType: 'json',
			complete: function( data ) {
			
				$('#go_mail').attr('value', 'Email отправлен!');
				$('#ok').fadeOut(2000);
			},
			error: function( e ) {
			alert('Вы ввели что-то не верно! ТТН не создана, внесите изменения и попробуйте снова!');
			}
		});
	
	
	});
	
	});
	</script>