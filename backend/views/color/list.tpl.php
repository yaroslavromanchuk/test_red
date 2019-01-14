<div class="card pd-30">
        <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
<p><img height="24" width="24" alt="new" src="/img/icons/edit-small.png"><a href="/admin/color/">Добавить цвет</a></p>
<div class="table-wrapper">
    <table class="table display responsive nowrap datatable1">
        <thead class="bg-info">
		<tr>
			<th>Действия</th>
			<th class="c-projecttitle">Цвет</th>
			<th class="c-projecttitle">Название</th>
                        <th class="c-projecttitle">id-1C</th>
                        

		</tr>
                </thead>
                <tbody>
	<?php
		foreach($this->getColors() as $sub )
		{
			$getId = $sub->getId();
			$sql = '
				SELECT
					count(ART.id) as cnt
				FROM
					ws_articles_sizes ART
				WHERE
					ART.id_color = '.$getId.'
			';
			$count = wsActiveRecord::findByQueryFirstArray($sql)['cnt'];
	?>
		<tr>
			<td>
				<a href="<?=$this->path?>color/id/<?=$getId?>/">
					<img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактирование" />
				</a>
			
<?php
				if(!$count) {
?>
                    <a href="<?=$this->path?>color/del/<?=$getId?>/" onclick="return confirm('Вы действиельно хотите удалить цвет?')">
						<img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удалить" width="24" height="24" />
					</a>
<?php
				}
				else {
					echo $count.' тов.';
				}
?>

            </td>
			<td>
                <div style="width: 30px; height: 30px; background: <?=$sub->color?>"></div>
            </td>
			<td><?=$sub->name?></td>
                        <td><?=$sub->id_1c?></td>
		</tr>
	<?php
		}
	?>
                  <tbody>
    </table>
</div>
</div>
