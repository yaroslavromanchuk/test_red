<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="description" content="<?php echo htmlspecialchars($this->getCurMenu()->getMetatagDescription()); ?>"/>
    <meta name="keywords" content="<?php echo htmlspecialchars($this->getCurMenu()->getMetatagKeywords()); ?>"/>
    <title>
<?php
	echo Config::findByCode('home_title')->getValue();
?>
    </title>
    <link rel="stylesheet" type="text/css" href="/css/bs/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="/css/style.css?v=20150819"/>
    <link rel="stylesheet" type="text/css" href="/css/new.css?v=20131227"/>
    <link rel="stylesheet" type="text/css" href="/css/tools.css?v=20131212"/>
    <link rel="stylesheet" type="text/css" href="/css/carusel.css?v=20131212"/>

    <link rel="stylesheet" type="text/css" href="/css/carousel/style.css">
    <link rel="stylesheet" type="text/css" href="/css/carousel/jcarousel.responsive.css">

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

    <script type="text/javascript" src="/js/carousel/jquery.jcarousel.min.js"></script>
    <script type="text/javascript" src="/js/carousel/jcarousel.responsive.js"></script>

    <script type="text/javascript" src="/js/jcarousel.js"></script>
    <script type="text/javascript" src="/js/jquery.liFixar.js"></script>
    <script type="text/javascript" src="/js/jquery.tools.min.js"></script>
    <script type="text/javascript" src="/js/mbulka.js"></script>
    <script type="text/javascript" src="/js/bbulka.js"></script>
    <script type="text/javascript" src="/js/functions.js?v=20131212"></script>
    <script type="text/javascript" src="/js/tipsy/jquery.tipsy.js"></script>

    <script src="/css/bs/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/js/tipsy/tipsy.css"/>
    <link rel="shortcut icon" href="/favicon.ico"/>
    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-525073-27']);
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
</head>

<body>
<?php
/*
<script type="text/javascript">
	_shcp = [];
	_shcp.push({widget_id : 600799, widget : "Chat", side : "bottom", position : "left" });
	(
		function() {
			var hcc = document.createElement("script");
			hcc.type = "text/javascript";
			hcc.async = true;
			hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://widget.siteheart.com/apps/js/sh.js";
			var s = document.getElementsByTagName("script")[0];
			s.parentNode.insertBefore(hcc, s.nextSibling); }
	)();
</script>
*/
?>
<?php
	echo $this->cached_top_menu_1;
?>

<div class="head_box">
    <div class="body head-block">

		<div class="row">
			<div class="col-md-4 ico_block">
				<a href="/brands/" style="color: #4f4f51;">
					<span class="glyphicon glyphicon-ok top_ico" aria-hidden="true"></span>
					<div class="top_ico_lbl" style="padding-top:22px;">Оригинальные бренды</div>
				</a>
			</div>
			<div class="col-md-4 ico_block">
				<a href="/pays/" style="color: #4f4f51;">
					<span class="glyphicon glyphicon-map-marker top_ico" aria-hidden="true"></span>
					<div class="top_ico_lbl" style="padding-top:15px;">Бесплатная доставка<br/>при заказе от 750 гривен</div>
				</a>
			</div>
			<div class="col-md-4 ico_block" style="color: #4f4f51;">
				<span class="glyphicon glyphicon-piggy-bank top_ico" aria-hidden="true"></span>
				<div class="top_ico_lbl" style="padding-top:22px;">Доступные цены</div>
			</div>
		</div>
<!--
            <div class="phone">
                <img src="/img/VoIP2.png" alt="tel" style="width: 30px; float: left; margin-top: 27px;"/>

                <div class="hidden html">
                    <div class="basket-bulka-box">
                            <strong>(044) 462-50-90</strong><br>
							<span>(044)</span> 462-57-67<br>
							<span>(093)</span> 854-23-53<br>
							<span>(067)</span> 406-90-80<br>
                    </div>
                </div>
            </div>
        </div>
-->
        <?php echo $this->cached_top_menu_2;?>
    </div>
