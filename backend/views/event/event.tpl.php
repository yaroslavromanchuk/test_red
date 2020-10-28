<img src="<?=$this->getCurMenu()->getImage()?>" class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<hr/>
<?=$this->getCurMenu()->getPageBody()?>
<p><a href="<?=$this->path?>event/id/">Новое событие</a></p>

<table class="table">
    <thead>
		<tr>
			<th>Действия</th>
			<th>Название</th>
			<th>Начало</th>
			<th>Конец</th>
			<th>Скидка</th>
			<th>Клиентов/Заказов</th>
			<th>Статус</th>
			<th>Ссылка</th>
		</tr>
         </thead>
                <tbody>
	<?php
		foreach($this->getEvents() as $event ){?>
		<tr>
                        <td>
                            <a href="<?=$this->path?>event/id/<?=$event->getId()?>/">
                                <img src="/img/icons/edit-small.png" alt="Редактирование" />
                            </a>
                        </td>
			<td><?=$event->getName()?></td>
			<td><?=date('d-m-Y',strtotime($event->getStart()))?></td>
			<td><?=date('d-m-Y',strtotime($event->getFinish()))?></td>
			<td><?=$event->getDiscont()?>%</td>
			<td><a href="/admin/usersevent/id/<?=$event->getId()?>"><?=$event->getCustomersCount()?>/<?=$event->getOrdersCount()?></a></td>
			<td><?=$event->getPublick()?'Запущена':'Остановлена'?></td>
			<td><span class="src" id="<?=$event->id?>" data-tooltip="tooltip" data-original-title="Кликните для копирования ссылки"  ><?=$event->getPath()?></span></td>
		</tr>
	<?php
		}
	?>
                </tbody>
    </table>
<script> 
$('.src').click(function() {
	    var $temp = $("<input>");
	    $("body").append($temp);
	    $temp.val($(this).html()).select();
	    document.execCommand("copy");
	    $temp.remove();
	    $(this).html('Тест скопирован!');
	});
</script> 