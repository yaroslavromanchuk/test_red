<?php
class wsCustomerAddress extends wsActiveRecord
{
	protected $_table = 'ws_customer_addresses';
	protected $_orderby = array('id' => 'ASC');
    
	protected function _defineRelations()
	{
		$this->_relations = array('customer' => array('type'=>'hasOne', //belongs to
													'class'=>self::$_customer_class,
													'field'=>'customer_id'),
								'country' => array('type'=>'hasOne', //belongs to
													'class'=>self::$_country_class,
													'field'=>'country_id',
													'autoload'=>true),
								);	
	}

	public function isCustomerAddress($customer_id)
	{
		if($this->getCustomerId() == $customer_id)
			return true;
		else
			return false;
	}

}
?>