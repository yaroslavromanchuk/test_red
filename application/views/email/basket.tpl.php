<?=$this->render('email/email.header.tpl.php');?>
<?php $text = explode(',', $this->trans->get('цвет,размер,количество,цена,всего,продолжить покупки,оформить заказ'));?>
<style>
.cart-table{border: 1px solid #8e8e8e; background: white; font-size: 14px; }
.cart-table > tbody > tr > th, .cart-table > tfoot > tr > th, .cart-table > thead > tr > td, .cart-table > tbody > tr > td, .cart-table > tfoot > tr > td{
    border: 0;
    border-top: 1px solid #8e8e8e;
    padding: 20px;
    vertical-align: middle;
	    text-align: center;
}
.cart-table .attention{color:red;}
.cart-table .t_bord{border-top: 1px solid white;padding: 5px;}
.cart-table > tbody > tr > td > .text-left {text-align:left;}
.cart-table > tbody > tr > td > .text-right {text-align:right;}
</style>
<table border="0" cellpadding="2" cellspacing="0" width="700" style="font-family: Verdana" align="center" >
<tr>
<td width=700 style="vertical-align:top">
<?php if($this->order['delivery_type_id'] == 3) { echo '<br><span style="font-size: 14px;color:#E8641B;">'.$this->trans->get('График работы пункта выдачи').' <b>Пн-Вс: 11:00- 21:00</b></span>'; }?>
<?php if($this->order['delivery_type_id'] == 5) { echo '<br><span style="font-size: 14px;color:#E8641B;">'.$this->trans->get('График работы пункта выдачи').' <b>Пн-Вс: 10:00- 20:00</b></span>'; }?>
<p><?=$this->trans->get('Дополнительную информацию можно получить по телефону');?>: (044) 224-40-00</p>
<?php if(in_array(@$this->order['delivery_type_id'],array(1,2,3,7,11,5))){ ?>
    <p style=" font-size: 14px;">
        <i><?=$this->trans->get('Условия оплаты и возврата заказа');?>:</i><br /><br />
              - <?=$this->trans->get('дисконтная скидка розничных магазинов RED недействительна для интернет-заказов');?>;<br />
              - <?=$this->trans->get('перед оплатой заказа, товары можно примерить в примерочной магазина,</br> рассмотреть и убедиться в правильной комплектации заказа');?>;<br />
              - <?=$this->trans->get('возврат товара до 14 дней с момента покупки');?>. <a href="https://www.red.ua/returns/"><?=$this->trans->get('Условия возврата');?></a>.<br /><br />
               <b><?=$this->trans->get('Удачных покупок');?>!</b>
    </p>
<?php } ?>

<h3 style="color:#E8641B;"><?=$this->trans->get('Список товаров');?>
    №<?=$this->order->getId();?></h3>
<table width="700"  class="table cart-table">
<thead>
    <tr>
<th>Продукт</th><th></th><th><?=$text[3];?></th><th><?=$text[2];?></th><th><?=$text[4];?></th>
    </tr>
	</thead>
    <?php 
	$t_count = 0; 
	$t_price = 0.00; 
	$total_price = 0.00;
    $to_pay = 0;
    $to_pay_minus = 0.00;
    $now_orders=0;
    $skidka = 0;
	
	$t_real_price = 0.00;
	$price_real = 0.00;
	$sum_skudka = 0.00;
	$skid = '';

     $order = new Shoporders((int)$this->order->getId());
	 //$this->view->order = $order;

            $all_orders = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery('
    SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
   WHERE ws_orders.customer_id = ' . $order->getCustomerId() . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0);
    
	$now_orders = $all_orders->getAmount();

    $now_orders += $order->getAmount();
	
    foreach ($order->getArticles() as $article_or) {
	
    $at = new Shoparticles($article_or->getArticleId());
		
   if($article_or->getCount() == 0 ){ $count = 'нет на складе';}else{$count = $article_or->getCount(); }
	
	$price_real = (int)$article_or->getOldPrice() ? $article_or->getOldPrice() : $article_or->getPrice();
		$t_real_price += $price_real * $article_or->getCount();
	
	$price = $article_or->getPerc($order->getAllAmount());
		$sum_skudka += $price['minus'];
		
	if($article_or->getCount() > 0){
	$skid_show = round((1 - (($price['price']/$article_or->getCount()) / $price_real)) * 100);
	}

        ?>
   <tr>
       <td>
	   <a href="https://<?=$_SERVER['HTTP_HOST'].$at->getPath()?>" >
	   <img width="100" src="https://<?=$_SERVER['HTTP_HOST'].$article_or->getImagePath('listing'); ?>" alt="<?=htmlspecialchars($article_or->getTitle());?>"/>
	   </a>
		</td>
		
       <td style="text-align:left;">
	   <b><?=$article_or->getTitle()?></b><br>
	   <?=$text[0]?>:<?=wsActiveRecord::useStatic('Shoparticlescolor')->findById($article_or->getColor())->getName()?> | <?=$text[1];?>:<?=wsActiveRecord::useStatic('Size')->findById($article_or->getSize())->getSize(); ?>
	   </td>
	   
       <td>		
	    <?php if($article_or->getCount() > 0) {
		$p = $price['price']/$article_or->getCount();
		if ($price_real != $p){ $pr = $price_real; }else{ $pr = ''; }
	
		$skid = '  -'.round((1 - ($p/ $price_real)) * 100).'%';
		
	echo '<span style="text-decoration: line-through;color: #666;font-weight: normal;font-size: 10px;">'.$pr.'</span><span style="font-size: 10px;color:red;font-weight: bold;position: relative;top: -5px;"> '.$skid.'</span><br>'.Shoparticles::showPrice($p).' грн.';

		}else { echo $count; } ?>
	   </td>
	   
       <td>
	   <?php $t_count += $count; echo $count; ?>
	   </td>
	   
       <td>
	    <?php echo Shoparticles::showPrice($price['price']); ?> грн.
	   </td>
        </tr>
<?php } ?>
	</table>
	<table width="700"  class="table cart-table">
		<tr>
				<td style="text-align:left;" colspan="3">
					<?=$this->trans->get('Способ доставки')?>:
				</td>
				<td style="text-align:right;" colspan="2" ><?=$order->getDeliveryType()->getName()?></td>
			</tr>
				<tr>
				<td style="text-align:left;" colspan="3">
					<?=$this->trans->get('Доставка')?>:
				</td>
				<td style="text-align:right;" colspan="2" >
						<?php if($order->delivery_type_id == 8 || $order->delivery_type_id == 16){
		echo $this->trans->get('По тарифам</br>Новой Почты');
		}else{
		echo Shoparticles::showPrice($order->delivery_cost)." грн.";
		}?>
				</td>
			</tr>
			<tr>
				<td style="text-align:left;" colspan="3">
					<?=$this->trans->get('Способ оплаты')?>:
				</td>
				<td style="text-align:right;" colspan="2" ><?=$order->getPaymentMethod()->getName()?></td>
			</tr>
			
	<?php if($order->kupon_price > 0){ ?>
	<tr>
	<td style="text-align:left;" colspan="3"><?=$this->trans->get('Код скидки')?>:
	</td>

    <td style="text-align:right;" colspan="2" ><?=$order->kupon?></td>
    </tr>
    <tr>
        <td style="text-align:left;" colspan="3"><?=$this->trans->get('Скидка по коду')?>:</td>
        <td style="text-align:right;" colspan="2"><?=(int)$order->kupon_price?> %</td>
    </tr>
	<?php } ?>
	
	<?php if($order->skidka > 0){ ?>
	<tr>
        <td style="text-align:left;" colspan="3"><?=$this->trans->get('Скидка')?>:</td>
        <td style="text-align:right;" colspan="2"><?=$order->skidka?> %</td>
    </tr>
	<?php } ?>

	<?php if($order->getBonus() > 0){ ?>
		<tr>
        <td style="text-align:left;" colspan="3"><?=$this->trans->get('Бонусная скидка')?>:</td>
        <td style="text-align:right;" colspan="2"><?=$order->getBonus()?> грн.</td>
		</tr>
	<?php } ?>
		<tr>
        <td style="text-align:left;" colspan="3"><?=$this->trans->get('Сумма скидки')?>:</td>
        <td style="text-align:right;" colspan="2"><?=Shoparticles::showPrice($sum_skudka)?> грн.</td>
		</tr>
    <?php if($order->deposit > 0){ ?>
	<tr>
        <td style="text-align:left;" colspan="3"><?=$this->trans->get('Депозит')?>:</td>
        <td style="text-align:right;" colspan="2"><?=$order->deposit?> грн.</td>
	</tr>
    <?php } ?>
	<tr>
        <td style="text-align:left;" colspan="3"><strong><?=$this->trans->get('Всего к оплате')?>:</strong></td>
        <td style="text-align:right;" colspan="2"><strong>
			  <?php //$t_count
	if($order->getBonus() > 0){ $bonus = true; }else{ $bonus = false; }
      echo Number::formatFloat($order->calculateOrderPrice2(true, true, true, $bonus), 2);
            ?> грн.
		</strong></td>
	</tr>
</table>
<br/>



<!--
<h3 style="color:#E8641B;"><?=$this->trans->get('Контактная информация')?></h3>
<table cellspacing="0" cellpadding="4" class="basket-cont" width="700"
       style="border:1px dashed #E8641B;padding:15px; font-size: 12px;">
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?=$this->trans->get('Компания');?></td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php echo (isset($this->basket_contacts['company']))
                ? $this->basket_contacts['company'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?=$this->trans->get('Имя');?></td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php echo (isset($this->basket_contacts['name']))
                ? $this->basket_contacts['name'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?=$this->trans->get('Город');?></td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php echo (isset($this->basket_contacts['city']))
                ? $this->basket_contacts['city'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?=$this->trans->get('Способ доставки');?></td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php $d = new DeliveryType(@$this->basket_contacts['delivery_type_id']); echo (isset($this->basket_contacts['delivery_type_id'])) ? $d->getName() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?=$this->trans->get('Адрес');?></td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php echo (isset($this->basket_contacts['address']))
                ? $this->basket_contacts['address'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?=$this->trans->get('Индекс');?></td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php echo (isset($this->basket_contacts['index']))
                ? $this->basket_contacts['index'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?=$this->trans->get('Улица');?></td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php echo (isset($this->basket_contacts['street']))
                ? $this->basket_contacts['street'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?=$this->trans->get('Дом');?></td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php echo (isset($this->basket_contacts['house']))
                ? $this->basket_contacts['house'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?=$this->trans->get('Квартира');?></td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php echo (isset($this->basket_contacts['flat']))
                ? $this->basket_contacts['flat'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?=$this->trans->get('Способ оплаты');?></td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php $p = new PaymentMethod(@$this->basket_contacts['payment_method_id']); echo (isset($this->basket_contacts['payment_method_id']))
                ? $p->getName() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?=$this->trans->get('Телефон');?></td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php echo (isset($this->basket_contacts['telephone']))
                ? $this->basket_contacts['telephone'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;">
            E-mail
        </td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php echo (isset($this->basket_contacts['email']))
                ? $this->basket_contacts['email'] : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php echo $this->trans->get('Комментарий');?></td>
        <td style="border-bottom:1px solid #E8641B; padding-bottom:10px; padding-top:5px; width:250px;"><?php echo (isset($this->basket_contacts['comments']))
                ? $this->basket_contacts['comments'] : "&nbsp;"; ?></td>
    </tr>
</table>
-->
</td>
</tr>
</table><br><!---->
<?=$this->render('email/email.footer.tpl.php');?>