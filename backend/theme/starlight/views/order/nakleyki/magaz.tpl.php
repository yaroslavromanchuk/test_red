<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?=$this->files;?>views/order/nakleyki/nakleyka.css?v=1.1">
    </head>
<body onload="window.print()">
<?php
if($this->orders){?>

    <?php foreach ($this->orders as $r) { ?>
    <div  class="d" >
        <span class="bukva">
   
            <?php 
	switch ($r->getDeliveryTypeId()) {
    case 3:
        echo "П";
        break;
    case 5:
        echo "С";
        break;
    case 12:
        echo "М";
        break;
        }
        ?>
          
            </span>
        <span class="nomer">  
            <?=$r->getId()?>
        </span><br>
        <span class="ves" >
           
                            <?php if ($r->getDateVProcese() != '0000-00-00') {
                            echo date('d.m', mktime(0, 0, 0, date("m", strtotime($r->getDateVProcese())), date("d", strtotime($r->getDateVProcese())) + 4, date("Y", strtotime($r->getDateVProcese()))));
                        } else {
			echo date('d.m', mktime(0, 0, 0, date("m"), date("d", time()) + 4, date("Y")));
                        
                        } ?>
                       
        </span>
    </div>
<div style='page-break-after: always;'></div>
<?php } } ?>
</body>
</html>