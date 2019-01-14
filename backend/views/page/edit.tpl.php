<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt="" class="page-img" />
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<?=$this->getCurMenu()->getPageBody()?>
<?php if($this->errors){ ?>
    <div id="errormessage"><img src="<?=SITE_URL?>/img/icons/error.png" alt="" width="32" height="32" class="page-img" />
        <h2>Найдены ошибки:</h2>
        <ul>
            <?php foreach($this->errors as $error){?>
                <li><?=$error?></li>
                <?php } ?>
        </ul>
    </div>  
    <?php
    }
    if($this->saved){?>    
    <div id="pagesaved">
        <img src="<?=SITE_URL?>/img/icons/accept.png" alt="" width="32" height="32" class="page-img" />
        <h1>Данные успешно сохранены</h1>
    </div>
    <?php
    }
?>

<form method="post" action="<?=$this->path?>pages/edit/id/<?=$this->page->getId()?$this->page->getId().'/':''?>" enctype="multipart/form-data">
    <table id="editpage" cellpadding="5" cellspacing="0">
        <?php
            //if($this->page->getTypeId()==1)
            {
            ?>
            <tr>
                <td class="kolom1">Тип меню</td>
                <td>
                    <select name="type_id" class="form-control" >
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
                </td>
            </tr>
            <tr>
                <td class="kolom1">Родительская категория</td>
                <td>
                    <select name="parent_id" class="form-control select">
                        <option value="0"></option>
                        <?php foreach($this->roots as $root) { 
                                $sel = ($root->getId() == $this->page->getParentId()) ? 'selected="selected"' : '';
                                echo '<option type="'.$root->getTypeId().'" value="' . $root->getId() . '" '.$sel.'>' . $root->getName() . '</option>';
                        } ?> 
                    </select>
                </td>
            </tr>
            <?php
            }
        ?>    
        <tr>
            <td class="kolom1">Адрес страницы</td>
            <td><input name="url" class="form-control" type="text" <?php echo $this->page->getId() ? 'disabled' : '';?> class="formfields" id="paginaid" value="<?php echo $this->page->getUrl();?>" /></td>
        </tr>
        <tr>
            <td class="kolom1">Заголовок страницы</td>
            <td><input name="name" class="form-control" type="text" class="formfields" id="paginatitle" value="<?php echo $this->page->getName();?>" /></td>
        </tr>
        <tr>
            <td class="kolom1">Заголовок страницы УКР</td>
            <td><input name="name_uk" class="form-control" type="text" class="formfields" id="paginatitleuk" value="<?php echo $this->page->getNameUk();?>" /></td>
        </tr>          
        <tr>
            <td class="kolom1">Содержание страницы</td>
            <td><textarea name="page_body"  rows="10" style="width: 763px;" class="pagetext" id="paginatext"><?php echo $this->page->getPageBody();?></textarea></td>
        </tr>
        <tr>
            <td class="kolom1">Содержание страницы УКР</td>
            <td><textarea name="page_body_uk" rows="10" style="width: 763px;"  class="pagetext" id="paginatextuk"><?php echo $this->page->getPageBodyUk();?></textarea></td>
        </tr>
        <?php
            if($this->user->isSuperAdmin())
            {
            ?>
            <tr>
                <td class="kolom1">Нельзя удалять</td>
                <td><input name="no_delete" type="checkbox" id="no_delete" value="1" <?php echo $this->page->getNoDelete()? 'checked': '';?> /></td>
            </tr>
            <?php
            }
        ?>     
        <tr>
            <td class="kolom1">Картинка 1</td>
            <td>
				<input name="image1" type="file" />
				<input name="rem_image1" type="submit" value="Удалить" />
                <?php if($this->page->getImage()) {
                        echo '<br/><img src="' . $this->page->getImage() . '" style="max-width:400px" />';
                } ?> 
            </td>
        </tr>
		<tr>
            <td class="kolom1">Картинка 2</td>
            <td>
				<input name="image2" type="file" />
				<input name="rem_image2" type="submit" value="Удалить" />
                <?php if($this->page->getImage2()) {
                        echo '<br/><img src="' . $this->page->getImage2() . '" style="max-width:400px" />';
                } ?> 
            </td>
        </tr>
		<tr>
            <td class="kolom1">Картинка 3</td>
            <td>    
				<input name="image3" type="file" />
				<input name="rem_image3" type="submit" value="Удалить" />
                <?php if($this->page->getImage3()) {
                        echo '<br/><img src="' . $this->page->getImage3() . '" style="max-width:400px" />';
                } ?> 
            </td>
        </tr>
		<tr>
            <td class="kolom1">Картинка 4</td>
            <td>
				<input name="image4" type="file" />
				<input name="rem_image4" type="submit" value="Удалить" />
                <?php if($this->page->getImage4()) {
                        echo '<br/><img src="' . $this->page->getImage4() . '" style="max-width:400px" />';
                } ?> 
            </td>
        </tr>
		<tr>
            <td class="kolom1">Картинка 5</td>
            <td>
				<input name="image5" type="file" />
				<input name="rem_image5" type="submit" value="Удалить" />
                <?php if($this->page->getImage5()) {
                        echo '<br/><img src="' . $this->page->getImage5() . '" style="max-width:400px" />';
                } ?> 
            </td>
        </tr>
        <tr>
            <td class="kolom1">Ключевые слова</td>
            <td><input type="text" name="metatag_keywords" id="metatag_keyword" value="<?php echo $this->page->getMetatagKeywords();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Описание</td>
            <td><input type="text" name="metatag_description" id="metatag_description" value="<?php echo $this->page->getMetatagDescription();?>"></td>
        </tr>          
    </table>
	 <?php
            if($this->user->isSuperAdmin())
            {
                // echo print_r($this->controller);
            ?>
   <!-- -->
   <div class="panel panel-primary">
       <div class="panel-body">
   <div class="row">
   <div class="form-group">
    <label for="controller" class="col-sm-12 col-md-12 col-lg-2 control-label">Контроллер:</label>
    <div class="col-sm-12 col-md-12 col-lg-10">
        <select name="controller" id="controller" class="form-control">
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
	<input name="action" id="action" class="form-control" type="text" value="<?php echo $this->page->getAction() ? $this->page->getAction() : 'index';?>" />
    </div>
  </div>
   <div class="form-group">
    <label for="parameter" class="col-sm-12 col-md-12 col-lg-2 control-label">get параметр:</label>
    <div class="col-sm-12 col-md-12 col-lg-10">
	 <input name="parameter" id="parameter" class="form-control" type="text" value="<?php echo $this->page->getParameter() ? $this->page->getParameter() : '';?>" />
    </div>
  </div>
  <! <div class="form-group">
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
   
       </div>
         </div>
        </div>
    <?php } ?>
    <p>
        <input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить страницу" />
    </p>
