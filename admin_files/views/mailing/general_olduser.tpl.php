<?php header('Access-Control-Allow-Origin: *'); ?>
<script src="<?php echo $this->files;?>scripts/ui/jquery-ui-1.9.1.custom.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo $this->files;?>scripts/ui/jquery-ui-1.9.1.custom.css" type="text/css" media="screen"/>
<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt=""  class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody(); ?>
<p>Рассылка будет
    отправлена <?php $cc = wsActiveRecord::useStatic('Customer')->count(array('time_zone_id' => 5)); echo $cc;?>
    подписчикам.</p>
<input type="hidden" id="all_subject" name="all_subject" value="<?=$cc?>"/>   
<?php if ($this->errors) { ?>
    <div id="conf-error-message">
        <p><img src="<?=SITE_URL;?>/img/icons/remove-small.png" class="iconnew" alt=""/>Возникли ошибки при отправке:</p>
        <ul>
            <?php foreach ($this->errors as $error) { ?>
                <li><?=$error;?></li>
            <?php } ?>
        </ul>
    </div>
<?php
}
if ($this->saved) { ?>
    <div id="conf-error-message">
        <p><img src="<?=SITE_URL?>/img/icons/accept.png" alt="" width="16" height="16" class="iconnew"/>
            <strong><?php echo (int)$this->saved;?> писем отправленно.</strong></p>
    </div>
<?php } ?>
<div class="mailing_start" style="display: none;">
	<img src="/images/lightbox-ico-loading.gif" alt="loading"/> Рассылка, подождите...<br/>
	<div id="progressbar" style="width: 960px; height: 30px; margin-top: 10px;"></div>
</div>
<p id="show_emails" style="display: none;font-size: 16px;font-weight: bold;">Список всех E-mail на которые уже разослано:</p>
<div id="send_emails" style="display: none; width: 960px; max-height: 120px; overflow-y: scroll;border: 1px dashed #C00000;padding: 5px 10px;"></div>

<script type="text/javascript">
	function loadArticles(category_id, i) {
		var data_to_post = new Object();
		data_to_post.id = category_id;
		data_to_post.getarticles = '1';
		$.post('/admin/oldusermailing/', data_to_post, function (data) {
			createSelectList(data, this, i); //console.log(data);
		}, 'json');
		$('#article_id_'+i).html('');
		$('#option_id').html('');
	}

	function createSelectList(data, a, item) {
		if ('done' == data.result) {
			out = '';
			himg = '';
			for (var i = 0; i < data.data.length; i++) {
				if (data.data[i].img) {
					himg += '<img style="display: none;" id ="aih_' + data.data[i].id + '" src="' + data.data[i].img + '"  />';
				}
				out += '<option value="' + data.data[i].id + '">' + data.data[i].title + himg + '</option>';
			}
			if ('articles' == data.type) {
				out = '<option value="0" selected>Выберите товар...</option>' + out;
				$('.article[rel="'+item+'"]').html(out);
				$('.aih_box[rel="'+item+'"]').html(himg);
			} else {
				out = '<option value="0" selected>Selecteer een optie...</option>' + out;
				$('#option_id').html(out);
			}
		}
	}
$( document ).ready(function() {
	$('.article').hover(function () {
		item=$(this).attr("rel");
		//console.log(item);
		$('.aih_box[rel="'+item+'"] img').hide();
		$('.aih_box[rel="'+item+'"] #aih_' + $(this).val()).show();
	}, function () {
		$('.aih_box[rel="'+item+'"] img').hide();
	});
	$('.article option').hover(function () {
		item=$(this).parent().attr("rel");
		//console.log(item);
		$('.aih_box[rel="'+item+'"] img').hide();
		$('.aih_box[rel="'+item+'"] #aih_' + $(this).attr('value')).show();
	}, function () {
		$('.aih_box[rel="'+item+'"] img').hide();
	});
});
</script>


