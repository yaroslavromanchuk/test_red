<?php

class ShopController extends controllerAbstract
{

	public function indexAction()
	{

		$this->view->setArticlesTop(wsActiveRecord::useStatic('Shoparticlestop')->findAll());

		echo $this->render('shop/index.tpl.php');

	}

	public function categoryAction()
                {
		$search_word = $this->get->s;

		if (!$search_word) {
			$search_word = false;
		} else {
		if (stristr($search_word, '.com') === FALSE) {
   SearchLog::setToLog(mysql_real_escape_string(htmlentities($search_word)), (int)$this->ws->getCustomer()->getId());
}else{
$search_word = false;
}
		}



		$category = new Shopcategories($this->get->id);
		$this->cur_menu->name = $category->getRoutez();
		$this->view->category = $category;
		$this->view->finder_category = $this->get->id;
		//if(/*$this->ws->getCustomer()->isAdmin()*/true){
		$this->getfilter($search_word, $this->get->id, $this->get->brands, $this->get->colors, $this->get->sizes, $this->get->labels, $this->get->sezons, $this->get->skidka, $this->get->categories, array('price_min'=>$this->get->price_min, 'price_max'=>$this->get->price_max));
		//}else{
		//$this->getsearch($search_word, $this->get->id, $this->get->brands, $this->get->colors, $this->get->sises, $this->get->labels, $this->get->sezons, $this->get->skidka, $this->get->categories);
		//}

		//unset($this->get[3]);

		//d($this->get, false);

	}
	function getfilter($search_word = '', $category = '', $brands = '', $colors = '', $sizes = '', $labels = '', $sezons = '', $skidka = '', $categories = '', $price = array())
	{
		//$addtional = array();
		$addtional = array('categories'=>array(), 'colors'=>array(), 'sizes'=>array(), 'labels'=>array(), 'brands'=>array(), 'sezons'=>array(), 'skidka'=>array(), 'price'=>array());

		//d($price, false);

		//price
		if($price['price_min'] != NULL ) $addtional['price']['min'] =  $price['price_min'];
		if($price['price_max'] != NULL ) $addtional['price']['max'] =  $price['price_max'];

		 //categories
		$addtional['categories'] = $categories?$categories:$this->post->categories;
		//brands
		//d($brands, false);
        foreach (explode(',', $brands?$brands:$this->post->brands) as $v){ if ($v) $addtional['brands'][] =  (int)$v; }

		//colors
        foreach (explode(',', $colors?$colors:$this->post->colors) as $v){ if ($v) $addtional['colors'][] = (int)$v;}

		//sizes
        foreach (explode(',', $sizes?$sizes:$this->post->sizes) as $v){ if ($v) $addtional['sizes'][] = (int)$v; }

		//labels
        foreach (explode(',', $labels?$labels:$this->post->labels) as $v){ if ($v) $addtional['labels'][] = (int)$v; }

		//sezons
        foreach (explode(',', $sezons?$sezons:$this->post->sezons) as $v){ if ($v)$addtional['sezons'][] = (int)$v; }

		//skidka
        foreach (explode(',', $skidka?$skidka:$this->post->skidka) as $v){ if ($v) $addtional['skidka'][] = (int)$v; }


		//d($addtional, false);
		$prod_on_page = $_COOKIE['items_on_page'];
                
                if (!$prod_on_page){ $prod_on_page = Config::findByCode('products_per_page')->getValue();}

		$this->view->per_page = $onPage = $prod_on_page;
		$page = $this->get->page?(int)$this->get->page:0;

		//d($page, false);
		$search_result = Filter::getArticlesFilter($search_word, $addtional, $category, $this->get->order_by, $page, $onPage);

		$this->view->filters = $search_result['parametr'];

		$this->view->cur_page = $page;

		$this->view->result_count = $search_result['count'];

		$this->view->total_pages = $search_result['pages'];

		$this->view->search_word = $search_word;
		$this->view->articles = $search_result['articles'];
		$this->view->result = $this->view->render('finder/list.tpl.php');


		$this->view->price_min = $search_result['min_max'] ? $search_result['min_max'][0]->min: 0;
		$this->view->price_max = $search_result['min_max'] ? $search_result['min_max'][0]->max : 1;
		echo $this->render('finder/result.tpl.php');
	}


