<?php



/**
 * JustIn API Class.
 * Delivery service.
 *
 * @author webvision
 *
 * @see https://github.com/Loremipsumdolorsit/justin
 *
 * @license MIT
 */
class JustinClass
{
    /**
     * API login
     *
     * @var string
     */
    protected $login = 'RedUA1';

    /**
     * API pass
     *
     * @var string
     */
    protected $pass = 'OsiMaNaT';

    /**
     * API account key
     *
     * @var string
     */
    protected $key = '08d0c6b5-1d89-11ea-abe1-0050569b41a9';

    /**
     * Response result lang
     *
     * @var string
     */
    protected $lang = 'UA';

    /**
     * Request protocol
     *
     * @var string
     */
    protected $apiProtocol = 'https://';

    /**
     * API host
     *
     * @var string
     */
    protected $apiHost = 'api.justin.ua';

    /**
     * API urn for 'catalog' and 'request' types of request
     *
     * @var string
     */
    protected $apiUrnRequest = '/justin_pms/hs/v2/runRequest';

    /**
     * API urn for make order request
     *
     * @var string
     */
    protected $apiUrnOrder = '/api_pms/hs/api/v1/documents/';

    /**
     * Static http auth login
     *
     * @var string
     */
    protected $apiStaticBasicLogin = 'Exchange';

    /**
     * Static http auth pass
     *
     * @var string
     */
    protected $apiStaticBasicPass = 'Exchange';

    /**
     * Request method
     *
     * @var string
     */
    protected $apiConnectionMethod = 'curl';

    /**
     * Enable/disable throwing errors
     *
     * @var bool
     */
    protected $throwErrors = false;

    /**
     * JustIn constructor.
     *
     * @param string $login
     * @param string $pass
     * @param string $key
     * @param string $lang
     * @param bool $throwErrors
     * @param string $connectionType
     */
    public function __construct($login, $pass, $key = '', $lang = 'UA', $throwErrors = false, $connectionType = 'curl')
    {
        $this->login = $login;
        $this->pass = $pass;
        $this->key = $key;
        $this->lang = strtoupper($lang);
        $this->throwErrors = (boolean)$throwErrors;
        $this->apiConnectionMethod = $connectionType;
    }

    /**
     * Create signature
     *
     * @return string
     */
    private function getSign()
    {
        return sha1($this->pass . ':' . date('Y-m-d'));
    }

    /**
     * Prepare data to request
     *
     * @param array $data
     * @return string
     */
    private function pack(array $data)
    {
        return json_encode($data, true);
    }

    /**
     * Prepare data from response
     *
     * @param $json
     * @return mixed
     */
    private function unpack($json)
    {
        return json_decode($json, true);
    }

    /**
     * Choose request method and execute send function
     *
     * @param $urn
     * @param $data
     * @return mixed
     */
    private function sendRequest($urn, $data)
    {
        $apiUri = $this->apiProtocol . $this->apiHost . $urn;

        if ($this->apiConnectionMethod === 'curl' && function_exists('curl_init')) {
            return $this->sendRequestCurl($apiUri, $data);
        } else {
            return $this->sendRequestHttp($apiUri, $data);
        }
    }

    /**
     * Send request via 'curl'
     *
     * @param $apiUri
     * @param $data
     * @return mixed
     */
    private function sendRequestCurl($apiUri, $data)
    {
        $parametr = $this->pack($data);
       // l($parametr);
        $curl = curl_init();
        curl_setopt_array($curl, array(
  CURLOPT_URL => $apiUri,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $parametr,
  CURLOPT_HTTPHEADER => array(
    "Accept: application/json",
    "Authorization: Basic {base64_encode('$this->apiStaticBasicLogin:$this->apiStaticBasicPass')}",
    "Content-Type: application/json"
  ),
));
        $result = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
if ($err) {
  echo "cURL Error #:" . $err;
} else {
   return $this->unpack($result);
}
      
    }

