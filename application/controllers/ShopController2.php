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
			SearchLog::setToLog(mysql_real_escape_string($search_word), (int)$this->ws->getCustomer()->getId());
		}
		$category = new Shopcategories($this->get->id);
		$this->cur_menu->name = $category->getRoutez();
		$this->view->category = $category;
		$this->view->finder_category = $this->get->id;
		$this->getsearch($search_word, $this->get->id, $this->get->brand);

		if (false) {
			$category = wsActiveRecord::useStatic('Shopcategories')->findById($this->get->getId());

			$this->view->per_page = $onPage = Config::findByCode('products_per_page')->getValue();

			$search = new Orm_Collection();
			$raw = $this->get;

			$title_add = '';
			$order_by = 'ctime';
			$order_by_type = 'DESC';

			if (@$raw['s'] == '== введите слово для поиска ==') @$raw['s'] = '';
			if (@$raw['brand']) {
				$search['brand'] = str_replace('_', '&', $raw['brand']);
				$title_add = $raw['brand'];
			}
			if (@$raw['price']) {
				$search['price'] = (int)$raw['price']; //$title_add = $raw['price'];
			}
			if (@$raw['color']) {
				$search['color'] = (int)$raw['color']; //$title_add = $raw['color'];
			}
			if (@$raw['size']) {
				$search['size'] = (int)$raw['size']; //$title_add = $raw['size'];
			}
			if (@$raw['sort']) {
				if ($raw['sort'] == 'dateplus') {
					$order_by = 'ctime';
					$order_by_type = 'DESC';
					$search['sort'] = 'dateplus';
				}
				if ($raw['sort'] == 'dateminus') {
					$order_by = 'ctime';
					$order_by_type = 'ASC';
					$search['sort'] = 'dateminus';
				}
				if ($raw['sort'] == 'priceplus') {
					$order_by = 'price';
					$order_by_type = 'DESC';
					$search['sort'] = 'priceplus';
				}
				if ($raw['sort'] == 'priceminus') {
					$order_by = 'price';
					$order_by_type = 'ASC';
					$search['sort'] = 'priceminus';
				}
				if ($raw['sort'] == 'views') {
					$order_by = 'views';
					$order_by_type = 'DESC';
					$search['sort'] = 'views';
				}
				if ($raw['sort'] == 'brandaz') {
					$order_by = 'brand';
					$order_by_type = 'ASC';
					$search['sort'] = 'brandaz';
				}
				if ($raw['sort'] == 'brandza') {
					$order_by = 'brand';
					$order_by_type = 'DESC';
					$search['sort'] = 'brandza';
				}
			}
			if ($category) {
				$this->view->getCurMenu()->setPageTitle($title_add ? $category->getName() . ' ' . $title_add
					: $category->getName());
			}
			if (@$raw['id']) {
				$search['category'] = new Shopcategories($raw['id']);

				$cat_text = 'AND ws_articles.category_id in (' . implode(', ', $search['category']->getKidsIds()) . ')';
			}
			if (@$raw['s']) {
				$search['s'] = $raw['s'];

				SearchLog::setToLog(mysql_real_escape_string($search['s']), (int)$this->ws->getCustomer()->getId());
			}
			$this->view->search = $search;
			if ($category && mb_strpos(mb_strtolower($category->getName()), 'new') !== false)
				$search['new'] = '1';

			if (@$raw['id'] == 106) {
				$q = "SELECT distinct(ws_articles.id), ws_articles.*
				 FROM ws_articles_sizes JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
				 WHERE ws_articles_sizes.count > 0 AND ws_articles.active = 'y'"
					. (@$search['new'] ? ' AND ws_articles.new = 1'
						: ' AND ws_articles.data_new > DATE_ADD(NOW(), INTERVAL -5 DAY) ')
					. (@$search['brand']
						? ' AND ws_articles.brand = "' . mysql_real_escape_string($search['brand']) . '"'
						: '')
					. (@$search['color'] ? ' AND ws_articles_sizes.id_color = ' . $search['color'] : '')
					. (@$search['price']
						? ' AND ws_articles.price <= ' . $search['price'] . ' AND ws_articles.price>' . ($search['price'] - 100)
						: '')
					. (@$search['size'] ? ' AND ws_articles_sizes.id_size = ' . $search['size'] : '')
					. (@$search['s']
						? ' AND ( model like "%' . mysql_real_escape_string($search['s']) . '%" or brand like "%' . mysql_real_escape_string($search['s']) . '%" or long_text like "%' . mysql_real_escape_string($search['s']) . '%")'
						: '')
					. " ORDER BY ws_articles." . $order_by . " " . $order_by_type; //sequence ASC
			} else {

				$q = "SELECT distinct(ws_articles.id), ws_articles.*
				 FROM ws_articles_sizes JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
				 WHERE ws_articles_sizes.count > 0 AND ws_articles.active = 'y'"
					. (@$search['new'] ? ' AND ws_articles.new = 1' : (@$search['category'] ? $cat_text : ''))
					. (@$search['brand']
						? ' AND ws_articles.brand = "' . mysql_real_escape_string($search['brand']) . '"'
						: '')
					. (@$search['color'] ? ' AND ws_articles_sizes.id_color = ' . $search['color'] : '')
					. (@$search['price']
						? ' AND ws_articles.price <= ' . $search['price'] . ' AND ws_articles.price>' . ($search['price'] - 100)
						: '')
					. (@$search['size'] ? ' AND ws_articles_sizes.id_size = ' . $search['size'] : '')
					. (@$search['s']
						? ' AND ( model like "%' . mysql_real_escape_string($search['s']) . '%" or brand like "%' . mysql_real_escape_string($search['s']) . '%" or long_text like "%' . mysql_real_escape_string($search['s']) . '%")'
						: '')
					. " ORDER BY ws_articles." . $order_by . " " . $order_by_type; //sequence ASC
			}
			//d($q);
			$this->view->articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($q);
			$page = (int)$this->get->getPage();
			if (@$_GET['view'] == 'all') {
				$page = 0;
				$onPage = $this->view->articles->count();
				$this->view->per_page = $onPage;
				$this->view->view_all = 1;
			} else {
				$this->view->view_all = 0;
			}
			if ($onPage == 0) $onPage = 1;
			$this->view->cur_page = $page;
			$this->view->items_count = $this->view->articles->count();
			if ($onPage) $this->view->page_count = ceil($this->view->articles->count() / $onPage) - 1;

			$q .= ' LIMIT ' . $page * $onPage . ',' . $onPage;
			$this->view->articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($q);

			if (!$category && $this->view->articles->at(0))
				$category = $this->view->articles->at(0)->getCategory();
			if (!$category)
				$category = wsActiveRecord::useStatic('Shopcategories')->findFirst();
			$this->view->category = $category;


			$this->view->page_url = Shoparticles::getSearchPath($search);
			echo $this->render('shop/category.tpl.php');
		}
	}


	function getsearch($search_word = '', $category = '', $brand = '')
	{
		$addtional = array();
		if ($brand) {
			$addtional['brands'][] = $brand;
		}
		$prod_on_page = (int)@$_SESSION['items_on_page'];
		if (!$prod_on_page) {
			$prod_on_page = Config::findByCode('products_per_page')->getValue();
		}

		$this->view->per_page = $onPage = $prod_on_page;

		$this->view->filters = Finder::getAllEnabledParametrs($search_word, $addtional, $category);
		//d($this);
		$page = (int)$this->get->page;
		$search_result = Finder::getArticlesByWord($search_word, $addtional, $page, $onPage, $category);
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

		$this->view->articles = $search_result['articles'];
		$this->view->result = $this->view->render('finder/list.tpl.php');
		$this->view->search_word = $search_word;
		$this->view->price_min = $search_result['min_max'] ? $search_result['min_max']->getMin() : 0;
		$this->view->price_max = $search_result['min_max'] ? $search_result['min_max']->getMax() : 1;
		echo $this->render('finder/result.tpl.php');
	}


	public function articleAction()
	{
		$article = wsActiveRecord::useStatic('Shoparticles')->findById($this->get->getId());
		if(!$article)
			$this->_redirect('/404');

		if ($this->get->metod == 'getbrand') {
			$text = '	<div class="brand_info">
					<table cellpadding="0" cellspacing="0">
						<tr>
						<td>';

			if ($article->article_brand->getImage()) {
				$text .= '<img src="' . $article->article_brand->getImage() . '"
								 alt="' . $article->article_brand->getName() . '"></td>';
			}
			$text .= '<td>
								<p class="strong">' . $article->article_brand->getName() . '</p>
								' . $article->article_brand->getText() . '
							</td>
						</tr>
					</table>
				</div>';
			die($text);

		}

		$this->view->getCurMenu()->setPageTitle($article->getCategory()->getName() . ' - ' . $article->getModel() . ' ' . $article->getBrand());

		if (!$article || !$article->getId())
			$this->_redir('index');
		$a_time = strtotime(date('Y-m-d', strtotime($article->getCtime())));
		$tinme_ok = (24 * 60 * 60);

		if (!$article->getGetNow()) {
			if ((time() - $a_time) < $tinme_ok && !$this->ws->getCustomer()->isAdmin()) $this->_redir('index');
		}
		if (strcasecmp($article->getActive(), 'y') != 0)
			$this->view->active = 0;
		else
			$this->view->active = 1;
		if (@$_POST) {
			$error = array();
			if (!isset($_POST['size']) or @$_POST['size'] == 0) {
				$error [] = "Выберите размер.";
			}
			if (!isset($_POST['color']) or @$_POST['color'] == 0) {
				$error [] = "Выберите цвет.";

			}
			foreach ($this->basket as $item) {
				if ($item['article_id'] == $article->getId() and $item['size'] == $_POST['size'] and $item['color'] == $_POST['color']) {
					$error [] = "Вы уже заказали этот товар.<br /> Для изменения количества зайдите в корзину.";
				}
			}
			if (count($error) == 0) {
				$change = $article->addToBasket(1, (int)@$_POST['size'], (int)@$_POST['color'], (isset($_POST['option'])
					? (int)@$_POST['option'] : 0));
				foreach (@$_POST as $key => $value) {
					$keys = explode('_', $key);
					if (strcasecmp($keys[0], 'sarticle') == 0 && (int)$value && ($sa = wsActiveRecord::useStatic('Shoparticles')->findById((int)$value)) && $sa->getId())
						if ($sa->addToBasket(1, (int)$_POST['size'], (int)$_POST['color'], (isset($_POST['soption_' . $keys[1]])
							? (int)@$_POST['soption_' . $keys[1]] : 0))
						)
							$change = true;
				}
				if ($change) {
					$this->basket = $this->view->basket = $_SESSION['basket'];
					$this->view->ok = true;
					if (@$_POST['metod'] != 'frame') $this->_redirect('/basket/');
					else {
						$this->_redirect('/basket/metod/frame/');
					}
				}
			} elseif (@$_POST['metod'] == 'frame') {
				$this->_redirect('/basket/metod/frame/?color=' . @$_POST['color'] . '&size=' . @$_POST['size']);
			}
		} else {
			//add view
			$article->setViews($article->getViews() + 1);
			$article->save();
			//add history
			$_SESSION['history'][$article->getId()] = 1;
			if ($this->ws->getCustomer()->getIsLoggedIn()) {
				$h = new Shoparticleshistory();
				$h->setCustomerId($this->ws->getCustomer()->getId());
				$h->setArticleId($article->getId());
				$h->save();
			}
		}
		if (isset($error)) {
			$this->view->error = $error;
		}

		$this->view->shop_item = $article;
		$this->view->category = $article->getCategory();
		$this->view->search = new Orm_Collection(array('brand' => $article->getBrand(), 'price' => ceil($article->getPrice() / 100) * 100));
		if ($this->get->metod == 'frame') echo $this->render('top.tpl.php');
		echo $this->render('shop/article.tpl.php');
		if ($this->get->metod == 'frame') echo $this->render('bottom.tpl.php');
		if ($this->get->metod == 'frame') die();

	}

	public function articleokAction()
	{

		echo $this->render('shop/article-ok.tpl.php');

	}
