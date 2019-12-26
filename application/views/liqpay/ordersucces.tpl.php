<div class="container">
<?php
 if($this->get->id){
$order = new Shoporders((int)$this->get->id);
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
if(isset($_COOKIE["utm_email_track"])){
     Emailpost::quickOrderEmail(['track'=>$_COOKIE["utm_email_track"], 'order'=>$order->getId(), 'amount'=>$order->getAmount()+$order->getDeposit(), 'count_article'=>$order->countArticlesSum()]);
}
?>
<div style="text-align:center;" class="card">
<?php 
//$d = new DeliveryType($order->delivery_type_id);
?>
<div class="card-header">
<?=$this->trans->get('Номер Вашего заказа')?> <b>#<?=$this->get->id?></b>
  </div>
    <div class="card-body">
<table  class="table" cellpadding="0" cellspacing="0" >
<tr style="text-align: left;"><th style=" padding: 5px;" ><?=$this->trans->get('Сумма заказа')?>:</th><td style=" padding: 10px;"><?=$order->amount." грн."; ?></td></tr>
<tr style="text-align: left;"><th style=" padding: 10px;"><?=$this->trans->get('Способ доставки')?>:</th><td style=" padding: 10px;">
<?=$order->getDeliveryType()->getName()?></td></tr>
<tr style="text-align: left;"><th style=" padding: 10px;"><?=$this->trans->get('Стоимость доставки')?>:</th><td style=" padding: 10px;"><?php if($order->delivery_type_id == 8 || $order->delivery_type_id == 16){ echo "По тарифам</br>Новой Почты.";}else{ echo $order->delivery_cost." грн.";}?></td></tr>			
<tr style="text-align: left;"><th style=" padding: 10px;"><?=$this->trans->get('Способ оплаты')?>:</th><td style=" padding: 10px;">
<?=$order->getPaymentMethod()->getName()?></td></tr>
<tr style="text-align: left;"><th style=" padding: 10px;"><?=$this->trans->get('Статус оплаты')?>:</th><td style=" padding: 10px;">
<?=$order->liqpay_status->getName()?></td></tr>
<?php if($order->payment_method_id == 7 and in_array($order->liqpay_status_id, [1,6,5])){ ?>
<tr>
<td colspan="2">
    <?=$this->form?>
<?php //$data = $this->data; ?>
    <!--
<form method="POST" action="<?=$data['action']?>" id="liqpay_checkout" accept-charset="utf-8">
    <input type="hidden" name="data"  value="<?=$data['data']?>" />
    <input type="hidden" name="signature" value="<?=$data['signature']?>" />
    <div class="buttons">
        <div class="right">
            <input type="submit" value="<?=$data['button_confirm']?>" id="button-confirm" class="btn btn-success button" />
        </div>
    </div>
</form>-->
</td>
</tr>
<?php 
} ?>
</table>
    <p style="margin-top:10px;"><span style="font-size: 13px;"><a href="/pays/" target="_blank"><?=$this->trans->get('Условия/график доставки и оплаты')?></a></span></p>
<p>
<span style="font-size: 13px;">
         <?=$this->trans->get('Если у Вас возникли вопросы, Вы можете перезвонить к нам в офис по телефону<strong> (044) 224-40-00</strong>, либо написать письмо администратору')?>: <a href="mailto:market@red.ua">market@red.ua</a>. <?=$this->trans->get('Наши сотрудники с радостью предоставят Вам всю необходимую информацию')?>.</span>
</p>    
    </div>
<?php  
//exit register sellaction
unset($_SESSION['order']);
unset($_SESSION['total_price']);
unset($_SESSION['order_amount']);
  ?>
    <div class="card-footer text-muted">
   <a href="/account/" class="btn btn-danger"><?=$this->trans->get('Перейти в личный кабинет')?></a>
  </div>


</div>
<?php } ?>
</div>
