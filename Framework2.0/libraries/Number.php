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
    
    static public function formatFloat($value, $digits = 2, $thousendSep = " ")
    {
    	if(is_numeric($value))
    		$ret = number_format($value, $digits, ",", $thousendSep);
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
    static public function formatExcel($value)
    {
    	return  str_replace('.', ',', trim($value));
    	
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
        
static public function formatToStringUk($number)
    {
    
	// обозначаем словарь в виде статической переменной функции, чтобы 
	// при повторном использовании функции его не определять заново
	static $dic = array(
	
		// словарь необходимых чисел
		array(
			-2	=> 'дві',
			-1	=> 'одна',
			1	=> 'одина',
			2	=> 'дві',
			3	=> 'три',
			4	=> 'чотири',
			5	=> 'пять',
			6	=> 'шість',
			7	=> 'сім',
			8	=> 'вісім',
			9	=> 'девять',
			10	=> 'десять',
			11	=> 'одинадцять',
			12	=> 'дванадцять',
			13	=> 'тринадцять',
			14	=> 'чотирнадцять' ,
			15	=> 'пятнадцять',
			16	=> 'шістнадцять',
			17	=> 'сімнадцять',
			18	=> 'вісімнадцять',
			19	=> 'девятнадцять',
			20	=> 'двадцять',
			30	=> 'тридцять',
			40	=> 'сорок',
			50	=> 'пятдесят',
			60	=> 'шістдесят',
			70	=> 'сімдесят',
			80	=> 'вісімдесят',
			90	=> 'девяносто',
			100	=> 'сто',
			200	=> 'двісти',
			300	=> 'триста',
			400	=> 'четириста',
			500	=> 'пятсот',
			600	=> 'шістсот',
			700	=> 'сімсот',
			800	=> 'вісімсот',
			900	=> 'дев\'ятсот'
		),
		
		// словарь порядков со склонениями для плюрализации
		array(
			array('гривня', 'гривні', 'гривень'),
			array('тисяча', 'тисячі', 'тисяч'),
			array('мільйон', 'мільйона', 'мільйонів'),
			array('мільярд', 'мільярда', 'мільярдів'),
			array('трильйон', 'трильйона', 'трильйонів'),
			array('квадрильйон', 'квадрильйона', 'квадрильйонів'),
			// квинтиллион, секстиллион и т.д.
		),
		
		// карта плюрализации
		array(
			2, 0, 1, 1, 1, 2
		)
	);
	
	// обозначаем переменную в которую будем писать сгенерированный текст
	$string = array();
	
	// дополняем число нулями слева до количества цифр кратного трем,
	// например 1234, преобразуется в 001234
	$number = str_pad($number, ceil(strlen($number)/3)*3, 0, STR_PAD_LEFT);
	
	// разбиваем число на части из 3 цифр (порядки) и инвертируем порядок частей,
	// т.к. мы не знаем максимальный порядок числа и будем бежать снизу
	// единицы, тысячи, миллионы и т.д.
	$parts = array_reverse(str_split($number,3));
	
	// бежим по каждой части
	foreach($parts as $i=>$part) {
		
		// если часть не равна нулю, нам надо преобразовать ее в текст
		if($part>0) {
			
			// обозначаем переменную в которую будем писать составные числа для текущей части
			$digits = array();
			
			// если число треххзначное, запоминаем количество сотен
			if($part>99) {
				$digits[] = floor($part/100)*100;
			}
			
			// если последние 2 цифры не равны нулю, продолжаем искать составные числа
			// (данный блок прокомментирую при необходимости)
			if($mod1=$part%100) {
				$mod2 = $part%10;
				$flag = $i==1 && $mod1!=11 && $mod1!=12 && $mod2<3 ? -1 : 1;
				if($mod1<20 || !$mod2) {
					$digits[] = $flag*$mod1;
				} else {
					$digits[] = floor($mod1/10)*10;
					$digits[] = $flag*$mod2;
				}
			}
			
			// берем последнее составное число, для плюрализации
			$last = abs(end($digits));
			
			// преобразуем все составные числа в слова
			foreach($digits as $j=>$digit) {
				$digits[$j] = $dic[0][$digit];
			}
			
			// добавляем обозначение порядка или валюту
			$digits[] = $dic[1][$i][(($last%=100)>4 && $last<20) ? 2 : $dic[2][min($last%10,5)]];
			
			// объединяем составные числа в единый текст и добавляем в переменную, которую вернет функция
			array_unshift($string, join(' ', $digits));
		}
	}
	
	// преобразуем переменную в текст и возвращаем из функции, ура!
	return join(' ', $string);
    }
}