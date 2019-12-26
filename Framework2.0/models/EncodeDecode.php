<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Telegram
 *
 * @author PHP
 */
class EncodeDecode extends wsActiveRecord
{
   public $_key = 'uhaehcv9ok';
   
   public static function encode($unencoded,$key = ''){//Шифруем
        if($key) {$this->_key = $key;}
$string=base64_encode($unencoded);//Переводим в base64

$arr=array();//Это массив
$x=0;
while ($x++< strlen($string)) {//Цикл
$arr[$x-1] = md5(md5($this->_key.$string[$x-1]).$this->_key);//Почти чистый md5
$newstr = $newstr.$arr[$x-1][3].$arr[$x-1][6].$arr[$x-1][1].$arr[$x-1][2];//Склеиваем символы
}
return $newstr;//Вертаем строку
}
    public static function decode($encoded, $key = '')
                        {//расшифровываем
        if($key) {$this->_key = $key;}
		$strofsym="qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM=";//Символы, с которых состоит base64-ключ
			$x=0;
			while ($x++<= strlen($strofsym)) {//Цикл
			$tmp = md5(md5($this->_key.$strofsym[$x-1]).$this->_key);//Хеш, который соответствует символу, на который его заменят.
			$encoded = str_replace($tmp[3].$tmp[6].$tmp[1].$tmp[2], $strofsym[$x-1], $encoded);//Заменяем №3,6,1,2 из хеша на символ
			}
			return base64_decode($encoded);//Вертаем расшифрованную строку
			}
}
