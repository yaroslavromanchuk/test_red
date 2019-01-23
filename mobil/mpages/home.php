<!--новый банер-->
<div class="row">
	<div class="slider-baner col-md-12 col-xl-12 px-0" >
            <div id="moby_slider" class="carousel slide" data-ride="carousel" data-keyboard="true">
  <div class="carousel-inner">
           <?php $i=0;  foreach ($this->block6 as $block) { ?>
			<div class="carousel-item <?=$i==0?'active':''?>"  >
				<a class="img" href="<?=$block->getUrl()?>">
					<img  class="d-block w-100" src="<?=$block->getImage()?>" alt="<?=$block->getName()?>" onclick="dataLayer.push({'event': 'banner'});" />
				</a>
			</div>
<?php $i++; }?>
  </div>
  <a class="carousel-control-prev" href="#moby_slider" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#moby_slider" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
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
			<div class="col-xs-12 w-50">
                            <a class="category" href="<?=$c->getPath()?>">
					<div class="category-logo" style="background-image: url('<?=$c->getLogo()?>');" > </div>
					<p class="category-header"><?=$c->getName()?></p>
				</a>
			</div>
	<?php $i++; } ?>
	</div>
	</div>
</div>
<?php if(/*$this->ws->getCustomer()->getId() == 8005*/ true){ ?>
<div class="row m-auto1 bg-dark">
    <div class="col-sm-12 col-md-12 col-lg-2 col-xl-2 text-center p-2">
        <a href="https://t.me/shop_red_ua" target="_blank" class="text-white">
            <img class="w-50" src="/img/social_black/telega.png" alt="telegram">
        </a>
    </div>
</div>
<?php } ?>
<script>
    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments)};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
rnt('add_event', {advId: '20676', pageType:'home'});
rnt('add_audience', {audienceId: '20676_254951d7-6d13-4ea2-a507-747c9e6fe802'});
    //<!-- EVENTS FINISH -->
</script>