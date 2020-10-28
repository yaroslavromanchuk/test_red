<?php 
$history = $this->history?$this->history:false;
if ($history){ ?>
<div class="col-sm-12 mx-auto w-100 history_block" style="display: none">
<div class="block-title py-4 w-100">
	<div class="vc_separator    vc_sep_pos_align_center vc_sep_color_black double-bordered-thick ">
	<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
	<h4><?=$this->trans->get('Вы недавно смотрели')?><span><?=$this->trans->get('успейте купить пока есть в наличии')?></span></h4>
	<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
	</div>
	</div>
<div class="top_articles col-md-12 px-0"> 
<?php
foreach ($history as $block) {
if ($block->getId()) {
     echo  $block->getItemBlockCachedHtml(false);
}
  } ?>         
</div>
</div>

<link rel="stylesheet" href="/js/slider-fhd/slick.css?v=1.2.0">
<script  src="/js/slider-fhd/slick.min.js?v=1.3" ></script>
<script>
    $(document).ready(function(){ 
	   $('.top_articles').slick({
	prevArrow: '<img src="#" style="background-image:url(/img/slider/p-n-b.png);" data-role="none" class="slick-prev-next prev" aria-label="Previous" tabindex="0" role="button">',
nextArrow: '<img src="#" style="background-image:url(/img/slider/p-n-b.png);"  data-role="none" class="slick-prev-next next" aria-label="Next" tabindex="0" role="button">',
      slidesToShow: 5,
	  responsive: [ { breakpoint: 700, settings: { slidesToShow: 3 } },
	  { breakpoint: 480, settings: { slidesToShow: 1 } }],
	  autoplaySpeed: 3000,
	  speed: 500,
	  easing: 'fade',
	  autoplay: true
	  });  
          $('.history_block').slideDown("slow");
});
</script>
<?php } ?>