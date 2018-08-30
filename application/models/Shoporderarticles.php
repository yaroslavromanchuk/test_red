<?php
    class Shoporderarticles extends wsActiveRecord
    {
        protected $_table = 'ws_order_articles';
        protected $_orderby = array('id' => 'ASC');

        protected function _defineRelations()
        {
            $this->_relations = array(
			'article_db' => array(
                'type' => 'hasOne',
                'class' => self::$_shop_articles_class,
                'field' => 'article_id'),
           'order' => array(
                    'type' => 'hasOne',
                    'class' => self::$_shop_orders_class,
                    'field' => 'order_id'),
		'sizes' => array(
                                    'type'=>'hasOne',
                                    'class'=>'Size',
                                    'field'=>'size'), 
        'colors' => array(
                                     'type'=>'hasOne',
                                     'class'=>'Shoparticlescolor',
                                     'field'=>'color'), 
					 
					);
        }

        public function getImagePath($type = 1)
        {
            return $this->getArticleDb()->getImagePath($type);
        }

        public function getProcent($all_orders_amount)
        {
            $user = wsActiveRecord::useStatic('Customer')->findFirst(array('id' => $this->order->getCustomerId()));
            if (@$user->getSkidka() != 0) {
                return $user->getSkidka() . '%';
            } else {
                if ($all_orders_amount <= 700) {
                    return '0,00%';
                } elseif ($all_orders_amount > 700 && $all_orders_amount <= 5000) { //5%
                    return '5,00%';
                } elseif ($all_orders_amount > 5000 && $all_orders_amount <= 12000) { //10%
                    return '10,00%';
                } elseif ($all_orders_amount > 12000) { //15%
                    return '15,00%';
                }
            }
        }

		
		public function getPerc($all_orders_amount, $sum_order = 0)
			{
			 $s = 0;
			$mas = array();
				$minus = 0.00;
				$price = $this->getPrice() * $this->getCount();
			
		 if($this->getOptionId() and $this->getOptionId() == 7){
		  $mas['minus'] = $price - $this->getOptionPrice()*$this->getCount();
		$mas['price'] = $this->getOptionPrice();
		$mas['comment'] = '-27% ко Дню независимости Украины';
		 return $mas;
		 }
		 
	if (!$this->getSkidkaBlock()) {
	 if($this->getOptionId()){ // акция 1+1=3
	 if($this->getOptionId() == 5){
	 $s+=15;
	 $mas['comment'] = '+15% на H&M';
	// break;
	 }else{
	  $mas['minus'] = $price - $this->getOptionPrice()*$this->getCount();
		$mas['price'] = $this->getOptionPrice();
		switch($this->getOptionId()){
		case 6: $mas['comment'] = 'Акция -20% на не уцененный товар<br>21-22.08.'; break;
		case 4: $mas['comment'] = 'Акция часы в подарок<br>сумма аксессуаров > 1000 грн.'; break;
		case 7: $mas['comment'] = '27% ко Дню независимости Украины'; break;
		default: $mas['comment'] = 'Цена по акции.'; break;
		}
			return $mas;
			}
	 }
	
		//$today = date("Y-m-d H:i:s");
			$kod = false;
			if(@$this->order->getKupon()){				
$kod = wsActiveRecord::useStatic('Other')->findFirst(array("cod"=>$this->order->getKupon()));



	if(@$kod->category_id){
	if($kod->category_id != $this->article_db->category_id) {
	$kod = false;
	}
	
	}
if($this->order->getSumOrder() < $kod->min_sum) {
$kod = false;
}
}

	
	 $skidka  = 0;
	 
		$skidka = $this->order->getSkidka();
		
	 //d($this->order, false);
	
	 
	 $event_skidka = $this->getEventSkidka();
	if ($event_skidka != 0) {

	$s += (int)$event_skidka;
	if($s == 50){
	$minus = (($price / 100) * $s);
    $price -= $minus;
	 $mas['price'] = $price;
     $mas['minus'] = $minus;
	 $mas['comment'] = 'На этот товар действует скидка по акции -50% НА ШЛЕПКИ';
        return $mas;
	}
	
	}
	 
			//d($skidka, false);

                if ((int)$this->getOldPrice() == 0) {
                    if ($skidka != 0) {
                        $minus = (($price / 100) * ($skidka+$s));
                        $price -= $minus;
						if(@$kod and $kod->new_cust_plus == 1){
						$m = (($price / 100) * $kod->skidka);
						$minus += $m;
						$price -= $m;
						//kupon
									}
                    }else{
                        if ($all_orders_amount <= 700) {
                            $minus = (($price / 100) * $s);
                            $price -= $minus;
                        } elseif ($all_orders_amount > 700 && $all_orders_amount <= 5000) { //5%
                             $minus = (($price / 100) * (5+$s));
                            $price -= $minus;
                        } elseif ($all_orders_amount > 5000 && $all_orders_amount <= 12000) { //10%
                           $minus = (($price / 100) * (10+$s));
                            $price -= $minus;
                        } elseif ($all_orders_amount > 12000) { //15%
                            $minus = (($price / 100) * (15+$s));
                            $price -= $minus;
                        }
						
					if(@$kod and $kod->new_sum_plus == 1){
						$m = (($price / 100) * $kod->skidka);
						$minus += $m;
						$price -= $m;
						//kupon
										}
                    }
					
                }else{
				
				$minus += ($this->getOldPrice() - $this->getPrice());
				$m = (($price / 100) * $s);
					$minus += $m;
					$price -= $m;
				//$price -= $m;
			//kupon
			if(@$kod and $kod->ucenka == 1){
						$m = (($price / 100) * $kod->skidka);
						$minus += $m;
						$price -= $m;
						//kupon
						}
		}
		
		//скидка к окремому  товару в заказе
						
			if(@$kod and $kod->all == 1){
			$m = (($price / 100) * $kod->skidka);
						$minus += $m;
						$price -= $m;
						//kupon
							}

        }
        $mas['price'] = $price;
        $mas['minus'] = $minus;
		
		
        return $mas;
    }
	

        public function  getCode()
        {
            $item = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $this->getArticleId(), 'id_size' => $this->getSize(), 'id_color' => $this->getColor()));
            if ($item) {
                return $item->getCode();
            }
            return '';
        }

        public function  getCountNow()
        {
            $item = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $this->getArticleId(), 'id_size' => $this->getSize(), 'id_color' => $this->getColor()));
            if ($item) {
                return $item->getCount();
            }
            return '';
        }
    }

?>