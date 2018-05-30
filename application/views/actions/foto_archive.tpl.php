<link type="text/css" href="/css/jquery_tools.css" rel="stylesheet"/>
<script type="text/javascript" src="/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="/js/jquery.tools.min.js" type="text/javascript"></script>
<h1><?php echo $this->getCurMenu()->getName();?></h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>
<br/>
<?php if ($this->fotos->count() == 0) echo '<p>Нету фото</p>'; ?>
<div class="articles-list">
    <?php $cnt = 0;
    foreach ($this->fotos as $foto)
    {
        if (!($cnt % 3)) echo '<div class="articles-row" style="padding: 3px 0 0;">';
        $all = $foto->score->count();
        $s5 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 5, 'image_id' => $foto->getId()));
        $s4 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 4, 'image_id' => $foto->getId()));
        $s3 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 3, 'image_id' => $foto->getId()));
        $s2 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 2, 'image_id' => $foto->getId()));
        $s1 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 1, 'image_id' => $foto->getId()));
        if ($all != 0) {
            $sred = round((($s5 * 5 + $s4 * 4 + $s3 * 3 + $s2 * 2 + $s1) / $all), 5);
        }
        else $sred = 0;
        ?>
        <div class="article-item" style="height: 250px;">
            <img class="clicable" style="width: 150px;" src="<?php echo $foto->getImagePath('listing');?>" rel="#mies<?=$foto->getId();?>"
                 alt="<?php echo htmlspecialchars($foto->getName());?>"/>

            <p class="brand"><?=$foto->getName()?></p>
            <p style="font-size: 11px;  height: 18px; overflow: hidden;"><?=$foto->getItem()?> "<?=$foto->getBrend()?>"</p>
            <p class="name" style="height: 16px; overflow: hidden;"><?=$foto->getPrice()?></p>


            <p style="font-size: 9px;">Голосов: <?=$all?> Оценка: <?=round($sred,2)?></p>


        </div>
        <?php
                                    $cnt++;
        if (!($cnt % 3)) echo '</div>';
    }
    if ($cnt % 3)
        echo '</div>';
    ?>
</div>
<?php foreach ($this->fotos as $foto) { ?>
<div class="simple_overlay" id="mies<?=$foto->getId();?>" style="min-height: 300px; padding:10px 80px">
    <img src="<?=$foto->getImagePath();?>" alt=""/>
</div>
<?php } ?>

