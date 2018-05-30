<script type="text/javascript" src="/js/call/jquery.mask.js"></script>
<script>
//$(function($){ $("#phone").mask("38(999)999-99-99");});
$(function($){ $("#date_birth").mask("99-99-9999");});

</script>
<h1>Регистрация</h1>
<div align="center" id='loadBar'></div>
<div style="text-align: center;">
<form method="post" action="#" class="contact-form" name="register" id="register">
<p style="font-size:14px;"><span style="color: red; font-size: 16px;font-weight: bold;">*</span> - поля обезательные для заполнения!</p>
<label for="email" style="width: 140px;text-align: start;">E-mail <span style="color: red;font-size: 16px;font-weight: bold;">*</span></label><input name="email" placeholder="Email" type="text" class="input_reg" id="email" value="<?=$this->post->email?>"/></br>
<label for="company" style="width: 140px;text-align: start;"><?php echo $this->trans->get('Компания');?></label><input name="company" type="text" placeholder="Компания" class="input_reg" id="company" value="<?=$this->post->company?>"/></br>
<label for="name" style="width: 140px;text-align: start;">Имя <span style="color: red;font-size: 16px;font-weight: bold;">*</span></label><input name="name" type="text" placeholder="Имя" class="input_reg" id="name" value="<?=$this->post->name?>"/></br>
<label for="middle_name" style="width: 140px;text-align: start;text-align: start;"><?php echo $this->trans->get('Фамилия');?> <span style="color: red;font-size: 16px;font-weight: bold;">*</span></label><input name="middle_name" type="text" placeholder="Фамилия" class="input_reg" id="middle_name" value="<?=$this->post->middle_name?>"/></br>
<label for="city" style="width: 140px;text-align: start;"><?php echo $this->trans->get('Город');?> <span style="color: red;font-size: 16px;font-weight: bold;">*</span></label><input name="city" type="text" placeholder="Город" class="input_reg" id="city" value="<?=$this->post->city?>"/></br>
<label for="street" style="width: 140px;text-align: start;">Улица <span style="color: red;font-size: 16px;font-weight: bold;">*</span></label><input name="street" type="text" placeholder="Улица" class="input_reg" id="street" value="<?=$this->post->street?>"/></br>
<label for="house" style="width: 140px;text-align: start;">Дом</label><input name="house" type="text" placeholder="Дом" class="input_reg" id="house" value="<?=$this->post->house?>"/></br>
<label for="date_birth" style="width: 140px;text-align: start;">Дата рождения</label><input name="date_birth" type="text" placeholder="99-99-9999" class="input_reg" id="date_birth" value="" onkeyup="validate(this)"/></br>
<label for="telephone" style="width: 140px;text-align: start;"><?php echo $this->trans->get('Телефон');?><span style="color: red;font-size: 16px;font-weight: bold;">*</span></label><input name="telephone" type="text" placeholder="(xxx)xxx-xx-xx" class="input_reg" id="telephone" value="<?=$this->post->telephone?>"/></br>
<label for="password" style="width: 140px;text-align: start;">Пароль<span style="color: red;font-size: 16px;font-weight: bold;">*</span></label><input name="password" type="password" placeholder="Пароль" class="input_reg" id="password" autocomplete="off" value=""/></br>
<label for="password2" style="width: 140px;text-align: start;">Повторите пароль<span style="color: red;font-size: 16px;font-weight: bold;">*</span></label><input name="password2" type="password"  placeholder="Пароль" class="input_reg" id="password2" autocomplete="off" value=""/></br>
<label for="captcha" style="width: 140px;text-align: start;">Введите буквы:<span style="color: red;font-size: 16px;font-weight: bold;">*</span></br><img  id="capT" src="/application/views/pages/captcha.php" ></label><input value="" class="input_reg" id="captcha" type="text" maxlength="5" name="captcha" placeholder="Введите буквы">
</br>
            <br/> 
			<button class="btn btn-default" type="button" id="send">Регистрация</button>
	</form>
	</div>
<script type="text/javascript"> 
$(document).ready(function() { 

var regVr22 = "<div><img style='margin-bottom:-4px;' src='/img/load.gif' alt='Отправка...' width='16' height='16'><span style='font: 11px Verdana; color:#333; margin-left:6px;'>Данные обрабатываются...</span></div><br>";

$("#send").click(function(){
    var captSRC = "/application/views/pages/captcha.php";
		$("#loadBar").html(regVr22).show();
		var email = $("#email").val();
		var name = $("#name").val();
		var middle_name = $("#middle_name").val();
		var city = $("#city").val();
		var company = $("#company").val();
		var street = $("#street").val();
		var house = $("#house").val();
		var date_birth = $("#date_birth").val();
		var telephone = $("#telephone").val();
		var password = $("#password").val();
		var password2 = $("#password2").val();
		var captcha = $("#captcha").val();
		$.ajax({
			type: "POST",
			url: "/account/register/",
			data: {"name": name, "email": email, "middle_name": middle_name, "city": city, "street": street, "house": house, "date_birth": date_birth, "telephone": telephone, "password": password, "password2": password2, "company": company,  "captcha": captcha},
			cache: false,
			success: function(response){
		var messageResp = "<p style='font-family:Verdana; font-size:11px; color:green; border:1px solid #00CC00; padding:10px; margin:20px; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px; background-color:#fff;'>Спасибо, <strong>";
		var resultStat = "!</strong> Ваш аккаунт зарегистрирован!</p>";
		var oll = (messageResp + middle_name + " " + name + resultStat);
				if(response == 1){ 
				$("#loadBar").html(oll).fadeIn(3000);
				$("#name").val("");
				$("#email").val("");
				$("#company").val("");
				$("#street").val("");
				$("#house").val("");
				$("#telephone").val("");
				$("#password").val("");
				$("#").val("");
				$("#middle_name").val("");
				$("#city").val("");
				$("#captcha").val("");
				$("#capT").attr('src',captSRC);
				} else {
		$("#loadBar").html(response).fadeIn(3000);
		$("#capT").attr('src', captSRC); 
		}
										}
		});
		return false;
});
});

			function validate(inp) {
    inp.value = inp.value.replace(/[^\d-]*/g, '');
}

jQuery(function($){
   $("#telephone").mask("(999)999-99-99");
});
</script>