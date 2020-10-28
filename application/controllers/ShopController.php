<?php

class ShopController extends controllerAbstract
{

	public function indexAction()
	{

		$this->view->setArticlesTop(wsActiveRecord::useStatic('Shoparticlestop')->findAll());

		echo $this->render('shop/index.tpl.php');

	}

	public function articleokAction()
	{
		echo $this->render('shop/article-ok.tpl.php');
	}

/**
 * 
 * @param type $basket - корзина
 * @param type $del_cost - стоимость доставки
 * @return type
 */
	private function createBasketList($basket = false)
	{
		if ($basket === false){ $basket =  $this->basket; }
		$articles = [];
		foreach ($basket as $item) {
		if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId()) {
	$articles[] = [
			'id' => $article->getId(),
			'title' => $article->getTitle(),
			'count' => $item['count'],
			'option_id' => $item['option_id'],
			'option_price' => $item['option_price'],
			'size' => $item['size'],
			'color' => $item['color'],
			'artikul' => $item['artikul']?$item['artikul']:wsActiveRecord::useStatic('Shoparticlessize')->findByQuery(" SELECT code ". "FROM ws_articles_sizes ". "WHERE id_article = ".$item['article_id']." and id_size = ".$item['size']." and id_color = ".$item['color']." and `count` > 0 ")->at(0)->code,
			'category' => $item['category'],
			'price' => $article->getRealPrice(),
			'skidka_block' =>$item['skidka_block']?$item['skidka_block']:0
					];
		}
		}
		return $articles;
	}
        /**
         * страница оформления заказа
         * url = /shop-checkout-step2/
         */
