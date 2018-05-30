<script>
document.title = 'Заказ не был совершен';
$('.navik').html('<a href="/">Главная</a> : Заказ не был совершен');
</script>
<h2>Внимание!</h2>
<p style="font-size:14px;">Мы сожалеем, но данных позиции уже <span style="text-decoration:underline;">нет</span> в наличии:</p>
<table class="basket" align="center" cellpadding="0" cellspacing="0" style="width:70%;margin:0;">
	<tr class="b_header">
		<th></th>
		<th>цвет</th>
		<th>размер</th>
		<th></th>
	</tr>
	<?php foreach ($this->articles as $item) {?>
	<tr>
		<?php $article = wsActiveRecord::useStatic('Shoparticles')->findById($item['id']); ?>

		<td width="160" class="">
			<a href="<?php echo $article->getPath(); ?>"><img src="<?php echo $article->getImagePath('listing'); ?>" alt="<?php echo htmlspecialchars($article->getTitle()); ?>"/><?php echo $article->getTitle(); ?></a>
		</td>
		<td><?php echo wsActiveRecord::useStatic('Shoparticlescolor')->findById($item['color'])->getName()?></td>
		<td><?php echo wsActiveRecord::useStatic('Size')->findById($item['size'])->getSize()?> </td>
		<td>нет в наличии</td>
	</tr>
	<?php } ?>
</table>
<br>
<?php if(count($_SESSION['basket_articles'])) { ?>
<a class="next-step new_button" href="<?php echo wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step2')->getPath(); ?>?recalculate">Оформить без указаных едениц</a>
<a class="next-step new_button" href="/basket">Корзина</a>
<?php } else {?>
<p>Ваша корзина пуста</p><a class="next-step new_button" href="/">На главную</a>
<?php } ?>