<script>
	function validate() {
		var phone = document.getElementById("telephone").value;
		var pattern = /^\d{3,5}([\-]\d{6,8})?$/;
		if (pattern.test(phone)) {
			alert("Your phone number : "+phone);
			return true;
		}
		alert("It is not valid phone number!");
		return false;
	}
</script>
<h1 class="violet"><?php echo $this -> getCurMenu() -> getName(); ?></h1>
<input type="hidden" value="<?php echo date('H') ?>" id='d_time'/>
<input type="hidden" value="<?php echo date('N') ?>" id='d_day'/>
<?php
	echo $this -> getCurMenu() -> getPageBody();

	$d_text = '';
	if (@$this->basket_contacts['delivery_type_id'] == 4) {
		$d_text = 'Отправка заказов Укрпочтой - понедельник и четверг.';
	}
	if (@$this->basket_contacts['delivery_type_id'] == 8) {
		$d_text = 'Менеджер проверит наличие товаров из заказа и вышлет Вам счет по e-mail.<br />
		После оплаты счета мы отправим Ваш заказ.<br />
		Отправка заказов Новой почтой - понедельник, среда и пятница.<br />';
	}
	if (date('N') <= 5 or (date('N') == 5 and date('H') < 15)) {
		if (in_array(@$this->basket_contacts['delivery_type_id'], array(1, 2, 3)) and date('H') < 15) {
			$d_text = 'Послезавтра в 10.00.';
		}
		if (in_array(@$this->basket_contacts['delivery_type_id'], array(7, 11)) and date('H') < 15) {
			$d_text = 'Послезавтра в 10.00.';
		}
		if (in_array(@$this->basket_contacts['delivery_type_id'], array(1, 2, 3)) and date('H') >= 15) {
			$d_text = 'Послезавтра в 13:00.';
		}
		if (in_array(@$this->basket_contacts['delivery_type_id'], array(7, 11)) and date('H') >= 15) {
			$d_text = 'Послезавтра в 15:00.';
		}
	}
	else {
		if (date('N') < 7 or (date('N') == 7 and date('H') < 15)) {
			if (in_array(@$this->basket_contacts['delivery_type_id'], array(1, 2, 3))) {
				$d_text = 'Во вторник в 10.00.';
			}
			if (in_array(@$this->basket_contacts['delivery_type_id'], array(7, 11))) {
				$d_text = 'В понедельник в 15.00.';
			}
		}
		else {
			if (in_array(@$this->basket_contacts['delivery_type_id'], array(1, 2, 3))) {
				$d_text = 'Во вторник в 13.00.';
			}
			if (in_array(@$this->basket_contacts['delivery_type_id'], array(7, 11))) {
				$d_text = 'Во вторник в 15.00.';
			}
		}
	}
?>
<div class="row">
	<div class="col-xs-12">

<input type="hidden" id='d_text_u' value="Отправка заказов Укрпочтой - понедельник и четверг."/>
<input type="hidden" id='d_text_np' value="После проверки заказа менеджер вышлет Вам счет по e-mail. <br/>
	После оплаты счета мы отправим Ваш заказ.<br/>
	Отправка заказов Новой почтой - понедельник и четверг."/>