//2517
	public function basketAction() {
		if ($this->ws->getCustomer()->isAdmin()) {
			if (!$this->ws->getCustomer()->hasRight('do_pay')) {
//				die('Нету прав на заказ');
			}
		}
/*
		if (!$this->basket)
			$this->_redir('index');
*/
//		print_r($this->basket);die();
		$error = array();
		if (isset ($_GET['size']) and !@$_GET['size']) {
			$error [] = "Выберите размер";
		}
		if (isset ($_GET['color']) and !@$_GET['color']) {
			$error [] = "Выберите цвет";
		}
		foreach ($this->basket as $item) {
			$article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id']);
			if ($item['article_id'] == $article->getId() and $item['size'] == @$_GET['size'] and $item['color'] == @$_GET['color']) {
				$error [] = "Вы уже заказали " . $article->getBrand() . " " . $article->getModel() . ". Можете изменить количество.";
			}
		}
		if ('delete' == $this->cur_menu->getParameter()) {
			$basket = $_SESSION['basket'];
			if ($basket) {
				$_SESSION['basket'] = array();
				foreach ($basket as $key => $value)
					if ($key != (int)$this->get->getPoint())
						$_SESSION['basket'][] = $value;
				$this->_redir('basket');
			}
		}
		elseif ('change' == $this->cur_menu->getParameter() && $this->get->getCount()) {
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
		if (count($_POST)) {
			if (isset($_POST['tostep2'])) {
				foreach ($_POST as &$value) {
					$value = stripslashes(trim($value));
				}
				if ($this->post->deposit == 1) {
					$_SESSION['deposit'] = $this->ws->getCustomer()->getDeposit();
				}
				else {
					unset($_SESSION['deposit']);
				}
				if (!isset($_SESSION['basket_contacts'])) {
					$_SESSION['basket_contacts'] = array();
				}
				$this->_redirect('/shop-checkout-step2/');
			}
		}
		if (isset($error)) {
			$this->view->error = $error;
		}
		if ($this->get->metod == 'frame') echo $this->render('top.tpl.php');
		echo $this->render('shop/basket-step1_test.tpl.php');
		if ($this->get->metod == 'frame') echo $this->render('bottom.tpl.php');
		if ($this->get->metod == 'frame') die();
	}

	private function createBasketList($basket = false, $del_cost = 0)
	{
		if ($basket === false)
			$basket = & $this->basket;
		if (!$del_cost)
			$del_cost = Config::findByCode('delivery_cost')->getValue();

		$articles = array();
		$options = array();

		$bool = false;
		foreach ($basket as $item)
			if ($item['option_id'] > 0)
				$bool = true;
		if (!$bool)
			$options[] = array(
				'id' => 0,
				'option' => $this->trans->get('delivery option'),
				'count' => 1,
				'size' => $item['size'],
				'color' => $item['color'],
				'price' => $del_cost);
		$sum = 0;
		foreach ($basket as $item) {
			if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId()) {
				$title = $article->getTitle();
				if (isset($item['title'])) {
					$title = $item['title'];
				}

				$articles[] = array(
					'id' => $article->getId(),
					'title' => $title,
					'count' => $item['count'],
					'option_id' => $item['option_id'],
					'size' => $item['size'],
					'color' => $item['color'],
					'code' => $article->getCode(),
					'price' => $article->getRealPrice());
				$sum += $article->getRealPrice() * $item['count'];
				$op = $article->getOptions();
				if (isset($op[$item['option_id']]) && $item['option_id'] > 0) {
					$options[] = array(
						'id' => $item['option_id'],
						'option' => $op[$item['option_id']]->getOption(),
						'count' => $item['count'],
						'size' => $item['size'],
						'color' => $item['color'],
						'price' => $op[$item['option_id']]->getRealPrice());
					$articles[count($articles) - 1]['option_price'] = $op[$item['option_id']]->getRealPrice();
				}
			}
		}

		$discount = 0; //ceil(($sum + 500)/1000)-1;
		$_SESSION['order_amount'] = $sum;

		if ($discount) {
			$_SESSION['order_amount'] = round(($sum * (100 - $discount) / 100) * 100) / 100;
			foreach ($articles as &$item)
				$item['price'] = round(($item['price'] * (100 - $discount) / 100) * 100) / 100;
		}
		return array($articles, $options);
	}
