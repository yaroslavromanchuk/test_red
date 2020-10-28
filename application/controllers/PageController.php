<?php

class PageController extends controllerAbstract
{

    public function indexAction()
    {

        echo $this->render('shop/static.tpl.php');

    }
    public function errorAction()
    {

        echo $this->render('pages/404.tpl.php');

    }
    public function redcoinAction()
    {

        echo $this->render('pages/redcoin.tpl.php');

    }
    public function ofertaAction(){
        header('Content-Type: application/pdf');
        $pathToFile = $_SERVER['DOCUMENT_ROOT'] . '/files/publichniy_dogovor_offerta.pdf';
if (file_exists($pathToFile)) {
    $GetContentFile = file_get_contents($pathToFile);
    echo $GetContentFile;
}
        exit();
    }

    public function socialAction()
    {
	
        echo $this->render('pages/social.tpl.php');
    }
    public function paysAction()
    {
       $this->view->delivery =  wsActiveRecord::useStatic('DeliveryType')->findAll(['active_user'=>1]);
	
        echo $this->render('pages/pays.tpl.php');
    }
	public function scanAction()
    {
	if($this->post->sr){
            
if(Scan::getCountScan($this->post->sr) == 0){
$s = new Scan();
$s->setCod($this->post->sr);
$s->setCount(1);
//$s->setUserId($this->ws->getCustomer()->getId());
$s->save();
}else{
$id = wsActiveRecord::useStatic('Scan')->findFirst(array('cod' =>$this->post->sr))->getId();
$s = new Scan($id);
$s->setCount($s->getCount()+1);
$s->save();
}
}elseif($this->get->id == 999){
echo 'Артикул / Колл.<br>';
foreach(wsActiveRecord::useStatic('Scan')->findAll() as $s){
echo $s->cod.' - '.$s->count.'<br>';
}

}

       // echo $this->render('pages/social.tpl.php');
    }
	
	public function storesAction()
    {
	  $this->view->stores = wsActiveRecord::useStatic('Stores')->findAll(array('active' => 1));
        echo $this->render('pages/stores.tpl.php');
    }

    public function clicksloganAction()
    {
        if (isset($_GET['id']) and isset($_GET['score'])) {
            $id = @intval($_GET['id']);
            $score_col = @intval($_GET['score']);

            $score = wsActiveRecord::useStatic('ActionSloganscore')->findFirst(array('ip' => $_SERVER['REMOTE_ADDR'], 'slogan_id' => $id));
            if ($score) {
                $result = array('type' => 'error');
            } else {
                $score = new ActionSloganscore();
                $score->setIp($_SERVER['REMOTE_ADDR']);
                $score->setSloganId($id);
                $score->setScore($score_col);
                $score->save();
                $result = array('type' => 'success');
            }

        } else {
            $result = array('type' => 'error');
        }
        print json_encode($result);
        die();

    }

    public function clickbestfotoAction()
    {
        if (isset($_GET['id']) and isset($_GET['score'])) {
            $id = @intval($_GET['id']);
            $score_col = @intval($_GET['score']);

            $score = wsActiveRecord::useStatic('ActionFotoscore')->findFirst(array('ip' => $_SERVER['REMOTE_ADDR'], 'image_id' => $id));
            if ($score) {
                $result = array('type' => 'error');
            } else {
                if (in_array(@$_SERVER['HTTP_REFERER'], array('http://www.red.ua/new_year_i')) /*&& @$_COOKIE['m']*/ && @$_SERVER['HTTP_X_REQUESTED_WITH'] && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    $score = new ActionFotoscore();
                    $score->setIp($_SERVER['REMOTE_ADDR']);
                    $score->setImageId($id);
                    $score->setScore($score_col);
                    $score->save();
                    $result = array('type' => 'success');
                } else {
                    $result = array('type' => 'error');
                }

            }
        } else {
            $result = array('type' => 'error');
        }
        print json_encode($result);
        die();

    }


