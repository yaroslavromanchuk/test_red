<div class="block green-block">
	<div class="inner">
		<div id="errordiv"></div>	
		<form name="send" method="post" action="/account/send2friend/" id="send-form">
			<div>
				<br clear="all" />
				<p class="form-item-title">Name from *</p><input name="name_from" type="text" class="form-input-style7" value="" />
				<p class="form-item-title">E-mail from *</p><input name="email_from" type="text" class="form-input-style7" value=""/>
				<p class="form-item-title">Name to *</p><input name="name_to" type="text" class="form-input-style7" value="" />
				<p class="form-item-title">E-mail to *</p><input name="email_to" type="text" class="form-input-style7" value="" />
				<p class="form-item-title">Message </p><textarea name="comment" class="form-textarea-style1"></textarea>
			</div>
			<br clear="all" />
			<div class="left button button-green spacer-left"><span class="button-inner"><input type="submit" value="Send" /></span></div>
		</form>
		<div class="clear"></div>
	</div>
</div>
<script type="text/javascript">
	validateForm('#send-form', buildURL('account', 'validateSend2friend'), false, 'errordiv');
</script>
