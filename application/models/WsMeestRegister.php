<?php 
class WsMeestRegister extends wsActiveRecord
    {
	  protected $_table = 'ws_meest_register';
        protected $_orderby = array('id' => 'DESC');
	
	
	public static function newRegister($ttn, $register)
    {
        $post = new WsMeestRegister();
		$post->setCtime(date('Y-m-d H:i:s'));
		$post->setList($ttn);
		$post->setRegister($register);
        $post->save();
		//return $post->getId();

    }

	
	
	
	
	
	}




?>