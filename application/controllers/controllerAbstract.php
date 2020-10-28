<?php
abstract class controllerAbstract extends Controller
{

    public function init()
    {
        //error_reporting(E_ALL);
        //ini_set('display_errors', 1);

        $this->domain = strtolower(str_replace('www', '', $_SERVER['HTTP_HOST']));
        $this->ws = $this->website = Registry::get('website');
        $this->view->user = $this->ws->getCustomer();

if(!$this->ws->getCustomer()->isAdmin()){
        if (strtotime($this->ws->getCustomer()->machine_last_visit->getCtime()) < (time() - 86400)){
            $this->ws->getCustomer()->logout();
            $this->ws->updateHashes();
$str = ((isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false) ? $_SERVER['HTTP_REFERER'] :  $_SERVER['REQUEST_URI']);// заменить $_SERVER['REQUEST_URI'] на '/'
    
    if (isset($_GET['redirect'])){ 
        $str = $_GET['redirect'];
    
    }else{

            $this->_redirect($str);
    }
        } elseif ($_SERVER['REMOTE_ADDR'] != $this->ws->getCustomer()->machine_last_visit->getIpCreated()) {
            $this->ws->getCustomer()->logout();
            $this->ws->updateHashes();
            $str = ((isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false)
                    ? $_SERVER['HTTP_REFERER'] :  $_SERVER['REQUEST_URI']);// заменить $_SERVER['REQUEST_URI'] на '/'
            
            if (isset($_GET['redirect'])){
            $str = $_GET['redirect'];
            }
            $this->_redirect($str);
        }
    }

        $this->message = Registry::get('message');
        $this->view->trans = $this->trans = new Translator();

        $this->view->ws = $this->ws;
        $this->view->message = $this->message;
        $this->view->get = $this->get;
        $this->view->post = $this->post;
        
        $this->view->css = []; // массив css файлов для корректного отображения страницы
        $this->view->scripts = []; // массив js файлов для работы страницы
		
        Registry::set('View', $this->view);
		
        $this->_global_template = 'site.tpl.php';


        if (!isset($_SESSION['basket'])){
            $_SESSION['basket'] = [];
            }
          //  if($this->ws->getCustomer()->id == 8005){
             //   $this->ws->getCustomer()->updateCartUserBacket();
          //  }
        $this->view->basket = $this->basket = $_SESSION['basket'];

        if (!isset($_SESSION['basket_contacts'])){
            $_SESSION['basket_contacts'] = [];
            }
        $this->view->basket_contacts = $this->basket_contacts = $_SESSION['basket_contacts'];

        if (!isset($_SESSION['basket_articles'])){$_SESSION['basket_articles'] = [];}
        
        $this->view->basket_articles = $this->basket_articles = $_SESSION['basket_articles'];

	$this->view->text_trans  = explode(',', $this->trans->get('Товаров на странице,Быстрый просмотр,Цвета,Размеры'));
		  //push
	
//	 $this->view->puch = $this->view->render('/cache/puch.tpl.php');
		 
		
        //menus_caching
        $cache = Registry::get('cache');
	//$cache->setEnabled(true);
        $lang = Registry::get('lang');
        $this->view->lang = $lang;
		
	if(Registry::get('device') == 'computer' or (isset($_COOKIE['mobil']) and $_COOKIE['mobil'] == 10)){ 
            $this->view->cached_top_menu = $this->view->render('/cache/top_menu.tpl.php');
		//top menu 2
            $cache_name = 'topcategories_3_'.$lang;
            $topcategories = $cache->load($cache_name);// меню навигации
        if (!$topcategories) { //если сломалось пищещь сюда TRUE // верхнее меню
            $topcategories =  $this->view->render('/cache/topcategories.tpl.php');
            $cache->save($topcategories, $cache_name, [$cache_name], false);
        }
        $this->view->cached_topcategories = $topcategories;
	
        //bottom menu
        $cache_name = 'footer_'.$lang;
        $bottom_menu = $cache->load($cache_name);
        if (!$bottom_menu) { //если сломалось пищещь сюда TRUE
            $bottom_menu = $this->view->render('/cache/footer.tpl.php');
            $cache->save($bottom_menu, $cache_name, array($cache_name), false);
        }
        $this->view->cached_bottom_menu = $bottom_menu;
	}else{
            $this->view->cached_mobi_menu = $this->view->render('/cache/mobi_menu.tpl.php');
	//mobi_futer
	$cache_name = 'mobi_futer_'.$lang;
        $mobi_futer = $cache->load($cache_name);
        if (!$mobi_futer){ //если сломалось пищещь сюда TRUE
            $mobi_futer = $this->view->render('/cache/mobi_futer.tpl.php');
            $cache->save($mobi_futer, $cache_name, array($cache_name), false);
        }
        $this->view->cached_bottom_menu = $mobi_futer;
	}
        //define('BTW', 0);
        return;
    }

    public function indexAction()
    {
        // Render default site template
        echo $this->render();
        return;
    }

    static public function isValidEmail($email)
    {
        $email_regular_expression = "^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}\$";
        $preg = (function_exists("preg_match") ? "/" . str_replace("/", "\\/", $email_regular_expression) . "/" : "");

        if ($preg) {
            return (preg_match($preg, $email));
        } else {
            return 0;
        }
    }

