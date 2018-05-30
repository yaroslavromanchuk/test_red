<?php
class BonusHistory extends wsActiveRecord
{
    protected $_table = 'bonus_history';
    protected $_orderby = array('id' => 'DESC');
	
    static function add($admin, $customer, $action, $sum, $order)
    {
        $history = new BonusHistory();
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