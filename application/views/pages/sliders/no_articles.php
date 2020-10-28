<?php
$similar = wsActiveRecord::useStatic('Shoparticles')->findAll(['category_id'=>$this->getCategory()->id, 'status'=>3, 'stock not like "0"'],['views'=>'ASC'], [0,12]);
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
<script>
    /*
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
          
          $('.similar_block').slideDown("slow");
});
*/
</script>
<?php } ?>
