<!DOCTYPE html >
<html lang="<?=Registry::get('lang')?>">
<head>
<?php //clearstatcache(); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="description" content="<?=htmlspecialchars($this->getCurMenu()->getMetatagDescription()); ?>"/>
    <meta name="keywords" content="<?=htmlspecialchars($this->getCurMenu()->getMetatagKeywords()); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="google-site-verification" content="5KsgGP4-JCTjV0dafIfi5_AI73MIryFuGqLvAgIthAI" />
    <title><?=Config::findByCode('home_title')->getValue()?></title>
	
	
	<link rel="alternate" hreflang="ru-UA" href="https://www.red.ua/ru<?=$_SERVER['REQUEST_URI']?>" />
		<link rel="alternate" hreflang="uk-UA" href="https://www.red.ua/uk<?=$_SERVER['REQUEST_URI']?>" />
	<link rel="shortcut icon" href="/favicon.ico"/>	
	<link  rel="stylesheet" type="text/css" href="/js/slider-fhd/slick.css?v=1"  />
	<link  rel="stylesheet" type="text/css" href="/js/slider-fhd/slick-theme.css?v=1" />
    <link rel="stylesheet" type="text/css" href="/css/bs/css/bootstrap.css?v=1.0"/>
    <link rel="stylesheet" type="text/css" href="/css/style.css?v=2.3.1"/>
    <link rel="stylesheet" type="text/css" href="/css/new.css?v=1.3"/>
    <!--<link rel="stylesheet" type="text/css" href="/css/stores/fm.revealator.jquery.min.css"/>-->
    
	
    <script src="/js/jquery.js"></script>
     <script src="/js/timer.js?v=1.6"></script>
	
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5DFS2PQ');</script>
<!-- End Google Tag Manager -->
</head>
<body>
 <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DFS2PQ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<header>
