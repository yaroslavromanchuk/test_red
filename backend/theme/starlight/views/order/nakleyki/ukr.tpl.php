<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?=$this->files;?>views/order/nakleyki/ukrp_st.css?v=1.0">
    </head>
    <body onload="window.print()">
<?php
if($this->orders){?>

    <?php foreach ($this->orders as $r) { ?>
<table border="0" cellpadding="3" cellspacing="0" class="table1">
    <tr>
        <td class="border_alls" >
		<div>
		<span class="span">ОДЕРЖУВАЧ</span>
        <span><?=$r->getName().' ' .$r->getMiddleName()?></span>
		 </div>
        </td>
    </tr>
</table>
<table border="0" cellpadding="3" cellspacing="0" class="table1" >
    <tr>
        <td class="border_alls">
         <div>
		<span class="span">АДРЕСА</span>
		<span><?=$r->index.', г. '.$r->city.', ул. '.$r->street.', д. '.$r->house.', кв. '.$r->flat;?></span>
		</div>
        </td>
    </tr>
</table>
<table border="0" cellpadding="3" cellspacing="0" class="table1" >
    <tr>
        <td class="border_alls">
		<div>
		<span style="padding:2px;" class="span">ВІДПРАВНИК</span>
		<span>ФЛП "ЦЫБУЛЯ И.В.", Р/Р 26005455021620 ПАТ "ОТП БАНК"  МФО 300528 РНОКППОП 2978405548</span>
		</div>
        </td>
    </tr>
</table>
<table border="0" cellpadding="3" cellspacing="0" class="table1" >
    <tr>
        <td class="border_alls">
		<div>
		<span class="span">АДРЕСА ВІДПРАВНИКА</span>
		<span>а/я №144, "Цыбуля И.В.", г.Киев, 04080</span>
		</div>
        </td>
    </tr>
</table>
<table border="0" cellpadding="3" cellspacing="0" class="table1" >
    <tr>
        <td class="border_alls">
          <div><span class="span" style="padding:1px;">ОГОЛОШЕНА ЦІННІСТЬ<br/>
<?php


	$amount = (float)$r->calculateOrderPrice2($deposit, false);//

	$price = Number::formatFloat(round($amount,2), 2);
	echo $price;
?>
                        ГРН.</span><span>
<?php
						list($grn, $kop) = explode('.', (string)sprintf("%.2F", $amount));
						
						$sgrn = trim(num2strm($grn));
						$first = mb_substr($sgrn,0,1, 'UTF-8');
						$last = mb_substr($sgrn,1);
						$first = mb_strtoupper($first, 'UTF-8');
						$sgrn = $first.$last;
						echo $sgrn.' '.morph($grn, 0).' '.num2strm($kop).' '.morph($kop, 1);
?>
</span>
</div>
        </td>
    </tr>
</table>
<table border="0" cellpadding="3" cellspacing="0" class="table1" >
    <tr>
        <td class="border_alls">
       <div><span class="span" style="padding:1px;">ПІСЛЯПЛАТА<br/>
<?php
							//$price = Number::formatFloat($amount, 2);
							echo $price;
?>
                        ГРН.</span><span>
<?php
						list($grn, $kop) = explode('.', (string)sprintf("%.2F", $amount));
						
						$sgrn = trim(num2strm($grn));
						$first = mb_substr($sgrn,0,1, 'UTF-8');
						$last = mb_substr($sgrn,1);
						$first = mb_strtoupper($first, 'UTF-8');
						$sgrn = $first.$last;
						echo $sgrn.' '.morph($grn, 0).' '.num2strm($kop).' '.morph($kop, 1);
?>
</span>
</div>
        </td>
    </tr>
</table>
<table border="0" cellpadding="3" cellspacing="0" class="table1" >
	<tr>
		<td class="border_alls">
		<div>
		<span class="span">НОМЕР ТЕЛЕФОНА</span><span><?=$r->getTelephone();?></span>
		</div>
		</td>
	</tr>
</table>
<div style='page-break-after: always;'></div>
<?php } } ?>
</body>
</html>