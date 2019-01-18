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
 * Корзина
 */
	public function basketAction()
                {
		if ($this->ws->getCustomer()->isAdmin()) {
			if (!$this->ws->getCustomer()->hasRight('do_pay')) {
			die('Нету прав на заказ');
			}
		}

                if('delete' == $this->cur_menu->getParameter()) {
			$basket = $_SESSION['basket'];
			if ($basket) {
				$_SESSION['basket'] = array();
				foreach ($basket as $key => $value)
					if ($key != (int)$this->get->getPoint())
						$_SESSION['basket'][] = $value;
				$this->_redir('basket');
			}
		}elseif('change' == $this->cur_menu->getParameter() && $this->get->getCount()) {
			if (isset($_SESSION['basket'][(int)$this->get->getPoint()]['count'])) {
				$count = $this->get->getCount();
				$_SESSION['basket'][(int)$this->get->getPoint()]['count'] = $count;
			}
			$this->_redir('basket');
		}
                
		$error = [];
		if (isset ($_GET['size']) and !$_GET['size']) {
			$error [] = $this->trans->get('Выберите размер');
		}
		if (isset ($_GET['color']) and !$_GET['color']) {
			$error [] = $this->trans->get('Выберите цвет');
		}
                
		foreach ($this->basket as $item) {
			$article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id']);
			if ($item['article_id'] == $article->getId() and $item['size'] == @$_GET['size'] and $item['color'] == @$_GET['color']) {
				$error [] = $this->trans->get('Вы уже заказали')." " . $article->getBrand() . " " . $article->getModel() . ".".$this->trans->get(' Можете изменить количество').".";
			}
		}
		
		// запись кода в сесию
		if($this->get->kupon){
					$today_c = date("Y-m-d H:i:s");
			$ok_cod = wsActiveRecord::useStatic('Other')->findFirst(array("cod like '".$this->get->kupon."'", "ctime <= '".$today_c."' ", " '".$today_c."' <= utime"));

			$find_count_orders_by_user_cod = true;

			if(!$ok_cod) {$_SESSION['error_cod'] = $this->trans->get('Код введен неверно! Повторите ввод кода');}

			if(@$ok_cod and $ok_cod->count_order){
			$find_count_orders_by_user = wsActiveRecord::useStatic('Shoporders')->count(array('kupon'=>$ok_cod->cod, 'customer_id' => $this->ws->getCustomer()->getId()));
			if($find_count_orders_by_user and $find_count_orders_by_user >= $ok_cod->count_order){

			$find_count_orders_by_user_cod = false;
			$_SESSION['error_cod'] = 'Вами превышен лимит использования этого промокода';
			}
			}

			//}
			unset($_SESSION['kupon']);
			//'customer_id' => $this->ws->getCustomer()->getId(),
					if($ok_cod and $find_count_orders_by_user_cod){
					$_SESSION['kupon'] = $ok_cod->cod;
					$this->view->kupon = $ok_cod->cod;
					unset($_SESSION['error_cod']);
					}
				}else{
				//unset($_SESSION['kupon']);
				unset($_SESSION['error_cod']);
				}
				//exit запись кода в сесию

		if (count($_POST)) {
			if (isset($_POST['tostep2'])) {
				foreach ($_POST as &$value) {
					$value = stripslashes(trim($value));
				}
				if ($this->post->deposit == 1) {
					$_SESSION['deposit'] = $this->ws->getCustomer()->getDeposit();
				}else{ unset($_SESSION['deposit']);}
                                
				if ($this->post->bonus == 1) {
					$_SESSION['bonus'] = $this->ws->getCustomer()->getBonus();
				}else {unset($_SESSION['bonus']);}

				if (!isset($_SESSION['basket_contacts'])) {
					$_SESSION['basket_contacts'] = [];
				}
				$this->_redirect('/shop-checkout-step2/');
			}
		}
		if (isset($error)) {
			$this->view->error = $error;
		}
		//if ($this->get->metod == 'frame') echo $this->render('top.tpl.php');
                if ($this->get->metod == 'frame'){ echo $this->render('bottom.tpl.php'); die(); }
		echo $this->render('shop/basket.tpl.php');
	}
/**
 * 
 * @param type $basket - корзина
 * @param type $del_cost - стоимость доставки
 * @return type
 */
	private function createBasketList($basket = false)
	{
		if ($basket === false)
                {
                    $basket =  $this->basket;
                }

		$articles = [];

		//$sum = 0;
		foreach ($basket as $item) {
		if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId()) {
$code = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery(" SELECT code "
        . "FROM ws_articles_sizes "
        . "WHERE id_article = ".$item['article_id']." and id_size = ".$item['size']." and id_color = ".$item['color']." and `count` > 0 ")->at(0)->code;

	$articles[] = [
			'id' => $article->getId(),
			'title' => $article->getTitle(),
			'count' => $item['count'],
			'option_id' => $item['option_id'],
			'option_price' => $item['option_price'],
			'size' => $item['size'],
			'color' => $item['color'],
			'artikul' => $code,
			'category' => $item['category'],
			'price' => $article->getRealPrice(),
			'skidka_block' =>$item['skidka_block']?$item['skidka_block']:0
					];
		//$sum += ($item['option_price']>0)?$article->getRealPrice($item['option_price']):$article->getRealPrice()*$item['count'];
			}
		}
		//$_SESSION['order_amount'] = $sum;

		return $articles;
	}

        /**
         * страница оформления заказа
         * url = /shop-checkout-step2/
         */