<form method="POST" id="mail_form" action="<?=$this->path?>oldusermailing/" target="_blank">
    <table id="editpage" cellpadding="5" cellspacing="0">
					   <tr>
            <td class="kolom1">Тема письма</td>
            <td><input name="subject" type="text" class="formfields" id="paginatitle"
                       value="<?=@$this->post->subject; ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Ссылка для статистики<br/>
            	https://www.red.ua/?</td>
            <td><input name="extra_url" type="text" class="formfields" id="paginatitle"
                       value="<?=@$this->post->extra_url ? $this->post->extra_url : 'utm_source=return_user_email&utm_medium=email&utm_content=return_user_email&utm_campaign=return_user_email'; ?>"/></td>
        </tr>        
        <tr>
            <td class="kolom1">Баннеры и текст вначале<br><br>(размер изображения по ширине нужно устанавливать = 700)<br><br>(ссылки должны начинаться c http:// <br> все относительные ссылки будут идти от домена red.ua)</td>
            <td><textarea name="intro" class="pagetext-s" id="page_body" style="width:500px;height:100px"><?php echo @stripslashes($this->post->intro);?></textarea></td>
        </tr>
		<?php $mas = array();
		$categories = wsActiveRecord::useStatic('Shopcategories')->findAll();
		foreach ($categories as $cat) $mas[$cat->getRoute(0)] = $cat->getId();
		ksort($mas);
		?>
		<tr>
			<td class="kolom1">Товар слева сверху</td>
			<td class="kolom1">
				<select class="formfields" onChange="loadArticles(this.value, 1); return false;">
					<option value="" selected>Выберите категорию</option>
					<?php foreach ($mas as $kay => $value) {?>
					<option value="<?=$value?>"><?=$kay?></option>
					<?php } ?>
				</select><br/>

				<select name="article_id[1]" class="formfields article" rel="1"></select>

				<div class="aih_box" rel="1"></div>
			</td>
		</tr>
		<tr>
			<td class="kolom1">Товар по центру</td>
			<td class="kolom1">
				<select class="formfields" onChange="loadArticles(this.value, 2); return false;">
					<option value="" selected>Выберите категорию</option>
					<?php foreach ($mas as $kay => $value) {?>
					<option value="<?=$value?>"><?=$kay?></option>
					<?php } ?>
				</select><br/>

				<select name="article_id[2]" class="formfields article" rel="2"></select>

				<div class="aih_box" rel="2"></div>
			</td>
		</tr>
		<tr>
			<td class="kolom1">Товар справа сверху</td>
			<td class="kolom1">
				<select class="formfields" onChange="loadArticles(this.value, 3); return false;">
					<option value="" selected>Выберите категорию</option>
					<?php foreach ($mas as $kay => $value) {?>
					<option value="<?php echo $value; ?>"><?php echo $kay; ?></option>
					<?php } ?>
				</select><br/>

				<select name="article_id[3]" class="formfields article" rel="3"></select>

				<div class="aih_box" rel="3"></div>
			</td>
		</tr>
		<tr>
			<td class="kolom1">Товар слева снизу</td>
			<td class="kolom1">
				<select class="formfields" onChange="loadArticles(this.value, 4); return false;">
					<option value="" selected>Выберите категорию</option>
					<?php foreach ($mas as $kay => $value) {?>
					<option value="<?php echo $value; ?>"><?php echo $kay; ?></option>
					<?php } ?>
				</select><br/>

				<select name="article_id[4]" class="formfields article" rel="4"></select>

				<div class="aih_box" rel="4"></div>
			</td>
		</tr>
		<tr>
			<td class="kolom1">Товар центер снизу</td> 
			<td class="kolom1">
				<select class="formfields" onChange="loadArticles(this.value, 5); return false;">
					<option value="" selected>Выберите категорию</option>
					<?php foreach ($mas as $kay => $value) {?>
					<option value="<?php echo $value; ?>"><?php echo $kay; ?></option>
					<?php } ?>
				</select><br/>

				<select name="article_id[5]" class="formfields article" rel="5"></select>

				<div class="aih_box" rel="5"></div>
			</td>
		</tr>
				<tr>
			<td class="kolom1">Товар справа снизу</td>
			<td class="kolom1">
				<select class="formfields" onChange="loadArticles(this.value, 6); return false;">
					<option value="" selected>Выберите категорию</option>
					<?php foreach ($mas as $kay => $value) {?>
					<option value="<?php echo $value; ?>"><?php echo $kay; ?></option>
					<?php } ?>
				</select><br/>

				<select name="article_id[6]" class="formfields article" rel="6"></select>

				<div class="aih_box" rel="6"></div>
			</td>
		</tr>
        <tr>
            <td class="kolom1">Баннеры и текст в конце</td>
            <td class="kolom1"><textarea name="ending" class="pagetext-s" id="page_ending" style="width:500px;height:100px"><?php echo @stripslashes($this->post->ending);?></textarea></td>
        </tr>
		

        <tr>
            <td class="kolom1">Тестовый e-mail на адрес</td>
			<td>
				<input name="test_email" type="text" class="formfields" id="paginatitle2" value="<?=$this->user->email;?>"/>
				<input name="send_test" type="button" class="buttonps" id="send_test" value="Отправить тест"/>
				<input name="preview" type="submit" class="buttonps" id="view_test" value="посмотреть"/>
			</td>
        </tr>
        <tr>
            <td class="kolom1">&nbsp;</td>
            <td><input type="button" id="send_all" class="buttonps" name="send_full" id="savepage" value="Отправить всем"/></td>
        </tr>
    </table>
</form>


<script type="text/javascript" src="<?=SITE_URL.$this->files; ?>scripts/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () { 

        var count_mail = $('#all_subject').val();
        var send_mail = 0;
        var count = 10;
        var mails ='';

        function sendMail(url, data, go) {
		var intro = tinymce.get('page_body').getContent().replace(/&/g,"#");
		var ending = tinymce.get('page_ending').getContent().replace(/&/g,"#");
var new_data = data + '&from_mail=' + send_mail + '&count=' + count +'&intro='+intro+'&ending='+ending+ '&go='+go;

			surl = url+"&"+new_data;
//			console.log(surl);
            $.ajax({
                url: surl,
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
                        if (send_mail <= count_mail && go == 0) {
                            sendMail(url, data, 0);
                        } else {
                            alert('Рассылка разослана.');
                            $('.mailing_start').hide();
                        }
                    }
                }
            });

        }
		
		function sendMailTest(url, data, test) {
		var intro = tinymce.get('page_body').getContent().replace(/&/g,"#");
		var ending = tinymce.get('page_ending').getContent().replace(/&/g,"#");
var new_data = data + '&from_mail=' + send_mail + '&count=' + count +'&intro='+intro+'&ending='+ending+ '&test='+ test;

			surl = url+"&"+new_data;
//			console.log(surl);
            $.ajax({
                url: surl,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                    if (res.status = 'send') {

                           if (res.status = 'send') {
                            alert('Рассылка отправлена на '+res.from);

                    }
                    }
                }
            });

        }
		
		

        $('#send_test').click(function () {
            var url = '/admin/oldusermailing/';
            var data = $('#mail_form').serialize();
           // $('.mailing_start').show();
           sendMailTest(url, data, 1);
			//$('#show_emails').show();
			//$('#send_emails').show();
			alert('Сообщение отправлено на тестовый email.');

        });

        $('#send_all').click(function () {
            var url = '/admin/oldusermailing/';
            var data = $('#mail_form').serialize();

            $(this).attr('disabled', 'true');
            $('.mailing_start').show();
            sendMail(url, data, 0);
			$('#show_emails').show();
            $('#send_emails').show();

            alert('Рассылка стартовала. Дождитесь окончания!');
        });



    });
	tinymce.init({
		selector: "textarea",
		width: 750,
		height: 300,
		language : 'ru',
		plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			 "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
	   ],
	   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
	   toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | fontsizeselect | preview | code ",
	   image_advtab: true ,
	   
	   external_filemanager_path:"/admin_files/scripts/filemanager/",
	   filemanager_title:"Responsive Filemanager" ,
	   external_plugins: { "filemanager" : "/admin_files/scripts/filemanager/plugin.min.js"},
	   convert_urls: false
	 });
</script>
<!-- /TinyMCE --> 	
