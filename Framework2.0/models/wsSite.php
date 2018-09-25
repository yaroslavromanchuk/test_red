<?php
class wsSite extends wsActiveRecord
{
    protected $_table = 'ws_sites';
    protected $_orderby = array('default' => 'DESC');


    static function getThisSite($hostname = '')
    {
		$site = null;
		if($hostname) 
		{
			$hostname = strtolower(str_replace('www.','',$hostname));
			$site = wsActiveRecord::useStatic(self::$_site_class)->findFirst(array('hostname' => $hostname));		
		}
		
		if (!$site)
                    { 
                        $site = new Site(Cfg::getInstance()->getValue('site_id')); 
                        
                    }
                
    	return $site;
    }

	public static function getDefault()
	{
		return wsActiveRecord::useStatic(self::$_site_class)->findFirst(array('default' => 1));
	}

}
