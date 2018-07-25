<?php header('Access-Control-Allow-Origin: *'); 
header('X-XSS-Protection: 0');?>
<script src="<?=$this->files?>scripts/ui/jquery-ui-1.9.1.custom.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?=$this->files?>scripts/ui/jquery-ui-1.9.1.custom.css" type="text/css" media="screen"/>
<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt="" class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle();?></h1>
<hr/>
<style>
.alert-danger, .alert-success {
display:none;
}
</style>
<?=$this->getCurMenu()->getPageBody(); ?>
<?php $coll = wsActiveRecord::useStatic('Subscriber')->count(array('active' => 1, 'confirmed is not null', 'men'=> 0, 'women'=>0, 'baby'=>0, 'shop'=>0)); ?>
<p>Рассылка будет отправлена <?=$coll?> подписчикам.</p>
<input type="hidden" id="all_subject" name="all_subject" value="<?=$coll?>"/>  
<div class="alert alert-success" role="alert">
  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  <span class="sr-only1">Отправлено писем: </span><span id="all_p"><?=(int)$this->saved?></span>
</div>
<div class="alert alert-danger <?php if($this->errors) echo 'show';?>"  role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <?php if($this->errors){ ?>
  <span class="sr-only1">Возникли ошибки:</span>
    <ul>
		<?php foreach ($this->errors as $error) { ?><li><?=$error?></li><?php } ?>
	</ul>
	<?php }else{
	?>
	<span class="sr-only1">Ошибки отправки: </span><span id="all_p_er"></span>
	<?php }?>
</div>
<div class="mailing_start" style="display: none;">
	<img src="/images/lightbox-ico-loading.gif" alt="loading"/> Рассылка, подождите...<br/>
		<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
  </div>
</div>
</div>
<p id="show_emails" style="display: none;font-size: 16px;font-weight: bold;">Список всех E-mail на которые уже разослано:</p>
<div id="send_emails" style="display: none; width: 960px; max-height: 120px; overflow-y: scroll;border: 1px dashed #C00000;padding: 5px 10px;"></div>

<script type="text/javascript">
	function loadArticles(category_id, i) {
		var data_to_post = new Object();
		data_to_post.id = category_id;
		data_to_post.getarticles = '1';
		$.post('/admin/generalmailing/', data_to_post, function (data) {
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
		//console.log($(this).val());
		$('.aih_box[rel="'+item+'"] img').hide();
		$('.aih_box[rel="'+item+'"] img#aih_' + $(this).val()).show();
	}, function () {
		$('.aih_box[rel="'+item+'"] img').hide();
	});
/*	$('.article').change(function(){
  alert('Элемент foo был изменен.');
});*/
	$('.article option').change(function () {
		item=$(this).attr("rel");
		console.log('111');
		$('.aih_box[rel="'+item+'"] img').hide();
		$('.aih_box[rel="'+item+'"] #aih_' + $(this).val()).show();
	}, function () {
		$('.aih_box[rel="'+item+'"] img').hide();
	});
});
</script>
<script>
   function agreeForm(f) {
    // Если поставлен флажок, снимаем блокирование кнопки
    if (f.s_start.checked) f.subject_start.disabled = 0 
    // В противном случае вновь блокируем кнопку
    else f.subject_start.disabled = 1
   }
  </script>

