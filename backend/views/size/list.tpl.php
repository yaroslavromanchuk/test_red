<div class="card pd-30">
        <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
<p>
    <img height="24" width="24" alt="new" src="/img/icons/edit-small.png">
    <a href="/admin/size/">Добавить размер</a>
</p>
<div class="table-wrapper">
    <table class="table display responsive nowrap datatable1">
        <thead class="bg-info">
		<tr>
			<th>Действия</th>
			<th>Размер</th>
			<th>Категория</th>

		</tr>
        </thead>
        <tbody>
	<?php
		foreach($this->getSizes() as $sub )
		{ ?>
		<tr>
		<td><a href="<?=$this->path?>size/id/<?=$sub->id?>/"><img src="/img/icons/edit-small.png" alt="Редактирование" /></a>
                        
                 <?php if(!$sub->getArticlesCount()){?>
                    <a href="<?=$this->path?>size/del/<?=$sub->id?>/" onclick="return confirm('Вы действиельно хотите удалить размер?')">
                        <img src="/img/icons/remove-small.png" alt="Удалить" width="24" height="24" />
                    </a>
                <?php } else {?>
                    <?=$sub->getArticlesCount()?> тов.
                <?php } ?>

            </td>
            <td>
                <?=$sub->getSize()?>
            </td>
            <td><?=$sub->category?$sub->category->getName():''?></td>
		</tr>
	<?php
		}
	?>
        </tbody>
    </table>
</div>
</div>