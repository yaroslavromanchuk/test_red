<?php
/**
 * Description of ArticlesController
 * контроллер товаров
 * @author PHP
 * 
 */
class ArticlesController extends controllerAbstract
{
    /**
     * карточка товара
     * для вызова в get нужно создать url /product/id/1
     * @return представление карточки  товара
     */
    public function articleAction()
	{
		$article = wsActiveRecord::useStatic('Shoparticles')->findById($this->get->getId());
                    if(!$article){$this->_redirect('/404');}

		if ($this->get->metod == 'getbrand') {
			$text = '<div class="brand_info" ><p>';

			if ($article->article_brand->getImage()) {
				$text .= '<img src="' . $article->article_brand->getImage() . '" alt="' . $article->article_brand->getName() . '"></p>';
			}
			$text .= '<p><p class="strong">' . $article->article_brand->getName() . '</p>' . $article->article_brand->getText() . '</p></div>';
			die($text);
		}

		$this->view->getCurMenu()->setPageTitle($article->getCategory()->getName() . ' - ' . $article->getModel() . ' ' . $article->getBrand());

		if (strcasecmp($article->getActive(), 'y') != 0){
                    $this->view->active = 0;
                        }else{
                            $this->view->active = 1;
                        }
		if ($_POST){
			$error = array();
			if (!isset($_POST['size']) or $_POST['size'] == 0) {
				$error [] = "Выберите размер.";
			}
			if (!isset($_POST['color']) or @$_POST['color'] == 0) {
				$error [] = "Выберите цвет.";

			}

			foreach ($this->basket as $item) {
				if ($item['article_id'] == $article->getId() and $item['size'] == $_POST['size'] and $item['color'] == $_POST['color']) {
					$error [] = "<p style='color: red;'>".$this->trans->get('Этот товар уже есть в корзине').".</p>";
				}
			}
			if (count($error) == 0) {
$change = $article->addToBasket(1, (int)$_POST['size'], (int)$_POST['color'], (isset($_POST['option']) ? (int)$_POST['option'] : 0), 0, (isset($_POST['artikul']) ? $_POST['artikul'] : 0) , $_POST['skidka_block']?$_POST['skidka_block']:0);
				foreach ($_POST as $key => $value) {
					$keys = explode('_', $key);
					if (strcasecmp($keys[0], 'sarticle') == 0 && (int)$value && ($sa = wsActiveRecord::useStatic('Shoparticles')->findById((int)$value)) && $sa->getId())
						if ($sa->addToBasket(1, (int)$_POST['size'], (int)$_POST['color'], (isset($_POST['soption_' . $keys[1]])
							? (int)$_POST['soption_' . $keys[1]] : 0), 0, (isset($_POST['artikul'])
					? $_POST['artikul'] : 0), $_POST['skidka_block']?$_POST['skidka_block']:0)
						)
							$change = true;
				}
				if ($change) {
				$count = 0;
				$sum = 0;
				foreach ($_SESSION['basket'] as $item) {
                $count += $item['count'];
                $sum += $item['price'] * $item['count'];
            }
			$messeg = "<p style='color:#4bab4c;'>".$this->trans->get('ТОВАР ДОБАВЛЕН В КОРЗИНУ')."!</p>";
			die(json_encode(array('count'=>$count, 'sum'=>$sum, 'error'=>0, 'message'=>$messeg)));
					$this->basket = $this->view->basket = $_SESSION['basket'];
					$this->view->ok = true;
                                        if ($_POST['metod'] != 'frame'){ $this->_redirect('/basket/');} else {

						$this->_redirect('/basket/metod/frame/');
					}
				}
			} elseif ($this->post->metod == 'frame') {
				//$this->_redirect('/basket/metod/frame/?color=' . @$_POST['color'] . '&size=' . @$_POST['size']);
				die(json_encode(array('error'=>1, 'message'=>$error)));
			}
		} else {
			//add view
			$article->setViews($article->getViews() + 1);
			$article->save();
			//add history
			//$_SESSION['history'][$article->getId()] = 1;
			$_SESSION['hist'][] = $article->getId();
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
		if ($this->get->metod == 'frame_admin'){
			//echo $this->render('shop/article.tpl.php');
		echo $this->render('top.tpl.php');
		}else{
		echo $this->render('shop/article.tpl.php');
		}
		//if ($this->get->metod == 'frame') echo $this->render('bottom.tpl.php');
		if ($this->get->metod == 'frame') die();

	}
        /**
         * отобраные по id товары
         * @return представление с содержимим товаров
         * для вызова в get нужно создать url /articles/id/1,2,3...
         */
        public function articlesAction()
        {
            
            $prod_on_page = $_COOKIE['items_on_page'];
                
                                if (!$prod_on_page){ $prod_on_page = Config::findByCode('products_per_page')->getValue();}

		$this->view->per_page = $onPage = $prod_on_page;
		$page = $this->get->page?(int)$this->get->page:0;
                

                if($this->get->id){
                    
                    $search_result = Filter::getArticlesList($this->get->id, $this->get->order_by,  $page, $onPage);
                    
                } elseif ($this->get->option){
                 
                     $search_result = Filter::getArticlesOptionList($this->get->option,  $this->get->order_by,  $page, $onPage);
                   
                }else{
                    
                      $search_result = [];
                }

           $this->view->filters = $search_result['parametr'];

            $this->view->cur_page = $page;

            $this->view->result_count = $search_result['count'];

            $this->view->total_pages = $search_result['pages'];
            $this->view->articles = $search_result['articles'];
            $this->view->result = $this->view->render('finder/list.tpl.php');
                
            $this->view->price_min = $search_result['min_max'] ? $search_result['min_max'][0]->min: 0;
            $this->view->price_max = $search_result['min_max'] ? $search_result['min_max'][0]->max : 1;
                
                echo $this->render('finder/result.tpl.php');
        }
}
