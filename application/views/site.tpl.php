<!DOCTYPE html>
<html id="html"  xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#" lang="ru" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="google-site-verification" content="5KsgGP4-JCTjV0dafIfi5_AI73MIryFuGqLvAgIthAI" />
    <?php
	//clearstatcache();
    $descriptions = '';
    $keywords = '';
    if ($this->getCurMenu()->getMetatagDescription()){
	$descriptions = $this->getCurMenu()->getMetatagDescription();
	}elseif ($this->getBlog()){ 
	$descriptions = 'Блог интернет-магазина одежды RED — о стиле жизни, о моде.';
	}elseif ($this->getOnepostblog()){ 
	if($this->onepostblog[0]->description){
	$descriptions = $this->onepostblog[0]->description;}else{
	$descriptions = $this->onepostblog[0]->post_name;
	}
	}elseif($this->getCategory()){ 
	$descriptions = strip_tags(stripslashes($this->getCategory()->getName())) . ' ' . strip_tags(stripslashes($this->getCategory()->getDescription()));
	}elseif ($this->getShopItem()) {
	$descriptions = strip_tags(stripslashes($this->getShopItem()->getModel())) . ' ' . strip_tags(stripslashes($this->getShopItem()->getBrand())) . '. ' . strip_tags(stripslashes($this->getShopItem()->getLongText()));
	}
	if ($this->getCurMenu()->getMetatagKeywords()){
	$keywords = $this->getCurMenu()->getMetatagKeywords();
	}elseif($this->getBlog()) {
	$keywords = 'блог, ред, мода, стиль, одежда, обувь, бренд';
	}elseif ($this->getOnepostblog()){ 
	$keywords = $this->onepostblog[0]->keyword;
	}elseif($this->getCategory()) {
	$keywords = strip_tags(stripslashes($this->getCategory()->getName())) .' '. strip_tags(stripslashes($this->getCategory()->getDescription()));
	}elseif ($this->getShopItem()) {
	$keywords = strip_tags(stripslashes($this->getShopItem()->getModel())) . ', ' . strip_tags(stripslashes($this->getShopItem()->getBrand()));
	}
	
	if($this->getShopItem()){
	$view = strip_tags(stripslashes($this->getShopItem()->getImagePath('listing'))); 
	}elseif($this->getCategory()){
	if($this->getCurMenu()->getPath() == '/product/') $view = strip_tags(stripslashes($this->getShopItem()->getImagePath('listing'))); 
	}elseif($this->getOnepostblog()){
	$view = '/storage'. strip_tags(stripslashes($this->onepostblog[0]->image)); 
	}elseif($this->getBlog()){
	$view = '/img/logo/logo_red.jpg'; 
	}else{
	$view = '/img/logo/logo_red.jpg';
	}
