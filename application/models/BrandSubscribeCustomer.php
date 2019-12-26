<?php

class BrandSubscribeCustomer extends wsActiveRecord
{
    protected $_table = 'red_brands_subscribe_customer';

    protected function _defineRelations()
    {

        $this->_relations = array(
            'customer' => array(
                'type' => 'hasOne', //belongs to
                'class' => self::$_customer_class,
                'field' => 'customer_id'),
            'brandsub' => array(
                'type' => 'hasOne',
                'class' => 'Brand',
                'field' => 'brand_id'
            ),

        );


    }
   
    
}

