<?php
class wsCustomerStatus extends wsActiveRecord
{
	protected $_table = 'ws_customer_statuses';
	protected $_orderby = array('default' => 'DESC',
								'name' => 'ASC');
								
	protected function _defineRelations()
	{
		$this->_relations = array('customers' => array('type' => 'hasMany',
													'class' => self::$_customer_class,
													'field_foreign' => 'customer_status_id'),
								);
	}
	
	//rewrite !!
	public static function getDefault()
	{
		return wsActiveRecord::useStatic(self::$_customer_status_class)->findFirst(array('default'=>1));
	}
}
?>