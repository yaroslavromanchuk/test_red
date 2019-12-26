<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Чат</title>

<link rel="stylesheet" type="text/css" href="js/jScrollPane/jScrollPane.css" />
<link rel="stylesheet" type="text/css" href="css/page.css" />
<link rel="stylesheet" type="text/css" href="css/chat.css" />
</head>

<body style="margin-left: -15%;">
<?php
//$_SESSION['chat']['name'] = $this->user->getFirstName();
//$_SESSION['chat']['email'] = $this->user->getEmail();
 //echo print_r($_SESSION);

 ?>
<div id="chatContainer">

    <div id="chatTopBar" class="rounded"></div>
    <div id="chatLineHolder"></div>
    
    <div id="chatUsers" class="rounded"></div>
    <div id="chatBottomBar" class="rounded">
    	<div class="tip"></div>
      
        <form id="loginForm" method="post" action="" name="loginForm">
<input id="name" name="name" hidden class="rounded" maxlength="20" value="<?php echo $_GET['name'];?>" />
<input id="email" name="email" hidden class="rounded" value="<?php echo $_GET['email'];?>" />
            <input type="submit" class="blueButton" value="Войти" />
        </form>
        
        <form id="submitForm" method="post" action="">
            <input id="chatText" name="chatText" class="rounded" maxlength="255" />
            <input type="submit" class="blueButton" value="Отправить" />
        </form>
		<div class="add_f">
		<!--<img src="img/avatar.gif" id="open" onClick="avatarOpen();" title="Avatar controls">-->
		<img src="img/close.gif" id="exit" onClick="avatarExit();" title="Avatar controls" style="display: none;">

		</div>       
		<div id="add_av" style="display: none;" >
		<p style="color:red;">Картинка должна быть не более 1 мб, в формате .jpg</p>
		<form id="avatarload" action="" method="post" enctype="multipart/form-data">
						<input name="email1" type="hidden" id="em" value="">
						<input name="FILE" type="file" accept="image/jpg">
						<input name="load" type="submit" value="Обновить аватар">
					</form>
		</div>
    </div>
    
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="js/jScrollPane/jquery.mousewheel.js"></script>
<script src="js/jScrollPane/jScrollPane.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
<script>

function avatarOpen(){ $("#add_av").show(); $("#exit").show();  } //$("#open").hide();
function avatarExit(){ $("#add_av").hide(); $("#exit").hide();  } ///*$("#open").show();*/
	//document.forms.loginForm.submit();
</script>
<?php 
if (isset($_POST['load']))
{
 //echo $_FILES['file']['error'];

//$error = false;
	$user = trim($_POST['email1']);
	//echo $user;
	//print_r($_FILES);
    //проверка расширения файла 
    $file_name = $_FILES['FILE']['name']; 
 
        // файл не должен быть пустым,  
        // или его размер должен быть <= 800 Кбайт 
        if($_FILES['FILE']['size'] != 0 
            AND $_FILES['FILE']['size']<=1024000)  
      { 
       //проверяем функцией is_uploaded_file 
    if(is_uploaded_file($_FILES['FILE']['tmp_name']))  
          {
 //$uploadfile = "images/".$_FILES['somename']['name'];
  //move_uploaded_file($_FILES['somename']['tmp_name'], $uploadfile);		  
		  @mkdir('avatars', 0777);
		@chmod('avatars', 0777);
		$moveto = 'avatars/'.$user.'.jpg';
		//move_uploaded_file($avatar['tmp_name'], $moveto);
		//
            // проверяется перемещение файла  
            // в файловую систему хостинга 
            if (move_uploaded_file($_FILES['FILE']['tmp_name'], 
               $moveto  ))  
              {
				@chmod($moveto, 0644);	
				echo '<script> alert("Обновите страницу для смены аватарки.");</script>';
				//echo "<p  id='box' style='padding-left: 100px;'>Обновите страницу.</p>";
				//echo "<script> setTimeout(function(){$('#box').fadeOut()},300); </script> ";//30000 = 30 секунд
   //echo '<script>window.location.reload();</script>';				
                    // подсказываем 
                   // echo 'Файл был успешно загружен'; 
				//	header('location:'.$_SERVER['HTTP_REFERER']);
					//header("Location: /chat/");
              } 
          }else{ echo "<p style='padding-left: 100px;'>error 2</p>";}
       }else{ echo "<p style='padding-left: 100px;'>Файл не должен быть больше 1 мб.!</p>";} 
    

}

