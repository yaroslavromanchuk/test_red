<?php

class BrandGryde extends wsActiveRecord
{
    protected $_table = 'red_brands_greyd';

    protected function _defineRelations()
    {

        $this->_relations = [
             'brands' => [
                'type' => 'hasMany',
                'class' => 'Brand',
                'field_foreign' => 'brand_id'
                
            ]
        ];


    }
   
    
}

