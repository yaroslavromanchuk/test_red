<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>

<?php
 $order_status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
if ($this->errors) {
    ?>
<div id="errormessage"><img src="<?php echo SITE_URL;?>/img/icons/error.png" alt="" class="page-img"/>

    <h1>Ошибка:</h1>
    <ul>
        <?php
                    foreach ($this->errors as $error)
    {
        ?>
        <li><?php echo $error;?></li>
        <?php

    }
        ?>
    </ul>
</div>
        <?php

}

if ($this->saved) {
    ?>
<div id="pagesaved">
    <img src="<?php echo SITE_URL;?>/img/icons/accept.png" alt="" class="page-img"/>

    <h1>Запись сохранена.</h1>
</div>
<?php

}
?>

<form method="POST" action="">
    <table id="editpage" cellpadding="5" cellspacing="0">
        <tr>
            <td class="kolom1">Имя</td>
            <td><input name="first_name" type="text" class="formfields" id="paginatitle"
                       value="<?php echo $this->sub->getFirstName();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Фамилия</td>
            <td><input name="middle_name" class="formfields"
                       value="<?php echo $this->sub->getMiddleName();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">E-mail</td>
            <td><input name="email" class="formfields" value="<?php echo $this->sub->getEmail();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Телефон</td>
            <td><input name="phone1" class="formfields" value="<?php echo $this->sub->getPhone1();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Адрес</td>
            <td><input name="adress" class="formfields" value="<?php echo $this->sub->getAdress();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Индекс</td>
            <td><input name="index" class="formfields" value="<?php echo $this->sub->getIndex();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Улица</td>
            <td><input name="street" class="formfields" value="<?php echo $this->sub->getStreet();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Дом</td>
            <td><input name="house" class="formfields" value="<?php echo $this->sub->getHouse();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Квартира</td>
            <td><input name="flat" class="formfields" value="<?php echo $this->sub->getFlat();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Компания</td>
            <td><input name="company_name" class="formfields"
                       value="<?php echo $this->sub->getCompanyName();?>"/></td>
        </tr>
        <tr>
        <tr>
            <td class="kolom1">Скидка по покупкам</td>
            <td><?php echo $this->sub->getDiscont();?>%</td>
        </tr>
        <tr>
        <tr>
            <td class="kolom1">Скидка VIP</td>
            <td><input name="skidka" class="formfields" value="<?php echo $this->sub->getSkidka();?>"/>%</td>
        </tr>
        <tr>
        <tr>
            <td class="kolom1">Депозит</td>
            <td><input name="deposit" class="formfields" value="<?php echo $this->sub->getDeposit();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Статус</td>
            <td>
                <input type="radio" name="customer_status_id" id='activ'
                       value="1" <?php if ($this->sub->getCustomerStatusId() != 2) { ?>checked="checked"<?php } ?>>
                <label for="activ">Активный</label>
                <input type="radio" name="customer_status_id" id='ban'
                       value="2" <?php if ($this->sub->getCustomerStatusId() == 2) { ?>checked="checked"<?php } ?>>
                <label for="activ">Бан</label>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Сброс пароля</td>
            <td>
                <?php if (isset($_GET['rpass'])) {
                if (@$_GET['rpass'] == 'email') echo 'Новый пароль отправлен по Email';
                elseif (@$_GET['rpass'] == 'sms') echo 'Новый пароль отправлен по Sms';
                else 'Ошибка';
            } else {
                ?>
                <a href="&resetpass=email">По Email</a> <br/>
                <a href="&resetpass=sms">По SMS</a>
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

    <?php  $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('customer_id' => $this->sub->getId()));
    if ($orders->count() > 0) {
        ?>
        <p>Заказы:</p>

        <table cellspacing="0" cellpadding="4" id="orders">
            <tr>
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
                    <a href="/admin/shop-orders/edit/id/<?php echo $order->getId(); ?>"><img width="24" height="24"
                                                                                             alt="Редактировать"
                                                                                             src="/img/icons/edit-small.png"></a>
                </td>
                <td><?php echo isset($order_status[$order->getStatus()]) ? $order_status[$order->getStatus()]
                        : ""; ?></td>
                <td><?=$order->getId();?></td>
                <td><?php $d = new wsDate($order->getDateCreate()); echo $d->getFormattedDateTime(); ?></td>
                <td><?php echo $order->getArticlesCount(); ?></td>
                <td><?php if ($order->getArticlesCount() != 0) {
                    echo number_format((double)$order->getTotal('a'), 2, ',', '') . ' грн <br/>' . Number::formatFloat(((float)$order->getPriceWithSkidka() + (float)$order->getDeliveryCost()), 2) . ' грн';
                } ?></td>
                <td>
                    <a target="_blank"
                       href="/admin/generateorder/id/<?php echo $order->getId();?>/type/1">Магазин</a><br/>
                    <a target="_blank"
                       href="/admin/generateorder/id/<?php echo $order->getId();?>/type/2">Укрпочта</a><br/>
                </td>
                <td>
                        <?php echo $order->getDeliveryTypeId() ? $order->getDeliveryType()->getName() : "&nbsp;"; ?>
                    <br/>
                    <?php if ($order->getNakladna()) { ?>
                    Накладная №: <?php echo $order->getNakladna(); ?>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>


        <?php } ?>
</form>
	