<div class="block green-block">
	<h1 class="green"><?php echo $this->curMenu->getTitle(); ?></h1>
	<div class="inner">
		<form name="change_password" method="post" action="/account/changepassword/" id="changepassword-form">
			<div class="section-holder-underline">
				<div class="textbox">
					<?php 
						$customer = $this->webshop->getCustomer();
						$default = $customer->getDefaultDeliveryAddress();
						echo "<p>{$default->getCompanyName()}<br />";
						echo $customer->getFullname() . '<br />';
						echo "{$default->getStreet()} {$default->getNumber()}<br />";
						echo "{$default->getPostcode()} {$default->getCity()}<br /><br /></p>";
					?>
					<a href="/order/updateAddress/aid/<?php echo $default->getId();?>/isaccount/1/" class="popup" size="250x300"><img class="edit_button" src="/images/btn_wijzig-adres.jpg" alt="Wizjig adres" /></a>
				</div>
				<h5 class="borderless left">Hiernaast zie je het geselecteerde afleveradres.</h5>
				<select name="address_id" onchange="window.location='/account/deliveryaddresses/?aid='+this.value;" style="width: 250px;">
					<?php
						foreach($customer->getAddresses() as $address)
						{
							$selected = ($default->getId() == $address->getId()) ? ' selected' : '';
							echo '<option value="' . $address->getId() . '"' . $selected. '>' . $address->getStreet() .' '. $address->getNumber() . ', ' . $address->getCity() . '</option>';
						}
					?>
				</select>
				<a href="/order/updateAddress/isaccount/1/?new=1" class="popup" size="250x300"><img src="/images/btn_nieuw-adres.jpg" alt="Nieuw adres" class="padtop10" /></a>
				<br /><input type="button" name="delete" value="Delete" onclick="document.location='/account/deleteAddress/aid/<?php echo $default->getId();?>/';" />
			</div>
		</form>
	</div>
</div>
