<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?=$this->files;?>views/order/nakleyki/nakleyka.css?v=1.1">
    </head>
<body onload="window.print()">
<?php
if($this->orders){?>

    <?php foreach ($this->orders as $r) { ?>
    <div  class="d" style="text-align: center; border: 0px; height: 90px; ;" >
        <span class="nomer" style="padding-top:  0px; font-size: 20px"><?=$r->getId()?></span><br>
         <span class="ves" style="font-size: 9px; padding-left: 0px"><?php if($r->getDeliveryTypeId() != 4){ echo '<b>'.$r->city.'</b> '.$r->sklad;}else{ echo $r->address;}?></span><br>
        <span class="ves" style="font-size: 9px; padding-left: 0px"><b><?=$r->middle_name.' '.$r->name?></b></span><br>
         <span class="ves" style="font-size: 9px; padding-left: 0px">Тел.:<?=$r->telephone?></span><br>
    <?php if($r->getDeliveryTypeId() == 4){ ?> <span class="ves" style="font-size: 12px;">Вес: _______</span> <?php }?>
    </div>
<div style='page-break-after: always;'></div>
     <?php } ?>
<?php } ?>
</body>
</html>
