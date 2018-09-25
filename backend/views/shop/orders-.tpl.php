<img src="<?php echo SITE_URL; ?><?php echo $this->getCurMenu()->getImage(); ?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1><br/>
<table style="width: 100%;">
	<tr>
		<td style="padding:0;">
<form action="/admin/shop-orders/" method="get">
	<style type="text/css">
	#search {border: 1px solid #DDD;}
	#search td {vertical-align: middle; padding: 1px 10px; font-size: 16px;}
	#search tr:nth-child(even) { background: #F8F8F8; }
	label {cursor: pointer;}
	.comm_ins {background: #C0FFD4; border: 1px solid #000; padding: 5px; margin: 5px;}
	.comm_cli {background: #ffff33; border: 1px solid #000; padding: 5px; margin: 5px;max-width: 208px;word-wrap: break-word;}
	</style>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.datepic').datepicker();
        });
    </script>
<table border="0" bordercolor="#ABADB3" style="background-color:#EEE; border-collapse: collapse; width: 390px; margin: 0 auto;" width="100%" cellpadding="3" cellspacing="0" id="search">
	<tr>
		<td colspan="2" align="center"><strong>Поиск:</strong></td>
	</tr>
	<tr>
		<td>Номер заказа :</td>
		<td><input type="text" value="<?php echo @$_GET['order'] ?>" name="order"/></td>
	</tr>
	<tr>
		<td>Телефон:</td>
		<td><input type="text" value="<?php echo @$_GET['phone'] ?>" name="phone"/></td>
	</tr>
	<tr>
		<td>Email:</td>
		<td><input type="text" value="<?php echo @$_GET['email'] ?>" name="email"/></td>
	</tr>
	<tr>
		<td>Имя:</td>
		<td><input type="text" value="<?php echo @$_GET['uname'] ?>" name="uname"/></td>
	</tr>
	<tr>
		<td>Магазин:</td>
		<td>
			<select name="delivery">
				<option value="">Все</option>
				<option
					value="1" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '1') echo 'selected="selected"';?>>ул.
					Телиги
				</option>
				<option
					value="2" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '2') echo 'selected="selected"';?>>ул.
					Фрунзе
				</option>
				<option
					value="3" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '3') echo 'selected="selected"';?>>пр.
					Победы
				</option>
				<option
					value="7" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '7') echo 'selected="selected"';?>>ул.
					Драйзера
				</option>
				<option
					value="11" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '11') echo 'selected="selected"';?>>
					пр.
					Правды
				</option>
				<option
					value="12" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '12') echo 'selected="selected"';?>>
					ул. Мишуги
				</option>
				<option
					value="13" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '13') echo 'selected="selected"';?>>
					пр. Героев Сталинграда 46
				</option>

                <option
                					value="15" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '15') echo 'selected="selected"';?>>
                    г. Борисполь
                				</option>
				<option
					value="4" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '4') echo 'selected="selected"';?>>
					Укрпочта
				</option>
				<option
					value="8" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '8') echo 'selected="selected"';?>>
					Новая почта
				</option>
				<option
					value="999" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '999') echo 'selected="selected"';?>>
					Магазины
				</option>
				<option
					value="9" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '9') echo 'selected="selected"';?>>
					Курьером по Киеву
				</option>
				<option
					value="10" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '10') echo 'selected="selected"';?>>
					Курьером по Украине
				</option>
				<option
					value="111" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '111') echo 'selected="selected"';?>>
					Курьером
				</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Статус:</td>
		<td>
			<select name="status" style="width:230px;">
				<option value="999">Все</option>
				<option value="0" <?php if (isset($_GET['status']) and $_GET['status'] == '0') echo 'selected="selected"';?>>
					Новый
				</option>
				<option value="1" <?php if (isset($_GET['status']) and $_GET['status'] == '1') echo 'selected="selected"';?>>В
					процессе
				</option>
				<option value="2" <?php if (isset($_GET['status']) and $_GET['status'] == '2') echo 'selected="selected"';?>>
					Отменён
				</option>
				<option value="3" <?php if (isset($_GET['status']) and $_GET['status'] == '3') echo 'selected="selected"';?>>
					Доставлен в магазин
				</option>
				<option value="4" <?php if (isset($_GET['status']) and $_GET['status'] == '4') echo 'selected="selected"';?>>
					Отправлен укрпочтой
				</option>
				<option value="5" <?php if (isset($_GET['status']) and $_GET['status'] == '5') echo 'selected="selected"';?>>
					Срок
					хранения заказа в магазине закончился
				</option>
				<option value="6" <?php if (isset($_GET['status']) and $_GET['status'] == '6') echo 'selected="selected"';?>>
					Отправлен Новая почта
				</option>
				<option value="7" <?php if (isset($_GET['status']) and $_GET['status'] == '7') echo 'selected="selected"';?>>
					Возврат
				</option>
				<option value="8" <?php if (isset($_GET['status']) and $_GET['status'] == '8') echo 'selected="selected"';?>>
					Оплачен
				</option>
				<option value="9" <?php if (isset($_GET['status']) and $_GET['status'] == '9') echo 'selected="selected"';?>>
					Собран
				</option>
				<option value="10" <?php if (isset($_GET['status']) and $_GET['status'] == '10') echo 'selected="selected"';?>>
					Продлён клиентом
				</option>
				<option value="11" <?php if (isset($_GET['status']) and $_GET['status'] == '11') echo 'selected="selected"';?>>
					Ждёт
					оплаты
				</option>
				<option value="12" <?php if (isset($_GET['status']) and $_GET['status'] == '12') echo 'selected="selected"';?>>
					Ждет
					возврат 
				</option>
				<option value="13" <?php if (isset($_GET['status']) and $_GET['status'] == '13') echo 'selected="selected"';?>>
					В
					процессе доставки
				</option>
				<option value="14" <?php if (isset($_GET['status']) and $_GET['status'] == '14') echo 'selected="selected"';?>>
					Оплачен депозитом
				</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Дата создания</td>
		<td>от <input type="text" value="<?php echo @$_GET['create_from'] ?>" class="datepic" name="create_from" size="9" />
		до <input type="text" value="<?php echo @$_GET['create_to'] ?>" class="datepic" name="create_to" size="9" /></td>
	</tr>
	<tr>
		<td>Дата отправки</td>
		<td>от <input type="text" value="<?php echo @$_GET['go_from'] ?>" class="datepic" name="go_from" size="9" />
		до <input type="text" value="<?php echo @$_GET['go_to'] ?>" class="datepic" name="go_to" size="9" /></td>
	</tr>
	<tr>
		<td>Цена:</td>
		<td><input type="text" value="<?php echo @$_GET['price'] ?>" name="price"/> +- 3 грн.</td>
	</tr>
	<tr>
		<td>Накладная:</td>
		<td><input type="text" value="<?php echo @$_GET['nakladna'] ?>" name="nakladna"/></td>
	</tr>
	<tr>
		<td colspan="2"><label><input type="checkbox" name="detail" value="1" <?php if (@$_GET['detail'] == 1) { ?>checked="checked" <?php } ?>> Уточнить детали </label></td>
	</tr>
	<tr>
		<td colspan="2"><label><input type="checkbox" name="is_admin" value="1" <?php if (@$_GET['is_admin'] == 1) { ?>checked="checked" <?php } ?>> Заказы администрации </label></td>
	</tr>
	<tr>
		<td colspan="2"><label><input type="checkbox" name="kupon" value="1" <?php if (@$_GET['kupon'] == 1) { ?>checked="checked" <?php } ?>> Заказы с купоном</label> <a href="http://pokupon.ua/merchant">Проверка купонов</a></td>
	</tr>
	<tr>
		<td colspan="2"><label><input type="checkbox" name="quick_order" value="1" <?php if (@$_GET['quick_order'] == 1) { ?>checked="checked" <?php } ?>> Заказы из заявок</label></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" value="Найти" style="padding: 5px 100px; cursor: pointer;" /></td>
	</tr>
