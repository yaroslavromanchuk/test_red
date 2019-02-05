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
<table border="0" cellpadding="2" cellspacing="0" width="700" align="center" style="font-family: Verdana">
<tr>
<td width="700" style="vertical-align:top">

<p><?=$this->trans->get('Дополнительную информацию можно получить по телефону');?>: (044) 224-40-00</p>

<h3 style="color:#E8641B;">Ваша заявка №<?=$this->order->getQuickNumber()?></h3>
<table width="700"  class="table cart-table">
<thead>
    <tr>
<th>Продукт</th><th></th><th><?=$text[3]?></th><th><?=$text[2]?></th><th><?=$text[4]?></th>
    </tr>
	</thead>
    <?php 
	
	$t_real_price = 0.00;
	$price_real = 0.00;
	//$sum_skudka = 0.00;
	$skid = '';

     $order = $this->order;
	
    foreach ($order->getArticles() as $article_or) {	
   if($article_or->getCount() == 0 ){ $count = 'нет на складе';}else{$count = $article_or->getCount(); }
	
	$price_real = (int)$article_or->getOldPrice() ? $article_or->getOldPrice() : $article_or->getPrice();
	
        $t_real_price += $price_real * $article_or->getCount();
	
	$price = $article_or->getPerc($order->getAllAmount());
	
        //$sum_skudka += $price['minus'];
		
	if($article_or->getCount() > 0){
	$skid_show = round((1 - (($price['price']/$article_or->getCount()) / $price_real)) * 100);
	}

        ?>
   <tr>
       <td>
	   <a href="https://<?=$_SERVER['HTTP_HOST'].$article_or->article_db->getPath()?>" >
	   <img width="100" src="https://<?=$_SERVER['HTTP_HOST'].$article_or->getImagePath('listing'); ?>" alt="<?=htmlspecialchars($article_or->getTitle());?>"/>
	   </a>
		</td>
		
       <td style="text-align:left;">
	   <b><?=$article_or->getTitle()?></b><br>
           <?=$text[0].':'.$article_or->colors->getName().' | '.$text[1].':'.$article_or->sizes->getSize()?>
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
	   <?=$count?>
	   </td>
	   
       <td><?=Shoparticles::showPrice($price['price'])?> грн.</td>
        </tr>
<?php } ?>
	</table>
<table width="700"  class="table cart-table">
	<tr>
        <td style="text-align:left;" colspan="3"><strong><?=$this->trans->get('Всего к оплате')?>:</strong></td>
        <td style="text-align:right;" colspan="2"><strong><?=Number::formatFloat($order->getAmount(), 2)?> грн.</strong></td>
	</tr>
</table>
</td>
</tr>
</table>
<?=$this->render('email/email.footer.tpl.php');?>

