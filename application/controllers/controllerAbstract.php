<?php
abstract class controllerAbstract extends Controller
{

    public function init()
    {
        //error_reporting(E_ALL);
        //ini_set('display_errors', 1);

        $this->domain = strtolower(str_replace('www', '', $_SERVER['HTTP_HOST']));
        $this->ws = $this->website = Registry::get('website');


        if (strtotime($this->ws->getCustomer()->machine_last_visit->getCtime()) < (time() - (24 * 60 * 60))) {
            $this->ws->getCustomer()->logout();
            $this->ws->updateHashes();
            $str = ((isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false)
                    ? $_SERVER['HTTP_REFERER'] :  $_SERVER['REQUEST_URI']);// заменить $_SERVER['REQUEST_URI'] на '/'
            if (isset($_GET['redirect']))
                $str = $_GET['redirect'];
            $this->_redirect($str);
        } elseif ($_SERVER['REMOTE_ADDR'] != $this->ws->getCustomer()->machine_last_visit->getIpCreated()) {
            $this->ws->getCustomer()->logout();
            $this->ws->updateHashes();
            $str = ((isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false)
                    ? $_SERVER['HTTP_REFERER'] :  $_SERVER['REQUEST_URI']);// заменить $_SERVER['REQUEST_URI'] на '/'
            if (isset($_GET['redirect']))
                $str = $_GET['redirect'];
            $this->_redirect($str);
        }

        $this->message = Registry::get('message');
        $this->view->trans = $this->trans = new Translator();

        $this->view->ws = $this->ws;
        $this->view->message = $this->message;
        $this->view->get = $this->get;
        $this->view->post = $this->post;
		
        Registry::set('View', $this->view);
		
        $this->_global_template = 'site.tpl.php';


        if (!isset($_SESSION['basket']))
            $_SESSION['basket'] = array();
        $this->basket = $this->view->basket = $_SESSION['basket'];

        if (!isset($_SESSION['basket_contacts']))
            $_SESSION['basket_contacts'] = array();
        $this->basket_contacts = $this->view->basket_contacts = $_SESSION['basket_contacts'];

        if (!isset($_SESSION['basket_articles']))
            $_SESSION['basket_articles'] = array();
        $this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'];

        if (!isset($_SESSION['basket_options']))
            $_SESSION['basket_options'] = array();
        $this->basket_options = $this->view->basket_options = $_SESSION['basket_options'];
		
		$this->view->text_trans  = explode(',', $this->trans->get('Товаров на странице,Быстрый просмотр,Цвета,Размеры'));
		  //push
	//	 $this->view->puch = $this->view->render('/cache/puch.tpl.php');
		 
		$sql = '
		SELECT  brand, count(ws_articles.id) as cnt, red_brands.logo, brand_id
		FROM ws_articles_sizes
		inner JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
		inner JOIN red_brands ON red_brands.id = ws_articles.brand_id
		WHERE ws_articles_sizes.count > 0 AND ws_articles.active = "y" AND brand_id > 0
		GROUP BY brand_id
		ORDER BY cnt DESC
		LIMIT 20
		';
		$this->view->cached_brands = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
	
        //menus_caching
        $cache = Registry::get('cache');
		$cache->setEnabled(true);
		//socsety
	/*	$cache_name = 'socsety_'.$_SESSION['lang'];
        $socsety = @$cache->load($cache_name);
       if ( !$socsety ) { //если сломалось пищещь сюда TRUE 
            $socsety = $this->view->render('/cache/soc.tpl.php');
            @$cache->save($socsety, $cache_name, array($cache_name), false);
        }
        $this->view->socsety = $socsety;
		*/
		if(Registry::get('device') == 'computer' or (@$_COOKIE['mobil'] and $_COOKIE['mobil'] == 10)){ 
		$this->view->cached_top_menu = $this->view->render('/cache/top_menu.tpl.php');
		//new_year
		/*$cache_name = 'new_year_fon';
        $new_year = $cache->load($cache_name);
       if (!$new_year) { //если сломалось пищещь сюда TRUE 
            $new_year = $this->view->render('/pages/fon.new.year.php');
            $cache->save($new_year, $cache_name, array($cache_name), false);
        }
        $this->view->cached_new_year = $new_year;
		*/
		//top menu 2
        $cache_name = 'topcategories_3_'.$_SESSION['lang'];
    //  $topcategories = $cache->load($cache_name);// меню навигации
        if (!$topcategories) { //если сломалось пищещь сюда TRUE // верхнее меню
            $topcategories = $this->view->render('/cache/topcategories.tpl.php');
            $cache->save($topcategories, $cache_name, array($cache_name), false);
        }
		//$topcategories = $this->view->render('/cache/topcategories.tpl.php');
        $this->view->cached_topcategories = $topcategories;
		 //bottom menu
        $cache_name = 'footer_'.$_SESSION['lang'];
        $bottom_menu = $cache->load($cache_name);
        if (!$botton_menu) { //если сломалось пищещь сюда TRUE
            $bottom_menu = $this->view->render('/cache/footer.tpl.php');
            $cache->save($bottom_menu, $cache_name, array($cache_name), false);
        }
        $this->view->cached_bottom_menu = $bottom_menu;
		}else{
		 $this->view->cached_mobi_menu = $this->view->render('/cache/mobi_menu.tpl.php');
		 		//mobi_futer
		$cache_name = 'mobi_futer_'.$_SESSION['lang'];
        $mobi_futer = $cache->load($cache_name);
        if (!$mobi_futer) { //если сломалось пищещь сюда TRUE
            $mobi_futer = $this->view->render('/cache/mobi_futer.tpl.php');
            $cache->save($mobi_futer, $cache_name, array($cache_name), false);
        }
        $this->view->cached_mobi_futer = $mobi_futer;
		}
		
		
			   if($this->ws->getCustomer()->getId()){ 
		$sql = "SELECT  `ws_articles`. * 
FROM  `ws_articles` 
INNER JOIN  `ws_articles_history` ON  `ws_articles`.`id` =  `ws_articles_history`.`article_id` 
WHERE  `ws_articles_history`.`customer_id` =".$this->ws->getCustomer()->getId()."
AND ws_articles.`stock`  not like '0'
GROUP BY  `ws_articles`.`id` 
ORDER BY  `ws_articles_history`.`id` DESC 
LIMIT 6";
			$this->view->history = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
		}
		
        //define('BTW', 0);
        //define('MAX_COUNT_PER_ARTICLE', 100);
        //define('DELIVERY_COST', Config::findByCode('delivery_cost')->getValue());
        //var_dump(DELIVERY_COST);die;
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
        @header('HTTP/1.0 404 Not Found');
        $page = wsActiveRecord::useStatic('Menu')->findByUrl('404');

        if ($page) {
            $this->view->cur_menu = $this->cur_menu = $page;
            echo $this->render('pages/static.tpl.php');
        } else
            echo $this->render('pages/404.tpl.php');
    }

