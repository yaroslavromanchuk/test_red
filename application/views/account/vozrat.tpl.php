<h1 class="green"><?php echo $this->curMenu->getTitle(); ?></h1>
<form action="" method="post" id="vozrat_form">
    <?

    $status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
    $order = $this->order;

    ?>
    <h2>Заказ за <?=date('d-m-Y', strtotime($order->getDateCreate()))?>, №: <?=$order->getId()?></h2>
    <h4><span style="color: #4F4F51;">Статус заказа:</span> <?=$status[$order->status]?> </h4>
    <?php $t_price = 0.00;
    $t_count = 0;
    $total_price = 0.00;
    $to_pay_perc = 0;?>
    <table cellspacing="0" cellpadding="0" style="width: 100%;" class="basket">
        <tbody>
        <tr>
            <th class="r_bord"></th>
            <th>цвет</th>
            <th>размер</th>
            <th>количество</th>
            <th class="r_bord">сумма</th>
            <th></th>
        </tr>
        <tr></tr>
        <?php
        $total_price = 0;
        $to_pay = 0;
        $peresilka = wsActiveRecord::useStatic('DeliveryType')->findFirst(array('id' => 4));
        $peresilka = $peresilka->getPrice();
        if ($order->getPaymentMethodId() != 3) {
            $peresilka = 0;
        }
        $to_pay_minus = '0.00';
        foreach ($order->getArticles() as $article) {
            $art = new Shoparticles($article->getArticleId());
            ?>

        <tr>
            <td width="160" class="r_bord b_bord"><img alt="<?php echo htmlspecialchars($article->getTitle()); ?>"
                                                       src="<?php echo $article->getImagePath('listing'); ?>"/> <br/>
                <?php echo $article->getTitle(); ?> <br/>
                <a
                        href="<?=$article->getArticleDb()->getPath()?>"><?php echo $this->trans->get('подробнее');?></a>
            </td>
            <td class="b_bord"><?=wsActiveRecord::useStatic('Shoparticlescolor')->findById($article['color'])->getName()?></td>
            <td class="b_bord"><?=wsActiveRecord::useStatic('Size')->findById($article['size'])->getSize()?></td>
            <td class="b_bord">
                <?php $t_count += $article['count']; echo $article['count']; ?>

            </td>
            <td class="r_bord b_bord">
                <?php $t_price += $article['price'] * $article['count']; echo Shoparticles::showPrice($article['price'] * $article['count']); ?>
                грн
            </td>
            <td width="140" class="b_bord">
                <div class="price-cart">
                    <?php if (!ShoporderarticlesVozrat::isArticleVozvat($article->getId())) { ?>
                    Вернуть <input type="checkbox" name="artice_<?php echo $article->getId()?>" value="1"/> <br/>
                    Количество <select name="count_<?php echo $article->getId()?>">
                        <?php for ($i = 1; $i <= $article['count']; $i++) { ?>
                        <option value="<?php echo $i?>"><?php echo $i?></option>
                        <?php } ?>
                    </select>
                    <?php } else { ?>
                    Отправлен на возврат.
                    <?php } ?>
                </div>
            </td>
        </tr>
            <?php
            $to_pay_perc = $article->getProcent($this->all_orders_amount); // процент скидки
            $total_price += $article->getPrice() * $article->getCount(); // обшая сумма
            $price = $article->getPerc(); // цена товара с кидкой
            $to_pay += $price['price'];
            $to_pay_minus += $price['minus'];

        }
        $tprice = $total_price - $to_pay_minus + $peresilka;
        ?>
        <tr>
            <td colspan="5" style="border-right: 1px solid #BFBFBF;">
                <table style="width: 100%;">
                    <tr>
                        <td><b>Способ возврата денег</b></td>
                        <td style="text-align: left;">
                            <input type="radio" value="1" checked="checked" name="sposob"> на депозит<br/>
                            <input type="radio" value="2" name="sposob"> денежным переводом
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;" colspan="2">
                            <br/>
                            Заявление на возврат товара: <a href="http://www.red.ua/storage/zayavlenie-vozvrat1.doc">Скачать</a>
                            <br/>
                        </td>
                    </tr>
                    <tr>
                        <td>Коментарий</td>
                        <td style="text-align: left;">
                            <textarea style="width: 200px; height: 50px;" name="comments"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">

                            <br/>
                            <a onclick="$('#vozrat_form').submit(); return false;" href="#"
                               class="next-step new_button">Возврат товара</a>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table cellpadding="0" cellspacing="0" style="width: 100%">
                    <tr>
                        <td style=" border-bottom: 1px solid #BFBFBF; padding: 5px;">
                            Сумма заказа:<br/>
                            <?php echo Number::formatFloat($total_price)?>  грн.
                        </td>
                    </tr>
                    <tr>
                        <td style=" border-bottom: 1px solid #BFBFBF;  padding: 5px;">
                            Сумма доставки:<br/>
                            <?php echo Number::formatFloat($peresilka, 2);?> грн.
                        </td>
                    </tr>
                    <tr>
                        <td style=" border-bottom: 1px solid #BFBFBF;  padding: 5px;">
                       <span style="color: #fe0000"> Скидка:<br/>
                           <?php echo $to_pay_perc;?>
                           </span>
                        </td>
                    </tr>
                    <tr>
                        <td style=" padding: 5px;">
                            <h4>
                                Всего:<br/>         <?php echo Number::formatFloat(($tprice - $order->getDeposit()), 2);?>
                                грн.</h4>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</form>
		
