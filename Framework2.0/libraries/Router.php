<?php

class Router {

	static public function route() {
		$controller = 'Home';
		$action = 'index';
		$params = array();
		$curMenu = new Menu();
//2517
		/*
	if (isset($_GET['adm'])) { 
		echo '<pre>';
		print_r($_GET);
		echo '</pre>';
	}
	if (isset($_GET['phpinfo'])) { 
		phpinfo();
	}
	if (isset($_GET['open'])) { 
		ini_set('open_basedir', NULL);
		echo '<pre>';
		print_r(ini_get('open_basedir'));
		echo '</pre>';
	}
	*/
	

	//$route = explode('/', @$_GET['route']); 
		
	$route = explode('/', substr(@$_GET['route'],1));
		unset($_GET['route']);
//2517
/*
		echo '<pre>';
		print_r($route);
		echo '</pre>';
*/
		if(!count($route) || !$route[0])
			$route[0] = 'homepage';

		if(self::isLang($route[0])) {
			$l = wsLanguage::findByCode($route[0]);
			Registry::set('lang_id', $l->getId());
			$_SESSION['lang'] = strtolower($l->getCode());
			unset($route[0]);
			$route = array_values($route);
			if(!count($route) || !$route[0])
				$route[0] = 'homepage';
		}
		$old_controller = ucfirst($route[0]);

		//try to find it all in menu
		$cnt = 0;
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
//2517
/*
		echo '<pre>';
		print_r($route);
		echo '</pre>';
*/
		//get controller and action
		if($curMenu->getId()) {
			$controller = ucfirst($curMenu->getController());
			$action = $curMenu->getAction();
		} else {
			$controller = ucfirst($route[0]);
			$action = @$route[1];
		}
//2517
/*
		echo '<pre>';
		print_r($controller);
		echo '</pre>';
		echo '<pre>';
		print_r($action);
		echo '</pre>';
		echo '<pre>';
		print_r($route);
		echo '</pre>';
*/

		//check if controller exists
		if($controller && !file_exists(APP_DIR . '/controllers/' . $controller . 'Controller.php')) {				
			//if not found
			$controller = 'Home';
			$action = '404';
		}
//2517
/*
		echo '<pre>MENU: ';
		print_r($curMenu->getId());
		echo '</pre>';
		if (!$curMenu) {
			echo '!$curMenu';
		}
		if (file_exists(APP_DIR . '/controllers/' . $old_controller . 'Controller.php')) {
			echo APP_DIR . '/controllers/' . $old_controller . 'Controller.php';
		}
*/
//\2517	
		if(!$curMenu->getId() && file_exists(APP_DIR . '/controllers/' . $old_controller . 'Controller.php')) {
			$controller = $old_controller;
			if(isset($route[1]) && $route[1]) {
				$action = $route[1];
				unset($route[1]);
			}
			unset($route[0]);
		}
//2517
/*
		echo '<pre>';
		print_r($route);
		echo '</pre>';
*/

		if(!$action)
			$action = 'index';
		$old_get = $_GET;
		$new_get = array();
		$new_get['controller'] = $controller;
		$new_get['action'] = $action;

//2517
/*
		echo '<pre>';
		print_r($new_get);
		echo '</pre>';
*/
		$route = array_values($route);
		//parse what have left from route
		for($i=0; $i < count($route); $i++) {
			if(isset($new_get[$route[$i]]) && !is_array($new_get[$route[$i]])) {
				$tmp = $new_get[$route[$i]];
				$new_get[$route[$i]] = array();
				$new_get[$route[$i]][] = $tmp;
			}
			
			if(isset($new_get[$route[$i]]) && is_array($new_get[$route[$i]]))
				$new_get[$route[$i]][] = @$route[$i+1];
			else
				$new_get[$route[$i]] = @$route[$i+1];
			$i++;
		}

//2517
/*
		echo '<pre>';
		print_r($new_get);
		echo '</pre>';
*/
		//parse old GET
		foreach($old_get as $key=>$value) {
			if(isset($new_get[$key]) && !is_array($new_get[$key])) {
				$tmp = $new_get[$key];
				$new_get[$key] = array();
				$new_get[$key][] = $tmp;
			}
			if(isset($new_get[$key]) && is_array($new_get[$key]))
				$new_get[$key][] = $value;
			else
				$new_get[$key] = $value;
		}

//2517
/*
		echo '<pre>';
		print_r($new_get);
		echo '</pre>';
*/
		//use only values with keys	
		foreach($new_get as $key => $value) {
			if($key)
				$params[$key] = $value;
		}

//2517
/*
		echo '<pre>params: ';
		print_r($params);
		echo '</pre>';
*/
//2517
/*
		echo '<pre>ORM_params: ';
		print_r(new Orm_Array($params));
		echo '</pre>';
*/
//2517
/*
		echo '<pre>';
		print_r($_GET);
		echo '</pre>';
*/
		//$params = array_merge($params, $_POST);
		//$_GET = $params; ?? ломает редактирование страниц
		
		Registry::set('cur_menu', $curMenu);	
		Registry::set('get', new Orm_Array($params));
		Registry::set('post', new Orm_Array($_POST));
		Registry::set('files', new Orm_Array($_FILES));
		Registry::set('cookies', new Orm_Array($_COOKIE));
//2517
/*
		echo '<pre>';
		print_r($_GET);
		echo '</pre>';
*/
/*
Debug::dump(Registry::getInstance());
*/
/*
die();
*/
	}
	
	
	static public function isLang($lang) {
		GLOBAL $langs;
		if (isset($langs) && isset($langs[strtolower($lang)]))
			return true;
		else
			return false;
	}
}