<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>

<?php
if ($this->errors) {
    ?>
<div id="errormessage"><img src="<?php echo SITE_URL;?>/img/icons/error.png" alt="" class="page-img"/>

    <h1>Ошибка:</h1>
    <ul>
        <?php
        foreach ($this->errors as $error) {
            ?>
            <li><?php echo $error;?></li>
            <?php

        }
        ?>
    </ul>
</div>
<?php

}

if ($this->saved) {
    ?>
<div id="pagesaved">
    <img src="<?php echo SITE_URL;?>/img/icons/accept.png" alt="" class="page-img"/>

    <h1>Запись сохранена.</h1>
</div>
<?php

}
?>

<form method="POST" action="<?php echo $this->path;?>brand/edit/id/<?php echo (int)$this->sub->getId();?>/"
      enctype="multipart/form-data">
    <input type="hidden" value="<?php echo @$_GET['order_id'];?>" name="order_id">
    <table id="editpage" cellpadding="5" cellspacing="0">
        <tr>
            <td class="kolom1">Название</td>
            <td><input name="name" type="text" class="formfields" id="paginatitle"
                       value="<?php echo $this->sub->getName();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">На главной</td>
            <td><input name="top" type="checkbox"  <?php if($this->sub->getTop()){?>checked="checked" <?php } ?>  value="1"/></td>
        </tr>
        <tr>
            <td class="kolom1">Содержимое</td>
            <td><textarea name="text" cols="45" rows="30" class="message-h"
                          id="paginatext"><?php echo $this->sub->getText();?></textarea></td>
        </tr>
        <tr>
            <td class="kolom1">Фото</td>
            <td>
                <label>
                    <input name="image" type="file" class="formfields-1" id="fileField"/>
                </label>
                <?php if ($this->sub->getImage()) {
                echo '<br/><img src="' . $this->sub->getImage() . '" />';
            } ?>
            </td>
        </tr>

        <tr>
                   <td class="kolom1">Логотип</td>
                   <td>
                       <label>
                           <input name="logo" type="file" class="formfields-1" id="fileFieldLogo"/>
                       </label>
                       <?php if ($this->sub->getLogo()) {
                       echo '<br/><img src="' . $this->sub->getLogo() . '" />';
                   } ?>
                   </td>
               </tr>

        <tr>
            <td class="kolom1">&nbsp;</td>
            <td><input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/></td>
        </tr>
    </table>
</form>
<script type="text/javascript"
        src="<?php echo SITE_URL;?><?php echo $this->files;?>scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#datetime').datepicker();
    });
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
        content_css:"<?php echo SITE_URL;?>/standard.css",
        external_link_list_url:"<?php echo SITE_URL;?>/admin/pages/tinymce_list/",
        external_image_list_url:"<?php echo SITE_URL;?>/admin/pages/tinymce_imagelist/"

    });
</script>
<!-- /TinyMCE --> 	


	