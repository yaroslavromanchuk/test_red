<?php
class BonusHistory extends wsActiveRecord
{
    protected $_table = 'bonus_history';
    protected $_orderby = array('id' => 'DESC');
	/**
         * 
         * @param type $customer - id клиента
         * @param type $action - действие
         * @param type $sum - сумма бонуса
         * @param type $order - номер заказа
         * @return boolean
         */
    static function add($customer, $action, $sum, $order)
    {
        $history = new BonusHistory();
        $history->setCustomerId($customer);
        $history->setAction($action);
        $history->setInfo($sum);
	$history->setOrders($order);
        $history->save();	
        return true;
    }

}
