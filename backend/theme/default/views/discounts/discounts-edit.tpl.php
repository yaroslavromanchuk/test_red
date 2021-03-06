 <div class="panel panel-primary">
    <div class="panel-heading"><h3 class="panel-title"><?=$this->discounts->option_text?></h3></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">  
            <form action="" method="post" class="form-horizontal was-validated" role="form">
                <input type="text" name="id" value="<?=$this->discounts->id?>" hidden />
        <div class="form-group">
    <label class="col-sm-12 col-lg-2 control-label" for="option_text">Название</label>
    <div class="col-sm-12 col-lg-10">
    <input type="text" class="form-control" id="option_text" name="option_text" placeholder="Название акции" value="<?=$this->discounts->option_text?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-12 col-lg-2 control-label" for="value">Скидка %</label>
    <div class="col-sm-12 col-lg-10">
    <input type="text" class="form-control" id="value" name="value"  onkeyup=" if(!/^[0-9]*$/.test(this.value)) this.value=''"  placeholder="процент скидки" value="<?=$this->discounts->value?>" style="width: 150px;" required>
      </div>
</div>
      <div class="form-group"> 
          <label class="col-sm-12 col-lg-2 control-label" >Тип скидки</label>
          <div class="col-sm-12 col-lg-10">
  <div class="radio-inline">
  <label>
      <input type="radio" name="type"  value="final" <?=$this->discounts->type == 'final'?'checked':''?> >
          Финишная
        </label>
</div>
<div class="radio-inline">
  <label>
          <input type="radio" name="type"  value="dop" <?=$this->discounts->type == 'dop'?'checked':''?>>
          Дополнительная
        </label>
</div>
              <div class="radio-inline">
  <label>
          <input type="radio" name="type"  value="all" <?=$this->discounts->type == 'all'?'checked':''?>>
          Информационная
        </label>
</div>
          </div>
                </div>
               
    <div class="form-group">
                    <label class="col-sm-12 col-lg-2 control-label" for="action">Распространяется на</label>
                    <div class="col-sm-12 col-lg-10">
                        <select name="action" class="form-control" id="action" required>
                    <option label="Укажите на что акция">Укажите на что акция</option>
                    <option value="article" <?=$this->discounts->action == 'article'?'selected':''?> >Товар</option>
                    <option value="category" <?=$this->discounts->action == 'category'?'selected':''?>>Категория</option>
                    <option value="brand" <?=$this->discounts->action == 'brand'?'selected':''?>>Бренд</option>
                    <option value="sezon" <?=$this->discounts->action == 'sezon'?'selected':''?>>Сезон</option>
                    <option value="all" <?=$this->discounts->action == 'all'?'selected':''?>>Общая информационная</option>
                        </select>
                </div>
    </div>
                 <div class="form-group">
    <label class="col-sm-12 col-lg-2 control-label"  for="komu" data-placement="top" title="" data-tooltip="tooltip" data-original-title="Для кого предназначена акция?">Предназначена для:</label>
   <div class="col-sm-12 col-lg-10">
    <select name="komu" class="form-control" id="komu" required="true">
                    <option value="">Для кого</option>
                    <option value="all"  <?=$this->discounts->komu == 'all'?'selected':''?> >Для всех</option>
                    <option value="user"  <?=$this->discounts->komu == 'user'?'selected':''?> >Авторизированые пользователи</option>
                    <option value="email"  <?=$this->discounts->komu == 'email'?'selected':''?> >Рассылка</option>
                    <option value="promo"  <?=$this->discounts->komu == 'promo'?'selected':''?> >Промокод</option>
                        </select>
   </div>
    
  </div>
                <div id="dop_file" class="form-group">
        <?php if($this->discounts->komu == 'email'){?>
                    <label class="col-sm-12 col-lg-2 control-label" for="email">Track - с рассылки</label><div class="col-sm-12 col-lg-10"><input type="text" class="form-control" id="email" name="email"   placeholder="track" value="<?=$this->discounts->email?>" style="width: 200px;" required></div>
            <?php }elseif($this->discounts->komu == 'promo'){?>
                    <label class="col-sm-12 col-lg-2 control-label" for="promo">Промокод</label><div class="col-sm-12 col-lg-10"><input type="text" class="form-control" id="promo" name="promo"   placeholder="промокод" value="<?=$this->discounts->promo?>" style="width: 200px;" required></div>
            <?php } ?>
    </div>
     <div class="form-group">
         <label class="col-sm-2 col-lg-2 control-label" for="value">Активность</label>
        <div class="col-sm-1">
          <input type="checkbox" name="status" id="status" value="1" <?=$this->discounts->status == 1?'checked':''?>>