<input type="hidden" id='d_text_m11' value="Послезавтра в 10.00."/>
<input type="hidden" id='d_text_m21' value="Послезавтра в 10.00."/>
<input type="hidden" id='d_text_m12' value="Послезавтра в 13:00."/>
<input type="hidden" id='d_text_m22' value="Послезавтра в 15:00"/>
<input type="hidden" id='d_text_m31' value="Во вторник в 10.00"/>
<input type="hidden" id='d_text_m32' value="В понедельник в 15.00"/>
<input type="hidden" id='d_text_m33' value="Во вторник в 13.00"/>
<input type="hidden" id='d_text_m34' value="Во вторник в 15.00"/>
<?php
	if ($this->errors) {
		echo '
			<div class="col-xs-10 col-xs-offset-1">
				<div class="alert alert-danger">';
					echo $this -> trans -> get('Вы не заполнили одно из полей отмеченых <span class="red">красным</span>');
		echo '
				</div>
			</div>';
	}
	if (isset($this -> errors['error'])) {
		echo '
			<div class="col-xs-10 col-xs-offset-1">
				<div class="alert alert-danger">
					'.$this -> errors['error'] . '
				</div>
			</div>
		';
	}
	if (isset($this -> error_email)) {
		echo '
			<div class="col-xs-10 col-xs-offset-1">
				<div class="alert alert-danger">
					'.$this -> error_email . '
				</div>
			</div>
		';
	}
?>

<form method="post" action="" class="contact-form" name="basket_contacts" id="basket_contacts">
	<div class="row form-group">
		<div class="col-xs-5 col-xs-offset-1">
<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn()) {
?>
					<input name="name" type="text" id="name" required="" placeholder="Имя" class="form-control<?php if (in_array('name', $this -> errors, true)) echo " red";
						?>" value="<?php if (isset($this -> basket_contacts['name'])) echo htmlspecialchars($this -> basket_contacts['name']); ?>"/>
<?php
				}
				else {
?>
					<input name="name" type="text" id="name" required="" placeholder="Имя" class="form-control<?php if (in_array('name', $this -> errors, true)) echo " red";
						?>" value="<?php echo $this->ws->getCustomer()->getFirstName(); ?>"/>
<?php
				}
?>
		</div>
		<div class="col-xs-5">
<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getMiddleName()) {
?>
					<input name="middle_name" type="text" placeholder="Фамилия"  id="middle_name"  required="" class="form-control<?php if (in_array('middle_name', $this -> errors, true)) echo " red";
						?>" value="<?php if (isset($this -> basket_contacts['middle_name'])) echo htmlspecialchars($this -> basket_contacts['middle_name']); ?>"/>
<?php
				}
				else {
?>
					<input name="middle_name" type="text" placeholder="Фамилия"  id="middle_name"  required="" class="form-control<?php if (in_array('middle_name', $this -> errors, true)) echo " red";
						?>" value="<?php echo $this->ws->getCustomer()->getMiddleName(); ?>"/>
<?php
				}
 ?>
		</div>
	</div>
	
	<div class="row form-group">
		<div class="col-xs-10 col-xs-offset-1">
<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getLastName()) {
?>
					<input name="last_name" type="text" placeholder="Отчество" id="last_name"  required="" class="form-control<?php if (in_array('last_name', $this -> errors, true)) echo " red";
						?>" value="<?php if (isset($this -> basket_contacts['last_name'])) echo htmlspecialchars($this -> basket_contacts['last_name']); ?>"/>
<?php
				}
				else {
					echo $this->ws->getCustomer()->getLastName();
					echo '<input name="last_name" type="hidden" id="last_name" value="' . $this->ws->getCustomer()->getLastName() . '"/> ';
				}
?><!--
				<span class="rd"><?php
					if (@$this -> basket_contacts['delivery_type_id'] == 9)
						echo '*';
?>
				</span>
-->
		</div>
	</div>
	
	<div class="row form-group">
		<div class="col-xs-5 col-xs-offset-1">
<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn()) {
?>
					<input name="telephone" placeholder="+38(000)000-00-00" maxlength="17" required="" id="telephone" type="tel" class="form-control<?php if (in_array('telephone', $this -> errors, true)) echo " red";
						?>" value="<?php if (isset($this -> basket_contacts['telephone'])) echo htmlspecialchars($this -> basket_contacts['telephone']); ?>"/>
<?php
				}
				else {
?>
					<input name="telephone" placeholder="+38(000)000-00-00" maxlength="17" required="" id="telephone" type="tel" class="form-control<?php if (in_array('telephone', $this -> errors, true)) echo " red";
						?>" value="<?php echo $this->ws->getCustomer()->getPhone1(); ?>"/>
<?php
				}
