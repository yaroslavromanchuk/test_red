<?php defined('EXEC') or die; ?>
<!--новый банер-->
<div class="row">
	<div class="slider-baner col-md-12 col-xl-12 px-0" >
     <?php   foreach ($this->block6 as $block) { ?>
			<div class="item" style="text-align:center;"  >
				<a class="img" href="<?=$block->getUrl()?>">
					<img  class="w-100" src="<?=$block->getImage()?>" alt="<?=$block->getName()?>" onclick="dataLayer.push({'event': 'banner'});" />
				</a>
			</div>
<?php }?>
	</div>
</div>
<!--/ новый банер-->
<!--для акции-->
<?php
$cats = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1, 'parent_id'=>0), array(), array('sequence'=>'ASC'));
$exit = $cats->count();
$i = 0;
?>
<div class="row categories-navigation">
	<div class="col-md-12">
	<?php
		for($i=0; $i<$exit; $i++){
		?>
		<div class="row">
		<?php if($i==$exit)break;?>
			<div class="col-xs-12" style="width:50%;">
				<a class="category" href="<?php if($cats[$i]->id==106 ||$cats[$i]->id==146 ||$cats[$i]->id==12 || $cats[$i]->id==248 || $cats[$i]->id==11){echo $cats[$i]->getPath();}else if($cats[$i]->id==267){ echo '/category/id/';}else{echo'?page=m_g&id='.$cats[$i]->id;} ?>">
					<div class="category-logo" style="background-image: url('<?php echo $cats[$i]->getLogo();?>');" > </div>
					<p class="category-header"><?=$cats[$i]->getName();?></p>
				</a>
			</div>
		<?php $i++; if($i==$exit)break;?>
			<div class="col-xs-4" style="width:50%;">
				<a class="category" href="<?php  if($cats[$i]->id==106 || $cats[$i]->id== 146 || $cats[$i]->id == 12 || $cats[$i]->id==248 || $cats[$i]->id==11){echo $cats[$i]->getPath();}else if($cats[$i]->id==267){ echo '/category/id/';}else{echo '?page=m_g&id='.$cats[$i]->id;}?>">
					<div class="category-logo" style="background-image: url('<?=$cats[$i]->getLogo();?>');" ></div>
					<p class="category-header"><?=$cats[$i]->getName();?></p>
				</a>
			</div>
		</div>
	<?php } ?>
	</div>
</div>
</div>
<script>
$(document).ready(function(){
   $('.slider-baner').slick({ accessibility: true, adaptiveHeight: true, arrows: false, autoplay: true, autoplaySpeed: 3000, draggable: true, easing: 'fade', fade: true, speed: 1000, dots: true });
});
</script>
<script>
    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments)};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
rnt('add_event', {advId: '20676', pageType:'home'});
rnt('add_audience', {audienceId: '20676_254951d7-6d13-4ea2-a507-747c9e6fe802'});
    //<!-- EVENTS FINISH -->
</script>