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
    /**
     * Создание записи о оплате и добавление в заказ номера оплаты и даты
     * @param type $admin
     * @param type $customer
     * @param type $sum
     * @param type $orders
     * @param type $deposit_plus
     * @return boolean
     */
    static function newOrderPay($admin, $customer, $sum, $order_id, $deposit_plus = 0)
    {
	 
         
        $pay = new OrdersPay();
        $pay->setAdminId($admin);
        $pay->setCustomerId($customer);
        $pay->setSum($sum);
        $pay->setOrdes($order_id);
        $pay->save();
        
        $order = new Shoporders($order_id);
        /*зачисление бонуса при смене на полачен, если ранее не был зачислен*/
        if($order->date_create > '2020-03-01 00:00:00' and $order->shop_id == 1){
          if($order->bonus_flag == 0){ 
              if(self::OrderCoinAdd($order)){
                  $order->setBonusFlag(1);
              }
          }
    }
           /*зачисление бонуса при смене на полачен, если ранее не был зачислен*/
            $order->setAdminPayId($pay->getId());
            $order->setAdminPayTime($pay->getCtime());
            $order->save();

        return true;
    }
    /**
     * Зачисление бонуса на счет клиента
     * @param type $order
     * @return boolean
     */
    static function OrderCoinAdd($order){
            $summ = $order->getOrderAmountMinusCoin();
           /* foreach ($order->articles as $art){
                if($art->old_price == 0 and !$art->skidka_block){ $summ+=$art->price; }
            }
            if($summ > ($order->amount + $order->deposit)){
                $summ = $order->amount + $order->deposit;
            }*/
            if($summ > 0){
                 //$s = ceil(($order->skidka+$order->event_skidka)/100*$summ);   
                
               // if($order->skidka > 0){
                 //   $s = ceil($order->skidka/100*$summ);  
              //  }else{
                //    $s = ceil(0.05*$summ);  
               // }
                  //  if($s > 0){
                       $coin = new RedCoin();
$coin->import(['coin' => $summ, 'customer_id' => $order->customer_id, 'status'=>1, 'order_id_add' => $order->id,  'date_add' => date("Y-m-d"), 'date_active' => date("Y-m-d", strtotime("now +14 days")), 'date_off' => date("Y-m-d", strtotime("now +194 days"))]);
$coin->save();
BonusHistory::add($order->customer_id, 'Зачислено', $summ, $order->id);
return true; 
                  //  }

            }
            return false;
    }
}

