<?php
$sub_cats = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id' => (int)$this->get->id, 'active' => 1), array('name'=>'ASC'));	
?>
<div class="row">
	<div class="col-md-12">
		<div class="banner banner-top">
					<h3><?=$sub_cats[0]->getRoutezGolovna()?></h3>
		</div>
		<hr>
	</div>
</div>
<div class="row categories-navigation">
	<div class="col-md-12">
<div class="row">
	<?php
	$i=0;
		foreach($sub_cats as $c){
		$arr = $c->getKidsIds();
						$arr[] = $c->getId();
$articles = wsActiveRecord::useStatic('Shoparticles')->count(array('category_id in('.implode(",", $arr).')',' stock > 0'));
						if ($articles == 0 and $c->parent_id != 85) continue;
		if($i == 3 or $i == 6 or $i == 9 or $i == 12) echo '</div><hr><div class="row">'; ?> 
		<div class="col-xs-5 col-xs-offset-1" style="width: 28%;margin-left: 4%;">
				<a class="category" href="<?=$c->getPath()?>">
					<div class="category-logo" style="background-image: url('<?=$c->getLogo()?>');"></div>
					<br>
					<p class="category-header"><?=$c->getName()?></p> 
				</a>
				</div>
		<?php 
		$i++;

	} ?></div>	
</div>
</div>
</div>