<?php 
	require_once "../configuration.php";
	require_once '../libs/PHPMailer/PHPMailerAutoload.php';
	
	$commentType = isset($_POST['comment-type']) ? $_POST['comment-type'] : "";
	$name = isset($_POST['name']) ? $_POST['name'] : "";
	$tel = isset($_POST['tel']) ? $_POST['tel'] : "";
	$email = isset($_POST['email']) ? $_POST['email'] : "";
	$commentText = isset($_POST['comment-text']) ? $_POST['comment-text'] : "";
	
	$subject = "Отзыв о работе 'RED'";
	
	$message = <<<HTML
		<html> 
		    <head> 
		        <title>Отзыв о работе 'RED'</title>
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
			    <h4>Отзыв о работе 'RED': {$commentType}<strong><h4>
				<table>
					<tr>
						<td>Имя</td>
						<td>{$name}</td>
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
						<td>Комментарий</td>
						<td>{$commentText}</td>
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
  
  $mail->setFrom(Config::$comment_send_from);
  foreach (Config::$comment_send_to as $address) {
    $mail->addAddress($address);
  }
  foreach (Config::$comment_send_to_bcc as $address) {
    $mail->addBCC($address);
  }
  
  $mail->isHTML(true);
  $mail->Subject = $subject;
  $mail->Body = $message;
  
  $mail->send();
  	
	if (isset($_GET['redirect'])) {
		header("location: {$_GET['redirect']}");
	} else {
		header("location: {$_SERVER['HTTP_REFERER']}");
	}
?>