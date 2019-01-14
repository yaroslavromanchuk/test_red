<?php
class wsCustomerMachine extends wsActiveRecord
{
    protected $_table = 'ws_machines';
	
	protected function _defineRelations()
	{	
		$this->_relations = array('visit_created' => array('type'=>'hasOne', //belongs to
													'class'=>self::$_customer_visit_class,
													'field'=>'visit_created_id'),
								'visit_last' => array('type'=>'hasOne', //belongs to
													'class'=>self::$_customer_visit_class,
													'field'=>'visit_last_id'),

								'visits' => array('type' => 'hasMany',
													'class' => self::$_customer_visit_class,
													'field_foreign' => 'machine_id'),
								);
	}
    
	//useless ??
    public static function findByHash($hash)
    {
        return wsActiveRecord::useStatic(self::$_customer_machine_class)->findFirst(array('hash_id'=>(string) $hash));
    }
    
}

