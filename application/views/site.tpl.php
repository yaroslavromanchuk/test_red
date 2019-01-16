<!DOCTYPE html>
<html id="html"  xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#" lang="<?=Registry::get('lang')?>" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="google-site-verification" content="5KsgGP4-JCTjV0dafIfi5_AI73MIryFuGqLvAgIthAI" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>

    <?php
	//clearstatcache();
    $descriptions = '';
        if ($this->getCurMenu()->getMetatagDescription()){
	$descriptions = $this->getCurMenu()->getMetatagDescription();
	}elseif($this->getCategory()){
           if($this->descriptions){
            $descriptions = $this->descriptions;
            }else{
	$descriptions = strip_tags(stripslashes($this->getCategory()->getDescription()));
            }
	}
	if($this->getShopItem()){
	$view = strip_tags(stripslashes($this->getShopItem()->getImagePath('listing'))); 
	}elseif($this->getCategory()){
        if($this->getCurMenu()->getPath() == '/product/') { $view = strip_tags(stripslashes($this->getShopItem()->getImagePath('listing'))); } 
	}elseif($this->getOnepostblog()){
	$view = '/storage'. strip_tags(stripslashes($this->onepostblog[0]->image)); 
	}elseif($this->getBlog()){
	$view = '/img/logo/RED_Logo_min.png'; 
	}else{
	$view = '/img/logo/RED_Logo_min.png';
	}
	//2702-2018-0827
if(Registry::get('device') == 'computer' or ($_COOKIE['mobil'] and $_COOKIE['mobil'] == 10)){
    $desctop = true; 
}else{
    $desctop = false;
} ?>
    
    <meta name="description" content="<?=htmlspecialchars($descriptions)?>"/>
    
	<meta  name="image"  content="https://www.red.ua<?=htmlspecialchars($view);?>" />
	<meta  property="og:image"  content="https://www.red.ua<?=htmlspecialchars($view);?>" />
        
        <?php if($this->get->page){
           echo '<link rel="canonical" href="https://www.red.ua'.$this->g_url.'" />';
        } ?>
	<?php if($this->getCurMenu()->getNofollow()){ ?>
	 <meta name="robots" content="noindex, follow"/>
	<?php }elseif($this->get->controller == 'Account'){ ?>
	<meta name="robots" content="noindex, follow"/>
        <?php }elseif($this->get->controller == 'Developer'){ ?>
          
          <meta name="robots" content="noindex, nofollow"/>
    <?php  } ?>
          
    <title>
        <?php
                if($this->getCurMenu()->getTitle()){		
		echo $this->getCurMenu()->getTitle();
		}elseif($this->getCurMenu()->getUrl() == 'search'){
		echo $this->getCurMenu()->getPageTitle().' - '.Config::findByCode('website_name')->getValue();
		}else{
		$title = $this->getCurMenu()->getTitle();
		echo $title?$title.' - '.Config::findByCode('website_name')->getValue():Config::findByCode('website_name')->getValue();
		} ?>
    </title>  
	
	<?php
	global $uk;
	global $ru;

