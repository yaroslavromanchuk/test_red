<?php
class Customer extends wsCustomer
{


	public function getDiscont($current_order_id = false, $plus = 0, $no_a_discint = false) {
		//показывать вообще по всем ордерам или без учета какого-то
      /*  if ($current_order_id) {
            $order = new Shoporders($current_order_id);
            $a_discont = $order->getEventSkidka();
        }else{
          $a_discont = 0;// EventCustomer::getEventsDiscont($this->getId());
        }*/
       // if($no_a_discint) {
            $a_discont = 0;
       // }

        if ($this->getSkidka()!=0) {
            return $this->getSkidka()+$a_discont;
        }else {
			if ($current_order_id && $this->getId()){
				$amount = wsActiveRecord::useStatic('Customer')->findByQuery('
				SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
						FROM ws_order_articles
						JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
						WHERE ws_orders.customer_id = ' . $this->getId() . ' AND ws_orders.status IN (0,1,3,4,6,8,9,10,11,13,14,15,16) AND ws_orders.id <=' . $current_order_id)->at(0)->getAmount();



			}elseif ($this->getId() and !$current_order_id) {

				$amount = wsActiveRecord::useStatic('Customer')->findByQuery('
				SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
						FROM ws_order_articles
						JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
				WHERE ws_orders.customer_id = ' . $this->getId() . ' AND ws_orders.status IN (0,1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0)->getAmount();

			}else{
				$amount = 0;
			}
				$amount += $plus;

			if ($amount <= 700){
				return 0+$a_discont;
			}elseif($amount > 700 && $amount <= 5000 ){//5%
				return 5+$a_discont;
			}elseif($amount > 5000 && $amount <= 12000){//10%
				return 10+$a_discont;
			}elseif($amount > 12000){//15%
				return 15+$a_discont;
			}
        }
	}
    public function getNextDiscont($plus=0){
        $now = $this->getDiscont(false,$plus,true);
        if($now == '0') return 5;
        if($now == '5') return 10;
        if($now == '10') return 15;
        if($now == '15') return 0;
    }
     public function getNextDiscontSum($plus=0){

		$a = wsActiveRecord::useStatic('Customer')->findByQuery('
		SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
		WHERE ws_orders.customer_id = ' . $this->getId() . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0);
		$amount = $a ? $a->getAmount() : 0;
		$amount = $amount +$plus;
		$next = $this->getNextDiscont($plus);
		if($next==5) return 700 - $amount;
		if($next==10) return 5000 - $amount;
		if($next==15) return 12000 - $amount;
		
    }

	public function getDiscontPrice($price = 0){
		$discont = $this->getDiscont();
		$new_price = 0;
		if ($discont == 0){
			$new_price = $price;
		}else{
			$new_price = $price - ($price / 100 * $discont);
		}

		return number_format((double)$new_price, 2, ',', '');
	}
    public function isUserTerms(){
        $order = wsActiveRecord::useStatic('Shoporders')->findFirst(array('customer_id'=>$this->getId(),'oznak'=>1,'soglas'=>1), array('id'=>'ASC'));
        if($order) return $order->getDateCreate();
        return false;
    }
	//проверка на бан пользователя
    public function isBan(){
        if($this->getCustomerStatusId() == 2) return true;
        return false;
    }
		//проверка на блок емейла
    public function isBlockEmail(){
        if($this->getBlockEmail() == 1) return true;
        return false;
    }
			//проверка на закрытия пуш уведомлений
    public function isClosePuch(){
        if($this->getClosePuch() == 1) return true;
        return false;
    }
	//проверка пользователя на бан заказывать наложкой нп
    public function isBlockNpN(){
        if($this->getBloсkNpN() == 1) return true;
        return false;
    }
	// временна зона 1 - год не заходили, 2 - б 3 - 
	public function isTimeZone(){
	 if($this->getTimeZoneId() == 1) return true;
        return false;
	}
	public function isNoActive(){
      
        if($this->getCurrencyId() == 1) return true;
        return false;
    } 
    public function isNoPayOrder(){
        if($this->getIsLoggedIn()){
        $order = wsActiveRecord::useStatic('Shoporders')->findFirst(array('customer_id'=>$this->getId(),'status'=>11,'delivery_type_id'=>8));
        if($order) return false;
        }
        return false;

    }
	//проверка на блок пользователя на заказ курьером
   public function isBlockCur(){ 
        if($this->getBlockCur() == 1) return true;
        return false;
    }
	//проверка на блок пользователя на заказ с магазина
    public function isBlockM(){
        if($this->getBlockM() == 1) return true;
        return false;
    }
	//проверка на блок пользователя на быструю заявку
    public function isBlockQuick(){
        if($this->getBlockQuick() == 1) return true;
        return false;
    }

	
    // public function countOrder(){
		// $co = wsActiveRecord::findByQueryFirstArray('SELECT COUNT(id) as c FROM `ws_orders` WHERE customer_id='.$this->getId());
		// return $co['c'];
    // }
}
?>