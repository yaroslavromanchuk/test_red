<?php

class Router {

	static public function route() {
		$controller = 'Home';
		$action = 'index';
		$params =[];
		$curMenu = new Menu();
                $i = 0;
//var_dump($_GET);
        //     echo '<pre>';
 //echo print_r($_GET);
//echo '</pre>';   
                $get = $_GET;
                
       // echo $_SERVER['QUERY_STRING'];        
            //echo substr($_SERVER['QUERY_STRING'], 7);    
                    
	 $rout = $_SERVER['REQUEST_URI'];
        // if($_SERVER['REQUEST_URI'] != $_GET['route']){
         //  header("HTTP/1.1 301 Moved Permanently"); 
		//	header("Location: ".$_GET['route'],TRUE,301);
			//exit();
       // }
         //echo $rout;
	$route = explode('/', substr($_GET['route'],1));
        //$route = explode('/', substr($_SERVER['QUERY_STRING'],7));
      // echo '<pre>';
 //echo print_r($route);
//echo '</pre>';
        //redir 404
                if(array_search('categories', $route))
                {
                    header('HTTP/1.0 404 Not Found');
                   header("Location: /404/");

                }/*elseif(array_search('brands', $route))
                {
                    header('HTTP/1.0 404 Not Found');
                   header("Location: /404/");
                }*/elseif(array_search('colors', $route))
                {
                    header('HTTP/1.0 404 Not Found');
                   header("Location: /404/");
                }elseif(array_search('sizes', $route))
                {
                    header('HTTP/1.0 404 Not Found');
                   header("Location: /404/");
                }elseif(array_search('labels', $route))
                {
                    header('HTTP/1.0 404 Not Found');
                   header("Location: /404/");
                }elseif(array_search('sezons', $route))
                {
                    header('HTTP/1.0 404 Not Found');
                   header("Location: /404/");
                }elseif(array_search('skidka', $route))
                {
                    header('HTTP/1.0 404 Not Found');
                   header("Location: /404/");
                }elseif(array_search('price_min', $route))
                {
                    header('HTTP/1.0 404 Not Found');
                   header("Location: /404/");
                }elseif(array_search('price_max', $route))
                {
                   header('HTTP/1.0 404 Not Found');
                   header("Location: /404/");
                }
                //exit redir 404
                ///$filter = [];
                
                    //$filter = explode('-', substr($_GET['route'], (strrpos($_SERVER['PHP_SELF'], "/")+1)));
                    $filter = explode('-', substr($_SERVER['PHP_SELF'], (strrpos($_SERVER['PHP_SELF'], "/")+1)));
                    
		unset($_GET['route']);

		if(!count($route) || !$route[$i])
                    {
                  $route[$i] = 'homepage';//
                    
                    }
                   
                        		
		if($_SESSION['lang'] == 'uk' and $route[$i] != 'uk'){
			
                    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
			
                        $r = '/uk'.$rout;
                        header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: $r",TRUE,301);
			exit();
                    }
		}
                
		if(self::isLang($route[$i])) {
			
			$l = wsLanguage::findByCode($route[$i]);
			
			Registry::set('lang_id', $l->getId());
			Registry::set('lang', strtolower($l->getCode()));
			$_SESSION['lang'] = strtolower($l->getCode());
			
			setcookie('lang', strtolower($l->getCode()));
			//$_COOKIE['lang'] = strtolower($l->getCode());
			
			//$ctn = 1;
                        if($route[$i]== 'ru'){
                           $ur = substr($_SERVER['PHP_SELF'],3);
                      header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: $ur",TRUE,301);
			exit();
                            
                            unset($route[$i]);
                            
                        }else{
                            $i++;//i = 1
                            
                        }
			
                        
			$route = array_values($route);
                        
                       // d($route);
			if(!count($route) || !$route[$i]){ $route[$i] = 'homepage';}
                }else{
                        Registry::set('lang', 'ru');
                        Registry::set('lang_id', 1);
			
			$_SESSION['lang'] = 'ru';
			unset($_COOKIE['lang']);
                }
               
                if(self::first($route[$i])){
                $ur = mb_strtolower($_SERVER['PHP_SELF']);
                      header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: $ur",TRUE,301);
			exit();
                }elseif(self::first($route[$i+1])){
                $ur = mb_strtolower($_SERVER['PHP_SELF']);
                    header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: $ur",TRUE,301);
			exit();
                }
                if($route[$i] == 'brands' and self::first($route[$i+3])){
                    $ur = mb_strtolower($_SERVER['PHP_SELF']);
                      header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: $ur",TRUE,301);
			exit();
                }
                
                
                 if($route[$i] == 'category'){
                    //$cat = Shopcategories::find(Shopcategories, ['id'=>$route[$i+2]]);
                   $cat = wsActiveRecord::useStatic('Shopcategories')->findById((int)$route[$i+2]);
                   if($route[$i+1] == 'brand' or $route[$i+1] == 'brands'){
                         $br = wsActiveRecord::useStatic('Brand')->findById((int)$route[$i+2]);
                         if($br->id){
                $lang = '';
                if(Registry::get('lang') == 'uk'){$lang = '/uk';}
                $ur = $lang.'/all/articles/brands-'.$br->name;
                header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: $ur",TRUE,301);
			exit();
                         }
                    }elseif($cat->id){
                        $lang = '';
                if(Registry::get('lang') == 'uk'){$lang = '/uk';}
            $ur= $lang.'/'.$cat->controller.'/'.$cat->action.'/';
                        header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: $ur",TRUE,301);
			exit();
                    }else{
                         $lang = '';
                        if(Registry::get('lang') == 'uk'){$lang = '/uk';}
                     $ur = $lang.str_replace('category', 'all/articles', $_SERVER['PHP_SELF']);
                header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: $ur",TRUE,301);
			exit();
                    }
                        
                    }
		
