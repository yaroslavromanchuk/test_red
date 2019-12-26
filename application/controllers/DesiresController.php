<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DesiresController
 *
 * @author PHP
 */
class DesiresController extends controllerAbstract
{
    
    
    //put your code here
    public function init() {
        parent::init();
        $this->view->css = [
            '/css/cloudzoom/cloudzoom.css',
            '/css/jquery.lightbox-0.5.css',
            '/js/select2/css/select2.min.css',
            '/css/catalog/catalog.css', 
        ];
        $this->view->scripts = [
            '/js/desires.js',
            '/js/call/jquery.mask.js',
            '/js/jquery.cycle.all.js',
            '/js/cloud-zoom.1.0.2.js',
            '/js/jquery.lightbox-0.5.js',
            '/js/slider-fhd/slick.min.js',
            '/lib/select2/js/select2.min.js'
        ];
    }
    
    public function indexAction() {
      // d(Shoparticles::findByIds($this->post->ids), false);
        if($this->post->method == 'add') { self::add($this->post->ids);}
	  if($this->post->method == 'dell') { self::dell($this->post->ids);}
          
	if($this->ws->getCustomer()->getIsLoggedIn()){
            $sql = 'SELECT `ws_articles`.* FROM `ws_articles` '
                    . 'join `ws_desires` ON  `ws_articles`.`id` = `ws_desires`.`id_articles`'
                    . 'WHERE `ws_desires`.`id_customer` ='.$this->ws->getCustomer()->getId().' and'
                    . ' `ws_articles`.`status` = 3';
            $art = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
        }else{
            if($_SESSION['desires']){                      
	$art = wsActiveRecord::useStatic('Shoparticles')->findAll(array('`id` IN (' . implode(',', $_SESSION['desires']) . ')', ' stock not like "0" and status = 3'));
            }
            
        }
            if($art){
	$this->view->articles = $art;
        $this->view->result_count = $art->count();
            }
	$this->view->result = $this->view->render('finder/list.desires.tpl.php');
        
	echo $this->view->render('finder/result.tpl.php');
		
	
	}
        
        private function add($id = false) 
        {
            $result = 'error';
                if($id){
		$result = 'on';
	if($this->ws->getCustomer()->getId()){
          //  if(Shoparticles::findByIds($id)){
	$_SESSION['desires'][$id] = $id;
	//$id_articles = $id;
	$a = wsActiveRecord::useStatic('Desires')->count(array('id_customer'=>$this->ws->getCustomer()->getId(), 'id_articles'=>$id));
	if(!$a){
	$d = new Desires();
	$d->setCtime(date('Y-m-d H:i:s'));
	$d->setIdCustomer($this->ws->getCustomer()->getId());
	$d->setIdArticles($id);
	$d->save();
	$result = 'ok+';
	}
       // }
	}else{
          //  if(Shoparticles::findByIds($id)->id){
	$_SESSION['desires'][$id] = $id;
	$result = 'ok+';
          //  }
	}
        die(json_encode($result));
        }
	
      die(json_encode($result));
   
        }
        
        private function dell($id = false) 
        {
            $result = 'error';
        if($id){
	$result = 'on';
	if($this->ws->getCustomer()->getId()){
	unset($_SESSION['desires'][$id]);
	//$id = $this->ws->getCustomer()->getId();
	//$id_articles = $id;
	$sql = "DELETE FROM `ws_desires` WHERE `id_customer` = ".$this->ws->getCustomer()->getId()." AND `id_articles` =".$id;
	wsActiveRecord::query($sql);
	$result = 'on--';
	}else{
	$result = 'on-';
	unset($_SESSION['desires'][$id]);
	}
	//unset($_POST['method']);
          
        }
        die(json_encode($result));
	
        }
       
}
