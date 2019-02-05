<?php 
/**
 * 
 */
class UkrPostAPI{
	protected $BEARER = '957aaecc-ed29-3c01-944f-0407dbd795ef';
	protected $TOKEN = '38c9400b-71c2-4acb-91db-a338c3030e90';
	protected $UUID  =  'ce4e19d6-6a4f-4f1f-a8c4-5b4a1f9cde9a';
	protected $throwErrors = FALSE;
	protected $type = 'POST';
        protected $sender_uuid = 'a3f4232c-0a5b-4cb2-8d06-2ce18dfcbd60';
        protected $senderAddressId = '15889152';
	
	/**
	 * @var string $format Format of returned data - array, json, xml
	 */
	protected $format = 'array';
        /**
         * 
         * @param type $BEARER
         * @param type $TOKEN
         * @param type $UUID
         * @param type $type
         * @param type $throwErrors
         * @return type
         */
	function __construct($BEARER = '957aaecc-ed29-3c01-944f-0407dbd795ef', $TOKEN = '38c9400b-71c2-4acb-91db-a338c3030e90', $UUID = 'ce4e19d6-6a4f-4f1f-a8c4-5b4a1f9cde9a', $type = 'POST', $throwErrors = FALSE) {
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
			$result = is_array($data) ? $data : json_decode($data);
			
			//if ($this->throwErrors AND $result['errors'])
				//throw new \Exception(is_array($result['errors']) ? implode("\n", $result['errors']) : $result['errors']);
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

	$model = '/api-docs';
	  return $this->request('GET', $model);
	}
        /**
         * 
         * @param type $postcode - индекс
         * @param type $region - область
         * @param type $district - район
         * @param type $city - город
         * @param type $street - улица
         * @param type $houseNumber - номер дома
         * @param type $externalId  - id клиента в базе
         * @return type
         */
        public function newAddress($postcode = 0, $region = '', $district = '', $city = '', $street = '', $houseNumber = '',$apartmentNumber = '', $externalId = ''){
            $param = [
                'postcode' => $postcode,
                'region' => $region,
                'district' => $district,
                'city' => $city,
                'street' => $street,
                'houseNumber' => $houseNumber,
                'apartmentNumber' => $apartmentNumber
            ];
           $model = 'addresses';
           $result = $this->request('POST', $model, $param); 
           if($result->id){
               $adr = [
        'ids' => $result->id,
        'postcode' => $result->postcode,
        'country' => $result->country,
        'region' => $result->region,
        'city' => $result->city,
        'district' => (string)$result->district,
        'street' => (string)$result->street,
       'house_number' => (string)$result->houseNumber,
        'apartment_number' => (string)$result->apartmentNumbe,
        'description' => (string)$result->description,
        'countryside' => $result->countryside,
        'detailed_info' => $result->detailedInfo,
        'clients_id' => $externalId,
        'ctime' => date('Y-m-d H:i:s')
    ];
        $adrr = new UkrPostAddress($result->id);
        $adrr->import($adr);
        $adrr->save();
           }
	 return $result;
        }
        /**
         * Отримання адреси по id
         * @param type $id - адреса
         * @return type
         */
        public function getAddress($id = ''){
            if($id){
                 $model = 'addresses/'.$id;
            }else{
                 $model = 'addresses';
            }
           
	 return $this->request('GET', $model); 
            
        }
        /**
         * отримання всіх адрес клієнта 
         * @param type $uuid - клієнта
         * @return type
         */
        public function getClientAddress($uuid = ''){
            $param = '&clientUuid='.$uuid ;
            $model = 'client-addresses';
         return  $this->prints('GET', $model, $param); 
        }
        /**
         * 
         * @param type $type - Тип клієнта.INDIVIDUAL – фізична особа  COMPANY – юридична особа  PRIVATE_ENTREPRENEUR – фізична особа підприємець.  За замовчуванням встановлено тип COMPANY. Тип клієнта неможливо змінити. 
         * @param type $name - Ім’я клієнта (не більше 60 символів, є обов’язковим для юридичної особи та фізичної особи підприємця. Для фізичної особи формується з параметрів: firstName, middleName, lastName). В поле неможливо ввести спецсимволи, більше 6 цифр підряд, а також абревіатури (МФО, ЄДРПОУ, ЕГРПОУ, ІПН, ИНН, р/р, р\р – оскільки для них використовуються окремі поля) 
         * @param type $lastName - Прізвище фізичної особи (від 2 до 250 символів)
         * @param type $firstName - Ім’я фізичної особи (від 2 до 250 символів)
         * @param type $middleName -  По батькові фізичної особи (від 2 до 250 символів) 
         * @param type $uniqueRegistrationNumber - Унікальний реєстраційний номер 
         * @param type $addressId - Ідентифікатор адреси, вказується Id попередньо створеної адреси 
         * @param type $phoneNumber - Телефонний номер клієнта (тільки цифри, не більше 25 символів)
         * @param type $bankCode - Код МФО клієнта (тільки цифри, не більше 6 символів). Вказуються тільки діючі банки. 
         * @param type $bankAccount - Розрахунковий рахунок (тільки цифри, від 6 до 11 символів), виконується перевірка на валідність.
         * @param type $counterpartyUuid - Ідентифікатор контрагента, що створив даного клієнта. Цей uuid використовується для клієнта, відправлення, а також вказується при формуванні документів на відправлення. 
         * @param type $email - Електрона пошта клієнта
         * @param type $postId - Унікальний ідентифікатор клієнта, що надається ПАТ «Укрпошта»
         * @param type $tin - Індивідуальний податковий номер для фізичних осіб та фізичних осіб підприємців (тільки цифри, для фізичних осіб 10, для фізичних осіб підприємців 12 символів). 
         * @param type $postcode - індекс укрпочти
         * @param type $region - область
         * @param type $city - населений пункт
         * @param type $district - район
         * @param type $street - вулиця
         * @param type $houseNumber - номер будинку
         * @param type $apartmentNumber - номер квартири
         * @param type $externalId - ид в системе
         * @return type
         */
public function getNewClientAdmin($type = 'INDIVIDUAL', $name = '', $lastName = '', $firstName = '', $middleName = '',  $uniqueRegistrationNumber = '',  $phoneNumber = 0, $bankCode = '', $bankAccount = '', $counterpartyUuid = 'ce4e19d6-6a4f-4f1f-a8c4-5b4a1f9cde9a', $email = '', $postId = '', $tin = '', $postcode = '', $region = '', $city = '', $district = '', $street = '', $houseNumber = '', $apartmentNumber = '', $externalId = 0){
   //return $externalId;
    $address = $this->newAddress($postcode, $region, $city, $district, $street, $houseNumber, $apartmentNumber, $externalId);
    if($address->id){
        
        $param = [
                'type' => $type,
                'name' => $name,
                'lastName' => $lastName,
                'firstName' => $firstName,
                'middleName' => $middleName,
                'uniqueRegistrationNumber' => $uniqueRegistrationNumber,
                'addressId' => $address->id,
               // 'individual' => true,
                'phoneNumber' => $phoneNumber,
                'bankCode' => $bankCode,
                'bankAccount' => $bankAccount,
                'counterpartyUuid' => $counterpartyUuid,
                'email' => $email,
                'postId' => $postId,
                'tin' => $tin,
                'externalId' => $externalId,
            'ctime' => date('Y-m-d H:i:s')
            ];
	$model = 'clients';
	$client =  $this->request('POST', $model, $param);
        if($client->uuid){
          $cl = new Ukrpostkontragent();
          $cl->import($client);
          $cl->save();
        }
          return $client;
    }else{
        
        return $address;
    }
    
	}
        /**
         * 
         * @param type $lastName - фамилия
         * @param type $firstName - имя
         * @param type $middleName - по батькові
         * @param type $phoneNumber - телефон
         * @param type $externalId - ид в системі
         * @param type $postcode - індекс укрпочти
         * @param type $region - область
         * @param type $city - місто
         * @param type $district - район
         * @param type $street - вулиця
         * @param type $houseNumber - номер будинку
         * @param type $apartmentNumber - номер квартири
         * @param type $email - email
         * @return type
         */   
	public function getNewClient($lastName = '', $firstName = '', $middleName = '', $phoneNumber = 0, $externalId = '',$postcode = '', $region = '', $city = '', $district = '', $street = '', $houseNumber = '', $apartmentNumber = '', $email = '' ){
             $address = $this->newAddress($postcode, $region, $city, $district, $street, $houseNumber, $apartmentNumber, $externalId);
    
             if($address->id){
            
            $param = [
                'type' => 'INDIVIDUAL',
                'lastName' => $lastName,
                'firstName' => $firstName,
                'middleName' => $middleName,
                'addressId' => $address->id,
               // 'individual' => true,
                'phoneNumber' => $phoneNumber,
                'email' => $email,
                'externalId' => $externalId,
                'counterpartyUuid' => $this->uuid
            ];
	$model = 'clients';
	  $client = $this->request('POST', $model, $param);
          if($client->uuid){
         $cl = new Ukrpostkontragent();
                 $cl->setUuid($client->uuid);
                 $cl->setName($client->name);
                 $cl->setFirstName($client->firstName);
                 $cl->setMiddleName($client->middleName);
                 $cl->setLastName($client->lastName);
                 $cl->setExternalId($client->externalId);
                 $cl->setCounterpartyUuid($client->counterpartyUuid);
                 $cl->setAddressId($client->addressId);
                 $cl->setPhoneNumber($client->phoneNumber);
                 $cl->setEmail($client->email);
                 $cl->setType($client->type);
                 $cl->setCtime(date('Y-m-d H:i:s'));
         $cl->save();
          }
          return  $client;
    }else{
        return $address;
    }
	}
        /**
         * Приклад запиту зміни клієнта
         * @param type $uuid
         * @param type $addressId
         * @param type $phoneNumber
         * @return type
         */
        public function getEditClient($uuid = '', $name = '', $addressId = 0, $phoneNumber = '',$externalId = '',$counterpartyUuid = ''){
            $param = [];
            
            if($name){ $param ['name'] = $name;}
            if($addressId){ $param ['addressId'] = $addressId;}
            if($phoneNumber){ $param ['phoneNumber'] = $phoneNumber;}
            if($externalId){ $param ['externalId'] = $externalId;}
            if($counterpartyUuid){$param['counterpartyUuid'] = $counterpartyUuid;}

            $model = 'clients/'.$uuid;
            return $this->request('PUT', $model, $param);
        }
        /**
         * Приклад запиту/відповіді зміни головної адреси клієнта 
         * @param type $uuid - клієнта
         * @param type $address_id - адреси
         * @return type
         */
        public function putAddressClient($uuid = '', $address_id = ''){
            $param = [
                'addresses' => [[
                    'addressId' => $address_id,
                    'main' => true
                    ]]
                    ];
            $model = 'clients/'.$uuid;
            return $this->request('PUT', $model, $param);  
        }
        /**
         * Пошук всіх кліентів по externalId
         * @param type $externalId - id клієнта в системі контрагента
         * @return type
         */
         public function getClientExternalId($externalId = ''){

            $model = 'clients/external-id/'.$externalId;
            return $this->request('GET', $model);
        }
        /**
         * Пошук клієнта по номеру телефону
         * @param type $phone
         * @return type
         */
        public function getClientPhone($phone = ''){
          $param = [
              'countryISO3166' => 'UA',
              'phoneNumber' => $phone
            ];
            $model = 'clients/phone';
            return $this->request('GET', $model, $param);
        }
	/**
         * Приклад запиту створення групи відправлень
         * @param type $name
         * @return type
         */
        public function getNewShipmentGroups($name = ''){
         $param = [
             'name' => $name, 
             'clientUuid' => $this->sender_uuid,
             'type' => 'STANDARD'
                 ];
         $model = 'shipment-groups';
             $res = $this->request('POST', $model, $param);
             if($res){
         $g = new UkrPostGroup();
         $g->setUuid($res->uuid);
         $g->setName($res->name);
         $g->setClientUuid($res->clientUuid);
         $g->setType($res->type);
          $g->save();
             }
             return $res;
        }
        
