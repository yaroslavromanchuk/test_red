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
        public static function getIsShop($dely){
            return  wsActiveRecord::useStatic('DeliveryType')->findById($dely)->shop;
        }
        public static function Adress($id){
            return wsActiveRecord::useStatic('DeliveryType')->findAll(['id'=>$id])->at(0)->getAdress();
        }

}
