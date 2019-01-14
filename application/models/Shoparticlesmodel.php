<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Shoparticlesmodel
 *
 * @author PHP
 */
class Shoparticlesmodel extends wsActiveRecord
{ 
     protected $_table = 'ws_articles_models';
    protected function _defineRelations()
    {
        $this->_relations = [
            'articles' => [
                'type' => 'hasMany',
		'class' => self::$_shop_articles_class,
		'field_foreign' => 'model_id'
            ]
        ];
    }
}
