<div class="row mx-auto bg-white p-3 ">
    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 m-auto text-center">
    	    <?php if($this->ok) { ?>
         <div class="alert alert-success m-auto" role="alert">
                <h4 class="alert-heading"><?=$this->ok?></h4>
</div>
    	    <?php }else{ ?>
            <div class="alert alert-danger" role="alert">
			<p>Укажите Ваш Логин (e-mail), который Вы использовали при регистрации</p>
                        </div>
			<?php } ?>
            
    	    <?php if($this->error){ ?><div class="alert alert-danger" role="alert"><?=$this->error?></div><?php } ?>
            
		<form name="reset_password" method="post" class="was-validated mt-2" action="/account/resetpassword/">
                    <div class="input-group mb-3">
  <input type="text" class="form-control" name="login"  placeholder="Логин(email)" aria-label="Логин(email)" required="" aria-describedby="button-addon2">
  <div class="input-group-append">
      <button class="btn btn-outline-danger" type="submit" id="button-addon2">Сбросить пароль</button>
  </div>
</div>
		</form>	
    </div>
       </div>
