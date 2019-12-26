<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Brands
 *
 * @author PHP
 */
class FooterTextAdmin extends FooterText{
    
    
    public static function getList(){
         return  wsActiveRecord::useStatic('FooterText')->findAll([],['id'=>'DESC']);
    }
    public static function getEditId($id){
         return  wsActiveRecord::useStatic('FooterText')->findById((int)$id);
    }
    
    
}
