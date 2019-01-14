<div class="sl-pagebody">
    <div class="card card pd-20 pd-sm-40">
    <h6 class="card-body-title"><?=$this->category_edit->getRoutez()?></h6>
  <form action="" method="post">
  <div class="card-body">
   <div class="row">
<div class="col-md-12">
<div class="form-group">
<div class="col-xs-4">
    <label >Активность:</label>
	</div>
    <div class="col-xs-4">
         <input name="active" type="checkbox" id="active" name="active" <?php if (strcasecmp($this->category_edit->active,'1') == 0){ echo 'checked="checked"';}?> />
    </div>
	<div class="col-xs-4">
	Товаров в категории: <?=$this->category_edit->getArticles()->count()?>
    </div>
    <div class="col-xs-12">
	Категория уценки: <select name="usenca_category" class="form-control" >
            <option value="" selected>Выберите категорию...</option>
<?php
    foreach (wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1, 'usenca_category'=>0)) as $value) {
        if($value->getRoutezGolovna() === 'SALE'){ ?>
            <option value="<?=$value->id?>" <?php if ($value->id == $this->category_edit->getUsencaCategory()){ echo "selected";}?>><?=$value->getRoutez()?></option>
                <?php

                            }
                  }
			?>
          </select>
    </div>

  </div>
</div>
<div class="col-md-12" >
<div class="form-group">
<div class="col-xs-2">
    <label >Название Рус:</label>
	</div>
    <div class="col-xs-10">
	<input type="text" class="form-control" value="<?=$this->category_edit->name?>" name="name" placeholder="Название">
    </div>
  </div>
</div>
<div class="col-md-12" >
<div class="form-group">
<div class="col-xs-2">
    <label >Название Укр:</label>
	</div>
    <div class="col-xs-10">
	<input type="text" class="form-control" value="<?=$this->category_edit->name_uk?>" name="name_uk" placeholder="Название">
    </div>
  </div>
</div>
  
       
<div class="col-md-12" >
<div class="form-group">
<div class="col-xs-2">
    <label >H1 заголовок Рус:</label>
	</div>
    <div class="col-xs-10">
	<input type="text" class="form-control" value="<?=$this->category_edit->h1?>" name="h1" placeholder="Заголовок h1">
    </div>
  </div>
</div>
<div class="col-md-12" >
<div class="form-group">
<div class="col-xs-2">
    <label >H1 заголовок Укр:</label>
	</div>
    <div class="col-xs-10">
	<input type="text" class="form-control" value="<?=$this->category_edit->h1_uk?>" name="h1_uk" placeholder="Заголовок h1">
    </div>
  </div>
</div>       
     
       
<div class="col-md-12" >
<div class="form-group">
<div class="col-xs-2">
    <label >Тайтл Рус:</label>
	</div>
    <div class="col-xs-10">
	<input type="text" class="form-control" value="<?=$this->category_edit->title?>" name="title" placeholder="Заголовок Тайтл">
    </div>
  </div>
</div>
<div class="col-md-12" >
<div class="form-group">
<div class="col-xs-2">
    <label >Тайтл Укр:</label>
	</div>
    <div class="col-xs-10">
	<input type="text" class="form-control" value="<?=$this->category_edit->title_uk?>" name="title_uk" placeholder="Заголовок Тайтл">
    </div>
  </div>
</div>
       
   </div> 
   <div class="row" style="margin-top:10px;">
<div class="col-md-12">
<div class="form-group">
<div class="col-xs-2">
    <label >Описание Рус:</label>
	</div>
    <div class="col-xs-10">
	<textarea name="description" class="form-control"><?=$this->category_edit->getDescription()?></textarea>
    </div>
  </div>
</div>
<div class="col-md-12">
<div class="form-group">
<div class="col-xs-2">
    <label >Описание Укр:</label>
	</div>
    <div class="col-xs-10">
	<textarea name="description_uk" class="form-control" ><?php echo $this->category_edit->getDescriptionUk();?></textarea>
    </div>
  </div>
</div>
</div>
      
   <div class="row" style="margin-top:10px;">
<div class="col-md-12">
<div class="form-group">
<div class="col-xs-2">
    <label >Футтер Рус:</label>
	</div>
    <div class="col-xs-10">
	<textarea name="footer" class="form-control"><?=$this->category_edit->getFooter()?></textarea>
    </div>
  </div>
</div>
<div class="col-md-12">
<div class="form-group">
<div class="col-xs-2">
    <label >Футтер Укр:</label>
	</div>
    <div class="col-xs-10">
	<textarea name="footer_uk" class="form-control" ><?=$this->category_edit->getFooterUk()?></textarea>
    </div>
  </div>
</div>
</div>      
      <div class="row">
          <p>
        <label>
            <input type="submit" name="button_save" class="btn btn-primary btn-lg" id="button" value="Сохранить" />
          </label>
      </p>
          
      </div>
  </div>
  </form>
  <div class="card-footer">
      <form name="add_category" class="form-inline" id="add_category" action="" method="post">
	<div class="row">
	<div class="col-md-12 text-left">
	<input type="text" hidden id="parent_id" name="parent_id" value="<?=$this->category_edit->id?>">
	<label for="name">Создание подкатегории:</label>
<div class="input-group">
  <span class="input-group-addon"><?=$this->category_edit->getRoutez()?> :</span>
  <input type="text" class="form-control" id="category_name"  name="category_name" aria-describedby="name">
<span class="input-group-btn">
    <button class="btn btn-primary"  type="submit"  name="new_cat">Создать</button>
      </span>
</div>  
  </div>
  </div>
	</form>
  </div>
</div>
</div>	
<script  src="<?=$this->files?>scripts/tinymce/tinymce.min.js"></script>
<script>
	tinymce.init({
		selector: "textarea",
		//width: 750,
		height: 300,
		language : 'ru',
		plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			 "table contextmenu directionality emoticons paste textcolor  code"
	   ],
	   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
	   toolbar2: "| link unlink anchor  | forecolor backcolor  | fontsizeselect | preview | code ",
	  // image_advtab: true ,
	   
	//  external_filemanager_path:"<?=$this->files?>scripts/filemanager/",
	 //  filemanager_title:"Responsive Filemanager" ,
	 // external_plugins: { "filemanager" : "<?=$this->files?>scripts/filemanager/plugin.min.js"},
	//  convert_urls: false
	 });
</script>