        /**
         * Приклад запиту оновлення групи відправлень 
         * @param type $shipmentGroupUuid
         * @param type $name
         * @return type
         */
        public function getEditShipmentGroups($shipmentGroupUuid = '', $name = '', $id = 0){
         $param = [
             'name' => $name,
            // 'clientUuid' => $this->sender_uuid,
                 ];
         $model = 'shipment-groups/'.$shipmentGroupUuid;
             $res = $this->request('PUT', $model, $param);
             if($res){
         $g = new UkrPostGroup($id);
         //$g->setUuid($res->uuid);
         $g->setName($res->name);
         //$g->setClientUuid($res->clientUuid);
        // $g->setType($res->type);
          $g->save();
             }
             return $res;
        }
        /**
         * Список всіх груп відправлень
         * @return type
         */
        public function getListGroups(){
         
         $model = 'shipment-groups/clients/'.$this->sender_uuid;
             return $this->request('GET', $model);
        }
        /**
         * Отримання групи по uuid
         * @param type $uuid
         * @return type
         */
        public function getGroups($uuid = ''){
         
         $model = 'shipment-groups/'.$uuid;
             return $this->request('GET', $model);
        }
        /**
         * Приклад запиту на масове додавання відправлень в групу поштових відправлень за допомогою баркодів 
         * @param type $shipmentGroupUuid - uuid групи
         * @param type $shipmentBarcode
         * @return type
         */
        public function getShipmentAddGroups($shipmentGroupUuid = '', $shipmentBarcode = []){
         $param = $shipmentBarcode;
         
         $model = 'shipment-groups/'.$shipmentGroupUuid.'/shipments';
             return $this->request('GET', $model, $param);
        }
        /**
         * видалення відправлення зі створеної групи 
         * @param type $shipmentUuid - uuid відправленя
         * @return type
         */
         public function getShipmentDellGroups($shipmentUuid = ''){
         //$param = $shipmentBarcode;
         
         $model = 'shipments/'.$shipmentUuid.'/shipment-groups';
             return $this->request('DELETE', $model);
        }
        /**
         * отримання всіх відправлень які входять в групу відправлень 
         * @param type $uuid - группи
         * @return type
         */
        public function getShipmentsGroup($uuid = '', $param = ''){
            $model = 'shipment-groups/'.$uuid.'/shipments';
             return $this->request('GET', $model, $param);
        }
        /**
         * Приклад запиту створення відправлення в групі поштових відправлень 
         * @param type $shipmentGroupUuid - uuid групи
         * @param type $r_uuid - uuid
         * @param type $r_lastName - фамілія
         * @param type $r_firstName - імя
         * @param type $r_middleName - по батькові
         * @param type $externalId - id замовлення в системі контагента
         * @param type $weight - вага відправлення в грамах
         * @param type $lenght - найдовша сторона в см.
         * @param type $widht - ширина відправлення в см.
         * @param type $height - висота відправлення в см.
         * @param type $declaredPrice - заявлена ціна відправлення округлена в більшу сторону
         * @param type $postPay - Післяплата в гривнях, не може бути більшою, ніж заявлена ціна
         * @param type $description - опис
         * @param type $transferPostPayToBankAccount - false
         * @param type $paidByRecipient - false
         * @param type $postPayPaidByRecipient - true
         * @return type
         */
        public function getShipmentInGroups($shipmentGroupUuid = '', $r_uuid = '', $r_lastName = '', $r_firstName = '', $r_middleName = '', $externalId = '',  $weight = '', $lenght = '',  $declaredPrice = '', $postPay = '', $description = '', $transferPostPayToBankAccount = false, $paidByRecipient = false, $postPayPaidByRecipient = true){
         $param = [
                'type'=> 'STANDARD',
                'sender'=> ['uuid'=>$this->sender_uuid],
                'recipient'=> ['uuid' => $r_uuid, 'lastName' => $r_lastName, 'firstName' => $r_firstName, 'middleName' => $r_middleName],
                //'senderAddressId' => $this->senderAddressId,
                'externalId' => $externalId,
                'deliveryType' => 'W2W',
                'shipmentGroupUuid' => $shipmentGroupUuid,
                'parcels' => [[
                'weight'=> $weight,
                'length'=>$lenght,
               // 'width'=>$widht,
               // 'height' => $height,
                'declaredPrice' => $declaredPrice
                ]],
                'postPay' => $postPay, 
                'description' => $description,  
                'fragile' => true,
                'transferPostPayToBankAccount' => $transferPostPayToBankAccount,
                'paidByRecipient' => $paidByRecipient,
                'postPayPaidByRecipient' => $postPayPaidByRecipient
                ];
        
         
         $model = 'shipment-groups/'.$shipmentGroupUuid.'/shipments';
         $res = $this->request('POST', $model, $param);
       //   echo '<pre>';
      //  print_r($res);
        // echo '</pre>';
        // die();
         if($res->uuid){
              $ss = [
                    'uuid' => $res->uuid,
                    'type' => $res->type,
                    'sender' => (string)$res->sender->name,
                    'recipient' => (string)$res->recipient->name,
                    'recipient_phone' => (string)$res->recipientPhone,
                    'recipient_address_id' => (string)$res->recipientAddressId,
                    'return_address_id' => (string)$res->returnAddressId,
                    'shipment_group_uuid' => (string)$res->shipmentGroupUuid,
                    'external_id' => (string)$res->externalId,
                    'delivery_type' => (string)$res->deliveryType,
                    'barcode' => (string)$res->barcode,
                    'weight' => $res->weight,
                    'length' => $res->length,
                    //'width' => $res->width,
                   // 'height' => $res->height,
                    'declared_price' => $res->declaredPrice,
                    'delivery_price' => $res->deliveryPrice,
                    'post_pay' => $res->postPay,
                    'post_pay_delivery_price' => $res->postPayDeliveryPrice,
                    'description' => (string)$res->description,
                    'delivery_date'=> $res->deliveryDate,
                  'ctime' => date('Y-m-d H:i:s')
                ];
                $s = new UkrPostShipments();
                        $s->import($ss);
                $s->save();
            }
         return $res;
        }
        /**
         * Приклад запиту на отримання кількості відправлень, що входять у групу відправлень 
         * @param type $shipmentGroupUuid
         * @return type
         */
        public function getCountShipmentInGroup($shipmentGroupUuid = ''){
            
             $model = 'shipment-groups/'.$shipmentGroupUuid.'/shipments-count';
             return $this->request('GET', $model);
        }
        /**
         * Створення відправлення
         * @param type $r_uuid - uuid
         * @param type $r_lastName - фамілія
         * @param type $r_firstName - імя
         * @param type $r_middleName - по батькові
         * @param type $externalId - id замовлення в системі контагента
         * @param type $weight - вага відправлення в грамах
         * @param type $lenght - найдовша сторона в см.
         * @param type $declaredPrice - заявлена ціна відправлення округлена в більшу сторону
         * @param type $postPay - Післяплата в гривнях, не може бути більшою, ніж заявлена ціна
         * @param type $description - опис
         * @param type $transferPostPayToBankAccount - false
         * @param type $paidByRecipient - false
         * @param type $postPayPaidByRecipient - true
         * @return type
         */

