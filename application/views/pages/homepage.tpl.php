<?php echo $this->getCurMenu()->getPageBody();?>
<!--
<ul class="content-list">
	<li>
	</li>
	<?php 
		$menus = wsActiveRecord::useStatic('Menu')->findAll(array('style' => '1'), array('sequence' => 'ASC'));
		foreach($menus as $menu) 
		{
	?>
	<li>
		<p class="title"><b>
			<?php echo $menu->getTitle();?></b>
		</p><br/>
		<?php echo $menu->getPageIntro();?>
		</br>
	</li>
	<?php } ?>
</ul>
-->