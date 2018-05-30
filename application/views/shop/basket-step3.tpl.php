<h1 class="violet"><?php echo $this->getCurMenu()->getName();?></h1>
<?php echo $this->render('parts/poll_box.tpl.php'); ?>

<!--<div id="steps">
    <p>
        <a class="prev-step" href="/basket/">ШАГ 1 - <?php /*echo $this->trans->get('Корзина');*/?></a>&nbsp;
        <a class="prev-step" href="/shop-checkout-step2/">ШАГ 2
            - <?php /*echo $this->trans->get('Контактные данные');*/?></a>&nbsp;
        <a class="prev-step">ШАГ 3 - <?php /*echo $this->trans->get('Обзор заказа');*/?></a>&nbsp;
        ШАГ 4 - <?php /*echo $this->trans->get('Заказать');*/?>&nbsp;
    </p>
</div>-->

<a class="next-step new_button"
   href="<?php echo wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step4')->getPath(); ?>">Подтвердить заказ</a>

<h4><?php echo $this->trans->get('Выбранные товары');?></h4>
<table cellpadding="4" cellspacing="0" class="basket-cont">
    <tr class="top">
        <td><strong><?php echo $this->trans->get('Товар');?></strong></td>
        <td><strong><?php echo $this->trans->get('Размер');?></strong></td>
        <td><strong><?php echo $this->trans->get('Цвет');?></strong></td>
        <td><strong><?php echo $this->trans->get('Количество');?></strong></td>
        <td>&nbsp;</td>
        <td><strong><?php echo $this->trans->get('Цена');?></strong></td>
        <td>&nbsp;</td>
        <td><strong><?php echo $this->trans->get('Всего');?></strong></td>
    </tr>
