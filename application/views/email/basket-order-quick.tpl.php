<?php $dname = Config::findByCode('domain_name')->getValue();
$basket = '?utm_source=returnbasket&utm_medium=email&utm_content=Return_Basket&utm_campaign=Return_Basket';
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>RED</title>

</head>
<body style="background-color:#c4c5c7">
<center>
<table border="0" cellpadding="0" cellspacing="0" style="border:10px solid #ffffff; background:#ffffff" width="700">
<tr>
<td>
<table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="background:#fff;border-collapse:collapse;">
			<tr>
				<td align="right" colspan="2" style="padding: 10px 10px 0 0;">
					<a href="http://www.facebook.com/pages/RED-UA/148503625241218?sk=wall"><img src="https://<?php echo $dname?>/img/social_black/social_black_fb.png" alt="facebook" border="0" width="25" height="25" style="text-decoration:none;"></a>
					<a href="http://twitter.com/#!/red_ukraine"><img src="https://<?php echo $dname?>/img/social_black/social_black_tw.png" alt="twitter" border="0" width="25" height="25" style="text-decoration:none;"></a>
					<a href="http://instagram.com/red_ua"><img src="https://<?php echo $dname?>/img/social_black/social_black_inst.png" alt="instagramm" border="0" width="25" height="25" style="text-decoration:none;"></a>
					<a href="http://www.odnoklassniki.ru/group/56643212738594"><img src="https://<?php echo $dname?>/img/social_black/social_black_odnkl.png" alt="odnoklasniki" border="0" width="25" height="25" style="text-decoration:none;"></a>
				</td>
			</tr>
			<tr>
				<td style="padding: 0 0 0 10px;">
					<a href="https://<?php echo $dname.$basket;?>" class="logo"><img src="https://<?php echo $dname?>/img/_red_logo.png" alt="RED" height="50" width="130"/></a>
				</td>
				<td>
					<span style="color: #666;font-size: 16pt;left: 195px;top: 40px;text-align: center;margin: 0;font-family: Verdana, Tahoma, Arial;">Большие бренды — маленькие цены</span>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<table border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse;margin: 0;width: 100%;border-top: 1px solid;border-bottom: 1px solid;height: 48px;">
						<tr><?php 
						$menu = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id'=>0, 'active'=>1));
						foreach ($menu as $menui) {
							echo '
								<td align="center" border="0" style="padding: 7px 0;">
									<span style="color: #2D2D2D;font-family: Arial;font-size: 15px;font-weight: bold;height: 48px;padding: 0 2px;text-decoration: none;">
				<a style="color: #2D2D2D;text-decoration: none;line-height: 15px;" href="'.$menui->getPath().$basket.'">
											'.$menui->getName().'
										</a>
									</span>
								</td>
							';
						} ?>
						</tr>
					</table>
				</td>
			</tr>
		</table>
<table border="0" cellpadding="2" cellspacing="0" width="700" style="font-family: Verdana">
<tr>
<td width="700" style="vertical-align:top">

<p>Дополнительную информацию можно получить по телефону: (044) 224-40-00</p>

