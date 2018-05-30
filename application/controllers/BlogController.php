<?php

class BlogController extends controllerAbstract
{


    public function indexAction()
    {
	$data = array();

	$this->view->blog_cat = wsActiveRecord::useStatic('Blog')->findByQuery('SELECT * FROM `ws_blog_catgories`');

	 if ((int)$this->get->id) {
            $blog_post = (int)$this->get->id;
            if ($blog_post) {
                $this->showOnePost($blog_post);
            } else {
                $this->_redirect('/blog/');
            }
        } elseif((int)$this->get->category) {

		$blog_c = (int)$this->get->category;
		if ($blog_c) {
                $this->showPostCategory($blog_c);
            } else {
                $this->_redirect('/blog/');
            }
		}else{
		$onPage = 10;
		$page = 1;
            if ((int)$this->get->page > 0) {
                $page = (int)$this->get->page;
            }
			 $this->view->onpage = $onPage;
            $this->view->page = $page;
		
		$d = date("Y-m-d H:i:s"); 
		$data[] = " public = 1 and ctime < '$d' ";
		$this->view->allcount = wsActiveRecord::useStatic('Blog')->count($data);
		$this->view->blog = wsActiveRecord::useStatic('Blog')->findAll($data, array(), array($onPage * ($page - 1),$onPage ));
           echo $this->render('blog/blog.tpl.php');
        }
			
		
        

    }
	public function showOnePost($blog_post) 
    {
	$data = array();
	
	$this->view->blog_cat = wsActiveRecord::useStatic('Blog')->findByQuery('
            SELECT * FROM `ws_blog_catgories`');
			$d = date("Y-m-d H:i:s");
		$data[] = " public = 1 and ctime < '$d' and id=".$blog_pos;
	$this->view->onepostblog = wsActiveRecord::useStatic('Blog')->findAll(array('public'=>1, "id"=>$blog_post));
    echo $this->render('blog/post.tpl.php');

    }
	public function showPostCategory($blog_c)
    {
	$data = array();
	$d = date("Y-m-d H:i:s");
		$data[] = " public = 1 and ctime < '$d' and categories LIKE  '%".$blog_c."%'";
		//$data[] .= 'c.email LIKE "%' . $dt->email . '%"';
		//array("public"=>1, "categories LIKE  '%".$blog_c."%'")
	$this->view->blog = wsActiveRecord::useStatic('Blog')->findAll($data);
    echo $this->render('blog/blog.tpl.php');

    }
	// для лайков на статью
	public function getlikeokAction(){
	$id_customer = @intval($_GET['id_c']);
	$id_post = @intval($_GET['id_p']);
	
      $sql = "SELECT * FROM ws_blog_like WHERE id_customer = ".$id_customer." AND id_post = ".$id_post;
		$result=mysql_query($sql);
		if(mysql_num_rows($result)==0){
		$sql = "INSERT INTO `ws_blog_like`(`id_customer`, `id_post`) VALUES (".$id_customer.", ".$id_post.")";
		wsActiveRecord::query($sql);
		$item = new Blog($id_post);
		$like = $item->getLike();
		$item->setLike($like+1);
		$item->save();
		$result = array('type' => 'success', 'like' => $like+1);
		//return $like+1;
		}else{
		$result = array('type' => 'error');
		}
		print json_encode($result);
        exit;
        
    }
	

	
}
