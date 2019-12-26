<?php 
	if (isset($_GET['page']) or isset($_GET['cat'])) {
	if(isset($_GET['page'])){
	$page = $_GET['page'];
	}else{ $page = $_GET['cat'];}
	} else {
		$page = 'home';
	}
	//echo $page;
	if (!file_exists('mobil/mpages/' . $page . '.php')) { $page = 'error'; }
	
	$pagePath = 'mobil/mpages/' . $page . '.php';
	$blog = 'mobil/mpages/blog.php';
?>
<!DOCTYPE html>
<html id="html" lang="<?=Registry::get('lang')?>" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<link rel="shortcut icon" href="/favicon.ico"/>
	<title><?=Config::findByCode('home_title')->getValue()?></title>

	<link rel="stylesheet" type="text/css" href="/css/bs/css/bootstrap.min.css?v=1.0"/>
        <link rel="stylesheet" type="text/css" href="/css/Ionicons/css/ionicons.min.css"/>
		<!--<link rel="stylesheet" type="text/css" href="/css/style.css?v=1.3.31"/>-->
                <link rel="stylesheet" type="text/css" href="/css/style_new.css?v=1.4"/>
                <link rel="stylesheet" type="text/css" href="/css/mobi/common_home.css?v=1.6"/>
	
	<script  src="/mobil/mjs/jquery.min.js"></script>
	
<style>.back{display:none;}</style>
	<!-- Google Tag Manager -->
<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5DFS2PQ');
</script>
<!-- End Google Tag Manager -->
</head>
<body style="padding-top: 56px;">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DFS2PQ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
 <?=$this->cached_mobi_menu?>
	<!--главная центр меню-->
	<div class="container-fluid <?=$page?> ">
	<?php include $pagePath; ?>
	<!--/главная центр меню-->	
<?php include $blog; ?>	
<?=$this->cached_bottom_menu?>
<div align="center" style="padding-top:10px;">
<input type="button" name="mobi" class="btn btn-secondary btn-sm" onClick="setCooki(10);" value="<?=$this->trans->get('Полная версия сайта');?>"><br>
<span  style="    font-size: 10px;    display: inline-block;color: gray;">Раскрутка сайта — <a href="https://aweb.ua/">Aweb.ua</a></span>
</div>
        </div>
	<script  src="/mobil/mjs/jquery.mask.js" ></script>
	<script  src="/css/bs/js/bootstrap.min.js?v=1.0"></script>
    <script src="/mobil/mjs/common.js"></script>
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
