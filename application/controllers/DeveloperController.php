<?php

class DeveloperController extends controllerAbstract
{

 //public function init()
   // {
	//$this->view->setRenderPath('developer');
	//$this->_global_template = 'developer.tpl.php';
	//}


public function indexAction(){
//require_once('Google/HelloAnalytics.php');
require_once('up/UkrPostAPI.php');

$up = new UkrPostAPI();
echo '<pre>';
//print_r($up->getBarcode('0500124419630'));
//$r = $up->newAddress('04080', 'Київська', 'Подільський', 'Київ', 'Нижньоюрківська', '31');// наш адрес

//print_r($up->newAddress('11033'));// вовий адрес
//print_r($up->getAddress(16439863)); 
//
if(false){
    $adr = [
        'ids' => $result->id,
        'postcode' => $result->postcode,
        'country' => $result->country,
        'region' => $result->region,
        'city' => $result->city,
        'district' => $result->district,
        'street' => (string)$result->street,
       'house_number' => (string)$result->houseNumber,
        'apartment_number' => (string)$result->apartmentNumbe,
        'description' => (string)$result->description,
        'countryside' => $result->countryside,
        'detailed_info' => $result->detailedInfo,
        'clients_id' => 8005
    ];
    print_r($adr);
    $adres = new UkrPostAddress();
    //$adres->setPostcode('11033');
     $adres->import($adr);
    // print_r($adres);
     $adres->save();
    /*
               $adrr = new UkrPostAddress($result->id);
               $adrr->setPostcode($result->postcode);
               $adrr->setCountry($result->country);
               $adrr->setRegion($result->region);
               $adrr->setCity($result->city);
               $adrr->setDistrict($result->district);
               $adrr->setStreet($result->street);
               $adrr->setHouseNumber($result->houseNumber);
               $adrr->setApartmentNumber($result->apartmentNumber);
               $adrr->setDescription($result->description);
               $adrr->setCountryside($result->countryside);
               $adrr->setDetailedInfo($result->detailedInfo);
               $adrr->setClientsId(8005);
               $adrr->save();
     * */
     
           }
         //  print_r($up->putAddressClient('e7059d25-7a15-44fe-bf92-cf7823fe014d', 15995702));
//print_r($up->getClientExternalId(8005));
if(false){
    echo 'tut';
    print_r($client);
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
         $cl->save();
          }
//L180005120BNN
//
//print_r($up->getNewClient('Романчук','Ярослав','Анатолійович',813285, '0968171330', 8005));
//print_r($up->getClientExternalId(15972));
//print_r($up->getClientAddress('e7059d25-7a15-44fe-bf92-cf7823fe014d'));
//print_r($up->getClientPhone('0968171330'));
//print_r($up->getEditClient('5b4260e8-b044-4aad-a750-6f4a26a23389', '', '', '', '', 'ce4e19d6-6a4f-4f1f-a8c4-5b4a1f9cde9a'));
/*$r = $up->getnewClientAdmin(
        'PRIVATE_ENTREPRENEUR',
        'ФОП ЦИБУЛЯ І.В., інд. роб місця-01926',
        'Цибуля',
        'Ірина',
        '',
        '812610',
        '0971876821',
        '300528',
        '26005455021620',
        '',
        'sku@red.ua',
        '',
        '2978405548',
        '04080',
        'Київська',
        'Подільський',
        'Київ',
        'Нижньоюрківська',
        '31',
        '',
        1
        );
 * */
//$r = $up->getNewClient($lastName = '', $firstName = '', $middleName = '', $phoneNumber = 0, $externalId = '',$postcode = '', $region = '', $city = '', $district = '', $street = '', $houseNumber = '', $apartmentNumber = '' );
//[

//print_r($up->getNewShipments('5b4260e8-b044-4aad-a750-6f4a26a23389', '365040', 1000, 24, 24, 15, 1240, '1239.5', 'Zakaz 365040'));
print_r($up->getListGroups());
print_r($up->getShipments('ee10ef89-0640-46cd-a588-8597073e4e61'));

if(false){
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
                    'delivery_date'=> $res->deliveryDate
                ];

                
                print_r($res);
    
    
}
//
//
//print_r($up->getNewShipmentGroups('06.12.2018')); // нова группа
//$r = $up->getEditShipmentGroups('6a35aeff-cae7-416e-b275-68091bf9565c', '05.12.2018'); //редактирование группи
if($r->uuid and false){
    echo $r->uuid;
   // $g = new UkrPostGroup();
   // $g->import($r);
      // $g->setUuid($r->uuid);
      //   $g->setName($r->name);
         $g->setClientUuid($r->clientUuid);
         $g->setType($r->type);
          $g->save();
}
//$up->getNewShipmentGroups('07.12.2018');

//$file = $up->getSticker100_100('b7af203e-82ec-4c30-a0ed-27d361ad5caa');
if($file){
    header('Content-type: application/pdf');
    //header('Content-Disposition: attachment; filename=b7af203e-82ec-4c30-a0ed-27d361ad5caa.pdf');
echo $file;
    exit();
}
   // header('Content-type: application/pdf');
   // header('Content-Disposition: attachment; filename=1.pdf');
   // echo $file;
     //exit();
// заставляем браузер показать окно сохранения файла
   // header('Content-Description: File Transfer');
   // header('Content-Type: application/octet-stream');
  // header('Content-Disposition: attachment; filename=new.pdf');
  //  header('Content-Transfer-Encoding: binary');
  //  header('Expires: 0');
  //  header('Cache-Control: must-revalidate');
   // header('Pragma: public');
   // header('Content-Length: ' . filesize($file));
    // читаем файл и отправляем его пользователю
   // readfile($file);
   /// exit;
//$file = file_get_contents('php://output', $file);
//echo $res;
//print_r($up->getListGroups());//список груп
//print_r($up->getDeleteShipments('1a57b6a5-1b88-4235-8619-80f1dd886752'));
//print_r($up->getCountShipmentInGroup('244a9fd2-1174-4314-ac8d-d062520b37e1')->quantity);//количество посилок в группе
//print_r($up->getSticker100_100Group('e6b590b2-e4fe-4b05-ac4f-0ebe8447c57a'));// печать стикеров  групи
//


//print_r($r);

//$array = json_decode(json_encode((array)simplexml_load_string($up->getPostIndexFilter('11033'))),true);
//$xml = new SimpleXMLElement($up->getPostIndexFilter('11033'));
//$res = $up->getPostIndexFilter('11033');
//$res = $up->getRegionsFilter();
//$res = $up->getCityFilter('Бровари');
/*
if(isset($res->Entry)){
    print_r($res->Entry);
}else{
    print_r($res);
    echo '<br>ошибка<br>'.$res->body->h1;
//print_r($up->getCityFilter('Бровари'));
}
*/


//print_r($res);
echo '</pre>';

//echo $p = $up->getPostIndexFilter('11033');
//echo $up->getShipmentGroups('e6b590b2-e4fe-4b05-ac4f-0ebe8447c57a');
//
//
//
//echo $up->getAssress('812308'); 
//echo print_r($up->getAddressFilter('get_city_details_by_postcode', ['postcode' => '11033']));
//
//$analytics = initializeAnalytics();
//$profile = '57394917';//$this->getFirstProfileId($analytics);
//echo $profile;
//$results = $this->getResults($analytics, $profile);
//$this->printResults($results); 

}
function getFirstProfileId($analytics) {
  // Get the user's first view (profile) ID.

  // Get the list of accounts for the authorized user.
  $accounts = $analytics->management_accounts->listManagementAccounts();

  if (count($accounts->getItems()) > 0) {
    $items = $accounts->getItems();
    $firstAccountId = $items[0]->getId();

    // Get the list of properties for the authorized user.
    $properties = $analytics->management_webproperties
        ->listManagementWebproperties($firstAccountId);

    if (count($properties->getItems()) > 0) {
      $items = $properties->getItems();
      $firstPropertyId = $items[0]->getId();

      // Get the list of views (profiles) for the authorized user.
      $profiles = $analytics->management_profiles
          ->listManagementProfiles($firstAccountId, $firstPropertyId);

      if (count($profiles->getItems()) > 0) {
        $items = $profiles->getItems();

        // Return the first view (profile) ID.
        return $items[0]->getId();

      } else {
        throw new Exception('No views (profiles) found for this user.');
      }
    } else {
      throw new Exception('No properties found for this user.');
    }
  } else {
    throw new Exception('No accounts found for this user.');
  }
}

