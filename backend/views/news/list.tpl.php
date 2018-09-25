<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>

    <p><img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Nieuwsbericht toevoegen" width="24" height="24" /><a href="<?php echo $this->path;?>news/edit/">Добавить новость</a></p>
    <table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th colspan="3">Действия</th>
			<th>Заголовок</th>
			<th>Статус</th>
			<th>Дата</th>
		</tr>
	<?php 
		$row = 'row1';
		foreach($this->getNews() as $n )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon"><a href="<?php echo $n->getPath();?>" target="_blank"><img src="/img/icons/view-small.png" alt="Просмотр" width="24" height="24" /></a></td>
			<td class="kolomicon"><a href="<?php echo $this->path;?>news/edit/id/<?php echo $n->getId();?>/"><img src="/img/icons/edit-small.png" alt="Редактировать" width="24" height="24" /></a></td>
			<td class="kolomicon"><a href="<?php echo $this->path;?>news/delete/id/<?php echo $n->getId();?>/" onclick="return confirm('Вы действительно хотите удалить новость?')"><img src="/img/icons/remove-small.png" alt="Удалить" width="24" height="24" /></a></td>
	        <td class="changecat-name"><?php echo $n->getTitle();?></td>
	        <td class="cat-seq"><?php echo $n->getStatusText();?></td>
	        <td class="cat-seq"><?php echo $n->getEndDate();?></td>
  		</tr>
	<?php
		}
	?>
    </table>