    public function show404Action($method, $params = '')
    {
        $message = "HTTP/1.0 404 Page Not Found ($method)";

        wsLog::add($message, 'INFO');

        // Send 404 header
        header("HTTP/1.0 404 Not Found");
         
      //  header("Location: /404/",TRUE,301);  
			//exit();
        $page = wsActiveRecord::useStatic('Menu')->findByUrl('404');

        if ($page) {
            $this->view->cur_menu = $this->cur_menu = $page;
            echo $this->render('pages/static.tpl.php');
        } else{ 
            echo $this->render('pages/404.tpl.php');
            
        }
    }

    protected function _postAction($content)
    {
        $this->view->setContent($content);
        return $this->view->render($this->_global_template);
    }
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
                   if(isset($meta['noindex'])) { $this->cur_menu->noindex = 1; } 
                   if(isset($meta['h1'])) {  $this->cur_menu->setName($meta['h1']);}
                   if(isset($meta['title'])) { $this->cur_menu->setPageTitle($meta['title']);}
                   if(isset($meta['descriptions'])) { $this->cur_menu->setMetatagDescription($meta['descriptions']);}
                   
                   if(isset($meta['footer'])) { self::futterText($meta['footer'], $category); }
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

                if(!empty($_COOKIE['items_on_page'])){
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

class Translator
{
   static public function get($msg)
    {
	if($msg){
        $value = wsActiveRecord::useStatic('Dictionary')->findByName(trim($msg))->at(0);
        if ($value) {
           if(Registry::get('lang') == 'uk')
		{
			return $value->getTranslationUk();
		}else{
			return $value->getTranslation();
		}
        }else{
        $value = new Dictionary();
            $value->setName($msg);
            $value->setTranslation($msg);
            $value->setTranslationUk($this->translate($msg, 'ru', 'uk'));
            $value->save();
            if(Registry::get('lang') == 'uk')
		{
			return $value->getTranslationUk();
		}else{
			return $value->getTranslation();
		}
        }
		
		}
		return false;
        
    }
	
	public function getTest($msg)
    {
	$trans = $_SESSION['translit'];
$text = '';
for($i=0;$i<count($trans);$i++){
if($trans[$i]['name'] == $msg){
$text = $trans[$i]['translation'];
 }
}
if($text == ''){
$value = new Dictionary();
            $value->setName($msg);
            $value->setTranslation($msg);
			$value->setTranslationUk($this->translate($msg, 'ru', 'uk'));
            $value->save();  
    }else{
	return $text;
	}    
    }

public function translateuk($str, $lang_from = 'ru', $lang_to='uk') {
 $apiKey = 'AIzaSyC5MeHPcuEKqiWH7Oqlxvp8GhY7TTYwUf8';    
  $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($str) . '&source='.$lang_from.'&target='.$lang_to;  
  $handle = curl_init($url);  
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true); 
		if (isset($_SERVER['HTTP_REFERER'])) {
            curl_setopt($handle, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
        }
     curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.71 Safari/534.24");    
  $response = curl_exec($handle);  
  $responseDecoded = json_decode($response, true);  
  $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE); 
  curl_close($handle);  
  if($responseCode == 200) {  
		return $responseDecoded['data']['translations'][0]['translatedText'];
 
    } else {  
       // echo 'Source: ' . $text . '<br>';  
       return false;
	    //  echo 'Fetching translation failed! Server response code:' . $responseCode . '<br>';  
        //echo 'Error description: ' . $responseDecoded['error']['errors'][0]['message'];  
    }
}
public function translateru($str, $lang_from = 'uk', $lang_to='ru') {
 $apiKey = 'AIzaSyC5MeHPcuEKqiWH7Oqlxvp8GhY7TTYwUf8';    
  $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($str) . '&source='.$lang_from.'&target='.$lang_to;  
  $handle = curl_init($url);  
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);  
  if (isset($_SERVER['HTTP_REFERER'])) {
            curl_setopt($handle, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
        }
     curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.71 Safari/534.24"); 
  $response = curl_exec($handle);  
  $responseDecoded = json_decode($response, true);  
  $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE); 
  curl_close($handle);  
  if($responseCode == 200) {  
		return $responseDecoded['data']['translations'][0]['translatedText'];
    } else {  
       // echo 'Source: ' . $text . '<br>';  
       return false;
	    //  echo 'Fetching translation failed! Server response code:' . $responseCode . '<br>';  
        //echo 'Error description: ' . $responseDecoded['error']['errors'][0]['message'];  
    }
}
public function translate($str, $lang_from = 'uk', $lang_to='ru'){
    // require_once('yandex/Translation.php');
   // $tr = new Translation();
   // $res = $tr->translate($lang_from, $lang_to, $str);            
        if(/*$res['code'] == 200*/false){ 
            return $res['text'][0];
        }else{
            $apiKey = 'AIzaSyC5MeHPcuEKqiWH7Oqlxvp8GhY7TTYwUf8';    
  $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($str) . '&source='.$lang_from.'&target='.$lang_to;  
  $handle = curl_init($url);  
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true); 
		if (isset($_SERVER['HTTP_REFERER'])) {
            curl_setopt($handle, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
        }
     curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.71 Safari/534.24");    
  $response = curl_exec($handle);  
  $responseDecoded = json_decode($response, true);  
  $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE); 
  curl_close($handle);  
  if($responseCode == 200) {  
		return $responseDecoded['data']['translations'][0]['translatedText'];
    } else {   
       return false;
	    //  echo 'Fetching translation failed! Server response code:' . $responseCode . '<br>';  
        //echo 'Error description: ' . $responseDecoded['error']['errors'][0]['message'];  
    }
        }
    return false;
}
}