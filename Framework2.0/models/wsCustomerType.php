<?php
class wsCustomerType extends wsActiveRecord
{
	protected $_table = 'ws_customer_types';
	protected $_orderby = array('default' => 'DESC',
								'name' => 'ASC');

	protected function _defineRelations()
	{	
		$this->_relations = array('customers' => array('type' => 'hasMany',
													'class' => self::$_customer_class,
													'field_foreign' => 'customer_type_id'),
								);
	}

	//rewrite !!
	public static function getDefault()
	{
		return wsActiveRecord::useStatic(self::$_customer_type_class)->findFirst(array('default'=>1));
	}	
}
?>