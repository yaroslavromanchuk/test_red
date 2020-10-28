<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FooterText
 *
 * @author PHP
 */
class FooterText extends wsActiveRecord
{
    protected $_table = 'ws_filter_footer_text';
    protected function _defineRelations()
    {
         $this->_relations = [
            'category' => array(
                'type' => 'hasOne',
                'class' => self::$_shop_categories_class,
                'field' => 'category_id'),
            'color' => array(
                'type' => 'hasOne',
                'class' => 'Shoparticlescolor',
                'field' => 'color_id'),
            'size' => array(
                'type' => 'hasOne',
                'class' => 'Size',
                 'field' => 'size_id'),
             'brand' => array(
                'type' => 'hasOne',
                'class' => 'Brand',
                 'field' => 'brand_id'),
             'sezon' => array(
                'type' => 'hasOne',
                'class' => 'Shoparticlessezon',
                 'field' => 'sezon_id'),
         ];
    }
    
    
    
    
   static public function Text($param){
         return  wsActiveRecord::useStatic('FooterText')->findFirst($param)->text;
        
    }
   /* static public function Text($param){
        $text = new FooterText();
        return $text->getText($param);
    }*/
    
}
