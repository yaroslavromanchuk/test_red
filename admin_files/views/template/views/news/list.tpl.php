
    <link href="<?=$this->files?>views/template/lib/datatables/jquery.dataTables.css" rel="stylesheet">
    <link href="<?=$this->files?>views/template/lib/select2/css/select2.min.css" rel="stylesheet">
<div class="card pd-20">
          <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?>
<a href="<?=$this->path?>news/edit/" data-toggle="tooltip"  title="Добавить новость">   <i class="menu-item-icon icon ion-ios-compose-outline tx-22 pd-5" ></i></a></h6>
          <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-15p">Действия</th>
                  <th class="wd-15p">Заголовок</th>
                  <th class="wd-20p">Статус</th>
                  <th class="wd-15p">Дата</th>
                </tr>
              </thead>
              <tbody>
			 <?php  foreach($this->getNews() as $n ){ ?>
			 <tr>
                  <td>
				  <a href="<?=$n->getPath()?>" target="_blank" data-toggle="tooltip" data-placement="right" title="Смотреть">
				  <i class="menu-item-icon icon ion-ios-monitor-outline tx-22 pd-5"></i>
				  </a>
				  <a href="<?=$this->path?>news/edit/id/<?=$n->getId()?>/" data-toggle="tooltip"  title="Редактировать">
				  <i class="menu-item-icon icon ion-ios-paper-outline tx-22 pd-5" ></i>
				  </a>
				  <a href="<?=$this->path?>news/delete/id/<?=$n->getId()?>/" onclick="return confirm('Вы действительно хотите удалить новость?')" data-toggle="tooltip" title="Удалить" >
				 <i class="menu-item-icon icon ion-ios-trash-outline tx-danger tx-22 pd-5"></i>
				  </a>
				  </td>
                  <td><?php echo $n->getTitle();?></td>
                  <td><?php echo $n->getStatusText();?></td>
                  <td><?php echo $n->getEndDatetime();?></td>
                </tr>
			 <?php }?>
                
              </tbody>
            </table>
          </div><!-- table-wrapper -->
        </div><!-- card -->
 <script src="<?=$this->files?>views/template/lib/highlightjs/highlight.pack.js"></script>
    <script src="<?=$this->files?>views/template/lib/datatables/jquery.dataTables.js"></script>
    <script src="<?=$this->files?>views/template/lib/datatables-responsive/dataTables.responsive.js"></script>
    <script src="<?=$this->files?>views/template/lib/select2/js/select2.min.js"></script>
    <script>
      $(function(){
        'use strict';

        $('#datatable1').DataTable({
          responsive: true,
          language: {
            searchPlaceholder: 'Поиск в таблице...',
            sSearch: '',
            lengthMenu: '_MENU_ записей/страница',
			processing: "Traitement en cours...",
			info:       "Записи с _START_ по _END_ из _TOTAL_ ",
			paginate: {
            first:      "Premier",
            previous:   "Придведущая",
            next:       "Следующая",
            last:       "Dernier"
        },
          }
        });

     /*   $('#datatable2').DataTable({
          bLengthChange: false,
          searching: false,
          responsive: true
        });*/

        // Select2
        //$('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>	