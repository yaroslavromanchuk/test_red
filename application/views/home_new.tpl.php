<!DOCTYPE html>
<html id="html" style="overflow:  hidden;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="alternate" hreflang="ru" href="http://www.red.ua/" />
    <meta name="description" content="<?php echo htmlspecialchars($this->getCurMenu()->getMetatagDescription()); ?>"/>
    <meta name="keywords" content="<?php echo htmlspecialchars($this->getCurMenu()->getMetatagKeywords()); ?>"/>
	 <meta property="og:image" content="http://www.red.ua/img/logo/lg.png"/>
    <title><?=Config::findByCode('home_title')->getValue();?></title>

	<link rel="stylesheet" type="text/css" href="/css/style.css?v=14082017"/>
	<link rel="stylesheet" type="text/css" href="/css/new.css?v=20131227x"/>
	<script  type="text/javascript" src="/js/jquery.js"></script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5DFS2PQ');</script>
<!-- End Google Tag Manager -->
</head>

<body style="display: none;">
<div class="content-box" style="width: 98%; height: 98%;padding: 1%;text-align: center; ">
<?php echo $this->render('finder/list.fhd.tpl.php'); ?>
</div>
<div style="width: 100%; height: 100%; display: none;" id="div_video">
<video id="video" src="" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' style="width:100%"></video>
</div>
<?php if($this->ws->getCustomer()->getId() == 8005){ echo '<a href="/" align="center" style="position: fixed;bottom: 20px;right: 22px;"><img src="/img/logo/lg.png" style="width:155px;"></a>';} ?>
<script >
    /* <![CDATA[ */
    var google_conversion_id = 1005381332;
    var google_conversion_label = "1vqdCJyg5gMQ1M2z3wM";
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>

<script defer type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt=""
             src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1005381332/?value=0&amp;label=1vqdCJyg5gMQ1M2z3wM&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>
<!-- Google Tag Manager (noscript) -->
<noscript>
<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DFS2PQ"
height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<script  type="text/javascript">
//var mas = ['1','2','3'];
var i = 0;

$(document).ready(function(){
 //$("body").css("display", "none");
$("body").fadeIn(1000);
var video = $("#video");
video.bind("ended", function() {
$('#div_video').fadeOut();
$(".content-box").html('<img src="/img/logo/logo-fhd-red.png" style="width: 1024px;margin-top: 250px;">');
$(".content-box").fadeIn(1000); 
showArticles();

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
	 $('#video').attr('src', '/img/video/'+rand+'.mp4');
		$('#video').trigger('play');
		$(".content-box").fadeOut(); 
	 	$(".content-box").html('');
	 // $('#div_video').fadeIn(2000);
		return true;
    }
function showArticles(){

$.ajax({  
            url: "/new_homepage_new/id/1",   
            success: function(data){	
                $(".content-box").html(data); 
				$('#video').trigger('stop');
            }			
        });
		
				return true;
}

  /* $('.single-item').slick();
      $('.slick-slider').on('afterChange', function(event, slick, currentSlide){
  if (currentSlide == 2) { console.log('Осуществлён переход к 3му слайду');
  show();
  }
});

$("body").css("display", "none");
$("body").fadeIn(2000);

 $("a").click(function(event){
  event.preventDefault();
  linkLocation = this.href;
  $("body").fadeOut(1000, redirectPage);
 });
 
 function redirectPage() {
  window.location = linkLocation;
 }

});
/*
function show()  
    { 
        $.ajax({  
            url: "/new_homepage_new/id/1",  
            cache: false,  
            success: function(data){
console.log('tut');		
	//console.log(data);	
                $(".content-box").html(data);  
            }			
        });  
		return false;
    }*/
</script>
</body>
</html>