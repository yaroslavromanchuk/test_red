<?php

class Cache {
    
    protected $_is_enabled = false;
    protected $_cache;
    
    public function setEnabled($is_enabled)
    {
      if ($is_enabled == true)
      { $this->_is_enabled = true;}
      else   
      {$this->_is_enabled = false;}
    }
    
    public function isEnabled()
    {
        return $this->_is_enabled;
    }
    
    public function load($id)
    {
        if (!$cache = $this->_getCache())
            return false;

        return $cache->load($id);
    }
    /**
     * 
     * @param type $data
     * @param type $id
     * @return boolean
     */
    public function save($data, $id)
    {
        if (!$cache = $this->_getCache()){ return false;}
        
        return $cache->save($data, $id);
    }
    
    protected function _getCache()
    {
        if (!$this->isEnabled())
            return false;
            
        if (is_object($this->_cache))
            return $this->_cache;
        
        // setup cachce
        Zend_Loader::loadClass("Zend_Cache");
        
        $frontendOptions = array(
            'lifetime' => 7200,
            'automatic_serialization' => true,
            'automatic_cleaning_factor' => 0); //disable autocleanup

        $backendOptions = array(
            'cache_dir' => './tmp/');

        //$this->_cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1'){
            $backendOptions = array(  'cache_dir' => './tmp/');
            $this->_cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        }else{
            $backendOptions = array();//APC
            $this->_cache = Zend_Cache::factory('Core', 'APC', $frontendOptions, $backendOptions);
        }
                
        return $this->_cache;
    }
    
    public function clean()
    {
    	$this->_getCache()->clean(Zend_Cache::CLEANING_MODE_ALL);
    	return ;
    }
	public function delete($name)
    {
	$this->_getCache()->remove(array('id' => $name));
	// parent::delete(md5(print_r($name, 1)));
	 
    	//$this->_getCache()->clean(Zend_Cache::CLEANING_MODE_ALL);
    	return ;
    }
}  