//2517
	public function basketcontactsAction()
	{
if (($_SERVER['REMOTE_ADDR'] == '91.225.165.62')||($_SERVER['REMOTE_ADDR'] == '127.0.0.1')){
/*
		echo '<pre>';
		echo 'Доступ через офисную сеть.';
		echo '</pre>';
*/
		if ($this->ws->getCustomer()->isBan()) {
			$this->_redir('ban');
		}

		if ($this->ws->getCustomer()->isNoPayOrder()) {
			$this->_redir('nopay');
		}

		if (!$this->basket)
			$this->_redir('index');

		$errors = array();

		if ($_POST) {
//			echo '<pre>POST</pre>';

			foreach ($_POST as &$value)
				$value = stripslashes(trim($value));

			$_SESSION['basket_contacts'] = $_POST;

			// check for errors
			$info = & $_SESSION['basket_contacts'];
			$error_email = 0;
			if (!$this->ws->getCustomer()->getIsLoggedIn()) {
				if (wsActiveRecord::useStatic('Customer')->findByEmail($info['email'])->count() != 0) {
					$error_email = 1;
					$this->view->error_email = 'Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь.';
				}
			}
			$tel = Number::clearPhone(trim($info['telephone']));
			$allowed_chars = '1234567890';
			if (!Number::clearPhone($tel)) {
				$errors['error'] = "Введите телефонный номер";
				$errors[] = 'telephone';
			}
			for ($i = 0; $i < mb_strlen($tel); $i++) {
				if (mb_strpos($allowed_chars, mb_strtolower($tel[$i])) === false) {
					$errors['error'] = "В номере должны быть только числа";
					$errors[] = 'telephone';
				}
			}
			$alredy = wsActiveRecord::useStatic('Customer')->findFirst(array('phone1' => $tel));
			if ($alredy) {
				if ($alredy->getUsername() != null and $alredy->getId() != $this->ws->getCustomer()->getId()) {
					$errors['error'] = "Пользователь с таким номером телефона уже существует";
					$errors[] = 'telephone';
				}
			}

			foreach ($info as $k => $v)
				$info[$k] = strip_tags(stripslashes($v));

			if (!$info['name'])
				$errors[] = 'name';

			if (isset($info['middle_name'])) {
				if (!$info['middle_name'])
					$errors[] = 'middle_name';
			} else {
				$errors[] = 'middle_name';
			}

			if (!$info['delivery_type_id'])
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
					$errors[] = 'street';
				if (!$info['house'])
					$errors[] = 'house';
				if (!$info['flat'])
					$errors[] = 'flat';
				if (!$info['obl'])
					$errors[] = 'obl';
				/*if (!$info['rayon'])
					$errors[] = 'rayon';*/
				if (!$info['city'])
					$errors[] = 'city';
			}
			if (@$info['delivery_type_id'] == 9 or @$info['delivery_type_id'] == 10) { //Курьер
				if (!$info['last_name'])
					$errors[] = 'last_name';
				if (!$info['street'])
					$errors[] = 'street';
				if (!$info['house'])
					$errors[] = 'house';
			}
			if (@$info['delivery_type_id'] == 8) { //Новая почта
				if (!$info['city'])
					$errors[] = 'city';
				if (!$info['street'])
					$errors[] = 'street';
				if (!$info['house'])
					$errors[] = 'house';
				if (!$info['sklad'])
					$errors[] = 'sklad';
			}

			if (!$info['email'] || !isValidEmail($info['email']))
				$errors[] = 'email';

			if (!$errors and $error_email == 0) {
//				echo '<pre>!ERRORS</pre>';
				
				list($articles, $options) = $this->createBasketList();
				$_SESSION['basket_articles'] = $articles;
				$this->view->articles = $articles;
				$_SESSION['basket_options'] = $options;
				$this->view->options = $options;

/**************************************************************************************************************************************************/				

//												basketorderAction();

/**************************************************************************************************************************************************/
		$this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'];
		$check_c = array();
		foreach ($this->basket_articles as $key => $article) {
			$itemcs = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article['id'], 'id_size' => $article['size'], 'id_color' => $article['color']));
			if ($itemcs->getCount() == 0) {
				$check_c[$key] = $article;
			}
		}

		if (count($_SESSION['basket_articles']) and isset($_GET['recalculate']))
			$this->_redir('shop-checkout-step4');
		if (!count($_SESSION['basket_articles']) /* || !$this->basket_options*/)
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
			$post_order = true;
		}
		else {
//			echo '<pre>D3</pre>';
			if ($errors)
				$this->_redir('shop-checkout-step2');

			$curdate = Registry::get('curdate');

			$mas_adres = array();
			if (isset($info['index']) and mb_strlen($info['index']) > 1) {
				$mas_adres[] = $info['index'];
			}
			if (isset($info['obl']) and mb_strlen($info['obl']) > 1) {
				$mas_adres[] = $info['obl'];
			}
			if (isset($info['rayon']) and mb_strlen($info['rayon']) > 1) {
				$mas_adres[] = $info['rayon'];
			}
			if (isset($info['city']) and mb_strlen($info['city']) > 1) {
				$mas_adres[] = 'г. ' . $info['city'];
			}
			if (isset($info['street']) and mb_strlen($info['street']) > 1) {
				$mas_adres[] = 'ул. ' . $info['street'];
			}
			if (isset($info['house']) and mb_strlen($info['house']) > 1) {
				$mas_adres[] = 'д.' . $info['house'];
			}
			if (isset($info['flat']) and mb_strlen($info['flat']) > 1) {
				$mas_adres[] = 'кв.' . $info['flat'];
			}
			if (@$info['delivery_type_id'] == 8) { //Новая почта
				if (isset($info['sklad']) and mb_strlen($info['sklad']) > 0) {
					$mas_adres[] = 'склад НП: ' . $info['sklad'];
				}
			}
			$info['address'] = implode(', ', $mas_adres);

			$data = array(
				'status' => 0,
				'date_create' => $curdate->getFormattedMySQLDateTime(),
				'company' => isset($info['company']) ? $info['company'] : '',
				'name' => @$info['name'],
				'middle_name' => @$info['middle_name'],
				'address' => @$info['address'],
				'index' => @$info['index'],
				'street' => @$info['street'],
				'house' => @$info['house'],
				'flat' => @$info['flat'],
				'pc' => @$info['pc'],
				'city' => @$info['city'],
				'obl' => isset($info['obl']) ? $info['obl'] : '',
				'rayon' => @$info['rayon'],
				'sklad' => @$info['sklad'],
				'telephone' => @Number::clearPhone(trim($info['telephone'])),
				'email' => @$info['email'],
				'comments' => isset($info['comments']) ? $info['comments'] : '',
				'delivery_cost' => Shoporders::getDeliveryPrice(),
				'delivery_type_id' => @$info['delivery_type_id'],
				'payment_method_id' => @$info['payment_method_id'],
				'amount' => @$_SESSION['sum_to_ses'] ? @$_SESSION['sum_to_ses'] : @$_SESSION['sum_to_ses_no_dep'],
				'soglas' => 0,
				'oznak' => 0,
				'deposit' => @$_SESSION['deposit'],
				'call_my' => @$info['callmy'] ? 1 : 0,
				'quick' => 0,
				'kupon' => @$info['kupon']
			);
			$payment_method_id = $info['payment_method_id'];
			$deposit = 0;
			$order = new Shoporders();
			$order->import($data);
			
/*
			echo '<pre>';
			print_r($data);
			echo '</pre>';
*/
			if (@$info['dontcall']) {
				$order->setCallMy(2);
			}

			if (!isset($_SESSION['deposit'])) {
				$order->setDeposit(0);
			}

			if (@$_SESSION['deposit']) {
				$customer = new Customer($this->ws->getCustomer()->getId());
				$customer->setDeposit($customer->getDeposit() - $_SESSION['deposit']);
				$customer->save();
				$deposit = $_SESSION['deposit'];
				unset($_SESSION['deposit']);
			}

			if (($order->getAmount() + $order->getDeliveryCost()) == $order->getDeposit()) {
				$order->setStatus(14);
			}
			if ($order->getAmount() == 0) {
				$order->setStatus(8);
			}
			$order->save();
//			echo '<pre>'; print_r($order); echo '</pre>';
//			echo '<pre>'; print_r($_SESSION); echo '</pre>';
//			echo '<pre>'; print_r($this->basket_articles); echo '</pre>';

//			if (!$order->getId())
//				$this->_redir('basket');


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
					if ($s) {
						$a->setEventSkidka($s->getValue());
						$a->setEventId($s->getId());
					}
					$a->save();
				}
				else {
					$article['count'] = 0;
					$article['title'] = $article['title'] . ' (нет на складе)';
					$a = new Shoporderarticles();
					$a->setOrderId($order->getId());
					$data = $article;
					$data['article_id'] = $data['id'];
					unset($data['id']);
					$a->import($data);
					$s = Skidki::getActiv($item->getId());
					if ($s) {
						$a->setEventSkidka($s->getValue());
						$a->setEventId($s->getId());
					}
					$a->save();
				}
				echo '<pre>';
				print_r($order_id);
				echo '</pre>';
				echo '<pre>';
				print_r($a);
				echo '</pre>';
			}

			$this->basket = $this->view->basket = $_SESSION['basket'] = array();
			$this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'] = array();
			$this->basket_options = $this->view->basket_options = $_SESSION['basket_options'] = array();

