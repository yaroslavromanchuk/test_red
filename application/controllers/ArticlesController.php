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
     //public function init() {
       // parent::init();
        
   // }
    /**
     * Карточка товара
     * url - /product/id/
     */
    public function articleAction()
	{
        $this->view->critical_css = [
          //  '/css/catalog/catalog.css', 
            '/css/article/article.css', 
             '/js/gallery/lightgallery.css',
        ];
        $this->view->css = [
          //  '/js/slider-fhd/slick.css',
          //  '/css/cloudzoom/cloudzoom.css',
           // '/css/jquery.lightbox-0.5.css',
                //   '/js/gallery/lightgallery.css',
                //  '/js/owl/owl-carousel.css',
                //  '/js/jetzoom/jetzoom.css',
             
        ];
        $this->view->scripts = [
            // '/js/filter.js',
            '/js/desires.js',
            '/js/call/jquery.mask.js',
           // '/js/cloud-zoom.1.0.2.js',
          //  '/js/jquery.lightbox-0.5.js',
         //   '/js/slider-fhd/slick.min.js',
            '/js/gallery/lightgallery.js',
            '/js/owl/owl.carousel.min.js',
            '/js/jetzoom/jetzoom.js',
           // '/js/jquery.cycle.all.js', 
            
        ];
    //   if($this->ws->getCustomer()->id == 8005){ 
           // l($this->get->count());
           // l($this->get);
          //  l($_GET);
     // }
          //  if($this->get->count() != 3){
            //   $this->_redirect('/404/'); 
          //  }
		$article = wsActiveRecord::useStatic('Shoparticles')->findById($this->get->getId());
                    if(!$article){
                     // $red = wsActiveRecord::findByQueryFirstArray("SELECT * FROM ws_articles WHERE redirect = ".$this->get->getId());
                      
                       // if($red){
                         //    $this->_redirect('/product/id/'.$red->url);
                      //  }
                        
                        $this->_redirect('/404/');
                        
                        
                    }
                    if($article->stock == '0' && !$this->ws->getCustomer()->isAdmin()){
                        if($article->category->controller){
                       redirect301($article->getPathCategory());
                        }else{
                            redirect301('/new/all/');
                        }
                    }
		if ($this->get->metod == 'getbrand') {
			$text = '<div class="brand_info" ><p>';

			if ($article->article_brand->getImage()) {
				$text .= '<img src="' . $article->article_brand->getImage() . '" alt="' . $article->article_brand->getName() . '"></p>';
			}
			$text .= '<p><p class="strong">' . $article->article_brand->getName() . '</p>' . $article->article_brand->getText() . '</p></div>';
			die($text);
		}
    $this->view->g_url = $url = $article->getPath(); 
    if($url!= $_SERVER['PHP_SELF']){
    $this->cur_menu->nofollow = 1;
}
$name = ucfirst($article->getModel()) . ' ' .trim($article->getBrand()).' '.$this->trans->get('color').' '.mb_strtolower(@$article->getColorName()->getName());
$title = $name;
//if($article->sizes->count() == 1){
   
    
//} 
$title.=' '.$this->trans->get('price').' '.$article->getPriceSkidka().'грн. ';
$title.= 'размер '.$article->sizes[0]->size->size.' артикул - '.$article->sizes[0]->code; 
$title.=' '.Config::findByCode('website_title')->getValue(); 

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
		$this->view->shop_item = $article;
		$this->view->category = $article->getCategory();
                $this->view->similar = $this->similar($article); // Похожие товары
                if(/*$this->ws->getCustomer()->isAdmin()*/true){
                    echo $this->render('article/article.tpl1.php');
                }else{
                    echo $this->render('article/article.tpl.php');
                }
		
                if ($this->get->metod == 'frame') { die(); }
	}
        /**
     * Карточка товара new
     * url - /p/id/
     */
    public function articlAction()
	{
        $this->view->critical_css = [
            '/css/catalog/catalog.css', 
            '/css/article/article.css', 
        ];
        $this->view->css = [
            '/js/slider-fhd/slick.css',
           // '/css/cloudzoom/cloudzoom.css',
            '/css/jquery.lightbox-0.5.css',
            '/js/gallery/lightgallery.css',
            '/js/owl/owl-carousel.css',
            '/js/jetzoom/jetzoom.css',
             
        ];
        $this->view->scripts = [
            // '/js/filter.js',
            '/js/desires.js',
            '/js/call/jquery.mask.js',
           // '/js/cloud-zoom.1.0.2.js',
            '/js/jquery.lightbox-0.5.js',
            '/js/slider-fhd/slick.min.js',
            '/js/gallery/lightgallery.js',
            '/js/owl/owl.carousel.min.js',
            '/js/jetzoom/jetzoom.js',
            '/js/jquery.cycle.all.js',
            
        ];
       // if($this->user->id == 8005){ 
            l($this->get->count());
           l($this->get);
       // }
         l($_SERVER['PHP_SELF']);
            if($this->get->count() != 3){
               $this->_redirect('/404/'); 
            }
            
		$article = wsActiveRecord::useStatic('Shoparticles')->findUrl($this->get->getT());
                    if(!$article){
                     // $red = wsActiveRecord::findByQueryFirstArray("SELECT * FROM ws_articles WHERE redirect = ".$this->get->getId());
                      
                       // if($red){
                         //    $this->_redirect('/product/id/'.$red->url);
                      //  }
                        
                        $this->_redirect('/404/');
  
                    }

$this->view->g_url = $url = $article->getPath(); 
if($url!= $_SERVER['PHP_SELF']){
    $this->cur_menu->nofollow = 1;
}
$name = ucfirst($article->getModel()) . ' ' .trim($article->getBrand()).' '.$this->trans->get('color').' '.mb_strtolower(@$article->getColorName()->getName());
$title = $name;
//if($article->sizes->count() == 1){
   
    
//} 
$title.=' '.$this->trans->get('price').' '.$article->getPriceSkidka().'грн. ';
$title.= 'размер '.$article->sizes[0]->size->size.' артикул - '.$article->sizes[0]->code; 
$title.=' '.Config::findByCode('website_title')->getValue(); 

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
		$this->view->shop_item = $article;
		$this->view->category = $article->getCategory();
                $this->view->similar = $this->similar($article); // Похожие товары
                if(/*$this->ws->getCustomer()->isAdmin()*/true){
                    echo $this->render('article/article.tpl1.php');
                }else{
                    echo $this->render('article/article.tpl.php');
                }
		
                if ($this->get->metod == 'frame') { die(); }
	}
        /**
         * Похожие товары
         * @param type $article
         * @return type
         */
        function similar($article){
         return    wsActiveRecord::useStatic('Shoparticles')->findAll(['category_id'=>$article->getCategoryId(), 'status'=>3, 'active'=>'y', 'stock not like "0"',  'id !='.$article->getId()],['ctime'=>'ASC'], [0,10]);
        }
        /**
         * Быстрый просмотр товара
         */
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
                $title = ucfirst($article->getModel()) . ' ' .trim($article->getBrand()).' '.$this->trans->get('color').' '.mb_strtolower($article->getColorName()->getName());
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
           
