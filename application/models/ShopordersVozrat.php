<?php
class ShopordersVozrat extends wsActiveRecord
{
    protected $_table = 'ws_orders_vozrat';
    protected $_orderby = array('id' => 'ASC');

    protected function _defineRelations()
    {
        $this->_relations = array(
		'articles' => array(
            'type' => 'hasMany',
            'class' => 'ShoporderarticlesVozrat',
            'field_foreign' => 'order_id',
			'orderby' => array('id' => 'ASC'),
            'onDelete' => 'delete'),
        'payment_method' => array(
                'type' => 'hasOne',
                'class' => 'PaymentMethod',
                'field' => 'payment_method_id'),
        'delivery_type' => array(
                'type' => 'hasOne',
                'class' => 'DeliveryType',
                'field' => 'delivery_type_id'),
        );
    }
    public function getVSum(){
        $sum  = 0;
        foreach($this->articles as $a){
            $sum +=$a->getPrice()*$a->getCount();
        }
        return $sum;
    }



}

?>