	function getsearch($search_word = '', $category = '', $brands = '', $colors = '', $sises = '', $labels = '', $sezons = '', $skidka = '', $categories = '')
	{
		//$addtional = array();
		$addtional = array('categories'=>array(), 'colors'=>array(), 'sizes'=>array(), 'labels'=>array(), 'brand'=>array(), 'sezons'=>array(), 'skidka'=>array());
		 //categories
        $t = explode(',', $categories?$categories:$this->post->categories);
        foreach ($t as $v){
            if (@$v){
                $addtional['categories'][] = (int)$v;
            }
        }
		//colors
        $t = explode(',', $this->get->colors?$this->get->colors:$this->post->colors);
        foreach ($t as $v){
            if ($v){
                $addtional['colors'][] = (int)$v;
            }
        }
		//sizes
        $t = explode(',', $this->get->sizes?$this->get->sizes:$this->post->sizes);
        foreach ($t as $v){
            if ($v){
                $addtional['sizes'][] = (int)$v;
            }
        }
		//labels
        $t = explode(',', $this->get->labels?$this->get->labels:$this->post->labels);
        foreach ($t as $v){
            if ($v){
                $addtional['labels'][] = Orm_Statement::escape($v);
            }
        }
		//sezons
        $t = explode(',', $this->get->sezons?$this->get->sezons:$this->post->sezons);
        foreach ($t as $v){
            if ($v){
                $addtional['sezons'][] = Orm_Statement::escape($v);
            }
        }
		//sezons
        $t = explode(',', $this->get->skidka?$this->get->skidka:$this->post->skidka);
        foreach ($t as $v){
            if ($v){
                $addtional['skidka'][] = Orm_Statement::escape($v);
            }
        }
		//brands
        $t = explode(',', $this->get->brands?$this->get->brands:$this->post->brands);
        foreach ($t as $v){
            if (@$v){
                $addtional['brands'][] =  (int)$v;//Orm_Statement::escape($v);
            }
        }

		/*if ($brand) {
			$addtional['brands'][] = $brand;
		}*/



		$prod_on_page = (int)@$_SESSION['items_on_page'];
		if (!$prod_on_page) {
			$prod_on_page = Config::findByCode('products_per_page')->getValue();
		}

		$this->view->per_page = $onPage = $prod_on_page;

		$this->view->filters = Finder::getAllEnabledParametrs($search_word, $addtional, $category);
		//d($this);
		$page = (int)$this->get->page;
		$search_result = Finder::getArticlesByWord($search_word, $addtional, $page, $onPage, $category, $this->get->order_by);
		if (@$_GET['view'] == 'all') {
			$page = 0;
			$onPage = $search_result['count'];
			$this->view->per_page = $onPage;
			$this->view->view_all = 1;
		} else {
			$this->view->view_all = 0;
		}
		$this->view->cur_page = $page;
		$this->view->items_count = $search_result['count'];
		if ($onPage == 0) $onPage = 1;
		$this->view->page_count = ceil($search_result['count'] / $onPage) - 1;
		$this->view->result_count = $search_result['count'];
		$this->view->total_pages = $search_result['pages'];
		$this->view->search_word = $search_word;
		$this->view->articles = $search_result['articles'];
		$this->view->result = $this->view->render('finder/list.tpl.php');


		$this->view->price_min = $search_result['min_max'] ? $search_result['min_max']->getMin() : 0;
		$this->view->price_max = $search_result['min_max'] ? $search_result['min_max']->getMax() : 1;
		echo $this->render('finder/result.tpl.php');
	}



