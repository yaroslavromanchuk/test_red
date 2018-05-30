<?php
class Subscriber extends wsActiveRecord
{
	protected $_table = 'subscribers';
	protected $_orderby = array('email' => 'ASC');
	
    static function findByEmail($email)
    {
    	return wsActiveRecord::useStatic('Subscriber')->findFirst(array('email'=>(string) $email));
    }
	
    static function findByEmailA($email)
    {
    	return wsActiveRecord::useStatic('Subscriber')->findFirst(array('email'=>(string) $email, 'active'=> '1'));
    }

    static function findByCode($code)
    {
    	return wsActiveRecord::useStatic('Subscriber')->findFirst(array('code'=>(string) $code));
    }
	
	static function findByCheck($m_new, $g_new, $d_new)
    {
    	return wsActiveRecord::useStatic('Subscriber')->findFirst(array('code'=>(string) $code, 'men'=>$m_new, 'women'=>$g_new, 'baby'=>$d_new));
    }
	 

}
?>