?>

		</div>
		<div class="col-xs-5">
<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn()) {
?>
					<input name="email" placeholder="E-mail" type="email" id="email" class="form-control<?php if (in_array('email', $this -> errors, true)) echo " red";
						?>" value="<?php if (isset($this -> basket_contacts['email'])) echo htmlspecialchars($this -> basket_contacts['email']); ?>"/>
<?php
				}
				else {
?>
					<input name="email" placeholder="E-mail" type="email" id="email" class="form-control<?php if (in_array('email', $this -> errors, true)) echo " red";
						?>" value="<?php echo $this->ws->getCustomer()->getEmail(); ?>"/>
<?php
				}
?>
		</div>
	</div>
	
	<div class="row form-group">
		<div class="col-xs-10 col-xs-offset-1">
			<textarea name="comments" id="order-comment" rows="4" class="form-control" placeholder="Коментарий к заказу"></textarea>
		</div>
	</div>
	
	<div class="row form-group">
		<div class="col-xs-10 col-xs-offset-1">
					<div class="btn-group" data-toggle="buttons" style="width:100%;">
						<label class="btn btn-default<?php if($this->basket_contacts['delivery_type_id'] == 8) echo ' active'; ?>" style="width:25%;">
							<input class="delivery_type" name="delivery_type_id" id="delivery_type" value="8" type="radio" autocomplete="off"<?php if($this->basket_contacts['delivery_type_id'] == 8) echo ' checked'; ?>>
							<img style="height:55px;" src="/img/delivery/nps.png"/>
							Новапошта
						</label>
						<label class="btn btn-default<?php if($this->basket_contacts['delivery_type_id'] == 4) echo ' active'; ?>" style="width:25%;">
							<input class="delivery_type" name="delivery_type_id" id="delivery_type" value="4" type="radio" autocomplete="off"<?php if($this->basket_contacts['delivery_type_id'] == 4) echo ' checked'; ?>>
							<img style="height:55px;" src="/img/delivery/upb.png"/>
							Укрпошта
						</label>
						<label class="btn btn-default<?php if($this->basket_contacts['delivery_type_id'] == 9) echo ' active'; ?>" style="width:25%;">
							<input class="delivery_type" name="delivery_type_id" id="delivery_type" value="9" type="radio" autocomplete="off"<?php if($this->basket_contacts['delivery_type_id'] == 9) echo ' checked'; ?>>
							<img style="height:55px;" src="/img/delivery/kk.png"/>
							Курьером
						</label>
						<label class="btn btn-default<?php if($this->basket_contacts['delivery_type_id'] == 12) echo ' active'; ?>" style="width:25%;">
							<input class="delivery_type" name="delivery_type_id" id="delivery_type" value="12" type="radio" autocomplete="off"<?php if($this->basket_contacts['delivery_type_id'] == 12) echo ' checked'; ?>>
							<img style="height:55px;" src="/img/delivery/sv.png"/>
							Самовывоз
						</label>
					</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 dop_fields" style="<?php
			if (!in_array(@$this->basket_contacts['delivery_type_id'], array(4, 9, 8, 10)))
				echo 'display: none;';
		?>">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="col-xs-6 form-group ukr only_ukr" style="<?php
						if (!in_array(@$this->basket_contacts['delivery_type_id'], array(4)))
							echo 'display: none;';
					?>">
<?php
						if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getIndex()) {
?>
							<input name="index" id="index" type="text" placeholder="Индекс" class="form-control<?php if (in_array('index', $this -> errors, true)) echo " red";
								?>" value="<?php if (isset($this -> basket_contacts['index'])) echo htmlspecialchars($this -> basket_contacts['index']); ?>"/>
<?php
						}
						else {
?>
							<input name="index" id="index" type="text" placeholder="Индекс" class="form-control<?php if (in_array('index', $this -> errors, true)) echo " red";
								?>" value="<?php echo $this->ws->getCustomer()->getIndex(); ?>"/>
<?php
						}
