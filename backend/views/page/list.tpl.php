<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>

    <p><img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Pagina toevoegen" width="24" height="24" /><a href="<?php echo $this->path;?>pages/edit/"><?php echo $this->trans->get('Добавить страницу'); ?></a></p>
    <table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th colspan="5" align="justify"><?php echo $this->trans->get('Действия'); ?></th>
		  <th><?php echo $this->trans->get('Название страницы'); ?></th>
		</tr>
	<?php 
		$row = 'row1';
		$cur = -1;
		$count = $this->getPages()->count();		
		foreach($this->getPages() as $page )
		{
			$cur++;
			$is_first = (0 == $cur);
			$is_last = ($count == $cur + 1);		
			if($page->getUrl() == 'admin') continue;
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon"><a href="<?php echo $page->getPath();?>" target="_blank"><img src="<?php echo SITE_URL;?>/img/icons/view-small.png" alt="Просмотр" width="24" height="24" /></a></td>
			<td class="kolomicon"><a href="<?php echo $this->path;?>pages/edit/id/<?php echo $page->getId();?>/"><img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактировать" width="24" height="24" /></a></td>
		<?php
			if($page->getNoDelete())
			{
		?>
			<td class="kolomicon"><img src="<?php echo SITE_URL;?>/img/icons/cantremove-small.png" alt="Страницу нельзя удалить" width="24" height="24" /></td>
		<?php
			}
			else
			{
		?>
			<td class="kolomicon"><a href="<?php echo $this->path;?>pages/delete/id/<?php echo $page->getId();?>/" onclick="return confirm('Вы действиельно хотите удалить страницу?')"><img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удалить" width="24" height="24" /></a></td>
		<?php
			}
			
			if ($count > 1) {
				if (!$is_first) {
					?><td class="kolomicon"><a href="<?php echo $this->path;?>pages/moveup/id/<?php echo $page->getId();?>/"><img src="<?php echo SITE_URL;?>/img/icons/up.png" alt="Вверх" /></a></td><?php
				} else {
					?><td class="kolomicon">&nbsp;</td><?php
				}
				if (!$is_last) {
					?><td class="kolomicon"><a href="<?php echo $this->path;?>pages/movedown/id/<?php echo $page->getId();?>/"><img src="<?php echo SITE_URL;?>/img/icons/down.png" alt="Вниз" /></a></td><?php
				} else {
					?><td class="kolomicon">&nbsp;</td><?php
				}
			} else {
				?><td class="kolomicon">&nbsp;</td>
				<td class="kolomicon">&nbsp;</td><?php
			}
			
		?>
			<td class="kolomtitle"><?php echo $page->getTitle();?></td>
		</tr>
	<?php
		}
	?>
    </table>