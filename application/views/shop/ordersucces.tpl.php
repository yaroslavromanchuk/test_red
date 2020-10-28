
<div class="container">
  <?php //$this->getCurMenu()->getPageBody()?>
<?php
if(isset($_SESSION['orders'])){ 
    foreach ($_SESSION['orders'] as $value) {
        $order = new Shoporders((int)$value);
        $sum = $order->calculateOrderPrice2(true, false);
        ?>
    <script>
	dataLayer = [{
    'transactionId': '<?=$order->getId()?>',
	'transactionAffiliation': 'www.red.ua',
    'transactionTotal': '<?=$sum?>'
}];

dataLayer.push({'id_page': 'pays'});
ga('send', {hitType: 'event', eventCategory: 'order',  eventAction: 'new_order', 'eventLabel' : '<?=$order->getId()?>', 'eventValue' : '<?=$sum?>' });
//dataLayer.push({'event' : 'order','eventAction' : 'new_order', 'eventLabel' : '<?=$order->getId()?>', 'eventValue' : '<?=$sum?>' });

</script>
<?php
//register sellaction
if (isset($_COOKIE["SAuid"]) && isset($_COOKIE["utm_source"]) && $_COOKIE["utm_source"] == "sellaction.net") {
	//$sa_tariff_id = '1573';
echo '<img src="https://sellaction.net/reg.php?id='.$_COOKIE["SAuid"].'-1573_'.$sum.'&order_id='.$order->getId().'" width="1" height="1" alt="" />';
	wsSellaction::add($order->getId(), $sum);
}
if(isset($_COOKIE["utm_email_track"])){
     Emailpost::quickOrderEmail(['track'=>$_COOKIE["utm_email_track"], 'order'=>$order->getId(), 'amount'=>$sum, 'count_article'=>$order->countArticlesSum()]);
}
?>
<div style="max-width: 320px;" class="card mx-auto mb-2 text-center"  >
<!--<p><span style="font-size: 13px;"><?=$this->trans->get('Ваш заказ успешно оформлен. В течение нескольких минут Вы получите sms и e-mail со сведениями о заказе')?>.</span></p>-->
<h5 class="card-header px-1">
    Ваш заказ<br> #<?=$order->getId()?>
  </h5>
<div class="card-body p-2">
<table align="center"   cellpadding="0" cellspacing="0" >
<tr style="text-align: left;"><td style=" padding: 5px;" ><?=$this->trans->get('Сумма заказа')?>:</td><td style=" padding: 10px;"><?=$order->amount." грн."; ?></td></tr>
<tr style="text-align: left;"><td style=" padding: 10px;"><?=$this->trans->get('Способ доставки')?>:</td><td style=" padding: 10px;">
<?=$order->getDeliveryType()->getName()?></td></tr>
<tr style="text-align: left;"><td style=" padding: 10px;"><?=$this->trans->get('Стоимость доставки')?>:</td><td style=" padding: 10px;"><?php if($order->delivery_type_id == 8 || $order->delivery_type_id == 16){ echo "По тарифам</br>Новой Почты.";}else{ echo $order->delivery_cost." грн.";}?></td></tr>			
<tr style="text-align: left;"><td style=" padding: 10px;"><?=$this->trans->get('Способ оплаты')?>:</td><td style=" padding: 10px;">
<?=$order->getPaymentMethod()->getName()?></td></tr>

<tr>
<td colspan="2" style="color: #0c69a0;padding: 10px;">
<?php if($order->delivery_type_id == 8 || $order->delivery_type_id == 16){
echo $this->trans->get('Ждите смс с номером ТТН');
}elseif($order->delivery_type_id == 4){
echo 'Ждите смс с номером ТТН';
}elseif($order->delivery_type_id == 9){
echo 'Ждите звонка менеджера';
}elseif($order->delivery_type_id == 3 || $order->delivery_type_id == 12){
echo $this->trans->get('Ждите смс о прибытии заказа в магазин');
} ?>
</td>
</tr>
</table>
    <div class="card-body">
        <?php if(($order->payment_method_id == 4 or $order->payment_method_id == 6) and $order->liqpay_status != 3 /* and $order->status == 100*/){ ?>

<form action="/payment/powtorpay/" method="POST" name="payment" target="_blank">
<div class="form-group" >
    <input  name="order" type="hidden" required  class="form-control" value="<?=$order->id?>">
    <input  name="payment_sistem" type="hidden" required  class="form-control" value="<?=$order->payment_method_id?>">
    <button type="submit" onclick="$(this).hide();"  class="btn btn-success btn-lg card-link">Оплатить</button>
</div>                
</form>
<?php } ?>
  </div>
</div>
</div>
<?php  
		
					//exit register sellaction
unset($_SESSION['order']);
unset($_SESSION['total_price']);
unset($_SESSION['order_amount']);
    }
   unset($_SESSION['orders']);
}


 if(@$_SESSION['order']['id']){
 $order = new Shoporders((int)$_SESSION['order']['id']);
 ?>
<script>
	dataLayer = [{
    'transactionId': '<?=$order->getId()?>',
	'transactionAffiliation': 'www.red.ua',
    'transactionTotal': '<?=$order->amount+$order->deposit+$order->bonus?>'
}];

dataLayer.push({'id_page': 'pays'});
ga('send', {hitType: 'event', eventCategory: 'order',  eventAction: 'new_order' });
//dataLayer.push({'event' : 'order','eventAction' : 'new_order', 'eventLabel' : '<?=$order->getId()?>', 'eventValue' : '<?=$order->getAmount()?>' });

</script>
<?php
//register sellaction
if (isset($_COOKIE["SAuid"]) && isset($_COOKIE["utm_source"]) && $_COOKIE["utm_source"] == "sellaction.net") {
	//$sa_tariff_id = '1573';
echo '<img src="https://sellaction.net/reg.php?id='.$_COOKIE["SAuid"].'-1573_'.($order->amount+$order->deposit+$order->bonus).'&order_id='.$order->getId().'" width="1" height="1" alt="" />';
	wsSellaction::add($order->getId(), ($order->amount+$order->deposit+$order->bonus));
}
if(isset($_COOKIE["utm_email_track"])){
     Emailpost::quickOrderEmail(['track'=>$_COOKIE["utm_email_track"], 'order'=>$order->getId(), 'amount'=>$order->amount+$order->deposit+$order->bonus, 'count_article'=>$order->countArticlesSum()]);
}
?>
<div style="text-align:center;">
<p><span style="font-size: 13px;"><?=$this->trans->get('Ваш заказ успешно оформлен. В течение нескольких минут Вы получите sms и e-mail со сведениями о заказе')?>.</span></p>
<?php if($_SESSION['pay']){ ?>
<form action="https://lmi.paysoft.solutions" method="POST" name="payment_form" id="payment_form">
<div class="row">
<div class="col-xs-10 col-xs-offset-1">
	<div class="col-xs-6 form-group">
	<input id="LMI_MERCHANT_ID" name="LMI_MERCHANT_ID" type="hidden" required="" placeholder="LMI_MERCHANT_ID" class="form-control" value="<?=$_SESSION['pay']['LMI_MERCHANT_ID']?>">
				</div>

				<div class="col-xs-6 form-group">
					<input id="LMI_PAYMENT_AMOUNT" name="LMI_PAYMENT_AMOUNT" type="hidden" required="" placeholder="LMI_PAYMENT_AMOUNT" class="form-control" value="<?=$_SESSION['pay']['LMI_PAYMENT_AMOUNT']?>">
				</div>
				
				<div class="col-xs-6 form-group">
					<input id="LMI_PAYMENT_NO" name="LMI_PAYMENT_NO" type="hidden" required="" placeholder="LMI_PAYMENT_NO" class="form-control" value="<?=$_SESSION['pay']['LMI_PAYMENT_NO']?>">
				</div>

				<div class="col-xs-6 form-group">
					<input id="LMI_PAYMENT_DESC" name="LMI_PAYMENT_DESC" type="hidden" required="" placeholder="LMI_PAYMENT_DESC" class="form-control" value="<?=$_SESSION['pay']['LMI_PAYMENT_DESC']?>">
				</div>
				<div class="col-xs-6 form-group">
					<input id="LMI_PAYMENT_SYSTEM" name="LMI_PAYMENT_SYSTEM" type="hidden" required="" placeholder="LMI_PAYMENT_SYSTEM" class="form-control" value="<?=$_SESSION['pay']['LMI_PAYMENT_SYSTEM']?>">
				</div>

				<div class="col-xs-6 form-group">
				<input id="LMI_SIM_MODE" name="LMI_SIM_MODE" type="hidden" required=""  placeholder="LMI_SIM_MODE" class="form-control" value="<?=$_SESSION['pay']['LMI_SIM_MODE']?>">
				</div>
				<div class="col-xs-6 form-group">
					<input id="LMI_HASH" name="LMI_HASH" type="hidden" required="" placeholder="LMI_HASH" class="form-control" value="<?=$_SESSION['pay']['LMI_HASH']?>">
				</div>

				<div class="col-xs-12 form-group">
					<button type="submit" style="display:none;" class="btn btn-default">Оплата</button>
				</div>
		</div>
	</div>
</form>
<?php  
unset($_SESSION['pay']);
unset($_SESSION['total_price']);
unset($_SESSION['order_amount']);
?>
<script>
	document.forms.payment_form.submit();
</script>
<?php }else{ ?>
<p style="
    font-weight: bold;
    font-size: 20px;
    padding-top: 5px;
    padding-bottom: 10px;
"><?=$this->trans->get('Номер Вашего заказа')?> #<?=$order->getId();?></p>
<table align="center" style="font-weight: bold;font-size: 13px;border-radius: 5px;box-shadow: 0 0 5px 5px #ddd;" cellpadding="0" cellspacing="0" >
<tr style="text-align: left;"><td style=" padding: 5px;" ><?=$this->trans->get('Сумма заказа')?>:</td><td style=" padding: 10px;"><?=$order->amount." грн."; ?></td></tr>
<tr style="text-align: left;"><td style=" padding: 10px;"><?=$this->trans->get('Способ доставки')?>:</td><td style=" padding: 10px;">
<?=$order->getDeliveryType()->getName()?></td></tr>
<tr style="text-align: left;"><td style=" padding: 10px;"><?=$this->trans->get('Стоимость доставки')?>:</td><td style=" padding: 10px;"><?php if($order->delivery_type_id == 8 || $order->delivery_type_id == 16){ echo "По тарифам</br>Новой Почты.";}else{ echo $order->delivery_cost." грн.";}?></td></tr>			
<tr style="text-align: left;"><td style=" padding: 10px;"><?=$this->trans->get('Способ оплаты')?>:</td><td style=" padding: 10px;">
<?=$order->getPaymentMethod()->getName()?></td></tr>
<?php if($order->payment_method_id == 7 and false){ ?>
<tr>
<td colspan="2">
<?php $data = $order->LiqPay(); ?>
<form method="POST" action="<?=$data['action']?>" id="liqpay_checkout_<?=$order->getId()?>" accept-charset="utf-8">
    <input type="hidden" name="data"  value="<?=$data['data']?>" />
    <input type="hidden" name="signature" value="<?=$data['signature']?>" />
    <div class="buttons">
        <div class="center">
            <input type="submit" value="<?=$data['button_confirm']?>" id="button-confirm" class="btn btn-success button" />
        </div>
    </div>
</form>
</td>
</tr>
<?php } ?>
<tr>
<td colspan="2" style="color: #0c69a0;padding: 10px;">
<?php if($order->delivery_type_id == 8 || $order->delivery_type_id == 16){
echo $this->trans->get('Ждите смс с номером ТТН');
}elseif($order->delivery_type_id == 4){
echo 'Ждите смс с номером ТТН';
}elseif($order->delivery_type_id == 9){
echo 'Ждите звонка менеджера';
}elseif($order->delivery_type_id == 3 || $order->delivery_type_id == 12){
echo $this->trans->get('Ждите смс о прибытии заказа в магазин');
} ?>
</td>
</tr>
</table>
<?php  
		
					//exit register sellaction
unset($_SESSION['order']);
unset($_SESSION['total_price']);
unset($_SESSION['order_amount']);
 } ?>
 
<p style="margin-top:10px;"><span style="font-size: 13px;"><a href="/pays/" target="_blank"><?=$this->trans->get('Условия/график доставки и оплаты')?></a></span></p>
<p>
<span style="font-size: 13px;"><?=$this->trans->get('Если у Вас возникли вопросы, Вы можете перезвонить к нам в офис по телефону<strong> (044) 224-40-00</strong>, либо написать письмо администратору')?>: <a href="mailto:market@red.ua">market@red.ua</a>. <?=$this->trans->get('Наши сотрудники с радостью предоставят Вам всю необходимую информацию')?>.</span>
</p>
<p><span style="font-size: 13px;"><?=$this->trans->get('Благодарим за Ваш выбор')?>.</span></p>
<a href="/account/" class="btn btn-danger"><?=$this->trans->get('Перейти в личный кабинет')?></a>
</div>
<?php } ?>
</div>