<?php
class Shoporders extends wsActiveRecord
{
    protected $_table = 'ws_orders';
    protected $_orderby = array('id' => 'DESC');

    protected function _defineRelations()
    {
        $this->_relations = array(
			'articles' => array(
				'type' => 'hasMany',
				'class' => self::$_shop_order_articles_class,
				'field_foreign' => 'order_id',
				'orderby' => array('id' => 'ASC'),
				'onDelete' => 'delete'
			),
			'remarks' => array(
				'type' => 'hasMany',
				'class' => self::$_shop_order_remarks_class,
				'field_foreign' => 'order_id',
				'orderby' => array('date_create' => 'ASC'),
				'onDelete' => 'delete'
			),
			'payment_method' => array(
				'type' => 'hasOne',
				'class' => 'PaymentMethod',
				'field' => 'payment_method_id'
			),
			'liqpay_status' => array(
				'type' => 'hasOne',
				'class' => 'LiqPayStatus',
				'field' => 'liqpay_status_id'
			),
			'delivery_type' => array(
				'type' => 'hasOne',
				'class' => 'DeliveryType',
				'field' => 'delivery_type_id'
			),
			'stat' => array(
				'type' => 'hasOne',
				'class' => 'Shoporderstatuses',
				'field' => 'status'
			),
            'fop' => [
                'type' => 'hasOne',
				'class' => 'Fop',
				'field' => 'fop_id'
            ],
                        'customer' => [
                        'type' => 'hasOne',
                        'class' => self::$_customer_class,
                        'field' => 'customer_id'
            ],
            'shop' => [
                        'type' => 'hasOne',
                        'class' => 'Shop',
                'field' => 'shop_id'
            ]
        );
    }
    
    public function isPay() {
        $res = '';
        if($this->payment_method_id == 6 or $this->payment_method_id == 4 or $this->payment_method_id == 8){
        switch ($this->payment_method_id)
        {
            case '4':
            if($this->liqpay_status_id == 3){
            $res = '<span style="color: #00da00;font-weight: bold;"> (оплачен)</span>';
            }else{
                $res = '<span style="color:red;    font-weight: bold;"> (не оплачен)</span>';
            }
            break;//Виза
            case '6': if($this->liqpay_status_id == 3){
            $res = '<span style="color: #00da00;font-weight: bold;"> (оплачен)</span>';
            }else{
                $res = '<span style="color:red;    font-weight: bold;"> (не оплачен)</span>';
            }
            break;//Приват24
            case '8': if($this->amount < 1){
            $res = '<span style="color: #00da00;font-weight: bold;"> (оплачен)</span>';
            }else{
                $res = '<span style="color:red;    font-weight: bold;"> (не оплачен)</span>';
            }
            break;//оплачен депозитом полностью
            default : $res = '<span style="color:red;    font-weight: bold;"> (не оплачен)</span>'; break;
        }
        }
        return $res;
    }
    
    public function isActiya()//акция положи шарф
    {
      
        $flag = false;
        $sharf = false;
        $code = explode(",", Config::findByCode('code_sharf')->getValue());
        $id = 165968;
       
        if(count($code)){
            
            $articles = $this->getArticles();
        if ($articles->count()){
            foreach ($articles as $article){
                if($article->count > 0 && in_array($article->article_db->code, $code)){
                    $flag = true;
                }
                if($article->article_id == $id && $article->count > 0){
                    $sharf = true;
                }
                
            }
    }
    }
        return ($flag && $sharf) ? false : $flag;
    }
    /**
     * Полная стоимость товаров в заказе
     * @return type
     */
    public function FirstPriceOrder() {
        $total = 0.00;
            foreach ($this->articles as $article){
                $total += ($article->price*$article->count);
            }
            return Number::formatMysql($total);
       // return Number::formatMysql(Number::formatFloat($total, 2));
    }
    /**
     * Сумма товаров для подсчета использования бонуса. Сумма без товаров !skidka_blosk && !option_id && count > 0
     * @return type
     */
    public function getOrderAmountCoin(){
         $total = 0.00;
            foreach ($this->articles as $article){
            // if((!$article->skidka_block && !$article->option_id && $article->count > 0) or ($article->option_id == 196)) {
                   if($article->count > 0) {
                  if($article->option_id){
                       $total += ($article->option_price*$article->count);
                  }else{
                        $total += ($article->price*$article->count);
                  }
              }
            }
            return Number::formatMysql($total);
    }
    public function sumBonusOrder(){
         $total = 0.00;
        foreach ($this->articles as $article){
            $total +=$article->coin;//*$article->count;
        }
        return $total;
    }
    /**
     * Сумма товаров минус redcoin для зачисления бонуса. Сумма без товаров !skidka_blosk && !option_id && count > 0
     * @return type
     */
    public function getOrderAmountMinusCoin(){
         $total = 0.00;
            foreach ($this->articles as $article){
              if((!$article->skidka_block && !$article->option_id && $article->count > 0 && $article->old_price == 0)) {//&& !$article->old_price
                  $sk = $this->skidka;
                  if($cashback = $article->article_db->getCashback()){
                      $sk = $cashback;
                  }
                  $total += ceil($sk/100*(($article->price-$article->coin)*$article->count));
              }
            }
            return Number::formatMysql($total);
    }
    /**
     * Количество позиций попадающие под бонус без товаров !skidka_blosk && !option_id && count > 0
     * @return int
     */
    public function getCountArticlesCoin(){
         $total = 0;
            foreach ($this->articles as $article){
              if((!$article->skidka_block and !$article->option_id && $article->count > 0)) {
                  $total ++; 
              }
            }
            return $total;
    }

    public function getTotal($param = false)
    { 
        $total_a = $total_o = 0.00;
        $articles = $this->getArticles();
        $bool = false;
        if ($articles->count()){

        foreach ($articles as $item){ if ($item['option_id'] > 0){ $bool = true;}}
            
        if (!$bool){ $total_o += $this->getDeliveryCost();}

        foreach ($articles as $article) {
            $total_a += $article['price'] * $article['count'];
            if ($article['option_id'] > 0){ $total_o += $article['option_price'] * $article['count'];}
        }
    }
        switch ($param) {
            case 'a'    :
                return $total_a;
            case 'o'    :
                return $total_o;
            default:
                return $total_a + $total_o;
        }
    }

