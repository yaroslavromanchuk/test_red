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
                width: 900px;
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
if($this->orders){ ?>
<table style="width:100%;">
<tr><td  colspan="2" style="text-align:right;">Додаток 2</td></tr>
<tr><td colspan="2" style="    padding-top: 15px;
    text-align: right;
    padding-right: 50px;
    padding-bottom: 15px;">Форма для бухгалтерії</td></tr>
<tr><td  colspan="2" style="text-align:center;"><b>Повернення коштів від <?=date('d.m.Y')?></b></td></tr>
</table>
<?php
$i=1;
$razom = 0;
$or = [];
foreach ($this->orders as $r) {
    $ord = new Shoporders($r->order_id);
    if($ord->id){
         $or[$ord->fop_id]['fop'] = $ord->fop->name;
        $or[$ord->fop_id]['orders'][] = ['id'=>$ord->id, 'name' =>$ord->getFullName(), 'summa'=>$r->amount+$r->dop_suma, 'dop_file'=>unserialize($r->dop_file) ];   
    }
}
//l($or);
if(count($or)){
    foreach ($or as $v) {
        $i = 1; ?>
    <table style="width: 100%; margin-top: 30px;">
        <tr>
            <th  style="text-align:left; width: 30%"><b>Відправник</b></th> 
            <th  style="text-align:left; width: 70%"><b><?=$v['fop']?></b></th> 
        </tr>
    </table>
    <table  border="1" cellpadding="1" cellspacing="0"  style="width:100%;margin-top: 10px;">
        <tr style="font-weight: bold;">
            <td>№/пп</td>
            <td>№ замовлення</td>
            <td>ПІБ</td>
            <td>Сумма повернення</td>
            <td>Реквізити отримувача</td>
        </tr>
        <?php 
        $s_fop = 0;
         foreach ($v['orders'] as $vv) { ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$vv['id']?></td>
            <td><?=$vv['name']?></td>
            <td><?=$vv['summa']?> грн.</td>
            <td><?php 
           
            if(count($vv['dop_file']) > 0 && is_array($vv['dop_file'])){
               // print_r($vv['dop_file']);
                // echo var_dump($vv['dop_file']);
                foreach ($vv['dop_file'] as $k=> $d){
                echo '<b>'.$k.'</b> '.$d.'<br>';
            } ?>
            </td>
        </tr>
        <?php $i++;
         }
         $s_fop+=$vv['summa'];
         }
         ?>
        <tr style="font-weight: bold;">
            <td colspan="3">Загально по <?=$v['fop']?></td>
            <td colspan="2"><?=$s_fop?> грн.</td>
        </tr>
        <?php
         $razom+=$s_fop;
        ?>
    </table>    
  <?php  }
}
/*
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
}*/
 ?>

<table style="width:100%;font-weight: 600;margin-top: 30px;">
<tr><td  style="text-align:right;"></td><td  style="text-align:right; padding-right: 45%;">Разом до повернення: <?=$razom?> грн.</td></tr>
</table>
<?php } ?>
<div style='page-break-after: always;'></div>
</body>
</html>
