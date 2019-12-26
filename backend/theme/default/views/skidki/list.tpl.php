<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody();?>

<p><a href="/admin/skidki/add/ar/">Добавить товыры</a></p>
<p><a href="/admin/skidki/add/cat/">Добавить категорию</a></p>

<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th >Действия</th>
			<th class="c-projecttitle">Скидка</th>
			<th class="c-projecttitle">Товар/Категория</th>
			<th class="c-projecttitle">Начало</th>
			<th class="c-projecttitle">Конец</th>
			<th class="c-projecttitle">Включена</th>
		</tr>
	<?php
		$row = 'row1';
		foreach($this->getLabels() as $sub )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon"><a href="<?php echo $this->path;?>skidka/<?php if($sub->getArticleId() != 0) {echo "id/".$sub->getId();} else {echo "cat/".$sub->getId();}?>/"><img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактирование" /></a></td>
			<td class="c-projecttitle"><?php echo $sub->getValue();?>%</td>
			<td class="c-projecttitle"><?php if($sub->getArticleId() != 0){ ?> <a href="<?php echo $sub->article->getPath();?>" target="_blank"><?php echo $sub->article->getBrand();?>(<?php echo $sub->article->getModel();?>)</a><?php 
			}else{ echo Shopcategories::CatName((int)$sub->getCategoryId());} ?></td>
            <td class="c-projecttitle"><?php echo $sub->getStart();?></td>
            <td class="c-projecttitle"><?php echo $sub->getFinish();?></td>
            <td class="c-projecttitle"><?php echo $sub->getPublish()?'Да':'Нет';?></td>
        </tr>
	<?php
		}
	?>
    </table>