//			$order = wsActiveRecord::useStatic('Shoporders')->findById($order->getId());

// TO DO : send mail to customer
			$admin_name = Config::findByCode('admin_name')->getValue();
			$admin_email = Config::findByCode('admin_email')->getValue();
			$do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
			$subject = Config::findByCode('email_order_subject')->getValue();

			$basket = $order->getArticles()->export();

			list($articles, $options) = $this->createBasketList($basket, $order->getDeliveryCost());
			$this->view->articles = $articles;
			$this->view->deposit = $deposit;
			$this->view->options = $options;
			$this->view->basket_contacts = $order;

			$msg = $this->render('email/basket.tpl.php');

			require_once('nomadmail/nomad_mimemail.inc.php');
			$mimemail = new nomad_mimemail();
			$mimemail->debug_status = 'no';
			$mimemail->set_from($do_not_reply, $admin_name);
			$mimemail->set_to($order->getEmail(), $order->getName());
			$mimemail->set_charset('UTF-8');
			$mimemail->set_subject($subject);
			$mimemail->set_text(make_plain($msg));
			$mimemail->set_html($msg);

			//@$mimemail->send();
			MailerNew::getInstance()->sendToEmail($order->getEmail(), $order->getName(), $subject, $msg);

			if (Config::findByCode('notify_admin')->getValue()) {
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

			$this->set_customer($order, $do_not_reply, $admin_name);

			$order_id = $order->getId();
			$order_amount = $order->calculateOrderPrice2();
			
			echo '<pre>';
			print_r($order);
			echo '</pre>';

			if ($order->getId()) {
				$order = new Shoporders($order->getId());
				$order->reCalculate();
				$phone = Number::clearPhone($order->getTelephone());

				include_once('smsclub.class.php');
				$sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
				$sender = Config::findByCode('sms_alphaname')->getValue();
				$user = $sms->sendSMS($sender, $phone, 'Ваш номер заказа ' . $order->getId() . ' на сумму ' . $order->calculateOrderPrice2() . ' грн. Тел. (044) 462-50-90');
				wsLog::add('SMS to user: ' . $sms->receiveSMS($user), 'SMS_' . $sms->receiveSMS($user));
				$admin_phone = Config::findByCode('admin_phone')->getValue();
				if (strlen($admin_phone) > 1) {
					$admin = $sms->sendSMS($sender, Config::findByCode('admin_phone')->getValue(), 'Новый заказ #' . $order->getId() . ' на сумму ' . $order->calculateOrderPrice2() . ' грн. Доставка ' . $order->delivery_type->getName());
					wsLog::add('SMS to admin: ' . $sms->receiveSMS($admin), 'SMS_' . $sms->receiveSMS($admin));
				}

				if (!$order->getCustomerId()) {
					$usr = Customer::findByUsername($order->getEmail());
					if ($usr->getId()) {
						$order->setCustomerId($usr->getId());
						$order->save();
					}
				}

				$find_count_orders_by_user = wsActiveRecord::useStatic('Shoporders')->count(array('customer_id' => $order->getCustomerId()));

				/* if (in_array($order->getDeliveryTypeId(), array(1, 2, 3, 7))) {*/
				if ($find_count_orders_by_user == 1) {
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
				}

				$find_sf = wsActiveRecord::useStatic('Event')->findFirst('sumforgift > 0 AND publick > 0', array('sumforgift' => 'ASC'));

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
					   /* $find_sf->setPublick(0);
						$find_sf->save();*/
					}
				}
			}

			$customer_id = $this->ws->getCustomer()->getId();
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
			}
