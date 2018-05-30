<?php header('Access-Control-Allow-Origin: *'); ?>
<img src="<?=SITE_URL.$this->getCurMenu()->getImage();?>" alt=""  class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle();?></h1>
<hr/>
<p>Рассылка будет
    отправлена <?php $cc = wsActiveRecord::useStatic('Customer')->count(array('time_zone_id' => 5)); echo $cc;?>
    пользователям.</p>
<input type="hidden" id="all_subject" name="all_subject" value="<?=$cc?>"/>   
<?php if ($this->errors) { ?>
    <div id="conf-error-message">
        <p><img src="<?=SITE_URL;?>/img/icons/remove-small.png" class="iconnew" alt=""/>Возникли ошибки при
            отправке:</p>
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
            <strong><?php echo (int)$this->saved;?> sms отправленно.</strong></p>
    </div>
<?php } ?>

<div class="mailing_start" style="display: none;">
	<img src="/img/loading_trac.png" width="20" alt="loading"/> Рассылка стартовала, подождите...<br/>
<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
  </div>
</div>
</div>
<p id="show_phones" style="display: none;font-size: 16px;font-weight: bold;">Список всех номеров на которые уже разослано:</p>
<div id="send_phones" style="display: none; width: 960px; max-height: 120px; overflow-y: scroll;border: 1px dashed #C00000;padding: 5px 10px;"></div>

<form method="POST" id="mail_form" action="<?=$this->path?>smsmailing/" target="_blank" class="form-horizontal">
<div class="form-group">
    <label for="sms" class="ct-110 control-label">Текст сообщения:</label>
    <div class="col-xs-6">
	<input name="subject" type="text" style="max-width: 550px;" placeholder="Введите сообщение" class="form-control input" id="sms" maxlength="67" value="<?=$this->post->subject; ?>"/>
    </div>
  </div>
  <div class="form-group">
    <label for="phone" class="ct-110 control-label">Тест SMS:</label>
    <div class="col-xs-6">
	<input name="test_phone" type="text" readonly class="form-control input" id="phone" value="<?=$this->user->phone1;?>"/>
	<input name="send_test" type="button" class="btn btn-small btn-default" id="send_test" value="Отправить"/>
	</div>
	</div>
	<div class="form-group">
    <label for="savepage" class="ct-110 control-label">Разослать:</label>
    <div class="col-xs-6">
	<input type="button" id="send_all" class="btn btn-small btn-default" name="send_full" id="savepage" value="Отправить рассылку"/>
	</div>
	</div>
</form>
<script type="text/javascript">
    $(document).ready(function () { 

        var count_phone = $('#all_subject').val();
        var send_phone = 0;
        var count = 10;
        var phones ='';

        function sendMail(url, data, go) {
var new_data = data + '&from=' + send_phone + '&count=' + count + '&go='+go;

			surl = url+"&"+new_data;
//			console.log(surl);
            $.ajax({
                url: surl,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                    if (res.status = 'send') {
                        send_phone += count;
                        phones += res.phones;
                        $('#send_phones').html(phones);
                        var proc = (send_phone / count_phone) * 100;
                       //
					   $(".progress-bar").css('width', Math.round(proc , 2)+'%');
						$(".progress-bar").html(Math.round(proc, 2)+'%');
						//
                        if (send_phone <= count_phone && go == 0) {
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
var new_data = data + '&test='+ test;
			surl = url+"&"+new_data;
//			console.log(surl);
            $.ajax({
                url: surl,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) { alert(res.ms);}
            });
        }
		
		

        $('#send_test').click(function () {
            var url = '/admin/smsmailing/';
            var data = $('#mail_form').serialize();
           sendMailTest(url, data, 1);
        });

        $('#send_all').click(function () {
            var url = '/admin/smsmailing/';
            var data = $('#mail_form').serialize();

            $(this).attr('disabled', 'true');
			$('#send_test').attr('disabled', 'true');
            $('.mailing_start').show();
            sendMail(url, data, 0);
			$('#show_emails').show();
            alert('Рассылка стартовала. Дождитесь окончания!');
        });
    });
</script>
<!-- /TinyMCE --> 	
