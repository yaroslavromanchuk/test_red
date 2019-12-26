<?php
class Subscriber extends wsActiveRecord
{
	protected $_table = 'subscribers';
	protected $_orderby = array('email' => 'ASC');
	
        protected function _defineRelations()
    {
        $this->_relations = [
            'segment' => [
            'type' => 'hasOne',
            'class' => 'Subscriberstype',
            'field' => 'segment_id'
                ]
        ];
    }
    static function findByEmail($email)
    {
    	return wsActiveRecord::useStatic('Subscriber')->findFirst(array('email'=>(string)$email));
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
    	return wsActiveRecord::useStatic('Subscriber')->findFirst(array('men'=>$m_new, 'women'=>$g_new, 'baby'=>$d_new));
    }
	 

}
