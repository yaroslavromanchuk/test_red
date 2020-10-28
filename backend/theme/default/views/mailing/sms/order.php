<form method="POST" id="mail_form" action="<?=$this->path?>smsmailing/"  class="form-horizontal " >
<div class="form-group">
    <label for="status" class="control-label  col-lg-2">Статус:</label>
    <div class="col-xs-8">
        <select name="status" id="status" class="form-control input select2 "  data-placeholder="Выберите статус заказа" tabindex="-1" aria-hidden="true">
            <option label="Все"></option>
			<?php foreach($this->status as $s){ ?>
                        <option value="<?=$s->id?>"><?=$s->name?></option>
			<?php } ?>
                  </select>
				  </div>
  </div>
<div class="form-group">
    <label for="orders" class="control-label  col-lg-2">Заказы:</label>
    <div class="col-lg-6">
	<input name="orders" type="text" style="max-width: 550px;" placeholder="Введите заказы, через ','" class="form-control input" id="orders" />
    </div>
  </div>
<div class="form-group">
    <label for="sms" class="control-label  col-lg-2">Сообщение:</label>
    <div class="col-lg-6">
	<input name="subject" type="text" style="max-width: 550px;" placeholder="Введите сообщение" class="form-control input" id="sms" maxlength="67" value="<?=$this->post->subject; ?>"/>
    </div>
  </div>
  <div class="form-group">
    <label for="phone" class="control-label col-lg-2">Тест SMS:</label>
    <div class="col-lg-6">
	<input name="test_phone" type="text" readonly class="form-control input" id="phone" value="<?=$this->user->phone1;?>"/>
	<input name="send_test" type="button" class="btn btn-small btn-default" id="send_test" value="Отправить"/>
	
	</div>
	</div>
	<div class="form-group">
	<label  class="control-label col-lg-2">Все готово?</label>
    <div class="col-lg-6">
	<input type="submit" id="send_order" class="btn btn-small btn-default" name="send_order"  value="Отправить рассылку"/>
	</div>
	</div>
	
</form>
<script>
$(document).ready(function () {

$("#mail_form").submit(function (e) {
        e.preventDefault();
		var orders = $('#orders').val();
		var sms = $('#sms').val();
		
		$.ajax({
                url: '/admin/smsmailing/',
                type: 'POST',
                dataType: 'json',
                data: {method: 'send_sms_order', orders: orders, message: sms},
                success: function (res) {
				//if(res.result){
					
					//$('#insert_form').html(res.message); 
					
					//}
				console.log(res);
				//alert(res.ms);
				},
				error: function (e) {
				console.log(e);
				}
            });
		
		return false;
//какие то действия
        //$(this).submit();
    });
	});
</script>