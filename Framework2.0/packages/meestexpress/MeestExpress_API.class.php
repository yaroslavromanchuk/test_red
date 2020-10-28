<?php
/**
 * API Meest Express
 *
 * @author    Yaroslav Romanchuk <php@red.ua>
 * @copyright RED
 * @package   MeestExpress
 */
class MeestExpress_API { 

    public function __construct($login, $password, $clientUID, $language = 'ru') {
        $this->_login = $login;
        $this->_password = $password;
        $this->_clientUID = $clientUID;
        $this->_language = strtoupper($language);
    }
	//перещет стоимости доставки
	public function calculateShipments($type, $bratch, $city, $cost, $strach, $masa, $paket) {
        $request = '
        <CalculateShipment>
		<ClientUID>35a4863e-13bb-11e7-80fc-1c98ec135263</ClientUID>
		<SenderService>1</SenderService>
		<SenderCity_UID>5CB61671-749B-11DF-B112-00215AEE3EBE</SenderCity_UID>

		<ReceiverService>'.$type.'</ReceiverService>';
		if($type == 0) {
		$request .='<ReceiverBranch_UID>'.$bratch.'<ReceiverBranch_UID/>';
		}else if($type == 1){
		$request .='
		<ReceiverCity_UID>'.$city.'</ReceiverCity_UID>';
		}
		
		$request .='
		<COD>'.$cost.'</COD>
		
		<Conditions_items>
		<ContitionsName></ContitionsName>
		</Conditions_items>
		
		<Places_items>
<SendingFormat>'.$paket.'</SendingFormat>
<Quantity>1</Quantity>
<Weight>'.$masa.'</Weight>';
if($paket == 'PAX'){
$request .='<Length>36</Length>
<Width>20</Width>
<Height>20</Height>';
}else {
$request .='<Length>25</Length>
<Width>20</Width>
<Height>20</Height>';
}
$request .='
<Packaging/>
<Insurance>'.$strach.'</Insurance>
</Places_items>
</CalculateShipment>';

        $result = $this->_requestDocument(
            'CalculateShipments',
            $request,
            '', // requestID не передаем при wait=1
            1 // wait=1
        );

     return  $result->result_table->items->PriceOfDelivery;
    }
	//exit calc
	
	
	
	
	 
//'.$this->_xmlEscape($shipment->getOrderID()).'
    public function createShipment(MeestExpress_Shipment $shipment) {
        $request = '<Shipments>
        <CreateShipment>
        <ClientsShipmentRef>'.$this->_xmlEscape($shipment->getOrderID()).'</ClientsShipmentRef>
        <ClientUID>35a4863e-13bb-11e7-80fc-1c98ec135263</ClientUID>

        <Sender>Интернет магазин РЕД</Sender>
        <SenderService>1</SenderService>
        <SenderStreet_UID>681D4708-E0D2-11DF-9B37-00215AEE3EBE</SenderStreet_UID>
        <SenderHouse>31</SenderHouse>
        <SenderFlat>1</SenderFlat>
        <SenderTel>380930510920</SenderTel>

        <Receiver>'.$this->_xmlEscape($shipment->getReceiverName()).'</Receiver>
        <ReceiverService>'.$shipment->getReceiverService().'</ReceiverService>';
		if($shipment->getReceiverService() == 0 ){$request .= '
        <ReceiverBranch_UID>'.$shipment->getReceiverBranchUID().'</ReceiverBranch_UID>';}
		if($shipment->getReceiverService() == 1 ){$request .= '
        <ReceiverStreet_UID>'.$shipment->getReceiverStreetUID().'</ReceiverStreet_UID>';}
		
		$request .= '
        <ReceiverHouse>'.$this->_xmlEscape($shipment->getReceiverHouse()).'</ReceiverHouse>
        <ReceiverFlat>'.$this->_xmlEscape($shipment->getReceiverFlat()).'</ReceiverFlat>
        <ReceiverFloor>'.$this->_xmlEscape($shipment->getReceiverFloor()).'</ReceiverFloor>
        <ReceiverTel>'.$this->_xmlEscape($shipment->getReceiverPhone()).'</ReceiverTel>
		<COD>'.$this->_xmlEscape($shipment->getCod()).'</COD>
        <Notation>'.$this->_xmlEscape($shipment->getNotation()).'</Notation>
        <Receiver_Pay>1</Receiver_Pay>
        <TypePay>1</TypePay>

        <Places_items>
            <SendingFormat>'.$this->_xmlEscape($shipment->getSendingFormat()).'</SendingFormat>
            <Quantity>1</Quantity>
            <Weight>'.$this->_xmlEscape($shipment->getSendingWeight()).'</Weight>
            <Insurance>'.$this->_xmlEscape($shipment->getSendingInsurance()).'</Insurance>
        </Places_items>
        ';

        if ($shipment->getDeliveryDate()) {
            $date = date('d.m.Y', strtotime($shipment->getDeliveryDate()));
            $request .= '<PlanDeliveryDate>'.$date.'</PlanDeliveryDate>';
        }
			if($shipment->getFrom()){$request .= '<PlanDeliveryTimeFrom>'.$shipment->getFrom().'</PlanDeliveryTimeFrom>';}
			if($shipment->getTo()){$request .= '<PlanDeliveryTimeTo>'.$shipment->getTo().'</PlanDeliveryTimeTo>';}


        $request .= '</CreateShipment>
        </Shipments>';

        $result = $this->_requestDocument(
            'CreateShipments',
            $request,
            '', // requestID не передаем при wait=1
            1 // wait=1
        );

        return trim($result->result_table->items->ClientsShipmentRef.'');
    }

    public function createRegister($shipment) {
        $request = '<CreateRegister>
        <ClientUID>35a4863e-13bb-11e7-80fc-1c98ec135263</ClientUID>
        <Shipments>';
		
		foreach ($shipment as $c) {
		$request .='<ClientsShipmentRef>'.$c.'</ClientsShipmentRef>';
			}
			
        $request.=' </Shipments>
        </CreateRegister>';
        $result = $this->_requestDocument(
            'CreateRegister',
            $request,
            '', // requestID не передаем при wait=1
            1 // wait=1
        );

        return trim($result->result_table->items->RegisterUID.'');
    }
	// заявка на вызов курьера
	public function createPickUp($shipment) {
$request = '<CreatePickUp>
<ClientUID>35a4863e-13bb-11e7-80fc-1c98ec135263</ClientUID>
        <Sender>Интернет магазин РЕД</Sender>
        <SenderService>1</SenderService>
        <SenderStreet_UID>681D4708-E0D2-11DF-9B37-00215AEE3EBE</SenderStreet_UID>
        <SenderHouse>31</SenderHouse>
        <SenderFlat>1</SenderFlat>
        <SenderTel>380930510920</SenderTel>
		
<Receiver_Pay>1</Receiver_Pay>
<TypePay>1</TypePay>
<Notes>RED</Notes>
<PickUpDate>'.date('d.m.Y').'</PickUpDate>
<PickUpTimeFrom>14:00</PickUpTimeFrom>
<PickUpTimeTo>17:00</PickUpTimeTo>
<Shipments>';

foreach ($shipment as $c) {
		$request .='<ClientsShipmentRef>'.$c.'</ClientsShipmentRef>';
			}

	$request .= '</Shipments>
</CreatePickUp>';

 $result = $this->_requestDocument(
            'CreatePickUp',
            $request,
            '', // requestID не передаем при wait=1
            1 // wait=1
        );
		
		return trim($result->result_table->items->PickUpNumber.'');

	}
	public function getDeliveryDays($city){
	$request = '<GetDeliveryDays>
	<ClientUID>35a4863e-13bb-11e7-80fc-1c98ec135263</ClientUID>
	<SenderCity_UID>5CB61671-749B-11DF-B112-00215AEE3EBE</SenderCity_UID>
	<ReceiverCity_UID>'.$city.'</ReceiverCity_UID>
	</GetDeliveryDays>';
	
	 $result = $this->_requestDocument(
            'GetDeliveryDays',
            $request,
            '', // requestID не передаем при wait=1
            1 // wait=1
        );
		
		return $result->result_table->items->DelivetyDate;
	
	}

    public function shipmentTracking($orderID) {
        $orderID = addslashes($orderID);

        $result = $this->_requestQuery(
            'ShipmentTracking',
            "ClientUID='{$this->_clientUID}' AND ClientShipmentRef = '{$orderID}'",
            ''
        );

          return  $result->result_table->items;
    }
//vse strany
	 public function getCountry() {
        $result = $this->_requestQuery('Country','','');

        return $result->result_table->items;
    }
	
	// konkretnay strana
    public function getCountryUID($country) {
        $country = addslashes($country);

        $result = $this->_requestQuery(
            'Country',
            "Description{$this->_language} LIKE '{$country}'",
            ''
        );

        if (isset($result->result_table->items[1])) {
            throw new MeestExpress_Exception('Too many results for '.$country);
        }

        if (!isset($result->result_table->items[0])) {
            throw new MeestExpress_Exception('No result for '.$country);
        }

        return trim($result->result_table->items->uuid.'');
    }
	
		 public function getCity($city, $countryUID) {
        $result = $this->_requestQuery('City',"Description{$this->_language} LIKE '{$city}%' AND Countryuuid='{$countryUID}'",'');
        return $result->result_table->items;
    }
	
	 public function getCityAll($countryUID) {
        $result = $this->_requestQuery('City',"Description{$this->_language} LIKE 'Киев%' AND Countryuuid='{$countryUID}'",'');
        return $result->result_table->items;
    }
//konkretny gorod
    public function getCityUID($city, $countryUID) {
        $city = addslashes($city);

        $result = $this->_requestQuery(
            'City',
            "Description{$this->_language} LIKE '{$city}' AND Countryuuid='{$countryUID}'",
            ''
        );

        if (isset($result->result_table->items[1])) {
            throw new MeestExpress_Exception('Too many results for '.$city);
        }

        if (!isset($result->result_table->items[0])) {
            throw new MeestExpress_Exception('No result for '.$city);
        }

        return trim($result->result_table->items->uuid.'');
    }
	public function getBranch($cityUID){
	
	$result = $this->_requestQuery(
            'Branch',
            "DescriptionRU NOT LIKE '%АПТ%' and Cityuuid='{$cityUID}'",
            ''
        );
		
	return $result->result_table->items;
	}

    public function getSteetUID($street, $cityUID) {
        $street = addslashes($street);

        $result = $this->_requestQuery(
            'Address',
            "Description{$this->_language} LIKE '{$street}' AND Cityuuid='{$cityUID}'",
            ''
        );

        /*if (isset($result->result_table->items[1])) {
            throw new MeestExpress_Exception('Too many results for '.$street);
        }*/

        if (!isset($result->result_table->items[0])) {
            throw new MeestExpress_Exception('No result for '.$street);
        }

        return trim($result->result_table->items->uuid.'');
    }
		 public function getStreet($street, $cityUID) {
        $result = $this->_requestQuery('Address',"Description{$this->_language} LIKE '%{$street}%' AND Cityuuid='{$cityUID}'",'');
        return $result->result_table->items;
    }
		 public function getStreetAll() {

        $result = $this->_requestQuery(
		    'Address',"Cityuuid = '5CB61671-749B-11DF-B112-00215AEE3EBE'",''
			);
        return $result->result_table;
    }

    public function formatStreetMeest($string) {
        $string = preg_replace('/Аллея|Аллея\,|Аллея\.|Аллея\-/ius', '', $string);
        $string = preg_replace(
            '/Бульвар|Бульвар\,|Бульвар\.|Бульвар\-|бул\s|бул\.|бул\,|б-р|б-р\,|б-р\./ius',
            '',
            $string
        );
        $string = preg_replace('/Вал\s|Вал\,|Вал\.|Вал\-/ius', '', $string);
        $string = preg_replace('/Взвоз\s|Взвоз\,|Взвоз\.|Взвоз\-/ius', '', $string);
        $string = preg_replace('/Въезд\s|Въезд\,|Въезд\.|Въезд\-/ius', '', $string);
        $string = preg_replace('/Дорога\s|Дорога\,|Дорога\.|Дорога\-|дор\s|дор\,|дор\./ius', '', $string);
        $string = preg_replace('/Заезд\s|Заезд\,|Заезд\.|Заезд\-/ius', '', $string);
        $string = preg_replace('/Кольцо\s|Кольцо\,|Кольцо\.|Кольцо\-/ius', '', $string);
        $string = preg_replace('/Линия\s|Линия\,|Линия\.|Линия\-/ius', '', $string);
        $string = preg_replace('/линнея\s|линнея\,|линнея\.|линнея\-/ius', '', $string);
        $string = preg_replace('/Луч\s|Луч\,|Луч\.|Луч\-/ius', '', $string);
        $string = preg_replace(
            '/Магистраль\s|Магистраль\,|Магистраль\.|Магистраль\-|маг\s|маг\,|маг\./ius',
            '',
            $string
        );
        $string = preg_replace('/Переулок\s|Переулок\,|Переулок\.|Переулок\-|пер\s|пер\,|пер\./ius', '', $string);
        $string = preg_replace('/Площадь\s|Площадь\,|Площадь\.|Площадь\-|пл\s|пл\,|пл\./ius', '', $string);
        $string = preg_replace(
            '/Проезд\s|Проезд\,|Проезд\.|Проезд\-|пр-д\s|пр-д\.|пр-д\,|пр\s|пр\,|пр\./ius',
            '',
            $string
        );
        $string = preg_replace(
            '/Проспект\s|Проспект\,|Проспект\.|Проспект\-|просп\s|просп\.|просп\,|пр-кт\s|пр-кт\,|пр-кт\./ius',
            '',
            $string
        );
        $string = preg_replace('/Проулок\s|Проулок\,|Проулок\.|Проулок\-/ius', '', $string);
        $string = preg_replace('/Разъезд\s|Разъезд\,|Разъезд\.|Разъезд\-/ius', '', $string);
        $string = preg_replace('/Ряд\s|Ряд\,|Ряд\.|Ряд\-/ius', '', $string);
        $string = preg_replace('/Спуск\s|Спуск\,|Спуск\.|Спуск\-/ius', '', $string);
        $string = preg_replace('/Съезд\s|Съезд\,|Съезд\.|Съезд\-/ius', '', $string);
        $string = preg_replace('/Территория\s|Территория\,|Территория\.|Территория\-/ius', '', $string);
        $string = preg_replace('/Тракт\s|Тракт\,|Тракт\.|Тракт\-/ius', '', $string);
        $string = preg_replace('/Тупик\s|Тупик\,|Тупик\.|Тупик\-|туп\s|туп\,|туп\./ius', '', $string);
        $string = preg_replace('/Вулиця\s|Вулиця\,|Вулиця\.|Вулиця\-|вул\s|вул\,|вул\./ius', '', $string);
        $string = preg_replace('/Улица\s|Улица\,|Улица\.|Улица\-|ул\s|ул\,|ул\./ius', '', $string);
        $string = preg_replace('/Шоссе\s|Шоссе\,|Шоссе\.|Шоссе\-|ш\s|ш\,|ш\./ius', '', $string);

        return trim($string);
    }

    private function _requestDocument($function, $request, $requestID, $wait) {
        // формируем подпись
        $sign = md5($this->_login.$this->_password.$function.$request.$requestID.$wait);

        // формируем XML
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <param>
        <login>'.$this->_login.'</login>
        <function>'.$function.'</function>
        <request>'.$request.'</request>
        <request_id>'.$requestID.'</request_id>
        <wait>'.$wait.'</wait>
        <sign>'.$sign.'</sign>
        </param>';

        // отправляем запрос
        return $this->_requestXML($xml, 'http://api1c.meest-group.com/services/1C_Document.php');
    }

    private function _requestQuery($function, $where, $order) {
        // формируем подпись
        $sign = md5($this->_login.$this->_password.$function.$where.$order);

        // формируем XML
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <param>
        <login>' . $this->_login .'</login>
        <function>' . $function .'</function>
        <where>' . $where .'</where>
        <order>' . $order .'</order>
        <sign>' . $sign .'</sign>
        </param>';

        // отправляем запрос
        return $this->_requestXML($xml, 'http://api1c.meest-group.com/services/1C_Query.php');
    }

    private function _requestXML($xmlString, $url) {
	//wsLog::add($xmlString, 'MEEST_XML');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlString);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if (!$response) {
           
            throw new MeestExpress_Exception($error);
        }

        $xml = simplexml_load_string($response);

        $resultCode = trim($xml->errors->code.'');
        $resultMessage = trim($xml->errors->name.'');

        if ($resultCode == '000') {
            return $xml;
        }

        throw new MeestExpress_Exception($resultMessage, $resultCode);
    }

    private function _xmlEscape($string) {
        return htmlspecialchars($string);
    }

    private $_login;

    private $_password;

    private $_clientUID;

    private $_language;

}