
<div class="card pd-20 mb-2">
    <div class="card-body">
        <div class="card-title"><?=$this->getCurMenu()->getTitle()?></div>
<table class="table">
    <thead>
		<tr>
			<th >Действия</th>
			<th class="c-projecttitle">Название</th>
			<th class="c-projecttitle">Стоимость</th>
			<th class="c-projecttitle">Статус</th>
		</tr>
                </thead>
                <tbody>
	<?php
		foreach($this->getDeliveries() as $delivery )
		{
		
	?>
		<tr>
			<td>
                <a href="<?=$this->path?>delivery_type/edit/id/<?=$delivery->getId()?>/">
                    <img src="/img/icons/edit-small.png" alt="Редактирование" />
                </a>
            </td>
			<td><?=$delivery->getName()?></td>
			<td ><?=$delivery->getPrice()?></td>
			<td ><?=$delivery->getActive() ? '<span style="color:green;padding-right: 10px;">Активен</span>' : '<span style="color:red;padding-right: 10px;">Неактивен</span>'?></td>
		</tr>
	<?php
		}
	?>
                </tbody>
    </table>
</div>
    <div class="card-footer">
        <p><a class="btn btn-success" href="<?=$this->path?>delivery_type/edit/id/">Новый способ доставки</a></p>
    </div>
    </div>

