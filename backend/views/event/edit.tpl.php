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

<form method="POST" action="<?php echo $this->path;?>event/id/<?php echo (int)$this->event->getId();?>/">

    <table id="editpage" cellpadding="5" cellspacing="0">
        <?php if ($this->event->getId()) { ?>
        <tr>
            <td class="kolom1">Номер события</td>
            <td><?php echo $this->event->getId();?></td>
        </tr>
        <tr>
            <td class="kolom1">Ссылка активации</td>
            <td><?php echo $this->event->getPath();?></td>
        </tr>
        <?php } ?>
        <tr>
            <td class="kolom1">Название</td>
            <td><input name="name" type="text" class="formfields" id="name"
                       value="<?php echo $this->event->getName();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Начало</td>
            <td><input name="start" type="text" class="formfields datetime"
                       value="<?php if ($this->event->getStart() and $this->event->getStart() != '0000-00-00') echo date('d.m.Y', strtotime($this->event->getStart())); else echo date('d.m.Y');?>"/>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Окончание</td>
            <td><input name="finish" type="text" class="formfields datetime"
                       value="<?php if ($this->event->getFinish() and $this->event->getFinish() != '0000-00-00') echo date('d.m.Y', strtotime($this->event->getFinish())); else echo date('d.m.Y');?>"/>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Скидка</td>
            <td><input name="discont" type="text" class="formfields" value="<?php echo $this->event->getDiscont();?>"/> %
            </td>
        </tr>
        <tr>
            <td class="kolom1">Сумма заказа для получения подарка</td>
            <td><input name="sumforgift" type="text" class="formfields" value="<?php echo $this->event->getSumforgift();?>"/> грн (0 - условие не активно)
            </td>
        </tr>
        <tr>
            <td class="kolom1">Активна</td>
            <td><input name="publick" type="checkbox"
                       value="1" <?php if ($this->event->getPublick()) { ?>checked="checked"<?php } ?>/></td>
        </tr>
        <tr>
            <td class="kolom1">Единоразово</td>
            <td><input name="disposable" type="checkbox"
                       value="1" <?php if ($this->event->getDisposable()) { ?>checked="checked"<?php } ?>/></td>
        </tr>
        <tr>
            <td class="kolom1">Описание</td>
            <td>
                <textarea name="text" rows="10" class="pagetext"
                          id="text"><?php echo $this->event->getText();?></textarea>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Подписавшихся</td>
            <td><a href="/admin/usersevent/id/<?php echo $this->event->getId();?>"><?php echo $this->event->getCustomersCount()?></a></td>
        </tr>

        <tr>
            <td class="kolom1">&nbsp;</td>
            <td><input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/></td>
        </tr>
    </table>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $('.datetime').datepicker();
    });
</script>
<script type="text/javascript"
        src="<?php echo SITE_URL;?><?php echo $this->files;?>scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        // General options
        mode:"exact",
        elements:"text",
        language:"ru",
        theme:"advanced",
        plugins:"table, paste, images",
        theme_advanced_layout_manager:"SimpleLayout",
        theme_advanced_toolbar_location:"top",
        theme_advanced_toolbar_align:"left",
        theme_advanced_buttons1:"pasteword, separator, bold, italic, underline, separator, justifyleft, justifycenter, justifyright, justifyfull, separator, styleselect, separator, bullist, numlist, separator, undo, redo, separator, link, unlink, anchor, image, help, code, charmap",
        theme_advanced_buttons2:"tablecontrols, separator, images, separator, forecolor, backcolor, separator, fontselect, fontsizeselect",
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