<?php
class wsLanguage extends wsActiveRecord
{
	protected $_table = 'ws_languages';
	protected $_orderby = array('default' => 'DESC',
								'sequence' => 'ASC');

		
	public static function getDefaultLang()
	{
		return wsActiveRecord::useStatic(self::$_language_class)->findFirst(array('default'=> 1, 'active'=>1));
	}

	public static function findAllActive()
	{
		return wsActiveRecord::useStatic(self::$_language_class)->findAll(array('active'=> 1));
	}

	public static function findByCode($code)
	{
		$l = wsActiveRecord::useStatic(self::$_language_class)->findFirst(array('code'=>$code, 'active'=>1));
		if(!$l){$l = wsLanguage::getDefaultLang();}
		return $l;
	}
        public static function findLangActive($lang = null)
	{
		return wsActiveRecord::useStatic(self::$_language_class)->findFirst(array('code'=> $lang));
	}

}