function getResults($analytics, $profileId) {
  // Calls the Core Reporting API and queries for the number of sessions
  // for the last seven days.
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       '2018-11-06',//1daysAgo
       '2018-11-07',
       'ga:sessions, ga:users , ga:newUsers, ga:bounceRate,  ga:pageviews, ga:pageviewsPerSession'
           );
}

function printResults($results) {
  // Parses the response from the Core Reporting API and prints
  // the profile name and total sessions.
  if (count($results->getRows()) > 0) {

    // Get the profile name.
    $profileName = $results->getProfileInfo()->getProfileName();

    // Get the entry for the first entry in the first row.
    $rows = $results->getRows();
    echo print_r($rows);
    //$sessions = $rows[0][0];

    // Print the results.
   // print "First view (profile) found: $profileName\n";
   // print "Total sessions: $sessions\n";
  } else {
    print "No results found.\n";
  }
}
/**
 * Description of HelloAnalytics
 *
 * @author PHP
 */
// Load the Google API PHP Client Library.

/*
function initializeAnalytics()
{
  // Creates and returns the Analytics Reporting service object.

  // Use the developers console and download your service account
  // credentials in JSON format. Place them in this directory or
  // change the key file location if necessary.
  $KEY_FILE_LOCATION = __DIR__ . '/google-api-php-client-master/My Project-815e1c05c886.json';

  // Create and configure a new client object.
  $client = new Google_Client();
  $client->setApplicationName("Hello Analytics Reporting");
  $client->setAuthConfig($KEY_FILE_LOCATION);
  $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
  $analytics = new Google_Service_Analytics($client);

  return $analytics;
}

function getFirstProfileId($analytics) {
  // Get the user's first view (profile) ID.

  // Get the list of accounts for the authorized user.
  $accounts = $analytics->management_accounts->listManagementAccounts();

  if (count($accounts->getItems()) > 0) {
    $items = $accounts->getItems();
    $firstAccountId = $items[0]->getId();

    // Get the list of properties for the authorized user.
    $properties = $analytics->management_webproperties
        ->listManagementWebproperties($firstAccountId);

    if (count($properties->getItems()) > 0) {
      $items = $properties->getItems();
      $firstPropertyId = $items[0]->getId();

      // Get the list of views (profiles) for the authorized user.
      $profiles = $analytics->management_profiles
          ->listManagementProfiles($firstAccountId, $firstPropertyId);

      if (count($profiles->getItems()) > 0) {
        $items = $profiles->getItems();

        // Return the first view (profile) ID.
        return $items[0]->getId();

      } else {
        throw new Exception('No views (profiles) found for this user.');
      }
    } else {
      throw new Exception('No properties found for this user.');
    }
  } else {
    throw new Exception('No accounts found for this user.');
  }
}

function getResults($analytics, $profileId) {
  // Calls the Core Reporting API and queries for the number of sessions
  // for the last seven days.
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       'today',
       'today',
       'ga:pageviews');
}

function printResults($results) {
  // Parses the response from the Core Reporting API and prints
  // the profile name and total sessions.
  if (count($results->getRows()) > 0) {

    // Get the profile name.
    $profileName = $results->getProfileInfo()->getProfileName();

    // Get the entry for the first entry in the first row.
    $rows = $results->getRows();
    $sessions = $rows[0][0];
       // echo print_r($rows);
    // Print the results.
   // print "First view (profile) found: $profileName\n";
   // print "Total sessions: $sessions\n";
  } else {
    print "No results found.\n";
  }
}
*/