?>
<!--						<span class="rd"><?php if (@$this -> basket_contacts['delivery_type_id'] == 4) echo '*'; ?></span>-->
					</div>
					<div class="col-xs-6 form-group ukr only_ukr novp only_novp" style="<?php
						if (!in_array(@$this->basket_contacts['delivery_type_id'], array(4)))
							echo 'display: none;';
					?>">
<?php
						if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getObl()) {
?>
							<input name="obl" id="obl" type="text" placeholder="Область" class="form-control<?php if (in_array('obl', $this -> errors, true)) echo " red";
								?>" value="<?php if (isset($this -> basket_contacts['obl'])) echo htmlspecialchars($this -> basket_contacts['obl']); ?>"/>
<?php
						}
						else {
?>
							<input name="obl" id="obl" type="text" placeholder="Область" class="form-control<?php if (in_array('obl', $this -> errors, true)) echo " red";
								?>" value="<?php echo $this->ws->getCustomer()->getObl(); ?>"/>
<?php
						}
?>
<!--
						<span class="rd">
							<?php if (@$this -> basket_contacts['delivery_type_id'] == 4) echo '*'; ?>
						</span>
-->
					</div>
					
					<div class="col-xs-6 form-group ukr only_ukr" style="<?php
						if (!in_array(@$this->basket_contacts['delivery_type_id'], array(4)))
							echo 'display: none;';
					?>">
<?php
						if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getRayon()) {
?>
							<input name="rayon" id="rayon" type="text" placeholder="Район" class="form-control<?php if (in_array('rayon', $this -> errors, true)) echo " red";
								?>" value="<?php if (isset($this -> basket_contacts['rayon'])) echo htmlspecialchars($this -> basket_contacts['rayon']); ?>"/>
<?php
						}
						else {
?>
							<input name="rayon" id="rayon" type="text" placeholder="Район" class="form-control<?php if (in_array('rayon', $this -> errors, true)) echo " red";
								?>" value="<?php echo $this->ws->getCustomer()->getRayon(); ?>"/>
<?php
						}
?>
<!--
						<span class="rd">
							<?php if (@$this -> basket_contacts['delivery_type_id'] == 4) echo '*'; ?>
						</span>
-->
					</div>
					
					<div class="col-xs-6 form-group ukr novp only_ukr only_novp">
<?php
						if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getCity()) {
?>
							<input name="city" id="city" type="text" placeholder="Город" class="form-control<?php if (in_array('city', $this -> errors, true)) echo " red";
								?>" value="<?php if (isset($this -> basket_contacts['city'])) echo htmlspecialchars($this -> basket_contacts['city']); ?>"/>
<?php
						}
						else {
?>
							<input name="city" id="city" type="text" placeholder="Город" class="form-control<?php if (in_array('city', $this -> errors, true)) echo " red";
								?>" value="<?php echo $this->ws->getCustomer()->getCity(); ?>"/>
<?php
						}
?>
<!--						<span class="rd">*</span>-->
					</div>
					
					<div class="col-xs-6 form-group ukr only_ukr kur only_kur">
<?php
									if (!$this->ws->getCustomer()->getIsLoggedIn()|| !$this->ws->getCustomer()->getStreet()) {
?>
							<input name="street" id="street" type="text" placeholder="Улица" class="form-control<?php if (in_array('street', $this -> errors, true)) echo " red";
								?>" value="<?php if (isset($this -> basket_contacts['street'])) echo htmlspecialchars($this -> basket_contacts['street']); ?>"/>
<?php
						}
						else {
?>
							<input name="street" id="street" type="text" placeholder="Улица" class="form-control<?php if (in_array('street', $this -> errors, true)) echo " red";
								?>" value="<?php echo $this->ws->getCustomer()->getStreet(); ?>"/>
<?php
						}
