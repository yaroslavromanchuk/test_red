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
     * @return карточка_товара
     */
    public function articleAction()
	{
		$article = wsActiveRecord::useStatic('Shoparticles')->findById($this->get->getId());
                    if(!$article){ $this->_redirect('/404/'); }
                   

		if ($this->get->metod == 'getbrand') {
			$text = '<div class="brand_info" ><p>';

			if ($article->article_brand->getImage()) {
				$text .= '<img src="' . $article->article_brand->getImage() . '" alt="' . $article->article_brand->getName() . '"></p>';
			}
			$text .= '<p><p class="strong">' . $article->article_brand->getName() . '</p>' . $article->article_brand->getText() . '</p></div>';
			die($text);
		}
                
    $this->view->g_url = $article->getPath(); 
$name = ucfirst($article->getModel()) . ' ' .trim($article->getBrand()).' '.$this->trans->get('color').' '.mb_strtolower($article->getColorName()->getName());
if($article->sizes->count() == 1){ $name.= ' размер '.$article->sizes[0]->size->size.' артикул - '.$article->sizes[0]->code; }   
$title = $name.' '.$this->trans->get('price').' '.$article->getPriceSkidka().'грн. '.Config::findByCode('website_title')->getValue(); 
                
		$this->cur_menu->setName($name);
                $this->cur_menu->setPageTitle($title);
                $this->cur_menu->setMetatagDescription($title.' '.$this->trans->get('description_exit'));
                
		
                if (strcasecmp($article->getActive(), 'y') != 0){
                    $this->view->active = 0;
                        }else{
                            $this->view->active = 1;
                        }
			//add view
			$article->setViews($article->getViews() + 1);
			$article->save();
			//add history
			if ($this->ws->getCustomer()->getIsLoggedIn()) {
                            if(!Shoparticleshistory::find('Shoparticleshistory', ['customer_id'=>$this->ws->getCustomer()->getId(), 'article_id'=>$article->getId()])->count()){
				$h = new Shoparticleshistory();
				$h->setCustomerId($this->ws->getCustomer()->getId());
				$h->setArticleId($article->getId());
				$h->save();
                            }
                        }else{
                            if(isset($_SESSION['hist'])){
                                if(!in_array($article->getId(), $_SESSION['hist']))
                                    {
                                        $_SESSION['hist'][] = $article->getId();
                                    }
                            }else{
                                $_SESSION['hist'][] = $article->getId();
                            }
                        }
		
		if (isset($error)) {
			$this->view->error = $error;
		}
                // $this->view->title = 'test';
                 
		$this->view->shop_item = $article;
		$this->view->category = $article->getCategory();

		echo $this->render('article/article.tpl.php');

                if ($this->get->metod == 'frame') { die(); }

	}
        
         public function quikviewAction() 
                 {
             $article = wsActiveRecord::useStatic('Shoparticles')->findById($this->get->getId());
                    if(!$article->id){
                        die(json_encode(array('error'=>1, 'message'=>'Непредвиденная ошибка')));
                        }
                        $article->setViews($article->getViews() + 1);
			$article->save();
                        
                $this->view->shop_item = $article;
		$this->view->category = $article->getCategory();
                $title = ucfirst($article->getModel()) . ' ' .trim($article->getBrand()).' '.$this->trans->get('color').' '.mb_strtolower($article->getColorName()->getName());; 
                die(json_encode(['title'=>$title, 'data'=>$this->render('article/quik_article.tpl.php')]));
                }
        /**
         * Добавление товара в корзину
         */
        public function addtocardAction() {
            
            $article = wsActiveRecord::useStatic('Shoparticles')->findById($this->get->getId());
                    if(!$article->id){
                        die(json_encode(array('error'=>1, 'message'=>'Непредвиденная ошибка')));
                        //$this->_redirect('/404/');
                        }
            if ($_POST){
			$error = self::articlepost($article, $_POST);

	if (count($error) == 0) {
$change = $article->addToBasket((int)$_POST['size'], (int)$_POST['color'], 0, 0, (isset($_POST['artikul']) ? $_POST['artikul'] : 0));

		if ($change['status']) {
			$messeg = $this->trans->get('ТОВАР ДОБАВЛЕН В КОРЗИНУ');
			die(json_encode(array('count'=>$change['count_card'], 'error'=>0, 'message'=>$messeg)));
                                }else{
                                    die(json_encode(array('error'=>1, 'message'=>$change['message'])));
                                }
			}else{
				die(json_encode(array('error'=>1, 'message'=>$error)));
			}
		}
            
            
             die();
        }
        
        private function articlepost($article, $post){
            $error = [];
            
            if (!isset($post['size']) or $post['size'] == 0) {
				$error [] = "Выберите размер.";
			}
			if (!isset($post['color']) or $post['color'] == 0) {
				$error [] = "Выберите цвет.";

			}

			foreach ($this->basket as $item) {
				if ($item['article_id'] == $article->getId() and $item['size'] == $post['size'] and $item['color'] == $post['color']) {
					$error [] = $this->trans->get('error_add_card_article');//"Этот товар уже есть в корзине</p>";
				}
			}
                        return $error;
        }

        /**
         * отобраные по id товары
         * @return представление с содержимим товаров
         * для вызова в get нужно создать url /articles/id/1,2,3...
         */
        public function articlesAction()
        {
           // var_dump($this->get);
            $page_onpage_order_by = FilterController::page_order_by();
           if($this->get->id){
               $act = new Shoparticlesoption((int)$this->get->id);
               $this->view->g_url = $act->getPathFind();
           }elseif($this->get->option){
                $act = new Shoparticlesoption((int)$this->get->option);
                 $this->view->g_url = $act->getPathFind();
           }
            $param = [];
            if($this->get->categories) {
           $param['categories'] = explode(',', $this->get->categories);
           
       }
       
       if($this->get->brands) {
           foreach (explode(',', $this->get->brands) as $v){
            if ($v) {
               $param['brands'][] = (int)Brand::findByQueryFirstArray("SELECT id FROM `red_brands` WHERE  `name` LIKE  '".$v."' ")['id'];
            }
            }
       }
       if($this->get->colors) {
           $param['colors'] = explode(',', $this->get->colors);
           
       }
       if($this->get->sizes) {
           $param['sizes'] = explode(',', $this->get->sizes);
           
       }
       if($this->get->labels) {
           $param['labels'] = explode(',', $this->get->labels);
           
       }
       if($this->get->skidka) {
           $param['skidka'] = explode(',', $this->get->skidka);
           
       }
       
       if($this->get->sezons) {
           foreach (explode(',', $this->get->sezons) as $v){
            if ($v) {
               $param['sezons'][] = (int)Shoparticlessezon::findByQueryFirstArray("SELECT * FROM  `ws_articles_sezon` WHERE  `translate` LIKE  '".$v."' ")['id'];
            }
            }
       }
       
       if($this->get->price_min) {$param['price']['min'] = $this->get->price_min;}
       if($this->get->price_max) {$param['price']['max'] = $this->get->price_max;}

            //$prod_on_page = $_COOKIE['items_on_page'];
                
               // if (!$prod_on_page){ $prod_on_page = Config::findByCode('products_per_page')->getValue();}

		//$this->view->per_page = $onPage = $prod_on_page;
		//$page = $this->get->page?(int)$this->get->page:0;
                

                if($this->get->id){
                    
                    $search_result = Filter::getArticlesList($this->get->id, $param, $page_onpage_order_by['order_by'],  $page_onpage_order_by['page'], $page_onpage_order_by['onPage']);
                    
                } elseif ($this->get->option){
                 
                     $search_result = Filter::getArticlesOptionList($act, $param,  $page_onpage_order_by['order_by'],  $page_onpage_order_by['page'], $page_onpage_order_by['onPage']);
                   
                }else{
                    
                      $search_result = [];
                }
       // $this->view->price_min = $search_result['parametr']['price_min']?$search_result['parametr']['price_min']:0;
	//$this->view->price_max = $search_result['parametr']['price_max']?$search_result['parametr']['price_max']:1; 
        
        $this->view->filters = $search_result['parametr'];
        $this->view->result_count = $search_result['count'];
        $this->view->total_pages = $search_result['pages'];
        $this->view->articles = $search_result['articles'];
	$this->view->result = $this->view->render('finder/list.tpl.php');
            echo $this->render('finder/result.tpl.php');
        }
}
