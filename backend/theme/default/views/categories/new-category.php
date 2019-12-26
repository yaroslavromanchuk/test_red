<img src="<?=$this->getCurMenu()->getImage()?>"  class="page-img" />
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<div class="panel panel-default">
  <div class="panel-heading"><?=$this->category_edit->getRoutez()?></div>
  <form action="" method="post">
  <div class="panel-body">
   <div class="row">
<div class="col-md-12">
<div class="form-group">
<div class="col-xs-2">
    <label >Активность:</label>
	</div>
    <div class="col-xs-2">
         <input name="active" type="checkbox" id="active" name="active" <?php if (strcasecmp($this->category_edit->active,'1') == 0){ echo 'checked="checked"';}?> />
    </div>
	<div class="col-xs-3">
	Товаров в категории: <?=$this->category_edit->getArticles()->count()?>
    </div>
    <div class="col-xs-5">
	Категория уценки: <select name="ucenka_edit_id" class="form-control" id="ucenka_edit_id">
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
     
  </div>
  
  <div class="panel-footer">
 <div class="row">
          <p>
        <label>
          <input type="submit" name="button_save" id="button" value="Сохранить" />
          </label>
      </p>
          
      </div>
  </div>
      </form>
</div>
	
<script  src="<?=$this->files?>scripts/tinymce/tinymce.min.js"></script>
<script >
    
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
	   image_advtab: true ,
	   
	  external_filemanager_path:"<?=$this->files?>scripts/filemanager/",
	   filemanager_title:"Responsive Filemanager" ,
	  external_plugins: { "filemanager" : "<?=$this->files?>scripts/filemanager/plugin.min.js"},
	  convert_urls: false
	 });
</script>

