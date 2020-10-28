<?php
class PaymentMethod extends wsActiveRecord
{
	protected $_table = 'ws_payment_methods';
	protected $_orderby = array('sort'=>'ASC');
         protected $_multilang = ['name' => 'name'];
	
	protected function _defineRelations()
	{	
		$this->_relations = array(	);
	}

}
