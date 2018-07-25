<?php
	class PaymentController extends controllerAbstract
	{
	
	private $key = 'joiedevivre';
	
	private function calculateHash($pay_data)
    {
		return strtoupper(hash('sha256',$pay_data['LMI_MERCHANT_ID'].$pay_data['LMI_PAYMENT_NO'].$pay_data['LMI_SYS_PAYMENT_ID'].$pay_data['LMI_SYS_PAYMENT_DATE'].$pay_data['LMI_PAYMENT_AMOUNT'].$pay_data['LMI_PAID_AMOUNT'].$pay_data['LMI_PAYMENT_SYSTEM'].$pay_data['LMI_MODE'].$this->key));
    }
	
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
						LMI_MERCHANT_ID LIKE '".$LMI_MERCHANT_ID."'
						AND LMI_PAYMENT_AMOUNT LIKE '".$LMI_PAYMENT_AMOUNT."'
						AND LMI_PAYMENT_NO LIKE '".$LMI_PAYMENT_NO."'
						AND LMI_PAYMENT_DESC LIKE '".$LMI_PAYMENT_DESC."'
						AND LMI_PAYMENT_SYSTEM LIKE '".$LMI_PAYMENT_SYSTEM."'
				";
				$count = wsActiveRecord::findByQueryArray($sql);
				$count = $count[0]->cnt;
				$count = 1;
				if ($count === 1) {
					$answer = "YES";
				}else{
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
			}else if(isset($_POST['LMI_HASH'])) {
			
			$data = $_POST;
					$hash = $this->calculateHash($data);
			if ($hash == $data['LMI_HASH']){
			
			$order_id = $data['LMI_PAYMENT_NO'];
			
			$order = new Shoporders((int)$order_id);
			$order->setLiqpayStatusId(3);
			$order->save();
			

				$LMI_MERCHANT_ID			= mysql_real_escape_string($data['LMI_MERCHANT_ID']);
				$LMI_PAYMENT_AMOUNT			= mysql_real_escape_string($data['LMI_PAYMENT_AMOUNT']);
				$LMI_PAID_AMOUNT			= mysql_real_escape_string($data['LMI_PAID_AMOUNT']);
				$LMI_PAYMENT_NO				= mysql_real_escape_string($data['LMI_PAYMENT_NO']);
				$LMI_MODE					= mysql_real_escape_string($data['LMI_MODE']);
				$LMI_SYS_PAYMENT_ID			= mysql_real_escape_string($data['LMI_SYS_PAYMENT_ID']);
				$LMI_PAYER_IDENTIFIER		= mysql_real_escape_string($data['LMI_PAYER_IDENTIFIER']);
				$LMI_PAYMENT_DESC			= mysql_real_escape_string($data['LMI_PAYMENT_DESC']);
				$LMI_PAYER_PHONE_NUMBER		= mysql_real_escape_string($data['LMI_PAYER_PHONE_NUMBER']);
				$LMI_PAYER_EMAIL			= mysql_real_escape_string($data['LMI_PAYER_EMAIL']);
				$LMI_HASH					= mysql_real_escape_string($data['LMI_HASH']);

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
			}
			die();
		}
		
		

		public function paymasterAction() {
			echo $this->render('payment/payment.tpl.php');
		}
	}
?>