</table>
</form>
</td>

<td style="padding: 0 0 0 20px;">
<div style="padding: 3px 10px 0px 10px; border: 1px solid #DDD; background: #fff;">
<form action="/admin/ordersexcel/" method="get">
    Експорт в ексель: № заказа с <input type="text" value="0" name="min" size="5" /> по <input type="text" value="0" name="max" size="5"/>
	<input type="submit" value="Получить"/>
</form>
<div>
    Массовое изменение статуса:
    <select name="order_status_all" id='order_status_all' style="width:200px;">
        <option></option>
        <?php foreach ($this->order_status as $key => $item) {
            if ($key != 6) {
                ?>
                <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
            <?php
            }
        } ?>
    </select>
    <input type="button" id='all_status' value="Изменить"/>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#all_status').click(function () {
            if ($('#order_status_all option:selected').val() != '') {
                if ($('.order-item:checked').val()) {
                    id = '';
                    i = 0;
                    jQuery.each($('.order-item:checked'), function () {
                        if (i != 0) {
                            id += ',' + $(this).attr('name').substr(5);
                        } else {
                            id += $(this).attr('name').substr(5);
                        }
                        i++;
                    });
                    window.location = '/admin/allstatus/id/' + id + '/status/' + $('#order_status_all option:selected').val();

                }
            }
        });
    });
