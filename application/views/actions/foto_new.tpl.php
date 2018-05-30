<link type="text/css" href="/css/jquery_tools.css" rel="stylesheet"/>
<script type="text/javascript" src="/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="/js/jquery.tools.min.js" type="text/javascript"></script>
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?34"></script>

<script type="text/javascript">
    $(document).ready(function () {
        VK.init({apiId:2633530, onlyWidgets:true});
        $('.text_box').hover(function () {
            $(this).find('.view_all_text').show();
        }, function () {
            $(this).find('.view_all_text').hide();
        })
    });
</script>
<h1><?php echo $this->getCurMenu()->getName();?></h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>
<br/>
<?php if ($this->fotos->count() == 0) echo '<p>Нет фото</p>'; ?>
<div class="articles-list">
    <?php $cnt = 0;
    foreach ($this->fotos as $foto) {
        if (!($cnt % 2)) echo '<div class="articles-row" style="padding: 3px 0 0; height: auto;">';
        $all = $foto->score->count();
        $s5 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 5, 'image_id' => $foto->getId()));
        $s4 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 4, 'image_id' => $foto->getId()));
        $s3 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 3, 'image_id' => $foto->getId()));
        $s2 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 2, 'image_id' => $foto->getId()));
        $s1 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 1, 'image_id' => $foto->getId()));
        if ($all != 0) {
            $sred = round((($s5 * 5 + $s4 * 4 + $s3 * 3 + $s2 * 2 + $s1) / $all), 5);
        } else $sred = 0;
        ?>
        <div class="article-item"
             style="height: auto; padding: 10px 8px;     text-align: center;  width: 310px; margin-bottom: 10px; height: 390px;">
            <table style="width: 300px; height: 300px;">
                <tr>
                    <td style="vertical-align: middle;">


                        <img class="clicable" style="max-width: 300px; max-height: 300px;" src="<?php echo $foto->getImagePath('listing');?>"
                             rel="#mies<?=$foto->getId();?>"
                             alt="<?php echo htmlspecialchars($foto->getNextName());?>"/>
                    </td>
                </tr>
            </table>

            <p class="brand" style="margin-top: 0px;"><?=$foto->getNextName()?></p>
            <?php if ($foto->getText()) { ?>
            <div style="font-size: 11px;  height: 24px; overflow: hidden;" class="text_box">
                <div class="view_all_text"
                     style="display: none; background: #fff; width: 300px; padding: 5px; border: 1px solid #fff; position: absolute;"><?=$foto->getText()?></div>
                <?/*=$foto->getItem()*/?> "<?/*=$foto->getBrend()*/?><?=$foto->getText()?>"
            </div>

            <?php } ?>


            <?php if (false) { ?>
            <script type="text/javascript" src="http://userapi.com/js/api/openapi.js?34"></script>

            <script type="text/javascript">
                $(document).ready(function () {
                    VK.Widgets.Like("vk_like<?php echo $foto->getId();?>", {type:"button", pageUrl:"http://<?php echo $_SERVER['HTTP_HOST'] . '/' . $foto->getImagePath('listing');?>"});

                });
            </script>

            <div id="vk_like<?php echo $foto->getId();?>"></div>

            <iframe src="http://www.facebook.com/plugins/like.php?href=http://<?php echo $_SERVER['HTTP_HOST'] . '/' . $foto->getImagePath('listing');?>&layout=button_count&show_faces
=true&width=450&action=like&colorscheme=light&height=35" scrolling="no" frameborder="0"
                    style="border:none; overflow:hidden; width:450px; height:35px;"
                    allowTransparency="true"></iframe>

            <?php } ?>
            <p style="font-size: 9px;">Голосов: <?=$all;?> Оценка: <?=round($sred, 2)?></p>

          <!--  <p class="star"><?php /*if ($foto->isScore()) { */?>
                <img class="clicable" src="/img/no_star.png" alt="1" name="<?/*=$foto->getId()*/?>">
                <img class="clicable" src="/img/no_star.png" alt="2" name="<?/*=$foto->getId()*/?>">
                <img class="clicable" src="/img/no_star.png" alt="3" name="<?/*=$foto->getId()*/?>">
                <img class="clicable" src="/img/no_star.png" alt="4" name="<?/*=$foto->getId()*/?>">
                <img class="clicable" src="/img/no_star.png" alt="5" name="<?/*=$foto->getId()*/?>">
                <?php /*} else echo 'Cпасибо за голос';*/?>
            </p>-->


        </div>
        <?php
        $cnt++;
        if (!($cnt % 2)) echo '</div>';
    }
    if ($cnt % 2)
        echo '</div>';
    ?>
</div>
<?php foreach ($this->fotos as $foto) { ?>
<div class="simple_overlay" id="mies<?=$foto->getId();?>" style="min-height: 300px; padding:10px 80px">
    <img src="<?=$foto->getImagePath();?>" alt=""/>
</div>
<?php } ?>
<script type="text/javascript">
    $(function () {
        $('p.star img').bind("mouseenter",
                function () {
                    $(this).parent().find('img:lt(' + $(this).attr('alt') + ')').attr('src', '/img/star.png');

                }).bind("mouseleave", function () {
                    $('p.star img').attr('src', '/img/no_star.png');
                });
        $('p.star img').click(function () {
            var p = $(this).parent();
            var url = '/page/clickbestfoto/';
            $.get(
                    url,
                    "id=" + $(this).attr('name') + '&score=' + $(this).attr('alt'),
                    function (result) {
                        if (result.type == 'error') {
                            return(false);
                        }
                        else {
                            p.html('Cпасибо за голос');
                        }
                    },
                    "json"
            );
        });
        var overlay = null;
        $("img[rel]").click(function () {
            overlay = $(this);
        });
        $("img[rel]").overlay();

    });
</script>
