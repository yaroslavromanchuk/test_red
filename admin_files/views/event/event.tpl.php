<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<hr/>
<?=$this->getCurMenu()->getPageBody()?>

<p><a href="<?=$this->path?>event/id/">Новое событие</a></p>

<table class="table">
		<tr>
			<th >Действия</th>
			<th>Название</th>
			<th>Начало</th>
			<th>Конец</th>
			<th>Скидка</th>
			<th>Клиентов</th>
			<th>Статус</th>
			<th>Ссылка</th>
		</tr>
	<?php
		$row = 'row1';
		foreach($this->getEvents() as $event ){
			$row = ($row == 'row2') ? 'row1' : 'row2';?>
		<tr class="<?=$row?>">
			<td>
                <a href="<?php echo $this->path;?>event/id/<?php echo $event->getId();?>/">
                    <img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактирование" />
                </a>
            </td>
			<td><?php echo $event->getName();?></td>
			<td><?php echo date('d-m-Y',strtotime($event->getStart()));?></td>
			<td><?php echo date('d-m-Y',strtotime($event->getFinish()));?></td>
			<td><?php echo $event->getDiscont();?>%</td>
			<td><a href="/admin/usersevent/id/<?php echo $event->getId();?>"><?php echo $event->getCustomersCount();?></a></td>
			<td><?php echo $event->getPublick()?'Запущена':'Остановлена';?></td>
			<td><?php echo $event->getPath(); ?></td>
		</tr>
	<?php
		}
	?>
    </table>