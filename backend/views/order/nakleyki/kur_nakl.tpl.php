<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?=$this->files;?>views/order/nakleyki/nakleyka.css?v=1.0">
    </head>
<body onload="window.print()">
<?php
if($this->orders){?>

    <?php foreach ($this->orders as $r) { ?>
    <div  class="d" style="text-align: center; height: 90px; margin-left: 15px;" >
        <span class="nomer" style="padding-top:  5px;padding-bottom: 10px;"><?=$r->getId()?></span><br>
    <span class="ves" style=""><?=$r->getName()?></span> 
    </div>
<div style='page-break-after: always;'></div>
     <?php } ?>
<?php } ?>
</body>
</html>
