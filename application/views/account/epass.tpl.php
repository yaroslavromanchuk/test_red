<h1><?=mb_strtoupper($this->getCurMenu()->getName())?></h1>
<?php if(isset($this->errors)){ foreach($this->errors as $error){ echo '<span style="color: red;">'.$error.'</span><br />'; } }

if(isset($this->ok)) echo '<span style="color: green;">'.$this->ok.'</span><br />';?>
<div class="row mx-auto bg-white p-3 ">
<div class="col-xl-12 p-3">
    <form method="post" action="/account/epass/" name="epass" id="epass" class="was-validated" style="width: 320px;margin: auto;">
	<div class="form-group">
    <label for="oldpass">Старый пароль</label>
<input type="password" name="oldpass" class="form-control" required="" value="">
  </div>
<div class="form-group">
   <label for="password">Новый пароль</label>
  <input type="password" name="password" id="password" class="form-control" required="" value="">
</div>
	<div class="form-group">
  <label for="password2">Повторите новый пароль</label>
  <input type="password" name="password2"  id="password2" class="form-control" required="" value="">
</div>
		<button class="btn btn-danger" type="submit" id="send">Сменить</button>
    </form>
	</div>
</div>