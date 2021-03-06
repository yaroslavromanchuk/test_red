<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Счет Магазин</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
	<meta name="robot" content="no-index,no-follow"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script src="<?=$this->files?>scripts/jquery.js"  charset="utf-8"></script>
</head> 
<style>
	body {font-size: 10px;font-family:  verdana;}
    .tt_border_bottom {border-bottom: solid 2px #000;}
    .fnt_size_1 {font-size: 14px;}
    .fnt_size_2 {font-size: 13px;}
    .fnt_size_3 {font-size: 18px;}
    .border_all {border-top: 2px solid #000;border-left: 2px solid #000;}
    .border_left {border-left: 2px solid #000;}
    .border_right {border-right: 2px solid #000;}
    .border_top {border-top: 2px solid #000;}
    .border_none {border: none;}
	.border{ border: 1px solid black;}
</style>
<body onload="window.print()" style="width: 700px; margin: auto">
    <table border="0" cellpadding="3" cellspacing="0" >
    <tr>
        <td colspan="10" class="tt_border_bottom">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
					 <td align="left" valign="top">
                        <img src="/img/logo/RED_Logo_RGB.png" style="padding-bottom: 5px;width: 100px;" alt=""/>
                    </td>
                    <td align="left" valign="top" style="padding-top: 5px;">
        <span style="color: #FA0000; font-size: 14px; font-style: italic;  letter-spacing: -1px;">
            Большие бренды - маленькие цены!
        </span><br>
		<span>(044) 224-40-00 | (063) 809-35-29 | (067) 406-90-80</span>
                    </td>
					<td style="text-align:center;">
					<strong>Интернет-магазин «RED.UA»</strong><br>
					 https://www.red.ua<br>
					  <strong>E-mail: market@red.ua</strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" rowspan="2"><img src="/images/barcodeimage.php?text=<?=$this->order->getId()?>" alt="Barcode Image" /></td>
        <td colspan="6" align="center" class="fnt_size_3">
        <strong>Товарный чек № <?=$this->order->id?></strong>
	<?=$this->order->getComlpect()?'<br><span>('.substr(implode(explode(";", $this->order->getComlpect()), ', '), 0, -2).')</span><br>':''?>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td colspan="6" align="center" class="fnt_size_1">
            <strong>от <?=$this->date_today?> года</strong>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td colspan="10">
            <i><strong>Получатель:</strong> <?php echo $this->order->getName(). ' '. $this->order->getMiddleName() ?>
            </i>

        </td>
    </tr>
    <tr>
        <td colspan="10"><i><strong>Коментарии к заказу:</strong><?=$this->order->getDeliveryType()->getName()?></i></td>
    </tr>
<?php if ($this->getOrder()->getComments()) { ?>
	<tr>
		<td colspan="10"><div class="comm_cli"><b>Комментарий клиента :</b><?=$this->getOrder()->getComments()?></div></td>
    </tr>
<?php } ?>
    <tr>
        <td colspan="7">
            <p>
                <i>Условия оплаты и возврата заказа:</i><br/>
                - дисконтная скидка розничных магазинов RED недействительна для интернет-заказов;<br/>
                - примерить товар можно только после оплаты полной стоимости заказа;<br/>
                - возврат товара до 14 дней с момента покупки. Условия возврата https://www.red.ua/returns/.<br/><br/>
                <b>Удачных покупок!</b>
            </p>
        </td>
	<td colspan="3"><p  class="qr<?=$this->order->getId()?>"></p>
        </td>
    </tr>
    <!-- TOVAR TITLES-->
    <tr >
        <td class="border_all fnt_size_1" align="center" width="25" rowspan="2">
            <strong>№</strong>
        </td>
        <td class="border_all" align="center" width="100" rowspan="2">
            <strong>Наименование товара</strong>
        </td>
        <td class="border_all" align="center" colspan="2" width="25">
            <strong>Коментарии кассира **</strong>
        </td>
        <td class="border_all" align="center" width="250" rowspan="2">
            <strong>КОД</strong>
        </td>
        <td class="border_all" align="center" width="25" rowspan="2">
            <strong>К-во (ед.)</strong>
        </td>
        <td class="border_all" align="center" width="35" rowspan="2">
            <strong>Старая Цена</strong>
        </td>
        <td class="border_all" align="center" width="30" rowspan="2">
            <strong>Скидка</strong>
        </td>
        <td class="border_all" align="center" width="35" rowspan="2">
            <strong>Новая Цена</strong>
        </td>
        <td class="border_all border_right" align="center" width="35" rowspan="2">
            <strong>Сумма</strong>
        </td>
    </tr>
    <tr>
        <td class="border_all" align="center">К</td>
        <td class="border_all" align="center">В</td>
    </tr>
    <!-- TOVAR -->
    <?php
    $i = 1;
    $c = $this->getOrder()->getSkuCount();
   // $total_price = 0;

	
	$price_real = 0;
	$t_real_price = 0;
	$price_show = 0;
	$t_minus = 0;
	$sk = 0;
	//$cod ='%';
       $cod ='%ID'.$this->order->customer->id.'&ORDER'.$this->order->id.'&';
        if($this->order->deposit > 0){
           $cod.='DEPO'.Number::formatFloat($this->order->deposit).'&'; 
        }
        if($this->order->bonus > 0){
            $cod.='COIN'.Number::formatFloat($this->order->bonus).'&'; 
        }
	
    foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
	if($article_rec->getCount() > 0){
	
			$price_real = (int)$article_rec->getOldPrice() ? $article_rec->getOldPrice() : $article_rec->getPrice();//стартовая цена
		//$t_real_price += $price_real;//сумируется стартовая цена
	
		$price_show = $article_rec->getPerc($this->order->getAllAmount());//вычисление цены и скидка на товар
                
		$t_real_price +=$price_show['price'];// общая сумма заказа
			//$sum_skudka += $price_show['minus']/$article_rec->getCount(); //сумируется общая скидка
			
				$skid_show = round(((1 - (($price_show['price']/$article_rec->getCount())/ $price_real)) * 100), 2);//вычисление процента скидки по товару
					
                                $sk = Number::formatFloat($skid_show, 2);
		
				//$z = chr(10);
		$cod.=$article_rec->getCode().'/'.$article_rec->getCount().'/'.$sk.'&';
		
        ?>
        <tr>
            <td class="border_all <?php echo ($i == $c) ? 'tt_border_bottom' : '' ?>" align="center">
                <strong><?=$i?></strong>
            </td>
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : '' ?>"><b>
                    <?php echo $article_rec->getTitle() . ', <br>' . wsActiveRecord::useStatic('Size')->findById($article_rec->getSize())->getSize() . ', <br>' . wsActiveRecord::useStatic('Shoparticlescolor')->findById($article_rec->getColor())->getName();?></b>
            </td>
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : '' ?>"></td>
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : '' ?>"></td>
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : '' ?>" align="center" >
				<?php if($article_rec->getCount()) {?>
				<img src="/images/barcodeimage.php?text=<?=$article_rec->getCode()?>" alt="Barcode Image" />
				<br><?=$article_rec->getCode()?>
				<?php } ?>
            </td>
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : '' ?>" align="center">
                <strong><?=$article_rec->getCount()?></strong>
            </td>
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : '' ?>" align="right">
                <?=Number::formatFloat($price_real, 2);?>
            </td>
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : '' ?>" align="right">
                        <?php
$st = (int)$article_rec->getOldPrice() ? 'style="color:red;font-size:12px;"' : 'style="font-size:12px;text-decoration: underline;"';//уценялся
	if((int)$article_rec->getEventSkidka()){ $st = 'style="color:red;font-size:12px;font-weight: 600;"';}//наличие доп скидки
					
echo $skid_show ? '<span '.$st.'>'.ceil($skid_show).' %</span>' : '';
                 if ($article_rec->getEventSkidka()>=5 and false) {
					$s = wsActiveRecord::useStatic('Skidki')->findFirst(array('id'=>$article_rec->event_id));
						if($s){ $name = htmlspecialchars($s->name_a);}else{$name = '';}
				echo '<br><span style="color: red;font-size: 8px;">+'.$article_rec->getEventSkidka().'%</span><br><span style="color: red;font-size: 7px;">'.$name.'</span>';} 
				?>
            </td>
            <td class="border_all<?php echo($i == $c) ? ' tt_border_bottom' : '' ?>" align="right">
                <?=Number::formatFloat($article_rec->getCount() > 1 ? $price_show['price']/$article_rec->getCount() : $price_show['price']); ?>
            </td>
            <td class="border_all border_right<?php echo($i == $c) ? ' tt_border_bottom' : '' ?>" align="right">
                <strong><?=Number::formatFloat($price_show['price'], 2)?></strong>
            </td>
        </tr>
        <?php
		
        $i++;
        $t_minus+=$price_show['minus'];
		}
    }

    //$to_pay = $this->getOrder()->calculateOrderPrice2(true, true); //общая сумма к оплате
   $to_pay = Number::formatFloat($this->order->amount, 2);
  // $t_minus = $price_show['minus'];$t_real_price - ($this->getOrder()->getAmount()-$this->getOrder()->dop_summa-$this->getOrder()->getDeliveryCost());//общая скидка
    $kop = round(($to_pay - toFixed($to_pay)) * 100, 0);
    ?>
    <tr>
        <td colspan="2" width="125" style="border: 2px solid #000000; border-top: none; text-align: center;"><b>Заполняет касcир</b></td>
        <td colspan="2"></td>
        <td colspan="4" align="right">
            <strong>Всего: </strong>
        </td>
        <td class="border_left border_right" align="right" colspan="2">
            <strong><?=Number::formatFloat($t_real_price, 2)?></strong>
        </td>
    </tr>
	<!--<tr>
			<td colspan="2" style=" border: 2px solid #000000; border-top: none;"><b>ФИО кассира:</b></td>
			<td colspan="2">**</td>
			<td colspan="4" align="right"><i>Скидка клиента</i></td>
			<td class="border_all border_right" align="right" colspan="2"><i><?php //$this->order->getDiscont();?> %*</i></td>
	</tr>-->
		<?php if($this->getOrder()->getKuponPrice() > 0){ ?> 
	<tr>
		<td colspan="2" style=" border: 2px solid #000000; border-top: none;"></td>
		<td colspan="6" align="right"><i style="font-weight: bold;">Код на скидку</i></td>
		<td class="border_all border_right" align="right" colspan="2"><i style="font-weight: bold;"><?=$this->getOrder()->getKupon()?></i></td>
	</tr>
	<tr>
		<td colspan="2" style=" border: 2px solid #000000; border-top: none;"></td>
		<td colspan="6" align="right"><i style="font-weight: bold;">Скидка по коду</i></td>
		<td class="border_all border_right" align="right" colspan="2"><i style="font-weight: bold;"><?=Number::formatFloat($this->getOrder()->getKuponPrice(), 2)?> %</i></td>
		</tr>
    <?php } ?>
	<!--<tr>
		<td colspan="2" style=" border: 2px solid #000000; border-top: none;"><b>Дата:</b></td>
			<td colspan="2">**</td>
			<td colspan="4" align="right"><i>Сумма общей скидки</i></td>
			<td class="border_all border_right" align="right" colspan="2"><i><?php //Number::formatFloat($t_minus, 2)?></i></td>
	</tr>-->
<?php
		if($this->getOrder()->getBonus() > 0){ ?>
		<tr>
            <td colspan="2" style=" border: 2px solid #000000; border-top: none;"></td>
			<td colspan="2"></td>
			<td colspan="4" align="right"><i>Бонусная скидка</i></td>
            <td class="border_all border_right" align="right" colspan="2">
                <i><?=Number::formatFloat($this->getOrder()->getBonus(), 2)?></i>
            </td>
        </tr>
		<?php } ?>
		<tr>
			<td colspan="2" style=" border: 2px solid #000000; border-top: none;"><b>Действителен до:</b></td>
			<td colspan="2"> <?php echo '<b>'.date('d.m.Y', mktime(0, 0, 0, date("m"), date("d", time()) + 5, date("Y"))).'</b>';?></td>
			<td colspan="4" align="right">
				<i>Стоимость с учетом скидок</i>
			</td>
			<td class="border_all border_right" align="right" colspan="2">
                            <i><?=Number::formatFloat(($this->order->calculateOrderPrice2(true, true, false)), 2)?></i>
			</td>
		</tr>
    <?php if ($this->order->deposit > 0) { ?>
        <tr>
            <td colspan="2" style=" border: 2px solid #000000; border-top: none;"></td>
            <td colspan="2"></td>
            <td colspan="4" align="right"><i>Депозит</i></td>
            <td class="border_all border_right" align="right" colspan="2">
                <i><?php echo Number::formatFloat($this->order->deposit, 2)?></i>
            </td>
        </tr>
    <?php } ?>
    <?php if ($this->getOrder()->getDopSumma() > 0) { ?>
        <tr>
            <td colspan="2"style="border: 2px solid #000000; border-top: none;"><b>Дополнительная оплата</b></td>
            
            <td colspan="6" align="right" >
            <i><?=$this->getOrder()->getCommentDopSumm()?></i>
            </td>
            <td class="border_all border_right" align="right" colspan="2">
                <i><?php echo Number::formatFloat($this->getOrder()->getDopSumma(), 2)?></i>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="2" style="border: 2px solid #000000; border-top: none;"><b>Коментарий кассира:</b></td>
        <td colspan="2" class="border_none">**</td>
        <td colspan="4" class="border_none fnt_size_1" align="right">
            <strong style="text-transform: uppercase;">Всего к оплате:</strong>
            <br/>
            <span style="font-size: 14px;">Без ПДВ</span>
        </td>
        <td class="border_all border_right tt_border_bottom" align="right" colspan="2">
            <strong><?=$to_pay?></strong>
        </td>
    </tr>
    <tr>
        <td colspan="10">
            <br/>
            <i>Всего наименований <strong><?php echo ($i - 1)?></strong>, на сумму <strong>
                    <?php 
                    $sum = explode(',', $to_pay);
                    echo $sum[0];
                    ?> грн.
                    <?php echo $sum[1] ? $sum[1] : '00'?> коп.
                    (<?php  echo Plural::currency(Number::formatFloat($to_pay, 2), $kop);?>)</strong></i>
            <br/><br/>
        </td>
    </tr>
    <tr>
        <td colspan="10" class="tt_border_bottom">
            * <i><b>
                    Общая сумма Ваших заказов в интернет-магазине RED на <?=$this->exploded_date[2]?>
                    .<?=$this->exploded_date[1]?>.<?=$this->exploded_date[0]?> г. составляет
                    <?php
                    if ($this->all_orders_amount_total == 0) {
                        echo '0 грн. 00 коп.';
                    } else {
                        echo Plural::currency($this->all_orders_amount_total, $kop = 0) . ' коп.';
                    }
                    ?> (без учета данного заказа)
                </b></i>
            <br/>
            ** <b>Поля обязательные для заполнения кассиром</b><br/>
            <br/>

        </td>
    </tr>
    <tr>
        <td colspan="10" style=" font-size: 11px; padding-bottom: 30px;">
            Персональные данные (ФИО и адрес) Покупателя были переданы интернет-магазину RED.ua, с целью выполнения данного
            заказа Покупателя, и в дальнейшем могут передаваться уполномоченным органам в установленном законом порядке.
            Как субъект персональных данных Покупатель имеет все права, предусмотренные ст. 8 Закона Украины «О защите
            персональных данных».
        </td>
    </tr>
    </table>
	    <?php
		require_once("QRCode/qrcode.php");
		$qr = new qrcode();
		$qr->text($cod);
               // echo $cod;
		echo "<p id='qr".$this->order->getId()."' hidden><img src='".$qr->get_link(120)."' style='max-width: 120px;' border='0'/></p>";
?>
		<div style='page-break-after: always;'></div>
		
<?php if(in_array($this->getOrder()->payment_method_id, [4,6,8]) and Config::findByCode('tov_check_zayava')->getValue()){ ?> 
		 <table border="0" cellpadding="3" cellspacing="0" width="700" >
    <tr>
        <td colspan="10" class="tt_border_bottom">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
					 <td align="left" valign="top">
                        <img src="/img/logo/RED_Logo_RGB.png" style="padding-bottom: 5px;width: 100px;" alt=""/>
                    </td>
                    <td align="left" valign="top" style="padding-top: 5px;">
        <span style="color: #FA0000; font-size: 14px; font-style: italic; font-family: Verdana,Tahoma,Arial; letter-spacing: -1px;">
            Большие бренды - маленькие цены!
        </span><br>
		<span>(044) 224-40-00 | (063) 809-35-29 | (067) 406-90-80</span>
                    </td>
					<td style="text-align:center;">
					<strong>Интернет-магазин «RED.UA»</strong><br>
					 https://www.red.ua<br>
					  <strong>E-mail: market@red.ua</strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
	<td colspan="5" style="width:50%;"></td>
	<td colspan="5" style="width:50%;">
	<p style="line-height: 2;">
	ДИРЕКТОРУ ИНТЕРНЕТ-МАГАЗИНА RED.UA<BR>
	от <?=$this->order->getMiddleName().' '.$this->order->getName()?><BR>
	паспорт серия* ____ № _________________<BR>
	дата выдачи ____.__________._________г.<BR>
	тел.: <?=$this->order->getTelephone()?><BR>
			______________________
	</p>
	</td>
	<tr>
	<td colspan="10" align="center" class="fnt_size_3">
	 <br/><b>ЗАЯВЛЕНИЕ<BR>О ВОЗВРАТЕ ТОВАРА И ВОЗМЕЩЕНИИ ДЕНЕЖНЫХ СРЕДСТВ</b>
	</td>
	</tr>
	<tr><td colspan="10" style="text-align:center"><p style="line-height: 2;">
	"_____"___________ <?=date("Y")?>.г в интернет-магазине RED.UA, согласно каталогу на сайте www.red.ua<br> по Заказу № <?=$this->order->id?>.</p>
	</td>
	</tr>
	<tr>
        <td  width="35" class="border" align="center"><strong>№</strong></td>
        <td  align="center" class="border" colspan="3" width="150"><strong>Наименование товара</strong></td>
		<td align="center" class="border"  width="150"><strong>КОД</strong></td>
        <td  align="center" class="border" width="35"><strong>К-во (ед.)</strong></td>
        <td  align="center" class="border" width="35"><strong>Стоимость<br>без скидки</strong></td>
        <td  align="center" class="border" width="35"><strong>Стоимость<br>со скидкой</strong></td>
        <td  align="center" class="border" colspan="2" width="35"><strong>Причина возврата</strong></td>
    </tr>
	<?php
	$i = 1;
foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
	if($article_rec->getCount() > 0){
	$price_real = (int)$article_rec->getOldPrice() ? $article_rec->getOldPrice() : $article_rec->getPrice();
	$price_show = $article_rec->getPerc($this->order->getAllAmount());//вычисление цены и скидка на товар
	?>
	<tr>
	<td class="border"><?=$i?></td>
	<td colspan="3" class="border">
	<b><?=$article_rec->getTitle() . ', <br>' . wsActiveRecord::useStatic('Size')->findById($article_rec->getSize())->getSize() . ', ' . wsActiveRecord::useStatic('Shoparticlescolor')->findById($article_rec->getColor())->getName();?></b>
	</td>
	<td class="border" align="center" >
	<?php if($article_rec->getCount()) {?>
				<img src="/images/barcodeimage.php?text=<?=$article_rec->getCode()?>" alt="Barcode Image" />
				<br><?=$article_rec->getCode()?>
				<?php } ?>
				</td>
	<td class="border" align="center"> ___ шт.</td>
	<td class="border" align="center"><?=$price_real?></td>
	<td class="border" align="center">
	<?=Number::formatFloat($article_rec->getCount() > 1 ? $price_show['price']/$article_rec->getCount() : $price_show['price']); ?>
	</td>
	<td colspan="2" class="border"></td>
	</tr>
	<?php 
	$i++;
	}
	}
	?>
	<tr><td colspan="10"  style="text-align:center"><p style="line-height: 2;">
	<i>На основании вышеупомянутого и Закона Украины "Про защиту прав потребителея" от 12.05.1991 г. №1023-ХII, прошу:</i></p>
	<p>Возместить денежные средства за приобретенный товар в размере __________ грн., ________коп. ________________________________________________________</p>
        <figcaption style="margin-top: -10px;"></i>(сумма цифрами и прописью)</i></figcaption>
	</td></tr>
	<tr><td  colspan="10"  style="text-align:left">
	<b>Выберите вариант возмещения:</b>
	</td></tr>
	<tr>
            <td colspan="2"  style="text-align:center">
                ☐
            </td>
            <td colspan="8" style="text-align:left">
                <b>на депозит</b> - внутренний счет в аккаунте на сайте red.ua
            </td>
        </tr>
        <?php if(in_array($this->getOrder()->payment_method_id, [4,6]) and Config::findByCode('return_pay_to_order_card')->getValue()){?>
            <tr>
            <td colspan="2"  style="text-align:center">
                ☐
            </td>
            <td colspan="8" style="text-align:left">
               <b>банковская карта</b> – возврат средств на карту с которой оплачивался заказ
            </td>
        </tr>
       <?php }?>
        <tr>
            <td colspan="2"  style="text-align:center">
	☐
            </td>
	<td colspan="8"  style="text-align:left">
	<b>почтовый перевод Укрпочтой</b> - адрес по которому я хочу получить перевод:
        </td>
            </tr>
        <tr>
            <td colspan="10"><br>
        *Индекс________, *город______________, *ул.________________, дом_____, кв.______<br><br>
        * - Поля обязательные для заполнения при возврате почтовым переводом.<br>
        <b>Отсутствие корректно заполненых данных не гарантирует своевременную отправку почтового перевода!</b>
	</td></tr>
	<tr><td colspan="10"  style="text-align:left">
	К заявлению прилогаются необходимые для возврата денежных средств документы:<br>
	1. Копия паспорта.<br>
	2. Копия документа об оплате.<br>
        3. Товарный чек.
	</td></tr>
	<tr><td colspan="5"  style="text-align:left">
	"____" ____________ <?=date("Y")?>.г
	</td>
	<td colspan="2" style="text-align:center"><p>_____________ </p><figcaption style="margin-top: -10px;"></i>(подпись)</i></figcaption>
	<td colspan="3" style="text-align:center"><p>
	(_________________)</p>
	<figcaption style="margin-top: -10px;"></i>(ФИО)</i></figcaption>
	</td></tr>
	</table>
	<div style='page-break-after: always;'></div>
	<?php } ?>
<script>
$(function(){
 $('.qr<?=$this->order->getId()?>').html($('#qr<?=$this->order->getId()?>').html());
 });
</script>
</body>
</html>
