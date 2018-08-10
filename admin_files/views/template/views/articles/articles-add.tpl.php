 <link href="<?=$this->files?>views/template/lib/highlightjs/github.css" rel="stylesheet">
    <link href="<?=$this->files?>views/template/lib/datatables/jquery.dataTables.css" rel="stylesheet">
<div class="sl-pagebody">
<!--
<div class="sl-page-title">
          <h5><?=$this->getCurMenu()->getTitle()?></h5>
          <p><?=$this->getCurMenu()->getPageBody()?></p>
</div>-->
<?php if($this->save){ ?>
<div class="alert alert-success <?php if($this->save) echo 'show';?>"  role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<span class="sr-only1"><?=$this->save?></span>
</div>
<?php } ?>
<?php if($this->errors){ ?>
<div class="alert alert-danger <?php if($this->errors) echo 'show';?>"  role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<span class="sr-only1">Возникли ошибки:</span>
    <ul>
		<?php foreach ($this->errors as $error) { ?><li><?=$error?></li><?php } ?>
	</ul>
</div>
<?php } ?>
<div class="card pd-10">
<h6 class="card-body-title">Форма загрузки товара</h6>
<p class="mg-b-20 mg-sm-b-30">Здесь Вы можете загрузить новый товар, посмотреть уже загруженый и добавить содержимое.</p>

<form action="<?=$this->path?>articles-add/listarticles/search/" method="POST" id="editForm">
<div class="row mg-b-25">
<div class="col-lg-4 mg-t-20 mg-lg-t-0">
                <div class="form-group  mg-b-0">
                  <select class="form-control select2" name="status" data-placeholder="Статус товара">
				  <option value="">Статус</option>
				  <?php if($this->status){
				foreach($this->status as $s){ ?>
                    <option value="<?=$s->id?>"><?=$s->name?></option>
					<?php } } ?>
                  </select>
                </div><!-- form-group -->
              </div><!-- col-4 -->
<div class="col-lg-4 mg-t-20 mg-lg-t-0">
              <div class="input-group">
                <input type="text" name="code" class="form-control" placeholder="Накладная...">
                <span class="input-group-btn">
                  <button class="btn bd bg-white tx-gray-600" type="submit"><i class="fa fa-search"></i></button>
                </span>
              </div><!-- input-group -->
			
            </div>
	  </div>		
  </form>

</div>
<div class="card pd-20 pd-sm-40 mg-t-50">
          <h6 class="card-body-title">Загрузить новую накладную</h6>
          <p class="mg-b-20 mg-sm-b-30">Выберите файл наскладной и нажмите "Загрузить".</p>
		  <form action="<?=$this->path?>articles-add/loadexcel/load/" method="POST"  enctype="multipart/form-data">
<div class="form-layout">
            <div class="row mg-b-25">
			 <div class="col-lg-3 mg-t-40 mg-lg-t-0">
              <label class="custom-file">
                <input type="file" name="excel_file" class="custom-file-input">
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
            </div><!-- col -->
			<div class="col-lg-3">
              <label class="rdiobox">
                <input name="version" type="radio" value="1">
                <span>Старая версия</span>
              </label>
			   <label class="rdiobox">
                <input name="version" type="radio" value="2">
                <span>Новая версия</span>
              </label>
            </div><!-- col-3 -->
            </div><!-- row -->

            <div class="form-layout-footer">
              <button class="btn btn-info mg-r-5" type="submit">Загрузить</button>
              <button class="btn btn-secondary">Очистить</button>
            </div><!-- form-layout-footer -->
          </div>
		  </form>
		 
        </div>
</div>
    <script src="<?=$this->files?>views/template/lib/datatables/jquery.dataTables.js"></script>
    <script src="<?=$this->files?>views/template/lib/datatables-responsive/dataTables.responsive.js"></script>
    <script>
      $(function(){
        'use strict';

       /* $('#datatable1').DataTable({
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
        });*/

     /*   $('#datatable1').DataTable({
          bLengthChange: false,
          searching: false,
          responsive: true
        });
		*/

        // Select2
       // $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>	