public function customerAction() {
    
    //$this->view->customer = wsActiveRecord::useStatic('Customer')->findAll(['customer_type_id < 2', 'customer_status_id < 2']);
    
    echo $this->render('developer/customer.php');
}

public function obuvAction()
        {
        $category = wsActiveRecord::useStatic('Shopcategories')->findFirst(['translate'=>'obuv']);
        
        $this->cur_menu->name = $category->getRoutez();
$this->view->category = $category;
if($category->getId()){
  $this->view->g_url = $category->getPath();  
    
}else{
    $this->view->g_url = 'category/';
    
}
$this->view->finder_category =  $category->id;
//$route = $this->rout;
//$get = array_values($route);
$get =  $_SERVER['DOCUMENT_URI'];
echo print_r($this->get);
$route = explode('/', substr($get,1));

$route = array_values($route);

//unset($route[0]);

echo print_r($route);
                       // echo $category->getRoutez();
    $this->getfilter(false, $category->id, $this->get->brands, $this->get->colors, $this->get->sizes, $this->get->labels, $this->get->sezons, $this->get->skidka, $this->get->categories, array('price_min'=>$this->get->price_min, 'price_max'=>$this->get->price_max));

            }
            
            
         function getfilter($search_word = '', $category = '', $brands = '', $colors = '', $sizes = '', $labels = '', $sezons = '', $skidka = '', $categories = '', $price = array())
	{
		//$addtional = array();
		//$addtional = array('categories'=>array(), 'colors'=>array(), 'sizes'=>array(), 'labels'=>array(), 'brands'=>array(), 'sezons'=>array(), 'skidka'=>array(), 'price'=>array());

		//d($price, false);

		//price
		if($price['price_min'] != NULL ) {$addtional['price']['min'] =  $price['price_min'];}
		if($price['price_max'] != NULL ) {$addtional['price']['max'] =  $price['price_max'];}

		 //categories
		$addtional['categories'] = $categories?$categories:$this->post->categories;
		//brands
		//d($brands, false);
                 $addtional['brands'] = $brands?$brands:$this->post->brands;
       // foreach (explode(',', $brands?$brands:$this->post->brands) as $v){ if ($v) $addtional['brands'][] =  (int)$v; }

		//colors
                $addtional['colors'] = $colors?$colors:$this->post->colors;
        //foreach (explode(',', $colors?$colors:$this->post->colors) as $v){ if ($v) $addtional['colors'][] = (int)$v;}

		//sizes
                $addtional['sizes'] = $sizes?$sizes:$this->post->sizes;
        //foreach (explode(',', $sizes?$sizes:$this->post->sizes) as $v){ if ($v) $addtional['sizes'][] = (int)$v; }

		//labels
                $addtional['labels'] =$labels?$labels:$this->post->labels;
        //foreach (explode(',', $labels?$labels:$this->post->labels) as $v){ if ($v) $addtional['labels'][] = (int)$v; }

		//sezons
                $addtional['sezons'] = $sezons?$sezons:$this->post->sezons;
       // foreach (explode(',', $sezons?$sezons:$this->post->sezons) as $v){ if ($v)$addtional['sezons'][] = (int)$v; }

		//skidka
                $addtional['skidka'] = $skidka?$skidka:$this->post->skidka;
       // foreach (explode(',', $skidka?$skidka:$this->post->skidka) as $v){ if ($v) $addtional['skidka'][] = (int)$v; }


		//d($addtional, false);
		$prod_on_page = $_COOKIE['items_on_page'];
                
                if (!$prod_on_page){ $prod_on_page = Config::findByCode('products_per_page')->getValue();}

		$this->view->per_page = $onPage = $prod_on_page;
                
		$page = $this->get->page?(int)$this->get->page:0;

		//d($page, false);
		$search_result = Filter::getArticlesFilter($search_word, $addtional, $category, $this->get->order_by, $page, $onPage);

		$this->view->filters = $search_result['parametr'];
               if($search_result['meta']) {$this->view->meta = $search_result['meta'];}

		$this->view->cur_page = $page;

		$this->view->result_count = $search_result['count'];

		$this->view->total_pages = $search_result['pages'];

		$this->view->search_word = $search_word;
		$this->view->articles = $search_result['articles'];
		$this->view->result = $this->view->render('finder/list.tpl.php');
                $this->view->order_by = $this->get->order_by; 

		$this->view->price_min = $search_result['min_max'] ? $search_result['min_max'][0]->min: 0;
		$this->view->price_max = $search_result['min_max'] ? $search_result['min_max'][0]->max : 1;
		echo $this->render('finder/result.tpl.php');
	}   
            
            

public function parseAction(){
    
    $i=0;

    echo ++$i;
    echo '<br>'.$i;
    
    echo '<pre>';
   // $route = explode('/', $this->get);
    echo print_r($this->get);
    //echo print_r(Registry::get('route'));
    echo '</pre>';
    
   // var_dump(parse_url($this->get, PHP_URL_PATH));
   // var_dump($_GET);
}