<h3 style="color:#E8641B;">Ваша заявка №<?php echo $this->basket_contacts->getQuickNumber();?></h3>
<table cellpadding="4" cellspacing="0" width="700" style="border:1px dashed #E8641B;padding:15px;">
    <tr>
        <td></td>
        <td><strong><?php echo $this->trans->get('Товар');?></strong></td>
        <td><strong><?php echo $this->trans->get('Размер');?></strong></td>
        <td><strong><?php echo $this->trans->get('Цвет');?></strong></td>
        <td><strong><?php echo $this->trans->get('Количество');?></strong></td>
        <td>&nbsp;</td>
        <td><strong><?php echo $this->trans->get('Цена');?></strong></td>
        <td>&nbsp;</td>
        <td><strong><?php echo $this->trans->get('Всего');?></strong></td>
    </tr>
    <?php 
	$t_count = 0; 
	$t_price = 0.00; 
	$total_price = 0.00;
    $to_pay = 0;
    $to_pay_minus = '0.00';
    $now_orders=0;
    $skidka = 0;
    $event_skidka = 0;
      if ($this->ws->getCustomer()->getIsLoggedIn()) {
            $skidka = $this->ws->getCustomer()->getDiscont();
         $event_skidka =  $this->basket_contacts->getEventSkidka(); 
//		  EventCustomer::getEventsDiscont($this->ws->getCustomer()->getId());
            $all_orders = wsActiveRecord::useStatic('Customer')->findByQuery('
    SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
   WHERE ws_orders.customer_id = ' . $this->ws->getCustomer()->getId() . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0);
            $now_orders = $all_orders->getAmount() + $t_price;
        }
    foreach ($this->getArticles() as $article) {
        $t_price += $article['price'] * $article['count'];
    }
    $now_orders += $t_price;
    foreach ($this->getArticles() as $article) {
        $at = new Shoparticles($article['id']);
        if($article['count'] == 0 ) $article['count'] = 'нет на складе';

        ?>
        <tr>
            <td style="border-bottom:1px solid #E8641B; font-size:85%; padding-right:10px; "><img
                    width="70"
                    src="https://<?=$_SERVER['HTTP_HOST'].$at->getImagePath('listing'); ?>"
                    alt="<?=htmlspecialchars($at->getTitle()); ?>"/></td>
            <td style="border-bottom:1px solid #E8641B; font-size:85%; padding-right:10px; <?php if( $article['count'] == 'нет на складе') echo 'text-decoration: line-through;'; ?>"><?php echo $article['title']; ?> </td>
            <td style="border-bottom:1px solid #E8641B; font-size:85%; padding-right:10px; <?php if( $article['count'] == 'нет на складе') echo 'text-decoration: line-through;'; ?>"><?php echo wsActiveRecord::useStatic('Size')->findById($article['size'])->getSize(); ?></td>
            <td style="border-bottom:1px solid #E8641B; font-size:85%; padding-right:10px; <?php if( $article['count'] == 'нет на складе') echo 'text-decoration: line-through;'; ?>"><?php echo wsActiveRecord::useStatic('Shoparticlescolor')->findById($article['color'])->getName(); ?></td>
            <td style="border-bottom:1px solid #E8641B; font-size:85%; padding-right:10px"><?php $t_count += $article['count']; echo $article['count']; ?></td>
            <td style="border-bottom:1px solid #E8641B; font-size:85%; padding-right:10px"></td>
            <td style="border-bottom:1px solid #E8641B; font-size:85%; padding-right:10px; <?php if( $article['count'] == 'нет на складе') echo 'text-decoration: line-through;'; ?>"> <?php echo Shoparticles::showPrice($article['price']); ?>
                грн
            </td>
            <td style="border-bottom:1px solid #E8641B; font-size:85%; padding-right:10px"></td>
            <td style="border-bottom:1px solid #E8641B; font-size:85%; padding-right:10px"><?php  if($article['count']!='нет на складе'){echo Shoparticles::showPrice($article['price'] * $article['count']).' грн.';} else echo $article['count']; ?>
            </td>
        </tr>
        <?php
$price = $at->getPerc($now_orders, $article['count'], $skidka, (int)$event_skidka, $this->basket_contacts['kupon'], $t_price);
        $to_pay += $price['price'];
        $to_pay_minus += $price['minus'];


		} ?>
  <!--  <tr>
        <td></td>
        <td colspan="3"><?php //echo $this->trans->get('Доставка');?></td>
        <td></td>
        <td></td>
        <td></td>
        <td>&nbsp;</td>
        <td></td>
        <td>&nbsp;</td>
        <td><?php //echo Shoparticles::showPrice(@$this->basket_contacts['delivery_cost']);?>
            грн
        </td>
    </tr>-->
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="4" ><?=$this->trans->get('Всего без скидки');?></td>
        <td><?=Shoparticles::showPrice($t_price);?> грн</td>
    </tr>
	<tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="4" ><?=$this->trans->get('Сумма скидки');?></td>
        <td><?=Shoparticles::showPrice($to_pay_minus);?> грн</td>
    </tr>
	<tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="4" ><?=$this->trans->get('Всего к оплате');?></td>
        <td><?=Shoparticles::showPrice($to_pay);?> грн</td>
    </tr>
</table>

</td>
</tr>

<tr>
    <td style="background:#ddd;text-align:center; padding:10px;">
					<a href="https://<?=$dname?>/returns/" style="text-decoration:none;padding:0 16px;text-align: center;"><span style="color:#333;">Возвраты ></span></a>
					<a href="https://<?=$dname?>/store-locator/" style="text-decoration:none;padding:0 16px;text-align: center;"><span style="color:#333;">Магазины сети </span><span style="color:red">RED</span><span style="color:#333;"> ></span></a>
					<a href="https://<?=$dname?>/pays/" style="text-decoration:none;padding:0 16px;text-align: center;"><span style="color:#333;">Доставка и оплата ></span></a>
				</td>
</tr>
</table>
</td>
</tr>
</table>
</center>
</body>
</html>

