
<div class="row mx-auto bg-white p-3 ">
    <div class="col-xl-6 p-3  m-auto text-center">
<?php if(isset($this->errors)){ ?>
        <div class="alert alert-danger" role="alert">
   <?php foreach($this->errors as $error){ ?>
        
        <span style="color: red;"><?=$error?></span><br />
  <?php  }  ?>
        </div>
 <?php   }
?>
        <?php if($this->ok){ ?>
            <div class="alert alert-success m-auto" role="alert">
                <h4 class="alert-heading"><?=$this->ok?></h4>
</div>
        <?php }?>
    </div>
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