<?php 
    $t_count = 0;
	$t_price = 0.00;
	$total_price = 0.00;
    $to_pay = 0;
    $to_pay_minus = 0.00;
    $skidka = 0;
    $now_orders = 0;
    $event_skidka = 0;
    if ($this->ws->getCustomer()->getIsLoggedIn()) {
        $skidka = $this->ws->getCustomer()->getDiscont();
        $event_skidka =  EventCustomer::getEventsDiscont($this->ws->getCustomer()->getId());
        $all_orders = wsActiveRecord::useStatic('Customer')->findByQuery('
            SELECT
				IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			FROM
				ws_order_articles
			    JOIN ws_orders
				ON ws_order_articles.order_id = ws_orders.id
            WHERE
				ws_orders.customer_id = ' . $this->ws->getCustomer()->getId() . '
				AND ws_orders.status IN (1,3,4,6,8,9,10,11,13) ')->at(0);
        $now_orders = $all_orders->getAmount();
    }
    foreach ($this->getArticles() as $article) {
        $t_price += $article['price'] * $article['count'];
    }
    $now_orders += $t_price;


    foreach ($this->getArticles() as $article) {
        $at = new Shoparticles($article['id']);
?>
        <tr class="article">
            <td><?php echo $article['title']; ?></td>
            <td><?php echo wsActiveRecord::useStatic('Size')->findById($article['size'])->getSize(); ?></td>
            <td><?php echo wsActiveRecord::useStatic('Shoparticlescolor')->findById($article['color'])->getName(); ?></td>
            <td><?php $t_count += $article['count']; echo $article['count']; ?></td>
            <td></td>
            <td> <?php echo Shoparticles::showPrice($article['price']); ?> грн</td>
            <td></td>
            <td><?php echo Shoparticles::showPrice($article['price'] * $article['count']); ?>
                грн
            </td>
        </tr>
<?php

        $to_pay_perc = $at->getProcent($now_orders, $skidka);
        $price = $at->getPerc($now_orders, $article['count'], $skidka, $event_skidka);

        $to_pay += $price['price'];
        $to_pay_minus += $price['minus'];


	}
?>

    <tr class="end">
        <td><strong><?php echo $this->trans->get('Доставка');?></strong></td>
        <td><strong></strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><strong></strong></td>
        <td>&nbsp;</td>
        <td><?php echo Shoparticles::showPrice(Shoporders::getDeliveryPrice());?> грн</td>
    </tr>
    <?php

    $tota_price = $t_price;




    /* $to_pay_minus = '0.00';
    if ($now_orders <= 700) {
        $to_pay = $tota_price;
        $to_pay_perc = '0,00%';
    } elseif ($now_orders > 700 && $now_orders <= 5000) { //5%
        $to_pay_perc = '5,00%';
        $to_pay_minus = (($tota_price / 100) * 5);
        $to_pay = $tota_price - $to_pay_minus;

    } elseif ($now_orders > 5000 && $now_orders <= 12000) { //10%
        $to_pay_perc = '10,00%';
        $to_pay_minus = (($tota_price / 100) * 10);
        $to_pay = $tota_price - $to_pay_minus;
    } elseif ($now_orders > 12000) { //15%
        $to_pay_perc = '15,00%';
        $to_pay_minus = (($tota_price / 100) * 15);
        $to_pay = $tota_price - $to_pay_minus;
    }*/


    $kop = round(($to_pay - toFixed($to_pay)) * 100, 0);

    $total_price = $to_pay + Shoporders::getDeliveryPrice();?>
    <tr>
        <td><strong><?php echo $this->trans->get('Всего без скидки');?></strong></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>&nbsp;</td>
        <td><strong></strong></td>
        <td><strong><?php  echo Shoparticles::showPrice($t_price); ?> грн</strong></td>
    </tr>
    <tr>
        <td><strong><?php echo $this->trans->get('Скидка');?></strong></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>&nbsp;</td>
        <td><strong></strong></td>
        <td><strong><?= $this->ws->getCustomer()->getDiscont();?>%</strong></td>
    </tr>
    <tr>
        <td><strong><?php echo $this->trans->get('Сумма скидки');?></strong></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>&nbsp;</td>
        <td><strong></strong></td>
        <td><strong><?php  echo Shoparticles::showPrice($to_pay_minus); ?> грн</strong></td>
    </tr>
<?php
$total_price2= $total_price;
if (isset($_SESSION['deposit']) and $this->ws->getCustomer()->getDeposit()) {
    $dep = $this->ws->getCustomer()->getDeposit() - $total_price;
    if ($dep <= 0) $dep = $this->ws->getCustomer()->getDeposit();
    else $dep = $total_price;
    $_SESSION['deposit'] = $dep;
?>
    <tr>
        <td><strong><?php echo $this->trans->get('Депозит');?></strong></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>&nbsp;</td>
        <td><strong></strong></td>
        <td><strong><?php  echo Shoparticles::showPrice($dep); ?> грн</strong></td>
    </tr>
<?php
    $total_price2= $total_price;
    $total_price = $total_price - $this->ws->getCustomer()->getDeposit();
    if ($total_price < 0) $total_price = 0;


} ?>
    <tr>
        <td><strong><?php echo $this->trans->get('Всего');?></strong></td>
        <td></td>
        <td></td>
        <td><strong><?php echo $t_count; ?></strong></td>
        <td></td>
        <td>&nbsp;</td>
        <td><strong></strong></td>
        <td><strong><?php  echo Shoparticles::showPrice($total_price); ?> грн</strong></td>
        <?php
        $_SESSION['sum_to_ses'] = $total_price;
        $_SESSION['sum_to_ses_no_dep'] = $total_price2;
        ?>
    </tr>
</table>
<br/>

<h4><?php echo $this->trans->get('Контактная информация');?></h4>
<table cellspacing="0" cellpadding="4" class="basket-cont view">

    <tr>
        <td class="info"><?php echo $this->trans->get('Имя');?></td>
        <td><?php echo (isset($this->basket_contacts['name'])) ? $this->basket_contacts['name'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info"><?php echo $this->trans->get('Фамилия');?></td>
        <td><?php echo (isset($this->basket_contacts['middle_name'])) ? $this->basket_contacts['middle_name']
                : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info"><?php echo $this->trans->get('Способ доставки');?></td>
        <td><?php $d = new DeliveryType(@$this->basket_contacts['delivery_type_id']); echo (isset($this->basket_contacts['delivery_type_id']))
                ? $d->getName() : "&nbsp;"; ?></td>
    </tr>
    <?php if (in_array(@$this->basket_contacts['delivery_type_id'], array(4, 8, 9))) { ?>
    <?php if (@$this->basket_contacts['delivery_type_id'] == 4) { ?>
        <tr>
            <td class="info"><?php echo $this->trans->get('Индекс');?></td>
            <td><?php echo (isset($this->basket_contacts['index'])) ? $this->basket_contacts['index']
                    : "&nbsp;"; ?></td>
        </tr>

        <tr>
            <td class="info"><?php echo $this->trans->get('Область');?></td>
            <td><?php echo (isset($this->basket_contacts['obl'])) ? $this->basket_contacts['obl']
                    : "&nbsp;"; ?></td>
        </tr>
        <tr>
            <td class="info"><?php echo $this->trans->get('Район');?></td>
            <td><?php echo (isset($this->basket_contacts['rayon'])) ? $this->basket_contacts['rayon']
                    : "&nbsp;"; ?></td>
        </tr>
        <?php } ?>
    <?php if (@$this->basket_contacts['delivery_type_id'] == 8 or @$this->basket_contacts['delivery_type_id'] == 4) { ?>
        <tr>
            <td class="info"><?php echo $this->trans->get('Город');?></td>
            <td><?php echo (isset($this->basket_contacts['city'])) ? $this->basket_contacts['city'] : "&nbsp;"; ?></td>
        </tr>
        <?php } ?>
    <tr>
        <td class="info"><?php echo $this->trans->get('Улица');?></td>
        <td><?php echo (isset($this->basket_contacts['street'])) ? $this->basket_contacts['street']
                : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info"><?php echo $this->trans->get('Дом');?></td>
        <td><?php echo (isset($this->basket_contacts['house'])) ? $this->basket_contacts['house']
                : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info"><?php echo $this->trans->get('Квартира');?></td>
        <td><?php echo (isset($this->basket_contacts['flat'])) ? $this->basket_contacts['flat']
                : "&nbsp;"; ?></td>
    </tr>
    <?php if (@$this->basket_contacts['delivery_type_id'] == 8) { ?>
        <tr>
            <td class="info"><?php echo $this->trans->get('Склад');?></td>
            <td><?php echo (isset($this->basket_contacts['sklad'])) ? $this->basket_contacts['sklad']
                    : "&nbsp;"; ?></td>
        </tr>
        <?php
    }
} ?>
    <tr>
        <td class="info"><?php echo $this->trans->get('Способ оплаты');?></td>
        <td><?php $p = new PaymentMethod(@$this->basket_contacts['payment_method_id']); echo (isset($this->basket_contacts['payment_method_id']))
                ? $p->getName() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info"><?php echo $this->trans->get('Телефон');?></td>
        <td><?php echo (isset($this->basket_contacts['telephone'])) ? $this->basket_contacts['telephone']
                : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info">E-mail</td>
        <td><?php echo (isset($this->basket_contacts['email'])) ? $this->basket_contacts['email'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info"><?php echo $this->trans->get('Комментарий');?></td>
        <td><?php echo (isset($this->basket_contacts['comments'])) ? $this->basket_contacts['comments']
                : "&nbsp;"; ?></td>
    </tr>
</table>
<p>&nbsp;</p>

<?php echo $this->getCurMenu()->getPageBody(); ?>

