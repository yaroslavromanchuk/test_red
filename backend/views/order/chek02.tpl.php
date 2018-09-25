<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Чек</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script src="<?=$this->files;?>scripts/jquery.js" type="text/javascript" charset="utf-8"></script>
</head>
<style type="text/css">
    body {
        font-size: 10px;
    }

    .tt_border_bottom {
        border-bottom: solid 1px #000;
    }

    .tt_border_top {
        border-top: solid 1px #000;
    }

    .fnt_size_1 {
        font-size: 12px;
    }

    .fnt_size_2 {
        font-size: 11px;
    }

    .fnt_size_3 {
        font-size: 15px;
    }

    .border_all {
        border-top: 1px solid #000;
        border-left: 1px solid #000;
    }

    .border_left {
        border-left: 1px solid #000;
    }

    .border_right {
        border-right: 1px solid #000;
    }

    .border_top {
        border-top: 1px solid #000;
    }

    .border_none {
        border: none;
    }

</style>
<body onload="window.print();">

<?php
/*function toFixed($number){
    $number = explode(',',$number);
    return $number[0];
}*/

for ($step = 0; $step < 2; $step++) {
    ?>
    <table cellpadding="0" cellspacing="0" width="250">
    <tr>
    <td>
    <table border="0" cellpadding="3" cellspacing="0" width="240">
	<tr>
	<td colspan="11" style="    text-align: center;" >
	<img src="/images/barcodeimage.php?text=<?=$this->order->getId()?>" alt="Barcode Image" />
	</td>
	</tr>
    <tr>
        <td colspan="11" class="tt_border_bottom" style="text-align: center">
            <strong>Интернет-магазин «RED.UA»</strong><br/>
            http://www.red.ua  
            <strong>  E-mail: market@red.ua</strong>
            <br><span>(044) 224-40-00 (063) 809-35-29 (067) 406-90-80</span>  
        </td>
    </tr>
    <tr>
        <td colspan="6" align="center" class="fnt_size_3">
            <strong>Товарный чек
<?php
?>
                № <?php
					echo $this->order->getId();
					if ($this->order->getComlpect()) {
						$compl = explode(';', $this->order->getComlpect());
						echo ' (';
						foreach ($compl as $cmpl) {
							$cnt++;
							if ($res > '') $res .= ',';
							$res .= $cmpl;
							if ($cnt > 4) {
								$res .= '<br/>';
								$cnt = 0;
							}
						}
						echo $res;
						echo ')';
					}
?></strong>
        </td>
    </tr>
    <tr>
        <td colspan="6" align="center" class="fnt_size_1">
            <strong>от <?php echo $this->date_today ?> года</strong>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <i><strong>Получатель:</strong> <?php echo $this->order->getName() . ' ' . $this->order->getMiddleName() ?><br />
                <?php echo $this->order->getAddress()?>
            </i><br/>
            <i>Контактный телефон: <?php echo $this->order->getTelephone()?></i>

        </td>
    </tr>
    <tr>
        <td colspan="6">
            <i><strong>Коментарии к заказу:</strong>
                <?php $deliver = wsActiveRecord::useStatic('DeliveryType')->findFirst(array('id' => $this->order->getDeliveryTypeId()));
                echo $deliver->getName();?></i>
            <br/><br/>
        </td>
    </tr>
    <tr>
        <td colspan="10">
            <?php

            if ($this->getOrder()->getRemarks()->count() or $this->getOrder()->getComments()) {
                $remar = array();
                foreach ($this->getOrder()->getRemarks() as $remark) {
                    $remar[] = $remark->getRemark();
                }
                ?>
                <?php if ($this->getOrder()->getComments()) { ?>
                    <div class="comm_cli">
                        <b>Комментарий клиента :</b>
                        <?php echo $this->getOrder()->getComments(); ?>
                    </div>
                <?php } ?>
            <?php } ?>
        </td>
    </tr>

    <!-- TOVAR TITLES-->
    <tr style="font-size: 10px;">
        <td class="border_all fnt_size_1" align="center">
            <strong>№</strong>
        </td>
        <td class="border_all" align="center">
            <strong>Наименование товара</strong>
        </td>
        <td class="border_all" align="center">
            <strong>К-во (ед.)</strong>
        </td>
        <td class="border_all" align="center">
            <strong>Цена</strong>
        </td>
        <td class="border_all" align="center">
            <strong>Скидка</strong>
        </td>
        <td class="border_all border_right" align="center">
            <strong>Сумма</strong>
        </td>
    </tr>
    <!-- TOVAR -->
    <?php
    $i = 1;
	$dep = false;
    $total_price = 0;
    $to_pay = 0;
    $to_pay_minus = '0.00';
    $peresilka = Shoparticles::showPrice($this->getOrder()->getDeliveryCost());
    foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
        if ($article_rec->getCount()) {
            $art = new Shoparticles($article_rec->getArticleId());
			
           // $size = new Size($article_rec->getSize());
			
           // $color = new Shoparticlescolor($article_rec->getColor());
			
            for ($a_cnt = 0; $a_cnt < $article_rec->getCount(); $a_cnt++) {
                ?>
                <tr>
                    <td class="border_all " align="center">
                        <strong><?php echo $i ?></strong>
                    </td>
                    <td class="border_all " style="font-size:11px;">
						<b>
<?php
							echo $article_rec->getTitle() . ', ' . wsActiveRecord::useStatic('Size')->findById($article_rec->getSize())->getSize() . ', ' . wsActiveRecord::useStatic('Shoparticlescolor')->findById($article_rec->getColor())->getName();
?>
							<br/>
							<?php echo $article_rec->getCode(); ?>
							<br/>
							<font style="font-size:10px;">
<?php
								$sql ="
									SELECT DISTINCT
										CASE WHEN w4.name IS NOT NULL
											THEN w4.name
											ELSE CASE WHEN w3.name IS NOT NULL
												THEN w3.name
												ELSE CASE WHEN w2.name IS NOT NULL
													THEN w2.name
													ELSE w1.name
												END
											END
										END ct
									FROM
										ws_articles art
										LEFT JOIN ws_categories w1
										ON w1.id = art.category_id
										LEFT JOIN ws_categories w2
										ON w2.id = w1.parent_id
										LEFT JOIN ws_categories w3
										ON w3.id = w2.parent_id
										LEFT JOIN ws_categories w4
										ON w4.id = w3.parent_id
									WHERE
										art.id = ".$article_rec->getArticleId()."
									ORDER BY
										ct ASC
								";
								//$category = wsActiveRecord::findByQueryArray($sql);
							//	echo $category[0]->ct;
							echo $article_rec->article_db->category->getRoutez();
?>
							</font>
						</b>
                    </td>
                    <td class="border_all" align="center">
                        <strong>1</strong>
                    </td>
                    <td class="border_all " align="right">
<?php
$price_real = (int)$article_rec->getOldPrice() ? $article_rec->getOldPrice() : $article_rec->getPrice();
		$t_real_price += $price_real * $article_rec->getCount();
	
		$price_show = $article_rec->getPerc($this->order->getAllAmount());
							$sum_skudka += $price_show['minus'];		
		if($article_rec->getCount() > 0){
					$skid_show = round((1 - (($price_show['price']/$article_rec->getCount())/ $price_real)) * 100);
					}
					
 echo Number::formatFloat($price_real, 2); ?>
                    </td>
                    <td class="border_all " align="right">
                        <?php
						//$
                        if ((float)$article_rec->getOldPrice()) {
    $skidka_t = (1 - ((float)$article_rec->getPrice() / ((float)$article_rec->getOldPrice() ? (float)$article_rec->getOldPrice() : 1))) * 100;
                        } else $skidka_t = $this->getOrder()->getDiscont();
						$skidka_t = round($skidka_t, 0);
						
                      /*  if ($skidka_t > 80) $skidka_t = round($skidka_t, 0);
                        elseif ($skidka_t >= 60) $skidka_t = round($skidka_t, 0);
                        elseif ($skidka_t >= 48) $skidka_t = 50;
                        elseif ($skidka_t >= 43) $skidka_t = 45;
						elseif ($skidka_t >= 38) $skidka_t = 40;
						elseif ($skidka_t >= 33) $skidka_t = 35;
						elseif ($skidka_t >= 28) $skidka_t = 30;
						elseif ($skidka_t >= 23) $skidka_t = 25;
						elseif ($skidka_t >= 18) $skidka_t = 20;
						elseif ($skidka_t >= 13) $skidka_t = 15;
						elseif ($skidka_t >= 8) $skidka_t = 10;
						elseif ($skidka_t >= 4) $skidka_t = 5;*/
						
						if(@$article_rec->getEventSkidka()){
						$c = wsActiveRecord::useStatic('Skidki')->findFirst(array('id'=>$article_rec->event_id));
						if($c){ $name = htmlspecialchars($c->name_a);}else{$name = '';}
						
						
						$pr = $article_rec->getPrice();
						if($article_rec->getOldPrice() > 0) $pr = $article_rec->getOldPrice();
						$ev_price = $article_rec->getPrice() * (1 - ($article_rec->getEventSkidka() / 100)) * 1;
						$skidka_t = (1 - ($ev_price / $pr)) * 100;
						$skidka_t = '<span>'.round($skidka_t, 2).' %</span><br><span style="color: red;font-size: 7px;">'.$name.'</span>';
						}else{
						$skidka_t = '<span>'.$skidka_t.' %</span>';
						}
						
						
                //echo $skidka_t;
				 if ($article_rec->getCount() > 0) { echo $skid_show ? '<span>'.$skid_show.'%</span>' : ''; }
				
				?>
                    </td>
                    <td class="border_all border_right"
                        align="right">
                        <strong><?php echo Number::formatFloat($price_show['price']); ?></strong>
						
						<?php if($this->order->getDiscont() > 0 and false) {
						if((int)$article_rec->getOldPrice() == 0){
						$z =  $article_rec->getPrice() * (1 - ($this->order->getDiscont() / 100)) * 1;
						$z =  $z * (1 - ($this->getOrder()->getKuponPrice() / 100)) * 1; 
						}else{
if($this->getOrder()->getKuponPrice() > 0) {
$z =  $article_rec->getPrice() * (1 - ($this->getOrder()->getKuponPrice() / 100)) * 1; 
}
}
						echo '<span style="color: red;font-size: 9px;text-decoration: underline;">'.$z.'%</span>';
						
						} ?>
                    </td>
                </tr>


                <?php
                $i++;
            }
           // $total_price += $article_rec->getPrice() * (1 - ($article_rec->getEventSkidka() / 100)) * $article_rec->getCount(); // обшая сумма

            $price = $article_rec->getPerc($this->all_orders_amount); // цена товара с кидкой

            $to_pay += $price['price'];
            $to_pay_minus += $price['minus'];

        }
    }

    $kop = round(($to_pay - toFixed($to_pay)) * 100, 0);
    ?>
    <tr>
        <td colspan="2" class="tt_border_top" align="right"></td>
        <td colspan="2" class="tt_border_top" align="right">
            <strong>Итого:</strong>
        </td>
        <td class="border_left border_right tt_border_top" align="right" colspan="2">
            <strong><?php echo Number::formatFloat($t_real_price); ?></strong>
        </td>
    </tr>
    <?php //if (!($this->getOrder()->getKuponPrice() > 0)) {
       // $ykupon = false; ?>
        <tr>
            <td colspan="2" align="right"></td>
            <td colspan="2" align="right">
                <i>Скидка клиента</i>
            </td>
            <td class="border_all border_right" align="right" colspan="2">
                <i><?php echo $this->order->getDiscont(); ?> %</i>
            </td>
            <td>*</td>
        </tr>
		    <?php
      if($this->getOrder()->getKuponPrice() > 0) {
        $ykupon = true; ?>
        <tr>
            <td colspan="1" align="right"></td>
            <td colspan="3" align="right">
                <i style="font-weight: bold;">Код на скидку</i>
            </td>
            <td class="border_all border_right" align="right" colspan="2">
                <i style="font-weight: bold;"><?php echo Number::formatFloat($this->getOrder()->getKupon(), 2) ?></i>
            </td>

        </tr>
        <tr>
            <td colspan="1" align="right"></td>
            <td colspan="3" align="right">
                <i style="font-weight: bold;">Скидка по коду</i>
            </td>
            <td class="border_all border_right" align="right" colspan="2">
                <i style="font-weight: bold;"><?php echo Number::formatFloat($this->getOrder()->getKuponPrice(), 2) ?>%</i>
            </td>

        </tr>
    <?php } ?>
        <tr>
            <td colspan="1" align="right"></td>
            <td colspan="3" align="right">
                <i>Сумма скидки</i>
            </td>
            <td class="border_all border_right" align="right" colspan="2">
                <i><?php echo Number::formatFloat($to_pay_minus, 2) ?></i>
            </td>

        </tr>
		<?php if($this->getOrder()->getBonus() > 0 and $to_pay >= 200){?>
		<tr>
            <td colspan="1" align="right"></td>
            <td colspan="3" align="right">
                <i>Бонусная скидка</i>
            </td>
            <td class="border_all border_right" align="right" colspan="2">
                <i><?php echo Number::formatFloat($this->getOrder()->getBonus(), 2) ?></i>
            </td>

        </tr>
		<?php } ?>
        <tr>
            <td colspan="1" align="right"></td>
            <td colspan="3" align="right">
                <i>Стоимость с учетом скидок</i>
            </td>
            <td class="border_all border_right" align="right" colspan="2">
                <i><?php
					if($to_pay > 200 and $this->getOrder()->getBonus() > 0){
					echo Number::formatFloat($to_pay - $this->getOrder()->getBonus(), 2);
					}else{ echo Number::formatFloat($to_pay, 2);} ?></i>
            </td>

        </tr>

 
    <tr>
        <td colspan="2"></td>
        <td colspan="2" align="right" class="fnt_size_1">
            Доставка:
        </td>
        <td class="border_all border_right" align="right" colspan="2">
            <?php echo Number::formatFloat($peresilka, 2);?>
        </td>
    </tr>
    <?php  if ($this->getOrder()->getDeposit() > 0) { $dep = true; ?>
        <tr>
            <td colspan="2" align="right"></td>
            <td colspan="2" align="right">
                <i>Депозит</i>
            </td>
            <td class="border_all border_right" align="right" colspan="2">
                <i><?php echo Number::formatFloat($this->getOrder()->getDeposit(), 2) ?></i>
            </td>

        </tr>
    <?php } ?>

  <?php $resultp = $dep ? ($to_pay + $peresilka - $this->getOrder()->getDeposit()) : $to_pay + $peresilka;
 // $resultp = $to_pay;
 if($this->getOrder()->getDeliveryTypeId() == 9) $resultp = $resultp - $peresilka;
	if($this->getOrder()->getBonus() > 0 and $resultp >=200) $resultp = $resultp - $this->getOrder()->getBonus();// else $resultp =  $resultp;
		$resultp = $resultp > 0 ? $resultp + $peresilka : '0' ; ?>

    <tr>
        <td colspan="1" align="right"></td>
        <td colspan="3" class="border_none fnt_size_1" align="right">
            <strong style="text-transform: uppercase;">Всего к оплате:</strong>
            <br/>
            <span style="font-size: 12px;">Без ПДВ</span>
        </td>
        <td class="border_all border_right tt_border_bottom" align="right" colspan="2">
            <strong> <?php echo Number::formatFloat($resultp, 2) ?></strong>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <br/>
            <i>Всего наименований <strong><?php echo($i - 1) ?></strong>, на сумму <strong>
                    <?php $sum = Number::formatFloat($resultp, 2);
                    $sum = explode(',', $sum);
                    echo $sum[0];
                    ?> грн.
                    <?php echo @$sum[1] ? @$sum[1] : '00' ?> коп.
                    (<?php echo Plural::currency(Number::formatFloat($resultp, 2), $kop); /* echo $kop ? '' : ' коп.';*/ ?>
                    )</strong></i>

            <br/><br/>
        </td>
        <td>
            <?php

            if ($this->getOrder()->getRemarks()->count() or $this->getOrder()->getComments()) {
                $remar = array();
                foreach ($this->getOrder()->getRemarks() as $remark) {
                    $remar[] = $remark->getRemark();
                }
                ?>
                <?php if ($this->getOrder()->getRemarks()->count()) { ?>
                    <div style="margin-top: -25px; padding-left: 25px;">◘</div>
                <?php } ?>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="tt_border_bottom">
            <b>Удачных покупок!</b>
        </td>
    </tr>
    <tr>
        <td colspan="6" style=" font-size: 10px; padding-bottom: 30px;">
            Персональные данные (ФИО и адрес) Покупателя были переданы интернет-магазину RED.ua, с целью выполнения данного
            заказа Покупателя, и в дальнейшем могут передаваться уполномоченным органам в установленном законом порядке.
            Как субъект персональных данных Покупатель имеет все права, предусмотренные ст. 8 Закона Украины «О защите
            персональных данных».
        </td>
    </tr>
    </table>
    </td>

    </table>
    <div style='page-break-after: always;'></div>
<?php } ?>
</body>
</html>