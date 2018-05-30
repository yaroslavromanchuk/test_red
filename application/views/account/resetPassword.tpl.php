<h1><?php echo mb_strtoupper($this->getCurMenu()->getName());?></h1>
		   <div class="col-xl-12 text-center">
    	    <?php if($this->ok) { ?>
    	    <p>Новый пароль был успешно отправлен</p>
    	    <?php } else { ?>
			<p>Укажите Ваш Логин (e-mail), который Вы использовали при регистрации</p>
			<?php } ?>
    	    <?php if($this->error) echo $this->error; ?>
		<form name="reset_password" method="post" class="register" action="/account/resetpassword/" id="resetpasswprd-form">
			Логин (e-mail) *:<input type="text" name="login" class="form-control" style="max-width: 200px;display: inline-block;" value=""/>
				<a onclick="document.forms.reset_password.submit(); return false;" href="#" class="btn btn-danger">Сбросить пароль</a>
		</form>
		</div>
