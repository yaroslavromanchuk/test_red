<?php
$articles_query1 = '
SELECT * FROM ws_articles WHERE
ws_articles.`stock` not like "0"
AND  (ws_articles.`model` =  "'.$this->getShopItem()->getModel().'" or ws_articles.`model_uk` =  "'.$this->getShopItem()->getModel().'")
AND  ws_articles.`category_id` ='.$this->getShopItem()->getCategoryId().'
AND   ws_articles.id != '.$this->getShopItem()->getId().'
and ws_articles.status = 3
ORDER BY  `ws_articles`.`ctime` ASC 
LIMIT 10';
$finish_articles1 = wsActiveRecord::useStatic('Shoparticles')->findByQuery($articles_query1);
    if ($finish_articles1->count() > 5 ) { ?>
<div class="col-md-12 mx-auto w-100">
<div class="block-title py-4 w-100">
	<div class="vc_separator    vc_sep_pos_align_center vc_sep_color_black double-bordered-thick ">
	<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
	<h4><?=$this->trans->get('Мы рекомендуем');?><span><?=$this->trans->get('похожие товары с модельного ряда')?></span></h4>
	<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
	</div>
	</div>
<div class="top_articles col-md-12 px-0"> 
<?php
foreach ($finish_articles1 as $block) {
if ($block->getId()) {
?>
		<div class="top_articles_item col-xs-12 col-sm-6 col-md-3" >
        <a  href="<?=$block->getPath();?>" style="    text-align: center;">
        <img  src="<?=$block->getImagePath('detail')?>" alt="<?=$block->getBrand();?>" style="max-width:100%;"  >  
		</a>
				<div class="post-name" >
				<h2><a href="<?=$block->getPath()?>"><?=$block->getModel();?></a></h2>
				<h2><a href="<?=$block->getPath()?>"><?=$block->getBrand();?></a></h2>
				</div>
				<hr>
				<p><?=$block->getPrice()?> грн</p>
     </div>
<?php } } ?>         
</div>
</div>
<?php } ?>
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
});

    
    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments);};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
rnt('add_audience', {audienceId: '20676_254951d7-6d13-4ea2-a507-747c9e6fe802', priceId: '3047', productId: '<?=$this->getShopItem()->getId()?>'});
rnt('add_product_event', {advId: '20676', priceId: '3047', productId: '<?=$this->getShopItem()->getId()?>'});
    //<!-- EVENTS FINISH -->
</script>