<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="description" content="<?php echo htmlspecialchars($this->getCurMenu()->getMetatagDescription());?>"/>
    <meta name="keywords" content="<?php echo htmlspecialchars($this->getCurMenu()->getMetatagKeywords());?>"/>
    <title>
        <?php
                                  $title = $this->getCurMenu()->getTitle();
        echo $title ? $title . ' - ' . Config::findByCode('website_name')->getValue()
                : Config::findByCode('website_name')->getValue();
        ?>
    </title>
    <link rel="stylesheet" type="text/css" href="/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="/css/tools.css"/>
    <link rel="stylesheet" type="text/css" href="/css/new.css"/>
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/jquery.tools.min.js"></script>
    <script type="text/javascript" src="/js/mbulka.js"></script>
    <script type="text/javascript" src="/js/bbulka.js"></script>
    <script type="text/javascript" src="/js/functions.js"></script>
    <link rel="shortcut icon" href="/favicon.ico"/>
    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-525073-27']);
        _gaq.push(['_setDomainName', '.red.ua']);
        _gaq.push(['_trackPageview']);

        (function() {
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
<?php echo $this->cached_top_menu_1?>
<div class="head_box">
    <div class="body head-block">
        <div class="header">
            <div class="login" style="padding:10px; position:absolute; right:0; text-align: right;">
                <?php  if (!$this->ws->getCustomer()->getIsLoggedIn()) { ?>
                <a href="/account/">Войти в личный кабинет</a> |
                <a href="/account/resetPassword/">Забыл
                    пароль?</a>
                <?php } else { ?>
                Здравствуйте <b><?= $this->ws->getCustomer()->getFirstName() ?></b><br/> <a href="/account/">профайл</a>
                | <a
                        href="/account/orderhistory/">мои заказы</a> | <a href="/account/logout/">выход</a>
                <?php } ?>
            </div>
            <!--img src="/img/newyear.png" alt="newyear" style="position: absolute; z-index: 999; left: 30px; top: 5px;" /-->
			|<h1 class="logo"><a href="/"><img src="/img/logo-8mart.png" alt="red" /></a></h1>

            <p class="phone"><img src="/img/VoIP2.png" alt="tel" style="margin-bottom: -18px;margin-top: 10px;" /><span>(044)</span> 462-50-90  <br />
                <span style=" margin-left: 30px;">(044)</span> 462-57-67<br />
                <span style=" margin-left: 30px;">(093)</span> 854-23-53<br />
                <span style=" margin-left: 30px;">(067)</span>  406-90-80<br />
                <a href="#" style="color: #a1a1a1; font-size: 11px;margin-left: 30px;  "
                   onclick="return false">oбратный звонок</a></p>

            <div class="hidden html">
                <div class="basket-bulka-box">
                    <h1>Oбратный звонок</h1>

                    <form action="/account/callmy/" method="post">
                        <table class="basket-cont">
                            <tr>
                                <td><label class="label-contact" style="width:60px">Имя *</label></td>
                                <td><input type="text" value="" id="city" class="formfields" name="name"
                                           style="width:110px"></td>
                            </tr>
                            <tr>
                                <td><label class="label-contact" style="width:60px">Телефон *</label></td>
                                <td><input type="text" value="" id="phone" class="formfields" name="phone"
                                           style="width:110px"></td>
                            </tr>
                        </table>
                        <br/>
                        <input type="submit" value="ПЕРЕЗВОНИТЕ"
                               style="background: none repeat scroll 0 0 #E8641B; color: #FFFFFF; padding: 3px 15px; border:none">
                    </form>
                </div>
            </div>
          <!--  <div class="socseti">
<a target="_blank" href="http://vkontakte.ru/club21090760">
<img height="30" alt="vkontakte" src="/img/vkontakte.png">
</a>
<a target="_blank" href="http://www.facebook.com/pages/RED-Ukraine/148503625241218?sk=wall">
<img height="30" alt="facebook" src="/img/Facebook.png">
</a>
<a target="_blank" href="http://twitter.com/#!/red_ukraine"><img height="30" alt="twitter" src="/img/twitter.png"></a>
            </div>-->

            <?php echo $this->render('parts/basket.tpl.php'); ?>
        </div>
        <?php echo $this->cached_top_menu_2;?>
        <?php echo $this->render('parts/topmenu.tpl.php'); ?>
    </div>
</div>
<div class="body">

<?php echo "hello"; ?>
<div class="homepage-box">

    <div class="column-1">

        <div class="ca-box">
            <div class="ca-ul-box">
                <ul class="ca-ul">

                    <?php

                    $count_slide =0;
                    $img = wsActiveRecord::useStatic('Shoparticlestop')->findAll(array(), array(), array('4'));
                  
                        ?>
                             <?php if ($this->block6->count()) {
                            foreach($this->block6 as $block){
                                $count_slide++;
                            ?>
                        <li class="ca-li ca-item-box">
                            <a class="img" style="width: 710px" href="<?php echo $block->getUrl()?>"><img style="width: 710px"
                                                                              src="<?php echo $block->getImage()?>"
                                                                              alt="<?php echo $block->getName()?>"/></a>
                        </li>
                            <?php } } ?>

                        <?php

                        if ($img->count() > 0 and Config::findByCode('show_image_to_home')->getValue()) {
                            foreach ($img as $item)
                            {
                                $count_slide++;
                                $article = $item->getArticle();
                                $label = false;
                                //if ($_SERVER['REMOTE_ADDR'] == '93.72.133.153')
                                {

                                    if (wsActiveRecord::useStatic('Shoparticleslabel')->findFirst(array('id' => $article->getLabelId()))) {
                                        $label = wsActiveRecord::useStatic('Shoparticleslabel')->findFirst(array('id' => $article->getLabelId()))->getImage();

                                    }
                                }

                                ?>
                                <li class="ca-li ca-item-box">
                                    <?php if ($label) { ?>
                                    <div class="article_label_container_3">
                                        <div class="article_label_3">
                                            <img src="<?php echo $label?>" alt=""/>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <a class="img" style="margin-left:100px;" href="<?php echo $article->getPath(); ?>"><img
                                            src="<?php echo $article->getImagePath('homepage'); ?>"
                                            alt="<?php echo htmlspecialchars($article->getTitle()); ?>"/></a>

                                    <div class="name">
                                        <?php echo $article->getBrand(); ?>
                                        <span><?php echo $article->getModel(); ?></span>
                                    </div>
                                    <?php if ($article->getDiscount()) { ?><div class="price-old">СТАРАЯ
                                ЦЕНА:<br/><?php echo Shoparticles::showPrice($article->getOldPrice()); ?>
                                грн</div><?php } ?>
                                    <div class="price-new">НОВАЯ ЦЕНА<br/><span
                                            class="price"><?php echo Shoparticles::showPrice($article->getPrice()); ?>
                                        грн</span>
                                        <?php if ($article->getDiscount()) { ?><span
                                                class="defer">(экономия <?php echo $article->getDiscount(); ?>
                                        %)</span><?php } ?>
                                    </div>
                                    <!--<div class="order-form-box">
                                        <form action="<?php /*echo $article->getPath(); */?>" method="post">
                                            <div>
                                                <input type="submit" class="submit" value="В КОРЗИНУ"/>
                                            </div>
                                        </form>
                                    </div>-->
                                </li>
                               <!-- <li class="ca-li ca-item-box">
                                    <a class="img" style="width: 650px" href="/konkurs/street_look/"><img
                                            style="width: 650px"
                                            src="/storage/images/302a6d7fdd1055e85ab6f4190848aeda.jpg" alt="baner"/></a>
                                </li>-->
                                <?php

                            }
                        }

                    ?>

                </ul>
            </div>
            <a href="javascript:void(0);" class="ca-button ca-button-left"></a>
            <a href="javascript:void(0);" class="ca-button ca-button-right"></a>
        </div>

    </div>

    <div class="column-2">
        <?php if ($this->block1->count()) {  $count_slide = 0;?>
        <div class="scrollable" id="scrollable" style="margin-bottom: 5px;">
            <div class="items">
                <?php
                                    foreach ($this->block1 as $block) {
                $count_slide++;
                ?>
                <div class="item">
                    <a href="<?php echo $block->getUrl()?>"><p><?php echo $block->getTitle()?></p>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td valign="middle">
                                    <img alt="<?php echo $block->getName()?>" src="<?php echo $block->getImage()?>">
                                </td>
                            </tr>
                        </table>
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php if($count_slide>1){?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#scrollable").scrollable({ vertical: true, circular: true});
                setInterval(function() {
                    $("#scrollable").data("scrollable").next()
                }, 8400);
                 
            });
        </script>
        <?php } ?>
        <?php } ?>

         <?php if ($this->block5->count()) {
        $count_slide =0;?>
        <div class="scrollable" id="scrollable5">
            <div class="items">
                <?php
                                    foreach ($this->block5 as $block) {
                $count_slide++;
                ?>
                <div class="item">
                    <a href="<?php echo $block->getUrl()?>"><p><?php echo $block->getTitle()?></p>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td valign="middle">
                                    <img alt="<?php echo $block->getName()?>" src="<?php echo $block->getImage()?>">
                                </td>
                            </tr>
                        </table>
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php if($count_slide>1){?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#scrollable5").scrollable({ vertical: true, circular: true});
                setTimeout(function(){setInterval(function() {
                    $("#scrollable5").data("scrollable").next()
                }, 9200)},1100);
            });
        </script>
        <?php } ?>
        <?php } ?>


    </div>

    <div class="clear"></div>
</div>
<div class="home-other-box">
    		<div class="ui-widget" id="poll_div">
     <?php                      //если есть активное голосование, покаываем его, а не новости

    $active = wsActiveRecord::useStatic('Poll')->count(array('active' => 1));
    if ($active && !@$_COOKIE['polled']) {
        ?>

            <?php echo Poll::drawLastPoll();?>

        <?php } else{  ?>
            <img src="/img/golos_ok.png" width="499" height="75" alt="senk" />
            <?php } ?>
</div>
         <form action="/subscribe/" method="post">
             <div class="poll_div" style="float: right;">
 <table class="poll_main" cellpadding="0" cellspacing="0">
     <tr><td style=" border-bottom: 1px solid #DDDDDD; padding:5px; ">
<div class="poll_title">
      Подписаться на новости, акции, распродажи, и конкурсы <input type="submit" class="submit" style="float: right; margin: 0;" value="Подписаться"/>
</div>
     </td>
     </tr><tr><td style="padding:5px; font-weight: bold;">
                    <div>
                <label>Имя</label>

                    <input type="text" class="text" value="" name="name"/>

                <label>E-mail</label>


                    <input type="hidden" name="active" value="1"/>
                    <input type="text" class="text" value="" name="email"/>

            </div>
     </td>
      </tr>
    </table>
               </form>
    </div>
<script type="text/javascript">
    $('document').ready(function(){
        if($('.mail-box').height() > $('#poll_div').height()){
          $('#poll_div').height( $('.mail-box').height()+10);
        }
         if($('.mail-box').height() < $('#poll_div').height()){
           $('.mail-box').height($('#poll_div').height()-10);
        }
    })
</script>
<div class="clear"></div>
    </div>
<div class="home-content-box">
    <div class="content-box">


        <?php echo $this->getCurMenu()->getPageBody(); ?>

    </div>
</div>
<div class="block-box">
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>

<?php if ($this->block2->count()) { $count_slide=0; ?>
                <div class="scrollable2" id="scrollable2" style="float: left;">
                    <div class="items">
        <?php
                    foreach ($this->block2 as $block) {
                         $count_slide++;
            ?>
            <div class="item">
                <a href="<?php echo $block->getUrl()?>">
                    <p><?php echo $block->getTitle()?></p>
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td valign="middle">
                                <img alt="<?php echo $block->getName()?>" src="<?php echo $block->getImage()?>">
                            </td>
                        </tr>
                    </table>

                </a>
            </div>
            <?php } ?>
                    </div>
                </div>
                <?php  if($count_slide>1){?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#scrollable2").scrollable({ circular: true});
                       setTimeout(function(){ setInterval(function() {
                           $("#scrollable2").data("scrollable").next()
                        }, 8200)},500);
                    });
                </script>
        <?php } ?>
    <?php } ?>
            </td>
            <td style="width: 328px;">

