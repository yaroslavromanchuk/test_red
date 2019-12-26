<img src="<?=SITE_URL.$this->getCurMenu()->getImage();?>" alt=""  class="page-img" />
<form method="POST" action="">
<div class="panel panel-primary" style="background-color: #ffffff78;">
 <div class="panel-heading"><h3 class="panel-title"><?=$this->getCurMenu()->getTitle()?></h3></div>
<div class="panel-body">
<?=$this->getCurMenu()->getPageBody()?>
<div class="row">
<div class="col-sm-12 col-md-12 col-lg-12">
<table  cellpadding="5" cellspacing="0" class="table">

        <tr>
            <td class="kolom1">Заказ №</td>
            <td><a href="/admin/shop-orders/edit/id/<?=$this->order->getId()?>"><?=$this->order->getId()?></a></td>
        </tr>

        <tr>
            <td class="kolom1">Скидка ко всему заказу</td>
            <td><input name="order_skidka" type="text" class="formfields" id="order_skidka"
                       value="<?=(int)$this->order->getEventSkidka()?>"/>%
            </td>
        </tr>
		<tr></tr>
        <?php foreach ($this->order->articles as $articles) {
        $color = new Shoparticlescolor($articles->getColor());
        $size = new Size($articles->getSize());
        ?>
        <tr>
            <td><img src="<?php echo $articles->getImagePath('small_basket'); ?>" alt="img"></td>
            <td>
      Скидка: <input name="skidka_<?=$articles->getId()?>" type="text" class="formfields" id="skidka_<?=$articles->geId()?>" value="<?=(int)$articles->getEventSkidka()?>"/>%<br>
	  <p><?php echo $articles->getTitle(); ?> :
                    <?php echo $size->getSize()?> -
                    <?php echo $color->getName();?> :
                    <?php echo $articles->getCount(); ?> шт. :
                    <?php $tmp = $articles->getPrice() * (1 - ($articles->getEventSkidka() / 100)) * $articles->getCount(); echo Shoparticles::showPrice($tmp); ?>
                    грн.</p>
            </td>
        </tr>
        <?php } ?>
    </table>

</div>
</div>
</div>
<div class="panel-footer">
<input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/>
</div>
</div>   
</form>