if(Registry::get('device') == 'computer' or ($_COOKIE['mobil'] and $_COOKIE['mobil'] == 10)){ $desctop = true; }else{ $desctop = false;}
	?>
    <meta name="description" content="<?= htmlspecialchars($descriptions); ?>"/>
    <meta name="keywords" content="<?=htmlspecialchars($keywords); ?>"/>
	<meta  name="image"  content="http://www.red.ua<?=htmlspecialchars($view);?>" />
	<meta  property="og:image"  content="http://www.red.ua<?=htmlspecialchars($view);?>" />
	<?php if($this->getCurMenu()->getNofollow()){ ?>
	 <meta name="robots" content="noindex, follow"/>
	<?php }elseif($this->get->controller == 'Account'){?>
	<meta name="robots" content="noindex, follow"/>
	<?php } ?>
    <title>
        <?php
		if($this->getOnepostblog()){
		$title = $this->onepostblog[0]->post_name;
		echo $title ? $title : Config::findByCode('website_name')->getValue();
		}else{
		$title = $this->getCurMenu()->getTitle();
		echo $title ? $title . ' - ' . Config::findByCode('website_name')->getValue() : Config::findByCode('website_name')->getValue();
		} ?>
    </title>    
		<link  rel="shortcut icon" href="/favicon.ico"/>
		<link href="/js/slider-fhd/slick.css" rel="stylesheet" type="text/css" />
		<link href="/js/slider-fhd/slick-theme.css" rel="stylesheet" type="text/css" />
        
		<?php if($desctop == true){ ?>
		<link rel="stylesheet" type="text/css" href="/css/bs/css/bootstrap.css?v=1.0"/>
		<link rel="stylesheet" type="text/css" href="/css/style.css?v=1.8"/>
		<?php }else{ ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/css/bs/css/bootstrap.css?v=1.1"/>
		<link rel="stylesheet" type="text/css" href="/css/style.css?v=1.8"/>
		<link rel="stylesheet" type="text/css" href="/css/common.css?v=1.9"/>
	<?php } ?>	
		
		<link rel="stylesheet" type="text/css" href="/js/select2/css/select2.min.css?v=1.0"/>
		<link rel="stylesheet" type="text/css" href="/css/new.css?v=1.0"/>
		<!--<link rel="stylesheet" type="text/css" href="/css/soc.css"/>-->
		<link rel="stylesheet" type="text/css" href="/css/cloud-zoom.css"/>
		<link rel="stylesheet" type="text/css" href="/css/jquery.lightbox-0.5.css" media="screen"/>		
		
	<script src="/js/jquery.js"></script>
	
				 
    <?php if (false) { ?>
        <script  type="text/javascript"
                src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?=Config::findByCode('google_map_api')->getValue();?>"
                type="text/javascript">
				</script>
    <?php } ?>
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
<div id="simple_overlay_back" class="simple_overlay_back"></div>
<header>
<?php if($desctop == true){
echo $this->cached_top_menu;  
echo $this->cached_topcategories;
}else{
 echo $this->cached_mobi_menu;
} ?>
</header>
<div class="container-fluid">
	<div class="row">
		<nav aria-label="breadcrumb" style="margin: 3px;">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/"><?=$this->trans->get('Главная');?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><?php echo @$this->getCategory() ? $this->getCategory()->getRoute() : $this->getCurMenu()->getName();?></li>
			</ol>
		</nav>
	</div>
<div class="clearfix"></div>
<div class="row column-1"> </div>		
<div class="row column-2">
<div class="content-box p-1">
<?php
$list_id = '';
 echo $this->getContent(); ?>
</div>
</div>
        <?php if(trim($this->getCurMenu()->getUrl()) =='basket'){
					$price_basket_hist = $this->trans->get('Цена');
					$mas_b = array();
					foreach ($_SESSION['basket'] as $k => $v){
					$mas_b[] = $v['article_id'];
					}
					$mass = array();
if($this->history){ ?>
 <div class="row column-3">
<div class="block-title py-4 w-100">
	<div class="vc_separator    vc_sep_pos_align_center vc_sep_color_black double-bordered-thick ">
	<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
	<h4><?=$this->trans->get('Вы недавно смотрели');?><span><?=$this->trans->get('успейте купить пока есть в наличии')?></span></h4>
	<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
	</div>
</div>
<div  class=" top_articles  col-md-12 px-0"> 
                    <?php
					$i=0; 
					foreach ($this->history as $v) {
					if(in_array($v->id, $mas_b)) break;

					?>
			<div class="top_articles_item col-xs-12 col-sm-6 col-md-3" >
         <a  href="<?=$v->getPath();?>" style="    text-align: center;">
        <img  src="<?=$v->getImagePath('detail'); ?>" alt="<?=$v->getBrand();?>" style="max-width:100%;"  >  
		</a>
				<div class="post-name" >
				<h3><a href="<?=$v->getPath();?>"><?=$v->getModel();?></a></h3>
				<h4><a href="<?=$v->getPath();?>"><?=$v->getBrand();?></a></h4>
				</div>
				<hr>
				<p><?=$v->getPrice()?> грн</p>
     </div>
                    <?php 
					$i++;
                    if($i == 15) break;
					} ?>
					</div>
					</div>
 
 <?php
}elseif(count($_SESSION['hist']) > 0){
foreach ($_SESSION['hist'] as $v) {
if(!in_array($v, $mas_b))$mass[] = $v;
}
$mass = array_unique($mass);
krsort($mass);


if(count($mass) > 0){ ?>
<div class="row column-3">
<div class="block-title py-4 w-100">
	<div class="vc_separator    vc_sep_pos_align_center vc_sep_color_black double-bordered-thick ">
	<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
	<h4><?=$this->trans->get('Вы недавно смотрели');?><span><?=$this->trans->get('успейте купить пока есть в наличии')?></span></h4>
	<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
	</div>
</div>
<div  class=" top_articles  col-md-12 px-0"> 
                    <?php
					$i=0; 
					foreach ($mass as $v) {
					$block = wsActiverecord::useStatic('Shoparticles')->findById($v); 
						if($block and $block->getStock() > 0){
					?>
			<div class="top_articles_item col-xs-12 col-sm-6 col-md-3" >
         <a  href="<?=$block->getPath();?>" style="    text-align: center;">
        <img  src="<?=$block->getImagePath('detail'); ?>" alt="<?=$block->getBrand();?>" style="max-width:100%;"  >  
		</a>
				<div class="post-name" >
				<h3><a href="<?=$block->getPath();?>"><?=$block->getModel();?></a></h3>
				<h4><a href="<?=$block->getPath();?>"><?=$block->getBrand();?></a></h4>
				</div>
				<hr>
				<p><?=$block->getPrice()?> грн</p>
     </div>
                    <?php 
					$i++;
					}
                    if($i == 15) break;
					} ?>
					</div>
					</div>
					<?php }

					}?>
					
					
					<?php }?>	
