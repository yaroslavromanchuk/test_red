<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt=""
     xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" width="32" class="page-img"
     height="32"/>
<h1>Заказ <?php echo $this->getOrder()->getId(); ?></h1>

<table cellspacing="0" cellpadding="4" id="order-details">
    <?php if ($this->order->getCallMy()) { ?>
    <tr>
        <td class="column-data" colspan="2">Перезвонить для уточнения деталей.</td>

    </tr>
    <?php } ?>

    <tr>
        <td class="column-data">Номер заказа</td>
        <td id='get_order_id'><?php echo $this->getOrder()->getId(); ?></td>
    </tr>
    <?php if ($this->getOrder()->getComlpect()) { ?>
    <tr>
        <td class="column-data">Скомплектовано с</td>
        <td id='get_order_complect'><?php echo $this->getOrder()->getComlpect(); ?></td>
    </tr>
    <?php } ?>
    <?php if ($this->getOrder()->getBoxNumberC()) { ?>
    <tr>
        <td class="column-data">Яцейки<br />ячейка(заказ)</td>
        <td ><?php echo $this->getOrder()->getBoxNumberC(); ?></td>
    </tr>
    <?php } ?>
    <tr>
        <td class="column-data">Дата</td>
        <td><?php $d = new wsDate($this->getOrder()->getDateCreate()); echo $d->getFormattedDateTime(); ?></td>
    </tr>
    <form action="" method="post">
        <tr>
            <td class="column-data">Номер ячейки</td>
            <td>
                №<?php echo $this->getOrder()->getBoxNumber();?>
            </td>
        </tr>
    </form>
    <form action="" method="post">
        <tr>
            <td class="column-data">Накладная</td>
            <td>
                № <?php echo $this->getOrder()->getNakladna();?>
            </td>
        </tr>
        <tr>
            <td class="column-data">Статус</td>
            <td>
                <?php echo @$this->order_status[$this->getOrder()->getStatus()]?>
               </td>
        </tr>
    </form>
</table>

<p><strong>Информация о покупателе</strong></p>
<table cellpadding="4" cellspacing="0" id="order-client"
       <?php
$order_owner = new Customer($this->getOrder()->getCustomerId());
if ($order_owner->getAdminComents()) {
    ?>
<tr>
    <td class="column-data">Коментарии админа</td>
    <td><?php echo $order_owner->getAdminComents(); ?></td>
</tr>
<?php } ?>
    <tr>
        <td class="column-data">Компания</td>
        <td><?php echo $this->getOrder()->getCompany() ? $this->getOrder()->getCompany() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Имя</td>
        <td><?php echo ($this->getOrder()->getName() ? $this->getOrder()->getName()
            : "&nbsp;") . '&nbsp;&nbsp;(всего заказов на сумму: <b>' . Shoparticles::showPrice($this->total_amount) . ' грн</b>)' ?></td>
    </tr>
    <tr>
        <td class="column-data">Адрес</td>
        <td><?php echo $this->getOrder()->getAddress() ? $this->getOrder()->getAddress() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Почтовый код</td>
        <td><?php echo $this->getOrder()->getPc() ? $this->getOrder()->getPc() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Город</td>
        <td><?php echo $this->getOrder()->getCity() ? $this->getOrder()->getCity() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Индекс</td>
        <td><?php echo $this->getOrder()->getIndex() ? $this->getOrder()->getIndex() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Улица</td>
        <td><?php echo $this->getOrder()->getStreet() ? $this->getOrder()->getStreet() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Дом</td>
        <td><?php echo $this->getOrder()->getHouse() ? $this->getOrder()->getHouse() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Квартира</td>
        <td><?php echo $this->getOrder()->getFlat() ? $this->getOrder()->getFlat() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Телефон</td>
        <td><?php echo $this->getOrder()->getTelephone() ? $this->getOrder()->getTelephone() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">E-mail</td>
        <td><?php echo $this->getOrder()->getEmail() ? $this->getOrder()->getEmail() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Комментарий</td>
        <td><?php echo $this->getOrder()->getComments() ? htmlspecialchars($this->getOrder()->getComments())
            : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Способ оплаты</td>
        <td>
            <?php echo $this->getOrder()->payment_method->getName()?>

        </td>
    </tr>
    <tr>
        <td class="column-data">Способ доставки</td>
        <td>
            <?php echo $this->getOrder()->delivery_type->getName()?>
           </td>
    </tr>
    <tr>
        <td class="column-data">Стоимость доставки</td>
        <td><?php echo Shoparticles::showPrice($this->getOrder()->getDeliveryCost()); ?> грн</td>
    </tr>
