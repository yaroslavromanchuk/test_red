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
                        'customer' => [
                        'type' => 'hasOne',
                        'class' => self::$_customer_class,
                        'field' => 'customer_id'
            ]
        );
    }

    public function getTotal($param = false)
    { 
        $total_a = $total_o = 0.00;
        $articles = $this->getArticles();
        if ($articles->count())

            $bool = false;
        foreach ($articles as $item)
            if ($item['option_id'] > 0)
                $bool = true;
        if (!$bool)
            $total_o += $this->getDeliveryCost();

        foreach ($articles as $article) {
            $total_a += $article['price'] * $article['count'];
            if ($article['option_id'] > 0)
                $total_o += $article['option_price'] * $article['count'];
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
        if ($articles->count())
            foreach ($articles as $article)
                $total += $article->getCount();
        return $total;
    }
	public function getSkuCount()//количество SKU в заказе
    {
        $total = 0;
        $articles = $this->getArticles();
        if ($articles->count())
            foreach ($articles as $article){
             if($article->getCount() > 0) $total++;
				}
        return $total;
    }
	public function getArticlesEvent()//наличие в заказе товара с доп скидкой
    {
        $event = false;
        $articles = $this->getArticles();
        if ($articles->count()){
            foreach ($articles as $article){
			if(($article->getEventId() and $article->getEventSkidka()) or($article->getOptionId())) $event = true;
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
		if ($_SESSION['order_amount'] >= 750 and $_SESSION['basket_contacts']['delivery_type_id'] == 9){ return 0;}
		
		$d = wsActiveRecord::useStatic('DeliveryPayment')->findFirst(array('delivery_id' => @$_SESSION['basket_contacts']['delivery_type_id'], 'payment_id' => @$_SESSION['basket_contacts']['payment_method_id']));

		if (!$d) {
			$d = new DeliveryType($_SESSION['basket_contacts']['delivery_type_id']);
		}
		if (!$d || !$d->getId()){return Config::findByCode('delivery_cost')->getValue();}
                
		return $d->getPrice();
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
		//$this->getOneOneThree();// акция 1+1=3
		
        $to_pay = 0;
        foreach ($this->getArticles() as $article_rec) {
		if($article_rec->getCount() > 0){
            $price = $article_rec->getPerc($this->getAllAmount()); // цена товара со всеми скидками
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

	   //if($delivery){
             //  $deli = (float)$this->getDeliveryCost(); }else{
               $deli =(float)$this->getDeliveryCost();
               
         //  }// esli ukrpochta = true else false
	   
        if ($order_history_price < 0) {$order_history_price = 0;}

        if ($this->getDeposit()) {
            $price_2 = (float)$order_history_price + (float)$deli - (float)$this->getDeposit();
        } else {
            $price_2 = (float)$order_history_price + (float)$deli;
        }
		
		
        if($this->getBonus()){
            if($price_2 >= Config::findByCode('min_sum_bonus')->getValue()){
                $price_2 = $price_2 - (float)$this->getBonus();
            }
        }	


    if ($price_2 < 0) {
        $price_2 = 0; 
    }
		
	$price_2 = round($price_2, 2);
		
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
     * @param type $delivery - true можно не указывать
     * @param type $bonus - false можно не указывать
     * @return float
     */
    public function calculateOrderPrice2($use_deposit = true, $use_format = true, $delivery = true, $bonus = false)
    {
      $order_history_price = $this->getPriceWithSkidka();
	   
        $deli = $this->getDeliveryCost();
        
        if ($this->getDeposit() > 0){
            $price_2 = $order_history_price + $deli - $this->getDeposit();
        } else {
            $price_2 = $order_history_price + $deli; 
            
        }
	
        if($this->getBonus() > 0){
            if($price_2 >= Config::findByCode('min_sum_bonus')->getValue()){
                $price_2 = $price_2 - $this->getBonus();
            }
        }
        
        if ($price_2 < 0) { $price_2 = 0; }
		
	$price_2 = round($price_2, 2);
        
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

		$d_cost = $order->getDeliveryCost(); 
			
            $customer = new Customer($order->getCustomerId());
            
            $sum = (($order->getPriceWithSkidka() + $d_cost) - $order->getDeposit()) - $customer->getDeposit();
            $old = $order->getDeposit();
            $deposit = ($customer->getDeposit() + $order->getDeposit()) - ($order->getPriceWithSkidka() + $d_cost);
            if ($sum < 0) { $sum = 0; }
            if ($deposit < 0){ $deposit = 0;}
            
            $dedosit_to_order = ($order->getPriceWithSkidka() + $d_cost) - $sum;
			
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
     * Обновление суммы заказа и запсить скидки клиентской
     * @param type $delivery - не нужно указывать
     * сохраняется даннные заказа
     */
    public function reCalculate($delivery = true)
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

    static function canEdit($order_id)
    {
        $ws = Registry::get('Website');
        $order = new Shoporders($order_id);
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

}

