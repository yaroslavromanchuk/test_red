<?php 
class Fop extends wsActiveRecord
{
        protected $_table = 'ws_fop'; 
	protected $_orderby = array('id' => 'ASC'); 
        
        protected function _defineRelations()
    {
        $this->_relations = [
            'orders' => [
				'type' => 'hasMany',
				'class' => self::$_shop_orders_class,
				'field_foreign' => 'fop_id',
				'orderby' => array('id' => 'ASC'),
				'onDelete' => 'delete'
			]
        ];
	
    }
    public function countOrder(){
        
       $order =  wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT count(id) as ctn FROM ws_orders WHERE DATE_FORMAT( `ws_orders`.`date_create` , '%Y' ) = '".date("Y")."' and  fop_id = ".$this->id." and status  in(8,13)")->at(0)->ctn;
       
        return $order;
    }
    public function summOrder(){
        
       $order =  wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT sum(ws_orders.amount) as ctn FROM ws_orders WHERE DATE_FORMAT( `ws_orders`.`date_create` , '%Y' ) = '".date("Y")."' and  fop_id = ".$this->id." and status  in(8,13)")->at(0)->ctn;
       
        return $order;
    }
}