<?=$this->cached_top_menu;?>
<?=$this->cached_topcategories;?>
</header>
<!-- содержымое главной страницы-->
<?php /*if($this->ws->getCustomer()->isAdmin()){ } */?>
<?php if($this->ws->getCustomer()->getId() == 8005){
echo '<div style="position: fixed;top: 47px;left: 0px;"><a href="/advertising/">Реклама</a></div>';

}?>
<section>
<div class="container-fluid">
    <?php if(true){ ?>
        <div class="row bg-dark p-1">
        <style>.col.text-center.p-1 a:hover { text-decoration: none;</style>   
            <div class="col text-center p-1">
                <a href="/advantages/" class="text-white font-weight-bold text-uppercase">
                <div class="d-inline-block">
                    <img class="" style="width: 25px" src="/storage/images/RED_ua/pays/delyvery.png" alt="Card image cap">
                </div>
                <span><?=$this->trans->get('Доставка товара от 1 дня')?></span>
                </a>
            </div>
             <div class="col text-center p-1 ">
                <a href="/advantages/" class="text-white font-weight-bold text-uppercase">
                <div class="d-inline-block">
                    <img class="" style="width: 25px" src="/storage/images/RED_ua/pays/new.png" alt="Card image cap">
                </div>
                <span><?=$this->trans->get('Новый товар каждый день')?></span>
                </a>
            </div>
            <div class="col text-center p-1">
                <a href="/advantages/" class="text-white font-weight-bold text-uppercase">
                <div class="d-inline-block">
                    <img class="" style="width: 25px" src="/storage/images/RED_ua/pays/sizes.png" alt="Card image cap">
                </div>
                <span><?=$this->trans->get('Возможность примерки товара')?></span>
                </a>
            </div>
    </div>
        
    <?php } ?>
<?php $c = $this->block6->count(); if ($c > 0) { ?>
<!--новый банер-->
<div class="row">
	<div class="slider-baner col-md-12 col-xl-12 px-0" >
            <div id="moby_slider" class="carousel slide" data-ride="carousel" data-keyboard="true">
                <ol class="carousel-indicators">
                    <?php for($j=0; $j<$c;$j++){ ?>
    <li data-target="#moby_slider" data-slide-to="<?=$j?>" <?=$j==0?'class="active"':''?> ></li>
                    <?php }?>
  </ol>
                
  <div class="carousel-inner">
           <?php $i=0;  foreach ($this->block6 as $block) { ?>
			<div class="carousel-item <?=$i==0?'active':''?>"  >
				<a class="img" href="<?=$block->getUrl()?>">
					<img  class="d-block w-100" src="<?=$block->getImage()?>" alt="<?=$block->getName()?>" onclick="dataLayer.push({'event': 'banner'});" />
				</a>
			</div>
<?php $i++; }?>
  </div>
  <a class="carousel-control-prev" href="#moby_slider" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#moby_slider" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
	</div>
</div>
<!--/ новый банер-->
<?php } ?>
<?php if(/*$this->ws->getCustomer()->getId() == 8005*/ true){ ?>
<div class="row m-auto1 bg-dark">
    <div class="col-sm-12 col-md-12 col-lg-2 col-xl-2 text-center p-2">
        <a href="https://t.me/shop_red_ua" target="_blank" class="text-white">
            <img class="w-50" src="/img/social_black/telega.png" alt="telegram">
        </a>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10 text-center p-2 align-middle text-white  text-uppercas">
  Друзья! Теперь вы можете первыми узнавать  о последних новостях и предложениях нашего интернет-магазина  red.ua  в мессенджере Telegram на своём мобильном телефоне.<br>
  Присоединяйтесь к нашему TELEGRAM-КАНАЛУ – <a href="https://t.me/shop_red_ua" target="_blank" class="text-white">shop_red_ua</a>
    </div>
</div>
<?php } ?>
<?php if ($this->topproduct->count() > 5) { ?>
<!--top articles-->
<div class="row">
<div class="block-title py-4 w-100">
	<div class="vc_separator    vc_sep_pos_align_center vc_sep_color_black double-bordered-thick ">
	<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
	<h4><?=$this->trans->get('ПОПУЛЯРНЫЕ ТОВАРЫ');?><span><?=$this->trans->get('самые просматриваемые товары');?></span></h4>
	<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
	</div>
</div>
    <div class="top_articles col-md-12 px-0">
<?php foreach ($this->topproduct as $block) {
            if ($block->getId()) { ?> 
			<div class="top_articles_item col-xs-12 col-sm-6 col-md-3" >
         <a  href="<?=$block->getPath();?>" style="    text-align: center;">
        <img  src="<?=$block->getImagePath('detail'); ?>" alt="<?=$block->getBrand();?>" style="max-width:100%;"  >  
		</a>
				<div class="post-name" >
				<h2><a href="<?=$block->getPath();?>"><?=$block->getModel();?></a></h2>
				<h2><a href="<?=$block->getPath();?>"><?=$block->getBrand();?></a></h2>
				</div>
				<hr>
				<p><?=$block->getPrice()?> грн</p>
     </div>
<?php } } ?>
    </div>
</div>
<!--top articles-->
<?php } ?>

<?php if($this->homeblock->count() > 0) { ?>
<!--top categories-->
<div class="row homeblock"  >
<?php foreach ($this->homeblock as $block) { ?>
<div class=" col-xs-12 col-sm-6 col-md-3 p-1">
<div class="column-inner">
				 <a href="<?=$block->getUrl()?>">
				 <img alt="<?=$block->getName()?>" src="<?=$block->getImage()?>" >
				 <span class="ol"></span>
				 <span class="centered">
				 <span class="title-block"><strong class="line-top"><?=$block->getName()?></strong></span>
				 </span>
				</a>
</div>
</div>
<?php } ?> 
<div class="clearfix"></div>
</div>
<!--top categories-->
<?php } ?>
<!--one articles-->
<?php if ($this->oneproduct->count() > 4) { ?>
<div class="row  ">
<div class="block-title py-4 w-100">
	<div class="vc_separator    vc_sep_pos_align_center vc_sep_color_black double-bordered-thick ">
	<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
	<h4><?=$this->trans->get('ПОСЛЕДНИЕ ТОВАРЫ');?><span><?=$this->trans->get('ПОСЛЕДНИЙ РАЗМЕР ИЛИ ПАРА');?></span></h4>
	<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
	</div>
	</div>
    <div class="top_articles col-md-12 px-0">
<?php foreach ($this->oneproduct as $block) {
            if ($block->getId()) { ?> 
			<div class="top_articles_item col-md-3" >
         <a  href="<?=$block->getPath();?>" style="text-align: center;">
        <img  src="<?=$block->getImagePath('detail'); ?>" alt="<?=$block->getBrand();?>" style="max-width:100%;"  >  
		</a>
				<div class="post-name" >
				<h2><a href="<?=$block->getPath();?>"><?=$block->getModel();?></a></h2>
				<h2><a href="<?=$block->getPath();?>"><?=$block->getBrand();?></a></h2>
				</div>
				<hr>
				<p><?=$block->getPrice()?> грн</p>
     </div>
<?php } } ?>
    </div>
</div>
<?php } ?>
<!--one articles-->
<!--blog-->
<?php if($this->blog){ ?>
<div class="row  ">
<div class="block-title py-4 w-100">
	<div class="vc_separator    vc_sep_pos_align_center vc_sep_color_black double-bordered-thick ">
	<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
	<h4>BLOG<span><?=$this->trans->get('Модные тенденции и тренды');?></span></h4>
	<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
	</div>
	</div>
<?php foreach($this->blog as $b){ ?>
<div class="col-xs-12  col-md-4 p-1">
	<article>           
               <div class="blog-div-fon">
			   <div class="post-image">
			    <a href="<?=$b->getPath()?>?utm_source=blog&utm_medium=link&utm_content=Blog&utm_campaign=Blog">
			   <img alt="<?=$b->getPostName()?>" src="/storage<?=$b->getImage()?>">
			   <span class="hover-overlay"></span>
			   <span class="hover-readmore"><?=$this->trans->get('Смотреть далее');?>...</span>
			   </a>
			   </div>
			   <div class="post-name">
				<h2><a href="<?=$b->getPath()?>?utm_source=blog&utm_medium=link&utm_content=Blog&utm_campaign=Blog"><?=$b->getPostName()?></a></h2>
				</div>
				<div class="post-date"><?=date("d.m.Y", strtotime($b->getUtime()))?></div>
				</div>
	</article>
</div>
<?php } ?> 
<div class="clearfix"></div>
</div>
<?php } ?>
<!--blog-->
<!--brand-->
<div class="row brand mt-2  " >
<div class="new_brand_slider" >
<div class="border-box">
					<?php $i=0; if($this->cached_brands->count() >= 3){
					foreach ($this->cached_brands as $brand) {
						if($brand['logo'] != NULL){ ?>
					<div class="brand_item" >
						<a href="/category/brands/<?=(int)$brand['brand_id'];?>" >
						<img style="max-height: 65px;max-width: 150px;" src="<?=$brand['logo']; ?>" alt="<?=$brand['brand'];?>">
						</a>
					</div>
					<?php 
					$i++;
					}
					if($i >= 8) break;
					}
					}
					?>
			</div>
	</div>
</div>
<!--brand-->
<div class="row mt-2  ">
    <div class="card p-3">
        <div class="carg-body">
            <!--seo-text-begin-->
    <?=$this->getCurMenu()->getPageFooter()?>
            <!--seo-text-end-->
        </div>
    </div>
</div>
<!--пуш о смене email -->
<?php if(!isset($_COOKIE['puch_close']) and isset($_COOKIE['s']) and $_COOKIE['s'] !='' and false){ 
if($this->ws->getCustomer()->getIsLoggedIn()){ 
if(!$this->ws->getCustomer()->isClosePuch()){ echo $this->puch; } 
}else{ echo $this->puch; }
} ?>
<!--/пуш о смене email -->
</div><!--end container-fluid div-->
</section>
<script>
    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments)};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
