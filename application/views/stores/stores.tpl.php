<!DOCTYPE html >
<html lang="<?=Registry::get('lang')?>">
<head>
<?php //clearstatcache(); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="description" content="<?=htmlspecialchars($this->getCurMenu()->getMetatagDescription()); ?>"/>
    <meta name="keywords" content="<?=htmlspecialchars($this->getCurMenu()->getMetatagKeywords()); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<meta name="google-site-verification" content="5KsgGP4-JCTjV0dafIfi5_AI73MIryFuGqLvAgIthAI" />
        <meta name="robots" content="noindex, follow"/>
    <title><?=htmlspecialchars($this->getCurMenu()->getTitle())?></title>
	<link rel="alternate" hreflang="ru-UA" href="https://www.red.ua/ru<?=$_SERVER['REQUEST_URI']?>" />
		<link rel="alternate" hreflang="uk-UA" href="https://www.red.ua/uk<?=$_SERVER['REQUEST_URI']?>" />
	<link rel="shortcut icon" href="/favicon.ico"/>	

    <link rel="stylesheet" type="text/css" href="/css/bs/css/bootstrap.css?v=1.0"/>
    <link rel="stylesheet" type="text/css" href="/application/views/stores/css/stores.css?v=1.1"/>
    <link rel="stylesheet" type="text/css" href="/css/stores/fm.revealator.jquery.min.css"/>

    <script src="/js/jquery.js"></script>
    <!-- <script src="/js/timer.js"></script>-->
	
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5DFS2PQ');</script>
<!-- End Google Tag Manager -->
</head>
<body>
     <?php
if($this->user->id == 8005){
  // echo '<pre>';
   // print_r($this->getCurMenu());
   //echo '</pre>';
}
 ?> 
 <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DFS2PQ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<header>
<?=$this->stores_header;?>
</header>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 py-3">
                    <h1 itemprop="name"  class="h1 text-dark font-size-100 text-center"><?=$this->getCurMenu()->getName()?></h1>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 p-0">
                        <?=$this->getContent()?>
                </div>
        
            </div>
        </div>
    </section>
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
 <script  src="/css/bs/js/bootstrap.js?v=1.0"></script>
  <script  src="/js/stores/fm.revealator.jquery.min.js?v=1.0"></script>
  <script src="https://rabota.ua/export/context/company.js"></script>
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
</body>
</html>
