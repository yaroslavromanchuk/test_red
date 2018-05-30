<script>
	dataLayer = [{
    'transactionId': '<?php if(@$_SESSION['order']) { echo $_SESSION['order']['id']; }?>',
	'transactionAffiliation': 'www.red.ua',
    'transactionTotal': '<?php if(@$_SESSION['order']){ echo $_SESSION['order']['amount'];} ?>'
}];

dataLayer.push({'id_page': 'pays'});
dataLayer.push({'event' : 'order','eventAction' : 'new_order', 'eventLabel' : '<?=$_SESSION['order']['id']?>', 'eventValue' : '<?=$_SESSION['order']['amount']?>' });
</script>
<form action="https://lmi.paymaster.ua/" method="POST" name="payment_form" id="payment_form">
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1">
				<div class="col-xs-6 form-group">
					<input id="LMI_MERCHANT_ID" name="LMI_MERCHANT_ID" type="hidden" required="" placeholder="LMI_MERCHANT_ID" class="form-control" value="<?=$this->pay_data['LMI_MERCHANT_ID']?>">
				</div>

				<div class="col-xs-6 form-group">
					<input id="LMI_PAYMENT_AMOUNT" name="LMI_PAYMENT_AMOUNT" type="hidden" required="" placeholder="LMI_PAYMENT_AMOUNT" class="form-control" value="<?=$this->pay_data['LMI_PAYMENT_AMOUNT']?>">
				</div>
				
				<div class="col-xs-6 form-group">
					<input id="LMI_PAYMENT_NO" name="LMI_PAYMENT_NO" type="hidden" required="" placeholder="LMI_PAYMENT_NO" class="form-control" value="<?=$this->pay_data['LMI_PAYMENT_NO']?>">
				</div>

				<div class="col-xs-6 form-group">
					<input id="LMI_PAYMENT_DESC" name="LMI_PAYMENT_DESC" type="hidden" required="" placeholder="LMI_PAYMENT_DESC" class="form-control" value="<?=$this->pay_data['LMI_PAYMENT_DESC']?>">
				</div>
<?php /*
				<div class="col-xs-6 form-group">
					<input id="LMI_PAYMENT_DESC_BASE64" name="LMI_PAYMENT_DESC_BASE64" type="text" required="" placeholder="LMI_PAYMENT_DESC_BASE64" class="form-control" value="Заказ № хххххх">
				</div>
*/ ?>
<?php /*
				<div class="col-xs-6 form-group">
					<input id="LMI_SUCCESS_URL" name="LMI_SUCCESS_URL" type="text" required="" placeholder="LMI_SUCCESS_URL" class="form-control" value="">
				</div>
*/ ?>
<?php /*
				<div class="col-xs-6 form-group">
					<input id="LMI_SUCCESS_METHOD" name="LMI_SUCCESS_METHOD" type="text" required="" placeholder="LMI_SUCCESS_METHOD" class="form-control" value="">
				</div>
*/ ?>
<?php /*
				<div class="col-xs-6 form-group">
					<input id="LMI_FAIL_URL" name="LMI_FAIL_URL" type="text" required="" placeholder="LMI_FAIL_URL" class="form-control" value="">
				</div>
*/ ?>
<?php /*
				<div class="col-xs-6 form-group">
					<input id="LMI_FAIL_METHOD" name="LMI_FAIL_METHOD" type="text" required="" placeholder="LMI_FAIL_METHOD" class="form-control" value="">
				</div>
*/ ?>
<?php /*
				<div class="col-xs-6 form-group">
					<input id="LMI_EXPIRES" name="LMI_EXPIRES" type="text" required="" placeholder="LMI_EXPIRES" class="form-control" value="">
				</div>
*/ ?>
				<div class="col-xs-6 form-group">
					<input id="LMI_PAYMENT_SYSTEM" name="LMI_PAYMENT_SYSTEM" type="hidden" required="" placeholder="LMI_PAYMENT_SYSTEM" class="form-control" value="<?=$this->pay_data['LMI_PAYMENT_SYSTEM']?>">
				</div>

				<div class="col-xs-6 form-group">
				<input id="LMI_SIM_MODE" name="LMI_SIM_MODE" type="hidden" required=""  placeholder="LMI_SIM_MODE" class="form-control" value="<?=$this->pay_data['LMI_SIM_MODE']?>">
				</div>
<?php /*
				<div class="col-xs-6 form-group">
					<input id="LMI_PAYER_PHONE_NUMBER" name="LMI_PAYER_PHONE_NUMBER" type="text" required="" placeholder="LMI_PAYER_PHONE_NUMBER" class="form-control" value="">
				</div>
*/ ?>
<?php /*
				<div class="col-xs-6 form-group">
					<input id="LMI_PAYER_EMAIL" name="LMI_PAYER_EMAIL" type="text" required="" placeholder="LMI_PAYER_EMAIL" class="form-control" value="">
				</div>
*/ ?>
				<div class="col-xs-6 form-group">
					<input id="LMI_HASH" name="LMI_HASH" type="hidden" required="" placeholder="LMI_HASH" class="form-control" value="<?=$this->pay_data['LMI_HASH']?>">
				</div>

				<div class="col-xs-12 form-group">
					<button type="submit" class="btn btn-default">Оплата</button>
				</div>
		</div>
	</div>
</form>
<script type="text/javascript">
	document.forms.payment_form.submit();
</script>