<!--похожее товары-->
<?php if(trim($this->getCurMenu()->getUrl()) == 'product'){

$articles_query1 = '
SELECT * FROM ws_articles WHERE
ws_articles.`stock` >0
AND  (ws_articles.`model` =  "'.$this->getShopItem()->getModel().'" or ws_articles.`model_uk` =  "'.$this->getShopItem()->getModel().'")
AND  ws_articles.`category_id` ='.$this->getShopItem()->getCategoryId().'
AND   ws_articles.id != '.$this->getShopItem()->getId().'
AND ws_articles.active =  "y"
and ws_articles.status = 3
ORDER BY  `ws_articles`.`ctime` ASC 
LIMIT 10';
$finish_articles1 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($articles_query1);
    if ($finish_articles1->count() > 5 ) { ?>
<div class="col-md-12 mx-auto w-100">
<div class="block-title py-4 w-100">
	<div class="vc_separator    vc_sep_pos_align_center vc_sep_color_black double-bordered-thick ">
	<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
	<h4><?=$this->trans->get('Мы рекомендуем');?><span><?=$this->trans->get('похожие товары с модельного ряда')?></span></h4>
	<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
	</div>
	</div>
<div class="top_articles col-md-12 px-0"> 
<?php
foreach ($finish_articles1 as $block) {
if ($block->getId()) {
?>
		<div class="top_articles_item col-xs-12 col-sm-6 col-md-3" >
        <a  href="<?=$block->getPath();?>" style="    text-align: center;">
        <img  src="<?=$block->getImagePath('detail')?>" alt="<?=$block->getBrand();?>" style="max-width:100%;"  >  
		</a>
				<div class="post-name" >
				<h3><a href="<?=$block->getPath();?>"><?=$block->getModel();?></a></h3>
				<h4><a href="<?=$block->getPath();?>"><?=$block->getBrand();?></a></h4>
				</div>
				<hr>
				<p><?=$block->getPrice()?> грн</p>
     </div>
<?php } } ?>         
</div>
</div>
<?php } ?>

<?php } ?>
<!--/похожее товары-->
<div class="clearfix"></div>
<?php if(trim($this->getCurMenu()->getUrl()) == 'product'){ ?>
<script>
    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments)};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
rnt('add_audience', {audienceId: '20676_254951d7-6d13-4ea2-a507-747c9e6fe802', priceId: '3047', productId: '<?=$this->getShopItem()->getId()?>'});
rnt('add_product_event', {advId: '20676', priceId: '3047', productId: '<?=$this->getShopItem()->getId()?>'});
    //<!-- EVENTS FINISH -->
</script>
<?php }else if(trim($this->getCurMenu()->getUrl()) == 'basket'){
$list_id = '';
$i=0;
foreach ($this->getBasket() as $key => $item) {
if($item['count'] > 0){
if($i == 0) {
		$list_id .= $item['article_id'];
		}else{
		$list_id .= ', '.$item['article_id'];
		}
		$i++;
		}
}
?>
<script>
    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments)};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
