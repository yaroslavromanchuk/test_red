<?php
$_SESSION['order']['id'] = 338900;
$order = $order = new Shoporders((int)$_SESSION['order']['id']);
 ?>
<script>
	dataLayer = [{
    'transactionId': '<?=$order->getId()?>',
	'transactionAffiliation': 'www.red.ua',
    'transactionTotal': '<?=$order->getAmount()?>'
}];

dataLayer.push({'id_page': 'pays'});

dataLayer.push({'event' : 'order','eventAction' : 'new_order', 'eventLabel' : '<?=$order->getId()?>', 'eventValue' : '<?=$order->getAmount()?>' });

</script>
<?php
//register sellaction
if (isset($_COOKIE["SAuid"]) && isset($_COOKIE["utm_source"]) && $_COOKIE["utm_source"] == "sellaction.net") {
	$sa_tariff_id = '1573';
    echo '<img src="http://sellaction.net/reg.php?id='.
    $_COOKIE["SAuid"].'-'.$sa_tariff_id.'_'.$order->getAmount().
    '&order_id='.$order->getId().'" width="1" height="1" alt="" />';
	wsSellaction::add($order->getId(), $order->getAmount());
}
?>
<?php if(true){
 ?>
<p><?=$this->getCurMenu()->getPageBody()?></p>
<div style="text-align:center;">
<p><span style="font-size: 13px;"><?=$this->trans->get('Ваш заказ успешно оформлен. В течение нескольких минут Вы получите sms и e-mail со сведениями о заказе')?>.</span></p>
<?php if(true){

$d = new DeliveryType($order->delivery_type_id);
?>
<p style="
    font-weight: bold;
    font-size: 20px;
    padding-top: 5px;
    padding-bottom: 10px;
"><?=$this->trans->get('Номер Вашего заказа')?> #<?=$_SESSION['order']['id'];?></p>
<table align="center" style="font-weight: bold;font-size: 13px;border-radius: 5px;box-shadow: 0 0 5px 5px #ddd;" cellpadding="0" cellspacing="0" >
<tr style="text-align: left;"><td style=" padding: 5px;" ><?=$this->trans->get('Сумма заказа')?>:</td><td style=" padding: 10px;"><?=$order->amount." грн."; ?></td></tr>
<tr style="text-align: left;"><td style=" padding: 10px;"><?=$this->trans->get('Способ доставки')?>:</td><td style=" padding: 10px;">
<?=$order->getDeliveryType()->getName()?></td></tr>
<tr style="text-align: left;"><td style=" padding: 10px;"><?=$this->trans->get('Стоимость доставки')?>:</td><td style=" padding: 10px;"><?php if($d->id == 8 || $d->id == 16){ echo "По тарифам</br>Новой Почты.";}else{ echo $order->delivery_cost." грн.";}?></td></tr>			
<tr style="text-align: left;"><td style=" padding: 10px;"><?=$this->trans->get('Способ оплаты')?>:</td><td style=" padding: 10px;">
<?=$order->getPaymentMethod()->getName()?></td></tr>
<?php if($order->payment_method_id == 7){ ?>
<tr>
<td colspan="2">
<?php $data = $this->data; ?>
<form method="POST" action="<?=$data['action']?>" id="liqpay_checkout" accept-charset="utf-8">
    <input type="hidden" name="data"  value="<?=$data['data']?>" />
    <input type="hidden" name="signature" value="<?=$data['signature']?>" />
    <div class="buttons">
        <div class="right">
            <input type="submit" value="<?=$data['button_confirm']?>" id="button-confirm" class="btn btn-success button" />
        </div>
    </div>
</form>
</td>
</tr>

<?php 
} ?>
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
 } ?>
 
<p style="margin-top:10px;"><span style="font-size: 13px;"><a href="/pays/" target="_blank"><?=$this->trans->get('Условия/график доставки и оплаты')?></a></span></p>
<p>
<span style="font-size: 13px;"><?=$this->trans->get('Если у Вас возникли вопросы, Вы можете перезвонить к нам в офис по телефону<strong> (044) 224-40-00</strong>, либо написать письмо администратору')?>: <a href="mailto:market@red.ua">market@red.ua</a>. <?=$this->trans->get('Наши сотрудники с радостью предоставят Вам всю необходимую информацию')?>.</span>
</p>
<p><span style="font-size: 13px;"><?=$this->trans->get('Благодарим за Ваш выбор')?>.</span></p>
<a href="/account/" class="btn btn-danger"><?=$this->trans->get('Перейти в личный кабинет')?></a>
</div>
<?php } ?>