        public function getNewShipments($r_uuid = '', $r_lastName = '', $r_firstName = '', $r_middleName = '', $externalId = '',  $weight = '', $lenght = '', $declaredPrice = '', $postPay = '', $description = '', $transferPostPayToBankAccount = false, $paidByRecipient = false, $postPayPaidByRecipient = true){
            $param = [
                'type'=> 'STANDARD',
                'sender'=> ['uuid'=>$this->sender_uuid],
                'recipient'=> ['uuid' => $r_uuid, 'lastName' => $r_lastName, 'firstName' => $r_firstName, 'middleName' => $r_middleName],
                //'senderAddressId' => $this->senderAddressId,
                'externalId' => $externalId,
                'deliveryType' => 'W2W',
                'parcels' => [[
                'weight'=> $weight,
                'length'=>$lenght,
               // 'width'=>$widht,
               // 'height' => $height,
                'declaredPrice' => $declaredPrice
                ]],
                'postPay' => $postPay, 
                'description' => $description,  
                //'fragile' => true,
                'transferPostPayToBankAccount' => $transferPostPayToBankAccount,
                'paidByRecipient' => $paidByRecipient,
                'postPayPaidByRecipient' => $postPayPaidByRecipient
                ];
            
            $model = 'shipments';
            $res = $this->request('POST', $model, $param);
            if($res->uuid){
               $ss = [
                    'uuid' => $res->uuid,
                    'type' => $res->type,
                    'sender' => (string)$res->sender->name,
                    'recipient' => (string)$res->recipient->name,
                    'recipient_phone' => $res->recipientPhone,
                    'recipient_address_id' => $res->recipientAddressId,
                    'return_address_id' => $res->returnAddressId,
                    'shipment_group_uuid' => (string)$res->shipmentGroupUuid,
                    'external_id' => $res->externalId,
                    'delivery_type' => $res->deliveryType,
                    'barcode' => $res->barcode,
                   'weight' => $res->weight,
                    'length' => $res->length,
                    'width' => $res->width,
                    'height' => $res->height,
                    'declared_price' => $res->declaredPrice,
                    'delivery_price' => $res->deliveryPrice,
                    'post_pay' => $res->postPay,
                    'post_pay_delivery_price' => $res->postPayDeliveryPrice,
                    'description' => (string)$res->description,
                    'delivery_date'=> $res->deliveryDate,
                   'ctime' => date('Y-m-d H:i:s')
                ];
                $s = new UkrPostShipments();
                        $s->import($ss);
                $s->save();
            }
            return $res;
        }
        /**
         * Отримати відправлення по uuid
         * @param type $uuid - відправлення
         * @return type
         */
         public function getShipments($uuid = ''){
             $model = 'shipments/'.$uuid;
            return $this->request('GET', $model);
             
         }
        /**
         * Приклад запиту зміни посилки
         * @param type $uuid
         * @param type $deliveryType
         * @param type $description
         * @param type $name
         * @param type $weight
         * @param type $length
         * @param type $declaredPrice
         * @return type
         */
         public function getEditShipments($uuid = '', $deliveryType = '', $description = '', $name = '', $weight = 1, $length = 1, $declaredPrice = 0){
             $param = [
                 'deliveryType' => $deliveryType,
                 'description' => $description,
                 'parcels' =>[
                     'name' => $name,
                     'weight' => $weight,
                     'length' => $length,
                     'declaredPrice' => $declaredPrice
                 ]
             ];
             $model = 'shipments/'.$uuid;
            return $this->request('PUT', $model, $param);
         }
         /**
          * Видалення відправлення по uuid
          * @param type $uuid - відправлення
          * @return type
          */
         public function getDeleteShipments($uuid = '') {
                $model = 'shipments/'.$uuid;
            return $this->request('DELETE', $model); 
         }
         /**
          * отримання статусу відправлення по uuid або баркоду
          * @param type $trec - uuid або  barcode відправлення
          * @return type
          */
         public function getlifecycle($trec = '') {
             
             $model = 'shipments/'.$trec.'/lifecycle';
            return $this->request('GET', $model); 
         }
         /**
          * отримання статусу післяплати по баркоду відправлення 
          * @param type $bar - barcode відправлення
          * @return type
          */
         public function getPostPays($bar = '') {
              $model = 'transfers/shipment-postpays/'.$bar.'/withrecipient';
            return $this->request('GET', $model); 
         }
         /**
          * Адресний ярлик 100*100 мм для друку на форматі А4, можна отримати за адресою
          * @param type $shipment_uui
          * @return type
          */
         public function getSticker100_100($shipment_uui = ''){
             $param = '&size=SIZE_A4';
             $model = 'shipments/'.$shipment_uui.'/sticker';
             return $this->prints('GET', $model, $param);
         }
         /**
          * Адресний ярлик 100*100 мм для друку на форматі А4, для групи відправлень
          * @param type $shipment_group_uuid
          * @return type
          */
         public function getSticker100_100Group($shipment_group_uuid = ''){
             //$param = ['size' => 'SIZE_A4'];
             $param = '';// '&size=SIZE_A4';
             $model = 'shipment-groups/'.$shipment_group_uuid.'/sticker';
             return $this->prints('GET', $model, $param);
         }
         /**
          * Форма 103а для групи поштових відправлень доступна за адресою
          * @param type $shipment_group_uuid
          * @return type
          */
         public function getForm103a($shipment_group_uuid = ''){
             $model = 'shipment-groups/'.$shipment_group_uuid.'/form103a';
             
             return $this->prints('GET', $model);
         }
         
        
        /**
         * Приклад запиту відстеженя посилки
         * @param type $b
         * @return type
         */
	public function getBarcode($b = ''){
	$model = 'shipments/barcode/'.$b;
	 return $this->request('GET', $model);
	}
        
