<?php header('Access-Control-Allow-Origin: *'); ?>
<script src="<?php echo $this->files; ?>scripts/ui/jquery-ui-1.9.1.custom.js" type="text/javascript"
        charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo $this->files; ?>scripts/ui/jquery-ui-1.9.1.custom.css" type="text/css"
      media="screen"/>
<style>
    .aih_box {
        position: absolute;
        right: 50px;
        top: 450px;
    }
</style>
<img src="<?php echo SITE_URL; ?><?php echo $this->getCurMenu()->getImage(); ?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle(); ?></h1>
<hr/>

<?php echo $this->getCurMenu()->getPageBody(); ?>

<p>Рассылка будет
    отправлена <span id="all_subject_span">0</span>
    подписчикам.</p>
<input type="hidden" id="all_subject"
       value="0"/>
<input type="hidden" id="all_emails"
       value=""/>
<?php
if ($this->errors) {
    ?>
    <div id="conf-error-message">
        <p><img src="<?php echo SITE_URL; ?>/img/icons/remove-small.png" class="iconnew" alt=""/>Возникли ошибки при
            отправке:</p>
        <ul>
            <?php foreach ($this->errors as $error) { ?>
                <li><?php echo $error; ?></li>
            <?php } ?>
        </ul>
    </div>
<?php
}

if ($this->saved) {
    ?>
    <div id="conf-error-message">
        <p><img src="<?php echo SITE_URL; ?>/img/icons/accept.png" alt="" width="16" height="16" class="iconnew"/>
            <strong><?php echo (int)$this->saved; ?> писем отправленно.</strong></p>
    </div>
<?php
}
?>
<div class="mailing_start" style="display: none;">
    <img src="/images/lightbox-ico-loading.gif" alt="loading"/> Рассылка, подождите...<br/>

    <div id="progressbar" style="width: 960px; height: 30px; margin-top: 10px;"></div>
</div>
<p id="show_emails" style="display: none;font-size: 16px;font-weight: bold;">Список всех E-mail на которые уже
    разослано:</p>
<div id="send_emails"
     style="display: none; width: 960px; max-height: 120px; overflow-y: scroll;border: 1px dashed #C00000;padding: 5px 10px;"></div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#save_emails').click(function () {
            $.ajax({
                url: '/admin/newmailing/',
                type: 'POST',
                dataType: 'json',
                data: 'metod=add_users&' + $('#add_emails').serialize(),
                success: function (res) {
                    confirm(res.message);
                    $('#all_subject').val(res.cnt);
                    $('#all_subject_span').html(res.cnt);
                    $('#add_emails').val(res.text);
                    $('#all_emails').val(res.emails);
                }
            });
        });
    });
</script>


<form method="POST" id="mail_form" action="<?php echo $this->path; ?>newmailing/" target="_blank">
    <table id="editpage" cellpadding="5" cellspacing="0">

        <tr>
            <td class="kolom1">Получатели<br/> (Каждый новый email с новой строки или розделен ";")<br/> (После
                добавление получателей, нажать кнопку "Применить")
            </td>
            <td>
                <textarea name="add" id="add_emails" style="width: 200px; height: 200px"></textarea>
                <input type="button" name="save_emails" value="Применить" id="save_emails"/>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Тема письма</td>
            <td><input name="subject" type="text" class="formfields" id="paginatitle"
                       value="<?php echo @$this->post->subject; ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Email отправитля</td>
            <td><input name="from_email" type="text" class="formfields" id="from_email"
                       value="<?php echo @$this->post->from_email /*? $this->post->from_email : Config::findByCode('new_main_admin_email')->getValue()*/; ?>"/>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Имя отправитля</td>
            <td><input name="from_name" type="text" class="formfields" id="from_name"
                       value="<?php echo @$this->post->from_name /* ? $this->post->from_name : Config::findByCode('admin_name')->getValue()*/; ?>"/>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Баннеры и текст вначале<br><br>(размер изображения по ширине нужно устанавливать =
                700)<br><br>(ссылки должны начинаться c http:// <br> все относительные ссылки будут идти от домена
                red.ua)
            </td>
            <td><textarea name="intro" class="pagetext-s tiny" id="page_body"
                          style="width:500px;height:100px"><?php echo @stripslashes($this->post->intro); ?></textarea>
            </td>
        </tr>


        <tr>
            <td class="kolom1">Баннеры и текст в конце</td>
            <td><textarea name="ending" class="pagetext-s tiny" id="page_ending"
                          style="width:500px;height:100px"><?php echo @stripslashes($this->post->ending); ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Тестовый e-mail на адрес</td>
            <td>
                <input name="test_email" type="text" class="formfields" id="paginatitle2"
                       value="<?php echo @$this->post->test_email; ?>"/>
                <input name="send_test" type="button" class="buttonps" id="send_test" value="Отправить тест"/>
                <input name="preview" type="submit" class="buttonps" id="view_test" value="посмотреть"/>
            </td>
        </tr>
        <tr>
            <td class="kolom1">&nbsp;</td>
            <td><input type="button" id="send_all" class="buttonps" name="send_full" id="savepage"
                       value="Отправить всем"/></td>
        </tr>
    </table>
</form>


<script type="text/javascript"
        src="<?php echo SITE_URL; ?><?php echo $this->files; ?>scripts/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        var count_mail = $('#all_subject').val();
        var send_mail = 0;
        var count = 10;
        var mails = '';

        function sendMail(url, data, test) {
            var new_data = data + '&from_mail=' + send_mail + '&count=' + count + '&intro=' + tinymce.get('page_body').getContent() + '&ending=' + tinymce.get('page_ending').getContent() + '&test=' + test + '&emails=' + $('#all_emails').val();
            var count_mail = $('#all_subject').val();

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                    if (res.status = 'send') {

                        send_mail += count;
                        mails += res.emails;
                        $('#send_emails').html(mails);
                        var proc = (send_mail / count_mail) * 100;
                        $("#progressbar").progressbar({
                            value: proc
                        });
                        if (send_mail <= count_mail && test == 0) {
                            sendMail(url, data, 0);
                        } else {
                            alert('Рассылка разослана.');
                            $('.mailing_start').hide();
                        }
                    }
                }
            });

        }

        $('#send_test').click(function () {
            var url = '/admin/newmailing/';
            var data = $('#mail_form').serialize();

            $('.mailing_start').show();
            sendMail(url, data, 1);
            $('#show_emails').show();
            $('#send_emails').show();

        });

        $('#send_all').click(function () {
            if (parseInt($('#all_subject').val()) > 0) {
                var url = '/admin/newmailing/';
                var data = $('#mail_form').serialize();

                $(this).attr('disabled', 'true');
                $('.mailing_start').show();
                sendMail(url, data, 0);
                $('#show_emails').show();
                $('#send_emails').show();

                alert('Рассылка стартовала. Дождитесь окончания!');
            } else {
                alert('Введите получателей и нажмите "Применить"!');
            }
        });


    });
    tinymce.init({
        selector: "textarea.tiny", width: 750, height: 300,
        language: 'ru',
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
        ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | fontsizeselect | preview | code ",
        image_advtab: true,

        external_filemanager_path: "/admin_files/scripts/filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: { "filemanager": "/admin_files/scripts/filemanager/plugin.min.js"},
        convert_urls: false,
        
    force_br_newlines : false,
      force_p_newlines : false,
      forced_root_block : '',       
    });
</script>
<!-- /TinyMCE --> 	
