<div class="card pd-20">
     <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
<?php if($this->articles){?>

          <div class="table-wrapper">
		  <table class="table display responsive nowrap datatable1" >
		  <thead class="bg-info">
									<tr>
									<td>Действие</td>
									<td>Модель</td>
									<td>Цена</td>
									<td>Накладна</td>
									<td>Размеры</td>
									</tr>
		</thead>
		<tbody>
		<?php foreach($this->articles as $a){ ?>
		  
		  <tr>
		  <td>
				  <a href="<?=$a->getPath()?>" target="_blank" data-tooltip="tooltip" data-placement="right" title="Смотреть">
				  <i class="menu-item-icon icon ion-md-eye tx-22 mg-10"></i>
				  </a>
				  <a href="<?=$this->path?>articles-add/edit/<?=$a->id?>/"  data-tooltip="tooltip"  title="Редактировать">
				  <i class="menu-item-icon icon ion-md-paper tx-22  mg-10" ></i>
				  </a>
				  </td>
									<td><?=$a->getTitle()?></td>
									<td><?=$a->getPrice()?></td>
									<td><?=$a->getCode()?></td>
									<td>
									<?php if($a->getSizes()){ ?>
								<!--	<table>
									<thead class="bg-primary">
									<tr><td>Код</td><td>Цвет</td><td>Размер</td><td>Колл.</td>
									</tr>
									</thead>-->
									<ol>
<?php foreach($a->getSizes() as $s){ ?>
<li><?=$s->code.', '.$s->color->name.', '.$s->size->size.':'.$s->count?></li>

								<!--	<tr>
									<td><?=$s->code;?></td>
									<td><?=$s->color->name;?></td>
									<td><?=$s->size->size;?></td>
									<td><?=$s->count;?></td>
									</tr>-->
<?php } ?>
</ol>
								<!--	</table>-->
									<?php } ?>
									</td>
									</tr>
								<?php	}?>
									</tbody>
</table> 

          </div><!-- table-wrapper -->

<?php } ?>

</div>
