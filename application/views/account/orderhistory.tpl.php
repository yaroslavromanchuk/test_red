<h1><?=$this->trans->get('История заказов')?></h1>
<link type="text/css" href="/css/findex.css?v=1" rel="stylesheet"/>
<?php
$text = explode(',', $this->trans->get('цвет,размер,количество,цена,всего,продолжить покупки,оформить заказ'));
	$status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
	$status[100] = $this->trans->get('new');
	$order_sa = $this->trans->get('Заказ за');
	$st = $this->trans->get('Статус заказа');
	foreach ($this->orders as $order) {
?>
<div class="row mx-auto">
<h4 class="col-xl-12"><?php echo $order_sa.' '.date('d-m-Y', strtotime($order->getDateCreate())); ?>, <b>№ <?php echo $order->getId(); ?></b></h4>
<h5 class="col-xl-12 text-center" >
	<span><?=$st?>:</span>
<b><?php if(in_array($order->status, array(15, 16, 9))){ echo $status[9]; }else{ echo $status[$order->status]; } ?></b>
</h5>
<?php
	if (in_array($order->status, array(100, 1, 9, 11, 10)) and false) { ?>
<a href="/account/orderhistory/?deleteorder=<?php echo $order->getId(); ?>">Отменить заказ</a><?php }
	$t_price = 0.00;
	$t_count = 0;
	$total_price = 0.00;
	$to_pay_perc = 0;
?>
<table class="table cart-table  mb-3">
   <thead>
		<tr><th>Продукт</th><th></th><th><?=$text[3];?></th><th><?=$text[2];?></th><th><?=$text[4];?></th></tr>
	</thead>
<?php
$price_real = 0.00;
	$t_real_price = 0.00;
		$sum_skudka = 0.00;
	$total_price = 0;
	$to_pay = 0;
	$to_pay_minus = 0.00;
	foreach ($order->getArticles() as $article) {
	$price_real = (int)$article->getOldPrice() ? $article->getOldPrice() : $article->getPrice();
						$t_real_price += $price_real * $article->getCount();

		$price = $article->getPerc($this->all_orders_amount); // цена товара с кидкой
			
			
			if($article->getCount() > 0){
			$sum_skudka += $price['minus'];
					$skid_show = round((1 - (($price['price']/$article->getCount())/ $price_real)) * 100);
					}
?>
 <tbody>
		<tr>
			<td>
				<a href="<?php echo $article->getImagePath(); ?>" class="l_box">
					<img style="max-width:150px;" alt="<?php echo htmlspecialchars($article->getTitle()); ?>" src="<?php echo $article->getImagePath('listing'); ?>"/>
					<span class="gls"></span>
				</a><br>
			<a href="<?=$article->getArticleDb()->getPath()?>"><?=$this->trans->get('подробнее')?></a>	
            </td>
           <td class="text-left">
			<b><?=$article->getTitle()?></b></br>
			<?=$text[0];?>:<?=wsActiveRecord::useStatic('Shoparticlescolor')->findById($article['color'])->getName();?> | <?=$text[1];?>:<?=wsActiveRecord::useStatic('Size')->findById($article['size'])->getSize();?>
			</td>
			<td>
<?php 
$rel_pr = $article->getCount() > 0 ? $price['price'] : 0.00;
echo '<span style="text-decoration: line-through;color: #666;font-weight: normal;font-size: 11px;">'.$price_real.'</span><span style="font-size: 11px;color:red;"> - '.$skid_show.' %</span><br>'.Shoparticles::showPrice($rel_pr);
?> грн
            </td>
			<td><?=$article['count'];?></td>
            <td><?=Shoparticles::showPrice($price['price'])?> грн
            </td>
        </tr>
<?php
		
	}	
	if($order->getBonus() > 0){ $bonus = true; }else{ $bonus = false; }
	$order_oplata = $order->calculateOrderPrice2(true, true, true, $bonus);
?>
    <tr>
        <td colspan="4" class="text-left">
		<?=$this->trans->get('Сумма заказа')?>:
		</td>
		<td>
		<?=Shoparticles::showPrice($t_real_price)?> грн.
        </td>
		</tr>
		<tr>
		<td colspan="4" class="text-left">
		<?=$this->trans->get('Способ доставки')?>: 
		</td>
		<td>
		<?=$order->getDeliveryType()->getName();?>
		</td>
		</tr>
		<tr>
		<td colspan="4" class="text-left">
		<?=$this->trans->get('Доставка')?>: 
		</td>
		<td>
		<?=$order->getDeliveryCost();?> грн.
		</td>
		</tr>
		<tr>
		<td colspan="4" class="text-left">
		<?=$this->trans->get('Способ оплаты')?>: 
		</td>
		<td>
		<?=$order->getPaymentMethod()->getName()?>
		</td>
		</tr>
		<tr>
			<td colspan="4" class="text-left" >
				<span style="color: #fe0000"><?=$this->trans->get('Скидка')?>: </span>
			</td>
			<td>
				<?=$order->getSkidka();?> %
			</td>
		</tr>
		<tr>
			<td colspan="4" class="text-left" >
				<span style="color: #fe0000"><?=$this->trans->get('Сумма скидки')?>: </span>
			</td>
			<td>
				<?=Shoparticles::showPrice($sum_skudka)?> грн.
			</td>
		</tr>
		<?php if($order->getKuponPrice() > 0){ ?>
		<tr>
			<td colspan="4" class="text-left" >
				<span style="color: #fe0000"><?=$this->trans->get('Код на скидку')?>:</span>
			</td>
			<td>
				<?=$order->getKupon()?>
			</td>
		</tr>
		<tr>
			<td colspan="4" class="text-left" >
				<span style="color: #fe0000"><?=$this->trans->get('Скидка по коду')?>:</span>
			</td>
			<td>
				<?=$order->getKuponPrice()?> грн.
			</td>
		</tr>
		<?php }
		if($order->getDeposit() > 0){?>
		<tr>
			<td colspan="4" class="text-left" >
				<span style="color: #fe0000"><?=$this->trans->get('Использовано депозита')?>:</span>
			</td>
			<td>
				<?=(float)$order->getDeposit()?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="4" class="text-left" >
				<span style="font-size:14px;"><b><?=$this->trans->get('Всего к оплате')?>:</b></span>
			</td> 
			<td><b>
				<?=$order_oplata?> грн.
	  </b>
			</td>
        </tr>
		<?php if($order->payment_method_id == 4 or $order->payment_method_id == 6){ ?>
		<td colspan="4" class="text-left">
		<b><?=$this->trans->get('Статус оплаты')?>: </b>
		</td>
		<td>
		<?=$order->liqpay_status->name?>
		</td>
		<?php } ?>
				<?php if($order->payment_method_id == 7){ ?>
		<tr>
		<td colspan="4" class="text-left">
		<b><?=$this->trans->get('Статус оплаты')?>: </b>
		</td>
		<td>
		<?=$order->liqpay_status->name?>
		<?php if($order->liqpay_status_id == 1){ $data = $order->LiqPay(); ?>
<form method="POST" action="<?=$data['action']?>" id="liqpay_checkout_<?=$order->getId()?>" accept-charset="utf-8">
    <input type="hidden" name="data"  value="<?=$data['data']?>" />
    <input type="hidden" name="signature" value="<?=$data['signature']?>" />
    <div class="buttons" style="margin-top: 10px;">
        <div class="center">
            <input type="submit" value="<?=$this->trans->get('Оплатить').' '.$order_oplata.' грн.';?>" id="button-confirm-<?=$order->getId()?>" class="btn btn-success button" />
        </div>
    </div>
</form>
	<?php } ?>
		</td>
		</tr>
		<?php } ?>
    </tbody>
</table>
</div>
<?php if (in_array($order->status, array(4,6,8))and false) { ?>
<a class="next-step new_button" style="padding: 3px;" href="/account/returnarticles/?order=<?=$order->getId()?>">Возврат товара</a>
<?php }

} //end for 

if ($this->allcount > $this->onpage) {
?>
	<div class="clear"></div>
	<div style="text-align: center;padding:10px;">
	<ul style="font-size: 16px;" class="finder_pages">
		<?php
	if ($this->page > 1) {
?>
		<li class="page-skip"><a href="&page=<?=$this->page-1;?>"><span style="padding:5px;"><< </span></a></li>
<?php
	} ?>
	<?php
	$b = '';
	$st = ceil($this->allcount/10);
	$q = 1;
	$f1 = 0;
	$f2 = 0;
for($i = 1;$i<=$st; $i++) {
if($i == $this->page)  {$b = 'class="selected"';}else{ $b = '';}
if($st > 10){
if($i < $this->page - 4 and $i < 4 ){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if($i < ($this->page - 3) and $f1 == 0){
$f1 = 1;
echo '<li><span style="padding:5px;">...</span></li>';
}elseif($this->page == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page - 1) == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page - 2) == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page - 3) == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page + 1) == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page + 2) == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page + 3) == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if($i > ($this->page + 3) and $f2 == 0 ){
echo '<li class="page-skip"><span style="padding:5px;">...</span></li>';
$f2 = 1;
}else if($i == $st){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if($i == ($st - 1)){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}
else if($i == ($st - 2)){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}
}else{
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}
}
	?>
		<?php
	if ($this->page < ceil($this->allcount / $this->onpage)) {
?>
			<li class="page-skip"><a href="&page=<?=$this->page + 1;?>"><span style="padding:5px;"> >></span></a></li>
<?php
	}
?>
	</ul>
	</div>
    <div class="clear"></div>
<?php
}
//echo $_SERVER['REMOTE_ADDR'];
?>