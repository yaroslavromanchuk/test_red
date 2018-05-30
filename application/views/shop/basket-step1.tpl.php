<h1 class="violet"><?php echo $this->getCurMenu()->getName();?></h1>
<?php if ($this->getBasket()) { ?>
<div id="steps">
	<p>
		<a class="prev-step" >ШАГ 1 - <?php echo $this->trans->get('Корзина');?></a>&nbsp;
		ШАГ 2 - <?php echo $this->trans->get('Контактные данные');?>&nbsp;
		ШАГ 3 - <?php echo $this->trans->get('Обзор заказа');?>&nbsp;
		ШАГ 4 - <?php echo $this->trans->get('Заказать');?>&nbsp;
	</p>
</div>
<?php $total = 0.0; ?>
<?php $bool = false; foreach ($this->getBasket() as $item_tmp)	if ($item_tmp['option_id'] > 0)	$bool = true; foreach($this->getBasket() as $key => $item) if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId()) { $cur_total = 0.00; ?>
<div class="article-cart">
	<table class="basket" style="width:100%;">
		<tr>
			<td width="160"><img src="<?php echo $article->getImagePath('listing'); ?>" alt="<?php echo htmlspecialchars($article->getTitle()); ?>" /></td>
			<td>
				<h4><?php echo $article->getTitle(); ?>, Размер: <?php echo wsActiveRecord::useStatic('Size')->findById($item['size'])->getSize()?>; Цвет: <?php echo wsActiveRecord::useStatic('Shoparticlescolor')->findById($item['color'])->getName()?></h4>
				
				<p><?php echo $article->getShortText(); ?> <a href="/product/id/<?=$article->getId()?>"><?php echo $this->trans->get('подробнее');?></a></p>
				<p>
					<a href="<?php echo wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step1-delete')->getPath()."point/{$key}"; ?>" onclick="return confirm('<?php echo $this->trans->get('Удалить товар из корзины?');?>')"><img class="remove-item" src="<?php echo SITE_URL; ?>/img/main/remove-item.gif" alt="удалить" width="20" height="20" /></a>
					<select name="select" class="number" onchange="document.location='<?php echo wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step1-change')->getPath()."point/{$key}/count/"; ?>'+this.value+'/';">
							<?php for ($i=1; $i<=wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article'=>$article->getId(),'id_size'=>$item['size'],'id_color'=>$item['color']))->getCount(); $i++) echo ($i != $item['count']) ? "<option value=\"{$i}\">{$i}</option>" : "<option value=\"{$i}\" selected=\"selected\">{$i}</option>"; ?>
					</select>
					<strong><?php echo $this->trans->get('Всего');?>: <?php $cur_total += $article->getPriceSkidka()*$item['count']; echo Shoparticles::showPrice($cur_total); ?> грн</strong>
				</p>
			</td>
			<td width="100">
				<div class="price-cart">
					<h4><?php echo Shoparticles::showPrice($article->getPriceSkidka()); ?> грн</h4>
				</div>
			</td>
		</tr>
	</table>
</div><!--end extra-article-->
<?php $total += $cur_total; } ?>
<p><!--end extra-article--></p>
<table id="total-price2" cellpadding="3" cellspacing="0" class="total-price2">
	<tr>
		<td><strong><?php echo $this->trans->get('Всего');?></strong></td>
		<td class="column-euro"><strong></strong></td>
		<td class="column-price"><strong><?php echo Shoparticles::showPrice($total); ?> грн</strong></td>
	</tr>
</table>
<a class="next-step" href="<?php echo wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step2')->getPath(); ?>">Перейти на ШАГ 2 - <?php echo $this->trans->get('Контактные данные');?></a>
<?php } else echo "<p>" . $this->trans->get('Корзина пуста') . "</p>"; ?>

<?php echo $this->getCurMenu()->getPageBody();?>