?>
<!--
						<span class="rd">
							<?php if (@$this -> basket_contacts['delivery_type_id'] == 4) echo '*'; ?>
						</span>
-->
					</div>
					
					<div class="col-xs-6 form-group ukr only_ukr kur only_kur">
<?php
						if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getHouse()) {
?>
							<input name="house" id="house" type="text" placeholder="Дом" class="form-control<?php if (in_array('house', $this -> errors, true)) echo " red";
								?>" value="<?php if (isset($this -> basket_contacts['house'])) echo htmlspecialchars($this -> basket_contacts['house']); ?>"/>
<?php
						}
						else {
?>
							<input name="house" id="house" type="text" placeholder="Дом" class="form-control<?php if (in_array('house', $this -> errors, true)) echo " red";
								?>" value="<?php echo $this->ws->getCustomer()->getHouse(); ?>"/>
<?php
						}
?>
<!--
						<span class="rd">
							<?php if (@$this -> basket_contacts['delivery_type_id'] == 4) echo '*'; ?>
						</span>
-->
					</div>
					
					<div class="col-xs-6 form-group ukr only_ukr kur only_kur">
<?php
						if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getFlat()) {
?>
							<input name="flat" id="flat" type="text" placeholder="Квартира" class="form-control<?php if (in_array('flat', $this -> errors, true)) echo " red";
								?>" value="<?php if (isset($this -> basket_contacts['flat'])) echo htmlspecialchars($this -> basket_contacts['flat']); ?>"/>
<?php
						}
						else {
?>
							<input name="flat" id="flat" type="text" placeholder="Квартира" class="form-control<?php if (in_array('flat', $this -> errors, true)) echo " red";
								?>" value="<?php echo $this->ws->getCustomer()->getFlat(); ?>"/>
<?php
						}
?>
<!--
						<span class="rd">
							<?php if (@$this -> basket_contacts['delivery_type_id'] == 4) echo '*'; ?>
						</span>
-->
					</div>
					
					<div class="col-xs-6 form-group novp only_novp" style="<?php
						if (!in_array(@$this->basket_contacts['delivery_type_id'], array(8, 16)))
							echo 'display: none;';
					?>">
						<input name="sklad" id="sklad" type="text" placeholder="Склад" class="form-control<?php if (in_array('sklad', $this -> errors, true)) echo " red";
							?>" value="<?php if (isset($this -> basket_contacts['sklad'])) echo htmlspecialchars($this -> basket_contacts['sklad']); ?>"/>
<!--						<span class="rd">*</span>-->
					</div>
					<div class="col-xs-6 form-group novp only_novp" style="<?php
						if (!in_array(@$this->basket_contacts['delivery_type_id'], array(8, 16)))
							echo 'display: none;';
					?>">
						<a href="http://novaposhta.ua/office" target="_blank">Номер склада и адрес своего отделения Вы можете найти здесь</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-10 col-xs-offset-1 sv only_sv" style="<?php
			if (!in_array(@$this->basket_contacts['delivery_type_id'], array(12)))
				echo 'display: none;';
		?>">
			<div class="panel panel-default sv only_sv">
				<div class="panel-body" style="padding:0px;">
					<div class="col-xs-4 form-group sv only_sv" style="padding:15px;">
						<div class="col-xs-12">
							<p>г. Киев</p>
							<p>Олександра Мишуги, 6</p>
							<p>ТЦ "Параллель"</p>
							<p>пн-вс: 10:00-20:00</p>
							<p>(063) 010 34 53</p>
							<p>(098) 634 26 82</p>
						</div>
					</div>
					
					<div class="col-xs-8 sv only_sv" style="padding: 0px; height: 300px;">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1069.354079170712!2d30.63653996745741!3d50.39699841521613!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0000000000000000%3A0x51f8436081d9359e!2z0J_QsNGA0LDQu9C70LXQu9GM!5e0!3m2!1sru!2sua!4v1440514498495" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row form-group payment_panel" 
