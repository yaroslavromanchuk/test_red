<?php 
class LiqPayController extends controllerAbstract{

	private $version = 3;
    private $public_key = 'i51858842721';
	private $action = 'pay';
	
	private $currency = 'UAH';
	private $private_key = 'r7A8olggiLS7OEZs4xAioTg2SZI4w6q6mdWFU9kT';
	private $checkout_url = 'https://www.liqpay.ua/api/3/checkout';
	


    private function calculateSignature($data, $private_key)
    {
        return base64_encode(sha1($private_key . $data . $private_key, true));
    }

    public function callbackAction()
    {
        $data = $this->post['data'];
        $private_key = $this->private_key;
        $signature = $this->calculateSignature($data, $private_key);
        $parsed_data = json_decode(base64_decode($data), true);
        $order_id = $parsed_data['order_id'];
		

		
			
        if ($signature == $this->post['signature']){
		$status = wsActiveRecord::useStatic('LiqPayStatus')->findFirst(array(" code LIKE  '".$parsed_data['status']."' "));
		 $order = new Shoporders((int)$order_id);
		 $order->setLiqpayStatusId($status->getId());
		 $order->setLiqpayPaytype($parsed_data['paytype']);
		 $order->save();
		 
		 LiqPayHistory::newHistory((int)$order_id, $status->getId(), $parsed_data['err_description']);
		 
		//echo print_r($parsed_data);
		
           // $this->load->model('checkout/order');
           // $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('liqpay_checkout_order_status_id'));
            //here you can update your order status
        }
		//die();
    }

public function indexAction(){

	$this->pay();
	echo $this->render('liqpay/ordersucces.tpl.php');
	}
	
	public function successAction(){
	if (count($_POST)) {
	$data = $this->post['data'];
        $private_key = $this->private_key;
        $signature = $this->calculateSignature($data, $private_key);
        $parsed_data = json_decode(base64_decode($data), true);
        $order_id = $parsed_data['order_id'];
		 if ($signature == $this->post['signature']){
		 $status = wsActiveRecord::useStatic('LiqPayStatus')->findFirst(array(" code LIKE  '".$parsed_data['status']."' "));
		 $order = new Shoporders((int)$order_id);
		 $order->setLiqpayStatusId($status->getId());
		 $order->setLiqpayPaytype($parsed_data['paytype']);
		 $order->save();
		 
		 LiqPayHistory::newHistory((int)$order_id, $status->getId(), $parsed_data['err_description']);
		 $parsed_data['status_d'] = $status->getName();
		 $this->view->test = $parsed_data;
		 }
}

	
	echo $this->render('liqpay/success.tpl.php');
	}
	
	public function pay(){
	$id = 338900;
	$amount = 10;
        //$this->load->language('languages/payment/liqpay_checkout');
		
       
		
        $order_id = $id;
		 $data['button_confirm'] = 'Оплатить '.$amount.' грн.';
       // $this->load->model('checkout/order');
       // $order_info = $this->model_checkout_order->getOrder($order_id);

        // Collect info about the order to be sent to the API

        $description = 'Заказ №' . $order_id;
		
		
       $result_url = 'https://www.red.ua/liqpay-success';//$this->url->link('liqpay/success', '', 'SSL');
		
        $server_url = 'https://www.red.ua/liqpay-callback';//$this->url->link('extension/payment/liqpay_checkout/callback', '', 'SSL');


        $amount = $amount; 
        $send_data = array(
			'version' => $this->version,
            'public_key' => $this->public_key,
            'amount' => $amount,
            'currency' => $this->currency,
            'description' => $description,
            'order_id' => $order_id,
            'action' => $this->action,
            'language' => $_SESSION['lang'],
            'server_url' => $server_url,
            'result_url' => $result_url,
			'sandbox' => 1
			);


        $liqpay_data = base64_encode(json_encode($send_data));
        $liqpay_signature = $this->calculateSignature($liqpay_data, $this->private_key);

        $data['data'] = $liqpay_data;
        $data['signature'] = $liqpay_signature;
        $data['action'] = $this->checkout_url;
		
		$this->view->data = $data;
       return $this->view->form = $this->render('liqpay/index.tpl.php');

       // return $this->load->view($view_path, $data);
    }
	


}

?>