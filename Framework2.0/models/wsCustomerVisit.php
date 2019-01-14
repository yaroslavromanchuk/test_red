<?php
class wsCustomerVisit extends wsActiveRecord
{
	protected $_table = 'ws_visits';
	protected $_orderby = array('id' => 'DESC');
	
	
	protected function _defineRelations()
	{	
		$this->_relations = array(
                    'customer' => array('type'=>'hasOne', //belongs to
                    'class'=> self::$_customer_class,
                    'field'=>'customer_id'),
                    'machine' => array('type'=>'hasOne', //belongs to
                    'class'=>self::$_customer_machine_class,
                    'field'=>'machine_id'),
								);
	}
	
	//useless ??
    public static function findByHash($hash)
    {
        return wsActiveRecord::useStatic(self::$_customer_visit_class)->findFirst(array('hash_id'=>(string)$hash));
    }
}