</script>
<p><input type="button" id='all_articles' value="Все товары в заказах"/>
<script type="text/javascript">
    $(document).ready(function () {
        $('#all_articles').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
                window.location = '/admin/allarticles/id/' + id;

            }
        });
    });
</script>
<input type="button" id='all_articles_excel' value="Экспорт товаров в заказах"/>
<script type="text/javascript">
    $(document).ready(function () {
        $('#all_articles_excel').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
                window.location = '/admin/allarticlesExcel/id/' + id;

            }
        });
    });
</script>
</p>

<p style="border:1px #999 dashed; padding: 10px 0;"><input type="button" id='nowa_pochta_exel' value="Excel список выделеных заказов"/>
<script type="text/javascript">
    $(document).ready(function () {
        $('#nowa_pochta_exel').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
                window.location = '/admin/nowapochtaexel/id/' + id;

            }
        });
    });
</script>
<input type="button" id='buhgalter_exel' value="Excel для бухгалтера"/>
<script type="text/javascript">
    $(document).ready(function () {
        $('#buhgalter_exel').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
                window.location = '/admin/exelfrombuhgalter/id/' + id;

            }
        });
    });
</script>
<input type="button" id='kurer_exel' value="Excel для курьерских заказов"/>
<script type="text/javascript">
    $(document).ready(function () {
        $('#kurer_exel').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
                window.location = '/admin/exelkurer/id/' + id;

            }
        });
    });
</script>
<input type="button" id='article_exel' value="Excel список артикулов в заказах"/> - выберите заказы
<script type="text/javascript">
    $(document).ready(function () {
        $('#article_exel').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
                window.location = '/admin/exeltoarticles/id/' + id;

            }
        });
    });
</script>
</p>

<p>
    <input type="text" class="datetime" id="order_date" value="<?php echo date('d.m.Y'); ?>" size="9" />
    <input type="button" id='order_nachal' value="Excel список заказов с начальной стоимостю"/> - выберите дату</p>
<script type="text/javascript">
    $(document).ready(function () {
        $('.datetime').datepicker();
        $('#order_nachal').click(function () {
            window.location = '/admin/otchets/type/3/?day=' + $('#order_date').val();
        });
    });
