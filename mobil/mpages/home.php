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
?>
<div class="row categories-navigation">
	<div class="col-md-12">
			<div class="row">
	<?php
	$i=0;
		foreach($cats as $c){
		if($i == 2 or $i == 4 or $i == 6 or $i == 8) echo '</div><div class="row">';
		?>
			<div class="col-xs-12" style="width:50%;">
				<a class="category" href="<?php if($c->id==106 ||$c->id==146 ||$c->id==12 || $c->id==248 || $c->id==11){echo $c->getPath();}else if($c->id==267){ echo '/category/id/';}else{echo'?page=m_g&id='.$c->id;} ?>">
					<div class="category-logo" style="background-image: url('<?php echo $c->getLogo();?>');" > </div>
					<p class="category-header"><?=$c->getName();?></p>
				</a>
			</div>
	<?php $i++; } ?>
	</div>
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