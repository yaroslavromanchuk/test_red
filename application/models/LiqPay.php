<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LiqPay
 *
 * @author PHP
 */
class LiqPay {
    //put your code here
    
   // private $version = 3;
      //  private $public_key = 'sandbox_i88126453387';//'i51858842721';
	//private $action = 'pay';
	
	//private $currency = 'UAH';
	public $private_key = 'sandbox_3gY2gskFTOqP6I2qk1CXRdbHAMJMFIIafbuwSpoB';//r7A8olggiLS7OEZs4xAioTg2SZI4w6q6mdWFU9kT';
	//private $checkout_url = 'https://www.liqpay.ua/api/3/checkout';
        
        
        
        public function calculateSignature($data)
                {
                    return base64_encode(sha1($this->private_key . $data . $this->private_key, true));
                }
}
