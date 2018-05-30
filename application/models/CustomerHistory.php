<?php
class CustomerHistory extends wsActiveRecord
{
    protected $_table = 'customer_history';
    protected $_orderby = array('id' => 'DESC');

    protected function _defineRelations()
    {
        $this->_relations = array(
            'admin' => array(
                'type' => 'hasOne',
                'class' => 'Customer',
                'field' => 'customer_id'),
        );
    }

    static function newCustomerHistory($admin, $customer, $action, $info)
    {
	$date = date('Y-m-d H:i:s');
	$sql = "INSERT INTO `customer_history`(`ctime`,`admin_id`, `customer_id`, `action`, `info`) VALUES ('$date',".$admin.", ".$customer.", '$action', '$info')";
        wsActiveRecord::query($sql);
		/*
        $history = new DepositHistory();
		$history->setAdminId($admin);
        $history->setCustomerId($customer);
        $history->setAction($action);
        $history->setInfo($sum);
		$history->setOrders($order);
        $history->save();
		*/
        return true;
    }

}

?>