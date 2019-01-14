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
    
    
    public function Text($param){
         return  wsActiveRecord::useStatic('FooterText')->findFirst($param)->text;
        
    }
    
}
