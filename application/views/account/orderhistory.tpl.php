<h1><?=$this->trans->get('История заказов')?></h1>
<link type="text/css" href="/css/findex.css?v=1.1" rel="stylesheet"/>
<div class="row m-auto">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 m-auto">
<?php
$text = explode(',', $this->trans->get('цвет,размер,количество,цена,всего,продолжить покупки,оформить заказ'));
	$status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
	$status[100] = $this->trans->get('new');
	$order_sa = $this->trans->get('Заказ за');
	$st = $this->trans->get('Статус заказа');
	foreach ($this->orders as $order) {
?>
<div class="row m-auto">
<?php
if (in_array($order->status, array(100, 1, 9, 11, 10)) and false) { ?>
<a href="/account/orderhistory/?deleteorder=<?php echo $order->getId(); ?>">Отменить заказ</a>
<?php } ?>
<div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 m-auto">
<div id="accordion">
<div class="card">
<div class="card-header p-2 text-uppercase " id="heading<?=$order->id?>" data-toggle="collapse" data-target="#collapse<?=$order->id?>" aria-expanded="true" aria-controls="collapse<?=$order->id?>">
   <!--<?=$order_sa.' '.date('d-m-Y', strtotime($order->getDateCreate()))?>,--> № <?=$order->getId()?>  <?=$st?>: <span class="font-weight-bold"><?=$order->stat->group_name->getName()?></span> (<?=Shoparticles::showPrice($order->amount)?> грн.)
<i class="icon ion-logo-buffer text-right float-right mx-2" style="font-size:14px;"></i>  
</div>
  <div id="collapse<?=$order->id?>" class="collapse" aria-labelledby="heading<?=$order->id?>" data-parent="#accordion">
  <div class="card-body">
  <div class="row">
  <?php
$price_real = 0.00;
	$t_real_price = 0.00;
	$sum_skudka = 0.00;
	$total_price = 0.00;
	$to_pay_minus = 0.00;
	foreach ($order->getArticles() as $article) {
	$price_real = (int)$article->getOldPrice()?$article->getOldPrice():$article->getPrice();
		$t_real_price += $price_real * $article->getCount();
		$price = $article->getPerc($this->all_orders_amount); // цена товара с кидкой
			if($article->getCount() > 0){
			$sum_skudka += $price['minus'];
			$skid_show = round((1 - (($price['price']/$article->getCount())/ $price_real)) * 100);
					}
?>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 col-xl-3 text-center">
	<a href="<?=$article->getImagePath()?>" class="l_box">
					<img style="max-width:150px;" alt="<?=htmlspecialchars($article->getTitle())?>" src="<?=$article->getImagePath('listing')?>"/>
					<span class="gls"></span>
	</a><br>
	<a href="<?=$article->getArticleDb()->getPath()?>"><?=$this->trans->get('подробнее')?></a>	
</div>
<div  class="col-xs-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
	<b><?=$article->getTitle()?></b></br>
	<?=$text[0]?>: <?=$article->article_db->color_name->getName()?> | <?=$text[1]?>:<?=$article->sizes->getSize()?>
</div>
<div  class="col-xs-12 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-left"><div><?=$text[3]?> : <div style="display:  inline-block;">
<?php 
$rel_pr = $article->getCount() > 0 ? $price['price'] : 0.00;
echo '<span style="text-decoration: line-through;color: #666;font-weight: normal;font-size: 11px;">'.$price_real.'</span><span style="font-size: 11px;color:red;"> - '.$skid_show.' %</span><br>'.Shoparticles::showPrice($rel_pr);
?> грн</div></div>
</div>
<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-left"><?=$text[2]?> : <?=$article['count']?></div>
<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-left"><?=$text[4]?> : <?=Shoparticles::showPrice($price['price'])?> грн</div>
<?php
		
	}	
	//$order_oplata = $order->calculateOrderPrice2(true, true, true);
?>
</div>
</div>

<div class="card-footer">
<div class="row">
<div class="col-xl-12">
<div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 text-left ">
		<?=$this->trans->get('Сумма заказа')?>:
		</div>
<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6">
		<?=Shoparticles::showPrice($t_real_price)?> грн.
        </div>
</div>
</div>
<div class="col-xl-12">
<div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 text-left ">
		<?=$this->trans->get('Способ доставки')?>: 
		</div>
<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 ">
		<?=@$order->getDeliveryTypeId()?strip_tags($order->getDeliveryType()->getName()):''?>
        </div>
</div>
</div>
    <?php if($order->getDeliveryTypeId() == 8 or $order->getDeliveryTypeId() == 16){ ?>
    <div class="col-xl-12">
<div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6text-left ">
		<?=$this->trans->get('Отделение')?>: 
		</div>
<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 ">
		<?=$order->getSklad()?>
        </div>
</div>
</div>
    <?php } ?>
<div class="col-xl-12">
<div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6text-left ">
		<?=$this->trans->get('Доставка')?>: 
		</div>
<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 ">
		<?=$order->getDeliveryCost()?> грн.
        </div>
</div>
</div>
<div class="col-xl-12">
<div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 text-left ">
		<?=$this->trans->get('Способ оплаты')?>: 
		</div>
<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 ">
		<?=$order->getPaymentMethodId()?strip_tags($order->getPaymentMethod()->getName()):''?>
</div>
</div>
</div>
<div class="col-xl-12">
<div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 text-left ">
		<span style="color: #fe0000"><?=$this->trans->get('Скидка')?>: </span>	
</div>
<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 ">
		<?=$order->getSkidka()?> %
        </div>
</div>
</div>
<div class="col-xl-12">
<div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 text-left">
		<span style="color: #fe0000"><?=$this->trans->get('Сумма скидки')?>: </span>	
		</div>
<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6">
		<?=Shoparticles::showPrice($sum_skudka)?> грн.
        </div>
</div>
</div>
<?php if($order->getKuponPrice() > 0){ ?>
<div class="col-xl-12">
<div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 text-left ">
		<span style="color: #fe0000"><?=$this->trans->get('Код на скидку')?>:</span>
		</div>
<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 ">
		<?=$order->getKupon()?>
        </div>
</div>
</div>
<div class="col-xl-12">
<div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 text-left ">
		<span style="color: #fe0000"><?=$this->trans->get('Скидка по коду')?>:</span>
		</div>
<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 ">
		<?=$order->getKuponPrice()?> грн.
        </div>
</div>
</div>
<?php } if($order->getDeposit() > 0){ ?>
<div class="col-xl-12">
<div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 text-left ">
		<span style="color: #fe0000"><?=$this->trans->get('Использовано депозита')?>:</span>
		</div>
<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 ">
		<?=(float)$order->getDeposit()?> грн.
        </div>
</div>
</div>
<?php } ?>
       <?php if($order->payment_method_id == 7 and $order->getCustomerId() == 26350){ ?> 
    <div class="col-xl-12">
    <div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 text-left ">
	<?=$this->trans->get('Статус оплаты')?>:
		</div>
    

<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 ">
<?=$order->liqpay_status->name?>
    <br>
    <a class="btn btn-success" href="/liqpay/id/<?=$order->id?>/">Оплатить <?=$order->amount?> грн.</a>
    </div>
    </div>
        </div>
            <?php } ?>
<div class="col-xl-12">
<div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 text-left ">
		<span style="font-size:14px;"><b><?=$this->trans->get('Всего к оплате')?>:</b></span>
		</div>
<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 ">
		<?=Shoparticles::showPrice($order->amount)?> грн.
        </div>
</div>
</div>
<?php if(($order->payment_method_id == 4 or $order->payment_method_id == 6)){ ?>
<div class="col-xl-12">
<div class="row">
<div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 text-left ">
	<b><?=$this->trans->get('Статус оплаты')?>: </b>
		</div>
    

<div class="text-right col-sm-6 col-xs-6 col-md-6 col-lg-6 col-xl-6 ">
<?=$order->liqpay_status->name?>
    
     <?php if($order->liqpay_status != 3  and $order->open_pay == 1 and $order->status == 100){ ?>
<form action="/payment/powtorpay/" method="POST" name="payment">
<div class="form-group" >
    <input  name="order" type="hidden" required  class="form-control" value="<?=$order->id?>">
    <select name="payment_sistem" required class="form-control-sm">
        <option value="4" <?php if($order->getPaymentMethodId() == 4) { echo 'selected'; } ?>>Visa/MasterCard</option>
        <option value="6" <?php if($order->getPaymentMethodId() == 6) { echo 'selected'; } ?>>Приват 24</option>
  </select>
</div>
    <div class="col-xs-12 form-group">
    <button type="submit"  class="btn btn-outline-success btn-sm">Оплатить</button>
</div>                
</form>
                    
            <?php    } ?>
</div>
</div>
</div>
	<?php
       
        
} ?>

	</div>
  </div>
  
  </div>
</div>
</div>
    </div>
</div>
      
    
<?php 

} ?>
        </div>
    </div>
