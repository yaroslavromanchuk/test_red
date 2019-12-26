<?php header('Access-Control-Allow-Origin: *'); 
header('X-XSS-Protection: 0');?>
<style>.modal-dialog{max-width:730px;width: 730px;}</style>
<script src="<?=$this->files?>scripts/ui/jquery-ui-1.9.1.custom.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?=$this->files?>scripts/ui/jquery-ui-1.9.1.custom.css" type="text/css" media="screen"/>
<style>
.progress{
   position: relative;
    top: -40%;
    width: 95%;
    margin: auto; 
}
</style>
<div class="card pd-20 mb-3">
     <h5 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h5>
  <p class="card-subtitle mb-2 text-muted">Форма работы с рассылками</p>
<div class="alert alert-success d-none" role="alert">
  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  <span class="sr-only1">Отправлено писем: </span><span id="all_p"><?=(int)$this->saved?></span>
</div>
<div class="alert alert-danger d-none"  role="alert">
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
  
<p id="show_emails" style="display: none;font-size: 16px;font-weight: bold;">Список всех E-mail на которые уже разослано:</p>
<div id="send_emails" style="display: none; width: 960px; max-height: 200px; overflow-y: scroll;border: 1px dashed #C00000;padding: 10px;"></div>


  <p>Рассылка будет отправлена <span class="count_sub_email_span">0</span> подписчикам.</p>
  <input type="number" hidden="hidden" id="all_subject" name="all_subject" value=""/>
  
  <form method="POST" id="mail_form" class="was-validated" action="<?=$this->path?>customerssegmentmailing/" target="_blank">
    <div class="card-body">
        <div class="row">
           
            <div class="col-sm-12 col-md-12 col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Тема письма: <span class="tx-danger">*</span></label>
                  <input name="subject" type="text" class="form-control" id="paginatitle" required="true"
                       value="<?php if(@$this->post->subject){ echo $this->post->subject;}elseif($this->pemail->subject){ echo $this->pemail->subject;} ?>"/>
                  </div>
            </div>
             <div class="col-sm-12 col-md-12 col-lg-4">
               <div class="form-group">
                  <label class="form-control-label">Кому отправлять: <span class="tx-danger">*</span></label>
                   <select name="segment_id" class="form-control select23" data-placeholder="Кому будем отправлять:" required  id="type_sub">
                <option label="Кому будем отправлять:"></option>
                <?php
                //$('#all_subject').val(this.value);
                foreach (CustomersSegment::getListCustomersSegmentType(true) as $v) { ?>
                <option value="<?=$v->id?>" data-count="<?=$v->ctn?>" <?php  if($v->id == $this->pemail->segment_id) { echo 'selected';}?> ><?=$v->name?> (<?=$v->ctn?>)</option>
                  <?php  } ?>
                </select>
                </div>
                 <div class="invalid-feedback">
        Нужно указать
      </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-4">
                 <div class="form-group">
                  <label class="form-control-label">Начало обращения: </label> <label class="ckbox d-inline-block">
<input type="checkbox" id="s_start" name="s_start" onclick="agreeForm(this.form)" value="1">
  <span>Добавить приветствие</span>
</label>
                  <input type="hidden" id="id_post"  name="id_post" value="<?=@$this->pemail->id?$this->pemail->id:''?>">
		<input name="subject_start" type="text" class="form-control"  disabled
                       value="<?php if(@$this->post->subject_start){ echo $this->post->subject_start;}elseif($this->pemail->subject_start){ echo $this->pemail->subject_start;} ?>"/>
               
               
                  </div>
            </div>
            
       <!--     <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="form-group">
                  <label class="form-control-label">Ссылка для отслеживания: <span class="tx-danger">*</span></label>
