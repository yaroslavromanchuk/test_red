<style type="text/css">
    body {
        font-size: 13px;
        width: 194px;
        margin: 0;
        padding: 0;
    }

   

    .border_alls {
        border-top: 2px solid #000;
        border-left: 2px solid #000;
        border-bottom: 2px solid #000;
        border-right: 2px solid #000;
    }
	.border_alls div{
	font-size: 13px;
	    text-align: center;
	width:164px;
	height:79px;
	}
	.border_alls div .span{
    display: block;
    padding: 5px;
	}

    .border_right {
        border-right: 2px solid #000;
    }

	.table1 {
		padding: 7px 0px 0px 20px;
		page-break-after: always; 
		margin: 0 auto;
	}
</style>

<table border="0" cellpadding="3" cellspacing="0" class="table1">
    <tr>
        <td class="border_alls" >
		<div>
		<span class="span">ОДЕРЖУВАЧ</span>
        <span><?=$this->order->getName().' ' .$this->order->getMiddleName()?></span>
		 </div>
        </td>
    </tr>
</table>
<table border="0" cellpadding="3" cellspacing="0" class="table1" >
    <tr>
        <td class="border_alls">
         <div>
		<span class="span">АДРЕСА</span>
		<span><?=$this->order->index.', г. '.$this->order->city.', ул. '.$this->order->street.', д. '.$this->order->house.', кв. '.$this->order->flat;?></span>
		</div>
        </td>
    </tr>
</table>
<table border="0" cellpadding="3" cellspacing="0" class="table1" >
    <tr>
        <td class="border_alls">
		<div>
		<span style="padding:2px;" class="span">ВІДПРАВНИК</span>
		<span>ФЛП "ЦЫБУЛЯ И.В.", Р/Р 26005455021620 ПАТ »ОТП БАНК»  МФО 300528 РНОКППОП 2978405548</span>
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

if($this->order->getBonus() > 0){
	$bonus = true;
	}else{
	$bonus = false;
	}
	if($this->order->getDeposit() > 0){
	$deposit = true;
	}else{
	$deposit = false;
	}

						$amount = (float)$this->order->calculateOrderPrice2($deposit, false, true, $bonus);//
						$amount_round = round($amount,2);

						$price = Number::formatFloat($amount_round, 2);
						echo $price;
?>
                        ГРН.</span><span>
<?php
						list($grn, $kop) = explode('.', (string)sprintf("%.2F", $amount_round));
						
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
							$price = Number::formatFloat($amount, 2);
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
		<span class="span">НОМЕР ТЕЛЕФОНА</span><span><?=$this->order->getTelephone();?></span>
		</div>
		</td>
	</tr>
</table>