<?php
if ($this->allcount > $this->onpage) {
?>
	<div class="row m-auto">
            <div class="col-xl-12">
	<div style="text-align: center;padding:10px;">
	<ul style="font-size: 16px;" class="finder_pages">
		<?php
	if ($this->page > 1) {
?>
		<li class="page-skip"><a href="?page=<?=$this->page-1;?>"><span style="padding:5px;"><< </span></a></li>
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
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if($i < ($this->page - 3) and $f1 == 0){
$f1 = 1;
echo '<li><span style="padding:5px;">...</span></li>';
}elseif($this->page == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page - 1) == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page - 2) == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page - 3) == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page + 1) == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page + 2) == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page + 3) == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if($i > ($this->page + 3) and $f2 == 0 ){
echo '<li class="page-skip"><span style="padding:5px;">...</span></li>';
$f2 = 1;
}else if($i == $st){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if($i == ($st - 1)){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}
else if($i == ($st - 2)){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}
}else{
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}
}
	?>
		<?php
	if ($this->page < ceil($this->allcount / $this->onpage)) {
?>
			<li class="page-skip"><a href="?page=<?=$this->page + 1;?>"><span style="padding:5px;"> >></span></a></li>
<?php
	}
?>
	</ul>
	</div>
                </div>
            </div>
<?php } ?>