	public function articleokAction()
	{

		echo $this->render('shop/article-ok.tpl.php');

	}

//2517
	public function basketAction() {
		if ($this->ws->getCustomer()->isAdmin()) {
			if (!$this->ws->getCustomer()->hasRight('do_pay')) {
			die('Нету прав на заказ');
			}
		}
/*
		if (!$this->basket)
			$this->_redir('index');
*/
//		print_r($this->basket);die();
                if ('delete' == $this->cur_menu->getParameter()) {
			$basket = $_SESSION['basket'];
			if ($basket) {
				$_SESSION['basket'] = array();
				foreach ($basket as $key => $value)
					if ($key != (int)$this->get->getPoint())
						$_SESSION['basket'][] = $value;
				$this->_redir('basket');
			}
		}elseif ('change' == $this->cur_menu->getParameter() && $this->get->getCount()) {
			if (isset($_SESSION['basket'][(int)$this->get->getPoint()]['count'])) {
				$count = $this->get->getCount();
/*
				if ($count > MAX_COUNT_PER_ARTICLE)
					$count = MAX_COUNT_PER_ARTICLE;
*/
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


					//$_SESSION['error_cod'] = $this->trans->get('Код введен неверно! Повторите ввод кода');
					//$_SESSION['error_cod'] = $this->trans->get('Этим кодом уже воспользовались. Код можно использовать единоразово')."!";


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

	private function createBasketList($basket = false, $del_cost = 0)
	{
		if ($basket === false)
                {
                    $basket =  $this->basket;
                }
		if (!$del_cost)
                {
                    $del_cost = Config::findByCode('delivery_cost')->getValue();
                }

		$articles = [];

		$sum = 0;
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

			//$real_price = 

				$sum += ($item['option_price']>0)?$article->getRealPrice($item['option_price']):$article->getRealPrice()*$item['count'];
			}
		}

		//$discount = 0; //ceil(($sum + 500)/1000)-1;
		$_SESSION['order_amount'] = $sum;

		return $articles;
	}
//2517
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

			//$info = $_SESSION['basket_contacts'];

			$info['kupon'] = $_SESSION['kupon'];

			unset($_SESSION['kupon']);

            $error_email = 0;
            if (!$this->ws->getCustomer()->getIsLoggedIn()) {
                if (wsActiveRecord::useStatic('Customer')->findByEmail($info['email'])->count() != 0) {
                    $error_email = 1;
					 $errors['error'][] = $this->trans->get('Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь').'.';
                   // $this->view->error_email = $this->trans->get('Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь').'.';
					$errors['Email'] = 'Email';
				}
            }
           // $tel = Number::clearPhone(trim($info['telephone']));
			
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
            
      if (!$this->ws->getCustomer()->getIsLoggedIn()) {
          $count_order_magaz  = 3;  
      }else{
          $count_order_magaz  = $this->ws->getCustomer()->count_order_magaz;
      }
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
                    
                }
                elseif ($info['delivery_type_id'] == 9)
                { //Курьер
                    if (!$info['s_street']){$errors[] = $this->trans->get('Улица');}
                    if (!$info['house']){$errors[] = $this->trans->get('Дом');}
                    if (!$info['flat']){$errors[] = $this->trans->get('Квартира');}
                    
                }
                elseif ($info['delivery_type_id'] == 8 or $info['delivery_type_id'] == 16)
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

				//$_SESSION['basket_articles'] = $articles;
					$this->view->articles = $_SESSION['basket_articles'] = $articles;
				//$_SESSION['basket_options'] = $options;
					//$this->view->options = $options;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//$t_count = 0;
				$t_price = 0.00;
				//$total_price = 0.00;
				$to_pay = 0;
				$to_pay_minus = 0.00;
				$skidka = 0;
				//$bonus = false;
				$now_orders = 0;
				$event_skidka = 0;
				$kupon = 0;
				//$sum_order = 0;