<tr>
    <td class="column-data">Акционная скидка</td>
    <td><?php echo $this->getOrder()->getEventSkidka(); ?> % </td>
</tr>
</table>

<p><strong>Товары</strong></p>

<table cellpadding="4" cellspacing="0" id="order-articles">
<tr>
    <td  class="kolomicon"></td>
    <td class="column-article"><strong>Кол./Товар</strong></td>
    <td class="column-article"><strong>Размер/Цвет</strong></td>
    <td><strong>Старая цена</strong></td>
    <td><strong>Скидка</strong></td>
    <td colspan="2"><strong>Цена</strong></td>
</tr>
<form method="post" action="">
    <?php $t_price = 0.00; $t_option = 0.00; if ($this->getOrder()->getArticles()->count()) { ?>
    <?php $bool = false;
    foreach ($this->getOrder()->getArticles() as $item_tmp) if ($item_tmp->getOptionId() > 0) $bool = true;
    foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
        $article = new Shoparticles($article_rec->getArticleId());
        ?>
        <tr>

            <td class="kolomicon">
                <img class="prev" rel="#miesart<?=$article_rec->getId();?>"
                     src="<?php echo $article_rec->getImagePath('small_basket'); ?>"
                     alt="<?php echo htmlspecialchars($article_rec->getTitle()); ?>"/>

                <div class="simple_overlay" id="miesart<?=$article_rec->getId();?>"
                     style="min-height: 300px; padding:10px 80px">
                    <img src="<?php echo $article_rec->getImagePath('detail'); ?>"
                         alt="<?php echo htmlspecialchars($article_rec->getTitle()); ?>"/>

                </div>
                <div class="previ" style="position: absolute; display: none; margin-left: 600px; margin-top: -150px;">
                    <img src="<?php echo $article_rec->getImagePath('detail'); ?>"
                         alt="<?php echo htmlspecialchars($article_rec->getTitle()); ?>"/></div>

            </td>

            <td class="column-article">


                <?php echo 'Заказано: ' . $article_rec->getCount();?>
                - <a href="<?php echo $article->getPath(); ?>"
                     target="_blank"><?php echo $article_rec->article_db->category->getRoutez() . ' : '; echo $article_rec->getTitle();  if (strlen($article_rec->getCode()) > 0) echo '(КОД: ' . $article_rec->getCode() . ')';?></a>
                <?php if ($main_key == 0 && !(isset($options[$article_rec->getOptionId()]) && $article_rec->getOptionId() > 0)) {
                echo "";
            } ?>
                <?php if (isset($options[$article_rec->getOptionId()]) && $article_rec->getOptionId() > 0) echo "<br />({$options[$article_rec->getOptionId()]->getOption()})"; ?>

            </td>
            <td class="column-euro">
                <input type="hidden" class="hidden" value="<?=$article->getId()?>">
                <?=wsActiveRecord::useStatic('Size')->findById($article_rec->getSize())->getSize();?>
                /<?=wsActiveRecord::useStatic('Shoparticlescolor')->findById($article_rec->getColor())->getName();?>

            </td>
            <td><?php $price_real = (int)$article_rec->getOldPrice() ? $article_rec->getOldPrice() : $article_rec->getPrice();
                echo $price_real; ?></td>
            <td>
                <?php echo ceil((1 - ($article_rec->getPrice() / $price_real)) * 100);?> %
                <?php if ($article_rec->getEventSkidka()) { ?>
                +<?php echo $article_rec->getEventSkidka(); ?>%
                <?php } ?>
            </td>
            <td>
                <?php $tmp = $article_rec->getPrice() * (1 - ($article_rec->getEventSkidka() / 100)) * $article_rec->getCount(); $t_price += $tmp; echo Shoparticles::showPrice($tmp); ?>
                грн
            </td>

        </tr>
        <?php } ?>
    <?php } ?>