<form method="POST" id="mail_form" action="<?php echo $this->path; ?>generalmailing/" target="_blank">
    <table id="editpage" cellpadding="5" cellspacing="0" class="table">
        <tr>
		    <td class="kolom1">Начало обращения</td>
            <td><input type="hidden" id="id_post"  name="id_post" value="<?php if($this->pemail->id){ echo $this->pemail->id;} ?>"/>
			<input name="subject_start" type="text" class="form-control input w500"  disabled
                       value="<?php if(@$this->post->subject_start){ echo $this->post->subject_start;}elseif($this->pemail->subject_start){ echo $this->pemail->subject_start;} ?>"/><label><input type="checkbox" id="s_start" name="s_start" onclick="agreeForm(this.form)" value="1">Добавить приветствие</label></td>
					   </tr>
					   <tr>
            <td class="kolom1">Тема письма</td>
            <td><input name="subject" type="text" class="form-control input w500" id="paginatitle"
                       value="<?php if(@$this->post->subject){ echo $this->post->subject;}elseif($this->pemail->subject){ echo $this->pemail->subject;} ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Ссылка для статистики<br/>
            	http://www.red.ua/?</td>
            <td><input name="extra_url" type="text" class="form-control input w500" 
                       value="<?php echo @$this->post->extra_url ? $this->post->extra_url : 'utm_source=emailletter_'.date('d.m.Y').'&utm_medium=email&utm_content=EmailLetter&utm_campaign=EmailLetter'; ?>"/>
					   <input name="unsubscribe" hidden type="text" 
                       value="<?=@$this->post->unsubscribe ? $this->post->unsubscribe : '&utm_source=unsubscribe_'.date('d.m.Y').'&utm_medium=email&utm_content=EmailLetter&utm_campaign=EmailLetter'?>"/>
					   </td>
        </tr>        
        <tr>
            <td class="kolom1">Баннеры и текст вначале<br><br>(размер изображения по ширине нужно устанавливать = 700)<br><br>(ссылки должны начинаться c http:// <br> все относительные ссылки будут идти от домена red.ua)</td>
            <td><textarea name="intro" class="pagetext-s" id="page_body" ><?php if(@$this->post->intro){ echo @stripslashes($this->post->intro);}elseif($this->pemail->intro){ echo @stripslashes($this->pemail->intro);}?></textarea></td>
        </tr>
   
	  <!-- <tr>
            <td class="kolom1 hide">Вложить новости</td>
            <td>
                <?php //foreach (wsActiveRecord::useStatic('News')->findAll(array('sent_general' => null)) as $news) {
                   // $sel = (@$this->post->news[$news->getId()]) ? 'checked="checked"' : '';
                   // echo '<label><input name="news[' . $news->getId() . ']" type="checkbox" value="1" ' . $sel . '/>' . $news->getTitle() . '</label><br />';
               // } ?>
            </td>
			
        </tr>-->
		<?php $mas = array();
		$categories = wsActiveRecord::useStatic('Shopcategories')->findAll();
		foreach ($categories as $cat) $mas[$cat->getRoute(0)] = $cat->getId();
		ksort($mas);
		?>
		<tr>
			<td class="kolom1">Товар слева сверху</td>
			<td class="kolom1">
				<select class="form-control input w500" onChange="loadArticles(this.value, 1); return false;">
					<option value="" selected>Выберите категорию</option>
					<?php foreach ($mas as $kay => $value) {?>
					<option value="<?=$value?>"><?=$kay?></option>
					<?php } ?>
				</select><br/>

				<select name="article_id[1]" size="5" class="form-control input w500 article" rel="1"></select>
				<div class="aih_box" rel="1"></div>
			</td>
		</tr>
		<tr>
			<td class="kolom1">Товар по центру</td>
			<td class="kolom1">
				<select class="form-control input w500" onChange="loadArticles(this.value, 2); return false;">
					<option value="" selected>Выберите категорию</option>
					<?php foreach ($mas as $kay => $value) {?>
					<option value="<?php echo $value; ?>"><?php echo $kay; ?></option>
					<?php } ?>
				</select><br/>

				<select name="article_id[2]" size="5" class="form-control input w500 article" rel="2"></select>

				<div class="aih_box" rel="2"></div>
			</td>
		</tr>
		<tr>
			<td class="kolom1">Товар справа сверху</td>
			<td class="kolom1">
				<select class="form-control input w500" onChange="loadArticles(this.value, 3); return false;">
					<option value="" selected>Выберите категорию</option>
					<?php foreach ($mas as $kay => $value) {?>
					<option value="<?php echo $value; ?>"><?php echo $kay; ?></option>
					<?php } ?>
				</select><br/>

				<select name="article_id[3]" size="5" class="form-control input w500 article" rel="3"></select>

				<div class="aih_box" rel="3"></div>
			</td>
		</tr>
		<tr>
			<td class="kolom1">Товар слева снизу</td>
			<td class="kolom1">
				<select class="form-control input w500" onChange="loadArticles(this.value, 4); return false;">
					<option value="" selected>Выберите категорию</option>
					<?php foreach ($mas as $kay => $value) {?>
					<option value="<?php echo $value; ?>"><?php echo $kay; ?></option>
					<?php } ?>
				</select><br/>

				<select name="article_id[4]" size="5" class="form-control input w500 article" rel="4"></select>

				<div class="aih_box" rel="4"></div>
			</td>
		</tr>
		<tr>
			<td class="kolom1">Товар центер снизу</td> 
			<td class="kolom1">
				<select class="form-control input w500" onChange="loadArticles(this.value, 5); return false;">
					<option value="" selected>Выберите категорию</option>
					<?php foreach ($mas as $kay => $value) {?>
					<option value="<?php echo $value; ?>"><?php echo $kay; ?></option>
					<?php } ?>
				</select><br/>

				<select name="article_id[5]" size="5" class="form-control input w500 article" rel="5"></select>

				<div class="aih_box" rel="5"></div>
			</td>
		</tr>
				<tr>
			<td class="kolom1">Товар справа снизу</td>
			<td class="kolom1">
				<select class="form-control input w500" onChange="loadArticles(this.value, 6); return false;">
					<option value="" selected>Выберите категорию</option>
					<?php foreach ($mas as $kay => $value) {?>
					<option value="<?php echo $value; ?>"><?php echo $kay; ?></option>
					<?php } ?>
				</select><br/>

				<select name="article_id[6]" size="5" class="form-control input w500 article" rel="6"></select>

				<div class="aih_box" rel="6"></div>
			</td>
		</tr>
        <tr>
            <td class="kolom1">Баннеры и текст в конце</td>
            <td class="kolom1"><textarea name="ending" class="pagetext-s" id="page_ending"><?php if(@$this->post->ending){ echo @stripslashes($this->post->ending);}elseif($this->pemail->ending){ echo @stripslashes($this->pemail->ending);}?></textarea></td>
        </tr>
		

        <tr>
            <td class="kolom1">Тестовый e-mail:</td>
			<td>
				<input name="test_email" type="text" class="form-control input" id="paginatitle2" value="<?=$this->user->email;?>"/>
				<input name="send_test" type="button" class="btn btn-default" id="send_test" value="Отправить тест"/>
				<label><input type="radio" name="copy" value="0" checked >Без копии</label>
				<label><input type="radio" name="copy" value="2">Добавить в копию</label>
				<!--<label><input type="radio" name="copy" value="1">Видимая копия</label>-->
			</td>
        </tr>
	<tr id="copy" style="display:none;"><td class="kolom1">Копия:</td><td><input name="copy_email" type="text" class="form-control input" placeholder="Email"  value=""/></td></tr>
	<tr><td class="kolom1">Просмотр:</td><td><input name="preview" type="submit" class="btn btn-default" id="view_test" value="Посмотреть"/></td></tr>
	
        <tr>
            <td class="kolom1">Действие:</td>
            <td>
			<input name="savepost" type="button" class="btn btn-default" id="savepost" value="Сохранить"/>
			<input type="button" id="send_all" class="btn btn-default" name="send_full" id="savepage" value="Отправить всем"/></td>
        </tr>
    </table>
</form>

<script type="text/javascript" src="<?=SITE_URL.$this->files; ?>scripts/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
	
	$('[type="radio"]').click(function(){
	console.log($(this).val());
			if($(this).val() == 1 || $(this).val() == 2){ $("#copy").show();}else{ $("#copy").hide();   }                
    });
		
        var count_mail = $('#all_subject').val();
        var send_mail = 0;
        var count = 10;
        var mails ='';
		var ctn = 0;
		var er = 0;
		
		function sendSave(url, data, save) {
		var intro = tinymce.get('page_body').getContent().replace(/&/g,"#");
		var ending = tinymce.get('page_ending').getContent().replace(/&/g,"#");
var new_data = data + '&intro='+intro+'&ending='+ending+ '&save='+ save;

			surl = url+"&"+new_data;
//		console.log(surl);
            $.ajax({
                url: surl,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (data) {
                    if (data.status = 'send') {
                            alert('Рассылка сохранена.');
                    }
                }
            });

        }

        function sendMail(url, data, go) {
		var intro = tinymce.get('page_body').getContent().replace(/&/g,"#");
		var ending = tinymce.get('page_ending').getContent().replace(/&/g,"#");
var new_data = data + '&from_mail=' + send_mail + '&count=' + count +'&intro='+encodeURIComponent(intro)+'&ending='+encodeURIComponent(ending)+ '&go='+go;

//			console.log(new_data);
//			url=url+"&"+new_data;
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
						ctn+=res.cnt;
						er+=res.er;
						$('#all_p').html(ctn);
						$('#all_p_er').html(er);
                        var proc = (send_mail / count_mail) * 100;
						$(".progress-bar").css('width', Math.round(proc , 2)+'%');
						$(".progress-bar").html(Math.round(proc, 2)+'%');
                        if (send_mail <= count_mail && go == 0) {
                            sendMail(url, data, 0);
                        } else {
						
                            alert('Рассылка разослана.');
                            $('.mailing_start').hide();
                        }
						
                    }
                },
				error: function(res){
				
					$('.mailing_start').html(res.statusText);
				console.log(res);
					}
            });

        }
		
		function sendMailTest(url, data, test) {
		var intro = tinymce.get('page_body').getContent().replace(/&/g,"#");
		var ending = tinymce.get('page_ending').getContent().replace(/&/g,"#");
var new_data = data + '&from_mail=' + send_mail + '&count=' + count +'&intro='+encodeURIComponent(intro)+'&ending='+encodeURIComponent(ending)+ '&test='+ test;

			surl = url+"&"+new_data;
			console.log(surl);
            $.ajax({
                url: surl,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                    if (res.status = 'send') {
                            alert('Рассылка отправлена на '+res.from);
                    }
                },
				error: function(res){
					$('.mailing_start').html(res.statusText);
				console.log(res);
					}
            });

        }
		
		

        $('#send_test').click(function () {
            var url = '/admin/generalmailing/';
            var data = $('#mail_form').serialize();
            //$('.mailing_start').show();
            sendMailTest(url, data, 1);
			//$('#show_emails').show();
			//$('#send_emails').show();
			///alert('Сообщение отправлено на тестовый email.');

        });
		 $('#savepost').click(function () {
            var url = '/admin/generalmailing/';
            var data = $('#mail_form').serialize();
           // $('.mailing_start').show();
            sendSave(url, data, 2);
			//$('#show_emails').show();
			//$('#send_emails').show();
			alert('Сообщение отправлено на сохранение.');

        });

        $('#send_all').click(function () {
			
            var url = '/admin/generalmailing/';
            var data = $('#mail_form').serialize();

            $(this).attr('disabled', 'true');
            $('.mailing_start').show();
            sendMail(url, data, 0);
			$('#show_emails').show();
			$('#send_emails').show();
				$('.alert-success').toggleClass('show');
				$('.alert-danger').toggleClass('show');
				
            alert('Рассылка стартовала. Дождитесь окончания!');
        });



    });
	tinymce.init({
		selector: "textarea",
		width: 750,
		height: 400,
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
