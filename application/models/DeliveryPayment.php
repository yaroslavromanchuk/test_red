<?php
class DeliveryPayment extends wsActiveRecord
{
	protected $_table = 'ws_delivery_payments';
	protected $_orderby = array('id'=>'ASC');
	
	protected function _defineRelations()
	{	
		$this->_relations = array(	'payment' => array(
                                                'type'            => 'hasOne', 
                                                'class'            => 'PaymentMethod',
                                                'field'    => 'payment_id',
													
													),
                                                    );
	}
        public static function getFop($dely, $pay){
            if($dely and $pay){
                $p = wsActiveRecord::useStatic('DeliveryPayment')->findFirst(['delivery_id' => $dely, 'payment_id' => $pay])->fop;
                if($p){
                    return $p;
                }
                 
            }
            return 1;
        }

}