    public function getArticlesCount()//количество товаров в заказе
    {
        $total = 0;
        $articles = $this->getArticles();
        if ($articles->count()){
            foreach ($articles as $article){
                $total += $article->getCount();
            }
    }
        return $total;
    }
	public function getSkuCount()//количество SKU в заказе
    {
        $total = 0;
        $articles = $this->getArticles();
        if ($articles->count()){
            foreach ($articles as $article){
             if($article->getCount() > 0) {$total++;}
				}
        }
        return $total;
    }
	public function getArticlesEvent()//наличие в заказе товара с доп скидкой
    {
        $event = false;
        $articles = $this->getArticles();
        if ($articles->count()){
            foreach ($articles as $article){
			if(($article->getEventId() and $article->getEventSkidka()) or($article->getOptionId())){ $event = true;}
				}
				}
        return $event;
    }
	public function getListArticlesOrder()//наличие в заказе товара с доп скидкой
    {
	$list = '';
        $articles = $this->getArticles();
		if ($articles->count()){
		$i=0;
            foreach ($articles as $article){
			if($i==0){
			$list .= $article->article_id;
			}else{
			$list .= ', '.$article->article_id;
			}
			$i++;
				}
				}
        return $list;
	}

    public function countArticles()
    {
        $co = wsActiveRecord::findByQueryFirstArray('SELECT COUNT(id) as c FROM `ws_order_articles` WHERE order_id=' . $this->getId());
        return $co['c'];
    }

    public function countArticlesSum()
    {
        $co = wsActiveRecord::findByQueryFirstArray('SELECT SUM(count) as c FROM `ws_order_articles` WHERE order_id=' . $this->getId());
        return $co['c'];
    }
	


	static public function getDeliveryPrice()
	{
		if ($_SESSION['order_amount'] >= (int)Config::findByCode('kuryer_amount')->getValue() and $_SESSION['basket_contacts']['delivery_type_id'] == 9){ return 0;}
		
		$d = wsActiveRecord::useStatic('DeliveryPayment')->findFirst(array('delivery_id' => @$_SESSION['basket_contacts']['delivery_type_id'], 'payment_id' => @$_SESSION['basket_contacts']['payment_method_id']));

		if (!$d) {
			$d = new DeliveryType($_SESSION['basket_contacts']['delivery_type_id']);
		}
		if (!$d || !$d->getId()){return Config::findByCode('delivery_cost')->getValue();}
                
		return $d->getPrice();
	}
         public function getDeliveryPriceReload()
	{
		if (($this->amount+$this->deposit) >= (int)Config::findByCode('kuryer_amount')->getValue() and $this->delivery_type_id == 9){
                    $this->setDeliveryCost(0);
                    $this->reCalculate();
                }
                return true;
		
		
	}