public function basketcontactsAction()
        {
    
	
	if ($this->ws->getCustomer()->isBan()) { $this->_redir('ban'); }
        if ($this->ws->getCustomer()->isNoPayOrder()) { $this->_redir('nopay');}
	if (!$this->basket){ $this->_redir('index'); }
        //if ($this->ws->getCustomer()->isAdmin()) { }
            if($this->ws->getCustomer()->getId() != 8005 && $this->ws->getCustomer()->isBloskOrder()){ 
                $this->_redir('block_order');
                } 
        /*
	if ($this->ws->getCustomer()->isAdmin()) {
			if (!$this->ws->getCustomer()->hasRight('do_pay')) {
				die('Нету прав на заказ');
			}
		}*/

        $errors = [];
	$err_m = [];
        
		if ($_POST)
                    {
                    
                    unset($_SESSION['orders']);
			foreach ($_POST as &$value){ $value = stripslashes(trim($value)); }
                        
			$_SESSION['basket_contacts'] = $info = $_POST;
                        
			$info['kupon'] = $_SESSION['kupon'];
			//unset($_SESSION['kupon']);
            $error_email = 0;
            if (!$this->ws->getCustomer()->getIsLoggedIn()) {
                if (wsActiveRecord::useStatic('Customer')->findByEmail($info['email'])->count() != 0) {
                    $error_email = 1;
					 $errors['error'][] = $this->trans->get('Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь').'.';
                  $errors['Email'] = 'Email';
				}
                                
                   $count_order_magaz  = 3;               
            }else{
          $count_order_magaz  = $this->ws->getCustomer()->count_order_magaz;
      }
	
            $tel = substr(preg_replace('/[^0-9]/', '', $info['telephone']), -10);
            
         
            if (!Number::clearPhone($tel)) {
                $errors['error'][] = $this->trans->get('Введите телефонный номер');
                $errors['telefon'] = $this->trans->get('Телефон');
            }
            if(strlen($tel) != 10){
			$errors['error'][] = $this->trans->get('Неверный формат номера').".";
            $errors['telefon'] = $this->trans->get('Телефон');
			}
            for ($i = 0; $i < mb_strlen($tel); $i++) {
                if (mb_strpos('1234567890', mb_strtolower($tel[$i])) === false) {
                    $errors['error'][] = $this->trans->get('В номере должны быть только числа');
                    $errors['telefon'] = $this->trans->get('Телефон');
                }
            }
            $alredy = wsActiveRecord::useStatic('Customer')->findFirst(array(" phone1 LIKE  '%".$tel."%' "));
            if ($alredy) {
                if ($alredy->getUsername() != null and $alredy->getId() != $this->ws->getCustomer()->getId()) {
                    $errors['error'][] = $this->trans->get('Пользователь с таким номером телефона уже зарегистрирован в системе.<br /> Поменяйте телефон или зайдите как зарегистрированный пользователь').".";
                    $errors['telefon'] = $this->trans->get('Телефон');
                }
            }
            
           // foreach ($info as $k => $v){ $info[$k] = strip_tags(stripslashes($v)); }

            if (!$info['name'])
            {
                $errors[] = $this->trans->get('Имя');
            }

            if (isset($info['middle_name'])) {
                if (!$info['middle_name'])
                {
                    $errors[] = $this->trans->get('Фамилия');
                }
            }else{
                $errors[] = $this->trans->get('Фамилия');
            }

            if (!isset($info['delivery_type_id'])){$errors[] = $this->trans->get('Способ доставки');}
            if (!isset($info['payment_method_id'])){ $errors[] = $this->trans->get('Способ оплаты');}
            if (!isset($info['soglas'])){$errors[] = $this->trans->get('Согласие');}
            if (!isset($info['oznak'])){$errors[] = $this->trans->get('С условиями ознакомлен');}
            if (!isset($info['email']) || !isValidEmail($info['email'])){$errors[] = 'email';}
            
                if ($info['delivery_type_id'] == 3 and $info['payment_method_id'] == 1)
                {//magasiny
	$or_c = wsActiveRecord::useStatic('Shoporders')->findAll(["email LIKE  '".$info['email']."'", 'delivery_type_id'=>3, 'payment_method_id'=>1, 'status'=>3]);
            
		if($or_c->count() >= $count_order_magaz)
                    {
			$ord = [];
                        foreach($or_c as $r){ $ord[] = $r->id;}
			$err_m[] = 'По состоянию на '.date('d.m.Y').', в пункте выдачи интернет-магазина, находятся Ваши неоплаченные заказы № '. implode($ord, ",").'. В связи с этим, Вам ограничено оформление заказов в пункты самовывоза с оплатой наличными при получении, до оплаты доставленных заказов. Дополнительную информацию Вы можете получить в нашем Call-центре по номеру (044)224-40-00 Пн-Пн с 09:00-18:00.';
                    }
                }
                
                if ($info['delivery_type_id'] == 4)
                { //Укр почта
                    if (!$info['index']){$errors[] = $this->trans->get('Индекс');}
                    if (!$info['street']){$errors[] = $this->trans->get('Улица');}
                    if (!$info['house']){$errors[] = $this->trans->get('Дом');}
                    if (!$info['flat']){$errors[] = $this->trans->get('Квартира');}
                    if (!$info['obl']){$errors[] = $this->trans->get('Область');}
                    if (!$info['rayon']){$errors[] = $this->trans->get('Район');}
                    if (!$info['city']){$errors[] = $this->trans->get('Город');}
                    
                }elseif ($info['delivery_type_id'] == 9)
                { //Курьер
                    if (!$info['s_street']){$errors[] = $this->trans->get('Улица');}
                    if (!$info['house']){$errors[] = $this->trans->get('Дом');}
                    if (!$info['flat']){$errors[] = $this->trans->get('Квартира');}
                    
                }elseif ($info['delivery_type_id'] == 8 or $info['delivery_type_id'] == 16)
                { //Новая почта
                    if (!$info['city_np']){$errors[] = $this->trans->get('Город');}
                    if (!$info['sklad']){$errors[] = $this->trans->get('Склад НП');}
                    if (!$info['sklad_np']){$errors[] = 'Проблема с ид склада';}
                
                    if ($info['payment_method_id'] == 3)
                    {
			$info['delivery_type_id'] = 16;
                    }else{
			$info['delivery_type_id'] = 8;
                    }
                }elseif($info['delivery_type_id'] == 18){
                     if (!$info['branch']){$errors[] = $this->trans->get('Отделение Justin');}
                }

            if (!$errors and $error_email == 0 and !$err_m) {
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$articles = $this->createBasketList();
		$this->view->articles = $_SESSION['basket_articles'] = $articles;                        
		$kupon = 0;
			if($info['kupon']){
			$today_c = date("Y-m-d H:i:s");
			$ok_kupon = wsActiveRecord::useStatic('Other')->findFirst(array("cod"=>$info['kupon'], "ctime <= '".$today_c."' ", " '".$today_c."' <= utime"));
			if($ok_kupon){
			$kupon = $ok_kupon->cod;
			$info['kupon_price'] = $ok_kupon->skidka;
			}
					}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$check_c = [];   
		$this->basket_articles = $this->view->basket_articles = $articles;
				foreach ($this->basket_articles as $key => $article) {
					$itemcs = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(['id_article' => $article['id'], 'id_size' => $article['size'], 'id_color' => $article['color']]);
					if ($itemcs->id){
                                            if($itemcs->count == 0) {
						$check_c[$key] = $article; //массив товаров которые в этот момент успели купить другие
					}
                                        }else{
                                            $check_c[$key] = $article;
                                        }
				}
				if (!count($_SESSION['basket_articles'])){ $this->_redir('shop-checkout-step3'); }
                                
				//проверка товаров которых нет в наличии
				if (count($check_c) > 0) {
					foreach ($check_c as $key => $val) {
						$basket = $_SESSION['basket'];
						if ($basket) {
							$_SESSION['basket'] = [];
							foreach ($basket as $keyb => $value){ if ($key != $keyb){$_SESSION['basket'][] = $value;} }
						}
						unset($_SESSION['basket_articles'][$key]);
					}
					$this->view->articles = $check_c;
					echo $this->render('shop/basket-message.tpl.php');
				}else{
					$curdate = Registry::get('curdate');
					$order = new Shoporders();

					$mas_adres = [];
					$city ='';
					$sklad = '';
                                        
					if (isset($info['index']) and strlen($info['index']) > 0) {
						$mas_adres[] = $info['index'];
					}
					if (isset($info['obl']) and strlen($info['obl']) > 0) {
						$mas_adres[] = $info['obl'];
					}
					if (isset($info['rayon']) and strlen($info['rayon']) > 0) {
						$mas_adres[] = $info['rayon'];
					}
					if ($info['delivery_type_id'] == 8 or $info['delivery_type_id'] == 16) { //Новая почта
						if (isset($info['city_np']) and strlen($info['city_np']) > 0) {
							$mas_adres[] = 'г. ' . $info['city_np'];
							$city = $info['city_np'];
						}
					}else if($info['delivery_type_id'] == 9){  //kurer
							$mas_adres[] = 'г. Киев';
							$city ='Киев';

					}elseif(isset($info['city']) and strlen($info['city']) > 0) {

						$mas_adres[] = 'г. ' . $info['city'];
						$city = $info['city'];

					}
					if($info['delivery_type_id'] == 9){  //kurer
					if (isset($info['s_street']) and strlen($info['s_street']) > 0) {
							$mas_adres[] = $info['s_street'];
						}

					}else if (isset($info['street']) and strlen($info['street']) > 0) {
						$mas_adres[] = 'ул.' . $info['street'];
					}
					if (isset($info['house']) and strlen($info['house']) > 0) {
						$mas_adres[] = 'д.' . $info['house'];
					}
					if (isset($info['flat']) and strlen($info['flat']) > 0) {
						$mas_adres[] = 'кв.' . $info['flat'];
					}
					if ($info['delivery_type_id'] == 8 or $info['delivery_type_id'] == 16) { //Новая почта
						if (isset($info['sklad']) and strlen($info['sklad']) > 0) {
							$mas_adres[] = 'НП: ' . $info['sklad'];
							$sklad = $info['sklad'];
						}
                                        }elseif($info['delivery_type_id'] == 18){
                                            if (isset($info['branch']) and strlen($info['branch']) > 0) {
                                                $j = JustinDepartments::getDepartment($info['branch']);
							$mas_adres[] = 'Justin: ' .  $j;
							$sklad =  $j;
						}
                                        }


					$info['address'] = implode(', ', $mas_adres);

					$info['l_name'] = $info['name'].' '.$info['last_name'];

					$phone = '38'.substr(preg_replace('/[^0-9]/', '', $info['telephone']), -10);
                                        
					$data = [
                                                'shop_id' => 1,
						'status' => 100,
						'date_create' => $curdate->getFormattedMySQLDateTime(),
						'company' => isset($info['company']) ? $info['company'] : '',
						'name' => $info['l_name'],
						'middle_name' => $info['middle_name'],
						'address' => $info['address'],
						'index' => $info['index'],
						'street' => $info['delivery_type_id'] == 9 ? $info['s_street'] : $info['street'],
						'house' => $info['house'],
						'flat' => $info['flat'],
						'pc' => $info['pc'],
						'city' => $city,
						//'city' =>  isset($info['city_np']) ? $info['city_np'] : $info['city'],
						'obl' => isset($info['obl']) ? $info['obl'] : '',
						'rayon' => $info['rayon'],
						'sklad' => $sklad,
						'telephone' => $phone,
						'email' => $info['email'],
						'comments' => isset($info['comments']) ? $info['comments'] : '',
						//'delivery_cost' =>Shoporders::getDeliveryPrice(),
						'delivery_type_id' => $info['delivery_type_id'],
						'payment_method_id' => $info['payment_method_id'],
                                                'fop_id' => DeliveryPayment::getFop($info['delivery_type_id'], $info['payment_method_id']),
						'liqpay_status_id' => 1,
						//'amount' => $_SESSION['sum_to_ses'] ? $_SESSION['sum_to_ses'] : 0,
						'soglas' => $info['soglas'] ? 1 : 0,
						'oznak' => $info['oznak'] ? 1 : 0,
						'call_my' => $info['callmy'] ? 1 : 0,
						'quick' => 0,
						'kupon' => $info['kupon']?$info['kupon']:'',
						'kupon_price' => $info['kupon_price']?$info['kupon_price']:'',
                                                'track' => isset($_COOKIE["track"])?$_COOKIE["track"]:isset($_COOKIE['utm_email_track'])?$_COOKIE['utm_email_track']:isset($_COOKIE['utm_source'])?$_COOKIE['utm_source']:'',
                                               // 'skidka' =>  $skidka
					];

					$order->import($data);
					$order->save();
                                        
                                        
                        if (!$order->getId()) {
                            $this->_redir('basket');
                        }
                        
                         $this->set_customer($order);
                        
                        $event_skidka_klient = 0;
			$event_skidka_klient_id = 0;
                        
                       $events = EventCustomer::getEvents($this->ws->getCustomer()->getId());
                       
					if($events){
					$event_skidka_klient = $events->discont;
					$event_skidka_klient_id = $events->event_id;
                                        $this->view->event = $event_skidka_klient;
					if($events->disposable == 1){
                                            $ev = new EventCustomer($events->id);
                                            $ev->setSt(5);
                                            $ev->save();
						}

					}
                                        
                                        
                                     
					foreach ($this->basket_articles as $article) {
						$item = new Shoparticles($article['id']);
						$itemcs = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article['id'], 'id_size' => $article['size'], 'id_color' => $article['color']));
						if ($itemcs->getCount() > 0) {
							$item->setStock($item->getStock() - $article['count']);
							$item->save();

							$itemcs->setCount($itemcs->getCount() - $article['count']);
							$itemcs->save();
                                                        $data = $article;
                                                        unset($data['id']);
                                                        $data['order_id'] = $order->getId();
							$data['article_id'] = $article['id'];
                                                        $data['old_price'] = $item->getOldPrice();
                                                        $data['artikul'] = $article['artikul'];
                                                        $data['ucenka'] = $item->ucenka;
                                                        $data['event_skidka'] = $event_skidka_klient;
                                                        $data['event_id'] = $event_skidka_klient_id;
                                                        
							$a = new Shoporderarticles();
							$a->import($data);
							$a->save();
						}else {
							$article['count'] = 0;
							$article['title'] = $article['title'] . ' (нет на складе)';
							$data = $article;
							$data['article_id'] = $data['id'];
							unset($data['id']);
                                                        $data['order_id'] = $order->getId();
                                                        
                                                        $a = new Shoporderarticles();
							$a->import($data);
							$a->save();
						}
					}
                                        
                        
                        $order->reCalculate();
                        
                        $_SESSION['order_amount'] = $order->getAmount(); 
                        
                        $order->setDeliveryCost($order->getDeliveryPrice());
                        $order->reCalculate();
                        
                        if (isset($_SESSION['cart']['coin']) and $this->ws->getCustomer()->getSummCoin('active')){
                            $coin = $this->ws->getCustomer()->getAllCoin('active');
                            $total_price = $order->getAmount();
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
        if($order ==0) break; 
    }
    $this->view->coin = $scoin;  
    $order->setBonus($scoin);
    $order->setAmount($total_price);
    $order->save();
    OrderHistory::newHistory($order->customer_id, $order->id,' Клиент использовал бонус ('.$scoin.') redcoin. ', '');
    
    //perevod v novu pochtu esly polnosty oplachen depositom
					if($order->getDeliveryTypeId() == 16 and $order->getAmount() == 0){
					$order->setDeliveryTypeId(8);
					$info['delivery_type_id'] = 8;
					$order->setPaymentMethodId(8);
                                         $order->setFopId(DeliveryPayment::getFop(8, 8));
					$info['payment_method_id'] = 8;
                                        }elseif($order->getDeliveryTypeId() == 4 and $order->getAmount() == 0){
                                            $order->setPaymentMethodId(8);
                                            $order->setFopId(DeliveryPayment::getFop(4, 8));
					$info['payment_method_id'] = 8;
                                        }
					//perevod v novu pochtu esly polnosty oplachen depositom                
                        }elseif(isset($_SESSION['cart']['deposit']) and $order->getAmount() > 0 and $this->ws->getCustomer()->getDeposit()){
                                            
					$total_price = $order->getAmount();
					
					$dep = $this->ws->getCustomer()->getDeposit();
                                        
                                        if(($total_price - $dep) < 0){
                                            $dep -= $total_price;
                                            $deposit = $total_price;
                                            $total_price = 0;
                                        }else{
                                            $total_price -= $dep;
                                            $deposit = $dep;
                                            $dep = 0;
                                        }
					$order->setDeposit($deposit);
                                        $order->setAmount($total_price);
					$order->save();
                                        
					//perevod v novu pochtu esly polnosty oplachen depositom
					if($order->getDeliveryTypeId() == 16 and $order->getAmount() == 0){
					$order->setDeliveryTypeId(8);
					$info['delivery_type_id'] = 8;
					$order->setPaymentMethodId(8);
                                         $order->setFopId(DeliveryPayment::getFop(8, 8));
					$info['payment_method_id'] = 8;
                                        }elseif($order->getDeliveryTypeId() == 4 and $order->getAmount() == 0){
                                            $order->setPaymentMethodId(8);
                                            $order->setFopId(DeliveryPayment::getFop(4, 8));
					$info['payment_method_id'] = 8;
                                        }
					//perevod v novu pochtu esly polnosty oplachen depositom
					
					$customer = new Customer($this->ws->getCustomer()->getId());
					$customer->setDeposit($dep);
					$customer->save();
OrderHistory::newHistory($customer->getId(),$order->getId(),' Клиент использовал депозит ('.$order->getDeposit().') грн. ','Осталось на депозите "' . $customer->getDeposit() . '"');
                        
				$no = '-';
				DepositHistory::newDepositHistory($customer->getId(), $customer->getId(), $no, $order->getDeposit(), $order->getId());
                                
                                $this->view->deposit = $deposit;
				}
    unset($_SESSION['cart']);
  /*                              
if($_SESSION['bonus'] and $this->ws->getCustomer()->getBonus() > 0 and $order->getAmount() >= Config::findByCode('min_sum_bonus')->getValue())
                    {
		$total_price = $order->getAmount();
		$bon = $this->ws->getCustomer()->getBonus() - $total_price;
                if ($bon <= 0){$bon = $this->ws->getCustomer()->getBonus();}else {$bon = $total_price;}

			//$_SESSION['bonus'] = $bon;
				$order->setBonus($bon);

				$customer = new Customer($this->ws->getCustomer()->getId());
				$customer->setBonus($customer->getBonus() - $bon);
				$customer->save();
		OrderHistory::newHistory($customer->getId(), $order->getId(), ' Клиент использовал бонус ('.$bon.') грн. ', ' ');
                $order->save();
				unset($_SESSION['bonus']);
				}*/
                                
                                
                    $payment_method_id = $info['payment_method_id'];// dlya onlayn oplat

		$this->basket = $this->view->basket = $_SESSION['basket'] = [];
                if($this->ws->getCustomer()->getCart()){
                    $this->ws->getCustomer()->getCart()->clearCart();
                }
		$this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'] = [];
                                        

	//meestexpres
	if($order->getDeliveryTypeId() == 8 or $order->getDeliveryTypeId() == 16){
               $new_np = Shopordersmeestexpres::newOrderNp($order->getId(), $info['cityx'], $info['sklad_np']);
               $order->setMeestId($new_np);
                $order->save();
        }elseif($order->getDeliveryTypeId() == 18){
             $new_np = JustinDepartmentToOrder::newOrderJastin($order->getId(), $info['city_justin'], $info['branch']);
              // $order->setMeestId($new_np);
              //  $order->save();
        }
					//exit meestexpres
//$basket = $order->getArticles()->export();

                                        
					//$this->view->articles = $this->createBasketList($basket);
					$this->view->order = $order;
            // otpravka email
	if(!$this->ws->getCustomer()->isBlockEmail()) {
	SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), Config::findByCode('email_order_subject')->getValue(), $this->render('email/basket.tpl.php'));
			}
                                        
	if ($order->getId()) {

	OrderHistory::newOrder($this->ws->getCustomer()->getId(), $order->getId(), ($order->getAmount()+$order->getDeposit()), $order->getArticlesCount());

        if($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->isAdmin() and $this->ws->getCustomer()->getTelegram()){
	$message = 'Ваш заказ № '.$order->getId().' оформлен. Сумма к оплате '.$order->calculateOrderPrice2().' грн. Телефон (044) 224-40-00';
	 Telegram::sendMessageTelegram($this->ws->getCustomer()->getTelegram(), $message);
	}else{
		$phone = Number::clearPhone($order->getTelephone());

		require_once('alphasms/smsclient.class.php');
		$sms = new SMSClient(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue(), Config::findByCode('sms_key')->getValue());
		$id = $sms->sendSMS(Config::findByCode('sms_alphaname')->getValue(), $phone, $this->trans->get('Vash zakaz').' № ' . $order->getId() . ' '.$this->trans->get('Summa').' ' . $order->calculateOrderPrice2() . ' grn. tel. (044) 224-40-00');

		if($sms->hasErrors()){
                    $res = $sms->getErrors();
                    }else{
                        $res = $sms->receiveSMS($id);
                        }
		wsLog::add('Order:'.$order->id.' to SMS: '.$phone.' - '.$res, 'SMS_' . $res);

						}

		if (!$order->getCustomerId()) {
			$usr = Customer::findByUsername($order->getEmail());
			if ($usr->getId()) {
			$order->setCustomerId($usr->getId());
			$order->save();
					}
					}

					}
                                        
   if($order->getDeliveryTypeId() == 16){
  $find_count_orders_by_user = wsActiveRecord::useStatic('Shoporders')->count(array('customer_id' => $order->getCustomerId(), 'delivery_type_id'=>16));
        
  if($find_count_orders_by_user == 1){
                     $remark = new Shoporderremarks();
				$com = [
					'order_id' => $order->getId(),
					'date_create' => $curdate->getFormattedMySQLDateTime(),
					'remark' => "Первый заказ наложкой!!! Не отправлять до уточнения деталей"
					];
                                        
				$remark->import($com);
				$remark->save();               
    }elseif(wsActiveRecord::useStatic('Shoporders')->count(array('customer_id' => $order->getCustomerId(), 'delivery_type_id' => 16, 'status'=> 6)) > 3){
        $remark = new Shoporderremarks();
				$com = [
					'order_id' => $order->getId(),
					'date_create' => $curdate->getFormattedMySQLDateTime(),
					'remark' => "Не отправлять пока не заберет заказы!"
					];
                                        
				$remark->import($com);
				$remark->save(); 
    }       
       
                                
                                }


					//dla finishnoy stranicu

					$_SESSION['order'] = [];
					$_SESSION['order']['id'] = $order->getId();
					$_SESSION['list_articles_order'] = $order->getListArticlesOrder();
                                       
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if($order->getPaymentMethodId() == 7){
			LiqPayHistory::newHistory($order->getId(), 1, 'create');
                        //$this->view->liqpay = ['order'=>$order->getId(), 'amount'=>$order->calculateOrderPrice2(true, false)];
                        $this->_redirect(SITE_URL . '/liqpay/id/'.$order->id.'/');
                        //$this->_redir('liqpay/?id=');//finish
			}
                        
                        
                        
			if (in_array($order->getPaymentMethodId(), array(4, 5, 6))) {
                            /*
		1 = Webmoney
		6 = MoneXy
		12 = EasyPay
		15 = NSMEP
		17 = Webmoney Terminal
		21 = PaymasterCard
		49 = Приват 24
		19 = LiqPay
		23 = Київстар
		2 = x20 WebMoney моб. платежи
		22 - Терминалы через кнопку пеймастера

		18 = test
		*/
                            switch ($order->getPaymentMethodId()){
                                case 4: $paymaster = 21; break;
                                case 5: $paymaster = 1; break;
                                case 6: $paymaster = 49; break;
                            }

						$order_id = $order->getId();
						$order_amount = $order->calculateOrderPrice2(true, false);

						$pay_data['LMI_MERCHANT_ID'] = 2285;
						$pay_data['LMI_PAYMENT_AMOUNT'] = $order_amount;//str_replace(" ","",$order_amount);
						$pay_data['LMI_PAYMENT_NO'] = $order_id;
						$pay_data['LMI_PAYMENT_DESC'] = 'Оплата за заказ № '.$order_id;
		
						$pay_data['LMI_PAYMENT_SYSTEM'] = $paymaster;
						$pay_data['LMI_SIM_MODE'] = 1;
						$pay_data['LMI_HASH'] = hash('sha256', $pay_data['LMI_MERCHANT_ID'].$pay_data['LMI_PAYMENT_NO'].$pay_data['LMI_PAYMENT_AMOUNT'].'joiedevivre');
						$pay_data['LMI_HASH'] = strtoupper($pay_data['LMI_HASH']);

						$LMI_MERCHANT_ID		= mysql_real_escape_string($pay_data['LMI_MERCHANT_ID']);
						$LMI_PAYMENT_AMOUNT		= mysql_real_escape_string($pay_data['LMI_PAYMENT_AMOUNT']);
						$LMI_PAYMENT_NO			= mysql_real_escape_string($pay_data['LMI_PAYMENT_NO']);
						$LMI_PAYMENT_DESC		= mysql_real_escape_string($pay_data['LMI_PAYMENT_DESC']);
						$LMI_PAYMENT_SYSTEM		= mysql_real_escape_string($pay_data['LMI_PAYMENT_SYSTEM']);
						$LMI_SIM_MODE			= mysql_real_escape_string($pay_data['LMI_SIM_MODE']);
						$LMI_HASH				= mysql_real_escape_string($pay_data['LMI_HASH']);

	wsActiveRecord::query("INSERT INTO pay_send ( LMI_MERCHANT_ID, LMI_PAYMENT_AMOUNT, LMI_PAYMENT_NO, LMI_PAYMENT_DESC, LMI_PAYMENT_SYSTEM, LMI_SIM_MODE, LMI_HASH, pid ) VALUES ( '".$LMI_MERCHANT_ID."', '".$LMI_PAYMENT_AMOUNT."', '".$LMI_PAYMENT_NO."', '".$LMI_PAYMENT_DESC."', '".$LMI_PAYMENT_SYSTEM."', '".$LMI_SIM_MODE."', '".$LMI_HASH."', '".$payment_method_id."' ) " );

						//$this->view->pay_data = $pay_data;
						$_SESSION['pay'] = $pay_data;
						//echo $this->render('payment/index.tpl.php');
					}

					 $this->_redirect(SITE_URL . '/ordersucces/');
						//$this->_redir('ordersucces');//finish

				}// vse tovary est v nalichii

			}//not error
                        
            $this->basket_contacts = $this->view->basket_contacts = $_SESSION['basket_contacts'];
        }//$_POST tst dannye - otpravlena forma
        $this->view->css = [
            '/css/cart/style.css',
        '/css/ui/jquery-ui.min.css',
             '/js/select2/css/select2.min.css',
        ];
        $this->view->scripts = [
            '/js/np/np_api.js',
            '/css/ui/jquery-ui.min.js',
            '/js/call/jquery.mask.js',
            '/css/cart/valid_cart.js',
             '/lib/select2/js/select2.min.js'
        ];
		$this->view->err_m = $err_m;
		$this->view->errors = $errors;

		 echo $this->render('shop/basket-step2.tpl.php');

}
public function justinAction()
        {
    if($this->post->metod == 'city'){
        $dep = wsActiveRecord::useStatic('JustinCities')->findAll(['active'=>1]);
    $rdep = [];
    $i=1;
    $rdep[0]['id'] = '';
    $rdep[0]['label'] = 'Выберите город';
    foreach ($dep as $d) {
      $rdep[$i]['id'] = $d->uuid;
      $rdep[$i]['text'] = $d->getName();
      $i++;
    }
    die(json_encode($rdep));
    }elseif($this->post->metod == 'search_depart'){
    $dep = wsActiveRecord::useStatic('JustinDepartments')->findAll(['city_uuid'=>$this->post->id]);
    $rdep = [];
    $i=0;
    foreach ($dep as $d) {
      $rdep[$i]['id'] = $d->branch;
      $rdep[$i]['text'] = $d->depart_descr.' обл. '.$d->address;
      $i++;
    }
    //{id:args.id, text: args.text}
   // echo $this->post->id;
    die(json_encode($rdep));
    
}
    die();
        }
