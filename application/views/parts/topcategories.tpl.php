<div class="menu-2-box">
	<div class="menu-2">
<?php
		$cats = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id' => 0, 'active' => 1));
		$k = $cats->count();
		//$i = 0;
		if($k == 7){$pading = 'padding: 0px 14px;';}
		if($k == 8){$pading = 'padding: 0px 12px;';}
		if($k == 9){$pading = 'padding: 0px 10px;';}
		if($k == 10){$pading = 'padding: 0px 7px;';}
		if($k == 11){$pading = 'padding: 0px 5px;';}
		foreach ($cats as $category) {
			//$i++;
			
			switch ($category->getId()) {
				case 106:
				$style = 'style="color: white;width: 69px;font-weight: bold;background-size: contain;background-repeat: no-repeat;background-image: url(/img/category/new.png);padding-left: 4px;padding-right: 5px;"';
					break;
				case 85:
				$style = 'style="font-weight: bold;color: red;'.$pading.'"';
					$pading = 'padding-left: 20px';
					break;
				case 267:
				$style = 'style="'.$pading.'"';
					break;
			default: $style = 'style="'.$pading.'"';
			break;
			}
			$sub_cats = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id' => $category->getId(), 'active' => 1));
			
			/*if($category->getId() == 106){//new
			echo '<a href="'.$category->getPath().'" '.$style.' ><p>'.$category->getName().'</p></a>';
			}else if($category->getId() == 85){//rozprodaga
			echo '<a href="'.$category->getPath().'" '.$style.'><p>'.$category->getName().'</p></a>';
			}else */
			if($category->getId() == 267){// vse tovary
			echo '<a href="/category/id/" '.$style.' ><p>'.$category->getName().'</p></a>';
			}else{
			echo '<a href="'.$category->getPath().'"'.$style.'><p>'.$category->getName().'</p></a>';
			}
			if ($sub_cats->count()) {
?>
		<div class="hidden html">
			<div class="submenu-box">
<?php
				echo '<div class="block">'; //перенесенно с фора
				foreach ($sub_cats as $sub_category) {
					if ($category->getId() != 85 and $category->getId() != 11 and $category->getId() != 12) {
						$arr = $sub_category->getKidsIds();
						$arr[] = $sub_category->getId();
						$where = '
							FROM
								ws_articles_sizes
								JOIN ws_articles
								ON ws_articles_sizes.id_article = ws_articles.id
							WHERE
								ws_articles_sizes.count > 0
								AND category_id in('.implode(',', $arr).')
						';
						$articles_query = '
							SELECT
								count(ws_articles.id) as counti
							'.$where;
						$articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($articles_query);
						if ($articles->at(0) and !$articles->at(0)->getCounti()) continue;
					}
					//if ($sub_category->getId() == 266 or $sub_category->getId() == 265 ) {
				//$color = ' style="color:red;"';
			//}
					echo '<a href="' . $sub_category->getPath() . '"><p >' . $sub_category->getName() . '</p></a>';
				}
				echo '</div>'; //перенесенно с фора
?>
				<div class="clear"></div>
			</div>
		</div>
<?php
			}
			else if ($category->getId() == '106') { //show articles from last 10 days
				$sub_cats = wsActiveRecord::useStatic('Shopcategories')->findByQuery('
					SELECT
						*
					FROM
						ws_categories
					WHERE
						id IN (
							SELECT
								DISTINCT(`category_id`)
							FROM
								ws_articles
							WHERE
								data_new > DATE_ADD(NOW(), INTERVAL -6 DAY)
						)
						AND active = 1
				');
?>
		<div class="hidden html">
			<div class="submenu-box">
<?php
				echo '<div class="block">';
				foreach ($sub_cats as $sub_category) {
					$parent = wsActiveRecord::useStatic('Shopcategories')->findFirst(array('id' => $sub_category->getParentId()));
					if ($parent) {
						if ($parent->getParentId() == '0') {
							echo '<a href="' . $sub_category->getPath() . '"><p>' . $parent->getName() . ' : ' . $sub_category->getName() . '</p></a>';
						}
						else {
							$parent2 = wsActiveRecord::useStatic('Shopcategories')->findFirst(array('id' => $parent->getParentId()));
							if ($parent2 and $parent2->getParentId() == '0') {
								echo '<a href="' . $sub_category->getPath() . '"><p>' . $parent2->getName() . ' : ' . $parent->getName() . ' : ' . $sub_category->getName() . '</p></a>';
							}
						}
					}
				}
				echo '</div>';
?>
				<div class="clear"></div>
			</div>
		</div>
<?php
			}
		}
	$array = array();
	$where = '
		FROM
			ws_articles_sizes
			JOIN ws_articles
			ON ws_articles_sizes.id_article = ws_articles.id
		WHERE
			ws_articles_sizes.count > 0
			AND ws_articles.active = "y"
			AND brand_id > 0
	';

	$brands = '
		SELECT
			distinct(brand_id),
			brand,
			count(ws_articles.id) as cnt
		'.$where;
	$brands = wsActiveRecord::useStatic('Shoparticles')->findByQuery($brands.'
		GROUP BY
			brand_id
		ORDER BY
			cnt DESC
		LIMIT 12
	');
	$i = 0;
	foreach ($brands as $brand) {
		$array[] = array(
			'id' => $brand->getBrandId(),
			'name' => $brand->getBrand(),
			'cnt' => $brand->getCnt()
		);
	}
	$brands = $array;
	if (count($brands)) {
?>
		<a href="/brands/">
			<p>
				Бренды
			</p>
		</a>
		<div class="hidden html">
			<div class="submenu-box">
				<div class="block">
<?php
					$j = 0;
					foreach ($brands as $brand) {
						if ((int)$brand['id']   and !in_array(@$brand['name'], array('<>', '1', 'Italia', 'Made in Germany'))) {
							$j++;
?>
							<a href="/category/brands/<?php echo (int)@$brand['id']; ?>"
							   <?php if ($j > 12){ ?>class="brand_hide"<?php } ?>>
								<p><?php echo @$brand['name']; ?></p></a>
							<?php if ($j == 12) { ?>
								<a href="/brands/" class="brand_show_all">
									<p><?php echo $this->trans->get('Показать все');?></p></a>
							<?php } ?>
<?php
						}
					} ?>
					<div class="line-1"></div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
<?php
	}
?>

<form method="get" id="new_search_box" action="/search/">
	<div class="new_search">
		<input type="text" class="form-control searsh"  name="s" id="s" placeholder="Поиск" pattern="^[A-Za-zА-Яа-яЁё ,.-_&іїІЇь]+$"   value=""/>
		<input type="submit" value="Искать" style="display: none;"/>
	</div>
</form>

	</div>
</div>
		