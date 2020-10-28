<?php
/**
 * Customer Class
 */
class Customer extends wsCustomer
{

/**
 * скидка клиента
 * @param int $current_order_id
 * @param float $plus
 * @param Boolean $no_a_discint
 * @return int
 */
	public function getDiscont($current_order_id = false, $plus = 0, $no_a_discint = false) {
              $sk1 = 0;
              $sk2 = 0;
        if ($this->getSkidka()!=0) {
            $sk1 =  $this->getSkidka();
        }
	$amount = 0;
        if ($current_order_id && $this->getId()){
				$amount = wsActiveRecord::useStatic('Customer')->findByQuery('
				SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
						FROM ws_order_articles
						JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
						WHERE ws_orders.customer_id = ' . $this->getId() . ' AND ws_orders.status IN (100,1,3,4,6,8,9,10,11,13,14,15,16) AND ws_orders.id <=' . $current_order_id)->at(0)->getAmount();



			}elseif ($this->getId() and !$current_order_id) {

				$amount = wsActiveRecord::useStatic('Customer')->findByQuery('
				SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
						FROM ws_order_articles
						JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
				WHERE ws_orders.customer_id = ' . $this->getId() . ' AND ws_orders.status IN (100,1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0)->getAmount();

			}

			if ($amount <= 700){
				$sk2 =  0;
			}elseif($amount > 700 && $amount <= 5000 ){//5%
				$sk2 =  5;
			}elseif($amount > 5000 && $amount <= 12000){//10%
				$sk2 =  10;
			}elseif($amount > 12000){//15%
				$sk2 =  15;
			}
        
        if($sk2 > $sk1){
            return $sk2;
        }else{
             return $sk1;
        }
        
	}
        
        /**
         * getAllCoin - список редкоинов
         * @param type $type - (add - зачислено, active - Активно, on - Использовано, off - Аннулировано, default = все)
         * @return type
         */
        public function getAllCoin($type = 'active'){
            switch ($type){
            case 'add': return wsActiveRecord::useStatic('RedCoin')->findAll(['coin > 0', 'customer_id'=>$this->id, 'status'=> 1]);// зачисленные бонусы
            case 'active': return wsActiveRecord::useStatic('RedCoin')->findAll(['coin > 0', 'customer_id'=>$this->id, 'status'=> 2]);// активные бонусы
            case 'on': return wsActiveRecord::useStatic('RedCoin')->findAll(['coin_on > 0', 'customer_id'=>$this->id, 'status'=> 3]);//использован бонус
            case 'off': return wsActiveRecord::useStatic('RedCoin')->findAll(['customer_id'=>$this->id, 'status'=> 4]);//просрочен бонус
            case 'all': return wsActiveRecord::useStatic('RedCoin')->findAll(['customer_id'=>$this->id,]);//Все бонусы
            default : return  wsActiveRecord::useStatic('RedCoin')->findAll(['coin > 0', 'customer_id'=>$this->id], ['id'=>'DESC'], [0, 50]);// активные бонусы
            }
        }
        /**
         * getSummCoin - Сумма редкоинов
         * @param type $type - (add - зачислено, active - Активно, on - Использовано, off - Аннулировано, default = active)
         * @return type
         */
        public function getSummCoin($type = 'active'){
            switch ($type){
                case 'add': return wsActiveRecord::useStatic('RedCoin')->findByQuery("SELECT if(SUM(coin)>0, SUM(coin), 0) as `summ` FROM ws_red_coin WHERE `coin` > 0 and status = 1 and customer_id=".$this->id)->at(0)->summ;// Сумма зачисленных бонусов
                case 'active': return wsActiveRecord::useStatic('RedCoin')->findByQuery("SELECT if(SUM(coin)>0, SUM(coin), 0) as `summ` FROM ws_red_coin WHERE `coin` > 0  and status = 2 and customer_id=".$this->id)->at(0)->summ;// Сумма активных бонусов
                case 'on': return wsActiveRecord::useStatic('RedCoin')->findByQuery("SELECT if(SUM(coin_on)>0, SUM(coin_on), 0) as `summ` FROM ws_red_coin WHERE coin_on > 0  and status in(2,3) and customer_id=".$this->id)->at(0)->summ;// Сумма использованых бонусов
                case 'off':  return wsActiveRecord::useStatic('RedCoin')->findByQuery("SELECT if(SUM(coin)>0, SUM(coin), 0) as `summ` FROM ws_red_coin WHERE status = 4  and customer_id=".$this->id)->at(0)->summ;// Сумма просроченых бонусов
                case 'vstup':  return wsActiveRecord::useStatic('RedCoin')->findByQuery("SELECT if(SUM(coin)>0, SUM(coin), 0) as `summ` FROM ws_red_coin WHERE status = 2  and customer_id={$this->id} and `ws_red_coin`.`date_off` = '2020-06-02' AND `date_active` = '2020-03-01'")->at(0)->summ;// Сумма просроченых бонусов   
            default: return wsActiveRecord::useStatic('RedCoin')->findByQuery("SELECT if(SUM(coin)>0, SUM(coin), 0) as `summ` FROM ws_red_coin WHERE `coin` > 0  and status = 2 and customer_id=".$this->id)->at(0)->summ;// Сумма активных бонусов
           }
            }


        /**
        * следующая скидка
        * @param float $plus
        * @return int
        */
    public function getNextDiscont($plus=0){
        $now = $this->getDiscont(false,$plus,true);
        if($now == '0') {return 5;}
        if($now == '5') {return 10;}
        if($now == '10') {return 15;}
        if($now == '15') {return 0;}
    }
    
    /**
     * следующая сумма для скидки
     * @param float $plus
     * @return float
     */
     public function getNextDiscontSum($plus=0){

		$a = wsActiveRecord::useStatic('Customer')->findByQuery('
		SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
		WHERE ws_orders.customer_id = ' . $this->getId() . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0);
		$amount = $a ? $a->getAmount() : 0;
		$amount += $plus;
		$next = $this->getNextDiscont($plus);
		if($next==5) {return 700 - $amount;}
		if($next==10) {return 5000 - $amount;}
		if($next==15) {return 12000 - $amount;}
		
    }

    /**
     * цена по скидке
     * @param float $price
     * @return double
     */
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
        
       /**
        * соглашение
        * @return boolean
        */ 
    public function isUserTerms(){
        $order = wsActiveRecord::useStatic('Shoporders')->findFirst(array('customer_id'=>$this->getId(),'oznak'=>1,'soglas'=>1), array('id'=>'ASC'));
        if($order) {return $order->getDateCreate();}
        return false;
    }
	/**
         * проверка на бан пользователя
         * 2 - true end false
         * @return boolean 
         *          */
    public function isBan(){
        if($this->getCustomerStatusId() == 2) {return true;}
        return false;
    }
    
    /**
     * проверка на блок емейла  
     */
		
    public function isBlockEmail(){
        if($this->getBlockEmail() == 1) {return true;}
        return false;
    }
			//проверка на закрытия пуш уведомлений
    public function isClosePuch(){
        if($this->getClosePuch() == 1) {return true;}
        return false;
    }
	//проверка пользователя на бан заказывать наложкой нп
    public function isBlockNpN(){
        if($this->getBloсkNpN() == 1) {return true;}
        return false;
    }
	// временна зона 1 - год не заходили, 2 - б 3 - 
	public function isTimeZone(){
	 if($this->getTimeZoneId() == 1) {return true;}
        return false;
	}
	public function isNoActive(){
      
        if($this->getCurrencyId() == 1) {return true;}
        return false;
    } 
    public function isNoPayOrder(){
        if($this->getIsLoggedIn()){
        $order = wsActiveRecord::useStatic('Shoporders')->findFirst(array('customer_id'=>$this->getId(),'status'=>11,'delivery_type_id'=>8));
        if($order) {return false;}
        }
        return false;

    }
    public function isBloskOrder(){
        
        if($this->getIsLoggedIn()){
    if($this->id == 1 || $this->id == 40619){ return false; }
            if($this->isAdmin()){
        
        $d = date('Y-m-d H:i:s', strtotime('-30 days'));
        $order = wsActiveRecord::useStatic('Shoporders')->findFirst(['customer_id'=>$this->id, 'status in(100,9,15,16)', 'date_create <="'.$d.'"']);
        if($order->id) { return true; }else{
            return false;
        }
        }else{
            return false;
        }
        }
        return false;
    }

    //проверка на блок пользователя на заказ курьером
   public function isBlockCur(){ 
        if($this->getBlockCur() == 1) {return true;}
        return false;
    }
	//проверка на блок пользователя на заказ с магазина
    public function isBlockM(){
        if($this->getBlockM() == 1) { return true;}
        return false;
    }
    //проверка на блок пользователя на заказ с магазина
    public function isBlockOnline(){
        if($this->getBlockOnline()){
            return true;
        }
        return false;
    }
	//проверка на блок пользователя на быструю заявку
    public function isBlockQuick(){
        if($this->getBlockQuick() == 1){ return true; }
        return false;
    }
    //проверка на блок пользователя на быструю заявку
    public function isBlockJustin(){
        if($this->bloсk_justin == 1){ return true; }
        return false;
    }

	
    public function getCountAllOrder()
            {
		 $co = wsActiveRecord::findByQueryFirstArray('SELECT COUNT(id) as c FROM `ws_orders` WHERE customer_id='.$this->getId().' and status !=17 ');
		return $co['c'];
            }
    public function getCountAllArticlesOrder()
            {
		$co = wsActiveRecord::findByQueryFirstArray('
                    SELECT SUM( IF(  `ws_order_articles`.`count` >0,  `ws_order_articles`.`count` , 1 ) ) AS suma
                    FROM  `ws_order_articles` 
                    JOIN  `ws_orders` ON  `ws_order_articles`.`order_id` =  `ws_orders`.`id` 
                    WHERE  `ws_orders`.`customer_id` ='.$this->getId());
                
		return $co['suma'];
            }
    /**
     * 
     * @return int - количество фактических заказов текущего клиента
     */
    public function getCountFactOrder(){
		 $co = wsActiveRecord::findByQueryFirstArray('SELECT COUNT(id) as c FROM `ws_orders` WHERE customer_id='.$this->getId().' and status not in(17,7,2)');
		return $co['c'];
    }
    /**
     * 
     * @return int  - количество фактических товаров в заказаз тепкущего клиента
     */
	public function getCountFactArticlesOrder()
                {
		 $co = wsActiveRecord::findByQueryFirstArray('
                    SELECT SUM(`ws_order_articles`.`count`) AS suma
                    FROM  `ws_order_articles` 
                    JOIN  `ws_orders` ON  `ws_order_articles`.`order_id` =  `ws_orders`.`id` 
                    WHERE  `ws_orders`.`customer_id` ='.$this->getId());
                 
		return $co['suma'];
                }
    
    /**
     * @getSumOrder() - сумма всех заказов с учетом депозита
     * @return float - сумма заказов текущего пользователя (с учетом депозита)
     */
	public function getSumOrder(){
		 $co = wsActiveRecord::findByQueryFirstArray('
                    SELECT SUM(`ws_orders`.`amount`+`ws_orders`.`deposit`) AS suma 
                    FROM  `ws_orders` 
                    WHERE  `ws_orders`.`customer_id` ='.$this->getId());
                 
		return $co['suma'];
    }
    /**
     * @getSumOrderNoNew() - сумма заказов с учетом депозита без новыйх
     * @return float - сумма заказов текущего пользователя (с учетом депозита)
     */
	public function getSumOrderNoNew(){
            return  wsActiveRecord::useStatic('Customer')->findByQuery('
						SELECT
							IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
						FROM
							ws_order_articles
							JOIN ws_orders
							ON ws_order_articles.order_id = ws_orders.id
						WHERE
							ws_orders.customer_id = ' . $this->getId() . '
							AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0)->getAmount();	
    }
    
    /**
     * @getDateOrderP - дата последнего заказа
     * @return string  - (2018-09-28 13:19:44)
    */
	public function getDateOrderP(){

           $co =  Shoporders::findByQueryFirstArray('
                    SELECT date_create 
                    FROM `ws_orders`
                    WHERE  `ws_orders`.`customer_id` ='.$this->getId().' 
                    ORDER BY  `id` DESC 
                    LIMIT 1');
		 //$co = wsActiveRecord::useStatic('Shoporders')->findFirst(['customer_id'=>$this->getId()],['id' => 'DESC'])->date_create;
                 
		return $co['date_create'];
    }
    public function sendEmailBirth(){
        $track = base64_encode(date('Y-m-d H:i:s'));
       // echo $this->email.'OK<br>';
        
        $e = new EncodeDecode();
        $h =  $e->encode($this->hash_id);
       

        $view = new View();
         $view->track_open = 'https://www.red.ua/email/cart/?photo='.$track.'.jpg';
       $link =  'https://www.red.ua/basket/l/'.$h.'/?utm_source=birthday&utm_medium=email&utm_content=Birthday&utm_campaign=Birthday&track_cart='.$track;
      // $view->cart = $this;
       $view->link = $link.'&referral=/account/redcoin/';
       $view->referral = '/account/redcoin/';
       $view->email = $this->email;
       $view->name = $this->middle_name.' '.$this->first_name;
       $view->track = '/l/'.$h.'/?utm_source=birthday&utm_medium=email&utm_content=Birthday&utm_campaign=Birthday';
       $msg =  $view->render('email/birthday.template.tpl.php');
       
          // $fn = md5(date('Y-m-d H:i:s').$this->id);
          // $file = "/birthday/{$fn}.html";
          // $fp =  fopen(INPATH."email".$file,"w");//если файла info.txt не существует, создаем его
           
          // fwrite($fp, $msg);//записываем в файл
          // fclose($fp);//закрываем файл.
           EmailLog::add($this->first_name.', Вам начислен бонус в честь дня рождения. Поспешите!', $msg, 'birthday', $this->id);
           
       SendMail::getInstance()->sendEmail($this->email, $this->middle_name, $this->first_name.', Вам начислен бонус в честь дня рождения. Поспешите!', $msg);
        return true;
        
    }
    
   
	
}