rnt('add_event', {advId: '20676', pageType:'home'});
rnt('add_audience', {audienceId: '20676_254951d7-6d13-4ea2-a507-747c9e6fe802'});
    //<!-- EVENTS FINISH -->
</script>
<!--footer--><?php echo $this->cached_bottom_menu; ?><!--footer-->

<?php 
if(Registry::get('device') != 'computer' or ($_COOKIE['mobil'] and $_COOKIE['mobil'] == 10) and false){ ?>
 <div id="slk" class="slk"><input type="button" name="mobi" class="btn btn-secondary btn-sm" onClick="setCooki(0);" value="<?=$this->trans->get('Мобильная версия');?>"><br></div>
<?php } ?>
<script>
    /* <![CDATA[ */
    var google_conversion_id = 1005381332;
    var google_conversion_label = "1vqdCJyg5gMQ1M2z3wM";
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script   src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript><div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1005381332/?value=0&amp;label=1vqdCJyg5gMQ1M2z3wM&amp;guid=ON&amp;script=0"/>
</div></noscript>

<script>
$(document).ready(function(){
var width = window.innerWidth;
var w = 6;
if(width <= 1200){w = 5;}
if(width <= 1003) { w = 4;}
if(width <= 993) { w = 3;}
if(width <= 770) { w = 2;}
/*
$('.slider-baner').slick({
accessibility: true,
adaptiveHeight: true,
arrows: true,
autoplay: true,
prevArrow: '<img src="#" style="background-image:url(/img/slider/p-n-b.png); width:50px; height:50px;"  data-role="none" class="slick-prev-next prev" aria-label="Previous" tabindex="0" role="button">',
nextArrow: '<img src="#" style="background-image:url(/img/slider/p-n-b.png); width:50px;height:50px;"  data-role="none" class="slick-prev-next next" aria-label="Next" tabindex="0" role="button">',
autoplaySpeed: 3000,
draggable: true,
easing: 'fade',
fade: true,
speed: 1000,
dots: true
});*/
$('.top_articles').slick({
	prevArrow: '<img src="#" style="background-image:url(/img/slider/p-n-b.png);" data-role="none" class="slick-prev-next prev" aria-label="Previous" tabindex="0" role="button">',
nextArrow: '<img src="#" style="background-image:url(/img/slider/p-n-b.png);"  data-role="none" class="slick-prev-next next" aria-label="Next" tabindex="0" role="button">',
      slidesToShow: w,
	  slidesToScroll: w,
	  autoplaySpeed: 4000,
	  speed: 1000,
	  easing: 'fade',
	  autoplay: false,
	  });  
});
</script>
<script src="/js/slider-fhd/slick.min.js"></script>	
	<script  src="/js/functions.js?v=1.3"></script>	
	<script src="/js/jquery.liFixar.js"></script>
     <script  src="/css/bs/js/bootstrap.js?v=1.0"></script>
	 <script   src="/css/bs/js/bootstrap.bundle.min.js?v=1.0"></script>
         <!--<script  src="/js/stores/fm.revealator.jquery.min.js?v=1.0"></script>-->
       <!--  <script  src="/js/parallax/parallax.min.js?v=1.0"></script>	-->
          
	 <script>
     
     $(window).bind('scroll',function(e){
         parallaxScroll();
    console.log($(window).scrollTop());
});
function parallaxScroll(){
    var scrolled = $(window).scrollTop();
    if(scrolled >= 490){ // transform: matrix(1, 0, 0, 1, 0, 31);
        
        //-o-transform: translateY(-20px);
 // -ms-transform: translateY(-20px);
  //-moz-transform: translateY(-20px);
  //-webkit-transform: translateY(-20px);  
  //transform: translateY(-20px);
       // $('.homeblock').css('transform', translateY(-21)+'px');
    //$('.homeblock').css('top',(0-(scrolled*.25))+'px');
    }
    //$('#parallax-bg2').css('top',(0-(scrolled*.5))+'px');
   // $('#parallax-bg3').css('top',(0-(scrolled*.75))+'px');
}
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
</body>


</html>