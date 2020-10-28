<?php if($this->order){
    $id = $this->order->getId();
    $summ = $this->order->calculateOrderPrice2(true, false);
    ?>
<script>
dataLayer = [{
    'transactionId' : '<?=$id?>',
	'transactionAffiliation' : 'www.red.ua',
    'transactionTotal' : '<?=$summ?>'
}];
dataLayer.push({'id_page' : 'pays'});
dataLayer.push({'event' : 'quick' , 'eventAction' : 'add_quick'});
</script>
	<?php
					//register sellaction
if (isset($_COOKIE["SAuid"]) && isset($_COOKIE["utm_source"]) && $_COOKIE["utm_source"] == "sellaction.net") {
echo '<img src="https://sellaction.net/reg.php?id='.$_COOKIE["SAuid"].'-1573_'.$summ.'&order_id='.$id.'" width="1" height="1" alt="" />';
	wsSellaction::add($id, $summ);
}
if(isset($_COOKIE["utm_email_track"])){
      Emailpost::quickOrderEmail(['track'=>$_COOKIE["utm_email_track"], 'order'=>$id, 'amount'=>$summ, 'count_article'=>$this->order->countArticlesSum()]);
}
//exit register sellaction
}
?>
<div class="alert alert-success" role="alert">
<h4><?=$this->trans->get('Ваш заказ принят')?>.</h4>
<p><?=$this->trans->get('Наши менеджеры свяжутся с Вами для уточнения деталей')?>.</p>
<p><?=$this->trans->get('Если у Вас возникли вопросы, Вы можете перезвонить к нам в офис по телефону (044) 224-40-00,(063) 809-35-29, (067) 406-90-80 либо написать письмо администратору')?>: <a href="mailto:market@red.ua">market@red.ua</a>. <br><?=$this->trans->get('Наши сотрудники с радостью предоставят Вам всю необходимую информацию')?>.</p>
</div>


