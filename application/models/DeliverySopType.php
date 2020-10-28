<?php
class DeliverySopType extends wsActiveRecord
{
	protected $_table = 'ws_delivery_shop_types';
	protected $_orderby = array('id'=>'ASC');
        protected $_multilang = [
			'name' => 'name'
                            ];
	
	protected function _defineRelations()
	{	
		$this->_relations = [
                    'delivery' => [
                                                'type'            => 'hasOne', 
                                                'class'            => 'DeliveryType',
                                                'field'    => 'delivery_id',
						],
                ];
	}
        
        public static function geAdress($id){
            return wsActiveRecord::useStatic('DeliverySopType')->findAll(['id'=>$id])->at(0)->getName();
        }
        

}