public function basketcontactsAction(){
			
		//if ($this->ws->getCustomer()->isBan()) $this->_redir('ban');
      //  if ($this->ws->getCustomer()->isNoPayOrder()) $this->_redir('nopay');
	//	if (!$this->basket) $this->_redir('index');
		
		//if ($this->ws->getCustomer()->isAdmin() and !$this->ws->getCustomer()->hasRight('do_pay')) die('Нету прав на заказ');

		$info = array();
		$errors = array();
		$err_m = array();
		
		
		if(@$this->post->contact_valid){ //
		if (!$this->ws->getCustomer()->getIsLoggedIn()) {
		$email = $this->post->email;
		$phone = $this->post->phone;
		$phone = Number::clearPhone(trim($info['telephone']));
			$phone = preg_replace('/[^0-9]/', '', $info['telephone']);
			$phone = substr($tel, -10);
			//die(json_encode($email));
		if (wsActiveRecord::useStatic('Customer')->findByEmail($email)->count()){
		
		$info['email'] = $email;
		$info['open_form_avtor'] = 'open';
		die(json_encode($info));
		}
		
		if(wsActiveRecord::useStatic('Customer')->findFirst(array(" phone1 LIKE  '%".$phone."%' "))){
		$info['phone'] = $this->post->phone;
		$info['open_form_avtor'] = 'open';
		die(json_encode($info));
		}
		
		}else{
		$info['open_form_avtor'] = 'off';
		die(json_encode($info));
		//
		}
		
		
		//
		}
		if(@$this->post->delivery_type){ //
		//$result = array();
		$dop = array();
		$pay = array();
		$id = $this->post->id;
		$dely = wsActiveRecord::useStatic('DeliveryPayment')->findAll(array('delivery_id'=>(int)$id));
		if($dely){
		foreach($dely as $d){
		$pay[$d->payment_id] = $d->payment->name;
		}
		}
		switch($id){
			case 9: $dop = array('street', 'hous', 'flat');
				break;
			case 8: $dop = array('city', 'sklad');
				break;
			case 3: $dop = array('pobeda');
				break;
			case 5: $dop = array('mishuga');
				break;
			case 4: $dop = array('index', 'obl', 'rayon', 'city', 'street', 'hous', 'flat');
				break;
		   }
		
		
		die(json_encode(array('pay'=>$pay, 'dop'=>$dop)));
		}
		if(@$this->post->payment_method){ //
		
		
		
		die();
		}
		if ($_POST and false) {
			foreach ($_POST as &$value)
				$value = stripslashes(trim($value));
				
			$_SESSION['basket_contacts'] = $_POST;

			$info = $_SESSION['basket_contacts'];
			$info['kupon'] = $_SESSION['kupon'];
			//unset($_SESSION['kupon']);
			
            $error_email = 0;
            if (!$this->ws->getCustomer()->getIsLoggedIn()) {
                if (wsActiveRecord::useStatic('Customer')->findByEmail($info['email'])->count() != 0) {
                    $error_email = 1;
					 $errors['error'][] = $this->trans->get('Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь').'.';
                   // $this->view->error_email = $this->trans->get('Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь').'.';
					$errors['Email'] = 'Email';
				}
				
				
            }
            $tel = Number::clearPhone(trim($info['telephone']));
			$tel = preg_replace('/[^0-9]/', '', $info['telephone']);
			$tel = substr($tel, -10);
            $allowed_chars = '1234567890'; 
            if (!Number::clearPhone($tel)) {
                $errors['error'][] = $this->trans->get('Введите телефонный номер');
                $errors['telefon'] = $this->trans->get('Телефон');
            }
			if(strlen($tel) != 10){
			$errors['error'][] = $this->trans->get('Неверный формат номера').".";
            $errors['telefon'] = $this->trans->get('Телефон');
			}
            for ($i = 0; $i < mb_strlen($tel); $i++) {
                if (mb_strpos($allowed_chars, mb_strtolower($tel[$i])) === false) {
                    $errors['error'][] = $this->trans->get('В номере должны быть только числа');
                    $errors['telefon'] = $this->trans->get('Телефон');
                }
            }
            $alredy = wsActiveRecord::useStatic('Customer')->findFirst(array(" phone1 LIKE  '%".$tel."%' "));
            if ($alredy) {
                if ($alredy->getUsername() != null and $alredy->getId() != $this->ws->getCustomer()->getId()) {
                    $errors['error'][] = $this->trans->get('Пользователь с таким номером телефона уже зарегистрирован в системе.<br /> Поменяйте телефон или зайдите как зарегистрированный пользователь').".";
                    $errors['telefon'] = $this->trans->get('Телефон');
                }
            }
			
			

            foreach ($info as $k => $v)
                $info[$k] = strip_tags(stripslashes($v));

            if (!$info['name'])
                $errors[] = $this->trans->get('Имя');

            if (isset($info['middle_name'])) {
                if (!$info['middle_name'])
                    $errors[] = $this->trans->get('Фамилия');
            }
			else {
                $errors[] = $this->trans->get('Фамилия');
            }

            if (!$info['delivery_type_id'])
                $errors[] = $this->trans->get('Способ доставки');
            if (!isset($info['payment_method_id']))
                $errors[] = $this->trans->get('Способ оплаты');
            if (!isset($info['soglas']))
                $errors[] = $this->trans->get('Согласие');
            if (!isset($info['oznak']))
                $errors[] = $this->trans->get('С условиями ознакомлен');

            if (@$info['delivery_type_id'] == 4) { //Укр почта
                if (!$info['index'])
                    $errors[] = $this->trans->get('Индекс');
                if (!$info['street'])
                    $errors[] = $this->trans->get('Улица');
                if (!$info['house'])
                    $errors[] = $this->trans->get('Дом');
                if (!$info['flat'])
                    $errors[] = $this->trans->get('Квартира');
                if (!$info['obl'])
                    $errors[] = $this->trans->get('Область');
                if (!$info['rayon'])
                    $errors[] = $this->trans->get('Район');
                if (!$info['city'])
                    $errors[] = $this->trans->get('Город');
            }
            if (@$info['delivery_type_id'] == 9) {
			//Курьер
			
			
               if (!$info['s_street'])
                    $errors[] = $this->trans->get('Улица');
                if (!$info['house'])
                    $errors[] = $this->trans->get('Дом');
					if (!$info['flat'])
                    $errors[] = $this->trans->get('Квартира');
					
					 
					}
					
					
            
            if ($info['delivery_type_id'] == 8  or $info['delivery_type_id'] == 16) {
				if (@$info['payment_method_id'] == 3) {
					$info['delivery_type_id'] = 16;
				}
				else {
					$info['delivery_type_id'] = 8;
				}
			}
            if (@$info['delivery_type_id'] == 8 or @$info['delivery_type_id'] == 16) { //Новая почта
                if (!$info['city_np'])
                    $errors[] = $this->trans->get('Город');
                if (!$info['sklad'])
                    $errors[] = $this->trans->get('Склад НП');
					if (!$info['sklad_np'])
                    $errors[] = 'Проблема с ид склада';
            }
            if (!$info['email'] || !isValidEmail($info['email']))
                $errors[] = 'email';
				
				if ($info['delivery_type_id'] == 3  or $info['delivery_type_id'] == 5) {
	$or_c = wsActiveRecord::useStatic('Shoporders')->findAll(array("email LIKE  '".$info['email']."'", 'delivery_type_id  IN ( 3, 5 ) ', 'payment_method_id'=>1, 'status'=>3));
	//
					if($or_c->count() >= 3){
					$ord = '';
foreach($or_c as $r){
$ord.=$r->id.', ';
}
					$err_m[] = 'По состоянию на '.date('d.m.Y').', в пункте выдачи интернет-магазина, находятся Ваши неоплаченые заказы № '.$ord.'. В связи с этим, Вам ограничено оформление заказов в пункты самовывоза с оплатой наличными при получении, до оплаты доставленых заказов. Дополнительную информацию Вы можете получить в нашем Call-центре по номеру (044)224-40-00 Пн-Пн с 09:00-18:00.';
					}
					}
				
            if (!$errors and $error_email == 0 and !$err_m) {
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				list($articles, $options) = $this->createBasketList();
				
				$_SESSION['basket_articles'] = $articles;
					$this->view->articles = $articles;
				$_SESSION['basket_options'] = $options;
					$this->view->options = $options;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$t_count = 0;
				$t_price = 0.00;
				$total_price = 0.00;
				$to_pay = 0;
				$to_pay_minus = 0.00;
				$skidka = 0;
				$bonus = false;
				$now_orders = 0;
				$event_skidka = 0;
				$kupon = 0;
				//$sum_order = 0;
				
				if(@$info['kupon']){	
			$today_c = date("Y-m-d H:i:s"); 
			$ok_kupon = wsActiveRecord::useStatic('Other')->findFirst(array("cod"=>$info['kupon'], "ctime <= '".$today_c."' ", " '".$today_c."' <= utime"));
			if(@$ok_kupon){
			$kupon = $ok_kupon->cod;
			$info['kupon_price'] = $ok_kupon->skidka;
			$find_count_orders_by_user_cod = 0;
			}
					}
				
				if ($this->ws->getCustomer()->getIsLoggedIn()) {
					$skidka = $this->ws->getCustomer()->getDiscont(false, 0, true);
					$event_skidka =  EventCustomer::getEventsDiscont($this->ws->getCustomer()->getId());
					$all_orders = wsActiveRecord::useStatic('Customer')->findByQuery('
						SELECT
							IF(SUM(price*count) IS NULL,0,SUM(price*count)) AS amount
						FROM
							ws_order_articles
							JOIN ws_orders
							ON ws_order_articles.order_id = ws_orders.id
						WHERE
							ws_orders.customer_id = ' . $this->ws->getCustomer()->getId() . '
							AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16) ')->at(0);
					$now_orders = $all_orders->getAmount();
				}
				foreach ($articles as $article) {
					$t_price += $article['price'] * $article['count'];
				}
				$now_orders += $t_price;
				
				foreach ($articles as $article) {
					$at = new Shoparticles($article['id']);
					//$to_pay_perc = $at->getProcent($now_orders, $skidka);
					if($article['option_id']){
					$price = $at->getPerc($now_orders, $article['count'], $skidka, 99, $kupon, $t_price);
					}else{
					$price = $at->getPerc($now_orders, $article['count'], $skidka, $event_skidka, $kupon, $t_price);
					}
							//$price = $at->getPerc($now_orders, $article['count'], $skidka, $event_skidka, $kupon, $t_price);
					$to_pay += $price['price'];
					$to_pay_minus += $price['minus'];
				}
				$tota_price = $t_price;
				
				$kop = round(($to_pay - toFixed($to_pay)) * 100, 0);
				$total_price = $to_pay + Shoporders::getDeliveryPrice();

				
				$_SESSION['sum_to_ses'] = $total_price;
				$_SESSION['sum_to_ses_no_dos'] = $to_pay;
				
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$check_c = array();
				$this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'];

				foreach ($this->basket_articles as $key => $article) {
					$itemcs = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article['id'], 'id_size' => $article['size'], 'id_color' => $article['color']));
					if ($itemcs->getCount() == 0) {
						$check_c[$key] = $article; //массив товаров которые в этот момент успели купить другие
					}
				}

				if (count($_SESSION['basket_articles']) and isset($_GET['recalculate']) and false )
					$this->_redir('shop-checkout-step4');
				if (!count($_SESSION['basket_articles']))
					$this->_redir('shop-checkout-step3');
				//проверка товаров которых нет в наличии
				if (count($check_c) > 0) {
					foreach ($check_c as $key => $val) {
						$basket = $_SESSION['basket'];
						if ($basket) {
							$_SESSION['basket'] = array();
							foreach ($basket as $keyb => $value)
								if ($key != $keyb)
									$_SESSION['basket'][] = $value;
						}
						unset($_SESSION['basket_articles'][$key]);
					}
					$this->view->articles = $check_c;
					echo $this->render('shop/basket-message.tpl.php');
				} else {
					$curdate = Registry::get('curdate');
					$order = new Shoporders(); 

					$mas_adres = array();
					$city ='';
					$sklad = '';
					if (isset($info['index']) and strlen($info['index']) > 0) {
						$mas_adres[] = $info['index'];
					}
					if (isset($info['obl']) and strlen($info['obl']) > 0) {
						$mas_adres[] = $info['obl'];
					}
					if (isset($info['rayon']) and strlen($info['rayon']) > 0) {
						$mas_adres[] = $info['rayon'];
					}
					if (@$info['delivery_type_id'] == 8 or @$info['delivery_type_id'] == 16) { //Новая почта
						if (isset($info['city_np']) and strlen($info['city_np']) > 0) {
							$mas_adres[] = 'г. ' . $info['city_np'];
							$city = $info['city_np'];
						}
					}else if(@$info['delivery_type_id'] == 9){  //kurer
							$mas_adres[] = 'г. Киев';
							$city ='Киев';

					}elseif(isset($info['city']) and strlen($info['city']) > 0) {
					
						$mas_adres[] = 'г. ' . $info['city'];
						$city = $info['city'];
						
					}
					if(@$info['delivery_type_id'] == 9){  //kurer
					if (isset($info['s_street']) and strlen($info['s_street']) > 0) {
							$mas_adres[] = $info['s_street'];
						}
					
					}else if (isset($info['street']) and strlen($info['street']) > 0) {
						$mas_adres[] = 'ул.' . $info['street'];
					}
					if (isset($info['house']) and strlen($info['house']) > 0) {
						$mas_adres[] = 'д.' . $info['house'];
					}
					if (isset($info['flat']) and strlen($info['flat']) > 0) {
						$mas_adres[] = 'кв.' . $info['flat'];
					}
					if (@$info['delivery_type_id'] == 8 or @$info['delivery_type_id'] == 16) { //Новая почта
						if (isset($info['sklad']) and strlen($info['sklad']) > 0) {
							$mas_adres[] = 'НП: ' . $info['sklad'];
							$sklad = $info['sklad'];
						}
					}

					
					$info['address'] = implode(', ', $mas_adres);
					
					$info['l_name'] = @$info['name'].' '.@$info['last_name'];
					
					$phone = preg_replace('/[^0-9]/', '', $info['telephone']);
					$phone = substr($phone, -10);
					$phone = '38'.$phone;
					$data = array(
						'status' => 100,
						'date_create' => $curdate->getFormattedMySQLDateTime(),
						'company' => isset($info['company']) ? $info['company'] : '',
						'name' => @$info['l_name'],
						'middle_name' => @$info['middle_name'],
						'address' => @$info['address'],
						'index' => @$info['index'],
						'street' => @$info['delivery_type_id'] == 9 ? $info['s_street'] : @$info['street'],
						'house' => @$info['house'],
						'flat' => @$info['flat'],
						'pc' => @$info['pc'],
						'city' => @$city,
						//'city' =>  isset($info['city_np']) ? $info['city_np'] : $info['city'],
						'obl' => isset($info['obl']) ? $info['obl'] : '',
						'rayon' => @$info['rayon'],
						'sklad' => @$sklad,
						'telephone' => @$phone,
						'email' => @$info['email'],
						'comments' => isset($info['comments']) ? $info['comments'] : '',
						'delivery_cost' =>Shoporders::getDeliveryPrice(),
						'delivery_type_id' => @$info['delivery_type_id'],
						'payment_method_id' => @$info['payment_method_id'],
						'liqpay_status_id' => 1,
						'amount' => @$_SESSION['sum_to_ses'] ? @$_SESSION['sum_to_ses'] : 0,
						'soglas' => @$info['soglas'] ? 1 : 0,
						'oznak' => @$info['oznak'] ? 1 : 0,
						'call_my' => @$info['callmy'] ? 1 : 0,
						'quick' => 0,
						'kupon' => @$info['kupon'] ? @$info['kupon'] : '',
						'kupon_price' => @$info['kupon_price'] ? @$info['kupon_price'] : ''
							
					);

					
					$deposit = 0;
					$order->import($data);
					$order->save();
					

					
					if (@$_SESSION['deposit'] and $this->ws->getCustomer()->getDeposit()) {
					$total_price = $order->getAmount();
					//}	
					$dep = $this->ws->getCustomer()->getDeposit() - $total_price;
					
					if ($dep <= 0) $dep = $this->ws->getCustomer()->getDeposit();
					else $dep = $total_price;
					
					$_SESSION['deposit'] = $dep; 
					$order->setDeposit($dep);
					//perevod v novu pochtu esly polnosty oplachen depositom
					if($order->getDeliveryTypeId() == 16 and $dep == $total_price){
					$order->setDeliveryTypeId(8);
					$info['delivery_type_id'] = 8;
					$order->setPaymentMethodId(2);
					$info['payment_method_id'] = 2;
					}
					//perevod v novu pochtu esly polnosty oplachen depositom
					$am = $total_price - $dep;
					$order->setAmount($am);
					$order->save();
					
					$customer = new Customer($this->ws->getCustomer()->getId());
					$customer->setDeposit($customer->getDeposit() - $dep);
					$customer->save();
					$c_dep = $customer->getDeposit();
				OrderHistory::newHistory($customer->getId(), $order->getId(), ' Клиент использовал депозит ('.$order->getDeposit().') грн. ',
                'Осталось на депозите "' . $c_dep . '"');
				$no = '-';
				DepositHistory::newDepositHistory($customer->getId(), $customer->getId(), $no, $order->getDeposit(), $order->getId());

						$deposit = $_SESSION['deposit'];
						unset($_SESSION['deposit']);
				
				}
		if($this->ws->getCustomer()->getBonus() > 0 and $order->getAmount() >= Config::findByCode('min_sum_bonus')->getValue()){
				$order->setBonus($this->ws->getCustomer()->getBonus());
				$customer = new Customer($this->ws->getCustomer()->getId());
				$customer->setBonus(0);
				$customer->save();
				OrderHistory::newHistory($this->ws->getCustomer()->getId(), $order->getId(), ' Клиент использовал бонус ('.$order->getBonus().') грн. ',
                ' ');
				$bonus = true;
				}
					$order->save();
					
					
					$payment_method_id = $info['payment_method_id'];// dlya onlayn oplat

					
					if (!$order->getId()) {$this->_redir('basket');}
					
					$utime = date("Y-m-d H:i:s");
					$q=" SELECT * FROM
							red_events
							JOIN red_event_customers
							on red_events.id = red_event_customers.event_id
						WHERE
							red_event_customers.status = 1
							AND red_events.publick = 1
							AND red_event_customers.customer_id = ".$this->ws->getCustomer()->getId()."
							AND red_events.start <= '".date('Y-m-d')."'
							AND red_events.finish >= '".date('Y-m-d')."'
							AND red_events.disposable = 1
							AND red_event_customers.st <= 2
							AND red_event_customers.end_time > '".$utime."'
							
					";//AND red_event_customers.session_id = '".session_id()."'
					$events = wsActiveRecord::useStatic('EventCustomer')->findByQuery($q);
					
					if($events->at(0)){
					$event_skidka_klient = $events->at(0)->getDiscont();
					$event_skidka_klient_id = $events->at(0)->getEventId();
					//$order->setEventSkidka($events->at(0)->getDiscont());
					//$order->setEventId($events->at(0)->getId());
					if($event_skidka_klient > 0){
					$this->view->event = $event_skidka_klient;
						$events->at(0)->setSt(5);
						$events->at(0)->save();
						}
						
					}else{
					$event_skidka_klient = 0;
					$event_skidka_klient_id = 0;
					}

					foreach ($this->basket_articles as $article) {
						$item = new Shoparticles($article['id']);
						$itemcs = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article['id'], 'id_size' => $article['size'], 'id_color' => $article['color']));
						if ($itemcs->getCount() > 0) {
							$item->setStock($item->getStock() - $article['count']);
							$item->save();

							$itemcs->setCount($itemcs->getCount() - $article['count']);
							$itemcs->save();
							$a = new Shoporderarticles();
							$a->setOrderId($order->getId());
							$data = $article;
							$data['article_id'] = $data['id'];
							unset($data['id']);
							$a->import($data);
							$a->setArtikul(trim($article['artikul']));
							$a->setOldPrice($item->getOldPrice());
							$s = Skidki::getActiv($item->getId());
							$c = Skidki::getActivCat($item->getCategoryId(), $item->getDopCatId());
							if(@$c){
							$a->setEventSkidka($c->getValue());
								$a->setEventId($c->getId());
							}
							if(@$s){
								$a->setEventSkidka($s->getValue()+$event_skidka_klient);
								$a->setEventId($s->getId()+$event_skidka_klient);
							}else{
							$a->setEventSkidka($event_skidka_klient);
								$a->setEventId($event_skidka_klient_id);
							}
							
							$a->save();
						}else {
							$article['count'] = 0;
							$article['title'] = $article['title'] . ' (нет на складе)';
							$a = new Shoporderarticles();
							$a->setOrderId($order->getId());
							$data = $article;
							$data['article_id'] = $data['id'];
							unset($data['id']);
							$a->import($data);
							$s = Skidki::getActiv($item->getId());
							$c = Skidki::getActivCat($item->getCategoryId(), $item->getDopCatId());
							if(@$s){
								$a->setEventSkidka($s->getValue());
								$a->setEventId($s->getId());
							}
							if(@$c){
								$a->setEventSkidka($c->getValue());
								$a->setEventId($c->getId());
							}
							
							$a->save();
						}
					}

					$this->basket = $this->view->basket = $_SESSION['basket'] = array();
					$this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'] = array();
					$this->basket_options = $this->view->basket_options = $_SESSION['basket_options'] = array();

					$order = wsActiveRecord::useStatic('Shoporders')->findById($order->getId());
					//meestexpres
					if($order->getDeliveryTypeId() == 8 or $order->getDeliveryTypeId() == 16){
					/*if(in_array($payment_method_id, array(2, 4, 5, 6))) {
					$pay_type = 0;
					}else{
					$pay_type = 1;
					}*/
					/*$newmeest = Shopordersmeestexpres::newPost(
$info['s_service'],
 $order->getId(),
 $info['s_city_mist_id'],
 @$info['s_branch_id'] ? @$info['s_branch_id'] : '',
 @$info['s_street_id'] ? $info['s_street_id'] : '',
 @$_SESSION['massa_post']
 );*/
	$new_np = Shopordersmeestexpres::newOrderNp($order->getId(), $info['cityx'], $info['sklad_np']);				
					
 $order->setMeestId($new_np);		
					}
					//exit meestexpres
					
					if($order->getBonus() > 0){$bonus = true;}

					$this->set_customer($order);
					
						$order->reCalculate(true, $bonus);
					

					$basket = $order->getArticles()->export();

					list($articles, $options) = $this->createBasketList($basket, $order->getDeliveryCost());
					$this->view->articles = $articles;
					$this->view->deposit = $deposit;
					$this->view->options = $options;
					$this->view->order = $order;
						
					if(!$this->ws->getCustomer()->isBlockEmail()) {
					$subject = Config::findByCode('email_order_subject')->getValue();
					$msg = $this->render('email/basket.tpl.php');
					SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg); 
					//MailerNew::getInstance()->sendToEmail($order->getEmail(), $order->getName(), $subject, $msg);
					}
					

					if ($order->getId()) {
						$order = new Shoporders($order->getId());
						
						$order->reCalculate(true, $bonus);
						
						
						
					if($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->getTelegram() != NULL){
	$message = 'Ваш заказ № '.$order->getId().' оформлен. Сумма к оплате '.$order->calculateOrderPrice2(true, true, true, $bonus).' грн. Телефон (044) 224-40-00';
	$this->sendMessageTelegram($this->ws->getCustomer()->getTelegram(), $message);
	}else{
				$phone = Number::clearPhone($order->getTelephone());
						include_once('smsclub.class.php');
						$sms = new SMSClub(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue());
						//$sender = ;
						$user = $sms->sendSMS(Config::findByCode('sms_alphaname')->getValue(), $phone, $this->trans->get('Vash zakaz').' № ' . $order->getId() . ' '.$this->trans->get('Summa').' ' . $order->calculateOrderPrice2(true, true, true,  $bonus) . ' grn. tel. (044) 224-40-00');
						wsLog::add('SMS to user: ' . $sms->receiveSMS($user), 'SMS_' . $sms->receiveSMS($user));
						}
//}

						if (!$order->getCustomerId()) {
							$usr = Customer::findByUsername($order->getEmail());
							if ($usr->getId()) {
								$order->setCustomerId($usr->getId());
								$order->save();
							}
						}
						/////

					if($order->getKuponPrice() > 0 and $order->getKupon() != null){
						$customer = new Customer($this->ws->getCustomer()->getId());
				OrderHistory::newHistory($customer->getId(), $order->getId(), 'Использовано скидку (по коду) - ('.$order->getKuponPrice().') %. ',
                'Код скидки "' . $order->getKupon() . '"'); 
						}
//////
						
					}
					

	
					//dla finishnoy stranicu
					
					$_SESSION['order'] = array();
					$_SESSION['order']['id'] = $order->getId();
					//$_SESSION['order']['amount'] = Shoparticles::showPrice($order->getAmount());
					//$_SESSION['order']['delivery_type_id'] = $order->getDeliveryTypeId();
					//$_SESSION['order']['delivery_cost'] = $order->getDeliveryCost();
					//$_SESSION['order']['payment_method_id'] = $order->getPaymentMethodId();	
					$_SESSION['list_articles_order'] = $order->getListArticlesOrder();
					//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if($order->getPaymentMethodId() == 7){
			LiqPayHistory::newHistory($order->getId(), 1, '');
			}
					if (in_array($payment_method_id, array(4, 5, 6))) {
						if ($payment_method_id == 4) {
							$paymaster = 21;
						}
						if ($payment_method_id == 5) {
							$paymaster = 1;
						}
						if ($payment_method_id == 6) {
							$paymaster = 49;
						}

						$order_id = $order->getId();
						$order_amount = $order->calculateOrderPrice2(true, false, true, $bonus);

						$pay_data['LMI_MERCHANT_ID'] = 2285;
						$pay_data['LMI_PAYMENT_AMOUNT'] = $order_amount;//str_replace(" ","",$order_amount);
						$pay_data['LMI_PAYMENT_NO'] = $order_id;
						$pay_data['LMI_PAYMENT_DESC'] = 'Оплата за заказ № '.$order_id;
		/*				
		1 = Webmoney
		6 = MoneXy
		12 = EasyPay
		15 = NSMEP
		17 = Webmoney Terminal
		21 = PaymasterCard
		49 = Приват 24
		19 = LiqPay
		23 = Київстар
		2 = x20 WebMoney моб. платежи
		22 - Терминалы через кнопку пеймастера

		18 = test
		*/
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
						$LMI_HASH				= mysql_real_escape_string($pay_data['LMI_HASH']);
						
	wsActiveRecord::query("INSERT INTO pay_send ( LMI_MERCHANT_ID, LMI_PAYMENT_AMOUNT, LMI_PAYMENT_NO, LMI_PAYMENT_DESC, LMI_PAYMENT_SYSTEM, LMI_SIM_MODE, LMI_HASH, pid ) VALUES ( '".$LMI_MERCHANT_ID."', '".$LMI_PAYMENT_AMOUNT."', '".$LMI_PAYMENT_NO."', '".$LMI_PAYMENT_DESC."', '".$LMI_PAYMENT_SYSTEM."', '".$LMI_SIM_MODE."', '".$LMI_HASH."', '".$payment_method_id."' ) " );
						
						//$this->view->pay_data = $pay_data;
						$_SESSION['pay'] = $pay_data;
						//echo $this->render('payment/index.tpl.php');
					}//else{
					//$this->_redir('ordersucces');//finish
					//echo $this->render('shop/ordersucces.tpl.php');
					//}
						
					$post_order = true;
						$this->_redir('ordersucces');//finish
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
            $this->basket_contacts = $this->view->basket_contacts = $_SESSION['basket_contacts'];
        }
		
		
		$this->view->info = $info;
		$this->view->err_m = $err_m;
		$this->view->errors = $errors;
		
if(!$post_order) echo $this->render('developer/basket-step-test.tpl.php');
	
}
//2517

}
