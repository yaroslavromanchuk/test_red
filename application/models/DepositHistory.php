<?php
class DepositHistory extends wsActiveRecord
{
    protected $_table = 'deposit_history';
    protected $_orderby = array('id' => 'DESC');
	
    static function newDepositHistory($admin, $customer, $action, $sum, $order)
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

?>