    /**
     * Send request via 'file_get_contents' function
     *
     * @param $apiUri
     * @param $data
     * @return mixed
     */
    private function sendRequestHttp($apiUri, $data)
    {
        $auth = 'Authorization: Basic ' . base64_encode($this->apiStaticBasicLogin . ':' . $this->apiStaticBasicPass);

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/json\r\n" . $auth,
                'content' => $this->pack($data),
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($apiUri, false, $context);

        return $this->unpack($result);
    }

    /**
     * Common request data template
     *
     * @param $type
     * @param $methodName
     * @param string $requestType
     * @param array $filter
     * @param int $top
     * @param array $params
     * @return array
     */
    private function prepareRequestDataTemplate($type, $methodName, $requestType = 'getData', $filter = [], $top = 0, $params = [])
    {
        $template = [
            'keyAccount' => $this->login,
            'sign' => $this->getSign(),
            'request' => $requestType,
            'type' => $type,
            'name' => $methodName,
            'language' => $this->lang
        ];

        if ($filter) {
            $template['filter'] = $filter;
        }
        if ($top) {
            $template['TOP'] = $top;
        }
        if ($params) {
            $template['params'] = $params;
        }//else{
          //  $template['params'] = ["language" => $this->lang];
        //}

        return $template;
    }

    /**
     * Send request to get account key for user
     *
     * @return mixed
     */
    public function getApiKey()
    {
        $result = $this->sendRequest(
            $this->apiUrnRequest,
            $this->prepareRequestDataTemplate(
                'infoData',
                'getSenderUUID',
                'getData',
                [[
                    'name' => 'login',
                    'comparison' => 'equal',
                    'leftValue' => $this->login
                ]],
                1
            )
        );

        if ($result['data'][0]['fields']['counterpart']['uuid']) {
            return $result['data'][0]['fields']['counterpart']['uuid'];
        }

        return false;
    }

    /**
     * Request regions data
     * Filter
     * [
     *      'name' => 'code | descr', filtered field name
     *      'comparison' => 'equal | not | less | more | in | between | not in | less or equal | more or equal | like',
     *      'leftvalue' => '', left limit
     *      'rightvalue' => '', right limit
     * ]
     *
     * @param array $filter
     * @param int $top
     * @return mixed
     */
    public function getRegions($filter = [], $top = 10)
    {
        return $this->sendRequest(
            $this->apiUrnRequest,
            $this->prepareRequestDataTemplate(
                'catalog',
                'cat_Region',
                'getData',
                $filter,
                $top
            )
        );
    }

    /**
     * Request areas regions data
     * Filter
     * [
     *      'name' => 'code | descr', filtered field name
     *      'comparison' => 'equal | not | less | more | in | between | not in | less or equal | more or equal | like',
     *      'leftvalue' => '', left limit
     *      'rightvalue' => '', right limit
     * ]
     *
     * @param $filter
     * @param int $top
     * @return mixed
     */
    public function getAreasRegion($filter = [], $top = 0)
    {
        return $this->sendRequest(
            $this->apiUrnRequest,
            $this->prepareRequestDataTemplate(
                'catalog',
                'cat_areasRegion',
                'getData',
                $filter,
                $top
            )
        );
    }

    /**
     * Request cities data
     * Filter
     * [
     *      'name' => 'code | descr', filtered field name
     *      'comparison' => 'equal | not | less | more | in | between | not in | less or equal | more or equal | like',
     *      'leftvalue' => '', left limit
     *      'rightvalue' => '', right limit
     * ]
     *
     * @param $filter
     * @param int $top
     * @return mixed
     */
    public function getCities($filter = [], $top = 0)
    {
        return $this->sendRequest(
            $this->apiUrnRequest,
            $this->prepareRequestDataTemplate(
                'catalog',
                'cat_Cities',
                'getData',
                $filter,
                $top
            )
        );
    }

