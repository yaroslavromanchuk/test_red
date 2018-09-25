<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Сохраненная <?=$this->name?></h3></div>
<div class="panel-body">
<table class="table table-hover" style="width: 100%;">
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
if(@$this->semail){
	foreach ($this->semail as $semail) { 
	$customer_new = wsActiveRecord::useStatic('Customer')->findFirst(array('id'=>$semail->id_customer_new));
	if(@$semail->id_customer_go) $customer_go = wsActiveRecord::useStatic('Customer')->findFirst(array('id'=>$semail->id_customer_go));
?>
					<tr>
					
					    <td><?php echo $semail->ctime;?></td>
						<td><?php echo $customer_new->getFirstName()." ".$customer_new->getMiddleName(); ?></a></td>
						<td><?php if(@$semail->go) echo $semail->go; ?></td>
						<td><?php if(@$semail->id_customer_go) echo $customer_go->getFirstName()." ".$customer_go->getMiddleName(); ?></td>
						<td><a href="/admin/<?=$this->flag?>/edit/?id=<?=$semail->id ?>" class="btn btn-sm btn-info" target="_blank" <?php if(@$semail->id_customer_go){ echo 'style="display:none;"';}?> >Редактировать</a></td>
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
			</div>
			</div>