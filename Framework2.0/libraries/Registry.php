<?php

class Registry extends Orm_Collection
{
	/**
	 *  To use without Zend Framework
	 */
	public $_inititated = 0;
	static $instance;

	public static function & getInstance ($config_file = '')
	{
		if (!isset(self::$instance) || !self::$instance) {
			$c = __CLASS__;
			self::$instance = new $c ($config_file);
		}

		return self::$instance;
	}
    
	
    static public function loadConfig($filename)
    {
		/*
    	if(strtolower($filename) == 'db')
    		return Registry::loadDBConfig();
		*/
			
    	if(is_array($filename) || file_exists($filename)) {
			if(is_array($filename))
				$config = $filename;
    		else
				include_once($filename);
    		if(!isset($config))
    			throw new Exception('$config variable not found');
    		//config loaded to variable $config
    		foreach($config as $key => $value) {
    			Registry::set(Registry::_implodeCase($key), $value);
    		}
    	} else {
    		throw new Exception("Cannot load config from $filename");
    	}
    }
    
    static public function loadDBConfig($class = '')
    {
    	$r = Registry::getInstance();
    	//only if DB initiated
		if(isset($r->db)) {
			if($class && class_exists($class)) {
				//cache this
				$rows = Config::useStatic($class)->findAll();
				foreach($rows as $row) {
					$name = $row->getName();
					$r->$name = $row->getValue();
				}
				$r->_initiated = 1;
			}
		}
		
		/*
		if(!$r->_initiated)
			throw new Exception("Cannot load config from DB");
		*/		
    }
	
	static public function get($variable)
	{
		$variable[0] = ucfirst($variable[0]); 
		$r = &Registry::getInstance();
		$var = Registry::_implodeCase($variable);
		if(isset($r->$variable))
			return $r->{"get$var"}();
		else {
			//try to load this data from config table

			//only if DB initiated
			if(!$r->_initiated && isset($r->db)) {
				Registry::loadDBConfig();
			}
			
			if($r->_initiated && isset($r->$var))
				return $r->{"get$var"}();
			else {
				//we didn't find it anywhere - then add it to DB
				if($r->_initiated) {
					if(!$r->{"get$var"}()) {
						$tmp = new Config();
						
						//autosave new values
						$tmp->setName(strtolower($variable));
						$tmp->save();
						$r->{"set$var"}($tmp);
						return $r->{"get$var"}();
					}
				}
			}			
		}
		return null;
	}

	static public function set($var, $value)
	{
/*
//2517
		echo '<pre>var: ';
		print_r($var);
		echo '</pre>';
		
//2517
		echo '<pre>value: ';
		print_r($value);
		echo '</pre>';
*/
		$var[0] = ucfirst($var[0]); 
		Registry::getInstance()->{"set$var"}($value);
	}
	
	static public function unRegister($var)
	{
		Registry::getInstance()->del($var);
	}	

	static public function isRegistered($var)
	{
		$var[0] = ucfirst($var[0]); 
		$r = Registry::getInstance();
		if(isset($r->$var))
			return true;
		else
			return false;
	}
}