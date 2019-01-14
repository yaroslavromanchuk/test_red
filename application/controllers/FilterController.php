<?php

class FilterController extends controllerAbstract {

    public function indexAction()
            {
        $search_word = Orm_Statement::escape(iconv('windows-1251','UTF-8//IGNORE', $this->get->s));
        if (LOCAL){
            $search_word = 'куртка';
        }
       
        $this->getfilter($search_word);
    }

    function getfilter1($search_word = '', $category = '')
            {
        $prod_on_page = (int)@$_SESSION['items_on_page'];
        if(!$prod_on_page) {
            $prod_on_page =  Config::findByCode('products_per_page')->getValue();
        }

        $this->view->per_page = $onPage = $prod_on_page;

        $this->view->filters = Finder::getAllEnabledParametrs($search_word, false, $category);
        //d($this);
        $page = (int)$this->get->page;

		
        $search_result = Finder::getArticlesByWord($search_word, array(), $page, $onPage, $category);
        if (@$_GET['view'] == 'all') {
            $page = 0;
            $onPage = $search_result['count'];
            $this->view->per_page = $onPage;
            $this->view->view_all = 1;
        } else {
            $this->view->view_all = 0;
        }
        $this->view->cur_page = $page;
        $this->view->items_count =  $search_result['count'];;
        $this->view->page_count = ceil($search_result['count'] / $onPage) - 1;
        $this->view->result_count = $search_result['count'];
        $this->view->total_pages = $search_result['pages'];

        $this->view->articles =  $search_result['articles'];
        $this->view->result = $this->view->render('finder/list.tpl.php');
		$this->view->search_word = $search_result['search'];
        $this->view->price_min = $search_result['min_max']->getMin();
        $this->view->price_max = $search_result['min_max']->getMax();
        echo $this->render('finder/result.tpl.php');
    }

    public function category1Action()
            {
        $category = new Shopcategories($this->get->id);
        $this->view->category = $category;
        $this->view->finder_category = $this->get->id;
        
        $this->getsearch(false, $this->get->id);
    }


