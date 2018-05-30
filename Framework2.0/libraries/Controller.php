<?php

abstract class Controller {

    public function __construct() {
    	$this->registry = Registry::getInstance();
    	//by reference?
		$this->get = $this->registry->getGet();
		$this->post = $this->registry->getPost();
		$this->files = $this->registry->getFiles();
		$this->cookies = $this->registry->getCookies();

		$this->website = $this->registry->get('Website');
		$this->cur_menu = $this->registry->get('curMenu');
		$this->view = new View();
		$this->view->curMenu = $this->cur_menu;
		$this->view->cur_menu = $this->cur_menu;
		$this->view->website = $this->website;
		$this->init();
    }
	
	public function render($file = '') {
		if(!$file)
			$file = strtolower(str_replace('Controller', '', get_class($this))) . DIRECTORY_SEPARATOR . 'index.tpl.php';
		return $this->view->render($file);
	}
	
	//404
	public function __call($method, $params) {
		if(strpos($method, 'ction')!==false)
			$this->show404Action($method, $params);
		else
			return false;
	}
		
	static public function process() {
		$controller = Registry::getInstance()->getGet()->getController();
		$action = Registry::getInstance()->getGet()->getAction();
		//print_r(Registry::getInstance()->getGet());
		//echo $action;
		//add Action and Controller
		$action .= 'Action'; 
		$controller .= 'Controller'; 
		//do actual call
		ob_start();
		$c = new $controller();
		/*
		if(!method_exists($c, $action))
			$action = 'indexAction';
		*/
		$c->$action();
		$result = ob_get_contents();
		ob_end_clean();
		return $c->_postAction($result);
	}
	
	protected function _postAction($content) {
		return $content;
	}
	
	public function init() {}
	
	protected function _redirect($url) {
		redirect($url);
	}
	
	abstract public function indexAction();
	
}