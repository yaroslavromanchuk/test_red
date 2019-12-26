<?php
class DepositHistory extends wsActiveRecord
{
    protected $_table = 'deposit_history';
    protected $_orderby = array('id' => 'DESC');
    
    /**
     * Запись в историю депозита
     * 
     * @param type $admin - кто id
     * @param type $customer - кому id
     * @param type $action - действие (+ -)
     * @param type $sum - сумма депозита
     * @param type $order - по какому заказу
     * @return boolean
     */
    static function newDepositHistory($admin, $customer, $action, $sum, $order = 0)
    {
        $history = new DepositHistory();
	$history->setAdminId($admin);
        $history->setCustomerId($customer);
        $history->setAction($action);
        $history->setInfo($sum);
	$history->setOrders($order);
        $history->save();
		
        return true;
    }

}
