<?php
	class PaymentController extends controllerAbstract
	{
		public function indexAction() {
			echo $this->render('payment/index.tpl.php');
		}

		public function successAction() {
			$LMI_MERCHANT_ID		= mysql_real_escape_string($_POST['LMI_MERCHANT_ID']);
			$LMI_PAYMENT_NO			= mysql_real_escape_string($_POST['LMI_PAYMENT_NO']);
			$LMI_PAYMENT_AMOUNT		= mysql_real_escape_string($_POST['LMI_PAYMENT_AMOUNT']);
			$LMI_PAYMENT_DESC		= mysql_real_escape_string($_POST['LMI_PAYMENT_DESC']);
			$LMI_SYS_PAYMENT_ID		= mysql_real_escape_string($_POST['LMI_SYS_PAYMENT_ID']);
			$LMI_MODE				= mysql_real_escape_string($_POST['LMI_MODE']);
			$LMI_SYS_PAYMENT_DATE	= mysql_real_escape_string($_POST['LMI_SYS_PAYMENT_DATE']);
			$LMI_PAYMENT_SYSTEM		= mysql_real_escape_string($_POST['LMI_PAYMENT_SYSTEM']);

			$sql = "
				INSERT INTO
					pay_success
				(
					LMI_MERCHANT_ID,
					LMI_PAYMENT_NO,
					LMI_PAYMENT_AMOUNT,
					LMI_PAYMENT_DESC,
					LMI_SYS_PAYMENT_ID,
					LMI_MODE,
					LMI_SYS_PAYMENT_DATE,
					LMI_PAYMENT_SYSTEM
				)
				VALUES (
					'".$LMI_MERCHANT_ID."',
					'".$LMI_PAYMENT_NO."',
					'".$LMI_PAYMENT_AMOUNT."',
					'".$LMI_PAYMENT_DESC."',
					'".$LMI_SYS_PAYMENT_ID."',
					'".$LMI_MODE."',
					'".$LMI_SYS_PAYMENT_DATE."',
					'".$LMI_PAYMENT_SYSTEM."'
				)
			";
			wsActiveRecord::query($sql);

			echo $this->render('payment/success.tpl.php');

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
					AND ws_orders.status IN (0,1,3,4,6,8,9,10,11,13,15,16) ')->at(0);

            $this->view->all_orders_amount = $all_orders->getAmount();

            $this->view->allcount = wsActiveRecord::useStatic('Shoporders')->count(array('customer_id' => $this->ws->getCustomer()->getId()));
            $this->view->orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('customer_id' => $this->ws->getCustomer()->getId()), array(), array($onPage * ($page - 1), $onPage));

            echo $this->view->render('account/orderhistory.tpl.php');

            return;
		}

		public function unsuccessAction() {
			$LMI_MERCHANT_ID		= mysql_real_escape_string($_POST['LMI_MERCHANT_ID']);
			$LMI_PAYMENT_NO			= mysql_real_escape_string($_POST['LMI_PAYMENT_NO']);
			$LMI_PAYMENT_AMOUNT		= mysql_real_escape_string($_POST['LMI_PAYMENT_AMOUNT']);
			$LMI_PAYMENT_DESC		= mysql_real_escape_string($_POST['LMI_PAYMENT_DESC']);
			$LMI_CLIENT_MESSAGE		= mysql_real_escape_string($_POST['LMI_CLIENT_MESSAGE']);

			$sql = "
				INSERT INTO
					pay_unsuccess
				(
					LMI_MERCHANT_ID,
					LMI_PAYMENT_NO,
					LMI_PAYMENT_AMOUNT,
					LMI_PAYMENT_DESC,
					LMI_CLIENT_MESSAGE
				)
				VALUES (
					'".$LMI_MERCHANT_ID."',
					'".$LMI_PAYMENT_NO."',
					'".$LMI_PAYMENT_AMOUNT."',
					'".$LMI_PAYMENT_DESC."',
					'".$LMI_CLIENT_MESSAGE."'
				)
			";
			wsActiveRecord::query($sql);
			echo $this->render('payment/unsuccess.tpl.php');
		}

		public function resultAction() {
			if (isset($_POST['LMI_PREREQUEST'])) {
				$LMI_PREREQUEST				= mysql_real_escape_string($_POST['LMI_PREREQUEST']);
				$LMI_MERCHANT_ID			= mysql_real_escape_string($_POST['LMI_MERCHANT_ID']);
				$LMI_PAYMENT_AMOUNT			= mysql_real_escape_string($_POST['LMI_PAYMENT_AMOUNT']);
				$LMI_PAYMENT_NO				= mysql_real_escape_string($_POST['LMI_PAYMENT_NO']);
				$LMI_MODE					= mysql_real_escape_string($_POST['LMI_MODE']);
				$LMI_PAYMENT_SYSTEM			= mysql_real_escape_string($_POST['LMI_PAYMENT_SYSTEM']);
				$LMI_PAYMENT_DESC			= mysql_real_escape_string($_POST['LMI_PAYMENT_DESC']);
				$LMI_PAYER_PHONE_NUMBER		= mysql_real_escape_string($_POST['LMI_PAYER_PHONE_NUMBER']);
				$LMI_PAYER_EMAIL			= mysql_real_escape_string($_POST['LMI_PAYER_EMAIL']);

				$sql = "
					SELECT
						count(*) cnt
					FROM
						pay_send
					WHERE
						LMI_MERCHANT_ID = '".$LMI_MERCHANT_ID."'
						AND LMI_PAYMENT_AMOUNT = '".$LMI_PAYMENT_AMOUNT."'
						AND LMI_PAYMENT_NO = '".$LMI_PAYMENT_NO."'
						AND LMI_PAYMENT_DESC = '".$LMI_PAYMENT_DESC."'
						AND LMI_PAYMENT_SYSTEM = '".$LMI_PAYMENT_SYSTEM."'
				";
				$count = wsActiveRecord::findByQueryArray($sql);
				$count = $count[0]->cnt;
				$count = 1;
				if ($count === 1) {
					$answer = "YES";
				}
				else {
					$answer = $count;
				}
					$answer = "YES";

				$sql = "
					INSERT INTO
						pay_confirmation
					(
						LMI_PREREQUEST,
						LMI_MERCHANT_ID,
						LMI_PAYMENT_AMOUNT,
						LMI_PAYMENT_NO,
						LMI_MODE,
						LMI_PAYMENT_SYSTEM,
						LMI_PAYMENT_DESC,
						LMI_PAYER_PHONE_NUMBER,
						LMI_PAYER_EMAIL,
						st
					)
					VALUES (
						'".$LMI_PREREQUEST."',
						'".$LMI_MERCHANT_ID."',
						'".$LMI_PAYMENT_AMOUNT."',
						'".$LMI_PAYMENT_NO."',
						'".$LMI_MODE."',
						'".$LMI_PAYMENT_SYSTEM."',
						'".$LMI_PAYMENT_DESC."',
						'".$LMI_PAYER_PHONE_NUMBER."',
						'".$LMI_PAYER_EMAIL."',
						'".$count."'
					)
				";
				wsActiveRecord::query($sql);
				echo $answer;
			}
			elseif (isset($_POST['LMI_HASH'])) {
				$LMI_MERCHANT_ID			= mysql_real_escape_string($_POST['LMI_MERCHANT_ID']);
				$LMI_PAYMENT_AMOUNT			= mysql_real_escape_string($_POST['LMI_PAYMENT_AMOUNT']);
				$LMI_PAID_AMOUNT			= mysql_real_escape_string($_POST['LMI_PAID_AMOUNT']);
				$LMI_PAYMENT_NO				= mysql_real_escape_string($_POST['LMI_PAYMENT_NO']);
				$LMI_MODE					= mysql_real_escape_string($_POST['LMI_MODE']);
				$LMI_SYS_PAYMENT_ID			= mysql_real_escape_string($_POST['LMI_SYS_PAYMENT_ID']);
				$LMI_PAYER_IDENTIFIER		= mysql_real_escape_string($_POST['LMI_PAYER_IDENTIFIER']);
				$LMI_PAYMENT_DESC			= mysql_real_escape_string($_POST['LMI_PAYMENT_DESC']);
				$LMI_PAYER_PHONE_NUMBER		= mysql_real_escape_string($_POST['LMI_PAYER_PHONE_NUMBER']);
				$LMI_PAYER_EMAIL			= mysql_real_escape_string($_POST['LMI_PAYER_EMAIL']);
				$LMI_HASH					= mysql_real_escape_string($_POST['LMI_HASH']);

				$sql = "
					INSERT INTO
						pay_result
					(
						LMI_MERCHANT_ID,
						LMI_PAYMENT_AMOUNT,
						LMI_PAID_AMOUNT,
						LMI_PAYMENT_NO,
						LMI_MODE,
						LMI_SYS_PAYMENT_ID,
						LMI_PAYMENT_SYSTEM,
						LMI_SYS_PAYMENT_DATE,
						LMI_PAYER_IDENTIFIER,
						LMI_PAYMENT_DESC,
						LMI_PAYER_PHONE_NUMBER,
						LMI_PAYER_EMAIL,
						LMI_HASH
					)
					VALUES (
						'".$LMI_MERCHANT_ID."',
						'".$LMI_PAYMENT_AMOUNT."',
						'".$LMI_PAID_AMOUNT."',
						'".$LMI_PAYMENT_NO."',
						'".$LMI_MODE."',
						'".$LMI_SYS_PAYMENT_ID."',
						'".$LMI_PAYMENT_SYSTEM."',
						'".$LMI_SYS_PAYMENT_DATE."',
						'".$LMI_PAYER_IDENTIFIER."',
						'".$LMI_PAYMENT_DESC."',
						'".$LMI_PAYER_PHONE_NUMBER."',
						'".$LMI_PAYER_EMAIL."',
						'".$LMI_HASH."'
					)
				";
				wsActiveRecord::query($sql);
			}
			die();
		}

		public function paymasterAction() {
			echo $this->render('payment/payment.tpl.php');
		}
	}
?>