
    <div class="card card pd-20 ">
    <h6 class="card-body-title"><img src="<?=$this->getCurMenu()->getImage()?>"  class="page-img" /><?=$this->getCurMenu()->getTitle()?></h6>
    <table   class="table  datatableCat table-hover  ">
		
        <thead>
            <tr>
                <th></th>
			<th>Действия</th>
			<th>Название</th>
                        <th>Товаров</th>
			<th>Путь</th>
                        <th>Текст</th>
		</tr>
        </thead>
        <tbody>
                
	<?php
		foreach($this->categories as $category){
                    
                   $count =  $category->getActiveProductCount();
	?>
		<tr>
                    <td></td>
		<td>
                <a href="<?=$this->path?>shop-categories/edit/id/<?=$category->getId()?>/"><i class="icon ion-ios-create-outline btn-lg"></i></a>
                <?php if($count){ ?>
                <i class="icon ion-ios-close-circle btn-lg"></i>
                    <?php }else{ ?>
                <a href="<?=$this->path?>shop-categories/delete/id/<?=$category->getId()?>/" onclick="return confirm('Удалить категорию?')">
                   <i class="icon ion-ios-close-circle-outline btn-lg"></i>
                </a> 
                    <?php  } ?>
		<a href="<?=$this->path?>shop-categories/moveup/id/<?=$category->getId()?>/"><i class="icon ion-ios-arrow-up btn-lg"></i></a>
		<a href="<?=$this->path?>shop-categories/movedown/id/<?=$category->getId()?>/"><i class="icon ion-ios-arrow-down btn-lg"></i></a>
                 </td>
		<td><?=$category->h1?$category->h1:$category->name?></td>
                <td><?=$count?></td>
		<td><?=$category->getRoutez()?></td>
                <td><?=$category->footer?'Есть':'Нет'?></td>
		</tr>
	
                <?php  } ?>
        </tbody>
        <tfoot>
            <tr>
                 <th></th>
                <th></th>
               <th></th>
    <th></th>
    <th></th>
    <th></th>
            </tr>
        </tfoot>
    </table>
</div>
<script>
    $(function(){
              

	  'use strict';
          var t =  $('.datatableCat').DataTable({
          initComplete: function () {
              var api = this.api();
            api.$('td').click( function () {
                api.search( this.innerHTML ).draw();
            } );
            api.columns().every( function () {
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
        order: [[ 1, 'asc' ]]
        });
 t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    });
</script>