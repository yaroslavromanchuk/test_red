<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Чек</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script src="<?=$this->files;?>scripts/jquery.js" type="text/javascript" charset="utf-8"></script>
</head>
<style type="text/css">
    body {font-size: 10px;}
	.tt_border_bottom {border-bottom: solid 1px #000;}
    .tt_border_top {border-top: solid 1px #000;}
    .fnt_size_1 {font-size: 12px;}
    .fnt_size_2 {font-size: 11px;}
    .fnt_size_3 {font-size: 15px;}
    .border_all {border-top: 1px solid #000;border-left: 1px solid #000;}
    .border_left {border-left: 1px solid #000;}
    .border_right {border-right: 1px solid #000;}
    .border_top {border-top: 1px solid #000;}
    .border_none {border: none;}
</style>
<body onload="window.print();">
<?php
for ($step = 0; $step < 2; $step++) {
    ?>
    <table border="0" cellpadding="3" cellspacing="0" width="250">
	<tr>
	<td colspan="6" style="text-align: center;" >
	<img src="/images/barcodeimage.php?text=<?=$this->order->getId()?>" alt="Barcode Image" />
	</td>
	</tr>
    <tr>
        <td colspan="6" class="tt_border_bottom" style="text-align: center">
            <strong>Интернет-магазин «RED.UA»</strong><br/>https://www.red.ua  
            <strong>  E-mail: market@red.ua</strong><br><span>(044) 224-40-00, (063) 809-35-29, (067) 406-90-80</span>  
        </td>
    </tr>
    <tr>
        <td colspan="6" align="center" class="fnt_size_3">
            <strong>Товарный чек
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
        <td colspan="6" align="center" class="fnt_size_1"><strong>от <?=$this->date_today?> года</strong></td>
    </tr>
    <tr>
        <td colspan="6"><i><strong>Получатель:</strong> <?=$this->order->getName() . ' ' . $this->order->getMiddleName()?><br />
                <?=$this->order->getAddress()?>
            </i><br/>
            <i>Контактный телефон: <?=$this->order->getTelephone()?></i>
        </td>
    </tr>
    <tr>
        <td colspan="6"><i><strong>Коментарии к заказу:</strong><?=$this->order->getDeliveryType()->getName()?></i></td>
    </tr>
    <tr>
        <td colspan="6">
                <?php if ($this->getOrder()->getComments()) { ?>
                    <div class="comm_cli">
                        <b>Комментарий клиента :</b>
                        <?=$this->getOrder()->getComments()?>
                    </div>
                <?php } ?>
        </td>
    </tr>
    <!-- TOVAR TITLES-->
    <tr>
        <td class="border_all fnt_size_1" align="center"><strong>№</strong></td>
        <td class="border_all" align="center"><strong>Наименование товара</strong></td>
        <td class="border_all" align="center"><strong>К-во (ед.)</strong></td>
        <td class="border_all" align="center"><strong>Цена</strong></td>
        <td class="border_all" align="center"><strong>Скидка</strong></td>
        <td class="border_all border_right" align="center"><strong>Сумма</strong></td>
    </tr>
    <!-- TOVAR -->
    <?php
    $i = 1;
    $to_pay = 0;
    $to_pay_minus = 0.00;
	$price_real = 0;
	$t_real_price = 0;
	$price_show = 0;
    foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
        if ($article_rec->getCount()) {
            for ($a_cnt = 0; $a_cnt < $article_rec->getCount(); $a_cnt++) { ?>
                <tr>
                    <td class="border_all " align="center"><strong><?=$i?></strong></td>
                    <td class="border_all " style="font-size:10px;">
						<b>
<?php echo $article_rec->getTitle() . ', <br>' . wsActiveRecord::useStatic('Size')->findById($article_rec->getSize())->getSize() . ', ' . wsActiveRecord::useStatic('Shoparticlescolor')->findById($article_rec->getColor())->getName();?>
							<br>
							<?=$article_rec->getCode()?>
							<br>
							<font style="font-size:10px;"><?=$article_rec->article_db->category->getRoutez()?></font>
						</b>
                    </td>
                    <td class="border_all" align="center"><strong>1</strong></td>
                    <td class="border_all " align="right">
<?php
$price_real = (int)$article_rec->getOldPrice() ? $article_rec->getOldPrice() : $article_rec->getPrice();//стартовая цена
		$t_real_price += $price_real;//сумируется стартовая цена
	
		$price_show = $article_rec->getPerc($this->order->getAllAmount());//вычисление цены и скидка на товар
		
			$sum_skudka += $price_show['minus']/$article_rec->getCount(); //сумируется общая скидка
			
				$skid_show = round((1 - (($price_show['price']/$article_rec->getCount())/ $price_real)) * 100);//вычисление процента скидки по товару
					
 echo Number::formatFloat($price_real, 2); ?>
                    </td>
                    <td class="border_all " align="right">
                        <?php
$st = (int)$article_rec->getOldPrice() ? 'style="color:red;font-size:12px;"' : 'style="font-size:12px;text-decoration: underline;"';//уценялся
	if((int)$article_rec->getEventSkidka()){ $st = 'style="color:red;font-size:12px;font-weight: 600;"';}//наличие доп скидки
					
echo $skid_show ? '<span '.$st.'>'.$skid_show.' %</span>' : '';?>
                    </td>
                    <td class="border_all border_right" align="right">
<strong><?=Number::formatFloat($article_rec->getCount() > 1 ? $price_show['price']/$article_rec->getCount() : $price_show['price']); ?></strong>
                    </td>
                </tr>
                <?php
                $i++;
            }
        }
    }
	
	if($this->getOrder()->getBonus() > 0){ $bonus = true; }else{ $bonus = false; }
	
    $to_pay = $this->getOrder()->calculateOrderPrice2(true, true, true, $bonus); //общая сумма к оплате
	
	$to_pay_minus = $sum_skudka;//общая скидка
	
    $kop = round(($to_pay - toFixed($to_pay)) * 100, 0);//
    ?>
    <tr>
        <td colspan="4" class="tt_border_top" align="right"><strong>Итого:</strong></td>
        <td class="border_left border_right tt_border_top" align="right" colspan="2"><strong><?=Number::formatFloat($t_real_price, 2)?></strong></td>
    </tr>
    <tr>
        <td colspan="4" align="right"><i>Скидка клиента</i></td>
        <td class="border_all border_right" align="right" colspan="2"><i><?=$this->order->getDiscont()?> %*</i></td>
    </tr>
		    <?php
      if($this->getOrder()->getKuponPrice() > 0) {?>
     <tr>
        <td colspan="4" align="right"><i style="font-weight: bold;">Код на скидку</i></td>
        <td class="border_all border_right" align="right" colspan="2"><i style="font-weight: bold;"><?=$this->getOrder()->getKupon()?></i></td>
	</tr>
    <tr>
		<td colspan="4" align="right"><i style="font-weight: bold;">Скидка по коду</i></td>
        <td class="border_all border_right" align="right" colspan="2"><i style="font-weight: bold;"><?=$this->getOrder()->getKuponPrice()?> %</i></td>
	</tr>
    <?php } ?>
	<tr>
		<td colspan="4" align="right"><i>Сумма общей скидки</i></td>
		<td class="border_all border_right" align="right" colspan="2"><i><?=Number::formatFloat($to_pay_minus, 2)?></i></td>
	</tr>
		<?php
		$min_sum_bonus = Config::findByCode('min_sum_bonus')->getValue();
		if($this->getOrder()->getBonus() > 0 and $to_pay >= $min_sum_bonus){?>
	<tr>
		<td colspan="4" align="right"><i>Бонусная скидка</i></td>
		<td class="border_all border_right" align="right" colspan="2"><i><?=Number::formatFloat($this->getOrder()->getBonus(), 2)?></i></td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="4" align="right"><i>Стоимость с учетом скидок</i></td>
		<td class="border_all border_right" align="right" colspan="2"><i>
		<?php
		if($this->getOrder()->getDeliveryCost() > 0 or $this->getOrder()->getDeposit() > 0) {
		echo $this->getOrder()->calculateOrderPrice2(false, true, false, $bonus);
		}else{ echo $to_pay;}
		?>
		</i>
        </td>
        </tr>
    <tr>
		<td colspan="4" align="right" class="fnt_size_1">Доставка:</td>
        <td class="border_all border_right" align="right" colspan="2"><?=Number::formatFloat($this->getOrder()->getDeliveryCost(), 2);?></td>
    </tr>
    <?php  if ($this->getOrder()->getDeposit() > 0) { ?>
	<tr>
		<td colspan="4" align="right"><i>Депозит</i></td>
		<td class="border_all border_right" align="right" colspan="2"><i><?=Number::formatFloat($this->getOrder()->getDeposit(), 2)?></i></td>
	</tr>
    <?php } ?>
    <tr>
		<td colspan="4" class="border_none fnt_size_1" align="right"><strong style="text-transform: uppercase;">Всего к оплате:</strong><br/>
            <span style="font-size: 12px;">Без ПДВ</span>
        </td>
        <td class="border_all border_right tt_border_bottom" align="right" colspan="2"><strong><?=$to_pay?></strong></td>
    </tr>
    <tr>
		<td colspan="6">
            <br/>
            <i>Всего наименований <strong><?php echo($i - 1) ?></strong>, на сумму <strong>
                    <?php $sum = explode(',', $to_pay); echo $sum[0];?> грн.
                    <?php echo @$sum[1] ? @$sum[1] : '00' ?> коп.
                    (<?=Plural::currency($to_pay, $kop)?>)</strong></i>
            <br/>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="tt_border_bottom"><b>Удачных покупок!</b></td>
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
    <div style='page-break-after: always;'></div>
<?php } ?>
</body>
</html>