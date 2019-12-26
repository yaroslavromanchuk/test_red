<?php
class Subscriberstype extends wsActiveRecord
{
    
	protected $_table = 'subscribers_type';
	protected $_orderby = array('id' => 'ASC');
	
        
    protected function _defineRelations()
    {
            $this->_relations = [
                'sizes' => [
                'type' => 'hasMany',
                'class' => 'Subscriber',
                'field_foreign' => 'segment_id',
                'orderby' => array('id' => 'ASC'),
                'onDelete' => 'delete'
                    ],
            ];
    }
    
    public function getCountSubscriber(){
       return wsActiveRecord::useStatic('Subscriber')->findAll(['segment_id'=>$this->id, 'active'=>1])->count();
    }
    
    /**
     * 
     * @param type $count - false не отображать количество подписчиков
     * @return type
     */
    public static function getListSubscriberType($count = false){
        
        if($count){ 
            $sql = "SELECT  `subscribers_type` . * , COUNT(  `subscribers`.`id` ) AS  `ctn` 
FROM  `subscribers_type` 
INNER JOIN  `subscribers` ON  `subscribers_type`.`id` =  `subscribers`.`segment_id` 
WHERE `subscribers`.`active` = 1
GROUP BY  `subscribers_type`.`id` ";
            return wsActiveRecord::useStatic('Subscriberstype')->findByQuery($sql);
        }else{
            return wsActiveRecord::useStatic('Subscriberstype')->findAll(['active'=>1]);
        }
        
    }
 

}
