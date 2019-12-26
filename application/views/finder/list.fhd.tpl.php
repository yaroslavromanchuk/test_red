<link href="/js/slider-fhd/slick.css" rel="stylesheet" type="text/css" />
<link href="/js/slider-fhd/slick-theme.css" rel="stylesheet" type="text/css" />
<div class="single-item">
	<?php 
         $mas=[30,69,70,77,80,113,142,144,147,148,32,39,40,73,78,107,149,36,57,58,62,60,68,67,53,273,274,275,276,277,281,282,283]; 
         shuffle($mas);
        foreach ($mas  as $b) {
            //AND DATE_FORMAT(ws_articles.ctime,"%Y-%m-%d") < DATE_ADD(NOW(), INTERVAL -5 DAY)
            $arr = wsActiveRecord::useStatic('Shoparticles')->findByQuery('
            SELECT ws_articles.*
        FROM ws_articles
        WHERE  ws_articles.active = "y"
        AND ws_articles.stock not like "0" 
	AND ws_articles.category_id = '.$b.'
        and ws_articles.status = 3
        ORDER BY RAND()  LIMIT 0, 8 ');
            if($arr->count() == 8){
            ?>
	<div class="item row pt-3 px-5 pb-5 m-auto">
            <?php
            foreach($arr as $article){
                echo $article->getItemBlockGlobusCachedHtml(); 
        }
?>		
</div>
    
            <?php } } ?>
</div>
<script   src="/js/slider-fhd/slick.js" ></script>
<script>
$(document).ready(function(){
   $('.single-item').slick({
   accessibility: true,
adaptiveHeight: true,
arrows: true,
prevArrow: '<img src="#"  style="background-image:url(/img/slider/p-n-b.png);" data-role="none" class="slick-prev-next prev" aria-label="Previous" tabindex="0" role="button">',
nextArrow: '<img src="#" style="background-image:url(/img/slider/p-n-b.png);"  data-role="none" class="slick-prev-next next" aria-label="Next" tabindex="0" role="button">',
autoplay: true,
autoplaySpeed: 3000,
draggable: true,
easing: 'linear',
fade: true,
speed: 500,
dots: false,
pauseOnHover: true,

}
   );

$('.slick-slider').on('beforeChange', function(event, slick, currentSlide){
  if (currentSlide == 10) {
if(i == 6){
i = 1;
}else{
i++;
}

  console.log('Осуществлён переход к '+currentSlide+' слайду '+i);
  showVideo(i);
  
		//$('#video').trigger('play');
    $('#div_video').fadeIn(100);
  }
});
});
</script>