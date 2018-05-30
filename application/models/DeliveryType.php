<?php
class DeliveryType extends wsActiveRecord
{
	protected $_table = 'ws_delivery_types';
	protected $_orderby = array('name'=>'ASC');
	
	protected function _defineRelations()
	{	
		$this->_relations = array();
	}

}
?>