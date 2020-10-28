<?php if(Config::findByCode('new_grafik')->getValue()){ ?>
<p style="text-align: center;color: red;font-size: 18px;"><?=Config::findByCode('new_grafik')->getValue()?></p>
<?php } ?>

<div class="row m-0">
    <div class="col-xl-6">
<?=$this->getCurMenu()->getPageBody()?>
        </div>
    <div class="col-xl-6">
        <div class="row m-0">
<?php 

echo $this->render('/pages/for_orbr_svazy.php');
//echo $this->render('/pages/karantin.php');
?>
</div>
    </div>
    </div>



