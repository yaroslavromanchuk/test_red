<?php if($this->errors){ ?>
<div class="alert alert-danger alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      <img src="/img/icons/error.png" alt="" class="page-img" /><strong>Найдены ошибки!</strong>
      <hr>
      <ul>
            <?php foreach($this->errors as $error){?>
                <li><?=$error?></li>
                <?php } ?>
        </ul>
    </div>  
    <?php } ?>
   <?php if($this->saved){ ?>
<div class="alert alert-success alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      <img src="/img/icons/accept.png" alt="" width="32" height="32" class="page-img" /><strong>Данные успешно сохранены!</strong>
    </div>
    <?php } ?>
<form method="post" action="<?=$this->path?>pages/edit/id/<?=$this->page->getId()?$this->page->getId().'/':''?>" id="form-edit" enctype="multipart/form-data" class="form-horizontal was-validated" role="form" data-toggle="validator">
    <div class="panel panel-primary">
    <div class="panel-heading"><h3 class="panel-title"><?=$this->getCurMenu()->getTitle()?></h3></div>
    <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-12 col-md-12 col-lg-2 control-label">Тип меню:</label>
                <div class="col-sm-12 col-md-12 col-lg-10">
                    <select name="type_id" class="form-control " required >
                        <option value="0"></option>
                        <?php foreach($this->menuTypes as $menuType) { 
                                $sel = ($menuType->getId() == $this->page->getTypeId()) ? 'selected="selected"' : '';
                                echo '<option value="' . $menuType->getId() . '" '.$sel.'>' . $menuType->getName() . '</option>';
                        } ?> 
                    </select>
                     <script >
                       
                        function correctMenuTypes(typeId, clear)
                        {
                            $('select[name=parent_id]').empty();
                            var tmp = $('option[value=0],option[type='+typeId+']', gi_select).clone();
                            $('select[name=parent_id]').html(tmp);
                            if (clear)
                                $('select[name=parent_id]').val(0);
                        }
                       var gi_select;
                       /* $(function(e){
                            gi_select = $('select[name=parent_id]').clone();
                            gi_select.attr('name', 'parent_id_clone');
                            correctMenuTypes('<?=$this->page->getTypeId()?>', false);
                        });*/
                      
                    </script>
                </div>
            </div>
             <div class="form-group">
                <label class="col-sm-12 col-md-12 col-lg-2 control-label">Родительская:</label>
                <div class="col-sm-12 col-md-12 col-lg-10">
                    <select name="parent_id" class="form-control  required" >
                        <option value="0"></option>
                        <?php foreach($this->roots as $root) { 
                                $sel = ($root->getId() == $this->page->getParentId()) ? 'selected="selected"' : '';
                                echo '<option type="'.$root->getTypeId().'" value="' . $root->getId() . '" '.$sel.'>' . $root->getName() . '</option>';
                        } ?> 
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-12 col-md-12 col-lg-2 control-label">URL:</label>
                <div class="col-sm-12 col-md-12 col-lg-10">
                   <input name="url" class="form-control" type="text" <?php echo $this->page->getId() ? 'disabled' : '';?> class="formfields" id="paginaid" required value="<?php echo $this->page->getUrl();?>" />
                </div>
            </div>
             <?php
            if($this->user->isSuperAdmin()){ ?>
<div class="form-group">
    <label  class="col-sm-12 col-md-12 col-lg-2 control-label">Нельзя удалять:</label>
    <div class="col-sm-12 col-md-12 col-lg-10">
	<input name="no_delete" type="checkbox" id="no_delete" value="1" <?php echo $this->page->getNoDelete()? 'checked': '';?> />
    </div>
  </div>
   <div class="form-group">
    <label for="controller" class="col-sm-12 col-md-12 col-lg-2 control-label">Контроллер:</label>
    <div class="col-sm-12 col-md-12 col-lg-10">
        <select name="controller" id="controller" required class="form-control ">
        <?php if($this->page->getController()){
                    echo '<option>'.$this->page->getController().'</option>';
                }else{
                    foreach ($this->controller as $c) {
                    ?>
                     <option value="<?=$c->controller?>"><?=$c->controller?></option>
                    <?php }
                    } ?>
    </select>
    </div>
  </div>
   <div class="form-group">
    <label for="action" class="col-sm-12 col-md-12 col-lg-2 control-label">Метод:</label>
    <div class="col-sm-12 col-md-12 col-lg-10">
	<input name="action" id="action" class="form-control" required type="text" value="<?php echo $this->page->getAction() ? $this->page->getAction() : 'index';?>" />
    </div>
  </div>
   <div class="form-group">
    <label for="parameter" class="col-sm-12 col-md-12 col-lg-2 control-label">get параметр:</label>
    <div class="col-sm-12 col-md-12 col-lg-10">
	 <input name="parameter" id="parameter" class="form-control" type="text" value="<?php echo $this->page->getParameter() ? $this->page->getParameter() : '';?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="section" class="col-sm-12 col-md-12 col-lg-2 control-label">Секция:</label>
    <div class="col-sm-12 col-md-12 col-lg-10">
	<select name="section" id="section" class="form-control">
            
    		<?php if($this->page->getSection()){
                    echo '<option value="'.$this->page->getSection().'">'.$this->page->getSection().'</option>';
                    
                } else{
                    echo '<option value="0"></option>';
    foreach ($this->section as $s) { ?>
        <option value="<?=$s->id?>"><?=$s->getName()?></option>
   <?php  }
   } ?>
    </select>
    </div>
   </div>

    <?php } ?>
       <div class="form-group">
    <label for="action" class="col-sm-12 col-md-12 col-lg-2 control-label">Картинка:</label>
    <div class="col-sm-12 col-md-12 col-lg-10">
        <input name="image1" class="form-control btn-sm" style="width: 260px;display: inline-block;" type="file" />
        <input name="rem_image1" class="btn btn-sm btn-danger" type="submit" value="Удалить" />
                <?php if($this->page->getImage()) {
                        echo '<br/><img src="' . $this->page->getImage() . '" style="max-width:400px" />';
                } ?>
    </div>
  </div> 
       
          <ul class="nav nav-tabs">
			<li class="nav-item active"><a class="nav-link active show" style="font-size: 14px;" href="#ru" data-toggle="tab" aria-expanded="true">Русский</a></li>
                        <li class="nav-item"><a class="nav-link" style="font-size: 14px;" href="#uk" data-toggle="tab">Українська</a></li>
	</ul>  
        <div class="tab-content" style="border-left: 1px solid #ddd;border-right: 1px solid #ddd;border-bottom: 1px solid #ddd;padding: 20px;">
                <div id="ru" class="tab-pane fade  active in">
                    <div class="form-group">
                  <label class="form-control-label">H1: <span class="tx-danger">*</span></label>
                  <input name="name" class="form-control" type="text" class="form-control" id="paginatitle" required value="<?php echo $this->page->getName();?>" />
                </div>
                    <div class="form-group">
                  <label class="form-control-label">Ключевые слова: </label>
                <input type="text" name="metatag_keywords" id="metatag_keyword"  class="form-control" value="<?php echo $this->page->getMetatagKeywords();?>"/>
                </div>
                     <div class="form-group">
                  <label class="form-control-label">Описание: </label>
                  <textarea name="metatag_description"  rows="5"  class="form-control" id="metatag_description"><?php echo $this->page->getMetatagDescription();?></textarea>
                </div>
                     <div class="form-group">
                  <label class="form-control-label">Содержание страницы:</label>
                <textarea name="page_body"  rows="10"   class="form-control" id="paginatext"><?php echo $this->page->getPageBody();?></textarea>
                </div>
                </div>
                <div id="uk" class="tab-pane fade">
                    <div class="form-group">
                  <label class="form-control-label">H1: <span class="tx-danger">*</span></label>
                 <input name="name_uk" class="form-control" type="text"  class="form-control" required id="paginatitleuk" value="<?php echo $this->page->getNameUk();?>" />
                </div>
                    <div class="form-group">
                  <label class="form-control-label">Ключевые слова: </label>
                <input type="text" name="metatag_keywords_uk" id="metatag_keyword_uk"  class="form-control" value="<?php echo $this->page->getMetatagKeywordsUk();?>"/>
                </div>
                    <div class="form-group">
                  <label class="form-control-label">Описание: </label>
                  <textarea name="metatag_description_uk"  rows="5"  class="form-control" id="metatag_description_uk"><?php echo $this->page->getMetatagDescriptionUk();?></textarea>
                </div>
                    <div class="form-group">
                  <label class="form-control-label">Содержание страницы:</label>
                 <textarea name="page_body_uk" rows="10" s  class="form-control"  id="paginatextuk"><?php echo $this->page->getPageBodyUk();?></textarea>
                </div>
                </div>
            </div>
    </div>
    <div class="panel-footer">
