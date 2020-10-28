<?php
/**
 * Description of CategoryController
 * @author PHP
 */
class CategoryController extends controllerAbstract
{
    //put your code here   
    	public function categoryAction()
        {
             $this->view->critical_css = [
           '/css/catalog/catalog.css', 
        ];
            $this->view->css = [
            '/css/cloudzoom/cloudzoom.css',
            '/css/jquery.lightbox-0.5.css',
                        '/js/select2/css/select2.min.css',
        ];
            $this->view->scripts = [
                 '/js/filter.js',
            '/js/jquery.cycle.all.js',
                '/lib/select2/js/select2.min.js'
                ];
            $search_word = $this->get->s;
		if (!$search_word) { $search_word = false;
		}else{
		if (stristr($search_word, '.com') === FALSE) {
   SearchLog::setToLog(mysql_real_escape_string(htmlentities($search_word)), (int)$this->ws->getCustomer()->getId());
}else{
$search_word = false;
}
		}
$this->view->category = $category = new Shopcategories($this->get->id);
if($category->getId()){
$this->view->g_url = $url = $category->getPath();  
}elseif($this->cur_menu->url){
    $this->view->g_url = $url = '/'.$this->cur_menu->url.'/'; 
}else{
$this->view->g_url = $url = '/category/';  
}
$this->view->finder_category = $this->get->id;
FilterController::getFilter($category, $url, $search_word);  
	}
        
        
        
        
        function getfilter($search_word = '', $category = '', $brands = '', $colors = '', $sizes = '', $labels = '', $sezons = '', $skidka = '', $categories = '', $price = array())
	{

		if($price['price_min'] != NULL ) {$addtional['price']['min'] =  $price['price_min'];}
		if($price['price_max'] != NULL ) {$addtional['price']['max'] =  $price['price_max'];}

		 //categories
		$addtional['categories'] = $categories?$categories:$this->post->categories;
		//brands
                 $addtional['brands'] = $brands?$brands:$this->post->brands;

		//colors
                $addtional['colors'] = $colors?$colors:$this->post->colors;

		//sizes
                $addtional['sizes'] = $sizes?$sizes:$this->post->sizes;

		//labels
                $addtional['labels'] =$labels?$labels:$this->post->labels;

		//sezons
        foreach (explode(',', $sezons?$sezons:$this->post->sezons) as $v){
            if ($v) {
               $addtional['sezons'][] = Shoparticlessezon::findByQueryFirstArray("SELECT * FROM  `ws_articles_sezon` WHERE  `translate` LIKE  '".$v."' ")['id'];
               // $addtional['sezons'][] = "'".$v."'";
            }
            }
		//skidka
                $addtional['skidka'] = $skidka?$skidka:$this->post->skidka;

		$prod_on_page = $_COOKIE['items_on_page'];//
                
                if (!$prod_on_page){ $prod_on_page = Config::findByCode('products_per_page')->getValue();}//

		$this->view->per_page = $onPage = $prod_on_page;//
                
		$page = $this->get->page?(int)$this->get->page:0;//

		//d($page, false);
		$search_result = Filter::getArticlesFilter($search_word, $addtional, $category, $this->get->order_by, $page, $onPage);//

		$this->view->filters = $search_result['parametr'];
               if($search_result['meta']) {
                   $this->view->meta = $search_result['meta'];
                   
               }

		$this->view->cur_page = $page;//

		$this->view->result_count = $search_result['count'];

		$this->view->total_pages = $search_result['pages'];

		$this->view->search_word = $search_word;
		$this->view->articles = $search_result['articles'];
		$this->view->result = $this->view->render('finder/list.tpl.php');
                $this->view->order_by = $this->get->order_by; 

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
            if ($v){ $addtional['labels'][] = Orm_Statement::escape($v); }
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
        
        
}
