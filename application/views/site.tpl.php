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
        <?php if($this->canonical){
             echo '<link rel="canonical" href="https://www.red.ua'.$this->canonical.'" />';
        }elseif($this->g_url ){
          //  if(Registry::get('lang') == 'uk'){
           // echo '<link rel="canonical" href="https://www.red.ua'.substr($this->g_url, 3).'" />';
       // }else{ 
             echo '<link rel="canonical" href="https://www.red.ua'.$this->g_url.'" />';
        // }
         }else{
         //    if(Registry::get('lang') == 'uk'){
         //   echo '<link rel="canonical" href="https://www.red.ua'.substr($_SERVER['REQUEST_URI'], 3).'" />';
      //  }else{ 
             echo '<link rel="canonical" href="https://www.red.ua'.$_SERVER['REQUEST_URI'].'" />';
        // }
         } ?>
        <?php if($this->get->page){
          // echo '<link rel="canonical" href="https://www.red.ua'.$this->g_url.'" />';
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
		<link rel="alternate" hreflang="ru-UA" href="https://www.red.ua<?=$ru?>" >
		<link rel="alternate" hreflang="uk-UA" href="https://www.red.ua<?=$uk?>" >
		<link  rel="shortcut icon" href="/favicon.ico">

                <link rel="stylesheet"  href="/css/bs/css/bootstrap.min.css?v=1.5"  >
               <!-- <link rel="stylesheet"  href="/css/Ionicons/css/ionicons.css">-->
               <link rel="stylesheet"  href="/css/ionicons/3.0/css/ionicons.min.css">
                    
                <link rel="stylesheet"  href="/css/style.css?v=1.4.2" media="(min-width: 768px)" >
		<link rel="stylesheet"  href="/css/style_new.css?v=1.4.9">
                <?php if(!$desctop){ ?><link rel="stylesheet"  href="/css/mobi/common_site.css?v=1.4.18"><?php }?>
		<?php
                if($this->css){
                    foreach ($this->css as $css) { ?>
                <link  rel="stylesheet"  href="<?=$css?>?v=1.1.88"   >
                    <?php }
                }
                ?>
        
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5DFS2PQ');</script>
<!-- End Google Tag Manager -->
</head>
<body>
  
<!--<script src="/js/jquery-3.4.1.min.js"></script>-->
<script src="/js/jquery.js"></script>
<script  src="/js/timer.js?v=1.6"></script>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DFS2PQ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php  if($this->user->id == 8005){ ?>

       <?php // echo $this->render('poll/index.tpl.php');
     // l($this->get);
  } ?>
<div id="simple_overlay_back" class="simple_overlay_back"></div>
<header>
<?php if($desctop){
echo $this->cached_top_menu;  
echo $this->cached_topcategories;
}else{
 echo $this->cached_mobi_menu;
} ?>
</header>
<?php if(isset($_COOKIE['track']) and $_COOKIE['track'] == 'globus_shop'){
echo '<div style="position: fixed;top: 70px;left: 15px;">'
    . '<a class="btn btn-danger" href="/advertising/">Реклама</a>'
        . '</div>';
}?>
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
<h1 itemprop="name"  class="h1 text-dark font-size-100 <?php if($this->getCurMenu()->getUrl() == 'product'){echo 'd-none';}?>">
     <?php   if ($this->getCurMenu()->getName()){
	 echo ucfirst($this->getCurMenu()->getName()); 
}elseif($this->getCurMenu()->getUrl() == 'search'){
 echo $this->getCurMenu()->getTitle();
 }elseif ($this->getBlog()){
 echo $this->getCurMenu()->getTitle();
 }elseif($this->getOnepostblog()){
echo '<h1 itemprop="name" class="text-dark font-size-100">'.$this->onepostblog[0]->post_name.'</h1>';
 }?>
</h1>
         </div>
</div>

    <!--
<div class="row column-1 d-none d-md-block d-lg-block d-xl-block">
<div class="col-lg-12 text-center">
</div>
</div>	-->	
<div class="row column-2 <?=$this->getCategory()->id?>">
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


<script src="/js/functions.js?v=4.3"></script>
<?php
    if($this->scripts){
        foreach ($this->scripts as $scripts) { ?>
            <script  src="<?=$scripts?>?v=1.1.3"></script>
        <?php }
    }
?>              
            <?php if($desctop){ ?>
            <script  src="/css/bs/js/bootstrap.js?v=1.5"></script><?php 
            }else{ ?>
            <script async src="/css/bs/js/bootstrap.min.js?v=1.5"></script>
            <?php } ?>
            <script async src="/css/bs/js/bootstrap.bundle.min.js?v=1.0"></script>
	<script>
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
   <!-- footer--><?=$this->cached_bottom_menu?><!-- exit footer-->
<?php // if($this->user->id == 8005){ l($_SESSION['poll']); } ?>
</body>
</html>