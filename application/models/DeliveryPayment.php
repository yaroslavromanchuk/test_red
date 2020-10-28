<?php
class DeliveryPayment extends wsActiveRecord
{
	protected $_table = 'ws_delivery_payments';
	protected $_orderby = array('id'=>'ASC');
	
	protected function _defineRelations()
	{	
		$this->_relations = [
                    'payment' => [
                                                'type'            => 'hasOne', 
                                                'class'            => 'PaymentMethod',
                                                'field'    => 'payment_id',
						],
                    'delivery' => [
                                                'type'            => 'hasOne', 
                                                'class'            => 'DeliveryType',
                                                'field'    => 'delivery_id',
						],
                     'fopname' => [
                                                'type'            => 'hasOne', 
                                                'class'            => 'Fop',
                                                'field'    => 'fop',
						],
                                                    ];
	}
        /**
         * Фоп оплаты
         * @param type $dely
         * @param type $pay
         * @return int
         */
        public static function getFop($dely, $pay){
            if($dely and $pay){
                $p = wsActiveRecord::useStatic('DeliveryPayment')->findFirst(['delivery_id' => $dely, 'payment_id' => $pay])->fop;
                if($p){
                    return $p;
                }
                 
            }
            return 1;
        }
        /**
         * стоимость доставки
         * @param type $dely
         * @param type $pay
         * @return int
         */
        public static function getPriceDelivery($dely, $pay){
            if($dely and $pay){
                $p = wsActiveRecord::useStatic('DeliveryPayment')->findFirst(['delivery_id' => $dely, 'payment_id' => $pay])->price;
                if($p){
                    return $p;
                }
                 
            }
            return 0;
        }

}