public function basketcontactsAction()
        {
	
	if ($this->ws->getCustomer()->isBan()) {
            $this->_redir('ban');
        }
        if ($this->ws->getCustomer()->isNoPayOrder()) {
            $this->_redir('nopay');
        }
	if (!$this->basket){ $this->_redir('index'); }
        /*
	if ($this->ws->getCustomer()->isAdmin()) {
			if (!$this->ws->getCustomer()->hasRight('do_pay')) {
				die('Нету прав на заказ');
			}
		}*/

        $errors = array();
	$err_m = array();
        
		if ($_POST)
                    {
			foreach ($_POST as $value){ $value = stripslashes(trim($value)); }

			$_SESSION['basket_contacts'] = $info =  $_POST;


			$info['kupon'] = $_SESSION['kupon'];

			unset($_SESSION['kupon']);

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



            foreach ($info as $k => $v){ $info[$k] = strip_tags(stripslashes($v)); }

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

            if (!$info['delivery_type_id']){$errors[] = $this->trans->get('Способ доставки');}
            if (!isset($info['payment_method_id'])){ $errors[] = $this->trans->get('Способ оплаты');}
            if (!isset($info['soglas'])){$errors[] = $this->trans->get('Согласие');}
            if (!isset($info['oznak'])){$errors[] = $this->trans->get('С условиями ознакомлен');}
            if (!$info['email'] || !isValidEmail($info['email'])){$errors[] = 'email';}
            
                if (($info['delivery_type_id'] == 3  or $info['delivery_type_id'] == 5) and $info['payment_method_id'] == 1)
                {//magasiny
	$or_c = wsActiveRecord::useStatic('Shoporders')->findAll(array("email LIKE  '".$info['email']."'", 'delivery_type_id  IN ( 3, 5 ) ', 'payment_method_id'=>1, 'status'=>3));
            
		if($or_c->count() >= $count_order_magaz)
                    {
			$ord = '';
                        foreach($or_c as $r){ $ord.=$r->id.', ';}
			$err_m[] = 'По состоянию на '.date('d.m.Y').', в пункте выдачи интернет-магазина, находятся Ваши неоплаченные заказы № '.$ord.'. В связи с этим, Вам ограничено оформление заказов в пункты самовывоза с оплатой наличными при получении, до оплаты доставленных заказов. Дополнительную информацию Вы можете получить в нашем Call-центре по номеру (044)224-40-00 Пн-Пн с 09:00-18:00.';
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
                }

                

            if (!$errors and $error_email == 0 and !$err_m) {
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$articles = $this->createBasketList();

		
		$this->view->articles = $_SESSION['basket_articles'] = $articles;
                                        
                                //$t_price = 0.00;
                                
				//$to_pay = 0;
				//$to_pay_minus = 0.00;
				//$skidka = 0;
				//$now_orders = 0;
				//$event_skidka = 0;
				$kupon = 0;

			if($info['kupon']){
			$today_c = date("Y-m-d H:i:s");
			$ok_kupon = wsActiveRecord::useStatic('Other')->findFirst(array("cod"=>$info['kupon'], "ctime <= '".$today_c."' ", " '".$today_c."' <= utime"));
			if($ok_kupon){
			$kupon = $ok_kupon->cod;
			$info['kupon_price'] = $ok_kupon->skidka;
			}
					}

			/*if ($this->ws->getCustomer()->getIsLoggedIn()) {
					$skidka = $this->ws->getCustomer()->getDiscont(false, 0, true);
					$event_skidka =  EventCustomer::getEventsDiscont($this->ws->getCustomer()->getId());
					$now_orders = $this->ws->getCustomer()->getSumOrderNoNew();
				}
                                */

                               // $t_price = $_SESSION['total_price'];
                                
				//$now_orders += $t_price;

				/*foreach ($articles as $article) {
                                    $at = new Shoparticles($article['id']);
                                    $price = $at->getPerc(($now_orders+$_SESSION['total_price']), $article['count'], $skidka, $event_skidka, $kupon, $_SESSION['total_price']);

					$to_pay += $price['price'];
					$to_pay_minus += $price['minus'];
				}
                                */
				
				//$total_price = $to_pay;

                              //  $_SESSION['order_amount'] = $to_pay;
                                
				//$_SESSION['sum_to_ses'] = $_SESSION['order_amount'] =  $to_pay;// + Shoporders::getDeliveryPrice();
                                
				//$_SESSION['sum_to_ses_no_dos'] = $to_pay;

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$check_c = [];   
                                
				$this->basket_articles = $this->view->basket_articles = $articles;

				foreach ($this->basket_articles as $key => $article) {
					$itemcs = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article['id'], 'id_size' => $article['size'], 'id_color' => $article['color']));
					if ($itemcs->id){
                                            if($itemcs->count == 0) {
						$check_c[$key] = $article; //массив товаров которые в этот момент успели купить другие
					}
                                        }else{
                                            $check_c[$key] = $article;
                                        }
				}


				if (!count($_SESSION['basket_articles'])){$this->_redir('shop-checkout-step3');}
                                
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
				} else {
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
					}


					$info['address'] = implode(', ', $mas_adres);

					$info['l_name'] = $info['name'].' '.$info['last_name'];

					$phone = '38'.substr(preg_replace('/[^0-9]/', '', $info['telephone']), -10);
                                        
					$data = [
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
						'liqpay_status_id' => 1,
						//'amount' => $_SESSION['sum_to_ses'] ? $_SESSION['sum_to_ses'] : 0,
						'soglas' => $info['soglas'] ? 1 : 0,
						'oznak' => $info['oznak'] ? 1 : 0,
						'call_my' => $info['callmy'] ? 1 : 0,
						'quick' => 0,
						'kupon' => $info['kupon']?$info['kupon']:'',
						'kupon_price' => $info['kupon_price']?$info['kupon_price']:'',
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
							AND red_event_customers.end_time > '".date('Y-m-d H:i:s')."'

					";
			$events = wsActiveRecord::useStatic('EventCustomer')->findByQuery($q)->at(0);
                                           
                                        
					if($events){
					$event_skidka_klient = $events->getDiscont();
					$event_skidka_klient_id = $events->getEventId();
					if($event_skidka_klient > 0){
					$this->view->event = $event_skidka_klient;
						$events->setSt(5);
						$events->save();
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
                                        
                                        $deposit = 0;

			if ($_SESSION['deposit'] and $this->ws->getCustomer()->getDeposit()) {
                                            
					$total_price = $order->getAmount();
					
					$dep = $this->ws->getCustomer()->getDeposit() - $total_price;

					if ($dep <= 0){
                                                        $dep = $this->ws->getCustomer()->getDeposit();
                                                  }else{
                                                        $dep = $total_price;
                                                       }

					$_SESSION['deposit'] = $dep;
					$order->setDeposit($dep);

					//perevod v novu pochtu esly polnosty oplachen depositom
					if($order->getDeliveryTypeId() == 16 and $dep == $total_price){
					$order->setDeliveryTypeId(8);
					$info['delivery_type_id'] = 8;
					$order->setPaymentMethodId(8);
					$info['payment_method_id'] = 8;
                                        }elseif($order->getDeliveryTypeId() == 4 and $dep == $total_price){
                                            $order->setPaymentMethodId(8);
					$info['payment_method_id'] = 8;
                                        }
                                        
					//perevod v novu pochtu esly polnosty oplachen depositom

					$order->setAmount(($total_price-$dep));
					$order->save();

					$customer = new Customer($this->ws->getCustomer()->getId());
					$customer->setDeposit($customer->getDeposit() - $dep);
					$customer->save();
                                        
					//$c_dep = $customer->getDeposit();
                                        
			OrderHistory::newHistory(
                                $customer->getId(),
                                $order->getId(),
                                ' Клиент использовал депозит ('.$order->getDeposit().') грн. ',
                                'Осталось на депозите "' . $customer->getDeposit() . '"'
                                );
                        
				$no = '-';
				DepositHistory::newDepositHistory($customer->getId(), $customer->getId(), $no, $order->getDeposit(), $order->getId());

				$deposit = $_SESSION['deposit'];
				unset($_SESSION['deposit']);

				}
                                
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
		//$bonus = $_SESSION['bonus'];
				unset($_SESSION['bonus']);
				//$bonus = true;
				}
		

				
                    $payment_method_id = $info['payment_method_id'];// dlya onlayn oplat

		$this->basket = $this->view->basket = $_SESSION['basket'] = [];
		$this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'] = [];
                                        

					//meestexpres
	if($order->getDeliveryTypeId() == 8 or $order->getDeliveryTypeId() == 16){
               $new_np = Shopordersmeestexpres::newOrderNp($order->getId(), $info['cityx'], $info['sklad_np']);
               $order->setMeestId($new_np);
	}
					//exit meestexpres
//$basket = $order->getArticles()->export();

                                        
					//$this->view->articles = $this->createBasketList($basket);
					$this->view->deposit = $deposit;
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
						/////
/*
	if($order->getKuponPrice() > 0 and $order->getKupon() != null){
	OrderHistory::newHistory($order->getCustomerId(), $order->getId(), 'Использовано скидку (по коду) - ('.$order->getKuponPrice().') %. ',
                'Код скидки "' . $order->getKupon() . '"');
						}
                                                */
//////

					}
   if($order->getDeliveryTypeId() == 16){
  $find_count_orders_by_user = wsActiveRecord::useStatic('Shoporders')->count(array('customer_id' => $order->getCustomerId()));
        
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
			LiqPayHistory::newHistory($order->getId(), 1, '');
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

					
						$this->_redir('ordersucces');//finish

				}// vse tovary est v nalichii

			}//not error
                        
            $this->basket_contacts = $this->view->basket_contacts = $_SESSION['basket_contacts'];
        }//$_POST tst dannye - otpravlena forma

		$this->view->err_m = $err_m;
		$this->view->errors = $errors;

		 echo $this->render('shop/basket-step2.tpl.php');

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

			$em = iconv_substr($order->getEmail(), 0, 4, 'UTF-8');
				if($em == 'miss'){
	SendMail::getInstance()->sendEmail('php@red.ua', 'Yaroslav', 'Создан новый акаунт МИСС', 'Email: '.$order->getEmail().'. Заказ: '.$order->Id());
				}

				$customer = new Customer();
				if (isset($_SESSION['parent_id']) and $_SESSION['parent_id'] != 0){ $customer->setParentId($_SESSION['parent_id']); }
                                
				$customer->setUsername($order->getEmail());
				$customer->setPassword(md5($newPass));
				$customer->setCustomerTypeId(1);
				$customer->setCompanyName($order->getCompany());
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

					$subscriber = new Subscriber();
					$subscriber->setName($order->getName());
					$subscriber->setEmail($order->getEmail());
					$subscriber->setConfirmed(date('Y-m-d H:i:s'));
					$subscriber->setActive(1);
					$subscriber->save();

				$order->setCustomerId($customer->getId());
				$order->save();

				$this->view->login = $order->getEmail();
				$this->view->pass = $newPass;
				$subject = 'Создан акаунт';
				$msg = $this->render('email/new-customer.tpl.php');

				SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg);

				$customer = $this->ws->getCustomer();
				$res = $customer->loginByEmail($order->getEmail(), $newPass);
				if (!isset($_SESSION['user_data']))
					$_SESSION['user_data'] = array();
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
//2517
	public function basketorderAction()
	{
	if(false)
            {
		if ($this->ws->getCustomer()->isAdmin()) {
			if (!$this->ws->getCustomer()->hasRight('do_pay')) {
				die('Нету прав на заказ');
			}
		}

		if ($this->ws->getCustomer()->isBan())
			$this->_redir('ban');

		if ($this->ws->getCustomer()->isNoPayOrder())
			$this->_redir('nopay');

		/*if ($this->ws->getCustomer()->isBlockK())
			$this->_redir('nopay');*/

		$check_c = array();
		foreach ($this->basket_articles as $key => $article) {
			$itemcs = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article['id'], 'id_size' => $article['size'], 'id_color' => $article['color']));
			if ($itemcs->getCount() == 0) {
				$check_c[$key] = $article; //массив товаров которые в этот момент успели купить другие
			}
		}

		if (count($_SESSION['basket_articles']) and isset($_GET['recalculate']) and false ) $this->_redir('shop-checkout-step4');
		if (!count($_SESSION['basket_articles']) /* || !$this->basket_options*/) $this->_redir('shop-checkout-step3');

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
		}else {
			// check for errors
			$errors = array();
			$info = & $this->basket_contacts;
			//$info['kupon'] = $_SESSION['kupon'];
			if (!$this->basket)
				$this->_redir('index');

			if (!$info['name'])
				$errors[] = 'name';
			if (!$info['middle_name'])
				$errors[] = 'middle_name';

			if (!$info['delivery_type_id'] or $info['delivery_type_id'] == 0)
				$errors[] = 'delivery_type';
			if (!isset($info['payment_method_id']))
				$errors[] = 'payment_method';
			if (!isset($info['soglas']))
				$errors[] = 'soglas';
			if (!isset($info['oznak']))
				$errors[] = 'oznak';

			if (@$info['delivery_type_id'] == 4) { //Укр почта
				if (!$info['index'])
					$errors[] = 'index';
				if (!$info['street'])
					$errors[] = 'Улица';
				if (!$info['house'])
					$errors[] = 'Дом';
				if (!$info['flat'])
					$errors[] = 'Квартира';
				if (!$info['obl'])
					$errors[] = 'Область';
				if (!$info['rayon'])
					$errors[] = 'Район';
				if (!$info['city'])
					$errors[] = 'Город';
			}
			 if (@$info['delivery_type_id'] == 9) {
			//Курьер
                if (!$info['s_street'])
                    $errors[] = 'Улица';
                if (!$info['house'])
                    $errors[] = 'Дом';
					if (!$info['flat'])
                    $errors[] = 'Квартира';
					}
			if (@$info['delivery_type_id'] == 8) { //Новая почта
				if (!$info['city_np'])
                    $errors[] = 'Город';
                if (!$info['sklad'])
                    $errors[] = 'Склад НП';
			}

			if (!$info['telephone'])
				$errors[] = 'Телефон';
			if (!$info['email'] || !isValidEmail($info['email']))
				$errors[] = 'email';

			if ($errors)
				$this->_redir('shop-checkout-step2');

			$curdate = Registry::get('curdate');
			$order = new Shoporders();

			$mas_adres = array();
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
						}
					}else if(@$info['delivery_type_id'] == 9){  //kurer
							$mas_adres[] = 'г. Киев';

					}else if(isset($info['city']) and strlen($info['city']) > 0) {

						$mas_adres[] = 'г. ' . $info['city'];
					}


					if(@$info['delivery_type_id'] == 9){  //kurer
					if (isset($info['s_street']) and strlen($info['s_street']) > 0) {
							$mas_adres[] = $info['s_street'];
						}
					}else if(isset($info['street']) and strlen($info['street']) > 0) {
						$mas_adres[] = 'ул. ' . $info['street'];
					}
					if (isset($info['house']) and strlen($info['house']) > 0) {
						$mas_adres[] = 'д.' . $info['house'];
					}
					if (isset($info['flat']) and strlen($info['flat']) > 0) {
						$mas_adres[] = 'кв.' . $info['flat'];
					}
					if (@$info['delivery_type_id'] == 8 or @$info['delivery_type_id'] == 16) { //Новая почта
						if (isset($info['sklad']) and strlen($info['sklad']) > 0) {
							$mas_adres[] = ' НП: ' . $info['sklad'];
						}
					}

					$info['address'] = implode(', ', $mas_adres);

					$info['l_name'] = @$info['name'].' '.@$info['last_name'];
					$city ='';
					$sklad = '';

					if (@$info['delivery_type_id'] == 8 or @$info['delivery_type_id'] == 16) {
					if (isset($info['city_np']) and strlen($info['city_np']) > 0) {
							$city = $info['city_np'];
						}
					}else if(@$info['delivery_type_id'] == 9){  //kurer
							$city = 'Киев';
					}else if(isset($info['city']) and strlen($info['city']) > 0) {
						$city = $info['city'];
					}

					if (@$info['delivery_type_id'] == 8 or @$info['delivery_type_id'] == 16) {
					if (isset($info['sklad']) and strlen($info['sklad']) > 0) {
							$sklad = $info['sklad'];
						}
					}

			$phone = preg_replace('/[^0-9]/', '', $info['telephone']);
			$phone = substr($phone, -10);
			$phone = '38'.$phone;
			$data = array(
				'status' => 100,
				'date_create' => $curdate->getFormattedMySQLDateTime(),
				'company' => isset($info['company']) ? $info['company'] : '',
				'name' => @$info['name'],
				'middle_name' => @$info['middle_name'],
				'address' => @$info['address'],
				'index' => @$info['index'],
				'street' => @$info['delivery_type_id'] == 9 ? $info['s_street'] : @$info['street'],
				'house' => @$info['house'],
				'flat' => @$info['flat'],
				'pc' => @$info['pc'],
				'city' => $city,
				'obl' => isset($info['obl']) ? $info['obl'] : '',
				'rayon' => @$info['rayon'],
				'sklad' => $sklad,
				'telephone' => @$phone,
				'email' => @$info['email'],
				'comments' => isset($info['comments']) ? $info['comments'] : '',
				'delivery_cost' => Shoporders::getDeliveryPrice(),
				'delivery_type_id' => @$info['delivery_type_id'],
				'payment_method_id' => @$info['payment_method_id'],
				'amount' => @$_SESSION['sum_to_ses'] ? @$_SESSION['sum_to_ses'] : 0,
				'soglas' => 0,
				'oznak' => 0,
				//'deposit' => @$_SESSION['deposit'],
				'call_my' => @$info['callmy'] ? 1 : 0,
				'quick' => 0,
				'kupon' => @$info['kupon'] ? @$info['kupon'] : '',
				'kupon_price' => @$info['kupon_price'] ? @$info['kupon_price'] : ''
			);

			$deposit = 0;
			$order->import($data);
            $order->save();

					$bonus = false;
	if($this->ws->getCustomer()->getBonus() > 0 and $order->getAmount() >= Config::findByCode('min_sum_bonus')->getValue()){
				$order->setBonus($this->ws->getCustomer()->getBonus());
				$customer = new Customer($this->ws->getCustomer()->getId());
				$customer->setBonus(0);
				$customer->save();
				OrderHistory::newHistory($this->ws->getCustomer()->getId(), $order->getId(), ' Клиент использовал бонус ('.$order->getBonus().') грн. ',
                ' ');
				$bonus = true;
				}

					if (isset($_SESSION['deposit']) and $this->ws->getCustomer()->getDeposit()) {

					$total_price = $order->getAmount();
					$dep = $this->ws->getCustomer()->getDeposit() - $total_price;

					if ($dep <= 0) $dep = $this->ws->getCustomer()->getDeposit();
					else $dep = $total_price;
					$_SESSION['deposit'] = $dep;
					$order->setDeposit($dep);
					//perevod v novu pochtu esly polnosty oplachen depositom
					if($order->getDelivertTypeId() == 16 and $dep == $total_price){
					$order->setDeliveryTypeId(8);
					$info['delivery_type_id'] = 8;
					$order->setPaymentMethodId(8);
					$info['payment_method_id'] = 8;
					}
					//perevod v novu pochtu esly polnosty oplachen depositom
					$am = $total_price - $dep;
					$order->setAmount($am);
					$order->save();

					$customer = new Customer($this->ws->getCustomer()->getId());
						$customer->setDeposit($customer->getDeposit() - $dep);
						$customer->save();
						$deposit = $_SESSION['deposit'];
						unset($_SESSION['deposit']);

				}
					$order->save();
						if($order->getDeposit() > 0){
				$customer = new Customer($this->ws->getCustomer()->getId());
					$c_dep = $customer->getDeposit();
				OrderHistory::newHistory($customer->getId(), $order->getId(), ' Клиент использовал депозит ('.$order->getDeposit().') грн. ',
                'Осталось на депозите "' . $c_dep . '"');
				$no = '-';
				DepositHistory::newDepositHistory($customer->getId(), $customer->getId(), $no, $order->getDeposit(), $order->getId());
				}


			$sub = Subscriber::findByEmail(@$info['email']);
			if (isset($info['podpis']) and (!$sub or $sub->getActive() == 0)) {
				//$admin_email = Config::findByCode('admin_email')->getValue();
				//$admin_name = Config::findByCode('admin_name')->getValue();
				//$do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
				$subject = Config::findByCode('confirm_email_subject')->getValue();
				$code = 1 . '-';
				for ($i = 0; $i <= 6; $i++)
				$code .= mt_rand(0, 9);
				$this->view->status = 1;
				$this->view->code = $code;
				$this->view->post = $info;

				$msg = $this->view->render('subscribe/email-confirm.tpl.php');
			if(!$this->ws->getCustomer()->isBlockEmail()) {
				SendMail::getInstance()->sendEmail($info['email'], $info['name'], $subject, $msg);
				//MailerNew::getInstance()->sendToEmail($info['email'], $info['name'], $subject, $msg);
				}

				$id = ($s = Subscriber::findByEmail(@$info['email'])) ? $s->getId() : 0;
				$sub = new Subscriber($id);
				$sub->setName($info['name']);
				$sub->setEmail($info['email']);
				$sub->setConfirmed('');
				$sub->setCode($code);
				$sub->save();
			}

			$order->save();


			if (!$order->getId()){ $this->_redir('basket'); }

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
					$event_skidka_klient_id = $events->at(0)->getId();
					//$order->setEventSkidka($events->at(0)->getDiscont());
					//$order->setEventId($events->at(0)->getId());
					$this->view->event = $event_skidka_klient;
						$events->at(0)->setSt(5);
						$events->at(0)->save();
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
					$a->setOldPrice($item->getOldPrice());
					$s = Skidki::getActiv($item->getId());
					$c = Skidki::getActivCat($item->getCategoryId());
					if (@$s) {
						$a->setEventSkidka($s->getValue()+$event_skidka_klient);
						$a->setEventId($s->getId());
					}elseif(@$c){
							$a->setEventSkidka($c->getValue()+$event_skidka_klient);
							$a->setEventId($c->getId());
						}else{
						$a->setEventSkidka($event_skidka_klient);
						$a->setEventId($event_skidka_klient_id);
						}

					$a->save();
				}else{
					$article['count'] = 0;
					$article['title'] = $article['title'] . ' (нет на складе)';
					$a = new Shoporderarticles();
					$a->setOrderId($order->getId());
					$data = $article;
					$data['article_id'] = $data['id'];
					unset($data['id']);
					$a->import($data);
					$s = Skidki::getActiv($item->getId());
					$c = Skidki::getActivCat($item->getCategoryId());
					if ($s) {
						$a->setEventSkidka($s->getValue());
						$a->setEventId($s->getId());
					}else if(@$c){
							$a->setEventSkidka($c->getValue());
							$a->setEventId($c->getId());
						}
					$a->save();
				}
			}

			$this->basket = $this->view->basket = $_SESSION['basket'] = array();
			$this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'] = array();
			//$this->basket_options = $this->view->basket_options = $_SESSION['basket_options'] = array();

			$order = wsActiveRecord::useStatic('Shoporders')->findById($order->getId());




					$order->reCalculate();


