<table class="table table-hover">
				<thead>
					<tr>
						<th>Дата создания</th>
						<th>Создал</th> 
						<th>Дата отправки</th>
						<th>Отправил</th> 
						<th>Редактировать</th>
						<th>Смотреть</th>
						<th>Удалить</th>
					</tr>
					</thead>
				<tbody>

<?php 
if($this->semail){
	foreach ($this->semail as $semail) {
?>
		<tr>
                    <td><?=$semail->ctime?></td>
                    <td><?=$semail->admin_create->getFullname()?></a></td>
                    <td><?=$semail->go?$semail->go:''?></td>
                    <td><?=$semail->id_customer_go?$semail->admin_go->getFullname():''?></td>
                    <td>
                        <a href="/admin/generalmailing/edit/?id=<?=$semail->id?>" class="btn btn-sm btn-info" target="_blank" <?php if($semail->id_customer_go){ echo 'style="display:none;"';}?> >Редактировать</a>
                    </td>
                    <td>
                        <input name="preview" type="button"  class="btn btn-sm btn-success" id="preview" onclick="return Preview(<?=$semail->id?>);" value="Посмотреть">
                    </td>
                    <td><input name="dell" type="button" class="btn btn-sm btn-danger" id="dell" onclick="return Dell(<?=$semail->id?>);" value="Удалить"></td>
					
					</tr> 
					
<?php
	}
	}
	
?>
				</tbody>
				
			</table>