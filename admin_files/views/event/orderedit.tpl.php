<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>



<form method="POST" action="">

    <table id="editpage" cellpadding="5" cellspacing="0">

        <tr>
            <td class="kolom1">Заказ №</td>
            <td><a href="/admin/shop-orders/edit/id/<?php echo $this->order->getId();?>"><?php echo $this->order->getId();?></a></td>
        </tr>

        <tr>
            <td class="kolom1">Акционная скидка</td>
            <td><input name="order_skidka" type="text" class="formfields" id="order_skidka"
                       value="<?php echo (int)$this->order->getEventSkidka();?>"/>%
            </td>
        </tr>
        <?php foreach ($this->order->articles as $articles) {
        $color = new Shoparticlescolor($articles->getColor());
        $size = new Size($articles->getSize());
        ?>
        <tr>
            <td><img src="<?php echo $articles->getImagePath('small_basket'); ?>" alt="img"></td>
            <td>
                <p><?php echo $articles->getTitle(); ?> :
                    <?php echo $size->getSize()?> -
                    <?php echo $color->getName();?> :
                    <?php echo $articles->getCount(); ?> шт. :
                    <?php $tmp = $articles->getPrice() * (1 - ($articles->getEventSkidka() / 100)) * $articles->getCount(); echo Shoparticles::showPrice($tmp); ?>
                    грн.</p>
                Скидка: <input name="skidka_<?php echo $articles->getId()?>" type="text" class="formfields"
                               id="skidka_<?php echo $articles->geId()?>"
                               value="<?php echo (int)$articles->getEventSkidka();?>"/>%
            </td>
        </tr>
        <?php } ?>

        <tr>
            <td class="kolom1">&nbsp;</td>
            <td><input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/></td>
        </tr>
    </table>
</form>