			if($info['kupon']){
			$today_c = date("Y-m-d H:i:s");
			$ok_kupon = wsActiveRecord::useStatic('Other')->findFirst(array("cod"=>$info['kupon'], "ctime <= '".$today_c."' ", " '".$today_c."' <= utime"));
			if($ok_kupon){
			$kupon = $ok_kupon->cod;
			$info['kupon_price'] = $ok_kupon->skidka;
			//$find_count_orders_by_user_cod = 0;
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
                                
				//foreach ($articles as $article) {
                                //   $art =  wsActiveRecord::useStatic('Shoparticles')->findById($article['article_id']);
				//	$t_price += $art->getPriceSkidka() * $article['count'];
				//}
                                $t_price = $_SESSION['total_price'];
                                
				$now_orders += $t_price;

				foreach ($articles as $article) {
					$at = new Shoparticles($article['id']);
				$price = $at->getPerc($now_orders, $article['count'], $skidka, $event_skidka, $kupon, $t_price);

					
					$to_pay += $price['price'];
					$to_pay_minus += $price['minus'];
				}
				//$tota_price = $t_price;

				//$kop = round(($to_pay - toFixed($to_pay)) * 100, 0);
				$total_price = $to_pay;//+ Shoporders::getDeliveryPrice();


				$_SESSION['sum_to_ses'] = $total_price;
				$_SESSION['sum_to_ses_no_dos'] = $to_pay;

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$check_c = [];   
                                
				$this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'];

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

				//if (count($_SESSION['basket_articles']) and isset($_GET['recalculate']) and false ){$this->_redir('shop-checkout-step4');}
                                
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

					$phone = preg_replace('/[^0-9]/', '', $info['telephone']);
					$phone = substr($phone, -10);
					$phone = '38'.$phone;
					$data = array(
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
						'amount' => $_SESSION['sum_to_ses'] ? $_SESSION['sum_to_ses'] : 0,
						'soglas' => $info['soglas'] ? 1 : 0,
						'oznak' => $info['oznak'] ? 1 : 0,
						'call_my' => $info['callmy'] ? 1 : 0,
						'quick' => 0,
						'kupon' => $info['kupon']?$info['kupon']:'',
						'kupon_price' => $info['kupon_price']?$info['kupon_price']:'',
                                                'skidka' =>  $skidka
					);

					$order->import($data);
					$order->save();
                                        
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

		if($_SESSION['bonus'] and $this->ws->getCustomer()->getBonus() > 0 and $order->getAmount() >= Config::findByCode('min_sum_bonus')->getValue()){
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

                    if (!$order->getId()) {
                        $this->_redir('basket');
                        }

					//$utime = date("Y-m-d H:i:s");
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
							//$a->setOrderId($order->getId());

							$a->import($data);
							//$a->setArtikul(trim($article['artikul']));
							//$a->setOldPrice($item->getOldPrice());
							/*$s = Skidki::getActiv($item->getId());
							$c = Skidki::getActivCat($item->getCategoryId());
							if($c){
							$a->setEventSkidka($c->getValue());
								$a->setEventId($c->getId());
							}elseif($s){
								$a->setEventSkidka($s->getValue()+$event_skidka_klient);
								$a->setEventId($s->getId()+$event_skidka_klient);
							}else{*/
							//$a->setEventSkidka($event_skidka_klient);
							//$a->setEventId($event_skidka_klient_id);
							//}

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

					$this->basket = $this->view->basket = $_SESSION['basket'] = [];
					$this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'] = [];
                                        
                                       // $order->save();
					//$order = wsActiveRecord::useStatic('Shoporders')->findById($order->getId());
                                        
					//meestexpres
					if($order->getDeliveryTypeId() == 8 or $order->getDeliveryTypeId() == 16){
                                            $new_np = Shopordersmeestexpres::newOrderNp($order->getId(), $info['cityx'], $info['sklad_np']);
                                            $order->setMeestId($new_np);
					}
					//exit meestexpres
                                        $_SESSION['order_amount'] = $order->getAmount();
					$this->set_customer($order);
                                        $order->setDeliveryCost($order->getDeliveryPrice());
                                        
                                      //  $order->save();
                                            
					$order->reCalculate();
                                        
                                         

					$basket = $order->getArticles()->export();

                                        
					$this->view->articles = $this->createBasketList($basket, $order->getDeliveryCost());
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

	if($order->getKuponPrice() > 0 and $order->getKupon() != null){
	OrderHistory::newHistory($order->getCustomerId(), $order->getId(), 'Использовано скидку (по коду) - ('.$order->getKuponPrice().') %. ',
                'Код скидки "' . $order->getKupon() . '"');
						}
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
					}//else{
					//$this->_redir('ordersucces');//finish
					//echo $this->render('shop/ordersucces.tpl.php');
					//}

					
						$this->_redir('ordersucces');//finish
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				}// vse tovary est v nalichii
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

		if (!$this->basket)
			$this->_redir('index');

		$articles = $this->createBasketList();

		$_SESSION['basket_articles'] = $articles;
		$this->view->articles = $articles;
		//$_SESSION['basket_options'] = $options;
		//$this->view->options = $options;
		echo $this->render('shop/basket-step3.tpl.php');
	}

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
				if (isset($_SESSION['parent_id']) and $_SESSION['parent_id'] != 0)
				$customer->setParentId($_SESSION['parent_id']);
                                
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

			$articles = $this->createBasketList($basket, $order->getDeliveryCost());
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
			$skidka_block = 0;
			$option_id = 0;

			if($article->getSkidkaBlock()){ $skidka_block = 1; }

//$change = $article->addToBasket(1, (int)$_POST['size'], (int)$_POST['color'], $option_id, 1, (isset($_POST['artikul'])?$_POST['artikul']:0), $skidka_block);
$change = $article->addToBasket(1, (int)$_POST['size'], (int)$_POST['color'], (isset($_POST['option']) ? (int)$_POST['option'] : 0), 1, (isset($_POST['artikul']) ? $_POST['artikul'] : 0) , $_POST['skidka_block']?$_POST['skidka_block']:0);


