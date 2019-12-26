<?php
class Trekko{ 

	protected $key = 'ogjlOLSGljlgojkfsd24dfsodflwGOTolnLejroi35lkjl2352ll8lj90KL';
	protected $throwErrors = FALSE;
	protected $client = '134';
	
	protected $obj = 'order';
        
        protected $format = 'array';

function __construct($key, $client, $obj, $throwErrors = true) {
		$this->throwErrors = $throwErrors;
		return $this	
			->setKey($key)
			->setClient($client)
			->setObject($obj);
	}
	function setKey($key) {
		$this->key = $key;
		return $this;
	}
	
	/**
	 * Getter for key property
	 * @return string
	 */
	function getKey() {
		return $this->key;
	}
	//
	function setClient($client) {
		$this->client = $client;
		return $this;
	}
	/**
	 * Getter for Client property
	 * @return string
	 */
	function getClient() {
		return $this->client;
	}
	///
	function setObject($obj) {
		$this->obj = $obj;
		return $this;
	}
	
	/**
	 * Getter for Object property
	 * @return string
	 */
	function getObject() {
		return $this->obj;
	}
	///

	public function getCreateOrder($order){
	$request = array('client' =>$this->client, 'object' => $this->obj, 'method' => 'create','parameters' => array('orders' => array($order)));
	return $this->_requestJSON(json_encode($request));
	}
	public function getCreateMasOrder($orders){
	$request = array('client' => $this->client, 'object' => $this->obj, 'method' => 'create','parameters' => array('orders' => $orders));
      //  echo 'pre';
      //  print_r($request);
       //// echo '</pre>';
       // return true;
	return $this->_requestJSON(json_encode($request));
	}
	
	public function getStatusTrekko($order){
	$request = array(
	'client' => $this->client,
	'object' => $this->obj,
	'method' => 'status',
	'parameters' => array(
	'orders' => array($order)
	)
	);
	return  $this->_requestJSON(json_encode($request));
	}
	public function getLoadingTrekko($type = 1){
	$request = array('client' => $this->client, 'object' => $this->obj, 'method' => 'loading','parameters' => array('date' => date('d.m.Y'), 'type' => '2', 'active' => $type, 'comment' => 'Забор до 18:00'));
	return $this->_requestJSON(json_encode($request));
	}
	
	private function prepare($data) {
		//Returns array
		if ($this->format == 'array') {
			$result = is_array($data) ? $data : json_decode($data);
			
			if ($this->throwErrors AND $result['errors']){
                            throw new \Exception(is_array($result['errors']) ? implode("\n", $result['errors']) : $result['errors']);
                            
                        }
			return $result;
		}
		// Returns json or xml document
		return $data;
	}
        
        public function getTestTrekko(){
            $request = array(
	'client' => $this->client,
	'object' => 'order',
	'method' => 'create',
	'parameters' => array(
		'orders' => array(
			array(
				'product_order' => '12345-125', //Ваш внутрений номер заказа (уникальный в текущем году) - ОБЯЗАТЕЛЬНОЕ ПОЛЕ
				'contact' => 'ФИО',
				'tel' => '+380987654321',
				'city' => 'Киев',
				'adress' => 'ТЕСТ ТЕСТ ТЕСТ', // ОБЯЗАТЕЛЬНОЕ ПОЛЕ
				'time' => 'ТЕСТ ТЕСТ ТЕСТ',
				'delivery_date' => '17.07.2019', //дата доставки
				'naimenovanie' => 'Описание',
				'summa_oplaty' => '200',
				'summa_strahovki' => '50',
				'ves' => '2.5',
				'mest' => '1',
				'primechanie' => 'ТЕСТ ТЕСТ ТЕСТ',
				'id_service' => '2', //вид услуги: 1-курьерская доставка, 2-отправка почтой, 4-самовывоз
				'id_status' => '1', //статус заказа: 0-"Забор", 1-"На станции"
				'TypesOfPayers' => 'Recipient', //(для услуги "Отправка почты") кто оплачивает доставку: "Recipient"-получатель, "Sender"-отправитель
				'BackwardDelivery' => 'Sender' //(для услуги "Отправка почты") кто оплачивает наложку: "Recipient"-получатель, "Sender"-отправитель, "No"-нет
			)
		)
	)
);
	return $this->_requestJSON(json_encode($request));
        }
	 private function _requestJSON($json) {
	 
	 $post = [
	'request' => $json,
	'sign' => md5($json.$this->key)
             ];
         
$ch = curl_init('https://portal.trekko.com.ua/api.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$result = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if($err){
  return json_decode($err);
}else{
return json_decode($result);
}
	 }


}