<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <title>Чек</title>
	<meta name="robot" content="no-index,no-follow"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script src="<?=$this->files;?>scripts/jquery.js" type="text/javascript" charset="utf-8"></script>
</head>
<style type="text/css">
    body {font-size: 8px; font-family: Verdana;}
	.tt_border_bottom {border-bottom: solid 1px #000;}
    .tt_border_top {border-top: solid 1px #000;}
    .fnt_size_1 {font-size: 12px;}
	.fnt_size_10{font-size: 10px;} 
    .fnt_size_2 {font-size: 11px;}
    .fnt_size_3 {font-size: 15px;}
    .border_all {border-top: 1px solid #000;border-left: 1px solid #000;}
    .border_left {border-left: 1px solid #000;}
    .border_right {border-right: 1px solid #000;}
    .border_top {border-top: 1px solid #000;}
    .border_none {border: none;}
</style>
<body onload="window.print()">
<?php
require_once("QRCode/qrcode.php");
for ($step = 0; $step < 2; $step++) {
//$res = '';
    ?>
    <table border="0" cellpadding="3" cellspacing="0" width="250">
	<tr>
            <td colspan="4"  style="text-align: center;"><img src="/img/logo/RED_Logo_RGB.png" style="padding-bottom: 5px;width: 100px;" alt=""/></td>
	<td colspan="3" style="text-align: center;" >
	<img src="/images/barcodeimage.php?text=<?=$this->order->getId()?>" alt="Barcode Image" />
	</td>
	</tr>
    <tr>
	
        <td colspan="7" align="center" class="fnt_size_3">
        <strong>Супроводжуючий документ на товар № <?=$this->order->id?></strong>
	<?=$this->order->getComlpect()?'<br><span>('.substr(implode(explode(";", $this->order->getComlpect()), ', '), 0, -2).')</span><br>':''?>
        </td>
    </tr>
    <tr>
        <td colspan="7" align="center" class="fnt_size_1"><strong>від <?=$this->date_today?> року</strong></td>
    </tr>
    <tr>
        <td colspan="7"><b>Отримувач:</b> <?=$this->order->getName() . ' ' . $this->order->getMiddleName()?>
        </td>
    </tr>
        <tr>
        <td colspan="7"><b>Контактний телефон:</b><?=$this->order->getTelephone()?>
        </td>
    </tr>
    <tr>
        <td colspan="7"><strong>Коментарі до замовлення: </strong><?=strip_tags($this->order->getDeliveryType()->getName())?></td>
    </tr>
    <tr>
        <td colspan="7">
                <?php if ($this->getOrder()->getComments()) { ?>
                    <div class="comm_cli">
                        <b>Коментарі клієнта: </b>
                        <?=$this->getOrder()->getComments()?>
                    </div>
                <?php } ?>
        </td>
    </tr>
    <!-- TOVAR TITLES-->
    <tr>
        <td class="border_all" align="center"><strong>#</strong></td>
        <td class="border_all" align="center"><strong>Назва товару</strong></td>
        <td class="border_all" align="center"><strong>Ціна</strong></td>
        <td class="border_all" align="center"><strong>Скидка</strong></td>
        <td class="border_all" align="center"><strong>redcoin</strong></td>
         <td class="border_all" align="center"><strong>Кілл.(од.)</strong></td>
        <td class="border_all border_right" align="center"><strong>Сума</strong></td>
    </tr>
    <!-- TOVAR -->
    <?php
	
	$sk = 0;
    $i = 1;
   // $to_pay = 0;
    $to_pay_minus = 0.00;
	$price_real = 0;
	$t_real_price = 0;
	$price_show = 0;
        $qr_cod = [];
        $qr_cod[] = '%ID'.$this->order->customer->id;
        $qr_cod[] ='ORDER'.$this->order->id;
        $cod ='%ID'.$this->order->customer->id.'&ORDER'.$this->order->id.'&';
        if($this->order->deposit > 0){
           $cod.='DEPO'.Number::formatFloat($this->order->deposit).'&'; 
           $qr_cod[] = 'DEPO'.Number::formatFloat($this->order->deposit);
        }
        if($this->order->bonus > 0){
            $cod.='COIN'.Number::formatFloat($this->order->bonus).'&'; 
            $qr_cod[] = 'COIN'.Number::formatFloat($this->order->bonus);
        }
        
         $to_pay = $this->getOrder()->calculateOrderPrice2(); //общая сумма к оплате
        
    foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
        if ($article_rec->getCount()) {
		
            for ($a_cnt = 0; $a_cnt < $article_rec->getCount(); $a_cnt++) { ?>
                <tr>
                    <td class="border_all " align="center"><strong><?=$i?></strong></td>
                    <td class="border_all " >
						<b>
<?php echo $article_rec->article_db->getTitle() . ', <br>' . wsActiveRecord::useStatic('Size')->findById($article_rec->getSize())->getSize() . ', ' . wsActiveRecord::useStatic('Shoparticlescolor')->findById($article_rec->getColor())->getName();?>
							<br>
							<?=$article_rec->getCode()?>
							<br>
							<font ><?=$article_rec->article_db->category->getRoutezGolovna()?></font>
						</b>
                    </td>
                    
                    <td class="border_all " align="right">
<?php
$price_real = (int)$article_rec->getOldPrice() ? $article_rec->getOldPrice() : $article_rec->getPrice();//стартовая цена
		//$t_real_price += $price_real;//сумируется стартовая цена
	
		$price_show = $article_rec->getPerc($this->order->getAllAmount());//вычисление цены и скидка на товар
                $t_real_price +=($price_show['price']/$article_rec->getCount())-$price_show['coin'];// общая сумма заказа
		$skid_show = round(((1 - (($price_show['price']/$article_rec->getCount())/ $price_real)) * 100), 2);//вычисление процента скидки по товару
				
		$sk = Number::formatFloat($skid_show, 2);
					
					
		if($skid_show == 100){ $sk = Number::formatFloat(99.99, 2);}
			
 echo Number::formatFloat($price_real, 2); 
 
 ?>
                    </td>
                    <td class="border_all " align="right">
                        <?php
$st = (int)$article_rec->getOldPrice() ? 'style="font-weight: 600;"' : 'style="text-decoration: underline;font-weight: 600;"';//уценялся
	if((int)$article_rec->getEventSkidka()){ $st = 'style="font-weight: 600;"';}//наличие доп скидки
					
echo $skid_show ? '<span '.$st.'>'.ceil($skid_show).' %</span>' : '';?>
                    </td>
                    <td class="border_all" align="center"><strong><?=Number::formatFloat($article_rec->coin?$article_rec->coin:'', 2)?></strong></td>
                    <td class="border_all" align="center"><strong>1</strong></td>
                    <td class="border_all border_right" align="right">
<strong><?=Number::formatFloat($article_rec->getCount() > 1 ? ($price_show['price']/$article_rec->getCount())-$price_show['coin'] : $price_show['price']-$price_show['coin']); ?></strong>
                    </td>
                </tr>
                <?php
                $i++;
                $to_pay_minus+=$price_show['minus']/$article_rec->getCount();
            }
			$cod.=$article_rec->getCode().'/'.$article_rec->getCount().'/'.$sk.'&';
                        $qr_cod[] =$article_rec->getCode().'/'.$article_rec->getCount().'/'.$sk;
        }
		
    }
	
	//if($this->getOrder()->getBonus() > 0){ $bonus = true; }else{ $bonus = false; }
	
   
	
	//$to_pay_minus = $t_real_price - ($this->getOrder()->getAmount()-$this->getOrder()->dop_summa-$this->getOrder()->getDeliveryCost());//общая скидка
	
   // $kop = round(($to_pay - toFixed($to_pay)) * 100, 0);//
    ?>
    <tr>
        <td colspan="5" class="tt_border_top" align="right"><strong>Всього:</strong></td>
        <td class="border_left border_right tt_border_top" align="right" colspan="2"><strong><?=Number::formatFloat($t_real_price, 2)?></strong></td>
    </tr>
   <!-- <tr>
        <td colspan="4" align="right"><i>Скидка клиента</i></td>
        <td class="border_all border_right" align="right" colspan="2"><i><?php //$this->order->getDiscont()?> %*</i></td>
    </tr>-->
		    <?php
      if($this->getOrder()->getKuponPrice() > 0) {?>
     <tr>
        <td colspan="5" align="right"><i style="font-weight: bold;">Код на скидку</i></td>
        <td class="border_all border_right" align="right" colspan="2"><i style="font-weight: bold;"><?=$this->getOrder()->getKupon()?></i></td>
	</tr>
    <tr>
		<td colspan="5" align="right"><i style="font-weight: bold;">Скидка по коду</i></td>
        <td class="border_all border_right" align="right" colspan="2"><i style="font-weight: bold;"><?=$this->getOrder()->getKuponPrice()?> %</i></td>
	</tr>
    <?php } ?>
	<!--<tr>
		<td colspan="4" align="right"><i>Сумма общей скидки</i></td>
		<td class="border_all border_right" align="right" colspan="2"><i><?php //Number::formatFloat($to_pay_minus, 2)?></i></td>
	</tr>-->
		<?php
		//$min_sum_bonus = Config::findByCode('min_sum_bonus')->getValue();
		if($this->getOrder()->getBonus() > 0){ ?>
	<tr>
		<td colspan="5" align="right"><b>Redcoin</b></td>
		<td class="border_all border_right" align="right" colspan="2"><i><?=Number::formatFloat($this->getOrder()->getBonus(), 2)?></i></td>
	</tr>
                <?php }
                if(false){
                ?>
	<tr>
		<td colspan="5" align="right"><i>Вартість із врахуванням кидок</i></td>
		<td class="border_all border_right" align="right" colspan="2"><i>
		<?=Number::formatFloat(($this->order->calculateOrderPrice2(true, true, false)), 2)?>
		</i>
        </td>
        </tr>
   
    <tr>
	<td colspan="4" align="right" class="fnt_size_1">Доставка:</td>
        <td class="border_all border_right" align="right" colspan="2"><?=Number::formatFloat($this->getOrder()->getDeliveryCost(), 2);?></td>
    </tr>
     <?php } ?>
    <?php  if ($this->getOrder()->getDeposit() > 0) { ?>
	<tr>
		<td colspan="4" align="right"><i>Депозит</i></td>
		<td class="border_all border_right" align="right" colspan="2"><i><?=Number::formatFloat($this->getOrder()->getDeposit(), 2)?></i></td>
	</tr>
    <?php } ?>
    
    <tr>
		<td colspan="5" class="border_none fnt_size_1" align="right"><strong style="text-transform: uppercase;">Всього до сплати:</strong><br/>
            <span>Без ПДВ</span>
        </td>
        <td class="border_all border_right tt_border_bottom" align="right" colspan="2"><strong><?=$to_pay?></strong></td>
    </tr>
	    <tr>
        <td colspan="7" class="tt_border_bottom"><b>Вдалих покупок!</b></td>
    </tr>
		<tr><td colspan="7">
            <br/>
            <i>Всього найменувань <strong><?php echo($i - 1) ?></strong>, на суму <strong>
                    <?php $sum = explode(',', $to_pay); echo $sum[0];?> грн.
                    <?php echo @$sum[1] ? @$sum[1] : '00' ?> коп.
                    (<?=Number::formatToStringUk((int)$to_pay)?>)</strong></i>
            <br/>
        </td>
    </tr>
	<tr><td colspan="7" style="text-align: center;" class="qr<?=$this->getOrder()->getId()?>" ></td></tr>

    <tr>
        <td colspan="7" style=" padding-bottom: 30px;">
            Персональні дані (ПІБ і адресу) Покупця були передані інтернет-магазину RED.ua, з метою виконання даного
             замовлення Покупця, і в подальшому можуть передаватися уповноваженим органам в установленому законом порядку.
             Як суб'єкт персональних даних Покупець має всі права, передбачені ст. 8 Закону України «Про захист
             персональних даних».
        </td>
    </tr>
    </table>
    <div style='page-break-after: always;'></div>
<?php
 }
$qr = new qrcode();
$qr->text($cod);
		echo "<p id='qr".$this->order->getId()."' hidden><img src='".$qr->get_link()."' style='max-width: 200px;' border='0'/></p>";
		//echo $cod;
 ?>
 <script>
     $(function(){
            $('.qr<?=$this->order->getId()?>').html($('#qr<?=$this->order->getId()?>').html());
        });
 </script>
</body>
</html>