    /**
     * Request city regions data
     * Filter
     * [
     *      'name' => 'code | descr', filtered field name
     *      'comparison' => 'equal | not | less | more | in | between | not in | less or equal | more or equal | like',
     *      'leftvalue' => '', left limit
     *      'rightvalue' => '', right limit
     * ]
     *
     * @param $filter
     * @param int $top
     * @return mixed
     */
    public function getCityRegions($filter = [], $top = 0)
    {
        return $this->sendRequest(
            $this->apiUrnRequest,
            $this->prepareRequestDataTemplate(
                'catalog',
                'cat_cityRegions',
                'getData',
                $filter,
                $top
            )
        );
    }

    /**
     * Request city streets data
     * Filter
     * [
     *      'name' => 'code | descr', filtered field name
     *      'comparison' => 'equal | not | less | more | in | between | not in | less or equal | more or equal | like',
     *      'leftvalue' => '', left limit
     *      'rightvalue' => '', right limit
     * ]
     *
     * @param $filter
     * @param int $top
     * @return mixed
     */
    public function getCityStreets($filter = [], $top = 0)
    {
        return $this->sendRequest(
            $this->apiUrnRequest,
            $this->prepareRequestDataTemplate(
                'catalog',
                'cat_cityStreets',
                'getData',
                $filter,
                $top
            )
        );
    }

    /**
     * Request departments data
     * Filter
     * [
     *      'name' => 'code | descr', filtered field name
     *      'comparison' => 'equal | not | less | more | in | between | not in | less or equal | more or equal | like',
     *      'leftvalue' => '', left limit
     *      'rightvalue' => '', right limit
     * ]
     *
     * @param $filter
     * @param int $top
     * @return mixed
     */
    public function getDepartments($filter = [], $top = 0)
    {
        return $this->sendRequest(
            $this->apiUrnRequest,
            $this->prepareRequestDataTemplate(
                'request',
                'req_DepartmentsLang',
                'getData',
                $filter,
                $top,
                ['language' => $this->lang]
            )
        );
    }

    /**
     * Sending request to make order (create waybill)
     *
     * @param array $data Order data:
     *                    number => Int, internal order id
     *                    date => String, date of order create format('Ymd' | 'Y-m-d')
     *                    sender_city_id => String, external city id, use $this->getCities()
     *                    sender_company => String, sender company name
     *                    sender_contact => String, sender full name
     *                    sender_phone => String, sender phone
     *                    sender_pick_up_address => String, address fo cargo pickup
     *                    pick_up_is_required => Boolean, is need cargo pickup on address
     *                    sender_branch => String, sender external department number, use $this->getDepartments()
     *                    receiver => String, recipient full name
     *                    receiver_contact => String, recipient full name (if another person receive cargo)
     *                    receiver_phone => String, recipient phone number
     *                    count_cargo_places => Int, count places
     *                    branch => String, recipient extend department number, use $this->getDepartments()
     *                    weight => Float, cargo weight
     *                    volume => Float, cargo volume
     *                    declared_cost => Int, declared cargo price
     *                    delivery_amount => Int, money redelivery (param delivery_payment_is_required)
     *                    redelivery_amount => Int, redelivery tax
     *                    order_amount => Int, all sum
     *                    redelivery_payment_is_required => Boolean, is need redelivery tax
     *                    redelivery_payment_payer => Int, tax payer (0 - sender | 1 - recipient)
     *                    delivery_payment_is_required => Boolean, is delivery payment required
     *                    delivery_payment_payer => Int, delivery payer (0 - sender | 1 - recipient)
     *                    order_payment_is_required => Boolean, is order payment required
     *
     * @return mixed
     */
    public function createOrder($data)
    {
        return $this->sendRequest(
            $this->apiUrnOrder.'orders',
            [
                'api_key' => $this->key ?: $this->getApiKey(),
                'data' => $data
            ]
        );
    }
    public function cancelOrder($number)
    {
        return $this->sendRequest(
            $this->apiUrnOrder.'orders_cancel',
            [
                'api_key' => $this->key ?: $this->getApiKey(),
                'number' => $number
            ]
        );
    }
    public function getOrderInfo($number){
         return $this->sendRequest(
            $this->apiUrnOrder.'getOrderInfo',
            [
                'api_key' => $this->key ?: $this->getApiKey(),
                'order_number' => $number
            ]
        );
    }
    /**
     * 
     * @param type $orders - массив ид заказов
     * @return type
     */
    public function getReestrAdd($orders){
         return $this->sendRequest(
            $this->apiUrnOrder.'pickup/add',
            [
                'api_key' => $this->key ?: $this->getApiKey(),
                'data' => [
                    'number' => "",
                    'date' => date('Y-m-d'),
                    'orders_number' => $orders
                ]
            ]
        );
    }
    public function getReestrInfo($date){
         return $this->sendRequest(
            $this->apiUrnOrder.'pickup/get',
            [
                'api_key' => $this->key ?: $this->getApiKey(),
                'data' => [
                    'date' => $date
                ]
            ]
        );
    }
    public function getReestrDell($number, $orders){
         return $this->sendRequest(
            $this->apiUrnOrder.'pickup/del',
            [
                'api_key' => $this->key ?: $this->getApiKey(),
                'data' => [
                    'number' => $number,
                    'date' => date('Y-m-d'),
                    'orders_number' => $orders
                ]
            ]
        );
    }
    
    
    public function getListOrders($date = '')
    {
        if(!$date){ $date = date('Y-m-d'); }
        return $this->sendRequest(
            $this->apiUrnOrder.'getListOrders',
            [
                'api_key' => $this->key ?: $this->getApiKey(),
                'period' => $date
            ]
        );
    }

