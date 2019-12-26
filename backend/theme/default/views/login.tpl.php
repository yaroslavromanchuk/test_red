<!DOCTYPE html>
<html lang="en">
<meta name="robots" content="noindex,nofollow">
<meta http-equiv="pragma" content="no-cache">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <title><?php echo 'Система Управления Сайтом '.$this->website->getSite()->getName();?></title>

    <!-- vendor css -->
    <link href="<?=$this->files?>lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="<?=$this->files?>lib/Ionicons/css/ionicons.css" rel="stylesheet">


    <!-- Starlight CSS -->
	<link rel="stylesheet" href="<?=$this->files?>css/starlight.css">
    
  </head>
  <body>
    <div class="d-flex align-items-center justify-content-center bg-sl-primary ht-100v">
      <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 bg-white">
        <div class="signin-logo tx-center tx-24 tx-bold tx-inverse">RED.UA <span class="tx-info tx-normal">CMS</span></div>
        <div class="tx-center mg-b-30">Среда управления сайтом</div>
		<?php if($this->errors){?>
		<div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <div class="d-flex align-items-center justify-content-start">
              <i class="icon ion-ios-close alert-icon tx-24"></i>
              <span><strong>Ошибка!</strong><br><?=$this->errors?>.</span>
            </div><!-- d-flex -->
          </div>
		  <?php } ?>
<form action="<?=$this->path?>login/" method="post" class="form-signin">
        <div class="form-group">
          <input type="text" class="form-control" name="login" placeholder="Enter your username">
        </div><!-- form-group -->
        <div class="form-group">
          <input type="password" name="password" class="form-control" placeholder="Enter your password">
          <!--<a href="" class="tx-info tx-12 d-block mg-t-10">Forgot password?</a>-->
        </div><!-- form-group -->
        <button type="submit" class="btn btn-info btn-block">Войти в систему</button>
</form>
      <!--  <div class="mg-t-60 tx-center">Not yet a member? <a href="page-signup.html" class="tx-info">Sign Up</a></div>-->
      </div><!-- login-wrapper -->
    </div><!-- d-flex -->

    <script src="<?=$this->files?>lib/jquery/jquery.js"></script>
    <script src="<?=$this->files?>lib/popperjs/popper.js"></script>
    <script src="<?=$this->files?>lib/bootstrap/bootstrap.js"></script>

  </body>
</html>