<?php

class AdminController extends controllerAbstract
{

    private $_files_folder = 'backend';
    private $_controller = 'admin';
    /**
     * Название темы
     * @var type 
     */
    private $_theme = 'default';
    /**
     * Файл шаблона
     * @var type 
     */
    private $_template = 'admin.tpl.php';

    //maybe put to controller abstract??
    public function init()
    {
        mb_internal_encoding("UTF-8");
        
        $this->view->path = '/' . $this->_controller . '/';
        $this->view->user = $this->user = $this->website->getCustomer();
        $this->view->trans = $this->trans = new Translator();
        
       // $cus = [8005];//,37484
       // if(in_array($this->user->id, $cus)){
          //  $this->_theme = 'starlight';
          //  $this->_template = 'index.php';
       // }
        
        $this->template = $this->_template;
        if(true){
           // $this->_theme = 'starlight';
             $this->view->files  = '/' . $this->_files_folder . '/theme/'.$this->_theme.'/';
             $this->view->setRenderPath(INPATH . $this->_files_folder.'/theme/'.$this->_theme);
        }else{
        $this->view->files  = '/' . $this->_files_folder . '/';
        $this->view->setRenderPath(INPATH . $this->_files_folder);
        }

        //get user from sesion or cookies
        if (!$this->user->getIsLoggedIn() || !$this->user->isAdmin()) {
            $this->loginAction();
            die();
        }

        $a_rights = [];
        foreach (AdminRights::getAdminRights($this->user->getId()) as $rights) {
            $a_rights[$rights->getPageId()]['right'] = $rights->getRight();
            $a_rights[$rights->getPageId()]['view'] = $rights->getView();
        }
        
        if (!$this->user->isSuperAdmin()) {
            if (!$a_rights[$this->cur_menu->getId()]['right']) {
                die('Нету прав просматривать эту страницу. Обратитесь к Супер-Администратору.<br /> <a href="/admin/">На главную</a>');
            }
        }
        $this->view->admin_rights = $a_rights;
        		
	//vsplivauche soobschenye
$this->view->message = $this->view->render('np/message.tpl.php');	
$this->view->days = array('Mon'=>'Понедельник', 'Tue'=>'Вторник', 'Wed'=>'Среда', 'Thu'=>'Четверг', 'Fri'=>'Пятница', 'Sat'=>'Суббота','Sun'=>'Воскресенье');
}

    public function render($inner, $outter = 'admin.tpl.php')
    {
        $this->view->middle_template = $inner;
        if($this->template == 'index.php'){ $outter = 'index.php';}
        return $this->view->render($outter);
    }

    public function _redir($action)
    {
        $this->_redirect($this->_path($action));
    }

    protected function _path($action)
    {
        return '/' . $this->_controller . '/' . $action . '/';
    }

    protected function _formatDT($val)
    {
        $data = date_parse($val);
        $res = $data['year'] . '-' . $data['month'] . '-' . $data['day'] . ' ' . str_pad($data['hour'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($data['minute'], 2, '0', STR_PAD_LEFT) . ':' . str_pad($data['second'], 2, '0', STR_PAD_LEFT);
        return $res;
    }

    public function __call($name, $params)
    {
        //in case we have error in admin panel
        die($name . ' - not found in admin panel');
    }


    protected function _postAction($content)
    {
        return $content;
    }

    public function loginAction()
    {
        if ($this->user->getIsLoggedIn() and $this->user->isAdmin()){
            if($this->user->getId() == 34936){ //dla Maksima redirect
                $this->_redir('stores-akciya');
            }
        $this->_redir('index');
        }

        if (!count($this->post)) {
            //render login page
            echo $this->render('', 'login.tpl.php');
        } else {
            //or do	login
            if (Registry::isRegistered('site_id')) {
                $old_site = Registry::get('site_id');
                Registry::unRegister('site_id');
            }

            $res = $this->user->loginByEmail($_POST['login'], $_POST['password']);

            if (isset($old_site)){  Registry::set('site_id', $old_site);}

            if ($res) {
                $this->website->updateHashes();
                if($this->user->getId() == 34936){
                $this->_redir('stores-akciya'); 
                
            }
                $this->_redir('index');
            } else {
                $this->view->errors = $this->trans->get("Wrong login/password");
                echo $this->render('', 'login.tpl.php');
            }
        }
    }

    public function logoutAction()
    {
        $this->website->getCustomer()->logout();
        $this->website->updateHashes();
        $this->_redir('index');
    }
        /**
         * Статистика
         * url = /home/
         */
	public function homeAction(){
	if($this->post->method){
            ini_set('memory_limit', '4096M');
                set_time_limit(2800);
            die(json_encode(HomeAnalitics::sendPost($this->post)));
            }elseif($this->get->method){
                ini_set('memory_limit', '4096M');
                set_time_limit(3600);  
            $res = HomeAnalitics::sendGet($this->get); 
            ParseExcel::saveToExcel($res['name'], $res['parametr'], $res['style']);
            exit(); 
            }
            
          //  l($this->cur_menu);
           if($this->cur_menu->getParameter() == 'oborot'){
                
                echo $this->render('reports/oborot.php', 'index.php');
            }elseif($this->cur_menu->getParameter() == 'prognoz'){
                
                echo $this->render('reports/prognoz.php', 'index.php');
            }else{
            
         //оформленные заказы за сегодня
        $res_create_order =  HomeAnalitics::createOrder();
        $this->view->orders_days = $res_create_order['days'];   
	$this->view->orders_week = $res_create_order['week']; 
	$this->view->orders_month = $res_create_order['month']; 
	$this->view->orders_year = $res_create_order['year']; 
         //активные заказы за сегодня
        $res_active_order =  HomeAnalitics::activeOrder();
        $this->view->orders_days_active = $res_active_order['days'];   
	$this->view->orders_week_active = $res_active_order['week']; 
	$this->view->orders_month_active = $res_active_order['month']; 
	$this->view->orders_year_active = $res_active_order['year'];
        //оплаченные заказы за сегодня
        $res_payment_order =  HomeAnalitics::paymentOrder();
        $this->view->orders_days_op = $res_payment_order['days'];   
	$this->view->orders_week_op = $res_payment_order['week'];
	$this->view->orders_month_op = $res_payment_order['month'];
	$this->view->orders_year_op = $res_payment_order['year'];
        //не обработанные коментарии
        $this->view->orders_koment =  HomeAnalitics::comment();
        //средний чек за последние 30 дней
        $this->view->chek = HomeAnalitics::chek();
        $this->view->res_brand_sub =  HomeAnalitics::brandsubscribe();
        
	echo $this->render('reports/statistic.php', 'index.php');
        }
        
	}
        
        /**
         * Google analitics - получение аналитики через апі
         * @param type $from - дата от: (2018-12-01)
         * @param type $to - дата до: (2018-12-31)
         * @return string
         */
        public function staticAction()
            {
                echo $this->render('slugebnoe/static.tpl.php');
            }


    public function sloganAction()
    {
        if (isset($_GET['status'])) {
            if (isset($_GET['id'])) {
                if ((int)$_GET['status'] == 1 or (int)$_GET['status'] == 0) {
                    $item = new ActionSlogan((int)$_GET['id']);
                    $item->setStatus((int)$_GET['status']);
                    $item->save();
                    $this->_redir('slogan');
                }
            }
        }
        if (isset($_GET['del']) and $_GET['del'] == 1) {
            if (isset($_GET['id'])) {
                $item = wsActiveRecord::useStatic('ActionSlogan')->findFirst(array('id' => (int)$_GET['id']));
                foreach (wsActiveRecord::useStatic('ActionSloganscore')->findAll(array('slogan_id' => $item->getId())) as $itm) {
                    $itm->destroy();
                }
                $item->destroy();
                $this->_redir('slogan');
            }
        }
        if (count($_POST)) {
            $date = $this->post;
            if ($date->id) {
                $item = new ActionSlogan($date->id);
                $item->setName($date->name);
                $item->setEmail($date->email);
                $item->setPhone($date->phone);
                $item->save();
            }
        }
        $this->view->slogans = wsActiveRecord::useStatic('ActionSlogan')->findAll(array('status < 3'));
        echo $this->render('action/slogan.tpl.php');
    }

    public function bestfotoAction()
    {
        if ($this->get->add_new) {
            $foto = new ActionFoto();
            if (count($_POST)) {
                $errors = array();

                $date = $this->post;
                if (strlen($date->name) < 2) $errors['name'] = $this->trans->get("Please enter your name");
                if (!$date->phone) $errors['phone'] = $this->trans->get("Please enter your phone");
                $filename_image = '';

                $foto->setFilename($filename_image);
                $foto->setName($date->name);
                $foto->setEmail($date->email);
                $foto->setPhone($date->phone);
                $foto->setStatus(1);
                $foto->setActionId($date->action_id);
                $foto->setText($date->text);

                if ($_FILES['image']['name']) {
                    $f = pathinfo($_FILES['image']['name']);
                    $ext = strtolower($f['extension']);
                    if ((int)$_FILES['image']['size'] == 0) $errors['image'] = "Выбирете фотограцию";
                    if ((int)$_FILES['image']['size'] > 2000000) $errors['image'] = "Размер фотографии не должен привышать 2 mb.";
                    if ($ext != 'jpeg' and $ext != 'jpg' and $ext != 'png' and $ext != 'gif') {
                        $errors['image'] = "Неверный формат файлов, загружать можно только файды .jpeg, .jpg, .png, .gif";
                    }
                }

                if (!count($errors)) {
                    if ($_FILES['image']) {
                        $mdfname = md5(uniqid(rand(), true));
                        require_once('upload/class.upload.php');

                        $handle = new upload($_FILES['image'], 'ru_RU');
                        $handle->image_resize = true;
                        $handle->image_x = 800;
                        $handle->image_y = 800;
                        $handle->image_ratio_no_zoom_in = true;
                        $handle->file_src_name_body = $mdfname;
                        $handle->file_dst_name_body = $mdfname;
                        $folder = INPATH . "files/org/";
                        if ($handle->uploaded) {
                            $handle->process($folder);
                            if ($handle->processed) {
                                $filename_image = $handle->file_dst_name;
                                $handle->clean();
                            } else {
                                $errors['image'] = "Не удалось загрузить фаил";
                            }

                        } else {
                            $errors['image'] = "Не удалось загрузить фаил";
                        }
                    }
                }

                if (!count($errors)) {

                    if (isset($filename_image)) {


                        $foto->setFilename($filename_image);
                        $foto->setName($date->name);
                        $foto->setEmail($date->email);
                        $foto->setPhone($date->phone);
                        $foto->setStatus(1);
                        $foto->setActionId($date->action_id);
                        /*                        $image->setItem($date->item);
                     /*   $image->setBrend($date->brend);*/
                        /* $image->setAge($date->age);
                        $image->setHoby($date->hoby);*/
                        $foto->setText($date->text);
                        /* $image->setNextName($date->next_name);*/
                        /*$image->setType($date->type);*/
                        /*                        $image->setPrice($price . ' грн.');*/
                        $foto->save();
                        $this->view->saved = 1;
                    }
                } else {
                    $this->view->errors = $errors;
                }

            }
            $this->view->foto = $foto;
            echo $this->render('action/best-foto-new.tpl.php');
        } else {
            if (isset($_GET['status'])) {
                if (isset($_GET['id'])) {
                    if ((int)$_GET['status'] == 1 or (int)$_GET['status'] == 0) {
                        $item = new ActionFoto((int)$_GET['id']);
                        $item->setStatus((int)$_GET['status']);
                        $item->save();
                        $this->_redir('best-foto');
                    }
                }
            }
            if (isset($_GET['del']) and $_GET['del'] == 1) {
                if (isset($_GET['id'])) {
                    $item = wsActiveRecord::useStatic('ActionFoto')->findFirst(array('id' => (int)$_GET['id']));
                    foreach (wsActiveRecord::useStatic('ActionFotoscore')->findAll(array('image_id' => $item->getId())) as $itm) {
                        $itm->destroy();
                    }
                    $item->destroy();
                    $this->_redir('best-foto');
                }
            }
            if (count($_POST)) {
                $date = $this->post;
                if ($date->id) {
                    $item = new ActionFoto($date->id);
                    $item->setName($date->name);
                    $item->setEmail($date->email);
                    $item->setPhone($date->phone);
                    $item->setItem($date->item);
                    $item->setBrend($date->brend);
                    $item->setPrice($date->price);
                    $item->setAge($date->age);
                    $item->setHoby($date->hoby);
                    $item->setText($date->text);
                    $item->setNextName($date->next_name);
                    $item->setType($date->type);
                    $item->save();
                }
            }

            if (isset($this->post->konkurs)) {
                $_SESSION['admin_konkurs'] = $this->post->konkurs;
            }
            $kid = (int)@$_SESSION['admin_konkurs'];
            $this->view->konkurs = $kid;

            $this->view->fotos = wsActiveRecord::useStatic('ActionFoto')->findAll(array('status < 3', 'action_id' => $kid));
            echo $this->render('action/best-foto.tpl.php');
        }
    }

    //------------ PAGES
    public function pagesAction()
    {
        $this->view->pages = wsActiveRecord::useStatic('Menu')->findUserPages();
        
        if ($this->user->isSuperAdmin()){ $this->view->pages->merge(Menu::findAdminMenu());}

        echo $this->render('page/list.tpl.php');
    }

    public function pageAction()
    {
        if ($this->cur_menu->getParameter() == 'delete') {
            if ($this->get->getId()) {
                $page = new Menu($this->get->getId());
                if ($page->getId() && !$page->getNoDelete())
                    $page->destroy();
                else
                    $this->view->errors = array($this->trans->get('Page cannot be deleted'));
            } else {
                $this->_redir('pages');
            }
            echo $this->render('slugebnoe/static.tpl.php');

        } elseif ($this->cur_menu->getParameter() == 'edit') {
            $page = new Menu($this->get->getId());
            /*if (!$page->getId()) {
                	
                    $page->action = "index";
                } elseif (!$page->getId() || !$page->getTypeId()==2){
                	$page->action = "admin";
                }*/

            //$this->view->roots = Menu::findTopMenu();

            $this->view->roots = wsActiveRecord::useStatic('Menu')->findAll(array("type_id"=>2," id <> '" . (int)$this->get->getId() . "'"));
			
            $this->view->menuTypes = wsActiveRecord::useStatic('wsMenuType')->findAll(array("id IN (1, 2, 8)"));
             $this->view->section = AdminSection::find('AdminSection');
                $sql = "SELECT * FROM  `ws_menus` GROUP BY  `controller`";
               $this->view->controller = wsActiveRecord::findByQueryArray($sql);
            /*if(!$page->getTypeId() && !$page->getId() && $this->user->isSuperAdmin())
                           $page->setTypeId(1);*/
            if (count($_POST)) {
                foreach ($_POST as &$value){$value = stripslashes($value);}
                $errors = [];
                if (!$page->getId() && !trim($_POST['url'])) {
                    $errors[] = $this->trans->get('Please fill in page ID');
                } else {
                    if (!$_POST['name']){$errors[] = $this->trans->get('Please fill in page title');}
                    if (!$_POST['action']){$errors[] = $this->trans->get('Please fill in page action');}

                    if ($this->user->isSuperAdmin()){ $page->setNoDelete(0);} //reset - to use with checkboxes

                    $page->import($_POST);

                    if ($page->getTypeId() == 2) {$page->setParentId(4);}

                    if (!$page->getId()) {
                        $ps = wsActiveRecord::useStatic('Menu')->findByUrl($page->getUrl());
                        if ($ps){
                            foreach ($ps as $p) {
                                if ($p == $page->getUrl()) {
                                    $errors[] = $this->trans->get('Page ID is already taken');
                                    break;
                                }
                            }
                    }

                        $tmp = wsActiveRecord::useStatic('Menu')->findLastSequenceRecord();
                        if ($tmp && $page->getTypeId() != 2){ 
                            $page->setSequence($tmp->getSequence() + 10);
                        }else{
                            $page->setSequence(10);
                        }
                    }

                    if (!$page->getTypeId()){ $page->setTypeId(null);} // should be null 1  or 2

                    if ($_POST['rem_image1']) { $page->setImage(''); }elseif (@$_FILES['image1']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['image1'], 'ru_RU');
                        $folder = Config::findByCode('menu_folder')->getValue();
                        if ($handle->uploaded) {
                            $width = Config::findByCode('menu_image_width')->getValue();
                            $height = Config::findByCode('menu_image_height')->getValue();
                            if (($handle->image_src_x != $width && $width) || ($handle->image_src_y != $height && $height)) {
                                $errors = array($this->trans->get('Image sizes do not match'));
                            }
                            if (!count($errors)) {
                                $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                                if ($handle->processed) {
                                    if ($page->getImage()){@unlink($page->getImage());}
                                    
                                    $page->setImage($folder . $handle->file_dst_name);
                                    $handle->clean();
                                }
                            }
                        }
                    }

                    if (!$page->getParentId()){ $page->setParentId(null); }
                    if ($page->getId() == $page->getParentId()){ $page->setParentId(null);}

                    $page->action = (trim($_POST['action']));
                    if (!count($errors)) {
                        $page->save();
                        $this->view->saved = 1;
                        //unset($this->view->errors);
                    } else {
                        $this->view->errors = $errors;
                    }
                }
            }
            $this->view->page = $page;
            echo $this->render('page/edit.tpl.php');

        } elseif (in_array($this->cur_menu->getParameter(), array('moveup', 'movedown'), true) && $this->get->getId()) {
            $a = new Menu($this->get->getId());
            if ($a && $a->getId()) {
                if ('moveup' == $this->cur_menu->getParameter()){
                    $b = wsActiveRecord::useStatic('Menu')->findFirst("sequence < '{$a->getSequence()}'", array('sequence' => 'DESC'));
                    }else{
                        $b = wsActiveRecord::useStatic('Menu')->findFirst("sequence > '{$a->getSequence()}'", array('sequence' => 'ASC'));
                        }
                if ($b && $b->getId()) {
                    $q = "update ws_menus set sequence = " . (int)$a->getSequence() . " where id = {$b->getId()}";
                    wsActiveRecord::query($q);
                    $q = "update ws_menus set sequence = " . (int)$b->getSequence() . " where id = {$a->getId()}";
                    wsActiveRecord::query($q);
                }
            }
            //d(SQLLogger::getInstance()->reportBySQL());
            $this->_redir('pages');
        } else{
        $this->_redir('pages');
        
        }

        //echo $this->render('page/list.tpl.php');
    }

    public function homeblocksAction()
    {
        $date = [];
        if (isset($_GET['block']) and (int)$_GET['block'] > 0){
            $date['block'] = (int)$_GET['block'];
                    }
        $this->view->pages = wsActiveRecord::useStatic('HomeBlock')->findAll($date);
        echo $this->render('page/blocklist.tpl.php');
    }

    public function homeblockAction()
    {
        if (isset($this->get->delete) and (int)$this->get->delete > 0) {
            $block = new HomeBlock((int)$this->get->delete);
            if ($block->getId()) { 
			    @unlink($_SERVER['DOCUMENT_ROOT'].$block->getImage());
                            @unlink($_SERVER['DOCUMENT_ROOT'].$block->getImageUk());
			$block->destroy();
			}
            $this->_redir('homeblocks');
        }elseif ($this->cur_menu->getParameter() == 'edit') {
           // if($this->post->savepage) { echo print_r($this->post); die();}
            $block = new HomeBlock((int)$this->get->edit);
            if (count($_POST)) {
               
                $errors = [];
                foreach ($_POST as &$value){$value = stripslashes($value);}
                $block->import($_POST);
                if ($_FILES['image']) {
                    require_once('upload/class.upload.php');
                    $handle = new upload($_FILES['image'], 'ru_RU');
                    $folder = Config::findByCode('bloks_folder')->getValue();
                    if ($handle->uploaded) {
                        if (!count($errors)) {
                            $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                            if ($handle->processed) {
                                if ($block->getImage()){ @unlink($_SERVER['DOCUMENT_ROOT'].$block->getImage());}
                                
                                $block->setImage($folder . $handle->file_dst_name);
					if($_POST['block'] != 6) {$block->setImageUk($folder . $handle->file_dst_name);}
					if($_POST['block'] == 6 and !@$_FILES['image_uk']) {$block->setImageUk($folder . $handle->file_dst_name);}
                                $handle->clean();
                            }
                        }
                    }
                }
		if($_POST['block'] == 6){
			if ($_FILES['image_uk']) {
                    require_once('upload/class.upload.php');
                    $handle = new upload($_FILES['image_uk'], 'ru_RU');
                    $folder = Config::findByCode('bloks_folder')->getValue();
                    if ($handle->uploaded) {
                        if (!count($errors)) {
                            $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                            if ($handle->processed) {
                                if ($block->getImage()){@unlink($_SERVER['DOCUMENT_ROOT'].$block->getImageUk());}
                                $block->setImageUk($folder . $handle->file_dst_name);
                                $handle->clean();
                            }
                        }
                    }
                }
	}
				$block->setDate($_POST['date']);
		if($_POST['exitdate']){
			$block->setExitdate($_POST['exitdate']);
				}
                $block->save();
                $this->view->saved = 1;
            }
            $this->view->block = $block;
            echo $this->render('page/blockedit.tpl.php');

        }

    }

    public function tinymcelistAction()
    {
        echo "var tinyMCELinkList = new Array(";
        $links = array();
        foreach ($pages = wsActiveRecord::useStatic('Menu')->findUserPages() as $page) {
            $links[] = "['" . addslashes($page->getName()) . "', '" . $page->getPath() . "']";
        }
        foreach ($pages = wsActiveRecord::useStatic('News')->findActiveNews() as $page) {
            $links[] = "['" . addslashes($page->getTitle()) . "', '" . $page->getPath() . "']";
        }
        echo implode($links, ', ');
        echo ");";
        die();
    }

    public function tinymceimagelistAction()
    {
        echo "var tinyMCEImageList = new Array(";
        $links = array();
        foreach ($images = wsActiveRecord::useStatic('wsFile')->findByFileTypeId(1) as $image) {
            $links[] = "['" . addslashes($image->getName()) . "', '" . $image->getUrl() . "']";
        }
        echo implode($links, ', ');
        echo ");";
        die();
    }

    //------------ IMAGES
    public function imagesAction()
    {
        if($this->get->id){
            $article = wsActiveRecord::useStatic('Shoparticles')->findById($this->get->id);
            if($article->id){
                 $this->view->article =$article;
            }
        }elseif($this->get->date){
            $d = date("Y-m-d H:i:s", strtotime($this->get->date));
           // $all_count = wsActiveRecord::useStatic('Shoparticles')->count(["`utime` <= '{$d}'", "`stock` LIKE '0'"]);
            $sq = "SELECT count(DISTINCT (`ws_articles` . id)) as ctn FROM `ws_articles`"
                    . "INNER JOIN `ws_articles_images` ON `ws_articles_images`.`article_id` = `ws_articles`.`id`"
                    . " WHERE `utime` <= '{$d}' AND `stock` LIKE '0'";
                   // . "GROUP BY `ws_articles`.`id`"
                    //        . " ORDER BY `ws_articles`.`id` ASC";
                   $all_count = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sq)->at(0)->ctn;         
            $sql = "SELECT `ws_articles` . * FROM `ws_articles`"
                    . "INNER JOIN `ws_articles_images` ON `ws_articles_images`.`article_id` = `ws_articles`.`id`"
                    . " WHERE `utime` <= '{$d}' AND `stock` LIKE '0'"
                    . "GROUP BY `ws_articles`.`id`"
                            . " ORDER BY `ws_articles`.`id` DeSC"
                            . " limit 0, 100 ";
           $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
          //  l(SQLLogger::getInstance()->reportBySQL());
           if($articles){
               $this->view->articles = $articles;
               $this->view->all_count = $all_count;
               $this->view->date = $d;
           }
        }elseif($this->post->method == 'dell_img'){
             $d = date("Y-m-d H:i:s", strtotime($this->post->date));
            $sql = "SELECT `ws_articles` . * FROM `ws_articles`"
                     . "INNER JOIN `ws_articles_images` ON `ws_articles_images`.`article_id` = `ws_articles`.`id`"
                    . " WHERE `utime` <= '{$d}' AND `stock` LIKE '0'"
                    . "GROUP BY `ws_articles`.`id`"
                    . " ORDER BY `ws_articles`.`id` ASC"
                            . " LIMIT {$this->post->l1}, {$this->post->l2} ";
           $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
           $arr = [
               '155',
               '360',
               '36',
               '600',
              // 'org'
           ];
           $result = [];
           $result['error'] = 0;
           $result['ok'] = 0;
           $result['l1'] = (int)$this->post->l2;
           $c = (int)($this->post->l2+50);
           if($c > (int)$this->post->all_count){
              $c = (int)$this->post->all_count;
           }
           $result['l2'] = $c;
              // 'error' => 0,
              // 'ok' => 0,
              // 'l1' => (int)$this->post->l2,
              // 'l2' => (int)($this->post->l2+10)
         //  ];
         //   $folder = $_SERVER['DOCUMENT_ROOT'].'/files/';
           if($articles){
               foreach ($articles as $a){
                  // if($a->getImages()->count() > 0){
                       foreach ($a->getImages() as $img){
                          // if(Mimeg::deleteAllsizes($img->image)){
                           //    unlink($_SERVER['DOCUMENT_ROOT'].'/files/org/'.$img->image);
                          // }
                         //  $_SERVER['DOCUMENT_ROOT'].'/files/org/'.$img->image;
                          //  $result['ok'] = $result['ok']+1;
                           
                           foreach ($arr as $v){
                              if(Mimeg::deleteimg($v, $v, $img->image)){
                                  $result['ok'] = $result['ok']+1;
                              }else{
                                   $result['error'] = $result['error']+1;
                              }
                              //unlink($_SERVER['DOCUMENT_ROOT'].'/files/org/'.$img->image);
                           }
                           if(Mimeg::deleteimgorg($img->image)){
                                $result['ok'] = $result['ok']+1;
                              }else{
                                   $result['error'] = $result['error']+1;
                              }
                           $img->destroy();
                       }
                  // }
               }
           }
           die(json_encode($result));
        }
        
       // $this->view->images = wsActiveRecord::useStatic('wsFile')->findByFileTypeId(1);
        echo $this->render('file/images.tpl.php');
    }

    //------------ GALLERY
    public function galleryAction()
    {
        $this->view->images = wsActiveRecord::useStatic('wsFile')->findByFileTypeId(3);
        echo $this->render('file/gallery.tpl.php');
    }


    //------------ PDF
    public function pdfsAction()
    {
        $this->view->pdfs = wsActiveRecord::useStatic('wsFile')->findByFileTypeId(2);
        echo $this->render('file/pdfs.tpl.php');
    }

    public function uploadAction()
    {
        if ($_FILES) {
            $file = new wsFile();
            $file->setFileTypeId($_REQUEST['file_type_id']);
            $file->setCategoryId($_REQUEST['category_id']);

            if ($_REQUEST['file_type_id'] == 2) {
                $extension = array('pdf');
                $this->view->file_type = 2;
            } else {
                $extension = array('jpg', 'jpeg', 'gif', 'png');
                $this->view->file_type = (int)$_REQUEST['file_type_id'];
            }
            $exts = explode('.', @$_FILES['file']['name']);
            $ext = strtolower($exts[count($exts) - 1]);
            if (!in_array($ext, $extension)) {
                $msg = $this->trans->get('Allowed file types:') . ' ' . implode(', ', $extension);
                @header('Content-type: application/json');
                die($msg);
            }
            require_once('upload/class.upload.php');
            //do upload here
            $handle = new upload($_FILES['file'], 'ru_RU');
            $folder = $file->getFileType()->getFolder();
            $errors = array();
            if ($handle->uploaded) {
                switch ($this->view->file_type) {
                    case 3:
                        $width = Config::findByCode('gallery_image_width')->getValue();
                        $height = Config::findByCode('gallery_image_height')->getValue();
                        break;
                    /*
                                             case 4:
                                             $width = Config::findByCode('frontpage_image_width')->getValue();
                                             $height = Config::findByCode('frontpage_image_height')->getValue();
                                             break;
                                             */
                    case 5:
                        $width = Config::findByCode('pages_image_width')->getValue();
                        $height = Config::findByCode('pages_image_height')->getValue();
                        break;
                    default:
                        $width = 0;
                        $height = 0;
                        break;
                }
                /*
                                    if($width && $height) {
                                    if( ($handle->image_src_x != $width && $width) || ($handle->image_src_y != $height && $height)) {
                                    $errors = array($this->trans->get('Image sizes do not match'));
                                    }
                                    }
                                    */

                if (!count($errors)) {
                    $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                    if ($handle->processed) {
                        $file->setName($handle->file_src_name);
                        $file->setFilename($handle->file_dst_name);
                        $file->setHeaderType($handle->file_src_mime);
                        $file->save();
                        $file->resize(Config::findByCode('gallery_small_width')->getValue(), Config::findByCode('gallery_small_height')->getValue(), 0, 'small');
                        $file->resize(Config::findByCode('gallery_big_width')->getValue(), Config::findByCode('gallery_big_height')->getValue(), 0, 'big');
                        $handle->clean();
                        $errors = array('Ok');
                    } else {
                        $errors = $handle->error;
                        $this->view->errors = array($errors);
                    }
                } else {
                    $this->view->errors = $errors;
                }
            } else {
                $errors = array('Error uploading');
            }

            @header('Content-type: application/json');
            die('{"result":"success", "size": "' . implode(', ', $errors) . '"}');
        } else {
            switch ($this->cur_menu->getParameter()) {
                case 'pdf':
                    $this->view->file_type = 2;
                    break;
                case 'image':
                    $this->view->file_type = 1;
                    break;
                case 'gallery':
                    $this->view->file_type = 3;
                    break;
            }
            echo $this->render('file/upload.tpl.php');
        }
    }

    public function upload2Action()
    {
        $errors = array();

        if ($_FILES) {
            $files = array('file_small', 'file_big');
            $fileCount = 0;

            $extension = array('jpg', 'jpeg', 'gif', 'png');
            $this->view->file_type = 1;


            $fileName = md5(uniqid());

            while ($files && !$errors) {
                $fileIndex = array_shift($files);

                if (isset($_FILES[$fileIndex])) {
                    $file = new wsFile();
                    $file->setFileTypeId(1);
                    $file->setCategoryId($_POST['category_id']);
                    $file->setName("{$fileName}_{$fileCount}");
                    $file->setDescription($_POST['descr']);

                    $folder = $file->getFileType()->getFolder();

                    $exts = explode('.', @$_FILES[$fileIndex]['name']);
                    $ext = strtolower($exts[count($exts) - 1]);
                    if (!in_array($ext, $extension))
                        $errors[] = $this->trans->get('Allowed file types:') . ' ' . implode(', ', $extension);

                    $file->setFilename("{$fileName}_{$fileCount}.{$ext}");

                    if (move_uploaded_file($_FILES[$fileIndex]['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $folder . $file->getFilename())) {
                        $res = getimagesize($_SERVER['DOCUMENT_ROOT'] . $folder . $file->getFilename());
                        $file->setHeaderType($res['mime']);
                        $file->save();
                        $fileCount++;
                    } else
                        $errors[] = $this->trans->get('Allowed file types:') . ' ' . implode(', ', $extension);
                }

            }
            if (!$errors)
                $this->_redir('images');
        } else
            $this->view->file_type = 1;

        $this->view->errors = $errors;
        echo $this->render('file/upload-2.tpl.php');
    }

    public function deletefileAction()
    {
        switch ($this->cur_menu->getParameter()) {
            case 'pdf':
                $this->view->file_type = 2;
                break;
            case 'image':
                $this->view->file_type = 1;
                break;
            case 'gallery':
                $this->view->file_type = 3;
                break;
        }

        if ($this->get->getId()) {
            $file = new wsFile($this->get->getId());
            @unlink($_SERVER['DOCUMENT_ROOT'].$file->getFilepath());
            $file->destroy();

            echo $this->render('slugebnoe/static.tpl.php');

        } else {
            switch ($this->view->file_type) {
                case 2:
                    $this->_redir('pdfs');
                    break;
                case 1:
                    $this->_redir('images');
                    break;
                case 3:
                    $this->_redir('gallery');
                    break;
            }
        }
    }

    //-------------------------------------------------------


    public function newsAction()
    {
        $news = wsActiveRecord::useStatic('News')->findAll();
        //set expire status
        foreach ($news as $n) {
            if (!$n->getStatus()){
                if (strtotime($n->getEndDatetime()) < time()) {
                    $n->setStatus(0);
                    $n->save();
                }
            }
        }
        $this->view->news = $news;
	
                    echo $this->render('news/list.tpl.php', 'index.php');
        
    }

    public function onenewAction()
    {
        if ($this->cur_menu->getParameter() == 'delete') {
            if ($this->get->getId()) {
                $n = new News($this->get->getId());
                $n->destroy();
            } else {
                $this->_redir('news');
            }
            echo $this->render('slugebnoe/static.tpl.php');
        } elseif ($this->cur_menu->getParameter() == 'edit') {
            $n = new News($this->get->getId());
            if (count($_POST)) {
                $errors = array();

                if (!@$_POST['title'])
                    $errors[] = $this->trans->get('Please fill in title');
				if(!@$_POST['start_datetime'])
					$errors[] = $this->trans->get('Please fill in date');
                if(!@$_POST['end_datetime'])
					$errors[] = $this->trans->get('Please fill in date');
                if(!@$_POST['intro'])
                    $errors[] = $this->trans->get('Please fill in intro');

                if ($_POST['start_datetime'])
                    @$_POST['start_datetime'] = $this->_formatDT(@$_POST['start_datetime'] . ' 00:00:00');
					
				if ($_POST['end_datetime'])
                    @$_POST['end_datetime'] = $this->_formatDT(@$_POST['end_datetime'] . ' 23:59:59');

                if (!isset($_POST['status'])){
						$n->setStatus((int)$_POST['status']);
					}else{
						$n->setStatus(3);
					}
					
                $n->import($_POST);

                if (@$_FILES['image']) {
                    require_once('upload/class.upload.php');
                    $handle = new upload($_FILES['image'], 'ru_RU');
                    $folder = Config::findByCode('news_folder')->getValue();
                    if ($handle->uploaded) {
                        $width = Config::findByCode('news_image_width')->getValue();
                        $height = Config::findByCode('news_image_height')->getValue();
                        if (($handle->image_src_x != $width && $width) || ($handle->image_src_y != $height && $height)) {
                            $errors = array($this->trans->get('Image sizes do not match'));
                        }
                        if (!count($errors)) {
                            $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                            if ($handle->processed) {
                                if ($n->getImage())
                                    @unlink($_SERVER['DOCUMENT_ROOT'].$n->getImage());
                                $n->setImage($folder . $handle->file_dst_name);
                                $handle->clean();
                            }
                        }
                    }
                }

                if (!count($errors)) {
                    $n->save();
                    $this->view->saved = 1;
                    //unset($this->view->errors);
                } else {
                    $this->view->errors = $errors;
                }
            }
            $this->view->onenew = $n;
		
        echo $this->render('news/edit.tpl.php');
		
            

        } else
            $this->_redir('news');

    }
	
	public function listnewsAction()
    {
         if(@$this->get->getMetod() == 'close_news'){
		 $new = new NewsViews();
		 $new->setIdNews((int)$this->post->id);
		 $new->setUser($this->user->getId());
		 $new->setFlag(2);
		 $new->setDate(date("Y-m-d H:i:s"));
		 $new->save();
		 die();//view_news
		 }elseif(@$this->get->getMetod() == 'view_news'){
		 $new = new NewsViews();
		 $new->setIdNews((int)$this->post->id);
		 $new->setUser($this->user->getId());
		 $new->setFlag(1);
		 $new->setDate(date("Y-m-d H:i:s"));
		 $new->save();
		 die('ok');
		 }elseif ($news = wsActiveRecord::useStatic('News')->findById($this->get->getId())) {
            $this->cur_menu->page_title = $news->title;
            $this->cur_menu->name = $news->title;
            $this->cur_menu->page_body = $news->content;
            echo $this->render('news/static.tpl.php');
        }else{
            echo $this->render('news/list.admin.tpl.php');
        }
    }

    public function newscleanupAction()
    {
        $news = wsActiveRecord::useStatic('News')->findByStatus(0);
        foreach ($news as $n) {
            $n->destroy();
        }
        echo $this->render('slugebnoe/static.tpl.php');
    }

    //-------------------------------------------------------

	public function subscribersemailAction()
	{ 
	if($this->post->segment){  
	$this->view->semail = Subscribers::ListSaveSubscriders($this->post->segment);
	 die(json_encode(['send'=>$this->post->segment, 'result' => $this->view->render('mailing/email_v.php')]));
    }elseif($this->post->segment_customer){
        $this->view->semail = Subscribers::ListSaveSubscridersSegment($this->post->segment_customer);
	 die(json_encode(['send'=>$this->post->segment_customer, 'result' => $this->view->render('mailing/email_v.php')]));
    }elseif($this->post->preview == 'view'){
	$j='view';
	$post = wsActiveRecord::useStatic('Emailpost')->findById($this->post->id);
	$content = '<table  style="width:700px;" align="center">
	<tr><td style="color:#383838; padding:0"><p>'.$post->intro.'</p></td></tr>';
        $ar = unserialize($post->articles);
        if($ar){
             $content .= '<tr>
				<td style="padding: 0;padding-top: 40px;">';
            $i = 0;
             foreach ($ar as $item){
                 $article = wsActiveRecord::useStatic('Shoparticles')->findFirst(array('id'=>$item)); //var_dump( $article);
					if(!$article){ continue;}
                                        ++$i;
					if($i==4) { echo "<br>";}
                
				$content .='<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" align="left">
					<tr>				
					<td ';
                                if ($i==2){ $content .='rowspan="2"'; } 
$content .= '>
						<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-bottom: 40px;" rel="<?=$i?>" align="center" >
						<tr>
							<td align="center" style="padding: 0px 16px 0 16px">
							<a href="https://www.red.ua';
$content .=$article->getPath().'" style="color:#333;text-decoration:none;">
								<img src="https://www.red.ua';
$content .=$article->getImagePath('detail').'" width="200"><br>';
								
							
                                                                $price = $article->getPerc();
                                                                $pr = $price['option_price']?$price['option_price']:$price['price'];
                                                               
                                                                $first_price = $article->getFirstPrice();
                                                                if ($first_price != $pr) {
                                                                  $content .= '<span style="text-decoration: line-through;color: #9E9E9E;font-size: 12px;display: block;">'.$first_price.' ₴</span>';
                                                                    
                                                                }
								$content .='<span style="display: inline-block;
    background-color: #e00e36;
    text-align: center;
    padding: 6px 8px;
    color: #fff;
    font-size: 17px;
    font-weight: 400;
    font-family: Arial, Helvetica, sans-serif;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;">  '.$pr.' ₴</span>
							</a>
							</td>
						</tr>
						</table>
					</td>';
					 if ($i==3){ $content .='</tr><tr>'; }
					$content .='</tr>
				</table>';
            }
            $content .='</td>
			</tr>';
        }
	$content .='<tr><td style="color:#383838; padding:0"><p>'.$post->ending.'</p></td></tr>	
	</table>';
           $this->view->content = $content;
	$subject = $post->subject_start?$post->subject_start.' Test, '.$post->subject:$post->subject;
	die(json_encode(['send'=>$j,'result' => $this->render('', 'mailing/template_view.tpl.php'), 'subject'=>$subject]));
        }elseif($this->post->dellete == 'dell'){
	$c = new Emailpost($this->post->id);
         $segment = 0;
	 if ($c->id) { $segment = $c->segment_id; $c->destroy();}		
	$this->view->semail = Subscribers::ListSaveSubscriders($segment);
 die(json_encode(['send'=>$segment, 'result' => $this->view->render('mailing/email_v.php')]));			
	}
	echo $this->render('mailing/subscribersemail.php');
	}
/**
 * Рассылка email, с возможностью подгрузки товара, предпросмотра, тестового email перед отправкой ну сама рассілка на большое количество подписчиков.
 * Организована с использованием ajax, для предотвращения попадания в спам почтового ящика.
 * Проверка на валидность email, ведение логов
 */	
 public function generalmailingAction()
    {
     
	if (isset($this->post->getarticles)) {
                if (isset($this->post->id)) {
        $data = [];
    $articles = wsActiveRecord::useStatic('Shoparticles')->findAll(['category_id' => $this->post->id, 'active' => 'y', 'stock > 0', 'status'=>3],['views'=>'DESC']);
                    if ($articles->count()){
                        foreach ($articles as $article){
                            $data[] = [
                                'id' => $article->getId(),
                                'title' => $article->id. ' - '.$article->getTitle() . " (" . Shoparticles::showPrice($article->getRealPrice()) . " грн)",
                                'img' => $article->getImagePath('listing')
                            ];
                }
                    }
                    $res = [
                        'result' => 'done',
                        'type' => 'articles',
                        'data' => $data
                    ]; 
                }else{
                    $res = ['result' => 'false'];
                }
                die(json_encode($res));
            }elseif($this->post->method == 'preview') {
                if(isset($this->post->brand_id)){
                    $b = implode(",", $this->post->brand_id);
                   // l($this->post);
                   $bl =  wsActiveRecord::useStatic('Brand')->findAll(['id in ('.$b.')']);
                   if($bl){
                        $this->view->brand = $bl;
                   }
                }
            $this->view->post = $this->post;
            //l($this->post->intro);
        if(isset($this->post->s_start) and $this->post->s_start == 1){
            $subject = $this->post->subject_start.', TEST, '.$this->post->subject; 
        }else{
            $subject = $this->post->subject;
        }
        $this->view->name = 'Test';
        $this->view->email = 'test@red.ua';
         
         $this->view->track_open = 'https://www.red.ua/email/image/?photo=test.jpg';
        $this->view->track = '?'
                                . '&utm_source=test_click_'.date('d.m.Y').''
                                . '&utm_medium=email'
                                . '&utm_campaign=TEST'
                                . '&utm_email_track=test';
        
         
        $this->view->unsubscribe = ''
                                . '&utm_source=test_unsubscribe_'.date('d.m.Y').''
                                . '&utm_medium=link'
                                . '&utm_campaign=TEST';
        
        die(json_encode(['title' => $subject, 'message'=>$this->view->render('mailing/general-email.tpl.php')])); 
            }elseif ($this->post->method == 'save'){
                $id = false;
                if($this->post->id_post){
                    $id = $this->post->id_post;
                }
                                    $parr = [
                                       'ctime' => date('Y-m-d H:i:s'),
                                        'id_customer_new' => $this->user->getId(),
                                        'segment_id' => $this->post->segment_id,
                                        'subject_start' => isset($this->post->subject_start)?$this->post->subject_start:'',
                                        'subject' => isset($this->post->subject)?$this->post->subject:'',
                                        'intro' => $this->post->intro?$this->post->intro:'',
                                        'ending' => $this->post->ending?$this->post->ending:'',
                                        'articles' => (count($this->post->article_id) > 0)?serialize($this->post->article_id):'',
                                    ];
                die(Subscribers::saveSubscribe($id, $parr));
            }elseif($this->post->method == 'go_test_email'){
                if(isset($this->post->brand_id)){
                    $b = implode(",", $this->post->brand_id);
                   // l($this->post);
                   $bl =  wsActiveRecord::useStatic('Brand')->findAll(['id in ('.$b.')']);
                   if($bl){
                        $this->view->brand = $bl;
                   }
                }
                
            $subject_start = '';
            $subject = $this->post->subject;
	if($this->post->subject_start){
            $subject_start = $this->post->subject_start;
        }
	if (isset($this->post->s_start) and $this->post->s_start == 1){
            $subject = $subject_start.', TEST, '.$subject; 
        }
        
        $copy = 2;                             
	if(isset($this->post->copy) and isset($this->post->copy_email)){
            $copy = $this->post->copy;   
	}
        
        $this->view->post = $this->post;  
        $this->view->name = 'Test';
        $this->view->email = $this->post->test_email;
        $this->view->track_open = 'https://www.red.ua/email/image/?photo=test.jpg';
        $this->view->track = '?'
                                . '&utm_source=test_click_'.date('d.m.Y').''
                                . '&utm_medium=email'
                                . '&utm_campaign=TEST'
                                . '&utm_email_track=test';
        
         
        $this->view->unsubscribe = ''
                                . '&utm_source=test_unsubscribe_'.date('d.m.Y').''
                                . '&utm_medium=link'
                                . '&utm_campaign=TEST';
        
        SendMail::getInstance()->sendSubEmail($this->post->test_email, 'Testing', $subject, $this->view->render('mailing/general-email.tpl.php'));
	//SendMail::getInstance()->sendSubEmail($_POST['test_email'], 'Testing', $subject, $msg, '','','', '', $copy, 'management@red.ua', 'Ирина');
            die(json_encode(array('status' => 'send', 'from' => $this->post->test_email)));
    }elseif($this->post->method == 'go_send_email') {
        $s_track = '';
        if($this->post->track){
            $s_track = $this->post->track;
        }
        
        $cnt = 0;
	$error = 0;
	$subject_start = '';
        $subject = $this->post->subject;
        
	if($this->post->subject_start){
            $subject_start = $this->post->subject_start;
        }
	if($this->post->from_mail == 0){
            $s_track = base64_encode(date('Y-m-d H:i:s')); //'MjAyMC0wNC0xNiAwODo1MDoyNg=='
            
           $opt = wsActiveRecord::useStatic('Shoparticlesoption')->findById(256); 
         $opt->setEmail($s_track);
         $opt->save();
            
				if($this->post->id_post){
				$s = new Emailpost($this->post->id_post);
				if($s->segment_id == $this->post->segment_id){
                                $s->setSubject($subject);
                                if($this->post->intro){
                                    $s->setIntro($this->post->intro);
                                }
                                if($this->post->ending){
                                    $s->setEnding($this->post->ending);
                                }
                                if(count($this->post->article_id) > 0){
                                    $s->setArticles(serialize($this->post->article_id));
                                }
				$s->setGo(date('Y-m-d H:i:s'));
                                $s->setCountGo($this->post->all_count);
                                $s->setTrack($s_track);
				$s->setIdCustomerGo($this->user->getId());
				$s->save();
                                }else{
                                    $parr = [
                                        'ctime' => date('Y-m-d H:i:s'),
                                        'go' => date('Y-m-d H:i:s'),
                                        'id_customer_new' => $this->user->getId(),
                                        'id_customer_go' => $this->user->getId(),
                                        'segment_id' => $this->post->segment_id,
                                        'subject_start' => $subject_start,
                                        'subject' => $subject,
                                        'intro' => $this->post->intro?$this->post->intro:'',
                                        'ending' => $this->post->ending?$this->post->ending:'',
                                        'articles' => (count($this->post->article_id) > 0)?serialize($this->post->article_id):'',
                                        'track' => $s_track,
                                        'count_go' => $this->post->all_count
                                    ];
				Subscribers::saveSubscribe(false, $parr);
                                }
				}else{
                                    $parr = [
                                        'ctime' => date('Y-m-d H:i:s'),
                                        'go' => date('Y-m-d H:i:s'),
                                        'id_customer_new' => $this->user->getId(),
                                        'id_customer_go' => $this->user->getId(),
                                        'segment_id' => $this->post->segment_id,
                                        'subject_start' => $subject_start,
                                        'subject' => $subject,
                                        'intro' => $this->post->intro?$this->post->intro:'',
                                        'ending' => $this->post->ending?$this->post->ending:'',
                                        'articles' => (count($this->post->article_id) > 0)?serialize($this->post->article_id):'',
                                        'track' => $s_track,
                                        'count_go' => $this->post->all_count
                                    ];
				Subscribers::saveSubscribe(false, $parr);
				}
		}
    $this->view->post = $this->post;        
    $count = $this->post->count;
    $emails = [];
    if(isset($this->post->brand_id)){
                    $b = implode(",", $this->post->brand_id);
                   // l($this->post);
                   $bl =  wsActiveRecord::useStatic('Brand')->findAll(['id in ('.$b.')']);
                   if($bl){
                        $this->view->brand = $bl;
                   }
                }
foreach (wsActiveRecord::useStatic('Subscriber')->findAll(['active' => 1, 'segment_id' => $this->post->segment_id ], ['email' => 'ASC'], [$this->post->from_mail, $count]) as $sub){  
    if (isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){
		
        $subject_new = $subject;
                
        if(isset( $this->post->s_start) and  $this->post->s_start == 1){
		$subject_new = $subject_start.', '.$sub->getName().', '.$subject;
        }
                    
                    $track = $sub->segment->track;
                    $this->view->track_open = 'https://www.red.ua/email/image/?photo='.$s_track.'.jpg';
			$this->view->openimg = ''
                                . 'https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1'
                                . '&cid='.$sub->getId().''
                                . '&t=event'
                                . '&el='.$sub->getId().''
                                . '&cs='.strtolower($track).'_open_'.date('d.m.Y').''
                                . '&cm=open'
                                . '&cn='.$track;
                        
                        $this->view->track = '?'
                                . '&utm_source='.strtolower($track).'_click_'.date('d.m.Y').''
                                . '&utm_medium=email'
                                . '&utm_campaign='.$track.''
                                . '&utm_email_track='.$s_track;
                        
                        $this->view->unsubscribe = ''
                                . '&utm_source='.strtolower($track).'_unsubscribe_'.date('d.m.Y').''
                                . '&utm_medium=link'
                                . '&utm_campaign='.$track;
                       
                        $this->view->name = $sub->getName();
                        $this->view->email = $sub->getEmail();
                        $emails[] = $sub->getEmail();
			SendMail::getInstance()->sendSubEmail($sub->getEmail(), $sub->getName(), $subject_new, $this->view->render('mailing/general-email.tpl.php'));
			$cnt++;
                       // sleep(1);
		}else{
                    $sub->setActive(0);
                    $sub->save();
                    $error++;
			wsLog::add('E-mail error: ' . $sub->getEmail(), 'EMAIL');
		}			 
    }	   
    die(json_encode(array('status' => 'send', 'from' => $this->post->from_mail, 'count' => $count, 'emails' => implode(',',$emails), 'cnt'=>$cnt, 'error'=>$error, 'track'=>$s_track))); 
        }
        
        if($this->get->id){ $this->view->pemail = wsActiveRecord::useStatic('Emailpost')->findById($this->get->id);}
        
            echo $this->render('mailing/general.tpl.php', 'index.php');
    }
	

	/**
         * sms рассылка
         */
	public function smsmailingAction()
                {
	$this->view->post = (object)$_POST;
		
		if (count($_POST)) {
			if($this->post->method == 'send_sms_order'){
				//$orders = explode(',',$this->post->orders);
				$orders = wsActiveRecord::useStatic('Shoporders')->findAll(['id in('.$this->post->orders.')']);
				$res = [];
				$message = (string)$this->post->message;
				if($orders){
					require_once('alphasms/smsclient.class.php');
		
			$sms = new SMSClient(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue(), Config::findByCode('sms_key')->getValue());
			
				foreach($orders as $r){
					$order = $r->id;
					$phone = Number::clearPhone($r->getTelephone());
					$message = str_replace('order', $order, $message);
			$id = $sms->sendSMS(Config::findByCode('sms_alphaname')->getValue(), $phone, $message);
			if($sms->hasErrors()){
                            $res[$order] = $sms->getErrors();
			}else{ 
			$res[$order] = [
			'mes' => $message,
			'id' => $id,
			'status' => $sms->receiveSMS($id),
			'response' => $sms->getResponse(),
			'phone' => $phone
			];	 
				}
			}
				}
				
				die(json_encode(array('result'=>$res, 'message'=>$message)));
			}elseif($this->post->type){
				$res = false;
				$message = '';
				switch($this->post->type){
					case 'order': $res = true; 
					 $this->view->status = wsActiveRecord::useStatic('Shoporderstatuses')->findAll(array('active' => 1));
					$message = $this->view->render('mailing/sms/order.php'); break; 
					case 'customer': $res = true; $message = $this->view->render('mailing/sms/customer.php'); break; 
					default: $res = false;
				}
				die(json_encode(array('result'=>$res, 'message'=>$message)));
			}
			
		$cnt=0;
		$errors = array();

        if (empty($this->post->subject)) {$errors[] = 'Пожалуйста, вваедите сообщение!';}
		if($this->post->balance){
		require_once('alphasms/smsclient.class.php');
		$sms = new SMSClient(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue(), Config::findByCode('sms_key')->getValue());
		$balance = (int)$sms->getBalance();
		die(json_encode(array('status' => 'send', 'ms'=>'Баланс AlphaSMS '.$balance.' грн.')));
		}elseif(!count($errors)){
		if ($this->post->test == 1) {
		
		$subject = $this->post->subject;
		$phone = '+'.$this->post->test_phone;
		if(strlen($phone) >= 10){
		require_once('alphasms/smsclient.class.php');
		$res = array();
			$sms = new SMSClient(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue(), Config::findByCode('sms_key')->getValue());
			
			$id = $sms->sendViber(Config::findByCode('sms_alphaname')->getValue(), $phone, $subject);
			if($sms->hasErrors()){
                            $res[] = $sms->getErrors();
			}else{ 
			$res['id'] = $id;
			$res['status'] = $sms->receiveSMS($id);
			$res['response'] = $sms->getResponse();
			$res['balance'] = $sms->getBalance();
			$res['phone'] = $phone;
			//$balanse = (int)$sms->getBalance();
			//if(true) $this->sendMessageTelegram(404070580, 'Баланс SMS '.$balanse.' грн.');//Yarik
			}	
			//
			
			die(json_encode(array('status' => 'send', 'ms'=>$res, 'sms'=>$id)));
			}else{
			die(json_encode(array('status' => 'error', 'ms'=>'SMS не отправлено!')));
			}
		}elseif($this->post->go == 0) {
		require_once('alphasms/smsclient.class.php');
		
        $count = $this->post->count;
		$phones = '';	
		$subject = $this->post->subject;
		$res = [];
		//$sms = new SMSClient(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue(), Config::findByCode('sms_key')->getValue());
		//if($this->post->orders){
		//	foreach()
		//}
		/*
foreach(wsActiveRecord::useStatic('Customer')->findAll(array('time_zone_id' => 5), array(), array($this->post->from, $count)) as $sub){
	$phone = Number::clearPhone($sub->getPhone1());
	$phones.=$phone.';';
	$cnt++;
		
		$sms = new SMSClient(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue(), Config::findByCode('sms_key')->getValue());
			
	$id = $sms->sendSMS(Config::findByCode('sms_alphaname')->getValue(), $phone, $subject);
	if($sms->hasErrors()){
	$res = $sms->getErrors(); 
	}else{
	$res = $sms->receiveSMS($id);
	}
		wsLog::add('Subscribe SMS to: '.$phone.' - '.$res, 'SMS_' . $res);
       // sleep(1);
                    }*/
    die(json_encode(array('status' => 'send', 'from' => $this->post->from, 'count' => $count, 'phone' => $phones)));		
	}
	//$this->view->saved = $cnt;
	}else{
	 $this->view->errors = $errors;
	}
	}
	echo $this->render('mailing/sms.mailing.tpl.php');
	}
	//

	/**
         * Шифрование строки в base64_encode
         * @param type $unencoded - строка
         * @param type $key ключ для шифрования
         * @return string
         */
	public function encode($unencoded,$key){//Шифруем
$string=base64_encode($unencoded);//Переводим в base64

$arr=array();//Это массив
$x=0;
while ($x++< strlen($string)) {//Цикл
$arr[$x-1] = md5(md5($key.$string[$x-1]).$key);//Почти чистый md5
$newstr = $newstr.$arr[$x-1][3].$arr[$x-1][6].$arr[$x-1][1].$arr[$x-1][2];//Склеиваем символы
}
return $newstr;//Вертаем строку
}

//-------USERS
    public function usersAction()
    {
	  $data = array();
	   $sortby = 'id';
        $direc = 'DESC';
		$count = 50;
       /* if($this->get->ajax == 'remove_ban'){
            if(count($this->post->id)){
                foreach($this->post->id as $id) {
                    $ban_c = new Customer((int)$id);
                    if ($ban_c->getId()) {
                        $ban_c->setCustomerStatusId(1);
                        $ban_c->save();
                    }
                }
            }
            die();
        }*/
        if ($this->get) {
		
            $dt = $this->get;
			if($dt->ban == 3) {
			$count = 1000;
			  $sortby = 'ban_date';
			  $data[] = 'c.bloсk_np_n = 1';
			}
			if($dt->ban == 2) {
			$count = 100;
			  $sortby = 'ban_date';
			  $data[] = 'c.block_cur = 1';
			}
			if($dt->ban == 4) { 
			$count = 100;
			  $sortby = 'ban_date';
			  $data[] = 'c.block_m = 1';
			}
			if($dt->ban == 5) { 
			$count = 100;
			  $sortby = 'ban_date';
			  $data[] = 'c.block_quick = 1';
			}
                        if($dt->ban == 6) { 
			$count = 100;
			  $sortby = 'ban_date';
			  $data[] = 'c.bloсk_justin = 1';
			}
			if ($dt->ban == 1) {
			$count = 100;
              $data[] = 'c.customer_status_id =2 ';
            }
			if ($dt->id != '') {
              $data[] = 'c.id = ' . $dt->id;
            }
            if (strlen($dt->cart) > 0) {
              $data[] = 'c.username LIKE "%' . $dt->cart . '%"';
            }
            if (strlen($dt->name) > 0) {
                $data[] = 'c.first_name LIKE "%' . $dt->name . '%" OR c.middle_name LIKE "%' . $dt->name . '%"';
            }
            if (strlen($dt->phone) > 0) {
                $data[] = 'c.phone1 LIKE "%' . $dt->phone . '%"';
            }
           if (strlen($dt->email) > 0) {
               $data[] = 'c.email LIKE "%' . $dt->email . '%" or c.temp_email LIKE "%' . $dt->email . '%" or c.username LIKE "%' . $dt->email . '%"';
            }
            if (strlen($dt->orders) > 0) {
                $mas = explode(',', $dt->orders);
                $us = array();
                foreach ($mas as $m) {
                    $oprd = new Shoporders((int)$m);
                    $us[] = $oprd->getCustomerId();
                }
            $data[] = 'c.id IN(' . implode(',', $us) . ')';
            }
            if (strlen($dt->sorting) > 0 && strlen($dt->direction) > 0) {
                $sortby = $dt->sorting;
                $direc = $dt->direction;
            }
        }
        $onPage = $count;
       // $page = !empty($this->get->page) && (int)$this->get->page ? (int)$this->get->page : 1;
        $startElement = 0;//($page - 1) * $onPage;
       // $total = wsActiveRecord::useStatic('Customer')->count($data);
       // $this->view->totalPages = ceil($total / $onPage);
        $this->view->count = $count;
        //$this->view->page = $page;
       // $this->view->start = ($page == 1) ? 1 : $onPage * ($page - 1);
        //$this->view->end = $onPage * ($page - 1) + $onPage;

        $where = count($data) ? 'WHERE ' . implode(' ', $data) : '';

        $this->view->subscribers = wsActiveRecord::useStatic('Customer')->findByQuery(
            'SELECT c.*, COUNT(o.id) as order_count
			FROM `ws_customers` as c LEFT JOIN `ws_orders` as o ON c.id = o.customer_id and o.status not in (17,2,5,7)' . $where . ' GROUP BY c.id ORDER BY ' . $sortby . ' ' . $direc . '  LIMIT ' . $startElement . ' , ' . $onPage . ' ');
			

        echo $this->render('user/list.tpl.php'); 

    }

    public function draftAction()
    {
        $btn = $_POST['button'];
        if (isset($btn)) {
            $this->view->subscribers = wsActiveRecord::useStatic('Customer')->findByQuery('SELECT id,first_name,middle_name,email FROM `ws_customers` WHERE drawing = "red2014" ORDER BY RAND() LIMIT 1');

            echo $this->render('user/draft_2.tpl.php');
            $q = "update ws_customers set drawing = null where drawing = 'red2014'";
            wsActiveRecord::query($q);
        } else {
	$this->view->subscribers = wsActiveRecord::useStatic('Shoporders')->findByQuery("
	SELECT SUM(  `ws_orders`.`amount` +  `ws_orders`.`deposit` ) AS summ,  `ws_orders`.`customer_id` ,  `ws_orders`.`email` ,  `ws_orders`.`date_create` ,  `ws_orders`.`name` ,  `ws_orders`.`delivery_type_id` , COUNT(  `ws_orders`.`id` ) AS count
FROM  `ws_orders` 
inner join `ws_customers` ON `ws_orders`.`customer_id` = `ws_customers`.`id`
WHERE  `ws_orders`.`status` = 8 AND (`ws_orders`.amount > 0 or `ws_orders`.deposit > 0)
AND `ws_customers`.`customer_type_id` =1
AND  `ws_orders`.`date_create` >=  '2020-02-05 00:00:00'
AND  `ws_orders`.`date_create` <=  '2020-02-29 23:59:59'
GROUP BY  `ws_orders`.`customer_id` 
ORDER BY  `summ` DESC
LIMIT 50");
            //$this->view->subscribers = wsActiveRecord::useStatic('Customer')->findByQuery('SELECT id,first_name,middle_name,email,drawing FROM `ws_customers` WHERE drawing = "red2014"');

            echo $this->render('user/draft_temp.tpl.php');
        }
    }

    public function userAction()
    {
	
if($this->post->method == 'emailgo'){
                                $sub = new Customer($this->post->id);
				$this->view->email = $sub->getEmail();
			
				$subject = ' подтвердите свой email на сайте RED.UA';
                        //set_time_limit(180);
                        wsLog::add('Sending email to ' . $sub->getEmail(), 'EMAIL');
                        $this->view->email = $sub->getEmail();
                        $this->view->content = '<p style="padding:15px">' 
                                . 'Нажмите на ссылку ниже для подтверждения изменения Вашего Email:<br>'
                                .'<a style="padding: 10px;
    margin: 5px;
    color: white;
    background: red;
    display: block;
    text-align: center;" href="/activeemail/email/'.$sub->getEmail().'/flag/go/">Подтвердить изменение email в интернет магазине red.ua</a><br>'
                                . '</p>';
			$msg = $this->view->render('email/template.tpl.php');
                        
                         EmailLog::add($sub->getFirstName().', '.$subject, $msg, 'user', $sub->getId() ); //сохранение письма отправленного пользователю
			SendMail::getInstance()->sendEmail($sub->getEmail(), $sub->getFirstName(), $sub->getFirstName().', '.$subject, $msg);		
	   
			die(true);
			
}elseif ($this->cur_menu->getParameter() == 'edit') {
            $sub = new Customer($this->get->getId());
            if (isset($_GET['resetpass'])) {
                if ($sub->getId()) {
                    $allowedChars = 'abcdefghijklmnopqrstuvwxyz'
                        . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                        . '0123456789';
                    $allowedCharsLength = strlen($allowedChars);
                    $newPass = '';
                    while (strlen($newPass) < 8){ $newPass .= $allowedChars[rand(0, $allowedCharsLength - 1)]; }
                    
                    $sub->setPassword(md5($newPass)); 
                    $sub->save();
                    
                    if ($_GET['resetpass'] == 'email') {

                        if (strlen($sub->getEmail()) > 4) {
			if(isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){
                            $subject = $this->trans->get('Your new password for red.ua');
                            $this->view->email = $sub->getEmail();
                            $this->view->content =  '<p style="padding: 15px">Логин: ' . $sub->getUsername() . '. ' . $this->trans->get('Your new password for red.ua') . ': ' . $newPass.'</p>';
                            $msg = $this->view->render('email/template.tpl.php');
                            EmailLog::add($subject, $msg, 'user', $sub->getId() ); //сохранение письма отправленного пользователю
				SendMail::getInstance()->sendEmail($sub->getEmail(), $sub->getFirstName(), $subject, $msg);				
                        }

                            $this->view->resetpass = 'email';
                        } else {
                            $this->view->resetpass = 'Error';
                        }


                    }elseif ($_GET['resetpass'] == 'sms') {
                        $phone = Number::clearPhone($sub->getPhone1());
                        require_once('smsclub.class.php');
                        $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                        $sender = Config::findByCode('sms_alphaname')->getValue();
                        $user = $sms->sendSMS($sender, $phone, 'Vash login: ' . $sub->getUsername() . '. Vash novyj password ' . $newPass);
                        wsLog::add('SMS to user: ' . $sms->receiveSMS($user), 'SMS_' . $sms->receiveSMS($user));
                        $this->view->resetpass = 'sms';
                    }
                }
                $this->_redirect('/admin/user/edit/id/' . $sub->getId() . '/&rpass=' . $this->view->resetpass);
            }

            if (count($_POST)) {
              //  l($_POST);
                foreach ($_POST as &$value){$value = stripslashes($value);}
                $errors = [];

                if (!@$_POST['first_name']){$errors[] = $this->trans->get('Please fill in section name');}
                
               // if($sub->getCustomerStatusId() == 1 and $_POST['customer_status_id'] == 2){
		if($sub->getCustomerStatusId() != $_POST['customer_status_id'] and $_POST['customer_status_id'] == 2){
                    $sub->setBanDate(date('Y-m-d H:i:s'));
                    $sub->setBanAdmin($this->user->getId());
                }
		if($sub->getBloсkNpN() != @$_POST['bloсk_np_n']){
				
				if($sub->getBloсkNpN() == 0){
				$info = 'Бан добавлен!';
				$ok = '+';
				CustomerHistory::newCustomerHistory($this->user->getId(), $this->get->getId(), $ok, $info);
				}else{
				$info = 'Бан снят!';
				$of = '-';
				CustomerHistory::newCustomerHistory($this->user->getId(), $this->get->getId(), $of, $info);
				}
				$sub->setBloсkNpN(@$_POST['bloсk_np_n']);
		}
				
		if($sub->getBlockCur()!= @$_POST['block_cur']){
				
				if($sub->getBlockCur() == 0){ 
				$info = 'Бан добавлен!';
				$ok = '+';
				CustomerHistory::newCustomerHistory($this->user->getId(), $this->get->getId(), $ok, $info);
				}else{
				$info = 'Бан снят!';
				$of = '-';
				CustomerHistory::newCustomerHistory($this->user->getId(), $this->get->getId(), $of, $info);
				}
				$sub->setBlockCur(@$_POST['block_cur']);
				}
				
				if($sub->getBlockM()!= @$_POST['block_m']){
				
				if($sub->getBlockM() == 0){
				$info = 'Бан добавлен!';
				$ok = '+';
				CustomerHistory::newCustomerHistory($this->user->getId(), $this->get->getId(), $ok, $info);
				}else{
				$info = 'Бан снят!';
				$of = '-';
				CustomerHistory::newCustomerHistory($this->user->getId(), $this->get->getId(), $of, $info);
				}
				$sub->setBlockM(@$_POST['block_m']);
				}
                                
                                if(isset($_POST['bloсk_justin'])){
				
				if($sub->bloсk_justin == 0){
                                   
				$info = 'Бан добавлен!';
				$ok = '+';
				CustomerHistory::newCustomerHistory($this->user->getId(), $this->get->getId(), $ok, $info);
                                $sub->setBlockJustin(1);
				}
                                }else{
                                  
                                    if($sub->bloсk_justin == 1){
                                        //  l($_POST);
                                        $info = 'Бан снят!';
				$of = '-';
				CustomerHistory::newCustomerHistory($this->user->getId(), $this->get->getId(), $of, $info);
                               // $sub->setBlockJustin(0);
                                $_POST['bloсk_justin'] = 0;
                                    }
                                }
                                
                                
                                
				if($sub->getBlockQuick()!= @$_POST['block_quick']){
				
				if($sub->getBlockQuick() == 0){
				$info = 'Бан заявки добавлен!';
				$ok = '+';
				CustomerHistory::newCustomerHistory($this->user->getId(), $this->get->getId(), $ok, $info);
				}else{
				$info = 'Бан заявки снят!';
				$of = '-';
				CustomerHistory::newCustomerHistory($this->user->getId(), $this->get->getId(), $of, $info);
				}
				$sub->setBlockQuick(@$_POST['block_quick']);
				}
				
				$d =  str_replace(',', '.', @$_POST['deposit']);
                                
                               // $_POST['deposit'] = $d;
                                
                                if(isset($_POST['deposit_edit']) and !empty($_POST['deposit_edit'])){
                                    
                                    $dep = $sub->getDeposit();
                                    $s = $dep + ($_POST['deposit_edit']);
                                    $_POST['deposit'] = $s;
                                    
                                    if($_POST['deposit_edit'] > 0){
                                        $flag = '+';
                                        $text = 'Зачисление депозита';
                                    }else{
                                        $flag = '-';
                                        $text = 'Списание депозита';
                                    }
                                  DepositHistory::newDepositHistory($this->user->getId(), $this->get->getId(), $flag, abs($_POST['deposit_edit']), $_POST['order_dep']);  
                                
                                  if(!isset($_POST['deposit_email'])){
				$this->getSendEmail($this->get->getId(), $text, abs($_POST['deposit_edit']), $_POST['order_dep'], $flag, true); 
				}
                                }
                              
                                
                                
                $sub->import($_POST);
		
		if (@$_FILES['logo']['name']) {
                    $f = pathinfo($_FILES['logo']['name']);
                    $ext = strtolower($f['extension']);
                    if ((int)$_FILES['logo']['size'] == 0) {$errors['logo'] = "Выбирете фотограцию";}
                    if ((int)$_FILES['logo']['size'] > 1000000) {$errors['logo'] = "Размер фотографии не должен привышать 1 mb.";}
                    if ($ext != 'jpeg' and $ext != 'jpg' and $ext != 'png' and $ext != 'gif') {
                        $errors['logo'] = "Неверный формат файлов, загружать можно только файды .jpeg, .jpg, .png, .gif";
                    }
			if (!count($errors['logo'])) {
                        $mdfname = md5(uniqid(rand(), true));
                        require_once('upload/class.upload.php');

                        $handle = new upload($_FILES['logo'], 'ru_RU');
                        $handle->image_resize = true;
                        $handle->image_x = 500;
                        $handle->image_y = 500;
                        $handle->image_ratio_no_zoom_in = true;
                        $handle->file_src_name_body = $mdfname;
                        $handle->file_dst_name_body = $mdfname;
                        $folder = Config::findByCode('admin_logo_folder')->getValue();
                        if ($handle->uploaded) {
                             $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                            if ($handle->processed){
				if ($sub->getLogo()){ @unlink($_SERVER['DOCUMENT_ROOT'].$sub->getLogo());}
                                 $sub->setLogo($folder . $handle->file_dst_name);
                                $handle->clean();
                            } else {
                                $errors['logo'] = "Не удалось загрузить фаил";
                            }

                        } else {
                            $errors['logo'] = "Не удалось загрузить фаил";
                        }
                    }		
                }
						
                
                if ($_POST['pass_p']) {
                    $sub->setPassword(md5($_POST['pass_p']));
                    $this->view->save_pass_p = 1;
                }

               // $curdate = Registry::get('curdate');
                $mas_adres = [];
                if (isset($_POST['index']) and strlen($_POST['index']) >0) {
                    $mas_adres[] = $_POST['index'];
                }
                if (isset($_POST['obl']) and strlen($_POST['obl']) > 0) {
                    $mas_adres[] = $_POST['obl'];
                }
                if (isset($_POST['rayon']) and strlen($_POST['rayon']) > 0) {
                    $mas_adres[] = $_POST['rayon'];
                }
                if (isset($_POST['city']) and strlen($_POST['city']) > 0) {
                    $mas_adres[] = 'г. ' . $_POST['city'];
                }
                if (isset($_POST['street']) and strlen($_POST['street']) > 0) {
                    $mas_adres[] = 'ул. ' . $_POST['street'];
                }
                if (isset($_POST['house']) and strlen($_POST['house']) > 0) {
                    $mas_adres[] = 'д. ' . $_POST['house'];
                }
                if (isset($_POST['flat']) and strlen($_POST['flat']) > 0) {
                    $mas_adres[] = 'кв. ' . $_POST['flat'];
                }
                if (isset($_POST['sklad']) and strlen($_POST['sklad']) > 0) {
                    $mas_adres[] = 'НП ' . $_POST['sklad'];
                }
                $_POST['address'] = implode(', ', $mas_adres);

                if (!count($errors)) {	
                    $sub->setAdress($_POST['address']);
                    $sub->save();
                    $this->view->saved = 1;
                    
                } else {
                    $this->view->errors = $errors;
                }

                $this->view->errors = $errors;
            }
            $this->view->sub = $sub;
            echo $this->render('user/edit.tpl.php');

        }elseif ($this->cur_menu->getParameter() == 'new_order') {
          //  $sub = new Customer($this->get->getId());
            $ord = wsActiveRecord::useStatic('Shoporders')->findFirst(['customer_id'=>$this->get->getId()], ['id' => 'DESC']);
            
            $order = new Shoporders();
            $order->import($ord);
            $order->setId('');
            $order->setStatus(100);
            $order->setSkidka(NULL);
            $order->setAdmin(NULL);
            $order->setDateCreate(date('Y-m-d H:i:s'));
            $order->setNakladna(NULL);
            $order->setDeliveryCost(0);
            $order->setAmount(0);
            $order->setComlpect(0);
            $order->setDeposit(0);
            $order->setBonus(0);
            $order->setDopSumma(0);
            $order->setIsUnitedly(NULL);
            $order->setQuick(0);
            $order->setQuickNumber(0);
            $order->setFromQuick(0);
            $order->save();
            $id = $order->id;
            $this->_redir('shop-orders/edit/id/'.$id.'/');
        } else{
            $this->_redir('users');
        }

    }

//-----

    //------------ SUBSCRIBERS
    public function subscribersAction()
    {
        
        if($this->post->method == 'active'){
             $sub = new Subscriber($this->post->id);
             $sub->setActive($this->post->activ);
             $sub->setConfirmed(date('Y-m-d H:i:s'));
             $sub->save();
            die(json_encode('tut'));
        }
        $data = [];
       // $data[] = 'active = 1';
        if ($_GET) {
            $dt = $this->get;
            if (strlen($dt->email) > 0) {
                $data[] = 'email LIKE "%' . $dt->email . '%"';
            }
            if (strlen($dt->name) > 0) {
                $data[] = 'name LIKE "%' . $dt->name . '%"';
            }
        }
        if ($_POST) {
            if ($_FILES['exel']) {
                $text = '';
                $add_em = array();
                $find = array();
                $error = array();
                ini_set('memory_limit', '2000M');
                set_time_limit(2800);
                $mdfname = md5(uniqid(rand(), true));
                if (is_uploaded_file($_FILES['exel']['tmp_name'])) {
                    $ext = pathinfo($_FILES['exel']['name'], PATHINFO_EXTENSION);
                    if (!$ext) {
                        $res = getimagesize($filename);
                        $ext = image_type_to_extension($res[2], false);
                    }
                    $oldfilename = $_FILES['exel']['tmp_name'];
                    $filename = INPATH . "tmp/{$mdfname}.{$ext}";

                    if (move_uploaded_file($oldfilename, $filename)) {
                        $filename_exel = pathinfo($filename, PATHINFO_BASENAME);

                        if (($handle_f = fopen($filename, "r")) !== FALSE) {

                            $add = 0;
                            while (($data_f = fgetcsv($handle_f, 0, ";")) !== FALSE) {
                                $email = trim(iconv("CP1251", "UTF-8", $data_f[0]));
                                if (!isValidEmail($email)) {
                                    $email = str_replace(',', '.', $email);
                                }
                                if (isValidEmail($email)) {
                                    if (Subscriber::findByEmail($email)) {
                                        $find[] = $email;
                                    } else {
                                        $subscribl = new Subscriber();
                                        $subscribl->setEmail(trim($email));
                                        $subscribl->setConfirmed(date('Y-m-d H:i:s'));
                                        $subscribl->setActive(1);
                                        $subscribl->save();
                                        $add_em[] = $email;
                                        $add++;
                                    }
                                } else {
                                    $error[] = $email;
                                }


                            }
                        }
                        unlink(INPATH . "tmp/" . $filename_exel);

                        $text .= "<br /> Добавлено " . $add . ' emails.<br />';
                        $text .= implode('<br />', $add_em);
                        $text .= "<br /> Найденные:<br />";
                        $text .= implode('<br />', $find);
                        $text .= "<br /> Ошибки:<br />";
                        $text .= implode('<br />', $error);

                    } else {
                        $text .= "Can not upload file";
                    }
                }
                $this->view->text = $text;

            }

        }
        $this->view->subscribers = wsActiveRecord::useStatic('Subscriber')->findAll($data, array('confirmed' => 'DESC'), array(0, 100));

        echo $this->render('subscriber/list.tpl.php');
       //  echo $this->render('mailing/list.tpl.php');
    }

    public function subscriberAction()
    {
        if ($this->cur_menu->getParameter() == 'delete') {
            if ($this->get->getId()) {
                $sub = new Subscriber($this->get->getId());
                $sub->destroy();
            } else {
                $this->_redir('subscribers');
            }
            echo $this->render('slugebnoe/static.tpl.php');

        } elseif ($this->cur_menu->getParameter() == 'edit') {
            

            if (isset($_POST['savepage'])) {
                $sub = new Subscriber($this->get->getId());
               foreach ($_POST as &$value){ $value = stripslashes($value);}
                $errors = [];
                        
               // if (!$_POST['name']){$errors[] = $this->trans->get('Please fill in section name');}
                if (!$_POST['email'] || !$this->isValidEmail($_POST['email'])){$errors[] = $this->trans->get('Please fill in valid email');}
                if (!$sub->getId() && wsActiveRecord::useStatic('Subscriber')->count(array('email' => $_POST['email']))){$errors[] = $this->trans->get('Email is already in DB');}

                if (!count($errors)) {
                    
                $_POST['confirmed'] = date('Y-m-d H:i:s');
                $sub->import($_POST);
                   $sub->save();
               
                    $this->view->saved = 1;
                }
                    $this->view->sub = $sub;
                $this->view->errors = $errors;
                
            }else{
               $this->view->sub = wsActiveRecord::useStatic('Subscriber')->findById($this->get->getId());
            }
           
            echo $this->render('subscriber/edit.tpl.php');

        } else{ $this->_redir('subscribers');}

    }

    //-------------------------------------------------------

    //------------ BACKUP and admin settings
    public function backupAction()
    {
        GLOBAL $db_config;

        if (@$_POST['backup']) {
            $fn = '/tmp/' . $db_config['config']['dbname'] . date("Y-m-d-H-i-s") . '.sql';
            $backupFile = $_SERVER['DOCUMENT_ROOT'] . $fn;
$command = "mysqldump -u {$db_config['config']['username']} --password={$db_config['config']['password']} {$db_config['config']['dbname']}  > $backupFile";
            $createZip = "gzip cvzf $backupFile.gz $backupFile";
            @system($command);
            @exec($createZip);
            @unlink($backupFile);
            //$this->_redir('backup');
            $this->view->saved = 1;
        }

        echo $this->render('config/backup.tpl.php');
    }

    public function configAction()
    {
        if ($this->user->isSuperAdmin()) {
          //  l($_POST);
         //   exit();
            if (isset($_POST['config'])) {
               foreach ($_POST['data'] as $id => $value) {
                    $config = new Config($id);
                    $config->import($value);
                   // $config->setValue(stripslashes($value)); //??
                    $config->save();
                }
                $this->view->saved = 1;
            }elseif(isset($_POST['save_translation'])) {
              //  l($_POST['translations']);
               // exit();
                foreach ($_POST['translations'] as $id => $data) {
                    $translation = new Dictionary($id);
                    $translation->import($data);
                    $translation->save();
                }
                $this->view->saved = 1;
            }elseif(isset($_POST['field'])) {
                foreach ($_POST['fields'] as $id => $data) {
                    $field = new Field($id);
                    if (!$data['code'] && $field->getId()){
                        $field->destroy();
                    }else {
                        $field->setRequired(0);
                        $field->import($data);
                        if ($field->getCode()){ $field->save();}
                    }
                }
                $this->view->saved = 1;
            }elseif(isset($_POST['redirect_save'])){
                $red = new Redirect();
                $red->import($this->post);
                $red->save();
            }elseif(isset($_POST['data'])){
                foreach ($_POST['data'] as $id => $data) {
                    $redirect = new Redirect($id);
                    $redirect->import($data);
                    $redirect->save();
                }
                $this->view->saved = 1;
            }

            $this->view->configs = wsActiveRecord::useStatic('Config')->findAll();
           // $this->view->fields = wsActiveRecord::useStatic('Field')->findAll();
            $this->view->translations = wsActiveRecord::useStatic('Dictionary')->findAll();
             $this->view->redirect = wsActiveRecord::useStatic('Redirect')->findAll();
        }

        echo $this->render('config/settings.tpl.php', 'index.php');
    }

    //------------ PASSWORD
    public function passwordAction()
    {
        if ($_POST) {
            $errors = array();

            if ($this->user->getPassword() != md5($_POST['oldpassword']))
            { $errors[] = $this->trans->get('Old password is incorrect');}

            if ($_POST['newpassword'] != $_POST['newpassword2'])
            {$errors[] = $this->trans->get('New passwords do not match');}

            if (strlen($_POST['newpassword']) < 8)
            { $errors[] = $this->trans->get('Password should be at least 8 symbols');}


            if (!count($errors)) {
                $this->user->setPassword('');
                $this->user->setOpenPassword($_POST['newpassword']);
                $this->user->save();
                $this->_redir('password/save');
            }
            $this->view->errors = $errors;
        }

        echo $this->render('config/password.tpl.php');
    }

    // Action for Hi Kitty
    public function shopcategoriesAction()
    {

        $redir = false;
        if ($_POST and false)
            {
            foreach ($_POST as &$value)
                $value = stripslashes($value);
            if (isset($_POST['category_name']) && $_POST['category_name']) {
                $c = new Shopcategories();
                if ($_POST['active'] == 'on') {
                    $c->setActive(1);
                } else {
                    $c->setActive(0);
                }
                $c->setName($_POST['category_name']);
                $c->setNameUk($_POST['category_name_uk']);
                $c->setDescription($_POST['category_edit_description']);
                $c->setDescriptionUk($_POST['category_edit_description_uk']);
                $c->setParentId($_POST['category_id']);
                $tmp = wsActiveRecord::useStatic('Shopcategories')->findLastSequenceRecord();
                if ($tmp)
                    $c->setSequence($tmp->getSequence() + 10);
                else
                    $c->setSequence(10);
                $c->save();
                $redir = true;
            }
            
            
            if (isset($_POST['category_edit_name']) && $_POST['category_edit_name'] && $this->get->getId()) {
                $c = new Shopcategories($this->get->getId());
                if ($c && $c->getId()) {
                    if ($_POST['active'] == 'on') {
                        $c->setActive(1);
                    } else {
                        $c->setActive(0);
                    }
                    $c->setName($_POST['category_edit_name']);
                    $c->setNameUk($_POST['category_edit_name_uk']);
                    $c->setDescription($_POST['category_edit_description']);
                    $c->setDescriptionUk($_POST['category_edit_description_uk']);
                    $c->setUsencaCategory($_POST['ucenka_edit_id']);
                    if ($_POST['category_edit_id'] != $c->getId())
                        $c->setParentId($_POST['category_edit_id']);
                    $c->save();
                    $redir = true;
                }
            }
        }

        if ('edit' == $this->cur_menu->getParameter() && $this->get->getId()) {
            
           if($this->post and isset($this->post->new_cat)){
            $cat = new Shopcategories();
            $cat->setParentId($this->post->parent_id);
            $cat->setName($this->post->category_name);
            $cat->setActive(0);
             $tmp = wsActiveRecord::useStatic('Shopcategories')->findLastSequenceRecord();
                if ($tmp){
                $cat->setSequence($tmp->getSequence() + 10);
                
                }else{
                    $cat->setSequence(10);
                }
                
            $cat->save();
            
            $this->view->category_edit = wsActiveRecord::useStatic('Shopcategories')->findById($this->get->getId());
             //echo $this->render('categories/new-category.php');
            $this->_redir('/shop-categories/edit/id/'.$cat->id);
            exit;
        }else if($this->post and isset($this->post->button_save)){
                $c = new Shopcategories($this->get->getId());
                if ($c && $c->getId()) {
                    
                    $c->import($this->post);
                    
                    if ($this->post->active == 'on') {
                        $c->setActive(1);
                    } else {
                        $c->setActive(0);
                    }
                    $c->save();
                }
                if(!empty($this->post->opis_id)){
               wsActiveRecord::query("DELETE FROM `ws_articles_opis` WHERE `cat` = ".$c->id);
               
                $opis_list = Shoparticlesopis::getOpisArray(implode(',', $this->post->opis_id));
                if($opis_list){
                    foreach ($opis_list as $value) {
                       $o = new Shoparticlesopis();
                       $o->setName($value->name);
                       $o->setText($value->text);
                       $o->setCat($c->id);
                       $o->setSort($value->sort);
                       $o->save();
                    }
                    
                }
                }
   
            }
            
            $this->view->opis_cat =  Shoparticlesopis::getOpisCatId($this->get->getId());
            $this->view->opis_all_cat =  Shoparticlesopis::getDistOpis();
            $this->view->category_edit =  wsActiveRecord::useStatic('Shopcategories')->findById($this->get->getId());
             echo $this->render('categories/categories-edit.tpl.php', 'index.php');
            exit;
        } elseif ('delete' == $this->cur_menu->getParameter() && $this->get->getId()) {
            $c = new Shopcategories($this->get->getId());
            $children = wsActiveRecord::useStatic('Shopcategories')->count(array('parent_id' => $c->getId()));
            if ($c && $c->getId() && !$c->getArticles()->count() && $children == 0) {
                $c->destroy();
                $redir = true;
            }
        } elseif (in_array($this->cur_menu->getParameter(), array('moveup', 'movedown'), true) && $this->get->getId()) {
            $a = new Shopcategories($this->get->getId());
            if ($a && $a->getId()) {
                if ('moveup' == $this->cur_menu->getParameter())
                    $b = wsActiveRecord::useStatic('Shopcategories')->findFirst("parent_id = '{$a->getParentId()}' AND sequence < '{$a->getSequence()}'", array('sequence' => 'DESC'));
                else
                    $b = wsActiveRecord::useStatic('Shopcategories')->findFirst("parent_id = '{$a->getParentId()}' AND sequence > '{$a->getSequence()}'", array('sequence' => 'ASC'));
                if ($b && $b->getId()) {
                    $tmp = $a->getSequence();
                    $a->setSequence($b->getSequence());
                    $b->setSequence($tmp);
                    $a->save();
                    $b->save();
                    $redir = true;
                }
            }
        }

        if ($redir) {$this->_redir('shop-categories');}

        $this->view->categories = wsActiveRecord::useStatic('Shopcategories')->findAll();

        echo $this->render('categories/categories.tpl.php', 'index.php');
    }
    public function footertextcategoryAction(){
         if ('new' == $this->cur_menu->getParameter()){
             if($this->post and isset($this->post->save_cat)){
            $edit = new FooterText();
             $edit->import($this->post);
            $edit->save();
            $this->_redir('footer-text/');
           // $this->_redir('footer-text-category/edit/id/'.$edit->id.'/');
            exit;
        }
         echo $this->render('categories/footertext-edit.tpl.php', 'index.php');
        //   exit(); 
         }elseif ('edit' == $this->cur_menu->getParameter() && $this->get->getId()) {
           $edit = new FooterText((int)$this->get->getId());
            if($this->post and isset($this->post->save_cat)){
              
              //  l($this->post);
                
                if($edit && $edit->getId()){
            $edit->import($this->post);
            $edit->save();
        } 
            }

             $this->view->footer_text = $edit;
             echo $this->render('categories/footertext-edit.tpl.php', 'index.php');
          // exit(); 
        }else{
            $this->view->footer_text = FooterTextAdmin::getList();
         echo $this->render('categories/footertext.tpl.php', 'index.php');
        }
        
        
        
    }

    public function shoparticlesAction()
    {
	if($this->post->method == 'sostav'){
	//$mas = array();
	$cat = wsActiveRecord::useStatic('Shoparticlessostav')->findByQueryArray("SELECT * FROM  `ws_articles_sostav`");
	
	die(json_encode($cat));
	}
	
					/*if ($this->post->update_price) {
						$article = new Shoparticles($this->post->id);
						if ($article) {
							if (trim($this->post->price) != '') {
								$article->setPrice($this->post->price);
							}
							if (trim($this->post->old_price) != '') {
								$article->setOldPrice($this->post->old_price);
							}
							$cat = wsActiveRecord::useStatic('Shopcategories')->findById($article->getCategoryId());
				$article->setDopCatId($cat->getUsencaCategory());
							$admin = $this->website->getCustomer();
							UcenkaHistory::newUcenka($admin->getId(), $article->getId(), $article->getOldPrice(), $article->getPrice());
							//$article->setDopCatId('85');
							$article->save();
						}
						die('ok');
					}*/
        if($this->get->act=='tablesize'){
            if((int)$this->get->id){
                if(count($_POST)){
                    if($_POST['size'] || $_POST['sleeve'] || $_POST['back'] || $_POST['waist'] || $_POST['seam'] || $_POST['hip_ratio'] || $_POST['length']){
                        $newSize = new SizeDescription();
                        $newSize->import($_POST);
                        $newSize->setArticleId((int)$this->get->id);
                        $newSize->save();
                    }
                }
                $html = '';
                $sizeData = wsActiveRecord::useStatic('SizeDescription')->findAll(array('article_id' => (int)$this->get->id));
                foreach($sizeData as $item){
                    $html.="<tr>
                                <td>Удалить</td>
                                <td>{$item->getSize()}</td>
                                <td>{$item->getSleeve()}</td>
                                <td>{$item->getBack()}</td>
                                <td>{$item->getWaist()}</td>
                                <td>{$item->getSeam()}</td>
                                <td>{$item->getHipRatio()}</td>
                                <td>{$item->getLength()}</td>
                           </tr>";
                }
                die(json_encode(array('txt'=>$html)));
            }
            die();
        }
		/*
        $kost = wsActiveRecord::useStatic('Shoparticles')->findByQuery('SELECT ws_articles.*, sum(ws_articles_sizes.count) as suma FROM  ws_articles_sizes
JOIN ws_articles on ws_articles.id = ws_articles_sizes.id_article
GROUP BY ws_articles_sizes.id_article
HAVING sum(ws_articles_sizes.count) <>stock ');

        foreach ($kost as $k) {
            $k->setStock($k->suma);
            $k->save();
        }
		*/
		
        //set_time_limit(0);
        $redir = false;

        $categories = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active = 1 or active = 2'));
        $this->view->categories = $categories;

        if ($categories->count()) {

            if ('edit' == $this->cur_menu->getParameter()) {
                $log_text = '';

                $errors = array();

              //  $cur_category = wsActiveRecord::useStatic('Shopcategories')->findById($this->get->getIdCat());
              //  if (!$cur_category || !$cur_category->getId())
                 //   $cur_category = $categories[0];
//
               // $this->view->cur_category = $cur_category;

                $article = new Shoparticles($this->get->getId());


               // if (!$article || !$article->getId()) {
               //     $article->setCategoryId($cur_category->getId());
				//	 $article->setDataNew(date('Y-m-d'));
                //    $article->setActive('n');//y
              //  }

                $filename_image = false;
		$filename_image_2 = false;
                $filename_pdf = false;

                if ($_FILES) {

                    if ($_FILES['image_file']) {
                       // include_once 'Asido/class.asido.php';
                      //  include_once 'Asido/class.driver.php';
                       // include_once 'Asido/class.driver.gd.php';

                        $mdfname = md5(uniqid(rand(), true));

                        if (is_uploaded_file($_FILES['image_file']['tmp_name'])) {
                            $ext = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
                            if (!$ext) {
                                $res = getimagesize(@$filename);
                                $ext = image_type_to_extension($res[2], false);
                            }
                            $oldfilename = $_FILES['image_file']['tmp_name'];
                            //$filename =  INPATH."files/tmp/{$mdfname}.{$ext}";
                            $filename = INPATH . "files/org/{$mdfname}." . strtolower($ext);
                            if (move_uploaded_file($oldfilename, $filename)) {

                                $filename_image = pathinfo($filename, PATHINFO_BASENAME);
                                $path_to_file = $_SERVER['DOCUMENT_ROOT'] . '/files/org/' . $filename_image;
                                Mimeg::generateAllsizes($path_to_file);

                            } else {
                                $errors[] = $this->trans->get("Can not upload file");
                            }
                        }
                    }
					if ($_FILES['image_file_2']) {

                        $mdfname = md5(uniqid(rand(), true));

                        if (is_uploaded_file($_FILES['image_file_2']['tmp_name'])) {
                            $ext = pathinfo($_FILES['image_file_2']['name'], PATHINFO_EXTENSION);
                            if (!$ext) {
                                $res = getimagesize(@$filename);
                                $ext = image_type_to_extension($res[2], false);
                            }
                            $oldfilename = $_FILES['image_file_2']['tmp_name'];
                            //$filename =  INPATH."files/tmp/{$mdfname}.{$ext}";
                            $filename = INPATH . "files/org/{$mdfname}." . strtolower($ext);
                            if (move_uploaded_file($oldfilename, $filename)) {

                                $filename_image_2 = pathinfo($filename, PATHINFO_BASENAME);
                                $path_to_file = $_SERVER['DOCUMENT_ROOT'] . '/files/org/' . $filename_image_2;
                                Mimeg::generateAllsizes($path_to_file);

                            } else {
                                $errors[] = $this->trans->get("Can not upload file");
                            }
                        }
						
                    }
                }

                $options = array();
               
                if ($_POST) {
                    if (isset($_POST['addsize'])) {
                        $s = new Shoparticlessize();
                        $s->setIdArticle($article->getId());
                        $s->setCount(0);
                        $s->save();
                        $result = array('id' => $s->getId());
                        print json_encode($result);
                        exit();
                    }
                    if (isset($_POST['addimage'])) {
                        $s = new Shoparticlesimage();
                        $s->setArticleId($article->getId());
                        $s->save();
                        $idimage = $s->getId();
                        $size = '';
                        $color = '';
                        foreach (wsActiveRecord::useStatic('Shoparticlescolor')->findAll(array('id' => $idimage)) as $colort) {
                            $color .= '<option value="' . $colort->getId() . '">' . $colort->getName() . '</option>';
                        }
                        $result = array('id' => $idimage, 'color' => $color, 'size' => $size);
                        print json_encode($result);
                        exit();
                    }

                    foreach ($_POST as $key => $value) {

                        $ms = explode('images', $key);
                        if (count($ms) > 1) {
                            if ($_FILES['images_file' . $ms[1]] and strlen($_FILES['images_file' . $ms[1]]['tmp_name']) > 1) {
                                $mdfname = md5(uniqid(rand(), true));
                                if (is_uploaded_file($_FILES['images_file' . $ms[1]]['tmp_name'])) {
                                    $ext = pathinfo($_FILES['images_file' . $ms[1]]['name'], PATHINFO_EXTENSION);
                                    if (!$ext) {
                                        $res = getimagesize($filename);
                                        $ext = image_type_to_extension($res[2], false);
                                    }
                                    $oldfilename = $_FILES['images_file' . $ms[1]]['tmp_name'];
                                    //$filename =  INPATH."files/tmp/{$mdfname}.{$ext}";
                                    $filename = INPATH . "files/org/{$mdfname}." . strtolower($ext);
                                    if (move_uploaded_file($oldfilename, $filename)) {

                                        $filename_image2 = pathinfo($filename, PATHINFO_BASENAME);
                                    } else {
                                        $errors[] = $this->trans->get("Can not upload file");
                                    }
                                }
                                $imageid = $ms[1];
                                $s = new Shoparticlesimage($imageid);
                                $s->setImage($filename_image2);
                                $s->save();
                                $path_to_file = $_SERVER['DOCUMENT_ROOT'] . '/files/org/' . $filename_image2;
                                Mimeg::generateAllsizes($path_to_file);
                                $log_text = 'Загрузка изображения';
                                $log = new Shoparticlelog();
                                $log->setCustomerId($this->user->getId());
                                $log->setUsername($this->user->getUsername());
                                $log->setArticleId($article->getId());
                                $log->setComents($log_text);
                                $log->save();
                            }


                        }

                        $ms = explode('delete_images', $key);
            if (count($ms) > 1) {
            $imageid = $ms[1];
            $s = new Shoparticlesimage($imageid);
			Mimeg::deleteAllsizes($s->getImage());
            $s->destroy();
			
            $log_text = 'Удаление изображения';
            $log = new Shoparticlelog();
            $log->setCustomerId($this->user->getId());
            $log->setUsername($this->user->getUsername());
            $log->setArticleId($article->getId());
            $log->setComents($log_text);
            $log->save();
           } else {
                            $ms = explode('color', $key);
                            if (count($ms) > 1) {
                                $sizeid = $ms[1];
                                $s = new Shoparticlessize($sizeid);
                                $old_count = $s->getCount();
                                $s->setCount((int)$_POST['count' . $sizeid]);
                                $s->setCode($_POST['sarticle' . $sizeid]);
                                $s->setIdSize((int)$_POST['size' . $sizeid]);
                                $s->setIdColor((int)$_POST['color' . $sizeid]);
                                $s->setLocation($_POST['location' . $sizeid]);
                                $s->save();

                                if ($s->getCount() != $old_count) {
                                    $log_text = 'Изменено количество';
                                    $log = new Shoparticlelog();
                                    $log->setCustomerId($this->user->getId());
                                    $log->setUsername($this->user->getUsername());
                                    $log->setArticleId($article->getId());
                                    
									if ($s->size and $s->color) {
                                        $log->setInfo($s->size->getSize() . ' ' . $s->color->getName());
                                    }
                                    if ($old_count < $s->getCount()) {
									
                                        $log->setTypeId(3);
                                        $log->setCount($s->getCount() - $old_count);
                                    }
                                    if ($old_count > $s->getCount()) {
									
                                        $log->setTypeId(2);
                                        $log->setCount($old_count - $s->getCount());
                                    }
									$log->setComents($log_text);
									$log->setCode($s->getCode());
                                    $log->save();
                                   // if ($s->getCount() > $old_count) {
                                       // $article->setDataNew(date('Y-m-d'));
                                    //}
                                }
                            }
                            $ms = explode('delete', $key);
                            if (count($ms) > 1) {
                                $sizeid = $ms[1];
                                $s = new Shoparticlessize($sizeid);
                                $orders = wsActiveRecord::useStatic('Shoporderarticles')->findFirst(array('article_id' => $s->getIdArticle(), 'size' => $s->getIdSize(), 'color' => $s->getIdColor()));
                                if ($orders) $errors[] = 'Товар с таким размером и цветом был куплен.';
                                else {
                                    $log_text = 'Удаление размера и цвета';
                                    $log = new Shoparticlelog();
                                    $log->setCustomerId($this->website->getCustomer()->getId());
                                    $log->setUsername($this->website->getCustomer()->getUsername());
                                    $log->setArticleId($article->getId());
                                    $log->setComents($log_text);
                                    if ($s->size and $s->color) {
                                        $log->setInfo($s->size->getSize() . ' ' . $s->color->getName());
                                    }
                                    $log->setTypeId(2);
                                    $log->setCount($s->getCount());
                                    $log->setCode($s->code);
                                    $log->save();
                                    $s->destroy();
                                }

                            }
                        }
						
						
                        $ms = explode('imcl_', $key);
                        if (count($ms) > 1) {
                            $imid = $ms[1];
                            $imd = new Shoparticlesimage($imid);
                            $imd->setColorId((int)@$_POST['imcl_' . $imid]);
                            $imd->save();
                        }

                    }
                    if ($this->get->getId()) {
                        $articleCount = new Shoparticles($this->get->getId());
                        $count = 0;
                        foreach ($articleCount->sizes as $siz) {
                            $count = $count + $siz->getCount();
                        }
                        $articleCount->setStock($count);
                        $articleCount->save();
                    }

                    if (isset($_POST['button'])) {
                     //   l($this->post->sostav);
                         if(count($this->post->sostav)){
                             l($this->post->sostav);
                     $ss = [];
                     foreach ($this->post->sostav as $s){
                         
                        $ss[$s['name']] = $s['value'];
                     }
                   //  var_dump($ss);
                  //   var_dump((array)unserialize($article->sostav));
                   //  var_dump(array_diff($ss, (array)unserialize($article->sostav)));
                   //  var_dump(array_diff((array)unserialize($article->sostav), $ss));
                   //  exit();
                     
                     if(
                             count(array_diff($ss, (array)unserialize($article->sostav))) > 0
                             or
                             count(array_diff((array)unserialize($article->sostav), $ss)) > 0
                             ){
                     $ss = [];
                     $ss_uk =  [];
                     foreach ($this->post->sostav as $s){
                         
                        $ss[$s['name']] = $s['value'];
                        $ss_uk[$this->trans->translate($s['name'], 'ru', 'uk')] = $s['value'];
                     }
                 
                     
		$article->sostav = serialize((object)$ss);
		$article->sostav_uk = serialize((object)$ss_uk);
              //   l($article->sostav);
                
                }
                unset($_POST['sostav'], $_POST['sostav_uk']);
	}
      //   l($article);
       //  exit();
                      foreach ($_POST as &$value)
                          $value = stripslashes(trim($value));
                      

                        $article->setSkidkaBlock(0);
                        $article->setGetNow(0);

                       if (isset($_POST['active'])){ 
						$article->setActive('y');
						//$article->setCtime(date('Y-m-d H:i:s'));
						//$article->setDataNew(date('Y-m-d'));
						}else{
                         $article->setActive('n');
						}
						
                        unset($_POST['active']);

                        if (isset($_POST['new'])) $article->setNew(1);
                        else $article->setNew(0);
                        unset($_POST['new']);
						
						if(isset($_POST['long_text']) and $_POST['long_text'] != '') $_POST['long_text_uk'] = $this->trans->translate($_POST['long_text'], 'ru', 'uk');
						
		//if(isset($_POST['sostav']) and $_POST['sostav'] != ''){
             //       $_POST['sostav_uk'] = $this->trans->translate($_POST['sostav'], 'ru', 'uk');
             //   }
                   

                        if (isset($_POST['price']))
                            $_POST['price'] = str_replace(',', '.', $_POST['price']);
						if (isset($_POST['min_price'])) 
                            $_POST['min_price'] = str_replace(',', '.', $_POST['min_price']);
							if (isset($_POST['max_skidka']))
                            $_POST['max_skidka'] = str_replace(',', '.', $_POST['max_skidka']);
						
                        $article->import($_POST);
			
                       // l($article);
                    //    exit;
					 
							
							
							
                        if ($filename_image) {
						//var_dump($article->getId());
                            if (!$article->isNew() || $article->getImage())
                                $article->deleteCurImages();
                            $article->setImage($filename_image);
                        } elseif ($article->isNew() && !$article->getImage())
                            $errors[] = $this->trans->get("Пожалуйста виберите основной рисунок");
							
                        if (isset($filename_image2)) {
                            $article->setImage2($filename_image2);
                        }
                        if (!$article->getCategoryId())
                            $errors[] = $this->trans->get("Пожалуйста укажите категорию");
                        if (!isset($_FILES['excel_file'])) {
                            if (!$article->getBrand())
                                $errors[] = $this->trans->get("Пожалуйста укажите бренд");
                            if (!$article->getModel())
                                $errors[] = $this->trans->get("Пожалуйста укажите модель");
                            if (!$article->getPrice())
                                $errors[] = $this->trans->get("Пожалуйста укажите цену");
								if (!$article->getMinPrice())
                                $errors[] = $this->trans->get("Пожалуйста укажите себестоимость");
								if (!$article->getMaxskidka())
                                $errors[] = $this->trans->get("Пожалуйста укажите мак. скидку");
                        }

                        if (isset($_FILES['excel_file'])) {

                            if (is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
                               // $ext = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);
                               // if (!$ext) {
                               //     $errors[] = $this->trans->get("Pdf file is incorrect");
                              //  }
                                $oldfilename_excel = $_FILES['excel_file']['tmp_name'];
                                $filename_excel = INPATH . "files/" . $_FILES['excel_file']['name'];
                                if (move_uploaded_file($oldfilename_excel, $filename_excel)) {
                                   // $filename_pdf = pathinfo($filename_excel, PATHINFO_BASENAME);
                                    //$ifos = $this->importadvertinfo($filename_excel);
                                   $ifos = ParseExcel::importadvertinfo($filename_excel);
                                    if (@$ifos['model'] and @$ifos['price']) {
                                       /* $article->setBrand($ifos['brand']);*/
									   if(@$ifos['nakladna']){ $article->setCode($ifos['nakladna']); } 
                                        $article->setModel($ifos['model']);
										$article->setModelUk($this->trans->translate($ifos['model'], 'ru', 'uk'));
                                        $article->setPrice($ifos['price']); 
										 $article->setMinPrice($ifos['min_price']);
										 $article->setMaxSkidka($ifos['max_skidka']);
                                    } else {
                                        $errors[] = $this->trans->get("Error excel file");
                                    }

                                } else {
                                    $errors[] = $this->trans->get("Can not upload file");
                                }
                            }
                        }
                        //if (!$article->getShortText())
                        //    $errors[] = $this->trans->get("Please fill in short text");
                        if (!$article->getLongText()) 
                            $errors[] = $this->trans->get("Please fill in long text");


                        if ($article->sizes->count() == 0 and $article->getId() != null)
                            $errors[] = "Please fill in size";
                        foreach ($article->sizes as $size) {
                            if ((int)$size->getIdSize() == 0 and $article->getId() != null)
                                $errors[] = "Please fill in size";
                            if ((int)$size->getIdColor() == 0 and $article->getId() != null)
                                $errors[] = "Please fill in color";
                        }
                        $cl = 0;
                        

                        if (!$errors) {
                            if ($brand = wsActiveRecord::useStatic('Brand')->findFirst('name LIKE "' . $article->getBrand() . '"')) {
                                $article->setBrandId($brand->getId());
                            } else {
                                $brand = new Brand();
                                $brand->setName($article->getBrand());
                                $brand->save();
                                $article->setBrandId($brand->getId());
                            }
                            if ($article->isNew()) {
                                $tmp = wsActiveRecord::useStatic('Shoparticles')->findLastSequenceRecord();
                                if ($tmp)
                                    $article->setSequence($tmp->getSequence() + 10);
                                else
                                    $article->setSequence(10);
                                $log_text = 'Новый товар';
                            } else { 
                                $log_text = 'Редактирование товара';
                            }
                            $new = 0;
                            if ((int)$article->getId() == 0) {
                                $new = 1;
                            }
							
		$article->save();
							
							 if ($filename_image_2) {
							$s = new Shoparticlesimage();
								 $s->setArticleId($article->getId());
                                $s->setImage($filename_image_2);
                                $s->save();
                            $article->setImage2($filename_image_2);
							
                        }
                            
                            if ($filename_excel) {

			//$mas = $this->importadvert($filename_excel);
                       $mas = ParseExcel::importadvert($filename_excel);
//var_dump($mas);
//die();
							//$errors_sr = array();
							
							//$i=0;
							//$j=0;
		if(true){
        foreach ($mas as $item) {
		$i=0;
           //$color_size = @$item[6];
            $index = $item['sr'];
			$color = $item['color'];
            $size = $item['size'];
			$count = (int)$item['count'];
			
            $size_find = wsActiveRecord::useStatic('Size')->findFirst(array('size LIKE "'.$size.'"'));
            if (!$size_find) {
                $errors[] = 'ошибка размера "' . $size . '"';
				//$i++;
            }
            $color_find = wsActiveRecord::useStatic('Shoparticlescolor')->findFirst(array('name' => mb_strtolower($color)));
            if (!$color_find) {
               $errors[] = 'ошибка цвета "' . $color . '"';
				//$i++;
            }
			$art = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array("code LIKE  '".$index."' "));
			 if ($art) {
				wsLog::add($index, 'ARTICLE-COPY');
               // $errors_sr[] = 'Товар с штрихкодом '.$index.' уже существует.';
				$errors[] = 'Товар с штрихкодом '.$index.' уже существует. ID = '.$art->id;
				//$j++;
				//$i++;
            }
            if (!count($errors)) {
 $find_article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article->getId(), 'id_size' => $size_find->getId(), 'id_color' => $color_find->getId()));
                if (@$find_article) {
                    $old_count = $find_article->getCount();
                    $find_article->setCount($count);
                    $find_article->setCode($index);
                    $find_article->save();
                    $log_text = 'Изменен размер '.$size.' '.$color;
                    $log = new Shoparticlelog();
                    $log->setCustomerId($this->user->getId());
                    $log->setUsername($this->user->getUsername());
                    $log->setArticleId($article->getId());
                    $log->setComents($log_text);
                    if ($old_count < $count) {
                        $log->setTypeId(3);
                        $log->setCount($count - $old_count);
                    }
                    if ($old_count > $count) {
                        $log->setTypeId(2);
                        $log->setCount($old_count - $count);
                    }
					$log->setCode($index); 
                    $log->save();
                }else{
                    $s = new Shoparticlessize();
                    $s->setIdArticle($article->getId());
                    $s->setCount($count);
                    $s->setIdSize($size_find->getId());
                    $s->setIdColor($color_find->getId());
                    $s->setCode($index);
                    $s->save();
                    $log_text = 'Добавлен размер';
                    $log = new Shoparticlelog();
                    $log->setCustomerId($this->user->getId());
                    $log->setUsername($this->user->getUsername());
                    $log->setArticleId($article->getId());
                    $log->setComents($log_text);
					$log->setInfo($size.' '.$color);
                    $log->setTypeId(3);
                    $log->setCount($count);
					$log->setCode($index); 
                    $log->save();
                }
            }
			
        }
		}else{
		$errors[] = 'ошибка чтения excel файла';
		}
		
if(count($errors) > 0){
 $this->view->errors = $errors;
 $this->view->article = $article;
 //$this->view->sostav = wsActiveRecord::useStatic('Shoparticlessostav')->findAll();
 $ss = wsActiveRecord::findByQueryArray("SELECT distinct (nam) as nam FROM  `ws_articles_sostav`");
                                $sss = [];
                                foreach ($ss as $s){ $sss[] =  $s->nam; }
                                    $this->view->sost = json_encode($sss);
 //$article->destroy();
echo $this->render('shop/article-edit.tpl.php');
return;
}		
                            }

                           // $log = new Shoparticlelog();
                           //// $log->setCustomerId($this->user->getId());
                           // $log->setUsername($this->user->getUsername());
                           // $log->setArticleId($article->getId());
                           // $log->setComents($log_text);
                          //  $log->save();

                            if ($this->post->getOntop()) {
                                $top = wsActiveRecord::useStatic('Shoparticlestop')->findFirst(array('article_id' => $article->getId()));
                                if (!$top) {
                                    $top = new Shoparticlestop();
                                    $top->setArticleId($article->getId());
                                    $top->save();
                                    $top->setSequence($top->getId());
                                    $top->setType(1);
                                    $top->save();
                                }

                            } else {
                                $top = wsActiveRecord::useStatic('Shoparticlestop')->findFirst(array('article_id' => $article->getId()));
                                if ($top) {
                                    $top->destroy();
                                }
                            }
							
                            if ($new == 1) {

                        $count = 0;
                        foreach ($article->sizes as $siz) {
                            $count+= $siz->getCount();
                        }
                        $article->setStock($count);
                        $article->save();
                                //$this->_redir('shop-articles/edit/id/' . $article->getId());
								$this->_redir('shop-articles/id/' . $article->getCategoryId());
                            } else {
                                $this->_redir('shop-articles/id/' . $article->getCategoryId());
                            }
						
                        }
                    }

                }
				
                $this->view->errors = $errors;
                $this->view->article = $article;
	//$this->view->sostav = wsActiveRecord::useStatic('Shoparticlessostav')->findAll();
                $ss = wsActiveRecord::findByQueryArray("SELECT distinct (nam) as nam FROM  `ws_articles_sostav`");
                                $sss = [];
                                foreach ($ss as $s){ $sss[] =  $s->nam; }
                                    $this->view->sost = json_encode($sss);

                echo $this->render('shop/article-edit.tpl.php');
                return;
            }elseif('delete' == $this->cur_menu->getParameter()) {
                $c = new Shoparticles($this->get->getId());
                if ($c && $c->getId()) {
                    $order_a = wsActiveRecord::useStatic('Shoporderarticles')->count(array('article_id' => $c->getId()));
                    if ($order_a == 0) {
                        $log = new Shoparticlelog();
                        $log->setCustomerId($this->user->getId());
                        $log->setUsername($this->user->getUsername());
                        $log->setArticleId($c->getId());
                        $log->setComents('Удаление товара с сайта');
			$log->setTypeId(7);
                        $info = [];
                        if($this->get->getMes()){ $info['message'] = $this->get->getMes(); }
                            foreach ($c->sizes as $a){
                                    $info[$a->code] = $a->count;
                            }
                        $log->setInfo(serialize($info));
                        $log->setCount($c->stock);
                        $log->save();
                        //$cur_category = $c->getCategory();
			@unlink($c->getImage());
			@unlink($c->getImage2());
                        $c->deleteCurImages();
                        $c->destroy();
                    }
                    $this->_redirect($_SERVER['HTTP_REFERER']);
                }
            }
            $order_by = 'ws_articles.ctime';
            $order_by_type = 'DESC';
            if (isset($_GET['sort']) and strlen(@$_GET['sort']) > 0) {
                if ($_GET['sort'] == 'dateplus') {
                    $order_by = 'ws_articles.ctime';
                    $order_by_type = 'DESC';
                }
                if ($_GET['sort'] == 'dateminus') {
                    $order_by = 'ws_articles.ctime';
                    $order_by_type = 'ASC';
                }
                if ($_GET['sort'] == 'priceplus') {
                    $order_by = 'ws_articles.price';
                    $order_by_type = 'DESC';
                }
                if ($_GET['sort'] == 'priceminus') {
                    $order_by = 'ws_articles.price';
                    $order_by_type = 'ASC';
                }
                if ($_GET['sort'] == 'viewsplus') {
                    $order_by = 'ws_articles.views';
                    $order_by_type = 'DESC';
                }
                if ($_GET['sort'] == 'viewsminus') {
                    $order_by = 'ws_articles.views';
                    $order_by_type = 'ASC';
                }
                if ($_GET['sort'] == 'category') {
                    $order_by = 'ws_articles.category_id';
                    $order_by_type = 'ASC';
                }
                if ($_GET['sort'] == 'name') {
                    $order_by = 'ws_articles.model';
                    $order_by_type = 'ASC';
                }
            }
            $data = array();
			$data1 = '';
			//$data[] = 'ws_articles.stock > 0';
			//$data1.= ' ws_articles.stock > 0 ';
          /*  if ((isset($_GET['search']) and strlen(@$_GET['search']) > 0) or ($_GET['from'] and $_GET['to']) and (strtotime($_GET['from']) <= strtotime($_GET['to'])) or ($_GET['brand'] and strlen(@$_GET['brand'] > 0)) or @$_GET['ucenka'] == 1 or @$_GET['issetone'] == 1 or $_GET['nalich'] == 1 or @$_GET['ucenalos'] == 1 or ) {*/
		  if($_GET){
			//$data = array();
			/*if($_GET['brand'] or $_GET['search']){
                $data[] = 'ws_articles.brand LIKE "%' . $_GET['brand'] . '%" AND (ws_articles.model LIKE "%' . $_GET['search'] . '%")';
				if(strlen($data1) > 0){ $data1.=' and ';}
				$data1.='ws_articles.brand LIKE "%' . $_GET['brand'] . '%" AND (ws_articles.model LIKE "%' . $_GET['search'] . '%")';
				}*/
                    if($_GET['brand_id']or $_GET['search']){
                             $data[] = "ws_articles.brand_id = {$_GET['brand_id']} and (ws_articles.model LIKE '%{$_GET['search']}%')";   
                             if(strlen($data1) > 0){ $data1.=" and ";}
                             $data1.="ws_articles.brand_id = {$_GET['brand_id']}  AND (ws_articles.model LIKE '%{$_GET['search']}%')";
                    }
                                
                                
                if ($_GET['from'] and $_GET['to'] and (strlen($_GET['from']) > 0 and strlen($_GET['to']) > 0) and (strtotime($_GET['from']) <= strtotime($_GET['to']))) {
                    $from = date('Y-m-d 00:00:00', strtotime($_GET['from']));
                    $to = date('Y-m-d 23:59:59', strtotime($_GET['to']));
                    $data[] = 'ws_articles.ctime >= "' . $from . '" AND ws_articles.ctime <= "' . $to . '"';
					if(strlen($data1) > 0){ $data1.=' and ';}
					$data1.='ws_articles.ctime >= "' . $from . '" AND ws_articles.ctime <= "' . $to . '"';
                }
				//
				if($_GET['color']){
				$data['ws_articles_sizes.id_color'] = $_GET['color'];
				if(strlen($data1) > 0){ $data1.=' and ';}
				$data1.='ws_articles_sizes.id_color = '.$_GET['color'];
				}
				if($_GET['code']){
				$data[] =' ws_articles.code LIKE "'.$_GET['code'].'" ';
				if(strlen($data1) > 0){ $data1.=' and ';}
				$data1.='ws_articles.code  LIKE "'.$_GET['code'].'" ';
				}
				if($_GET['price']){
				$p0 = $_GET['price'] - 3;
				$p1 = $_GET['price'];
				$p2 = $_GET['price'] +3;
				$data[] = ' (ws_articles.price = '.$_GET['price'].' or ws_articles.old_price = '.$_GET['price'].') ';
				if(strlen($data1) > 0){ $data1.=' and ';}
		$data1.='((ws_articles.price >= '.$p0.' and ws_articles.price <= '.$p2.') or (ws_articles.old_price >= '.$p0.' and ws_articles.old_price <= '.$p2.'))';
				}
				//
				
                if (@$_GET['issetone'] == 1) {
                    $data['ws_articles.stock'] = 1;
				if(strlen($data1) > 0){ $data1.=' and ';}
					$data1.='ws_articles.stock = 1';
                }
				if(@$_GET['nalich'] == 0){  
				//array_splice($data, 'stock > 0', -1);
				$t = 'ws_articles.stock > 0';
				unset($data[array_search($t,$data)]);
				$data[] = 'stock >= 0';
				if(strlen($data1) > 0){ $data1.=' and ';}
				$data1.=' ws_articles.stock >= 0 ';
				}
                if (@$_GET['nalich'] == 2) {
				$t = 'ws_articles.stock > 0';
				unset($data[array_search($t,$data)]);
				$data[] = ' ws_articles.stock = 0 ';
				if(strlen($data1) > 0){ $data1.=' and ';}
				$data1.='ws_articles.stock = 0';
                    //$data['stock'] = 0;
				   //$data[] = 'stock = 0';
                }
                if (@$_GET['nalich'] == 1) {
                    $data[] = 'ws_articles.stock > 0';
					if(strlen($data1) > 0){ $data1.=' and ';}
					$data1.='ws_articles.stock > 0';
                }
				
                if (@$_GET['ucenka'] == 1) {
                    $data[] = 'ws_articles.stock > 0';
					if(strlen($data1) > 0){ $data1.=' and ';}
					$data1.='ws_articles.stock > 1';
					date("Y-m-d H:i:s");
					$data[] = "ws_articles.ctime <= ('".date('Y-m-d 00:00:00')."' - INTERVAL 2 MONTH)";//заменил Ярик
					if(strlen($data1) > 0){ $data1.=' and ';}
					$data1.='ws_articles.ctime <= ("'.date('Y-m-d 00:00:00').'" - INTERVAL 2 MONTH)';
                }
                if (@$_GET['ucenalos'] == 1) {
                    $data[] = 'ws_articles.price < ws_articles.old_price';
					if(strlen($data1) > 0){ $data1.=' and ';}
					$data1.=' ws_articles.price < ws_articles.old_price and ws_articles.price / ws_articles.old_price < 0.68';
                }
				 if (@$_GET['active'] == 1) {
                    $data[] = 'ws_articles.active = "n"';
					if(strlen($data1) > 0){ $data1.=' and ';}
					$data1.='ws_articles.active = "n"';
                }
				 if (@$_GET['proc']) {
                    $data[] = 'ws_articles.ucenka = '.(int)$_GET['proc'];
					if(strlen($data1) > 0){ $data1.=' and ';}
					$data1.='ws_articles.ucenka ='.(int)$_GET['proc'];
                }
				if (@$_GET['sezon']) {
                    $data[] = 'ws_articles.sezon = '.(int)$_GET['sezon'];
					if(strlen($data1) > 0){ $data1.=' and ';}
					$data1.='ws_articles.sezon ='.(int)$_GET['sezon'];
                }
				 if (@$_GET['status']) {
                    $data[] = 'ws_articles.status = '.$_GET['status'];
					if(strlen($data1) > 0){ $data1.=' and ';}
					$data1.='ws_articles.status = '.$_GET['status'];
                }else{
				$data[] = 'ws_articles.status != 1';
					if(strlen($data1) > 0){ $data1.=' and ';}
					$data1.='ws_articles.status != 1';
				}
            }else{
			$data[] = 'ws_articles.stock > 0 and ws_articles.status !=1';
			$data1.= ' ws_articles.stock > 0 and ws_articles.status !=1';
			}
            if ($this->get->getId()) {
                if (isset($_GET['whith_kids']) and @$_GET['whith_kids'] == 1) {
                    $cur_category = wsActiveRecord::useStatic('Shopcategories')->findById($this->get->getId());
                    if (!$cur_category || !$cur_category->getId())
                        $cur_category = $categories[0];
                    $this->view->cur_category = $cur_category;
                    if ($cur_category->getId()) {
                        $kids = $cur_category->getKidsIds();
                        $kids[] = $cur_category->getId();
                        $data [] = 'ws_articles.category_id in(' . implode(',', $kids) . ')';
						if(strlen($data1) > 0){ $data1.=' and ';}
						$data1.='ws_articles.category_id in(' . implode(',', $kids) . ')';
                    } else {
                    }

                } else {
                    $data['ws_articles.category_id'] = (int)$this->get->getId();
					if(strlen($data1) > 0){ $data1.=' and ';}
					$data1.='ws_articles.category_id = '.(int)$this->get->getId();
                    $cur_category = wsActiveRecord::useStatic('Shopcategories')->findById($this->get->getId());
                    if (!$cur_category || !$cur_category->getId())
                        $cur_category = $categories[0];

                    $this->view->cur_category = $cur_category;
                }
            }

            if (in_array($this->cur_menu->getParameter(), array('moveup', 'movedown'), true) && $this->get->getId()) {
                $a = new Shoparticles($this->get->getId());
                if ($a && $a->getId()) {
                    if ('moveup' == $this->cur_menu->getParameter())
                        $b = wsActiveRecord::useStatic('Shoparticles')->findFirst("category_id = '{$a->getCategoryId()}' AND sequence > '{$a->getSequence()}'", array('sequence' => 'ASC'));
                    else
                        $b = wsActiveRecord::useStatic('Shoparticles')->findFirst("category_id = '{$a->getCategoryId()}' AND sequence < '{$a->getSequence()}'", array('sequence' => 'DESC'));
                    if ($b && $b->getId()) {
                        $tmp = $a->getSequence();
                        $a->setSequence($b->getSequence());
                        $b->setSequence($tmp);
                        $a->save();
                        $b->save();
                        $cur_category = $a->getCategory();
                        $redir = true;
                    }
                }
            }

            $onPage = 50;
			
            $page = !empty($this->get->page) && (int)$this->get->page ? (int)$this->get->page : 1;
            $startElement = ($page - 1) * $onPage;
			if(isset($_GET['go'])){
			$sql='SELECT COUNT( DISTINCT ws_articles.id ) AS ctn FROM ws_articles INNER JOIN ws_articles_sizes ON ws_articles.id = ws_articles_sizes.id_article WHERE   '.$data1.' ';
			//var_dump($sql);
			$total =  wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
			}else{
            $total = 1000;
			}
			
			if (@$_GET['proc'])  $onPage = $total;
			
            $this->view->totalPages = ceil($total / $onPage);
            $this->view->count = $total;
            $this->view->page = $page;
            $this->view->start = ($page == 1) ? 1 : $onPage * ($page - 1);
            $this->view->end = $onPage * ($page - 1) + $onPage;


            if (isset($_GET['search_artikul']) and strlen(@$_GET['search_artikul']) > 0) {
                $articles = wsActiveRecord::useStatic('Shoparticles')->getArticlesByArticul($_GET['search_artikul']);
            } else {
			
			$sql='SELECT ws_articles.*, ws_articles_sizes.id_article, ws_articles_sizes.id_color, ws_articles_sizes.id_size from ws_articles inner join ws_articles_sizes on  ws_articles.id = ws_articles_sizes.id_article
			WHERE  '.$data1.' GROUP BY ws_articles.id order by '.$order_by.' '.$order_by_type.' LIMIT '.$startElement.' , '.$onPage;
			//var_dump($sql);
			$articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql); 
            }

            if ($this->get->getId()) {
                $this->view->brands = wsActiveRecord::useStatic('Shoparticles')->findByQuery('SELECT DISTINCT brand from ws_articles WHERE category_id = ' . (int)$this->get->getId() . ' ORDER BY brand ASC');
            } else {
                $this->view->brands = wsActiveRecord::useStatic('Shoparticles')->findByQuery('SELECT DISTINCT brand from ws_articles ORDER BY brand ASC');
            }

            $this->view->articles = $articles;


            $this->view->order_by = $order_by;
            $this->view->order_by_type = $order_by_type;
            if ($redir) $this->_redir('shop-articles/id/' . @$cur_category->getId());
            //return;
        }
        echo $this->render('shop/articles.tpl.php');
    }

    public function changearticelinfoAction()
    {
        if ($this->post->update_price) {
            $article = new Shoparticles($this->post->id);
            if ($article) {
                if (trim($this->post->price) != '') {
                    $article->setPrice($this->post->price);
                }
                if (trim($this->post->old_price) != '') {
                    $article->setOldPrice($this->post->old_price);
                }
				$cat = wsActiveRecord::useStatic('Shopcategories')->findById($article->getCategoryId());
				$article->setDopCatId($cat->getUsencaCategory());
                $admin = $this->website->getCustomer();
                UcenkaHistory::newUcenka($admin->getId(), $article->getId(), $article->getOldPrice(), $article->getPrice());
				//if ($this->post->update_price)
					//$article->setDopCatId('85');
                $article->save();
            }
            die('ok');
        } else {
            $category_id = (int)$this->post->category_id;
            if ($category_id) {
               // $ids_for_updates = array();

                 $user_id = $this->user->getId();
                 $user_name = $this->user->getUsername();
                 $cat = new Shopcategories($category_id);
                foreach ($_POST as $k => $v) {
                    if (substr($k, 0, 28) == 'articel_for_change_category_'){
                        //$ids_for_updates[] = (int)substr($k, 28, strlen($k));
                        $id = substr($k, 28, strlen($k));
                       
                        $art = new Shoparticles((int)$id);

                      if($art){
                    $log = new Shoparticlelog();
                    $log->setCustomerId($user_id);
                    $log->setUsername($user_name);
                    $log->setArticleId($art->id);
                    $log->setComents('Замена категории');
                    $log->setInfo('С '.$art->category->getRoutez().' на '.$cat->getRoutez());
                    $log->save();
                    
                        $art->setCategoryId($cat->id);
                        $art->save();
                    }
                    }
                }
               // $update_query = 'UPDATE ws_articles SET category_id = ' . $category_id . ' WHERE id IN (' . implode(',', $ids_for_updates) . ')';
               // wsActiveRecord::query($update_query);
            }
			$this->_redirect($_SERVER['HTTP_REFERER']);
           // $this->_redirect('/admin/shop-articles/');
		  // die('ok');
        }
        die('');
    }

    public function activearticleAction(){ // активация/деактивация товара
	if($this->post->add_reminder){
	$id = (int)$this->post->add_reminder;
	if(wsActiveRecord::useStatic('Reminder')->findFirst(array('article_id' =>$id, 'admin_id'=>$this->user->id))){
	die(json_encode(array('title'=>'Уведомление о активации товара', 'message'=>'Вы уже оставили напоминание об этом товаре!')));
	}else{
	$r = new Reminder();
	$r->setArticleId($id);
	$r->setAdminId($this->user->id);
	$r->setFlag(0);
	$r->save();
	die(json_encode(array('title'=>'Уведомление о активации товара', 'message'=>'Напоминание успошно добавлено. После активации этого товара вы получите сообщение в телеграм и сможете его заказать!')));
	}
	}elseif($this->post->code){
	$arts = wsActiveRecord::useStatic('Shoparticles')->findAll(array("code LIKE  '".$this->post->code."'", " active = 'n'", "status" => 2));
	if($arts->count()){
	$ctn = wsActiveRecord::useStatic('Shoparticles')->findAll(array("code LIKE  '".$this->post->code."'", "status"=> 1));
	//if(!$ctn->count()){
            
	$i = 0;
	$sum = 0;
        $mas_brand = [];
            foreach ($arts as $art) {
			if($art->getActive() != 'y'){
                            if(array_key_exists($art->brand_id, $mas_brand)){
                               $mas_brand[$art->brand_id] =  $mas_brand[$art->brand_id]+1;
                            }else{
                                $mas_brand[$art->brand_id] = 1;
                            }
                             
			$this->reminderarticleAction($art->getId());
			$sum += $art->getStock();
                $art->setActive('y');
				$art->setCtime(date('Y-m-d H:i:s'));
				$art->setDataNew(date('Y-m-d'));
				$art->setStatus(3);
                $art->save();
									$log = new Shoparticlelog();
                                    $log->setCustomerId($this->user->getId());
                                    $log->setUsername($this->user->getUsername());
                                    $log->setArticleId($art->getId());
									$log->setTypeId(4);
									$log->setCount($art->getStock());
									$log->setComents("Товар активирован!");
									$log->save();
                                                                        
				$i++;
				}
            }
                if($sum > 0) {
                    $message = "Добавлена накладная №".$this->post->code.".\r\n ".$i." SKU -  ".$sum." единиц.";
                    $ct = $ctn->count();
                    if($ct > 0){
                        $message .= "\r\nНе добавлено {$ct} товара";
                    }

	//print json_encode($this->post->code);
	
	// Telegram::sendMessageTelegram(404070580, $message);//ya
        // Telegram::sendMessageTelegram(404070580, "https://www.red.ua/new/all/?code=".$this->post->code);
         
	// Telegram::sendMessageTelegram(396902554, $message);//Ira
       //  Telegram::sendMessageTelegram(396902554, "https://www.red.ua/new/all/?code=".$this->post->code);
         
       //  if($i > 5){
        // Telegram::sendMessageTelegram(326712054, "https://www.red.ua/new/all/?code=".$this->post->code);//andriy 
        // }
	
	$message = 'Активирован товар с накладной №'.$this->post->code.' Количество '.$sum.' шт.';
        if($ct > 0){
            $message .="<br>Недобавлено {$ct} товара!";
        }
         if(count($mas_brand)){
            foreach ($mas_brand as $k => $value) {
               
            $res = BrandSubscribeCustomerAdmin::getCustomer($k);
            if($res){
                 
                $b = new Brand($k);
                    
                $track = 'EmailSubscribeBrand';
                        $tr = 'https://www.red.ua/new/all/brands-'.$b->name.'?'
                                . '&utm_source='.strtolower($track).'_click_'.date('d.m.Y').''
                                . '&utm_medium=email'
                                . '&utm_campaign='.$track.''
                                . '&utm_email_track='.$b->track;
                        
                        $text = '<h4>Ранее Вы подписались на обновления бренда '.$b->name.' на сайте RED.UA</h4>';
                       $text .= '<p>На сайт добавлено '.$value.' единиц(ы). Бренда '.$b->name.'.</p>';
                         $text .= '<p style="display:block;text-align:center"><a '
                                . 'href="'.$tr.'"
                            style="display: inline-block;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid transparent;
    padding: 10px;
    font-size: 16px;
    line-height: 1.5;
    border-radius: 0;
    background-color: #dc3545;
    border-color: #dc3545;color: white;
    text-decoration: none;
}"
    >Смотреть товары</a></p>';
                       
                        $this->view->content = $text;
                            $msg = $this->view->render('mailing/brand/template.tpl.php');
                            $subject = 'На сайт добавлено '.$value.' единиц(ы). Бренда '.$b->name;
            foreach ($res as $c) {
			if($c->email and $this->isValidEmail($c->email)){
                            EmailLog::add($subject, $msg, 'shop', $c->customer_id ); //сохранение письма отправленного пользователю
                        SendMail::getInstance()->sendEmail($c->email, '', $subject, $msg);
                        }
            }
            }
            } 
         }
        }
			//}else{
			//$message ='Вы не можете активировать эту накладную №'.$this->post->code.'. Еще не добавлено '.$ctn->count().' SKU.';
			//}
			}else{
			$message ='Товаров с накладной №'.$this->post->code.', не найдено либо уже активирован!';
			}
	die(json_encode($message));
	
	}elseif($this->get->id){
	if($this->get->type == 'a'){
	 $article = new Shoparticles($this->get->id);
			$article->setActive('y');
			$article->setCtime(date('Y-m-d H:i:s'));
			$article->setDataNew(date('Y-m-d')); //убрать когда Аня закончит
			$article->setStatus(3);
            $article->save();
				$log = new Shoparticlelog();
                                    $log->setCustomerId($this->user->getId());
                                    $log->setUsername($this->user->getUsername());
                                    $log->setArticleId($article->getId());
									$log->setTypeId(4);
									$log->setCount($article->getStock());
									$log->setComents("Товар активирован!");
									$log->save();
	$result = array('type' => 'success', 'id' => 'd_'.$this->get->id, 'func' =>'d');
	
	}elseif($this->get->type == 'd'){
	$article = new Shoparticles($this->get->id);
			$article->setActive('n');

			$article->setStatus(2);
            $article->save();
									$log = new Shoparticlelog();
                                    $log->setCustomerId($this->user->getId());
                                    $log->setUsername($this->user->getUsername());
                                    $log->setArticleId($article->getId());
									$log->setTypeId(1);
									$log->setCount($article->getStock());
									$log->setComents("Товар деактивирован!");
									$log->save();
	$result = array('type' => 'success', 'id' => 'a_'.$this->get->id, 'func' =>'a');
	
	}else{
	 $result = array('type' => 'error');
	}
	die(json_encode($result));
	}
	

    }
	 public function reminderarticleAction($article_id = false){
	 if($article_id){
	 $customers = wsActiveRecord::useStatic('Reminder')->findAll(array('article_id' =>$article_id, 'flag'=>0));
	 if($customers){
	 $art = wsActiveRecord::useStatic('Shoparticles')->findById($article_id);
	 foreach($customers as $c){
	 Telegram::sendMessageTelegram($c->getCustomer()->getTelegram(), 'Товар'.$art->getTitle().' активирован. Вы можете его заказать по ссылке: red.ua'.$art->getPath());
	 $c->setFlag(1);
	 $c->save();
	 }
	 return true;
	 }
	 }
	 return false;
	 }

    public function shopopeningpageAction()
    {

        $errors = array();
        $msg = array();
        if ($_POST) {
            $count = 0;

            foreach ($_POST as $key => $value) {

                if ((int)$value == 0) {
                    $sequence = str_replace('article', '', $key);
                    if ((int)$sequence != 0) {
                        $a = new Shoparticlestop((int)$sequence);
                        $a->deleteArticleId();
                        $a->deleteSequence();
                        $a->destroy();
                    }

                } else {
                    $sequence = str_replace('article', '', $key);
                    if ((int)$sequence != 0) {
                        $a = new Shoparticlestop((int)$sequence);
                        $a->setArticleId($value);
                        $a->setSequence($sequence);
                        $a->save();
                        $a->setSequence($a->getId());
                        $a->setType(1);
                        $a->save();
                        $count++;
                    } else {
                        $a = new Shoparticlestop();
                        $a->setArticleId($value);
                        $a->save();
                        $a->setSequence($a->getId());
                        $a->setType(1);
                        $a->save();
                        $count++;
                    }

                }
            }

           
            $msg[] = $this->trans->get("Данные сохранены");
        }

        $this->view->categories = wsActiveRecord::useStatic('Shopcategories')->findAll(array(), array('parent_id' => 'ASC', 'name' => 'ASC'));
        $this->view->articles = wsActiveRecord::useStatic('Shoparticlestop')->findAll(array('type' => 1), array(), array('20'));

        $this->view->errors = $errors;
        $this->view->msg = $msg;

        echo $this->render('shop/openingpage.tpl.php');
    }

    public function ordersexcelAction()
    {
        if (!$this->get->max and !$this->get->min) {
            die('error max or min');
        }
        if ((int)$this->get->max == 0 or (int)$this->get->min == 0) {
            die('error max or min');
        }
        if ($this->get->max < $this->get->min) {
            die('error max or min');
        }
        $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id >= ' . (int)$this->get->min . ' AND id <= ' . (int)$this->get->max . ' AND status not in( 2, 17 )'), array(), array());
        $filename = 'orderexel_'.$this->get->min.'_'.$this->get->max;
        $parametr = [];
                 $parametr['header'][0][1] = '№ п/п';
                 $parametr['header'][0][2] = 'Дата заказа';
                 $parametr['header'][0][3] = 'Номер заказа';
                 $parametr['header'][0][4] = 'ФИО';
                 $parametr['header'][0][5] = 'Способ доставки';
                 $parametr['header'][0][6] = 'Сумма с учетом скидки';
                 $parametr['header'][0][7] = 'Статус';
                 
        $i = 1;
        foreach ($orders as $order) {
            if ($order->getId() and $order->getName()) {
                $d = new wsDate($order->getDateCreate());
                $price_skidka = 0;
                if ($order->getArticlesCount() != 0) {
                    $price_skidka = $order->calculateOrderPrice2(false, false);
                }
                $delivery = explode(' ', $order->getDeliveryType()->getName());
                $del = '';
                $sel = '';
                if (count($delivery) > 1){
                    $del = $delivery[0];
                    unset($delivery[0]);
                    $sel = implode(' ', $delivery);
                }else{
                    $del = implode('', $delivery);
                    $sel = implode('', $delivery);
                }
                $name = $order->getMiddleName() . ' ' . $order->getName();
                $parametr['data'][$i][1] = $i;
                 $parametr['data'][$i][2] = $d->getFormattedDateTime();
                  $parametr['data'][$i][3] = $order->getId();
                   $parametr['data'][$i][4] = trim($name);
                    $parametr['data'][$i][5] = $sel;
                     $parametr['data'][$i][6] = $price_skidka;
                      $parametr['data'][$i][7] = $order->getStat()->getName();
                $i++;
            }
        }
        ParseExcel::saveToExcel($filename, [0 => $parametr]);
        exit();
    }

    public function articleexcelAction()
    {
        $page = 1;
        if ($this->get->part) {$page = $this->get->part;}
        $page = $page - 1;
        $filename = 'articles';
        $parametr = [];
        $parametr['header'][0][1] = '№ п/п';
        $parametr['header'][0][2] = 'ID';
        $parametr['header'][0][3] = 'Категория';
        $parametr['header'][0][4] = 'Название';
        $parametr['header'][0][5] = 'Бренд';
        $parametr['header'][0][6] = 'Артикул';
        $parametr['header'][0][7] = 'Старая цена';
        $parametr['header'][0][8] = 'Новая цена';
        $parametr['header'][0][9] = 'Количество';
        $parametr['header'][0][10] = 'По размерам';
        $i = 1;
        ini_set('memory_limit', '1000M');
        set_time_limit(1800);

        foreach (wsActiveRecord::useStatic('Shoparticles')->findAll(array(), array('id' => 'ASC'), array($page * 1000, 1000)) as $article) {
            $text = '';
            $cnt = 0;
            foreach ($article->sizes as $sizes) {
                if ($sizes and $sizes->color) {
                    $text .= $sizes->getCode() . '-' . $sizes->color->getName() . '-' . $sizes->size->getSize() . ": " . $sizes->getCount() . "\n\r";
                    $cnt += $sizes->getCount();
                }
            }
            $parametr['data'][$i][1] = $i;
            $parametr['data'][$i][2] = $article->getId();
            $parametr['data'][$i][3] = $article->category->getRoutez();
            $parametr['data'][$i][4] = $article->getModel();
            $parametr['data'][$i][5] = $article->getBrand();
            $parametr['data'][$i][6] = $article->getCode();
            $parametr['data'][$i][7] = $article->getOldPrice() ? $article->getOldPrice() . ' грн.' : '';
            $parametr['data'][$i][8] = $article->getPrice() . ' грн.';
            $parametr['data'][$i][9] = $cnt;
            $parametr['data'][$i][10] = $text;
            $i++;
        }
        ParseExcel::saveToExcel($filename, [0 => $parametr]);
            exit();
    }


    public function shopordersAction()
    {
        // JSON
        if ($_POST and isset($_POST['edit']))
            {

            foreach ($_POST as $kay => $value) {
                $k = explode('count-', $kay);
                if (count($k) > 1) {
                    $size = (int)$_POST['size-' . $k[1]];
                    $color = (int)$_POST['color-' . $k[1]];
                    $count = (int)$_POST['count-' . $k[1]];
                    if (isset($_POST['edit_count-' . $k[1]])) {
                        if ($size != 0 and $color != 0 and $count != 0) {
                            $order = new Shoporderarticles($k[1]);
                           // Shoporders::canEdit($order->getOrderId());
                            $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $order->getArticleId(), 'id_size' => $order->getSize(), 'id_color' => $order->getColor()));
                            $article->setCount($article->getCount() + $order->getCount());
                            $article->save();
                            $artic = new Shoparticles($order->getArticleId());
                            $artic->setStock($artic->getStock() + $order->getCount());
                            $artic->save();
			if($order->getCount() != $count){
    OrderHistory::newHistory($this->user->getId(), $order->getOrderId(), 'Изменение товара', OrderHistory::getOrderArticle($order->getId(), $size, $color, $count), $order->getArticleId());
    
                                                        }
                            $order->setSize($size);
                            $order->setColor($color);
                            $order->setCount($count);
                            $articlee = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $order->getArticleId(), 'id_size' => $size, 'id_color' => $color));
                            $articlee->setCount($articlee->getCount() - $count);
                            $artic->setStock($artic->getStock() - $count);
                            $artic->save();
                            $order->save();
                            $articlee->save();
                        }
                    }

                }
            }
            $this->_redir('shop-orders/edit/id/' . $this->get->getId());
        }
        
        
        //подключение отключение скидки
        if($this->post->addskidka == 'add_sk' and $this->post->id)
            {
		 $order_art = new Shoporderarticles((int)$this->post->id);
		
                 $opt = new Shoparticlesoption((int)$this->post->option_id);
                 $order_art->setOptionId($opt->id);
                 if($opt->type == 'final'){
                     $summ = $order_art->getPrice()*(1-($opt->value/100));
		 $order_art->setOptionPrice($summ);
                 }else{
                      $summ = $order_art->getPrice()*(1-($opt->value/100));
                    $order_art->setOptionPrice($summ); 
                 }
		 $order_art->save();
                 OrderHistory::newHistory($this->user->getId(), $order_art->order_id, 'Cкидка подключена', $opt->option_text, $order_art->article_id);
		die(true);
                }elseif($this->post->addskidka == 'dell_sk' and $this->post->id)
                    {
                     $order_art = new Shoporderarticles((int)$this->post->id);
                      $order_art->setOptionId(0);
                     $order_art->setOptionPrice(0);
                     $order_art->save();
                      OrderHistory::newHistory($this->user->getId(), $order_art->order_id, 'Cкидка отключена', $opt->option_text, $order_art->article_id);
                    die(true);
                }
                 //подключение отключение скидки
                 
                 
		 //стара форма добавления товара в заказ
    /*    if ($_POST and isset($_POST['Toevoegen']) and isset($_POST['article_id']) and isset($_POST['size_id']) and isset($_POST['color_id'])) {

            $article_id = (int)$_POST['article_id'];
            $size_id = (int)$_POST['size_id'];
            $color_id = (int)$_POST['color_id'];

            if ($article_id != 0 and $size_id != 0 and $color_id != 0) {
                $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article_id, 'id_size' => $size_id, 'id_color' => $color_id));
                $ar = new Shoparticles($article_id);
				$s = Skidki::getActiv($ar->getId());
				$c = Skidki::getActivCat($ar->getCategoryId(), $ar->getDopCatId());
                if ($article->getCount() != 0) {
                    $order = new Shoporderarticles();
                    Shoporders::canEdit($this->get->getId());
                    $order->setOrderId($this->get->getId());
                    $order->setArticleId($article_id);
                    $order->setTitle($ar->getTitle());
                    $order->setCount(1);
                    $order->setPrice($ar->getRealPrice());
			if($s){ $order->setEventSkidka($s->getValue()); $order->setEventId($s->getId()); }
			if($c){ $order->setEventSkidka($c->getValue()); $order->setEventId($c->getId()); }
                    $order->setColor($color_id);
                    $order->setSize($size_id);
                    $order->setArtikul($article->getCode());
                    $article->setCount($article->getCount() - 1);
                    $order->setOldPrice($ar->getOldPrice());
                    $article->save();
                    $order->save();
                    OrderHistory::newHistory($this->user->getId(), $order->getOrderId(), 'Новый товар',
                        OrderHistory::getNewOrderArticle($order->getId()), $order->getArticleId());
                }

            }

            $this->_redir('shop-orders/edit/id/' . $this->get->getId());
        }else
            */
            //стара форма добавления товара в заказ
            
                //добавление в заказ товара по штрихкоду
            if ($_POST and isset($_POST['Toevoegen2']) and isset($_POST['add_article_by_barcode']))
                {

            $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('code' => $_POST['add_article_by_barcode']));
            $article_id = $article->getIdArticle();
            $size_id = $article->getIdSize();
            $color_id = $article->getIdColor();
		

            if ($article_id != 0 and $size_id != 0 and $color_id != 0) {
                $ar = new Shoparticles($article_id);
				//$s = Skidki::getActiv($ar->getId());
				//$c = Skidki::getActivCat($ar->getCategoryId(), $ar->getDopCatId());
			$or = new Shoporders($this->get->getId());
                if ($article->getCount() != 0 && $ar->shop_id == $or->shop_id) { //проверка на наличии и соответствие продавцу
                    $order = new Shoporderarticles();
                    $order->setOrderId($this->get->getId());
                    $order->setArticleId($article_id);
                    $order->setTitle($ar->getTitle());
                    $order->setCount(1);
                    $order->setPrice($ar->getPrice());
					//if($s){ $order->setEventSkidka($s->getValue()); $order->setEventId($s->getId()); }
					//if($c){ $order->setEventSkidka($c->getValue()); $order->setEventId($c->getId()); }
                    $order->setColor($color_id);
                    $order->setSize($size_id);
                    $order->setArtikul($article->getCode());
                        $article->setCount($article->getCount() - 1);
                    $order->setOldPrice($ar->getOldPrice());
                        $article->save();
                    $order->save();
                    OrderHistory::newHistory($this->user->getId(), $order->getOrderId(), 'Новый товар', OrderHistory::getNewOrderArticle($order->getId()), $order->getArticleId());
                }
            }

            $this->_redir('shop-orders/edit/id/' . $this->get->getId());
        }
         //добавление в заказ товара по штрихкоду
	
        //выборка статусов
        $dat = [];
        $dat[] = ' active = 1';
	if($this->user->isPointIssueAdmin()){
	$dat[] = ' id in(100,3,5,7,8,9,15,16,13)';
	}else{
	$dat[] = ' id != 0';
	}
	/*if($this->user->isDeveloperAdmin()){
	$dat[] = ' id in(0,1,3,5,7,8,9,15,16)';
	}*/
	
        $o_stat = wsActiveRecord::useStatic('OrderStatuses')->findAll($dat);
        $mas_os = [];
        foreach ($o_stat as $o) {
            $mas_os[$o->getId()] = $o->getName();
        }
        $this->view->order_status = $mas_os;
        //выборка статусов

        //$this->view->categories = wsActiveRecord::useStatic('Shopcategories')->findAll();

        if ('edit' == $this->cur_menu->getParameter())
            {

            $errors = [];
            $order = wsActiveRecord::useStatic('Shoporders')->findById($this->get->getId());
           /// l($order);
            if($order->id){
            Shoporders::canEdit($order);//проверка на право редактировать заказ
            
            
           /* if (!($order || $order->getId()))
            *  {
                if ($_POST) {
                    $order = new Shoporders();
                    if ((int)$_POST['id'] != 0) {
                        $order->setCustomerId($_POST['id']);
                    }
                    $order->setCompany($_POST['company']);
                    $order->setNakladna($_POST['nakladna']);
                    $order->setName($_POST['name']);
                    $order->setIndex($_POST['index']);
                    $order->setStreet($_POST['street']);
                    $order->setHouse($_POST['house']);
                    $order->setFlat($_POST['flat']);
                    $order->setAddress($_POST['address']);
                    $order->setRayon($_POST['rayon']);
                    $order->setObl($_POST['obl']);
                    $order->setCity($_POST['city']);
                    $order->setTelephone(Number::clearPhone($_POST['phone']));
                    $order->setEmail($_POST['email']);
                    $order->setComments($_POST['coments']);
                    $order->setDeliveryTypeId($_POST['delivery_type_id']);
                    $order->setPaymentMethodId($_POST['payment_method_id']);

                   // $order->setDeliveryCost($_POST['payment_method_id']);
                    
                    
                    if ((int)$_POST['delivery_type_id'] == 0) {
                        $errors[] = 'Выберите способ доставки';
                    
                    }elseif((int)$_POST['delivery_type_id'] == 4) {
                        if (strlen($_POST['index']) < 1) {$errors[] = 'Введите индекс';}
                        if (strlen($_POST['obl']) < 1) {$errors[] = 'Введите область';}
                        if (strlen($_POST['street']) < 1) {$errors[] = 'Введите улицу';}
                        if (strlen($_POST['house']) < 1) {$errors[] = 'Введите дом';}
                        if (strlen($_POST['flat']) < 1) {$errors[] = 'Введите квартиру';}
                    }
                    if ((int)$_POST['payment_method_id'] == 0) {$errors[] = 'Выберите способ оплаты';}
                    

                    if ((int)$_POST['id'] == 0) {
                        $alredy = wsActiveRecord::useStatic('Customer')->findFirst(array('phone1' => $order->getTelephone()));
                        if ($alredy and strlen(Number::clearPhone($_POST['phone'])) > 0) $errors[] = "Пользователь с таким номером телефона уже существует";
                    }
                    if (!count($errors)) {
                        $cost = wsActiveRecord::useStatic('DeliveryPayment')->findFirst(array('delivery_id' => (int)$_POST['delivery_type_id'], 'payment_id' => (int)$_POST['payment_method_id']))->getPrice();
                        $order->setDeliveryCost($cost);
                    }
                    if (strlen($_POST['name']) < 1) {$errors[] = 'Введите имя клиента';}
                    if (strlen($_POST['address']) < 1) {$errors[] = 'Введите адрес клиента';}
                    if (strlen($_POST['email']) > 1) {
                        if (!count($errors)) {
                            $klient = wsActiveRecord::useStatic('Customer')->findFirst(array('username' => $_POST['email']));
                            if ($klient and $klient->getId() != (int)$_POST['id']) {
                                $errors[] = 'Такой Email уже используется, найдите клиента в списке клиентов';
                                
                            }elseif(!isValidEmail($_POST['email'])) {
                                $errors[] = $this->trans->get("Email is invalid");
                                
                            }elseif((int)$_POST['id'] == 0) {
                                $allowedChars = 'abcdefghijklmnopqrstuvwxyz'
                                    . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                                    . '0123456789';
                                $newPass = '';
                                $allowedCharsLength = strlen($allowedChars);
                                while (strlen($newPass) < 8)
                                    $newPass .= $allowedChars[rand(0, $allowedCharsLength - 1)];
                                $customer = new Customer();
                                $customer->setUsername($order->getEmail());
                                $customer->setPassword(md5($newPass));
                                $customer->setCustomerTypeId(1);
                                $customer->setCompanyName($order->getCompany());
                                $customer->setFirstName($order->getName());
                                $customer->setEmail($order->getEmail());
                                $customer->setPhone1($order->getTelephone());
                                $customer->setCity($order->getCity());
                                $customer->setAdress($order->getAddress());
                                $customer->setIndex($order->getIndex());
                                $customer->setObl($order->getObl());
                                $customer->setRayon($order->getRayon());
                                $customer->setStreet($order->getStreet());
                                $customer->setHouse($order->getHouse());
                                $customer->setFlat($order->getFlat());
                                $customer->save();

                                $this->view->login = $order->getEmail();
                                $this->view->pass = $newPass;
                                $admin_name = Config::findByCode('admin_name')->getValue();
                                $do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
                                $msg = $this->render('email/new-customer.tpl.php');
                                $subject = 'Создан акаунт';

                                require_once('nomadmail/nomad_mimemail.inc.php');
                                $mimemail = new nomad_mimemail();
                                $mimemail->debug_status = 'no';
                                $mimemail->set_from($do_not_reply, $admin_name);
                                $mimemail->set_to($order->getEmail(), $order->getName());
                                $mimemail->set_charset('UTF-8');
                                $mimemail->set_subject($subject);
                                $mimemail->set_text(make_plain($msg));
                                $mimemail->set_html($msg);
                                //@$mimemail->send();

                                MailerNew::getInstance()->sendToEmail($order->getEmail(), $order->getName(), $subject, $msg);
                            }
                        }
                    } else {
                        if ((int)$_POST['id'] == 0) {
                            $allowed_chars = '1234567890';
                            $tel = $order->getTelephone();
                            if (!Number::clearPhone($tel))
                                $errors[] = "Введите телефонный номер";
                            for ($i = 0; $i < mb_strlen($tel); $i++) {
                                if (mb_strpos($allowed_chars, mb_strtolower($tel[$i])) === false) {
                                    $errors[] = "В номоре должны быть только числа";
                                }
                            }

                            if (!count($errors)) {
                                $allowedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                                $newPass = '';
                                $allowedCharsLength = strlen($allowedChars);
                                while (strlen($newPass) < 8){$newPass .= $allowedChars[rand(0, $allowedCharsLength - 1)];}
                                $customer = new Customer();
                                $customer->setUsername(Number::clearPhone($order->getTelephone()));
                                $customer->setPassword(md5($newPass));
                                $customer->setCustomerTypeId(1);
                                $customer->setCompanyName($order->getCompany());
                                $customer->setFirstName($order->getName());
                                $customer->setPhone1($order->getTelephone());
                                $customer->setCity($order->getCity());
                                $customer->setAdress($order->getAddress());
                                $customer->setIndex($order->getIndex());
                                $customer->setObl($order->getObl());
                                $customer->setRayon($order->getRayon());
                                $customer->setStreet($order->getStreet());
                                $customer->setHouse($order->getHouse());
                                $customer->setFlat($order->getFlat());
                                $customer->save();
                                $phone = Number::clearPhone($order->getTelephone());
                                include_once('smsclub.class.php');
                                $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                                $sender = Config::findByCode('sms_alphaname')->getValue();
                                $user = $sms->sendSMS($sender, $phone, 'Vy zaregistrirovany na sajte Red.ua. Vash login: '. $phone .'. Vash password: '. $newPass .'.');
                                wsLog::add('SMS to user: ' . $sms->receiveSMS($user), 'SMS_' . $sms->receiveSMS($user));
                            }
                        }
                    }
                    if (!count($errors)) {
                        $order->save();
                        $this->_redir('shop-orders/edit/id/' . $order->getId());
                    }
                }
                $user = 0;
                if (isset($_GET['user'])) {
                    if ((int)$_GET['user'] != 0) {
                        $user = (int)$_GET['user'];
                    }
                }
                if ($user == 0) {
                    $user = new Customer();
                } else {
                    $user = new Customer($user);
                }
                $this->view->errors = $errors;
                $this->view->customer = $user;
                echo $this->render('shop/order-new.tpl.php');
                return;
            }else*/
                

                if ($_POST) {
                    if(isset($_POST['skidka'])){  $order->setSkidka($_POST['skidka']);}
                    if (isset($_POST['box_number'])) { $order->setBoxNumber($_POST['box_number']);}
                    if (isset($_POST['kupon'])) { $order->setKupon($_POST['kupon']); }
                    if (isset($_POST['kupon_price'])) { $order->setKuponPrice($_POST['kupon_price']); }
                    if(isset($_POST['dop_summa'])){
                        if($order->getDopSumma()!= $_POST['dop_summa']){
                            OrderHistory::newHistory($this->user->id, $order->id, 'Смена доп.Суммы', 'доп.сумма сменилась с "'.$order->getDopSumma().'" на "'.$_POST['dop_summa'].'"');
                            $order->setDopSumma($_POST['dop_summa']); 
                        }
                    }
                    if(isset($_POST['comment_dop_summ'])){$order->setCommentDopSumm($_POST['comment_dop_summ']);}
                    
                    $order->save();
                    
                    if (isset($_POST['order_status'])) {  
                        if ((int)$_POST['order_status'] == 13 and strlen($_POST['nakladna']) == 0 and $order->delivery_type_id != 3) {// если нет номера накладной нельзя менять на отправлен УП/НП кроме магазина
                            $this->_redirect($_SERVER['HTTP_REFERER']);
     }                 
    //смена накладной
    if ($order->getNakladna() != $_POST['nakladna']) {
        $order->editNakladna($_POST['nakladna']);              
        }
                       

                          // $order->save();
                           if($order->getStatus() != $_POST['order_status']){
                           if(!$order->editStatus($_POST['order_status'], $this->view, $this->user)){
                               $this->_redirect($_SERVER['HTTP_REFERER']);
                        } // смена статуса
                           }
                        $this->_redirect($_SERVER['HTTP_REFERER']);
                    } elseif (isset($_POST['add_remark']) && isset($_POST['remark'])) {
                        $remark = new Shoporderremarks();
                        $data = [
                            'order_id' => $order->getId(),
                            'date_create' => date("Y-m-d H:i:s"),
                            'remark' => $_POST['remark'],
                            'name' => $this->user->getMiddleName()
                        ];
                        $remark->import($data);
                        $remark->save();
                        $this->_redir('shop-orders/edit/id/' . $order->getId());
                    }
                }

                $this->view->order = $order;
		$this->view->total_amount = wsActiveRecord::useStatic('Shoporders')->findByQuery('
                SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS sum_amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
                WHERE ws_orders.status in(3,4,6,8)
                AND ws_orders.customer_id=' . $order->getCustomerId())->at(0)->getSumAmount();

                echo $this->render('shop/order-edit.tpl.php');
                return;
            }
        }
        elseif ('editquickorder' == $this->cur_menu->getParameter())//обработка быстрой заявки
            {
            $order = wsActiveRecord::useStatic('Shoporders')->findById($this->get->getId());
            if (isset($_POST['add_remark']) && isset($_POST['remark'])) {
                QuickOrder::addRemark($order, $_POST['remark'], $this->user->getMiddleName());// добавление коментария к заказу
                $this->_redir('edit-quick-order/id/' . $order->getId());
            }elseif (isset($_POST['converting_to_order'])) {
		QuickOrder::toOrder($order);// заявка в заказ
                $this->_redir('shop-quick-orders');
            }elseif (isset($_POST['delete_qo'])) {//отмена заявки
                QuickOrder::otmena($order);
                $this->_redir('shop-quick-orders');
            }
            $this->view->order = $order;
              // echo $this->render('shop/order-edit-quick.tpl.php');
               echo $this->render('shop/order-edit-quick.tpl_1.php', 'index.php');
           
            return ;
        }
        elseif ('delete' == $this->cur_menu->getParameter())//удаление заказа
            {

            $c = new Shoporders($this->get->getId());
            if ($c && $c->getId()) {
                /*    $c->destroy();*/
                $this->_redir('shop-orders');
            }

        }
        elseif ('adelete' == $this->cur_menu->getParameter())//удаление товара с заказа на сайт
            {

            $c = new Shoporderarticles($this->get->getId());
            if ($c && $c->getId()) {
                $order_id = $c->getOrder()->getId();
                $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $c->getArticleId(), 'id_size' => $c->getSize(), 'id_color' => $c->getColor()));
				if($article){
					$artic = new Shoparticles($c->getArticleId());
				 $pos = strpos($article->code, 'SR');
                                if($pos === false){
					if($article->getCount() == 0 and $artic->getCategoryId() != 16){
					if(wsActiveRecord::useStatic('Returnarticle')->count(array('code' => $article->getCode(), 'utime is null')) > 0){
					$this->sendMailAddCount($article->getCode(), $article->getIdArticle());
					}	
						}
			    $article->setCount($article->getCount() + $c->getCount());
                $artic->setStock($artic->getStock() + $c->getCount());
                                }

			   OrderHistory::newHistory($this->user->getId(), $c->getOrderId(), 'Удаление товара',
                    OrderHistory::getNewOrderArticle($c->getId()), $c->getArticleId());
					
				$article->save();
				$artic->save();

				$c->setCount(0);
                //   $c->setPrice(0);
                $c->save();	
				}else{
				 wsLog::add('Ошибка удаления ' . $c->Title() . ' - ' . $c->getArticleId(), 'ERROR dell article');
				$this->view->errordell = "Не удается удалить товар, ".$c->Title().". Попробуйте снова!";
				} 

                $this->_redir('shop-orders/edit/id/' . $order_id.'/#flag='.$this->get->getId());
            }

        }
        elseif('return_article' == $this->cur_menu->getParameter())//удаление товара с заказа, товар попадает в таблицу возвратов
            {
		
			$mes = 'возврат';

		$c = new Shoporderarticles((int)$this->post->id);
            if ($c && $c->getId()) {
			if($c->getCount() > 1){
			//die('dva');
				for($i = 1; $i <= $c->getCount(); $i++){
				$artic = new ShoporderarticlesVozrat();
				$artic->setStatus(0);
				$artic->setOrderId($c->getOrderId());
				$artic->setArticleId($c->getArticleId());
				$artic->setCod($c->getArtikul());
				$artic->setTitle($c->getTitle());
				$artic->setCount(1);
				$artic->setPrice($c->getPrice());
				$artic->setCtime(date('Y-m-d H:i:s'));
				$artic->setUtime(date('0000-00-00 00:00:00'));
				$artic->setUser($this->user->getId());
				$artic->setDelivery($c->getOrder()->getDeliveryTypeId());
				$artic->setSize($c->getSize());
				$artic->setColor($c->getColor());
				$artic->setOldPrice($c->getOldPrice());
				if($c->getOrder()->getDeposit() > 0){
				$artic->setDeposit(1);
				}
				$artic->save();
				}
				}else{
									
				$artic = new ShoporderarticlesVozrat();
				$artic->setStatus(0);
				$artic->setOrderId($c->getOrderId());
				$artic->setArticleId($c->getArticleId());
				$artic->setCod($c->getArtikul());
				$artic->setTitle($c->getTitle());
				$artic->setCount(1);
				$artic->setPrice($c->getPrice());
				$artic->setCtime(date('Y-m-d H:i:s'));
				$artic->setUtime(date('0000-00-00 00:00:00'));
				$artic->setUser($this->user->getId());
				$artic->setDelivery($c->order->delivery_type_id);
				$artic->setSize($c->getSize());
				$artic->setColor($c->getColor());
				$artic->setOldPrice($c->getOldPrice());
				if($c->getOrder()->getDeposit() > 0){
				$artic->setDeposit(1);
				}
				$artic->save();
				}
				
				$c->setCount(0);
                                $c->setCoin(0);
                $c->save();	
				
	OrderHistory::newHistory($this->user->getId(), $c->getOrderId(), 'Возврат товара', OrderHistory::getNewOrderArticle($c->getId()), $c->getArticleId());
        $order = new Shoporders($c->getOrderId());
        if($order->id){
            $order->ravnoCoinInOrderOfReturnArticles();
            $order->calculateOrderPrice();
        }
					if($this->post->js){
					die(json_encode('ok'));
					}else{
                 $this->_redir('shop-orders/edit/id/' .$c->getOrder()->getId().'/#flag='.$this->get->getId());
				 }
            }else{
				 wsLog::add('Ошибка удаления  на возврат ' . $c->Title() . ' - ' . $c->getArticleId(), 'ERROR dell article');
				$this->view->errordell = "Не удается удалить товар на возврат, ".$c->Title().". Попробуйте снова!";
				} 
		
		
		}
                elseif ('adeletenoshop' == $this->cur_menu->getParameter()) //удаление товара с заказа без возврата на сайт
                    {
			if($this->get->getMes()){
			$mes = $this->get->getMes();
			}else{
			$mes = '';
			}
            $c = new Shoporderarticles($this->get->getId());
            if ($c && $c->getId()) { 
			
									$log_text = 'Удаление без возврата';
									//$log_text .= '<br> ( '.$mes.' )';
									$log = new Shoparticlelog();
                                    $log->setCustomerId($this->website->getCustomer()->getId());
                                    $log->setUsername($this->website->getCustomer()->getUsername());
                                    $log->setArticleId($c->getArticleId());
									if ($c->getSize() and $c->getColor()) { 
									$size = new Size($c->getSize());
									$color = new Shoparticlescolor($c->getColor());
                                        $log->setInfo($size->getSize() . ' ' . $color->getName());
                                    }
									$log->setTypeId(7);
									$log->setCount($c->getCount());
									$log->setComents($log_text);
									$log->setCode(wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $c->getArticleId(), 'id_size' => $c->getSize(), 'id_color' => $c->getColor()))->getCode());
									$log->save();
                $c->setCount(0);
                $c->setCoin(0);
                $c->save();
                OrderHistory::newHistory($this->user->getId(), $c->getOrderId(), 'Удаление товара без возврата<br>( '.$mes.' )',
                    OrderHistory::getNewOrderArticle($c->getId()), $c->getArticleId());
               
			   $this->_redir('shop-orders/edit/id/' . $c->getOrder()->getId());
            }

        }

        
        //форма отбора заказов

        $data = [];
        $data[] = 'magaz != 1';
       // l($_GET);

        if ('quick' == $this->cur_menu->getParameter()){ 
            $data[] = 'quick = 1';
            
        }else{
            $data[] = 'quick = 0';
            
        }

       // $admins = wsActiveRecord::useStatic('Customer')->findAll(array('customer_type_id >1'));
       // $adminsid = [];
       // foreach ($admins as $ad) {
       //     $adminsid[] = $ad->getId();
       // }
        if (isset($_GET['kupon']) and @$_GET['kupon'] == 1) {$data[] = 'kupon NOT LIKE ""';}
		if (isset($_GET['online']) and @$_GET['online'] == 1) {$data[] = 'payment_method_id in(7,4,6)';}
		if(isset($_GET['bonus']) and @$_GET['bonus'] == 1) {$data[] = 'bonus > 0';}
        if (isset($_GET['quick_order']) and @$_GET['quick_order'] == 1) {
            $data[] = 'from_quick = 1';
        } elseif ('quick' != $this->cur_menu->getParameter()) {
            if (isset($_GET['is_admin']) and @$_GET['is_admin'] == 1) {
                $data[] = 'is_admin = 1';
            } else {
                $data[] = 'is_admin = 0';
            }
        }
         if (isset($_GET['shop_id']) and !empty($_GET['shop_id'])){ $data[] = 'shop_id = '.$_GET['shop_id']; }
        if (isset($_GET['detail']) and @$_GET['detail'] == 1) {$data[] = 'call_my = 1';}

		if (isset($_GET['nall']) and @$_GET['nall'] == 1) {$data[] = 'amount > 0';}
		
        if (isset($_GET['order']) and $_GET['order'] > 0) {
            
            $iddd = explode(',', $_GET['order']);
			
			if(count($iddd) == 1){
			 $data[] = '( id = '.$_GET['order'].' or comlpect LIKE "%' . $_GET['order'] . '%" or oldid = '.$_GET['order'].')';
			}else{
			$data[] = 'id in( '.implode(",", $iddd).') ';
			}
        }
        if(isset($_GET['quick_number']) and $_GET['quick_number'] > 0){
            $data[] = 'quick_number = '.$_GET['quick_number'];
        }
        if (isset($_GET['phone']) and strlen($_GET['phone']) > 0) $data[] = 'telephone LIKE "%' . trim($_GET['phone']) . '%"';

        if (isset($_GET['uname']) and strlen($_GET['uname']) > 0) $data[] = 'name LIKE "%' . $_GET['uname'] . '%"';

        if (isset($_GET['email']) and strlen($_GET['email']) > 0) $data[] = 'email LIKE "%' . $_GET['email'] . '%" or temp_email LIKE "%' . $_GET['email'] . '%"';

		if (isset($_GET['customer_id']) and strlen($_GET['customer_id']) > 0) $data[] = 'customer_id = '.trim($_GET['customer_id']);

        if (isset($_GET['delivery']) and (int)$_GET['delivery'] > 0) {
            if ((int)$_GET['delivery'] != 999) {
              /*  if ((int)$_GET['delivery'] == 111) {
                    $data[] = 'delivery_type_id in(9,10)';
                } else {*/
                    $data['delivery_type_id'] = (int)$_GET['delivery'];
                //}
            } else {
                $data[] = 'delivery_type_id in(3,5)';
            }
        }
        if (isset($_GET['status']) and (int)$_GET['status'] < 900) {
			if ($_GET['status'] == 0 or $_GET['status'] == 15 or $_GET['status'] == 16 or $_GET['status'] == 9 or $_GET['status'] == 100) {
				$order_orderby = array('id' => 'ASC');
			}else{
				$order_orderby = array('id' => 'DESC');
			}
            if ((int)$_GET['status'] == 8) {
                $data[] = 'status = 8 OR status = 14';
            }else{
                $data['status'] = (int)$_GET['status'];
            }
        }
        if(isset($_GET['liqpay_status_id']) and ($_GET['liqpay_status_id'] == 1 or $_GET['liqpay_status_id'] == 3)){
             $data['liqpay_status_id'] = (int)$_GET['liqpay_status_id'];
        }
        if(isset($_GET['admin']) and (int)$_GET['admin'] > 0){
            
            $data['admin'] = (int)$_GET['admin'];
        }


        if ((isset($_GET['create_from']) and strlen($_GET['create_from']) > 0) or (isset($_GET['create_to']) and strlen($_GET['create_to']) > 0)) {

            $start = '';
            $end = '';
            if (strlen(@$_GET['create_from']) > 0) $start = strtotime($_GET['create_from']);
            if (strlen(@$_GET['create_to']) > 0) $end = strtotime($_GET['create_to']);
            if ($start != '' and $end != '') {
                if ($start > $end) {
                    $k = $start;
                    $start = $end;
                    $end = $k;
                }
            }
            if ($start != '') $data[] = 'date_create > "' . date('Y-m-d 00:00:00', $start) . '"';
            if ($end != '') $data[] = 'date_create < "' . date('Y-m-d 23:59:59', $end) . '"';

        }

        if ((isset($_GET['go_from']) and strlen($_GET['go_from']) > 0) or (isset($_GET['go_to']) and strlen($_GET['go_to']) > 0)) {

            $start = '';
            $end = '';
            if (strlen(@$_GET['go_from']) > 0) $start = strtotime($_GET['go_from']);
            if (strlen(@$_GET['go_to']) > 0) $end = strtotime($_GET['go_to']);
            if ($start != '' and $end != '') {
                if ($start > $end) {
                    $k = $start;
                    $start = $end;
                    $end = $k;
                }
            }
            if ($start != '') $data[] = 'order_go > "' . date('Y-m-d 00:00:00', $start) . '"';
            if ($end != '') $data[] = 'order_go < "' . date('Y-m-d 23:59:59', $end) . '"';

        }
        if (isset($_GET['price']) and strlen($_GET['price']) > 0) {
            $data[] = 'amount > ' . ((int)$_GET['price'] - 3) . ' AND amount < ' . ((int)$_GET['price'] + 3);
        }
        if (isset($_GET['nakladna']) and strlen($_GET['nakladna']) > 0) {
            $data[] = 'nakladna LIKE "%' . $_GET['nakladna'] . '%"';
        }

        $onPage = $_COOKIE['item_page']?$_COOKIE['item_page']:40;
        
        $page = !empty($this->get->page) && (int)$this->get->page ? (int)$this->get->page : 1;
        $startElement = ($page - 1) * $onPage;
		if(isset($_GET['go'])){
		$total = wsActiveRecord::useStatic('Shoporders')->count($data);
		}else{
		if('quick' == $this->cur_menu->getParameter()) {
                    $onPage = 300;
                    $total = 60; }else{ $total = 300; }
                
		}
        //$total = wsActiveRecord::useStatic('Shoporders')->count($data);
        $this->view->totalPages = ceil($total / $onPage);
        $this->view->count = $total;
        $this->view->page = $page;
        $this->view->start = ($page == 1) ? 1 : $onPage * ($page - 1);
        $this->view->end = $onPage * ($page - 1) + $onPage;
        $this->view->orders = wsActiveRecord::useStatic('Shoporders')->findAll($data, $order_orderby, array($startElement, $onPage));
	
        if ('quick' == $this->cur_menu->getParameter()){
            echo $this->render('shop/orders-quick.tpl_1.php', 'index.php');
        }else{
            echo $this->render('shop/orders.tpl.php');
        }
        //форма отбора заказов
			


    }
public function generatePassword($length = 10){
  $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
  $numChars = strlen($chars);
  $string = '';
  for ($i = 0; $i < $length; $i++) {
    $string .= substr($chars, rand(1, $numChars) - 1, 1);
  }
  return $string;
}	
	
	
	public function sendMailAddCount($code, $id) 
    {
	$art = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('code' => $code, 'id_article'=>$id));
	$this->view->art = $art; 
    $this->view->art1 = wsActiveRecord::useStatic('Shoparticles')->findFirst(array('id' => $id));

	 foreach (wsActiveRecord::useStatic('Returnarticle')->findAll(array('code' => $code, 'utime is null', 'id_article'=>$id)) as $articles) {
	 if(isValidEmailNew($articles->getEmail()) and isValidEmailRu($articles->getEmail())){
	 
							$msg = $this->view->render('mailing/notice.template.tpl.php');
							$subject = 'Привет, '.$articles->getName().', товар появился в наличии. Cпеши купить!';
							$this->view->email = $articles->getEmail();
							SendMail::getInstance()->sendSubEmail($articles->getEmail(), $articles->getName(), $subject, $msg);
	
	$articles->setUtime(date('Y-m-d H:i:s'));
    $articles->save(); 
	}
	}
	
	}
	public function sendMailAddCountTrue($code,$id)
    {
	 $art = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('code' => $code));
	$this->view->art = $art; 
    $this->view->art1 = wsActiveRecord::useStatic('Shoparticles')->findFirst(array('id' => $art->getIdArticle()));

	 foreach (wsActiveRecord::useStatic('Returnarticle')->findAll(array('id' => $id, 'utime is null')) as $articles) {
	 if(isValidEmailNew($articles->getEmail()) and isValidEmailRu($articles->getEmail())){
							
							$msg = $this->view->render('mailing/notice.template.tpl.php');
							$subject = 'Привет, '.$articles->getName().', товар появился в наличии. Cпеши купить!';
							$this->view->email = $articles->getEmail();
							SendMail::getInstance()->sendSubEmail($articles->getEmail(), $articles->getName(), $subject, $msg);
	
	$articles->setUtime(date('Y-m-d H:i:s'));
	$articles->setCodepoluchil($code);
    $articles->save(); 
	}
	}
	}
	

    public function searchlogAction()
    {
        $data = [];
        $data[] = '1';
       // if(isset($this->get->go)){
            if(!empty($this->get->from)){
                $data[] = "ctime >= '".date('Y-m-d 00:00:00', strtotime($this->get->from))."'";
            }else{
                 $data[] = "ctime >= '".date('Y-m-d 00:00:00')."'";
            }
            if(!empty($this->get->to)){
                $data[] = "ctime <= '".date('Y-m-d 23:59:59', strtotime($this->get->to))."'";
            }else{
                 $data[] = "ctime <= '".date('Y-m-d 23:59:59')."'";
            }
       // }
        $d = implode(' and ', $data);
        $sql = "SELECT  `id` FROM `red_search_logs` WHERE  {$d} GROUP BY `search`";
        $onPage = 100;
        $page = !empty($this->get->page) && (int)$this->get->page ? (int)$this->get->page : 1;
        $startElement = ($page - 1) * $onPage;
       // $total = wsActiveRecord::useStatic('SearchLog')->count($data);
        $total = wsActiveRecord::useStatic('SearchLog')->findByQuery($sql)->count();
        $this->view->totalPages = ceil($total / $onPage);
        $this->view->count = $total;
        $this->view->page = $page;
        $this->view->start = ($page == 1) ? 1 : $onPage * ($page - 1);
        $this->view->end = $onPage * ($page - 1) + $onPage;
         $sql1 = "SELECT `search` , COUNT( `id` ) AS ctn FROM `red_search_logs` WHERE  {$d} GROUP BY `search` ORDER BY `ctn` DESC LIMIT {$startElement} , {$onPage}";
      //   l($sql1);
        //$this->view->searchs = wsActiveRecord::useStatic('SearchLog')->findAll($data, array(), array($startElement, $onPage));
        $this->view->searchs = wsActiveRecord::useStatic('SearchLog')->findByQuery($sql1);
        echo $this->render('shop/search_log.tpl.php');
    }

    public function shopofferAction()
    {

        if ($_POST && isset($_POST['getprice'])) {
            if (isset($_POST['id']) && ($article = wsActiveRecord::useStatic('Shoparticles')->findById((int)$_POST['id'])) && $article->getId()) {
                $res = array(
                    'result' => 'done',
                    'price' => Shoparticles::showPrice($article->getPrice()),
                    'short_text' => $article->getShortText()
                );
            } else {
                $res = array('result' => 'false');
            }
            echo json_encode($res);
            die();
        }

        $errors = array();

        $filename_image = false;
        if ($_FILES) {
            if ($_FILES['image_file']) {
                include_once 'Asido/class.asido.php';
                include_once 'Asido/class.driver.php';
                include_once 'Asido/class.driver.gd.php';

                $mdfname = md5(uniqid(rand(), true));

                if (is_uploaded_file($_FILES['image_file']['tmp_name'])) {
                    $ext = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
                    if (!$ext) {
                        $res = getimagesize($filename);
                        $ext = image_type_to_extension($res[2], false);
                    }
                    $oldfilename = $_FILES['image_file']['tmp_name'];
                    $filename = INPATH . "files/tmp/{$mdfname}.{$ext}";
                    if (move_uploaded_file($oldfilename, $filename)) {

                        $filename_dest4 = INPATH . "files/i4/{$mdfname}.{$ext}";

                        $size = getimagesize($filename);
                        $rorg = $size[0] / $size[1];

                        $asido = new Asido();
                        $asido->Driver('gd');

                        // image 80 x 80
                        $frame = $asido->Image($filename, $filename_dest4);
                        $width = 80;
                        $height = 80;
                        if ($width / $height < $rorg) {
                            $w = $size[0] * ($height / $size[1]);
                            $h = $height;
                        } else {
                            $w = $width;
                            $h = $size[1] * ($width / $size[0]);
                        }
                        $asido->stretch($frame, $w, $h);
                        if ($w > $width)
                            $asido->crop($frame, round($w - $width) / 2, 0, $width, $height);
                        elseif ($h > $height)
                            $asido->crop($frame, 0, round($h - $height) / 2, $width, $height);
                        $frame->Save(ASIDO_OVERWRITE_ENABLED);

                        if (is_file($filename))
                            unlink($filename);

                        $filename_image = pathinfo($filename_dest4, PATHINFO_BASENAME);
                    } else {
                        $errors[] = $this->trans->get("Can not upload file");
                    }
                }
            }
        }

        $offer = false;

        if ($_POST) {
            foreach ($_POST as &$value)
                $value = stripslashes(trim($value));

            $offer = wsActiveRecord::useStatic('Shoparticlesoffer')->findFirst();
            if (!$offer)
                $offer = new Shoparticlesoffer();

            if (isset($_POST['price']))
                $_POST['price'] = str_replace(',', '.', $_POST['price']);

            $offer->import($_POST);

            if ($filename_image) {
                if (!$offer->isNew() || $offer->getImage())
                    $offer->deleteCurImages();
                $offer->setImage($filename_image);
            } elseif ($offer->isNew() && !$offer->getImage())
                $errors[] = $this->trans->get("Пожалуйста виберите основной рисунок");

            if (!$offer->getShortText())
                $errors[] = $this->trans->get("Пожалуйста заполните описание");
            if (!$offer->getPrice())
                $errors[] = $this->trans->get("Пожалуйста укажите цену");
            if (!$offer->getPriceOld())
                $errors[] = $this->trans->get("Пожалуйста укажите цену до скидки");

            if (!$errors) {
                $offer->save();
                $this->_redir('shop-offer');
            }
        }

        $this->view->errors = $errors;

        $this->view->categories = wsActiveRecord::useStatic('Shopcategories')->findAll();

        $this->view->offer = (!$offer) ? wsActiveRecord::useStatic('Shoparticlesoffer')->findFirst() : $offer;

        echo $this->render('shop/offer.tpl.php');

    }

    public function generateorderAction()
    {
       $month = array('01' => 'січня', '02' => 'лютого', '03' => 'березня', '04' => 'квітня', '05' => 'травня', '06' => 'червня',
           '07' => 'липня', '08' => 'серпня', '09' => 'вересня', '10' => 'жовтня', '11' => 'листопада', '12' => 'грудня'
        );
       /* $month = array('01' => 'января', '02' => 'Февраля', '03' => 'марта', '04' => 'апреля', '05' => 'мая', '06' => 'июня',
            '07' => 'июля', '08' => 'августа', '09' => 'сентября', '10' => 'октября', '11' => 'ноября', '12' => 'декабря'
        );*/
        $order = new Shoporders($this->get->id);
        $dt = explode('-', substr($order->getDateCreate(), 0, 10));
        $dttd = explode('-', date("Y-m-d"));
        $this->view->date = $dt[2] . ' ' . $month[$dt[1]] . ' ' . $dt[0];
        $this->view->date_today = $dttd[2] . ' ' . $month[$dttd[1]] . ' ' . $dttd[0];
        $this->view->exploded_date = $dt;
        $this->view->order = $order;
        
        $all_orders_2 = wsActiveRecord::useStatic('Customer')->findByQuery('
			       SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
			        WHERE ws_orders.customer_id = ' . $order->getCustomerId() . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) AND ws_orders.id < ' . $order->id)->at(0);

        $this->view->all_orders_amount_total = $all_orders_2->getAmount();
           if($this->get->count){
                $count = $this->get->count;
            }else{
                  $count = 2;
            }
            $this->view->zayava = true;
                if($order->magaz){
                    $count = 1;
                //    if(!in_array($order->payment_method_id, [4,6,8,9]) and !$order->deposit){
                        $this->view->zayava = false; 
              //  }
                   $this->view->count = $count;
                 
                 echo $this->render('', 'order/tovarniy_chek_magaz.php');
                }else{
                    $this->view->count = $count;
                 
                 echo $this->render('', 'order/tovarniy_chek.php');
                }
            
                
                  $_SESSION['lang'] = 'ru';
    
        exit();
    }

    public function masgenerateorderAction()
    {
        $month = array('01' => 'січня', '02' => 'лютого', '03' => 'березня', '04' => 'квітня', '05' => 'травня', '06' => 'червня',
           '07' => 'липня', '08' => 'серпня', '09' => 'вересня', '10' => 'жовтня', '11' => 'листопада', '12' => 'грудня'
        );
        /*$month = array('01' => 'января', '02' => 'Февраля', '03' => 'марта', '04' => 'апреля', '05' => 'мая', '06' => 'июня',
            '07' => 'июля', '08' => 'августа', '09' => 'сентября', '10' => 'октября', '11' => 'ноября', '12' => 'декабря'
        );*/
        if (!$this->get->ids) {
            $this->_redir('admin');
        }
        $ids = explode(',', $this->get->ids);
        sort($ids);
$_SESSION['lang'] = 'uk';
        foreach ($ids as $id) {

            $order = new Shoporders($id);
            $dt = explode('-', substr($order->getDateCreate(), 0, 10));
            $dttd = explode('-', date("Y-m-d"));
            $this->view->date = $dt[2] . ' ' . $month[$dt[1]] . ' ' . $dt[0];
            $this->view->date_today = $dttd[2] . ' ' . $month[$dttd[1]] . ' ' . $dttd[0];
            $this->view->exploded_date = $dt;
            $this->view->order = $order;


            $all_orders_2 = wsActiveRecord::useStatic('Customer')->findByQuery('
			        SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
			        WHERE ws_orders.customer_id = ' . $order->getCustomerId() . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) AND ws_orders.id <' . $id)->at(0);
            $this->view->all_orders_amount_total = (int)$all_orders_2->getAmount();

            if($this->get->count){
                $count = $this->get->count;
            }else{
                  $count = 2;
            }
            $this->view->zayava = true;
            $_SESSION['lang'] = 'ru';
                if($order->magaz){
                    $count = 1;
                 //   if(!in_array($order->payment_method_id, [1,4,6,8,9])){
                        $this->view->zayava = false; 
              //  }
                 $this->view->count = $count;
                    echo $this->render('', 'order/tovarniy_chek_magaz.php');
                }else{
                     $this->view->count = $count;
                     echo $this->render('', 'order/tovarniy_chek.php');
                }
               
                
                 
      
        }
    }

    public function masgeneratechekAction()
    {
        $month = array('01' => 'січня', '02' => 'лютого', '03' => 'березня', '04' => 'квітня', '05' => 'травня', '06' => 'червня',
           '07' => 'липня', '08' => 'серпня', '09' => 'вересня', '10' => 'жовтня', '11' => 'листопада', '12' => 'грудня'
        );
       /* $month = array('01' => 'января', '02' => 'Февраля', '03' => 'марта', '04' => 'апреля', '05' => 'мая', '06' => 'июня',
            '07' => 'июля', '08' => 'августа', '09' => 'сентября', '10' => 'октября', '11' => 'ноября', '12' => 'декабря'
        );*/
        if (!$this->get->ids) {
            $this->_redir('admin');
        }
        $ids = explode(',', $this->get->ids);
        sort($ids);

		//$this->view->orders = $ids;
		 $dttd = explode('-', date("Y-m-d"));
		 $this->view->date_today = $dttd[2] . ' ' . $month[$dttd[1]] . ' ' . $dttd[0];
        foreach ($ids as $id) {
            $order = new Shoporders($id);

            $this->view->order = $order;

            echo $this->render('', 'order/chek.tpl.php');
            $_SESSION['lang'] = 'ru';
        }
    }
    public function masgeneratelentashopAction()
    {
        $month = array('01' => 'січня', '02' => 'лютого', '03' => 'березня', '04' => 'квітня', '05' => 'травня', '06' => 'червня',
           '07' => 'липня', '08' => 'серпня', '09' => 'вересня', '10' => 'жовтня', '11' => 'листопада', '12' => 'грудня'
        );
       /* $month = array('01' => 'января', '02' => 'Февраля', '03' => 'марта', '04' => 'апреля', '05' => 'мая', '06' => 'июня',
            '07' => 'июля', '08' => 'августа', '09' => 'сентября', '10' => 'октября', '11' => 'ноября', '12' => 'декабря'
        );*/
        if (!$this->get->ids) {
            $this->_redir('admin');
        }
        $ids = explode(',', $this->get->ids);
        sort($ids);

		//$this->view->orders = $ids;
		 $dttd = explode('-', date("Y-m-d"));
		 $this->view->date_today = $dttd[2] . ' ' . $month[$dttd[1]] . ' ' . $dttd[0];
                 $articles = [];
        foreach ($ids as $id) {
          //  $order = new Shoporders($id);
           $order = wsActiveRecord::useStatic('Shoporderarticles')->findAll(['order_id'=> $id, 'count > 0']);
            foreach ($order as $article ){
           // $this->view->order = $order;
                
$articles[$article->article_id][] = (object)[
    'name' => $article->article_db->getTitle(),
    'cod' => $article->artikul,
    'count' => $article->count
];
                
           // 
            }
            
        }
        $this->view->articles = (object)$articles;
        $delivery = new Shoporders($id);
        
        $this->view->delivery = $delivery->delivery_type->getName();
        
        echo $this->render('', 'order/chek_lenta_shop.tpl.php');
        $_SESSION['lang'] = 'ru';
    }

    public function masgeneratenaklAction()
    {
    /*   $month_orig = array('01' => 'січень', '02' => 'лютий', '03' => 'березень', '04' => 'квітень', '05' => 'травень', '06' => 'червень',
            '07' => 'липень', '08' => 'серпень', '09' => 'вересень', '10' => 'жовтень', '11' => 'листопад', '12' => 'грудень'
        );
        
        $month = array('01' => 'января', '02' => 'Февраля', '03' => 'марта', '04' => 'апреля', '05' => 'мая', '06' => 'июня',
            '07' => 'июля', '08' => 'августа', '09' => 'сентября', '10' => 'октября', '11' => 'ноября', '12' => 'декабря'
        );
    */
        if (!$this->get->ids) { $this->_redir('admin'); }
        $res = Naleyki::getPrint($this->get);
        $this->view->orders = $res['order'];
        echo $this->render('', $res['page']);
        exit();
    }

    public function masgenerateblankAction()
    {
        $month_orig = array('01' => 'січень', '02' => 'лютий', '03' => 'березень', '04' => 'квітень', '05' => 'травень', '06' => 'червень',
            '07' => 'липень', '08' => 'серпень', '09' => 'вересень', '10' => 'жовтень', '11' => 'листопад', '12' => 'грудень'
        );
        $month = array('01' => 'января', '02' => 'Февраля', '03' => 'марта', '04' => 'апреля', '05' => 'мая', '06' => 'июня',
            '07' => 'июля', '08' => 'августа', '09' => 'сентября', '10' => 'октября', '11' => 'ноября', '12' => 'декабря'
        );
        if (!$this->get->ids) {
            $this->_redir('admin');
        }
        $ids = explode(',', $this->get->ids);
        sort($ids);

        $blank117 = $this->render('', 'order/wrapper_blank117.tpl.php');

        $i = 0;
        foreach ($ids as $id) {

            $order = new Shoporders($id);
            $dt = explode('-', substr($order->getDateCreate(), 0, 10));
            $this->view->date = $dt[2] . ' ' . $month[@$dt[1]] . ' ' . $dt[0];
            $this->view->exploded_date = $dt;
            $this->view->order = $order;
            $customer_id = $order->getCustomerId();
            $all_orders = wsActiveRecord::useStatic('Customer')->findByQuery('
				SELECT
					IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
				FROM
					ws_order_articles
					JOIN ws_orders
					ON ws_order_articles.order_id = ws_orders.id
				WHERE
					ws_orders.customer_id = '.$customer_id.'
					AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0);
            $all_orders_2 = wsActiveRecord::useStatic('Customer')->findByQuery('
				SELECT
					IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
				FROM
					ws_order_articles
					JOIN ws_orders
					ON ws_order_articles.order_id = ws_orders.id
				WHERE
					ws_orders.customer_id = '.$customer_id.'
					AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16)
					AND ws_orders.id <= '.$id)->at(0);
            $this->view->all_orders_amount = $all_orders->getAmount();
            $this->view->all_orders_amount_total = $all_orders_2->getAmount();

            $amount = sprintf("%.2F", $order->getAmount());
            $amount_round = sprintf("%.2F", ceil($order->getAmount()));

            list($doz, $poslez) = explode('.', (string)$amount);
            list($doz_r, $poslez_r) = explode('.', (string)$amount_round);

            $this->view->doz = $doz;
            $this->view->poslez = $poslez;
            $this->view->str_price = $this->num2str($doz) . 'грн. ' . ($poslez ? sprintf("%02u коп", $poslez) : '');
            $this->view->str_price_round = $this->num2str($doz_r) . 'грн. ' . ($poslez_r ? sprintf("%02u коп", $poslez_r) : '');

            $this->view->num_price = $amount;
            $this->view->num_price_round = $amount_round;

            $this->view->blank_count = $i;

            $blank117 .= $this->render('', 'order/blank117.tpl.php');

            ++$i;
        }
        echo $blank117 . '</body></html>';
    }

// 
	    public function masgenerateblank_testAction()
    {
        $month_orig = array('01' => 'січень', '02' => 'лютий', '03' => 'березень', '04' => 'квітень', '05' => 'травень', '06' => 'червень',
            '07' => 'липень', '08' => 'серпень', '09' => 'вересень', '10' => 'жовтень', '11' => 'листопад', '12' => 'грудень'
        );
        $month = array('01' => 'января', '02' => 'Февраля', '03' => 'марта', '04' => 'апреля', '05' => 'мая', '06' => 'июня',
            '07' => 'июля', '08' => 'августа', '09' => 'сентября', '10' => 'октября', '11' => 'ноября', '12' => 'декабря'
        );
        if (!$this->get->ids) {
            $this->_redir('admin');
        }
        $ids = explode(',', $this->get->ids);
        sort($ids);

        $blank118 = $this->render('', 'order/wrapper_blank118.tpl.php');

        $i = 0;
        foreach ($ids as $id) {

            $order = new Shoporders($id);
            $dt = explode('-', substr($order->getDateCreate(), 0, 10));
            $this->view->date = $dt[2] . ' ' . $month[$dt[1]] . ' ' . $dt[0];
            $this->view->exploded_date = $dt;
            $this->view->order = $order;
            $customer_id = $order->getCustomerId();



            $all_orders = wsActiveRecord::useStatic('Customer')->findByQuery('
				SELECT
					IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
				FROM
					ws_order_articles
					JOIN ws_orders
					ON ws_order_articles.order_id = ws_orders.id
				WHERE
					ws_orders.customer_id = '.$customer_id.'
					AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,15,16) ')->at(0);
           


 $all_orders_2 = wsActiveRecord::useStatic('Customer')->findByQuery('
				SELECT
					IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
				FROM
					ws_order_articles
					JOIN ws_orders
					ON ws_order_articles.order_id = ws_orders.id
				WHERE
					ws_orders.customer_id = '.$customer_id.'
					AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16)
					AND ws_orders.id <= '.$id)->at(0);
            $this->view->all_orders_amount = $all_orders->getAmount();
            $this->view->all_orders_amount_total = $all_orders_2->getAmount();

            $amount = sprintf("%.2F", $order->getAmount());
            $amount_round = sprintf("%.2F", ceil($order->getAmount()));

           // list($doz, $poslez) = explode('.', (string)$amount);
            list($doz_r, $poslez_r) = explode('.', (string)$amount_round);

           // $this->view->doz = $doz;
            //$this->view->poslez = $poslez;
            //$this->view->str_price = $this->num2str($doz) . 'грн. ' . ($poslez ? sprintf("%02u коп", $poslez) : '');
            $this->view->str_price_round = $this->num2str($doz_r) . 'грн. ' . ($poslez_r ? sprintf("%02u коп", $poslez_r) : '');

           // $this->view->num_price = $amount;
            $this->view->num_price_round = $amount_round;

            $this->view->blank_count = $i;

            $blank118 .= $this->render('', 'order/blank118.tpl.php');

            ++$i;
        }
        echo $blank118 . '</body></html>';
    }
//


    function pollresultsAction()
    {
        if ((int)$this->get->excel) {
            $poll = new Poll((int)$this->get->excel);
            if ($poll->getId()) {

                require_once('PHPExel/PHPExcel.php');
                $order_status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));

                $name = 'pollresult';
                $filename = $name . '.xls';

                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
                $aSheet->setCellValue('A1', 'Опрос: ' . $poll->getName());
                $sum = 0;
                $votes = wsActiveRecord::useStatic('PollResults')->findByQuery(
                    'SELECT name,IF( question_id is null  ,0,count(*)) as c
                            FROM poll_questions
                            LEFT JOIN `poll_results`
                                ON poll_results.question_id = poll_questions.id
                        WHERE poll_results.poll_id = ' . $poll->getId() . '
                						GROUP BY question_id
                						ORDER BY c DESC'
                );
                foreach ($votes as $result) {
                    $sum += $result->getC();
                }
                $aSheet->setCellValue('A2', 'Всего проголосовало: ' . $sum);
                $aSheet->setCellValue('A3', 'Ответы');
                $aSheet->setCellValue('B3', 'Количество');
                $i = 4;
                foreach ($votes as $item) {
                    $aSheet->setCellValue('A' . $i, $item->getName());
                    $aSheet->setCellValue('B' . $i, $item->getC());
                    $i++;
                }

                $aSheet->setCellValue('A' . $i, 'Ответы пользователей');
                $aSheet->setCellValue('B' . $i, 'Количество');
                $i++;
                $ans = wsActiveRecord::useStatic('PollResults')->findByQuery('SELECT res as name, count(res) as c FROM `poll_results` WHERE res IS NOT NULL AND poll_id=' . $poll->getId() . ' GROUP BY name ORDER BY c DESC');

                foreach ($ans as $item) {
                    $aSheet->setCellValue('A' . $i, $item->getName());
                    $aSheet->setCellValue('B' . $i, $item->getC());
                    $i++;
                }


                $q = 'SELECT ws_customers.* FROM ws_customers
                JOIN poll_results ON poll_results.customer_id = ws_customers.id
                WHERE poll_results.customer_id > 0 AND poll_results.poll_id = ' . $poll->getId();
                $customers = wsActiveRecord::useStatic('Customer')->findByQuery($q);
                $regions = array();
                $city = array();
                $proc = array();
                foreach ($customers as $cus) {
                    if (@$regions[$cus->getObl()]) {
                        $regions[$cus->getObl()] = $regions[$cus->getObl()] + 1;
                    } else {
                        $regions[$cus->getObl()] = 1;
                    }

                    if (@$city[$cus->getCity()]) {
                        $city[$cus->getCity()] = $city[$cus->getCity()] + 1;
                    } else {
                        $city[$cus->getCity()] = 1;
                    }

                    if (@$proc[$cus->getRealSkidka() . '%']) {
                        $proc[$cus->getRealSkidka() . '%'] = $proc[$cus->getRealSkidka() . '%'] + 1;
                    } else {
                        $proc[$cus->getRealSkidka() . '%'] = 1;
                    }

                }

                $i++;
                $aSheet->setCellValue('A' . $i, 'Пользователи по регионам');
                $aSheet->setCellValue('B' . $i, 'Количество');
                $i++;
                foreach ($regions as $k => $v) {
                    if ($k) {
                        $aSheet->setCellValue('A' . $i, $k);
                        $aSheet->setCellValue('B' . $i, $v);
                        $i++;
                    }

                }
                $i++;
                $aSheet->setCellValue('A' . $i, 'Пользователи по скидке');
                $aSheet->setCellValue('B' . $i, 'Количество');
                $i++;
                foreach ($proc as $k => $v) {
                    $aSheet->setCellValue('A' . $i, $k);
                    $aSheet->setCellValue('B' . $i, $v);
                    $i++;

                }
                $i++;
                $aSheet->setCellValue('A' . $i, 'Пользователи по городам');
                $aSheet->setCellValue('B' . $i, 'Количество');
                $i++;
                foreach ($city as $k => $v) {
                    if ($k) {
                        $aSheet->setCellValue('A' . $i, $k);
                        $aSheet->setCellValue('B' . $i, $v);
                        $i++;
                    }
                }


                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");


                $objWriter->save('php://output');


                //header("Content-type: application/x-msexcel");

            } else {
                $this->_redir('pollresults');
            }
        }elseif($this->get->new_poll == 'new'){
		//$this->cur_menu->page_title = $news->title;
        $this->cur_menu->name = "Новое голосование:";
		
		echo $this->render('poll/new.poll.tpl.php');
		}else{
		if(in_array($this->user->getId(), array(27391,6741,25129,22609,34608,28767,26187,8985,7668,73412993,1,8005))){
		//array(33929,22832,31748,29397,24148,22699,8005,24150)
		 $filter = array('active = 2');
		}else{
		 $filter = array('active = 1');
		}
       

        $polls = wsActiveRecord::useStatic('Poll')->findAll($filter);
        $results = array();

        if ($polls) {
            foreach ($polls as $poll) {
                $votes = wsActiveRecord::useStatic('PollResults')->findByQuery(
                    'SELECT name,IF( question_id is null  , 0, count(*)) as c
							FROM poll_questions
							LEFT JOIN `poll_results`
								ON poll_results.question_id = poll_questions.id
						WHERE poll_results.poll_id = ' . $poll->getId() . '
						GROUP BY question_id
						ORDER BY c DESC'
                );

                $results[$poll->getName()]['results'] = $votes;
                $results[$poll->getName()]['id'] = $poll->getId();
                $results[$poll->getName()]['active'] = $poll->getActive();
                $results[$poll->getName()]['answers'] = wsActiveRecord::useStatic('PollResults')->findByQuery('SELECT res, count(res) as cnt FROM `poll_results` WHERE res IS NOT NULL AND poll_id=' . $poll->getId() . ' GROUP BY res ORDER BY cnt DESC');
            }

            $this->view->results = $results;
            //get all additional answers
            echo $this->render('poll/result.tpl.php');
        }
		}
    }

    public function getarticlenameAction()
    {
        if ($this->get->query) {
            $date = '(model LIKE "%' . mysql_real_escape_string($this->get->query) . '%")';
            $find = wsActiveRecord::useStatic('Shoparticles')->findByQuery('SELECT distinct(model) from ws_articles WHERE ' . $date);
            $mas = array();
            foreach ($find as $item) {
                $mas[] = $item->getModel();
            }
            echo json_encode(array('query' => $this->get->query, 'suggestions' => $mas));
        }
        die();
    }
	//автокомплектация цвета товара
	/* public function getarticlecolorAction()
    {
        if ($this->get->query) {
            $date = '(name LIKE "%' . mysql_real_escape_string($this->get->query) . '%")';
            $find = wsActiveRecord::useStatic('Shoparticlescolor')->findByQuery('SELECT distinct(name) from ws_articles_colors WHERE ' . $date);
            $mas = array();
            foreach ($find as $item) {
                $mas[] = $item->getName();
            }
            echo json_encode(array('query' => $this->get->query, 'suggestions' => $mas));
        }
        die();
    }*/

    public function getarticlebrendAction()
    {
        if ($this->get->query) {
            $date = '(brand LIKE "%' . mysql_real_escape_string($this->get->query) . '%")';

            $find = wsActiveRecord::useStatic('Shoparticles')->findByQuery('SELECT distinct(brand) from ws_articles WHERE ' . $date);
            $mas = array();

            foreach ($find as $item) {
                $mas[] = $item->getBrand();
            }
            echo json_encode(array('query' => $this->get->query, 'suggestions' => $mas));
        }
        die();
    }

    public function articlehistoryAction()
    {
        if ($this->get->id) {
		if($this->get->m){
			$hist = wsActiveRecord::useStatic('Shoparticlelog')->findAll(array('article_id' => (int)$this->get->id), array(), array('50'));
			if($hist->count() > 0){
			$text = '
			<table cellpadding="4" cellspacing="0" class="table table-striped table-hover table-bordered table-condensed " style="font-size: 90%;">
				<tr>
					<th>Дата</th>
					<th>Пользователь</th>
					<th>Действия</th>
					<th>Информация</th>
					<th>Колл.</th>
					<th>Артикул</th>
			</tr>';
			 foreach($hist as $s){
			 $f = '';
			 if($s->getTypeId() == 5){
			  $f = '(+) ';
			 }elseif($s->getTypeId() == 2){
			 $f = '(-) ';
			 }
			 $text.='<tr>
						<td>'.date('d.m.Y H:i:s',strtotime($s->getCtime())).'</td>
						<td>'.$s->admin->getMiddleName().'</td>
						<td>'.$s->getComents().'</td>
						<td>'.$s->getInfo().'</td>
						<td>'.$f.$s->getCount().'</td>
						<td>'.$s->getCode().'</td>
					</tr>';
			 }
			 $text.='</table>';
			die($text);
			 }else{
			 $text.='По данному заказу история отсутствует.';
			 die($text);
			 }
			 
			}else{
            $this->view->logs = wsActiveRecord::useStatic('Shoparticlelog')->findAll(array('article_id' => (int)$this->get->id), array(), array('50'));
            echo $this->render('shop/articlelog.tpl.php');
			}

        } else {
            $this->_redir('index');
        }
    }

//	2517
    public function orderhistoryAction()
    {
        $mas_stat = array(
            '1' => 'Смена статуса',
            '2' => 'Удаление товара',
            '3' => 'Удаление товара без возврата',
            '4' => 'Изменение товара',
            '5' => 'Новый товар',
            '6' => 'Смена доставки',
            '7' => 'Смена номера накладной',
            '8' => 'Совмещение заказов'
        );
        if ($this->get->id) {

            $this->view->id = $this->get->id;
            $data = array();
            $order = new Shoporders((int)$this->get->id);
            if ($order->getComlpect()) {
                $last = explode(';', $order->getComlpect());
                $mas = array();
                $mas[] = (int)$this->get->id;
                foreach ($last as $lst) {
                    if ($lst) {
                        $mas[] = $lst;
                    }
                }
                $data[] = 'order_id in(' . implode(',', $mas) . ')';
            } else {
                $data['order_id'] = (int)$this->get->id;
            }
            if (@$_GET['type'] > 0) {
                $data['name'] = @$mas_stat[$_GET['type']];
            }
			if($this->get->m){
			$hist = wsActiveRecord::useStatic('OrderHistory')->findAll($data, array(), array(150));
			if($hist->count() > 0){
			$text = '
			<table cellpadding="4" cellspacing="0" class="table table-striped table-hover table-bordered table-condensed " style="font-size: 90%;">
				<tr>
					<th>Заказ</th>
					<th>Дата</th>
					<th>Пользователь</th>
					<th>Действия</th>
					<th>Коментарий</th>
			</tr>';
			
			
			 foreach($hist as $s){
			 $text.='<tr>
						<td>'.$s->getOrderId().'</td>
						<td>'.date('d.m.Y H:i:s',strtotime($s->getCtime())).'</td>
						<td>'.@$s->admin->getMiddleName().' '.@$s->admin->getFirstName().'</td>
						<td>'.$s->getName().'</td>
						<td>'.$s->getInfo().'</td> 
					</tr>';
			 }
			 $text.='</table>';
			die($text);
			 }else{
			 $text.='По данному заказу история отсутствует.';
			 die($text);
			 }
			 
			}else{
			$this->view->logs = wsActiveRecord::useStatic('OrderHistory')->findAll($data, array(), array('50'));
            echo $this->render('shop/orderlog.tpl.php');
			}
        } else {
            $this->_redir('index');
        }
    }
	public function historypaystatusAction()
    {
        $mas_stat = array(
            '1' => 'Смена статуса',
            '2' => 'Удаление товара',
            '3' => 'Удаление товара без возврата',
            '4' => 'Изменение товара',
            '5' => 'Новый товар',
            '6' => 'Смена доставки',
            '7' => 'Смена номера накладной',
            '8' => 'Совмещение заказов'
        );
        if ($this->get->id) {

            $this->view->id = $this->get->id;
            $data = array();

                $data[] = 'order_id =' . (int)$this->get->id;

$text = '';
			$hist = wsActiveRecord::useStatic('LiqPayHistory')->findAll($data, array(), array('10'));
			if($hist->count() > 0){
			$text = '
			<table cellpadding="4" cellspacing="0" class="table table-striped table-hover table-bordered table-condensed " style="font-size: 90%;">
				<tr>
					<th>Дата</th>
					<th>Статус</th>
			</tr>';
			
			
			 foreach($hist as $s){
			 $text.='<tr>
						<td>'.date('d.m.Y H:i:s',strtotime($s->getCtime())).'</td>
						<td>'.$s->liqpay_status_name->getName().'</td>
					</tr>';
			 }
			 $text.='</table>';
			die($text);
			 }else{
			 $text.='По данному заказу история отсутствует.';
			 die($text);
			 }
			 
        } else {
            $this->_redir('index');
        }
    }
	

    public function allstatusAction()
    {
        if ($this->get->id and strlen($this->get->status) > 0) {
            $mas = explode(',', $this->get->id);
            $orders = wsActiveRecord::useStatic('Shoporders')->findAll(['id in (' . $this->get->id . ')']);
            if ($orders->count() > 0) {
                
                
                
                $status =  wsActiveRecord::useStatic('Shoporderstatuses')->findById((int)$this->get->status);
                foreach ($orders as $order) {
                    if(/*$this->user->id == 8005*/ true){
                            if($order->getStatus() != $this->get->status){
                                $order->editStatus($this->get->status, $this->view, $this->user, $status); // смена статуса
                            }
        }else{
                    
                    if($order->getStatus() == 100 and in_array((int)$this->get->status, [9,15,16])){
                        $order->setAdmin($this->user->id);
                    }
                    
                    OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Смена статуса',  'C "'.$order->getStat()->name.'" на "'.$status->name.'"');
			
                    $st = $order->getStatus();
			
                if((int)$this->get->status == 5){ //Срок хранения заказа в магазине закончился
	$today = date('Y-m-d H:i:s', strtotime('-'.(int)Config::findByCode('count_dey_ban_samovyvos')->getValue().' days'));	
$ord = wsActiveRecord::useStatic('Shoporders')->findAll(array('customer_id' => $order->getCustomerId(), 'date_create >= "'.$today.'" ', 'flag'=>1));
//$count = count($or);
$ban = (int)Config::findByCode('ban_shop_count')->getValue()-1;
if($ord->count() >= $ban){
$or_list="";
foreach($ord as $r){
$or_list.=$r->getId().", ";
$r->setFlag(2);
$r->save();
}
$order->setFlag(2); 
$user = new Customer($order->getCustomerId());
$user->setBanAdmin($this->user->getId());
$user->setBanComment('Автобан по заказу ( '.$order->getId().'). Список заказов '.$or_list);
$user->setBlockM(1);
$user->setBanDate(date('Y-m-d H:i:s'));
$user->save();
wsLog::add('Автобан по заказу ( '.$order->getId().'). Список заказов '.$or_list, 'BAN');
}else{
$order->setFlag(1); 
}
	}
        
                    $order->setStatus((int)$this->get->status);//set status in order
                    $rez = $order->save();
                    
                    if ($rez) {
                        if ((int)$this->get->status == 2 or (int)$this->get->status == 7) {//Отменён,Возврат
                            OrderHistory::cancelOrder($order->id, $order->customer_id);
                            
                          /*  if($st == 3){
						if((int)$order->getDeliveryTypeId() == 5 and (int)$this->get->status == 7){
							 $order->setFlag($st);
							}
					}elseif($st == 8){
							if((int)$order->getDeliveryTypeId() == 5 and (int)$this->get->status == 7){
							 $order->setFlag($st);
							}
							}*/
							
                            foreach ($order->articles as $art) {
		if((int)$this->get->status == 7 and in_array($order->getDeliveryTypeId(), [3,5]) and $art->getCount() > 0){
									$article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $art->getArticleId(), 'id_size' => $art->getSize(), 'id_color' => $art->getColor()));
									if($article){
									if($art->getCount() > 1){
									for($i = 1; $i <= $art->getCount(); $i++){
									OrderHistory::newHistory($this->user->id, $order->getId(), 'Возврат товара', OrderHistory::getNewOrderArticle($art->getId()), $art->getArticleId());
									//OrderHistory::newHistory($this->user->getId(), $order->getOrderId(), 'Удаление товара на возврат', OrderHistory::getNewOrderArticle($c->getId()), $c->getArticleId());
									$artic = new ShoporderarticlesVozrat();
									$artic->setStatus(0);
									$artic->setOrderId($art->getOrderId());
									$artic->setArticleId($art->getArticleId());
									$artic->setCod($art->getArtikul());
									$artic->setTitle($art->getTitle());
									$artic->setCount(1);
									$artic->setPrice($art->getPrice());
									$artic->setCtime(date('Y-m-d H:i:s'));
									$artic->setUtime(date('0000-00-00 00:00:00'));
									$artic->setUser($this->user->getId());
									$artic->setDelivery($order->getDeliveryTypeId());
									$artic->setSize($art->getSize());
									$artic->setColor($art->getColor());
									$artic->setOldPrice($art->getOldPrice());
									if($order->getDeposit() > 0){
									$artic->setDeposit(1);
									}
									$artic->save();
									}							
									}else{
									OrderHistory::newHistory($this->user->id, $order->getId(), 'Возврат товара', OrderHistory::getNewOrderArticle($art->getId()), $art->getArticleId());
									$artic = new ShoporderarticlesVozrat();
									$artic->setStatus(0);
									$artic->setOrderId($art->getOrderId());
									$artic->setArticleId($art->getArticleId());
									$artic->setCod($art->getArtikul());
									$artic->setTitle($art->getTitle());
									$artic->setCount($art->getCount());
									$artic->setPrice($art->getPrice());
									$artic->setCtime(date('Y-m-d H:i:s'));
									$artic->setUtime(date('0000-00-00 00:00:00'));
									$artic->setUser($this->user->getId());
									$artic->setDelivery($order->getDeliveryTypeId());
									$artic->setSize($art->getSize());
									$artic->setColor($art->getColor());
									$artic->setOldPrice($art->getOldPrice());
									if($order->getDeposit() > 0){
									$artic->setDeposit(1);
									}
									$artic->save();
									}
									$art->setCount(0);
                                    $art->save();
									}
									}else{
                                    $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $art->getArticleId(), 'id_size' => $art->getSize(), 'id_color' => $art->getColor()));
				if($article){
					OrderHistory::newHistory($this->user->id, $art->getOrderId(), 'Отмена заказа', '', $art->getArticleId());
                                     
                                    $article->setCount($article->getCount() + $art->getCount());
                                    $article->save();
                                    $artic = new Shoparticles($art->getArticleId());
                                    $artic->setStock($artic->getStock() + $art->getCount());
                                    $artic->save();
                                                                                
									$art->setCount(0);
                                    $art->save();
									}
                                }
                                
                            }
                            $deposit = $order->getDeposit();
                            $order->setDeposit(0);
                            $order->save();
                            $customer = new Customer($order->getCustomerId());
                            $c_dep = $customer->getDeposit();
							$new_d = (float)$customer->getDeposit() + (float)$deposit;
                            $customer->setDeposit($new_d);
                            $customer->save();
			if($deposit > 0){
			OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Клиенту зачислен депозит ('.$deposit.') грн. ', 'C "' . $c_dep . '" на "' . $new_d . '"');
				$ok = '+';
			DepositHistory::newDepositHistory($this->user->getId(), $customer->getId(), $ok, $deposit, $order->getId());
                            }
                        }
                        
                        if ((int)$this->get->status == 8) {//Оплачен
			OrdersPay::newOrderPay($this->user->getId(), $order->getCustomerId(), $order->calculateOrderPrice(true, false), $order->getId());
                        }
                        
                        if (in_array($this->get->status, [3,4,6,13])) {//3-Доставлен в магазин, 4-Отправлен Укрпочтой, 6-Отправлен Новой почтой,13-В процессе доставки
                            $order->setOrderGo(date('Y-m-d H:i:s'));
                        $order->setDayOrderGo(time() - strtotime($order->getDateCreate()));
                        $order->save();
                        if($status->send_sms){
                            $phone = Number::clearPhone($order->getTelephone());
                            include_once('smsclub.class.php');
                            $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                            $sender = Config::findByCode('sms_alphaname')->getValue();

			if ((int)$this->get->status == 4 or (int)$this->get->status == 6) { 
                                    $user = $sms->sendSMS($sender, $phone, 'Zakaz №' . (int)$order->getId() . ' ' . $status->traslite . '. TTH №' . $order->getNakladna());
                                } else if((int)$this->get->status == 13){
                                    $user = $sms->sendSMS($sender, $phone, 'Zakaz №' . (int)$order->getId() . ' ' . $status->traslite . '. Dostavka:' . $order->getDeliveryDate());
				}else {
                                    $user = $sms->sendSMS($sender, $phone, 'Zakaz №' . (int)$order->getId() . ' ' . $status->traslite . '. Summa ' . $order->getAmount() . ' grn.');
                                }
				wsLog::add('SMS to user: ' . @$sms->receiveSMS($user), 'SMS_' . @$sms->receiveSMS($user));
                        }
				if(isValidEmailNew($order->getEmail()) and isValidEmailRu($order->getEmail()) and $status->send_email){
                                    $text = '';
                                if ((int)$this->get->status == 4) {
                                    $text .= '</br>Номер накладной: '.$order->getNakladna().' <br /> <a href="http://www.ukrposhta.com/www/upost.nsf/search_post?openpage">По этой ссылке</a> можно перейти и по номеру декларации можно отследить состояние посылки.';
                                }elseif((int)$this->get->status == 6) {
                                    $text .= '</br>Номер накладной: '.$order->getNakladna().' <br /> <a href="https://novaposhta.ua/tracking/?cargo_number='.$order->getNakladna().'">По этой ссылке</a> можно перейти и по номеру декларации можно отследить состояние посылки.';
                                }elseif ((int)$this->get->status == 13 and $order->delivery_type_id == 18) {
                                     $text .= '</br>Номер накладной: '.$order->getNakladna().' <br /> <a href="https://justin.ua/tracking-ttn/?ttn_number='.$order->getNakladna().'">По этой ссылке</a> можно перейти и по номеру ТТН можно отследить состояние посылки.';
					//$text .= 'Дата доставки: ' . $order->getDeliveryDate().' '. $order->getDeliveryInterval();
                                    }
                                    $subject = 'Изменения статуса заказа';
                                    $this->view->content = 'Ваш заказ № <a href="http://www.red.ua/account/orderhistory/">' . (int)$order->getId() . '</a> сменил статус на: ' . $status->name . '.' . $text;
                                    $msg = $this->view->render('mailing/template.tpl.php');
                                     EmailLog::add($subject, $msg, 'shop', $order->customer_id,  $order->id); //сохранение письма отправленного пользователю
                                    SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg);
				}
                            }
                        
                        if ((int)$this->get->status == 11) {
                            $order->setDelayToPay(date('Y-m-d'));
                            $order->save();
                        }elseif ((int)$this->get->status == 1) {
                            $order->setDateVProcese(date('Y-m-d'));
                            $order->save();
                        }
                    }
        }

                }

            }

        }
        $this->_redirect(@$_SERVER['HTTP_REFERER']);

    }

    public function ordersbyartycleAction()
    {
        if ($this->get->id and (int)$this->get->id > 0) {
            $q = " SELECT DISTINCT ws_orders.id,ws_orders.address, ws_orders.amount, ws_orders.city, ws_orders.comments, ws_orders.company, ws_orders.customer_id, ws_orders.date_create, ws_orders.date_modify, ws_orders.delivery_cost, ws_orders.delivery_type_id, ws_orders.email, ws_orders.flat, ws_orders.house, ws_orders.`index`,ws_orders.name, ws_orders.payment_method_id, ws_orders.pc, ws_orders.status, ws_orders.street, ws_orders.telephone, ws_order_articles.size, ws_order_articles.color, ws_order_articles.article_id  FROM ws_orders
INNER JOIN ws_order_articles on ws_orders.id =  ws_order_articles.order_id
INNER JOIN ws_articles on ws_order_articles.article_id = ws_articles.id
WHERE ws_articles.id = " . (int)$this->get->id." ORDER BY ws_orders.id DESC";
if($this->get->m){
$orders = wsActiveRecord::useStatic('Shoporders')->findByQuery($q);

$text = '
			<table class="table table-striped table-hover table-bordered table-condensed " style="font-size: 90%;">
				<tr>
					<th>Действие</th>
					<th>Статус</th>
					<th>Разм./Цвет</th>
					<th>№ заказа</th>
					<th>Дата</th>
					<th>Имя</th>
					<th>Колл.</th>
					<th>Доставка</th>
					<th>Скидка</th>
			</tr>';
			 foreach($orders as $or){
			  if(wsActiveRecord::useStatic('Shoporderarticles')->findFirst(array('order_id' => $or->getId(), 'article_id'=> $or->getArticleId(),  'size'=> $or->getSize(), 'color' => $or->getColor() ))->getCount() == 0){
                              
                             $st = 'style="background: rgba(255, 10, 10, 0.58);"';
                          
                          }else{
			  $st = '';
			  }
	$delivery = '';		  
if($or->getDeliveryTypeId() > 0) $delivery = $or->getDeliveryType()->getName();		  
			 $text.='<tr '.$st.' >
						<td><a href="/admin/shop-orders/edit/id/'.$or->getId().'"><img class="img_return" alt="Редактировать" src="/img/icons/edit-small.png"></a></td>
						<td>'.wsActiveRecord::useStatic('Shoporderstatuses')->findById($or->getStatus())->getName().'</td>
						<td>'.wsActiveRecord::useStatic('Size')->findById($or->getSize())->getSize(). '/'. wsActiveRecord::useStatic('Shoparticlescolor')->findById($or->getColor())->getName().'</td>
						<td>'.$or->getId().'</td>
						<td>'.date('d.m.Y H:i:s', strtotime($or->getDateCreate())).'</td>
						<td>'.$or->getName().'</td>
						<td>'.$or->getArticlesCount().'</td>
						<td>'.$delivery.'</td>
						<td>'.$or->getDiscont().' %</td>
					</tr>';
					
			 }
			 $text.='</table>';
			die($text);

}else{
            $this->view->orders = wsActiveRecord::useStatic('Shoporders')->findByQuery($q);
            echo $this->render('shop/orderbyarticle.tpl.php');
			}
        } else {
            $this->_redir('/');
        }
    }

    public function nowapochtaexelAction()//Excel Новая Почта
    {
        if ($this->get->id) {
            $mas = explode(',', $this->get->id);
            if (count($mas) > 0) {
                require_once('PHPExel/PHPExcel.php');
                $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in( ' . $this->get->id . ')'), array(), array());
                $name = 'orderexel';
                $filename = $name . '.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
                $aSheet->setCellValue('A1', '№ п/п');
                $aSheet->setCellValue('B1', 'Дата заказа');
                $aSheet->setCellValue('C1', 'Номер заказа');
                $aSheet->setCellValue('D1', 'ФИО');
                $aSheet->setCellValue('E1', 'Адрес');
                $aSheet->setCellValue('F1', 'Сумма');
                $aSheet->setCellValue('G1', 'Телефон');
                $aSheet->setCellValue('H1', 'Вид доставки');

                $i = 2;
                $assoc = array();
                foreach ($orders as $order) {
                    if ($order->getId() and $order->getName()) {
                        $d = new wsDate($order->getDateCreate());
                        $order_owner = new Customer($order->getCustomerId());
                        $price = 0;
                        $price_skidka = 0;
                        if ($order->getArticlesCount() != 0) {
                            $price = number_format((double)$order->getTotal('a'), 2, ',', '');
                            //$price_skidka = Number::formatFloat(((float)$order->getPriceWithSkidka() + (float)$order->getDeliveryCost()), 2);//ЕВЖЕНЯ
                            $price_skidka = $order->calculateOrderPrice2(false, false);
                        }
                        $aSheet->setCellValue('A' . $i, ($i - 1));
                        $aSheet->setCellValue('B' . $i, $d->getFormattedDateTime());
                        $aSheet->setCellValue('C' . $i, $order->getId());
                        $aSheet->setCellValue('D' . $i, $order->getFirstName() . ' ' . $order->getMiddleName());
                        $aSheet->setCellValue('E' . $i, $order->getAddress());
                        $aSheet->setCellValue('F' . $i, $price_skidka);
                        $aSheet->setCellValue('G' . $i, $order->getTelephone());
                        $aSheet->setCellValue('H' . $i, $order->getDeliveryType()->getName());
                        $i++;
                    }
                }
                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

                $objWriter->save('php://output');

            }

        }
        die();
    }

    public function ordercomplectAction()// Слияние заказов
    {
        if ($this->get->id) {
            $mas = explode(',', $this->get->id);
            if (count($mas) > 0) {

                $first_order = new Shoporders(max($mas));
//                $max_id_query = wsActiveRecord::useStatic('Shoporders')->findByQuery('SELECT MAX(id) FROM ws_orders');
//                $max_id = 0;
//                foreach($max_id_query as $item){
//                    $max_id = $item['m_a_x(id)'];
//                }
                if ($first_order->getId()) {
//                    $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in( ' . $this->get->id . ')', 'customer_id' => $first_order->getCustomerId(), 'id<>' . $first_order->getId()));
                    $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in( ' . $this->get->id . ')'));
                    if ($orders->count()) {
                        //проверить что бы все заказы были одного клиента и одного статуса
                        $customers = [];
			$status = [];
			$blok_complikt = [];
                        $dostup_status = [100,3];
                        $dostup_status_error = [];
                        foreach($orders as $one){
                            if(!in_array($one->status, $dostup_status)){
                                $dostup_status_error[] = 1;
                            }
                            $customers[$one->getCustomerId()] = 1;
                            $status[$one->getStatus()] = 1;
							
							//foreach ($one->getArticles() as $ar) {
							//if($ar->getOptionId()) { $blok_complikt['block'] = 1; $blok_complikt['error'] = 'Нельзя совмещать с заказом'.$ar->order_id.'.';}
							//}
			}
							
                        if(count($customers)!=1) {die('ОШИБКА: заказы разных клиентов!');}
			if(count($status)!=1) {die('ОШИБКА: нельзя совмещать заказы разных статусов!');}
                        if(count($dostup_status_error)){ die('ОШИБКА: Можно совмещать заказы, только в статусе "Новый" и "Доставлен в магазин"'); }
			if(count($blok_complikt)) {die($blok_complikt['error']); }

                        $new_order = new Shoporders();
                        $new_order->import($first_order);
                        //$new_order->setDeposit(0);
                        $new_order->setId('');
                        $new_order->setDateCreate('');
                       if($first_order->getComlpect()) { 
					  $new_order->setComlpect($first_order->getComlpect().$first_order->getId() . ';');
					   }else{ 
					   $new_order->setComlpect($first_order->getId() . ';');
					   } 
                        $new_order->setIsUnitedly(2);
                        if ($first_order->getBoxNumber()) {
                            $new_order->setBoxNumberC($first_order->getBoxNumber() . '( ' . $first_order->getId() . ');');
                        }
                        $new_order->save();
			wsLog::add('Linking order ' . $first_order->getId() . ' deposit ' . $first_order->getDeposit() . ' - with ' . $new_order->getId());
                        foreach ($first_order->articles as $article) {
OrderHistory::getGoArticle($this->user->getId(), $new_order->getId(), $first_order->getId(), $article->getTitle(), $article->artikul, $article->getPrice(), $article->article_id);
                            $article->setOrderIdOld($first_order->getId());
                            $article->setOrderId($new_order->getId());
                            $article->save(); 
                        } 
                        $new_order = new Shoporders($new_order->getId());
                        foreach ($orders as $order) {
						if($first_order->getId() != $order->getId()){ 
                            wsLog::add('Linking order ' . $order->getId() . ' deposit ' . $order->getDeposit() . ' - with ' . $new_order->getId());
                            if($order->getKupon()) { $new_order->setKupon($order->getKupon()); $new_order->setKuponPrice($order->getKuponPrice());}
                            $new_order->setComments($new_order->getComments() . ' ' . $order->getComments());
                            $new_order->setAmount($new_order->getAmount() + $order->getAmount());
                            $new_order->setDeposit($new_order->getDeposit() + $order->getDeposit());
                            $new_order->setBonus($new_order->getBonus() + $order->getBonus());
                            $new_order->setComlpect($new_order->getComlpect() . $order->getComlpect() . $order->getId() . ';');

                            if ($order->getBoxNumber()) {
                                $new_order->setBoxNumberC($new_order->getBoxNumberC() . $order->getBoxNumberC() . $order->getBoxNumber() . '( ' . $order->getId() . ');');
                            }
                            foreach ($order->articles as $article) {
	//OrderHistory::getGoArticle($this->user->getId(), $new_order->getId(), $order->getId(), $article->getTitle(), $article->getSize(), $article->getColor(), $article->getPrice());
    OrderHistory::getGoArticle($this->user->getId(), $new_order->getId(), $order->getId(), $article->getTitle(), $article->artikul, $article->getPrice(), $article->article_id);
                                $article->setOrderIdOld($order->getId());
                                $article->setOrderId($new_order->getId());
                                $article->save();
                            }
							}
                            foreach ($order->remarks as $remark) {
                                $remark->setOrderIdOld($order->getId());
                                $remark->setOrderId($new_order->getId());
				$remark->setDateCreate($remark->getDateCreate());
				$remark->setRemark($remark->getRemark());
				$remark->setName($remark->getName());
                                $remark->save();
                            }

                        }
			if($new_order->getDeliveryTypeId() == 9 and $new_order->getAmount() >= (int)Config::findByCode('kuryer_amount')->getValue()){ $new_order->setDeliveryCost(0);}
                       
                        $new_order->save();
						
						
                        OrderHistory::newHistory($this->user->getId(), $new_order->getId(), 'Совмещение заказов', $this->get->id);
						
                        //$orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in( ' . $this->get->id . ')'));
                        foreach ($orders as $order) {
			$s = $order->getStatus();
                            $order->setIsUnitedly(0);//1
                            $order->setStatus(17);
                            /*
                             * 1 - простой заказ, который стал частью совмещённого (нужно для скрытия-показа простых заказов)
                             * 2 - уже совмещённый заказ (нужно для удаления совмещённого заказа просле его зазбития на простые)
                             *
                             * */
                            $order->save();
	OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Смена статуса', OrderHistory::getStatusText($s, $order->getStatus()));	
                        }
                    }
                }
            }
        }
        $this->_redirect(@$_SERVER['HTTP_REFERER']);
    }

    public function orderuncomplectAction()// Разъединение заказов
    {
        if ($this->get->id) {
            $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in( ' . $this->get->id . ')'));
			//$orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id'=>$this->get->id));
            foreach($orders as $order){
			wsLog::add('UnLinking order ' . $order->getId());
                if($order->getIsUnitedly() == 2){   
                    /*
                             * 1 - простой заказ, который стал частью совмещённого (нужно для скрытия-показа простых заказов)
                             * 2 - уже совмещённый заказ (нужно для удаления совмещённого заказа просле его зазбития на простые)
                             *
                             * */
                    foreach ($order->articles as $article) {
                        $article->setOrderId($article->getOrderIdOld());
                        $article->save();
                        $show_order = wsActiveRecord::useStatic('Shoporders')->findAll(array('id' => $article->getOrderIdOld()));
                        foreach($show_order as $item){
                                    $s = $item->getStatus();
                                    $item->setStatus($order->getStatus());
                            $item->save();
							OrderHistory::newHistory($this->user->getId(), $item->getId(), 'Разделение заказа', $order->getId());	 
							OrderHistory::newHistory($this->user->getId(), $item->getId(), 'Смена статуса', OrderHistory::getStatusText($s, $order->getStatus()));	
                        }
                    } 
                    foreach ($order->remarks as $remark) {
                        $remark->setOrderId($remark->getOrderIdOld());
                        $remark->save();
                    }	
						$order->setStatus(17);
						$order->setDeposit(0);
						$order->setAmount(0);
                                                $order->setBonus(0);
						$order->setDeliveryCost(0);
                        $order->setIsUnitedly(1);
                        $order->save();
                    }
                }
            }
        $this->_redirect(@$_SERVER['HTTP_REFERER']);
    }

    public function allarticlesAction()//Все товары в заказах
    {
        $this->view->orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in (' . $this->get->id . ')'));
        $mast = array();
        if ($this->get->id) {
            $mas = explode(',', $this->get->id);
            if (count($mas) > 0) {
                $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery('
                SELECT ws_articles.*, ws_order_articles.title,ws_order_articles.size, ws_order_articles.color as artcolor, sum(ws_order_articles.count) as allcount FROM ws_articles
JOIN ws_order_articles ON ws_order_articles.article_id = ws_articles.id
WHERE ws_order_articles.order_id in(' . $this->get->id . ')
GROUP BY ws_order_articles.article_id,ws_order_articles.size, ws_order_articles.color
ORDER BY ws_articles.model ASC, ws_articles.brand ASC');


                foreach ($articles as $article) {
                    //   d($article->category->getRoutez());
                    $cat = explode(' : ', $article->category->getRoutez());
                    if ($cat[0] == 'РАСПРОДАЖА') {
                        $cat[0] = $cat[1];
                        if ($cat[0] == 'Женская одежда') $cat[0] = 'Женское';
                        if ($cat[0] == 'Мужская одежда') $cat[0] = 'Мужское';
                        if ($cat[0] == 'Женская обувь') $cat[0] = 'Обувь';
                        if ($cat[0] == 'Мужская обувь') $cat[0] = 'Обувь';
                    }
                    $mast[$cat[0]][] = $article;
                }
                ksort($mast);
            }
        }
        $this->view->ids = $this->get->id;
        $this->view->catarticle = $mast;
        echo $this->render('shop/market.tpl.php');
    }

    public function allarticlesExcelAction()//Экспорт товаров в заказах
    {
        $this->view->orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in (' . $this->get->id . ')'));
        $mast = array();
        if ($this->get->id) {
            $mas = explode(',', $this->get->id);
            if (count($mas) > 0) {
                $q = 'SELECT ws_articles.*,
                    ws_order_articles.size,
                    ws_order_articles.order_id,
                    ws_order_articles.count as allcount,
                     ws_order_articles.color as artcolor
                    FROM ws_articles
                    JOIN ws_order_articles ON ws_order_articles.article_id = ws_articles.id
                    WHERE ws_order_articles.order_id in(' . $this->get->id . ')
                     ORDER BY ws_articles.model ASC, ws_articles.brand ASC';
                $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($q);


                foreach ($articles as $article) {
                    $cat = explode(' : ', $article->category->getRoutez());
                    if ($cat[0] == 'РАСПРОДАЖА') {
                        $cat[0] = $cat[1];
                        if ($cat[0] == 'Женская одежда') $cat[0] = 'Женское';
                        if ($cat[0] == 'Мужская одежда') $cat[0] = 'Мужское';
                        if ($cat[0] == 'Женская обувь') $cat[0] = 'Обувь';
                        if ($cat[0] == 'Мужская обувь') $cat[0] = 'Обувь';
                    }
                    $mast[$cat[0]][] = $article;
                }
                ksort($mast);

                require_once('PHPExel/PHPExcel.php');
                $name = 'export_articles';
                $filename = $name . '.xls';
                $boldFont = array(
                    'font' => array(
                        'bold' => true
                    )
                );
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
                $aSheet->setCellValue('A1', 'Товар');
                $aSheet->setCellValue('B1', 'Бренд');
                $aSheet->setCellValue('C1', 'Размер');
                $aSheet->setCellValue('D1', 'Цвет');
                $aSheet->setCellValue('E1', 'Количество');
                $aSheet->setCellValue('F1', 'Артикул');
                $aSheet->setCellValue('G1', 'Цена');
                $aSheet->setCellValue('H1', '№ заказа');
                $aSheet->setCellValue('I1', 'Тип доставки');

                $aSheet->getStyle('A1')->applyFromArray($boldFont);
                $aSheet->getStyle('B1')->applyFromArray($boldFont);
                $aSheet->getStyle('C1')->applyFromArray($boldFont);
                $aSheet->getStyle('D1')->applyFromArray($boldFont);
                $aSheet->getStyle('E1')->applyFromArray($boldFont);
                $aSheet->getStyle('F1')->applyFromArray($boldFont);
                $aSheet->getStyle('G1')->applyFromArray($boldFont);
                $aSheet->getStyle('H1')->applyFromArray($boldFont);
                $aSheet->getStyle('I1')->applyFromArray($boldFont);

                $i = 2;

                $cat = '';
                $count = 0;
                $count_ucen = 0;
                foreach ($mast as $kay => $val) {
                    $order = new Shoporders($article->order_id);
                    if ($cat != $kay) {
                        $cat = $kay;
                        $aSheet->setCellValue('A' . $i, $cat);

                        $aSheet->getStyle('A' . $i)->applyFromArray($boldFont);
                        $i++;
                    }
                    ini_set('memory_limit', '2000M');
                    set_time_limit(2800);
                    foreach ($val as $article) {
                        if ($article->allcount > 0) {
                            $size = new Size($article->size);
                            $color = new Shoparticlescolor($article->artcolor);
                            $sz = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article->id, 'id_size' => $article->size, 'id_color' => $article->artcolor));
                            $aSheet->setCellValue('A' . $i, $article->model);
                            $aSheet->setCellValue('B' . $i, $article->brand);
                            $aSheet->setCellValue('C' . $i, $size->getSize());
                            $aSheet->setCellValue('D' . $i, $color->getName());
                            $aSheet->setCellValue('E' . $i, $article->allcount);
                            $aSheet->setCellValue('F' . $i, $sz->code);
                            $aSheet->setCellValue('G' . $i, $article->getPrice());
                            $aSheet->setCellValue('H' . $i, $article->order_id);
                            $aSheet->setCellValue('I' . $i, str_replace('Самовывоз ', '', $order->delivery_type->getName()));

                            $i++;
                        }
                    }

                }
                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");


                $objWriter->save('php://output');


                //header("Content-type: application/x-msexcel");
                die();


            }
        }

    }

    public function nowamailAction()// отаправка счета и письма - не можем связаться
    {
        if($this->post->metod == 'gel_email_customer'){
            $id = $this->post->id;
           $res =  EmailLog::getListEmail($id, 'customer');
            die(json_encode(array('status'=>'ok',  'message'=>$res)));
        }elseif($this->post->metod == 'gel_email_load_form'){
            $msg = file_get_contents((INPATH.$this->post->file));
            die(json_encode($msg));
        }elseif ($this->post->metod == 'getmail'){
	$status='';
	$error = false;
	$message = '';
	$order = new Shoporders((int)$this->post->id);
	  if ($order->getId()) {
	  
				$this->view->name = $order->getName();
				$this->view->email = $order->getEmail();
			$subject = $this->post->subject;
			$this->view->content = '<div style="margin: 10px;padding:5px;">'.$this->post->message.'</div>';			
			$msg = $this->view->render('email/template.tpl.php');
                         EmailLog::add($subject, $msg, 'shop', $order->customer_id,  $order->id); //сохранение письма отправленного пользователю
			$res = SendMail::getInstance()->sendEmail($this->view->email, $this->view->name, $subject, $msg);
	  
	  if($res == true){
	  $status ='send';
	   $message = 'Сообщение успешно отправлено';
		$remark = new Shoporderremarks();
        $data = array(
                    'order_id' => $order->getId(),
                    'date_create' => date("Y-m-d H:i:s"),
                    'remark' => 'Email - '.$this->post->subject,
					'name' => $this->user->getMiddleName()
                );
                $remark->import($data);
                $remark->save();	   
	  }else{
	   $status ='error';
	  $error = true;
	  $message = 'Сообщение не отправлено((( Попробуте еще раз.';
	  }
	  }else{
	  $status ='error';
	  $error = true;
	  $message = 'Ошибка в номере заказа(((';
	  }
	die(json_encode(array('status'=>$status, 'error'=>$error, 'message'=>$message)));
	}elseif ($this->post->metod == 'getcall') {
	//$id = $this->post->id;
        $order = new Shoporders($this->post->id);
        if ($order->getId()) {
			$subject = 'С Вами не удалось связаться по заявке №' . $order->getQuickNumber();
		  $text = '
		  <table border="0" cellpadding="5" cellspacing="0" width="700" align="center" style="background:#fff;border-collapse:collapse;">
		  <tr>
		  <td>
		  Здравствуйте, '. $order->getName().'.
		</td>
		</tr>
		<tr>
		  <td>
  Мы не смогли связаться с Вами по телефону:<br>
</td>
		</tr>
		<tr>
		  <td>
  <font size="4" color="red" face="Arial"><b>'.$order->getTelephone().'</b></font><br>
</td>
		</tr>
		<tr>
		  <td>
  <b>Пожалуйста, отправте нам сообщение на market@red.ua, в котором укажите номер заявки, актуальный номер своего телефона и время, когда Вам удобно разговаривать</b> — наш менеджер перезвонит Вам для подтверждения заказа.
</td>
		</tr>
		<tr>
		  <td>
  Также Вы можете связаться с нами по телефонам:</br>
  (044) 224-40-00, (063) 809-35-29, (067) 406-90-80
</td>
		</tr>
</table>'; 
if(isValidEmailNew($order->getEmail()) and isValidEmailRu($order->getEmail())){

            $this->view->name = $order->getName();
            $this->view->email = $order->getEmail();
			
			$this->view->content = $text;			
			$msg = $this->view->render('email/template.tpl.php');
                         EmailLog::add($subject,  $msg, 'shop', $order->customer_id, $order->id); //сохранение письма отправленного пользователю
			$res = SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg);
                        
                        $order->setCallMail(date('Y-m-d H:i:s'));
                        $order->save();
			$mes='письмо отправлено: ' . date('d-m-Y', strtotime($order->getCallMail()));
            die($mes);
}else{
    die('Ошибка отправки');
}
			
 
		}else{ die('error');}
	
	}elseif(false){
	
        $order = new Shoporders($this->post->id);
		
        if ($order->getId()) {

            $month_orig = array('01' => 'січень', '02' => 'лютий', '03' => 'березень', '04' => 'квітень', '05' => 'травень', '06' => 'червень',
                '07' => 'липень', '08' => 'серпень', '09' => 'вересень', '10' => 'жовтень', '11' => 'листопад', '12' => 'грудень'
            );
            $month = array('01' => 'января', '02' => 'Февраля', '03' => 'марта', '04' => 'апреля', '05' => 'мая', '06' => 'июня',
                '07' => 'июля', '08' => 'августа', '09' => 'сентября', '10' => 'октября', '11' => 'ноября', '12' => 'декабря'
            );
            $dt = explode('-', substr($order->getDateCreate(), 0, 10));
            $this->view->date = $dt[2] . ' ' . $month[$dt[1]] . ' ' . $dt[0];
            $this->view->exploded_date = $dt;
            $this->view->order = $order;
            $customer_id = $order->getCustomerId();

            $all_orders = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery('
            SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
			WHERE ws_orders.customer_id = ' . $customer_id . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0);
            $all_orders_2 = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery('
			        SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
			        WHERE ws_orders.customer_id = ' . $customer_id . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) AND ws_orders.id <=' . $this->post->id)->at(0);

            $this->view->all_orders_amount = $all_orders->getAmount();
            $this->view->all_orders_amount_total = $all_orders_2->getAmount();

            $this->view->shet = $this->render('', 'order/novap_mail.tpl.php');

		   $subject = 'Счет к заказу №' . $order->getId();
            $this->view->name = $order->getName();
            $this->view->email = $order->getEmail();

            $msg = $this->view->render('email/nowa_mail.tpl.php');
			
			SendMail::getInstance()->sendEmail($this->view->email, $this->view->name, $subject, $msg);

            $order->setNowaMail(date('Y-m-d H:i:s'));

            $order->save();
            die('счет отправлен: ' . date('d-m-Y', strtotime($order->getNowaMail())));


        } else die('error');
		}
    }
    public function userwhithokAction()//Excel Список пользователей
    {
        require_once('PHPExel/PHPExcel.php');


        $orders = wsActiveRecord::useStatic('Shoporders')->findByQuery('SELECT customer_id FROM ws_orders WHERE oznak=1 and soglas=1 GROUP BY customer_id');
        $mas = array();
        foreach ($orders as $user) {
            $mas[] = $user->getCustomerId();
        }
        ini_set('memory_limit', '2000M');
        set_time_limit(2800);
        if ($this->get->ok == 1) {
            $users = wsActiveRecord::useStatic('Customer')->findAll(array('id in (' . implode(',', $mas) . ')'), array('first_name' => 'ASC'));
        } else {

            $users = wsActiveRecord::useStatic('Customer')->findAll(array('id not in (' . implode(',', $mas) . ')'), array('first_name' => 'ASC'));
        }
        $name = 'usersexel';
        $filename = $name . '.xls';

        $pExcel = new PHPExcel();
        $pExcel->setActiveSheetIndex(0);
        $aSheet = $pExcel->getActiveSheet();
        $aSheet->setTitle('Первый лист');
        $aSheet->setCellValue('A1', '№ п/п');
        $aSheet->setCellValue('B1', 'Имя');
        $aSheet->setCellValue('C1', 'Фамилия');
        $aSheet->setCellValue('D1', 'Е-мейл');
        $aSheet->setCellValue('E1', 'Телефон');
        $aSheet->setCellValue('F1', 'Адрес');
        $aSheet->setCellValue('G1', 'Скидка');
        $aSheet->setCellValue('H1', 'Заказов');
        $aSheet->setCellValue('I1', 'Соглашение');


        $i = 2;

        foreach ($users as $user) {

            $aSheet->setCellValue('A' . $i, ($i - 1));
            $aSheet->setCellValue('B' . $i, $user->getFirstName());
            $aSheet->setCellValue('C' . $i, $user->getMiddleName());
            $aSheet->setCellValue('D' . $i, $user->getEmail());
            $aSheet->setCellValue('E' . $i, $user->getPhone1());
            $aSheet->setCellValue('F' . $i, $user->getAdress());
            $aSheet->setCellValue('G' . $i, $user->getDiscont());
            $aSheet->setCellValue('H' . $i, wsActiveRecord::useStatic('Shoporders')->count(array('customer_id' => $user->getId())));
            $aSheet->setCellValue('I' . $i, $user->isUserTerms() ? date('d-m-Y', strtotime($user->isUserTerms()))
                : 'Нет');

            $i++;

        }
        require_once("PHPExel/PHPExcel/Writer/Excel5.php");
        $objWriter = new PHPExcel_Writer_Excel5($pExcel);

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-Disposition: attachment; filename=\"" . $filename . "\"");


        $objWriter->save('php://output');


       // header("Content-type: application/x-msexcel");
        die();
    }

    //-------Brands
    public function brandsAction()
    {
        if($this->post->method == "set_greyd"){
            $param = [];
            $param['greyd'] = (int)$this->post->greyd;
            if($this->post->dell){
                $param['hide'] = 0;
            }
          $res = Brands::BrandEdit($this->post->id, $param);
            die($res);
        }
             $this->view->brands = Brands::getAllBrands(2000);
      
       
        echo $this->render('brand/list.tpl.php', 'index.php');
    }

    public function brandAction()
    {

        if ($this->cur_menu->getParameter() == 'edit') {
            $sub = new Brand($this->get->getId());

            if (count($_POST)) {
                foreach ($_POST as &$value){$value = stripslashes($value);}
                
                $errors = [];

                if (!$_POST['name']){ $errors[] = $this->trans->get('Please fill name');}
                
                
                if (!count($errors)) {
                    $sub->setTop(0);
                $sub->import($_POST);
                    if ($_FILES['image']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['image'], 'ru_RU');
                        $folder = '/storage/brands/';
                        if ($handle->uploaded) {
                            if (!count($errors)) {
                                $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                                if ($handle->processed) {
                                    if ($sub->getImage())
                                        unlink($sub->getImage());
                                    $sub->setImage($folder.$handle->file_dst_name);
                                    $handle->clean();
                                }
                            }
                        }
                    }
                    if ($_FILES['logo']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['logo'], 'ru_RU');
                        $folder = '/storage/brands/';
                        if ($handle->uploaded) {
                            if (!count($errors)) {
                                $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                                if ($handle->processed) {
                                    if ($sub->getlogo())
                                        unlink($sub->getLogo());
                                    $sub->setLogo($folder . $handle->file_dst_name);
                                    $handle->clean();
                                }
                            }
                        }
                    }
                
                    $sub->save();
                    //foreach ($sub->articles as $article) {
                      //  $article->setBrand($sub->getName());
                      //  $article->save();
                  //  }
                    $this->view->saved = 1;
                    //unset($this->view->errors);
                } else {
                    $this->view->errors = $errors;
                }

                $this->view->errors = $errors;
            }
            $this->view->sub = $sub;
            echo $this->render('brand/edit.tpl.php', 'index.php');

        } elseif ($this->cur_menu->getParameter() == 'delete') {
            if ($this->get->getId()) {
                $n = new Brand($this->get->getId());
                if (!$n->articles->count()) {
                    @unlink($n->getImage());
                    @unlink($n->getLogo());
                    $n->destroy();
                }
            }
            $this->_redir('brands');

        } else
            $this->_redir('brands');

    }

    public function exelkurerAction()
    {
        $order_status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
        if ($this->get->id) {
            $mas = explode(',', $this->get->id);
            if (count($mas) > 0) {
                require_once('PHPExel/PHPExcel.php');
                $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in( ' . $this->get->id . ')'), array(), array());
				$dd = date("d.m.Y");
                $name = 'exelkurer('.$dd.')';
                $kount = 1;
                $filename = $name . '.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                
				$pExcel->getDefaultStyle()->getFont()->setSize(8);				
				$pExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Доставка курьером');
				
				$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
				$aSheet->getPageSetup()->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
			
				$aSheet->getPageMargins()->setTop(0.75);
				$aSheet->getPageMargins()->setRight(0.26);
				$aSheet->getPageMargins()->setLeft(0.26);
				$aSheet->getPageMargins()->setBottom(0.75);

                $aSheet->getColumnDimension('A')->setWidth(4);
                $aSheet->getColumnDimension('B')->setWidth(10);
                $aSheet->getColumnDimension('C')->setWidth(20);
                $aSheet->getColumnDimension('D')->setWidth(15);
                $aSheet->getColumnDimension('E')->setWidth(15);
                $aSheet->getColumnDimension('F')->setWidth(25);
                $aSheet->getColumnDimension('G')->setWidth(10);
                $aSheet->getColumnDimension('H')->setWidth(15);
                $aSheet->getColumnDimension('I')->setWidth(15);
                $aSheet->getColumnDimension('J')->setWidth(10);
                $aSheet->getColumnDimension('K')->setWidth(15);
				
				$aSheet->getRowDimension(1)->setRowHeight(-1);
		
				$aSheet->setCellValue('A1', '№ п/п');
                $aSheet->setCellValue('B1', 'Номер заказа');
                $aSheet->setCellValue('C1', 'Контактное лицо');
                $aSheet->setCellValue('D1', 'Телефон');
                $aSheet->setCellValue('E1', 'Город');				
                $aSheet->setCellValue('F1', 'Адрес доставки');
                $aSheet->setCellValue('G1', 'Дата доставки');
				$aSheet->setCellValue('H1', 'Время доставки');
                $aSheet->setCellValue('I1', 'Описание товара');
                $aSheet->setCellValue('J1', 'Сумма к оплате');				
                $aSheet->setCellValue('K1', 'Сумма страховки');
	        
                $boldFont = array(
                    'font' => array(
                        'bold' => true,
                    ),
					'alignment' => array(
						'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
					),
					'fill' => array(
						'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
						'color'=>array(
							'rgb' => 'CFCFCF'
						)
					)
                );
                $aSheet->getStyle('A1')->applyFromArray($boldFont);
                $aSheet->getStyle('B1')->applyFromArray($boldFont);
                $aSheet->getStyle('C1')->applyFromArray($boldFont);
                $aSheet->getStyle('D1')->applyFromArray($boldFont);
                $aSheet->getStyle('E1')->applyFromArray($boldFont);
                $aSheet->getStyle('F1')->applyFromArray($boldFont);
                $aSheet->getStyle('G1')->applyFromArray($boldFont);
                $aSheet->getStyle('H1')->applyFromArray($boldFont);
                $aSheet->getStyle('I1')->applyFromArray($boldFont);
                $aSheet->getStyle('J1')->applyFromArray($boldFont);
                $aSheet->getStyle('K1')->applyFromArray($boldFont);

                $i = 2;
                $assoc = array();
                foreach ($orders as $order) {
                    if ($order->getId() and $order->getName()) {

                        $price_skidka = 0;
                        if ($order->getArticlesCount() != 0) {
                           // $price = number_format((double)$order->getTotal('a'), 2, ',', '');
                            //$price_skidka = Number::formatFloat(((float)$order->getPriceWithSkidka() + (float)$order->getDeliveryCost() - (float)$order->getDeposit()), 2);//Yarik 
							if($order->getPaymentMethodId() == 4 or $order->getPaymentMethodId() == 8 or $order->getPaymentMethodId() == 6){
							$price_skidka = 0;
							}else{
							 $price_skidka = $order->calculateOrderPrice2(true, false, true);
							 }
							
                        }
                        $aSheet->setCellValue('A' . $i, $kount);					//	№ п/п
                        $aSheet->setCellValue('B' . $i, $order->getId());			//	Номер заказа
                        $aSheet->setCellValue('C' . $i, $order->getMiddleName().' '.$order->getName());			//	Контактное лицо
                        $aSheet->setCellValueExplicit('D' . $i, $order->getTelephone(), PHPExcel_Cell_DataType::TYPE_STRING);	//	Телефон
                        $aSheet->setCellValue('E' . $i, 'Киев');					//	Город
                        $aSheet->setCellValue('F' . $i, $order->getAddress());		//	Адрес доставки
                        $aSheet->setCellValue('G' . $i, date('d.m.y', strtotime($order->getDeliveryDate())));		//	Дата доставки
						$aSheet->setCellValue('H' . $i, $order->getDeliveryInterval());		//	Время доставки
                       // $aSheet->setCellValue('I' . $i, $order->getNakladna());				
                        $aSheet->setCellValue('I' . $i, '');						//	Описание товара
                        $aSheet->setCellValue('J' . $i, $price_skidka);				//	Сумма к оплате
                        $aSheet->setCellValue('K' . $i, ceil((float)$price_skidka + (float)$order->getDeposit()));			//	Сумма страховки
                        $kount++;
                        $i++;
                    }
                }
                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);
				
				if($this->get->flag){
				$path1file = INPATH . "backend/views/trekko/". $filename;
				if (file_exists($path1file)){
						if (unlink($path1file)) $objWriter->save($path1file);
						}else{
						$objWriter->save($path1file);
						}
						
						$email = 'oba@red.ua';
						//$email = 'php@red.ua';
						$name = 'Баранецкая О.';
						//$admin_email = Config::findByCode('admin_email')->getValue();
                        $admin_name = $this->user->getFirstName().' '.$this->user->getMiddleName();
			$subject = 'Курьерская заявка за '.date("d.m.Y");
                        $this->view->name = $name;
                        $this->view->email = $email;
			$msg = $this->view->render('trekko/kurer-email.tpl.php');
			SendMail::getInstance()->sendEmail($email, $admin_name, $subject, $msg, $path1file, $filename);
                         
                     // MailerNew::getInstance()->sendToEmail($email, $admin_name, $subject, $msg, $new = 0, '', $admin_name, 1,  0,  0, $path1file, $filename);
					 die(json_encode("Письмо отправлено!"));
				}else{
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
                $objWriter->save('php://output');
				}
            }
        }
        die();
    }

    public function exelarticlesAction(){ //
        if ($this->get->id) {
			
            $mas = explode(',', $this->get->id);
			
            if (count($mas) > 0) {
              //  
				
                $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array(' id in( ' . $this->get->id . ')'), array('id'=>'ASC'));
				//$orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in (' . $this->get->id . ')'));
				//echo print_r($orders);
			//die();
				require_once('PHPExel/PHPExcel.php');
                $filename = '1C_'.$this->user->middle_name.'_'.date("d.m.Y H-i-s").'.xls';	
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
                $aSheet->getColumnDimension('A')->setWidth(18);
                $aSheet->getColumnDimension('B')->setWidth(5);
                $i = 1;
                foreach ($orders as $order) {
                    if ($order->getId()) {
                        foreach ($order->articles as $article) {
                            if ($article->getCount() > 0) {
                              // $aSheet->setCellValue('A' . $i, wsActiveRecord::findByQueryFirstArray("SELECT code from ws_articles_sizes where id_article ='$article->article_id' and id_size = '$article->size' and  id_color = '$article->color'")['code']);
							 $aSheet->setCellValue('A' . $i, $article->getArtikul());
							 $aSheet->setCellValue('B' . $i, $article->getCount());
                    $i++;
                            }

                        }
                    }

                }
                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

                $objWriter->save('php://output');
            }

        }
        die();
    }

//	2517
    public function edithistoryAction()
    {
        $this->view->admins = wsActiveRecord::useStatic('Customer')->findAll('customer_type_id > 2');
/*
        $date = array();
        if ((int)$this->get->customer > 0) $date['customer_id'] = (int)$this->get->customer;
        if ($this->get->from) {
            $date[] = 'ctime >= "' . date('Y-m-d 00:00:00', strtotime($this->get->from)) . '"';
        }
        if ($this->get->to) {
            $date[] = 'ctime <= "' . date('Y-m-d 23:59:59', strtotime($this->get->to)) . '"';
        }
*/
		$where = 'WHERE 1=1';
		if ((int)$this->get->customer > 0) $where .= ' AND customer_id = '.(int)$this->get->customer;
        if ($this->get->from) {
			$where .= ' AND ctime >= "'. date('Y-m-d 00:00:00', strtotime($this->get->from)) .'"';
        }
        if ($this->get->to) {
			$where .= ' AND ctime <= "'. date('Y-m-d 23:59:59', strtotime($this->get->to)) .'"';
        }
//        $this->view->logs = wsActiveRecord::useStatic('Shoparticlelog')->findAll($date, array(), array('5'));

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//        $this->view->logs = wsActiveRecord::useStatic('OrderHistory')->findAll($date, array(), array('5'));

//      echo $this->render('shop/orderlog.tpl.php');

		$sql = "
			SELECT
				LOG.*,
				ART.brand BRA,
				ART.model MDL,
				CUS.username USR,
				CONCAT(CUS.first_name, ' ', CUS.middle_name) FIO
			FROM (
				(
					SELECT DISTINCT
					   ctime,
					   customer_id,
					   '' order_id,
					   article_id,
					   coments info
					FROM
						red_article_log
					".$where."
					ORDER BY
						ctime DESC
					LIMIT
						100
				)
				UNION
				(
					SELECT DISTINCT
					   ctime,
					   customer_id,
					   order_id,
					   article_id,
					   CONCAT(name, ' ', info)
					FROM
						order_history
					".$where."
					ORDER BY
						ctime DESC
					LIMIT
						100
				)
			) LOG
			LEFT JOIN
				ws_customers CUS
				ON CUS.id = LOG.customer_id
				LEFT JOIN ws_articles ART
				ON ART.id = LOG.article_id
			ORDER BY
				LOG.ctime DESC
			LIMIT
				100
		";
		$this->view->logs = wsActiveRecord::findByQueryArray($sql);

		echo $this->render('shop/articlelog_big.tpl.php');
    }

    public function adddopcatAction()
    {
        if (isset($_GET['cat']) and isset($_GET['ids'])) {
            $arts = wsActiveRecord::useStatic('Shoparticles')->findAll(array('id in(' . $_GET['ids'] . ')'));
            foreach ($arts as $art) {
                $art->setDopCatId($_GET['cat']);
                $art->save();
            }
            $this->_redirect($_SERVER["HTTP_REFERER"]);
        } else {

        }
    }
	


    public function payorderAction()
    {
        if (isset($_GET['pay']) and isset($_GET['ids']) and isset($_GET['customer'])) {
            OrdersPay::newOrderPay($this->user->getId(), (int)$_GET['customer'], (float)$_GET['pay'], $_GET['ids']);
            $this->_redirect($_SERVER["HTTP_REFERER"]);
        } else {

        }

    }

    public function callingAction()
    {
        $order = new Shoporders($this->get->order);
        if ($this->get->type == 'first') {
            $order->setFirst(2);
        }
        if ($this->get->type == 'otmena') {
            $order->setFirst(3);
            include_once('smsclub.class.php');
            $phone = Number::clearPhone($order->getTelephone());
            $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
            $sender = Config::findByCode('sms_alphaname')->getValue();
            $user = $sms->sendSMS($sender, $phone, 'Vash zakaz №' . $order->getId() . '. Dlja utochnenija detalej svjazhites s nami. (044) 224-40-00');
            wsLog::add('SMS to user: ' . $sms->receiveSMS($user), 'SMS_' . $sms->receiveSMS($user));
        } else {
            $order->setCallMy(0);
            OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Заказ уточнен у заказчика', '');
        }
        $order->save();

        $this->_redirect($_SERVER["HTTP_REFERER"]);
    }

    public function usedepositAction()
    {
        $order = new Shoporders($this->get->id);
        $order->updateDeposit($this->user->getId(), false);
        $this->_redirect($_SERVER["HTTP_REFERER"]);
    }
    public function updatebonusAction()
    {
        $order = new Shoporders($this->get->id);
        $order->updateBonus($this->user->getId());
        $this->_redirect($_SERVER["HTTP_REFERER"]);
    }

    public function unusedepositAction()
    {
        $order = new Shoporders($this->get->id);
        if ($order->getId()) {
            $customer = new Customer($order->getCustomerId());
            $sum = $order->getDeposit();
            $customer->setDeposit($customer->getDeposit() + $sum);
            $order->setDeposit(0);
            $customer->save();
            $order->save();
            OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Снят депозит. Депозит сменился ',
                'C "' . $sum . '" на "' . $order->getDeposit() . '"');
				$ok = '+';
				DepositHistory::newDepositHistory($this->user->getId(), $customer->getId(), $ok, $sum, $order->getId());
        }
        $this->_redirect($_SERVER["HTTP_REFERER"]);
    }

    public function ucenkaAction()
    {
        if ($_GET['revert-auto-ucenka'] == true) {
            try {
                //формуємо автоматичні уцінки
                $sql = "SELECT d1.* FROM ucenka_history d1
                  JOIN (SELECT MIN(id) AS id FROM ucenka_history where `admin_id` = 8005 GROUP BY article_id ORDER BY NULL)
                  d2 USING (id)";
                $ucenki = wsActiveRecord::findByQueryArray($sql);
                $ucenki = array_chunk($ucenki, 1000);
                foreach ($ucenki as $tab) {
                    $ucenkiAuto = json_decode(json_encode($tab), true);
                    $ucenkiAuto = array_column($ucenkiAuto, null, 'article_id');
                    $ids = array_column($ucenkiAuto, 'article_id');
                    $ids = implode(', ', $ids);
                    $sql = "SELECT d1.* FROM ucenka_history d1
                      JOIN (SELECT MAX(id) AS id FROM ucenka_history where `admin_id` != 8005 and article_id in ($ids) GROUP BY article_id ORDER BY NULL)
                      d2 USING (id)";
                    //формуємо мануальні уцінки
                    $ucenkiManual = wsActiveRecord::findByQueryArray($sql);
                    $ucenkiManual = json_decode(json_encode($ucenkiManual), true);
                    $ucenkiManual = array_column($ucenkiManual, null, 'article_id');

                    //відбираємо товар, зв’язаний з уцінкою
                    $sql = "select id, old_price, price, ucenka from ws_articles where id IN ($ids)";
                    $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
                    foreach ($articles as $article) {
                        if (isset($ucenkiAuto[$article->id]) && !isset($ucenkiManual[$article->id])) {
                            $article->setPrice($ucenkiAuto[$article->id]['old_price']);
                            $article->setOldPrice(0);
                            $article->setUcenka(0);
                        } else if (isset($ucenkiManual[$article->id])) {
                            $article->setPrice($ucenkiManual[$article->id]['new_price']);
                            $article->setOldPrice($ucenkiManual[$article->id]['old_price']);
                            $article->setUcenka($ucenkiManual[$article->id]['proc']);
                        }
                            $article->save();
                    }
                }

            } catch (Exception $e) {
                var_dump($e->getMessage());
                die;
            }
        }

if($this->post->ucenka_id){
$result = array();
//$id = explode(',', $this->post->ucenka_id);
$id = $this->post->ucenka_id;

switch((int)$this->post->usenka_id_proc){
				 case 10: $proc = 0.9; break;
				 case 15: $proc = 0.85; break;
				 case 20: $proc = 0.8; break;
				 case 25: $proc = 0.75; break;
				 case 30: $proc = 0.7; break;
				 case 35: $proc = 0.65; break;
				 case 40: $proc = 0.6; break;
				 case 45: $proc = 0.55; break;
				 case 50: $proc = 0.5; break;
				 case 60: $proc = 0.4; break;
				 case 70: $proc = 0.3; break;
				 case 90: $proc = 0.1; break;
					default: $proc = 1;
				}
$result['proc'] = $proc;

$articles_ucen = wsActiveRecord::useStatic('Shoparticles')->findAll(array('id in('.$id.')'));
                $i = 0;
				
foreach ($articles_ucen as $art) {

 if ($art->getOldPrice() == 0){
	$art->setDopCatId($art->category->getUsencaCategory());
	$s_p = $art->getPrice();
	$art->setOldPrice($art->getPrice());
 }else{
 $s_p = $art->getOldPrice();
 }
 
					if(@$this->post->skidka_block) $art->setSkidkaBlock(1);
					
					if(@$this->post->block_ucenka_end == 1) $art->setLabelid(0);
					
                    $art->setPrice(ceil($art->getOldPrice() * $proc));
					$art->setDataUcenki(date('Y-m-d H:i:s'));
					$art->setUcenka((int)$this->post->usenka_id_proc);
                   $art->save();
					
UcenkaHistory::newUcenka($this->user->getId(), $art->getId(), $s_p, $art->getPrice(), (int)$art->getStock(), (int)$this->post->usenka_id_proc);
                    $i++;
					$result['id'][] = $art->getId();
                }
				
$result['uceneno'] = $i;


die(json_encode($result));
}
       $this->view->categories = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1));

            $order_by = 'ctime';
            $order_by_type = 'DESC';
            if (isset($_GET['sort']) and strlen($_GET['sort']) > 0) {
                if ($_GET['sort'] == 'dateplus') {
                    $order_by = 'ctime';
                    $order_by_type = 'DESC';
                }
                if ($_GET['sort'] == 'dateminus') {
                    $order_by = 'ctime';
                    $order_by_type = 'ASC';
                }
                if ($_GET['sort'] == 'priceplus') {
                    $order_by = 'price';
                    $order_by_type = 'DESC';
                }
                if ($_GET['sort'] == 'priceminus') {
                    $order_by = 'price';
                    $order_by_type = 'ASC';
                }
                if ($_GET['sort'] == 'viewsplus') {
                    $order_by = 'views';
                    $order_by_type = 'DESC';
                }
                if ($_GET['sort'] == 'viewsminus') {
                    $order_by = 'views';
                    $order_by_type = 'ASC';
                }
                if ($_GET['sort'] == 'category') {
                    $order_by = 'category_id';
                    $order_by_type = 'ASC';
                }
                if ($_GET['sort'] == 'brandaz') {
                    $order_by = 'barnd';
                    $order_by_type = 'ASC';
                }
                if ($_GET['sort'] == 'brandza') {
                    $order_by = 'barnd';
                    $order_by_type = 'DESC';
                }
                if ($_GET['sort'] == 'ucenkazr') {
                    $order_by = 'ucenka';
                    $order_by_type = 'ASC';
                }
                if ($_GET['sort'] == 'ucenkasp') {
                    $order_by = 'ucenka';
                    $order_by_type = 'DESC';
                }
            }
			
            $data = array();
			
			$where = ' stock not like "0" AND skidka_block <> 1 and active = "y" ';
			
            $interval_to = 0;//do
			$interval_n = 0;//ot
			
			
/*
29 дней - 30%
58 дней - 50%
87 дней - 70%
166 дней - 90%
*/
if(!empty($_GET['block_ucenka'])){
$where .= ' and labelid > 0 ';
}


            if (@$_GET['proc'] and $_GET['proc'] != 1) {
			switch((int)$_GET['proc']){
			case 20: $interval_to = 34; $interval_n = 59;	break;
			case 30: $interval_to = 59; $interval_n = 84;break;
			case 40: $interval_to = 84; $interval_n = 109;break;
			case 50: $interval_to = 109; $interval_n = 134; break;
			case 60: $interval_to = 134; break;
			//default: $proc = 1;
			}
            

/*			if ($_GET['proc'] == 5 or $_GET['proc'] == 10 or $_GET['proc'] == 15 or $_GET['proc'] == 20 or $_GET['proc'] == 25 or $_GET['proc'] == 30) {
					$interval_to = 29;
					$interval_n = 58;		
				}
                if ($_GET['proc'] == 35 or $_GET['proc'] == 40 or $_GET['proc'] == 45 or $_GET['proc'] == 50) {
					$interval_to = 58;
					$interval_n = 87;	
				}
                if ($_GET['proc'] == 70) {
					$interval_to = 87;
					$interval_n = 116;	
				}
                if ($_GET['proc'] == 90) {
					$interval_to = 166;
					$interval_n = 0;
				}
				*/
				
				$where .= ' and '.$_GET['proc'].' <= max_skidka and ucenka < '.(int)$_GET['proc'];
            }
			
	if(isset($_GET['brand']) and strlen($_GET['brand']) > 0 ) $where .= ' AND brand LIKE "%'.$_GET['brand'].'%" ';
			
	if (isset($_GET['search']) and strlen($_GET['search']) > 0) $where .= 'AND ( model LIKE "%'.$_GET['search'].'%" OR code LIKE "%'.$_GET['search'].'%" )';

  if(@$_GET['from']) { $day = date('Y-m-d', strtotime($_GET['from'])); }else{ $day = date('Y-m-d'); }

if($interval_to > 0) $where .= " AND DATE_FORMAT(  ws_articles.`ctime` ,  '".$day."' ) < DATE_SUB( CURRENT_DATE, INTERVAL ".$interval_to." DAY ) ";

//$where .= ' AND  ws_articles.ctime <= DATE_ADD("'.$day.'", INTERVAL - '.$interval_to.' DAY) ' . $interval_n; 
  
if ($interval_n > 0) $where .= " AND DATE_FORMAT(  ws_articles.`ctime` ,  '".$day."' ) > DATE_SUB( CURRENT_DATE, INTERVAL ".$interval_n." DAY ) ";

//$interval_n = ' AND ws_articles.ctime >= DATE_ADD("'.$day .'", INTERVAL - ' . $interval_n . ' DAY)';




 if (isset($_GET['id'])&&($_GET['id']!='')) {
                if (isset($_GET['whith_kids']) and @$_GET['whith_kids'] == 1) {
                    $cur_category = wsActiveRecord::useStatic('Shopcategories')->findById($_GET['id']);
                    if ($cur_category->getId()) {
						$this->view->cur_category = $cur_category;
                        $kids = $cur_category->getKidsIds();
                        $kids[] = $cur_category->getId();
                        $data [] = 'category_id in(' . implode(',', $kids) . ') OR dop_cat_id in(' . implode(',', $kids) . ')';
						$where .= 'AND (category_id in(' . implode(',', $kids) . ') OR dop_cat_id in(' . implode(',', $kids) . ') )';
                    }
                }else{
                    $data[] = '	category_id = ' . (int)$this->get->getId() . ' OR dop_cat_id =' . (int)$this->get->getId();

					$where .= ' AND ( category_id in(' . (int)$this->get->getId() . ') OR dop_cat_id in(' . (int)$this->get->getId() . ') )';
					
                    $cur_category = wsActiveRecord::useStatic('Shopcategories')->findById($this->get->getId());
                    if ($cur_category->getId())
						$this->view->cur_category = $cur_category;
                }
            }
			
			//d($where, false);
			//уценка отмеченые
        /*    if (@$this->get->ucenka_id and @$this->get->usenka_id_proc) {
                $data[] = 'id in(' . $this->get->ucenka_id . ')';
				$proc = 1.00;
				switch((int)$_GET['usenka_id_proc']){
				 case 10: $proc = 0.9; break;
				 case 15: $proc = 0.85; break;
				 case 20: $proc = 0.8; break;
				 case 25: $proc = 0.75; break;
				 case 30: $proc = 0.7; break;
				 case 35: $proc = 0.65; break;
				 case 40: $proc = 0.6; break;
				 case 45: $proc = 0.55; break;
				 case 50: $proc = 0.5; break;
				 case 60: $proc = 0.4; break;
				 case 70: $proc = 0.3; break;
				 case 90: $proc = 0.1; break;
					default: $proc = 1;
				}
				
$articles_ucen = wsActiveRecord::useStatic('Shoparticles')->findAll($data);
                $i = 0;
foreach ($articles_ucen as $art) {

 if ($art->getOldPrice() == 0){
 $category = new Shopcategories($art->getCategoryId());
 $art->setDopCatId($category->getUsencaCategory());
 
$s_p = $art->getPrice();

$art->setOldPrice($art->getPrice());

 }else{
 
 $s_p = $art->getOldPrice();
 
 }
 if(@$this->get->skidka_block) $art->setSkidkaBlock(1);
					
                    $art->setPrice($art->getOldPrice() * $proc);
					$art->setDataUcenki(date('Y-m-d H:i:s'));
					$art->setUcenka((int)$_GET['usenka_id_proc']);
                    $art->save();
					
UcenkaHistory::newUcenka($this->user->getId(), $art->getId(), $s_p, $art->getPrice(), (int)$art->getStock(), (int)$_GET['usenka_id_proc']);
                    $i++;
                }
				
                $this->view->ucenka_ok = $i;
				
	if($i > 0){
				$mess = "Уценено ".$i." товаров на ".$_GET['usenka_id_proc']." %.";
				$this->sendMessageTelegram(404070580, $mess);
		}

            }*/

			$onPage = 200;
            $page = !empty($_GET['page']) && (int)$_GET['page'] ? (int)$_GET['page'] : 1;
            $startElement = ($page - 1) * $onPage;

			$sql='SELECT ws_articles.*, ws_articles_sizes.id_article, ws_articles_sizes.code, ws_articles_sizes.id_color, ws_articles_sizes.id_size from ws_articles inner join ws_articles_sizes on  ws_articles.id = ws_articles_sizes.id_article
			WHERE '.$where.' GROUP BY ws_articles.id order by '.$order_by.' '.$order_by_type.' LIMIT '.$startElement.' , '.$onPage;
			$sql_c='SELECT count(ws_articles.id) as ctn, ws_articles.*  from ws_articles inner join ws_articles_sizes on  ws_articles.id = ws_articles_sizes.id_article
			WHERE '.$where.' GROUP BY ws_articles.id order by '.$order_by.' '.$order_by_type;
			//d($sql, false);
			$articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
			$total = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql_c)->count();
			$this->view->articles_a  = $articles;
			
            $this->view->totalPages = ceil($total / $onPage);
            $this->view->count = $total;
            $this->view->page = $page;
            $this->view->start = ($page == 1) ? 1 : $onPage * ($page - 1);
            $this->view->end = $onPage * ($page - 1) + $onPage;
            $this->view->order_by = $order_by;
            $this->view->order_by_type = $order_by_type;

        echo $this->render('shop/articles_ucenka.tpl.php');
    }

	protected function _generateUrl_v($shortcut)
	{
		$shortcut = $this->_translit_v(iconv('UTF-8','windows-1251',$shortcut));
		return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), $this->remove_accent_v($shortcut)));
	}
	
	public function remove_accent_v($str)
	{
		$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
		$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
		return str_replace($a, $b, $str);
	}
	
	public function _translit_v($str)
	{
		$transchars =array (
			"E1"=>"A",
			"E2"=>"B",
			"F7"=>"V",
			"E7"=>"G",
			"E4"=>"D",
			"E5"=>"E",
			"B3"=>"Jo",
			"F6"=>"Zh",
			"FA"=>"Z",
			"E9"=>"I",
			"EA"=>"I",
			"EB"=>"K",
			"EC"=>"L",
			"ED"=>"M",
			"EE"=>"N",
			"EF"=>"O",
			"F0"=>"P",
			"F2"=>"R",
			"F3"=>"S",
			"F4"=>"T",
			"F5"=>"U",
			"E6"=>"F",
			"E8"=>"H",
			"E3"=>"C",
			"FE"=>"Ch",
			"FB"=>"Sh",
			"FD"=>"W",
			"FF"=>"X",
			"F9"=>"Y",
			"F8"=>"Q",
			"FC"=>"Eh",
			"E0"=>"Ju",
			"F1"=>"Ja",

			"C1"=>"a",
			"C2"=>"b",
			"D7"=>"v",
			"C7"=>"g",
			"C4"=>"d",
			"C5"=>"e",
			"A3"=>"jo",
			"D6"=>"zh",
			"DA"=>"z",
			"C9"=>"i",
			"CA"=>"i",
			"CB"=>"k",
			"CC"=>"l",
			"CD"=>"m",
			"CE"=>"n",
			"CF"=>"o",
			"D0"=>"p",
			"D2"=>"r",
			"D3"=>"s",
			"D4"=>"t",
			"D5"=>"u",
			"C6"=>"f",
			"C8"=>"h",
			"C3"=>"c",
			"DE"=>"ch",
			"DB"=>"sh",
			"DD"=>"w",
			"DF"=>"x",
			"D9"=>"y",
			"D8"=>"",
			"DC"=>"eh",
			"C0"=>"ju",
			"D1"=>"ja",
		);

		$str = trim($str);
		$ns = convert_cyr_string($str, "w", "k");
		$b = '';
		for ($i=0;$i<strlen($ns);$i++)
		{
			$c=substr($ns,$i,1);
			$a=strtoupper(dechex(ord($c)));
			if (isset($transchars[$a])) {
				$a=$transchars[$a];
			} else if (ctype_alnum($c)){
				$a=$c;
			} else if (ctype_space($c)){
				$a='-';
			} else {
				$a='-';
			}
			$b.=$a;
		}
		return $b;
	}	
	
    public function otchetsAction()
    {
	 
      if ($this->get->type) {
        if ($this->get->type == 10) {

            ini_set('memory_limit', '1024M');

            $q = "SELECT DISTINCT kupon_price FROM `ws_orders` WHERE `kupon` >0 AND `kupon_price`>0";
            $q0 = "SELECT DISTINCT kupon_price, COUNT(id) FROM `ws_orders` WHERE `kupon` >0 AND kupon_price =200";
            $q1 = "SELECT t2.id, t1.name AS  'first', t2.name AS  'second', SUM( t3.stock ) AS 'ostatok'
				FROM  `ws_categories` t1
				JOIN  `ws_categories` t2 ON t1.id = t2.parent_id
				RIGHT JOIN  `ws_articles` t3 ON t2.id = t3.category_id
				WHERE t1.`parent_id` =0
				GROUP BY t2.id
				ORDER BY  `first` ,  `second` ASC";

            $artucles = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q1);

            require_once('PHPExel/PHPExcel.php');
            $kount = 1;
            $filename = 'otchet_tov_group_' . date("Y-m-d_H:i:s") . '.xls';
            $pExcel = new PHPExcel();
            $pExcel->setActiveSheetIndex(0);
            $aSheet = $pExcel->getActiveSheet();
            $aSheet->setTitle('Товары по группам');
            $aSheet->getColumnDimension('A')->setWidth(9);
            $aSheet->getColumnDimension('B')->setWidth(12);
            $aSheet->getColumnDimension('C')->setWidth(17);
            $aSheet->getColumnDimension('D')->setWidth(8);
            $aSheet->getColumnDimension('E')->setWidth(20);

            $aSheet->setCellValue('A1', 'Номинал');
            $aSheet->setCellValue('B1', 'К-во общее');
            $aSheet->setCellValue('C1', 'Из них оплачено');
            $aSheet->setCellValue('D1', 'Возврат');
            $aSheet->setCellValue('E1', '№ Заказов');

            $i = 2;
            $akp = array(500, 300, 100);

            foreach ($akp as $a) {
                $q_in1 = "SELECT COUNT(*) as kol FROM `ws_orders` WHERE `kupon` NOT LIKE 0 AND kupon_price =$a";
                $kol = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q_in1);

                $q_in2 = "SELECT COUNT(*) as opl FROM `ws_orders` WHERE `kupon` NOT LIKE 0 AND kupon_price =$a AND status = 8";
                $opl = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q_in2);

                $q_in3 = "SELECT COUNT(*) as vozvr FROM `ws_orders` WHERE `kupon` NOT LIKE 0 AND kupon_price =$a AND status = 7";
                $vozvr = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q_in3);

                $q_in4 = "SELECT id FROM `ws_orders` WHERE `kupon` NOT LIKE 0 AND kupon_price =$a";
                $nzak = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q_in4);
                $ni = array();
                foreach ($nzak as $n) $ni[] = $n->getId();

                $aSheet->setCellValue('A' . $i, $a);
                $aSheet->setCellValue('B' . $i, $kol[0]->getKol());
                $aSheet->setCellValue('C' . $i, $opl[0]->getOpl());
                $aSheet->setCellValue('D' . $i, $vozvr[0]->getVozvr());
                $aSheet->setCellValue('E' . $i, implode(", ", $ni));
                ++$i;
            }


            require_once("PHPExel/PHPExcel/Writer/Excel5.php");
            $objWriter = new PHPExcel_Writer_Excel5($pExcel);

            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

            $objWriter->save('php://output');
        }elseif ($this->get->type == 11) { //Заказы по городам(Новая почта и Укр почта).
            ini_set('memory_limit', '2048M');
            Report::toExcel($this->get, $_POST);
            exit();
        }elseif ($this->get->type == 12) {
            ini_set('memory_limit', '1024M');

            $customers = wsActiveRecord::useStatic('Customer')->findAll(array('drawing' => 'red2014'));


            $mas = array();


            require_once('PHPExel/PHPExcel.php');
            $name = 'otchet_order_' . $_POST['order_from'] . '_' . $_POST['order_to'];
            $kount = 1;
            $filename = $name . '.xls';
            $pExcel = new PHPExcel();
            $pExcel->setActiveSheetIndex(0);
            $aSheet = $pExcel->getActiveSheet();
            $aSheet->setTitle('Первый лист');
            $aSheet->getColumnDimension('A')->setWidth(30);
            $aSheet->getColumnDimension('B')->setWidth(15);
            $aSheet->getColumnDimension('C')->setWidth(15);
            $aSheet->getColumnDimension('D')->setWidth(15);
            $aSheet->getColumnDimension('E')->setWidth(15);
            $aSheet->getColumnDimension('F')->setWidth(15);
            $aSheet->setCellValue('A1', 'Email');
            $aSheet->setCellValue('B1', 'Телефон');
            $aSheet->setCellValue('C1', 'Имя');
            $aSheet->setCellValue('D1', 'Количество заказов');
            $aSheet->setCellValue('E1', 'Количество единиц');
            $aSheet->setCellValue('F1', 'Сумма');


            $boldFont = array(
                'font' => array(
                    'bold' => true
                )
            );
            $aSheet->getStyle('A1')->applyFromArray($boldFont);
            $aSheet->getStyle('B1')->applyFromArray($boldFont);
            $aSheet->getStyle('C1')->applyFromArray($boldFont);
            $aSheet->getStyle('D1')->applyFromArray($boldFont);
            $aSheet->getStyle('E1')->applyFromArray($boldFont);
            $aSheet->getStyle('F1')->applyFromArray($boldFont);
            $i = 2;
            foreach ($customers as $customer) {
                $aSheet->setCellValue('A' . $i, $customer->getEmail());
                $aSheet->setCellValue('B' . $i, $customer->getPhone1());
                $aSheet->setCellValue('C' . $i, $customer->getFullname());

                $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('status in (1,3,4,5,6,7,8,9,10,11,12,13,14,15,16)', 'customer_id' => $customer->getId()), array('date_create' => 'ASC'));

                $kount_a = 0;
                $sum = 0;

                foreach ($orders as $order) {
                    $kount_a += $order->countArticlesSum();
                    $sum += Number::formatFloat($order->getAmount(), 2);
                }


                $aSheet->setCellValue('D' . $i, $orders->count());
                $aSheet->setCellValue('E' . $i, $kount_a);
                $aSheet->setCellValue('F' . $i, $sum);
                $i++;
            }

            require_once("PHPExel/PHPExcel/Writer/Excel5.php");
            $objWriter = new PHPExcel_Writer_Excel5($pExcel);

            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
            $objWriter->save('php://output');
        }elseif ($this->get->type == 13) {

                ini_set('memory_limit', '1024M');

                $q = "SELECT  ws_articles_sizes.*, ws_articles.brand, ws_articles.model, ws_articles.code, ws_articles.price, ws_articles.old_price,  ws_articles.data_new
				FROM ws_articles_sizes
					JOIN ws_articles ON ws_articles.id = ws_articles_sizes.id_article
					WHERE  ws_articles.ctime > '" . date('Y-m-d', strtotime($_POST['order_from'])) . " 00:00:00'
					AND ws_articles.ctime < '" . date('Y-m-d', strtotime($_POST['order_to'])) . " 23:59:59'
					AND ws_articles.active = 'y' ";

                $artucles = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($q);
                $mas = array();
				$s_pr = 0;
				$s_rs = 0;
				$s_ost = 0;
				$s_price = 0;
				$s_sum_pr = 0;
				$s_sum_poter = 0;
                foreach ($artucles as $article) {
                    
					$nakladna = $article->getCode();
					if($article->getOldPrice() > 0){ $price = $article->getOldPrice(); }else{ $price = $article->getPrice(); }
					$date_add = $article->getDataNew();
					$brand = $article->getBrand();
					$model = $article->getModel();
                    $name = $article->getModel();
					
                   
	$q = 'SELECT sum(count) as cnt FROM ws_articles_sizes where id_article = ' . $article->getIdArticle();
		$now_count = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($q)->at(0)->cnt;
			//$summ2 = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q1)->at(0)->summ;			
	$q1 = 'SELECT SUM(`ws_order_articles`.`count`) AS ccc FROM ws_order_articles WHERE ws_order_articles.article_id = ' . $article->getIdArticle();
		$count1 = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q1)->at(0)->ccc;
		
$q2 = 'SELECT SUM( IF(  `ws_order_articles`.`old_price` >0,  `ws_order_articles`.`price` *  `ws_order_articles`.`count` , `ws_order_articles`.`price` *  `ws_order_articles`.`count` * ( 1 -  `ws_orders`.`skidka` /100 ) ) ) AS `summ`
FROM `ws_order_articles`
INNER JOIN `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE `ws_order_articles`.`article_id` = ' . $article->getIdArticle();
		
			$summ2 = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q2)->at(0)->summ;
//$spos = $price*($now_count+$count1);
                        $mas[$name][$article->getIdArticle()]['nakladna'] = $nakladna;
                        $mas[$name][$article->getIdArticle()]['brand'] = $brand;
						$mas[$name][$article->getIdArticle()]['model'] = $model;
                        $mas[$name][$article->getIdArticle()]['count'] = $count1 > 0 ? $count1 : 0;
                        //$mas[$name][$article->getIdArticle()]['зкшсу'] = $spos;
						 $mas[$name][$article->getIdArticle()]['sum_pr'] = $summ2;
                        $mas[$name][$article->getIdArticle()]['now_count'] = $now_count;
						$mas[$name][$article->getIdArticle()]['price'] = $price;
						$mas[$name][$article->getIdArticle()]['date_new'] = $date_add;
						$mas[$name][$article->getIdArticle()]['raznica'] = $price - $summ2;
						

                }

                require_once('PHPExel/PHPExcel.php');
                $name = 'otchetexel_nakladna_'.$_POST['order_from'].'-'.$_POST['order_to'];
                //$kount = 1;
                $filename = $name . '.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
              
                $aSheet->getColumnDimension('A')->setWidth(10);
                $aSheet->getColumnDimension('B')->setWidth(15);
                $aSheet->getColumnDimension('C')->setWidth(10);
                $aSheet->getColumnDimension('D')->setWidth(10);
                $aSheet->getColumnDimension('E')->setWidth(10);
                $aSheet->getColumnDimension('F')->setWidth(10);
				 $aSheet->getColumnDimension('G')->setWidth(15);
				 $aSheet->getColumnDimension('H')->setWidth(15);
				 $aSheet->getColumnDimension('I')->setWidth(15);
				
				$aSheet->setCellValue('A1', 'Дата поступления'); // Накладна
                $aSheet->setCellValue('B1', 'Накладна'); // Накладна
                $aSheet->setCellValue('C1', 'Бренд'); // Цена
                $aSheet->setCellValue('D1', 'Модель'); // к-во всего
                $aSheet->setCellValue('E1', 'Приход'); // к-во прод.
                $aSheet->setCellValue('F1', 'Расход'); //остаток
				$aSheet->setCellValue('G1', 'Остаток'); // к-во прод.
				$aSheet->setCellValue('H1', 'Цена поступления'); // к-во прод.
				$aSheet->setCellValue('I1', 'Сумма продж'); // к-во прод.
				$aSheet->setCellValue('J1', 'Потеря'); // к-во прод.
				

                $boldFont = array(
                    'font' => array(
                        'bold' => true
                    )
                );
                $aSheet->getStyle('A1:J1')->applyFromArray($boldFont);
		$aSheet->getStyle('E2:J1')->applyFromArray($boldFont);
		
                $i = 3;
                foreach ($mas as $kay => $val) {
                    foreach ($val as $tov_kay => $tov_val) {
						$aSheet->setCellValue('A' . $i, $tov_val['date_new']);
                       	$aSheet->setCellValue('B' . $i, $tov_val['nakladna']);
						$aSheet->setCellValue('C' . $i, $tov_val['brand']);
						$aSheet->setCellValue('D' . $i, $tov_val['model']);
						$aSheet->setCellValue('E' . $i, $tov_val['now_count'] + $tov_val['count']);
						$aSheet->setCellValue('F' . $i, $tov_val['count']);
						$aSheet->setCellValue('G' . $i, $tov_val['now_count']);
						$aSheet->setCellValue('H' . $i, $tov_val['price']);
						$aSheet->setCellValue('I' . $i, $tov_val['sum_pr']);
						$aSheet->setCellValue('J' . $i, $tov_val['sum_pr'] > 0 ? $tov_val['price']*$tov_val['count']-$tov_val['sum_pr'] : 0);
                        $i++;
				$s_pr += ($tov_val['now_count'] + $tov_val['count']);
				$s_rs += $tov_val['count'];
				$s_ost += $tov_val['now_count'];
				$s_sum_post += ($tov_val['price']*($tov_val['now_count'] + $tov_val['count']));
				$s_sum_pr += $tov_val['sum_pr'];
				 $s_sum_poter += $tov_val['sum_pr'] > 0 ? $tov_val['price']*$tov_val['count']-$tov_val['sum_pr'] : 0;
                    }
                }
                $i++;
                 $aSheet->setCellValue('E2', $s_pr); // к-во прод.
                $aSheet->setCellValue('F2', $s_rs); //остаток
				$aSheet->setCellValue('G2', $s_ost); // к-во прод.
				$aSheet->setCellValue('H2', $s_sum_post); // к-во прод.
				$aSheet->setCellValue('I2', $s_sum_pr); // к-во прод.
				$aSheet->setCellValue('J2', $s_sum_poter); // к-во прод.

                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

                $objWriter->save('php://output');
            }elseif ($this->get->type == 14) {
            ini_set('memory_limit', '1024M');
            $to = strtotime($_POST['order_to']);
            $from = strtotime($_POST['order_from']);


            $q = 'SELECT u.*, `as`.code, a.brand, a.model FROM ucenka_history as u
					LEFT JOIN ws_articles_sizes as `as` ON u.article_id = `as`.id_article
					LEFT JOIN ws_articles as a ON as.id_article = a.id
					WHERE u.ctime <="' . date('Y-m-d', $to) . ' 23:59:59"
					AND u.ctime >= "' . date('Y-m-d', $from) . ' 00:00:00"';

            $mas = wsActiveRecord::findByQueryArray($q);

            require_once('PHPExel/PHPExcel.php');

            $filename = 'otchet_ucenka_' . date('Y-m-d', $from) . '_' . date('Y-m-d', $to) . '.xls';
            $pExcel = new PHPExcel();
            $pExcel->setActiveSheetIndex(0);
            $aSheet = $pExcel->getActiveSheet();
            $aSheet->setTitle('Первый лист');
            $aSheet->getColumnDimension('A')->setWidth(19);
            $aSheet->getColumnDimension('B')->setWidth(25);
            $aSheet->getColumnDimension('C')->setWidth(9);
            $aSheet->getColumnDimension('D')->setWidth(10);
            $aSheet->getColumnDimension('E')->setWidth(13);
            $aSheet->getColumnDimension('F')->setWidth(8);
            $aSheet->getColumnDimension('G')->setWidth(18);
            $aSheet->setCellValue('A1', 'Код');
            $aSheet->setCellValue('B1', 'Название');
            $aSheet->setCellValue('C1', 'Процент');
            $aSheet->setCellValue('D1', 'Сумма до');
            $aSheet->setCellValue('E1', 'Сумма после');
            $aSheet->setCellValue('F1', 'Разница');
            $aSheet->setCellValue('G1', 'Дата уценки');
            $boldFont = array(
                'font' => array(
                    'bold' => true
                )
            );
            $aSheet->getStyle('A1:G1')->applyFromArray($boldFont);

            $i = 2;
            foreach ($mas as $m) {
                $aSheet->setCellValue('A' . $i, $m->code);
                $aSheet->setCellValue('B' . $i, $m->brand . ' ' . $m->model);
                $aSheet->setCellValue('C' . $i, $m->proc . '%');
                $aSheet->setCellValue('D' . $i, $m->old_price);
                $aSheet->setCellValue('E' . $i, $m->new_price);
                $aSheet->setCellValue('F' . $i, $m->old_price - $m->new_price);
                $aSheet->setCellValue('G' . $i, $m->ctime);
                ++$i;
            }

            require_once("PHPExel/PHPExcel/Writer/Excel5.php");
            $objWriter = new PHPExcel_Writer_Excel5($pExcel);

            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

            $objWriter->save('php://output');

        }elseif ($this->get->type == 15) { 
            ini_set('memory_limit', '1024M');
			$sql = "SELECT  `as`.`code` ,  `d`.`id_articles` ,  `a`.`brand` , `a`.`brand_id`,  `a`.`model`, `a`.`price` 
		FROM  `ws_desires` AS  `d` 
			LEFT JOIN  `ws_articles_sizes` AS  `as` ON  `d`.`id_articles` =  `as`.`id_article` 
			LEFT JOIN  `ws_articles` AS  `a` ON  `d`.`id_articles` =  `a`.`id` 
			WHERE ";
			if($_POST['brend'] != 0) { 
			$sql .=" `a`.`brand_id` = " . @$_POST['brend'] . "
			AND";}
			
			if($_POST['model'] != 'Модель') { 
			$sql .=" `a`.`model` LIKE '". @$_POST['model'] . "'
			AND";}
			$sql .="
			EXISTS (
				SELECT  `d`.`id_articles` 
				FROM  `ws_desires`
					)
					GROUP BY  `d`.`id_articles` 
					ORDER BY  `a`.`brand` ASC ";
			$mas = wsActiveRecord::findByQueryArray($sql);
			//kupily
			$sql_k="
			SELECT ws_orders.id, ws_orders.customer_id, ws_desires.id_articles
FROM ws_orders
INNER JOIN ws_order_articles ON ws_orders.id = ws_order_articles.order_id
INNER JOIN ws_desires ON ws_order_articles.article_id = ws_desires.id_articles
WHERE ws_orders.customer_id = ws_desires.id_customer
AND ws_orders.status NOT 
IN ( 2, 5, 7, 12 ) 
GROUP BY ws_orders.id";
$mas_kup = wsActiveRecord::findByQueryArray($sql_k);
$m_k = array();
$j = 0;
foreach ($mas_kup as $m) {
			$m_k[$i] = $m->id_articles;
			$i++;
			}
			$count=count($m_k);
			$g = array_count_values($m_k);
			//exit kupily
			$sql_d = "SELECT  `d`.`id_articles` FROM  `ws_desires` AS  `d` ";
				$mas_d = wsActiveRecord::findByQueryArray($sql_d);
					$obj_users = array();
					$i = 0;
			foreach ($mas_d as $m) {
			$obj_users[$i] = $m->id_articles;
			$i++;
			}
			$count=count($obj_users);
			$d = array_count_values($obj_users);
            require_once('PHPExel/PHPExcel.php');

            $filename = 'otchet_desires_' . date('Y-m-d') . '.xls';
            $pExcel = new PHPExcel();
            $pExcel->setActiveSheetIndex(0);
            $aSheet = $pExcel->getActiveSheet();
            $aSheet->setTitle('Первый лист');
            $aSheet->getColumnDimension('A')->setWidth(20);
            $aSheet->getColumnDimension('B')->setWidth(20);
            $aSheet->getColumnDimension('C')->setWidth(10);
            $aSheet->getColumnDimension('D')->setWidth(12);
            $aSheet->getColumnDimension('E')->setWidth(10);
			$aSheet->getColumnDimension('F')->setWidth(12);
            

            $aSheet->setCellValue('A1', 'Бренд');
            $aSheet->setCellValue('B1', 'Модель');
            $aSheet->setCellValue('C1', 'Процент');
            $aSheet->setCellValue('D1', 'Количество');
            $aSheet->setCellValue('E1', 'Цена грн');
			$aSheet->setCellValue('F1', 'Купили');
            
            $boldFont = array(
                'font' => array(
                    'bold' => true
                )
            );
            $aSheet->getStyle('A1:G1')->applyFromArray($boldFont);
			
			$i = 2;
            foreach ($mas as $m) {
			$c = (100/$count)*$d[$m->id_articles];
                //$aSheet->setCellValue('A' . $i, $m->code);
                $aSheet->setCellValue('A' . $i, $m->brand);
				$aSheet->setCellValue('B' . $i, $m->model);
                $aSheet->setCellValue('C' . $i, round($c,2).' %');  
                $aSheet->setCellValue('D' . $i, $d[$m->id_articles]); 
                $aSheet->setCellValue('E' . $i, $m->price);
				$aSheet->setCellValue('F' . $i, $g[$m->id_articles]); 
                ++$i;
            }

            require_once("PHPExel/PHPExcel/Writer/Excel5.php");
            $objWriter = new PHPExcel_Writer_Excel5($pExcel);

            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

            //header("Content-type: application/x-msexcel");

            $objWriter->save('php://output');
			
			}elseif ($this->get->type == 16) { 
            ini_set('memory_limit', '1024M');
			$name = '';
			if($_POST['category'] == 0){
			$name = 'vse';
		$sql = "
			SELECT * FROM  `ws_return_article` ORDER BY  `ws_return_article`.`id` DESC ";
		
		}elseif($_POST['category'] == 1){
		$name = 'zhdut_uvedomleniya';
		$sql = "SELECT * FROM  `ws_return_article` WHERE  `utime` IS NULL ORDER BY  `ws_return_article`.`id` DESC ";
		
		}elseif($_POST['category'] == 2){
			$name = 'poluchili_uvedomleniya';
		$sql = "SELECT * FROM  `ws_return_article` WHERE  `utime` IS NOT NULL  ORDER BY  `ws_return_article`.`id` DESC ";
		}
		
		$notice = wsActiveRecord::findByQueryArray($sql);
        //$this->view->notice = $notice;
			
			
			
			
			
			//kupily
			$sql_k="
			SELECT ws_return_article.id, ws_return_article.code, ws_return_article.id_article, ws_return_article.email
FROM ws_orders
INNER JOIN ws_order_articles ON ws_orders.id = ws_order_articles.order_id
INNER JOIN ws_return_article ON ws_order_articles.article_id = ws_return_article.id_article
WHERE ws_orders.email = ws_return_article.email
AND ws_orders.status NOT 
IN ( 2, 5, 7, 12 ) 
AND ws_orders.date_create > ws_return_article.utime
GROUP BY ws_orders.id";

$mas_kup = wsActiveRecord::findByQueryArray($sql_k);

$m_k = array();
$j = 0;
foreach ($mas_kup as $m) {
			$m_k[$j] = $m->id;
			$j++;
			}
			//$count=count($m_k);
			//$g = array_count_values($m_k);
			//exit kupily
			
			
            require_once('PHPExel/PHPExcel.php');

            $filename = 'otchet_notice_'.$name.'_' . date('Y-m-d') . '.xls';
            $pExcel = new PHPExcel();
            $pExcel->setActiveSheetIndex(0);
            $aSheet = $pExcel->getActiveSheet();
            $aSheet->setTitle('Первый лист');
            $aSheet->getColumnDimension('A')->setWidth(20);
            $aSheet->getColumnDimension('B')->setWidth(25);
            $aSheet->getColumnDimension('C')->setWidth(15);
			 $aSheet->getColumnDimension('D')->setWidth(15);

            

            //$aSheet->setCellValue('A1', 'Артикул');
            //$aSheet->setCellValue('B1', 'Подписан');
           // $aSheet->setCellValue('C1', 'Уведомлен');
            $aSheet->setCellValue('A1', 'Имя');
            $aSheet->setCellValue('B1', 'Email');
			$aSheet->setCellValue('C1', 'Купили');
			$aSheet->setCellValue('D1', 'Сума');

            
            $boldFont = array(
                'font' => array(
                    'bold' => true
                )
            );
            $aSheet->getStyle('A1:G1')->applyFromArray($boldFont);
			
			$i = 2;
			$k=0;
            foreach ($notice as $m) {
			//$x = $m->id_articles;
			//$key = array_search($m->id_article, $m_k);
			if (in_array($m->id, $m_k)){
			$k = 1; 
			} else {
			$k = '';
			}
			//$c = (100/$count)*$d[$m->id_articles];
                //$aSheet->setCellValue('A' . $i, $m->code);
               // $aSheet->setCellValue('A' . $i, $m->code);
				//$aSheet->setCellValue('B' . $i, $m->ctime);
                //$aSheet->setCellValue('C' . $i, $m->utime);  
                $aSheet->setCellValue('A' . $i, $m->name); 
                $aSheet->setCellValue('B' . $i, $m->email);
				$aSheet->setCellValue('C' . $i, $k); 
				$aSheet->setCellValue('D' . $i, 1);
                ++$i;
            }

            require_once("PHPExel/PHPExcel/Writer/Excel5.php");
            $objWriter = new PHPExcel_Writer_Excel5($pExcel);

            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

            //header("Content-type: application/x-msexcel");

            $objWriter->save('php://output');
			
			}elseif ($this->get->type == 17) { 
		// die(json_encode(array('start'=>$this->post->start, 'end'=>$this->post->end, 'exit'=>'fak')));
		$start = $this->post->start;
           ini_set('memory_limit', '2048M');
		  // die(json_encode(array('start'=>$this->post->start, 'end'=>$this->post->end, 'exit'=>'fak')));
			//set_time_limit(1200);
			
			$sql ="SELECT  `ws_articles_sizes`.`id` AS  `d` ,  `ws_articles`.`id` ,  `ws_articles_sizes`.`id_article` ,  `ws_articles`.`category_id` , `ws_articles_sizes`.`id_size` ,  `ws_articles_sizes`.`id_color` ,  `ws_articles_sizes`.`count` ,  `ws_articles_sizes`.`code` , `ws_articles`.`ctime` ,  `ws_articles`.`brand` ,  `ws_articles`.`model` ,  `ws_articles`.`old_price` ,  `ws_articles`.`price` 
FROM  `ws_articles_sizes` 
INNER JOIN  `ws_articles` ON  `ws_articles_sizes`.`id_article` =  `ws_articles`.`id` 
WHERE  `ws_articles_sizes`.`count` >0
AND  `ws_articles`.`active` =  'y'
ORDER BY  `d` ASC 
LIMIT ".$start." , 10 ";
			 $articles = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($sql);
			 $mas = array();
			 
			 require_once('PHPExel/PHPExcel.php');
			  require_once("PHPExel/PHPExcel/Writer/Excel5.php");
     $name = 'otchet_articles_' .date('d-m-Y');
     $filename = $name . '.xls';
	 $path1file = INPATH . "backend/views/chart/". $filename;
				
                
				if($start == 0){
				
				$pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
				
                $aSheet->getColumnDimension('A')->setWidth(40);
                $aSheet->getColumnDimension('B')->setWidth(15);
                $aSheet->getColumnDimension('C')->setWidth(18);
                $aSheet->getColumnDimension('D')->setWidth(12);
                $aSheet->getColumnDimension('E')->setWidth(16);
                $aSheet->getColumnDimension('F')->setWidth(18);
				$aSheet->getColumnDimension('G')->setWidth(8);
				$aSheet->getColumnDimension('H')->setWidth(13);
				$aSheet->getColumnDimension('I')->setWidth(10);
				$aSheet->getColumnDimension('J')->setWidth(12);
				$aSheet->getColumnDimension('K')->setWidth(10);
				$aSheet->getColumnDimension('L')->setWidth(8);
				$aSheet->getColumnDimension('M')->setWidth(13);
				$aSheet->getColumnDimension('N')->setWidth(18);

				
                $aSheet->setCellValue('A1', 'Категория');
                $aSheet->setCellValue('B1', 'Название');
                $aSheet->setCellValue('C1', 'Бренд');
                $aSheet->setCellValue('D1', 'Размер');
                $aSheet->setCellValue('E1', 'Цвет');
                $aSheet->setCellValue('F1', 'Артикул');
				$aSheet->setCellValue('G1', 'Цена');
				$aSheet->setCellValue('H1', 'Стартовая цена');
                $aSheet->setCellValue('I1', 'Добавлено');
                $aSheet->setCellValue('J1', 'Заказывалось');
                $aSheet->setCellValue('K1', 'Вернулось');
				$aSheet->setCellValue('L1', 'Остаток');
				$aSheet->setCellValue('M1', 'Дней на сайте');
				$aSheet->setCellValue('N1', 'Дней с посл. заказа');
                $boldFont = array(
                    'font' => array(
                        'bold' => true
                    )
                );
                $aSheet->getStyle('A1:N1')->applyFromArray($boldFont);
				}else{
			$pExcel = PHPExcel_IOFactory::load($path1file);
			$pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
				
				}
             // if($start == 0){
			 // $i = 2;
			 // }else{
			  $i = $start+2;
			 // }  
			 foreach($articles as $a){
			 $aSheet->setCellValue('A' . $i, Shopcategories::CatName($a->getCategoryId()));
			 $aSheet->setCellValue('B' . $i, $a->getModel());
			 $aSheet->setCellValue('C' . $i, $a->getBrand());
			 $aSheet->setCellValue('D' . $i, $a->getSize()->getSize());
			 $aSheet->setCellValue('E' . $i, $a->getColor()->getName());
			 $aSheet->setCellValue('F' . $i, $a->getCode());
			 $aSheet->setCellValue('G' . $i, $a->getPrice());
			 $aSheet->setCellValue('H' . $i, $a->getOldPrice() > 0 ? $a->getOldPrice() : $a->getPrice());
			 			  $q = "SELECT SUM(  `red_article_log`.`count` ) AS ctn
					FROM red_article_log
					inner join ws_articles on red_article_log.article_id = ws_articles.`id`
					WHERE red_article_log.type_id = 1
						and ws_articles.status = 3
					AND  `red_article_log`.`code` LIKE  '".$a->getCode()."'";
			 $aSheet->setCellValue('I' . $i, wsActiveRecord::useStatic('Shoparticlelog')->findByQuery($q)->at(0)->getCtn());
			 	$q="
	SELECT SUM( IF(  `ws_order_articles`.`count` >0,  `ws_order_articles`.`count` , 1 ) ) AS ctn, MAX(  `ws_orders`.`date_create` ) AS dat
FROM  `ws_order_articles` 
INNER JOIN  `ws_orders` ON  `ws_order_articles`.`order_id` =  `ws_orders`.`id` 
WHERE  `artikul` LIKE  '".$a->getCode()."'
	";
	$order = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q);
			 $aSheet->setCellValue('J' . $i, $order->at(0)->ctn);
			 			  $s = "SELECT count(ws_order_articles_vozrat.id) as allcount 
					FROM ws_order_articles_vozrat
					WHERE	ws_order_articles_vozrat.status = 1
					and  ws_order_articles_vozrat.`count` = 0
					and ws_order_articles_vozrat.article_id = ".$a->getId()."
					and ws_order_articles_vozrat.`cod` LIKE  '".$a->getCode()."'
					";
			 $aSheet->setCellValue('K' . $i, wsActiveRecord::useStatic('ShoporderarticlesVozrat')->findByQuery($s)->at(0)->getAllcount());
			 
			 $aSheet->setCellValue('L' . $i, $a->getCount());
				$item_time = strtotime($a->getCtime());
				$day = (time() - $item_time) / (24 * 60 * 60);
			 $aSheet->setCellValue('M' . $i, (int)$day);
				$item_order = strtotime($order->at(0)->dat);
				$dey_order = (time() - $item_order) / (24 * 60 * 60);
			 $aSheet->setCellValue('N' . $i, (int)$dey_order);
			 
			 $i++;
			 }
			 
			 

                $objWriter = new PHPExcel_Writer_Excel5($pExcel);
				
				
$end = $this->post->end - 10;

		if($end <= $this->post->start){	
$objWriter->save($path1file);
die(json_encode(array('exit'=>"Отчёт сформирован!", 'src'=>'https://www.red.ua/backend/views/chart/'.$filename)));
}else{
$objWriter->save($path1file);
die(json_encode(array('start'=>(int)$this->post->start+=10, 'end'=>(int)$this->post->end)));	
}

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

               // header("Content-type: application/x-msexcel");

                $objWriter->save('php://output');

			
			}elseif ($this->get->type == 18) {
if($this->post->ucenka_data){
$data = date('Y-m-d', strtotime($this->post->ucenka_data));
$sql = "SELECT  `ws_articles`. * ,  `ucenka_history`.`koll` , ((`ucenka_history`.`old_price` -  `ucenka_history`.`new_price`) *`ucenka_history`.`koll`) AS  `potera` 
FROM  `ws_articles` 
INNER JOIN  `ucenka_history` ON  `ws_articles`.`id` =  `ucenka_history`.`article_id` 
WHERE  `ucenka_history`.`admin_id` = 8005
AND DATE_FORMAT(  `ws_articles`.`data_ucenki` ,  '%Y-%m-%d' ) =  '".$data."'
AND  `ws_articles`.`ucenka` > 0";
$ucenka = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);

require_once('PHPExel/PHPExcel.php');
			  require_once("PHPExel/PHPExcel/Writer/Excel5.php");
     $name = 'ucenka_' .$data;
     $filename = $name . '.xls';
	 
	 $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
				
                $aSheet->getColumnDimension('A')->setWidth(40);
                $aSheet->getColumnDimension('B')->setWidth(15);
                $aSheet->getColumnDimension('C')->setWidth(18);
                $aSheet->getColumnDimension('D')->setWidth(18);
                $aSheet->getColumnDimension('E')->setWidth(12);
                $aSheet->getColumnDimension('F')->setWidth(12);
				$aSheet->getColumnDimension('G')->setWidth(12);


				
                $aSheet->setCellValue('A1', 'Категория');
                $aSheet->setCellValue('B1', 'Название');
                $aSheet->setCellValue('C1', 'Бренд');
                $aSheet->setCellValue('D1', 'Сезон');
                $aSheet->setCellValue('E1', 'Процент');
                $aSheet->setCellValue('F1', 'Количество');
				$aSheet->setCellValue('G1', 'Потеря');
                $boldFont = array(
                    'font' => array(
                        'bold' => true
                    )
                );
                $aSheet->getStyle('A1:G1')->applyFromArray($boldFont);
				$i = 2;
				$mas = array(1=>'Лето', 2=>'Осень-Весна', 3=>'Зима', 4=>'Всесезон');
				foreach($ucenka as $a){
			 $aSheet->setCellValue('A' . $i, $a->getCategory()->getRoutez());
			 $aSheet->setCellValue('B' . $i, $a->getModel());
			 $aSheet->setCellValue('C' . $i, $a->getBrand());
			 $aSheet->setCellValue('D' . $i, $mas[$a->getSezon()]);
			 $aSheet->setCellValue('E' . $i, $a->getUcenka());
			 $aSheet->setCellValue('F' . $i, $a->getKoll()?$a->getKoll():1);
			 $aSheet->setCellValue('G' . $i, $a->getPotera());
			 $i++;
			 }
			  $objWriter = new PHPExcel_Writer_Excel5($pExcel);
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

            //header("Content-type: application/x-msexcel");

            $objWriter->save('php://output');
}

}elseif ($this->get->type == 19) { //отчет по товарам и остатку
		 
		$start = (int)$this->post->start;
$cats = wsActiveRecord::useStatic('Shopcategories')->findFirst(array('id' =>(int)$this->post->cat, 'active' => 1));
$arr = $cats->getKidsIds();
$arr = array_unique($arr);
$cat = implode(",", $arr);
//$cat = implode(",", $this->post->cat);

//die(json_encode(array('start'=>$this->post->start, 'end'=>$this->post->end, 'exit'=>'fak', 'cat'=>$cat)));

           ini_set('memory_limit', '2048M');
		  // die(json_encode(array('start'=>$this->post->start, 'end'=>$this->post->end, 'exit'=>'fak')));
			set_time_limit(1200);
			
			$sql ="SELECT 
			`ws_articles`.`id` ,
			`ws_articles`.`category_id` ,
			`ws_articles`.`brand` ,
			`ws_articles`.`model` ,
			`ws_sizes`.`size` AS  `sizes` ,
			`ws_articles_colors`.`name` AS  `colors`,
				SUM(  `ws_order_articles`.`count` ) AS  `sum_order`,
				SUM(if(`ws_order_articles`.`count`=0,1,0)) as `sum_ret`,
			`ws_articles_sizes`.`count` ,
			`ws_articles`.`data_ucenki` ,
			`ws_articles`.`ucenka`,
			`ws_articles`.`sezon`,
			`ws_articles_sizes`.`id` AS  `d` ,
			`ws_articles_sizes`.`id_article`,

			`ws_articles_sizes`.`code`,
			`ws_articles`.`ctime` 

FROM  `ws_articles_sizes` 
JOIN  `ws_articles` ON  `ws_articles_sizes`.`id_article` =  `ws_articles`.`id`
JOIN  `ws_sizes` ON  `ws_articles_sizes`.`id_size` =  `ws_sizes`.`id` 
JOIN  `ws_articles_colors` ON  `ws_articles_sizes`.`id_color` =  `ws_articles_colors`.`id`  
LEFT JOIN  `ws_order_articles` ON  `ws_articles_sizes`.`id_article` =  `ws_order_articles`.`article_id` 
AND  `ws_articles_sizes`.`id_size` =  `ws_order_articles`.`size` 
AND  `ws_articles_sizes`.`id_color` =  `ws_order_articles`.`color`

WHERE  `ws_articles_sizes`.`count` > 0
AND  `ws_articles`.`active` =  'y' and `ws_articles`.`category_id` in (".$cat.")
GROUP BY  `ws_articles_sizes`.`id` 
ORDER BY  `d` ASC
LIMIT ".$start." , 50";

//die(json_encode(array('start'=>$this->post->start, 'end'=>$this->post->end, 'exit'=>'fak', 'cat'=>$sql)));
			 $articles = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($sql);
			 $mas = array();
			 
			 require_once('PHPExel/PHPExcel.php');
			  require_once("PHPExel/PHPExcel/Writer/Excel5.php");
     $name = 'otchet_articles_cat_'.$this->post->cat.'_'.date('d-m-Y');
     $filename = $name . '.xls';
	 $path1file = INPATH . "backend/views/chart/". $filename;
				
                
				if($start == 0){
				
				$pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle($this->post->cat);
				
                $aSheet->getColumnDimension('A')->setWidth(40);
                $aSheet->getColumnDimension('B')->setWidth(15);
                $aSheet->getColumnDimension('C')->setWidth(18);
                $aSheet->getColumnDimension('D')->setWidth(12);
                $aSheet->getColumnDimension('E')->setWidth(16);
                $aSheet->getColumnDimension('F')->setWidth(18);
				$aSheet->getColumnDimension('G')->setWidth(8);
				$aSheet->getColumnDimension('H')->setWidth(13);
				$aSheet->getColumnDimension('I')->setWidth(10);
				$aSheet->getColumnDimension('J')->setWidth(12);
				$aSheet->getColumnDimension('K')->setWidth(10);
				$aSheet->getColumnDimension('L')->setWidth(8);
				$aSheet->getColumnDimension('M')->setWidth(13);
				$aSheet->getColumnDimension('N')->setWidth(18);

				
                $aSheet->setCellValue('A1', 'Бренд');
                $aSheet->setCellValue('B1', 'Модель');
                $aSheet->setCellValue('C1', 'Размер');
                $aSheet->setCellValue('D1', 'Цвет');
				$aSheet->setCellValue('E1', 'Артикул');
				$aSheet->setCellValue('F1', 'Сезон');
                $aSheet->setCellValue('G1', 'Приход');
                $aSheet->setCellValue('H1', 'Расход');
				$aSheet->setCellValue('I1', 'Возвраты');
				$aSheet->setCellValue('J1', 'Остаток');
                $aSheet->setCellValue('K1', 'Дата добавления');
                $aSheet->setCellValue('L1', 'Дата уценки');
                $aSheet->setCellValue('M1', 'Процент');
				$aSheet->setCellValue('N1', 'Более 1 год на сайте');
				$aSheet->setCellValue('O1', 'Возвратов больше 5 и 1 в остатке');
				$aSheet->setCellValue('P1', 'Уценка больше 50% и возвраты больше чем приход');
				$aSheet->setCellValue('Q1', 'Более 60 дней на сайте и 0 продаж');
				$aSheet->setCellValue('R1', 'Дней с посл. заказа');
				
                $boldFont = array('font' => array('bold' => true));
				
                $aSheet->getStyle('A1:R1')->applyFromArray($boldFont);
				
				}else{
			$pExcel = PHPExcel_IOFactory::load($path1file);
			$pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
               // $aSheet->setTitle('Первый лист');
				
				}
             // if($start == 0){
			 // $i = 2;
			 // }else{
			  $i = $start+2;
			 // }  
			 $sezon = array(1=>'Лето', 2=>'Осень-Весна', 3=>'Зима', 4=>'Всесезон', 5=>'Демисезон');
			 foreach($articles as $a){
			 
			 $aSheet->setCellValue('A' . $i, $a->brand);
			 $aSheet->setCellValue('B' . $i, $a->model);
			 $aSheet->setCellValue('C' . $i, $a->sizes);
			 $aSheet->setCellValue('D' . $i, $a->colors);
			 $aSheet->setCellValue('E' . $i, $a->code);

			 $aSheet->setCellValue('F' . $i,  $sezon[$a->sezon]);
			 
			 $q = "SELECT SUM(  `red_article_log`.`count` ) AS ctn
					FROM red_article_log
					inner join ws_articles on red_article_log.article_id = ws_articles.`id`
					WHERE red_article_log.type_id = 1
						and ws_articles.status = 3
					AND  `red_article_log`.`code` LIKE  '".$a->getCode()."'";
					$prich = wsActiveRecord::findByQueryArray($q)[0]->ctn;
					
			 $aSheet->setCellValue('G' . $i, $prich);

			 $aSheet->setCellValue('H' . $i, $a->sum_order);
			 
			 $aSheet->setCellValue('I' . $i, $a->sum_ret);
			 
			 
			  $s = "SELECT count(ws_order_articles_vozrat.id) as allcount 
					FROM ws_order_articles_vozrat
					WHERE	ws_order_articles_vozrat.status = 0
					and  ws_order_articles_vozrat.`count` > 0
					and ws_order_articles_vozrat.`cod` LIKE  '".$a->getCode()."'
					";
			 
			 $count = wsActiveRecord::findByQueryArray($s)[0]->allcount;
			 
			 $count+=$a->getCount();
			// $count = $a->getCount();
                         
			 $aSheet->setCellValue('J' . $i, $count);
			 $aSheet->setCellValue('K' . $i, date('d.m.Y', strtotime($a->getCtime())));
			 
			 $aSheet->setCellValue('L' . $i, $a->getUcenka()?date('d.m.Y', strtotime($a->getDataUcenki())):'');

			 $aSheet->setCellValue('M' . $i, $a->getUcenka()?$a->getUcenka():'');
			 
			 
			 
				$item_time = strtotime($a->getCtime());
				if($item_time < time()){
				$day = (int)(time() - $item_time) / (24 * 60 * 60);
				}else{
				$day = 0;
				}
				
			$Q = ((int)$day > 60 and $a->sum_order == 0)?$count:0;
				 $aSheet->setCellValue('Q' . $i, $Q);
			if($Q != 0){ 
				 $aSheet->setCellValue('P' . $i, 0);
				 $aSheet->setCellValue('O' . $i, 0);
				 $aSheet->setCellValue('N' . $i, 0);
				 }else{
				 
				 $P = 	($a->getUcenka() >= 50 and $a->sum_ret > $prich)?$count:0; 
					$aSheet->setCellValue('P' . $i, $P);
				if($P != 0){
				 $aSheet->setCellValue('O' . $i, 0);
				 $aSheet->setCellValue('N' . $i, 0);
				}else{
				$O = ($a->sum_ret > 5 and $count == 1)?$count:0;
					$aSheet->setCellValue('O' . $i, $O);
					if($O != 0){
					$aSheet->setCellValue('N' . $i, 0);
					}else{
					$N = (int)$day>365?$count:0;
					$aSheet->setCellValue('N' . $i, $N);
					
					}
				}
				 }

				//$item_order = strtotime($order->at(0)->dat);
                                 $item_order = strtotime($a->getDateOrderOst());
				if($item_order < time()){
				$dey_order = (int)((time() - $item_order) / (24 * 60 * 60));
				}else{
				$dey_order = 0;
				}
                                
			if((int)$dey_order > 1000) {
                            $dey_order = 'не заказывался';
                            
                        }
                        
                                
			 $aSheet->setCellValue('R' . $i, $dey_order);
 
			 
			 $i++;
			 }
			 
			 

                $objWriter = new PHPExcel_Writer_Excel5($pExcel);
				
				
$end = $this->post->end - 50;

		if($end <= $this->post->start){	
$objWriter->save($path1file);
die(json_encode(array('exit'=>"Отчёт сформирован!", 'src'=>'https://www.red.ua/backend/views/chart/'.$filename)));
}else{
$objWriter->save($path1file);
die(json_encode(array('start'=>(int)$this->post->start+=50, 'end'=>(int)$this->post->end, 'cat'=>(int)$this->post->cat)));	
}

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

               // header("Content-type: application/x-msexcel");

                $objWriter->save('php://output');

			
			}elseif ($this->get->type == 20) {
		
 ini_set('memory_limit', '2048M');
	set_time_limit(2800);
     require_once('PHPExel/PHPExcel.php');
	 $name = 'articleexel_article';
                    $kount = 1;
                    $filename = $name . '.xls';
                    $pExcel = new PHPExcel();
                    $pExcel->setActiveSheetIndex(0);
                    $aSheet = $pExcel->getActiveSheet();
                    $aSheet->setTitle('Первый лист');
                    $aSheet->getColumnDimension('A')->setWidth(15);
                    $aSheet->getColumnDimension('B')->setWidth(15);
                    $aSheet->getColumnDimension('C')->setWidth(18);
                    $aSheet->getColumnDimension('D')->setWidth(16);
                    $aSheet->getColumnDimension('E')->setWidth(10);
                    $aSheet->getColumnDimension('F')->setWidth(10);
					 $aSheet->getColumnDimension('G')->setWidth(10);
					  $aSheet->getColumnDimension('H')->setWidth(10);
					   $aSheet->getColumnDimension('I')->setWidth(10);


                    $aSheet->setCellValue('A1', 'Категория');
					$aSheet->setCellValue('B1', 'Бренд');
					$aSheet->setCellValue('C1', 'Артикул');
                    $aSheet->setCellValue('D1', 'Цвет');
                    $aSheet->setCellValue('E1', 'Размер');
                    $aSheet->setCellValue('F1', 'Приход');
                    $aSheet->setCellValue('G1', 'Расход');
					$aSheet->setCellValue('H1', 'Возвраты');
					$aSheet->setCellValue('I1', 'Остаток');


                    $boldFont = array('font' => array('bold' => true));
                    $aSheet->getStyle('A1:I1')->applyFromArray($boldFont);


                    $i = 2;
					
					
$cats = wsActiveRecord::useStatic('Shoparticles')->findByQuery("SELECT * 
FROM  `ws_articles` 
WHERE  `stock` !=  '0'
AND  `active` =  'y'
AND  `ucenka` =20
GROUP BY  `category_id`");
foreach ($cats as $cat){





}


                    $q = 'SELECT  `ws_articles`.`id` ,  `ws_articles_sizes`.`code` AS  `acode` ,  `ws_articles_sizes`.`count` AS  `sklad` ,  `ws_articles`.`model` ,  `ws_articles`.`brand` , `ws_sizes`.`size` AS  `sizes` ,  `ws_articles_colors`.`name` AS  `colors` , SUM(  `ws_order_articles`.`count` ) AS  `sum_order` , sum(if(`ws_order_articles`.`count`=0,1,0)) as `sum_ret`
FROM  `ws_articles` 
JOIN  `ws_articles_sizes` ON  `ws_articles_sizes`.`id_article` =  `ws_articles`.`id` 
JOIN  `ws_sizes` ON  `ws_articles_sizes`.`id_size` =  `ws_sizes`.`id` 
JOIN  `ws_articles_colors` ON  `ws_articles_sizes`.`id_color` =  `ws_articles_colors`.`id` 
LEFT JOIN  `ws_order_articles` ON  `ws_articles`.`id` =  `ws_order_articles`.`article_id` 
AND  `ws_articles_sizes`.`id_size` =  `ws_order_articles`.`size` 
AND  `ws_articles_sizes`.`id_color` =  `ws_order_articles`.`color` 
WHERE  `ws_articles_sizes`.`count` >0
AND  `ws_articles`.`ucenka` = 20
GROUP BY  `ws_articles_sizes`.`code` 
ORDER BY  `ws_articles`.`id` ASC 
LIMIT 0, 500';
                    $articles = wsActiveRecord::findByQueryArray($q);
					
					
					$assoc = array();   
                    foreach ($articles as $article) {	
$s = "SELECT sum(ws_order_articles_vozrat.`count) as allcount 
					FROM ws_order_articles_vozrat
					WHERE	ws_order_articles_vozrat.status = 0
					and  ws_order_articles_vozrat.`count` > 0
					and ws_order_articles_vozrat.`cod` LIKE  '".$article->acode."'
					";

$q = "SELECT SUM(  `red_article_log`.`count` ) AS ctn
					FROM red_article_log
					inner join ws_articles on red_article_log.article_id = ws_articles.`id`
					WHERE red_article_log.type_id = 1
						and ws_articles.status = 3
					AND  `red_article_log`.`code` LIKE  '".$article->acode."'";
					
					 $aSheet->setCellValue('A' . $i, $article->model);
                        $aSheet->setCellValue('B' . $i, $article->brand);
						$aSheet->setCellValue('C' . $i, $article->acode);
                        $aSheet->setCellValue('D' . $i, $article->colors);
                        $aSheet->setCellValue('E' . $i, $article->sizes);
                        $aSheet->setCellValue('F' . $i, wsActiveRecord::findByQueryArray($q)[0]->ctn);
                        $aSheet->setCellValue('G' . $i, $article->sum_order);
						$aSheet->setCellValue('H' . $i, $article->sum_ret);
						$aSheet->setCellValue('I' . $i, $article->sklad+wsActiveRecord::findByQueryArray($s)[0]->allcount);
                        $i++;
						
                    }
                    require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                    $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                    header("Cache-Control: no-cache, must-revalidate");
                    header("Pragma: no-cache");
                    header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

                    $objWriter->save('php://output');

             
		   die('ok');
        }elseif ($this->get->type == 21) { //отчет по пользователям
		
		 set_time_limit(1200);
		 ini_set('memory_limit', '2048M');
		 
		$start = (int)$this->post->start;
		
		$sql = "SELECT 
`ws_customers`.`id`,
`ws_customers`.`customer_status_id`,
`ws_customers`.`customer_type_id`,
`ws_customers`.`first_name`,
`ws_customers`.`middle_name`,
`ws_customers`.`last_name`,
`ws_customers`.`date_birth`,
`ws_customers`.`email`,
`ws_customers`.`phone1`,
`ws_customers`.`ctime`,
`ws_customers`.`utime`,
`ws_customers`.`city`,
`ws_customers`.`skidka`,
`ws_customers`.`obl`,
`ws_customers`.`rayon`,
`ws_customers`.`street`,
`ws_customers`.`admin_coments`
FROM `ws_customers`
where `customer_type_id` = 1
ORDER BY  `ws_customers`.`id` DESC 
LIMIT ".$start." , 100";
$customers = wsActiveRecord::useStatic('Customer')->findByQuery($sql);

			 $mas = array();
			 
			 require_once('PHPExel/PHPExcel.php');
			  require_once("PHPExel/PHPExcel/Writer/Excel5.php");
     $name = 'otchet_customers_'.date('d-m-Y');
     $filename = $name . '.xls';
	 $path1file = INPATH . "backend/views/chart/". $filename;
				
                
				if($start == 0){
				
				$pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('customers');
				
              /*  $aSheet->getColumnDimension('A')->setWidth(40);
                $aSheet->getColumnDimension('B')->setWidth(15);
                $aSheet->getColumnDimension('C')->setWidth(18);
                $aSheet->getColumnDimension('D')->setWidth(12);
                $aSheet->getColumnDimension('E')->setWidth(16);
                $aSheet->getColumnDimension('F')->setWidth(18);
				$aSheet->getColumnDimension('G')->setWidth(8);
				$aSheet->getColumnDimension('H')->setWidth(13);
				$aSheet->getColumnDimension('I')->setWidth(10);
				$aSheet->getColumnDimension('J')->setWidth(12);
				$aSheet->getColumnDimension('K')->setWidth(10);
				$aSheet->getColumnDimension('L')->setWidth(8);
				$aSheet->getColumnDimension('M')->setWidth(13);
				$aSheet->getColumnDimension('N')->setWidth(18);*/

				
                $aSheet->setCellValue('A1', 'id');
                $aSheet->setCellValue('B1', 'Фамилия');
                $aSheet->setCellValue('C1', 'Имя');
                $aSheet->setCellValue('D1', 'Отчество');
				$aSheet->setCellValue('E1', 'Дата рождения');
				$aSheet->setCellValue('F1', 'Email');
                $aSheet->setCellValue('G1', 'Телефон');
                $aSheet->setCellValue('H1', 'Дата регистрации');
				$aSheet->setCellValue('I1', 'Последний визит');
				$aSheet->setCellValue('J1', 'Область');
                $aSheet->setCellValue('K1', 'Район');
                $aSheet->setCellValue('L1', 'Город');
				$aSheet->setCellValue('M1', 'Скидка');
                $aSheet->setCellValue('N1', 'Бан');
				$aSheet->setCellValue('O1', 'Общее количество заказов');
				$aSheet->setCellValue('P1', 'Фактические заказы');
				$aSheet->setCellValue('Q1', 'Заказано единиц');
				$aSheet->setCellValue('R1', 'Куплено едениц');
				$aSheet->setCellValue('S1', 'Сумма заказов');
				$aSheet->setCellValue('T1', 'Дата последнего заказа');
				
                $boldFont = array('font' => array('bold' => true));
				
                $aSheet->getStyle('A1:T1')->applyFromArray($boldFont);
				
				}else{
			$pExcel = PHPExcel_IOFactory::load($path1file);
			$pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
				}
			  $i = $start+2;

			 $sezon = array(1=>'Лето', 2=>'Осень-Весна', 3=>'Зима', 4=>'Всесезон', 5=>'Демисезон');
			 foreach($customers as $c){
			 
			 $aSheet->setCellValue('A' . $i, @$c->id);
			 $aSheet->setCellValue('B' . $i, @$c->first_name);
			 $aSheet->setCellValue('C' . $i, @$c->middle_name);
			 $aSheet->setCellValue('D' . $i, @$c->last_name);
			 $aSheet->setCellValue('E' . $i, @$c->date_birth);
			 $aSheet->setCellValue('F' . $i, @$c->email);
			 $aSheet->setCellValue('G' . $i, @$c->phone1);
			 $aSheet->setCellValue('H' . $i, @$c->ctime);
			 $aSheet->setCellValue('I' . $i, @$c->utime);
			 $aSheet->setCellValue('J' . $i, @$c->obl);
			 $aSheet->setCellValue('K' . $i, @$c->rayon);
			 $aSheet->setCellValue('L' . $i, @$c->city);
			 $aSheet->setCellValue('M' . $i, @$c->skidka);
			 $aSheet->setCellValue('N' . $i, @$c->customer_status_id);
			 $aSheet->setCellValue('O' . $i, @$c->getCountAllOrder());
			 $aSheet->setCellValue('P' . $i, @$c->getCountFactOrder());
			 $aSheet->setCellValue('Q' . $i, @$c->getCountAllArticlesOrder());
			 $aSheet->setCellValue('R' . $i, @$c->getCountFactArticlesOrder());
			 $aSheet->setCellValue('S' . $i, @$c->getSumOrder());
			 $aSheet->setCellValue('T' . $i, $c->getDateOrderP()?date('d-m-Y', strtotime($c->getDateOrderP())):'');
			
			 $i++;
			 }
			 
			 

                $objWriter = new PHPExcel_Writer_Excel5($pExcel);
				
				
$end = $this->post->end - 100;

		if($end <= $this->post->start){	
$objWriter->save($path1file);
die(json_encode(array('exit'=>"Отчёт сформирован!", 'src'=>'https://www.red.ua/backend/views/chart/'.$filename)));
}else{
$objWriter->save($path1file);
die(json_encode(array('start'=>(int)$this->post->start+=100, 'end'=>(int)$this->post->end, 'cat'=>(int)$this->post->cat)));	
}

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
                $objWriter->save('php://output');

			
			}elseif($this->get->type == 22){
			set_time_limit(1200);
		 ini_set('memory_limit', '2048M');
			$id = $this->get->id;
			require_once('PHPExel/PHPExcel.php');
			  require_once("PHPExel/PHPExcel/Writer/Excel5.php");
     $name = 'otchet_customers_'.date('d-m-Y');
     $filename = $name . '.xls';
	 
				$pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
				$boldFont = array('font' => array('bold' => true));
				
			switch($id){
			case 1: $aSheet->setTitle('top_25_s_pokupkamy');
				$sql =""; 
					
				//$aSheet->setCellValue('A1', 'id');
                //$aSheet->setCellValue('B1', 'Фамилия');
                //$aSheet->setCellValue('C1', 'Имя');
                //$aSheet->setCellValue('D1', 'Отчество');
				//$aSheet->setCellValue('E1', 'Дата рождения');
				//$aSheet->setCellValue('F1', 'Email');
                //$aSheet->setCellValue('G1', 'Телефон');
                //$aSheet->setCellValue('H1', 'Дата регистрации');
				//$aSheet->setCellValue('I1', 'Последний визит');
				//$aSheet->setCellValue('J1', 'Область');
				//$aSheet->setCellValue('K1', 'Район');
                //$aSheet->setCellValue('L1', 'Город');
				//$aSheet->setCellValue('M1', 'Скидка');
                //$aSheet->setCellValue('N1', 'Бан');
				//$aSheet->setCellValue('O1', 'Общее количество заказов');
				//$aSheet->setCellValue('P1', 'Фактические заказы');
				//$aSheet->setCellValue('Q1', 'Заказано единиц');
				//$aSheet->setCellValue('R1', 'Куплено едениц');
				//$aSheet->setCellValue('S1', 'Сумма заказов');
				//$aSheet->setCellValue('T1', 'Дата последнего заказа');

                $aSheet->getStyle('A1:T1')->applyFromArray($boldFont);					
				break;
			case 2: die($id); 
			$sql ="";
					$type = 'bolee_goda_ne_zakazivaly';	
				break;
			case 3: die($id);
			$sql ="";
					$type = 'bolee_goda_ne_zakazivaly';						
				break;
			case 4: 
					$aSheet->setTitle('bolee_goda_ne_zakazivaly');
					$sql = "SELECT  `ws_customers`.`id` ,  `ws_customers`.`utime` ,  `ws_customers`.`deposit` ,  `ws_customers`.`real_skidka` , `ws_customers`.`email` , COUNT(  `ws_orders`.`id` ) AS count_order, SUM(  `ws_orders`.`amount` + `ws_orders`.`deposit` ) AS sum_order
FROM  `ws_customers` 
JOIN  `ws_orders` ON  `ws_customers`.id =  `ws_orders`.customer_id
WHERE  `ws_customers`.`utime` < ( NOW( ) - INTERVAL 1 YEAR ) 
AND  `ws_customers`.`deposit` = 0
group by `ws_customers`.`id`
ORDER BY  `ws_customers`.`id` DESC";
					$customers = wsActiveRecord::useStatic('Customer')->findByQueryArray($sql);
				$aSheet->setCellValue('A1', 'idКлиента');
                $aSheet->setCellValue('B1', 'email');
                $aSheet->setCellValue('C1', 'ПоследнийВизит');
                $aSheet->setCellValue('D1', 'Скидка');
				$aSheet->setCellValue('E1', 'Депозит');
				$aSheet->setCellValue('F1', 'КоллЗаказов');
                $aSheet->setCellValue('G1', 'СуммаЗаказов');
                $aSheet->getStyle('A1:G1')->applyFromArray($boldFont);
				$i = 2;
			foreach($customers as $c){
			 $aSheet->setCellValue('A' . $i, @$c->id);
			 $aSheet->setCellValue('B' . $i, @$c->email);
			 $aSheet->setCellValue('C' . $i, @date('d.m.Y', strtotime($c->utime)));
			 $aSheet->setCellValue('D' . $i, @$c->real_skidka);
			 $aSheet->setCellValue('E' . $i, @$c->deposit);
			 $aSheet->setCellValue('F' . $i, @$c->count_order);
			 $aSheet->setCellValue('G' . $i, @$c->sum_order);
			 $i++;
			 }
				break;
			default: $type = 'all';	
			}
			

			 $objWriter = new PHPExcel_Writer_Excel5($pExcel);
			 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
                $objWriter->save('php://output');
			}elseif ($this->get->type == 'getbrends') { 
		 $id = $this->get->id;
		 $sql = "SELECT  `as`.`code` ,  `d`.`id_articles` ,  `a`.`brand` ,  `a`.`brand_id` ,  `a`.`model` ,  `a`.`price` 
FROM  `ws_desires` AS  `d` 
LEFT JOIN  `ws_articles_sizes` AS  `as` ON  `d`.`id_articles` =  `as`.`id_article` 
LEFT JOIN  `ws_articles` AS  `a` ON  `d`.`id_articles` =  `a`.`id` 
WHERE EXISTS (
SELECT  `d`.`id_articles` 
FROM  `ws_desires`
)
and `a`.`brand_id` = ".$id."
GROUP BY  `a`.`model`";
$mas = wsActiveRecord::findByQueryArray($sql);
$text = 'Модель <select name="model">
	<option value="Модель">Модель</option>';
		foreach ($mas as $model) { 
		$text .='
            <option value="'.$model->model.'">'.$model->model.'</option>
			';
         }
		$text .='</select>';
die($text);		
		 }elseif ($this->get->type == 'getmodels') { 
		 $name = $this->get->name;
		 $sql = "SELECT  `as`.`code` ,  `d`.`id_articles` ,  `a`.`brand` ,  `a`.`brand_id` ,  `a`.`model` ,  `a`.`price` 
FROM  `ws_desires` AS  `d` 
LEFT JOIN  `ws_articles_sizes` AS  `as` ON  `d`.`id_articles` =  `as`.`id_article` 
LEFT JOIN  `ws_articles` AS  `a` ON  `d`.`id_articles` =  `a`.`id` 
WHERE EXISTS (
SELECT  `d`.`id_articles` 
FROM  `ws_desires`
)
and `a`.`model` = '".$name."'
GROUP BY  `a`.`brand_id`";
$mas = wsActiveRecord::findByQueryArray($sql);
$text = 'Бренд <select name="brend">
	<option value="0">Бренд</option>';
		foreach ($mas as $brand) { 
		$text .='
            <option value="'.$brand->brand_id.'">'.$brand->brand.'</option>
			';
         }
		$text .='</select>';
die($text);		
		 }
		 			
	}
        //end get
        
        echo $this->render('shop/otchet.tpl.php');
    }

    public function eventsAction()
    {
        $this->view->events = wsActiveRecord::useStatic('Event')->findAll();
        echo $this->render('event/event.tpl.php');
    }

    public function userseventAction()
    {
        if ($this->get->id) {
            $users = wsActiveRecord::useStatic('EventCustomer')->findAll(array('event_id' => $this->get->id));
            if ($users->count()) {
               // $obj_users = array();
               // foreach ($users as $item) {
                 //   $obj_users[] = new Customer($item->getCustomerId());
               // }
                $this->view->subscribers = $users;
                echo $this->render('event/userlist.tpl.php');

            } else {
                $this->_redir('events');
            }
        } else {
            $this->_redir('events');
        }

    }

    public function eventAction()
    {
        $event = new Event((int)$this->get->id);
        if (count($_POST)) {
            foreach ($_POST as &$value)
                $value = stripslashes($value);
            $error = array();
            if (!$_POST['name']) {
                $error[] = 'Введите название';
            }
            if (!$_POST['discont']) {
                $error[] = 'Введите скидку';
            }
            if (!$_POST['start']) {
                $error[] = 'Введите начало';
            }
            if (!$_POST['finish']) {
                $error[] = 'Введите окончание';
            }
            $start = strtotime($_POST['start']);
            $finish = strtotime($_POST['finish']);
            if ($start > $finish) {
                $error[] = 'Начало не может быть больше окончания';
            }


            if (count($error)) {
                $this->view->errors = $error;
            } else {
                $this->post->start = date('Y-m-d', $start);
                $this->post->finish = date('Y-m-d', $finish);
                $event->setPublick(0);
                $event->setDisposable(0);
                $event->import($this->post);
                $event->save();
                $this->view->saved = 1;
            }
        }
        $this->view->event = $event;
        echo $this->render('event/edit.tpl.php');
    }

   

    public function editskidkabyorderAction()
    {
        if ($this->get->id) {
            $order = new Shoporders($this->get->id);
            if (!$order->getId()) {
                $this->_redirect('/admin/');
            }
            if (count($_POST)) {
                $order->setEventSkidka((int)$_POST['order_skidka']);
                $order->save();
                /* $order->updateDeposit($this->user->getId());*/
                foreach ($_POST as $kay => $val) {
                    $ids = explode('_', $kay);
                    if ($ids[0] == 'skidka' and isset($ids[1])) {
                        $article = new Shoporderarticles($ids[1]);
                        $article->setEventSkidka($val);

                        $article->save();
                    }
                }
				// echo $this->render('event/orderedit.tpl.php');
				//$this->_redir('shop-orders/editskidkabyorder/id/' . $this->get->getId());
            }
            $this->view->order = $order;
			
            echo $this->render('event/orderedit.tpl.php');

        } else {
            $this->_redirect('/admin/');
        }

    }

    public function topmenuAction()
    {
        if ($this->get->delete) {
            $new = new TopMenu($this->get->delete);
            if ($new->getId()) {
                $new->destroy();
            }
            $this->_redir('topmenu');
        }

        if (count($_POST)) {
		if(isset($_POST['save'])){
            $menus = wsActiveRecord::useStatic('TopMenu')->findAll();
            foreach ($menus as $m) {
                $m->setTitle(@$_POST['title_' . $m->getId()]);
				$m->setTitleUk(@$_POST['title_uk_' . $m->getId()]);
                $m->save();
            }
            if ($_POST['new_'] and $_POST['newurl_']) {
                $new = new TopMenu();
                $new->setTitle($_POST['new_']);
				 $new->setTitleUk($_POST['new_uk_']);
                $new->setUrl($_POST['newurl_']);
                $new->save();
            }
			}
        }
        $this->view->menus = wsActiveRecord::useStatic('TopMenu')->findAll();

        echo $this->render('page/topmenu.tpl.php');
    }
	public function footermenuAction()
    {
        if ($this->get->delete) {
            $new = new FooterMenu($this->get->delete);
            if ($new->getId()) {
                $new->destroy();
            }
            $this->_redir('footermenu');
        }

        if (count($_POST)) {
		if(isset($_POST['save'])){
            $menus = wsActiveRecord::useStatic('FooterMenu')->findAll();
            foreach ($menus as $m) {
                $m->setTitle(@$_POST['title_' . $m->getId()]);
				$m->setTitleUk(@$_POST['title_uk_' . $m->getId()]);
                $m->setUrl(@$_POST['url_' . $m->getId()]);
                $m->save();
            }
            if ($_POST['new_'] and $_POST['newurl_']) {
                $new = new FooterMenu();
                $new->setTitle($_POST['new_']);
				$new->setTitleUk($_POST['new_uk_']);
                $new->setUrl($_POST['newurl_']);
                $new->save();
            }
			}
        }
        $this->view->menuf = wsActiveRecord::useStatic('FooterMenu')->findAll();

        echo $this->render('page/footermenu.tpl.php');
    }

    public function getusertoexcelAction()
    {

        ini_set('memory_limit', '3024M');
        set_time_limit(0);
        $users = wsActiveRecord::useStatic('Customer')->findByQuery('SELECT * FROM ws_customers');
        $name = 'userexel';
        $filename = $name . '.csv';
        $str = '№ клиента;Login;Имя;Компания;Email;Телефон;Дата регистрации;Город;Скидка' . "\r";

        foreach ($users as $user) {
            $str .= $user->getId() . ';' . str_replace(';', '', $user->getUsername()) . ';' . str_replace(';', '', $user->getFullname()) . ';' .
                str_replace(';', '', $user->getCompanyName()) . ';' . str_replace(';', '', $user->getEmail()) . ';' . str_replace(';', '', $user->getPhone1()) . ';' .
                date('d-m-Y', strtotime($user->getCtime())) . ';' . str_replace(';', '', $user->getCity()) . ';' . $user->getRealSkidka() . '%' . "\r";
        }


        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        header("Content-type: application/csv");
        die(iconv('utf-8', 'windows-1251', $str));


    }

    public function getusertoexcelspecAction()
    {

        ini_set('memory_limit', '3024M');
        set_time_limit(0);
        $users = wsActiveRecord::useStatic('Customer')->findByQuery('SELECT * FROM ws_customers WHERE id in(175,883,3436,4720,1116,88,1701,3747,4835,1492,1543,76,252,22,6021,1770,2138,537,945,1027,1171,2312,601,296,435,5048,3856,1872,522,2488,1323,350,322,2026,675,86,1201,4180,1070,1984,3286,3637,566,1472,3918,3172,585,903,821,516,2088,598,1118,3077,2852,2007,290,3138,6601,510,2824,553,1358,2730,488,4319,3231,1758,1257,2656,3789,355,4015,2993,74,5640,3445,2320,457,1,1585,2696,8134,94,6698,335,3182,1819,357,5630,4823,135,2180,150,641,4114,2987,3734,5054,4078,4178,5442,1096,68,658,318,404,3558,8809,2070,5032,3903,610,6535,361,2269,1967,1688,6371,3283,1998,300,2108,2336,119,6862,1370,1903,454,872,489,2809,4659,104,1902,1702,2893,387,842,2745,1049,4146,6986,1597,779,1141,1587,4505,5915,2286,6980,3977,4003,983,6217,3368,7685,3712,2995,1924,2414,1738,6406,1305,95,2647,3133,249,966,5041,7380,5816,652,2462,432,5204,3150,8089,3820,2703,1905,492,1242,3057,1606,2104,3328,2566,3924,449,4167,3572,5072,2439,2174,3385,602,6474,3470,2475,354,1123,6456,479,1,15,17,69,88,100,175,310,322,350,492,7504,601,641,649,698,743,873,903,966,969,1114,1115,1138,1158,1242,1254,1357,1358,1367,1429,1535,1585,1758,1765,1927,1943,2053,2181,2183,2237,2238,2261,2298,6986,2362,2385,2739,2745,2875,2893,2944,2993,3057,3470,3599,3655,3683,3747,3756,3964,4015,4232,4160,4319,4493,4505,4533,4723,4873,4942,5061,5091,5141,5182,5226,5337,5533,5491,5508,5535,5590,5607,5795,5872,5960,5988,6021,6156,6162,6176,6179,6332,6447,6503,6605,6652,6684,6698,6743,6741,6742,6858,6862,6923,6956,6980,7071,7101,7146,7214,7230,7284,7313,7341,7668,7576,7622,7825,8002,8005,8071,8254,8311,8357,8378,8451,8454,8474,8609,8630,8685,8696,8717,8734,8890,8985,9011,9032,9135,9143,9411,9436,9452,9509,9623,9626,9692,9714,9733,9768,9972,10065,10083,10087,10102,10113,10122,10140,10173,10176,10347,10377,10441,10615,10676,10755,10756,10757,10763,10833,10871,10922,10982)');
        $name = 'userexel';
        $filename = $name . '.csv';

        @unlink($_SERVER['DOCUMENT_ROOT'] . '/tmp/___s_users.csv');
        $str = iconv('UTF-8', 'WINDOWS-1251', '№ клиента;Login;Имя;Телефон;Email;K-во заказов;Оплаченых;Сумма заказов;Скидка' . "\r");

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/tmp/___s_users.csv', $str, FILE_APPEND);
        foreach ($users as $user) {
            $orders = wsActiveRecord::useStatic('Shoporders')->findByQuery('SELECT * FROM ws_orders WHERE  ws_orders.status IN (0,1,3,4,6,8,9,10,11,13,14,15,16) AND customer_id =' . $user->getId());
            $count = 0;
            $count_o = 0;
            $sum = 0;
            foreach ($orders as $order) {
                $count++;
                if ($order->getStatus() == 8) {
                    $count_o++;
                }
                $sum += $order->getAmount();
            }

            $str = iconv('UTF-8', 'WINDOWS-1251', $user->getId() . ';' . str_replace(';', '', $user->getUsername()) . ';' . str_replace(';', '', $user->getFullname()) . ';' .
                str_replace(';', '', $user->getPhone1()) . ';' . str_replace(';', '', $user->getEmail()) . ';' .
                $count . ';' . $count_o . ';' . $sum . ';' . str_replace(';', '', $user->getDiscont(false, 0, true)) . "\r");
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/tmp/___s_users.csv', $str, FILE_APPEND);
        }


        chmod($_SERVER['DOCUMENT_ROOT'] . '/tmp/___s_users.csv', 0777);


        die('ok');


    }

    //-------colors
    public function colorsAction()
    {
        $data = array();
        $this->view->colors = wsActiveRecord::useStatic('Shoparticlescolor')->findAll($data, array('name' => 'ASC'));
        echo $this->render('color/list.tpl.php', 'index.php');
    }

    public function colorAction()
    {
        if ((int)$this->get->del > 0) {
            $sub = new Shoparticlescolor((int)$this->get->del);
            if (!$sub->articles->count()) {
                $sub->destroy();
            }
            $this->_redir('colors');
        }

        $sub = new Shoparticlescolor($this->get->getId());

        if (count($_POST)) {
            foreach ($_POST as &$value)
                $value = stripslashes($value);
            $errors = array();

            if (!@$_POST['name'])
                $errors[] = $this->trans->get('Please fill name');

            $sub->import($_POST);

            if (!count($errors)) {
                $sub->save();
                $this->view->saved = 1;

            } else {
                $this->view->errors = $errors;
            }

            $this->view->errors = $errors;
        }
        $this->view->sub = $sub;
        echo $this->render('color/edit.tpl.php', 'index.php');


    }


    //-------Sizes
    public function sizesAction()
    {
        $this->view->sizes = wsActiveRecord::useStatic('Size')->findAll([], ['category_id' => 'ASC', 'size' => 'ASC']);
        echo $this->render('size/list.tpl.php', 'index.php');
    }

    public function sizeAction()
    {
        if ((int)$this->get->del > 0) {
            $sub = new Size((int)$this->get->del);
            if (!$sub->articles->count()) {
                $sub->destroy();
            }
            $this->_redir('sizes');
        }

        $sub = new Size($this->get->getId());

        if (count($_POST)) {
            foreach ($_POST as &$value){ $value = stripslashes($value);}
            
            $errors = [];

            if (!$_POST['size']){ $errors[] = $this->trans->get('Please fill size');}

            $sub->import($_POST);

            if (!count($errors)) {
                $sub->save();
                $this->view->saved = 1;

            } else {
                $this->view->errors = $errors;
            }

            $this->view->errors = $errors;
        }
        $this->view->sub = $sub;
        echo $this->render('size/edit.tpl.php', 'index.php');


    }

    public function adminsAction()
    {
        $this->view->admins = wsActiveRecord::useStatic('Customer')->findAll(array('customer_type_id > 1'), array('customer_type_id'=>'DESC'));
        echo $this->render('admins/list.tpl.php');
    }

    public function admineditAction()
    {

        $customer = new Customer($this->get->edit);
        if ($this->get->new_admin == 1) {
            if ($customer->getId()) {
                $customer->setCustomerTypeId(3);
                $customer->save();
            }
        }
        if (!$customer->getId() or $customer->getCustomerTypeId() < 2) $this->_redir('admins');
        $pages = AdminRights::getPages();
        foreach ($pages as $pg) {
            if (!AdminRights::issetRights($customer->getId(), $pg->getId())) {
                $right = new AdminRights();
                $right->setAdminId($customer->getId());
                $right->setPageId($pg->getId());
                $right->setRight(0);
                $right->setView(0);
                $right->save();
            }
        }
        if (count($_POST)) {
            $customer->setCustomerTypeId($_POST['customer_type_id']);
            $customer->save();
            if ($_POST['customer_type_id'] == 1) {
                AdminRights::destroyRights($customer->getId());
                $this->_redir('admins');
            }

            foreach ($_POST['right'] as $key => $val) {
                AdminRights::setRights($customer->getId(), $key, $val);
                if (isset($_POST['view'][$key])) {
                    AdminRights::setViews($customer->getId(), $key, $_POST['view'][$key]);
                }
            }

            if (@$_POST["all_right"]["do_pay"]) {
                $right = wsActiveRecord::useStatic('wsRight')->findFirst(array('customer_id' => $customer->getId(), 'name' => 'do_pay'));
                $right->setActive(1);
                $right->save();
            } else {
                $right = wsActiveRecord::useStatic('wsRight')->findFirst(array('customer_id' => $customer->getId(), 'name' => 'do_pay'));
                $right->setActive(0);
                $right->save();
            }
            if (@$_POST["all_right"]["edit_my_order"]) {
                $right = wsActiveRecord::useStatic('wsRight')->findFirst(array('customer_id' => $customer->getId(), 'name' => 'edit_my_order'));
                $right->setActive(1);
                $right->save();
            } else {
                $right = wsActiveRecord::useStatic('wsRight')->findFirst(array('customer_id' => $customer->getId(), 'name' => 'edit_my_order'));
                $right->setActive(0);
                $right->save();
            }


        }
        $customer_rights = array();
        foreach (AdminRights::getAdminRights($customer->getId()) as $rights) {
            $customer_rights[$rights->getPageId()]['right'] = $rights->getRight();
            $customer_rights[$rights->getPageId()]['view'] = $rights->getView();
        }

        $this->view->rights = $customer_rights;
        $this->view->admin = $customer;
        echo $this->render('admins/edit.tpl.php');
    }

    //------Vozrat
    public function vozvratsAction()
    {
	 $this->view->order_status = array(
            0 => 'Новый',
            1 => 'Принят',
            2 => 'Удален без возврата',
            3 => 'Возврат в заказ',
        );
	
			if($this->post->method == 'priyom') {
			$mas = explode(',', $this->post->id);
			$result = array();
			foreach($mas as $m){
            $c = new ShoporderarticlesVozrat($m);
            if ($c && $c->getId() && $c->getCount() > 0) {
                $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $c->getArticleId(), 'id_size' => $c->getSize(), 'id_color' => $c->getColor()));
				if($article){
                                     $pos = strpos($article->code, 'SR');
                                    if($pos === false){
					$artic = new Shoparticles($c->getArticleId());
					if($article->getCount() == 0 and $artic->getCategoryId() != 16 and false){
					if(wsActiveRecord::useStatic('Returnarticle')->count(array('code' => $article->getCode(), 'utime is null')) > 0){
					$this->sendMailAddCount($article->getCode(), $article->getIdArticle());
					}	
						}
                                                
			    $article->setCount($article->getCount() + $c->getCount());
                $artic->setStock($artic->getStock() + $c->getCount());
				$article->save();
				$artic->save();
                                }
				$c->setCount(0);
				$c->setStatus(1);
				$c->setUtime(date("Y-m-d h:i:s"));
				$c->setUserPr($this->user->getId());
                $c->save();	
			   $result['send'] = 1;
				$result['text'][] = 'Товар '.$c->cod.' принят!';
				$old_size = new Size($c->getSize());
			$old_color = new Shoparticlescolor($c->getColor());
			$text = $c->getTitle(). ' ' . $old_size->getSize() . ' ' . $old_color->getName();
				OrderHistory::newHistory($this->user->getId(), $c->getOrderId(), 'Прийом товара с возврата.<br>Накладна №'.$this->post->nakladna, $text, $c->getArticleId());
				}else{
				$result['send'] = 0;
				$result['text'][] = 'Ошибка! Товар '.$c->cod.' в базе не найден!';
				 wsLog::add('Ошибка прийома ' . $c->Title() . ' - ' . $c->getArticleId(), 'ERROR priyom article');
				$this->view->error = "Не удается принять товар, ".$c->Title().". Попробуйте снова!";
				} 
            }else{
			$result['send'] = 0;
			$result['text'][] = 'Ошибка в товаре '.$c->cod.'. Товар не принят!';
			}
			}
				die(json_encode($result));
        }elseif($this->post->method == 'deleteshop') {
		$mas = explode(',', $this->post->id);
		$result = array();
			if($this->post->mes){
			$mes = $this->post->mes;
			}else{
			$mes = '';
			}
			foreach($mas as $m){
            $c = new ShoporderarticlesVozrat($m);
            if ($c && $c->getId() && $c->getCount() > 0) { 
									$log_text = 'Удаление с возвратов';
                                                                        if($mes){$log_text .= '<br> ( '.$mes.' )';}
									$log = new Shoparticlelog();
                                    $log->setCustomerId($this->user->getId());
                                    $log->setUsername($this->user->getUsername());
                                    $log->setArticleId($c->getArticleId());
									if ($c->getSize() and $c->getColor()) { 
									$size = new Size($c->getSize());
									$color = new Shoparticlescolor($c->getColor());
                                        $log->setInfo($size->getSize() . ' ' . $color->getName());
                                    }
									$log->setTypeId(2);
									$log->setCount($c->getCount());
									$log->setComents($log_text);
									$log->setCode($c->getCod());
									$log->save();
                $c->setCount(0);
				$c->setStatus(2);
				$c->setUtime(date("Y-m-d h:i:s"));
				$c->setUserPr($this->user->getId());
                $c->save();
				

			$result['send'] = 1;
			$result['text'] = 'Товар удален!';
			$result['ss'] = $mes;
			//$old_size = new Size($c->getSize());
			//$old_color = new Shoparticlescolor($c->getColor());
			$text = $c->getTitle(). ' ' . $size->getSize() . ' ' . $color->getName();
OrderHistory::newHistory($this->user->getId(), $c->getOrderId(), 'Удаление без возврата.<br>Накладна №'.$this->post->nakladna, $text, $c->getArticleId());
            } else {
			$result['send'] = 0;
			$result['text'] = 'Ошибка. Товар не удален!';
			$result['ss'] = $mes;
			}
			}
				die(json_encode($result));
        }elseif(($this->post->method == 'return_order')){
		if($this->post->mes){
			$mes = $this->post->mes;
			}else{
			$mes = '';
			}
		$result = array();
		$c = new ShoporderarticlesVozrat((int)$this->post->getId());
		$mes = $c;
            if ($c && $c->getId() && $c->getCount() > 0) { 
$article = wsActiveRecord::useStatic('Shoporderarticles')->findFirst(array("order_id"=>$c->order_id,"article_id" => $c->getArticleId(), "artikul LIKE '".$c->getCod()."' "));
			if(@$article && $article->getCount() == 0){
			$article->setCount($article->getCount()+$c->getCount());
			$c->setCount(0);
			$c->setStatus(3);
			$article->save();
			$c->setUtime(date("Y-m-d h:i:s"));
			$c->setUserPr($this->user->getId());
			$c->save();
			$result['send'] = 1;
			$result['text'][] = 'Товар '.$c->cod.' вернулся в заказ!';
			$result['ss'] = $mes;
			$old_size = new Size($c->getSize());
			$old_color = new Shoparticlescolor($c->getColor());
			$text = $c->getTitle(). ' ' . $old_size->getSize() . ' ' . $old_color->getName();
				OrderHistory::newHistory($this->user->getId(), $c->getOrderId(), 'Возврат в заказ.', $text, $c->getArticleId());
			}else{
			$result['send'] = 0;
			$result['text'] = 'Ошибка. Товар вернуть не удалось!';
			$result['ss'] = $mes;
			}
			}
		die(json_encode($result));
		}
		
		$order_by = array('id' => 'DESC');
        $data = [];
	if($_GET){
        if (isset($_GET['order']) and $_GET['order'] !='') {
            $data['order_id'] = (int)$_GET['order'];
        }
        if (isset($_GET['articul']) and strlen($_GET['articul']) > 0) {
            $data[] = 'cod LIKE "%' . trim($_GET['articul']) . '%"';
        }
        if (isset($_GET['delivery']) and (int)$_GET['delivery'] > 0) {
        $data['delivery'] = (int)$_GET['delivery'];
        }
        if (isset($_GET['status']) and $_GET['status'] !='') {
		$order_by = array('id' => 'DESC');
            $data['status'] = (int)$_GET['status'];
        }
		if(isset($_GET['admin']) and (int)$_GET['admin'] > 0){
		 $data[] = ' ( user = '.(int)$_GET['admin'].' or user_pr = '.(int)$_GET['admin'].' )';
		}
		if(isset($_GET['create_from']) and $_GET['create_from'] != ''){
		 $data[] = 'ctime >= '.date('Y-m-d h:i:s', strtotime($_GET['create_from']));
		}
		if(isset($_GET['create_to']) and $_GET['create_to'] != ''){
		 $data[] = 'ctime <= '.date('Y-m-d h:i:s', strtotime($_GET['create_to']));
		}
		if(isset($_GET['deposit']) and $_GET['deposit'] !=''){
		 $data['deposit'] = (int)$_GET['deposit'];
		}
		
		
        $onPage = 100;
        $page = !empty($this->get->page) && (int)$this->get->page ? (int)$this->get->page : 1;
        $startElement = ($page - 1) * $onPage;
        $total = wsActiveRecord::useStatic('ShoporderarticlesVozrat')->count($data);
        $this->view->totalPages = ceil($total / $onPage);
        $this->view->count = $total;
        $this->view->page = $page;
        $this->view->start = ($page == 1) ? 1 : $onPage * ($page - 1);
        $this->view->end = $onPage * ($page - 1) + $onPage;
        $this->view->articles = wsActiveRecord::useStatic('ShoporderarticlesVozrat')->findAll($data, $order_by, array($startElement, $onPage));
		}
        echo $this->render('return_articles/vozvrats.tpl.php');
    }

    public function vozratAction()
    {
	
	if($this->post->method == 'add_order_vozvrat'){ 
	$result = array();
	$mas = explode(',', $this->post->order);
	if(count($mas)> 0){
	foreach($mas as $k=>$r){
	$order = wsActiveRecord::useStatic('Shoporders')->findFirst(array('id'=>(int)$r, 'status in(8,13)'));
	$c_or = wsActiveRecord::useStatic('ShopordersVozrat')->count(array("order_id"=>(int)$r, "date_create >= '".date('Y-m-d 00:00:00')."' "));
	if($order and !$c_or){
	$v = new ShopordersVozrat();
	$v->setOrderId($order->id);
	$v->setDateCreate(date('Y-m-d H:i:s'));
	$v->setAdminCreate($this->user->id);
	$v->setCustomerId($order->customer_id);
	$v->save();
	$result['ok'][] = $r;
	}else{
	$result['error'][] = $r;
	
		if ($c_or) { $result['message'] = ' уже принят на возврат!!!';}
		if (!$order) { $result['message'] = ' в статусе с которого нельзя принимать на возврат!!!';}
	}
	}
	
	}
	die(json_encode($result));
	}elseif($this->get->method == 'forma103'){
	$ids = explode(',', $this->get->ids);
	$this->view->order = wsActiveRecord::useStatic('ShopordersVozrat')->findAll(array('id in('.$this->get->ids.')'));
	echo $this->render('', 'return_articles/forma103.ukr.post.php');
	exit();
	}elseif($this->get->method == 'forma_buh'){
	$ids = explode(',', $this->get->ids);
	$this->view->orders = wsActiveRecord::useStatic('ShopordersVozrat')->findAll(array('id in('.$this->get->ids.')'));
	echo $this->render('', 'return_articles/forma_buh.ukr.post.php');
	exit();
	}elseif($this->post->method == 'go_mail_forma103'){
	//$ids = explode(',', $this->post->ids);
	$orders = wsActiveRecord::useStatic('ShopordersVozrat')->findAll(array('id in('.$this->post->ids.')'));
	$message = '<div>';
	
	foreach($orders as $vozrat){
	if($vozrat->getStatus() != 3){
	$ord = new Shoporders((int)$vozrat->order_id);
	$vozrat->setStatus(3);
	$vozrat->setDateObrabotan(date('Y-m-d H:i:s'));
	$vozrat->setAdminObrabotan($this->user->id);					
	$vozrat->save();
	$spos = [1=>'На депозин', 2=>'Поштовий переказ', 3=>'Повернення на картку', 4 => 'ФОП возврат'];
	$remark = new Shoporderremarks();
        $data = [
            'order_id' => $vozrat->order_id,
            'date_create' => date("Y-m-d H:i:s"),
            'remark' => $spos[$vozrat->sposob].' '.($vozrat->amount+$vozrat->dop_suma).' грн.',
            'name' => $this->user->getMiddleName()
                        ];
        $remark->import($data);
        $remark->save();
						
	$text = 'Доброго дня!<br>';
        if($vozrat->sposob == 2){
        $text.=$ord->middle_name.' '.$ord->name.', Вам відправлено поштовий переказ '.($vozrat->amount+$vozrat->dop_suma).' грн. за повернення замовлення №'.$ord->id.'<br>Місце отримання, поштове відділення Укр.Пошти: '.$ord->getIndex();
         EmailLog::add('Поштовий переказ за замовлення №'.$ord->id, $text, 'shop', $ord->customer_id,  $ord->id); //сохранение письма отправленного пользователю
	SendMail::getInstance()->sendEmail($ord->email, $ord->middle_name.' '.$ord->name, 'Поштовий переказ за замовлення №'.$ord->id, $text, '', '', 'return@red.ua', 'RED.UA', 2, 'return@red.ua', $this->user->getMiddleName());
	
	$message.='<p>Відправлено поштовий переказ '.($vozrat->amount+$vozrat->dop_suma).'грн. за замовлення №'.$ord->id.'</p><br/>'; 
        }else{
	$text.=$ord->middle_name.' '.$ord->name.', Вам відправлено повернення коштів на картку '.($vozrat->amount+$vozrat->dop_suma).' грн. за повернення замовлення №'.$ord->id;
EmailLog::add('Повернення на картку за замовлення №'.$ord->id,  $text, 'shop', $ord->customer_id, $ord->id); //сохранение письма отправленного пользователю
	SendMail::getInstance()->sendEmail($ord->email, $ord->middle_name.' '.$ord->name, 'Повернення на картку за замовлення №'.$ord->id, $text, '', '', 'return@red.ua', 'RED.UA', 2, 'return@red.ua', $this->user->getMiddleName());
	
	$message.='<p>Повернення на картку '.($vozrat->amount+$vozrat->dop_suma).'грн. за замовлення №'.$ord->id.'</p><br/>';
        }
	}else{
	$message.='<p>Нельза отправить сообщение по заказу №'.$ord->id.' он в статусе "Обработан", отправлять можно только когда заказ в статусе "В процессе"</p><br/>';
	}
	}
	$message .= '</div>';
	
	 die(json_encode(array('send'=>'ok', 'message'=>$message, 'ids'=>$ids)));
	
        }elseif($this->post->method == 'edit_sposob'){
            
            $vozrat = new ShopordersVozrat((int)$this->post->id);
            $vozrat->setSposob((int)$this->post->sposob);
            $vozrat->save();
            die($this->post->id);
        }
        $this->view->order_status = array(1 => 'Принят', 2 => 'В процессе', 3 => 'Обработан', 4=>'Возврат', 5=> 'Отменён');
		
        if($this->get->id) {
		$vozrat = new ShopordersVozrat((int)$this->get->id);
		
	if($vozrat->getId()){
            $order = new Shoporders($vozrat->getOrderId());
            if ($order->getId()){
                if (count($_POST)) {
				 $m_a = array();
				 if (isset($_POST['order_status'])) {
				 $error = [];
				
				 foreach ($_POST as $k =>$v) {
				 if($v == 'on'){
                            $m = explode('_', $k);
                                $m_a[$m[1]] = $m[0];
						}
                        }
                        if ($_POST['order_status'] != $vozrat->getStatus()) {
						
			if ($_POST['order_status'] == 2 and $_POST['sposob'] == 1 and count($m_a)){ //v procese deposit	
						foreach($m_a as $k => $art){
	$article_order = wsActiveRecord::useStatic('Shoporderarticles')->findFirst(array("order_id"=>(int)$vozrat->order_id, "article_id" =>(int)$art, "artikul LIKE '".trim($k)."' "));
            if($article_order){					
                    $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array("id_article" => $article_order->article_id, "id_color" =>$article_order->color, "id_size" => $article_order->size));
									if($article){
									if($article_order->getCount() > 1){
									for($i=1; $i<=$article_order->getCount(); $i++){
									
									OrderHistory::newHistory($this->user->id, $article_order->getOrderId(), 'Возврат товара', OrderHistory::getNewOrderArticle($article_order->getId()), $article_order->getArticleId());
									$artic = new ShoporderarticlesVozrat();
									$artic->setStatus(0);
									$artic->setOrderId($article_order->getOrderId());
									$artic->setArticleId($article_order->getArticleId());
									$artic->setCod($article_order->getArtikul());
									$artic->setTitle($article_order->getTitle());
									$artic->setCount(1);
									$artic->setPrice($article_order->getPrice());
									$artic->setCtime(date('Y-m-d H:i:s'));
									$artic->setUtime(date('0000-00-00 00:00:00'));
									$artic->setUser($this->user->getId());
									$artic->setDelivery($order->getDeliveryTypeId());
									$artic->setSize($article_order->getSize());
									$artic->setColor($article_order->getColor());
									$artic->setOldPrice($article_order->getOldPrice());
									if($order->getDeposit() > 0){
									$artic->setDeposit(1);
									}
									$artic->save();
									}
									}else{
									OrderHistory::newHistory($this->user->id, $article_order->getOrderId(), 'Возврат товара', OrderHistory::getNewOrderArticle($article_order->getId()), $article_order->getArticleId());
									$artic = new ShoporderarticlesVozrat();
									$artic->setStatus(0);
									$artic->setOrderId($article_order->getOrderId());
									$artic->setArticleId($article_order->getArticleId());
									$artic->setCod($article_order->getArtikul());
									$artic->setTitle($article_order->getTitle());
									$artic->setCount($article_order->getCount());
									$artic->setPrice($article_order->getPrice());
									$artic->setCtime(date('Y-m-d H:i:s'));
									$artic->setUtime(date('0000-00-00 00:00:00'));
									$artic->setUser($this->user->getId());
									$artic->setDelivery($order->getDeliveryTypeId());
									$artic->setSize($article_order->getSize());
									$artic->setColor($article_order->getColor());
									$artic->setOldPrice($article_order->getOldPrice());
									if($order->getDeposit() > 0){
									$artic->setDeposit(1);
									}
									$artic->save();
									}
									$article_order->setCount(0);
									$article_order->save();
									}else{
									die('Ошибка чтения товара в остатке!');
									$error[] = 'Ошибка чтения товара в остатке!';
									}
									}else{
									die('Ошибка чтения товара в заказе!');
									$error[] = 'Ошибка чтения товара в заказе!';
									
									}
									
									}
									
									if(!count($error)){
									$customer = new Customer($order->getCustomerId());
									if ($customer->getId()) {
									if($order->getSkuCount() == 0) {$order->setStatus(7);}
									
									$sum_return_all = $_POST['sum_voz_all'];
									$dop_sum = $_POST['dop_suma']?$_POST['dop_suma']:0;
									$sum_return = ($sum_return_all - $dop_sum);
									
									
									$c_dep = $customer->getDeposit();
									
									$or_dep = $order->getDeposit();
									if($or_dep > 0){
									if($or_dep > $sum_return_all){
									$order->setDeposit($or_dep-$sum_return_all);
									$customer->setDeposit($c_dep+$sum_return_all);
									$customer->save();
									}else{
									$order->setDeposit(0);
									$customer->setDeposit($c_dep+$sum_return_all);
									$customer->save();
									}
									}else{
									$customer->setDeposit($c_dep+$sum_return_all);
									$customer->save();
									}
									
									$order->calculateOrderPrice(true, false);
									$order->save();
						$remark = new Shoporderremarks();
                        $data = [
                            'order_id' => $order->getId(),
                            'date_create' => date("Y-m-d H:i:s"),
                            'remark' => 'Зачислен депозит '.$sum_return_all.' грн.',
                            'name' => $this->user->getMiddleName()
                        ];
                        $remark->import($data);
                        $remark->save();
									
// история изменения депозита
OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Клиенту зачислен депозит ('.$sum_return_all.') грн. ','C "' . $c_dep . '" на "' .$customer->getDeposit(). '"');
// история зачисления депозита
DepositHistory::newDepositHistory($this->user->getId(), $customer->getId(), '+', $sum_return_all, $order->getId());

									
									$vozrat->setStatus(3);
									$vozrat->setAmount($sum_return);
									$vozrat->setDopSuma($dop_sum);
									$vozrat->setSposob(1);
									$vozrat->setDateVProcese(date('Y-m-d H:i:s'));
									$vozrat->setAdminVProcese($this->user->id);
									$vozrat->setDateObrabotan(date('Y-m-d H:i:s'));
									$vozrat->setAdminObrabotan($this->user->id);
									
									if(isset($_POST['comments']) and $_POST['comments'] !=''){$vozrat->setComments($_POST['comments']);}
									if(isset($_POST['nakladna']) and $_POST['nakladna'] !=''){$vozrat->setNakladna($_POST['nakladna']);}
									
									$vozrat->save();

									}
									 $this->_redir('vozrat');
									
									}else{
									$this->view->error = $error;
									 $this->_redirect('/admin/vozrat/id/' . $vozrat->getId());
									}
                        }elseif($_POST['order_status'] == 2 and ($_POST['sposob'] == 2 or $_POST['sposob'] == 3 or $_POST['sposob'] == 4)){ // v procese perevod
						//die('poch');
						foreach($m_a as $k => $art){
						
	$article_order = wsActiveRecord::useStatic('Shoporderarticles')->findFirst(array("order_id"=>$vozrat->order_id, "article_id" => $art, "artikul LIKE '".trim($k)."' ", " count > 0"));
								
								if($article_order){
									
		//wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array("id_article" => $article_order->article_id, "code LIKE '".$article_order->artikul."' "));
               $article =  wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array("id_article" => $article_order->article_id, "id_color" =>$article_order->color, "id_size" => $article_order->size));
									if($article){
									if($article_order->getCount() > 1){
									for($i=1; $i<=$article_order->getCount(); $i++){
									OrderHistory::newHistory($this->user->id, $article_order->getOrderId(), 'Возврат товара', OrderHistory::getNewOrderArticle($article_order->getId()), $article_order->getArticleId());
									$artic = new ShoporderarticlesVozrat();
									$artic->setStatus(0);
									$artic->setOrderId($article_order->getOrderId());
									$artic->setArticleId($article_order->getArticleId());
									$artic->setCod($article_order->getArtikul());
									$artic->setTitle($article_order->getTitle());
									$artic->setCount(1);
									$artic->setPrice($article_order->getPrice());
									$artic->setCtime(date('Y-m-d H:i:s'));
									$artic->setUtime(date('0000-00-00 00:00:00'));
									$artic->setUser($this->user->getId());
									$artic->setDelivery($order->getDeliveryTypeId());
									$artic->setSize($article_order->getSize());
									$artic->setColor($article_order->getColor());
									$artic->setOldPrice($article_order->getOldPrice());
									if($order->getDeposit() > 0){
									$artic->setDeposit(1);
									}
									$artic->save();
									}
									}else{
								OrderHistory::newHistory($this->user->id, $article_order->getOrderId(), 'Возврат товара', OrderHistory::getNewOrderArticle($article_order->getId()), $article_order->getArticleId());
									$artic = new ShoporderarticlesVozrat();
									$artic->setStatus(0);
									$artic->setOrderId($article_order->getOrderId());
									$artic->setArticleId($article_order->getArticleId());
									$artic->setCod($article_order->getArtikul());
									$artic->setTitle($article_order->getTitle());
									$artic->setCount($article_order->getCount());
									$artic->setPrice($article_order->getPrice());
									$artic->setCtime(date('Y-m-d H:i:s'));
									$artic->setUtime(date('0000-00-00 00:00:00'));
									$artic->setUser($this->user->getId());
									$artic->setDelivery($order->getDeliveryTypeId());
									$artic->setSize($article_order->getSize());
									$artic->setColor($article_order->getColor());
									$artic->setOldPrice($article_order->getOldPrice());
									if($order->getDeposit() > 0){
									$artic->setDeposit(1);
									}
									$artic->save();
									}
									$article_order->setCount(0);
									$article_order->save();
									}else{
									$error[] = 'Ошибка чтения товара в остатке!';
									}
									}else{
									$error[] = 'Ошибка чтения товара в заказе!';
									
									}
									
									}
	if(!count($error)){
		if($order->getSkuCount() == 0){ $order->setStatus(7); }
				
                if($_POST['sposob'] == 4){
                   $dop_file = $_POST['dop'];
                    
                   if (!is_string($dop_file)) {
                        $dop_file = serialize($dop_file);
                    }
        $vozrat->setDopFile($dop_file);
                }
                
		$sum_return_all = $_POST['sum_voz_all'];
		$dop_sum = $_POST['dop_suma']?$_POST['dop_suma']:0;
		$sum_return = ($sum_return_all - $dop_sum);
							
		$order->calculateOrderPrice(true, false);
						
		$vozrat->setStatus(2);
		$vozrat->setAmount($sum_return);
		$vozrat->setDopSuma($dop_sum);
		$vozrat->setSposob((int)$_POST['sposob']);
		$vozrat->setDateVProcese(date('Y-m-d H:i:s'));
		$vozrat->setAdminVProcese($this->user->id);
									//$vozrat->setDateObrabotan(date('Y-m-d H:i:s'));
									//$vozrat->setAdminObrabotan($this->user->id);
									
		if(isset($_POST['comments']) and $_POST['comments'] !=''){$vozrat->setComments($_POST['comments']);}
		if(isset($_POST['nakladna']) and $_POST['nakladna'] !=''){$vozrat->setNakladna($_POST['nakladna']);}
									
		$vozrat->save();

								
	}else{
		$this->view->error = $error;
		$this->_redirect('/admin/vozrat/id/' . $vozrat->getId());
            }
	$this->_redir('vozrat');
									

						}elseif($_POST['order_status'] == 5){
						$vozrat->setStatus(5);
									$vozrat->setDateObrabotan(date('Y-m-d H:i:s'));
									$vozrat->setAdminObrabotan($this->user->id);
									
									if(isset($_POST['comments']) and $_POST['comments'] !='')$vozrat->setComments($_POST['comments']);
									
									$vozrat->save();
									
						 $this->_redir('vozrat');
						}elseif($_POST['order_status'] == 3 and ($vozrat->getSposob() == 2 or $vozrat->getSposob() == 3 or $vozrat->getSposob() == 4)){
						$vozrat->setStatus(3);
									$vozrat->setDateObrabotan(date('Y-m-d H:i:s'));
									$vozrat->setAdminObrabotan($this->user->id);
									
									if(isset($_POST['comments']) and $_POST['comments'] !='')$vozrat->setComments($_POST['comments']);
									
									$vozrat->save();
						
						
						}elseif($_POST['order_status'] == 4){
						$vozrat->setStatus(4);
						$vozrat->setDateObrabotan(date('Y-m-d H:i:s'));
						$vozrat->setAdminObrabotan($this->user->id);
									
						if(isset($_POST['comments']) and $_POST['comments'] !='')$vozrat->setComments($_POST['comments']);
									
						$vozrat->save();
						
		OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Смена статуса', OrderHistory::getStatusText($order->getStatus(), 7));
						
						foreach($order->articles as $art){
						
						$article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array("id_article" => $art->getArticleId(), "id_size" => $art->getSize(), "id_color" => $art->getColor()));
						if($article){
									OrderHistory::newHistory($this->user->id, $art->getOrderId(), 'Отмена заказа', '', $art->getArticleId());
					 $pos = strpos($article->code, 'SR');
                                        if($pos === false){				
                                    $article->setCount($article->getCount() + $art->getCount());
                                    $article->save();
                                    $artic = new Shoparticles($art->getArticleId());
                                    $artic->setStock($artic->getStock() + $art->getCount());
                                    $artic->save();
                                                }
                                    $art->setCount(0);
                                    $art->save();
									}
									}
				$order->setStatus(7);	
				$deposit = $order->getDeposit();
				if($deposit > 0){
                            $order->setDeposit(0);
                            $order->save();
                            $customer = new Customer($order->getCustomerId());
				$c_dep = $customer->getDeposit();
				$new_d = (float)$customer->getDeposit() + (float)$deposit;
                            $customer->setDeposit($new_d);
                            $customer->save();
							
	OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Клиенту зачислен депозит ('.$deposit.') грн. ','C "' . $c_dep . '" на "' . $new_d . '"');
				
				$ok = '+';
				DepositHistory::newDepositHistory($this->user->getId(), $customer->getId(), $ok, $deposit, $order->getId());
				}
							
						 $order->save();

						}else{
						die($_POST['order_status']);
						}
						
						}
						 $this->_redir('vozrat');
                    }else{
                        die();
                    }
					
               
                }
		$this->view->vozrat = $vozrat;
                $this->view->order = $order;
                echo $this->render('return_articles/vozvrat.tpl.php', 'index.php');
                exit();
            } else {
                $this->_redir('vozrat');
            }
		}else{
			$this->_redir('vozrat');
	}
        }elseif($_GET){
            //l($this->get);
		$order_by = array('date_create' => 'DESC');
		$data = [];
		if($this->get->status){ $data['status'] = (int)$this->get->status; 
		if($this->get->status == 3) { $order_by = ['date_obrabotan' => 'DESC'];}
                
                }
		if($this->get->customer_id) {$data['customer_id'] = (int)$this->get->customer_id;}
		if($this->get->order) { $data['order_id'] = (int)$this->get->order;}
                if($this->get->date_obrabotan) { 
                    $data[] = " date_obrabotan >='".date('Y-m-d 00:00:00', strtotime($this->get->date_obrabotan))."' ";
                     $data[] = " date_obrabotan <='".date('Y-m-d 23:59:59', strtotime($this->get->date_obrabotan))."' ";
                }
		if($this->get->create_from) {$data[] = " date_create >='".date('Y-m-d 00:00:00', strtotime($this->get->create_from))."' ";}
		if($this->get->create_to) {$data[] = " date_create <= '".date('Y-m-d 23:59:59', strtotime($this->get->create_to))."' ";}
		if($this->get->sposob){ $data['sposob'] = implode(",", $this->get->sposob); }
		$orders = wsActiveRecord::useStatic('ShopordersVozrat')->findAll($data, $order_by, [0, 100]);
		if($orders){
		$this->view->orders = $orders;
		}
		}
                
		echo $this->render('return_articles/vozvrat_list.tpl.php', 'index.php');
    }

    public function labelsAction()
    {
      if ($this->get->delete) {
            $leb = new Shoparticleslabel((int)$this->get->delete);

            if ($leb->getId() and !$leb->articles->count()) {
			@unlink($leb->getImage());
                $leb->destroy();
            }
            $this->_redir('labels');
        }

        $this->view->labels = wsActiveRecord::useStatic('Shoparticleslabel')->findAll();

        echo $this->render('label/list.tpl.php');

    }

    public function labelAction()
    {

        $sub = new Shoparticleslabel($this->get->getId());

        if (count($_POST)) {
            foreach ($_POST as &$value)
                $value = stripslashes($value);
            $errors = array();

            if (!@$_POST['name'])
                $errors[] = $this->trans->get('Please fill name');
            $sub->setTop(0);
            $sub->setLeft(0);
            $sub->import($_POST);
            if (!count($errors)) {
                if (@$_FILES['image']) {
                    require_once('upload/class.upload.php');
                    $handle = new upload($_FILES['image'], 'ru_RU');
                    $folder = '/storage/label/';
                    if ($handle->uploaded) {
                        if (!count($errors)) {
                            $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                            if ($handle->processed) {
                                if ($sub->getImage())
                                    @unlink($sub->getImage());
                                $sub->setImage($folder . $handle->file_dst_name);
                                $handle->clean();
                            }
                        }
                    }
                }

            }
            if (!count($errors)) {
                $sub->save();

                $this->view->saved = 1;
            } else {
                $this->view->errors = $errors;
            }

            $this->view->errors = $errors;
        }
        $this->view->sub = $sub;
        echo $this->render('label/edit.tpl.php');


    }

    public function zgodaAction()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(2800);
        $q = "SELECT DISTINCT(customer_id) as customer_id FROM ws_orders WHERE soglas = 1";
        $orders = wsActiveRecord::useStatic('Shoporders')->findByQuery($q);
        foreach ($orders as $ord) {
            if ($ord->getCustomerId()) {
                $file = $_SERVER['DOCUMENT_ROOT'] . '/soglas/' . $ord->getCustomerId() . '.html';
                if (!is_file($file)) {
                    $user = new Customer($ord->getCustomerId());
                    $this->view->user = $user;
                    $text = $this->view->render('user/zgoda.tpl.php');
                    file_put_contents($file, $text);
                }
            }
        }
        $archive_dir = $_SERVER['DOCUMENT_ROOT'] . '/tmp/';
        $src_dir = $_SERVER['DOCUMENT_ROOT'] . '/soglas/';
        $fileName = $archive_dir . "users_zgoda.zip";

        @unlink($fileName);

        $zip = new ZipArchive();
        if ($zip->open($fileName, ZIPARCHIVE::CREATE) !== true) {
            die("Error while creating archive file");
            exit(1);
        }
        $dirHandle = opendir($src_dir);
        while (false !== ($file = readdir($dirHandle))) {
            $zip->addFile($src_dir . $file, $file);
        }
        $zip->close();
        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=\"" . basename($fileName) . "\";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($fileName));
        readfile("$fileName");
    }

    public function normaPAction()
    {

	if ($this->post->metod == 'slider'){
		$norma = array();
	
	$a = $this->post->x;
	$b = $this->post->y;
	$d1 =  new DateTime();
	$d1->modify("- ".$a."day");
	$d1 = $d1->format('Y-m-d');
	
	$d2 =  new DateTime();
	$d2->modify("- ".$b."day");
	$d2 = $d2->format('Y-m-d');
	
	
	$q = "SELECT *  FROM ws_articles
                       WHERE ws_articles.stock not like '0'
					   AND ws_articles.active = 'y'
					   and ws_articles.status = 3
					   AND ws_articles.data_new <= '".$d1."' AND ws_articles.data_new >= '".$d2."' ";
					
        $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($q);

        
        foreach ($articles as $art) {
           $q = 'SELECT SUM(ws_articles_sizes.count) as counti FROM ws_articles_sizes WHERE ws_articles_sizes.id_article =' . $art->getId();
           $count = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($q)->at(0)->getCounti();
           $q = 'SELECT SUM(ws_order_articles.count) as counti FROM ws_order_articles WHERE ws_order_articles.article_id = ' . $art->getId();
           $count2  = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q)->at(0)->getCounti();
            $proc = $count2 / (($count + $count2) / 100);
            $day = ceil((time() - strtotime($art->getDataNew())) / (24 * 60 * 60));
            $norma[$day][$art->getId()]['proc'] = $proc;
			$norma[$day][$art->getId()]['title'] = $art->getTitle();
			$norma[$day][$art->getId()]['diskont'] = $art->getDiscount();
			$norma[$day][$art->getId()]['patch'] = $art->getPath();
            $norma[$day][$art->getId()]['all'] = $count + $count2;
            $norma[$day][$art->getId()]['by'] = $count2;
            $norma[$day][$art->getId()]['day'] = $day;
        }
//$norma = ksort($norma);

	$this->view->norma = $norma;

		die(json_encode(array('result' => $this->view->render('page/normap_s.tpl.php'))));
		 }
        echo $this->render('shop/normap.tpl.php');
    }

    public function viewOrderAction() {
	if($this->post->query == 'ist'){
	$max = 0;
	$mas = array();
	if(@(int)$this->post->id){
	$id = (int)$this->post->id;
	$articles = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT id, title from ws_order_articles where  id > ".$id." ORDER BY `id` DESC LIMIT 0, 10 ");
		$max = $articles[0]->id;
		foreach ($articles as $article){ $mas[] = $article->title; }
	}else{
	$res = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT max(id) as maxs from ws_order_articles");
	 //echo print_r(array('id'=>$res[0]->maxs));
	$max = $res[0]->maxs;
	//echo print_r(array('id'=>$max, 'mas'=>$mas));
	}
	if(!@$max){ $max = (int)$this->post->id; }
$result = array('id'=>$max, 'mas'=>$mas);
		die(json_encode($result));
	}else if($this->get->metod == 'view'){
	 if (isset($_GET['articul']) and strlen(@$_GET['articul']) > 9) {
$articles = wsActiveRecord::useStatic('Shoparticles')->getArticlesByArticul($_GET['articul']);
if($articles) $this->_redirect('/product/id/'.$articles['0']->getId().'/');

        }else{
		 $this->_redirect('/admin/');
		}
	
	
	}else{
        $order_status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
        $this->view->order_status = $order_status;
        $data = array();
        if (isset($_GET['order']) and (int)$_GET['order'] > 0) {
            $iddd = (int)$_GET['order'];
            $data[] = '(id = ' . $iddd . ' or comlpect LIKE "%' . $iddd . '%")';
        }
        $order = wsActiveRecord::useStatic('Shoporders')->findFirst($data);
        if ($order) {
            if ($this->get->metod == 'edit') {
                $this->_redirect('/admin/shop-orders/edit/id/' . $order->getId() . '/');
            }
            $this->view->order = $order;
            $this->view->total_amount = wsActiveRecord::useStatic('Shoporders')->findByQuery('
                              SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS sum_amount
              			        FROM ws_order_articles
              			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
                              WHERE ws_orders.status in(3,4,6,8)
                              AND ws_orders.customer_id=' . $order->getCustomerId())->at(0)->getSumAmount();

            echo $this->render('shop/order-view.tpl.php');
            return;

        } else {
            $this->_redirect('/admin/');
        }
		}
    }
    public function addArdicleHistory()
    {
        $type = 'm';
        if ($type == 'm') {
            $q = 'SELECT DATE_FORMAT(ctime,"%Y-%m ") as dt, count(id) as ct FROM ws_articles
            GROUP BY dt
            ORDER BY dt ASC';
        }elseif ($type == 'd') {
            $q = 'SELECT DATE_FORMAT(ctime,"%Y-%m-%d ") as dt, count(id) as ct FROM ws_articles
                       GROUP BY dt
                       ORDER BY dt ASC';
        }elseif ($type == 'y') {
            $q = 'SELECT DATE_FORMAT(ctime,"%Y ") as dt, count(id) as ct FROM ws_articles
                       GROUP BY dt
                       ORDER BY dt ASC';
        }

        $satt = wsActiveRecord::useStatic('Shoparticles')->findByQuery($q);
        $this->stat = $satt;
        $type->type = $type;
        echo $this->render('shop/stat.tpl.php');
    }

    public function skidkiAction()
    {
	
	 if (isset($_POST['getarticles'])) {
                if (isset($_POST['id'])) {
                    $data = array();
                    $articles = wsActiveRecord::useStatic('Shoparticles')->findAll(array('category_id' => $_POST['id'], 'active' => 'y', 'stock > 0'));
                    if ($articles->count())
                        foreach ($articles as $article)
                            $data[] = array(
                                'id' => $article->getId(),
                                'title' => $article->getTitle() . " (" . Shoparticles::showPrice($article->getRealPrice()) . " грн)",
                                'img' => $article->getImagePath('listing')
                            );
                    $res = array(
                        'result' => 'done',
                        'type' => 'articles',
                        'data' => $data
                    );
                } else {
                    $res = array('result' => 'false');
                }
                echo json_encode($res);
                exit;
            }
			
			
	if($this->post->method == 'add_category'){
	$sub = new Skidki();
	$sub->setStart(date('Y-m-d ', strtotime($this->post->start)));
    $sub->setFinish(date('Y-m-d ', strtotime($this->post->finish)));
	$sub->setName($this->user->getFirstName());
	$sub->setCustomerId($this->user->getId());
	$sub->setArticleId(0);
	$sub->setCategoryId($this->post->id);
	$sub->setValue((int)$this->post->procent);
	if($this->post->publish == 1) $sub->setPublish(1); else $sub->setPublish(0);
	$sub->save();
	$mas = $this->post->id;

	echo json_encode($mas);
	die();
	}elseif($this->post->method == 'add_articles'){
	$mas =  explode(',', $this->post->id);
	$i = 0;
	foreach ($mas as $value){
	$sub = new Skidki();
	$sub->setStart('2017-04-28 13:00:00');
        $sub->setFinish('2017-04-28 17:00:00');
	$sub->setName('Yaroslav');
	$sub->setCustomerId($this->user->getId());
	$sub->setArticleId($value);
	$sub->setValue(10);
	$sub->setPublish(0);
	$sub->save();
	$i++;
	}
	echo json_encode($i);
	die();
	
	}elseif($this->get->add and $this->get->add == 'ar' ){
	 $categories = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active in(1,2)'));
        $this->view->categories = $categories;
	echo $this->render('skidki/add.tpl.php');
	}elseif($this->get->add and $this->get->add == 'cat' ){
	 $categories = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active in (1,2)'));
        $this->view->categories = $categories;
	echo $this->render('skidki/add_cat.tpl.php');
	}else{
        $this->view->labels = wsActiveRecord::useStatic('Skidki')->findAll([], ['start' => 'DESC']);
        echo $this->render('skidki/list.tpl.php');
	}


    }

    public function skidkaAction()
    {
	
	if(@$this->get->id){
        $sub = new Skidki($this->get->id);
        if ($sub->getId()) {
            $article = $sub->article;
        } else {
            $article = new Shoparticles((int)$this->get->article ? (int)$this->get->article : (int)$this->post->article_id);
        }
        if (!$article->getId()) {
            $this->_redirect('/admin/');
        }
        if (count($_POST)) {
            foreach ($_POST as &$value)
                $value = stripslashes($value);
            $errors = array();

            if (!@$_POST['name'])
                $errors[] = $this->trans->get('Please fill name');
            if (!@$_POST['value'])
                $errors[] = $this->trans->get('Please fill value');

            $sub->setPublish(0);
            $sub->import($_POST);
            $sub->setStart(date('Y-m-d ', strtotime($_POST['start'])) . $_POST['start_time']);
            $sub->setFinish(date('Y-m-d ', strtotime($_POST['finish'])) . $_POST['finish_time']);
            $sub->setCustomerId($this->user->getId());

            if (!count($errors)) {
                $sub->save();

                $this->view->saved = 1;
            } else {
                $this->view->errors = $errors;
            }

            $this->view->errors = $errors;
        }
        $this->view->sub = $sub;
        $this->view->article = $article;
        echo $this->render('skidki/edit.tpl.php');
		}else if($this->get->cat){
		 $sub = new Skidki($this->get->cat);
		 $sub->setPublish(0);
            $sub->import($_POST);
           $sub->setStart(date('Y-m-d ', strtotime($this->post->start)));
    $sub->setFinish(date('Y-m-d ', strtotime($this->post->finish)));
            $sub->setCustomerId($this->user->getId());
			$sub->save();
			 $this->view->saved = 1;
		 $this->view->sub = $sub;
		echo $this->render('skidki/edit_cat.tpl.php');
		}
		


    }

    public function orderinfoAction()
    {
        $errors = array();
        $order = new Shoporders((int)$this->get->id);
        if (!$order->getId()) {
            $this->_redirect('/admin/');
        }
        if (count($_POST)) {
            if(isset($_POST['open_pay'])){
                            $_POST['open_pay'] = 1;
                $remark = new Shoporderremarks();
                        $data = array(
                            'order_id' =>  $order->id,
                            'date_create' => date("Y-m-d H:i:s"),
                            'remark' => 'Открыта онлайн оплата',
			'name' => $this->user->getMiddleName()
                        );
                        $remark->import($data);
                        $remark->save();        
                        }else{
                            $_POST['open_pay'] = 0;
                        
                        }
            foreach ($_POST as &$value){ $value = stripslashes($value); }
           // print_r($_POST);
            //die();
                        
                        
				//$ms=array();
				//$ms = $order;	
				$n = $order->getName();
				$f = $order->getMiddleName();
				$c = $order->getCity();
				$index = $order->getIndex();
				$s = $order->getStreet();
				$house = $order->getHouse();
				$flat = $order->getFlat();
				$sk = $order->getSklad();
				$t = $order->getTelephone();
				$em = $order->getEmail();
				$d = $order->getDeliveryDate();
				
            $order->import($_POST);
            
			$text = '';
			
			if($n != $order->getName()){$text .=' Имя с "'.$n.'" на "'.$order->getName().'"';}
			if($f != $order->getMiddleName()){$text .= ' Фамилия с '.$f.'" на "'.$order->getMiddleName().'"';}
			if($c != $order->getCity()){$text .=' Гогод с "'.$c.'" на "'.$order->getCity().'"';}
			if($index != $order->getIndex()){$text .=' Индекс с "'.$index.'" на "'.$order->getIndex().'"';}
			if($s != $order->getStreet()){$text .= ' Улица с "'.$s.'" на "'.$order->getStreet().'"';}
			if($house != $order->getHouse()){$text .= ' Дом с "'.$house.'" на "'.$order->getHouse().'"';}
			if($flat != $order->getFlat()){$text .= ' Квартира с "'.$flat.'" на "'.$order->getFlat().'"';}
			if($sk != $order->getSklad()){$text .= ' Склад с "'.$sk.'" на "'.$order->getSklad().'"';}
			if($t != $order->getTelephone()){$text .= ' Телефон с '.$t.'" на "'.$order->getTelephone().'"';}
			if($em != $order->getEmail()){$text .= ' Email с "'.$em.'" на "'.$order->getEmail().'"';}
			if($d != $order->getDeliveryDate()){$text .= ' Дата доставки с "'.$d.'" на "'.$order->getDeliveryDate().'"';}
			
			
			if($order->getDeliveryTypeId() == 9 and false){
			if($c != $order->getCity() or $s != $order->getStreet()){
			$m = wsActiveRecord::useStatic('Shopordersmeestexpres')->findFirst(array('order_id'=>$order->getId()));
			if($m){
			$m->setUuidCity('');
			$m->setUuidStreet('');
			$m->save();
			}
			}
			}
			
            if (!$order->getEmail()) {
                $errors[] = 'Email';
            }
            if (!$order->getName()) {
                $errors[] = 'Имя';
            }
            if (!$order->getMiddleName()) {
                $errors[] = 'Фамилия';
            }
            if (!$order->getTelephone()) {
                $errors[] = 'Телефон';
            }
            //$curdate = Registry::get('curdate');
            $mas_adres = array();
            if (isset($order['index']) and strlen($order['index']) > 0) {
                $mas_adres[] = $order['index'];
            }
            if (isset($order['obl']) and strlen($order['obl']) > 0) {
                $mas_adres[] = $order['obl'];
            }
            if (isset($order['rayon']) and strlen($order['rayon']) > 0) {
                $mas_adres[] = $order['rayon'];
            }
            if (isset($order['city']) and strlen($order['city']) > 0) {
                $mas_adres[] = 'г. ' . $order['city'];
            }
            if (isset($order['street']) and strlen($order['street']) > 0) {
                $mas_adres[] = 'ул. ' . $order['street'];
            }
            if (isset($order['house']) and strlen($order['house']) > 0) {
                $mas_adres[] = 'д. ' . $order['house'];
            }
            if (isset($order['flat']) and strlen($order['flat']) > 0) {
                $mas_adres[] = 'кв. ' . $order['flat'];
            }
            if (isset($order['sklad']) and strlen($order['sklad']) > 0) {
                $mas_adres[] = 'НП: ' . $order['sklad'];
            }
            $order['address'] = implode(', ', $mas_adres);

            if (!count($errors)) {
                $order->setAdress($order['address']);
              if(strlen($text) > 0) OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Редактирование информации', $text);
                $order->save();
                $this->view->saved = 1;
            }

        }
        if (isset($_POST['ajax'])) {
            if (count($errors)) {
                echo '
					<img src="/img/icons/error.png" alt="" class="page-img"/>
					<h1>Ошибка, эти поля обязательные:</h1>
					<div>
						' . implode(", ", $errors) . '
					</div>';
            }else{
              echo 'success';
                die();
            }
         
        }
        $this->view->errors = $errors;
        $this->view->order = $order;
        echo $this->render('shop/order-info-edit.tpl.php');
    }


    public function deliveryTypeAction()
    {
        if($this->post->method == 'load_form_payment'){ 
            //$delivery = new DeliveryType((int)$this->get->id);
           $this->view->payment = $payments = wsActiveRecord::useStatic('DeliveryPayment')->findAll(['delivery_id' => (int)$this->post->id]);
           // $this->view->payment = $payments = wsActiveRecord::findByQueryArray("SELECT *FROM `ws_delivery_payments`WHERE `delivery_id` =3");
            $form = $this->view->render('delivery/delivery-type-edit-form.php');
          
            die(json_encode($form));
        }elseif($this->post->method == 'save_form_payment'){
           // l($this->post->data);
            die(json_encode($this->post->data));
        }
        if ('edit' == $this->cur_menu->getParameter()) {
           // l($this->post);
            $delivery = new DeliveryType((int)$this->get->id);
            if (count($_POST)) {
                foreach ($_POST as &$value)
                    $value = stripslashes($value);
                $error = array();
                if (!$_POST['name']) {
                    $error[] = 'Введите название';
                }
                if (!isset($_POST['prices'])) {
                    $error[] = 'Укажите стоимость';
                }
                if (count($error)) {
                    $this->view->errors = $error;
                } else {
                    //$delivery->setName($_POST['name']);
                    //$delivery->setPrices($_POST['price']);
                    $delivery->setActive(@$_POST['active'] ? 1 : 0);
                    $delivery->import($this->post);
                    $delivery->save();
                    $this->view->saved = 1;
					foreach ($_POST as $key => $value) {
						if (is_int($key)) {
							$pay = new DeliveryPayment((int)$key);
							$pay->setPrice((int)$value);
							$pay->save();
						}
					}
                }
            }
            $payments = wsActiveRecord::useStatic('DeliveryPayment')->findAll(array('delivery_id' => (int)$this->get->id));
            $this->view->delivery = $delivery;
            $this->view->payments = $payments;
            echo $this->render('delivery/delivery-type-edit.tpl.php', 'index.php');
        } else {
            $this->view->deliveries = wsActiveRecord::useStatic('DeliveryType')->findAll([], ['active' => 'DESC']); 
            echo $this->render('delivery/delivery-type.tpl.php', 'index.php');
        }
    }
    
    public function deliverypaymentAction(){
        if(isset($this->get->edit) && isset($_POST['d']) && !empty($_POST['d'])){
            $post = (object)$this->post->d;
            if(isset($post->active)){$post->active = 1;}else{$post->active = 0;}
           // l($post);
            $dely = new DeliveryPayment();
            $dely->import($post);
            $dely->save();
            $this->view->saved = 'Запись успешно сохранена!';
            unset($_POST);
            $this->_redirect('/admin/deliverypayment/');
            
        }
        $this->view->fop = wsActiveRecord::useStatic('Fop')->findAll();
        $this->view->payments = wsActiveRecord::useStatic('PaymentMethod')->findAll();
        $this->view->deliveries = wsActiveRecord::useStatic('DeliveryPayment')->findAll([], ['delivery_id' => 'ASC']); 
        
            echo $this->render('delivery/delivery-payment.php', 'index.php');
    }

    public function orderStatusesAction()
    {
        if ('edit' == $this->cur_menu->getParameter()) {
            $o = new OrderStatuses((int)$this->get->id);
            if (count($_POST)) {
                foreach ($_POST as &$value)
                    $value = stripslashes($value);
                $error = array();
                if (!$_POST['name']) {
                    $error[] = 'Введите название';
                }

                if (count($error)) {
                    $this->view->errors = $error;
                } else {
                    $o->setName($_POST['name']);
                    $o->setOrder($_POST['order']);
                    $o->setActive(@$_POST['active'] ? 1 : 0);
                    $o->setSendSms(@$_POST['send_sms'] ? 1 : 0);
                    $o->setSendEmail(@$_POST['send_email'] ? 1 : 0);
                    $o->import($this->post);
                    $o->save();
                    $this->view->saved = 1;

                }
            }
            $this->view->order_statuses = $o;
            echo $this->render('order_statuses/status-edit.tpl.php');
        } else {
            $this->view->order_statuses = wsActiveRecord::useStatic('OrderStatuses')->findAll();
            echo $this->render('order_statuses/statuses.tpl.php');
        }
    }

    public function deliveryTypeEditAction()
    {

        $this->_redir('delivery_type');
    }

    public function fiveminAction()
    {

        $fivem = new CustomerFive();
        //print_r();
        $fivemi = $fivem->findFirst(array('custopmer_id' => $this->website->getCustomer()->getId()));
        if ($fivemi and date('Y-m-d') == $fivemi->getDate()) {
            if (count($_POST)) {
                $fivemi->{'setCheck' . $_POST['n']}($_POST['time']);
                $fivemi->save();
            }
        } elseif ($this->cur_menu->parameter != 'all') {
            $fivem->setCustopmerId($this->website->getCustomer()->getId());
            $fivem->setDate(date('Y-m-d'));
            $fivem->save();
        }


        if ($this->cur_menu->parameter == 'all')
            $this->view->fivem = $fivem->findAll(
                'date >="' . date('Y-m-d', mktime(0, 0, 0, date("n"), date("j") - 7, date("Y"))) . '"',
                array(
                    'date' => 'DESC',
                    'custopmer_id' => 'ASC'
                )
            );
        else
            $this->view->fivem = $fivem->findAll(
                array(
                    'custopmer_id' => $this->website->getCustomer()->getId(),
                    'date >="' . date('Y-m-d', mktime(0, 0, 0, date("n"), date("j") - 7, date("Y"))) . '"'
                ),
                array(
                    'date' => 'DESC'
                )
            );

        echo $this->render('slugebnoe/fivemin.tpl.php');
    }

    public function chatAction()
    {

       // setcookie("chatName", str_replace(' ', '_', $this->translit(mb_substr($this->website->getCustomer()->getFirstName() . '_' . $this->website->getCustomer()->getMiddleName(), 0, 18))), 0, '/backend/views/slugebnoe/chat');
        echo $this->render('slugebnoe/chat.tpl.php');
        return;
    }

    function num2str($num)
    {

        $ukr = array(
            array( //one_nine
                array('', 'один', 'два', 'три', 'чотири', 'п\'ять', 'шість', 'сімь', 'вісімь', 'дев\'ять'),
                array('', 'одна', 'дві', 'три', 'чотири', 'п\'ять', 'шість', 'сімь', 'вісімь', 'дев\'ять'),
            ),
            array( //teen
                'десять', 'одинадцять', 'дванадцять', 'тринадцать', 'чотирнадцять', 'п\'ятнадцять', 'шістнадцять', 'сімнадцять', 'вісімнадцять', 'дев\'ятнадцять'
            ),
            array( //tenth
                2 => 'двадцять', 'тридцять', 'сорок', 'п\'ятьдесят', 'шістьдесят', 'сімдесять', 'вісімьдесят', 'дев\'яносто'
            ),
            array( //hundred
                '', 'сто', 'двісти', 'триста', 'чотириста', 'п\'ятсот', 'шістсот', 'сімсот', 'вісімсот', 'дев\'ятсот'
            ),
            array( //scales
                array('триліон', 'триліона', 'триліонів', 0),
                array('мільйард', 'мільйарда', 'мільйардів', 0),
                array('мільйон', 'мільйона', 'мільйонів', 0),
                array('тисяча', 'тисячі', 'тисяч', 1),
                array('', '', '', 0)
            ),
            array('Вкажіть число (до 15 цифр)') //number_not_set
        );

        $num = is_numeric(trim($num)) ? (string)$num : 0;

        list($one_nine, $teen, $tenth, $hundred, $scales, $number_not_set) = $ukr;

        // массив будующего числа
        $out = array();

        // обробатываем числа не больше 15 знаков
        if (intval($num) > 0 and strlen(trim($num)) <= 15) {

            // формируем число с нулями перед ним и длиной 15 сиволов
            $num = sprintf("%015s", trim($num));

            // обробатываем по 3 символа
            foreach (str_split($num, 3) as $k => $v) {

                // пропускаем 000
                if (!intval($v)) continue;

                list($num1, $num2, $num3) = array_map('intval', str_split($v, 1));

                // диапазон 1-999
                $out[] = $hundred[$num1]; // диапазон 100-900
                if ($num2 > 1)
                    $out[] = $tenth[$num2] . ' ' . $one_nine[$scales[$k][3]] [$num3]; // диапазон 20-99
                elseif ($num2 > 0)
                    $out[] = $teen[$num3]; // диапазон 10-19
                else $out[] = $one_nine[$scales[$k][3]] [$num3]; // диапазон 1-9

                // тысячи, милионы ... и склонения
                $n = $v % 10;
                $n2 = $v % 100;
                if ($n2 > 10 && $n2 < 20) $out[] = $scales[$k][2];
                elseif ($n > 1 && $n < 5) $out[] = $scales[$k][1];
                elseif ($n == 1) $out[] = $scales[$k][0];
                else $out[] = $scales[$k][2];

            }
        } else $out[] = $number_not_set[0];

        return implode(' ', $out);
    }
	
	
    function translit($str)
    {
        $tr = array(
            "А" => "A", "Б" => "B", "В" => "V", "Г" => "G",
            "Д" => "D", "Е" => "E", "Ж" => "J", "З" => "Z", "И" => "I",
            "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
            "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
            "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH",
            "Ш" => "SH", "Щ" => "SCH", "Ъ" => "", "Ы" => "YI", "Ь" => "",
            "Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b",
            "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
            "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
            "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
            "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
            "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya"
        );
        return strtr($str, $tr);
    }

    public function blacklistAction()
    {
        $add = 0;
        $error = 0;
        $find = 0;
        if ($this->get->del) {
            $f = new Blacklist((int)$this->get->del);
            if ($f->getId()) {
                $f->destroy();
            }
            $this->_redirect(@$_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '/admin/blacklist/');
        }
        if (count($_POST)) {

            $em_list = array();
            $emails = explode("\n", $this->post->add);
            foreach ($emails as $em) {
                $tmp = explode(';', $em);
                if (count($tmp) == 1) {
                    $tmp = explode(',', $em);
                }
                foreach ($tmp as $t) {
                    $em_list[] = $t;
                }
            }

            foreach ($em_list as $email) {
                $email = trim($email);
                $er = 0;
                if (!isValidEmailNew($email)) {
                    $error++;
                    $er = 1;
                }

                if (!$er) {
                    $fnd = Blacklist::findByEmail($email);
                    if ($fnd) {
                        $find++;
                        $er = 1;
                    }
                }

                if (!$er) {
                    $new = new Blacklist();
                    $new->setEmail($email);
                    $new->setStatusId(1);
                    $new->save();
                    $add++;
                }
            }
        }
        $this->view->add = $add;
        $this->view->error = $error;
        $this->view->find = $find;
        $data = array();
        //$sorting = array('id' => 'DESC');
        $sortby = 'id';
        $direc = 'DESC';
        if ($_GET) {
            $dt = $this->get;
            if (strlen($dt->email) > 0) {
                $data[] = 'email LIKE "%' . $dt->email . '%" or temp_email LIKE "%' . $dt->email . '%"';
            }


            if (strlen($dt->sorting) > 0 && strlen($dt->direction) > 0) {
                $sortby = $dt->sorting;
                $direc = $dt->direction;
            }

        }


        $onPage = 100;
        $page = !empty($this->get->page) && (int)$this->get->page ? (int)$this->get->page : 1;
        $startElement = ($page - 1) * $onPage;
        $total = wsActiveRecord::useStatic('Blacklist')->count($data);
        $this->view->totalPages = ceil($total / $onPage);
        $this->view->count = $total;
        $this->view->page = $page;
        $this->view->start = ($page == 1) ? 1 : $onPage * ($page - 1);
        $this->view->end = $onPage * ($page - 1) + $onPage;

        $where = count($data) ? 'WHERE ' . implode(' ', $data) : '';


        $this->view->count = wsActiveRecord::useStatic('Blacklist')->count($data);
        $this->view->subscribers = wsActiveRecord::useStatic('Blacklist')->findAll($data, array($sortby => $direc), array($startElement, $onPage));


        echo $this->render('new_mailing/list.tpl.php');
    }

    public function newmailingAction()
    {
        $this->view->post = (object)$_POST;

        if (count($_POST)) {
            if ($this->post->metod == 'add_users') {
                $add = 0;
                $error = 0;
                $find = 0;
                $emials = $this->post->add;

                $good = array();
                $em_list = array();
                $emails = explode("\n", $emials);
                foreach ($emails as $em) {
                    $tmp = explode(';', $em);
                    if (count($tmp) == 1) {
                        $tmp = explode(',', $em);
                    }
                    foreach ($tmp as $t) {
                        $em_list[] = $t;
                    }
                }

                foreach ($em_list as $email) {
                    $email = trim($email);
                    $er = 0;
                    if (!isValidEmailNew($email)) {
                        $error++;
                        $er = 1;
                    }

                    if (!$er) {
                        $fnd = Blacklist::findByEmail($email);
                        if ($fnd) {
                            $find++;
                            $er = 1;
                        }
                    }

                    if (!$er) {
                        $good[] = $email;
                        $add++;
                    }
                }

                die(json_encode(array('text' => implode("\n", $good), 'emails' => implode(";", $good), 'cnt' => count($good), 'message' => 'Добавлено: ' . $add . ' Ошибки: ' . $error . ' В черном списке: ' . $find)));
            } else {


                $errors = array();

                if(!@$_POST['from_email'] || !@$_POST['from_name'])
                    $errors[] = $this->trans->get('Please fill in valid email and name');                    

                if (!@$_POST['subject'])
                    $errors[] = $this->trans->get('Пожалуйста, вваедите тему письма!');

                if (@$_POST['test_email'] && @$_POST['send_test'] && !$this->isValidEmail($_POST['test_email']))
                    $errors[] = $this->trans->get('Please fill in valid test email');

                if (!count($errors)) {
                    $cnt = 0;
                    //generate email

                    if (@$_POST['from_email']) {
                        $admin_email = $_POST['from_email'];
                    } else {
                        $admin_email = Config::findByCode('new_main_admin_email')->getValue();
                    }
                    if (@$_POST['from_name']) {
                        $admin_name = $_POST['from_name'];
                    } else {
                        $admin_name = Config::findByCode('admin_name')->getValue();
                    }

                    $subject = $_POST['subject'];

                    require_once('nomadmail/nomad_mimemail.inc.php');

                    if (@$_POST['send_test'] or @$_POST['test']) {
                        $this->view->name = 'Testing';
                        $this->view->email = 'test@email.com';
                        $msg = $this->view->render('new_mailing/general-email.tpl.php');

                        $mimemail = new nomad_mimemail();
                        $mimemail->debug_status = 'no';
                        $mimemail->set_charset('UTF-8');
                        $mimemail->set_from($admin_email, $admin_name);
                        $mimemail->set_subject($subject);
                        $mimemail->set_to($_POST['test_email'], $admin_name);
                        $mimemail->set_text($msg);
                        $mimemail->set_html($msg);
                        //$mimemail->send();

                        MailerNew::getInstance()->sendToEmail($_POST['test_email'], $admin_name, $subject, $msg, 1, $admin_email, $admin_name);
                        MailerNew::getInstance()->sendToEmail('management@red.ua', $admin_name, $subject, $msg, 1, $admin_email, $admin_name);

                        $cnt++;
                        die(json_encode(array('status' => 'send', 'from' => $admin_email, 'count' => 1, 'emails' => $_POST['test_email'], 'post' => $_POST)));
                        //send test email
                        //die('sending test');
                    } elseif (isset($_POST['preview'])) {
                        echo $this->view->render('new_mailing/general-email.tpl.php');
                        exit;
                    } elseif (@$_POST['test'] == 0) {


                        $count = $this->post->count;
                        $send_emails = explode(';', $this->post->emails);
                        for ($i = $this->post->from_mail; $i < $this->post->from_mail + $count; $i++) {
                            if (isset($send_emails[$i]) and isValidEmailNew(@$send_emails[$i]) and isValidEmailRu($this->view->email)) {
                                $email = @$send_emails[$i];
                                set_time_limit(180);
                                wsLog::add('Sending email to ' . $email, 'EMAIL');
                                $this->view->name = '';
                                $this->view->email = $email;
                                $msg = $this->view->render('new_mailing/general-email.tpl.php');
                                $mimemail = new nomad_mimemail();
                                $mimemail->debug_status = 'no';
                                $mimemail->set_charset('UTF-8');
                                $mimemail->set_from($admin_email, $admin_name);
                                $mimemail->set_subject($subject);
                                $mimemail->set_to($email, '');
                                $mimemail->set_text($msg);
                                $mimemail->set_html($msg);
                                $emails .= $email . ', ';
                                //$mimemail->send();

                                MailerNew::getInstance()->sendToEmail($email, '', $subject, $msg, $new = 1, $admin_email, $admin_name);

                                $cnt++;
                            }
                        }


                        die(json_encode(array('status' => 'send', 'from' => $this->post->from_mail, 'count' => $count, 'emails' => $emails)));

                    }
                    $this->view->saved = $cnt;
                } else {
                    $this->view->errors = $errors;
                }
            }
        }

        echo $this->render('new_mailing/general.tpl.php');
    }

    public function logoAction()
    {
        $logotype = Config::findByCode('logotype');
        $slogon = Config::findByCode('slogon');
        $header_info = Config::findByCode('header_info');
        $phones = Config::findByCode('phones');

        if (count($_POST)) {
            $slogon->setValue(stripslashes(trim($_POST['slogon'])));
            $slogon->save();
            $header_info->setValue(stripslashes(trim($_POST['header_info'])));
            $header_info->save();
            $phones->setValue(stripslashes(trim($_POST['phones'])));
            $phones->save();

            if (@$_FILES['logotype']) {
                require_once('upload/class.upload.php');
                $handle = new upload($_FILES['logotype'], 'ru_RU');
                $handle->image_resize = true;
                $handle->image_x = 250;
                $handle->image_y = 250;
                $handle->image_ratio_no_zoom_in = true;
                $folder = '/img/logo/';
                if ($handle->uploaded) {
                    if (!count($errors)) {
                        $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                        if ($handle->processed) {
                            $logotype->setValue($folder . $handle->file_dst_name);
                            $logotype->save();
                            $handle->clean();
                        }
                    }
                }
            }
        }
        $this->view->slogon = $slogon;
        $this->view->logotype = $logotype;
        $this->view->header_info = $header_info;
        $this->view->phones = $phones;
        echo $this->render('config/logo.tpl.php');

    }

    public function paymentsAction()
    {
        if($this->get->order){
            $sqlr = "
			SELECT DISTINCT
				pay_result.LMI_PAYMENT_NO,
				pay_result.LMI_PAYMENT_AMOUNT,
				pay_send.LMI_PAYMENT_SYSTEM,
				pay_result.cdt
			FROM
				pay_result
				inner JOIN pay_send ON pay_send.LMI_PAYMENT_NO = pay_result.LMI_PAYMENT_NO
                                where pay_result.LMI_PAYMENT_NO = ".(int)$this->get->order;
        $this->view->searsh = (object)wsActiveRecord::findByQueryFirstArray($sqlr); 
        }
        
	
					$sql = "
			SELECT DISTINCT
				pay_result.LMI_PAYMENT_NO,
				pay_result.LMI_PAYMENT_AMOUNT,
				pay_send.LMI_PAYMENT_SYSTEM,
				pay_result.cdt
			FROM
				pay_result
				inner JOIN pay_send ON pay_send.LMI_PAYMENT_NO = pay_result.LMI_PAYMENT_NO
			ORDER BY
				pay_result.cdt DESC 
				LIMIT 200
		";
	$payments = wsActiveRecord::findByQueryArray($sql);
        $this->view->payments = $payments;
        $sql1 = "
			SELECT DISTINCT
				pay_unsuccess.LMI_PAYMENT_NO,
				pay_unsuccess.LMI_PAYMENT_AMOUNT,
				pay_send.LMI_PAYMENT_SYSTEM,
				pay_unsuccess.LMI_CLIENT_MESSAGE,
				pay_unsuccess.cdt
			FROM
				pay_unsuccess
				inner JOIN pay_send
				ON pay_send.LMI_PAYMENT_NO = pay_unsuccess.LMI_PAYMENT_NO
				WHERE pay_unsuccess.LMI_PAYMENT_NO IS NOT NULL 
			ORDER BY
				pay_unsuccess.cdt DESC  
				LIMIT 100
		";
		$payments_no = wsActiveRecord::findByQueryArray($sql1);
                $this->view->paymentsno = $payments_no;
		echo $this->render('payment/success.tpl.php');
		
        
	}
// дальше все сделал Ярик
public function reviewsAction(){
$data = array();
$text= '';
//echo print_r($this->get->views);
//die();
if(isset($this->get->views)){
$this->view->type = $this->get->views;
$id = $this->get->views;

switch($id){
case 1:
       $data['public'] = 0;
	   $data['parent_id'] = 0;
        break;
case 2:
		$data['public'] = 1;
		$data['parent_id'] = 0;
        break;
case 3:
       $data['public'] = 0;
	   $data[] = 'parent_id != 0';
        break;
case 4:
       $data['public'] = 1;
	   $data[] = 'parent_id != 0';
        break;
case 5:
       $data['public'] = 2;
        break;

}

}

	//одобряем
	if(isset($_GET['good_comment']))
	{
	$id = $_GET['good_comment'];
	mysql_query("update ws_comment_system set public = '1' where id = '{$id}'") or die ("Error! query - set");	
	}
		//скрываем
	if(isset($_GET['hide_comment']))
	{
	$id = $_GET['hide_comment'];
	mysql_query("update ws_comment_system set public = '2' where id = '{$id}'") or die ("Error! query - set");	
	}
		//удаляем
	if(isset($_GET['delete_comment']))
	{
	$id = $_GET['delete_comment'];
	mysql_query("delete from ws_comment_system where id = '{$id}'") or die ("Error! query - delete");
	}
	
	 $this->view->comments = wsActiveRecord::useStatic('Reviews')->findAll($data, array(),array(100));
	$mas = array();
	
	
	$mas['otziw_new'] = wsActiveRecord::useStatic('Reviews')->count(array('public'=>0, 'parent_id'=>0));
	$mas['otziw_ok'] = wsActiveRecord::useStatic('Reviews')->count(array('public'=>1, 'parent_id'=>0));
	$mas['otvet_new'] = wsActiveRecord::useStatic('Reviews')->count(array('public'=>0, 'parent_id !=0'));
	$mas['otvet_ok'] = wsActiveRecord::useStatic('Reviews')->count(array('public'=>1, 'parent_id !=0'));
	$mas['hide'] = wsActiveRecord::useStatic('Reviews')->count(array('public'=>2));
	$this->view->c_rew = $mas;

		echo $this->render('reviews/index.php');
	}
	
	public function noticeAction()
				{
	if(isset($_GET['subscribe'])){
		$sql = "
			SELECT * FROM  `ws_return_article` ORDER BY  `ws_return_article`.`id` DESC";
			$notice = wsActiveRecord::findByQueryArray($sql);
		$count = count($notice);
		$this->view->count_all = $count;
        $this->view->notice = $notice;
		
		}elseif(isset($_GET['notified'])){
		$sql = "SELECT * FROM  `ws_return_article` WHERE  `utime` IS NOT NULL ORDER BY  `ws_return_article`.`id` DESC ";
		$notice = wsActiveRecord::findByQueryArray($sql);
		$count = count($notice);
		$this->view->count_ok = $count;
        $this->view->notice = $notice;
		}elseif(isset($_GET['go-notified'])){
				$sql = "SELECT * FROM  `ws_return_article` WHERE  `utime` IS NULL ORDER BY  `ws_return_article`.`id` DESC ";
		$notice = wsActiveRecord::findByQueryArray($sql);
		$count = count($notice);
		$this->view->count_zd = $count;
        $this->view->notice = $notice;
		}else{
		$sql = "SELECT * FROM  `ws_return_article` ORDER BY  `ws_return_article`.`id` DESC";
		$notice = wsActiveRecord::findByQueryArray($sql);
		$count = count($notice);
		$this->view->count_all = $count;
        $this->view->notice = $notice;
		}
		
		
		
		if($_GET['code']) { 
			$id = $this->get->id;
			$code = $this->get->code;
			$this->sendMailAddCountTrue($code, $id);
			unset($_GET['code']);
			unset($_GET['id']);
		}
		
		echo $this->render('notice/index.php');
		
		
	}
	public function notactiveusersAction(){
	
	
	$sql = "SELECT * FROM  `ws_customers` WHERE  `utime` < ( NOW( ) - INTERVAL 1 YEAR ) ORDER BY  `ws_customers`.`deposit` DESC  ";
	$notactive = wsActiveRecord::findByQueryArray($sql);
        $this->view->notactive = $notactive;
	echo $this->render('user/notactiveusers.php');
	}
	
	
		public function presentblocksAction(){
	$date = array();
        if (isset($_GET['block']) and (int)$_GET['block'] > 0) $date['block'] = (int)$_GET['block'];
        $this->view->pages = wsActiveRecord::useStatic('PresentationBlock')->findAll($date, array('block' => 'ASC'));
        echo $this->render('page/blocklist_present.tpl.php');
	
	
	}
	public function presentblockAction()
    {

        if (isset($this->get->delete) and (int)$this->get->delete > 0) {
            $block = new PresentationBlock((int)$this->get->delete);
            if ($block->getId()) $block->destroy();
            $this->_redir('presentblocks');
        }
        if (isset($this->get->edit)) {
            $block = new PresentationBlock((int)$this->get->edit);
            if (count($_POST)) {
                $errors = array();
                foreach ($_POST as &$value)
                    $value = stripslashes($value);
                $block->import($_POST);
                if (@$_FILES['image']) {
                    require_once('upload/class.upload.php');
                    $handle = new upload($_FILES['image'], 'ru_RU');
                    $folder = '/storage/presentation/';//Config::findByCode('menu_folder')->getValue();
                    if ($handle->uploaded) {

                        if (!count($errors)) {
                            $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                            if ($handle->processed) {
                                if ($block->getImage())
                                    @unlink($block->getImage());
                                $block->setImage($folder . $handle->file_dst_name);
                                $handle->clean();
                            }
                        }
                    }
                }
                $block->save();
                $this->view->saved = 1;
            }
            $this->view->block = $block;
            echo $this->render('page/blockedit_present.tpl.php');

        }

    }

    public function blogAction()
    {

        if (isset($this->get->delete) and (int)$this->get->delete > 0) {
        $n = new Blog((int)$this->get->delete);
		if ($n->getId()) {$n->destroy();}
            $this->_redir('blog-post');
        }
        
        if($this->cur_menu->getParameter() == 'new'){
            if (count($_POST)) {
                 $block = new Blog();
                 $errors = array();
                 $errors = array();
		        if (!$_POST['post_name']){$errors[] = 'Пожалуйста заполните поле "Заголовок"';}
                if(!$_POST['preview_post']){ $errors[] = 'Пожалуйста заполните поле "Вступительная часть"';}
                if(!$_POST['content_post']){ $errors[] = 'Пожалуйста заполните поле "Содержимое"';}
                
                if (!count($errors)) {
				
					$block->setPostName($_POST['post_name']);
                    if (!empty($_POST['post_name_uk'])) {
                        $block->setPostNameUk($_POST['post_name_uk']);
                    } else {
                        $block->setPostNameUk($this->trans->translate($_POST['post_name'], 'ru', 'uk'));
                    }
					$block->setCtime($_POST['ctime']);
					$block->setUtime(date("y-m-d h:i:s"));
					if(isset($_POST['autor'])) { $block->setAutor($_POST['autor']); $block->setAutorUk($_POST['autor']);}
					//$block->setAutor($autor);
					$block->setPreviewPost($_POST['preview_post']);
                    if (!empty($_POST['preview_post_uk'])) {
                        $block->setPreviewPostUk($_POST['preview_post_uk']);
                    } else {
                        $block->setPreviewPostUk($this->trans->translate($_POST['preview_post'], 'ru', 'uk'));
                    }
					$block->setContentPost($_POST['content_post']);
                    if (!empty($_POST['content_post_uk'])) {
                        $block->setContentPostUk($_POST['content_post_uk']);
                    } else {
                        $block->setContentPostUk($this->trans->translate($_POST['content_post'], 'ru', 'uk'));
                    }
					if (isset($_POST['public']))$block->setPublic(1);
						else $block->setPublic(0);
						$text_cat = '0';	
						if(isset($_POST['c1'])) {$text_cat .=',1';}
						if(isset($_POST['c2'])) {$text_cat .=',2';}
						if(isset($_POST['c3'])) {$text_cat .=',3';}
						if(isset($_POST['c4'])) {$text_cat .=',4';}
						if(isset($_POST['c5'])) {$text_cat .=',5';}
						if(isset($_POST['c6'])) {$text_cat .=',6';}
						if(isset($_POST['c7'])) {$text_cat .=',7';}
					$block->setDescription($_POST['description']);
					$block->setKeyword($_POST['keyword']);
					$block->setCategories($text_cat);
					$block->setImage($_POST['image']);
					
                    $block->save();
					
                $this->view->saved = 1;
                $this->view->blogedit = $block;
                $this->_redir('blog-post/edit/id/'.$block->id);
                } else {
                    $this->view->errors = $errors;
                }
            }
            
             echo $this->render('blog/new.tpl.php');
            
        }elseif($this->cur_menu->getParameter() == 'edit'){
            $block = new Blog((int)$this->get->id);
           // var_dump($this->get);
            if (count($_POST)) {
                $errors = array();
		if (!$_POST['post_name']){$errors[] = 'Пожалуйста заполните поле "Заголовок"';}
                if(!$_POST['preview_post']){ $errors[] = 'Пожалуйста заполните поле "Вступительная часть"';}
                if(!$_POST['content_post']){ $errors[] = 'Пожалуйста заполните поле "Содержимое"';}

				if (!count($errors)) {
				
					$block->setPostName($_POST['post_name']);
					if (!empty($_POST['post_name_uk'])) {
                        $block->setPostNameUk($_POST['post_name_uk']);
                    } else {
                        $block->setPostNameUk($this->trans->translate($_POST['post_name'], 'ru', 'uk'));
                    }
					$block->setCtime($_POST['ctime']);
					$block->setUtime(date("y-m-d h:i:s"));
					if(isset($_POST['autor'])) { $block->setAutor($_POST['autor']); $block->setAutorUk($this->trans->translate($_POST['autor'], 'ru', 'uk'));}
					//$block->setAutor($autor);
					$block->setPreviewPost($_POST['preview_post']);
					if (!empty($_POST['preview_post_uk'])) {
                        $block->setPreviewPostUk($_POST['preview_post_uk']);
                    } else {
                        $block->setPreviewPostUk($this->trans->translate($_POST['preview_post'], 'ru', 'uk'));
                    }
                    $block->setContentPost($_POST['content_post']);
					if (!empty($_POST['content_post_uk'])) {
                        $block->setContentPostUk($_POST['content_post_uk']);
                    } else {
                        $block->setContentPostUk($this->trans->translate($_POST['content_post'], 'ru', 'uk'));
                    }
					if (isset($_POST['public'])){
					    $block->setPublic(1);
					} else {
					    $block->setPublic(0);
					}
                    $text_cat = '0';
                    if(isset($_POST['c1'])) {$text_cat .=',1';}
                    if(isset($_POST['c2'])) {$text_cat .=',2';}
                    if(isset($_POST['c3'])) {$text_cat .=',3';}
                    if(isset($_POST['c4'])) {$text_cat .=',4';}
                    if(isset($_POST['c5'])) {$text_cat .=',5';}
                    if(isset($_POST['c6'])) {$text_cat .=',6';}
                    if(isset($_POST['c7'])) {$text_cat .=',7';}
					$block->setDescription($_POST['description']);
                    if (!empty($_POST['description_uk'])) {
                        $block->setDescriptionUk($_POST['description_uk']);
                    } else {
                        $block->setDescriptionUk($this->trans->translate($_POST['description'], 'ru', 'uk'));
                    }
					$block->setKeyword($_POST['keyword']);
					$block->setCategories($text_cat);
					$block->setImage($_POST['image']);

                    $block->save();

                $this->view->saved = 1;
                } else {
                    $this->view->errors = $errors;
                }
    
            }
            $this->view->blogedit = $block;
        echo $this->render('blog/edit.tpl.php');
            
        }else{
        $this->view->blog = wsActiveRecord::useStatic('Blog')->findAll();
        echo $this->render('blog/list.tpl.php');
        }
    }
	
	public function blogeditAction()
    {

	
	
		
		

    }
	
	public function depositAction(){

	$data = array();
	if ($this->get->email > 0) {
	$customer = (int)$_GET['email'];
	$data[] = ' customer_id = ' . $customer;
	$sqladd = "SELECT SUM(  `info` ) as sum
FROM  `deposit_history` 
WHERE  `action` =  '+'
AND  `customer_id` =".$customer;
$sqlmin = "SELECT SUM(  `info` ) as sum
FROM  `deposit_history` 
WHERE  `action` =  '-'
AND  `customer_id` =".$customer;
	}else if(@$this->get->from and @$this->get->to){
		$sqladd = "SELECT SUM(  `info` ) as sum 
FROM  `deposit_history` 
WHERE  `action` =  '+' and `ctime` >= '".date('Y-m-d H:i:s ', strtotime($this->get->from))."' and `ctime` <= '".date('Y-m-d H:i:s ', strtotime($this->get->to))."' ";
$sqlmin = "SELECT SUM(  `info` ) as sum
FROM  `deposit_history` 
WHERE  `action` =  '-' and `ctime` >= '".date('Y-m-d H:i:s ', strtotime($this->get->from))."' and `ctime` <= '".date('Y-m-d H:i:s ', strtotime($this->get->to))."' ";
	
	}else{
	$sqladd = "SELECT SUM(  `info` ) as sum 
FROM  `deposit_history` 
WHERE  `action` =  '+'";
$sqlmin = "SELECT SUM(  `info` ) as sum
FROM  `deposit_history` 
WHERE  `action` =  '-'";
	}
	if ($this->get->admin > 0) {
	$admin = (int)$_GET['admin'];
	$data[] = ' admin_id = ' . $admin;
	}
	
        $this->view->deposit = wsActiveRecord::useStatic('DepositHistory')->findAll($data, array('id' => 'DESC'), array(200));

			
			$add = wsActiveRecord::useStatic('DepositHistory')->findByQuery($sqladd);
				$this->view->sumadd = $add;
			$min = wsActiveRecord::useStatic('DepositHistory')->findByQuery($sqlmin);
				$this->view->summin = $min;
				
				
				
	echo $this->render('page/deposit.tpl.php');
	}
	
	
	// yarik - nova pochta
	public function novapochtaAction(){
	require_once('np/NovaPoshta.php');
	//$np = new NovaPoshta('920af0b399119755cbca360907f4fa60', 'ru', true, 'curl');
        $np = new NovaPoshta();
        
//if(isset($_GET['id']) and $_GET['id'] > 0){
//$this->view->order = wsActiveRecord::useStatic('Shoporders')->findById((int)$this->get->id);
//}
if($this->post->metod == 'delete_document'){
    $res = [];
    $res = $np->getDeleteDocument($this->post->ref);
    if($res['success']){
        $order = new Shoporders((int)$this->post->order);
        $order->setNakladna('');
        $order->save();
    }
die(json_encode($res));
}elseif($this->post->metod == 'delete_register'){
     $res = $np->deleteScanSheet($this->post->ref);
    die(json_encode($res));
}elseif($this->post->metod == 'new_registr'){
$ref = array();
$id = explode(',', $this->post->id);
foreach($id as $r){
$re = wsActiveRecord::useStatic('Shopordersmeestexpres')->findById((int)$r)->getRef();
if($re){ $ref[] = $re;}
}
$res = $np->newRegistr($ref);
//$res = $np->getRegistr('8b1e5571-c61f-11e7-becf-005056881c6b');
if($res['data'][0]['Ref']){
	$reg = new WsMeestRegister();
	$reg->setCtime(date('Y-m-d H:i:s'));
	//$reg->setList($res['data'][0]['success']);
	$reg->setRegister($res['data'][0]['Ref']);
	$reg->setKuryer($res['data'][0]['Number']);
	$reg->save();
}
die(json_encode($res));
}else if($this->post->metod == 'new_ttn'){
   
if($this->post->cost == 0){
$this->post->cost = 100;
}else{
$this->post->cost = ceil($this->post->cost);
}
$sender = array(
'CitySender' => '8d5a980d-391c-11dd-90d9-001a92567626',
'Sender' => 'df31fa0b-f0f4-11ea-8513-b88303659df5',
'SenderAddress' => '16d300ea-8501-11e4-acce-0050568002cf',//'01ae25f4-e1c2-11e3-8c4a-0050568002cf' - ,
'ContactSender' => 'f2d13386-f0f4-11ea-8513-b88303659df5',
'SendersPhone' => '380674069080',
);
$recipient = array(
'CityRecipient' => $this->post->cityrecipient,
'Recipient' => 'df32b679-f0f4-11ea-8513-b88303659df5',
'RecipientAddress' => $this->post->recipient,
'ContactRecipient' => $this->post->recipientname,
'RecipientsPhone' => $this->post->phones,
);
$params = array(
'Cost' => $this->post->cost,
'Description' => 'Одяг',
'Weight' =>$this->post->weight,
'DateTime' => date('d.m.Y'),//, strtotime("now +1 days")
'ServiceType' => 'WarehouseWarehouse',
'PaymentMethod' => 'Cash',
'PayerType' => $this->post->payer_type,//'Recipient', 
'SeatsAmount' => $this->post->seatsamount,
'CargoType' => $this->post->сargoеype,
'VolumeGeneral' => $this->post->volumegeneral,
'ClientBarcode' => $this->post->clientbarcode
);
if($this->post->type == 16){
$params['BackwardDeliveryData'] = [array(
'PayerType' => 'Recipient',
'CargoType' => 'Money',
'RedeliveryString' => $this->post->cost
)]; 
}
//$errors = array('zxgzxcgf');
//$res = array($sender, $recipient, $params, $errors);

$res = $np->newInternetDocument($sender, $recipient, $params);
if($res['data'][0]['Ref']){
    unset($_COOKIE['uid_city']);
    unset($_COOKIE['uid_counterparties']);
    unset($_COOKIE['uid_warehouses']);
$order = new Shoporders((int)$this->post->clientbarcode);
if($order){
if($order->getMeestId()){
$or_np = new Shopordersmeestexpres((int)$order->getMeestId());
if($or_np){
$or_np->setRef($res['data'][0]['Ref']);
$or_np->setStatus(1);
$or_np->setCost($res['data'][0]['CostOnSite']);
if($or_np->getOrderId() != $this->post->clientbarcode) { $or_np->setOrderId((int)$this->post->clientbarcode);}
if($this->post->cityrecipient != $or_np->uuid_city ){ $or_np->setUuidCity(trim($this->post->cityrecipient)); }
if($this->post->recipient != $or_np->getUuidBranch()){ $or_np->setUuidBranch($this->post->recipient); }
$or_np->save();
}
}else{
$or_np_new = new Shopordersmeestexpres();
$or_np_new->setUuidCity($this->post->cityrecipient);
$or_np_new->setUuidBranch($this->post->recipient);
$or_np_new->setOrderId($order->getId());
$or_np_new->setRef($res['data'][0]['Ref']);
$or_np_new->setStatus(1);
$or_np_new->setCost($res['data'][0]['CostOnSite']);
$or_np_new->save();
$order->setMeestId($or_np_new->getId());
}
$cust = new Customer($order->getCustomerId());
if($cust){
if($cust->getUuidNp() == NULL){
$cust->setUuidNp($this->post->recipientname);
$cust->save();
}
}
$order->setNakladna($res['data'][0]['IntDocNumber']);
$order->save();
}
$res['print'] = $np->printMarking100x100($res['data'][0]['Ref'], 'pdf_link');
//$print = $np->printDocument($res['data'][0]['Ref']);
}
die(json_encode($res));
}elseif($this->post->metod == 'update_counterparties'){
//$res = array();
$res = $np->updateContactPerson(['Ref' => $this->post->ref, 'LastName' => $this->post->lastname, 'FirstName' => $this->post->firstname, 'MiddleName' =>$this->post->middlename, 'Phone' =>$this->post->phone]);
die(json_encode($res));
}elseif($this->post->metod == 'new_counterparties'){
//$res = array();
$res = $np->newContactPerson(['LastName' => $this->post->lastname, 'FirstName' => $this->post->firstname, 'MiddleName' =>$this->post->middlename, 'Phone' =>$this->post->phone]);
die(json_encode($res));
}elseif ($this->get->metod == 'warehouses') {
   $wh = $np->getWarehouses($this->get->getWarehouses());
		$text = '';
    foreach ($wh['data'] as $warehouse) {
	$pos = strpos($warehouse['DescriptionRu'], 'Почтомат');
	if($pos === false){
	$text.='<option dat-value="'.$warehouse['Ref'].'" value="'.$warehouse['DescriptionRu'].'">'.$warehouse['DescriptionRu'].'</option>';
	}
    }
	die($text);
	}else if($this->get->metod == 'tracking'){
$text = '';
$result = $np->documentsTracking2($this->get->getTracking());
if($result['StatusCode'] != 3 and $result['StatusCode'] != 1){
	$text.="Відправлення: ".$result['Number']."<br>";
	if($result['RecipientFullName'])$text.="Отримувач: ".$result['RecipientFullName']."<br>";
	if($result['PhoneRecipient']) $text.="Телефон отримувача: ".$result['PhoneRecipient']."</br>";
	$text.="Маршрут: ".$result['CitySender']." - ".$result['CityRecipient']."<br>";
	$text.="Адреса: ".$result['RecipientAddress']."<br>";
	if($result['RecipientDateTime']) {
	$text.="Статус: ".$result['Status']." ".$result['RecipientDateTime']."<br>";
	}else{
	$text.="Статус: ".$result['Status'].".  Очікувана дата доставки ".$result['ScheduledDeliveryDate']."<br>";
	}
	$text.="Вага відправлення: ".$result['DocumentWeight']." кг.<br>"; 
	$text.="Ціна доставки: ".$result['DocumentCost']." грн.<br>"; 
	if($result['Redelivery']==1) {$text.="Зворотня доставка: ".$result['RedeliverySum']." грн.<br>";}
	if($result['LastTransactionStatusGM']) {$text.="Відправлений  грошевой переказ. Статус: ".$result['LastTransactionStatusGM']."</br>";}
	if($result['UndeliveryReasonsSubtypeDescription']) {$text.="Причина відмови: ".$result['UndeliveryReasonsSubtypeDescription']."</br>";}
	if($result['LastCreatedOnTheBasisNumber'] and strlen($result['LastCreatedOnTheBasisNumber']) ==14){
	$result1 = $np->documentsTracking2($result['LastCreatedOnTheBasisNumber']);
	$text.="</br>Повернення: ";
	$text.=$result1['Number']."</br>";
	if($result1['RecipientFullName']){$text.="Отримувач: ".$result1['RecipientFullName']."<br>";}
	$text.="Маршрут: ".$result1['CitySender']." - ".$result1['CityRecipient']."<br>";
	$text.="Адреса: ".$result1['WarehouseRecipient']."<br>";
	$text.="Статус: ".$result1['Status']." ".$result1['RecipientDateTime']."<br>";
	$text.="Вага відправлення: ".$result1['DocumentWeight']." кг.<br>"; 
	$text.="Ціна доставки: ".$result1['DocumentCost']." грн.<br>";
	}
}else{
$text.=$result['Status'];
}
die($text);
}elseif($this->get->what == 'citynpochta'){
				$cities = $np->getCities(0, $this->get->term);
				$mas = array();
		$i = 0;
		foreach ($cities['data'] as $c) {
		$mas[$i]['label'] = $c['Description'];
		$mas[$i]['value'] = $c['Description'];
		$mas[$i]['id'] = $c['Ref']; 
		$i++;
			}
			echo json_encode($mas);
				
				
	
				die();
				}elseif($this->get->what == 'warehouses'){
			$wh = $np->getWarehouses1($_COOKIE['uid_city'], $this->get->term);
				$mas = [];
		$i = 0;

		foreach ($wh['data'] as $c) {
		$mas[$i]['label'] = $c['Description'];
		$mas[$i]['value'] = $c['Description'];
		$mas[$i]['id'] = $c['Ref']; 
		$mas[$i]['city'] = $_COOKIE['uid_city'];
		$mas[$i]['term'] = $this->get->term;
		$i++;
			}
			echo json_encode($mas);
				die();
				}elseif($this->get->what == 'counterparties'){
				$counterparties = $np->getCounterpartyContactPersons('df32b679-f0f4-11ea-8513-b88303659df5', $this->get->term, $_COOKIE['uid_city']);

		$mas = [];
		$i = 0;
                if($counterparties['data']){
		foreach ($counterparties['data'] as $c) {
		$mas[$i]['label'] = $c['Description'].', тел: '.$c['Phones'];
		$mas[$i]['value'] = $c['Description'];
		$mas[$i]['id'] = $c['Ref']; 
		$mas[$i]['phones'] = $c['Phones']; 
		$i++;
			}
                                }
			echo json_encode($mas);
				
				
	
				die();
				}else if($this->get->metod == 'ukr'){	
$result = '';

try {			
$client = new SoapClient('http://services.ukrposhta.ua/barcodestatistic/barcodestatistic.asmx?WSDL');
if($client){
$params = new stdClass();
$params->guid = 'fcc8d9e1-b6f9-438f-9ac8-b67ab44391dd';//fcc8d9e1-b6f9-438f-9ac8-b67ab44391dd
$params->barcode = $this->get->getUkr();
$params->culture = 'uk';
$result .= (string)$client->GetBarcodeInfo($params)->GetBarcodeInfoResult->eventdescription;
}else{
$result .='Сервис временно недоступен!';
}

} catch (Exception $e) {
        $result.= $e->getMessage();
    }
//var_dump($result);
die($result);
				}elseif($this->cur_menu->getParameter() == 'list'){
            $this->view->registers = $np->getScanSheetList();
            
            echo $this->render('np/register_list.php', 'index.php');
        }elseif($this->cur_menu->getParameter() == 'new'){
            if($this->get->id){
$this->view->order = wsActiveRecord::useStatic('Shoporders')->findById((int)$this->get->id);
}
   echo $this->render('np/new_ttn.php', 'index.php');
        }else{
				
		$o_stat = wsActiveRecord::useStatic('OrderStatuses')->findAll();
        $mas_os = array();
        foreach ($o_stat as $o) { $mas_os[$o->getId()] = $o->getName();}
		
        $this->view->order_status = $mas_os;		
/*				
$sql = 'SELECT  `ws_order_meestexpres`.`ctime` ,  `ws_order_meestexpres`.`id` AS  `np_id` , `ws_order_meestexpres`.`order_id` ,  `ws_orders`.`status` ,  `ws_orders`.`id` AS  `or_id` ,  `ws_orders`.`city` , `ws_orders`.`sklad` , `ws_orders`.`nakladna`, `ws_orders`.`order_go`, `ws_orders`.`delivery_type_id` as `type_id`
FROM  `ws_order_meestexpres` 
INNER JOIN  `ws_orders` ON  `ws_order_meestexpres`.`order_id` =  `ws_orders`.`id` 
WHERE  `ws_orders`.`status` 
IN ( 0, 1, 8, 9, 15, 16 ) ORDER BY  `ws_order_meestexpres`.`ctime` DESC ';	*/
		$this->view->all_order = wsActiveRecord::useStatic('Shoporders')->findAll(array('status in(1,9,15,16)', 'delivery_type_id in(8,16)', 'is_admin'=>0));
		//------
		//$cities = $np->getCities();
		//$this->view->cities = $cities['data']; 
			echo $this->render('np/np.tpl.php', 'index.php');
}
	
	}
	// decoding str
	public function decode($encoded, $key){//расшифровываем
		$strofsym="qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM=";//Символы, с которых состоит base64-ключ
			$x=0;
			while ($x++<= strlen($strofsym)) {//Цикл
			$tmp = md5(md5($key.$strofsym[$x-1]).$key);//Хеш, который соответствует символу, на который его заменят.
			$encoded = str_replace($tmp[3].$tmp[6].$tmp[1].$tmp[2], $strofsym[$x-1], $encoded);//Заменяем №3,6,1,2 из хеша на символ
			}
			return base64_decode($encoded);//Вертаем расшифрованную строку
			}
	
	
	// yarik - аналитика
	public function analiticsAction(){
	
	if($this->post->metod == 'goo'){
	
	 $from = strtotime($this->post->from);
	 $to = strtotime($this->post->to);
         if( $from < $to ){
	
	$from = date('Y-m-d 00:00:00', $from);
	$to = date('Y-m-d 23:00:00', $to);


	$where='';
	if($this->post->interval == 1){
	$where .= " DATE(  `date_create` ) "; 
	}elseif($this->post->interval == 2){
	$where .= " WEEK(  `date_create`, 0 ) ";
	}elseif($this->post->interval == 3){
	$where .=" DATE_FORMAT(  `date_create` ,  '%Y-%m' ) ";
	
	}else{
	$where .= " DATE(  `date_create` ) ";
	}

	
	$sql1 = "SELECT COUNT( * ) as `coll`, SUM( `amount` ) as `sum` FROM `ws_orders` WHERE `status` not in(2, 7, 17) AND `date_create` >= '".$from."' AND `date_create` <= '".$to."' GROUP BY ". $where ." ORDER BY `ws_orders`.`date_create` ASC ";
        $orders1 = wsActiveRecord::useStatic('Shoporders')->findByQuery($sql1);
		
		$sql2 = "SELECT COUNT( * ) as `coll`, SUM( `amount` ) as `sum` FROM `ws_orders` WHERE `status` in(2, 7) AND `date_create` >= '".$from."' AND `date_create` <= '".$to."' GROUP BY ". $where ." ORDER BY `ws_orders`.`date_create` ASC ";
        $orders2 = wsActiveRecord::useStatic('Shoporders')->findByQuery($sql2);
		
		$sql_all = "SELECT `date_create` FROM `ws_orders` WHERE  `date_create` >= '".$from."' AND `date_create` <= '".$to."' GROUP BY ". $where ." ORDER BY `ws_orders`.`date_create` ASC ";
        $orders_all = wsActiveRecord::useStatic('Shoporders')->findByQuery($sql_all);
		

$date = array();
$date2 = array();
$year = array();
$i = 0;
foreach ($orders1 as $doc) {
$date[$i]= (int)$doc->getColl();
$i++;
}
$i = 0;
foreach ($orders2 as $doc) {
$date2[$i]= (int)$doc->getColl();
$i++;
}
$i = 0;
foreach ($orders_all as $doc) {
$z = strtotime($doc->getDateCreate());
$z = date('d-m', $z);
$year[$i] = $z;
$i++;
}

	$result = array('send'=>$date, 'year'=>$year, 'send2'=>$date2);
	  //print json_encode($result);
            die(json_encode($result));
        }
	}else if($this->post->metod == 'goodelivery'){
	 	$from = strtotime($this->post->fromd);
		$to = strtotime($this->post->tod);
			 if($from < $to){ 
			$from = date('Y-m-d 00:00:00', $from);	
			$to = date('Y-m-d 23:00:00', $to);


	$where='';
	if($this->post->interval == 1){
	$where .= " DATE(  `date_create` ) "; 
	}elseif($this->post->interval == 2){
	$where .= " WEEK(  `date_create`, 0 ) ";
	}elseif($this->post->interval == 3){
	$where .=" DATE_FORMAT(  `date_create` ,  '%Y-%m' ) ";
	
	}else{
	$where .= " DATE(  `date_create` ) ";
	}
	
	$sql_m = "SELECT * , COUNT( * ) as `coll`, SUM( `amount` ) as `sum`  FROM `ws_orders` WHERE delivery_type_id in(3,12,5) and `status` not in(2, 7, 17) AND `date_create` >= '".$from."' AND `date_create` <= '".$to."' GROUP BY ". $where ." ORDER BY `ws_orders`.`date_create` ASC ";
        $orders_m = wsActiveRecord::useStatic('Shoporders')->findByQuery($sql_m);
			
			$sql_np = "SELECT * , COUNT( * ) as `coll`, SUM( `amount` ) as `sum`  FROM `ws_orders` WHERE delivery_type_id in(8,16) and `status` not in(2, 7, 17) AND `date_create` >= '".$from."' AND `date_create` <= '".$to."' GROUP BY ". $where ." ORDER BY `ws_orders`.`date_create` ASC ";
        $orders_np = wsActiveRecord::useStatic('Shoporders')->findByQuery($sql_np);
		
		$sql_up = "SELECT * , COUNT( * ) as `coll`, SUM( `amount` ) as `sum`  FROM `ws_orders` WHERE delivery_type_id = 4 and `status` not in(2, 7, 17) AND `date_create` >= '".$from."' AND `date_create` <= '".$to."' GROUP BY ". $where ." ORDER BY `ws_orders`.`date_create` ASC ";
        $orders_up = wsActiveRecord::useStatic('Shoporders')->findByQuery($sql_up);
		
		$sql_k = "SELECT * , COUNT( * ) as `coll`, SUM( `amount` ) as `sum`  FROM `ws_orders` WHERE delivery_type_id = 9 and `status` not in(2, 7, 17) AND `date_create` >= '".$from."' AND `date_create` <= '".$to."' GROUP BY ". $where ." ORDER BY `ws_orders`.`date_create` ASC ";
        $orders_k = wsActiveRecord::useStatic('Shoporders')->findByQuery($sql_k);
		
		// vozvrat
	$sql_return = "SELECT * , COUNT( * ) as `coll`, SUM( `amount` ) as `sum`  FROM `ws_orders` WHERE `status` in(2, 7) AND `date_create` >= '".$from."' AND `date_create` <= '".$to."' GROUP BY ". $where ." ORDER BY `ws_orders`.`date_create` ASC ";
       $orders_return = wsActiveRecord::useStatic('Shoporders')->findByQuery($sql_return);
	   
	   $sql_all = "SELECT * , COUNT( * ) as `coll`, SUM( `amount` ) as `sum`  FROM `ws_orders` WHERE  `date_create` >= '".$from."' AND `date_create` <= '".$to."' GROUP BY ". $where ." ORDER BY `ws_orders`.`date_create` ASC ";
        $orders_all = wsActiveRecord::useStatic('Shoporders')->findByQuery($sql_all);

$date_m = array();
$date_np = array();
$date_k = array();
$date_up = array();
$date_return = array();
$year = array();
$i = 0;
foreach ($orders_m as $doc) {
$date_m[$i] = (int)$doc->getColl();
$i++;
}
$i = 0;
foreach ($orders_np as $doc) {
$date_np[$i] = (int)$doc->getColl();
$i++;
}
$i = 0;
foreach ($orders_up as $doc) {
$date_up[$i] = (int)$doc->getColl();
$i++;
}
$i = 0;
foreach ($orders_k as $doc) {
$date_k[$i] = (int)$doc->getColl();
$i++;
}
$i = 0;
foreach ($orders_all as $doc) {
$z = strtotime($doc->getDateCreate());
$z = date('d-m', $z);
$year[$i] = $z;
$i++;
}
$i = 0;
foreach ($orders_return as $doc) {
$date_return[$i]= (int)$doc->getColl();
$i++;
}
	   
	  
$result = array('send_m'=>$date_m, 'send_np'=>$date_np, 'send_k'=>$date_k, 'send_up'=>$date_up, 'send_retupn'=>$date_return, 'year'=>$year);
	 // print json_encode($result);
            die(json_encode($result));
        }

	}
if($this->post->method == 'othot' and $this->post->from and $this->post->to){
ini_set('memory_limit', '2048M');
//fastcgi_read_timeout(300);
//max_execution_time 2400;
 set_time_limit(1200);
 $start = (int)$this->post->start;
$from = date('Y-m-d', strtotime($this->post->from));
$to = date('Y-m-d', strtotime($this->post->to));


	require_once('PHPExel/PHPExcel/IOFactory.php');
	require_once("PHPExel/PHPExcel/Writer/Excel5.php");
	 $filename = 'ostatki_'.date('Y-m-d').'.xls';
	 $path1file = INPATH . "backend/views/chart/". $filename;
	 // Создаем объект класса PHPExcel
	 if($start == 2){
	 $xls = new PHPExcel();
	 }else{
	 $xls = PHPExcel_IOFactory::load($path1file);
	 }



// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(0);
// Получаем активный лист
$sheet = $xls->getActiveSheet();
// Подписываем лист
//$sheet->setTitle('ostatky');
if( $start == 2){
$sheet->getColumnDimension('A')->setWidth(20);
           $sheet->getColumnDimension('B')->setWidth(40);
            //$sheet->getColumnDimension('C')->setWidth(10);
			// $sheet->getColumnDimension('D')->setWidth(15);

            $boldFont = array(
                'font' => array(
                    'bold' => true,
					'size' => 11
                )
            );
            $sheet->getStyle('A1:BI1')->applyFromArray($boldFont);
			$sheet->getStyle('A2:BI2')->applyFromArray($boldFont);
			// Выравнивание текста
		
			$type =  array(
			'numberformat'=>array(
			'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER
			)
			);
			$sheet->getStyle('C:BI')->applyFromArray($type);
			// Вставляем текст в первую строчку 1
$sheet->setCellValue("A2", 'Бренд');
$sheet->setCellValue("B2", 'Категория');
}

require_once('PHPExel/excelarray.php');//массив буквенных обозначений в excel


$balance = wsActiveRecord::useStatic('Balance')->findFirst(array('date = "'.$from.'" '));
/*$where = '';
$brand_mas = array();
if($this->post->brand and $this->post->brand != 0){
 //$brand_mas[] = (int)$this->post->brand;
 $where = $this->post->brand;// implode(",", $brand_mas);
}
*/


$i = $start;//начнем выводить даты с столбика "С"

$sheet->setCellValue($h[$i].'1', $balance->date);
	$sheet->getStyle($h[$i].'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->setCellValue($h[$i].'2', 'Остаток');
	$sheet->getStyle($h[$i].'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->mergeCells($h[$i].'1:'.$h[++$i].'1');
$sheet->setCellValue($h[$i].'2', 'Продано');
	$sheet->getStyle($h[$i].'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


$mass = array();
//$brand = wsActiveRecord::useStatic('BalanceCategory')->findByQueryArray("SELECT * FROM  `ws_balance_category` group by `id_brand`");
//$brand = wsActiveRecord::useStatic('BalanceCategory')->findByQuery("SELECT DISTINCT `id_brand` FROM `ws_balance_category` where id_brand = ".$where);

//foreach($brand as $k => $b){
$category = wsActiveRecord::findByQueryArray("SELECT DISTINCT `id_category` FROM `ws_balance_category`");
foreach($category as $k => $c){

$sql = "SELECT sum(`count`) as ctn  FROM  `ws_balance_category` where  id_balance = ".$balance->id." and id_category=".$c->id_category;
$count = wsActiveRecord::findByQueryArray($sql)[0]->ctn;

if(!$count)	$count = 0;

$mass[$c->id_category][$balance->id][] = (int)$count;

$sql="SELECT SUM( `ws_order_articles`.`count` ) AS  `ctn` 
FROM  `ws_order_articles` 
INNER JOIN  `ws_orders` ON  `ws_order_articles`.`order_id` =  `ws_orders`.`id` 
INNER JOIN  `ws_articles` ON  `ws_order_articles`.`article_id` =  `ws_articles`.`id` 
WHERE   `ws_orders`.`date_create` > DATE_FORMAT( '".$balance->date."' , '%Y-%m-%d 03:00:00' )
AND `ws_orders`.`date_create` <= DATE_FORMAT(DATE_ADD('" . $balance->date . "', INTERVAL +1 DAY) , '%Y-%m-%d 03:00:00' )
AND  `ws_articles`.`category_id` = ".$c->id_category;
$c_r = wsActiveRecord::findByQueryArray($sql)[0]->ctn;

$mass[$c->id_category][$balance->id][] = (int)$c_r;

}
//}

$ob = 0;
	$i = 3;
	$mas_ob = array(); 
	//$m_ost_pr = array();
	
	/*if($start != 2) {
	foreach ($this->post->mas as $k => $pm){
	$m_ost_pr[$k]['st'] = $pm->st;
	$m_ost_pr[$k]['pr'] = $pm->pr;
	}
	}*/
	//foreach ($mass as $k => $brand) {
	
	foreach($mass as $z => $category){
	if($start == 2){
	//$sheet->setCellValue($h[0].$i, '');
	$cat =  wsActiveRecord::useStatic('Shopcategories')->findById($z);
	$sheet->setCellValue($h[1].$i, $cat->getRoutez());
	}
	//echo $k.' : '.$z.' : ';
	$j = $start;
	$sr = 0.00;
	$pr = 0;
	foreach($category as $q => $d){
	foreach($d as $c => $p){
	
	//if($c == 0){ $sr+=$p; }else{ $pr+=$p;}
	//if($start == 2){
	//if($c == 0){ $m_ost_pr[$i][0] = (int)$p; }else{ $m_ost_pr[$i][1] = (int)$p; }
	//}
	
	$sheet->setCellValue($h[$j].$i, $p);
	$j++;
	}
	}
	//if($sr != 0.00 ){
	//$sr = $sr/$dney;
	//if($pr != 0){ $ob = ($sr/$pr)*$dney; }else{$ob = 0.00;}
	//}else{
	//$ob = 0.00;
	//}

	//$mas_ob[$i] = $ob;
	
	
	$i++;
	}
	//}
	



	
	//header("Content-Type: text/html; charset=utf-8");
		//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      //  header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
      //  header("Cache-Control: no-cache, must-revalidate");
      //  header("Pragma: no-cache");
		//header ( "Content-Disposition: attachment; filename=ostatki.xls");
		
		
		
		// Выводим содержимое файла
		$objWriter = new PHPExcel_Writer_Excel5($xls);
			//$objWriter->save('php://output');
			
			//$path1file = INPATH . "backend/views/trekko/". $filename;
			//	if (file_exists($path1file)){
					//	if (unlink($path1file)) $objWriter->save($path1file);
					//	}else{
						//$objWriter->save($path1file);
					//	}
					
$fifnish = $this->post->finish+=1;

		if($fifnish == $this->post->dney){	

		
		
//$i=3;
$sheet->setCellValue($h[$j].'2', "Оборот/дней");
$sheet->getColumnDimension($h[$j])->setWidth(13);
/*
foreach ($mas_ob as $k => $m) {
//echo $k.':'.$m.'<br>';
$sheet->setCellValue($h[$j].$i, $m);
$sheet->getStyle($h[$j].$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
$i++;
}*/

		
	//	header("Content-Type: text/html; charset=utf-8");
	//	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    //    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    ////   header("Cache-Control: no-cache, must-revalidate");
    //    header("Pragma: no-cache");
	//	header ( "Content-Disposition: attachment; filename=".$filename);
//$objWriter->save('php://output');
$objWriter->save($path1file);
die(json_encode(array('exit'=>"Отчёт сформирован!", 'src'=>'https://www.red.ua/backend/views/chart/'.$filename)));

}else{

$objWriter->save($path1file);
$end_date = date("Y-m-d", strtotime("+1 days", strtotime($from)));
	die(json_encode(array('start'=>$this->post->start+=2, 'from'=>$end_date, 'finish'=>$fifnish, 'dney'=>(int)$this->post->dney)));
	

}	

			
		//die();
}//выход с услови отправки формы

 
	echo $this->render('chart/chart.tpl.php');
	//echo $this->render('chart/chart.tpl.php', 'index.php');
	
	}
	
	// sozdal Yarik история удаления товара без возврата
	public function controldellarticlesAction(){
	if($this->post->metod == 'go'){
	if($this->post->from){
	$from = date('Y-m-d 00:00:00', strtotime($this->post->from));
	}else{
	$d = new DateTime();
	$d->modify("-1 month");
	$from = $d->format("Y-m-d H:i:s");	
	}
	if($this->post->to){
	$to = date('Y-m-d 23:59:59', strtotime($this->post->to));
	}else{ 
	$to = date('Y-m-d H:i:s');
	}
	
	$not = 2993;
	if($this->post->not != ''){ $not.=$this->post->not; }
        
	$kost = wsActiveRecord::useStatic('Shoparticlelog')->findByQuery("SELECT *  FROM  `red_article_log` WHERE customer_id not in(".$not.")  and  type_id = 2 and  `ctime` >=  '".$from."' and `ctime` <= '".$to."'  and `code` IS NOT NULL ORDER BY  `red_article_log`.`id` ASC ");
	
	$date = [];
	$i = 0;
	foreach ($kost as $k) {
	$add = wsActiveRecord::useStatic('Shoparticlelog')->findByQuery("SELECT *  FROM  `red_article_log` WHERE customer_id != 2993 and  type_id in (3,6) and  `ctime` >  '".$from."' and `ctime` <= '".$to."'  and `code` LIKE  '".$k->getCode()."' ");;
	if($add[0]['count'] != $k->getCount()){
	$t = wsActiveRecord::useStatic('Shoparticles')->findById($k->getArticleId());
	$c =  wsActiveRecord::useStatic('Customer')->findById($k->getCustomerId());
	$date[$i]['tovar'] = $t['model']."(".$t['brand'].")"; 
	$date[$i]['model'] = $k->getInfo();
	$date[$i]['articul'] = $k->getCode();
	$date[$i]['admin'] =$c['first_name'].' '.$c['middle_name'];
	$date[$i]['proces'] = $k->getComents();
	$date[$i]['dell'] = $k->getCount();
	$date[$i]['ctime'] = date('d-m H:i', strtotime($k->getCtime()));//date('Y-m-d 23:59:59', $to);
	$i++;
	}
	}

   die(json_encode(array('send'=>$date)));
	}
	echo $this->render('page/controldellarticles.tpl.php', 'index.php');
	}
	
	public function meestexpressAction(){
	require_once('meestexpress/include.php');
	$api = new MeestExpress_API('red_ua','3#snlMO0sP','35a4863e-13bb-11e7-80fc-1c98ec135263');
	
	if($this->post->metod == 'countryUID'){
	//var_dump($countryUID = $api->getCountryUID('Украина'));
	//$countryUID = $api->getCountryUID('Украина');
	//$this->view->country = $countryUID['data']; 
	$text = '';
	$text.= $countryUID['DescriptionUA'];
	//print $countryUID;
	die();
	}else{
	//$countryUID  =  $api->getCountryUID('Украина');
	//$city = $api->getCountry();
	//$branch = $api->getBranch('62C3D54A-749B-11DF-B112-00215AEE3EBE');
		//$city = $api->getCity('Олевс', 'C35B6195-4EA3-11DE-8591-001D600938F8');
	//var_dump($city->items);
	//var_dump($city = $api->getCity(('Олевск', $countryUID));
	//$city = $api->getCityUID('Олевск', $countryUID);
		
		$this->view->city = $branch;
	
	}
	$this->view->content = 'testfhdsfgh<br>dfghdfhdfghdfg'; 
	$msg = $this->view->render('email/template.tpl.php');

//SendMail::getInstance()->sendEmail('php@red.ua', 'RED', 'TEST', $msg);
	echo $this->render('meestexpress/meestexpress.tpl.php');
	}
	
	  public function getmistcityAction()
    { 
	
	if(isset($_GET['uid'])){
		$_SESSION['uid'] = $_GET['uid'];
	}
	
	require_once('meestexpress/include.php');
	$api = new MeestExpress_API('red_ua','3#snlMO0sP','35a4863e-13bb-11e7-80fc-1c98ec135263');
	$strana = 'C35B6195-4EA3-11DE-8591-001D600938F8';
		if($this->get->what == 'city'){
		$city = $api->getCity($this->get->term, $strana);
		$mas = array();
		$i = 0;
		foreach ($city as $c) {	
		$mas[$i]['label'] = (string)$c->DescriptionRU.' ( '.$c->RegionDescriptionRU.' обл. '.$c->DistrictDescriptionRU.' р-н )';
		$mas[$i]['value'] = (string)$c->DescriptionRU.' ( '.$c->RegionDescriptionRU.' обл. '.$c->DistrictDescriptionRU.' р-н )';
		$mas[$i]['id'] = (string)$c->uuid; 
		$i++;
			}
			echo json_encode($mas);
		
		}
		
		if ($this->get->what == 'branch') {
		$branch = $api->getBranch($this->get->term);
		if(count($branch) == 0){
		$text = '<option  value="">В этом городе нет отделений Мист Экспресс</option>';
		}else{
	$text = '';
	$text.='<option  value="">Выберите отделение:</option>';
    foreach ($branch as $b) {
	$text.='<option data-uid="'.$b->UUID.'" value="'.$b->DescriptionRU.'">'.$b->DescriptionRU.'</option>';
    }	
	}
		 die($text);
	}
	
	if ($this->get->what == 'dateplan') {
		$dat = $api->getDeliveryDays($this->get->term);
	$text = '';
	$text.='<option  value="">Выберите дату...</option>';
    foreach ($dat as $b) {
	$text.='<option  value="'.$b.'">'.$b.'</option>';
    }	
		 die($text);
	}
	
	
	if ($this->get->what == 'street') {
$x = $_SESSION['uid'];
		$street = $api->getStreet($this->get->term, $x);
		$mas = array();
		$i = 0;
		foreach ($street as $c) {
		$mas[$i]['label'] = (string)$c->StreetTypeRU.' '.$c->DescriptionRU;
		$mas[$i]['value'] = (string)$c->StreetTypeRU.' '.$c->DescriptionRU;
		$mas[$i]['id'] = (string)$c->uuid; 
		$i++;
			}
			echo json_encode($mas);
	}
	
		if ($this->get->what == 'calc') {
		$paket = 'PAX';

$strach = (float)$this->get->strach;
 $cost = (float)$this->get->cost;
$masa =  0.5;
	if($masa < 1){$paket = 'DOX';}
	$calc = $api->calculateShipments($this->get->uid_type, (string)$this->get->uid_b, (string)$this->get->uid_c, $cost, $strach, $masa, $paket);
	if($cost == 0){$proc = 0;}else{$proc = $cost * 0.02;}
		if($cost > 0 and $proc < 15){$proc = 15;}
		$proc = ceil($proc);
	$calc = $calc + (float)$proc;
	die((float)$calc);
	}

        die();
    }
	
	public function addmeestttnAction(){
	if (@$this->get->edit) {// открытие формы создания ттн
	$this->view->id = $this->get->edit;
	$id = $this->get->edit;
	$this->view->order = wsActiveRecord::useStatic('Shoporders')->findById($id);
	echo $this->render('meestexpress/addmeestttn.tpl.php');
	}else if(@$this->get->register){
	$this->view->register = wsActiveRecord::useStatic('WsMeestRegister')->findAll();
	echo $this->render('meestexpress/meestregister.tpl.php');
	}else if(@$this->get->metod == 'tracking'){
	
	require_once('meestexpress/include.php');
	$api = new MeestExpress_API('red_ua','3#snlMO0sP','35a4863e-13bb-11e7-80fc-1c98ec135263');

	$text = '';
	try{
	$mm = array();
	$mass = $api->shipmentTracking($ttn = $this->get->getTtn());
	if(@$mass){
	foreach ($mass as $c) {
	$mm[date('Y-m-d H:i:s', strtotime($c->SetStatusDateTime))] = $c->StatusUA;
	}
	//var_dump($mass);
	ksort($mm);
	$i = 1;
	foreach ($mm as $k => $c) {
	$text.= '<p style="text-align: left;">'.$i.': '. $k. ' '.$c.'</p>';
	$i++;
	}
	}else{
	$text.='Посылка с таким номером отсутствует!!!';
	}
	} catch (Exception $e) {
	$e->getMessage();
	$text.= $e->getMessage();
	}
	
	die($text);
	}else if(@$this->post->method == 'add_ttn'){ // создать ттн
	require_once('meestexpress/include.php');
	$api = new MeestExpress_API('red_ua','3#snlMO0sP','35a4863e-13bb-11e7-80fc-1c98ec135263');
	$id_m =0;
	if(isset($this->post->meest_id) and $this->post->meest_id != '' ){
	$or_m = new Shopordersmeestexpres($this->post->meest_id);
	$or_m->import($_POST);
	if($or_m->getOrderId() != $this->post->order_id) $or_m->setOrderId($this->post->order_id);
	$or_m->setGotime(date('Y-m-d'));
	$or_m->setDateGo(date('Y-m-d', strtotime($this->post->date_go) ));
	$or_m->save();
	}/*else{	
	$ro_m = new Shopordersmeestexpres();
	$ro_m->import($_POST);
	$ro_m->setGotime(date('Y.m.d', strtotime($this->post->gotime)));
	$ro_m->setDateGo(date('Y.m.d'));
	$ro_m->save();
	$id_m = $ro_m->getId();
	}*/
	
	
	// обновляем данные заказа
	$order = new Shoporders($this->post->order_id);
	$order->import($_POST);
	if($id_m != 0) $order->setMeestId($id_m);
	$order->setOrderGo(date('Y-m-d', strtotime($this->post->date_go)));
        
        $time = time() - strtotime($order->getDateCreate()); 
                        //$day = $time / (24 * 60 * 60);
                           // if($day == 0 and $time > 0){ $day = 1;}
                            $order->setDayOrderGo($time);
                            
	$order->save();
	
	$result = array();
	
	$from =  explode('-', $this->post->interval);
	
// генерируем отправку
try { //содаем ттн
    $shipment = new MeestExpress_Shipment($api);

    $shipment->setOrderID($this->post->order_id);

    // отправитель
	//$hipment->setSender();


    // получатель
    $shipment->setReceiverName($this->post->name);
    $shipment->setReceiverPhone($this->post->telephone);
    $shipment->setReceiverService($this->post->type_id); // 1 - до дверей, 0 - до склада
    $shipment->setReceiverAddress($this->post->uuid_street, $this->post->uuid_branch, $this->post->house, $this->post->flat, $this->post->floor);



    // опции груза
    $shipment->setSendingFormat($this->post->box); // тип отправки: DOX - это конверт
	$shipment->setCod($this->post->r_summa); // сумма обратной доставки
    $shipment->setSendingInsurance($this->post->r_strach); // сумма страховки груза
    //$shipment->setSendingQuantity(1); // количество мест
    $shipment->setSendingWeight($this->post->massa); // вес

    $shipment->setNotation($this->post->r_koment);

    // желаемый адрес доставки
    $shipment->setDeliveryDate(date('d.m.Y', strtotime($this->post->date_go)));
	$shipment->setFormat($from[0],$from[1]);


    // регистрируем shipment
    // на выходе получим barcode - номер наклейки (нам с ним ничего делать не надо)
   $ttn = $api->createShipment($shipment);
   
   $order->setNakladna($ttn);
   $order->setDeliveryCost($this->recalculeyt($this->post->type_id, $this->post->uuid_branch, $or_m->getUuidCity(), $this->post->r_summa, $this->post->r_strach,$this->post->massa, $this->post->box));
	$order->save();
	
	$or_m->setStatus(1);
	$or_m->save();
	
    // отправляем
    // на выходе получим номер накладной
   // $deliveryNote = $api->createRegister($shipment);
	$result['ttn'] = $ttn;
	$result['error'] = 'TTН успешно создана! №: '.$ttn;
   // var_dump($deliveryNote);
} catch (Exception $e) {
    // если что-то будет не так - будет внятный exception
     $e->getCode();
    $e->getMessage();
    wsLog::add($e, 'MEEST');
	$result['ttn'] = 'ERROR';
	$result['error'] = $e->getMessage();
}

	 echo json_encode($result);
	die();
	}else if(@$this->post->method == 'add_register'){
	require_once('meestexpress/include.php');
	$api = new MeestExpress_API('red_ua','3#snlMO0sP','35a4863e-13bb-11e7-80fc-1c98ec135263');
	$mass = array();
	$shipment =  explode(', ', $this->post->mas);
	$ord = explode(',', $this->post->idm);

	try{
	$deliveryNote = $api->createRegister($shipment);
	$deliveryKur = $api->createPickUp($shipment);
	$mass = 'Реест  успешно создан! № Реестра: '.(string)$deliveryNote;
	$reg = new WsMeestRegister();
	$reg->setCtime(date('Y-m-d H:i:s'));
	$reg->setList($this->post->mas);
	$reg->setRegister($deliveryNote);
	$reg->setKuryer($deliveryKur);
	$reg->save();
	
	
	foreach ($ord as $c) {
	$or_m = new Shopordersmeestexpres($c);
	$or_m->setStatus(2);
	$or_m->save();
	}
	} catch(Exception $e){
	 $e->getCode();
    $e->getMessage();
    wsLog::add($e, 'MEEST');
	$mass = $e->getMessage();
	}
	
	//$mass = '233ac81c-0bfa-11e3-9df6-003048d2b473';// $shipment;
	echo json_encode($mass);
	die();
	
	}else{
	//$data=array();
	$data = 0;
	if(@$this->get->status_ttn){
	$data = $this->get->status_ttn;
	}
	$this->view->ttn = wsActiveRecord::useStatic('Shopordersmeestexpres')->findByQuery("SELECT `m`.`id` as `idt`, `m`.`status` AS  `status` ,  `m`.`order_id` AS  `order` ,  `m`.`ctime` AS  `ctime` ,  `o`.`id` AS  `id` ,  `o`.`status` AS  `statusor` , `o`.`nakladna` AS  `ttn` ,  `m`.`type_id` AS  `type` ,  `m`.`massa` AS  `massa` ,  `m`.`date_go` AS  `datego` ,  `m`.`gotime` AS  `gotime` 
FROM  `ws_order_meestexpres` AS  `m` 
INNER JOIN  `ws_orders` AS  `o` ON  `m`.`id` =  `o`.`meest_id` 
WHERE  `o`.`status` NOT 
IN ( 2, 17, 7 ) AND `o`.`delivery_type_id` = 9
AND  `m`.`status` = ".$data." order by `m`.`id` DESC");
	echo $this->render('meestexpress/list-meest.tpl.php');
	}
	}
	// перещет стоимости доставки курьерского заказа после оформления ттн
	public function recalculeyt($type, $b, $c, $cost, $strach, $massa, $paket){ 
	require_once('meestexpress/include.php');
	$api = new MeestExpress_API('red_ua','3#snlMO0sP','35a4863e-13bb-11e7-80fc-1c98ec135263');
	
	$calc = $api->calculateShipments($type, $b, $c, $cost, $strach, $massa, $paket);
	if($cost == 0){$proc = 0;}else{$proc = $cost * 0.02;}
		if($cost > 0 and $proc < 15){$proc = 15;}
		$proc = ceil($proc);
	$calc = $calc + (float)$proc;
	
	return (float)$calc;
	
	}
	
	//email(id_customer, subjekt, chislo, sms, email)
        /**
         * Отправка уведомления о смене депозита
         * @param type $id_customer - id пользователя
         * @param string $subject - тема
         * @param type $sum - сумма внесенного депозита
         * @param type $order - на основании заказа
         * @param type $flag - (false) "+" - зачисление, "-" - списание
         * @param type $email - (false) - уведомить на почту
         * @param SMSClub $sms - (false) - уведомить по смс
         * @return boolean
         */
	public function getSendEmail($id_customer, $subject = '',  $sum = 0, $order, $flag = false, $email = true, $sms = false){
	$sub = new Customer($id_customer);

	if ($sms) {
	$subject_sms = $subject .' : '. $flag.' '.$sum.' грн.';
                        $phone = Number::clearPhone($sub->getPhone1());
                        include_once('smsclub.class.php');
                        $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                        $sender = Config::findByCode('sms_alphaname')->getValue();
                        $user = $sms->sendSMS($sender, $phone, $subject_sms);
                        wsLog::add('SMS to user: ' . $sms->receiveSMS($user), 'SMS_' . $sms->receiveSMS($user));
        }
					
	if ($email) {
	
	if($flag == '+'){
	$dep = $sub->getDeposit() + $sum;
	}else if($flag == '-'){
	$dep = $sub->getDeposit() - $sum;
	}
	
	$subject_email = $subject.'  RED.UA';
	$subject .= ' : '.$flag.' '.$sum.' грн.';

	$text = '
		  <table border="0" cellpadding="5" cellspacing="0" width="700" align="center" style="background:#fff;border-collapse:collapse;">
		  <tr>
		  <td>
		  Здравствуйте, '.$sub->getFullName().'. Внесены изменения по Вашему депозиту: "'.$subject.'"
		</td>
		</tr>
		<tr>
		  <td>';
		  
		if($dep > 0){
                    $text .= 'В данный момент у Вас на депозите: '.$dep.' грн.</br>Воспользоватся своим депозитом Вы сможете при оформлении следующего заказа.</br>
		  Если Вы уже оформили заказ и хотите использовать свой депозит, свяжитесь с нашими менеджерами.';
                }
                
		$text .= '  </td>
		  </tr>
		</table>';
	

			if(isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){
							
			$this->view->content = $text;			
			$msg = $this->view->render('email/template.tpl.php');
EmailLog::add($subject_email, $msg, 'shop', $sub->id,  $order); //сохранение письма отправленного пользователю
			SendMail::getInstance()->sendEmail($sub->getEmail(), $sub->getFullname(), $subject_email, $msg);					
							wsLog::add('Email to user: ' . $sub->getEmail(), 'Email_' . $subject_email);
							
							}

                        }
						
						
						return false;
	
	}
	public function trekkoAction(){
	require_once('Trekko/Trekko.php');
	$api = new Trekko();
        
	if($this->get->metod == 'status'){
	$mmss = array(
0	=> 'Забор',
1	=> 'На станции',
2	=> 'На доставке',
3	=> 'Возврат отправителю',
4	=> 'Доставлен',
5	=> 'Выручка получена',
6	=> 'Выручка передана',
8	=> 'Возвращено',
9	=> 'Возвращено (Ч.В.)',
10	=> 'Частичный возврат',
11	=> 'Возврат (Б.О.)',
12	=> 'Возращено (Б.О.)',
	);
	$id = (int)$this->get->id;
	$result = $api->getStatusTrekko($id);
	//$res = $result->response->$id;
	die($mmss[$result->response->$id]);
	}elseif($this->get->prints){
	$orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in('.$this->get->prints.')'));
	/*foreach($orders as $order){
	OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Смена статуса', OrderHistory::getStatusText($order->getStatus(), 13));
	//$order->setStatus(13);
	//$order->setNakladna($order->getId());
	$order->setOrderGo(date('Y-m-d H:i:s'));
	$order->save();
	}*/
	$this->view->order = $orders;
	$this->view->name= $this->user->getMiddleName().' '.$this->user->getFirstName();
	echo $this->render('','trekko/print.tpl.php');
	die();
        
	}elseif($this->post->method == 'add_ttn'){

	$res = array();
	$array = array();
	$dt = date('d.m.Y', strtotime($this->post->delivery_date));
	$array = array('product_order' => $this->post->order, 'contact' => $this->post->name, 'tel'=>$this->post->phone,  'city'=>'Киев', 'adress'=>$this->post->address, 'time'=>$this->post->delivery_interval, 'delivery_date'=>$dt, 'naimenovanie' => 'Одежда',  'summa_oplaty'=>$this->post->amount , 'summa_strahovki'=>$this->post->strachovka, 'ves'=>$this->post->ves, 'mest'=>$this->post->mest, 'primechanie'=>$this->post->koment, 'id_service' => '1','id_status' => '1');
	$result = $api->getCreateOrder($array);
	if($result->success == 1){
	$res['success'] = $result->success;
	$res['response'] = $result->response;
	}else{
	$res['success'] = $result->success;
	$res['code'] = $result->code;
	$res['error'] = $result->error;
	}
	die(json_encode($res));
	
	}
        elseif($this->post->method == 'add_ttn_all'){
           // print_r($api->getTestTrekko());
           // die($api->getTestTrekko());
                $shipment =  explode(',', $this->post->id);
	$res = [];
	$mas_res_ok = [];
	$mas_res_off = [];
	$i=0;
            foreach($shipment as $or){
                $order = new Shoporders($or);
                    $dt = date('d.m.Y', strtotime($order->getDeliveryDate()));
                    $name = $order->getMiddleName().' '.$order->getName();
                    $adress = 'г.'.$order->getCity().', '.$order->getStreet().', д.'.$order->getHouse().', кв.'.$order->getFlat();
                    
	if($order->getPaymentMethodId() == 4 or $order->getPaymentMethodId() == 6 or $order->getPaymentMethodId() == 8){
            $summ = 0;
	}else{
            $summ = $order->getAmount();
	}
        
        $strach = ceil($order->getAmount()+$order->getDeposit());

	$res[] = [
            'product_order' => $order->getId(),
            'contact' => $name, 'tel'=>$order->getTelephone(),
            'city'=>'Киев',
            'adress'=>$adress,
            'time'=>$order->getDeliveryInterval(),
            'delivery_date'=>$dt,
            'naimenovanie' => 'Одежда',
            'summa_oplaty'=>$summ,
            'summa_strahovki'=>$strach,
            'ves'=>'1',
            'mest'=>'1',
            'primechanie'=>' ',
            'id_service' => '1',
            'id_status' => '1',
            'vybor' => '0'
            ];
	$i++;
	}

	$result = $api->getCreateMasOrder($res);
        
       // echo '<pre>';
      //  print_r($result);
      //  echo '</pre>';
        //die();
	//$cur = '';
	if($result->success == 1){
	$mas_res_ok = $result->response;
	$cur = $api->getLoadingTrekko(1);
	foreach($result->response as $resp){
	$ord = new Shoporders((int)$resp->order_id);
	OrderHistory::newHistory($this->user->getId(), $ord->getId(), 'Смена статуса', OrderHistory::getStatusText($ord->getStatus(), 13));
	$ord->setNakladna((int)$resp->id);
	$ord->setStatus(13);
	$ord->setOrderGo(date('Y-m-d H:i:s'));
        $time = time() - strtotime($ord->getDateCreate()); 
        $ord->setDayOrderGo($time);
	$ord->save();
	}
	}else{
        l($result);
	$mas_res_off[] =  array('success'=>$result->success, 'code'=>$result->code, 'error'=>$result->error);
	}
        
	die(json_encode(array('ok'=>$mas_res_ok, 'off'=>$mas_res_off, 'cur'=>$cur->success)));
	
        
        }elseif($this->get->edit)// открытие формы создания ттн
            {
	$this->view->id = $this->get->edit;
	$id = $this->get->edit;
	$this->view->order_trekko_edit = wsActiveRecord::useStatic('Shoporders')->findById($id);
	echo $this->render('trekko/add_order.tpl.php');
        
	}else{
	$orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('status in (9,15,16)', 'delivery_type_id'=>9, 'delivery_date != 0000-00-00', 'is_admin'=>0));
	$this->view->order_trekko = $orders;
		echo $this->render('trekko/list-trekko.tpl.php', 'index.php');
		}
	}
	  
	public function search_articulAction(){
	$mass = [];
        
	if($this->post->articul){
            $ostatok = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery("SELECT count, id_article, code, code_sr FROM ws_articles_sizes WHERE code LIKE '%".$this->post->articul."%' or code_sr LIKE '%".$this->post->articul."%' ")->at(0);
if($ostatok){
            $mass['ostatok'] = $ostatok->count;

	$add = wsActiveRecord::useStatic('Shoparticlelog')->findByQuery("SELECT sum(count) as addsum FROM  `red_article_log` WHERE type_id in(3,6) and `code` LIKE '%".$this->post->articul."'")->at(0)->addsum;
$mass['add'] = $add;

	$del = wsActiveRecord::useStatic('Shoparticlelog')->findByQuery("SELECT sum(count) as del FROM  `red_article_log` WHERE type_id in(2,7) and `code` LIKE '".$this->post->articul."'")->at(0)->del;
$mass['del'] = $del;

	$order = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT  sum(ws_order_articles.count) as sumaorder FROM ws_order_articles
	WHERE ws_order_articles.artikul LIKE  '".$this->post->articul."'")->at(0)->sumaorder;
$mass['order'] = $order;

$return = wsActiveRecord::useStatic('ShoporderarticlesVozrat')->findByQuery("SELECT  sum(ws_order_articles_vozrat.count) as sumreturn FROM ws_order_articles_vozrat
	WHERE ws_order_articles_vozrat.cod LIKE  '".$this->post->articul."'")->at(0)->sumreturn;
$mass['return'] = $return;



$img = wsActiveRecord::useStatic('Shoparticles')->findById($ostatok->id_article);

$mass['img'] = $img->getImagePath();
$mass['cat'] = $img->category->getRoutez();
$mass['id'] = $ostatok->id_article;
$mass['cup'] = $img->ArtycleBuyCount();
$mass['code'] = $ostatok->code;
$mass['sr'] = $ostatok->code_sr;
}else{
    $mass['error'] = $this->post->articul.' не нашлось(((';
}

    

	print json_encode($mass);
            die();
	//$this->view->article = $mass;
	}else{
	echo $this->render('page/search_articul.tpl.php', 'index.php');
	//$this->view->article = $mass;
	}
	}
	
	public function revisiyaAction(){
	$ms = [];
	$errors = [];
	if($this->post->method == 'dell'){ 
		$q = "TRUNCATE TABLE ws_revisiya";
       	wsActiveRecord::query($q);
		die(json_encode('База очищена!'));
	}elseif($this->post->method == 'start'){
	$from = (int)$this->post->from;
	$limit = (int)$this->post->limit;
	$i=0;
	$j = 0;
	$z = 0;
	$s = 0;
	$r_o = 0; 
        
	$sql = "SELECT ws_articles_sizes.* FROM ws_articles_sizes
                inner join ws_articles on ws_articles_sizes.id_article = ws_articles.id
                WHERE ws_articles.status != 1 and ws_articles_sizes.`ctime` !=  '0000-00-00 00:00:00' AND  ws_articles_sizes.`ctime` >  '2017-01-01 00:00:00'
                ORDER BY ws_articles_sizes.id DESC
	LIMIT ".$from.", ".$limit;
        
	$articles = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($sql);
		foreach ($articles as $siz){ 
		//d($siz->code, false);
		//set_time_limit(160);
		//ini_set('memory_limit', '1024M');
	$reviz = wsActiveRecord::useStatic('Revisiya')->findFirst(array('sr LIKE "'.$siz->code.'"'));
	//echo print_r($reviz);
	//die();
		if($reviz and $reviz->flag == 0){
		//die();
		$count = $siz->count;// ostatok
		
		$z = 0; //v rakazach
				
	$r = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery('
		SELECT SUM(`ws_order_articles`.`count`) as orc FROM `ws_order_articles`
		JOIN `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
		WHERE `ws_orders`.`status` = 100
		and `ws_order_articles`.`color` = '.$siz->id_color.' 
		and `ws_order_articles`.`size` = '.$siz->id_size.' 
		and `ws_order_articles`.`article_id` = '.$siz->id_article)->at(0)->getOrc();
		
											
		if($r) {$z = $r;}// to chto nashli v zakazach
		
		if($z > 0){  $count += $z; } // dobavlaem to chto nashli v zakazach
											
											
		if($count != $reviz->count){ //sravnivaem ostatok i reviziy
		
		$r_o += $z; //suma naidenich v zakach
												
		if($count > $reviz->count){ //ostatok bolshe chem reviziya 
				$co = $count - $reviz->count; 
				$f = 2; //udaleno
				$log_text = 'Ревизия '.date("d.m").' - Удалено';
				}else{
				$co = $reviz->count - $count;
				$f = 3; //dobavleno
				$log_text = 'Ревизия '.date("d.m").' - Добавлено'; 
				}

							$ms[$s]['id_article'] = $siz->id_article;
							$ms[$s]['code'] = $siz->code;
							$ms[$s]['count'] = $siz->count;
							$ms[$s]['count_r'] = $z;
							$ms[$s]['ct_r'] = $reviz->count;
							$ms[$s]['text'] = $log_text;
							$ms[$s]['ct_edit'] = $co;
							$ms[$s]['type'] = $f;
							
								$aa = $reviz->count - $z;
								if($aa < 0) {$aa = 0;}
								
								
							if(true){
							/*$param = [
                                                                'customer_id' => $this->user->id,
                                                                'username' => $this->user->username,
                                                                'article_id' => $siz->id_article,
                                                                'info' => $siz->size->getSize().' '.$siz->color->getName(),
                                                                'type_id' => $f,
                                                                'count' => $co,
                                                                'coments' => $log_text,
                                                                'code' => $siz->code
                                                            ];*/
									$log = new Shoparticlelog();
                                                                            $log->setCustomerId($this->user->getId());
                                                                            $log->setUsername($this->user->getUsername());
                                                                            $log->setArticleId($siz->id_article);
                                                                            $log->setInfo($siz->size->getSize().' '.$siz->color->getName());
                                                                            $log->setTypeId($f);
                                                                            $log->setCount($co);
                                                                            $log->setComents($log_text);
                                                                            $log->setCode($siz->code);
                                                                            $log->save();
				
										$siz->setCount($aa);
										$siz->save();
										
										}
							$i++;
							$s++;
							$siz->setFlag(1);//vneseny izmeneniya
							$siz->save();	
							}else{
							$siz->setFlag(2);//tovar sootvetstvuet
							$siz->save();	
							}		

								$reviz->setFlag(1);//tovar uge obrabotan
								$reviz->save();
								
												}elseif(@$reviz and $reviz->flag == 1){
												$j++;
												$siz->setFlag(5);// povtor
												$siz->save();
												}elseif($siz->count > 0){
											//	$siz->setCount(0);// net v 1C
												$siz->setFlag(3);// net v 1C
												$siz->save();
												}else{
												$siz->setFlag(4);//net tovara
												$siz->save();
												}
												
															}
															
die(json_encode(array('status' => 'send', 'from' => $from+$limit,  'saved'=>$i, 'coll'=>$j, 'order'=>$r_o, 'article' => $ms)));  
							///////////////////////////////
	}elseif($_POST and isset($_POST['save'])){
	 //$errors = $_FILES;
	if(isset($_FILES['exel'])){
	ini_set('memory_limit', '1024M');
     // set_time_limit(300);
                            if (is_uploaded_file($_FILES['exel']['tmp_name'])) {
                                $oldfilename_excel = $_FILES['exel']['tmp_name'];
                                $filename_excel = INPATH . "files/" . $_FILES['exel']['name'];
								
                                if (move_uploaded_file($oldfilename_excel, $filename_excel)) {
                                    
                                   $res = ParseExcel::getExcelToArray($filename_excel);
                                           
                                           
								   $i=0;
								   if($res){
								   foreach ($res as $s) {
										if($s[0]){
											$sr = new Revisiya();
											$sr->setSr($s[0]);
											$sr->setCount($s[1]);
											$sr->save();
												$i++;
												}
														}
												}
								   
								  $this->view->add_count = $i;
                                    unset($_POST['save']);
									 unset($_FILES['exel']);
								} else {
										$errors[] = $this->trans->get("Can not upload file");
										}
								}else{
									$errors[] = 'is_uploaded_file';
									}
							}else{
							$errors[] = 'Проблемы с файлом';
							}
							
		}
		
							
    if(count($errors) > 0) {$this->view->errors = $errors;}	
    
        echo $this->render('page/revisiya.tpl.php', 'index.php');
        
		}
							
 
	public function addttnorderAction(){
	if($this->post->ttn){
	require_once('np/NovaPoshta.php');
	$np = new NovaPoshta();
	$rr = '';
	$result = $np->getOrderNumber($this->post->ttn);
	if($result != 'error'){
	$order = new Shoporders((int)$result);
	$order->setNakladna($this->post->ttn); 
	$order->save();
	$rr =  $order->id;
	}else{
	$rr = $result;
	}
	print json_encode($rr);
    die();
	}else{
	echo $this->render('page/addttnorder.tpl.php');
	}
	}
	
	
	public function amazonAction(){
	$ms = array();
	$errors = array();
	//обновление корзины
	if($this->post->method == 'count_basket'){
	$sum=0; 
	$sum_price = 0;
	foreach($_SESSION['am_basket'] as $b){
	$sum+=$b['count'];
	$sum_price+=($b['count']*$b['price']);
	}
	die(json_encode(' В корзине '.$sum.' единиц на сумму '.$sum_price.' EU   '));
	}
	 //очистка таблицы с содержимым файла
	if($this->post->method == 'dell_base'){
		$q = "TRUNCATE TABLE ws_amazon";
		$w = "DELETE FROM `ws_amazon` WHERE `admin` =".$this->user->getId();
       	wsActiveRecord::query($w);
		 $this->_redirect('/admin/amazon');
		die(json_encode('База очищена!'));
	}
	//содержимое корзины
	if($this->post->method == 'view_basket'){
	$result = '';
	$result .='<div>';
	if($_SESSION['am_basket']){
	$sum=0;
	$sum_price =0;
	$result .='<table style="font-size: 84%;margin-bottom: 10px;" border="1"><tr><th>Фото</th><th>Название</th><th>Размер</th><th>Цвет</th><th>Количество</th><th>Цена</th><th>Удалить</th></tr>';
	//<th>Смотреть</th>
	foreach($_SESSION['am_basket'] as $key => $b){
	$result .='<tr><td><img class="prev" src="'.$b['img_sm'].'" rel="#m'.$key.'">
	<div class="simple_overlay" id="m'.$key.'" style="position: fixed;top: 10%;
    margin-left: 10%;"><img src="'.$b['img'].'" style="max-width:500px;"/></div>
	</td><td>'.$b['title'].'</td><td>'.$b['size'].'</td><td>'.$b['color'].'</td><td>'.$b['count'].'</td><td>'.$b['price'].'</td><td><img src="/img/del_basket_item.jpg" style="cursor: pointer;" name="'.$key.'" onclick="dell_basket(this);"></td></tr>';
	$sum+=$b['count'];
	$sum_price+=($b['count']*$b['price']);
	}
	$result .='<tr><td></td><td></td><td></td><td></td><th>'.$sum.'</th><th>'.$sum_price.'</th></tr>';

$result .='</table>';
$result.= '<input type="button" name="form_basket" id="form_basket" class="button" onclick="order_from_basket(this);"  value="Оформить заказ">';
$result.= '<script>$(document).ready(function () {
     $(".prev").hover(function () {
     $(this).parent().find("div.simple_overlay").show();
     }, function () {
     $(this).parent().find("div.simple_overlay").hide();
     });
		});
		 </script>';
	}else{
	$result .='<p>Корзина пуста</p>';
	}
	$result .='</div>';
	die(json_encode($result));
	}
	//удаление товара с корзины
	if($this->post->method == 'dell_basket'){
	unset ($_SESSION['am_basket'][$this->post->asin]);
	die(json_encode('ok'));
	}
	 //добавление товара в корзину
	if($this->post->method == 'add_basket'){
	if($_SESSION['am_basket']){
	if(array_key_exists($this->post->asin, $_SESSION['am_basket']) === true){
	die(json_encode('<p>В корзине уже присутствует этот товар</p>'));
	}
	}

	$_SESSION['am_basket'][$this->post->asin] = array('count'=>$this->post->cnt, 'price'=>$this->post->price, 'img'=>$this->post->img, 'img_sm'=>$this->post->img_sm, 'link'=>$this->post->link, 'size'=>$this->post->size, 'color'=>$this->post->color, 'title'=>$this->post->title );
	die(json_encode('В корзину добавлено '.$this->post->cnt.' единиц.'));
	}
	 //Создание заказа
	if($this->post->method == 'add_order'){
	$order = new Amazonorders();
	$order->setAdmin($this->user->getId());
	$order->setName($this->user->getMiddleName());
	$order->setCtime(date('Y-m-d H:i:s'));
	
	$order->save();
	
						
	$sum=0;
	$sum_price =0;
	foreach($_SESSION['am_basket'] as $key => $b){
	$or_art = new Amazonorderarticles();
	$or_art->setOrderId($order->getId());
	$or_art->setAsin($key);
	$or_art->setTitle($b['title']);
	$or_art->setCount($b['count']);
	$or_art->setPrice($b['price']);
	$or_art->setSize($b['size']);
	$or_art->setColor($b['color']);
	$or_art->setLink($b['link']);
	if($b['img']){$or_art->setImg($b['img']);}
	if($b['img_sm']){$or_art->setImgSm($b['img_sm']);}
	$or_art->save();
	$sum+=$b['count'];
	$sum_price+=($b['count']*$b['price']);
	}
	$order->setCount($sum);
	$order->setPrice($sum_price);
	$order->save();

			unset ($_SESSION['am_basket']);
			die(json_encode('Создан заказ № '.$order->getId()));
	}
	 //просмотр товара
	if($this->post->method == 'view'){
			require_once ('Amazon/AmazonAPI.php');
				require_once ('Amazon/AmazonUrlBuilder.php');
				
		$urlBuilder = new AmazonUrlBuilder('AKIAI6LYCSFCOWKAZOYQ', '8x5kBJytSq38Kszua/yj5N3Q9h1sDQ9PYR02mo47', 'AKIAI6LYCSFCOWKAZOYQ', 'de');
		$amazonAPI = new AmazonAPI($urlBuilder, 'simple');		
			sleep(1);
			$html = $amazonAPI->ItemLookUp($this->post->asin, true);
			$result ='';
			if($html){
$result.= '<div id="block_am">';
$result.= '<div id="img" class="amazon_img" >';
$result.= '<img name="img_a" class="img_a" src="'.$html[0]['largeImage'].'"> <img hidden class="img_sm" src="'.$html[0]['smallImage'].'">  '; 
$result.='</div>';
if($this->post->flag == 1){
$result.= '<div id="description_amazon" class="description_amazon">';//des
$result.= '<div class="title"><b>'.$html[0]['title'].'</b></div>';
$result.= '<div class="brand"><span>Бренд: </span><b>'.$html[0]['brand'].'</b></div>';
$result.= '<div class="size"><span>Размер: </span><b>'.$html[0]['size'].'</b></div>';
$result.= '<div class="color"><span>Цвет: </span><b>'.$html[0]['color'].'</b></div>';
$result.= '<div class="binding"><span>Материал: </span><b>'.$html[0]['binding'].'</b></div>';
$result.= '<div class="count"><span>Количество </span><select id="count_art" name="count_art" class="form-control input w100">';
for($i=1;$i<=$this->post->cnt; $i++){$result.= '<option value="'.$i.'">'.$i.'</option>';}
$result.= '</select></div>';
$result.= '<input type="hidden" name="price" id="price" value="'.$this->post->price.'">';
$result.= '<input type="hidden" name="link" id="link" value="'.$this->post->link.'">';
$result.= '<span>Цена <span id="show_price">'.$this->post->price.'</span> EU</span><br><br>';
$result.= '<input type="button" name="'.$this->post->asin.'"  class="btn btn-primary" onclick="add_basket(this);" value="Добавить в корзину"><br><br>';

$result.= '<ul>';
foreach($html[0]['feature'] as $f){
$result.='<li>'.$f.'</li>';
}
$result.= '</ul>';

$result.= '</div>';//des
}
$result.= '</div>';
}	
		die(json_encode($result));
	}
	//загрузка файта в базу
	if(isset($_POST['save'])){
	
	 //$errors = $_FILES;
	if(isset($_FILES['exel'])){
	
	ini_set('memory_limit', '1024M');
   //   set_time_limit(300);
                            if (is_uploaded_file($_FILES['exel']['tmp_name'])) {
                                $oldfilename_excel = $_FILES['exel']['tmp_name'];
                                $filename_excel = INPATH . "files/" . $_FILES['exel']['name'];
                                if (move_uploaded_file($oldfilename_excel, $filename_excel)) {
                                   $res = $this->parse_excel_file_amazon($filename_excel);
								  $this->view->add_count = $res;
                                    unset($_POST['save']);
									 unset($_FILES['exel']);
								} else {
										$errors[] = $this->trans->get("Can not upload file");
										}
								}else{
									$errors[] = 'is_uploaded_file';
									}
							}else{
							$errors[] = 'Проблемы с файлом';
							}
				//$this->view->article = wsActiveRecord::useStatic('Amazon')->findAll();			
		}
		
//выгрузка товаров с базы
$data = array('admin'=>$this->user->getId());
if($this->user->getId() == 1 or $this->user->getId() == 8005) {$data = array();}
	
$onPage = 100;
            $page = !empty($this->get->page) && (int)$this->get->page ? (int)$this->get->page : 1;
            $startElement = ($page - 1) * $onPage;
			//if(isset($_GET['go'])){
			$total = wsActiveRecord::useStatic('Amazon')->count($data);
			//}else{
           // $total = 1000;
			//}
            $this->view->totalPages = ceil($total / $onPage);
            $this->view->count = $total;
            $this->view->page = $page;
            $this->view->start = ($page == 1) ? 1 : $onPage * ($page - 1);
            $this->view->end = $onPage * ($page - 1) + $onPage;
$this->view->article = wsActiveRecord::useStatic('Amazon')->findAll($data, array('category'=>'ASC'), array($startElement, $onPage));
						
if(count($errors) > 0){ $this->view->errors = $errors;	}

echo $this->render('amazon/amazon.tpl.php');

}
        
	//чтение файла с амазона и загрузка в базу 
	public function parse_excel_file_amazon($file)
{
	$i=0;
	//$mass = array();
	 require_once('PHPExel/PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
      $aSheet = $objPHPExcel->getActiveSheet()->toArray();
	  
	  foreach ($aSheet as $s) {
	  if($s[0] and $i > 0){
	  $sr = new Amazon();
	  $sr->setAdmin($this->user->getId());
	  $sr->setGroup($s[0]);
	  $sr->setLink($s[1]);
	  $sr->setName($s[2]);
	  $sr->setCategory($s[3]);
	  $sr->setSubCategory($s[4]);
	  $sr->setEan($s[5]);
	  $sr->setAsin($s[6]);
	  $sr->setCondition($s[7]);
	  $sr->setQuantity($s[8]);
	  $sr->setWholesalePrice($s[9]);//10
	  $sr->setPrice($s[11]);//12
	  $sr->save();
	//  $mass[$i] = array('sr'=>$s[0], 'ct'=>$s[1]);

	  }
 $i++;
	  }
	   unlink($file);
	// die(print_r($mass));
	  return $i;
    }
	public function amazonordersAction()
	{
	if($this->post->method == 'dell'){
		
		$or_art = new Amazonorderarticles($this->post->id);
			$order = new Amazonorders($or_art->getOrderId());
		$or_art->destroy();
			$order->ReCalculate();
			
	die($this->post->id);
	
	}elseif($this->post->method == 'edit'){
	$or_art = new Amazonorderarticles($this->post->id);
	$or_art->setCount($this->post->count);
	$or_art->save();
	$order = new Amazonorders($or_art->getOrderId());
	$order->ReCalculate();
	die(json_encode(array('cnt'=>$or_art->getCount(), 'sum'=>$or_art->getCount()*$or_art->getPrice())));
	}elseif($this->post->method == 'ok'){
	$order = new Amazonorders($this->post->id);
	$order->setFlag(1);//odbrit
	$order->save();
	die(json_encode('Заказ одобрен!'));
	}
	if($this->post->method == 'off'){
	$order = new Amazonorders($this->post->id);
	$order->setFlag(2);// ne odbrit
	$order->save();
	die(json_encode('Заказ не одобрен!'));
	}
	if($this->post->method == 'order_go'){
	$order = new Amazonorders($this->post->id);

	$res = $this->ordergoamazon($order->getId());
	if($res){ 
	$t = 'Заказ отправлен!';
	$order->setOtpravka(1);//otpravlen
	$order->save();
	}else{
	$t = 'При отправке заказа возникли ошибки.<br>Заказ не отправлен!';
	}
	die(json_encode($t));
	}
	if($this->get->orderarticles){
	$this->view->order = wsActiveRecord::useStatic('Amazonorderarticles')->findAll(array('order_id'=>$this->get->orderarticles));
	echo $this->render('amazon/amazon_order.tpl.php');
	}else{
	$data = array('admin'=>$this->user->getId());
	if($this->user->getId() == 1 or $this->user->getId() == 8005) $data = array();
	$this->view->orders = wsActiveRecord::useStatic('Amazonorders')->findAll($data);
	echo $this->render('amazon/amazon_orders.tpl.php');
	}
	
	}
	public function ordergoamazon($order){// заказы в амазон
	$f = false;
	// Подключаем класс для работы с excel
				require_once('PHPExel/PHPExcel.php');
				//текущая дата
				$dd = date("d.m.Y");
                //задаем имя файла
				$name = 'order_red_ua_'.$order;
                //добавляем формат к имени файла
				$filename = $name . '.xls';
                // Создаем объект класса PHPExcel
				$pExcel = new PHPExcel();
                // Устанавливаем индекс активного листа
				$pExcel->setActiveSheetIndex(0);
                //устанавливаем размер шрифта
				$pExcel->getDefaultStyle()->getFont()->setSize(12);	
				//шрифт
				$pExcel->getDefaultStyle()->getFont()->setName('Arial');
				//
				$pExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$pExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				//Получаем активный лист
				$aSheet = $pExcel->getActiveSheet();
				//
				 $aSheet->getColumnDimension('A')->setWidth(8);
				 $aSheet->getColumnDimension('B')->setWidth(8);
				 $aSheet->getColumnDimension('C')->setWidth(14);
				 $aSheet->getColumnDimension('D')->setWidth(12);
				 $aSheet->getColumnDimension('E')->setWidth(10);
				 $aSheet->getColumnDimension('F')->setWidth(12);
				 $aSheet->getColumnDimension('G')->setWidth(8);
				//устанавливаем обтекаемость текста
				//$pExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
               
				// Подписываем лист
                $aSheet->setTitle($name);
				//устанавливаем ориентацию страницы
				$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
				//устанавливаем размер страницы
				$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
				// Вставляем текст в ячейку A1
				// Объединяем ячейки
				$aSheet->mergeCells('B1:F1');
				//
				$aSheet->getRowDimension(1)->setRowHeight(65);
				$imagePath = INPATH . 'backend/views/amazon/logo-red-amazon.png';
				if (file_exists($imagePath)) {
					$logo = new PHPExcel_Worksheet_Drawing();
					$logo->setPath($imagePath);
					$logo->setCoordinates('C1');				
					$logo->setOffsetX(80);
					$logo->setOffsetY(5);	
					//$aSheet->getRowDimension(2)->setRowHeight(60);
					$logo->setWorksheet($aSheet);
				}

				 //массив стилей 
				$boldFont = array(
                    'font' => array(
                        'bold' => true,//жырный
                    ),
					'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array (
											'rgb' => 'EEEEEE'
											)
								)
                );
				$border = array(
				'borders'=> array(
							'top' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('rgb' => '000000')
										),
							'right' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('rgb' => '000000')
										),
							'bottom' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('rgb' => '000000')
										),
							'left' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('rgb' => '000000')
										)
				)
				);
				$aSheet->setCellValue("B2", 'Заказ № '.$order.' от '.$dd);
				
					$aSheet->getStyle('B2')->getFont()->setBold(true);
					$aSheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				// Объединяем ячейки
					$aSheet->mergeCells('B2:F2');
				//устанавливаем заполнение
				//$aSheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				//$aSheet->getStyle('B2')->getFill()->getStartColor()->setRGB('EEEEEE');
				
				//
				$aSheet->setCellValue('B3', 'Ответственный:');
					$aSheet->getStyle('B3')->getFont()->setBold(true);
					$aSheet->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$aSheet->mergeCells('B3:C3');
				$aSheet->setCellValue('D3', $this->user->getFirstName().' '.$this->user->getMiddleName());
					$aSheet->getStyle('D3')->getFont()->setBold(true);
					$aSheet->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$aSheet->mergeCells('D3:F3');
					
				$aSheet->setCellValue('B4', '№');
					$aSheet->getStyle('B4')->applyFromArray($boldFont);
					
$aSheet->getStyle('B4')->getBorders()->getTop()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000')));
$aSheet->getStyle('B4')->getBorders()->getRight()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '000000')));
$aSheet->getStyle('B4')->getBorders()->getBottom()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000')));
$aSheet->getStyle('B4')->getBorders()->getLeft()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000')));

				$aSheet->setCellValue('C4', 'ASIN');
$aSheet->getStyle('C4')->getBorders()->getTop()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000')));
$aSheet->getStyle('C4')->getBorders()->getRight()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '000000')));
$aSheet->getStyle('C4')->getBorders()->getBottom()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000')));
$aSheet->getStyle('C4')->getBorders()->getLeft()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '000000')));
$aSheet->getStyle('C4')->applyFromArray($boldFont);
				$aSheet->setCellValue('D4', 'Количество');
$aSheet->getStyle('D4')->getBorders()->getTop()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000')));
$aSheet->getStyle('D4')->getBorders()->getRight()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '000000')));
$aSheet->getStyle('D4')->getBorders()->getBottom()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000')));
$aSheet->getStyle('D4')->getBorders()->getLeft()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '000000')));
					$aSheet->getStyle('D4')->applyFromArray($boldFont);
				$aSheet->setCellValue('E4', 'Цена');
$aSheet->getStyle('E4')->getBorders()->getTop()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000')));
$aSheet->getStyle('E4')->getBorders()->getRight()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '000000')));
$aSheet->getStyle('E4')->getBorders()->getBottom()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000')));
$aSheet->getStyle('E4')->getBorders()->getLeft()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '000000')));
					$aSheet->getStyle('E4')->applyFromArray($boldFont);
				$aSheet->setCellValue('F4', 'Сумма');
$aSheet->getStyle('F4')->getBorders()->getTop()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000')));
$aSheet->getStyle('F4')->getBorders()->getRight()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000')));
$aSheet->getStyle('F4')->getBorders()->getBottom()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000')));
$aSheet->getStyle('F4')->getBorders()->getLeft()->applyFromArray(array('style' =>PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '000000')));
					$aSheet->getStyle('F4')->applyFromArray($boldFont);
		

$j=1;			
	$i=5;		
	$sum=0;
	$sum_price =0;
	$or = wsActiveRecord::useStatic('Amazonorderarticles')->findAll(array('order_id'=>$order));
	foreach($or as $or_art){
	//$or_art->setOrderId($order->getId());
	//$or_art->setAsin($key);
	//$or_art->setCount($b[0]);
	//$or_art->setPrice($b[1]);
	//$or_art->save();
	$sum+=$or_art->getCount();
	$sum_price+=($or_art->getCount()*$or_art->getPrice());
	
	$aSheet->setCellValue('B' . $i, $j);
	$aSheet->getStyle('B'.$i)->applyFromArray($border);
	$aSheet->setCellValue('C' . $i, $or_art->getAsin());
	$aSheet->getStyle('C'.$i)->applyFromArray($border);
	$aSheet->setCellValue('D' . $i, $or_art->getCount());
	$aSheet->getStyle('D'.$i)->applyFromArray($border);
	$aSheet->setCellValue('E' . $i, $or_art->getPrice());
	$aSheet->getStyle('E'.$i)->applyFromArray($border);
	$aSheet->setCellValue('F' . $i, $or_art->getPrice() * $or_art->getCount());
	$aSheet->getStyle('F'.$i)->applyFromArray($border);

	$i++;
	$j++;
	}
		$aSheet->setCellValue('D' . $i, $sum);
	$aSheet->getStyle('D'.$i)->applyFromArray($border);
	$aSheet->setCellValue('F' . $i, $sum_price);
	$aSheet->getStyle('F'.$i)->applyFromArray($border);
	
	require_once("PHPExel/PHPExcel/Writer/Excel5.php");
		$objWriter = new PHPExcel_Writer_Excel5($pExcel);
				$path1file = INPATH . "backend/views/amazon/". $filename;
				if (file_exists($path1file)){
						if (unlink($path1file)) $objWriter->save($path1file);
						}else{
						$objWriter->save($path1file);
						}
						$email = 'php@red.ua';
						$email1 = 'a.holoshnaya@red.ua';
						$name = $this->user->getFirstName().' '.$this->user->getMiddleName();
						$admin_email = Config::findByCode('admin_email')->getValue();
                        $admin_name = 'RED.UA';
						$subject = 'Заказ Amazon за '.date("d.m.Y");
                        $this->view->name = $name;
                        $this->view->email = $email;
						$msg = 'Вы оформили заказ';//$this->view->render('trekko/kurer-email.tpl.php');

    MailerNew::getInstance()->sendToEmail($email, $admin_name, $subject, $msg, $new = 1, $from_email = $admin_email, $from_name = $admin_name, $smtp = 1, $usubscribe_text = 0, $subsciber = 0, $uploadfile = $path1file, $filename = $filename);	
					  
					MailerNew::getInstance()->sendToEmail($email1, $admin_name, $subject, $msg, $new = 1, $from_email = $admin_email, $from_name = $admin_name, $smtp = 1, $usubscribe_text = 0, $subsciber = 0, $uploadfile = $path1file, $filename = $filename);
					
					MailerNew::getInstance()->sendToEmail('management@red.ua', $admin_name, $subject, $msg, $new = 1, $from_email = $admin_email, $from_name = $admin_name, $smtp = 1, $usubscribe_text = 0, $subsciber = 0, $uploadfile = $path1file, $filename = $filename);
					
					$f = true;					
	
	return $f;
	
	}// заказы в амазон
	
	public function ukrpostAction(){
        require_once('up/UkrPostAPI.php');
        $this->view->up = $up =  new UkrPostAPI();
if($this->post->method == "trac"){
    $res = $up->getStatusTraking($this->post->barcode);
    $text =  '<table class="table" style="font-size:12px;">'
            . '<tr>'
            . '<thead>'
            . '<th>Дата</th>'
            . '<th>Індекс</th>'
            . '<th>Місце</th>'
            . '<th>Операція</th>'
            . '</thead>'
            . '</tr>';
    if($res){
    foreach ($res as $value) {
        $text.= '<tr>'
                . '<td>'.$value->date.'</td>'
                . '<td>'.$value->index.'</td>'
                . '<td>'.$value->name.'</td>'
                . '<td>'.$value->eventName.'</td>'
                . '</tr>';
    }
}
    $text .= '</table>';
    die(json_encode($text));
}elseif($this->cur_menu->getParameter() == 'ukrpost_transfer' and $this->get->id){
	$id = $this->get->id;
	$order = new Shoporders($id);
	if($order){
	
	$text = 'Доброго дня!<br>';
	$text.=$order->middle_name.' '.$order->name.', Вам відправлено поштовий переказ '.$this->get->summa.' грн. за повернення замовлення №'.$order->id.'<br>Місце отримання, поштове відділення Укр.Пошти: '.$this->get->index;
	$remark = new Shoporderremarks();
                        $data = [
                            'order_id' => $order->getId(),
                            'date_create' => date("Y-m-d H:i:s"),
                            'remark' => 'Почтовый перевод '.$this->get->summa.' грн.',
                            'name' => $this->user->getMiddleName()
                        ];
                        $remark->import($data);
                        $remark->save();
			EmailLog::add('Поштовий переказ за замовлення №'.$order->id,  $text, 'shop', $order->customer_id, $order->id); //сохранение письма отправленного пользователю			
	SendMail::getInstance()->sendEmail($order->email, $order->middle_name.' '.$order->name, 'Поштовий переказ за замовлення №'.$order->id, $text, '', '', 'return@red.ua', 'RED.UA', 2, 'return@red.ua', $this->user->getMiddleName());
	
}else{
$text = 'Помилка';
}
	die($text);
        }elseif($this->cur_menu->getParameter() == 'print'){
           
       if($this->get->sticker){
            switch ($this->get->sticker){
                case 'shipment':
                    $res =  $up->getSticker100_100($this->get->uuid);
                    header('Content-type: application/pdf');
                   // header('Content-Disposition: attachment; filename='.$this->get->uuid.'.pdf');
                    echo '<div style="padding: 10px;    margin: 10px;">'.$res.'</div>'; 
                   // exit();
                    break;
                case 'group':  
                    $res =  $up->getSticker100_100Group($this->get->uuid, $this->get->type);
                    header('Content-type: application/pdf'); 
                    echo $res;
                   // exit();
                    break;
                case 'form103': 
                    $res = $up->getForm103a($this->get->uuid); 
                    header('Content-type: application/pdf');
                    echo $res;
                    //exit(); 
                    break;
                default: $res = ['hnjgfh'];
            }
        }
            die();
            
        }elseif($this->cur_menu->getParameter() == 'new-group'){
            if(count($_POST)){
                $res = $up->getNewShipmentGroups($_POST['name']);
                if($res->uuid){
                     $this->_redir('ukrpost');
                }else{
                    $this->_redir('ukrpost');
                }
            }
        }elseif($this->cur_menu->getParameter() == 'edit-client'){
             $client = $up->getEditClient($this->post->uuid, $this->post->param);
            if($client->uuid){
                $mas = ['status'=>'ok', 'message'=>$client->name];
            }elseif($client){
                 $mas = ['status'=>'error', 'message'=>$client->message];
            }else{
                $mas = ['status'=>'error', 'message'=>'ошибка'];
            }
              die(json_encode($mas));
        }elseif($this->cur_menu->getParameter() == 'new-client'){
            
            if($this->post->method == 'put_address'){
                
             $res =  $up->putAddressClient($this->post->uuid, $this->post->ids);
             if($res->uuid){
                 die(json_encode(['status'=>'ok']));
                  //$this->_redir('ukrpost/new-shipment/id/'.$this->post->order.'/');
             }else{
                  die(json_encode(['status'=>'err',  'message'=>$res->message]));
             }
        }else{
         $client = $up->getNewClient($this->post->last_name, $this->post->first_name, $this->post->middle_name, $this->post->phone_number, $this->post->external_id, $this->post->postcode, $this->post->region, $this->post->city, $this->post->district, $this->post->street, $this->post->house_number, $this->post->apartment_number, $this->post->email);
            if($client->uuid){
                $mas = ['status'=>'ok', 'message'=>$client->name];
            }elseif($client){
                 $mas = ['status'=>'error', 'message'=>$client->message];
            }else{
                $mas = ['status'=>'error', 'message'=>'ошибка'];
            }
            die(json_encode($mas));
        }
        }elseif($this->cur_menu->getParameter() == 'new-address'){
            if ($this->get->query == 'postcode') {
              //  echo print_r($this->get);
                $res = $up->getPostIndexFilter($this->get->term);
            $mas = [];
            $i = 0;
            if(is_object($res->Entry)){
            $m = $res;
                }else{
            $m = $res->Entry;
            }
            foreach ($m as $item) { 
                $mas[$i]['label'] = $item->TECHINDEX.' '.$item->PO_LONG;
                $mas[$i]['value'] = $item->TECHINDEX;
                $mas[$i]['id'] = $item->TECHINDEX;
                $mas[$i]['REGION'] = $item->REGION_RU;
                $mas[$i]['DISTRICT'] = $item->DISTRICT_RU;
                $mas[$i]['CITY'] = $item->PDCITY_RU;
                $mas[$i]['CITYID'] = $item->POCITY_ID;
                
                $i++;
            }
            echo json_encode(array('query' => $this->get->query, 'suggestions' => $mas));
            die();
        }elseif($this->post->method == 'info_address'){
            //$res = $this->post->ids;
           $res = UkrPostAddress::getAddress($this->post->ids);
           $mas = [
               'postcode' => $res->postcode,
               'region' => $res->region,
               'district' => $res->district,
               'city' => $res->city,
               'street' => $res->street,
               'houseNumber' => $res->houseNumber,
               'apartmentNumber' => $res->apartmentNumber,
               'description' => $res->description
           ];
            die(json_encode($mas));
        }elseif(isset($this->post->save_address)){
           //  echo print_r($this->post);
            //die();
            $res = $up->newAddress($this->post->m_postcode, $this->post->m_region, $this->post->m_district, $this->post->m_city, $this->post->m_street, $this->post->m_houseNumber, '', $this->post->customer_id);
             if($res->id){
                  $res =  $up->putAddressClient($this->post->uuid, $res->id);
                  if($res->id){
                 $this->_redir('ukrpost/new-shipment/id/'.$this->post->m_order_id.'/');
                  }else{
                        $this->_redir('ukrpost/new-shipment/id/'.$this->post->m_order_id.'/');
                  }
             }else{
                 $this->_redir('ukrpost/new-address/');
             }
            
           // echo print_r($this->post);
          //  die();
        }
        
            
            
            echo $this->render('ukrpost/ukrpost_new_address.tpl.php', 'index.php');
        }elseif($this->cur_menu->getParameter() == 'new-shipment'){
          //  print_r($this->get);
           // die();
            $errors = [];
            if($this->post->method == 'delete_shipment'){
                $up->getDeleteShipments($this->post->uuid);
                die(json_encode(true));
            }elseif(count($_POST) and isset($_POST['add_ttn'])){
                $post = $_POST;
                $_POST = [];
         //  echo '<pre>';
          //  print_r($this->post);
         //   echo '</pre>';
           /// echo $this->get->id;
          //  $this->post = [];
           // die(); 
$s = $up->getShipmentInGroups(
        $post['shipmentGroupUuid'],
        $post['recipient_uuid'],
        $post['lastName'],
        $post['firstName'],
        $post['middleName'],
        $post['externalId'],
        $post['weight'],
        $post['length'],
        $post['declaredPrice'],
        $post['postPay'],
        $post['description'],
        $post['transferPostPayToBankAccount'],
        $post['paidByRecipient'],
        $post['postPayPaidByRecipient']
        );



if($s->uuid){
    $ord= new Shoporders((int)$this->get->id);
   // OrderHistory::newHistory($this->user->getId(), $ord->getId(), 'Смена статуса', OrderHistory::getStatusText($ord->getStatus(), 4));
	$ord->setNakladna($s->barcode);
	//$ord->setStatus(4);
	//$ord->setOrderGo(date('Y-m-d H:i:s'));
      //  $time = time() - strtotime($ord->getDateCreate()); 
                        //$day = $time / (24 * 60 * 60);
                           // if($day == 0 and $time > 0){ $day = 1;}
                     //       $ord->setDayOrderGo($time);

   
    $ord->save();
    
    //$this->_redir('ukrpost');
   $this->_redir('ukrpost/print/sticker/shipment/uuid/'.$s->uuid);
    
}elseif($s){
    
    
    //print_r($s);
    $errors = 'Помилка. '.$s->message;
     $order = new Shoporders((int)$post['externalId']);
     $this->view->order = $order;
                    $this->view->sender = Ukrpostkontragent::getSender(1); //$up->getClientExternalId(1);//
                    $this->view->client = $up->getClientExternalId($order->customer_id);//Ukrpostkontragent::getRecipient($order->customer_id); 
                    $this->view->sgroup = $up->getListGroups();
    
}else{
     $errors = 'Ошибка связи с сервером!!!!';
     $order = new Shoporders((int)$post['externalId']);
     $this->view->order = $order;
                    $this->view->sender = Ukrpostkontragent::getSender(1); //$up->getClientExternalId(1);//
                    $this->view->client = $up->getClientExternalId($order->customer_id);//Ukrpostkontragent::getRecipient($order->customer_id); 
                    $this->view->sgroup = $up->getListGroups();
}
       
            }elseif($this->get->id){
                $order = new Shoporders((int)$this->get->id);
                if($order->id){
                    $this->view->order = $order;
                    $this->view->sender = Ukrpostkontragent::getSender(1); //$up->getClientExternalId(1);//
                    $this->view->client = $up->getClientExternalId($order->customer_id);//Ukrpostkontragent::getRecipient($order->customer_id); 
                    $this->view->sgroup = $up->getListGroups();
                }else{
                    $errors = 'Заказ с № '.$this->get->id.' отсутствует!';
                }  
            }
            
            $this->view->error = $errors;
            
             echo $this->render('ukrpost/ukrpost_new_shipment.tpl.php', 'index.php');
        }else{
            if($this->get->group){
                $this->view->shipments = $up->getShipmentsGroup($this->get->group, ['status' => 'CREATED']);
            }
            $this->view->all_order = wsActiveRecord::useStatic('Shoporders')->findAll(array('status in(9,15,16)', 'delivery_type_id = 4', 'is_admin'=>0));
            
           echo $this->render('ukrpost/ukrpost.tpl.php', 'index.php'); 
        }
	
	
	
	}
	
	public function attributesAction(){
        $this->view->categories = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active in (1,2)'));
		
		if($this->get->atrib){
		$atributes = array();
		$atrib = wsActiveRecord::useStatic('Atributes')->findAll(array('id_cat'=>(int)$this->get->atrib));
		$i=0;
		foreach($atrib as $a){
		$atributes[$i]['id'] = $a->id;
		$atributes[$i]['name'] = $a->name;
		$i++;
		}
		
		die(json_encode(array('send'=>'ok', 'atribures'=>$atributes)));
		}elseif($this->get->param){
		$parametr = array();
		$param = wsActiveRecord::useStatic('AtributesParametr')->findAll(array('id_atrib'=>(int)$this->get->param));
		$i=0;
		foreach($param as $a){
		$parametr[$i]['id'] = $a->id;
		$parametr[$i]['text'] = $a->text;
		$i++;
		}
		
		die(json_encode(array('send'=>'ok', 'parametr'=>$parametr)));
		}
		
	
	echo $this->render('articles/attributes.tpl.php', 'index.php');
	
	}
	
  
public function sendMessageTelegram($chat_id, $message) {
   return false;
   
}

  


public function articlesaddAction(){

if($this->post->method == 'opisanie'){
$html = '';
$status = '';
$opis = wsActiveRecord::useStatic('Shoparticlesopis')->findByQueryArray("SELECT * FROM  `ws_articles_opis` where `cat` = ".(int)$this->post->category." or `cat` = 999 ORDER BY  `ws_articles_opis`.`sort` ASC ");
if($opis){
$status = 'ok';
                foreach($opis as $s){
                    $html.='
					<div class="input-group col-sm-12 col-md-12 col-lg-12 col-xl-12 mg-b-5" >
			<span class="input-group-addon bg-transparent">
			<label class="ckbox wd-16">
			<input type="checkbox" id="p_'.$s->id.'" class="long_checkbox"><span></span>
			</label>
			</span><span class="input-group-addon tx-size-sm lh-2">'.$s->name.'</span>
			'.$s->text.'
			</div>';
                }
				 $html.='<script>$( ".opis" ).click(function() {
var c = $(this).parent("div").find("input.long_checkbox")[0];
console.log(this);
$("#"+c.id).prop("checked", true);
});</script>';
				}else{
				$status = 'error';
				$html = 'Нет описания';
				}
                die(json_encode(array('status'=>$status, 'result'=>$html)));
}

		
		if($this->get->edit){//редактирование товара
		//die($this->get);
		$errors = [];
                 if($this->post->save){
                   //  l($this->post);
                        //   l($this->files);
                     $article = new Shoparticles((int)$this->get->edit);
                     $ar = (object)[];
                     $ar->category_id = $this->post->category;
                        setcookie("category", $this->post->category);
                        if($this->post->dop_cat_id) { $ar->dop_cat_id = $this->post->dop_cat_id;}
                        if($this->post->shop_id) { $ar->shop_id = $this->post->shop_id;}
                        $ar->size_type = $this->post->size_type;
			
                        if($article->brand_id != $this->post->brand){
                            $ar->brand_id = $this->post->brand;
                             $ar->brand = wsActiveRecord::useStatic('Brand')->findById((int)$this->post->brand)->name;
                            }
                            
			$ar->sezon = $this->post->sezon;
                        
			if($this->post->soot_rozmer){$ar->soot_rozmer = $this->post->soot_rozmer;}
                        
                       
                        $ar->model_id = $this->post->model_id;
                        setcookie("model_id",  $this->post->model_id);
                   
                        
                                       
                           
                          if(!$article->image){
                            // l($this->files['image_file']);
                             
                             $images = [];
                             foreach ($this->files['image_file']['name'] as $k => $name){
                                 $images[$this->files['image_file']['tmp_name'][$k]] = $name;
                             }
                             asort($images);
                           //  l($images);
                             // exit();
                          $c =  count($images);
                          if($c > 0){
                        //  $img =[];
                          $i = 0;
                        //  for($i = 0; $i < $c; $i++){
                          foreach($images as $oldfilename => $name){
                              if($i == 0){
                                //  $oldfilename = $this->files['image_file']['tmp_name'][$i];
                                  if (is_uploaded_file($oldfilename)) {
                                    $ext = pathinfo($oldfilename, PATHINFO_EXTENSION);
                                     if (!$ext) {
                                            $res = getimagesize($oldfilename);
                                            $ext = image_type_to_extension($res[2], false);
                                        }
                                        $mdfname = md5(uniqid(rand(), true));
                            $filename = INPATH . "files/org/{$mdfname}." . strtolower($ext);
                            if (move_uploaded_file($oldfilename, $filename)) {
                               $ar->image = pathinfo($filename, PATHINFO_BASENAME);
                                Mimeg::generateAllsizes($filename);
				
                            } else {
                                $errors[] = $this->trans->get("Can not upload file");
                            }
                            $i++;
                        }
                              }else{
                                //  $oldfilename = $this->files['image_file']['tmp_name'][$i];
                                  if (is_uploaded_file($oldfilename)) {
                                    $ext = pathinfo($oldfilename, PATHINFO_EXTENSION);
                                     if (!$ext) {
                                            $res = getimagesize($oldfilename);
                                            $ext = image_type_to_extension($res[2], false);
                                        }
                                        $mdfname = md5(uniqid(rand(), true));
                            $filename = INPATH . "files/org/{$mdfname}." . strtolower($ext);
                            if (move_uploaded_file($oldfilename, $filename)) {
                               $m = new Shoparticlesimage();
                                $m->setArticleId($article->id);
                                $m->setImage(pathinfo($filename, PATHINFO_BASENAME));
				$m->setTitle($article->getTitle());
                                $m->save();
                                Mimeg::generateAllsizes($filename);
				
                            } else {
                                $errors[] = $this->trans->get("Can not upload file");
                            }
                            $i++;
                        }
                       
                              }
                             // $i++;
                            
                          }     
                 }else{
	$errors[] = 'Пробоема с сагрузкой рисунков!';
	}
                 }
                
                 
                 if(count($this->post->sostav)){
                     $ss = [];
                     foreach ($this->post->sostav as $s){
                         
                        $ss[$s['name']] = $s['value'];
                     }
                    // l($ss);
                    // l((array)unserialize($article->sostav));
                     //exit();
                     
                     if(
                             count(array_diff($ss, (array)unserialize($article->sostav))) > 0
                             or
                             count(array_diff((array)unserialize($article->sostav), $ss)) > 0
                             ){
                     $ss = [];
                     $ss_uk =  [];
                     foreach ($this->post->sostav as $s){
                         
                        $ss[$s['name']] = $s['value'];
                        $ss_uk[$this->trans->translate($s['name'], 'ru', 'uk')] = $s['value'];
                     }
                 
                     
		$ar->sostav = serialize((object)$ss);
		$ar->sostav_uk = serialize((object)$ss_uk);
                }
	}else{
	$errors[] = 'Вы не ввели состав товара!';
	}
        if(count($this->post->opis)){
            if(count(array_diff($this->post->opis, (array)unserialize($article->long_text))) or count(array_diff((array)unserialize($article->long_text), $this->post->opis))){
            $opis = [];
            $opis_uk = [];
            foreach ($this->post->opis  as $k => $o){
                         
                        $opis[$k] = $o;
                        $opis_uk[$this->trans->translate($k, 'ru', 'uk')] = $this->trans->translate($o, 'ru', 'uk');
                     }
		$ar->long_text = serialize((object)$opis);
		$ar->long_text_uk = serialize((object)$opis_uk);
        }
	}else{
	$errors[] = 'Вы не ввели описание товара!';
	}
        $ar->status = 2;
               //  echo '---------------------------------';
                 //  l($ar); 
                  //  echo '---------------------------------';
        $article->import($ar);
        // l($article); 
        //   exit();         
                    if(!count($errors)){
	//$article->setStatus(2);
	//$article->save();
          $article->setPath(); // генерация url
											$log = new Shoparticlelog();
											$log->setCustomerId($this->user->getId());
											$log->setUsername($this->user->getUsername());
											$log->setArticleId($article->getId());
											$log->setComents('Добавлен товар');
											//$log->setInfo($s['size'].' '.$s['color']);
											$log->setTypeId(3);
											$log->setCount($article->getStock());
											//$log->setCode($s['code']); 
											$log->save();
        unset($_SESSION['astrafit']);
        $_SESSION['astrafit'] = $article->getId();
	$this->_redir('articles-add/listarticles/search/code/' . $article->getCode());
	}else{
	$this->view->errors = $errors;
	}
                      //  exit();
                       }
                       
                       
                       
		 if(false){
                      
                     setcookie("category", $this->post->category);
                    
                    
		
		 $article = new Shoparticles((int)$this->get->edit);
			
			$article->setCategoryId($this->post->category);
                        if($this->post->dop_cat_id) { $article->setDopCatId($this->post->dop_cat_id);}
                        if($this->post->shop_id) { $article->setShopId($this->post->shop_id);}
			$article->setSizeType($this->post->size_type);
                        if($article->brand_id != $this->post->brand){
                            $article->setBrandId($this->post->brand);
                             $article->setBrand(wsActiveRecord::useStatic('Brand')->findById((int)$this->post->brand)->name);
                            }
			$article->setSezon($this->post->sezon);
			if($this->post->soot_rozmer){$article->setSootRozmer($this->post->soot_rozmer);}
                        if($this->post->model_id){
                            $article->setModelId($this->post->model_id);
                             setcookie("model_id",  $this->post->model_id);
                        }
		
		if ($_FILES) {//загрузка фоток
                    if ($_FILES['image_file']) {
                        $mdfname = md5(uniqid(rand(), true));
                        if (is_uploaded_file($_FILES['image_file']['tmp_name'])) {
                            $ext = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
                            if (!$ext) {
                                $res = getimagesize(@$filename);
                                $ext = image_type_to_extension($res[2], false);
                            }
                            $oldfilename = $_FILES['image_file']['tmp_name'];
                            
                            $filename = INPATH . "files/org/{$mdfname}." . strtolower($ext);
                            
                            if (move_uploaded_file($oldfilename, $filename)) {
                                $filename_image = pathinfo($filename, PATHINFO_BASENAME);
                                $article->setImage($filename_image);

                                Mimeg::generateAllsizes($_SERVER['DOCUMENT_ROOT'] . '/files/org/' . $filename_image);
				
                            } else {
                                $errors[] = $this->trans->get("Can not upload file");
                            }
                        }
                    }

			foreach ($this->files as $key => $value) {
					// if($key == 'image_file') break;
					 $ms = explode('images_file', $key);
					// echo $key.'<br>';
					// print_r($ms);
                        if (count($ms) > 1) {
                            if ($_FILES['images_file' . $ms[1]] and strlen($_FILES['images_file' . $ms[1]]['tmp_name']) > 1) {
                                $mdfname = md5(uniqid(rand(), true));
                                if (is_uploaded_file($_FILES['images_file' . $ms[1]]['tmp_name'])) {
                                    $ext = pathinfo($_FILES['images_file' . $ms[1]]['name'], PATHINFO_EXTENSION);
                                    if (!$ext) {
                                        $res = getimagesize($filename);
                                        $ext = image_type_to_extension($res[2], false);
                                    }
                                    $oldfilename = $_FILES['images_file' . $ms[1]]['tmp_name'];
                                    $filename = INPATH . "files/org/{$mdfname}." . strtolower($ext);
                                    if (move_uploaded_file($oldfilename, $filename)) {

                                        $filename_image2 = pathinfo($filename, PATHINFO_BASENAME);
					$imageid = $ms[1];
                                $s = new Shoparticlesimage($imageid);
                                $s->setImage($filename_image2);
				$s->setTitle($article->getTitle());
                                $s->save();
				$path_to_file = $_SERVER['DOCUMENT_ROOT'] . '/files/org/' . $filename_image2;
                                Mimeg::generateAllsizes($path_to_file);
                                    } else {
                                        $errors[] = $this->trans->get("Can not upload file");
                                    }
                                }
                            }


                        }
						}
                }else{
	$errors[] = 'Пробоема с сагрузкой рисунков!';
	}
				
	if(isset($this->post->sostav) and $this->post->sostav != ''){
		$article->setSostav($this->post->sostav);
		$article->setSostavUk($this->trans->translate($this->post->sostav, 'ru', 'uk'));
	}else{
	$errors[] = 'Вы не ввели состав товара!';
	}
if(isset($this->post->long_text) and $this->post->long_text != ''){
		$article->setLongText($this->post->long_text);
		$article->setLongTextUk($this->trans->translate($this->post->long_text, 'ru', 'uk'));
	}else{
	$errors[] = 'Вы не ввели описание товара!';
	}	
	
	if ($this->post->ontop) {
       $top = wsActiveRecord::useStatic('Shoparticlestop')->findFirst(array('article_id' => $article->getId()));
                                if (!$top) {
                                    $top = new Shoparticlestop();
                                    $top->setArticleId($article->getId());
                                    $top->setType(1);
                                    $top->save();
                                }

                            }
	
	if(!count($errors)){
	$article->setStatus(2);
	//$article->save();
          $article->setPath(); // генерация url
											$log = new Shoparticlelog();
											$log->setCustomerId($this->user->getId());
											$log->setUsername($this->user->getUsername());
											$log->setArticleId($article->getId());
											$log->setComents('Добавлен товар');
											//$log->setInfo($s['size'].' '.$s['color']);
											$log->setTypeId(3);
											$log->setCount($article->getStock());
											//$log->setCode($s['code']); 
											$log->save();
        unset($_SESSION['astrafit']);
        $_SESSION['astrafit'] = $article->getId();
	$this->_redir('articles-add/listarticles/search/code/' . $article->getCode());
	}else{
	$this->view->errors = $errors;
	}
}
        
				
				//$this->view->sostav = wsActiveRecord::useStatic('Shoparticlessostav')->findByQueryArray("SELECT * FROM  `ws_articles_sostav`");
                                $ss = wsActiveRecord::findByQueryArray("SELECT distinct (nam) as nam FROM  `ws_articles_sostav`");
                                $sss = [];
                                foreach ($ss as $s){ $sss[] =  $s->nam; }
                                    $this->view->sost = json_encode($sss);
                                
				$this->view->sex = wsActiveRecord::useStatic('Shoparticlessex')->findAll();
				$this->view->sezon = wsActiveRecord::useStatic('Shoparticlessezon')->findAll();
				$this->view->categories = wsActiveRecord::useStatic('Shopcategories')->findAll(['active = 1 or active = 2', 'id not in(85, 106, 267)', 'parent_id not in(85)']);
                                $this->view->brands = wsActiveRecord::useStatic('Brand')->findAll(['hide'=>1]);
				$this->view->article = wsActiveRecord::useStatic('Shoparticles')->findById((int)$this->get->edit);  
                               // if(true){
                                echo $this->render('articles/articles-edit.tpl_2.php', 'index.php');
                               // }else{
				//echo $this->render('articles/articles-edit.tpl.php', 'index.php');
                               // }
                                
		
}elseif($this->get->loadexcel){//Загрузка excel файла
		//die($_FILES['excel_file']);
		if (isset($_FILES['excel_file'])) {
               // ini_set('memory_limit', '2048M');
               // set_time_limit(2800);                    
 if (is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
		$tmp_name_excel = $_FILES['excel_file']['tmp_name'];
		$name = $_FILES['excel_file']['name'];
		$nakladnaya = preg_replace('~[^0-9]+~','',substr($name, 0, strpos($name, ".")));
									
	
								if(isset($_POST['version'])){
								if($_POST['version'] == 1){ //старая версия
								
								$ifos = ParseExcel::getExcelArticlesStaraVersiya($tmp_name_excel);
									//echo '<pre>';
									//echo print_r($ifos);
									//echo '</pre>';
									//die();
                                    if ($ifos) {
									if(!count($ifos['error'])){
									unset($ifos['error']);
									foreach($ifos as $a){
									$art = new Shoparticles();
									if ($brand = wsActiveRecord::useStatic('Brand')->findFirst('name LIKE "' .$a['brand']. '"')) {
										$art->setBrandId($brand->getId());
										$art->setBrand($a['brand']);
									} else {
										$brand = new Brand();
										$brand->setName($a['brand']);
										$brand->save();
										$art->setBrandId($brand->getId());
										$art->setBrand($a['brand']);
									}
								$tmp = wsActiveRecord::useStatic('Shoparticles')->findLastSequenceRecord();
                                $art->setSequence($tmp->getSequence() + 10);
									
									$art->setColorId($a['color_id']); 
									$art->setModel($a['model']);
									$art->setModelUk($this->trans->translate($a['model'], 'ru', 'uk'));
                                    $art->setPrice($a['price']); 
									$art->setStock((int)$a['stock']);
									$art->setActive('n');
									$art->setCode($nakladnaya);
									$art->setMinPrice($a['cc']);
									$art->setMaxSkidka($a['skidka']);
									$art->setStatus(1);
									$art->save();
                                                                       //   $art->setPath(); // генерация url
									
									foreach($a['sizes'] as $s){
									$size = new Shoparticlessize();
									$size->setIdArticle($art->getId());
									$size->setIdSize((int)$s['id_size']);
									$size->setIdColor((int)$s['id_color']);
									$size->setCount((int)$s['count']);
                                                                        $size->setComing((int)$s['count']);
									$size->setCode($s['code']);
									//$size->setFlag(999);
									$size->save();

										$log = new Shoparticlelog();
											$log->setCustomerId($this->user->getId());
											$log->setUsername($this->user->getUsername());
											$log->setArticleId($art->getId());
											$log->setComents('Загружен размер');
											$log->setInfo($s['size'].' '.$s['color']);
											$log->setTypeId(1);
											$log->setCount((int)$s['count']);
											$log->setCode($s['code']); 
											$log->save();
									
									}
									}
									$this->_redirect('/admin/articles-add/listarticles/search/code/'.$nakladnaya);

									}else{
									$this->view->errors = $ifos['error'];
									echo $this->render('articles/articles-add.tpl_2.php', 'index.php');							
									}
									
									
                                    }else{
									die('Ошибка чтения excel файла!');
                                    }
								
								
	}elseif($_POST['version'] == 2){ //новая версия
	$ifos = ParseExcel::getExcelArticles($tmp_name_excel);
				//l($ifos);
         //  exit();					//echo '<pre>';
	if (!empty($ifos) && is_array($ifos) && count($ifos) > 0) {
          // l($ifos);
          // exit();
            $add = 0;
            $errors = [];
            foreach ($ifos as $a) {
                if(empty($a['errors'])){
                    $add++;
                   $art = new Shoparticles();
                                $b = $a['brand'];
                                    if ($brand = wsActiveRecord::useStatic('Brand')->findFirst('name LIKE "'.$b.'"')) {
										$art->setBrandId($brand->getId());
										$art->setBrand($a['brand']);
                                    } else {
										$brand = new Brand();
										$brand->setName($a['brand']);
										$brand->save();
										$art->setBrandId($brand->getId());
										$art->setBrand($a['brand']);
                                    }
			$tmp = wsActiveRecord::useStatic('Shoparticles')->findLastSequenceRecord();
                                                            $art->setSequence($tmp->getSequence() + 10);
									
									$art->setColorId($a['color_id']); 
									$art->setModelUk($a['model']);
                                                                         $model_uk = $this->trans->translate(mb_strtolower($a['model']), 'uk', 'ru');
                                                                            list($model_uk[0], $model_uk[1]) = mb_strtoupper($model_uk[0].$model_uk[1], 'UTF8'); 
									$art->setModel($model_uk);
                                                                       
                                                                        $art->setPrice($a['price']); 
									$art->setStock((int)$a['stock']);
									$art->setSezon($a['id_season']);
									$art->setSizeType($a['id_sex']);
									$art->setActive('n');
									$art->setCode($nakladnaya);
									$art->setMinPrice($a['cc']);
									$art->setMaxSkidka($a['skidka']);
									$art->setStatus(1);
                                                                        $art->setArtikul($a['artikul']);
                                                                        if($a['sostav']){
                                                                           // $ss = [];
                     $ss_uk =  [];
                     foreach ($a['sostav'] as $s_key => $s){
                         
                        $ss_uk[$this->trans->translate($s_key, 'ru', 'uk')] = $s;
                     }
                 
                     
		$art->setSostav(serialize((object)$a['sostav']));
		$art->setSostavUk(serialize((object)$ss_uk));
                                                                        }
                                                                        
									$art->save();
                                                                          //  $art->setPath(); // генерация url
                                                                        
									
									foreach($a['sizes'] as $s){
									$size = new Shoparticlessize();
									$size->setIdArticle($art->getId());
									$size->setIdSize((int)$s['id_size']);
									$size->setIdColor((int)$s['id_color']);
									$size->setCount((int)$s['count']);
                                                                        $size->setComing((int)$s['count']);
									$size->setCode($s['code']);
									//$size->setFlag(999);
									$size->save();

										$log = new Shoparticlelog();
											$log->setCustomerId($this->user->getId());
											$log->setUsername($this->user->getUsername());
											$log->setArticleId($art->getId());
											$log->setComents('Загружен размер');
											$log->setInfo($s['size'].' '.$s['color']);
											$log->setTypeId(1);
											$log->setCount((int)$s['count']);
											$log->setCode($s['code']); 
											$log->save();
									
									}
                }else{
                   $errors = array_merge($errors, $a['errors']);
                }
            }
            if(count($errors) > 0){
                $this->view->save = 'Добавлено ' . $add . ' товара';
                $this->view->errors = $errors;
		echo $this->render('articles/articles-add.tpl_2.php', 'index.php');
            }else{ 
            $this->_redirect('/admin/articles-add/listarticles/search/code/'.$nakladnaya);
            }
					
                                    }else{
									die('Ошибка чтения excel файла!');
                                    }
								}
									}else{
									$this->view->errors = array('Выберите тип накладной с которой хотите загрузить товар!');
									echo $this->render('articles/articles-add.tpl_2.php', 'index.php');			
									}
                            }else{
							die('Ошибка загрузки excel файла!');
							}
                        }else{
						die('not file');
						}
						
		}elseif($this->get->listarticles){
		$errors = [];
		$save = '';//array();
                
                if($this->get->artikul){
                  //   if($this->user->id == 8005){
                     //    l($this->get);
                     //}
                 //   
                     $sql = "SELECT `ws_articles`.`status` , `ws_articles`.`code` , `ws_articles`.`id`
FROM `ws_articles_sizes`
INNER JOIN `ws_articles` ON `ws_articles`.`id` = `ws_articles_sizes`.`id_article`
WHERE `ws_articles_sizes`.`code` LIKE '%{$this->get->artikul}%' ";
//l($sql);
                  $art = wsActiveRecord::findByQueryFirstArray($sql);
if($art){
if($art['status'] == 1){
    $this->_redirect($this->_path('articles-add/edit/' . $art['id'].'/'));
}elseif($art['status'] == 2){
    $errors[] = 'Товар по штрих-коду: '.$this->get->artikul.' уже добавлен!';
   // $this->view->save = $save;
}
}else{
    $errors[] = 'Штрих-код:'.$this->get->artikul.' отсутствует!';
  
}
$this->view->errors = $errors;

 
                   if($this->user->id == 8005){
                    //l($art);
                   // l($errors);
                   // exit();
               }
                }
		$data = [];
		$data["active"] = 'n';
		//if($this->post->status){
		//$data['status'] = $this->post->status;
		//}else{
		$data['status'] = 1;
		//}
		if($this->post->code or $this->get->code){
		$code = $this->post->code?$this->post->code:$this->get->code;
		$data[] = " `code` LIKE  '".$code."' ";
		}

		$articles = wsActiveRecord::useStatic('Shoparticles')->findAll($data, [], [0,200]);

		if($articles->count() == 0){
		if($code){
		$art_c = wsActiveRecord::useStatic('Shoparticles')->count(array(" `code` LIKE  '".$code."' and  status != 1"));
		}else{
		$art_c = 0;
		}
                
		if($art_c > 0){
                    
                    
		$save = 'Накладная №'.$code.' успешно добалена! Добавлено '.$art_c.' SKU.';
		$this->view->save = $save;
		}else{
		if($code){
		$errors[] = 'Накладная №'.$code.' отсутствует!';
		}else{
		$errors[] = 'За данным критерием ничего не найдено.';
		}
		
		}
		$this->view->errors = $errors;
		//$this->view->status = wsActiveRecord::useStatic('Shoparticlesstatus')->findAll();
		echo $this->render('articles/articles-add.tpl.php', 'index.php');
		//$this->_redirect('/admin/articles-add/');
		}else{
                    if($this->post->code){
                        $this->view->code = $this->post->code;
                    }
                    $this->view->errors = $errors;
                    $this->view->articles = $articles;
		echo $this->render('articles/articles-list.tpl.php', 'index.php');
		}
		}else{
		
                if(true){
                    echo $this->render('articles/articles-add.tpl_2.php', 'index.php');
                }else{
                    $this->view->status = wsActiveRecord::useStatic('Shoparticlesstatus')->findAll();
		echo $this->render('articles/articles-add.tpl.php', 'index.php');
                }
                
		}
                


}

    /**
     * Снятие товра
     */
	public function withdrawAction()
                {
	if(isset($_POST['close_article_count_snat'])){ //Форма снятия товара с продажи по акртикулу сколько снять.
			if (isset($_FILES['excel_file']) && is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
//die('tut');
                                $res = ParseExcel::getExcelToArray($_FILES['excel_file']['tmp_name']);

				if($res){
								 $ma_rez = [];
								 $i=0;
								 foreach($res as $r){
								 $a = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array(" code LIKE'".trim($r[0])."' and count >=".$r[1]));
								 if($a){ 
								 $temp = $a->count;
								 $a->setFlag($temp);
                                                                 $temp -= (int)$r[1];
								 $a->setCount($temp);
								 $a->save();
								
								$log = new Shoparticlelog();
								$log->setCustomerId($this->user->getId());
								$log->setUsername($this->user->getUsername());
								$log->setArticleId($a->id_article);
								$log->setComents('Снятие с продажи');
								$log->setInfo($a->size->size.' '.$a->color->name);
								$log->setTypeId(2);
								$log->setCount($r[1]);
								$log->setCode($r[0]); 
								$log->save();
											

								 $ma_rez[$i]['code'] = $r[0];
								 $ma_rez[$i]['snatye'] = $r[1];
								 $i++;
								 }
								 }
		if(count($ma_rez)){

                require_once('PHPExel/PHPExcel.php');
                $filename = 'Собрать_'.$this->user->getMiddleName().'_'.date("d.m.Y H-i").'.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('На снятие');

                $aSheet->getColumnDimension('A')->setWidth(15);
		$aSheet->getColumnDimension('B')->setWidth(10);

		$aSheet->setCellValue('A1', 'Артикул');
                $aSheet->setCellValue('B1', 'КоллСнять');
                $i = 2;
                foreach ($ma_rez as $t) { 
                        $aSheet->setCellValue('A'.$i, $t['code']);					//	№ п/п
                        $aSheet->setCellValue('B'.$i, $t['snatye']);			//	Номер заказа
                        $i++;
                }		
                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

		$pathfile = INPATH . "backend/excel/". $filename;
				
		if (file_exists($pathfile)){
			if (unlink($pathfile)) {$objWriter->save($pathfile);}
		}else{
			$objWriter->save($pathfile);
		}
						
                $email_copy = 'management@red.ua';
		$email_copy_name = 'Ирина';
		$subject = 'Снятие товара';
		$msg = 'Файл на снятие во вложении';
						
 SendMail::getInstance()->sendEmail('sku@red.ua', 'Анна', $subject, $msg, $pathfile, $filename, 'market@red.ua', 'RED',1, $email_copy, $email_copy_name);  
	}
								 }
									}else{
									die('Ошибка чтения excel файла!');
									}
                }elseif(isset($_POST['close_article'])){ // Форма снятия товара с продажи по артикулу c остатком на сайте
			if (isset($_FILES['excel_file']) && is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
//die('tut');
                         		
                                $tmp_name_excel = $_FILES['excel_file']['tmp_name'];
                                
                                $res = ParseExcel::getExcelToArray($tmp_name_excel);

								 if($res){
								 $ma_rez = [];
								 $i=0;
								 foreach($res as $r){
								 $a = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array(" code LIKE'".trim($r[0])."' and count >".$r[1]));
								 if($a){ 
								 $temp = $a->count;
								 $a->setFlag($temp);
								 $a->setCount((int)$r[1]);
								 $a->save();
								$temp -= (int)$r[1];
								$log = new Shoparticlelog();
											$log->setCustomerId($this->user->getId());
											$log->setUsername($this->user->getUsername());
											$log->setArticleId($a->id_article);
											$log->setComents('Снятие с продажи');
											$log->setInfo($a->size->size.' '.$a->color->name);
											$log->setTypeId(2);
											$log->setCount($temp);
											$log->setCode($r[0]); 
											$log->save();
											

								 $ma_rez[$i]['code'] = $r[0];
								 $ma_rez[$i]['snatye'] = $temp;
								 //echo $r[0].' - '.$a->count.'<br>';
								 $i++;
								 }
								 }
								 if(count($ma_rez)){

                require_once('PHPExel/PHPExcel.php');
                $filename = 'Собрать_'.$this->user->getMiddleName().'_'.date("d.m.Y H-i").'.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('На снятие');

                $aSheet->getColumnDimension('A')->setWidth(15);
		$aSheet->getColumnDimension('B')->setWidth(10);


		$aSheet->setCellValue('A1', 'Артикул');
                $aSheet->setCellValue('B1', 'КоллСнять');
                $i = 2;
                foreach ($ma_rez as $t) { 

                        $aSheet->setCellValue('A'.$i, $t['code']);		//	№ п/п
                        $aSheet->setCellValue('B'.$i, $t['snatye']);		//	Номер заказа
                        $i++;
                }
				
				
                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

				$pathfile = INPATH . "backend/excel/". $filename;
				
				if (file_exists($pathfile)){
						if (unlink($pathfile)) {$objWriter->save($pathfile);}
						}else{
						$objWriter->save($pathfile);
						}
						
                             
 $email_copy = 'management@red.ua';
		$email_copy_name = 'Ирина';
		$subject = 'Снятие товара';
		$msg = 'Файл на снятие во вложении';
						
 SendMail::getInstance()->sendEmail('sku@red.ua', 'Анна', $subject, $msg, $pathfile, $filename, 'market@red.ua', 'RED',1, $email_copy, $email_copy_name); 
								 }
								 }
									
									
		}else{
			die('Ошибка чтения excel файла!');
			}
                }elseif(isset($_POST['otbor_artikul'])){
                    
                    if (true) {

                                $res = wsActiveRecord::useStatic('Shoparticlessize')->findAll(["`code` LIKE  '%SR%'", "`count` > 0 "], ['id'=> 'ASC'], [0,100]);
                                if($res){
					$ma_rez = [];
					$i = 0;
					foreach($res as $a){
					//$a = wsActiveRecord::useStatic('Shoparticlessize')->findFirst([" code LIKE'".trim($r[0])."'"]);
						$log = new Shoparticlelog();
						$log->setCustomerId($this->user->getId());
						$log->setUsername($this->user->getUsername());
						$log->setArticleId($a->id_article);
						$log->setComents('Снятие на замену артикула');
						$log->setInfo($a->size->size.' '.$a->color->name);
						$log->setTypeId(2);
						$log->setCount($a->count);
						$log->setCode($a->code); 
						$log->save();

						$ma_rez[$i]['code'] = $a->code;
						$ma_rez[$i]['snatye'] = $a->count;
                                                
                                                $a->setFlag($a->count);
						$a->setCount(0);
						$a->save();
                                                
								 $i++;
                                                
						}
		if(count($ma_rez)){

                require_once('PHPExel/PHPExcel.php');
                $filename = 'zamena_artikula_'.$this->user->getMiddleName().'_'.date("d.m.Y H-i").'.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Zamena artikula');

                $aSheet->getColumnDimension('A')->setWidth(15);
		$aSheet->getColumnDimension('B')->setWidth(10);
                $i = 1;
                $count = 0;
                foreach ($ma_rez as $t) { 
                        $aSheet->setCellValue('A'.$i, $t['code']);	
                        $aSheet->setCellValue('B'.$i, $t['snatye']);	
                        $i++;
                        $count+=$t['snatye'];
                }
				
				
                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

               header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
                $objWriter->save('php://output');
						
						//$email = 'php@red.ua';
						//$name = 'Ярослав';
                                               // $admin_name = 'Ярослав';
						//$subject = 'Снятие товара на замену артикулов';
						//$msg = 'Файл на снятие во вложении';
						
      // MailerNew::getInstance()->sendToEmail('php@red.ua', $admin_name, $subject, $msg, 0, '', $admin_name, 1, 0, 0, $pathfile, $filename);
      // MailerNew::getInstance()->sendToEmail('sku@red.ua', $admin_name, $subject, $msg, 0, '', $admin_name, 1, 0, 0, $pathfile, $filename);
  $this->view->save = 'Снято  '. $count.' записей';
				}
                        }else{
                             $this->view->warning = 'Нет записей на снятие! ';
                        }             
                    }
                    
                }elseif(isset($_POST['edit_artikul'])){
                    if (isset($_FILES['excel_file_artikul'])) {
                            if (is_uploaded_file($_FILES['excel_file_artikul']['tmp_name'])) {
							
                                $tmp_name_excel = $_FILES['excel_file_artikul']['tmp_name'];
                                
                                $res = ParseExcel::getExcelToArray($tmp_name_excel);
                                
                                if($res){
                                    unset($res[0]); 
                                //echo '<pre>';
                               // echo print_r($res);
                              //  echo '</pre>';
                              // die();
                                $i = 0;
                                $j = 0;
                                $error = [];
                                foreach ($res as $key => $value) {
                                   // echo $key.' - '.$value[0].' - '.$value[1].'<br>';
                                     $a = wsActiveRecord::useStatic('Shoparticlessize')->findFirst([" code LIKE'".trim($value[24])."'"]);
                                     if($a){
                                        $log = new Shoparticlelog();
					$log->setCustomerId($this->user->getId());
					$log->setUsername($this->user->getUsername());
					$log->setArticleId($a->id_article);
					$log->setComents('Замена артикула');
					$log->setInfo('C '.$value[24].' на '.$value[25]);
					$log->setTypeId(1);
					$log->setCount($a->getFlag());
					$log->setCode($value[25]); 
					$log->save();
                                        
                                         $a->setCount((int)$value[20]);
                                         $a->setCode(trim($value[25]));
                                         $a->save();
                                         
                                        $i++;  
                                     }else{
                                         $error[] = $value[24];
                                     }
                                   $j++;
                                }
                                if($i != $j){
                                     $this->view->warning = 'Найдено '.$i.' записей с  '.$j;
                                      $this->view->error = 'Проблемы с  артикулами:'. implode(',', $error);
                                }else{
                                    $this->view->save = 'Найдено '.$i.' записей с  '.$j;
                                   
                                }
                                
                                   //  die();
                                }else{
                                    $this->view->error = 'В выбраном файле нет записей!';
                                }
                                
                                
                            }
                    }
                    
                }elseif(isset($_POST['snatie_to_id_article'])){
                     if (isset($_FILES['excel_file']) && is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
                            
				
                                $res = ParseExcel::getExcelToArray($_FILES['excel_file']['tmp_name']);
                               
                                
                                if ($res) {
                                     $i=0;
                                     $ma_rez = [];
                                    foreach ($res as $val) {
                                        if(!empty($val[0])){
                                $a = new Shoparticles((int)$val[0]);
				if($a->id){
                                    $er = false;
                                    foreach ($a->sizes as $s){
                                        if($s->count > 0){
                                            $temp = $s->count;
                                            $s->setSnyto($s->count);
                                            $s->setCount(0);
                                            $s->save();
                                            
                                            $log = new Shoparticlelog();
                                            $log->import([
                                                'customer_id'   => $this->user->getId(),
                                                'username'      => $this->user->getUsername(),
                                                'article_id'    => $a->id,
                                                'coments'       => 'Снятие с продажи',
                                                'info'          => $s->size->size.' '.$s->color->name,
                                                'type_id'       => 2,
                                                'count'         => $temp,
                                                'code'          => $s->code
                                                ]);
                                            $log->save();
					$ma_rez[$i]['code'] = $s->code;
					$ma_rez[$i]['snatye'] =$temp;
					$i++;
                                        $er = true;
                                        }
                                    }
                                    if($er){
                                        $a->setStock(0);
                                        $a->save();
                                        
                                    }
				}			 
                                        }
                                    }
        if(count($ma_rez)){

                require_once('PHPExel/PHPExcel.php');
                $filename = 'Собрать_'.$this->user->getMiddleName().'_'.date("d.m.Y H-i").'.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('На снятие');

                $aSheet->getColumnDimension('A')->setWidth(15);
		$aSheet->getColumnDimension('B')->setWidth(10);

		$aSheet->setCellValue('A1', 'Артикул');
                $aSheet->setCellValue('B1', 'КоллСнять');
                $i = 2;
                $snyto = 0;
                foreach ($ma_rez as $t) { 
                        $aSheet->setCellValue('A'.$i, $t['code']);					//	№ п/п
                        $aSheet->setCellValue('B'.$i, $t['snatye']);	
                        $snyto += $t['snatye'];//	Номер заказа
                        $i++;
                }		
                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

		$pathfile = INPATH . "backend/excel/". $filename;
				
		if (file_exists($pathfile)){
			if (unlink($pathfile)) {$objWriter->save($pathfile);}
		}else{
			$objWriter->save($pathfile);
		}
						
                $email_copy = 'management@red.ua';
		$email_copy_name = 'Ирина';
                // $email_copy = 'php@red.ua';
		$subject = 'Снятие товара';
		$msg = 'Файл на снятие во вложении';
						
 SendMail::getInstance()->sendEmail('sku@red.ua', 'Анна', $subject, $msg, $pathfile, $filename, 'market@red.ua', 'RED',1, $email_copy, $email_copy_name); 
  // SendMail::getInstance()->sendEmail('php@red.ua', 'Анна', $subject, $msg, $pathfile, $filename, 'market@red.ua', 'RED',1, $email_copy, $email_copy_name); 
$this->view->save =  'Снято '. $snyto . ' шт.';
	}    
                                    
                            }
                     }
                    
                }
              //  $this->view->article = Shoparticlessize::findByQueryFirstArray("SELECT COUNT(  `id` ) AS st, SUM( `count`) AS sumaFROM  `ws_articles_sizes` WHERE  `count` >0 AND  `code` LIKE  'SR%'");
									
			//$this->view->status = wsActiveRecord::useStatic('Shoparticlesstatus')->findAll();
			echo $this->render('slugebnoe/withdraw.tpl.php', 'index.php');
	}
	/**
         * отправка письма
         * указывается тема, содержимое 
         */
   	public function emailAction(){
	
	
	//if(isset($this->post->send)){
	//echo print_r($this->post);
	//die();
	//}
	
	if(isset($this->post->send)){
	$email = 'php@red.ua'; 
	$name = 'Романчук Ярослав';
	$subject = 'Новое висьмо';
		if($this->post->subject) {$subject = $this->post->subject;}
		
	$msg = 'текст';
		if($this->post->message) {$msg = $this->post->message;}
	
	$uploadfile = '';
	$filename = '';
	if (isset($_FILES['file'])) {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $uploadfile = $_FILES['file']['tmp_name'];
            $filename = $_FILES['file']['name'];
		}
		}

        SendMail::getInstance()->sendEmail($email, $name, $subject, $msg, $uploadfile, $filename, 'php@red.ua', 'Романчук');                      
	//SendMail::getInstance()->sendEmail($email, $name, $subject, $msg, $uploadfile, $filename, 'oba@red.ua', 'Баранецкая Олеся');
	}//else{
	//$res = SendMail::getInstance()->getMailList();
	//echo $res;
	//die();
	//}
	echo $this->render('page/email_page.tpl.php', 'index.php');
	
	}
	
	/**
         * Акции с использованием option_id
         */
	public function discountsAction()
	{
            /**
             * История покупок по акции
             */
            if($this->get->method == 'result'){
                $sql = "SELECT * , SUM( `count` ) AS ctn
FROM `ws_order_articles`
WHERE `count` >0
AND `option_id` ={$this->get->id}
GROUP BY `article_id`
ORDER BY `ws_order_articles`.`title` ASC";
$query = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($sql);
                
              //  $query = wsActiveRecord::useStatic('Shoporderarticles')->findAll(['option_id' => $this->get->id, 'count > 0']);
                
               /* $query = Shoporderarticles::findByQueryFirstArray("
                    SELECT 
                        SUM( IF(  `count` = 0, 1,  `count` ) ) AS  `all`,
                        SUM( IF(  `count` = 0,  `option_price` , (`option_price` *  `count`) ) ) AS  `summa_all` ,
                        SUM( IF(  `count` = 0,  `price` , (`price` *  `count`) ) ) AS  `summa_all_no_akciya` ,
                        SUM(  `count` ) AS  `fact` ,
                        SUM(  `option_price` *  `count` ) AS  `summa_fact`,
                        SUM(  `price` *  `count` ) AS  `summa_fact_no_akciya`
                        FROM  `ws_order_articles` 
                            WHERE  `option_id` =".$this->get->id);
                */

                $this->view->message = $query;
                $this->view->id = $this->get->id;
                $rezult = $this->view->render('discounts/resultat.php');
                die($rezult);
            }elseif($this->get->to_excel){
              
                
                    $mass = [];
                    $query = Shoporderarticles::findByQueryFirstArray("
                    SELECT 
                        SUM( IF(  `count` =0, 1,  `count` ) ) AS  `all` ,
                        SUM( IF(  `count` =0,  `option_price` , (`option_price` *  `count`) ) ) AS  `summa_all` ,
                        SUM( IF(  `count` =0,  `price` , (`price` *  `count`) ) ) AS  `summa_all_no_akciya` ,
                        SUM(  `count` ) AS  `fact` ,
                        SUM(  `option_price` *  `count` ) AS  `summa_fact`,
                        SUM(  `price` *  `count` ) AS  `summa_fact_no_akciya`
                        FROM  `ws_order_articles` 
                            WHERE  `option_id` = ".$this->get->to_excel);
                    
                    $mass['header'][0][0] =  'Заказано';
                    $mass['header'][0][1] =  'Сумма без акции';
                    $mass['header'][0][2] =  'Сумма по акции';
                    $mass['header'][0][3] =  'Разница';
                    $mass['header'][0][4] =  'Выкуплено';
                    $mass['header'][0][5] =  'Сумма без акции';
                    $mass['header'][0][6] =  'Сумма по акции';
                    $mass['header'][0][7] =  'Разница';
                    
                    $mass['data'][1][0] =  $query['all'];
                    $mass['data'][1][2] =  $query['summa_all'];
                    $mass['data'][1][1] =  $query['summa_all_no_akciya'];
                    $mass['data'][1][3] =  $query['summa_all_no_akciya']-$query['summa_all'];
                    $mass['data'][1][4] =  $query['fact'];
                    $mass['data'][1][6] =  $query['summa_fact'];
                    $mass['data'][1][5] =  $query['summa_fact_no_akciya'];
                    $mass['data'][1][7] =  $query['summa_fact_no_akciya']-$query['summa_fact'];
                    
                   // foreach ($query as $k => $value) {
                        //   $mass[1][] =  $value;
                   // }
                    //$res = $mass;
                ParseExcel::saveToExcel('akciya', [0 => $mass]);

               // echo json_encode($res);
                die();
            
            }elseif($this->get->method == 'history'){
                $query = wsActiveRecord::useStatic('Shoparticlesoption')->findById((int)$this->get->id);
                if($query->id){
                $this->view->history = $query->log;
                }
               // $this->view->id = $this->get->id;
                $rezult = $this->view->render('discounts/history.php');
                die($rezult);
            }
         /**
          * редактирование акции и добавление содержимого
          */  
	if($this->get->edit)
            {
            $errors = [];
            if($this->post->dell){
                $op = new Shoparticlesoptions($this->post->id);
                $op->destroy();  
            }elseif($this->post->save){
              
                
                if ((!empty($this->post->article_id) or isset($_FILES['excel_file'])) and empty($this->post->category_id) and empty($this->post->brand_id) and empty($this->post->sezon_id)) {
                       //add articles
                    foreach (Shoparticlesoptions::find('Shoparticlesoptions', ['option_id'=>(int)$this->post->option_id]) as $v) {
                        if($v->category_id){
                           $errors[] = 'В этой акции участвует категория, добавление товара не возможно!';  
                        }elseif($v->brand_id){  
                            $errors[] = 'В этой акции участвует бренд, добавление товара не возможно!';  
                        }elseif($v->sezon_id){
                             $errors[] = 'В этой акции участвует сезон, добавление категории не возможно!'; 
                        }
                    }
                    
                           if(!count($errors)){
                              // echo print_r('$errors');
              //die();
                               if (isset($_FILES['excel_file']) and $_FILES['excel_file']['name']) {
                                  // echo print_r($_FILES['excel_file']);
                                  // die();
                                    if (is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
                                            $tmp_name_excel = $_FILES['excel_file']['tmp_name'];
                                           $array =  ParseExcel::getExcelToArray($tmp_name_excel);
                                          // echo '<pre>';
                                          // echo print_r($array);
                                           // echo '</pre>';
                                          // die();
                                           if(count($array)){
                                               foreach ($array as $d => $v){
                                                  // echo (int)$v['0'],'<br>';
                                                        $o = new Shoparticlesoptions();
                                                        $o->setOptionId($this->post->option_id);
                                                        $o->setArticleId((int)$v['0']);
                                                        $o->save(); 
                                                }   
                                           }else{
                                               $errors[] = 'Ошибка Excel файла!';
                                           }
                                           unset($_FILES['excel_file']);
                                    }
                               }else{
                                   // echo print_r($this->post->article_id);
            //  die();
                               $art = explode(',', $this->post->article_id);
                               foreach ($art as $d => $v){
                                    $o = new Shoparticlesoptions();
                                    $o->setOptionId($this->post->option_id);
                                    $o->setArticleId($v);
                                    $o->save(); 
                                    }
                               }
                                
                           
                           }else{
                               $this->view->errors = $errors;
                           }
                } elseif(empty($this->post->article_id) and !empty($this->post->category_id) and empty($this->post->brand_id) and empty($this->post->sezon_id)){
                    //add category
                    foreach (Shoparticlesoptions::find('Shoparticlesoptions', ['option_id'=>$this->post->option_id]) as $v) {
                        if($v->article_id){
                           $errors[] = 'В этой акции участвует товар, добавление категории не возможно!';  
                        }elseif($v->brand_id){  
                            $errors[] = 'В этой акции участвует бренд, добавление категории не возможно!';  
                        }elseif($v->sezon_id){
                             $errors[] = 'В этой акции участвует сезон, добавление категории не возможно!'; 
                        }
                    }
                    if(count($errors) == 0){
                       // echo print_r($_POST);
                        //$cat_id = explode(',', $this->post->category_id);
                       // echo print_r($this->post->category_id);
                        //die();
                        foreach ($this->post->category_id as $value) {
                             $cat = new Shopcategories($value);
                        if($cat and count($cat->getKidsIds()) > 1){
                            foreach ($cat->getKidsIds() as  $val) {
                                $o = new Shoparticlesoptions();
                                $o->setOptionId($this->post->option_id);
                                $o->setCategoryId($val);
                                $o->save(); 
                            }
                        }else{
                          $o = new Shoparticlesoptions();
                                $o->setOptionId($this->post->option_id);
                                $o->setCategoryId($value);
                                $o->save(); 
                            
                        }
                        }
                       
                           
                                
                           
                    }
                    
                }elseif(empty($this->post->article_id) and empty($this->post->category_id) and !empty($this->post->brand_id) and empty($this->post->sezon_id)){
                     foreach (Shoparticlesoptions::find('Shoparticlesoptions', ['option_id'=>$this->post->option_id]) as $v) {
                        if($v->article_id){
                           $errors[] = 'В этой акции участвует товар, добавление бренда не возможно!';  
                        }elseif($v->category_id){  
                            $errors[] = 'В этой акции участвует категория, добавление бренда не возможно!';  
                        }elseif($v->sezon_id){
                             $errors[] = 'В этой акции участвует сезон, добавление категории не возможно!'; 
                        }
                    }
                    if(count($errors) == 0){
                           
                                $o = new Shoparticlesoptions();
                                $o->setOptionId($this->post->option_id);
                                $o->setBrandId($this->post->brand_id);
                                $o->save();
                           
                    }
                }elseif(empty($this->post->article_id) and empty($this->post->category_id) and empty($this->post->brand_id) and !empty($this->post->sezon_id)){
                    foreach (Shoparticlesoptions::find('Shoparticlesoptions', ['option_id'=>$this->post->option_id]) as $v) {
                        if($v->article_id){
                           $errors[] = 'В этой акции участвует товар, добавление бренда не возможно!';  
                          }elseif($v->category_id){ 
                            $errors[] = 'В этой акции участвует категория, добавление бренда не возможно!';  
                        }elseif($v->brand_id){  
                            $errors[] = 'В этой акции участвует бренд, добавление категории не возможно!';  
                        }
                    }
                    if(count($errors) == 0){
                           
                                $o = new Shoparticlesoptions();
                                $o->setOptionId($this->post->option_id);
                                $o->setSezonId($this->post->sezon_id);
                                $o->save();
                           
                    }
                }else{
                    $errors[] = 'В акцию можно добавлять по одному критерию: товар, категорию или бренд!';
                    
                }

            }elseif(isset($this->post->save_cat)){
               
                 $a = new Shoparticlesoption($this->post->id);
                 if($a){
                        $a->import($this->post);                       
                            if(!isset($this->post->status)){
                                $a->setStatus(0);
                            }
                            if(!isset($this->post->timer)){
                                $a->setTimer(0);
                            }
                        $a->save();
                 } 
                
            }
            if(count($errors) > 0){ $this->view->errors = $errors;}
	//var_dump($this->get);
	$discounts = wsActiveRecord::useStatic('Shoparticlesoption')->findById((int)$this->get->edit);
	//var_dump($discounts);
	$this->view->discounts = $discounts;
	
	$options = wsActiveRecord::useStatic('Shoparticlesoptions')->findByOptionId($discounts->id);
	//var_dump($options);
	$this->view->options = $options;
	
	echo $this->render('discounts/discounts-edit.tpl.php');
	}else{
            /**
             * Создание акции
             */
            if($this->post and isset($this->post->add)){
                $a = new Shoparticlesoption();
                $a->import($this->post);
                if(!isset($this->post->status)){ $a->setStatus(0);}
                $a->save();
            }elseif($this->post and isset($this->post->dell_akciya)){
                
                wsActiveRecord::query("DELETE FROM `ws_articles_options` WHERE `option_id` = ".$this->post->id);
                 wsActiveRecord::query("DELETE FROM `ws_articles_option` WHERE `id` = ".$this->post->id); 
            }
            /**
             * выборка всех акций
             */
	$this->view->discounts = wsActiveRecord::useStatic('Shoparticlesoption')->findAll();
	/**
             * отображение всех акций
             */
	echo $this->render('discounts/discounts.tpl.php');
	}
    }
        
  /**
   * Загрузка файлов на сервер(фотки, документы и другое)
   */      
public function dialogAction()
	{
		
		echo $this->render('dialog/index.php', 'index.php');
	}
        
public function articlemodelsAction()
	{ 

    if($this->get->new){
        
         $model = new Shoparticlesmodel();
        // var_dump($_POST);
        if(isset($_POST['save'])){
           $model->import($_POST);
           $model->save();
           $this->_redir('articlemodels');
        }else{
            //$this->view->error = 'Ошибка ввода даных';
            
        }
           echo $this->render('model_articles/article_models_new.php', 'index.php');
    }elseif($this->get->edit){
       // var_dump($this->get);
       $model = new Shoparticlesmodel((int)$this->get->edit);
        
        if($_POST){
            $model->import($_POST);
           $model->save();
        }
        $this->view->model = $model;
        echo $this->render('model_articles/article_models_edit.php', 'index.php');
    }else{

       $this->view->models = wsActiveRecord::useStatic('Shoparticlesmodel')->findAll();
            
	echo $this->render('model_articles/article_models_list.php', 'index.php');
    }
	}
        public function storesinfoAction(){
            
            if ($this->cur_menu->getParameter() == 'delete') {
            if ($this->get->id) {
                $info = new Stores((int)$this->get->id);
                if ($info->getId()){$info->destroy();}
            }
            $this->_redir('stores-info');
        } elseif($this->cur_menu->getParameter() == 'edit'){
             $info = new Stores((int)$this->get->id);
             
             if(count($_POST)){
                 if(!isset($_POST['active'])){
                     $_POST['active'] = 0;
                 }
                 $_POST['type'] = 'info';
                 if ($_FILES['src']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['src'], 'ru_RU');
                        $folder = '/storage/images/RED_ua/stores/info/';
                        if ($handle->uploaded) {;
                            if ($handle->image_src_x != $handle->image_src_y) {
                                  $errors = ['Картинка должна быть квадратной. Сейчас: '.$handle->image_src_x.'x'.$handle->image_src_y];
                            }
                            if (!count($errors)) {
                                $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                                if ($handle->processed) {
                                    if ($info->getSrc()){ unlink($_SERVER['DOCUMENT_ROOT'].$info->getSrc()); }
                                    //$info->setSrc($folder . $handle->file_dst_name);
                                    $_POST['src'] = $folder . $handle->file_dst_name;
                                    $handle->clean();
                                }
                            }else{
                               $this->view->error =  $errors;
                            }
                        }
                    }
                    $info->import($_POST);
                 $info->save();
                 if(!$this->get->id) {
                     $this->_redir('stores-info/edit/id/'.$info->id);
                     }
             }
              $this->view->info = $info;
                echo $this->render('stores/info_edit.php', 'index.php');
            }else{
              $this->view->info =  $info = Stores::getInfo();
            
           echo $this->render('stores/info_list.php', 'index.php');
            }
        }
         public function storesakciyaAction(){
             if($this->post->metod == 'sort'){
                // $ids = implode(',', $this->post->id);
                 $ids = array_flip($this->post->id);
                 foreach ($ids as $key => $id) {
                      $akciya = new Stores((int)$key);
                      $akciya->setSort($id);
                      $akciya->save();
                     
                 }
                 //$mas_id = array_flip($this->post->id);
                // echo print_r($mas_id);
                 die();
             }
            
             if ($this->cur_menu->getParameter() == 'delete') {
            if ($this->get->id) {
                $akciya = new Stores((int)$this->get->id);
                if ($akciya->getId()){$akciya->destroy();}
            }
            $this->_redir('stores-akciya');
        } elseif($this->cur_menu->getParameter() == 'edit'){
             $akciya = new Stores((int)$this->get->id);

             if(count($_POST)){
                 if(!isset($_POST['active'])){
                     $_POST['active'] = 0;
                 }
                 $_POST['type'] = 'new';
                 if ($_FILES['src']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['src'], 'ru_RU');
                        $folder = '/storage/images/RED_ua/stores/action/';
                        if ($handle->uploaded) {

                            if ($handle->image_src_x != $handle->image_src_y) {
                                 $errors = ['Картинка должна быть квадратной. Сейчас: '.$handle->image_src_x.'x'.$handle->image_src_y];
                            }
                            if (!count($errors)) {
                                $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                                if ($handle->processed) {
                                    if ($akciya->getSrc()){ unlink($_SERVER['DOCUMENT_ROOT'].$akciya->getSrc());}
                                   // $akciya->setSrc($folder . $handle->file_dst_name);
                                     $_POST['src'] = $folder . $handle->file_dst_name;
                                    $handle->clean();
                                }
                            }else{
                               $this->view->error =  $errors;
                            }
                        }
                    }
                     $akciya->import($_POST);
                 $akciya->save();
                 if(!$this->get->id) {
                     $this->_redir('stores-akciya/edit/id/'.$akciya->id);
                     }
             }
              $this->view->akciya = $akciya;
                echo $this->render('stores/akciya_edit.php', 'index.php');
            }else{
              $this->view->akciya  = Storess::getAkcii();
            
           echo $this->render('stores/akciy_list.php', 'index.php');
            }
        }
        public function storestempAction(){
             if($this->post->metod == 'sort'){
                // $ids = implode(',', $this->post->id);
                 $ids = array_flip($this->post->id);
                 foreach ($ids as $key => $id) {
                      $akciya = new Stores((int)$key);
                      $akciya->setSort($id);
                      $akciya->save();
                     
                 }
                 //$mas_id = array_flip($this->post->id);
                // echo print_r($mas_id);
                 die();
             }
            
             if ($this->cur_menu->getParameter() == 'delete') {
            if ($this->get->id) {
                $akciya = new Stores((int)$this->get->id);
                if ($akciya->getId()){$akciya->destroy();}
            }
            $this->_redir('stores-temp');
        } elseif($this->cur_menu->getParameter() == 'edit'){
             $akciya = new Stores((int)$this->get->id);

             if(count($_POST)){
                 if(!isset($_POST['active'])){
                     $_POST['active'] = 0;
                 }
                 $_POST['type'] = 'temp';
                 if ($_FILES['src']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['src'], 'ru_RU');
                        $folder = '/storage/images/RED_ua/stores/temp_post/';
                        if ($handle->uploaded) {

                            if ($handle->image_src_x != $handle->image_src_y) {
                                 $errors = ['Картинка должна быть квадратной. Сейчас: '.$handle->image_src_x.'x'.$handle->image_src_y];
                            }
                            if (!count($errors)) {
                                $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                                if ($handle->processed) {
                                    if ($akciya->getSrc()){ unlink($_SERVER['DOCUMENT_ROOT'].$akciya->getSrc());}
                                   // $akciya->setSrc($folder . $handle->file_dst_name);
                                     $_POST['src'] = $folder . $handle->file_dst_name;
                                    $handle->clean();
                                }
                            }else{
                               $this->view->error =  $errors;
                            }
                        }
                    }
                     $akciya->import($_POST);
                 $akciya->save();
                 if(!$this->get->id) {
                     $this->_redir('stores-temp/edit/id/'.$akciya->id);
                     }
             }
              $this->view->akciya = $akciya;
                echo $this->render('stores/akciya_temp_edit.php', 'index.php');
            }else{
              $this->view->akciya  = Storess::getTempAkcii();
            
           echo $this->render('stores/akciy_temp_list.php', 'index.php');
            }
        }
        
    public function storesaddressAction(){
            
            if ($this->cur_menu->getParameter() == 'delete') {
            if ($this->get->id) {
                $address = new Stores((int)$this->get->id);
                if ($address->getId()){$address->destroy();}
            }
            $this->_redir('stores-address');
        } elseif($this->cur_menu->getParameter() == 'edit'){
             $address = new Stores((int)$this->get->id);

             if(count($_POST)){
                 if(!isset($_POST['active'])){
                     $_POST['active'] = 0;
                 }
                 $_POST['type'] = 'addres';
                
                 if ($_FILES['src']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['src'], 'ru_RU');
                        $folder = '/storage/images/RED_ua/stores/address/';
                        if ($handle->uploaded) {
                            if ($handle->image_src_x != $handle->image_src_y){
                                $errors = ['Картинка должна быть квадратной. Сейчас: '.$handle->image_src_x.'x'.$handle->image_src_y];
                            }
                            if (!count($errors)) {
                                $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                                if ($handle->processed) {
                                    if ($address->getSrc()){ unlink($_SERVER['DOCUMENT_ROOT'].$address->getSrc());}
                                    //$address->setSrc($folder . $handle->file_dst_name);
                                    $_POST['src'] = $folder . $handle->file_dst_name;
                                    $handle->clean();
                                }
                            }else{
                               $this->view->error =  $errors;
                            }
                        }
                    }
                    
                     $address->import($_POST);
                 $address->save();
                  if(!$this->get->id) {
                     $this->_redir('stores-address/edit/id/'.$address->id);
                     }
             }
              $this->view->address = $address;
                echo $this->render('stores/address_edit.php', 'index.php');
            }else{
              $this->view->address =  $address = Stores::getAllSrores();
            
           echo $this->render('stores/address_list.php', 'index.php');
            }
        }

    public function fileeditAction(){
          echo $this->render('fileedit/file_list.php', 'index.php');
    }
                
    public function cartsAction(){
                    
                    if($this->post->method == 'view_cart'){
                        $id = $this->post->id;
                        $res = Cart::view_cart($id);
                        die($res);
                    }  
        $this->view->carts = wsActiveRecord::useStatic('Cart')->findAll();
                    
        echo $this->render('carts/list.tpl.php', 'index.php');
    }

    public function formdesignerAction(){
        if($this->post->method == 'send'){
          //  print_r($this->post->param);
           //exit();
           // $f = new Forms(1);
           $it =  wsActiveRecord::useStatic('FormsItem')->findFirst(['forms_id'=>1]);
       // $it = $f->items;
        $it->setItem($this->post->param);
        $it->save();
            die($this->post->param);
        }
        if($this->post->method == 'trans'){
            die($this->trans->translate($this->post->text, $this->post->from, $this->post->to));
        }
        
        echo $this->render('slugebnoe/formdesigner/form.tpl.php', 'index.php');
    }
    
    public function customerssegmentAction(){
        if($this->post->method == 'reload' and !empty($this->post->id)){
          $segment =  wsActiveRecord::useStatic('CustomersSegment')->findById($this->post->id);
          $i = 0;
           if($segment->id){
                $orders =  wsActiveRecord::useStatic('Shoporders')->findByQuery($segment->sql);
                foreach($orders as $c){
                    $cust = wsActiveRecord::useStatic('Customer')->findById($c->customer_id);
           if($cust->id){
               $cust->setSegmentId($segment->id);
               $cust->save();
               $i++;
           }
                }
            }
           // $this->_redir('customers-segment'); 
            die(json_encode($i));
        }
        
        $this->view->segment = wsActiveRecord::useStatic('CustomersSegment')->findAll(['active'=>1]);
        echo $this->render('mailing/segment/list.tpl.php', 'index.php');
        
    }
    /**
     * Для сегментов пользователей
 * Рассылка email, с возможностью подгрузки товара, предпросмотра, тестового email перед отправкой ну сама рассілка на большое количество подписчиков.
 * Организована с использованием ajax, для предотвращения попадания в спам почтового ящика.
 * Проверка на валидность email, ведение логов
 */	
 public function customerssegmentmailingAction()
    {
     
	 if (isset($this->post->getarticles)) {
                if (isset($this->post->id)) {
        $data = [];
    $articles = wsActiveRecord::useStatic('Shoparticles')->findAll(['category_id' => $this->post->id, 'active' => 'y', 'stock > 0', 'status'=>3],['views'=>'DESC']);
                    if ($articles->count()){
                        foreach ($articles as $article){
                            $data[] = [
                                'id' => $article->getId(),
                                'title' => $article->getTitle() . " (" . Shoparticles::showPrice($article->getRealPrice()) . " грн)",
                                'img' => $article->getImagePath('listing')
                            ];
                }
                    }
                    $res = [
                        'result' => 'done',
                        'type' => 'articles',
                        'data' => $data
                    ];
                }else{
                    $res = ['result' => 'false'];
                }
                die(json_encode($res));
            }elseif($this->post->method == 'preview') {
            $this->view->post = $this->post;
        if(isset($this->post->s_start) and $this->post->s_start == 1){
            $subject = $this->post->subject_start.', TEST, '.$this->post->subject; 
        }else{
            $subject = $this->post->subject;
        }
        $this->view->name = 'Test';
        $this->view->email = 'test@red.ua';
        $this->view->track = '?'
                                . 'cs=test_click_'.date('d.m.Y').''
                                . '&cm=email'
                                . '&cn=TEST';
        $this->view->unsubscribe = ''
                                . '&cs=test_unsubscribe_'.date('d.m.Y').''
                                . '&cm=link'
                                . '&cn=TEST';
        die(json_encode(['title' => $subject, 'message'=>$this->view->render('mailing/general-email.tpl.php')])); 
            }elseif ($this->post->method == 'save'){
                $id = false;
                if($this->post->id_post){
                    $id = $this->post->id_post;
                }
                                    $parr = [
                                        'ctime' => date('Y-m-d H:i:s'),
                                        'id_customer_new' => $this->user->getId(),
                                        'segment_customer' => $this->post->segment_id,
                                        'subject_start' => isset($this->post->subject_start)?$this->post->subject_start:'',
                                        'subject' => isset($this->post->subject)?$this->post->subject:'',
                                        'intro' => $this->post->intro?$this->post->intro:'',
                                        'ending' => $this->post->ending?$this->post->ending:''
                                    ];
                die(Subscribers::saveSubscribe($id, $parr));
            }elseif($this->post->method == 'go_test_email'){
            $subject_start = '';
            $subject = $this->post->subject;
	if($this->post->subject_start){
            $subject_start = $this->post->subject_start;
        }
	if (isset($this->post->s_start) and $this->post->s_start == 1){
            $subject = $subject_start.', '.$this->user->first_name.', '.$subject; 
        }
        
        $copy = 2;                             
	if(isset($this->post->copy) and isset($this->post->copy_email)){
            $copy = $this->post->copy;   
	}
        // $this->view->deposit = 100;
        $sub = new Customer(8005);
         if($this->post->segment_id == 14){
                        $this->view->deposit = $sub->deposit;
        }elseif($this->post->segment_id == 15){
                        $this->view->coin = $sub->getSummCoin('vstup');
        }
        // $this->view->deposit = $sub->deposit;
       //  $this->view->coin = $sub->getSummCoin('active');
        // $this->view->coin = 100;
        $this->view->post = $this->post;  
        $this->view->name = $this->user->first_name;
        $this->view->email = $this->post->test_email;
        $this->view->track = '?'
                                . '&utm_source=test_click_'.date('d.m.Y').''
                                . '&utm_medium=email'
                                . '&utm_campaign=TEST'
                                . '&utm_email_track=test';
        $this->view->unsubscribe = ''
                                . '&utm_source=test_unsubscribe_'.date('d.m.Y').''
                                . '&utm_medium=link'
                                . '&utm_campaign=TEST';
                 
        SendMail::getInstance()->sendSubEmail($this->post->test_email, 'Testing', $subject, $this->view->render('mailing/general-email.tpl.php'));
	//SendMail::getInstance()->sendSubEmail($_POST['test_email'], 'Testing', $subject, $msg, '','','', '', $copy, 'management@red.ua', 'Ирина');
		
            die(json_encode(array('status' => 'send', 'from' => $this->post->test_email)));
    }elseif($this->post->method == 'go_send_email') {
         $s_track = '';
        if($this->post->track){
            $s_track = $this->post->track;
        }
        $cnt = 0;
	$error = 0;
	$subject_start = '';
        $subject = $this->post->subject;
        if($this->post->subject_start){
            $subject_start = $this->post->subject_start;
        }
        
        if($this->post->from_mail == 0){
            $s_track = base64_encode(date('Y-m-d H:i:s'));
				if($this->post->id_post){
				$s = new Emailpost($this->post->id_post);
				if($s->segment_customer == $this->post->segment_id){
				$s->setGo(date('Y-m-d H:i:s'));
                                $s->setCountGo($this->post->all_count);
                                $s->setTrack($s_track);
				$s->setIdCustomerGo($this->user->getId());
				$s->save();
                                }else{
                                    $parr = [
                                        'ctime' => date('Y-m-d H:i:s'),
                                        'go' => date('Y-m-d H:i:s'),
                                        'id_customer_new' => $this->user->getId(),
                                        'id_customer_go' => $this->user->getId(),
                                        'segment_customer' => $this->post->segment_id,
                                        'subject_start' => $subject_start,
                                        'subject' => $subject,
                                        'intro' => $this->post->intro?$this->post->intro:'',
                                        'ending' => $this->post->ending?$this->post->ending:'',
                                        'track' => $s_track,
                                        'count_go' => $this->post->all_count
                                    ];
				Subscribers::saveSubscribe(false, $parr);
                                }
				}else{
                                    $parr = [
                                        'ctime' => date('Y-m-d H:i:s'),
                                        'go' => date('Y-m-d H:i:s'),
                                        'id_customer_new' => $this->user->getId(),
                                        'id_customer_go' => $this->user->getId(),
                                        'segment_customer' => $this->post->segment_id,
                                        'subject_start' => $subject_start,
                                        'subject' => $subject,
                                        'intro' => $this->post->intro?$this->post->intro:'',
                                        'ending' => $this->post->ending?$this->post->ending:'',
                                        'track' => $s_track,
                                        'count_go' => $this->post->all_count
                                    ];
				Subscribers::saveSubscribe(false, $parr);
				}
		}
	
	
    $this->view->post = $this->post;        
    $count = $this->post->count;
                    
    $emails = [];
foreach (wsActiveRecord::useStatic('Customer')->findAll(['segment_id' => $this->post->segment_id, 'customer_type_id' => 1], ['id' => 'ASC'], [$this->post->from_mail, $count]) as $sub){  
    if (isValidEmailNew($sub->getEmail()) && isValidEmailRu($sub->getEmail()) && isValidEmailRed($sub->getEmail())){
		
        $subject_new = $subject;
                
        if(isset( $this->post->s_start) and  $this->post->s_start == 1){
		$subject_new = $subject_start.', '.$sub->first_name.', '.$subject;
        }
                    $this->view->segment = $this->post->segment_id;
                    
                    if($this->post->segment_id == 14){
                        $this->view->deposit = $sub->deposit;
                    }elseif($this->post->segment_id == 15){
                        $this->view->coin = $sub->getSummCoin('active');
                    }
                    
                    $track = $sub->segment_type->track;
                    $this->view->track_open = 'https://www.red.ua/email/image/?photo='.$s_track.'.jpg';
                    $this->view->openimg = ''
                                . 'https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1'
                                . '&cid='.$sub->getId().''
                                . '&t=event'
                                . '&el='.$sub->getId().''
                                . '&cs='.strtolower($track).'_open_'.date('d.m.Y').''
                                . '&cm=open'
                                . '&cn='.$track;
                        $this->view->track = '?'
                                . '&utm_source='.strtolower($track).'_click_'.date('d.m.Y').''
                                . '&utm_medium=email'
                                . '&utm_campaign='.$track.''
                                . '&utm_email_track='.$s_track;
                        $this->view->unsubscribe = ''
                                . '&utm_source='.strtolower($track).'_unsubscribe_'.date('d.m.Y').''
                                . '&utm_medium=link'
                                . '&utm_campaign='.$track;
                       
                        $this->view->name = $sub->first_name;
                        $this->view->email = $sub->getEmail();
                        $emails[] = $sub->getEmail();
			SendMail::getInstance()->sendSubEmail($sub->getEmail(), $sub->first_name, $subject_new, $this->view->render('mailing/general-email.tpl.php'));
			$cnt++;
		}else{
                   // $sub->setActive(0);
                   // $sub->save();
                    $error++;
			wsLog::add('E-mail error: ' . $sub->getEmail(), 'EMAIL');
		}			 
    }	   
    die(json_encode(array('status' => 'send', 'from' => $this->post->from_mail, 'count' => $count, 'emails' => implode(',',$emails), 'cnt'=>$cnt, 'error'=>$error, 'track'=>$s_track))); 
        }
         if($this->get->id){ $this->view->pemail = wsActiveRecord::useStatic('Emailpost')->findById($this->get->id);}
        
            echo $this->render('mailing/segment/form_email.tpl.php', 'index.php');
    }
    public function sellactionAction(){
        if(isset($_POST['open_file'])){
			if (isset($_FILES['excel_file'])) {
                            if (is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
							
                                $tmp_name_excel = $_FILES['excel_file']['tmp_name'];
                                $res = ParseExcel::getExcelToArray($tmp_name_excel);
                                
                                if($this->post->order and $this->post->status and $this->post->summa){
                                   // l($this->post);
                                  $mass = [];
                                    foreach ($res as $k => $value) {
                                        if($k == 0) {
                                            $mass[$k] = $value;
                                            continue; 
                                            
                                        }
                                         $mass[$k] = $value;
                                       // l($value[$this->post->order]);
                $order = wsActiveRecord::useStatic('Shoporders')->findById((int)$value[$this->post->order]);
                    if($order){
                        $mass[$k][$this->post->summa] = str_replace('.', ',', ($order->amount+$order->deposit+$order->bonus));
                        $mass[$k][$this->post->status] = $order->stat->name;
                    }else{
                         $this->view->error = 'Ошибка заказа';
                    }
                                        
                                    }
                                     $this->view->excel = $mass;
                                }else{
                                     $this->view->error = 'Укажите поле Заказа, Статуса и Суммы';
                                }
                               
                            }else{
                                 $this->view->error = 'Проблемы с файлом';        
                            }
                        }
        }elseif(isset($_POST['data'])) {
            
            $mass['header'][0] = $_POST['data'][0];
            unset($_POST['data'][0]);
            
          // l($mass);
          //  exit(); 
            $mass['data'] = $_POST['data'];
            $mass['title'] = 'sellection_'.date('d-m-Y');
             ParseExcel::saveToExcel($mass['title'], [0 => $mass]);
                $this->view->saved = 1;
            }
            //l($_POST['save']);
        if(false){
        $this->view->warning = 'Найдено '.$i.' записей с  '.$j;
                    
        $this->view->save = 'Найдено '.$i.' записей с  '.$j;
    }                        
         echo $this->render('slugebnoe/sellaction/forma.php', 'index.php');
    }
   public function  clearcasheAction(){
       $cache = new Cache();
       $cache->setEnabled(true);
        $cache->clean();
       $this->_redir('index'); 
   }
   public function siteAction(){
       $this->_redirect('/');
   }
   
   public function justinAction(){
     
    if ($this->post->metod == 'load_excel') {
        l($this->post);
        if (isset($_FILES['load_excel_registr']) &&is_uploaded_file($_FILES['load_excel_registr']['tmp_name'])) {
          $file =  ParseExcel::getExcelToArray($_FILES['load_excel_registr']['tmp_name']);
          if(!empty($file)){
              unset($file[0]);
              $orders = [];
              foreach ($file as $key => $value) { 
                  $orders[] = $value[0];
                  
              }
          }
        //  l($orders);
        //  $c1= count($orders);
         // echo $c1;
          $order_list = implode(",", $orders);
        //  l($order_list);
         $orderr =  wsActiveRecord::useStatic('Shoporders')->findAll(["id in({$order_list})"]);
         
        // l(SQLLogger::getInstance()->reportBySQL());
        //  echo $orderr->count();
          if($orderr && $orderr->count() == count($orders)){
              $month = array('01' => 'січня', '02' => 'лютого', '03' => 'березня', '04' => 'квітня', '05' => 'травня', '06' => 'червня',
           '07' => 'липня', '08' => 'серпня', '09' => 'вересня', '10' => 'жовтня', '11' => 'листопада', '12' => 'грудня'
        );
              $_SESSION['lang'] = 'uk';
              foreach ($orderr as $order){
                  if($order->status == 13){
                      if($order->editStatus(8, $this->view, $this->user)){
                          $dt = explode('-', substr($order->getDateCreate(), 0, 10));
            $dttd = explode('-', date("Y-m-d"));
            $this->view->date = $dt[2] . ' ' . $month[$dt[1]] . ' ' . $dt[0];
            $this->view->date_today = $dttd[2] . ' ' . $month[$dttd[1]] . ' ' . $dttd[0];
            $this->view->exploded_date = $dt;
            $this->view->order = $order;


            $all_orders_2 = wsActiveRecord::useStatic('Customer')->findByQuery('
			        SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
			        WHERE ws_orders.customer_id = ' . $order->getCustomerId() . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) AND ws_orders.id <' . $order->id)->at(0);
            $this->view->all_orders_amount_total = (int)$all_orders_2->getAmount();
        
           
              
            $this->view->zayava = false; 
            $this->view->count = 1;
            echo $this->render('', 'order/tovarniy_chek.php');
                      }
                  }
             }
              $_SESSION['lang'] = 'ru';
          }else{
              echo 'ERROR! В базе не все заказі найдены!';
          }
          unset($_FILES);
        }
        exit();
        
    } elseif ($this->post->metod == 'search_depart') {
    $dep = wsActiveRecord::useStatic('JustinDepartments')->findAll(['city_uuid'=>$this->post->id]);
    $rdep = [];
    $i=0;
    foreach ($dep as $d) {
      $rdep[$i]['id'] = $d->branch;
      $rdep[$i]['text'] = $d->depart_descr.' обл. '.$d->address;
      $i++;
    }
    //{id:args.id, text: args.text}
   // echo $this->post->id;
    die(json_encode($rdep));
    
}elseif($this->cur_menu->getParameter() == "new"){
    $text = [];
    require_once('Justin/JustinClass.php');
        $justin = new JustinClass();
    if($this->get->add){
        //l($this->post);
        $arr = [];
        
        foreach ($this->post as $k => $p){
          $arr[$k] = $p;  
        }
        $res = $justin->createOrder($arr);
       // l($res);
      //  die();
       if($res['result'] == "success"){
           $arr['number_ttn'] = $res['data']['ttn'];
           $arr['number_pms'] = $res['data']['number'];
           $p = new JustinRequestDeliveryInfo();
           $p->import($arr);
           $p->save();
           $order = new Shoporders($arr['number']);
           if($order->id){
              // $order->setNakladna($res['data']['ttn']);
                $order->setNakladna($res['data']['number']);
               $order->save();
           }
         $text = ['result' =>true, 'text' => $justin->getOrderStickerLink($arr['number'])];
            die(json_encode($text));
        }else{
            
            if(isset($res['errors'])){
              //  l($res['errors']);
              //  die();
             $text = ['result' =>false, 'text' => '<div class="alert alert-danger" role="alert">'.implode("<br>", $res['errors']).'</div>'];
            }
            //l($res['errors']);
             die(json_encode($text));
        }
       // print_r($res);
        
    }
           $this->view->order = wsActiveRecord::useStatic('Shoporders')->findById($this->get->id);
            $this->view->list = wsActiveRecord::useStatic('JustinDepartments')->findAll();
            echo $this->render('justin/new.php', 'index.php');
           
       }elseif($this->cur_menu->getParameter() == "edit"){
            require_once('Justin/JustinClass.php');
        $justin = new JustinClass();
        if($this->get->cancel){
           $res =  $justin->cancelOrder($this->post->number);
           if($res['result'] == "success"){
               $or = $res['data'];
               wsActiveRecord::query("DELETE FROM `ws_justin_request_delivery_info` WHERE `number_pms` =".$res['data']['number']);
              // $p = wsActiveRecord::useStatic('JustinRequestDeliveryInfo')->findFirst(['number_pms'=>$res['data']['number']]);
               //DELETE FROM `ws_justin_request_delivery_info` WHERE `number_pms` =12345
           $order = new Shoporders($this->post->number);
           if($order->id){
               $order->setNakladna(NULL);
               $order->save();
           }
           echo 'ТТН №'.$res['data']['number'].' отменена!';
          // echo 'ТТН №'.$res['data']['ttn'].' отменена!';
           }else{
               echo 'error delete'.$this->post->number;
           }
           // l($res);
            die();
        }elseif($this->get->info){
            $res =  $justin->getOrderInfo($this->post->number);
            if($res){
                l($res);
            }else{
                 echo 'error info'.$this->post->number;
            }
            die();
        }elseif($this->get->stickers){
            
            die($justin->getOrderStickerLink($this->post->number));
            
        }
        
           $this->view->order = wsActiveRecord::useStatic('Shoporders')->findById($this->get->id);

                $this->view->list = wsActiveRecord::useStatic('JustinCities')->findAll(['active'=>1]);
            echo $this->render('justin/edit.php', 'index.php');
       }else{

       require_once('Justin/JustinClass.php');
        //$justin = new JustinClass('RedUA1', 'OsiMaNaT', '08d0c6b5-1d89-11ea-abe1-0050569b41a9', 'UA');
         $justin = new JustinClass();
        
        
if($this->post->refresh_department){
try {
        // Отримання інформації про відділення по API
         $res = $justin->getDepartments([], 1000)['data']; 
        
         $list = [];
         $i=1;
         if(!empty($res) && is_array($res)){
         foreach ($res as $value) {
                 $list[$i]['branch'] = $value['fields']['branch'];
                 $list[$i]['number'] = $value['fields']['departNumber'];
                 $list[$i]['uuid'] = $value['fields']['Depart']['uuid'];
                 $list[$i]['depart_descr'] = $value['fields']['Depart']['descr'];
                 $list[$i]['description'] = $value['fields']['descr'];
                 $list[$i]['region_uuid'] = $value['fields']['region']['uuid'];
                 $list[$i]['region_name'] = $value['fields']['region']['descr'];
                 $list[$i]['city_uuid'] = $value['fields']['city']['uuid'];
                 $list[$i]['city_name'] = $value['fields']['city']['descr'];
                 $list[$i]['street_uuid'] = $value['fields']['street']['uuid'];
                 $list[$i]['street_name'] = $value['fields']['street']['descr'];
                 $list[$i]['street_number'] = $value['fields']['houseNumber'];
                 $list[$i]['weight_limit'] = $value['fields']['weight_limit'];
                 $list[$i]['address'] = $value['fields']['address'];
                 $list[$i]['lat'] = $value['fields']['lat'];
                 $list[$i]['lng'] = $value['fields']['lng'];
                 $list[$i]['type_value'] = $value['fields']['TypeDepart']['value'];
                 $list[$i]['type_descr'] = $value['fields']['TypeDepart']['enum'];
                 
                 $i++;
         }
         if(count($list)){
       	wsActiveRecord::query("TRUNCATE TABLE ws_justin_departments");
         foreach ($list as $value) {
            $d = new JustinDepartments();
            $d->import($value);
            $d->save();
         }
         
            echo '<div class="alert alert-success" role="alert">Отделения обновлены. '.$i.'</div>'; 
         }
}else{
    l($res);
    echo 'Error';
}
       
           
       
        
         
         die();
        

} catch (Exception $e) {

    echo $e->getMessage() . "\n";

}
}elseif($this->post->refresh_cities){
    try {
      $res =  $justin->getCities([], 1000)['data'];
     // l($res);
      $list = [];
         $i=1;
         foreach ($res as $value) {
                // $list[$i]['uuid'] = $value['fields']['uuid'];
               //  $list[$i]['name_uk'] = $value['fields']['descr'];
                 $d = $justin->getDepartments([
                      0 => [
                   'name'=>'city',
                   'comparison'=>'equal',
                   'leftValue'=>$value['fields']['uuid']
           ]
                 ], 1000)['data'];
                 if($d){
                $list[$i]['uuid'] = $value['fields']['uuid'];
                 $list[$i]['name_uk'] = $value['fields']['descr'];
                //$list[$i]['dep'] = $d;
                  $i++;
                 }
                
                
         }
       /* foreach ($list as $value) {
           // $d = wsActiveRecord::useStatic('JustinCities')->findFirst(['uuid'=>$value['uuid']]);
           // if($d->id){
               // $d->setNameUa($value['name_ru']);
            $d = new JustinCities();
            $d->import($value);
            $d->save();
           // }
         }
         */
         l($list);
         die();
      
    }catch(Exception $e){
    echo  $e->getMessage() . "\n";
}

}elseif($this->post->reestradd and !empty($this->post->id)){
   $res =  $justin->getReestrAdd($this->post->id);
   //l($res);
   if($res['result'] == "success"){
             //  $or = $res['data'];
              // wsActiveRecord::query("DELETE FROM `ws_justin_request_delivery_info` WHERE `number_pms` =".$res['data']['number']);
              // $p = wsActiveRecord::useStatic('JustinRequestDeliveryInfo')->findFirst(['number_pms'=>$res['data']['number']]);
               //DELETE FROM `ws_justin_request_delivery_info` WHERE `number_pms` =12345
          // $order = new Shoporders($this->post->number);
           //if($order->id){
             //  $order->setNakladna(NULL);
            //   $order->save();
          // }
           echo 'Реестр создан №'.$res['data']['number'];
           }else{
               echo 'Ошибка создания реестра.';
               l($res['errors']);
           }
   //l($res);
    die();
}elseif($this->post->reestrinfo){
    //$justin = new JustinClass('RedUA1', 'OsiMaNaT', '08d0c6b5-1d89-11ea-abe1-0050569b41a9', 'UA');
    $res =  $justin->getReestrInfo(date('Y-m-d'));
    l($res);
    die();
}elseif($this->post->method == 'trac'){
    $fop = [
        1 => ['login'=>'redUA', 'pass'=>'TEdmAtIc', 'key'=>'851e2b3e-ffd5-11e9-abd8-0050569b9e7e'],
        2 => ['login'=>'RedUA1', 'pass'=>'OsiMaNaT', 'key'=>'08d0c6b5-1d89-11ea-abe1-0050569b41a9'],
        5 => ['login'=>'PixIad', 'pass'=>'SzajabXuIveH', 'key'=>'918d4960-f296-11ea-ac14-0050569b9e7e']
    ];
    $justin = new JustinClass($fop[$this->post->fop]['login'], $fop[$this->post->fop]['pass'], $fop[$this->post->fop]['key']);
    $id = $this->post->barcode;
   // l($justin->getApiKey());
   $res =  $justin->getOrderStatusesHistory(
           [
            /*   0 => [
                   'name'=>'TTN',
                   'comparison'=>'equal',
                   'leftValue'=>$id
           ],*/
                0 => [
                   'name'=>'orderNumber',
                   'comparison'=>'equal',
                   'leftValue'=>$id
           ]], 10);
 l($res);
   if($res['response']['codeError'] == 777){
   $m =  $res['data'];
   $rr = [];
   $i=0;
   foreach ($m as $value) {
       $rr[$i] = $value['fields']['statusOrder']['descr'];
       $i++;
   }
   l($rr);
   die();
}else{
    l($res);
}
    die();
}elseif(!empty($this->post->list_orders)){
   // $fop = [
   //     1=> ['login'=>'redUA', 'pass'=>'TEdmAtIc', 'key'=>'851e2b3e-ffd5-11e9-abd8-0050569b9e7e'],
   //     2=> ['login'=>'RedUA1', 'pass'=>'OsiMaNaT', 'key'=>'08d0c6b5-1d89-11ea-abe1-0050569b41a9'], 
  //  ];
   // $justin = new JustinClass($fop[2]['login'], $fop[2]['pass'], $fop[2]['key']);
     
    $res = $justin->getListOrders(date('Y-m-d', strtotime($this->post->list_orders)));
    
    if(count($res)){
        $text = '';
        $reestr = $justin->getReestrInfo(date('Y-m-d', strtotime($this->post->list_orders)));
       
    if($reestr['result'] == 'success'){
        foreach ($reestr['data'] as $value) {
             $text .= '<div class="card"><div class="card-body"><h5 class="card-title">Реестр № '.$value['number'].'</h5>';
             $text.= '<p>('.count($value['orders_number']).') - '.implode(',', $value['orders_number']).'</p>';  
            $text .='</div></div>';
        }
       
    }
        $text .= '<div class="card"><div class="card-body"><table class="table table-hover table-bordered datatable1" data-page-length="50">'
                . '<thead><tr>'
                . '<th>№</th>'
                . '<th>Заказ</th>'
                . '<th>ТТН</th>'
                . '<th>ЭН</th>'
                . '<th>Создано</th>'
                . '<th>Получатель</th>'
                . '<th>Телефон</th>'
                . '<th>Статус</th>'
                . '</tr></thead><tbody>';
        $i=1;
        foreach ($res as $value) { 
            $text .= '<tr>'
                    . '<td>'.$i.'</td>'
                    . '<td>'.$value["number_kis"].'</td>'
                    . '<td>'.$value["number_ttn"].'</td>'
                    . '<td>'.$value["number_en"].'</td>'
                    . '<td>'.date("d-m-Y", strtotime($value["date"])).'</td>'
                    . '<td>'.$value["reciver"].'</td>'
                    . '<td>'.$value["phone_reciver"].'</td>'
                    . '<td>'.$value["status"].' ('.$value["date_status"].')</td>'
                    . '</tr>';
            $i++;
        }
        $text .= '</tbody></table>'
                . '</div></div>';
        echo $text;
    }else{
       echo $this->post->list_orders.' - оформлений не было';
    }
    die();
}
                //l($justin->getListOrders());
    $this->view->orders = wsActiveRecord::useStatic('Shoporders')->findAll(['delivery_type_id'=>18, 'status in(9,15,16)', 'is_admin' => 0]);
   // $this->view->orders_admin = wsActiveRecord::useStatic('Shoporders')->findAll(['delivery_type_id'=>18, 'status in(9,15,16)', 'is_admin' => 1]);
    $this->view->list = wsActiveRecord::useStatic('JustinCities')->findAll(['active'=>1]);
 //$list = wsActiveRecord::useStatic('JustinDepartments')->findByArray();
//$list = wsActiveRecord::findByQueryArray("SELECT * FROM `ws_justin_departments`");
        // $list;
        echo $this->render('justin/index.php', 'index.php');
   }
   
   }
   public function redcoinAction(){
       if($_GET){
           $data = [];
           if(!empty($this->get->status)){$data[] = "`status` = {$this->get->status}";}
           if(!empty($this->get->order_id_add)){$data[] = "`order_id_add` = {$this->get->order_id_add}";}
           if(!empty($this->get->customer_id)){$data[] = "`customer_id` = {$this->get->customer_id}";}
           if(!empty($this->get->date_add)){$data[] = "`date_add` = '{$this->get->date_add}'";}
           if(!empty($this->get->date_actrive)){$data[] = "`date_active` = '{$this->get->date_actrive}'";}
           if(!empty($this->get->date_off)){ $data[] = "`date_off` = '{$this->get->date_off}'";}
          /* foreach ($_GET as $k=>$p){
               if($p){
                   $data[$k] = $p;
               }
           }*/
           $this->view->coin = wsActiveRecord::useStatic('RedCoin')->findAll($data, ['id'=>'DESC'],[0, 100]);
          // l(SQLLogger::getInstance()->reportBySQL());
       }
      $this->view->status = wsActiveRecord::useStatic('RedCoinStatus')->findAll();
        echo $this->render('redcoin/index.php', 'index.php');
        
        
   }
   public function fopAction(){
       
       
        $this->view->fop = wsActiveRecord::useStatic('Fop')->findAll();
        echo $this->render('fop/index.php', 'index.php');
   }
   public function tovarAction(){
      // $sql = "SELECT ws_articles.*,";
       $select = "SELECT ws_articles.*";
       $from = " FROM ws_articles";
       $join = "";
       $where = [];
       $order_by = "";
       $limit = "";
       if($this->get){
           l($this->get);
           if(!empty($this->get->id)){
              // $select .=", red_brands.greyd";
               //$join .=" INNER JOIN  red_brands ON ws_articles.brand_id = red_brands.id";
               $where[] =  "ws_articles.id = ".$this->get->id;
           }
           if(!empty($this->get->artikul)){
              // $select .=", red_brands.greyd";
               //$join .=" INNER JOIN  red_brands ON ws_articles.brand_id = red_brands.id";
               $where[] =  "ws_articles.artikul  LIKE '".$this->get->artikul."'";
           }
           
           if(!empty($this->get->greyd)){
               $select .=", red_brands.greyd";
               $join .=" INNER JOIN  red_brands ON ws_articles.brand_id = red_brands.id";
               $where[] =  "red_brands.greyd = ".$this->get->greyd;
           }
           
       }
       
      // $where_s = explode('&', $where);
       $sql = $select.$from.$join. " WHERE ".implode(" and ", $where).$order_by.$limit;
       l($sql);
       $onPage = 50;

            $page = !empty($this->get->page) && (int)$this->get->page ? (int)$this->get->page : 1;
            $startElement = ($page - 1) * $onPage;
			if(isset($_GET['go'])){
			$sql='SELECT COUNT( DISTINCT ws_articles.id ) AS ctn FROM ws_articles'
                                . ' INNER JOIN ws_articles_sizes ON ws_articles.id = ws_articles_sizes.id_article'
                                . ' WHERE   '.$data1.' ';
			$total =  wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql)->at(0)->getCtn();
			}else{
            $total = 1000;
			}
			
		//	if (@$_GET['proc'])  $onPage = $total;
			
            $this->view->totalPages = ceil($total / $onPage);
            $this->view->count = $total;
            $this->view->page = $page;
            $this->view->start = ($page == 1) ? 1 : $onPage * ($page - 1);
            $this->view->end = $onPage * ($page - 1) + $onPage;


            if (isset($_GET['search_artikul']) and strlen(@$_GET['search_artikul']) > 0) {
                $articles = wsActiveRecord::useStatic('Shoparticles')->getArticlesByArticul($_GET['search_artikul']);
            } else {
			
		$sql='SELECT ws_articles.*, ws_articles_sizes.id_article, ws_articles_sizes.id_color, ws_articles_sizes.id_size from ws_articles inner join ws_articles_sizes on  ws_articles.id = ws_articles_sizes.id_article
			WHERE  '.$data1.' GROUP BY ws_articles.id order by '.$order_by.' '.$order_by_type.' LIMIT '.$startElement.' , '.$onPage;
			//var_dump($sql);
			$articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql); 
            }
            $this->view->articles = $articles;
      //  $this->view->fop = wsActiveRecord::useStatic('Fop')->findAll();
       echo $this->render('tovarovedy/index.php', 'index.php');
   }
 public function tablesizeAction(){
      $this->view->table = $table = wsActiveRecord::useStatic('Shoparticlestablesize')->findAll();
     
      l($table);
      echo $this->render('size/list.table.tpl.php', 'index.php');
      
   }
   public function ordersAction(){
       //выборка статусов
        $dat = [];
        $dat[] = ' active = 1';
	if($this->user->isPointIssueAdmin()){
	$dat[] = ' id in(100,3,5,7,8,9,15,16,13)';
	}else{
	$dat[] = ' id != 0';
	}
	/*if($this->user->isDeveloperAdmin()){
	$dat[] = ' id in(0,1,3,5,7,8,9,15,16)';
	}*/
	
        $o_stat = wsActiveRecord::useStatic('OrderStatuses')->findAll($dat);
        $mas_os = [];
        foreach ($o_stat as $o) {
            $mas_os[$o->getId()] = $o->getName();
        }
        $this->view->order_status = $mas_os;
        //выборка статусов
        
        //форма отбора заказов

        $data = [];
        $data[] = 'magaz = 1';
       // l($_GET);

        if (isset($_GET['kupon']) and @$_GET['kupon'] == 1) {$data[] = 'kupon NOT LIKE ""';}
	if (isset($_GET['online']) and @$_GET['online'] == 1) {$data[] = 'payment_method_id in(7,4,6)';}
	if (isset($_GET['bonus']) and @$_GET['bonus'] == 1) {$data[] = 'bonus > 0';}
        if (isset($_GET['is_admin']) and $_GET['is_admin'] == 1) { $data[] = 'is_admin = 1'; }else{$data[] = 'is_admin = 0'; }
        
        //if (isset($_GET['shop_id']) and !empty($_GET['shop_id'])){ $data[] = 'shop_id = '.$_GET['shop_id']; }
        if (isset($_GET['detail']) and @$_GET['detail'] == 1) {$data[] = 'call_my = 1';}

	if (isset($_GET['nall']) and @$_GET['nall'] == 1) {$data[] = 'amount > 0';}
		
        if (isset($_GET['order']) and $_GET['order'] > 0) {
            
            $iddd = explode(',', $_GET['order']);
			
			if(count($iddd) == 1){
			 $data[] = '( id = '.$_GET['order'].' or comlpect LIKE "%' . $_GET['order'] . '%" or oldid = '.$_GET['order'].')';
			}else{
			$data[] = 'id in( '.implode(",", $iddd).') ';
			}
        }

        if (isset($_GET['phone']) and strlen($_GET['phone']) > 0) $data[] = 'telephone LIKE "%' . trim($_GET['phone']) . '%"';

        if (isset($_GET['uname']) and strlen($_GET['uname']) > 0) $data[] = 'name LIKE "%' . $_GET['uname'] . '%"';

        if (isset($_GET['email']) and strlen($_GET['email']) > 0) $data[] = 'email LIKE "%' . $_GET['email'] . '%" or temp_email LIKE "%' . $_GET['email'] . '%"';

	if (isset($_GET['customer_id']) and strlen($_GET['customer_id']) > 0) $data[] = 'customer_id = '.trim($_GET['customer_id']);

        if (isset($_GET['delivery']) and (int)$_GET['delivery'] > 0) {$data['delivery_type_id'] = (int)$_GET['delivery'];}
        if (isset($_GET['status']) and (int)$_GET['status'] < 900) {
	if ($_GET['status'] == 0 or $_GET['status'] == 15 or $_GET['status'] == 16 or $_GET['status'] == 9 or $_GET['status'] == 100) {
				$order_orderby = array('id' => 'ASC');
	}else{
				$order_orderby = array('id' => 'DESC');
	}
            if ((int)$_GET['status'] == 8) {
                $data[] = 'status = 8 OR status = 14';
            }else{
                $data['status'] = (int)$_GET['status'];
            }
        }
        if(isset($_GET['liqpay_status_id']) and ($_GET['liqpay_status_id'] == 1 or $_GET['liqpay_status_id'] == 3)){
             $data['liqpay_status_id'] = (int)$_GET['liqpay_status_id'];
        }
        if(isset($_GET['admin']) and (int)$_GET['admin'] > 0){$data['admin'] = (int)$_GET['admin'];}


        if ((isset($_GET['create_from']) and strlen($_GET['create_from']) > 0) or (isset($_GET['create_to']) and strlen($_GET['create_to']) > 0)) {

            $start = '';
            $end = '';
            if (strlen(@$_GET['create_from']) > 0) $start = strtotime($_GET['create_from']);
            if (strlen(@$_GET['create_to']) > 0) $end = strtotime($_GET['create_to']);
            if ($start != '' and $end != '') {
                if ($start > $end) {
                    $k = $start;
                    $start = $end;
                    $end = $k;
                }
            }
            if ($start != '') $data[] = 'date_create > "' . date('Y-m-d 00:00:00', $start) . '"';
            if ($end != '') $data[] = 'date_create < "' . date('Y-m-d 23:59:59', $end) . '"';

        }

        if ((isset($_GET['go_from']) and strlen($_GET['go_from']) > 0) or (isset($_GET['go_to']) and strlen($_GET['go_to']) > 0)) {

            $start = '';
            $end = '';
            if (strlen(@$_GET['go_from']) > 0) $start = strtotime($_GET['go_from']);
            if (strlen(@$_GET['go_to']) > 0) $end = strtotime($_GET['go_to']);
            if ($start != '' and $end != '') {
                if ($start > $end) {
                    $k = $start;
                    $start = $end;
                    $end = $k;
                }
            }
            if ($start != '') $data[] = 'order_go > "' . date('Y-m-d 00:00:00', $start) . '"';
            if ($end != '') $data[] = 'order_go < "' . date('Y-m-d 23:59:59', $end) . '"';

        }
        if (isset($_GET['price']) and strlen($_GET['price']) > 0) { $data[] = 'amount > ' . ((int)$_GET['price'] - 3) . ' AND amount < ' . ((int)$_GET['price'] + 3);}
        if (isset($_GET['nakladna']) and strlen($_GET['nakladna']) > 0) {$data[] = 'nakladna LIKE "%' . $_GET['nakladna'] . '%"';}

        
        
        $onPage = $_COOKIE['item_page']?$_COOKIE['item_page']:40;
        
        $page = !empty($this->get->page) && (int)$this->get->page ? (int)$this->get->page : 1;
        $startElement = ($page - 1) * $onPage;
		//if(isset($_GET['go'])){
		$total = wsActiveRecord::useStatic('Shoporders')->count($data);
		//}else{
		// $total = 300; 
                
		///}
        //$total = wsActiveRecord::useStatic('Shoporders')->count($data);
        $this->view->totalPages = ceil($total / $onPage);
        $this->view->count = $total;
        $this->view->page = $page;
        $this->view->start = ($page == 1) ? 1 : $onPage * ($page - 1);
        $this->view->end = $onPage * ($page - 1) + $onPage;
        $this->view->orders = wsActiveRecord::useStatic('Shoporders')->findAll($data, $order_orderby, array($startElement, $onPage));
        
         echo $this->render('shoporders/orders.tpl.php');
    }
    
    public function promocodeAction(){
       // l($this->cur_menu->getParameter());
        if ($this->cur_menu->getParameter() == "edit") {
            if($this->post->add_promokode){
                $this->post->ctime = date('Y-m-d H:i:00', strtotime($this->post->ctime));
                $this->post->expirationtime = date('Y-m-d H:i:59', strtotime($this->post->expirationtime));
                unset($this->post->add_promokode);
                $other = new Other();
                $other->import($this->post);
                $other->save();
                
                redirect("/admin/promocode/");
            }else{
                 $other = wsActiveRecord::useStatic('Other')->findById($this->get->id);
            }
            
            //$other = wsActiveRecord::useStatic('Other')->findById($this->get->id);
            
            
            $this->view->other = $other;
            echo $this->render('promocode/edit.tpl.php', 'index.php');
        
            return;
        }
        
        $this->view->others = wsActiveRecord::useStatic('Other')->findAll([], [], [0,50]);
        
         echo $this->render('promocode/list.tpl.php', 'index.php');
    }
    
}