<?php
class OrdersPay extends wsActiveRecord
{
    protected $_table = 'orders_pays';
    protected $_orderby = array('ctime' => 'DESC');

    protected function _defineRelations()
    {
        $this->_relations = array(
            'customer' => array(
                'type' => 'hasOne',
                'class' => 'Customer',
                'field' => 'customer_id'),
            'admin' => array(
                'type' => 'hasOne',
                'class' => 'Customer',
                'field' => 'admin_id'),
        );
    }

    public function getOrdersToPay()
    {
        return wsActiveRecord::useStatic('Shoporders')->findAll(array('id in(' . $this->getOrders() . ')'));
    }

    static function newOrderPay($admin, $customer, $sum, $orders, $deposit_plus = 0)
    {
	 $order = new Shoporders($orders);

       // $mas = explode(',', $orders);
       // $ords= array();
       // $deposit_order = 0;
        //$user = new Customer($customer);
     //   foreach($mas as $m){
      //      $ord = new Shoporders((int)$m);
      //      $ords[] = $ord;
       //     $deposit_order = $deposit_order + (float)$ord->getPriceWithSkidka() + (float)$ord->getDeliveryCost() - $ord->getDeposit();
     //   }


       // $deposit_order =  (float)$sum - (float)$deposit_order + (float)$deposit_plus;

       // if($deposit_order < 0 ) die('Error sum');
		
        $pay = new OrdersPay();
        $pay->setAdminId($admin);
        $pay->setCustomerId($customer);
        $pay->setSum($sum);
        $pay->setOrdes($orders);
       // $pay->setToDeposit($deposit_order);
        //$user->setDeposit($user->getDeposit()+$deposit_order);//Yarik
        $pay->save();
      //  $user->save();
       // foreach ($ords as $order) {
            $order->setAdminPayId($pay->getId());
            $order->setAdminPayTime($pay->getCtime());
            //$order->calculateOrderPrice(true, false);
            $order->save();
        //}
        return true;
    }
}

