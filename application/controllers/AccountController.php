<?php
    /**
     *  Readshop abstract
     */
    include_once 'controllerAbstract.php';


    class AccountController extends controllerAbstract
    {

        /**
         *  index action
         * @return null void
         */
        public function indexAction()
        {
            if (!$this->ws->getCustomer()->getIsLoggedIn()) {
                $this->_redirect('/account/login/');
                return;
            }
			
            $this->view->user = $this->ws->getCustomer();
			$id = $this->ws->getCustomer()->getId();
			
			//для вывода в личный кабинет инфи по акциям(была нова-укр почта)
			/*
			$this->view->action = wsActiveRecord::useStatic('Shoporders')->findByQuery("
			SELECT SUM(  `amount`+`deposit` ) AS summ,  `customer_id`,`date_create` ,  `delivery_type_id` 
FROM  `ws_orders` 
WHERE  `status` 
IN ( 14, 6, 8 )
AND ws_orders.customer_id = ".$id."
AND  `delivery_type_id` 
IN ( 4, 16, 8 ) 
AND  `date_create` >  '2016-08-17 00:00:00'
AND  `date_create` <  '2016-09-18 00:00:00'
GROUP BY  `customer_id` 
ORDER BY  `summ` DESC");
*/
            echo $this->render('account/index.tpl.php');
            return;
        }

        public function callmyAction()
        {
            $msg = array();
            if (strlen(trim(@$_POST['name'])) > 3) {
			if(strlen(trim(@$_POST['phone'])) > 9){
			if(strlen(trim(@$_POST['message'])) > 0){

                    $phone = Number::formatPhoneSMS($_POST['phone']);
                    $name = htmlspecialchars(trim($_POST['name']));
					$message = htmlspecialchars(trim($_POST['message']));
                    $bd = new CallMy();
                    $bd->setName($name);
                    $bd->setPhone($phone);
                    $bd->save();
                    $admin_name = Config::findByCode('admin_name')->getValue();
                    $admin_email = Config::findByCode('admin_email')->getValue();
                    $do_not_reply = Config::findByCode('subscribe_email')->getValue();
                    $msg = "Имя: " . $name . "</br>Телефон: " . $phone;
					$msg .="</br>По вопросу: ".$message;
                    $subject = 'Обратный звонок';
                    require_once('nomadmail/nomad_mimemail.inc.php');
                    $mimemail = new nomad_mimemail();
                    $mimemail->debug_status = 'no';
                    $mimemail->set_from($do_not_reply, $name);
                    $mimemail->set_to($do_not_reply, $admin_name);
                    $mimemail->set_charset('UTF-8');
                    $mimemail->set_subject($subject);
                    $mimemail->set_text(make_plain($msg));
                    $mimemail->set_html($msg);
                    //@$mimemail->send();

                    MailerNew::getInstance()->sendToEmailSub($admin_email, $admin_name, $subject, $msg);

                    include_once('smsclub.class.php');
                    $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                    $sender = Config::findByCode('sms_alphaname')->getValue();
                    $admin_phone = Config::findByCode('admin_phone')->getValue();
                   /* if (strlen($admin_phone) > 1) {
                        $admin = $sms->sendSMS($sender, $admin_phone, 'Obratnuy zvonok: ' . $name . ' Tel.: ' . $phone);
                        wsLog::add('SMS to admin: ' . $sms->receiveSMS($admin), 'SMS_' . $sms->receiveSMS($admin));
                    }*/
                    $msg = 'В ближайшее время с Вами свяжется менеджер.';
					die($msg);
                }else{
				 $msg = 'Вы не указали свой вопрос. Попробуйте еще раз.';
					die($msg);
				} 
            } else {
                $msg = 'Телефон долже содержать 10 символов. Попробуйте еще раз.';
					die($msg);
            }
			}else {
			  $msg = 'Имя должно быть больше 3 символов. Попробуйте еще раз.';
			  	die($msg);
			}
            echo $this->render('/');
          // return ;
        }

        public function registerAction()
        {
		//session_start();
            if ($_POST){
				
				$log =="";
				$error="no"; //флаг наличия ошибки
				
                foreach ($_POST as &$value) $value = stripslashes(trim($value));
					
					$info = $_POST;
					$id = '';
				
				//Проверка правильности капчи!
if ($info['captcha'] != $_SESSION['rand']) {
$log.="<li>Вы ввели неверные буквы с картинки!</li>"; $error="yes"; }
//Проверка email адреса
function isEmail($email){
                return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i"
                        ,$email));
            } 
			
if($info['email'] == '')
                {
	$log .= "<li>Пожалуйста, введите Ваш email!</li>";
	$error = "yes";
                  
                }else if(!isEmail($info['email'])){
                   
	$log .= "<li>Вы ввели неправильный e-mail. Пожалуйста, исправьте его!</li>";
	$error = "yes";
                }else if(wsActiveRecord::useStatic('Customer')->findByEmail($info['email'])->count() != 0) {
				$log .= "<li>Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь!</li>";
				$error = "yes";
                }
				
if (empty($info['telephone']))
{
	$log .= "<li>Необходимо указать телефонный номер!</li>";
	$error = "yes";
}else{
$phone = preg_replace('/[^0-9]/', '', $info['telephone']);
$phone = substr($phone, -10);
//$phone = '38'.$phone;
if(strlen($phone) != 10){
$log .= "<li>В номере должны быть только числа!</li>";
	$error = "yes";
}if(substr($phone, 0, 3) == '044'){
$log .= "<li>Укажите мобильный номер телефона! Городские номера не допускаются</li>";
	$error = "yes";

}else if(wsActiveRecord::useStatic('Customer')->findByPhone1(array(" phone1 LIKE  '%".$phone."%' "))->count() != 0){
$log .= "<li>Пользователь с таким номером телефона уже зарегистрирован в системе!</li>";
	$error = "yes";
}else{
$phone = '38'.$phone;
}
}
$date_birth = '';
			if(@$info['date_birth'] and $info['date_birth'] != ''){
			if(strlen($info['date_birth']) == 10){
			$date_birth =  date('Y-m-d', strtotime($info['date_birth']));
			}else{
			$log .= "<li>Введите корректно дату рождения!</li>";
			$error = "yes";
			}
			}else{
			$log .= "<li>Вы не ввели дату рождения!</li>";
			$error = "yes";
			}
 if (!@$info['password']){
                  $log .= "<li>Необходимо указать пароль!</li>";
	$error = "yes";
	}else if(strlen($info['password']) < 6){
	 $log .= "<li>Пароль должен быть больше 6-ти символов!</li>";
	$error = "yes";
	}
 if (!@$info['password2']){
                  $log .= "<li>Необходимо повторить пароль!</li>";
	$error = "yes";
	}else if($info['password'] != $info['password2']){
	 $log .= "<li>Пароли не совпадають. Введите пароли снова!</li>";
	$error = "yes";
	}

	if (!$info['name']){ $log .= "<li>Необходимо указать Имя!</li>";
	$error = "yes";}
	if (!$info['middle_name']){$log .= "<li>Необходимо указать Фамилию!</li>";
	$error = "yes";}
	if (!$info['city']){$log .= "<li>Необходимо указать Город!</li>";
	$error = "yes";}
    if (!$info['street']){$log .= "<li>Необходимо указать Улицу!</li>";
	$error = "yes";}

                if ($error=="no") {
				
				
				$em = iconv_substr($info['email'], 0, 4, 'UTF-8');
				if($em == 'miss'){
						$subject = 'Создан новый акаунт МИСС';
				$msg = 'Email: '.$info['email'];
				require_once('nomadmail/nomad_mimemail.inc.php');
				$mimemail = new nomad_mimemail();
				$mimemail->debug_status = 'no';
				$mimemail->set_from(null, null);
				$mimemail->set_to('php@red.ua', 'Yaroslav');
				$mimemail->set_charset('UTF-8');
				$mimemail->set_subject($subject);
				$mimemail->set_text(make_plain($msg));
				$mimemail->set_html($msg);
				//@$mimemail->send();

				MailerNew::getInstance()->sendToEmail('php@red.ua', 'Yaroslav', $subject, $msg);
				}
				
                    $customer = new Customer($id);
                    if (isset($_SESSION['parent_id']) and $_SESSION['parent_id'] != 0)
                        $customer->setParentId($_SESSION['parent_id']);
                    $customer->setUsername($info['email']);
                    $customer->setPassword(md5($info['password']));
                    $customer->setCustomerTypeId(1);
                    $customer->setCompanyName($info['company']);
                    $customer->setFirstName($info['name']);
					$customer->setMiddleName($info['middle_name']);
                    $customer->setEmail($info['email']);
                    $customer->setPhone1($phone);
					$customer->setDateBirth($date_birth);
                    $customer->setCity($info['city']);
                    $customer->setStreet($info['street']);
					$customer->setHouse($info['house']);
					$customer->setFlat($info['flat']);
                    $customer->save();
					$subscriber = new Subscriber($id);
					$subscriber->setName($info['name']);
					$subscriber->setEmail($info['email']);
					$subscriber->setConfirmed(date('Y-m-d H:i:s'));
					$subscriber->save();
                    $this->view->login = $info['email'];
                    $this->view->pass = $info['password'];
                    $admin_name = Config::findByCode('admin_name')->getValue();
                    $admin_email = Config::findByCode('admin_email')->getValue();
                    $do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
                    $msg = $this->render('email/new-customer.tpl.php');
                    $subject = 'Создан акаунт';
                    require_once('nomadmail/nomad_mimemail.inc.php');
                    $mimemail = new nomad_mimemail();
                    $mimemail->debug_status = 'no';
                    $mimemail->set_from($do_not_reply, $admin_name);
                    $mimemail->set_to($info['email'], $info['name']);
                    $mimemail->set_charset('UTF-8');
                    $mimemail->set_subject($subject);
                    $mimemail->set_text(make_plain($msg));
                    $mimemail->set_html($msg);
                    //@$mimemail->send();

                    MailerNew::getInstance()->sendToEmail($info['email'], $info['name'], $subject, $msg);

                    $customer = $this->ws->getCustomer();
                    $res = $customer->loginByEmail($info['email'], $info['password']);
                    if (!isset($_SESSION['user_data']))
                        $_SESSION['user_data'] = array();
                    $_SESSION['user_data']['login'] = $info['email'];
                    $_SESSION['user_data']['password'] = $info['password'];
                    if ($res) {
                        $this->website->updateHashes();
                    }
					 
					die("1"); //Всё Ok!
                }else{
				$z = "<p style='font: 13px Verdana;'><font color=#FF3333><strong>Ошибка !</strong></font></p><ul style='list-style: none; font: 12px Verdana; color:#000; border:1px solid #c00; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px; background-color:#fff; padding:5px; margin:5px 10px;'>".$log."</ul><br />"; 
				die($z);
				}

            }
            echo $this->render('account/register.tpl.php');
            return;
        }

        public function registerCartAction()
        {
            $errors = array();
            if ($_POST) {

                foreach ($_POST as &$value)
                    $value = stripslashes(trim($value));
                $info = $_POST;
                $error_email = 0;
                $id = '';
                if (wsActiveRecord::useStatic('Customer')->findByEmail($info['email'])->count() != 0) {
                    d(wsActiveRecord::useStatic('Customer')->findByEmail($info['email']));
                    $error_email = 1;
                    $this->view->error_email = 'Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь.';
                }
                if (wsActiveRecord::useStatic('Customer')->findByUsername($info['cart'])) {
                    $error_email = 1;
                    $this->view->error_email = 'Такой № карты уже используется.';
                    $errors[] = 'cart';
                }
                $tel = Number::clearPhone(trim($info['telephone']));
                $allowed_chars = '1234567890';
                if (!Number::clearPhone($tel)) {
                    $errors['error'] = "Введите телефонный номер";
                    $errors[] = 'telephone';
                }
                for ($i = 0; $i < mb_strlen($tel); $i++) {
                    if (mb_strpos($allowed_chars, mb_strtolower($tel[$i])) === false) {
                        $errors['error'] = "В номоре должны быть только числа";
                        $errors[] = 'telephone';
                    }
                }

                for ($i = 0; $i < mb_strlen($info['cart']); $i++) {
                    if (mb_strpos($allowed_chars, mb_strtolower($info['cart'][$i])) === false) {
                        $errors['error'] = "В № карты должны быть только числа";
                        $errors[] = 'cart';
                    }
                }
                $alredy = wsActiveRecord::useStatic('Customer')->findFirst(array('phone1' => $tel));
                if ($alredy) {
                    if ($alredy->getUsername() != null) {
                        $errors['error'] = "Пользователь с таким номером телефона уже существует";
                        $errors[] = 'telephone';
                    } else {
                        $id = $alredy->getId();
                    }
                }

                if (!$info['name'])
                    $errors[] = 'name';
                if (!@$info['password'])
                    $errors[] = 'password';
                if (!$info['password2'])
                    $errors[] = 'password';
                if (!$info['cart'])
                    $errors[] = 'cart';
                if (!$info['email'] || !isValidEmail($info['email']))
                    $errors[] = 'email';
                if ($info['password'] != $info['password2'])
                    $errors[] = 'password';
                if (strlen($info['password']) < 5) {
                    $errors['error'] = "Пароль должен быть больше 5-ти символов.";
                }
                if (!$errors and $error_email == 0) {
                    $customer = new Customer($id);
                    $customer->setUsername($info['cart']);
                    $customer->setPassword(md5($info['password']));
                    $customer->setCustomerTypeId(1);
                    $customer->setFirstName($info['name']);
                    $customer->setEmail($info['email']);
                    $customer->setPhone1($tel);
                    $customer->save();
                    $this->view->login = $info['cart'];
                    $this->view->pass = $info['password'];
                    $admin_name = Config::findByCode('admin_name')->getValue();
                    $admin_email = Config::findByCode('admin_email')->getValue();
                    $do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
                    $msg = $this->render('email/new-customer.tpl.php');
                    $subject = 'Создан акаунт';
                    require_once('nomadmail/nomad_mimemail.inc.php');
                    $mimemail = new nomad_mimemail();
                    $mimemail->debug_status = 'no';
                    $mimemail->set_from($do_not_reply, $admin_name);
                    $mimemail->set_to($info['email'], $info['name']);
                    $mimemail->set_charset('UTF-8');
                    $mimemail->set_subject($subject);
                    $mimemail->set_text(make_plain($msg));
                    $mimemail->set_html($msg);
                    //@$mimemail->send();

                    MailerNew::getInstance()->sendToEmail($info['email'], $info['name'], $subject, $msg);

                    $customer = $this->ws->getCustomer();
                    $res = $customer->loginByEmail($info['cart'], $info['password']);
                    if (!isset($_SESSION['user_data']))
                        $_SESSION['user_data'] = array();
                    $_SESSION['user_data']['login'] = $info['cart'];
                    $_SESSION['user_data']['password'] = $info['password'];
                    if ($res) {
                        $this->website->updateHashes();
                    }
                    echo $this->render('account/register_cart_ok.tpl.php');
                    return;
                }

            }
            $this->view->errors = $errors;
            echo $this->render('account/register_cart.tpl.php');
            return;
        }

        public function inviteAction()
        {
            if (isset($_GET['guest'])) {
                $_SESSION['parent_id'] = (int)$_GET['guest'];
                $this->_redirect('/');
            }
            if (!$this->ws->getCustomer()->getIsLoggedIn()) {
                $this->_redirect('/account/login/');
                return;
            }
            if ($_POST) {
                $msg = array();
                if (!isset($_POST['email']) || !$this->isValidEmail($_POST['email']))
                    $msg[] = $this->trans->get("Email is invalid");
                if (!isset($_POST['name']) || !$_POST['name'])
                    $msg[] = $this->trans->get("Please enter your name");
                if (count($msg) > 0) {
                    $this->view->errors = $msg;
                } else {
                    $admin_name = Config::findByCode('admin_name')->getValue();
                    $do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
                    $this->view->user = $this->ws->getCustomer();
                    $subject = $this->trans->get("Mail invite subject");
                    $msg = $this->render('email/invite.tpl.php');
                    require_once('nomadmail/nomad_mimemail.inc.php');
                    $mimemail = new nomad_mimemail();
                    $mimemail->debug_status = 'no';
                    $mimemail->set_from($do_not_reply, $admin_name);
                    $mimemail->set_to($_POST['email'], $_POST['name']);
                    $mimemail->set_charset('UTF-8');
                    $mimemail->set_subject($subject);
                    $mimemail->set_text(make_plain($msg));
                    $mimemail->set_html($msg);
                    //@$mimemail->send();

                    MailerNew::getInstance()->sendToEmail($_POST['email'], $_POST['name'], $subject, $msg);
                    
                    $this->view->ok = $this->trans->get("Email is send");
                }
            }
            echo $this->render('account/invite.tpl.php');
            return;
        }

        public function editAction()
        {
		/*
		//avtoregister po ssilke
		if(@$this->get->l and @$this->get->p){
		$customer = $this->ws->getCustomer();    
		$key = 'uhaehcv9ok';
		$l = trim($this->get->l);
		$p = trim($this->get->p);
		$login = $this->decode($l, $key);
		$pass = $this->decode($p, $key);
		$res = $customer->loginByEmail($login, $pass);
		//var_dump($res);
		if($res){
		 $this->website->updateHashes();
		 $this->_redirect('/account/edit/');
		}
			 
		}*///avtoregister po ssilke
            $this->view->user = $this->ws->getCustomer();
            $errors = array();
            if ($_POST) {
			if(@$_POST['temp_email']){
			$temp_email = $_POST['temp_email'];
			function isEmail($temp_email){
                return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i"
                        ,$temp_email));
            } 
			if(strlen(trim($temp_email)) == 0){
			$errors[] = "Вы не ввели новый Email!";
			}else if(!isEmail($temp_email)){
			$errors[] = "Вы ввели недопустимый Email!";
			}
			$temp_email = trim($temp_email);
			}
			$date_birth = '';
			if(isset($_POST['date_birth'])){
			
			if($_POST['date_birth'] != ''){
			if(strlen($_POST['date_birth']) == 10){
			$date_birth =  date('Y-m-d', strtotime($_POST['date_birth']));
			}else{
			$errors[] = "Введите корректно дату рождения!";
			
			}
			}else{
			$errors[] = "Вы не ввели дату рождения!";
			}
			}
              /*  $tel = Number::clearPhone(trim($_POST['phone']));
                $allowed_chars = '1234567890';
                if (!Number::clearPhone($tel))
                    $errors[] = "Введите телефонный номер";
                for ($i = 0; $i < mb_strlen($tel); $i++) {
                    if (mb_strpos($allowed_chars, mb_strtolower($tel[$i])) === false) {
                        $errors[] = "В номeре должны быть только числа";
                    }
                }*/ 
                //$alredy = wsActiveRecord::useStatic('Customer')->findFirst(array('phone1' => $tel));
               // if ($alredy and $alredy->getId() != $this->ws->getCustomer()->getId()) $errors[] = "Пользователь с таким номером телефона уже существует";
                if (strlen(trim($_POST['street'])) == 0) $errors[] = "Введите улицу";
                if (strlen(trim($_POST['name'])) == 0) $errors[] = "Введите имя";
				if (strlen(trim($_POST['middle_name'])) == 0) $errors[] = "Введите фамилию";
				//if (strlen(trim($_POST['last_name'])) == 0) $errors[] = "Введите отчество";
				//if (strlen(trim($_POST['drawing'])) != strlen('red2014') || strlen('')) $errors[] = "Вы ввели неверный акционный код";
					$curdate = Registry::get('curdate');
					$mas_adres = array();
					if (isset($_POST['index']) and mb_strlen($_POST['index']) > 1) {
						$mas_adres[] = $_POST['index'];
					}
					if (isset($_POST['obl']) and mb_strlen($_POST['obl']) > 1) {
						$mas_adres[] = $_POST['obl'];
					}
					if (isset($_POST['rayon']) and mb_strlen($_POST['rayon']) > 1) {
						$mas_adres[] = $_POST['rayon'];
					}
					if (isset($_POST['city']) and mb_strlen($_POST['city']) > 1) {
						$mas_adres[] = 'г. ' . $_POST['city'];
					}
					if (isset($_POST['street']) and mb_strlen($_POST['street']) > 1) {
						$mas_adres[] = 'ул. ' . $_POST['street'];
					}
					if (isset($_POST['house']) and mb_strlen($_POST['house']) > 1) {
						$mas_adres[] = 'д.' . $_POST['house'];
					}
					if (isset($_POST['flat']) and mb_strlen($_POST['flat']) > 1) {
						$mas_adres[] = 'кв.' . $_POST['flat'];
					}
					$_POST['address'] = implode(', ', $mas_adres);
				
                if (!count($errors)) {
                    $user = new Customer($this->ws->getCustomer()->getId());
                    $user->setCompanyName(trim($_POST['company']));
                    $user->setFirstName(trim($_POST['name']));
                    $user->setMiddleName(trim($_POST['middle_name']));
					$user->setLastName(trim($_POST['last_name']));
					$user->setIndex(trim($_POST['index']));
					$user->setObl(trim($_POST['obl']));
					$user->setRayon(trim($_POST['rayon']));
                    $user->setCity(trim($_POST['city']));
                    $user->setStreet(trim($_POST['street']));
					$user->setHouse(trim($_POST['house']));
					$user->setFlat(trim($_POST['flat']));
					if(@$_POST['temp_email']){
					$user->setBlockEmail(2);
					$user->setTempEmail($temp_email);
					$user->setEmailOk(2);}
					if(strlen($date_birth) != 0){
					$user->setDateBirth($date_birth);
					}
                    ///$user->setPhone1($tel);
					$user->setAdress($_POST['address']);
					//$user->setDrawing(trim($_POST['drawing']));
                    $user->save();
					if(@$_POST['temp_email'] and $user->getEmail() != $temp_email){
					
					$this->view->email = $temp_email;
					$this->view->email = $temp_email;
					
					$admin_email = Config::findByCode('admin_email')->getValue();
				$admin_name = Config::findByCode('admin_name')->getValue();
				$subject = 'Подтверждение изменения email на сайте RED.UA';
				$msg = $this->view->render('account/edit/edit-email.tpl.php');
	
				require_once('nomadmail/nomad_mimemail.inc.php');
				$mimemail = new nomad_mimemail();
				$mimemail->debug_status = 'no';	
				$mimemail->set_to($temp_email, $_POST['name']);
				$mimemail->set_from($admin_email, $admin_name);
				$mimemail->set_charset('UTF-8');
				$mimemail->set_subject($subject);
				$mimemail->set_text($msg);
				$mimemail->set_html($msg);
				//@$mimemail->send();
			
                MailerNew::getInstance()->sendToEmail($temp_email, $_POST['name'], $subject, $msg);
					
					
					echo $this->render('account/edit/ok_edit.tpl.php');
					return;
					}else{
                    $this->_redirect('/account/');
					}
                }
            }
			
            $this->view->errors = $errors;
            echo $this->render('account/edit.tpl.php');
            return;


        }
		public function decode($encoded, $key){//расшифровываем
		$strofsym="qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM=";//Символы, с которых состоит base64-ключ
			$x=0;
			while ($x++<= strlen($strofsym)) {//Цикл
			$tmp = md5(md5($key.$strofsym[$x-1]).$key);//Хеш, который соответствует символу, на который его заменят.
			$encoded = str_replace($tmp[3].$tmp[6].$tmp[1].$tmp[2], $strofsym[$x-1], $encoded);//Заменяем №3,6,1,2 из хеша на символ
			}
			return base64_decode($encoded);//Вертаем расшифрованную строку
			}
		
		public function activeemailAction()
	{
		$ok = 0;
		$em = $this->get->email;
		$this->view->email = $em;
		if($em){
		//$go = 
		if(@$this->get->flag){
		$temp = wsActiveRecord::useStatic('Customer')->findFirst(array('email'=>(string)trim($em), 'email_ok'=>0));
		
		}else{
		$temp = wsActiveRecord::useStatic('Customer')->findFirst(array('temp_email'=>(string)trim($em), 'email_ok'=>2));
		}
		
		if($temp){
		$sub = Subscriber::findByEmail($temp->getEmail());
		if($sub){
		$sub->setEmail(trim($em));
		$sub->setActive(1);
		$sub->save();
		}
		$t = $temp->getEmail();
			$temp->setEmail(trim($em));
			$temp->setTempEmail($t);
			$temp->setUsername(trim($em));
			$temp->setEmailOk(1);
			$temp->setCurrencyId(2);
			$temp->setBlockEmail(3);
			$temp->save();
			$ok = 1;
			$this->view->email = $temp->getEmail();
			
		}
		}
		if($ok){
			echo $this->render('account/edit/edit-good.tpl.php');
			}
		else{
			echo $this->render('account/edit/edit-bad.tpl.php');
			}
	}

        public function epassAction()
        {
            $this->view->user = $this->ws->getCustomer();
            if ($_POST) {
                $msg = array();

                if (!$_POST['oldpass'])
                    $msg[] = "Please enter old password";
                if ($this->ws->getCustomer()->getPassword() != md5($_POST['oldpass']))
                    $msg[] = "Password do not match";
                if (!$_POST['password'] || !$_POST['password2'])
                    $msg[] = "Please enter 2 passwords";
                if ($_POST['password'] != $_POST['password2'])
                    $msg[] = "Please enter the same password twice";
                if (strlen($_POST['password']) < 6)
                    $msg[] = "Please use minimum 6 symbols for password";

                if (count($msg) > 0) {
                    $this->view->errors = $msg;
                } else {
                    $customer = $this->ws->getCustomer();
                    $customer->setPassword(md5($_POST["password"]));
                    $customer->save();
                    $this->view->ok = 'Пароль сменен';
                }
            }
            echo $this->render('account/epass.tpl.php');
            return;
        }

        public function validateLoginAction()
        {
            $msg = array();

            if (!isset($_REQUEST['login']) || !$this->isValidEmail($_REQUEST['login']))
                $msg[] = $this->_trans->get("Email is invalid");
            elseif (!$customer = Customer::findByUsername($_REQUEST['login']))
                $msg[] = $this->_trans->get("Email is not found");

            if (!isset($_REQUEST['password']))
                $msg[] = $this->_trans->get("Please enter password");
            elseif (strlen($_REQUEST['password']) < 6)
                $msg[] = $this->_trans->get("Please use minimum 6 symbols for password");

            //everything is ok
            if (!count($msg)) die();

            $this->view->errors = $msg;
            echo $this->render('static/errors.tpl.php');
            die();
        }

        public function validateEmailAction()
        {
            $msg = array();

            if (!isset($_REQUEST['login']) || !$this->isValidEmail($_REQUEST['login']))
                $msg[] = $this->_trans->get("Email is invalid");
            elseif (!$customer = Customer::findByUsername($_REQUEST['login']))
                $msg[] = $this->_trans->get("Email is not found");

            //everything is ok
            if (!count($msg)) die();

            $this->view->errors = $msg;
            echo $this->render('static/errors.tpl.php');
            die();
        }

        public function validatePasswordAction()
        {
            $msg = array();

            if (!$_REQUEST['oldpass'])
                $msg[] = $this->_trans->get("Please enter old password");
            elseif ($this->webshop->getCustomer()->getPassword() != md5($_REQUEST['oldpass']))
                $msg[] = $this->_trans->get("Password do not match"); elseif (!$_REQUEST['password'] || !$_REQUEST['password2'])
                $msg[] = $this->_trans->get("Please enter 2 passwords"); elseif ($_REQUEST['password'] != $_REQUEST['password2'])
                $msg[] = $this->_trans->get("Please enter the same password twice"); elseif (strlen($_REQUEST['password']) < 6)
                $msg[] = $this->_trans->get("Please use minimum 6 symbols for password");

            //everything is ok
            if (!count($msg)) die();

            $this->view->errors = $msg;
            echo $this->render('static/errors.tpl.php');
            die();
        }

        public function validateDetailsAction()
        {
            $msg = array();

            if (!$_REQUEST['email'] || !$this->isValidEmail($_REQUEST['email']))
                $msg[] = $this->_trans->get("Email is invalid");
            elseif ($c = Customer::findByUsername($_REQUEST['email'])) {
                $id = $c->getId();
                if ($id != $this->webshop->getCustomer()->getId())
                    $msg[] = $this->_trans->get("Email is already used");
            }

            if (!isset($_REQUEST['first_name']) || !$_REQUEST['first_name'])
                $msg[] = $this->_trans->get("Please enter your name");

            if (!isset($_REQUEST['last_name']) || !$_REQUEST['last_name'])
                $msg[] = $this->_trans->get("Please enter your surname");

            if (!isset($_REQUEST['gender']) || !$_REQUEST['gender'])
                $msg[] = $this->_trans->get("Please enter your gender");

            if (!isset($_REQUEST['phone']) || !$_REQUEST['phone'])
                $msg[] = $this->_trans->get("Please enter your phone");

            //everything is ok
            if (!count($msg)) die();

            $this->view->errors = $msg;
            echo $this->render('static/errors.tpl.php');
            die();
        }

        public function loginAsAction()
        {
            if (!$this->ws->getCustomer()->isAdmin())
                return $this->show404Action();

            $user = $this->get->getUser();
            if (!(int)$user)
                $this->_redirect('/order/');

            $this->ws->getCustomer()->loginByUsername($user, wsActiveRecord::useStatic()->findByUsername($user)->at(0)->getPassword());
            $this->ws->upadteHashes();
			//добавление избранных товаров к акаунту
					if($_SESSION['desires']){
			$id = $this->ws->getCustomer()->getId();
			$array = array();
			$i=0;
			foreach ($_SESSION['desires'] as $item) {
				$array[$i]=$item;
					$i++;
					} 
				foreach($array as $article) {
				$a = wsActiveRecord::useStatic('Desires')->count(array('id_customer'=>$id, 'id_articles'=>$article)); 
				if($a == 0){
				$desires = new Desires();
				$desires->setIdCustomer($id);
				$desires->setIdArticles($article);
				$desires->save();
						}
					}
				}
			// exit добавление избранных товаров к акаунту
			//добавление избранных товаров c акаунта в cесию
			$res = wsActiveRecord::useStatic('Desires')->findByIdCustomer($id);
			if($res){
			foreach($res as $article){
			$_SESSION['desires'][$article->getId()] = $article->getId();
			}
			}
			// exitдобавление избранных товаров c акаунта в cесию
            $this->_redirect('/order/');

        }

        public function loginAction()
        {
			if (!isset($_SESSION['j25k17l2517'])) {
				if ($this->ws->getCustomer()->getIsLoggedIn()) {
					$this->_redirect('/account/'); 
					//$this->_redirect('/');
				}
			}
            if (isset($_GET['redirect'])) {
                $this->view->redirectHash = $_GET['redirect'];
            } else {
                $this->view->redirectHash = '/order/';
            }
			
			if (isset($_SESSION['j25k17l2517'])) {
				$_POST['login'] = $_GET['login'];
				$_POST['password'] = $_GET['password'];
			}

            if (isset($_POST['login']) && isset($_POST['password'])) {
                $userName = trim($_POST['login']);
                unset($_POST['login']);
                $password = $_POST['password'];
                unset($_POST['password']);

                $customer = $this->ws->getCustomer();

                //fill all other post values
                if (count($_POST)) {
                    if (!isset($_SESSION['user_data']))
                        $_SESSION['user_data'] = array();
                    foreach ($_POST as $key => $value)
                        $_SESSION['user_data'][$key] = $value;
                }
                $res = $customer->loginByEmail($userName, $password);

                if ($res) {
                    $this->website->updateHashes();
					if (isset($_SESSION['j25k17l2517'])) {
						$this->_redirect('http://www.red.ua');
					}
					else {
					//добавление избранных товаров c cесии к акаунту
					$id = $this->ws->getCustomer()->getId();
					//добавление бонуса в аккаунт
				/*	$temp = wsActiveRecord::useStatic('Customer')->findFirst(array('id'=>$id, 'time_zone_id'=>1));
					if($temp){
					$temp->setBonus(100);
					$temp->setTimeZoneId(2);
					$temp->save();
					}*/
					//выход добавление бонуса в аккаунт
					if($_SESSION['desires']){
			
			$array = array();
			$i=0;
			foreach ($_SESSION['desires'] as $item) {
				$array[$i]=$item;
					$i++;
					}
					if(count($array) > 0){
				foreach($array as $article) {
					$a = wsActiveRecord::useStatic('Desires')->count(array('id_customer'=>$id, 'id_articles'=>$article)); 
				if($a == 0){
				$desires = new Desires();
				$desires->setCtime(date('Y-m-d H:i:s'));
				$desires->setIdCustomer($id);
				$desires->setIdArticles($article);
				$desires->save();
						}
					}
					}
				}
			// exit добавление избранных товаров к акаунту
				//добавление избранных товаров c акаунта в cесию
			$res = wsActiveRecord::useStatic('Desires')->findByIdCustomer($id);
			if($res){
			foreach($res as $article){
			$_SESSION['desires'][$article->getId()] = $article->getId();
			}
			}
			// exit добавление избранных товаров c акаунта в cесию
						$this->_redirect($_GET['redirect'] ? $_GET['redirect'] : '/account/');
					}
                    return;
                } else {
                    echo $this->render('account/errorLogin.tpl.php');
                    return;
                }
            }

            echo $this->render('account/login.tpl.php');
            return;
        }


        public function logoutAction()
        {
			if(count($_SESSION['basket']) > 0){
				$admin_name = Config::findByCode('admin_name')->getValue();
					$admin_email = Config::findByCode('admin_email')->getValue();
					$do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
				
			$mel = $this->ws->getCustomer()->getEmail();
			$nam = $this->ws->getCustomer()->getFirstName(); 
				$subject = "НЕЗАВЕРШЕННЫЙ ЗАКАЗ! Успей купить, пока есть в наличии."; 
				$msg = $this->render('email/bek.template.tpl.php');
				$this->view->email = $mel;
				require_once('nomadmail/nomad_mimemail.inc.php');
				$mimemail = new nomad_mimemail();
				$mimemail->debug_status = 'no';
				$mimemail->set_from($do_not_reply, $admin_name);
				$mimemail->set_to($mel, $nam);
				$mimemail->set_charset('UTF-8');
				$mimemail->set_subject($subject);
				$mimemail->set_text(make_plain($msg));
				$mimemail->set_html($msg);
				//@$mimemail->send();

				MailerNew::getInstance()->sendToEmail($mel, $nam, $subject, $msg);	
			}
			
            $this->ws->getCustomer()->logout();
            $this->ws->updateHashes();
			unset($_SESSION['desires']);
            //$this->webshopUpdate();
            //$_SESSION['exit'] = 1;
            $str = ((isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false)
                ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI']);// заменить $_SERVER['REQUEST_URI'] на '/'
            if (isset($_GET['redirect']))
                $str = $_GET['redirect'];
            $this->_redirect($str);
            return;
        }

        public function resetpasswordAction()
        {
            // If authenticated, go to account
            if ($this->ws->getCustomer()->getIsLoggedIn()) {
                $this->_redirect('/account/');
                return;
            }

            if (!isset($_POST['login'])) {
                echo $this->view->render('account/resetPassword.tpl.php');
            } elseif (isset($_POST['login'])) {
                // customer not found
                if (!$customer = wsActiveRecord::useStatic('Customer')->findByUsername($_POST['login'])) {
                    $this->view->error = "Пользователь с такой электронной почтой или телефоном не найден";
                } // customer found, reset the password and send the new one
                else {

                    $allowedChars = 'abcdefghijklmnopqrstuvwxyz'
                        . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                        . '0123456789';
                    $allowedCharsLength = strlen($allowedChars);
                    $newPass = '';
                    while (strlen($newPass) < 8)
                        $newPass .= $allowedChars[rand(0, $allowedCharsLength - 1)];
                    if (strlen($customer->getEmail()) > 4) {

                        $admin_email = Config::findByCode('admin_email')->getValue();
                        $admin_name = Config::findByCode('admin_name')->getValue();
                        $do_not_reply = Config::findByCode('do_not_reply_email')->getValue();
                        //$all_email = Config::findByCode('bcc_all_emails')->getValue();


                        $customer->setPassword(md5($newPass));
                        $customer->save();

                        $subject = $this->trans->get('Your new password for red.ua');
                        $this->view->new_password = $newPass;
                        $this->view->customer = $customer;
                        $msg = 'Логин: ' . $customer->getUsername() . '. ' . $this->trans->get('Your new password for red.ua') . ': ' . $newPass;
                        //echo $this->view->render('account/resetPassword-email.tpl.php');

                        require_once('nomadmail/nomad_mimemail.inc.php');
                        $mimemail = new nomad_mimemail();
                        $mimemail->debug_status = 'no';
                        $mimemail->set_from($do_not_reply, $admin_name);
                        $mimemail->set_to($customer->getEmail(), $customer->getFullname());
                        $mimemail->set_charset('UTF-8');
                        $mimemail->set_subject($subject);
                        $mimemail->set_text($msg);
                        $mimemail->set_html(nl2br($msg));
                        //@$mimemail->send();

                        MailerNew::getInstance()->sendToEmail($customer->getEmail(), $customer->getFullname(), $subject, $msg);

                        $this->view->ok = 1;
                    } else {

                        $customer->setPassword(md5($newPass));
                        $customer->save();
                        $phone = Number::clearPhone($customer->getPhone1());
                        include_once('smsclub.class.php');
                        $sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
                        $sender = Config::findByCode('sms_alphaname')->getValue();
                        $user = $sms->sendSMS($sender, $phone, 'Vash login: ' . $customer->getUsername() . '. Vash novyj password ' . $newPass);
                        wsLog::add('SMS to user: ' . $sms->receiveSMS($user), 'SMS_' . $sms->receiveSMS($user));
                        $this->view->ok = 1;
                    }
                }


                echo $this->view->render('account/resetPassword.tpl.php');
            }

            return;
        }


        public function changepasswordAction()
        {
            if (!$this->ws->getCustomer()->getIsLoggedIn()) {
                $this->_redirect('/account/');
                return;
            }

            $this->view->content = $this->view->render('account/changePassword.tpl.php');

            if (count($_POST)) {
                $customer = $this->ws->getCustomer();
                $customer->setOpenPassword($_POST["password"]);
                $customer->save();
                //$this->view->templates['bodyTemplate'] = 'account/changePasswordStatus.tpl.php';
                $this->_redirect('/account/');
                return;
            }

            echo $this->view->render('site.tpl.php');
            return;
        }

        public function changedetailsAction()
        {
            if (!$this->ws->getCustomer()->getIsLoggedIn()) {
                $this->_redirect('/account/');
                return;
            }

            $this->view->content = $this->view->render('account/changeDetails.tpl.php');

            if (count($_POST)) {
                $customer = $this->ws->getCustomer();
                $customer->setGender($_POST["gender"]);
                $customer->setFirstName($_POST["first_name"]);
                $customer->setMiddleName($_POST["middle_name"]);
                $customer->setLastName($_POST["last_name"]);
                $customer->setPhone1($_POST["phone"]);
                $customer->setPhone2($_POST["mobile"]);
                $customer->setEmail($_POST["email"]);
                $customer->save();

                $this->_redirect('/account/');
                return;
            }

            echo $this->view->render('site.tpl.php');
            return;
        }

        public function orderhistoryAction()
        {
            if (!$this->ws->getCustomer()->getIsLoggedIn()) {
                $this->_redirect('/account/');
                return;
            }
            /* if ($this->get->deleteorder > 0) {
                 $order = new Shoporders((int)$this->get->deleteorder);
                 if ($order->getId()  and $order->getCustomerId() == $this->ws->getCustomer()->getId() and in_array($order->getStatus(), array(0, 1, 9, 11, 10))) {
                     foreach ($order->articles as $art) {
                         $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $art->getArticleId(), 'id_size' => $art->getSize(), 'id_color' => $art->getColor()));
                         $article->setCount($article->getCount() + $art->getCount());
                         $article->save();
                         $artic = new Shoparticles($art->getArticleId());
                         $artic->setStock($artic->getStock() + $art->getCount());
                         $artic->save();
                         $art->setCount(0);
                         $art->save();
                     }
                     $deposit = $order->getDeposit();
                     $order->setDeposit(0);
                     $order->setStatus(2);
                     $order->save();
                     $customer = new Customer($order->getCustomerId());
                     $customer->setDeposit((float)$customer->getDeposit() + (float)$deposit);
                     $customer->save();
                 }
                 $this->_redirect('/account/orderhistory/');
             }*/

            $onPage = 5;
            $page = 1;
            if ((int)$this->get->page > 0) {
                $page = (int)$this->get->page;
            }

            $this->view->user = $this->ws->getCustomer();
            $this->view->onpage = $onPage;
            $this->view->page = $page;
            $all_orders = wsActiveRecord::useStatic('Customer')->findByQuery('
				SELECT
					IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
				FROM
					ws_order_articles
			        JOIN ws_orders
					ON ws_order_articles.order_id = ws_orders.id
				WHERE
					ws_orders.customer_id = ' . $this->ws->getCustomer()->getId() . '
					AND ws_orders.status IN (100,1,3,4,6,8,9,10,11,13,15,16) ')->at(0);

            $this->view->all_orders_amount = $all_orders->getAmount();

            $this->view->allcount = wsActiveRecord::useStatic('Shoporders')->count(array('customer_id' => $this->ws->getCustomer()->getId()));
            $this->view->orders = wsActiveRecord::useStatic('Shoporders')->findAll(array(' status IN (100,2,1,3,4,5,6,7,8,9,10,11,12,13,15,16)','customer_id' => $this->ws->getCustomer()->getId()), array(), array($onPage * ($page - 1), $onPage));

            echo $this->view->render('account/orderhistory.tpl.php');

            return;
        }

        public function send2friendAction()
        {
            if (count($_POST)) {
                $msg = "<a href='" . $_SERVER["HTTP_REFERER"] . "' target='_blank'>" . $_SERVER["HTTP_REFERER"] . "</a><br /><br />" . str_replace("\n", "<br />", $_POST["comment"]);

                $subject = $this->_trans->get("Your friend suggested you this link");
                $mimemail = new nomad_mimemail();
                $mimemail->debug_status = 'no';
                $mimemail->set_from($_POST["email_from"], $_POST["name_from"]);
                $mimemail->set_to($_POST["email_to"], $_POST["name_to"]);
                $mimemail->set_charset('UTF-8');
                $mimemail->set_subject($subject);
                $mimemail->set_text(make_plain($msg));
                $mimemail->set_html($msg);
                //@$mimemail->send();

                MailerNew::getInstance()->sendToEmail($_POST["email_to"], $_POST["name_to"], $subject, $msg);

                $this->_redirect($_SERVER["HTTP_REFERER"]);
            } else {
                echo $this->view->render('account/send2friend.tpl.php');
            }
        }

        public function returnarticlesAction()
        {
            if ($this->get->order) {
                $order = new Shoporders($this->get->order);
                if ($order->getId() and in_array($order->getStatus(), array(4, 6))) {
                    if (count($_POST)) {
                        $articles = array();
                        $count = 0;
                        foreach ($_POST as $key => $val) {
                            $mas = explode('_', $key);
                            if (count($mas) == 2) {
                                if ($mas[0] == 'artice') {
                                    if (!ShoporderarticlesVozrat::isArticleVozvat($mas[1])) {
                                        $articles[$count]['id'] = $mas[1];
                                        $articles[$count]['count'] = $_POST['count_' . $mas[1]];
                                        $count++;
                                    }
                                }
                            }
                        }
                        if (count($articles)) {
                            $vozratOrder = new ShopordersVozrat();
                            $vozratOrder->import($order->export());
                            $vozratOrder->setDateModify(date('Y-m-d H:i:s'));
                            $vozratOrder->setRealAmount((double)$order->getTotal('a'));
                            $vozratOrder->setSposob($_POST['sposob']);
                            $vozratOrder->setComments($_POST['comments']);
                            $vozratOrder->setOldOrder($order->getId());
                            $vozratOrder->setId(null);
                            $vozratOrder->save();

                            foreach ($articles as $art) {
                                $orderArticle = new Shoporderarticles($art['id']);
                                $vozratArticle = new ShoporderarticlesVozrat();
                                $vozratArticle->import($orderArticle->export());
                                $vozratArticle->setOrderId($vozratOrder->getId());
                                $vozratArticle->setCount($art['count']);
                                $vozratArticle->setId(null);
                                $vozratArticle->setOldArticle($orderArticle->getId());
                                $price = $orderArticle->getPerc($order->getAllAmount()); // цена товара с кидкой
                                $to_pay = $price['price'];

                                $vozratArticle->setPrice($to_pay);
                                $vozratArticle->save();
                            }
                            $this->view->saved = 1;
                        }
                    }
                    $this->view->order = $order;
                    echo $this->view->render('account/vozrat.tpl.php');
                } else {
                    $this->_redirect('/');
                }
            } else {
                $this->_redirect('/');
            }
        }

        public function novaposhtaAction()
        {
            $order = new Shoporders($this->get->id);
            if ($order->getId()) {
                if($order->getCustomerId()!=$this->ws->getCustomer()->getId()){
                  $this->_redirect('/');
                }


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

                $all_orders = wsActiveRecord::useStatic('Customer')->findByQuery('
                      SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
          			        FROM ws_order_articles
          			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
          			WHERE ws_orders.customer_id = ' . $customer_id . ' AND ws_orders.status IN (100,1,3,4,6,8,9,10,11,13,15,16) ')->at(0);
                $all_orders_2 = wsActiveRecord::useStatic('Customer')->findByQuery('
          			        SELECT IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
          			        FROM ws_order_articles
          			        JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
          			        WHERE ws_orders.customer_id = ' . $customer_id . ' AND ws_orders.status IN (100,1,3,4,6,8,9,10,11,13,15,16) AND ws_orders.id <=' . $this->get->id)->at(0);

                $this->view->all_orders_amount = $all_orders->getAmount();
                $this->view->all_orders_amount_total = $all_orders_2->getAmount();

                echo $this->render('account/novap_mail.tpl.php');
                die();

            } else {
                $this->_redirect('/');
            };
        }


    }
