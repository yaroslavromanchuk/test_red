<?php 
header("Expires: " . date("r", time() + 7200));
define("EXEC", TRUE);
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
<html id="html" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<link rel="shortcut icon" href="mobil/mimages/favicon.ico"/>
	<title><?=Config::findByCode('home_title')->getValue();?></title>
	
	
	<link  href="/js/slider-fhd/slick.css" rel="stylesheet" type="text/css" />
	<link href="/js/slider-fhd/slick-theme.css" rel="stylesheet" type="text/css" />
	
	<script  src="mobil/mjs/jquery.min.js"></script>
	<script  src="mobil/mjs/jquery.mask.js" ></script>
	<script  src="/css/bs/js/bootstrap.js?v=1.5"></script>
    <script src="mobil/mjs/common.js"></script>
<script   src="/js/slider-fhd/slick.min.js" ></script>
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

		<link rel="stylesheet" type="text/css" href="/css/bs/css/bootstrap.min.css?v=1.0"/>
		<link rel="stylesheet" type="text/css" href="/css/style.css?v=1.0"/>
		<link rel="stylesheet" type="text/css" href="/css/common.css?v=1.0"/>
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
<body style="padding-top: 48px;">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DFS2PQ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
 <?php echo $this->cached_mobi_menu;?>
	<!--главная центр меню-->
	<div class="container-fluid <?=$page?> ">
	<?php include $pagePath; ?>
	<!--/главная центр меню-->	
<?php include $blog; ?>	
<?=$this->cached_mobi_futer?>
<div align="center" style="padding-top:10px;">
<input type="button" name="mobi" class="btn btn-secondary btn-sm" onClick="setCooki(10);" value="<?=$this->trans->get('Полная версия сайта');?>">
</div>
</body>
</html>