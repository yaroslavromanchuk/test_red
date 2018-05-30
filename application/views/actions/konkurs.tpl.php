<h1><?php echo $this->getCurMenu()->getName(); ?></h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>
<br/>
<script>
    $(document).ready(function () {
        $('a.konkurs_images').lightBox({fixedNavigation: true});
    })
</script>
<div class="articles-list">
    <?php
    $cnt = 0;
    foreach ($this->fotos as $foto) {
        if (!($cnt % 3)) echo '<div class="articles-row" style="padding: 3px 0 0; height: auto;">';
        ?>
        <div class="article-item" style="height: auto; padding: 10px 8px;  height: 222px; overflow: hidden; width: 200px; text-align: center;">
            <a class="konkurs_images" title="<?= $foto->getName() ?>" href="<?= $foto->getImagePath(); ?>">
                <img class="clicable" style="width: 150px;" src="<?php echo $foto->getImagePath('listing'); ?>"
                     alt="<?php echo htmlspecialchars($foto->getNextName()); ?>"/>
            </a>

            <p class="brand" style="margin-top: 0px;"><?= $foto->getNextName() ?></p>
        </div>
        <?php

        $cnt++;
        if (!($cnt % 3)) echo '</div>';
    }
    if ($cnt % 3)
        echo '</div>';
    ?>
</div>