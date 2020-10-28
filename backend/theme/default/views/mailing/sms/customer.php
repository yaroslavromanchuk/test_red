<form method="POST" id="mail_form" action="<?=$this->path?>smsmailing/" target="_blank" class="form-horizontal " >

<div class="form-group">
    <label for="sms" class="control-label  col-lg-2">Текст сообщения:</label>
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
	<label for="phone" class="control-label col-lg-2">Все готово?</label>
    <div class="col-lg-6">
	<input type="button" id="send_all" class="btn btn-small btn-default" name="send_full" id="savepage" value="Отправить рассылку"/>
	</div>
	</div>
	
</form>