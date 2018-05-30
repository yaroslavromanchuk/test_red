<?php
class Trekko{ 

	protected $key = 'ogjlOLSGljlgojkfsd24dfsodflwGOTolnLejroi35lkjl2352ll8lj90KL';
	
	protected $client = '134';
	
	protected $obj = 'order';

function __construct($key, $client, $obj) {
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
	
	
	
	 private function _requestJSON($json) {
	 
	 $post = array(
	'request' => $json,
	'sign' => md5($json.$this->key)
);
$ch = curl_init('http://portal.trekko.com.ua/api.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$result = curl_exec($ch);
curl_close($ch);
//return $result;
	return json_decode($result);
	 }


}