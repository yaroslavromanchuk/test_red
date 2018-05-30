<?php 
class UkrPostAPI{
	protected $BEARER ='45929ca4-c122-3d2f-9522-ba47f85909ec';
	protected $TOKEN = 'ba5378df-985e-49c5-9cf3-d222fa60aa68';
	protected $UUID  =  '2304bbe5-015c-44f6-a5bf-3e750d753a17';
	protected $throwErrors = FALSE;
	protected $type = 'POST';
	
	/**
	 * @var string $format Format of returned data - array, json, xml
	 */
	protected $format = 'array';

	function __construct($BEARER, $TOKEN, $UUID, $type, $throwErrors = FALSE) {
		$this->throwErrors = $throwErrors;
		return $this	
			->setBearer($BEARER)
			->setToken($TOKEN)
			->setUuid($UUID)
			->setType($type);
	}
	function setBearer($BEARER) {
		$this->bearer = $BEARER;
		return $this;
	}
	function getBearer() {
		return $this->bearer;
	}
	function setType($type) {
		$this->type = $type;
		return $this;
	}
	function getType() {
		return $this->type;
	}
	
	function setToken($TOKEN) {
		$this->token = $TOKEN;
		return $this;
	}
	function getToken() {
		return $this->token;
	}
	
	function setUuid($UUID) {
		$this->uuid = $UUID;
		return $this;
	}
	function getUuid() {
		return $this->uuid;
	}
	
	private function prepare($data) {
		//Returns array
		if ($this->format == 'array') {
			$result = is_array($data)
				? $data
				: json_decode($data, 1);
			// If error exists, throw Exception
			//if ($this->throwErrors AND $result['errors'])
			//	throw new \Exception(is_array($result['errors']) ? implode("\n", $result['errors']) : $result['errors']);
			return $result;
		}
		// Returns json or xml document
		return $data;
	}
	
	public function getInfo(){
	$request = array(
"postcode"=>"07401",
"country"=> "UA",
"region"=>"Київська",
"city"=>"Бровари",
"district"=>"Київський",
"street"=>"Котляревського",
"houseNumber"=>"12",
"apartmentNumber"=>"33",
	);

	$model = '/api-docs?token='.$this->token;
	  return $this->request('GET', $model);
	}
	
	public function getNewClient(){
	
	$request = array(
	"addressId"=> 1,
  "bankAccount"=> "123456",
  "bankCode"=> "123456",
  "contactPersonName"=> "Ivan Ivanovich Ivanov",
  "counterpartyUuid"=> "2304bbe5-015c-44f6-a5bf-3e750d753a17",
  "edrpou"=> "string",
  "email"=> "definitely@real.mail",
  "externalId"=> "1234567890",
  "firstName"=> "Ivan",
  "firstNameEn"=> "John",
  "individual"=> false,
  "lastName"=> "Ivanov",
  "lastNameEn"=> "Smith",
  "middleName"=> "Ivanovich",
  "name"=> "FOP Petrov",
  "nameEn"=> "John Smith",
  "phoneNumber"=> "+30987654321",
  "postId"=> "string",
  "resident"=> false,
  "tin"=> "string",
  "type"=> "INDIVIDUAL"
	);
	$t = array(
"name"=> "TOV BANK",
"uniqueRegistrationNumber"=> "0035",
"addressId"=> 56922,
"phoneNumber"=> "0671231234",
"bankCode"=> "123000",
"bankAccount"=> "111000222000",
"resident"=> true,
"edrpou"=> "20053145",
"email"=> "test@test.com",
);
	$model = 'clients';
	 return $this->request('POST',$model, $request);
	}
	
	public function getBarcode($b = ''){
	

	$model = 'shipments/barcode/'.$b;
	 return $this->request('GET', $model);
	}
	
	private function request($type = 'GET', $model = 'doc', $param = ''){
//return json_encode($param);
		// Get required URL
$url = 'https://www.ukrposhta.ua/ecom/0.0.1/'.$model.'?token='.$this->token;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => $type,
  CURLOPT_POSTFIELDS => json_encode($param),
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer f9027fbb-cf33-3e11-84bb-5484491e2c94",
    "Cache-Control: no-cache",
    "Content-Type: application/json",
    "Postman-Token: 722b18d4-5b0f-47a7-a71d-c8cb048b9f2f"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

}
		
		
	
	
	

}

?>