    /**
     * Request statuses list data
     * Filter
     * [
     *      'name' => 'code | descr', filtered field name
     *      'comparison' => 'equal | not | less | more | in | between | not in | less or equal | more or equal | like',
     *      'leftvalue' => '', left limit
     *      'rightvalue' => '', right limit
     * ]
     *
     * @param $filter
     * @param int $top
     * @return mixed
     */
    public function getStatusesList($filter = [], $top = 0)
    {
        return $this->sendRequest(
            $this->apiUrnRequest,
            $this->prepareRequestDataTemplate(
                'catalog',
                'orderStatuses',
                'getData',
                $filter,
                $top
            )
        );
    }

    /**
     * Request order status history data
     * Filter
     * [
     *      'name' => 'code | descr', filtered field name
     *      'comparison' => 'equal | not | less | more | in | between | not in | less or equal | more or equal | like',
     *      'leftvalue' => '', left limit
     *      'rightvalue' => '', right limit
     * ]
     *
     * @param $filter
     * @param int $top
     * @return mixed
     */
    public function getOrderStatusesHistory($filter = [], $top = 0)
    {
        return $this->sendRequest(
            $this->apiUrnRequest,
            $this->prepareRequestDataTemplate(
                'request',
                'getOrderStatusesHistoryF',
                'getData',
                $filter,
                $top
            )
        );
    }
    public function getOrderStatuses($filter = [], $top = 0)
    {
        return $this->sendRequest(
            $this->apiUrnRequest,
            $this->prepareRequestDataTemplate(
                'request',
                'getOrderStatusesHistory',
                'getData',
                $filter,
                $top
            )
        );
    }
    
    public function getSenderUUIDWithSubordinates($filter = [], $top = 0)
    {
        return $this->sendRequest(
            $this->apiUrnRequest,
            $this->prepareRequestDataTemplate(
                'request',
                'getSenderUUIDWithSubordinates',
                'getData',
                $filter,
                    $top
            )
        );
    }
    

    /**
     * Get link to order stickers with sender/recipient name
     *
     * @param $orderNumber
     * @return string
     */
    public function getOrderStickerLink($orderNumber)
    {
        return $this->apiProtocol . $this->apiHost
            . "/pms/hs/api/v1/printSticker/order?order_number={$orderNumber}&api_key={$this->key}";
    }

    /**
     * Get link to order stickers with sender/recipient contact
     *
     * @param $orderNumber
     * @return string
     */
    public function getOrderStickerLinkWithContact($orderNumber)
    {
        return $this->apiProtocol . $this->apiHost
            . "/pms/hs/api/v1/printStickerWithContactPerson/order?order_number={$orderNumber}&api_key={$this->key}";
    }
}
