<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
	<title>Счет УкрПочта</title>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <style type="text/css">
	    body{
		    font-size:14px;
	    }
		.tt_border_bottom{
			border-bottom:solid 2px #000;
		}
	    .fnt_size_1{
		    font-size:20px;
	    }
	    .fnt_size_2{
		    font-size:16px;
	    }
	    .fnt_size_3{
		    font-size:22px;
	    }
	    .borderer{
		    border:solid 2px #000;
	    }
	    .border_all{
		    border-top:2px solid #000;
		    border-left:2px solid #000;
	    }
	    .border_right{
		    border-right:2px solid #000;
	    }

    </style>
<body onload="window.print();">

<table border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td colspan="6">
			<div style="border: 1px solid rgb(0, 0, 0); padding: 15px 0pt 15px 15px; margin: 5px 0pt 5px 5px; width: 660px;">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="80">
							Получатель
						</td>
						<td colspan="3" width="498">
							<strong>ООО "РЕД Юкрейн"</strong>
						</td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td width="74">
							Код
						</td>
						<td class="borderer" align="center" width="200" height="30">
							<strong>37026988</strong>
						</td>
						<td width="156">
							&nbsp;
						</td>
						<td align="center" width="144">
							КРЕДИТ счет N
						</td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td width="300" height="30">Банк получателя</td>
						<td width="104" align="center">Код банка</td>
						<td width="18" >&nbsp;</td>
						<td width="150" align="center" class="borderer">26003060404851</td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td width="300" height="30"><strong>ПАТ КБ «Приватбанк»</strong></td>
						<td width="104" align="center" class="borderer">320649</td>
						<td width="18" >&nbsp;</td>
						<td width="150" align="center" ></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="6" align="center" class="fnt_size_3">
			<br/>
			<strong>Счет на оплату заказа № <?php echo $this->order->getId()?> от <?php echo $this->date?> года</strong><br/><br/>
		</td>
	</tr>
	<tr>
		<td valign="top">
			Поставщик:
		</td>
		<td align="left" colspan="5">
			<div style="margin:0px 0 10px 0px">
				<strong>ООО "РЕД Юкрейн"</strong><br/>
				р/с 26003060404851, Банк ПАТ КБ «Приватбанк» , МФО 320649<br/>
				 Украина, 04112, Киев, Е. Телиги, 13/14
			</div>
		</td>
	</tr>
	<tr>
		<td width="100" valign="top">
			Покупатель:
		</td>
		<td>
			<table>
				<tr>
					<td>
						<div style="border-bottom: solid 1px;padding:0 50px;min-width: 100px;font-weight: bold;"><?php echo $this->order->getName()?></div>
					</td>
					<td>
						<strong>(Фамилия, имя)</strong>
					</td>
				</tr>
				<tr>
					<td>
						<div style="border-bottom: solid 1px;padding:0 50px 0 0;min-width: 100px;font-weight: bold;"><?php echo $this->order->getAddress()?></div>
					</td>
					<td>
						<strong>(почт.адрес)</strong>
					</td>
				</tr>
			</table>
			<br/><br/>
		</td>
	</tr>

	<!-- TOVAR TITLES-->
	<tr>
		<td class="border_all fnt_size_1" style="background:#EAE5D8" align="center">
			<strong>№ п/п</strong>
		</td>
		<td class="border_all" style="background:#EAE5D8" align="center">
			<strong>ТОВАР</strong>
		</td>
		<td class="border_all" style="background:#EAE5D8" align="center" width="50">
			<strong>К-во</strong>
		</td>
		<td class="border_all" style="background:#EAE5D8" align="center" width="100">
			<strong>Ед.</strong>
		</td>
		<td class="border_all" style="background:#EAE5D8" align="center" width="100">
			<strong>ЦЕНА</strong>
		</td>
		<td class="border_all border_right" style="background:#EAE5D8" align="center" width="50">
			<strong>СУММА</strong>
		</td>
	</tr>