//2517
	public function basketoverviewAction()
	{
		if ($this->ws->getCustomer()->isBan()) {
			$this->_redir('ban');
		}

		if ($this->ws->getCustomer()->isNoPayOrder()) {
			$this->_redir('nopay');
		}

		if (!$this->basket){$this->_redir('index');}

		$articles = $this->createBasketList();

		$_SESSION['basket_articles'] = $articles;
		$this->view->articles = $articles;
		echo $this->render('shop/basket-step3.tpl.php');
	}

//2517
	public function basketorderAction()
	{
                                echo 'Ваш заказ принят';
	}

        /**
         * оформление быстрого заказа
         */
	public function basketorderquickAction()
	{
		if ($this->post){
			//____________________________start_check_inputs_________________________________________

			//$_SESSION['basket_contacts'] = $this->post;

			// check for errors
			$errors = [];
                      

			$info = $this->post;//$_SESSION['basket_contacts'];
                        //  l($info);
                         // die();
                        
			//$_SESSION['basket_contacts']['comments'] = $info['comment'];

			$error_email = 0;
			if (!$this->ws->getCustomer()->getIsLoggedIn() and wsActiveRecord::useStatic('Customer')->findByEmail($info['email'])->count() != 0) {
				$error_email = 1;
				$errors['error']['email'] = $this->trans->get('Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь').'.<br>';
			}
			$tel = Number::clearPhone(trim($info['telephone']));
			$tel = preg_replace('/[^0-9]/', '', $info['telephone']);
			$tel = substr($tel, -10);
			$allowed_chars = '1234567890';
			if (!Number::clearPhone($tel)) {
				$errors['error']['telephone'] = $this->trans->get('Введите телефонный номер');
			}
			for ($i = 0; $i < mb_strlen($tel); $i++) {
				if (mb_strpos($allowed_chars, mb_strtolower($tel[$i])) === false) {
					$errors['error']['telephone'] = $this->trans->get('В номере должны быть только числа');
				}
			}
			$alredy = wsActiveRecord::useStatic('Customer')->findFirst(array(" phone1 LIKE  '%".$tel."%' "));
			if ($alredy and $alredy->getUsername() != null and $alredy->getId() != $this->ws->getCustomer()->getId()) {
				$errors['error']['telephone'] = $this->trans->get('Пользователь с таким номером телефона уже зарегистрирован в системе.<br /> Поменяйте телефон или зайдите как зарегистрированный пользователь').".";
			}
                        
			foreach ($info as $k => $v){
                            $info[$k] = strip_tags(stripslashes($v));
                        
                        }
			foreach ($_POST as &$value) {
				$value = stripslashes(trim($value));
                        }
			if (!$info['name'])
                        {
                            $errors['error']['name'] = $this->trans->get('Неверное имя');   
                        }
			if (!$info['email'] || !isValidEmail($info['email']))
                        {
                            $errors['error']['email'] = $this->trans->get('Неверный email'); 
                        }
			if (!Number::clearPhone(trim($info['telephone'])) || !$info['telephone'] || !isValidTel)
                        {
                            $errors['error']['telephone'] = $this->trans->get('Неверный телефон'); 
                        }
			if ($this->ws->getCustomer()->isBan())
                        {
                            $errors['error']['ban'] = $this->trans->get('Доступ заблокирован');
                        }
			if ($this->ws->getCustomer()->isBlockQuick())
                        {
                            $errors['error']['block_quick'] = $this->trans->get('Заблокировано оформление быстрых заказов');  
                        }
			if ($this->ws->getCustomer()->isNoPayOrder())
                        {
                            $errors['error']['block'] = $this->trans->get('Доступ заблокирован');   
                        }
                       // if ($this->ws->getCustomer()->isAdmin()) {
                            if($this->ws->getCustomer()->getId() != 8005 && $this->ws->getCustomer()->isBloskOrder()){
                               $errors['error']['block'] =  $this->render('shop/block_order.php');
                                //$this->_redir('block_order');
                                }
                           // }
			if (!isset($_POST['size']) or $_POST['size'] == 0)
                        {
                            $errors['error']['size'] = $this->trans->get('Выберите размер');   
                        }
			if (!isset($_POST['color']) or $_POST['color'] == 0)
                        {
                            $errors['error']['color'] = $this->trans->get('Выберите цвет');   
                        }
			//________________________end_check_inputs_________________________________________
                        
			if (!count($errors)){
				//____________________________start_add_to_basket____________________________________
$item = new Shoparticles((int)$_POST['id']);
$itemcs = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(['id_article' => $item->getId(), 'id_size' => (int)$_POST['size'], 'id_color' => (int)$_POST['color']]);
if($item->id and $itemcs->id){
    $this->view->ok = true;
}else{
    die(json_encode(array('result'=>'error', 'message'=>$errors['error']['error_articles']='Ошибка с товаром, попробуйте еще раз.')));
    exit();
}
				//________________________start_added_fields____________________________________________
				$order = new Shoporders();
				$phone = preg_replace('/[^0-9]/', '', $info['telephone']);
				$phone = substr($phone, -10);
				$phone = '38'.$phone;
				$data = array(
					'status' => 100,
                                        'shop_id' => isset($info['shop_id'])?$info['shop_id']:1,
                                        'is_admin' => $this->ws->getCustomer()->isAdmin()?1:0,
					'date_create' => date("Y-m-d H:i:s"),
					'name' => $info['name'],
					'middle_name' => isset($info['middle_name']) ? $info['middle_name'] : '',
                                        'index' => @$info['index'],
					'street' => @$info['street'],
					'house' => @$info['house'],
					'flat' => @$info['flat'],
					'pc' => @$info['pc'],
					'city' => isset($info['city']) ? $info['city'] : '',
					'obl' => isset($info['obl']) ? $info['obl'] : '',
					'rayon' => @$info['rayon'],
					'sklad' => @$info['sklad'],
					'telephone' => $phone,
					'email' => $info['email'],
					'comments' => isset($info['comments']) ? $info['comments'] : '',
					'delivery_cost' => 0,
					'delivery_type_id' =>  '',
					'payment_method_id' =>  1,
					//'amount' => $_SESSION['order_amount'],
					'soglas' => 1,
					'oznak' => 1,
					//'deposit' => @$_SESSION['deposit'],
					//'call_my' => @$info['callmy'] ? 1 : 0,
					'quick' => 1,
					'from_quick' => 1,
                                        'track' => isset($_COOKIE["track"])?$_COOKIE["track"]:isset($_COOKIE['utm_email_track'])?$_COOKIE['utm_email_track']:isset($_COOKIE['utm_source'])?$_COOKIE['utm_source']:'',
				);
				$order->import($data);
				//________________________end_added_fields_____________________________________________
				$lastnq = wsActiveRecord::findByQueryFirstArray('SELECT MAX(quick_number) as quick_number FROM `ws_orders`');
				$order->setQuickNumber(++$lastnq['quick_number']);
				$order->save();
				$this->set_customer($order);

                                 /*       $event_skidka_klient = 0;
					$event_skidka_klient_id = 0;
                                        $events = EventCustomer::getEvents($this->ws->getCustomer()->getId());
					if($events){
					$event_skidka_klient = $events->discont;
					$event_skidka_klient_id = $events->event_id;
                                        $this->view->event = $event_skidka_klient;
					if($events->disposable == 1){
                                            $ev = new EventCustomer($events->id);
                                            $ev->setSt(5);
                                            $ev->save();
						}

					}
                                        
*/
$option_id = 0;
$option_price =0;
$option = $item->getOptions();
if($option){
                    switch ($option->type){
                        case 'final':
                         $option_id = $option->id;
                         $option_price = $item->price - ($item->price * ($option->value/100));  
                           break;
                        case 'dop':
                           //  if($item->price > $option->min_summa){
                            $option_id = $option->id;
                             $option_price = $item->price - ($item->price * ($option->value/100));
                            // }
                            break;
                        default: $option_id = 0; break;
                    }
                    
                }
$article = [
    'order_id' => $order->getId(),
    'article_id' => $item->getId(),
    'title' => $item->getTitle(),
    'count' => 1,
    'price' => $item->getPrice(),
    'old_price' =>$item->getOldPrice(),
    'ucenka' =>$item->ucenka,
    'option_id' => $option_id,
    'option_price' => $option_price,
    'size' => $itemcs->id_size,
    'color' => $itemcs->id_color,
    'artikul' => $itemcs->code,
    'skidka_block' => $item->skidka_block,
   // 'event_skidka' => $event_skidka_klient,
   // 'event_id' => $event_skidka_klient_id
];

$a = new Shoporderarticles();    
	if ($itemcs->getCount() > 0) {
		$item->setStock($item->getStock() - 1);
		$item->save();
    $itemcs->setCount($itemcs->getCount() - 1);
    $itemcs->save(); 
		}else{
		$article['count'] = 0;
		$article['title'] = $article['title'] . ' (нет на складе)';			
	}   
    $a->import($article);
    $a->save();                         
    $order->reCalculate(); 
				//____________________send_email_________________
				if(!$this->ws->getCustomer()->isBlockEmail()){
                                $this->view->order = $order;
                                $msg = $this->view->render('email/basket-order-quick.tpl.php');
				$subject = $this->trans->get('Принята заявка').' № '.$order->getQuickNumber();
                                    EmailLog::add($subject, $msg, 'new_order', $order->getCustomerId(),  $order->getId()); //сохранение письма отправленного пользователю
				SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg);
                                }
				//____________________send_email________________
if($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->getTelegram()){
$message = 'Ваша заявка № '.$order->getQuickNumber().' прийнята. Ожидайте звонок менеджера для уточнения деталей доставки и оплаты.';
 Telegram::sendMessageTelegram($this->ws->getCustomer()->getTelegram(), $message);
}else{
				//____________________send_sms__________________
				require_once('alphasms/smsclient.class.php');
				$phone = Number::clearPhone($order->getTelephone());
				$sms = new SMSClient(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue(), Config::findByCode('sms_key')->getValue());
				$id = $sms->sendSMS(Config::findByCode('sms_alphaname')->getValue(), $phone, $this->trans->get('Vasha zajavka').' №' . $order->getQuickNumber() .' '.$this->trans->get('prinjata. Ozhidajte zvonok menedzhera'));
				if($sms->hasErrors()){
                                    $res = $sms->getErrors(); 
                                }else{
                                    $res = $sms->receiveSMS($id);
                                    }
		wsLog::add('Quick:'.$order->getQuickNumber().' to SMS: '.$phone.' - '.$res, 'SMS_' . $res);
				//____________________send_sms__________________
}

        $this->view->order = $order;
	OrderHistory::newOrder($order->getCustomerId(), $order->getId(), $order->calculateOrderPrice(), $order->getArticlesCount());

die(json_encode(array('result'=>'send', 'message'=>$this->render('shop/quick-order-result.php'))));
			}else{
		die(json_encode(array('result'=>'error', 'message'=>$errors)));
		}
		}
		exit;
	}
        /**
 * Присвоение заказу пользователя або регистрация нового
 * @param type $order - заказ
 */
	private function set_customer($order = null)
	{
		if (!$this->ws->getCustomer()->getIsLoggedIn()) 
                    {
		$allowedChars = 'abcdefghijklmnopqrstuvwxyz'
			. 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
			. '0123456789';
		$newPass = '';
		$allowedCharsLength = strlen($allowedChars);
                
		while (strlen($newPass) < 8)
			$newPass .= $allowedChars[rand(0, $allowedCharsLength - 1)];
                
		if (!wsActiveRecord::useStatic('Customer')->findFirst(array('username' => $order->getEmail()))) {

			/*$em = iconv_substr($order->getEmail(), 0, 4, 'UTF-8');
				if($em == 'miss'){
	SendMail::getInstance()->sendEmail('php@red.ua', 'Yaroslav', 'Создан новый акаунт МИСС', 'Email: '.$order->getEmail().'. Заказ: '.$order->Id());
				}*/

	$customer = new Customer();
	//if (isset($_SESSION['parent_id']) and $_SESSION['parent_id'] != 0){ $customer->setParentId($_SESSION['parent_id']); }
                                
				$customer->setUsername($order->getEmail());
				$customer->setPassword(md5($newPass));
				$customer->setCustomerTypeId(1);
				//$customer->setCompanyName($order->getCompany());
				$customer->setFirstName($order->getName());
				$customer->setMiddleName($order->getMiddleName());
				$customer->setEmail($order->getEmail());
				$customer->setPhone1(Number::clearPhone($order->getTelephone()));
				$customer->setCity($order->getCity());
				$customer->setObl($order->getObl());
				$customer->setAdress($order->getAddress());
				$customer->setRayon($order->getRayon());
				$customer->setIndex($order->getIndex());
				$customer->setStreet($order->getStreet());
				$customer->setHouse($order->getHouse());
				$customer->setFlat($order->getFlat());
				$customer->save();
                                
                                $coin = new RedCoin();
$coin->import(
        [
            'coin' => 50,
            'customer_id' => $customer->id,
            'status'=>2,
            'order_id_add' => 0,
            'date_add' => date("Y-m-d"),
            'date_active' => date("Y-m-d"),
            'date_off' => date("Y-m-d", strtotime("now +30 days"))
            ]
        );
$coin->save();

BonusHistory::add($customer->id, 'Зачислено', 50, 0);
                                
                                
				$order->setCustomerId($customer->getId());
				$order->save();
                                
                                $subscriber = new Subscriber();
                                $subscriber->setSegmentId(1);
                                $subscriber->setCustomerId($customer->getId());
				$subscriber->setName($order->getName());
				$subscriber->setEmail($order->getEmail());
				$subscriber->setConfirmed(date('Y-m-d H:i:s'));
				$subscriber->setActive(1);
				$subscriber->save();

				$this->view->login = $order->getEmail();
				$this->view->pass = $newPass;
				$subject = 'Создан акаунт';
				$msg = $this->render('email/new-customer.tpl.php');
                                  EmailLog::add($subject, $msg, 'new_customer', $customer->getId() );
				SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg);

				$customer = $this->ws->getCustomer();
				$res = $customer->loginByEmail($order->getEmail(), $newPass);
                                
				if (!isset($_SESSION['user_data'])){$_SESSION['user_data'] = [];}
                                
				$_SESSION['user_data']['login'] = $order->getEmail();
				$_SESSION['user_data']['password'] = $newPass;
				if ($res) {
					$this->website->updateHashes();
				}
			}
		} else {
			$order->setCustomerId($this->ws->getCustomer()->getId());
                        $order->setSkidka($this->ws->getCustomer()->getDiscont());
                        
			if (!$this->ws->getCustomer()->getMiddleName()) {
				$this->ws->getCustomer()->setMiddleName($order->getMiddleName());
			}
			if (!$this->ws->getCustomer()->getFirstName()) {
				$this->ws->getCustomer()->setFirstName($order->getName());
			}
			if (!$this->ws->getCustomer()->getPhone1()) {
				$this->ws->getCustomer()->setPhone1(Number::clearPhone($order->getTelephone()));
			}
			if (!$this->ws->getCustomer()->getCity()) {
				$this->ws->getCustomer()->setCity($order->getCity());
			}
			if (!$this->ws->getCustomer()->getAdress()) {
				$this->ws->getCustomer()->setAdress($order->getAddress());
			}
			if (!$this->ws->getCustomer()->getObl()) {
				$this->ws->getCustomer()->setObl($order->getObl());
			}
			if (!$this->ws->getCustomer()->getRayon()) {
				$this->ws->getCustomer()->setRayon($order->getRayon());
			}
			if (!$this->ws->getCustomer()->getStreet()) {
				$this->ws->getCustomer()->setStreet($order->getStreet());
			}
			if (!$this->ws->getCustomer()->getHouse()) {
				$this->ws->getCustomer()->setHouse($order->getHouse());
			}
			if (!$this->ws->getCustomer()->getFlat()) {
				$this->ws->getCustomer()->setFlat($order->getFlat());
			}
			if (!$this->ws->getCustomer()->getIndex()) {
				$this->ws->getCustomer()->setIndex($order->getIndex());
			}
			$this->ws->getCustomer()->save();

			$order->save();

		}
	}

	public function pagetextAction()
	{

		echo $this->render('shop/static.tpl.php');

	}

	public function _redir($param)
	{
		if ($param == 'index'){
                    $this->_redirect(SITE_URL . '/');
                    
                }else{
                    $this->_redirect(SITE_URL . '/' . $param . '/');
                    
                }
	}

	/** - nova pochta
         * 
         */
	public function novapochtaAction()
                {
            $lang = Registry::get('lang');
        
	if($lang == 'uk')
            {
            $lang = 'ua';  
            }

	require_once('np/NovaPoshta.php');
	$np = new NovaPoshta('920af0b399119755cbca360907f4fa60', $lang, true);
        
        if($this->get->what == 'citynpochta'){
            $c = $np->getCities(0, $this->get->term);
            $mas = array();
            $i = 0;
		$l = true;
		if($lang == 'ua'){
                    $l = false;
                }
            if($c['success']){
		foreach ($c['data'] as $c) {
                    if($l){
                        $mas[$i]['label'] = $c['DescriptionRu'];
                        $mas[$i]['value'] = $c['DescriptionRu'];
                    }else{
                         $mas[$i]['label'] = $c['Description'];
                        $mas[$i]['value'] = $c['Description'];
                    }
		$mas[$i]['id'] = $c['Ref']; 
		$i++;
			}
            }else{
                 if($l){
                $mas[0]['label'] = 'Ошибка доступа к городам НП, попробуйте позже';
		$mas[0]['value'] = 'Ошибка доступа к городам НП, попробуйте позже';
                 }else{
                $mas[0]['label'] = 'Помилка доступу до міст НП, спробуйте пізніше';
		$mas[0]['value'] =  'Помилка доступу до міст НП, спробуйте пізніше';
                 }
		$mas[0]['id'] = null; 
            }
            die(json_encode($mas));
            
	// die(json_encode(City::listcity($this->get->term)));
	}elseif ($this->post->metod == 'getframe_np') {

   $wh = $np->getWarehouses($this->post->warehouses);
	$text = '';
    foreach ($wh['data'] as $warehouse) {
	$pos = strpos($warehouse['DescriptionRu'], 'Почтомат');
	$pos_u = strpos($warehouse['Description'], 'Поштомат');
	if(!$pos and !$pos_u){
	if($lang === 'ua') {
            $text.='<option data-id ="'.$warehouse['Ref'].'" value="'.$warehouse['Description'].'">'.$warehouse['Description'].'</option>';
            
        }else{
            $text.='<option data-id ="'.$warehouse['Ref'].'" value="'.$warehouse['DescriptionRu'].'">'.$warehouse['DescriptionRu'].'</option>';
            
        }
	}
    }
    die(json_encode($text));

	
	}
        die();

}

    /**
     * улици Киева по митекспрес
     */
	public function getmistcityAction()
            {
	if ($this->get->what == 'street') {//поиск улицы в киеве с мит експреса
	$lang = $_SESSION['lang'];
	if($lang == 'uk') {$lang = 'ua';}
	require_once('meestexpress/include.php');
	$api = new MeestExpress_API('red_ua','3#snlMO0sP','35a4863e-13bb-11e7-80fc-1c98ec135263', $lang);

		$street = $api->getStreet($this->get->term, '5CB61671-749B-11DF-B112-00215AEE3EBE');
		$mas = array();
		$i = 0;
		foreach ($street as $c) {
		if($_SESSION['lang'] and $_SESSION['lang'] == 'uk'){
		$mas[$i]['label'] = (string)$c->StreetTypeUA.' '.$c->DescriptionUA;
		$mas[$i]['value'] = (string)$c->StreetTypeUA.' '.$c->DescriptionUA;
		$mas[$i]['id'] = (string)$c->uuid;
		$i++;
			}else{
		$mas[$i]['label'] = (string)$c->StreetTypeRU.' '.$c->DescriptionRU;
		$mas[$i]['value'] = (string)$c->StreetTypeRU.' '.$c->DescriptionRU;
		$mas[$i]['id'] = (string)$c->uuid;
		$i++;
			}
			}
			echo json_encode($mas);
	}
		die();
    }
	/**
         * автокомплектация поиска
         */
	public function gosearchAction()
                {

	$date = '( model LIKE "' . mysql_real_escape_string($this->get->term) . '%" or brand LIKE "%'.mysql_real_escape_string($this->get->term) .'%")';

            $find = wsActiveRecord::useStatic('Shoparticles')->findByQuery('SELECT distinct(ws_articles.model), distinct(ws_articles.brand)   from ws_articles WHERE ' . $date);

		$mas = array();
		$i = 0;
		foreach ($find as $item) {
		$mas[$i]['label'] = (string)$item->getModel();
		$mas[$i]['value'] = (string)$item->getModel();
		$mas[$i]['id'] = (string)$item->getModel();
		$i++;

			}
			foreach ($find as $item) {
		$mas[$i]['label'] = (string)$item->getBrand();
		$mas[$i]['value'] = (string)$item->getBrand();
		$mas[$i]['id'] = (string)$item->getBrand();
		$i++;
			}


			echo json_encode($mas);


	die();
	}
	/**
         *  конечная страница оформленого заказа
         */
	public function ordersuccesAction()
                {
                    echo $this->render('shop/ordersucces.tpl.php');
                }
