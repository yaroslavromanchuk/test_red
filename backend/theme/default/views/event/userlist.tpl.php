<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody();?>

<table cellpadding="2" cellspacing="0" class="table table-striped table-hover table-bordered table-condensed" >
		<tr>
			<th >Действия</th>
			<th>Имя</th>
			<th>Е-мейл</th>
			<th >Телефон</th>
			<th>Скидка</th>
			<th >Акционная скидка</th>
			<th >Старт</th>
			<th >Стоп</th>
			<th >Заказ</th>
		</tr>
	<?php
		$row = 'row1';
		foreach($this->getSubscribers() as $item )
		{
		$row = ($row == 'row2') ? 'row1' : 'row2';
		$sub = new Customer($item->getCustomerId());
	?>
		<tr class="<?=$row?>">
			<td class="kolomicon"><a href="<?=$this->path?>user/edit/id/<?=$sub->getId()?>/"><img src="<?=SITE_URL?>/img/icons/edit-small.png" alt="Редактирование" /></a></td>
			<td ><?php echo $sub->getFullname();?></td>
			<td ><?php echo $sub->getEmail();?></td>
			<td ><?php echo $sub->getPhone1();?></td>
			<td ><?php echo $sub->getDiscont(false,0,true);?>%</td>
            <td ><?php echo (int)EventCustomer::getEventsDiscont($sub->getId());?>%</td>
			<td ><?php echo $item->ctime?></td>
			<td ><?php echo $item->end_time;?></td>
			<td ><?php echo $item->st == 5 ? '+' : '-'?></td>

		</tr>
	<?php
		}
	?>
    </table>