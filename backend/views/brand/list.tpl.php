<div class="card pd-30">
<?php
	$getBrands = $this->getBrands();
	$path = $this->path;
?>
        <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
	<p>
		<img height="24" width="24" alt="new" src="/img/icons/edit-small.png">
		<a href="/admin/brand/edit/">Добавить бренд</a>
	</p>
        <div class="card-body">
            <table  class="table table-hover table-bordered">
                <thead>
		<tr>
			<th  colspan="2">Действия</th>
			<th class="c-projecttitle">Изображение</th>
			<th class="c-projecttitle">Название</th>
                        <th class="c-projecttitle">Страна бренда</th>
			<th class="c-projecttitle">Количество товаров</th>
			<th class="c-projecttitle">На главной</th>
		</tr>
                </thead>
                <tbody>
<?php
	foreach($getBrands as $sub ) {
		$count = wsActiveRecord::useStatic('Shoparticles')->count(array('stock > 0', 'active = "y"', 'brand_id'=>$sub->getId()));
		//$count = $count[0]->cnt;
		$getId = $sub->getId();
		$getImage = $sub->getImage();
		$getName = $sub->getName();
		$getTop = $sub->getTop();

		
?>
		<tr>
			<td><a href="<?=$path?>brand/edit/id/<?=$getId?>/"><img src="/img/icons/edit-small.png" alt="Редактирование" /></a></td>
			<td><?php if (!$count) {?><a href="<?=$path?>brand/delete/id/<?=$getId?>/"><img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удалить" /></a><?php } ?></td>
			<td><img style="max-width: 30px; max-height: 30px;" src="<?=$getImage?>" alt="<?=$getName?>"></td>
			<td><?=$sub->name?></td>
                        <td><?=$sub->country_brand?></td>
			<td><a href="/admin/shop-articles/?search=&brand=<?=$sub->name?>&from=&to=&id=&sort=dateminus"><?=$count?></a></td>
			<td><?=$getTop?'Да':'Нет'?></td>
		</tr>
<?php
		}
?>
                </tbody>
    </table>
             </div>  
       </div>  