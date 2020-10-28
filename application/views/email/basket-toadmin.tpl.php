<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>RED</title>
</head>
<body style="background-color:#c4c5c7">
<center>
<table border="0" cellpadding="0" cellspacing="0" style="border:10px solid #ffffff; background:#ffffff" width="700">
<tr>
<td>
<table border="0" cellpadding="0" cellspacing="0" width="700" margin-bottom="10px">
    <tr>
        <td>
            <img src="http://<?php echo Config::findByCode('domain_name')->getValue();?>/img/mail-top.jpg"
                 width="700" alt="RED" title="RED">

        </td>
    </tr>
</table>
<table border="0" cellpadding="20" cellspacing="0" width="700" style="font-family: Verdana">
<tr>
<td width=700 style="vertical-align:top">

<h2><?php echo $this->trans->get('Список товаров');?> №<?php echo $this->basket_contacts->getId();?></h2>
<table cellpadding="4" cellspacing="0" class="overview-order">
    <tr>
        <td class="article"><strong><?php echo $this->trans->get('Товар');?></strong></td>
        <td><strong><?php echo $this->trans->get('Размер');?></strong></td>
        <td><strong><?php echo $this->trans->get('Цвет');?></strong></td>
        <td class="number"><strong><?php echo $this->trans->get('Количество');?></strong>
        </td>
        <td class="column-euro">&nbsp;</td>
        <td class="prices"><strong><?php echo $this->trans->get('Цена');?></strong></td>
        <td class="column-euro">&nbsp;</td>
        <td class="prices"><strong><?php echo $this->trans->get('Всего');?></strong></td>
    </tr>
    <?php $t_count = 0; $t_price = 0.00; $total_price = 0.00;
    $to_pay = 0;
    $to_pay_minus = '0.00';
    $now_orders = 0;
    $skidka = 0;
    $event_skidka = 0;
        if ($this->ws->getCustomer()->getIsLoggedIn()) {
            $skidka = $this->ws->getCustomer()->getDiscont();
            $event_skidka =  EventCustomer::getEventsDiscont($this->ws->getCustomer()->getId());
            $all_orders = wsActiveRecord::useStatic('Customer')->findByQuery('
    SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
   WHERE ws_orders.customer_id = ' . $this->ws->getCustomer()->getId() . ' AND ws_orders.status IN (0,1,3,4,6,8,9,10,11,13) ')->at(0);
            $now_orders = $all_orders->getAmount();
        }
    foreach ($this->getArticles() as $article) {
        $t_price += $article['price'] * $article['count'];
    }
     $now_orders += $t_price;
    foreach ($this->getArticles() as $article) {
        $at = new Shoparticles($article['id']);
        if($article['count'] == 0 ) $article['count'] = 'нет на складе';
        ?>
        <tr>
            <td class="article">
                <img width="70"
                     src="http://<?php echo $_SERVER['HTTP_HOST']; echo $at->getImagePath('listing'); ?>"
                     alt="<?php echo htmlspecialchars($at->getTitle()); ?>"/>
                <br/>
                <?php echo $article['title']; ?><?php if (strlen($article['code']) > 0) echo '(КОД: ' . $article['code'] . ')';?>
            </td>
            <td><?php echo wsActiveRecord::useStatic('Size')->findById($article['size'])->getSize(); ?></td>
            <td><?php echo wsActiveRecord::useStatic('Shoparticlescolor')->findById($article['color'])->getName(); ?></td>
            <td class="number"><?php $t_count += $article['count']; echo $article['count']; ?></td>
            <td class="column-euro"></td>
            <td class="prices"> <?php echo Shoparticles::showPrice($article['price']); ?>грн
            </td>
            <td class="column-euro"></td>
            <td class="prices"><?php if($article['count']!='нет на складе'){echo Shoparticles::showPrice($article['price'] * $article['count']).' грн.';} else echo $article['count']; ?>
            </td>
        </tr>
        <?php


        $to_pay_perc = $at->getProcent($now_orders, $skidka);
        $price = $at->getPerc();
        $to_pay += $price['price'];
        $to_pay_minus += $price['minus'];

    } ?>
    <tr>
        <td class="article"><strong><?php echo $this->trans->get('Доставка');?></strong>
        </td>
        <td class="number"><strong></strong></td>
        <td class="number"><strong></strong></td>
        <td class="number"><strong></strong></td>
        <td class="column-euro">&nbsp;</td>
        <td class="prices"><strong></strong></td>
        <td class="column-euro">&nbsp;</td>
        <td class="prices"><?php echo Shoparticles::showPrice(@$this->basket_contacts['delivery_cost']);?>
            грн
        </td>
    </tr>
    <?php


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
    if ($_SERVER['REMOTE_ADDR'] == '93.72.133.153') {
        //print_r($to_pay_perc.' '.$to_pay_minus.' '.$to_pay);
    }

    $kop = round(($to_pay - toFixed($to_pay)) * 100, 0);

    $total_price = $to_pay + @$this->basket_contacts['delivery_cost'];?>
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
        <td><strong><?=$this->ws->getCustomer()->getDiscont();?>%</strong></td>
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
        <?php if($this->deposit > 0){?>
         <tr>
        <td></td>
        <td><strong><?php echo $this->trans->get('Депозит');?></strong></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>&nbsp;</td>
        <td><strong></strong></td>
        <td><strong><?php  echo Shoparticles::showPrice($this->deposit); ?> грн</strong></td>
    </tr>
        <?php } ?>
    <tr>
        <td class="article"><strong><?php echo $this->trans->get('Всего');?></strong></td>
        <td class="number"><strong></strong></td>
        <td class="number"><strong></strong></td>
        <td class="number"><strong><?php echo $t_count; ?></strong></td>
        <td class="column-euro">&nbsp;</td>
        <td class="prices">&nbsp;</td>
        <td class="column-euro"><strong></strong></td>
        <td class="prices">
            <strong><?php echo Shoparticles::showPrice($total_price-$this->deposit); ?></strong>
            грн
        </td>
    </tr>
</table>

<br/>

<h2><?php echo $this->trans->get('Контактная информация');?></h2>
<table cellspacing="0" cellpadding="4" class="overview-contact">
    <tr>
        <td class="info"><?php echo $this->trans->get('Компания');?></td>
        <td><?php echo (isset($this->basket_contacts['company']))
                ? $this->basket_contacts['company'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info"><?php echo $this->trans->get('Имя');?></td>
        <td><?php echo (isset($this->basket_contacts['name']))
                ? $this->basket_contacts['name'] : "&nbsp;"; ?></td>
    </tr>

    <!--tr>
                                    <td class="info">Почтовый код</td>
                                    <td><?php echo (isset($this->basket_contacts['pc'])) ? $this->basket_contacts['pc']
            : "&nbsp;"; ?></td>
                                    </tr-->
    <tr>
        <td class="info"><?php echo $this->trans->get('Город');?></td>
        <td><?php echo (isset($this->basket_contacts['city']))
                ? $this->basket_contacts['city'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info"><?php echo $this->trans->get('Способ доставки');?></td>
        <td><?php $d = new DeliveryType(@$this->basket_contacts['delivery_type_id']); echo (isset($this->basket_contacts['delivery_type_id']))
                ? $d->getName() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info"><?php echo $this->trans->get('Адрес');?></td>
        <td><?php echo (isset($this->basket_contacts['address']))
                ? $this->basket_contacts['address'] : "&nbsp;"; ?></td>
    </tr>
        <tr>
        <td class="info"><?php echo $this->trans->get('Индекс');?></td>
        <td><?php echo (isset($this->basket_contacts['index']))
                ? $this->basket_contacts['index'] : "&nbsp;"; ?></td>
    </tr>
        <tr>
        <td class="info"><?php echo $this->trans->get('Улица');?></td>
        <td><?php echo (isset($this->basket_contacts['street']))
                ? $this->basket_contacts['street'] : "&nbsp;"; ?></td>
    </tr>
        <tr>
        <td class="info"><?php echo $this->trans->get('Дом');?></td>
        <td><?php echo (isset($this->basket_contacts['house']))
                ? $this->basket_contacts['house'] : "&nbsp;"; ?></td>
    </tr>
        <tr>
        <td class="info"><?php echo $this->trans->get('Квартира');?></td>
        <td><?php echo (isset($this->basket_contacts['flat']))
                ? $this->basket_contacts['flat'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info"><?php echo $this->trans->get('Способ оплаты');?></td>
        <td><?php $p = new PaymentMethod(@$this->basket_contacts['payment_method_id']); echo (isset($this->basket_contacts['payment_method_id']))
                ? $p->getName() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info"><?php echo $this->trans->get('Телефон');?></td>
        <td><?php echo (isset($this->basket_contacts['telephone']))
                ? $this->basket_contacts['telephone'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info">E-mail</td>
        <td><?php echo (isset($this->basket_contacts['email']))
                ? $this->basket_contacts['email'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="info"><?php echo $this->trans->get('Комментарий');?></td>
        <td><?php echo (isset($this->basket_contacts['comments']))
                ? $this->basket_contacts['comments'] : "&nbsp;"; ?></td>
    </tr>
</table>

</tr>

<tr>
    <td valign="top">
        <img src="http://<?php echo Config::findByCode('domain_name')->getValue();?>/img/mail-bottom.jpg"
             width="700" height="30" alt=""></td>
</tr>
</table>
</td>
</tr>
</table>
</center>
</body>
</html>