    public function getTotalAmount() 
    {
        return wsActiveRecord::useStatic('Shoporders')->findByQuery('
			SELECT
				IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS sum_amount
			FROM
				ws_order_articles
				JOIN ws_orders
				ON ws_order_articles.order_id = ws_orders.id
			WHERE
				ws_orders.status in(1,3,4,6,8,9,10,11,13,14,15,16)
				AND ws_orders.id <=' . $this->getId() . '
				AND ws_orders.customer_id=' . $this->getCustomerId())->at(0)->getSumAmount();
				
    }
    /**
     * 
     * @return type
     */
    public function getAllAmount()
    {
        return wsActiveRecord::useStatic('Shoporders')->findByQuery('
			SELECT
				IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS sum_amount
			FROM
				ws_order_articles
				JOIN ws_orders
				ON ws_order_articles.order_id = ws_orders.id
			WHERE
				ws_orders.status in(1,3,4,6,8,9,10,11,13,14,15,16,100)
				AND ws_orders.id <= '.$this->getId().'
				AND ws_orders.customer_id = '.$this->getCustomerId())->at(0)->getSumAmount();
    }
	
	public function getCountOrder($type = 'm'){
	 switch ($type) {
            case 'd' : 
			$dat =  '%Y%m%d';
			return	wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT COUNT(  `id` ) AS ctn FROM  `ws_orders` WHERE DATE_FORMAT(  `admin_pay_time` ,  '".$dat."' ) = DATE_FORMAT( NOW( ) ,  '".$dat."' ) and status in(8,14) and customer_id = ".$this->getCustomerId())->at(0)->getCtn();
            case 'm' : 
			$dat =  '%Y%m';
			return	wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT COUNT(  `id` ) AS ctn FROM  `ws_orders` WHERE DATE_FORMAT(  `admin_pay_time` ,  '".$dat."' ) = DATE_FORMAT( NOW( ) ,  '".$dat."' ) and status in(8,14) and customer_id = ".$this->getCustomerId())->at(0)->getCtn();
			case 'y' :
			$dat =  '%Y';
			return	wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT COUNT(  `id` ) AS ctn FROM  `ws_orders` WHERE DATE_FORMAT(  `admin_pay_time` ,  '".$dat."' ) = DATE_FORMAT( NOW( ) ,  '".$dat."' ) and status in(8,14) and customer_id = ".$this->getCustomerId())->at(0)->getCtn();
            default : 
			$dat =  '%Y%m%d';
			return	wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT COUNT(  `id` ) AS ctn FROM  `ws_orders` WHERE DATE_FORMAT(  `admin_pay_time` ,  '".$dat."' ) = DATE_FORMAT( NOW( ) ,  '".$dat."' ) and status in(8,14) and customer_id = ".$this->getCustomerId())->at(0)->getCtn();
		   }

	}
	

    public function getTotalPerecent()
    {
        $user = wsActiveRecord::useStatic('Customer')->findFirst(array('id' => $this->getCustomerId()));
        if ($user->getSkidka() != 0) {
            return $user->getSkidka() . '%';
        } else {
            if ($this->getTotalAmount() <= 700) {
                return '0,00%';
            } elseif ($this->getTotalAmount() > 700 && $this->getTotalAmount() <= 5000) { //5%
                return '5,00%';
            } elseif ($this->getTotalAmount() > 5000 && $this->getTotalAmount() <= 12000) { //10%
                return '10,00%';
            } elseif ($this->getTotalAmount() > 12000) { //15%
                return '15,00%';
            }
        }
    }

    public function getTotalPrice()
    {
        $total_price = 0;
        foreach ($this->getArticles() as $article_rec) {
            $total_price += $article_rec->getPrice();// сумма товаров с учетом уценки и без учета количества
        }
        return $total_price;
    }
	 public function getSumOrder()
    {
	
	$skidka = 1 - ($this->getSkidka() / 100);
	//echo $skidka;
        $total_price = 0;
		$sum = 0;
        foreach ($this->getArticles() as $article_rec) {
			$sum = $article_rec->getPrice() * $article_rec->getCount();
			if($article_rec->getOldPrice() == 0) { $sum = $sum * $skidka; }
            $total_price +=$sum; // сумма товаров с учетом уценки и количества единиц
        }
        return $total_price;
    }

    public function getPriceWithSkidka()
    {
         $ws = Registry::get('Website');
		//$this->getOneOneThree();// акция 1+1=3
$articles = $this->getArticles();
        if($this->bonus > 0 and in_array($this->status, [100,9,15,16,3])){ //новый, собран, собран 2, собран 3, Доставлен в магазин
             $coin = $this->bonus; // получаэм бонус в заказе
             $sum_dly_coin = $this->getOrderAmountCoin(); // Сумма товаров на которые распространяются бонусы
             $all_sum = 0;
          //   echo $sum_dly_coin; //149
            // echo $coin;
             
             if($coin > $sum_dly_coin){
                 $coin = $sum_dly_coin;
                 $this->setBonus($coin);
                 $this->save();
                OrderHistory::newHistory($ws->getCustomer()->id, $this->id, 'Обновление бонуса. Бонус сменился ',  'C "' . $coin . '" на "' . $this->bonus . '"');
             }
            // if($this->amount+$coin <= $sum_dly_coin ){
                 
            // }
        
        foreach ($articles as $ar) { // обнуляєм все бонусы в товаре перед просчетом
            $ar->setCoin(0);
            $ar->save();
        }
        foreach ($articles as $a) { // Розделяем бонус на товары
          //  if(!$a->skidka_block && !$a->option_id && $a->count > 0) {
                 if($a->count > 0) {
                     if($a->option_id){
                          $s = round(($a->option_price*$coin/$sum_dly_coin)/$a->count); 
                     }else{
                        $s = round(($a->price*$coin/$sum_dly_coin)/$a->count);
                     }
                   //  echo $s;
                     $all_sum+=$s;
                        $a->setCoin($s);
                        $a->save();
                    }
        }
        if($all_sum >= $coin && $all_sum >= ($this->amount+$this->bonus-1)){ // если сумма розделенных бонусов(из-за округления) больше общего бонуса, отнимаэм разницу от самого дорогого товара
            $r = $all_sum-$coin;
            if($r == 0){ $r = 1;}
            $max = 0;
            $index = -1;
            foreach ($articles as $k => $a){
                if($a->coin > 0){
                    if($a->coin > $max){
                        $max = $a->coin;
                        $index = $k;
                    }
                }
            }
            if($index >= 0){
            $articles[$index]->setCoin($articles[$index]->coin-$r);
            $articles[$index]->save();
        }
        }elseif($coin > $all_sum){//если сумма розделенных бонусов(из-за округления) меньше общего бонуса, добавляем разницу к самому дорогому товару
              $r = $coin-$all_sum;
              $max = 0;
            $index = -1;
            foreach ($articles as $k => $a){
                if($a->coin > 0){
                    if($a->coin > $max){
                        $max = $a->coin;
                        $index = $k;
                    }
                }
            }
            if($index >= 0){
            $articles[$index]->setCoin($articles[$index]->coin+$r);
            $articles[$index]->save();
            }
        }
    }
        $to_pay = 0;
        foreach ($articles as $a) {
		if($a->getCount() > 0){ 
            $price = $a->getPerc(); // цена товара со всеми скидками
            $to_pay += $price['price'];
                        }
        }
        return $to_pay;
    }
	
	public function getOneOneThree(){ // акция 1+1=3
	
	if($this->getArticlesCount() >= 3){
	$mas = array();
	$min = array();
	foreach ($this->getArticles() as $art) {
	if(($art->getArticleDb()->getCategoryId() == 117 or $art->getArticleDb()->getCategoryId() == 70) and $art->getCount() > 0){
	$mas[$art->getId().'_'.$art->getArtikul()] = $art->getPrice();
	}
	$art->setOptionId(0);
	$art->save();
	}
	$resul = count($mas);
	//d($mas, false);
	if($resul >=3 and $resul < 6 ){
	$m1 = array_keys($mas, min($mas))[0];
		$min[] = $m1;
	}elseif($resul >=6 and $resul < 9){
	$m1 = array_keys($mas, min($mas))[0];
	$min[] = $m1;
	 unset($mas[$m1]);
	 $m2 = array_keys($mas, min($mas))[0];
	 $min[] = $m2;
	}
	
	if(count($min) > 0){
	//d($min, false);
	foreach ($this->getArticles() as $ar) {
	if(in_array($ar->getId().'_'.$ar->getArtikul(), $min)) {
	$ar->setOptionId(1);
	$ar->save();
	}
	}
	
	}
	}else{
	foreach ($this->getArticles() as $art) {
	$art->setOptionId(0);
	$art->save();
	}
	}

	
	
	}
        
        public function ravnoCoinInOrderOfReturnArticles(){
            $s = 0;
            foreach ($this->getArticles() as $a){
               $s+=$a->coin*$a->count; 
            }
            if($this->bonus > $s){ $this->setBonus(round($s)); $this->save(); }
        }

        /**
     * Shoporders::calculateOrderPrice()
     * Считаем сумму заказа и сохраняет новую сумму в заказ
     * 
     * @param type $use_deposit - считать депозит, по умолчанию true
     * @param type $use_format - true отобразить в формате 100,10 иначе 100.10 по умолчанию true
     * @param type $delivery - true можно не указывать
     * @param type $bonus - false можно не указывать
     * @return float
     */
    public function calculateOrderPrice($use_deposit = true, $use_format = true, $delivery = true, $bonus = false)
    {
       $order_history_price = $this->getPriceWithSkidka();

        if ($order_history_price < 0) {  $order_history_price = 0; }
        
         $price_2 = (float)$order_history_price;
         
         if($this->getBonus()){
            if($price_2 >= Config::findByCode('min_sum_bonus')->getValue()){
                $price_2 -= (float)$this->getBonus();
            }
        }
        
        $price_2 += (float)$this->getDeliveryCost();

        if ($this->getDeposit()) { $price_2 -= (float)$this->getDeposit(); }
        
        if($this->getDopSumma()){ $price_2 += (float)$this->getDopSumma();}
		
		
        	


    if ($price_2 < 0) { $price_2 = 0; }
    
   // $price_2 = ceil($price_2);
		
    if ($price_2 != $this->getAmount()){
		//d($price_2, false);
		//d((float)$this->getAmount(), false);
	$this->save();
		}
                
        if ($use_format) { $price_2 = Number::formatFloat($price_2, 2); }
		 
        return $price_2;
    }
    /**
     * Shoporders::calculateOrderPrice2()
     * Считаем сумму заказа
     * 
     * @param type $use_deposit - считать депозит, по умолчанию true
     * @param type $use_format - true отобразить в формате 100,10 иначе 100.10 по умолчанию true
     * @param type $delivery - true для учета стоимости доставки, можно не указывать
     * @param type $bonus - false можно не указывать
     * @return float
     */
    public function calculateOrderPrice2($use_deposit = true, $use_format = true, $delivery = true)
    {
      $order_history_price = $this->getPriceWithSkidka();
	   
         if ($order_history_price < 0) {  $order_history_price = 0; }
         
         $price_2 = $order_history_price;
        
        if($this->getBonus() > 0){
            if($price_2 >= Config::findByCode('min_sum_bonus')->getValue()){
                $price_2 -= $this->getBonus();
                if($price_2 <= 0) { $price_2 = 1;} 
            }
        }
        
        if($delivery){
         $price_2 += (float)$this->getDeliveryCost();
        }
        if ($this->getDeposit() > 0){ $price_2 -= $this->getDeposit(); }

        if($this->getDopSumma()){ $price_2 += (float)$this->getDopSumma();}
        
        if ($price_2 <= 0 ) { $price_2 = 0; }
		
	//$price_2 = ceil($price_2);
        
	if ($use_format) { $price_2 = Number::formatFloat($price_2, 2); }
		
        return $price_2;
    }

    public function isUcenArticle()
    {
        foreach ($this->getArticles() as $article_rec) {
            $article = new Shoparticles($article_rec->getArticleId());
            if ((int)$article->getOldPrice() and !$article->getSkidkaBlock()) {
                return true;
            }
        }
        return false;
    }

    public function getDiscont()
    {
        $customer = new Customer($this->getCustomerId());
        if ($customer) {
            return $customer->getDiscont($this->getId());
        } else {
            return '0';
        }
    }

    public function updateDeposit($admin, $is_deposit = true)
    {
        $order = $this;
        if ($order->getId()) {
            if ($is_deposit) {
                if ($order->getDeposit() == 0) return false;
            }

		//$d_cost = $order->getDeliveryCost(); 
			
            $customer = new Customer($order->getCustomerId());
            
            $sum = (($order->getPriceWithSkidka() + $order->getDeliveryCost()+$order->getDopSumma()) - $order->getDeposit()) - $customer->getDeposit();
            
            $old = $order->getDeposit();//=100
            
            $deposit = ($customer->getDeposit() + $order->getDeposit()) - ($order->getPriceWithSkidka() + $order->getDeliveryCost()+$order->getDopSumma());
            if ($sum < 0) { $sum = 0; }
            if ($deposit < 0){ $deposit = 0;}
            
            $dedosit_to_order = ($order->getPriceWithSkidka() + $order->getDeliveryCost()+$order->getDopSumma()) - $sum;
			
            $customer->setDeposit(round($deposit, 2));
            $order->setDeposit(round($dedosit_to_order, 2));
            $customer->save();
            $order->save();
            
        OrderHistory::newHistory($admin, $order->getId(), 'Использован депозит. Депозит сменился ', 'C "' . $old . '" на "' . $order->getDeposit() . '"');
	$no = '-';
	DepositHistory::newDepositHistory($admin, $customer->getId(), $no, $order->getDeposit(), $order->getId());
        }
        return true;

    }
    /**
     * Использование бонуса в заказе
     * @param type $admin - id админа
     * @return boolean
     */
    public function updateBonus($admin)
    {
        $order = $this;
        $summ = $this->getOrderAmountCoin();
        if($order->bonus > 0){
            $bonus = $order->bonus;
           
           /* foreach ($order->articles as $art){
                if(!$art->skidka_block){
                    $summ+=$art->price*$art->count; 
                }
                 
            }*/
            
            if($bonus >= $summ){
               // if($summ >= $order->amount){
                    $summ--;
              //  }
                 OrderHistory::newHistory($admin, $order->id, 'Обновление бонусаа. Бонус сменился ',  'C "' . $order->bonus . '" на "' . $summ . '"');
                $order->setBonus($summ);
                $order->save();
                return true;
            }elseif($summ > ($bonus+1) && $order->customer->getSummCoin('active')){
                 $coin = $order->customer->getAllCoin('active');
        $total_price = $summ - $order->bonus;
        $scoin = 0.00;
    foreach ($coin as $m){
        if($m->coin <= $total_price){
            $total_price -=  $m->coin;
            $scoin += $m->coin;
            BonusHistory::add($order->customer_id, 'Использован', $m->coin, $order->id);
            $m->setCoinOn($m->coin_on+$m->coin);
            $m->setCoin(0);
            $m->setStatus(3);
            $m->save();
            
        }else{
            $m->setCoin($m->coin - $total_price);
            $m->setCoinOn($m->coin_on+$total_price);
            $scoin += $total_price;
            BonusHistory::add($order->customer_id, 'Использован', $total_price, $order->id);
            $total_price = 0;
            $m->save();
        }
    }
   // if($scoin >= $order->amount){
   //     $scoin--;
   // }
    OrderHistory::newHistory($admin, $order->id, 'Обновление бонуса.. Бонус сменился ',  'C "' . $order->bonus . '" на "' . ($order->bonus+$scoin) . '"');
    $order->setBonus($order->bonus+$scoin);
     $amount = ($order->amount-$scoin)<=0?1:$order->amount-$scoin;
    $order->setAmount($amount);
    //$order->setAmount($total_price);
    $order->save();
    
                  return true; 
            }
        }elseif($summ > 0 and $order->customer->getSummCoin('active')){
        $coin = $order->customer->getAllCoin('active');
        $total_price = $summ;
        $scoin = 0;
    foreach ($coin as $m){
        if($m->coin <= $total_price){
            $total_price -=  $m->coin;
            $scoin += $m->coin;
            BonusHistory::add($order->customer_id, 'Использован', $m->coin, $order->id);
            $m->setCoinOn($m->coin_on+$m->coin);
            $m->setCoin(0);
            $m->setStatus(3);
            $m->save();
            
        }else{
            $m->setCoin($m->coin - $total_price);
            $m->setCoinOn($m->coin_on+$total_price);
            $scoin += $total_price;
            BonusHistory::add($order->customer_id, 'Использован', $total_price, $order->id);
            $total_price = 0;
            $m->save();
        }
    }
    if($scoin >= $order->amount){
        $scoin--;
    }
    OrderHistory::newHistory($admin, $order->id, 'Использован бонус... Бонус сменился ',  'C "' . $order->bonus . '" на "' . $scoin . '"');
    $order->setBonus($scoin);
    $amount = ($order->amount-$scoin)<=0?1:$order->amount-$scoin;
    $order->setAmount($amount);
    //$order->setAmount($total_price);
    $order->save();
    
                  return true; 
                        }
                        return false;
        
        
        
        
       /* if ($order->getId()) {
            $customer = new Customer($order->getCustomerId());
             
            if ($customer->getBonus() == 0) {}
                
            
            if($order->getBonus() == 0){
               
            $sum = $order->getAmount();
            if ($sum < 0) { $sum = 0; }
             $c_bonus = $customer->getBonus();
            if($sum > 0 and $c_bonus > 0){
                if($sum >= $c_bonus){
                   $bonus = $c_bonus;
                     $customer->setBonus(0);
                    $customer->save();
                }else{
                    $bonus = $sum;
                     $customer->setBonus(round(($c_bonus-$sum), 2));
                    $customer->save();
                }
                
                 OrderHistory::newHistory($admin, $order->getId(), 'Использован бонус. Бонус сменился ', 'C "' . $order->getBonus() . '" на "' . $bonus . '"');
                
           $order->setBonus($bonus);
           $order->save();
            }
            }else{
                return false;
            }
        }
        return true;
*/
    }
    /**
     * Обновление суммы заказа и запсить скидки клиентской
     * сохраняется даннные заказа
     */
    public function reCalculate()
    {
        $order_owner = new Customer($this->getCustomerId());
        if ($order_owner->getId()) {
            $this->setAmount(str_replace(',', '.', $this->calculateOrderPrice2(true, false)));
            $this->setSkidka($order_owner->getDiscont($this->getId()));
        }
        $this->save();
    }

    public function _beforeSave()
    {
        $ws = Registry::get('Website');

        $order_owner = new Customer($this->getCustomerId());
        if ($order_owner->getId()) {
            $this->setAmount(trim(str_replace(',', '.', $this->calculateOrderPrice2(true, false))));
        }

        if ($ws->getCustomer()->isAdmin()) {
            $gt = Registry::get('get');
            $customer = wsActiveRecord::useStatic('Customer')->findFirst(array('id' => $this->getCustomerId()));
            if ($customer) {
                if (!$ws->getCustomer()->hasRight('edit_my_order') and strtolower($gt->controller) == 'admin' and $customer->isAdmin()) {
                    die('Нету прав редактировать заказ');
                }
            }
        }

        return true;
    }

    static function canEdit($order)
    {
        $ws = Registry::get('Website');
        //$order = new Shoporders($order_id);
        if ($ws->getCustomer()->isAdmin() and $order->getId()) {
            $gt = Registry::get('get');
            $customer = wsActiveRecord::useStatic('Customer')->findFirst(array('id' => $order->getCustomerId()));
            if ($customer) {
                if (!$ws->getCustomer()->hasRight('edit_my_order') and strtolower($gt->controller) == 'admin' and $customer->isAdmin()) {
                    die('Нету прав редактировать заказ');
                }
            }
        }
    }

    public function _afterSave()
    {
        $order_owner = new Customer($this->getCustomerId());
        if ($order_owner->getId()) {
            $s = $order_owner->getDiscont(false, 0, true);
            $order_owner->setRealSkidka($s);
            $order_owner->save();
        }

        return true;
    }

    public function _beforeDelete()
    {
        $ws = Registry::get('Website');
        $data = array(
            'ip' => $_SERVER['REMOTE_ADDR'],
            'referer' => @$_SERVER['HTTP_REFERER'],
            'customer' => $ws->getCustomer()->getId(),
            'data' => json_encode($this->export())
        );

        wsLog::add(print_r($data, 1), 'DeleteOrder');

        if (!$ws->getCustomer()->isAdmin()) return false;

        return true;
    }
	public function LiqPay(){
	$ammount = $this->calculateOrderPrice2(true, false);
	$order_id = $this->getId();
	$data['button_confirm'] = 'Оплатить '.$ammount.' грн.';
	$description = 'Заказ №' . $order_id;
		
		
        $result_url = 'https://www.red.ua/liqpay-success';//$this->url->link('liqpay/success', '', 'SSL');
		
        $server_url = 'https://www.red.ua/liqpay-callback';//$this->url->link('extension/payment/liqpay_checkout/callback', '', 'SSL');
		$send_data = array(
			'version' => 3,
            'public_key' => 'i51858842721',
            'amount' => $ammount,
            'currency' => 'UAH',
            'description' => $description,
            'order_id' => $order_id,
            'action' => 'pay',
            'language' => $_SESSION['lang'],
            'server_url' => $server_url,
            'result_url' => $result_url
			);


        $liqpay_data = base64_encode(json_encode($send_data));
        $liqpay_signature = $this->calculateSignature($liqpay_data, 'r7A8olggiLS7OEZs4xAioTg2SZI4w6q6mdWFU9kT');

        $data['data'] = $liqpay_data;
        $data['signature'] = $liqpay_signature;
        $data['action'] = 'https://www.liqpay.ua/api/3/checkout';
	return $data;
	}
        
    private function calculateSignature($data, $private_key)
        {
            return base64_encode(sha1($private_key . $data . $private_key, true));
        }
        /**
         * Изменение статуса
         * @param type $post_st_id - id status new
         * @param type $view  - представление для чтения файлов
         * @param type $user  - админ
         * @param type $status  - новый статус
         * @return boolean
         */
        public function editStatus($post_st_id, $view, $user, $status = false){
            $order = $this;
           // $view = Registry::get('View');
           // $user = Registry::get('Website')->getCustomer();
            if(!$status){
                $status =  wsActiveRecord::useStatic('Shoporderstatuses')->findById((int)$post_st_id);
            }
            $st = $order->status;
            switch ($status->id)
            {   
                case '100': break;// Новый
                case '1': // в процессе(не используется)
                     $order->setDateVProcese(date('Y-m-d'));
                    break; 
                case '2'://Отменён
                    $error = [];
                    foreach ($order->articles as $art) {
$article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(['id_article' => $art->getArticleId(), 'id_size' => $art->getSize(), 'id_color' => $art->getColor()]);
    if($article){		
		OrderHistory::newHistory($user->id, $art->getOrderId(), 'Отмена заказа', '', $art->getArticleId());
                $artic = new Shoparticles($art->getArticleId());
                                                                               
                     if($article->getCount() == 0){ //отправка напоминания о наличии
			if(wsActiveRecord::useStatic('Returnarticle')->count(array('code' => $article->getCode(), 'utime is null')) > 0){
                            $this->sendMailAddCount($view, $article->getCode(), $article->getIdArticle());
			}	
                    }
		$article->setCount($article->getCount() + $art->getCount());
		$artic->setStock($artic->getStock() + $art->getCount());
                                                
		$art->setCount(0);
                $art->save();
		$article->save();
		$artic->save();						
	}else{
		wsLog::add('Ошибка удаления ' . $art->Title() . ' - ' . $art->getArticleId(), 'ERROR dell article');
               $error[] = 'Ошибка удаления';
            }  
        }//end foreath
                                
                if(!count($error)){
                    
                    $order->setDeliveryCost(0);
        $deposit = $order->getDeposit();
        $order->setDeposit(0);
        $order->save();
        $customer = new Customer($order->getCustomerId());
	$c_dep = $customer->getDeposit();
	$new_d = (float)$customer->getDeposit() + (float)$deposit;
        $customer->setDeposit((float)$customer->getDeposit() + (float)$deposit);
        $customer->save();
	if($deposit > 0){
	OrderHistory::newHistory($user->getId(), $order->getId(), 'Клиенту зачислен депозит ('.$deposit.') грн. ','C "' . $c_dep . '" на "' . $new_d . '"');
	DepositHistory::newDepositHistory($user->getId(), $customer->getId(), '+', $deposit, $order->getId());
	}
         OrderHistory::cancelOrder($order->id, $order->customer_id);//Отмена заказа и списание бонусов
                }
                    break;
                case '3': //Доставлен в магазин
                  //  $order->setOrderGo(date('Y-m-d H:i:s'));
                   // $order->setDayOrderGo(time() - strtotime($order->getDateCreate()));//время зборки заказа
                    if(in_array($order->getDeliveryTypeId(), [1,2,3,5,7,11,12,13,15,19,21])){
                        $order->seDeliveryDate(date('Y-m-d'));
                    if($status->send_sms){
                                $phone = Number::clearPhone($order->getTelephone());
                                include_once('smsclub.class.php');
                                $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                                $sender = Config::findByCode('sms_alphaname')->getValue(); 
				$mes = 'Zakaz №' . (int)$order->getId() . ' ' . $status->traslite . '. Summa ' . $order->getAmount() . ' grn.';
                                $use = $sms->sendSMS($sender, $phone, $mes);
                                wsLog::add('SMS to order: ' .$mes, 'SMS_' . @$sms->receiveSMS($use));
			}
                        if ($status->send_email and isValidEmailNew($order->getEmail()) and isValidEmailRu($order->getEmail())){
                            $text = '<tr>
					<td><br>
					Ваш заказ № <a href="https://www.red.ua/account/orderhistory/">' . (int)$order->getId() . '</a> - "' . $status->name . '".<br><br>
					</td>
                                    </tr>';
                            $view->content = $text;
                            $msg = $view->render('email/official/template.php');					
                            SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), '#'.$order->getId().' - изменения статуса', $msg);
                        }
            }else{
                return false;
            }
                    break;
                case '4': //отпр. укрпочтой (не используется)
                   $order->setOrderGo(date('Y-m-d H:i:s'));
                   $order->setDayOrderGo(time() - strtotime($order->getDateCreate()));//время зборки заказа
                    if($status->send_sms){
                                $phone = Number::clearPhone($order->getTelephone());
                                include_once('smsclub.class.php');
                                $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                                $sender = Config::findByCode('sms_alphaname')->getValue(); 
				$mes = 'Zakaz №' . (int)$order->getId() . ' ' . $status->traslite . '. TTH №' . $order->nakladna;
                                $use = $sms->sendSMS($sender, $phone, $mes);
                                wsLog::add('SMS to order: ' .$mes, 'SMS_' . @$sms->receiveSMS($use));
			}
                        if ($status->send_email and isValidEmailNew($order->getEmail()) and isValidEmailRu($order->getEmail())){
                            $text = '<tr>
					<td><br>
					Ваш заказ № <a href="https://www.red.ua/account/orderhistory/">' . (int)$order->getId() . '</a> - "' . $status->name . '" <br>
					</td>
                                    </tr>';
                            if($order->nakladna){
                                $text .= '<tr>
                                            <td>Номер накладной: №' . $order->nakladna.' <a href="https://track.ukrposhta.ua/tracking_UA.html?barcode='.$order->nakladna.'">Отследить </a></td>
					</tr>';
                            }
                            $view->content = $text;
                            $msg = $view->render('email/official/template.php');					
                            SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), '#'.$order->getId().' - изменения статуса', $msg);
                        }
                    break;
                case '5': //срок хранения
                    $today = date('Y-m-d H:i:s', strtotime('-'.(int)Config::findByCode('count_dey_ban_samovyvos')->getValue().' days'));		
$ord = wsActiveRecord::useStatic('Shoporders')->findAll(array('customer_id' => $order->getCustomerId(), 'flag = 1 and date_create >= "'.$today.'"'));
$ban = (int)Config::findByCode('ban_shop_count')->getValue()-1;
if($ord->count() >= $ban){
$or_list="";
foreach($ord as $r){
$or_list.=$r->getId().", ";
$r->setFlag(2);
$r->save();
}
$order->setFlag(2); 
$users = new Customer($order->getCustomerId());
$users->setBanAdmin($user->getId());
$users->setBanComment('Автобан по заказу ( '.$order->getId().'). Список заказов '.$or_list);
$users->setBlockM(1);
$users->setBanDate(date('Y-m-d H:i:s'));
$users->save();
wsLog::add('Автобан по заказу ( '.$order->getId().'). Список заказов '.$or_list, 'BAN');
}else{
 $order->setFlag(1);
 }
                    break;
                case '6': //отрп.нп(не используется)
                    $order->setOrderGo(date('Y-m-d H:i:s'));
                   $order->setDayOrderGo(time() - strtotime($order->getDateCreate()));//время зборки заказа
                    if($status->send_sms){
                                $phone = Number::clearPhone($order->getTelephone());
                                include_once('smsclub.class.php');
                                $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                                $sender = Config::findByCode('sms_alphaname')->getValue(); 
				$mes = 'Zakaz №' . (int)$order->getId() . ' ' . $status->traslite . '. TTH №' . $order->nakladna;
                                $use = $sms->sendSMS($sender, $phone, $mes);
                                wsLog::add('SMS to order: ' .$mes, 'SMS_' . @$sms->receiveSMS($use));
			}
                        if ($status->send_email and isValidEmailNew($order->getEmail()) and isValidEmailRu($order->getEmail())){
                            $text = '<tr>
					<td><br>
					Ваш заказ № <a href="https://www.red.ua/account/orderhistory/">' . (int)$order->getId() . '</a> - "' . $status->name . '".<br>
					</td>
                                    </tr>';
                            if($order->nakladna){
                                $text .= '<tr>
                                            <td>Номер накладной: №' . $order->nakladna.' <a href="https://novaposhta.ua/tracking/?cargo_number='.$order->nakladna.'">Отследить</a></td>
					</tr>';
                            }
                            $view->content = $text;
                            $msg = $view->render('email/official/template.php');					
                            SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), '#'.$order->getId().' - изменения статуса', $msg);
                        }
                    break;
                case '7'://Возврат
                    if($st == 3 and $order->getDeliveryTypeId() == 3){ $order->setFlag(3); }
                    $error = [];
                    foreach ($order->articles as $art) {            
			if($order->getDeliveryTypeId()== 3 and $art->getCount() > 0){
				$article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $art->getArticleId(), 'id_size' => $art->getSize(), 'id_color' => $art->getColor()));
					if($article->id){
									for($i=1; $i<=$art->getCount(); $i++){
									OrderHistory::newHistory($user->id, $order->Id(), 'Возврат товара', OrderHistory::getNewOrderArticle($art->getId()), $art->getArticleId());
									$artic = new ShoporderarticlesVozrat();
									$artic->setStatus(0);
									$artic->setOrderId($art->getOrderId());
									$artic->setArticleId($art->getArticleId());
									$artic->setCod($art->getArtikul());
									$artic->setTitle($art->getTitle());
									$artic->setCount(1);
									$artic->setPrice($art->getPrice());
									$artic->setCtime(date('Y-m-d H:i:s'));
									$artic->setUtime(date('0000-00-00 00:00:00'));
									$artic->setUser($user->getId());
									$artic->setDelivery($order->getDeliveryTypeId());
									$artic->setSize($art->getSize());
									$artic->setColor($art->getColor());
									$artic->setOldPrice($art->getOldPrice());
									if($order->getDeposit() > 0){
									$artic->setDeposit(1);
									}
									$artic->save();
									}
									
									$art->setCount(0);
                                                                            $art->save();
									}else{
									wsLog::add('Ошибка перемещения на возврат ' . $art->Title() . ' - ' . $art->getArticleId(), 'ERROR dell article');
					$error[] = 'Ошибка перемещения на возврат';
                                  }
				}else{
                                        $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $art->getArticleId(), 'id_size' => $art->getSize(), 'id_color' => $art->getColor()));
				if($article){
										
				OrderHistory::newHistory($user->id, $art->getOrderId(), 'Отмена заказа', '', $art->getArticleId());
					$artic = new Shoparticles($art->getArticleId());
                                                                                
                                        if($article->getCount() == 0){
					if(wsActiveRecord::useStatic('Returnarticle')->count(['code' => $article->getCode(), 'utime is null']) > 0){
					$this->sendMailAddCount($view, $article->getCode(), $article->getIdArticle());
					}	
						}
                                        
                                               
                                                
					$article->setCount($article->getCount() + $art->getCount());
					$artic->setStock($artic->getStock() + $art->getCount());
                                                
					$art->setCount(0);
                                    $art->save();
					$article->save();
					$artic->save();
									
				}else{
				wsLog::add('Ошибка удаления ' . $art->Title() . ' - ' . $art->getArticleId(), 'ERROR dell article');
				$error[] = 'Ошибка удаления';
                                												} 
				}   
                                }//end foreath
                                
                                if(!count($error)){
                                    $order->setDeliveryCost(0);
                                
                                $deposit = $order->getDeposit();
                                $order->setDeposit(0);
                                $order->save();
                                $customer = new Customer($order->getCustomerId());
				$c_dep = $customer->getDeposit();
				$new_d = (float)$customer->getDeposit() + (float)$deposit;
                                $customer->setDeposit((float)$customer->getDeposit() + (float)$deposit);
                                $customer->save();
				if($deposit > 0){
				OrderHistory::newHistory($user->getId(), $order->getId(), 'Клиенту зачислен депозит ('.$deposit.') грн. ','C "' . $c_dep . '" на "' . $new_d . '"');
				DepositHistory::newDepositHistory($user->getId(), $customer->getId(), '+', $deposit, $order->getId());
				}
                                 OrderHistory::cancelOrder($order->id, $order->customer_id);//Отмена заказа и списание бонусов
            }
                    break;
                case '8':  //Оплачен
                    OrdersPay::newOrderPay($user->getId(), $order->getCustomerId(), $order->calculateOrderPrice(true, false), $order->getId());
                    