//			204
//			echo $this->render('shop/basket-step4.tpl.php');
			if (in_array($payment_method_id, array(4, 5, 6))) {
				if ($payment_method_id == 4) {
					$paymaster = 21;
				}
				if ($payment_method_id == 5) {
					$paymaster = 1;
				}
				if ($payment_method_id == 6) {
					$paymaster = 20;
				}
/*
				echo '<pre>';
				echo $payment_method_id;
				echo '</pre>';

				echo '<pre>';
				echo $paymaster;
				echo '</pre>';
*/
/*
				$paymaster[4] = 21;	//	PaymasterCard (VISA/MASTERCARD)
				$paymaster[5] = 1;	//	WebMoney
				$paymaster[6] = 20;	//	Privat24
				$paymaster = $paymaster[$payment_method_id];
*/
				$order_id = $order->getId();
				$order_amount = $order->calculateOrderPrice2();
				
//				$order_amount = str_replace (',', '', $order_amount);
				
				$pay_data['LMI_MERCHANT_ID'] = 1212;
				$pay_data['LMI_PAYMENT_AMOUNT'] = $order_amount;
				$pay_data['LMI_PAYMENT_NO'] = $order_id;
				$pay_data['LMI_PAYMENT_DESC'] = 'Оплата за заказ № '.$order_id;
/*				
1 = Webmoney
6 = MoneXy
12 = EasyPay
15 = NSMEP
17 = Webmoney Terminal
21 = PaymasterCard
20 = Приват 24
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

/*
				$this->_redir('/payment/?LMI_MERCHANT_ID='.$pay_data['LMI_MERCHANT_ID'].
					'&LMI_PAYMENT_AMOUNT='.$pay_data['LMI_PAYMENT_AMOUNT'].
					'&LMI_PAYMENT_NO='.$pay_data['LMI_PAYMENT_NO'].
					'&LMI_PAYMENT_DESC='.$pay_data['LMI_PAYMENT_DESC'].
					'&LMI_PAYMENT_SYSTEM='.$pay_data['LMI_PAYMENT_SYSTEM'].
					'&LMI_SIM_MODE='.$pay_data['LMI_SIM_MODE'].
					'&LMI_HASH='.$pay_data['LMI_HASH']
				);
*/

				$LMI_MERCHANT_ID		= mysql_real_escape_string($pay_data['LMI_MERCHANT_ID']);
				$LMI_PAYMENT_AMOUNT		= mysql_real_escape_string($pay_data['LMI_PAYMENT_AMOUNT']);
				$LMI_PAYMENT_NO			= mysql_real_escape_string($pay_data['LMI_PAYMENT_NO']);
				$LMI_PAYMENT_DESC		= mysql_real_escape_string($pay_data['LMI_PAYMENT_DESC']);
				$LMI_PAYMENT_SYSTEM		= mysql_real_escape_string($pay_data['LMI_PAYMENT_SYSTEM']);
				$LMI_SIM_MODE			= mysql_real_escape_string($pay_data['LMI_SIM_MODE']);
				$LMI_HASH				= mysql_real_escape_string($pay_data['LMI_HASH']);
				
				$sql = "
					INSERT INTO
						pay_send
					(
						LMI_MERCHANT_ID,
						LMI_PAYMENT_AMOUNT,
						LMI_PAYMENT_NO,
						LMI_PAYMENT_DESC,
						LMI_PAYMENT_SYSTEM,
						LMI_SIM_MODE,
						LMI_HASH,
						pid
					)
					VALUES (
						'".$LMI_MERCHANT_ID."',
						'".$LMI_PAYMENT_AMOUNT."',
						'".$LMI_PAYMENT_NO."',
						'".$LMI_PAYMENT_DESC."',
						'".$LMI_PAYMENT_SYSTEM."',
						'".$LMI_SIM_MODE."',
						'".$LMI_HASH."',
						'".$payment_method_id."'
					)
				";
				wsActiveRecord::query($sql);

				$this->view->pay_data = $pay_data;
//				echo $this->render('payment/index.tpl.php');
/*
				header('Location: https://lmi.paymaster.ua/?LMI_MERCHANT_ID='.$pay_data['LMI_MERCHANT_ID'].
					'&LMI_PAYMENT_AMOUNT='.$pay_data['LMI_PAYMENT_AMOUNT'].
					'&LMI_PAYMENT_NO='.$pay_data['LMI_PAYMENT_NO'].
					'&LMI_PAYMENT_DESC='.$pay_data['LMI_PAYMENT_DESC'].
					'&LMI_PAYMENT_SYSTEM='.$pay_data['LMI_PAYMENT_SYSTEM'].
					'&LMI_SIM_MODE='.$pay_data['LMI_SIM_MODE'].
					'&LMI_HASH='.$pay_data['LMI_HASH']);
*/
			}
			else {
				$this->_redir('account');
			}
			$post_order = true;
		}

/**************************************************************************************************************************************************/				

//																basketorderAction();

