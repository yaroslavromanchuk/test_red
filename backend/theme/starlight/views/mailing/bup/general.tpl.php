<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>

<?php echo $this->getCurMenu()->getPageBody();?>

<p>Рассылка будет отправлена <?php echo (int)wsActiveRecord::useStatic('Subscriber')->findAll(array('active'=>1, 'confirmed is not null'))->count();?> подписчикам.</p>

<?php
	if($this->errors)
	{
?>
    <div id="conf-error-message">
		<p><img src="<?php echo SITE_URL;?>/img/main/remove-small.png" class="iconnew"  alt="" />Возникли ошибки при отправке:</p>
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
	<div id="conf-error-message">
	<p><img src="<?php echo SITE_URL;?>/img/icons/accept.png" alt="" width="16" height="16" class="iconnew" />
		<strong><?php echo (int)$this->saved;?> писем отправленно.</strong></p>
	</div>
<?php
	}
?>

	<form method="POST" action="<?php echo $this->path;?>generalmailing/">
		<table id="editpage" cellpadding="5" cellspacing="0">
		  <tr>
		    <td class="kolom1">Тема письма</td>
		    <td><input name="subject" type="text" class="formfields" id="paginatitle" value="<?php echo @$this->post->subject;?>" /></td>
		  </tr>
		  <tr>
		    <td class="kolom1">Вступительный текст</td>
		    <td><textarea name="intro" rows="5" cols="40" class="pagetext-s" id="page_body"><?php echo @$this->post->intro;?></textarea></td>
		  </tr>
				  <tr>
		    <td class="kolom1">Вложить новости</td>
		    <td>
		    	<?php foreach(wsActiveRecord::useStatic('News')->findAll(array('sent_general'=>null)) as $news) { 
		    		$sel = (@$this->post->news[$news->getId()])?'checked="checked"':'';
		    		echo '<input name="news[' . $news->getId() . ']" type="checkbox" value="1" '.$sel.'/>' . $news->getTitle(). '<br />';
		    	 } ?>
		      </td>
		  </tr>	  
		          <tr>
		            <td class="kolom1">Тестовый e-mail на адрес</td>
		            <td><input name="test_email" type="text" class="formfields" id="paginatitle2" value="<?php echo @$this->post->test_email;?>" />
		              <input name="send_test" type="submit" class="buttonps" id="button" value="Отправить тест" />
		            </td>
            </tr>
            <tr>
			<td class="kolom1">&nbsp;</td>
			<td><input type="submit" class="buttonps" name="send_full" id="savepage" value="Отправить всем" /></td>
		  </tr>
		</table>
	</form>
	
	
<script type="text/javascript" src="<?php echo SITE_URL;?><?php echo $this->files;?>scripts/tiny_mce/tiny_mce.js"></script> 	
<script type="text/javascript"> 
	tinyMCE.init({
		// General options
        mode : "textareas",
        language : "ru",
        theme : "advanced",
        plugins : "paste, images",
        theme_advanced_layout_manager : "SimpleLayout",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_buttons1 : "pastetext, separator, bold, italic, underline, separator, separator, bullist, numlist, separator, undo, redo",
        theme_advanced_buttons2 : "link, unlink, anchor, image, code, charmap",
        theme_advanced_buttons3 : "",
        theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,

	paste_use_dialog : false,
	force_br_newlines : false,
	relative_urls : false,
apply_source_formatting : true,
remove_script_host : true,
 
		// Example word content CSS (should be your site CSS) this one removes paragraph margins
		content_css : "<?php echo SITE_URL;?>/standard.css",
		external_link_list_url : "<?php echo SITE_URL;?>/admin/pages/tinymce_list/",
		external_image_list_url : "<?php echo SITE_URL;?>/admin/pages/tinymce_imagelist/"

	});
</script> 
<!-- /TinyMCE --> 	