</script>
<p><input type="button" id='ordercomplect' value="Совместить заказы"/> - выберите заказы</p>
<script type="text/javascript">
    function NowaMail(id, object) {
        $.post('/admin/nowamail/', {id: id}, function (data) {
            object.parent().html(data);
        });
    }
    $(document).ready(function () {
        $('#ordercomplect').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
                window.location = '/admin/ordercomplect/id/' + id;

            }
        });
    });
</script>
<p>
    <select name="masrintordertype" id="masrintordertype">
        <option value="1">Магазин</option>
        <option value="2">Укр-почта</option>
        <option value="3">Новая почта</option>
        <option value="4">Курьером</option>
    </select>
    <input type="button" id='masrintorder' value="Масcовая печать счетов"/>
</p>
<script type="text/javascript">

    $(document).ready(function () {
        $('#masrintorder').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
				window.open ( '/admin/masgenerateorder/ids/' + id + '/type/' + $('#masrintordertype').val(), '_blank');

            }
        });
    });
</script>

<p>
    <select name="masrintnakltype" id="masrintnakltype">
        <option value="1">Магазин</option>
        <option value="2">Укр-почта</option>
        <option value="3">Новая почта</option>
        <option value="4">Курьером</option>
    </select>
    <input type="button" id='masrintnakl' value="Масcовая печать наклеек"/>
</p>
<script type="text/javascript">

    $(document).ready(function () {
        $('#masrintnakl').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
				window.open ( '/admin/masgeneratenakl/ids/' + id + '/type/' + $('#masrintnakltype').val(), '_blank');

            }
        });
    });
</script>

<p>    <input type="button" id='masrintblank' value="Масcовая печать бланков"/>
</p>
<p>
    <input type="button" id='masrintchek' value="Масcовая печать чеков"/>
    <script type="text/javascript">

        $(document).ready(function () {
            $('#masrintchek').click(function () {
                if ($('.order-item:checked').val()) {
                    id = '';
                    i = 0;
                    jQuery.each($('.order-item:checked'), function () {
                        if (i != 0) {
                            id += ',' + $(this).attr('name').substr(5);
                        } else {
                            id += $(this).attr('name').substr(5);
                        }
                        i++;
                    });
    				window.open ( '/admin/masgeneratechek/ids/' + id , '_blank');

                }
            });
        });
    </script>
</p>
</div>
</td>
</tr>
</table>
<script type="text/javascript">

    $(document).ready(function () {
        $('#masrintblank').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
                window.open ( '/admin/masgenerateblank/ids/' + id , '_blank');

            }
        });
    });
</script>
<br/>
<a href="/admin/shop-orders/edit/id/"><img src="<?php echo SITE_URL; ?><?php echo $this->getCurMenu()->getImage(); ?>"
                                           alt="" width="32" class="page-img" height="32"/>Новый заказ</a>
<br/>
<br/>

