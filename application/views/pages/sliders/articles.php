<?php
$similar = ($this->similar->count() > 5)?$this->similar:false;
    if ($similar){ ?>

<div class="col-sm-12 mx-auto w-100 similar_block ">
<div class="block-title py-4 w-100">
	<div class="vc_separator    vc_sep_pos_align_center vc_sep_color_black double-bordered-thick ">
	<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
	<h4><?=$this->trans->get('Мы рекомендуем');?><span><?=$this->trans->get('похожие товары с модельного ряда')?></span></h4>
	<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
	</div>
	</div>
<div class="top_articles row px-0"> 
<?php
foreach ($similar as $block) { 
if ($block->getId()) {
    echo  $block->getItemBlockCachedHtml(false);
    } 
} 
?>         
</div>
</div>
<link rel="stylesheet" href="/js/slider-fhd/slick.css?v=1.2.0">
<script  src="/js/slider-fhd/slick.min.js?v=1.3" ></script>
<script>
    
   $(window).load(function() {
	   $('.top_articles').slick({
	prevArrow: '<img src="#" style="background-image:url(/img/slider/p-n-b.png);" data-role="none" class="slick-prev-next prev" aria-label="Previous" tabindex="0" role="button">',
        nextArrow: '<img src="#" style="background-image:url(/img/slider/p-n-b.png);"  data-role="none" class="slick-prev-next next" aria-label="Next" tabindex="0" role="button">',
        slidesToShow: 5,
	  responsive: [ 
      { breakpoint: 700, settings: { slidesToShow: 3 } },
	  { breakpoint: 480, settings: { slidesToShow: 1 } }
      ],
	  autoplaySpeed: 3000,
	  speed: 500,
	  easing: 'fade',
	  autoplay: true
	  });
          
        //  $('.similar_block').slideDown("slow");
        //  $('.similar_block').fadeIn();
});

    
   // window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments);};
  //  rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
//rnt('add_audience', {audienceId: '20676_254951d7-6d13-4ea2-a507-747c9e6fe802', priceId: '3047', productId: '<?=$this->getShopItem()->getId()?>'});
//rnt('add_product_event', {advId: '20676', priceId: '3047', productId: '<?=$this->getShopItem()->getId()?>'});
    //<!-- EVENTS FINISH -->
</script>
<?php } ?>