<?php
	if (!isset($this->basket_contacts['delivery_type_id']) && !($this->basket_contacts['delivery_type_id'] > 0)) {
		echo 'style="display: none;"';
	}
?>>
		<div class="col-xs-10 col-xs-offset-1">
					<div class="btn-group payment_method_container" data-toggle="buttons" style="width: 100%;" style="<?php
						if (!in_array(@$this->basket_contacts['delivery_type_id'], array(4, 8, 9, 12)))
							echo 'display: none;';
					?>">
						<label class="btn btn-default only_sv only_kur" style="width: 25%;<?php
							if (in_array(@$this->basket_contacts['delivery_type_id'], array(4, 8)))
								echo ' display: none;';
						?>">
							<input class="payment_method" name="payment_method_id" id="payment_method" value="1" type="radio" autocomplete="off">
							<img style="height:55px; height: 45px; margin: 5px 0px;" src="/img/delivery/uah.png"/>
							Наличными
						</label>
						<label class="btn btn-default only_novp only_ukr" style="width: 25%;<?php
							if (in_array(@$this->basket_contacts['delivery_type_id'], array(9, 12)))
								echo ' display: none;';
						?>">
							<input class="payment_method" name="payment_method_id" id="payment_method" value="3" type="radio" autocomplete="off">
							<img style="height: 45px; margin: 5px 0px;float: left;" src="/img/delivery/npuah.png">
							<div style="
								text-align: left;
								padding-left: 50px;
								padding-top: 5px;
							">
								Наложенный<br>платеж
							</div>
						</label>
						<label class="btn btn-default" style="width: 25%;">
							<input class="payment_method" name="payment_method_id" id="payment_method" value="4" type="radio" autocomplete="off">
							<img style="height: 35px; margin: 10px 0px;" src="/img/delivery/vm.png"/>
							Visa/MasterCard
						</label>
						<label class="btn btn-default" style="width: 25%;">
							<input class="payment_method" name="payment_method_id" id="payment_method" value="5" type="radio" autocomplete="off">
							<img style="height:55px; height: 45px; margin: 5px 0px;" src="/img/delivery/wm.png"/>
							WebMoney
						</label>
						<label class="btn btn-default" style="width: 25%;">
							<input class="payment_method" name="payment_method_id" id="payment_method" value="6" type="radio" autocomplete="off">
							<img style="height:55px; height: 45px; margin: 5px 0px;" src="/img/delivery/p24.png"/>
							Приват24
						</label>
					</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-10 col-xs-offset-1">
			<div class="alert alert-info" role="alert" id="d_text">
				<?php echo $d_text; ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-10 col-xs-offset-1">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="soglas" id="soglas" checked> Я выражаю согласие на обработку моих персональных данных
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" name="oznak" id="oznak" checked> Я ознакомлен с <a href="/terms/" target="_blank">условиями предоставления услуг</a> ФЛП "Плахотнюк"
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 text-right"">
			<button type="button" class="btn btn-danger" onclick="document.forms.basket_contacts.submit(); return false;">Заказать</button>
		</div>
	</div>
