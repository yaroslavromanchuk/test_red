<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="uk" lang="uk">
<head>
    <title>Рахунок <?=strip_tags($this->order->getDeliveryType()->getName())?></title>
	<meta name="robot" content="no-index,no-follow"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

</head>
<style type="text/css">
	body {font-size: 10px;font-family: Verdana;}
	.tt_border_bottom {border-bottom: solid 2px #000;}
	.fnt_size_1 {font-size: 13px;}
	.fnt_size_2 {font-size: 13px;}
	.fnt_size_3 {font-size: 18px;}
	.border_all {border-top: 2px solid #000;border-left: 2px solid #000;}
	.border_right {border-right: 2px solid #000;}
	.border_left {border-left: 2px solid #000;}
	.border{ border: 1px solid black;}
        ul{
            
        }
        ul li{
            padding: 5px;
        }
</style> 
    <body onload="window.print()" style="width: 700px; margin: auto">
     <?php
    
     $z = 2;//2
require_once("QRCode/qrcode.php");
for ($step = 0; $step < $this->count; $step++) { ?>
        <table border="0" cellpadding="3" cellspacing="0" width="100%" >
            <tr>
        <td  class="tt_border_bottom">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
			<td align="left" valign="top">
                        <img src="/img/logo/RED_Logo_RGB.png" style="padding-bottom: 5px;width: 100px;" alt=""/>
                    </td>
                    <td align="left" valign="top" style="padding-top: 15px;">
        <span style="color: #FA0000; font-size: 14px; font-style: italic;">
            Великі бренди - маленькі ціни!
        </span><br>
		<span>(044) 224-40-00 | (063) 809-35-29 | (067) 406-90-80</span>
                    </td>
                    <td style="text-align:center;">
					<strong>Інтернет-магазин «RED.UA»</strong>
                                        <br>
                                            <img src="/images/barcodeimage.php?text=<?=$this->order->getId()?>" alt="Barcode Image" style="margin-top: 5px;" /></strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
        <td  align="center" colspan="2" class="fnt_size_3">
        <strong>Товарний чек № <?=$this->order->id?></strong>
	<?=$this->order->getComlpect()?'<br><span>('.substr(implode(explode(";", $this->order->getComlpect()), ', '), 0, -2).')</span><br>':''?>
        </td>
    </tr>
    <tr>
        <td  align="center" colspan="2" class="fnt_size_1">
            <strong>від <?=$this->date_today?> року</strong>
        </td>
    </tr>
        <tr>
                               <td >
                                   <ul>
                                       <li><strong>Отримувач: </strong> <?php echo $this->order->getName(). ' '. $this->order->getMiddleName() ?></li>
                                       <li><strong>Доставка: </strong><?=strip_tags($this->order->getDeliveryType()->getName())?></li>
                                       <li><strong>Оплата: </strong><?=strip_tags($this->order->getPaymentMethod()->getName())?></li>
                                       <?php if($this->order->delivery_type_id != 3){ ?><li><strong>Адрес: </strong><?=$this->order->getAddress()?></li> <?php } ?>
                                       <li><strong>Телефон: </strong><?=$this->order->getTelephone()?></li>
                                       <?php if($this->getOrder()->getComments()){ ?>
                                       <li><strong>Коментарі до замовлення: </strong><?=$this->order->getComments()?></li>
                                           <?php } ?>
                                   </ul> 
                               </td>
        
        <td>
            <p class="qr<?=$this->order->getId()?>" style="float: right;"></p>
        </td>
    </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table border="0" cellpadding="3" cellspacing="0" >
    <!-- TOVAR TITLES-->
    <tr>
        <td class="border_all fnt_size_1" width="35" align="center"><strong>№</strong></td>
        <td class="border_all" align="center" width="150"><strong>Назва товару</strong></td>
        <td class="border_all" align="center" width="250"><strong>КОД</strong></td>
        <td class="border_all" align="center" width="35"><strong>Стара Ціна</strong></td>
        <td class="border_all" align="center" width="35"><strong>Знижка</strong></td>
        <td class="border_all" align="center" width="35"><strong>redcoin</strong></td>
        <td class="border_all" align="center" width="35"><strong>Нова Ціна</strong></td>
        <td class="border_all" align="center" width="35"><strong>Кіл.(од.)</strong></td>
        <td class="border_all border_right" align="center" width="35"><strong>Сума</strong></td>
    </tr>
    <?php
    $i = 1;
    $c = $this->getOrder()->getSkuCount();
    $to_pay = $this->order->calculateOrderPrice2();
    $total_price = 0;
	$price_real = 0;
	$t_real_price = 0;
	$price_show = 0;
	$to_pay_minus = 0;
	$sk = 0;
        $coin = $this->order->sumBonusOrder();
        $qr_cod = [];
        $qr_cod[] = '%ID'.$this->order->customer->id;
        $qr_cod[] ='ORDER'.$this->order->id;
        if($this->order->deposit > 0){
           $qr_cod[] = 'DEPO'.Number::formatFloat($this->order->deposit);
        }
        if($this->order->bonus > 0){
            $qr_cod[] = 'COIN'.Number::formatFloat($coin, 0, '');
        }
        
        foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
		if($article_rec->getCount() > 0){
	
		$price_real = (int)$article_rec->getOldPrice() ? $article_rec->getOldPrice() : $article_rec->getPrice();//стартовая цена
	
		$price_show = $article_rec->getPerc();//вычисление цены и скидка на товар
                
		$t_real_price +=($price_show['price']-$price_show['coin']);// общая сумма заказа
		$sum_skudka += $price_show['minus']/$article_rec->getCount(); //сумируется общая скидка
			
		$skid_show = round(((1 - (($price_show['price']/$article_rec->getCount())/ $price_real)) * 100), 2);//вычисление процента скидки по товару
		
		if($skid_show == 100){
			$sk = Number::formatFloat(99.99, 2);
		}else{
			$sk = Number::formatFloat($skid_show, 2);
		}
		$cod.=$article_rec->getCode().'/'.$article_rec->getCount().'/'.$sk.'&';
                $qr_cod[] =$article_rec->getCode().'/'.$article_rec->getCount().'/'.$sk;
		
        ?>
        <tr>
            <td class="border_all <?php echo ($i == $c) ? 'tt_border_bottom' : ''?>" align="center"><strong><?=$i?></strong></td>
             <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : '' ?>"><b>
                    <?php echo $article_rec->article_db->getTitle() . ', <br>' . wsActiveRecord::useStatic('Size')->findById($article_rec->getSize())->getSize() . ', ' . wsActiveRecord::useStatic('Shoparticlescolor')->findById($article_rec->getColor())->getName();?></b><br>
					<font style="font-size:10px;"><b><?=$article_rec->article_db->category->getRoutez()?></b></font>
            </td>
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>" align="center">
				<?php if($article_rec->getCount()) {?>
				<img src="/images/barcodeimage.php?text=<?=$article_rec->getCode()?>" alt="Barcode Image" />
				<br><?=$article_rec->getCode()?>
				<?php } ?>
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
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>" align="center">
                <?=Number::formatFloat($article_rec->coin?$article_rec->coin:'', 2)?>
            </td>
           <td class="border_all<?php echo($i == $c) ? ' tt_border_bottom' : '' ?>" align="right">
                <?=Number::formatFloat($article_rec->getCount() > 1 ? ($price_show['price']/$article_rec->getCount())-$price_show['coin'] : $price_show['price']-$price_show['coin'])?>
            </td>
            <td class="border_all <?php echo($i == $c) ? 'tt_border_bottom' : ''?>" align="center">
                <strong><?php echo $article_rec->getCount()?></strong>
            </td>
            <td class="border_all border_right<?php echo($i == $c) ? ' tt_border_bottom' : '' ?>" align="right">
                <strong><?=Number::formatFloat(($price_show['price']-$price_show['coin']), 2)?></strong>
            </td>
        </tr>
            <?php

        $i++;
        $to_pay_minus+=$price_show['minus'];
		}
        }
     //  round(($to_pay - toFixed($to_pay)) * 100, 0);
        
        ?>
    <tr>

        <td colspan="6" align="right">
            <strong>Всього:</strong>
        </td>
        <td class="border_left border_right" align="right" colspan="3">
            <strong><?=Number::formatFloat($t_real_price, 2)?></strong>
        </td>
    </tr>
    <?php if($this->getOrder()->kupon){ ?>
        <tr>
			<td colspan="6" align="right"><i>Промокод</i></td>
            <td class="border_all border_right" align="right" colspan="3">
                <i><?=$this->getOrder()->kupon?></i>
            </td>
        </tr>
   <?php } if($this->getOrder()->getBonus() > 0){ ?>
	<tr>
			<td colspan="6" align="right"><i>Redcoin</i></td>
            <td class="border_all border_right" align="right" colspan="3">
                <i><?=Number::formatFloat($coin, 2) ?></i>
            </td>
        </tr>
        <?php }
        if($this->getOrder()->getDeliveryCost() > 0){
        ?>
    <tr>
		<td colspan="6" align="right" class="fnt_size_1">Доставка:</td>
        <td class="border_all border_right" align="right" colspan="3"><?=Number::formatFloat($this->getOrder()->getDeliveryCost(), 2);?></td>
    </tr>
<?php } ?>
    <?php if ($this->getOrder()->getDeposit() > 0) { ?>
        <tr>
            <td colspan="6" align="right"><i>Депозит</i></td>
            <td class="border_all border_right" align="right" colspan="3">
                <i><?=Number::formatFloat($this->getOrder()->getDeposit(), 2)?></i>
            </td>
        </tr>
    <?php } ?>
    <?php if ($this->getOrder()->getDopSumma() > 0) { ?>
        <tr>
            <td colspan="6" align="right" ><span class="fnt_size_1">Додаткова сплата</span><br>
            <i><?=$this->getOrder()->getCommentDopSumm()?></i>
            </td>
            <td class="border_all border_right" align="right" colspan="3">
                <i><?=Number::formatFloat($this->getOrder()->getDopSumma(), 2)?></i>
            </td>
        </tr>
    <?php } ?>
    <tr>

        <td colspan="6" class="border_none fnt_size_1" align="right">
            <strong style="text-transform: uppercase;">Всього до сплати:</strong>
            <br/>
            <span style="font-size: 14px;">Без ПДВ</span>
        </td>
        <td class="border_all border_right tt_border_bottom" align="right" colspan="3">
            <strong><?=$to_pay?></strong>
        </td>
    </tr>
                    </table>
                </td>
            </tr>
            
        
            <tr>
        <td >
            
            <i>Всього товарів <strong><?php echo ($i - 1)?></strong>, на суму <strong>
                    <?php 
                    $sum = explode(',', $to_pay);
                    echo $sum[0];
                    ?> грн.
                    <?php echo $sum[1] ? $sum[1] : '00'?> коп.
                    (<?=Number::formatToStringUk((int)$to_pay)?>)</strong></i>
            <br/><br/>
        </td>
    </tr>
            <tr>
        <td class="tt_border_bottom">
            * <i><b>
                    Загальна сума Ваших замовлень в інтернет-магазині RED на <?=$this->exploded_date[2]?>
                    .<?=$this->exploded_date[1]?>.<?=$this->exploded_date[0]?> рік. становить
                    <?php
                    if ($this->all_orders_amount_total == 0) {
                        echo '0 грн. 00 коп.';
                    } else {
                        echo Number::formatToStringUk($this->all_orders_amount_total) . ' 0 коп.';
                    }
                    ?> (без урахування даного замовлення)
                </b>
            </i>
        </td>
    </tr>
            <tr>
    <td  style="">
            Персональні дані (ПІБ і адресу) Покупця були передані інтернет-магазину RED.ua, з метою виконання даного
             замовлення Покупця, і в подальшому можуть передаватися уповноваженим органам в установленому законом порядку.
             Як суб'єкт персональних даних Покупець має всі права, передбачені ст. 8 Закону України «Про захист
             персональних даних».
    </td>
    </tr>
        </table>
        <?php
        
        
		if($this->getOrder()->getDeliveryCost() > 0){ //$cod.='USL00000002/1/&';
                    
                    switch ($this->getOrder()->delivery_type_id){
                        case '9': $qr_cod[] = 'USL00000002/1/';  break;//kurer
                        case '4': $qr_cod[] = 'USL0000004/1/';  break;//kurer
                    }
                
                }
		$qr = new qrcode();
		$qr->text(implode("&", $qr_cod)."&");
		echo "<p id='qr".$this->order->getId()."' hidden ><img src='".$qr->get_link(140)."' style='max-width: 140px;' border='0'/></p>";
?>
    <div style='page-break-after: always;'></div>
<?php }
if(Config::findByCode('tov_check_zayava')->getValue() and $this->zayava){ ?>
 <table border="0" cellpadding="3" cellspacing="0"  >
    <tr>
        <td colspan="10" class="tt_border_bottom">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
					 <td align="left" valign="top">
                      <img src="/img/logo/RED_Logo_RGB.png" style="width: 80px;" alt=""/>
                    </td>
                    <td align="left" valign="top" style="padding-top: 10px;">
        <span style="color: #FA0000; font-size: 14px; font-style: italic; ">
            Великі бренди - маленькі ціни!
        </span><br>
		<span>(044) 224-40-00 | (063) 809-35-29 | (067) 406-90-80</span>
                    </td>
                    <td style="text-align:center;">
                        <strong>Інтернет-магазин «RED.UA»<br>E-mail: market@red.ua</strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
	<td colspan="5" style="width:50%;"></td>
	<td colspan="5" style="width:50%;">
	<p style="line-height: 2;">
	ДИРЕКТОРОВІ ІНТЕРНЕТ-МАГАЗИНУ RED.UA<BR>
	від <?=$this->order->getMiddleName().' '.$this->order->getName()?><BR>
	паспорт серія* ____ № _________________<BR>
	дата видачі ____.__________._________г.<BR>
	тел.: <?=$this->order->getTelephone()?><BR>
			______________________
	</p>
	</td>
	<tr>
	<td colspan="10" align="center" class="fnt_size_3">
	<b>ЗАЯВА<BR>ПРО ПОВЕРНЕННЯ ТОВАРУ ТА ВІДШКОДУВАННЯ КОШТІВ </b>
	</td>
	</tr>
	<tr><td colspan="10" style="text-align:center"><p style="line-height: 2;">
	"_____"___________ <?=date("Y")?>.року в інтернет-магазині RED.UA, згідно каталогу на сайті www.red.ua<br> по замовленню № <?=$this->order->id?>.</p>
	</td>
	</tr>
	<tr>
        <td  width="35" class="border" align="center"><strong>№</strong></td>
        <td  align="center" class="border" colspan="3" width="150"><strong>Назва товару</strong></td>
		<td align="center" class="border"  width="150"><strong>КОД</strong></td>
        <td  align="center" class="border" width="35"><strong>Кіл.(од.)</strong></td>
        <td  align="center" class="border" width="35"><strong>Вартісь <br>без знижки</strong></td>
        <td  align="center" class="border" width="35"><strong>Вартісь<br>зі знижкою</strong></td>
        <td  align="center" class="border" colspan="2" width="35"><strong>Причина повернення</strong></td>
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
	<b><?=$article_rec->article_db->getTitle() . ', <br>' . wsActiveRecord::useStatic('Size')->findById($article_rec->getSize())->getSize() . ', ' . wsActiveRecord::useStatic('Shoparticlescolor')->findById($article_rec->getColor())->getName();?></b>
	</td>
	<td class="border" align="center" >
	<?php if($article_rec->getCount()) {?>
				<img src="/images/barcodeimage.php?text=<?=$article_rec->getCode()?>" style="max-height: 25px;" alt="Barcode Image" />
				<br><span style="font-size:10px"><?=$article_rec->getCode()?></span>
				<?php } ?>
				</td>
	<td class="border" align="center"> ___ шт.</td>
	<td class="border" align="center"><?=$price_real?></td>
	<td class="border" align="center">
	<?=Number::formatFloat($article_rec->getCount() > 1 ? ($price_show['price']/$article_rec->getCount())-$price_show['coin'] : $price_show['price']-$price_show['coin']); ?>
	</td>
	<td colspan="2" class="border"></td>
	</tr>
	<?php 
	$i++;
	}
	}
	?>
	<tr>
            <td colspan="10"  style="text-align:center">
                <p style="line-height: 2;">
	<i>На підставі вищезазначеного та Закону України "Про захист прав споживачів" від 12.05.1991 р №1023-ХІІ, прошу:</i></p>
	<p>Відшкодувати грошові кошти за придбаний товар в розмірі __________ грн., ________коп. ________________________________________________________</p>
        <figcaption style="margin-top: -10px;"></i>(сума цифрами і прописом, українською мовою)</i></figcaption>
	</td></tr>
	<tr><td  colspan="10"  style="text-align:left">
	<b>Виберіть варіант відшкодування:</b>
	</td>
        </tr>
        <tr>
            <td  style="text-align:center">
                ☐
            </td>
            <td colspan="9" style="text-align:left">
                <b>на депозит</b> - внутрішній рахунок в акаунті на сайті red.ua
            </td>
        </tr>
        <?php if(in_array($this->getOrder()->payment_method_id, [4,6]) and Config::findByCode('return_pay_to_order_card')->getValue()){ ?>
            <tr>
            <td   style="text-align:center">
                ☐
            </td>
            <td colspan="9" style="text-align:left">
               <b>банківська картка</b> – повернення коштів на карту з якої оплачувалася замовлення на сайті
            </td>
        </tr>
       <?php }else{ ?>
           <tr>
            <td   style="text-align:center">
                ☐
            </td>
            <td colspan="9" style="text-align:left">
               <b>картковий рахунок</b> – банківський рахунок, на який потрібно повернути кошти
            </td>
        </tr>
        <tr>
            <td   style="text-align:center"></td>
            <td colspan="9">
                <table> 
                    <tr>
                        <td>*Назва банку</td>
                        <td>______________________________________________________________</td>
                    </tr>
                    <tr>
                        <td>*Номер картки для клієнтів Приват Банку</td>
                        <td style="font-size: 1.4rem;">☐☐☐☐ ☐☐☐☐ ☐☐☐☐ ☐☐☐☐</td>
                    </tr>
                    <tr>
                        <td>*ПІБ отримувача</td>
                        <td>______________________________________________________________</td>
                    </tr>
                    <tr>
                        <td>*ІПН/ЄДРПОУ/РНОКПП<br>(Ідентицікаційний подавтковий номер)</td>
                        <td style="font-size: 1.4rem;">☐☐☐☐☐☐☐☐☐☐</td>
                    </tr>
                    <tr>
                        <td>*IBAN - Рахунок отримувача який складається з 27 цифр.</td>
                        <td style="font-size: 1.4rem;">UA<span >☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐☐</span></td>
                    </tr>
                </table>
                
                * - Поля обов'язкові для заповнення.
            </td>
        </tr>
      <?php } ?>
        <tr>
            <td   style="text-align:center">
	☐
            </td>
	<td colspan="9"  style="text-align:left">
	<b>поштовий переказ Укрпоштою </b> - адреса за яким я хочу отримати переказ:
        </td>
            </tr>
        <tr>
            <td >
            </td>
            <td colspan="9">*Індекс________, *місто______________, *вул.________________, буд._____, кв.______<br>
        * - Поля обов'язкові для заповнення.<br>
        
	</td></tr>
	<tr><td colspan="10"  style="text-align:left"><b>Відсутність коректно заповнених даних не гарантує своєчасну відправку повернення!</b><br>
	До заяви додаються необхідні для повернення грошових коштів документи: <br>
        1. Копія паспорта. <br>
        2. Копія документа про оплату. <br>
        3. Товарний чек.
	</td></tr>
	<tr><td colspan="5"  style="text-align:left">
	"____" ____________ <?=date("Y")?>.року
	</td>
	<td colspan="2" style="text-align:center"><p>_____________ </p><figcaption style="margin-top: -10px;"></i>(підпись)</i></figcaption>
	<td colspan="3" style="text-align:center"><p>
	(_________________)</p>
	<figcaption style="margin-top: -10px;"></i>(ПІБ)</i></figcaption>
	</td>
	</tr>
	</table>
	<div style='page-break-after: always;'></div>
<?php } ?>   
<script src="<?=$this->files?>scripts/jquery.js" charset="utf-8"></script>
<script>
$(function(){
 $('.qr<?=$this->order->getId()?>').html($('#qr<?=$this->order->getId()?>').html());
 });
</script>
    </body>
</html>