<table border="0" cellpadding="3" cellspacing="0" width="770">
<tr>
    <td colspan="6" class="tt_border_bottom">
        <table border="0" cellpadding="0" cellspacing="0" width="400" style="font-style: italic;">
            <tr>
                <td align="left">
                    <strong>ФЛП "Цыбуля И.В."</strong>
                </td>
            </tr>
            <tr>
                <td align="left">
                    (РНОКППОП <strong>2978405548</strong>), Р/с 26005455021620 в ПАТ «ОТП Банк»,
                </td>
            </tr>
            <tr>
                <td align="left">
                    МФО 300528, http://www.red.ua, <strong>тел. (044) 462-50-90.</strong>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <strong>E-mail:</strong> market@red.ua
                </td>
            </tr>
        </table>
    </td>
    <td align="right" class="tt_border_bottom" valign="top" colspan="4">
        <img src="/img/redsmalllogo.png" alt=""/>
    </td>
</tr>
<tr>
    <td colspan="10" align="center" class="fnt_size_3">
        <br/>
        <strong>Товарный чек № <?php echo $this->order->getId()?></strong>
    </td>
</tr>
<tr>
    <td colspan="10" align="center" class="fnt_size_1">
        <strong>от <?php echo $this->date?> года</strong>
    </td>
</tr>
<tr>
    <td colspan="10">
        <i>Получатель: <?php echo $this->order->getName()?><?php //echo ', '.$this->order->getAddress()?>
        </i>

    </td>
</tr>
<tr>
    <td colspan="10">
        <i>Способ доставки: Новая почта</i>
    </td>
</tr>
<tr>
    <td colspan="10">
        <i>Адрес: <?php echo $this->order->getAddress()?></i>
    </td>
</tr>

<tr>
    <td colspan="10">
        <i>Коментарии к заказу:
            <?php echo $this->order->getComments()?></i>

    </td>
</tr>
<tr>
    <td colspan="10">
        <i>Контактный телефон: <?php echo $this->order->getTelephone()?></i>
        <br/><br/>
    </td>
</tr>
<!-- TOVAR TITLES-->
<tr>
    <td class="border_all fnt_size_1" align="center" rowspan="2" width="30">
        <strong>№</strong>
    </td>
    <td class="border_all" align="center" rowspan="2" width="400">
        <strong>Наименование товара</strong>
    </td>
    <td class="border_all" align="center" colspan="2" width="25">
        <strong>Коментарии кассира **</strong>
    </td>
    <td class="border_all" align="center" rowspan="2" width="50">
        <strong>КОД</strong>
    </td>
    <td class="border_all" align="center" width="50" rowspan="2">
        <strong>К-во (ед.)</strong>
    </td>
    <td class="border_all" align="center" width="50" rowspan="2">
        <strong>Старая Цена</strong>
    </td>
    <td class="border_all" align="center" width="50" rowspan="2">
        <strong>Скидка</strong>
    </td>
    <td class="border_all" align="center" width="50" rowspan="2">
        <strong>Новая Цена</strong>
    </td>
    <td class="border_all border_right" align="center" width="50" rowspan="2">
        <strong>Сумма</strong>
    </td>
</tr>
<tr>
    <td class="border_all" align="center" width="30"></td>
    <td class="border_all" align="center" width="30"></td>
</tr>
<!-- TOVAR -->
    <?php
        $i = 1;
    $c = $this->getOrder()->getArticles()->count();
    $total_price = 0;
    $to_pay = 0;
    $to_pay_minus = '0.00';
    foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
        $art = new Shoparticles($article_rec->getArticleId());
        $size = new Size($article_rec->getSize());
        $color = new Shoparticlescolor($article_rec->getColor());
        ?>
    <tr>
        <td class="border_all <?php echo ($i == $c) ? 'tt_border_bottom' : ''?>" align="center">
            <strong><?php echo $i?></strong>
        </td>
        <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>">
           <b> <?php echo (($article_rec->getBrand()) ? $article_rec->getBrand() . ', '
                : '') . $article_rec->getTitle() . ', ' . $size->getSize() . ', ' . $color->getName() ?></b>
        </td>
        <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>"></td>
        <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>"></td>
        <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>">
            <?php echo $article_rec->getCode()?>
        </td>
        <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>" align="center">
            <strong><?php echo $article_rec->getCount()?></strong>
        </td>

        <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>" align="right">
            <?php echo (int)$article_rec->getOldPrice() ? Number::formatFloat($article_rec->getOldPrice(), 2)
                : Number::formatFloat($article_rec->getPrice(), 2)?>
        </td>
        <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>" align="right">
            <?php
                               if ((int)$article_rec->getOldPrice()) {
            $skidka_t = (1 - ((int)$article_rec->getPrice() / ((int)$article_rec->getOldPrice()?(int)$article_rec->getOldPrice():1))) * 100;
        } else $skidka_t = 0;
            if ($skidka_t > 60) $skidka_t = 70;
            elseif ($skidka_t > 40) $skidka_t = 50;
            elseif ($skidka_t > 20) $skidka_t = 30;


            echo $skidka_t;?> %
             <?php if($article_rec->getEventSkidka()){?>
            +<?php echo $article_rec->getEventSkidka();?>%
            <?php } ?>
        </td>
        <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>" align="right">
            <?php echo Number::formatFloat($article_rec->getPrice()*(1 - ($article_rec->getEventSkidka() / 100)), 2)?>
        </td>
        <td class="border_all border_right" align="right">
            <strong><?php echo Number::formatFloat(($article_rec->getPrice()*(1 - ($article_rec->getEventSkidka() / 100)) * $article_rec->getCount()), 2)?></strong>
        </td>
    </tr>
            <?php
                    $total_price += $article_rec->getPrice()*(1 - ($article_rec->getEventSkidka() / 100)) * $article_rec->getCount(); // обшая сумма
        $to_pay_perc = $article_rec->getProcent($this->all_orders_amount); // процент скидки
        $price = $article_rec->getPerc(); // цена товара с кидкой
        $to_pay += $price['price'];
        $to_pay_minus += $price['minus'];
        $i++;
    }

    $kop = round(($to_pay - toFixed($to_pay)) * 100, 0);
    ?>
