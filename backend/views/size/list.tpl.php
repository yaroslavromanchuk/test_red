<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody();?>
<p><img height="24" width="24" alt="new" src="/img/icons/edit-small.png"><a href="/admin/size/">Добавить размер</a></p>
<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th  colspan="2">Действия</th>
			<th class="c-projecttitle">Размер</th>
			<th class="c-projecttitle">Категория</th>

		</tr>
	<?php
		$row = 'row1';
		foreach($this->getSizes() as $sub )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon"><a href="<?php echo $this->path;?>size/id/<?php echo $sub->getId();?>/"><img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактирование" /></a></td>
			<td class="kolomicon">
                 <?php if(!$sub->getArticlesCount()){?>
                    <a href="<?php echo $this->path;?>size/del/<?php echo $sub->getId();?>/" onclick="return confirm('Вы действиельно хотите удалить размер?')"><img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удалить" width="24" height="24" /></a>
                <?php } else {?>
                    <?php echo $sub->getArticlesCount()?> тов.
                <?php } ?>

            </td>
			<td class="c-projecttitle">
                <?php echo $sub->getSize()?>
                </td>
			<td class="c-projecttitle"><?php echo $sub->category?$sub->category->getName():'';?></td>


		</tr>
	<?php
		}
	?>
    </table>