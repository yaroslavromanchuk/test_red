<div class="row row-sm mg-x-0">
     <div class="col-sm-12">
<div class="card pd-30">
<h6 class="card-body-title">Форма снятия товара с продажи</h6>
<p>Выберите excel файл. Структура: А-артикул, B-количество на остаток</p>
<form action="" method="POST"  enctype="multipart/form-data">
<div class="form-layout">
            <div class="row mg-b-25">
		<div class="col-lg-3 mg-t-40 mg-lg-t-0">
              <label class="custom-file">
                <input type="file" name="excel_file" class="custom-file-input">
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
            </div>
                    <button class="btn btn-info mg-r-5" name="close_article" type="submit">Снять товар</button>
            </div>
</div>
</form>
</div>
</div>
<div class="col-sm-12">
<div class="card pd-30">
<h6 class="card-body-title">Форма снятия товара на земену артикула</h6>

<p>Еще осталось <?=$this->article['st']?> строк = <?=$this->article['suma']?> единиц. </p>
<!--<p>Выберите excel файл. Структура: А-артикул</p>-->
<form action="" method="POST"  enctype="multipart/form-data">
<div class="form-layout">
            <div class="row mg-b-25">
		<!--<div class="col-lg-3 mg-t-40 mg-lg-t-0">
              <label class="custom-file">
                <input type="file" name="otbor_excel_file" class="custom-file-input">
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
            </div>-->
                    <button class="btn btn-info mg-r-5" name="otbor_artikul" type="submit">Снять 100 шт.</button>
            </div>
</div>
</form>
</div>
</div>
<div class="col-sm-12">
       <div class="card pd-30">
<h6 class="card-body-title">Форма замены артикулов на новые</h6>
<p>Выберите excel файл. Структура: А-sr..., B-new_code</p>
<form action="" method="POST"  enctype="multipart/form-data">
<div class="form-layout">
            <div class="row mg-b-25">
			 <div class="col-lg-3 mg-t-40 mg-lg-t-0">
              <label class="custom-file">
                <input type="file" name="excel_file_artikul" class="custom-file-input">
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
            </div>
                 <button class="btn btn-info mg-r-5" name="edit_artikul" type="submit">Заменить акртикулы</button>
            </div>
</div>
</form>
</div> 
    </div>
    <div class="col-sm-12">
        <?php  if($this->save){ ?>
        <div class="alert alert-success" role="alert"><strong>Успех!</strong> <?=$this->save?></div>
       <?php } ?>
         <?php  if($this->warning){ ?>
        <div class="alert alert-warning" role="alert"><strong>Предупреждение!</strong> <?=$this->warning?></div>
       <?php } ?>
         <?php  if($this->error){ ?>
        <div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> <?=$this->error?></div>
       <?php } ?>
    </div>  
</div>