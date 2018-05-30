
<?php  header('Access-Control-Allow-Origin: *'); 
header('X-XSS-Protection: 0'); ?>
<div class="col-xs-12" >
<table class="table table-hover" style="width: 100%;">
				<caption>Рассылки</caption>
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
						<td><a href="/admin/<?= $this->flag ?>/edit/?id=<?= $semail->id ?>" target="_blank" <?php if(@$semail->id_customer_go){ echo 'style="display:none;"';}?> >Редактировать</a></td>
						<td>
						<input name="preview" type="button" style="border-radius: 5px;" class="buttonps11" id="preview" onclick="Preview(<?= $semail->id ?>)" value="Посмотреть">
						</td>
						<td><input name="dell" type="button" style="border-radius: 5px;" class="buttonps11" id="dell" onclick="Dell(<?= $semail->id ?>)" value="Удалить"></td>
					
					</tr> 
					
<?php
	}
	}
	
?>
				</tbody>
				
			</table>
</div>