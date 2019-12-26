<?php

class SubscribeController extends controllerAbstract {

	public function indexAction() {
		if (!isset($_POST['name'])) {
			$_POST['name'] = '';
		}
		
		
        if($this->get->email_to_black){
            $find = Blacklist::findByEmail($this->get->email_to_black);
            if(!$find and isValidEmailNew($this->get->email_to_black)){
                $new = new Blacklist();
                $new->setStatusId(1);
                $new->setEmail($this->get->email_to_black);
                $new->save();
            }
            echo $this->render('subscribe/ok.tpl.php');
            return;
        }
		if(count($_POST) and isset($_POST['save'])) {
			$errors = [];

			if($_POST['email'] )
			{
				if(!$_POST['email'] || !$this->isValidEmail($_POST['email']))
                                {$errors[] = $this->trans->get('Please fill in valid email');}
				//if($_POST['active'] && Subscriber::findByEmailA($_POST['email']))
					//$errors[] = $this->trans->get('Email is already subscribed');
				if(!$_POST['active'] && !Subscriber::findByEmail($_POST['email']))
                                {$errors[] = $this->trans->get('Email is not found');}
			}else{
				$errors[] = $this->trans->get('Please fill in valid email');
                        }
			
				
			
			if(!count($errors)) {
                            $this->view->post = $this->post;
				$id = ($s=Subscriber::findByEmail($_POST['email']))?$s->getId():0;
                                
				$sub = new Subscriber($id);
				$sub->setName($_POST['name']);
                                $sub->setEmail($_POST['email']);
                                
                                if($this->ws->getCustomer()->getIsLoggedIn()){
                                    $sub->setCustomerId($this->ws->getCustomer()->getId());
                                }
                                
                                if(!$sub->segment_id){
                                    $sub->setSegmentId(5);
                                }
				$sub->setConfirmed(date('Y-m-d H:i:s'));
                                $sub->setActive($_POST['active']);
				$sub->save();
                                 $this->view->email = $_POST['email'];
                                if($_POST['active'] == 1){
				echo $this->render('subscribe/ok.tpl.php');
                                }else{
                             echo $this->render('subscribe/unsubscribe.tpl.php');
                                }
                                
			} else {
				$this->view->errors = $errors;
                                echo $this->render('subscribe/form.tpl.php');
                              
			}
		}else{
                echo $this->render('subscribe/form.tpl.php');
                }
	}
	
	public function confirmAction()
	{
		$ok = 0;
		list($this->view->status,) = explode('-', $this->get->code);//sub or unsub
		$sub = Subscriber::findByCode($this->get->code);
		if($this->get->code && $sub)
		{
			$sub->setConfirmed(date('Y-m-d H:i:s'));
			$sub->setActive($this->view->status);
			$sub->save();
			$ok = 1;
                $this->view->email = $sub->email;
		}
		
		if($ok){
                    echo $this->render('subscribe/ok.tpl.php');
                }else{
			 echo $this->render('subscribe/unsubscribe.tpl.php');
                }
	}
        public function unsubscribeAction(){
            
            if($this->get->email){
               $s =  Subscriber::findByEmail($this->get->email);
               if($s->id){
                   $s->setActive(0);
                   $s->save();
                   
               }
               $this->view->email = $this->get->email;
              
            }
             echo $this->render('subscribe/unsubscribe.tpl.php');
             
        }
        

}