    protected function _postAction($content)
    {
        $this->view->setContent($content);
        return $this->view->render($this->_global_template);
    }
	
		

}

class Translator
{
    public function get($msg)
    {
	if($msg){
        $value = wsActiveRecord::useStatic('Dictionary')->findByName(trim($msg))->at(0);
        if (!$value) {
            $value = new Dictionary();
            $value->setName($msg);
            $value->setTranslation($msg);
			$value->setTranslationUk($this->translateuk($msg, 'ru', 'uk'));
            $value->save();
        }
		if($_SESSION['lang'] == 'uk')
		{
			return $value->getTranslationUk();
		}else{
			return $value->getTranslation();
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
			$value->setTranslationUk($this->translateuk($msg, 'ru', 'uk'));
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
  $response = curl_exec($handle);  
  $responseDecoded = json_decode($response, true);  
  $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE); 
  curl_close($handle);  
  if($responseCode != 200) {  
        echo 'Fetching translation failed! Server response code:' . $responseCode . '<br>';  
        echo 'Error description: ' . $responseDecoded['error']['errors'][0]['message'];  
    } else {  
       // echo 'Source: ' . $text . '<br>';  
        $trans = $responseDecoded['data']['translations'][0]['translatedText'];  
		return $trans;
    }
}
public function translateru($str, $lang_from = 'uk', $lang_to='ru') {
 $apiKey = 'AIzaSyC5MeHPcuEKqiWH7Oqlxvp8GhY7TTYwUf8';    
  $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($str) . '&source='.$lang_from.'&target='.$lang_to;  
  $handle = curl_init($url);  
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);  
  $response = curl_exec($handle);  
  $responseDecoded = json_decode($response, true);  
  $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE); 
  curl_close($handle);  
  if($responseCode != 200) {  
        echo 'Fetching translation failed! Server response code:' . $responseCode . '<br>';  
        echo 'Error description: ' . $responseDecoded['error']['errors'][0]['message'];  
    } else {  
       // echo 'Source: ' . $text . '<br>';  
        $trans = $responseDecoded['data']['translations'][0]['translatedText'];  
		return $trans;
    }
}
	
	
	
	
}