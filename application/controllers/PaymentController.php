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
						count(pay_send.id) cnt
					FROM
						pay_send
					WHERE
						LMI_MERCHANT_ID LIKE '%".$LMI_MERCHANT_ID."%'
						AND LMI_PAYMENT_AMOUNT LIKE '%".(float)$LMI_PAYMENT_AMOUNT."%'
						AND LMI_PAYMENT_NO LIKE '%".$LMI_PAYMENT_NO."%'
						AND LMI_PAYMENT_SYSTEM LIKE '%".$LMI_PAYMENT_SYSTEM."%'
				";
				$count = wsActiveRecord::findByQueryFirstArray($sql)['cnt'];
				//$count = $count[0]->cnt;
				//$count = 1;
				if ($count) {
					$answer = "YES";
				}else{
					$answer = $count;
				}
					///$answer = "YES";
                                if(!$count){
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
					)VALUES (
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
                                }
				echo $answer;
			}elseif(isset($_POST['LMI_HASH'])) {
			
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
                /**
                 * Повторная оплата заказа
                 */
                public function powtorPayAction()
                        {
                    //echo print_r($this->post);
                    $form = '';
                    switch ((int)$this->post->payment_sistem){
                                case 4: $paymaster = 21; break;
                                case 6: $paymaster = 49; break;
                            }
                            
                    $order = new Shoporders($this->post->order);
                    if($order->id){
                        $pay_data['LMI_MERCHANT_ID'] = 2285;
			$pay_data['LMI_PAYMENT_AMOUNT'] =  $order->calculateOrderPrice2(true, false);//str_replace(" ","",$order_amount);
			$pay_data['LMI_PAYMENT_NO'] = $order->id;
			$pay_data['LMI_PAYMENT_DESC'] = 'Оплата за заказ № '.$order->id;
		
			$pay_data['LMI_PAYMENT_SYSTEM'] = $paymaster;
			$pay_data['LMI_SIM_MODE'] = 1;
			$pay_data['LMI_HASH'] = hash('sha256', $pay_data['LMI_MERCHANT_ID'].$pay_data['LMI_PAYMENT_NO'].$pay_data['LMI_PAYMENT_AMOUNT'].'joiedevivre');
			$pay_data['LMI_HASH'] = strtoupper($pay_data['LMI_HASH']);
                        
                        $LMI_MERCHANT_ID		= mysql_real_escape_string($pay_data['LMI_MERCHANT_ID']);
			$LMI_PAYMENT_AMOUNT		= mysql_real_escape_string($pay_data['LMI_PAYMENT_AMOUNT']);
			$LMI_PAYMENT_NO			= mysql_real_escape_string($pay_data['LMI_PAYMENT_NO']);
			$LMI_PAYMENT_DESC		= mysql_real_escape_string($pay_data['LMI_PAYMENT_DESC']);
			$LMI_PAYMENT_SYSTEM		= mysql_real_escape_string($pay_data['LMI_PAYMENT_SYSTEM']);
			$LMI_SIM_MODE			= mysql_real_escape_string($pay_data['LMI_SIM_MODE']);
			$LMI_HASH			= mysql_real_escape_string($pay_data['LMI_HASH']);
                        
                   
            $sql = "
					SELECT
						count(pay_send.id) cnt
					FROM
						pay_send
					WHERE
						LMI_MERCHANT_ID LIKE '%".$LMI_MERCHANT_ID."%'
						AND LMI_PAYMENT_AMOUNT LIKE '%".(float)$LMI_PAYMENT_AMOUNT."%'
						AND LMI_PAYMENT_NO LIKE '%".$LMI_PAYMENT_NO."%'
						AND LMI_PAYMENT_SYSTEM LIKE '%".$LMI_PAYMENT_SYSTEM."%'
				";
            
	$count = wsActiveRecord::findByQueryFirstArray($sql)['cnt'];        
                    
    if(($order->payment_method_id != (int)$this->post->payment_sistem) or !$count){
            $order->setPaymentMethodId($this->post->payment_sistem);
    wsActiveRecord::query("INSERT INTO pay_send ( LMI_MERCHANT_ID, LMI_PAYMENT_AMOUNT, LMI_PAYMENT_NO, LMI_PAYMENT_DESC, LMI_PAYMENT_SYSTEM, LMI_SIM_MODE, LMI_HASH, pid ) VALUES ( '".$LMI_MERCHANT_ID."', '".$LMI_PAYMENT_AMOUNT."', '".$LMI_PAYMENT_NO."', '".$LMI_PAYMENT_DESC."', '".$LMI_PAYMENT_SYSTEM."', '".$LMI_SIM_MODE."', '".$LMI_HASH."', '".$this->post->payment_sistem."' ) " );
                    }
                    
                    $order->setOpenPay(0);
                    $order->save(); 

                    $form = '<form action="https://lmi.paysoft.solutions" method="POST" name="payment_form" id="payment_form">
<div class="row m-auto">
<div class="col-xs-10 col-xs-offset-1">
<div class="col-xs-6 form-group">
    <input id="LMI_MERCHANT_ID" name="LMI_MERCHANT_ID" type="hidden" required="" placeholder="LMI_MERCHANT_ID" class="form-control" value="'.$pay_data['LMI_MERCHANT_ID'].'">
</div>
<div class="col-xs-6 form-group">
    <input id="LMI_PAYMENT_AMOUNT" name="LMI_PAYMENT_AMOUNT" type="hidden" required="" placeholder="LMI_PAYMENT_AMOUNT" class="form-control" value="'.$pay_data['LMI_PAYMENT_AMOUNT'].'">
</div>
<div class="col-xs-6 form-group">
    <input id="LMI_PAYMENT_NO" name="LMI_PAYMENT_NO" type="hidden" required="" placeholder="LMI_PAYMENT_NO" class="form-control" value="'.$pay_data['LMI_PAYMENT_NO'].'">
    </div>
<div class="col-xs-6 form-group">
    <input id="LMI_PAYMENT_DESC" name="LMI_PAYMENT_DESC" type="hidden" required="" placeholder="LMI_PAYMENT_DESC" class="form-control" value="'.$pay_data['LMI_PAYMENT_DESC'].'">
</div>
<div class="col-xs-6 form-group">
    <input id="LMI_PAYMENT_SYSTEM" name="LMI_PAYMENT_SYSTEM" type="hidden" required="" placeholder="LMI_PAYMENT_SYSTEM" class="form-control" value="'.$pay_data['LMI_PAYMENT_SYSTEM'].'">
</div>
<div class="col-xs-6 form-group">
    <input id="LMI_SIM_MODE" name="LMI_SIM_MODE" type="hidden" required=""  placeholder="LMI_SIM_MODE" class="form-control" value="'.$pay_data['LMI_SIM_MODE'].'">
</div>
<div class="col-xs-6 form-group">
    <input id="LMI_HASH" name="LMI_HASH" type="hidden" required="" placeholder="LMI_HASH" class="form-control" value="'.$pay_data['LMI_HASH'].'">
</div>
<div class="col-xs-12 form-group">
    <button type="submit"   class="btn btn-default d-none">Оплата</button>
</div>
</div>
	</div>
</form>
<script >
	document.forms.payment_form.submit();
</script>
';
                    
                     }
                    echo $form;
                        }
	}
