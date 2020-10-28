<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CartItem
 *
 * @author PHP
 */
class CartItem extends wsActiveRecord
{
    protected $_table = 'ws_cart_item';
    protected $_orderby = array('id_cart' => 'ASC');
    protected function _defineRelations()
    {
         $this->_relations = [
             'cart' => [
                  'type' => 'hasOne',
                    'class' => self::$_shopping_cart_class,
                    'field' => 'id_cart'],
             'article' => [
                    'type' => 'hasOne',
                    'class' => self::$_shop_articles_class,
                    'field' => 'article_id'],
              'sizes' => [
                            'type'=>'hasOne',
                            'class'=>'Size',
                            'field'=>'size'
                            ], 
               'colors' => [
                            'type'=>'hasOne',
                            'class'=>'Shoparticlescolor',
                            'field'=>'color'
                            ]
         ];
    }
    //put your code here
    public function clearCartIthem(){
        if($this->id){
            //print_r($this);
            @$this->destroy();
            //die();
        }
        return false;
    }
}
