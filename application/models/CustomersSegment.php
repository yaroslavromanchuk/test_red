<?php
class CustomersSegment extends wsActiveRecord
{
    
	protected $_table = 'ws_customers_segment';
	protected $_orderby = array('id' => 'ASC');
	
        
    protected function _defineRelations()
    {
            $this->_relations = [
                'segment_customers' => [
                'type' => 'hasMany',
                'class' => self::$_customer_class,
                'field_foreign' => 'segment_id',
                'orderby' => array('id' => 'ASC'),
                'onDelete' => 'delete'
                    ],
                'segment_mailing' => [
                'type' => 'hasMany',
                'class' => 'Emailpost',
                'field_foreign' => 'segment_customer',
                'orderby' => array('id' => 'ASC'),
                'onDelete' => 'delete'
                    ],
            ];
    }
    
    public function getCountCustomer(){
        $sql = "SELECT COUNT(  `ws_customers`.`id` ) AS  `ctn` from `ws_customers` WHERE  `ws_customers`.`segment_id` =".$this->id;
       return wsActiveRecord::useStatic('Customer')->findByQuery($sql)->at(0)->ctn;
    }
    
    /**
     * 
     * @param type $count - false не отображать количество подписчиков
     * @return type
     */
    public static function getListCustomersSegmentType($count = false){
        
        if($count){
            $sql = "SELECT  `ws_customers_segment` . * , COUNT(  `ws_customers`.`id` ) AS  `ctn` 
FROM  `ws_customers_segment` 
INNER JOIN  `ws_customers` ON  `ws_customers_segment`.`id` =  `ws_customers`.`segment_id` 
WHERE `ws_customers_segment`.`active` = 1
GROUP BY  `ws_customers_segment`.`id` ";
            return wsActiveRecord::useStatic('CustomersSegment')->findByQuery($sql);
        }else{
            return wsActiveRecord::useStatic('CustomersSegment')->findAll(['active'=>1]);
        }
        
    }
 

}
