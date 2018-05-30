<?php defined('EXEC') or die; ?>
<?php
$id=$_GET["id"];
//$id=32;
$articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery('
SELECT * 
FROM  `ws_articles` 
WHERE  `category_id` ='.$id.'
AND  `stock` >0
AND  `active` =  "y"
ORDER BY  `ws_articles`.`data_new` DESC 
');
$count=$articles->count(); 
//echo $articles[0]->image;
//echo $count;

$i=0;
?>
<div class="row">
	<div class="col-md-12">
		<div class="banner banner-top">
			<div class='banner-default'>
				
					<h3><?php echo $page_name;?></h3>
									
				
			</div>
			<?php 
  			if (isset($_SESSION['Message'])) {
    			echo "<div class='banner-temporary'>";
    			echo "<h3>{$_SESSION['Message']}</h3>";
          echo "</div>";
    			unset($_SESSION['Message']);
  			}
  		?>
		</div>
		<hr>
	</div>
</div>

<div class="row categories-navigation">
	<div class="col-md-12">
		<?php
		for($i=0; $i<$count; $i++)
		{
			$articles[$i]->image=substr($articles[$i]->image,0,-4);
		?> 
						<div class="row">
				<?php if($i==$count)break;?>
				<div class="col-xs-5" style="width:100%;">
				<a class="category" href="<?php echo'?page=shop&id='.$sub_cats[$i]->id;?>">
					<div class="category-logo">
					<img width="280px" height="280px" src="/files/360_360/<?php echo $articles[$i]->image;?>_w360_h360_cf_ft_fc255_255_255.jpg">
					</div>
					<br>
					<p class="category-header"><?php echo $articles[$i]->brand;?></p>
					<p class="category-header"><?php echo $articles[$i]->model;?></p>
				</a>
				</div>
				</div>
		
		<?php }
		
		?>

</div>
</div>