/*  if(EventCustomer::getEventsCustomerCount($order->getCustomerId()) < 2 and wsActiveRecord::useStatic('EventCustomer')->count(array('order'=>$order->getId())) == 0 and date("Y-m-d") <= '2020-02-27'){
	
	
	$end_date = date("Y-m-d H:i:s", strtotime("now +2 days"));
	$dat_e = date("d-m-Y H:i:s", strtotime($end_date));
							$ev = new EventCustomer();
							$ev->setCtime(date("Y-m-d H:i:s"));
							$ev->setEndTime($end_date);
							$ev->setEventId(15);
							$ev->setCustomerId($order->getCustomerId());
							$ev->setStatus(1);
							$ev->setSt(1);
							$ev->setOrder($order->getId());
							$ev->save();
							
			$text = '
<p><img src="https://www.red.ua/storage/images/RED_ua/New/h_1449567151_1867958_82a3df0b9a-1024x356.jpg" alt="" width="700" height="243"></p>
<p style="text-align: center;font-size: 14pt;"><strong>'.$order->getName().', у нас для тебя есть специальное предложение..</strong></p>
<p style="text-align: justify;"><span style="font-size: 12pt;">Дарим дополнительную скидку 10% на покупку в нашем интернет-магазине. Предложение диствительно 48 часов с момента получения этого письма <span style="font-size: 10pt;">(до '.$dat_e.')</span>. </span></p>
<p style="text-align: center; font-size: 10pt; color: &amp;808080;">Для получения скидки нужно успеть оформить заказ в течении 48 часов.</p>
<p style="text-align: center; font-size: 10pt; color: &amp;808080;"><strong>Дополнительные условия:</strong></p>
<p style="font-size: 10pt; color: &amp;808080; text-align: left;">		1. скидка действует единоразово (при отмене или возврате заказа скидка теряется),</p>
<p style="font-size: 10pt; color: &amp;808080; text-align: left;">		2. скидка сумируется со всеми скидками на сайте кроме товаров участвующих в других акциях и товаров с этикеткой "LAST PRICE",</p>
<p style="font-size: 10pt; color: &amp;808080; text-align: left;">		3. распространяется только на товары в заказе оформленном в период акции ( совмещение с другими заказами невозможно).</p>
<p style="font-size: 10pt; color: &amp;808080; text-align: left;">		4. каждый покупатель может получить максимум два  предложения со скидкой в месяц,</p>
<p style="font-size: 10pt; color: &amp;808080; text-align: left;">		5. скидка подключается при оформлении заказа через корзину, при оформлении быстрого заказа - акция не подключается.</p>
<p style="text-align: left;"></p>';
							
						$view->content = $text; 
						$msg = $view->render('email/template.tpl.php');
						SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), 'Дополнительная скидка 10% на новую покупку.', $msg);
							
							}*/
                    
                    if($order->getCountOrder('m') == 3){
			if(isValidEmailNew($order->getEmail()) and isValidEmailRu($order->getEmail())){
								$text_full = '
	<tr><td><img src="https://www.red.ua/images/otzyv1.png"  width="700" height="50" border="0"/></td></tr>
    <tr><td>
	<h2>Привет, '.$order->getName().'!</h2>
	Тобой был сделан заказ № <a href="http://www.red.ua/account/orderhistory/">'.$order->id . '</a>, оставь, пожалуйста, свой отзыв.<br></td></tr> 
	<tr><td><br>
        <a href="https://www.red.ua/reviews/">
        <img src="https://www.red.ua/images/kol.jpg"  width="700" height="300" border="0"/>
        </a>
        </td></tr>';
	$view->content = $text_full; 
	$msg = $view->render('email/official/template.php');
	SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $order->getName().', тебе понравилась покупка? Оставь свой отзыв.', $msg);
	}
							
		}
                    
                    break;
                case '9': //Собран
                    if($st == 100){
                    $order->setAdmin($user->id);
                } 
                    break;
                case '10': break;//Продлен клиентом(не используется)
                case '11': //Ждет оплаты (не используется)
                     $order->setDelayToPay(date('Y-m-d'));
                    break;
                case '12': break;//Ждет возврат
                
                case '13': //Отправлен
                    $order->setOrderGo(date('Y-m-d H:i:s'));
                   $order->setDayOrderGo(time() - strtotime($order->getDateCreate()));//время зборки заказа
                    if($status->send_sms){
                switch ($order->getDeliveryTypeId())
                    {   
                case '4': 
                    $mes = 'Zakaz №' . (int)$order->getId() . ' ' . $status->traslite . '. TTH №' . $order->nakladna;
                    $this->sendSMS($mes);      
                    break;
                case '8': 
                   $mes = 'Zakaz №' . (int)$order->getId() . ' ' . $status->traslite . '. TTH №' . $order->nakladna;
                    $this->sendSMS($mes);      
                    break;
                case '16': 
                   $mes = 'Zakaz №' . (int)$order->getId() . ' ' . $status->traslite . '. TTH №' . $order->nakladna;
                    $this->sendSMS($mes);      
                    break;
                case '9': 
                    $mes = 'Zakaz №' . (int)$order->getId() . ' ' . $status->traslite . '. Dostavka:' . $order->getDeliveryDate();
                    $this->sendSMS($mes);      
                    break;
                case '18': 
                    $mes = 'Zakaz №' . (int)$order->getId() . ' ' . $status->traslite . '. TTH №' . $order->nakladna;
                    $this->sendSMS($mes);      
                    break;
                default : break;
                    }
			}
                        
                        
                if ($status->send_email and isValidEmailNew($order->getEmail()) and isValidEmailRu($order->getEmail())){
                $text = '<tr><td><br>Ваш заказ № <a href="https://www.red.ua/account/orderhistory/">' .$order->id. '</a> - "'.$status->name.'".<br></td></tr>';
                switch ($order->getDeliveryTypeId())
                    {   
                case '4': 
                    if($order->nakladna){
                                $text .= '<tr>
                                            <td>Номер накладной: №' . $order->nakladna.' <a href="https://track.ukrposhta.ua/tracking_UA.html?barcode='.$order->nakladna.'">Отследить </a></td>
					</tr>';
                            }
                   //  $this->sendEmail($text);      
                    break;
                case '8': 
                   if($order->nakladna){
                                $text .= '<tr>
                                            <td>Номер накладной: №' . $order->nakladna.' <a href="https://novaposhta.ua/tracking/?cargo_number='.$order->nakladna.'">Отследить</a></td>
					</tr>';
                            }
                    // $this->sendEmail($text);     
                    break;
                case '16': 
                   if($order->nakladna){
                                $text .= '<tr>
                                            <td>Номер накладной: №' . $order->nakladna.' <a href="https://novaposhta.ua/tracking/?cargo_number='.$order->nakladna.'">Отследить</a></td>
					</tr>';
                            }
                    // $this->sendEmail($text);      
                    break;
                case '9': 
                    if($order->nakladna){
                               $text .= '<tr>
                                            <td>Дата доставки: ' . $order->getDeliveryDate().'</td>
					</tr>';
                            }
                    // $this->sendEmail($text);      
                    break;
                case '18': 
                    if($order->nakladna){
                               $text .= '<tr><td>Номер накладной: №' .$order->nakladna.' <a href="https://justin.ua/tracking-ttn/?ttn_number='.$order->nakladna.'">Отследить</a></td></tr>';
                           }
           
                    // $this->sendEmail($text);     
                    break;
                default : break;
                    }  
            $view->content = $text;
            $msg = $view->render('email/official/template.php');					
            SendMail::getInstance()->sendEmail($this->getEmail(), $this->getName(), 'Изменения статуса заказа', $msg);
                        }
                    break;
                case '14': break; //Оплачен депозитом (не используется)
                case '15': // Собран 2
                     if($st == 100){
                    $order->setAdmin($user->id);
                }
                    break;
                case '16': // Собран 3
                     if($st == 100){
                    $order->setAdmin($user->id);
                }
                    break;
                case '17': break; //Совмещен
                
                    default: break;
            }
            
            OrderHistory::newHistory(
                    $user->id,
                    $order->id,
                    'Смена статуса',
                    'C "'.$order->getStat()->name.'" на "'.$status->name.'"'
                    );
            
            $order->setStatus($status->id);
            return $order->save();
    }
    /**
     * Изменение ТТН
     * @param type $ttn
     * @return boolean
     */
    public function editNakladna($ttn = ''){
         $user = Registry::get('Website')->getCustomer();
            OrderHistory::newHistory($user->id, $this->getId(), 'Смена номера накладной', 'C "' . $this->getNakladna() . '" на "' . $ttn . '"');  
            $this->setNakladna($ttn);
            $this->save();
            return true;
    }
    
    public function sendMailAddCount($view, $code, $id) 
    {
	$art = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(['code' => $code, 'id_article'=>$id]);
        if($art){
	$view->art = $art; 
        $view->art1 = wsActiveRecord::useStatic('Shoparticles')->findFirst(['id' => $id]);

	foreach(wsActiveRecord::useStatic('Returnarticle')->findAll(['code' => $code, 'utime is null', 'id_article'=>$id]) as $articles) {
            if(isValidEmailNew($articles->getEmail()) and isValidEmailRu($articles->getEmail())){
		$msg = $view->render('mailing/notice.template.tpl.php');
		$subject = 'Привет, '.$articles->getName().', товар появился в наличии. Cпеши купить!';
		$view->email = $articles->getEmail();
		SendMail::getInstance()->sendSubEmail($articles->getEmail(), $articles->getName(), $subject, $msg);
	$articles->setUtime(date('Y-m-d H:i:s'));
        $articles->save(); 
	}
	}
        }
       return false;	
	}
        
        public function sendSMS($mes){
            $phone = Number::clearPhone($this->getTelephone());
                                include_once('smsclub.class.php');
                                $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                                $sender = Config::findByCode('sms_alphaname')->getValue(); 
                                $use = $sms->sendSMS($sender, $phone, $mes);
                                wsLog::add('SMS to order: ' .$mes, 'SMS_' . @$sms->receiveSMS($use));
            return $use;
        }
        
        public function sendEmail($text){
            $view = Registry::get('View');
            $view->content = $text;
            $msg = $view->render('email/official/template.php');					
            SendMail::getInstance()->sendEmail($this->getEmail(), $this->getName(), 'Изменения статуса заказа', $msg);
            return true;
        }
        public function getFullName(){
            return $this->name.' '.$this->middle_name;
        }
    

}

