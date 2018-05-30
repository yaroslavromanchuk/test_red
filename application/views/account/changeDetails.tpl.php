<div class="block green-block">
	<h1 class="green"><?php echo $this->curMenu->getTitle(); ?></h1>
	<div class="inner">
		<form name="change_details" method="post" action="/account/changedetails/" id="changedetails-form">
			<div class="section-holder-underline">
				<br clear="all" />
				<p class="form-item-title">Gebruikersnaam</p><input name="username" type="text" class="form-input-style4" value="<?php echo $this->webshop->getCustomer()->getUsername()?>" readonly=""/>
				<p class="form-item-title">Geslacht *</p><input name="gender" type="radio" value="f" class="form-input-style1" <?php if ($this->webshop->getCustomer()->getGender() == "f") echo "checked";?> /><p class="form-item-label">Vrouw</p><input name="gender" type="radio" value="m" class="form-input-style1" <?php if ($this->webshop->getCustomer()->getGender() == "m") echo "checked";?> /><p class="form-item-label">Man</p>
				<br clear="all" />
				<p class="form-item-title">Voornaam *</p><input name="first_name" type="text" class="form-input-style2" value="<?php echo $this->webshop->getCustomer()->getFirstName()?>" />
				<p class="form-item-title">Tussenvoegsel</p><input name="middle_name" type="text" class="form-input-style3" value="<?php echo $this->webshop->getCustomer()->getMiddleName()?>" />
				<br clear="all" />
				<p class="form-item-title">Achternaam *</p><input name="last_name" type="text" class="form-input-style4" value="<?php echo $this->webshop->getCustomer()->getLastName()?>" />
				<br clear="all" />
				<p class="form-item-title">Telefoon thuis *</p><input name="phone" type="text" class="form-input-style9" value="<?php echo $this->webshop->getCustomer()->getPhone1()?>" /><p class="form-item-title">Telefoon mobiel</p><input name="mobile" type="text" class="form-input-style9a" value="<?php echo $this->webshop->getCustomer()->getPhone2()?>" />
				<br clear="all" />
				<p class="form-item-title">E-mail adres *</p><input name="email" type="text" class="form-input-style4" value="<?php echo $this->webshop->getCustomer()->getEmail()?>" />
			</div>
			<div class="left button button-green spacer-left"><span class="button-inner"><input type="submit" value="Save" /></span></div>
		</form>
		<div class="clear"></div>
	</div>
</div>
<script type="text/javascript">
	validateForm('#changedetails-form', buildURL('account', 'validateDetails'), false);
</script>
