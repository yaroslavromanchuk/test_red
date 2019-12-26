<?php
class wsConfig extends wsActiveRecord
{
	protected $_table = 'ws_config';
	
	protected static $_cache = [];
	protected $_multilang = ['value' => 'value'];

    public static function findByCode($code)
    {
		if(isset(self::$_cache[$code])){return self::$_cache[$code];}
		
        self::$_cache[$code] = wsActiveRecord::useStatic(self::$_config_class)->findFirst(array('code'=>$code));
		if(!self::$_cache[$code] || !self::$_cache[$code]->getId())
		{
			self::$_cache[$code] = new self::$_config_class();
			
			//autosave new values
			self::$_cache[$code]->setCode($code);
			self::$_cache[$code]->save();
		}
		return self::$_cache[$code];
    }

}