<input name="extra_url" type="text" class="form-control " disabled="true"
value="<?=$this->post->extra_url?$this->post->extra_url : 'utm_source=emailletter_'.date('d.m.Y').'&utm_medium=email&utm_campaign=EmailLetter'?>"/>
<input name="unsubscribe" hidden type="text" 
value="<?=@$this->post->unsubscribe ? $this->post->unsubscribe : '&utm_source=unsubscribe_'.date('d.m.Y').'&utm_medium=email&utm_campaign=EmailLetter'?>"/>
                  </div>
            </div>-->
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="form-group">
                    <label class="form-control-label">Баннеры и текст вначале:</label> <p>(размер изображения по ширине нужно устанавливать = 700)<br>(ссылки должны начинаться c http://<br> все относительные ссылки будут идти от домена red.ua)</p>
                  <textarea name="intro" class="pagetext-s form-control" id="page_body" ><?php if(@$this->post->intro){ echo @stripslashes($this->post->intro);}elseif($this->pemail->intro){ echo @stripslashes($this->pemail->intro);}?></textarea>
                  </div>
            </div>
            <?php
               
                    $mas = [];
                    $cat = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1, 'parent_id'=>0, 'id not in(85,106,267)'));
 foreach ($cat as $c) {
     $cid = $c->getKidsIds();
     $c_cid = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1, 'parent_id'=>$c->id,  'id in ('.implode(',',$cid).')'));
     foreach ($c_cid as $value) {
         if(count($value->getKidsIds()) > 0){
              foreach (wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1,  'id in ('.implode(',',$value->getKidsIds()).')')) as $value_d) {
                  $mas[$c->name][$value_d->id] = $value_d->getRoutez();
              }
         }else{
              $mas[$c->name][$value->id] = $value->getRoutez();
         }
         
     }
    
 }
 //l($mas);
		ksort($mas);
               for($i=0; $i<6; $i++){ ?>
                    <div class="col-sm-12 col-md-12 col-lg-4">
                <div class="form-group">
                    
                
                  <label class="form-control-label">Товар - <?=$i+1?>: </label>
                  <select class="form-control select2" onChange="loadArticles(this.value, <?=$i?>); return false;" data-placeholder="Выберите категорию" >
                      <option label="Выберите категорию"></option>
					<?php foreach ($mas as $kay => $value) {?>
                      <optgroup label="<?=$kay?>">
                         <?php  foreach($value as $k => $v){ ?>
                             <option value="<?=$k?>"><?=$v?></option>
                       <?php  } ?>
                      </optgroup>
					
					<?php } ?>
				</select><br/>

		<select name="article_id[<?=$i?>]" size="5" class="form-control select2  article" rel="<?=$i?>"></select>
		<div class="aih_box" rel="<?=$i?>"></div>
                </div>
            </div>
                   
              <?php } 
                
		?>
             <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="form-group">
                    <label class="form-control-label">Баннеры и текст в конце:</label>
                 <textarea name="ending" class="pagetext-s form-control" id="page_ending"><?php if(@$this->post->ending){ echo @stripslashes($this->post->ending);}elseif($this->pemail->ending){ echo @stripslashes($this->pemail->ending);}?></textarea>
                  </div>
            </div>
        </div>       
	  <!-- <tr>
            <td class="kolom1 hide">Вложить новости</td>
            <td>
                <?php //foreach (wsActiveRecord::useStatic('News')->findAll(array('sent_general' => null)) as $news) {
                   // $sel = (@$this->post->news[$news->getId()]) ? 'checked="checked"' : '';
                   // echo '<label><input name="news[' . $news->getId() . ']" type="checkbox" value="1" ' . $sel . '/>' . $news->getTitle() . '</label><br />';
               // } ?>
            </td>
			
        </tr>-->
   </div>
  <div class="card-footer">
      <div class="row">
          <div class="col-sm-12 col-lg-6">
              <label class="form-control-label">Тестовый e-mail:</label> 
              <div class="input-group mb-3">
                   
  <input type="text" class="form-control"  name="test_email" id="paginatitle2" value="<?=$this->user->email;?>" aria-describedby="send_test" >
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" name="send_test"  id="send_test"  type="button" >Отправить тест</button>
  </div>
