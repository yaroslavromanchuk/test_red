<?php
/*
Copyright (c) 2009 by Dmitry Skachko, SMSClub.com.ua
All rights reserved.
Version 1.1

History:

Version 1.0 (10.06.2009)
 - First Release
 
Version 1.1 (24.09.2009)
 - Added send_date parameter
 - Added wap-push link
 - Added flash message 
*/

class SMSClub
{
	//protected $_server = 'http://smsclub.com.ua/api/http.php';
	protected $_server = 'https://alphasms.com.ua/api/http.php';
	protected $_errors = array();
	
	//IN: login, password on website SMSClub.com.ua
	public function __construct($login, $password)
	{
		$this->_login = $login;
		$this->_password = $password;
	}

	//IN: sender name, phone of receiver, text message in UTF-8 - if long - will be auto split
	//OUT: message_id to track delivery status, if empty message_id - check errors via $this->getErrors()
	public function sendSMS($from, $to, $message, $send_dt = 0, $wap = '', $flash = 0)
	{
		if(!$send_dt)
			$send_dt = time();
		$data = array(	'from'=>$from,
						'to'=>$to,
						'message'=>$message,
						'ask_date'=>date('Y-m-d H:i:s', $send_dt),
						'wap'=>$wap,
						'flash'=>$flash);
		$result = $this->execute('send', $data);
		if(count(@$result['errors']))
			$this->_errors = $result['errors'];
		return @$result['id'];
	}
	
	//IN: message_id to track delivery status
	//OUT: text name of status
	public function receiveSMS($sms_id)
	{
		$data = array('id'=>$sms_id);
		$result = $this->execute('receive', $data);
		if(count(@$result['errors']))
			$this->_errors = $result['errors'];
		return @$result['status'];		
	}

	//OUT: amount in UAH, if no return - check errors
	public function getBalance()
	{
		$result = $this->execute('balance');
		if(count(@$result['errors']))
			$this->_errors = $result['errors'];
		return @$result['balance'];		
	}
	
	//OUT: returns array of errors
	public function getErrors()
	{
		return $this->_errors;
	}
	

	protected function execute($command, $params = array())
	{
		$this->_errors = array();
		//$response = file_get_contents($this->generateUrl($command, $params));
		return @unserialize($this->base64_url_decode(file_get_contents($this->generateUrl($command, $params))));
	}
	
	protected function generateUrl($command, $params = array())
	{
		$params_url = '';
		if(count($params))
			foreach($params as $key=>$value)
		 		$params_url .= '&' . $key . '=' . $this->base64_url_encode($value);
		$auth = '?login=' . $this->base64_url_encode($this->_login) . '&password=' . $this->base64_url_encode($this->_password);
		$command = '&command=' . $this->base64_url_encode($command);
		return $this->_server . $auth . $command . $params_url;
	}

	public function base64_url_encode($input)
	{
		return strtr(base64_encode($input), '+/=', '-_,');
	}
	
	public function base64_url_decode($input)
	{
		return base64_decode(strtr($input, '-_,', '+/='));
	}

}

