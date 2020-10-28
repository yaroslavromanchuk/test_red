<?php
class RedCoin extends wsActiveRecord
{
	protected $_table = 'ws_red_coin';
	protected $_orderby = array('id' => 'ASC');
	
    protected function _defineRelations()
    {
        $this->_relations = [
            'customer' => [
                'type' => 'hasOne',
                'class' => self::$_customer_class,
                'field' => 'customer_id'],
            'order_add' => [
                'type' => 'hasOne',
                'class' => self::$_shop_orders_class,
                'field' => 'order_id_add'],
            'order_on' => [
                'type' => 'hasOne',
                'class' => self::$_shop_orders_class,
                'field' => 'order_id_on'],
            'status_name' => [
                'type' => 'hasOne',
                'class' => 'RedCoinStatus',
                'field' => 'status'
            ]
        ];
    }
}