</div>
<div class="body">
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<a href="http://www.red.ua/category/id/15/muzhskaja-odezhda/?utm_source=site_main&utm_medium=banner&utm_campaign=mujskaya_banner">
			<div style="
				height: 396px;
				width: 800px;
				background: url(../img/blocks/125.jpg);
				background-size: 100%;
			">

			</div>
		</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12">

		<?php $brands = wsActiveRecord::useStatic('Brand')->findAll(array('top' => 1, 'logo <> ""', 'logo is not null')); ?>

		<script type="text/javascript">
			$(document).ready(function () {
				$('.jcarousel-clip').scrollable({items: ".scrollable_brand", prev: ".jcarousel-prev", next: ".jcarousel-next"});
			});
		</script>
		<div class="new_brand_slider">
			<div class="jcarousel-skin-tango">
				<div class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
					<div class="jcarousel-clip jcarousel-clip-horizontal" style="overflow: hidden; position: relative;">
						<div class="scrollable_brand jcarousel-list jcarousel-list-horizontal" style="overflow: hidden; position: relative; top: 0px; left: 0px; margin: 0px; padding: 0px; width: 20000em;">
							<div style="float:left">
								<?php $i=0;foreach ($brands as $brand) { ?>
								<?php if ($i%3 == 0 and $i!=0) { ?></div><div style="float:left"><?php } ?>
								<div class="jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" jcarouselindex="1" style="float: left; list-style: none;">
									<a href="/category/brand/<?php echo $brand->getId(); ?>">
										<table style="width: 100%" cellpadding="0" cellspacing="0">
											<tr>
												<td valign="middle">
													<img style="max-height: 65px; width: auto;" src="<?php echo $brand->getLogo(); ?>" alt="<?php echo $brand->getName(); ?>">
												</td>
											</tr>
										</table>
									</a>
								</div>
								<?php ++$i; } ?>
							</div>
						</div>
					</div>
					<div class="jcarousel-prev jcarousel-prev-horizontal" style="display: block;"></div>
					<div class="jcarousel-next jcarousel-next-horizontal" style="display: block;"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
    if ($this->topproduct->count()) {
?>
<div class="row">
    <div class="col-md-12">
            <div class="jcarousel-wrapper">
                <div class="jcarousel" data-jcarousel="true">
                    <ul style="left: 0px; top: 0px;">
<?php
        foreach ($this->topproduct as $block) {
            if ($block->getId()) {
                $k++;
?>
                        <li>
                                <a name="<?php echo $block->getId(); ?>" href="<?php echo $block->getPath() ?>">
                                    <img src="<?php echo $block->getImagePath('small_preview'); ?>">
                                    <div class="text_top_prod">
                                        <p class="name_top_prod" style="text-align: center;">
    <?php
                                            echo $block->getBrand();
    ?>
                                            <span style="color: red;">
    <?php
                                                echo (float)$block->getPrice(), ' грн'
    ?>
                                            </span>
                                        </p>
                                        <div class="sh_clear"></div>
                                    </div>
                                </a>
                        </li>
<?php
            }
        }
?>
                    </ul>
                </div>
<!--
                <a class="jcarousel-control-prev left carousel-control" href="#myCarousel" role="button" data-jcarouselcontrol="true">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="jcarousel-control-next right carousel-control" href="#myCarousel" role="button" data-jcarouselcontrol="true">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
-->

                <a href="#" class="jcarousel-control-prev" data-jcarouselcontrol="true">‹</a>
                <a href="#" class="jcarousel-control-next" data-jcarouselcontrol="true">›</a>

            </div>
    </div>
</div>
<?php
    }
?>


<div class="row">
    <div class="col-md-4" style="width:35%; float: left;">
        <a href="/category/id/59/detjam/">
			<div style="
				width: 320px;
				height: 386px;
				background: url(../img/blocks/2.jpg);
				background-size: 100%;
			">

			</div>
        </a>
    </div>
    <div class="col-md-4" style="width:30%; float: left;">
        <a href="/category/id/107/muzhskaja-odezhda/dzhinsy/">
			<div style="
				width: 270px;
				height: 180px;
				background: url(../img/blocks/3.jpg);
				background-size: 100%;
			">

			</div>
        </a>
        <a href="/category/id/253/aksessuary/ochki-i-opravy/">
			<div style="
				width: 270px;
				height: 180px;
				background: url(../img/blocks/4.jpg);
				background-size: 100%;
				margin-top: 25px;
			">

			</div>
        </a>
    </div>
    <div class="col-md-4" style="width:35%; float: left;">
        <a href="/category/id/85/rasprodazha/">
			<div style="
				width: 320px;
				height: 386px;
				background: url(../img/blocks/5.jpg);
				background-size: 100%;
			">

			</div>
        </a>
    </div>