</form>


<script  src="<?=$this->files?>scripts/tiny_mce/tiny_mce.js"></script>     
<script > 
    tinyMCE.init({
        // General options
        mode : "exact",
        elements: "paginatext, paginaintro, paginaintrouk, paginatextuk",
        language : "ru",
        theme : "advanced",
        plugins : "table, paste, images, pastehtml",
        theme_advanced_layout_manager : "SimpleLayout",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_buttons1 : "pastehtml, pasteword, separator, bold, italic, underline, separator, justifyleft, justifycenter, justifyright, justifyfull, separator, styleselect, separator, bullist, numlist, separator, undo, redo, separator, link, unlink, anchor, image, help, code, charmap",
        theme_advanced_buttons2 : "tablecontrols, separator, images, separator, forecolor, backcolor, separator, fontselect, fontsizeselect",
        theme_advanced_buttons3 : "",

        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : false,

        paste_use_dialog : false,
        force_br_newlines : true,
        force_p_newlines : false,
        relative_urls : false,
        apply_source_formatting : true,
        remove_script_host : true,

        // Example word content CSS (should be your site CSS) this one removes paragraph margins
        content_css : "<?=SITE_URL?>/standard.css?<?=rand(1,10000)?>",
        external_link_list_url : "<?=SITE_URL?>/admin/pages/tinymce_list/",
        extended_valid_elements : "b,u,i,iframe[name|src|framespacing|border|frameborder|scrolling|title|height|width],object[declare|classid|codebase|data|type|codetype|archive|standby|height|width|usemap|name|tabindex|align|border|hspace|vspace],div[*],p[*]",
        external_image_list_url : "<?=SITE_URL?>/admin/pages/tinymce_imagelist/"

    });
</script> 
<!-- /TinyMCE -->     
