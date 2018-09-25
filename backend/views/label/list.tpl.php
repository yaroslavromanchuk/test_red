<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt="" class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<hr/>
<?=$this->getCurMenu()->getPageBody()?>
<p><img height="24" width="24" alt="new" src="/img/icons/edit-small.png"><a href="/admin/label/id/">Добавить этикетку</a></p>
<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th  colspan="2">Действия</th>
			<th class="c-projecttitle">Изображение</th>
			<th class="c-projecttitle">Название</th>
		</tr>
	<?php
	if($this->labels){
		$row = 'row1';
		foreach($this->getLabels() as $sub )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?=$row?>">
			<td class="kolomicon"><a href="<?=$this->path?>label/id/<?=$sub->getId()?>/"><img src="<?=SITE_URL?>/img/icons/edit-small.png" alt="Редактирование" /></a></td>
			<td class="kolomicon">
                <a href="<?=$this->path?>labels/delete/<?=$sub->getId()?>/"><img src="<?=SITE_URL?>/img/icons/remove-small.png" alt="Удалить" /></a>
        </td>
			<td class="c-projecttitle"><img src="<?=$sub->getImage()?>" alt="<?=$sub->getName()?>"></td>
			<td class="c-projecttitle"><?=$sub->getName()?></td>
		</tr>
	<?php
		}
		}
	?>
    </table>