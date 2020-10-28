<?php

class FilterController extends controllerAbstract {

    public function indexAction()
            {
        $search_word = Orm_Statement::escape(iconv('windows-1251','UTF-8//IGNORE', $this->get->s));
        if (LOCAL){
            $search_word = 'куртка';
        }
       
        $this->getFilter('', '', $search_word);
    }

    /**
     * 
     * @param type $category - Обьект - категория/ Обьезательный параметр
     * @param type $url - если нужно передать урл, иначе автоматически формируется ''
     * @param type $search - в случаи поиска передаем текст иначе пустота ''
     * @return отфильтрованый товар
     */
    public  function getFilter($category, $url = '', $search = '')
            {
        
        $page_onpage_order_by = self::page_order_by();
        $canonical = '';
       if($url == ''){ $canonical .= $category->getPath(); $this->view->g_url = $category->getPath(); }//
       if($search != ''){ $this->view->search_word = $search; }
        $param = [];
       if($this->get->categories) {
           $param['categories'] = explode(',', $this->get->categories);
           
       }
//if($this->ws->getCustomer()->id == 8005){l($this->get);}
       if(count($_GET) > 1){
          // print_r($_GET);
           $this->cur_menu->noindex = 1;
       }
       
       if($this->get->brands) {
           $canonical.= 'brands-'.$this->get->brands.'/';
          // print_r($this->get);
           foreach (explode(',', $this->get->brands) as $v){
            if ($v) {
              $v =   str_replace("'", "\'", $v);
             //  echo $v;
               $param['brands'][] = (int)Brand::findByQueryFirstArray("SELECT id FROM `red_brands` WHERE  `name` LIKE  '".$v."' ")['id'];
            }
            }
       }
       $this->view->canonical = $canonical;
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
       
       $meta_param = [
           'search'=>$search,
           'filter' => $param,
           'category' => $category,
           'order_by' => $page_onpage_order_by['order_by'],
           'page' => $page_onpage_order_by['page'],
           'Onpage' => $page_onpage_order_by['onPage']
       ];
       
       $meta = Meta::getMeta($meta_param);
       
      //   $search_result = Filter::getArticlesFilter($search, $param, $category, $page_onpage_order_by['order_by'], $page_onpage_order_by['page'], $page_onpage_order_by['onPage']);
          $search_result = Filter::getArticlesFilter($meta_param);
         
         if($meta) {
                   if($meta['noindex']) { $this->cur_menu->noindex = 1; } 
                   if($meta['h1']) {  $this->cur_menu->setName($meta['h1']);}
                   if($meta['title']) { $this->cur_menu->setPageTitle($meta['title']);}
                   if($meta['descriptions']) { $this->cur_menu->setMetatagDescription($meta['descriptions']);}
                   
                   self::futterText($meta['footer'], $category);
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
                if(isset($param['block'])){
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
             //   $this->cur_menu->article = 1; 
                    $this->cur_menu->setName(($category->getH1()?$category->getH1():$category->getName()).' '.$this->trans->get('в интернет магазине RED'));
                    $this->cur_menu->setPageTitle(($category->getTitle()?$category->getTitle():$category->getName()).' '.$this->trans->get('dop_title'));
                    $this->cur_menu->setMetatagDescription($category->getDescription());
                    $this->cur_menu->setPageFooter($category->getFooter());
            }
	
	
}