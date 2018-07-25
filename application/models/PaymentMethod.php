<?php
class PaymentMethod extends wsActiveRecord
{
	protected $_table = 'ws_payment_methods';
	protected $_orderby = array('name'=>'ASC');
	
	protected function _defineRelations()
	{	
		$this->_relations = array(	);
	}

}
?>