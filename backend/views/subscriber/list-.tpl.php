<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody();?>

    <p><img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Добавить" class="iconnew" /><a href="<?php echo $this->path;?>subscribers/edit/">Добавить подписчика</a></p>
<form action="" method="post" enctype="multipart/form-data" id="form1">
     <p>Загрузка подписчиков. Файл .csv розделение ";"</p>
      <input type="file" name="exel"/>
                <input type="submit" name = 'csv' value="Загрузить" />
 </form
    <?php if(isset($this->text)){?>
        <textarea rows="20" cols="60"><?=$this->text?></textarea>
        <?php } ?>

<form action="" method="get">
    <p>Поиск</p>
    <span> Email </span><input type="text" name="email" value="<?=@$_GET['email'];?>" /><span> Имя </span><input type="text" name="name" value="<?=@$_GET['name'];?>" /><input type="submit" value="Найти" />
</form><br/><p>Первые 100</p>


<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th colspan="2">Действия</th>
			<th class="c-projecttitle">Имя</th>
			<th class="c-projecttitle">Е-мейл</th>
			<th class="c-clientname">Дата активации</th>
		</tr>
	<?php
		$row = 'row1';
		foreach($this->getSubscribers() as $sub )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon"><a href="<?php echo $this->path;?>subscribers/edit/id/<?php echo $sub->getId();?>/"><img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактирование" /></a></td>
			<td class="kolomicon"><a href="<?php echo $this->path;?>subscribers/delete/id/<?php echo $sub->getId();?>/" onclick="return confirm('Удалить подписчика?')"><img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удаление" /></a></td>
			<td class="c-projecttitle"><?php echo $sub->getName();?></td>
			<td class="c-projecttitle"><?php echo $sub->getEmail();?></td>
			<td class="c-clientname"><?php echo $sub->getConfirmed() ? date('d-m-Y', strtotime($sub->getConfirmed())) : 'Не активирован';?></td>
		</tr>
	<?php
		}
	?>
    </table>