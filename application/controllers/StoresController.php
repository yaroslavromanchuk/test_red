<?php

class StoresController extends  Controller
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
         $cache_name = 'stores_header';
      $stores_header = $cache->load($cache_name);// меню навигации
        if (!$stores_header) { //если сломалось пищещь сюда TRUE // верхнее меню
            $stores_header = $this->view->render('/stores/header.php');
            $cache->save($stores_header, $cache_name, array($cache_name), false);
        }
          $this->view->stores_header = $stores_header;
	//$this->view->setRenderPath('developer');
	$this->_global_template = '/stores/stores.tpl.php';
    }


public function indexAction(){
    if($this->get->id){
        $this->_global_template = '/stores/stores_temp.tpl.php';
         $this->view->post = $post =  Stores::getIds($this->get->id);
         $this->cur_menu->setName($post->name);
   $this->cur_menu->setPageTitle('Магазини RED');
    $this->cur_menu->setMetatagDescription('Акції роздрібних магазинів RED – Київ, Бориспіль ✓ Телефони ✓ Графік работи');
        $this->cur_menu->nofollow = 1;
         echo $this->render('stores/one_post.php');
       // echo 'tut';
        //die();
    }else{
   $this->cur_menu->setName('Акції');
   $this->cur_menu->setPageTitle('Магазини RED');
   $this->cur_menu->setMetatagDescription('Акції роздрібних магазинів RED – Київ, Бориспіль ✓ Телефони ✓ Графік работи');
   $this->cur_menu->nofollow = 1;
    echo $this->render('stores/akcii.php');
    }
}
public function postAction(){
    $this->_global_template = '/stores/stores_temp.tpl.php';
    if($this->get->id){
       $post =  Stores::getOnePostIds($this->get->id);
        if($post->id){
            $this->view->post = $post; 
     $this->cur_menu->setName($post->name);
   $this->cur_menu->setPageTitle($post->name);
    $this->cur_menu->setMetatagDescription($post->name.' - Акції роздрібних магазинів RED – Київ, Бориспіль ✓ Телефони ✓ Графік работи');
        }else{  
    $post['src'] = '/storage/images/RED_ua/stores/temp_post/_404.png'; 
    $post['href'] = '/stores/'; 
    $this->view->error = $post;
   //$this->cur_menu->setName('404');
   $this->cur_menu->setPageTitle('404');
   $this->cur_menu->setMetatagDescription('Акції роздрібних магазинів RED – Київ, Бориспіль ✓ Телефони ✓ Графік работи');
        }
     
       }else{
           
       }
         
  
     echo $this->render('stores/one_post.php');
}
public function addresAction(){
    
   $this->cur_menu->setName('Адреси магазинів');
   $this->cur_menu->setPageTitle('Магазини RED');
   $this->cur_menu->setMetatagDescription('Адреса роздрібних магазинів RED – Київ, Бориспіль ✓ Телефони ✓ Графік работи');
   $this->cur_menu->nofollow = 1;
    echo $this->render('stores/address.php');
}
public function infoAction(){
    
   $this->cur_menu->setName('Інформація');
   $this->cur_menu->setPageTitle('Магазини RED');
   $this->cur_menu->setMetatagDescription('Інформація роздрібних магазинів RED – Київ, Бориспіль ✓ Телефони ✓ Графік работи');
   $this->cur_menu->nofollow = 1;
    echo $this->render('stores/info.php');
}

protected function _postAction($content)
    {
        $this->view->setContent($content);
        return $this->view->render($this->_global_template);
    }

}