<div class="form-group">
    <div class="col-xs-offset-1 col-xs-11" style="text-align:center;">
      <button type="submit" name="savepage"  class="btn  btn-lg btn-dark"><i class="glyphicon glyphicon-save-file" aria-hidden="true"></i> Сохранить страницу</button>
  </div>
  </div>
  </div>
</div>
</form>
<script  src="<?=$this->files?>scripts/validator.min.js"></script>  
<script  src="<?=$this->files?>scripts/tinymce/tinymce.min.js"></script>
<script> 
    $('#form-edit').validator();
    $(document).ready(function(){
 tinymce.init({ 
mode : "exact",
elements : "metatag_description_uk, metatag_description, paginatextuk, paginatext",
language : 'ru',
width: 1000,
height: 500,
plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			 "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
	   ],
	   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
	   toolbar2: "| responsivefilemanager | link unlink anchor | image  media | forecolor backcolor  | fontsizeselect | preview | code",
	    image_advtab: true,
            
            external_filemanager_path:"/backend/scripts/filemanager/",
	   filemanager_title:"Responsive Filemanager",
           filemanager_subfolder: "page/",
           filemanager_access_key: "yarik",
	   external_plugins: { "filemanager" : "/backend/scripts/filemanager/plugin.min.js"},
	   convert_urls: false
 });
 
  });
</script> 
  