// TO DO : send mail to customer
			//$admin_name = Config::findByCode('admin_name')->getValue();
			//$admin_email = Config::findByCode('admin_email')->getValue();
			//$do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
			//$subject = Config::findByCode('email_order_subject')->getValue();

			$basket = $order->getArticles()->export();

			$articles = $this->createBasketList($basket);
			$this->view->articles = $articles;
			$this->view->deposit = $deposit;
			//$this->view->options = $options;
			$this->view->basket_contacts = $order;

if(!$this->ws->getCustomer()->isBlockEmail()) {
			$msg = $this->render('email/basket.tpl.php');

			SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg);
			//MailerNew::getInstance()->sendToEmail($order->getEmail(), $order->getName(), $subject, $msg);
}

			if (Config::findByCode('notify_admin')->getValue() and false) {
				$msg = $this->render('email/basket-toadmin.tpl.php');
				if ($order->getDelivertTypeId() == 9) $admin_email = 'market@red.ua';
				$mimemail = new nomad_mimemail();
				$mimemail->debug_status = 'no';
				$mimemail->set_from($order->getEmail(), $order->getName());
				$mimemail->set_to($admin_email);
				$mimemail->set_charset('UTF-8');
				$mimemail->set_subject($subject);
				$mimemail->set_text(make_plain($msg));
				$mimemail->set_html($msg);

				//@$mimemail->send();

				MailerNew::getInstance()->sendToEmail($admin_email, 'RED', $subject, $msg, 0, $order->getEmail(), $order->getName());
			}


			$this->set_customer($order);


			if ($order->getId()) {
				$order = new Shoporders($order->getId());

				$order->reCalculate(true);
		if($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->getTelegram()){
$message = 'Ваш заказ № '.$order->getId().' оформлен. Сумма к оплате '.$order->calculateOrderPrice2(true, true, true).' грн. Телефон (044) 224-40-00';
 Telegram::sendMessageTelegram($this->ws->getCustomer()->getTelegram(), $message);
}else{
				$phone = Number::clearPhone($order->getTelephone());

				require_once('alphasms/smsclient.class.php');
				$sms = new SMSClient(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue(), Config::findByCode('sms_key')->getValue());
				$id = $sms->sendSMS(Config::findByCode('sms_alphaname')->getValue(), $phone, 'Vash zakaz № ' . $order->getId() . ' Summa ' . $order->calculateOrderPrice2(true, true, true) . ' grn. tel. (044) 224-40-00');
				if($sms->hasErrors()){ $res = $sms->getErrors(); }else{ $res = $sms->receiveSMS($id); }
				wsLog::add('Order:'.$order->id.' to SMS: '.$phone.' - '.$res, 'SMS_' . $res);
				}

				if (!$order->getCustomerId()) {
					$usr = Customer::findByUsername($order->getEmail());
					if ($usr->getId()) {
						$order->setCustomerId($usr->getId());
						$order->save();
					}
				}
				if($order->getKuponPrice() > 0 and $order->getKupon() != null){
						$customer = new Customer($this->ws->getCustomer()->getId());
				OrderHistory::newHistory($customer->getId(), $order->getId(), 'Использовано скидку (по коду) ('.$order->getKuponPrice().') %. ',
                'Код скидки "' . $order->getKupon() . '"');
						}

		

                                 if ($order->getDeliveryTypeId() == 16) {
                                     $find_count_orders_by_user = wsActiveRecord::useStatic('Shoporders')->count(array('customer_id' => $order->getCustomerId()));
                                if($find_count_orders_by_user == 1){
                                    
                                }
                                     
                                     
                                 }
				/*if ($find_count_orders_by_user == 1) {
					$order->setFirst(1);
					$order->save();
					$msg = '<a href="http://www.red.ua/admin/shop-orders/edit/id/' . $order->getId() . '/">http://www.red.ua/admin/shop-orders/edit/id/'
						. $order->getId() . '</a>';
					$subject = 'Первый заказ №' . $order->getId() . ' ' . $order->delivery_type->getName();
					$mimemail = new nomad_mimemail();
					$mimemail->debug_status = 'no';
					$mimemail->set_from("orders@red.ua", "RED");
					$mimemail->set_to("orders@red.ua", "RED");
					$mimemail->set_charset('UTF-8');
					$mimemail->set_subject($subject);
					$mimemail->set_text(make_plain($msg));
					$mimemail->set_html($msg);
					//@$mimemail->send();

					MailerNew::getInstance()->sendToEmail("orders@red.ua", 'RED', $subject, $msg);

				}*/

			/*	$find_sf = wsActiveRecord::useStatic('Event')->findFirst('sumforgift > 0 AND publick > 0', array('sumforgift' => 'ASC'));

				if (@$find_sf) {
					if ($order->getAmount() >= $find_sf->getSumforgift() && @$info['aquaaction']) {
						$eventcustomer = new EventCustomer();
						$eventcustomer->setCustomerId($this->ws->getCustomer()->getId());
						$eventcustomer->setEventId($find_sf->getId());
						$eventcustomer->save();

						$curdate = Registry::get('curdate');
						$remark = new Shoporderremarks();
						$data = array(
							'order_id' => $order->getId(),
							'date_create' => $curdate->getFormattedMySQLDateTime(),
							'remark' => "Нужно положить подарочный сертификат!"
						);
						$remark->import($data);
						$remark->save();
					} elseif ($amt_customer > 20) {
					   $find_sf->setPublick(0);
						$find_sf->save();
					}
				}*/

			}

			/*$customer_id = $this->ws->getCustomer()->getId();
			$time = time();
			$q="
				SELECT
					*
				FROM
					red_events
					JOIN red_event_customers
					on red_events.id = red_event_customers.event_id
				WHERE
					red_event_customers.status = 1
					AND red_events.publick = 1
					AND red_event_customers.customer_id = ".$customer_id."
					AND red_events.start <= '".date('Y-m-d',$time)."'
					AND red_events.finish >= '".date('Y-m-d',$time)."'
					AND red_events.disposable = 1
					AND red_event_customers.st <= 2
					AND red_event_customers.session_id = '".session_id()."'
			";
			$events = wsActiveRecord::useStatic('EventCustomer')->findByQuery($q);
			if($events->at(0)){
				$events->at(0)->setSt(5);
				$events->at(0)->save();
			}*/
			echo $this->render('shop/basket-step4.tpl.php');
		}
		}//end false
	}

        /**
         * оформление быстрого заказа
         */
	public function basketorderquickAction()
	{
		if ($this->post){
			//____________________________start_check_inputs_________________________________________

			$_SESSION['basket_contacts'] = $this->post;

			// check for errors
			$errors = [];

			$info = $_SESSION['basket_contacts'];
                        
			$_SESSION['basket_contacts']['comments'] = $info['comment'];

			$error_email = 0;
			if (!$this->ws->getCustomer()->getIsLoggedIn() and wsActiveRecord::useStatic('Customer')->findByEmail($info['email'])->count() != 0) {
				$error_email = 1;
				$errors['error']['email'] = $this->trans->get('Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь').'.';
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

$article = wsActiveRecord::useStatic('Shoparticles')->findById($_POST['id']);
$change = $article->addToBasket((int)$_POST['size'], (int)$_POST['color'], (isset($_POST['artikul']) ? $_POST['artikul'] : 0), 1);


	if ($change['status']) {
	$this->basket = $_SESSION['basket'];
	$this->view->ok = true;
	}else{
	die(json_encode(array('result'=>'error', 'message'=>$errors['error']['error_articles']='Ошибка с товаром, попробуйте еще раз.')));
	}

				//____________________________end_add_to_basket____________________________________


				$articles = $this->createBasketList();

				$_SESSION['basket_articles'] = $articles;


				//________________________start_added_fields____________________________________________

				$order = new Shoporders();
				$phone = preg_replace('/[^0-9]/', '', $info['telephone']);
				$phone = substr($phone, -10);
				$phone = '38'.$phone;
				$data = array(
					'status' => 100,
					'date_create' => date("Y-m-d H:i:s"),
					'name' => $info['name'],
					'middle_name' => isset($info['middle_name']) ? $info['middle_name'] : '',
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
					'from_quick' => 1
				);
				$order->import($data);


				//________________________end_added_fields_____________________________________________


				$lastnq = wsActiveRecord::findByQueryFirstArray('SELECT MAX(quick_number) as quick_number FROM `ws_orders`');
				$order->setQuickNumber(++$lastnq['quick_number']);
				$order->save();


				//________________________put order_to_db______________________________________________
				$this->set_customer($order);

				
                                
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
							AND red_event_customers.end_time > '".date('Y-m-d H:i:s')."'

					";
					$events = wsActiveRecord::useStatic('EventCustomer')->findByQuery($q)->at(0);
                                           
                                        $event_skidka_klient = 0;
					$event_skidka_klient_id = 0;
					if($events){
					$event_skidka_klient = $events->getDiscont();
					$event_skidka_klient_id = $events->getEventId();
					if($event_skidka_klient > 0){
					$this->view->event = $event_skidka_klient;
						$events->setSt(5);
						$events->save();
						}

					}

		foreach ($articles as $article) {

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
                                                        $data['event_skidka'] = $event_skidka_klient;
                                                       $data['event_id'] = $event_skidka_klient_id;
                                                   $a = new Shoporderarticles();     
                                                
                                                $a->import($data);
                                                
						$a->save();
					} else {
						$article['count'] = 0;
						$article['title'] = $article['title'] . ' (нет на складе)';
						$a = new Shoporderarticles();
						$a->setOrderId($order->getId());
						$data = $article;
						$data['article_id'] = $data['id'];
						unset($data['id']);
						$a->import($data);
						
						$a->save();
					}

				}
                                
                               $order->reCalculate(); 

				//____________________send_email_________________


				$this->view->articles = $this->createBasketList($order->getArticles()->export());
				$this->view->basket_contacts = $order;
				$msg = $this->view->render('email/basket-order-quick.tpl.php');
				if(!$this->ws->getCustomer()->isBlockEmail()) {
                                    
				$subject = $this->trans->get('Принята заявка');

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

				if($sms->hasErrors()){ $res = $sms->getErrors(); }else{ $res = $sms->receiveSMS($id); }
					wsLog::add('Quick:'.$order->getQuickNumber().' to SMS: '.$phone.' - '.$res, 'SMS_' . $res);
				//____________________send_sms__________________
}


			$this->view->id_order = $order->getId();
			$this->view->summ = $order->calculateOrderPrice(false, true);

				$this->basket = $_SESSION['basket'] = [];
				$this->basket_articles = $_SESSION['basket_articles'] = [];

				OrderHistory::newOrder($order->getCustomerId(), $order->getId(), ($order->getAmount()+$order->getDeposit()), $order->getArticlesCount());

die(json_encode(array('result'=>'send', 'message'=>$this->render('shop/quick-order-result.php'))));

			}else{

		die(json_encode(array('result'=>'error', 'message'=>$errors)));
		}

		}

		exit;
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

        if($this->get->what == 'citynpochta'){
	die(json_encode(City::listcity($this->get->term)));
				}elseif ($this->post->metod == 'getframe_np') {
    
        $lang = $_SESSION['lang'];
        
	if($lang == 'uk')
            {
            $lang = 'ua';  
            }

	require_once('np/NovaPoshta.php');
	$np = new NovaPoshta('1e594a002b9860276775916cdc07c9a6', $lang, true, 'curl');


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
$text = '';
$client = new SoapClient('http://services.ukrposhta.ua/barcodestatistic/barcodestatistic.asmx?WSDL');
				// Формируем объект для создания запроса к сервису:
$params = new stdClass();
$params->guid = '1';//fcc8d9e1-b6f9-438f-9ac8-b67ab44391dd
$params->culture = 'uk';
$params->barcode = $this->get->getTtn();
$text .=  $client->GetBarcodeInfo($params)->GetBarcodeInfoResult->eventdescription;
die($text);
				}else if($this->get->metod == 'np'){
				require_once('np/NovaPoshta.php');
	$np = new NovaPoshta(
    '5936c1426b742661db1dd37c5639f7b6',
    $_SESSION['lang'], // Язык возвращаемых данных: ru (default) | ua | en
    FALSE, // При ошибке в запросе выбрасывать Exception: FALSE (default) | TRUE
    'curl' // Используемый механизм запроса: curl (defalut) | file_get_content
);
$text = '';
$result = $np->documentsTracking($this->get->getTtn());
if($result['errors']){ $text.="Посылки № ".$this->get->getTtn()." не найдена!!!";}else{
$text .= $result['data'][0]['StateName'];
}
die($text);
}
	}
}