</div>
             </div>
                     <div class="form-group">
         <label class="col-sm-1 col-lg-2 control-label" for="timer">Таймер</label>
        <div class="col-sm-1">
          <input type="checkbox" name="timer" id="timer" value="1" <?=$this->discounts->timer == 1?'checked':''?>>
</div>
             </div>
    
    <div class="form-group">
        
    <label class="col-sm-12 col-lg-2 control-label" for="start">Старт</label>
    <div class="col-sm-12 col-lg-2">
    <input type="date" class="form-control" name="start" id="start" value="<?=$this->discounts->start?>" required>
        </div>
        
    <label class="col-sm-12 col-lg-2 control-label" for="end">Финиш</label>
    <div class="col-sm-12 col-lg-2">
    <input type="date" class="form-control" name="end" id="end" value="<?=$this->discounts->end?>" required>
        </div>
  </div>
                <div class="form-group">
                    <label class="col-sm-12 col-lg-1 control-label" for="intro">Превью:</label>
                    <div class="col-sm-12">
                        <textarea name="intro" cols="80" rows="5" class="form-control" id="intro" ><?=$this->discounts->intro?></textarea>
                </div>
                    </div>
        <div class="form-group">
                    <label class="col-sm-12 col-lg-1 control-label" for="content">Содержимое:</label>
                    <div class="col-sm-12">
                        <textarea name="content" cols="80" rows="5"  class="form-control" id="content" ><?=$this->discounts->content?></textarea>
                </div>
  
            </div>
                <div class="form-group text-center p-t-3">
                <button type="submit" class="btn btn-primary btn-lg" name="save_cat">Сохранить изменения</button>
                </div>
    </form>
                <hr>
            </div>
            
      </div>
        <script>
          var email ='<label class="col-sm-12 col-lg-2 control-label" for="email">Track - с рассылки</label><div class="col-sm-12 col-lg-10"><input type="text" class="form-control" id="email" name="email"   placeholder="track" value="<?=$this->discounts->email?>" style="width: 200px;" required></div>';
          var promo = '<label class="col-sm-12 col-lg-2 control-label" for="promo">Промокод</label><div class="col-sm-12 col-lg-10"><input type="text" class="form-control" id="promo" name="promo"   placeholder="промокод" value="<?=$this->discounts->promo?>" style="width: 200px;" required></div>';
              $("#komu").change(function() {
  switch($(this).val()){
      case 'email': $('#dop_file').html(email);break;
      case 'promo': $('#dop_file').html(promo); break;
      default: $('#dop_file').html('');
  }
});
        </script>
        <div class="row">
            <div class="col col-sm-12 col-md-12 col-lg-12">
<?php
if($this->errors){ ?>
    <div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <?php
   foreach ($this->errors as $r=>$v) { ?>
 <strong>Ошибка!</strong> <?=$v?> <br>
<?php
   }
  ?>
 
</div>
  <?php  
}

