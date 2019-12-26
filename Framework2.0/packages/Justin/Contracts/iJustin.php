<?php




//require_once('../Justin.php');
/**
 *
 * Interface iJustin
 *
 * @package Justin\Contracts
 *
 */
interface iJustin
{
    /**
     *
     * INIT CLASS
     *
     * @param STRING $language
     *
     * @param BOOLEAN $sandbox
     *
     * @param STRING $version
     *
     * @param STRING $timezone
     *
     * @return OBJECT
     *
     */
    public function __construct($language = 'UA', $sandbox = false, $version = 'v2', $timeout = 60, $connect_timeout = 60, $timezone = 'Europe/Kiev');
    /**
     *
     * SET SANDBOX
     *
     * @param BOOLEAN sandbox
     *
     * @param STRING $type
     *
     * @return OBJECT
     *
     */
    public function setSandbox($sandbox, $type = 'justin_pms');
    /**
     *
     * SET LANGUAGE
     *
     * @param STRING $lang
     *
     * @return OBJECT
     *
     */
    public function setLanguage($language);
    /**
     *
     * SET AUTH LOGIN
     *
     * @param STRING $login
     *
     * @return OBJECT
     *
     */
    public function setAuthLogin($login);
    /**
     *
     * SET AUTH PASSWORD
     *
     * @param STRING $password
     *
     * @return OBJECT
     *
     */
    public function setAuthPassword($password);
    /**
     *
     * SET KEY
     *
     * @param STRING $key
     *
     * @return OBJECT
     *
     */
    public function setKey($key);
    /**
     *
     * SET LOGIN
     *
     * @param STRING $login
     *
     * @return OBJECT
     *
     */
    public function setLogin($login);
    /**
     *
     * SET PASSWORD
     *
     * @param STRING $password
     *
     * @return OBJECT
     *
     */
    public function setPassword($password);
    /**
     *
     * SET ADDRESS API
     *
     * @param STRING $address_api
     *
     * @return OBJECT
     *
     */
    public function setAddressApi($address_api);
    /**
     *
     * LIST REGIONS
     * СПИСОК ОБЛАСТЕЙ
     * ДАННІ ОБЛАСТЕЙ
     *
     * @param ARRAY $filter
     *
     * @param INTEGER $limit
     *
     * @return ARRAY
     *
     */
    public function listRegions($filter = [], $limit = 0);
    /**
     *
     * LIST AREAS REGION
     * СПИСОК ОБЛАСТНЫХ РАЙОНОВ
     * СПИСОК ОБЛАСНИХ РАЙОНІВ
     *
     * @param ARRAY $filter
     *
     * @param INTEGER $limit
     *
     * @return ARRAY
     *
     */
    public function listAreasRegion($filter = [], $limit = 0);
    /**
     *
     * LIST CITY REGIONS
     * СПИСОК РАЙОНОВ НАСЕЛЕННЫХ ПУНКТОВ
     * СПИСОК РАЙОНІВ НАСЕЛЕНИХ ПУНКТІВ
     *
     * @param ARRAY $filter
     *
     * @param INTEGER $limit
     *
     * @return ARRAY
     *
     */
    public function listCityRegion($filter = [], $limit = 0);
    /**
     *
     * LIST STREETS CITY
     * СПИСОК УЛИЦ ГОРОДА
     * СПИСОК ВУЛИЦЬ МІСТА
     *
     * @param ARRAY $filter
     *
     * @param INTEGER $limit
     *
     * @return ARRAY
     *
     */
    public function listStreetsCity($filter = [], $limit = 0);
    /**
     *
     * GET LIST TYPES BRACHES
     * ПОЛУЧИТЬ СПИСОК ТИПОВ ОТДЕЛЕНИЙ
     * ОТРИМАТИ СПИСОК ТИПІВ ВІДДІЛЕНЬ
     *
     * @param INTEGER $limit
     *
     * @return OBJECT
     *
     */
    public function branchTypes($limit = 0);
    /**
     *
     * GET BRANCH
     * ПОЛУЧИТЬ ИНФОРМАЦИЮ ПРО ОТДЕЛЕНИЕ
     * ОТРИМАТИ ІНФОРМАЦІЮ ПРО ВІДДІЛЕННЯ
     *
     * @return OBJECT
     *
     */
    public function getBranch($id);
    /**
     * OLD METHOD
     *
     * LIST DEPARTMENTS
     * СПИСОК ОТДЕЛЕНИЙ
     * СПИСОК ВІДДІЛЕНЬ
     *
     * @param ARRAY $filter
     *
     * @param INTEGER $limit
     *
     * @return ARRAY
     *
     */
    public function listDepartments($filter = [], $limit = 0);
    /**
     *
     * LIST DEPARTMENTS
     * СПИСОК ОТДЕЛЕНИЙ
     * СПИСОК ВІДДІЛЕНЬ
     *
     * @param ARRAY $filter
     *
     * @param INTEGER $limit
     *
     * @return ARRAY
     *
     */
    public function listDepartmentsLang($filter = [], $limit = 0);
    /**
     *
     * GET SCHEDULE BRANCHES
     * ПОЛУЧИТЬ РАСПИСАНИЕ РАБОТЫ ОТДЕЛЕНИЯ
     * ОТРИМАТИ РОЗКЛАД РОБОТИ ВІДДІЛЕННЯ
     *
     * @param ARRAY $filter
     *
     * @param INTEGER $limit
     *
     * @return OBJECT
     *
     */
    public function branchSchedule($filter = [], $limit = 0);
    /**
     *
     * GET NEAREST DEPARTMENT
     * ПОЛУЧИТЬ БЛИЖАЙШЕЕ ОТДЛЕНИЕ ПО АДРЕСУ
     * ОТРИМАТИ НАЙБЛИЖЧЕ ВІДДІЛЕННЯ ЗА АДРЕСОЮ
     *
     * @param STRING $address
     *
     * @return OBJECT
     *
     */
    public function getNeartDepartment($address);
    /**
     *
     * CREATE NEW ORDER
     * СОЗДАТЬ НОВЫЙ ЗАКАЗ НА ДОСТАВКУ
     * СТВОРИТИ НОВЕ ЗАМОВЛЕННЯ НА ДОСТАВКУ
     *
     * @param ARRAY $data
     *
     * @param STRING $version
     *
     * @return OBJECT
     *
     */
    public function createOrder($data = [], $version = 'v1');
    /**
     *
     * CANCEL ORDER
     * ОТМЕНА ЗАКАЗА
     * ВІДМІНА ЗАМОВЛЕННЯ
     *
     * @param STRING $number
     *
     * @param STRING $version
     *
     * @return OBJECT
     *
     */
    public function cancelOrder($number, $version = 'v1');
    /**
     *
     * LIST STATUSES
     * СПИСОК СТАСУСОВ ЗАКАЗА
     * СПИСОК СТАТУСІВ ЗАМОВЛЕНЬ
     *
     * @param ARRAY $filter
     *
     * @param INTEGER $limit
     *
     * @return ARRAY
     *
     */
    public function listStatuses($filter = [], $limit = 0);
    /**
     *
     * KEY SELLER
     * КЛЮЧ ТОРГОВЦА
     * КЛЮЧ ТОРГОВЦЯ
     *
     * @param ARRAY $filter
     *
     * @return OBJECT
     *
     */
    public function keySeller($filter = []);
    /**
     *
     * GET CURRENT STATUS
     * ПОЛУЧИТЬ ТЕКУЩИЙ СТАТУС ЗАКАЗА
     * ОТРИМАТИ ПОТОЧНИЙ СТАТУС ЗАМОВЛЕННЯ
     *
     * @param STRING $number
     *
     * @return OBJECT
     *
     */
    public function currentStatus($number);
    /**
     *
     * GET TRACKING HISTORY
     * ПОЛУЧИТЬ ИСТОРИЮ ДВИЖЕНИЯ ОТПРАВЛЕНИЯ
     * ОТРИМАТИ ІСТОРІЮ РУХУ ВІДПРАВЛЕННЯ
     *
     * @param STRING $number
     *
     * @return OBJECT
     *
     */
    public function trackingHistory($number);
    /**
     *
     * LOCALITIES
     *
     * ПОЛУЧИТЬ НАСЕЛЕННЫЕ ПУНКТЫ
     * ОТРИМАТИ НАСЕЛЕНІ ПУНКТИ
     *
     * @param STRING $action all | activity
     *
     * @return OBJECT
     *
     */
    public function localities($action = '');
    /**
     *
     * SERVICES
     *
     * ПОЛУЧИТЬ ИНФОРМАЦИЮ О ДОСТУПНЫХ СЕРВИСАХ
     * ОТРИМАТИ ІНФОРМАЦІЮ ПРО ДОСТУПНІ СЕРВІСИ
     *
     * @return OBJECT
     *
     */
    public function services();
    /**
     * OLD METHOD
     *
     * GET HISTORY STATUSES ORDERS
     * ПОЛУЧИТЬ ИСТОРИЮ СТАТУСОВ ЗАКАЗОВ
     * ОТРИМАТИ ІСТОРІЮ СТАТУСІВ ЗАМОВЛЕНЬ
     *
     * @param ARRAY $filter
     *
     * @param INTEGER $limit
     *
     * @return OBJECT
     *
     */
    public function getStatusHistory($filter = [], $limit = 0);
    /**
     *
     * GET HISTORY STATUSES ORDERS
     * ПОЛУЧИТЬ ИСТОРИЮ СТАТУСОВ ЗАКАЗОВ
     * ОТРИМАТИ ІСТОРІЮ СТАТУСІВ ЗАМОВЛЕНЬ
     *
     * @param ARRAY $filter
     *
     * @param INTEGER $limit
     *
     * @param STRING $senderID
     *
     * @return OBJECT
     *
     */
    public function getStatusHistoryF($filter, $limit = 0, $senderID = '');
    /**
     *
     * GET LIST ORDERS
     * ПОЛУЧИТЬ СПИСОК ЗАКАЗОВ ЗА УКАЗАННЫЙ ПЕРИОД
     * ОТРИМАТИ СПИСОК ЗАМОВЛЕНЬ ЗА ВКАЗАНИЙ ПЕРІОД
     *
     * @param STRING $date
     *
     * @param STRING $version
     *
     * @return OBJECT
     *
     */
    public function listOrders($date, $version = 'v1');
    /**
     *
     * GET ORDER INFO
     * ПОЛУЧИТЬ ИНФОРМАЦИЮ О ЗАКАЗЕ
     * ОТРИМАТИ ІНФОРМАЦІЮ ПРО ЗАМОВЛЕННЯ
     *
     * @param STRING $number
     *
     * @param STRING $version
     *
     * @return OBJECT
     *
     */
    public function orderInfo($number, $version = 'v1');
    /**
     *
     * STICKER PDF
     *
     * @param INTEGER $orderNumber
     *
     * @param BOOLEAN $show
     *
     * @param STRING $path
     *
     * @param INTEGER $type
     *
     * @param BOOLEAN $version
     *
     * @throws JustinFileException
     *
     * @return BOOLEAN
     *
     */
    public function createSticker($orderNumber, $show = false, $path = null, $type = 0, $version = 'v1');
}
