<script type="text/javascript" src="/js/call/jquery.mask.js"></script>
<script>
$(function($){
 $("#date_birth").mask("99-99-9999");
 $("#telephone").mask("(999)999-99-99");
 });
</script>
<h1>Регистрация</h1>
<div align="center" id='loadBar'></div>
<form method="post" action="post" class="contact-form w-100 " name="register" id="register">
<div class="row mx-auto bg-white p-3">
<div class="col-xl-12 p-1">
<div class="comment-types">
<div class="comment-type">
<span class="red">*</span> - Поля, обязательные для заполнения
</div>
</div>
</div>
<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1">
<div class="form-group form-group-sm">
<label for="name"><?=$this->trans->get('Имя')?> <span class="red">*</span></label>
<input class="form-control" name="name" type="text" id="name" required="" value="<?=$this->post->name?>">
</div>
<div class="form-group form-group-sm">
<label for="middle_name"><?=$this->trans->get('Фамилия')?> <span class="red">*</span></label>
<input class="form-control" name="middle_name" type="text" id="middle_name" required="" value="<?=$this->post->middle_name?>">
</div>
<div class="form-group form-group-sm">
<label for="gender"><?=$this->trans->get('Sex')?> <span class="red">*</span></label>
<select name="gender" id="gender" class="form-control" required>
<option></option>
<option value="w" <?=(@$this->post->gender == 'w')?'selected':''?> ><?=$this->trans->get('Женский')?></option>
<option value="m" <?=(@$this->post->gender == 'm')?'selected':''?> ><?=$this->trans->get('Мужской')?></option>
</select>
</div>
<div class="form-group form-group-sm">
<label for="date_birth"><?=$this->trans->get('Дата рождения')?> <span class="red">*</span></label>
<input class="form-control" name="date_birth" type="text" id="date_birth" required="" placeholder="99-99-9999" value="<?=$this->post->date_birth?>" onkeyup="validate(this)">
</div>
<div class="form-group form-group-sm">
<label for="telephone"><?=$this->trans->get('Телефон')?> <span class="red">*</span></label>
<input class="form-control" name="telephone" type="tel" id="telephone" required="" placeholder="(xxx)xxx-xx-xx" value="<?=$this->post->telephone?>" >
</div>
<div class="form-group form-group-sm">
<label for="email">E-mail <span class="red">*</span></label>
<input class="form-control" name="email" id="email" type="email" required="" value="<?=$this->post->email?>" >
</div>

</div>
<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1">
<div class="form-group form-group-sm">
<label for="password">Пароль <span class="red">*</span></label>
<input class="form-control" name="password" id="password" type="password" placeholder="Пароль" autocomplete="off" required="" value="" >
</div>
<div class="form-group form-group-sm">
<label for="password2">Повторите пароль <span class="red">*</span></label>
<input class="form-control" name="password2" id="password2" type="password" placeholder="Пароль" autocomplete="off" required="" value="" >
</div>
<div class="form-group form-group-sm">
<label for="captcha">Капча <span class="red">*</span></label><br>
<img  id="capT" src="/application/views/pages/captcha.php" ><input class="form-control" name="captcha" id="captcha" type="text" placeholder="Введите буквы" maxlength="5"  required="" value="" >
</div>
          </div>
<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1">
<div class="form-group form-group-sm">
<label for="company"><?=$this->trans->get('Компания')?></label>
<input class="form-control" name="company" id="company" type="text"  value="<?=$this->post->company?>" >
</div>
<div class="form-group form-group-sm">
<label for="city"><?=$this->trans->get('Город')?></label>
<input class="form-control" name="city" id="city" type="text"  value="<?=$this->post->city?>" >
</div>
<div class="form-group form-group-sm">
<label for="street"><?=$this->trans->get('Улица')?></label>
<input class="form-control" name="street" id="street" type="text"  value="<?=$this->post->street?>" >
</div>
<div class="form-group form-group-sm">
<label for="house"><?=$this->trans->get('Дом')?></label>
<input class="form-control" name="house" id="house" type="text"  value="<?=$this->post->house?>" >
</div>
</div>

		  <div class="col-xl-12 text-center">
			<button class="btn btn-danger" type="submit" id="send">Регистрация</button>
		</div>	
		</div>
	</form>
	
<script type="text/javascript"> 
$(document).ready(function() { 

var regVr22 = "<div><img style='margin-bottom:-4px;' src='/img/load.gif' alt='Отправка...' width='16' height='16'><span style='font: 11px Verdana; color:#333; margin-left:6px;'>Данные обрабатываются...</span></div><br>";
/*
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
		
});*/
});

			function validate(inp) {
    inp.value = inp.value.replace(/[^\d-]*/g, '');
}
</script>