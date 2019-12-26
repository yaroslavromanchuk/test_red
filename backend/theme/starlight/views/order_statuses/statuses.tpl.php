<div style="width: 880px;margin-left: 50px;">
<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody();?>

<p><a href="<?php echo $this->path;?>order_statuses/edit/id/">Новый статус</a></p>


<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th></th>
			<th class="c-projecttitle">Название</th>
			<th class="c-projecttitle"> </th>
			<th class="c-projecttitle">Порядок</th>
			<th class="c-projecttitle">СМС</th>
			<th class="c-projecttitle">Email</th>
		</tr>
	<?php
		$row = 'row1';
		foreach($this->getOrderStatuses() as $delivery )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon">
                <a href="<?php echo $this->path;?>order_statuses/edit/id/<?php echo $delivery->getId();?>/">
                    <img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактирование" />
                </a>
            </td>
			<td class="c-projecttitle"><?php echo $delivery->getName();?></td>
			<td class="c-projecttitle"><?php echo $delivery->getActive() ? '<span style="color:green;padding-right: 10px;">Активен</span>' : '<span style="color:red;padding-right: 10px;">Неактивен</span>';?></td>
			<td class="c-projecttitle" style="text-align: center;"><?php echo $delivery->getOrder();?></td>
			<td class="c-projecttitle"><?php echo $delivery->getSendSms() ? '<span style="color:green;padding-right: 10px;">Да</span>' : '<span style="color:red;padding-right: 10px;">Нет</span>';?></td>
			<td class="c-projecttitle"><?php echo $delivery->getSendEmail() ? '<span style="color:green;padding-right: 10px;">Да</span>' : '<span style="color:red;padding-right: 10px;">Нет</span>';?></td>
		</tr>
	<?php } ?>
</table>
</div>