</div>
              <div class="input-group" >  <label class="rdiobox">
                                    <input name="copy" type="radio" value="0" checked >
                                    <span>Без копии</span>
                                </label><label class="rdiobox">
                                    <input type="radio" name="copy" value="2"><span>Добавить в копию</span></label>
				<!--<label><input type="radio" name="copy" value="1">Видимая копия</label>-->
                  </div>
              <div class="form-group" id="copy" style="display:none;" >
                 <label class="form-control-label">Копия:</label> 
                 <input name="copy_email" type="text" class="form-control" placeholder="Email"  value=""/>
              </div>
              
          </div>
          <div class="col-sm-12 col-lg-6 text-right">
              <label class="form-control-label">Действия:</label><br>
              <div class="btn-group" role="group" aria-label="Basic example">
  <button type="botton"  name="preview" class="btn btn-outline-secondary"  id="view_test">Посмотреть</button>
  <!--<button type="button"  name="savepost" id="savepost" class="btn btn-outline-success">Сохранить</button>-->
  <button type="submit"  name="send_full" id="send_all" class="btn btn-outline-danger">Отправить всем</button>
</div>
          </div>
      </div>
  </div> 
</form>

  
</div>

<script  src="<?=$this->files?>scripts/tinymce/tinymce.min.js"></script>
<script>

 $(function(){
        'use strict';

var  count_all = $('#all_subject');    
        
var selectType = $('#type_sub');
/** Запись количесва емейлов на отправку */
if(selectType.val()){
count_all.val(selectType.find(':selected').data('count'));
 $('.count_sub_email_span').html(selectType.find(':selected').data('count'));
}
 /** Выход с  записи количесва емейлов на отправку */
 
selectType.change(function(){
    var c = selectType.find(':selected').data('count');
     count_all.val(c);
     $('.count_sub_email_span').html(c);
  });

// var count_mail = $('#all_subject').val(); 
        var send_mail = 0;
        var count = 10;
        var mails ='';
	var ctn = 0;
	var error = 0;

	$('.article').change(function () {
		var item = $(this).attr("rel");
		console.log('111');
		$('.aih_box[rel="'+item+'"] img').hide();
		$('.aih_box[rel="'+item+'"] #aih_' + $(this).val()).show();
	});
        
	$('[type="radio"]').click(function(){
            if($(this).val() == 1 || $(this).val() == 2){ $("#copy").show();}else{ $("#copy").hide();   }                
        });
		
       
       $('#mail_form').submit(function (e) {
        $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div><br><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>'}).appendTo('body');
            sendMail('/admin/customerssegmentmailing/', $('#mail_form').serialize(), 'go_send_email', '');
            $('.alert-success').toggleClass('d-none');
            $('.alert-danger').toggleClass('d-none');
		
       return false;
    });
       

        $('#send_test').click(function () {
            sendMailTest('/admin/customerssegmentmailing/', $('#mail_form').serialize(), 'go_test_email');
            alert('Сообщение отправлено на тестовый email.');
        });
        
	$('#savepost').click(function () {
            if($('#type_sub option:selected').val()){
            sendSave('/admin/customerssegmentmailing/', $('#mail_form').serialize(), 'save');
	alert('Сообщение отправлено на сохранение.');
    }else{
        alert('Заполните поля отмеченнные красным');
        $('#type_sub').focus();
    }
        });
                ///admin/customerssegmentmailing/
          $('#view_test').click(function () {
            //  console.log(tinymce.get('page_body').getContent());
            //  console.log(tinymce.get('page_ending').getContent());
              var intro = tinymce.get('page_body').getContent();//.replace(/&/g,"#");
		var ending = tinymce.get('page_ending').getContent();//.replace(/&/g,"#");
              $.ajax({
                url: '/admin/customerssegmentmailing/',
                type: 'POST',
                dataType: 'json',
                data: $('#mail_form').serialize()+'&method=preview&intro='+intro+'&ending='+ending,
                success: function (data) {
                  //  console.log(data);
                    fopen(data.title, data.message);
                },
                error: function(e){
                    console.log(e);
                }
            });
            return false;
        });

    
    function sendMail(url, data, go, track) {
 var  count_mail = $('#all_subject').val();
       
            console.log('count='+count_mail);
            
		var intro = tinymce.get('page_body').getContent();//.replace(/&/g,"#");
		var ending = tinymce.get('page_ending').getContent();//.replace(/&/g,"#");
var new_data = data + '&from_mail=' + send_mail + '&count=' + count +'&intro='+intro+'&ending='+ending+'&method='+go+'&track='+track+'&all_count='+count_mail;

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                    console.log(res);
                    console.log('send='+res.cnt+', error='+res.error, 'track='+res.track);
                        send_mail += count;
                        mails += res.emails;
                        $('#send_emails').html(mails);
						ctn+=res.cnt;
						error+=res.error;
						$('#all_p').html(ctn);
						$('#all_p_er').html(error);
                        var proc = (send_mail / count_mail) * 100;
				$(".progress-bar").css('width', Math.round(proc , 2)+'%');
				$(".progress-bar").html(Math.round(proc, 2)+'%');
                        if (send_mail <= count_mail) {
                            sendMail(url, data, 'go_send_email', res.track);
                        } else {
                  send_mail = 0;
        //count = 10;
        mails ='';
	ctn = 0;
	error = 0;
                          $('#foo').detach();
                          $('body,html').animate({scrollTop: 0}, 1);
                        }
		
                },
				error: function(res){
				
				//$('.mailing_start').html(res.statusText);
				console.log(res);
					}
            });

        };
        });
		
		function sendMailTest(url, data, test) {
		var intro = tinymce.get('page_body').getContent();//.replace(/&/g,"#");
		var ending = tinymce.get('page_ending').getContent();//.replace(/&/g,"#");
                var new_data = data + '&intro='+encodeURIComponent(intro)+'&ending='+encodeURIComponent(ending)+ '&method='+ test;

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                    if (res.status == 'send') {
                            alert('Рассылка отправлена на '+res.from);
                    }
                },
		error: function(res){
		//$('.mailing_start').html(res.statusText);
		console.log(res);
		}
            });
            return false;

        }
    
    	function sendSave(url, data, save) {
		var intro = tinymce.get('page_body').getContent();//.replace(/&/g,"#");
		var ending = tinymce.get('page_ending').getContent();//.replace(/&/g,"#");
                var new_data = data + '&intro='+intro+'&ending='+ending+ '&method='+ save;
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (data) {
                            alert(data.message);
                }
            });
            return false;

        };
        
    
	function loadArticles(category_id, i) {
		var data_to_post = new Object();
		data_to_post.id = category_id;
		data_to_post.getarticles = '1';
		$.post('/email/gomail/', data_to_post, function (data) {
			createSelectList(data, this, i);
                      //  console.log(data);
		}, 'json');
		$('#article_id_'+i).html('');
		$('#option_id').html('');
	}

	function createSelectList(data, a, item) {
		if ('done' == data.result) {
			var out = '';
			var himg = '';
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
       function agreeForm(f) {
    // Если поставлен флажок, снимаем блокирование кнопки
    if (f.s_start.checked) f.subject_start.disabled = 0 
    // В противном случае вновь блокируем кнопку
    else f.subject_start.disabled = 1
   }
tinymce.init({
		selector: "textarea",
		//width: 750,
		height: 500,
		language : 'ru',
		plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			 "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
	   ],
	   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
	   toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | fontsizeselect | preview | code ",
	   image_advtab: true ,
	   
	   external_filemanager_path:"/backend/scripts/filemanager/",
	   filemanager_title:"Responsive Filemanager" ,
	   external_plugins: { "filemanager" : "/backend/scripts/filemanager/plugin.min.js"},
	   convert_urls: false
	 });
        
         
</script>
<!-- /TinyMCE --> 	
