<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <title>Форма 103</title>
	<meta name="robot" content="no-index,no-follow"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<script src="<?=$this->files?>scripts/jquery.js" charset="utf-8"></script>
        <style>
            body{
                width: 1200px;
    font-size: 12px;
    font-family: Verdana;
    margin: auto;
            }
            table td{
                padding: 5px;
                font-size: 14px;
            }
            
        </style>
</head>

<body>
<?php
if($this->order){ ?>
<table style="width:100%;">
<tr><td  colspan="2" style="text-align:right;">Додаток 1</td></tr>
<tr><td colspan="2" style="    padding-top: 15px;
    text-align: right;
    padding-right: 50px;
    padding-bottom: 15px;">Форма № 103-1</td></tr>
<tr><td  colspan="2" style="text-align:center;"><b>СПИСОК</b></td></tr>
<tr><td colspan="2" style="text-align:center;"><b>групових поштових переказів електронних</b></td></tr>
<tr><td  style="text-align:left;"><b>Відправник</b></td><td  style="text-align:left;">ФОП "Цибуля І.В."</td></tr>
<tr><td  style="text-align:left;"><b>Реквізити відправника</b></td><td  style="text-align:left;">р/р UA163005280000026005455021620, МФО 300528</td></tr>
<tr><td  style="text-align:left;"></td><td  style="text-align:left;">назва банку АТ «ОТП Банк»</td></tr>
</table>
<table border="1" cellpadding="1" cellspacing="0"  style="width:100%;margin-top: 10px;">
<tr style="text-align:center;">
<td>№ з/п</td>
<td>Куди</td>
<td>Кому</td>
<td>Сумма переказу, грн.</td>
<td>Особливі відмітки</td>
<td>Плата за пересилання* грн.</td>
<td>№ переказу</td>
<td>Примітка</td>
</tr>
<tr style="text-align:center;">
<td>1</td>
<td>2</td>
<td>3</td>
<td>4</td>
<td>5</td>
<td>6</td>
<td>7</td>
<td>8</td>
</tr>
<?php
$i=1;
$razom = 0;
$raz_dop = 0;
$or = [];
foreach ($this->order as $r) {
    $ord = new Shoporders($r->order_id);
    if($ord->id){
        if(array_key_exists($ord->customer_id, $or)){
            $or[$ord->customer_id]['ammount'] = $or[$ord->customer_id]['ammount']+$r->amount;
        $or[$ord->customer_id]['dop'] = $or[$ord->customer_id]['dop'] + $r->dop_suma;
        }else{
        $or[$ord->customer_id]['ammount'] = $r->amount;
        $or[$ord->customer_id]['dop'] = $r->dop_suma;
$adr ='';
$adr_m = [];
$ord->index?$adr_m[] = $ord->index:'';
$ord->city?$adr_m[] = '  м. '.$ord->city:'';
$ord->street?$adr_m[] ='  '.$ord->street:'';
if($ord->house) { $adr_m[] = '  буд. '.$ord->house;}
if($ord->flat) { $adr_m[] ='  кв. '.$ord->flat;}

$or[$ord->customer_id]['adr'] = implode(",", $adr_m);//$adr;
$or[$ord->customer_id]['name'] = $ord->getFullName();
$or[$ord->customer_id]['phone'] = $ord->getTelephone();
        }
        
        
    }
   
}
//echo print_r($or);
foreach ($or as $key => $value) {

        $ammount = $value['ammount']+$value['dop'];
        $razom+=$ammount;
        $per = 0;
if($ammount <= 2000){
$per = round($ammount - ($ammount*0.97), 2);
}elseif(2000 < $ammount and $ammount <= 3000){
$per = round($ammount - ($ammount*0.99), 2);
}elseif(3000 < $ammount){
$per = round($ammount - ($ammount*0.992),2);
}
if($per < 15) {$per = 15;}
 $raz_dop +=$per;
  ?>
<tr >
<td style="text-align:center;"><?=$i?></td>
<td><?=$value['adr']?></td>
<td><?=$value['name']?></td>
<td style="text-align:center;"><?=$ammount?></td>
<td style="text-align:center;"><?=$value['phone']?></td>
<td style="text-align:center;"><?=$per?></td>
<td></td>
<td></td>
</tr>

<?php
$i++;
}
 ?>
</table>
<table style="width:100%;font-weight: 600;margin-top: 30px;">
<tr><td  style="text-align:right;"></td><td  style="text-align:right; padding-right: 45%;">Разом: <?=$razom?></td></tr>
<tr><td  style="text-align:right;"></td><td  style="text-align:right;padding-right: 45%;">Плата за пересилання: <?=$raz_dop?></td></tr>
<tr><td  style="text-align:right;"></td><td  style="text-align:right;padding-right: 45%;">Усього: <?=$razom+$raz_dop?></td></tr>
</table>
<?php } ?>
<div style='page-break-after: always;'></div>
</body>
</html>
