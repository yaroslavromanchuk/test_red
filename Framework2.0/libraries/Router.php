<?php

class Router {

	static public function route() {
	    var_dump(111);die;

           if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    // не ajax запрос
  if(strpos($_SERVER['PHP_SELF'], 'ajaxsearch')) {
     header("HTTP/1.1 301 Moved Permanently");
    header("Location: /",TRUE,301);
    exit();
  }
    $redirect = wsActiveRecord::useStatic('Redirect')->findFirst([' url LIKE  "'.(string)$_SERVER['PHP_SELF'].'" ']);

                if(!empty($redirect->to_url)){
                     header("HTTP/1.1 301 Moved Permanently");
			header("Location: $redirect->to_url",TRUE,301);
			exit();
                }

       }

                  $i = 0;
                $get = $_GET;

	$rout = $_SERVER['REQUEST_URI'];
	$route = isset($get['route']) ? explode('/', substr($get['route'],1)) : [];
                //redirect no "/"
       // echo $_SERVER['PHP_SELF'];

      //  l($_SERVER);
                if($_SERVER['PHP_SELF'] and substr($_SERVER['PHP_SELF'], -1) != '/' and  $route[$i] != 'admin'){
                   // $r = $rout.'/';
                  //  $r =  str_replace($_SERVER['PHP_SELF'], $_SERVER['PHP_SELF']."/", $_SERVER['REQUEST_URI']);
                    $r = $_SERVER['PHP_SELF'].'/';
                     header("HTTP/1.1 301 Moved Permanently");
			header("Location: $r",TRUE,301);
			exit();
               }
                //exit redirect no "/"

		$controller = 'Home';
		$action = 'index';
		$params =[];
		$curMenu = new Menu();

                if(array_search('category', $route)){
                  header('HTTP/1.0 404 Not Found');
                   header("Location: /404/");
                }elseif(array_search('categories', $route))
                {
                    header('HTTP/1.0 404 Not Found');
                   header("Location: /404/");

                }elseif(array_search('colors', $route))
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

                if(strpos($_SERVER['PHP_SELF'], 'category')){
             header("HTTP/1.1 301 Moved Permanently");
			header("Location: /new/all/",TRUE,301);
			exit();
        }

		unset($_GET['route']);

		if(!count($route) || !$route[$i])
                    {
                  $route[$i] = 'homepage';//

                    }

		if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'uk' && $route[$i] != 'uk'){

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

                        if(empty($_SESSION['lang'])){
                            $_SESSION['lang'] = strtolower($l->getCode());
                        }elseif(isset($_SESSION['lang']) && $_SESSION['lang'] != $route[$i]){
                             $ur = substr($_SERVER['PHP_SELF'],3);
                                    header("HTTP/1.1 301 Moved Permanently");
                                    header("Location: $ur",TRUE,301);
                                exit();
                            unset($route[$i]);
                        }
			//$_SESSION['lang'] = strtolower($l->getCode());
			//setcookie('lang', strtolower($l->getCode()));
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
                }elseif(isset($route[$i+1]) && self::first($route[$i+1])){
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

                //echo $route[$i];
                 if($route[$i] == 'category'){
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
                            //редирект в случаи если в урл присутствуют лишние символы, делалось для категорий 18.11.2019
                        if($route[$i+2]){
                            $cat = wsActiveRecord::useStatic('Shopcategories')->findFirst(['controller'=>$controller, 'action'=>$action]);
                            if($cat->id){
                            $arr = explode('-', $route[$i+2]);
                             if(!in_array($arr[0], ['brands','sezons','sizes','colors','labels','skidka','price_min','price_max'])){
                                header("HTTP/1.1 301 Moved Permanently");
                                // header("HTTP/1.0 404 Not Found");
                                header("Location: /404/",TRUE,301);
			exit();
                            }

                             }
                        }
                        //exit

		}
		//check if controller exists
		if($controller && !file_exists(APP_DIR . '/controllers/' . $controller . 'Controller.php')) {
			header("HTTP/1.1 301 Moved Permanently");
                   // header("HTTP/1.0 404 Not Found");
                    header("Location: /404/",TRUE,301);
			exit();
		}

		if(!$curMenu->getId() && file_exists(APP_DIR . '/controllers/' . $old_controller . 'Controller.php')) {
			$controller = $old_controller;
			if(isset($route[$i+1]) && $route[$i+1]) {
				$action = preg_replace("/[^a-zA-Z0-9\s]/","",$route[$i+1]);
				unset($route[$i+1]);
			}
			unset($route[$i]);
		}

                if(!$action){
                    $action = 'index';
                }
		$old_get = $_GET;
		$new_get = [];
		$new_get['controller'] = $controller;
		$new_get['action'] = $action;

                if($controller != 'Articles' && $action != 'article'){
                    $ff = [];
                  foreach (explode('/', $_SERVER['PHP_SELF']) as $k => $f){
                      if($f){
                          $ff[] = $f;

                      }
                  }


                   $filter = isset($ff[$i+2]) ? explode('-', $ff[$i+2]) : [];
                  // print_r($filter);
                   // $filter = explode('-', $route[$i+2]);
                if(count($filter) > 0){
                    foreach ($filter as $k => $value) {
                        if ($k % 2 == 0 && !empty($filter[($k+1)])){
                            $new_get[$value] = $filter[($k+1)];

                        }
                    }
                    }
       }

		$route = array_values($route);

		//parse what have left from route
		for($j=$i; $j < count($route); $j++) {
			if(isset($new_get[$route[$j]]) && !is_array($new_get[$route[$j]])) {
				$tmp = $new_get[$route[$j]];
				$new_get[$route[$j]] = [];
				$new_get[$route[$j]][] = $tmp;
			}elseif(isset($new_get[$route[$j]]) && is_array($new_get[$route[$j]])){
                            $new_get[$route[$j]][] = $route[$j+1];

                        }else if(isset($route[$j+1])){
                            $new_get[$route[$j]] = $route[$j+1];

                        }
			$j++;
		}

//if($controller != 'Articles' && $action != 'article'){
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
       // }


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