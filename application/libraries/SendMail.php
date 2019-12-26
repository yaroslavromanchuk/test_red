<?php
 class SendMail{
	static private $instance = null;
        private $email_charset;
	private $mailer;
	
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
        /**
         * Отправка письма с market@red.ua
         * @param type $to_email = '' - email получателя
         * @param type $to_name = '' - имя получателя
         * @param type $subject = '' - тема письма
         * @param type $msg = '' - содержимое письма
         * @param type $uploadfile = '' - путь к вложенному файлу
         * @param type $filename = '' - имя вложенного файла
         * @param type $from_email = false - email отправителя
         * @param type $from_name = false - имя отправителя
         * @param type $copy = false - (0-нет, 1- видимая, 2- скрытая)
         * @param type $copy_email = false - email получателя копии
         * @param type $copy_name = false - имя получателя копии
         * @return boolean
         */
	public function sendEmail($to_email = '', $to_name = '', $subject = '', $msg ='', $uploadfile = '', $filename = '', $from_email = false, $from_name = false, $copy = false, $copy_email = false, $copy_name = false){
	if(substr(trim($to_email), -2) != 'ru'){
        
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
if($uploadfile and $filename){ $this->mailer->addAttachment($uploadfile, $filename);}
            try{	

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
if(!@$this->mailer->Send()){
$res = 'Не могу отослать письмо!'.$this->mailer->ErrorInfo;
}else{
$res = 'Письмо отослано!';
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
        
        
/**
         * Отправка рассылки c notforall@red.ua
         * @param type $to_email = '' - email получателя
         * @param type $to_name = '' - имя получателя
         * @param type $subject = '' - тема письма
         * @param type $msg = '' - содержимое письма
         * @param type $uploadfile = '' - путь к вложенному файлу
         * @param type $filename = '' - имя вложенного файла
         * @param type $from_email = false - email отправителя
         * @param type $from_name = false - имя отправителя
         * @param type $copy = false - (0-нет, 1- видимая, 2- скрытая)
         * @param type $copy_email = false - email получателя копии
         * @param type $copy_name = false - имя получателя копии
         * @return boolean
         */	
public function sendSubEmail($to_email, $to_name = '', $subject, $msg ='', $uploadfile = '', $filename = '', $from_email = false, $from_name = false, $copy = false, $copy_email = '', $copy_name = ''){
		
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
