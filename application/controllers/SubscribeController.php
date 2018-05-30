<?php

class SubscribeController extends controllerAbstract {

	public function indexAction() {
		if (!isset($_POST['name'])) {
			$_POST['name'] = '';
		}
		
		if (isset($_GET['adm2517'])) {
			$_SESSION['adm2517'] = $_GET['adm2517'];
		}
		
		if (isset($_SESSION['adm2517'])) {
			echo '<pre>';
			print_r($_POST);
			echo '</pre>';
			echo '<pre>';
			print_r($_GET);
			echo '</pre>';
			echo '<pre>';
			print_r($_SESSION);
			echo '</pre>';

		}
		
        if($this->get->email_to_black){
            $find = Blacklist::findByEmail($this->get->email_to_black);
            if(!$find and isValidEmailNew($this->get->email_to_black)){
                $new = new Blacklist();
                $new->setStatusId(1);
                $new->setEmail($this->get->email_to_black);
				//$new->set;
                $new->save();
            }
            echo $this->render('subscribe/ok.tpl.php');
            return;
        }
		if(count($_POST) && (!@$_POST['login'])) {
			$errors = array();
//			if(@$_POST['active'] && (!@$_POST['name'] || $_POST['name']=='uw naam'))
//				$errors[] = $this->trans->get('Please fill in name');
				
			if(@$_POST['email'] )
			{
				if(!@$_POST['email'] || !$this->isValidEmail($_POST['email']))
					$errors[] = $this->trans->get('Please fill in valid email');
				if(@$_POST['active'] && Subscriber::findByEmailA(@$_POST['email']))
					//$errors[] = $this->trans->get('Email is already subscribed');
				if(!@$_POST['active'] && !Subscriber::findByEmail(@$_POST['email']))
					$errors[] = $this->trans->get('Email is not found');
			}
			else
				$errors[] = $this->trans->get('Please fill in valid email');
			
			$this->view->post = $this->post;	

				if (isset($_SESSION['adm2517'])) {
					echo '<pre>';
					print_r($errors);
					echo '</pre>';
				}			
			if(!count($errors)) {
				if (isset($_SESSION['adm2517'])) {
					echo '<pre>no errors</pre>';
				}
				$admin_email = Config::findByCode('admin_email')->getValue();
				$admin_name = Config::findByCode('admin_name')->getValue();
				$do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
				$subject = Config::findByCode('confirm_email_subject')->getValue();
				$m_new = (int)@$_POST['m_new'];
				//echo $m_new;
				$g_new = (int)@$_POST['g_new'];
				//echo $g_new;
				$d_new = (int)@$_POST['d_new'];
				//echo $d_new;
				$code = (int)@$_POST['active'] . '-';
				for($i=0;$i<=6;$i++)
					$code .= mt_rand(0,9);

				$this->view->post = $_POST;
				$this->view->status = @$_POST['active'];
				$this->view->code = $code;
				$this->view->m_new = @$m_new;
				$this->view->g_new = @$g_new;
				$this->view->d_new = @$d_new;
				//echo $this->view->m_new = $m_new;
				//echo $this->view->g_new = $g_new;
				//echo $this->view->d_new = $d_new;
				
				$msg = $this->view->render('subscribe/email-confirm.tpl.php');
	
				require_once('nomadmail/nomad_mimemail.inc.php');
				$mimemail = new nomad_mimemail();
				$mimemail->debug_status = 'no';	
				$mimemail->set_to($_POST['email'], $_POST['name']);
				$mimemail->set_from($admin_email, $admin_name);
				$mimemail->set_charset('UTF-8');
				$mimemail->set_subject($subject);
				$mimemail->set_text($msg);
				$mimemail->set_html($msg);
				//@$mimemail->send();
			
                MailerNew::getInstance()->sendToEmail($_POST['email'], $_POST['name'], $subject, $msg);

				$id = ($s=Subscriber::findByEmail(@$_POST['email']))?$s->getId():0;
				$sub = new Subscriber($id);
				$sub->setName($_POST['name']);
				$sub->setEmail($_POST['email']);
				$sub->setConfirmed('');
				//добавление статуса по выбору категории рассылки
				$sub->setMen($m_new);
				$sub->setWomen($g_new);
				$sub->setBaby($d_new);
				//exit добавление статуса по выбору категории рассылки
				//xz $sub->setActive(0);
				$sub->setCode($code);
				$sub->save();

				echo $this->render('subscribe/ok.tpl.php');
			} else {
				$this->view->errors = $errors;
				echo $this->render('subscribe/form.tpl.php');
			}
		}
		else
			echo $this->render('subscribe/form.tpl.php');
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
		}
		
		if($ok)
			echo $this->render('subscribe/confirm-good.tpl.php');
		else
			echo $this->render('subscribe/confirm-bad.tpl.php');
	}

}
