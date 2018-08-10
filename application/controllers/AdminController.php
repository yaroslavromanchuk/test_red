<?php

class AdminController extends controllerAbstract
{

    private $_files_folder = 'admin_files';
    private $_controller = 'admin';

    //maybe put to controller abstract??
    public function init()
    {
	if(@$_SESSION['lang']){
	$lang = $_SESSION['lang'];
	}else{
	$lang = 'ru';
	}
        Registry::set('lang_id', wsLanguage::findByCode($lang)->getId());
        Registry::set('lang', $lang);

        mb_internal_encoding("UTF-8");
        define('TEMP', $_SERVER['DOCUMENT_ROOT'] . '/tmp/');
        $this->view->setRenderPath(INPATH . $this->_files_folder);

        $this->view->path = SITE_URL . '/' . $this->_controller . '/';
        $this->view->files = '/' . $this->_files_folder . '/';

        $this->user = $this->website->getCustomer();
        $this->view->user = $this->user;

        $this->view->trans = $this->trans = new Translator();


        define('MAX_COUNT_PER_ARTICLE', 100);

        //get user from sesion or cookies
        if (!$this->user->getIsLoggedIn() || !$this->user->isAdmin()) {
            $this->loginAction();
            die();
        }

        foreach (AdminRights::getAdminRights($this->user->getId()) as $rights) {
            $a_rights[$rights->getPageId()]['right'] = $rights->getRight();
            $a_rights[$rights->getPageId()]['view'] = $rights->getView();
        }
        $this->view->admin_rights = $a_rights;
        if (!$this->user->isSuperAdmin()) {
            if (!@$a_rights[$this->cur_menu->getId()]['right']) {
                die('Нету прав просматривать эту страницу. Обратитесь к Супер-Администратору.
                <br /> <a href="/admin/">На главную</a>');
            }
        }
				
		//vsplivauche soobschenye
			$this->view->message = $this->view->render('np/message.tpl.php');
		
$this->view->days = array('Mon'=>'Понедельник', 'Tue'=>'Вторник', 'Wed'=>'Среда', 'Thu'=>'Четверг', 'Fri'=>'Пятница', 'Sat'=>'Суббота','Sun'=>'Воскресенье');
		
		
        /* if (!$this->user->isSuperAdmin()) {
            if ($this->cur_menu->getAdminRights()) {
                $rights = explode(',', $this->cur_menu->getAdminRights());
                if ($this->user->getAdminRights() != 999) {
                    if (!in_array($this->user->getAdminRights(), $rights)) $this->_redir('index');
                }
            }
        }*/
		

    }
	
	
    public function migrateAction() {
		die('Миграция выполненна');
		$q = "ALTER TABLE  `red_events` ADD  `disposable` INT( 1 ) NOT NULL DEFAULT  '0';";
       	wsActiveRecord::query($q);
		$q = "ALTER TABLE  `red_event_customers` ADD  `st` INT( 11 ) NOT NULL DEFAULT  '1', ADD  `session_id` VARCHAR( 100 ) NOT NULL;";
       	wsActiveRecord::query($q);
		die('Миграция выполненна');
	}

    public function testAction()
    {
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$q = "ALTER TABLE  `red_events` ADD  `disposable` INT( 1 ) NOT NULL DEFAULT  '0';";
       	wsActiveRecord::query($q);
		$q = "ALTER TABLE  `red_event_customers` ADD  `st` INT( 11 ) NOT NULL DEFAULT  '1', ADD  `session_id` VARCHAR( 100 ) NOT NULL;";
       	wsActiveRecord::query($q);
		die('Миграция выполненна');
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ini_set('memory_limit', '1024M');
        set_time_limit(2800);
        //$order = new Shoporders(41873);
//        $order->save();
        die('ok');
    }
	
	
    public function render($inner, $outter = 'admin.tpl.php')
    {
        $this->view->middle_template = $inner;
		//if($this->user->id == 8005) return $this->view->render('index.php');
		//if($inner == '/template/views/home.php') return $this->view->render('index.php');
        return $this->view->render($outter);
    }

    public function _redir($action)
    {
        $this->_redirect($this->_path($action));
    }

    protected function _path($action)
    {
        return SITE_URL . '/' . $this->_controller . '/' . $action . '/';
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
        if ($this->user->getIsLoggedIn() and $this->user->isAdmin())
            $this->_redir('index');

        if (!count($_POST)) {
            //render login page
            echo $this->render('', 'login.tpl.php');
        } else {
            //or do	login
            if (Registry::isRegistered('site_id')) {
                $old_site = Registry::get('site_id');
                Registry::unRegister('site_id');
            }

            $res = $this->user->loginByEmail(@$_POST['login'], @$_POST['password']);

            if (isset($old_site))
                Registry::set('site_id', $old_site);

            if ($res) {
                $this->website->updateHashes();
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

	public function homeAction(){
	$result = array();
	 $r_ok = array();
	$type="";
		if($this->post->method == "ucenka_2"){
	
	$sql = "SELECT  `ws_articles`.`ucenka` , SUM(  `ws_articles_sizes`.`count` ) AS ctn
FROM  `ws_articles` 
INNER JOIN  `ws_articles_sizes` ON  `ws_articles`.`id` =  `ws_articles_sizes`.`id_article` 
WHERE  `ws_articles_sizes`.`count` > 0
GROUP BY  `ws_articles`.`ucenka` ";
$ucenka = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
$s = 0;
foreach($ucenka as $c){
$result[$c->ucenka] = $c->ctn;
$s+=$c->ctn;
}
$result['sum'] = $s;

	
	die(json_encode($result));
	
	}
	if($this->post->method == "ucenka"){
	/*
	$sql = "SELECT  `ws_articles`.`ucenka` , SUM(  `ws_articles_sizes`.`count` ) AS ctn
FROM  `ws_articles` 
INNER JOIN  `ws_articles_sizes` ON  `ws_articles`.`id` =  `ws_articles_sizes`.`id_article` 
WHERE  `ws_articles_sizes`.`count` >0
GROUP BY  `ws_articles`.`ucenka` ";
$ucenka = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
$s = 0;
foreach($ucenka as $c){
$result[$c->ucenka] = $c->ctn;
$s+=$c->ctn;
}
$result['sum'] = $s;

	
	die(json_encode($result));
	*/
$sql="SELECT DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) AS dat
FROM  `ucenka_history` 
WHERE DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) >  '2018-06-05'
GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ";
$mas = array();
$i=0;
foreach(wsActiveRecord::useStatic('UcenkaHistory')->findByQueryArray($sql) as $c){
$sql = "SELECT  `proc` , SUM(  `koll` ) AS ctn FROM   `ucenka_history` WHERE  DATE_FORMAT( `ctime` ,  '%Y-%m-%d' ) =  '".$c->dat."' GROUP BY  `proc` ";
$mas[$i]['x'] = $c->dat;
foreach(wsActiveRecord::useStatic('UcenkaHistory')->findByQueryArray($sql) as $t){
$mas[$i][$t->proc] = @$t->ctn?$t->ctn:0;
}
$i++;
}
die(json_encode($mas));
	}
	if($this->post->method == "shop" and @$this->post->type){
	$type = $this->post->type;
	switch ($type) {
	case 'h_a' :
$ok = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status`  in(0,1,9,15,16)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d %H' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d %H' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status`  in(2,7)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d %H' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[(int)$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[(int)$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[(int)$k->dat] ? $p[(int)$k->dat] : 0, 'ret'=>$re[(int)$k->dat] ? $re[(int)$k->dat] : 0 );
		   }
		   
			die(json_encode($r_ok));
			case 'n_d_a' :
$ok = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY )
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status`  in(2,7)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[$k->dat] ? $p[$k->dat] : 0, 'ret'=>$re[$k->dat] ? $re[$k->dat] : 0 );
		   }
		   
			die(json_encode($r_ok));
			case 'm_d_a' :
$ok = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status`  in(2,7)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[$k->dat] ? $p[$k->dat] : 0, 'ret'=>$re[$k->dat] ? $re[$k->dat] : 0 );
		   }
		   
			die(json_encode($r_ok));
	default : 
 
 
			die(json_encode($r_ok));
	}
	}
	if($this->post->method == "order" and @$this->post->type){
	$type = $this->post->type;
	switch ($type) {
            case 'h' : 
			$ok = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT DATE_FORMAT(  `date_create` ,  '%H' ) AS dat, COUNT(  `id` ) AS ctn FROM  `ws_orders` WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) AND status NOT IN (5,7,17) GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d %H' ) ORDER BY  `dat` ASC ");
		   //$i = 0;
		   foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->ctn, $type => 0 );
		   //$i++;
		   }
		    die(json_encode($r_ok));
            case 'm_h' : 
			$ok = 	wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT DATE_FORMAT(  `date_create` ,  '%H' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND status NOT IN (5,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");
  $i = 0;
		   foreach($ok as $k){
		   //$r_ok[$i] = array('x'=> $i, 'y' =>(int)$k->ctn , $type => 0 );
		    $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->ctn, $type => 0 );
		   $i++;
		   }
		    die(json_encode($r_ok));
			 case 'm_d' : 
			$ok = 	wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND status NOT IN (5,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
  $i = 0;
		   foreach($ok as $k){
		   //$r_ok[$i] = array('x'=> $i, 'y' =>(int)$k->ctn , $type => 0 );
		    $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn, $type => 0 );
		   $i++;
		   }
		    die(json_encode($r_ok));
			case 'n_h' :
			$ok = 	wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT DATE_FORMAT(  `date_create` ,  '%H' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 
DAY ) 
AND status NOT IN (5,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");
			  $i = 0;
		   foreach($ok as $k){
		   //$r_ok[$i] = array('x'=> $i, 'y' =>(int)$k->ctn , $type => 0 );
		    $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->ctn, $type => 0 );
		   $i++;
		   }
			 die(json_encode($r_ok));
			 case 'n_d' :
			$ok = 	wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 
DAY ) 
AND status NOT IN (5,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
			  $i = 0;
		   foreach($ok as $k){
		   //$r_ok[$i] = array('x'=> $i, 'y' =>(int)$k->ctn , $type => 0 );
		    $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn, $type => 0 );
		   $i++;
		   }
			 die(json_encode($r_ok));
		case 'dely':
		$days_arr_dely = array();
	$days_dely = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT COUNT(  `ws_orders`.`id` ) AS ctn,  `ws_orders`.`delivery_type_id` 
FROM  `ws_orders` 
WHERE  STATUS IN ( 100, 9, 15,16 ) 
AND  `ws_orders`.`delivery_type_id` !=0 and `ws_orders`.`delivery_type_id` !=12
GROUP BY  `ws_orders`.`delivery_type_id` 
ORDER BY  `ctn` ASC");
$mas = array(3=>'Победа', 4=>'Укр.Почта', 5=>'Строителей', 8=>'НП:ОО', 9=>'Курьер', 16=>'НП:НП');
	foreach($days_dely as $d){
	$days_arr_dely['name'][] = $mas[$d->delivery_type_id];
	$days_arr_dely['koll'][] = (int)$d->ctn;
}
	die(json_encode($days_arr_dely));
	case 'status':
	$days_arr_status = array();
	$days_status = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT COUNT(  `ws_orders`.`id` ) AS ctn,  `ws_orders`.`status` 
FROM  `ws_orders` 
WHERE `ws_orders`.`status` IN (100,1,9,15,16) 
GROUP BY  `ws_orders`.`status` 
ORDER BY  `ws_orders`.`status` ASC");
$mas = array(100=>'Новый', 1=>'В процесе', 2=>'Отменён', 8=>'Оплачен', 9=>'Собран', 10=>'Продлён клиентом', 12=>'Ждёт возврат', 15=>'Собран 2', 16=>'Собран 3' );
$color = array(0=>'#4c4fd2',1=>'#d7da5c',2=>'#677489',8=>'#5B93D3',9=>'#37da3d', 15=>'#37c2da', 16=>'#dac037');
	foreach($days_status as $d){
	$days_arr_status[] = array('label'=>$mas[$d->status], data=>array(1,(int)$d->ctn), 'color'=>$color[$d->status]);
}
	
	die(json_encode($days_arr_status));
	case 'quick':
	$quick_arr = array();
	$quick = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT  `status` , COUNT(  `id` ) AS  `ctn` 
FROM  `ws_orders` 
WHERE  `from_quick` = 1
AND  `status` != 17
AND  `quick` =1
AND DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 6 
DAY ) 
GROUP BY  `status` ");
$mas = array(100=>'Новый', 1=>'В процесе', 2=>'Отменён', 8=>'Оплачен', 9=>'Собран', 10=>'Продлён клиентом', 12=>'Ждёт возврат', 15=>'Собран 2', 16=>'Собран 3' );
$color = array(0=>'#4c4fd2',1=>'#d7da5c',2=>'#677489',8=>'#5B93D3',9=>'#37da3d', 15=>'#37c2da', 16=>'#dac037');
	foreach($quick as $d){
	$days_arr_status[] = array('label'=>$mas[$d->status], data=>array(1,(int)$d->ctn), 'color'=>$color[$d->status]);
}
	
	die(json_encode($days_arr_status));
            default : 
			$ok = "SELECT DATE_FORMAT(  `date_create` ,  '%Y-%m-%d %H' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) 
AND status NOT IN (5,7,17) 
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d %H' ) 
ORDER BY  `dat` ASC ";
			$ok = wsActiveRecord::useStatic('Shoporders')->findByQuery($ok);
		   $i = 0;
		   foreach($ok as $k){
		   //$r_ok[$i] = array('x'=> $i, 'y' =>(int)$k->ctn , $type => 0 );
		    $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn, $type => 0 );
		   $i++;
		   }
		    die(json_encode($r_ok));
		   }
		  
		   }
		   
		   
	  $days_arr = array();
	$days = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%H' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit FROM  `ws_orders` WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) AND status IN (8,14) GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d %H' ) ORDER BY  `dat` ASC ");
	foreach($days as $d){
	$days_arr['am'][$d->dat] = $d->money;
	$days_arr['dep'][$d->dat] = $d->deposit;
	$days_arr['koll'][] = $d->deposit+$d->money;
}
	$week_arr = array();
	$week = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%d' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 
DAY ) 
AND status IN (8,14) 
GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
	foreach($week as $d){
	$week_arr['am'][$d->dat] = $d->money;
	$week_arr['dep'][$d->dat] = $d->deposit;
	$week_arr['koll'][] = $d->deposit+$d->money;
}
$month_arr = array();
$month = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND status IN (8,14) 
GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
	foreach($month as $d){
	$month_arr['am'][$d->dat] = $d->money;
	$month_arr['dep'][$d->dat] = $d->deposit;
	$month_arr['koll'][] = $d->deposit+$d->money;
}
$year_arr = array();
$year = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y' ) = DATE_FORMAT( NOW( ) ,  '%Y' ) 
AND status IN (8,14) 
GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m' ) 
ORDER BY  `dat` ASC ");
	foreach($year as $d){
	$year_arr['am'][$d->dat] = $d->money;
	$year_arr['dep'][$d->dat] = $d->deposit;
	$year_arr['koll'][] = $d->deposit+$d->money;
}

