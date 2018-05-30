<?php 
  session_start();
  
	require_once "../configuration.php";
	require_once '../libs/PHPMailer/PHPMailerAutoload.php';
	
	$position = isset($_POST['position']) ? $_POST['position'] : "";
	$name = isset($_POST['name']) ? $_POST['name'] : "";
	$age = isset($_POST['age']) ? $_POST['age'] : "";
	$citizenship = isset($_POST['citizenship']) ? $_POST['citizenship'] : "";
	$tel = isset($_POST['tel']) ? $_POST['tel'] : "";
	$email = isset($_POST['email']) ? $_POST['email'] : "";
	$preferedShop = isset($_POST['prefered-shop']) ? $_POST['prefered-shop'] : "";
	
	$subject = "Aнкета на должность '{$position}'";
	
	$message = <<<HTML
		<html> 
		    <head> 
		        <title>Aнкета на должность '{$position}'</title>
		        <style>
		        	table {
			        	border-collapse: collapse;
			        }
			        td {
				        padding: 5px;
				        min-width: 150px;
				        border: 1px solid #cacaca;
				    }
		        </style> 
		    </head> 
		    <body> 
			    <h4>Aнкета на должность <strong>'{$_POST['position']}'<strong><h4>
				<table>
					<tr>
						<td>Фамилия, имя</td>
						<td>{$name}</td>
					</tr>
					<tr>
						<td>Возраст</td>
						<td>{$age}</td>
					</tr>
					<tr>
						<td>Гражданство</td>
						<td>{$citizenship}</td>
					</tr>
					<tr>
						<td>Телефон</td>
						<td>{$tel}</td>
					</tr>
					<tr>
						<td>e-mail</td>
						<td>{$email}</td>
					</tr>
					<tr>
						<td>Желаемое место работы </td>
						<td>{$preferedShop}</td>
					</tr>
				</table>
		    </body> 
		</html>
HTML;

  $mail = new PHPMailer;
    
  $mail->isSMTP();
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'tls';
  $mail->Host = Config::$mail_host;
  $mail->Username = Config::$mail_username;
  $mail->Password = Config::$mail_password;
  $mail->Port = Config::$mail_port;
  $mail->CharSet = "utf-8";
  
  $mail->setFrom(Config::$vacancy_questionnaire_send_from);
  foreach (Config::$vacancy_questionnaire_send_to as $address) {
    $mail->addAddress($address);
  }
  foreach (Config::$vacancy_questionnaire_send_to_bcc as $address) {
    $mail->addBCC($address);
  }
  
  $mail->isHTML(true);
  $mail->Subject = $subject;
  $mail->Body = $message;
  
  if(!$mail->send()) {
      $_SESSION['Message'] = "Анкета НЕ отправлена! <small>Что-то пошло не так...</small>";
  		header("location: ../");
  		exit();
  } else {
  	if (isset($_GET['redirect'])) {
  		header("location: {$_GET['redirect']}");
  		exit();
  	} else {
      $_SESSION['Message'] = "Анкета отправлена!";
  		header("location: ../");
  		exit();
  	}
  }
?>