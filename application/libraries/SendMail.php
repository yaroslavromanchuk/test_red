<?php
 class SendMail{
		static private $instance = null;
		private $view;
        private $admin_email;
        private $admin_name;
        private $email_charset;
		private $mailer;
		private $admin_email_pass;
	
	 static public function getInstance()
        {
            if (self::$instance == null) {
                self::$instance = new self();
            }
            return self::$instance;
        }
	
	// Подключаем класс FreakMailer

        private function  __construct(){
		global $site;
			
			require_once($_SERVER['DOCUMENT_ROOT'].'/application/packages/phpmailer/class.phpmailer.php');

            $this->mailer = new PHPMailer(true);
		if($site['smtp_mode'] == 'enabled'){  $this->mailer->Mailer = "smtp";}
		$this->mailer->Priority = 3;
			$this->mailer->IsSMTP();
			$this->mailer->SMTPAuth = true;
			$this->mailer->SMTPSecure = $site['smtp_secure'];
			$this->mailer->Host = $site['smtp_host'];
			$this->mailer->Port = $site['smtp_port'];
			$this->mailer->Username = trim(Config::findByCode('admin_email')->getValue());
            $this->mailer->Password = trim(Config::findByCode('admin_email_pass')->getValue());
			$this->mailer->FromName = trim(Config::findByCode('admin_name')->getValue());
			$this->mailer->From = trim(Config::findByCode('admin_email')->getValue());
			$this->mailer->CharSet="UTF-8";
			//$this->mailer->SMTPDebug = 2;
			

			$this->subscribe_email = Config::findByCode('subscribe_email')->getValue();
			$this->subscribe_pass = Config::findByCode('subscribe_pass')->getValue();
            $this->email_charset = 'UTF-8';
        }

	public function sendEmail($to_email = '', $to_name = '', $subject = '', $msg ='', $uploadfile = false, $filename = false, $from_email = false, $from_name = false, $copy = false, $copy_email = false, $copy_name = false){
	if(substr(trim($to_email), -2) != 'ru'){
            try{	
// инициализируем класс
//$this->mailer = new PHPMailer(true);
$this->mailer->IsHTML(true);
//$mailer->AddReplyTo('notforall@red.ua', 'Департаменту оплаты');

$subject = iconv('UTF-8', $this->email_charset, $subject);
	if (!trim($subject)) {return false;}
	
$msg = iconv('UTF-8', $this->email_charset, trim($msg));
// Устанавливаем тему письма
//$this->mailer->Subject = $subject;
$this->mailer->Subject = "=?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
// настройка класса для отправки почты
if($uploadfile and $filename) $this->mailer->addAttachment($uploadfile, $filename);
if($to_email and $this->isValidEmailNew($to_email)){
// Добавляем адрес в список получателей
$this->mailer->AddAddress($to_email, $to_name);

if($copy and $copy_email and $this->isValidEmailNew($copy_email)){
if($copy == 1) {$this->mailer->AddCC($copy_email, $copy_name);}//общая копия
if($copy == 2) {$this->mailer->AddBCC($copy_email, $copy_name);}//скрытая копия
}
} 

if ($from_name) {$this->mailer->FromName = $from_name;}
if ($from_email) {$this->mailer->From = $from_email;}
				
// Задаем тело письма
$this->mailer->Body = $msg;
//$this->mailer->send();

if(!$this->mailer->send()){
$res = 'Не могу отослать письмо!'.$this->mailer->ErrorInfo;
  //return 'Не могу отослать письмо!';
}else{
$res = 'Письмо отослано!';
  //return 'Письмо отослано!';
}

$this->mailer->ClearAddresses();
$this->mailer->ClearAttachments();
$this->mailer->IsHTML(false);
  wsLog::add('Email notification "' . $res . '" sent to: ' . $to_email , 'Email');
 // return true;
}catch (phpmailerException $e) {
	wsLog::add($e->errorMessage(), 'ERROR1'); //Pretty error messages from PHPMailer
    //return $e->errorMessage();
}catch (Exception $e) {
              wsLog::add($e->getMessage(), 'ERROR2'); //Boring error messages from anything else!
            }
return true;
        }
        
        return false;
	}
	
public function sendSubEmail($to_email, $to_name = '', $subject, $msg ='', $uploadfile = '', $filename = '', $from_email = '', $from_name = '', $copy = false, $copy_email = '', $copy_name = ''){
		
		try{
// инициализируем класс
//$mailer = new FreakMailer();
$this->mailer->IsHTML(true);

$this->mailer->Username = $this->subscribe_email;
$this->mailer->Password = $this->subscribe_pass;

if ($from_name) { $this->mailer->FromName = $from_name; }
if ($from_email){ 
    $this->mailer->From = $from_email;
}else{
    $this->mailer->From = $this->subscribe_email;
}


				
if($to_email and $this->isValidEmailNew($to_email)){
// Добавляем адрес в список получателей
    if(false){
        $this->mailer->AddReplyTo('replyto@email.com', 'Reply to name');
    }
$this->mailer->AddAddress(trim($to_email), $to_name);
/*
if($copy and $copy_email and $this->isValidEmailNew($copy_email)){
if($copy == 1) $this->mailer->AddCC($copy_email, $copy_name);//общая копия
if($copy == 2) $this->mailer->AddBCC($copy_email, $copy_name);//скрытая копия
}*/
} 
//$mailer->AddReplyTo('notforall@red.ua', 'Департаменту оплаты');
$subject = iconv('UTF-8', $this->email_charset, $subject);
	if (!trim($subject)) { return false; }
	
//$this->mailer->CharSet="UTF-8";

// Устанавливаем тему письма
$this->mailer->Subject = $subject;

$msg = iconv('UTF-8', $this->email_charset, trim($msg));
// Задаем тело письма
$this->mailer->Body = $msg;

// настройка класса для отправки почты
if($uploadfile and $filename){ $this->mailer->addAttachment($uploadfile, $filename); }

if(!$this->mailer->Send()){
$res = 'Не могу отослать письмо!'.$this->mailer->ErrorInfo;
$r = false;
}else{
$res = 'Письмо отослано!';
$r = true;
}
$this->mailer->ClearAddresses();
$this->mailer->ClearAttachments();
$this->mailer->IsHTML(false);
  wsLog::add('Email subscribe notification "' . $res . '" sent to: ' . $to_email , 'Email');
  return $r;

}catch (phpmailerException $e) {
	wsLog::add($e->errorMessage(), 'ERROR'); //Pretty error messages from PHPMailer
    return $e->errorMessage();
}catch (Exception $e) {
              wsLog::add($e->getMessage(), 'ERROR2'); //Boring error messages from anything else!
            }
			  return true;
	}
	
	public function getMailList(){

	try{
	$this->imap = new FreakImap();
	
	$this->imap->do_debug = $pop3['do_debug'];
	// $this->imap->tval = $pop3['tval'];
	 $this->imap->host = $pop3['host'];
	// $this->imap->port = $pop3['port']; 
	 $this->imap->username = $pop3['username'];
	 $this->imap->password = $pop3['password'];
	$res = $this->imap->Authorise();
	if($res){ echo 'ok';}else{
	echo 'fak';
	}
	}catch (phpmailerException $e) {
	wsLog::add($e->errorMessage(), 'ERROR_IMAP1'); //Pretty error messages from PHPMailer
	//return $e->errorMessage();
	}catch (Exception $e) {
              wsLog::add($e->getMessage(), 'ERROR2'); //Boring error messages from anything else!
            }
	
	}
	
public function isValidEmailNew($email){
            return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
        }

}
