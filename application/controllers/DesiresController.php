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
    
    
    public function indexAction() {
        
        if($this->post->method == 'add') { $this->add($this->post->ids);}
	  if($this->post->method == 'dell') { $this->dell($this->post->ids);}
          
	if($this->ws->getCustomer()->getIsLoggedIn()){
	
	$array = [];
	foreach (Desires::findByQueryArray("SELECT  `id_articles` FROM  `ws_desires` WHERE  `id_customer` = ".$this->ws->getCustomer()->getId()) as $value) {
	$array[] = $value->id_articles;
            }

	$this->view->articles = wsActiveRecord::useStatic('Shoparticles')->findAll(array('`id` IN (' . implode(',', array_map('intval', $array)) . ')', ' stock not like "0" and status = 3'));
 
	$this->view->result = $this->view->render('finder/list.tpl.php');
        
	echo $this->view->render('finder/result.tpl.php');
		
		
}elseif(!$this->ws->getCustomer()->getIsLoggedIn()){
		if($_SESSION['desires']){
			$array = [];
			foreach ($_SESSION['desires'] as $item) {
				$array[] = $item;
			}
		$this->view->articles = wsActiveRecord::useStatic('Shoparticles')->findAll(array('`id` IN (' . implode(',', array_map('intval', $array)) . ')', ' stock not like "0"'));	
		$this->view->result = $this->view->render('finder/list.tpl.php');
		echo $this->view->render('finder/result.tpl.php');

		}
			}	
	}
        
        public function add($id = false) 
        {
                if($id){
		$result = 'on';
	if($this->ws->getCustomer()->getId()){
	$_SESSION['desires'][$id] = $id;
	$id_articles = $id;
	$a = wsActiveRecord::useStatic('Desires')->count(array('id_customer'=>$this->ws->getCustomer()->getId(), 'id_articles'=>$id_articles));
	if($a == 0){
	$d = new Desires();
	$d->setCtime(date('Y-m-d H:i:s'));
	$d->setIdCustomer($this->ws->getCustomer()->getId());
	$d->setIdArticles($id_articles);
	$d->save();
	$result = 'ok+';
	}
	}else{
	$_SESSION['desires'][$id] = $id;
	$result = 'ok+';
	}
        die(json_encode($result));
        }
	
      die(json_encode($result));
   
        }
        
        public function dell($id = false) 
        {

        if($id){
	$result = 'on';
	if($this->ws->getCustomer()->getId()){
	unset($_SESSION['desires'][$id]);
	$id = $this->ws->getCustomer()->getId();
	$id_articles = $id;
	$sql = "DELETE FROM `ws_desires` WHERE `id_customer` = ".$id." AND `id_articles` =".$id_articles;
	wsActiveRecord::query($sql);
	$result = 'on-';
	}else{
	$result = 'on-';
	unset($_SESSION['desires'][$id]);
	}
	//unset($_POST['method']);
          die(json_encode($result));
        }
	
        }
}