        public function getGroupStiker($uuid = ''){
            $model = 'shipment-groups/'.$uuid.'/sticker';
            return $this->prints('GET', $model);
        }
        
    
	
private function request($type = 'GET', $model = 'doc', $param = ''){
$curl = curl_init();
$crl = [
  CURLOPT_URL => 'https://www.ukrposhta.ua/ecom/0.0.1/'.$model.'?token='.$this->token,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
 CURLOPT_CUSTOMREQUEST => $type,
  CURLOPT_POSTFIELDS => json_encode($param),
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer ".$this->bearer,
    "Cache-Control: no-cache",
    "Content-Type: application/json"
  ]
];
curl_setopt_array($curl, $crl);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
  return $this->prepare($err);
} else {
  return $this->prepare($response);
 // echo $response;
}
}
private function prints($type = 'GET', $model = 'doc', $param = ''){
$curl = curl_init();
$crl = [
  CURLOPT_URL => 'https://www.ukrposhta.ua/ecom/0.0.1/'.$model.'?token='.$this->token.$param,
  CURLOPT_RETURNTRANSFER => true,
 // CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
 // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
 // CURLOPT_CUSTOMREQUEST => $type,
 // CURLOPT_POSTFIELDS => $param,
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer ".$this->bearer,
    "Cache-Control: no-cache",
    "Content-Type: application/json"
  ]
];
curl_setopt_array($curl, $crl);

