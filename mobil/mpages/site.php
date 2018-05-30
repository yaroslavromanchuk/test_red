<?php defined('EXEC') or die; ?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php
    $descriptions = '';
    $keywords = '';
    if ($this->getCurMenu()->getMetatagDescription()) $descriptions = $this->getCurMenu()->getMetatagDescription();
    if ($this->getCategory()) $descriptions = strip_tags(stripslashes($this->getCategory()->getName())) . '. ' . strip_tags(stripslashes($this->getCategory()->getDescription()));
    if ($this->getShopItem()) $descriptions = strip_tags(stripslashes($this->getShopItem()->getModel())) . '  ' . strip_tags(stripslashes($this->getShopItem()->getBrand())) . '. ' . strip_tags(stripslashes($this->getShopItem()->getLongText()));
    if ($this->getCurMenu()->getMetatagKeywords()) $keywords = $this->getCurMenu()->getMetatagKeywords();
    if ($this->getCategory()) $keywords = strip_tags(stripslashes($this->getCategory()->getName()));
    if ($this->getShopItem()) $keywords = strip_tags(stripslashes($this->getShopItem()->getModel())) . ', ' . strip_tags(stripslashes($this->getShopItem()->getBrand()));
    ?>
    <meta name="description" content="<?php echo htmlspecialchars($descriptions); ?>"/>
    <meta name="keywords" content="<?php echo htmlspecialchars($keywords); ?>"/>
    <title>
        <?php
        $title = $this->getCurMenu()->getTitle();
        echo $title ? $title . ' - ' . Config::findByCode('website_name')->getValue() : Config::findByCode('website_name')->getValue();
        ?>
    </title>
	<script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript">
        jQuery.browser = {};
        (function () {
            jQuery.browser.msie = false;
            jQuery.browser.version = 0;
            if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                jQuery.browser.msie = true;
                jQuery.browser.version = RegExp.$1;
            }
        })();
    </script>

    <?php if (LOCAL) { ?>
        <script type="text/javascript"
                src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?php echo Config::findByCode('google_map_api')->getValue(); ?>"
                type="text/javascript"></script>
    <?php } ?>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-29951245-1']);
        _gaq.push(['_setDomainName', '.red.ua']);
        _gaq.push(['_trackPageview']);

        (function () {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();
    </script>
	<script type="text/javascript">
		$('.payment_a').click(
			function(){
				$('.pi').hide();
				$('.b_'+this.id).show();
				return false;
			}
		)
	</script>
</head>
<body>
<div class="body">
    <div class="contentpage-box">
        <div class="navik">
            <a href="/">Главная</a>
            <span class="sep">:</span>
            <?php if ($this->getCategory())
                echo $this->getCategory()->getRoute();
            else
                echo $this->getCurMenu()->getName();
            ?>
        </div>
        <?php
        if (($this->getCurMenu()->getPath() != '/product/')&&($this->getCurMenu()->getPath() != '/brands/')&&($this->get->controller!='Payment')) {
            ?>

            <div class="column-1">
                <?php
                if ($this->get->controller != 'Finder'
                    && ($this->get->controller != 'Shop' && $this->get->action != 'category')
                ) {
                    ?>
                    <?php echo $this->render('parts/leftcat.tpl.php'); ?>

					<?php if ($this->getCurMenu()->getImage()){ ?><img src="<?php echo $this->getCurMenu()->getImage();?>" class="topleftimage" width="240" /> <?php } ?>
					<?php if ($this->getCurMenu()->getImage2()){ ?><img src="<?php echo $this->getCurMenu()->getImage2();?>" class="topleftimage" width="240" /> <?php } ?>
					<?php if ($this->getCurMenu()->getImage3()){ ?><img src="<?php echo $this->getCurMenu()->getImage3();?>" class="topleftimage" width="240" /> <?php } ?>
					<?php if ($this->getCurMenu()->getImage4()){ ?><img src="<?php echo $this->getCurMenu()->getImage4();?>" class="topleftimage" width="240" /> <?php } ?>
					<?php if ($this->getCurMenu()->getImage5()){ ?><img src="<?php echo $this->getCurMenu()->getImage5();?>" class="topleftimage" width="240" /> <?php } ?>
<!--
                    <?php if (isset($_SESSION['history'])) {
                        if (count($_SESSION['history']) > 0) {
                            ?>
                            <div class="slide_show_cap">
                                <h1>История просмотров</h1>
                            </div>
                            <div class="slide_show_nav">
                                <?php
                                $ids = '';
                                foreach ($_SESSION['history'] as $k => $v) {
                                    $ids .= $k . ',';
                                    $i++;
                                    if($i > 14) {break;}
                                }
                                $history_products = wsActiverecord::useStatic('Shoparticles')->findAll(array('id IN (' . rtrim($ids, ',') . ')'));
                                if ($history_products->count() > 1) {
                                    ?>
                                    <a id="prev_1" href="#" title="Vorige">Назад</a>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a id="next_1" href="#" title="Volgende">Вперед</a>
                                <?php } ?>
                            </div>
                            <div class="slideshow">
                            <?php
                            foreach ($history_products as $hp) {
                                $label = false;
                                if (wsActiveRecord::useStatic('Shoparticleslabel')->findFirst(array('id' => $hp->getLabelId()))) {
                                    $label = wsActiveRecord::useStatic('Shoparticleslabel')->findFirst(array('id' => $hp->getLabelId()))->getImage();

                                }
                                echo '<div class="slideshowitem">
												  <div class="article-item">';
                                if ($label) {
                                    echo '<div class="article_label_container">
																	<div class="article_label">
																		   <img src="' . $label . '" alt="" />
																	</div>
														</div>';
                                }
                                echo '
														<a href="' . $hp->getPath() . '" class="img"><img src="' . $hp->getImagePath('listing') . '" alt="' . htmlspecialchars($hp->getTitle()) . '"/></a>
														<p class="brand">' . $hp->getBrand() . '&nbsp;</p>
														<p class="name">' . $hp->getModel() . '</p>
														<p class="price">Цена ' . $hp->showPrice($hp->getPriceSkidka()), 'грн</p>';
                                if ((int)$hp->getOldPrice()) {
                                    echo '<p class="price-old" style="text-decoration: line-through;">' . $hp->showPrice($hp->getOldPrice()) . 'грн</p>';
                                }
                                echo '</div>
												</div>
												';
                            }
                            //d($history_products);
                        } ?>
                        </div>
                    <?php } ?>
-->
                <?php } ?>
            </div>
        <?php }    ?>
        <div class="column-2 <?php if ($this->getCurMenu()->getPath() == '/product/') { echo 'overlord'; } elseif ($this->getCurMenu()->getPath() == '/brands/') echo 'brands'; ?>">
            <div class="content-box">
                <?php echo $this->getContent();?>
            </div>
        </div>
<!--
		<?php
		//if($this->getCurMenu()->getPath()=='/basket/'){
		if (true) {
?>
        <div class="colunm-3">
                <div class="finish_add">
                    <?php
                    $where = "  FROM ws_articles_sizes
                        JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
                        WHERE ws_articles_sizes.count > 0
                        AND ws_articles.active = 'y'
                        AND ( DATE_FORMAT(ws_articles.ctime,\"%Y-%m-%d\") < DATE_ADD(NOW(), INTERVAL -5 DAY) or OR ws_articles.get_now = 1)
                        AND ws_articles.price <= 40
                       ";

                    $articles_query = 'SELECT distinct(ws_articles.id), ws_articles.*,DATE_FORMAT(ws_articles.data_new,"%Y-%m-%d") as orderctime ' . $where . ' ORDER BY RAND() LIMIT 3';

                    $finish_articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($articles_query);
                    ?>

                    <h2>Товар последней минуты</h2>
<script type="text/javascript">
    function getQuikArticle(id){
           $('#quik_frame').html('');
           $.post('/product/id/'+id+'/metod/frame/',function(data){
                $('#quik_frame').html(data);

                               $('a.cloud-zoom').lightBox({fixedNavigation:true});
               $('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();


           });
       }
    $(document).ready(function () {
            $('#quik').css('left', ($(document).width() - $('#quik').width()) / 2);
            $("a[rel]").overlay({mask: {
                color: '#ebecff',
                loadSpeed: 200,
                opacity: 0.7
            }});

        });
</script>
                    <div class="simple_overlay" id="quik">
                        <a class="close"></a>

                        <div id='quik_frame'>

                        </div>
                    </div>
                    <?php foreach ($finish_articles as $hp) { ?>
                        <div class="article-item">

                            <a href="#"  rel="#quik"  onclick="getQuikArticle(<?php echo $hp->getId();?>); return false;" class="img"><img
                                    src="<?php echo $hp->getImagePath('listing') ?>"
                                    alt="<?php echo htmlspecialchars($hp->getTitle()) ?>"/></a>

                            <p class="brand"><?php echo $hp->getBrand() ?>&nbsp;</p>

                            <p class="name"><?php echo $hp->getModel() ?></p>

                            <p class="price">Цена <?php echo $hp->showPrice($hp->getPriceSkidka())?> грн</p>
                            <?php if ($hp->getOldPrice()) { ?>
                                <p class="price-old"
                                   style="text-decoration: line-through;"><?php echo $hp->showPrice($hp->getOldPrice()) ?>
                                    грн</p>;
                            <?php } ?>
                        </div>

                    <?php } ?>

                </div>

        </div>
        <?php } ?>
        <div class="clear"></div>
    </div>
	-->
</div>
</body>
</html>