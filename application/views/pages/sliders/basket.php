<?php 
$price_basket_hist = $this->trans->get('Цена');
					$mas_b = [];
					foreach ($_SESSION['basket'] as $k => $v){
					$mas_b[] = $v['article_id'];
					}
					$mass = [];
if($this->history){ ?>
<div class="block-title py-4 w-100">
	<div class="vc_separator    vc_sep_pos_align_center vc_sep_color_black double-bordered-thick ">
	<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
	<h4><?=$this->trans->get('Вы недавно смотрели');?><span><?=$this->trans->get('успейте купить пока есть в наличии')?></span></h4>
	<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
	</div>
</div>
<div  class=" top_articles  col-md-12 px-0"> 
                    <?php
					$i=0; 
					foreach ($this->history as $v) {
					if(in_array($v->id, $mas_b)) continue;

					?>
			<div class="top_articles_item col-xs-12 col-sm-6 col-md-3" >
         <a  href="<?=$v->getPath()?>" style="text-align: center;">
        <img  src="<?=$v->getImagePath('detail')?>" alt="<?=$v->getBrand()?>" style="max-width:100%;"  >  
		</a>
				<div class="post-name" >
				<h2><a href="<?=$v->getPath()?>"><?=$v->getModel()?></a></h2>
				<h2><a href="<?=$v->getPath()?>"><?=$v->getBrand()?></a></h2>
				</div>
				<hr>
				<p><?=$v->getPrice()?> грн</p>
     </div>
                    <?php 
					$i++;
                    if($i == 15){ break;}
					} ?>
					</div>
 
 <?php
}elseif(count($_SESSION['hist']) > 0){
foreach ($_SESSION['hist'] as $v) {
if(!in_array($v, $mas_b)){$mass[] = $v;}
}
$mass = array_unique($mass);
krsort($mass);


if(count($mass) > 0){ ?>
<div class="block-title py-4 w-100">
	<div class="vc_separator    vc_sep_pos_align_center vc_sep_color_black double-bordered-thick ">
	<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
	<h4><?=$this->trans->get('Вы недавно смотрели')?><span><?=$this->trans->get('успейте купить пока есть в наличии')?></span></h4>
	<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
	</div>
</div>
<div  class=" top_articles col-sm-12  col-md-12 col-lg-12 col-xl-12 px-0"> 
                    <?php
					$i=0; 
					foreach ($mass as $v) {
					$block = wsActiverecord::useStatic('Shoparticles')->findById($v); 
						if($block and $block->getStock() > 0){
					?>
			<div class="top_articles_item col-sm-12 col-md-12 col-xs-12  " >
         <a  href="<?=$block->getPath();?>" style="    text-align: center;">
        <img  src="<?=$block->getImagePath('detail')?>" alt="<?=$block->getBrand()?>" style="max-width:100%;"  >  
		</a>
				<div class="post-name" >
				<h2><a href="<?=$block->getPath()?>"><?=$block->getModel()?></a></h2>
				<h2><a href="<?=$block->getPath()?>"><?=$block->getBrand()?></a></h2>
				</div>
				<hr>
				<p><?=$block->getPrice()?> грн</p>
     </div>
                    <?php 
					$i++;
					}
                    if($i == 15) {break;}
					} ?>
					</div>

					<?php }

					}?>
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
</script>