/**
 * отслеживание заказа
 */
	public function tracingAction()
                {
	if($this->get->metod == 'ukr'){
             require_once('up/UkrPostAPI.php');
        $up =  new UkrPostAPI();
         $res = $up->getStatusTraking($this->get->getTtn());
        $text =  '<table class="table" style="font-size:12px;">'
            . '<tr>'
            . '<thead>'
            . '<th>Дата</th>'
            . '<th>Індекс</th>'
            . '<th>Місце</th>'
            . '<th>Операція</th>'
            . '</thead>'
            . '</tr>';
       foreach ($res as $value) {
        $text.= '<tr>'
                . '<td>'.$value->date.'</td>'
                . '<td>'.$value->index.'</td>'
                . '<td>'.$value->name.'</td>'
                . '<td>'.$value->eventName.'</td>'
                . '</tr>';
    }
        $text .= '</table>';
        die($text);
        /*
$text = '';
$client = new SoapClient('http://services.ukrposhta.ua/barcodestatistic/barcodestatistic.asmx?WSDL');
				// Формируем объект для создания запроса к сервису:
$params = new stdClass();
$params->guid = '1';//fcc8d9e1-b6f9-438f-9ac8-b67ab44391dd
$params->culture = 'uk';
$params->barcode = $this->get->getTtn();
$text .=  $client->GetBarcodeInfo($params)->GetBarcodeInfoResult->eventdescription;
die($text);*/
}else if($this->get->metod == 'np'){
require_once('np/NovaPoshta.php');
$np = new NovaPoshta($_SESSION['lang']);
$text = '';
$result = $np->documentsTracking2($this->get->getTtn());
if($result['StatusCode'] != 3 and $result['StatusCode'] != 1){
    $text.="Відправлення: ".$result['Number']."<br>";
    $text.="Адреса: ".$result['RecipientAddress']."<br>";
	if($result['RecipientDateTime']) {
	$text.="Статус: ".$result['Status']." ".$result['RecipientDateTime']."<br>";
	}else{
	$text.="Статус: ".$result['Status'].".  Очікувана дата доставки ".$result['ScheduledDeliveryDate']."<br>";
	}
}else{
     $text.="Посылки № ".$this->get->getTtn()." не найдена!!!";
}
die($text);
}
	}
}
