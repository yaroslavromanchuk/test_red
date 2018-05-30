<?php
class wsSellaction extends wsActiveRecord
{
    protected $_table = 'ws_order_sellaction';
    protected $_orderby = array('id' => 'ASC');


	public static function add($order, $summ)
	{
		$logger = new wsSellaction();
		$logger->setOrderId($order);
		$logger->setOrderSumm($summ);
		$logger->setCtime(date('Y-m-d H:i:s'));
		$logger->save();
	}
}
?>