/**************************************************************************************************************************************************/
			}
			$this->basket_contacts = $this->view->basket_contacts = $_SESSION['basket_contacts'];
		}

		$this->view->errors = $errors;
		if(!$post_order)
			echo $this->render('shop/basket-step22.tpl.php');
}
else {
	        if ($this->ws->getCustomer()->isBan()) {
            $this->_redir('ban');
        }

        if ($this->ws->getCustomer()->isNoPayOrder()) {
            $this->_redir('nopay');
        }

        /*  if ($this->get->deposit == 1) {
            $_SESSION['deposit'] = $this->ws->getCustomer()->getDeposit();
        } else {
            unset($_SESSION['deposit']);
        }*/

        if (!$this->basket)
            $this->_redir('index');

        $errors = array();

        if ($_POST) {

            foreach ($_POST as &$value)
                $value = stripslashes(trim($value));

            $_SESSION['basket_contacts'] = $_POST;

            // check for errors
            $info = & $_SESSION['basket_contacts'];
            $error_email = 0;
            if (!$this->ws->getCustomer()->getIsLoggedIn()) {
                if (wsActiveRecord::useStatic('Customer')->findByEmail($info['email'])->count() != 0) {
                    $error_email = 1;
                    $this->view->error_email = 'Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь.';
                }
            }
            $tel = Number::clearPhone(trim($info['telephone']));
            $allowed_chars = '1234567890';
            if (!Number::clearPhone($tel)) {
                $errors['error'] = "Введите телефонный номер";
                $errors[] = 'telephone';
            }
            for ($i = 0; $i < mb_strlen($tel); $i++) {
                if (mb_strpos($allowed_chars, mb_strtolower($tel[$i])) === false) {
                    $errors['error'] = "В номере должны быть только числа";
                    $errors[] = 'telephone';
                }
            }
            $alredy = wsActiveRecord::useStatic('Customer')->findFirst(array('phone1' => $tel));
            if ($alredy) {
                if ($alredy->getUsername() != null and $alredy->getId() != $this->ws->getCustomer()->getId()) {
                    $errors['error'] = "Пользователь с таким номером телефона уже существует";
                    $errors[] = 'telephone';
                }
            }

            foreach ($info as $k => $v)
                $info[$k] = strip_tags(stripslashes($v));


            if (!$info['name'])
                $errors[] = 'name';

            if (isset($info['middle_name'])) {
                if (!$info['middle_name'])
                    $errors[] = 'middle_name';
            } else {
                $errors[] = 'middle_name';
            }


            if (!$info['delivery_type_id'])
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
                    $errors[] = 'street';
                if (!$info['house'])
                    $errors[] = 'house';
                if (!$info['flat'])
                    $errors[] = 'flat';
                if (!$info['obl'])
                    $errors[] = 'obl';
                /*if (!$info['rayon'])
                    $errors[] = 'rayon';*/
                if (!$info['city'])
                    $errors[] = 'city';

            }
            if (@$info['delivery_type_id'] == 9 or @$info['delivery_type_id'] == 10) { //Курьер
                if (!$info['last_name'])
                    $errors[] = 'last_name';
                if (!$info['street'])
                    $errors[] = 'street';
                if (!$info['house'])
                    $errors[] = 'house';

            }
            if (@$info['delivery_type_id'] == 8) { //Новая почта
                if (!$info['city'])
                    $errors[] = 'city';
                if (!$info['street'])
                    $errors[] = 'street';
                if (!$info['house'])
                    $errors[] = 'house';
                if (!$info['sklad'])
                    $errors[] = 'sklad';

            }


            if (!$info['email'] || !isValidEmail($info['email']))
                $errors[] = 'email';
            if (!$errors and $error_email == 0)
                $this->_redir('shop-checkout-step3');

            $this->basket_contacts = $this->view->basket_contacts = $_SESSION['basket_contacts'];
        }

        $this->view->errors = $errors;

        echo $this->render('shop/basket-step2.tpl.php');

}
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

		list($articles, $options) = $this->createBasketList();

		$_SESSION['basket_articles'] = $articles;
		$this->view->articles = $articles;
		$_SESSION['basket_options'] = $options;
		$this->view->options = $options;
		echo $this->render('shop/basket-step3.tpl.php');
	}

	private function set_customer($order = null, $do_not_reply = null, $admin_name = null)
	{
		$allowedChars = 'abcdefghijklmnopqrstuvwxyz'
			. 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
			. '0123456789';
		$newPass = '';
		$allowedCharsLength = strlen($allowedChars);
		while (strlen($newPass) < 8)
			$newPass .= $allowedChars[rand(0, $allowedCharsLength - 1)];
		if (!$this->ws->getCustomer()->getIsLoggedIn()) {
			if (!wsActiveRecord::useStatic('Customer')->findFirst(array('username' => $order->getEmail()))) {
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
				$order->setCustomerId($customer->getId());
				$rez = $order->save();

				$this->view->login = $order->getEmail();
				$this->view->pass = $newPass;
				$subject = 'Создан акаунт';
				$msg = $this->render('email/new-customer.tpl.php');
				require_once('nomadmail/nomad_mimemail.inc.php');
				$mimemail = new nomad_mimemail();
				$mimemail->debug_status = 'no';
				$mimemail->set_from($do_not_reply, $admin_name);
				$mimemail->set_to($order->getEmail(), $order->getName());
				$mimemail->set_charset('UTF-8');
				$mimemail->set_subject($subject);
				$mimemail->set_text(make_plain($msg));
				$mimemail->set_html($msg);
				//@$mimemail->send();

				MailerNew::getInstance()->sendToEmail($order->getEmail(), $order->getName(), $subject, $msg);

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

			$order->setEventSkidka(EventCustomer::getEventsDiscont($this->ws->getCustomer()->getId()));
			$rez = $order->save();

		}
	}
//2517
	public function basketorderAction()
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

		$check_c = array();
		foreach ($this->basket_articles as $key => $article) {
			$itemcs = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article['id'], 'id_size' => $article['size'], 'id_color' => $article['color']));
			if ($itemcs->getCount() == 0) {
				$check_c[$key] = $article; //массив товаров которые в этот момент успели купить другие
			}
		}

		if (count($_SESSION['basket_articles']) and isset($_GET['recalculate']))
			$this->_redir('shop-checkout-step4');
		if (!count($_SESSION['basket_articles']) /* || !$this->basket_options*/)
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
		}
		else {
			// check for errors
			$errors = array();
			$info = & $this->basket_contacts;

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
					$errors[] = 'street';
				if (!$info['house'])
					$errors[] = 'house';
				if (!$info['flat'])
					$errors[] = 'flat';
				if (!$info['obl'])
					$errors[] = 'obl';
				/*if (!$info['rayon'])
					$errors[] = 'rayon';*/
				if (!$info['city'])
					$errors[] = 'city';
			}
			if (@$info['delivery_type_id'] == 9) { //Курьер
				if (!$info['street'])
					$errors[] = 'street';
				if (!$info['house'])
					$errors[] = 'house';
			}
			if (@$info['delivery_type_id'] == 8) { //Новая почта
				if (!$info['city'])
					$errors[] = 'city';
				if (!$info['street'])
					$errors[] = 'street';
				if (!$info['house'])
					$errors[] = 'house';
			}

			if (!$info['telephone'])
				$errors[] = 'telephone';
			if (!$info['email'] || !isValidEmail($info['email']))
				$errors[] = 'email';

			if ($errors)
				$this->_redir('shop-checkout-step2');

			$curdate = Registry::get('curdate');
			$order = new Shoporders();

			$mas_adres = array();
			if (isset($info['index']) and mb_strlen($info['index']) > 1) {
				$mas_adres[] = $info['index'];
			}
			if (isset($info['obl']) and mb_strlen($info['obl']) > 1) {
				$mas_adres[] = $info['obl'];
			}
			if (isset($info['rayon']) and mb_strlen($info['rayon']) > 1) {
				$mas_adres[] = $info['rayon'];
			}
			if (isset($info['city']) and mb_strlen($info['city']) > 1) {
				$mas_adres[] = 'г. ' . $info['city'];
			}
			if (isset($info['street']) and mb_strlen($info['street']) > 1) {
				$mas_adres[] = 'ул. ' . $info['street'];
			}
			if (isset($info['house']) and mb_strlen($info['house']) > 1) {
				$mas_adres[] = 'д.' . $info['house'];
			}
			if (isset($info['flat']) and mb_strlen($info['flat']) > 1) {
				$mas_adres[] = 'кв.' . $info['flat'];
			}
			if (@$info['delivery_type_id'] == 8) { //Новая почта
				if (isset($info['sklad']) and mb_strlen($info['sklad']) > 0) {
					$mas_adres[] = 'склад НП: ' . $info['sklad'];
				}
			}
			$info['address'] = implode(', ', $mas_adres);

			$data = array(
				'status' => 0,
				'date_create' => $curdate->getFormattedMySQLDateTime(),
				'company' => isset($info['company']) ? $info['company'] : '',
				'name' => @$info['name'],
				'middle_name' => @$info['middle_name'],
				'address' => @$info['address'],
				'index' => @$info['index'],
				'street' => @$info['street'],
				'house' => @$info['house'],
				'flat' => @$info['flat'],
				'pc' => @$info['pc'],
				'city' => @$info['city'],
				'obl' => isset($info['obl']) ? $info['obl'] : '',
				'rayon' => @$info['rayon'],
				'sklad' => @$info['sklad'],
				'telephone' => @Number::clearPhone(trim($info['telephone'])),
				'email' => @$info['email'],
				'comments' => isset($info['comments']) ? $info['comments'] : '',
				'delivery_cost' => Shoporders::getDeliveryPrice(),
				'delivery_type_id' => @$info['delivery_type_id'],
				'payment_method_id' => @$info['payment_method_id'],
				'amount' => @$_SESSION['sum_to_ses'] ? @$_SESSION['sum_to_ses'] : @$_SESSION['sum_to_ses_no_dep'],
				'soglas' => 0,
				'oznak' => 0,
				'deposit' => @$_SESSION['deposit'],
				'call_my' => @$info['callmy'] ? 1 : 0,
				'quick' => 0,
				'kupon' => @$info['kupon']
			);

			$deposit = 0;
			$order->import($data);

			if (@$info['dontcall']) {
				$order->setCallMy(2);
			}

			if (!isset($_SESSION['deposit'])) {
				$order->setDeposit(0);
			}

			if (@$_SESSION['deposit']) {
				$customer = new Customer($this->ws->getCustomer()->getId());
				$customer->setDeposit($customer->getDeposit() - $_SESSION['deposit']);
				$customer->save();
				$deposit = $_SESSION['deposit'];
				unset($_SESSION['deposit']);
			}

			$sub = Subscriber::findByEmail(@$info['email']);
			if (isset($info['podpis']) and (!$sub or $sub->getActive() == 0)) {
				$admin_email = Config::findByCode('admin_email')->getValue();
				$admin_name = Config::findByCode('admin_name')->getValue();
				$do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
				$subject = Config::findByCode('confirm_email_subject')->getValue();
				$code = 1 . '-';
				for ($i = 0; $i <= 6; $i++)
				$code .= mt_rand(0, 9);
				$this->view->status = 1;
				$this->view->code = $code;
				$this->view->post = $info;

				$msg = $this->view->render('subscribe/email-confirm.tpl.php');

				require_once('nomadmail/nomad_mimemail.inc.php');
				$mimemail = new nomad_mimemail();
				$mimemail->debug_status = 'no';
				$mimemail->set_to($info['email'], $info['name']);
				$mimemail->set_from($admin_email, $admin_name);
				$mimemail->set_charset('UTF-8');
				$mimemail->set_subject($subject);
				$mimemail->set_text($msg);
				$mimemail->set_html($msg);
				//@$mimemail->send();

				MailerNew::getInstance()->sendToEmail($info['email'], $info['name'], $subject, $msg);

				$id = ($s = Subscriber::findByEmail(@$info['email'])) ? $s->getId() : 0;
				$sub = new Subscriber($id);
				$sub->setName($info['name']);
				$sub->setEmail($info['email']);
				$sub->setConfirmed('');
				$sub->setCode($code);
				$sub->save();
			}

			if (($order->getAmount() + $order->getDeliveryCost()) == $order->getDeposit()) {
				$order->setStatus(14);
			}
			$order->save();
			if ($order->getAmount() == 0) {
				$order->setStatus(8);
				$order->save();
			}