<?php if ($this->getOrders()->count()) { ?>
    <script type="text/javascript">
        var clik_ok = 0;
        function chekAll() {
            if (!clik_ok) {
                $('.cheker').attr('checked', true);
                clik_ok = 1;
            } else {
                $('.cheker').attr('checked', false);
                clik_ok = 0;
            }

            return false;
        }
    </script>

    <a href="#" onclick="chekAll(); return false;">Отметить/Снять все</a>
    <table cellspacing="0" cellpadding="4" id="orders">
        <tr>
            <th colspan="2">Действия</th>
            <th>Статус</th>
            <th>Номер</th>
            <th>Дата</th>
            <th>Имя</th>
            <th>Товаров</th>
            <th>Стоимость</th>
            <!-- <th>Счет</th>-->
            <th>Магазин</th>
            <th>Скидка</th>
            <th>Изменить</th>
            <th>Согласие</th>
        </tr>
        <?php $row = 'row2'; foreach ($this->getOrders() as $order) {
            $row = ($row == 'row2') ? 'row1' : 'row2';
            $order_owner = new Customer($order->getCustomerId());

            ?>
            <tr class="<?php echo $row; ?>"
                <?php
				if ($order_owner->getAdminComents()) echo 'style="background: #ff6666;" ';
                elseif($order->getDeposit() > 0) echo 'style="background: #90EE90;" ';
				else {
                if ($order->getNowaMail() != '0000-00-00 00:00:00' and $order->getDeliveryTypeId() == 8 and strtotime($order->getNowaMail()) <= mktime(0, 0, 0, date("m"), date("d") - 5, date("Y")) and false) echo "style='background: #ff9900'"; elseif ($order->getStatus() == 11 and $order->getDelayToPay() and $order->getDelayToPay() != '0000-00-00' and strtotime($order->getDelayToPay()) <= mktime(0, 0, 0, date("m"), date("d") - 5, date("Y"))) echo "style='background: #ff9900'";
            } ?>>
                <td>
                    <input type="checkbox" class="order-item cheker" name="item_<?php echo $order->getId(); ?>"/>
                </td>
                <td class="kolomicon"><a
                        href="<?php echo $this->path; ?>shop-orders/edit/id/<?php echo $order->getId(); ?>/"><img
                            src="<?php echo SITE_URL; ?>/img/icons/edit-small.png" alt="Редактировать" width="24"
                            height="24"/></a>
                    <?php if ($this->user->isSuperAdmin()) { ?>
                        <a target="_blank" href="/admin/orderhistory/id/<?php echo $order->getId(); ?>">
                            <img height="24" width="24" alt="История" src="/img/icons/histori.png"></a>
                    <?php } ?>
                </td>
                <td><?php echo isset($this->order_status[$order->getStatus()]) ? $this->order_status[$order->getStatus()]
                        : ""; ?>
                    <?php if ($order->getComlpect()) { ?>
                        Совмещенный заказ
                    <?php } ?>
                    <?php if ($order->getCallMy() == 1) { ?>
                        <b>Уточнить детали</b>
                    <?php } ?>
                    <?php if ($order->getCallMy() == 2) { ?>
                        <b>Нет необходимости подтверждать заказ по телефону</b>
                    <?php } ?>
                </td>
                <td><?=$order->getId();?></td>
                <td><?php $d = new wsDate($order->getDateCreate()); echo $d->getFormattedDateTime(); ?></td>
                <td><?php echo $order->getName() . ' ' . $order->getMiddleName(); ?></td>
                <td><?php echo $order->getArticlesCount(); ?></td>
                <td><?php if ($order->getArticlesCount() != 0) {
                        $sttt = '';
                        $sttt2 = '';
                        if ($order->isUcenArticle()) {
                            $sttt = '<span style="color:#a51515">';
                            $sttt2 = '</span>';
                        }
                        $price_1 = number_format((double)$order->getTotal('a'), 2, ',', '');
                        $price_2 = $order->getAmount();
                        echo  $price_1 . ' грн <br/>' . $sttt . $price_2 . ' грн' . $sttt2;
                    } ?></td>

                <td><?php echo $order->getDeliveryTypeId() ? $order->getDeliveryType()->getName() : "&nbsp;"; ?></td>
                <td><?php  if ($order->getSkidka() != '') {
                        echo $order->getSkidka();
                    } else {
                        $order->save();
                        echo $order->getSkidka();
                    } ?>%
                </td>
                <td>
                    <?php if ($order->getBoxNumber()) { ?>
                        Номер ячейки: <?php echo $order->getBoxNumber(); ?>
                    <?php } ?>
                    <form action="/admin/shop-orders/edit/id/<?= $order->getId() ?>/" method="post">
                        Накладная: №<input type="text" name="nakladna" value="<?php echo $order->getNakladna(); ?>"/>
                        <br/>
                        <?php if ($order->getDeliveryTypeId() == 8) {
                            if ($order->getNowaMail() == '0000-00-00 00:00:00') {
                                ?>
                                <span> <a href="#"
                                          onclick="NowaMail(<?php echo $order->getId(); ?>,$(this)); return false;"
                                          class="nowa_mail">отправить счет</a></span>
                            <?php } else { ?>
                                <span> счет отправлен: <?php echo date('d-m-Y', strtotime($order->getNowaMail()));?>
                                    <a href="#"
                                       onclick="NowaMail(<?php echo $order->getId(); ?>,$(this)); return false;"
                                       class="nowa_mail">отправить повторно</a></span>
                            <?php } ?>
                            <br/>
                        <?php } ?>
                        <select name="order_status" onChange="this.form.submit(); return false;">
                            <?php foreach ($this->order_status as $key => $item) { ?>
                                <option value="<?php echo $key; ?>" <?php if ($key == $order->getStatus()) echo "selected"; ?>><?php echo $item; ?></option>
                            <?php } ?>
                        </select>
                    </form>
                    <?php

                    if ($order->getRemarks()->count() or $order->getComments()) {
                        $remar = array();
                        foreach ($order->getRemarks() as $remark) {
                            $remar[] = $remark->getRemark();
                        }
                        ?>
                            <?php if ($order->getRemarks()->count()) { ?>
								<div class="comm_ins">
                                <b>Внутренний комментарий :</b><br/>
                                <?php echo implode(';', $remar); ?>
								</div>
                            <?php } ?>
                            <?php if ($order->getComments()) { ?>
								<div class="comm_cli">
                                <b>Комментарий клиента :</b><br/>
                                <?php echo $order->getComments(); ?>
								</div>
                            <?php } ?>
                    <?php } ?>
                </td>
                <td><?php if ($order->getOznak() == 1 && $order->getSoglas() == 1) echo 'Да'; else echo 'Нет'; ?></td>
            </tr>


        <?php } ?>

    </table>
    <p>
        <label></label>
    </p>
    <?php
    $limitLeft = 2;
    $limitRight = 2;
    $url = explode('?', $_SERVER['REQUEST_URI']);
    if (count($url) == 2) {
        $ur = $url[0];
        $get = '?' . $url[1];
    } else {
        $ur = $_SERVER['REQUEST_URI'];
        $get = '';
    }
    $pager = preg_replace('/\/page\/\d*/', '', $ur) . '/page/';
    $paginator = '&nbsp;&nbsp;';
    if ($this->page > 1) {
        $paginator .= '<a href="' . $pager . '1' . $get . '"><<</a>&nbsp;<a href="' . $pager . ($this->page - 1) . $get . '"><</a>&nbsp;';
    } else {
        $paginator .= '<span class="grey"><</span>&nbsp;<span class="grey"><<</span>&nbsp;';
    }
    $start = 1;
    $end = $this->totalPages;
    if ($this->page > $limitLeft) {
        $paginator .= '...&nbsp;';
        $start = $this->page - $limitLeft;
    }
    if (($this->page + $limitRight) < $this->totalPages) {
        $end = $this->page + $limitRight;
    }
    //for ($i = 1; $i <= $this->totalPages; $i++){
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $this->page) {
            $paginator .= '<span>' . $i . '</span>';
        } else {
            $paginator .= '<span><a href="' . $pager . $i . $get . '">' . $i . '</a></span>';
        }
        if ($i <= $end - 1) {
            $paginator .= '<span class="delimiter">&nbsp;|&nbsp;</span>';
        }

    }
    if ($this->page == $this->totalPages) {
        $paginator .= '&nbsp;<span class="grey">>></span>&nbsp;<span class="grey">></span>';
    } else {
        $paginator .= '&nbsp;<a href="' . $pager . ($this->page + 1) . $get . '">></a>&nbsp;<a href="' . $pager . $this->totalPages . $get . '">>></a>';
    }
    echo $paginator;

    ?><br/>
    Всего страниц: <?php echo $this->totalPages ?>,  записей: <?php echo $this->count ?>


<?php } else echo 'Нет записей'; ?>
<p>&nbsp;</p>