switch($_SESSION['lang']){
	case 'uk': 
	$uk = $_SERVER['REQUEST_URI'];
	$ru = substr($_SERVER['REQUEST_URI'], 3);
	break;
	case 'ru': 
	$uk = '/uk'.$_SERVER['REQUEST_URI'];
	$ru = $_SERVER['REQUEST_URI'];
	break;
	default: 
	$uk = '/uk'.$_SERVER['REQUEST_URI'];
	$ru = $_SERVER['REQUEST_URI'];
	break;
	
}
?>
	
		<link rel="alternate" hreflang="ru-UA" href="https://www.red.ua<?=$ru?>" />
		<link rel="alternate" hreflang="uk-UA" href="https://www.red.ua<?=$uk?>" />
		<link  rel="shortcut icon" href="/favicon.ico"/>
		<link href="/js/slider-fhd/slick.css" rel="stylesheet" type="text/css" />
		<link href="/js/slider-fhd/slick-theme.css" rel="stylesheet" type="text/css" />  
		<link rel="stylesheet" type="text/css" href="/css/bs/css/bootstrap.css?v=1.0"/>
                <link rel="stylesheet" type="text/css" href="/css/Ionicons/css/ionicons.min.css"/>
                <link rel="stylesheet" type="text/css" href="/js/select2/css/select2.min.css?v=1.2"/>
		<link rel="stylesheet" type="text/css" href="/css/style.css?v=2.2.8"/>
                <?php if(!$desctop){ ?><link rel="stylesheet" type="text/css" href="/css/common.css?v=2.38"/><?php } ?>	
		<link rel="stylesheet" type="text/css" href="/css/new.css?v=1.0"/>
		<link rel="stylesheet" type="text/css" href="/css/cloud-zoom.css"/>
		<link rel="stylesheet" type="text/css" href="/css/jquery.lightbox-0.5.css" media="screen"/>		
		
	<script src="/js/jquery.js"></script>
        <script src="/js/timer.js?v=1.6"></script>
		 
    <?php if (false) { ?>
        <script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?=Config::findByCode('google_map_api')->getValue();?>"></script>
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
    <?php if($this->user->id == 8005){
        //var_dump($this->getCategory());
        //echo print_r($this->getProduct());
       // echo 'tut';
        //echo print_r($this->get);
       // var_damp($this->get);
           // echo print_r($this->files);
	//echo $this->get
	/*if($this->getShopItem()){
            if($this->getShopItem()->getOptions()){
	echo print_r($this->getShopItem()->getOptions());
        echo $this->getShopItem()->getOptions()->type;
            }
	}*/
	//echo $this->getCategory()->getParent(1)->getTitle();
	//echo $this->getCategory()->getRoutezGolovna();
	//echo '<pre>';
	//echo print_r($this->getCurMenu());
	//echo '</pre>';
	//d(,false);
	//echo $_SERVER['REQUEST_URI'];
	//echo $this->getCurMenu()->getPath();
	//echo $this->getCurMenu()->getTitle();
	
         //  echo '<pre>';
          // print_r($this->transla);
			// print_r($this->getCurMenu());
			// print_r($this->meta);
			//echo print_r($this->getCategory()->getParent(1)->getTitle());
			//echo '</pre>';
        
	} ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DFS2PQ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
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
<div itemscope <?php if(trim($this->getCurMenu()->getUrl()) == 'product'){ echo 'itemtype="http://schema.org/Product"';} ?>  class="container-fluid">
<div class="row d-none d-md-block d-lg-block d-xl-block">
<nav aria-label="breadcrumb" style="margin: 3px;">
    <ol class="breadcrumb">
	<li class="breadcrumb-item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="/"><span itemprop="title"><?=$this->trans->get('Главная')?></span></a></li>
       <?php  if($this->getCategory()){
           echo $this->getCategory()->getRoute();
       }else{
           echo '<li class="breadcrumb-item active" aria-current="page" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="" ><span itemprop="title">'.$this->getCurMenu()->getName().'</span></a></li>';
       } ?>
    </ol>
</nav>
</div>
<div class="row">
    <div class="col-sm-12 text-center">
    <?php
if($this->g_url){ ?>
     <input type="text" hidden id="g_url" itemprop="url" value="<?=$this->g_url?>">
   <?php 
}
 ?>
                <h1 itemprop="name"  class="h1 text-dark font-size-100">
     <?php   if ($this->getCurMenu()->getName()){
	 echo ucfirst($this->getCurMenu()->getName()); 
}elseif($this->getCurMenu()->getUrl() == 'search'){
 echo $this->getCurMenu()->getTitle();
 }elseif ($this->getBlog()){
 echo $this->getCurMenu()->getTitle();
 }elseif($this->getOnepostblog()){
echo '<h1 itemprop="name" class="text-dark font-size-100">'.$this->onepostblog[0]->post_name.'</h1>';
 }
 ?>
                    
            </h1>
         </div>
</div>
<div class="row column-1 d-none d-md-block d-lg-block d-xl-block">
<div class="col-lg-12 text-center">
</div>
</div>		
<div class="row column-2">
<div class="content-box w-100"><?=$this->getContent()?></div>
</div>
<div class="row column-3">
<?php
if($this->getCurMenu()->getFooter()){ ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 p-3 "  >
<div class="caregory_footer bg-white p-3 text-secondary" style="font-size:12px;">
    <!--seo-text-begin-->
<?=$this->getCurMenu()->getFooter()?>
    <!--seo-text-end-->
</div>
</div>
<?php } ?>
</div>
<div class="clearfix"></div>
<?php  if(trim($this->getCurMenu()->getUrl()) == 'category'){
$sp = [];
foreach($this->articles as $article){$sp[] = $article->getId();}
$list_id = implode(',', $sp);
 ?>
<script>
    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments);};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
	rnt('add_category_event', {advId: '20676', priceId: '3047', categoryId: '<?=$this->getCategory()->getId()?>', productIds: '<?=$list_id?>'});
	rnt('add_audience', {audienceId: '20676_254951d7-6d13-4ea2-a507-747c9e6fe802'});
    //<!-- EVENTS FINISH -->
</script>
<?php }else if(trim($this->getCurMenu()->getUrl()) == 'ordersucces'){ ?>
<script>
    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments);};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
	rnt('add_order_event', {advId: '20676', priceId: '3047', productIds: '<?=$_SESSION['list_articles_order']?>'});
    //<!-- EVENTS FINISH -->
</script>
<?php 
unset($_SESSION['list_articles_order']);
}else{ ?>
<script>
    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments);};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
rnt('add_audience', {audienceId: '20676_254951d7-6d13-4ea2-a507-747c9e6fe802'});
    //<!-- EVENTS FINISH -->
</script>
<?php } ?>
</div>
 
<script  src="/js/filter.js?v=5.5"></script>
<script   src="/js/jquery.liFixar.js"></script>
    <script src="/js/functions.js?v=3.9.17"></script>
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
   <!-- footer--><?php if($desctop == true){ echo $this->cached_bottom_menu; }else{ echo $this->cached_mobi_futer; } ?><!-- exit footer-->	     
 <?php
if($this->user->id == 8005){
   //echo '<pre>';
   // print_r($this->getCurMenu());
   //print_r($_GET);print_r($this->get);
   
   //echo '</pre>';
}
 ?>       
</body>
</html>