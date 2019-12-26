<table class="table datatable1 dataTable table-hover">
    <thead>
    <th>#</th> 
    <th>Имя/Тел.</th>
    <th>Товаров</th>
    <th>Сумма</th>
    <th>Последнее обновление</th>
    </thead>
    <tbody class="accordion" id="accordionExample">
<?php
$i = 1;
foreach ($this->carts as $c) { ?>
    <tr  id="heading-<?=$c->id?>"    >
        <td><?=$i?></td>
        <td class="collapsed" data-toggle="collapse" data-target="#<?=$c->id?>" aria-expanded="false" aria-controls="<?=$c->id?>" >
            <?php echo $c->getUser()->getFullname().' / '.$c->getUser()->phone1; ?>
            <div id="<?=$c->id?>" class="collapse">
                <ul class="list-group">
                    <?php foreach ($c->item as $a) { ?>
                <li class="list-group-item">
                    <a href="<?=$a->article->getPath()?>" target="_blank" class="img_pre">
                    <img src="<?=$a->article->getImagePath('small_basket')?>" />
                    </a>
                    <div class="simple_overlay" id="imgiyem<?=$a->id; ?>" style="position: fixed;top: 20%;left: 30%; z-index:100">
                    <img src="<?=$a->article->getImagePath('detail'); ?>" />
                    </div>
                <?=$a->article->getTitle()?>
                    <div class="d-inline-block">Цвет: <?=$a->colors->name?> | Размер: <?=$a->sizes->size?></div>
                    <div class="d-inline-block">Цена: <?=$a->old_price>0?$a->old_price:$a->article->price?>/<?=$a->price?> (<?=$a->skidka?>)</div>
                    
                </li>
                <?php } ?>
                </ul>
    </div> 
        </td>
        <td><?=$c->count?></td>
        <td><?=$c->total_price?></td>
        <td><?=$c->ctime?></td>
    </tr>
    
<?php $i++; } ?>
</tbody>
</table>
<script>
    $(document).ready(function () {
								 $('a.img_pre').hover(function () {
								 console.log('+');
		$(this).parent().find('div.simple_overlay').show();
        }, function () {
		console.log('-');
		$(this).parent().find('div.simple_overlay').hide();
        });
    });
    </script>