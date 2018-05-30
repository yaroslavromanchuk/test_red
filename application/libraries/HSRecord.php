<?php
class HSRecord{
    protected static $_instance;
    protected static $_hs = false;
    protected static $all_indexes = array();
    //
    private function __construct(){
        if (!extension_loaded('handlersocket')){
            throw new Exception('The handlersocket extension must be loaded for using session !');
        }
    }
    //
    private function __clone(){}
    //
    public static function getInstance() {
        // проверяем актуальность экземпляра
        if (null === self::$_instance) {
            // создаем новый экземпляр
            self::$_instance = new self();
        }
        // возвращаем созданный или существующий экземпляр
        return self::$_instance;
    }
    //
    public function init(){
        if (!HSRecord::$_hs){
            HSRecord::$_hs = new HandlerSocket('localhost', '9998');
        }
        return HSRecord::$_hs;
    }
    //
    public function openIndex($table, $fields, $field){
        HSRecord::getInstance()->init();
        //что-бы не открывать один и тот-же индекс несколько раз
        if(!isset(HSRecord::$all_indexes[$table])){
            HSRecord::$all_indexes[$table] = HSRecord::$_hs->openIndex(count(HSRecord::$all_indexes), Registry::get('db_name'), $table, 'PRIMARY', $fields);//PRIMARY
        }
        if (!HSRecord::$all_indexes[$table]){
            throw new Exception(HSRecord::$_hs->getError());
        }
        return HSRecord::$all_indexes[$table];
    }
    //
    public function getById($table, $fields, $field, $value){
        $fields_plain = is_array($fields)?implode(',', array_keys($fields)):$fields;
        //$timer1 = new DebugTimer(5);
        //$timer2 = new DebugTimer(5);
        $index = HSRecord::getInstance()->openIndex($table, $fields_plain, $field);
        //$timer1->stop();
        $result = HSRecord::$_hs->executeSingle(array_search($table, array_keys(HSRecord::$all_indexes)), '=', array($value), 1, 0, null, null);
        //$timer2->stop();d($timer1->getResults(),false);d($timer2->getResults());
        if (count($result)){
            $result = $result[0];
            return array_combine(array_keys($fields), $result);
        }else{
            return false;
        }
    }
}