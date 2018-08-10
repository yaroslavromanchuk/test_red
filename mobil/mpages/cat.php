<?php defined('EXEC') or die; ?>
<?php
$pages =$_SERVER['HTTP_REFERER'];
$id=$_GET['id'];
$where='';
$page_name='';
switch($id){
case 11:
	$page_name='Спортивная Одежда';
	    $where = '`ws_categories`.`parent_id` IN (11)';
    break;
case 14:
	$page_name='Женская Одежда';
	$where = '  `ws_categories`.`parent_id` = 14';
    break;
case 15:
$page_name='Мужская Одежда';
    $where = '`ws_articles`.`category_id` =  `ws_categories`.`id` 
AND  `ws_categories`.`parent_id` 
IN (15)
AND  `ws_articles`.`stock` >0';
    break;
case 33:
$page_name='Обувь';
    $where = '`ws_articles`.`stock` >0
	AND `ws_categories`.`id` 
IN (35,56,67)';
    break;
case 81:
$page_name='Парфюмерия';
    $where = '`ws_articles`.`category_id` =  `ws_categories`.`id` 
AND  `ws_categories`.`parent_id` 
IN (81)
AND  `ws_articles`.`stock` >0';
    break;
case 54:
$page_name='Аксессуары';
    $where = '`ws_articles`.`category_id` =  `ws_categories`.`id` 
AND  `ws_categories`.`parent_id` 
IN (54)
AND  `ws_articles`.`stock` >0';
    break;	
case 59:
$page_name='Детям';
    $where = '`ws_articles`.`category_id` =  `ws_categories`.`id` 
AND  `ws_categories`.`parent_id` 
IN (59)';
    break;
case 85:
$page_name='Распродажа';
    $where = '`ws_categories`.`parent_id` 
IN (85)';
    break;
	case 299:
$page_name='BLACK FRIDAY';
    $where = '`ws_categories`.`parent_id` 
IN (299)';
    break;
}
$sub_cats = wsActiveRecord::useStatic('Shopcategories')->findByQuery('SELECT DISTINCT  `ws_categories`.* 
FROM  `ws_categories`  
join  `ws_articles` on  `ws_categories`.`id` = `ws_articles`.`category_id`
WHERE `ws_categories`.`parent_id` = '.$id.' AND  `ws_categories`.`active` = 1 AND  `ws_articles`.`stock` not like "0"
');
		$exit=$sub_cats->count();
		$i=0;
		//d($sub_cats, false);
?>
<div class="row">
	<div class="col-md-12">
		<div class="banner banner-top">
					<h3><?php //echo $page_name;?></h3>
		</div>
		<hr>
	</div>
</div>
<div class="row categories-navigation">
	<div class="col-md-12">

	<?php foreach($sub_cats as $c){ ?> 
		
		<div class="row">
				<?php if($i==$exit)break;?>
				<div class="col-xs-5 col-xs-offset-1" style="width: 28%;margin-left: 4%;">
				<a class="category" href="<?php echo $c->getPath();?>">
					<div class="category-logo" style="background-image: url('<?php echo $c->getLogo(); ?>');"></div>
					<br>
					<p class="category-header"><?php // echo $c->getName();?></p> 
				</a>
				</div>
				
				<?php $i++; if($i==$exit)break;?>
				<div class="col-xs-5 col-xs-offset-1" style="width: 28%;margin-left: 4%;">
				<a class="category" href="<?php echo $c->getPath();?>">
					<div class="category-logo" style="background-image: url('<?php echo $c->getLogo(); ?>');"></div>
					<br>
					<p class="category-header"><?php echo $c->getName();?></p> 
				</a>
				</div>
				<?php $i++; if($i==$exit)break;?>
				<div class="col-xs-5 col-xs-offset-1" style="width: 28%;margin-left: 4%;">
				<a class="category" href="<?php echo $c->getPath();?>">
					<div class="category-logo" style="background-image: url('<?php echo $c->getLogo(); ?>');"></div>
					<br>
					<p class="category-header"><?php echo $c->getName();?></p> 
				</a>
				</div>
				 <?php?>
			</div>	
			<hr>
			
		<?php 

	}
		?>
</div>
</div>
</div>