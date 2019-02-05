<?php if(@$this->id_order){ ?>
<script>
dataLayer = [{
    'transactionId' : '<?=$this->id_order?>',
	'transactionAffiliation' : 'www.red.ua',
    'transactionTotal' : '<?=$this->summ?>'
}];
dataLayer.push({'id_page' : 'pays'});
dataLayer.push({'event' : 'quick' , 'eventAction' : 'add_quick'});
</script>
	<?php
					//register sellaction
if (isset($_COOKIE["SAuid"]) && isset($_COOKIE["utm_source"]) && $_COOKIE["utm_source"] == "sellaction.net") {
    echo '<img src="http://sellaction.net/reg.php?id='.$_COOKIE["SAuid"].'-1573_'.$this->summ.'&order_id='.$this->id_order.'" width="1" height="1" alt="" />';
	wsSellaction::add($this->id_order, $this->summ);
}		
//exit register sellaction
					}
	?>
<div class="alert alert-success" role="alert">
<h4><?=$this->trans->get('Ваш заказ принят')?>.</h4>
<p><?=$this->trans->get('Наши менеджеры свяжутся с Вами для уточнения деталей')?>.</p>
<p><?=$this->trans->get('Если у Вас возникли вопросы, Вы можете перезвонить к нам в офис по телефону (044) 224-40-00,(063) 809-35-29, (067) 406-90-80 либо написать письмо администратору')?>: <a href="mailto:market@red.ua">market@red.ua</a>. <br><?=$this->trans->get('Наши сотрудники с радостью предоставят Вам всю необходимую информацию')?>.</p>
</div>


