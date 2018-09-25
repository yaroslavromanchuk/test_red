<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody();?>

<p><a href="<?php echo $this->path;?>delivery_type/edit/id/">Новый способ доставки</a></p>


<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th >Действия</th>
			<th class="c-projecttitle">Название</th>
			<th class="c-projecttitle">Стоимость</th>
			<th class="c-projecttitle">Статус</th>
		</tr>
	<?php
		$row = 'row1';
		foreach($this->getDeliveries() as $delivery )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon">
                <a href="<?php echo $this->path;?>delivery_type/edit/id/<?php echo $delivery->getId();?>/">
                    <img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактирование" />
                </a>
            </td>
			<td class="c-projecttitle"><?php echo $delivery->getName();?></td>
			<td class="c-projecttitle" style="text-align: center;"><?php echo $delivery->getPrice();?></td>
			<td class="c-projecttitle"><?php echo $delivery->getActive() ? '<span style="color:green;padding-right: 10px;">Активен</span>' : '<span style="color:red;padding-right: 10px;">Неактивен</span>';?></td>
		</tr>
	<?php
		}
	?>
    </table>