<?php

class DeveloperController extends controllerAbstract
{

 public function init()
    {
	//$this->view->setRenderPath('developer');
	$this->_global_template = 'developer.tpl.php';
	}


public function indexAction(){


echo $this->render('developer/index.php');
}


public function customerAction() {
    
    $this->view->customer = wsActiveRecord::useStatic('Customer')->findAll(['customer_type_id < 2', 'customer_status_id < 2']);
    
    echo $this->render('developer/customer.php');
}


public function basketcontactsAction() {
			
		//if ($this->ws->getCustomer()->isBan()) $this->_redir('ban');
      //  if ($this->ws->getCustomer()->isNoPayOrder()) $this->_redir('nopay');
	//	if (!$this->basket) $this->_redir('index');
		
		//if ($this->ws->getCustomer()->isAdmin() and !$this->ws->getCustomer()->hasRight('do_pay')) die('Нету прав на заказ');

		$info = array();
		$errors = array();
		$err_m = array();
		
		
		if(@$this->post->contact_valid){ //
		if (!$this->ws->getCustomer()->getIsLoggedIn()) {
		$email = $this->post->email;
		$phone = $this->post->phone;
		$phone = Number::clearPhone(trim($info['telephone']));
			$phone = preg_replace('/[^0-9]/', '', $info['telephone']);
			$phone = substr($tel, -10);
			//die(json_encode($email));
		if (wsActiveRecord::useStatic('Customer')->findByEmail($email)->count()){
		
		$info['email'] = $email;
		$info['open_form_avtor'] = 'open';
		die(json_encode($info));
		}
		
		if(wsActiveRecord::useStatic('Customer')->findFirst(array(" phone1 LIKE  '%".$phone."%' "))){
		$info['phone'] = $this->post->phone;
		$info['open_form_avtor'] = 'open';
		die(json_encode($info));
		}
		
		}else{
		$info['open_form_avtor'] = 'off';
		die(json_encode($info));
		//
		}
		
		
		//
		}
		if(@$this->post->delivery_type){ //
		//$result = array();
		$dop = array();
		$pay = array();
		$id = $this->post->id;
		$dely = wsActiveRecord::useStatic('DeliveryPayment')->findAll(array('delivery_id'=>(int)$id));
		if($dely){
		foreach($dely as $d){
		$pay[$d->payment_id] = $d->payment->name;
		}
		}
		switch($id){
			case 9: $dop = array('street', 'hous', 'flat');
				break;
			case 8: $dop = array('city', 'sklad');
				break;
			case 3: $dop = array('pobeda');
				break;
			case 5: $dop = array('mishuga');
				break;
			case 4: $dop = array('index', 'obl', 'rayon', 'city', 'street', 'hous', 'flat');
				break;
		   }
		
		
		die(json_encode(array('pay'=>$pay, 'dop'=>$dop)));
		}
		if(@$this->post->payment_method){ //
		
		
		
		die();
		}
		if ($_POST and false) {
			foreach ($_POST as &$value)
				$value = stripslashes(trim($value));
				
			$_SESSION['basket_contacts'] = $_POST;

			$info = $_SESSION['basket_contacts'];
			$info['kupon'] = $_SESSION['kupon'];
			//unset($_SESSION['kupon']);
			
            $error_email = 0;
            if (!$this->ws->getCustomer()->getIsLoggedIn()) {
                if (wsActiveRecord::useStatic('Customer')->findByEmail($info['email'])->count() != 0) {
                    $error_email = 1;
					 $errors['error'][] = $this->trans->get('Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь').'.';
                   // $this->view->error_email = $this->trans->get('Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь').'.';
					$errors['Email'] = 'Email';
				}
				
				
            }
            $tel = Number::clearPhone(trim($info['telephone']));
			$tel = preg_replace('/[^0-9]/', '', $info['telephone']);
			$tel = substr($tel, -10);
            $allowed_chars = '1234567890'; 
            if (!Number::clearPhone($tel)) {
                $errors['error'][] = $this->trans->get('Введите телефонный номер');
                $errors['telefon'] = $this->trans->get('Телефон');
            }
			if(strlen($tel) != 10){
			$errors['error'][] = $this->trans->get('Неверный формат номера').".";
            $errors['telefon'] = $this->trans->get('Телефон');
			}
            for ($i = 0; $i < mb_strlen($tel); $i++) {
                if (mb_strpos($allowed_chars, mb_strtolower($tel[$i])) === false) {
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
			
			

            foreach ($info as $k => $v)
                $info[$k] = strip_tags(stripslashes($v));

            if (!$info['name'])
                $errors[] = $this->trans->get('Имя');

            if (isset($info['middle_name'])) {
                if (!$info['middle_name'])
                    $errors[] = $this->trans->get('Фамилия');
            }
			else {
                $errors[] = $this->trans->get('Фамилия');
            }

            if (!$info['delivery_type_id'])
                $errors[] = $this->trans->get('Способ доставки');
            if (!isset($info['payment_method_id']))
                $errors[] = $this->trans->get('Способ оплаты');
            if (!isset($info['soglas']))
                $errors[] = $this->trans->get('Согласие');
            if (!isset($info['oznak']))
                $errors[] = $this->trans->get('С условиями ознакомлен');

            if (@$info['delivery_type_id'] == 4) { //Укр почта
                if (!$info['index'])
                    $errors[] = $this->trans->get('Индекс');
                if (!$info['street'])
                    $errors[] = $this->trans->get('Улица');
                if (!$info['house'])
                    $errors[] = $this->trans->get('Дом');
                if (!$info['flat'])
                    $errors[] = $this->trans->get('Квартира');
                if (!$info['obl'])
                    $errors[] = $this->trans->get('Область');
                if (!$info['rayon'])
                    $errors[] = $this->trans->get('Район');
                if (!$info['city'])
                    $errors[] = $this->trans->get('Город');
            }
            if (@$info['delivery_type_id'] == 9) {
			//Курьер
			
			
               if (!$info['s_street'])
                    $errors[] = $this->trans->get('Улица');
                if (!$info['house'])
                    $errors[] = $this->trans->get('Дом');
					if (!$info['flat'])
                    $errors[] = $this->trans->get('Квартира');
					
					 
					}
					
					
            
            if ($info['delivery_type_id'] == 8  or $info['delivery_type_id'] == 16) {
				if (@$info['payment_method_id'] == 3) {
					$info['delivery_type_id'] = 16;
				}
				else {
					$info['delivery_type_id'] = 8;
				}
			}
            if (@$info['delivery_type_id'] == 8 or @$info['delivery_type_id'] == 16) { //Новая почта
                if (!$info['city_np'])
                    $errors[] = $this->trans->get('Город');
                if (!$info['sklad'])
                    $errors[] = $this->trans->get('Склад НП');
					if (!$info['sklad_np'])
                    $errors[] = 'Проблема с ид склада';
            }
            if (!$info['email'] || !isValidEmail($info['email']))
                $errors[] = 'email';
				
				if ($info['delivery_type_id'] == 3  or $info['delivery_type_id'] == 5) {
	$or_c = wsActiveRecord::useStatic('Shoporders')->findAll(array("email LIKE  '".$info['email']."'", 'delivery_type_id  IN ( 3, 5 ) ', 'payment_method_id'=>1, 'status'=>3));
	//
					if($or_c->count() >= 3){
					$ord = '';
foreach($or_c as $r){
$ord.=$r->id.', ';
}
					$err_m[] = 'По состоянию на '.date('d.m.Y').', в пункте выдачи интернет-магазина, находятся Ваши неоплаченые заказы № '.$ord.'. В связи с этим, Вам ограничено оформление заказов в пункты самовывоза с оплатой наличными при получении, до оплаты доставленых заказов. Дополнительную информацию Вы можете получить в нашем Call-центре по номеру (044)224-40-00 Пн-Пн с 09:00-18:00.';
					}
					}
				
            if (!$errors and $error_email == 0 and !$err_m) {
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				list($articles, $options) = $this->createBasketList();
				
				$_SESSION['basket_articles'] = $articles;
					$this->view->articles = $articles;
				$_SESSION['basket_options'] = $options;
					$this->view->options = $options;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$t_count = 0;
				$t_price = 0.00;
				$total_price = 0.00;
				$to_pay = 0;
				$to_pay_minus = 0.00;
				$skidka = 0;
				$bonus = false;
				$now_orders = 0;
				$event_skidka = 0;
				$kupon = 0;
				//$sum_order = 0;
				
				if(@$info['kupon']){	
			$today_c = date("Y-m-d H:i:s"); 
			$ok_kupon = wsActiveRecord::useStatic('Other')->findFirst(array("cod"=>$info['kupon'], "ctime <= '".$today_c."' ", " '".$today_c."' <= utime"));
			if(@$ok_kupon){
			$kupon = $ok_kupon->cod;
			$info['kupon_price'] = $ok_kupon->skidka;
			$find_count_orders_by_user_cod = 0;
			}
					}
				
				if ($this->ws->getCustomer()->getIsLoggedIn()) {
					$skidka = $this->ws->getCustomer()->getDiscont(false, 0, true);
					$event_skidka =  EventCustomer::getEventsDiscont($this->ws->getCustomer()->getId());
					$all_orders = wsActiveRecord::useStatic('Customer')->findByQuery('
						SELECT
							IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
						FROM
							ws_order_articles
							JOIN ws_orders
							ON ws_order_articles.order_id = ws_orders.id
						WHERE
							ws_orders.customer_id = ' . $this->ws->getCustomer()->getId() . '
							AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0);
					$now_orders = $all_orders->getAmount();
				}
				foreach ($articles as $article) {
					$t_price += $article['price'] * $article['count'];
				}
				$now_orders += $t_price;
				
				foreach ($articles as $article) {
					$at = new Shoparticles($article['id']);
					//$to_pay_perc = $at->getProcent($now_orders, $skidka);
					if($article['option_id']){
					$price = $at->getPerc($now_orders, $article['count'], $skidka, 99, $kupon, $t_price);
					}else{
					$price = $at->getPerc($now_orders, $article['count'], $skidka, $event_skidka, $kupon, $t_price);
					}
							//$price = $at->getPerc($now_orders, $article['count'], $skidka, $event_skidka, $kupon, $t_price);
					$to_pay += $price['price'];
					$to_pay_minus += $price['minus'];
				}
				$tota_price = $t_price;
				
				$kop = round(($to_pay - toFixed($to_pay)) * 100, 0);
				$total_price = $to_pay + Shoporders::getDeliveryPrice();

				
				$_SESSION['sum_to_ses'] = $total_price;
				$_SESSION['sum_to_ses_no_dos'] = $to_pay;
				
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$check_c = array();
				$this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'];

				foreach ($this->basket_articles as $key => $article) {
					$itemcs = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article['id'], 'id_size' => $article['size'], 'id_color' => $article['color']));
					if ($itemcs->getCount() == 0) {
						$check_c[$key] = $article; //массив товаров которые в этот момент успели купить другие
					}
				}

				if (count($_SESSION['basket_articles']) and isset($_GET['recalculate']) and false )
					$this->_redir('shop-checkout-step4');
				if (!count($_SESSION['basket_articles']))
					$this->_redir('shop-checkout-step3');
				//проверка товаров которых нет в наличии
				if (count($check_c) > 0) {
					foreach ($check_c as $key => $val) {
						$basket = $_SESSION['basket'];
						if ($basket) {
							$_SESSION['basket'] = array();
							foreach ($basket as $keyb => $value)
								if ($key != $keyb)
									$_SESSION['basket'][] = $value;
						}
						unset($_SESSION['basket_articles'][$key]);
					}
					$this->view->articles = $check_c;
					echo $this->render('shop/basket-message.tpl.php');
				} else {
					$curdate = Registry::get('curdate');
					$order = new Shoporders(); 

					$mas_adres = array();
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
					if (@$info['delivery_type_id'] == 8 or @$info['delivery_type_id'] == 16) { //Новая почта
						if (isset($info['city_np']) and strlen($info['city_np']) > 0) {
							$mas_adres[] = 'г. ' . $info['city_np'];
							$city = $info['city_np'];
						}
					}else if(@$info['delivery_type_id'] == 9){  //kurer
							$mas_adres[] = 'г. Киев';
							$city ='Киев';

					}elseif(isset($info['city']) and strlen($info['city']) > 0) {
					
						$mas_adres[] = 'г. ' . $info['city'];
						$city = $info['city'];
						
					}
					if(@$info['delivery_type_id'] == 9){  //kurer
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
					if (@$info['delivery_type_id'] == 8 or @$info['delivery_type_id'] == 16) { //Новая почта
						if (isset($info['sklad']) and strlen($info['sklad']) > 0) {
							$mas_adres[] = 'НП: ' . $info['sklad'];
							$sklad = $info['sklad'];
						}
					}

					
					$info['address'] = implode(', ', $mas_adres);
					
					$info['l_name'] = @$info['name'].' '.@$info['last_name'];
					
					$phone = preg_replace('/[^0-9]/', '', $info['telephone']);
					$phone = substr($phone, -10);
					$phone = '38'.$phone;
					$data = array(
						'status' => 100,
						'date_create' => $curdate->getFormattedMySQLDateTime(),
						'company' => isset($info['company']) ? $info['company'] : '',
						'name' => @$info['l_name'],
						'middle_name' => @$info['middle_name'],
						'address' => @$info['address'],
						'index' => @$info['index'],
						'street' => @$info['delivery_type_id'] == 9 ? $info['s_street'] : @$info['street'],
						'house' => @$info['house'],
						'flat' => @$info['flat'],
						'pc' => @$info['pc'],
						'city' => @$city,
						//'city' =>  isset($info['city_np']) ? $info['city_np'] : $info['city'],
						'obl' => isset($info['obl']) ? $info['obl'] : '',
						'rayon' => @$info['rayon'],
						'sklad' => @$sklad,
						'telephone' => @$phone,
						'email' => @$info['email'],
						'comments' => isset($info['comments']) ? $info['comments'] : '',
						'delivery_cost' =>Shoporders::getDeliveryPrice(),
						'delivery_type_id' => @$info['delivery_type_id'],
						'payment_method_id' => @$info['payment_method_id'],
						'liqpay_status_id' => 1,
						'amount' => @$_SESSION['sum_to_ses'] ? @$_SESSION['sum_to_ses'] : 0,
						'soglas' => @$info['soglas'] ? 1 : 0,
						'oznak' => @$info['oznak'] ? 1 : 0,
						'call_my' => @$info['callmy'] ? 1 : 0,
						'quick' => 0,
						'kupon' => @$info['kupon'] ? @$info['kupon'] : '',
						'kupon_price' => @$info['kupon_price'] ? @$info['kupon_price'] : ''
							
					);

					
					$deposit = 0;
					$order->import($data);
					$order->save();
					

					
					if (@$_SESSION['deposit'] and $this->ws->getCustomer()->getDeposit()) {
					$total_price = $order->getAmount();
					//}	
					$dep = $this->ws->getCustomer()->getDeposit() - $total_price;
					
					if ($dep <= 0) $dep = $this->ws->getCustomer()->getDeposit();
					else $dep = $total_price;
					
					$_SESSION['deposit'] = $dep; 
					$order->setDeposit($dep);
					//perevod v novu pochtu esly polnosty oplachen depositom
					if($order->getDeliveryTypeId() == 16 and $dep == $total_price){
					$order->setDeliveryTypeId(8);
					$info['delivery_type_id'] = 8;
					$order->setPaymentMethodId(2);
					$info['payment_method_id'] = 2;
					}
					//perevod v novu pochtu esly polnosty oplachen depositom
					$am = $total_price - $dep;
					$order->setAmount($am);
					$order->save();
					
					$customer = new Customer($this->ws->getCustomer()->getId());
					$customer->setDeposit($customer->getDeposit() - $dep);
					$customer->save();
					$c_dep = $customer->getDeposit();
				OrderHistory::newHistory($customer->getId(), $order->getId(), ' Клиент использовал депозит ('.$order->getDeposit().') грн. ',
                'Осталось на депозите "' . $c_dep . '"');
				$no = '-';
				DepositHistory::newDepositHistory($customer->getId(), $customer->getId(), $no, $order->getDeposit(), $order->getId());

						$deposit = $_SESSION['deposit'];
						unset($_SESSION['deposit']);
				
				}
		if($this->ws->getCustomer()->getBonus() > 0 and $order->getAmount() >= Config::findByCode('min_sum_bonus')->getValue()){
				$order->setBonus($this->ws->getCustomer()->getBonus());
				$customer = new Customer($this->ws->getCustomer()->getId());
				$customer->setBonus(0);
				$customer->save();
				OrderHistory::newHistory($this->ws->getCustomer()->getId(), $order->getId(), ' Клиент использовал бонус ('.$order->getBonus().') грн. ',
                ' ');
				$bonus = true;
				}
					$order->save();
					
					
					$payment_method_id = $info['payment_method_id'];// dlya onlayn oplat

					
					if (!$order->getId()) {$this->_redir('basket');}
					
					$utime = date("Y-m-d H:i:s");
					$q=" SELECT * FROM
							red_events
							JOIN red_event_customers
							on red_events.id = red_event_customers.event_id
						WHERE
							red_event_customers.status = 1
							AND red_events.publick = 1
							AND red_event_customers.customer_id = ".$this->ws->getCustomer()->getId()."
							AND red_events.start <= '".date('Y-m-d')."'
							AND red_events.finish >= '".date('Y-m-d')."'
							AND red_events.disposable = 1
							AND red_event_customers.st <= 2
							AND red_event_customers.end_time > '".$utime."'
							
					";//AND red_event_customers.session_id = '".session_id()."'
					$events = wsActiveRecord::useStatic('EventCustomer')->findByQuery($q);
					
					if($events->at(0)){
					$event_skidka_klient = $events->at(0)->getDiscont();
					$event_skidka_klient_id = $events->at(0)->getEventId();
					//$order->setEventSkidka($events->at(0)->getDiscont());
					//$order->setEventId($events->at(0)->getId());
					if($event_skidka_klient > 0){
					$this->view->event = $event_skidka_klient;
						$events->at(0)->setSt(5);
						$events->at(0)->save();
						}
						
					}else{
					$event_skidka_klient = 0;
					$event_skidka_klient_id = 0;
					}

					foreach ($this->basket_articles as $article) {
						$item = new Shoparticles($article['id']);
						$itemcs = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article['id'], 'id_size' => $article['size'], 'id_color' => $article['color']));
						if ($itemcs->getCount() > 0) {
							$item->setStock($item->getStock() - $article['count']);
							$item->save();

							$itemcs->setCount($itemcs->getCount() - $article['count']);
							$itemcs->save();
							$a = new Shoporderarticles();
							$a->setOrderId($order->getId());
							$data = $article;
							$data['article_id'] = $data['id'];
							unset($data['id']);
							$a->import($data);
							$a->setArtikul(trim($article['artikul']));
							$a->setOldPrice($item->getOldPrice());
							$s = Skidki::getActiv($item->getId());
							$c = Skidki::getActivCat($item->getCategoryId(), $item->getDopCatId());
							if(@$c){
							$a->setEventSkidka($c->getValue());
								$a->setEventId($c->getId());
							}
							if(@$s){
								$a->setEventSkidka($s->getValue()+$event_skidka_klient);
								$a->setEventId($s->getId()+$event_skidka_klient);
							}else{
							$a->setEventSkidka($event_skidka_klient);
								$a->setEventId($event_skidka_klient_id);
							}
							
							$a->save();
						}else {
							$article['count'] = 0;
							$article['title'] = $article['title'] . ' (нет на складе)';
							$a = new Shoporderarticles();
							$a->setOrderId($order->getId());
							$data = $article;
							$data['article_id'] = $data['id'];
							unset($data['id']);
							$a->import($data);
							$s = Skidki::getActiv($item->getId());
							$c = Skidki::getActivCat($item->getCategoryId(), $item->getDopCatId());
							if(@$s){
								$a->setEventSkidka($s->getValue());
								$a->setEventId($s->getId());
							}
							if(@$c){
								$a->setEventSkidka($c->getValue());
								$a->setEventId($c->getId());
							}
							
							$a->save();
						}
					}

					$this->basket = $this->view->basket = $_SESSION['basket'] = array();
					$this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'] = array();
					$this->basket_options = $this->view->basket_options = $_SESSION['basket_options'] = array();

					$order = wsActiveRecord::useStatic('Shoporders')->findById($order->getId());
					//meestexpres
					if($order->getDeliveryTypeId() == 8 or $order->getDeliveryTypeId() == 16){
					/*if(in_array($payment_method_id, array(2, 4, 5, 6))) {
					$pay_type = 0;
					}else{
					$pay_type = 1;
					}*/
					/*$newmeest = Shopordersmeestexpres::newPost(
$info['s_service'],
 $order->getId(),
 $info['s_city_mist_id'],
 @$info['s_branch_id'] ? @$info['s_branch_id'] : '',
 @$info['s_street_id'] ? $info['s_street_id'] : '',
 @$_SESSION['massa_post']
 );*/
	$new_np = Shopordersmeestexpres::newOrderNp($order->getId(), $info['cityx'], $info['sklad_np']);				
					
 $order->setMeestId($new_np);		
					}
					//exit meestexpres
					
					if($order->getBonus() > 0){$bonus = true;}

					$this->set_customer($order);
					
						$order->reCalculate(true, $bonus);
					

					$basket = $order->getArticles()->export();

					list($articles, $options) = $this->createBasketList($basket, $order->getDeliveryCost());
					$this->view->articles = $articles;
					$this->view->deposit = $deposit;
					$this->view->options = $options;
					$this->view->order = $order;
						
					if(!$this->ws->getCustomer()->isBlockEmail()) {
					$subject = Config::findByCode('email_order_subject')->getValue();
					$msg = $this->render('email/basket.tpl.php');
					SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg); 
					//MailerNew::getInstance()->sendToEmail($order->getEmail(), $order->getName(), $subject, $msg);
					}
					

					if ($order->getId()) {
						$order = new Shoporders($order->getId());
						
						$order->reCalculate(true, $bonus);
						
						
						
					if($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->getTelegram() != NULL){
	$message = 'Ваш заказ № '.$order->getId().' оформлен. Сумма к оплате '.$order->calculateOrderPrice2(true, true, true, $bonus).' грн. Телефон (044) 224-40-00';
	$this->sendMessageTelegram($this->ws->getCustomer()->getTelegram(), $message);
	}else{
				$phone = Number::clearPhone($order->getTelephone());
						include_once('smsclub.class.php');
						$sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
						//$sender = ;
						$user = $sms->sendSMS(Config::findByCode('sms_alphaname')->getValue(), $phone, $this->trans->get('Vash zakaz').' № ' . $order->getId() . ' '.$this->trans->get('Summa').' ' . $order->calculateOrderPrice2(true, true, true,  $bonus) . ' grn. tel. (044) 224-40-00');
						wsLog::add('SMS to user: ' . $sms->receiveSMS($user), 'SMS_' . $sms->receiveSMS($user));
						}
//}

						if (!$order->getCustomerId()) {
							$usr = Customer::findByUsername($order->getEmail());
							if ($usr->getId()) {
								$order->setCustomerId($usr->getId());
								$order->save();
							}
						}
						/////

					if($order->getKuponPrice() > 0 and $order->getKupon() != null){
						$customer = new Customer($this->ws->getCustomer()->getId());
				OrderHistory::newHistory($customer->getId(), $order->getId(), 'Использовано скидку (по коду) - ('.$order->getKuponPrice().') %. ',
                'Код скидки "' . $order->getKupon() . '"'); 
						}
//////
						
					}
					

	
					//dla finishnoy stranicu
					
					$_SESSION['order'] = array();
					$_SESSION['order']['id'] = $order->getId();
					//$_SESSION['order']['amount'] = Shoparticles::showPrice($order->getAmount());
					//$_SESSION['order']['delivery_type_id'] = $order->getDeliveryTypeId();
					//$_SESSION['order']['delivery_cost'] = $order->getDeliveryCost();
					//$_SESSION['order']['payment_method_id'] = $order->getPaymentMethodId();	
					$_SESSION['list_articles_order'] = $order->getListArticlesOrder();
					//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if($order->getPaymentMethodId() == 7){
			LiqPayHistory::newHistory($order->getId(), 1, '');
			}
					if (in_array($payment_method_id, array(4, 5, 6))) {
						if ($payment_method_id == 4) {
							$paymaster = 21;
						}
						if ($payment_method_id == 5) {
							$paymaster = 1;
						}
						if ($payment_method_id == 6) {
							$paymaster = 49;
						}

						$order_id = $order->getId();
						$order_amount = $order->calculateOrderPrice2(true, false, true, $bonus);

						$pay_data['LMI_MERCHANT_ID'] = 2285;
						$pay_data['LMI_PAYMENT_AMOUNT'] = $order_amount;//str_replace(" ","",$order_amount);
						$pay_data['LMI_PAYMENT_NO'] = $order_id;
						$pay_data['LMI_PAYMENT_DESC'] = 'Оплата за заказ № '.$order_id;
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
					}//else{
					//$this->_redir('ordersucces');//finish
					//echo $this->render('shop/ordersucces.tpl.php');
					//}
						
					$post_order = true;
						$this->_redir('ordersucces');//finish
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
            $this->basket_contacts = $this->view->basket_contacts = $_SESSION['basket_contacts'];
        }
		
		
		$this->view->info = $info;
		$this->view->err_m = $err_m;
		$this->view->errors = $errors;
		
if(!$post_order) echo $this->render('developer/basket-step-test.tpl.php');
	
}
//2517

}
?>