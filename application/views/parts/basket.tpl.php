			<div class="basket-box">
				<?php
					$articles_count = 0;
					$articles_price = 0.0;
					if ($this->getBasket())
						foreach ($this->getBasket() as $item)
						{
							$articles_count += $item['count'];
							$articles_price += $item['price']*$item['count'];
					}
					if ($articles_count == 1)
						$word = "товар";
					elseif ($articles_count >= 2 && $articles_count <= 4)
						$word = "товара";
					else
						$word = $this->trans->get("товаров");
				?>            
                <div class="title" style="cursor: pointer;" onclick="window.location.href='/basket/'">КОРЗИНА</div>
                <?php if($articles_count) { ?>
                	<p><?php echo (int)$articles_count; ?> <?php echo $word; ?>, <nobr><?php echo Shoparticles::showPrice($articles_price); ?> грн</nobr></p>

					<form method="post" action="/basket/">
						<div class="basket-submit">
							<input type="submit" class="submit" value="Оформить заказ"/>
						</div>
					</form>
					<a href="/basket/" class="show-basket"></a>
                <?php } else { ?>
                	<p>Корзина пустая</p>
                <?php } ?>

				<?php if($articles_count) { ?>
                <div class="hidden html">
                    <div class="basket-bulka-box">
                        <h1>ТОВАРЫ - ПОЧТИ ВАШИ!</h1>
                        <div class="summa">Итого: <?php echo Shoparticles::showPrice($articles_price); ?> грн</div>
                        <div class="count">Кол.: <?php echo (int)$articles_count; ?></div>
                        <div class="clear"></div>
                        <?php foreach($this->getBasket() as $key => $item)
                        		if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId())
                        		{
                        			$cur_total = 0.00;
                        ?>                        
                        <div class="item">
                            <a href="<?php echo $article->getPath(); ?>" class="img"><img src="<?php echo $article->getImagePath('small_basket'); ?>" alt="<?php echo htmlspecialchars($article->getTitle()); ?>"/></a>
                            <a href="<?php echo $article->getPath(); ?>" class="name"><?php echo $article->getTitle(); ?></a>
                        </div>
							<?php } ?>
	
	
                        <div class="line-1"></div>
                        <form method="post" action="/basket/">
                            <div class="basket-submit">
                                <input type="submit" class="submit" value="Оформить заказ"/>
                            </div>
                        </form>
                        <a href="/basket/" class="edit-basket">Изменить</a>
                        <div class="clear"></div>
                    </div>
                </div>
                <?php } ?>
            </div>