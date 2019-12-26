<?php
class DeliveryType extends wsActiveRecord
{
	protected $_table = 'ws_delivery_types';
	protected $_orderby = array('sort'=>'ASC');
        protected $_multilang = [
			'name' => 'name',
                        'prices' => 'prices',
			'time' => 'time',
			'notice' => 'notice',
                        'note' => 'note',
			'adress' => 'adress'
                            ];
	
	protected function _defineRelations()
	{	
		$this->_relations = array();
	}

}
