<?php
class Blacklist extends wsActiveRecord
{
	protected $_table = 'blacklist';
	protected $_orderby = array('id' => 'ASC');
	
    static function findByEmail($email)
    {
    	return wsActiveRecord::useStatic('Blacklist')->findFirst(array('email'=>(string) $email));
    }


}