<?php
	$i = 1;
	$c = $this->getOrder()->getArticles()->count();
	$total_price = 0;
	$total_pricePDV = 0;
    $to_pay = 0;
    $to_pay_minus = '0.00';
	$peresilka = wsActiveRecord::useStatic('DeliveryType')->findFirst(array('id'=>4));
	$peresilka = $peresilka->getPrice();
    if($this->getOrder()->getPaymentMethodId() != 3){
       $peresilka =0;
    }
	foreach ($this->getOrder()->getArticles() as $main_key => $article_rec){
		$art = new Shoparticles($article_rec->getId());
		$size = new Size($article_rec->getSize());
		$color = new Shoparticlescolor($article_rec->getColor());
		$price_withoutPDV = $article_rec->getPrice() - $article_rec->getPrice()/6;
		$price =  $article_rec->getPrice();
	?>
	<tr>
		<td class="border_all <?php echo ($i==$c)?'tt_border_bottom':''?>" align="center">
			<strong><?php echo $i?></strong>
		</td>
		<td class="border_all <?php echo($i==$c)?'tt_border_bottom':''?>">
			<?php echo (($article_rec->getBrand())?$article_rec->getBrand().', ':'').$article_rec->getTitle().', '.$size->getSize().', '.$color->getName() ?>
		</td>
		<td class="border_all <?php echo($i==$c)?'tt_border_bottom':''?>" align="center">
			<strong><?php echo $article_rec->getCount()?></strong>
		</td>
		<td class="border_all <?php echo($i==$c)?'tt_border_bottom':''?>">
			шт.
		</td>

		<td class="border_all <?php echo($i==$c)?'tt_border_bottom':''?>" align="right">
			<?php echo Number::formatFloat($price,2)?>
		</td>
		<td class="border_all border_right" align="right">
			<strong><?php echo Number::formatFloat(($price * $article_rec->getCount()),2)?></strong>
		</td>
	</tr>
	<?php
	$total_pricePDV += $price_withoutPDV * $article_rec->getCount();
	$total_price += $price * $article_rec->getCount();


        $to_pay_perc = $article_rec->getProcent($this->all_orders_amount); // процент скидки
        $price = $article_rec->getPerc($this->all_orders_amount); // цена товара со скидкой
        $to_pay += $price['price'];
        $to_pay_minus += $price['minus'];



	 $i++;
	}

/*	$to_pay_perc = 0;
	$to_pay_minus = 0;
	$to_pay = $total_price;
	if ($this->all_orders_amount <= 700){
		$to_pay = $total_price;
		$to_pay_perc = '0,00%';
	}elseif($this->all_orders_amount > 700 && $this->all_orders_amount <= 5000 ){//5%
		$to_pay_perc = '5,00%';
		$to_pay_minus = (($total_price / 100) * 5);
		$to_pay =  $total_price - $to_pay_minus;

	}elseif($this->all_orders_amount > 5000 && $this->all_orders_amount <= 12000){//10%
		$to_pay_perc = '10,00%';
		$to_pay_minus = (($total_price / 100) * 10);
		$to_pay =  $total_price - $to_pay_minus;
	}elseif($this->all_orders_amount > 12000){//15%
		$to_pay_perc = '15,00%';
		$to_pay_minus = (($total_price / 100) * 15);
		$to_pay =  $total_price - $to_pay_minus;
	}*/
	//echo $total_price;
	$tprice = $total_price-$to_pay_minus+$peresilka;

	//$to_pay_perc = 0;
	//$to_pay_minus = 0;
	?>



	<tr>
		<td colspan="5" align="right">
			<strong>ВСЕГО:</strong>
		</td>
		<td class="border_all border_right" align="right">
			<strong><?php echo Number::formatFloat($total_price)?></strong>
		</td>
	</tr>
	<tr>
		<td colspan="5" align="right">
			<strong>Скидка:</strong>
		</td>
		<td class="border_all border_right" align="right">
			<strong><?php echo Number::formatFloat($to_pay_perc)?></strong>
		</td>
	</tr>
	<tr>
		<td colspan="5" align="right">
			<strong>Сумма скидки:</strong>
		</td>
		<td class="border_all border_right" align="right">
			<strong><?php echo Number::formatFloat($to_pay_minus,2)?></strong>
		</td>
	</tr>
	<tr>
		<td colspan="5" align="right">
			Доставка:
		</td>
		<td class="border_all border_right" align="right">
			<?php echo Number::formatFloat($peresilka,2);?>
		</td>
	</tr>
	<tr>
		<td colspan="5" align="right" class="fnt_size_1">
			<strong>Всего к оплате:</strong>
		</td>
		<td class="border_all border_right" align="right">
			<i><?php echo Number::formatFloat(($tprice),2);?></i>
		</td>
	</tr>
	<tr>
		<td colspan="5" align="right">
			<strong>В т.ч. ПДВ:</strong>
		</td>
		<td class="border_all border_right tt_border_bottom" align="right">
			<strong><?php echo Number::formatFloat((($tprice)/6),2);?></strong>
		</td>
	</tr>
	<tr>
		<td colspan="6">
			<br/>
			Всего наименований <?php echo ($i-1)?>, на сумму <?php echo Number::formatFloat($tprice,2)?> грн.<br/>
			<strong class="fnt_size_1"><?php
				$string = Plural::currency(($tprice), $kop = 0);
				echo mb_strtoupper(mb_substr($string, 0, 1, "UTF-8"), "UTF-8").mb_substr($string, 1, mb_strlen($string), "UTF-8" );
			?></strong><br/>
			<span class="fnt_size_1">У т.ч. ПДВ:
				<?php
					$pdv = ($tprice)/6;
					$string = (Plural::currency($pdv, 0));
					echo mb_strtoupper(mb_substr($string, 0, 1, "UTF-8"), "UTF-8").mb_substr($string, 1, mb_strlen($string), "UTF-8" );


				?>
				</span>

			<br/><br/>
		</td>
	</tr>

</table>
</body>
</html>