<!DOCTYPE html >
<html lang="<?=Registry::get('lang')?>" style="overflow:  hidden;">
<head>
<?php //clearstatcache(); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="description" content="<?=htmlspecialchars($this->getCurMenu()->getMetatagDescription()); ?>"/>
    <meta name="keywords" content="<?=htmlspecialchars($this->getCurMenu()->getMetatagKeywords()); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<meta name="google-site-verification" content="5KsgGP4-JCTjV0dafIfi5_AI73MIryFuGqLvAgIthAI" />
        <meta name="robots" content="noindex, nofollow"/>
    <title><?=htmlspecialchars($this->getCurMenu()->getTitle())?></title>
	<link rel="alternate" hreflang="ru-UA" href="https://www.red.ua/ru<?=$_SERVER['REQUEST_URI']?>" />
	<link rel="alternate" hreflang="uk-UA" href="https://www.red.ua/uk<?=$_SERVER['REQUEST_URI']?>" />
	<link rel="shortcut icon" href="/favicon.ico"/>	

    <link rel="stylesheet" type="text/css" href="/css/bs/css/bootstrap.css?v=1.0"/>
    <link rel="stylesheet" type="text/css" href="/css/stores/fm.revealator.jquery.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/style.css?v=14082017"/>
	<link rel="stylesheet" type="text/css" href="/css/new.css?v=20131227x"/>

    <script src="/js/jquery.js"></script>
	
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
<section>
<div class="container-fluid p-0">
<div class="row m-auto">
<div class="col-sm-12 content-box p-2 m-2 text-center" style="display: none;"><?=$this->render('finder/list.fhd.tpl.php')?></div>
<div class="col-sm-12 content-box-baner p-0 m-0 text-center" >
    <img src="/img/logo/logo-fhd-red_new.png" class="my-auto" style="width: 100%">
</div>
<div class="col-sm-12 p-0 m-0" id="div_video">
    <video id="video" src="" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' class="w-100" ></video>
</div>
            </div>
        </div>
    <div class="go_to_sit">На сайт</div>>
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
        
//var mas = ['1','2','3'];
var i = 0;

$(document).ready(function(){
    //$("body").fadeIn(1000);
    $('.content-box-baner').fadeOut(100);
    $('.content-box').fadeIn(1000);
 //$("body").css("display", "none");

var video = $("#video");
video.bind("ended", function() {
$('#div_video').fadeOut();
//$(".content-box-baner").html('<img src="/img/logo/logo-fhd-red.png" class="my-auto" style="width: 75%">');
$(".content-box-baner").fadeIn(100);
showArticles();
//$(".content-box").fadeIn(1000); 


//return false;
});
video.bind("loadeddata", function() {

}
// Действия после запуска
);

});
function showVideo(i){ 

//console.log(mas);
var rand = i;//Math.floor(Math.random() * (7 - 1)) + 1;
	 $('#video').attr('src', '/img/video/'+rand+'_1.mp4');
		$('#video').trigger('play');
		$(".content-box").fadeOut(); 
                 $(".content-box-baner").fadeOut();
	 	$(".content-box").html('');
	 // $('#div_video').fadeIn(2000);
		return true;
    }
function showArticles(){

$.ajax({  
            url: "/advertising/index/id/1/",   
            success: function(data){	
                $(".content-box").html(data).fadeIn(1000); 
		$('#video').trigger('stop');
            },
            complete: function (e) {
                $(".content-box-baner").fadeOut();
               // $(".content-box").fadeIn(1000); 
            }
        });
		
				return true;
}
    </script>
</body>
</html>
 <?php
if($this->user->id == 8005){
   //echo '<pre>';
   // print_r($this->getCurMenu());
   //print_r($_GET);
   //print_r($this->get);
   
  // echo '</pre>';
}
 ?>   