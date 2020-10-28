
    <div class="card card pd-20 pd-sm-40">
    <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
    <a href="/admin/footer-text-category/new/">
<i class="icon ion-ios-clipboard-outline  tx-30 pd-5" alt="Новая запись" data-placement="left" title="" data-tooltip="tooltip" data-original-title="Добавить запись"></i>
    </a>
    <div class="card-body">
<?php if($this->footer_text){
?>
   <table cellpadding="4" cellspacing="0"  class="table " >
    <thead class="thead-light">
<tr>
    <td><strong>Действие</strong></td>
    <td><strong>Название</strong></td>
    <td><strong>Категория</strong></td>
    <td><strong>Бренд</strong></td>
    <td><strong>Сезон</strong></td>
	<td><strong>Цвет</strong></td>
	<td><strong>Размер</strong></td>
</tr>
 </thead>
 <tbody>
<?php
foreach($this->footer_text as $d){ ?>
<tr>
<td>
    <a href="/admin/footer-text-category/edit/id/<?=$d->id?>/">
<i class="icon ion-ios-clipboard-outline  tx-30 pd-5" alt="Редактировать" data-placement="left" title="" data-tooltip="tooltip" data-original-title="Редактировать акцию"></i>
    </a>
    
</td>
<td><?=$d->name?></td>
<td><?=$d->category_id?$d->category->title:''?></td>
<td><?=$d->brand_id?$d->brand->name:''?></td>
<td><?=$d->sezon_id?$d->sezon->name:''?></td>
<td><?=$d->color_id?$d->color->name:''?></td>
<td><?=$d->size_id?$d->size->size:''?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
    </div>
</div>