<tr>
    <td colspan="2"></td>
    <td colspan="2"></td>
    <td colspan="3" align="right">
        <strong>Итого:</strong>
    </td>
    <td class="border_all border_right" align="right" colspan="3">
        <strong><?php echo Number::formatFloat($total_price, 2)?></strong>
    </td>
</tr>
<tr>
    <td colspan="2"></td>
    <td colspan="2"></td>
    <td colspan="3" align="right">
        <i>Скидка</i>
    </td>
    <td class="border_all border_right" align="right" colspan="3">
        <i><?php echo $this->order->getDiscont();?>%</i>
    </td>
    <td>*</td>
</tr>
<tr>
    <td colspan="2"></td>
    <td colspan="2"></td>
    <td colspan="3" align="right" colspan="3">
        <i>Сумма скидки</i>
    </td>
    <td class="border_all border_right" align="right" colspan="3">
        <i><?php echo Number::formatFloat($to_pay_minus, 2)?></i>
    </td>

</tr>
    <?php if ($this->getOrder()->getDeposit() > 0) { ?>
<tr>
    <td colspan="2"></td>
    <td colspan="2"></td>
    <td colspan="3" align="right">
        <i>Депозит</i>
    </td>
    <td class="border_all border_right" align="right" colspan="3">
        <i><?php echo Number::formatFloat($this->getOrder()->getDeposit(), 2)?></i>
    </td>

</tr>
    <?php } ?>
<tr>
    <td colspan="2"></td>
    <td colspan="2"></td>
    <td colspan="3" align="right" class="fnt_size_1">
        <strong>Всего к оплате:</strong>
        <br/>
        <span style="font-size: 14px;">Без ПДВ</span>
    </td>
    <td class="border_all border_right tt_border_bottom" align="right" colspan="3">
        <i><?php echo Number::formatFloat($to_pay - $this->getOrder()->getDeposit(), 2)?></i>
    </td>
</tr>
<tr>
    <td colspan="10">
        <br/>
        <i>Всего наименований <strong><?php echo ($i - 1)?></strong>, на сумму <strong>
            <?php $sum = Number::formatFloat($to_pay - $this->getOrder()->getDeposit(), 2);
            $sum = explode(',', $sum);
            echo $sum[0];
            ?> грн.
            <?php echo @$sum[1] ? @$sum[1] : '00'?> коп.
            (<?php  echo Plural::currency(Number::formatFloat($to_pay - $this->getOrder()->getDeposit(), 2), $kop);
            /* echo $kop ? '' : ' коп.';*/?>
            )</strong></i>

        <br/><br/>
    </td>
</tr>
<tr>
    <td colspan="10" class="tt_border_bottom">
        * <i><b>
        Общая сумма Ваших заказов в интернет-магазине RED на <?php echo $this->exploded_date[2]?>
        .<?php echo $this->exploded_date[1]?>.2011 г. составляет
    <?php
                        if ($this->all_orders_amount_total == 0) {
        echo '0 грн. 00 коп.';
    } else {
        echo Plural::currency($this->all_orders_amount_total, $kop = 0) . ' коп.';
    }
        ?> (без учета данного заказа)
    </b></i>
        <br/><br/>
    </td>

</tr>
<tr>
    <td colspan="10" style=" font-size: 11px; padding-bottom: 30px;">
        Персональные данные (ФИО и адрес) Покупателя были переданы ФЛП Цибуля И.В., с целью выполнения данного заказа Покупателя, и в дальнейшем могут передаваться уполномоченным органам в установленном законом порядке. Как субъект персональных данных Покупатель имеет все права, предусмотренные ст. 8 Закона Украины «О защите персональных данных».
      </td>
</tr>
</table>