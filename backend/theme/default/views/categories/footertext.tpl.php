
    <div class="card card pd-20 pd-sm-40">
    <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
    <a href="/admin/footer-text/new/">
<i class="icon ion-ios-clipboard-outline  tx-30 pd-5" alt="Новая запись" data-placement="left" title="" data-tooltip="tooltip" data-original-title="Добавить запись"></i>
    </a>
    <div class="card-body">
<?php if($this->footer_text){
?>
   <table cellpadding="4" cellspacing="0"  class="table  dataFotter" >
    <thead class="thead-light">
<tr>
    <th></th>
    <th><strong>Действие</strong></th>
    <th><strong>Название</strong></th>
    <th><strong>Категория</strong></th>
    <th><strong>Бренд</strong></th>
    <th><strong>Сезон</strong></th>
	<th><strong>Цвет</strong></th>
	<th><strong>Размер</strong></th>
</tr>
 </thead>
 <tbody>
<?php
foreach($this->footer_text as $d){ ?>
<tr>
    <td></td>
<td>
    <a href="/admin/footer-text/edit/id/<?=$d->id?>/">
<i class="icon ion-ios-clipboard-outline  tx-30 pd-5" alt="Редактировать" data-placement="left" title="" data-tooltip="tooltip" data-original-title="Редактировать акцию"></i>
    </a>
    
</td>
<td><?=$d->name?></td>
<td><?=$d->category_id?$d->category->getRoutez():''?></td>
<td><?=$d->brand_id?$d->brand->name:''?></td>
<td><?=$d->sezon_id?$d->sezon->name:''?></td>
<td><?=$d->color_id?$d->color->name:''?></td>
<td><?=$d->size_id?$d->size->size:''?></td>
</tr>
<?php } ?>
</tbody>
<tfoot>
            <tr>
                 <th></th>
                <th></th>
                <th></th>
    <th></th>
    <th></th>
    <th></th>
	<th></th>
	<th></th>
            </tr>
        </tfoot>
</table>
<?php } ?>
    </div>
</div>
<script>
    $(function(){
              

	  'use strict';
          var t =  $('.dataFotter').DataTable({
          initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
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
          },
          columnDefs: [ {
            searchable: true,
            orderable: false,
            targets: 0
        } ],
        order: [[ 1, 'asc' ]],
        
        });
 t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    });
</script>