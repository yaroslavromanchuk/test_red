<?php

class Number {

	static public function formatPhone($val, $pattern = '##(###)###-##-##') {
		if(!$val)
			return '';
		if(strlen($val)<6)
			return $val;

		//$val = Number::clearPhone($val);
		//$val = /*$val[0] .*/'('.$val[1].$val[2].$val[3].')'.
		//		$val[4].$val[5].$val[6].'-'.$val[7].$val[8].'-'.$val[9].$val[10];
		//8(050)443-23-20
		//$pattern = '##(###)###-##-##';
		$i = 1;
		$j = 1;
		$res = '';
		while(isset($pattern[strlen($pattern)-$i]))
		{
			if($pattern[strlen($pattern)-$i] == '#')
			{
				$res .= $val[strlen($val)-$j];
				$j++;
			}
			else
				$res .= $pattern[strlen($pattern)-$i];
			$i++;
		}
		return strrev($res);
	}

	static public function formatPhoneSorting($val) {
		if(!$val)
			return '';
		return Number::formatPhone($val);
		$val =  '('.$val[1].$val[2].$val[3].') '.
				$val[4].$val[5].$val[6].' '.$val[7].$val[8].''.$val[9].$val[10];
		//8(050)443-23-20
		
		return $val;
	}

	static public function formatPhoneSMS($val, $prefix = '3') {
		$res = Number::clearPhone($val);
		if($res && (mb_strlen($res) == 11))
		{
			if($res[0] <> '8')
				return /*'+' .*/ $res;
			else
				$res = $prefix . $res;
		}
		if($res && (mb_strlen($res) == 12))
			$res = /*'+' .*/ $res;
		return $res;
	}
	
	static public function clearPhone($val) {
		$res = '';
		$val = (string) $val;
		for($i=0;$i<mb_strlen($val);$i++) {
			if(is_numeric($val[$i]))
				$res .= $val[$i];
		}
		if(mb_strlen($res)==7) {
			$res = '8044' . $res;
		}
		if(mb_strlen($res)==9) {
			$res = '80' . $res;
		}
		if(mb_strlen($res)==10) {
			if($res[0] == '0')
				$res = '8' . $res; //ukraine
			else //if 9
				$res = '7' . $res; //russia
		}
		if(mb_strlen($res)>12) {
			$res = substr ( $res, -12, 12 );
		}
		return $res;
	}

    static public function hidePhone($value, $num = 3)
    {
    	$pos = strlen($value);
		while(($counter < $num) || ($pos == 0))
		{
			if(is_numeric($value[$pos]))
			{
   				$value[$pos] = '#';
   				$counter++;
   			}
   			$pos--;
    	}
    	return $value;
    }
    

	static public function clearHouse($val) {
		return str_replace('\\','/',str_replace('-','',$val));
	}

    static public function formatInt($value)
    {
    	if(is_numeric($value))
    		$ret = number_format($value, 0, "", " ");
    	else
    		$ret = $value;

    	return $ret;
    }
    
    static public function formatFloat($value, $digits = 2)
    {
    	if(is_numeric($value))
    		$ret = number_format($value, $digits, ",", " ");
    	else
    		$ret = $value;

    	return $ret;
    }

	//add currency sign??
    static public function formatPrice($value, $digits = 0)
    {
    	if(is_numeric($value))
    		$ret = number_format($value, $digits, ",", " ");
    	else
    		$ret = $value;

    	return $ret;
    }

    static public function formatMysql($value)
    {
    	$value = str_replace(',', '.', trim($value));
    	return $value;
    }

    static public function formatPercent($value, $digits = 0)
    {
    	if(is_numeric($value))
    		$ret = number_format((float)$value, $digits, ",", "");
    	else
    		$ret = (float)$value;

    	return $ret . '%';
    }

    static private function hideDigits($value)
    {
		for($i = 0; $i<mb_strlen($value); $i++)
    	{
    		if(is_numeric($value[$i]))
    			$value[$i] = '#';
    	}
    	return $value;
    }
    
    static public function formatFulldate($date, $format = 'd/m/y H:i')
    {
    	return date($format, strtotime($date));
    }

	static function extractPhones($data)
	{
		$phone_regular_expression="((8|\+38|)-?)?\(?\d{3,5}\)?-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}((-?\d{1})?-?\d{1})?";
		$preg=(function_exists("preg_match") ? "/".str_replace("/", "\\/", $phone_regular_expression)."/i" : "");
		preg_match_all($preg, str_replace(')','-',str_replace('(','-',$data)), $text);
		$phones = array_unique($text[0]);
		if(count($phones))
			foreach($phones as &$phone)
			{
				$phone = Number::formatPhoneSMS($phone);
			}
		$phones = array_unique($phones);	
		return $phones;
	}

}