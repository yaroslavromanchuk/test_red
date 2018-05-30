<div class="block green-block">
	<h1 class="green"><?php echo $this->curMenu->getTitle(); ?></h1>
	<div class="inner">
		<form name="change_password" method="post" action="/account/changepassword/" id="changepassword-form">
			<p>Maak hier je wachtwoord aan, hiermee kun je jouw gegevens ten alle tijden bewerken.</p>
			<p class="form-item-title">Oud wachtwoord *</p><input type="password" class="quart" name="oldpass" id='oldpass' value=""/>
			<p class="form-item-title">Wachtwoord *</p><input name="password" type="password" value="" class="form-input-style10" /><p class="form-item-title2">Bevestig wachtwoord</p><input name="password2" type="password" value="" class="form-input-style10a" />			
			<div class="left button button-green spacer-left"><span class="button-inner"><input type="submit" value="Save" /></span></div>
		</form>
		<div class="clear"></div>
	</div>
</div>
<script type="text/javascript">
	validateForm('#changepassword-form', buildURL('account', 'validatePassword'), false);
</script>
