<div class="row row-sm mg-x-0">
     <div class="col-sm-12 col-lg-6">
<div class="card pd-30 mb-3">
<h6 class="card-body-title">Форма снятия товара с продажи по артикулу</h6>
<p class="card-subtitle mb-2 text-muted">В фале указать штрихкод | сколько шт. <b>оставить!</b></p>
<p>Выберите excel файл. Структура: А-артикул, B-количество на остаток</p>
<form action="" method="POST"  enctype="multipart/form-data">
<div class="form-layout">
            <div class="row mg-b-25">
		<div class="col-lg-6 mg-t-40 mg-lg-t-0">
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
<div class="col-sm-12 col-lg-6">
<div class="card pd-30 mb-3">
<h6 class="card-body-title">Форма снятия товара с продажи по акртикулу</h6>
<p class="card-subtitle mb-2 text-muted">В фале указать штрихкод | сколько шт. <b>снять!</b></p>
<p>Выберите excel файл. Структура: А-артикул, B-количество снять</p>
<form action="" method="POST"  enctype="multipart/form-data">
<div class="form-layout">
            <div class="row mg-b-25">
		<div class="col-lg-6 mg-t-40 mg-lg-t-0">
              <label class="custom-file">
                <input type="file" name="excel_file" class="custom-file-input">
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
            </div>
                    <button class="btn btn-info mg-r-5" name="close_article_count_snat" type="submit">Снять товар</button>
            </div>
</div>
</form>
</div>
</div>
    <div class="col-sm-12 col-lg-6">
<div class="card pd-30 mb-3">
<h6 class="card-body-title">Форма снятия товара с продажи по ID_товара</h6>
<p class="card-subtitle mb-2 text-muted">В фале указать <b>id - товара!</b></p>
<p>Выберите excel файл. Структура: А(или "1", как excel отображает): id - товара</p>
<form action="" method="POST"  enctype="multipart/form-data">
<div class="form-layout">
            <div class="row mg-b-25">
		<div class="col-lg-6 mg-t-40 mg-lg-t-0">
              <label class="custom-file">
                <input type="file" name="excel_file" class="custom-file-input">
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
            </div>
                    <button class="btn btn-info mg-r-5" name="snatie_to_id_article" type="submit">Снять товар</button>
            </div>
</div>
</form>
</div>
</div>
    <!--
<div class="col-sm-12 col-lg-6">
<div class="card pd-30 mb-3">
<h6 class="card-body-title">Форма снятия товара на земену артикула</h6>

<p>Еще осталось <?=isset($this->article['st'])?$this->article['st']:''?> строк = <?=isset($this->article['suma'])?$this->article['suma']:''?> единиц. </p>
<form action="" method="POST"  enctype="multipart/form-data">
<div class="form-layout">
            <div class="row mg-b-25">
		<div class="col-lg-3 mg-t-40 mg-lg-t-0">
              <label class="custom-file">
                <input type="file" name="otbor_excel_file" class="custom-file-input">
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
            </div>
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
    -->
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