</form>



<tr>
    <td colspan="6" class="tussenrij">&nbsp;</td>
</tr>
<tr>
    <td colspan="4"><strong>Всего</strong></td>
    <td class="column-euro"><strong></strong></td>
    <td><strong><?php echo Shoparticles::showPrice($t_price); ?> грн</strong></td>
</tr>
<tr>
    <td colspan="4"><strong>Со скидкой</strong></td>
    <td class="column-euro"><strong></strong></td>
    <td><strong><?php
        //$price_with_skidka = $this->getOrder()->getPriceWithSkidka();//ЕВЖЕНЯ
        echo $this->getOrder()->calculateOrderPrice(false);
        ?> грн</strong></td>
</tr>
<tr>
    <td colspan="4"><strong>Скидка</strong></td>
    <td class="column-euro"><strong></strong></td>
    <td><strong><?php

        echo $order_owner->getDiscont($this->getOrder()->getId()); ?>%</strong></td>
</tr>
<?php if ($this->getOrder()->getDeposit() > 0) { ?>
<tr>
    <td colspan="4"><strong>Депозит</strong></td>
    <td class="column-euro"><strong></strong></td>
    <td><strong><?php

        echo $this->getOrder()->getDeposit() ?> грн</strong></td>
</tr>
    <?php } ?>
<tr>
    <td colspan="4"><strong>Счет</strong></td>
    <td class="column-euro"><strong></strong></td>
    <td><strong>
        <a target="_blank"
           href="/admin/generateorder/id/<?php echo $this->getOrder()->getId();?>/type/1">Магазин</a><br/>
        <a target="_blank"
           href="/admin/generateorder/id/<?php echo $this->getOrder()->getId();?>/type/2">Укрпочта</a><br/>
        <a target="_blank"
           href="/admin/generateorder/id/<?php echo $this->getOrder()->getId();?>/type/3">Новая почта</a><br/>
        <a target="_blank"
           href="/admin/generateorder/id/<?php echo $this->getOrder()->getId();?>/type/4">Курьер</a><br/>
    </strong></td>
</tr>

<tr>
    <td colspan="4"><strong>У пользователя на депозите</strong></td>
    <td class="column-euro"><strong></strong></td>
    <td><strong><?php

        echo $order_owner->getDeposit() ?> грн</strong><br/>
    </td>
</tr>

</table>
<p><strong>Комментарий (внутренний)</strong></p>
<table cellpadding="4" cellspacing="0" id="order-remarks">
    <?php foreach ($this->getOrder()->getRemarks() as $remark) { ?>
    <tr>
        <td class="column-data"><?php $d = new wsDate($remark->getDateCreate()); echo $d->getFormattedDate(); ?><br/>
            <?php echo $d->getHour() . '.' . $d->getMinute(); ?></td>
        <td><?php echo $remark->getRemark(); ?></td>
    </tr>
    <?php } ?>
</table>
<p>&nbsp;</p>
<script type="text/javascript">
$(document).ready(function () {
    $('.prev').hover(function () {
        $(this).parent().find('div.previ').show();
    }, function () {
        $(this).parent().find('div.previ').hide();
    });

});

</script>
<script type="text/javascript">

  

    $('#article_id').mousemove(function () {
        $('#aih_box img').hide();
        $('#aih_box #aih_' + $(this).attr('value')).show();
    }).mouseout(function () {
                $('#aih_box img').hide();
            });

</script>
<script type="text/javascript"
        src="<?php echo SITE_URL;?><?php echo $this->files;?>scripts/tiny_mce/tiny_mce.js"></script>