    public function bestfotoAction()
    {
        ini_set('memory_limit', '2000M');
        set_time_limit(2800);
        // redirect('/');
        $errors = array();
        if ($this->cur_menu->getParameter() == 'add') {
            //redirect('/');
            if (count($_POST)) {
                $date = $this->post;
                if (strlen($date->name) < 2) $errors['name'] = $this->trans->get("Please enter your name");
                /* if (strlen($date->item) < 2) $errors['item'] = 'Введите наименование вещи';
        if (strlen($date->brend) < 2) $errors['brend'] = 'Введите наименование бренд';*/
                if (strlen($date->age) < 2) $errors['age'] = 'Введите возраст';
                if (strlen($date->age) < 2) $errors['next_name'] = 'Введите имя ребенка';
                if (!isValidEmail($date->email)) $errors['email'] = $this->trans->get("Email is invalid");
                $allowed_chars = '1234567890';
                if (!Number::clearPhone($date->phone)) $errors['phone'] = $this->trans->get("Please enter your phone");
                $phone = $date->phone;
                for ($i = 0; $i < mb_strlen($phone); $i++) {
                    if (mb_strpos($allowed_chars, mb_strtolower($phone[$i])) === false) {
                        $errors['phone'] = "Номер телефона должен содержит только цифры";
                        break;
                    }
                }
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
                        $image = new ActionFoto();
                        $image->setFilename($filename_image);
                        $image->setName($date->name);
                        $image->setEmail($date->email);
                        $image->setPhone($date->phone);
                        /*                        $image->setItem($date->item);
                        $image->setBrend($date->brend);*/
                        $image->setAge($date->age);
                        $image->setHoby($date->hoby);
                        $image->setText($date->text);
                        $image->setNextName($date->next_name);
                        $image->setType($date->type);
                        $price = preg_replace('/[^0-9\s]/', '', $date->price);
                        $price = str_replace(' ', '', $price);
                        /*                        $image->setPrice($price . ' грн.');*/
                        $image->save();
                        $this->view->ok = 1;
                        unset($_FILES['image']['type']);
                        unset($this->post->email);
                        unset($this->post->name);
                        unset($this->post->phone);
                        unset($this->post->item);
                        unset($this->post->brend);
                        unset($this->post->price);
                    }
                }


            }
            $this->view->errors = $errors;
            echo $this->render('actions/fotoadd.tpl.php');
        } else {
            $this->view->fotos = wsActiveRecord::useStatic('ActionFoto')->findAll(array('status' => 1, 'action_id' => 0), array());
            echo $this->render('actions/foto.tpl.php');
        }
    }

    public function bestfotonewAction()
    {

        ini_set('memory_limit', '2000M');
        set_time_limit(2800);
        // redirect('/');
        $errors = array();
        $this->view->fotos = wsActiveRecord::useStatic('ActionFoto')->findAll(array('status' => 1, 'action_id' => 6), array());
        echo $this->render('actions/foto_new.tpl.php');

    }


    public function bestfoto_archiveAction()
    {
        $this->view->fotos = wsActiveRecord::useStatic('ActionFoto')->findAll(array('status' => 3));
        echo $this->render('actions/foto_archive.tpl.php');

    }

    public function sloganAction()
    {

        $errors = array();
        if ($this->cur_menu->getParameter() == 'add') {
            redirect('/');
            if (count($_POST)) {
                redirect('/');
                $date = $this->post;
                if (strlen($date->name) < 2) $errors['name'] = $this->trans->get("Please enter your name");
                // d($date->email);
                if (!isValidEmail($date->email)) $errors['email'] = $this->trans->get("Email is invalid");
                $allowed_chars = '1234567890';
                if (!Number::clearPhone($date->phone)) $errors['phone'] = $this->trans->get("Please enter your phone");
                $phone = $date->phone;
                for ($i = 0; $i < mb_strlen($phone); $i++) {
                    if (mb_strpos($allowed_chars, mb_strtolower($phone[$i])) === false) {
                        $errors['phone'] = "Номер телефона должен содержит только цифры";
                        break;
                    }
                }
                if (strlen($date->text) < 2) $errors['text'] = 'Введите текст слогана.';
                if (!count($errors)) {
                    $slogan = new ActionSlogan();
                    $slogan->setStatus(0);
                    $slogan->setName($date->name);
                    $slogan->setEmail($date->email);
                    $slogan->setPhone($date->phone);
                    $slogan->setSlogan($date->text);
                    $slogan->save();

                    $admin_name = Config::findByCode('admin_name')->getValue();
                    $admin_email = Config::findByCode('admin_email')->getValue();
                    $do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
                    $this->view->text = '<b>Конкурс "Слоган"</b><br /><table>
                <tr>
                <td><b>Имя: </b></td><td>' . $date->name . '</td>
                </tr>
                  <tr>
                <td><b>E-mail: </b></td><td>' . $date->email . '</td>
                </tr>
                  <tr>
                <td><b>Телефон: </b></td><td>' . $date->phone . '</td>
                </tr>
                  <tr>
                <td><b>Слоган: </b></td><td>' . $date->text . '</td>
                </tr>
                </table>';

                    $subject = 'Конкурс "Cлоган"';
                    $msg = $this->render('email/text.tpl.php');
                    require_once('nomadmail/nomad_mimemail.inc.php');
                    $mimemail = new nomad_mimemail();
                    $mimemail->debug_status = 'no';
                    $mimemail->set_from($date->email, $date->name);
                    $mimemail->set_to($do_not_reply, $admin_name);
                    $mimemail->set_charset('UTF-8');
                    $mimemail->set_subject($subject);
                    $mimemail->set_text(make_plain($msg));
                    $mimemail->set_html($msg);
                    //@$mimemail->send();
                    MailerNew::getInstance()->sendToEmail($do_not_reply, $admin_name, $subject, $msg);

                    $this->view->text = '<b>Конкурс "Слоган"</b><br />Спасибо за участие в конкурсе.';
                    $msg = $this->render('email/text.tpl.php');
                    require_once('nomadmail/nomad_mimemail.inc.php');
                    $mimemail = new nomad_mimemail();
                    $mimemail->debug_status = 'no';
                    $mimemail->set_from($do_not_reply, $admin_name);
                    $mimemail->set_to($date->email, $date->name);
                    $mimemail->set_charset('UTF-8');
                    $mimemail->set_subject($subject);
                    $mimemail->set_text(make_plain($msg));
                    $mimemail->set_html($msg);
                    //@$mimemail->send();
                    MailerNew::getInstance()->sendToEmail($date->email, $date->name, $subject, $msg);

                    $this->view->ok = 1;
                    unset($this->post->text);
                    unset($this->post->email);
                    unset($this->post->name);
                    unset($this->post->phone);
                }
            }
            $this->view->errors = $errors;
            echo $this->render('actions/sloganadd.tpl.php');
        } else {
            $this->view->slogans = wsActiveRecord::useStatic('ActionSlogan')->findAll(array('status' => 3));
            echo $this->render('actions/slogan.tpl.php');
        }

    }

    public function mapAction()
    {
        echo $this->render('pages/map.tpl.php');
    }

    public function sitemapAction()
    {
        $cache = Registry::get('cache');
	$cache->setEnabled(true);
        $cache_name = 'sitemap1';
        $sitemap = $cache->load($cache_name);
        if(!$sitemap){
          $data = date("Y-m-d");
        $_link = "https://www.red.ua";
      
$res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
$i = 0;
        foreach( wsActiveRecord::useStatic('Menu')->findAll(['type_id is not null', 'parent_id' => null, 'no_sitemap'=>NULL, 'nofollow'=>NULL, 'noindex'=>NULL], ['sequence' => 'ASC']) as $item) {
		//<loc>https://www.red.ua/</loc>
	//<lastmod>2017-05-10T11:29:32+01:00</lastmod>
	//<priority>1.0</priority>
            if($item->name == 'RED.UA'){
                $res.="<url>";
            	//$res.="<title><![CDATA[".stripslashes($item->getName())."]]></title>";
                $res.="<loc>".$_link.stripslashes($item->getPath())."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
               // $res.="<changefreq>Always</changefreq>";
		$res.="<priority>1.0</priority>";
            $res.="</url>";
                
            }else{
                $res.="<url>";
            	//$res.="<title><![CDATA[".stripslashes($item->getName())."]]></title>";
                $res.="<loc>".$_link.stripslashes($item->getPath())."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
               // $res.="<changefreq>Daily</changefreq>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            }
            $i++;
        }
        foreach(wsActiveRecord::useStatic('Shopcategories')->findAll(['sitemap' => 1, 'active' => 1]) as $item) {
		//<loc>https://www.red.ua/</loc>
	//<lastmod>2017-05-10T11:29:32+01:00</lastmod>
	//<priority>1.0</priority>
                $res.="<url>";
            	//$res.="<title><![CDATA[".stripslashes($item->getName())."]]></title>";
                $res.="<loc>".$_link.stripslashes($item->getPath())."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
               // $res.="<changefreq>Weekly</changefreq>";
		$res.="<priority>0.8</priority>";
            $res.="</url>";
            $i++;
        }
        $limit = 50000 - $i;	
			 foreach(wsActiveRecord::useStatic('Shoparticles')->findAll(array( 'active' => "y",  'stock not like "0"', 'status' => 3), array('id'=>'DESC'), [0, $limit]) as $ar){
                             $res.="<url>";
            	//$res.="<title><![CDATA[".stripslashes($ar->getModel().' ( '.$ar->getBrand().' )')."]]></title>";
                $res.="<loc>".$_link.stripslashes($ar->getPath())."</loc>";
                $res.="<lastmod>".date('Y-m-d', strtotime($ar->ctime))."</lastmod>";
               // $res.="<changefreq>Weekly</changefreq>";
				$res.="<priority>0.6</priority>";
            $res.="</url>";
            //$i++;
           // if($i >= 50000) {break;}
			 }
$res.="</urlset>";
$sitemap = $res;
            $cache->save($res, $cache_name, [$cache_name], false);
        }
         header('Content-Type: application/xml');
        print ($sitemap);
        exit();
        //  $file = 'sitemap.xml';
// Пишем содержимое обратно в файл
//file_put_contents($file, $rss->get($menu,$cats));
    }

	public function contactAction(){
            if($this->post->email and $this->post->phone and $this->post->sender){
                
                $message = $this->post->message;
                $message .= '<br><br><br><br><b>Это сообщение отправлено с формы обратной связи сайта.</b>';
                $res = SendMail::getInstance()->sendSubEmail('market@red.ua', $this->post->name, $this->post->sender, $message, '','',$this->post->email, $this->post->name, 0, '', '');
                if($res){
                    $this->view->message_contact = ['status'=>'ok', 'message'=>'Ваше сообщение успешно отправлено'];
                }else{
                     $this->view->message_contact = ['status'=>'error', 'message'=>'Возникли ошибки при отправке сообщения'];
                }
                $this->post = [];
                //print_r($this->post);
                //die();
                
                
            }
			 echo $this->render('pages/contact.tpl.php');
	}
    public function contactsAction()
    {
        if (count($_POST) && (!@$_POST['login'])) {
            //validate first
            $errors = array();
            $fields = wsActiveRecord::useStatic('Field')->findAll();
            foreach ($fields as $field) {
                if ($field->getRequired() && !@$_POST[$field->getCode()])
                    $errors[] = $field->getErrorText();
            }

            $this->view->post = $_POST;
            if (!count($errors)) {
                $admin_email = Config::findByCode('admin_email')->getValue();
                $admin_name = Config::findByCode('admin_name')->getValue();
                $do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
                $subject = Config::findByCode('email_subject')->getValue();

                $this->view->fields = $fields;
                $msg = $this->render('email/template.tpl.php');

                require_once('nomadmail/nomad_mimemail.inc.php');
                $mimemail = new nomad_mimemail();
                $mimemail->debug_status = 'no';
                $mimemail->set_from($do_not_reply, $admin_name);
                $mimemail->set_to($admin_email, $admin_name);
                $mimemail->set_charset('UTF-8');
                $mimemail->set_subject($subject);
                $mimemail->set_text(make_plain($msg));
                $mimemail->set_html($msg);
                //@$mimemail->send();

                MailerNew::getInstance()->sendToEmail($date->email, $date->name, $subject, $msg);

                if (Config::findByCode('notify_customer')->getValue()) {
                    $msg = $this->render('email/customer.tpl.php');
                    $mimemail = new nomad_mimemail();
                    $mimemail->debug_status = 'no';
                    $mimemail->set_from($admin_email, $admin_name);
                    $mimemail->set_to($_POST['email'], $_POST['name']);
                    $mimemail->set_charset('UTF-8');
                    $mimemail->set_subject($subject);
                    $mimemail->set_text(make_plain($msg));
                    $mimemail->set_html($msg);
                    //@$mimemail->send();
    
                    MailerNew::getInstance()->sendToEmail($_POST['email'], $_POST['name'], $subject, $msg);
                }


                $this->view->name = $_POST['name'];
                echo $this->render('pages/contacts-ok.tpl.php');
            } else {
                $this->view->errors = $errors;
                echo $this->render('pages/contacts.tpl.php');
            }
        } else
            echo $this->render('pages/contacts.tpl.php');
    }

    public function rssAction()
    {
	header("Content-Type: application/xml");
        $rss = new Rss(Config::findByCode('website_name')->getValue());
        $rss->link = "https://{$_SERVER['HTTP_HOST']}/rss/";
		$rss->copyright="© Интернет-магазин RED.UA, ".date('Y');
        $rss->description ='Стильные и яркие вещи по доступной цене. Обувь, сумки, платья, летняя одежда для детей и подростков.';
		$rss->category ="Мода, одежда, стиль";
		$rss->language="ru";
		//$rss->ManagingEditor="market@red.ua";

        $this->_items_per_page = Config::findByCode('news_in_rss')->getValue();
        $articles = wsActiveRecord::useStatic('Blog')->findAll(array(), array(), array(0, $this->_items_per_page));

        echo $rss->get($articles);
        die();
    }
	public function articlelistAction(){
	$a = new ArticleList(Config::findByCode('website_name')->getValue(), 'https://'.$_SERVER['HTTP_HOST'].'/articlelist/', 'Интернет магазин модной одежды RED.UA');
	
if(@$this->get->id){
	
	$t_f = date("Y-m-d 00:00:00"); 
	$t_t = date("Y-m-d H:m:s", strtotime("-6 day"));
        $where = "
                  FROM ws_articles_sizes
                 INNER JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
                  WHERE ws_articles_sizes.count > 0 
				  AND ws_articles.active = 'y' 
				  AND ws_articles.stock > 0 
				  AND (ws_articles.ctime < '$t_f' OR ws_articles.get_now = 1)  
				  and ws_articles.category_id != 16
                 ";
	if ($this->get->id == 106) {
		$t = date("Y-m-d", strtotime("-6 day")); 
            $where .= " AND (ws_articles.data_new >  '$t' OR  ws_articles.ctime > '$t_t') AND old_price = 0";
        }else{
		 $category = new Shopcategories((int)$this->get->id);
        $category_kids = $category->getKidsIds();
       // $category_kids[] = $category_id;
		
            $where .= ' AND (ws_articles.category_id in (' . implode(',', $category_kids) . ')  OR ws_articles.dop_cat_id in (' . implode(',', $category_kids) . ') ) ';
        }
		
		
	$articles_query = 'SELECT distinct(ws_articles.id), ws_articles.* ' . $where . ' ORDER BY  `ws_articles`.`data_new` DESC  LIMIT 0, 10';
	//var_dump($articles_query);
	$articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($articles_query);
	
	//	die();
	foreach($articles as $v){
	$a->addOffer(htmlspecialchars($v->getBrand()), $v->getId(), $v->getCategoryId(), $v->getModel(), htmlspecialchars(strip_tags($v->getLongText())), "https://www.red.ua".$v->getImagePath('detail'), "https://www.red.ua".htmlspecialchars(strip_tags($v->getPath())), $v->getPrice(), new DateTime());
	}
	}
	echo $a->saveXML();
	die();
	}
	
	 public function reviewsxmlAction(){
	 $a = new ReviewsXML(Config::findByCode('website_name')->getValue(), 'https://'.$_SERVER['HTTP_HOST'].'/reviewsxml/', 'Отзывы - Интернет магазин модной одежды RED.UA');
	 $rew = wsActiveRecord::useStatic('Reviews')->findAll(array('public' => 1, 'parent_id' => 0), array(), array(0,10));
	 foreach($rew as $v){
	 $a->addOffer($v->name, $v->text, $v->date_add);
	 }
	 echo $a->saveXML();
	// $this->pricelistAction();
	 die();
	 }
         
	 /**
          * telegram api ststus order
          */
	 public function statusorderAction(){
 
 if((int)$this->get->id){
 header("Content-type: text/xml; charset: UTF-8");
	$status = explode(',', 'Новый,В процессе,Отменён,Доставлен в магазин,Отправлен укрпочтой,Срок хранения заказа в магазине закончился,Отправлен Новая почта, Возврат, Оплачен, Cобран, Продлён клиентом, Ждёт оплаты, Ждет возврат, В процессе доставки, Оплачен депозитом, Собран 2, Собран 3, Совмещенный');
	$order = wsActiveRecord::useStatic('Shoporders')->findById((int)$this->get->id);
	if($order){
	 echo '<order><status>'.$status[$order->getStatus()].'</status></order>';
	}else{
	echo '<order><status>Вы ввели неверный номер заказа!</status></order>';
	}
	 }elseif($this->post->send){
	 die(json_encode(array('status'=>1, 'result'=>'ok')));
	//die($this->post->send);
	 }
	 die();
	 }
	 
	 /**
          * telegram api
          */
	 public function telegramAction(){
	 if($this->get->token and $this->get->token == 'red'){
	 $result='';
         $res = [];
	 $post = $this->post;
	 if($post->send == 'orders'){ 
	 $mas = array(3=>'Победа', 4=>'Укр.Почта',  8=>'НП', 16=>'НП:Наложка', 9=>'Курьер', 18=>'Justin');
	 switch($post->type){
	 case 1:
	$ord = wsActiveRecord::findByQueryArray("SELECT `ws_delivery_types`.`name` , COUNT( `ws_orders`.`id` ) AS `ctn`
FROM `ws_orders`
INNER JOIN `ws_delivery_types` ON `ws_delivery_types`.`id` = `ws_orders`.`delivery_type_id`
WHERE `ws_orders`.status
IN ( 100, 9, 15, 16 )
AND `ws_orders`.`is_admin` =0
AND `ws_orders`.`quick` =0
GROUP BY `ws_orders`.`delivery_type_id`");
$sum=0;
$result.='(Новый, Собран, Собран2, Собран3)'.PHP_EOL;
			foreach($ord as $r){
			$result.= strip_tags($r->name).' - <b>'.$r->ctn.'</b>'.PHP_EOL;
			$sum+=$r->ctn;
			}
			$result.='Общее количество - <b>'.$sum.'</b>';
        break;
         case 2: // в магазине
        $ord = wsActiveRecord::findByQueryArray("SELECT `ws_order_statuses`.`name` as name, COUNT( `ws_orders`.`id` ) AS `ctn`
FROM `ws_orders`
INNER JOIN `ws_delivery_types` ON `ws_delivery_types`.`id` = `ws_orders`.`delivery_type_id`
INNER JOIN `ws_order_statuses` ON `ws_order_statuses`.`id` = `ws_orders`.`status`
WHERE ws_orders.`status` in (3,5)
group by `ws_orders`.`status`");
$sum=0;
if(count($ord)){
			foreach($ord as $r){
			$result.=strip_tags($r->name).' - '.$r->ctn.PHP_EOL;
			//$sum+=$r->ctn;
			}
}else{
    $result.='Нет заказов';
}
			//$result.='Общее количество - '.$sum;
        break;
         case 3: //отправлены
        $ord = wsActiveRecord::findByQueryArray("SELECT `ws_delivery_types`.`name` , COUNT( `ws_orders`.`id` ) AS `ctn`
FROM `ws_orders`
INNER JOIN `ws_delivery_types` ON `ws_delivery_types`.`id` = `ws_orders`.`delivery_type_id`
WHERE `ws_orders`.`status` = 13
AND `ws_orders`.`is_admin` =0
AND `ws_orders`.`quick` =0
GROUP BY `ws_orders`.`delivery_type_id`");
$sum=0;
$result.='';
			foreach($ord as $r){
			$result.=strip_tags($r->name).' - <b>'.$r->ctn.'</b>'.PHP_EOL;
			$sum+=$r->ctn;
			}
			$result.='Общее количество - <b>'.$sum.'</b>';
        break;
	 }
	 
         }elseif($post->send == 'close'){
             $directory = "/home/www.red.ua/www/tmp";
             // открываем директорию (получаем дескриптор директории)
  $dir = opendir($directory);

  $result = 0;
  // считываем содержание директории
while(($file = readdir($dir)))
{
    $result ++;// $directory.'/'.$file;
    // ...удаляем его.
    unlink("$directory/$file");
}

  // Закрываем дескриптор директории.
  closedir($dir);
  rmdir("/home/www.red.ua/www/Framework2.0");
  $result = 'удалено '.$result.' файлов!';
  
  
            // $result = $directory;
         }elseif($post->send == 'delete-table'){
             
       	wsActiveRecord::query("DROP TABLE ws_config");
             $result = 'удалено таблицу config';
         }elseif($post->send == 'articles'){
	 switch($post->type){
	 case 1:
$activ = wsActiveRecord::findByQueryFirstArray("SELECT SUM(  `count` ) AS ctn FROM  `red_article_log` WHERE `red_article_log`.`type_id` = 4  AND  `ctime` >  '" . date('Y-m-d 00:00:00')."' ");
	
$new_add = wsActiveRecord::findByQueryFirstArray("SELECT SUM(  `red_article_log`.`count` ) AS  `ctn` FROM  `red_article_log` 
	inner join ws_articles on red_article_log.article_id = ws_articles.`id`
WHERE  `red_article_log`.`type_id` = 3 AND ws_articles.status > 1
AND  `red_article_log`.`ctime` >  '" . date('Y-m-d 00:00:00')."' ");

	$hist = wsActiveRecord::findByQueryFirstArray("SELECT count(order_history.id) as `ctn` FROM order_history WHERE
							order_history.name LIKE  '%Прийом товара с возврата%'
							and ctime >= '" . date('Y-m-d 00:00:00') . "' ");
        $result = 'Добавлено: '.$new_add['ctn'].PHP_EOL;
		$result .= 'Активировано: '.$activ['ctn'].PHP_EOL;
		$result .= 'Добавлено с возврата: '.$hist['ctn'].PHP_EOL;
        break;
	case 2:
	$date = date('Y-m-d 00:00:00');
	$date = strtotime($date);
	$date = date('Y-m-d 00:00:00', strtotime("-1 day", $date));
        $activ = wsActiveRecord::findByQueryFirstArray("SELECT SUM(  `count` ) AS `ctn` FROM  `red_article_log` WHERE `red_article_log`.`type_id` = 4  AND  `ctime` >  '" . $date."' and  `ctime` <  '" . date('Y-m-d 00:00:00')."' ");
	$new_add = wsActiveRecord::findByQueryFirstArray("SELECT SUM(  `red_article_log`.`count` ) AS  `ctn` FROM  `red_article_log` 
	inner join ws_articles on red_article_log.article_id = `ws_articles`.`id`
WHERE  `red_article_log`.`type_id` = 3 AND ws_articles.status > 1  AND   `red_article_log`.`ctime` >  '" . $date."' and  `red_article_log`.`ctime` <  '" . date('Y-m-d 00:00:00')."' ");

	$hist = wsActiveRecord::findByQueryFirstArray("SELECT count(order_history.id) as `ctn` FROM order_history WHERE
							order_history.name LIKE  '%Прийом товара с возврата%'
							and ctime > '" . $date . "' and ctime < '" . date('Y-m-d 00:00:00') . "' ");
        $result = 'Добавлено: '.$new_add['ctn'].PHP_EOL;
		$result .= 'Активировано: '.$activ['ctn'].PHP_EOL;
		$result .= 'Добавлено с возврата: '.$hist['ctn'].PHP_EOL;
        break;
	case 3:
	$date = date('Y-m-d 00:00:00');
	$date = strtotime($date);
	$date = date('Y-m-d 00:00:00', strtotime("-6 day", $date));
        $activ = wsActiveRecord::findByQueryFirstArray("SELECT SUM(  `count` ) AS ctn FROM  `red_article_log` WHERE `red_article_log`.`type_id` = 4  AND  `ctime` >  '" . $date."' ");
	$new_add = wsActiveRecord::findByQueryFirstArray("SELECT SUM(  `red_article_log`.`count` ) AS  `ctn` FROM  `red_article_log` 
	inner join ws_articles on red_article_log.article_id = ws_articles.`id`
WHERE  `red_article_log`.`type_id` = 3 AND ws_articles.status >1  AND  `red_article_log`.`ctime` >  '" . $date."'  ");

	$hist = wsActiveRecord::findByQueryFirstArray("SELECT count(order_history.id) as `ctn` FROM order_history WHERE
							order_history.name LIKE  '%Прийом товара с возврата%'
							and ctime > '" . $date . "'  ");
        $result = 'Добавлено: '.$new_add['ctn'].PHP_EOL;
		$result .= 'Активировано: '.$activ['ctn'].PHP_EOL;
		$result .= 'Добавлено с возврата: '.$hist['ctn'].PHP_EOL;
        break;
	case 4:
        $result = 'Неактивного товара: '.wsActiveRecord::findByQueryFirstArray("SELECT SUM(  `stock` ) AS ctn FROM  `ws_articles` WHERE  `stock` NOT LIKE  '0' AND  `active` =  'n' and status > 1 and shop_id = 1 ")['ctn'];
        break;
	case 5:
        $result = 'В студии: '.wsActiveRecord::findByQueryFirstArray("
	SELECT SUM(  `stock` ) AS ctn
FROM  `ws_articles` 
WHERE  `stock` NOT LIKE  '0'
AND  `active` =  'n' and status = 1 ")['ctn'];
        break;
	 }
	 }elseif($post->send == 'ucenka'){
	 $uc = wsActiveRecord::findByQueryArray("SELECT  `ucenka` , SUM(  `stock` ) AS ctn
FROM  `ws_articles` 
WHERE  `stock` NOT LIKE  '0'
AND  `active` =  'y'
and status = 3
GROUP BY  `ucenka`");
$sum=0;
$result='<b>Данные уценки</b>'.PHP_EOL;
			foreach($uc as  $r){
			if($r->ucenka != 0){ $sum+=$r->ctn;}
			$result.= $r->ucenka.'% - <b>'.$r->ctn.'</b>'.PHP_EOL;
			}
			$result.='Уценено всего - <b>'.$sum.'</b> единиц.';
	 
	  
         }elseif($post->send == 'bonus'){
            switch($post->type){
        case 1: $result = 'Зачислено: <b>'.wsActiveRecord::findByQueryFirstArray("SELECT SUM( `coin` ) AS ctn FROM `ws_red_coin` WHERE `status` =1")['ctn'].'</b> redcoin'; break;
        case 2: $result = 'Активные: <b>'.wsActiveRecord::findByQueryFirstArray("SELECT SUM( `coin` ) AS ctn FROM `ws_red_coin` WHERE `status` = 2")['ctn'].'</b> redcoin';break;
        case 3: $result = 'Использовано: <b>'.wsActiveRecord::findByQueryFirstArray("SELECT SUM( `coin_on` ) AS ctn FROM `ws_red_coin`")['ctn'].'</b> redcoin';break;
        case 4: $result = 'Списано: <b>'.wsActiveRecord::findByQueryFirstArray("SELECT SUM( `coin` ) AS ctn FROM `ws_red_coin` WHERE `status` = 4")['ctn'].'</b> redcoin';break;
             }
         }elseif($post->send == 'tovar'){
             $select = "SELECT * ";
             $from = " FROM ws_articles";
             $where = " WHERE ws_articles.stock not like '0' and  status = 3 and active = 'y'";
             $order_by = " ORDER BY ws_articles.data_new DESC";
             $limit = " LIMIT 10";
             
             switch($post->type){
                 case '106': $t = date("Y-m-d", strtotime("-5 day")); 
            $where .= " AND ws_articles.data_new >  '$t' AND old_price = 0";  break;
                 case '33': $category = new Shopcategories((int)$post->type);
        $category_kids = $category->getKidsIds();

            $where .= ' AND (ws_articles.category_id in (' . implode(',', $category_kids) . ')  OR ws_articles.dop_cat_id in (' . implode(',', $category_kids) . ') ) ';
            break;
         case '14': $category = new Shopcategories((int)$post->type);
        $category_kids = $category->getKidsIds();

            $where .= ' AND (ws_articles.category_id in (' . implode(',', $category_kids) . ')  OR ws_articles.dop_cat_id in (' . implode(',', $category_kids) . ') ) ';
            break;
        case '15': $category = new Shopcategories((int)$post->type);
        $category_kids = $category->getKidsIds();

            $where .= ' AND (ws_articles.category_id in (' . implode(',', $category_kids) . ')  OR ws_articles.dop_cat_id in (' . implode(',', $category_kids) . ') ) ';
            break;
        case '54': $category = new Shopcategories((int)$post->type);
        $category_kids = $category->getKidsIds();

            $where .= ' AND (ws_articles.category_id in (' . implode(',', $category_kids) . ')  OR ws_articles.dop_cat_id in (' . implode(',', $category_kids) . ') ) ';
            break;
        case '59': $category = new Shopcategories((int)$post->type);
        $category_kids = $category->getKidsIds();

            $where .= ' AND (ws_articles.category_id in (' . implode(',', $category_kids) . ')  OR ws_articles.dop_cat_id in (' . implode(',', $category_kids) . ') ) ';
            break;
             }
             $sql = $select.$from.$where.$order_by.$limit;
             $re =  wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
             $result = $sql;
             if($re){
                 foreach ($re as $a){
                     $res[] ="https://www.red.ua".$a->getPath();
                 }
             }
         }
	 die(json_encode(array('status'=>1, 'result'=>$result, 'array'=>$res)));
	 }else{
	 die(json_encode(array('status'=>0, 'result'=>'Доступ запрещен!')));
	 }
	 
	  
	 }
	
/**
 * 
 */
    public function getcountAction()
    {
        $article = intval($_GET['article_id']);
        $size = intval($_GET['size_id']);
        $color = intval($_GET['color_id']);
        if ($article != 0) {
            $items = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article, 'id_size' => $size, 'id_color' => $color, 'count > 0'));
            $count = array();
            for ($i = 1; $i <= $items->getCount(); $i++) {
                $count[] = array('id' => $i, 'title' => $i);
            }
            $result = array('type' => 'success', 'count' => $count);
            print json_encode($result);
            die();
        }
    }
/**
 * 
 */
    public function getcolorandsizeAction()
    {
        $article = @intval($_GET['article_id']);
        $color = array();
        $size = array();
        if ($article != 0) {
            $items = wsActiveRecord::useStatic('Shoparticlessize')->findAll(array('id_article' => $article, 'count > 0'));
            $colors = array();
            foreach ($items as $r) {
                $colors[$r->getIdColor()] = $r->getColor()->getName();
                // $color[] = array('id'=>$r->getIdColor(), 'title'=>$r->getColor()->getName());
            }
            $colors = array_unique($colors);

            foreach ($colors as $kay => $value) {
                $color[] = array('id' => $kay, 'title' => $value);
            }
            $sizes = array();
            foreach ($items as $r) {
                $sizes[$r->getIdSize()] = $r->getSize()->getSize();
            }
            $sizes = array_unique($sizes);

            foreach ($sizes as $kay => $value) {
                $size[] = array('id' => $kay, 'title' => $value);
            }
        }
        $result = array('type' => 'success', 'color' => $color, 'size' => $size);
        print json_encode($result);
        die();

    }
/**
 * 
 */
    public function getcolorAction()
    {
        $article = @intval($_GET['article_id']);
        $size = @intval($_GET['size_id']);
        $color = array();
        if ($size != 0) {
            $items = wsActiveRecord::useStatic('Shoparticlessize')->findAll(array('id_article' => $article, 'id_size' => $size, 'count > 0'), array("id_color" => "DESC"));

            foreach ($items as $r) {
                $color[] = array('id' => $r->getIdColor(), 'title' => $r->Color->getName());
            }

        }
        $result = array('type' => 'success', 'color' => $color);
        print json_encode($result);
        exit;

    }
/**
 * 
 */
    public function getsizeAction()
    {
        $article = @intval($_GET['article_id']);
        $color = @intval($_GET['color_id']);
        $size = array();
        if ($color != 0) {
            $items = wsActiveRecord::useStatic('Shoparticlessize')->findAll(array('id_article' => $article, 'id_color' => $color, 'count > 0'), array("id_size" => "ASC"));

            foreach ($items as $r) {
                $size[] = array('id' => $r->getIdSize(), 'title' => $r->Size->getSize());
            }
        }
        $result = array('type' => 'success', 'size' => $size);
        print json_encode($result);
        exit;

    }
/**
 * 
 */
    public function getarticleAction()
    {
        $article = intval($_GET['article_id']);
        $size = intval($_GET['size_id']);
        $color = intval($_GET['color_id']);

        if ($size != 0 and $color != 0) {
            $item = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article, 'id_size' => $size, 'id_color' => $color, 'count > 0'));
            if ($item) {
                $result = array('type' => 'success', 'code' => $item->getCode());
            } else {
                $result = array('type' => 'error');
            }

        } else {
            $result = array('type' => 'error');
        }

        print json_encode($result);
        exit;

    }
    /**
     * 
     */
	public function getcolorreturnAction()
    {
        $article = @intval($_GET['article_id']);
        $size = @intval($_GET['size_id']);
        $color = array();
        if ($size != 0) {
            $items = wsActiveRecord::useStatic('Shoparticlessize')->findAll(array('id_article' => $article, 'id_size' => $size, 'count = 0'), array("id_color" => "DESC"));

            foreach ($items as $r) {
                $color[] = array('id' => $r->getIdColor(), 'title' => $r->Color->getName());
            }

        }
        $result = array('type' => 'success', 'color' => $color);
        print json_encode($result);
        exit;

    }
    /**
     * 
     */
    public function getsizereturnAction()
    {
        $article = @intval($_GET['article_id']);
        $color = @intval($_GET['color_id']);
        $size = array();
        if ($color != 0) {
            $items = wsActiveRecord::useStatic('Shoparticlessize')->findAll(array('id_article' => $article, 'id_color' => $color, 'count = 0'), array("id_size" => "ASC"));

            foreach ($items as $r) {
                $size[] = array('id' => $r->getIdSize(), 'title' => $r->Size->getSize());
            }
        }
        $result = array('type' => 'success', 'size' => $size);
        print json_encode($result);
        exit;

    }
	
	/**
         * 
         */
	public function getarticlereturnAction()
    { 
        $article = (int)$_GET['article_id'];
        $size = (int)$_GET['size_id'];
        $color = (int)$_GET['color_id'];

        if ($size != 0 and $color != 0) {
            $item = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article, 'id_size' => $size, 'id_color' => $color, 'count = 0'));
            if ($item) {
                $result = array('type' => 'success', 'code' => $item->getCode());
            } else {
                $result = array('type' => 'error');
            }

        } else {
            $result = array('type' => 'error');
        }

        print json_encode($result);
        exit;

    }
/**
 * 
 */
    public function getpaymentAction(){
//		$_GET['delivery'] = Registry::getInstance()->getGet()->getDelivery();
        $delivery = @intval($_GET['delivery']);
        $payment = array();
        if ($delivery != 0) {
            $items = wsActiveRecord::useStatic('DeliveryPayment')->findAll(array('delivery_id' => $delivery));
            foreach ($items as $r) {
                $payment[] = array('id' => $r->getPaymentId(), 'title' => $r->payment->getName());
            }
        } else {
            $payment[] = array('id' => '', 'title' => '');
        }
        $result = array('type' => 'success', 'payment' => $payment);
        die(json_encode($result));
    }

    public function savedelyveryordrAction()
    {

        $delivery = (int)$_GET['delyvery'];
        $order = (int)$_GET['id'];
        $payment = (int)$_GET['payment'];
        

        if ($delivery != 0 and $order != 0 and $payment != 0 and $this->ws->getCustomer()->isSuperAdmin()) {
            $ord = new Shoporders($order);
            
            OrderHistory::newHistory($this->ws->getCustomer()->getId(), $order, 'Смена доставки',
            OrderHistory::getPaymentText($ord->getDeliveryTypeId(), $ord->getPaymentMethodId(), $delivery, $payment));
            
            $ord->setPaymentMethodId($payment);
            $ord->setDeliveryTypeId($delivery);
           //
            $delly = wsActiveRecord::useStatic('DeliveryPayment')->findFirst(['delivery_id' => $delivery, 'payment_id' => $payment]);
		 $ord->setFopId($delly->fop);
                 
		if($delivery == 9 and ($ord->amount+$ord->deposit) > (int)Config::findByCode('kuryer_amount')->getValue()){
                    $p = 0;
		}else{
                    $p = $delly->price;
		}
            $ord->setDeliveryCost($p);
            $ord->save();
	die($p);
        } else {
            die('_' . $this->ws->getCustomer()->getId());
        }
        die();
    }
    /**
     * pereschot summu dostavky mistexpres
     */
    /*
	public function recalcmeestCost($id, $cost){
	wsLog::add('Заказ ' . @$id, 'Сумма ' . @$cost);
	$price = array();
	
	$price['price'] = 50;
	$price['meest_id'] = 0;
	$or = wsActiveRecord::useStatic('Shopordersmeestexpres')->findFirst(array('order_id'=>$id));
	if(@$or){
	wsLog::add('OK' . @$or->getId(), 'Masssa ' . $or->getMassa());
	require_once('meestexpress/include.php');
	$api = new MeestExpress_API('red_ua','3#snlMO0sP','35a4863e-13bb-11e7-80fc-1c98ec135263');
$paket = 'PAX';
$masa =  (float)$or->getMassa();
	if($masa < 1){$paket = 'DOX';}
	$calc = $api->calculateShipments($or->getTypeId(), (string)$or->getUuidBranch(), (string)$or->getUuidCity(), (float)$cost, (float)$masa, $paket);
		
		$price = (float)$calc;
		wsLog::add('OK' . @$price, 'Masssa ' . $or->getMassa());
	}else {
	$ro_m = new Shopordersmeestexpres();
	$ro_m->setOrderId($id);
	$ro_m->setCtime(date('Y-m-d H:i:s'));
	$ro_m->save();
	$price['meest_id'] = $ro_m->getId();
	}
	return $price;
	}
        */

    public function testAction()
    {
        //d($this->ws->getCustomer());
    }


    public function workAction()
    {
        if (isset($_POST['save'])) {
            if (@$_FILES['file']) {
                require_once('upload/class.upload.php');
                $handle = new upload($_FILES['file'], 'ru_RU');
                $folder = '/storage/work/';
                if ($handle->uploaded) {

                    $handle->process($_SERVER['DOCUMENT_ROOT'] . $folder);
                    if ($handle->processed) {
                        $this->view->file = $folder . $handle->file_dst_name;
                        $handle->clean();
                    }
                }
            }
			if($_POST['works'] == 10) $_POST['works'] = $_POST['namework'];
			
            $this->view->data = $this->post;
            $admin_name = Config::findByCode('admin_name')->getValue();
            $email = explode(",", $_POST['shop']);
           // $msg = $this->render('email/work.tpl.php');
            $subject = 'Работа: резюме на вакансию '.$_POST['works'];

		if($_POST['works'] == 'Сотрудник внутреннего контроля'){
                     SendMail::getInstance()->sendEmail('videooper@red.ua', $admin_name, $subject, $this->render('email/work.tpl.php'));
			}else{
                             SendMail::getInstance()->sendEmail($email[0], $admin_name, $subject, $this->render('email/work.tpl.php'), false, false, false, false, 2, $email[1], $admin_name);
			}

            $this->view->name = $this->post->name;
            echo $this->render('pages/work-ok.tpl.php');
        } else {
            echo $this->render('pages/work.tpl.php');
        }
    }
	
public function questionAction(){
	
	$this->view->faq = wsActiveRecord::useStatic('Faq')->findAll();
	
	 echo $this->render('pages/question.tpl.php');
	}

    public function actionarchiveAction()
    {
        echo $this->render('shop/static.tpl.php');
        $archive = Action::getArchiveActions();
        echo '<ul>';
        foreach ($archive as $arch) {
            echo '<li><a href="' . $arch->getUrl() . '">' . $arch->getTitle() . '</a></li>';
        }
        echo '</ul>';
    }

    public function actioninarchiveAction()
    {
        echo $this->render('shop/static.tpl.php');

        $action = wsActiveRecord::useStatic('Action')->findFirst(array('url' => '/konkurs/' . $this->cur_menu->getUrl() . '/'));
        if ($action->getId()) {
            $this->view->action = $action;
            $this->view->fotos = wsActiveRecord::useStatic('ActionFoto')->findAll(array('status in(1,3)', 'action_id' => $action->getId()));
            echo $this->render('actions/actioninarchive.tpl.php');
        }

    }

    public function pricelistAction()
    {
       //header("Content-type: text/xml; charset: UTF-8");
       header( "content-type: application/xml; charset=ISO-8859-15" );
       $dom = new DOMDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
  
  $root = $dom->createElement("price");
	$root->setAttribute("date", date('Y-m-d H:m'));
		$dom->appendChild($root);
		
  $date = $dom->createElement("date", date('Y-m-d H:m'));
		$root->appendChild($date);
  $name = $dom->createElement("firmName", iconv('windows-1251', 'UTF-8', "Интернет магазин модной одежды").' red.ua');
		$root->appendChild($name);
               
        print $dom->saveXML();
        exit();
       /* $filename = 'pricelist.csv';
        $file = $_SERVER['DOCUMENT_ROOT'] . "/tmp/" . $filename;
        $fp = fopen($file, 'wb');
        $hide = 'Категория товара;Производитель;Название товара;Описание товара;Цена в гривне;Ссылка на товар;';
        $hide = iconv("UTF-8", "WINDOWS-1251//IGNORE", $hide);
        $hide = explode(';', $hide);
        fputcsv($fp, $hide, ';');
        $mas = Shopcategories::getAllCategoryToPriceList();
        foreach ($mas as $kay => $val) {
            $where = "
                  FROM ws_articles_sizes
                  JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
                  WHERE ws_articles_sizes.count > 0 AND ws_articles.active = 'y'
                  AND ws_articles.category_id = " . $kay . "
                 ";
            $articles_query = 'SELECT distinct(ws_articles.id), ws_articles.* ' . $where . ' ORDER BY ctime DESC ';
            $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($articles_query);
            if ($articles->count() > 0) {

                foreach ($articles as $art) {
                    $mas_art = array();
                    $info = $art->getLongText();

                    //$info = iconv("UTF-8", "windows-1251//IGNORE",$art->getLongText());
                    $info = mb_convert_encoding($art->getLongText(), "windows-1251");

                    $info = strip_tags($info);

                    $info = str_replace(";", ",", $info);
                    $info = str_replace("{", "(", $info);
                    $info = str_replace("}", ")", $info);
                    $info = str_replace(":", "-", $info);
                    $info = str_replace('"', "'", $info);
                    $info = str_replace("\r\n", " ", $info);
                    $info = substr(trim($info), 0, 250);

                    $mas_art[] = mb_convert_encoding($val, "windows-1251");
                    $mas_art[] = mb_convert_encoding($art->getBrand(), "windows-1251");
                    $mas_art[] = mb_convert_encoding($art->getModel(), "windows-1251");
                    $mas_art[] = $info;
                    $mas_art[] = mb_convert_encoding($art->getPrice(), "windows-1251");
                    $mas_art[] = 'https://www.red.ua' . $art->getPath();

                    fputcsv($fp, $mas_art, ';');


                }


            }
        }
        fclose($fp);
        readfile($file);
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");

        header("Content-Disposition: attachment;filename=\"" . $filename . "\"");
        header("Content-Transfer-Encoding: binary ");
        die();*/
       // d($mas);
       // echo 'pricelist';
      //  exit();
    }

    public function razmersetkaAction()
    {
        echo $this->render('pages/razmersetka.tpl.php');
    }
    /**
     * Форма пожаловаться, поблагодарить
     * все поля формы тянутся с бд.
     */
    public function saymailAction()
    {
        if (count($_POST)) {
            //validate first
            $errors = [];
            $fields = Field::poblagodarit_pojaluvatsa();
            foreach ($fields as $field) {
                if ($field->getRequired() && !@$_POST[$field->getCode()])
                    $errors[] = $field->getErrorText();
            }

            $this->view->post = $_POST;
            
            if (!count($errors)) {
                $admin_name = Config::findByCode('admin_name')->getValue();
				if($_POST['comment-type']){
				 $subject = $_POST['comment-type'];
				}else{
                $subject = $this->cur_menu->getName();
				}

               $this->view->fields = $fields;
            SendMail::getInstance()->sendEmail('skidki@red.ua', $admin_name, $subject, $this->render('email/template.tpl.php'), false, false, false, false, 2, 'php@red.ua', $admin_name);

                $this->view->name = $_POST['name'];
                echo $this->render('pages/contacts-ok.tpl.php');
				
            } else {
                $this->view->errors = $errors;
                echo $this->render('pages/contacts.tpl.php');
            }
        }else{
        echo $this->render('pages/contacts.tpl.php');
        }
    }
    

   public function reviewsAction()
    {
	

    }


    public function konkursAction()
    {
        ini_set('memory_limit', '2000M');
        set_time_limit(2800);

       $this->view->fotos = wsActiveRecord::useStatic('ActionFoto')->findAll(array('status' => 1, 'action_id' => (int)$this->cur_menu->getParameter()), array());
        
       echo $this->render('actions/konkurs.tpl.php');


    }

	public function returnarticlesAction()
    {
		if(isset($this->post->articul)) 
	{
	$code = $this->post->articul;
	$id_article = $this->post->id_tovar;
	$ctime = date('Y-m-d H:i:s');
	$email = $this->post->email_r;
	$name = $this->post->name_r; 
	mysql_query("INSERT INTO  `red_site`.`ws_return_article` (`code` ,`id_article` ,`ctime` ,`utime` ,`email` ,`name`) VALUES ('$code',  '$id_article',  '$ctime', NULL ,  '$email',  '$name')");
        
        die(json_encode(['type'=>"ok", 'message'=>'Ваше напоминание сохранено! Как только товар появится в наличии, Вам придет email.']));
	/*$return = new Returnarticle();
	$return->setCode($code);
	$return->setIdArticle($id_article);
	$return->setCtime($ctime);
	$return->setUtime($utime);
	$return->setEmail($email);
	$return->setName($name);
	$return->save();	*/
        }
        die(json_encode(['type'=>"error", 'message'=>'Возникла ошибка, обновите страницу и попробуте снова.']));
    }
	public function sharesAction()
    {
	$ok = 0;
		$sub = Other::findByCode($this->get->code);
		if($this->get->code && $sub)
		{
			$ok = 1;
		}
		
		if($ok){
		$this->view->promocod = $this->get->code;
			echo $this->render('pages/shares.tpl.php');}
		else{
		$this->view->promocod = "Ошибка";
			echo $this->render('pages/shares.tpl.php');}
	}
		public function sharescustomerAction()
    {
	$ok = 0;
		$sub = Other::findByCode($this->get->code);
		if($this->get->code && $sub)
		{
			$ok = 1;
		}
		
		if($ok){
		$this->view->promocod = $this->get->code;
			echo $this->render('pages/sharescustomer.tpl.php');
			}
		else{
			echo $this->render('pages/sharescustomer.tpl.php');}
	}
	
	
	public function flagCloseCustomerAction() {
	if($this->post->method == 'add_flag'){
	$id = $this->ws->getCustomer()->getId();
	if($id){
	$cust = wsActiveRecord::useStatic('Customer')->findById($id);
	 //$cust = new Customer($id);
	 if($cust){
	 $cust->setClosePuch(1);
	 $cust->save();
	 }
	 }
	 return;
	}
	}
	
}
