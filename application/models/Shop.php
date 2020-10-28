<?php
class Shop extends wsActiveRecord
{
	protected $_table = 'ws_shop';
   // protected $_orderby = array('sequence' => 'DESC');

        protected $_multilang = ['name' => 'name'];


	protected function _defineRelations()
    {
        $this->_relations = [
            'articles' => [
                 'type' => 'hasMany',
                 'class'=>self::$_shop_articles_class,
                'field_foreign' => 'shop_id',
                'orderby' => array('id' => 'ASC'),
                'onDelete' => 'delete'
            ],
            'orders' => [
                'type' => 'hasMany',
                'class'=> self::$_shop_orders_class,
                'field_foreign' => 'shop_id',
                'orderby' => array('id' => 'ASC'),
                'onDelete' => 'delete'
            ],
            'brands' =>[
                'type' => 'hasMany',
                'class' => 'Brand',
                'field_foreign' => 'shop_id',
                
            ]
        ];
    }
    public static function getNameId($id){
        return wsActiveRecord::useStatic('Shop')->findById((int)$id)->getName();
    }
	
}