</div>

<div class="row" style="padding: 0px 30px;">
    <div class="col-md-12" style="
        height: 375px;
        background-image: url(/img/blog_bgb.png);
        margin-top: 25px;
        padding-top: 15px;
    ">
        <div class="row" style="
            background-color: white;
            opacity: 0.6;
            padding: 5px;
            text-align: center;
            font-size: 25px;
            color: black;
            font-weight: bold;
        ">
            <div class="col-md-12">
                Blog
            </div>
        </div>
        <div class="row" style="
            margin-top: 10px;
        ">
            <a class="col-md-4" style="float: left; width: 33%;" href="http://lifestyle.red.ua/2015/08/vero-moda.html">
                <div class="blog-overlay-placeholder" style="background-image: url('/img/blog/7.jpg');">
                    <div class="blog-cover">
                        <div class="blog-overlay">
                            <span id="hover-text" class="blog_text">Распродажа Vero Moda</span>
                        </div>
                    </div>
                </div>
            </a>
            <a class="col-md-4" style="float: left; width: 33%;" href="http://lifestyle.red.ua/2015/08/5-2015.html">
                <div class="blog-overlay-placeholder" style="background-image: url('/img/blog/8.jpg');">
                    <div class="blog-cover">
                        <div class="blog-overlay">
                            <span id="hover-text" class="blog_text">5 модных трендов осени 2015</span>
                        </div>
                    </div>
                </div>
            </a>
            <a class="col-md-4" style="float: left; width: 33%;" href="http://lifestyle.red.ua/2015/08/blog-post_12.html">
                <div class="blog-overlay-placeholder" style="background-image: url('/img/blog/9.jpg');">
                    <div class="blog-cover">
                        <div class="blog-overlay">
                            <span id="hover-text" class="blog_text">Что делать, когда скучно?</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php // echo $this->cached_bottom_menu; ?>
<!--
<div class="seo_box">
    <?php // echo $this->getCurMenu()->getPageBodyUk(); ?>
</div>
-->
<!--
<div class="copyright-box">
    <div class="right">RED &copy; 2010-<?php echo date('Y');?>. All rights reserved.</div>
    <div class="left"><a href="http://www.webunion.com.ua/" class="logo"></a>
        <a href="http://www.webunion.com.ua/">Разработка интернет-магазинов WebUnion</a></div>

</div>
-->
</div>

<?php
	echo $this->cached_bottom_menu;
?>

<!-- Yandex.Metrika counter -->
<div style="display:none;">
    <script type="text/javascript">
        (function (w, c) {
            (w[c] = w[c] || []).push(function () {
                try {
                    w.yaCounter12225895 = new Ya.Metrika({id: 12225895, enableAll: true, webvisor: true});
                }
                catch (e) {
                }
            });
        })(window, "yandex_metrika_callbacks");
    </script>
</div>
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script>
<noscript>
    <div><img src="//mc.yandex.ru/watch/12225895" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->

<!-- Google Code for &#1058;&#1077;&#1075; &#1088;&#1077;&#1084;&#1072;&#1088;&#1082;&#1077;&#1090;&#1080;&#1085;&#1075;&#1072; -->
<!-- Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. For instructions on adding this tag and more information on the above requirements, read the setup guide: google.com/ads/remarketingsetup -->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 1005381332;
    var google_conversion_label = "1vqdCJyg5gMQ1M2z3wM";
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt=""
             src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1005381332/?value=0&amp;label=1vqdCJyg5gMQ1M2z3wM&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>
<p id="back-top">
	<a href="#top"><span></span></a>
</p>
<script>
$(document).ready(function(){

	// hide #back-top first
	$("#back-top").hide();
	
	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

});
</script>
</body>
</html>