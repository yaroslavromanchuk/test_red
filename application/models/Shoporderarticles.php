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
		'option' => array(
                    'type' => 'hasOne',
                    'class' => 'Shoparticlesoption',
                    'field' => 'option_id'),
					 
					);
        }
        /**
         * Ссылка на картинку товара в заказе
         * @param type $type
         * @return type
         */
        public function getImagePath($type = 1)
        {
            return $this->getArticleDb()->getImagePath($type);
        }
        /**
         * Кешбек по конкретному товару
         * @return int
         */
        public function getCashback(){
            if((!$this->skidka_block && !$this->option_id && $this->count > 0)) {
                  $sk = $this->order->skidka;
                  if($cashback = $this->article_db->getCashback()){
                      $sk = $cashback;
                  }
                  return ceil($sk/100*(($this->price-$this->coin)*$this->count));
              }
            return 0;
        }

        public function getProcent($all_orders_amount)
        {
            $user = wsActiveRecord::useStatic('Customer')->findFirst(array('id' => $this->order->getCustomerId()));
            if ($user->getSkidka() != 0) {
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

		/**
                 * Прощет стоимости товара с учетом акций
                 * @param type $all_orders_amount - сумма всех заказов
                 * @param type $sum_order - сумма текущего заказа
                 * @return type
                 */
	public function getPerc1($all_orders_amount, $sum_order = 0)
            {
            /*
             * $s - дополнительная скидка на товар
             */
            $s = 0;
            
            /*
             * $mas - массив для возвращения данных
             */
            $mas = [];
            
            /*
             * сумма скидки на товар
             */
		$minus = 0.00;
            /*
             * стоимость товара в заказе
             */                   
		$price = $this->getPrice() * $this->getCount();
		
                //$event_skidka = $this->getEventSkidka();
         /*
          * проверяем наличие доп. скидки на это товар
          * если больше 0 - к $s добавляем доп. скидку 
          */
	if ($this->getEventSkidka() != 0) 
            {
             $s += (int)$this->getEventSkidka();
            }
            
            	$kod = false;
    if($this->order->getKupon()){				
        $kod = wsActiveRecord::useStatic('Other')->findFirst(["cod"=>$this->order->getKupon()]);
	if(($kod->category_id and $kod->category_id != $this->article_db->category_id) or ($this->order->getSumOrder() < $kod->min_sum))
            { 
            $kod = false; 
            }
}    
        /*
         * проверка на участвие товара в акциях
         */
	if($this->getOptionId())
            { 
                     /*
                      * если $this->getOptionPrice() > 0 - возвращаем сумму скидки и фиксировану цену товра
                      * @return $mas
                      * выходим с функции 
                      * 
                      * если $this->getOptionPrice() == 0 - добавляем к $s дополнительную скидку и идем дальше по функции
                      */
                     if($this->getOptionPrice()  > 0){
                       
                        $mas['price'] = ceil($this->getOptionPrice()*$this->getCount());
                        
                        if($this->order->getKupon()){
                        
                             $kod = wsActiveRecord::useStatic('Other')->findFirst(array("cod"=>$this->order->getKupon()));
                             if($kod and $kod->all == 1){
                                 $mas['price'] = ceil($mas['price'] *(1-$kod->skidka/100));
					}
                         }
                        if($s > 0){
                               $mas['price'] = ceil($mas['price']*(1-$s/100));
                        }   
                        $mas['minus'] = FLOOR($price - $mas['price']);
                       
			return $mas;
                     }else{
                         
                        $e =  wsActiveRecord::useStatic('Shoparticlesoption')->findById($this->getOptionId());

                        $s += $e->value;
                     }
            }
	/*
         * проверяем стоит ли блок скидок на этом товаре
         * усли нет - заходим в условие для добавления доп. скидок
         * если стоит блок return $mas
         */	 
	if (/*!$this->getSkidkaBlock()*/true) {
	 
	
		//$today = date("Y-m-d H:i:s");


	
	
	 /*
          * доп. скидка на весь заказ
          */
	$skidka = $this->order->getSkidka();
		
	 //d($this->order, false);
	
	 
	 
	 
			//d($skidka, false);
                /*
                 * проверяем товар на уценку,
                 * если $this->getOldPrice() == 0 - значит товар не уценялся и на него действуют доп. скидки и аклиентские и скидки по промокоду
                 * усли $this->getOldPrice()  > 0 - значит товар попал в уценку и на него не действуют клиентские скидки, действуют только доп. скидки и скидки по промокоду
                 */
                if ((int)$this->getOldPrice() == 0) {
                    if ($skidka != 0) {
                        $minus = FLOOR((($price / 100) * ($skidka+$s)));
                        $price -= $minus;
                            if($kod and $kod->new_cust_plus == 1){ // 
				$m = FLOOR((($price / 100) * $kod->skidka));
				$minus += $m;
				$price -= $m;
                            }
                    }else{
                        if ($all_orders_amount <= 700) {
                            $minus = FLOOR((($price / 100) * $s));
                            $price -= $minus;
                        } elseif ($all_orders_amount > 700 && $all_orders_amount <= 5000) { //5%
                             $minus = FLOOR(($price / 100) * (5+$s));
                            $price -= $minus;
                        } elseif ($all_orders_amount > 5000 && $all_orders_amount <= 12000) { //10%
                           $minus = FLOOR(($price / 100) * (10+$s));
                            $price -= $minus;
                        } elseif ($all_orders_amount > 12000) { //15%
                            $minus = FLOOR(($price / 100) * (15+$s));
                            $price -= $minus;
                        }
						
					if($kod and $kod->new_sum_plus == 1){
						$m = FLOOR(($price / 100) * $kod->skidka);
						$minus += $m;
						$price -= $m;
						//kupon
										}
                    }
					
                }else{
                    
                    if($this->getOldPrice() and $this->ucenka and $this->order->skidka > $this->ucenka){
                       // $price = $this->getOldPrice() * $this->getCount();
                        $price = $this->getOldPrice() * (1-$this->order->skidka/100) * $this->getCount();
                        $minus = FLOOR(($this->getOldPrice()-$price));
			
                    }else{
			
                    $minus = FLOOR($this->getOldPrice() - $this->getPrice());
                    }
				
			//$minus += ($this->getOldPrice() - $this->getPrice());
                    if($s > 0){
			//$m = FLOOR(($price / 100) * $s);
                        $price = $price * (1-$s/100);
			$minus = ($this->getOldPrice() - $price);
			//$price -= $m;
                }
				//$price -= $m;
			//kupon
			if($kod and $kod->ucenka == 1){
						$m = FLOOR(($price / 100) * $kod->skidka);
						$minus += $m;
						$price -= $m;
						//kupon
						}
		}
		
		//скидка к окремому  товару в заказе
						
			if($kod and $kod->all == 1){
			$m = FLOOR(($price / 100) * $kod->skidka);
						$minus += $m;
						$price -= $m;
						//kupon
							}

        }
        
        
        $mas['price'] = ceil($price);
        $mas['minus'] = FLOOR($minus);
		
		
        return $mas;
    }
    /**
     * Цена товара * количество
     * @param type $all_orders_amount
     * @param type $sum_order
     * @return string
     */
    public function getPerc($all_orders_amount = 0)
            {
        /*
             * $mas - массив для возвращения данных
             */
            $mas = [];
            
            if($this->getOptionPrice()  > 0 and $this->option_id){      
                        $mas['price'] = ceil($this->getOptionPrice()*$this->count);// $this->getOptionPrice()*$this->count;
                        
                        $mas['coin'] = $this->coin;
                        $mas['minus'] = FLOOR($this->getPrice()*$this->count - $mas['price']);
			return $mas;
                     }else{ //if($this->order->date_create > '2020-03-01 00:00:00')
        $price = $this->order->kupon_price?ceil($this->price*(1-$this->order->kupon_price/100)):$this->price;
        $minus = ($this->old_price > 0)?($this->old_price-$price):0;
        $comment = '';
        return ['price'=>($price*$this->count), 'minus'=>$minus, 'coin'=>$this->coin, 'comment'=> $comment]; 
     }
            /*
             * $s - дополнительная скидка на товар
             */
            $s = 0;
            $coment = '';
            /*
             * сумма скидки на товар
             */
		$minus = 0.00;
            /*
             * стоимость товара в заказе
             */                   
		$price = $this->getPrice();
		
               if($this->article_db->brand_id == 1504){
                   if($this->order->getSkidka() > 0){
            $price = ceil($this->getPrice() *(1-$this->order->getSkidka()/100));
            $minus = FLOOR($this->getPrice() - $price);
                }else{
                    $price = $this->getPrice();
                    $minus = 0;
                }
                
        $mas['price'] = ceil($price*$this->count);
        $mas['minus'] = FLOOR($minus*$this->count);
        //$mas['comment'] = $coment;	
        return $mas;
               }
         /*
          * проверяем наличие доп. скидки на это товар
          * если больше 0 - к $s добавляем доп. скидку 
          */
	if ($this->getEventSkidka() != 0) 
            {
             $s += (int)$this->getEventSkidka();
             $coment.='<div class="alert alert-info" style="padding: 5px;margin-top: 10px;font-size: 10px;">- '.$s.'% дополнительная скидка</div>';
            }
            
            	$kod = false;
    if($this->order->getKupon()){				
        $kod = wsActiveRecord::useStatic('Other')->findFirst(["cod"=>$this->order->getKupon()]);
	if(($kod->category_id and $kod->category_id != $this->article_db->category_id) or ($this->order->getSumOrder() < $kod->min_sum))
            { 
            $kod = false; 
            }
            }    
        /*
         * проверка на участвие товара в акциях
         */
	if($this->getOptionId())
            { 
                     /*
                      * если $this->getOptionPrice() > 0 - возвращаем сумму скидки и фиксировану цену товра
                      * @return $mas
                      * выходим с функции 
                      * 
                      * если $this->getOptionPrice() == 0 - добавляем к $s дополнительную скидку и идем дальше по функции
                      */
                     if($this->getOptionPrice()  > 0){
                          $e =  wsActiveRecord::useStatic('Shoparticlesoption')->findById($this->getOptionId());
                       if($s > 0){
                             $s+=$e->value;//  $mas['price'] = ceil($mas['price']*(1-$s/100));
                        }else{
                            $s=$e->value;
                        }
                         if($kod and $kod->all == 1){
                             $s+=$kod->skidka;
				}
                                
                        $mas['price'] = ceil($this->getPrice() *(1-$s/100))*$this->count;;// $this->getOptionPrice()*$this->count;
                        
                          
                        $mas['minus'] = FLOOR($this->getPrice()*$this->count - $mas['price']);
			return $mas;
                     }else{
                         
                        $e =  wsActiveRecord::useStatic('Shoparticlesoption')->findById($this->getOptionId());

                        $s += $e->value;
                         $coment .= '<div class="alert alert-success" style="padding: 5px;margin-top: 10px;font-size: 10px;">'.$e->option_text.'</div>';
                     }
            }
	/*
         * проверяем стоит ли блок скидок на этом товаре
         * усли нет - заходим в условие для добавления доп. скидок
         * если стоит блок return $mas
         */	 
	if (/*!$this->getSkidkaBlock()*/true) {
	 /*
          * доп. скидка на весь заказ
          */
	$skidka = $this->order->getSkidka();
                /*
                 * проверяем товар на уценку,
                 * если $this->getOldPrice() == 0 - значит товар не уценялся и на него действуют доп. скидки и аклиентские и скидки по промокоду
                 * усли $this->getOldPrice()  > 0 - значит товар попал в уценку и на него не действуют клиентские скидки, действуют только доп. скидки и скидки по промокоду
                 */
                if ((int)$this->getOldPrice() == 0) {
                    if ($skidka != 0) {
                        $s+=$skidka;
                            if($kod and $kod->new_cust_plus == 1){ // 
				$s+=$kod->skidka;
                                $coment .= '<div class="alert alert-info" style="padding: 5px;margin-top: 10px;">На этот товар действует скидка '.$kod->skidka.'% по промокоду.</div>';
                            }
                    }else{
                          if ($all_orders_amount > 700 && $all_orders_amount <= 5000) { //5%
                                $s+=5;
                        } elseif ($all_orders_amount > 5000 && $all_orders_amount <= 12000) { //10%
                            $s+=10;
                        } elseif ($all_orders_amount > 12000) { //15%
                            $s+=15;
                        }			
			if($kod and $kod->new_sum_plus == 1){
			$s+=$kod->skidka;
			$coment .= '<div class="alert alert-info" style="padding: 5px;margin-top: 10px;">На этот товар действует скидка '.$kod->skidka.'% по промокоду.</div>';
			}
                    }		
                }else{
                    if($this->getOldPrice() and $this->ucenka and $this->order->skidka > $this->ucenka){
			 $s+=ceil($this->order->skidka-$this->ucenka);
                    }
			if($kod and $kod->ucenka == 1){
                            $s+=$kod->skidka;
			}
		}
		//скидка к окремому  товару в заказе			
			if($kod and $kod->all == 1){
			 $s+=$kod->skidka;
			}
        }
         if($s > 0){
            $price = ceil($this->getPrice() *(1-$s/100));
            $minus = FLOOR($this->getPrice() - $price);
                }else{
                    $price = $this->getPrice();
                    $minus = 0;
                }
                
        $mas['price'] = ceil($price*$this->count);
        $mas['minus'] = FLOOR($minus*$this->count);
        $mas['comment'] = $coment;
		
		
        return $mas;
    }
	
        /**
         * Получить артикул товара в заказе
         * @return string
         */
        public function  getCode()
        {
            if($this->getArtikul()){ return $this->getArtikul(); }
            
            $item = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $this->getArticleId(), 'id_size' => $this->getSize(), 'id_color' => $this->getColor()));
            if ($item) {
                return $item->getCode();
            }
            return '';
        }
        /**
         * Остаток товара на складе
         * @return string
         */
        public function  getCountNow()
        {
            $item = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $this->getArticleId(), 'id_size' => $this->getSize(), 'id_color' => $this->getColor()));
            if ($item) {
                return $item->getCount();
            }
            return '';
        }
	
        /**
         * @return false - товар не участвует в акции
         * или
         * @return параметры акции в которой учавствует товар
         */
	public function getOptions()
	{
	$dat = date('Y-m-d');
       // $dat = date('Y-m-d', strtotime("-1 days"));
	$sql ="SELECT  `ws_articles_option`. * 
FROM  `ws_articles_option` 
JOIN  `ws_articles_options` ON  `ws_articles_option`.`id` =  `ws_articles_options`.`option_id` 
WHERE  `ws_articles_option`.`status` = 1
AND  `start` <=  '$dat'
AND  `end` >=  '$dat'
AND (
 `ws_articles_options`.`article_id` = ".$this->article_db->id."
OR  `ws_articles_options`.`category_id` = ".$this->article_db->category_id."
OR  `ws_articles_options`.`brand_id` = ".$this->article_db->brand_id."
OR  `ws_articles_options`.`sezon_id` = ".$this->article_db->sezon."
)";
	$option = wsActiveRecord::findByQueryArray($sql);
	if (count($option)){
		return $option;
	}
	return false;
        }
        /**
         * Заказы конкретного размера
         * @return type
         */
        public function getOrders(){
            
            $sql="SELECT *"
                    . " FROM ws_order_articles"
                    . " WHERE article_id = ".$this->article_id
                    . " and size = ".$this->size
                    . " and color = ".$this->color
                    . " GROUP BY  `order_id` "
                    . " ORDER BY  `id` DESC ";
             return  wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($sql);
        }
    


}
