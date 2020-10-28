<?php

class HomeController extends controllerAbstract {
    
      public function init() {
        parent::init();
        $this->view->critical_css = [
            '/css/bs/css/bootstrap.min.css',
            '/css/home/home.css',
        ];
        $this->view->css = [
            '/js/slider-fhd/slick.css',
            //'/js/slider-fhd/slick-theme.css?v=1',
            //'/css/bs/css/bootstrap.min.css',
            '/css/style_new.css',
            //'/css/new.css?v=1.3',
            //'/css/home/home.css',
          //  '/css/stores/fm.revealator.jquery.min.css'
        ];
        $this->view->scripts = [
            '/js/slider-fhd/slick.min.js',
            '/js/stores/fm.revealator.jquery.min.js',
            '/css/bs/js/bootstrap.min.js',
        ];
    if(Registry::get('device') == 'computer'){
	$this->_global_template = 'home.tpl.php';
    }else{
        if(isset($_COOKIE['mobil']) and $_COOKIE['mobil'] == 10){
            $this->_global_template = 'home.tpl.php';
        }else{
            $this->_global_template = 'mindex.php';
	}
    }
    
                    }

	public function indexAction() {
            
		if(!$this->cur_menu) {
			$this->view->cur_menu = $this->cur_menu = wsActiveRecord::useStatic(self::$_menu_class)->findByUrl('homepage')->at(0);
		}
                
        $today = date("Y-m-d H:i:s"); 
			$sq = "SELECT distinct(block), `red_home_blocks`.* from `red_home_blocks` where block in(1,2,4,5) and date <= '$today' ORDER BY  `red_home_blocks`.`sequence` ASC ";
		$this->view->homeblock = wsActiveRecord::useStatic('HomeBlock')->findByQuery($sq);
		
        $this->view->block6 = wsActiveRecord::useStatic('HomeBlock')->findAll(array("block = 6 and date <= '$today' and ( '$today' <= exitdate or exitdate = '0000-00-00 00:00:00') "), array(), array(0, (int)Config::findByCode('baner_to_home')->getValue()));

        if(Registry::get('device') == 'computer'){
            $lim = 3;
        }else{
            $lim = 6;
        }
		//blog
		$this->view->blog = wsActiveRecord::useStatic('Blog')->findAll(array('public = 1 and ctime < "'.date("Y-m-d H:i:s").'" '), array(), array(0, $lim));
		//blog
		
		
					
		/*$sql = "SELECT ws_articles.* FROM  ws_articles
			inner join ws_articles_top ON ws_articles.id = ws_articles_top.article_id
					WHERE ws_articles.active = 'y' and ws_articles.stock > 2 and ws_articles.old_price = 0 and ws_articles.status = 3  ORDER BY ws_articles.views DESC   LIMIT 0, 18";			
		$top = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
                */
		
               /*     $query = "SELECT * FROM  ws_articles
					WHERE stock > 2
					AND active = 'y'
					and ws_articles.status = 3
					and old_price = 0
					AND category_id NOT IN(54, 55, 163,84,315,314,249,306,297,307,296)
					AND dop_cat_id NOT IN (54, 55, 163,84,315,314,249,306,297,307,296)
					ORDER BY RAND ()  LIMIT 0, 10";
			$this->view->topproduct = wsActiveRecord::useStatic('Shoparticles')->findByQuery($query);
		*/
		
		
	//topprodukt 
	//oneprodukt	
	/*	$query = "SELECT * FROM  ws_articles
					WHERE stock like '1'
					AND active = 'y'
					and ws_articles.status = 3
					AND category_id NOT IN(54, 55, 163,84,315,314,249,306,297,307,296)
					AND dop_cat_id NOT IN (54, 55, 163,84,315,314,249,306,297,307,296)
					ORDER BY RAND () DESC LIMIT 0, 20";
		$this->view->oneproduct = wsActiveRecord::useStatic('Shoparticles')->findByQuery($query);
                */
	//oneprodukt
	
		//$this->view->news = News::findActiveNews(1);



	}

    public function newAction() {
	
	if($this->get->id){
	echo $this->render('finder/list.fhd.tpl.php');
	exit;
	}
	//$this->view->articles =  $this->ActiveArticles();
	$this->_global_template = 'home_new.tpl.php';

	}
	 public  function ActiveArticles(){
	 $mas = array();
		$mas[0] = 142;
		$mas[1] = 149;
		$mas[2] = 69;
		$mas[3] = 78;

	 $articles = array();
	 
	foreach ($mas as $m) {
	$articles[] = wsActiveRecord::useStatic('Shoparticles')->findByQuery('SELECT distinct(ws_articles.id), ws_articles.*,DATE_FORMAT(ws_articles.data_new,"%Y-%m-%d") as orderctime
        FROM ws_articles_sizes
        JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
        WHERE ws_articles_sizes.count > 0
        AND ws_articles.active = "y"
        AND ws_articles.stock not like "0"
		AND ws_articles.status = 3
		AND ws_articles.category_id = '.$m.'
        ORDER BY RAND()  LIMIT 10 ');
	}
	return $articles;

    }

}