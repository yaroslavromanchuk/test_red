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
//print_r($up->getAddress(19049636)); 
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
//print_r($up->getClientExternalId(14268));
//print_r($up->getEditClient('0f5862a8-8b71-4c90-996d-f72387ce55e8', '', '', '', '', 0, '+380958087963'));
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
//print_r($up->getListGroups());
//print_r($up->getShipments('6e1d6575-8e2c-4cae-bd41-9cf99f7eda42'));
          $ot = $up->getPostIndexFilter(25006);

$mas = [];
$res = false;
if(is_object($ot->Entry)){
    $flag = false;
    $res = $ot;
}else{
    $flag = true;
    $res = $ot->Entry;

}
print_r($ot);
            $i = 0;
            foreach ($res as $item) { 
                $mas[$i]['label'] = $item->TECHINDEX.' '.$item->PO_LONG;
                $mas[$i]['value'] = $item->TECHINDEX;
                $mas[$i]['id'] = $item->TECHINDEX;
                $mas[$i]['REGION'] = $item->REGION_RU;
                $mas[$i]['DISTRICT'] = $item->DISTRICT_RU;
                $mas[$i]['CITY'] = $item->PDCITY_RU;
                $mas[$i]['CITYID'] = $item->POCITY_ID;
                //if($flag) break;
                $i++;
            }
            print_r($mas);
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

public function newpageAction(){

  echo $this->render('developer/new_page.php');
}


public function basketcontactsAction(){
			
		//if ($this->ws->getCustomer()->isBan()) $this->_redir('ban');
      //  if ($this->ws->getCustomer()->isNoPayOrder()) $this->_redir('nopay');
	//	if (!$this->basket) $this->_redir('index');
		
		//if ($this->ws->getCustomer()->isAdmin() and !$this->ws->getCustomer()->hasRight('do_pay')) die('Нету прав на заказ');

		$info = array();
		$errors = array();
		$err_m = array();
		
		
		if($this->post->contact_valid){ //
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
		}elseif($this->post->delivery_type){ //
		//$result = array();
		$dop = array();
		$pay = array();
		$id = $this->post->id;
		$dely = wsActiveRecord::useStatic('DeliveryPayment')->findAll(array('delivery_id'=>(int)$id));
		if($dely){
		foreach($dely as $d){
                    if($d->payment->active == 1){
		$pay[$d->payment_id] = $d->payment->name;
                    }
		}
		}
		switch($id){
			case 9: $dop = array('street', 'hous', 'flat');
				break;
			case 8: $dop = array('city_np', 'sklad');
				break;
			case 3: $dop = array('pobeda');
				break;
			case 5: $dop = array('stroyka');
				break;
			case 4: $dop = array('index', 'obl', 'rayon', 'city', 'street', 'hous', 'flat');
				break;
		   }
		
		
		die(json_encode(array('pay'=>$pay, 'dop'=>$dop)));
		}elseif($this->post->payment_method){ //
		
		
		
		die();
		}
		
        if($_POST){
            l($_POST);
             exit();
        }
       
		
		
		$this->view->info = $info;
		$this->view->err_m = $err_m;
		$this->view->errors = $errors;
		
if(!$post_order) echo $this->render('developer/basket-step-test.tpl.php');
	
}
//2517

}
