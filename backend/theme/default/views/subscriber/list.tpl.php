<img src="<?=$this->getCurMenu()->getImage()?>" alt="" class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<hr/>
<!--
    <p><img src="/img/icons/edit-small.png" alt="Добавить" class="iconnew" /><a href="<?php echo $this->path;?>subscribers/edit/">Добавить подписчика</a></p>
<form action="" method="post" enctype="multipart/form-data" id="form1">
     <p>Загрузка подписчиков. Файл .csv розделение ";"</p>
      <input type="file" name="exel"/>
                <input type="submit" name = 'csv' value="Загрузить" />
 </form>-->
    <?php if(isset($this->text)){?>
        <textarea rows="20" cols="60"><?=$this->text?></textarea>
        <?php } ?>
<form action="" method="get">
    <p>Поиск</p>
    <span> Email </span><input type="text" name="email" value="<?=$_GET['email']?>" /><span> Имя </span><input type="text" name="name" value="<?=$_GET['name']?>" /><input type="submit" value="Найти" />
</form><br/><p>Первые 100</p>


<table class="table">
		<tr>
			<th colspan="2">Действия</th>
			<th>Имя</th>
			<th>Е-мейл</th>
			<th>Активность</th>
                        <th>Сегмент</th>
                        <th>Дата редактирования</th>
		</tr>
	<?php
		$row = 'row1';
		foreach($this->getSubscribers() as $sub )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td>
                            <a href="<?=$this->path?>subscribers/edit/id/<?=$sub->getId()?>/">
                                <img src="/img/icons/edit-small.png" alt="Редактирование" />
                            </a>
                        </td>
			<td>
                            <a href="<?=$this->path?>subscribers/delete/id/<?=$sub->getId()?>/" onclick="return confirm('Удалить подписчика?')">
                                <img src="/img/icons/remove-small.png" alt="Удаление" />
                            </a>
                        </td>
			<td><?=$sub->getName()?></td>
			<td ><?=$sub->getEmail()?></td>
			<td>
                            <label class="switch">
<input type="checkbox" <?=$sub->active ==1?'checked':''?> name="active" id="<?=$sub->id?>"   class="inputdemo">
 <div class="slider round"></div>
</label>     
                        </td>
                        <td><?=$sub->segment->name?></td>
                        <td><?=date('d.m.Y H:i', strtotime($sub->confirmed))?></td>
		</tr>
	<?php
		}
	?>
    </table>
<script>
$(function(){
  $( ".inputdemo" ).change(function(e) {
 // console.log(e);
  if(this.checked){
       console.log('+'+this.id);
      $.post('/admin/subscribers/', {method:'active', id: this.id, activ: 1}, function (data) {
             console.log(data);
        });
      console.log('+');
  }else{
       $.post('/admin/subscribers/', { method:'active', id: this.id, activ: 0}, function (data) {
             console.log(data);
        });
      console.log('-');
  }
});  
});  
</script>