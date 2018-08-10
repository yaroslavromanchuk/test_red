<?php
//require_once('class.phpmailer.php');
require_once('class.imap.php');
//require_once('class.pop3.php');
/*
class FreakMailer extends PHPMailer
{
    var $priority = 3;
    var $to_name;
    var $to_email;
    var $From = null;
    var $FromName = null;
    var $Sender = null;
  
    function FreakMailer()
    {
      global $site;
      
      // Берем из файла config.php массив $site
       $this->Host = $site['smtp_host'];
        $this->Port = $site['smtp_port'];
		 $this->SMTPSecure = $site['smtp_secure'];
      if($site['smtp_mode'] == 'enabled' and false)
      {
		
       
		//$this->IsSMTP();
	    $this->SMTPAuth  = true;
		$this->Username  = $site['smtp_username'];
         $this->Password  =  $site['smtp_password'];
		 $this->SMTPSecure = $site['smtp_secure'];
		 $this->SMTPDebug = 2;
        $this->Mailer = "smtp";
      }
	  
      if(!$this->From)
      {
        $this->From = $site['from_email'];
      }
      if(!$this->FromName)
      {
        $this-> FromName = $site['from_name'];
      }
      if(!$this->Sender)
      {
        $this->Sender = $site['from_email'];
      }
      $this->Priority = $this->priority;
    }
	
	
	
}*/

class FreakImap extends IMAP{
var $do_debug = 0;
    var $tval;
    var $host;
    var $port;
	
function FreakImap(){
	 global $pop3;

	 
	 $this->do_debug = $pop3['do_debug'];
	 $this->tval = $pop3['tval'];
	 $this->host = $pop3['host'];
	 $this->port = $pop3['port']; 
	 $this->username = $pop3['username'];
	 $this->password = $pop3['password'];
	 
	}
}
?>