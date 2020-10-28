<table class="table table-hover text-center">
				<thead>
					<tr >
                                            <th>#</th>
						<th>Дата создания</th>
						<th>Создал</th> 
						<th>Дата отправки</th>
						<th>Отправил</th> 
                                                <th>Отправлено</th>
                                                <th>Открыто</th>
                                                <th>Переходов</th>
                                                <th>Заказов</th>
                                                <th>Товаров</th>
                                                <th>Сумма</th>
                                                <th>Конверсия</th>
						<th>Редактировать</th>
						<th>Смотреть</th>
						<th>Удалить</th>
					</tr>
					</thead>
				<tbody>

<?php 
if($this->semail){
    $i=1;
	foreach ($this->semail as $semail) {
?>
		<tr>
                    <td><?=$i?></td>
                    <td><?=$semail->ctime?></td>
                    <td><?=$semail->admin_create->getFullname()?></a></td>
                    <td><?=$semail->go?$semail->go:''?></td>
                    <td><?=$semail->id_customer_go?$semail->admin_go->getFullname():''?></td>
                    <td><?=$semail->count_go?></td>
                    <td><span style="color: #0dc30d;margin-right: 5px"><?=round($semail->count_go?$semail->count_open/$semail->count_go*100:0,1)?>%</span>(<?=$semail->count_open?>)</td>
                    <td><span style="color: #0dc30d;margin-right: 5px"><?=round($semail->count_go?$semail->count_link/$semail->count_go*100:0,1)?>%</span>(<?=$semail->count_link?>)</td>
                    <td><?=$semail->count_order?></td>
                    <td><?=$semail->count_order_article?></td>
                    <td><?=$semail->order_amount?></td>
                    <td><span style="color: #0dc30d;margin-right: 5px"><?=round($semail->count_order_article?$semail->count_order_article/$semail->count_open*100:0,1)?>%</span></td>
                    <td>
                        <a href="/admin/generalmailing/edit/?id=<?=$semail->id?>" class="btn btn-sm btn-info" target="_blank" <?php if($semail->id_customer_go){ echo 'style="display:none;"';}?> >Редактировать</a>
                    </td>
                    <td>
                        <input name="preview" type="button"  class="btn btn-sm btn-success" id="preview" onclick="return Preview(<?=$semail->id?>);" value="Посмотреть">
                    </td>
                    <td><input name="dell" type="button" class="btn btn-sm btn-danger" id="dell" onclick="return Dell(<?=$semail->id?>);" value="Удалить"></td>
		</tr> 
					
<?php $i++;
	}
	}
?>
	</tbody>
</table>