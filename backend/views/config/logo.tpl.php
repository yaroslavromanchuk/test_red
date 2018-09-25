<img src="<?php echo SITE_URL; ?><?php echo $this->getCurMenu()->getImage(); ?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle(); ?> </h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>

<?php
if ($this->errors) {
    ?>
    <div id="errormessage"><img src="<?php echo SITE_URL; ?>/img/icons/error.png" alt="" width="32" height="32"
                                class="page-img"/>

        <h1>Че-то произошло:</h1>
        <ul>
            <?php
            foreach ($this->errors as $error) {
                ?>
                <li><?php echo $error; ?></li>
            <?php
            }
            ?>
        </ul>
    </div>
<?php
}
?>


<form method="post" enctype="multipart/form-data" action="<?php echo $this->path; ?>logo/">

    <table id="changepw" cellpadding="6" cellspacing="0">

        <tr>
            <td class="kolom1">Логотип</td>
            <td>
                <input type="file" name="logotype"/>
                <img src="<?php echo $this->logotype->getValue(); ?>" style="width: 140px;" alt="">

            </td>
        </tr>
        <tr>
            <td class="kolom1">Левый блок</td>
            <td>
                <textarea rows="" cols="" id="slogon" name="slogon"><?php echo $this->slogon->getValue(); ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Центральный блок</td>
            <td>
                <textarea rows="" cols="" id="header_info"
                          name="header_info"><?php echo $this->header_info->getValue(); ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Правый блок</td>
            <td>
                <textarea rows="" cols="" id="phones" name="phones"><?php echo $this->phones->getValue(); ?></textarea>
            </td>
        </tr>
    </table>
    <p>
        <label>
            <input type="submit" class="buttonpw" value="Сохранить"/>
        </label>
    </p>
</form>

<script type="text/javascript"
        src="<?=SITE_URL.$this->files; ?>scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">

    tinyMCE.init({
        // General options
        mode: "exact",
        elements: "slogon, header_info, phones",
        language: "ru",
        theme: "advanced",
        plugins: "table, paste, images, pastehtml",
        theme_advanced_layout_manager: "SimpleLayout",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_buttons1: "pastehtml,pastetext, separator, bold, underline, italic, separator, link, unlink, bullist, numlist, separator, image, separator, code, separator, styleselect",
        theme_advanced_buttons2: "",
        theme_advanced_buttons3: "",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: false,
        force_p_newlines: false,
        force_br_newlines: true,
        paste_use_dialog: false,
        forced_root_block: false,
        relative_urls: false,
        apply_source_formatting: true,
        remove_script_host: true,


        // Example word content CSS (should be your site CSS) this one removes paragraph margins
        content_css: "<?=SITE_URL;?>/standard.css",
        external_link_list_url: "<?=SITE_URL;?>/admin/pages/tinymce_list/",
        extended_valid_elements: "b,u,i,iframe[name|src|framespacing|border|frameborder|scrolling|title|height|width],object[declare|classid|codebase|data|type|codetype|archive|standby|height|width|usemap|name|tabindex|align|border|hspace|vspace],div[*],p[*]",
        external_image_list_url: "<?=SITE_URL;?>/admin/pages/tinymce_imagelist/"

    });
</script>
    