//			echo "7"; var_dump($order->getId());
//			print_r($order);
			if (!$order->getId())
				$this->_redir('basket');

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
					if ($s) {
						$a->setEventSkidka($s->getValue());
						$a->setEventId($s->getId());
					}
					$a->save();
				}
				else {
					$article['count'] = 0;
					$article['title'] = $article['title'] . ' (нет на складе)';
					$a = new Shoporderarticles();
					$a->setOrderId($order->getId());
					$data = $article;
					$data['article_id'] = $data['id'];
					unset($data['id']);
					$a->import($data);
					$s = Skidki::getActiv($item->getId());
					if ($s) {
						$a->setEventSkidka($s->getValue());
						$a->setEventId($s->getId());
					}
					$a->save();
				}
			}

			$this->basket = $this->view->basket = $_SESSION['basket'] = array();
			$this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'] = array();
			$this->basket_options = $this->view->basket_options = $_SESSION['basket_options'] = array();

			$order = wsActiveRecord::useStatic('Shoporders')->findById($order->getId());

// TO DO : send mail to customer
			$admin_name = Config::findByCode('admin_name')->getValue();
			$admin_email = Config::findByCode('admin_email')->getValue();
			$do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
			$subject = Config::findByCode('email_order_subject')->getValue();

			$basket = $order->getArticles()->export();

			list($articles, $options) = $this->createBasketList($basket, $order->getDeliveryCost());
			$this->view->articles = $articles;
			$this->view->deposit = $deposit;
			$this->view->options = $options;
			$this->view->basket_contacts = $order;
			/*if ($_SERVER['REMOTE_ADDR']=='93.72.133.153')
								{
									echo $this->render('email/basket-toadmin.tpl.php');
									 $msg = $this->render('email/basket.tpl.php');
									die();

								}*/

			$msg = $this->render('email/basket.tpl.php');

			require_once('nomadmail/nomad_mimemail.inc.php');
			$mimemail = new nomad_mimemail();
			$mimemail->debug_status = 'no';
			$mimemail->set_from($do_not_reply, $admin_name);
			$mimemail->set_to($order->getEmail(), $order->getName());
			$mimemail->set_charset('UTF-8');
			$mimemail->set_subject($subject);
			$mimemail->set_text(make_plain($msg));
			$mimemail->set_html($msg);

			//@$mimemail->send();
			
			MailerNew::getInstance()->sendToEmail($order->getEmail(), $order->getName(), $subject, $msg);


			if (Config::findByCode('notify_admin')->getValue()) {
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


			$this->set_customer($order, $do_not_reply, $admin_name);


			if ($order->getId()) {
				$order = new Shoporders($order->getId());
				$order->reCalculate();
				$phone = Number::clearPhone($order->getTelephone());


				include_once('smsclub.class.php');
				$sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
				$sender = Config::findByCode('sms_alphaname')->getValue();
				$user = $sms->sendSMS($sender, $phone, 'Ваш номер заказа ' . $order->getId() . ' на сумму ' . $order->calculateOrderPrice2() . ' грн. Тел. (044) 462-50-90');
				wsLog::add('SMS to user: ' . $sms->receiveSMS($user), 'SMS_' . $sms->receiveSMS($user));
				$admin_phone = Config::findByCode('admin_phone')->getValue();
				if (strlen($admin_phone) > 1) {
					$admin = $sms->sendSMS($sender, Config::findByCode('admin_phone')->getValue(), 'Новый заказ #' . $order->getId() . ' на сумму ' . $order->calculateOrderPrice2() . ' грн. Доставка ' . $order->delivery_type->getName());
					wsLog::add('SMS to admin: ' . $sms->receiveSMS($admin), 'SMS_' . $sms->receiveSMS($admin));
				}

				if (!$order->getCustomerId()) {
					$usr = Customer::findByUsername($order->getEmail());
					if ($usr->getId()) {
						$order->setCustomerId($usr->getId());
						$order->save();
					}
				}

				$find_count_orders_by_user = wsActiveRecord::useStatic('Shoporders')->count(array('customer_id' => $order->getCustomerId()));

				/* if (in_array($order->getDeliveryTypeId(), array(1, 2, 3, 7))) {*/
				if ($find_count_orders_by_user == 1) {
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

				}

				$find_sf = wsActiveRecord::useStatic('Event')->findFirst('sumforgift > 0 AND publick > 0', array('sumforgift' => 'ASC'));

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
					   /* $find_sf->setPublick(0);
						$find_sf->save();*/
					}
				}

			}

			$customer_id = $this->ws->getCustomer()->getId();
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
			}
			echo $this->render('shop/basket-step4.tpl.php');

		}

	}
