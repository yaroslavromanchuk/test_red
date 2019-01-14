<link href="/js/slider-fhd/slick.css" rel="stylesheet" type="text/css" />
<link href="/js/slider-fhd/slick-theme.css" rel="stylesheet" type="text/css" />
<div class="single-item">
	<?php 
         $mas=[30,69,70,77,80,113,142,144,147,148,32,39,40,73,78,107,149,36,57,58,62,60,68,67,53,273,274,275,276,277,281,282,283]; 
        foreach ($mas  as $b) {
            $arr = wsActiveRecord::useStatic('Shoparticles')->findByQuery('
            SELECT ws_articles.*
        FROM ws_articles
        WHERE  ws_articles.active = "y"
        AND ws_articles.stock not like "0" 
		AND ws_articles.category_id = '.$b.'
        AND DATE_FORMAT(ws_articles.ctime,"%Y-%m-%d") < DATE_ADD(NOW(), INTERVAL -5 DAY)
        and ws_articles.status = 3
        ORDER BY RAND()  LIMIT 0, 8 ');
            ?>
	<div class="item row p-2 m-auto">
            <?php
            foreach($arr as $article){ ?>
            
    <div class="article-item-fhd1 col-sm-12 col-md-6 col-lg-3 col-xl-3 float-left p-1 <?=$article->id?>" >
        <div class="bg-white p-1 text-center d-inline-block">
    <a href="<?=$article->getPath()?>" class="img">
        <img src="<?=$article->getImagePath('card_product')?>"  class="m-auto" style="height:  100%; max-height: 430px; " alt="<?=htmlspecialchars($article->getTitle())?>">
	</a>
            <p style="padding: 5px;text-align: left;margin: 2px;">
                <span  class="name" style="    text-transform: uppercase;font-size: 16px;" ><?=$article->getModel()?></span> -  <span class="brand" style="color: #828181;text-transform: uppercase;" ><?=$article->getBrand()?></span>
            </p>
    <p class="price" style="font-size: 18px;
    color: #000000;
    font-weight: bold;
    margin-bottom: 10px;
    padding: 0px 5px;
    text-align: right">
        <?php $pric = Number::formatFloat($article->getPriceSkidka(), 2); $pric = explode(',', $pric); echo $pric[0];?>
	<span style="font-size:11px;vertical-align: text-top;margin-left: -4px;"><?php echo (int)$pric[1] ? ','.$pric[1] : ''; //копейки ?></span>&nbsp;грн
	<?php if((float)$article->getOldPrice()){ ?>
	<span class="price-old" style="text-decoration: line-through;
    color: #e40413;
    font-weight: normal;
    font-size: 14px;
    margin-right: 5px;
    display: inline-block;"><?=$article->showPrice($article->getOldPrice())?>грн</span>
	<?php } ?>
	</p>
        </div>
</div>

          <?php 
              //$article->getSpecNakl();
   // echo $article->getBlockHtml();
          
        }
?>		
</div>
<?php } ?>
</div>
<script   src="/js/slider-fhd/slick.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
   $('.single-item').slick({
   accessibility: true,
adaptiveHeight: true,
arrows: true,
prevArrow: '<img src="#"  style="background-image:url(/img/slider/p-n-b.png);" data-role="none" class="slick-prev-next prev" aria-label="Previous" tabindex="0" role="button">',
nextArrow: '<img src="#" style="background-image:url(/img/slider/p-n-b.png);"  data-role="none" class="slick-prev-next next" aria-label="Next" tabindex="0" role="button">',
autoplay: true,
autoplaySpeed: 7000,
draggable: true,
easing: 'ease',
fade: true,
speed: 1200
}
   );

$('.slick-slider').on('beforeChange', function(event, slick, currentSlide){
  if (currentSlide == 32) {
if(i == 6){
i = 1;
}else{
i++;
}

  console.log('Осуществлён переход к '+currentSlide+'слайду'+i);
  showVideo(i);
  
		//$('#video').trigger('play');
    $('#div_video').fadeIn(1000);
  }
});
});
</script>