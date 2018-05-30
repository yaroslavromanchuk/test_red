<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody();?>
<p><img height="24" width="24" alt="new" src="/img/icons/edit-small.png"><a href="/admin/label/id/">Добавить этикетку</a></p>
<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th  colspan="2">Действия</th>
			<th class="c-projecttitle">Изображение</th>
			<th class="c-projecttitle">Название</th>
			<th class="c-projecttitle">Количество товаров</th>
		</tr>
	<?php
		$row = 'row1';
		foreach($this->getLabels() as $sub )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon"><a href="<?php echo $this->path;?>label/id/<?php echo $sub->getId();?>/"><img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактирование" /></a></td>
			<td class="kolomicon">
                <?php if($sub->articles->count()){?>
                <?php } else {?>
                <a href="<?php echo $this->path;?>labels/delete/<?php echo $sub->getId();?>/"><img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удалить" /></a>
                <?php } ?>
        </td>
			<td class="c-projecttitle"><img src="<?php echo $sub->getImage();?>" alt="<?php echo $sub->getName();?>"></td>
			<td class="c-projecttitle"><?php echo $sub->getName();?></td>
			<td class="c-projecttitle"><?php echo $sub->articles->count();?></td>
		</tr>
	<?php
		}
	?>
    </table>