//2517
	public function basketorderquickAction()
	{
		if (@$_POST) {
			//____________________________start_check_inputs_________________________________________

			$_SESSION['basket_contacts'] = $_POST;

			// check for errors
			$errors = array();

			$info = & $_SESSION['basket_contacts'];
			$_SESSION['basket_contacts']['comments'] = @$info['comment'];

			$error_email = 0;
			if (!$this->ws->getCustomer()->getIsLoggedIn() and wsActiveRecord::useStatic('Customer')->findByEmail($info['email'])->count() != 0) {
				$error_email = 1;
				$errors['error'][] = 'Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь.';
			}
			$tel = Number::clearPhone(trim($info['telephone']));
			$allowed_chars = '1234567890';
			if (!Number::clearPhone($tel)) {
				$errors['error'][] = "Введите телефонный номер";
				$errors[] = 'telephone';
			}
			for ($i = 0; $i < mb_strlen($tel); $i++) {
				if (mb_strpos($allowed_chars, mb_strtolower($tel[$i])) === false) {
					$errors['error'][] = "В номоре должны быть только числа";
					$errors[] = 'telephone';
				}
			}
			$alredy = wsActiveRecord::useStatic('Customer')->findFirst(array('phone1' => $tel));
			if ($alredy and $alredy->getUsername() != null and $alredy->getId() != $this->ws->getCustomer()->getId()) {
				$errors['error'][] = "Пользователь с таким номером телефона уже существует";
				$errors[] = 'telephone';
			}

			foreach ($info as $k => $v)
				$info[$k] = strip_tags(stripslashes($v));
			foreach ($_POST as &$value)
				$value = stripslashes(trim($value));


			if (!$info['name'])
				$errors['error'][] = 'Неверное имя';

			if (!$info['email'] || !isValidEmail($info['email']))
				$errors['error'][] = 'Неверный email';

			if (!Number::clearPhone(trim($info['telephone'])) || !$info['telephone'] || !isValidTel)
				$errors['error'][] = 'Неверный телефон';

			if ($this->ws->getCustomer()->isBan())
				$errors['error'][] = "Доступ заблокирован";

			if ($this->ws->getCustomer()->isNoPayOrder())
				$errors['error'][] = "Доступ заблокирован";

			if (!isset($_POST['size']) or @$_POST['size'] == 0)
				$errors['error'][] = "Выберите размер.";

			if (!isset($_POST['color']) or @$_POST['color'] == 0)
				$errors['error'][] = "Выберите цвет.";


			//________________________end_check_inputs_________________________________________


			if (count($errors) == 0) {
				//____________________________start_add_to_basket____________________________________

				$article = wsActiveRecord::useStatic('Shoparticles')->findById(@$_POST['id']);

				$change = $article->addToBasket(1, (int)@$_POST['size'], (int)@$_POST['color'], (isset($_POST['option']) ? (int)@$_POST['option'] : 0));
				foreach (@$_POST as $key => $value) {
					$keys = explode('_', $key);
					if (strcasecmp($keys[0], 'sarticle') == 0 && (int)$value && ($sa = wsActiveRecord::useStatic('Shoparticles')->findById((int)$value)) && $sa->getId())
						if ($sa->addToBasket(1, (int)$_POST['size'], (int)$_POST['color'], (isset($_POST['soption_' . $keys[1]]) ? (int)@$_POST['soption_' . $keys[1]] : 0))
						)
							$change = true;
				}
				if ($change) {
					$this->basket = $_SESSION['basket'];
					$this->view->ok = true;
				}
				//echo 'change='.$change;


				//____________________________end_add_to_basket____________________________________


				list($articles, $options) = $this->createBasketList();

				$_SESSION['basket_articles'] = $articles;
				$_SESSION['basket_options'] = $options;


				//________________________start_added_fields____________________________________________


				$curdate = Registry::get('curdate');
				$order = new Shoporders();
				$data = array(
					'status' => 0,
					'date_create' => $curdate->getFormattedMySQLDateTime(),
					'company' => isset($info['company']) ? $info['company'] : '',
					'name' => @$info['name'],
					'middle_name' => isset($info['middle_name']) ? $info['middle_name'] : '',
					//'address' => isset($info['address']) ? $info['address'] : '',
					'index' => @$info['index'],
					'street' => @$info['street'],
					'house' => @$info['house'],
					'flat' => @$info['flat'],
					'pc' => @$info['pc'],
					'city' => isset($info['city']) ? $info['city'] : '',
					'obl' => isset($info['obl']) ? $info['obl'] : '',
					'rayon' => @$info['rayon'],
					'sklad' => @$info['sklad'],
					'telephone' => @Number::clearPhone(trim($info['telephone'])),
					'email' => @$info['email'],
					'comments' => isset($info['comments']) ? $info['comments'] : '',
					'delivery_cost' => Shoporders::getDeliveryPrice(),
					'delivery_type_id' => isset($info['delivery_type_id']) ? $info['delivery_type_id'] : '',
					'payment_method_id' => isset($info['payment_method_id']) ? $info['payment_method_id'] : '',
					'amount' => @$_SESSION['order_amount'],
					'soglas' => 1,
					'oznak' => 1,
					'deposit' => @$_SESSION['deposit'],
					'call_my' => @$info['callmy'] ? 1 : 0,
					'quick' => 1,
					'from_quick' => 1
				);
				//print_r($data);

				//$test1 =
				$order->import($data);
				//print_r ($test1);
				if (!isset($_SESSION['deposit'])) {
					$order->setDeposit(0);
				}
				if (@$_SESSION['deposit']) {
					$customer = new Customer($this->ws->getCustomer()->getId());
					$customer->setDeposit($customer->getDeposit() - $_SESSION['deposit']);
					$customer->save();
					$deposit = $_SESSION['deposit'];
					unset($_SESSION['deposit']);

				}

				//________________________end_added_fields_____________________________________________


				if (($order->getAmount() + $order->getDeliveryCost()) == $order->getDeposit()) {
					$order->setStatus(14);
				}

				$lastnq = wsActiveRecord::findByQueryFirstArray('SELECT MAX(quick_number) as quick_number FROM `ws_orders`');
				$order->setQuickNumber(++$lastnq['quick_number']);
				$order->save();

				if ($order->getAmount() == 0) {
					$order->setStatus(8);
					$order->save();
				}


				//________________________put order_to_db______________________________________________
				$this->set_customer($order, 0, 0);


				foreach ($_SESSION['basket_articles'] as $article) {

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
						if ($s) {
							$a->setEventSkidka($s->getValue());
							$a->setEventId($s->getId());
						}
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
						$s = Skidki::getActiv($item->getId());
						if ($s) {
							$a->setEventSkidka($s->getValue());
							$a->setEventId($s->getId());
						}
						$a->save();
					}

				}

				//____________________send_email_________________
				$order = wsActiveRecord::useStatic('Shoporders')->findById($order->getId());
				list($articles, $options) = $this->createBasketList($order->getArticles()->export(), $order->getDeliveryCost());
				$this->view->articles = $articles;
				$this->view->deposit = $deposit;
				$this->view->options = $options;
				$this->view->basket_contacts = $order;
				$msg = $this->view->render('email/basket-order-quick.tpl.php');
				$admin_name = Config::findByCode('admin_name')->getValue();
				$admin_email = Config::findByCode('admin_email')->getValue();
				$do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
				$subject = 'Принята заявка';
				require_once('nomadmail/nomad_mimemail.inc.php');
				$mimemail = new nomad_mimemail();
				$mimemail->debug_status = 'no';
				$mimemail->set_from($do_not_reply, $admin_name);
				$mimemail->set_to($order->getEmail(), $order->getName());
				$mimemail->set_charset('UTF-8');
				$mimemail->set_subject($subject);
				$mimemail->set_text(make_plain($msg));
				$mimemail->set_html($msg);
				//@$mimemail->send();

				MailerNew::getInstance()->sendToEmail($order->getEmail(), $order->getName(), $subject, $msg);				
				//____________________send_email________________

				//____________________send_sms__________________
				include_once('smsclub.class.php');
				$phone = Number::clearPhone($order->getTelephone());
				$sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
				$sender = Config::findByCode('sms_alphaname')->getValue();
				$user = $sms->sendSMS($sender, $phone, 'Ваш заявка №' . $order->getQuickNumber() . ' принята. Ожидайте звонок менеджера.');
				wsLog::add('SMS to user: ' . $sms->receiveSMS($user), 'SMS_' . $sms->receiveSMS($user));
				//____________________send_sms__________________


				$this->basket = $_SESSION['basket'] = array();
				$this->basket_articles = $_SESSION['basket_articles'] = array();
				$this->basket_options = $_SESSION['basket_options'] = array();

			}
		} else $errors['error'][] = "Ошибка с передачей информации";

		$this->view->errors = $errors;

		echo $this->render('shop/quick-order-result.php');
		exit;
	}

	public function pagetextAction()
	{

		echo $this->render('shop/static.tpl.php');

	}

	public function _redir($param)
	{
		if ($param == 'index')
			$this->_redirect(SITE_URL . '/');
		else
			$this->_redirect(SITE_URL . '/' . $param . '/');
	}


	public function mimageAction()
	{

		/*
		* width - int
		* height - int
		* crop - true || false
		* fill - true || false
		* fill_color - r_g_b (255_255_255)
		*/
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
		/*elseif ($get['type'] && $get['id'])
		$file = wsActiveRecord::useStatic('MFile')->findFirst(array('type' => $get['type'], 'id' => $get['id']));*/
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

					/*		header("HTTP/1.1 200 Ok", 1);*/

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
}