rnt('add_shopping_cart_event', {advId: '20676', priceId: '3047', productIds: '<?=$list_id?>'});
    //<!-- EVENTS FINISH -->
</script>
<?php }else if(trim($this->getCurMenu()->getUrl()) == 'category'){
$list_id = '';
$i=0;
foreach($this->articles as $article){
if($i == 0) {
		$list_id .= $article->getId();
		}else{
		$list_id .= ', '.$article->getId();
		}
		$i++;
}
 ?>
<script>
    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments)};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
	rnt('add_category_event', {advId: '20676', priceId: '3047', categoryId: '<?=$this->getCategory()->getId()?>', productIds: '<?=$list_id?>'});
	rnt('add_audience', {audienceId: '20676_254951d7-6d13-4ea2-a507-747c9e6fe802'});
    //<!-- EVENTS FINISH -->
</script>
<?php }else if(trim($this->getCurMenu()->getUrl()) == 'ordersucces'){ ?>
<script>
    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments)};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
	rnt('add_order_event', {advId: '20676', priceId: '3047', productIds: '<?=$_SESSION['list_articles_order']?>'});
    //<!-- EVENTS FINISH -->
</script>
<?php 
unset($_SESSION['list_articles_order']);
}else{ ?>
<script>
    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments)};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
rnt('add_audience', {audienceId: '20676_254951d7-6d13-4ea2-a507-747c9e6fe802'});
    //<!-- EVENTS FINISH -->
</script>
<?php } ?>
<!-- socsety--><?php if(trim($this->getCurMenu()->getUrl()) != 'product' and trim($this->getCurMenu()->getUrl()) !='basket' and trim($this->getCurMenu()->getUrl()) != 'shop-checkout-step2')  echo $this->socsety;?><!-- exit socsety-->
<!--пуш о смене email -->
<?php if(!isset($_COOKIE['puch_close']) and isset($_COOKIE['s']) and $_COOKIE['s'] !='' and false){ 
if($this->ws->getCustomer()->getIsLoggedIn()){ 
if( !$this->ws->getCustomer()->isClosePuch()){
 echo $this->puch;
 }
}else{
echo $this->puch;
}
} ?>
<!--/пуш о смене email -->
</div>
<!-- footer--><?php if($desctop == true){ echo $this->cached_bottom_menu; }else{ echo $this->cached_mobi_futer; } ?><!-- exit footer-->	
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
function hideShowDiv(){//filter hide/show
	$('#top').toggle();
      $('#filter').toggle();
	  $('body,html').animate({ scrollTop: 5 }, 'slow');  
}
$(document).ready(function(){ 
	   $('.top_articles').slick({
	prevArrow: '<img src="#" style="background-image:url(/img/slider/p-n-b.png);" data-role="none" class="slick-prev-next prev" aria-label="Previous" tabindex="0" role="button">',
nextArrow: '<img src="#" style="background-image:url(/img/slider/p-n-b.png);"  data-role="none" class="slick-prev-next next" aria-label="Next" tabindex="0" role="button">',
      slidesToShow: 5,
	  responsive: [ { breakpoint: 700, settings: { slidesToShow: 3 } },
	  { breakpoint: 480, settings: { slidesToShow: 1 } }],
	  autoplaySpeed: 3000,
	  speed: 500,
	  easing: 'fade',
	  autoplay: true,
	  });  
});

</script>
<script  src="/js/engine.js?v=1.3"></script>
<script   src="/js/jquery.liFixar.js"></script>
    <script src="/js/functions.js?v=1.3"></script>
    <script   src="/js/cloud-zoom.1.0.2.js"></script>
    <script  src="/js/jquery.cycle.all.js?v=3.0.3"></script>
    <script  src="/js/jquery.lightbox-0.5.js"></script>
    <script  src="/css/bs/js/bootstrap.js?v=1.5"></script>
	<script   src="/css/bs/js/bootstrap.bundle.min.js?v=1.0"></script>
	<script    src="/js/select2/js/select2.min.js?v=1.0"></script>
	
	<script  src="/js/slider-fhd/slick.min.js" ></script>
	<script >
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