$response = curl_exec($curl);
$err = curl_error($curl);
//print_r(curl_getinfo($curl));  
curl_close($curl);
if ($err) {
  return $this->prepare($err);
} else {
return $response;
}
}

        
public function getRegionsFilter(){
              $model = 'address-classifier/get_regions_by_region_ua';
	 return $this->requestfilter('GET', $model); 
        }

        /**
         * Отримання міста за назвою
         * @param type $city - назва міста 
         * @return type
         */
        public function getCityFilter($city = ''){
              $model = 'address-classifier/get_city_by_name?city_name='.iconv('UTF-8', 'windows-1251', $city).'&lang=UA&fuzzy=1';
             // $model = 'address-classifier/get_city_by_name?city_name=Бровари&lang=UA&fuzzy=1';
	 return $this->requestfilter('GET', $model); 
        }
        /**
         * Отримання міста за назвою
         * @param type $id - id міста 
         * @return type
         */
        public function getPostByCityFilter($id){
              $model = 'address-classifier/get_postoffices_by_city_id?city_id='.$id;
	 return $this->requestfilter('GET', $model); 
        }
        /**
         * Отримання інформації про відділення по індексу
         * @param type $id - індекс відділення
         * @return type
         */
        public function getPostIndexFilter($id = ''){
              $model = 'address-classifier/get_postoffices_by_postindex?pi='.$id;
	 return $this->requestfilter('GET', $model);
        }

private function requestfilter($type = 'GET', $model = 'doc'){
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://www.ukrposhta.ua/'.$model,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
  //CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  //CURLOPT_CUSTOMREQUEST => $type,
// CURLOPT_POSTFIELDS => $param,
  /* CURLOPT_HTTPHEADER => [
    "Cache-Control: no-cache",
    "Content-Type: application/json"
  ],*/
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
  echo "cURL Error #:" . $err;
} else {
 //return $this->prepare(simplexml_load_string($response));
    $arr = (array)simplexml_load_string($response);
    //return $arr;
 return $this->prepare(json_encode($arr));
 // echo print_r($response);
}
}
		
		
	
	
	

}