		$old_controller = ucfirst($route[$i]);// perevod v verchniy registr - Controller
                
                if(count($get) > 1 and $old_controller === 'Category'){
                    
                    $r = $get['route'];
                    unset($get['route']);
                    $st = [];
                    foreach ($get as $key=>$value) {
                        switch ($key) {
                            case 'categories':
                                                $st[] = $key.'-'.$value;

                                break;
                            case 'brands':
                                                $st[] = $key.'-'.$value;
                                break;
                            case 'colors':

                                                $st[] = $key.'-'.$value;
                                break;
                             case 'sizes':

                                                $st[] = $key.'-'.$value;
                                break;
                             case 'labels':

                                                $st[] = $key.'-'.$value;
                                break;
                             case 'sezons':
                                $ar_s = [1=>'summer',2=>'autumn_spring',3=>'winter',4=>'all_season',5=>'demi_season'];
                                 $seson = explode(',', $value);
                                 
                                 foreach ($seson as $v) {
                                     $seson_new[] = $ar_s[$value];
                                 }
                                                $st[] = $key.'-'. implode(',', $seson_new);
                                break;
                             case 'skidka':

                                                $st[] = $key.'-'.$value;
                                break;
                            case 'price_min':

                                                $st[] = $key.'-'.$value;
                                break;
                            case 'price_max':

                                                $st[] = $key.'-'.$value;
                                break;

                            default:
                                break;
                        }
                    }
                    
                    if(count($st) > 0){
                     $q = $r.implode('-', $st);
                     
                        header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: $q",TRUE,301);
			exit();
                    }

                }
                
                //
                //
                //d($old_controller);
		//try to find it all in menu
		$cnt = $i;
		do {
			$found = 0;
			if(isset($route[$cnt]) && ($m = wsActiveRecord::useStatic('Menu')->findByUrlAndParentId($route[$cnt], ($curMenu->getId()?$curMenu->getId() : null )))) {
				if(!$curMenu->getId() || ($curMenu->getId() == $m->getParentId()) ) {
					$curMenu = $m;
					$found = 1;
					unset($route[$cnt]);
				}
			}
			$cnt++;
		} while ($found);

		//get controller and action
		if($curMenu->getId()) {
			$controller = ucfirst($curMenu->getController());
			$action = $curMenu->getAction();
		} else {
			$controller = ucfirst($route[$i]);
			$action = preg_replace ("/[^a-zA-Z0-9\s]/","",$route[$i+1]);
		}
                
		//if($controller == 'home'){  header("HTTP/1.1 301 Moved Permanently");header("Location: /",TRUE,301); exit();}

		//check if controller exists
		if($controller && !file_exists(APP_DIR . '/controllers/' . $controller . 'Controller.php')) {	
			header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: /404/",TRUE,301); 
			exit();
		}
                //if($action && !)

		
		if(!$curMenu->getId() && file_exists(APP_DIR . '/controllers/' . $old_controller . 'Controller.php')) {
			$controller = $old_controller;
			if(isset($route[$i+1]) && $route[$i+1]) {
				$action = preg_replace("/[^a-zA-Z0-9\s]/","",$route[$i+1]);
				unset($route[$i+1]);
			}
			unset($route[$i]);
		}

		
		if(!$action){ $action = 'index'; }
                    
                    //d($route, false);
                    
		$old_get = $_GET;
               // d($route, false);
		$new_get = [];
		$new_get['controller'] = $controller;
		$new_get['action'] = $action;
                

                if(count($filter) > 0){
                    
                    foreach ($filter as $k => $value) {
                        if ($k % 2 == 0){
                            $new_get[$value] = $filter[($k+1)];
                            
                        }
                    }
                    } 
                

		$route = array_values($route);
                //var_dump($route);
                
		//parse what have left from route
		for($j=$i; $j < count($route); $j++) {
			if(isset($new_get[$route[$j]]) && !is_array($new_get[$route[$j]])) {
				$tmp = $new_get[$route[$j]];
				$new_get[$route[$j]] = [];
				$new_get[$route[$j]][] = $tmp;
			}elseif(isset($new_get[$route[$j]]) && is_array($new_get[$route[$j]])){
                            $new_get[$route[$j]][] = $route[$j+1];
                        
                        }else{
                            $new_get[$route[$j]] = $route[$j+1];
                            
                        }
			$j++;
		}


		//parse old GET
		foreach($old_get as $key=>$value) {
			if(isset($new_get[$key]) && !is_array($new_get[$key])) {
				$tmp = $new_get[$key];
				$new_get[$key] = [];
				$new_get[$key][] = $tmp;
			}elseif(isset($new_get[$key]) && is_array($new_get[$key])){
				$new_get[$key][] = $value;
                        }else{
				$new_get[$key] = $value;
                        }
		}

                
		//use only values with keys	
		foreach($new_get as $key => $value) {
			if($key and $value){ $params[$key] = $value; }
		}
                
                


		Registry::set('cur_menu', $curMenu);	
		Registry::set('get', new Orm_Array($params));
		Registry::set('post', new Orm_Array($_POST));
		Registry::set('files', new Orm_Array($_FILES));
		Registry::set('cookies', new Orm_Array($_COOKIE));
		//Registry::set('route', $route);
		

	}
	
	
	static public function isLang($lang) {
	GLOBAL $langs;
	if (isset($langs) && isset($langs[strtolower($lang)])){ 
            return true;
            }
            return false;
	}
        
       static public function first($str) {
    if(preg_match('%^\p{Lu}%u', $str)) {
        return true;
    } else {
        return false;
    }
}
}