<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>

    <p><img src="/img/icons/edit-small.png" alt="Nieuwsbericht toevoegen" width="24" height="24" />
	<a href="<?=$this->path?>blog-post/new/">Добавить пост</a></p>
    <table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th colspan="3">Действия</th>
			<th style="width: 500px;">Заголовок</th>
			<th style="width: 100px;">Статус</th>
			<th>Дата изменения</th>
			<th>Дата публикации</th>
		</tr>
	<?php 
	$a = "Опубликовано";
	$c = "Черновык";
		$row = 'row1';
		foreach($this->blog as $n )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon"><a href="<?=$n->getPath()?>" target="_blank"><img src="/img/icons/view-small.png" alt="Просмотр" width="24" height="24" /></a></td>
			<td class="kolomicon"><a href="<?=$this->path?>blog-post/edit/id/<?=$n->getId()?>/"><img src="/img/icons/edit-small.png" alt="Редактировать" width="24" height="24" /></a></td>
			<td class="kolomicon"><a href="<?=$this->path?>blog-post/delete/<?=$n->getId()?>/" onclick="return confirm('Вы действительно хотите удалить пост?')"><img src="/img/icons/remove-small.png" alt="Удалить" width="24" height="24" /></a></td>
	        <td class="changecat-name"><?php echo $n->getPostName();?></td>
	        <td class="cat-seq"><?php if($n->getPublic() == 1){ echo $a;} else { echo $c;}?></td>
	        <td class="cat-seq"><?php echo $n->getUtime();?></td>
			<td class="cat-seq"><?php echo $n->getCtime();?></td>
  		</tr>
	<?php
		}
	?>
    </table>