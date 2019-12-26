<?php

class ReviewsController extends controllerAbstract
{

public function init() {
        parent::init();
        $this->view->css = [
            '/css/findex.css',
            '/application/views/reviews/css/style_reviews.css',
            '/application/views/reviews/css/modal-contact-form.css'
        ];
        /*
        $this->view->scripts = [
            '/js/cloud-zoom.1.0.2.js?v=1.3.8',
            '/js/jquery.lightbox-0.5.js',
        ];
        */
    }
    
    public function indexAction()
    {
			
if((isset($_POST['send_reviews']) and isset($_POST['comment-type'])) or isset($_POST['send_onswer']))
	{
	if($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->isAdmin()){
	$pub = 1;
	}else{
	$pub = 0;
	}
	
	$rev = new Reviews();
	$rev->setParent_id($_POST['buf']);
	$rev->setUrl_id($_POST['url_id']);
	$rev->setName($_POST['sender_name']);
	$rev->setUrl($_POST['url']);
	$rev->setMail($_POST['sender_email']);
	$rev->setText(strip_tags($_POST['message']));
	$rev->setDate_add(date("d.m.Y \в\ H:i"));
	$rev->setPublic($pub);
	if(isset($_POST['comment-type'])){$rev->setFlag($_POST['comment-type']);}
	$rev->save();
	
	 header ('Location: /reviews/');
	}
	
	$onPage = 20;
        $page = 1;
            if ((int)$this->get->page > 0) {
                $page = (int)$this->get->page;
            }
            // $this->cur_menu->setName($this->trans->get('blog_name'));
        $this->cur_menu->setPageTitle($this->cur_menu->getName().': '.$this->trans->get('page').' '.$page.' - '.$this->trans->get('в интернет магазине RED'));
        $this->cur_menu->setMetatagDescription($this->cur_menu->getName().': '.$this->trans->get('page').' '.$page.' - '.$this->cur_menu->getMetatagDescription()); 
        
	$this->view->onpage = $onPage;
            $this->view->page = $page;
			
$this->view->allcount = wsActiveRecord::useStatic('Reviews')->count(array('public' => 1, 'parent_id' => 0));
$coments = wsActiveRecord::useStatic('Reviews')->findByQuery('SELECT  distinct(`id`), `parent_id`, `url_id`, `id_material`, `name`, `url`, `mail`, `text`, `date_add`, `public`, `flag` FROM ws_comment_system where public = 1 and parent_id = 0 order by id DESC LIMIT '.$onPage * ($page - 1).', '.$onPage);
		
		$this->view->coments = $coments;
		
		echo $this->render('reviews/comments.php');
        

    }
   


	
}
