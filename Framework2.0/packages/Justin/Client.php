<?php



class Client {
    
    protected $timeout = 60;
    protected $connect_timeout = 60;
    protected $url = 'https://api.justin.ua';

    public function __construct($timeout = 60, $connect_timeout = 60)
    {
        return $this
            ->setTimeout($timeout)
            ->setConnect($connect_timeout);
    }
    public function setTimeout($timeout)
    {

        $this->timeout = $timeout;

        return $this;

    }
    public function setConnect($connect_timeout)
    {

        $this->connect_timeout = $connect_timeout;

        return $this;

    }
    
    public function post($url, $p = []){
        echo $url.'<br>';
        print_r($p);
        $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $p,
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic cmVkVUE6VEVkbUF0SWM=",
    "Postman-Token: c493f151-0cc7-45d3-a444-62514f7d701a",
    "cache-control: no-cache"
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
    public function get($url){
          $curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => $url,
CURLOPT_RETURNTRANSFER => True,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
));
$result = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if($err) { 
	return $err;
}else{
	return $result;
}
    }
}