if($this->discounts->getOptions()){ ?>
   <table cellpadding="4" cellspacing="0" id="order-articles" class="table " >
        <thead class="thead-light">
            <tr>
              <?php if($this->discounts->action == 'article'){ ?>  <td><strong>Товары</strong></td> <?php } ?>
              <?php if($this->discounts->action == 'category'){ ?>   <td><strong>Категории</strong></td><?php } ?>
              <?php if($this->discounts->action == 'brand'){ ?>   <td><strong>Бренды</strong></td><?php } ?>
              <?php if($this->discounts->action == 'sezon'){ ?>   <td><strong>Сезоны</strong></td><?php } ?>
                <td><strong>Действия</strong></td>
            </tr>
        </thead>
        <tbody>
<?php foreach($this->discounts->getOptions() as $d){ ?>
            <tr>
             <?php if($this->discounts->action == 'article'){ ?>   <td><?php if($d->article_id){
                    $a = new Shoparticles($d->article_id);
                    
                    echo '<a href="'.$a->getPath().'" target="_blank"><img src="'.$a->getImagePath('small_basket').'">'.$a->getTitle().'</a>';
             }?></td> <?php } ?>
             <?php if($this->discounts->action == 'category'){ ?>   <td><?php if($d->category_id){ $c = new Shopcategories($d->category_id); echo $c->getRoutez(); }?></td><?php } ?>
             <?php if($this->discounts->action == 'brand'){ ?>   <td><?php if($d->brand_id){ $b = new Brand($d->brand_id); echo $b->getName();  }?></td><?php } ?>
             <?php if($this->discounts->action == 'sezon'){ ?>   <td><?php if($d->sezon_id){ $c = new Shoparticlessezon($d->sezon_id); echo $c->name; }?></td><?php } ?>
          <?php if($d->id){ ?>      <td><form action="" name="del-<?=$d->id?>" method="post">
                                  <input type="text" name="id" value="<?=$d->id?>" hidden />
                               <!-- <button type="submit" name="dell" >Удалить</button>-->
                                <input type="submit" value="Удалить" name="dell" class="btn btn-danger btn-sm" />
                                
                    </form>
                    
                </td> <?php } ?>
            </tr>
<?php } ?>
        </tbody>
    </table>
<?php } ?>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <form name="new_param" method="POST" action="" class="was-validated" enctype="multipart/form-data">
                <div class="row">
                    <input type="text" name="option_id" value="<?=$this->discounts->id?>" hidden />
                    <div class="col-lg-12 col-sm-12">
                        <?php if($this->discounts->action == 'article'){ ?>
    <div class="form-group">
    <label class="sr-only1" for="article_id">SKU Товара(можно указать через "," несколько SKU)</label>
   <input type="text" class="form-control" id="article_id" name="article_id" placeholder="id">
    <!--<textarea  class="form-control" id="article_id" name="article_id" placeholder="id"></textarea>-->
  </div>
    <div class="form-group">
    <label class="sr-only1" for="excel_file">Загрузить SKU с Excel файла</label>
    <input type="file" class="form-control" id="excel_file" name="excel_file"  />
  </div>
                        <?php }elseif($this->discounts->action == 'category'){ ?>
                <div class="form-group inline-block">
                    <label class="sr-only1" for="category_id">Категория</label>
                <select name="category_id[]" class="form-control select2" multiple id="category_id">
                    <option value="">Выберите категорию</option>
                    <?php
                    
                    $mas = [];
                foreach (Shopcategories::find('Shopcategories', ['active'=>1, 'id not in(85, 267, 106)']) as $cat){
                    if($cat->getRoutezGolovna() != 'SALE'){
                        $mas[$cat->getId()] = $cat->getRoutez(); 
                        
                    } 
                    
                    }
			asort($mas);
                    
                    foreach ($mas as $k=>$c) { ?>

			<option value="<?=$k?>" ><?=$c?></option>
			<?php 
                        
                                } ?>
                </select>
                        </div>
                        <?php }elseif($this->discounts->action == 'brand'){ ?>
                <div class="form-group inline-block">
                      <label class="sr-only1" for="brand_id">Бренд</label>
                      <select name="brand_id" class="form-control" id="brand_id">
                    <option value="">Выберите бренд</option>
                    <?php foreach (Brand::find('Brand') as $b) {
				if ($b->getName() != '') { ?>
			<option value="<?=$b->id?>" ><?=$b->getName()?></option>
			<?php } } ?>
                </select>
                        </div>
                        
                        <?php  }elseif($this->discounts->action == 'sezon'){ ?>
                            <div class="form-group inline-block">
                      <label class="sr-only1" for="sezon_id">Сезон</label>
                      <select name="sezon_id" class="form-control" id="sezon_id">
                    <option value="">Выберите сезон</option>
                    <?php foreach (Shoparticlessezon::find('Shoparticlessezon') as $b) {
				if ($b->getName() != '') { ?>
			<option value="<?=$b->id?>" ><?=$b->getName()?></option>
			<?php } } ?>
                </select>
                        </div>
                        <?php } ?>
            </div>
                </div>
           
           
            <input type="submit" value="Добавить" name="save" class="btn btn-info" />
        </form>
    </div>
</div>
<script src="<?=$this->files?>scripts/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
		selector: "textarea",
		//width: 750,
		height: 400,
		language : 'ru',
		plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			 "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
	   ],
	   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
	   toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | fontsizeselect | preview | code ",
	   image_advtab: true ,
	   
	   external_filemanager_path:"/backend/scripts/filemanager/",
	   filemanager_title:"Responsive Filemanager" ,
           filemanager_subfolder: "images/RED_ua/news/",
           filemanager_access_key: "nia",
	   external_plugins: { "filemanager" : "/backend/scripts/filemanager/plugin.min.js"},
	   convert_urls: false
	 });
    
    </script>