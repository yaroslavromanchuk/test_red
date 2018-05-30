	<script  type="text/javascript" src="/js/jquery.js"></script>
<link href="/js/slider-fhd/slick.css" rel="stylesheet" type="text/css" />
<link href="/js/slider-fhd/slick-theme.css" rel="stylesheet" type="text/css" />
<script   src="/js/slider-fhd/slick.js" type="text/javascript"></script>
<div class="single-item">
	<?php 
	if(@Home::newActiveArticles()) {
        foreach (Home::newActiveArticles() as $block) {?>
			<div class="item">
			<?php
foreach($block as $article)
{
    $article->getSpecNakl();
    echo $article->getSmallBlockCachedHtml(true, true);
}
?>		
			</div>
<?php } 
}?>
</div>
<script>
$(document).ready(function(){
   $('.single-item').slick({
   accessibility: true,
adaptiveHeight: true,
arrows: true,
prevArrow: '<img src=""  data-role="none" class="slick-prev-next prev" aria-label="Previous" tabindex="0" role="button">',
nextArrow: '<img src=""  data-role="none" class="slick-prev-next next" aria-label="Next" tabindex="0" role="button">',
autoplay: true,
autoplaySpeed: 7000,
draggable: true,
easing: 'ease',
fade: true,
speed: 1500}
   );
      $('.slick-slider').on('beforeChange', function(event, slick, currentSlide){
  if (currentSlide == 32) {
if(i == 6){
i=1;
}else{
i++;
}

  console.log('Осуществлён переход к '+currentSlide+'слайду'+i);
  showVideo(i);
  
		//$('#video').trigger('play');
	  $('#div_video').fadeIn(2000);
  }
});
});
</script>