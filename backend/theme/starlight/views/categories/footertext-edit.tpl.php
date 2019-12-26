
    <div class="card card pd-20 pd-sm-40">
    <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
    <div class="card-body">

            
            <form action="" method="post" class="form-horizontal was-validated" role="form">
                <div class="row">
                 <div class="col-sm-12 col-md-12 col-lg-6"> 
                <input type="text" name="id" value="<?=$this->footer_text->id?>" hidden />
        <div class="form-group">
    <label class="sr-only1" for="name">Название</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Название отбора" value="<?=$this->footer_text->name?>" >
  </div>
                </div>
                     <div class="col-sm-12 col-md-12 col-lg-6"> 
  <div class="form-group ">
                    <label class="sr-only1" for="category_id">Категория</label>
                <select name="category_id" class="form-control select2-show-search "  id="category_id" data-placeholder="Выберите категорию">
                    <option value="">Выберите категорию</option>
                    <?php
                    
                    $mas = [];
                foreach (Shopcategories::find('Shopcategories', ['active'=>1]) as $cat) { if($cat->getRoutezGolovna() != 'SALE'){ $mas[$cat->getId()] = $cat->getRoutez();} }
			asort($mas);
                    
                    foreach ($mas as $k=>$c) { ?>

			<option value="<?=$k?>" <?php if($k == $this->footer_text->category_id){echo 'selected';} ?> ><?=$c?></option>
			<?php 
                        
                                } ?>
                </select>
                        </div>
                         </div>
                    <div class="col-sm-12 col-md-12 col-lg-3"> 
       <div class="form-group ">
                    <label class="sr-only1" for="brand_id">Бренд</label>
                <select name="brand_id" class="form-control select2-show-search"  id="brand_id" data-placeholder="Выберите Бренд">
                    <option value="">Выберите бренд</option>
                    <?php
                    
                  
                foreach (Brand::find('Brand', ['hide'=>1]) as $b) { ?>
                    <option value="<?=$b-id?>" <?php if($b->id == $this->footer_text->brand_id){echo 'selected';} ?> ><?=$b->name?></option>
                 <?php   } ?>
                </select>
                        </div>
                        </div>
                     <div class="col-sm-12 col-md-12 col-lg-3"> 
       <div class="form-group ">
                    <label class="sr-only1" for="sezon_id">Сезон</label>
                <select name="sezon_id" class="form-control select2"  id="sezon_id" data-placeholder="Выберите сезон">
                    <option value="">Выберите сезон</option>
                    <?php
                    
                  
                foreach (Shoparticlessezon::find('Shoparticlessezon') as $s) { ?>
                    <option value="<?=$s->id?>" <?php if($s->id == $this->footer_text->sezon_id){echo 'selected';} ?> ><?=$s->name?></option>
                 <?php   } ?>
                </select>
                        </div>
                        </div>
                     <div class="col-sm-12 col-md-12 col-lg-3"> 
       <div class="form-group ">
                    <label class="sr-only1" for="color_id">Цвет</label>
                <select name="color_id" class="form-control select2-show-search"  id="color_id" data-placeholder="Выберите цвет">
                    <option value="">Выберите цвет</option>
                    <?php
                    
                  
                foreach (Shoparticlescolor::find('Shoparticlescolor') as $c) { ?>
                    <option value="<?=$c->id?>" <?php if($c->id == $this->footer_text->color_id){echo 'selected';} ?> ><?=$c->name?></option>
                 <?php   } ?>
                </select>
                        </div>
                        </div>
                     <div class="col-sm-12 col-md-12 col-lg-3"> 
       <div class="form-group ">
                    <label class="sr-only1" for="size_id">Размер</label>
                <select name="size_id" class="form-control select2-show-search"  id="size_id" data-placeholder="Выберите размер">
                    <option value="">Выберите размер</option>
                    <?php
                    
                  
                foreach (Size::find('Size') as $s) { ?>
                    <option value="<?=$s->id?>" <?php if($s->id == $this->footer_text->size_id){echo 'selected';} ?> ><?=$s->size?></option>
                 <?php   } ?>
                </select>
                        </div>
                        </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                         <div class="form-group">
                    <label class="sr-only1" for="text">Текст:</label>
                        <textarea name="text" cols="80" rows="5" class="form-control"  ><?=$this->footer_text->text?></textarea>


            </div>
                        </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                         <div class="form-group">
                    <label class="sr-only1" for="text">Текст укр:</label>
                        <textarea name="text_uk" cols="80" rows="5" class="form-control"  ><?=$this->footer_text->text_uk?></textarea>


            </div>
                        </div>
               


    <button type="submit" class="btn btn-outline-primary" name="save_cat">Сохранить изменения</button>
        
                </div>
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
	   external_plugins: { "filemanager" : "/backend/scripts/filemanager/plugin.min.js"},
	   convert_urls: false
	 });
    
    </script>