<?php if ($this->block3->count()) { $count_slide=0;?>
                <div class="scrollable2" id="scrollable3">
                    <div class="items">
        <?php
                    foreach ($this->block3 as $block) {
                        $count_slide++;
            ?>
            <div class="item">
                <a href="<?php echo $block->getUrl()?>">
                    <p><?php echo $block->getTitle()?></p>
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td valign="middle">
                                <img alt="<?php echo $block->getName()?>" src="<?php echo $block->getImage()?>">
                            </td>
                        </tr>
                    </table>

                </a>
            </div>
            <?php } ?>
                    </div>
                </div>
                <?php if($count_slide>1){?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#scrollable3").scrollable({ circular: true});
                          setTimeout(function(){ setInterval(function() {
                            $("#scrollable3").data("scrollable").next()
                        }, 9400)}, 700);
                    });
                </script>
        <?php } ?>
    <?php } ?>
            </td>
            <td>
<?php if ($this->block4->count()) { $count_slide=0; ?>

                <div class="scrollable2 no_marg" id="scrollable4" style="float: right;">
                    <div class="items">
        <?php
                    foreach ($this->block4 as $block) {
                        $count_slide++;
            ?>
            <div class="item">
                <a href="<?php echo $block->getUrl()?>">
                    <p><?php echo $block->getTitle()?></p>
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td valign="middle">
                                <img alt="<?php echo $block->getName()?>" src="<?php echo $block->getImage()?>">
                            </td>
                        </tr>
                    </table>

                </a>
            </div>
            <?php } ?>
                    </div>
                </div>
                <?php  if($count_slide>1){?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#scrollable4").scrollable({ circular: true});
                        setTimeout( function(){ setInterval(function() {
                            $("#scrollable4").data("scrollable").next()
                        }, 9000)}, 900);
                    });
                </script>
        <?php } ?>
    <?php } ?>
            </td>
        </tr>
    </table>

    <div class="clear"></div>

</div>

<?php echo $this->cached_bottom_menu; ?>



<div class="copyright-box">
    <div class="right">RED &copy; 2010-<?php echo date('Y');?>. All rights reserved.</div>
    <div class="left"><a href="http://www.webunion.com.ua/" class="logo"></a>
        <a href="http://www.webunion.com.ua/">Разработка интернет-магазинов WebUnion</a></div>

</div>

</div>
<!-- Yandex.Metrika counter -->
<div style="display:none;">
    <script type="text/javascript">
        (function(w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter12225895 = new Ya.Metrika({id:12225895, enableAll: true, webvisor:true});
                }
                catch(e) {
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
</body>
</html>