    public function ajaxsearch1Action()
            {
        $search_word =  $this->get->search_word? $this->get->search_word: $this->post->search_word;
        $search_word = str_replace(array('\\','"'), '' , $search_word);

        $parametrs = array('categories'=>array(), 'colors'=>array(), 'sizes'=>array(), 'labels'=>array(), 'brands'=>array(), 'sezons'=>array(),'skidka'=>array() );
        //categories
        $t = explode(',', $this->get->categories ? $this->get->categories : $this->post->categories);
        foreach ($t as $v){
            if ($v){
                $parametrs['categories'][] = (int)$v;
            }
        }
        //colors
        $t = explode(',', $this->get->colors?$this->get->colors:$this->post->colors);
        foreach ($t as $v){
            if ($v){
                $parametrs['colors'][] = (int)$v;
            }
        }
        //sizes
        $t = explode(',', $this->get->sizes?$this->get->sizes:$this->post->sizes);
        foreach ($t as $v){
            if ($v){
                $parametrs['sizes'][] = (int)$v;
            }
        }
        //labels
        $t = explode(',', $this->get->labels?$this->get->labels:$this->post->labels);
        foreach ($t as $v){
            if ($v){
                $parametrs['labels'][] = mysql_real_escape_string($v);
            }
        }
		//sezons
        $t = explode(',', $this->get->sezons?$this->get->sezons:$this->post->sezons);
        foreach ($t as $v){
            if ($v){
                $parametrs['sezons'][] = mysql_real_escape_string($v);
            }
        }
		//sezons
        $t = explode(',', $this->get->skidka?$this->get->skidka:$this->post->skidka);
        foreach ($t as $v){
            if ($v){
                $parametrs['skidka'][] = mysql_real_escape_string($v);
            }
        }
        //brands
        $t = explode(',', $this->get->brands?$this->get->brands:$this->post->brands);
        foreach ($t as $v){
            if ($v){
                $parametrs['brands'][] = mysql_real_escape_string($v);
            }
        }
        $enable_all_child_categories = false;
        if (!count($parametrs['brands'])
            && !count($parametrs['labels'])
                && !count($parametrs['sizes'])
					&& !count($parametrs['colors'])
						&& !count($parametrs['sezons'])){
            $enable_all_child_categories = true;
        }
        //
       // $parametrs['prices_min_max'] = array(
          //  'min'=>$this->post->price_min?$this->post->price_min:$this->get->price_min,
          //  'max'=>$this->post->price_max?$this->post->price_max:$this->get->price_max
      //  );

        $page = $this->get->page?(int)$this->get->page:$this->post->page;
        if((int)$this->post->on_page){
            $_SESSION['items_on_page'] = (int)$this->post->on_page;
        }else{
            $_SESSION['item_on_page'] =  Config::findByCode('products_per_page')->getValue();
        }
        
        $prod_on_page = (int)$_SESSION['items_on_page'];
              if(!$prod_on_page) {
                  $prod_on_page =  Config::findByCode('products_per_page')->getValue();
              }

             $onPage = $prod_on_page;



        $articles = Finder::getArticlesByWord($search_word, $parametrs, $page, $onPage, $this->post->selected_root_category, $this->post->order_by);


        $total = $articles['count'];
        $pages = $articles['pages'];

        $this->view->articles = $articles['articles'];
        $this->view->cur_page = $this->post->page;
        $this->view->total_pages = $pages;

       // $articles = 

        $enabled_params = Finder::getAllEnabledParametrs($search_word, $parametrs, false, $enable_all_child_categories);

        $result = [
            'result'=>$this->render('finder/list.tpl.php'),
            'enabled_params'=>$enabled_params,
            'total_count'=>$total,
            'total_pages'=>$pages
        ];
        
        die(json_encode($result));
    }
    /**
     * 
     * @param type $category - Обьект - категория/ Обьезательный параметр
     * @param type $url - если нужно передать урл, иначе автоматически формируется ''
     * @param type $search - в случаи поиска передаем текст иначе пустота ''
     * @return отфильтрованій товар
     */
    public  function getFilter($category, $url = '', $search = '')
            {
        
        $page_onpage_order_by = self::page_order_by();
        
       if($url == ''){ $this->view->g_url = $category->getPath(); }
       if($search != ''){ $this->view->search_word = $search; }
        $param = [];
       if($this->get->categories) {
           $param['categories'] = explode(',', $this->get->categories);
           
       }
       if(count($_GET)){
          // print_r($_GET);
           $this->cur_menu->nofollow = 1;
       }
       
       if($this->get->brands) {
         //  $b= [];
          // $param['brands'] = $this->get->brands;
           foreach (explode(',', $this->get->brands) as $v){
            if ($v) {
                //echo $v;
               $param['brands'][] = (int)Brand::findByQueryFirstArray("SELECT id FROM `red_brands` WHERE  `name` LIKE  '".$v."' ")['id'];
            }
            }
            //$param['brands'] = implode(',', $b);
           
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
        //   $sez = [];
           foreach (explode(',', $this->get->sezons) as $v){
            if ($v) {
               $param['sezons'][] = (int)Shoparticlessezon::findByQueryFirstArray("SELECT * FROM  `ws_articles_sezon` WHERE  `translate` LIKE  '".$v."' ")['id'];
            }
            }
           // $param['sezons'] = implode(',', $sez);
       }
       
       if($this->get->price_min) {$param['price']['min'] = $this->get->price_min;}
       if($this->get->price_max) {$param['price']['max'] = $this->get->price_max;}

         $search_result = Filter::getArticlesFilter($search, $param, $category, $page_onpage_order_by['order_by'], $page_onpage_order_by['page'], $page_onpage_order_by['onPage']);
         if($search_result['meta']) {
                   if($search_result['meta']['nofollow']) { $this->cur_menu->nofollow = 1; } 
                   if($search_result['meta']['h1']) {  $this->cur_menu->setName($search_result['meta']['h1']);}
                   if($search_result['meta']['title']) { $this->cur_menu->setPageTitle($search_result['meta']['title']);}
                   if($search_result['meta']['descriptions']) { $this->cur_menu->setMetatagDescription($search_result['meta']['descriptions']);}
                 
                   
                   self::futterText($search_result['meta']['footer'], $category);
                   
                /*   if($search_result['meta']['footer']){
                        $this->cur_menu->setPageFooter('');
                   }else{
                        $this->cur_menu->setPageFooter($category->getFooter());
                   }
                   */
                   
               }else{
                   self::get_cur_menu($category);
               }
        $this->view->filters = $search_result['parametr'];
        $this->view->result_count = $search_result['count'];
        $this->view->total_pages = $search_result['pages'];
        $this->view->articles = $search_result['articles'];
	$this->view->result = $this->view->render('finder/list.tpl.php');
            echo $this->render('finder/result.tpl.php');
            }
            
            public function futterText($param, $category) {
                if(@$param['block']){
                $this->cur_menu->setPageFooter('');
            }elseif($param['text']){
                $this->cur_menu->setPageFooter($param['text']);
            }else{
                     $this->cur_menu->setPageFooter($category->getFooter());
                }
            }
            
            
          public  function page_order_by(){
              
                   $this->view->order_by = $order_by = $this->get->order_by;

               
                if($_COOKIE['items_on_page']){
                    $this->view->per_page = $onPage = $_COOKIE['items_on_page'];
                }else{
                     $this->view->per_page = $onPage = Config::findByCode('products_per_page')->getValue();
                }
              
                $this->view->cur_page = $page = (int)$this->get->page?(int)$this->get->page:0;
                return ['page' => $page, 'onPage' => $onPage, 'order_by' =>$order_by];
            }
            
            public function get_cur_menu($category) { 
                    $this->cur_menu->setName(($category->getH1()?$category->getH1():$category->getName()).' '.$this->trans->get('в интернет магазине RED'));
                    $this->cur_menu->setPageTitle(($category->getTitle()?$category->getTitle():$category->getName()).' '.$this->trans->get('dop_title'));
                    $this->cur_menu->setMetatagDescription($category->getDescription());
                    $this->cur_menu->setPageFooter($category->getFooter());
            }
	
	
}