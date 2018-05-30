<h1 class="violet">о нас</h1>
<?php echo $this->getCurMenu()->getPageBody();?>
                <div class="line"></div>
                
                <h1 class="hit"><?php echo $this->trans->get('Новинки');?><span class="bg-right"></span><span class="star"></span></h1>
                <div class="items-line">
				<?php $step = 0; foreach ($this->getArticlesTop() as $articletop) { $article = $articletop->getArticle(); if ($article->getId() && strcasecmp($article->getActive(),'y') == 0) { $step++; ?>
               
                    <div class="item <?php if ($step % 3 == 0) echo "last "; ?>">
                        <a href="<?php echo wsActiveRecord::useStatic('Menu')->findByUrl('shop-item')->getPath()."id/{$article->getId()}"; ?>" class="image"><img src="<?php echo $article->getImagePath(2); ?>" alt="<?php echo htmlspecialchars($article->getTitle()); ?>"/></a>
                        <a href="<?php echo wsActiveRecord::useStatic('Menu')->findByUrl('shop-item')->getPath()."id/{$article->getId()}"; ?>" class="name"><?php echo $article->getTitle(); ?></a>
                        <span class="price"><?php echo Shoparticles::showPrice($article->getPriceSkidka());?> грн</span>
                    </div>
				<?php } } ?>
                </div>
                
                <div class="line"></div>

<!--div class="line-1"></div>
<h1 class="catalog">КАТАЛОГ ПРОДУКЦИИ</h1>
<ul class="catalog">
	<li>
	<?php
		$categories = wsActiveRecord::useStatic('Shopcategories')->findAll();
		$step = 0;
		$sep = $sep_value = ceil($categories->count() / 3);
		$li_counts = 2;
		foreach ($categories as $category) {
			echo "<a href=\"".wsActiveRecord::useStatic('Menu')->findByUrl('shop-category')->getPath()."id/{$category->getId()}\">{$category->getName()}</a>";
			$step++;
			$sep--;
			if ($sep <= 0 && $li_counts > 0) {
				echo "</li><li>";
				$sep = $sep_value;
				$li_counts--;
			}
		}
		while ($li_counts > 0) {
			echo "</li><li>";
			$li_counts--;
		}
	?>
	</li>
	<li class="clear"></li>
</ul-->