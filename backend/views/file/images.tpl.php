<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>

	<p>
		<img src="<?php echo SITE_URL;?>/img/icons/upload-small.png" alt="Pagina toevoegen" width="24" height="24" />
		<a href="<?php echo $this->path;?>images/upload/">Загрузить изображение</a>&nbsp;
		<!--<img src="<?php echo SITE_URL;?>/img/icons/upload-small.png" alt="Pagina toevoegen" width="24" height="24" />
		<a href="<?php echo $this->path;?>images/upload-gallery/">Загрузить изображения для галереи</a>-->
	</p>

	<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th colspan="2">Действие</th>
			<th>URL изображения</th>
			<th>Страница</th>
		</tr>
	<?php 
		$row = 'row1';
		foreach($this->getImages() as $image )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon"><a href="<?php echo $image->getOpenUrl();?>" target="_blank"><img src="<?php echo SITE_URL;?>/img/icons/view-small.png" alt="Просмотр изображения" width="24" height="24" /></a></td>
			<td class="kolomicon"><a href="<?php echo $this->path;?>images/delete/id/<?php echo $image->getId();?>/" onclick="return confirm('Вы уверены, что хотите удалить изображение?')"><img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удалить изображение" width="24" height="24" /></a></td>
			<td class="kolomtitle"><?php echo $image->getOpenUrl();?></td>
			<td><?php echo ($image->getCategoryId() ? $image->getCategory()->getName() : '');?></td>			
		</tr>
	<?php
		}
	?>
    </table>