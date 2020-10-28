<div class="card pd-20">
	<p>
		<img height="24" width="24" alt="new" src="/img/icons/edit-small.png">
		<a href="/admin/brand/edit/">Добавить бренд</a>
	</p>
        <div class="card-body">
            <table  class="table display responsive nowrap table-dark  datatable1">
                <thead>
		<tr>
			<th >Действия</th>
			<th >Изображение</th>
			<th >Название</th>
                        <th >Страна бренда</th>
			<th >Товаров</th>
			<th >На главной</th>
                        <th>Грейд</th>
                        <th>Действие</th>
		</tr>
                </thead>
                <tbody>
<?php
	foreach($this->getBrands() as $b ) {		
?>
		<tr>
			<td><a href="<?=$this->path?>brand/edit/id/<?=$b->id?>/"><img src="/img/icons/edit-small.png" alt="Редактирование" /></a>
			<?php if (!$count) { ?>
                  <label class="ckbox wd-16 mt-3" data-tooltip="tooltip"  title="Пометка на удаление">
                      <input type="checkbox" id="dell_<?=$b->id?>" name="dell_<?=$b->id?>"><span></span>
                  </label>
              </div>
<!--<a href="<?=$this->path?>brand/delete/id/<?=$b->id?>/"><img src="/img/icons/remove-small.png" alt="Удалить" /></a>--><?php } ?></td>
                        <td><?php if($b->getImage()) { ?><img style="max-height:  30px" src="<?=$b->getImage()?>" alt="<?=$b->name?>"><?php } ?></td>
			<td><?=$b->name?></td>
                        <td><?=$b->country_brand?></td>
                        <td><a href="/admin/shop-articles/?brand_id=<?=$b->id?>&status=3&nalich=1&sort=dateminus" target="_blank"><?=$b->getCountArticles()?></a></td>
			<td><?=$b->top?'Да':'Нет'?></td>
                        <td><?=$b->greyd?></td>
                        <td>
                            <select class="form-control " id="<?=$b->id?>" name="<?=$b->id?>" data-placeholder="Грейд" onchange="setGreyd(this);">
                            <option label="Грейд"></option>
                            <option value="0">Без грейда</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            </select>
                        </td>
		</tr>
<?php
		}
?>
                </tbody>
    </table>
             </div>  
       </div>
<script>
function setGreyd(e){
    
    console.log(e.name);
     console.log(e.value);
     var dell = 0; 
     if($('#dell_'+e.name).prop( "checked" )){
         dell = 1;
     }
    $.ajax({
                url: '/admin/brands/',
                type: 'POST',
                dataType: 'json',
                data: '&method=set_greyd&greyd='+e.value+'&dell='+dell+'&id='+e.name,
                success: function (res) {
                    console.log(res);
				if(res){
                                    $('#'+e.name).hide();
                                }
				
                },
				error: function(e){
				console.log(e);
				}
            });
    return false;
}
</script>