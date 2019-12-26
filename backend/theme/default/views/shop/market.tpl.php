<?php $iimg = 0; ?>
<h1>Товары в заказах</h1>
<?php
 $cat = '';
$i = 0;
$count = 0;
$count_ucen =0;
foreach ($this->catarticle as $kay => $val) {
    $i++;

    if ($cat != $kay) {
        $cat = $kay;
        if ($i != 1) echo ' </table>';
        ?>
    <p><?=$cat?></p>
           <table id="products1" cellpadding="4" cellspacing="0" style="width:900px;">
                   <tr>
                       <th>Просмотр</th>
                       <th>Фото</th>
                       <th>Товар</th>
                       <th>Цена</th>
                       <th>Размер</th>
                       <th>Цвет</th>
                       <th>Заказано</th>
                       <th>Заказы</th>
                   </tr>
        <?php

    }
    ?>
    <?php
    $row = 'row1';

    ini_set('memory_limit', '1000M');
    set_time_limit(1800);
    $articles = $this->articles;
    foreach ($val as $article)
    {
        if ($article->allcount > 0) {

            $row = ($row == 'row2') ? 'row1' : 'row2';

            ?>
            <tr class="<?php echo $row;?>" <?php if((int)$article->getOldPrice()){ $count_ucen++;?> style="background: #fb6d6d" <?php } ?>>
                <td width="50" class="kolomicon" valign="top">
                    <a href="<?=$article->getPath();?>" target="_blank"><img
                            src="<?=SITE_URL?>/img/icons/view-small.png" alt="Просмотр" width="24"
                            height="24"/></a></td>

                <td>
                    <img class="img_pre" rel="#miesim<?=$iimg;?>"
                         src="<?=$article->getImagePath('small_basket');?>"
                         alt="<?=htmlspecialchars($article->getTitle());?>"/>

                    <div class="simple_overlay" id="miesim<?=$iimg;?>" style="position: fixed;top: 20%; left:35%;">
                        <img src="<?=$article->getImagePath('detail'); ?>" alt=""/>
                    </div>
                    <?php $iimg++; ?>

                </td>
                <td> <?=$article->getTitle();?></td>

                <td>
                    <?=$article->getPrice();?>
                </td>
                <td>
                    <?php $size = new Size($article->size); echo $size->getSize();?>
                </td>

                <td>
                    <?php $color = new Shoparticlescolor($article->artcolor); echo $color->getName();?>
                </td>
                <td>
                    <?php echo $article->allcount; $count = $count + $article->allcount?>
                </td>
                <td>
                    <?php
                    $art_order =array();
                    foreach(wsActiveRecord::useStatic('Shoporderarticles')->findAll(array('order_id in('.$this->ids.')','article_id'=>$article->getId(),'size'=>$article->size,'color'=>$article->artcolor)) as $order_a){
                        @$art_order[$order_a->getArticleId()]['order'] .= $order_a->getOrderId().',';
                    }
                    $mms = explode(',',@$art_order[$article->getId()]['order']);
                    foreach($mms as $m){ if($m){
                        $order = new Shoporders($m);
                        ?>
                        <a target="_blank" href="/admin/shop-orders/edit/id/<?php echo $order->getId()?>">№<?php echo $order->getId()?> <?php echo $order->getDeliveryTypeId() ? $order->getDeliveryType()->getName():''?></a><br />
                  <?php } }
                    ?>
                </td>
            </tr>
            <?php
        }
    }
}
?>
    </table>
        <p><strong>Всего заказано товаров:</strong> <?=$count;?></p>
        <p><strong>Всего уцененных товаров:</strong> <?=$count_ucen;?></p>
<p><strong>Кометарии в заказах:</strong></p>
<?php foreach ($this->orders as $order) {
    if (strlen(trim($order->getComments())) > 2) {
        ?>
    <p>Заказ №<?=$order->getId()?>: <i><?=$order->getComments()?></i></p>
    <?php }
} ?>
<script type="text/javascript">
    $(document).ready(function(){
	 $('img.img_pre').hover(function () {
		$(this).parent().find('div.simple_overlay').show();
        }, function () {
		$(this).parent().find('div.simple_overlay').hide();
        });
    });
</script>