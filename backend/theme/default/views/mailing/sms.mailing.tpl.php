<style>.saved{display:none;}
</style>
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading">
    <h3 class="panel-title">
        <img src="<?=$this->getCurMenu()->getImage()?>" alt=""  class="page-img"/><?=$this->getCurMenu()->getTitle()?>
    </h3>
</div>
<div class="panel-body">
<p>Рассылка будет отправлена <?php $cc = wsActiveRecord::useStatic('Customer')->count(array('time_zone_id' => 5)); echo $cc;?> пользователям.</p>
<input type="hidden" id="all_subject" name="all_subject" value="<?=$cc?>"/> 
<div class="alert alert-success saved" role="alert"></div>
<div class="alert alert-danger errors " style="display: none;" role="alert">
    
</div>

<div class="mailing_start" style="display: none;">
	<img src="/img/loading_trac.png" width="20" alt="loading"/> Рассылка стартовала, подождите...<br/>
<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
  </div>
</div>
</div>
<p id="show_phones" style="display: none;font-size: 16px;font-weight: bold;">Список всех номеров на которые уже разослано:</p>
<div id="send_phones" style="display: none; width: 960px; max-height: 120px; overflow-y: scroll;border: 1px dashed #C00000;padding: 5px 10px;"></div>
</div>
<div class="panel-body">
<div class="form-group">
    <label for="type" class="ct-110 control-label">Рассылка:</label>
    <div class="col-xs-8">
        <select name="type" id="type" class="form-control input select2 " onChange=" return select_type(this);" data-placeholder="Выберите тип рассылки" tabindex="-1" aria-hidden="true">
            <option label="Все"></option>
                        <option value="order">Заказы</option>
                      <option value="customer">Клиенты</option>
                  </select>
				  </div>
  </div>
  </div>
 <div class="panel-body">
 <div class="panel panel-default">

<div class="panel-body" id="insert_form">

</div>
</div>
</div>
<div class="panel-body">
<div class="form-group">
	<label for="phone" class="control-label col-lg-2">Баланс:</label>
    <div class="col-lg-6">
<input name="send_balance" type="button" class="btn btn-small btn-default" id="send_balance" value="Смотреть"/>
	</div>
	</div>
</div>
</div>
</div>

  
<?php if ($this->errors) { ?>
    <div id="conf-error-message">
        <p><img src="/img/icons/remove-small.png" class="iconnew" alt=""/>Возникли ошибки при
            отправке:</p>
        <ul>
            <?php foreach ($this->errors as $error) { ?>
                <li><?=$error;?></li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>


<script>
function select_type(e){
	//console.log(e);
	var type = e.value;

           $.ajax({
                url: '/admin/smsmailing/',
                type: 'POST',
                dataType: 'json',
                data: {type: e.value},
                success: function (res) {
				if(res.result){
					
					$('#insert_form').html(res.message); 
					
					}
				console.log(res);
				//alert(res.ms);
				},
				error: function (e) {
				console.log(e);
				}
            });
			return false;
	
	console.log(type);
}
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
var new_data = data + '&test='+test;
//			console.log(surl);
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
		if(res.status == 'send' && res.sms){
                    $('.saved').html('SMS на номер '+res.ms.phone+' - '+res.ms.status); $('.saved').show();
                }else{
                    var t = '';
                    for(var key in res.ms[0]){
                       t += res.ms[0][key]+'<br>';
                    }
                    $('.errors').html(t);
                }
				console.log(res);
				//alert(res.ms);
				
		},
		error: function (e) {
				console.log(e);
		}
            });
            return false;
        }
		
		

        $('#send_test').click(function () {
            var url = '/admin/smsmailing/';
            var data = $('#mail_form').serialize();
           sendMailTest(url, data, 1);
        });
	$('#send_balance').click(function () {
            var url = '/admin/smsmailing/';
            var data = '?&balance=1';
           $.ajax({
                url: '/admin/smsmailing/',
                type: 'POST',
                dataType: 'json',
                data: '?&balance=1',
                success: function (res) {
				if(res.status == 'send'){ $('.saved').html('<strong>'+res.ms+'</strong>'); $('.saved').show(); }
				console.log(res);
				//alert(res.ms);
				},
				error: function (e) {
				console.log(e);
				}
            });
			return false;
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