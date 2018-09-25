
<div class="sl-pagebody">
<!--
<div class="sl-page-title">
          <h5><?=$this->getCurMenu()->getTitle()?></h5>
          <p><?=$this->getCurMenu()->getPageBody()?></p>
</div>
-->
<div class="card pd-20">
<?php if(@$this->articles){?>

          <div class="table-wrapper">
		  <table class="table display responsive nowrap" id="datatable1">
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
				  <a href="<?=$a->getPath()?>" target="_blank" data-toggle="tooltip" data-placement="right" title="Смотреть">
				  <i class="menu-item-icon icon ion-ios-monitor-outline tx-22 pd-5 mg-10"></i>
				  </a>
				  <a href="<?=$this->path?>articles-add/edit/<?=$a->id?>/"  data-toggle="tooltip"  title="Редактировать">
				  <i class="menu-item-icon icon ion-ios-paper-outline tx-22 pd-5 mg-10" ></i>
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
</div>
    <script>
      $(function(){
       // 'use strict';

  $('#datatable1').DataTable({
          //responsive: true,
          language: {
            searchPlaceholder: 'Поиск в таблице...',
            sSearch: '',
            lengthMenu: '_MENU_ записей/страница',
			processing: "Выполняется обработка...",
			info:       "Записи с _START_ по _END_ из _TOTAL_ ",
			sInfoFiltered: '(найдено _TOTAL_ из _MAX_ записей)',
			paginate: {
            first:      "Первая",
            previous:   "Придведущая",
            next:       "Следующая",
            last:       "Последняя"
        },
          }
        });

        // Select2
       // $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

     });
    </script>	