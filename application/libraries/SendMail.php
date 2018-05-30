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
			
			require_once($_SERVER['DOCUMENT_ROOT'].'/application/packages/phpmailer2/freakmailer.php');

            $mailer = new FreakMailer(true);
			
			$this->admin_email = trim(Config::findByCode('admin_email')->getValue());
			$this->admin_email_pass = trim(Config::findByCode('admin_email_pass')->getValue());
			
			$this->subscribe_email = Config::findByCode('subscribe_email')->getValue();
			$this->subscribe_pass = Config::findByCode('subscribe_pass')->getValue();
			
            $this->admin_name = trim(Config::findByCode('admin_name')->getValue());
            $this->email_charset = 'UTF-8';
        }

	public function sendEmail($to_email, $to_name = '', $subject, $msg ='', $uploadfile = '', $filename = '', $from_email = '', $from_name = '', $copy = false, $copy_email = '', $copy_name = ''){
		try{
// инициализируем класс
$mailer = new FreakMailer();

$mailer->IsHTML(true);

$mailer->Username = $this->admin_email;
$mailer->Password = $this->admin_email_pass;
if ($from_name) {
 $mailer->FromName = $from_name;
 }else{
 $mailer->FromName = $this->admin_name;
 }

if ($from_email) {
 $mailer->From = $from_email;
 }else{
 $mailer->From = $this->admin_email;
 } 
				
if($to_email and $this->isValidEmailNew($to_email)){
// Добавляем адрес в список получателей
$mailer->AddAddress($to_email, $to_name);

if($copy and $copy_email and $this->isValidEmailNew($copy_email)){
if($copy == 1) $mailer->AddCC($copy_email, $copy_name);//общая копия
if($copy == 2) $mailer->AddBCC($copy_email, $copy_name);//скрытая копия
}
} 
//$mailer->AddReplyTo('notforall@red.ua', 'Департаменту оплаты');
$subject = iconv('UTF-8', $this->email_charset, $subject);
	if (!trim($subject)) return false;
	
$mailer->CharSet="UTF-8";

// Устанавливаем тему письма
$mailer->Subject = $subject;

// настройка класса для отправки почты
if($uploadfile and $filename){
			 $mailer->addAttachment($uploadfile, $filename);
			 }

$msg = iconv('UTF-8', $this->email_charset, trim($msg));
// Задаем тело письма
$mailer->Body = $msg;



if(!$mailer->Send()){
$res = 'Не могу отослать письмо!'.$mailer->ErrorInfo;
  //return 'Не могу отослать письмо!';
}else{
$res = 'Письмо отослано!';
  //return 'Письмо отослано!';
}

$mailer->ClearAddresses();
$mailer->ClearAttachments();
$mailer->IsHTML(false);
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
	
public function sendSubEmail($to_email, $to_name = '', $subject, $msg ='', $uploadfile = '', $filename = '', $from_email = '', $from_name = '', $copy = false, $copy_email = '', $copy_name = ''){
		try{
// инициализируем класс
$mailer = new FreakMailer();
$mailer->IsHTML(true);

$mailer->Username = $this->subscribe_email;
$mailer->Password = $this->subscribe_pass;
if ($from_name) {
 $mailer->FromName = $from_name;
 }else{
 $mailer->FromName = $this->admin_name;
 }

if ($from_email) {
 $mailer->From = $from_email;
 }else{
 $mailer->From = $this->subscribe_email;
 } 
				
if($to_email and $this->isValidEmailNew($to_email)){
// Добавляем адрес в список получателей
$mailer->AddAddress($to_email, $to_name);
if($copy and $copy_email and $this->isValidEmailNew($copy_email)){
if($copy == 1) $mailer->AddCC($copy_email, $copy_name);//общая копия
if($copy == 2) $mailer->AddBCC($copy_email, $copy_name);//скрытая копия
}
} 
//$mailer->AddReplyTo('notforall@red.ua', 'Департаменту оплаты');
$subject = iconv('UTF-8', $this->email_charset, $subject);
	if (!trim($subject)) return false;
	
$mailer->CharSet="UTF-8";

// Устанавливаем тему письма
$mailer->Subject = $subject;

$msg = iconv('UTF-8', $this->email_charset, trim($msg));
// Задаем тело письма
$mailer->Body = $msg;

// настройка класса для отправки почты
if($uploadfile and $filename){ $mailer->addAttachment($uploadfile, $filename); }

if(!$mailer->Send()){
$res = 'Не могу отослать письмо!'.$mailer->ErrorInfo;
  //return 'Не могу отослать письмо!';
}else{
$res = 'Письмо отослано!';
  //return 'Письмо отослано!';
}

$mailer->ClearAddresses();
$mailer->ClearAttachments();
$mailer->IsHTML(false);
  wsLog::add('Email subscribe notification "' . $res . '" sent to: ' . $to_email , 'Email');

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
	$mailer = new FreakImap();
	$res = $mailer->Authorise();
	return $res;
	}catch (phpmailerException $e) {
	wsLog::add($e->errorMessage(), 'ERROR_IMAP1'); //Pretty error messages from PHPMailer
	//return $e->errorMessage();
	}catch (Exception $e) {
              wsLog::add($e->getMessage(), 'ERROR2'); //Boring error messages from anything else!
            }
	
	}
	
public function isValidEmailNew($email)
        {
            return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
        }



}
?>