	if ($change) {
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
					'company' => isset($info['company']) ? $info['company'] : '',
					'name' => $info['name'],
					'middle_name' => isset($info['middle_name']) ? $info['middle_name'] : '',
					//'address' => isset($info['address']) ? $info['address'] : '',
					'index' => $info['index'],
					'street' => $info['street'],
					'house' => $info['house'],
					'flat' => $info['flat'],
					'pc' => $info['pc'],
					'city' => isset($info['city']) ? $info['city'] : '',
					'obl' => isset($info['obl']) ? $info['obl'] : '',
					'rayon' => $info['rayon'],
					'sklad' => $info['sklad'],
					'telephone' => $phone,
					'email' => $info['email'],
					'comments' => isset($info['comments']) ? $info['comments'] : '',
					'delivery_cost' => 0,
					'delivery_type_id' => isset($info['delivery_type_id']) ? $info['delivery_type_id'] : '',
					'payment_method_id' => isset($info['payment_method_id']) ? $info['payment_method_id'] : 1,
					'amount' => $_SESSION['order_amount'],
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

				$order->reCalculate();
                                
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
                                                
						//$a = new Shoporderarticles();
						//$a->setOrderId($order->getId());
						//$data = $article;
						//$data['article_id'] = $data['id'];
						//unset($data['id']);
						//$a->import($data);
						//$a->setOldPrice($item->getOldPrice());
                                                
						
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
						//$s = Skidki::getActiv($item->getId());
						//$c = Skidki::getActivCat($item->getCategoryId());
						//if ($s) {
							//$a->setEventSkidka($s->getValue());
							//$a->setEventId($s->getId());
						//}elseif(@$c){
							//$a->setEventSkidka($c->getValue());
							//$a->setEventId($c->getId());
						//}
						$a->save();
					}

				}

				//____________________send_email_________________


				$this->view->articles = $this->createBasketList($order->getArticles()->export(), $order->getDeliveryCost());
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

				$this->basket = $_SESSION['basket'] = array();
				$this->basket_articles = $_SESSION['basket_articles'] = array();

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

