<div id="page">
	<h1 class="violet"><?php echo $this->getCurMenu()->getPageIntro(); ?></h1>
	<p><?php echo $this->getCurMenu()->getPageBody(); ?></p>
	<?php if ($this->errors) { ?><p><strong>De <span class="red">rood</span> gemarkeerde velden moeten worden ingevuld om de gegevens te kunnen verzenden.</strong></p><?php } ?>
	<form method="post" action="" class="contact-form">
	<fieldset>
		<label for="company" class="label-contact">Bedrijfsnaam</label>
		<input name="company" type="text" class="formfields-1 formfields-2" id="company" value="<?php echo isset($this->post['company']) ? htmlspecialchars($this->post['company']) : ''; ?>"/>
		
		<label for="name" class="label-contact">Naam <span>*</span></label>
		<input name="name" type="text" class="formfields-1 formfields-2<?php if (in_array('name', $this->errors, true)) echo " red"; ?>" id="name" value="<?php echo isset($this->post['name']) ? htmlspecialchars($this->post['name']) : ''; ?>"/>
		
		<label for="address" class="label-contact">Adres</label>
		<input name="address" type="text" class="formfields-1 formfields-2" id="address" value="<?php echo isset($this->post['address']) ? htmlspecialchars($this->post['address']) : ''; ?>"/>
		
		<label for="pc" class="label-contact">Postcode</label>
		<input name="pc" type="text" class="formfields-1 formfields-2" id="pc" value="<?php echo isset($this->post['pc']) ? htmlspecialchars($this->post['pc']) : ''; ?>"/>
		
		<label for="city" class="label-contact">Woonplaats</label>
		<input name="city" type="text" class="formfields-1 formfields-2" id="city" value="<?php echo isset($this->post['city']) ? htmlspecialchars($this->post['city']) : ''; ?>"/>
		
		<label for="telephone" class="label-contact">Telefoon <span>*</span></label>
		<input name="telephone" type="text" class="formfields-1 formfields-2<?php if (in_array('telephone', $this->errors, true)) echo " red"; ?>" id="telephone" value="<?php echo isset($this->post['telephone']) ? htmlspecialchars($this->post['telephone']) : ''; ?>"/>
		
		<label for="email" class="label-contact">E-mail <span>*</span></label>
		<input name="email" type="text" class="formfields-1 formfields-2<?php if (in_array('email', $this->errors, true)) echo " red"; ?>" id="email" value="<?php echo isset($this->post['email']) ? htmlspecialchars($this->post['email']) : ''; ?>"/>
		
		<label for="comments" class="label-contact">Uw vraag <span>*</span></label>
		<textarea name="comments" rows="7" class="formfields-1<?php if (in_array('comments', $this->errors, true)) echo " red"; ?>" id="comments"><?php echo isset($this->post['comments']) ? $this->post['comments'] : ''; ?></textarea>
	</fieldset>
	<button type="submit" name="submit1">Verzenden</button> 
	</form>
</div>