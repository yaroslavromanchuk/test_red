<?php
    class MailerNew
    {
        static private $instance = null;
        private $admin_email;
        private $admin_name;
        private $email_charset;
        private $mimemail;

        static public function getInstance()
        {
            if (self::$instance == null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        private function  __construct()
        {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/application/packages/phpmailer/my.class.phpmailer.php');

            $this->mimemail = new PHPMailer(true);
			$this->admin_email = Config::findByCode('admin_email')->getValue();
			$this->admin_email_pass = Config::findByCode('admin_email_pass')->getValue();
			
			$this->subscribe_email = Config::findByCode('subscribe_email')->getValue();
			$this->subscribe_pass = Config::findByCode('subscribe_pass')->getValue();
            $this->admin_name = Config::findByCode('admin_name')->getValue();
            $this->email_charset = 'UTF-8';
        }

//	MailerNew::getInstance()->sendToEmail($admin_email, 'RED', $subject, $msg, 0, $order->getEmail(), $order->getName());
        /**
         * 
         * @param type $email
         * @param type $name
         * @param type $subject
         * @param type $text
         * @param type $new
         * @param type $from_email
         * @param type $from_name
         * @param type $smtp
         * @param type $usubscribe_text
         * @param type $subsciber
         * @param type $uploadfile
         * @param type $filename
         * @return boolean
         */
        public function sendToEmail($email, $name = '', $subject, $text, $new = 1, $from_email = '', $from_name = '', $smtp = 1, $usubscribe_text = 0, $subsciber = 0, $uploadfile = '', $filename = '')
        {

            if ($new) {
                $this->mimemail = new PHPMailer(true);
            }

            if (!$this->isValidEmailNew($email)) {

                return false;
            }

            if ($subsciber) {
                $find = wsActiveRecord::useStatic('Customer')->findFirst(array('username' => $email));
                if ($find and $find->getSubscriber() == 0) {
                    return false;
                }

                $find = wsActiveRecord::useStatic('Customer')->findFirst(array('email' => $email));
                if ($find and $find->getSubscriber() == 0) {
                    return false;
                }
            }


            $text = iconv('UTF-8', $this->email_charset, trim($text));
            $subject = iconv('UTF-8', $this->email_charset, $subject);

            if (!trim(strip_tags($text)) || !trim($subject)) {
                return false;
            }


            $row = array();
            $row['email'] = $email;
            $row['name'] = $name;
	
            if ($smtp) {
                $this->mimemail->IsSMTP();
                $this->mimemail->SMTPAuth = true;
                $this->mimemail->SMTPSecure = "tls";
                $this->mimemail->Host = 'mail.red.org.ua';
                $this->mimemail->Port = '25';
                $this->mimemail->Username = $this->admin_email;
                $this->mimemail->Password = $this->admin_email_pass;
            }

			//$this->mimemail->SMTPDebug = 2;
            $this->mimemail->IsHTML(true);
            $this->mimemail->CharSet = 'UTF-8';
            $this->mimemail->Subject = "=?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
            if($uploadfile and $filename){
		$this->mimemail->addAttachment($uploadfile, $filename);
            }

            try {
                $this->mimemail->AddAddress($email, $name);
                if ($from_name) {
                    $this->mimemail->FromName = $from_name;
                } else {
                    $this->mimemail->FromName = $this->admin_name;
                }
                if ($from_email) {
                    $this->mimemail->From = $from_email;
                } else {
                    $this->mimemail->From = $this->admin_email;
                }
                $this->mimemail->Body = $text;
                @$this->mimemail->send();
                $this->mimemail->ClearAddresses();
                $this->mimemail->ClearAttachments();
                $this->mimemail->IsHTML(false);
                wsLog::add('Email notification "' . $subject . '" sent to: ' . $row['email'], 'Email');
                
            } catch (phpmailerException $e) {
              wsLog::add($e->errorMessage(), 'ERROR'); //Pretty error messages from PHPMailer
            } catch (Exception $e) {
              wsLog::add($e->getMessage(), 'ERROR'); //Boring error messages from anything else!
            }

            return true;
        }
	/**
         * 
         * @param type $email
         * @param type $name
         * @param type $subject
         * @param type $text
         * @param type $new
         * @param type $from_email
         * @param type $from_name
         * @param type $smtp
         * @param type $usubscribe_text
         * @param type $subsciber
         * @return boolean
         */	
	public function sendToEmailSub($email, $name = '', $subject, $text, $new = 1, $from_email = '', $from_name = '', $smtp = 1, $usubscribe_text = 0, $subsciber = 0)
        {

            if ($new) {
                $this->mimemail = new PHPMailer(true);
            }

            if (!$this->isValidEmailNew($email)) {

                return false;
            }

            if ($subsciber) {
                $find = wsActiveRecord::useStatic('Customer')->findFirst(array('username' => $email));
                if ($find and $find->getSubscriber() == 0) {
                    return false;
                }

                $find = wsActiveRecord::useStatic('Customer')->findFirst(array('email' => $email));
                if ($find and $find->getSubscriber() == 0) {
                    return false;
                }
            }

            //if ($usubscribe_text) {
                //$text .= '<p>' . 'Чтобы отписаться от этой рассылки, перейдите <a  href="http://datamind.com.ua/unsubscriber?code=' . Customer::getHideEmail($email) . '">по ссылке</a>' . '<p>';
            //}

            $text = iconv('UTF-8', $this->email_charset, trim($text));
            $subject = iconv('UTF-8', $this->email_charset, $subject);

            if (!trim(strip_tags($text)) || !trim($subject)) {
                return false;
            }


            $row = array();
            $row['email'] = $email;
            $row['name'] = $name;
			/*
            $this->view->data = $row;
            $this->view->post = new Orm_Array();
            $this->view->post->message = $text;

            $msg = $text;//$this->view->render('email/general.tpl.php');
*/
            if ($smtp) {
                $this->mimemail->IsSMTP();
                $this->mimemail->SMTPAuth = true;
                $this->mimemail->SMTPSecure = "tls";
                $this->mimemail->Host = 'mail.red.org.ua';
                $this->mimemail->Port = '25';
                $this->mimemail->Username = $this->subscribe_email;
                $this->mimemail->Password = $this->subscribe_pass; 
            }


            $this->mimemail->IsHTML(true);
            $this->mimemail->CharSet = 'UTF-8';
            $this->mimemail->Subject = "=?UTF-8?B?" . base64_encode($subject) . "?=\r\n";

            try {
                $this->mimemail->AddAddress($email, $name);
                if ($from_name) {
                    $this->mimemail->FromName = $from_name;
                } else {
                    $this->mimemail->FromName = $this->admin_name;
                }
                if ($from_email) {
                    $this->mimemail->From = $from_email;
                } else {
                    $this->mimemail->From = $this->subscribe_email;
                }
                $this->mimemail->Body = $text;
                @$this->mimemail->Send();
                wsLog::add('Email notification "' . $subject . '" sent to: ' . $row['email'], 'Email');
            } catch (phpmailerException $e) {
              wsLog::add($e->errorMessage(), 'ERROR'); //Pretty error messages from PHPMailer
            } catch (Exception $e) {
              wsLog::add($e->getMessage(), 'ERROR'); //Boring error messages from anything else!
            }

            return true;
        }
		
		

        public function sendToEmailWithFile($email, $name = '', $subject, $text, $new = 1, $from_email = '', $from_name = '', $smtp = 0, $file = '', $file_name = '', $usubscribe_text = 0, $subsciber = 0)
        {

            if ($new) {
                $this->mimemail = new PHPMailer(true);
            }

            if (!$this->isValidEmailNew($email)) {
                return false;
            }
            if ($subsciber) {
                $find = wsActiveRecord::useStatic('Customer')->findFirst(array('username' => $email));
                if ($find and $find->getSubscriber() == 0) {
                    return false;
                }

                $find = wsActiveRecord::useStatic('Customer')->findFirst(array('email' => $email));
                if ($find and $find->getSubscriber() == 0) {
                    return false;
                }
            }

            //if ($usubscribe_text) {
                //$text .= '<p>' . 'Чтобы отписаться от этой рассылки, перейдите <a  href="http://datamind.com.ua/unsubscriber?code=' . Customer::getHideEmail($email) . '">по ссылке</a>' . '<p>';
            //}

            $text = iconv('UTF-8', $this->email_charset, trim($text));
            $subject = iconv('UTF-8', $this->email_charset, $subject);
            if (!trim(strip_tags($text)) || !trim($subject)) {
                return false;
            }

            /*
            $row = array();
            $row['email'] = $email;
            $row['name'] = $name;
            $this->view->data = $row;
            $this->view->post = new Orm_Array();
            $this->view->post->message = $text;

            $msg = $this->view->render('email/empty.tpl.php');
            */

            if ($smtp) {
                $this->mimemail->IsSMTP();
                $this->mimemail->SMTPAuth = true;
                $this->mimemail->SMTPSecure = "tls";
                $this->mimemail->Host = 'email-smtp.us-east-1.amazonaws.com';
                $this->mimemail->Port = '25';
                $this->mimemail->Username = 'AKIAIKOIDWHCPVFMRRTA';
                $this->mimemail->Password = 'AhPtG0jqLzEcZ9b5oj1iAs6E9CJVdu87N5Hm3YiBVFy8';
            }

            $this->mimemail->IsHTML(true);
            $this->mimemail->AddAddress($email, $name);
            $this->mimemail->CharSet = 'UTF-8';
            $this->mimemail->Subject = "=?UTF-8?B?" . base64_encode($subject) . "?=\r\n";

            if ($from_name) {
                $this->mimemail->FromName = $from_name;
            } else {
                $this->mimemail->FromName = $this->admin_name;
            }
            if ($from_email) {
                $this->mimemail->From = $from_email;
            } else {
                $this->mimemail->From = $this->admin_email;
            }

            $this->mimemail->Body = $text;
            if ($file and $file_name) {
                $this->mimemail->AddEmbeddedImage($file, $file_name);
            }

            @$this->mimemail->send();
            wsLog::add('Email notification "' . $subject . '" sent to: ' . $row['email'], 'Email');

            return true;
        }

        public function isValidEmailNew($email)
        {
            return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
        }

    }