/*
	public function mimageAction()
	{

		///*
		//* width - int
		//* height - int
		//* crop - true || false
		/* fill - true || false
		//* fill_color - r_g_b (255_255_255)
		///
		$cache = true;

		$default = array('path', 'type', 'filename', 'id', 'width', 'height', 'crop', 'crop_coords', 'fill', 'fill_color', 'original');
		$get = array();
		foreach ($default as $item)
			$get[$item] = $this->get->{
				"get" . ucfirst($item)
				}();

		$get['original'] = (int)$get['original'];
		$get['width'] = (int)$get['width'];
		$get['height'] = (int)$get['height'];
		$get['crop'] = $get['crop'] ? (strtolower($get['crop']) === 'true' ? 't' : 'f') : FALSE;
		$get['fill'] = $get['fill'] ? (strtolower($get['fill']) === 'true' ? 't' : 'f') : FALSE;
		$get['fill_color'] = $get['fill_color'] ? explode('_', $get['fill_color']) : FALSE;
		$get['crop_coords'] = $get['crop_coords'] ? explode('_', $get['crop_coords']) : FALSE;
// fill_color check value
		if ($get['fill_color'] && count($get['fill_color']) === 3)
			foreach ($get['fill_color'] as $key => $value)
				$get['fill_color'][$key] = (int)$value;
		else
			$get['fill_color'] = FALSE;
// crop_coords check value
		if ($get['crop_coords'] && count($get['crop_coords']) === 6)
			foreach ($get['crop_coords'] as $key => $value)
				$get['crop_coords'][$key] = (int)$value;
		else
			$get['crop_coords'] = FALSE;

		$filename_original = FALSE;
		$file = FALSE;

		$get['type'] = "standard";

		if ($get['type'] && $get['filename'])
			$filename_original = wsActiveRecord::useStatic('Shoparticles')->getSystemPath($get['type'], $get['filename']);
		
		elseif ($get['path'])
			$filename_original = FCPATH . $get['path'];
		if ($filename_original) {
			$ext = pathinfo($filename_original, PATHINFO_EXTENSION);
			if (!in_array(strtolower($ext), array('jpeg', 'jpg', 'gif', 'png', 'flv'), TRUE))
				$filename_original = FALSE;
		}

		if (($file && $file->id) || $filename_original) {
			if (!$filename_original)
				$filename_original = $file->getSystemPath();

			$ext = pathinfo($filename_original, PATHINFO_EXTENSION);

// image for video
			if (strtolower($ext) == 'flv') {
				$ext = 'jpeg';
				$filename_original = substr($filename_original, 0, strripos($filename_original, 'flv')) . $ext;
			}

			if ($get['original']) {
				$filename_original_org = substr($filename_original, 0, strrpos($filename_original, '.')) . "_org.{$ext}";
				if (!is_file($filename_original_org) && is_file($filename_original))
					copy($filename_original, $filename_original_org);
				$filename_original = $filename_original_org;
			}

			$filename_dest = substr($filename_original, 0, strrpos($filename_original, '.'));

			if ($get['width'])
				$filename_dest .= '_w' . $get['width'];
			if ($get['height'])
				$filename_dest .= '_h' . $get['height'];
			if ($get['crop'])
				$filename_dest .= '_c' . $get['crop'];
			if ($get['fill'])
				$filename_dest .= '_f' . $get['fill'];
			if ($get['fill_color'])
				$filename_dest .= '_fc' . implode('_', $get['fill_color']);
			if ($get['crop_coords'])
				$filename_dest .= '_cc' . implode('_', $get['crop_coords']);

			$filename_dest .= '.' . $ext;


//sort cache by size
			if ($get['width'] || $get['height']) {
				$folder = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $get['width'] . '_' . $get['height'] . '/';
				if (!file_exists($folder)) {
					mkdir($folder);
				}
				$filename_dest = $folder . pathinfo($filename_dest, PATHINFO_BASENAME);

			}

			$tmpname = FALSE;
			if ($get['crop_coords'] && !file_exists($filename_dest) && count($get['crop_coords']) == 6 && $get['crop_coords'][2] * $get['crop_coords'][3] > 0) {
				$tmpname = tempnam(INPATH . 'tmp/', "cc_");
				if (rvk_cut_image($filename_original, $tmpname, $get['crop_coords'], $get['crop_coords'][4], $get['crop_coords'][5]))
					$filename_original = $tmpname;
			}
			$rvk_image = new RvkImage();

			if (file_exists($filename_dest) || $rvk_image->copy($filename_original, $filename_dest, $get['width'], $get['height'], ($get['crop'] === 't'
					? TRUE : FALSE), ($get['fill'] === 't' ? TRUE : FALSE), ($get['fill_color'] ? $get['fill_color']
					: array(255, 255, 255)))
			) {


				if ($cache) {

					$f = $filename_dest;
					$etag = base_convert(md5($f), 16, 26);
					$etags[] = substr($etag, 0, 6);
					$etags[] = substr($etag, 6, 4);
					$etags[] = substr($etag, 10, (strlen($etag) - 10));
					$etag = implode("-", $etags);

					$pi = pathinfo($f);

					$request = getallheaders();
					if (isset($request['If-Modified-Since']) || isset($request['If-None-Match'])) {
						header("HTTP/1.1 304 Not Modified", 1);
						header("Cache-control: public", 1);
						header("Last-Modified: " . date("r", mktime(3, 0, 0, 2, 26, 1980)), 1);
						header("ETag: \"" . $etag . "\"", 1);
						header("Connection: close");
						readfile($f);
						exit;
					}

					

					header("Last-Modified: " . date("r", mktime(3, 0, 0, 2, 26, 1980)), 1);

					header("ETag: \"" . $etag . "\"", 1);

					header("Accept-Ranges: bytes");
					header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600 * 24 * 365) . 'GMT');
					header("Content-Length: " . filesize($f));
					header("Cache-control: public", 1);
					header("Content-Type: image/" . $pi['extension'], 1);

					readfile($f);
				} else {
					$size = getimagesize($filename_dest);
					header("Content-type: {$size['mime']}");
					readfile($filename_dest);
				}
			}
			if ($tmpname && is_file($tmpname))
				unlink($tmpname);
			exit(0);
		}
		header("HTTP/1.1 404 Not Found", 1);
		exit;
	}
*/
	// yarik - nova pochta
	public function novapochtaAction()
                {

if($this->get->what == 'citynpochta'){
	die(json_encode(City::listcity($this->get->term)));
				}
if ($this->post->metod == 'getframe_np') {
    
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

}


	public function getmistcityAction()
            {
	if ($this->get->what == 'street') {//поиск улицы в киеве с мит експреса
	$lang = $_SESSION['lang'];
	if($lang == 'uk') $lang = 'ua';
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
	//автокомплектация поиска
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
	// конечная страница оформленого заказа
	public function ordersuccesAction()
                {
                    echo $this->render('shop/ordersucces.tpl.php');
                }

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
