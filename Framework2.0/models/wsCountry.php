<?php
class wsCountry extends wsActiveRecord
{
	protected $_table = 'ws_countries';
	protected $_orderby = array('default' => 'DESC',
								'country' => 'ASC');

	public static function getDefault()
	{
		return wsActiveRecord::useStatic(self::$_country_class)->findFirst(array('default'=>1));
	}
}

