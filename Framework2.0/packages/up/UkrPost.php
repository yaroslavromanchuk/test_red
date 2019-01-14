<?php
class UkrPost{
//protected $guid = '1';
//protected $language = 'uk';
//protected $connectionType = 'curl';
//protected $params;

/*function __construct($guid, $language = 'uk') {
		//$this->throwErrors = $throwErrors;
		return $this	
			->setGuid($guid)
			->setLanguage($language)
			->setConnectionType($connectionType);
	}*/
	
	public function Treck($barcode){
	$url = 'http://services.ukrposhta.com/barcodestatistic/barcodestatistic.asmx?wsdl';
	 $uri = 'http://barcode.org/';
	 $servis = 'BarcodeStatistic';
	 $tochka = 'BarcodeStatisticSoap';
	 $guid = 'fcc8d9e1-b6f9-438f-9ac8-b67ab44391dd';
	 $lang = 'uk';
	 $client = new SoapClient($url);
	 $request = new stdClass($uri, $servis, $tochka);
	 $result = $request->GetBarcodeInfo($guid, $barcode, $lang)->GetBarcodeInfoResult->eventdescription;
	
	}
	public function requestQuery($barcode) {
        // формируем подпись
       // $sign = md5($this->_login.$this->_password.$function.$where.$order);


$xml = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetBarcodeInfo xmlns="http://barcode.org/">
      <guid>1</guid>
      <barcode>'.$barcode.'</barcode>
      <culture>uk</culture>
    </GetBarcodeInfo>
  </soap:Body>
</soap:Envelope>';

        // отправляем запрос
        return $this->_requestXML($xml, 'http://services.ukrposhta.com');
    }
	private function _requestXML($xmlString, $url) {
	//wsLog::add($xmlString, 'MEEST_XML');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlString);
        curl_setopt($ch, CURLOPT_POST, '/barcodestatistic/barcodestatistic.asmx ');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        if (!$response) {
            //throw new MeestExpress_Exception('Empty response');
        }

        $xml = simplexml_load_string($response);

        $resultCode = trim($xml->errors->code.'');
        //$resultMessage = trim($xml->errors->name.'');

        if ($resultCode) {
            return $xml;
        }

        //throw new MeestExpress_Exception($resultMessage, $resultCode);
    }
	




}



 ?>