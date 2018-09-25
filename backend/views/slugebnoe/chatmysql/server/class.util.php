<?php

class util
{
	
	public static function escape($string)
	{
		return (get_magic_quotes_gpc()) ? $string : db::escape($string);
	}
	
	public static function preparePass($pass)
	{
		return self::escape($pass);
	}
	
	public static function prepareNickname($nickname)
	{
		return self::escape(htmlspecialchars(trim($nickname)));
	}
	
	public static function preparePassword($password)
	{
		return md5(md5($password).md5(cfg::$unique_hash));
	}
	
	public static function prepareMessage($message)
	{
		return self::escape(self::replaceEmail(self::replaceUrl(htmlspecialchars(trim($message), ENT_QUOTES))));
	}
	
	public static function checkNickname($nickname)
	{
		return preg_match('/^[A-Za-zА-Яа-я0-9\.\-\_]+$/u', $nickname);
	}
	
	public static function generatePass()
	{
		return md5(microtime().rand(100, 999).cfg::$unique_hash);
	}
	
	public static function replaceUrl($message)
	{
		return preg_replace('/(https?|ftp):\/\/\S+/i', '<a href=\"$0\" target=\"_blank\">$0</a>', $message);
	}
	
	public static function replaceEmail($message)
	{
		return preg_replace('/[a-z0-9\.\-\_]+@[a-z0-9\.\-]+\.[a-z]{2,6}/i', '<a href=\"mailto:$0\">$0</a>', $message);
	}
	
	public static function strlen($s)
	{
		return mb_strlen($s, 'utf-8');
	}
	
	public static function setVar($var, $value)
	{
		if (db::selectRow(TB_SETTINGS, '*', "`var` = '{$var}'")) {
			db::update(TB_SETTINGS, array('var' => $var, 'value' => $value), "`var` = '{$var}'");
		} else {
			db::insert(TB_SETTINGS, array('var' => $var, 'value' => $value));
		}
	}
	
	public static function getVar($var)
	{
		if ($get = db::selectRow(TB_SETTINGS, '*', "`var` = '{$var}'")) {
			return $get['value'];
		} else {
			return false;
		}
	}
	
}

?>