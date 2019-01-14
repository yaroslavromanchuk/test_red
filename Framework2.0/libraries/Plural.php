<?php
class Plural {

	const MALE = 1;
	const FEMALE = 2;
	const NEUTRAL = 3;
	
	protected static $_digits = array(
		self::MALE => array('ноль', 'один', 'два', 'три', 'четыре','пять', 'шесть', 'семь', 'восемь', 'девять'),
		self::FEMALE => array('ноль', 'одна', 'две', 'три', 'четыре','пять', 'шесть', 'семь', 'восемь', 'девять'),
		self::NEUTRAL => array('ноль', 'одно', 'два', 'три', 'четыре','пять', 'шесть', 'семь', 'восемь', 'девять')
		);
	
	protected static $_ths = array(
		0 => array('','',''),
		1=> array('тысяча', 'тысячи', 'тысяч'),	
		2 => array('миллион', 'миллиона', 'миллионов'),
        3 => array('миллиард','миллиарда','миллиардов'),
        4 => array('триллион','триллиона','триллионов'),
        5 => array('квадриллион','квадриллиона','квадриллионов')
		);
	
	protected static $_ths_g = array(self::NEUTRAL, self::FEMALE, self::MALE, self::MALE, self::MALE, self::MALE); // hack 4 thsds
	
	protected static $_teens = array(
		0=>'десять',
        1=>'одиннадцать',
        2=>'двенадцать',
        3=>'тринадцать',
        4=>'четырнадцать',
        5=>'пятнадцать',
        6=>'шестнадцать',
        7=>'семнадцать',
        8=>'восемнадцать',
        9=>'девятнадцать'
        );

	protected static $_tens = array(
	    2=>'двадцать',
        3=>'тридцать',
        4=>'сорок',
        5=>'пятьдесят',
        6=>'шестьдесят',
        7=>'семьдесят',
        8=>'восемьдесят',
        9=>'девяносто'
		);
	
	protected static $_hundreds = array(
        1=>'сто',
        2=>'двести',
        3=>'триста',
        4=>'четыреста',
        5=>'пятьсот',
        6=>'шестьсот',
        7=>'семьсот',
        8=>'восемьсот',
        9=>'девятьсот'
        );
	
	static protected function _ending($value, array $endings = array()) {
		$result = '';
		if ($value < 2) $result = $endings[0];
		elseif ($value < 5) $result = $endings[1];
		else $result = $endings[2];
		
		return $result;	
	}
	
	static protected function _triade($value, $mode = self::MALE, array $endings = array()) {
		$result = '';
		if ($value == 0) { return $result; }
		$triade = str_split(str_pad($value,3,'0',STR_PAD_LEFT));
		if ($triade[0]!=0) { $result.= (self::$_hundreds[$triade[0]].' '); }
		if ($triade[1]==1) { $result.= (self::$_teens[$triade[2]].' '); }
		elseif(($triade[1]!=0)) { $result.= (self::$_tens[$triade[1]].' '); }
		if (($triade[2]!=0)&&($triade[1]!=1)) { $result.= (self::$_digits[$mode][$triade[2]].' '); }
		if ($value!=0) { $ends = ($triade[1]==1?'1':'').$triade[2]; $result.= self::_ending($ends,$endings).' '; }
		return $result;
	}
	/**
         * 
         * @param type $value
         * @param type $mode
         * @param array $endings
         * @return type
         */
	static public function asString($value, $mode = self::MALE, array $endings = array()) {
		if (empty($endings)) { $endings = array('','',''); }
		$result = '';
		$steps = ceil(strlen($value)/3);
		$sv = str_pad($value, $steps*3, '0', STR_PAD_LEFT);
		for ($i=0; $i<$steps; $i++) {
			$triade = substr($sv, $i*3, 3);
			$iter = $steps - $i;
			$ends = ($iter!=1)?(self::$_ths[$iter-1]):($endings);
			$gender = ($iter!=1)?(self::$_ths_g[$iter-1]):($mode);
			$result.= self::_triade($triade,$gender, $ends);
		}
		return $result;
	}
	/**
         * Валюта
         * @param type $value
         * @param type $kop
         * @return string
         */
	static public function currency($value, $kop = 1)
	{
		$parts = explode(',',str_replace(' ', '', Number::formatPrice($value,2)));
		$first = self::asString($parts[0], self::FEMALE, array('грн.', 'грн.', 'грн.'));

		$second = self::asString($parts[1], self::FEMALE, array('коп.', 'коп.', 'коп.'));
		$kops = explode(' ', $second);
		if(!$kop)
			$second = $parts[1] . ' ' . @$kops[count($kops)-2];

		return trim($first . $second);
	}
	
	/**
         * Склонение
         * @param type $int
         * @param type $expressions
         * @param type $showint
         * @return string
         */
	public static function declension($int, $expressions, $showint = true) {
		settype($int, "integer");
		$count = $int % 100;
		if ($count >= 5 && $count <= 20) {
			$result = ($showint? $int." ":"").$expressions['2'];
		} else {
			$count = $count % 10;
			if ($count == 1) {
				$result = ($showint? $int." ":"").$expressions['0'];
			} elseif ($count >= 2 && $count <= 4) {
				$result = ($showint? $int." ":"").$expressions['1'];
			} else {
				$result = ($showint? $int." ":"").$expressions['2'];
			}
		}
		return $result;
	 }
	 /**
          * Относительное время
          * @param type $timestamp
          * @return string
          */
	static public function relativeTime($timestamp) {
		$difference = time() - $timestamp;
		$periods = array(	array("секунду", "секунды", "секунд"), 
							array("минуту", "минуты", "минут"),
							array("час", "часа", "часов"),
							array("день", "дня", "дней"), 
							array("неделя", "недели", "недель"),
							array("месяц", "месяца", "месяцев"),
							array("год", "года", "лет"),
							array("декада", "декады", "декад"),
							array("столетие", "столетия", "столетий"));
		$lengths = array("60","60","24","7","4.35","12","10","100");
		
		if ($difference > 0) { // this was in the past
			$ending = "назад";
		} else { // this was in the future
			$difference = -$difference;
			$ending = "вперед";
		}
		for($j = 0; $difference >= $lengths[$j]; $j++)
			$difference /= $lengths[$j];
		$difference = round($difference);
		$text = Plural::declension($difference, $periods[$j]) . " $ending";
		
		return $text;
	}	 
	
}
