<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Новая почта</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script src="<?=$this->files?>scripts/jquery.js" type="text/javascript" charset="utf-8"></script>
</head>
<style type="text/css">
    body {font-size: 10px;font-family: Verdana,Myriad Pro;}
    .tt_border_bottom {border-bottom: solid 2px #000;}
    .fnt_size_1 {font-size: 14px;}
    .fnt_size_2 {font-size: 13px;}
    .fnt_size_3 {font-size: 18px;}
    .borderer {border: solid 2px #000;}
    .border_all {border-top: 2px solid #000;border-left: 2px solid #000;}
    .border_right {border-right: 2px solid #000;}
    .border_left {border-left: 2px solid #000;}
	.border{ border: 1px solid black;}
</style>
<body>
<?php 
require_once("QRCode/qrcode.php");
for ($step = 0; $step < 3; $step++) { ?>
<table border="0" cellpadding="3" cellspacing="0" width="700">
<tr>
    <td colspan="8" class="tt_border_bottom">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
					 <td align="left" valign="top">
                        <img src="/img/_new_logo.png" style="padding-bottom: 5px;" alt=""/>
                    </td>
                    <td align="left" valign="top" style="padding-top: 5px;">
        <span style="color: #FA0000; font-size: 14px; font-style: italic; font-family: Verdana,Tahoma,Arial; letter-spacing: -1px;">
            Большие бренды - маленькие цены!
        </span><br>
		<span>(044) 224-40-00 | (063) 809-35-29 | (067) 406-90-80</span>
                    </td>
					<td style="text-align:center;">
					<strong>Интернет-магазин «RED.UA»</strong><br>
					 http://www.red.ua<br>
					  <strong>E-mail: market@red.ua</strong>
                    </td>
                </tr>
            </table>
    </td>
</tr>
<tr>
    <td colspan="8" align="center" class="fnt_size_3">
        <br/>
        <strong>Товарный чек
            № <?php echo $this->order->getId(); ?>
			<?php
				if ($this->order->getComlpect()) {
					$compl = explode(';', $this->order->getComlpect());
					$i = 0;
					$str = '<br/>(';
					foreach ($compl as $key => $value) {
						if ($value > 0) {
							$i++;
							$str .= $value.',';
							if ($i == 8) {
								$i = 0;
								$str .= '<br/>';
							}
						}
					}
						$str = substr($str, 0, strlen($str) - 1);
						$str .= ')<br/>';
						echo $str;
//					echo ' ('.str_replace(';', ',', $this->order->getComlpect()).')';
				}
			?>
           <br> от <?=$this->date?>
            года</strong><br/><br/>
    </td>
</tr>
   <tr>
        <td colspan="4">
            <i><strong>Получатель: </strong> <?php echo $this->order->getName(). ' '. $this->order->getMiddleName() ?></i>
        </td>
		<td colspan="4" rowspan="4"><p class="qr<?=$this->order->getId()?>" style="float: right;"></p></td>
    </tr>
	<tr>
        <td colspan="4">
            <i><strong>Доставка: </strong>
                <?=$this->order->getDeliveryType()->getName()?></i>
            <br/>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <i><strong>Адрес: </strong>
                <?=$this->order->getAddress()?></i>
            <br/>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <i><strong>Телефон: </strong>
                <?php echo $this->order->getTelephone()?></i>
            <br/>
        </td>
    </tr>
    <tr>
        <td colspan="4">
                            <?php if ($this->getOrder()->getComments()) { ?>
								<div class="comm_cli">
                                <b>Коментарии клиента :</b>
                                <?php echo $this->getOrder()->getComments(); ?>
								<br><br>
								</div>
                            <?php } ?>
        </td>
    </tr>
    <!-- TOVAR TITLES-->
    <tr>
        <td class="border_all fnt_size_1" width="35" align="center"><strong>№</strong></td>
        <td class="border_all" align="center" width="150"><strong>Наименование товара</strong></td>
        <td class="border_all" align="center" width="250"><strong>КОД</strong></td>
        <td class="border_all" align="center" width="35"><strong>К-во (ед.)</strong></td>
        <td class="border_all" align="center" width="35"><strong>Старая Цена</strong></td>
        <td class="border_all" align="center" width="35"><strong>Скидка</strong></td>
        <td class="border_all" align="center" width="35"><strong>Новая Цена</strong></td>
        <td class="border_all border_right" align="center" width="35"><strong>Сумма</strong></td>
    </tr>
    <?php
    $i = 1;
    $c = $this->getOrder()->getSkuCount();
    $total_price = 0;
    $to_pay = 0;
    $to_pay_minus = 0.00;
	
	$price_real = 0;
	$t_real_price = 0;
	$price_show = 0;
	
	$sk = 0;
	$cod ='%';
	
    foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
	if($article_rec->getCount() > 0){
	
			$price_real = (int)$article_rec->getOldPrice() ? $article_rec->getOldPrice() : $article_rec->getPrice();//стартовая цена
		$t_real_price += $price_real;//сумируется стартовая цена
	
		$price_show = $article_rec->getPerc($this->order->getAllAmount());//вычисление цены и скидка на товар
		
			$sum_skudka += $price_show['minus']/$article_rec->getCount(); //сумируется общая скидка
			
				$skid_show = round((1 - (($price_show['price']/$article_rec->getCount())/ $price_real)) * 100);//вычисление процента скидки по товару
					
							if($skid_show == 100){
$sk = Number::formatFloat(99.99, 2);
			}else{
		$sk = Number::formatFloat($skid_show, 2);
		}
		
				//$z = chr(10);
		$cod.=$article_rec->getCode().'/'.$article_rec->getCount().'/'.$sk.'&';
		
        ?>
        <tr>
            <td class="border_all <?php echo ($i == $c) ? 'tt_border_bottom' : ''?>" align="center"><strong><?=$i?></strong></td>
             <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : '' ?>"><b>
                    <?php echo $article_rec->getTitle() . ', <br>' . wsActiveRecord::useStatic('Size')->findById($article_rec->getSize())->getSize() . ', ' . wsActiveRecord::useStatic('Shoparticlescolor')->findById($article_rec->getColor())->getName();?></b><br>
					<font style="font-size:10px;"><b><?=$article_rec->article_db->category->getRoutez()?></b></font>
            </td>
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>" align="center">
				<?php if($article_rec->getCount()) {?>
				<img src="/images/barcodeimage.php?text=<?=$article_rec->getCode()?>" alt="Barcode Image" />
				<br><?=$article_rec->getCode()?>
				<?php } ?>
            </td>
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>" align="center">
                <strong><?php echo $article_rec->getCount()?></strong>
            </td>
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : '' ?>" align="right">
                <?=Number::formatFloat($price_real, 2);?>
            </td>
             <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : '' ?>" align="right">
                        <?php
$st = (int)$article_rec->getOldPrice() ? 'style="color:red;font-size:12px;"' : 'style="font-size:12px;text-decoration: underline;"';//уценялся
	if((int)$article_rec->getEventSkidka()){ $st = 'style="color:red;font-size:12px;font-weight: 600;"';}//наличие доп скидки
					
echo $skid_show ? '<span '.$st.'>'.$skid_show.' %</span>' : '';
                 if ($article_rec->getEventSkidka()>=5 and false) {
					$s = wsActiveRecord::useStatic('Skidki')->findFirst(array('id'=>$article_rec->event_id));
						if($s){ $name = htmlspecialchars($s->name_a);}else{$name = '';}
				echo '<br><span style="color: red;font-size: 8px;">+'.$article_rec->getEventSkidka().'%</span><br><span style="color: red;font-size: 7px;">'.$name.'</span>';} 
				?>
            </td>
           <td class="border_all<?php echo($i == $c) ? ' tt_border_bottom' : '' ?>" align="right">
                <?=Number::formatFloat($article_rec->getCount() > 1 ? $price_show['price']/$article_rec->getCount() : $price_show['price']); ?>
            </td>
            <td class="border_all border_right<?php echo($i == $c) ? ' tt_border_bottom' : '' ?> <?=$i.'-'.$c?>" align="right">
                <strong><?=Number::formatFloat($price_show['price'], 2)?></strong>
            </td>
        </tr>
            <?php

        $i++;
		}
        }
		if($this->getOrder()->getBonus() > 0){ $bonus = true; }else{ $bonus = false; }
	
    $to_pay = $this->getOrder()->calculateOrderPrice2(true, true, true, $bonus); //общая сумма к оплате
	
	$to_pay_minus = $sum_skudka;//общая скидка

        $kop = round(($to_pay - toFixed($to_pay)) * 100, 0);
        ?>
    <tr>

        <td colspan="6" align="right">
            <strong>Всего:</strong>
        </td>
        <td class="border_left border_right" align="right" colspan="2">
            <strong><?=Number::formatFloat($t_real_price, 2)?></strong>
        </td>
    </tr>
    <tr>
			<td colspan="6" align="right"><i>Скидка клиента</i></td>
			<td class="border_all border_right" align="right" colspan="2"><i><?=$this->order->getDiscont();?> %*</i></td>
	</tr>
		<?php if($this->getOrder()->getKuponPrice() > 0){ ?> 
	<tr>
		<td colspan="6" align="right"><i style="font-weight: bold;">Код на скидку</i></td>
		<td class="border_all border_right" align="right" colspan="2"><i style="font-weight: bold;"><?=$this->getOrder()->getKupon()?></i></td>
	</tr>
	<tr>
		<td colspan="6" align="right"><i style="font-weight: bold;">Скидка по коду</i></td>
		<td class="border_all border_right" align="right" colspan="2"><i style="font-weight: bold;"><?=Number::formatFloat($this->getOrder()->getKuponPrice(), 2)?> %</i></td>
		</tr>
    <?php } ?>
	<tr>
			<td colspan="6" align="right"><i>Сумма общей скидки</i></td>
			<td class="border_all border_right" align="right" colspan="2"><i><?=Number::formatFloat($to_pay_minus, 2)?></i></td>
	</tr>
<?php
$min_sum_bonus = Config::findByCode('min_sum_bonus')->getValue();
		if($this->getOrder()->getBonus() > 0 and $to_pay >= $min_sum_bonus){?>
		<tr>
			<td colspan="6" align="right"><i>Бонусная скидка</i></td>
            <td class="border_all border_right" align="right" colspan="2">
                <i><?php echo Number::formatFloat($this->getOrder()->getBonus(), 2) ?></i>
            </td>
        </tr>
		<?php } ?>
		<tr>
			<td colspan="6" align="right">
				<i>Стоимость с учетом скидок</i>
			</td>
			<td class="border_all border_right" align="right" colspan="2">
				<i>
				<?php
		if($this->getOrder()->getDeliveryCost() > 0 or $this->getOrder()->getDeposit() > 0) {
		echo $this->getOrder()->calculateOrderPrice2(false, true, false, $bonus);
		}else{ echo $to_pay;}
		?>
		</i>
			</td>
		</tr>
    <?php if ($this->getOrder()->getDeposit() > 0) {?>
        <tr>
            <td colspan="6" align="right"><i>Депозит</i></td>
            <td class="border_all border_right" align="right" colspan="2">
                <i><?php echo Number::formatFloat($this->getOrder()->getDeposit(), 2)?></i>
            </td>
        </tr>
    <?php } ?>
    <tr>

        <td colspan="6" class="border_none fnt_size_1" align="right">
            <strong style="text-transform: uppercase;">Всего к оплате:</strong>
            <br/>
            <span style="font-size: 14px;">Без ПДВ</span>
        </td>
        <td class="border_all border_right tt_border_bottom" align="right" colspan="2">
            <strong><?=$to_pay?></strong>
        </td>
    </tr>
<!--<tr>
		<td colspan="5" align="right">
			<strong>В т.ч. ПДВ:</strong>
		</td>
		<td class="border_all border_right tt_border_bottom" align="right">
			<strong><?php /*echo Number::formatFloat((($tprice)/6),2);*/?></strong>
		</td>
	</tr>-->
<tr>
    <td colspan="7">
        <br/>
        <i>Всего наименований <strong><?php echo ($i - 1)?></strong>, на сумму <strong>
                <?php 
                $sum = explode(',', $to_pay);
                echo $sum[0];
                ?> грн.
                <?php echo @$sum[1] ? @$sum[1] : '00'?> коп.
                (<?php  echo Plural::currency(Number::formatFloat($to_pay, 2), $kop);?>)</strong></i>
			<br/>
        <br/>
    </td>
	<td>

                            <?php if ($this->getOrder()->getRemarks()->count()) { ?>
								<div style="margin-top: -25px; padding-left: 25px;">◘</div>
                            <?php } ?>
        </td>
</tr>
    <tr>
        <td colspan="8" class="tt_border_bottom">
            * <i><b>
            Общая сумма Ваших заказов в интернет-магазине RED на <?php echo $this->exploded_date[2]?>
            .<?php echo $this->exploded_date[1]?>.<?php echo $this->exploded_date[0]?> г. составляет
            <?php
            if ($this->all_orders_amount_total == 0) {
                echo '0 грн. 00 коп.';
            } else {
                echo Plural::currency($this->all_orders_amount_total, $kop = 0);
            }
            ?> (без учета данного заказа)
        </b></i>
            <br/><br/>
        </td>
    </tr>
<tr>
    <td colspan="8" style=" font-size: 11px; padding-bottom: 30px;">
        Персональные данные (ФИО и адрес) Покупателя были переданы интернет-магазину RED.ua, с целью выполнения данного заказа Покупателя, и в дальнейшем могут передаваться уполномоченным органам в установленном законом порядке. Как субъект персональных данных Покупатель имеет все права, предусмотренные ст. 8 Закона Украины «О защите персональных данных».
       </td>
</tr>
</table>
	<?php if(true){
		$qr = new qrcode();
		$qr->text($cod);
		echo "<p id='qr".$this->order->getId()."' hidden><img src='".$qr->get_link(140)."' border='0'/></p>";
		//echo $cod;
				}?>
    <div style='page-break-after: always;'></div>
		<?php } ?>
		
	 <table border="0" cellpadding="3" cellspacing="0" width="700" >
    <tr>
        <td colspan="10" class="tt_border_bottom">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
					 <td align="left" valign="top">
                        <img src="/img/_new_logo.png" style="padding-bottom: 5px;" alt=""/>
                    </td>
                    <td align="left" valign="top" style="padding-top: 5px;">
        <span style="color: #FA0000; font-size: 14px; font-style: italic; font-family: Verdana,Tahoma,Arial; letter-spacing: -1px;">
            Большие бренды - маленькие цены!
        </span><br>
		<span>(044) 224-40-00 | (063) 809-35-29 | (067) 406-90-80</span>
                    </td>
					<td style="text-align:center;">
					<strong>Интернет-магазин «RED.UA»</strong><br>
					 http://www.red.ua<br>
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
	<td class="border" align="center" style="font-size: 12px;">
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
	<p>Возместить денежные средства за приобретенный товар в размере __________ грн., ________коп. ___________________________________________________________</p><figcaption style="margin-top: -15px;font-size: 12px;"></i>(сумма цифрами и прописью)</i></figcaption>
	</td></tr>
	<tr><td  colspan="10"  style="text-align:left">
	<b>Выберите вариант возмещения:</b>
	</td></tr>
	<tr><td colspan="2"  style="text-align:center">
	☐<br>☐</td>
	<td colspan="8"  style="text-align:left">
	на депозит<br>
	почтовым переводом по адресу ___________________________________________________
	</td></tr>
	<tr><td colspan="10"  style="text-align:left">
	К заявлению прилогаются необходимые для возврата денежных средств документы:<br>
	1. Копия паспорта.<br>
	2. Копия документа об оплате.
	</td></tr>
	<tr><td colspan="5"  style="text-align:left">
	"____" ____________ <?=date("Y")?>.г
	</td>
	<td colspan="2" style="text-align:center"><p>_____________ </p><figcaption style="margin-top: -15px;font-size: 12px;"></i>(подпись)</i></figcaption>
	<td colspan="3" style="text-align:center"><p>
	(_________________)</p>
	<figcaption style="margin-top: -15px;font-size: 12px;"></i>(ФИО)</i></figcaption>
	</td></tr>
	</table>
	<div style='page-break-after: always;'></div>
</body>
</html>
<script>
$(window).load(function(){
 $('.qr<?=$this->order->getId()?>').html($('#qr<?=$this->order->getId()?>').html());
 window.print();
 });
</script>