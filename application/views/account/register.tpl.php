<script type="text/javascript" src="/js/call/jquery.mask.js"></script>
<script>
$(function($){
 $("#date_birth").mask("99-99-9999");
 $("#telephone").mask("(999)999-99-99");
 });
</script>
<h1>Регистрация</h1>
<?php
$bool = false;
 if($this->errors) {$bool = true;}
 ?>
<form method="post" action="" class="contact-form w-100 was-validated " name="register" id="register">
<div class="row mx-auto  p-3 ">
<div class="col-xl-12 p-2 bg-white text-center">
<div class="comment-types">
<div class="comment-type">
<span class="red">*</span> - Поля, обязательные для заполнения
</div>
</div>
</div>
<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-3">
<div class="form-group form-group-sm">
<label for="name"><?=$this->trans->get('Имя')?> <span class="red">*</span> <?php if($bool and @$this->errors['name']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['name'].'</span>'; ?></label>
<input class="form-control" name="name" type="text" id="name" required="" value="<?=$this->post->name?>">
</div>
<div class="form-group form-group-sm">
<label for="middle_name"><?=$this->trans->get('Фамилия')?> <span class="red">*</span> <?php if($bool and @$this->errors['middle_name']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['middle_name'].'</span>'; ?></label>
<input class="form-control" name="middle_name" type="text" id="middle_name" required="" value="<?=$this->post->middle_name?>">
</div>
<div class="form-group form-group-sm">
<label for="gender"><?=$this->trans->get('Sex')?> <span class="red">*</span> <?php if($bool and @$this->errors['gender']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['gender'].'</span>'; ?></label>
<select name="gender" id="gender" class="form-control" required>
<option></option>
<option value="w" <?=(@$this->post->gender == 'w')?'selected':''?> ><?=$this->trans->get('Женский')?></option>
<option value="m" <?=(@$this->post->gender == 'm')?'selected':''?> ><?=$this->trans->get('Мужской')?></option>
</select>
</div>
<div class="form-group form-group-sm">
<label for="date_birth"><?=$this->trans->get('Дата рождения')?> <span class="red">*</span> <?php if($bool and @$this->errors['date_birth']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['date_birth'].'</span>'; ?></label>
<input class="form-control" name="date_birth" type="text" id="date_birth" required="" placeholder="99-99-9999" value="<?=$this->post->date_birth?>" onkeyup="this.value.replace(/[^\d-]*/g, '')">
</div>
<div class="form-group form-group-sm">
<label for="telephone"><?=$this->trans->get('Телефон')?> <span class="red">*</span> <?php if($bool and @$this->errors['telephone']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['telephone'].'</span>'; ?></label>
<input class="form-control" name="telephone" type="tel" id="telephone" required="" placeholder="(xxx)xxx-xx-xx" value="<?=$this->post->telephone?>" >
</div>
<div class="form-group form-group-sm">
<label for="email">E-mail <span class="red">*</span>  <?php if($bool and @$this->errors['email']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['email'].'</span>'; ?></label>
<input class="form-control" name="email" id="email" type="email" required="" value="<?=$this->post->email?>" >
</div>

</div>
<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-3">
<div class="form-group form-group-sm">
<label for="password">Пароль <span class="red">*</span> <?php if($bool and @$this->errors['password']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['password'].'</span>'; ?></label>
<input class="form-control" name="password" id="password" type="password" placeholder="Пароль" pattern="[A-Za-z0-9_-]{6,20}" autocomplete="off" required="" value="" >
</div>
<div class="form-group form-group-sm">
<label for="password2">Повторите пароль <span class="red">*</span> <?php if($bool and @$this->errors['password2']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['password2'].'</span>'; ?></label>
<input class="form-control" name="password2" id="password2" type="password" placeholder="Пароль" pattern="[A-Za-z0-9_-]{6,20}" autocomplete="off" required="" value="" >
</div>
<div class="form-group form-group-sm">
<label for="captcha">Капча <span class="red">*</span> </label><br>
<img  id="capT" src="/application/views/pages/captcha.php" ><input class="form-control" name="captcha" id="captcha" type="text" placeholder="Введите буквы" maxlength="5"  required="" value="" ><?php if($bool and @$this->errors['captcha']) echo '<span class="red" style="font-size: 10px;">'.$this->errors['captcha'].'</span>'; ?>
</div>
          </div>
<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-3">
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