</form>
</div>
</div>
<script type="text/javascript">
			$(document).ready(function() {
				var delivery = $('.delivery_type:checked').val();
				if (delivery == '0') {
					$('.payment_panel').hide;
					return(false);
				}

				$('.delivery_type').change(function() {

					$('.payment_panel').show();
					$('.payment_mathod').attr('checked', 'checked');

					if ($(this).val() == 4) {
						$('#d_text').html($('#d_text_u').val());
						$('#dostav').show();
					}
					if ($(this).val() == 8) {
						$('#d_text').html($('#d_text_np').val());
						$('#dostav').show();
					}
					if ($('#d_day').val() <= 5 || ($('#d_day').val() == 5 && $('#d_time').val() < 15)) {
						if ($(this).val() >= 1 && $(this).val() <= 3 && $('#d_time').val() < 15) {
							$('#d_text').html($('#d_text_m11').val());
							$('#dostav').show();
						}
						if (($(this).val() == 7 || $(this).val() == 11) && $('#d_time').val() < 15) {
							$('#d_text').html($('#d_text_m21').val());
							$('#dostav').show();
						}
						if ($(this).val() >= 1 && $(this).val() <= 3 && $('#d_time').val() >= 15) {
							$('#d_text').html($('#d_text_m12').val());
							$('#dostav').show();
						}
						if (($(this).val() == 7 || $(this).val() == 11) && $('#d_time').val() >= 15) {
							$('#d_text').html($('#d_text_m22').val());
							$('#dostav').show();
						}
					} else {
						if ($('#d_day').val() < 7 || ($('#d_day').val() == 7 && $('#d_time').val() < 15)) {
							if ($(this).val() >= 1 && $(this).val() <= 3) {
								$('#d_text').html($('#d_text_m31').val());
								$('#dostav').show();
							}
							if (($(this).val() == 7 || $(this).val() == 11)) {
								$('#d_text').html($('#d_text_m32').val());
								$('#dostav').show();
							}

						} else {
							if ($(this).val() >= 1 && $(this).val() <= 3) {
								$('#d_text').html($('#d_text_m33').val());
								$('#dostav').show();
							}
							if (($(this).val() == 7 || $(this).val() == 11)) {
								$('#d_text').html($('#d_text_m34').val());
								$('#dostav').show();
							}

						}
					}
					if ($(this).val() == 10 || $(this).val() == 9) {
						$('#dostav').hide();
					}

					if ($(this).val() > 0 && $(this).val() != 4) {

					} else {
						$('#address').val('');
					}
					var delivery = $(this).val();
					$('.dop_fields').hide();
					$('.only_ukr').hide();
					$('.only_kur').hide();
					$('.only_sv').hide();
					$('.only_novp').hide('');
					if (delivery == 4) {
						$('.only_ukr').show();
						$('.ukr span').html('*');
						$('.dop_fields').show();
					}

					if (delivery == 8 || delivery == 16) {
						$('.only_novp').show();
						$('.novp span').html('*');
						$('.dop_fields').show();
					}

					if (delivery == 9 || delivery == 10) {
						$('.only_kur').show();
						$('.kur span').html('*');
						$('.dop_fields').show();
					}

					if (delivery == 12) {
						$('.only_sv').show();
						$('.sv span').html('*');
					}

					var payment = $('#payment_method').val();
					if (delivery == '0') {
						$('#payment_method').html('<option value="0"></option>');
						return (false);
					}
					$('#payment_method').attr('disabled', true);
					$('#payment_method').html('<option>загрузка...</option>');
					var url = '/page/getpayment/&delivery=' + delivery;
					$.get(url, "delivery=" + delivery, function(result) {
						if (result.type == 'error') {
							alert('error');
							return (false);
						} else {
							var options = '';
							var option = '';
							$(result.payment).each(function() {
								option = '';
								if (payment == $(this).attr('id'))
									option = 'selected="selected"';
								options += '<option value="' + $(this).attr('id') + '" ' + option + '>' + $(this).attr('title') + '</option>';
							});
							$('#payment_method').html(options);
							$('#payment_method').attr('disabled', false);
						}
					}, "json");
				});
			});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('.dont_call').click(function() {
			if ($('.call').prop('checked') == true) {
				$('.call').attr('checked', false);
			}
		});
		$('.call').click(function() {
			if ($('.dont_call').prop('checked') == true) {
				$('.dont_call').attr('checked', false);
			}
		});
	})
</script>
