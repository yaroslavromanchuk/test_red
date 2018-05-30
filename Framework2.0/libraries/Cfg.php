<?php
class Cfg
{
	protected $_default_pricelist;

	protected $_all_values;

	public static function getInstance()
	{
		static $instance;

		if ( !isset($instance) )
		{
			$instance = new Cfg();
		}

		return $instance;
	}

	public function __construct()
	{
		global $config_values;
		if(!isset($config_values))
			throw new Exception('Configuration is not found');

		$this->_all_values = $config_values;
	}

	public function getValues()
	{
		return (object) $this->_all_values;
	}

	public function getValue($name = '')
	{
		if(!$name)
			return $this->getValues();

		if(isset($this->_all_values[$name]))
			return $this->_all_values[$name];
		else
			throw new Exception("Value for config '$name' not found");
	}
}

/*

Cfg::getInstance()->getValues()->name_of_value;
Cfg::getInstance()->getValue('name_of_value');
*/