$koment_arr = array();
$koment = wsActiveRecord::useStatic('Shoporders')->findByQuery("SELECT * 
FROM  `ws_orders` 
WHERE  `ws_orders`.`status` = 100
AND  `id` NOT 
IN (

SELECT  `ws_order_remarks`.`order_id` 
FROM  `ws_order_remarks`
)
AND  `customer_id` NOT 
IN (

SELECT  `id` 
FROM  `ws_customers` 
WHERE  `customer_type_id` >1
)
AND  `comments` !=  ''
ORDER BY  `ws_orders`.`date_create` ASC");

$this->view->orders_koment = $koment;   

	$this->view->orders_days = $days_arr;   
	$this->view->orders_week = $week_arr;
	$this->view->orders_month = $month_arr;
	$this->view->orders_year = $year_arr;
	
	   
	

	echo $this->render('/template/views/home.php', 'index.php');
	
	}
    public function staticAction()
    {
	
        echo $this->render('static.tpl.php');
		
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

                /*if (!eregi('^[a-z0-9_.\-]+@[a-z0-9\-]+\.[a-z0-9.\-]+$', $date->email)) $errors['email'] = $this->trans->get("Email is invalid");*/
                /*  $allowed_chars = '1234567890';
                if (!Number::clearPhone($date->phone)) $errors['phone'] = $this->trans->get("Please enter your phone");
                $phone = $date->phone;
                for ($i = 0; $i < mb_strlen($phone); $i++) {
                    if (mb_strpos($allowed_chars, mb_strtolower($phone[$i])) === false) {
                        $errors['phone'] = "Номер телефона должен содержит только цифры";
                        break;
                    }
                }*/

                if (@$_FILES['image']['name']) {
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
        if ($this->user->isSuperAdmin())
            $this->view->pages->merge(Menu::findAdminMenu());

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
            echo $this->render('static.tpl.php');

        } elseif ($this->cur_menu->getParameter() == 'edit') {
            $page = new Menu($this->get->getId());
            /*if (!$page->getId()) {
                	
                    $page->action = "index";
                } elseif (!$page->getId() || !$page->getTypeId()==2){
                	$page->action = "admin";
                }*/

            //$this->view->roots = Menu::findTopMenu();

            $this->view->roots = wsActiveRecord::useStatic('Menu')->findAll(array("(type_id = 1 OR (type_id in (2,3,4,5,6,7) AND (parent_id = 0 OR parent_id IS NULL))) AND id <> '" . (int)$this->get->getId() . "'"));
			
            $this->view->menuTypes = wsActiveRecord::useStatic('wsMenuType')->findAll(array("id IN (1, 2, 3, 4, 5, 6, 7)"));

            /*if(!$page->getTypeId() && !$page->getId() && $this->user->isSuperAdmin())
                           $page->setTypeId(1);*/
            if (count($_POST)) {
                foreach ($_POST as &$value)
                    $value = stripslashes($value);
                $errors = array();
                if (!$page->getId() && !trim(@$_POST['url'])) {
                    $errors[] = $this->trans->get('Please fill in page ID');
                } else {
                    if (!@$_POST['name'])
                        $errors[] = $this->trans->get('Please fill in page title');
                    if (!@$_POST['action'])
                        $errors[] = $this->trans->get('Please fill in page action');

                    if ($this->user->isSuperAdmin())
                        $page->setNoDelete(0); //reset - to use with checkboxes

                    $page->import($_POST);

                    if ($page->getTypeId() == 2) {
                        $page->setParentId(4);
                    }

                    if (!$page->getId()) {
                        $ps = wsActiveRecord::useStatic('Menu')->findByUrl($page->getUrl());
                        if ($ps)
                            foreach ($ps as $p) {
                                if ($p == $page->getUrl()) {
                                    $errors[] = $this->trans->get('Page ID is already taken');
                                    break;
                                }
                            }

                        $tmp = wsActiveRecord::useStatic('Menu')->findLastSequenceRecord();
                        if ($tmp && $page->getTypeId() != 2)
                            $page->setSequence($tmp->getSequence() + 10);
                        else
                            $page->setSequence(10);
                    }

                    if (!$page->getTypeId())
                        $page->setTypeId(null); // should be null 1  or 2

                    if (@$_POST['rem_image1']) $page->setImage('');
                    elseif (@$_FILES['image1']) {
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
                                    if ($page->getImage())
                                        @unlink($page->getImage());
                                    $page->setImage($folder . $handle->file_dst_name);
                                    $handle->clean();
                                }
                            }
                        }
                    }


                    if (@$_POST['rem_image2']) $page->setImage2('');
                    elseif (@$_FILES['image2']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['image2'], 'ru_RU');
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
                                    if ($page->getImage2())
                                        @unlink($page->getImage2());
                                    $page->setImage2($folder . $handle->file_dst_name);
                                    $handle->clean();
                                }
                            }
                        }
                    }


                    if (@$_POST['rem_image3']) $page->setImage3('');
                    elseif (@$_FILES['image3']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['image3'], 'ru_RU');
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
                                    if ($page->getImage3())
                                        @unlink($page->getImage3());
                                    $page->setImage3($folder . $handle->file_dst_name);
                                    $handle->clean();
                                }
                            }
                        }
                    }


                    if (@$_POST['rem_image4']) $page->setImage4('');
                    elseif (@$_FILES['image4']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['image4'], 'ru_RU');
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
                                    if ($page->getImage4())
                                        @unlink($page->getImage4());
                                    $page->setImage4($folder . $handle->file_dst_name);
                                    $handle->clean();
                                }
                            }
                        }
                    }


                    if (@$_POST['rem_image5']) $page->setImage5('');
                    elseif (@$_FILES['image5']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['image5'], 'ru_RU');
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
                                    if ($page->getImage5())
                                        @unlink($page->getImage5());
                                    $page->setImage5($folder . $handle->file_dst_name);
                                    $handle->clean();
                                }
                            }
                        }
                    }

                    if (!$page->getParentId())
                        $page->setParentId(null);
                    if ($page->getId() == $page->getParentId())
                        $page->setParentId(null);

                    $page->action = (trim(@$_POST['action']));
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
                if ('moveup' == $this->cur_menu->getParameter())
                    $b = wsActiveRecord::useStatic('Menu')->findFirst("sequence < '{$a->getSequence()}'", array('sequence' => 'DESC'));
                else
                    $b = wsActiveRecord::useStatic('Menu')->findFirst("sequence > '{$a->getSequence()}'", array('sequence' => 'ASC'));
                if ($b && $b->getId()) {
                  //  $tmp = (int)$a->getSequence();
                    //$a->setSequence($b->getSequence());
                    //$b->setSequence($tmp);
                    //$a->save();
                    //$b->save();
                    $q = "update ws_menus set sequence = " . (int)$a->getSequence() . " where id = {$b->getId()}";
                    wsActiveRecord::query($q);
                    $q = "update ws_menus set sequence = " . (int)$b->getSequence() . " where id = {$a->getId()}";
                    wsActiveRecord::query($q);
                }
            }
            //d(SQLLogger::getInstance()->reportBySQL());
            $this->_redir('pages');
        } else
            $this->_redir('pages');

        //echo $this->render('page/list.tpl.php');
    }

    public function homeblocksAction()
    {
        $date = array();
        if (isset($_GET['block']) and (int)$_GET['block'] > 0) $date['block'] = (int)$_GET['block'];
        $this->view->pages = wsActiveRecord::useStatic('HomeBlock')->findAll($date, array('block' => 'ASC'));
        echo $this->render('page/blocklist.tpl.php');
    }

    public function homeblockAction()
    {

        if (isset($this->get->delete) and (int)$this->get->delete > 0) {
            $block = new HomeBlock((int)$this->get->delete);
            if ($block->getId()) { 
			    @unlink($block->getImage());
				@unlink($block->getImageUk());
			$block->destroy();
			}
            $this->_redir('homeblocks');
        }
        if (isset($this->get->edit)) {
            $block = new HomeBlock((int)$this->get->edit);
            if (count($_POST)) {
                $errors = array();
                foreach ($_POST as &$value)
                    $value = stripslashes($value);
                $block->import($_POST);
                if (@$_FILES['image']) {
                    require_once('upload/class.upload.php');
                    $handle = new upload($_FILES['image'], 'ru_RU');
                    $folder = Config::findByCode('bloks_folder')->getValue();
                    if ($handle->uploaded) {
                        if (!count($errors)) {
                            $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                            if ($handle->processed) {
                                if ($block->getImage())
                                    @unlink($block->getImage());
                                $block->setImage($folder . $handle->file_dst_name);
							if($_POST['block'] != 6) $block->setImageUk($folder . $handle->file_dst_name);
							if($_POST['block'] == 6 and !@$_FILES['image_uk']) $block->setImageUk($folder . $handle->file_dst_name);
                                $handle->clean();
                            }
                        }
                    }
                }
				if($_POST['block'] == 6){
				if (@$_FILES['image_uk']) {
                    require_once('upload/class.upload.php');
                    $handle = new upload($_FILES['image_uk'], 'ru_RU');
                    $folder = Config::findByCode('bloks_folder')->getValue();
                    if ($handle->uploaded) {

                        if (!count($errors)) {
                            $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                            if ($handle->processed) {
                                if ($block->getImage())
                                    @unlink($block->getImageUk());
                                $block->setImageUk($folder . $handle->file_dst_name);
                                $handle->clean();
                            }
                        }
                    }
                }
				}
				$block->setDate($_POST['date']);
				if(@$_POST['exitdate']){
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
        $this->view->images = wsActiveRecord::useStatic('wsFile')->findByFileTypeId(1);
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
            @unlink($file->getFilepath());
            $file->destroy();

            echo $this->render('static.tpl.php');

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
            if (!$n->getStatus())
                if (strtotime($n->getEndDatetime()) < time()) {
                    $n->setStatus(0);
                    $n->save();
                }
        }
        $this->view->news = $news;
		if($this->user->id == 8005 and false){ echo $this->render('template/views/news/list.tpl.php', 'index.php');}else{
		echo $this->render('news/list.tpl.php');
		}

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
            echo $this->render('static.tpl.php');
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
                                    @unlink($n->getImage());
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
			if($this->user->id == 8005 and false){ echo $this->render('template/views/news/edit.tpl.php');}else{
        echo $this->render('news/edit.tpl.php');
		}
            

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
		 //$news = wsActiveRecord::useStatic('NewsViews')->findFirst(array('id_news'=>(int)$this->post->id, 'user'=>$this->user->getId()))->getFlag();
		 //$news = wsActiveRecord::useStatic('NewsViews')->findById($this->get->getId();
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
        } else
            echo $this->render('news/list.admin.tpl.php');
    }

    public function newscleanupAction()
    {
        $news = wsActiveRecord::useStatic('News')->findByStatus(0);
        foreach ($news as $n) {
            $n->destroy();
        }
        echo $this->render('static.tpl.php');
    }

    //-------------------------------------------------------

	public function subscribersemailAction()
	{ 
	
	
	if($this->post->par){
	//die('tut');
	$i='';
	switch($this->post->par){
	case 'all': $i='all';
	$this->view->flag = 'generalmailing';
	$this->view->name = 'Общая';
	$this->view->semail = wsActiveRecord::useStatic('Emailpost')->findAll(array('all'=>1));
	break;
	case 'shop':
	$i='shop'; 
	$this->view->flag = 'shopmailing';
	$this->view->name = 'Магазинная';
	$this->view->semail = wsActiveRecord::useStatic('Emailpost')->findAll(array('shop'=>1));
	break;
	case 'men':
	$i='men';
	$this->view->flag = 'menmailing';
	$this->view->name = 'Мужская';
	$this->view->semail = wsActiveRecord::useStatic('Emailpost')->findAll(array('men'=>1));
	break;
	case 'women':
	$i='women';
	$this->view->flag = 'womenmailing';
	$this->view->name = 'Женская';
	$this->view->semail = wsActiveRecord::useStatic('Emailpost')->findAll(array('women'=>1));
	break;
	case 'baby':
	$i='baby';
	$this->view->flag = 'babymailing';
	$this->view->name = 'Детская';
	$this->view->semail = wsActiveRecord::useStatic('Emailpost')->findAll(array('baby'=>1));
	break;
	default: case 'all': $i='all';
	$this->view->flag = 'generalmailing';
	$this->view->name = 'Общая';
	$this->view->semail = wsActiveRecord::useStatic('Emailpost')->findAll(array('all'=>1));
	break;
	
	}
	 die(json_encode(array('send'=>$i, 'result' => $this->view->render('mailing/email_v.php'))));
                   // exit; 
    }
	
	if(@$this->post->preview){
				$subject = '';
	$j='fac';
	if(@$this->post->preview == 'view'){
	$content= '';
	$j='view';
	$post = wsActiveRecord::useStatic('Emailpost')->findById($this->post->id);
	
	$content = '<table border="0" cellpadding="0" cellspacing="0"  style="width:700px;" align="center">
	<tr>
				<td style="color:#383838; padding:0">
					<p>'.$post->intro.'</p>
				</td>
			</tr>
		<tr>
				<td style="color:#383838; padding:0">
					<p>'.$post->ending.'</p>
				</td>
			</tr>	
	</table>';
	$this->view->content = $content;
	$subject .= $post->subject_start ? $post->subject_start.' Test, '.$post->subject : $post->subject;
	//$result = $this->view->render('mailing/template.tpl.php');
	die(json_encode(array('send'=>$j,'result' => $this->render('', 'mailing/template_view.tpl.php'), 'subject'=>$subject)));
	}
	if(@$this->post->preview == 'dell'){
	$j='dell';
	$c = new Emailpost($this->post->id);
	$data = array();
	
            if ($c && $c->getId()) {
	if($c->getAll()==1) {$data[] = " all=1"; $t = 'all';}
	if($c->getShop()==1){$data[] = " shop=1"; $t = 'shop';}
	if($c->getMen()==1){$data[] = " men=1"; $t = 'men';}
	if($c->getWomen()==1){$data[] = " women=1"; $t = 'women';}
	if($c->getBaby()==1){$data[] = " baby=1"; $t = 'baby';}
			
                    $c->destroy();
					}
			$this->view->flag = $t.='mailing';		
			$this->view->semail = wsActiveRecord::useStatic('Emailpost')->findAll($data);
			//$result = $this->view->render('mailing/email_v.php');
die(json_encode(array('send'=>$j,'result' => $this->view->render('mailing/email_v.php'))));			
	} 
	
					//exit;
                }

			echo $this->render('mailing/subscribersemail.php');
	
	}
	
 public function generalmailingAction()
    {
		
        $this->view->post = (object)$_POST;

        if (count($_POST)) {
            if (isset($_POST['getarticles'])) {
                if (isset($_POST['id'])) {
                    $data = array();
                    $articles = wsActiveRecord::useStatic('Shoparticles')->findAll(array('category_id' => $_POST['id'], 'active' => 'y', 'stock > 0'));
                    if ($articles->count())
                        foreach ($articles as $article)
                            $data[] = array(
                                'id' => $article->getId(),
                                'title' => $article->getTitle() . " (" . Shoparticles::showPrice($article->getRealPrice()) . " грн)",
                                'img' => $article->getImagePath('detail')
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

            $errors = array();

			if(@$_POST['extra_url'])
                $this->view->extra_url = '?' . $_POST['extra_url'];
				

            if (!@$_POST['subject'])
                $errors[] = $this->trans->get('Пожалуйста, вваедите тему письма!');
            // if (!@$_POST['intro'])
            // $errors[] = $this->trans->get('Please fill in intro');
            //if(!count(@$_POST['anons']))
            //$errors[] = $this->trans->get('Please select announcements');

            if (@$_POST['test_email'] && @$_POST['send_test'] && !$this->isValidEmail($_POST['test_email']))
                $errors[] = $this->trans->get('Please fill in valid test email');

            if (!count($errors)) {
                $cnt = 0;
				$er = 0;
				
				$subject_start = '';
					if(@$_POST['subject_start']) $subject_start = $_POST['subject_start'];
			   $subject ='';
					if(@$_POST['subject']) $subject = $_POST['subject'];

                if(@$_POST['save']==2){
				$s = new Emailpost();
				$s->setCtime(date('Y-m-d H:i:s'));
				//$s->setGo(NULL);
				$s->setIdCustomerNew($this->user->getId());
				$s->setAll(1);
				$s->setSubjectStart($_POST['subject_start']);
				$s->setSubject($_POST['subject']);
				if(@$_POST['intro']) $s->setIntro($this->post->intro);
				if(@$_POST['ending']) $s->setEnding($this->post->ending);
				if(@$this->post->article_id){
					$text='';				
				foreach($this->post->article_id as $item ){ $text.= $item.',';}
				$s->setArticleId($text);
				}
				$s->save();
				 die(json_encode(array('status' => 'send', 'ok'=>'ok')));
				 exit;
				}elseif (@$_POST['test']==1) {
				if(isset($_POST['s_start']) and $_POST['s_start'] == 1){ $subject = $subject_start.', TEST, '.$subject; }
				
				if(isset($_POST['copy']) and isset($_POST['copy_email'])){
				if($_POST['copy'] == 1) $copy = 1;
				if($_POST['copy'] == 2) $copy = 2;
				}else{
				$copy = 2;
				}
                    $this->view->name = 'Testing';
                    $this->view->email = $_POST['test_email'];
                    $msg = $this->view->render('mailing/general-email.tpl.php');

		SendMail::getInstance()->sendSubEmail($_POST['test_email'], 'Testing', $subject, $msg, '','','', '', $copy, 'management@red.ua', 'Ирина');
		
die(json_encode(array('status' => 'send', 'from' => $_POST['test_email'])));
			unset($_POST['test']);
			exit;
                } elseif (isset($_POST['preview'])) {
				
                    echo $this->view->render('mailing/general-email.tpl.php');
					//print_r($_POST);
                    exit; 
                }elseif(@$this->post->go == 0) {
				if($this->post->from_mail == 0){
				if(@$this->post->id_post){
				$s = new Emailpost($this->post->id_post);
				$s->setGo(date('Y-m-d H:i:s'));
				$s->setIdCustomerGo($this->user->getId());
				$s->save();
				}else{
				$s = new Emailpost();
				$s->setCtime(date('Y-m-d H:i:s'));
				$s->setGo(date('Y-m-d H:i:s'));
				$s->setIdCustomerNew($this->user->getId());
				$s->setIdCustomerGo($this->user->getId());
				$s->setAll(1);
				$s->setSubjectStart($subject_start);
				$s->setSubject($subject);
				if(@$_POST['intro']) $s->setIntro($this->post->intro);
				if(@$_POST['ending']) $s->setEnding($this->post->ending);
				//if(@$this->post->article_id){
				//	$text='';				
				//foreach($this->post->article_id as $item ){ $text.= $item.',';}
				//$s->setArticleId($text);
				//}
				$s->save();
				}
				}
                    $count = $this->post->count;
					$emails = '';
										
				foreach (wsActiveRecord::useStatic('Subscriber')->findAll(array('active' => 1, 'confirmed is not null','baby'=>0, 'men'=>0, 'women'=>0, 'shop'=>0), array(), array($this->post->from_mail, $count)) as $sub){  
				if (isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){
				$subject_new = '';
				if(isset($_POST['s_start']) and $_POST['s_start'] == 1){
				$subject_new = $subject_start.', '.$sub->getName().', '.$subject;
				}
				if($subject_new == '') $subject_new = $subject;

                       // wsLog::add('Sending e-mail: ' . $sub->getEmail(), 'EMAIL');
						$this->view->openimg = 'https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1&cid='.$sub->getId().'&t=event&ec=email_letter_open_'.date('d.m.Y').'&ea=open&el='.$sub->getId().'&cs=email_letter_open_'.date('d.m.Y').'&cm=email&cn=EmailLetter';
                        $this->view->name = $sub->getName();
                        $this->view->email = $sub->getEmail();
                        $msg = $this->view->render('mailing/general-email.tpl.php');
                        $emails .= $sub->getEmail() . ', ';
						$res = SendMail::getInstance()->sendSubEmail($sub->getEmail(), $sub->getName(), $subject_new, $msg);
							$cnt++;
						}else{
						$er++;
						//$emails .= $sub->getEmail() . ', ';
						wsLog::add('E-mail error: ' . $sub->getEmail(), 'EMAIL');
						}
						
						
						 
                    }
				   
    die(json_encode(array('status' => 'send', 'from' => $this->post->from_mail, 'count' => $count, 'emails' => $emails, 'cnt'=>$cnt, 'er'=>$er)));
                    
                }

                $this->view->saved = $cnt;
                //unset($this->view->errors);
            } else {
                $this->view->errors = $errors;
            }
        }
	if(@$this->get->getId()) $this->view->pemail = wsActiveRecord::useStatic('Emailpost')->findById($this->get->getId());
        echo $this->render('/mailing/general.tpl.php');
		
    }
	
	public function menmailingAction()
	{

		
		$this->view->post = (object)$_POST;
		
		if (count($_POST)) {
            if (isset($_POST['getarticles'])) {
                if (isset($_POST['id'])) {
                    $data = array();
                    $articles = wsActiveRecord::useStatic('Shoparticles')->findAll(array('category_id' => $_POST['id'], 'active' => 'y', 'stock > 0'));
                    if ($articles->count())
                        foreach ($articles as $article)
                            $data[] = array(
                                'id' => $article->getId(),
                                'title' => $article->getTitle() . " (" . Shoparticles::showPrice($article->getRealPrice()) . " грн)",
                                'img' => $article->getImagePath('detail')
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

            $errors = array();

			if(@$_POST['extra_url'])
                $this->view->extra_url = '?' . $_POST['extra_url'];

            if (!@$_POST['subject'])
                $errors[] = $this->trans->get('Пожалуйста, вваедите тему письма!');
           

            if (@$_POST['test_email'] && @$_POST['send_test'] && !$this->isValidEmail($_POST['test_email']))
                $errors[] = $this->trans->get('Please fill in valid test email');

            if (!count($errors)) {
                $cnt = 0;
				$er = 0;

				$subject_start = $_POST['subject_start'];
				
				$subject = $_POST['subject'];

                
				if(@$_POST['save']==2){
				$s = new Emailpost();
				$s->setCtime(date('Y-m-d H:i:s'));
				//$s->setUtime(NULL);
				$s->setIdCustomerNew($this->user->getId());
				$s->setMen(1);
				$s->setSubjectStart($_POST['subject_start']);
				$s->setSubject($_POST['subject']);
				if(@$_POST['intro']) $s->setIntro($this->post->intro);
				if(@$_POST['ending']) $s->setEnding($this->post->ending);
				if(@$this->post->article_id){
					$text='';				
				foreach($this->post->article_id as $item ){ $text.= $item.',';}
				$s->setArticleId($text);
				}
				$s->save();
				 die(json_encode(array('status' => 'send', 'ok'=>'ok')));
				 exit;
				}elseif(@$_POST['test'] == 1) {
					if(isset($_POST['s_start']) and $_POST['s_start'] == 1){
				$subject = $subject_start.', TEST, '.$subject;
				}
				if(isset($_POST['copy'])){
				if($_POST['copy'] == 1) $copy = 1;
				if($_POST['copy'] == 2) $copy = 2;
				}else{
				$copy = 2;
				}
                    $this->view->name = 'Testing';
                    $this->view->email = $_POST['test_email'];
                    $msg = $this->view->render('mailing/general-email.tpl.php');

			SendMail::getInstance()->sendSubEmail($_POST['test_email'], 'Testing', $subject, $msg, '','','', '', $copy, 'management@red.ua', 'Ирина');
           // MailerNew::getInstance()->sendToEmailSub($_POST['test_email'], $admin_name, $subject, $msg);

			die(json_encode(array('status' => 'send', 'from' => $_POST['test_email'])));
			unset($_POST['test']);
					exit;
                } elseif (isset($_POST['preview'])) {
                    echo $this->view->render('mailing/general-email.tpl.php');
                    exit;
                } 
				elseif (@$_POST['go'] == 0) {
				if($this->post->from_mail == 0){
				if(@$_POST['id_post']){
				$s = new Emailpost($_POST['id_post']);
				$s->setGo(date('Y-m-d H:i:s'));
				$s->setIdCustomerGo($this->user->getId());
				$s->save();
				}else{
				$s = new Emailpost();
				$s->setCtime(date('Y-m-d H:i:s'));
				$s->setGo(date('Y-m-d H:i:s'));
				$s->setIdCustomerNew($this->user->getId());
				$s->setIdCustomerGo($this->user->getId());
				$s->setMen(1);
				$s->setSubjectStart($_POST['subject_start']);
				$s->setSubject($_POST['subject']);
				if(@$_POST['intro']) $s->setIntro($this->post->intro);
				if(@$_POST['ending']) $s->setEnding($this->post->ending);
				if(@$this->post->article_id){
					$text='';				
				foreach($this->post->article_id as $item ){ $text.= $item.',';}
				$s->setArticleId($text);
				}
				$s->save();

				}
				}
                    $count = $this->post->count;
					$emails = '';	
				foreach (wsActiveRecord::useStatic('Subscriber')->findAll(array('active' => 1, 'confirmed is not null','men'=>1), array(), array($this->post->from_mail, $count)) as $sub){  
				if (isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){
				
				$subject_new = '';
				
				if(isset($_POST['s_start']) and $_POST['s_start'] == 1){
				$subject_new = $subject_start.', '.$sub->getName().', '.$subject;
				}
				if($subject_new == '') $subject_new = $subject;
				
                       // wsLog::add('Sending email to ' . $sub->getEmail(), 'EMAIL');
                        $this->view->name = $sub->getName();
                        $this->view->email = $sub->getEmail();
						
						
						$this->view->openimg = 'https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1&cid='.$sub->getId().'&t=event&ec=men_subscriber_open_'.date('d.m.Y').'&ea=open&el='.$sub->getId().'&cs=men_subscriber_open_'.date('d.m.Y').'&cm=email&cn=Men_Subscriber';
                        $msg = $this->view->render('mailing/general-email.tpl.php');

                       // MailerNew::getInstance()->sendToEmailSub($sub->getEmail(), $sub->getName(), $subject_new, $msg, $new = 1);
						$res = SendMail::getInstance()->sendSubEmail($sub->getEmail(), $sub->getName(), $subject_new, $msg);
						$emails .= $sub->getEmail() . ', ';
                        $cnt++;
						}else{
						//$emails .= $sub->getEmail() . ', ';
						$er++;
						wsLog::add('E-mail error: ' . $sub->getEmail(), 'EMAIL');
						}
						
						
                    }
    die(json_encode(array('status' => 'send', 'from' => $this->post->from_mail, 'count' => $count, 'emails' => $emails)));
                }

                $this->view->saved = $cnt;

            } else {
                $this->view->errors = $errors;
            }
        }

		if(@$this->get->getId()) $this->view->pemail = wsActiveRecord::useStatic('Emailpost')->findById($this->get->getId());
		
		echo $this->render('mailing/general_men.tpl.php');
		
	}
	
	public function womenmailingAction()
	{
		$this->view->post = (object)$_POST;
		
		if (count($_POST)) {
            if (isset($_POST['getarticles'])) {
                if (isset($_POST['id'])) {
                    $data = array();
                    $articles = wsActiveRecord::useStatic('Shoparticles')->findAll(array('category_id' => $_POST['id'], 'active' => 'y', 'stock > 0'));
                    if ($articles->count())
                        foreach ($articles as $article)
                            $data[] = array(
                                'id' => $article->getId(),
                                'title' => $article->getTitle() . " (" . Shoparticles::showPrice($article->getRealPrice()) . " грн)",
                                'img' => $article->getImagePath('detail')
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

            $errors = array();

			if(@$_POST['extra_url'])
                $this->view->extra_url = '?' . $_POST['extra_url'];

            if (!@$_POST['subject'])
                $errors[] = $this->trans->get('Пожалуйста, вваедите тему письма!');
            

            if (@$_POST['test_email'] && @$_POST['send_test'] && !$this->isValidEmail($_POST['test_email']))
                $errors[] = $this->trans->get('Please fill in valid test email');

            if (!count($errors)) {
                $cnt = 0;

				$subject_start = $_POST['subject_start'];
			   $subject = $_POST['subject'];

                 if(@$_POST['save']==2){
				$s = new Emailpost();
				$s->setCtime(date('Y-m-d H:i:s'));
				//$s->setUtime(NULL);
				$s->setIdCustomerNew($this->user->getId());
				$s->setWomen(1);
				$s->setSubjectStart($_POST['subject_start']);
				$s->setSubject($_POST['subject']);
				if(@$_POST['intro']) $s->setIntro($this->post->intro);
				if(@$_POST['ending']) $s->setEnding($this->post->ending);
				if(@$this->post->article_id){
					$text='';				
				foreach($this->post->article_id as $item ){ $text.= $item.',';}
				$s->setArticleId($text);
				}
				$s->save();
				 die(json_encode(array('status' => 'send', 'ok'=>'ok')));
				 exit;
				}elseif (@$_POST['test'] == 1) {
				if(isset($_POST['s_start']) and $_POST['s_start'] == 1){
				$subject = $subject_start.', TEST, '.$subject;
				}
				if(isset($_POST['copy'])){
				if($_POST['copy'] == 1) $copy = 1;
				if($_POST['copy'] == 2) $copy = 2;
				}else{
				$copy = 2;
				}
                    $this->view->name = 'Testing';
                    $this->view->email = $_POST['test_email'];
                    $msg = $this->view->render('mailing/general-email.tpl.php');

		SendMail::getInstance()->sendSubEmail($_POST['test_email'], 'Testing', $subject, $msg, '','','', '', $copy, 'management@red.ua', 'Ирина');
                   // MailerNew::getInstance()->sendToEmailSub($_POST['test_email'], $admin_name, $subject, $msg);
                   // MailerNew::getInstance()->sendToEmailSub('management@red.ua', $admin_name, $subject, $msg);
			die(json_encode(array('status' => 'send', 'from' => $_POST['test_email'])));
			unset($_POST['test']);
			exit;
                } elseif (isset($_POST['preview'])) {
                    echo $this->view->render('mailing/general-email.tpl.php');
                    exit;
                }elseif (@$_POST['go'] == 0) {
				if($this->post->from_mail == 0){
				if(@$_POST['id_post']){
				$s = new Emailpost($_POST['id_post']);
				$s->setGo(date('Y-m-d H:i:s'));
				$s->setIdCustomerGo($this->user->getId());
				$s->save();
				}else{
				$s = new Emailpost();
				$s->setCtime(date('Y-m-d H:i:s'));
				$s->setGo(date('Y-m-d H:i:s'));
				$s->setIdCustomerNew($this->user->getId());
				$s->setIdCustomerGo($this->user->getId());
				$s->setWomen(1);
				$s->setSubjectStart($_POST['subject_start']);
				$s->setSubject($_POST['subject']);
				if(@$_POST['intro']) $s->setIntro($this->post->intro);
				if(@$_POST['ending']) $s->setEnding($this->post->ending);
				if(@$this->post->article_id){
					$text='';				
				foreach($this->post->article_id as $item ){ $text.= $item.',';}
				$s->setArticleId($text);
				}
				$s->save();

				}
				}
                    $count = $this->post->count;
					$emails = '';	
				foreach (wsActiveRecord::useStatic('Subscriber')->findAll(array('active' => 1, 'confirmed is not null','women'=>1), array(), array($this->post->from_mail, $count)) as $sub){  
				if (isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){
								$subject_new = '';
				if(isset($_POST['s_start']) and $_POST['s_start'] == 1){
				$subject_new = $subject_start.', '.$sub->getName().', '.$subject;
				}
				if($subject_new == '') $subject_new = $subject;
				
                       // wsLog::add('Sending email to ' . $sub->getEmail(), 'EMAIL');
                        $this->view->name = $sub->getName();
                        $this->view->email = $sub->getEmail();
						$this->view->openimg = 'https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1&cid='.$sub->getId().'&t=event&ec=women_subscriber_open_'.date('d.m.Y').'&ea=open&el='.$sub->getId().'&cs=women_subscriber_open_'.date('d.m.Y').'&cm=email&cn=Women_Subscriber';
                        
						$msg = $this->view->render('mailing/general-email.tpl.php');
						$emails .= $sub->getEmail() . ', ';
                  $res =  SendMail::getInstance()->sendSubEmail($sub->getEmail(), $sub->getName(), $subject_new, $msg);     
                      //  MailerNew::getInstance()->sendToEmailSub($sub->getEmail(), $sub->getName(), $subject_new, $msg, $new = 1);
                       $cnt++;
						}else{
						wsLog::add('E-mail error: ' . $sub->getEmail(), 'EMAIL');
						}
						 
                    }

    die(json_encode(array('status' => 'send', 'from' => $this->post->from_mail, 'count' => $count, 'emails' => $emails)));
                }

                $this->view->saved = $cnt;

            } else {
                $this->view->errors = $errors;
            }
        }
		if(@$this->get->getId()) $this->view->pemail = wsActiveRecord::useStatic('Emailpost')->findById($this->get->getId());
		echo $this->render('mailing/general_women.tpl.php');
		
	}
	
	public function babymailingAction()
	{
		
		$this->view->post = (object)$_POST;
		
		if (count($_POST)) {
            if (isset($_POST['getarticles'])) {
                if (isset($_POST['id'])) {
                    $data = array();
                    $articles = wsActiveRecord::useStatic('Shoparticles')->findAll(array('category_id' => $_POST['id'], 'active' => 'y', 'stock > 0'));
                    if ($articles->count())
                        foreach ($articles as $article)
                            $data[] = array(
                                'id' => $article->getId(),
                                'title' => $article->getTitle() . " (" . Shoparticles::showPrice($article->getRealPrice()) . " грн)",
                                'img' => $article->getImagePath('detail')
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

            $errors = array();

			if(@$_POST['extra_url'])
                $this->view->extra_url = '?' . $_POST['extra_url'];

            if (!@$_POST['subject'])
                $errors[] = $this->trans->get('Пожалуйста, вваедите тему письма!');
            

            if (@$_POST['test_email'] && @$_POST['send_test'] && !$this->isValidEmail($_POST['test_email']))
                $errors[] = $this->trans->get('Please fill in valid test email');

            if (!count($errors)) {
                $cnt = 0;

               
				$subject_start = $_POST['subject_start'];
			  
				$subject = $_POST['subject'];



                 if(@$_POST['save']==2){
				$s = new Emailpost();
				$s->setCtime(date('Y-m-d H:i:s'));
				//$s->setUtime(NULL);
				$s->setIdCustomerNew($this->user->getId());
				$s->setBaby(1);
				$s->setSubjectStart($_POST['subject_start']);
				$s->setSubject($_POST['subject']);
				if(@$_POST['intro']) $s->setIntro($this->post->intro);
				if(@$_POST['ending']) $s->setEnding($this->post->ending);
				if(@$this->post->article_id){
					$text='';				
				foreach($this->post->article_id as $item ){ $text.= $item.',';}
				$s->setArticleId($text);
				}
				$s->save();
				 die(json_encode(array('status' => 'send', 'ok'=>'ok')));
				 exit;
				}elseif (@$_POST['test'] == 1) {
				if(isset($_POST['s_start']) and $_POST['s_start'] == 1){
				$subject = $subject_start.', TEST, '.$subject;
				}
				if(isset($_POST['copy'])){
				if($_POST['copy'] == 1) $copy = 1;
				if($_POST['copy'] == 2) $copy = 2;
				}else{
				$copy = 2;
				}
				
                    $this->view->name = 'Testing';
                    $this->view->email = $_POST['test_email'];
                    $msg = $this->view->render('mailing/general-email.tpl.php');
		SendMail::getInstance()->sendSubEmail($_POST['test_email'], 'Testing', $subject, $msg, '','','', '', $copy, 'management@red.ua', 'Ирина');
                   /// MailerNew::getInstance()->sendToEmailSub($_POST['test_email'], $admin_name, $subject, $msg);
                // MailerNew::getInstance()->sendToEmailSub('management@red.ua', $admin_name, $subject, $msg);
die(json_encode(array('status' => 'send', 'from' => $_POST['test_email'])));
			unset($_POST['test']);
			exit;
} elseif (isset($_POST['preview'])) {
                    echo $this->view->render('mailing/general-email.tpl.php');
                    exit;
                }elseif (@$_POST['go'] == 0) {
				if($this->post->from_mail == 0){
				if(@$_POST['id_post']){
				$s = new Emailpost($_POST['id_post']);;
				$s->setGo(date('Y-m-d H:i:s'));
				$s->setIdCustomerGo($this->user->getId());
				$s->save();
				}else{
				$s = new Emailpost();
				$s->setCtime(date('Y-m-d H:i:s'));
				$s->setGo(date('Y-m-d H:i:s'));
				$s->setIdCustomerNew($this->user->getId());
				$s->setIdCustomerGo($this->user->getId());
				$s->setBaby(1);
				$s->setSubjectStart($_POST['subject_start']);
				$s->setSubject($_POST['subject']);
				if(@$_POST['intro']) $s->setIntro($this->post->intro);
				if(@$_POST['ending']) $s->setEnding($this->post->ending);
				/*if(@$this->post->article_id){
					$text='';				
				foreach($this->post->article_id as $item ){ $text.= $item.',';}
				$s->setArticleId($text);
				}*/
				$s->save();

				}
				}
                    $count = $this->post->count;
					$emails = '';	
				foreach (wsActiveRecord::useStatic('Subscriber')->findAll(array('active' => 1, 'confirmed is not null','baby'=>1), array(), array($this->post->from_mail, $count)) as $sub){  
				if (isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){
				$subject_new = '';
				if(isset($_POST['s_start']) and $_POST['s_start'] == 1){
				$subject_new = $subject_start.', '.$sub->getName().', '.$subject;
				}
				if($subject_new == '') $subject_new = $subject;

                      //  wsLog::add('Sending email to ' . $sub->getEmail(), 'EMAIL');
                        $this->view->name = $sub->getName();
                        $this->view->email = $sub->getEmail();
						$this->view->openimg = 'https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1&cid='.$sub->getId().'&t=event&ec=baby_subscriber_open_'.date('d.m.Y').'&ea=open&el='.$sub->getId().'&cs=baby_subscriber_open_'.date('d.m.Y').'&cm=email&cn=Baby_Subscriber';
                        $msg = $this->view->render('mailing/general-email.tpl.php');
						$emails .= $sub->getEmail() . ', ';
			$res = SendMail::getInstance()->sendSubEmail($sub->getEmail(), $sub->getName(), $subject_new, $msg);   
                        //MailerNew::getInstance()->sendToEmailSub($sub->getEmail(), $sub->getName(), $subject_new, $msg, $new = 1);
						 $cnt++;
						}else{
					//	wsLog::add('E-mail error: ' . $sub->getEmail(), 'EMAIL');
						}
                       
                    }

    die(json_encode(array('status' => 'send', 'from' => $this->post->from_mail, 'count' => $count, 'emails' => $emails)));
                }

                $this->view->saved = $cnt;

            } else {
                $this->view->errors = $errors;
            }
        }
		if(@$this->get->getId()) $this->view->pemail = wsActiveRecord::useStatic('Emailpost')->findById($this->get->getId());
		echo $this->render('mailing/general_baby.tpl.php');
		
	}
	
	public function shopmailingAction()
	{
		
		$this->view->post = (object)$_POST;
		
		if (count($_POST)) {
            if (isset($_POST['getarticles'])) {
                if (isset($_POST['id'])) {
                    $data = array();
                    $articles = wsActiveRecord::useStatic('Shoparticles')->findAll(array('category_id' => $_POST['id'], 'active' => 'y', 'stock > 0'));
                    if ($articles->count())
                        foreach ($articles as $article)
                            $data[] = array(
                                'id' => $article->getId(),
                                'title' => $article->getTitle() . " (" . Shoparticles::showPrice($article->getRealPrice()) . " грн)",
                                'img' => $article->getImagePath('detail')
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

            $errors = array();

			if(@$_POST['extra_url'])
                $this->view->extra_url = '?' . $_POST['extra_url'];

            if (!@$_POST['subject'])
                $errors[] = $this->trans->get('Пожалуйста, вваедите тему письма!');
     

            if (@$_POST['test_email'] && @$_POST['send_test'] && !$this->isValidEmail($_POST['test_email']))
                $errors[] = $this->trans->get('Please fill in valid test email');

            if (!count($errors)) {
                $cnt = 0;
				$er = 0;

				//$subject_start = $_POST['subject_start'];
			  
			  $subject = $_POST['subject'];
              
                 if(@$_POST['save']==2){
				$s = new Emailpost();
				$s->setCtime(date('Y-m-d H:i:s'));
				//$s->setUtime(NULL);
				$s->setIdCustomerNew($this->user->getId());
				$s->setShop(1);
				$s->setSubject($_POST['subject']);
				if(@$_POST['intro']) $s->setIntro($this->post->intro);
				if(@$_POST['ending']) $s->setEnding($this->post->ending);
				if(@$this->post->article_id){
					$text='';				
				foreach($this->post->article_id as $item ){ $text.= $item.',';}
				$s->setArticleId($text);
				}
				$s->save();
				 die(json_encode(array('status' => 'send', 'ok'=>'ok')));
				 exit;
				}elseif (@$_POST['test'] == 1) {
					//if (isset($_POST['send_test'])) {
                    $this->view->name = 'Testing';
                    $this->view->email = 'test@email.com';
                    $msg = $this->view->render('mailing/template_all.tpl.php');
					
					if(isset($_POST['copy'])){
				if($_POST['copy'] == 1) $copy = 1;
				if($_POST['copy'] == 2) $copy = 2;
				}else{
				$copy = 2;
				}
		SendMail::getInstance()->sendSubEmail($_POST['test_email'], 'Testing', $subject, $msg, '','','', '', $copy, 'management@red.ua', 'Ирина');
           // MailerNew::getInstance()->sendToEmailSub($_POST['test_email'], $admin_name, $subject, $msg);
            //MailerNew::getInstance()->sendToEmailSub('management@red.ua', $admin_name, $subject, $msg);
die(json_encode(array('status' => 'send', 'from' => $_POST['test_email'])));
			unset($_POST['test']);
				
					exit;
                }elseif(isset($_POST['preview'])) {
                    echo $this->view->render('mailing/template_all.tpl.php');
                    exit;
                }elseif (@$_POST['go'] == 0) {
				if($this->post->from_mail == 0){
				if(@$_POST['id_post']){
				$s = new Emailpost($_POST['id_post']);
				$s->setGo(date('Y-m-d H:i:s'));
				$s->setIdCustomerGo($this->user->getId());
				$s->save();
				}else{
				$s = new Emailpost();
				$s->setCtime(date('Y-m-d H:i:s'));
				$s->setGo(date('Y-m-d H:i:s'));
				$s->setIdCustomerNew($this->user->getId());
				$s->setIdCustomerGo($this->user->getId());
				$s->setShop(1);
				$s->setSubject($subject);
				if(@$_POST['intro']) $s->setIntro($this->post->intro);
				if(@$_POST['ending']) $s->setEnding($this->post->ending);
				/*if(@$this->post->article_id){
					$text='';				
				foreach($this->post->article_id as $item ){ $text.= $item.',';}
				$s->setArticleId($text);
				}*/
				$s->save();

				}
				}
                    $count = $this->post->count;
					$emails = '';	
				foreach (wsActiveRecord::useStatic('Subscriber')->findAll(array('active' => 1, 'confirmed is not null','baby'=>0, 'men'=>0, 'women'=>0, 'shop'=>1), array(), array($this->post->from_mail, $count)) as $sub){  
				if (isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){

                     //   wsLog::add('Sending email to ' . $sub->getEmail(), 'EMAIL');
                        $this->view->name = $sub->getName();
                        $this->view->email = $sub->getEmail();
						$this->view->openimg = 'https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1&cid='.$sub->getId().'&t=event&ec=shop_subscriber_open_'.date('d.m.Y').'&ea=open&el='.$sub->getId().'&cs=shop_subscriber_open_'.date('d.m.Y').'&cm=email&cn=Shop_Subscriber';
                        $msg = $this->view->render('mailing/template_all.tpl.php');
						
					$res = SendMail::getInstance()->sendSubEmail($sub->getEmail(), $sub->getName(), $subject, $msg); 
					
					$cnt++;
						$emails .= $sub->getEmail() . ', ';
						}else{
						$er++;
						wsLog::add('E-mail error: ' . $sub->getEmail(), 'EMAIL');
						}
						
						 
                       
                    } 
    die(json_encode(array('status' => 'send', 'from' => $this->post->from_mail, 'count' => $count, 'emails' => $emails, 'cnt'=>$cnt, 'er'=>$er)));
                }

                $this->view->saved = $cnt;

            } else {
                $this->view->errors = $errors;
            }
        }
		if(@$this->get->getId()) $this->view->pemail = wsActiveRecord::useStatic('Emailpost')->findById($this->get->getId());
		echo $this->render('mailing/general_shop.tpl.php');
		
	}
	
	public function oldusermailingAction()
	{
		
		$this->view->post = (object)$_POST;
		
		if (count($_POST)) {
            if (isset($_POST['getarticles'])) {
                if (isset($_POST['id'])) {
                    $data = array();
                    $articles = wsActiveRecord::useStatic('Shoparticles')->findAll(array('category_id' => $_POST['id'], 'active' => 'y', 'stock > 0'));
                    if ($articles->count())
                        foreach ($articles as $article)
                            $data[] = array(
                                'id' => $article->getId(),
                                'title' => $article->getTitle() . " (" . Shoparticles::showPrice($article->getRealPrice()) . " грн)",
                                'img' => $article->getImagePath('detail')
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

            $errors = array();

			if(@$_POST['extra_url'])
                $this->view->extra_url = '?' . $_POST['extra_url'];

            if (!@$_POST['subject'])
                $errors[] = $this->trans->get('Пожалуйста, вваедите тему письма!');
            

            if (@$_POST['test_email'] && @$_POST['send_test'] && !$this->isValidEmail($_POST['test_email']))
                $errors[] = $this->trans->get('Please fill in valid test email');

            if (!count($errors)) {
                $cnt = 0;
				$er = 0;
				//$subject_start = $_POST['subject_start'];
			   
			   $subject = $_POST['subject'];

                if (@$_POST['test'] == 1) {
				
				//$key = 'uhaehcv9ok';
						// $this->view->login = $this->encode($this->user->getUsername(),$key);
						//$this->view->pass = $this->encode($this->user->getPassword(),$key);
					//if (isset($_POST['send_test'])) {
                    $this->view->name = 'Testing';
                    $this->view->email = $_POST['test_email'];
					
                    $msg = $this->view->render('mailing/general-email.tpl.php');

				if(isset($_POST['copy'])){
				if($_POST['copy'] == 1) $copy = 1;
				if($_POST['copy'] == 2) $copy = 2;
				}else{
				$copy = 2;
				}
				
		SendMail::getInstance()->sendSubEmail($_POST['test_email'], 'Testing', $subject, $msg, '','','', '', $copy, 'management@red.ua', 'Ирина');
die(json_encode(array('status' => 'send', 'from' => $_POST['test_email'])));
			unset($_POST['test']);
			exit;
                } elseif (isset($_POST['preview'])) {
				
				//$key = 'uhaehcv9ok';
					//	 $this->view->login = $this->encode($this->user->getUsername(),$key);
					//	$this->view->pass = $this->encode($this->user->getPassword(),$key);
                   //echo $this->view->render('mailing/general-email.tpl.php');
				   echo $this->view->render('email/template_new.tpl.php');
                    exit;
                } 
				elseif (@$_POST['go'] == 0) {
                    $count = $this->post->count;
					$emails = '';	
				foreach (wsActiveRecord::useStatic('Customer')->findAll(array('time_zone_id' => 5), array(), array($this->post->from_mail, $count)) as $sub){
							//$key = 'uhaehcv9ok';
						 //$this->view->login = $this->encode($sub->getUsername(),$key);
						//$this->view->pass = $this->encode($sub->getPassword(),$key);
						
						if(isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){

                        wsLog::add('Sending email to ' . $sub->getEmail(), 'EMAIL');
                        $this->view->name = $sub->getFirstName();
                        $this->view->email = $sub->getEmail();
						$this->view->openimg = 'https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1&cid='.$sub->getId().'&t=event&ec=return_user_email_open_'.date('d.m.Y').'&ea=open&el='.$sub->getId().'&cs=return_user_email_open_'.date('d.m.Y').'&cm=email&cn=return_user_email';

					   $msg = $this->view->render('mailing/general-email.tpl.php');
					   $emails .= $sub->getEmail() . ', ';
			SendMail::getInstance()->sendSubEmail($sub->getEmail(), $sub->getName(), $subject, $msg); 
                        $cnt++;
						}else{
						$er++;
					//	wsLog::add('Error Sending email to ' . $sub->getEmail(), 'EMAIL');
						}
                    }
    die(json_encode(array('status' => 'send', 'from' => $this->post->from_mail, 'count' => $count, 'emails' => $emails, 'cnt'=>$cnt, 'er'=>$er)));
                }

                $this->view->saved = $cnt;

            } else {
                $this->view->errors = $errors;
            }
        }
		
		echo $this->render('mailing/general_olduser.tpl.php');
		
	}
   
	
	//sms розсылка
	public function smsmailingAction(){
	$this->view->post = (object)$_POST;
		
		if (count($_POST)) {
		$cnt=0;
		$errors = array();

        if (!@$_POST['subject']) $errors[] = 'Пожалуйста, вваедите сообщение!';
		if(@$this->post->balance){
		require_once('alphasms/smsclient.class.php');
		$sms = new SMSClient(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue(), Config::findByCode('sms_key')->getValue());
		$balance = (int)$sms->getBalance();
		die(json_encode(array('status' => 'send', 'ms'=>'Баланс AlphaSMS '.$balance.' грн.')));
		}elseif(!count($errors)){
		if (@$_POST['test'] == 1) {
		
		$subject = $this->post->subject;
		$phone = $this->post->test_phone;
		if(strlen($phone) >= 10){
		require_once('alphasms/smsclient.class.php');
		$res = array();
			$sms = new SMSClient(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue(), Config::findByCode('sms_key')->getValue());
			
			$id = $sms->sendSMS(Config::findByCode('sms_alphaname')->getValue(), $phone, $subject);
			if($sms->hasErrors()){ $res[] = $sms->getErrors();
			}else{ 
			$res['id'] = $id;
			$res['status'] = $sms->receiveSMS($id);
			$res['response'] = $sms->getResponse();
			$res['balance'] = $sms->getBalance();
			$res['phone'] = $phone;
			//$balanse = (int)$sms->getBalance();
			//if($balanse < 100) $this->sendMessageTelegram(404070580, 'Баланс SMS '.$balanse.' грн.');//Yarik
			}	
			//
			
			die(json_encode(array('status' => 'send', 'ms'=>$res, 'sms'=>$id)));
			}else{
			die(json_encode(array('status' => 'error', 'ms'=>'SMS не отправлено!')));
			}
		}elseif ($_POST['go'] == 0) {
		require_once('alphasms/smsclient.class.php');
		
        $count = $this->post->count;
		$phones = '';	
		$subject = $this->post->subject;
		
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
sleep(1);
                    }
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


    //-------------------------------------------------------
	// кодируем строку
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
        if ($_GET) {
		
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
			if ($dt->ban == 1) {
			$count = 100;
              $data[] = 'c.customer_status_id =2 ';
            }
			if (@$dt->id != '') {
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
			SELECT SUM(  `amount` +  `deposit` ) AS summ,  `customer_id` ,  `email` ,  `date_create` ,  `name` ,  `delivery_type_id` , COUNT(  `id` ) AS count
FROM  `ws_orders` 
WHERE  `status` 
IN ( 14, 6, 8 ) 
AND  `delivery_type_id` 
IN ( 4, 16, 8 ) 
AND  `date_create` >  '2016-08-17 00:00:00'
AND  `date_create` <  '2016-09-18 00:00:00'
GROUP BY  `customer_id` 
ORDER BY  `summ` DESC ");
            //$this->view->subscribers = wsActiveRecord::useStatic('Customer')->findByQuery('SELECT id,first_name,middle_name,email,drawing FROM `ws_customers` WHERE drawing = "red2014"');

            echo $this->render('user/draft_temp.tpl.php');
        }
    }

    public function userAction()
    {
	
if($this->post->method == 'emailgo'){
$sub = new Customer($this->post->id);
						 $this->view->email = $sub->getEmail();
						
						 $admin_email = Config::findByCode('admin_email')->getValue();
                            $admin_name = Config::findByCode('admin_name')->getValue();
						$subject = ' подтвердите свой email на сайте RED.UA';
                        //set_time_limit(180);
                        wsLog::add('Sending email to ' . $sub->getEmail(), 'EMAIL');
                        $this->view->name = $sub->getFirstName();
                        $this->view->email = $sub->getEmail();
						$msg = $this->view->render('user/edit-email.tpl.php');
						
                        require_once('nomadmail/nomad_mimemail.inc.php');
                            $mimemail = new nomad_mimemail();
                            $mimemail->debug_status = 'no';
                            $mimemail->set_from($admin_email, $admin_name);
                            $mimemail->set_to($sub->getEmail(), $sub->getFirstName());
                            $mimemail->set_charset('UTF-8');
                            $mimemail->set_subject($subject);
                            $mimemail->set_text($msg);
                            $mimemail->set_html(nl2br($msg));

                        MailerNew::getInstance()->sendToEmail($sub->getEmail(), $sub->getFirstName(), $sub->getFirstName().', '.$subject, $msg);
                     wsLog::add('Ok email  ' . $sub->getEmail(), 'EMAIL');
					   
						die();
						return;
}	

        if ($this->cur_menu->getParameter() == 'edit') {
            $sub = new Customer($this->get->getId());
            if (isset($_GET['resetpass'])) {
                if ($sub->getId()) {
                    $allowedChars = 'abcdefghijklmnopqrstuvwxyz'
                        . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                        . '0123456789';
                    $allowedCharsLength = strlen($allowedChars);
                    $newPass = '';
                    while (strlen($newPass) < 8)
                        $newPass .= $allowedChars[rand(0, $allowedCharsLength - 1)];
                    if ($_GET['resetpass'] == 'email') {

                        if (strlen($sub->getEmail()) > 4) {

                            $admin_email = Config::findByCode('admin_email')->getValue();
                            $admin_name = Config::findByCode('admin_name')->getValue();
                            $do_not_reply = Config::findByCode('do_not_reply_email')->getValue();

                            $sub->setPassword(md5($newPass));
                            $sub->save();
							if(isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){
                            $subject = $this->trans->get('Your new password for red.ua');
                            $this->view->new_password = $newPass;
                            $this->view->customer = $sub;
                            $msg = 'Логин: ' . $sub->getUsername() . '. ' . $this->trans->get('Your new password for red.ua') . ': ' . $newPass;
                            //echo $this->view->render('account/resetPassword-email.tpl.php');

                            require_once('nomadmail/nomad_mimemail.inc.php');
                            $mimemail = new nomad_mimemail();
                            $mimemail->debug_status = 'no';
                            $mimemail->set_from($do_not_reply, $admin_name);
                            $mimemail->set_to($sub->getEmail(), $sub->getFullname());
                            $mimemail->set_charset('UTF-8');
                            $mimemail->set_subject($subject);
                            $mimemail->set_text($msg);
                            $mimemail->set_html(nl2br($msg));

                            //@$mimemail->send();

                            MailerNew::getInstance()->sendToEmail($sub->getEmail(), $sub->getFullname(), $subject, $msg);
							}

                            $this->view->resetpass = 'email';
                        } else {
                            $this->view->resetpass = 'Error';
                        }


                    }
                    if ($_GET['resetpass'] == 'sms') {
                        $sub->setPassword(md5($newPass));
                        $sub->save();
                        $phone = Number::clearPhone($sub->getPhone1());
                        require_once('smsclub.class.php');
                        $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                        $sender = Config::findByCode('sms_alphaname')->getValue();
                        $user = $sms->sendSMS($sender, $phone, 'Vash login: ' . $sub->getUsername() . '. Vash novyj password ' . $newPass);
                        wsLog::add('SMS to user: ' . $sms->receiveSMS($user), 'SMS_' . $sms->receiveSMS($user));
                        $this->view->resetpass = 'sms';
                    }
                }
                $this->_redirect('/admin/user/edit/id/' . $sub->getId() . '&rpass=' . $this->view->resetpass);
            }

            if (count($_POST)) {
                foreach ($_POST as &$value)
                    $value = stripslashes($value);
                $errors = array();

                if (!@$_POST['first_name'])
                    $errors[] = $this->trans->get('Please fill in section name');
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
				if($d >= 0){
				
				$dep = $sub->getDeposit();
				
				if($d > $dep){
				
				$sum = $d - $dep;
				$or = 0;
				$ok = '+';
				if(!isset($_POST['deposit_email'])){
				$this->getSendEmail($this->get->getId(), $this->user->getId(), $text = 'Зачисление депозита', $sum, $or, true, true, '+'); 
				}
				DepositHistory::newDepositHistory($this->user->getId(), $this->get->getId(), $ok, $sum, $or);
				}elseif($d < $dep){
				$sum = $dep - $d;
				$or = 0;
				$no = '-';
				if(!isset($_POST['deposit_email'])){
				$this->getSendEmail($this->get->getId(), $this->user->getId(), $text = 'Списание депозита', $sum, $or, true, true, '-'); 
				}
				DepositHistory::newDepositHistory($this->user->getId(), $this->get->getId(), $no, $sum, $or);
				}
				}
				
                $sub->import($_POST);
				
				if (@$_FILES['logo']['name']) {
                    $f = pathinfo($_FILES['logo']['name']);
                    $ext = strtolower($f['extension']);
                    if ((int)$_FILES['logo']['size'] == 0) $errors['logo'] = "Выбирете фотограцию";
                    if ((int)$_FILES['logo']['size'] > 1000000) $errors['logo'] = "Размер фотографии не должен привышать 1 mb.";
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
								 if ($sub->getLogo()) @unlink($sub->getLogo());
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
				
				
				
				
                $sub->setDeposit(str_replace(',', '.', @$_POST['deposit']));
                if (@$_POST['pass_p']) {
                    $sub->setPassword(md5($_POST['pass_p']));
                    $this->view->save_pass_p = 1;
                }

                $curdate = Registry::get('curdate');
                $mas_adres = array();
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

        } else
            $this->_redir('users');

    }

//-----

    //------------ SUBSCRIBERS
    public function subscribersAction()
    {
        $data = array();
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
            echo $this->render('static.tpl.php');

        } elseif ($this->cur_menu->getParameter() == 'edit') {
            $sub = new Subscriber($this->get->getId());

            if (count($_POST)) {
                foreach ($_POST as &$value)
                    $value = stripslashes($value);
                $errors = array();

                if (!@$_POST['name'])
                    $errors[] = $this->trans->get('Please fill in section name');
                if (!@$_POST['email'] || !$this->isValidEmail($_POST['email']))
                    $errors[] = $this->trans->get('Please fill in valid email');
                if (!$sub->getId() && wsActiveRecord::useStatic('Subscriber')->count(array('email' => @$_POST['email'])))
                    $errors[] = $this->trans->get('Email is already in DB');

                $sub->import($_POST);
                $sub->setActive(1);
                $sub->setConfirmed(date('Y-m-d H:i:s'));

                if (!count($errors)) {
                    $sub->save();
                    $this->view->saved = 1;
                    //unset($this->view->errors);
                } else {
                    $this->view->errors = $errors;
                }

                $this->view->errors = $errors;
            }
            $this->view->sub = $sub;
            echo $this->render('subscriber/edit.tpl.php');

        } else
            $this->_redir('subscribers');

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
            if (@$_POST['config']) {
                foreach ($_POST['data'] as $id => $value) {
                    $config = new Config($id);
                    $config->setValue(stripslashes($value)); //??
                    $config->save();
                }
                $this->view->saved = 1;
            }

            if (@$_POST['field']) {
                foreach ($_POST['fields'] as $id => $data) {
                    $field = new Field($id);
                    if (!$data['code'] && $field->getId())
                        $field->destroy();
                    else {
                        $field->setRequired(0);
                        $field->import($data);
                        if ($field->getCode())
                            $field->save();
                    }
                }
                $this->view->saved = 1;
            }

            if (@$_POST['translation']) {
                foreach ($_POST['translations'] as $id => $data) {
                    $translation = new Dictionary($id);
                    $translation->import($data);
                    $translation->save();
                }
                $this->view->saved = 1;
            }

            $this->view->configs = wsActiveRecord::useStatic('Config')->findAll();
            $this->view->fields = wsActiveRecord::useStatic('Field')->findAll();
            $this->view->translations = wsActiveRecord::useStatic('Dictionary')->findAll();
        }

        echo $this->render('config/settings.tpl.php');
    }

    //------------ PASSWORD
    public function passwordAction()
    {
        if ($_POST) {
            $errors = array();

            if ($this->user->getPassword() != md5($_POST['oldpassword']))
                $errors[] = $this->trans->get('Old password is incorrect');

            if ($_POST['newpassword'] != $_POST['newpassword2'])
                $errors[] = $this->trans->get('New passwords do not match');

            if (strlen($_POST['newpassword']) < 8)
                $errors[] = $this->trans->get('Password should be at least 8 symbols');


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


    //-------------------------------------------------------



    // Action for Hi Kitty
    public function shopcategoriesAction()
    {

        $redir = false;
        if ($_POST) {
            foreach ($_POST as &$value)
                $value = stripslashes($value);
            if (isset($_POST['category_name']) && $_POST['category_name']) {
                $c = new Shopcategories();
                if (@$_POST['active'] == 'on') {
                    $c->setActive(1);
                } else {
                    $c->setActive(0);
                }
                $c->setName($_POST['category_name']);
                $c->setNameUk(@$_POST['category_name_uk']);
                $c->setDescription(@$_POST['category_edit_description']);
                $c->setDescriptionUk(@$_POST['category_edit_description_uk']);
                $c->setParentId(@$_POST['category_id']);
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
                    if (@$_POST['active'] == 'on') {
                        $c->setActive(1);
                    } else {
                        $c->setActive(0);
                    }
                    $c->setName($_POST['category_edit_name']);
                    $c->setNameUk(@$_POST['category_edit_name_uk']);
                    $c->setDescription(@$_POST['category_edit_description']);
                    $c->setDescriptionUk(@$_POST['category_edit_description_uk']);
                    $c->setUsencaCategory(@$_POST['ucenka_edit_id']);
                    if (@$_POST['category_edit_id'] != $c->getId())
                        $c->setParentId(@$_POST['category_edit_id']);
                    $c->save();
                    $redir = true;
                }
            }
        }

        if ('edit' == $this->cur_menu->getParameter() && $this->get->getId()) {
            $this->view->category_edit = wsActiveRecord::useStatic('Shopcategories')->findById($this->get->getId());
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

        if ($redir)
            $this->_redir('shop-categories');

        $this->view->categories = wsActiveRecord::useStatic('Shopcategories')->findAll();

        echo $this->render('shop/categories.tpl.php');
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

                $cur_category = wsActiveRecord::useStatic('Shopcategories')->findById($this->get->getIdCat());
                if (!$cur_category || !$cur_category->getId())
                    $cur_category = $categories[0];

                $this->view->cur_category = $cur_category;

                $article = new Shoparticles($this->get->getId());


                if (!$article || !$article->getId()) {
                    $article->setCategoryId($cur_category->getId());
					 $article->setDataNew(date('Y-m-d'));
                    $article->setActive('n');//y
                }

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
                      //  include_once 'Asido/class.asido.php';
                      //  include_once 'Asido/class.driver.php';
                       // include_once 'Asido/class.driver.gd.php';

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
                                    $log->setCustomerId($this->website->getCustomer()->getId());
                                    $log->setUsername($this->website->getCustomer()->getUsername());
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
						
						if(isset($_POST['long_text']) and $_POST['long_text'] != '') $_POST['long_text_uk'] = $this->trans->translateuk($_POST['long_text'], 'ru', 'uk');
						
						if(isset($_POST['sostav']) and $_POST['sostav'] != '') $_POST['sostav_uk'] = $this->trans->translateuk($_POST['sostav'], 'ru', 'uk');

                        if (isset($_POST['price']))
                            $_POST['price'] = str_replace(',', '.', $_POST['price']);
						if (isset($_POST['min_price'])) 
                            $_POST['min_price'] = str_replace(',', '.', $_POST['min_price']);
							if (isset($_POST['max_skidka']))
                            $_POST['max_skidka'] = str_replace(',', '.', $_POST['max_skidka']);
						
                        $article->import($_POST);
					 
					 
							
							
							
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
                                    $ifos = $this->importadvertinfo($filename_excel);
                                    if (@$ifos['model'] and @$ifos['price']) {
                                       /* $article->setBrand($ifos['brand']);*/
									   if(@$ifos['nakladna']){ $article->setCode($ifos['nakladna']); } 
                                        $article->setModel($ifos['model']);
										$article->setModelUk($this->trans->translateuk($ifos['model'], 'ru', 'uk'));
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
                            
                            if (@$filename_excel) {
                           // $mas =  $this->importadvert($filename_excel, $article->getId());
							 $mas = $this->importadvert($filename_excel);
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
 $this->view->sostav = wsActiveRecord::useStatic('Shoparticlessostav')->findAll();
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
	$this->view->sostav = wsActiveRecord::useStatic('Shoparticlessostav')->findAll();

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
                        $log->save();
                        $cur_category = $c->getCategory();
						@unlink($c->getImage());
						@unlink($c->getImage2());
                        $c->deleteCurImages();
                        $c->deleteCurPdf();
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
			if($_GET['brand'] or $_GET['search']){
                $data[] = 'ws_articles.brand LIKE "%' . $_GET['brand'] . '%" AND (ws_articles.model LIKE "%' . $_GET['search'] . '%")';
				if(strlen($data1) > 0){ $data1.=' and ';}
				$data1.='ws_articles.brand LIKE "%' . $_GET['brand'] . '%" AND (ws_articles.model LIKE "%' . $_GET['search'] . '%")';
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
                $ids_for_updates = array();
                foreach ($_POST as $k => $v) {
                    if (substr($k, 0, 28) == 'articel_for_change_category_') {
                        $ids_for_updates[] = (int)substr($k, 28, strlen($k));

                    }
                }
                $update_query = 'UPDATE ws_articles SET category_id = ' . $category_id . ' WHERE id IN (' . implode(',', $ids_for_updates) . ')';
                wsActiveRecord::query($update_query);
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
	}
	if($this->post->code){
	$arts = wsActiveRecord::useStatic('Shoparticles')->findAll(array("code LIKE  '".$this->post->code."'"));
	if(@$arts){
	$ctn = wsActiveRecord::useStatic('Shoparticles')->count(array("code LIKE  '".$this->post->code."' and status = 1"));
	if($ctn == 0){
	
	//$act_art = wsActiveRecord::useStatic('Shoparticles')->count(array("code LIKE  '".$this->post->code."' and status = 1"));
	
	//$message = '';
	$i = 0;
	$sum = 0;
            foreach ($arts as $art) {
			if($art->getActive() != 'y'){
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
		$message ="Добавлена накладная №".$this->post->code.".\r\n ".$i." SKU -  ".$sum." единиц.";	
		
	//print json_encode($this->post->code);
	
	$this->sendMessageTelegram(404070580, $message);
	
	$this->sendMessageTelegram(396902554, $message);
	
	$message ='Активирован товар с накладной №'.$this->post->code.'. Количество '.$sum.' шт.';
	
			}else{
			$message ='Вы не можете активировать эту накладную №'.$this->post->code.'. Еще не добавлено '.$ctn.' SKU.';
			}
			}else{
			$message ='Товаров с накладной №'.$this->post->code.', не найдено!';
			}
	die(json_encode($message));
	
	}
	if($this->get->id){
	if($this->get->type == 'a'){
	 $article = new Shoparticles($this->get->id);
			$article->setActive('y');
			$article->setCtime(date('Y-m-d H:i:s'));
			$article->setDataNew(date('Y-m-d'));
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
	 $this->sendMessageTelegram($c->getCustomer()->getTelegram(), 'Товар'.$art->getTitle().' активирован. Вы можете его заказать по ссылке: red.ua'.$art->getPath());
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
        require_once('PHPExel/PHPExcel.php');
        $order_status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
        //$categories = wsActiveRecord::useStatic('Shopcategories')->findAll();
        if (!$this->get->max and !$this->get->min) {
            die('error max or min');
        }
        if ((int)$this->get->max == 0 or (int)$this->get->min == 0) {
            die('error max or min');
        }
        if ($this->get->max < $this->get->min) {
            die('error max or min');
        }
        // $onPage = 100;
        // $page = !empty($this->get->page) && (int)$this->get->page ? (int)$this->get->page : 1;
        // $startElement = ($page - 1) * $onPage;
        ///$total = wsActiveRecord::useStatic('Shoporders')->count();
        //$orders = wsActiveRecord::useStatic('Shoporders')->findAll(array(), array(), array($startElement, $onPage));
        $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id >= ' . (int)$this->get->min . ' AND id <= ' . (int)$this->get->max . ' AND status not in( 2, 17 )'), array(), array());
        //--- create file
        $name = 'orderexel';
        $filename = $name . '.xls';

        $pExcel = new PHPExcel();
        $pExcel->setActiveSheetIndex(0);
        $aSheet = $pExcel->getActiveSheet();
        $aSheet->setTitle('Первый лист');
		 $aSheet->getColumnDimension('A')->setWidth(5);
		 $aSheet->getColumnDimension('B')->setWidth(15);
		 $aSheet->getColumnDimension('C')->setWidth(12);
		 $aSheet->getColumnDimension('D')->setWidth(30);
		 $aSheet->getColumnDimension('E')->setWidth(20);
		 $aSheet->getColumnDimension('F')->setWidth(20);
		 $aSheet->getColumnDimension('G')->setWidth(15);
        $aSheet->setCellValue('A1', '№ п/п');
        $aSheet->setCellValue('B1', 'Дата заказа');
        $aSheet->setCellValue('C1', 'Номер заказа');
        $aSheet->setCellValue('D1', 'ФИО');
		$aSheet->setCellValue('E1', 'Способ доставки');
		 $aSheet->setCellValue('F1', 'Сумма с учетом скидки');
        $aSheet->setCellValue('G1', 'Статус');
        //$aSheet->setCellValue('E1', 'Адрес');
        //$aSheet->setCellValue('F1', 'Вид доставки');
        
        //$aSheet->setCellValue('H1', 'Телефон');
        //$aSheet->setCellValue('I1', 'E-mail');
        //$aSheet->setCellValue('J1', 'Товары');
        //$aSheet->setCellValue('K1', 'Количество товаров');
        //$aSheet->setCellValue('L1', 'Сумма без скидки');
        //$aSheet->setCellValue('M1', 'Скидка %');
        //$aSheet->setCellValue('N1', 'Доставка (грн.)');
       

        $i = 2;
        $assoc = array();
        foreach ($orders as $order) {
            if ($order->getId() and $order->getName() and $order->getAddress()) {
                $d = new wsDate($order->getDateCreate());
                $order_owner = new Customer($order->getCustomerId());
                //$price = 0;
                $price_skidka = 0;
                if ($order->getArticlesCount() != 0) {
                    //$price = number_format((double)$order->getTotal('a'), 2, ',', '');
                    //$price_skidka = Number::formatFloat(((float)$order->getPriceWithSkidka() + (float)$order->getDeliveryCost()), 2);//
					$price_skidka = $order->calculateOrderPrice2(false, false);
                }
                $delivery = explode(' ', $order->getDeliveryType()->getName());
                $del = '';
                $sel = '';
                if (count($delivery) > 1) {
                    $del = $delivery[0];
                    unset($delivery[0]);
                    $sel = implode(' ', $delivery);
                } else {
                    $del = implode('', $delivery);
                    $sel = implode('', $delivery);
                }
               /*$art = '';
                $cou = 0;
                foreach ($order->articles as $aticle) {
                    $art .= $aticle->getTitle() . ' ( КОД: ' . $aticle->getCode() . ') ' . @wsActiveRecord::useStatic('Size')->findById($aticle->getSize())->getSize() . '/' . @wsActiveRecord::useStatic('Shoparticlescolor')->findById($aticle->getColor())->getName() . ' - ' . $aticle->getCount() . ';';
                    $cou += $aticle->getCount();
                }
				*/
                $name = $order->getMiddleName() . ' ' . $order->getName();
                $aSheet->setCellValue('A' . $i, ($i - 1));
                $aSheet->setCellValue('B' . $i, $d->getFormattedDateTime());
                $aSheet->setCellValue('C' . $i, $order->getId());
                $aSheet->setCellValue('D' . $i, trim($name));
                //$aSheet->setCellValue('E' . $i, $order->getAddress());
                //$aSheet->setCellValue('F' . $i, $del);
                $aSheet->setCellValue('E' . $i, $sel);
                //$aSheet->setCellValue('H' . $i, $order->getTelephone());
               // $aSheet->setCellValue('I' . $i, $order->getEmail());
               // $aSheet->setCellValue('J' . $i, $art);
                //$aSheet->setCellValue('K' . $i, $cou);
                //$aSheet->setCellValue('L' . $i, $price);
                //$aSheet->setCellValue('M' . $i, $order_owner->getDiscont());
                //$aSheet->setCellValue('N' . $i, $order->getDeliveryCost());
                $aSheet->setCellValue('F' . $i, $price_skidka);
                $aSheet->setCellValue('G' . $i, $order_status[$order->getStatus()]);
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

    }

    public function articleexcelAction()
    {
        $page = 1;
        if ($this->get->part) $page = $this->get->part;
        $page = $page - 1;
        $name = 'articles';
        $filename = $name . '.xls';
        require_once('PHPExel/PHPExcel.php');

        $pExcel = new PHPExcel();
        $pExcel->setActiveSheetIndex(0);
        $aSheet = $pExcel->getActiveSheet();
        $aSheet->setTitle('Первый лист');
        $aSheet->setCellValue('A1', '№ п/п');
        $aSheet->setCellValue('B1', 'ID');
        $aSheet->setCellValue('C1', 'Категория');
        $aSheet->setCellValue('D1', 'Название');
        $aSheet->setCellValue('E1', 'Бренд');
        $aSheet->setCellValue('F1', 'Артикул');
        $aSheet->setCellValue('G1', 'Старая цена');
        $aSheet->setCellValue('H1', 'Новая цена');
        $aSheet->setCellValue('I1', 'Количество');
        $aSheet->setCellValue('J1', 'По размерам');
        $i = 2;

        ini_set('memory_limit', '1000M');
        set_time_limit(1800);

        $articles = wsActiveRecord::useStatic('Shoparticles')->findAll(array(), array('id' => 'ASC'), array($page * 1000, 1000));

        foreach ($articles as $article) {
            $text = '';
            $cnt = 0;
            foreach ($article->sizes as $sizes) {
                if ($sizes and $sizes->color) {
                    $text .= $sizes->getCode() . '-' . $sizes->color->getName() . '-' . $sizes->size->getSize() . ": " . $sizes->getCount() . "\n\r";
                    $cnt += $sizes->getCount();
                }
            }
            if (!$article->category) d($article);
            $aSheet->setCellValue('A' . $i, ($i - 1));
            $aSheet->setCellValue('B' . $i, $article->getId());
            $aSheet->setCellValue('C' . $i, @$article->category->getRoutez());
            $aSheet->setCellValue('D' . $i, $article->getModel());
            $aSheet->setCellValue('E' . $i, $article->getBrand());
            $aSheet->setCellValue('F' . $i, $article->getCode());
            $aSheet->setCellValue('G' . $i, $article->getOldPrice() ? $article->getOldPrice() . ' грн.' : '');
            $aSheet->setCellValue('H' . $i, $article->getPrice() . ' грн.');
            $aSheet->setCellValue('I' . $i, $cnt);
            $aSheet->setCellValue('J' . $i, $text);
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


        //header("Content-type: application/x-msexcel");

    }


    public function shopordersAction()
    {
        // JSON
        if ($_POST and isset($_POST['edit'])) {

            foreach ($_POST as $kay => $value) {
                $k = explode('count-', $kay);
                if (count($k) > 1) {
                    $size = (int)$_POST['size-' . $k[1]];
                    $color = (int)$_POST['color-' . $k[1]];
                    $count = (int)$_POST['count-' . $k[1]];
                    if (isset($_POST['edit_count-' . $k[1]])) {
                        if ($size != 0 and $color != 0 and $count != 0) {
                            $order = new Shoporderarticles($k[1]);
                            Shoporders::canEdit($order->getOrderId());
                            $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $order->getArticleId(), 'id_size' => $order->getSize(), 'id_color' => $order->getColor()));
                            $article->setCount($article->getCount() + $order->getCount());
                            $article->save();
                            $artic = new Shoparticles($order->getArticleId());
                            $artic->setStock($artic->getStock() + $order->getCount());
                            $artic->save();
							if($order->getCount() != $count){
                            OrderHistory::newHistory($this->user->getId(), $order->getOrderId(), 'Изменение товара',
                                OrderHistory::getOrderArticle($order->getId(), $size, $color, $count), $order->getArticleId());}
                            $order->setSize($size);
                            $order->setColor($color);
                            $order->setCount($count);
                            $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $order->getArticleId(), 'id_size' => $size, 'id_color' => $color));
                            $article->setCount($article->getCount() - $count);
                            $artic->setStock($artic->getStock() - $count);
                            $artic->save();
                            $order->save();
                            $article->save();
                        }
                    }

                }
				//break;
            }
           // $order = new Shoporders($this->get->getId());
            /*$order->updateDeposit($this->user->getId());*/
            $this->_redir('shop-orders/edit/id/' . $this->get->getId());
        }
        if ($_POST and isset($_POST['Toevoegen']) and isset($_POST['article_id']) and isset($_POST['size_id']) and isset($_POST['color_id'])) {

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
        }elseif ($_POST and isset($_POST['Toevoegen2']) and isset($_POST['add_article_by_barcode'])) {

            $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('code' => $_POST['add_article_by_barcode']));
            $article_id = $article->getIdArticle();
            $size_id = $article->getIdSize();
            $color_id = $article->getIdColor();
		

            if ($article_id != 0 and $size_id != 0 and $color_id != 0) {
                $ar = new Shoparticles($article_id);
				$s = Skidki::getActiv($ar->getId());
				$c = Skidki::getActivCat($ar->getCategoryId(), $ar->getDopCatId());
				
                if ($article->getCount() != 0) {
                    $order = new Shoporderarticles();
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
        }
        if ($_POST && isset($_POST['getarticles'])) {
            if (isset($_POST['id']) && ($category = wsActiveRecord::useStatic('Shopcategories')->findById((int)$_POST['id'])) && $category->getId()) {
                $data = array();
                $articles = $category->getArticles(array('active' => 'y'));
                if ($articles->count())
                    foreach ($articles as $article)
                        $data[] = array(
                            'id' => $article->getId(),
                            'title' => $article->getTitle() . " (" . Shoparticles::showPrice($article->getRealPrice()) . " грн)",
                            'img' => $article->getImagePath('detail')
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
            die();
        }
        if ($_POST && isset($_POST['getoptions'])) {
            if (isset($_POST['id']) && ($article = wsActiveRecord::useStatic('Shoparticles')->findById((int)$_POST['id'])) && $article->getId()) {
                $data = array();
                $options = $article->getOptions();
                $count = 0;
                $del = isset($_POST['delivery_cost']) ? str_replace(',', '.', $_POST['delivery_cost'])
                    : Config::findByCode('delivery_cost')->getValue();
                if (isset($_POST['articles_count']) && (int)$_POST['articles_count'])
                    $del = 0.00;
                if ($options->count())
                    foreach ($options as $option)
                        $data[] = array(
                            'id' => $count++,
                            'title' => (($count > 1) ? $option->getOption()
                                    : $this->trans->get('delivery option')) . (($count > 1 || $del != 0)
                                    ? " (&euro;" . Shoparticles::showPrice($count == 1 ? $del
                                        : $option->getRealPrice()) . ")"
                                    : "")
                        );
                $res = array(
                    'result' => 'done',
                    'type' => 'options',
                    'data' => $data
                );
            } else {
                $res = array('result' => 'false');
            }
            echo json_encode($res);
            die();
        }

        // END OF JSON
		$dat = array();
	if($this->user->isPointIssueAdmin()){
	$dat[] = ' id in(100,1,3,5,7,8,9,15,16)';
	}else{
	$dat[] = ' id != 0';
	}
	/*if($this->user->isDeveloperAdmin()){
	$dat[] = ' id in(0,1,3,5,7,8,9,15,16)';
	}*/
	
        $o_stat = wsActiveRecord::useStatic('OrderStatuses')->findAll($dat);
        $mas_os = array();
        foreach ($o_stat as $o) {
            $mas_os[$o->getId()] = $o->getName();
        }
        $this->view->order_status = $mas_os;


        $this->view->categories = wsActiveRecord::useStatic('Shopcategories')->findAll();

        if ('edit' == $this->cur_menu->getParameter()) {

            $errors = array();
            $order = wsActiveRecord::useStatic('Shoporders')->findById($this->get->getId());

            Shoporders::canEdit($this->get->getId());
            if (!($order || $order->getId())) {
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
                    if ((int)$_POST['delivery_type_id'] == 4) {
                        if (strlen($_POST['index']) < 1) $errors[] = 'Введите индекс';
                        if (strlen($_POST['obl']) < 1) $errors[] = 'Введите область';
                        if (strlen($_POST['street']) < 1) $errors[] = 'Введите улицу';
                        if (strlen($_POST['house']) < 1) $errors[] = 'Введите дом';
                        if (strlen($_POST['flat']) < 1) $errors[] = 'Введите квартиру';
                    }
                    if ((int)$_POST['delivery_type_id'] == 0) $errors[] = 'Выберите способ доставки';
                    if ((int)$_POST['payment_method_id'] == 0) $errors[] = 'Выберите способ оплаты';

                    if ((int)$_POST['id'] == 0) {
                        $alredy = wsActiveRecord::useStatic('Customer')->findFirst(array('phone1' => $order->getTelephone()));
                        if ($alredy and strlen(Number::clearPhone($_POST['phone'])) > 0) $errors[] = "Пользователь с таким номером телефона уже существует";
                    }
                    if (!count($errors)) {
                        $cost = wsActiveRecord::useStatic('DeliveryPayment')->findFirst(array('delivery_id' => (int)$_POST['delivery_type_id'], 'payment_id' => (int)$_POST['payment_method_id']))->getPrice();
                        $order->setDeliveryCost($cost);
                    }
                    if (strlen($_POST['name']) < 1) $errors[] = 'Введите имя клиента';
                    if (strlen($_POST['address']) < 1) $errors[] = 'Введите адрес клиента';
                    if (strlen($_POST['email']) > 1) {
                        if (!count($errors)) {
                            $klient = wsActiveRecord::useStatic('Customer')->findFirst(array('username' => $_POST['email']));
                            if ($klient and $klient->getId() != (int)$_POST['id']) $errors[] = 'Такой Email уже используется, найдите клиента в списке клиентов';
                            elseif (!isValidEmail($_POST['email'])) $errors[] = $this->trans->get("Email is invalid");
                            elseif ((int)$_POST['id'] == 0) {
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
                                $admin_email = Config::findByCode('admin_email')->getValue();
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
                                $allowedChars = 'abcdefghijklmnopqrstuvwxyz'
                                    . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                                    . '0123456789';
                                $newPass = '';
                                $allowedCharsLength = strlen($allowedChars);
                                while (strlen($newPass) < 8)
                                    $newPass .= $allowedChars[rand(0, $allowedCharsLength - 1)];
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
            } else {

                if ($_POST) {
				if(isset($_POST['skidka'])){
				 $order->setSkidka(@$_POST['skidka']);
				}
                    if (isset($_POST['box_number'])) {
                        $order->setBoxNumber(@$_POST['box_number']);
                    }
                    if (isset($_POST['kupon'])) {
                        $order->setKupon(@$_POST['kupon']);
                    }
                    if (isset($_POST['kupon_price'])) {
                        $order->setKuponPrice(@$_POST['kupon_price']);
                    }
                    $order->save();
                    if (isset($_POST['order_status'])) {
                        if ($order->getNakladna() != @$_POST['nakladna']) {
                            OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Смена номера накладной',
                                'C "' . $order->getNakladna() . '" на "' . @$_POST['nakladna'] . '"');
                        }
                        $order->setNakladna(@$_POST['nakladna']);

                        
     if (((int)$_POST['order_status'] == 6 or (int)$_POST['order_status'] == 4) and strlen(@$_POST['nakladna']) == 0) {
         $this->_redirect($_SERVER['HTTP_REFERER']);
     }

	 
                        if ((int)$_POST['order_status'] == 3 or (int)$_POST['order_status'] == 4 or (int)$_POST['order_status'] == 6 or (int)$_POST['order_status'] == 13) {
                            $order->setOrderGo(date('Y-m-d H:i:s'));
                        }
                        if ((int)$_POST['order_status'] == 8) {
                            OrdersPay::newOrderPay($this->user->getId(), $order->getCustomerId(), $order->calculateOrderPrice(), $order->getId());	
                        }
						OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Смена статуса',
                            OrderHistory::getStatusText($order->getStatus(), (int)$_POST['order_status']));
							$st = $order->getStatus();
						if((int)$_POST['order_status'] == 5) {
						$today = date('Y-m-d H:i:s', strtotime('-'.(int)Config::findByCode('count_dey_ban_samovyvos')->getValue().' days'));		
$ord = wsActiveRecord::useStatic('Shoporders')->findAll(array('customer_id' => $order->getCustomerId(), 'flag = 1 and date_create >= "'.$today.'"'));
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
                        $order->setStatus((int)$_POST['order_status']);
                        $rez = $order->save();
                        if ($rez) {
                            $status = explode(',', $this->trans->get('new,processing,canceled,dostavlen v magazin,otpravlen ukrpochtoj,srok zakonchivsa,otpravlen novoj pochtoj'));
                            if ((int)$_POST['order_status'] == 2 or (int)$_POST['order_status'] == 7) {
							if($st == 3 and $order->getDeliveryTypeId() == 5 and (int)$_POST['order_status'] == 7){
							 $order->setFlag(3);
							}
							
                                foreach ($order->articles as $art) {
                                    if ((int)$_POST['order_status'] == 2 or (int)$_POST['order_status'] == 7) {
									if((int)$_POST['order_status'] == 7 and in_array($order->getDeliveryTypeId(), array(3,5)) and $art->getCount() > 0){
									$article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $art->getArticleId(), 'id_size' => $art->getSize(), 'id_color' => $art->getColor()));
									if($article){
									if($art->getCount() > 1){
									for($i=1; $i<=$art->getCount(); $i++){
									OrderHistory::newHistory($this->user->id, $order->Id(), 'Возврат товара', OrderHistory::getNewOrderArticle($art->getId()), $art->getArticleId());
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
									OrderHistory::newHistory($this->user->id, $order->Id(), 'Возврат товара', OrderHistory::getNewOrderArticle($art->getId()), $art->getArticleId());
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
									}else{
									$order->setStatus($st); 
									 wsLog::add('Ошибка перемещения на возврат ' . $art->Title() . ' - ' . $art->getArticleId(), 'ERROR dell article');
									$this->view->errordell = "Не удается переместить товар на возврат, ".$art->Title().". Попробуйте снова!";
									 $this->_redir('shop-orders/edit/id/' . $order->getId());
									 break;
									}
									}else{
                                        $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $art->getArticleId(), 'id_size' => $art->getSize(), 'id_color' => $art->getColor()));
										if(@$article){
										
								OrderHistory::newHistory($this->user->id, $art->getOrderId(), 'Отмена заказа', '', $art->getArticleId());
										$artic = new Shoparticles($art->getArticleId());
                                        if($article->getCount() == 0 and $artic->getCategoryId() != 16){
					if(wsActiveRecord::useStatic('Returnarticle')->count(array('code' => $article->getCode(), 'utime is null')) > 0){
					$this->sendMailAddCount($article->getCode(), $article->getIdArticle());
					}	
						}
									$article->setCount($article->getCount() + $art->getCount());
									$artic->setStock($artic->getStock() + $art->getCount());
									$art->setCount(0);
                                    $art->save();
									$article->save();
									$artic->save();
									
									}else{
									$order->setStatus($st); 
									 wsLog::add('Ошибка удаления ' . $art->Title() . ' - ' . $art->getArticleId(), 'ERROR dell article');
									$this->view->errordell = "Не удается удалить товар, ".$art->Title().". Попробуйте снова!";
									 $this->_redir('shop-orders/edit/id/' . $order->getId());
									 break;
									} 
									}
                                    }
                                    
                                }
                                $deposit = $order->getDeposit();
                                $order->setDeposit(0);
                                $order->save();
                                $customer = new Customer($order->getCustomerId());
								$c_dep = $customer->getDeposit();
								$new_d = (float)$customer->getDeposit() + (float)$deposit;
                                $customer->setDeposit((float)$customer->getDeposit() + (float)$deposit);
                                $customer->save();
								if($deposit > 0){
								OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Клиенту зачислен депозит ('.$deposit.') грн. ',
                'C "' . $c_dep . '" на "' . $new_d . '"');
				$ok = '+';
				DepositHistory::newDepositHistory($this->user->getId(), $customer->getId(), $ok, $deposit, $order->getId());
				}
                            }
      if ((int)$_POST['order_status'] == 3 or (int)$_POST['order_status'] == 4 or (int)$_POST['order_status'] == 6 or (int)$_POST['order_status'] == 13) {
                                $phone = Number::clearPhone($order->getTelephone());
                                include_once('smsclub.class.php');
                                $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                                $sender = Config::findByCode('sms_alphaname')->getValue(); 
                                if ((int)$_POST['order_status'] == 6 or (int)$_POST['order_status'] == 4) { 
								$mes = 'Zakaz №' . (int)$order->getId() . ' ' . $status[(int)$_POST['order_status']] . '. TTH №' . $_POST['nakladna'] . '.';
                                    $user = $sms->sendSMS($sender, $phone, $mes);
                                } else if((int)$_POST['order_status'] == 13){
								$mes = 'Zakaz №' . (int)$order->getId() . ' ' . $status[(int)$_POST['order_status']] . '. Dostavka:' . $order->getDeliveryDate();
								 $user = $sms->sendSMS($sender, $phone, $mes);
								}else {
								$mes = 'Zakaz №' . (int)$order->getId() . ' ' . $status[(int)$_POST['order_status']] . '. Summa ' . $order->getAmount() . ' grn.';
                                    $user = $sms->sendSMS($sender, $phone, $mes);
                                }

            wsLog::add('SMS to order: ' .$mes, 'SMS_' . @$sms->receiveSMS($user));
								}

             if ((int)$_POST['order_status'] == 3 or (int)$_POST['order_status'] == 4 or (int)$_POST['order_status'] == 6 or (int)$_POST['order_status'] == 13) {
								$status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
                                    $fields = array();
                                    $text = '';
									$text .= '
										<table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="background:#fff;border-collapse:collapse;">
										<tr>
										<td colspan="2"><br>
										Ваш заказ № <a href="http://www.red.ua/account/orderhistory/">' . (int)$order->getId() . '</a> сменил статус на: ' . $status[(int)$_POST['order_status']] . '.<br>
										</td>
										</tr>
										</table>';
                                    if (strlen($_POST['nakladna']) > 0) {
									
                                    if ((int)$_POST['order_status'] == 4) {
                                        $text .= '
										<table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="background:#fff;border-collapse:collapse;">
											
											<tr>
											<td colspan="2">
										Номер накладной: №' . $_POST['nakladna'].'
										</td>
										</tr>
										
											<tr>
										<td colspan="2">
										<a href="http://services.ukrposhta.ua/bardcodesingle/Default.aspx">По этой ссылке</a> можно перейти и по номеру декларации отследить состояние посылки.<br>
										</td>
										</tr>
										</table>';
                                    }
                                    if ((int)$_POST['order_status'] == 6) {
                                        $text .= '
										<table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="background:#fff;border-collapse:collapse;">
											<tr>
											<td colspan="2">
										Номер накладной: №' . $_POST['nakladna'].'
										</td>
										</tr>
	<tr>
	<td colspan="2">
										<a href="https://novaposhta.ua/tracking/?cargo_number='.$_POST['nakladna'].'">По этой ссылке</a> можно перейти и по номеру декларации отследить состояние посылки.<br>
										</td>
										</tr>
										</table>';
                                    }
									}
									  if ((int)$_POST['order_status'] == 13) {
                                        $text .= '
										<table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="background:#fff;border-collapse:collapse;">
												<tr>
											<td colspan="2">
										Дата доставки: ' . $order->getDeliveryDate().' '. $order->getDeliveryInterval().'
										</td>
										</tr>
										</table>';
                                    }

									if(isValidEmailNew($order->getEmail()) and isValidEmailRu($order->getEmail())){
                                    $subject = 'Изменения статуса заказа';
									$this->view->content = $text;
                                    $msg = $this->view->render('mailing/template.tpl.php');
									
                                    SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg);
									

									}
                                }
                            

							//отправка письма - оставь свой отзыв + скидка 10% на 48 часов
							if ((int)$_POST['order_status'] == 8 || (int)$_POST['order_status'] == 14) {
	if(EventCustomer::getEventsCustomerCount($order->getCustomerId()) < 2 and wsActiveRecord::useStatic('EventCustomer')->count(array('order'=>$order->getId())) == 0){
	
	
	$end_date = date("Y-m-d H:i:s", strtotime("now +2 days"));
	$dat_e = date("d-m-Y H:i:s", strtotime($end_date));
							$ev = new EventCustomer();
							$ev->setCtime(date("Y-m-d H:i:s"));
							$ev->setEndTime($end_date);
							$ev->setEventId(15);
							$ev->setCustomerId($order->getCustomerId());
							$ev->setStatus(1);
							$ev->setSt(1);
							$ev->setOrder($order->getId());
							$ev->save();
							
							$text = '
<p><img src="https://www.red.ua/storage/images/RED_ua/New/h_1449567151_1867958_82a3df0b9a-1024x356.jpg" alt="" width="700" height="243"></p>
<p style="text-align: center;font-size: 14pt;"><strong>'.$order->getName().', у нас для тебя есть специальное предложение..</strong></p>
<p style="text-align: justify;"><span style="font-size: 12pt;">Дарим дополнительную скидку 10% на покупку в нашем интернет-магазине. Предложение диствительно 48 часов с момента получения этого письма <span style="font-size: 10pt;">(до '.$dat_e.')</span>. </span></p>
<p style="text-align: center; font-size: 10pt; color: &amp;808080;">Для получения скидки нужно успеть оформить заказ в течении 48 часов.</p>
<p style="text-align: center; font-size: 10pt; color: &amp;808080;"><strong>Дополнительные условия:</strong></p>
<p style="font-size: 10pt; color: &amp;808080; text-align: left;">		1. скидка действует единоразово (при отмене или возврате заказа скидка теряется),</p>
<p style="font-size: 10pt; color: &amp;808080; text-align: left;">		2. скидка сумируется со всеми скидками на сайте кроме товаров со скидкой -60%,</p>
<p style="font-size: 10pt; color: &amp;808080; text-align: left;">		3. распространяется только на товары в заказе оформленном в период акции ( совмещение с другими заказами невозможно).</p>
<p style="font-size: 10pt; color: &amp;808080; text-align: left;">		4. каждый покупатель может получить максимум два  предложения со скидкой в месяц,</p>
<p style="font-size: 10pt; color: &amp;808080; text-align: left;">		5. скидка подключается при оформлении заказа через корзину, при оформлении быстрого заказа - акция не подключается.</p>
<p style="text-align: left;"></p>';
							
							$subject = 'Дополнительная скидка 10% на новую покупку.';
								$this->view->content = $text; 
									$msg = $this->view->render('email/template_new.tpl.php');
										SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg);
							
							}elseif($order->getCountOrder('m') == 3){
							
								if(isValidEmailNew($order->getEmail()) and isValidEmailRu($order->getEmail())){
								$text_full = '';
								$text_full .= '
	<table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="background:#fff;border-collapse:collapse;">
	<tr><td><img src="https://red.ua/images/otzyv1.png"  width="700" height="50" border="0"/></td></tr>
    <tr><td>
	<h2>Привет, '.$order->getName().'!</h2>
	Тобой был сделан заказ № <a href="http://www.red.ua/account/orderhistory/">' . (int)$order->getId() . '</a>,';
if($order->getDeliveryTypeId() == 8 or $order->getDeliveryTypeId()==16){
$text_full .=' он уже в пути. После получения, ';}
$text_full .= '
	оставь, пожалуйста, свой отзыв.<br></td></tr> 
	<tr><td><br><a href="https://red.ua/reviews/"><img src="https://red.ua/images/kol.jpg"  width="700" height="300" border="0"/></a></td></tr>
	</table>';
	
							$subject = $order->getName().', тебе понравилась покупка? Оставь свой отзыв.';
								$this->view->content = $text_full; 
									$msg = $this->view->render('mailing/template.tpl.php');
										SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg);
	}
							
							}
							
						
	
							}
							
							
                            if ((int)$_POST['order_status'] == 11) {
                                $order->setDelayToPay(date('Y-m-d'));
                                $order->save();
                            }
                            if ((int)$_POST['order_status'] == 1) {
                                $order->setDateVProcese(date('Y-m-d'));
                                $order->save();
                            }
                        }
/////
                        $this->_redirect($_SERVER['HTTP_REFERER']);
                    } elseif (isset($_POST['add_remark']) && isset($_POST['remark'])) {
                        $remark = new Shoporderremarks();
                        $data = array(
                            'order_id' => $order->getId(),
                            'date_create' => date("Y-m-d H:i:s"),
                            'remark' => $_POST['remark'],
							'name' => $this->user->getMiddleName()
                        );
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
        }elseif ('editquickorder' == $this->cur_menu->getParameter()) {
            $order = wsActiveRecord::useStatic('Shoporders')->findById($this->get->getId());
            if (isset($_POST['add_remark']) && isset($_POST['remark'])) {              
			   $remark = new Shoporderremarks();
                $data = array(
                    'order_id' => $order->getId(),
                    'date_create' => date("Y-m-d H:i:s"),
                    'remark' => $_POST['remark'],
					'name' => $this->user->getMiddleName()
                );
                $remark->import($data);
                $remark->save();
                $this->_redir('edit-quick-order/id/' . $order->getId());
            }
            if (isset($_POST['converting_to_order'])) {
			
                $order->setQuick(0);
                $order->setDateCreate(date('Y-m-d H:i:s'));
                $order->save();
                $this->_redir('shop-quick-orders');
            }
            if (isset($_POST['delete_qo'])) {
                foreach ($order->articles as $art) {
                    $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $art->getArticleId(), 'id_size' => $art->getSize(), 'id_color' => $art->getColor()));
					if ($article) {
						$artic = new Shoparticles($art->getArticleId());
					 if($article->getCount() == 0 and $artic->getCategoryId() != 16){
					if(wsActiveRecord::useStatic('Returnarticle')->count(array('code' => $article->getCode(), 'utime is null')) > 0){
					$this->sendMailAddCount($article->getCode(), $article->getIdArticle());
					}	
						}
					
						$article->setCount($article->getCount() + $art->getCount());
						$artic->setStock($artic->getStock() + $art->getCount());
						$article->save();
						$artic->save();	
						 $art->setCount(0);
                    $art->save();
					}
                   
                }
                $deposit = $order->getDeposit();
                $order->setStatus(2);
                $order->setDeposit(0);
                $order->save();
                $customer = new Customer($order->getCustomerId());
				$c_dep = $customer->getDeposit();
					$new_d = (float)$customer->getDeposit() + (float)$deposit;
                $customer->setDeposit($new_d);
                $customer->save();
				
                $this->_redir('shop-quick-orders');
            }

            $this->view->order = $order;
            echo $this->render('shop/order-edit-quick.tpl.php');
            return;
        } elseif ('delete' == $this->cur_menu->getParameter()) {

            $c = new Shoporders($this->get->getId());
            if ($c && $c->getId()) {
                /*    $c->destroy();*/
                $this->_redir('shop-orders');
            }

        }elseif ('adelete' == $this->cur_menu->getParameter()) {

            $c = new Shoporderarticles($this->get->getId());
            if ($c && $c->getId()) {
                $order_id = $c->getOrder()->getId();
                $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $c->getArticleId(), 'id_size' => $c->getSize(), 'id_color' => $c->getColor()));
				if(@$article){
					$artic = new Shoparticles($c->getArticleId());
				
					if($article->getCount() == 0 and $artic->getCategoryId() != 16){
					if(wsActiveRecord::useStatic('Returnarticle')->count(array('code' => $article->getCode(), 'utime is null')) > 0){
					$this->sendMailAddCount($article->getCode(), $article->getIdArticle());
					}	
						}
			    $article->setCount($article->getCount() + $c->getCount());
                $artic->setStock($artic->getStock() + $c->getCount());

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

        }elseif('return_article' == $this->cur_menu->getParameter()){
		
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
                $c->save();	
				
	OrderHistory::newHistory($this->user->getId(), $c->getOrderId(), 'Возврат товара', OrderHistory::getNewOrderArticle($c->getId()), $c->getArticleId());
					if(@$this->post->js){
					die(json_encode('ok'));
					}else{
                 $this->_redir('shop-orders/edit/id/' .$c->getOrder()->getId().'/#flag='.$this->get->getId());
				 }
            }else{
				 wsLog::add('Ошибка удаления  на возврат ' . $c->Title() . ' - ' . $c->getArticleId(), 'ERROR dell article');
				$this->view->errordell = "Не удается удалить товар на возврат, ".$c->Title().". Попробуйте снова!";
				} 
		
		
		}elseif ('adeletenoshop' == $this->cur_menu->getParameter()) {
			if(@$this->get->getMes()){
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
                $c->save();
                OrderHistory::newHistory($this->user->getId(), $c->getOrderId(), 'Удаление товара без возврата<br>( '.$mes.' )',
                    OrderHistory::getNewOrderArticle($c->getId()), $c->getArticleId());
               
			   $this->_redir('shop-orders/edit/id/' . $c->getOrder()->getId());
            }

        }


        $data = array();

        if ('quick' == $this->cur_menu->getParameter()) $data[] = 'quick = 1';
        else $data[] = 'quick = 0';

        $admins = wsActiveRecord::useStatic('Customer')->findAll(array('customer_type_id >1'));
        $adminsid = array();
        foreach ($admins as $ad) {
            $adminsid[] = $ad->getId();
        }
        if (isset($_GET['kupon']) and @$_GET['kupon'] == 1) $data[] = 'kupon NOT LIKE ""';
		if (isset($_GET['online']) and @$_GET['online'] == 1) $data[] = 'payment_method_id in(7,4,6)';
		if(isset($_GET['bonus']) and @$_GET['bonus'] == 1) $data[] = 'bonus > 0';
        if (isset($_GET['quick_order']) and @$_GET['quick_order'] == 1) {
            $data[] = 'from_quick = 1';
        } elseif ('quick' != $this->cur_menu->getParameter()) {
            if (isset($_GET['is_admin']) and @$_GET['is_admin'] == 1) {
                $data[] = 'customer_id in(' . implode(',', $adminsid) . ')';
            } else {
                $data[] = 'customer_id not in(' . implode(',', $adminsid) . ')';
            }
        }

        if (isset($_GET['detail']) and @$_GET['detail'] == 1) $data[] = 'call_my = 1';

		if (isset($_GET['nall']) and @$_GET['nall'] == 1) $data[] = 'amount > 0';
		
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

        $onPage = 40;
        $page = !empty($this->get->page) && (int)$this->get->page ? (int)$this->get->page : 1;
        $startElement = ($page - 1) * $onPage;
		if(isset($_GET['go'])){
		$total = wsActiveRecord::useStatic('Shoporders')->count($data);
		}else{
		if('quick' == $this->cur_menu->getParameter()) { $total = 60; }else{ $total = 300; }
		}
        //$total = wsActiveRecord::useStatic('Shoporders')->count($data);
        $this->view->totalPages = ceil($total / $onPage);
        $this->view->count = $total;
        $this->view->page = $page;
        $this->view->start = ($page == 1) ? 1 : $onPage * ($page - 1);
        $this->view->end = $onPage * ($page - 1) + $onPage;

        $this->view->orders = wsActiveRecord::useStatic('Shoporders')->findAll($data, $order_orderby, array($startElement, $onPage));
		//$this->view->orders = wsActiveRecord::useStatic('Shoporders')->findAll($data, array(), array($startElement, $onPage));

		if($this->user->id == 8005 and false) {
		if ('quick' == $this->cur_menu->getParameter())
            echo $this->render('template/shop/orders-quick.tpl.php');
        else
            echo $this->render('template/shop/orders.tpl.php');
		}else{
		
        if ('quick' == $this->cur_menu->getParameter())
            echo $this->render('shop/orders-quick.tpl.php');
        else
            echo $this->render('shop/orders.tpl.php');
			}


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
        $data = array();
        $onPage = 100;
        $page = !empty($this->get->page) && (int)$this->get->page ? (int)$this->get->page : 1;
        $startElement = ($page - 1) * $onPage;
        $total = wsActiveRecord::useStatic('SearchLog')->count($data);
        $this->view->totalPages = ceil($total / $onPage);
        $this->view->count = $total;
        $this->view->page = $page;
        $this->view->start = ($page == 1) ? 1 : $onPage * ($page - 1);
        $this->view->end = $onPage * ($page - 1) + $onPage;
        $this->view->searchs = wsActiveRecord::useStatic('SearchLog')->findAll($data, array(), array($startElement, $onPage));
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
        $month_orig = array('01' => 'січень', '02' => 'лютий', '03' => 'березень', '04' => 'квітень', '05' => 'травень', '06' => 'червень',
            '07' => 'липень', '08' => 'серпень', '09' => 'вересень', '10' => 'жовтень', '11' => 'листопад', '12' => 'грудень'
        );
        $month = array('01' => 'января', '02' => 'Февраля', '03' => 'марта', '04' => 'апреля', '05' => 'мая', '06' => 'июня',
            '07' => 'июля', '08' => 'августа', '09' => 'сентября', '10' => 'октября', '11' => 'ноября', '12' => 'декабря'
        );
        $order = new Shoporders($this->get->id);
        $dt = explode('-', substr($order->getDateCreate(), 0, 10));
        $dttd = explode('-', date("Y-m-d"));
        $this->view->date = $dt[2] . ' ' . $month[$dt[1]] . ' ' . $dt[0];
        $this->view->date_today = $dttd[2] . ' ' . $month[$dttd[1]] . ' ' . $dttd[0];
        $this->view->exploded_date = $dt;
        $this->view->order = $order;
        $customer_id = $order->getCustomerId();
        /*
                        0 - Новый
                        1 - В процесе
                        2 - Отменён
                        3 - Доставлен в магазин
                        4 - Отправлен укрпочтой
                     */
        $all_orders = wsActiveRecord::useStatic('Customer')->findByQuery('
			SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
			WHERE ws_orders.customer_id = ' . $customer_id . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) AND ws_orders.id <= ' . $order->id)->at(0);
        $all_orders_2 = wsActiveRecord::useStatic('Customer')->findByQuery('
			       SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
			        WHERE ws_orders.customer_id = ' . $customer_id . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) AND ws_orders.id < ' . $order->id)->at(0);

        $this->view->all_orders_amount = $all_orders->getAmount();
        $this->view->all_orders_amount_total = $all_orders_2->getAmount();

        if ($this->get->type == 1) {
            echo $this->render('', 'order/magaz.tpl.php');
        } elseif ($this->get->type == 2) {
            echo $this->render('', 'order/ukrp.tpl.php');
        } elseif ($this->get->type == 3) {
            echo $this->render('', 'order/novap.tpl.php');
        } elseif ($this->get->type == 4) {
            echo $this->render('', 'order/kurer.tpl.php');
        }
        die();
    }

    public function masgenerateorderAction()
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

        foreach ($ids as $id) {

            $order = new Shoporders($id);
            $dt = explode('-', substr($order->getDateCreate(), 0, 10));
            $dttd = explode('-', date("Y-m-d"));
            $this->view->date = @$dt[2] . ' ' . @$month[@$dt[1]] . ' ' . @$dt[0];
            $this->view->date_today = $dttd[2] . ' ' . $month[@$dttd[1]] . ' ' . $dttd[0];
            $this->view->exploded_date = $dt;
            $this->view->order = $order;


            $all_orders_2 = wsActiveRecord::useStatic('Customer')->findByQuery('
			        SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
			        WHERE ws_orders.customer_id = ' . $order->getCustomerId() . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) AND ws_orders.id <=' . $id)->at(0);
            $this->view->all_orders_amount_total = $all_orders_2->getAmount();

            if ($this->get->type == 1) {
                echo $this->render('', 'order/magaz.tpl.php');
            } elseif ($this->get->type == 2) {
                echo $this->render('', 'order/ukrp.tpl.php');
            } elseif ($this->get->type == 3) {
                echo $this->render('', 'order/novap.tpl.php');
            } elseif ($this->get->type == 4) {
                echo $this->render('', 'order/kurer.tpl.php');
            }
        }
    }

    public function masgeneratechekAction()
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

        foreach ($ids as $id) {
            $order = new Shoporders($id);
            $dt = explode('-', substr($order->getDateCreate(), 0, 10));
            $dttd = explode('-', date("Y-m-d"));
            $this->view->date = @$dt[2] . ' ' . @$month[@$dt[1]] . ' ' . @$dt[0];
            $this->view->date_today = $dttd[2] . ' ' . $month[@$dttd[1]] . ' ' . $dttd[0];
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
					ws_orders.customer_id = ' . $customer_id . '
					AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0);
            $all_orders_2 = wsActiveRecord::useStatic('Customer')->findByQuery('
				SELECT
					IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
				FROM
					ws_order_articles
					JOIN ws_orders
					ON ws_order_articles.order_id = ws_orders.id
				WHERE
					ws_orders.customer_id = ' . $customer_id . '
					AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,15,16)
					AND ws_orders.id <=' . $id)->at(0);
            $this->view->all_orders_amount = $all_orders->getAmount();
            $this->view->all_orders_amount_total = $all_orders_2->getAmount();
            echo $this->render('', 'order/chek.tpl.php');
        }
    }

    public function masgeneratenaklAction()
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
        $nakleiki = '';
        sort($ids);

        foreach ($ids as $id) {

            $order = new Shoporders($id);
            $dt = explode('-', substr($order->getDateCreate(), 0, 10));
            $this->view->date = @$dt[2] . ' ' . @$month[@$dt[1]] . ' ' . @$dt[0];
            $this->view->exploded_date = $dt;
            $this->view->order = $order;
            $customer_id = $order->getCustomerId();
            $all_orders = wsActiveRecord::useStatic('Customer')->findByQuery('
            SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
			WHERE ws_orders.customer_id = ' . $customer_id . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0);
            $all_orders_2 = wsActiveRecord::useStatic('Customer')->findByQuery('
			        SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
			        FROM ws_order_articles
			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
			        WHERE ws_orders.customer_id = ' . $customer_id . ' AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) AND ws_orders.id <=' . $id)->at(0);
            $this->view->all_orders_amount = $all_orders->getAmount();
            $this->view->all_orders_amount_total = $all_orders_2->getAmount();

            if ($this->get->type == 1) {
                $nakleiki .= $this->render('', 'order/magaz_nakl.tpl.php');
            } elseif ($this->get->type == 2) {
                $nakleiki .= $this->render('', 'order/ukrp_nakl.tpl.php');
            }elseif($this->get->type == 4){
			$nakleiki .= $this->render('', 'order/kur_nakl.tpl.php');
			}

        }
        echo $nakleiki;
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
            $this->view->date = @$dt[2] . ' ' . @$month[@$dt[1]] . ' ' . @$dt[0];
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
            $this->view->date = @$dt[2] . ' ' . @$month[@$dt[1]] . ' ' . @$dt[0];
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

            list($doz, $poslez) = explode('.', (string)$amount);
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
			$hist = wsActiveRecord::useStatic('OrderHistory')->findAll($data, array(), array('50'));
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
						<td>'.$s->admin->getMiddleName().' '.$s->admin->getFirstName().'</td>
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
            $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in (' . $this->get->id . ')'));
            if ($orders->count() > 0) {
                foreach ($orders as $order) {
                    OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Смена статуса',
                        OrderHistory::getStatusText($order->getStatus(), (int)$this->get->status));
						$st = $order->getStatus();
						if((int)$this->get->status == 5){
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
                    $order->setStatus((int)$this->get->status);
                    if ((int)$this->get->status == 3 or (int)$this->get->status == 4 or (int)$this->get->status == 6 or (int)$this->get->status == 13) {
                        $order->setOrderGo(date('Y-m-d H:i:s'));
                    }
                    $rez = $order->save();
                    if ($rez) {
					 $status = explode(',', $this->trans->get('new,processing,canceled,dostavlen v magazin,otpravlen ukrpochtoj,srok zakonchivsa,otpravlen novoj pochtoj'));
                       
                        if ((int)$this->get->status == 2 or (int)$this->get->status == 7) {
						if($st == 3){
						if((int)$order->getDeliveryTypeId() == 5 and (int)$this->get->status == 7){
							 $order->setFlag($st);
							}
							}elseif($st == 8){
							if((int)$order->getDeliveryTypeId() == 5 and (int)$this->get->status == 7){
							 $order->setFlag($st);
							}
							}
							//if((int)$this->get->status == 2){
							//OrderHistory::cancelOrder($this->user->id, $order->getId(), $order->calculateOrderPrice2(true, false, true), $order->getArticlesCount());
							//}
							
							
                            foreach ($order->articles as $art) {
		if((int)$this->get->status == 7 and in_array($order->getDeliveryTypeId(), array(3,5)) and $art->getCount() > 0){
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
							OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Клиенту зачислен депозит ('.$deposit.') грн. ',
                'C "' . $c_dep . '" на "' . $new_d . '"');
				
				$ok = '+';
				DepositHistory::newDepositHistory($this->user->getId(), $customer->getId(), $ok, $deposit, $order->getId());
							}
                        }
                        if ((int)$this->get->status == 8) {
  //OrdersPay::newOrderPay($this->user->getId(), $order->getCustomerId(), (($order->getPriceWithSkidka() + $order->getDeliveryCost()) - $order->getDeposit()), $order->getId());
						OrdersPay::newOrderPay($this->user->getId(), $order->getCustomerId(), $order->calculateOrderPrice(), $order->getId());
                        }
                        if ((int)$this->get->status == 3 or (int)$this->get->status == 4 or (int)$this->get->status == 6 or (int)$this->get->status == 13) {
                            $phone = Number::clearPhone($order->getTelephone());
                            include_once('smsclub.class.php');
                            $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                            $sender = Config::findByCode('sms_alphaname')->getValue();

							if ((int)$this->get->status == 4 or (int)$this->get->status == 6) { 
                                    $user = $sms->sendSMS($sender, $phone, 'Zakaz №' . (int)$order->getId() . ' ' . $status[(int)$this->get->status] . '. TTH №' . (int)$order->getNakladna() . '.');
                                } else if((int)$this->get->status == 13){
								  $user = $sms->sendSMS($sender, $phone, 'Zakaz №' . (int)$order->getId() . ' ' . $status[(int)$_POST['order_status']] . '. Dostavka:' . $order->getDeliveryDate());
								
								}else {
                                    $user = $sms->sendSMS($sender, $phone, 'Zakaz №' . (int)$order->getId() . ' ' . $status[(int)$this->get->status] . '. Summa ' . $order->getAmount() . ' grn.');
                                }
							
							
                           
						   wsLog::add('SMS to user: ' . @$sms->receiveSMS($user), 'SMS_' . @$sms->receiveSMS($user));
}
                            if ((int)$this->get->status == 3 || (int)$this->get->status == 4 || (int)$this->get->status == 6 || (int)$this->get->status == 13) {
							 $status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
                                $text = '';
                                if ((int)$this->get->status == 4) {
                                    $text .= '</br>Номер накладной: '.$order->getNakladna().' <br /> <a href="http://www.ukrposhta.com/www/upost.nsf/search_post?openpage">По этой ссылке</a> можно перейти и по номеру декларации можно отследить состояние посылки.';
                                }
                                if ((int)$this->get->status == 6) {
                                    $text .= '</br>Номер накладной: '.$order->getNakladna().' <br /> <a href="https://novaposhta.ua/tracking/?cargo_number='.$order->getNakladna().'">По этой ссылке</a> можно перейти и по номеру декларации можно отследить состояние посылки.';
                                }
								
								if ((int)$this->get->status == 13) {
								  $text .= 'Дата доставки: ' . $order->getDeliveryDate().' '. $order->getDeliveryInterval();
                                    }

								if(isValidEmailNew($order->getEmail()) and isValidEmailRu($order->getEmail())){
                                
								$subject = 'Изменения статуса заказа';
                                
								$this->view->content = 'Ваш заказ № <a href="http://www.red.ua/account/orderhistory/">' . (int)$order->getId() . '</a> сменил статус на: ' . $status[(int)$this->get->status] . '.' . $text;
                                
								$msg = $this->view->render('mailing/template.tpl.php');
									SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg);
								}
                            }
                        
                        if ((int)$this->get->status == 11) {
                            $order->setDelayToPay(date('Y-m-d'));
                            $order->save();
                        }
                        if ((int)$this->get->status == 1) {
                            $order->setDateVProcese(date('Y-m-d'));
                            $order->save();
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
			  if(wsActiveRecord::useStatic('Shoporderarticles')->findFirst(array('order_id' => $or->getId(), 'article_id'=> $or->getArticleId(),  'size'=> $or->getSize(), 'color' => $or->getColor() ))->getCount() == 0){ $st = 'style="background: rgba(255, 10, 10, 0.58);"';}else{
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
                        $customers = array();
						$status = array();
						$blok_complikt = array();
                        foreach($orders as $one){
                            $customers[$one->getCustomerId()] = 1;
							$status[$one->getStatus()] = 1;
							
							foreach ($one->getArticles() as $ar) {
							if($ar->getOptionId()) { $blok_complikt['block'] = 1; $blok_complikt['error'] = 'Нельзя совмещать с заказом'.$ar->order_id.'.';}
							}
							}
							
                        if(count($customers)!=1) die('ОШИБКА: заказы разных клиентов!');
						if(count($status)!=1) die('ОШИБКА: нельзя совмещать заказы разных статусов!');
						if(count($blok_complikt)) die($blok_complikt['error']); 

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
OrderHistory::getGoArticle($this->user->getId(), $new_order->getId(), $first_order->getId(), $article->getTitle(), $article->getSize(), $article->getColor(), $article->getPrice());
                            $article->setOrderIdOld($first_order->getId());
                            $article->setOrderId($new_order->getId());
                            $article->save(); 
                        } 
                        $new_order = new Shoporders($new_order->getId());
                        foreach ($orders as $order) {
						if($first_order->getId() != $order->getId()){ 
                            wsLog::add('Linking order ' . $order->getId() . ' deposit ' . $order->getDeposit() . ' - with ' . $new_order->getId());
							if($order->getKupon()) {$new_order->setKupon($order->getKupon()); $new_order->setKuponPrice($order->getKuponPrice());}
                            $new_order->setComments($new_order->getComments() . ' ' . $order->getComments());
                            $new_order->setAmount($new_order->getAmount() + $order->getAmount());
                            $new_order->setDeposit($new_order->getDeposit() + $order->getDeposit());
                            $new_order->setComlpect($new_order->getComlpect() . $order->getComlpect() . $order->getId() . ';');

                            if ($order->getBoxNumber()) {
                                $new_order->setBoxNumberC($new_order->getBoxNumberC() . $order->getBoxNumberC() . $order->getBoxNumber() . '( ' . $order->getId() . ');');
                            }
                            foreach ($order->articles as $article) {
	OrderHistory::getGoArticle($this->user->getId(), $new_order->getId(), $order->getId(), $article->getTitle(), $article->getSize(), $article->getColor(), $article->getPrice());
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
						if($new_order->getDeliveryTypeId() == 9 and $new_order->getAmount() >= 750){ $new_order->setDeliveryCost(0);}
                        $new_order->save();
						
						
                        OrderHistory::newHistory($this->user->getId(), $new_order->getId(), 'Совмещение заказов', $this->get->id);
						
                        $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in( ' . $this->get->id . ')'));
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
	
	if ($this->post->metod == 'getcall') {
	$id = $this->post->id;
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
  <font size="4" color="red" face="Arial"><b>'. $order->getTelephone().'</b></font><br>
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
if(isValidEmailNew($this->view->email) and isValidEmailRu($this->view->email)){

            $this->view->name = $order->getName();
            $this->view->email = $order->getEmail();
			
			$this->view->content = $text;			
			$msg = $this->view->render('email/template.tpl.php');
				SendMail::getInstance()->sendEmail($this->view->email, $this->view->name, $subject, $msg);
}
			$order->setCallMail(date('Y-m-d H:i:s'));
            $order->save();
			$mes='письмо отправлено: ' . date('d-m-Y', strtotime($order->getCallMail()));
            die($mes);
 
		}else die('error');
	
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
        $data = array();
        $this->view->brands = wsActiveRecord::useStatic('Brand')->findAll($data, array('name' => 'ASC'));
        echo $this->render('brand/list.tpl.php');
    }

    public function brandAction()
    {

        if ($this->cur_menu->getParameter() == 'edit') {
            $sub = new Brand($this->get->getId());

            if (count($_POST)) {
                foreach ($_POST as &$value)
                    $value = stripslashes($value);
                $errors = array();

                if (!@$_POST['name'])
                    $errors[] = $this->trans->get('Please fill name');
                $sub->setTop(0);
                $sub->import($_POST);
                if (!count($errors)) {
                    if (@$_FILES['image']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['image'], 'ru_RU');
                        $folder = '/storage/brands/';
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
                    if (@$_FILES['logo']) {
                        require_once('upload/class.upload.php');
                        $handle = new upload($_FILES['logo'], 'ru_RU');
                        $folder = '/storage/brands/';
                        if ($handle->uploaded) {
                            if (!count($errors)) {
                                $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                                if ($handle->processed) {
                                    if ($sub->getlogo())
                                        @unlink($sub->getLogo());
                                    $sub->setLogo($folder . $handle->file_dst_name);
                                    $handle->clean();
                                }
                            }
                        }
                    }
                }
                if (!count($errors)) {
                    $sub->save();
                    foreach ($sub->articles as $article) {
                        $article->setBrand($sub->getName());
                        $article->save();
                    }
                    $this->view->saved = 1;
                    //unset($this->view->errors);
                } else {
                    $this->view->errors = $errors;
                }

                $this->view->errors = $errors;
            }
            $this->view->sub = $sub;
            echo $this->render('brand/edit.tpl.php');

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
				$path1file = INPATH . "admin_files/views/trekko/". $filename;
				if (file_exists($path1file)){
						if (unlink($path1file)) $objWriter->save($path1file);
						}else{
						$objWriter->save($path1file);
						}
						
						$email = 'oba@red.ua';
						//$email = 'php@red.ua';
						$name = 'Баранецкая О.';
						$admin_email = Config::findByCode('admin_email')->getValue();
                        $admin_name = $this->user->getFirstName().' '.$this->user->getMiddleName();
						$subject = 'Курьерская заявка за '.date("d.m.Y");
                        $this->view->name = $name;
                        $this->view->email = $email;
						$msg = $this->view->render('trekko/kurer-email.tpl.php');
						//SendMail::getInstance()->sendEmail($email, $admin_name, $subject, $msg, $uploadfile = $path1file, $filename = $filename);

                      MailerNew::getInstance()->sendToEmail($email, $admin_name, $subject, $msg, $new = 0, '', $admin_name, $smtp = 1, $usubscribe_text = 0, $subsciber = 0, $uploadfile = $path1file, $filename = $filename);
                   //  wsLog::add('Ok email  ' . $email, 'EMAIL');
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

    public function exelarticlesAction(){//
        if ($this->get->id) {
            $mas = explode(',', $this->get->id);
            if (count($mas) > 0) {
                require_once('PHPExel/PHPExcel.php');
                $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('id in( ' . $this->get->id . ')'), array('id'=>'ASC'));
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
						200
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
						200
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
				200
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
if(@$this->post->ucenka_id){
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
					
                    $art->setPrice($art->getOldPrice() * $proc);
					$art->setDataUcenki(date('Y-m-d H:i:s'));
					$art->setUcenka((int)$this->post->usenka_id_proc);
                   $art->save();
					
UcenkaHistory::newUcenka($this->user->getId(), $art->getId(), $s_p, $art->getPrice(), (int)$art->getStock(), (int)$this->post->usenka_id_proc);
                    $i++;
					$result['id'][] = $art->getId();
                }
				
	if($i > 0) $this->sendMessageTelegram(404070580, "Уценено ".$i." товаров на ".$this->post->usenka_id_proc." %. Товары: ".$this->post->ucenka_id);

$result['uceneno'] = $i;


die(json_encode($result));
}
       $this->view->categories = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1));

            $order_by = 'ctime';
            $order_by_type = 'DESC';
            if (isset($_GET['sort']) and strlen(@$_GET['sort']) > 0) {
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
			//var_dump($sql_c);
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
	 
        if ($this->get->type == 1) {
                ini_set('memory_limit', '1024M');

                $day = strtotime($_POST['day']);
                $mast = array();
                $q = 'SELECT ws_order_articles.* FROM ws_order_articles
JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
WHERE ws_orders.date_create  > DATE_FORMAT(DATE_ADD("' . date('Y-m-d', $day) . ' 00:00:00", INTERVAL -' . ($_POST['type'] - 1) . ' DAY), "%Y-%m-%d 00:00:00")
        AND ws_orders.date_create <= "' . date('Y-m-d', $day) . ' 23:59:59"';
/*
$q = 'SELECT ws_order_articles.* 
FROM ws_order_articles
JOIN ws_orders 
ON 
ws_order_articles.order_id = ws_orders.id
WHERE
ws_orders.date_create  >="2016-03-02 13:00:00"
        
AND 
ws_orders.date_create <= "2016-03-02 23:59:59"';
*/
                $articles = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q);


                foreach ($articles as $article) {

                    //   d($article->category->getRoutez());
                    $cat = explode(' : ', $article->article_db->category->getRoutez());
                    if ($cat[0] == 'РАСПРОДАЖА') {
                        $cat[0] = $cat[1];
                        /*
							if ($cat[0] == 'Женская одежда') $cat[0] = 'Женское';
							if ($cat[0] == 'Мужская одежда') $cat[0] = 'Мужское';*/

                        if ($cat[0] == 'Детям') $cat[0] = 'Детская : ' . (@$cat[2]);
                        if ($cat[0] == 'Обувь') $cat[0] = 'Обувь : ' . (@$cat[2]);
                        if ($cat[0] == 'Белье') $cat[0] = @$cat[2];
                    }
                    if ($cat[0] == 'Белье') $cat[0] = @$cat[1];
                    if ($cat[1] == 'Детское белье') $cat[0] = 'Детям : ' . (@$cat[1]);
                    if ($cat[0] == 'Обувь') $cat[0] = 'Обувь : ' . (@$cat[1]);
                    /* $mast[$cat[0]][] = $article;*/
                    $a_price = $article->getPerc($article->order->getAllAmount(), 0);
                    if (@$mast[$cat[0]]['count']) {
                        $mast[$cat[0]]['count'] += $article->getCount();
                        $mast[$cat[0]]['price'] += $a_price['price'];
                        $mast[$cat[0]]['minus'] += $a_price['minus'];
                    } else {
                        $mast[$cat[0]]['count'] = $article->getCount();
                        $mast[$cat[0]]['price'] = $a_price['price'];
                        $mast[$cat[0]]['minus'] = $a_price['minus'];
                    }
                }
                ksort($mast);

                require_once('PHPExel/PHPExcel.php');
                $name = 'otchet_all_order_by_cat_' . $_POST['day'];
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

                $aSheet->setCellValue('A1', 'С даты:');
                $aSheet->setCellValue('B1', date('d-m-Y', $day));
                if ($_POST['type'] > 1) {
                    $aSheet->setCellValue('C1', 'за ' . $_POST['type'] . ' дней');
                } else {
                    $aSheet->setCellValue('C1', 'за ' . $_POST['type'] . ' день');
                }

                $aSheet->setCellValue('A2', 'Товары');
                $aSheet->setCellValue('B2', 'К-во ед.');
                $aSheet->setCellValue('C2', 'Сумма');
                $aSheet->setCellValue('D2', 'Скидка');


                $boldFont = array(
                    'font' => array(
                        'bold' => true
                    )
                );
                $aSheet->getStyle('A2')->applyFromArray($boldFont);
                $aSheet->getStyle('B2')->applyFromArray($boldFont);
                $aSheet->getStyle('B1')->applyFromArray($boldFont);
                $aSheet->getStyle('C2')->applyFromArray($boldFont);
                $aSheet->getStyle('D2')->applyFromArray($boldFont);
                $aSheet->getStyle('E2')->applyFromArray($boldFont);
                $aSheet->getStyle('F2')->applyFromArray($boldFont);
                $i = 3;
                $assoc = array();
                foreach ($mast as $kay => $val) {
                    $aSheet->setCellValue('A' . $i, $kay);
                    $aSheet->setCellValue('B' . $i, $val['count']);
                    $aSheet->setCellValue('C' . $i, $val['price']);
                    $aSheet->setCellValue('D' . $i, $val['minus']);
                    $i++;
                }

                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

               // header("Content-type: application/x-msexcel");
                $objWriter->save('php://output');

            }
        if ($this->get->type == 2) {
                ini_set('memory_limit', '1024M');
                $from = strtotime($_POST['order_from']);
                $to = strtotime($_POST['order_to']);
                if (@$_POST['no_new']) {
				$orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('status not in (100, 17)', 'date_create <="' . date('Y-m-d', $to) . ' 23:59:59" and date_create >= "' . date('Y-m-d', $from) . ' 00:00:00"'), array('date_create' => 'ASC'));
                   
                } else {
                     $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('date_create <="' . date('Y-m-d', $to) . ' 23:59:59" and date_create >= "' . date('Y-m-d', $from) . ' 00:00:00"', ' status != 17'), array('date_create' => 'ASC'));
					  }
					  
					  $orders = "
					SELECT
							 DATE_FORMAT( order_history.ctime,  '%d-%m-%Y' ) AS dat, SUM(  `sum_order` ) AS suma, COUNT(  `id` ) AS ctn, SUM(  `count_article` ) AS ctn_ar
						FROM
							order_history
						WHERE
							order_history.name LIKE  'Заказ оформлен'
							and ctime >= '" . date('Y-m-d', $from) . " 00:00:00'
							and ctime <= '" . date('Y-m-d', $to) ." 23:59:59'
							group by dat
					";
					$orders = wsActiveRecord::useStatic('OrderHistory')->findByQuery($orders);
                $mas = array();
/* 
0 2 5 7 10 12 14
100	Новый											
1	В процессе										*	-
2	Отменён											*	
3	Доставлен в магазин								*	-
4	Отправлен Укрпочтой								*	-
5	Срок хранения заказа в магазине закончился		
6	Отправлен Новая почта								-
7	Возврат											*	
8	Оплачен											*	-
9	Cобран											*	-
10	Продлён клиентом								*	
11	Ждёт оплаты										*	-
12	Ждет возврат									*	
13	В процессе доставки								*	-
14	Оплачен депозитом								
*/
            /*    foreach ($orders as $order) {
                    if (in_array($order->getDeliveryTypeId(), array(1, 2, 3, 7, 11, 12, 13, 15, 5))) {
                        $mas[date('d-m-Y', strtotime($order->getDateCreate()))][1][] = $order;
                    } elseif (in_array($order->getDeliveryTypeId(), array(8, 16))) {
                        $mas[date('d-m-Y', strtotime($order->getDateCreate()))][2][] = $order;
                    } elseif (in_array($order->getDeliveryTypeId(), array(4))) {
                        $mas[date('d-m-Y', strtotime($order->getDateCreate()))][3][] = $order;
                    } elseif (in_array($order->getDeliveryTypeId(), array(9, 10))) {
                        $mas[date('d-m-Y', strtotime($order->getDateCreate()))][4][] = $order;
                    } else $mas[date('d-m-Y', strtotime($order->getDateCreate()))][5][] = $order;
                }*/
/*
				echo '<pre>';
				print_r($mas);
				echo '</pre>';
*/
                require_once('PHPExel/PHPExcel.php');
                $name = 'otchet_order_' . $_POST['order_from'] . '_' . $_POST['order_to'];
                $kount = 1;
                $filename = $name . '.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
                $aSheet->getColumnDimension('A')->setWidth(10);
                $aSheet->getColumnDimension('B')->setWidth(12);
                $aSheet->getColumnDimension('C')->setWidth(18);
                $aSheet->getColumnDimension('D')->setWidth(18);
                $aSheet->getColumnDimension('E')->setWidth(12);
                $aSheet->getColumnDimension('F')->setWidth(15);
				$aSheet->getColumnDimension('G')->setWidth(17);
				$aSheet->getColumnDimension('H')->setWidth(28);
				//$aSheet->getColumnDimension('I')->setWidth(12);
				//$aSheet->getColumnDimension('J')->setWidth(16);
				//$aSheet->getColumnDimension('K')->setWidth(17);
				//$aSheet->getColumnDimension('K')->setWidth(28);
				
                $aSheet->setCellValue('A1', 'Дата');
                $aSheet->setCellValue('B1', 'Сумма');
                $aSheet->setCellValue('C1', 'Количество заказов');
                $aSheet->setCellValue('D1', 'Количество единиц');
				/*$aSheet->mergeCells('E1:H1');
                $aSheet->setCellValue('E1', 'Способ доставки');
                $aSheet->setCellValue('E2', 'Магазины');
                $aSheet->setCellValue('F2', 'Новая Почта');
                $aSheet->setCellValue('G2', 'Укр.Почта');
                $aSheet->setCellValue('H2', 'Курьер');*/
                $aSheet->setCellValue('E1', 'Товар');
				$aSheet->mergeCells('E1:H1');
                $aSheet->setCellValue('E2', 'Новый товар');
				$aSheet->setCellValue('F2', 'Товар с возврата');
				$aSheet->setCellValue('G2', 'Отменено с заказа');
				$aSheet->setCellValue('H2', 'Удалено без возврата с заказа');
				
                $boldFont = array('font' => array('bold' => true));
                $aSheet->getStyle('A1:H1')->applyFromArray($boldFont);
				$aSheet->getStyle('A2:H2')->applyFromArray($boldFont);

                $i = 3;
                foreach ($orders as $k => $m) {
                    $q = "SELECT SUM(  `red_article_log`.`count` ) AS  `allcount` 
FROM  `red_article_log` 
INNER JOIN  `ws_articles` ON  `red_article_log`.`article_id` =  `ws_articles`.`id` 
WHERE  `red_article_log`.`type_id` = 4
and `ws_articles`.`status` > 2
and `ws_articles`.`ctime` >= '" . date('Y-m-d 00:00:00', strtotime($m->dat)) . "'
and `ws_articles`.`ctime` <= '" . date('Y-m-d 23:59:59', strtotime($m->dat)) . "'
AND  `ws_articles`.`active` =  'y'
					";

                    $count_add = wsActiveRecord::useStatic('Shoparticlelog')->findByQuery($q);
					
					
					$s = "
					SELECT
							count(order_history.id) as allcount
						FROM
							order_history
						WHERE
							order_history.name LIKE  '%Прийом товара с возврата%'
							and ctime >= '" . date('Y-m-d 00:00:00', strtotime($m->dat)) . "'
							and ctime <= '" . date('Y-m-d 23:59:59', strtotime($m->dat)) . "'
					";
					$hist = wsActiveRecord::useStatic('OrderHistory')->findByQuery($s);
					$s = "
					SELECT
							count(order_history.id) as allcount
						FROM
							order_history
						WHERE
							order_history.name LIKE  'Отмена заказа'
							and ctime >= '" . date('Y-m-d 00:00:00', strtotime($m->dat)) . "'
							and ctime <= '" . date('Y-m-d 23:59:59', strtotime($m->dat)) . "'
					";
					$cancel = wsActiveRecord::useStatic('OrderHistory')->findByQuery($s);
					$s = "
					SELECT
							count(order_history.id) as allcount
						FROM
							order_history
						WHERE
							order_history.name LIKE  'Удаление товара без возврата'
							and ctime >= '" . date('Y-m-d 00:00:00', strtotime($m->dat)) . "'
							and ctime <= '" . date('Y-m-d 23:59:59', strtotime($m->dat)) . "'
					";
					$del = wsActiveRecord::useStatic('OrderHistory')->findByQuery($s);
                    $aSheet->setCellValue('A' . $i, $m->dat);
                    $aSheet->setCellValue('B' . $i, round($m->suma, 2));
					$aSheet->setCellValue('C' . $i, $m->ctn);
					$aSheet->setCellValue('D' . $i, $m->ctn_ar);
                   // $aSheet->setCellValue('E' . $i, count(@$m['1']));
                   // $aSheet->setCellValue('F' . $i, count(@$m['2']));
                   // $aSheet->setCellValue('G' . $i, count(@$m['3']));
                   // $aSheet->setCellValue('H' . $i, count(@$m['4']));
                    $aSheet->setCellValue('E' . $i, $count_add->at(0)->getAllcount());
					$aSheet->setCellValue('F' . $i, $hist->at(0)->getAllcount());
					$aSheet->setCellValue('G' . $i, $cancel->at(0)->getAllcount());
					$aSheet->setCellValue('H' . $i, $del->at(0)->getAllcount());
                  /*  $kount_a = 0;
                    $sum = 0;
                    foreach ($m as $kd => $obj) {
                        foreach ($obj as $order) {
                            $kount_a += $order->countArticlesSum();
                            $sum += $order->getAmount();
                        }
                    }*/
                    
                    
                    $i++;
                }

                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

               // header("Content-type: application/x-msexcel");

                $objWriter->save('php://output');

            }
		if ($this->get->type == 3) {
            if (isset($_GET['day'])) {
                $order_status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
                $to = strtotime($_GET['day']);
                $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('date_create <="' . date('Y-m-d', $to) . ' 23:59:59" and date_create >= "' . date('Y-m-d', $to) . ' 00:00:00"'), array('date_create' => 'ASC'));
                require_once('PHPExel/PHPExcel.php');
                $name = 'orderexel';
                $filename = $name . '.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
                $aSheet->getColumnDimension('A')->setWidth(12);
                $aSheet->getColumnDimension('B')->setWidth(15);
                $aSheet->getColumnDimension('C')->setWidth(30);
                $aSheet->getColumnDimension('D')->setWidth(22);
                $aSheet->getColumnDimension('E')->setWidth(15);
                $aSheet->getColumnDimension('F')->setWidth(20);
                $aSheet->getColumnDimension('G')->setWidth(20);
                $aSheet->getColumnDimension('H')->setWidth(50);
                $aSheet->getColumnDimension('I')->setWidth(10);
                $aSheet->getColumnDimension('J')->setWidth(13);
                $aSheet->getColumnDimension('K')->setWidth(15);
                $aSheet->getColumnDimension('L')->setWidth(15);
                $aSheet->getColumnDimension('M')->setWidth(16);
                $aSheet->getColumnDimension('N')->setWidth(16);
                $aSheet->getColumnDimension('O')->setWidth(16);
                $aSheet->getColumnDimension('P')->setWidth(50);
                $aSheet->getColumnDimension('Q')->setWidth(20);
                $aSheet->getColumnDimension('R')->setWidth(20);
                $aSheet->getColumnDimension('S')->setWidth(30);
                $aSheet->setCellValue('A1', 'Номер счета');
                $aSheet->setCellValue('B1', 'ФИО');
                $aSheet->setCellValue('C1', '№ клиента');
                $aSheet->setCellValue('B1', 'Тип');
                $aSheet->setCellValue('E1', 'Статус');
                $aSheet->setCellValue('F1', "Дата создания/\nОтправка письма Новая почта");
                $aSheet->setCellValue('G1', 'Дата отправки товара');
                $aSheet->setCellValue('H1', 'Наименование товара');
                $aSheet->setCellValue('I1', 'Артикул');
                $aSheet->setCellValue('J1', 'Кол-во товара');
                $aSheet->setCellValue('K1', 'Цена');
                $aSheet->setCellValue('L1', 'Старая цена');
                $aSheet->setCellValue('M1', 'Сума без скидки');
                $aSheet->setCellValue('N1', 'Скидка');
                $aSheet->setCellValue('O1', 'Сума(+ доставка)');
                $aSheet->setCellValue('P1', 'Состав');
                $aSheet->setCellValue('Q1', 'Дата оплаты');
                $aSheet->setCellValue('R1', 'Сумма оплаты');
                $aSheet->setCellValue('S1', 'Оплачены заказы');
                $aSheet->setCellValue('T1', 'начальная стоимость');
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
                $aSheet->getStyle('G1')->applyFromArray($boldFont);
                $aSheet->getStyle('H1')->applyFromArray($boldFont);
                $aSheet->getStyle('I1')->applyFromArray($boldFont);
                $aSheet->getStyle('J1')->applyFromArray($boldFont);
                $aSheet->getStyle('K1')->applyFromArray($boldFont);
                $aSheet->getStyle('L1')->applyFromArray($boldFont);
                $aSheet->getStyle('M1')->applyFromArray($boldFont);
                $aSheet->getStyle('N1')->applyFromArray($boldFont);
                $aSheet->getStyle('O1')->applyFromArray($boldFont);
                $aSheet->getStyle('P1')->applyFromArray($boldFont);
                $aSheet->getStyle('Q1')->applyFromArray($boldFont);
                $aSheet->getStyle('R1')->applyFromArray($boldFont);
                $aSheet->getStyle('S1')->applyFromArray($boldFont);
                $aSheet->getStyle('T1')->applyFromArray($boldFont);

                $i = 2;
                $assoc = array();
                foreach ($orders as $order) {
                    if ($order->getId() and $order->getName()) {
                        $date = '0000-00-00 00:00:00';
                        if ($order->getOrderGo() and $order->getOrderGo() != '0000-00-00 00:00:00') {
                            $date = $order->getOrderGo();
                        }

                        if ($date and $date != '0000-00-00 00:00:00') {
                            $d = new wsDate($date);
                            $time = $d->getFormattedDateTime();
                        } else {
                            $time = 'не отправлен';
                        }

                        $datenova = '0000-00-00 00:00:00';
                        if ($order->getNowaMail() and $order->getNowaMail() != '0000-00-00 00:00:00') {
                            $datenova = $order->getNowaMail();
                        }
                        $datesozd = $order->getDateCreate();
                        $d = new wsDate($datesozd);
                        $timesozd = $d->getFormattedDateTime();
                        if ($datenova and $datenova != '0000-00-00 00:00:00') {
                            $d = new wsDate($datenova);
                            $timenova = " / \n" . $d->getFormattedDateTime();
                        } else {
                            $timenova = '';
                        }


                        $order_owner = new Customer($order->getCustomerId());
                        $price = 0;
                        $price_skidka = 0;
                        if ($order->getArticlesCount() != 0) {
                            $price = number_format((double)$order->getTotal('a'), 2, ',', ''); 
                           // $price_skidka = Number::formatFloat(((float)$order->getPriceWithSkidka() + (float)$order->getDeliveryCost()), 2);//Yarik
						   $price_skidka = $order->calculateOrderPrice2(false, false);
                        }
                        $aSheet->setCellValue('T' . $i, $order->getAmount());
                        $aSheet->setCellValue('A' . $i, $order->getId());
                        $aSheet->setCellValue('B' . $i, $order->getName());
                        $aSheet->setCellValue('C' . $i, $order_owner->getId());
                        $aSheet->setCellValue('D' . $i, $order->getDeliveryTypeId()
                            ? $order->getDeliveryType()->getName() : " ");
                        $aSheet->setCellValue('E' . $i, isset($order_status[$order->getStatus()])
                            ? $order_status[$order->getStatus()] : "");
                        $aSheet->setCellValue('F' . $i, $timesozd . $timenova);
                        $aSheet->setCellValue('G' . $i, $time);
                        $aSheet->setCellValue('H' . $i, '');
                        $aSheet->setCellValue('I' . $i, '');
                        $aSheet->setCellValue('J' . $i, $order->articles->count());
                        $aSheet->setCellValue('K' . $i, '');
                        $aSheet->setCellValue('L' . $i, '');
                        $aSheet->setCellValue('M' . $i, number_format((double)$order->getTotal('a'), 2, ',', ''));
                        $aSheet->setCellValue('N' . $i, $order_owner->getDiscont() . '%');
                        $aSheet->setCellValue('O' . $i, $price_skidka);
                        $aSheet->setCellValue('P' . $i, '');

                        if ($order->getAdminPayId()) {
                            $order_pay = wsActiveRecord::useStatic('OrdersPay')->findAll(array('customer_id' => $order->getCustomerId(), 'ordes=' . $order->getId() . ' OR ordes LIKE "%' . $order->getId() . '%"'));
                            $pay_date = array();
                            $pay_sum = array();
                            $pay_orders = array();

                            if ($order_pay->count()) {
                                foreach ($order_pay as $pay) {
                                    $pay_date[] = date('d-m-Y', strtotime($pay->getCtime()));
                                    $pay_sum[] = $pay->getSum();
                                    $pay_orders[] = $pay->getOrdes();
                                }
                            }
                            $aSheet->setCellValue('Q' . $i, implode(',', $pay_date));
                            $aSheet->setCellValue('R' . $i, implode(',', $pay_sum));
                            $aSheet->setCellValue('S' . $i, implode(',', $pay_orders));


                        }
                        foreach ($order->articles as $art) {
                            $i++;
                            $aSheet->setCellValue('G' . $i, 'Товар');
                            $art_orig = new Shoparticles($art->getArticleId());
                            $size = new Size($art->getSize());
                            $art_name = ($art->getBrand() ? $art->getBrand() . ', '
                                    : '') . $art->getTitle() . ', ' . $size->getSize();
                            $aSheet->setCellValue('H' . $i, $art_name);
                            $aSheet->setCellValue('I' . $i, $art->getCode());
                            $aSheet->setCellValue('J' . $i, $art->getCount());
                            $aSheet->setCellValue('K' . $i, Number::formatFloat($art->getPrice(), 2));
                            $aSheet->setCellValue('L' . $i, Number::formatFloat($art->getOldPrice(), 2));
                            $aSheet->setCellValue('O' . $i, Number::formatFloat(($art->getPrice() * $art->getCount()), 2));
                            $aSheet->setCellValue('P' . $i, $art_orig->getSostav());
                            if ((int)$art->getOldPrice()) {
                                $skidka_t = (1 - ((int)$art->getPrice() / ((int)$art->getOldPrice()
                                                ? (int)$art->getOldPrice() : 1))) * 100;
                            } else $skidka_t = 0;
                            if ($skidka_t > 80) $skidka_t = 90;
                            elseif ($skidka_t > 60) $skidka_t = 70;
                            elseif ($skidka_t > 40) $skidka_t = 50;
                            elseif ($skidka_t > 21) $skidka_t = 30;
							elseif ($skidka_t > 10) $skidka_t = 20;

                            if ($skidka_t != 0) {
                                $aSheet->setCellValue('N' . $i, $skidka_t . '%');
                            }

                        }
                        if ((int)$order->getDeliveryCost() > 0) {
                            $i++;
                            $aSheet->setCellValue('G' . $i, 'Услуга');
                            $aSheet->setCellValue('H' . $i, 'Доставка');
                            $aSheet->setCellValue('K' . $i, Shoparticles::showPrice($order->getDeliveryCost()));
                        }
                        $i++;
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

               // header("Content-type: application/x-msexcel");
            }
        }
        if ($this->get->type == 4) {

                ini_set('memory_limit', '1024M');
				
				
				
             /*   $q = "SELECT ws_order_articles.*, ws_articles_sizes.count FROM ws_order_articles
                JOIN ws_articles ON ws_articles.id = ws_order_articles.article_id
				JOIN ws_articles_sizes ON ws_articles_sizes.id_article = ws_articles.id
                JOIN ws_orders ON ws_orders.id = ws_order_articles.order_id
                WHERE ws_order_articles.count > 0
                AND ws_articles.brand_id = " . @$_POST['brend'] . "
                AND ws_articles.data_new >= '" . date('Y-m-d', strtotime(@$_POST['order_from'])) . "'
                AND ws_articles.data_new <= '" . date('Y-m-d', strtotime(@$_POST['order_to'])) . "'";*/
				  /* AND ws_orders.date_create > '" . date('Y-m-d', strtotime(@$_POST['order_from'])) . " 00:00:00'
                AND ws_orders.date_create < '" . date('Y-m-d', strtotime(@$_POST['order_to'])) . " 23:59:59'";*/
				
				$q = "SELECT ws_articles_sizes.*, ws_articles.model as model  FROM ws_articles_sizes
                JOIN ws_articles ON ws_articles.id = ws_articles_sizes.id_article
				
                WHERE ws_articles.brand_id = " . @$_POST['brend'] . "
                AND ws_articles.data_new >= '" . date('Y-m-d', strtotime(@$_POST['add_from'])) . "'
                AND ws_articles.data_new <= '" . date('Y-m-d', strtotime(@$_POST['add_to'])) . "'";

                $artucles = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($q);
                $mas = array();
                foreach ($artucles as $article) {
                 /*   $ucen = wsActiveRecord::useStatic('UcenkaHistory')->findFirst(array('article_id' => $article->getIdArticle(), 'ctime < "' . $article->order->getDateCreate() . '"'));
                    if ($ucen) {
                        $price_bez = $ucen->getOldPrice();
                        $price_skid = $ucen->getNewPrice();
                    } else {
                        $price_bez = $article->article_db->getPrice();
                        if ($article->article_db->getOldPrice()) {
                            $price_skid = $article->article_db->getOldPrice();
                        } else {
                            $price_skid = $article->article_db->getPrice();
                        }
                    }
					*/

                   // $opder_price = $article->order->getAllAmount();
                    $count = $article->getCount();
                   // $real_price = $article->getPerc($opder_price);
                   // $real_price = $real_price['price'] * (1 - ($article->getEventSkidka() / 100));
                    $k1 = 0;
                    $k2 = 0;
                    $k3 = 0;
                 /*   if ($real_price == $price_skid) {
                        $real_price = 0;

                        if ($price_skid == $price_bez) {
                            $k1 = $count;
                            $price_skid = 0;
                        } else {
                            $k3 = $count;
                            $price_bez = 0;
                        }
                    } else {
                        $k2 = $count;
                        $price_skid = 0;
                        $price_bez = 0;
                    }
*/

                   /* $name = explode('(', $article->getModel());
                    if (count($name) == 2) {
                        $name = str_replace(')', '', $name[1]);
                    } else {
                        $name = '';
                    }*/
					$name = $article->getModel();
                    if (isset($mas[$name]) and isset($mas[$name][$article->getIdArticle()])) {
                        $mas[$name][$article->getIdArticle()]['count'] += $count;
                        /*$mas[$name][$article->getIdArticle()]['no_sum'] += $price_bez * $count;
                        $mas[$name][$article->getIdArticle()]['ns_sum'] += $real_price * $count;
                        $mas[$name][$article->getIdArticle()]['r_sum'] += $price_skid * $count;
                        $mas[$name][$article->getIdArticle()]['no_c'] += $k1;
                        $mas[$name][$article->getIdArticle()]['ns_c'] += $k2;
                        $mas[$name][$article->getIdArticle()]['r_c'] += $k3;*/
                    } else {
                        $q = "SELECT sum(count) as cnt, sum(count * price) as ssum FROM ws_order_articles
						 JOIN ws_orders ON ws_orders.id = ws_order_articles.order_id
                        where ws_order_articles.article_id = " . $article->getIdArticle()." and ws_orders.date_create >= '" . date('Y-m-d', strtotime(@$_POST['order_from'])) . " 00:00:00'
                AND ws_orders.date_create <= '" . date('Y-m-d', strtotime(@$_POST['order_to'])) . " 23:59:59'";
                        $now_count = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q);//->at(0)->cnt;
						
						$q2 = "SELECT sum(count) as cnt FROM ws_order_articles
                        where article_id = " . $article->getIdArticle();
						 $ostatok = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q2)->at(0)->cnt;
						 
                        $mas[$name][$article->getIdArticle()]['count'] = $count;
						$mas[$name][$article->getIdArticle()]['ssum'] =  @$now_count[0]->ssum ? $now_count[0]->ssum : 0 ;
                      //  $mas[$name][$article->getIdArticle()]['no_sum'] = $price_bez * $count;
                       // $mas[$name][$article->getIdArticle()]['ns_sum'] = $real_price * $count;
                       // $mas[$name][$article->getIdArticle()]['r_sum'] = $price_skid * $count;
                        $mas[$name][$article->getIdArticle()]['now_count'] = @$now_count[0]->cnt ? $now_count[0]->cnt : 0 ;
						$mas[$name][$article->getIdArticle()]['ostatok'] =  $ostatok;
						//  $mas[$name][$article->getIdArticle()]['no_c'] = $k1;
                       // $mas[$name][$article->getIdArticle()]['ns_c'] = $k2;
                        //$mas[$name][$article->getIdArticle()]['r_c'] = $k3;
                    }

                }

                require_once('PHPExel/PHPExcel.php');
                $name = 'otchetexel';
                $kount = 1;
                $filename = $name . '.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
                $aSheet->getColumnDimension('A')->setWidth(35);
                $aSheet->getColumnDimension('B')->setWidth(20);
                $aSheet->getColumnDimension('C')->setWidth(20);
                $aSheet->getColumnDimension('D')->setWidth(20);
                $aSheet->getColumnDimension('E')->setWidth(30);
               // $aSheet->getColumnDimension('F')->setWidth(20);

                $aSheet->setCellValue('A1', 'Характеристика номенклатуры:');
                $aSheet->setCellValue('B1', 'Приход');
                $aSheet->setCellValue('C1', 'Расход');
                $aSheet->setCellValue('D1', 'Остаток');
                $aSheet->setCellValue('E1', 'Продано на сумму');
                /*$aSheet->setCellValue('E2', 'без с скидки');
                $aSheet->setCellValue('F2', 'с клиентской скидкой ');
                $aSheet->setCellValue('G2', 'с уценочной скидкой');
                $aSheet->setCellValue('E1', 'Сумма проданных единиц');
                $aSheet->setCellValue('H1', 'Количество проданных единиц');
                $aSheet->setCellValue('H2', 'без с скидки');
                $aSheet->setCellValue('I2', 'с клиентской скидкой');
                $aSheet->setCellValue('J2', 'с уценочной скидкой');
                $aSheet->setCellValue('K1', 'Общая сумма продаж');
                $aSheet->setCellValue('K2', 'сумма без скидки+клиентские скидки');
                $aSheet->setCellValue('L2', 'вся сумма');*/

                $boldFont = array(
                    'font' => array(
                        'bold' => true
                    )
                );
                $aSheet->getStyle('A1')->applyFromArray($boldFont);
                $aSheet->getStyle('B1')->applyFromArray($boldFont);
                $aSheet->getStyle('C1')->applyFromArray($boldFont);
                $aSheet->getStyle('D1')->applyFromArray($boldFont);
                //$aSheet->getStyle('D2')->applyFromArray($boldFont);
				$aSheet->getStyle('E1')->applyFromArray($boldFont);
                /*$aSheet->getStyle('E2')->applyFromArray($boldFont);
                $aSheet->getStyle('F2')->applyFromArray($boldFont);
                
                $aSheet->getStyle('G2')->applyFromArray($boldFont);
                $aSheet->getStyle('H1')->applyFromArray($boldFont);
                $aSheet->getStyle('H2')->applyFromArray($boldFont);
                $aSheet->getStyle('I2')->applyFromArray($boldFont);
                $aSheet->getStyle('J2')->applyFromArray($boldFont);
                $aSheet->getStyle('K1')->applyFromArray($boldFont);
                $aSheet->getStyle('K2')->applyFromArray($boldFont);
                $aSheet->getStyle('L2')->applyFromArray($boldFont);*/
                $i = 3;
                $all_c = 0;
                $all_a1 = 0;
                $all_a2 = 0;
                $all_a3 = 0;
                $all_a4 = 0;
                $all_a5 = 0;
                $all_a6 = 0;
                $all_a7 = 0;
                $all_a8 = 0;
                foreach ($mas as $kay => $val) {
                    $all = $i;
                    $aSheet->setCellValue('A' . $i, $kay);
                    $i++;
                    $count = 0;
                    $os = 0;
                    $ns = 0;
                    $rs = 0;
                    $nc = 0;
                    $prih = 0;
                    $a1 = 0;
                    $a2 = 0;

                    $a3 = 0;
                    $a4 = 0;
                    $a5 = 0;
                    foreach ($val as $tov_kay => $tov_val) {
                        $aSheet->setCellValue('A' . $i, $tov_kay);
                        $aSheet->setCellValue('C' . $i, $tov_val['now_count']);
                        $aSheet->setCellValue('E' . $i, $tov_val['ssum']);
                       // $aSheet->setCellValue('F' . $i, $tov_val['ns_sum']);
                       // $aSheet->setCellValue('G' . $i, $tov_val['r_sum']);
                        $aSheet->setCellValue('D' . $i, $tov_val['count']);
                        $aSheet->setCellValue('B' . $i, $tov_val['ostatok'] + $tov_val['count']);//$tov_val['now_count'] + $tov_val['count']);

                       // $aSheet->setCellValue('H' . $i, $tov_val['no_c']);
                       // $aSheet->setCellValue('I' . $i, $tov_val['ns_c']);
                      //  $aSheet->setCellValue('J' . $i, $tov_val['r_c']);

                       // $aSheet->setCellValue('K' . $i, $tov_val['no_sum'] + $tov_val['ns_sum']);
                       // $aSheet->setCellValue('L' . $i, $tov_val['r_sum'] + $tov_val['no_sum'] + $tov_val['ns_sum']);
                        $count += $tov_val['count'];
                        //$os += $tov_val['no_sum'];
                        //$ns += $tov_val['ns_sum'];
                        //$rs += $tov_val['r_sum'];
                        $nc += $tov_val['now_count'];
                        //$a1 += $tov_val['no_sum'] + $tov_val['ns_sum'];
                       // $a2 += $tov_val['no_sum'] + $tov_val['ns_sum'] + $tov_val['r_sum'];
                       // $prih += $tov_val['now_count'] + $tov_val['count'];
					   $prih += $tov_val['ostatok'] + $tov_val['count'];
					    $rs += $tov_val['ssum'];

                        //$a3 += $tov_val['no_c'];
                        //$a4 += $tov_val['ns_c'];
                        //$a5 += $tov_val['r_c'];

                       // $all_c += $tov_val['count'];
                       // $all_a1 += $tov_val['no_sum'];
                        //$all_a2 += $tov_val['ns_sum'];
                        //$all_a3 += $tov_val['r_sum'];
                        //$all_a4 += $tov_val['no_sum'] + $tov_val['ns_sum'];
                       // $all_a5 += $tov_val['no_sum'] + $tov_val['ns_sum'] + $tov_val['r_sum'];
                       // $all_a6 += $tov_val['no_c'];
                       // $all_a7 += $tov_val['ns_c'];
                       // $all_a8 += $tov_val['r_c'];

                        $i++;
                    }
                    $aSheet->setCellValue('D' . $all, $count);
                   $aSheet->setCellValue('E' . $all, $rs);
                   // $aSheet->setCellValue('F' . $all, $ns);
                    //$aSheet->setCellValue('G' . $all, $rs);
                    $aSheet->setCellValue('C' . $all, $nc);
                    $aSheet->setCellValue('B' . $all, $prih);
                   // $aSheet->setCellValue('K' . $all, $a1);
                    //$aSheet->setCellValue('L' . $all, $a2);

                    //$aSheet->setCellValue('H' . $all, $a3);
                    //$aSheet->setCellValue('I' . $all, $a4);
                    //$aSheet->setCellValue('J' . $all, $a5);

                    $aSheet->getStyle('A' . $all)->applyFromArray($boldFont);
                    $aSheet->getStyle('B' . $all)->applyFromArray($boldFont);
                    $aSheet->getStyle('D' . $all)->applyFromArray($boldFont);
                    $aSheet->getStyle('E' . $all)->applyFromArray($boldFont);
                   // $aSheet->getStyle('F' . $all)->applyFromArray($boldFont);
                    $aSheet->getStyle('C' . $all)->applyFromArray($boldFont);
                   // $aSheet->getStyle('G' . $all)->applyFromArray($boldFont);
                   // $aSheet->getStyle('K' . $all)->applyFromArray($boldFont);
                   // $aSheet->getStyle('L' . $all)->applyFromArray($boldFont);
                    //$aSheet->getStyle('H' . $all)->applyFromArray($boldFont);
                    //$aSheet->getStyle('J' . $all)->applyFromArray($boldFont);
                    //$aSheet->getStyle('I' . $all)->applyFromArray($boldFont);
                }
                $i++;
                $aSheet->setCellValue('A' . $i, 'ВСЕГО');
                $aSheet->setCellValue('D' . $i, $count);
                   //$aSheet->setCellValue('E' . $all, $os);
                   // $aSheet->setCellValue('F' . $all, $ns);
                    //$aSheet->setCellValue('G' . $all, $rs);
                    $aSheet->setCellValue('C' . $i, $nc);
                    $aSheet->setCellValue('B' . $i, $prih);
                $aSheet->setCellValue('E' . $i, $rs);
               // $aSheet->setCellValue('F' . $i, $all_a2);
                ////$aSheet->setCellValue('G' . $i, $all_a3);
                //$aSheet->setCellValue('K' . $i, $all_a4);
               // $aSheet->setCellValue('L' . $i, $all_a5);
                //$aSheet->setCellValue('H' . $i, $all_a6);
                //$aSheet->setCellValue('I' . $i, $all_a7);
               // $aSheet->setCellValue('J' . $i, $all_a8);

                $aSheet->getStyle('A' . $i)->applyFromArray($boldFont);
                $aSheet->getStyle('B' . $i)->applyFromArray($boldFont);
                $aSheet->getStyle('D' . $i)->applyFromArray($boldFont);
                //$aSheet->getStyle('E' . $i)->applyFromArray($boldFont);
                //$aSheet->getStyle('F' . $i)->applyFromArray($boldFont);
                $aSheet->getStyle('C' . $i)->applyFromArray($boldFont);
                //$aSheet->getStyle('G' . $i)->applyFromArray($boldFont);
                //$aSheet->getStyle('H' . $i)->applyFromArray($boldFont);
                //$aSheet->getStyle('I' . $i)->applyFromArray($boldFont);
                //$aSheet->getStyle('K' . $i)->applyFromArray($boldFont);
                //$aSheet->getStyle('L' . $i)->applyFromArray($boldFont);
               // $aSheet->getStyle('J' . $i)->applyFromArray($boldFont);


                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

                $objWriter->save('php://output');

                //header("Content-type: application/x-msexcel");


            }     
        if ($this->get->type == 5){
		
		if($this->post->method == 'list_brand'){
		
		$arr = array();
		$sql = "SELECT  `ws_articles`.`brand_id` 
FROM  `ws_articles` 
JOIN  `ws_articles_sizes` ON  `ws_articles_sizes`.`id_article` =  `ws_articles`.`id` 
JOIN  `ws_sizes` ON  `ws_articles_sizes`.`id_size` =  `ws_sizes`.`id` 
JOIN  `ws_articles_colors` ON  `ws_articles_sizes`.`id_color` =  `ws_articles_colors`.`id` 
LEFT JOIN  `ws_order_articles` ON  `ws_articles`.`id` =  `ws_order_articles`.`article_id` 
AND  `ws_articles_sizes`.`id_size` =  `ws_order_articles`.`size` 
AND  `ws_articles_sizes`.`id_color` =  `ws_order_articles`.`color` 
WHERE  `ws_articles_sizes`.`count` >0
AND  `ws_articles`.`ucenka` = ".$this->post->proc."
GROUP BY  `ws_articles`.`brand_id` 
ORDER BY  `ws_articles`.`brand_id` ASC";
 foreach(wsActiveRecord::findByQueryArray($sql) as $a){
 $arr[] = $a->brand_id;
 }
		
		die(json_encode($arr));
		}
		require_once('PHPExel/PHPExcel.php');
		require_once("PHPExel/PHPExcel/Writer/Excel5.php");
		$proc = (int)$this->post->proc;
		$start = (int)$this->post->start;
		$brand = (int)$this->post->brand;
 ini_set('memory_limit', '2048M');
	set_time_limit(2800);
	

                    $q = "SELECT  `ws_articles`.`id`,
					`ws_articles`.`old_price`,
					`ws_articles`.`price`,
					`ws_articles`.`category_id`,
					`ws_articles`.`brand_id`,
					`ws_articles`.`ctime`,
					`ws_articles`.`data_ucenki`,
					`ws_articles_sizes`.`code` AS  `acode` ,
					`ws_articles_sizes`.`count` AS  `sklad` ,
					`ws_articles`.`model` ,  `ws_articles`.`brand` ,
					`ws_sizes`.`size` AS  `sizes` ,  `ws_articles_colors`.`name` AS  `colors` ,
					SUM(if(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,0) ) AS  `sum_order` ,
					sum(if(`ws_order_articles`.`count`=0,1,0)) as `sum_ret`
FROM  `ws_articles` 
JOIN  `ws_articles_sizes` ON  `ws_articles_sizes`.`id_article` =  `ws_articles`.`id` 
JOIN  `ws_sizes` ON  `ws_articles_sizes`.`id_size` =  `ws_sizes`.`id` 
JOIN  `ws_articles_colors` ON  `ws_articles_sizes`.`id_color` =  `ws_articles_colors`.`id` 
LEFT JOIN  `ws_order_articles` ON  `ws_articles`.`id` =  `ws_order_articles`.`article_id` 
AND  `ws_articles_sizes`.`id_size` =  `ws_order_articles`.`size` 
AND  `ws_articles_sizes`.`id_color` =  `ws_order_articles`.`color` 
WHERE  `ws_articles_sizes`.`count` >0
AND  `ws_articles`.`ucenka` = ".$proc."
and `ws_articles`.`brand_id` = ".$brand."
GROUP BY  `ws_articles_sizes`.`code` 
ORDER BY  `ws_articles`.`category_id`, `ws_articles`.`brand_id` ASC";

                    $articles = wsActiveRecord::findByQueryArray($q);
					
		
     $name = 'otchet_ucenka_'.$proc.'_'.date('d-m-Y');
	 
     $filename = $name.'.xls';
	 
	 $path1file = INPATH . "admin_files/views/chart/". $filename;
	  $boldFont = array('font' => array('bold' => true));
			if($start == 0){
				
				$pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle((string)$proc);
				
					$aSheet->setCellValue('A1', 'Бренд');
					$aSheet->setCellValue('B1', 'Категория');
                    $aSheet->setCellValue('C1', 'Название');
					
					$aSheet->setCellValue('D1', 'Артикул');
                    $aSheet->setCellValue('E1', 'Цвет');
                    $aSheet->setCellValue('F1', 'Размер');
                    $aSheet->setCellValue('G1', 'Приход');
                    $aSheet->setCellValue('H1', 'Расход');
					$aSheet->setCellValue('I1', 'Возвраты');
					$aSheet->setCellValue('J1', 'Остаток');
					$aSheet->setCellValue('K1', 'Цена до уценки');
					$aSheet->setCellValue('L1', 'Цена после уценки');
					$aSheet->setCellValue('M1', 'Добавлен');
					$aSheet->setCellValue('N1', 'Уценен');

					$i = 2;
                   
                    $aSheet->getStyle('A1:N1')->applyFromArray($boldFont);
				}else{
			$pExcel = PHPExcel_IOFactory::load($path1file);
			$pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
				$i = $start;
				}

                  //  $i = 2;
					
					$assoc = array();   
                    foreach ($articles as $article) {
					$cat = $article->category_id;
					//$assoc[$article->brand_id]['n_brand'] = $article->brand;
					$assoc[$article->brand_id][$cat][$article->acode]['category'] =  Shopcategories::CatName($article->category_id);
					$assoc[$article->brand_id][$cat][$article->acode]['model'] =  $article->model;
					$assoc[$article->brand_id][$cat][$article->acode]['color'] =  $article->colors;
					$assoc[$article->brand_id][$cat][$article->acode]['brand'] =  $article->brand;
				
				$assoc[$article->brand_id][$cat][$article->acode]['sizes'] =  $article->sizes;
					
					$q = "SELECT SUM(  `red_article_log`.`count` ) AS ctn
					FROM red_article_log
					inner join ws_articles on red_article_log.article_id = ws_articles.`id`
					WHERE red_article_log.type_id = 1
						and ws_articles.status = 3
					AND  `red_article_log`.`code` LIKE  '".$article->acode."'";
					
						$assoc[$article->brand_id][$cat][$article->acode]['prichod'] =  wsActiveRecord::findByQueryArray($q)[0]->ctn;
						$assoc[$article->brand_id][$cat][$article->acode]['order'] =  $article->sum_order;
						$assoc[$article->brand_id][$cat][$article->acode]['returns'] =  $article->sum_ret;
					
					$s = "SELECT sum(ws_order_articles_vozrat.`count) as allcount 
					FROM ws_order_articles_vozrat
					WHERE	ws_order_articles_vozrat.status = 0
					and  ws_order_articles_vozrat.`count` > 0
					and ws_order_articles_vozrat.`cod` LIKE  '".$article->acode."' ";
					
					$assoc[$article->brand_id][$cat][$article->acode]['sklad'] =  $article->sklad+wsActiveRecord::findByQueryArray($s)[0]->allcount;
					$assoc[$article->brand_id][$cat][$article->acode]['old_price'] =  $article->old_price;
					$assoc[$article->brand_id][$cat][$article->acode]['price'] =  $article->price;
					$assoc[$article->brand_id][$cat][$article->acode]['ctime'] =  date("d-m-Y", strtotime($article->ctime));
					$assoc[$article->brand_id][$cat][$article->acode]['ucenka'] =  date("d-m-Y", strtotime($article->data_ucenki));
				
                    }
					
					//echo '<pre>';
					//echo print_r($assoc);
					//echo '</pre>';
					//die();
					//$i = $start;
					foreach($assoc as $brand=>$cat){
					$p = 0;
					$r = 0;
					$v = 0;
					$o = 0;
					$o_p = 0;
					$pr = 0;
					$b = '';
					$c = '';
					//$b = $brand['n_brand'];
					//$c = $q['category'];
					foreach($cat as $c => $code){
					foreach($code as $cod=>$q){
					$b=$q['brand'];
					$c = $q['category'];
					 $aSheet->setCellValue('A' . $i, $q['brand']);
					 $aSheet->setCellValue('B' . $i, $q['category']);
					 $aSheet->setCellValue('C' . $i, $q['model']);
					 $aSheet->setCellValue('D' . $i, $cod);
					  $aSheet->setCellValue('E' . $i, $q['color']);
					  
					  $aSheet->setCellValue('F' . $i, $q['sizes']);
					  $aSheet->setCellValue('G' . $i, $q['prichod']);
					  $aSheet->setCellValue('H' . $i, $q['order']);
					  $aSheet->setCellValue('I' . $i, $q['returns']);
					  $aSheet->setCellValue('J' . $i, $q['sklad']);
					  $aSheet->setCellValue('K' . $i, $q['old_price']);
					  $aSheet->setCellValue('L' . $i, $q['price']);
					  $aSheet->setCellValue('M' . $i, $q['ctime']);
					  $aSheet->setCellValue('N' . $i, $q['ucenka']);
					  
					$p += (int)$q['prichod']?(int)$q['prichod']:0;
					$r += (int)$q['order']?(int)$q['order']:0;
					$v += (int)$q['returns']?(int)$q['returns']:0;
					$o += (int)$q['sklad']?(int)$q['sklad']:0;
					$o_p += (int)$q['old_price']?(int)$q['old_price']:0;
					$pr += (int)$q['price']?(int)$q['price']:0;
					
					$i++;
					}
					}
					//$aSheet->setCellValue('A' . $i, $b);
					//$aSheet->setCellValue('B' . $i, $c);
					//$aSheet->setCellValue('G' . $i, $p);
					//$aSheet->setCellValue('H' . $i, $r);
					//$aSheet->setCellValue('I' . $i, $v);
					//$aSheet->setCellValue('J' . $i, $o);
					//$aSheet->setCellValue('K' . $i, $o_p);
					//$aSheet->setCellValue('L' . $i, $pr);
					//$aSheet->getStyle('A'.$i.':N'.$i)->applyFromArray($boldFont);
					
					//$i++;
					
					}
					
$objWriter = new PHPExcel_Writer_Excel5($pExcel);		
				
$end = $this->post->end - $i;

//if($end <= $this->post->start){	
//$objWriter->save($path1file);
//die(json_encode(array('exit'=>"Отчёт сформирован!", 'src'=>'https://www.red.ua/admin_files/views/chart/'.$filename)));
//}else{
$objWriter->save($path1file);
die(json_encode(array('start'=>(int)$i, 'end'=>(int)$this->post->end, 'proc'=>(int)$proc, 'exit'=>"Отчёт сформирован!", 'src'=>'https://www.red.ua/admin_files/views/chart/'.$filename)));	
//}		

                    

                    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                    header("Cache-Control: no-cache, must-revalidate");
                    header("Pragma: no-cache");
                    header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

                    $objWriter->save('php://output');

             
		   die('ok');
        }
		
        if ($this->get->type == 6) {
            ini_set('memory_limit', '1024M');
            $from = strtotime($_POST['order_from']);
            $to = strtotime($_POST['order_to']);


            $q = 'SELECT ws_order_articles.*, ws_orders.date_create, ws_orders.delivery_type_id, ws_orders.status FROM ws_order_articles
                JOIN ws_orders on ws_orders.id = ws_order_articles.order_id
                JOIN ws_articles on ws_articles.id = ws_order_articles.article_id
                WHERE ws_articles.skidka_block = 1
                AND date_create <="' . date('Y-m-d', $to) . ' 23:59:59"
                AND date_create >= "' . date('Y-m-d', $from) . ' 00:00:00"
                ';
            if (@$_POST['no_new']) {
                $q .= 'AND status in (1,3,4,6,8,9,11,13,14,15,16)';
            }
            $articles = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q);

            $mas = array();
            $i = 0;
            foreach ($articles as $article) {
                $i++;
                $count_now = $article->getCountNow();
                if (in_array($article->getDeliveryTypeId(), array(1, 2, 3, 7, 11, 12, 13))) { //Shops
                    $mas[$article->getArticleId()]['types'][1][] = $article;
                    $mas[$article->getArticleId()]['counts'][$article->getSize() . '_' . $article->getColor()] = $count_now;
                }
                if (in_array($article->getDeliveryTypeId(), array(8))) { // Nova
                    $mas[$article->getArticleId()]['types'][2][] = $article;
                    $mas[$article->getArticleId()]['counts'][$article->getSize() . '_' . $article->getColor()] = $count_now;
                }
                if (in_array($article->getDeliveryTypeId(), array(4))) { //Ukr
                    $mas[$article->getArticleId()]['types'][3][] = $article;
                    $mas[$article->getArticleId()]['counts'][$article->getSize() . '_' . $article->getColor()] = $count_now;
                }
                if (in_array($article->getDeliveryTypeId(), array(9, 10))) { // Kurer
                    $mas[$article->getArticleId()]['types'][4][] = $article;
                    $mas[$article->getArticleId()]['counts'][$article->getSize() . '_' . $article->getColor()] = $count_now;
                }
            }

            require_once('PHPExel/PHPExcel.php');
            $name = 'otchetexel';
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
            $aSheet->setCellValue('A1', 'Товар');
            $aSheet->setCellValue('B1', 'Загружено');
            $aSheet->setCellValue('C1', 'Цена');
            $aSheet->setCellValue('D1', 'Продано');
            $aSheet->setCellValue('E1', 'Цена');
            $aSheet->setCellValue('F1', 'Остаток');
            $aSheet->setCellValue('G1', 'Цена');
            $aSheet->setCellValue('H1', 'Магазины');
            $aSheet->setCellValue('I1', 'Цена');
            $aSheet->setCellValue('J1', 'Курьером');
            $aSheet->setCellValue('K1', 'Цена');
            $aSheet->setCellValue('L1', 'Новой почтой');
            $aSheet->setCellValue('M1', 'Цена');
            $aSheet->setCellValue('N1', 'Укрпочтой');
            $aSheet->setCellValue('O1', 'Цена');
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
            $aSheet->getStyle('G1')->applyFromArray($boldFont);
            $aSheet->getStyle('H1')->applyFromArray($boldFont);
            $aSheet->getStyle('I1')->applyFromArray($boldFont);
            $aSheet->getStyle('J1')->applyFromArray($boldFont);
            $aSheet->getStyle('K1')->applyFromArray($boldFont);
            $aSheet->getStyle('L1')->applyFromArray($boldFont);
            $aSheet->getStyle('M1')->applyFromArray($boldFont);
            $aSheet->getStyle('N1')->applyFromArray($boldFont);
            $aSheet->getStyle('O1')->applyFromArray($boldFont);
            $i = 2;
            foreach ($mas as $k => $m) {
                $article_r = new Shoparticles($k);
                $count = 0;
                foreach ($m['counts'] as $v) {
                    $count += $v;
                }

                $price = array();
                $counts = array();
                $price['0'] = 0;
                $price['1'] = 0;
                $price['2'] = 0;
                $price['3'] = 0;
                $price['4'] = 0;
                $counts['0'] = 0;
                $counts['1'] = 0;
                $counts['2'] = 0;
                $counts['3'] = 0;
                $counts['4'] = 0;
                $name = '';
                if (isset($m['types'][1])) {
                    foreach ($m['types'][1] as $art) {
                        $price['1'] += $art->getPrice() * $art->getCount();
                        $price['0'] += $art->getPrice() * $art->getCount();
                        $counts['1'] += $art->getCount();
                        $counts['0'] += $art->getCount();
                        $name = $art->getTitle();
                    }
                }

                if (isset($m['types'][2])) {
                    foreach ($m['types'][2] as $art) {
                        $price['2'] += $art->getPrice() * $art->getCount();
                        $price['0'] += $art->getPrice() * $art->getCount();
                        $counts['2'] += $art->getCount();
                        $counts['0'] += $art->getCount();
                        $name = $art->getTitle();
                    }
                }

                if (isset($m['types'][3])) {
                    foreach ($m['types'][3] as $art) {
                        $price['3'] += $art->getPrice() * $art->getCount();
                        $price['0'] += $art->getPrice() * $art->getCount();
                        $counts['3'] += $art->getCount();
                        $counts['0'] += $art->getCount();
                        $name = $art->getTitle();
                    }
                }

                if (isset($m['types'][4])) {
                    foreach ($m['types'][4] as $art) {
                        $price['4'] += $art->getPrice() * $art->getCount();
                        $price['0'] += $art->getPrice() * $art->getCount();
                        $counts['4'] += $art->getCount();
                        $counts['0'] += $art->getCount();
                        $name = $art->getTitle();
                    }
                }

                $aSheet->setCellValue('A' . $i, $name);
                $aSheet->setCellValue('B' . $i, $count + $counts['0']);
                $aSheet->setCellValue('C' . $i, ($count + $counts['0']) * $article_r->getPrice());
                $aSheet->setCellValue('D' . $i, $counts['0']);
                $aSheet->setCellValue('E' . $i, $price['0']);
                $aSheet->setCellValue('F' . $i, $count);
                $aSheet->setCellValue('G' . $i, $count * $article_r->getPrice());

                $aSheet->setCellValue('H' . $i, $counts['1']);
                $aSheet->setCellValue('I' . $i, $price['1']);

                $aSheet->setCellValue('J' . $i, $counts['4']);
                $aSheet->setCellValue('K' . $i, $price['4']);

                $aSheet->setCellValue('L' . $i, $counts['2']);
                $aSheet->setCellValue('M' . $i, $price['2']);

                $aSheet->setCellValue('N' . $i, $counts['3']);
                $aSheet->setCellValue('O' . $i, $price['3']);
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

        }
        if ($this->get->type == 7) {
            ini_set('memory_limit', '1024M');
            $from = strtotime($_POST['order_from']);
            $to = strtotime($_POST['order_to']);


            $q = 'SELECT * FROM ucenka_history
                           WHERE ctime <="' . date('Y-m-d', $to) . ' 23:59:59"
                           AND ctime >= "' . date('Y-m-d', $from) . ' 00:00:00"
                           ';

            $history = wsActiveRecord::useStatic('UcenkaHistory')->findByQuery($q);

            $mas = array();
            $i = 0;
            $prest = 'Y-m';
            if ($this->post->to_day) {
                $prest = 'Y-m-d';
            }
            foreach ($history as $h) {
                $i++;
                $proc = round($h->getProc() / 10) * 10;

                $article = new Shoparticles($h->getArticleId());
                if (!$this->post->to_day) {
                    $count = $article->getCountByDate(date('Y-m-1', strtotime($h->getCtime())), date('Y-m-31', strtotime($h->getCtime())));
                } else {
                    $count = $article->getCountByDate(date('Y-m-d', strtotime($h->getCtime())), date('Y-m-d', strtotime($h->getCtime())));
                }
                $mas[date($prest, strtotime($h->getCtime()))][$proc]['count'] = @$mas[date($prest, strtotime($h->getCtime()))][$proc]['count'] + 1;
                $mas[date($prest, strtotime($h->getCtime()))][$proc]['count_items'] = @$mas[date($prest, strtotime($h->getCtime()))][$proc]['count_items'] + $count;
                $mas[date($prest, strtotime($h->getCtime()))][$proc]['old_price'] = @$mas[date($prest, strtotime($h->getCtime()))][$proc]['old_price'] + ($count * $h->getOldPrice());
                $mas[date($prest, strtotime($h->getCtime()))][$proc]['price'] = @$mas[date($prest, strtotime($h->getCtime()))][$proc]['price'] + ($count * $h->getNewPrice());
            }
            require_once('PHPExel/PHPExcel.php');
            $name = 'otchetexel';
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
            $aSheet->setCellValue('A1', 'Дата');
            $aSheet->setCellValue('B1', 'Процент');
            $aSheet->setCellValue('C1', 'Моделей');
            $aSheet->setCellValue('D1', 'Едениц');
            $aSheet->setCellValue('E1', 'Сумма до');
            $aSheet->setCellValue('F1', 'Сумма после');
            $aSheet->setCellValue('G1', 'Разница');
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
            $aSheet->getStyle('G1')->applyFromArray($boldFont);
            $i = 2;
            foreach ($mas as $k => $m) {
                $aSheet->setCellValue('A' . $i, $k);
                $i++;
                ksort($m);
                foreach ($m as $k => $v) {
                    $aSheet->setCellValue('B' . $i, $k . '%');
                    $aSheet->setCellValue('C' . $i, $v['count']);
                    $aSheet->setCellValue('D' . $i, $v['count_items']);
                    $aSheet->setCellValue('E' . $i, $v['old_price']);
                    $aSheet->setCellValue('F' . $i, $v['price']);
                    $aSheet->setCellValue('G' . $i, $v['old_price'] - $v['price']);
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

        }
		if ($this->get->type == 8) {
			if(@$_POST['cat']){
$cats = wsActiveRecord::useStatic('Shopcategories')->findFirst(array('id' => (int)$_POST['cat'], 'active' => 1));
$arr = $cats->getKidsIds();
$arr = array_unique($arr);
			$cat = implode(",", $arr);
			}else{
			$cat = '';
			}
			//var_dump($cat);

				ini_set('memory_limit', '2048M');
				set_time_limit(2400);
					
				require_once('PHPExel/PHPExcel.php');
                $name = 'otchetexel_cat_' . $_POST['cat'];
                $filename = $name . '.xls';
                $pExcel = new PHPExcel();
				
				//$ind = 0;
				//foreach ($arr as $cat){
				
			
		//		if($ind > 0){
       // $pExcel->createSheet();
		//$pExcel->setActiveSheetIndex($ind);
		//$aSheet = $pExcel->getActiveSheet();
      ////  $aSheet->setTitle($cat);
        //Do you want something more here
   // }else{
        $pExcel->setActiveSheetIndex(0)->setTitle($_POST['cat']);
		$aSheet = $pExcel->getActiveSheet();
   // }

				
                $mas = array();
					$q = "SELECT ws_order_articles . * , ws_articles.model, ws_articles.brand, ws_articles.ctime AS dat_add,  `ws_articles`.`data_ucenki` , `ws_articles`.`ucenka` 
FROM ws_order_articles
INNER JOIN ws_articles ON ws_articles.id = ws_order_articles.article_id
INNER JOIN ws_articles_sizes ON ws_order_articles.article_id = ws_articles_sizes.id_article
INNER JOIN ws_orders ON ws_orders.id = ws_order_articles.order_id
WHERE ws_articles.active =  'y'
AND ws_articles_sizes.count >0
AND ws_articles.category_id in(". $cat.") ";
					
					//var_dump($q);

                $artucles = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q);
                foreach ($artucles as $article) {
					$brand = $article->getBrand();
                    $title = $article->getModel();
                    $color = $article->getColors()->getName();
                    $size = $article->getSizes()->getSize();
                    $date = date('d.m.Y', strtotime($article->getDatAdd()));
                    $date28 = $article->getUcenka()?date('d.m.Y', strtotime($article->getDataUcenki())):'';

					if(strtotime($date)<strtotime("-1 year")){
					$bolee_god = true;
					}else{
					$bolee_god = false;
					}
					if(strtotime($date)<strtotime("-60 day")){
					$bolee_60 = true;
					}else{
					$bolee_60 = false;
					}
							
					$proc = $article->getUcenka();//procent
                    $count = $article->getCount();
					
					$opder_price = $article->order->getAllAmount();
                    $real_price = $article->getPerc($opder_price);
                    $real_price = $real_price['price'] * (1 - ($article->getEventSkidka() / 100));

					$return = $count==0?1:0; 
					
                    if (isset($mas[$article->getArticleId()])) {
                        $mas[$article->getArticleId()]['count'] += $count;//prodano
                        $mas[$article->getArticleId()]['return'] += $return;
                        $mas[$article->getArticleId()]['price'] += $real_price * $count;
                    } else {
                        $q = 'SELECT sum(`count`) as cnt FROM ws_articles_sizes
							where `count` > 0 and id_article = ' . $article->getArticleId();
                        $now_count = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($q)->at(0)->cnt;
                        $mas[$article->getArticleId()]['title'] = $title;
						$mas[$article->getArticleId()]['brand'] = $brand;
						$mas[$article->getArticleId()]['color'] = $color;
						$mas[$article->getArticleId()]['size'] = $size;
						
						$mas[$article->getArticleId()]['count'] = $count;//prodano
						$mas[$article->getArticleId()]['return'] = $return;//vozvraty
						$mas[$article->getArticleId()]['now_count'] = $now_count;//ostatok
						$mas[$article->getArticleId()]['price'] = $real_price * $count;
						
						
						
                        $mas[$article->getArticleId()]['date'] = $date;//sozdan
                        $mas[$article->getArticleId()]['date28'] = $date28;//ucenks
						$mas[$article->getArticleId()]['proc'] = $proc;//porocent ucenki
						
						
						$mas[$article->getArticleId()]['bolee_god'] = $bolee_god?1:0;//ostatok
						//$mas[$article->getArticleId()]['return_5'] = $return>5?$now_count==1?$now_count:'':'';//ostatok
						$mas[$article->getArticleId()]['uc50_ret_p'] = $proc>=50?1:0;//ostatok
						$mas[$article->getArticleId()]['b60_prod_0'] = $bolee_60?1:0;
                       
                        

                    }

                }

               
                

                $aSheet->getColumnDimension('A')->setWidth(18);
                $aSheet->getColumnDimension('B')->setWidth(15);
                $aSheet->getColumnDimension('C')->setWidth(15);
                $aSheet->getColumnDimension('D')->setWidth(10);
                $aSheet->getColumnDimension('E')->setWidth(10);
                $aSheet->getColumnDimension('F')->setWidth(10);
                $aSheet->getColumnDimension('G')->setWidth(10);
                $aSheet->getColumnDimension('H')->setWidth(10);
				$aSheet->getColumnDimension('I')->setWidth(19);
				$aSheet->getColumnDimension('J')->setWidth(16);
				$aSheet->getColumnDimension('K')->setWidth(16);
				$aSheet->getColumnDimension('L')->setWidth(16);
				$aSheet->getColumnDimension('M')->setWidth(16);
				$aSheet->getColumnDimension('N')->setWidth(16);
				$aSheet->getColumnDimension('O')->setWidth(16);
				$aSheet->getColumnDimension('P')->setWidth(16);

                $aSheet->setCellValue('A1', 'Бренд:'); // Наименование
				$aSheet->setCellValue('B1', 'Модель:'); // Наименование
				$aSheet->setCellValue('C1', 'Цвет:'); // Наименование
				$aSheet->setCellValue('D1', 'Размер:'); // Наименование
                $aSheet->setCellValue('E1', 'Приход'); // Цена
                $aSheet->setCellValue('F1', 'Расход'); // к-во всего
				$aSheet->setCellValue('G1', 'Возвраты'); // к-во всего
                $aSheet->setCellValue('H1', 'Остаток'); // к-во прод.
                $aSheet->setCellValue('I1', 'Общая сумма продаж');
                $aSheet->setCellValue('J1', 'Дата добавления');
                $aSheet->setCellValue('K1', 'Дата уценки');
                $aSheet->setCellValue('L1', 'Процент уценки');
				$aSheet->setCellValue('M1', 'Более 1 год на сайте');
				$aSheet->setCellValue('N1', 'Возвратов больше 5 и 1 в остатке');
				$aSheet->setCellValue('O1', 'Уценка больше 50% и возвраты больше чем приход');
				$aSheet->setCellValue('P1', 'Более 60 дней на сайте и 0 продаж');
				

                $boldFont = array('font' => array('bold' => true));
				
                $aSheet->getStyle('A1:P1')->applyFromArray($boldFont);

                $i = 2;
                    foreach ($mas as $tov_kay => $tov_val) {
                        $aSheet->setCellValue('A' . $i, $tov_val['brand']);
						$aSheet->setCellValue('B' . $i, $tov_val['title']);
						$aSheet->setCellValue('C' . $i, $tov_val['color']);
						$aSheet->setCellValue('D' . $i, $tov_val['size']);
						$aSheet->setCellValue('E' . $i, $tov_val['now_count'] + $tov_val['count']);
                        $aSheet->setCellValue('F' . $i, $tov_val['count']);
						$aSheet->setCellValue('G' . $i, $tov_val['return']);
						$aSheet->setCellValue('H' . $i, $tov_val['now_count']);
                        $aSheet->setCellValue('I' . $i, $tov_val['price']);
                        $aSheet->setCellValue('J' . $i, $tov_val['date']);
                        $aSheet->setCellValue('K' . $i, $tov_val['date28']);
                        $aSheet->setCellValue('L' . $i, $tov_val['proc']);
						 $aSheet->setCellValue('M' . $i, $tov_val['bolee_god']);
						//  $aSheet->setCellValue('N' . $i, $tov_val['return_5']);
						  $aSheet->setCellValue('O' . $i, $tov_val['uc50_ret_p']);
						    $aSheet->setCellValue('P' . $i, $tov_val['b60_prod_0']);

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

                //header("Content-type: application/x-msexcel");


            }
        if ($this->get->type == 9) {

            ini_set('memory_limit', '1024M');

            $q1 = "SELECT t1.id, t1.parent_id, t1.name, SUM( t3.stock ) AS 'ostatok'
				FROM  `ws_categories` t1
				RIGHT JOIN  `ws_articles` t3 ON t1.id = t3.category_id
				GROUP BY t1.id 
				ORDER BY  t1.name ASC";

            $artucles = wsActiveRecord::useStatic('Shopcategories')->findByQuery($q1);

            require_once('PHPExel/PHPExcel.php');
            $kount = 1;
            $filename = 'otchet_tov_group_' . date("Y-m-d_H:i:s") . '.xls';
            $pExcel = new PHPExcel();
            $pExcel->setActiveSheetIndex(0);
            $aSheet = $pExcel->getActiveSheet();
            $aSheet->setTitle('Товары по группам');
            $aSheet->getColumnDimension('A')->setWidth(45);
            $aSheet->getColumnDimension('B')->setWidth(10);
            $aSheet->getColumnDimension('C')->setWidth(10);
            $aSheet->getColumnDimension('D')->setWidth(10);

            $aSheet->setCellValue('A1', 'Категория');
            $aSheet->setCellValue('B1', 'Приход');
            $aSheet->setCellValue('C1', 'Расход');
            $aSheet->setCellValue('D1', 'Остаток');

            $i = 2;

            $mascat = array();
            foreach ($artucles as $cat) {
                $mascat[$cat->getRoutez()] = $cat;
            }
            ksort($mascat);

            foreach ($mascat as $val => $article) {
                $q_in = "SELECT SUM( t4.`count` ) as rashod
						FROM  `ws_categories` t1
						RIGHT JOIN  `ws_categories` t2 ON t1.id = t2.parent_id
						RIGHT JOIN  `ws_articles` t3 ON t2.id = t3.category_id
						INNER JOIN `ws_order_articles` t4 ON t3.id = t4.article_id
						WHERE t2.`id` =" . $article->getId() . "
						GROUP BY t2.id";
                $rashod = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q_in);
//if (!(@$rashod[0]->getRashod())) var_dump(@$rashod[0]);

                $rasho = (@$rashod[0]) ? $rashod[0]->getRashod() : 0;


                $aSheet->setCellValue('A' . $i, $val);
                $aSheet->setCellValue('B' . $i, $article->getOstatok() + $rasho);
                $aSheet->setCellValue('C' . $i, $rasho);
                $aSheet->setCellValue('D' . $i, $article->getOstatok());
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

           // header("Content-type: application/x-msexcel");


        }
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

           // header("Content-type: application/x-msexcel");


        }
        if ($this->get->type == 11) {
            ini_set('memory_limit', '1024M');
            $from = strtotime($_POST['order_from']);
            $to = strtotime($_POST['order_to']);
            $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('status in (1,3,4,5,6,7,8,9,10,11,12,13,14,15,16)', 'delivery_type_id in (4,8,10)', 'date_create <="' . date('Y-m-d', $to) . ' 23:59:59" and date_create >= "' . date('Y-m-d', $from) . ' 00:00:00"'), array('date_create' => 'ASC'));

            $mas = array();

            foreach ($orders as $order) {
                $city = str_replace(array('г.', 'м.', 'c.', 'п.г.т.', 'пгт.', 'с.', 'п.', 'пт.', 'п.т.', 'т.'), '', $order->getCity());
                $city = trim(mb_strtolower($city));

                if (in_array($order->getDeliveryTypeId(), array(4))) {
                    $mas[$city][1][] = $order;
                } elseif (in_array($order->getDeliveryTypeId(), array(8))) {
                    $mas[$city][2][] = $order;
                } elseif (in_array($order->getDeliveryTypeId(), array(10))) {
                    $mas[$city][3][] = $order;
                }
            }
            ksort($mas);

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
            $aSheet->setCellValue('A1', 'Город');
            $aSheet->setCellValue('B1', 'Сумма');
            $aSheet->setCellValue('C1', 'Количество заказов');
            $aSheet->setCellValue('D1', 'Количество единиц');
            $aSheet->setCellValue('F1', 'Способы доставки');
            $aSheet->setCellValue('E2', 'Укрпочта');
            $aSheet->setCellValue('F2', 'Новая почта');
            $aSheet->setCellValue('G2', 'Курьером по Украине');
            $aSheet->setCellValue('H1', 'к-во клиентов');

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
            $aSheet->getStyle('E2')->applyFromArray($boldFont);
            $aSheet->getStyle('F2')->applyFromArray($boldFont);
            $aSheet->getStyle('G2')->applyFromArray($boldFont);
            $aSheet->getStyle('H1')->applyFromArray($boldFont);
            $i = 3;
            foreach ($mas as $k => $m) {
                $aSheet->setCellValue('A' . $i, $k);
                $aSheet->setCellValue('C' . $i, count(@$m['1']) + count(@$m['2']) + count(@$m['3']));
                $aSheet->setCellValue('E' . $i, count(@$m['1']));
                $aSheet->setCellValue('F' . $i, count(@$m['2']));
                $aSheet->setCellValue('G' . $i, count(@$m['3']));

                $kount_a = 0;
                $sum = 0;
                $cus = array();
                foreach ($m as $kd => $obj) {
                    foreach ($obj as $order) {
                        $kount_a += $order->countArticlesSum();
                        $sum += Number::formatFloat($order->getAmount(), 2);
                        $cus[$order->getCustomerId()] = 1;
                    }
                }
                $aSheet->setCellValue('D' . $i, $kount_a);
                $aSheet->setCellValue('B' . $i, $sum);
                $aSheet->setCellValue('H' . $i, count($cus));
                $i++;
            }

            require_once("PHPExel/PHPExcel/Writer/Excel5.php");
            $objWriter = new PHPExcel_Writer_Excel5($pExcel);

            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

           // header("Content-type: application/x-msexcel");

            $objWriter->save('php://output');
        }
        if ($this->get->type == 12) {
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

            //header("Content-type: application/x-msexcel");

            $objWriter->save('php://output');
        }
		if ($this->get->type == 13) {

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
               // $aSheet->getStyle('A1')->applyFromArray($boldFont);
                $aSheet->getStyle('A1:J1')->applyFromArray($boldFont);
				$aSheet->getStyle('E2:J1')->applyFromArray($boldFont);
               // $aSheet->getStyle('C1')->applyFromArray($boldFont);
                //$aSheet->getStyle('D1')->applyFromArray($boldFont);
				//$aSheet->getStyle('E1')->applyFromArray($boldFont);
				//$aSheet->getStyle('F1')->applyFromArray($boldFont);
				//$aSheet->getStyle('G1')->applyFromArray($boldFont);
				

               
				
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

                //$aSheet->getStyle('A' . $i)->applyFromArray($boldFont);
                //$aSheet->getStyle('B' . $i)->applyFromArray($boldFont);
               // $aSheet->getStyle('C' . $i)->applyFromArray($boldFont);
               // $aSheet->getStyle('D' . $i)->applyFromArray($boldFont);
               // $aSheet->getStyle('E' . $i)->applyFromArray($boldFont);
				


                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

                $objWriter->save('php://output');

               // header("Content-type: application/x-msexcel");


            }
		if ($this->get->type == 14) {
            ini_set('memory_limit', '1024M');
            $to = strtotime($_POST['order_to']);
            $from = strtotime($_POST['order_from']);


            $q = 'SELECT u.*, `as`.code, a.brand, a.model FROM ucenka_history as u
					LEFT JOIN ws_articles_sizes as `as` ON u.article_id = `as`.id_article
					LEFT JOIN ws_articles as a ON as.id_article = a.id
					WHERE u.ctime <="' . date('Y-m-d', $to) . ' 23:59:59"
					AND u.ctime >= "' . date('Y-m-d', $from) . ' 00:00:00"';

            $mas = wsActiveRecord::findByQueryArray($q);
            // $mas[$code]['proc'] = round($h->getProc() / 10) * 10;

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

            //header("Content-type: application/x-msexcel");

            $objWriter->save('php://output');

        }	
		if ($this->get->type == 15) { 
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
			
			}
		if ($this->get->type == 16) { 
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
			
			}
		if ($this->get->type == 17) { 
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
	 $path1file = INPATH . "admin_files/views/chart/". $filename;
				
                
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
die(json_encode(array('exit'=>"Отчёт сформирован!", 'src'=>'https://www.red.ua/admin_files/views/chart/'.$filename)));
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

			
			}
if ($this->get->type == 18) {
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

}
		if ($this->get->type == 19) { //отчет по товарам и остатку
		 
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
	 $path1file = INPATH . "admin_files/views/chart/". $filename;
				
                
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
				//$aSheet->setCellValue('R1', 'Дней с посл. заказа');
				
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

			/*	$item_order = strtotime($order->at(0)->dat);
				if($item_order < time()){
				$dey_order = (int)(time() - $item_order) / (24 * 60 * 60);
				}else{
				$dey_order = 0;
				}
				if((int)$dey_order > 1000) $dey_order=0;
			 $aSheet->setCellValue('R' . $i, (int)$dey_order);*/
			 
			 $i++;
			 }
			 
			 

                $objWriter = new PHPExcel_Writer_Excel5($pExcel);
				
				
$end = $this->post->end - 50;

		if($end <= $this->post->start){	
$objWriter->save($path1file);
die(json_encode(array('exit'=>"Отчёт сформирован!", 'src'=>'https://www.red.ua/admin_files/views/chart/'.$filename)));
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

			
			}
        if ($this->get->type == 20) {
		
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
        }
		
		if ($this->get->type == 21) { //отчет по пользователям
		
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
	 $path1file = INPATH . "admin_files/views/chart/". $filename;
				
                
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
die(json_encode(array('exit'=>"Отчёт сформирован!", 'src'=>'https://www.red.ua/admin_files/views/chart/'.$filename)));
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

			
			}
			if($this->get->type == 22){
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
			}
			
		if ($this->get->type == 'getbrends') { 
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
		 }
		if ($this->get->type == 'getmodels') { 
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

    public function importadvertinfo($file)
    {
        require_once('PHPExel/PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
        //$aSheet = $objPHPExcel->getActiveSheet();
		$aSheet = $objPHPExcel->getActiveSheet()->toArray();
$mas=array('model'=>$aSheet[1][3], 'price' =>$aSheet[1][32], 'min_price'=>$aSheet[1][35], 'max_skidka'=>$aSheet[1][38], 'nakladna'=>$aSheet[1][0]);

        return $mas;
    }
    public function importadvert($file)
    {
        require_once('PHPExel/PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
       // $aSheet = $objPHPExcel->getActiveSheet();
		$aSheet = $objPHPExcel->getActiveSheet()->toArray();
		
		$mas = array();
		unset($aSheet[0]);
		foreach($aSheet as $k => $m){
		if($m[1] != NULL) { $mas[] = array('sr'=>$m[20], 'color'=>$m[23], 'size'=>$m[26], 'count'=>$m[29]);}
		}
		 @unlink($file);
		 return $mas;
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
        echo $this->render('color/list.tpl.php');
    }

    public function colorAction()
    {
        if ((int)@$this->get->del > 0) {
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
        echo $this->render('color/edit.tpl.php');


    }


    //-------Sizes
    public function sizesAction()
    {
        $data = array();
        $this->view->sizes = wsActiveRecord::useStatic('Size')->findAll($data, array('category_id' => 'ASC', 'size' => 'ASC'));
        echo $this->render('size/list.tpl.php');
    }

    public function sizeAction()
    {
        if ((int)@$this->get->del > 0) {
            $sub = new Size((int)$this->get->del);
            if (!$sub->articles->count()) {
                $sub->destroy();
            }
            $this->_redir('sizes');
        }

        $sub = new Size($this->get->getId());

        if (count($_POST)) {
            foreach ($_POST as &$value)
                $value = stripslashes($value);
            $errors = array();

            if (!@$_POST['size'])
                $errors[] = $this->trans->get('Please fill size');

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
        echo $this->render('size/edit.tpl.php');


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
        }
		
		if ($this->post->method == 'deleteshop') {
		$mas = explode(',', $this->post->id);
		$result = array();
			if(@$this->post->mes){
			$mes = $this->post->mes;
			}else{
			$mes = '';
			}
			foreach($mas as $m){
            $c = new ShoporderarticlesVozrat($m);
            if ($c && $c->getId() && $c->getCount() > 0) { 
									$log_text = 'Удаление с возвратов';
									$log_text .= '<br> ( '.$mes.' )';
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
			$old_size = new Size($c->getSize());
			$old_color = new Shoparticlescolor($c->getColor());
			$text = $c->getTitle(). ' ' . $old_size->getSize() . ' ' . $old_color->getName();
OrderHistory::newHistory($this->user->getId(), $c->getOrderId(), 'Удаление без возврата.<br>Накладна №'.$this->post->nakladna, $text, $c->getArticleId());
            } else {
			$result['send'] = 0;
			$result['text'] = 'Ошибка. Товар не удален!';
			$result['ss'] = $mes;
			}
			}
				die(json_encode($result));
        }
		if(($this->post->method == 'return_order')){
		if(@$this->post->mes){
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
        $data = array();
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
	$order = wsActiveRecord::useStatic('Shoporders')->findFirst(array('id'=>(int)$r, 'status in(4,6,8,13,12)'));
	$c_or = wsActiveRecord::useStatic('ShopordersVozrat')->count(array("order_id"=>(int)$r, "date_create > '".date('Y-m-d 00:00:00')."' "));
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
	}
	}
	
	}
	die(json_encode($result));
	}
	if($this->get->method == 'forma103'){
	$ids = explode(',', $this->get->ids);
	$orders = wsActiveRecord::useStatic('ShopordersVozrat')->findAll(array('id in('.$this->get->ids.')'));
	foreach($orders as $vozrat){
	$ord = new Shoporders($vozrat->order_id);
	$vozrat->setStatus(3);
	$vozrat->setDateObrabotan(date('Y-m-d H:i:s'));
	$vozrat->setAdminObrabotan($this->user->id);
									
	//if(isset($_POST['comments']) and $_POST['comments'] !='')$vozrat->setComments($_POST['comments']);
									
	$vozrat->save();
	$remark = new Shoporderremarks();
                        $data = array(
                            'order_id' => $vozrat->order_id,
                            'date_create' => date("Y-m-d H:i:s"),
                            'remark' => 'Почтовый перевод '.($vozrat->amount+$vozrat->dop_suma).' грн.',
							'name' => $this->user->getMiddleName()
                        );
                        $remark->import($data);
                        $remark->save();
						
	$text = 'Доброго дня!<br>';
	$text.=$order->middle_name.' '.$order->name.', Вам відправлено поштовий переказ '.($vozrat->amount+$vozrat->dop_suma).' грн. за повернення замовлення №'.$ord->id.'<br>Місце отримання, поштове відділення Укр.Пошти: '.$ord->index;

	SendMail::getInstance()->sendEmail($ord->email, $ord->middle_name.' '.$ord->name, 'Поштовий переказ за замовлення №'.$order->id, $text, '', '', 'return@red.ua', 'RED.UA', 2, 'return@red.ua', $this->user->getMiddleName());
	
	}
	
	$this->view->order = $orders;
	
	//echo print_r($order);
	echo $this->render('', 'return_articles/forma103.ukr.post.php');
	die();
	}
        $this->view->order_status = array(1 => 'Принят', 2 => 'В процессе', 3 => 'Обработан', 4=>'Возврат', 5=> 'Отменён');
		
        if(isset($_GET['search'])){
		$data = array();
		if(@$this->get->status) $data['status'] = (int)$this->get->status;
		if(@$this->get->customer_id) $data['customer_id'] = (int)$this->get->customer_id;
		if(@$this->get->order) $data['order_id'] = (int)$this->get->order;
		if(@$this->get->create_from) $data[] = " date_create >='".date('Y-m-d 00:00:00', strtotime($this->get->create_from))."' ";
		if(@$this->get->create_to) $data[] = " date_create <= '".date('Y-m-d 23:59:59', strtotime($this->get->create_to))."' ";
		if(@$this->get->sposob) $data['sposob'] = (int)$this->get->sposob;
		//echo print_r($data);
		//die();
		$orders = wsActiveRecord::useStatic('ShopordersVozrat')->findAll($data);
		if($orders){
		$this->view->orders = $orders;
		
		 echo $this->render('return_articles/vozvrat_list.tpl.php');
		}
		//echo print_r($orders);
		//die();
		
		
		}elseif ($this->get->id) {
		$vozrat = new ShopordersVozrat((int)$this->get->id);
		
		if($vozrat->getId()){
		//if($vozrat->getStatus() == 1) {
		//$vozrat->setStatus(2);
		//$vozrat->save();
		//}
            $order = new Shoporders($vozrat->getOrderId());
            if ($order->getId()){
             /*   if ($this->get->del) {
                    $article = new ShoporderarticlesVozrat($this->get->del);
                    if ($article->getId()) {
                        $article->destroy();
                    }
                    $this->_redirect('/admin/vozrat/id/' . $order->getId());
                }*/

                if (count($_POST)) {
				//echo '<pre>';
				//echo print_r($m_a);
				//echo implode(",", $m_a);
				//echo print_r($_POST);
		
				//echo '</pre>';
				//die();
				
				 $m_a = array();
				 if (isset($_POST['order_status'])) {
				 $error = array();
				
				 foreach ($_POST as $k =>$v) {
				 if($v == 'on'){
                            $m = explode('_', $k);
                            if ($m[0] == 'item') $m_a[$m[2]] = $m[1];
							}
                        }

                        if ($_POST['order_status'] != $vozrat->getStatus()) {
						
						if ($_POST['order_status'] == 2 and $_POST['sposob'] == 1 and count($m_a)){
					//	echo '<pre>';
				//echo print_r($m_a);
				//echo implode(",", $m_a);
				//echo print_r($_POST);
		
				//echo '</pre>';
				//die();
						
						foreach($m_a as $k=>$art){
						//die($k.'-'.$art);
						//die($vozrat->order_id);
						
	$article_order = wsActiveRecord::useStatic('Shoporderarticles')->findFirst(array("order_id"=>(int)$vozrat->order_id, "article_id" =>(int)$art, "artikul LIKE '".trim($k)."' "));
	//echo print_r($article_order);
								//die();
								if($article_order){
									
		$article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array("id_article" => $article_order->article_id, "code LIKE '".$article_order->artikul."' "));
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
									if($order->getSkuCount() == 0) $order->setStatus(7);
									
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
									
									$order->calculateOrderPrice(true, false, true);
									
						$remark = new Shoporderremarks();
                        $data = array(
                            'order_id' => $order->getId(),
                            'date_create' => date("Y-m-d H:i:s"),
                            'remark' => 'Зачислен депозит '.$sum_return_all.' грн.',
							'name' => $this->user->getMiddleName()
                        );
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
									
									if(isset($_POST['comments']) and $_POST['comments'] !='')$vozrat->setComments($_POST['comments']);
									if(isset($_POST['nakladna']) and $_POST['nakladna'] !='')$vozrat->setNakladna($_POST['nakladna']);
									
									$vozrat->save();

									}
									 $this->_redir('vozrat');
									//echo $this->render('return_articles/vozvrat.tpl.php');
									}else{
									$this->view->error = $error;
									 $this->_redirect('/admin/vozrat/id/' . $vozrat->getId());
									}
                        }elseif($_POST['order_status'] == 2 and $_POST['sposob'] == 2){
						//die('poch');
						foreach($m_a as $k=>$art){
						
	$article_order = wsActiveRecord::useStatic('Shoporderarticles')->findFirst(array("order_id"=>$vozrat->order_id, "article_id" => $art, "artikul LIKE '".$k."' ", " count > 0"));
								
								if($article_order){
									
		$article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array("id_article" => $article_order->article_id, "code LIKE '".$article_order->artikul."' "));
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
									if($order->getSkuCount() == 0) $order->setStatus(7);
									
									$sum_return_all = $_POST['sum_voz_all'];
									$dop_sum = $_POST['dop_suma']?$_POST['dop_suma']:0;
									$sum_return = ($sum_return_all - $dop_sum);

									
									$order->calculateOrderPrice(true, false, true);

									
									$vozrat->setStatus(2);
									$vozrat->setAmount($sum_return);
									$vozrat->setDopSuma($dop_sum);
									$vozrat->setSposob(2);
									$vozrat->setDateVProcese(date('Y-m-d H:i:s'));
									$vozrat->setAdminVProcese($this->user->id);
									//$vozrat->setDateObrabotan(date('Y-m-d H:i:s'));
									//$vozrat->setAdminObrabotan($this->user->id);
									
									if(isset($_POST['comments']) and $_POST['comments'] !='')$vozrat->setComments($_POST['comments']);
									if(isset($_POST['nakladna']) and $_POST['nakladna'] !='')$vozrat->setNakladna($_POST['nakladna']);
									
									$vozrat->save();

								
									}else{
									$this->view->error = $error;
									 $this->_redirect('/admin/vozrat/id/' . $vozrat->getId());
									}
									 $this->_redir('vozrat');
									//echo $this->render('return_articles/vozvrat.tpl.php');

						}elseif($_POST['order_status'] == 5){
						$vozrat->setStatus(5);
									$vozrat->setDateObrabotan(date('Y-m-d H:i:s'));
									$vozrat->setAdminObrabotan($this->user->id);
									
									if(isset($_POST['comments']) and $_POST['comments'] !='')$vozrat->setComments($_POST['comments']);
									
									$vozrat->save();
									
						 $this->_redir('vozrat');
						}elseif($_POST['order_status'] == 3 and $vozrat->getSposob() == 2){
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
						
						OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Смена статуса',
                        OrderHistory::getStatusText($order->getStatus(), 7));
						
						foreach($order->articles as $art){
						
						$article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array("id_article" => $art->getArticleId(), "id_size" => $art->getSize(), "id_color" => $art->getColor(), "code LIKE '".$art->artikul."' "));
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
							
							OrderHistory::newHistory($this->user->getId(), $order->getId(), 'Клиенту зачислен депозит ('.$deposit.') грн. ',
                'C "' . $c_dep . '" на "' . $new_d . '"');
				
				$ok = '+';
				DepositHistory::newDepositHistory($this->user->getId(), $customer->getId(), $ok, $deposit, $order->getId());
							}
							
						 $order->save();
							
							
							$remark = new Shoporderremarks();
                        $data = array(
                            'order_id' => $order->getId(),
                            'date_create' => date("Y-m-d H:i:s"),
                            'remark' => 'Клиент не забрал посылку',
							'name' => $this->user->getMiddleName()
                        );
                        $remark->import($data);
                        $remark->save();
						
						}else{
						die($_POST['order_status']);
						}
						
						}
                        $this->_redirect('/admin/vozrat/id/' . $vozrat->getId());
                    }else{
					die();
					}
					
                   /* if (isset($_POST['edit'])) {
                        foreach ($_POST as $key => $val) {
                            $m = explode('_', $key);
                            if (count($m) == 2) {
                                if ($m[0] == 'count') {
                                    $article = new ShoporderarticlesVozrat($m[1]);
                                    $article->setCount($val);
                                    $article->save();
                                }
                            }
                        }
                    }*/
					
                   /* if (isset($_POST['add'])) {
                        foreach ($_POST as $key => $val) {
                            $m = explode('_', $key);
                            if (count($m) == 2) {
                                if ($m[0] == 'adda') {
                                    $o_article = new Shoporderarticles($m[1]);
                                    $article = new ShoporderarticlesVozrat();
                                    $article->import($o_article->export());
                                    $article->setCount($_POST['addcount_' . $m[1]]);
                                    $article->setId(null);
                                    $article->setOldArticle($o_article->getId());
                                    $article->setOrderId($order->getId());
                                    $article->save();
                                }
                            }
                        }

                    }*/


                }
				$this->view->vozrat = $vozrat;
                $this->view->order = $order;
                echo $this->render('return_articles/vozvrat.tpl.php');
            } else {
                $this->_redir('vozrat');
            }
			}else{
			$this->_redir('vozrat');
			}
        }else{
		echo $this->render('return_articles/vozvrat_list.tpl.php');
        }


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
       // $data = array();

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
        }
        if ($type == 'd') {
            $q = 'SELECT DATE_FORMAT(ctime,"%Y-%m-%d ") as dt, count(id) as ct FROM ws_articles
                       GROUP BY dt
                       ORDER BY dt ASC';
        }
        if ($type == 'y') {
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
			
			
	if(@$this->post->method == 'add_category'){
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
	}	
	if(@$this->post->method == 'add_articles'){
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
	$mas = $i;

	echo json_encode($mas);
	die();
	
	}
	
	if(@$this->get->add and $this->get->add == 'ar' ){
	 $categories = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active in(1,2)'));
        $this->view->categories = $categories;
	echo $this->render('skidki/add.tpl.php');
	}else if(@$this->get->add and $this->get->add == 'cat' ){
	 $categories = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active in (1,2)'));
        $this->view->categories = $categories;
	echo $this->render('skidki/add_cat.tpl.php');
	}else{
	 $data = array();
        $this->view->labels = wsActiveRecord::useStatic('Skidki')->findAll($data, array('start' => 'DESC'));
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
            foreach ($_POST as &$value)
                $value = stripslashes($value);
				
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
			
			if($n != $order->getName())$text .=' Имя с "'.$n.'" на "'.$order->getName().'"';
			if($f != $order->getMiddleName())$text .= ' Фамилия с '.$f.'" на "'.$order->getMiddleName().'"';
			if($c != $order->getCity())$text .=' Гогод с "'.$c.'" на "'.$order->getCity().'"';
			if($index != $order->getIndex())$text .=' Индекс с "'.$index.'" на "'.$order->getIndex().'"';
			if($s != $order->getStreet())$text .= ' Улица с "'.$s.'" на "'.$order->getStreet().'"';
			if($house != $order->getHouse())$text .= ' Дом с "'.$house.'" на "'.$order->getHouse().'"';
			if($flat != $order->getFlat())$text .= ' Квартира с "'.$flat.'" на "'.$order->getFlat().'"';
			if($sk != $order->getSklad())$text .= ' Склад с "'.$sk.'" на "'.$order->getSklad().'"';
			if($t != $order->getTelephone())$text .= ' Телефон с '.$t.'" на "'.$order->getTelephone().'"';
			if($em != $order->getEmail())$text .= ' Email с "'.$em.'" на "'.$order->getEmail().'"';
			if($d != $order->getDeliveryDate())$text .= ' Дата доставки с "'.$d.'" на "'.$order->getDeliveryDate().'"';
			
			
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
        if (@$_POST['ajax']) {
            if (count($errors)) {
                echo '
					<img src="/img/icons/error.png" alt="" class="page-img"/>
					<h1>Ошибка, эти поля обязательные:</h1>
					<div>
						' . implode(", ", $errors) . '
					</div>';
            }
            exit;
        }
        $this->view->errors = $errors;
        $this->view->order = $order;
        echo $this->render('shop/order-info-edit.tpl.php');
    }


    public function deliveryTypeAction()
    {
        if ('edit' == $this->cur_menu->getParameter()) {
            $delivery = new DeliveryType((int)$this->get->id);
            if (count($_POST)) {
                foreach ($_POST as &$value)
                    $value = stripslashes($value);
                $error = array();
                if (!$_POST['name']) {
                    $error[] = 'Введите название';
                }
                if (!isset($_POST['price'])) {
                    $error[] = 'Укажите стоимость';
                }
                if (count($error)) {
                    $this->view->errors = $error;
                } else {
                    $delivery->setName($_POST['name']);
                    $delivery->setPrice($_POST['price']);
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
            echo $this->render('delivery/delivery-type-edit.tpl.php');
        } else {
            $this->view->deliveries = wsActiveRecord::useStatic('DeliveryType')->findAll();
            echo $this->render('delivery/delivery-type.tpl.php');
        }
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

       // setcookie("chatName", str_replace(' ', '_', $this->translit(mb_substr($this->website->getCustomer()->getFirstName() . '_' . $this->website->getCustomer()->getMiddleName(), 0, 18))), 0, '/admin_files/views/slugebnoe/chat');
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
	if(isset($_GET['good_pay'])){
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
				LIMIT 30
		";
		$payments = wsActiveRecord::findByQueryArray($sql);
        $this->view->payments = $payments;
		echo $this->render('payment/success.tpl.php');
				}elseif(isset($_GET['no_pay'])){
					$sql = "
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
				LIMIT 30
		";
		$payments = wsActiveRecord::findByQueryArray($sql);
        $this->view->payments = $payments;
		echo $this->render('payment/no_success.tpl.php');
				}else{
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
				LIMIT 30
		";
		$payments = wsActiveRecord::findByQueryArray($sql);
        $this->view->payments = $payments;
		echo $this->render('payment/success.tpl.php');
		}
        
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
        $this->view->blog = wsActiveRecord::useStatic('Blog')->findAll();
        echo $this->render('blog/list.tpl.php');
    }
	
	public function blogeditAction()
    {

	if (isset($this->get->delete) and (int)$this->get->delete > 0) {
        $n = new Blog((int)$this->get->delete);
		if ($n->getId()) $n->destroy();
            $this->_redir('blog-post');
        }
		if (isset($this->get->edit)) {
            $block = new Blog((int)$this->get->edit);
            if (count($_POST)) {
                $errors = array();
				 if (!@$_POST['post_name'])
                    $errors[] = 'Пожалуйста заполните поле "Заголовок"';
                if(!@$_POST['preview_post'])
                $errors[] = 'Пожалуйста заполните поле "Вступительная часть"';
                if(!@$_POST['content_post'])
                                    $errors[] = 'Пожалуйста заполните поле "Содержимое"';

				if (!count($errors)) {
				
					$block->setPostName($_POST['post_name']);
					$block->setPostNameUk($_POST['post_name']);
					$block->setCtime($_POST['ctime']);
					$block->setUtime(date("y-m-d h:i:s"));
					if(isset($_POST['autor'])) { $block->setAutor($_POST['autor']); $block->setAutorUk($_POST['autor']);}
					//$block->setAutor($autor);
					$block->setPreviewPost($_POST['preview_post']);
					$block->setPreviewPostUk($_POST['preview_post']);
					$block->setContentPost($_POST['content_post']);
					$block->setContentPostUk($_POST['content_post']);
					$block->setContentPostUk($_POST['content_post']);
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
                } else {
                    $this->view->errors = $errors;
                }
    
            }
            $this->view->blogedit = $block;
            echo $this->render('blog/edit.tpl.php');

        }
		
		

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
	$np = new NovaPoshta('2c28a9c1a5878cb01c8f9c440e827a61', 'ru', true, 'curl');
if(isset($_GET['id']) and $_GET['id'] > 0){
$this->view->order = wsActiveRecord::useStatic('Shoporders')->findById((int)$this->get->id);
}
if($this->post->metod == 'new_registr'){
$res = array();
$ref = array();
$id = explode(',', $this->post->id);
foreach($id as $r){
$re = wsActiveRecord::useStatic('Shopordersmeestexpres')->findById((int)$r)->getRef();
if(@$re) $ref[] = $re;
}
$res = $np->newRegistr($ref);
//$res = $np->getRegistr('8b1e5571-c61f-11e7-becf-005056881c6b');
if(@$res['data'][0]['Ref']){
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
'Sender' => '8492900e-1b5f-11e6-971e-005056887b8d',
'SenderAddress' => '01ae25f4-e1c2-11e3-8c4a-0050568002cf',
'ContactSender' => '381d9a4b-1c98-11e6-971e-005056887b8d',
'SendersPhone' => '380971876821',
);
$recipient = array(
'CityRecipient' => $this->post->cityrecipient,
'Recipient' => 'ea1b5c6e-3875-11e6-a54a-005056801333',
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
'PayerType' => 'Recipient',
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
if(@$res['data'][0]['Ref']){
$order = new Shoporders((int)$this->post->clientbarcode);
if(@$order){
if(@$order->getMeestId()){
$or_np = new Shopordersmeestexpres((int)$order->getMeestId());
if(@$or_np){
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
if(@$cust){
if($cust->getUuidNp() == NULL){
$cust->setUuidNp($this->post->recipientname);
$cust->save();
}
}
$order->setNakladna($res['data'][0]['IntDocNumber']);
$order->save();
}
//$res['print'] = [$np->printDocument($res['data'][0]['Ref'])];
//$print = $np->printDocument($res['data'][0]['Ref']);
}
die(json_encode($res));
}elseif($this->post->metod == 'update_counterparties'){
$res = array();
$res = $np->updateContactPerson($this->post->ref, $this->post->lastname, $this->post->firstname, $this->post->middlename, $this->post->phone);
die(json_encode($res));
}elseif($this->post->metod == 'new_counterparties'){
$res = array();
$res = $np->newContactPerson($this->post->lastname, $this->post->firstname, $this->post->middlename, $this->post->phone);
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
	if(@$result['RecipientFullName'])$text.="Отримувач: ".$result['RecipientFullName']."<br>";
	if(@$result['PhoneRecipient']) $text.="Телефон отримувача: ".$result['PhoneRecipient']."</br>";
	$text.="Маршрут: ".$result['CitySender']." - ".$result['CityRecipient']."<br>";
	$text.="Адреса: ".$result['RecipientAddress']."<br>";
	if(@$result['RecipientDateTime']) {
	$text.="Статус: ".$result['Status']." ".$result['RecipientDateTime']."<br>";
	}else{
	$text.="Статус: ".$result['Status'].".  Очікувана дата доставки ".$result['ScheduledDeliveryDate']."<br>";
	}
	$text.="Вага відправлення: ".$result['DocumentWeight']." кг.<br>"; 
	$text.="Ціна доставки: ".$result['DocumentCost']." грн.<br>"; 
	if($result['Redelivery']==1) $text.="Зворотня доставка: ".$result['RedeliverySum']." грн.<br>";
	if(@$result['LastTransactionStatusGM']) $text.="Відправлений  грошевой переказ. Статус: ".$result['LastTransactionStatusGM']."</br>";
	if(@$result['UndeliveryReasonsSubtypeDescription']) $text.="Причина відмови: ".$result['UndeliveryReasonsSubtypeDescription']."</br>";
	if(@$result['LastCreatedOnTheBasisNumber'] and strlen($result['LastCreatedOnTheBasisNumber']) ==14){
	$result1 = $np->documentsTracking2($result['LastCreatedOnTheBasisNumber']);
	$text.="</br>Повернення: ";
	$text.=$result1['Number']."</br>";
	if(@$result1['RecipientFullName'])$text.="Отримувач: ".$result1['RecipientFullName']."<br>";
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
				  $wh = $np->getWarehouses1($_COOKIE["uid_city"], $this->get->term);
				$mas = array();
		$i = 0;
		foreach ($wh['data'] as $c) {
		$mas[$i]['label'] = $c['Description'];
		$mas[$i]['value'] = $c['Description'];
		$mas[$i]['id'] = $c['Ref']; 
		$mas[$i]['city'] = $_COOKIE["uid_city"];
		$mas[$i]['term'] = $this->get->term;
		$i++;
			}
			echo json_encode($mas);
				
				
	
				die();
				}elseif($this->get->what == 'counterparties'){
				$counterparties = $np->getCounterpartyContactPersons('ea1b5c6e-3875-11e6-a54a-005056801333', $this->get->term, $_COOKIE["uid_city"]);

		$mas = array();
		$i = 0;
		foreach ($counterparties['data'] as $c) {
		$mas[$i]['label'] = $c['Description'].', тел: '.$c['Phones'];
		$mas[$i]['value'] = $c['Description'];
		$mas[$i]['id'] = $c['Ref']; 
		$mas[$i]['phones'] = $c['Phones']; 
		$i++;
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
		$this->view->all_order = wsActiveRecord::useStatic('Shoporders')->findAll(array('status in(1,9,15,16)', 'delivery_type_id in(8,16)'));
		//------
		//$cities = $np->getCities();
		//$this->view->cities = $cities['data']; 
			echo $this->render('np/np.tpl.php');
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
	 if($from >$to) break;
	
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
	}else if($this->post->metod == 'goodelivery'){
	 	$from = strtotime($this->post->fromd);
		$to = strtotime($this->post->tod);
			 if($from >$to) break;
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
	 $path1file = INPATH . "admin_files/views/chart/". $filename;
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

$h = array(
0 => 'A',
1 => 'B',
 2 => 'C',
 3 => 'D',
 4 => 'E',
 5 => 'F',
 6 => 'G',
 7 => 'H',
 8 => 'I',
 9 => 'J',
 10 => 'K',
 11 => 'L',
 12 => 'M',
 13 => 'N',
 14 => 'O',
 15=>'P',
 16=>'Q',
 17=>'R',
 18=>'S',
 19=>'T',
 20=>'U',
 21=>'V',
 22=>'W', 
 23=>'X', 
 24=>'Y', 
 25=>'Z',
 26=>'AA',
 27=>'AB',
 28=>'AC',
 29=>'AD',
 30=>'AE',
 31=>'AF',
 32=>'AG',
 33=>'AH',
 34=>'AI',
 35=>'AJ',
 36=>'AK',
 37=>'AL',
 38=>'AM',
 39=>'AN',
 40=>'AO',
 41=>'AP',
 42=>'AQ',
 43=>'AR',
 44=>'AS',
 45=>'AT',
 46=>'AU',
 47=>'AV',
 48=>'AW',
 49=>'AX',
 50=>'AY',
 51=>'AZ',
 52=>'BA',
 53=>'BB',
 54=>'BC',
 55=>'BD',
 56=>'BE',
 57=>'BF',
 58=>'BG',
 59=>'BH',
 60=>'BI'
 );


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
$brand = wsActiveRecord::useStatic('BalanceCategory')->findByQuery("SELECT DISTINCT `id_brand` FROM  `ws_balance_category`");
//$brand = wsActiveRecord::useStatic('BalanceCategory')->findByQuery("SELECT DISTINCT `id_brand` FROM `ws_balance_category` where id_brand = ".$where);

foreach($brand as $k => $b){
$category = wsActiveRecord::useStatic('BalanceCategory')->findByQuery("SELECT DISTINCT `id_category` FROM `ws_balance_category` where id_brand =".$b->id_brand);
foreach($category as $k => $c){

$sql = "SELECT `count` as ctn  FROM  `ws_balance_category` where id_brand = ".$b->id_brand." and id_balance = ".$balance->id." and id_category=".$c->id_category;
$count = wsActiveRecord::useStatic('BalanceCategory')->findByQuery($sql)->at(0);
if($count){ $count = $count['ctn']; }else{	$count = 0;}
$mass[$b->getArticleBrand()->getName()][$c->getCategoryName()->getRoutez()][$balance->id][] = $count;

$sql="SELECT IF( SUM(  `count` ) IS NULL , 0, SUM(  `count` ) ) AS  `ctn` 
FROM  `ws_order_articles` 
INNER JOIN  `ws_orders` ON  `ws_order_articles`.`order_id` =  `ws_orders`.`id` 
INNER JOIN  `ws_articles` ON  `ws_order_articles`.`article_id` =  `ws_articles`.`id` 
WHERE  `ws_articles`.`brand_id` = ".$b->id_brand."
AND  `ws_orders`.`date_create` > DATE_FORMAT( '".$balance->date."' , '%Y-%m-%d 03:00:00' )
AND `ws_orders`.`date_create` <= DATE_FORMAT(DATE_ADD('" . $balance->date . "', INTERVAL +1 DAY) , '%Y-%m-%d 03:00:00' )
AND  `ws_articles`.`category_id` = ".$c->id_category;

$count_r = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($sql)->at(0)->getCtn();
$mass[$b->getArticleBrand()->getName()][$c->getCategoryName()->getRoutez()][$balance->id][] = $count_r;

}
}

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
	foreach ($mass as $k => $brand) {
	
	foreach($brand as $z => $category){
	if($start == 2){
	$sheet->setCellValue($h[0].$i, $k);
	$sheet->setCellValue($h[1].$i, $z);
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
	}
	



	
	//header("Content-Type: text/html; charset=utf-8");
		//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      //  header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
      //  header("Cache-Control: no-cache, must-revalidate");
      //  header("Pragma: no-cache");
		//header ( "Content-Disposition: attachment; filename=ostatki.xls");
		
		
		
		// Выводим содержимое файла
		$objWriter = new PHPExcel_Writer_Excel5($xls);
			//$objWriter->save('php://output');
			
			//$path1file = INPATH . "admin_files/views/trekko/". $filename;
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
die(json_encode(array('exit'=>"Отчёт сформирован!", 'src'=>'https://www.red.ua/admin_files/views/chart/'.$filename)));

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
	if($this->post->not != ''){$not.=$this->post->not;}
	$kost = wsActiveRecord::useStatic('Shoparticlelog')->findByQuery("SELECT *  FROM  `red_article_log` WHERE customer_id not in(".$not.")  and  type_id = 2 and  `ctime` >=  '".$from."' and `ctime` <= '".$to."'  and `code` IS NOT NULL ORDER BY  `red_article_log`.`id` ASC ");
	
	$date = array();
	$i = 0;
	foreach ($kost as $k) {
	$add = wsActiveRecord::useStatic('Shoparticlelog')->findByQuery("SELECT *  FROM  `red_article_log` WHERE customer_id != 2993 and  type_id in (3,6) and  `ctime` >  '".$from."' and `ctime` <= '".$to."'  and `code` LIKE  '".$k->getCode()."' ");;
	if($add[0]['count'] != $k->getCount()){
	$t = wsActiveRecord::useStatic('Shoparticles')->findById($k->getArticleId());
	$c =  wsActiveRecord::useStatic('Customer')->findById($k->getCustomerId());
	$date[$i]['tovar'] = $t['model']."(".$t['brand'].")"; 
	$date[$i]['model'] = $k->getInfo();
	$date[$i]['articul'] = $k->getCode();
	//$date[$i]['admin'] =$c['first_name'].' '.$c['middle_name'];
	$date[$i]['proces'] = $k->getComents();
	$date[$i]['dell'] = $k->getCount();
	$date[$i]['ctime'] = date('d-m H:i', strtotime($k->getCtime()));//date('Y-m-d 23:59:59', $to);
	$i++;
	}
	}

   die(json_encode(array('send'=>$date)));
	}
	echo $this->render('page/controldellarticles.tpl.php');
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
	$msg = $this->view->render('email/template_new.tpl.php');

//SendMail::getInstance()->sendEmail('php@red.ua', 'RED', 'TEST', $msg);
	echo $this->render('meestexpress/meestexpress.tpl.php');
	//echo $this->render('static.tpl.php', 'index.html');
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
	public function getSendEmail($id_customer, $id_admin, $subject = '',  $sum = 0, $order, $sms = false, $email = false, $flag = false){
	if(true){
	$sub = new Customer($id_customer);
	
	
	if ($sms == true) {
	$subject_sms = $subject .' : '. $flag.' '.$sum.' грн.';
                        $phone = Number::clearPhone($sub->getPhone1());
                        include_once('smsclub.class.php');
                        $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                        $sender = Config::findByCode('sms_alphaname')->getValue();
                        $user = $sms->sendSMS($sender, $phone, $subject_sms);
                        wsLog::add('SMS to user: ' . $sms->receiveSMS($user), 'SMS_' . $sms->receiveSMS($user));
                    }
					
	if ($email == true) {
	
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
		  Здравствуйте, '.$sub->middle_name.' '.$sub->first_name.'. Внесены изменения по Вашему депозиту: "'.$subject.'"
		</td>
		</tr>
		<tr>
		  <td>';
		  
		  if($dep > 0) $text .= 'В данный момент у Вас на депозите: '.$dep.' грн.</br>Воспользоватся своим депозитом Вы сможете при оформлении следующего заказа.</br>
		  Если Вы уже оформили заказ и хотите использовать свой депозит, свяжитесь с нашими менеджерами.';
		$text .= '  </td>
		  </tr>
		</table>';
	
	

                            $admin_email = Config::findByCode('admin_email')->getValue();
                            $admin_name = Config::findByCode('admin_name')->getValue();
                            $do_not_reply = Config::findByCode('do_not_reply_email')->getValue();

							if(isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){
							
							$this->view->content = $text;			
							$msg = $this->view->render('email/template.tpl.php');

                         //   $msg = 'Логин: ' . $sub->getUsername() . '. ' . $this->trans->get('Your new password for red.ua') . ': ' . $newPass;

                            require_once('nomadmail/nomad_mimemail.inc.php');
                            $mimemail = new nomad_mimemail();
                            $mimemail->debug_status = 'no';
                            $mimemail->set_from($do_not_reply, $admin_name);
                            $mimemail->set_to($sub->getEmail(), $sub->getFullname());
                            $mimemail->set_charset('UTF-8');
                            $mimemail->set_subject($subject_email);
                            $mimemail->set_text($msg);
                            $mimemail->set_html(nl2br($msg));

                            //@$mimemail->send();

                            MailerNew::getInstance()->sendToEmail($sub->getEmail(), $sub->getFullname(), $subject_email, $msg);
							
							wsLog::add('Email to user: ' . $sub->getEmail(), 'Email_' . $subject_email);
							
							}

                        }
						
						}
						return false;
	
	}
	public function trekkoAction(){
	require_once('Trekko/Trekko.php');
	$api = new Trekko('ogjlOLSGljlgojkfsd24dfsodflwGOTolnLejroi35lkjl2352ll8lj90KL', '134', 'order');
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
	}else if(@$this->get->prints){
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
	}else if(@$this->post->method == 'add_ttn'){

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
	
	}elseif(@$this->post->method == 'add_ttn_all'){
	$shipment =  explode(',', $this->post->id);
	$res = array();
	$mas_res_ok = array();
	$mas_res_off = array();
	$i=0;
	foreach($shipment as $or){
	$order = new Shoporders($or);
	$dt = date('d.m.Y', strtotime($order->getDeliveryDate()));
	$name = $order->getMiddleName().' '.$order->getName();
	$adress = 'г.'.$order->getCity().', ул.'.$order->getStreet().', д.'.$order->getHouse().', кв.'.$order->getFlat();
	if($order->getPaymentMethodId() == 4 or $order->getPaymentMethodId() == 6 or $order->getPaymentMethodId() == 8){
	$summ = 0;
	}else{
	$summ = $order->getAmount();
	}
	if($order->getDeposit() > 0){
	$strach = ($order->getAmount()+$order->getDeposit());
	}else{
	$strach = ceil($order->getAmount());
	}
	$res[] = array('product_order' => $order->getId(), 'contact' => $name, 'tel'=>$order->getTelephone(), 'city'=>'Киев', 'adress'=>$adress, 'time'=>$order->getDeliveryInterval(), 'delivery_date'=>$dt, 'naimenovanie' => 'Одежда',  'summa_oplaty'=>$summ, 'summa_strahovki'=>$strach, 'ves'=>'1', 'mest'=>'1', 'primechanie'=>' ', 'id_service' => '1','id_status' => '1');
	$i++;
	}
	$result = $api->getCreateMasOrder($res);
	$cur = '';
	if($result->success == 1){
	$mas_res_ok = $result->response;
	$cur = $api->getLoadingTrekko(1);
	foreach($result->response as $resp){
	$ord = new Shoporders((int)$resp->order_id);
	OrderHistory::newHistory($this->user->getId(), $ord->getId(), 'Смена статуса', OrderHistory::getStatusText($ord->getStatus(), 13));
	$ord->setNakladna((int)$resp->id);
	$ord->setStatus(13);
	$ord->setOrderGo(date('Y-m-d H:i:s'));
	$ord->save();
	
	//$order->setStatus(13);
	//$order->setNakladna($order->getId());
	
	}
	}else{
	//$cur = '';
	$mas_res_off[] = array('success'=>$result->success, 'code'=>$result->code, 'error'=>$result->error);
	}
	die(json_encode(array('ok'=>$mas_res_ok, 'off'=>$mas_res_off, 'cur'=>$cur->success)));
	}else if(@$this->get->edit){// открытие формы создания ттн
	$this->view->id = $this->get->edit;
	$id = $this->get->edit;
	$this->view->order_trekko_edit = wsActiveRecord::useStatic('Shoporders')->findById($id);
	echo $this->render('trekko/add_order.tpl.php');
	}else{
	$orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('status in (9,15,16)', 'delivery_type_id'=>9, 'delivery_date != 0000-00-00'));
	$this->view->order_trekko = $orders;
		echo $this->render('trekko/list-trekko.tpl.php');
		}
	}
	  
	public function search_articulAction(){
	$mass = array();
	if(@$this->post->articul){
	$add = wsActiveRecord::useStatic('Shoparticlelog')->findByQuery("SELECT sum(count) as addsum FROM  `red_article_log` WHERE type_id in(3,6) and `code` LIKE '".$this->post->articul."'")->at(0)->addsum;
$mass['add'] = $add;
	$del = wsActiveRecord::useStatic('Shoparticlelog')->findByQuery("SELECT sum(count) as del FROM  `red_article_log` WHERE type_id = 2 and `code` LIKE '".$this->post->articul."'")->at(0)->del;
$mass['del'] = $del;
	$order = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT  sum(ws_order_articles.count) as sumaorder FROM ws_order_articles
	WHERE ws_order_articles.artikul LIKE  '".$this->post->articul."'")->at(0)->sumaorder;
$mass['order'] = $order;
$return = wsActiveRecord::useStatic('ShoporderarticlesVozrat')->findByQuery("SELECT  sum(ws_order_articles_vozrat.count) as sumreturn FROM ws_order_articles_vozrat
	WHERE ws_order_articles_vozrat.cod LIKE  '".$this->post->articul."'")->at(0)->sumreturn;
$mass['return'] = $return;
$ostatok = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery("SELECT count, id_article FROM ws_articles_sizes WHERE code LIKE '".$this->post->articul."' ")->at(0);
$mass['ostatok'] = $ostatok->count;

$img = wsActiveRecord::useStatic('Shoparticles')->findById($ostatok->id_article);
$mass['img'] = $img->getImagePath('detail');
$mass['cat'] = $img->category->getRoutez();
$mass['id'] = $ostatok->id_article;
$mass['cup'] = $img->ArtycleBuyCount();
	print json_encode($mass);
            die();
	//$this->view->article = $mass;
	}else{
	echo $this->render('page/search_articul.tpl.php', 'index.php');
	//$this->view->article = $mass;
	}
	}
	
	public function revisiyaAction(){
	$ms = array();
	$errors = array();
	if(@$this->post->method == 'dell'){ 
		$q = "TRUNCATE TABLE ws_revisiya";
       	wsActiveRecord::query($q);
		die(json_encode('База очищена!'));
	}
	if($this->post->method == 'start'){
	$from = (int)$this->post->from;
	$limit = (int)$this->post->limit;
	$i=0;
	$j = 0;
	$z = 0;
	$s = 0;
	$r_o = 0;
	$sql = "SELECT ws_articles_sizes.* FROM ws_articles_sizes
join ws_articles on ws_articles_sizes.id_article = ws_articles.id
 	WHERE ws_articles.status != 1 and ws_articles_sizes.`ctime` !=  '0000-00-00 00:00:00'
AND  ws_articles_sizes.`ctime` >  '2016-01-01 00:00:00'
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
		if(@$reviz and $reviz->flag == 0){
		//die();
		$count = $siz->count;// ostatok
		
		$z = 0; //v rakazach
				
	$r = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery('
		SELECT SUM(`ws_order_articles`.`count`) as orc FROM `ws_order_articles`
		JOIN `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
		WHERE `ws_orders`.`status` = 100
		and `ws_order_articles`.`color` = '.$siz->id_color.' 
		and `ws_order_articles`.`size` = '.$siz->id_size.' 
		and `ws_order_articles`.`article_id` = '.$siz->id_article.'
		and  `ws_order_articles`.`artikul` LIKE "'.$siz->code.'" ')->at(0)->getOrc();
		
											
		if($r) $z = $r;// to chto nashli v zakazach
		
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
								if($aa < 0) $aa = 0;
								
								
							if(true){
							
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
							}
	if ($_POST and isset($_POST['save'])){
	 //$errors = $_FILES;
	if(isset($_FILES['exel'])){
	ini_set('memory_limit', '1024M');
     // set_time_limit(300);
                            if (is_uploaded_file($_FILES['exel']['tmp_name'])) {
                                $oldfilename_excel = $_FILES['exel']['tmp_name'];
                                $filename_excel = INPATH . "files/" . $_FILES['exel']['name'];
								
                                if (move_uploaded_file($oldfilename_excel, $filename_excel)) {
                                   $res = $this->parse_excel_file($filename_excel);
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
		
							
if(count($errors) > 0) $this->view->errors = $errors;	
echo $this->render('page/revisiya.tpl.php');
		}
							
public function parse_excel_file($file)
    {
	$i=0;
	 require_once('PHPExel/PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
		@unlink($file);
		 return $objPHPExcel->getActiveSheet()->toArray();
     // $aSheet = $objPHPExcel->getActiveSheet()->toArray();
	  
	/*  foreach ($aSheet as $s) {
	  if($s[0]){
	  $sr = new Revisiya();
	  $sr->setSr($s[0]);
	  $sr->setCount($s[1]);
	  $sr->save();
 $i++;
	  }
	  }*/
	   
	  return $i;
    }
	public function addttnorderAction(){
	if(@$this->post->ttn){
	require_once('np/NovaPoshta.php');
	require_once('np/NovaPoshtaApi2Areas.php');
	$np = new NovaPoshta('2c28a9c1a5878cb01c8f9c440e827a61','ru', true, 'curl' );
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
	if($this->user->getId() == 1 or $this->user->getId() == 8005) $data = array();
	
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
						
if(count($errors) > 0) $this->view->errors = $errors;	
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
	   @unlink($file);
	// die(print_r($mass));
	  return $i;
    }
	public function amazonordersAction(){
	if($this->post->method == 'dell'){
	$or_art = new Amazonorderarticles($this->post->id);
	$order = new Amazonorders($or_art->getOrderId());
	$or_art->destroy();
	$order->ReCalculate();
	die($this->post->id);
	}
		if($this->post->method == 'edit'){
	$or_art = new Amazonorderarticles($this->post->id);
	$or_art->setCount($this->post->count);
	$or_art->save();
	$order = new Amazonorders($or_art->getOrderId());
	$order->ReCalculate();
	die(json_encode(array('cnt'=>$or_art->getCount(), 'sum'=>$or_art->getCount()*$or_art->getPrice())));
	}
	if($this->post->method == 'ok'){
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
				$imagePath = INPATH . 'admin_files/views/amazon/logo-red-amazon.png';
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
				$path1file = INPATH . "admin_files/views/amazon/". $filename;
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
	
	if($this->cur_menu->getParameter() == 'ukrpost_transfer' and $this->get->id){
	$id = $this->get->id;
	$order = new Shoporders($id);
	if($order){
	
	$text = 'Доброго дня!<br>';
	$text.=$order->middle_name.' '.$order->name.', Вам відправлено поштовий переказ '.$this->get->summa.' грн. за повернення замовлення №'.$order->id.'<br>Місце отримання, поштове відділення Укр.Пошти: '.$this->get->index;
	$remark = new Shoporderremarks();
                        $data = array(
                            'order_id' => $order->getId(),
                            'date_create' => date("Y-m-d H:i:s"),
                            'remark' => 'Почтовый перевод '.$this->get->summa.' грн.',
							'name' => $this->user->getMiddleName()
                        );
                        $remark->import($data);
                        $remark->save();
						
	SendMail::getInstance()->sendEmail($order->email, $order->middle_name.' '.$order->name, 'Поштовий переказ за замовлення №'.$order->id, $text, '', '', 'return@red.ua', 'RED.UA', 2, 'return@red.ua', $this->user->getMiddleName());
	
}else{
$text = 'Помилка';
}
	die($text);
	}
	
	echo $this->render('template/views/page/ukrpost.tpl.php', 'index.php');
	
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
		}
		if($this->get->param){
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
		
	
	echo $this->render('template/views/articles/attributes.tpl.php', 'index.php');
	
	}
	
		public function sendMessageTelegram($chat_id, $message) {
  file_get_contents('https://api.telegram.org/bot'.Config::findByCode('telegram_key')->getValue().'/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
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
		$errors = array();
		 if($this->post->save){
		// if($this->post->save){
		//echo '<pre>';
		//print($this->post);
		//echo '</pre>';
		// die();
		// }
		
		 $article = new Shoparticles((int)$this->get->edit);
			
			$article->setCategoryId($this->post->category);
			$article->setSizeType($this->post->size_type);
			$article->setSezon($this->post->sezon);
			if($this->post->soot_rozmer){$article->setSootRozmer($this->post->soot_rozmer);}
			$article->setSezon($this->post->sezon);
		
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
                                $path_to_file = $_SERVER['DOCUMENT_ROOT'] . '/files/org/' . $filename_image;
                                Mimeg::generateAllsizes($path_to_file);
								$article->setImage($filename_image);
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
		$article->setSostavUk($this->trans->translateuk($this->post->sostav, 'ru', 'uk'));
	}else{
	$errors[] = 'Вы не ввели состав товара!';
	}
if(isset($this->post->long_text) and $this->post->long_text != ''){
		$article->setLongText($this->post->long_text);
		$article->setLongTextUk($this->trans->translateuk($this->post->long_text, 'ru', 'uk'));
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
	$article->save();
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
	
	$this->_redir('articles-add/listarticles/search/code/' . $article->getCode());
	}else{
	$this->view->errors = $errors;
	}
	}
				
				$this->view->sostav = wsActiveRecord::useStatic('Shoparticlessostav')->findByQueryArray("SELECT * FROM  `ws_articles_sostav`");
				$this->view->sex = wsActiveRecord::useStatic('Shoparticlessex')->findAll();
				$this->view->sezon = wsActiveRecord::useStatic('Shoparticlessezon')->findAll();
				$this->view->categories = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active = 1 or active = 2'));
				$this->view->article = wsActiveRecord::useStatic('Shoparticles')->findById((int)$this->get->edit);
				
				echo $this->render('template/views/articles/articles-edit.tpl.php', 'index.php');
		
		}elseif($this->get->loadexcel){//Загрузка excel файла
		//die($_FILES['excel_file']);
		if (isset($_FILES['excel_file'])) {
//die('tut');
                            if (is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
							
                                $tmp_name_excel = $_FILES['excel_file']['tmp_name'];
									$name = $_FILES['excel_file']['name'];
									$str = strpos($name, ".");
									$nakladnaya = substr($name, 0, $str);
									$nakladnaya = preg_replace('~[^0-9]+~','',$nakladnaya);
									
	
								if(isset($_POST['version'])){
								if($_POST['version'] == 1){//старая версия
								
								$ifos = $this->parseexcelfiletemp($tmp_name_excel);
									//echo '<pre>';
									//echo print_r($ifos);
									//echo '</pre>';
									//die();
                                    if (@$ifos) {
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

									$art->setModel($a['model']);
									$art->setModelUk($this->trans->translateuk($a['model'], 'ru', 'uk'));
                                    $art->setPrice($a['price']); 
									$art->setStock((int)$a['stock']);
									$art->setActive('n');
									$art->setCode($nakladnaya);
									$art->setMinPrice($a['cc']);
									$art->setMaxSkidka($a['skidka']);
									$art->setStatus(1);
									$art->save();
									
									foreach($a['sizes'] as $s){
									$size = new Shoparticlessize();
									$size->setIdArticle($art->getId());
									$size->setIdSize((int)$s['id_size']);
									$size->setIdColor((int)$s['id_color']);
									$size->setCount((int)$s['count']);
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
									echo $this->render('template/views/articles/articles-add.tpl.php', 'index.php');							
									}
									
									
                                    }else{
									die('Ошибка чтения excel файла!');
                                    }
								
								
								}elseif($_POST['version'] == 2){//новая версия
								$ifos = $this->parseexcelfile($tmp_name_excel);
									//echo '<pre>';
									//echo print_r($ifos);
									//echo '</pre>';
									//die();
                                    if (@$ifos) {
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
									 
									$art->setModelUk($a['model']);
									$art->setModel($this->trans->translateuk(mb_strtolower($a['model']), 'uk', 'ru'));
                                    $art->setPrice($a['price']); 
									$art->setStock((int)$a['stock']);
									$art->setSezon($a['id_season']);
									$art->setSizeType($a['id_sex']);
									$art->setActive('n');
									$art->setCode($nakladnaya);
									$art->setMinPrice($a['cc']);
									$art->setMaxSkidka($a['skidka']);
									$art->setStatus(1);
									$art->save();
									
									foreach($a['sizes'] as $s){
									$size = new Shoparticlessize();
									$size->setIdArticle($art->getId());
									$size->setIdSize((int)$s['id_size']);
									$size->setIdColor((int)$s['id_color']);
									$size->setCount((int)$s['count']);
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
									echo $this->render('template/views/articles/articles-add.tpl.php', 'index.php');							
									}
									
									
                                    }else{
									die('Ошибка чтения excel файла!');
                                    }
								}
									}else{
									$this->view->errors = array('Выберите тип накладной с которой хотите загрузить товар!');
									echo $this->render('template/views/articles/articles-add.tpl.php', 'index.php');			
									}
                            }else{
							die('Ошибка загрузки excel файла!');
							}
                        }else{
						die('not file');
						}
						
		}elseif($this->get->listarticles){
		$errors = array();
		$save = '';//array();
		$data = array();
		$data["active"] = 'n';
		if($this->post->status){
		$data['status'] = $this->post->status;
		}else{
		$data['status'] = 1;
		}
		if($this->post->code or $this->get->code){
		$code = $this->post->code?$this->post->code:$this->get->code;
		$data[] = " `code` LIKE  '".$code."' ";
		}

		$articles = wsActiveRecord::useStatic('Shoparticles')->findAll($data, array(), array(0,1000));

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
		$this->view->errors = $errors;
		}
		
		$this->view->status = wsActiveRecord::useStatic('Shoparticlesstatus')->findAll();
		echo $this->render('template/views/articles/articles-add.tpl.php', 'index.php');
		//$this->_redirect('/admin/articles-add/');
		}else{
        $this->view->articles = $articles;
		echo $this->render('template/views/articles/articles-list.tpl.php', 'index.php');
		}
		}else{
		$this->view->status = wsActiveRecord::useStatic('Shoparticlesstatus')->findAll();
		echo $this->render('template/views/articles/articles-add.tpl.php', 'index.php');
		}


}

public function parseexcelfile($file)
    {
        require_once('PHPExel/PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
        //$aSheet = $objPHPExcel->getActiveSheet();
		$aSheet = $objPHPExcel->getActiveSheet()->toArray();
		$mas = array();
		$errors = array();
		unset($aSheet[0]);
		unset($aSheet[1]);
		foreach($aSheet as $k => $m){
		if($m[0]){
		//$mas[$m[16]]['nakladnaya'] = $nakladna[2];
		$mas[$m[16]]['model'] = trim($m[1]);
		
		$mas[$m[16]]['brand'] = trim($m[2]);
		
		$mas[$m[16]]['stock'] = $mas[$m[16]]['stock'] + (int)$m[8];
				
			$sex = wsActiveRecord::useStatic('Shoparticlessex')->findFirst(array('id_1c'=>(int)$m[9]));
				if (!$sex) { $errors[] = 'Ошибка Пола "' . $m[10] . '", строка '.$m[0]; $sex = 0; }else{ $sex = $sex->id;}
				
		$mas[$m[16]]['id_sex'] = $sex;
		$mas[$m[16]]['sex'] = $m[10];
		
				$season = wsActiveRecord::useStatic('Shoparticlessezon')->findFirst(array('id_1c'=>(int)$m[11]));
				if (!$season) { $errors[] = 'Ошибка сезона "' . $m[12] . '", строка '.$m[0]; $season = 0; }else{ $season = $season->id;}
				
		$mas[$m[16]]['id_season'] = $season;
		$mas[$m[16]]['season'] = $m[12];
		
					
		
		$mas[$m[16]]['price'] = trim($m[13]);
		$mas[$m[16]]['cc'] = trim($m[14]);
		$mas[$m[16]]['skidka'] = trim($m[15]);
		
		
		//$size = wsActiveRecord::useStatic('Size')->findFirst(array('id_1c'=>(int)$m[6]));
		//	if (!$size) { $errors[] = 'Ошибка размера "' . $m[7] . '", строка '.$m[0]; $size_id = 0; $size_name = $m[7]; }else{ $size_id = $size->id; $size_name = $size->size;}
			
		$size = wsActiveRecord::useStatic('Size')->findFirst(array('size LIKE "'.trim($m[7]).'"'));
			if (!$size) { $errors[] = 'Ошибка размера "' . $m[7] . '", строка '.$m[0]; $size_id = 0; $size_name = $m[7]; }else{ $size_name = $size->size; $size_id = $size->id;}
			
		$color = wsActiveRecord::useStatic('Shoparticlescolor')->findFirst(array('id_1c' =>(int)$m[4]));
			if (!$color) { $errors[] = 'Ошибка с цветом "' . $m[5] . '", строка '.$m[0]; $color_id = 0; $color_name = $m[5]; }else{ $color_id = $color->id; $color_name = $color->name;}
			
		$art = wsActiveRecord::useStatic('Shoparticlessize')->count(array("code LIKE  '".trim($m[3])."' "));
			if ($art) { $errors[] = 'Товар с штрихкодом '.trim($m[3]).' уже существует. id: '.$art->id.' Строка в накладной: '.$m[0]; }
		
		$mas[$m[16]]['sizes'][] = array(
		'code'=>trim($m[3]),
		'id_color'=>$color_id,
		'color'=>$color_name,
		'id_size'=>$size_id,
		'size'=>$size_name,
		'count'=>(int)$m[8]
		);
		}else{
		break;
		}
		}
		$mas['error'] = $errors;
        return $mas;
    }
   public function parseexcelfiletemp($load_file){//старые накладные
   
         require_once('PHPExel/PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($load_file);
        $objPHPExcel->setActiveSheetIndex(0);
		$aSheet = $objPHPExcel->getActiveSheet()->toArray();
		$mas = array();
		$errors = array();
		unset($aSheet[0]);
		unset($aSheet[1]);
		unset($aSheet[2]);
		unset($aSheet[3]);
		unset($aSheet[4]);
		unset($aSheet[5]);
		unset($aSheet[6]);
		unset($aSheet[7]);
		//return $aSheet;
		foreach($aSheet as $k => $m){
		if($m[1]){
		$mas[$m[41]]['model'] = trim($m[3]);
		$mas[$m[41]]['brand'] = trim($m[17]);
		$mas[$m[41]]['stock'] = $mas[$m[41]]['stock'] + (int)$m[29];
		$mas[$m[41]]['price'] = trim($m[32]);
		$mas[$m[41]]['cc'] = trim($m[35]);
		$mas[$m[41]]['skidka'] = trim($m[38]);
		
		$size = wsActiveRecord::useStatic('Size')->findFirst(array("size LIKE '".trim($m[26])."' "));
			if (!$size) { $errors[] = 'Ошибка размера "' . $m[26] . '", строка '.$m[1]; $size = 0; }else{ $size = $size->id;}
		$color = wsActiveRecord::useStatic('Shoparticlescolor')->findFirst(array('name' => mb_strtolower($m[23])));
			if (!$color) { $errors[] = 'Ошибка с цветом "' . $m[23] . '", строка '.$m[1]; $color = 0; }else{ $color = $color->id;}
		$art = wsActiveRecord::useStatic('Shoparticlessize')->count(array("code LIKE  '".trim($m[20])."' "));
			if ($art) { $errors[] = 'Товар с штрихкодом '.trim($m[20]).' уже существует. id: '.$art->id.' Строка в накладной: '.$m[1]; }
			
		$mas[$m[41]]['sizes'][] = array(
		'code'=>trim($m[20]),
		'id_color'=>$color,
		'color'=>trim($m[23]),
		'id_size'=>$size,
		'size'=>trim($m[26]),
		'count'=>(int)$m[29]
		);
		}else{
		break;
		}
		}
		$mas['error'] = $errors;
		 //@unlink($file);
		 return $mas;
    }
	public function withdrawAction(){
	
			if (isset($_FILES['excel_file'])) {
//die('tut');
                            if (is_uploaded_file($_FILES['excel_file']['tmp_name'])) {
							
                                $tmp_name_excel = $_FILES['excel_file']['tmp_name'];
								 $res = $this->parse_excel_file($tmp_name_excel);
								 if($res){
								 $ma_rez = array();
								 $i=0;
								 foreach($res as $r){
								 $a = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array(" code LIKE'".trim($r[0])."' and count >".$r[1]));
								 if($a){ 
								 $temp = $a->count;
								 $a->setFlag($temp);
								 $a->setCount((int)$r[1]);
								 $a->save();
								$temp = ($temp - (int)$r[1]);
								$log = new Shoparticlelog();
											$log->setCustomerId($this->user->getId());
											$log->setUsername($this->user->getUsername());
											$log->setArticleId($a->id_article);
											$log->setComents('Снятие с продажи');
											$log->setInfo($s['size'].' '.$s['color']);
											$log->setTypeId(2);
											$log->setCount($temp);
											$log->setCode($r[0]); 
											$log->save();
											
								 $ma_rez[$i]['category'] = $a->article_rod->category->getRoutezGolovna();
								 $ma_rez[$i]['rozdel'] = $a->article_rod->category->name;
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
                
				//$pExcel->getDefaultStyle()->getFont()->setSize(8);				
				//$pExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('На снятие');
				
				//$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
				//$aSheet->getPageSetup()->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                $aSheet->getColumnDimension('A')->setWidth(20);
				$aSheet->getColumnDimension('B')->setWidth(30);
                $aSheet->getColumnDimension('C')->setWidth(15);
                $aSheet->getColumnDimension('D')->setWidth(10);

				
				//$aSheet->getRowDimension(1)->setRowHeight(-1);
		
				$aSheet->setCellValue('A1', 'Категория');
                $aSheet->setCellValue('B1', 'Товар');
                $aSheet->setCellValue('C1', 'Артикул');
                $aSheet->setCellValue('D1', 'КоллСнять');
                $i = 2;
                foreach ($ma_rez as $t) { 

                        $aSheet->setCellValue('A'.$i, $t['category']);					//	№ п/п
                        $aSheet->setCellValue('B'.$i, $t['rozdel']);			//	Номер заказа
                        $aSheet->setCellValue('C'.$i, $t['code']);			//	Контактное лицо
                        $aSheet->setCellValue('D'.$i, $t['snatye']);	//	Телефон
                        $i++;
                }
				
				
                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

				$pathfile = INPATH . "admin_files/excel/". $filename;
				
				if (file_exists($pathfile)){
						if (unlink($pathfile)) $objWriter->save($pathfile);
						}else{
						$objWriter->save($pathfile);
						}
						
						$email = 'php@red.ua';
						$name = 'Ярослав';
                        $admin_name = 'Ярослав';
						$subject = 'Снятие товара';
						$msg = 'Файл на снятие во вложении';
						
       MailerNew::getInstance()->sendToEmail($email, $admin_name, $subject, $msg, 0, '', $admin_name, $smtp = 1, $usubscribe_text = 0, $subsciber = 0, $uploadfile = $pathfile, $filename = $filename);
						
								 
//SendMail::getInstance()->sendEmail($email, $name, $subject, $msg, $uploadfile = $pathfile, $filename = $filename, '', '', 0, 'yaroslav_148@icloud.com', 'Ярослав');

								 }
								// echo '<pre>';
								// echo print_r($ma_rez);
								//// echo '</pre>';
							//	 die();
								 }
									}else{
									die('Ошибка чтения excel файла!');
									}
									
									}
									
			//$this->view->status = wsActiveRecord::useStatic('Shoparticlesstatus')->findAll();
			echo $this->render('template/views/page/withdraw.tpl.php', 'index.php');
	}
	
	public function emailAction(){
	
	
	//if(isset($this->post->send)){
	//echo print_r($this->post);
	//die();
	//}
	
	if(isset($this->post->send)){
	$email = 'php@red.ua';
	$name = 'Ярослав';
    $admin_name = 'Ярослав';
	$subject = 'Снятие товара';
		if(@$this->post->subject) $subject = $this->post->subject;
		
	$msg = 'Файл на снятие во вложении';
		if(@$this->post->message) $msg = $this->post->message;
	
	$uploadfile = '';
	$filename = '';
	if (isset($_FILES['file'])) {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
			$uploadfile = $_FILES['file']['tmp_name'];
				$filename = $_FILES['file']['name'];				
							}
							}
	
     //  MailerNew::getInstance()->sendToEmail($email, $admin_name, $subject, $msg, 0, '', $admin_name, $smtp = 1, $usubscribe_text = 0, $subsciber = 0, $uploadfile, $filename);

	
	SendMail::getInstance()->sendEmail($email, $name, $subject, $msg, $uploadfile, $filename, '', '', 0, 'yaroslav_148@icloud.com', 'Ярослав');
	}//else{
	//$res = SendMail::getInstance()->getMailList();
	//echo $res;
	//die();
	//}
	echo $this->render('template/views/page/email_page.tpl.php', 'index.php');
	
	}




}