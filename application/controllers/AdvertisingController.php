<?php

class AdvertisingController extends  Controller
{

 public function init()
    {
     $this->domain = strtolower(str_replace('www', '', $_SERVER['HTTP_HOST']));
        $this->ws = $this->website = Registry::get('website');
        $this->view->user = $this->ws->getCustomer();
        
        $this->view->ws = $this->ws;
       // $this->view->message = $this->message;
        $this->view->get = $this->get;
        $this->view->post = $this->post;

		
        Registry::set('View', $this->view);
         $cache = Registry::get('cache');
	$cache->setEnabled(true);
        
        /* $cache_name = 'stores_header';
      $stores_header = $cache->load($cache_name);// меню навигации
        if (!$stores_header) { //если сломалось пищещь сюда TRUE // верхнее меню
            $stores_header = $this->view->render('/stores/header.php');
            $cache->save($stores_header, $cache_name, array($cache_name), false);
        }*/
          //$this->view->stores_header = $stores_header;
	//$this->view->setRenderPath('developer');
	$this->_global_template = '/advertising/advertising.tpl.php';
    }


public function indexAction(){
   if($this->get->id){
   echo $this->render('finder/list.fhd.tpl.php');
   exit;
   }
   
}


protected function _postAction($content)
    {
        $this->view->setContent($content);
        return $this->view->render($this->_global_template);
    }

}
