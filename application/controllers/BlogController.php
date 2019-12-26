<?php

class BlogController extends controllerAbstract
{

public function init() {
        parent::init();
        $this->view->css = [
            '/css/cloudzoom/cloudzoom.css',
            '/css/jquery.lightbox-0.5.css',
            '/css/findex.css',
            '/css/stores/fm.revealator.jquery.min.css'
        ];
        $this->view->scripts = [
            '/js/cloud-zoom.1.0.2.js',
            '/js/jquery.lightbox-0.5.js',
            '/js/stores/fm.revealator.jquery.min.js'
        ];
    }
    
    public function indexAction()
    {
	$data = [];

	//$this->view->blog_cat = wsActiveRecord::useStatic('Blog')->findByQuery('SELECT * FROM `ws_blog_catgories`');

        //die($this->get);
	 if ((int)$this->get->id) {
             
            $blog_post = (int)$this->get->id;
            if ($blog_post) {
                $this->showOnePost($blog_post);
            } else {
                $this->_redirect('/blog/');
            }
        }elseif((int)$this->get->cat) {

		$blog_c = (int)$this->get->cat;
		if ($blog_c) {
                    
                    $cat = new BlogCategory($blog_c);
                    $this->category($cat);
                    //$this->_redirect($cat->getPath());
            } else {
                $this->_redirect('/blog/');
            }
		}else{
                    
                    $this->cur_menu->setName($this->trans->get('blog_name'));
        $this->cur_menu->setPageTitle($this->trans->get('blog_title'));
        $this->cur_menu->setMetatagDescription($this->trans->get('blog_description')); 
                  //  $this->cur_menu->url = '';
                    
              
                    
		$onPage = 10;
		$page = 1;
            if ((int)$this->get->page > 0) {
                $this->cur_menu->nofollow = 1;
                $page = (int)$this->get->page;
            }
            if(isset($_GET['utm_source'])){
                $this->cur_menu->nofollow = 1;
            }
            
	$this->view->onpage = $onPage;
            $this->view->page = $page;
		
		$d = date("Y-m-d H:i:s"); 
		$data[] = " public = 1 and ctime < '$d' ";
		$this->view->allcount = wsActiveRecord::useStatic('Blog')->count($data);
		$this->view->blog = wsActiveRecord::useStatic('Blog')->findAll($data, [], [$onPage * ($page - 1),$onPage ]);
                
           echo $this->render('blog/blog.tpl.php');
        }
			
		
        

    }
   
    
	public function showOnePost($blog_post) 
    {
	$this->view->onepostblog = $post = wsActiveRecord::useStatic('Blog')->findById((int)$blog_post);
        
         $this->cur_menu->setPageTitle($post->getPostName().' - '.$this->cur_menu->getName().' '.$this->trans->get('в интернет магазине RED'));
         
        $this->cur_menu->setName($post->getPostName());
        
        $this->cur_menu->setMetatagDescription($post->getPostName().' - '.$this->trans->get('Блог магазина RED ✓Тренды ✓Луки ✓ Советы по стилю'));//<Н1> 🡒 Блог магазина RED ✓Тренды ✓Луки ✓ Советы по стилю
        
    echo $this->render('blog/post.tpl.php');
 //echo $this->render('blog/blog.tpl.php');
    }
    
    public function category($cat){
     // $id = (int)$this->get->id;
     // $cat = new BlogCategory($id);
      
        $this->cur_menu->setName($this->trans->get('blog_name').' - '.$cat->getName());
        $this->cur_menu->setPageTitle($cat->getTitle());
        $this->cur_menu->setMetatagDescription($cat->getDescription());
      
        $onPage = 10;
		$page = 1;
            if ((int)$this->get->page > 0) {
                $this->cur_menu->nofollow = 1;
                $page = (int)$this->get->page;
            }
	$this->view->onpage = $onPage;
            $this->view->page = $page;
		
        
      $data = [];
	$d = date("Y-m-d H:i:s");
	$data[] = " public = 1 and ctime < '$d' and categories LIKE  '%".$cat->id."%'";
        $this->view->allcount = wsActiveRecord::useStatic('Blog')->count($data);
	$this->view->blog = wsActiveRecord::useStatic('Blog')->findAll($data, [], [$onPage * ($page - 1),$onPage ]);
    echo $this->render('blog/blog.tpl.php');
      
    }


	
}