$change = $article->addToBasket((int)$_POST['size'], (int)$_POST['color'], isset($_POST['artikul'])?$_POST['artikul']:0, $this->ws->getCustomer()->getIsLoggedIn()?$this->ws->getCustomer():false);

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
         * отобраные по id товары с акции
         * @return представление с содержимим товаров
         * для вызова в get нужно создать url /articles/id/1,2,3...
         */
        public function articlesAction()
        {
            $this->view->critical_css = [
            //'/css/catalog/catalog.css', 
            '/css/article/article.css', 
        ];
            $this->view->css = [
            '/css/cloudzoom/cloudzoom.css',
            '/css/jquery.lightbox-0.5.css',
            '/js/select2/css/select2.min.css',
        ];
            $this->view->scripts = [ 
                 '/js/filter.js',
            '/js/jquery.cycle.all.js',
                '/js/cloud-zoom.1.0.2.js',
            '/js/jquery.lightbox-0.5.js',
            '/lib/select2/js/select2.min.js'
                ];
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
 //if($this->ws->getCustomer()->id == 8005){ l($page_onpage_order_by); l($this->get); }
       //  if($this->ws->getCustomer()->isAdmin()){l($this->get);}
            
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


                if($this->get->id){
                    
                    $search_result = Filter::getArticlesList($this->get->id, $param, $page_onpage_order_by['order_by'],  $page_onpage_order_by['page'], $page_onpage_order_by['onPage']);
                    
                } elseif ($this->get->option){
                 $meta_param = [
                     'option' => $act,
           'filter' => $param,
           'order_by' => $page_onpage_order_by['order_by'],
           'page' => $page_onpage_order_by['page'],
           'Onpage' => $page_onpage_order_by['onPage']
       ];
                     $search_result = Filter::getArticlesOptionList($meta_param);
                  
                    $this->cur_menu->nofollow = 1; 
                   $this->cur_menu->setName($act->option_text);
                   $this->cur_menu->setPageTitle('Акции: '.$act->option_text);
                   $this->cur_menu->setMetatagDescription($act->option_text.' '.Translator::get('description_exit'));
                     
                }else{
                    
                      $search_result = [];
                }
                 

       // $this->view->filters = $search_result['parametr'];
        $this->view->result_count = $search_result['count'];
        $this->view->total_pages = $search_result['pages'];
        $this->view->articles = $search_result['articles'];
	$this->view->result = $this->view->render('finder/list.tpl.php');
            echo $this->render('finder/result.tpl.php');
        }
        
      
}
