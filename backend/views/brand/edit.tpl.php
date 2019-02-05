<div class="sl-pagebody ">
       <?php if ($this->errors) { ?>
<div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <strong>Ошибка!</strong>
  <ul> <?php foreach ($this->errors as $error) { ?><li><?=$error?></li><?php } ?> </ul>
</div>
<?php } ?>
      <?php if ($this->saved) { ?>
<div class="alert alert-success alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <strong>Запись сохранена.</strong>
</div>
<?php } ?>   
    
<div class="card card pd-20 pd-sm-40">
   <form method="POST" action="<?=$this->path?>brand/edit/id/<?=(int)$this->sub->getId()?>/" enctype="multipart/form-data" >
  <div class="card-body">
      <div class="row">
          <div class="col-lg-3">
    <div class="form-group inline">
    <label class="sr-only1" for="paginatitle" >Название</label>
    <input name="name" type="text" class="form-control" id="paginatitle" required="" value="<?=$this->sub->getName()?>"/>
  </div>
    </div>
          <div class="col-lg-3">
    <div class="form-group inline">
    <label class="sr-only1" for="country_brand" >Страна бренда</label>
   <input name="country_brand" type="text" class="form-control" id="country_brand" required=""
                       value="<?=$this->sub->getCountryBrand()?>"/>
  </div>
          </div>
                       <div class="col-lg-3">
    <div class="form-group inline">
    <label class="sr-only1" for="top" >Грейд</label>
    <select class="form-control select2" name="greyd" id="greyd" data-placeholder="Грейд" >
                            <option label="Грейд"></option>
                            <option value="1" <?php if($this->sub->greyd == 1){?>selected <?php } ?> >1</option>
                            <option value="2" <?php if($this->sub->greyd == 2){?>selected <?php } ?>>2</option>
                            <option value="3" <?php if($this->sub->greyd == 3){?>selected <?php } ?>>3</option>
                            <option value="4" <?php if($this->sub->greyd == 4){?>selected <?php } ?>>4</option>
                            <option value="5" <?php if($this->sub->greyd == 5){?>selected <?php } ?>>5</option>
                            </select>
  </div>
      </div>
          <div class="col-lg-3">
    <div class="form-group inline">
    <label class="sr-only1" for="top" >На главной</label>
   <input name="top" type="checkbox" id="top"  <?php if($this->sub->getTop()){?>checked="checked" <?php } ?>  value="1"/>
  </div>
      </div>
   
    </div>
      <div class="row">
          <div class="col-lg-12">
          <div class="form-group inline">
    <label class="sr-only1" for="paginatext" >Содержимое</label>
   <textarea name="text"   class="message-h"
                          id="paginatext"><?=$this->sub->getText()?></textarea>
  </div>
              </div>
          </div>
      <div class="row">
          <div class="col-lg-6">
    <div class="form-group inline">
    <label class="sr-only1" for="fileField" >Фото</label>
   <input name="image" type="file" class="form-control" id="fileField"/>
   <?php if ($this->sub->getImage()) {
                echo '<br/><img src="' . $this->sub->getImage() . '" style="max-width: 300px;" />';
            } ?>
  </div>
    </div>
      <div class="col-lg-6">    
    <div class="form-group inline">
    <label class="sr-only1" for="fileFieldLogo" >Логотип</label>
    <input name="logo" type="file" class="form-control" id="fileFieldLogo"/>
    <?php if ($this->sub->getLogo()) {
                       echo '<br/><img src="' . $this->sub->getLogo() . '" style="max-width: 300px;" />';
                   } ?>
  </div>
          </div>
          </div>
      </div>
      <div class="card-footer">
          <input type="submit" class="btn btn-primary btn-lg" name="savepage" id="savepage" value="Сохранить"/>
      </div>
      </form>
</div>
    </div>
<script src="<?=$this->files?>scripts/tiny_mce/tiny_mce.js"></script>
<script>

    tinyMCE.init({
        // General options
        mode:"exact",
        elements:"paginatext, paginaintro",
        language:"ru",
        theme:"advanced",
        plugins:"table, paste, images",
        theme_advanced_layout_manager:"SimpleLayout",
        theme_advanced_toolbar_location:"top",
        theme_advanced_toolbar_align:"left",
        theme_advanced_buttons1:"pasteword, separator, bold, italic, underline, separator, justifyleft, justifycenter, justifyright, justifyfull, separator, styleselect, separator, bullist, numlist, separator, undo, redo, separator, link, unlink, anchor, image, help, code, charmap",
        theme_advanced_buttons2:"tablecontrols, separator, images, separator, forecolor, backcolor, separator, fontselect",
        theme_advanced_buttons3:"",

        theme_advanced_statusbar_location:"bottom",
        theme_advanced_resizing:false,

        paste_use_dialog:false,
        force_br_newlines:true,
        force_p_newlines:false,
        relative_urls:false,
        apply_source_formatting:true,
        remove_script_host:true,

        // Example word content CSS (should be your site CSS) this one removes paragraph margins
        //content_css:"<?=SITE_URL?>/standard.css",
        external_link_list_url:"<?=SITE_URL?>/admin/pages/tinymce_list/",
        external_image_list_url:"<?=SITE_URL?>/admin/pages/tinymce_imagelist/"

    });
</script>
<!-- /TinyMCE --> 	


	