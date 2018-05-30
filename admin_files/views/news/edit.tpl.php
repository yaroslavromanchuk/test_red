<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<?=$this->getCurMenu()->getPageBody()?>

<?php
	if($this->errors)
	{
?>
	<div id="errormessage"><img src="<?php echo SITE_URL;?>/img/icons/error.png" alt="" width="32" height="32" class="page-img" />
	    <h1>Найдены ошибки:</h1>
	    <ul>
    	<?php
    		foreach($this->errors as $error)
    		{
    	?>
		    <li><?php echo $error;?></li>
		<?php
			}
		?>
	    </ul>
	</div>  
<?php
	}

	if($this->saved)
	{
?>	
	<div id="pagesaved">
		<img src="<?php echo SITE_URL;?>/img/icons/accept.png" alt="" width="32" height="32" class="page-img" />
		<h1>Данные успешно сохранены.</h1>
	</div>
<?php
	}
?>

	<form method="post" action="<?=$this->path?>news/edit/id/<?=(int)$this->onenew->getId()?>/"  claass="form-horizontal form-inline form-style"  enctype="multipart/form-data">
    <table id="editpage" cellpadding="5" cellspacing="0" class="table">
      <tr>
        <td class="kolom1">Заголовок</td>
        <td><input name="title" type="text" class="form-control " id="paginaid" value="<?php echo $this->onenew->getTitle();?>" /></td>
      </tr>
	   <tr>
        <td class="kolom1">Действительно c</td>
        <td><input name="start_datetime" type="date" class="form-control input w150" id="start_datetime" value="<?php echo $this->onenew->getId() ? date('Y-m-d', strtotime(substr($this->onenew->getStartDatetime(), 0, 10))) : date('Y-m-d');?>"/> 
        </td>
      </tr>
      <tr>
        <td class="kolom1">Действительно до</td>
        <td><input name="end_datetime" type="date" class="form-control input w150" id="end_datetime" value="<?php echo $this->onenew->getId() ? date('Y-m-d', strtotime(substr($this->onenew->getEndDatetime(), 0, 10))) : date('Y-m-d');?>"/> 
        </td>
      </tr>
      <tr>
        <td class="kolom1">Новости для:</td>
        <td>
		<label>
          <input type="radio" name="status" value="1"  <?php echo ($this->onenew->getStatus()==1) ? 'checked' : '';?>/>
		  Админы
        </label>
		<label>
          <input type="radio" name="status" value="2" <?php echo ($this->onenew->getStatus()==2) ? 'checked' : '';?>/>
		  Клиенты
        </label>
		<label>
          <input type="radio" name="status" value="0"  <?php echo ($this->onenew->getStatus()==0) ? 'checked' : '';?>/>
		  Архив
        </label>
		</td>
      </tr>
       <tr>
        <td class="kolom1">Вступление</td>
        <td><textarea name="intro" cols="80" rows="5" class="form-control" id="introtext"><?php echo $this->onenew->getIntro();?></textarea></td>
      </tr> 
      <tr>
        <td class="kolom1">Содержимое</td>
        <td><textarea name="content" cols="45" rows="30" class="form-control" id="paginatext"><?php echo $this->onenew->getContent();?></textarea></td>
      </tr>
	<!--<tr>
        <td class="kolom1">Фото</td>
        <td>    
        <label>
          <input name="image" type="file" class="formfields-1" id="fileField" />
        </label>
        <?php if($this->onenew->getImage()) {
        	echo '<br/><img src="' . $this->onenew->getImage() . '" />';
        } ?> 
        </td>
      </tr>-->
    </table>
	<p><button type="submit" class="btn btn-small btn-default" name="savepage" id="savepage">
	<i class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></i> Сохранить
	</button>
	</p>
    </form>
<script type="text/javascript" src="<?=SITE_URL.$this->files;?>scripts/tiny_mce/tiny_mce.js"></script> 	
<script type="text/javascript">

	    tinyMCE.init({
        // General options
        mode : "exact",
        elements: "paginatext, paginaintro",
        language : "ru",
        theme : "advanced",
        plugins : "table, paste, images, pastehtml",
        theme_advanced_layout_manager : "SimpleLayout",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_buttons1 : "pastehtml, pasteword, separator, bold, italic, underline, separator, justifyleft, justifycenter, justifyright, justifyfull, separator, styleselect, separator, bullist, numlist, separator, undo, redo, separator, link, unlink, anchor, image, help, code, charmap",
        theme_advanced_buttons2 : "tablecontrols, separator, images, separator, forecolor, backcolor, separator, fontselect",
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
        content_css : "<?php echo SITE_URL;?>/standard.css",
        external_link_list_url : "<?php echo SITE_URL;?>/admin/pages/tinymce_list/",
            extended_valid_elements : "b,u,i,iframe[name|src|framespacing|border|frameborder|scrolling|title|height|width],object[declare|classid|codebase|data|type|codetype|archive|standby|height|width|usemap|name|tabindex|align|border|hspace|vspace],div[*],p[*]",
        external_image_list_url : "<?php echo SITE_URL;?>/admin/pages/tinymce_imagelist/"

    });
</script> 
<!-- /TinyMCE --> 	
